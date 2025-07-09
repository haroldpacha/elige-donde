<?php

namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\PropertyTypeModel;
use App\Models\LocationModel;
use App\Models\AgentModel;
use App\Models\PropertyImageModel;

class Home extends BaseController
{
    protected $propertyModel;
    protected $propertyTypeModel;
    protected $locationModel;
    protected $agentModel;
    protected $propertyImageModel;

    public function __construct()
    {
        $this->propertyModel = new PropertyModel();
        $this->propertyTypeModel = new PropertyTypeModel();
        $this->locationModel = new LocationModel();
        $this->agentModel = new AgentModel();
        $this->propertyImageModel = new PropertyImageModel();
    }

    public function index()
    {
        // Obtener propiedades destacadas para la página principal
        $featuredProperties = $this->propertyModel->getFeaturedProperties(8);

        // Obtener imágenes principales para las propiedades
        if (!empty($featuredProperties)) {
            $propertyIds = array_column($featuredProperties, 'id');
            $mainImages = $this->propertyImageModel->getMainImagesForProperties($propertyIds);

            // Asociar imágenes con propiedades
            $imagesByProperty = [];
            foreach ($mainImages as $image) {
                $imagesByProperty[$image['property_id']] = $image;
            }

            foreach ($featuredProperties as &$property) {
                $property['main_image'] = $imagesByProperty[$property['id']] ?? null;
            }
        }

        // Obtener tipos de propiedades para el filtro
        $propertyTypes = $this->propertyTypeModel->getAllTypes();

        // Obtener ubicaciones para el filtro
        $locations = $this->locationModel->getAllLocations();

        // Obtener agentes destacados
        $topAgents = $this->agentModel->getTopAgents(6);

        // Estadísticas generales
        $stats = [
            'total_properties' => $this->propertyModel->where('is_active', 1)->countAllResults(),
            'properties_for_sale' => $this->propertyModel->where(['is_active' => 1, 'transaction_type' => 'venta'])->countAllResults(),
            'properties_for_rent' => $this->propertyModel->where(['is_active' => 1, 'transaction_type' => 'alquiler'])->countAllResults(),
            'total_agents' => $this->agentModel->where('is_active', 1)->countAllResults()
        ];

        $data = [
            'title' => 'RE/MAX Perú - Nadie en el mundo vende más bienes raíces que RE/MAX',
            'featured_properties' => $featuredProperties,
            'property_types' => $propertyTypes,
            'locations' => $locations,
            'top_agents' => $topAgents,
            'stats' => $stats
        ];

        return view('home/index', $data);
    }

    // Método para búsqueda rápida desde el formulario principal
    public function quickSearch()
    {
        $request = $this->request;

        $filters = [
            'transaction_type' => $request->getPost('transaction_type'),
            'property_type_id' => $request->getPost('property_type_id'),
            'location_id' => $request->getPost('location_id'),
            'price_min' => $request->getPost('price_min'),
            'price_max' => $request->getPost('price_max'),
            'currency' => $request->getPost('currency') ?: 'pen'
        ];

        // Filtrar valores vacíos
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Redireccionar a la página de búsqueda con los filtros
        $queryString = http_build_query($filters);
        return redirect()->to('/buscar-propiedades?' . $queryString);
    }

    // Método para obtener ubicaciones por AJAX
    public function getLocationsByDepartment($department = null)
    {
        if (!$department) {
            return $this->response->setJSON(['error' => 'Departamento requerido']);
        }

        $locations = $this->locationModel->getLocationsByDepartment($department);

        return $this->response->setJSON([
            'success' => true,
            'locations' => $locations
        ]);
    }

    // Método para contacto general
    public function contact()
    {
        if ($this->request->getMethod() === 'POST') {
            $validation = \Config\Services::validation();

            $rules = [
                'name' => 'required|min_length[2]|max_length[100]',
                'email' => 'required|valid_email',
                'phone' => 'permit_empty|max_length[20]',
                'subject' => 'required|max_length[200]',
                'message' => 'required|min_length[10]'
            ];

            if ($validation->setRules($rules)->run($this->request->getPost())) {
                // Procesar envío del formulario de contacto
                $contactData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'phone' => $this->request->getPost('phone'),
                    'subject' => $this->request->getPost('subject'),
                    'message' => $this->request->getPost('message'),
                    'created_at' => date('Y-m-d H:i:s')
                ];

                // Enviar email de notificación
                $this->sendContactNotification($contactData);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'errors' => $validation->getErrors()
                ]);
            }
        }

        $data = [
            'title' => 'Contacto - RE/MAX Perú',
            'agents' => $this->agentModel->getActiveAgents()
        ];

        return view('contact/index', $data);
    }

    private function sendContactNotification($contactData)
    {
        $email = \Config\Services::email();
        $email->setTo('info@remax-peru.com');
        $email->setFrom($contactData['email'], $contactData['name']);
        $email->setSubject('Contacto desde web: ' . $contactData['subject']);

        $message = "
            Nuevo mensaje de contacto desde la web:

            Nombre: {$contactData['name']}
            Email: {$contactData['email']}
            Teléfono: {$contactData['phone']}
            Asunto: {$contactData['subject']}

            Mensaje:
            {$contactData['message']}

            Fecha: {$contactData['created_at']}
        ";

        $email->setMessage($message);
        return $email->send();
    }
}
