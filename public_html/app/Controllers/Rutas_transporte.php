<?php

namespace App\Controllers;

use App\Models\Rutas_model;
use \Gumlet\ImageResize;

class Rutas_transporte extends BaseControllerGC
{


    public function rutas()
    {
        $data = array();

        $data = datos_user();

        // Obtener el ID del transportista de la URL
        $id_transportista = $this->request->getGet('transportista');

        // Conectar a la base de datos del cliente
        $db = db_connect($data['new_db']);
        $builder = $db->table('rutas');
        $builder->select('*');
        $builder->where('estado_ruta<', '2');

        // Filtrar rutas por el transportista si el nivel de acceso es 1
        if ($data['nivel'] == 1) {
            $builder->where('transportista', $data['id_user']);
        }

        // Añadir cláusula WHERE para filtrar por transportista si se proporciona
        if ($id_transportista) {
            $builder->where('transportista', $id_transportista);
        }

        // Ordenar y unir tablas relevantes
        $builder->orderby("rutas.poblacion", "desc");
        $builder->select('rutas.observaciones, rutas.id_cliente, poblaciones_rutas.poblacion, rutas.recogida_entrega, rutas.lugar, rutas.observaciones, rutas.estado_ruta, rutas.fecha_ruta');
        $builder->join('poblaciones_rutas', 'poblaciones_rutas.id_poblacion = rutas.poblacion');
        //$builder->join('Pedidos', 'Pedidos.id_pedido = Rutas.id_pedido');
        $builder->join('clientes', 'clientes.id_cliente = rutas.id_cliente');

        $query = $builder->get();

        if ($query === false) {
            // Log the error message
            log_message('error', 'Database query failed: ' . $db->error()['message']);
            // Display an error message to the user
            echo "An error occurred while fetching routes. Please try again later.";
            return;
        }

        $data['rutas'] = $query->getResult();

        // Obtener los datos del usuario para el formulario de edición
        $data['user'] = $this->edit();

        // Mostrar la vista de rutas
        echo view('rutas', (array) $data);
    }


    private function update_ruta($id_ruta, $estado_ruta, $redirect_url)
    {
        $database = datos_user();
        $db = db_connect($database['new_db']);
        $rutas_model = new Rutas_model($db);
        $data = [
            'estado_ruta' => $estado_ruta
        ];
        $rutas_model->update($id_ruta, $data);

        // Obtén el ID del transportista asociado a la ruta
        $ruta = $rutas_model->find($id_ruta);
        $id_transportista = $ruta['transportista'];

        // Añade la lógica de redirección de la función nivel1
        $session = session();
        $session_data = $session->get('logged_in');
        $nivel_acceso = $session_data['nivel'];

        // Redirige a la nueva URL solo si nivel_acceso es mayor que 1
        if ($nivel_acceso > 1) {
            return redirect()->to(base_url('rutas_transporte/rutas?transportista=' . $id_transportista));
        }
        // Redirección por defecto
        return redirect()->to(base_url($redirect_url));
    }


    public function save()
    {
        $session = session();
        $userId = $session->get('logged_in')['id_user'];

        $post_array = $this->request->getPost();
        $updateDataOriginal = ['email' => $post_array['email']];
        foreach (['username'] as $field) {
            if (!empty($post_array[$field])) {
                $updateDataOriginal[$field] = $post_array[$field];
            }
        }

        if (!empty($post_array['password'])) {
            if ($this->isValidPassword($post_array['password'])) {
                $updateDataOriginal['password'] = md5($post_array['password']);
            } else {
                $session->setFlashdata('error', 'La contraseña no cumple con los requisitos de seguridad.');
                return redirect()->back()->withInput();
            }
        }

        $updateDataCliente = ['email' => $post_array['email']];

        $imageFile = $this->request->getFile('userfoto');
        $maxSize = 1024 * 1024; // 1MB

        if ($imageFile->isValid() && !$imageFile->hasMoved() && $imageFile->getSize() <= $maxSize) {
            $originalName = $imageFile->getName();
            $id_empresa = $this->data['id_empresa'];
            $id_user = $this->data['id_user'];
            $specificPath = FCPATH . "public/assets/uploads/files/" . $id_empresa . "/usuarios/" . $id_user . "/";

            if (!is_dir($specificPath)) {
                mkdir($specificPath, 0777, true);
            } else {
                array_map('unlink', glob($specificPath . "*"));
            }

            $imageFile->move($specificPath, $originalName);
            $imagePath = $specificPath . $originalName;
            $this->compressAndResizeImage($imagePath, 400, 200);

            $updateDataCliente['userfoto'] = $id_user . '/' . $originalName;
        }

        if (!empty($updateDataOriginal)) {
            $dbOriginal = db_connect();
            $dbOriginal->table('users')->where('id', $userId)->update($updateDataOriginal);
        }

        $database = datos_user();
        $dbCliente = db_connect($database['new_db']);
        if (!empty($updateDataCliente)) {
            $dbCliente->table('users')->where('id', $userId)->update($updateDataCliente);
        }

        $this->logAction('Transportista', 'Edita Datos Acceso', $post_array);
        return redirect()->to('/Rutas_transporte/rutas');
    }

    private function isValidPassword($password)
    {
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return true;
    }

    private function compressAndResizeImage($imagePath, $width, $height)
    {
        $image = new \Gumlet\ImageResize($imagePath);
        $image->resizeToBestFit($width, $height);
        $image->save($imagePath);
    }

    public function edit()
    {
        $session = session();
        $sessionData = $session->get('logged_in');
        $userId = $sessionData['id_user'];
        $db = db_connect();

        $query = $db->table('users')->where('id', $userId)->get();
        $user = $query->getRow();

        $data = datos_user();
        $database = $data['new_db'];
        $dbClient = db_connect($database);

        $queryClient = $dbClient->table('users')->where('id', $userId)->get();
        $userClient = $queryClient->getRow();

        $id_empresa = $this->data['id_empresa'];
        $specificPath = "public/assets/uploads/files/" . $id_empresa . "/usuarios/" . $userId . "/";
        if (isset($userClient->userfoto)) {
            $slashPosition = strpos($userClient->userfoto, '/');
            $imageNameWithoutPrefix = substr($userClient->userfoto, $slashPosition + 1);
            $user->imagePath = $specificPath . $imageNameWithoutPrefix;
        }

        return $user;
    }


    public function entregar_ruta($id_ruta)
    {
        $post_array = ['action' => 'Ruta FINALIZADA', 'id_ruta' => $id_ruta];
        $this->logAction('Transportista', 'Ruta FINALIZADA', $post_array);
        return $this->update_ruta($id_ruta, '2', 'rutas_transporte/rutas/entregado');
    }

    public function pendiente_ruta($id_ruta)
    {
        $post_array = ['action' => 'Ruta pendiente', 'id_ruta' => $id_ruta];
        $this->logAction('Transportista', 'Ruta PENDIENTE', $post_array);
        return $this->update_ruta($id_ruta, '1', 'rutas_transporte/rutas/pendiente');
    }
}
