<?php

namespace App\Models;

use CodeIgniter\Model;

class Rutas_model extends Model
{
    protected $table = 'rutas';
    protected $primaryKey = 'id_ruta';
    protected $allowedFields = [
        'fecha_ruta',
        'estado_ruta',
        'id_cliente',
        'poblacion',
        'lugar',
        'recogida_entrega',
        'observaciones',
        'transportista',
        'id_pedido'
    ];

    // Obtener rutas según el filtro de estado
    public function getRutas($coge_estado, $where_estado)
    {
        if (empty($coge_estado) || empty($where_estado)) {
            throw new \InvalidArgumentException('Los parámetros "coge_estado" y "where_estado" son requeridos.');
        }

        return $this->where($coge_estado, $where_estado)->findAll();
    }

    // Obtener información de una ruta por su ID
    public function getRutaById($id_ruta)
    {
        return $this->find($id_ruta);
    }

    // Obtener rutas con información del cliente
    public function getRutasWithCliente($coge_estado, $where_estado)
    {
        $this->select('rutas.*, clientes.nombre_cliente');
        $this->join('clientes', 'rutas.id_cliente = clientes.id_cliente', 'left');
        $this->where($coge_estado, $where_estado);
        return $this->findAll();
    }

    // Obtener rutas con detalles adicionales
    public function getRutasWithDetails($coge_estado, $where_estado)
{
    $this->select('rutas.*, 
                   clientes.nombre_cliente, 
                   poblaciones_rutas.poblacion AS nombre_poblacion, 
                   CONCAT(users.nombre_usuario, " ", users.apellidos_usuario) AS nombre_transportista, 
                   rutas.estado_ruta AS ruta_estado'); // Asegúrate de que este campo esté incluido
    $this->join('clientes', 'rutas.id_cliente = clientes.id_cliente', 'left');
    $this->join('poblaciones_rutas', 'rutas.poblacion = poblaciones_rutas.id_poblacion', 'left');
    $this->join('users', 'rutas.transportista = users.id', 'left');
    $this->where($coge_estado, $where_estado);
    return $this->findAll();
}

    // Obtener el nombre del cliente por el ID del pedido
    public function getNombreClienteByPedido($id_pedido)
    {
        $builder = $this->db->table('pedidos');
        $builder->select('clientes.nombre_cliente');
        $builder->join('clientes', 'clientes.id_cliente = pedidos.id_cliente', 'left');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();

        return $query->getRow()->nombre_cliente ?? '';
    }

    // Obtener rutas por ID de pedido
    public function getRutasPorPedido($id_pedido)
    {
        return $this->where('id_pedido', $id_pedido)->findAll();
    }

    // Obtener nombre del transportista por su ID
    public function getNombreTransportistaById($id_transportista)
    {
        $builder = $this->db->table('users'); // Cambia 'users' por el nombre correcto de la tabla de transportistas
        $builder->select('CONCAT(nombre_usuario, " ", apellidos_usuario) AS nombre_transportista');
        $builder->where('id', $id_transportista);
        $query = $builder->get();
    
        return $query->getRow()->nombre_transportista ?? 'Transportista desconocido';
    }
}