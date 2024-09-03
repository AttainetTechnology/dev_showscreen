<?php

namespace App\Models;

use CodeIgniter\Model;

class FamiliaProveedorModel extends Model
{
    protected $table = 'familia_proveedor';
    protected $primaryKey = 'id_familia';
    protected $allowedFields = ['nombre'];

}

