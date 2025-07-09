<?php

namespace App\Models;

use CodeIgniter\Model;

class LocationModel extends Model
{
    protected $table = 'locations';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'name', 'department', 'province', 'district'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[100]',
        'department' => 'required|max_length[50]',
        'province' => 'required|max_length[50]',
        'district' => 'required|max_length[50]'
    ];

    // Obtener todas las ubicaciones
    public function getAllLocations()
    {
        return $this->orderBy('department', 'ASC')
                   ->orderBy('province', 'ASC')
                   ->orderBy('district', 'ASC')
                   ->findAll();
    }

    // Obtener ubicaciones por departamento
    public function getLocationsByDepartment($department)
    {
        return $this->where('department', $department)
                   ->orderBy('province', 'ASC')
                   ->orderBy('district', 'ASC')
                   ->findAll();
    }

    // Obtener departamentos únicos
    public function getDepartments()
    {
        return $this->select('department')
                   ->distinct()
                   ->orderBy('department', 'ASC')
                   ->findAll();
    }

    // Obtener provincias por departamento
    public function getProvincesByDepartment($department)
    {
        return $this->select('province')
                   ->where('department', $department)
                   ->distinct()
                   ->orderBy('province', 'ASC')
                   ->findAll();
    }

    // Obtener distritos por provincia
    public function getDistrictsByProvince($department, $province)
    {
        return $this->select('district, id, name')
                   ->where('department', $department)
                   ->where('province', $province)
                   ->orderBy('district', 'ASC')
                   ->findAll();
    }

    // Buscar ubicaciones
    public function searchLocations($search)
    {
        return $this->groupStart()
                   ->like('name', $search)
                   ->orLike('district', $search)
                   ->orLike('province', $search)
                   ->orLike('department', $search)
                   ->groupEnd()
                   ->orderBy('department', 'ASC')
                   ->findAll();
    }
}
