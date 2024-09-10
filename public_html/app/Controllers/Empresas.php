<?php

namespace App\Controllers;

class Empresas extends BaseControllerGC
{
public function index()
{
		$crud = $this->_getClientDatabase();
		$crud->setSubject('Empresa', 'Empresas');
		$crud->setTable('clientes');
		// Relations
		$crud->setRelation('id_provincia','provincias','provincia');
		$crud->setRelation('pais','paises','nombre');
		$crud->setRelation('id_contacto','contactos','{nombre} {apellidos}');
		//Fields
		$crud->addFields(['nombre_cliente', 'nif', 'email', 'telf']);
		$crud->editFields(['nombre_cliente', 'nif','direccion','id_provincia','poblacion','telf','cargaen','f_pago','web','email','observaciones_cliente','id_contacto']);
		//Columns
		$crud->columns(['nombre_cliente', 'nif','direccion','id_provincia','poblacion','telf','cargaen','f_pago','web','email','observaciones_cliente']);
		$crud->displayAs('id_provincia', 'Provincia');
		$crud->setLangString('modal_save', 'Guardar Empresa');

		// Callbacks para registrar las acciones realizadas en LOG
		$crud->callbackAfterInsert(function ($stateParameters) {
			$this->logAction('Empresas', 'Añade empresa', $stateParameters);
			return $stateParameters;
		});
		$crud->callbackAfterUpdate(function ($stateParameters) {
			$this->logAction('Empresas', 'Edita empresa, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
			return $stateParameters;
		});
		$crud->callbackAfterDelete(function ($stateParameters) {
			$this->logAction('Empresas', 'Elimina empresa, ID: ' . $stateParameters->primaryKeyValue, $stateParameters);
			return $stateParameters;
		});
		$crud->callbackEditField('id_contacto', function ($fieldValue, $primaryKeyValue, $rowData) {
			//Conecto la BDD
			helper('controlacceso');
			$data= usuario_sesion(); 
			$db = db_connect($data['new_db']);
			$datos = model('Contactos', true, $db);
			$array = ['id_cliente' => $primaryKeyValue];
			$contactos= $datos->where($array)->findAll();
			$contact= "<table class='table table-striped'>";
				if ($contactos){
					foreach ($contactos as $row) {
					   $contact= $contact."<tr><td><b>".$row['nombre']." ".$row['apellidos'] ."</b></td><td>".$row['email']."</td><td>".$row['telf']."</td><td>".$row['cargo']."</td></tr>";
					}
				} 
					$boton="</table><div style='text-align:right'><br><button type='button' class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#myModal'>
					Editar contactos
					</button>	</div>
					";
					$editar = "
					<!-- Modal -->
					<div class='modal fade' id='myModal' tabindex='-1' aria-labelledby='myModalLabel' aria-hidden='true'>
					<div class='modal-dialog modal-xl'>
						<div class='modal-content'>
						<div class='modal-header'>
						<h5 class='modal-title' id='myModalLabel'>" . $rowData['nombre_cliente'] . "</h5>
							<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
						</div>
						<div class='modal-body'>
						<iframe src='" . base_url('/Contactos_empresa/add/' . $primaryKeyValue) . "' frameborder='0' width='100%' height='600px'></iframe>
						</div>
						</div>
					</div>
					</div>
					<!-- /.modal -->";
					$contact=$contact.$boton.$editar;
					return $contact;
		});
		//Output
		$output = $crud->render();
		return $this->_GC_output("layouts/main", $output); 
}

}

