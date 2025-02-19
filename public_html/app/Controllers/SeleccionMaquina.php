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
                ->where('procesos_pedidos.estado <', 4) // Procesos en estado < 4
                ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
                ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
                ->join('relacion_proceso_usuario', 'relacion_proceso_usuario.id_proceso_pedido = procesos_pedidos.id_relacion', 'left') // Usamos LEFT JOIN para incluir los procesos sin relación
                ->groupStart()  // Empezamos un grupo para aplicar condiciones OR
                ->where('relacion_proceso_usuario.estado', 2) // Procesos en estado 2 en la relación
                ->orWhere('relacion_proceso_usuario.id IS NULL') // O aquellos procesos que no tienen relación (no están en la tabla)
                ->groupEnd()  // Terminamos el grupo
                ->select('procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.observaciones, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial, linea_pedidos.id_pedido')  // Traemos el id_pedido
                ->findAll();

            // Obtener las máquinas disponibles
            $maquinasModel = new Maquinas($db);
            $maquinas = $maquinasModel->findAll();
            $maquinaSeleccionada = $maquinasModel->find($idMaquina);

            // Añadir más datos a los procesos
            foreach ($procesos as &$proceso) {
                $producto = $this->obtenerProducto($proceso['id_producto']);
                $proceso['nombre_producto'] = $producto['nombre'];
                $proceso['imagen_producto'] = $producto['imagen'];

                $nombreCliente = $this->obtenerNombreClientePorPedido($proceso['id_pedido']);
                $proceso['nombre_cliente'] = $nombreCliente;
            }

            // Renderizar la vista con los datos de los procesos y las máquinas
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
            'estado' => '1',
            'buenas' => 0,
            'malas' => 0,
            'repasadas' => 0
        ];

        // Insertar en la base de datos
        $db = $this->db;
        $builder = $db->table('relacion_proceso_usuario');
        $builder->insert($data);

        // Obtener el ID del registro recién insertado
        $nuevo_id = $db->insertID();

        // Actualizar todos los registros con el mismo 'id_proceso_pedido', excluyendo el recién insertado
        $builder = $db->table('relacion_proceso_usuario');
        $builder->where('id_proceso_pedido', $id_proceso_pedido)
            ->where('id !=', $nuevo_id) // Excluir el nuevo registro recién insertado
            ->update(['estado' => 3]); // Cambiar el estado a 3

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
            ->where('relacion_proceso_usuario.estado', 1)  // Filtrar por estado = 1
            ->select('relacion_proceso_usuario.id, procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.observaciones, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial, linea_pedidos.id_pedido') // Selección del 'id' de la tabla 'relacion_proceso_usuario'
            ->get()
            ->getResultArray();

        // Asegúrate de obtener los nombres de los productos y los clientes
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
            return redirect()->to('/error')->with('error', 'Proceso no encontrado o estado inválido.');
        }

        // Obtener nombre del producto y cliente si no se han cargado
        $proceso['nombre_producto'] = $this->obtenerProducto($proceso['id_producto'])['nombre'];
        $proceso['imagen_producto'] = $this->obtenerProducto($proceso['id_producto'])['imagen'];
        $proceso['nombre_cliente'] = $this->obtenerNombreClientePorPedido($proceso['id_pedido']);

        // Llamar a mostrarPiezas para obtener las piezas asociadas a este proceso
        $unidadesIndividuales = $this->mostrarPiezas($id);

        // Llamar a mostrarTotales para obtener las piezas totales (sumadas) por id_proceso_pedido
        $totales = $this->mostrarTotales($proceso['id_proceso_pedido']);

        // Devolver todo junto a la vista
        return view('editarProcesoUser', [
            'proceso' => $proceso,
            'unidadesIndividuales' => $unidadesIndividuales,
            'totales' => $totales
        ]);
    }


    public function mostrarPiezas($idRelacionProcesoUsuario)
    {
        $db = $this->db;

        // Recuperar las unidades individuales (buenas, malas, repasadas) y el id de la tabla relacion_proceso_usuario
        $unidadesIndividuales = $db->table('relacion_proceso_usuario')
            ->select('id, buenas, malas, repasadas')  // Ahora seleccionamos también el 'id'
            ->where('id', $idRelacionProcesoUsuario)  // Filtramos por el id que llega por URL
            ->get()
            ->getRowArray();  // Devuelve un solo registro

        if (!$unidadesIndividuales) {
            // Si no se encuentra el registro, redirigimos a una página de error
            return redirect()->to('/error')->with('error', 'No se encontraron las unidades para este proceso.');
        }

        return $unidadesIndividuales;  // Devolver las unidades buenas, malas y repasadas junto con el id
    }


    public function mostrarTotales($idProcesoPedido)
    {
        $db = $this->db;

        // Recuperar las sumas de las unidades buenas, malas y repasadas para todos los registros con el mismo id_proceso_pedido
        $totales = $db->table('relacion_proceso_usuario')
            ->selectSum('buenas', 'total_buenas')
            ->selectSum('malas', 'total_malas')
            ->selectSum('repasadas', 'total_repasadas')
            ->where('id_proceso_pedido', $idProcesoPedido)  // Filtramos por el id_proceso_pedido
            ->get()
            ->getRowArray();  // Devuelve un solo registro con las sumas

        if (!$totales) {
            // Si no se encuentran registros, podemos devolver totales en 0
            return [
                'total_buenas' => 0,
                'total_malas' => 0,
                'total_repasadas' => 0
            ];
        }

        return $totales;  // Devolver las sumas totales
    }

    public function editarPiezas()
    {
        // Recibimos los datos del formulario
        $buenas = $this->request->getPost('buenas');
        $malas = $this->request->getPost('malas');
        $repasadas = $this->request->getPost('repasadas');
        $action = $this->request->getPost('action');  // Obtener el valor del botón presionado
    
        // Asegurarnos de que los datos son válidos
        if ($buenas < 0 || $malas < 0 || $repasadas < 0) {
            return redirect()->to('/error')->with('error', 'Los valores no pueden ser negativos.');
        }
    
        // Obtener el ID de la relación_proceso_usuario desde el formulario
        $idRelacionProcesoUsuario = $this->request->getPost('id_relacion_proceso_usuario');
    
        // Obtener los valores actuales de las piezas desde la base de datos
        $db = $this->db;
        $relacionModel = $db->table('relacion_proceso_usuario');
        $registro = $relacionModel->where('id', $idRelacionProcesoUsuario)->get()->getRowArray();
    
        if (!$registro) {
            return redirect()->to('/error')->with('error', 'Registro no encontrado.');
        }
    
        // Sumar los valores nuevos a los existentes
        $nuevasBuenas = $registro['buenas'] + $buenas;
        $nuevasMalas = $registro['malas'] + $malas;
        $nuevasRepasadas = $registro['repasadas'] + $repasadas;
    
        // Determinar el estado según el botón presionado
        $estado = 1; // Estado normal (guardar)
        if ($action === 'apuntar_terminar') {
            $estado = 3;  // Terminar pedido
        } elseif ($action === 'guardar_continuar') {
            $estado = 2;  // Continuar más tarde
        }
    
        // Actualizar los valores sumados y el estado en la base de datos
        $relacionModel->where('id', $idRelacionProcesoUsuario)
            ->update([
                'buenas' => $nuevasBuenas,
                'malas' => $nuevasMalas,
                'repasadas' => $nuevasRepasadas,
                'estado' => $estado  // Actualizamos el estado según el botón
            ]);
    
        // Redirigir a la vista del proceso con los nuevos valores
        return redirect()->to('/editarProceso/' . $idRelacionProcesoUsuario);
    }
    

}
