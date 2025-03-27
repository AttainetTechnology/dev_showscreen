<?php
namespace App\Models;

use CodeIgniter\Model;

class Fichajes extends Model
{
    protected $table = 'fichajes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_usuario', 'entrada', 'salida', 'total', 'incidencia', 'extras'];
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
