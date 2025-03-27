<?php

namespace App\Models;

use CodeIgniter\Model;

class Laborables_model extends Model
{
    protected $table = 'laborables';
    protected $primaryKey = 'id';
    protected $allowedFields = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
}
