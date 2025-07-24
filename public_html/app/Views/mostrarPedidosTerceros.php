<?php foreach ($pedidos as $pedido): ?>
<tr>
    <td><?= esc($pedido['id_ped_terceros']) ?></td>
    <td><?= esc($pedido['nombre_proveedor']) ?></td>
    <td><?= esc($pedido['cantidad']) ?></td>
    <td><?= esc($pedido['observaciones']) ?></td>
    <td>
        <a href="<?= base_url('pedidos/edit/' . $pedido['id_pedido_cliente']) ?>" class="btn btn-primary">Editar</a>
    </td>
</tr>
<?php endforeach; ?> 