<?php

namespace App\Models;

use CodeIgniter\Model;

class LineaPedidoModel extends Model
{
    protected $table = 'linea_pedido_proveedor';
    protected $primaryKey = 'id_lineapedido';
    protected $allowedFields = [
        'id_pedido',
        'fecha_salida',
        'fecha_entrega',
        'id_producto',
        'n_piezas',
        'observaciones',
        'id_usuario',
        'unidades',
        'precio_compra',
        'descuento',
        'add_linea',
        'total_linea',
        'estado'
    ];

    public function obtener_lineas_pedido($id_pedido)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);

        $builder = $db->table('linea_pedido_proveedor');
        $builder->select('*');
        $builder->join('productos_necesidad', 'productos_necesidad.id_producto = linea_pedido_proveedor.id_producto', 'left');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();

        return $query->getResult();
    }

    public function anular_lineas($id_pedido)
    {
        $data = ['estado' => '6'];
        helper('controlacceso');
        $data2 = usuario_sesion();
        $db = db_connect($data2['new_db']);

        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }
        $builder = $db->table('linea_pedido_proveedor');
        $builder->set($data);
        $builder->where('id_pedido', $id_pedido);
        $builder->update();

        $builder = $db->table('pedidos_proveedor');
        $builder->set($data);
        $builder->where('id_pedido', $id_pedido);
        return $builder->update();
    }
}
