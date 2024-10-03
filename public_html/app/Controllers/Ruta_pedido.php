<?php

namespace App\Controllers;

use App\Models\Rutas_model; // Modelo para la tabla rutas
use App\Models\Usuarios2_Model;
use App\Models\PoblacionesModel;

class Ruta_pedido extends BaseController
{
    public $npedido = 0;
    public $idcliente;

    public function Rutas($pedido, $id_cliente)
    {
        // Obtener los datos del usuario en sesión
        $data = usuario_sesion();
        // Intentar conectar a la base de datos del cliente
        $db = db_connect($data['new_db']);
        if (!$db) {
            log_message('error', 'No se pudo conectar a la base de datos del cliente');
            return;
        }

        $this->npedido = $pedido;
        $this->idcliente = $id_cliente;

        // Cargar las rutas del pedido desde el modelo
        $rutasModel = new Rutas_model($db);
        try {
            $rutas = $rutasModel->where('id_pedido', $pedido)->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener las rutas: ' . $e->getMessage());
            $rutas = [];
        }

        // Obtener lista de transportistas
        $transportistas = $this->transportistas();
        // Obtener lista de poblaciones
        $poblacionesModel = new PoblacionesModel($db);
        $poblaciones = $poblacionesModel->obtenerPoblaciones();
        foreach ($poblaciones as $poblacion) {
            $poblacionesMap[$poblacion['id_poblacion']] = $poblacion['poblacion'];
        }


        // Pasar los datos a la vista
        return view('RutasModalPedido', [
            'rutas' => $rutas,
            'id_pedido' => $pedido,
            'id_cliente' => $id_cliente,
            'transportistas' => $transportistas,
            'poblacionesMap' => $poblacionesMap,
            'poblaciones' => $poblaciones
        ]);
    }
    public function guardarRuta()
    {
        // Obtener los datos del usuario y conectar a la base de datos del cliente
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);
        
        if (!$db) {
            return redirect()->back()->with('error', 'No se pudo conectar a la base de datos.');
        }
    
        // Validar los datos del formulario
        $validation = \Config\Services::validation();
        $validation->setRules([
            'poblacion' => 'required',
            'lugar' => 'required',
            'recogida_entrega' => 'required',
            'transportista' => 'required',
            'fecha_ruta' => 'required|valid_date',
            'observaciones' => 'permit_empty'
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            // Manejar errores de validación
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
    
        // Obtener los datos enviados por el formulario
        $rutasModel = new Rutas_model($db);
        $result = $rutasModel->insert([
            'poblacion' => $this->request->getPost('poblacion'),
            'lugar' => $this->request->getPost('lugar'),
            'recogida_entrega' => $this->request->getPost('recogida_entrega'),
            'transportista' => $this->request->getPost('transportista'),
            'fecha_ruta' => $this->request->getPost('fecha_ruta'),
            'observaciones' => $this->request->getPost('observaciones'),
            'id_pedido' => $this->request->getPost('id_pedido')  // Añadir el pedido asociado
        ]);
    
        // Verificar si la inserción fue exitosa
        if (!$result) {
            return redirect()->back()->with('error', 'Hubo un error al guardar la ruta.');
        }
    
        // Redirigir de vuelta a la página del pedido con id_pedido e id_cliente
        $id_cliente = $this->request->getPost('id_cliente');
        return redirect()->to('/Ruta_pedido/rutas/' . $this->request->getPost('id_pedido') . '/' . $id_cliente)
                         ->with('success', 'La ruta ha sido añadida correctamente.');
    }
    

    function transportistas()
    {
        // Crea una nueva instancia del modelo Usuarios2_Model
        $datos = new \App\Models\Usuarios2_Model();
        $data = usuario_sesion();
        $id_empresa = $data['id_empresa'];

        // Define los criterios para la consulta a la base de datos
        $array = ['nivel_acceso' => '1', 'id_empresa' => $id_empresa];
        $usuarios = $datos->where($array)->findAll();
        $user_ids = array();

        // Almacena los IDs de los usuarios en el array
        foreach ($usuarios as $usuario) {
            $user_ids[] = $usuario['id'];
        }

        // Verificar si hay IDs antes de hacer la consulta
        if (empty($user_ids)) {
            // Si no hay transportistas, devolver un array vacío
            log_message('info', 'No se encontraron transportistas para la empresa con ID: ' . $id_empresa);
            return [];
        }

        // Conéctate a la base de datos del cliente
        $db_cliente = db_connect($data['new_db']);
        $builder = $db_cliente->table('users');
        $builder->select('id, nombre_usuario, apellidos_usuario');
        $builder->whereIn('id', $user_ids); // Ejecutar solo si hay IDs
        $builder->where('user_activo', '1');
        $query = $builder->get();

        $transportistas = array();

        // Verificar si la consulta fue exitosa y si hay resultados
        if ($query && $query->getNumRows() > 0) {
            foreach ($query->getResult() as $row) {
                $transportistas[$row->id] = $row->nombre_usuario . ' ' . $row->apellidos_usuario;
            }
        } else {
            log_message('info', 'No se encontraron transportistas activos o la consulta no devolvió resultados.');
        }

        return $transportistas;
    }
}
