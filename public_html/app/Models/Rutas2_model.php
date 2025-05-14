<?php

namespace App\Models;

use CodeIgniter\Model;

class Rutas2_model extends Model
{
    protected $table = 'rutas';
    protected $primaryKey = 'id_ruta';
    protected $returnType = 'object';
    protected $allowedFields = ['id_pedido', 'poblacion', 'transportista', 'fecha_ruta', 'estado_ruta', 'recogida_entrega', 'lugar'];

    public function getRutasPorPedido($id_pedido)
    {
        helper('controlacceso');
        $data = datos_user(); // Obtener los datos del usuario y la base de datos
        $db = db_connect($data['new_db']); // Conectar a la base de datos correspondiente

        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table($this->table);
        $builder->select('rutas.*, 
                          poblaciones_rutas.poblacion AS nombre_poblacion, 
                          CONCAT(users.nombre_usuario, " ", users.apellidos_usuario) AS nombre_transportista');
        $builder->join('poblaciones_rutas', 'rutas.poblacion = poblaciones_rutas.id_poblacion', 'left');
        $builder->join('users', 'rutas.transportista = users.id', 'left');
        $builder->where('rutas.id_pedido', $id_pedido);
        $query = $builder->get();

        if (!$query) {
            // Consulta fallida
            throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
        }

        return $query->getResult(); // Devolver los resultados como objetos
    }
}