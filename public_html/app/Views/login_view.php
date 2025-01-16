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
                        <input class="form-control" id="password" placeholder="Password" name="password" type="password"
                            value="">
                    </div>
                    <!-- Change this to a button or input when using this as a form -->
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
    </style>
</body>

</html>