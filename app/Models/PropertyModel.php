<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table = 'properties';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'property_code', 'title', 'description', 'property_type_id',
        'transaction_type', 'price_pen', 'price_usd', 'location_id',
        'address', 'latitude', 'longitude', 'land_area', 'built_area',
        'bedrooms', 'bathrooms', 'half_bathrooms', 'parking_spaces',
        'floors', 'age_years', 'featured', 'is_active',
        'published_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'property_code' => 'required|max_length[20]|is_unique[properties.property_code]',
        'title' => 'required|max_length[255]',
        'transaction_type' => 'required|in_list[venta,alquiler,anticresis]',
        'price_pen' => 'permit_empty|decimal',
        'price_usd' => 'permit_empty|decimal',
        'property_type_id' => 'required|is_natural_no_zero'
    ];

    // Obtener propiedades con información relacionada
    public function getPropertiesWithDetails($limit = 12, $offset = 0)
    {
        $properties = $this->select('
                properties.*,
                property_types.name as property_type_name,
                locations.name as location_name,
                locations.district,
                locations.province,
                locations.department
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left')
            ->where('properties.is_active', 1)
            ->orderBy('properties.featured', 'DESC')
            ->orderBy('properties.created_at', 'DESC')
            ->findAll($limit, $offset);

        // Obtener agentes para cada propiedad
        if (!empty($properties)) {
            $propertyAgentModel = new \App\Models\PropertyAgentModel();
            foreach ($properties as &$property) {
                $agents = $propertyAgentModel->getPropertyAgents($property['id']);
                $property['agents'] = $agents;

                // Obtener agente principal para compatibilidad con el código existente
                $primaryAgent = $propertyAgentModel->getPrimaryAgent($property['id']);
                if ($primaryAgent) {
                    $property['agent_first_name'] = $primaryAgent['first_name'];
                    $property['agent_last_name'] = $primaryAgent['last_name'];
                    $property['agent_photo'] = $primaryAgent['photo'];
                    $property['agent_phone'] = $primaryAgent['phone'];
                    $property['agent_cell_phone'] = $primaryAgent['cell_phone'];
                    $property['agent_email'] = $primaryAgent['email'];
                    $property['primary_agent'] = $primaryAgent;
                }
            }
        }

        return $properties;
    }

    // Obtener una propiedad específica por código
    public function getPropertyByCode($propertyCode)
    {
        $property = $this->select('
                properties.*,
                property_types.name as property_type_name,
                locations.name as location_name,
                locations.district,
                locations.province,
                locations.department
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left')
            ->where('properties.property_code', $propertyCode)
            ->where('properties.is_active', 1)
            ->first();

        if ($property) {
            $propertyAgentModel = new \App\Models\PropertyAgentModel();

            // Obtener todos los agentes de la propiedad
            $agents = $propertyAgentModel->getPropertyAgents($property['id']);
            $property['agents'] = $agents;

            // Obtener agente principal para compatibilidad
            $primaryAgent = $propertyAgentModel->getPrimaryAgent($property['id']);
            if ($primaryAgent) {
                $property['agent_first_name'] = $primaryAgent['first_name'];
                $property['agent_last_name'] = $primaryAgent['last_name'];
                $property['agent_photo'] = $primaryAgent['photo'];
                $property['agent_phone'] = $primaryAgent['phone'];
                $property['agent_cell_phone'] = $primaryAgent['cell_phone'];
                $property['agent_email'] = $primaryAgent['email'];
                $property['agent_company'] = $primaryAgent['company'];
                $property['primary_agent'] = $primaryAgent;
                $property['agent_id'] = $primaryAgent['agent_id']; // Para compatibilidad
            }
        }

        return $property;
    }

    // Búsqueda con filtros
    public function searchProperties($filters = [])
    {
        $builder = $this->select('
                properties.*,
                property_types.name as property_type_name,
                locations.name as location_name,
                locations.district
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left')
            ->where('properties.is_active', 1);

        // Filtro por tipo de transacción
        if (!empty($filters['transaction_type'])) {
            $builder->where('properties.transaction_type', $filters['transaction_type']);
        }

        // Filtro por tipo de propiedad
        if (!empty($filters['property_type_id'])) {
            $builder->where('properties.property_type_id', $filters['property_type_id']);
        }

        // Filtro por ubicación
        if (!empty($filters['location_id'])) {
            $builder->where('properties.location_id', $filters['location_id']);
        }

        // Filtro por rango de precios
        if (!empty($filters['price_min'])) {
            $currency = $filters['currency'] ?? 'pen';
            $priceField = $currency === 'usd' ? 'price_usd' : 'price_pen';
            $builder->where("properties.{$priceField} >=", $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $currency = $filters['currency'] ?? 'pen';
            $priceField = $currency === 'usd' ? 'price_usd' : 'price_pen';
            $builder->where("properties.{$priceField} <=", $filters['price_max']);
        }

        // Filtro por número de habitaciones
        if (!empty($filters['bedrooms'])) {
            $builder->where('properties.bedrooms >=', $filters['bedrooms']);
        }

        // Filtro por número de baños
        if (!empty($filters['bathrooms'])) {
            $builder->where('properties.bathrooms >=', $filters['bathrooms']);
        }

        // Búsqueda por texto
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('properties.title', $filters['search'])
                ->orLike('properties.description', $filters['search'])
                ->orLike('locations.name', $filters['search'])
                ->orLike('locations.district', $filters['search'])
                ->groupEnd();
        }

        return $builder->orderBy('properties.featured', 'DESC')
                      ->orderBy('properties.created_at', 'DESC');
    }

    // Obtener propiedades destacadas
    public function getFeaturedProperties($limit = 8)
    {
        return $this->getPropertiesWithDetails($limit, 0);
    }

    // Obtener propiedades por agente
    public function getPropertiesByAgent($agentId, $limit = 10)
    {
        $propertyAgentModel = new \App\Models\PropertyAgentModel();
        $agentProperties = $propertyAgentModel->getAgentProperties($agentId);

        if (empty($agentProperties)) {
            return [];
        }

        $propertyIds = array_column($agentProperties, 'property_id');

        $properties = $this->select('
                properties.*,
                property_types.name as property_type_name,
                locations.name as location_name
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left')
            ->whereIn('properties.id', $propertyIds)
            ->where('properties.is_active', 1)
            ->orderBy('properties.created_at', 'DESC')
            ->findAll($limit);

        // Agregar información del rol del agente en cada propiedad
        foreach ($properties as &$property) {
            foreach ($agentProperties as $agentProperty) {
                if ($agentProperty['property_id'] == $property['id']) {
                    $property['agent_role'] = $agentProperty['role'];
                    $property['is_primary_agent'] = $agentProperty['is_primary'];
                    $property['commission_percentage'] = $agentProperty['commission_percentage'];
                    break;
                }
            }
        }

        return $properties;
    }

    // Generar código único de propiedad
    public function generatePropertyCode()
    {
        $year = date('Y');
        $month = date('m');

        // Buscar el último código del mes
        $lastProperty = $this->where('property_code LIKE', "{$year}{$month}%")
                           ->orderBy('property_code', 'DESC')
                           ->first();

        if ($lastProperty) {
            $lastNumber = (int)substr($lastProperty['property_code'], 6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Crear propiedad con agente principal
    public function createPropertyWithAgent($propertyData, $agentId, $agentRole = 'principal')
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Crear la propiedad
            $propertyId = $this->insert($propertyData);

            if (!$propertyId) {
                throw new \Exception('Error al crear la propiedad');
            }

            // Asignar agente principal
            $propertyAgentModel = new \App\Models\PropertyAgentModel();
            $agentAssignment = $propertyAgentModel->assignAgentToProperty($propertyId, $agentId, [
                'is_primary' => true,
                'role' => $agentRole,
                'commission_percentage' => 100.00
            ]);

            if (!$agentAssignment) {
                throw new \Exception('Error al asignar agente a la propiedad');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }

            return $propertyId;

        } catch (\Exception $e) {
            $db->transRollback();
            return false;
        }
    }

    // Obtener propiedades con múltiples agentes
    public function getPropertiesWithMultipleAgents($limit = 50)
    {
        $propertyAgentModel = new \App\Models\PropertyAgentModel();
        $multiAgentProperties = $propertyAgentModel->getPropertiesWithMultipleAgents();

        if (empty($multiAgentProperties)) {
            return [];
        }

        $propertyIds = array_column($multiAgentProperties, 'property_id');

        $properties = $this->select('
                properties.*,
                property_types.name as property_type_name,
                locations.name as location_name,
                locations.district
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left')
            ->whereIn('properties.id', $propertyIds)
            ->where('properties.is_active', 1)
            ->orderBy('properties.created_at', 'DESC')
            ->findAll($limit);

        // Agregar información de agentes a cada propiedad
        foreach ($properties as &$property) {
            $property['agents'] = $propertyAgentModel->getPropertyAgents($property['id']);
            $property['agent_count'] = count($property['agents']);
        }

        return $properties;
    }

    // Buscar propiedades por agente específico
    public function searchPropertiesByAgent($agentId, $filters = [])
    {
        $propertyAgentModel = new \App\Models\PropertyAgentModel();
        $agentProperties = $propertyAgentModel->getAgentProperties($agentId);

        if (empty($agentProperties)) {
            return [];
        }

        $propertyIds = array_column($agentProperties, 'property_id');

        $builder = $this->select('
                properties.*,
                property_types.name as property_type_name,
                locations.name as location_name,
                locations.district
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left')
            ->whereIn('properties.id', $propertyIds)
            ->where('properties.is_active', 1);

        // Aplicar filtros adicionales
        if (!empty($filters['transaction_type'])) {
            $builder->where('properties.transaction_type', $filters['transaction_type']);
        }

        if (!empty($filters['property_type_id'])) {
            $builder->where('properties.property_type_id', $filters['property_type_id']);
        }

        if (!empty($filters['is_primary_only'])) {
            // Filtrar solo propiedades donde el agente es principal
            $primaryPropertyIds = [];
            foreach ($agentProperties as $agentProperty) {
                if ($agentProperty['is_primary']) {
                    $primaryPropertyIds[] = $agentProperty['property_id'];
                }
            }
            if (!empty($primaryPropertyIds)) {
                $builder->whereIn('properties.id', $primaryPropertyIds);
            } else {
                return []; // No tiene propiedades como agente principal
            }
        }

        return $builder->orderBy('properties.created_at', 'DESC')->findAll();
    }
}
