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

    public function deleteLog($id)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $logModel = new Log_model($db);
        if ($logModel->delete($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar el log.']);
        }
    }
}
