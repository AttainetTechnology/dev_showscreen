<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Pedido Proveedor</title>
    <style>
        /* Añade tus estilos de impresión aquí */
        body { font-family: Arial, sans-serif; }
        .pedido { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: left; }
    </style>
</head>
<body>
    <h1>Pedido a Proveedor</h1>
    <div class="pedido">
        <p><strong>ID del Pedido:</strong> <?= $pedido['id_pedido'] ?></p>
        <p><strong>Proveedor:</strong> <?= $pedido['id_proveedor'] ?></p>
        <p><strong>Referencia:</strong> <?= $pedido['referencia'] ?></p>
        <p><strong>Fecha de Entrega:</strong> <?= $pedido['fecha_entrega'] ?></p>
        <p><strong>Observaciones:</strong> <?= $pedido['observaciones'] ?></p>
    </div>

    <?php if (!empty($lineas)): ?>
    <h2>Detalle del Pedido</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lineas as $linea): ?>
            <tr>
                <td><?= $linea['nombre_producto'] ?></td>
                <td><?= $linea['cantidad'] ?></td>
                <td><?= $linea['precio'] ?></td>
                <td><?= $linea['total_linea'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No hay líneas de pedido.</p>
    <?php endif; ?>

</body>
</html>
