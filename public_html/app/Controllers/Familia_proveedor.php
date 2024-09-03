<?php
namespace App\Controllers;

class Familia_proveedor extends BaseControllerGC
{
    public function index()
    {
        // Inicialización del objeto CRUD
        $crud = $this->_getClientDatabase();
        $crud->setSubject('Familia de Proveedor', 'Familias de Proveedores');
        $crud->setTable('familia_proveedor');

        // Campos para añadir y editar
        $crud->requiredFields(['nombre']);
        $crud->addFields(['nombre']);
        $crud->editFields(['nombre']);

        // Columnas que se mostrarán en la vista de lista
        $crud->columns(['id_familia', 'nombre']);
        $crud->displayAs('nombre', 'Nombre Familia');

        $crud->setLangString('modal_save', 'Guardar Familia');

        // Callbacks para LOG
        $crud->callbackAfterInsert(function ($stateParameters) {
            $this->logAction('FamiliaProveedor', 'Añade familia de proveedor', $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterUpdate(function ($stateParameters) {
            $this->logAction('FamiliaProveedor', 'Edita familia de proveedor, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterDelete(function ($stateParameters) {
            $this->logAction('FamiliaProveedor', 'Elimina familia de proveedor, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });

        // Renderizar salida
        $output = $crud->render();
        return $this->_GC_output("layouts/main", $output);
    }
    public function test()
{
    echo "El método test funciona correctamente.";
}

}
