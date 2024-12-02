<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\Nivel_model;

class Menu extends BaseController
{
	public function index()
	{
		$nivel = control_login();
		if ($nivel < '9') {
			header('Location: ' . base_url());
			exit();
		}

		// Obtener los menús
		$data['menus'] = $this->getMenus();

		// Obtener la sesión del usuario
		$sessionData = usuario_sesion();

		// Conectar a la base de datos con los datos del usuario
		$db = db_connect($sessionData['new_db']);

		// Cargar los niveles desde la base de datos
		$nivelModel = new Nivel_model($db);
		$data['niveles'] = $nivelModel->findAll();

		// Pasar los datos correctamente a la vista
		return view('menu_view', $data);
	}
	public function getMenus()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);

		// Obtener solo los menús sin dependencia
		$menusSinDependencia = $menuModel->where('dependencia', 0)->findAll();

		return [
			'sin_dependencia' => $menusSinDependencia
		];
	}


	public function getSubmenus($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);

		// Obtener los menús que tienen como dependencia el ID pasado
		$submenus = $menuModel->where('dependencia', $id)->findAll();

		return [
			'submenus' => $submenus
		];
	}

	public function submenus($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);

		// Obtener los submenús que tienen este ID como dependencia
		$submenus = $this->getSubmenus($id);

		// Obtener el título del menú principal para mostrarlo en el encabezado
		$menu = $menuModel->find($id);
		$data['titulo'] = $menu ? $menu['titulo'] : 'Menú';

		// Pasar los submenús a la vista
		$data['submenus'] = $submenus['submenus'];

		return view('submenu_view', $data);
	}

	public function delete($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);
		if ($menuModel->delete($id)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el menú.']);
		}
	}
	public function add()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);

		// Recoger datos del formulario
		$formData = [
			'posicion' => $this->request->getPost('posicion'),
			'titulo' => $this->request->getPost('titulo'),
			'enlace' => $this->request->getPost('enlace'),
			'nivel' => $this->request->getPost('nivel'),
			'activo' => $this->request->getPost('activo'),
			'estilo' => $this->request->getPost('estilo'),
			'url_especial' => $this->request->getPost('url_especial'),
			'separador' => $this->request->getPost('separador'),
			'nueva_pestana' => $this->request->getPost('nueva_pestana'),
			'dependencia' => 0, // Menú sin dependencia
		];

		// Insertar el nuevo menú en la base de datos
		if ($menuModel->insert($formData)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al añadir el menú.']);
		}
	}
	public function edit($id)
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $menuModel = new MenuModel($db);

    // Obtener el menú con el ID proporcionado
    $menu = $menuModel->find($id);

    if (!$menu) {
        return $this->response->setJSON(['success' => false, 'message' => 'Menú no encontrado']);
    }

    return $this->response->setJSON([
        'success' => true,
        'menu' => $menu
    ]);
}


	public function update($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);

		// Recoger los datos del formulario
		$formData = [
			'posicion' => $this->request->getPost('posicion'),
			'titulo' => $this->request->getPost('titulo'),
			'enlace' => $this->request->getPost('enlace'),
			'nivel' => $this->request->getPost('nivel'),
			'activo' => $this->request->getPost('activo'),
			'estilo' => $this->request->getPost('estilo'),
			'url_especial' => $this->request->getPost('url_especial'),
			'separador' => $this->request->getPost('separador'),
			'nueva_pestana' => $this->request->getPost('nueva_pestana'),
		];

		// Actualizar el menú en la base de datos
		if ($menuModel->update($id, $formData)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el menú.']);
		}
	}

}
