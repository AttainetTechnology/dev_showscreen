<?php

namespace App\Controllers;

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
        $crud->editFields(['nombre_producto', 'id_familia', 'imagen', 'unidad', 'estado_producto']);
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
}
