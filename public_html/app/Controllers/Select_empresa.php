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
        // Añadir migas de pan
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Empresas');
        $data['amiga'] = $this->getBreadcrumbs();
        if ($nivel < '9') {
            return redirect()->to(base_url());
        } else {
            $db = \Config\Database::connect();
            $builder = $db->table('dbconnections');
            $query = $builder->get();
            $data['empresas'] = $query->getResult();

            return view('selectempresa', $data);
        }
    }

    public function get_empresa($id)
    {
        $empresaModel = new DbConnections_Model();
        $empresa = $empresaModel->find($id);
        return $this->response->setJSON($empresa);
    }
    public function editar()
    {
        $empresaModel = new DbConnections_Model();

        $id = $this->request->getPost('id_empresa');
        $data = usuario_sesion(); // Obtener datos del usuario autenticado
        $userSesionId = isset($data['id_user']) ? $data['id_user'] : 'unknown';

        $updateData = [
            'nombre_empresa' => $this->request->getPost('nombre_empresa'),
            'db_name' => $this->request->getPost('db_name'),
            'db_user' => $this->request->getPost('db_user'),
            'db_password' => $this->request->getPost('db_password'),
            'NIF' => $this->request->getPost('NIF'),
        ];

        // Ruta base para los archivos públicos
        $uploadPath = FCPATH . 'public/assets/uploads/files/' . $id . '/logos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Procesar logo de empresa
        $logoEmpresa = $this->request->getFile('logo_empresa');
        if ($logoEmpresa && $logoEmpresa->isValid()) {
            $newName = pathinfo($logoEmpresa->getName(), PATHINFO_FILENAME) . "_IDUser{$userSesionId}." . $logoEmpresa->getExtension();
            $filePath = $id . '/logos/' . $newName;
            $logoEmpresa->move($uploadPath, $newName);
            $updateData['logo_empresa'] = $filePath;
        }

        // Procesar favicon
        $favicon = $this->request->getFile('favicon');
        if ($favicon && $favicon->isValid()) {
            $newName = pathinfo($favicon->getName(), PATHINFO_FILENAME) . "_IDUser{$userSesionId}." . $favicon->getExtension();
            $filePath = $id . '/logos/' . $newName;
            $favicon->move($uploadPath, $newName);
            $updateData['favicon'] = $filePath;
        }

        // Procesar logo de fichajes
        $logoFichajes = $this->request->getFile('logo_fichajes');
        if ($logoFichajes && $logoFichajes->isValid()) {
            $newName = pathinfo($logoFichajes->getName(), PATHINFO_FILENAME) . "_IDUser{$userSesionId}." . $logoFichajes->getExtension();
            $filePath = $id . '/logos/' . $newName;
            $logoFichajes->move($uploadPath, $newName);
            $updateData['logo_fichajes'] = $filePath;
        }

        if (!empty($id)) {
            $empresaModel->update($id, $updateData);
        } else {
            $empresaModel->insert($updateData);
        }

        return redirect()->to(base_url('select_empresa'));
    }

    public function eliminar($id)
    {
        $empresaModel = new DbConnections_Model();

        // Eliminar el registro de la base de datos
        if ($empresaModel->delete($id)) {
            // Opcional: Eliminar archivos asociados a la empresa
            $uploadPath = FCPATH . 'public/assets/uploads/files/' . $id;
            if (is_dir($uploadPath)) {
                $this->deleteDirectory($uploadPath);
            }

            return $this->response->setJSON(['status' => 'success', 'message' => 'Empresa eliminada correctamente.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo eliminar la empresa.']);
        }
    }

    // Método auxiliar para eliminar un directorio y su contenido
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (is_dir("$dir/$item")) {
                $this->deleteDirectory("$dir/$item");
            } else {
                unlink("$dir/$item");
            }
        }
        return rmdir($dir);
    }


}
