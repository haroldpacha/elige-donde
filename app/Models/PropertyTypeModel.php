<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyTypeModel extends Model
{
    protected $table = 'property_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'name', 'slug'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[50]',
        'slug' => 'required|max_length[50]|is_unique[property_types.slug]'
    ];

    // Obtener todos los tipos de propiedad
    public function getAllTypes()
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }

    // Obtener tipo por slug
    public function getBySlug($slug)
    {
        return $this->where('slug', $slug)->first();
    }

    // Obtener tipos con conteo de propiedades
    public function getTypesWithCount()
    {
        return $this->select('property_types.*, COUNT(properties.id) as property_count')
                   ->join('properties', 'properties.property_type_id = property_types.id AND properties.is_active = 1', 'left')
                   ->groupBy('property_types.id')
                   ->orderBy('property_types.name', 'ASC')
                   ->findAll();
    }
}
