<?php 
namespace App\Models;

use CodeIgniter\Model;

class Proceso extends Model {
    protected $table = 'procesos';
    protected $primaryKey = 'id_proceso';

    protected $allowedFields = ['nombre_proceso', 'estado_proceso', 'restriccion','maq_preasignada'];

    public function obtenerProcesos() {
        return $this->findAll();
    }

}