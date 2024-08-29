<?php

namespace App\Controllers;

use App\Models\Informe_model;
use App\Models\Usuarios2_Model;
use App\Models\Fichajes;
use App\Models\Vacaciones_model;
use App\Models\Festivos;
use App\Models\Laborables_model;

class Informe_detalle extends BaseController
{
    // Función para generar el rango de fechas
    private function generarRangoFechas($inicio, $fin) {
        $inicio = new \DateTime($inicio);
        $fin = new \DateTime($fin);
        $fin->modify('+1 day'); // incluir la fecha final
        $intervalo = new \DateInterval('P1D');
        $periodo = new \DatePeriod($inicio, $intervalo, $fin);

        $rango = [];
        foreach ($periodo as $fecha) {
            $rango[] = $fecha->format("d/m/Y"); 
        }
        return $rango;
    }

    public function index($id_informe)
    {
        // Control de login    
        helper('controlacceso');
        $nivel = control_login();
        // Fin Control de Login
        
        // Saco los datos del usuario
        $data = datos_user();
        
        // Conecto la BDD
        $db = db_connect($data['new_db']);
        
        // Seleccionamos el informe
        $model = new Informe_model($db);
        $informe = $model->find($id_informe);
        if ($informe) {
            $desde_informe = $informe['desde'];
            $hasta_informe = $informe['hasta'];
            $extras_informe = $informe['extras'];
            $ausencias_informe = $informe['ausencias'];
            $vacaciones_informe = $informe['vacaciones'];
            $incidencias_informe = $informe['incidencias'];
            $data = [
                'titulo_informe' => $informe['titulo'],
                'desde_informe' => $informe['desde'],
                'hasta_informe' => $informe['hasta'],
                'extras_informe' => $informe['extras'],
                'ausencias_informe' => $informe['ausencias'],
                'vacaciones_informe' => $informe['vacaciones'],
                'incidencias_informe' => $informe['incidencias']
            ];
        }
        
        if ($vacaciones_informe == 1) {
            // Saco todos los festivos en el periodo y los paso a la vista
            $festivos_model = new Festivos($db);
            $festivos = $festivos_model
                ->where('fecha>=', $desde_informe)
                ->where('fecha<=', $hasta_informe)
                ->findAll();
            if ($festivos) {
                $data['festivos'] = $festivos;
            } else {
                $data['festivos'] = "";
            }
            // Saco los laborables y los paso a la vista
            $laborables_model = new Laborables_model($db);
            $laborables = $laborables_model->findAll();
            if ($laborables) {
                $data['laborables'] = $laborables;
            }
        }

        // Seleccionamos los usuarios
        $user = new Usuarios2_Model($db);
        $usuario = $user->where('user_ficha', 1)
                        ->where('user_activo', 1)
                        ->findAll();
        if ($usuario) {
            $data['usuarios'] = $usuario;
            // De cada usuario seleccionamos sus fichajes dentro del periodo
            foreach ($usuario as $row) {
                $id_user = $row['id'];
                if ($extras_informe == 1) {
                    // EXTRAS
                    $fichajes = new Fichajes($db);
                    $fichaje = $fichajes
                        ->where('id_usuario', $id_user)
                        ->where('entrada>', $desde_informe)
                        ->where('entrada<', $hasta_informe)
                        ->where('extras', '1')
                        ->findAll();
                    if ($fichaje) {
                        $data['fichajes'][$id_user] = $fichaje;
                    }
                    // Calculo el total de horas del user
                    $total = new Fichajes($db);
                    $total_li = $total
                        ->where('id_usuario', $id_user)
                        ->where('entrada>', $desde_informe)
                        ->where('entrada<', $hasta_informe)
                        ->where('extras', '1')
                        ->selectSum('total')
                        ->findAll();
                    if ($total_li) {
                        $data['total_linea'][$id_user] = $total_li;
                    }
                }

                if ($incidencias_informe == 1) {
                    //INCIDENCIAS
                  //Menos de 8 horas o mas de 8h y media
            if ($incidencias_informe == 1) {
                $incidencias_model = new Fichajes($db);
                $incidencia = $incidencias_model
                   ->select('*') // Selecciona todos los campos; ajustar según necesidad
                   ->select("TIMESTAMPDIFF(MINUTE, entrada, COALESCE(salida, NOW())) as duracion") // Calcula la duración en minutos
                   ->Where('id_usuario', $id_user)
                   ->Where('entrada>', $desde_informe)
                   ->Where('entrada<', $hasta_informe)
                   ->GroupStart() // Inicia agrupación para condiciones OR
                      ->orWhere('incidencia', 'Menos de 8H') 
                      ->orWhere('incidencia', 'sin cerrar')
                      // Añade condiciones para duración menor a 8 horas (480 minutos) o mayor a 8 horas y 30 minutos (510 minutos)
                      ->orWhere("TIMESTAMPDIFF(MINUTE, entrada, COALESCE(salida, NOW())) < 480", null, false)
                      ->orWhere("TIMESTAMPDIFF(MINUTE, entrada, COALESCE(salida, NOW())) > 510", null, false)
                   ->groupEnd() // Cierra agrupación
                   ->findAll();
                   if ($incidencia){
                      $data['incid'][$id_user] = $incidencia;
                   }
            }//CIERRE INCIDENCIAS
            
                 }
 
                 if ($vacaciones_informe == 1) {
                    // VACACIONES
                    $vacacion = new Vacaciones_model($db);
                    $vacacions = $vacacion
                        ->where('user_id', $id_user)
                        ->groupStart()
                            ->orGroupStart()
                                ->where('desde>=', $desde_informe)
                                ->where('desde<=', $hasta_informe)
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('hasta>=', $desde_informe)
                                ->where('hasta<=', $hasta_informe)
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('desde<=', $desde_informe)
                                ->where('hasta>=', $hasta_informe)
                            ->groupEnd()
                        ->groupEnd()
                        ->findAll();
                    if ($vacacions) {
                        $data['vacas'][$id_user] = $vacacions;
                    }
                }

                if ($ausencias_informe == 1) {
                    // AUSENCIAS
                    $ausencias = new Fichajes($db);
                    // Consulta las ausencias del usuario dentro del rango de fechas del informe
                    $ausencia = $ausencias
                        ->where('id_usuario', $id_user)
                        ->groupStart() // Inicia agrupación de condiciones
                            ->orGroupStart()
                                ->where('entrada>=', $desde_informe) // ENTRADAS
                                ->where('entrada<=', $hasta_informe)
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('salida>=', $desde_informe) // SALIDAS
                                ->where('salida<=', $hasta_informe)
                            ->groupEnd()
                            ->orGroupStart()
                                ->where('entrada<=', $desde_informe) // COMPARA
                                ->where('salida>=', $hasta_informe)
                            ->groupEnd()
                        ->groupEnd() // Cierra agrupación de condiciones
                        ->where('entrada <', date('Y-m-d')) // Añadir esta línea para asegurarse de que no se incluyen futuras entradas
                        ->findAll(); // Ejecuta la consulta y devuelve los resultados
                    
                    // Obtener los días laborables
                    $laborables_model = new Laborables_model($db);
                    $laborables = $laborables_model->findAll();
                    $workingDays = [];
                    foreach ($laborables as $lab) {
                        if ($lab['lunes'] != 0) $workingDays[] = 1;
                        if ($lab['martes'] != 0) $workingDays[] = 2;
                        if ($lab['miercoles'] != 0) $workingDays[] = 3;
                        if ($lab['jueves'] != 0) $workingDays[] = 4;
                        if ($lab['viernes'] != 0) $workingDays[] = 5;
                        if ($lab['sabado'] != 0) $workingDays[] = 6;
                        if ($lab['domingo'] != 0) $workingDays[] = 7;
                    }
                
                    // Generar el rango de fechas del informe
                    $rangoFechasInforme = $this->generarRangoFechas($desde_informe, $hasta_informe);
                
                    // Obtener las fechas con fichajes
                    $fichajes = $ausencias
                        ->select('entrada')
                        ->where('id_usuario', $id_user)
                        ->where('entrada>=', $desde_informe)
                        ->where('entrada<=', $hasta_informe)
                        ->where('entrada <', date('Y-m-d')) // Añadir esta línea para asegurarse de que no se incluyen futuras entradas
                        ->findAll();
                    
                    $diasConRegistro = [];
                    foreach ($fichajes as $f) {
                        $entrada = new \DateTime($f['entrada']);
                        $diasConRegistro[] = $entrada->format("d/m/Y"); 
                    }
                
                    // Calcular los días sin fichajes y filtrar los días no laborables
                    $diasSinAusencia = array_diff($rangoFechasInforme, $diasConRegistro);
                    $diasSinAusenciaLaborables = [];
                    $hoy = new \DateTime();
                    $hoy->setTime(0, 0);
                    foreach ($diasSinAusencia as $dia) {
                        $date = \DateTime::createFromFormat('d/m/Y', $dia);
                        if ($date && $date < $hoy && in_array($date->format('N'), $workingDays)) { // Filtrar solo los días anteriores a hoy
                            $diasSinAusenciaLaborables[] = $dia;
                        }
                    }
                
                    // Filtrar los días sin fichajes que no están en la tabla de vacaciones
                    $vacaciones = new Vacaciones_model($db);
                    foreach ($diasSinAusenciaLaborables as $key => $dia) {
                        $date = \DateTime::createFromFormat('d/m/Y', $dia);
                        if ($date) {
                            $fecha = $date->format('Y-m-d');
                            $vacacion = $vacaciones
                                ->where('user_id', $id_user)
                                ->where('desde <=', $fecha)
                                ->where('hasta >=', $fecha)
                                ->first();
                            if ($vacacion) {
                                unset($diasSinAusenciaLaborables[$key]); // Si está en vacaciones, eliminar de las ausencias
                            }
                        }
                    }
                
                    // Filtrar los días sin fichajes que no están en la tabla de festivos
                    $festivos = new Festivos($db);
                    foreach ($diasSinAusenciaLaborables as $key => $dia) {
                        $date = \DateTime::createFromFormat('d/m/Y', $dia);
                        if ($date) {
                            $fecha = $date->format('Y-m-d');
                            $festivo = $festivos
                                ->where('fecha', $fecha)
                                ->first();
                            if ($festivo) {
                                unset($diasSinAusenciaLaborables[$key]); // Si es festivo, eliminar de las ausencias
                            }
                        }
                    }
                
                    // Si se encontraron ausencias, las añade al array de datos
                    if (!empty($diasSinAusenciaLaborables)) {
                        $data['ausencias'][$id_user] = $diasSinAusenciaLaborables;
                    } else {
                        $data['ausencias'][$id_user] = []; // Inicializar como array vacío si no hay ausencias laborables
                    }
                }
            }
        }
                

        echo view('header_partes');
        echo view('informe', (array)$data);
        echo view('footer');
    }
}
?>
