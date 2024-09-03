<?php

namespace App\Controllers;

use App\Models\ProductosNecesidadModel;
use App\Models\ProductosProveedorModel;

class ComparadorProductos extends BaseController
{
    public function index()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        
        // Instanciar los modelos
        $productosNecesidadModel = new ProductosNecesidadModel($db);
        $productosProveedorModel = new ProductosProveedorModel($db);

        // Obtener todos los productos de necesidad, ordenados por nombre
        $productos = $productosNecesidadModel->orderBy('nombre_producto', 'ASC')->findAll();

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
}
