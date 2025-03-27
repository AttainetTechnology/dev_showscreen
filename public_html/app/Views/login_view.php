<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ATTAINET CRM</title>
    <!-- Attainet partes CSS -->
    <? $ahora = time();
    $validation = \Config\Services::validation();
    ?>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 loginform">
                <img src="<?php
                echo $logo
                    ?>" class="logo_entrada">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>


                <?= \Config\Services::validation()->listErrors() ?>
                <!-- Llamo al controlador Verifylogin index.php/Verifylogin -->
                <?php echo form_open(base_url('Verifylogin')); ?>
                <fieldset>
                    <div class="form-group">
                        <input class="form-control" id="username" placeholder="Usuario" name="username" type="text"
                            autofocus autocomplete="off">
                        <div class="password-container">
                            <input class="form-control" id="password" placeholder="Password" name="password"
                                type="password" value="">
                            <!-- Icono de visibilidad de la contraseña -->
                            <div id="eye-icon" onclick="togglePassword()">
                                <!-- Ojo cerrado (por defecto) -->
                                <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon-slash" width="20" height="20"
                                    fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                                    <path
                                        d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z" />
                                    <path
                                        d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829" />
                                    <path
                                        d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z" />
                                </svg>
                                <!-- Ojo abierto (cuando se activa) -->
                                <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon-open" width="20" height="20"
                                    fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16" style="display:none;">
                                    <path
                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                    <path
                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div id="botones">
                        <input type="submit" value="Acceder" class="btn btn-lg btn-success btn-block" />
                        <a href="javascript:void(0);" onclick="openGoogleLoginWindow();"
                            class="btn btn-lg btn-danger btn-block"> Acceder con Google</a>
                    </div>
                    <script>
                        function openGoogleLoginWindow() {
                            var googleLoginWindow = window.open('<?= base_url() ?>/login/google_login', 'GoogleLogin', 'width=500,height=500');

                            // Función disponible para la ventana emergente
                            window.closeGoogleLoginWindow = function () {
                                googleLoginWindow.close();
                            };

                            var googleLoginCheck = setInterval(function () {
                                if (googleLoginWindow.closed) {
                                    clearInterval(googleLoginCheck);
                                    // Actualizar la página principal después del cierre
                                    location.reload();
                                }
                            }, 100);
                        }
                    </script>
                </fieldset>
                </form>
            </div>
        </div>
    </div>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }

        .col-md-4.col-md-offset-4.loginform {
            margin: auto;
            padding-top: 20vh;
            text-align: center;
            max-width: 400px;
        }

        img.logo_entrada {
            margin-bottom: 30px;
        }

        .loginform .form-group input {
            margin-bottom: 10px;
        }

        #botones {
            margin-top: 20px;
        }

        .password-container {
            position: relative;
        }

        /* Estilo para el icono dentro del campo de contraseña */
        .password-container svg {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</body>

<script>
    function togglePassword() {
        var passwordField = document.getElementById('password');
        var eyeIconSlash = document.getElementById('eye-icon-slash');
        var eyeIconOpen = document.getElementById('eye-icon-open');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';  // Muestra la contraseña
            eyeIconSlash.style.display = 'none'; // Oculta el ojo cerrado
            eyeIconOpen.style.display = 'block'; // Muestra el ojo abierto
        } else {
            passwordField.type = 'password';  // Oculta la contraseña
            eyeIconOpen.style.display = 'none'; // Oculta el ojo abierto
            eyeIconSlash.style.display = 'block'; // Muestra el ojo cerrado
        }
    }
</script>

</html>