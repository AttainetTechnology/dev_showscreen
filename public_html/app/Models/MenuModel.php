<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id';
    protected $allowedFields = ['titulo', 'enlace', 'dependencia', 'nivel', 'posicion', 'estado', 'activo', 'url_especial','nueva_pestana'];

}

