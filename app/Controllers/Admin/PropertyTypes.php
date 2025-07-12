<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyTypeModel;

class PropertyTypes extends BaseController
{
    public function index()
    {
        $typeModel = new PropertyTypeModel();
        $data['types'] = $typeModel->findAll();

        return view('admin/property_types/index', $data);
    }

    public function new()
    {
        return view('admin/property_types/new');
    }

    public function create()
    {
        $typeModel = new PropertyTypeModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
        ];

        if ($typeModel->save($data)) {
            return redirect()->to('/admin/tipos-propiedades')->with('message', 'Tipo de propiedad creado con éxito.');
        }

        return redirect()->back()->withInput()->with('errors', $typeModel->errors());
    }

    public function edit($id = null)
    {
        $typeModel = new PropertyTypeModel();
        $data['type'] = $typeModel->find($id);

        if (!$data['type']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipo de propiedad no encontrado');
        }

        return view('admin/property_types/edit', $data);
    }

    public function update($id = null)
    {
        $typeModel = new PropertyTypeModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
        ];

        if ($typeModel->update($id, $data)) {
            return redirect()->to('/admin/tipos-propiedades')->with('message', 'Tipo de propiedad actualizado con éxito.');
        }

        return redirect()->back()->withInput()->with('errors', $typeModel->errors());
    }

    public function delete($id = null)
    {
        $typeModel = new PropertyTypeModel();

        if ($this->request->getMethod() === 'delete' && $typeModel->find($id)) {
            $typeModel->delete($id);
            return redirect()->to('/admin/tipos-propiedades')->with('message', 'Tipo de propiedad eliminado con éxito.');
        }

        return redirect()->to('/admin/tipos-propiedades')->with('error', 'No se pudo eliminar el tipo de propiedad.');
    }
}
