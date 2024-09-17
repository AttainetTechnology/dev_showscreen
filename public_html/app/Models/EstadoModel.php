<?php

namespace App\Models;

use CodeIgniter\Model;

class estadoModel extends Model {
    protected $table = 'estados';
    protected $primaryKey = 'id_estado';
    protected $allowedFields = ['nombre_estado'];
}
