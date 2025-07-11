<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyDocumentModel;
use App\Models\PropertyAgentModel;
use App\Models\PropertyTypeModel;
use App\Models\LocationModel;
use App\Models\AgentModel;
use App\Models\PropertyFeatureModel;

class Properties extends BaseController
{
    protected $propertyModel;
    protected $propertyImageModel;
    protected $propertyDocumentModel;
    protected $propertyAgentModel;
    protected $propertyTypeModel;
    protected $locationModel;
    protected $agentModel;
    protected $propertyFeatureModel;

    public function __construct()
    {
        $this->propertyModel = new PropertyModel();
        $this->propertyImageModel = new PropertyImageModel();
        $this->propertyDocumentModel = new PropertyDocumentModel();
        $this->propertyAgentModel = new PropertyAgentModel();
        $this->propertyTypeModel = new PropertyTypeModel();
        $this->locationModel = new LocationModel();
        $this->agentModel = new AgentModel();
        $this->propertyFeatureModel = new PropertyFeatureModel();
    }

    // Check authentication
    private function checkAuth()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login')->with('error', 'Debes iniciar sesión para acceder al panel administrativo');
        }
        return true;
    }

    // List all properties
    public function index()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 15;
        $offset = ($page - 1) * $perPage;

        // Filters
        $filters = [
            'search' => $this->request->getGet('search'),
            'transaction_type' => $this->request->getGet('transaction_type'),
            'property_type_id' => $this->request->getGet('property_type_id'),
            'location_id' => $this->request->getGet('location_id'),
            'featured' => $this->request->getGet('featured'),
            'is_active' => $this->request->getGet('is_active')
        ];

        // Build query
        $builder = $this->propertyModel->select('
                properties.*,
                property_types.name as property_type_name,
                locations.name as location_name,
                locations.district
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left');

        // Apply filters
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                switch ($key) {
                    case 'search':
                        $builder->groupStart()
                                ->like('properties.title', $value)
                                ->orLike('properties.property_code', $value)
                                ->orLike('properties.description', $value)
                                ->groupEnd();
                        break;
                    case 'is_active':
                        $builder->where('properties.is_active', $value === 'true' ? 1 : 0);
                        break;
                    default:
                        $builder->where("properties.{$key}", $value);
                        break;
                }
            }
        }

        $totalResults = $builder->countAllResults(false);
        $properties = $builder->orderBy('properties.created_at', 'DESC')
                             ->findAll($perPage, $offset);

        // Get main images for properties
        if (!empty($properties)) {
            $propertyIds = array_column($properties, 'id');
            $mainImages = $this->propertyImageModel->getMainImagesForProperties($propertyIds);

            $imagesByProperty = [];
            foreach ($mainImages as $image) {
                $imagesByProperty[$image['property_id']] = $image;
            }

            foreach ($properties as &$property) {
                $property['main_image'] = $imagesByProperty[$property['id']] ?? null;
            }
        }

        // Get filter options
        $propertyTypes = $this->propertyTypeModel->getAllTypes();
        $locations = $this->locationModel->getAllLocations();

        $data = [
            'title' => 'Gestión de Propiedades - Admin Elige Donde',
            'properties' => $properties,
            'property_types' => $propertyTypes,
            'locations' => $locations,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($totalResults / $perPage),
                'total_results' => $totalResults,
                'per_page' => $perPage
            ]
        ];

        return view('admin/properties/index', $data);
    }

    // Show create form
    public function create()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $data = [
            'title' => 'Crear Nueva Propiedad - Admin Elige Donde',
            'property_types' => $this->propertyTypeModel->getAllTypes(),
            'locations' => $this->locationModel->getAllLocations(),
            'agents' => $this->agentModel->getActiveAgents(),
            'features' => $this->propertyFeatureModel->getAllFeatures()
        ];

        return view('admin/properties/create', $data);
    }

    // Store new property
    public function store()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|max_length[255]',
            'property_type_id' => 'required|is_natural_no_zero',
            'transaction_type' => 'required|in_list[venta,alquiler,anticresis]',
            'price_pen' => 'permit_empty|decimal',
            'price_usd' => 'permit_empty|decimal',
            'location_id' => 'required|is_natural_no_zero',
            'address' => 'permit_empty',
            'bedrooms' => 'permit_empty|is_natural',
            'bathrooms' => 'permit_empty|is_natural',
            'agents' => 'required',
            'images.*' => 'permit_empty|uploaded[images]|max_size[images,5120]|is_image[images]',
            'pdf_document' => 'permit_empty|uploaded[pdf_document]|max_size[pdf_document,10240]|ext_in[pdf_document,pdf]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Generate property code
        $propertyCode = $this->propertyModel->generatePropertyCode();

        // Prepare property data
        $propertyData = [
            'property_code' => $propertyCode,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'property_type_id' => $this->request->getPost('property_type_id'),
            'transaction_type' => $this->request->getPost('transaction_type'),
            'price_pen' => $this->request->getPost('price_pen') ?: null,
            'price_usd' => $this->request->getPost('price_usd') ?: null,
            'location_id' => $this->request->getPost('location_id'),
            'address' => $this->request->getPost('address'),
            'latitude' => $this->request->getPost('latitude') ?: null,
            'longitude' => $this->request->getPost('longitude') ?: null,
            'land_area' => $this->request->getPost('land_area') ?: null,
            'built_area' => $this->request->getPost('built_area') ?: null,
            'bedrooms' => $this->request->getPost('bedrooms') ?: 0,
            'bathrooms' => $this->request->getPost('bathrooms') ?: 0,
            'half_bathrooms' => $this->request->getPost('half_bathrooms') ?: 0,
            'parking_spaces' => $this->request->getPost('parking_spaces') ?: 0,
            'floors' => $this->request->getPost('floors') ?: 0,
            'age_years' => $this->request->getPost('age_years') ?: 0,
            'featured' => $this->request->getPost('featured') ? 1 : 0,
            'is_active' => 1,
            'published_at' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Create property
            $propertyId = $this->propertyModel->insert($propertyData);

            if (!$propertyId) {
                throw new \Exception('Error al crear la propiedad');
            }

            // Assign agents
            $agents = $this->request->getPost('agents');
            if (!empty($agents)) {
                foreach ($agents as $index => $agentData) {
                    if (empty($agentData['agent_id'])) {
                        continue; // Skip if no agent ID provided
                    }
                    $this->propertyAgentModel->assignAgentToProperty($propertyId, $agentData['agent_id'], [
                        'is_primary' => $index === 0 ? true : false,
                        'role' => $agentData['role'] ?? 'principal',
                        'commission_percentage' => $agentData['commission'] ?? 0
                    ]);
                }
            }

            // Handle image uploads
            $images = $this->request->getFileMultiple('images');
            if (!empty($images)) {
                foreach ($images as $index => $image) {
                    if ($image->isValid() && !$image->hasMoved()) {
                        $fileName = $propertyCode . '_' . ($index + 1) . '.' . $image->getExtension();
                        $uploadPath = FCPATH . 'uploads/properties/images/';

                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0755, true);
                        }

                        if ($image->move($uploadPath, $fileName)) {
                            $this->propertyImageModel->insert([
                                'property_id' => $propertyId,
                                'image_url' => $fileName,
                                'alt_text' => $propertyData['title'],
                                'is_main' => $index === 0 ? 1 : 0,
                                'order_index' => $index + 1
                            ]);
                        }
                    }
                }
            }

            // Handle PDF upload
            $pdfFile = $this->request->getFile('pdf_document');
            if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
                $fileName = $propertyCode . '_document.pdf';
                $filePath = $this->propertyDocumentModel->generateFilePath($propertyCode, $fileName);
                $fullPath = FCPATH . $filePath;

                if ($pdfFile->move(dirname($fullPath), basename($fullPath))) {
                    $this->propertyDocumentModel->insert([
                        'property_id' => $propertyId,
                        'document_type' => 'pdf',
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_size' => $pdfFile->getSize(),
                        'uploaded_by' => session()->get('admin_id')
                    ]);
                }
            }

            // Handle features
            $features = $this->request->getPost('features');
            if (!empty($features)) {
                foreach ($features as $featureId => $value) {
                    if (!empty($value)) {
                        $this->propertyFeatureModel->assignToProperty($propertyId, $featureId, $value);
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }

            return redirect()->to('/admin/propiedades')->with('success', 'Propiedad creada exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error al crear la propiedad: ' . $e->getMessage());
        }
    }

    // Show edit form
    public function edit($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $property = $this->propertyModel->find($id);

        if (!$property) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get related data
        $property['images'] = $this->propertyImageModel->getPropertyImages($id);
        $property['agents'] = $this->propertyAgentModel->getPropertyAgents($id);
        $property['features'] = $this->propertyFeatureModel->getPropertyFeatures($id);
        $property['documents'] = $this->propertyDocumentModel->getPropertyDocuments($id);

        $data = [
            'title' => 'Editar Propiedad - Admin Elige Donde',
            'property' => $property,
            'property_types' => $this->propertyTypeModel->getAllTypes(),
            'locations' => $this->locationModel->getAllLocations(),
            'agents' => $this->agentModel->getActiveAgents(),
            'all_features' => $this->propertyFeatureModel->getAllFeatures()
        ];

        return view('admin/properties/edit', $data);
    }

    // Update property
    public function update($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $property = $this->propertyModel->find($id);

        if (!$property) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();

        $rules = [
            'title' => 'required|max_length[255]',
            'property_type_id' => 'required|is_natural_no_zero',
            'transaction_type' => 'required|in_list[venta,alquiler,anticresis]',
            'price_pen' => 'permit_empty|decimal',
            'price_usd' => 'permit_empty|decimal',
            'location_id' => 'required|is_natural_no_zero',
            'images.*' => 'permit_empty|uploaded[images]|max_size[images,5120]|is_image[images]',
            'pdf_document' => 'permit_empty|uploaded[pdf_document]|max_size[pdf_document,10240]|ext_in[pdf_document,pdf]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare update data
        $updateData = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'property_type_id' => $this->request->getPost('property_type_id'),
            'transaction_type' => $this->request->getPost('transaction_type'),
            'price_pen' => $this->request->getPost('price_pen') ?: null,
            'price_usd' => $this->request->getPost('price_usd') ?: null,
            'location_id' => $this->request->getPost('location_id'),
            'address' => $this->request->getPost('address'),
            'latitude' => $this->request->getPost('latitude') ?: null,
            'longitude' => $this->request->getPost('longitude') ?: null,
            'land_area' => $this->request->getPost('land_area') ?: null,
            'built_area' => $this->request->getPost('built_area') ?: null,
            'bedrooms' => $this->request->getPost('bedrooms') ?: 0,
            'bathrooms' => $this->request->getPost('bathrooms') ?: 0,
            'half_bathrooms' => $this->request->getPost('half_bathrooms') ?: 0,
            'parking_spaces' => $this->request->getPost('parking_spaces') ?: 0,
            'floors' => $this->request->getPost('floors') ?: 0,
            'age_years' => $this->request->getPost('age_years') ?: 0,
            'featured' => $this->request->getPost('featured') ? 1 : 0,
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Update property
            $this->propertyModel->update($id, $updateData);

            // Update agents
            $agents = $this->request->getPost('agents');
            if (!empty($agents)) {
                // Remove existing agent assignments
                $this->propertyAgentModel->where('property_id', $id)->delete();

                // Add new assignments
                foreach ($agents as $index => $agentData) {
                    $this->propertyAgentModel->assignAgentToProperty($id, $agentData['agent_id'], [
                        'is_primary' => $index === 0 ? true : false,
                        'role' => $agentData['role'] ?? 'principal',
                        'commission_percentage' => $agentData['commission'] ?? 0
                    ]);
                }
            }

            // Handle new image uploads
            $images = $this->request->getFiles('images');
            if (!empty($images)) {
                foreach ($images as $index => $image) {
                    if ($image->isValid() && !$image->hasMoved()) {
                        $existingImagesCount = $this->propertyImageModel->where('property_id', $id)->countAllResults();
                        $fileName = $property['property_code'] . '_' . ($existingImagesCount + $index + 1) . '.' . $image->getExtension();
                        $uploadPath = FCPATH . 'uploads/properties/images/';

                        if ($image->move($uploadPath, $fileName)) {
                            $this->propertyImageModel->insert([
                                'property_id' => $id,
                                'image_url' => $fileName,
                                'alt_text' => $updateData['title'],
                                'is_main' => $existingImagesCount === 0 && $index === 0 ? 1 : 0,
                                'order_index' => $existingImagesCount + $index + 1
                            ]);
                        }
                    }
                }
            }

            // Handle new PDF upload
            $pdfFile = $this->request->getFile('pdf_document');
            if ($pdfFile && $pdfFile->isValid() && !$pdfFile->hasMoved()) {
                $fileName = $property['property_code'] . '_document_' . time() . '.pdf';
                $filePath = $this->propertyDocumentModel->generateFilePath($property['property_code'], $fileName);
                $fullPath = FCPATH . $filePath;

                if ($pdfFile->move(dirname($fullPath), basename($fullPath))) {
                    $this->propertyDocumentModel->insert([
                        'property_id' => $id,
                        'document_type' => 'pdf',
                        'file_name' => $fileName,
                        'file_path' => $filePath,
                        'file_size' => $pdfFile->getSize(),
                        'uploaded_by' => session()->get('admin_id')
                    ]);
                }
            }

            // Update features
            $features = $this->request->getPost('features');
            if ($features !== null) {
                $this->propertyFeatureModel->updatePropertyFeatures($id, $features);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }

            return redirect()->to('/admin/propiedades')->with('success', 'Propiedad actualizada exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la propiedad: ' . $e->getMessage());
        }
    }

    // Delete property
    public function delete($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $authCheck;
        }

        $property = $this->propertyModel->find($id);

        if (!$property) {
            return $this->response->setJSON(['success' => false, 'message' => 'Propiedad no encontrada']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete images files
            $images = $this->propertyImageModel->getPropertyImages($id);
            foreach ($images as $image) {
                $imagePath = FCPATH . 'uploads/properties/images/' . $image['image_url'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete document files
            $documents = $this->propertyDocumentModel->getPropertyDocuments($id);
            foreach ($documents as $document) {
                $documentPath = FCPATH . $document['file_path'];
                if (file_exists($documentPath)) {
                    unlink($documentPath);
                }
            }

            // Delete property (cascade will handle related records)
            $this->propertyModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción');
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Propiedad eliminada exitosamente']);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la propiedad: ' . $e->getMessage()]);
        }
    }

    // Delete image
    public function deleteImage($imageId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $image = $this->propertyImageModel->find($imageId);

        if (!$image) {
            return $this->response->setJSON(['success' => false, 'message' => 'Imagen no encontrada']);
        }

        // Delete physical file
        $imagePath = FCPATH . 'uploads/properties/images/' . $image['image_url'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete database record
        if ($this->propertyImageModel->delete($imageId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Imagen eliminada exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la imagen']);
        }
    }

    // Delete document
    public function deleteDocument($documentId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        if ($this->propertyDocumentModel->deleteDocument($documentId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Documento eliminado exitosamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el documento']);
        }
    }

    // Set main image
    public function setMainImage($propertyId, $imageId)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        if ($this->propertyImageModel->setMainImage($propertyId, $imageId)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Imagen principal establecida']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al establecer imagen principal']);
        }
    }

    // Toggle featured status
    public function toggleFeatured($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $property = $this->propertyModel->find($id);

        if (!$property) {
            return $this->response->setJSON(['success' => false, 'message' => 'Propiedad no encontrada']);
        }

        $newStatus = $property['featured'] ? 0 : 1;

        if ($this->propertyModel->update($id, ['featured' => $newStatus])) {
            $message = $newStatus ? 'Propiedad marcada como destacada' : 'Propiedad removida de destacadas';
            return $this->response->setJSON(['success' => true, 'message' => $message, 'featured' => $newStatus]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el estado']);
        }
    }

    // Toggle active status
    public function toggleActive($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck !== true) {
            return $this->response->setJSON(['success' => false, 'message' => 'No autorizado']);
        }

        $property = $this->propertyModel->find($id);

        if (!$property) {
            return $this->response->setJSON(['success' => false, 'message' => 'Propiedad no encontrada']);
        }

        $newStatus = $property['is_active'] ? 0 : 1;

        if ($this->propertyModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'Propiedad activada' : 'Propiedad desactivada';
            return $this->response->setJSON(['success' => true, 'message' => $message, 'is_active' => $newStatus]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el estado']);
        }
    }
}
