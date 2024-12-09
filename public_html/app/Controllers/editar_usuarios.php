<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2>Editar Usuario</h2>

<form action="/usuarios/actualizarUsuario" method="post">
    <input type="hidden" name="id" value="<?= esc($usuario['id']) ?>">

    <div class="mb-3">
        <label for="nombre_usuario" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" 
               value="<?= esc($usuario['nombre_usuario']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="apellidos_usuario" class="form-label">Apellidos</label>
        <input type="text" class="form-control" id="apellidos_usuario" name="apellidos_usuario" 
               value="<?= esc($usuario['apellidos_usuario']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" 
               value="<?= esc($usuario['email']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" 
               value="<?= esc($usuario['telefono']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="user_activo" class="form-label">Activo</label>
        <select class="form-control" id="user_activo" name="user_activo">
            <option value="1" <?= $usuario['user_activo'] == 1 ? 'selected' : '' ?>>Sí</option>
            <option value="0" <?= $usuario['user_activo'] == 0 ? 'selected' : '' ?>>No</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="/usuarios" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection() ?>
