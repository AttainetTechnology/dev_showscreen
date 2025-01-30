<?php

namespace App\Controllers;

use App\Models\Maquinas;

class MaquinasController extends BaseController
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
        $this->addBreadcrumb('Máquinas');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('maquinas', $data);
    }

    public function getMaquinas()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Maquinas($db);
        $maquinas = $model->findAll();
        foreach ($maquinas as &$maquina) {
            $maquina['acciones'] = [
                'editar' => base_url('maquinas/editar/' . $maquina['id_maquina']),
                'eliminar' => base_url('maquinas/eliminar/' . $maquina['id_maquina']),
            ];
        }
        return $this->response->setJSON($maquinas);
    }

    public function eliminarMaquina($id_maquina)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Maquinas($db);

        $model->delete($id_maquina);

        return $this->response->setJSON(['success' => true]);
    }

    public function actualizarMaquina()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Maquinas($db);

        $idMaquina = $this->request->getPost('id_maquina');
        $nombre = $this->request->getPost('nombre');

        if (empty($nombre)) {
            return $this->response->setJSON(['success' => false, 'message' => 'El campo nombre es obligatorio.']);
        }

        $model->set('nombre', $nombre)
            ->where('id_maquina', $idMaquina)
            ->update();

        return $this->response->setJSON(['success' => true]);
    }

    public function editar($id_maquina)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Maquinas($db);

        $maquina = $model->find($id_maquina);

        if (!$maquina) {
            return $this->response->setJSON(['success' => false, 'message' => 'Máquina no encontrada.']);
        }

        return view('editMaquinasModal', ['maquina' => $maquina]);
    }

    public function agregarMaquina()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Maquinas($db);

        $nombre = $this->request->getPost('nombre');

        if (empty($nombre)) {
            return $this->response->setJSON(['success' => false, 'message' => 'El campo nombre es obligatorio.']);
        }

        $model->insert(['nombre' => $nombre]);

        return $this->response->setJSON(['success' => true]);
    }
}
