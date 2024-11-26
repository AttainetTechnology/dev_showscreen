<?php

namespace App\Controllers;

use App\Models\Lineaspedido_model;
use App\Models\Pedidos_model;
use CodeIgniter\Controller;

class Lista_produccion extends BaseControllerGC
{
    protected $Menu_familias_model;

    public function pendientes()
    {
        $this->todos('estado=', '0', 'Pendientes');
    }

    public function enmarcha()
    {
        $this->todos('estado=', '2', 'En cola');
    }

    public function enmaquina()
    {
        $this->todos('estado=', '3', 'En máquina');
    }

    public function terminados()
    {
        $this->todos('estado=', '4', 'Terminados');
    }

    public function entregados()
    {
        $this->todos('estado=', '5', 'Entregados');
    }

    public function todoslospartes()
    {
        $this->todos('estado<', '7', '(Todos)');
    }

    public function todos($coge_estado, $where_estado, $situacion)
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Partes');
        // Control de login    
        helper('controlacceso');
        $nivel = control_login();

        // Conectar a la base de datos
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Obtener los datos de la tabla
        $builder = $db->table('v_linea_pedidos_con_familia');
        $builder->select('id_lineapedido, fecha_entrada, med_inicial, med_final, id_cliente, nom_base, id_producto, id_pedido, estado, id_familia');
        $builder->where($coge_estado . $where_estado);
        $builder->orderBy('fecha_entrada', 'DESC');
        $query = $builder->get();
        $result = $query->getResultArray();

        // Obtener nombres de relaciones
        $clientesModel = new \App\Models\ClienteModel($db);
        $familiasModel = new \App\Models\Familia_productos_model($db);
        $productosModel = new \App\Models\Productos_model($db);
        foreach ($result as &$row) {
            $cliente = $clientesModel->find($row['id_cliente'])['nombre_cliente'] ?? 'Desconocido';
            $row['pedido_completo'] = $row['id_pedido'] . ' - ' . $cliente;
            $row['nombre_cliente'] = $cliente;
            $row['nombre_familia'] = $familiasModel->find($row['id_familia'])['nombre'] ?? 'Desconocido';
            $row['nombre_producto'] = $productosModel->find($row['id_producto'])['nombre_producto'] ?? 'Desconocido';
            $estado = $this->asignaEstado($row['estado']);
            $row['estado'] = $estado['nombre_estado'];
            $row['estado_clase'] = $estado['estado_clase']; // Asigna la clase correspondiente
            $row['accion_parte'] = base_url('partes/print/' . $row['id_lineapedido']) . '?volver=' . urlencode(current_url()); // URL para el botón "Parte"
        }
        
        

        // Definimos el título de la tabla
        $ahora = date('d-m-y');
        $titulo_pagina = "Partes " . $situacion . " - fecha: " . $ahora;

        // Pasar los datos a la vista
        $data['titulo_pagina'] = $titulo_pagina;
        $data['result'] = $result;

        $data['amiga'] = $this->getBreadcrumbs();
        echo view('lista_produccion_view', $data);
    }

    function asignaEstado($estado)
    {
        $estado_clase = "";
        $nombre_estado = "";
        
        switch ($estado) {
            case '0':
                $nombre_estado = "Pendiente de material";
                $estado_clase = "estado1"; // Clase para el estado 1
                break;
            case '1':
                $nombre_estado = "Falta material";
                $estado_clase = "estado2"; // Clase para el estado 2
                break;
            case '2':
                $nombre_estado = "Material recibido";
                $estado_clase = "estado3"; // Clase para el estado 3
                break;
            case '3':
                $nombre_estado = "En máquinas";
                $estado_clase = "estado4"; // Clase para el estado 4
                break;
            case '4':
                $nombre_estado = "Terminado";
                $estado_clase = "estado5"; // Clase para el estado 5
                break;
            case '5':
                $nombre_estado = "Entregado";
                $estado_clase = "estado6"; // Clase para el estado 6
                break;
            case '6':
                $nombre_estado = "Anulado";
                $estado_clase = "estado7"; // Clase para el estado 7 (si lo necesitas)
                break;
            default:
                $nombre_estado = "Desconocido";
                $estado_clase = "estado-default"; // Clase por defecto
                break;
        }
    
        return ['nombre_estado' => $nombre_estado, 'estado_clase' => $estado_clase];
    }
    

    function nombre_cliente($id_pedido)
    {
        $Pedidos_model = new Pedidos_model();
        $pedido = $Pedidos_model->find($id_pedido);
        if ($pedido) {
            $cliente = $pedido->nombre_cliente;
            return "<b><a href='" . base_url("Pedidos/edit/{$id_pedido}") . "'>{$id_pedido} - {$cliente}</a></b>";
        }
        return "Desconocido";
    }


    public function actualiza_linea($id_lineapedido, $estado)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $Lineaspedido_model = new Lineaspedido_model($db);
        $Lineaspedido_model->actualiza_linea($id_lineapedido, $estado);
        $data = datos_user();
        $db = db_connect($data['new_db']);

        $builder = $db->table('procesos_pedidos');
        $builder->where('id_linea_pedido', $id_lineapedido);
        $builder->delete();

        $builder = $db->table('linea_pedidos');
        $builder->select('id_pedido');
        $builder->where('id_lineapedido', $id_lineapedido);
        $query = $builder->get();
        $id_pedido = $query->getRow()->id_pedido;

        $builder = $db->table('linea_pedidos');
        $builder->select('estado');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();
        $allInState5 = true;
        foreach ($query->getResult() as $row) {
            if ($row->estado != 5) {
                $allInState5 = false;
                break;
            }
        }
        if ($allInState5) {
            $builder = $db->table('pedidos');
            $builder->set('estado', 5);
            $builder->where('id_pedido', $id_pedido);
            $builder->update();
        }

        if (isset($_GET['volver'])) {
            $volver = $_GET['volver'];
        }
        helper('url');
        return redirect()->to($volver);
    }

}