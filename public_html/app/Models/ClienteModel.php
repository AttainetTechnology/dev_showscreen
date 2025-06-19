<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $allowedFields = [
        'nombre_cliente',
        'nif',
        'direccion',
        'pais',
        'id_provincia',
        'poblacion',
        'telf',
        'fax',
        'cargaen',
        'exportacion',
        'f_pago',
        'otros_contactos',
        'observaciones_cliente',
        'id_contacto',
        'email',
        'web'
    ];
    public function obtenerClientePorId($id_cliente)
{
    return $this->select('clientes.*, provincias.provincia, paises.nombre as nombre_pais')
        ->join('provincias', 'provincias.id_provincia = clientes.id_provincia', 'left')
        ->join('paises', 'paises.id = clientes.pais', 'left')
        ->where('clientes.id_cliente', $id_cliente)
        ->first();

}
}
