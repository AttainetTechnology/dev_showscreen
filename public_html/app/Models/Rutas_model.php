<?php

namespace App\Models;
use CodeIgniter\Model;

class Rutas_model extends Model
{

    protected $table      ='rutas';
    protected $primaryKey = 'id_ruta';
    protected $allowedFields = ['fecha_ruta','estado_ruta', 'id_cliente','poblacion','lugar','recogida_entrega','observaciones','transportista','id_pedido'];
 // Obtener rutas según el filtro de estado
 public function getRutas($coge_estado, $where_estado)
 {
     return $this->where($coge_estado . $where_estado)->findAll();
 }

 // Obtener información de una ruta por su ID
 public function getRutaById($id_ruta)
 {
     return $this->find($id_ruta);
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
}