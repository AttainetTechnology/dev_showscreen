<?php

namespace App\Controllers;

use App\Models\Rutas_model;
use App\Models\Usuarios2_Model;
use App\Models\PoblacionesModel;
use App\Models\Pedidos_model;

class Ruta_pedido extends BaseController
{
    public $npedido = 0;
    public $idcliente;
    public function Rutas($pedido, $id_cliente)
    {

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        if (!$db) {
            log_message('error', 'No se pudo conectar a la base de datos del cliente');
            return $this->response->setJSON(['error' => 'Error al conectar a la base de datos']);
        }

        $this->npedido = $pedido;
        $this->idcliente = $id_cliente;
        $rutasModel = new Rutas_model($db);
        try {
            $rutas = $rutasModel->where('id_pedido', $pedido)->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener las rutas: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error al obtener las rutas']);
        }
        $transportistas = $this->transportistas();
        $poblacionesModel = new PoblacionesModel($db);
        $poblaciones = $poblacionesModel->obtenerPoblaciones();

        foreach ($poblaciones as $poblacion) {
            $poblacionesMap[$poblacion['id_poblacion']] = $poblacion['poblacion'];
        }

        return $this->response->setJSON([
            'rutas' => $rutas,
            'transportistas' => $transportistas,
            'poblacionesMap' => $poblacionesMap
        ]);
    }
    public function mostrarFormulario($pedidoId, $clienteId)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        if (!$db) {
            log_message('error', 'No se pudo conectar a la base de datos del cliente');
            return json_encode(['error' => 'Error de conexión a la base de datos']);
        }
        $transportistas = $this->transportistas();
        $poblacionesModel = new PoblacionesModel($db);
        try {
            $poblaciones = $poblacionesModel->obtenerPoblaciones();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener las poblaciones: ' . $e->getMessage());
            return json_encode(['error' => 'Error al obtener las poblaciones']);
        }
        $poblacionesMap = [];
        foreach ($poblaciones as $poblacion) {
            $poblacionesMap[$poblacion['id_poblacion']] = $poblacion['poblacion'];
        }
        return view('rutasModalPedido', [
            'id_pedido' => $pedidoId,
            'id_cliente' => $clienteId,
            'transportistas' => $transportistas,
            'poblacionesMap' => $poblacionesMap,
            'poblaciones' => $poblaciones
        ]);
    }
    public function guardarRuta()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        $validation = \Config\Services::validation();
        $validation->setRules([
            'poblacion' => 'required',
            'lugar' => 'permit_empty',
            'recogida_entrega' => 'required',
            'transportista' => 'required',
            'fecha_ruta' => 'required|valid_date',
            'observaciones' => 'permit_empty',
            'estado_ruta' => 'permit_empty'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['error' => $validation->getErrors()]);
        }
        $rutasModel = new Rutas_model($db);
        $id_pedido = $this->request->getPost('id_pedido');
        $id_cliente = $this->request->getPost('id_cliente');
        if ($this->request->getPost('id_ruta')) {
            $rutasModel->update($this->request->getPost('id_ruta'), [
                'poblacion' => $this->request->getPost('poblacion'),
                'lugar' => $this->request->getPost('lugar'),
                'recogida_entrega' => $this->request->getPost('recogida_entrega'),
                'transportista' => $this->request->getPost('transportista'),
                'fecha_ruta' => $this->request->getPost('fecha_ruta'),
                'observaciones' => $this->request->getPost('observaciones'),
                'estado_ruta' => $this->request->getPost('estado_ruta'),
                'id_cliente' => $id_cliente,
                'id_pedido' => $id_pedido
            ]);
        } else {
            $rutasModel->insert([
                'poblacion' => $this->request->getPost('poblacion'),
                'lugar' => $this->request->getPost('lugar'),
                'recogida_entrega' => $this->request->getPost('recogida_entrega'),
                'transportista' => $this->request->getPost('transportista'),
                'fecha_ruta' => $this->request->getPost('fecha_ruta'),
                'observaciones' => $this->request->getPost('observaciones'),
                'id_cliente' => $id_cliente,
                'id_pedido' => $id_pedido
            ]);
        }
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['success' => true]);
        }
        return redirect()->to('/Ruta_pedido/rutas/' . $id_pedido . '/' . $id_cliente)
            ->with('success', 'La ruta ha sido guardada correctamente.');
    }
    public function delete($id_ruta)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        $rutasModel = new Rutas_model($db);
        try {
            $rutasModel->delete($id_ruta);
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar la ruta: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al eliminar la ruta.']);
        }
    }
    public function obtenerRuta($id_ruta)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $rutasModel = new Rutas_model($db);
        $ruta = $rutasModel->find($id_ruta);
        if ($ruta) {
            return $this->response->setJSON($ruta);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Ruta no encontrada']);
        }
    }

    function transportistas()
    {
        $datos = new \App\Models\Usuarios2_Model();
        $data = usuario_sesion();
        $id_empresa = $data['id_empresa'];
        $array = ['nivel_acceso' => '1', 'id_empresa' => $id_empresa];
        $usuarios = $datos->where($array)->findAll();
        $user_ids = array();
        foreach ($usuarios as $usuario) {
            $user_ids[] = $usuario['id'];
        }
        if (empty($user_ids)) {
            log_message('info', 'No se encontraron transportistas para la empresa con ID: ' . $id_empresa);
            return [];
        }
        $db_cliente = db_connect($data['new_db']);
        $builder = $db_cliente->table('users');
        $builder->select('id, nombre_usuario, apellidos_usuario');
        $builder->whereIn('id', $user_ids);
        $builder->where('user_activo', '1');
        $query = $builder->get();
        $transportistas = array();
        if ($query && $query->getNumRows() > 0) {
            foreach ($query->getResult() as $row) {
                $transportistas[$row->id] = $row->nombre_usuario . ' ' . $row->apellidos_usuario;
            }
        } else {
            log_message('info', 'No se encontraron transportistas activos o la consulta no devolvió resultados.');
        }

        return $transportistas;
    }
}
