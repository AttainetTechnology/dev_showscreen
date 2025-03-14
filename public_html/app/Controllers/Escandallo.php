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
        $procesosPedidosModel = new \App\Models\ProcesosPedido($db);

        $procesos = $procesosPedidosModel->where('id_linea_pedido', $id_linea_pedido)->findAll();

        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Partes', base_url('/lista_produccion/todoslospartes'));
        $this->addBreadcrumb('Escandallo', base_url('/escandallo'));
        $data['amiga'] = $this->getBreadcrumbs();

        if ($procesos) {
            $agrupadas = $this->agruparRelaciones($procesos, $db);
            $data['relaciones'] = array_values($agrupadas);
        } else {
            $data['error'] = 'No se encontraron detalles para esta línea de pedido.';
        }

        return view('escandallo', $data);
    }

    private function agruparRelaciones($procesos, $db)
    {
        $agrupadas = [];
        $relacionProcesoUsuarioModel = new RelacionProcesoUsuario_model($db); 

        foreach ($procesos as $proceso) {
            $id_proceso_pedido = $proceso['id_relacion'];
            $relaciones = $relacionProcesoUsuarioModel->where('id_proceso_pedido', $id_proceso_pedido)->findAll();

            if (!isset($agrupadas[$id_proceso_pedido])) {
                $agrupadas[$id_proceso_pedido] = [
                    'id_proceso_pedido' => $id_proceso_pedido,
                    'nombre_maquina' => $this->obtenerNombreMaquina($proceso['id_maquina'], $db),
                    'buenas' => 0,
                    'malas' => 0,
                    'repasadas' => 0,
                    'usuarios' => [],
                    'nombre_proceso' => $this->obtenerNombreProceso($id_proceso_pedido, $db),
                    'estados' => [],
                    'estado' => null,
                    'tiene_restricciones' => $this->tieneRestricciones($id_proceso_pedido, $db),
                    'tiene_relaciones' => !empty($relaciones) 
                ];
            }

            foreach ($relaciones as $relacion) {
                $this->sumarPiezas($agrupadas[$id_proceso_pedido], $relacion);
                $agrupadas[$id_proceso_pedido]['estados'][] = $relacion['estado'];
            }
        }

        foreach ($agrupadas as &$grupo) {
            $grupo['estado'] = $this->calcularEstado($grupo['estados']);
            $grupo['estado'] = $this->convertirEstado($grupo['estado']);
        }

        return $agrupadas;
    }


    private function tieneRestricciones($id_proceso_pedido, $db)
    {
        $builder = $db->table('procesos_pedidos');
        $builder->select('restriccion');
        $builder->where('id_relacion', $id_proceso_pedido);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            $result = $query->getRow();
            return !empty($result->restriccion);
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

        $relaciones = $model->where('id_proceso_pedido', $id_proceso_pedido)
            ->findAll();

        $nombre_proceso = $this->obtenerNombreProceso($id_proceso_pedido, $db);

        $id_linea_pedido = $db->table('procesos_pedidos')->where('id_relacion', $id_proceso_pedido)->get()->getRow()->id_linea_pedido;

        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Partes', base_url('/lista_produccion/todoslospartes'));
        $this->addBreadcrumb('Escandallo', base_url('/escandallo/' . $id_linea_pedido));
        $this->addBreadcrumb('Datos Escandallo', base_url('/escandalloIndividual/' . $id_proceso_pedido));

        $data['amiga'] = $this->getBreadcrumbs();

        if ($relaciones) {
            foreach ($relaciones as &$relacion) {
                $relacion['nombre_usuario'] = $this->obtenerNombreUsuario($relacion['id_usuario'], $db);
                $relacion['estado'] = $this->convertirEstado($relacion['estado']);
            }
            $data['relaciones'] = $relaciones;
            $data['nombre_proceso'] = $nombre_proceso;
            $data['id_linea_pedido'] = $id_linea_pedido;

            return view('escandalloIndividual', $data);
        } else {
            $data['error'] = 'No se encontraron registros para este proceso.';
            return view('escandalloIndividual', $data);
        }
    }
}