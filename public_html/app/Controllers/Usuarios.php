<?php

namespace App\Controllers;

use App\Models\Usuarios2_Model;
use App\Models\Usuarios1_Model;

class Usuarios extends BaseController
{
    public function index()
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Usuarios');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('usuarios', $data);
    }

    public function getUsuarios()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Usuarios2_Model($db);
        $usuarios = $model->findAll();
        return $this->response->setJSON($usuarios);
    }

    public function editar($id)
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Usuarios', base_url('/usuarios'));
        $this->addBreadcrumb('Editar usuario');
        $data['amiga'] = $this->getBreadcrumbs();
        $sessionData = usuario_sesion();
        $db = db_connect($sessionData['new_db']);
        $model = new Usuarios2_Model($db);
        $usuario = $model->findUserById($id);

        return view('editar_usuarios', ['usuario' => $usuario, 'amiga' => $data['amiga']]);
    }

    public function actualizarUsuario()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Usuarios2_Model($db);
        $id = $this->request->getPost('id');

        $data = [
            'nombre_usuario' => $this->request->getPost('nombre_usuario'),
            'apellidos_usuario' => $this->request->getPost('apellidos_usuario'),
            'email' => $this->request->getPost('email'),
            'telefono' => $this->request->getPost('telefono'),
            'user_activo' => $this->request->getPost('user_activo'),
        ];

        if ($model->update($id, $data)) {
            return redirect()->to('/usuarios')->with('success', 'Usuario actualizado correctamente.');
        } else {
            return redirect()->back()->with('error', 'No se pudo actualizar el usuario.')->withInput();
        }
    }

    public function eliminarUsuario($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Usuarios2_Model($db);

        if ($model->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario eliminado correctamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No se pudo eliminar el usuario']);
        }
    }

    public function crearUsuario()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Usuarios2_Model($db);

        $data = [
            'nombre_usuario' => $this->request->getPost('nombre_usuario'),
            'apellidos_usuario' => $this->request->getPost('apellidos_usuario'),
            'email' => $this->request->getPost('email'),
            'telefono' => $this->request->getPost('telefono'),
            'user_activo' => $this->request->getPost('user_activo') ?? 1,
        ];

        if ($model->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Usuario añadido correctamente']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No se pudo añadir el usuario']);
        }
    }

    public function datosAcceso($id)
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Usuarios', base_url('/usuarios'));
        $this->addBreadcrumb('Editar usuario', base_url('/usuarios/editar/' . $id));
        $this->addBreadcrumb('Datos de acceso');
        $breadcrumbs = $this->getBreadcrumbs();

        $sessionData = usuario_sesion();
        $db = db_connect($sessionData['new_db']);
        $usuariosModel = new Usuarios1_Model($db);
        $usuariosModel2 = new Usuarios1_Model();
        $usuario = $usuariosModel->find($id);

        $usuarioConNivel = $usuariosModel2->find($id);
        $nivelUsuario = $usuarioConNivel['nivel_acceso'] ?? null;

        $nivelesAcceso = $db->table('niveles_acceso')->get()->getResultArray();

        $usuario['username'] = $usuarioConNivel['username'] ?? null;

        return view('datosAcceso', [
            'user' => $usuario,
            'niveles_acceso' => $nivelesAcceso, 
            'nivel_usuario' => $nivelUsuario, 
            'amiga' => $breadcrumbs
        ]);
    }
}