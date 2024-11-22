<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Editar Datos de Acceso</h2>
    <form id="editUserForm" action="<?= base_url('password/save/' . $user['id']) ?>" method="post">
        <input type="hidden" id="userId" name="id" value="<?= esc($user['id']) ?>">

        <!-- Campo para nombre_usuario -->
        <div class="form-group">
            <label for="nombre_usuario">Nombre</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario"
                value="<?= esc($user['nombre_usuario']) ?>" readonly>
        </div>
        <br>

        <!-- Campo para username -->
        <div class="form-group">
            <label for="username">Nombre de usuario</label>
            <input type="text" class="form-control" id="username" name="username"
                value="<?= esc($user['username'] ?? '') ?>" required>
        </div>
        <br>

        <!-- Campo para email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email'] ?? '') ?>">
        </div>
        <br>

        <!-- Campo para password -->
        <div class="form-group">
            <label for="password">Nueva contraseña</label>
            <input type="password" class="form-control" id="password" name="password"
                placeholder="Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números">
        </div>
        <br>

        <!-- Select para niveles de acceso -->
        <div class="form-group">
            <label for="nivel_acceso">Nivel de acceso</label>
            <select class="form-control" id="nivel_acceso" name="nivel_acceso" required>
                <option value="">Seleccione un nivel de acceso</option>
                <?php foreach ($niveles_acceso as $nivel): ?>
                    <option value="<?= esc($nivel['id_nivel']) ?>" <?= $nivel['id_nivel'] == ($nivel_usuario ?? '') ? 'selected' : '' ?>>
                        <?= esc($nivel['nombre_nivel']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
<?= $this->endSection() ?>