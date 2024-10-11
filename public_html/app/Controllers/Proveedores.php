<?php

namespace App\Controllers;

use App\Models\ProductosProveedorModel;
use App\Models\ProveedoresModel;
use App\Models\FamiliaProveedorModel;


class Proveedores extends BaseControllerGC
{
    public function index()
    {
        return view('proveedores');
    }

    public function getProveedores()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('proveedores');
        $builder->select('proveedores.id_proveedor, proveedores.nombre_proveedor, proveedores.nif, proveedores.direccion, proveedores.contacto, proveedores.telf, proveedores.cargaen, proveedores.web, proveedores.email, provincias.provincia AS nombre_provincia');
        $builder->join('provincias', 'proveedores.id_provincia = provincias.id_provincia', 'left');
        $result = $builder->get()->getResult();

        foreach ($result as &$row) {
            $row->acciones = [
                'editar' => base_url("proveedores/edit/{$row->id_proveedor}"),
                'eliminar' => base_url("proveedores/delete/{$row->id_proveedor}")
            ];
        }

        return $this->response->setJSON($result);
    }


    public function add()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $provinciasModel = new \App\Models\ProvinciasModel($db);
        $provincias = $provinciasModel->findAll();

        return view('addProveedor', [
            'provincias' => $provincias
        ]);
    }

    public function verProductos($id_proveedor)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);
        $proveedoresModel = new ProveedoresModel($db);
        // Obtener el nombre del proveedor
        $proveedor = $proveedoresModel->find($id_proveedor);
        // Obtener los productos asociados al proveedor, incluyendo el campo id_producto_necesidad
        $productos = $model
            ->select('productos_proveedor.ref_producto, productos_proveedor.id_producto_necesidad, productos_necesidad.nombre_producto, productos_proveedor.precio')
            ->join('productos_necesidad', 'productos_necesidad.id_producto = productos_proveedor.id_producto_necesidad')
            ->where('productos_proveedor.id_proveedor', $id_proveedor)
            ->findAll();
        // Obtener todas las familias de productos
        $familiaProveedorModel = new FamiliaProveedorModel($db);
        $familias = $familiaProveedorModel->findAll();
        // Obtener todos los productos de la tabla productos_necesidad
        $productosNecesidadModel = new \App\Models\ProductosNecesidadModel($db);
        $productos_necesidad = $productosNecesidadModel->findAll();
        // Cargar la vista con los productos, el nombre del proveedor, las familias, el desplegable y el id_proveedor
        return view('productos_proveedor', [
            'productos' => $productos,
            'productos_necesidad' => $productos_necesidad,
            'id_proveedor' => $id_proveedor,
            'nombre_proveedor' => $proveedor['nombre_proveedor'],
            'familias' => $familias
        ]);
    }

    public function agregarProducto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);

        // Validar que el campo id_producto_necesidad no esté vacío
        if (empty($this->request->getPost('id_producto_necesidad'))) {
            return redirect()->back()->with('error', 'El ID del producto necesidad es obligatorio.');
        }

        $productoData = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'id_producto_necesidad' => $this->request->getPost('id_producto_necesidad'),
            'precio' => $this->request->getPost('precio'),
            'ref_producto' => $this->request->getPost('ref_producto'),
        ];

        // Log de adición de producto
        $log = "Producto añadido al proveedor ID: " . $productoData['id_proveedor'];
        $seccion = "Productos de proveedor";
        $this->logAction($seccion, $log, $data);

        // Insertar el producto en la base de datos
        $model->insert($productoData);

        // Verificar que la inserción fue exitosa antes de redirigir
        if ($db->affectedRows() > 0) {
            return redirect()->back()->with('message', 'Producto añadido con éxito.');
        } else {
            echo "Error al añadir el producto.";
        }
    }


    public function eliminarProducto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);
        $idProveedor = $this->request->getPost('id_proveedor');
        $idProductoNecesidad = $this->request->getPost('id_producto_necesidad');
        // Eliminar el producto asociado al proveedor
        $model->where('id_proveedor', $idProveedor)
            ->where('id_producto_necesidad', $idProductoNecesidad)
            ->delete();
        // Log de eliminación de producto
        $log = "Producto eliminado del proveedor ID: " . $idProveedor;
        $seccion = "Productos de proveedor";
        $this->logAction($seccion, $log, $data);

        return redirect()->back()->with('message', 'Producto eliminado con éxito.');
    }

    public function actualizarProducto()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);
        $idProveedor = $this->request->getPost('id_proveedor');
        $idProductoNecesidad = $this->request->getPost('id_producto_necesidad');
        $refProducto = $this->request->getPost('ref_producto');
        $precio = $this->request->getPost('precio');

        // Validación de los campos
        if (empty($precio) || empty($refProducto)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Los campos de precio y referencia son obligatorios.']);
        }

        // Actualizar el producto
        $model->where('id_proveedor', $idProveedor)
            ->where('id_producto_necesidad', $idProductoNecesidad)
            ->where('ref_producto', $refProducto)
            ->set([
                'ref_producto' => $refProducto,
                'precio' => $precio
            ])
            ->update();

        // Log de actualización de producto
        $log = "Producto actualizado para el proveedor ID: " . $idProveedor;
        $seccion = "Productos de proveedor";
        $this->logAction($seccion, $log, $data);

        return $this->response->setJSON(['success' => true]);
    }
    public function asociarProveedor()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);

        $id_producto = $this->request->getPost('id_producto');
        $id_proveedor = $this->request->getPost('id_proveedor');
        $ref_producto = $this->request->getPost('ref_producto');
        $precio = $this->request->getPost('precio');

        if (empty($id_producto) || empty($id_proveedor) || empty($ref_producto) || empty($precio)) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios.');
        }

        $model->insert([
            'id_producto_necesidad' => $id_producto,
            'id_proveedor' => $id_proveedor,
            'ref_producto' => $ref_producto,
            'precio' => $precio,
        ]);

        return redirect()->to(base_url('comparadorproductos/' . $id_producto))->with('message', 'Proveedor asociado exitosamente.');
    }
    public function elegirProveedor($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $proveedoresModel = new ProveedoresModel($db);

        // Obtener todos los proveedores para el desplegable
        $proveedores = $proveedoresModel->findAll();

        // Cargar la vista con los proveedores y el ID del producto
        return view('elegirProveedor', [
            'id_producto' => $id_producto,
            'proveedores' => $proveedores,
        ]);
    }
}
