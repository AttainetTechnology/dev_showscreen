<?php

namespace App\Controllers;

use App\Models\Proceso;
use CodeIgniter\Controller;

class Procesos extends BaseController
{
    public function index()
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Procesos');
        
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $procesos = $procesoModel->where('estado_proceso', 1)->orderBy('nombre_proceso', 'ASC')->findAll();
    
        // Pasar las migas de pan a la vista
        return view('procesos', [
            'procesos' => $procesos,
            'estado_proceso' => 1,
            'amiga' => $this->getBreadcrumbs()
        ]);
    }
    
    public function getProcesos($estado = null)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);

        // Ordenar por estado primero (activos primero) y luego por nombre
        if ($estado !== null) {
            $procesos = $procesoModel
                ->where('estado_proceso', $estado)
                ->orderBy('nombre_proceso', 'ASC')
                ->findAll();
        } else {
            $procesos = $procesoModel
                ->orderBy('estado_proceso', 'DESC') // Activos (1) primero
                ->orderBy('nombre_proceso', 'ASC') // Orden alfabético dentro de cada grupo
                ->findAll();
        }

        // Añadir acciones para cada proceso
        foreach ($procesos as &$proceso) {
            $proceso['acciones'] = [
                'editar' => base_url('procesos/restriccion/' . $proceso['id_proceso']),
                'cambiar_estado' => base_url('procesos/cambiaEstado/' . $proceso['id_proceso'] . '/' . $proceso['estado_proceso']),
            ];
        }

        return $this->response->setJSON($procesos);
    }



    public function add()
    {
        return view('add_procesos');
    }
    public function create()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $nombre_proceso = $this->request->getPost('nombre_proceso');
        $nombre_proceso = strtoupper($nombre_proceso);
        $estado_proceso = $this->request->getPost('estado_proceso');
        // Insertar el nuevo proceso si la validación es correcta
        $procesoModel->insert([
            'nombre_proceso' => $nombre_proceso,
            'estado_proceso' => $estado_proceso,
            'restriccion' => null
        ]);
        // Registrar la acción en el log
        $id_proceso = $procesoModel->insertID(); // Obtener el ID del nuevo proceso
        $log = "Nuevo proceso añadido: {$nombre_proceso} con ID: {$id_proceso}";
        $this->logAction('Procesos', $log, $data);
        return redirect()->to(base_url('procesos'));
    }
    public function restriccion($primaryKey)
    {
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Procesos', base_url('procesos'));
        $this->addBreadcrumb('Editar Proceso');
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $procesos = $procesoModel->where('id_proceso !=', $primaryKey)->orderBy('nombre_proceso', 'ASC')->findAll();
        $proceso_principal = $procesoModel->find($primaryKey);
        // Filtrar solo los procesos activos (estado_proceso = 1)
        $procesos = $procesoModel->where('id_proceso !=', $primaryKey)
            ->where('estado_proceso', 1)
            ->orderBy('nombre_proceso', 'ASC')
            ->findAll();

        $proceso_principal = $procesoModel->find($primaryKey);
        // Verificar que estado_proceso esté bien definido
        if ($proceso_principal['estado_proceso'] === null) {
            $proceso_principal['estado_proceso'] = '1';
        }
        $previous_proceso_id = $this->getPreviousProceso($primaryKey);
        $next_proceso_id = $this->getNextProceso($primaryKey);
        if ($this->request->is('post')) {
            $nombre_proceso = strtoupper($this->request->getPost('nombre_proceso'));
            $estado_proceso = $this->request->getPost('estado_proceso') ?? '1';
            $restricciones = $this->request->getPost('restricciones');
            $redirect_url = $this->request->getPost('redirect_url');
            $restricciones_string = $restricciones ? implode(',', $restricciones) : '';
            $procesoModel->update($primaryKey, [
                'nombre_proceso' => $nombre_proceso,
                'estado_proceso' => $estado_proceso,
                'restriccion' => $restricciones_string
            ]);
            $log = "Actualización de restriccion del proceso ID: {$primaryKey}";
            $this->logAction('Procesos', $log, $data);
            $this->updateOrderAfterRestrictionChange($primaryKey, $restricciones_string);
            return redirect()->to($redirect_url);
        }
        $data = [
            'proceso_principal' => $proceso_principal,
            'procesos' => $procesos,
            'primaryKey' => $primaryKey,
            'previous_proceso_id' => $previous_proceso_id,
            'next_proceso_id' => $next_proceso_id,
            'amiga' => $this->getBreadcrumbs()
        ];
        return view('edit_procesos', $data);
    }
    private function updateOrderAfterRestrictionChange($id_proceso, $restricciones)
    {
        $data = datos_user();
        $dbClient = db_connect($data['new_db']);
        // Obtiene los productos asociados al proceso modificado
        $productos = $dbClient->table('procesos_productos')->where('id_proceso', $id_proceso)->get()->getResultArray();
        foreach ($productos as $producto) {
            $id_producto = $producto['id_producto'];
            $this->updateOrderLogic($dbClient, $id_producto);
        }
    }
    private function updateOrderLogic($dbClient, $id_producto)
    {
        $newOrder = $dbClient->table('procesos_productos')->where('id_producto', $id_producto)->orderBy('orden', 'ASC')->get()->getResultArray();
        $dbClient->table('procesos_productos')->where('id_producto', $id_producto)->delete();
        foreach ($newOrder as $item) {
            $id_proceso = $item['id_proceso'];
            $orden = $item['orden'];
            $proceso = $dbClient->table('procesos')->where('id_proceso', $id_proceso)->get()->getRow();
            $restricciones = $proceso->restriccion;
            if (!empty($restricciones)) {
                $restriccionesArray = explode(',', $restricciones);
                $builder = $dbClient->table('procesos_productos');
                $builder->select('id_proceso');
                $builder->where('id_producto', $id_producto);
                $query = $builder->get();
                $procesosProducto = $query->getResultArray();
                $procesosProductoIds = array_column($procesosProducto, 'id_proceso');
                $restriccionesFiltradas = array_intersect($restriccionesArray, $procesosProductoIds);
                $restriccionesFiltradasString = implode(',', $restriccionesFiltradas);
            } else {
                $restriccionesFiltradasString = '';
            }
            $dbClient->table('procesos_productos')->insert([
                'id_producto' => $id_producto,
                'id_proceso' => $id_proceso,
                'orden' => $orden,
                'restriccion' => $restriccionesFiltradasString
            ]);
        }
    }
    public function cambiaEstado($id, $estado_actual)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);

        try {
            $proceso = $procesoModel->find($id);
            $nuevo_estado = ($proceso['estado_proceso'] == 1) ? 0 : 1;
            $procesoModel->update($id, ['estado_proceso' => $nuevo_estado]);
            if ($nuevo_estado == 0) {
                $this->removeProcesoFromProductos($db, $id);
            }
            $accion = $nuevo_estado == 1 ? 'activado' : 'desactivado';

            return $this->response->setJSON(['success' => true, 'message' => "Proceso {$accion} correctamente."]);
        } catch (\Exception $e) {
            // Manejar errores y devolver un JSON
            return $this->response->setJSON(['success' => false, 'message' => 'Error al cambiar el estado del proceso.', 'error' => $e->getMessage()]);
        }
    }

    //Controla la desactivacion de procesos asociados a productos
    private function removeProcesoFromProductos($db, $id_proceso)
    {
        // Obtener todos los productos asociados a este proceso
        $productos = $db->table('procesos_productos')->where('id_proceso', $id_proceso)->get()->getResultArray();
        foreach ($productos as $producto) {
            $id_producto = $producto['id_producto'];
            $orden = $producto['orden'];
            // Eliminar el registro correspondiente en procesos_productos
            $db->table('procesos_productos')->where('id_proceso', $id_proceso)->delete();
            // Reordenar los procesos restantes para el producto
            $this->reordenarProcesos($db, $id_producto, $orden);
        }
    }
    private function reordenarProcesos($db, $id_producto, $orden_eliminado)
    {
        // Obtener los procesos restantes para el producto
        $procesos = $db->table('procesos_productos')
            ->where('id_producto', $id_producto)
            ->orderBy('orden', 'ASC')
            ->get()->getResultArray();
        // Eliminar todos los registros actuales del producto para evitar duplicados
        $db->table('procesos_productos')->where('id_producto', $id_producto)->delete();
        // Reinsertar los procesos con el nuevo orden ajustado
        foreach ($procesos as $proceso) {
            // Obtener las restricciones del proceso actual
            $proceso_data = $db->table('procesos')->where('id_proceso', $proceso['id_proceso'])->get()->getRow();
            $restricciones = $proceso_data->restriccion;
            // Si hay restricciones, filtrar para incluir solo procesos asociados al mismo producto
            if (!empty($restricciones)) {
                $restriccionesArray = explode(',', $restricciones);

                $builder = $db->table('procesos_productos');
                $builder->select('id_proceso');
                $builder->where('id_producto', $id_producto);
                $query = $builder->get();
                $procesosProducto = $query->getResultArray();

                $procesosProductoIds = array_column($procesosProducto, 'id_proceso');
                $restriccionesFiltradas = array_intersect($restriccionesArray, $procesosProductoIds);

                // Convertir las restricciones filtradas de nuevo a string
                $restriccionesFiltradasString = implode(',', $restriccionesFiltradas);
            } else {
                $restriccionesFiltradasString = '';
            }
            // Ajustar el orden del proceso
            $nuevo_orden = $proceso['orden'];
            if ($proceso['orden'] > $orden_eliminado) {
                $nuevo_orden--; // Decrementar el orden para llenar el hueco
            }
            // Insertar el proceso en la tabla procesos_productos con el orden ajustado y restricciones filtradas
            $db->table('procesos_productos')->insert([
                'id_producto' => $id_producto,
                'id_proceso' => $proceso['id_proceso'],
                'orden' => $nuevo_orden,
                'restriccion' => $restriccionesFiltradasString
            ]);
        }
    }
    public function getPreviousProceso($id)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $proceso = $procesoModel->where('id_proceso <', $id)->orderBy('id_proceso', 'DESC')->first();
        if ($proceso) {
            return $proceso['id_proceso'];
        } else {
            return $id;
        }
    }
    public function getNextProceso($id)
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $proceso = $procesoModel->where('id_proceso >', $id)->orderBy('id_proceso', 'ASC')->first();
        if ($proceso) {
            return $proceso['id_proceso'];
        } else {
            return $id;
        }
    }
    public function inactivos()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $procesos = $procesoModel->where('estado_proceso', 0)->orderBy('nombre_proceso', 'ASC')->findAll();
        // Pasar estado_proceso a la vista para mostrar botón correcto
        return view('procesos', ['procesos' => $procesos, 'estado_proceso' => 0]);
    }
}
