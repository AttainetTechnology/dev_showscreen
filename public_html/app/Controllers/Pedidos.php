<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\Usuarios2_Model;
use App\Models\Pedidos_model;

class Pedidos extends BaseControllerGC
{
	protected $idpedido = 0;

	function __construct()
	{
		$this->idpedido = 0;
	}
	public function index()
	{
		$this->todos('estado!=', '8');
	}
	public function enmarcha()
	{
		$this->todos('estado<', '4');
	}
	public function terminados()
	{
		$this->todos('estado=', '4');
	}
	public function entregados()
	{
		$this->todos('estado=', '5');
	}

	//CREAMOS LA PAGINA DE PEDIDOS

	public function todos($coge_estado, $where_estado)
{
    // Control de login
    helper('controlacceso');
    $session = session();
    $data = datos_user();
    $db = db_connect($data['new_db']);
    $session_data = $session->get('logged_in');
    $nivel_acceso = $session_data['nivel'];

    $model = new Pedidos_model($db);

    // Obtener los pedidos con relaciones
    $data['pedidos'] = $model->getPedidoWithRelations($coge_estado, $where_estado);

    // Verificar el nivel de acceso para permitir la eliminación
    if ($nivel_acceso != 9) {
        $data['allow_delete'] = false;
    } else {
        $data['allow_delete'] = true;
    }

    // Cargar la vista pasando los datos
    echo view('mostrarPedido', $data);
}

}