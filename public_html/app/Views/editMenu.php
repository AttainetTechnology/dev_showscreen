<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <h2>Editar Menú</h2>

    <form action="<?= base_url('menu/update/' . $menu['id']) ?>" method="POST">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="posicion">Posición</label>
            <input type="number" class="form-control" id="posicion" name="posicion" value="<?= old('posicion', $menu['posicion']) ?>" required>
        </div>

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="<?= old('titulo', $menu['titulo']) ?>" required>
        </div>

        <div class="form-group">
            <label for="enlace">Enlace</label>
            <input type="text" class="form-control" id="enlace" name="enlace" value="<?= old('enlace', $menu['enlace']) ?>">
        </div>

        <div class="form-group">
            <label for="nivel">Nivel</label>
            <select class="form-control" id="nivel" name="nivel" required>
                <?php foreach ($niveles_acceso as $nivel): ?>
                    <option value="<?= $nivel->id_nivel ?>" <?= $nivel->id_nivel == $menu['nivel'] ? 'selected' : '' ?>>
                        <?= $nivel->nombre_nivel ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="activo">Activo</label>
            <select class="form-control" id="activo" name="activo">
                <option value="1" <?= $menu['activo'] == '1' ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= $menu['activo'] == '0' ? 'selected' : '' ?>>Desactivado</option>
            </select>
        </div>

        <div class="form-group">
            <label for="nueva_pestana">Abrir en nueva pestaña?</label>
            <select class="form-control" id="nueva_pestana" name="nueva_pestana">
                <option value="0" <?= $menu['nueva_pestana'] == '0' ? 'selected' : '' ?>>No</option>
                <option value="1" <?= $menu['nueva_pestana'] == '1' ? 'selected' : '' ?>>Sí</option>
            </select>
        </div>

        <div class="form-group">
            <label for="url_especial">¿URL personalizada?</label>
            <select class="form-control" id="url_especial" name="url_especial">
                <option value="0" <?= $menu['url_especial'] == '0' ? 'selected' : '' ?>>No, URL genérica</option>
                <option value="1" <?= $menu['url_especial'] == '1' ? 'selected' : '' ?>>Sí, URL personalizada</option>
            </select>
        </div>

        <div class="form-group">
            <label for="separador">Posición del separador</label>
            <select class="form-control" id="separador" name="separador">
                <option value="">Ninguno</option>
                <option value="arriba" <?= $menu['separador'] == 'arriba' ? 'selected' : '' ?>>Arriba</option>
                <option value="abajo" <?= $menu['separador'] == 'abajo' ? 'selected' : '' ?>>Abajo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>

<?= $this->endSection() ?>
