<?php

namespace App\Models;

use CodeIgniter\Model;

class Pedidos_model extends Model
{
    protected $table      = 'pedidos';
    protected $primaryKey = 'id_pedido';
    protected $returnType = 'object';
    protected $allowedFields = ['id_cliente', 'id_usuario', 'fecha_entrada', 'estado', 'total_pedido', 'fecha_entrega', 'referencia', 'observaciones', 'pedido_por'];
    public function obtener_datos_pedido($id_pedido)
    {
        helper('controlacceso');
        $data = datos_user();
        $db = db_connect($data['new_db']);

        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }
        $builder = $db->table('pedidos');
        $builder->select('*');
        $builder->join('clientes', 'clientes.id_cliente = pedidos.id_cliente', 'left');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();
        if (!$query) {
            // Consulta fallida
            throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
        }
        return $query->getResult();
    }
    public function getPedidoWithRelations($coge_estado, $where_estado)
    {
        return $this->select('pedidos.*, clientes.nombre_cliente, users.nombre_usuario')
            ->join('clientes', 'clientes.id_cliente = pedidos.id_cliente', 'left')
            ->join('users', 'users.id = pedidos.id_usuario', 'left')
            ->where($coge_estado . $where_estado)
            ->orderBy('pedidos.fecha_entrada', 'desc')
            ->orderBy('pedidos.id_pedido', 'desc')
            ->findAll();
    }

    public function findPedidoWithUser($id_pedido)
    {
        return $this->select('pedidos.*, clientes.nombre_cliente, users.nombre_usuario, users.apellidos_usuario')
            ->join('clientes', 'clientes.id_cliente = pedidos.id_cliente', 'left')
            ->join('users', 'users.id = pedidos.id_usuario', 'left')
            ->where('pedidos.id_pedido', $id_pedido)
            ->first();
    }
    public function getFilteredPedidos($start, $length, $searchValue, $orderColumn, $orderDir)
    {
        $builder = $this->db->table('pedidos');
        // Selecciona solo las columnas necesarias para evitar la carga innecesaria de datos
        $builder->select('pedidos.id_pedido, pedidos.fecha_entrada, pedidos.fecha_entrega, pedidos.referencia, pedidos.estado, pedidos.total_pedido, clientes.nombre_cliente, users.nombre_usuario');
        // Realiza el JOIN solo con las columnas necesarias
        $builder->join('clientes', 'clientes.id_cliente = pedidos.id_cliente', 'left');
        $builder->join('users', 'users.id = pedidos.id_usuario', 'left');
        // Aplica el filtro de búsqueda si es necesario
        if ($searchValue) {
            $builder->groupStart();
            $builder->like('pedidos.id_pedido', $searchValue);
            $builder->orLike('clientes.nombre_cliente', $searchValue);
            $builder->orLike('users.nombre_usuario', $searchValue);
            $builder->groupEnd();
        }
        // Añade la paginación y el orden de los datos
        $builder->limit($length, $start);
        $builder->orderBy($orderColumn, $orderDir);

        return $builder->get()->getResult();
    }
    public function countAllPedidos()
    {
        // Devuelve el conteo total de todos los registros en la tabla 'pedidos'
        return $this->db->table('pedidos')->countAllResults();
    }
    public function countFilteredPedidos($searchValue)
    {
        $builder = $this->db->table('pedidos');
        //JOINs necesarios para las tablas clientes y users
        $builder->join('clientes', 'clientes.id_cliente = pedidos.id_cliente', 'left');
        $builder->join('users', 'users.id = pedidos.id_usuario', 'left');
        $builder->select('pedidos.id_pedido');
        if ($searchValue) {
            $builder->groupStart();
            $builder->like('pedidos.id_pedido', $searchValue);
            $builder->orLike('clientes.nombre_cliente', $searchValue);
            $builder->orLike('users.nombre_usuario', $searchValue);
            $builder->groupEnd();
        }
        return $builder->countAllResults();
    }
}
