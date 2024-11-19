<?php

namespace App\Controllers;

use App\Models\ProductosNecesidadModel;
use App\Models\ProductosProveedorModel;

class ComparadorProductos extends BaseController
{public function index($id_producto = null)
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Productos Necesidad', base_url('/productos_necesidad'));
        $this->addBreadcrumb('Comparador de Precios');
    
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
    
        $productosNecesidadModel = new ProductosNecesidadModel($db);
        $productosProveedorModel = new ProductosProveedorModel($db);
    
        $productos = $id_producto
            ? $productosNecesidadModel->where('id_producto', $id_producto)->findAll()
            : $productosNecesidadModel->orderBy('nombre_producto', 'ASC')->findAll();
    
        $comparador = [];
        foreach ($productos as $producto) {
            $ofertas = $productosProveedorModel
                ->select('productos_proveedor.*, proveedores.nombre_proveedor')
                ->join('proveedores', 'proveedores.id_proveedor = productos_proveedor.id_proveedor')
                ->where('id_producto_necesidad', $producto['id_producto'])
                ->findAll();
    
            // Determinar el producto "mejor"
            $idMejor = $producto['mejor']; // ID del mejor producto asociado al producto de necesidad
    
            foreach ($ofertas as &$oferta) {
                $oferta['es_mejor'] = ($oferta['id'] == $idMejor); // Agregar campo que indique si es el mejor
            }
    
            $comparador[] = [
                'producto' => $producto,
                'ofertas' => $ofertas,
            ];
        }
    
        return view('comparadorProductos', [
            'comparador' => $comparador,
            'amiga' => $this->getBreadcrumbs(),
        ]);
    }
    
    public function seleccionarMejor()
    {
        $productoIndex = $this->request->getPost('productoIndex'); // ID del producto en productos_necesidad
        $ofertaIndex = $this->request->getPost('ofertaIndex'); // ID del producto en productos_proveedor
    
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosNecesidadModel = new ProductosNecesidadModel($db);
    
        // Actualizar el campo 'mejor' en la tabla productos_necesidad
        $productosNecesidadModel->update($productoIndex, ['mejor' => $ofertaIndex]);
    
        $this->logAction("Selección Mejor Oferta", "Seleccion producto: $productoIndex, oferta: $ofertaIndex", $data);
    
        return $this->response->setJSON(['status' => 'success']);
    }
    
    public function deseleccionarMejor()
    {
        $productoIndex = $this->request->getPost('productoIndex'); // ID del producto en productos_necesidad
    
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosNecesidadModel = new ProductosNecesidadModel($db);
    
        // Limpiar el campo 'mejor' en la tabla productos_necesidad
        $productosNecesidadModel->update($productoIndex, ['mejor' => null]);
    
        $this->logAction("Deselección Mejor Oferta", "Deseleccion producto: $productoIndex", $data);
    
        return $this->response->setJSON(['status' => 'success']);
    }
    

    public function editarOferta($id_producto = null, $id_oferta = null)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosProveedorModel = new ProductosProveedorModel($db);
    
        // Si es una solicitud POST, intenta actualizar la oferta
        if ($this->request->getPost()) {
            $id_producto = $this->request->getPost('id_producto');
            $id_oferta = $this->request->getPost('id');
            $updateData = [
                'ref_producto' => $this->request->getPost('ref_producto'),
                'precio' => $this->request->getPost('precio'),
            ];
            if ($productosProveedorModel->update($id_oferta, $updateData)) {
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo actualizar la oferta']);
            }
        }
    
        // Cargar el formulario de edición si no es una solicitud POST
        $oferta = $productosProveedorModel->find($id_oferta);
        return view('editarOferta', ['oferta' => $oferta, 'id_producto' => $id_producto]);
    }
    
    
    public function eliminarOferta($id_producto, $id_oferta)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosProveedorModel = new ProductosProveedorModel($db);
    
        $productosProveedorModel->delete($id_oferta);
        
        $this->logAction("Eliminación de Oferta", "Producto: $id_producto, Oferta eliminada: $id_oferta", $data);
    
        return $this->response->setJSON(['success' => true]);
    }
    
    
}
