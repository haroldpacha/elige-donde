<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyImageModel extends Model
{
    protected $table = 'property_images';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'property_id', 'image_url', 'alt_text', 'is_main', 'order_index'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'property_id' => 'required|is_natural_no_zero',
        'image_url' => 'required|max_length[255]',
        'alt_text' => 'permit_empty|max_length[255]'
    ];

    // Obtener imágenes de una propiedad
    public function getPropertyImages($propertyId)
    {
        return $this->where('property_id', $propertyId)
                   ->orderBy('is_main', 'DESC')
                   ->orderBy('order_index', 'ASC')
                   ->findAll();
    }

    // Obtener imagen principal de una propiedad
    public function getMainImage($propertyId)
    {
        $mainImage = $this->where('property_id', $propertyId)
                         ->where('is_main', 1)
                         ->first();

        if (!$mainImage) {
            $mainImage = $this->where('property_id', $propertyId)
                             ->orderBy('order_index', 'ASC')
                             ->first();
        }

        return $mainImage;
    }

    // Establecer imagen principal
    public function setMainImage($propertyId, $imageId)
    {
        // Quitar marca de principal a todas las imágenes de la propiedad
        $this->where('property_id', $propertyId)
             ->set('is_main', 0)
             ->update();

        // Establecer la nueva imagen principal
        return $this->update($imageId, ['is_main' => 1]);
    }

    // Obtener múltiples imágenes principales para listado de propiedades
    public function getMainImagesForProperties($propertyIds)
    {
        if (empty($propertyIds)) {
            return [];
        }

        return $this->whereIn('property_id', $propertyIds)
                   ->where('is_main', 1)
                   ->findAll();
    }
}
