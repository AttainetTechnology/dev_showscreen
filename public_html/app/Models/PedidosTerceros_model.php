<?php
namespace App\Models;

use CodeIgniter\Model;

class PedidosTerceros_model extends Model
{
    protected $table = 'pedido_terceros';
    protected $primaryKey = 'id_ped_terceros';
    protected $allowedFields = [
        'id_pedido_cliente',
        'id_proveedor',
        'cantidad',
        'id_producto',
        'observaciones',
        'estado',
        'fecha_creacion',
        'fecha_recepcion',
    ];
}