<style>
    .modal-title {
        margin-left: 20px;
        margin-top: 10px;
    }
</style>
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModal">Editar usuario</h5>
                <button type="button" class="btn-close-custom" aria-label="Close"
                    onclick="window.location.href='<?= base_url('/Rutas_transporte/rutas') ?>'">
                    &times;
                </button>
            </div>
            <div class="modal-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                <div class="alert alert-danger" style="display: none;"></div>
                <form id="editTransportForm" action="<?= base_url('Rutas_transporte/save') ?>" method="post"
                    enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />
                    <div class="form-group">
                        <label for="username">Nombre de usuario</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?= old('username', isset($user) ? $user->username : '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?= old('email', isset($user) ? $user->email : '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Nueva contraseña</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números"
                            autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label for="userfoto">Imagen de usuario</label>
                        <?php
                        $maxSize = 1 * 1024 * 1024; // 1MB
                        if (isset($user->imagePath) && file_exists($user->imagePath) && filesize($user->imagePath) <= $maxSize): ?>
                            <div id="imageSection">
                                <img id="userfotoPreview" src="<?= base_url($user->imagePath) ?>" alt="UserFoto"
                                    style="width: 50px; height: auto;">
                                <button type="button" id="deleteImage" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-trash" viewBox="0 0 16 16">
                                        <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                        <path
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                    </svg>
                                </button>
                            </div>
                            <div id="newImageInput" class="hidden" style="display: none;">
                                <input type="file" class="form-control-file" id="userfoto" name="userfoto"
                                    style="display: none;">
                                <label for="userfoto" class="btn btn-primary custom-file-upload">
                                    Seleccionar Archivo
                                </label>
                                <button type="button" id="restoreImage" class="btn btn-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                        <path
                                            d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41m-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9" />
                                        <path fill-rule="evenodd"
                                            d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5 5 0 0 0 8 3M3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9z" />
                                    </svg>
                                </button>
                            </div>
                        <?php else: ?>
                            <input type="file" class="form-control-file" id="userfoto" name="userfoto"
                                style="display: none;">
                            <label for="userfoto" class="btn btn-primary custom-file-upload">
                                Seleccionar Archivo
                            </label>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="boton btnAdd float-end">Guardar Cambios
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27" fill="none">
                            <path
                            d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                            fill="white" />
                        </svg></button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('editTransportForm');
        var passwordInput = document.getElementById('password');
        var errorMessageContainer = document.querySelector('.alert-danger');

        form.addEventListener('submit', function (event) {
            var password = passwordInput.value;
            var errorMessage = '';

            if (password.length > 0) {
                if (password.length < 8) {
                    errorMessage = 'La contraseña debe tener al menos 8 caracteres.';
                } else if (!/[A-Z]/.test(password)) {
                    errorMessage = 'La contraseña debe tener al menos una letra mayúscula.';
                } else if (!/[a-z]/.test(password)) {
                    errorMessage = 'La contraseña debe tener al menos una letra minúscula.';
                } else if (!/[0-9]/.test(password)) {
                    errorMessage = 'La contraseña debe tener al menos un número.';
                }
            }

            if (errorMessage) {
                event.preventDefault();
                errorMessageContainer.style.display = 'block';
                errorMessageContainer.innerHTML = errorMessage;
            }
        });

        var userFotoElement = document.getElementById('userfoto');
        if (userFotoElement) {
            userFotoElement.addEventListener('change', function () {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var userFotoPreview = document.getElementById('userfotoPreview');
                    if (userFotoPreview) {
                        userFotoPreview.src = e.target.result;
                    }
                }
                reader.readAsDataURL(this.files[0]);

                var maxSize = 1 * 1024 * 1024; // 1MB
                if (this.files[0].size > maxSize) {
                    alert('La imagen es demasiado grande. El tamaño máximo permitido es 1MB.');
                    this.value = ''; // Limpiar el campo de archivo
                }
            });
        }

        var errorMessage = <?= json_encode(session()->getFlashdata('error')) ?>;
        if (errorMessage) {
            errorMessageContainer.style.display = 'block';
            errorMessageContainer.innerHTML = errorMessage;
        }

        $('#deleteImage').on('click', function () {
            $('#imageSection').hide();
            $('#newImageInput').show();
            $('<input>').attr({
                type: 'hidden',
                name: 'deleteImage',
                value: '1'
            }).appendTo('#editTransportForm');
        });

        $('#restoreImage').on('click', function () {
            $('#newImageInput').hide();
            $('#imageSection').show();
            $('input[name="deleteImage"]').remove();
        });
    });
</script>