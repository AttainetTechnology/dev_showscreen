<?php

namespace App\Controllers;

class Pedido_terceros_controller extends BaseController
{
    public function mostrar($id_ped_terceros)
    {
        helper('controlacceso');
        control_login();

        $data = datos_user();
        $db = db_connect($data['new_db']);

        // Obtener el pedido a terceros
        $builder = $db->table('pedido_terceros');
        $builder->select('pedido_terceros.*, proveedores.nombre_proveedor, productos_proveedor.ref_producto');
        $builder->join('proveedores', 'proveedores.id_proveedor = pedido_terceros.id_proveedor', 'left');
        $builder->join('productos_proveedor', 'productos_proveedor.id = pedido_terceros.id_producto', 'left');
        $builder->where('pedido_terceros.id_ped_terceros', $id_ped_terceros);
        $query = $builder->get();
        $data['pedido'] = $query->getRow();

        // Obtener cliente si existe
        $data['cliente'] = [];
        if ($data['pedido'] && isset($data['pedido']->id_pedido_cliente)) {
            $builder = $db->table('pedidos');
            $builder->select('*');
            $builder->where('id_pedido', $data['pedido']->id_pedido_cliente);
            $pedido_cliente = $builder->get()->getRow();

            if ($pedido_cliente && isset($pedido_cliente->id_cliente)) {
                $builder = $db->table('clientes');
                $builder->select('*');
                $builder->where('id_cliente', $pedido_cliente->id_cliente);
                $data['cliente'] = $builder->get()->getRow();
            }
        }

        // Puedes añadir más datos relacionados si lo necesitas

        if ($this->request->isAJAX()) {
            return view('pedido_terceros', (array) $data);
        } else {
            echo view('header');
            echo view('pedido_terceros', (array) $data);
            echo view('footer');
        }
    }
}