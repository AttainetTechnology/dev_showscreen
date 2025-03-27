<?php

namespace App\Models;

use CodeIgniter\Model;

class RelacionProcesoUsuario_model extends Model
{
    protected $table = 'relacion_proceso_usuario';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_pedido',
        'id_linea_pedido',
        'id_proceso_pedido',
        'id_usuario',
        'id_maquina',
        'estado',
        'buenas',
        'malas',
        'repasadas'
    ];

    protected $useTimestamps = false;
}
