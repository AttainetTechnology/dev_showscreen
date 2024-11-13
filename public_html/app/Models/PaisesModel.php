<?php

namespace App\Models;

use CodeIgniter\Model;

class PaisesModel extends Model
{
    protected $table = 'paises';
    protected $primaryKey = 'id';
    protected $allowedFields = ['iso', 'nombre'];

    // Método para obtener todos los países
    public function obtenerPaises()
    {
        return $this->findAll();
    }
}
