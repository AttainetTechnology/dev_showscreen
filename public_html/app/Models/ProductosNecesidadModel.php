<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosNecesidadModel extends Model
{
    protected $table = 'productos_necesidad';
    protected $primaryKey = 'id_producto';
    protected $allowedFields = [
        'nombre_producto', 
        'id_familia', 
        'imagen', 
        'unidad', 
        'estado_producto',
        'id_producto_venta',
    ];
}
