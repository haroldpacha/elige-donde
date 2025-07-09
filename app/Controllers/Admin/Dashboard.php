<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\AgentModel;
use App\Models\AdminUserModel;
use App\Models\PropertyDocumentModel;
use App\Models\InquiryModel;
use App\Models\PropertyAgentModel;

class Dashboard extends BaseController
{
    protected $propertyModel;
    protected $agentModel;
    protected $adminUserModel;
    protected $propertyDocumentModel;
    protected $inquiryModel;
    protected $propertyAgentModel;

    public function __construct()
    {
        $this->propertyModel = new PropertyModel();
        $this->agentModel = new AgentModel();
        $this->adminUserModel = new AdminUserModel();
        $this->propertyDocumentModel = new PropertyDocumentModel();
        $this->inquiryModel = new InquiryModel();
        $this->propertyAgentModel = new PropertyAgentModel();
    }

    // Check authentication
    private function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login')->with('error', 'Debes iniciar sesión para acceder al panel administrativo');
        }
        return true;
    }

    // Dashboard home
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Get dashboard statistics
        $stats = [
            'total_properties' => $this->propertyModel->countAllResults(),
            'active_properties' => $this->propertyModel->where('is_active', 1)->countAllResults(),
            'featured_properties' => $this->propertyModel->where(['is_active' => 1, 'featured' => 1])->countAllResults(),
            'total_agents' => $this->agentModel->where('is_active', 1)->countAllResults(),
            'pending_inquiries' => $this->inquiryModel->where('status', 'new')->countAllResults(),
            'total_documents' => $this->propertyDocumentModel->countAllResults(),
            'admin_users' => $this->adminUserModel->where('is_active', 1)->countAllResults()
        ];

        // Recent activities
        $recentProperties = $this->propertyModel->select('id, property_code, title, created_at')
                                              ->orderBy('created_at', 'DESC')
                                              ->findAll(5);

        $recentInquiries = $this->inquiryModel->select('
                inquiries.*,
                properties.title as property_title,
                properties.property_code
            ')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->orderBy('inquiries.created_at', 'DESC')
            ->findAll(5);

        $recentDocuments = $this->propertyDocumentModel->getAllDocumentsWithProperty(5);

        // Property statistics by type
        $propertyStats = $this->propertyModel->select('
                property_types.name as type_name,
                COUNT(*) as count
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->where('properties.is_active', 1)
            ->groupBy('properties.property_type_id')
            ->findAll();

        // Monthly property additions (last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-{$i} months"));
            $count = $this->propertyModel->where('DATE_FORMAT(created_at, "%Y-%m")', $month)->countAllResults();
            $monthlyStats[] = [
                'month' => date('M Y', strtotime("-{$i} months")),
                'count' => $count
            ];
        }

        $data = [
            'title' => 'Dashboard - Admin RE/MAX Perú',
            'stats' => $stats,
            'recent_properties' => $recentProperties,
            'recent_inquiries' => $recentInquiries,
            'recent_documents' => $recentDocuments,
            'property_stats' => $propertyStats,
            'monthly_stats' => $monthlyStats
        ];

        return view('admin/dashboard/index', $data);
    }

    // Analytics page
    public function analytics()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Detailed analytics
        $analytics = [
            'properties_by_transaction' => $this->propertyModel->select('transaction_type, COUNT(*) as count')
                                                             ->where('is_active', 1)
                                                             ->groupBy('transaction_type')
                                                             ->findAll(),

            'properties_by_location' => $this->propertyModel->select('
                    locations.name as location_name,
                    locations.district,
                    COUNT(*) as count
                ')
                ->join('locations', 'locations.id = properties.location_id', 'left')
                ->where('properties.is_active', 1)
                ->groupBy('properties.location_id')
                ->orderBy('count', 'DESC')
                ->findAll(10),

            'top_agents' => $this->propertyAgentModel->select('
                    agents.first_name,
                    agents.last_name,
                    COUNT(*) as property_count
                ')
                ->join('agents', 'agents.id = property_agent.agent_id')
                ->join('properties', 'properties.id = property_agent.property_id AND properties.is_active = 1')
                ->groupBy('property_agent.agent_id')
                ->orderBy('property_count', 'DESC')
                ->findAll(10),

            'price_ranges' => $this->getPriceRangeStats(),

            'inquiries_by_month' => $this->getInquiriesByMonth(),

            'documents_by_type' => $this->propertyDocumentModel->select('document_type, COUNT(*) as count')
                                                              ->groupBy('document_type')
                                                              ->findAll()
        ];

        $data = [
            'title' => 'Análiticas - Admin RE/MAX Perú',
            'analytics' => $analytics
        ];

        return view('admin/dashboard/analytics', $data);
    }

    // Get price range statistics
    private function getPriceRangeStats()
    {
        $ranges = [
            '0-100000' => ['min' => 0, 'max' => 100000, 'label' => 'Hasta S/ 100,000'],
            '100000-300000' => ['min' => 100000, 'max' => 300000, 'label' => 'S/ 100,000 - S/ 300,000'],
            '300000-500000' => ['min' => 300000, 'max' => 500000, 'label' => 'S/ 300,000 - S/ 500,000'],
            '500000-1000000' => ['min' => 500000, 'max' => 1000000, 'label' => 'S/ 500,000 - S/ 1,000,000'],
            '1000000+' => ['min' => 1000000, 'max' => null, 'label' => 'Más de S/ 1,000,000']
        ];

        $stats = [];
        foreach ($ranges as $key => $range) {
            $builder = $this->propertyModel->where('is_active', 1)
                                         ->where('price_pen >=', $range['min']);

            if ($range['max']) {
                $builder->where('price_pen <=', $range['max']);
            }

            $stats[] = [
                'range' => $range['label'],
                'count' => $builder->countAllResults()
            ];
        }

        return $stats;
    }

    // Get inquiries by month
    private function getInquiriesByMonth()
    {
        $stats = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-{$i} months"));
            $count = $this->inquiryModel->where('DATE_FORMAT(created_at, "%Y-%m")', $month)->countAllResults();
            $stats[] = [
                'month' => date('M Y', strtotime("-{$i} months")),
                'count' => $count
            ];
        }
        return $stats;
    }

    // System settings
    public function settings()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $data = [
            'title' => 'Configuración del Sistema - Admin RE/MAX Perú'
        ];

        return view('admin/dashboard/settings', $data);
    }

    // Activity log
    public function activityLog()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        // Recent activities (you can implement a proper activity log table)
        $activities = [
            'recent_properties' => $this->propertyModel->select('id, property_code, title, created_at')
                                                     ->orderBy('created_at', 'DESC')
                                                     ->findAll(20),

            'recent_inquiries' => $this->inquiryModel->select('
                    inquiries.*,
                    properties.title as property_title,
                    properties.property_code
                ')
                ->join('properties', 'properties.id = inquiries.property_id', 'left')
                ->orderBy('inquiries.created_at', 'DESC')
                ->findAll(20),

            'recent_documents' => $this->propertyDocumentModel->getAllDocumentsWithProperty(20),

            'recent_logins' => $this->adminUserModel->select('first_name, last_name, email, last_login')
                                                   ->where('last_login IS NOT NULL')
                                                   ->orderBy('last_login', 'DESC')
                                                   ->findAll(20)
        ];

        $data = [
            'title' => 'Registro de Actividades - Admin RE/MAX Perú',
            'activities' => $activities
        ];

        return view('admin/dashboard/activity_log', $data);
    }
}
