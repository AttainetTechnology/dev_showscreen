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
    return $this->select('pedidos.*, clientes.nombre_cliente, users.nombre_usuario, users.apellidos_usuario')
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
    public function countPedidos($coge_estado, $where_estado)
    {
        return $this->where($coge_estado, $where_estado)->countAllResults();
    }
}
