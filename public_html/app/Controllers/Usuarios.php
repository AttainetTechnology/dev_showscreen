<?php

namespace App\Controllers;

use App\Models\Usuarios2_Model;
use App\Models\Usuarios1_Model;

class Usuarios extends BaseController
{
    public function index()
    {
        return view('usuarios');
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
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Usuarios2_Model($db);
        $usuario = $model->findUserById($id);

        return view('editar_usuarios', ['usuario' => $usuario]);
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
    public function datosAcceso($id)
    {
        // Conexión a la base de datos configurada en $data['new_db']
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $usuariosModel = new Usuarios1_Model($db);
    
        // Conexión a la otra base de datos para niveles de acceso y `username`
        $usuariosModel2 = new Usuarios1_Model();
    
        // Buscar usuario por ID para obtener datos generales (nombre_usuario)
        $usuario = $usuariosModel->find($id);
         // Buscar usuario por ID en la base de datos general para obtener `username` y nivel_acceso
        $usuarioConNivel = $usuariosModel2->find($id);
        $nivelUsuario = $usuarioConNivel['nivel_acceso'] ?? null;
            // Obtener los niveles de acceso desde la base de datos configurada
        $nivelesAcceso = $db->table('niveles_acceso')->get()->getResultArray();
    
        // Combinar los datos relevantes en un solo array
        $usuario['username'] = $usuarioConNivel['username'] ?? null;
    
        return view('datosAcceso', [
            'user' => $usuario, // Datos del usuario combinados
            'niveles_acceso' => $nivelesAcceso, // Lista de niveles de acceso
            'nivel_usuario' => $nivelUsuario, // Nivel actual del usuario
        ]);
    }
    
}
