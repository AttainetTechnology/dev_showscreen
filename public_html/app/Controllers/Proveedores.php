<?php

namespace App\Controllers;

use App\Models\ProductosProveedorModel;
use App\Models\ProveedoresModel;
use App\Models\FamiliaProveedorModel;

class Proveedores extends BaseController
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
        $this->addBreadcrumb('Proveedores');
        $data['amiga'] = $this->getBreadcrumbs();

        return view('proveedores', $data);
    }
    public function getProveedores()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('proveedores');
        $builder->select('proveedores.id_proveedor, proveedores.nombre_proveedor, proveedores.nif, proveedores.direccion, proveedores.contacto, proveedores.telf,proveedores.web, proveedores.email, provincias.provincia AS nombre_provincia');
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
        $builderPaises = $db->table('paises');
        $paises = $builderPaises->select('id, nombre')->get()->getResultArray();
        return view('addProveedor', [
            'provincias' => $provincias,
            'paises' => $paises,
        ]);
    }

    public function save()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $proveedoresModel = new ProveedoresModel($db);
        $nombreProveedor = $this->request->getPost('nombre_proveedor');
        if (empty($nombreProveedor)) {
            return redirect()->back()->with('error', 'El nombre del proveedor es obligatorio.');
        }
        $proveedorData = [
            'nombre_proveedor' => $nombreProveedor,
            'nif' => $this->request->getPost('nif'),
            'email' => $this->request->getPost('email'),
            'telf' => $this->request->getPost('telf'),
            'contacto' => $this->request->getPost('contacto'),
            'direccion' => $this->request->getPost('direccion'),
            'pais' => $this->request->getPost('pais'),
            'id_provincia' => $this->request->getPost('id_provincia'),
            'poblacion' => $this->request->getPost('poblacion'),
            'observaciones_proveedor' => $this->request->getPost('observaciones_proveedor'),
            'web' => $this->request->getPost('web'),
        ];
        if ($proveedoresModel->insert($proveedorData)) {
            return redirect()->to(base_url('proveedores'))->with('message', 'Proveedor añadido con éxito.');
        } else {
            return redirect()->back()->with('error', 'Error al añadir el proveedor.');
        }
    }
    public function edit($id)
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Proveedores', base_url('/proveedores'));
        $this->addBreadcrumb('Editar Proveedor');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $proveedoresModel = new ProveedoresModel($db);
        $data['amiga'] = $this->getBreadcrumbs();
        $proveedor = $proveedoresModel->find($id);
        if (!$proveedor) {
            return redirect()->to(base_url('proveedores'))->with('error', 'Proveedor no encontrado.');
        }
        $provinciasModel = new \App\Models\ProvinciasModel($db);
        $provincias = $provinciasModel->findAll();
        $builderPaises = $db->table('paises');
        $paises = $builderPaises->select('id, nombre')->get()->getResultArray();
        $builderFormasPago = $db->table('formas_pago');
        $formas_pago = $builderFormasPago->select('id_formapago, formapago')->get()->getResultArray();
        return view('editProveedores', [
            'proveedor' => $proveedor,
            'provincias' => $provincias,
            'paises' => $paises,
            'formas_pago' => $formas_pago,
            'amiga' => $data['amiga']
        ]);
    }

    public function update($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $proveedoresModel = new ProveedoresModel($db);
        $proveedor = $proveedoresModel->find($id);
        if (!$proveedor) {
            return redirect()->to(base_url('proveedores'))->with('error', 'Proveedor no encontrado.');
        }
        $proveedorData = [
            'nombre_proveedor' => $this->request->getPost('nombre_proveedor'),
            'nif' => $this->request->getPost('nif'),
            'email' => $this->request->getPost('email'),
            'telf' => $this->request->getPost('telf'),
            'contacto' => $this->request->getPost('contacto'),
            'direccion' => $this->request->getPost('direccion'),
            'pais' => $this->request->getPost('pais'),
            'id_provincia' => $this->request->getPost('id_provincia'),
            'poblacion' => $this->request->getPost('poblacion'),
            'observaciones_proveedor' => $this->request->getPost('observaciones_proveedor'),
            'web' => $this->request->getPost('web'),
        ];
        if ($proveedoresModel->update($id, $proveedorData)) {
            return redirect()->to(base_url('proveedores'))->with('message', 'Proveedor actualizado con éxito.');
        } else {
            return redirect()->back()->with('error', 'Error al actualizar el proveedor.');
        }
    }
    public function verProductos($id_proveedor)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);
        $proveedoresModel = new ProveedoresModel($db);
        $proveedor = $proveedoresModel->find($id_proveedor);
        $productos = $model
            ->select('productos_proveedor.ref_producto, productos_proveedor.id_producto_necesidad, productos_necesidad.nombre_producto, productos_proveedor.precio')
            ->join('productos_necesidad', 'productos_necesidad.id_producto = productos_proveedor.id_producto_necesidad')
            ->where('productos_proveedor.id_proveedor', $id_proveedor)
            ->findAll();
        $familiaProveedorModel = new FamiliaProveedorModel($db);
        $familias = $familiaProveedorModel->findAll();
        $productosNecesidadModel = new \App\Models\ProductosNecesidadModel($db);
        $productos_necesidad = $productosNecesidadModel->findAll();
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
        if (empty($this->request->getPost('id_producto_necesidad'))) {
            return redirect()->back()->with('error', 'El ID del producto necesidad es obligatorio.');
        }
        $productoData = [
            'id_proveedor' => $this->request->getPost('id_proveedor'),
            'id_producto_necesidad' => $this->request->getPost('id_producto_necesidad'),
            'precio' => $this->request->getPost('precio'),
            'ref_producto' => $this->request->getPost('ref_producto'),
        ];
        $model->insert($productoData);
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
        $model->where('id_proveedor', $idProveedor)
            ->where('id_producto_necesidad', $idProductoNecesidad)
            ->delete();
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

        if (empty($precio) || empty($refProducto)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Los campos de precio y referencia son obligatorios.']);
        }

        $model->where('id_proveedor', $idProveedor)
            ->where('id_producto_necesidad', $idProductoNecesidad)
            ->where('ref_producto', $refProducto)
            ->set([
                'ref_producto' => $refProducto,
                'precio' => $precio
            ])
            ->update();

        return $this->response->setJSON(['success' => true]);
    }
    public function asociarProveedor()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new ProductosProveedorModel($db);

        // Obtener los datos del formulario
        $id_producto = $this->request->getPost('id_producto');
        $id_proveedor = $this->request->getPost('id_proveedor');
        $ref_producto = $this->request->getPost('ref_producto');
        $precio = $this->request->getPost('precio');

        // Validar que los campos obligatorios no estén vacíos
        if (empty($id_producto) || empty($id_proveedor) || empty($ref_producto)) {
            return redirect()->back()->with('error', 'Todos los campos son obligatorios excepto el precio.');
        }

        // Si el campo precio está vacío, asignamos 0
        if (empty($precio)) {
            $precio = 0;
        } else {
            // Asegurarse de que el precio sea un número válido
            $precio = (float) str_replace(',', '.', $precio); // Reemplaza comas por puntos y convierte a float
        }

        // Insertar los datos en la base de datos
        $model->insert([
            'id_producto_necesidad' => $id_producto,
            'id_proveedor' => $id_proveedor,
            'ref_producto' => $ref_producto,
            'precio' => $precio,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->to(base_url('comparadorproductos/' . $id_producto))->with('message', 'Proveedor asociado exitosamente.');
    }

    public function elegirProveedor($id_producto)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $proveedoresModel = new ProveedoresModel($db);
        $proveedores = $proveedoresModel->findAll();

        return view('elegirProveedor', [
            'id_producto' => $id_producto,
            'proveedores' => $proveedores,
        ]);
    }
    public function delete($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $proveedoresModel = new ProveedoresModel($db);

        if ($proveedoresModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Proveedor eliminado con éxito.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No se pudo eliminar el proveedor.']);
        }
    }
}
