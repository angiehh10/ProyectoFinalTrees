<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index'); // Página principal
$routes->get('/login', 'Home::login'); // Mostrar formulario de inicio de sesión
$routes->post('/login', 'Home::processLogin'); // Procesar inicio de sesión
$routes->get('/logout', 'Home::logout'); // Ruta para cerrar sesión
$routes->get('/admin/viewFriendTrees/(:num)', 'Admin::getFriendTrees/$1', ['filter' => 'auth:Administrador']);
$routes->get('/admin', 'Admin::index', ['filter' => 'auth:Administrador']); // Solo para administradores
$routes->get('/amigo', 'Amigo::index', ['filter' => 'auth:Amigo']); // Solo para administradores
$routes->get('/register', 'Home::register'); // Formulario de registro
$routes->post('/register/store', 'Home::store'); // Procesar registro
$routes->post('/admin/createSpecies', 'Admin::createSpecies', ['filter' => 'auth:Administrador']);
$routes->post('/admin/updateSpecies', 'Admin::updateSpecies', ['filter' => 'auth:Administrador']);
$routes->get('/admin/deleteSpecies/(:num)', 'Admin::deleteSpecies/$1', ['filter' => 'auth:Administrador']);
$routes->post('/admin/createTree', 'Admin::createTree', ['filter' => 'auth:Administrador']);
$routes->post('/admin/updateTree', 'Admin::updateTree', ['filter' => 'auth:Administrador']);
$routes->get('/operador', 'Operador::index', ['filter' => 'auth:Operador']); // Dashboard del Operador
$routes->get('/operador/historial/(:num)', 'Operador::historial/$1', ['filter' => 'auth:Operador']); // Historial de actualizaciones de un árbol
$routes->post('/operador/actualizacion', 'Operador::registrarActualizacion', ['filter' => 'auth:Operador']); // Registrar actualización de árbol



