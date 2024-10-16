<?php

namespace App\Controllers;

class Poblaciones_rutas extends BaseControllerGC
{
    
public function index()
{
$crud = $this->_getClientDatabase();

$crud->setSubject('Poblacion','Poblaciones');
$crud->setTable('poblaciones_rutas');
$crud->requiredFields(['poblacion']);
$crud->columns(array('poblacion'));
$crud->addFields(['poblacion']);
$crud->setLangString('modal_save', 'Crear Población');

// Callbacks para registrar las acciones realizadas en LOG
$crud->callbackAfterInsert(function ($stateParameters) {
    $this->logAction('Poblaciones', 'Añade población', $stateParameters);
    return $stateParameters;
});
$crud->callbackAfterUpdate(function ($stateParameters) {
    $this->logAction('Poblaciones', 'Edita población, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
    return $stateParameters;
});
$crud->callbackAfterDelete(function ($stateParameters) {
    $this->logAction('Poblaciones', 'Elimina población, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
    return $stateParameters;
});

$output = $crud->render();		

return $this->_GC_output("layouts/main", $output);
}

}

