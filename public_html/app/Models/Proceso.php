<?php 
namespace App\Models;

use CodeIgniter\Model;

class Proceso extends Model {
    protected $table = 'procesos';
    protected $primaryKey = 'id_proceso';

    protected $allowedFields = ['nombre_proceso', 'id_maquina', 'estado_proceso', 'restriccion'];

    public function obtenerProcesos() {
        return $this->findAll();
    }

}