<?php

namespace App\Controllers;

use App\Models\LineaPedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\ProcesoProducto;
use App\Models\Proceso;
use App\Models\Maquinas;
use App\Models\ProcesosPedido;
use App\Models\ProcesosProductos;

class Procesos_pedidos extends BaseControllerGC
{
    public function index()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $lineaPedidoModel = new LineaPedido($db);
        $pedidoModel = new Pedido($db);
        $productoModel = new Producto($db);
        $clienteModel = new Cliente($db);
        $procesoProducto = new ProcesoProducto($db);
        $procesoModel = new Proceso($db);
        $maquinasModel = new Maquinas($db);
        $procesosPedidoModel = new ProcesosPedido($db);
        $lineasConEstado2 = $this->obtenerLineasPedidoConEstado2YCrearProcesos();
        // Obtener líneas de pedido con estado = 2
        $lineas = $lineaPedidoModel->whereIn('estado', [2, 3])->findAll();

        // Obtener procesos_pedido con estado = 2
        $procesosPedido = $procesosPedidoModel->where('estado', 2)->findAll();
        $data = [];

        foreach ($lineas as $linea) {
            foreach ($procesosPedido as $procesoPedido) {
                if ($linea['id_lineapedido'] == $procesoPedido['id_linea_pedido']) {

                    $pedido = $pedidoModel->find($linea['id_pedido']);
                    $cliente = $clienteModel->find($pedido['id_cliente']);
                    $producto = $productoModel->find($linea['id_producto']);
                    $proceso = $procesoModel->find($procesoPedido['id_proceso']);

                    $data[] = [
                        'id_linea_pedido' => $linea['id_lineapedido'],
                        'cliente' => $cliente['nombre_cliente'],
                        'fecha' => $linea['fecha_entrega'],
                        'producto' => $producto['nombre_producto'],
                        'n_piezas' => $linea['n_piezas'],
                        'proceso' => $proceso['nombre_proceso'],
                        'medidas' => $linea['med_inicial'] . ' - ' . $linea['med_final'],
                        'base' => $linea['nom_base'],
                        'restriccion' => $procesoPedido['restriccion'] ?? null,
                    ];
                }
            }
        }


        // Obtener líneas con estado = 3 y ordenar por el campo 'id_maquina'
        $lineasEstado3 = $procesosPedidoModel->where('estado', 3)->orderBy('id_maquina', 'ASC')->orderBy('orden', 'ASC')->findAll();
        $dataEstado3 = [];

        foreach ($lineasEstado3 as $lineaEstado3) {
            $lineaPedido = $lineaPedidoModel->find($lineaEstado3['id_linea_pedido']);
            $pedido = $pedidoModel->find($lineaPedido['id_pedido']);
            $cliente = $clienteModel->find($pedido['id_cliente']);
            $producto = $productoModel->find($lineaPedido['id_producto']);
            $proceso = $procesoModel->find($lineaEstado3['id_proceso']);

            $dataEstado3[] = [
                'id_linea_pedido' => $lineaPedido['id_lineapedido'],
                'cliente' => $cliente['nombre_cliente'],
                'fecha' => $lineaPedido['fecha_entrega'],
                'producto' => $producto['nombre_producto'],
                'n_piezas' => $lineaPedido['n_piezas'],
                'proceso' => $proceso['nombre_proceso'],
                'id_maquina' => $lineaEstado3['id_maquina'],
                'medidas' => $lineaPedido['med_inicial'] . ' - ' . $lineaPedido['med_final'],
                'orden' => $lineaEstado3['orden'],
                'base' => $lineaPedido['nom_base'],
                'guardado' => $lineaEstado3['guardado'] ?? 'nuevo',
                'restriccion' => $lineaEstado3['restriccion'] ?? null
            ];
        }

        // Obtener todas las máquinas
        $maquinas = $maquinasModel->findAll();
        $procesos = $procesoModel->findAll();
        $clientes = $clienteModel->findAll();
        $productos = $productoModel->findAll();


