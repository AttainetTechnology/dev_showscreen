<?php

namespace App\Controllers;

use App\Models\ProveedoresModel;
use App\Models\PedidosTercerosModel; 
use App\Models\LineaPedidoModel;
use App\Models\ProductosNecesidadModel;

class pedidos_terceros_listado extends BaseController
{
    protected $idpedido = 0;

    function __construct()
    {
        $this->idpedido = 0;
    }

    public function index()
    {
        helper('controlacceso');
        $redirect = check_access_level();
        $redirectUrl = session()->getFlashdata('redirect');
        if ($redirect && is_string($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }
        $this->todos('estado!=', '6');
    }

    public function pendientesRealizar()
    {
        $this->todos('estado=', '0');
    }

    public function pendientesRecibir()
    {
        $this->todos('estado=', '1');
    }
 
    public function recibidos()
    {
        $this->todos('estado=', '2');
    }
    //CREAMOS LA PAGINA DE PEDIDOS
    public function todos()
    {
        // Agregar breadcrumbs para la página de todos los pedidos
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Pedidos');

        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $builder = $db->table('pedido_terceros');

        $builder->select('*');
        $builder->orderBy('fecha_creacion', 'desc');
        $builder->orderBy('id_ped_terceros', 'desc');

        $pedidos = $builder->get()->getResultArray();

        $data['amiga'] = $this->getBreadcrumbs();

        foreach ($pedidos as &$pedido) {
            $pedido['nombre_proveedor'] = $this->getProveedorNombre($pedido['id_proveedor']);
            $pedido['nombre_usuario'] = $this->getUsuarioNombre($pedido['id_usuario']);
            $pedido['estado_texto'] = $this->getEstadoTexto($pedido['estado']);
            $pedido['acciones'] = [
                'editar' => base_url("pedidos/edit/{$pedido['id_pedido_cliente']}")
            ];
        }

        return view('mostrarPedidosTerceros', [
            'pedidos' => $pedidos,
            'amiga' => $data['amiga']
        ]);
    }

    private function getProveedorNombre($id_proveedor)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $proveedor = $db->table('proveedores')->select('nombre_proveedor')->where('id_proveedor', $id_proveedor)->get()->getRow();
        return $proveedor ? $proveedor->nombre_proveedor : 'Desconocido';
    }
    private function getUsuarioNombre($id_usuario)
    {
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        $usuario = $db->table('users')->select('nombre_usuario')->where('id', $id_usuario)->get()->getRow();
        return $usuario ? $usuario->nombre_usuario : 'Desconocido';
    }
    private function getEstadoTexto($estado)
    {
        $estados = [
            "0" => "Pendiente de realizar",
            "1" => "Pendiente de recibir",
            "2" => "Recibido",
            "6" => "Anulado"
        ];
        return $estados[$estado] ?? 'Desconocido';
    }

   
    /* Funciones de salida - Vistas */
    function _output_sencillo($output = null)
    {
        echo view('sencillo', (array) $output);
    }
}
