<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php foreach($css_files as $file): ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
</head>
<body>
    <?php include('navegacion.php'); ?>

    <div style='height:20px;'></div>  
    <div style="padding: 10px">
        <?php echo $output; ?>
    </div>
    <div>
        <button id="add-familia-producto-btn" data-toggle="modal" data-target="#addFamiliaProductoModal">Añadir Familia Producto</button>
    </div>
    <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

    <!-- Modal -->
    <div class="modal fade" id="addFamiliaProductoModal" tabindex="-1" role="dialog" aria-labelledby="addFamiliaProductoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFamiliaProductoModalLabel">Añadir Familia Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="add-familia-producto-form">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="orden">Orden</label>
                            <input type="number" class="form-control" id="orden" name="orden" required>
                        </div>
                        <div class="form-group">
                            <label for="id_activo">ID Activo</label>
                            <select class="form-control" id="id_activo" name="id_activo">
                                <?php
                                foreach ($booleano as $bool) {
                                    $selected = $bool->valor == 'Activo' ? 'selected' : '';
                                    echo "<option value='{$bool->id_activo}' $selected>{$bool->valor}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="save-familia-producto-btn">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#save-familia-producto-btn').click(function(e) {
                e.preventDefault();
    
                var data = new FormData($('#add-familia-producto-form')[0]);
    
                $.ajax({
                    url: '<?php echo site_url('familia_productos/add_familia_producto') ?>',
                    type: 'POST',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                })
                .done(function(response) {
                    $('#addFamiliaProductoModal').modal('hide');
                    location.reload();
                    alert(response.message);
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error: ", textStatus, errorThrown);
                    alert('Hubo un error al guardar la familia de producto. Por favor, inténtalo de nuevo.');
                });
            });
        });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>