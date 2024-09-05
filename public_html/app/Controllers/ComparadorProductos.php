<?php

namespace App\Controllers;

use App\Models\ProductosNecesidadModel;
use App\Models\ProductosProveedorModel;

class ComparadorProductos extends BaseController
{
    public function index($id_producto = null)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Instanciar los modelos
        $productosNecesidadModel = new ProductosNecesidadModel($db);
        $productosProveedorModel = new ProductosProveedorModel($db);

        // Si se proporciona un ID de producto, filtrar por ese producto
        if ($id_producto) {
            $productos = $productosNecesidadModel->where('id_producto', $id_producto)->findAll();
        } else {
            // Si no se proporciona un ID de producto, obtener todos los productos de necesidad
            $productos = $productosNecesidadModel->orderBy('nombre_producto', 'ASC')->findAll();
        }

        // Crear un array para almacenar los productos y sus ofertas
        $comparador = [];

        // Recorrer todos los productos y obtener las ofertas de los proveedores
        foreach ($productos as $producto) {
            // Obtener las ofertas de los proveedores para este producto
            $ofertas = $productosProveedorModel
                ->select('productos_proveedor.*, proveedores.nombre_proveedor')
                ->join('proveedores', 'proveedores.id_proveedor = productos_proveedor.id_proveedor')
                ->where('id_producto_necesidad', $producto['id_producto'])
                ->findAll();

            // Agregar las ofertas al array de comparador
            $comparador[] = [
                'producto' => $producto,
                'ofertas' => $ofertas
            ];
        }

        // Pasar los datos a la vista
        return view('comparadorProductos', ['comparador' => $comparador]);
    }
    public function seleccionarMejor()
    {
        $productoIndex = $this->request->getPost('productoIndex');
        $ofertaIndex = $this->request->getPost('ofertaIndex');

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosProveedorModel = new ProductosProveedorModel($db);

        // Desmarcar cualquier otra oferta como "mejor" para este producto
        $productosProveedorModel->where('id_producto_necesidad', $productoIndex)
            ->set('seleccion_mejor', null)
            ->update();

        // Marcar la oferta seleccionada como "mejor"
        $productosProveedorModel->where('id', $ofertaIndex)
            ->set('seleccion_mejor', 1)
            ->update();

        $log = "Seleccion producto: " . $productoIndex . ", oferta:" . $ofertaIndex;
        $seccion = "Selección Mejor Oferta";
        $this->logAction($seccion, $log, $data);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function deseleccionarMejor()
    {
        $productoIndex = $this->request->getPost('productoIndex');
        $ofertaIndex = $this->request->getPost('ofertaIndex');

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productosProveedorModel = new ProductosProveedorModel($db);

        // Desmarcar la oferta seleccionada
        $productosProveedorModel->where('id', $ofertaIndex)
            ->set('seleccion_mejor', null)
            ->update();

        // Log de deselección de la mejor oferta
        $log = "Deseleccion producto: " . $productoIndex . ", oferta:" . $ofertaIndex;
        $seccion = "Deselección Mejor Oferta";
        $this->logAction($seccion, $log, $data);

        return $this->response->setJSON(['status' => 'success']);
    }
}
