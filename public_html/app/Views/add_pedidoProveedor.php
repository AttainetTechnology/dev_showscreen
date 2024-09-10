<!-- app/Views/add_pedido.php -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="modal fade" id="addPedidoModal" tabindex="-1" role="dialog" aria-labelledby="addPedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPedidoModalLabel">Añadir Pedido</h5>
                <button type="button" class="btn-close-custom" aria-label="Close" onclick="window.location.href='<?= base_url('pedidos_proveedor') ?>'">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('Pedidos_proveedor/save') ?>" method="post">
                    <div class="form-group">
                        <label for="id_cliente">Empresa:</label>
                        <select id="id_cliente" name="id_proveedor" class="form-control">
                            <option value="" selected disabled hidden>Seleccione empresa</option>
                            <?php foreach ($proveedores as $proveedor) : ?>
                                <option value="<?= $proveedor['id_proveedor'] ?>"><?= $proveedor['nombre_proveedor'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="referencia">Referencia:</label>
                        <input type="text" id="referencia" name="referencia" class="form-control">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="fecha_salida">Fecha de Salida:</label>
                        <input type="date" id="fecha_salida" name="fecha_salida" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="fecha_entrega">Fecha de Entrega:</label>
                        <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="observaciones">Observaciones:</label>
                        <textarea id="observaciones" name="observaciones" class="form-control"></textarea>
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="id_usuario">Hace el pedido:</label>
                        <?= $usuario_html; ?>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Guardar Pedido</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addPedidoModal').modal('show');

        $('#addPedidoModal').on('hidden.bs.modal', function(e) {
            window.location.href = '<?= base_url('pedidos_proveedor') ?>';
        });
    });
</script>
<?= $this->endSection() ?>