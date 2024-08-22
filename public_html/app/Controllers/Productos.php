<?php

namespace App\Controllers;

use \Gumlet\ImageResize;

class Productos extends BaseControllerGC
{
    public function index()
    {
        $crud = $this->_getClientDatabase();
        $crud->setSubject('Producto', 'Productos');
        $crud->setTable('productos');
        $crud->setRelation('id_familia', 'familia_productos', 'nombre');
        $crud->columns(['nombre_producto', 'id_familia', 'precio', 'unidad', 'estado_producto', 'imagen']);
        $crud->setRelation('unidad', 'unidades', 'nombre_unidad');
        $crud->displayAs('id_familia', 'Familia');
        $crud->displayAs('nombre_producto', 'Nombre');
        $crud->requiredFields(['nombre_producto', 'id_familia', 'unidad']);
        $crud->setRelationNtoN('procesos', 'procesos_productos', 'procesos', 'id_producto', 'id_proceso', '{nombre_proceso}');
        $crud->setLangString('modal_save', 'Guardar Producto');
        $crud->addFields(['nombre_producto', 'id_familia', 'precio', 'unidad', 'estado_producto']);
        $crud->editFields(['id_producto', 'nombre_producto', 'id_familia', 'imagen', 'precio', 'unidad', 'estado_producto']);
        $crud->unsetRead();

        $crud->fieldType('estado_producto', 'dropdown_search', [
            '1' => 'Activo',
            '0' => 'Inactivo'
        ]);

        $crud->callbackEditField('id_producto', function ($fieldValue, $primaryKeyValue, $rowData) {
            $_SESSION['procesos'] = $fieldValue;
            $id = $fieldValue; // Aquí se obtiene el id_producto

            helper('controlacceso');
            $data = usuario_sesion();
            $db = db_connect($data['new_db']);

            $builder = $db->table('procesos_productos');
            $builder->select('*');
            $builder->where(['id_producto' => $id]);
            $builder->join('procesos', 'procesos.id_proceso=procesos_productos.id_proceso', 'left');
            $builder->orderby('nombre_proceso', 'asc');
            $query = $builder->get();

            // Get the ordered processes
            $orderedProcesses = $query->getResult();

            return '<input type="hidden" name="id_producto" value="' . $fieldValue . '">' .
                '<div class="botones_user"><a href="' . base_url('productos/' . $fieldValue) . '" class="btn btn-warning btn-sm"><i class="fa fa-box fa-fw"></i>Procesos del producto</a></div>';
        });

        // Callbacks para registrar las acciones realizadas en LOG
        $crud->callbackAfterInsert(function ($stateParameters) {
            $this->logAction('Productos', 'Añade producto', $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterUpdate(function ($stateParameters) {
            $this->logAction('Productos', 'Edita producto, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });
        $crud->callbackAfterDelete(function ($stateParameters) {
            $this->logAction('Productos', 'Elimina producto, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });

        $globalUploadPath = 'public/assets/uploads/files/' . $this->data['id_empresa'] . '/productos/';
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
                $specificPath = "public/assets/uploads/files/" . $id_empresa . "/productos/";
                return "<img src='" . base_url($specificPath . $value) . "' height='60' class='img_produco'>";
            }
        });

        $crud->callbackBeforeDelete(function ($stateParameters) use ($globalUploadPath) {
            $productoId = $stateParameters->primaryKeyValue;

            // Delete related rows in the procesos_productos table
            helper('controlacceso');
            $data = usuario_sesion();
            $db = db_connect($data['new_db']);

            $db->table('procesos_productos')->where('id_producto', $productoId)->delete();

            // Delete the product's image folder
            $productoFolder = $globalUploadPath . $productoId;
            if (is_dir($productoFolder)) {
                array_map('unlink', glob("$productoFolder/."));
                rmdir($productoFolder);
            }
            return $stateParameters;
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
                $producto = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_SESSION['producto_actual'] ?? '');
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

        $output = $crud->render();
        return $this->_GC_output("layouts/main", $output);
    }

    public function show($id)
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $dbClient = db_connect($data['new_db']);

        $query = $dbClient->table('productos')->getWhere(['id_producto' => $id]);
        $producto = $query->getRow();

        $query = $dbClient->table('procesos')->get();
        $allProcesses = $query->getResult();

        $builder = $dbClient->table('procesos_productos');
        $builder->select('procesos_productos.*, procesos.nombre_proceso');
        $builder->where(['id_producto' => $id]);
        $builder->join('procesos', 'procesos.id_proceso=procesos_productos.id_proceso', 'left');
        $builder->orderby('orden', 'asc');
        $query = $builder->get();

        $orderedProcesses = $query->getResult();

        return view('procesos_view', [
            'producto' => $producto,
            'procesos' => $orderedProcesses,
            'allProcesses' => $allProcesses
        ]);
    }



    public function updateOrder()
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $dbClient = db_connect($data['new_db']);
        $postData = $this->request->getPost();

        if (!isset($postData['data']) || !is_string($postData['data'])) {
            return $this->response->setStatusCode(400, 'Bad Request: Missing or invalid data parameter');
        }

        $newOrder = json_decode($postData['data'], true);
        $id_producto = $newOrder[0]['id_producto'];
        $dbClient->table('procesos_productos')->where('id_producto', $id_producto)->delete();

        foreach ($newOrder as $item) {
            $id_proceso = $item['id_proceso'];
            $orden = $item['orden'];

            // Obtener las restricciones del proceso actual
            $proceso = $dbClient->table('procesos')->where('id_proceso', $id_proceso)->get()->getRow();
            $restricciones = $proceso->restriccion;

            if (!empty($restricciones)) {
                $restriccionesArray = explode(',', $restricciones);

                // Filtrar las restricciones para que solo incluyan procesos asociados al mismo producto
                $builder = $dbClient->table('procesos_productos');
                $builder->select('id_proceso');
                $builder->where('id_producto', $id_producto);
                $query = $builder->get();
                $procesosProducto = $query->getResultArray();

                $procesosProductoIds = array_column($procesosProducto, 'id_proceso');
                $restriccionesFiltradas = array_intersect($restriccionesArray, $procesosProductoIds);

                // Convertir las restricciones filtradas de nuevo a string
                $restriccionesFiltradasString = implode(',', $restriccionesFiltradas);
            } else {
                $restriccionesFiltradasString = '';
            }

            // Insertar el proceso en la tabla procesos_productos con las restricciones filtradas
            $dbClient->table('procesos_productos')->insert([
                'id_producto' => $id_producto,
                'id_proceso' => $id_proceso,
                'orden' => $orden,
                'restriccion' => $restriccionesFiltradasString
            ]);
        }

        return $this->response->setStatusCode(200, 'Order updated successfully');
    }
}
