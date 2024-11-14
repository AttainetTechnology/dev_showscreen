<?php

namespace App\Controllers;

use App\Models\Productos_model;

class Productos extends BaseController
{
    public function index()
    {
        return view('productos_view'); // Vista principal de productos con ag-Grid
    }

    public function getProductos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        $productos = $productosModel->select('productos.*, 
                                              familia_productos.nombre AS nombre_familia, 
                                              unidades.nombre_unidad AS unidad_nombre')
            ->join('familia_productos', 'productos.id_familia = familia_productos.id_familia', 'left')
            ->join('unidades', 'productos.unidad = unidades.id_unidad', 'left')
            ->findAll();

        foreach ($productos as &$producto) {
            $producto['estado_nombre'] = $producto['estado_producto'] == 1 ? 'Activo' : 'Inactivo';
            $producto['imagen_url'] = $producto['imagen']
                ? base_url("public/assets/uploads/files/{$data['id_empresa']}/productos/{$producto['imagen']}")
                : base_url('public/assets/images/default.png'); // Imagen por defecto si no hay imagen
        }

        return $this->response->setJSON($productos);
    }

    public function getProducto($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        $producto = $productosModel->find($id);
        if ($producto) {
            $producto['imagen_url'] = $producto['imagen']
                ? base_url("public/assets/uploads/files/{$data['id_empresa']}/productos/{$producto['imagen']}")
                : base_url('public/assets/images/default.png');
            return $this->response->setJSON($producto);
        } else {
            return $this->response->setStatusCode(404, 'Producto no encontrado');
        }
    }

    public function agregarProducto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        $postData = $this->request->getPost();
        if ($productosModel->insert($postData)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al añadir el producto.']);
        }
    }

    public function editarVista($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        $producto = $productosModel->find($id);
        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado');
        }

        $producto['imagen_url'] = $producto['imagen']
            ? base_url("public/assets/uploads/files/{$data['id_empresa']}/productos/{$producto['imagen']}")
            : base_url('public/assets/images/default.png');

        return view('editProducto', ['producto' => $producto]);
    }

    public function editarProducto($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);
    
        $postData = $this->request->getPost();
        $file = $this->request->getFile('imagen');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move("public/assets/uploads/files/{$data['id_empresa']}/productos", $newName);
            $postData['imagen'] = $newName;
        }
    
        if ($productosModel->update($id, $postData)) {
        } else {
            session()->setFlashdata('error', 'Error al actualizar el producto');
        }
    
        // Redirige a la vista de edición para evitar el reenvío de datos POST
        return redirect()->to(base_url("productos/editarVista/{$id}"));
    }
    public function eliminarProducto($id)
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $productosModel = new Productos_model($db);

    if ($productosModel->delete($id)) {
        return $this->response->setJSON(['success' => true]);
    } else {
        return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el producto.']);
    }
}

}
