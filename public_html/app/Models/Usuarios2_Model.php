<?php

namespace App\Models;

use CodeIgniter\Model;

class Usuarios2_Model extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'nombre_usuario', 'apellidos_usuario', 'user_ficha', 'user_activo', 'userfoto', 'telefono', 'fecha_alta', 'fecha_baja', 'email', 'id_acceso', 'dni', 'seguridad_social'];

    public function findUserById($id)
    {
             return $this->asArray()
                    ->where(['id' => $id])
                    ->first();
    }
}