<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyFeatureModel;

class PropertyFeatures extends BaseController
{
    public function index()
    {
        $featureModel = new PropertyFeatureModel();
        $data['features'] = $featureModel->orderBy('category', 'ASC')->orderBy('name', 'ASC')->findAll();

        return view('admin/caracteristicas/index', $data);
    }

    public function new()
    {
        return view('admin/caracteristicas/new');
    }

    public function create()
    {
        $featureModel = new PropertyFeatureModel();

        $data = [
            'name'     => $this->request->getPost('name'),
            'icon'     => $this->request->getPost('icon'),
            'category' => $this->request->getPost('category'),
        ];

        if ($featureModel->save($data)) {
            return redirect()->to('/admin/caracteristicas')->with('message', 'Característica creada con éxito.');
        }

        return redirect()->back()->withInput()->with('errors', $featureModel->errors());
    }

    public function edit($id = null)
    {
        $featureModel = new PropertyFeatureModel();
        $data['feature'] = $featureModel->find($id);

        if (!$data['feature']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Característica no encontrada');
        }

        return view('admin/caracteristicas/edit', $data);
    }

    public function update($id = null)
    {
        $featureModel = new PropertyFeatureModel();

        $data = [
            'name'     => $this->request->getPost('name'),
            'icon'     => $this->request->getPost('icon'),
            'category' => $this->request->getPost('category'),
        ];

        if ($featureModel->update($id, $data)) {
            return redirect()->to('/admin/caracteristicas')->with('message', 'Característica actualizada con éxito.');
        }

        return redirect()->back()->withInput()->with('errors', $featureModel->errors());
    }

    public function delete($id = null)
    {
        $featureModel = new PropertyFeatureModel();
        
        if ($this->request->getMethod() === 'delete' && $featureModel->find($id)) {
            $featureModel->delete($id);
            return redirect()->to('/admin/caracteristicas')->with('message', 'Característica eliminada con éxito.');
        }
        
        return redirect()->to('/admin/caracteristicas')->with('error', 'No se pudo eliminar la característica.');
    }
}