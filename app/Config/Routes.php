<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index'); // Página principal
$routes->get('/login', 'Home::login'); // Mostrar formulario de inicio de sesión
$routes->post('/login', 'Home::processLogin'); // Procesar inicio de sesión
$routes->get('/logout', 'Home::logout'); // Ruta para cerrar sesión
$routes->get('admin/viewFriendTrees/(:num)', 'Admin::viewFriendTrees/$1', ['filter' => 'auth:Administrador']);
$routes->get('/admin', 'Admin::index', ['filter' => 'auth:Administrador']); // Solo para administradores
$routes->post('admin/updateTree', 'Admin::updateTree');
$routes->get('/amigo', 'Amigo::index', ['filter' => 'auth:Amigo']); // Solo para amigos
$routes->get('amigo/comprar/(:num)', 'Amigo::comprar/$1', ['filter' => 'auth:Amigo']);
$routes->post('amigo/comprar/(:num)', 'Amigo::comprar/$1', ['filter' => 'auth:Amigo']);
$routes->get('/amigo/detalles/(:num)', 'Amigo::detalles/$1', ['filter' => 'auth:Amigo']);
$routes->get('/amigo/actualizaciones/(:num)', 'Amigo::actualizaciones/$1', ['as' => 'amigo.actualizaciones']);
$routes->post('/admin/crearUsuario', 'Admin::crearUsuario', ['filter' => 'auth:Administrador']);
$routes->get('/register', 'Home::register'); // Formulario de registro
$routes->post('/register/store', 'Home::store'); // Procesar registro
$routes->post('/admin/createSpecies', 'Admin::createSpecies', ['filter' => 'auth:Administrador']);
$routes->post('/admin/updateSpecies', 'Admin::updateSpecies', ['filter' => 'auth:Administrador']);
$routes->get('/admin/deleteSpecies/(:num)', 'Admin::deleteSpecies/$1', ['filter' => 'auth:Administrador']);
$routes->post('/admin/createTree', 'Admin::createTree', ['filter' => 'auth:Administrador']);
$routes->post('/admin/updateTree', 'Admin::updateTree', ['filter' => 'auth:Administrador']);

