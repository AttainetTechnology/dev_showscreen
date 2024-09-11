<?php

namespace App\Controllers;

class Ruta_pedido extends BaseControllerGC
{
    public $npedido = 0;
    public $idcliente;

    public function index()
    {
        //
    }

    /* Funciones de salida - Vistas */
    function _output_sencillo($output = null)
    {
        echo view('sencillo', (array)$output);
    }

    public function Rutas($pedido, $id_cliente)
    {
        $this->npedido = $pedido;
        $this->idcliente = $id_cliente;
        //Cargo los datos de acceso del usuario
        //Control de login
        helper('controlacceso');
        $nivel = control_login();
        //Fin Control de Login
        $crud = $this->_getClientDatabase();

        $crud->setSubject('Ruta', 'Rutas del pedido ' . $pedido);
        $crud->where('id_pedido=' . $pedido);
        $crud->columns(array('poblacion', 'lugar', 'recogida_entrega', 'observaciones', 'transportista', 'estado_ruta', 'fecha_ruta'));
        $crud->addFields(['id_cliente', 'poblacion', 'lugar', 'recogida_entrega', 'observaciones', 'transportista', 'id_pedido', 'fecha_ruta']);

        $crud->callbackAddField('recogida_entrega', function () {
            return '<select name="recogida_entrega" class="form-control">
						<option value="1" selected>Recogida</option>
						<option value="2">Entrega</option>
					</select>';
        });

        // Valor por defecto para 'fecha_ruta' (fecha actual)
        $crud->callbackAddField('fecha_ruta', function () {
            $currentDate = date('Y-m-d');
            return '<input type="date" name="fecha_ruta" value="' . $currentDate . '" class="form-control">';
        });

        // Obtener el primer transportista de la lista
        $transportistas = $this->transportistas();
        $primer_transportista_id = array_key_first($transportistas); 

        // Valor por defecto para 'transportista'
        $crud->callbackAddField('transportista', function () use ($primer_transportista_id, $transportistas) {
            $options = '';
            foreach ($transportistas as $id => $nombre) {
                $selected = $id == $primer_transportista_id ? 'selected' : '';
                $options .= '<option value="' . $id . '" ' . $selected . '>' . $nombre . '</option>';
            }
            return '<select name="transportista" class="form-control">' . $options . '</select>';
        });

        $crud->requiredFields(['transportista', 'poblacion']);
        $crud->displayAs('id_cliente', 'Cliente');
        $crud->setTable('rutas');
        $crud->defaultOrdering('poblacion', 'asc');

        $crud->fieldType('transportista', 'dropdown_search', $this->transportistas());
        $crud->setRelation('poblacion', 'poblaciones_rutas', 'poblacion');
        $crud->setRelation('id_cliente', 'clientes', 'nombre_cliente');

        $crud->fieldType('estado_ruta', 'dropdown_search', [
            '1' => 'Pendiente de recoger',
            '2' => 'No preparado',
            '3' => 'Recogido'
        ]);

        $crud->fieldType('recogida_entrega', 'dropdown_search', [
            '1' => 'Recogida',
            '2' => 'Entrega'
        ]);

        $crud->fieldType('id_pedido', 'hidden');
        $crud->fieldType('id_cliente', 'hidden');
        //$crud->callbackColumn('estado_ruta',array($this,'_cambia_color_lineas'));

        $crud->callbackAddForm(function ($data) {
            $data['id_pedido'] = $this->npedido;
            $data['id_cliente'] = $this->idcliente;
            return $data;
        });

        $crud->callbackAfterInsert(function ($stateParameters) {
            $id_pedido = $stateParameters->data['id_pedido'] ?? 'unknown';
            $this->logAction('Pedidos', 'Añade ruta, ID pedido: ' . $id_pedido, $stateParameters);
            return $stateParameters;
        });

        $output = $crud->render();
        if ($output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }

        $this->_output_sencillo($output);
    }

    public function _saca_fecha_entrada()
    {
        $entrada = date('d/m/y');
        return "<input id='field-fecha-entrada' type='text' name='fecha_ruta' value=" . $entrada . " class='datepicker-input form-control hasDatepicker'>";
    }

    function _cambia_color_lineas($estado_ruta)
    {
        $nombre_estado = "";
        if ($estado_ruta == '1') {
            $nombre_estado = "0. Pendiente";
        }
        if ($estado_ruta == '2') {
            $nombre_estado = "1. No preparado";
        }
        if ($estado_ruta == '3') {
            $nombre_estado = "2. Entregado / recogido";
        }
        return "<div class='ruta" . (($estado_ruta) ?: 'error') . "'>$nombre_estado</div>";
    }

    function paso_id_pedido($post_array, $pedido)
    {
        $post_array['data']['id_pedido'] = $pedido;
        return $post_array;
    }

    function transportistas()
    {
        // Crea una nueva instancia del modelo Usuarios2_Model
        $datos = new \App\Models\Usuarios2_Model();
        $data = usuario_sesion();
        $id_empresa = $data['id_empresa'];

        // Define los criterios para la consulta a la base de datos
        $array = ['nivel_acceso' => '1', 'id_empresa' => $id_empresa];
        $usuarios = $datos->where($array)->findAll();
        $user_ids = array();
        foreach ($usuarios as $usuario) {
            $user_ids[] = $usuario['id'];
        }

        // Conéctate a la base de datos del cliente
        $db_cliente = db_connect($data['new_db']);
        $builder = $db_cliente->table('users');
        $builder->select('id, nombre_usuario, apellidos_usuario');
        $builder->whereIn('id', $user_ids);
        $builder->where('user_activo', '1');
        $query = $builder->get();

        $transportistas = array();
        if ($query && $query->getNumRows() > 0) {
            foreach ($query->getResult() as $row) {
                $transportistas[$row->id] = $row->nombre_usuario . ' ' . $row->apellidos_usuario;
            }
        } else {
            if ($db_cliente->error()['code'] !== 0) {
                log_message('error', 'Database error: ' . json_encode($db_cliente->error()));
            } else {
                log_message('error', 'No rows found or query returned false.');
            }
        }

        return $transportistas;
    }
}
