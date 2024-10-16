<?php

namespace App\Controllers;

use App\Models\Productos_model;
use App\Models\ProductosNecesidadModel;
use App\Models\FamiliaProveedorModel;


use \Gumlet\ImageResize;

class Productos_necesidad extends BaseControllerGC
{

    public function index()
    {
        return view('productos_necesidad');
    }
    public function getProductos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $familiaModel = new FamiliaProveedorModel($db);
        $productos = $productosModel->findAll();
        foreach ($productos as &$producto) {
            $familia = $familiaModel->find($producto['id_familia']);
            $producto['nombre_familia'] = $familia ? $familia['nombre'] : 'Sin familia';
            $producto['imagen'] = $this->getImageUrl($producto['imagen'], $data['id_empresa'], $producto['id_producto']);
            $producto['acciones'] = [
                'precio' => base_url("comparadorproductos/{$producto['id_producto']}"),
                'verProductos' => base_url("productos_necesidad/verProductos/{$producto['id_producto']}"),
                'editar' => base_url("productos_necesidad/edit/{$producto['id_producto']}"),
                'eliminar' => base_url("productos_necesidad/delete/{$producto['id_producto']}")
            ];
        }
        return $this->response->setJSON($productos);
    }

    private function getImageUrl($imageName, $idEmpresa, $idProducto)
    {
        $path = "public/assets/uploads/files/{$idEmpresa}/productos_necesidad/{$idProducto}/";
        return $imageName ? base_url($path . $imageName) : '';
    }


    public function verProductos($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new Productos_model($db);
        $familiaModel = new \App\Models\FamiliaProveedorModel($db);

        $productos = $model->orderBy('nombre_producto', 'ASC')->findAll();
        $familias = $familiaModel->orderBy('nombre', 'ASC')->findAll();

        $productosNecesidadModel = new ProductosNecesidadModel($db);
        $productoNecesidad = $productosNecesidadModel->find($id_producto);
        $idProductoVentaSeleccionado = $productoNecesidad['id_producto_venta'];

        return view('selectProducto', [
            'productos' => $productos,
            'familias' => $familias,
            'id_producto' => $id_producto,
            'id_producto_venta' => $idProductoVentaSeleccionado
        ]);
    }

    public function actualizarProductoVenta()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new \App\Models\ProductosNecesidadModel($db);

        $idProductoNecesidad = $this->request->getPost('id_producto_necesidad');
        $idProductoVenta = $this->request->getPost('id_producto_venta');
        if ($idProductoVenta === null || $idProductoVenta === '') {
            $model->update($idProductoNecesidad, [
                'id_producto_venta' => null
            ]);
            $log = "Deselección de producto ID: " . $idProductoNecesidad;
            $seccion = "Deselección de Producto";
        } else {
            $model->update($idProductoNecesidad, [
                'id_producto_venta' => $idProductoVenta
            ]);
            $log = "Actualización producto: " . $idProductoNecesidad . ", nuevo producto: " . $idProductoVenta;
            $seccion = "Seleccion de producto";
        }
        $this->logAction($seccion, $log, $data);
        return $this->response->setJSON(['success' => true]);
    }
    public function add()
    {

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $familiaModel = new FamiliaProveedorModel($db);
        $familias = $familiaModel->findAll();

        return view('addProductoProveedor', ['familias' => $familias]);
    }

    public function save()
    {

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre_producto' => 'required',
            'id_familia' => 'required',
            'estado_producto' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $productosModel->save([
            'nombre_producto' => $this->request->getPost('nombre_producto'),
            'id_familia' => $this->request->getPost('id_familia'),
            'unidad' => $this->request->getPost('unidad'),
            'estado_producto' => $this->request->getPost('estado_producto')
        ]);

        return redirect()->to(base_url('productos_necesidad'))->with('success', 'Producto añadido correctamente.');
    }

    public function edit($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $familiaModel = new FamiliaProveedorModel($db);
        $producto = $productosModel->find($id_producto);
        $familias = $familiaModel->findAll();
        $productoVentaNombre = $this->obtenerNombreProductoVenta($id_producto);

        return view('editProductoProveedor', [
            'producto' => $producto,
            'familias' => $familias,
            'productoVentaNombre' => $productoVentaNombre,
            'id_empresa' => $data['id_empresa']
        ]);
    }
    public function update($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre_producto' => 'required',
            'id_familia' => 'required',
            'estado_producto' => 'required'
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $image = $this->request->getFile('imagen');
        $productFolder = "public/assets/uploads/files/{$data['id_empresa']}/productos_necesidad/{$id_producto}/";
        $imageName = $productosModel->find($id_producto)['imagen'];

        if ($image && $image->isValid()) {
            if ($imageName && file_exists($productFolder . $imageName)) {
                unlink($productFolder . $imageName);
            }
            $imageName = $image->getRandomName();
            if (!is_dir($productFolder)) {
                mkdir($productFolder, 0777, true);
            }
            $image->move($productFolder, $imageName);
        }
        $productosModel->update($id_producto, [
            'para_boton' => $this->request->getPost('para_boton'),
            'nombre_producto' => $this->request->getPost('nombre_producto'),
            'id_familia' => $this->request->getPost('id_familia'),
            'imagen' => $imageName,
            'unidad' => $this->request->getPost('unidad'),
            'estado_producto' => $this->request->getPost('estado_producto')
        ]);
        return redirect()->to(base_url('productos_necesidad/edit/' . $id_producto))->with('success', 'Producto actualizado correctamente.');
    }

    public function delete($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        if ($productosModel->find($id_producto)) {
            $productosModel->delete($id_producto);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Producto no encontrado.']);
        }
    }
    public function eliminarImagen($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $producto = $productosModel->find($id_producto);
        if ($producto && $producto['imagen']) {
            $productFolder = "public/assets/uploads/files/{$data['id_empresa']}/productos_necesidad/{$id_producto}/";
            $imagePath = $productFolder . $producto['imagen'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $productosModel->update($id_producto, ['imagen' => null]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No se encontró la imagen o el producto.']);
        }
    }
    private function obtenerNombreProductoVenta($id_producto_necesidad)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('productos_necesidad');
        $builder->select('id_producto_venta');
        $builder->where('id_producto', $id_producto_necesidad);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $id_producto_venta = $query->getRow()->id_producto_venta;
            if ($id_producto_venta) {
                $builder_productos = $db->table('productos');
                $builder_productos->select('nombre_producto');
                $builder_productos->where('id_producto', $id_producto_venta);
                $query_productos = $builder_productos->get();

                if ($query_productos->getNumRows() > 0) {
                    return $query_productos->getRow()->nombre_producto;
                }
            }
        }
        return 'No hay producto de venta seleccionado';
    }
}
