<?php
namespace App\Controllers;

use App\Models\RelacionProcesoUsuario_model;

class Escandallo extends BaseController
{
    public function ver($id_linea_pedido)
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new RelacionProcesoUsuario_model($db);
    
        $relaciones = $this->getRelaciones($model, $id_linea_pedido);
    
        // Agregar migas de pan (breadcrumbs)
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Partes', base_url('/lista_produccion/todoslospartes'));
        $this->addBreadcrumb('Escandallo', base_url('/escandallo'));
    
        // Pasar las migas de pan a la vista
        $data['amiga'] = $this->getBreadcrumbs();
    
        if ($relaciones) {
            $agrupadas = $this->agruparRelaciones($relaciones, $db);
    
            // Agregar información sobre restricciones
            foreach ($agrupadas as &$grupo) {
                $grupo['tiene_restricciones'] = $this->tieneRestricciones($grupo['id_proceso_pedido'], $db);
            }
    
            $relacionesAgrupadas = array_values($agrupadas);
            $data['relaciones'] = $relacionesAgrupadas;
        } else {
            $data['error'] = 'No se encontraron detalles para esta línea de pedido.';
        }
    
        return view('escandallo', $data);
    }
    
    private function getRelaciones($model, $id_linea_pedido)
    {
        return $model->where('id_linea_pedido', $id_linea_pedido)->findAll();
    }

    private function agruparRelaciones($relaciones, $db)
    {
        $agrupadas = [];

        foreach ($relaciones as $relacion) {
            $id_proceso_pedido = $relacion['id_proceso_pedido'];
            $id_maquina = $relacion['id_maquina'];
            $estado = $relacion['estado'];

            if (!isset($agrupadas[$id_proceso_pedido])) {
                $agrupadas[$id_proceso_pedido] = [
                    'id_proceso_pedido' => $id_proceso_pedido, // Aseguramos que se incluya en el array
                    'id_pedido' => $relacion['id_pedido'],
                    'nombre_maquina' => $this->obtenerNombreMaquina($id_maquina, $db),
                    'buenas' => 0,
                    'malas' => 0,
                    'repasadas' => 0,
                    'usuarios' => [],
                    'nombre_proceso' => $this->obtenerNombreProceso($id_proceso_pedido, $db),
                    'estados' => [],
                    'estado' => null,
                ];
            }

            $this->sumarPiezas($agrupadas[$id_proceso_pedido], $relacion);
            $agrupadas[$id_proceso_pedido]['estados'][] = $estado;
        }

        foreach ($agrupadas as $key => &$grupo) {
            $grupo['estado'] = $this->calcularEstado($grupo['estados']);
            $grupo['estado'] = $this->convertirEstado($grupo['estado']);
        }
        return $agrupadas;
    }

    private function tieneRestricciones($id_proceso_pedido, $db)
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('procesos_pedidos');
        $builder->select('restriccion');
        $builder->where('id_relacion', $id_proceso_pedido);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return !empty($result->restricciones); 
        }

        return false;
    }

    private function calcularEstado($estados)
    {

        $conteo = array_count_values($estados);

        if (count($conteo) == 1 && isset($conteo[3])) {
            return 3;
        }
        if (isset($conteo[4])) {
            return 4;
        }
        if (isset($conteo[1])) {
            return 1;
        }
        if (isset($conteo[2])) {
            return 2;
        }
        return 0;
    }


    private function convertirEstado($estado)
    {
        switch ($estado) {
            case 1:
                return 'En maquina';
            case 2:
                return 'En espera';
            case 3:
                return 'Terminado';
            case 4:
                return 'Falta de material';

        }
    }

    private function sumarPiezas(&$grupo, $relacion)
    {
        $grupo['buenas'] += (int) $relacion['buenas'];
        $grupo['malas'] += (int) $relacion['malas'];
        $grupo['repasadas'] += (int) $relacion['repasadas'];
    }

    private function obtenerNombreProceso($id_proceso_pedido, $db)
    {

        $builder = $db->table('procesos_pedidos');
        $builder->select('procesos.nombre_proceso');
        $builder->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso', 'inner');
        $builder->where('procesos_pedidos.id_relacion', $id_proceso_pedido);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return $result->nombre_proceso;
        }

        return 'Proceso no encontrado';
    }

    private function obtenerNombreMaquina($id_maquina, $db)
    {

        $builder = $db->table('maquinas');
        $builder->select('nombre');
        $builder->where('id_maquina', $id_maquina);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return $result->nombre;
        }
        return 'Máquina no encontrada';
    }

    private function obtenerNombreUsuario($id_usuario, $db)
    {
        $builder = $db->table('users');
        $builder->select('nombre_usuario');
        $builder->where('id', $id_usuario);

        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return $result->nombre_usuario;
        }
        return 'Usuario no encontrado';
    }
    public function verEscandalloIndividual($id_proceso_pedido)
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $model = new RelacionProcesoUsuario_model($db);

        $relaciones = $model->where('id_proceso_pedido', $id_proceso_pedido)->findAll();

        $nombre_proceso = $this->obtenerNombreProceso($id_proceso_pedido, $db);

        if ($relaciones) {
            foreach ($relaciones as &$relacion) {
                $relacion['nombre_usuario'] = $this->obtenerNombreUsuario($relacion['id_usuario'], $db);

                $relacion['estado'] = $this->convertirEstado($relacion['estado']);
            }
            return view('escandalloIndividual', ['relaciones' => $relaciones, 'nombre_proceso' => $nombre_proceso]);
        } else {
            return view('escandalloIndividual', ['error' => 'No se encontraron registros para este proceso.', 'nombre_proceso' => $nombre_proceso]);
        }
    }



}