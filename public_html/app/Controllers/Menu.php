<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\Nivel_model;

class Menu extends BaseController
{
	public function index()
	{
		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Menú');
		$data['amiga'] = $this->getBreadcrumbs();
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
		$nivelModel = new Nivel_model($db);

		// Obtener menús con nivel
		$menus = $menuModel->where('dependencia', 0)->findAll();

		$niveles = $nivelModel->findAll();
		$nivelesMap = [];
		foreach ($niveles as $nivel) {
			if (is_array($nivel)) {
				$nivel = (object) $nivel;  // Convertir el array en un objeto
			}
			$nivelesMap[$nivel->id_nivel] = $nivel->nombre_nivel;
		}
		foreach ($menus as &$menu) {
			$menu['nivel'] = $nivelesMap[$menu['nivel']] ?? 'Desconocido';
		}
		return [
			'sin_dependencia' => $menus
		];
	}
	public function getSubmenus($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);
		$nivelModel = new Nivel_model($db);

		// Obtener los submenús que tienen este ID como dependencia
		$submenus = $menuModel->where('dependencia', $id)->findAll();

		// Obtener los niveles y mapearlos
		$niveles = $nivelModel->findAll();
		$nivelesMap = [];
		foreach ($niveles as $nivel) {
			$nivelesMap[$nivel['id_nivel']] = $nivel['nombre_nivel'];
		}

		// Reemplazar el ID de nivel por el nombre en los submenús
		foreach ($submenus as &$submenu) {
			$submenu['nivel'] = $nivelesMap[$submenu['nivel']] ?? 'Desconocido';  // Mapeo
		}

		return [
			'submenus' => $submenus
		];
	}

	public function submenus($id)
	{

		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);
		$nivelModel = new Nivel_model($db);

		// Obtener los submenús que tienen este ID como dependencia
		$submenus = $this->getSubmenus($id);

		// Obtener el título del menú principal para mostrarlo en el encabezado
		$menu = $menuModel->find($id);
		$data['titulo'] = $menu ? $menu['titulo'] : 'Menú';
		$data['id'] = $id;

		// Obtener los niveles
		$data['niveles'] = $nivelModel->findAll();

		// Pasar los submenús y niveles a la vista
		$data['menus'] = $submenus;
		$this->addBreadcrumb('Inicio', base_url('/'));
		$this->addBreadcrumb('Menú', base_url('/menu'));
		$this->addBreadcrumb('Submenú');
		$data['amiga'] = $this->getBreadcrumbs();

		return view('submenu_view', $data);
	}

	public function delete($id)
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);

		// Comenzamos la transacción
		$db->transStart();

		// 1. Eliminar el menú principal
		$menuModel->delete($id);

		// 2. Buscar los menús dependientes (donde 'dependencia' sea igual al ID del menú eliminado)
		$dependentMenus = $menuModel->where('dependencia', $id)->findAll();

		if ($dependentMenus) {
			// 3. Eliminar los menús dependientes
			foreach ($dependentMenus as $menu) {
				$menuModel->delete($menu['id']);
			}
		}

		// Si todo salió bien, confirmamos la transacción
		$db->transComplete();

		// Verificamos si la transacción fue exitosa
		if ($db->transStatus() === FALSE) {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el menú y sus dependencias.']);
		} else {
			return $this->response->setJSON(['success' => true]);
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

		// Validar que el campo 'posicion' no esté vacío y sea un número
		if (empty($formData['posicion']) || !is_numeric($formData['posicion'])) {
			return $this->response->setJSON(['success' => false, 'message' => 'La posición debe ser un número válido.']);
		}

		// Insertar el nuevo menú en la base de datos
		if ($menuModel->insert($formData)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al añadir el menú.']);
		}
	}
	public function addSubmenu()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']);
		$menuModel = new MenuModel($db);

		// Recoger datos del formulario
		$formData = [
			'posicion' => $this->request->getPost('posicion'),
			'titulo' => $this->request->getPost('titulo'),
			'enlace' => $this->request->getPost('enlace'), // Recoger el enlace
			'nivel' => $this->request->getPost('nivel'),
			'activo' => $this->request->getPost('activo'),
			'url_especial' => $this->request->getPost('url_especial'),
			'nueva_pestana' => $this->request->getPost('nueva_pestana'),
			'dependencia' => $this->request->getPost('dependencia'),
		];

		if (empty($formData['posicion']) || !is_numeric($formData['posicion'])) {
			return $this->response->setJSON(['success' => false, 'message' => 'La posición debe ser un número válido.']);
		}

		// Insertar el nuevo submenú en la base de datos
		if ($menuModel->insert($formData)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al añadir el submenú.']);
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
			'nivel' => $this->request->getPost('nivel'),
			'activo' => $this->request->getPost('activo'),
			'url_especial' => $this->request->getPost('url_especial'),
			'nueva_pestana' => $this->request->getPost('nueva_pestana'),
		];

		// Actualizar el menú en la base de datos
		if ($menuModel->update($id, $formData)) {
			return $this->response->setJSON(['success' => true]);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el menú.']);
		}
	}

	public function updateSubmenu($id)
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
			'url_especial' => $this->request->getPost('url_especial'),
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
