<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2 class="titlepedidosadd">Añadir Pedido</h2>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<div class="container mt-4 addpedido">
    <form action="<?= base_url('pedidos/save') ?>" method="post">
        <div class="form-group">
            <label for="id_cliente">Empresa:</label>
            <select id="id_cliente" name="id_cliente" class="form-control" required>
                <option value="" selected disabled hidden>Seleccione empresa</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>"><?= $cliente['nombre_cliente'] ?></option>
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
            <label for="fecha_entrada">Fecha de Entrada:</label>
            <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" value="<?= date('Y-m-d') ?>" required>
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
        <input type="hidden" id="id_usuario" name="id_usuario" value="<?= esc($usuario_sesion['id_user']); ?>">
        <br>        
        <div class="buttonsEditProductProveedAbajo">
        <button type="submit" class="btn btn-primary">Guardar Pedido</button>
        <button type="button" class="btn mb-3 volverButton" id="volverButton" style="margin-top:15px;">Volver</button>
        </div>
    </form>
</div>
<script>
    document.getElementById('volverButton').addEventListener('click', function() {
        window.location.href = '<?= base_url('pedidos/enmarcha') ?>';
    });
</script>

<?= $this->endSection() ?>
