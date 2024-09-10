<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
//Definimos la pagina de inicio de la app//
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->get('Usuarios', 'Usuarios::usuarios');
$routes->get('google_login', 'Login::google_login');
$routes->get('welcome/index', 'Welcome::index');




$routes->get('login/'          , 'Login::index'                   );
$routes->get('Verifylogin'     , 'Verifylogin::index'             );
$routes->post('Verifylogin', 'Verifylogin::index');

$routes->post('familia_productos/add_familia_producto', 'FamiliaProductos::add_familia_producto');
$routes->get('/productos/(:num)', 'Productos::show/$1');

//Rutas fichajes

$routes->get('presentes/(:num)'             , 'Fichar::index/$1'        );
$routes->get('ausentes'             , 'Fichar::ausentes'        );
$routes->get('entrar/(:num)'        , 'Fichar::entrar/$1'       );
$routes->get('entra/(:num)'         , 'Fichar::entra/$1'        );
$routes->get('entraextras/(:num)'   , 'Fichar::entraextras/$1'  );
$routes->get('salir/(:num)'         , 'Fichar::salir/$1'        );
$routes->get('sal/(:num)'           , 'Fichar::sal/$1'          );
//END Fichajes
$routes->get('login/'                                , 'Login::index'                   );
$routes->get('password/edit/(:num)/(:num)'           , 'Password::edit/$1/$2'          );
$routes->get('Contactos_empresa/borrar/(:num)'       , 'Contactos_empresa::borrar/$1'   );
$routes->get('Contactos_empresa/add/(:num)'              , 'Contactos_empresa::add/$1'    );
$routes->get('Contactos_empresa/add/'                    , 'Contactos_empresa::add/*'    );

$routes->add('partes/print/(\d+)', 'Partes_controller::parte_print/$1');
$routes->add('partes/printproveedor/(\d+)', 'Partes_controller_proveedor::parte_print/$1');
$routes->add('pedidos/print/(\d+)', 'Pedido_print_controller::pedido_print/$1');
$routes->add('pedidos_proveedor/print/(\d+)', 'Pedido_print_controller::pedido_print_proveedor/$1');
$routes->add('pedidos/parte_complejo/(\d+)', 'Parte_complejo::pedido_print/$1');
$routes->add('informe_detalle/(\d+)', 'Informe_detalle::/$1');
$routes->add('Submenus/(\d+)', 'Submenus::/$1');
$routes->add('Publicaciones/(\d+)', 'Publicaciones::/$1');
$routes->add('Acceso/(\d+)', 'Acceso::/$1');

$routes->get('usuarios/getNombreUsuario/(:num)', 'Usuarios::getNombreUsuario/$1');

$routes->get('usuarios/edit/(:num)', 'Usuarios::edit/$1');

$routes->get('/Index', 'Index::index');
$routes->get('index/(:any)', 'Index::index/$1');

$routes->get('usuarios/(:num)', 'Usuarios::show/$1');


$routes->post('procesos_pedidos/actualizarEstadoProcesos', 'Procesos_pedidos::actualizarEstadoProcesos');

$routes->get('presentes/(:any)', 'Presentes::index/$1');

$routes->get('presentes', 'Fichar::index');

$routes->get('comparadorproductos/(:num)', 'ComparadorProductos::index/$1');

$routes->get('familiaproveedor', 'Familia_proveedor::index');