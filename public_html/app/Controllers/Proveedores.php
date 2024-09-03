<?php

namespace App\Controllers;

use App\Models\ProductosProveedorModel;
use App\Models\ProveedoresModel;

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
        $proveedoresModel = new ProveedoresModel($db);

        // Obtener el nombre del proveedor
        $proveedor = $proveedoresModel->find($id_proveedor);

        // Obtener los productos asociados al proveedor, incluyendo el campo id_producto_necesidad
        $productos = $model
            ->select('productos_proveedor.ref_producto, productos_proveedor.id_producto_necesidad, productos_necesidad.nombre_producto, productos_proveedor.precio')
            ->join('productos_necesidad', 'productos_necesidad.id_producto = productos_proveedor.id_producto_necesidad')
            ->where('productos_proveedor.id_proveedor', $id_proveedor)
            ->findAll();

        // Obtener los ID de productos ya asociados a este proveedor
        $productosAsociados = $model
            ->select('id_producto_necesidad')
            ->where('id_proveedor', $id_proveedor)
            ->findColumn('id_producto_necesidad');

        // Obtener los productos disponibles de la tabla productos_necesidad que no están asociados a este proveedor
        $productosNecesidadModel = new \App\Models\ProductosNecesidadModel($db);
        if (!empty($productosAsociados)) {
            $productos_necesidad = $productosNecesidadModel
                ->whereNotIn('id_producto', $productosAsociados)
                ->findAll();
        } else {
            $productos_necesidad = $productosNecesidadModel->findAll();
        }

        // Cargar la vista con los productos, el nombre del proveedor, el desplegable y el id_proveedor
        return view('productos_proveedor', [
            'productos' => $productos,
            'productos_necesidad' => $productos_necesidad,
            'id_proveedor' => $id_proveedor,
            'nombre_proveedor' => $proveedor['nombre_proveedor'] // Pasa el nombre del proveedor a la vista
        ]);
    }

    public function agregarProducto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);
        $data = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'id_producto_necesidad' => $this->request->getPost('id_producto_necesidad'),
            'precio' => $this->request->getPost('precio'),
            'ref_producto' => $this->request->getPost('ref_producto'),
        ];
        $model->insert($data);
        return redirect()->back()->with('message', 'Producto añadido con éxito.');
    }

    public function eliminarProducto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);
        $idProveedor = $this->request->getPost('id_proveedor');
        $idProductoNecesidad = $this->request->getPost('id_producto_necesidad');

        // Eliminar el producto asociado al proveedor
        $model->where('id_proveedor', $idProveedor)
            ->where('id_producto_necesidad', $idProductoNecesidad)
            ->delete();
        return redirect()->back()->with('message', 'Producto eliminado con éxito.');
    }
}
