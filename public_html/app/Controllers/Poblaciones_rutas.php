<?php

namespace App\Controllers;

use App\Models\PoblacionesModel;

class Poblaciones_rutas extends BaseController
{
    public function index()
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Poblaciones');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('poblacionesRutas', $data);
    }

    public function getPoblaciones()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new PoblacionesModel($db);
        $poblaciones = $model->findAll();
        
        foreach ($poblaciones as &$poblacion) {
            $poblacion['acciones'] = [
                'editar' => base_url('poblaciones_rutas/editar/' . $poblacion['id_poblacion']),
                'eliminar' => base_url('poblaciones_rutas/eliminar/' . $poblacion['id_poblacion'])
            ];
            
        }
        return $this->response->setJSON($poblaciones);
    }

    public function eliminarPoblacion($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new PoblacionesModel($db);
    
        $model->delete($id);
    
        return $this->response->setJSON(['success' => true]);
    }
    public function actualizarPoblacion()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new PoblacionesModel($db);
    
        $id = $this->request->getPost('id_poblacion');
        $poblacion = $this->request->getPost('poblacion');

        if (empty($id) || empty($poblacion)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Faltan datos.']);
        }
 
        $model->set('poblacion', $poblacion)
              ->where('id_poblacion', $id)
              ->update();
    
        return $this->response->setJSON(['success' => true]);
    }
    
    public function agregarPoblacion()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new PoblacionesModel($db);

        $poblacion = $this->request->getPost('poblacion');

        if (empty($poblacion)) {
            return $this->response->setJSON(['success' => false, 'message' => 'El campo poblacion es obligatorio.']);
        }

        $model->insert(['poblacion' => $poblacion]);

        return $this->response->setJSON(['success' => true]);
    }

    public function editar($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new PoblacionesModel($db);

        $poblacion = $model->find($id);

        if (!$poblacion) {
            return $this->response->setJSON(['success' => false, 'message' => 'Población no encontrada.']);
        }

        return view('editPoblacionModal', ['poblacion' => $poblacion]);
    }
}
