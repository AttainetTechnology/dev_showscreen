<?php

namespace App\Models;
use CodeIgniter\Model;

class PoblacionesModel extends Model
{
    protected $table = 'poblaciones_rutas';
    protected $primaryKey = 'id_poblacion';
    protected $allowedFields = ['poblacion'];

    // Método para obtener todas las poblaciones
    public function obtenerPoblaciones()
    {
        return $this->findAll();
    }
}
