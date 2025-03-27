<?php
namespace App\Controllers;

use App\Models\FichajesModel;
use App\Models\Usuarios2_Model;

class Fichajes extends BaseController
{
    public function index()
    {
        helper('controlacceso');
        $redirect = check_access_level();
        $redirectUrl = session()->getFlashdata('redirect');
        if ($redirect && is_string($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Fichajes');
        $data['amiga'] = $this->getBreadcrumbs();
        return view('fichajes_view', $data);
    }

    private function Pasa_a_Horas($entrada, $salida)
    {

        if (empty($salida) || $salida == '0000-00-00 00:00:00' || $salida == '00:00:00') {
            return '';
        }

        $entrada = new \DateTime($entrada);
        $salida = new \DateTime($salida);

        $intervalo = $entrada->diff($salida);

        $totalMinutos = ($intervalo->days * 24 * 60) + ($intervalo->h * 60) + $intervalo->i;

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

        $fichajes = $fichajesModel->findAll();

        foreach ($fichajes as &$fichaje) {
            $usuario = $usuariosModel->findUserById($fichaje['id_usuario']);
            if ($usuario) {
                $fichaje['nombre_usuario'] = $usuario['nombre_usuario'] . ' ' . $usuario['apellidos_usuario'];
            } else {
                $fichaje['nombre_usuario'] = 'Usuario no encontrado';
            }
            $fichaje['salida'] = ($fichaje['salida'] == '0000-00-00 00:00:00' || empty($fichaje['salida'])) ? '' : $fichaje['salida'];
            $fichaje['total'] = $this->Pasa_a_Horas($fichaje['entrada'], $fichaje['salida']);
            $fichaje['extras'] = ($fichaje['extras'] == 1) ? 'Sí' : 'No';
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
        $usuariosModel = new Usuarios2_Model($db);
        $fichaje = $fichajesModel->find($id);
        if (!$fichaje) {
            return $this->response->setJSON(['error' => 'Fichaje no encontrado']);
        }
        $usuarios = $usuariosModel->findAll();
        return $this->response->setJSON([
            'fichaje' => $fichaje,
            'usuarios' => $usuarios
        ]);
    }

    public function actualizar()
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $fichajesModel = new FichajesModel($db);

        $id = $this->request->getPost('id');
        $entrada = $this->request->getPost('entrada');
        $salida = $this->request->getPost('salida');
        $incidencia = $this->request->getPost('incidencia');
        $extras = $this->request->getPost('extras');
        $id_usuario = $this->request->getPost('nombre');
        $fichaje = $fichajesModel->find($id);
        if (!$fichaje) {
            return $this->response->setJSON(['error' => 'Fichaje no encontrado']);
        }
        $fichajesModel->update($id, [
            'entrada' => $entrada,
            'salida' => $salida,
            'incidencia' => $incidencia,
            'extras' => $extras,
            'id_usuario' => $id_usuario
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function eliminar($id)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $fichajesModel = new FichajesModel($db);

        $fichaje = $fichajesModel->find($id);
        if (!$fichaje) {
            return $this->response->setJSON(['error' => 'Fichaje no encontrado']);
        }

        $fichajesModel->delete($id);

        return $this->response->setJSON(['success' => true]);
    }

}
