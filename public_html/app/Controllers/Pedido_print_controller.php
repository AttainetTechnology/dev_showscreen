<?php

namespace App\Controllers;

class Pedido_print_controller extends BaseControllerGC
{

    function __construct()
    {
        $validation = \Config\Services::validation();
        $session = session();
        if (empty($session->get('logged_in'))) {
            return redirect()->to('Login');
        }
        $database = \Config\Services::database();
    }

    public function pedido_print($id_pedido)
    {
        helper('controlacceso');
        $redirect = check_access_level();
        $redirectUrl = session()->getFlashdata('redirect');
        if ($redirect && is_string($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
        //Saco los detalles del pedido

        $Pedidos_model = model('App\Models\Pedidos_model');
        $data['pedido'] = $Pedidos_model->obtener_datos_pedido($id_pedido);
        $Lineaspedido_model = model('App\Models\Lineaspedido_model');
        
        $lineas = $Lineaspedido_model->obtener_lineas_pedido($id_pedido);
        $data['lineas'] = array_filter($lineas, function ($linea) {
            return $linea->estado !== '6';
        });

        echo view('header_partes');
        echo view('pedidos', (array) $data);
        echo view('footer');
    }
    public function obtener_lineas_pedido($id_pedido)
    {
        $builder = $this->db->table('linea_pedido_proveedor');
        $builder->select('linea_pedido_proveedor.*, productos_proveedor.ref_producto');
        $builder->join('productos_proveedor', 'productos_proveedor.ref_producto = linea_pedido_proveedor.ref_producto', 'left');
        $builder->where('linea_pedido_proveedor.id_pedido', $id_pedido);
        return $builder->get()->getResult();
    }
    public function pedido_print_proveedor($id_pedido)
    {
        $Pedidos_model = model('App\Models\PedidosProveedorModel');
        $data['pedido'] = $Pedidos_model->obtener_datos_pedido($id_pedido);
        $Lineaspedido_model = model('App\Models\LineaPedidoModel');
        $data['lineas'] = $Lineaspedido_model->obtener_lineas_pedido($id_pedido);

        echo view('header_partes');
        echo view('pedidos_proveedor', $data);
        echo view('footer');
    }
}
