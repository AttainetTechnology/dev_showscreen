<?php

namespace App\Controllers;

use App\Models\Rutas_model; // Modelo para la tabla rutas
use App\Models\Usuarios2_Model;

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
            return; // Detener si no hay conexión
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

        // Pasar los datos a la vista
        return view('RutasModalPedido', [
            'rutas' => $rutas,
            'id_pedido' => $pedido,
            'id_cliente' => $id_cliente,
            'transportistas' => $transportistas,
        ]);
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
