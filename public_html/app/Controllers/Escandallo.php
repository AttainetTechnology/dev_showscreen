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

        if ($relaciones) {
            $agrupadas = $this->agruparRelaciones($relaciones, $db);  // Pasamos el db aquí

            $relacionesAgrupadas = array_values($agrupadas);

            return view('escandallo', ['relaciones' => $relacionesAgrupadas]);
        } else {
            return view('escandallo', ['error' => 'No se encontraron detalles para esta línea de pedido.']);
        }
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
            $id_usuario = $relacion['id_usuario'];
            $id_maquina = $relacion['id_maquina'];

            if (!isset($agrupadas[$id_proceso_pedido])) {
                $agrupadas[$id_proceso_pedido] = [
                    'id_pedido' => $relacion['id_pedido'],
                    'nombre_maquina' => $this->obtenerNombreMaquina($id_maquina, $db),
                    'buenas' => 0,
                    'malas' => 0,
                    'repasadas' => 0,
                    'usuarios' => [],
                    'nombre_proceso' => $this->obtenerNombreProceso($id_proceso_pedido, $db), // Obtener el nombre del proceso
                ];
            }

            // Sumar las piezas
            $this->sumarPiezas($agrupadas[$id_proceso_pedido], $relacion);

        }

        return $agrupadas;
    }

    private function sumarPiezas(&$grupo, $relacion)
    {
        $grupo['buenas'] += (int) $relacion['buenas'];
        $grupo['malas'] += (int) $relacion['malas'];
        $grupo['repasadas'] += (int) $relacion['repasadas'];
    }

    private function obtenerNombreProceso($id_proceso_pedido, $db)
    {
        // Realizamos la consulta para obtener el nombre del proceso
        $builder = $db->table('procesos_pedidos');
        $builder->select('procesos.nombre_proceso');
        $builder->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso', 'inner');
        $builder->where('procesos_pedidos.id_relacion', $id_proceso_pedido);

        // Ejecutamos la consulta y obtenemos el resultado
        $query = $builder->get();

        // Verificamos si la consulta devolvió un resultado
        if ($query->getNumRows() > 0) {
            // Si hay resultados, devolvemos el nombre del proceso
            $result = $query->getRow();  // Obtenemos la primera fila
            return $result->nombre_proceso;
        }

        // Si no se encontraron resultados, devolvemos un valor por defecto o un mensaje de error
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

}