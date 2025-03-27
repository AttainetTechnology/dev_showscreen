<!-- Cargar jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Cargar Bootstrap 5 sin jQuery -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Modal para añadir una nueva ruta -->
<div class="modal fade" id="addRutaModal" tabindex="-1" aria-labelledby="addRutaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRutaModalLabel">Añadir Nueva Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Aquí va el contenido del formulario para añadir una nueva ruta -->
                <form id="addRutaForm" method="post" action="<?= site_url('ruta_pedido/addRuta') ?>">
                    <div class="mb-3">
                        <label for="poblacion" class="form-label">Población</label>
                        <input type="text" class="form-control" id="poblacion" name="poblacion" required>
                    </div>
                    <div class="mb-3">
                        <label for="lugar" class="form-label">Lugar</label>
                        <input type="text" class="form-control" id="lugar" name="lugar" required>
                    </div>
                    <div class="mb-3">
                        <label for="recogida_entrega" class="form-label">Recogida/Entrega</label>
                        <select class="form-select" id="recogida_entrega" name="recogida_entrega">
                            <option value="1">Recogida</option>
                            <option value="2">Entrega</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="transportista" class="form-label">Transportista</label>
                        <select class="form-select" id="transportista" name="transportista">
                            <?php foreach ($transportistas as $id => $nombre): ?>
                                <option value="<?= esc($id) ?>"><?= esc($nombre) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_ruta" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fecha_ruta" name="fecha_ruta" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Ruta</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
