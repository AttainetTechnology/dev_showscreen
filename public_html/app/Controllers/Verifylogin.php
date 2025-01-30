<?php

namespace App\Controllers;

class Verifylogin extends LoginController
{
    public function index()
    {
        helper(['url', 'form', 'controlacceso']);

        if ($this->request->getPost('username')) {
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->select('*');
            $builder->where('username', $this->request->getPost('username'));
            $builder->limit(1);
            $query = $builder->get();
            $record = $query->getRow();

            if (!empty($record)) {
                // Verificar la contraseña
                $password = md5($this->request->getPost('password')); // Convertir la contraseña proporcionada a MD5
                if ($record->password === $password) {
                    // Contraseña válida, iniciar sesión
                    $login = new Login();
                    $login->crea_sesion($record);
                    return redirect()->to('login')->withInput()->with('error', 'No existe el usuario');
                    ;
                } else {
                    // Contraseña incorrecta
                    return redirect()->to('login')->withInput()->with('error', 'Usuario o contraseña incorrectos');
                }
            } else {
                // Usuario no encontrado en la base de datos
                return redirect()->to('login')->withInput()->with('error', 'Usuario o contraseña incorrectos');
            }
        } else {
            // Si no es una solicitud POST, redirigir al formulario de login
            return redirect()->to('login');
        }
    }
}
