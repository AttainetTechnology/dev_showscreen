<?php
namespace App\Controllers;

use Config\Database as ConfigDatabase;
use Config\GroceryCrud as ConfigGroceryCrud;
use GroceryCrud\Core\GroceryCrud;

class Example extends BaseController
{
    public function index()
    {
        $output = (object) [
            'js_files' => [],
            'output' => ''
        ];

        return $this->_example_output($output);
    }

    public function customers()
    {
        $crud = $this->_getClientDatabase();

        $crud->setCsrfTokenName(csrf_token());
        $crud->setCsrfTokenValue(csrf_hash());

        $crud->setTable('customers');
        $crud->setSubject('Customer', 'Customers');

        $output = $crud->render();

        return $this->_example_output($output);
    }

    private function _example_output($output = null)
    {
        if (isset($output->isJSONResponse) && $output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }

        return view('example.php', (array) $output);
    }

    private function _getDbData()
    {
        $db = (new ConfigDatabase())->default;
        return [
            'adapter' => [
                'driver' => 'Pdo_Mysql',
                'host' => $db['hostname'],
                'database' => $db['database'],
                'username' => $db['username'],
                'password' => $db['password'],
                'charset' => 'utf8'
            ]
        ];
    }
    private function _getGroceryCrudEnterprise($bootstrap = true, $jquery = true)
    {
        $db = $this->_getDbData();
        $config = (new ConfigGroceryCrud())->getDefaultConfig();

        $groceryCrud = new GroceryCrud($config, $db);
        return $groceryCrud;
    }
}