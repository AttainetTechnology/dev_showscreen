<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h2>Pedidos</h2>
<br>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Fecha Entrada</th>
            <th>Fecha Entrega</th>
            <th>Cliente</th>
            <th>Referencia</th>
            <th>Estado</th>
            <th>Usuario</th>
            <th>Total</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pedidos as $pedido): ?>
        <tr>
            <td><?= $pedido->id_pedido ?></td>
            <td><?= $pedido->fecha_entrada ?></td>
            <td><?= $pedido->fecha_entrega ?></td>
            <td><?= $pedido->nombre_cliente ?></td>
            <td><?= $pedido->referencia ?></td>
            <td><?= $pedido->estado ?></td>
            <td><?= $pedido->nombre_usuario ?></td>
            <td><?= $pedido->total_pedido ?>€</td>
            <td>
                <a href="<?= base_url('pedidos/print/' . $pedido->id_pedido) ?>" class="btn btn-info" target="_blank">Imprimir</a>
                <a href="<?= base_url('pedidos/edit/' . $pedido->id_pedido) ?>" class="btn btn-warning">Editar</a>
                <?php if ($allow_delete): ?>
                    <a href="<?= base_url('pedidos/delete/' . $pedido->id_pedido) ?>" class="btn btn-danger">Eliminar</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
