<?php

namespace App\Controllers;

use App\Models\ProductosNecesidadModel;
use App\Models\ProductosProveedorModel;
use App\Models\ProveedoresModel;

class ComparadorProductos extends BaseController
{
    public function index()
    {
        $productosModel = new ProductosNecesidadModel();
        $productos = $productosModel->findAll();

        $productosProveedorModel = new ProductosProveedorModel();
        $proveedoresModel = new ProveedoresModel();

        // Crear un array para almacenar productos con sus respectivos proveedores
        $productosConProveedores = [];

        foreach ($productos as $producto) {
            // Obtener los proveedores para cada producto
            $proveedores = $productosProveedorModel
                ->select('proveedores.nombre_proveedor, productos_proveedor.precio')
                ->join('proveedores', 'proveedores.id_proveedor = productos_proveedor.id_proveedor')
                ->where('productos_proveedor.id_producto_necesidad', $producto['id_producto'])
                ->findAll();

            // Añadir los proveedores al producto
            $productosConProveedores[] = [
                'producto' => $producto,
                'proveedores' => $proveedores
            ];
        }

        // Pasar los datos a la vista
        return view('productos_lista', ['productosConProveedores' => $productosConProveedores]);
    }
}
