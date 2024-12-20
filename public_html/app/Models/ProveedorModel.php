<?php
namespace App\Models;

use CodeIgniter\Model;

class ProveedoresModel extends Model
{
    protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';
    protected $allowedFields = [
        'nombre_proveedor', 
        'nif', 
        'direccion', 
        'pais', 
        'id_provincia', 
        'poblacion', 
        'telf', 
        'fax', 
        'cargaen', 
        'f_pago', 
        'contacto', 
        'observaciones_proveedor', 
        'email', 
        'web'
    ];
}

