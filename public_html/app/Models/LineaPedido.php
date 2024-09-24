<?php

namespace App\Models;

use CodeIgniter\Model;

class LineaPedido extends Model {
    protected $table = 'linea_pedidos';
    protected $primaryKey = 'id_lineapedido';
    protected $allowedFields = [
        'id_producto', 
        'n_piezas', 
        'id_pedido',
        'precio_venta', 
        'nom_base', 
        'med_inicial', 
        'med_final', 
        'lado', 
        'distancia', 
        'estado', 
        'fecha_entrada', 
        'fecha_entrega', 
        'observaciones', 
        'total_linea',
    ];
    public function obtenerLineasPorPedido($estado, $id_pedido = null) {
        $query = $this->where('estado', $estado);
        
        if ($id_pedido !== null) {
            $query = $query->where('id_pedido', $id_pedido);
        }
        
        return $query->orderBy('id_lineapedido', 'asc')->findAll();
    }
}
class Pedido extends Model {
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    protected $allowedFields = [
        'id_cliente', 'referencia', 'observaciones', 'fecha_entrada', 'fecha_entrega',
        'estante', 'id_usuario', 'total_pedido', 'detalles', 'estado', 'pedido_por', 'representante'
    ];

    public function obtenerPedidos() {
        return $this->findAll();
    }
}

class Producto extends Model {
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';

    protected $allowedFields = [
        'nombre_producto', 'id_familia', 'imagen', 'precio', 'unidad', 'estado_producto'
    ];

    public function obtenerProductos() {
        return $this->findAll();
    }
    
}

class Cliente extends Model {
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';

    protected $allowedFields = [
        'nombre_cliente', 'nif', 'direccion', 'pais', 'id_provincia', 'poblacion',
        'telf', 'fax', 'cargaen', 'exportacion', 'f_pago', 'otros_contactos',
        'observaciones_cliente', 'id_contacto', 'email', 'web'
    ];

    public function obtenerClientes() {
        return $this->findAll();
    }
}

class ProcesoProducto extends Model {
    protected $table = 'procesos_productos';
    protected $primaryKey = 'id_relacion';

    protected $allowedFields = ['id_producto', 'id_proceso', 'orden'];

    public function obtenerProcesosProductos() {
        return $this->findAll();
    }
}


