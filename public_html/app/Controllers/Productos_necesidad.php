<?php

namespace App\Controllers;

use App\Models\Productos_model;


use \Gumlet\ImageResize;

class Productos_necesidad extends BaseControllerGC
{
    public function index()
    {
        $crud = $this->_getClientDatabase();
        $crud->setSubject('Producto', 'Productos Necesidad');
        $crud->setTable('productos_necesidad');
        // Fields
        $crud->addFields(['nombre_producto', 'id_familia', 'imagen', 'unidad', 'estado_producto']);
        $crud->editFields(['id_producto', 'nombre_producto', 'id_familia', 'imagen', 'unidad', 'estado_producto']);
        $crud->columns(['nombre_producto', 'id_familia', 'imagen', 'unidad', 'estado_producto']);
        $crud->setRelation('id_familia', 'familia_proveedor', 'nombre');
        // Display As
        $crud->displayAs('id_familia', 'Familia');
        $crud->displayAs('nombre_producto', 'Nombre del Producto');
        $crud->displayAs('imagen', 'Imagen');
        $crud->displayAs('unidad', 'Unidad');
        $crud->displayAs('estado_producto', 'Estado');
        $crud->setLangString('modal_save', 'Guardar Producto');
        // ACCIONES
        $crud->setActionButton('Precio', 'fa fa-euro-sign', function ($row) {
            $link = base_url('comparadorproductos/' . $row->id_producto);
            return $link;
        }, false);

        // Personalizar el campo id_producto para incluir el botón
        $crud->callbackEditField('id_producto', function ($fieldValue, $primaryKeyValue, $rowData) {
            $button = "<a href='" . base_url("productos_necesidad/verProductos/{$primaryKeyValue}") . "' class='btn btn-info mt-3' data-toggle='modal' data-target='#productoModal'>¿Vendemos este producto?</a>";
            return $button . "<input type='hidden' name='id_producto' value='{$fieldValue}'>";
        });
        // Define paths and upload settings for images
        $globalUploadPath = 'public/assets/uploads/files/' . $this->data['id_empresa'] . '/productos_necesidad/';
        if (!is_dir($globalUploadPath)) {
            mkdir($globalUploadPath, 0777, true);
        }
        $uploadValidations = [
            'maxUploadSize' => '7M',
            'minUploadSize' => '1K',
            'allowedFileTypes' => ['gif', 'jpeg', 'jpg', 'png', 'tiff']
        ];
        $crud->setFieldUpload('imagen', $globalUploadPath, $globalUploadPath, $uploadValidations);

        $id_empresa = $this->data['id_empresa'];
        $crud->callbackColumn('imagen', function ($value, $row) use ($id_empresa) {
            if ($value === null || $value === '') {
                return '';
            } else {
                $specificPath = "public/assets/uploads/files/" . $id_empresa . "/productos_necesidad/";
                return "<img src='" . base_url($specificPath . $value) . "' height='60' class='img_producto'>";
            }
        });

        $crud->callbackBeforeUpload(function ($stateParameters) use ($globalUploadPath) {
            $productoId = $_POST['pk_value'] ?? null;
            $uploadPath = $globalUploadPath . $productoId . '/';
            if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
                return false;
            }
            $existingImages = glob($uploadPath . "*.{jpg,jpeg,png}", GLOB_BRACE);
            foreach ($existingImages as $image) {
                unlink($image);
            }
            $stateParameters->uploadPath = $uploadPath;
            return $stateParameters;
        });
        $crud->callbackAfterUpload(function ($result) {
            $isSuccess = isset($result->isSuccess) ? $result->isSuccess : true;
            if ($isSuccess && is_string($result->uploadResult)) {
                $fileName = $result->uploadResult;
                $producto = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_POST['nombre_producto'] ?? '');
                $idProducto = $_POST['pk_value'] ?? '';
                $Newname = $producto . $idProducto . "/" . $fileName;
                $result->uploadResult = $Newname;

                $fullPath = $result->stateParameters->uploadPath . $fileName;

                if (file_exists($fullPath)) {
                    $image = new ImageResize($fullPath);
                    $image->resizeToBestFit(300, 300);
                    $image->save($fullPath);
                }
            }
            return $result;
        });
        // Callbacks para registrar las acciones realizadas en LOG
        $crud->callbackAfterInsert(function ($stateParameters) {
            $this->logAction('Productos Necesidad', 'Añade producto', $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterUpdate(function ($stateParameters) {
            $this->logAction('Productos Necesidad', 'Edita producto, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterDelete(function ($stateParameters) {
            $this->logAction('Productos Necesidad', 'Elimina producto, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });

        // Output
        $output = $crud->render();
        return $this->_GC_output("layouts/main", $output);
    }

    public function getProductos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);
        $productos = $productosModel->findAll();

        return $this->response->setJSON($productos);
    }
    public function verProductos($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Productos_model($db);

        // Obtener todos los productos de la tabla productos
        $productos = $model->findAll();

        // Obtener el producto necesidad actual para verificar si tiene un id_producto_venta asignado
        $productosNecesidadModel = new \App\Models\ProductosNecesidadModel($db);
        $productoNecesidad = $productosNecesidadModel->find($id_producto);
        $idProductoVentaSeleccionado = $productoNecesidad['id_producto_venta'];

        // Cargar la vista con la lista de productos, el id_producto y si está seleccionado
        return view('selectProducto', [
            'productos' => $productos,
            'id_producto' => $id_producto,
            'id_producto_venta' => $idProductoVentaSeleccionado
        ]);
    }
    public function actualizarProductoVenta()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new \App\Models\ProductosNecesidadModel($db);

        // Obtener el id del producto necesidad y el id del producto venta seleccionado
        $idProductoNecesidad = $this->request->getPost('id_producto_necesidad');
        $idProductoVenta = $this->request->getPost('id_producto_venta');

        // Actualizar el registro en la base de datos (id_producto_venta puede ser null para deseleccionar)
        $model->update($idProductoNecesidad, [
            'id_producto_venta' => $idProductoVenta
        ]);

        // Devolver una respuesta JSON
        return $this->response->setJSON(['success' => true]);
    }
}
