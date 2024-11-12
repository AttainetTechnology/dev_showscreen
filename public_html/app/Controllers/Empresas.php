<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\ProvinciasModel;

class Empresas extends BaseControllerGC
{
    public function index()
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Empresas');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('empresas_view', $data);
    }

    public function getEmpresas()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ClienteModel($db);
        $empresas = $model->select(['id_cliente', 'nombre_cliente', 'nif', 'direccion', 'id_provincia', 'poblacion', 'telf', 'cargaen', 'f_pago', 'web', 'email', 'observaciones_cliente'])->findAll();

        foreach ($empresas as &$empresa) {
            $empresa['acciones'] = [
                'editar' => base_url('empresas/editForm/' . $empresa['id_cliente']),
                'eliminar' => base_url('empresas/eliminar/' . $empresa['id_cliente'])
            ];
        }

        return $this->response->setJSON($empresas);
    }

	public function addForm()
	{
		$data = usuario_sesion();
		$db = db_connect($data['new_db']); 
		$provinciasModel = new ProvinciasModel($db); 
		$data['provincias'] = $provinciasModel->findAll(); 
	
		return view('addEmpresas', $data);
	}
	

    public function editForm($id)
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']); 
    $clienteModel = new ClienteModel($db);
    $provinciasModel = new ProvinciasModel($db);

    $data['empresa'] = $clienteModel->find($id);
    $data['provincias'] = $provinciasModel->findAll(); 

    return view('editEmpresa', $data); 
}

	public function add()
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $model = new ClienteModel($db);

    // Asegúrate de que estos campos existan en la tabla `clientes`
    $formData = [
        'nombre_cliente' => $this->request->getPost('nombre_cliente'),
        'nif' => $this->request->getPost('nif'),
        'direccion' => $this->request->getPost('direccion'),
        'pais' => $this->request->getPost('pais'),
        'id_provincia' => $this->request->getPost('id_provincia'),
        'poblacion' => $this->request->getPost('poblacion'),
        'telf' => $this->request->getPost('telf'),
        'fax' => $this->request->getPost('fax'),
        'cargaen' => $this->request->getPost('cargaen'),
        'exportacion' => $this->request->getPost('exportacion'),
        'f_pago' => $this->request->getPost('f_pago'),
        'otros_contactos' => $this->request->getPost('otros_contactos'),
        'observaciones_cliente' => $this->request->getPost('observaciones_cliente'),
        'id_contacto' => $this->request->getPost('id_contacto'),
        'email' => $this->request->getPost('email'),
        'web' => $this->request->getPost('web'),
    ];

    if (!$formData['nombre_cliente'] || !$formData['nif']) {
        return $this->response->setJSON(['success' => false, 'message' => 'Nombre y NIF son obligatorios.']);
    }

    if ($model->insert($formData)) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Error al agregar la empresa.']);
    }
}


    public function actualizar()
    {
        $id = $this->request->getPost('id_cliente');
        $model = new ClienteModel();
        if ($model->update($id, $this->request->getPost())) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar la empresa.']);
        }
    }
	public function eliminar($id)
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $model = new ClienteModel($db);

    // Intentar eliminar la empresa con el ID proporcionado
    if ($model->delete($id)) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar la empresa.']);
    }
}
public function getProvincias()
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']); 
    $provinciasModel = new ProvinciasModel($db); 
    return $this->response->setJSON($provinciasModel->findAll());
}

}
