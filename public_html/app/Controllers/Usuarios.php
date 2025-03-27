<?php

namespace App\Controllers;

use App\Models\Usuarios2_Model;
use App\Models\Usuarios1_Model;

class Usuarios extends BaseController
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
        $this->addBreadcrumb('Usuarios');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('usuarios', $data);
    }
    public function getUsuarios()
    {
        $data = usuario_sesion(); // Recuperas la sesión del usuario
        $db = db_connect($data['new_db']);
        $model = new Usuarios2_Model($db);
        $usuarios = $model->findAll();

        // Asegúrate de incluir id_empresa en cada usuario
        foreach ($usuarios as &$usuario) {
            $usuario['id_empresa'] = $data['id_empresa']; // Añadir id_empresa al dato del usuario
        }

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
        if (!isset($data['id_user'])) {
            return redirect()->back()->with('error', 'No se pudo obtener el usuario autenticado.');
        }

        $db = db_connect($data['new_db']);
        $model = new Usuarios2_Model($db);
        $id = $this->request->getPost('id');

        $dataFormulario = [
            'nombre_usuario' => $this->request->getPost('nombre_usuario'),
            'apellidos_usuario' => $this->request->getPost('apellidos_usuario'),
            'email' => $this->request->getPost('email'),
            'telefono' => $this->request->getPost('telefono'),
            'user_activo' => $this->request->getPost('user_activo'),
            'id_empresa' => $data['id_empresa'],
            'dni' => $this->request->getPost('dni'),
            'seguridad_social' => $this->request->getPost('seguridad_social'),
        ];

        if ($this->request->getFile('userfoto')->isValid()) {
            $userfoto = $this->request->getFile('userfoto');
            $id_empresa = $data['id_empresa'];
            $user_id = $id; // ID del usuario que se está editando
            $user_sesion_id = $data['id_user']; // ID del usuario autenticado en la sesión
            $uploadPath = ROOTPATH . 'public/assets/uploads/files/' . $id_empresa . '/usuarios/' . $user_id . '/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $this->eliminarImagenesExistentes($uploadPath);

            $originalName = $userfoto->getName();
            $extension = $userfoto->getExtension();
            $newFileName = pathinfo($originalName, PATHINFO_FILENAME) . '_IDUser' . $user_sesion_id . '.' . $extension;

            $userfoto->move($uploadPath, $newFileName);

            $dataFormulario['userfoto'] = $user_id . '/' . $newFileName;
        }

        if ($model->update($id, $dataFormulario)) {
            return redirect()->to('/usuarios')->with('success', 'Usuario actualizado correctamente.');
        } else {
            return redirect()->back()->with('error', 'No se pudo actualizar el usuario.')->withInput();
        }
    }

    public function eliminarUsuario($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        $modelUsuarios2 = new Usuarios2_Model($db);
        $modelUsuarios1 = new Usuarios1_Model();

        $usuario = $modelUsuarios2->find($id);
        if ($usuario) {
            $id_acceso = $usuario['id_acceso'];
            $modelUsuarios1->where('id', $id_acceso)->delete();
            if ($modelUsuarios2->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Usuario y su acceso eliminados correctamente']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'No se pudo eliminar el usuario de Usuarios2_Model']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No se encontró el usuario en Usuarios2_Model']);
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
            'dni' => $this->request->getPost('dni'),
            'seguridad_social' => $this->request->getPost('seguridad_social'),
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
    public function eliminarFoto($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Usuarios2_Model($db);
        $usuario = $model->find($id);
        if ($usuario && !empty($usuario['userfoto'])) {
            $path = ROOTPATH . 'public/assets/uploads/files/' . $data['id_empresa'] . '/usuarios/' . $usuario['userfoto'];
            if (file_exists($path)) {
                unlink($path);
            }
            $model->update($id, ['userfoto' => null]);
            return redirect()->to('/usuarios/editar/' . $id)->with('success', 'Foto eliminada correctamente.');
        } else {
            return redirect()->back()->with('error', 'No se pudo eliminar la foto.');
        }
    }
    private function eliminarImagenesExistentes($uploadPath)
    {
        $archivos = glob($uploadPath . '*');

        foreach ($archivos as $archivo) {
            if (is_file($archivo)) {
                unlink($archivo);
            }
        }
    }
}