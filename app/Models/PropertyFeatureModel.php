<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyFeatureModel extends Model
{
    protected $table = 'property_features';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'name', 'icon', 'category'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'category' => 'permit_empty|max_length[50]'
    ];

    // Obtener todas las características
    public function getAllFeatures()
    {
        return $this->orderBy('category', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    // Obtener características por categoría
    public function getFeaturesByCategory($category)
    {
        return $this->where('category', $category)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    // Obtener características de una propiedad específica
    public function getPropertyFeatures($propertyId)
    {
        return $this->select('property_features.*, property_feature_pivot.value')
                   ->join('property_feature_pivot', 'property_feature_pivot.feature_id = property_features.id')
                   ->where('property_feature_pivot.property_id', $propertyId)
                   ->orderBy('property_features.category', 'ASC')
                   ->orderBy('property_features.name', 'ASC')
                   ->findAll();
    }

    // Obtener características agrupadas por categoría para una propiedad
    public function getPropertyFeaturesByCategory($propertyId)
    {
        $features = $this->getPropertyFeatures($propertyId);
        $grouped = [];

        foreach ($features as $feature) {
            $category = $feature['category'] ?: 'otros';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $feature;
        }

        return $grouped;
    }

    // Asignar característica a propiedad
    public function assignToProperty($propertyId, $featureId, $value = null)
    {
        $db = \Config\Database::connect();

        $data = [
            'property_id' => $propertyId,
            'feature_id' => $featureId,
            'value' => $value
        ];

        return $db->table('property_feature_pivot')->insert($data);
    }

    // Remover característica de propiedad
    public function removeFromProperty($propertyId, $featureId)
    {
        $db = \Config\Database::connect();

        return $db->table('property_feature_pivot')
                 ->where('property_id', $propertyId)
                 ->where('feature_id', $featureId)
                 ->delete();
    }

    // Actualizar características de una propiedad
    public function updatePropertyFeatures($propertyId, $features)
    {
        $db = \Config\Database::connect();

        // Eliminar características existentes
        $db->table('property_feature_pivot')
           ->where('property_id', $propertyId)
           ->delete();

        // Insertar nuevas características
        foreach ($features as $featureId => $value) {
            $this->assignToProperty($propertyId, $featureId, $value);
        }

        return true;
    }
}
