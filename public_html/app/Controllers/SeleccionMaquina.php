<?php

namespace App\Controllers;

use App\Models\Maquinas;
use App\Models\ProcesosPedido;
use App\Models\Usuarios2_Model;



class SeleccionMaquina extends BaseFichar
{
    public function index()
    {
        $usuario = session()->get('usuario');

        if (!$usuario) {
            return redirect()->to('/login');
        }
        $id_usuario = $usuario['id'];
        return redirect()->to("/selectMaquina/{$id_usuario}");
    }

    public function getMaquina($id_usuario)
    {
        helper('controlacceso');
        $db = $this->db;

        $maquinasModel = new Maquinas($db);
        $maquinas = $maquinasModel->findAll();

        $usuariosModel = new Usuarios2_Model($db);
        $usuario = $usuariosModel->find($id_usuario);


        session()->set('usuario', $usuario);

        return view('selectMaquina', [
            'maquinas' => $maquinas,
            'usuario' => $usuario
        ]);
    }

    public function selectMaquina()
    {
        $usuario = session()->get('usuario');

        if (!$usuario) {
            return redirect()->to('/error');
        }

        $idMaquina = $this->request->getPost('id_maquina');

        if (!$idMaquina) {
            return redirect()->to(current_url());
        }
        if ($idMaquina) {

            $db = $this->db;
            $procesosPedidoModel = new ProcesosPedido($db);
            $procesos = $procesosPedidoModel
                ->where('procesos_pedidos.id_maquina', $idMaquina)
                ->where('procesos_pedidos.estado <', 4)
                ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
                ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
                ->select('procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.observaciones, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial, linea_pedidos.id_pedido')  // Traemos el id_pedido
                ->findAll();

            $maquinasModel = new Maquinas($db);
            $maquinas = $maquinasModel->findAll();
            $maquinaSeleccionada = $maquinasModel->find($idMaquina);
            foreach ($procesos as &$proceso) {
                $producto = $this->obtenerProducto($proceso['id_producto']);
                $proceso['nombre_producto'] = $producto['nombre'];
                $proceso['imagen_producto'] = $producto['imagen'];

                $nombreCliente = $this->obtenerNombreClientePorPedido($proceso['id_pedido']);
                $proceso['nombre_cliente'] = $nombreCliente;
            }

            return view('selectMaquina', [
                'maquinas' => $maquinas,
                'procesos' => $procesos,
                'usuario' => $usuario,
                'nombreMaquinaSeleccionada' => $maquinaSeleccionada['nombre'],
                'idMaquina' => $idMaquina
            ]);
        }
        return redirect()->to('selectMaquina');
    }

    public function obtenerProducto($idProducto)
    {
        $db = $this->db;
        $productoModel = new \App\Models\Productos_model($db);

        $producto = $productoModel->find($idProducto);
        if ($producto) {
            $empresaId = session()->get('id');

            // Generar la URL de la imagen
            $imagenUrl = $producto['imagen']
                ? base_url("public/assets/uploads/files/{$empresaId}/productos/{$producto['imagen']}")
                : null;

            return [
                'nombre' => $producto['nombre_producto'],
                'imagen' => $imagenUrl  // Se pasa la URL completa de la imagen
            ];
        }
    }

    public function obtenerNombreClientePorPedido($idPedido)
    {
        $db = $this->db;
        $pedidosModel = new \App\Models\Pedidos_model($db);
        $pedido = $pedidosModel->where('id_pedido', $idPedido)->first();

        if ($pedido) {
            $idCliente = $pedido->id_cliente;
            $clientesModel = new \App\Models\ClienteModel($db);
            $cliente = $clientesModel->where('id_cliente', $idCliente)->first();

            if ($cliente) {
                return $cliente['nombre_cliente'];
            }
        }

        return 'Cliente no encontrado';
    }
    public function seleccionarProceso()
    {
        // Obtener los datos del formulario
        $id_linea_pedido = $this->request->getPost('id_linea_pedido');
        $id_proceso_pedido = $this->request->getPost('id_proceso_pedido');
        $id_pedido = $this->request->getPost('id_pedido');
        $id_maquina = $this->request->getPost('id_maquina');

        // Obtener el usuario actual
        $usuario = session()->get('usuario');
        $id_usuario = $usuario['id'];

        // Crear el registro en la tabla 'relacion_proceso_usuario'
        $data = [
            'id_pedido' => $id_pedido,
            'id_linea_pedido' => $id_linea_pedido,
            'id_proceso_pedido' => $id_proceso_pedido,
            'id_usuario' => $id_usuario,
            'id_maquina' => $id_maquina,
            'estado' => 'en maquina', // Estado inicial
            'buenas' => 0,
            'malas' => 0,
            'repasadas' => 0
        ];

        // Insertar en la base de datos
        $db = $this->db;
        $builder = $db->table('relacion_proceso_usuario');
        $builder->insert($data);

        // Redirigir a la vista de selección de máquina
        return redirect()->to('/selectMaquina/' . $id_usuario)->with('success', 'Proceso seleccionado correctamente.');
    }
}
