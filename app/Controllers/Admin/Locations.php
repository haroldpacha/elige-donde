<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LocationModel;

class Locations extends BaseController
{
    public function index()
    {
        $locationModel = new LocationModel();
        $data['locations'] = $locationModel->getAllLocations();

        return view('admin/locations/index', $data);
    }

    public function new()
    {
        return view('admin/locations/new');
    }

    public function create()
    {
        $locationModel = new LocationModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'department' => $this->request->getPost('department'),
            'province'   => $this->request->getPost('province'),
            'district'   => $this->request->getPost('district'),
        ];

        if ($locationModel->save($data)) {
            return redirect()->to('/admin/ubicaciones')->with('message', 'Ubicación creada con éxito.');
        }

        return redirect()->back()->withInput()->with('errors', $locationModel->errors());
    }

    public function edit($id = null)
    {
        $locationModel = new LocationModel();
        $data['location'] = $locationModel->find($id);

        if (!$data['location']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Ubicación no encontrada');
        }

        return view('admin/locations/edit', $data);
    }

    public function update($id = null)
    {
        $locationModel = new LocationModel();

        $data = [
            'name'       => $this->request->getPost('name'),
            'department' => $this->request->getPost('department'),
            'province'   => $this->request->getPost('province'),
            'district'   => $this->request->getPost('district'),
        ];

        if ($locationModel->update($id, $data)) {
            return redirect()->to('/admin/ubicaciones')->with('message', 'Ubicación actualizada con éxito.');
        }

        return redirect()->back()->withInput()->with('errors', $locationModel->errors());
    }

    public function delete($id = null)
    {
        $locationModel = new LocationModel();

        if ($this->request->getMethod() === 'delete' && $locationModel->find($id)) {
            $locationModel->delete($id);
            return redirect()->to('/admin/ubicaciones')->with('message', 'Ubicación eliminada con éxito.');
        }

        return redirect()->to('/admin/ubicaciones')->with('error', 'No se pudo eliminar la ubicación.');
    }
}
