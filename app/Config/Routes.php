<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// **Rutas Públicas**
$routes->get('/', 'Home::index'); // Página principal
$routes->get('/login', 'Auth::login'); // Mostrar formulario de inicio de sesión
$routes->post('/login', 'Home::processLogin'); // Procesar inicio de sesión
$routes->get('/logout', 'Home::logout'); // Ruta para cerrar sesión
$routes->get('/register', 'Home::register'); // Formulario de registro
$routes->post('/register/store', 'Home::store'); // Procesar registro de usuario


// **Rutas para Administradores**
$routes->get('/admin', 'Admin::index', ['filter' => 'auth:Administrador']); // Dashboard del administrador
$routes->get('/admin/viewFriendTrees/(:num)', 'Admin::viewFriendTrees/$1', ['filter' => 'auth:Administrador']); // Ver árboles de un amigo
$routes->post('/admin/viewFriendTrees/(:num)', 'Admin::viewFriendTrees/$1', ['filter' => 'auth:Administrador']); // Actualizar el arbol
$routes->post('/admin/crearUsuario', 'Admin::crearUsuario', ['filter' => 'auth:Administrador']); // Crear un nuevo usuario

// Gestión de Especies
$routes->post('/admin/createSpecies', 'Admin::createSpecies', ['filter' => 'auth:Administrador']); // Crear una nueva especie
$routes->post('admin/updateEspecie/(:num)', 'Admin::updateSpecies/$1' , ['filter' => 'auth:Administrador']); // Actualizar una especie
$routes->get('/admin/deleteSpecies/(:num)', 'Admin::deleteSpecies/$1', ['filter' => 'auth:Administrador']); // Eliminar una especie
$routes->post('admin/deleteEspecie/(:num)', 'Admin::deleteSpecies/$1'); // Eliminar una especie

// Gestión de Árboles
$routes->post('/admin/createTree', 'Admin::createTree', ['filter' => 'auth:Administrador']); // Crear un nuevo árbol
$routes->post('/admin/updateTree', 'Admin::updateTree', ['filter' => 'auth:Administrador']); // Actualizar información de un árbol

// Historial y Actualizaciones
$routes->post('/admin/registrarActualizacion', 'Admin::registrarActualizacion', ['filter' => 'auth:Administrador']); // Registrar una actualización
$routes->get('/admin/historial/(:num)', 'Admin::historial/$1', ['filter' => 'auth:Administrador']); // Historial de cambios de un árbol
$routes->get('/admin/historial', 'Admin::historial', ['filter' => 'auth:Administrador']); // Ver historial general


// **Rutas para Amigos**
$routes->get('/amigo', 'Amigo::index', ['filter' => 'auth:Amigo']); // Dashboard principal del amigo
$routes->get('/amigo/comprar/(:num)', 'Amigo::getCompra/$1', ['filter' => 'auth:Amigo']);
$routes->post('/amigo/comprar/', 'Amigo::postCompra/$1', ['filter' => 'auth:Amigo']);
$routes->get('/amigo/detalles/(:num)', 'Amigo::detalles/$1', ['filter' => 'auth:Amigo']); // Ver detalles de un árbol (por ID)
$routes->get('/amigo/actualizaciones/(:num)', 'Amigo::actualizaciones/$1', ['filter' => 'auth:Amigo']); // Ver actualizaciones de un árbol


// **Rutas para Operadores**
$routes->get('/operador', 'Operador::index', ['filter' => 'auth:Operador']); // Dashboard del Operador
$routes->post('/operador/registrarActualizacion', 'Operador::registrarActualizacion', ['filter' => 'auth:Operador']); // Registrar actualización
$routes->get('/operador/historial/(:num)', 'Operador::historial/$1', ['filter' => 'auth:Operador']); // Historial de un árbol

