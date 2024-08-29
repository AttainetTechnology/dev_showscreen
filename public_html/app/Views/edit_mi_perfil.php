<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Mi perfil</h5>
                <button type="button" class="btn-close-custom" aria-label="Close" onclick="window.location.href='<?= base_url() ?>'">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="<?= base_url('Mi_perfil/save') ?>" method="post" enctype="multipart/form-data">
                    <?php $session = \Config\Services::session(); ?>
                    <?php if ($session->getFlashdata('error')) : ?>
                        <div class="alert alert-danger">
                            <?= $session->getFlashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="alert alert-danger" id="imageError" style="display: none;"></div>
                    <input type="hidden" id="userId" name="id" value="<?= isset($user) ? $user->id : '' ?>">
                    <div class="form-group">
                        <label for="username">Nombre usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($user) ? $user->username : '' ?>" required autocomplete="off">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($user) ? $user->email : '' ?>" autocomplete="off">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="password">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números" autocomplete="new-password">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="userfoto">Imagen</label>
                        <br>
                        <?php if (isset($user->imagePath) && file_exists($user->imagePath)) : ?>
                            <div id="imageSection">
                                <img id="userfotoPreview" src="<?= base_url($user->imagePath) ?>" alt="UserFoto" style="width: 50px; height: auto;">
                                <button type="button" id="deleteImage" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                    </svg>
                                </button>
                            </div>
                            <div id="newImageInput" class="hidden" style="display: none;">
                                <input type="file" class="form-control-file" id="userfoto" name="userfoto" style="display: none;">
                                <label for="userfoto" class="btn btn-primary custom-file-upload">
                                    Seleccionar Archivo
                                </label>
                                <button type="button" id="restoreImage" class="btn btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9" />
                                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z" />
                                    </svg>
                                </button>
                            </div>
                        <?php else : ?>
                            <input type="file" class="form-control-file" id="userfoto" name="userfoto" style="display: none;">
                            <label for="userfoto" class="btn btn-primary custom-file-upload">
                                Seleccionar Archivo
                            </label>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary float-end" id="guardarModal">Guardar</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        var id = window.location.pathname.split('/').pop();
        if (id) {
            $.get('<?= base_url('Mi_perfil/getUserData/') ?>' + id, function(data) {
                $('#nombre_usuario').val(data.username);
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#userId').val(id);
                $('#editUserForm').attr('action', '<?= base_url('Mi_perfil/save/') ?>' + id);

                if (data.userfoto) {
                    $('#userfotoPreview').attr('src', '<?= base_url() ?>/' + data.userfoto).show();
                }
            }).fail(function(error) {
                console.error("Error al obtener los datos del usuario", error);
            });
        }

        $('#editUserModal').on('hidden.bs.modal', function(e) {
            window.location.href = '<?= base_url() ?>';
        });

        var maxSize = 1 * 1024 * 1024; // 1MB
        var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        $('#userfoto').on('change', function() {
            var file = this.files[0];
            var errorMessage = '';

            if (file.size > maxSize) {
                errorMessage = 'El tamaño de la imagen excede el máximo permitido de 1MB.';
            } else if ($.inArray(file.type, allowedTypes) === -1) {
                errorMessage = 'El formato de la imagen no está permitido. Los formatos permitidos son JPEG, PNG y GIF.';
            }

            if (errorMessage) {
                $('#imageError').text(errorMessage).show();
                this.value = ''; // Limpiar el campo de archivo
            } else {
                $('#imageError').hide();
            }
        });

        $('#deleteImage').on('click', function() {
            $('#imageSection').hide();
            $('#newImageInput').show();
            $('<input>').attr({
                type: 'hidden',
                name: 'deleteImage',
                value: '1'
            }).appendTo('#editUserForm');
        });

        $('#restoreImage').on('click', function() {
            $('#newImageInput').hide();
            $('#imageSection').show();
            $('input[name="deleteImage"]').remove();
        });

        $('#editUserModal').modal('show');
    });
</script>
<?= $this->endSection() ?>