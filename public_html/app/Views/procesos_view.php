<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<br>
<style>
    .placeholder {
        user-select: none;
        pointer-events: none;
    }

    li.ui-state-default {
        list-style-type: none;
    }

    .ui-state-default {
        position: relative;
        border: 1px solid #ddd;
        background-color: #f8f9fa;
        padding: 8px 16px;
        border-radius: 4px;
        margin-bottom: 4px;
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
        cursor: pointer;

    }

    .ui-state-default.ui-sortable-helper {
        box-shadow: 0 4px 2px rgba(0, 0, 0, 0.1);
    }

    .connectedSortable {
        min-height: 50px;
        padding: 10px;
        border: 1px dashed #ccc;
    }


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

    .modal-body .row {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        /* columnas  misma altura */
    }

    .modal-body .col-md-6 {
        flex: 0 0 48%;
        max-width: 48%;
        display: flex;
        flex-direction: column;
    }


    .connectedSortable {
        flex-grow: 1;
        min-height: 100px;
        border: 1px dashed #ccc;
        padding: 15px;
        border-radius: 10px;
        background-color: #fafafa;
    }


    .modal-dialog {
        max-width: 100%;
    }


    .modal-content {
        border-radius: 10px;
        font-family: 'Arial', sans-serif;
        margin: -20px;
        margin-top: 5px;
    }

    .modal-title {
        font-weight: bold;
        font-size: 20px;
        color: #333;
        margin-top: 10px;
    }

    h6 {
        font-size: 14px !important;
        margin: 17px;
    }

    .btn {
        border-radius: 5px;
        margin-right: 21px;
        padding: 10px 20px !important;

    }

    .btn-primary {
        background-color: #45a049;
        ;
        border: none;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #4CAF50;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    #filterInput {
        border-radius: 5px;
        padding: 10px;
        border: 1px solid #ccc;
        transition: box-shadow 0.3s ease;
    }

    #filterInput:focus {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-color: #4CAF50;
    }

    .ui-state-default {
        background-color: #f1f1f1;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 5px;
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }

    .ui-state-default:hover {
        background-color: #e9ecef;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }


    .modal-body .col-md-6 {
        flex: 0 0 48%;
        max-width: 48%;
    }


    .connectedSortable {
        min-height: 100px;
        border: 1px dashed #ccc;
        padding: 15px;
        border-radius: 10px;
        background-color: #fafafa;
    }

    .placeholder {
        color: #888;
        font-style: italic;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
    }
</style>

<h5 id="procesosModalLabel">Producto: <?= esc($producto->nombre_producto ?? 'Nombre no disponible') ?></h5>
<div class="buttonsEditProductProveedAbajo">
    <button type="button" class="boton btnGuardar" id="saveOrder">Guardar Procesos
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27" fill="none">
            <path
                d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                fill="white" />
        </svg>
    </button>
    <!-- Botón "Volver" -->
    <button type="button" class="boton volverButton"
        onclick="location.href='https://dev.showscreen.app/productos/editarVista/<?= esc($producto->id_producto) ?>'">Volver
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M19.5 13C19.5 13.2155 19.4144 13.4221 19.262 13.5745C19.1096 13.7269 18.903 13.8125 18.6875 13.8125H9.27386L12.7627 17.2997C12.8383 17.3753 12.8982 17.465 12.9391 17.5637C12.98 17.6624 13.001 17.7682 13.001 17.875C13.001 17.9818 12.98 18.0876 12.9391 18.1863C12.8982 18.285 12.8383 18.3747 12.7627 18.4502C12.6872 18.5258 12.5975 18.5857 12.4988 18.6266C12.4001 18.6675 12.2943 18.6885 12.1875 18.6885C12.0807 18.6885 11.9749 18.6675 11.8762 18.6266C11.7775 18.5857 11.6878 18.5258 11.6122 18.4502L6.73724 13.5752C6.66157 13.4998 6.60154 13.4101 6.56058 13.3114C6.51962 13.2127 6.49854 13.1069 6.49854 13C6.49854 12.8931 6.51962 12.7873 6.56058 12.6886C6.60154 12.5899 6.66157 12.5002 6.73724 12.4247L11.6122 7.54974C11.7648 7.39717 11.9717 7.31146 12.1875 7.31146C12.4032 7.31146 12.6102 7.39717 12.7627 7.54974C12.9153 7.7023 13.001 7.90923 13.001 8.12499C13.001 8.34075 12.9153 8.54767 12.7627 8.70024L9.27386 12.1875H18.6875C18.903 12.1875 19.1096 12.2731 19.262 12.4255C19.4144 12.5778 19.5 12.7845 19.5 13Z"
                fill="white" />
        </svg>
    </button>
</div>

<div class="row">
    <div class="col-md-6">
        <h6>Todos los Procesos</h6>
        <input type="text" id="filterInput" placeholder="Buscar procesos..." class="form-control mb-2">
        <?php if (!empty($allProcesses)): ?>
            <?php usort($allProcesses, function ($a, $b) {
                return strcmp($a->nombre_proceso, $b->nombre_proceso);
            }); ?>
            <ul id="sortable" class="connectedSortable">
                <?php foreach ($allProcesses as $proceso): ?>
                    <?php if ($proceso->estado_proceso == 1 && !in_array($proceso->id_proceso, array_column($procesos, 'id_proceso'))): ?>
                        <li class="ui-state-default" data-id="<?= $proceso->id_proceso ?>">
                            <?= $proceso->nombre_proceso ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No hay procesos disponibles.</p>
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <h6>Ordenar Procesos</h6>
        <ul class="connectedSortable" style="border: 1px solid #000; margin: 10px; padding: 10px; min-height: 50px;"
            id="orderList">
            <?php if (!empty($procesos)): ?>
                <?php foreach ($procesos as $proceso): ?>
                    <li class="ui-state-default" data-id="<?= $proceso->id_proceso ?>">
                        <?= $proceso->nombre_proceso ?>
                        <button class="remove-process" data-id="<?= $proceso->id_proceso ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="placeholder">No hay procesos asociados.</li>
            <?php endif; ?>
        </ul>
        <input type="hidden" id="order" value="">
    </div>
</div>




<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<script>
    $(document).ready(function () {
        // Filtro de búsqueda
        $('#filterInput').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#sortable li').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        // Mostrar el modal al cargar la página
        $('#procesosModal').modal('show');

        // Manejar la eliminación del proceso
        $(document).on('click', '.remove-process', function () {
            eliminarProceso($(this));
        });

        // Inicializar listas ordenables
        inicializarSortable();

        // Guardar el orden al hacer click en el botón de guardar
        $('#saveOrder').click(function () {
            guardarOrden();
        });

        // Regresar al historial anterior al cerrar el modal
        $('#procesosModal').on('hidden.bs.modal', function () {
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
            $('#orderList').children('li').each(function (index) {
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
                receive: function (event, ui) {
                    var idProceso = ui.item.data('id');

                    // Verificar si el proceso ya está en "Ordenar Procesos"
                    if ($('#orderList').find('[data-id="' + idProceso + '"]').length > 0) {
                        $(ui.sender).sortable('cancel');
                    }
                }
            }).disableSelection();

            $("#orderList").sortable({
                items: "li:not(.placeholder)",
                receive: function (event, ui) {
                    manejarRecepcionElemento(ui.item);
                    actualizarOrden();

                    // Eliminar el proceso de "Todos los Procesos" al moverlo a "Ordenar Procesos"
                    $('#sortable').find('[data-id="' + ui.item.data('id') + '"]').remove();
                },
                update: actualizarOrden
            }).disableSelection();

            $(".connectedSortable").on("sortreceive sortremove", function () {
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
            var orden = [];
            $('#orderList').children('li').each(function (index) {
                orden.push({
                    id_proceso: $(this).data('id'),
                    orden: index + 1
                });
            });

            var idProducto = <?= esc($producto->id_producto) ?>;

            // Enviar datos al servidor
            $.ajax({
                url: '<?= base_url('productos/updateOrder') ?>',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    id_producto: idProducto,
                    procesos: orden
                }),
                success: function () {
                    alert('Procesos guardados correctamente.');
                    location.reload();
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert('Ha habido un error al guardar los procesos.');
                }
            });
        }

        function obtenerIdProducto(url) {
            return url.substring(url.lastIndexOf('/') + 1);
        }
    });
</script>


<?= $this->endSection() ?>