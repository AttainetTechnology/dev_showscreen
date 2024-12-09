<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="container">
    <h2><?= isset($submenu) ? 'Editar' : 'Añadir' ?> Submenú</h2>

    <form method="post" action="<?= isset($submenu) ? base_url('menu/updateSubmenu/' . $submenu['id']) : base_url('menu/addSubmenu/' . $id) ?>">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" name="titulo" id="titulo" value="<?= isset($submenu) ? $submenu['titulo'] : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="enlace">Enlace</label>
            <input type="text" class="form-control" name="enlace" id="enlace" value="<?= isset($submenu) ? $submenu['enlace'] : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="nivel">Nivel</label>
            <input type="text" class="form-control" name="nivel" id="nivel" value="<?= isset($submenu) ? $submenu['nivel'] : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="activo">Activo</label>
            <select class="form-control" name="activo" id="activo">
                <option value="1" <?= isset($submenu) && $submenu['activo'] == 1 ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= isset($submenu) && $submenu['activo'] == 0 ? 'selected' : '' ?>>Desactivado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary"><?= isset($submenu) ? 'Actualizar' : 'Añadir' ?> Submenú</button>
    </form>
</div>
<?= $this->endSection() ?>
