<?php

namespace App\Controllers;

use CodeIgniter\Model;
use App\Models\ProcesosProductos;
use App\Models\ProcesosPedido;
use App\Models\LineaPedido;

class Partes_controller extends BaseControllerGC
{
    public function parte_print($id_lineapedido)
    {

        helper('controlacceso');
        control_login();

        $data = datos_user();

        $db = db_connect($data['new_db']);

        $builder = $db->table('linea_pedidos');
        $builder->select('*');
        $builder->where('id_lineapedido', $id_lineapedido);
        $query = $builder->get();
        $data['lineas'] = $query->getResult();

        $data['clientes'] = [];
        $data['mas_de_una_linea'] = [];

        foreach ($query->getResult() as $row) {
            $elpedido = $row->id_pedido;

            $builder = $db->table('pedidos');
            $builder->select('*');
            $builder->where('id_pedido', $elpedido);
            $query2 = $builder->get();
            $data['pedidos'] = $query2->getResult();

            if (!empty($data['pedidos']) && isset($data['pedidos'][0]->id_cliente)) {
                $elcliente = $data['pedidos'][0]->id_cliente;

                $builder = $db->table('clientes');
                $builder->select('*');
                $builder->where('id_cliente', $elcliente);
                $query5 = $builder->get();
                $data['clientes'] = $query5->getResult();
            }

            $builder = $db->table('linea_pedidos');
            $builder->select('linea_pedidos.*, productos.nombre_producto');
            $builder->where('id_pedido', $elpedido);
            $builder->where('id_lineapedido !=', $id_lineapedido);
            $builder->join('productos', 'productos.id_producto = linea_pedidos.id_producto', 'left');
            $queryMasDeUnaLinea = $builder->get();
            $data['mas_de_una_linea'] = $queryMasDeUnaLinea->getResult();
        }


        foreach ($query->getResult() as $row) {
            $elproducto = $row->id_producto;

            $builder = $db->table('productos');
            $builder->select('*');
            $builder->where('id_producto', $elproducto);
            $query3 = $builder->get();
            $data['productos'] = $query3->getResult();
        }

        $builder = $db->table('procesos_productos');
        $builder->select('*');
        $builder->where(['id_producto' => $elproducto]);
        $builder->join('procesos', 'procesos.id_proceso=procesos_productos.id_proceso', 'left');
        $builder->orderby('procesos_productos.orden', 'asc');
        $query4 = $builder->get();
        $data['procesos'] = $query4->getResult();

        if ($this->request->isAJAX()) {
            return view('partes', (array) $data);
        } else {
            echo view('header_partes');
            echo view('partes', (array) $data);
            echo view('footer');
        }
    }

    public function pedido_print($id_pedido)
    {

        $db = \Config\Database::connect();
        $builder = $db->table('pedidos');
        $builder->where('id_pedido', $id_pedido);
        $builder->select('*');
        $query = $builder->get();

        $data['records'] = $query->getResult();
        helper('url');
        echo view('pedidos', (array) $data);
    }

    public function CambiaEstado($id_lineapedido)
    {

        $data2 = array('estado' => '2');
        helper('controlacceso');
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $builder = $db->table('linea_pedidos');
        $builder->set($data2);
        $builder->where('id_lineapedido', $id_lineapedido);
        $builder->update();
        $this->logAction('Linea Pedido', 'Parte Linea, ID: ' . $id_lineapedido, []);

        $Lineaspedido_model = model('App\Models\Lineaspedido_model');
        $Lineaspedido_model->actualiza_estado_lineas($id_lineapedido);
        $this->obtenerLineasPedidoConEstado2YCrearProcesos();

        echo "<script>window.close();</script>";
        exit;
    }

    public function verificarEstadoProcesos($id_lineapedido)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);

        $builder = $db->table('procesos_pedidos');
        $builder->select('id_relacion');
        $builder->where('id_linea_pedido', $id_lineapedido);
        $builder->where('estado', 4);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Uno de los procesos ya ha sido terminado!']);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function marcarTodasLasLineasComoRecibidas($id_pedido)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $builder = $db->table('linea_pedidos');
        $builder->where('id_pedido', $id_pedido);
        $lineas_pedido = $builder->get()->getResultArray();

        $data2 = array('estado' => 2);
        foreach ($lineas_pedido as $linea) {
            $builder->set($data2);
            $builder->where('id_lineapedido', $linea['id_lineapedido']);
            $builder->update();
        }

        $builder->select('*');
        $builder->where('id_pedido', $id_pedido);
        $builder->where('estado !=', 2);
        $lineas_no_recibidas = $builder->countAllResults();

        if ($lineas_no_recibidas == 0) {
            $builderPedido = $db->table('pedidos');
            $builderPedido->set('estado', 2);
            $builderPedido->where('id_pedido', $id_pedido);
            $builderPedido->update();
        }

        $Lineaspedido_model = model('App\Models\Lineaspedido_model');
        $Lineaspedido_model->actualiza_estado_lineas($id_pedido);
        $this->obtenerLineasPedidoConEstado2YCrearProcesos();

        return $this->response->setJSON(['status' => 'success']);
    }

    public function obtenerLineasPedidoConEstado2YCrearProcesos()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $lineaPedidoModel = new LineaPedido($db);
        $procesosPedidoModel = new ProcesosPedido($db);
        $procesosProductosModel = new ProcesosProductos($db);

        $lineasConEstado2 = $lineaPedidoModel->where('estado', 2)->findAll();

        foreach ($lineasConEstado2 as $linea) {
            $idProducto = $linea['id_producto'];
            $procesosProductos = $procesosProductosModel->where('id_producto', $idProducto)->findAll();

            foreach ($procesosProductos as $procesoProducto) {
                $existe = $procesosPedidoModel->where([
                    'id_proceso' => $procesoProducto['id_proceso'],
                    'id_linea_pedido' => $linea['id_lineapedido']
                ])->first();

                if (!$existe) {
                    $procesosPedidoModel->insert([
                        'id_proceso' => $procesoProducto['id_proceso'],
                        'id_linea_pedido' => $linea['id_lineapedido'],
                        'id_maquina' => null,
                        'estado' => 2,
                    ]);
                }
            }
        }

        return $lineasConEstado2;
    }
}