<div class="list-group">
	<?php 
	$total_recogidas=0;
	$total_entregas=0;
	
	$poblaciones = array();
	$recogidas =array();
	$entregas = array();

	foreach($rutas as $r) { 
		if (!in_array($r['poblacion'], $poblaciones)){
			$poblaciones[] = $r['poblacion'];
		}
		if ($r['recogida_entrega']=='1'){
			$recogidas[] = $r['poblacion'];
		}
		else{
			$entregas[] = $r['poblacion'];
		}
	}	
	foreach($poblaciones as $poblacion){
		if (in_array($poblacion, $recogidas)){
			$total_recogidas = array_count_values($recogidas)[$poblacion];
		}
		if (in_array($poblacion, $entregas)){
			$total_entregas = array_count_values($entregas)[$poblacion];
		}
		echo "<a href='". site_url('/Rutas/enmarcha')."' class='list-group-item'><strong>".$poblacion.":</strong> Recoger <strong>".$total_recogidas."</strong> y entregar <strong>".$total_entregas."</strong></a>";
		$total_recogidas = 0;
		$total_entregas = 0;
	}
	?>
</div>

