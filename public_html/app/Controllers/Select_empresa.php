<?php

namespace App\Controllers;

use App\Models\DbConnections_Model;
use CodeIgniter\Files\File;

class Select_empresa extends BaseController
{
    public function index()
    {
        helper('controlacceso');
        $nivel = control_login();
        $data = datos_user();

        // Verificar el nivel de acceso
        if ($nivel < '9') {
            return redirect()->to(base_url());
        } else {
            // Acceso directo a la base de datos para obtener la información
            $db = \Config\Database::connect();
            $builder = $db->table('dbconnections');
            $query = $builder->get();
            $data['empresas'] = $query->getResult();

            return view('selectempresa', $data);
        }
    }

    // Método para obtener los datos de la empresa para editar
    public function get_empresa($id)
    {
        $empresaModel = new DbConnections_Model();
        $empresa = $empresaModel->find($id);
        return $this->response->setJSON($empresa);
    }

    // Método para editar la empresa
    public function editar()
    {
        if ($this->request->getMethod() === 'post') {
            $empresaModel = new DbConnections_Model();
            $id = $this->request->getPost('id_empresa');
            
            // Cargar imágenes si están presentes
            $logo_empresa = $this->request->getFile('logo_empresa');
            $favicon = $this->request->getFile('favicon');
            $logo_fichajes = $this->request->getFile('logo_fichajes');

            // Validar y mover las imágenes a la carpeta correspondiente
            if ($logo_empresa && $logo_empresa->isValid()) {
                $logo_empresa->move("public/assets/uploads/files/{$id}/logos");
                $logo_empresa = $logo_empresa->getName();  // Obtener el nombre del archivo
            } else {
                $logo_empresa = $this->request->getPost('current_logo_empresa');
            }

            if ($favicon && $favicon->isValid()) {
                $favicon->move("public/assets/uploads/files/{$id}/logos");
                $favicon = $favicon->getName();  // Obtener el nombre del archivo
            } else {
                $favicon = $this->request->getPost('current_favicon');
            }

            if ($logo_fichajes && $logo_fichajes->isValid()) {
                $logo_fichajes->move("public/assets/uploads/files/{$id}/logos");
                $logo_fichajes = $logo_fichajes->getName();  // Obtener el nombre del archivo
            } else {
                $logo_fichajes = $this->request->getPost('current_logo_fichajes');
            }

            // Actualizar los datos
            $data = [
                'id' => $id,
                'nombre_empresa' => $this->request->getPost('nombre_empresa'),
                'db_name' => $this->request->getPost('db_name'),
                'db_user' => $this->request->getPost('db_user'),
                'db_password' => $this->request->getPost('db_password'),
                'NIF' => $this->request->getPost('NIF'),
                'logo_empresa' => $logo_empresa,
                'favicon' => $favicon,
                'logo_fichajes' => $logo_fichajes,
            ];

            $empresaModel->update($id, $data);

            // Redirigir a la vista principal después de la edición
            return redirect()->to(base_url('select_empresa'));
        }
    }
}
