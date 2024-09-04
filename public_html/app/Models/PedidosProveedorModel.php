<?php

namespace App\Models;

use CodeIgniter\Model;

class PedidosProveedorModel extends Model
{
    protected $table = 'pedidos_proveedor';
    protected $primaryKey = 'id_pedido';   

    protected $allowedFields = [
        'id_proveedor', 
        'referencia', 
        'observaciones', 
        'fecha_salida', 
        'fecha_entrega', 
        'estante', 
        'id_usuario', 
        'total_pedido', 
        'detalles', 
        'estado', 
        'representante', 
        'bt_imprimir'
    ];

    public function obtener_datos_pedido($id_pedido)
    {
        helper('controlacceso');
        $data = datos_user();     
        $db = db_connect($data['new_db']);
        
        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table('pedidos_proveedor');
        $builder->select('*');
        $builder->join('proveedores', 'proveedores.id_proveedor = pedidos_proveedor.id_proveedor', 'left');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();

        if (!$query) {
            // Consulta fallida
            throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
        }

        return $query->getResult();
    }
}
