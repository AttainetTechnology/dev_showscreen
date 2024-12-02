<?php
namespace App\Models;
use CodeIgniter\Model;

class Log_model extends Model
{
    protected $table      = 'log';
    protected $primaryKey = 'id_log';     protected $allowedFields = ['fecha', 'id_usuario', 'log', 'seccion']; 
}
