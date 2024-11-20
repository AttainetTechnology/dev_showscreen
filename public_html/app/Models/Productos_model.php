<?php

namespace App\Models;

use CodeIgniter\Model;

class Productos_model extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    protected $allowedFields = ['id_producto', 'nombre_producto', 'id_familia', 'imagen', 'precio', 'unidad', 'estado_producto' ];

    public function getProductoConFamilia($id_producto) {
        return $this->select('productos.*, familia_productos.nombre as nombre_familia')
                    ->join('familia_productos', 'familia_productos.id_familia = productos.id_familia')
                    ->where('productos.id_producto', $id_producto)
                    ->first();
    }
}
