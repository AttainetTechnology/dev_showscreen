
<div id="fondo">
<input action="action" type="button" value="Cerrar" onclick="window.close();" class="btn btn-warning btn-sm"/>
<input type="button" onclick="printDiv('printableArea')" value="Imprimir Informe" class="btn btn-success btn-sm"/>
	<div id="printableArea">
    	<!-- info row -->
    	<div class="row">
			<div class="col-sm-8 col-print-8">
				<h1><?php echo $titulo_informe; ?></h1>
				<div>
				<?php
				$desde_informe= date_create ($desde_informe);
				$desde_informe=date_format($desde_informe, 'd/m/Y');
				$hasta_informe= date_create ($hasta_informe);
				$hasta_informe=date_format($hasta_informe, 'd/m/Y');
				?>
				Desde: <b><?php echo $desde_informe; ?></b> hasta: <b><?php echo $hasta_informe; ?></b>
				</div>
			</div>
			<div class="col-sm-4 col-print-4">
				<img src="<?php helper('logo');
				$logo=logo();
				echo $logo; ?>" class="logo_app float-end" width="175px">
			</div>
		</div>
		<?php if (!empty($extras_informe)){?>
					<br><br>
				<div class="row">
				<div class="col-sm-12 col-print-12">
				<h2>Horas extras</h2>
				
				<table class="table table-sm table-hover">
					<thead>
						<tr>
							<th>Nombre y apellidos</th>
							<th>Entrada</th>
							<th>Salida</th>
							<th>Total horas</th>
						</tr>
					</thead>
					<?php foreach($usuarios as $u){
						$id_user=$u['id'];
						$total_linea=0;
						$linea="";
						$fila_usuario="";
						if (!empty($fichajes[$id_user])){
							foreach($fichajes[$id_user] as $f){
								$total_parcial=intval($f['total'])/60;
								$total_linea+=$total_parcial;
								$entrada= date_create ($f['entrada']);
								$entrada=date_format($entrada, 'd/m/Y - H:i'); // Corregido formato de minutos
								$salida= date_create ($f['salida']);
								$salida=date_format($salida, 'd/m/Y - H:i'); // Corregido formato de minutos
								// Convertir total_parcial a horas y minutos
								$horas = floor($total_parcial);
								$minutos = round(($total_parcial - $horas) * 60);
								// Construir la cadena de texto para mostrar horas y minutos si hay minutos
								$textoHoras = $minutos > 0 ? sprintf("%d horas y %02d minutos", $horas, $minutos) : sprintf("%d horas", $horas);
								$linea.="
								<tr>
									<td></td>
									<td>". $entrada ."</td>
									<td>". $salida ."</td>
									<td>". $textoHoras ."</td>
								</tr>";
							}
							// Convertir total_linea a horas y minutos para la fila del usuario
							$horas_linea = floor($total_linea);
							$minutos_linea = round(($total_linea - $horas_linea) * 60);
							// Construir la cadena de texto para mostrar horas y minutos si hay minutos
							$textoHorasLinea = $minutos_linea > 0 ? sprintf("%d horas y %02d minutos", $horas_linea, $minutos_linea) : sprintf("%d horas", $horas_linea);
							$fila_usuario="
							<tr class='table-warning'>
								<td>". $u['nombre_usuario'] ." ". $u['apellidos_usuario'] ."</td>
								<td></td>
								<td></td>
								<td><strong>". $textoHorasLinea ."</strong></td>
							</tr>
							";  
						}
						//Imprimo las lineas
						if (!empty($fila_usuario)){
							echo $fila_usuario;
						}
						if (!empty($linea)){
						echo $linea;
						}
					} ?>
				</table>
					</div>
				</div>
				<?php } //Cierro extras ?>
		<?php if (!empty($incidencias_informe)){?>
		<br><br>
		<div class="row">
		<div class="col-sm-12 col-print-12">
		<h2>Incidencias</h2>
		Este listado muestra el total de fichajes incompletos o sin cerrar llevados a cabo durante el periodo seleccionado. <br><br>
		<table class="table table-sm table-hover">
			<thead>
				<tr>
					<th>Nombre y apellidos</th>
					<th>Entrada</th>
					<th>Salida</th>
					<th>Incidencia</th>
				</tr>
			</thead>
			<?php foreach($usuarios as $w){
				$id_user=$w['id'];
				$total_linea=0;
				$linea="";
				$fila_usuario="";
				if (!empty($incid[$id_user])){
					foreach($incid[$id_user] as $f){
						$entrada= date_create ($f['entrada']);
						$entrada=date_format($entrada, 'd/m/Y - H:i');
						$salida= date_create ($f['salida']);
						$salida=date_format($salida, 'd/m/Y - H:i');
						$duracion = $f['duracion']; // Asumiendo que 'duracion' ya está calculada y disponible

						// Determinar el tipo de incidencia basado en la duración
						if ($duracion < 480) {
							$tipo_incidencia = "Menos 8h";
						} elseif ($duracion > 510) {
							$tipo_incidencia = "Sin cerrar";
						} else {
							$tipo_incidencia = "Duración adecuada"; // O cualquier otro mensaje predeterminado
						}

						// Si 'salida' es NULL, indicar que el fichaje no ha sido cerrado
						if (is_null($f['salida'])) {
							$tipo_incidencia = "Fichaje sin cerrar";
						}

						$linea.="
						<tr>
							<td></td>
							<td>". $entrada ."</td>
							<td>". ($f['salida'] ? $salida : "No cerrado") ."</td>
							<td>". $tipo_incidencia ."</td>
						</tr>";
					} // Cierro foreach incidencias
					$fila_usuario="
					<tr class='table-warning'>
						<td>". $w['nombre_usuario'] ." ". $w['apellidos_usuario'] ."</td>
						<td></td>
						<td></td>
						<td><strong></strong></td>
					</tr>
					";  
				} // Cierro if empty incidencias

				//Imprimo las lineas
				if (!empty($fila_usuario)){
					echo $fila_usuario;
				}
				if (!empty($linea)){
				echo $linea;
				}
		 	} //Cierro foreach usuarios ?>
		</table>
			</div>
		</div>
		<?php } //Cierro incidencias 
	   		
			 // VACACIONES 
		$workingDays = [];
		
		if (!empty($vacaciones_informe)){
			$holidayDays = [];
			if (!empty($festivos)){   
				foreach ($festivos as $fes){
					$holidayDays[] = $fes['fecha'];
				}
			} 
			if (!empty($laborables)){ 
				foreach ($laborables as $lab){
					$workingDays = [
						$lab['lunes'],
						$lab['martes'],
						$lab['miercoles'],
						$lab['jueves'],
						$lab['viernes'],
						$lab['sabado'],
						$lab['domingo']
					];
				}
			}

		?>

		<br><br>
		<div class="row">
		<div class="col-sm-12 col-print-12">
		<h2>Personal de Vacaciones</h2>
		<table class="table table-sm table-hover">
			<thead>
				<tr>
					<th>Nombre y apellidos</th>
					<th>Desde</th>
					<th>Hasta</th>
					<th>Descripción</th>
					<th>Total días</th>
					<th>*Total laborables</th>
				</tr>
			</thead>
			<?php foreach($usuarios as $x){
				$id_user=$x['id'];
				$total_linea=0;
				$linea="";
				$fila_usuario="";
				$sumadias=0;
				$sumadiaslaborables=0;
				if (!empty($vacas[$id_user])){
					foreach($vacas[$id_user] as $f){
						$desde= date_create ($f['desde']);
						$hasta= date_create ($f['hasta']);
						$desde=date_format($desde, 'd/m/Y');
						$hasta=date_format($hasta, 'd/m/Y');
						//Calculo la diferencia
						$date1 = date_create_from_format('Y-m-d', $f['desde']);
						$date2 = date_create_from_format('Y-m-d', $f['hasta']);
						$diff = (array) date_diff($date2, $date1);
						//Guardamos el total del tiempo en minutos
						//print_r($diff);
						$dias=$diff['days']+1;
	
						$DiasLaborables= number_of_working_days($f['desde'], $f['hasta'],$holidayDays, $workingDays);
						$linea.="
						<tr>
							<td></td>
							<td>". $desde ."</td>
							<td>". $hasta ."</td>
							<td>". $f['observaciones'] ."</td>
							<td>".$dias."</td>
							<td>".$DiasLaborables."</td>
						</tr>";
						$sumadias += $dias;
						$sumadiaslaborables += $DiasLaborables;
					} // Cierro foreach incidencias
					$fila_usuario="
					<tr class='table-warning'>
						<td>". $x['nombre_usuario'] ." ". $x['apellidos_usuario'] ."</td>
						<td></td>
						<td></td>
						<td></td>
						<td>".$sumadias."</td>
						<td><strong>".$sumadiaslaborables."</strong></td>
					</tr>
					";	
				} // Cierro if empty incidencias
				//Imprimo las lineas
				if (!empty($fila_usuario)){
					echo $fila_usuario;
				}
				if (!empty($linea)){
				echo $linea;
				}
			 } //Cierro foreach usuarios ?>
		</table>
		<?php 
		if (!empty($festivos)){   
			echo "<strong>Festivos en el periodo: </strong><br>";
			//print_r($festivos);
			foreach ($festivos as $fes){
				echo $fes['fecha']."  (". $fes['festivo'].")<br>";
			}
		} 
		if (!empty($laborables)){   
			echo "*Se consideran laborables: <strong>";
			$i=0;
			$coma="";
			$diaslab="";
			$y=" y ";
			
			foreach ($laborables as $la){
			if ($la['domingo']=='7'){ 
				$diaslab=$y."domingo";					
				$coma="";
				$y="";
				}
			if ($la['sabado']=='6'){ 
				$diaslab= $y." sábado".$coma.$diaslab;
				if ($y==""){$coma=", ";};
				$y="";
				}
			if ($la['viernes']=='5'){ 
				$diaslab = $y." viernes". $coma." ".$diaslab;
				if ($y==""){$coma=", ";};
				$y="";
				}
			if ($la['jueves']=='4'){ 
				$diaslab = $y." jueves". $coma."".$diaslab;
				if ($y==""){$coma=", ";};
				$y="";
				}
			if ($la['miercoles']=='3'){ 
				$diaslab= $y." miércoles". $coma."".$diaslab;
				if ($y==""){$coma=", ";};
				$y="";
				}
			if ($la['martes']=='2'){ 
				$diaslab= $y." martes". $coma."".$diaslab;
				if ($y==""){$coma=", ";};
				$y="";
				}
			if ($la['lunes']=='1'){ 
				$diaslab= "lunes". $coma."".$diaslab;
				}
			}
			
			echo $diaslab."</strong>. Y se descuentan los festivos que coinciden durante el periodo.";
		
			
		} 
		?>
		
			</div>
		</div>
		<?php } //Vacaciones 
		?>
		<?php if (!empty($ausencias_informe)) { ?>
    <br><br>
    <div class="row">
        <div class="col-sm-12 col-print-12">
            <h2>Ausencias</h2>
            Este listado muestra los días en los que se esperaba la asistencia del personal y este no hizo acto de presencia ni lo justificó.<br><br>
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Nombre y apellidos</th>
                        <th>Fecha de la ausencia</th>
                        <th>Total días</th>
                    </tr>
                </thead>
                <?php
                foreach ($usuarios as $u) {
                    $id_user = $u['id'];
                    if (isset($ausencias[$id_user]) && is_array($ausencias[$id_user])) {
                        $diasSinAusenciaLaborables = $ausencias[$id_user];
                        $fila_usuario = "<tr class='table-warning'><td>". $u['nombre_usuario'] ." ". $u['apellidos_usuario'] ."</td><td></td><td><strong>". count($diasSinAusenciaLaborables) ."</strong></td></tr>";
                        echo $fila_usuario;

                        foreach ($diasSinAusenciaLaborables as $dia) {
                            echo "<tr><td></td><td>". $dia ."</td><td></td></tr>";
                        }
                    }
                }
                ?>
            </table>
        </div>
    </div>
<?php } // Cierro ausencias ?>
					</div><!-- /#Printable area -->
			</div> <!-- Fondo -->
			<?php	
	/* Comprobamos si fue sábado, domingo o festivo */ 
	   function number_of_working_days($from, $to, $holidayDays, $workingDays) {
			//   $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
			//   $holidayDays = ['2022-08-15', '2022-01-01', '2022-12-23']; # variable and fixed holidays
			  $from = new DateTime($from);
			  $to = new DateTime($to);
			  $to->modify('+1 day');
			  $interval = new DateInterval('P1D');
			  $periods = new DatePeriod($from, $interval, $to);
		  
			  $days = 0;
			  foreach ($periods as $period) {
				  if (!in_array($period->format('N'), $workingDays)) continue;
				  if (in_array($period->format('"d/m/Y"'), $holidayDays)) continue;
				  if (in_array($period->format('*-m-d'), $holidayDays)) continue;
				  $days++;
			  }
			  return $days;
		  }

?>
