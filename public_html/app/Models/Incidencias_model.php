<?php

namespace App\Models;

use CodeIgniter\Model;

class Incidencias_model extends Model
{
    protected $table = 'fichajes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'entrada',
        'salida',
        'incidencia',
        'id_usuario',
        'extras'
    ];

    public function index()
    {
        $data = array();
        $db      = \Config\Database::connect();
        $builder = $db->table($this->table);
        $builder->select('fichajes.id, entrada, incidencia, id_usuario, extras, nombre_usuario');
        $builder->join('users', 'users.id = fichajes.id_usuario', 'right outer');
        $condicion = "(fichajes.incidencia != '') OR (fichajes.extras != '')";
        $builder->where($condicion);
        $builder->orderby("fichajes.entrada", "desc");
        $query = $builder->get();
        $data['incidencias'] = $query->getResult();
        echo view('incidencias_home', $data);
    }
    public function getIncidencias()
    {
        $data = datos_user();
        $new_db = db_connect($data['new_db']);

        $db = \Config\Database::connect($new_db);
        $builder = $db->table($this->table);

        $startDate = date('Y-m-d', strtotime('-7 days'));
        $endDate = date('Y-m-d');

        $builder->select('fichajes.id, fichajes.entrada, fichajes.incidencia, users.nombre_usuario');
        $builder->join('users', 'users.id = fichajes.id_usuario', 'inner');
        $builder->where('fichajes.incidencia !=', '');
        $builder->where('DATE(fichajes.entrada) >=', $startDate);
        $builder->where('DATE(fichajes.entrada) <=', $endDate);
        $builder->orderBy('fichajes.entrada', 'desc');

        $query = $builder->get();

        return $query->getResult(); // Devuelve los datos obtenidos
    }
}
