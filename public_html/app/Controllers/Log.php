<?php
namespace App\Controllers;


use App\Models\Log_model;

class Log extends BaseController
{
    public function index()
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Logs');
        $data['amiga'] = $this->getBreadcrumbs();

        return view('log_view', $data);
    }
    public function getLogs()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $logModel = new Log_model($db);
        $logs = $logModel->findAll();
    
        if ($logs) {
            return $this->response->setJSON($logs);
        } else {
            return $this->response->setJSON(['error' => 'No se encontraron logs.']);
        }
    }
    public function deleteLog($id_log)
    {
        if (empty($id_log)) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID de log no válido.']);
        }
    
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $logModel = new Log_model($db);

        $log = $logModel->find($id_log);
        if ($log) {
            if ($logModel->delete($id_log)) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el log.']);
            }
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Log no encontrado para el ID: ' . $id_log]);
        }
    }
    
    
}
