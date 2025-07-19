<?php

namespace App\Controllers;

use App\Models\PropertyModel;
use App\Models\PropertyTypeModel;
use App\Models\LocationModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyFeatureModel;
use App\Models\InquiryModel;
use App\Models\AgentModel;

class Property extends BaseController
{
    protected $propertyModel;
    protected $propertyTypeModel;
    protected $locationModel;
    protected $propertyImageModel;
    protected $propertyFeatureModel;
    protected $inquiryModel;
    protected $agentModel;

    public function __construct()
    {
        $this->propertyModel = new PropertyModel();
        $this->propertyTypeModel = new PropertyTypeModel();
        $this->locationModel = new LocationModel();
        $this->propertyImageModel = new PropertyImageModel();
        $this->propertyFeatureModel = new PropertyFeatureModel();
        $this->inquiryModel = new InquiryModel();
        $this->agentModel = new AgentModel();
    }

    // Listado de propiedades
    public function index()
    {
        $request = $this->request;
        $page = (int) ($request->getGet('page') ?? 1);
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // Obtener filtros de la URL
        $filters = [
            'transaction_type' => $request->getGet('transaction_type'),
            'property_type_id' => $request->getGet('property_type_id'),
            'location_id' => $request->getGet('location_id'),
            'price_min' => $request->getGet('price_min'),
            'price_max' => $request->getGet('price_max'),
            'currency' => $request->getGet('currency') ?: 'pen',
            'bedrooms' => $request->getGet('bedrooms'),
            'bathrooms' => $request->getGet('bathrooms'),
            'search' => $request->getGet('search')
        ];

        // Limpiar filtros vacíos
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        // Realizar búsqueda
        $searchBuilder = $this->propertyModel->searchProperties($filters);
        $totalResults = $searchBuilder->countAllResults(false);
        $properties = $searchBuilder->findAll($perPage, $offset);

        // Obtener imágenes principales
        if (!empty($properties)) {
            $propertyIds = array_column($properties, 'id');
            $mainImages = $this->propertyImageModel->getImagesForProperties($propertyIds);

            $imagesByProperty = [];
            foreach ($mainImages as $image) {
                $imagesByProperty[$image['property_id']][] = $image;
            }

            $propertyAgentModel = new \App\Models\PropertyAgentModel();

            foreach ($properties as &$property) {
                $property['images'] = $imagesByProperty[$property['id']] ?? null;

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

        // Datos para los filtros
        $propertyTypes = $this->propertyTypeModel->getAllTypes();
        $locations = $this->locationModel->getAllLocations();

        // Paginación
        $totalPages = ceil($totalResults / $perPage);

        $data = [
            'title' => 'Propiedades - Elige Donde',
            'properties' => $properties,
            'property_types' => $propertyTypes,
            'locations' => $locations,
            'filters' => $filters,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_results' => $totalResults,
                'per_page' => $perPage
            ]
        ];

        return view('property/index', $data);
    }

    // Página de búsqueda
    public function search()
    {
        // Esta es la misma funcionalidad que index, redirigir
        return $this->index();
    }

    // Detalle de propiedad
    public function detail($propertyCode)
    {
        // Obtener propiedad por código
        $property = $this->propertyModel->getPropertyByCode($propertyCode);

        if (!$property) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Obtener imágenes de la propiedad
        $images = $this->propertyImageModel->getPropertyImages($property['id']);

        // Obtener características de la propiedad agrupadas por categoría
        $features = $this->propertyFeatureModel->getPropertyFeaturesByCategory($property['id']);

        // Obtener propiedades relacionadas (mismo agente o misma ubicación)
        $relatedProperties = $this->propertyModel->select('
                properties.*,
                property_types.name as property_type_name,
                locations.name as location_name
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->join('locations', 'locations.id = properties.location_id', 'left')
            ->where('properties.id !=', $property['id'])
            ->where('properties.is_active', 1)
            ->groupStart()
                //->where('properties.agent_id', $property['agent_id'])
                ->where('properties.location_id', $property['location_id'])
            ->groupEnd()
            ->orderBy('properties.featured', 'DESC')
            ->findAll(6);

        // Obtener imágenes principales para propiedades relacionadas
        if (!empty($relatedProperties)) {
            $relatedIds = array_column($relatedProperties, 'id');
            $relatedImages = $this->propertyImageModel->getMainImagesForProperties($relatedIds);

            $relatedImagesByProperty = [];
            foreach ($relatedImages as $image) {
                $relatedImagesByProperty[$image['property_id']] = $image;
            }

            foreach ($relatedProperties as &$relatedProperty) {
                $relatedProperty['main_image'] = $relatedImagesByProperty[$relatedProperty['id']] ?? null;
            }
        }

        $data = [
            'title' => $property['title'] . ' - Elige Donde',
            'property' => $property,
            'images' => $images,
            'features' => $features,
            'related_properties' => $relatedProperties
        ];

        return view('property/detail', $data);
    }

    // Procesar formulario de consulta de propiedad
    public function inquiry()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back();
        }

        $validation = \Config\Services::validation();

        $rules = [
            'property_id' => 'required|is_natural_no_zero',
            'agent_id' => 'required|is_natural_no_zero',
            'name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty|max_length[20]',
            'message' => 'permit_empty',
            'inquiry_type' => 'permit_empty|in_list[info,visit,call,email]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }

        $inquiryData = [
            'property_id' => $this->request->getPost('property_id'),
            'agent_id' => $this->request->getPost('agent_id'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'message' => $this->request->getPost('message'),
            'inquiry_type' => $this->request->getPost('inquiry_type') ?: 'info'
        ];

        $inquiryId = $this->inquiryModel->createInquiry($inquiryData);

        if ($inquiryId) {
            // Enviar notificación al agente
            $this->inquiryModel->notifyAgent($inquiryId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tu consulta ha sido enviada correctamente. El agente se pondrá en contacto contigo pronto.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error al enviar la consulta. Inténtalo nuevamente.'
            ]);
        }
    }

    // Búsqueda por AJAX para autocompletado
    public function searchSuggestions()
    {
        $query = $this->request->getGet('q');

        if (strlen($query) < 2) {
            return $this->response->setJSON([]);
        }

        $suggestions = [];

        // Buscar en títulos de propiedades
        $properties = $this->propertyModel->select('title, property_code')
                                        ->like('title', $query)
                                        ->where('is_active', 1)
                                        ->findAll(5);

        foreach ($properties as $property) {
            $suggestions[] = [
                'type' => 'property',
                'text' => $property['title'],
                'url' => site_url('propiedad/' . $property['property_code'])
            ];
        }

        // Buscar en ubicaciones
        $locations = $this->locationModel->searchLocations($query);
        foreach (array_slice($locations, 0, 3) as $location) {
            $suggestions[] = [
                'type' => 'location',
                'text' => $location['name'] . ', ' . $location['district'],
                'url' => site_url('buscar-propiedades?location_id=' . $location['id'])
            ];
        }

        return $this->response->setJSON($suggestions);
    }

    // Generar mapa de propiedades para una ubicación
    public function mapData()
    {
        $locationId = $this->request->getGet('location_id');

        $properties = $this->propertyModel->select('
                properties.id,
                properties.title,
                properties.property_code,
                properties.price_pen,
                properties.price_usd,
                properties.latitude,
                properties.longitude,
                properties.transaction_type,
                property_types.name as property_type_name
            ')
            ->join('property_types', 'property_types.id = properties.property_type_id', 'left')
            ->where('properties.is_active', 1)
            ->where('properties.latitude IS NOT NULL')
            ->where('properties.longitude IS NOT NULL');

        if ($locationId) {
            $properties->where('properties.location_id', $locationId);
        }

        $mapProperties = $properties->findAll();

        return $this->response->setJSON([
            'properties' => $mapProperties
        ]);
    }
}
