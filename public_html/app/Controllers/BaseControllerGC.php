<?php
namespace App\Controllers;
use App\Models\MenuModel;
use App\Models\Usuarios2_Model;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\GroceryCrudEnterprise as ConfigGroceryCrud;
use GroceryCrud\Core\GroceryCrud;
use Config\Database as ConfigDatabase;
class BaseControllerGC extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $db;
    protected $output = [];
    protected $crud;
    protected $nivel;
    protected $data;
    protected $amiga = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        // Cargar helper de control de acceso
        helper('controlacceso');
        helper('url');
        // Ejecutar la lógica de control de acceso
        control_login();
        $this->nivel=control_login();
        //Cargo los archivos para que GroceryCRud no de error
        $this->output = (object)[
            'js_files' => [],
            'output' => ''
        ];
        // Obtener los datos del usuario y almacenarlos en $data
        $this->data = datos_user();
        // Establecer la conexión a la base de datos especificada en $this->data['new_db']
        $this->db = db_connect($this->data['new_db']);            
        // Crear una instancia del modelo de menú
        $menuModel = new MenuModel($this->db);
        // Obtener todos los elementos del menú desde la base de datos
        $menuItems = $menuModel->orderBy('posicion', 'asc')->findAll();
        // Estructurar los datos del menú para la vista
        $structuredMenu = $this->buildMenuStructure($menuItems);
        // Creo una instancia del modelo de usuarios para sacar el nombre y la foto del user activo
        $datosUsuario = new Usuarios2_Model($this->db);


        $session = session();
        $sessionData = $session->get('logged_in');

        $itemsUsuario = $datosUsuario->find($this->data['id_user']);


        if ($itemsUsuario !== null) {
            $this->data['nombre_usuario'] = $itemsUsuario['nombre_usuario'];
            $this->data['apellidos_usuario'] = $itemsUsuario['apellidos_usuario'];
            $this->data['userfoto'] = $itemsUsuario['userfoto'];
        } else {
        // Si $itemsUsuario es nulo, obtenemos los datos de la sesión
            if ($sessionData !== null) {
                $this->data['nombre_usuario'] = $sessionData['nombre_usuario'];
                $this->data['apellidos_usuario'] = $sessionData['apellidos_usuario'];
                $this->data['userfoto'] = array_key_exists('userfoto', $sessionData) ? $sessionData['userfoto'] : '';
            } 
        }

        // Determina la página actual
        $path =  $request->getUri();
        $segments = explode('/', $path);
        $currentPage = end($segments);
        // Pasar los datos del menú y la página actual a la vista
        $this->data['menu'] = $structuredMenu;
        $this->data['currentPage'] = $currentPage;
        return view('partials/menu_lateral', $this->data);
        $output = (object)[
            'js_files' => [],
            'output' => ''
        ];
    }
    protected function _getDbData() {
        $db = (new ConfigDatabase())->default;
        $data=datos_user();
        $db = $data['new_db'];
        return [
            'adapter' => [
                'driver' => 'Pdo_Mysql',
                'host'     => $db['hostname'],
                'database' => $db['database'],
                'username' => $db['username'],
                'password' => $db['password'],
                'charset' => 'utf8'
            ]
        ];
    }
    
    protected function _GC_output($layout, $output = null) {
        if (isset($output->isJSONResponse) && $output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }
        return view($layout, (array)$output);
    }
    protected function _getClientDatabase($bootstrap = true, $jquery = true) {
        $db = $this->_getDbData();
        $config = (new ConfigGroceryCrud())->getDefaultConfig();
        $crud = new GroceryCrud($config, $db);
        $crud->setCsrfTokenName(csrf_token());
        $crud->setCsrfTokenValue(csrf_hash());
        return $crud; 
    }
    protected function _getDb1Data() {
        $db = (new \Config\Database())->default;
        return [
        'adapter' => [
        'driver' => 'Pdo_Mysql',
        'host'     => $db['hostname'],
        'database' => $db['database'],
        'username' => $db['username'],
        'password' => $db['password'],
        'charset' => 'utf8'
        ]
        ];
        }
        
    protected function _getDefaultDatabase($bootstrap = true, $jquery = true) {
        $db1 = $this->_getDb1Data();
        //print_r($db1);
        $config = (new ConfigGroceryCrud())->getDefaultConfig();
        $crud = new GroceryCrud($config, $db1);
        return $crud; 
    }
 
    // Método para estructurar los datos del menú
    protected function buildMenuStructure($menuItems, $parent = 0)
    {
        $menu = [];
        foreach ($menuItems as $menuItem) {
            if ($menuItem['dependencia'] == $parent) {
                $menuItem['submenu'] = $this->buildMenuStructure($menuItems, $menuItem['id']);
                $menu[] = $menuItem;
            }
        }
        return $menu;
    }

//TABLA LOG
protected function logAction($seccion, $log, $stateParameters) {
    // Crear una instancia del modelo Log y Usuarios2_Model
    $logModel = new \App\Models\Log_model($this->db);
    $datosUsuario = new Usuarios2_Model($this->db);

    // Intentar obtener los datos del usuario de la base de datos
    $itemsUsuario = $datosUsuario->find($this->data['id_user']);
    if ($itemsUsuario !== null) {
        $nombre_usuario = $itemsUsuario['nombre_usuario'];
        $apellidos_usuario = $itemsUsuario['apellidos_usuario'];
    } else {
        // Si $itemsUsuario es nulo, obtenemos los datos de la sesión
        $session = session();
        $sessionData = $session->get('logged_in');
        if ($sessionData !== null) {
            $nombre_usuario = array_key_exists('nombre_usuario', $sessionData) ? $sessionData['nombre_usuario'] : '';
            $apellidos_usuario = array_key_exists('apellidos_usuario', $sessionData) ? $sessionData['apellidos_usuario'] : '';
        } else {
            // Los datos de la sesión no existen, salir de la función
            return;
        }
    }
    // Crear un nuevo registro de log
    $data = [
        'fecha' => date('Y-m-d H:i:s'), // Fecha y hora actual
        'id_usuario' => $nombre_usuario . ' ' . $apellidos_usuario,
        'log' => $log,
        'seccion' => $seccion
    ];

    // Insertar el registro de log en la base de datos
    $logModel->insert($data);
}

public function addBreadcrumb($title, $link = '#')
{
    $this->amiga[] = ['title' => $title, 'link' => $link];
}

public function getBreadcrumbs()
{
    return $this->amiga;
}
}
