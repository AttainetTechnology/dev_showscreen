<?php

namespace App\Controllers;

class Parte_complejo extends BaseControllerGC
{

    function __construct()
    {
        $validation = \Config\Services::validation();
        $session = session();
        if (empty($session->get('logged_in'))) {
            return redirect()->to('Login');
        }
        helper('url');
        $database = \Config\Services::database();
    }

    public function pedido_print($id_pedido)
    {
        //TABLA LOG
        $this->logAction('Pedidos', 'Parte complejo, ID: ' . $id_pedido, []);

        //Saco los detalles del pedido

        $Pedidos_model = model('App\Models\Pedidos_model');
        $data['pedido'] = $Pedidos_model->obtener_datos_pedido($id_pedido);
        $Lineaspedido_model = model('App\Models\Lineaspedido_model');
        $data['lineas'] = $Lineaspedido_model->obtener_lineas_pedido($id_pedido);

        echo view('header_partes');
        echo view('parte_complejo', (array) $data);
        echo view('footer');

    }

}

