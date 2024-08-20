<?php

namespace App\Controllers;
// use App\Libraries\GroceryCrud;

class Fichajes extends BaseControllerGC
{

    public function index()
    {
        //Control de login	
        helper('controlacceso');
        $nivel = control_login();
        //Fin Control de Login
        $this->SacaFichajes(
            'Fichaje',
            'Fichajes',
            '',
            ''
        );
    }
    public function incidencias()
    {
        $this->SacaFichajes(
            'Incidencia',
            'Incidencias',
            'fichajes.incidencia NOT LIKE ?',
            ''
        );
    }

    public function extras()
    {
        $this->SacaFichajes(
            'Extra',
            'Extras',
            'fichajes.extras',
            '1'
        );
    }
    public function SacaFichajes($titulo, $titulos, $comparador, $valor)
    {

        $crud = $this->_getClientDatabase();

        $crud->setSubject($titulo, $titulos);
        $crud->setTable('fichajes');
        $crud->columns(['id_usuario', 'entrada', 'salida', 'total', 'incidencia', 'extras']);
        $this->groceryCRUDAddExtraColumn($crud, 'fecha');
        if ($comparador != '') {
            $crud->where([
                $comparador => $valor
            ]);
        }
        $crud->setRelation('id_usuario', 'users', 'nombre_usuario');
        $crud->displayAs('total', 'Horas');
        $crud->displayAs('id_usuario', 'Nombre');
        //$crud->displayAs('id','Fecha');
        $crud->fieldType('extras', 'dropdown', [
            '1' => 'Sí',
            '0' => ' '
        ]);
        $crud->fieldType('incidencia', 'dropdown', [
            ' '    => ' ',
            'No 8 horas' => 'Menos de 8H',
            'sin cerrar' => 'Sin cerrar',
            'Ausencia' => 'Ausencia'
        ]);

        $crud->callbackColumn('total', array($this, 'Pasa_a_Horas'));
        $crud->unsetRead();
        $crud->unsetSearchColumns(['entrada', 'salida', 'total']);
        $crud->setLangString('modal_save', 'Guardar Fichaje');
        $output = $crud->render();

        if ($output->isJSONResponse) {
            header('Content-Type: application/json; charset=utf-8');
            echo $output->output;
            exit;
        }
        //Paso las fechas de búsqueda
        // $output->data['inicio'] = "01/01/2022";
        // $output->data['fin'] = "02/01/2022";

        echo view('layouts/main', (array)$output);
    }

    function groceryCRUDAddExtraColumn($crud, $columnName)
    {
        $crud->fieldTypeColumn($columnName, 'varchar');
        $crud->mapColumn($columnName, 'id');
    }


    function Pasa_a_Horas($value, $row)
    {
        // Verifica que los valores de entrada y salida estén definidos
        if (!isset($row->entrada) || !isset($row->salida)) {
            return 'Datos insuficientes';
        }

        // Convertir las fechas y horas a objetos DateTime
        $entrada = new \DateTime($row->entrada);
        $salida = new \DateTime($row->salida);

        // Calcular la diferencia
        $intervalo = $entrada->diff($salida);

        // Convertir la diferencia a minutos totales
        $totalMinutos = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;

        // Calcular días, horas y minutos
        $dias = intval($totalMinutos / (24 * 60));
        $totalMinutos = $totalMinutos % (24 * 60);
        $totalhoras = intval($totalMinutos / 60);
        $minutos = $totalMinutos % 60;

        $resultado = '';
        if ($dias > 0) {
            $resultado .= $dias . ' días ';
        }
        if ($totalhoras > 0) {
            $resultado .= $totalhoras . ' horas ';
        }
        if ($minutos > 0) {
            $resultado .= $minutos . ' minutos';
        }

        return trim($resultado);
    }
}
