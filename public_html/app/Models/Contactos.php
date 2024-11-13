<?php

namespace App\Models;
use CodeIgniter\Model;

class Contactos extends Model
{
    protected $table = 'contactos';
    protected $primaryKey = 'id_contacto';
    protected $allowedFields = ['nombre', 'apellidos', 'telf', 'id_cliente', 'cargo', 'email'];

    // Método para obtener los contactos asociados a un cliente específico
    public function obtenerContactosPorCliente($id_cliente)
    {
        return $this->where('id_cliente', $id_cliente)->findAll();
    }
}


