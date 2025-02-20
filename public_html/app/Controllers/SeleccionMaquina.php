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

            // Consulta para obtener procesos
            $procesos = $procesosPedidoModel
                ->where('procesos_pedidos.id_maquina', $idMaquina)
                ->where('procesos_pedidos.estado <', 4)
                ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
                ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
                ->join('relacion_proceso_usuario', 'relacion_proceso_usuario.id_proceso_pedido = procesos_pedidos.id_relacion', 'left')
                ->groupStart()
                ->where('relacion_proceso_usuario.estado', 2)
                ->orWhere('relacion_proceso_usuario.id IS NULL')
                ->groupEnd()
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

            $imagenUrl = $producto['imagen']
                ? base_url("public/assets/uploads/files/{$empresaId}/productos/{$producto['imagen']}")
                : null;

            return [
                'nombre' => $producto['nombre_producto'],
                'imagen' => $imagenUrl
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
        $id_linea_pedido = $this->request->getPost('id_linea_pedido');
        $id_proceso_pedido = $this->request->getPost('id_proceso_pedido');
        $id_pedido = $this->request->getPost('id_pedido');
        $id_maquina = $this->request->getPost('id_maquina');

        $usuario = session()->get('usuario');
        $id_usuario = $usuario['id'];

        $data = [
            'id_pedido' => $id_pedido,
            'id_linea_pedido' => $id_linea_pedido,
            'id_proceso_pedido' => $id_proceso_pedido,
            'id_usuario' => $id_usuario,
            'id_maquina' => $id_maquina,
            'estado' => '1',
            'buenas' => 0,
            'malas' => 0,
            'repasadas' => 0
        ];

        $db = $this->db;
        $builder = $db->table('relacion_proceso_usuario');
        $builder->insert($data);

        $nuevo_id = $db->insertID();

        $builder = $db->table('relacion_proceso_usuario');
        $builder->where('id_proceso_pedido', $id_proceso_pedido)
            ->where('id !=', $nuevo_id)
            ->update(['estado' => 3]);

        return redirect()->to('/selectMaquina/' . $id_usuario)->with('success', 'Proceso seleccionado correctamente.');
    }

    public function obtenerProcesosUsuario($id_usuario)
    {
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');

        $procesos = $relacionModel
            ->join('procesos_pedidos', 'procesos_pedidos.id_relacion = relacion_proceso_usuario.id_proceso_pedido')
            ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
            ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
            ->join('productos', 'productos.id_producto = linea_pedidos.id_producto')
            ->where('relacion_proceso_usuario.id_usuario', $id_usuario)
            ->where('procesos_pedidos.estado <', 4)
            ->where('relacion_proceso_usuario.estado', 1)
            ->select('relacion_proceso_usuario.id, procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.observaciones, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial, linea_pedidos.id_pedido') // Selección del 'id' de la tabla 'relacion_proceso_usuario'
            ->get()
            ->getResultArray();
        foreach ($procesos as &$proceso) {
            $proceso['nombre_producto'] = $this->obtenerProducto($proceso['id_producto'])['nombre'];
            $proceso['imagen_producto'] = $this->obtenerProducto($proceso['id_producto'])['imagen'];
            $proceso['nombre_cliente'] = $this->obtenerNombreClientePorPedido($proceso['id_pedido']);
        }

        return $procesos;
    }

    public function obtenerProcesoPorId($id)
    {
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');

        $proceso = $relacionModel
            ->join('procesos_pedidos', 'procesos_pedidos.id_relacion = relacion_proceso_usuario.id_proceso_pedido')
            ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
            ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
            ->join('productos', 'productos.id_producto = linea_pedidos.id_producto')
            ->where('relacion_proceso_usuario.id', $id)
            ->get()
            ->getRowArray();

        if (!$proceso) {
            return redirect()->to('/error')->with('error', 'Proceso no encontrado o estado inválido.');
        }

        $proceso['nombre_producto'] = $this->obtenerProducto($proceso['id_producto'])['nombre'];
        $proceso['imagen_producto'] = $this->obtenerProducto($proceso['id_producto'])['imagen'];
        $proceso['nombre_cliente'] = $this->obtenerNombreClientePorPedido($proceso['id_pedido']);

        $unidadesIndividuales = $this->mostrarPiezas($id);

        $totales = $this->mostrarTotales($proceso['id_proceso_pedido']);

        return view('editarProcesoUser', [
            'proceso' => $proceso,
            'unidadesIndividuales' => $unidadesIndividuales,
            'totales' => $totales
        ]);
    }


    public function mostrarPiezas($idRelacionProcesoUsuario)
    {
        $db = $this->db;

        $unidadesIndividuales = $db->table('relacion_proceso_usuario')
            ->select('id, buenas, malas, repasadas')
            ->where('id', $idRelacionProcesoUsuario)
            ->get()
            ->getRowArray();
        if (!$unidadesIndividuales) {
            return redirect()->to('/error')->with('error', 'No se encontraron las unidades para este proceso.');
        }

        return $unidadesIndividuales;
    }

    public function mostrarTotales($idProcesoPedido)
    {
        $db = $this->db;

        $totales = $db->table('relacion_proceso_usuario')
            ->selectSum('buenas', 'total_buenas')
            ->selectSum('malas', 'total_malas')
            ->selectSum('repasadas', 'total_repasadas')
            ->where('id_proceso_pedido', $idProcesoPedido)
            ->get()
            ->getRowArray();

        if (!$totales) {
            return [
                'total_buenas' => 0,
                'total_malas' => 0,
                'total_repasadas' => 0
            ];
        }

        return $totales;
    }

    public function editarPiezas()
    {
        $buenas = $this->request->getPost('buenas');
        $malas = $this->request->getPost('malas');
        $repasadas = $this->request->getPost('repasadas');
        $action = $this->request->getPost('action');

        if ($buenas < 0 || $malas < 0 || $repasadas < 0) {
            return redirect()->to('/error')->with('error', 'Los valores no pueden ser negativos.');
        }

        $idRelacionProcesoUsuario = $this->request->getPost('id_relacion_proceso_usuario');

        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');
        $registro = $relacionModel->where('id', $idRelacionProcesoUsuario)->get()->getRowArray();

        if (!$registro) {
            return redirect()->to('/error')->with('error', 'Registro no encontrado.');
        }

        if ($action === 'falta_material') {
            $estado = 4;
            $relacionModel->where('id', $idRelacionProcesoUsuario)
                ->update(['estado' => $estado]);
            return redirect()->to('/selectMaquina');
        }

        $nuevasBuenas = $registro['buenas'] + $buenas;
        $nuevasMalas = $registro['malas'] + $malas;
        $nuevasRepasadas = $registro['repasadas'] + $repasadas;

        $estado = 1;
        if ($action === 'apuntar_terminar') {
            $estado = 3;
        } elseif ($action === 'apuntar_continuar') {
            $estado = 2;
        }

        $relacionModel->where('id', $idRelacionProcesoUsuario)
            ->update([
                'buenas' => $nuevasBuenas,
                'malas' => $nuevasMalas,
                'repasadas' => $nuevasRepasadas,
                'estado' => $estado
            ]);

        if ($action === 'apuntar_terminar' || $action === 'apuntar_continuar') {
            return redirect()->to('/selectMaquina');
        }

        return redirect()->to('/editarProceso/' . $idRelacionProcesoUsuario);
    }


}