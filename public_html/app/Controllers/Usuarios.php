<?php

namespace App\Controllers;

use \Gumlet\ImageResize;

class Usuarios extends BaseControllerGC
{
    public function index()
    {
        $crud = $this->_getClientDatabase();

        // Obtener el NIF de la empresa
        $dbConnectionsModel = new \App\Models\DbConnectionsModel();
        $nif = $dbConnectionsModel->getNIF($this->data['id_empresa']);

        // Configuración del CRUD
        $crud->setSubject('Usuario', 'Usuarios');
        $crud->setTable('users');

        if (!empty($nif)) {
            $crud->columns(['nombre_usuario', 'apellidos_usuario', 'email', 'telefono', 'user_activo', 'user_ficha', 'fecha_alta', 'fecha_baja', 'userfoto']);
        } else {
            $crud->columns(['nombre_usuario', 'apellidos_usuario', 'email', 'telefono', 'user_activo', 'fecha_alta', 'fecha_baja', 'userfoto']);
        }

        $crud->unsetCssTheme();

        // Configuración de los tipos de campo
        if (!empty($nif)) {
            $crud->fieldType('user_ficha', 'dropdown_search', ['0' => '❌', '1' => '✅']);
        }
        $crud->fieldType('user_activo', 'dropdown_search', ['0' => '❌', '1' => '✅']);
        $crud->fieldType('id', 'hidden');

        // Configuración de las etiquetas de visualización
        $crud->displayAs('id', ' ');
        if (!empty($nif)) {
            $crud->displayAs('user_ficha', 'Fichaje');
        }
        $crud->displayAs('nombre_usuario', 'Nombre');
        $crud->displayAs('apellidos_usuario', 'Apellidos');
        $crud->displayAs('userfoto', 'Foto');
        $crud->defaultOrdering('nombre_usuario', 'asc');

        // Configuración de las columnas de búsqueda
        $crud->unsetSearchColumns(['userfoto']);

        if (!empty($nif)) {
            $crud->fields(['id', 'nombre_usuario', 'apellidos_usuario', 'email', 'telefono', 'user_activo', 'user_ficha', 'fecha_alta', 'fecha_baja', 'userfoto']);
            $crud->addFields(['nombre_usuario', 'apellidos_usuario', 'email', 'telefono', 'user_ficha', 'fecha_alta']);
        } else {
            $crud->fields(['id', 'nombre_usuario', 'apellidos_usuario', 'email', 'telefono', 'user_activo', 'fecha_alta', 'fecha_baja', 'userfoto']);
            $crud->addFields(['nombre_usuario', 'apellidos_usuario', 'email', 'telefono', 'fecha_alta']);
        }

        //Añade fecha_alta automatico
        $crud->callbackAddForm(function ($data) {
            $data['fecha_alta'] = date('Y-m-d');
            return $data;
        });

        $crud->callbackBeforeInsert(function ($stateParameters) {
            $db = db_connect();

            // Use a more robust way to find the next available ID
            $query = $db->query('SELECT MAX(id) + 1 as next_id FROM users');
            $row = $query->getRow();
            $nextId = $row->next_id ? $row->next_id : 1;
            // $stateParameters->data['id'] = $nextId;
            $db->close();
            return $stateParameters;
        });

        // Callbacks Insertar en la tabla LOG las acciones realizadas
        $crud->callbackAfterInsert(function ($stateParameters) {
            $this->logAction('Usuarios', 'Añade usuario', $stateParameters);
            return $stateParameters;
        });

        $crud->callbackAfterUpdate(function ($stateParameters) {
            $this->logAction('Usuarios', 'Edita usuario, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });

        $crud->callbackAfterDelete(function ($stateParameters) {
            $this->logAction('Usuarios', 'Elimina usuario, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
            return $stateParameters;
        });

        // Callback para añadir 'Datos Acceso' en el modo edición
        $crud->callbackEditField('id', function ($fieldValue, $primaryKeyValue, $rowData) {
            $_SESSION['usuario_actual'] = $fieldValue;
            $id = $this->data['id_user'];
            if ($this->nivel > '7') {
                return '<input type="hidden" name="id" value="' . $fieldValue . '">' .
                    '<div class="botones_user"><a href="' . base_url('usuarios/' . $fieldValue) . '" class="btn btn-warning btn-sm"><i class="fa fa-user fa-fw"></i> Datos de acceso</a></div>';
            }
            return $this->nivel . '<input type="hidden" name="id" value="' . $fieldValue . '">';
        });

        // Definimos la ruta de subida de archivos
        $globalUploadPath = 'public/assets/uploads/files/' . $this->data['id_empresa'] . '/usuarios/';
        if (!is_dir($globalUploadPath)) {
            mkdir($globalUploadPath, 0777, true);
        }
        // Configuración de validaciones para la carga de archivos
        $uploadValidations = [
            'maxUploadSize' => '7M', // 20 Mega Bytes
            'minUploadSize' => '1K', // 1 Kilo Byte
            'allowedFileTypes' => [
                'gif',
                'jpeg',
                'jpg',
                'png',
                'tiff'
            ]
        ];

        // Formatos permitidos para 'userfoto'
        $crud->setFieldUpload('userfoto', $globalUploadPath, $globalUploadPath, $uploadValidations);

        // Callback para mostrar la imagen del usuario en la vista columnas
        $id_empresa = $this->data['id_empresa'];
        $crud->callbackColumn('userfoto', function ($value, $row) use ($id_empresa) {
            if ($value === null || $value === '') {
                return '';
            } else {
                $specificPath = "public/assets/uploads/files/" . $id_empresa . "/usuarios/";
                return "<img src='" . base_url($specificPath . $value) . "' height='60' class='foto_user'>";
            }
        });

        // Elimina al usuario de la BDD1 y la carpeta de img del id que hemos eliminado de bbdd cliente
        $crud->callbackBeforeDelete(function ($stateParameters) use ($globalUploadPath) {
            $userId = $stateParameters->primaryKeyValue;
            $db = db_connect();
            $db->table('users')->delete(['id' => $userId]);
            $userFolder = $globalUploadPath . $userId;
            if (is_dir($userFolder)) {
                array_map('unlink', glob("$userFolder/*.*"));
                rmdir($userFolder);
            }
            return $stateParameters;
        });

        // Creamos el directorio para subir el archivo
        $crud->callbackBeforeUpload(function ($stateParameters) use ($globalUploadPath) {
            // Obtenemos la id del usuario
            $userId = $_POST['pk_value'] ?? null;
            // Crea un directorio para el usuario si no existe.
            $uploadPath = $globalUploadPath . $userId . '/';
            if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
                return false;
            }
            // Busca todas las imágenes existentes en el directorio del usuario.
            $existingImages = glob($uploadPath . "*.{jpg,jpeg,png}", GLOB_BRACE);
            // Elimina todas las imágenes existentes en el directorio del usuario.
            foreach ($existingImages as $image) {
                unlink($image);
            }
            // Establece la ruta de subida en los parámetros del estado.
            $stateParameters->uploadPath = $uploadPath;
            return $stateParameters;
        });

        // Callback para que la ruta de las fotos incluyan la id del user
        $crud->callbackAfterUpload(function ($result) {
            // Si isSuccess no está definido o es falso, establece un valor predeterminado
            $isSuccess = isset($result->isSuccess) ? $result->isSuccess : true;

            if ($isSuccess && is_string($result->uploadResult)) {
                $fileName = $result->uploadResult;
                $usuario = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_SESSION['usuario_actual'] ?? '');
                $Newname = $usuario . "/" . $fileName;
                $result->uploadResult = $Newname;

                // Redimensión de archivos
                $fullPath = $result->stateParameters->uploadPath . $fileName;

                if (file_exists($fullPath)) {
                    // Crea una nueva instancia de ImageResize y cambia tamaño img
                    $image = new ImageResize($fullPath);
                    $image->resizeToBestFit(400, 200);

                    // Guarda la imagen redimensionada en la misma ruta
                    $image->save($fullPath);
                }
            }

            return $result;
        });

        $crud->setLangString('modal_save', 'Guardar Usuario');
        if (!empty($nif)) {
            $crud->setRelation('user_ficha', 'valoresboleanos', 'valor');
        }

        $output = $crud->render();
        return $this->_GC_output('layouts/main', $output);
    }

    public function show($id)
    {
        // Conexión a la base de datos original
        $db = db_connect();
        $query = $db->table('users')->getWhere(['id' => $id]);
        $user = $query->getRow();

        // Conexión a la base de datos del cliente
        $database = datos_user('new_db');
        $dbClient = db_connect($database['new_db']);

        // Buscar los niveles de acceso en la base de datos del cliente
        $query = $dbClient->table('niveles_acceso')->get();
        $niveles_acceso = $query->getResult();

        // Pasar los niveles de acceso y el usuario a la vista
        return view('editUser', ['user' => $user, 'niveles_acceso' => $niveles_acceso]);
    }
}
