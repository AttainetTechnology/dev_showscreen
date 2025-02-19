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
        $procesosUsuario = $this->obtenerProcesosUsuario($usuario['id']);
        return view('selectMaquina', [
            'maquinas' => $maquinas,
            'usuario' => $usuario,
            'procesosUsuario' => $procesosUsuario
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

            // Obtener procesos del usuario

            return view('selectMaquina', [
                'maquinas' => $maquinas,
                'procesos' => $procesos,
                'usuario' => $usuario,
                'nombreMaquinaSeleccionada' => $maquinaSeleccionada['nombre'],
                'idMaquina' => $idMaquina,

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
    public function obtenerProcesosUsuario($id_usuario)
    {
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');

        // Ajuste de la consulta con las relaciones correctas
        $procesos = $relacionModel
            ->join('procesos_pedidos', 'procesos_pedidos.id_relacion = relacion_proceso_usuario.id_proceso_pedido') // Aquí se relaciona con la tabla 'procesos_pedidos'
            ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso') // Relación con la tabla 'procesos'
            ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido') // Relación con la tabla 'linea_pedidos'
            ->join('productos', 'productos.id_producto = linea_pedidos.id_producto') // Relación con la tabla 'productos'
            ->where('relacion_proceso_usuario.id_usuario', $id_usuario)
            ->where('procesos_pedidos.estado <', 4) // Filtro para el estado
            ->select('relacion_proceso_usuario.id, procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.observaciones, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial, linea_pedidos.id_pedido') // Selección del 'id' de la tabla 'relacion_proceso_usuario'
            ->get()
            ->getResultArray();

        // Verificar si hay resultados
        if (empty($procesos)) {
            log_message('debug', "No se encontraron procesos para el usuario con ID: $id_usuario");
        } else {
            log_message('debug', "Procesos encontrados: " . print_r($procesos, true));
        }

        // Asegúrate de obtener los nombres de los productos y los clientes
        foreach ($procesos as &$proceso) {
            $proceso['nombre_producto'] = $this->obtenerProducto($proceso['id_producto'])['nombre'];
            $proceso['imagen_producto'] = $this->obtenerProducto($proceso['id_producto'])['imagen'];
            $proceso['nombre_cliente'] = $this->obtenerNombreClientePorPedido($proceso['id_pedido']);
        }

        return $procesos;
    }

    public function editarProceso($id)
    {
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');

        // Obtener el proceso desde la tabla 'relacion_proceso_usuario' usando 'id'
        $proceso = $relacionModel
            ->join('procesos_pedidos', 'procesos_pedidos.id_relacion = relacion_proceso_usuario.id_proceso_pedido')
            ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
            ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
            ->join('productos', 'productos.id_producto = linea_pedidos.id_producto')
            ->where('relacion_proceso_usuario.id', $id)  // Usamos el 'id' de la tabla relacion_proceso_usuario
            ->get()
            ->getRowArray();  // Usamos getRowArray() ya que esperamos solo un resultado

        if (!$proceso) {
            return redirect()->to('/error')->with('error', 'Proceso no encontrado.');
        }

        // Pasar los datos del proceso a la vista
        return view('editarProcesoUser', [
            'proceso' => $proceso
        ]);
    }
    public function obtenerProcesoPorId($id)
    {
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');
    
        // Obtener el proceso específico con el id recibido por URL
        $proceso = $relacionModel
            ->join('procesos_pedidos', 'procesos_pedidos.id_relacion = relacion_proceso_usuario.id_proceso_pedido')
            ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
            ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
            ->join('productos', 'productos.id_producto = linea_pedidos.id_producto')
            ->where('relacion_proceso_usuario.id', $id)  // Filtrar por el id de la tabla 'relacion_proceso_usuario'
            ->get()
            ->getRowArray();  // Usamos getRowArray() porque esperamos solo un resultado
    
        if (!$proceso) {
            return redirect()->to('/error')->with('error', 'Proceso no encontrado.');
        }
    
        // Obtener nombre del producto y cliente si no se han cargado
        $proceso['nombre_producto'] = $this->obtenerProducto($proceso['id_producto'])['nombre'];
        $proceso['imagen_producto'] = $this->obtenerProducto($proceso['id_producto'])['imagen'];
        $proceso['nombre_cliente'] = $this->obtenerNombreClientePorPedido($proceso['id_pedido']);
    
        return view('editarProcesoUser', [
            'proceso' => $proceso
        ]);
    }
    

}
