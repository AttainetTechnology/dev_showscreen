<?php
namespace App\Models;

use CodeIgniter\Model;

class Nivel_model extends Model
{
    protected $table = 'niveles_acceso';
    protected $primaryKey = 'id_nivel';
    protected $allowedFields = ['nombre_nivel'];
}


