<?php

namespace App\Controllers;

use App\Models\Rutas_model; // Modelo para la tabla rutas
use App\Models\Usuarios2_Model;
use App\Models\PoblacionesModel;
use App\Models\Pedidos_model;

class Ruta_pedido extends BaseController
{
    public $npedido = 0;
    public $idcliente;

    public function Rutas($pedido, $id_cliente)
    {
        // Obtener los datos del usuario en sesión
        $data = usuario_sesion();
        // Intentar conectar a la base de datos del cliente
        $db = db_connect($data['new_db']);
        if (!$db) {
            log_message('error', 'No se pudo conectar a la base de datos del cliente');
            return;
        }

        $this->npedido = $pedido;
        $this->idcliente = $id_cliente;

        // Cargar las rutas del pedido desde el modelo
        $rutasModel = new Rutas_model($db);
        try {
            $rutas = $rutasModel->where('id_pedido', $pedido)->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener las rutas: ' . $e->getMessage());
            $rutas = [];
        }

        // Obtener lista de transportistas
        $transportistas = $this->transportistas();
        // Obtener lista de poblaciones
        $poblacionesModel = new PoblacionesModel($db);
        $poblaciones = $poblacionesModel->obtenerPoblaciones();
        foreach ($poblaciones as $poblacion) {
            $poblacionesMap[$poblacion['id_poblacion']] = $poblacion['poblacion'];
        }


        // Pasar los datos a la vista
        return view('RutasModalPedido', [
            'rutas' => $rutas,
            'id_pedido' => $pedido,
            'id_cliente' => $id_cliente,
            'transportistas' => $transportistas,
            'poblacionesMap' => $poblacionesMap,
            'poblaciones' => $poblaciones
        ]);
    }
    public function guardarRuta()
    {
        // Conectar a la base de datos
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Validar los datos del formulario
        $validation = \Config\Services::validation();
        $validation->setRules([
            'poblacion' => 'required',
            'lugar' => 'required',
            'recogida_entrega' => 'required',
            'transportista' => 'required',
            'fecha_ruta' => 'required|valid_date',
            'observaciones' => 'permit_empty',
            'estado_ruta' => 'permit_empty'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $rutasModel = new Rutas_model($db);
        $pedidosModel = new Pedidos_model($db);

        $id_pedido = $this->request->getPost('id_pedido');
        $id_cliente = $this->request->getPost('id_cliente');

        $pedido = $pedidosModel->find($id_pedido);

        // Si estamos editando una ruta existente
        if ($this->request->getPost('id_ruta')) {
            $rutasModel->update($this->request->getPost('id_ruta'), [
                'poblacion' => $this->request->getPost('poblacion'),
                'lugar' => $this->request->getPost('lugar'),
                'recogida_entrega' => $this->request->getPost('recogida_entrega'),
                'transportista' => $this->request->getPost('transportista'),
                'fecha_ruta' => $this->request->getPost('fecha_ruta'),
                'observaciones' => $this->request->getPost('observaciones'),
                'estado_ruta' => $this->request->getPost('estado_ruta'),
                'id_cliente' => $id_cliente, // Guardamos el id_cliente en la ruta
                'id_pedido' => $id_pedido
            ]);
        } else {
            // Si estamos añadiendo una nueva ruta
            $rutasModel->insert([
                'poblacion' => $this->request->getPost('poblacion'),
                'lugar' => $this->request->getPost('lugar'),
                'recogida_entrega' => $this->request->getPost('recogida_entrega'),
                'transportista' => $this->request->getPost('transportista'),
                'fecha_ruta' => $this->request->getPost('fecha_ruta'),
                'observaciones' => $this->request->getPost('observaciones'),
                'id_cliente' => $id_cliente, // Guardamos el id_cliente en la ruta
                'id_pedido' => $id_pedido
            ]);
        }

        return redirect()->to('/Ruta_pedido/rutas/' . $this->request->getPost('id_pedido') . '/' . $this->request->getPost('id_cliente'))
            ->with('success', 'La ruta ha sido guardada correctamente.');
    }

    public function obtenerRuta($id_ruta)
    {
        // Conectar a la base de datos
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Obtener los datos de la ruta
        $rutasModel = new Rutas_model($db);
        $ruta = $rutasModel->find($id_ruta);

        // Verificar si se encontró la ruta
        if ($ruta) {
            return $this->response->setJSON($ruta);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Ruta no encontrada']);
        }
    }

    function transportistas()
    {
        // Crea una nueva instancia del modelo Usuarios2_Model
        $datos = new \App\Models\Usuarios2_Model();
        $data = usuario_sesion();
        $id_empresa = $data['id_empresa'];

        // Define los criterios para la consulta a la base de datos
        $array = ['nivel_acceso' => '1', 'id_empresa' => $id_empresa];
        $usuarios = $datos->where($array)->findAll();
        $user_ids = array();

        // Almacena los IDs de los usuarios en el array
        foreach ($usuarios as $usuario) {
            $user_ids[] = $usuario['id'];
        }

        // Verificar si hay IDs antes de hacer la consulta
        if (empty($user_ids)) {
            // Si no hay transportistas, devolver un array vacío
            log_message('info', 'No se encontraron transportistas para la empresa con ID: ' . $id_empresa);
            return [];
        }

        // Conéctate a la base de datos del cliente
        $db_cliente = db_connect($data['new_db']);
        $builder = $db_cliente->table('users');
        $builder->select('id, nombre_usuario, apellidos_usuario');
        $builder->whereIn('id', $user_ids); // Ejecutar solo si hay IDs
        $builder->where('user_activo', '1');
        $query = $builder->get();

        $transportistas = array();

        // Verificar si la consulta fue exitosa y si hay resultados
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
