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
    public function editar($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $fichajesModel = new FichajesModel($db);
        $usuariosModel = new Usuarios2_Model($db); // Modelo para obtener los usuarios
    
        $fichaje = $fichajesModel->find($id);
        if (!$fichaje) {
            return $this->response->setJSON(['error' => 'Fichaje no encontrado']);
        }
    
        // Obtener todos los usuarios para el select
        $usuarios = $usuariosModel->findAll();
    
        // Pasar el fichaje y los usuarios a la vista
        return $this->response->setJSON([
            'fichaje' => $fichaje,
            'usuarios' => $usuarios // Lista de usuarios
        ]);
    }
    
    public function actualizar()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $fichajesModel = new FichajesModel($db);
    
        // Recoger los datos del formulario
        $id = $this->request->getPost('id');
        $entrada = $this->request->getPost('entrada');
        $salida = $this->request->getPost('salida');
        $incidencia = $this->request->getPost('incidencia');
        $extras = $this->request->getPost('extras');
        $id_usuario = $this->request->getPost('nombre');
    
        // Verificar si el fichaje existe
        $fichaje = $fichajesModel->find($id);
        if (!$fichaje) {
            return $this->response->setJSON(['error' => 'Fichaje no encontrado']);
        }
    
        // Actualizar los datos en la base de datos
        $fichajesModel->update($id, [
            'entrada' => $entrada,
            'salida' => $salida,
            'incidencia' => $incidencia,
            'extras' => $extras,
            'id_usuario' => $id_usuario
        ]);
    
        return $this->response->setJSON(['success' => true]);
    }
    
     // Acción para eliminar un fichaje
     public function eliminar($id)
     {
         $data = usuario_sesion();
         $db = db_connect($data['new_db']);
         $fichajesModel = new FichajesModel($db);
 
         $fichaje = $fichajesModel->find($id);
         if (!$fichaje) {
             return $this->response->setJSON(['error' => 'Fichaje no encontrado']);
         }
 
         // Eliminar el fichaje
         $fichajesModel->delete($id);
 
         return $this->response->setJSON(['success' => true]);
     }
    
}
