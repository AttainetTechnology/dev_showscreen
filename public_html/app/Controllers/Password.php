<?php
namespace App\Controllers;

use App\Models\Usuarios2_Model;
use App\Models\Usuarios1_Model;
use App\Controllers\CrudUsuarios;

class Password extends BaseControllerGC
{
    public function save($id = null)
    {
        $request = \Config\Services::request();
        $post_array = $request->getPost();

        // Asigna el ID del usuario desde la URL
        $post_array['id'] = $id;

        if (!empty($post_array['password'])) {
            if (!$this->isValidPassword($post_array['password'])) {
                error_log("ERROR: Contraseña no válida: " . $post_array['password']);
                return redirect()->back()->with('error', 'La contraseña no cumple con los requisitos de seguridad.');
            } else {
                $post_array['password'] = md5($post_array['password']);
            }
        }

        unset($post_array['nombre_usuario']);
        $db = db_connect();
        if ($db === null) {
            return;
        }

        // Buscar el usuario por nombre de usuario
        $user = $db->table('users')->where('username', $post_array['username'])->get()->getRow();

        // Si el nombre de usuario ya existe y es diferente al actual, mostrar un error
        if ($user && $user->id != $post_array['id']) {
            $session = \Config\Services::session();
            $session->setFlashdata('error', 'El nombre de usuario ya existe');
            return redirect()->back();
        }

        // Conexión a la base de datos del cliente
        $database = datos_user('new_db');
        $dbClient = db_connect($database['new_db']);

        // Validar que el ID del usuario se haya proporcionado
        if (!isset($post_array['id']) || empty($post_array['id'])) {
            return redirect()->back()->with('error', 'No se ha proporcionado el ID del usuario');
        } else {
            // Buscar el usuario por ID en la base de datos del cliente
            $clientUser = $dbClient->table('users')->where('id', $post_array['id'])->get()->getRow();
            if ($clientUser) {
                // Buscar el usuario por ID en la base de datos principal
                $user = $db->table('users')->where('id', $post_array['id'])->get()->getRow();
                if ($user) {
                    // Actualizar el usuario en la base de datos principal
                    if (empty($post_array['password'])) {
                        unset($post_array['password']);
                    }
                    $db->table('users')->where('id', $post_array['id'])->update($post_array);
                    $this->logAction('Datos Acceso', 'Edita acceso, Id: ' . $post_array['id'], $post_array);
                } else {
                    // Crear el usuario en la base de datos principal
                    $session = \Config\Services::session();
                    $logged_in_data = $session->get('logged_in');
                    $post_array['id_empresa'] = $logged_in_data['id_empresa'];
                    $db->table('users')->insert($post_array);
                    $this->logAction('Datos Acceso', 'Añade acceso', $post_array);
                }

                // Actualizar el email en la base de datos del cliente
                $dbClient->table('users')->where('id', $post_array['id'])->update(['email' => $post_array['email'], 'id_acceso' => $post_array['id']]);
            } else {
                return redirect()->back()->with('error', 'El usuario no existe en la base de datos del cliente');
            }
        }

        return redirect()->to('/usuarios');
    }

    public function edit($id = null)
    {
        helper('controlacceso_helper');
        $request = \Config\Services::request();

        // Obtén los datos del formulario
        $post_array = $request->getPost();
        $post_array['id'] = $id;

        unset($post_array['nombre_usuario']);

        if (!empty($post_array['password'])) {
            if ($this->isValidPassword($post_array['password'])) {
                $post_array['password'] = md5($post_array['password']);
                error_log('Contraseña hasheada: ' . $post_array['password']);
            } else {
                return redirect()->back()->with('error', 'La contraseña no cumple con los requisitos de seguridad.');
            }
        } else {
            if (isset($post_array['password'])) {
                unset($post_array['password']);
            }
        }

        $db = db_connect();
        if ($db === null) {
            error_log('No se pudo conectar a la base de datos');
            return;
        }

        // Buscar el usuario por ID en la base de datos del cliente
        $database = datos_user('new_db');
        $dbClient = db_connect($database['new_db']);
        $clientUser = $dbClient->table('users')->where('id', $id)->get()->getRow();

        // Si el usuario existe en la base de datos del cliente
        if ($clientUser) {
            // Buscar el usuario por ID en la base de datos principal
            $user = $db->table('users')->where('id', $post_array['id'])->get()->getRow();

            // Si el usuario existe en la base de datos principal, actualizar sus datos
            if ($user) {
                $db->table('users')->where('id', $post_array['id'])->update($post_array);
                $this->logAction('Datos Acceso', 'Edita acceso, Id: ' . $post_array['id'], $post_array);
            } else {
                // Crear el usuario en la base de datos principal
                $session = \Config\Services::session();
                $logged_in_data = $session->get('logged_in');
                $post_array['id_empresa'] = $logged_in_data['id_empresa'];
                $db->table('users')->insert($post_array);
                $this->logAction('Datos Acceso', 'Añade acceso', $post_array);
            }

            // Actualizar el email en la base de datos del cliente
            $dbClient->table('users')->where('id', $post_array['id'])->update(['email' => $post_array['email'], 'id_acceso' => $post_array['id']]);
        } else {
            return redirect()->back()->with('error', 'El usuario no existe en la base de datos del cliente');
        }

        return redirect()->to('/usuarios');
    }

    private function isValidPassword($password)
    {
        // Validar longitud mínima
        if (strlen($password) < 8) {
            error_log("ERROR: Contraseña demasiado corta.");
            return false;
        }
        // Validar que contenga al menos una mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            error_log("ERROR: Falta mayúscula.");
            return false;
        }
        // Validar que contenga al menos una minúscula
        if (!preg_match('/[a-z]/', $password)) {
            error_log("ERROR: Falta minúscula.");
            return false;
        }
        // Validar que contenga al menos un número
        if (!preg_match('/[0-9]/', $password)) {
            error_log("ERROR: Falta número.");
            return false;
        }

        return true;
    }

    public function getNivelAcceso($id)
    {
        $db = db_connect();
        $query = $db->table('users')->where('id', $id)->get();
        $user = $query->getRow();

        if ($user) {
            return $this->response->setJSON(['nivel_acceso' => $user->nivel_acceso]);
        } else {
            return $this->response->setJSON(['error' => 'Usuario no encontrado'], 404);
        }
    }


    public function getNombreUsuario($id)
    {
        $database = datos_user('new_db');
        $db = db_connect($database['new_db']);
        $query = $db->query("SELECT nombre_usuario, email FROM users WHERE id = $id");
        $row = $query->getRow();
        return $this->response->setJSON(['nombre_usuario' => $row->nombre_usuario, 'email' => $row->email]);
    }
}
