<?php

namespace App\Models;

use CodeIgniter\Model;

class Informe_model extends Model
{
    protected $table = 'informes';
    protected $primaryKey = 'id_informe';
    protected $allowedFields = ['titulo', 'desde', 'hasta', 'ausencias', 'vacaciones', 'extras', 'incidencias'];
}
