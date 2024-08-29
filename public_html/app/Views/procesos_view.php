<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<style>
    .placeholder {
        user-select: none;
        pointer-events: none;
    }

    li.ui-state-default {
        list-style-type: none;
    }

    /* Estilo para las cartas */
    .ui-state-default {
        position: relative;
        border: 1px solid #ddd;
        background-color: #f8f9fa;
        padding: 8px 16px;
        border-radius: 4px;
        margin-bottom: 4px;
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        /* Cambio para mejorar la indicación de que se puede arrastrar */
    }

    /* Estilo cuando se arrastra */
    .ui-state-default.ui-sortable-helper {
        box-shadow: 0 4px 2px rgba(0, 0, 0, 0.1);
    }

    /* Estilo para los contenedores conectados */
    .connectedSortable {
        min-height: 50px;
        /* Asegura que el contenedor siempre es visible y arrastrable */
        padding: 10px;
        border: 1px dashed #ccc;
    }

    /* Estilo para el botón de eliminar */
    .remove-process {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #dc3545;
        font-size: 16px;
        cursor: pointer;
    }

    .remove-process:hover {
        color: #bd2130;
    }
</style>

<div class="modal fade" id="procesosModal" tabindex="-1" role="dialog" aria-labelledby="procesosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="procesosModalLabel">Producto: <?= $producto->nombre_producto ?></h5>
                <button type="button" class="btn-close-custom" aria-label="Close" onclick="window.history.back();">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Todos los Procesos</h6>
                        <?php if (!empty($allProcesses)) : ?>
                            <ul id="sortable" class="connectedSortable">
                                <?php foreach ($allProcesses as $proceso) : ?>
                                    <?php if ($proceso->estado_proceso == 1 && !in_array($proceso->id_proceso, array_column($procesos, 'id_proceso'))) : ?>
                                        <li class="ui-state-default" data-id="<?= $proceso->id_proceso ?>">
                                            <?= $proceso->nombre_proceso ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p>No hay procesos disponibles.</p>
                        <?php endif; ?>

                    </div>
                    <div class="col-md-6">
                        <h6>Ordenar Procesos</h6>
                        <ul class="connectedSortable" style="border: 1px solid #000; margin: 10px; padding: 10px; min-height: 50px;" id="orderList">
                            <?php if (!empty($procesos)) : ?>
                                <?php foreach ($procesos as $proceso) : ?>
                                    <li class="ui-state-default" data-id="<?= $proceso->id_proceso ?>">
                                        <?= $proceso->nombre_proceso ?>
                                        <button class="remove-process" data-id="<?= $proceso->id_proceso ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <li class="placeholder">No hay procesos asociados.</li>
                            <?php endif; ?>
                        </ul>
                        <input type="hidden" id="order" value="">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-button" data-dismiss="modal" onclick="window.location.href='<?= base_url('productos') ?>';">Cerrar</button>

                <button type="button" class="btn btn-primary save-button" id="saveOrder">Guardar Orden</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
    $(document).ready(function() {
        // Mostrar el modal al cargar la página
        $('#procesosModal').modal('show');

        // Manejar la eliminación del proceso
        $(document).on('click', '.remove-process', function() {
            eliminarProceso($(this));
        });

        // Inicializar listas ordenables
        inicializarSortable();

        // Guardar el orden al hacer click en el botón de guardar
        $('#saveOrder').click(function() {
            guardarOrden();
        });

        // Regresar al historial anterior al cerrar el modal
        $('#procesosModal').on('hidden.bs.modal', function() {
            window.history.back();
        });

        function eliminarProceso(elemento) {
            var idProceso = elemento.data('id');
            var nombreProceso = elemento.closest('li').text().trim();
            elemento.closest('li').remove(); // Elimina el proceso de la lista
            actualizarOrden(); // Actualiza el orden después de eliminar

            // Mover el proceso de vuelta a "Todos los Procesos" si no está ya allí
            if ($('#sortable').find('[data-id="' + idProceso + '"]').length === 0) {
                $('#sortable').append('<li class="ui-state-default" data-id="' + idProceso + '">' + nombreProceso + '</li>');
            }
        }

        function actualizarOrden() {
            var orden = [];
            $('#orderList').children('li').each(function(index) {
                var id = $(this).data('id');
                if (id) {
                    orden.push([id, index + 1]);
                }
            });
            $('#order').val(JSON.stringify(orden));
        }

        function inicializarSortable() {
            $("#sortable").sortable({
                connectWith: "#orderList",
                items: "li:not(.placeholder)",
                receive: function(event, ui) {
                    var idProceso = ui.item.data('id');

                    // Verificar si el proceso ya está en "Ordenar Procesos"
                    if ($('#orderList').find('[data-id="' + idProceso + '"]').length > 0) {
                        $(ui.sender).sortable('cancel');
                    }
                }
            }).disableSelection();

            $("#orderList").sortable({
                items: "li:not(.placeholder)",
                receive: function(event, ui) {
                    manejarRecepcionElemento(ui.item);
                    actualizarOrden();

                    // Eliminar el proceso de "Todos los Procesos" al moverlo a "Ordenar Procesos"
                    $('#sortable').find('[data-id="' + ui.item.data('id') + '"]').remove();
                },
                update: actualizarOrden
            }).disableSelection();

            $(".connectedSortable").on("sortreceive sortremove", function() {
                var tieneElementos = $(this).children('li').length > 0;
                $(this).find(".placeholder").toggle(!tieneElementos);
            });
        }

        function manejarRecepcionElemento(elemento) {
            var idProceso = elemento.data('id');

            if (!elemento.find('.remove-process').length) {
                elemento.append(crearBotonEliminar(idProceso));
            }
        }

        function crearBotonEliminar(idProceso) {
            return '<button class="remove-process" data-id="' + idProceso + '"><i class="fas fa-trash"></i></button>';
        }

        function guardarOrden() {
            var orden = JSON.parse($('#order').val());
            var url = window.location.pathname;
            var idProducto = obtenerIdProducto(url);

            var data = orden.map(function(item) {
                return {
                    id_producto: idProducto,
                    id_proceso: item[0],
                    orden: item[1]
                };
            });

            $.post('updateOrder', {
                data: JSON.stringify(data)
            }).done(function() {
                alert('Procesos guardados');
                window.location.href = '<?= base_url('productos') ?>';
            }).fail(function() {
                alert('Ha habido un error');
            });
        }

        function obtenerIdProducto(url) {
            return url.substring(url.lastIndexOf('/') + 1);
        }
    });
</script>

<?= $this->endSection() ?>