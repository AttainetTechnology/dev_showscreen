<?php 
namespace App\Models;

use CodeIgniter\Model;
class ProcesosPedido extends Model {
    protected $table = 'procesos_pedidos';
    protected $primaryKey = 'id_relacion';
    protected $allowedFields = ['id_proceso', 'id_linea_pedido', 'id_maquina', 'estado', 'orden', 'restriccion'];
    
    public function obtenerProcesosPedido() {
        return $this->findAll();
    }
}
