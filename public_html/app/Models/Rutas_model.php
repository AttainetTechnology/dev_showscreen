<?php

namespace App\Models;
use CodeIgniter\Model;

class Rutas_model extends Model
{

    protected $table      ='rutas';
    protected $primaryKey = 'id_ruta';
    protected $allowedFields = ['fecha_ruta','estado_ruta', 'id_cliente','poblacion','lugar','recogida_entrega','observaciones','transportista','id_pedido'];
}


