<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $allowedFields = [
        'nombre_cliente',
        'nif',
        'direccion',
        'pais',
        'id_provincia',
        'poblacion',
        'telf',
        'fax',
        'cargaen',
        'exportacion',
        'f_pago',
        'otros_contactos',
        'observaciones_cliente',
        'id_contacto',
        'email',
        'web'
    ];
}
