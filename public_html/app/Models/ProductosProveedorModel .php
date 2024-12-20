<?php
namespace App\Models;

use CodeIgniter\Model;

class ProductosProveedorModel extends Model
{
    protected $table = 'productos_proveedor';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ref_producto', 'id_producto_necesidad', 'precio', 'id_proveedor'];
}
