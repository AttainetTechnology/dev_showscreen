<?php
namespace App\Models;

use CodeIgniter\Model;

class LineasPedidoFamiliaModel extends Model
{
    protected $table = 'v_linea_pedidos_con_familia';
    protected $primaryKey = 'id_lineapedido';
    protected $allowedFields = [
        'id_lineapedido', 'id_pedido', 'fecha_entrada', 'fecha_entrega', 'id_producto', 'n_piezas', 
        'nom_base', 'nom_inserto', 'tono', 'cal', 'torelo', 'med_inicial', 'med_final', 'lado', 'distancia', 
        'observaciones', 'id_usuario', 'unidades', 'precio_venta', 'manipulacion', 'descuento', 'add_linea', 
        'total_linea', 'estado', 'id_familia', 'id_cliente'
    ];

    // Método para obtener los datos ordenados por fecha_entrada
    public function getPedidosOrdenados($coge_estado, $where_estado)
    {
        return $this->where($coge_estado, $where_estado)
                    ->orderBy('fecha_entrada', 'DESC') // Ordenar por la fecha de entrada más reciente
                    ->findAll();
    }
}
