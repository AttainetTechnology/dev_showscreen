<?php

namespace App\Controllers;
// use App\Libraries\GroceryCrud;

class Submenus extends BaseControllerGC
{
	public function index($dependencia)
	{
		$titulo_submenu = "Submenú " . $_GET['titulo_submenu'] . "";
		//Control de login	
		$nivel = control_login();
		//Fin Control de Login
		$dependencia = $dependencia;
		if (!$dependencia) {
			$dependencia = '1';
		}
		if (!$titulo_submenu) {
			$titulo_submenu = '';
		}
		//Si el usuario no tiene permisos lo tiro a la home
		if ($nivel < '9') {
			header('Location: ' . base_url());
			exit();
		} else {
			$crud = $this->_getClientDatabase();

			$crud->setSubject('Submenús a ' . $titulo_submenu, $titulo_submenu);
			$crud->setTable('menu');
			$crud->where([
				'dependencia' => $dependencia

			]);
			$crud->columns(['posicion', 'titulo', 'enlace', 'nivel', 'activo']);
			$crud->requiredFields(['posicion', 'titulo', 'nivel', 'activo']);
			$crud->editFields(['posicion', 'titulo', 'enlace', 'nivel', 'activo', 'estilo', 'url_especial', 'separador', 'nueva_pestana']);
			$crud->AddFields(['titulo', 'posicion', 'enlace', 'nivel', 'activo', 'estilo', 'dependencia', 'url_especial', 'separador']);
			$crud->setRelation('nivel', 'niveles_acceso', '{id_nivel} - {nombre_nivel}');
			$crud->callbackEditField('dependencia', function ($dependencia) {
				return '<input name="dependencia" value="' . $dependencia . '"  />';
			});
			$crud->fieldType('nueva_pestana', 'dropdown_search', [
				'0' => 'No',
				'1' => 'Sí'
			]);
			$crud->displayAs('nueva_pestana', 'Abrir en nueva pestaña?');
			$crud->callbackAddField('dependencia', function () use ($dependencia) {
				return '<input name="dependencia" value="' . $dependencia . '"  />';
			});
			$crud->setActionButton('Submenú', 'fa fa-list', function ($row) {
				return base_url('/Submenus/') . "/" . $row->id . "?titulo_submenu=" . $row->titulo;
			}, false);
			$crud->fieldType('activo', 'dropdown_search', [
				'0' => 'Desactivado',
				'1' => 'Activo'
			]);
			$crud->fieldType('url_especial', 'dropdown_search', [
				'0' => 'No, url genérica.',
				'1' => 'Sí, url personalizada.'
			]);
			$crud->fieldType('separador', 'dropdown_search', [
				'' => 'Ninguno',
				'arriba' => 'Arriba',
				'abajo' => 'Abajo'
			]);
			$crud->displayAs('dependencia', '');
			$crud->setLangString('modal_save', 'Guardar');

			$output = $crud->render();
			$output->titulo = "Submenú de " . $titulo_submenu . "";
			if ($output->isJSONResponse) {
				header('Content-Type: application/json; charset=utf-8');
				echo $output->output;
				exit;
			}

			echo view('layouts/main', (array) $output);
		}
	}

}

