<?php

namespace App\Controllers;

use App\Models\Proceso;
use CodeIgniter\Controller;

class Procesos extends BaseControllerGC
{
    public function index()
    {
        $data = datos_user();
        $db = db_connect($data['new_db']);
        $procesoModel = new Proceso($db);
        $procesos = $procesoModel->where('estado_proceso', 1)->orderBy('nombre_proceso', 'ASC')->findAll();
        // Pasar estado_proceso a la vista para mostrar botón correcto
        return view('procesos', ['procesos' => $procesos, 'estado_proceso' => 1]);
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
            'next_proceso_id' => $next_proceso_id
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
        // Obtener el proceso actual
        $proceso = $procesoModel->find($id);
        // Alternar el estado del proceso
        $nuevo_estado = ($proceso['estado_proceso'] == 1) ? 0 : 1;
        // Actualizar el estado del proceso
        $procesoModel->update($id, ['estado_proceso' => $nuevo_estado]);
        // Si el proceso pasa a inactivo, realizar la eliminación en la tabla procesos_productos
        if ($nuevo_estado == 0) {
            $this->removeProcesoFromProductos($db, $id);
        }
        // Registrar la acción en el log
        $accion = $nuevo_estado == 1 ? 'activado' : 'desactivado';
        $log = "Proceso ID: {$id} ha sido {$accion}";
        $this->logAction('Procesos', $log, $data);
        // Redirigir de vuelta a la vista que estaba antes del cambio
        if ($estado_actual == 1) {
            return redirect()->to(base_url('procesos'));
        } else {
            return redirect()->to(base_url('procesos/inactivos'));
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
