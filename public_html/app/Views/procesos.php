<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>


<div class="modal fade" id="processListModal" tabindex="-1" role="dialog" aria-labelledby="processListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="processListModalLabel">Lista de Procesos</h5>
                <button type="button" class="btn-close" aria-label="Close" onclick="window.location.href='<?= base_url() ?>'"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <a href="<?= base_url('procesos/add'); ?>" class="btn btn-success btn-sm"> + Añadir</a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre del Proceso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($procesos as $proceso): ?>
                            <tr>
                                <td><?= esc($proceso['nombre_proceso']); ?></td>
                                <td>
                                    <a href="<?= base_url('procesos/restriccion/' . $proceso['id_proceso']); ?>" class="btn btn-secondary btn-sm text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.854a.5.5 0 0 1 .708 0l2.292 2.292a.5.5 0 0 1 0 .708l-9.29 9.29-.706.354a1 1 0 0 1-.262.104l-3 1a1 1 0 0 1-1.26-1.26l1-3a1 1 0 0 1 .104-.262l.354-.706 9.29-9.29zM11.207 3L13 4.793 14.207 3.5 12.5 1.793 11.207 3zm1.586 1.5L10.5 4.707 4.707 10.5 3.5 12.207 1.793 10.5 3 9.293l5.793-5.793L11.5 4.793zm-7.5 7.793L2 13.207l.207-1.793L3.707 11l-1.293 1.293z" />
                                        </svg>
                                    </a>

                                    <a href="<?= base_url('procesos/delete/' . $proceso['id_proceso']); ?>" class="btn btn-danger btn-sm text-white" onclick="return confirm('¿Estás seguro de eliminar este proceso?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                        </svg>
                                    </a>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Muestra el modal cuando se carga la página
        $('#processListModal').modal('show');

        // Cierra el modal y redirige al inicio si se cierra
        $('#processListModal').on('hidden.bs.modal', function(e) {
            window.location.href = '<?= base_url() ?>';
        });
    });
</script>

<?= $this->endSection() ?>