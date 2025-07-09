<?php

namespace App\Models;

use CodeIgniter\Model;

class AgentModel extends Model
{
    protected $table = 'agents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'first_name', 'last_name', 'email', 'phone', 'cell_phone',
        'photo', 'company', 'license_number', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'first_name' => 'required|max_length[50]',
        'last_name' => 'required|max_length[50]',
        'email' => 'required|valid_email|max_length[100]|is_unique[agents.email]',
        'phone' => 'permit_empty|max_length[20]',
        'cell_phone' => 'permit_empty|max_length[20]'
    ];

    // Obtener todos los agentes activos
    public function getActiveAgents()
    {
        return $this->where('is_active', 1)
                   ->orderBy('first_name', 'ASC')
                   ->findAll();
    }

    // Obtener agente con estadísticas
    public function getAgentWithStats($agentId)
    {
        $agent = $this->find($agentId);

        if (!$agent) {
            return null;
        }

        // Usar el nuevo modelo de relación para obtener estadísticas
        $propertyAgentModel = new \App\Models\PropertyAgentModel();
        $stats = $propertyAgentModel->getAgentStats($agentId);

        $agent['total_properties'] = $stats['total_properties'];
        $agent['primary_properties'] = $stats['primary_properties'];
        $agent['sale_properties'] = $stats['sale_properties'];
        $agent['rent_properties'] = $stats['rent_properties'];
        $agent['collaboration_properties'] = $stats['collaboration_properties'];
        $agent['full_name'] = $agent['first_name'] . ' ' . $agent['last_name'];

        return $agent;
    }

    // Obtener top agentes por número de propiedades
    public function getTopAgents($limit = 6)
    {
        return $this->select('agents.*, COUNT(property_agent.property_id) as property_count')
                   ->join('property_agent', 'property_agent.agent_id = agents.id', 'left')
                   ->join('properties', 'properties.id = property_agent.property_id AND properties.is_active = 1', 'left')
                   ->where('agents.is_active', 1)
                   ->groupBy('agents.id')
                   ->orderBy('property_count', 'DESC')
                   ->orderBy('agents.first_name', 'ASC')
                   ->findAll($limit);
    }

    // Buscar agentes
    public function searchAgents($search = '')
    {
        $builder = $this->where('is_active', 1);

        if (!empty($search)) {
            $builder->groupStart()
                   ->like('first_name', $search)
                   ->orLike('last_name', $search)
                   ->orLike('email', $search)
                   ->groupEnd();
        }

        return $builder->orderBy('first_name', 'ASC')->findAll();
    }

    // Obtener agentes colaboradores frecuentes
    public function getFrequentCollaborators($agentId, $limit = 5)
    {
        $propertyAgentModel = new \App\Models\PropertyAgentModel();
        return $propertyAgentModel->getFrequentCollaborations($agentId, $limit);
    }

    // Obtener propiedades donde el agente es principal
    public function getPrimaryProperties($agentId, $limit = 20)
    {
        $propertyModel = new \App\Models\PropertyModel();
        return $propertyModel->searchPropertiesByAgent($agentId, ['is_primary_only' => true]);
    }

    // Obtener todas las propiedades del agente (principal y colaborador)
    public function getAllAgentProperties($agentId, $filters = [])
    {
        $propertyModel = new \App\Models\PropertyModel();
        return $propertyModel->searchPropertiesByAgent($agentId, $filters);
    }

    // Obtener agentes por tipo de rol en propiedades
    public function getAgentsByRole($role = 'principal', $limit = 10)
    {
        return $this->select('agents.*, COUNT(property_agent.property_id) as property_count')
                   ->join('property_agent', 'property_agent.agent_id = agents.id AND property_agent.role = "' . $role . '"', 'left')
                   ->join('properties', 'properties.id = property_agent.property_id AND properties.is_active = 1', 'left')
                   ->where('agents.is_active', 1)
                   ->groupBy('agents.id')
                   ->orderBy('property_count', 'DESC')
                   ->orderBy('agents.first_name', 'ASC')
                   ->findAll($limit);
    }

    // Obtener agentes con más colaboraciones
    public function getTopCollaborators($limit = 10)
    {
        return $this->select('agents.*, COUNT(property_agent.property_id) as collaboration_count')
                   ->join('property_agent', 'property_agent.agent_id = agents.id AND property_agent.is_primary = 0', 'left')
                   ->join('properties', 'properties.id = property_agent.property_id AND properties.is_active = 1', 'left')
                   ->where('agents.is_active', 1)
                   ->groupBy('agents.id')
                   ->having('collaboration_count > 0')
                   ->orderBy('collaboration_count', 'DESC')
                   ->orderBy('agents.first_name', 'ASC')
                   ->findAll($limit);
    }

    // Obtener estadísticas detalladas del agente
    public function getDetailedAgentStats($agentId)
    {
        $agent = $this->getAgentWithStats($agentId);

        if (!$agent) {
            return null;
        }

        $propertyAgentModel = new \App\Models\PropertyAgentModel();

        // Obtener colaboradores frecuentes
        $agent['frequent_collaborators'] = $propertyAgentModel->getFrequentCollaborations($agentId, 5);

        // Obtener propiedades por rol
        $agentProperties = $propertyAgentModel->getAgentProperties($agentId);

        $roleStats = [
            'principal' => 0,
            'co-agente' => 0,
            'colaborador' => 0
        ];

        foreach ($agentProperties as $property) {
            if (isset($roleStats[$property['role']])) {
                $roleStats[$property['role']]++;
            }
        }

        $agent['role_stats'] = $roleStats;

        // Calcular comisiones promedio
        $totalCommission = 0;
        $commissionCount = 0;

        foreach ($agentProperties as $property) {
            if ($property['commission_percentage'] > 0) {
                $totalCommission += $property['commission_percentage'];
                $commissionCount++;
            }
        }

        $agent['average_commission'] = $commissionCount > 0 ? round($totalCommission / $commissionCount, 2) : 0;

        return $agent;
    }

    // Verificar si un agente puede ser asignado a una propiedad
    public function canBeAssignedToProperty($agentId, $propertyId)
    {
        $propertyAgentModel = new \App\Models\PropertyAgentModel();
        return $propertyAgentModel->validateAgentAssignment($propertyId, $agentId);
    }
}
