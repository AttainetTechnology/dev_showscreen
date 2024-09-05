<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use Config\Database;

class BaseFichar extends Controller
{
    protected $db; // Para almacenar la conexión a la base de datos

//  Esta función se llama automáticamente al crear una instancia de BaseFichar.
// Establece la conexión a la base de datos y la asigna a $this->db.
    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->db = $this->setupDatabaseConnection();
    }

    protected function setupDatabaseConnection()
    {
        // Iniciar el servicio de sesión
        $session = \Config\Services::session();

        // Si la sesión ya tiene el NIF y el nombre de la empresa, usarlos
        if ($session->has('NIF') && $session->has('nombre_empresa')) {
            $nif = $session->get('NIF');
        } else {
            // Si no, obtener el NIF de la URL
            $nif = $this->request->getUri()->getSegment(2);
        }

        $db = $this->createDatabaseConnection($nif);

        return $db;
    }

    protected function createDatabaseConnection($nif)
    {
        // Cargar el modelo
        $dbConnectionsModel = new \App\Models\DbConnections_Model();

        // Obtener la fila que coincide con el NIF proporcionado
        $dbConfigRow = $dbConnectionsModel->where('NIF', $nif)->first();

        // Si la empresa existe, obtener la configuración de la base de datos. Si no, devolver null.
        if ($dbConfigRow) {
            // Construir el array de configuración
            $new_db = [
                'DSN'      => '',
                'hostname' => 'localhost', 
                'username' => $dbConfigRow['db_user'],
                'password' => $dbConfigRow['db_password'],
                'database' => $dbConfigRow['db_name'],
                'DBDriver' => 'MySQLi',
                'DBPrefix' => '',
                'pConnect' => false,
                'DBDebug'  => (ENVIRONMENT !== 'production'),
                'cacheOn'  => false,
                'cacheDir' => '',
                'charset'  => 'utf8',
                'DBCollat' => 'utf8_general_ci',
                'swapPre'  => '',
                'encrypt'  => false,
                'compress' => false,
                'strictOn' => false,
                'failover' => [],
                'port'     => 3306,
            ];
           
            // Conectar a la base de datos del cliente
            $db = Database::connect($new_db);

            // Iniciar el servicio de sesión
            $session = \Config\Services::session();

            // Guardar el NIF y el nombre de la empresa en la sesión
            $session->set('NIF', $nif);
            $session->set('nombre_empresa', $dbConfigRow['nombre_empresa']);
            $session->set('id', $dbConfigRow['id']);

            return $db;
        } else {
            return null;
        }
    }



}