<form id="editEmpresaForm">
    <input type="hidden" id="edit_id_cliente" name="id_cliente" value="<?= $empresa['id_cliente'] ?>">
    <div class="modal-header">
        <h5 class="modal-title">Editar Empresa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <div class="modal-body">
        <div class="mb-3">
            <label for="edit_nombre_cliente" class="form-label">Nombre Cliente</label>
            <input type="text" class="form-control" id="edit_nombre_cliente" name="nombre_cliente" value="<?= $empresa['nombre_cliente'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="edit_nif" class="form-label">NIF</label>
            <input type="text" class="form-control" id="edit_nif" name="nif" value="<?= $empresa['nif'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="edit_direccion" class="form-label">Dirección</label>
            <input type="text" class="form-control" id="edit_direccion" name="direccion" value="<?= $empresa['direccion'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_pais" class="form-label">País</label>
            <input type="text" class="form-control" id="edit_pais" name="pais" value="<?= $empresa['pais'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_id_provincia" class="form-label">Provincia</label>
            <select class="form-control" id="edit_id_provincia" name="id_provincia">
                <option value="">Seleccione una provincia</option>
                <?php foreach ($provincias as $provincia): ?>
                    <option value="<?= $provincia['id_provincia'] ?>" <?= $empresa['id_provincia'] == $provincia['id_provincia'] ? 'selected' : '' ?>>
                        <?= $provincia['provincia'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer buttonsEditProductProveedAbajo">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="actualizarEmpresa()">Guardar Cambios</button>
    </div>
</form>

<script>
function actualizarEmpresa() {
    var formData = $('#editEmpresaForm').serialize();
    $.ajax({
        url: '<?= base_url("empresas/actualizar") ?>',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                $('#editModal').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('Error en la solicitud.');
        }
    });
}
</script>
