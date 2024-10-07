
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery primero, ya que algunos scripts pueden depender de él -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap después de jQuery -->
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script> <!-- ag-Grid al final -->

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<!-- Formulario para añadir una nueva ruta -->
<div id="addRutaForm">
    <form id="formNuevaRuta" method="POST" action="<?= base_url('Ruta_pedido/guardarRuta') ?>">
        <input type="hidden" name="id_pedido" value="<?= esc($id_pedido) ?>" />
        <input type="hidden" name="id_cliente" value="<?= esc($id_cliente) ?>" />
        <input type="hidden" name="id_ruta" id="id_ruta" />

        <div class="mb-3">
            <label for="poblacion" class="form-label">Población</label>
            <select class="form-control" id="poblacion" name="poblacion" required>
                <option value="">Selecciona una población</option>
                <?php foreach ($poblaciones as $poblacion): ?>
                    <option value="<?= esc($poblacion['id_poblacion']) ?>"><?= esc($poblacion['poblacion']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="lugar" class="form-label">Lugar</label>
            <input type="text" class="form-control" id="lugar" name="lugar">
        </div>
        <div class="mb-3">
            <label for="recogida_entrega" class="form-label">Recogida/Entrega</label>
            <select class="form-control" id="recogida_entrega" name="recogida_entrega" required>
                <option value="1">Recogida</option>
                <option value="2">Entrega</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="transportista" class="form-label">Transportista</label>
            <select class="form-control" id="transportista" name="transportista" required>
                <?php foreach ($transportistas as $id => $nombre): ?>
                    <option value="<?= esc($id) ?>"><?= esc($nombre) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="fecha_ruta" class="form-label">Fecha</label>
            <input type="date" class="form-control" id="fecha_ruta" name="fecha_ruta" required>
        </div>
        <div class="mb-3">
            <label for="observaciones" class="form-label">Observaciones</label>
            <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
        </div>

        <div class="mb-3" id="estadoRutaDiv" style="display:none;">
            <label for="estado_ruta" class="form-label">Estado</label>
            <select class="form-control" id="estado_ruta" name="estado_ruta">
                <option value="1">No preparado</option>
                <option value="2">Recogido</option>
                <option value="0">Pendiente</option>
            </select>
        </div>
        <div class="btnModaladdruta">
            <button type="submit" class="btn btn-primary btnGuardarRuta">Guardar Ruta</button>
            <button type="button" class="btn btn-secondary btnVolverRuta" id="volverTabla">Volver</button>
        </div>
    </form>
</div>



<!-- Añadir el script justo antes del cierre del body -->
<script>
$(document).ready(function() {
    // Obtener la fecha de hoy en formato 'YYYY-MM-DD'
    var today = new Date().toISOString().split('T')[0];
    if ($('#fecha_ruta').val() === '') {
        $('#fecha_ruta').val(today);
    }

    // Mostrar formulario y ocultar botones
    $('#openAddRuta').on('click', function() {
        $('#botonesRuta, #gridRutas').hide();   // Ocultar los botones y la tabla
        $('#addRutaForm').show();                // Mostrar el formulario
    });

    // Al hacer clic en el botón "Guardar Ruta"
    $(document).on('submit', '#formNuevaRuta', function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $('.btnGuardarRuta').prop('disabled', true);

        $.ajax({
            url: '<?= base_url('Ruta_pedido/guardarRuta') ?>',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Recargar la página para volver a la vista de la tabla
                    window.location.href = window.location.href.split('?')[0] + '?openModal=1';
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function() {
                alert('Error al guardar la ruta.');
            },
            complete: function() {
                $('.btnGuardarRuta').prop('disabled', false);
            }
        });
    });

    // Volver a la tabla y mostrar los botones
    $('#volverTabla').on('click', function() {
        $('#addRutaForm').hide();           // Ocultar el formulario
        $('#gridRutas, #botonesRuta').show(); // Mostrar la tabla y los botones
    });

    // Limpiar filtros en la tabla
    $('#clear-filters-rutas').on('click', function() {
        if (window.gridApiRutas) {
            window.gridApiRutas.setFilterModel(null);
            window.gridApiRutas.onFilterChanged();
        }
    });
});


</script>
