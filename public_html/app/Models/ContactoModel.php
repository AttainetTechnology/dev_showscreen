<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactoModel extends Model
{
    protected $table = 'contactos';
    protected $primaryKey = 'id_contacto';
    protected $allowedFields = ['nombre', 'apellidos', 'telf', 'id_cliente', 'cargo'];
}
