<?php

namespace App\Models;

use CodeIgniter\Model;

class IncidenciaModel extends Model
{
    protected $table = 'incidencias';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_fichaje', 'incidencia', 'fecha', 'justificada', 'id_usuario'];

    public function getIncidencias()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $builder = $db->table($this->table);
        $builder->select('incidencias.*, DATE_FORMAT(fichajes.entrada, "%H:%i") as entrada_hora, DATE_FORMAT(COALESCE(fichajes.salida, NOW()), "%H:%i") as salida_hora, TIMESTAMPDIFF(MINUTE, fichajes.entrada, COALESCE(fichajes.salida, NOW())) as duracion, users.nombre_usuario');
        $builder->join('fichajes', 'fichajes.id = incidencias.id_fichaje', 'left');
        $builder->join('users', 'users.id = incidencias.id_usuario', 'left');
        $builder->orderBy('incidencias.fecha', 'desc');
        $query = $builder->get();
        return $query->getResultArray();
    }
}
