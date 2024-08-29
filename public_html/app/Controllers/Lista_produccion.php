<?php

namespace App\Controllers;

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
        // Control de login    
        helper('controlacceso');
        $nivel = control_login();
        // Fin Control de Login    

        // Comienza Grocery CRUD a montar la tabla    
        $crud = $this->_getClientDatabase();
        // Definimos las columnas y la tabla
        $crud->setTable('v_linea_pedidos_con_familia');
        $crud->setPrimaryKey('id_lineapedido', 'v_linea_pedidos_con_familia');
        $crud->columns(['fecha_entrada', 'id_cliente', 'id_producto', 'id_familia', 'id_pedido', 'estado']);
        $crud->setRelation('id_cliente', 'clientes', 'nombre_cliente');
        $crud->setRelation('id_familia', 'familia_productos', 'nombre');
        $crud->setRelation('id_producto', 'productos', 'nombre_producto');
        $crud->displayAs('fecha_entrada', 'Fecha de Entrada');
        $crud->displayAs('id_cliente', 'Cliente');
        $crud->displayAs('id_producto', 'Producto');
        $crud->displayAs('id_familia', 'Familia');
        $crud->displayAs('id_pedido', 'Pedido');
        $crud->displayAs('estado', 'Estado');

        // Aplicamos el filtro según la situación
        $crud->where($coge_estado . $where_estado);

        // Definimos el título de la tabla
        $ahora = date('d-m-y');
        $titulo_pagina = "Partes " . $situacion . " - fecha: " . $ahora;
        $crud->setSubject($titulo_pagina, $titulo_pagina);

        // Acciones personalizadas según el estado
        if ($where_estado == '0') {
            $crud->setActionButton('Parte', 'fa fa-print', function ($row) {
                $uri = service('uri');
                $uri = current_url(true);
                $pg2 = urlencode($uri); // Codifica la URL para evitar caracteres no permitidos
                return base_url('partes/print/' . $row->id_lineapedido) . '?volver=' . $pg2 .  'target="_blank"';
            }, true); // El último parámetro indica que es un enlace
        }


        if ($where_estado == '4') {
            $crud->setActionButton('Entregar', 'fa fa-truck', function ($row) {
                $uri = service('uri');
                $uri = current_url(true);
                $pg2 = $uri;
                return base_url('/lista_produccion/actualiza_linea/') . '/' . $row->id_lineapedido . '/5/?volver=' . $pg2;
            }, false);
        }

        $crud->callbackColumn('estado', array($this, '_cambia_color_lineas'));
        $crud->unsetEdit();
        $crud->unsetDelete();
        $crud->unsetAdd();
        $crud->unsetRead();

        $crud->callbackColumn('id_pedido', array($this, 'nombre_cliente'));

        $output = $crud->render();

        if ($output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }

        echo view('layouts/main', (array)$output);
    }

    function _cambia_color_lineas($estado)
    {
        $nombre_estado = "";
        if ($estado == '0') {
            $nombre_estado = "0. Pendiente de material";
        }
        if ($estado == '1') {
            $nombre_estado = "1. Falta material";
        }
        if ($estado == '2') {
            $nombre_estado = "2. Material recibido";
        }
        if ($estado == '3') {
            $nombre_estado = "3. En máquinas";
        }
        if ($estado == '4') {
            $nombre_estado = "4. Terminado";
        }
        if ($estado == '5') {
            $nombre_estado = "5. Entregado";
        }
        return "<div class='estado estado" . (($estado) ?: 'error') . "'>$nombre_estado</div>";
    }

    function nombre_cliente($id_pedido)
    {
        $Pedidos_model = model('App\Models\Pedidos_model');
        $pedido = $Pedidos_model->obtener_datos_pedido($id_pedido);
        foreach ($pedido as $row) {
            $cliente = $row->nombre_cliente;
            return "<b><a href=" . base_url() . "Pedidos2/enmarcha#/edit/" . $id_pedido . " target='_blank'>" . $id_pedido . " - " . $cliente . "</a></b>";
        }
    }


    public function actualiza_linea($id_lineapedido, $estado)
    {
        $Lineaspedido_model = model('App\Models\Lineaspedido_model');
        $Lineaspedido_model->actualiza_linea($id_lineapedido, $estado);
    
        // Conectar a la base de datos
        $data = datos_user();     
        $db = db_connect($data['new_db']);
        
        // Eliminar todos los registros de la tabla procesos_pedidos con la misma id_linea_pedido
        $builder = $db->table('procesos_pedidos');
        $builder->where('id_linea_pedido', $id_lineapedido);
        $builder->delete();
        
        // Obtener el id_pedido asociado a la línea de pedido
        $builder = $db->table('linea_pedidos');
        $builder->select('id_pedido');
        $builder->where('id_lineapedido', $id_lineapedido);
        $query = $builder->get();
        $id_pedido = $query->getRow()->id_pedido;
    
        // Comprobar si todas las líneas de pedido asociadas al id_pedido están en estado 5
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
    
        // Si todas las líneas de pedido están en estado 5, actualizar el estado del pedido a 5
        if ($allInState5) {
            $builder = $db->table('pedidos');
            $builder->set('estado', 5);
            $builder->where('id_pedido', $id_pedido);
            $builder->update();
        }
    
        // Redirigir a la URL de retorno si está definida
        if (isset($_GET['volver'])) {
            $volver = $_GET['volver'];
        }
        helper('url');
        return redirect()->to($volver);
    }
}
