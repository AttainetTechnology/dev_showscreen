<?php

namespace App\Models;

use CodeIgniter\Model;

class FormasPagoModel extends Model
{
    protected $table = 'formas_pago';
    protected $primaryKey = 'id_formapago';
    protected $allowedFields = ['formapago'];

    public function obtenerFormasPago()
    {
        return $this->findAll();
    }
}
