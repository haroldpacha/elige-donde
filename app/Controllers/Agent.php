<?php

namespace App\Controllers;

use App\Models\AgentModel;
use App\Models\PropertyModel;
use App\Models\PropertyImageModel;
use App\Models\InquiryModel;

class Agent extends BaseController
{
    protected $agentModel;
    protected $propertyModel;
    protected $propertyImageModel;
    protected $inquiryModel;

    public function __construct()
    {
        $this->agentModel = new AgentModel();
        $this->propertyModel = new PropertyModel();
        $this->propertyImageModel = new PropertyImageModel();
        $this->inquiryModel = new InquiryModel();
    }

    // Listado de agentes
    public function index()
    {
        $request = $this->request;
        $search = $request->getGet('search');

        // Obtener agentes con búsqueda opcional
        $agents = $this->agentModel->searchAgents($search);

        // Agregar estadísticas a cada agente
        foreach ($agents as &$agent) {
            $agentStats = $this->agentModel->getAgentWithStats($agent['id']);
            $agent['total_properties'] = $agentStats['total_properties'];
            $agent['sale_properties'] = $agentStats['sale_properties'];
            $agent['rent_properties'] = $agentStats['rent_properties'];
            $agent['full_name'] = $agent['first_name'] . ' ' . $agent['last_name'];
        }

        $data = [
            'title' => 'Asesores Inmobiliarios - Elige Donde',
            'agents' => $agents,
            'search' => $search
        ];

        return view('agent/index', $data);
    }

    // Perfil de agente individual
    public function profile($agentId)
    {
        // Obtener información del agente con estadísticas
        $agent = $this->agentModel->getAgentWithStats($agentId);

        if (!$agent || !$agent['is_active']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Obtener propiedades del agente
        $agentProperties = $this->propertyModel->getPropertiesByAgent($agentId, 20);

        // Obtener imágenes principales para las propiedades
        if (!empty($agentProperties)) {
            $propertyIds = array_column($agentProperties, 'id');
            $mainImages = $this->propertyImageModel->getMainImagesForProperties($propertyIds);

            $imagesByProperty = [];
            foreach ($mainImages as $image) {
                $imagesByProperty[$image['property_id']] = $image;
            }

            foreach ($agentProperties as &$property) {
                $property['main_image'] = $imagesByProperty[$property['id']] ?? null;
            }
        }

        // Obtener consultas recientes del agente
        $recentInquiries = $this->inquiryModel->getInquiriesByAgent($agentId, 'new');

        // Estadísticas adicionales
        $monthlyStats = $this->getAgentMonthlyStats($agentId);

        $data = [
            'title' => $agent['full_name'] . ' - Asesor Elige Donde',
            'agent' => $agent,
            'properties' => $agentProperties,
            'recent_inquiries' => array_slice($recentInquiries, 0, 5),
            'monthly_stats' => $monthlyStats
        ];

        return view('agent/profile', $data);
    }

    // Contactar agente directamente
    public function contact()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $validation = \Config\Services::validation();

        $rules = [
            'agent_id' => 'required|is_natural_no_zero',
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty|max_length[20]',
            'subject' => 'required|max_length[200]',
            'message' => 'required|min_length[10]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        $agentId = $this->request->getPost('agent_id');
        $agent = $this->agentModel->find($agentId);

        if (!$agent) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Agente no encontrado'
            ]);
        }

        // Crear consulta general al agente
        $inquiryData = [
            'agent_id' => $agentId,
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'message' => $this->request->getPost('subject') . "\n\n" . $this->request->getPost('message'),
            'inquiry_type' => 'email'
        ];

        $inquiryId = $this->inquiryModel->createInquiry($inquiryData);

        if ($inquiryId) {
            // Enviar notificación directa al agente
            $this->sendDirectNotification($agent, $inquiryData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Mensaje enviado correctamente. El agente se pondrá en contacto contigo pronto.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al enviar el mensaje. Inténtalo nuevamente.'
            ]);
        }
    }

    // Estadísticas mensuales del agente
    private function getAgentMonthlyStats($agentId)
    {
        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        // Propiedades publicadas este mes
        $currentMonthProperties = $this->propertyModel->where('agent_id', $agentId)
                                                    ->where('is_active', 1)
                                                    ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                                                    ->countAllResults();

        // Consultas recibidas este mes
        $currentMonthInquiries = $this->inquiryModel->where('agent_id', $agentId)
                                                   ->where('DATE_FORMAT(created_at, "%Y-%m")', $currentMonth)
                                                   ->countAllResults();

        // Consultas del mes pasado para comparación
        $lastMonthInquiries = $this->inquiryModel->where('agent_id', $agentId)
                                                ->where('DATE_FORMAT(created_at, "%Y-%m")', $lastMonth)
                                                ->countAllResults();

        return [
            'current_month_properties' => $currentMonthProperties,
            'current_month_inquiries' => $currentMonthInquiries,
            'last_month_inquiries' => $lastMonthInquiries,
            'inquiry_growth' => $lastMonthInquiries > 0 ?
                round((($currentMonthInquiries - $lastMonthInquiries) / $lastMonthInquiries) * 100, 1) : 0
        ];
    }

    // Enviar notificación directa al agente
    private function sendDirectNotification($agent, $inquiryData)
    {
        $email = \Config\Services::email();
        $email->setTo($agent['email']);
        $email->setFrom($inquiryData['email'], $inquiryData['name']);
        $email->setSubject('Contacto directo desde Elige Donde');

        $message = "
            Has recibido un nuevo mensaje de contacto:

            Nombre: {$inquiryData['name']}
            Email: {$inquiryData['email']}
            Teléfono: {$inquiryData['phone']}

            Mensaje:
            {$inquiryData['message']}

            Puedes responder directamente a este email o contactar al cliente usando la información proporcionada.

            Saludos,
            Equipo Elige Donde
        ";

        $email->setMessage($message);
        return $email->send();
    }

    // Obtener propiedades del agente por AJAX
    public function getProperties($agentId)
    {
        $agent = $this->agentModel->find($agentId);

        if (!$agent) {
            return $this->response->setJSON(['error' => 'Agente no encontrado']);
        }

        $properties = $this->propertyModel->getPropertiesByAgent($agentId, 50);

        return $this->response->setJSON([
            'success' => true,
            'properties' => $properties
        ]);
    }
}