        return view('procesos_pedidos', [
            'lineas' => $data,
            'lineasEstado3' => $dataEstado3,
            'maquinas' => $maquinas,
            'procesos' => $procesos,
            'clientes' => $clientes,
            'productos' => $productos,
        ]);
    }

    public function actualizarEstadoProcesos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);
        $procesoModel = new Proceso($db);
        $lineaPedidoModel = new LineaPedido($db);
        $pedidoModel = new Pedido($db);
        $data = $this->request->getJSON(true);

        if (!isset($data['procesos']) || !is_array($data['procesos'])) {
            return $this->response->setJSON(['error' => 'Datos inválidos']);
        }
        foreach ($data['procesos'] as $proceso) {
            if (isset($proceso['nombre_proceso']) && isset($proceso['id_linea_pedido']) && isset($proceso['id_maquina']) && isset($proceso['orden'])) {
                $procesoEncontrado = $procesoModel
                    ->where('nombre_proceso', $this->db->escapeLikeString($proceso['nombre_proceso'])) // Escapando caracteres especiales
                    ->first();
                if ($procesoEncontrado) {
                    $idProceso = $procesoEncontrado['id_proceso'];
                    // Pasar el orden específico
                    $this->calcularYActualizarOrdenParaMaquina($proceso['id_maquina'], $idProceso, $proceso['id_linea_pedido'], $proceso['orden']);

                    $actualizarLineaPedido = true;
                    $procesosDeLaLinea = $procesosPedidoModel
                        ->where('id_linea_pedido', $proceso['id_linea_pedido'])
                        ->findAll();
                    foreach ($procesosDeLaLinea as $procesoPedido) {
                        if ($procesoPedido['estado'] < 3) {
                            $actualizarLineaPedido = false;
                            break;
                        }
                    }
                    if ($actualizarLineaPedido) {
                        $lineaPedidoModel
                            ->where('id_lineapedido', $proceso['id_linea_pedido'])
                            ->set(['estado' => 3])
                            ->update();
                        // Obtener el ID del pedido asociado a la línea de pedido actualizada
                        $idPedido = $lineaPedidoModel->where('id_lineapedido', $proceso['id_linea_pedido'])->first()['id_pedido'];
                        // Actualizar el estado del pedido a 3 sin verificar el estado de todas las líneas
                        $pedidoModel
                            ->where('id_pedido', $idPedido)
                            ->set(['estado' => 3])
                            ->update();
                    }
                }
            }
        }
        return $this->response->setJSON(['success' => 'Estados actualizados correctamente.']);
    }
    private function calcularYActualizarOrdenParaMaquina($idMaquina, $idProceso, $idLineaPedido, $orden)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);

        // Actualizar el proceso con el orden específico y estado 3
        $procesosPedidoModel
            ->where('id_proceso', $idProceso)
            ->where('id_linea_pedido', $idLineaPedido)
            ->where('estado', 2)
            ->set(['estado' => 3, 'id_maquina' => $idMaquina, 'orden' => $orden])
            ->update();
    }
    public function actualizarEstadoLineaPedido()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $lineaPedidoModel = new LineaPedido($db);
        $procesosPedidoModel = new ProcesosPedido($db);
        $pedidoModel = new Pedido($db); // Asumiendo que existe un modelo Pedido para la tabla pedidos

        // Obtener todos los id_linea_pedido únicos de procesos_pedidos
        $procesosPedido = $procesosPedidoModel->select('id_linea_pedido')->distinct()->findAll();

        foreach ($procesosPedido as $procesoPedido) {
            $idLineaPedido = $procesoPedido['id_linea_pedido'];

            // Verificar si existe al menos un proceso en estado 3 para el id_linea_pedido actual
            $existeProcesoEnEstado3 = $procesosPedidoModel
                ->where('id_linea_pedido', $idLineaPedido)
                ->where('estado', 3)
                ->countAllResults() > 0;

            // Si existe al menos un proceso en estado 3, actualizar el estado de la línea de pedido a 3
            if ($existeProcesoEnEstado3) {
                $lineaPedidoModel->update($idLineaPedido, ['estado' => 3]);

                // Obtener el id_pedido asociado a la línea de pedido actualizada
                $idPedido = $lineaPedidoModel->find($idLineaPedido)['id_pedido'];

                // Actualizar el estado del pedido a 3
                $pedidoModel->update($idPedido, ['estado' => 3]);
            }
        }

        return $this->response->setJSON(['success' => 'Estados de línea de pedido y pedidos actualizados correctamente.']);
    }
    public function revertirEstadoProcesos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);
        $procesoModel = new Proceso($db);
        $lineaPedidoModel = new LineaPedido($db);
        $pedidoModel = new Pedido($db);

        $data = $this->request->getJSON(true);

        if (!isset($data['procesos']) || !is_array($data['procesos'])) {
            return $this->response->setJSON(['error' => 'Datos inválidos']);
        }

        $idsPedidoActualizados = [];
        $idsMaquinaAfectadas = []; // Nuevo array para almacenar los IDs de máquinas afectadas
        foreach ($data['procesos'] as $proceso) {
            if (isset($proceso['nombre_proceso']) && isset($proceso['id_linea_pedido'])) {
                $procesoEncontrado = $procesoModel->where('nombre_proceso', $proceso['nombre_proceso'])->first();

                if ($procesoEncontrado) {
                    $idProceso = $procesoEncontrado['id_proceso'];

                    // Antes de actualizar, obtener el id_maquina actual para reordenamiento posterior
                    $procesoActual = $procesosPedidoModel
                        ->where('id_proceso', $idProceso)
                        ->where('id_linea_pedido', $proceso['id_linea_pedido'])
                        ->first();

                    if ($procesoActual && $procesoActual['id_maquina'] !== null) {
                        $idsMaquinaAfectadas[$procesoActual['id_maquina']] = true;
                    }

                    $procesosPedidoModel
                        ->where('id_proceso', $idProceso)
                        ->where('id_linea_pedido', $proceso['id_linea_pedido'])
                        ->set(['estado' => 2, 'id_maquina' => null, 'orden' => 0])
                        ->update();

                    $lineaPedido = $lineaPedidoModel->where('id_lineapedido', $proceso['id_linea_pedido'])->first();
                    if ($lineaPedido && $lineaPedido['estado'] != 2) {
                        $lineaPedidoModel
                            ->where('id_lineapedido', $proceso['id_linea_pedido'])
                            ->set(['estado' => 2])
                            ->update();
                    }

                    $idsPedidoActualizados[$lineaPedido['id_pedido']] = true;
                }
            }
        }

        // Actualizar el estado de los pedidos afectados
        foreach (array_keys($idsPedidoActualizados) as $idPedido) {
            $pedidoModel
                ->where('id_pedido', $idPedido)
                ->set(['estado' => 2])
                ->update();
        }

        // Llamar a reordenarProcesosParaMaquina para cada máquina afectada
        foreach (array_keys($idsMaquinaAfectadas) as $idMaquina) {
            $this->reordenarProcesosParaMaquina($idMaquina); // Asegúrate de que esta función sea accesible
        }

        return $this->response->setJSON(['success' => 'Estados revertidos y procesos reordenados correctamente.']);
    }

    private function reordenarProcesosParaMaquina($idMaquina)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);

        // Paso 1: Obtener todos los procesos para la máquina especificada, ordenados por 'orden'
        $procesos = $procesosPedidoModel
            ->where('id_maquina', $idMaquina)
            ->orderBy('orden', 'asc')
            ->findAll();

        // Paso 2: Reordenar los procesos
        $nuevoOrden = 1;
        foreach ($procesos as $proceso) {
            // Paso 3: Actualizar la base de datos con el nuevo orden
            $procesosPedidoModel
                ->where('id_proceso', $proceso['id_proceso'])
                ->where('id_linea_pedido', $proceso['id_linea_pedido'])
                ->set(['orden' => $nuevoOrden])
                ->update();
            $nuevoOrden++;
        }
    }


    public function marcarTerminado()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);
        $lineaPedidoModel = new LineaPedido($db);
        $pedidoModel = new Pedido($db);
        $procesoModel = new Proceso($db);
        $data = $this->request->getJSON(true);
        if (!isset($data['lineItems']) || !is_array($data['lineItems'])) {
            return $this->response->setJSON(['error' => 'Datos inválidos']);
        }
        $procesosConRestricciones = [];
        foreach ($data['lineItems'] as $item) {
            $idLineaPedido = $item['idLineaPedido'] ?? null;
            $nombreProceso = $item['nombreProceso'] ?? null;
            if (is_null($idLineaPedido) || is_null($nombreProceso)) {
                continue;
            }
            // Limpiar el nombre del proceso: eliminar espacios y emojis
            $nombreProcesoLimpio = trim(preg_replace('/\s+/', ' ', preg_replace('/[^\w\s\+\-\/\(\)]/u', '', $nombreProceso))); // Ajuste para caracteres especiales
            $proceso = $procesoModel->where('nombre_proceso', $nombreProcesoLimpio)->first();
            if (!$proceso) {
                continue;
            }
            $idProceso = $proceso['id_proceso'];
            $procesoInfo = $procesosPedidoModel->where('id_linea_pedido', $idLineaPedido)->where('id_proceso', $idProceso)->first();
            if (is_null($procesoInfo)) {
                continue;
            }
            if (!empty($procesoInfo['restriccion'])) {
                $idsRestricciones = explode(',', $procesoInfo['restriccion']);
                $nombresRestricciones = [];
                foreach ($idsRestricciones as $idRestriccion) {
                    $procesoRestringido = $procesoModel->find($idRestriccion);
                    if ($procesoRestringido) {
                        $nombresRestricciones[] = $procesoRestringido['nombre_proceso'];
                    }
                }
                $procesosConRestricciones[] = [
                    'nombre_proceso' => $nombreProcesoLimpio,
                    'restricciones' => $nombresRestricciones
                ];
                continue;
            }
            $idMaquina = $procesoInfo['id_maquina'] ?? null;
            if (is_null($idMaquina)) {
                continue;
            }
            // Cambiar el estado del proceso a 4
            $procesosPedidoModel
                ->where('id_linea_pedido', $idLineaPedido)
                ->where('id_proceso', $idProceso)
                ->set(['estado' => 4])
                ->update();

            // Reordenar los procesos en la máquina
            $this->reordenarProcesosParaMaquina($idMaquina);
            // Eliminar id_proceso de las restricciones en otros procesos
            $this->eliminarRestricciones($idLineaPedido, $idProceso);
            $todosEnEstado4 = true;
            $procesos = $procesosPedidoModel->where('id_linea_pedido', $idLineaPedido)->findAll();
            foreach ($procesos as $proceso) {
                if ($proceso['estado'] != 4) {
                    $todosEnEstado4 = false;
                    break;
                }
            }
            if ($todosEnEstado4) {
                $lineaPedidoModel
                    ->where('id_lineapedido', $idLineaPedido)
                    ->set(['estado' => 4])
                    ->update();
                $lineaPedido = $lineaPedidoModel->find($idLineaPedido);
                $idPedido = $lineaPedido['id_pedido'] ?? null;
                if (is_null($idPedido)) {
                    continue;
                }
                $todasLineasEnEstado4 = true;
                $lineasPedido = $lineaPedidoModel->where('id_pedido', $idPedido)->findAll();
                foreach ($lineasPedido as $linea) {
                    if ($linea['estado'] < 4) {
                        $todasLineasEnEstado4 = false;
                        break;
                    }
                }
                if ($todasLineasEnEstado4) {
                    $pedidoModel->where('id_pedido', $idPedido)->set(['estado' => 4])->update();
                }
            }
        }

        if (!empty($procesosConRestricciones)) {
            return $this->response->setJSON([
                'error' => 'Uno o más procesos tienen restricciones pendientes.',
                'procesosConRestricciones' => $procesosConRestricciones
            ]);
        }
        return $this->response->setJSON(['success' => 'Estados actualizados y líneas eliminadas']);
    }

    private function eliminarRestricciones($idLineaPedido, $idProcesoTerminado)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);

        // Obtener todos los procesos que pertenecen al mismo id_linea_pedido
        $procesosRelacionados = $procesosPedidoModel
            ->where('id_linea_pedido', $idLineaPedido)
            ->findAll();

        foreach ($procesosRelacionados as $proceso) {
            $restricciones = $proceso['restriccion'] ? explode(',', $proceso['restriccion']) : [];

            // Verificar si el idProcesoTerminado está en la lista de restricciones
            if (in_array($idProcesoTerminado, $restricciones)) {
                // Filtrar el idProcesoTerminado fuera de la lista de restricciones
                $nuevasRestricciones = array_filter($restricciones, function ($value) use ($idProcesoTerminado) {
                    return $value != $idProcesoTerminado;
                });

                // Actualizar la restricción en la base de datos
                $procesosPedidoModel
                    ->where('id_proceso', $proceso['id_proceso'])
                    ->where('id_linea_pedido', $proceso['id_linea_pedido'])
                    ->set(['restriccion' => implode(',', $nuevasRestricciones)])
                    ->update();
            }
        }
    }

    public function actualizarOrdenProcesos()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);

        $data = $this->request->getJSON(true);

        if (!isset($data['ordenes']) || !is_array($data['ordenes'])) {
            return $this->response->setJSON(['error' => 'Datos inválidos']);
        }

        $db->transStart(); // Inicia una transacción

        $procesosActualizados = [];
        $procesosNoEncontrados = [];

        try {
            foreach ($data['ordenes'] as $orden) {
                if (isset($orden['id_linea_pedido'], $orden['nombre_proceso'], $orden['orden'], $orden['id_maquina'])) {
                    // Buscar el proceso específico dentro de la id_maquina dada
                    $proceso = $procesosPedidoModel
                        ->where('id_linea_pedido', $orden['id_linea_pedido'])
                        ->where('id_maquina', $orden['id_maquina'])
                        ->whereIn('id_proceso', function ($builder) use ($orden) {
                            return $builder->select('id_proceso')
                                ->from('procesos')
                                ->where('nombre_proceso', $orden['nombre_proceso']);
                        })
                        ->first();

                    if ($proceso) {
                        // Actualizar el orden del proceso específico
                        $procesosPedidoModel
                            ->where('id_linea_pedido', $orden['id_linea_pedido'])
                            ->where('id_maquina', $orden['id_maquina'])
                            ->where('id_proceso', $proceso['id_proceso'])
                            ->set(['orden' => $orden['orden']])
                            ->update();

                        $procesosActualizados[] = [
                            'id_linea_pedido' => $orden['id_linea_pedido'],
                            'nombre_proceso' => $orden['nombre_proceso'],
                            'orden' => $orden['orden'],
                            'id_maquina' => $orden['id_maquina']
                        ];
                    } else {
                        $procesosNoEncontrados[] = [
                            'id_linea_pedido' => $orden['id_linea_pedido'],
                            'nombre_proceso' => $orden['nombre_proceso'],
                            'orden' => $orden['orden'],
                            'id_maquina' => $orden['id_maquina']
                        ];
                    }
                } else {
                    throw new \Exception('Datos del orden incompletos o inválidos.');
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error al actualizar el orden de los procesos.');
            }

            return $this->response->setJSON([
                'success' => 'Orden actualizado correctamente.',
                'procesos_actualizados' => $procesosActualizados,
                'procesos_no_encontrados' => $procesosNoEncontrados
            ]);
        } catch (\Exception $e) {
            $db->transRollback(); // Revierte la transacción en caso de error
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }


    function mostrarVista()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $modeloCliente = new Cliente($db);
        $clientes = $modeloCliente->obtenerClientes();

        // Pasar la lista de clientes a la vista
        return view('procesos_pedidos', ['clientes' => $clientes]);
    }
    public function obtenerLineasPedidoConEstado2YCrearProcesos()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $lineaPedidoModel = new LineaPedido($db);
        $procesosPedidoModel = new ProcesosPedido($db);
        $procesosProductosModel = new ProcesosProductos($db);

        // Obtener líneas de pedido con estado = 2
        $lineasConEstado2 = $lineaPedidoModel->where('estado', 2)->findAll();

        foreach ($lineasConEstado2 as $linea) {
            $idProducto = $linea['id_producto'];

            // Obtener todos los procesos asociados a este producto
            $procesosProductos = $procesosProductosModel->where('id_producto', $idProducto)->findAll();

            foreach ($procesosProductos as $procesoProducto) {
                // Comprobar si ya existe una línea con este id_proceso y id_linea_pedido
                $existe = $procesosPedidoModel->where([
                    'id_proceso' => $procesoProducto['id_proceso'],
                    'id_linea_pedido' => $linea['id_lineapedido']
                ])->first();

                $dataUpdate = [
                    'restriccion' => $procesoProducto['restriccion'] ?? null
                ];


                if (!$existe) {
                    $dataInsert = [
                        'id_proceso' => $procesoProducto['id_proceso'],
                        'id_linea_pedido' => $linea['id_lineapedido'],
                        'id_maquina' => null,
                        'estado' => 2,
                        'restriccion' => $procesoProducto['restriccion'],
                    ];

                    $procesosPedidoModel->insert($dataInsert);
                } else {
                    // Actualizar las restricciones si no coinciden
                    if (!isset($existe['restriccion']) || $existe['restriccion'] !== $procesoProducto['restriccion']) {
                        $procesosPedidoModel->update($existe['id_relacion'], $dataUpdate);
                    }
                }
            }
        }

        return $lineasConEstado2;
    }
    //Procesos estado 4, manejo:
    public function getProcesosEstado4()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);
        $procesos = $procesosPedidoModel
            ->select('procesos_pedidos.id_relacion, procesos_pedidos.id_linea_pedido, procesos_pedidos.id_proceso, procesos.nombre_proceso, productos.nombre_producto')
            ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
            ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
            ->join('productos', 'productos.id_producto = linea_pedidos.id_producto')
            ->orderBy('procesos_pedidos.id_relacion', 'DESC')
            ->limit(15)
            ->where('procesos_pedidos.estado', 4)
            ->findAll();

        return $this->response->setJSON($procesos);
    }

    public function actualizarEstadoYEliminarRestricciones($idRelacion = null)
    {
        if (is_null($idRelacion)) {
            return $this->response->setJSON(['error' => 'El idRelacion es requerido.']);
        }

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $procesosPedidoModel = new ProcesosPedido($db);
        $procesoModel = new Proceso($db);
        $lineaPedidoModel = new LineaPedido($db);
        $procesosProductosModel = new ProcesosProductos($db);

        // Actualizar el proceso a estado 2 y eliminar los campos orden e id_maquina
        $this->actualizarEstadoProceso($procesosPedidoModel, $idRelacion);

        // Obtener los datos relevantes del proceso y línea de pedido
        $procesoActual = $procesosPedidoModel->find($idRelacion);
        if (!$procesoActual) {
            return $this->response->setJSON(['error' => 'No se encontró el proceso asociado.']);
        }

        $idLineaPedido = $procesoActual['id_linea_pedido'];
        $idProcesoActual = $procesoActual['id_proceso'];
        $idProducto = $lineaPedidoModel->find($idLineaPedido)['id_producto'];

        // Asegurarse de que la línea de pedido esté en estado 3
        $this->actualizarEstadoLinea($lineaPedidoModel, $idLineaPedido);

        // Registrar en el log la actualización del estado de la línea de pedido
        $this->logAction('Organizador', "Proceso con ID $idLineaPedido fue actualizado a estado 3.", $data);

        // Gestionar las restricciones basadas en procesos en estado 2 o 3
        $this->gestionarRestricciones($procesosPedidoModel, $procesosProductosModel, $idLineaPedido, $idProducto, $idProcesoActual, $idRelacion);

        // Eliminar y actualizar restricciones en procesos relacionados
        $this->actualizarRestriccionesEnProcesosRelacionados($procesosPedidoModel, $procesoModel, $idLineaPedido, $idProcesoActual);

        return $this->response->setJSON(['success' => 'Estado actualizado y restricciones aplicadas correctamente.']);
    }

    private function actualizarEstadoProceso($procesosPedidoModel, $idRelacion)
    {
        $procesosPedidoModel->update($idRelacion, [
            'estado' => 2,
            'orden' => null,
            'id_maquina' => null
        ]);
    }

    private function actualizarEstadoLinea($lineaPedidoModel, $idLineaPedido)
    {
        $lineaPedido = $lineaPedidoModel->find($idLineaPedido);
        if ($lineaPedido && $lineaPedido['estado'] != 3) {
            $lineaPedidoModel->update($idLineaPedido, ['estado' => 3]);
        }
    }

    private function gestionarRestricciones($procesosPedidoModel, $procesosProductosModel, $idLineaPedido, $idProducto, $idProcesoActual, $idRelacion)
    {
        $procesosRelacionados = $procesosPedidoModel
            ->where('id_linea_pedido', $idLineaPedido)
            ->whereIn('estado', [2, 3])
            ->findAll();

        $nuevasRestricciones = [];
        foreach ($procesosRelacionados as $proceso) {
            $idProcesoRelacionado = $proceso['id_proceso'];

            $restriccionProducto = $procesosProductosModel
                ->where('id_proceso', $idProcesoActual)
                ->where('id_producto', $idProducto)
                ->first();

            if ($restriccionProducto && !empty($restriccionProducto['restriccion'])) {
                $restriccionesArray = explode(',', $restriccionProducto['restriccion']);
                if (in_array($idProcesoRelacionado, $restriccionesArray)) {
                    $nuevasRestricciones[] = $idProcesoRelacionado;
                }
            }
        }

        if (!empty($nuevasRestricciones)) {
            $procesosPedidoModel->update($idRelacion, [
                'restriccion' => implode(',', $nuevasRestricciones)
            ]);
        }
    }

    private function actualizarRestriccionesEnProcesosRelacionados($procesosPedidoModel, $procesoModel, $idLineaPedido, $idProcesoActual)
    {
        $procesosRelacionados = $procesosPedidoModel->where('id_linea_pedido', $idLineaPedido)->findAll();

        foreach ($procesosRelacionados as $proceso) {
            $this->eliminarRestriccionProceso($procesosPedidoModel, $proceso, $idProcesoActual, $idLineaPedido);
            $this->agregarRestriccionProceso($procesosPedidoModel, $procesoModel, $proceso, $idProcesoActual, $idLineaPedido);
        }
    }

    private function eliminarRestriccionProceso($procesosPedidoModel, $proceso, $idProcesoActual, $idLineaPedido)
    {
        if (!empty($proceso['restriccion'])) {
            $restricciones = explode(',', $proceso['restriccion']);
            if (in_array($idProcesoActual, $restricciones)) {
                $nuevasRestricciones = array_filter($restricciones, function ($value) use ($idProcesoActual) {
                    return $value != $idProcesoActual;
                });

                $procesosPedidoModel
                    ->where('id_proceso', $proceso['id_proceso'])
                    ->where('id_linea_pedido', $idLineaPedido)
                    ->set(['restriccion' => implode(',', $nuevasRestricciones) ?: null])
                    ->update();
            }
        }
    }

    private function agregarRestriccionProceso($procesosPedidoModel, $procesoModel, $proceso, $idProcesoActual, $idLineaPedido)
    {
        if ($proceso['estado'] < 4 && $proceso['id_proceso'] != $idProcesoActual) {
            $restriccionesConfig = $procesoModel->where('id_proceso', $proceso['id_proceso'])->first();

            if ($restriccionesConfig) {
                $restriccionesConfigArray = explode(',', $restriccionesConfig['restriccion']);
                if (in_array($idProcesoActual, $restriccionesConfigArray)) {
                    $restricciones = $proceso['restriccion'] ? explode(',', $proceso['restriccion']) : [];
                    if (!in_array($idProcesoActual, $restricciones)) {
                        $restricciones[] = $idProcesoActual;
                        $procesosPedidoModel
                            ->where('id_proceso', $proceso['id_proceso'])
                            ->where('id_linea_pedido', $idLineaPedido)
                            ->set(['restriccion' => implode(',', $restricciones)])
                            ->update();
                    }
                }
            }
        }
    }
}
