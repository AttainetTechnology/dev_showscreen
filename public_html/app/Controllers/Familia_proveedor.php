<?php 
namespace App\Controllers;

use App\Models\FamiliaProveedorModel;

class Familia_proveedor extends BaseController
{
    // Método que carga la vista principal de la tabla con ag-grid
    public function index()
    {
        return view('familiaProveedor');
    }

    // Obtener los datos para la tabla
    public function getFamiliasProveedores()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);
        $familias = $model->findAll();

        foreach ($familias as &$familia) {
            $familia['acciones'] = [
                'editar' => base_url('familiaProveedor/editar/' . $familia['id_familia']),
                'eliminar' => base_url('familiaProveedor/eliminar/' . $familia['id_familia'])
            ];
        }

        return $this->response->setJSON($familias);
    }

    public function eliminarFamilia($id_familia)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);
    
        // Eliminar la familia según el ID proporcionado
        $model->delete($id_familia);
    
        // Retornar la respuesta como JSON
        return $this->response->setJSON(['success' => true]);
    }
    

    public function actualizarFamilia()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);
    
        $idFamilia = $this->request->getPost('id_familia');
        $nombre = $this->request->getPost('nombre');
    
        if (empty($nombre)) {
            return $this->response->setJSON(['success' => false, 'message' => 'El campo nombre es obligatorio.']);
        }
    
        // Actualizar la familia utilizando `set` y `update`
        $model->set('nombre', $nombre)
              ->where('id_familia', $idFamilia)
              ->update();
    
        return $this->response->setJSON(['success' => true]);
    }
    

    // Cargar el formulario para editar la familia
    public function editar($id_familia)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new FamiliaProveedorModel($db);

        // Obtener los datos de la familia
        $familia = $model->find($id_familia);
        if (!$familia) {
            return $this->response->setJSON(['success' => false, 'message' => 'Familia no encontrada.']);
        }

        // Enviar los datos a la vista del modal
        return view('editFamiliaProveedorModal', ['familia' => $familia]);
    }

    public function agregarFamilia()
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $model = new FamiliaProveedorModel($db);

    $nombre = $this->request->getPost('nombre');

    if (empty($nombre)) {
        return $this->response->setJSON(['success' => false, 'message' => 'El campo nombre es obligatorio.']);
    }

    // Insertar una nueva familia
    $model->insert(['nombre' => $nombre]);

    return $this->response->setJSON(['success' => true]);
}

}
