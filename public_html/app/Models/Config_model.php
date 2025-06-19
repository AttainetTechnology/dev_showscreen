<?php

namespace App\Models;
use CodeIgniter\Model;

class Config_model extends Model
{
    protected $table      ='config';
    protected $primaryKey = 'id';
    protected $allowedFields = ['url_fichar', 'mensaje_alb'];
}