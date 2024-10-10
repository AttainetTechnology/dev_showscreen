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
        // Cargar la vista principal de AG-Grid para productos
        return view('productos_necesidad');
    }

    
    public function getProductos()
    {
        // Obtener productos en formato JSON para AG-Grid
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);
        $familiaModel = new FamiliaProveedorModel($db);
        
        // Obtener todos los productos
        $productos = $productosModel->findAll();

        // Asociar el nombre de la familia a cada producto
        foreach ($productos as &$producto) {
            // Obtener el nombre de la familia a partir del id_familia
            $familia = $familiaModel->find($producto['id_familia']);
            $producto['nombre_familia'] = $familia ? $familia['nombre'] : 'Sin familia';

            // Construir la URL de la imagen
            $producto['imagen'] = $this->getImageUrl($producto['imagen'], $data['id_empresa']);
            
            // Definir las acciones
            $producto['acciones'] = [
                'precio' => base_url("comparadorproductos/{$producto['id_producto']}"),
                'verProductos' => base_url("productos_necesidad/verProductos/{$producto['id_producto']}"),
                'editar' => base_url("productos_necesidad/edit/{$producto['id_producto']}"),
                'eliminar' => base_url("productos_necesidad/delete/{$producto['id_producto']}")
            ];
        }

        return $this->response->setJSON($productos);
    }

    private function getImageUrl($imageName, $idEmpresa)
    {
        $path = "public/assets/uploads/files/{$idEmpresa}/productos_necesidad/";
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

        // Si el id_producto_venta es null, deseleccionar el producto de venta
        if ($idProductoVenta === null || $idProductoVenta === '') {
            // Actualizar para eliminar la relación con el producto de venta
            $model->update($idProductoNecesidad, [
                'id_producto_venta' => null
            ]);

            // Log de deselección del producto de venta
            $log = "Deselección de producto ID: " . $idProductoNecesidad;
            $seccion = "Deselección de Producto";
        } else {
            // Actualizar el producto de venta
            $model->update($idProductoNecesidad, [
                'id_producto_venta' => $idProductoVenta
            ]);

            // Log de actualización del producto de venta
            $log = "Actualización producto: " . $idProductoNecesidad . ", nuevo producto: " . $idProductoVenta;
            $seccion = "Seleccion de producto";
        }

        // Registrar la acción en el log
        $this->logAction($seccion, $log, $data);

        // Devolver una respuesta JSON
        return $this->response->setJSON(['success' => true]);
    }
    public function add()
    {
        // Cargar vista del formulario de añadir producto
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $familiaModel = new FamiliaProveedorModel($db);

        // Obtener familias para el select en el formulario
        $familias = $familiaModel->findAll();

        return view('addProductoProveedor', ['familias' => $familias]);
    }

    public function save()
    {
        
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosModel = new ProductosNecesidadModel($db);

        // Validación básica
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nombre_producto' => 'required',
            'id_familia' => 'required',
            'estado_producto' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Guardar producto
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

    // Obtener el producto y las familias disponibles
    $producto = $productosModel->find($id_producto);
    $familias = $familiaModel->findAll();

    // Verificar si hay un producto de venta asociado
    $productoVentaNombre = $this->obtenerNombreProductoVenta($id_producto);

    return view('editProductoProveedor', [
        'producto' => $producto,
        'familias' => $familias,
        'productoVentaNombre' => $productoVentaNombre
    ]);
}
public function update($id_producto)
{
    $data = usuario_sesion();
    $db = db_connect($data['new_db']);
    $productosModel = new ProductosNecesidadModel($db);

    // Validación básica
    $validation = \Config\Services::validation();
    $validation->setRules([
        'nombre_producto' => 'required',
        'id_familia' => 'required',
        'estado_producto' => 'required'
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->back()->withInput()->with('errors', $validation->getErrors());
    }

    // Procesar imagen si se subió una nueva
    $image = $this->request->getFile('imagen');
    $imageName = $productosModel->find($id_producto)['imagen']; // Usar la imagen existente si no se carga una nueva
    if ($image && $image->isValid()) {
        $imageName = $image->getRandomName();
        $image->move("public/assets/uploads/files/{$data['id_empresa']}/productos_necesidad/", $imageName);
    }

    // Actualizar producto
    $productosModel->update($id_producto, [
        'para_boton' => $this->request->getPost('para_boton'),
        'nombre_producto' => $this->request->getPost('nombre_producto'),
        'id_familia' => $this->request->getPost('id_familia'),
        'imagen' => $imageName,
        'unidad' => $this->request->getPost('unidad'),
        'estado_producto' => $this->request->getPost('estado_producto')
    ]);

    return redirect()->to(base_url('productos_necesidad'))->with('success', 'Producto actualizado correctamente.');
}


    private function obtenerNombreProductoVenta($id_producto_necesidad)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        // Obtener el id_producto_venta asociado
        $builder = $db->table('productos_necesidad');
        $builder->select('id_producto_venta');
        $builder->where('id_producto', $id_producto_necesidad);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $id_producto_venta = $query->getRow()->id_producto_venta;

            if ($id_producto_venta) {
                // Obtener el nombre del producto de la tabla productos
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
