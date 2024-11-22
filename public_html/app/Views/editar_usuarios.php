<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Editar Usuario</h2>
    <form action="<?= base_url('usuarios/actualizarUsuario') ?>" method="post">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        <div class="form-group">
            <label for="nombre_usuario">Nombre:</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario"
                value="<?= esc($usuario['nombre_usuario']) ?>" required>
        </div>
        <div class="form-group">
            <label for="apellidos_usuario">Apellidos:</label>
            <input type="text" class="form-control" id="apellidos_usuario" name="apellidos_usuario"
                value="<?= esc($usuario['apellidos_usuario']) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= esc($usuario['email']) ?>"
                required>
        </div>
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" class="form-control" id="telefono" name="telefono"
                value="<?= esc($usuario['telefono']) ?>" required>
        </div>
        <div class="form-group">
            <label for="user_activo">Activo:</label>
            <select class="form-control" id="user_activo" name="user_activo">
                <option value="1" <?= $usuario['user_activo'] == 1 ? 'selected' : '' ?>>Sí</option>
                <option value="0" <?= $usuario['user_activo'] == 0 ? 'selected' : '' ?>>No</option>
            </select>
        </div>

        <!-- Botón para datos de acceso -->
        <div class="mt-3">
            <a href="<?= base_url('usuarios/datosAcceso/' . $usuario['id']) ?>" class="btn btn-info">
                <i class="fa fa-key"></i> Datos de Acceso
            </a>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Guardar Cambios</button>
    </form>
</div>
<?= $this->endSection() ?>