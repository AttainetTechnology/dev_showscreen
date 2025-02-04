<?php

namespace App\Models;

use CodeIgniter\Model;

class Familia_productos_model extends Model
{
    protected $table = 'familia_productos';
    protected $primaryKey = 'id_familia';
    protected $allowedFields = ['id_familia', 'nombre'];
}
