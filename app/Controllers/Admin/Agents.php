<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AgentModel;
use App\Models\PropertyAgentModel;
use App\Models\InquiryModel;

class Agents extends BaseController
{
    protected $agentModel;
    protected $propertyAgentModel;
    protected $inquiryModel;

    public function __construct()
    {
        $this->agentModel = new AgentModel();
        $this->propertyAgentModel = new PropertyAgentModel();
        $this->inquiryModel = new InquiryModel();
    }

    // Check authentication
    private function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login')->with('error', 'Debes iniciar sesión para acceder al panel administrativo');
        }
        return true;
    }

    // List all agents
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        // Filters
        $filters = [
            'search' => $this->request->getGet('search'),
            'is_active' => $this->request->getGet('is_active'),
            'license_number' => $this->request->getGet('license_number')
        ];

        // Build query
        $builder = $this->agentModel->select('agents.*');

        // Apply filters
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                switch ($key) {
                    case 'search':
                        $builder->groupStart()
                                ->like('agents.first_name', $value)
                                ->orLike('agents.last_name', $value)
                                ->orLike('agents.email', $value)
                                ->orLike('agents.phone', $value)
                                ->orLike('agents.cell_phone', $value)
                                ->groupEnd();
                        break;
                    case 'is_active':
                        $builder->where('agents.is_active', $value === 'true' ? 1 : 0);
                        break;
                    case 'license_number':
                        $builder->where('agents.license_number IS NOT NULL');
                        break;
                }
            }
        }

        $totalResults = $builder->countAllResults(false);
        $agents = $builder->orderBy('agents.created_at', 'DESC')
                         ->findAll($perPage, $offset);

        // Get statistics for each agent
        foreach ($agents as &$agent) {
            $stats = $this->propertyAgentModel->getAgentStats($agent['id']);
            $agent['total_properties'] = $stats['total_properties'];
            $agent['primary_properties'] = $stats['primary_properties'];
            $agent['recent_inquiries'] = $this->inquiryModel->where('agent_id', $agent['id'])
                                                           ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                                                           ->countAllResults();
        }

        $data = [
            'title' => 'Gestión de Agentes - Admin RE/MAX Perú',
            'agents' => $agents,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($totalResults / $perPage),
                'total_results' => $totalResults,
                'per_page' => $perPage
            ]
        ];

        return view('admin/agents/index', $data);
    }

    // Show create form
    public function create()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $data = [
            'title' => 'Crear Nuevo Agente - Admin RE/MAX Perú'
        ];

        return view('admin/agents/create', $data);
    }

    // Store new agent
    public function store()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $validation = \Config\Services::validation();

        $rules = [
            'first_name' => 'required|max_length[50]',
            'last_name' => 'required|max_length[50]',
            'email' => 'required|valid_email|max_length[100]|is_unique[agents.email]',
            'phone' => 'permit_empty|max_length[20]',
            'cell_phone' => 'permit_empty|max_length[20]',
            'company' => 'permit_empty|max_length[100]',
            'license_number' => 'permit_empty|max_length[50]',
            'photo' => 'permit_empty|uploaded[photo]|max_size[photo,2048]|is_image[photo]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare agent data
        $agentData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'cell_phone' => $this->request->getPost('cell_phone'),
            'company' => $this->request->getPost('company') ?: 'RE/MAX CENTRAL REALTY',
            'license_number' => $this->request->getPost('license_number'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Handle photo upload
        $photoFile = $this->request->getFile('photo');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $photoName = $agentData['first_name'] . '_' . $agentData['last_name'] . '_' . time() . '.' . $photoFile->getExtension();
            $uploadPath = FCPATH . 'assets/images/agents/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($photoFile->move($uploadPath, $photoName)) {
                $agentData['photo'] = $photoName;
            }
        }

        if ($this->agentModel->insert($agentData)) {
            return redirect()->to('/admin/agentes')->with('success', 'Agente creado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al crear el agente');
        }
    }

    // Show edit form
    public function edit($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $agent = $this->agentModel->find($id);

        if (!$agent) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get agent statistics
        $stats = $this->propertyAgentModel->getAgentStats($id);
        $agent['stats'] = $stats;

        // Get recent properties
        $agent['recent_properties'] = $this->propertyAgentModel->getAgentProperties($id);

        // Get recent inquiries
        $agent['recent_inquiries'] = $this->inquiryModel->getInquiriesByAgent($id);

        $data = [
            'title' => 'Editar Agente - Admin RE/MAX Perú',
            'agent' => $agent
        ];

        return view('admin/agents/edit', $data);
    }

    // Update agent
    public function update($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $agent = $this->agentModel->find($id);

        if (!$agent) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();

        $rules = [
            'first_name' => 'required|max_length[50]',
            'last_name' => 'required|max_length[50]',
            'email' => "required|valid_email|max_length[100]|is_unique[agents.email,id,{$id}]",
            'phone' => 'permit_empty|max_length[20]',
            'cell_phone' => 'permit_empty|max_length[20]',
            'company' => 'permit_empty|max_length[100]',
            'license_number' => 'permit_empty|max_length[50]',
            'photo' => 'permit_empty|uploaded[photo]|max_size[photo,2048]|is_image[photo]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare update data
        $updateData = [
            'id' => $id,
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'cell_phone' => $this->request->getPost('cell_phone'),
            'company' => $this->request->getPost('company') ?: 'RE/MAX CENTRAL REALTY',
            'license_number' => $this->request->getPost('license_number'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        // Handle photo upload
        $photoFile = $this->request->getFile('photo');
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            // Delete old photo if exists
            if ($agent['photo']) {
                $oldPhotoPath = FCPATH . 'assets/images/agents/' . $agent['photo'];
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $photoName = $updateData['first_name'] . '_' . $updateData['last_name'] . '_' . time() . '.' . $photoFile->getExtension();
            $uploadPath = FCPATH . 'assets/images/agents/';

            if ($photoFile->move($uploadPath, $photoName)) {
                $updateData['photo'] = $photoName;
            }
        }

        if ($this->agentModel->update($id, $updateData)) {
            return redirect()->to('/admin/agentes')->with('success', 'Agente actualizado exitosamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el agente');
        }
    }

    // Delete agent
    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $agent = $this->agentModel->find($id);

        if (!$agent) {
            return $this->response->setJSON(['success' => false, 'message' => 'Agente no encontrado']);
        }

        // Check if agent has properties
        $hasProperties = $this->propertyAgentModel->where('agent_id', $id)->countAllResults();

        if ($hasProperties > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede eliminar el agente porque tiene propiedades asignadas'
            ]);
        }

        // Delete photo if exists
        if ($agent['photo']) {
            $photoPath = FCPATH . 'assets/images/agents/' . $agent['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        if ($this->agentModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Agente eliminado exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el agente']);
        }
    }

    // Toggle active status
    public function toggleActive($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $agent = $this->agentModel->find($id);

        if (!$agent) {
            return $this->response->setJSON(['success' => false, 'message' => 'Agente no encontrado']);
        }

        $newStatus = $agent['is_active'] ? 0 : 1;

        if ($this->agentModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'Agente activado' : 'Agente desactivado';
            return $this->response->setJSON(['success' => true, 'message' => $message, 'is_active' => $newStatus]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el estado']);
        }
    }

    // View agent details
    public function show($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $agent = $this->agentModel->getDetailedAgentStats($id);

        if (!$agent) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get agent properties
        $agent['properties'] = $this->propertyAgentModel->getAgentProperties($id);

        // Get agent inquiries
        $agent['inquiries'] = $this->inquiryModel->getInquiriesByAgent($id);

        $data = [
            'title' => $agent['first_name'] . ' ' . $agent['last_name'] . ' - Admin RE/MAX Perú',
            'agent' => $agent
        ];

        return view('admin/agents/show', $data);
    }

    // Delete photo
    public function deletePhoto($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $agent = $this->agentModel->find($id);

        if (!$agent) {
            return $this->response->setJSON(['success' => false, 'message' => 'Agente no encontrado']);
        }

        if ($agent['photo']) {
            $photoPath = FCPATH . 'assets/images/agents/' . $agent['photo'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }

            if ($this->agentModel->update($id, ['photo' => null])) {
                return $this->response->setJSON(['success' => true, 'message' => 'Foto eliminada exitosamente']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la foto']);
    }

    // Export agents to CSV
    public function export()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $agents = $this->agentModel->where('is_active', 1)->findAll();

        $csvData = [];
        $csvData[] = ['ID', 'Nombre', 'Apellido', 'Email', 'Teléfono', 'Celular', 'Empresa', 'Licencia', 'Fecha Creación'];

        foreach ($agents as $agent) {
            $csvData[] = [
                $agent['id'],
                $agent['first_name'],
                $agent['last_name'],
                $agent['email'],
                $agent['phone'],
                $agent['cell_phone'],
                $agent['company'],
                $agent['license_number'],
                $agent['created_at']
            ];
        }

        $filename = 'agentes_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        foreach ($csvData as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
