<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<br>
<div style="margin:20px">
    <h2>Mi Perfil</h2>
    <form id="editUserForm" action="<?= base_url('Mi_perfil/save') ?>" method="post" enctype="multipart/form-data"
        style="margin-top:10px">
        <?php $session = \Config\Services::session(); ?>
        <?php if ($session->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $session->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>
        <div class="alert alert-danger" id="imageError" style="display: none;"></div>
        <input type="hidden" id="userId" name="id" value="<?= isset($user) ? $user->id : '' ?>">

        <div class="form-group">
            <label for="username">Nombre usuario</label>
            <input type="text" class="form-control" id="username" name="username"
                value="<?= isset($user) ? $user->username : '' ?>" required autocomplete="off">
        </div>
        <br>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email"
                value="<?= isset($user) ? $user->email : '' ?>" autocomplete="off">
        </div>
        <br>
        <div class="form-group password-container">
            <label for="password">Nueva contraseña</label>
            <input type="password" class="form-control" id="password" name="password"
                placeholder="Mínimo 8 caracteres, incluyendo mayúsculas, minúsculas y números">
            <div class="form-group password-container">

                <div id="eye-icon" onclick="togglePassword()">
                    <!-- Ojo cerrado -->
                    <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon-slash" width="20" height="20"
                        fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                        <path
                            d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z" />
                        <path
                            d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829" />
                        <path
                            d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z" />
                    </svg>
                    <!-- Ojo abierto-->
                    <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon-open" width="20" height="20"
                        fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="display:none;">
                        <path
                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                        <path
                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                    </svg>
                </div>
            </div>
            <br>
            <br>
            <div class="form-group">
                <label for="userfoto">Imagen</label>
                <br>
                <?php if (isset($user->imagePath) && file_exists($user->imagePath)): ?>
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
                        <input type="file" class="form-control-file" id="userfoto" name="userfoto" style="display: none;">
                        <label for="userfoto" class="btn boton volverButton">
                            Seleccionar Archivo
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-paperclip" viewBox="0 0 16 16">
                                <path
                                    d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z" />
                            </svg>
                        </label>
                        <button type="button" id="restoreImage" class="btn boton"
                            style="margin-left: 20px !important; height:40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <g clip-path="url(#clip0_2485_321)">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M9.77589 4.20044C11.0778 4.20094 12.3445 4.62322 13.3862 5.40402C14.428 6.18483 15.1888 7.28213 15.5546 8.53158C15.9204 9.78102 15.8716 11.1154 15.4154 12.3347C14.9593 13.5541 14.1203 14.5929 13.0243 15.2954C11.9282 15.998 10.6341 16.3266 9.3356 16.2319C8.03715 16.1373 6.80431 15.6245 5.82174 14.7704C4.83917 13.9163 4.15976 12.7668 3.88527 11.4942C3.61078 10.2215 3.75598 8.89424 4.29913 7.71106C4.35825 7.5672 4.35944 7.40606 4.30245 7.26135C4.24546 7.11664 4.13471 6.99959 3.99337 6.93468C3.85204 6.86977 3.69108 6.86204 3.54417 6.91311C3.39727 6.96418 3.27582 7.07009 3.20523 7.20868C2.55347 8.62857 2.3793 10.2214 2.70879 11.7486C3.03828 13.2758 3.8537 14.6551 5.03291 15.68C6.21212 16.7049 7.69164 17.3201 9.24985 17.4335C10.8081 17.5469 12.3611 17.1525 13.6763 16.3092C14.9915 15.4659 15.9981 14.2192 16.5453 12.7558C17.0924 11.2925 17.1508 9.6912 16.7115 8.19188C16.2723 6.69257 15.3591 5.37592 14.1088 4.43916C12.8584 3.5024 11.3382 2.99596 9.77589 2.9957V4.20044Z"
                                        fill="white" />
                                    <path
                                        d="M9.77587 5.96658V1.22954C9.77585 1.17231 9.75952 1.11627 9.7288 1.06798C9.69808 1.0197 9.65424 0.981164 9.60241 0.956893C9.55059 0.932623 9.49292 0.923621 9.43616 0.930941C9.3794 0.938262 9.3259 0.961602 9.28193 0.998229L6.43874 3.36675C6.40484 3.39502 6.37757 3.43039 6.35886 3.47036C6.34014 3.51033 6.33044 3.55393 6.33044 3.59806C6.33044 3.6422 6.34014 3.68579 6.35886 3.72576C6.37757 3.76573 6.40484 3.8011 6.43874 3.82937L9.28193 6.19789C9.3259 6.23452 9.3794 6.25786 9.43616 6.26518C9.49292 6.2725 9.55059 6.2635 9.60241 6.23923C9.65424 6.21496 9.69808 6.17642 9.7288 6.12814C9.75952 6.07985 9.77585 6.02381 9.77587 5.96658Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_2485_321">
                                        <rect width="19.2759" height="19.2759" fill="white"
                                            transform="translate(0.137939 0.586212)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>
                <?php else: ?>
                    <input type="file" class="form-control-file" id="userfoto" name="userfoto" style="display: none;">
                    <label for="userfoto" class="btn btn-primary custom-file-upload">
                        Seleccionar Archivo
                    </label>
                <?php endif; ?>
            </div>
            <div class="modal-footer buttonsEditProductProveedAbajo">
                <a href="<?= base_url('') ?>" class="boton volverButton">Volver
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M19.5 13C19.5 13.2155 19.4144 13.4221 19.262 13.5745C19.1096 13.7269 18.903 13.8125 18.6875 13.8125H9.27386L12.7627 17.2997C12.8383 17.3753 12.8982 17.465 12.9391 17.5637C12.98 17.6624 13.001 17.7682 13.001 17.875C13.001 17.9818 12.98 18.0876 12.9391 18.1863C12.8982 18.285 12.8383 18.3747 12.7627 18.4502C12.6872 18.5258 12.5975 18.5857 12.4988 18.6266C12.4001 18.6675 12.2943 18.6885 12.1875 18.6885C12.0807 18.6885 11.9749 18.6675 11.8762 18.6266C11.7775 18.5857 11.6878 18.5258 11.6122 18.4502L6.73724 13.5752C6.66157 13.4998 6.60154 13.4101 6.56058 13.3114C6.51962 13.2127 6.49854 13.1069 6.49854 13C6.49854 12.8931 6.51962 12.7873 6.56058 12.6886C6.60154 12.5899 6.66157 12.5002 6.73724 12.4247L11.6122 7.54974C11.7648 7.39717 11.9717 7.31146 12.1875 7.31146C12.4032 7.31146 12.6102 7.39717 12.7627 7.54974C12.9153 7.7023 13.001 7.90923 13.001 8.12499C13.001 8.34075 12.9153 8.54767 12.7627 8.70024L9.27386 12.1875H18.6875C18.903 12.1875 19.1096 12.2731 19.262 12.4255C19.4144 12.5778 19.5 12.7845 19.5 13Z"
                            fill="white" />
                    </svg>
                </a>
                <button type="submit" class="boton btnGuardar  float-end" id="guardarModal">Guardar Cambios
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27" fill="none">
                        <path
                            d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                            fill="white" />
                    </svg>
                </button>
            </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        var id = window.location.pathname.split('/').pop();
        if (id) {
            $.get('<?= base_url('Mi_perfil/getUserData/') ?>' + id, function (data) {
                $('#nombre_usuario').val(data.username);
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#userId').val(id);
                $('#editUserForm').attr('action', '<?= base_url('Mi_perfil/save/') ?>' + id);

                if (data.userfoto) {
                    $('#userfotoPreview').attr('src', '<?= base_url() ?>/' + data.userfoto).show();
                }
            }).fail(function (error) {
                console.error("Error al obtener los datos del usuario", error);
            });
        }

        var maxSize = 1 * 1024 * 1024; // 1MB
        var allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        $('#userfoto').on('change', function () {
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

        $('#deleteImage').on('click', function () {
            $('#imageSection').hide();
            $('#newImageInput').show();
            $('<input>').attr({
                type: 'hidden',
                name: 'deleteImage',
                value: '1'
            }).appendTo('#editUserForm');
        });

        $('#restoreImage').on('click', function () {
            $('#newImageInput').hide();
            $('#imageSection').show();
            $('input[name="deleteImage"]').remove();
        });
    });

    function togglePassword() {
        var passwordField = document.getElementById("password");
        var eyeIconSlash = document.getElementById("eye-icon-slash");
        var eyeIconOpen = document.getElementById("eye-icon-open");

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIconSlash.style.display = "none";
            eyeIconOpen.style.display = "inline";
        } else {
            passwordField.type = "password";
            eyeIconSlash.style.display = "inline";
            eyeIconOpen.style.display = "none";
        }
    }
</script>

<?= $this->endSection() ?>