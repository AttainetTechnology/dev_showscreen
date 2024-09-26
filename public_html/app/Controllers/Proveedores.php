<?php

namespace App\Controllers;

use App\Models\ProductosProveedorModel;
use App\Models\ProveedoresModel;
use App\Models\FamiliaProveedorModel;

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
        $crud->editFields(['para_boton', 'nombre_proveedor', 'nif', 'direccion', 'id_provincia', 'poblacion', 'telf', 'cargaen', 'f_pago', 'web', 'email', 'observaciones_proveedor', 'fax', 'contacto']);
        // Columnas
        $crud->columns(['nombre_proveedor', 'nif', 'direccion', 'contacto', 'id_provincia', 'telf', 'cargaen', 'web', 'email']);
        $crud->displayAs('id_provincia', 'Provincia');
        $crud->displayAs('f_pago', 'Forma Pago');
        $crud->displayAs('cargaen', 'Carga en');
        $crud->displayAs('observaciones_proveedor', 'Observaciones');
        $crud->setLangString('modal_save', 'Guardar Proveedor');
        // Personalizar el campo id_proveedor para incluir el botón
        $crud->callbackEditField('para_boton', function ($fieldValue, $primaryKeyValue, $rowData) {
            $button = "<a href='" . base_url("proveedores/verProductos/{$primaryKeyValue}") . "' class='btn btn-warning btn-sm botonProductos' data-toggle='modal' data-target='#productosModal'><i class='fa fa-box fa-fw'></i> Ver Productos</a>";
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
        // Obtener todas las familias de productos
        $familiaProveedorModel = new FamiliaProveedorModel($db);
        $familias = $familiaProveedorModel->findAll();
        // Obtener todos los productos de la tabla productos_necesidad
        $productosNecesidadModel = new \App\Models\ProductosNecesidadModel($db);
        $productos_necesidad = $productosNecesidadModel->findAll();
        // Cargar la vista con los productos, el nombre del proveedor, las familias, el desplegable y el id_proveedor
        return view('productos_proveedor', [
            'productos' => $productos,
            'productos_necesidad' => $productos_necesidad,
            'id_proveedor' => $id_proveedor,
            'nombre_proveedor' => $proveedor['nombre_proveedor'],
            'familias' => $familias
        ]);
    }

    public function agregarProducto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);

        // Validar que el campo id_producto_necesidad no esté vacío
        if (empty($this->request->getPost('id_producto_necesidad'))) {
            return redirect()->back()->with('error', 'El ID del producto necesidad es obligatorio.');
        }

        $productoData = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'id_producto_necesidad' => $this->request->getPost('id_producto_necesidad'),
            'precio' => $this->request->getPost('precio'),
            'ref_producto' => $this->request->getPost('ref_producto'),
        ];

        // Log de adición de producto
        $log = "Producto añadido al proveedor ID: " . $productoData['id_proveedor'];
        $seccion = "Productos de proveedor";
        $this->logAction($seccion, $log, $data);

        // Insertar el producto en la base de datos
        $model->insert($productoData);

        // Verificar que la inserción fue exitosa antes de redirigir
        if ($db->affectedRows() > 0) {
            return redirect()->back()->with('message', 'Producto añadido con éxito.');
        } else {
            echo "Error al añadir el producto.";
        }
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
        // Log de eliminación de producto
        $log = "Producto eliminado del proveedor ID: " . $idProveedor;
        $seccion = "Productos de proveedor";
        $this->logAction($seccion, $log, $data);

        return redirect()->back()->with('message', 'Producto eliminado con éxito.');
    }

    public function actualizarProducto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);
        $idProveedor = $this->request->getPost('id_proveedor');
        $idProductoNecesidad = $this->request->getPost('id_producto_necesidad');
        $refProducto = $this->request->getPost('ref_producto');
        $precio = $this->request->getPost('precio');

        // Validación de los campos
        if (empty($precio) || empty($refProducto)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Los campos de precio y referencia son obligatorios.']);
        }

        // Actualizar el producto
        $model->where('id_proveedor', $idProveedor)
            ->where('id_producto_necesidad', $idProductoNecesidad)
            ->where('ref_producto', $refProducto)
            ->set([
                'ref_producto' => $refProducto,
                'precio' => $precio
            ])
            ->update();

        // Log de actualización de producto
        $log = "Producto actualizado para el proveedor ID: " . $idProveedor;
        $seccion = "Productos de proveedor";
        $this->logAction($seccion, $log, $data);

        return $this->response->setJSON(['success' => true]);
    }
}
