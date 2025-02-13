<?php

namespace App\Controllers;

use App\Models\Maquinas;
use App\Models\ProcesosPedido;



class SeleccionMaquina extends BaseFichar
{

    public function getMaquina()
    {
        helper('controlacceso');
        $data = usuario_sesion();
        $db = db_connect($data['new_db']);

        // Cargar el modelo de Maquinas
        $maquinasModel = new Maquinas($db);

        // Obtener todas las máquinas
        $maquinas = $maquinasModel->findAll();

        // Retornamos la vista con las máquinas
        return view('selectMaquina', [
            'maquinas' => $maquinas
        ]);
    }

    public function selectMaquina()
    {
        // Verificamos si se ha seleccionado una máquina
        $idMaquina = $this->request->getPost('id_maquina');

        if ($idMaquina) {
            // Modelo para procesos pedidos
            helper('controlacceso');
            $data = usuario_sesion();
            $db = db_connect($data['new_db']);

            // Cargar el modelo de ProcesosPedido
            $procesosPedidoModel = new ProcesosPedido($db);

            // Traemos los procesos relacionados con la máquina seleccionada y con estado menor a 4
            $procesos = $procesosPedidoModel
                ->where('procesos_pedidos.id_maquina', $idMaquina)  // Asegúrate de especificar la tabla
                ->where('procesos_pedidos.estado <', 4)  // Especifica la tabla para 'estado'
                ->join('procesos', 'procesos.id_proceso = procesos_pedidos.id_proceso')
                ->join('linea_pedidos', 'linea_pedidos.id_lineapedido = procesos_pedidos.id_linea_pedido')
                ->select('procesos_pedidos.*, procesos.nombre_proceso, linea_pedidos.id_producto, linea_pedidos.n_piezas, linea_pedidos.nom_base, linea_pedidos.med_final, linea_pedidos.med_inicial')
                ->findAll();


            // Cargamos el modelo de Maquinas y obtenemos todas las máquinas
            $maquinasModel = new Maquinas($db);
            $maquinas = $maquinasModel->findAll();  // Ejecutamos findAll() para obtener los datos de las máquinas
            $maquinaSeleccionada = $maquinasModel->find($idMaquina);

            // Mostrar la vista con los procesos encontrados
            return view('selectMaquina', [
                'maquinas' => $maquinas,  // Pasamos las máquinas correctamente
                'procesos' => $procesos,
                'nombreMaquinaSeleccionada' => $maquinaSeleccionada['nombre']
            ]);
        }

        // Si no se seleccionó máquina, redirigir de nuevo
        return redirect()->to('selectMaquina');
    }


}

