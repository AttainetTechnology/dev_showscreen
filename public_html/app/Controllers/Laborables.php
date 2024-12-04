<?php

namespace App\Controllers;

use App\Models\Laborables_model;

class Laborables extends BaseController
{
	public function index()
	{
		helper('controlacceso');
		$nivel = control_login();
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);

		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Laborables');
		$data['amiga'] = $this->getBreadcrumbs();

		$laborablesModel = new Laborables_model($db);
		$laborables = $laborablesModel->find(1);

		return view('laborables_view', ['laborables' => $laborables, 'amiga' => $data['amiga']]);
	}
	public function actualizarLaborables()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$model = new Laborables_model($db);
		$postData = $this->request->getPost();

		$diasLaborables = [
			'lunes' => 1,
			'martes' => 2,
			'miercoles' => 3,
			'jueves' => 4,
			'viernes' => 5,
			'sabado' => 6,
			'domingo' => 7
		];

		foreach ($postData as $key => $value) {
			if (isset($diasLaborables[$key]) && $value == $diasLaborables[$key]) {
				$postData[$key] = $diasLaborables[$key];
			} else {
				$postData[$key] = 0;
			}
		}

		if ($model->update(1, $postData)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar los días laborables.']);
		}
	}

}
