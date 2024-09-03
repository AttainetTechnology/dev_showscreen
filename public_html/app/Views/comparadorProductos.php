<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>Lista de Productos</h1>

<?php if (empty($productosConProveedores)): ?>
    <p>No hay productos disponibles.</p>
<?php else: ?>
    <ul>
        <?php foreach ($productosConProveedores as $item): ?>
            <li>
                <strong><?= esc($item['producto']['nombre_producto']) ?></strong>
                <ul>
                    <?php if (empty($item['proveedores'])): ?>
                        <li>No hay proveedores para este producto.</li>
                    <?php else: ?>
                        <?php foreach ($item['proveedores'] as $proveedor): ?>
                            <li>
                                <?= esc($proveedor['nombre_proveedor']) ?> - Precio: <?= esc($proveedor['precio']) ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?= $this->endSection() ?>
