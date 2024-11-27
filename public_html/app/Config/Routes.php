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
$routes->add('pedidos/imprimir_parte/(:num)', 'Pedidos::imprimir_parte/$1');
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
$routes->post('Ruta_pedido/guardarRuta', 'Ruta_pedido::guardarRuta');
$routes->get('elegirProveedor/(:num)', 'Proveedores::elegirProveedor/$1');
$routes->post('productos_necesidad/save', 'Productos_necesidad::save');
$routes->get('pedidos_proveedor', 'Pedidos_proveedor::todos');
$routes->get('familiaProveedor/getFamiliasProveedores', 'Familia_proveedor::getFamiliasProveedores');
$routes->match(['GET', 'POST'], 'familiaProveedor/editar/(:num)', 'Familia_proveedor::editar/$1');
$routes->post('familiaProveedor/actualizarFamilia', 'Familia_proveedor::actualizarFamilia');
$routes->post('familiaProveedor/agregarFamilia', 'Familia_proveedor::agregarFamilia');
$routes->post('familiaProveedor/eliminar/(:num)', 'Familia_proveedor::eliminarFamilia/$1');
$routes->post('ofertas/eliminar/(:num)/(:num)', 'ComparadorProductos::eliminarOferta/$1/$2');
$routes->get('contactos/getContactosPorEmpresa/(:num)', 'Empresas::getContactosPorEmpresa/$1');
$routes->post('contactos/agregar', 'Empresas::agregarContacto');
$routes->post('contactos/eliminarContacto/(:num)', 'Empresas::eliminarContacto/$1');
$routes->get('empresas/getContacto/(:num)', 'Empresas::getContacto/$1');
$routes->get('contactos', 'Contactos::index');
$routes->get('contactos/getContactos', 'Contactos::getContactos');
$routes->post('contactos/agregarContacto', 'Contactos::agregarContacto');
$routes->post('contactos/actualizarContacto/(:num)', 'Contactos::actualizarContacto/$1');
$routes->post('contactos/eliminarContacto/(:num)', 'Contactos::eliminarContacto/$1');
$routes->get('contactos/getContacto/(:num)', 'Contactos::getContacto/$1');
$routes->delete('productos/eliminarImagen/(:num)', 'Productos::eliminarImagen/$1');
$routes->post('familia_productos/eliminar/(:num)', 'Familia_productos::eliminarFamilia/$1');
$routes->get('/maquinas', 'MaquinasController::index');
$routes->get('/maquinas/getMaquinas', 'MaquinasController::getMaquinas');
$routes->post('/maquinas/agregarMaquina', 'MaquinasController::agregarMaquina');
$routes->post('/maquinas/actualizarMaquina', 'MaquinasController::actualizarMaquina');
$routes->post('/maquinas/eliminar/(:num)', 'MaquinasController::eliminarMaquina/$1');
$routes->get('/maquinas/editar/(:num)', 'MaquinasController::editar/$1');
$routes->get('productos/procesos/(:num)', 'Productos::verProcesos/$1');
$routes->post('productos/updateOrder', 'Productos::updateOrder');
$routes->get('usuarios/datosAcceso/(:num)', 'Usuarios::datosAcceso/$1');
$routes->delete('usuarios/eliminar/(:num)', 'Usuarios::eliminarUsuario/$1');
$routes->get('editar_rutas/(:num)', 'Rutas::editar_ruta/$1');
$routes->post('rutas/updateRuta/(:num)', 'Rutas::updateRuta/$1');
$routes->get('rutas/add_ruta', 'Rutas::add_ruta');
$routes->post('rutas/addRuta', 'Rutas::addRuta');

