<?php

namespace App\Controllers;

use App\Models\Config_model;
use App\Models\Contador_model;
use App\Models\Lineapedidosnew_model;
use App\Models\Rutas_model;
use App\Models\Incidencias_model;
use App\Models\RelacionProcesoUsuario_model;

class Index extends BaseController
{
    public function pendientes()
    {
        $this->index('0');
    }
    public function enmarcha()
    {
        $this->index('2');
    }
    public function enmaquina()
    {
        $this->index('3');
    }
    public function terminados()
    {
        $this->index('4');
    }
    public function index($estado = 2)
    {
        // Control de login
        helper('controlacceso');
        control_login();

        // Saco los datos del usuario
        $data = datos_user();

        // Verificar si el nivel del usuario es 1
        if (isset($data['nivel']) && $data['nivel'] == 1) {
            // Redirigir a la página deseada si el nivel es 1
            return redirect()->to('/rutas_transporte/rutas'); // Cambia "/pagina-deseada" por la URL o ruta correcta
        }

        // Conecto la BDD
        $db = db_connect($data['new_db']);

        // Cargamos los módulos de la Home
        // Creo los 4 bloques que aparecen en la parte superior de la index
        $incidenciasModel = new Incidencias_model();
        $data['incidencias'] = $incidenciasModel->getIncidencias();
        $data['pendientes'] = $this->cuenta('0', $db);
        $data['en_cola'] = $this->cuenta('2', $db);
        $data['en_maquina'] = $this->cuenta('3', $db);
        $data['terminados'] = $this->cuenta('4', $db);

        $data['piezasfamilia'] = $this->pedidos_tabla($estado, $db);
        $data['rutas'] = array_map(function ($ruta) {
    return (array) $ruta;
}, (new \App\Models\Rutas_model($db))->getRutasWithDetails('rutas.estado_ruta <', 2));


        if ($estado == 0) {
            $data['titulo'] = "Piezas en espera de material";
            $data['clase'] = "";
        } elseif ($estado == 2) {
            $data['titulo'] = "Piezas en cola de producción";
            $data['clase'] = "panel-info";
        } elseif ($estado == 3) {
            $data['titulo'] = "Piezas en máquina";
            $data['clase'] = "panel-danger";
        } elseif ($estado == 4) {
            $data['titulo'] = "Piezas terminadas";
            $data['clase'] = "panel-success";
        }

        echo view('estadisticas', $data);
    }
    public function obtenerFaltaMaterial($db)
    {
        $query = $db->table('relacion_proceso_usuario')
            ->select('relacion_proceso_usuario.*, procesos.nombre_proceso, users.nombre_usuario, users.apellidos_usuario, 
                 maquinas.nombre, linea_pedidos.n_piezas')
            ->join('procesos_pedidos', 'procesos_pedidos.id_relacion = relacion_proceso_usuario.id_proceso_pedido')
            ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
            ->join('users', 'users.id = relacion_proceso_usuario.id_usuario')
            ->join('maquinas', 'maquinas.id_maquina = relacion_proceso_usuario.id_maquina')
            ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = relacion_proceso_usuario.id_linea_pedido')
            ->where('relacion_proceso_usuario.estado', 4)
            ->get();

        $resultados = $query->getResultArray();

        foreach ($resultados as &$registro) {
            $sumaBuenas = $this->obtenerSumaBuenas($db, $registro['id_proceso_pedido']);
            $registro['total_buenas'] = $sumaBuenas;
        }

        return $resultados;
    }


    public function obtenerSumaBuenas($db, $idProcesoPedido)
    {
        $query = $db->table('relacion_proceso_usuario')
            ->selectSum('buenas') 
            ->where('id_proceso_pedido', $idProcesoPedido)
            ->get();

        $resultado = $query->getRowArray();
        return $resultado['buenas'] ?? 0;
    }

    public function resetMaterial()
    {
        $id = $this->request->getPost('id');

        if ($id) {
            $data = datos_user();
            $db = db_connect($data['new_db']);

            $db->table('relacion_proceso_usuario')
                ->where('id', $id)
                ->update(['estado' => 2]);

            return redirect()->to('/index')->with('message', 'Material reseteado exitosamente.');
        } else {
            return redirect()->to('/index')->with('message', 'ID no válido.');
        }
    }

    function cuenta($estado, $db)
    {
        $contador = new Contador_model($db);
        try {
            $query = $contador->where('estado', $estado)->countAllResults();
            return $query ?: "0";
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return "0";
        }
    }

    public function pedidos_tabla($estado, $db)
    {
        // Control de login    
        helper('controlacceso');

        // Saco los datos del usuario
        $data = datos_user();

        // Conecto la BDD
        $db = db_connect($data['new_db']);
        $lineapedidos = new Lineapedidosnew_model($db);
        try {
            $query = $lineapedidos
                ->where('estado', $estado)
                ->select('sum(total_linea) as total_euros')
                ->select('sum(n_piezas) as total_piezas')
                ->select('familia_productos.id_familia')  // table name in lowercase
                ->join('productos', 'linea_pedidos.id_producto = productos.id_producto')  // table name in lowercase
                ->join('familia_productos', 'productos.id_familia = familia_productos.id_familia')  // table name in lowercase
                ->select('familia_productos.nombre')  // table name in lowercase
                ->orderby("id_familia", "asc")
                ->groupby('id_familia')
                ->findAll();

            if ($query === false) {
                // Log detailed error message
                log_message('error', 'Query failed: ' . json_encode($db->error()));
                return [];
            }

            return $query;
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            log_message('error', $e->getMessage());
            return [];
        }
    }


    public function incidencias()
    {
        echo "La función incidencias() se ha llamado correctamente."; // Mensaje de depuración
        $incidenciasModel = new Incidencias_model();
        $data['incidencias'] = $incidenciasModel->getIncidencias(); // Obtiene los datos y los guarda en 'incidencias'

        var_dump($data['incidencias']); // Depuración: muestra los datos obtenidos

        return view('estadisticas', $data); // Pasa los datos a la vista 'estadisticas'
    }
}
