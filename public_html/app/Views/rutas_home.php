<div class="list-group">
    <?php 
    // Inicializar contadores y arrays
    $poblaciones = [];
    $totales = [];

    // Iterar sobre las rutas para agrupar por población
    foreach ($rutas as $r) {
        $poblacion = $r['nombre_poblacion'];

        // Inicializar los contadores si la población no existe
        if (!isset($totales[$poblacion])) {
            $totales[$poblacion] = [
                'recogidas' => 0,
                'entregas' => 0
            ];
        }

        // Incrementar los contadores según el valor de recogida_entrega
        if ($r['recogida_entrega'] == '1') {
            $totales[$poblacion]['recogidas']++;
        } else {
            $totales[$poblacion]['entregas']++;
        }
    }

    // Mostrar los resultados
    foreach ($totales as $poblacion => $conteo) {
        echo "<a href='" . site_url('/Rutas/enmarcha') . "' class='list-group-item'>";
        echo "<strong>" . esc($poblacion) . ":</strong> ";
        echo "Recoger <strong>" . esc($conteo['recogidas']) . "</strong> ";
        echo "y Entregar <strong>" . esc($conteo['entregas']) . "</strong>";
        echo "</a>";
    }
    ?>
</div>

