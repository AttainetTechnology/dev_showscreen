<?php

namespace App\Models;

use CodeIgniter\Model;

class LineaPedidosModel extends Model
{
    protected $table = 'linea_pedidos';

    public function getLineaPedidosWithFamilia()
    {
        $data = datos_user(); 
        $db = db_connect($data['new_db']);
        $builder = $db->table('linea_pedidos');
        $builder->select('linea_pedidos.*, familia_productos.nombre AS nombre_familia');
        $builder->join('productos', 'linea_pedidos.id_producto = productos.id_producto');
        $builder->join('familia_productos', 'productos.id_familia = familia_productos.id_familia');
        return $builder->get()->getResult();
    }
}



