<?php

namespace App\Controllers;

use App\Models\ProductosProveedorModel;

class Proveedores extends BaseControllerGC
{
    public function index()
    {
        $crud = $this->_getClientDatabase();
        $crud->setSubject('Proveedor', 'Proveedores');
        $crud->setTable('proveedores');

        // Relaciones
        $crud->setRelation('id_provincia', 'provincias', 'provincia');
        $crud->setRelation('pais', 'paises', 'nombre');
        $crud->setRelation('id_contacto', 'contactos', '{nombre} {apellidos}');

        // Campos
        $crud->addFields(['nombre_proveedor', 'nif', 'email', 'telf', 'contacto', 'direccion', 'pais', 'id_provincia', 'poblacion', 'f_pago', 'fax', 'cargaen', 'contacto', 'observaciones_proveedor', 'web']);
        $crud->editFields(['id_proveedor', 'nombre_proveedor', 'nif', 'direccion', 'id_provincia', 'poblacion', 'telf', 'cargaen', 'f_pago', 'web', 'email', 'observaciones_proveedor', 'fax', 'contacto']);

        // Columnas
        $crud->columns(['nombre_proveedor', 'nif', 'direccion', 'contacto', 'id_provincia', 'telf', 'cargaen', 'web', 'email']);
        $crud->displayAs('id_provincia', 'Provincia');
        $crud->displayAs('f_pago', 'Forma Pago');
        $crud->displayAs('cargaen', 'Carga en');
        $crud->displayAs('observaciones_proveedor', 'Observaciones');
        $crud->setLangString('modal_save', 'Guardar Proveedor');

        // Personalizar el campo id_proveedor para incluir el botón
        $crud->callbackEditField('id_proveedor', function ($fieldValue, $primaryKeyValue, $rowData) {
            $button = "<a href='" . base_url("proveedores/verProductos/{$primaryKeyValue}") . "' class='btn btn-info mt-3' data-toggle='modal' data-target='#productosModal'>Ver Productos</a>";
            return $button . "<input type='hidden' name='id_proveedor' value='{$fieldValue}'>";
        });

        // Callbacks para LOG
        $crud->callbackAfterInsert(function ($stateParameters) {
            $this->logAction('Proveedores', 'Añade proveedor', $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterUpdate(function ($stateParameters) {
            $this->logAction('Proveedores', 'Edita proveedor, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterDelete(function ($stateParameters) {
            $this->logAction('Proveedores', 'Elimina proveedor, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });

        // Renderizar salida
        $output = $crud->render();
        return $this->_GC_output("layouts/main", $output);
    }

    public function verProductos($id_proveedor)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);

        // Obtener los productos asociados al proveedor y el nombre del producto
        $productos = $model
            ->select('productos_proveedor.ref_producto, productos_necesidad.nombre_producto, productos_proveedor.precio')
            ->join('productos_necesidad', 'productos_necesidad.id_producto = productos_proveedor.id_producto_necesidad')
            ->where('productos_proveedor.id_proveedor', $id_proveedor)
            ->findAll();

        // Cargar la vista con los productos
        return view('productos_proveedor', ['productos' => $productos]);
    }
}
