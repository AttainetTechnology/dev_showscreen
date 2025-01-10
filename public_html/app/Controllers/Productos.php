<?php

namespace App\Controllers;

use App\Models\Productos_model;

class Productos extends BaseController
{
    public function index()
    {
        helper('controlacceso');
        $redirect = check_access_level();
        $redirectUrl = session()->getFlashdata('redirect');
        if ($redirect && is_string($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Productos');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('productos_view', ['amiga' => $data['amiga']]);

    }
    public function verProcesos($id_producto)
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Obtener el producto
        $producto = $db->table('productos')
            ->where('id_producto', $id_producto)
            ->get()
            ->getRow();

        if (!$producto) {
            return redirect()->to(base_url('productos'))->with('error', 'Producto no encontrado');
        }

        // Obtener todos los procesos
        $allProcesses = $db->table('procesos')->get()->getResult();

        // Obtener los procesos asociados al producto
        $procesos = $db->table('procesos_productos')
            ->select('procesos_productos.*, procesos.nombre_proceso')
            ->join('procesos', 'procesos.id_proceso = procesos_productos.id_proceso', 'left')
            ->where('id_producto', $id_producto)
            ->orderBy('procesos_productos.orden', 'asc')
            ->get()
            ->getResult();

        // Añadir migas de pan
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Productos', base_url('/productos'));
        $this->addBreadcrumb("Procesos de {$producto->nombre_producto}");

        // Preparar datos para la vista
        $data['producto'] = $producto;
        $data['allProcesses'] = $allProcesses;
        $data['procesos'] = $procesos;
        $data['amiga'] = $this->getBreadcrumbs();

        return view('procesos_view', $data);
    }


    public function updateOrder()
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        $jsonData = $this->request->getJSON(); // Decodificar JSON automáticamente

        if (!is_object($jsonData) || !isset($jsonData->id_producto) || !isset($jsonData->procesos)) {
            return $this->response->setStatusCode(400, 'Datos no válidos');
        }

        $id_producto = $jsonData->id_producto;
        $procesos = $jsonData->procesos;

        if (empty($id_producto) || !is_array($procesos)) {
            return $this->response->setStatusCode(400, 'Datos incompletos');
        }
        // Eliminar procesos existentes
        $db->table('procesos_productos')->where('id_producto', $id_producto)->delete();
        foreach ($procesos as $proceso) {
            if (!isset($proceso->id_proceso) || !isset($proceso->orden)) {
                log_message('error', 'Proceso malformado: ' . json_encode($proceso));
                continue; // Saltar procesos malformados
            }
            $db->table('procesos_productos')->insert([
                'id_producto' => $id_producto,
                'id_proceso' => $proceso->id_proceso,
                'orden' => $proceso->orden
            ]);
        }
        return $this->response->setJSON(['success' => true, 'message' => 'Orden actualizado correctamente']);
    }

    public function getProductos()
    {
        // Lógica existente para obtener productos
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
                : null; // Devuelve null si no hay imagen
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
    public function getUnidades()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $unidades = $db->table('unidades')->select('id_unidad, nombre_unidad')->get()->getResultArray();

        return $this->response->setJSON($unidades);
    }
    public function getFamilias()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $familias = $db->table('familia_productos')->select('id_familia, nombre')->get()->getResultArray();

        return $this->response->setJSON($familias);
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
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Productos', base_url('/productos'));
        $this->addBreadcrumb('Editar Producto');

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        $producto = $productosModel->find($id);
        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado');
        }

        $producto['imagen_url'] = $producto['imagen']
            ? base_url("public/assets/uploads/files/{$data['id_empresa']}/productos/{$producto['imagen']}")
            : null;

        $familias = $db->table('familia_productos')->get()->getResultArray();
        $unidades = $db->table('unidades')->get()->getResultArray();

        // Cargar imágenes desde la galería
        $gallery = new \App\Controllers\Gallery();
        $currentDirectory = $gallery->buildDirectoryPath('productos');
        [$folders, $images] = $gallery->scanDirectory($currentDirectory, 'productos');

        return view('editProducto', [
            'producto' => $producto,
            'familias' => $familias,
            'unidades' => $unidades,
            'amiga' => $this->getBreadcrumbs(),
            'data' => $data, // Pasar $data
            'images' => $images, // Pasar las imágenes a la vista
        ]);
    }

    public function editarProducto($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        $postData = $this->request->getPost();
        $file = $this->request->getFile('imagen');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $rutaProductos = "public/assets/uploads/files/{$data['id_empresa']}/productos";

            // Crear la carpeta principal si no existe
            if (!is_dir($rutaProductos)) {
                mkdir($rutaProductos, 0777, true);
            }

            // Generar un nombre único con el ID del usuario
            $nombreBase = pathinfo($file->getName(), PATHINFO_FILENAME);
            $extension = $file->getExtension();
            $idUser = $data['id_user']; // ID del usuario desde la sesión
            $nombreConId = "{$nombreBase}_IDUser{$idUser}.{$extension}";

            $rutaArchivo = "{$rutaProductos}/{$nombreConId}";

            if (file_exists($rutaArchivo)) {
                // Si la imagen ya existe con el ID del usuario, simplemente asociarla
                $postData['imagen'] = $nombreConId;
            } else {
                // Si no existe, moverla y asociarla
                $file->move($rutaProductos, $nombreConId);
                $postData['imagen'] = $nombreConId;
            }
        }

        if ($productosModel->update($id, $postData)) {
            return redirect()->to(base_url("productos/editarVista/{$id}"));
        } else {
            return redirect()->to(base_url("productos/editarVista/{$id}"))->with('error', 'Error al actualizar el producto');
        }
    }

    public function asociarImagen()
    {
        $id_producto = $this->request->getPost('id_producto');
        $imagen = $this->request->getPost('imagen');

        if (!$id_producto || !$imagen) {
            return $this->response->setJSON(['success' => false, 'message' => 'Faltan datos.']);
        }

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        $producto = $productosModel->find($id_producto);

        if (!$producto) {
            return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado.']);
        }

        $productosModel->update($id_producto, ['imagen' => $imagen]);

    }


    public function eliminarProducto($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        $producto = $productosModel->find($id);
        if (!$producto) {
            return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado.']);
        }

        $rutaImagenes = "public/assets/uploads/files/{$data['id_empresa']}/productos";

        // Eliminar la imagen del producto si existe
        if ($producto['imagen']) {
            $imagenPath = "{$rutaImagenes}/{$producto['imagen']}";
            if (file_exists($imagenPath)) {
                unlink($imagenPath);
            }
        }

        // Eliminar el producto de la base de datos
        if ($productosModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el producto.']);
        }

    }

    public function eliminarImagen($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new Productos_model($db);

        // Buscar el producto por ID
        $producto = $productosModel->find($id);
        if (!$producto || !$producto['imagen']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Imagen no encontrada.']);
        }
        // Desasociar la imagen del producto (establecer el campo 'imagen' en NULL)
        $productosModel->update($id, ['imagen' => null]);
    }


}
