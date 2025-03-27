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
        <div class="mb-3">
            <label for="edit_poblacion" class="form-label">Población</label>
            <input type="text" class="form-control" id="edit_poblacion" name="poblacion" value="<?= $empresa['poblacion'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_telf" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="edit_telf" name="telf" value="<?= $empresa['telf'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_fax" class="form-label">Fax</label>
            <input type="text" class="form-control" id="edit_fax" name="fax" value="<?= $empresa['fax'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_cargaen" class="form-label">Carga en</label>
            <input type="text" class="form-control" id="edit_cargaen" name="cargaen" value="<?= $empresa['cargaen'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_exportacion" class="form-label">Exportación</label>
            <input type="text" class="form-control" id="edit_exportacion" name="exportacion" value="<?= $empresa['exportacion'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_f_pago" class="form-label">Forma de Pago</label>
            <input type="text" class="form-control" id="edit_f_pago" name="f_pago" value="<?= $empresa['f_pago'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_otros_contactos" class="form-label">Otros Contactos</label>
            <input type="text" class="form-control" id="edit_otros_contactos" name="otros_contactos" value="<?= $empresa['otros_contactos'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="edit_email" name="email" value="<?= $empresa['email'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_web" class="form-label">Web</label>
            <input type="text" class="form-control" id="edit_web" name="web" value="<?= $empresa['web'] ?>">
        </div>
        <div class="mb-3">
            <label for="edit_observaciones_cliente" class="form-label">Observaciones</label>
            <textarea class="form-control" id="edit_observaciones_cliente" name="observaciones_cliente"><?= $empresa['observaciones_cliente'] ?></textarea>
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
