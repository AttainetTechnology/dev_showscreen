<?php
namespace App\Controllers;

use App\Models\Vacaciones_model;
use App\Models\Usuarios2_Model;
use DateTime; // Asegúrate de importar la clase DateTime

class Vacaciones extends BaseController
{
    public function index()
    {
		$this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Vacaciones');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('vacaciones_view', $data);
    }

    public function getVacaciones()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Vacaciones_model($db);
        $vacaciones = $model->findAll();

        // Obtener nombres de usuarios
        $usuariosModel = new Usuarios2_Model($db);
        foreach ($vacaciones as &$vacacion) {
            if (isset($vacacion['user_id'])) {
                $usuario = $usuariosModel->findUserById($vacacion['user_id']);
                $vacacion['nombre_usuario'] = $usuario['nombre_usuario'] ?? 'Desconocido';
            } else {
                $vacacion['nombre_usuario'] = 'Desconocido';
            }

            // Convertir fechas al formato dd/mm/yyyy
            $vacacion['desde'] = DateTime::createFromFormat('Y-m-d', $vacacion['desde'])->format('d/m/Y');
            $vacacion['hasta'] = DateTime::createFromFormat('Y-m-d', $vacacion['hasta'])->format('d/m/Y');
        }
        return $this->response->setJSON($vacaciones);
    }

    public function getUsuarios()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $usuariosModel = new Usuarios2_Model($db);
        $usuarios = $usuariosModel->findAll();
        return $this->response->setJSON($usuarios);
    }

    public function save()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Vacaciones_model($db);
        $data = $this->request->getPost();

        // Convertir fechas al formato YYYY-MM-DD
        $data['desde'] = DateTime::createFromFormat('d/m/Y', $data['desde'])->format('Y-m-d');
        $data['hasta'] = DateTime::createFromFormat('d/m/Y', $data['hasta'])->format('Y-m-d');

        if ($model->save($data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al guardar los datos.']);
        }
    }

    public function delete($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Vacaciones_model($db);
        if ($model->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar los datos.']);
        }
    }
}