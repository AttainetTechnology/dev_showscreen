<?php
namespace App\Controllers;

use App\Models\PedidosTerceros_model;

class Pedidos_terceros extends BaseController
{
    private function getDb()
    {
        $sessionData = usuario_sesion();
        $db = db_connect($sessionData['new_db']);
        if (!$db) {
            log_message('error', 'No se pudo conectar a la base de datos del cliente');
            $this->response->setJSON(['error' => 'Error al conectar a la base de datos'])->send();
            exit;
        }
        return $db;
    }
public function listado($id_pedido)
{
    $db = $this->getDb();
    $builder = $db->table('pedido_terceros');
    $builder->select('pedido_terceros.*, proveedores.nombre_proveedor, productos_proveedor.ref_producto');
    $builder->join('proveedores', 'proveedores.id_proveedor = pedido_terceros.id_proveedor', 'left');
    $builder->join('productos_proveedor', 'productos_proveedor.id = pedido_terceros.id_producto', 'left');
    $builder->where('pedido_terceros.id_pedido_cliente', $id_pedido);
    $pedidos = $builder->get()->getResultArray();
    return $this->response->setJSON($pedidos);
}
public function mostrarFormularioAdd($id_pedido)
{
    $db = $this->getDb();
    // Obtén los proveedores de la base de datos
    $proveedores = $db->table('proveedores')->select('id_proveedor, nombre_proveedor')->get()->getResultArray();
    // Pasa el array a la vista
    return view('pedido_terceros_add', [
        'id_pedido' => $id_pedido,
        'proveedores' => $proveedores
    ]);
}

public function mostrarFormularioEditar($id_ped_terceros)
{
    $db = $this->getDb();
    $model = new PedidosTerceros_model($db);
    $pedido = $model->find($id_ped_terceros);

    $proveedores = $db->table('proveedores')->select('id_proveedor, nombre_proveedor')->get()->getResultArray();

    // Obtener productos del proveedor actual
    $productos_actuales = [];
    if ($pedido && $pedido['id_proveedor']) {
        $productos_actuales = $db->table('productos_proveedor')
            ->select('id, ref_producto')
            ->where('id_proveedor', $pedido['id_proveedor'])
            ->get()
            ->getResultArray();
    }

    return view('pedido_terceros_edit', [
        'id_ped_terceros' => $id_ped_terceros,
        'pedido' => $pedido,
        'proveedores' => $proveedores,
        'productos_actuales' => $productos_actuales
    ]);
}
public function guardar()
{
    $db = $this->getDb();
    $model = new PedidosTerceros_model($db);



    // Recoge los datos del formulario de edición
    $id_ped_terceros = $this->request->getPost('id_ped_terceros');
    $data = [
        'id_ped_terceros' => $id_ped_terceros, 
        'id_proveedor'  => $this->request->getPost('id_proveedor'),
        'id_producto'   => $this->request->getPost('id_producto'),
        'cantidad'      => $this->request->getPost('cantidad'),
        'observaciones' => $this->request->getPost('observaciones'),
        'estado' => $this->request->getPost('estado'),
        'fecha_creacion' => $this->request->getPost('fecha_creacion'),
        'fecha_recepcion' => $this->request->getPost('fecha_recepcion')
    ];

    // Actualiza el registro donde id_ped_terceros
    if (!$model->update($id_ped_terceros, $data)) {
        return $this->response->setJSON(['success' => false, 'errors' => $model->errors()]);
    }
    return $this->response->setJSON(['success' => true]);
}
    public function productosPorProveedor($id_proveedor)
    {
        $db = $this->getDb();
        $productos = $db->table('productos_proveedor')
            ->select('id, ref_producto')
            ->where('id_proveedor', $id_proveedor)
            ->get()
            ->getResultArray();
        return $this->response->setJSON($productos);
    }
    public function creaPedidoTerceros()
    {
        $data = usuario_sesion();
		$id_user = $data['id_user'];

        $db = $this->getDb();
        $model = new \App\Models\PedidosTerceros_model($db);

        $data = [
            'id_pedido_cliente' => $this->request->getPost('id_ped_terceros'),
            'id_proveedor'      => $this->request->getPost('id_proveedor'),
            'id_producto'       => $this->request->getPost('id_producto'),
            'cantidad'          => $this->request->getPost('cantidad'),
            'observaciones'     => $this->request->getPost('observaciones'),
            'fecha_creacion'    => date('Y-m-d'),
            'id_usuario'        => $id_user,
        ];

        $model->save($data);

        return $this->response->setJSON(['success' => true]);
    }
    public function borrar($id_ped_terceros)
    {
        $db = $this->getDb();
        $model = new \App\Models\PedidosTerceros_model($db);
        $model->delete($id_ped_terceros);
        return $this->response->setJSON(['success' => true]);
    }
    public function marcarRecibido($id_ped_terceros)
    {
        $db = $this->getDb();
        $model = new PedidosTerceros_model($db);
        $model->update($id_ped_terceros, [
            'estado' => 2,
            'fecha_recepcion' => date('Y-m-d')
        ]);
        return $this->response->setJSON(['success' => true]);
    }
    public function marcarEnviado($id_ped_terceros)
    {
        $db = $this->getDb();
        $model = new \App\Models\PedidosTerceros_model($db);

        // Cambia el estado a 1 (enviado)
        $result = $model->update($id_ped_terceros, ['estado' => 1]);

        if ($result) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No se pudo marcar como enviado']);
        }
    }
}
