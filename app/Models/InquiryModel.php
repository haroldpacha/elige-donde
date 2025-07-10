<?php

namespace App\Models;

use CodeIgniter\Model;

class InquiryModel extends Model
{
    protected $table = 'inquiries';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'property_id', 'agent_id', 'name', 'email', 'phone',
        'message', 'inquiry_type', 'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'email' => 'required|valid_email|max_length[100]',
        'phone' => 'permit_empty|max_length[20]',
        'message' => 'permit_empty',
        'inquiry_type' => 'permit_empty|in_list[info,visit,call,email]',
        'status' => 'permit_empty|in_list[new,contacted,closed]'
    ];

    // Crear nueva consulta
    public function createInquiry($data)
    {
        $data['status'] = $data['status'] ?? 'new';
        $data['inquiry_type'] = $data['inquiry_type'] ?? 'info';

        return $this->insert($data);
    }

    // Obtener consultas con información de propiedad y agente
    public function getInquiriesWithDetails($limit = 50, $offset = 0)
    {
        return $this->select('
                inquiries.*,
                properties.title as property_title,
                properties.property_code,
                agents.first_name as agent_first_name,
                agents.last_name as agent_last_name,
                agents.email as agent_email
            ')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->join('agents', 'agents.id = inquiries.agent_id', 'left')
            ->orderBy('inquiries.created_at', 'DESC')
            ->findAll($limit, $offset);
    }

    // Obtener consultas por agente
    public function getInquiriesByAgent($agentId, $status = null)
    {
        $builder = $this->select('
                inquiries.*,
                properties.title as property_title,
                properties.property_code
            ')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->where('inquiries.agent_id', $agentId);

        if ($status) {
            $builder->where('inquiries.status', $status);
        }

        return $builder->orderBy('inquiries.created_at', 'DESC')->findAll();
    }

    // Obtener consultas por propiedad
    public function getInquiriesByProperty($propertyId)
    {
        return $this->select('
                inquiries.*,
                agents.first_name as agent_first_name,
                agents.last_name as agent_last_name
            ')
            ->join('agents', 'agents.id = inquiries.agent_id', 'left')
            ->where('inquiries.property_id', $propertyId)
            ->orderBy('inquiries.created_at', 'DESC')
            ->findAll();
    }

    // Actualizar estado de consulta
    public function updateInquiryStatus($inquiryId, $status)
    {
        return $this->update($inquiryId, ['status' => $status]);
    }

    // Obtener estadísticas de consultas
    public function getInquiryStats($agentId = null)
    {
        $builder = $this->select('
                status,
                COUNT(*) as count,
                DATE(created_at) as date
            ');

        if ($agentId) {
            $builder->where('agent_id', $agentId);
        }

        return $builder->where('created_at >=', date('Y-m-d', strtotime('-30 days')))
                      ->groupBy(['status', 'DATE(created_at)'])
                      ->orderBy('date', 'DESC')
                      ->findAll();
    }

    // Enviar notificación por email al agente
    public function notifyAgent($inquiryId)
    {
        $inquiry = $this->select('
                inquiries.*,
                properties.title as property_title,
                properties.property_code,
                agents.first_name as agent_first_name,
                agents.last_name as agent_last_name,
                agents.email as agent_email
            ')
            ->join('properties', 'properties.id = inquiries.property_id', 'left')
            ->join('agents', 'agents.id = inquiries.agent_id', 'left')
            ->find($inquiryId);

        if (!$inquiry) {
            return false;
        }

        // Aquí implementarías el envío de email
        // Por ejemplo usando la librería de email de CodeIgniter
        $email = \Config\Services::email();
        $email->setTo($inquiry['agent_email']);
        $email->setFrom('noreply@remax-peru.com', 'Elige Donde');
        $email->setSubject('Nueva consulta de propiedad: ' . $inquiry['property_title']);

        $message = "
            Nueva consulta recibida para la propiedad: {$inquiry['property_title']} (#{$inquiry['property_code']})

            Cliente: {$inquiry['name']}
            Email: {$inquiry['email']}
            Teléfono: {$inquiry['phone']}
            Tipo de consulta: {$inquiry['inquiry_type']}

            Mensaje:
            {$inquiry['message']}

            Fecha: {$inquiry['created_at']}
        ";

        $email->setMessage($message);

        return $email->send();
    }
}
