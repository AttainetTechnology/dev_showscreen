<?php

namespace App\Controllers;

class Familia_productos extends BaseControllerGC
{
    public function index()
    {

    $crud = $this->_getClientDatabase();

    $crud->setSubject('Familia de productos','Familias de productos');
    $crud->setTable('familia_productos');
    $crud->columns(['nombre']);
    $crud->requiredFields(['nombre']);
    $crud->editFields(['nombre']);
    $crud->defaultOrdering('orden','asc');
    $crud->defaultOrdering('en_menu','desc');
    // $crud->unsetRead();
    $crud->setLangString('modal_save', 'Guardar Familia');

    // Callbacks para registrar las acciones realizadas en LOG
    $crud->callbackAfterInsert(function ($stateParameters) {
        $this->logAction('Familia', 'Añade familia', $stateParameters);
        return $stateParameters;
    });
    $crud->callbackAfterUpdate(function ($stateParameters) {
        $this->logAction('Familia', 'Edita familia, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
        return $stateParameters;
    });
    $crud->callbackAfterDelete(function ($stateParameters) {
        $this->logAction('Familia', 'Elimina familia, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
        return $stateParameters;
    });

    $output = $crud->render();


return $this->_GC_output("layouts/main", $output); 
    }
}

