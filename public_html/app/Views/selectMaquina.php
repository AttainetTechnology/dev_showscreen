<?php //print_r($presentes); ?>
<?= $cabecera; ?>

<body class="page-select" onload="startTime()">
    <?= $hora; ?>
    <div class="fondo-select">
        <h2><?= $usuario['nombre_usuario']; ?> <?= $usuario['apellidos_usuario']; ?></h2>
        <div class="volver">
            <a href="<?= site_url('salir/' . $usuario['id']); ?>" class="btn volverButton">
                <span class="glyphicon glyphicon-arrow-left"></span> Volver
            </a>
            <button data-action="cancelar" onclick="window.location.reload();" class="btn btnRecarga "
                data-bs-toggle="tooltip" title="Recargar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
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

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <form action="<?= site_url('selectMaquina') ?>" method="POST">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre de la Máquina</th>
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
                <div class="col-md-9">
                    <?php if (isset($procesos) && !empty($procesos)): ?>
                        <h2>Procesos en <?= $nombreMaquinaSeleccionada ?></h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Proceso</th>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Observaciones</th>
                                    <th>Número de Piezas</th>
                                    <th>Nombre Base</th>
                                    <th>Medida Inicial</th>
                                    <th>Medida Final</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($procesos as $proceso): ?>
                                    <tr>
                                        <td><?= $proceso['nombre_proceso'] ?></td>
                                        <td><?= $proceso['nombre_cliente'] ?></td>
                                        <td>
                                            <strong><?= $proceso['nombre_producto'] ?></strong><br>
                                            <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto" width="100">
                                        </td>
                                        <td><?= $proceso['observaciones'] ?></td>
                                        <td><?= $proceso['n_piezas'] ?></td>
                                        <td><?= $proceso['nom_base'] ?></td>
                                        <td><?= $proceso['med_inicial'] ?></td>
                                        <td><?= $proceso['med_final'] ?></td>
                                        <td>
                                            <form action="<?= site_url('seleccionarProceso') ?>" method="POST">
                                                <input type="hidden" name="id_linea_pedido"
                                                    value="<?= $proceso['id_linea_pedido'] ?>">
                                                <input type="hidden" name="id_proceso_pedido"
                                                    value="<?= $proceso['id_relacion'] ?>">
                                                <input type="hidden" name="id_pedido" value="<?= $proceso['id_pedido'] ?>">
                                                <input type="hidden" name="id_maquina" value="<?= $idMaquina ?>">
                                                <button type="submit" class="btn btn-primary">Seleccionar</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php elseif (!isset($idMaquina) || $idMaquina == null): ?>
                        <h2>Procesos relacionados con tu usuario</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Proceso</th>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Observaciones</th>
                                    <th>Número de Piezas</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($procesosUsuario)): ?>
                                    <?php foreach ($procesosUsuario as $proceso): ?>
                                        <tr>
                                            <td><?= $proceso['nombre_proceso'] ?></td>
                                            <td><?= $proceso['nombre_cliente'] ?></td>
                                            <td>
                                                <strong><?= $proceso['nombre_producto'] ?></strong><br>
                                                <img src="<?= $proceso['imagen_producto'] ?>" alt="Imagen de producto" width="100">
                                            </td>
                                            <td><?= $proceso['observaciones'] ?></td>
                                            <td><?= $proceso['n_piezas'] ?></td>
                                            <td>
                                                <a href="<?= site_url('editarProceso/' . $proceso['id']) ?>"
                                                    class="btn btn-primary">Editar</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">No se encontraron procesos para este usuario.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>


                </div>

                <script>
                    window.history.replaceState({}, document.title, "<?= base_url('selectMaquina'); ?>");
                </script>
            </div>
        </div>