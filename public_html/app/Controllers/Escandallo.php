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
            $agrupadas = $this->agruparRelaciones($relaciones);
            $estado = $this->determinarEstado($agrupadas);
            $this->asignarEstado($agrupadas, $estado);

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

    private function agruparRelaciones($relaciones)
    {
        $agrupadas = [];

        foreach ($relaciones as $relacion) {
            $id_proceso_pedido = $relacion['id_proceso_pedido'];
            $id_usuario = $relacion['id_usuario'];

            if (!isset($agrupadas[$id_proceso_pedido])) {
                $agrupadas[$id_proceso_pedido] = [
                    'id_pedido' => $relacion['id_pedido'],
                    'id_proceso_pedido' => $id_proceso_pedido,
                    'buenas' => 0,
                    'malas' => 0,
                    'repasadas' => 0,
                    'usuarios' => []
                ];
            }

            // Sumar las piezas
            $this->sumarPiezas($agrupadas[$id_proceso_pedido], $relacion);

            $this->agregarUsuario($agrupadas[$id_proceso_pedido], $id_usuario);
        }

        return $agrupadas;
    }


    private function sumarPiezas(&$grupo, $relacion)
    {
        $grupo['buenas'] += (int) $relacion['buenas'];
        $grupo['malas'] += (int) $relacion['malas'];
        $grupo['repasadas'] += (int) $relacion['repasadas'];
    }

    private function agregarUsuario(&$grupo, $id_usuario)
    {
        if (!in_array($id_usuario, $grupo['usuarios'])) {
            $grupo['usuarios'][] = $id_usuario;
        }
    }

    // Función para determinar el estado del grupo
    private function determinarEstado($agrupadas)
    {
        $estado = 0;  // Inicializamos el estado a 0

        foreach ($agrupadas as $registro) {
            // Si el estado es 4, lo asignamos inmediatamente
            if (in_array(4, $registro['usuarios'])) {
                return 4;
            }

            // De lo contrario, tomamos el estado más bajo
            foreach ($registro['usuarios'] as $usuario) {
                if ($estado === 0 || $usuario < $estado) {
                    $estado = $usuario;
                }
            }
        }

        return $estado;
    }

    // Función para asignar el estado final a cada grupo
    private function asignarEstado(&$agrupadas, $estado)
    {
        foreach ($agrupadas as $id_proceso_pedido => $registro) {
            $agrupadas[$id_proceso_pedido]['estado'] = $estado;
        }
    }
}
