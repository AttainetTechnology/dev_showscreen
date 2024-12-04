<?php

namespace App\Controllers;

use App\Models\Informe_model;

class Informes extends BaseController
{
    public function index()
    {
        helper('controlacceso');
        $nivel = control_login();
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Breadcrumbs
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Informes');
        $data['amiga'] = $this->getBreadcrumbs();

        $informeModel = new Informe_model($db);
        $informes = $informeModel->findAll();
        return view('informe_view', ['informes' => $informes, 'amiga' => $data['amiga']]);
    }

    public function getInformes()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Informe_model($db);
        $informes = $model->findAll();
        return $this->response->setJSON($informes);
    }

    public function agregarInforme()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Informe_model($db);
        $data = $this->request->getPost();

        if ($model->insert($data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al agregar informe.']);
        }
    }

    public function getInforme($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Informe_model($db);
        $informe = $model->find($id);

        if ($informe) {
            return $this->response->setJSON($informe);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Informe no encontrado'], 404);
        }
    }

    public function actualizarInforme($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Informe_model($db);
        $data = $this->request->getPost();

        if ($model->update($id, $data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar informe.']);
        }
    }

    public function eliminarInforme($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Informe_model($db);

        if ($model->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar informe.']);
        }
    }
}
