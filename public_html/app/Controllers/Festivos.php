<?php

namespace App\Controllers;

use App\Models\FestivosModel;

class Festivos extends BaseController
{
	public function index()
	{
		helper('controlacceso');
		$nivel = control_login();
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);

		// Breadcrumbs
		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Festivos');
		$data['amiga'] = $this->getBreadcrumbs();

		$festivoModel = new FestivosModel($db);
		$festivos = $festivoModel->findAll();
		return view('festivos_view', ['festivos' => $festivos, 'amiga' => $data['amiga']]);
	}

	public function getFestivos()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new FestivosModel($db);
		$festivos = $model->findAll();
		return $this->response->setJSON($festivos);
	}

	public function agregarFestivo()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new FestivosModel($db);
		$data = $this->request->getPost();

		if ($model->insert($data)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al agregar festivo.']);
		}
	}

	public function getFestivo($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new FestivosModel($db);
		$festivo = $model->find($id);

		if ($festivo) {
			return $this->response->setJSON($festivo);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Festivo no encontrado'], 404);
		}
	}

	public function actualizarFestivo($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new FestivosModel($db);
		$data = $this->request->getPost();

		if ($model->update($id, $data)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar festivo.']);
		}
	}

	public function eliminarFestivo($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new FestivosModel($db);

		if ($model->delete($id)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar festivo.']);
		}
	}
}
