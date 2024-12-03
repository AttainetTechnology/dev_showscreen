<?php
namespace App\Controllers;

use App\Models\FichajesModel;
use App\Models\Usuarios2_Model;

class Fichajes extends BaseController
{
    public function index()
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Fichajes');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('fichajes_view', $data);
    }

    // Esta es la función que convierte la diferencia de horas
    private function Pasa_a_Horas($entrada, $salida)
    {
        // Si la salida es '0' o vacía, retornar una cadena vacía
        if (empty($salida) || $salida == '0000-00-00 00:00:00' || $salida == '00:00:00') {
            return ''; // Devolver vacío en lugar de calcular la diferencia
        }

        // Convertir las fechas y horas a objetos DateTime
        $entrada = new \DateTime($entrada);
        $salida = new \DateTime($salida);

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
    public function getFichajes()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $fichajesModel = new FichajesModel($db);
        $usuariosModel = new Usuarios2_Model($db);
    
        // Obtener todos los fichajes
        $fichajes = $fichajesModel->findAll();
    
        // Reemplazar el id_user por el nombre y apellidos y calcular el total
        foreach ($fichajes as &$fichaje) {
            $usuario = $usuariosModel->findUserById($fichaje['id_usuario']);
            if ($usuario) {
                // Concatenar nombre y apellidos del usuario
                $fichaje['nombre_usuario'] = $usuario['nombre_usuario'] . ' ' . $usuario['apellidos_usuario'];
            } else {
                // Si no se encuentra el usuario, asignar un valor por defecto
                $fichaje['nombre_usuario'] = 'Usuario no encontrado';
            }
    
            // Calcular el total en horas usando la función Pasa_a_Horas
            $fichaje['salida'] = ($fichaje['salida'] == '0000-00-00 00:00:00' || empty($fichaje['salida'])) ? '' : $fichaje['salida'];
            $fichaje['total'] = $this->Pasa_a_Horas($fichaje['entrada'], $fichaje['salida']);
    
            // Convertir el valor de extras de 0/1 a No/Sí
            $fichaje['extras'] = ($fichaje['extras'] == 1) ? 'Sí' : 'No';
    
            // Agregar las acciones (editar, eliminar)
            $fichaje['acciones'] = [
                'editar' => base_url('fichajes/editar/' . $fichaje['id']),
                'eliminar' => base_url('fichajes/eliminar/' . $fichaje['id'])
            ];
        }
    
        return $this->response->setJSON($fichajes);
    }
    
}
