<?php

namespace App\Controllers;

class Contactos_empresa extends BaseControllerGC
{
    public function index()
    {

        $crud = $this->_getClientDatabase();
        $crud->setTable('contactos');
        $crud->columns(['id_cliente', 'nombre', 'apellidos', 'telf', 'cargo']);
        $crud->editFields(['id_contacto', 'id_cliente', 'nombre', 'apellidos', 'email', 'telf', 'cargo']);
        $crud->requiredFields(['nombre']);
        $state = $crud->getState();
        // Usar un condicional para determinar qué hacer en el callback
        if ($state === 'add') {
            $crud->callbackAddField('id_cliente', function () use ($cliente) {
                return '<input name="id_cliente" value="' . $cliente . '" type="hidden"/>';
            });
        } else if ($state === 'edit') {
            // Ejecutar otra lógica para el estado 'edit'
            $crud->callbackEditField('id_cliente', function ($value, $primaryKey, $row) {
                // Aquí puedes hacer algo más con estos parámetros
                return '<input name="id_cliente" value="' . $value . '" type="hidden"/>';
            });
        }
        $crud->unsetRead();
        $crud->setLangString('modal_save', 'Guardar Contacto');
        $output = $crud->render();
        return $this->_GC_output("/layouts/ventana_flotante", $output);
    }
    public function add($cliente)
    {
        $crud = $this->_getClientDatabase();
        $crud->setSubject('Personas de contacto', 'Personas de contacto');
        $crud->setTable('contactos');
        $crud->where([
            'id_cliente' => $cliente
        ]);
        $crud->displayAs('id_cliente', ' ');
        $crud->displayAs('id_contacto', ' ');
        if (!empty($cliente)) {
            $crud->callbackAddField('id_cliente', function () use ($cliente) {
                return '<input name="id_cliente" value="' . $cliente . '" type="hidden"/>';
            });
        }
        $crud->columns(['nombre', 'apellidos', 'telf', 'cargo']);
        $crud->requiredFields(['nombre']);

        $crud->addFields(['id_cliente', 'nombre', 'apellidos', 'email', 'telf', 'cargo']);
        $crud->editFields(['nombre', 'apellidos', 'email', 'telf', 'cargo']);

        if (!empty($cliente)) {
            $crud->callbackEditField('id_contacto', function () use ($cliente) {
                return $this->volver($cliente);
            });
        }
        if (!empty($cliente)) {
            $crud->callbackAddField('id_contacto', function () use ($cliente) {
                return $this->volver($cliente);
            });
        }
        $crud->unsetRead();
        $crud->unsetFilters();
        $crud->unsetExport();
        $crud->unsetSettings();
        $crud->setLangString('modal_save', 'Guardar Contacto');
        // Callbacks para registrar las acciones realizadas en LOG
        $crud->callbackAfterInsert(function ($stateParameters) use ($cliente) {
            $this->logAction('Empresas', 'Añade contacto a empresa, ID: ' . $cliente, $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterUpdate(function ($stateParameters) use ($cliente) {
            $this->logAction('Empresas', 'Edita contacto de empresa, ID: ' . $cliente, $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterDelete(function ($stateParameters) use ($cliente) {
            $this->logAction('Empresas', 'Elimina contacto de empresa, ID: ' . $cliente, $stateParameters);
            return $stateParameters;
        });

        $output = $crud->render();
        return $this->_GC_output("/layouts/ventana_flotante", $output);
    }
    function volver($cliente)
    {
        return '<div class="botones_user"><a href="' . base_url() . '/Contactos_empresa/add/' . $cliente . '" class="btn btn-info btn-sm"><i class="fa fa-arrow-left fa-fw"></i> Volver</a></div>';
    }
    function borrar($id_contacto, $id_cliente)
    {
        //Conecto la BDD
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $datos = model('Contactos', true, $db);
        $datos->delete($id_contacto);
        return redirect()->to(base_url() . '/layouts/ventana_flotante/add/' . $id_cliente);
    }
}
