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

        $datos = [
            'cabecera' => view('template/cabecera_select'),
            'hora' => view('template/hora_logo'),
            'maquinas' => $maquinas,
            'usuario' => $usuario,
            'procesosUsuario' => $procesosUsuario
        ];

        return view('selectMaquina', $datos);
    }

    public function entrarEditor($id_usuario)
    {
        helper('controlacceso');
        $db = $this->db;

        $maquinasModel = new Maquinas($db);
        $maquinas = $maquinasModel->findAll();

        $usuariosModel = new Usuarios2_Model($db);
        $usuario = $usuariosModel->find($id_usuario);

        session()->set('usuario', $usuario);

        $relacionModel = $db->table('relacion_proceso_usuario');
        $registroRelacion = $relacionModel->where('id_usuario', $id_usuario)
            ->where('estado', 1)
            ->get()
            ->getRowArray();

        if ($registroRelacion) {
            return redirect()->to('/editarProceso/' . $registroRelacion['id']);
        }

        $procesosUsuario = $this->obtenerProcesosUsuario($usuario['id']);

        $datos = [
            'cabecera' => view('template/cabecera_select'),
            'hora' => view('template/hora_logo'),
            'maquinas' => $maquinas,
            'usuario' => $usuario,
            'procesosUsuario' => $procesosUsuario
        ];

        return view('selectMaquina', $datos);
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
                ->groupStart()
                ->where('procesos_pedidos.restriccion', '') 
                ->orWhere('procesos_pedidos.restriccion IS NULL')
                ->groupEnd()
                ->where('procesos_pedidos.estado', 3)
                ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
                ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
                ->join('relacion_proceso_usuario', 'relacion_proceso_usuario.id_proceso_pedido = procesos_pedidos.id_relacion', 'left')
                ->groupStart()
                ->where('relacion_proceso_usuario.estado', 2)
                ->orWhere('relacion_proceso_usuario.id IS NULL')
                ->groupEnd()
                ->orderBy('procesos_pedidos.orden', 'asc')
                ->select('procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.observaciones, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial, linea_pedidos.id_pedido')
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

            $datos = [
                'cabecera' => view('template/cabecera_select'),
                'hora' => view('template/hora_logo'),
                'maquinas' => $maquinas,
                'procesos' => $procesos,
                'usuario' => $usuario,
                'nombreMaquinaSeleccionada' => $maquinaSeleccionada['nombre'],
                'idMaquina' => $idMaquina,
            ];

            return view('selectMaquina', $datos);
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

        return redirect()->to('/selectMaquina')->with('success', 'Proceso seleccionado correctamente.');
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
            ->select('relacion_proceso_usuario.id, procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.observaciones, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial, linea_pedidos.id_pedido')
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
            'cabecera' => view('template/cabecera_select'),
            'hora' => view('template/hora_logo'),
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
        $usuario = session()->get('usuario')['id'];  
        $buenas = $this->request->getPost('buenas');
        $malas = $this->request->getPost('malas');
        $repasadas = $this->request->getPost('repasadas');
        $action = $this->request->getPost('action');
    
        if ($buenas < 0 || $malas < 0 || $repasadas < 0) {
            return redirect()->to('/error')->with('error', 'Los valores no pueden ser negativos.');
        }
    // Creo la variable para almacenar el id de la relación del proceso usuario 
    // y la inicializo con el valor del post.
        $idRelacionProcesoUsuario = $this->request->getPost('id_relacion_proceso_usuario');

    // Verifico que el id de la relación del proceso usuario no esté vacío.
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');
        $registro = $relacionModel->where('id', $idRelacionProcesoUsuario)->get()->getRowArray();
    
        if (!$registro) {
            return redirect()->to('/error')->with('error', 'Registro no encontrado.');
        }
    // Obtengo el id de la línea de pedido del registro actual para guardar el valor de "buenas" en el último fichaje
    $id_lineapedido = $registro['id_linea_pedido'];
    $id_proceso = $registro['id_proceso_pedido'];
    $lineaPedidoModel = $db->table('linea_pedidos');
    $lineaPedido = $lineaPedidoModel->where('id_lineapedido', $id_lineapedido)->get()->getRowArray();

    // Obtengo el nombre del proceso asociado al id_proceso_pedido
    $procesosModel = $db->table('procesos_pedidos');
    $procesoPedido = $procesosModel->where('id_relacion', $id_proceso)->get()->getRowArray();

    if ($procesoPedido) {
        $idProceso = $procesoPedido['id_proceso'];
        $procesoModel = $db->table('procesos');
        $proceso = $procesoModel->where('id_proceso', $idProceso)->get()->getRowArray();

        if ($proceso) {
            $nom_proceso = $proceso['nombre_proceso'];
        } else {
            $nom_proceso = 'Proceso no encontrado';
        }
    } else {
        $nom_proceso = 'Proceso pedido no encontrado';
    }
    //Si es el mismo proceso que el último fichaje, sumo el valor de "buenas" al último fichaje
    if ($lineaPedido && $lineaPedido['id_proceso_actual'] == $registro['id_proceso_pedido']) {
        $buenas += $lineaPedido['ultimo_fichaje'];
        
    } 
    // Guardo el valor de "buenas" en el último fichaje de la línea de pedido y la id de proceso actual
    $lineaPedidoModel->where('id_lineapedido', $id_lineapedido)
        ->update([
            'ultimo_fichaje' => $buenas,
            'id_proceso_actual' => $id_proceso,
            'proceso' => $nom_proceso
        ]);

    
        if ($action === 'falta_material') {
            $relacionModel->where('id', $idRelacionProcesoUsuario)
                ->update(['estado' => 4]);
    
            // Finalizar explícitamente el proceso pedido aquí:
            $this->finalizarProcesoPedido($idRelacionProcesoUsuario);
            $this->eliminarRestriccion($idRelacionProcesoUsuario);
            $this->ActualizarEstadoLineaPedido($idRelacionProcesoUsuario);
            $this->ActualizarEstadoPedido($idRelacionProcesoUsuario);
    
            return redirect()->to('/selectMaquina');
        }
    
        $estadoActual = 3;
        $relacionModel->where('id', $idRelacionProcesoUsuario)
            ->update(['estado' => $estadoActual]);
    
        if ($action === 'apuntar_terminar') {
            $nuevoEstado = 3;
        } elseif ($action === 'apuntar_continuar') {
            $nuevoEstado = 2;
        } else {
            $nuevoEstado = 1;
        }
    
        $nuevoRegistro = [
            'id_proceso_pedido' => $registro['id_proceso_pedido'],
            'id_usuario' => $registro['id_usuario'],
            'id_linea_pedido' => $registro['id_linea_pedido'],
            'id_pedido' => $registro['id_pedido'],
            'id_maquina' => $registro['id_maquina'],
            'buenas' => $buenas,
            'malas' => $malas,
            'repasadas' => $repasadas,
            'estado' => $nuevoEstado
        ];
    
        $relacionModel->insert($nuevoRegistro);
    
        // Aquí está la clave de tu corrección:
        if ($action === 'apuntar_terminar') {
            $this->finalizarProcesoPedido($idRelacionProcesoUsuario);
            $this->eliminarRestriccion($idRelacionProcesoUsuario);
            $this->ActualizarEstadoLineaPedido($idRelacionProcesoUsuario);
            $this->ActualizarEstadoPedido($idRelacionProcesoUsuario);
        }
    
        $this->eliminarRegistroSiVacio($registro['id_proceso_pedido']);
    
        return redirect()->to('/selectMaquina');
    }
    
    
    private function eliminarRegistroSiVacio($id_proceso_pedido)
    {
        $db = $this->db;
        $builder = $db->table('relacion_proceso_usuario');

        $registro = $builder->where('id_proceso_pedido', $id_proceso_pedido)
            ->where('buenas', 0)
            ->where('malas', 0)
            ->where('repasadas', 0)
            ->get()
            ->getRow();

        if ($registro) {
            $builder->where('id', $registro->id)->delete();
        }
    }


    public function finalizarProcesoPedido($idRelacionProcesoUsuario)
    {
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');
        $registro = $relacionModel->where('id', $idRelacionProcesoUsuario)->get()->getRowArray();

        if (!$registro) {
            return redirect()->to('/error')->with('error', 'Registro no encontrado.');
        }

        $idProcesoPedido = $registro['id_proceso_pedido'];

        $procesosModel = $db->table('procesos_pedidos');
        $procesoPedido = $procesosModel->where('id_relacion', $idProcesoPedido)->get()->getRowArray();

        if (!$procesoPedido) {
            return redirect()->to('/error')->with('error', 'No se encontró el proceso de pedido.');
        }

        $procesosModel->where('id_relacion', $idProcesoPedido)
            ->update(['estado' => 4]);

        $this->eliminarRestriccion($idRelacionProcesoUsuario);

        $this->ActualizarEstadoLineaPedido($idRelacionProcesoUsuario);
        $this->ActualizarEstadoPedido($idRelacionProcesoUsuario);

        return true;
    }

    public function eliminarRestriccion($idRelacionProcesoUsuario)
    {
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');
        $registro = $relacionModel->where('id', $idRelacionProcesoUsuario)->get()->getRowArray();

        if (!$registro) {
            return redirect()->to('/error')->with('error', 'Registro no encontrado.');
        }

        $idProcesoPedido = $registro['id_proceso_pedido'];

        $procesosModel = $db->table('procesos_pedidos');
        $procesoPedido = $procesosModel->where('id_relacion', $idProcesoPedido)->get()->getRowArray();

        if (!$procesoPedido) {
            return redirect()->to('/error')->with('error', 'No se encontró el proceso de pedido.');
        }

        $idRelacion = $procesoPedido['id_proceso'];

        $procesosPedido = $procesosModel->where('id_linea_pedido', $registro['id_linea_pedido'])->get()->getResultArray();

        if (empty($procesosPedido)) {
            return redirect()->to('/error')->with('error', 'No se encontraron registros con el mismo id_linea_pedido.');
        }

        foreach ($procesosPedido as $proceso) {
            if (!empty($proceso['restriccion'])) {
                $restricciones = explode(',', $proceso['restriccion']);
                if (($key = array_search($idRelacion, $restricciones)) !== false) {
                    unset($restricciones[$key]);
                    $nuevasRestricciones = implode(',', $restricciones);
                    $procesosModel->where('id_proceso', $proceso['id_proceso'])
                        ->update(['restriccion' => $nuevasRestricciones]);
                }
            }
        }

        return true;
    }

    public function ActualizarEstadoLineaPedido($idRelacionProcesoUsuario)
    {
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');
        $registro = $relacionModel->where('id', $idRelacionProcesoUsuario)->get()->getRowArray();

        if (!$registro) {
            return redirect()->to('/error')->with('error', 'Registro no encontrado.');
        }

        $idProcesoPedido = $registro['id_proceso_pedido'];

        $procesosModel = $db->table('procesos_pedidos');
        $procesoPedido = $procesosModel->where('id_relacion', $idProcesoPedido)->get()->getRowArray();

        if (!$procesoPedido) {
            return redirect()->to('/error')->with('error', 'No se encontró el proceso de pedido.');
        }

        $idLineaPedido = $procesoPedido['id_linea_pedido'];

        $procesosPedido = $procesosModel->where('id_linea_pedido', $idLineaPedido)->get()->getResultArray();

        if (empty($procesosPedido)) {
            return redirect()->to('/error')->with('error', 'No se encontraron registros con el mismo id_linea_pedido.');
        }

        $todosEnEstado4 = true;
        foreach ($procesosPedido as $proceso) {
            if ($proceso['estado'] != 4) {
                $todosEnEstado4 = false;
                break;
            }
        }

        if ($todosEnEstado4) {
            $lineaPedidoModel = $db->table('linea_pedidos');
            $lineaPedidoModel->where('id_lineapedido', $idLineaPedido)
                ->update(['estado' => 4]);
        }

        return true;
    }


    public function ActualizarEstadoPedido($idRelacionProcesoUsuario)
    {
        $db = $this->db;

        // Obtener el registro de la relación proceso-usuario
        $relacionModel = $db->table('relacion_proceso_usuario');
        $registro = $relacionModel->where('id', $idRelacionProcesoUsuario)->get()->getRowArray();

        if (!$registro) {
            return redirect()->to('/error')->with('error', 'Registro no encontrado.');
        }

        // Obtener el id_pedido desde la línea de pedido
        $lineaPedidoModel = $db->table('linea_pedidos');
        $lineaPedido = $lineaPedidoModel->where('id_lineapedido', $registro['id_linea_pedido'])->get()->getRowArray();

        if (!$lineaPedido) {
            return redirect()->to('/error')->with('error', 'Línea de pedido no encontrada.');
        }

        $idPedido = $lineaPedido['id_pedido'];

        // Obtener todas las líneas de pedido asociadas al id_pedido
        $lineasPedido = $lineaPedidoModel->where('id_pedido', $idPedido)->get()->getResultArray();

        if (empty($lineasPedido)) {
            return redirect()->to('/error')->with('error', 'No se encontraron líneas de pedido asociadas al pedido.');
        }

        // Verificar si todas las líneas están en estado 4
        $todasEnEstado4 = true;
        foreach ($lineasPedido as $linea) {
            if ($linea['estado'] != 4) {
                $todasEnEstado4 = false;
                break;
            }
        }

        // Actualizar el estado del pedido
        $pedidoModel = $db->table('pedidos');
        $nuevoEstado = $todasEnEstado4 ? 4 : 3;
        $pedidoModel->where('id_pedido', $idPedido)->update(['estado' => $nuevoEstado]);

        return true;
    }


}