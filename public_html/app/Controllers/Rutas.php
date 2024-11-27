<?php

namespace App\Controllers;

use App\Models\Rutas_model;

class Rutas extends BaseController
{

	public function todas($coge_estado, $where_estado)
	{
		return view('mostrarRutas', [
			'estado' => json_encode([
				'condicion' => $coge_estado,
				'valor' => $where_estado
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
		// Recuperar parámetros enviados desde el frontend
		$coge_estado = $this->request->getJSON()->coge_estado ?? null;
		$where_estado = $this->request->getJSON()->where_estado ?? null;

		if ($coge_estado === null || $where_estado === null) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Los parámetros "coge_estado" y "where_estado" son requeridos.'
			])->setStatusCode(400);
		}

		// Conectar a la base de datos
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);

		try {
			// Pasar los parámetros al modelo y obtener las rutas
			$rutas = $model->getRutas($coge_estado, $where_estado);
			return $this->response->setJSON(['success' => true, 'data' => $rutas]);
		} catch (\Exception $e) {
			return $this->response->setJSON([
				'success' => false,
				'message' => 'Error al obtener las rutas: ' . $e->getMessage()
			])->setStatusCode(500);
		}
	}
	public function addRuta()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);
		$data = $this->request->getPost();

		if ($model->insert($data)) {
			return $this->response->setJSON(['success' => true, 'message' => 'Ruta añadida correctamente']);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al añadir la ruta']);
		}
	}

	public function updateRuta($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Rutas_model($db);
		$data = $this->request->getPost();

		if ($model->update($id, $data)) {
			return $this->response->setJSON(['success' => true, 'message' => 'Ruta actualizada correctamente']);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar la ruta']);
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

	public function getTransportistas()
	{
		// Lógica similar al método `transportistas()` en tu controlador actual.
		// Obtén los transportistas de las bases de datos correspondientes.
		$transportistas = [
			['id' => 1, 'name' => 'Transportista 1'],
			['id' => 2, 'name' => 'Transportista 2']
		];
		return $this->response->setJSON($transportistas);
	}
}
