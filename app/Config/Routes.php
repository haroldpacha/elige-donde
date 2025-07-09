<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// Rutas principales
$routes->get('/', 'Home::index');
$routes->get('inicio', 'Home::index');

// Rutas de búsqueda y propiedades
$routes->get('buscar-propiedades', 'Property::search');
$routes->get('propiedades', 'Property::index');
$routes->get('propiedad/(:any)', 'Property::detail/$1');
$routes->post('buscar', 'Property::searchResults');

// Rutas de agentes
$routes->get('asesores', 'Agent::index');
$routes->get('asesor/(:num)', 'Agent::profile/$1');

// Rutas de contacto
$routes->post('contacto', 'Contact::send');
$routes->post('solicitar-informacion', 'Contact::propertyInquiry');

// Rutas de ubicaciones (AJAX)
$routes->get('api/ubicaciones/(:any)', 'Location::getByDepartment/$1');

// Rutas administrativas
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    // Autenticación
    $routes->get('login', 'Auth::login');
    $routes->post('auth/process-login', 'Auth::processLogin');
    $routes->get('auth/logout', 'Auth::logout');
    $routes->get('auth/forgot-password', 'Auth::forgotPassword');
    $routes->post('auth/process-forgot-password', 'Auth::processForgotPassword');
    $routes->get('auth/profile', 'Auth::profile');
    $routes->post('auth/update-profile', 'Auth::updateProfile');
    $routes->post('auth/change-password', 'Auth::changePassword');

    // Dashboard principal
    $routes->get('/', 'Dashboard::index');
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('analiticas', 'Dashboard::analytics');
    $routes->get('configuracion', 'Dashboard::settings');
    $routes->get('actividad', 'Dashboard::activityLog');

    // Gestión de propiedades
    $routes->get('propiedades', 'Properties::index');
    $routes->get('propiedades/crear', 'Properties::create');
    $routes->post('propiedades/store', 'Properties::store');
    $routes->get('propiedades/edit/(:num)', 'Properties::edit/$1');
    $routes->post('propiedades/update/(:num)', 'Properties::update/$1');
    $routes->delete('propiedades/delete/(:num)', 'Properties::delete/$1');
    $routes->post('propiedades/toggle-featured/(:num)', 'Properties::toggleFeatured/$1');
    $routes->post('propiedades/toggle-active/(:num)', 'Properties::toggleActive/$1');
    $routes->delete('propiedades/delete-image/(:num)', 'Properties::deleteImage/$1');
    $routes->delete('propiedades/delete-document/(:num)', 'Properties::deleteDocument/$1');
    $routes->post('propiedades/set-main-image/(:num)/(:num)', 'Properties::setMainImage/$1/$2');

    // Gestión de agentes
    $routes->resource('agentes', ['controller' => 'Agents']);

    // Gestión de consultas
    $routes->get('consultas', 'Inquiries::index');
    $routes->get('consultas/(:num)', 'Inquiries::view/$1');
    $routes->post('consultas/update-status/(:num)', 'Inquiries::updateStatus/$1');

    // Gestión de contenido
    $routes->resource('tipos-propiedades', ['controller' => 'PropertyTypes']);
    $routes->resource('ubicaciones', ['controller' => 'Locations']);
    $routes->resource('caracteristicas', ['controller' => 'Features']);

    // Gestión de usuarios administrativos
    $routes->resource('usuarios', ['controller' => 'AdminUsers']);

    // Documentos
    $routes->get('documentos', 'Documents::index');
    $routes->get('documentos/download/(:num)', 'Documents::download/$1');

    // Reportes
    $routes->get('reportes', 'Reports::index');
    $routes->get('reportes/propiedades', 'Reports::properties');
    $routes->get('reportes/agentes', 'Reports::agents');
    $routes->get('reportes/consultas', 'Reports::inquiries');
    $routes->post('reportes/export', 'Reports::export');
});

// Rutas de API para filtros dinámicos
$routes->group('api', function($routes) {
    $routes->get('property-types', 'Api::getPropertyTypes');
    $routes->get('locations', 'Api::getLocations');
    $routes->get('price-range', 'Api::getPriceRange');
});
