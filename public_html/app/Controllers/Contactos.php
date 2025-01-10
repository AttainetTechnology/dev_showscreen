<?php

namespace App\Controllers;

use App\Models\ContactoModel;

class Contactos extends BaseController
{
    public function index()
    {
        helper('controlacceso');
        $redirect = check_access_level();
        $redirectUrl = session()->getFlashdata('redirect');
        if ($redirect && is_string($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
        $nivel = control_login();
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        //amiga
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Contactos');
        $data['amiga'] = $this->getBreadcrumbs();

        $clienteModel = new \App\Models\ClienteModel($db);
        $clientes = $clienteModel->findAll();
        return view('contactosView', ['clientes' => $clientes, 'amiga' => $data['amiga']]);
    }

    public function getContactos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ContactoModel($db);
        $contactos = $model->findAll();
        return $this->response->setJSON($contactos);
    }
    //para mostrar los contactos
    public function agregarContacto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ContactoModel($db);
        $data = $this->request->getPost();

        if ($model->insert($data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al agregar contacto.']);
        }
    }
    //para asociar el contacto a la empresa
    public function getContacto($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ContactoModel($db);
        $contacto = $model->find($id);

        if ($contacto) {
            return $this->response->setJSON($contacto);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Contacto no encontrado'], 404);
        }
    }

    public function actualizarContacto($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ContactoModel($db);
        $data = $this->request->getPost();

        if ($model->update($id, $data)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar contacto.']);
        }
    }

    public function eliminarContacto($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ContactoModel($db);

        if ($model->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar contacto.']);
        }
    }
}
