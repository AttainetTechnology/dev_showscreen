<!-- app/Views/add_form.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Pedido</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" rel="stylesheet">

<!-- Opcional: iconos de Bootstrap -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <style>
        /* Estilos adicionales para el formulario en el modal */
        .modal-content {
            border-radius: 0.5rem;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-body {
            padding: 2rem;
        }
        .modal-footer {
            border-top: none;
            padding: 1rem;
        }
        .alert-danger {
            text-align: left;
            color: #dc3545; /* Color rojo de Bootstrap */
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
  <!-- Modal -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderModalLabel">Añadir Nuevo Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url('pedidos2/save') ?>" method="post">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="mb-3">
                        <label for="id_cliente" class="form-label">Cliente</label>
                        <input type="text" class="form-control" id="id_cliente" name="id_cliente" value="<?= old('id_cliente') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="referencia" class="form-label">Referencia</label>
                        <input type="text" class="form-control" id="referencia" name="referencia" value="<?= old('referencia') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="id_usuario" name="id_usuario" value="<?= old('id_usuario') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_entrada" class="form-label">Fecha de Entrada</label>
                        <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada" value="<?= old('fecha_entrada') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                        <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" value="<?= old('fecha_entrega') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones"><?= old('observaciones') ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
