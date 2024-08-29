<?php

namespace App\Controllers;
// use App\Libraries\GroceryCrud;
use CodeIgniter\Model;
setlocale(LC_ALL, 'spanish');
class Festivos extends BaseControllerGC
{
	

public function index()
{
//Control de login	
helper('controlacceso');
$nivel=control_login();
//Fin Control de Login

$crud = $this->_getClientDatabase();
$crud->setSubject('Festivo', 'Festivos');
$crud->setTable('festivos');
$crud->requiredFields(['festivo', 'fecha','tipo_festivo']);
$crud->unsetRead();
$crud->setLangString('modal_save', 'Guardar festivo');
$crud->fieldType('tipo_festivo', 'dropdown', [
	'1' => 'Se repite anualmente',
	'0' => 'Varía anualmente'
]);
$crud->callbackColumn('fecha',function($value, $row){
if ($row->tipo_festivo=='1'){

$fecha2 = $value;
$fecha_festivo= date_create ($fecha2);
$fecha_festivo=date_format($fecha_festivo, 'd M');
return $fecha_festivo;}
else {
	$fecha2 = $value;
	$fecha_festivo= date_create ($fecha2);
	$fecha_festivo=date_format($fecha_festivo, 'd/m/Y');
	return $fecha_festivo;}
});
$output = $crud->render();
if ($output->isJSONResponse) {
	header('Content-Type: application/json; charset=utf-8');
	echo $output->output;
	exit;
}
echo view('layouts/main', (array)$output);  

}

}