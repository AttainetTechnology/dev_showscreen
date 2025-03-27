<?php

namespace App\Controllers;
// use App\Libraries\GroceryCrud;
use CodeIgniter\Model;
use App\Models\Rutas_model;
use App\Models\transportistas;

class Rutas extends BaseControllerGC
{

	public function index()
	{
		$this->todas('estado_ruta=', '2');
	}

	public function enmarcha()
	{
		$this->todas('estado_ruta!=', '2');
	}

	public function entregados()
	{
		$this->todas('estado_ruta=', '3');
	}

	public function todas($coge_estado, $where_estado)
	{
		$crud = $this->_getClientDatabase();

		// Configuración inicial del CRUD
		$crud->setSubject('Ruta', 'Rutas');
		$crud->setTable('rutas');
		$crud->where($coge_estado . $where_estado);
		$crud->defaultOrdering([
			'estado_ruta' => 'ASC',
			'fecha_ruta' => 'DESC'
		]);
		$crud->columns(array('fecha_ruta', 'estado_ruta', 'id_cliente', 'poblacion', 'lugar', 'recogida_entrega', 'observaciones', 'transportista', 'id_pedido'));
		$crud->addFields(['id_cliente', 'poblacion', 'lugar', 'recogida_entrega', 'observaciones', 'transportista', 'fecha_ruta']);
		$crud->editFields(['id_cliente', 'poblacion', 'lugar', 'recogida_entrega', 'observaciones', 'transportista', 'fecha_ruta', 'estado_ruta']);

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
		$transportistas = $this->transportistas(); // Obtener todos los transportistas
		$primer_transportista_id = array_key_first($transportistas); // Obtener el ID del primer transportista

		// Valor por defecto para 'transportista'
		$crud->callbackAddField('transportista', function () use ($primer_transportista_id, $transportistas) {
			$options = '';
			foreach ($transportistas as $id => $nombre) {
				$selected = $id == $primer_transportista_id ? 'selected' : '';
				$options .= '<option value="' . $id . '" ' . $selected . '>' . $nombre . '</option>';
			}
			return '<select name="transportista" class="form-control">' . $options . '</select>';
		});


		// Configuración del campo 'transportista'
		$crud->fieldType('transportista', 'dropdown_search', $this->transportistas());

		//Configuración de visualización
		$crud->setRelation('poblacion', 'poblaciones_rutas', 'poblacion');
		$crud->setRelation('id_cliente', 'clientes', 'nombre_cliente');
		$crud->displayAs('id_cliente', 'Cliente');
		$crud->requiredFields(['transportista', 'poblacion', 'id_cliente', 'recogida_entrega']);
		$crud->fieldType('estado_ruta', 'hidden', '0');
		$crud->fieldType('estado_ruta', 'dropdown_search', [
			'0' => 'Pendiente',
			'1' => 'No preparado',
			'2' => 'Recogido/Entregado'
		]);
		$crud->fieldType('recogida_entrega', 'dropdown_search', [
			'1' => 'Recogida',
			'2' => 'Entrega'
		]);
		$crud->callbackEditField('id_pedido', array($this, 'coge_id_pedido'));
		$crud->callbackAddField('id_pedido', array($this, 'coge_id_pedido'));
		$crud->callbackColumn('estado_ruta', function ($value, $row) {
			$resultado = "";
			if ($row->estado_ruta == '2') {
				if ($row->recogida_entrega == '1') {
					$resultado = "<div class='ruta2'>Recogido</div>";
				} else if ($row->recogida_entrega == '2') {
					$resultado = "<div class='ruta2'>Entregado</div>";
				}
			}
			if ($row->estado_ruta == '1') {
				$resultado = "
				<div class='ruta1'>No preparado <a href='" . base_url('Rutas/preparado/') . "/" . $row->id_ruta . "?pg1=transporte&pg2=rutasenmarcha' alt='Marcar como en marcha'><i class='bi bi-arrow-counterclockwise'></i> &#x21bb;</a></div>";
			}

			if ($row->estado_ruta == '0') {
				$resultado = "<div class='ruta'>Pendiente</div>";
			}
			if ($resultado) {
				return $resultado;
			}
		});

		$crud->callbackColumn('id_pedido', array($this, 'coge_nombre_cliente'));
		$crud->unsetSearchColumns(['id_pedido']);
		$crud->displayAs('id_pedido', 'Pedido');
		$crud->setLangString('modal_save', 'Guardar Ruta');

		// Restricciones basadas en el nivel de acceso
		if ($this->nivel < '6') {
			$crud->unsetDelete();
			$crud->unsetAdd();
			$crud->unsetRead();
			$crud->unsetEdit();
		}

		// Callbacks para registrar las acciones realizadas en la tabla LOG
		$crud->callbackAfterInsert(function ($stateParameters) {
			$this->logAction('Rutas', 'Añade ruta', $stateParameters);
			return $stateParameters;
		});
		$crud->callbackAfterUpdate(function ($stateParameters) {
			$this->logAction('Rutas', 'Edita ruta, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
			return $stateParameters;
		});
		$crud->callbackAfterDelete(function ($stateParameters) {
			$this->logAction('Rutas', 'Elimina ruta, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
			return $stateParameters;
		});

		// Renderizado del output
		$output = $crud->render();
		if ($output->isJSONResponse) {
			header('Content-Type: application/json; charset=utf-8');
			echo $output->output;
			exit;
		}
		echo view('layouts/main', (array)$output);
	}

	// Método para obtener la lista de transportistas
	function transportistas()
	{
		// Conexión a la base de datos original
		$db_original = \Config\Database::connect();

		// Conexión a la base de datos del cliente
		$data = usuario_sesion();
		$db_cliente = db_connect($data['new_db']);

		// Obtener nivel_acceso de la base de datos original
		$builder_original = $db_original->table('users');
		$builder_original->select('id, nivel_acceso');
		$builder_original->where('nivel_acceso', '1');
		$query_original = $builder_original->get();

		// Verificar si la consulta fue exitosa
		if (!$query_original) {
			log_message('error', 'Error en la consulta a la base de datos original: ' . $db_original->error());
			return [];
		}

		$transportistas_original = $query_original->getResultArray();

		// Obtener nombre y apellidos de la base de datos del cliente
		$builder_cliente = $db_cliente->table('users');
		$builder_cliente->select('id, nombre_usuario, apellidos_usuario');
		$query_cliente = $builder_cliente->get();

		// Verificar si la consulta fue exitosa
		if (!$query_cliente) {
			log_message('error', 'Error en la consulta a la base de datos del cliente: ' . $db_cliente->error());
			return [];
		}

		$transportistas_cliente = $query_cliente->getResultArray();

		// Combinar los datos
		$transport = [];
		foreach ($transportistas_original as $trans_original) {
			foreach ($transportistas_cliente as $trans_cliente) {
				if ($trans_original['id'] == $trans_cliente['id'] && $trans_original['nivel_acceso'] == '1') {
					$transport[$trans_cliente['id']] = $trans_cliente['nombre_usuario'] . " " . $trans_cliente['apellidos_usuario'];
				}
			}
		}

		return $transport;
	}

	// Método para obtener el nombre del transportista por su ID
	public function obtenerNombreTransportistaPorId($id_transportista)
	{
		$db_cliente = db_connect(usuario_sesion()['new_db']);
		$builder = $db_cliente->table('users');
		$builder->select('nombre_usuario, apellidos_usuario');
		$builder->where('id', $id_transportista);
		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			$result = $query->getRow();
			return $result->nombre_usuario . ' ' . $result->apellidos_usuario;
		}
	}

	// Método para obtener el nombre del cliente basado en el ID del pedido
	public function coge_nombre_cliente($id_pedido)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$builder = $db->table('pedidos');
		$builder->select('nombre_cliente');
		$builder->join('clientes', 'clientes.id_cliente = pedidos.id_cliente', 'left');
		$builder->where('id_pedido', $id_pedido);
		$query = $builder->get();

		$cliente = "";
		foreach ($query->getResult() as $row) {
			$cliente = $row->nombre_cliente;
		}
		return "<div><a href=" . base_url() . "/Pedidos/edit/" . $id_pedido . ">" . $cliente . "</a></div>";
	}

	// Método para obtener el ID del pedido (ejemplo)
	function coge_id_pedido($row)
	{
		return '<input type="text" name="id_pedido" value="' . 1 . '">';
	}

	// Método para marcar una ruta como 'preparado'

	public function preparado($id_ruta)
	{
		$data = usuario_sesion(); // Obtener los datos de la sesión
		$db = db_connect($data['new_db']); // Conectar a la base de datos
		$rutas_model = new Rutas_model($db);

		$data = [
			'estado_ruta' => '0' // Cambiar el estado a 'En progreso'
		];
		$rutas_model->update($id_ruta, $data);
		$this->enmarcha(); // Redirigir a la página de rutas 'En progreso'

		$post_array = ['action' => 'Actualizar "No preparado"', 'id_ruta' => $id_ruta];
		$this->logAction('Rutas', 'Actualizar "No preparado"', $post_array);
	}
}
