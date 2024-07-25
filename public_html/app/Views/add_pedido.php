<!-- app/Views/add_pedido.php -->
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <h2>Añadir Pedido</h2>
    <form action="<?= base_url('pedidos2/save') ?>" method="post">
        <div class="form-group">
            <br>
            <label for="id_cliente">Empresa:</label>
            <select id="id_cliente" name="id_cliente" class="form-control">
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
        <div class="form-group">
            <label for="id_usuario">Hace el pedido:</label>
            <?= $usuario_html; ?>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Guardar Pedido</button>
        <a href="<?= base_url('pedidos2/enmarcha') ?>" class="btn btn-secondary">Cerrar</a>
    </form>
</div>
<?= $this->endSection() ?>
