<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyAgentModel extends Model
{
    protected $table = 'property_agent';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'property_id', 'agent_id', 'is_primary', 'role',
        'commission_percentage', 'assigned_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'property_id' => 'required|is_natural_no_zero',
        'agent_id' => 'required|is_natural_no_zero',
        'role' => 'permit_empty|in_list[principal,co-agente,colaborador]',
        'commission_percentage' => 'permit_empty|decimal'
    ];

    // Obtener agentes de una propiedad específica
    public function getPropertyAgents($propertyId)
    {
        return $this->select('
                property_agent.*,
                agents.first_name,
                agents.last_name,
                agents.email,
                agents.phone,
                agents.cell_phone,
                agents.photo,
                agents.company,
                agents.license_number
            ')
            ->join('agents', 'agents.id = property_agent.agent_id')
            ->where('property_agent.property_id', $propertyId)
            ->where('agents.is_active', 1)
            ->orderBy('property_agent.is_primary', 'DESC')
            ->orderBy('property_agent.role', 'ASC')
            ->findAll();
    }

    // Obtener agente principal de una propiedad
    public function getPrimaryAgent($propertyId)
    {
        return $this->select('
                property_agent.*,
                agents.first_name,
                agents.last_name,
                agents.email,
                agents.phone,
                agents.cell_phone,
                agents.photo,
                agents.company,
                agents.license_number
            ')
            ->join('agents', 'agents.id = property_agent.agent_id')
            ->where('property_agent.property_id', $propertyId)
            ->where('property_agent.is_primary', 1)
            ->where('agents.is_active', 1)
            ->first();
    }

    // Obtener propiedades de un agente específico
    public function getAgentProperties($agentId)
    {
        return $this->select('
                property_agent.*,
                properties.property_code,
                properties.title,
                properties.price_pen,
                properties.price_usd,
                properties.transaction_type,
                property_types.name as property_type_name,
                locations.name as location_name,
                locations.district
            ')
            ->join('properties', 'properties.id = property_agent.property_id')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left')
            ->where('property_agent.agent_id', $agentId)
            ->where('properties.is_active', 1)
            ->orderBy('property_agent.is_primary', 'DESC')
            ->orderBy('properties.created_at', 'DESC')
            ->findAll();
    }

    // Asignar agente a propiedad
    public function assignAgentToProperty($propertyId, $agentId, $data = [])
    {
        // Verificar si ya existe la relación
        $existing = $this->where('property_id', $propertyId)
                        ->where('agent_id', $agentId)
                        ->first();

        if ($existing) {
            return false; // Ya existe la relación
        }

        $assignmentData = [
            'property_id' => $propertyId,
            'agent_id' => $agentId,
            'is_primary' => $data['is_primary'] ?? false,
            'role' => $data['role'] ?? 'principal',
            'commission_percentage' => $data['commission_percentage'] ?? 0.00,
            'assigned_at' => date('Y-m-d H:i:s')
        ];

        // Si es agente principal, quitar marca de principal a otros
        if ($assignmentData['is_primary']) {
            $this->where('property_id', $propertyId)
                 ->set('is_primary', 0)
                 ->update();
        }

        return $this->insert($assignmentData);
    }

    // Remover agente de propiedad
    public function removeAgentFromProperty($propertyId, $agentId)
    {
        return $this->where('property_id', $propertyId)
                   ->where('agent_id', $agentId)
                   ->delete();
    }

    // Establecer agente principal
    public function setPrimaryAgent($propertyId, $agentId)
    {
        // Quitar marca de principal a todos los agentes de la propiedad
        $this->where('property_id', $propertyId)
             ->set('is_primary', 0)
             ->update();

        // Establecer el nuevo agente principal
        return $this->where('property_id', $propertyId)
                   ->where('agent_id', $agentId)
                   ->set('is_primary', 1)
                   ->update();
    }

    // Actualizar rol del agente en una propiedad
    public function updateAgentRole($propertyId, $agentId, $role, $commissionPercentage = null)
    {
        $updateData = ['role' => $role];

        if ($commissionPercentage !== null) {
            $updateData['commission_percentage'] = $commissionPercentage;
        }

        return $this->where('property_id', $propertyId)
                   ->where('agent_id', $agentId)
                   ->set($updateData)
                   ->update();
    }

    // Obtener estadísticas de agente
    public function getAgentStats($agentId)
    {
        // Propiedades totales
        $totalProperties = $this->where('agent_id', $agentId)
                               ->join('properties', 'properties.id = property_agent.property_id')
                               ->where('properties.is_active', 1)
                               ->countAllResults();

        // Propiedades como agente principal
        $primaryProperties = $this->where('agent_id', $agentId)
                                 ->where('is_primary', 1)
                                 ->join('properties', 'properties.id = property_agent.property_id')
                                 ->where('properties.is_active', 1)
                                 ->countAllResults();

        // Propiedades por tipo de transacción
        $saleProperties = $this->where('agent_id', $agentId)
                              ->join('properties', 'properties.id = property_agent.property_id')
                              ->where('properties.is_active', 1)
                              ->where('properties.transaction_type', 'venta')
                              ->countAllResults();

        $rentProperties = $this->where('agent_id', $agentId)
                              ->join('properties', 'properties.id = property_agent.property_id')
                              ->where('properties.is_active', 1)
                              ->where('properties.transaction_type', 'alquiler')
                              ->countAllResults();

        return [
            'total_properties' => $totalProperties,
            'primary_properties' => $primaryProperties,
            'sale_properties' => $saleProperties,
            'rent_properties' => $rentProperties,
            'collaboration_properties' => $totalProperties - $primaryProperties
        ];
    }

    // Obtener propiedades con múltiples agentes
    public function getPropertiesWithMultipleAgents()
    {
        return $this->select('property_id, COUNT(*) as agent_count')
                   ->groupBy('property_id')
                   ->having('agent_count > 1')
                   ->findAll();
    }

    // Obtener agentes que trabajan juntos frecuentemente
    public function getFrequentCollaborations($agentId, $limit = 5)
    {
        $db = \Config\Database::connect();

        $query = $db->query("
            SELECT
                a.agent_id,
                ag.first_name,
                ag.last_name,
                ag.photo,
                COUNT(*) as collaboration_count
            FROM property_agent pa1
            INNER JOIN property_agent a ON pa1.property_id = a.property_id AND a.agent_id != pa1.agent_id
            INNER JOIN agents ag ON ag.id = a.agent_id
            WHERE pa1.agent_id = ? AND ag.is_active = 1
            GROUP BY a.agent_id, ag.first_name, ag.last_name, ag.photo
            ORDER BY collaboration_count DESC
            LIMIT ?
        ", [$agentId, $limit]);

        return $query->getResultArray();
    }

    // Validar asignación de agente
    public function validateAgentAssignment($propertyId, $agentId)
    {
        // Verificar que la propiedad existe
        $propertyModel = new \App\Models\PropertyModel();
        $property = $propertyModel->find($propertyId);

        if (!$property || !$property['is_active']) {
            return ['valid' => false, 'message' => 'Propiedad no encontrada o inactiva'];
        }

        // Verificar que el agente existe y está activo
        $agentModel = new \App\Models\AgentModel();
        $agent = $agentModel->find($agentId);

        if (!$agent || !$agent['is_active']) {
            return ['valid' => false, 'message' => 'Agente no encontrado o inactivo'];
        }

        // Verificar si ya existe la relación
        $existing = $this->where('property_id', $propertyId)
                        ->where('agent_id', $agentId)
                        ->first();

        if ($existing) {
            return ['valid' => false, 'message' => 'El agente ya está asignado a esta propiedad'];
        }

        return ['valid' => true, 'message' => 'Asignación válida'];
    }
}
