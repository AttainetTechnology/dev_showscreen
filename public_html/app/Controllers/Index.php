<?php

namespace App\Controllers;

use App\Models\Config_model;
use App\Models\Contador_model;
use App\Models\Lineapedidosnew_model;
use App\Models\Rutas_model;
use App\Models\Incidencias_model;

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
        /** APARTADO STANDARD PARA TODOS LOS CONTROLADORES **/
        // Control de login    
        helper('controlacceso');
        control_login();

        // Saco los datos del usuario
        $data = datos_user();

        // Conecto la BDD
        $db = db_connect($data['new_db']);

        // Cargamos los módulos de la Home
        // Creo los 4 bloques que aparecen en la parte superior de la index
        $incidenciasModel = new Incidencias_model();
        $data['incidencias'] = $incidenciasModel->getIncidencias();
        $data['pendientes'] = $this->cuenta('0', $db);
        $data['en_cola']    = $this->cuenta('2', $db);
        $data['en_maquina'] = $this->cuenta('3', $db);
        $data['terminados'] = $this->cuenta('4', $db);

        $data['piezasfamilia'] = $this->pedidos_tabla($estado, $db);
        $data['rutas'] = $this->rutas_home($db);

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

        // Cargamos las vistas
        echo view('estadisticas', $data);
    }

    // Esta función cuenta las líneas de una tabla
    function cuenta($estado, $db)
    {
        $contador = new Contador_model($db);
        try {
            $query = $contador->where('estado', $estado)->countAllResults();
            return $query ?: "0";
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
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

    public function rutas_home($db)
    {
        $builder = new Rutas_model($db);
        try {
            $query = $builder
                ->where('rutas.estado_ruta <', '2')  // table name in lowercase
                ->join('poblaciones_rutas', 'poblaciones_rutas.id_poblacion = rutas.poblacion')  // table name in lowercase
                ->orderBy('poblacion', 'DESC')
                ->select('poblaciones_rutas.poblacion, rutas.recogida_entrega')  // table name in lowercase
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
