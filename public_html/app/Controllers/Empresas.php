<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use App\Models\ProvinciasModel;
use App\Models\PoblacionesModel;
use App\Models\FormasPagoModel;
use App\Models\PaisesModel;
use App\Models\Contactos;

class Empresas extends BaseController
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
    
        // Obtener empresas
        $empresas = $model->select(['id_cliente', 'nombre_cliente', 'nif', 'direccion', 'pais', 'id_provincia', 'poblacion', 'telf', 'cargaen', 'f_pago', 'web', 'email', 'observaciones_cliente'])->findAll();
    
        // Obtener listas de países, provincias, formas de pago y poblaciones
        $paisesModel = new PaisesModel($db);
        $provinciasModel = new ProvinciasModel($db);
        $formasPagoModel = new FormasPagoModel($db);
        $poblacionesModel = new PoblacionesModel($db);
    
        // Convertir las listas en arrays asociativos [id => nombre]
        $paises = array_column($paisesModel->obtenerPaises(), 'nombre', 'id');
        $provincias = array_column($provinciasModel->findAll(), 'provincia', 'id_provincia');
        $formasPago = array_column($formasPagoModel->obtenerFormasPago(), 'formapago', 'id_formapago');
        $poblaciones = array_column($poblacionesModel->obtenerPoblaciones(), 'poblacion', 'id_poblacion');
    
        // Reemplazar los IDs por los nombres correspondientes si existen en las listas
        foreach ($empresas as &$empresa) {
            if (isset($paises[$empresa['pais']])) {
                $empresa['pais'] = $paises[$empresa['pais']];
            }
            if (isset($provincias[$empresa['id_provincia']])) {
                $empresa['id_provincia'] = $provincias[$empresa['id_provincia']];
            }
            if (isset($formasPago[$empresa['f_pago']])) {
                $empresa['f_pago'] = $formasPago[$empresa['f_pago']];
            }
            if (isset($poblaciones[$empresa['poblacion']])) {
                $empresa['poblacion'] = $poblaciones[$empresa['poblacion']];
            }
    
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

        // Instancia los modelos
        $provinciasModel = new ProvinciasModel($db);
        $poblacionesModel = new PoblacionesModel($db);
        $formasPagoModel = new FormasPagoModel($db);
        $paisesModel = new PaisesModel($db);

        // Obtiene provincias, poblaciones, formas de pago y países
        $data['provincias'] = $provinciasModel->findAll();
        $data['poblaciones'] = $poblacionesModel->obtenerPoblaciones();
        $data['formas_pago'] = $formasPagoModel->obtenerFormasPago();
        $data['paises'] = $paisesModel->obtenerPaises();

        return view('addEmpresas', $data);
    }
    public function editForm($id)
    {
        // Verifica si el ID es válido
        if (!is_numeric($id)) {
            return $this->response->setStatusCode(400, 'ID inválido');
        }
    
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
    
        // Instancia los modelos
        $clienteModel = new ClienteModel($db);
        $contactoModel = new Contactos($db);
        $provinciasModel = new ProvinciasModel($db);
        $poblacionesModel = new PoblacionesModel($db);
        $formasPagoModel = new FormasPagoModel($db);
        $paisesModel = new PaisesModel($db);
    
        // Obtiene los datos de la empresa, provincias, poblaciones, formas de pago y países
        $data['empresa'] = $clienteModel->find($id);
        $data['provincias'] = $provinciasModel->findAll();
        $data['poblaciones'] = $poblacionesModel->obtenerPoblaciones();
        $data['formas_pago'] = $formasPagoModel->obtenerFormasPago();
        $data['paises'] = $paisesModel->obtenerPaises();
    
        // Obtiene los contactos relacionados con la empresa
        $data['contactos'] = $contactoModel->where('id_cliente', $id)->findAll();
    
        // Verifica que la empresa exista
        if (!$data['empresa']) {
            return $this->response->setStatusCode(404, 'Empresa no encontrada');
        }
    
        // Añade migas de pan para la vista de edición de la empresa
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Empresas', base_url('empresas'));
        $this->addBreadcrumb('Editar Empresa');
        $data['amiga'] = $this->getBreadcrumbs();
    
        return view('editarEmpresa', $data);
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
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ClienteModel($db);

        // Recoge y verifica los datos
        $formData = $this->request->getPost();

        if ($model->update($id, $formData)) {
            // Redirige a la misma vista de edición en caso de éxito
            return redirect()->to(base_url('empresas/editForm/' . $id))->with('success', 'La empresa ha sido actualizada correctamente.');
        } else {
            // Redirige con un mensaje de error en caso de fallo
            return redirect()->back()->with('error', 'Error al actualizar la empresa.');
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
    public function getContactosPorEmpresa($id_cliente)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $contactoModel = new Contactos($db);
    
        $contactos = $contactoModel->where('id_cliente', $id_cliente)->findAll();
        return $this->response->setJSON($contactos);
    }
    public function agregarContacto()
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $contactoModel = new Contactos($db);

    // Recoge los datos del formulario
    $nuevoContacto = [
        'nombre' => $this->request->getPost('nombre'),
        'apellidos' => $this->request->getPost('apellidos'),
        'telf' => $this->request->getPost('telf'),
        'cargo' => $this->request->getPost('cargo'),
        'email' => $this->request->getPost('email'),
        'id_cliente' => $this->request->getPost('id_cliente')
    ];

    // Inserta el contacto en la base de datos
    if ($contactoModel->insert($nuevoContacto)) {
        // Devuelve el nuevo contacto en la respuesta
        $nuevoContacto['id_contacto'] = $contactoModel->insertID(); // ID del nuevo contacto
        return $this->response->setJSON(['success' => true, 'data' => $nuevoContacto]);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Error al agregar el contacto.']);
    }
}

public function eliminarContacto($id_contacto)
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $contactoModel = new Contactos($db);

    // Intentar eliminar el contacto con el ID proporcionado
    if ($contactoModel->delete($id_contacto)) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el contacto.']);
    }
}
public function getContacto($id_contacto)
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $contactoModel = new Contactos($db);

    // Obtiene los datos del contacto
    $contacto = $contactoModel->find($id_contacto);

    if ($contacto) {
        return $this->response->setJSON(['success' => true, 'data' => $contacto]);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Contacto no encontrado'], 404);
    }
}


public function actualizarContacto()
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $contactoModel = new Contactos($db);

    // Recoge los datos del formulario
    $id_contacto = $this->request->getPost('id_contacto');
    $contactoData = [
        'nombre' => $this->request->getPost('nombre'),
        'apellidos' => $this->request->getPost('apellidos'),
        'telf' => $this->request->getPost('telf'),
        'cargo' => $this->request->getPost('cargo'),
        'email' => $this->request->getPost('email'),
    ];

    // Actualiza el contacto
    if ($contactoModel->update($id_contacto, $contactoData)) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el contacto.']);
    }
}

  

}
