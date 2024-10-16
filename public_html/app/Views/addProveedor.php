<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/proveedor.css') ?>?v=<?= time() ?>">
<h2 class="titleAddProveedor">Añadir Proveedor</h2>
<form action="<?= base_url('proveedores/save') ?>" method="post" enctype="multipart/form-data" class="fromAddProveedor">
    <div class="mb-3">
        <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
        <input type="text" name="nombre_proveedor" id="nombre_proveedor" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="nif" class="form-label">NIF</label>
        <input type="text" name="nif" id="nif" class="form-control">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control">
    </div>
    <div class="mb-3">
        <label for="telf" class="form-label">Teléfono</label>
        <input type="text" name="telf" id="telf" class="form-control">
    </div>
    <div class="mb-3">
        <label for="contacto" class="form-label">Contacto</label>
        <input type="text" name="contacto" id="contacto" class="form-control">
    </div>
    <div class="mb-3">
        <label for="direccion" class="form-label">Dirección</label>
        <input type="text" name="direccion" id="direccion" class="form-control">
    </div>
    <div class="mb-3">
        <label for="pais" class="form-label">País</label>
        <select name="pais" id="pais" class="form-select">
            <option value="">Selecciona un país</option>
            <?php foreach ($paises as $pais): ?>
                <option value="<?= $pais['id'] ?>"><?= $pais['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="id_provincia" class="form-label">Provincia</label>
        <select name="id_provincia" id="id_provincia" class="form-select">
            <option value="">Selecciona una provincia</option>
            <?php foreach ($provincias as $provincia): ?>
                <option value="<?= $provincia['id_provincia'] ?>"><?= $provincia['provincia'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="poblacion" class="form-label">Población</label>
        <input type="text" name="poblacion" id="poblacion" class="form-control">
    </div>
    <div class="mb-3">
        <label for="observaciones_proveedor" class="form-label">Observaciones</label>
        <textarea name="observaciones_proveedor" id="observaciones_proveedor" class="form-control" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="web" class="form-label">Sitio Web</label>
        <input type="text" name="web" id="web" class="form-control">
    </div>
    <div class="buttonsAddProveedor">
        <button type="submit" class="btn btn-primary saveProveedor">Añadir Proveedor</button>
        <button type="button" class="btn btn-info mb-3" id="volverButton" style="margin-top:15px;">Volver</button>
    </div>
</form>
<script>
    document.getElementById('volverButton').addEventListener('click', function() {
        window.location.href = '<?= base_url('proveedores') ?>';
    });
</script>
<?= $this->endSection() ?>