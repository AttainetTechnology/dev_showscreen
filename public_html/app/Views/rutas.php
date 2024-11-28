<?php
use App\Models\transportistas;

helper('controlacceso');

// Obtener los datos del usuario
$data = datos_user();

// Función para obtener el nombre del transportista por su ID
function obtenerNombreTransportistaPorId($id_transportista)
{
    $data = usuario_sesion();
    $db_cliente = db_connect($data['new_db']);
    $builder = $db_cliente->table('users');
    $builder->select('nombre_usuario, apellidos_usuario');
    $builder->where('id', $id_transportista);
    $query = $builder->get();

    if ($query->getNumRows() > 0) {
        $result = $query->getRow();
        return $result->nombre_usuario . ' ' . $result->apellidos_usuario;
    } else {
        return 'No asignado';
    }
}
?>

<?= $this->extend('layouts/partes') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>?v=<?= time() ?>">
<div id="fondo_rutas">
    <?php $hoy = date("d/m/Y"); ?>
    <br>
    <h1 style="margin-left: 20px;">Rutas de transporte</h1><br>
    <p style="margin-left: 20px;">fecha: <b><?php echo $hoy; ?> </p> </b>
    <?php
    $nueva_poblacion = "";
    // Recorrer todas las rutas
    foreach ($rutas as $r) { ?>
        <?php if ($nueva_poblacion != $r->poblacion) { ?>
            <!-- Mostrar población -->
            <hr>
            <h2 style="margin-left: 20px;">
                <?php echo $r->poblacion;
                $nueva_poblacion = ($r->poblacion); ?>
            </h2>
            <br>
        <?php } ?>
        <div class="col-lg-12">
            <div class="alert <?php if ($r->estado_ruta == '1') { ?>alert-warning<?php } else { ?>panel-default<?php } ?>">
                <div class="panel-heading">
                    <?php $fecha = ($r->fecha_ruta); ?>
                    <!-- Mostrar fecha y detalles de la ruta -->
                    <?php echo date('d/m/Y', strtotime($fecha)); ?> -
                    <?php if ($r->recogida_entrega == '1') { ?>RECOGER<?php } else { ?>ENTREGAR<?php } ?> en:
                    <b><?php echo $r->lugar; ?></b> para
                    <b><?php echo $r->nombre_cliente; ?></b>
                    <?php if ($data['nivel'] > 1) {
                        $transport = obtenerNombreTransportistaPorId($r->transportista);
                        ?>
                        <!-- Mostrar nombre del transportista si el nivel es mayor a 1 -->
                        <br> Transportista: <b><?php echo $transport; ?></b>
                    <?php } ?>
                </div>
                <div class="panel-body">
                    <?php if ($r->estado_ruta == '1') { ?>
                        <!-- Mostrar mensaje si el material no está preparado -->
                        <br>
                        <h3>MATERIAL NO PREPARADO</h3>
                    <?php } else { ?>
                        <!-- Mostrar observaciones y botones de acción -->
                        <?php echo $r->observaciones; ?><br><br>
                        <a href="<?php echo base_url() ?>/Rutas_transporte/entregar_ruta/<?php echo $r->id_ruta; ?>"
                            type="button" class="boton btnRutaFinaliza">Finalizado
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                                <path
                                    d="M13.5046 6.54104C12.7379 6.32819 12.0258 6.97208 11.9745 7.80567C11.9167 8.74167 11.79 9.60108 11.6312 10.1123C11.531 10.4329 11.2471 11.0144 10.7972 11.5719C10.3505 12.1268 9.76911 12.621 9.08825 12.8276C8.54934 12.9906 8 13.5089 8 14.2659V17.8291C8 18.5817 8.54693 19.133 9.16123 19.2051C10.0193 19.3066 10.4155 19.5747 10.8197 19.849L10.8582 19.8757C11.0763 20.0226 11.3217 20.1856 11.6361 20.3067C11.9544 20.4279 12.3265 20.5 12.8117 20.5H15.6186C16.37 20.5 16.9009 20.0752 17.1695 19.5524C17.2994 19.3057 17.3696 19.0259 17.3732 18.7402C17.3732 18.6048 17.3548 18.4624 17.3115 18.327C17.4727 18.0928 17.6162 17.8122 17.7028 17.5246C17.7911 17.2307 17.8408 16.8459 17.706 16.5013C17.7614 16.3855 17.8023 16.2617 17.8336 16.1424C17.8953 15.9019 17.9242 15.6365 17.9242 15.3792C17.9242 15.1227 17.8953 14.8582 17.8336 14.6168C17.8061 14.5056 17.7691 14.3977 17.7229 14.2944C17.8633 14.0726 17.9537 13.8165 17.9863 13.5477C18.019 13.279 17.993 13.0054 17.9105 12.7502C17.7453 12.2229 17.3636 11.7705 16.9482 11.6174C16.2689 11.3662 15.5023 11.3716 14.9305 11.4294C14.8118 11.4413 14.6933 11.4562 14.5752 11.474C14.8523 10.1508 14.8352 8.77257 14.5255 7.45834C14.4714 7.2478 14.3677 7.05712 14.225 6.90603C14.0823 6.75494 13.9059 6.64892 13.7139 6.59893L13.5046 6.54104ZM15.6186 19.6103H12.8117C12.4027 19.6103 12.1196 19.5489 11.8975 19.4643C11.6721 19.3779 11.4917 19.2612 11.2752 19.1143L11.2431 19.0929C10.798 18.791 10.2824 18.4419 9.24624 18.3199C8.97919 18.2878 8.80195 18.0616 8.80195 17.83V14.2659C8.80195 14.0397 8.98319 13.7824 9.29916 13.6871C10.1773 13.4199 10.8846 12.8 11.3955 12.1659C11.9047 11.5336 12.2488 10.8523 12.3883 10.4044C12.5832 9.78097 12.7147 8.82983 12.7748 7.86623C12.7949 7.54384 13.0635 7.33722 13.3097 7.4049L13.5198 7.46368C13.6482 7.49931 13.7267 7.59104 13.7508 7.69078C14.0783 9.07654 14.0379 10.5379 13.6345 11.8988C13.6117 11.9745 13.6076 12.0556 13.6227 12.1336C13.6377 12.2117 13.6714 12.2838 13.7202 12.3424C13.769 12.401 13.8311 12.444 13.8999 12.4669C13.9688 12.4897 14.0419 12.4916 14.1117 12.4723L14.1141 12.4714L14.1253 12.4687L14.1718 12.4563C14.4461 12.3918 14.7235 12.3451 15.0027 12.3165C15.5344 12.263 16.1711 12.2684 16.6948 12.4625C16.8351 12.5142 17.0557 12.7297 17.1519 13.0414C17.2377 13.3157 17.2217 13.6381 16.9386 13.9516L16.6555 14.2659L16.9386 14.5812C16.9731 14.6195 17.0228 14.7068 17.0621 14.8617C17.1006 15.0105 17.1222 15.1912 17.1222 15.3792C17.1222 15.568 17.1006 15.7479 17.0621 15.8975C17.022 16.0524 16.9731 16.1397 16.9386 16.178L16.6555 16.4924L16.9386 16.8077C16.9763 16.8495 17.026 16.9653 16.9426 17.2423C16.8562 17.5111 16.718 17.7558 16.5376 17.9592L16.2545 18.2735L16.5376 18.5888C16.5424 18.5933 16.5705 18.6333 16.5705 18.7402C16.5658 18.8706 16.5325 18.9978 16.4734 19.1107C16.3411 19.3672 16.0701 19.6103 15.6186 19.6103Z"
                                    fill="white" />
                            </svg>
                        </a>
                        <a href="<?php echo base_url() ?>/Rutas_transporte/pendiente_ruta/<?php echo $r->id_ruta; ?>"
                            type="button" class="boton btnRutaNoPreparado">No preparado
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="13" viewBox="0 0 16 13" fill="none">
                                <path
                                    d="M7.75428 1.29186C7.77219 1.2825 7.79233 1.27771 7.81276 1.27793C7.83288 1.27785 7.8527 1.28265 7.87034 1.29186C7.89112 1.30393 7.90816 1.32105 7.91969 1.34147L14.1863 11.497C14.2192 11.5492 14.2183 11.6049 14.1881 11.6563C14.1756 11.6767 14.1588 11.6945 14.1388 11.7085C14.1211 11.72 14.0998 11.7252 14.0785 11.7233H1.54706C1.52575 11.7252 1.50441 11.72 1.48674 11.7085C1.46673 11.6945 1.44994 11.6767 1.43739 11.6563C1.42271 11.632 1.41514 11.6044 1.41546 11.5764C1.41578 11.5484 1.42399 11.521 1.43922 11.497L7.70492 1.34147C7.71646 1.32105 7.73349 1.30393 7.75428 1.29186ZM8.70839 0.900156C8.61792 0.75006 8.48747 0.625356 8.33022 0.538636C8.17297 0.451916 7.99447 0.40625 7.81276 0.40625C7.63105 0.40625 7.45256 0.451916 7.29531 0.538636C7.13805 0.625356 7.00761 0.75006 6.91714 0.900156L0.650521 11.0557C0.232868 11.7329 0.733686 12.5938 1.54614 12.5938H14.0785C14.8909 12.5938 15.3927 11.732 14.9741 11.0557L8.70839 0.900156Z"
                                    fill="white" />
                                <path
                                    d="M6.59375 10.4615C6.59375 10.3415 6.62527 10.2228 6.68652 10.1119C6.74777 10.0011 6.83754 9.9004 6.95071 9.81557C7.06389 9.73075 7.19824 9.66346 7.3461 9.61755C7.49397 9.57164 7.65245 9.54802 7.8125 9.54802C7.97255 9.54802 8.13103 9.57164 8.2789 9.61755C8.42676 9.66346 8.56111 9.73075 8.67429 9.81557C8.78746 9.9004 8.87723 10.0011 8.93848 10.1119C8.99973 10.2228 9.03125 10.3415 9.03125 10.4615C9.03125 10.7038 8.90285 10.9361 8.67429 11.1074C8.44573 11.2788 8.13573 11.375 7.8125 11.375C7.48927 11.375 7.17927 11.2788 6.95071 11.1074C6.72215 10.9361 6.59375 10.7038 6.59375 10.4615ZM6.71319 4.97599C6.69696 4.86075 6.71323 4.74423 6.76096 4.63401C6.80869 4.52378 6.8868 4.42231 6.99023 4.33617C7.09366 4.25003 7.2201 4.18116 7.36133 4.13401C7.50257 4.08686 7.65545 4.0625 7.81006 4.0625C7.96467 4.0625 8.11756 4.08686 8.25879 4.13401C8.40003 4.18116 8.52646 4.25003 8.62989 4.33617C8.73332 4.42231 8.81143 4.52378 8.85916 4.63401C8.90689 4.74423 8.92317 4.86075 8.90694 4.97599L8.48038 8.17961C8.46604 8.30546 8.38922 8.4227 8.26509 8.50813C8.14096 8.59356 7.97858 8.64097 7.81006 8.64097C7.64154 8.64097 7.47916 8.59356 7.35504 8.50813C7.23091 8.4227 7.15408 8.30546 7.13975 8.17961L6.71319 4.97599Z"
                                    fill="white" />
                            </svg>
                        </a>
                    <?php } ?>
                </div>
                <br>
            </div>
        </div>

    <?php } // Fin del foreach de rutas 
    ?>
    <br>
    <?php
    $session = session();
    $session_data = $session->get('logged_in');
    $display = 'none';
    if ($session_data && array_key_exists('nivel', $session_data) && $session_data['nivel'] == 1) {
        $display = 'block';
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="navbar-nav" style="display: flex; gap: 10px;">
                    <!-- Botón de datos de acceso -->
                    <button type="button" id="DatosAcceso" data-bs-toggle="modal" data-bs-target="#editUserModal"
                        style="display: <?= $display ?>;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                            <path fill-rule="evenodd"
                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                        </svg>
                    </button>

                    <!-- Opción para salir -->
                    <a href="<?php echo site_url('/home/logout') ?>" id="BotonSalir" onclick="localStorage.clear();">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                            <path fill-rule="evenodd"
                                d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var navbarToggler = document.querySelector('.navbar-toggler');

            navbarToggler.addEventListener('click', function () {
                setTimeout(function () {
                    window.scrollTo(0, document.body.scrollHeight);
                }, 300);
            });
        });
    </script>

    <?= view('editTransportista') ?>
</div>
<?= $this->endSection() ?>