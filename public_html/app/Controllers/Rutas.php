<?php

namespace App\Controllers;

use App\Models\Rutas_model;

class Rutas extends BaseController
{


	public function todas($coge_estado, $where_estado)
	{
		$this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Familia Productos');
        $data['amiga'] = $this->getBreadcrumbs();

		return view('mostrarRutas', [
			'estado' => json_encode([
				'condicion' => $coge_estado,
				'valor' => $where_estado,
				$data
			])
		]);
	}

	public function index()
	{
		return $this->todas('estado_ruta!=', '9'); // Asegura que usa "todas".
	}

	public function enmarcha()
	{
		return $this->todas('estado_ruta!=', '2'); // Asegura que usa "todas".
	}


	public function getRutas()
	{
		$coge_estado = $this->request->getJSON()->coge_estado ?? null;
		$where_estado = $this->request->getJSON()->where_estado ?? null;

		if ($coge_estado === null || $where_estado === null) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Los parámetros "coge_estado" y "where_estado" son requeridos.'
			])->setStatusCode(400);
		}

		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);

		try {
			$rutas = $model->getRutasWithDetails($coge_estado, $where_estado);

			// Formatear fechas
			foreach ($rutas as &$ruta) {
				$ruta['fecha_ruta'] = date('d-m-y', strtotime($ruta['fecha_ruta']));
			}

			return $this->response->setJSON(['success' => true, 'data' => $rutas]);
		} catch (\Exception $e) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Error al obtener las rutas: ' . $e->getMessage()
			])->setStatusCode(500);
		}
	}


	public function add_ruta()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$clientesModel = new \App\Models\ClienteModel($db);
		$poblacionesModel = new \App\Models\PoblacionesModel($db);

		$clientes = $clientesModel->findAll();
		$poblaciones = $poblacionesModel->findAll();
		$transportistas = $this->getTransportistas();

		// Formatear fecha actual
		$fechaHoy = date('d-m-y');

		return view('add_ruta', [
			'clientes' => $clientes,
			'poblaciones' => $poblaciones,
			'transportistas' => $transportistas,
			'fechaHoy' => $fechaHoy
		]);
	}

	public function addRuta()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);
		$data = $this->request->getPost();

		if ($model->insert($data)) {
			return redirect()->to('/rutas/enmarcha'); // Redirige a la URL deseada
		} else {
			return redirect()->back()->with('error', 'Error al añadir la ruta'); // Redirige al formulario con un mensaje de error
		}
	}


	public function editar_ruta($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);

		try {
			$ruta = $model->getRutaById($id);
			if (!$ruta) {
				throw new \Exception('Ruta no encontrada');
			}

			$clientesModel = new \App\Models\ClienteModel($db);
			$poblacionesModel = new \App\Models\PoblacionesModel($db);

			// Obtener listas para los selects
			$clientes = $clientesModel->findAll();
			$poblaciones = $poblacionesModel->findAll();
			$transportistas = $this->getTransportistas();

			return view('editar_rutas', [
				'ruta' => $ruta,
				'clientes' => $clientes,
				'poblaciones' => $poblaciones,
				'transportistas' => $transportistas
			]);
		} catch (\Exception $e) {
			return redirect()->back()->with('error', $e->getMessage());
		}
	}
	public function updateRuta($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);

		$updatedData = $this->request->getPost();

		try {
			if ($model->update($id, $updatedData)) {
				return redirect()->to('/rutas')->with('success', 'Ruta actualizada correctamente');
			} else {
				throw new \Exception('Error al actualizar la ruta');
			}
		} catch (\Exception $e) {
			return redirect()->back()->with('error', $e->getMessage());
		}
	}



	public function deleteRuta($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);

		if ($model->delete($id)) {
			return $this->response->setJSON(['success' => true, 'message' => 'Ruta eliminada correctamente']);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la ruta']);
		}
	}

	function getTransportistas()
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
}
