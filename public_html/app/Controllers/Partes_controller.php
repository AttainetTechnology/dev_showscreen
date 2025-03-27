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
        /** APARTADO STANDARD PARA TODOS LOS CONTROLADORES **/
        // Control de login
        helper('controlacceso');
        control_login();
    
        // Saco los datos del usuario
        $data = datos_user();
    
        // Conecto la BDD
        $db = db_connect($data['new_db']);
    
        // Obtener la línea de pedido
        $builder = $db->table('linea_pedidos');
        $builder->select('*');
        $builder->where('id_lineapedido', $id_lineapedido);
        $query = $builder->get();
        $data['lineas'] = $query->getResult();
    
        // Inicializar `$data['clientes']` como un array vacío por si no se encuentra el cliente
        $data['clientes'] = [];
    
        // Obtener los detalles del pedido y cliente
        foreach ($query->getResult() as $row) {
            $elpedido = $row->id_pedido;
            $builder = $db->table('pedidos');
            $builder->select('*');
            $builder->where('id_pedido', $elpedido);
            $query2 = $builder->get();
            $data['pedidos'] = $query2->getResult();
    
            // Obtener el cliente si existe
            if (!empty($data['pedidos']) && isset($data['pedidos'][0]->id_cliente)) {
                $elcliente = $data['pedidos'][0]->id_cliente;
    
                $builder = $db->table('clientes');
                $builder->select('*');
                $builder->where('id_cliente', $elcliente);
                $query5 = $builder->get();
                $data['clientes'] = $query5->getResult();
            }
        }
    
        // Saco los detalles del producto
        foreach ($query->getResult() as $row) {
            $elproducto = $row->id_producto;
            $builder = $db->table('productos');
            $builder->select('*');
            $builder->where('id_producto', $elproducto);
            $query3 = $builder->get();
            $data['productos'] = $query3->getResult();
        }
    
        // Saco los procesos
        $builder = $db->table('procesos_productos');
        $builder->select('*');
        $builder->where(['id_producto' => $elproducto]);
        $builder->join('procesos', 'procesos.id_proceso=procesos_productos.id_proceso', 'left');
        $builder->orderby('procesos_productos.orden', 'asc');
        $query4 = $builder->get();
        $data['procesos'] = $query4->getResult();
    
        // Devolver la vista de acuerdo con el tipo de solicitud
        if ($this->request->isAJAX()) {
            return view('partes', (array)$data); // solo el contenido
        } else {
            echo view('header_partes');
            echo view('partes', (array)$data);
            echo view('footer');
        }
    }
    

    public function pedido_print($id_pedido)
    {

        $db      = \Config\Database::connect();
        $builder = $db->table('pedidos');
        $builder->where('id_pedido', $id_pedido);
        $builder->select('*');
        $query = $builder->get();

        $data['records'] = $query->getResult();
        helper('url');
        echo view('pedidos', (array)$data);
    }
    //Cambio el estado de la linea de pedido a "material recibido" cuando sacamos el parte.
    public function CambiaEstado($id_lineapedido)
    {
        // Cambiar el estado de la línea de pedido
        $data2 = array('estado' => '2');
        helper('controlacceso');
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $builder = $db->table('linea_pedidos');
        $builder->set($data2);
        $builder->where('id_lineapedido', $id_lineapedido);
        $builder->update();

        // Revisar si todas las líneas han cambiado de estado y actualizar el estado del pedido
        $Lineaspedido_model = model('App\Models\Lineaspedido_model');
        $Lineaspedido_model->actualiza_estado_lineas($id_lineapedido);
        $this->obtenerLineasPedidoConEstado2YCrearProcesos();

        // Enviar respuesta para cerrar la pestaña actual
        echo "<script>window.close();</script>";
        exit;
    }


    public function verificarEstadoProcesos($id_lineapedido)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);

        // Confirmar que ningun proceso esta en estado = 4
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

    public function obtenerLineasPedidoConEstado2YCrearProcesos()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $lineaPedidoModel = new LineaPedido($db);
        $procesosPedidoModel = new ProcesosPedido($db);
        $procesosProductosModel = new ProcesosProductos($db);

        //Añadir que filtre por id_lineapedido
        $lineasConEstado2 = $lineaPedidoModel->where('estado', 2)->findAll();

        foreach ($lineasConEstado2 as $linea) {
            $idProducto = $linea['id_producto'];
            $procesosProductos = $procesosProductosModel->where('id_producto', $idProducto)->findAll();

            foreach ($procesosProductos as $procesoProducto) {
                // Comprobar si ya existe una línea con este id_proceso y id_linea_pedido
                $existe = $procesosPedidoModel->where([
                    'id_proceso' => $procesoProducto['id_proceso'],
                    'id_linea_pedido' => $linea['id_lineapedido']
                ])->first();

                // Si no existe, insertar la nueva fila
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
