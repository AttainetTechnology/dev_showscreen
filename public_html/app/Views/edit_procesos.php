<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <a href="<?= base_url('procesos/restriccion/' . $previous_proceso_id); ?>" id="prev-link" class="btn btn-secondary">&larr;</a>
    <a href="<?= base_url('procesos/restriccion/' . $next_proceso_id); ?>" id="next-link" class="btn btn-secondary"> &rarr;</a>




    <form id="edit-form" action="<?= base_url('procesos/restriccion/' . $proceso_principal['id_proceso']); ?>" method="post">
        <input type="hidden" name="redirect_url" value="<?= current_url(); ?>">
        <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>

        <h2 class="text-center mb-4"><?= $proceso_principal['nombre_proceso'] ?></h2>
        <div class="form-group">
            <label for="nombre_proceso">Nombre del Proceso</label>
            <input type="text" class="form-control" id="nombre_proceso" name="nombre_proceso" value="<?= esc($proceso_principal['nombre_proceso']); ?>" required>
        </div>
        <div class="form-group">
            <label for="estado_proceso">Estado del Proceso</label>
            <select class="form-control" id="estado_proceso" name="estado_proceso" required>
                <option value="1" <?= $proceso_principal['estado_proceso'] == '1' ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?= $proceso_principal['estado_proceso'] == '0' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
        <br>
        <h3 class="text-center mb-4">Editar Restricciones</h3>
        <?php
        $restricciones_actuales = explode(',', $proceso_principal['restriccion']);
        ?>
        <div class="row">
            <?php foreach ($procesos as $proceso): ?>
                <?php
                // Verificamos si el proceso actual está en las restricciones
                $is_restricted = in_array($proceso['id_proceso'], $restricciones_actuales);
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card proceso-box <?= $is_restricted ? 'selected border-primary shadow' : '' ?>" data-id="<?= $proceso['id_proceso'] ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= $proceso['nombre_proceso'] ?></h5>
                        </div>
                    </div>
                    <input type="checkbox" name="restricciones[]" value="<?= $proceso['id_proceso'] ?>" class="d-none" <?= $is_restricted ? 'checked' : '' ?>>
                </div>
            <?php endforeach; ?>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isDirty = false;
        const form = document.getElementById('edit-form');
        const inputs = form.querySelectorAll('input, select');

        // Detectar cambios en los campos del formulario
        inputs.forEach(function(input) {
            input.addEventListener('change', function() {
                isDirty = true;
            });
        });

        // Interceptar clicks en las flechas de navegación
        document.getElementById('prev-link').addEventListener('click', function(event) {
            if (isDirty && !confirm('Tienes cambios sin guardar. ¿Estás seguro de que deseas salir sin guardar?')) {
                event.preventDefault();
            }
        });

        document.getElementById('next-link').addEventListener('click', function(event) {
            if (isDirty && !confirm('Tienes cambios sin guardar. ¿Estás seguro de que deseas salir sin guardar?')) {
                event.preventDefault();
            }
        });

        // Detectar clicks en los cuadros de restricción para marcar el formulario como modificado
        document.querySelectorAll('.proceso-box').forEach(function(box) {
            box.addEventListener('click', function() {
                this.classList.toggle('selected');
                this.classList.toggle('border-primary');
                this.classList.toggle('shadow');
                var checkbox = this.nextElementSibling;
                checkbox.checked = !checkbox.checked;
                isDirty = true; // Marcar el formulario como modificado
            });
        });
    });
</script>

<style>
    .proceso-box {
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .proceso-box.selected {
        background-color: #f0f8ff;
    }

    .proceso-box:hover {
        box-shadow: 0 0 11px rgba(33, 33, 33, .2);
    }
</style>

<?= $this->endSection() ?>