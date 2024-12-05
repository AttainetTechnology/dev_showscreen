<?php

namespace App\Models;
use CodeIgniter\Model;

class Lineaspedido_model extends Model
{
    protected $table = 'linea_pedido';
    protected $primaryKey = 'id_lineapedido';
    protected $allowedFields = [
        'id_pedido',
        'fecha_salida',
        'fecha_entrega',
        'id_producto',
        'n_piezas',
        'observaciones',
        'id_usuario',
        'unidades',
        'precio_compra',
        'descuento',
        'add_linea',
        'total_linea',
        'estado'
    ];
    public function index() {}

    // Función para obtener las líneas de un pedido concreto 
    public function obtener_lineas_pedido($id_pedido)
    {
        $data = datos_user(); 
        $db = db_connect($data['new_db']);
        
        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table('linea_pedidos');
        $builder->select('*');
        $builder->join('productos', 'productos.id_producto = linea_pedidos.id_producto', 'left');
        $builder->where('id_pedido', $id_pedido);
        $query = $builder->get();

        if (!$query) {
            // Consulta fallida
            throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
        }

        return $query->getResult();
    }

    // Función que obtiene líneas de pedido en un estado concreto
    public function obtener_lineas_estado($estado)
    {
        $data = datos_user(); 
        $db = db_connect($data['new_db']);

        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table('linea_pedidos');
        $builder->select('*');
        $builder->join('productos', 'productos.id_producto = linea_pedidos.id_producto', 'left');
        $builder->join('pedidos', 'pedidos.id_pedido = linea_pedidos.id_pedido', 'left');
        $builder->where('estado', $estado);
        $query = $builder->get();

        if (!$query) {
            // Consulta fallida
            throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
        }

        return $query->getResult();
    }

    // Función que actualiza el estado de líneas de pedido a través de su id lineapedido
    public function actualiza_estado_lineas($id_lineapedido)
    {
        $data = datos_user(); 
        $db = db_connect($data['new_db']);

        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table('linea_pedidos');
        $builder->select('*');
        $builder->where('id_lineapedido', $id_lineapedido);
        $query = $builder->get();

        if (!$query) {
            // Consulta fallida
            throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
        }

        foreach ($query->getResult() as $row) {
            $elpedido = $row->id_pedido;
            $builder = $db->table('linea_pedidos');
            $builder->select('*');
            $builder->where('id_pedido', $elpedido);
            $query2 = $builder->get();

            if (!$query2) {
                // Consulta fallida
                throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
            }

            $estado_menor = '7';
            foreach ($query2->getResult() as $row) {
                $estado_actual = $row->estado;
                if ($estado_actual <= $estado_menor) {
                    $estado_menor = $estado_actual;
                }
            }
        }

        $data2 = ['estado' => $estado_menor];
        $builder = $db->table('pedidos');
        $builder->set($data2);
        $builder->where('id_pedido', $elpedido);
        $builder->update();

        return true;
    }

    // Función que actualiza el estado del pedido en función del estado de sus líneas pero proviene de la id pedido
    public function actualiza_estado_pedido($id_pedido)
    {
        $estado_menor = '7';
        $data = datos_user(); 
        $db = db_connect($data['new_db']);

        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table('linea_pedidos');
        $builder->select('*');
        $builder->where('id_pedido', $id_pedido);
        $query2 = $builder->get();

        if (!$query2) {
            // Consulta fallida
            throw new \Exception('Consulta fallida: ' . $db->getLastQuery() . ' - Error: ' . $db->error());
        }

        foreach ($query2->getResult() as $row) {
            $estado_actual = $row->estado;
            if ($estado_actual <= $estado_menor) {
                $estado_menor = $estado_actual;
            }
        }

        $data2 = ['estado' => $estado_menor];
        $builder = $db->table('pedidos');
        $builder->set($data2);
        $builder->where('id_pedido', $id_pedido);
        $builder->update();

        return true;
    }

    public function entrega_lineas($id_pedido)
    {
        $data = ['estado' => '5'];
        helper('controlacceso');
        $data2 = usuario_sesion();
        $db = db_connect($data2['new_db']);

        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table('linea_pedidos');
        $builder->set($data);
        $builder->where('id_pedido', $id_pedido);
        $builder->update();

        $builder = $db->table('pedidos');
        $builder->set($data);
        $builder->where('id_pedido', $id_pedido);
        return $builder->update();
    }

    public function actualiza_linea($id_lineapedido, $estado)
    {
        $data = ['estado' => $estado];
        helper('controlacceso');
        $data2 = usuario_sesion();
        $db = db_connect($data2['new_db']);

        if (!$db->connID) {
            // Conexión fallida
            throw new \Exception('Conexión a la base de datos fallida: ' . $db->error());
        }

        $builder = $db->table('linea_pedidos');
        $builder->where('id_lineapedido', $id_lineapedido);
        $builder->update($data);
    }

    function imprimir_parte($row)
	{
		if (is_numeric($row)) {
			$url = base_url() . "/partes/print/" . $row;
			return redirect()->to($url);
		} else {
			return redirect()->to(base_url('/error_page'))->with('error', 'Valor inválido recibido.');
		}
	}
    
    public function anular_lineas ($id_pedido)
    {
                    //Si se marca el pedido como anulado se anulan todas las lineas
                    $data = array('estado' => '6');

                    helper('controlacceso');
                    $data2= usuario_sesion(); 
                    $db = db_connect($data2['new_db']);
                    $builder = $db->table('linea_pedidos');
                    $builder->set($data);
                    $builder->where('id_pedido', $id_pedido);
                    $builder->update();

                    $builder = $db->table('pedidos');
                    $builder->set($data);
                    $builder->where('id_pedido', $id_pedido);
                    return $builder->update();
    
    }

}
