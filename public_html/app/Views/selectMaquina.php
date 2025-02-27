<?php //print_r($presentes); ?>
<?= $cabecera; ?>

<body class="page-select" onload="startTime()">
    <?= $hora; ?>
    <div class="fondo-select">
        <div class="cabecera2">
            <h2><?= $usuario['nombre_usuario']; ?> <?= $usuario['apellidos_usuario']; ?></h2>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 columna1">
                    <form action="<?= site_url('selectMaquina') ?>" method="POST">
                        <table class="maquina table table-bordered">
                            <thead>
                                <tr>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($maquinas as $maquina): ?>
                                    <tr>
                                        <td>
                                            <button type="submit" name="id_maquina" value="<?= $maquina['id_maquina'] ?>"
                                                class="btn btn-light btn-block"><?= $maquina['nombre'] ?></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-8 columna2">
                    <?php if (isset($procesos) && !empty($procesos)): ?>
                        <h2>Procesos en <?= $nombreMaquinaSeleccionada ?></h2>
                        <table class="procesos table  table-responsive">
                            <thead>
                                <tr>
                                    <th>Acción</th>
                                    <th>Proceso</th>
                                    <th>Producto</th>
                                    <th>Observaciones</th>
                                    <th>Nº de Piezas</th>
                                    <th>Nombre Base</th>
                                    <th>Med. Inicial</th>
                                    <th>Med. Final</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($procesos as $proceso): ?>
                                    <tr>
                                        <td>
                                            <form action="<?= site_url('seleccionarProceso') ?>" method="POST">
                                                <input type="hidden" name="id_linea_pedido"
                                                    value="<?= $proceso['id_linea_pedido'] ?>">
                                                <input type="hidden" name="id_proceso_pedido"
                                                    value="<?= $proceso['id_relacion'] ?>">
                                                <input type="hidden" name="id_pedido" value="<?= $proceso['id_pedido'] ?>">
                                                <input type="hidden" name="id_maquina" value="<?= $idMaquina ?>">
                                                <button type="submit" class="btn boton btnAdd">Seleccionar
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27"
                                                        viewBox="0 0 26 27" fill="none">
                                                        <path
                                                            d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z"
                                                            fill="white" />
                                                    </svg>

                                                </button>
                                            </form>
                                        </td>
                                        <td class="nombre_proceso"><?= $proceso['nombre_proceso'] ?></td>
                                        <td>
                                            <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto" width="100">
                                            <br>
                                            <strong><?= $proceso['nombre_producto'] ?></strong>
                                        </td>
                                        <td><?= $proceso['observaciones'] ?></td>
                                        <td><?= $proceso['n_piezas'] ?></td>
                                        <td><?= $proceso['nom_base'] ?></td>
                                        <td><?= $proceso['med_inicial'] ?></td>
                                        <td><?= $proceso['med_final'] ?></td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif (!isset($idMaquina) || $idMaquina == null): ?>
                        <h2>Procesos asociados</h2>
                        <table class="procesos table table-bordered">
                            <thead>
                                <tr>
                                    <th>Acción</th>
                                    <th>Proceso</th>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Observaciones</th>
                                    <th>Nº de Piezas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($procesosUsuario)): ?>
                                    <?php foreach ($procesosUsuario as $proceso): ?>
                                        <tr>
                                            <td>
                                                <a href="<?= site_url('editarProceso/' . $proceso['id']) ?>"
                                                    class="btn boton  btnEditar">Editar
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16"
                                                        viewBox="0 0 15 16" fill="none">
                                                        <path
                                                            d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z"
                                                            fill="white" />
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z"
                                                            fill="white" />
                                                    </svg></a>
                                            </td>
                                            <td class="nombre_proceso"><?= $proceso['nombre_proceso'] ?></td>
                                            <td><?= $proceso['nombre_cliente'] ?></td>
                                            <td>
                                                <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto" width="100">
                                                <br>
                                                <strong><?= $proceso['nombre_producto'] ?></strong>
                                            </td>
                                            <td><?= $proceso['observaciones'] ?></td>
                                            <td><?= $proceso['n_piezas'] ?></td>

                                        </tr>
                                    <?php endforeach; ?>

                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">NO TIENES PROCESOS ACTIVOS</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    <?php endif; ?>
