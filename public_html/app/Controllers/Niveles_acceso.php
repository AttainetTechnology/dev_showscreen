<?php
namespace App\Controllers;

use App\Models\Nivel_model;

class Niveles_acceso extends BaseController
{
    public function index()
    {
        helper('controlacceso');
        $redirect = check_access_level();
        $redirectUrl = session()->getFlashdata('redirect');
        if ($redirect && is_string($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Niveles Acceso');
        $data['amiga'] = $this->getBreadcrumbs();

        return view('niveles_acceso_view', $data);
    }

    public function getNievel()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $logModel = new Nivel_model($db);
        $logs = $logModel->findAll();

        if ($logs) {
            return $this->response->setJSON($logs);
        } else {
            return $this->response->setJSON(['error' => 'No se encontraron logs.']);
        }
    }
    public function deleteNievel($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $logModel = new Nivel_model($db);

        // Verificar si el registro existe
        $log = $logModel->find($id);

        if ($log) {
            // Si estás usando soft delete:
            $logModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);  // Marca el registro como eliminado
            return $this->response->setJSON(['success' => true, 'message' => 'Nivel marcado como eliminado.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Nivel no encontrado.']);
        }
    }


    public function getNiveles()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Nivel_model($db);
        $contactos = $model->findAll();
        return $this->response->setJSON($contactos);
    }
    //para mostrar los contactos
    public function agregarNivel()
    {
        $data = usuario_sesion();  // Obtén la información del usuario
        $db = db_connect($data['new_db']);  // Conexión a la base de datos
        $model = new Nivel_model($db);  // Instancia el modelo de Nivel
        $data = $this->request->getPost();  // Obtén los datos del formulario

        if ($model->insert($data)) {  // Intenta insertar los datos
            return $this->response->setJSON(['success' => true]);  // Respuesta positiva
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al agregar nivel.']);  // Respuesta en caso de error
        }
    }

    //para asociar el contacto a la empresa
    public function getNivel($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Nivel_model($db);
        $nivel = $model->find($id);

        if ($nivel) {
            return $this->response->setJSON($nivel);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Nivel no encontrado'], 404);
        }
    }
    public function actualizarNivel($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Nivel_model($db);
        $data = $this->request->getPost();

        if ($model->update($id, $data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar nivel.']);
        }
    }

    public function deleteNivel($id)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $logModel = new Nivel_model($db);
        if ($logModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el nivel.']);
        }
    }

}
