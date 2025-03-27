<?php
namespace App\Controllers;

use App\Models\FamiliaProveedorModel;

class Familia_proveedor extends BaseController
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
        $this->addBreadcrumb('Familia Productos');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('familiaProveedor', $data);
    }

    public function getFamiliasProveedores()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);
        $familias = $model->findAll();
        foreach ($familias as &$familia) {
            $familia['acciones'] = [
                'editar' => base_url('familiaProveedor/editar/' . $familia['id_familia']),
                'eliminar' => base_url('familiaProveedor/eliminar/' . $familia['id_familia'])
            ];
        }
        return $this->response->setJSON($familias);
    }
    public function eliminarFamilia($id_familia)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);
        $model->delete($id_familia);
        return $this->response->setJSON(['success' => true]);
    }
    public function actualizarFamilia()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);
        $idFamilia = $this->request->getPost('id_familia');
        $nombre = $this->request->getPost('nombre');

        if (empty($nombre)) {
            return $this->response->setJSON(['success' => false, 'message' => 'El campo nombre es obligatorio.']);
        }
        $model->set('nombre', $nombre)
            ->where('id_familia', $idFamilia)
            ->update();

        return $this->response->setJSON(['success' => true]);
    }

    public function editar($id_familia)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);
        $familia = $model->find($id_familia);
        if (!$familia) {
            return $this->response->setJSON(['success' => false, 'message' => 'Familia no encontrada.']);
        }
        return view('editFamiliaProveedorModal', ['familia' => $familia]);
    }

    public function agregarFamilia()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);
        $nombre = $this->request->getPost('nombre');
        if (empty($nombre)) {
            return $this->response->setJSON(['success' => false, 'message' => 'El campo nombre es obligatorio.']);
        }
        $model->insert(['nombre' => $nombre]);
        return $this->response->setJSON(['success' => true]);
    }

}
