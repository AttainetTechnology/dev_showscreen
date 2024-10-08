<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2 class="titlepedidosadd">Añadir Pedido</h2>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<div class="container mt-4 addpedido">
    <form action="<?= base_url('pedidos/save') ?>" method="post">
        <!-- Campo de empresa -->
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

        <!-- Campo de referencia -->
        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control">
        </div>
        <br>

        <!-- Campo de fecha de entrada -->
        <div class="form-group">
            <label for="fecha_entrada">Fecha de Entrada:</label>
            <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <br>

        <!-- Campo de fecha de entrega -->
        <div class="form-group">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required>
        </div>
        <br>

        <!-- Observaciones -->
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control"></textarea>
        </div>
        <br>

        <!-- Usuario -->
        <input type="hidden" id="id_usuario" name="id_usuario" value="<?= esc($usuario_sesion['id_user']); ?>">
        
        <br>
        <button type="submit" class="btn btn-primary">Guardar Pedido</button>
    </form>
</div>
<?= $this->endSection() ?>
