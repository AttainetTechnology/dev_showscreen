<?php

namespace App\Controllers;

use App\Models\Maquinas;
use App\Models\ProcesosPedido;



class SeleccionMaquina extends BaseFichar
{

    public function getMaquina()
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Cargar el modelo de Maquinas
        $maquinasModel = new Maquinas($db);

        // Obtener todas las máquinas
        $maquinas = $maquinasModel->findAll();

        // Retornamos la vista con las máquinas
        return view('selectMaquina', [
            'maquinas' => $maquinas
        ]);
    }
    public function selectMaquina()
    {
        // Verificamos si se ha seleccionado una máquina
        $idMaquina = $this->request->getPost('id_maquina');

        if ($idMaquina) {
            // Modelo para procesos pedidos
            helper('controlacceso');
            $data = usuario_sesion();
            $db = db_connect($data['new_db']);

            // Cargar el modelo de ProcesosPedido
            $procesosPedidoModel = new ProcesosPedido($db);

            // Traemos los procesos relacionados con la máquina seleccionada y con estado menor a 4
            $procesos = $procesosPedidoModel
                ->where('procesos_pedidos.id_maquina', $idMaquina)
                ->where('procesos_pedidos.estado <', 4)
                ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
                ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
                ->select('procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.observaciones, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial, linea_pedidos.id_pedido')  // Traemos el id_pedido
                ->findAll();

            // Cargamos el modelo de Maquinas y obtenemos la máquina seleccionada
            $maquinasModel = new Maquinas($db);
            $maquinas = $maquinasModel->findAll();  // Ejecutamos findAll() para obtener los datos de las máquinas
            $maquinaSeleccionada = $maquinasModel->find($idMaquina);
            // Iteramos sobre los procesos y obtenemos la información del producto
            foreach ($procesos as &$proceso) {
                $producto = $this->obtenerProducto($proceso['id_producto']);  // Obtener datos del producto
                $proceso['nombre_producto'] = $producto['nombre'];  
                $proceso['imagen_producto'] = $producto['imagen']; 

                // Obtener el nombre del cliente asociado al pedido de la linea de pedido
                $nombreCliente = $this->obtenerNombreClientePorPedido($proceso['id_pedido']);
                $proceso['nombre_cliente'] = $nombreCliente;  // Agregamos el nombre del cliente al proceso
            }

            // Mostrar la vista con los procesos encontrados y la máquina seleccionada
            return view('selectMaquina', [
                'maquinas' => $maquinas,  // Pasamos todas las máquinas
                'procesos' => $procesos,
                'nombreMaquinaSeleccionada' => $maquinaSeleccionada['nombre']
            ]);
        }

        // Si no se seleccionó máquina, redirigir de nuevo
        return redirect()->to('selectMaquina');
    }


    public function obtenerProducto($idProducto)
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $productoModel = new \App\Models\Productos_model($db);

        // Obtener los detalles del producto
        $producto = $productoModel->find($idProducto);

        // Verificamos si se encontró el producto
        if ($producto) {
            // Generar la URL de la imagen
            $imagenUrl = $producto['imagen']
                ? base_url("public/assets/uploads/files/{$data['id_empresa']}/productos/{$producto['imagen']}")
                : null;  // Si no tiene imagen, se asigna null

            return [
                'nombre' => $producto['nombre_producto'],
                'imagen' => $imagenUrl  // Se pasa la URL completa de la imagen
            ];
        }

        // Si no se encuentra el producto, retornamos un valor por defecto
        return [
            'nombre' => 'Producto no encontrado',
            'imagen' => 'default-image.jpg'  // Imagen predeterminada
        ];
    }

    public function obtenerNombreClientePorPedido($idPedido)
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Modelo para la tabla pedidos
        $pedidosModel = new \App\Models\Pedidos_model($db);

        // Buscar el cliente asociado al id_pedido
        $pedido = $pedidosModel->where('id_pedido', $idPedido)->first();

        if ($pedido) {
            // Obtener el id_cliente del pedido
            $idCliente = $pedido->id_cliente;

            // Modelo para la tabla clientes
            $clientesModel = new \App\Models\ClienteModel($db);

            // Buscar el cliente por el id_cliente
            $cliente = $clientesModel->where('id_cliente', $idCliente)->first();

            if ($cliente) {
                return $cliente['nombre_cliente'];
            }

        }

        return 'Cliente no encontrado';
    }


}

