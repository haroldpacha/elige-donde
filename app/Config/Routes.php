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

// Rutas administrativas (opcional)
$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->resource('propiedades', ['controller' => 'Properties']);
    $routes->resource('agentes', ['controller' => 'Agents']);
    $routes->get('consultas', 'Inquiries::index');
});

// Rutas de API para filtros dinámicos
$routes->group('api', function($routes) {
    $routes->get('property-types', 'Api::getPropertyTypes');
    $routes->get('locations', 'Api::getLocations');
    $routes->get('price-range', 'Api::getPriceRange');
});