<br>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="<?= site_url('salir/' . $usuario['id']); ?>" class="btn volverButton">
                            <span class="glyphicon glyphicon-arrow-left"></span> Volver
                        </a>
                        <button data-action="cancelar" onclick="window.location.reload();" class="btn btnRecarga "
                            data-bs-toggle="tooltip" title="Recargar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <g clip-path="url(#clip0_2485_250)">
                                    <path
                                        d="M14.0334 9.0194H18.7705C18.8277 9.01942 18.8837 9.03575 18.932 9.06647C18.9803 9.09719 19.0188 9.14103 19.0431 9.19286C19.0674 9.24468 19.0764 9.30235 19.069 9.35911C19.0617 9.41587 19.0384 9.46937 19.0018 9.51335L16.6332 12.3565C16.605 12.3904 16.5696 12.4177 16.5296 12.4364C16.4897 12.4551 16.4461 12.4648 16.4019 12.4648C16.3578 12.4648 16.3142 12.4551 16.2742 12.4364C16.2343 12.4177 16.1989 12.3904 16.1706 12.3565L13.8021 9.51335C13.7655 9.46937 13.7421 9.41587 13.7348 9.35911C13.7275 9.30235 13.7365 9.24468 13.7608 9.19286C13.785 9.14103 13.8236 9.09719 13.8719 9.06647C13.9201 9.03575 13.9762 9.01942 14.0334 9.0194ZM0.781251 11.4289H5.5183C5.57552 11.4289 5.63156 11.4125 5.67985 11.3818C5.72814 11.3511 5.76667 11.3073 5.79094 11.2554C5.81521 11.2036 5.82421 11.1459 5.81689 11.0892C5.80957 11.0324 5.78623 10.9789 5.74961 10.9349L3.38108 8.09175C3.35282 8.05786 3.31745 8.03059 3.27748 8.01187C3.2375 7.99316 3.19391 7.98346 3.14977 7.98346C3.10564 7.98346 3.06204 7.99316 3.02207 8.01187C2.9821 8.03059 2.94673 8.05786 2.91846 8.09175L0.549941 10.9349C0.513314 10.9789 0.489974 11.0324 0.482653 11.0892C0.475333 11.1459 0.484335 11.2036 0.508605 11.2554C0.532876 11.3073 0.571411 11.3511 0.619697 11.3818C0.667983 11.4125 0.724022 11.4289 0.781251 11.4289Z"
                                        fill="white" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M9.77585 4.20043C7.9061 4.20043 6.23392 5.05218 5.12917 6.39065C5.07984 6.45459 5.01815 6.50796 4.94778 6.54758C4.87741 6.5872 4.79979 6.61227 4.71954 6.62129C4.63929 6.63032 4.55804 6.62311 4.48063 6.6001C4.40322 6.5771 4.33123 6.53876 4.26893 6.48737C4.20663 6.43598 4.15531 6.37259 4.118 6.30097C4.08069 6.22934 4.05817 6.15095 4.05177 6.07044C4.04537 5.98994 4.05522 5.90897 4.08074 5.83235C4.10625 5.75573 4.14692 5.68503 4.20031 5.62444C5.08241 4.55647 6.2527 3.76433 7.57198 3.34224C8.89126 2.92015 10.304 2.88588 11.6422 3.24349C12.9804 3.60111 14.1877 4.33556 15.1206 5.3595C16.0534 6.38344 16.6726 7.65377 16.9043 9.0194H15.6791C15.4014 7.65881 14.6621 6.43596 13.5863 5.55781C12.5106 4.67966 11.1645 4.20015 9.77585 4.20043ZM3.87262 11.4289C4.10069 12.5428 4.63926 13.5695 5.42611 14.3903C6.21296 15.2111 7.21597 15.7926 8.31928 16.0675C9.42258 16.3424 10.5812 16.2996 11.6611 15.9439C12.7411 15.5883 13.6985 14.9343 14.4225 14.0576C14.4719 13.9937 14.5336 13.9403 14.6039 13.9007C14.6743 13.8611 14.7519 13.836 14.8322 13.827C14.9124 13.818 14.9937 13.8252 15.0711 13.8482C15.1485 13.8712 15.2205 13.9095 15.2828 13.9609C15.3451 14.0123 15.3964 14.0757 15.4337 14.1473C15.471 14.2189 15.4935 14.2973 15.4999 14.3778C15.5063 14.4583 15.4965 14.5393 15.471 14.6159C15.4455 14.6925 15.4048 14.7633 15.3514 14.8238C14.4693 15.8918 13.299 16.6839 11.9797 17.106C10.6604 17.5281 9.2477 17.5624 7.9095 17.2048C6.5713 16.8472 5.36398 16.1127 4.43113 15.0888C3.49828 14.0648 2.87915 12.7945 2.6474 11.4289H3.87262Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_2485_250">
                                        <rect width="19.2759" height="19.2759" fill="white"
                                            transform="translate(0.137924 0.586182)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    </div>
                </div>
                <script>
                    window.history.replaceState({}, document.title, "<?= base_url('selectMaquina'); ?>");
                </script>
            </div>
        </div>