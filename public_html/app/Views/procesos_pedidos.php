<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORGANIZADOR</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/organizador.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>?v=<?= time() ?>">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<div class="wrapper">
    <div id="organizador">
        
        <div class="column" id="col3">

            <button data-action="cancelar" onclick="window.location.reload();" class="btn btnRecarga botonOrganizador" style="margin-bottom: 30px;" data-bs-toggle="tooltip" title="Recargar">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <g clip-path="url(#clip0_2485_250)">
                        <path d="M14.0334 9.0194H18.7705C18.8277 9.01942 18.8837 9.03575 18.932 9.06647C18.9803 9.09719 19.0188 9.14103 19.0431 9.19286C19.0674 9.24468 19.0764 9.30235 19.069 9.35911C19.0617 9.41587 19.0384 9.46937 19.0018 9.51335L16.6332 12.3565C16.605 12.3904 16.5696 12.4177 16.5296 12.4364C16.4897 12.4551 16.4461 12.4648 16.4019 12.4648C16.3578 12.4648 16.3142 12.4551 16.2742 12.4364C16.2343 12.4177 16.1989 12.3904 16.1706 12.3565L13.8021 9.51335C13.7655 9.46937 13.7421 9.41587 13.7348 9.35911C13.7275 9.30235 13.7365 9.24468 13.7608 9.19286C13.785 9.14103 13.8236 9.09719 13.8719 9.06647C13.9201 9.03575 13.9762 9.01942 14.0334 9.0194ZM0.781251 11.4289H5.5183C5.57552 11.4289 5.63156 11.4125 5.67985 11.3818C5.72814 11.3511 5.76667 11.3073 5.79094 11.2554C5.81521 11.2036 5.82421 11.1459 5.81689 11.0892C5.80957 11.0324 5.78623 10.9789 5.74961 10.9349L3.38108 8.09175C3.35282 8.05786 3.31745 8.03059 3.27748 8.01187C3.2375 7.99316 3.19391 7.98346 3.14977 7.98346C3.10564 7.98346 3.06204 7.99316 3.02207 8.01187C2.9821 8.03059 2.94673 8.05786 2.91846 8.09175L0.549941 10.9349C0.513314 10.9789 0.489974 11.0324 0.482653 11.0892C0.475333 11.1459 0.484335 11.2036 0.508605 11.2554C0.532876 11.3073 0.571411 11.3511 0.619697 11.3818C0.667983 11.4125 0.724022 11.4289 0.781251 11.4289Z" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.77585 4.20043C7.9061 4.20043 6.23392 5.05218 5.12917 6.39065C5.07984 6.45459 5.01815 6.50796 4.94778 6.54758C4.87741 6.5872 4.79979 6.61227 4.71954 6.62129C4.63929 6.63032 4.55804 6.62311 4.48063 6.6001C4.40322 6.5771 4.33123 6.53876 4.26893 6.48737C4.20663 6.43598 4.15531 6.37259 4.118 6.30097C4.08069 6.22934 4.05817 6.15095 4.05177 6.07044C4.04537 5.98994 4.05522 5.90897 4.08074 5.83235C4.10625 5.75573 4.14692 5.68503 4.20031 5.62444C5.08241 4.55647 6.2527 3.76433 7.57198 3.34224C8.89126 2.92015 10.304 2.88588 11.6422 3.24349C12.9804 3.60111 14.1877 4.33556 15.1206 5.3595C16.0534 6.38344 16.6726 7.65377 16.9043 9.0194H15.6791C15.4014 7.65881 14.6621 6.43596 13.5863 5.55781C12.5106 4.67966 11.1645 4.20015 9.77585 4.20043ZM3.87262 11.4289C4.10069 12.5428 4.63926 13.5695 5.42611 14.3903C6.21296 15.2111 7.21597 15.7926 8.31928 16.0675C9.42258 16.3424 10.5812 16.2996 11.6611 15.9439C12.7411 15.5883 13.6985 14.9343 14.4225 14.0576C14.4719 13.9937 14.5336 13.9403 14.6039 13.9007C14.6743 13.8611 14.7519 13.836 14.8322 13.827C14.9124 13.818 14.9937 13.8252 15.0711 13.8482C15.1485 13.8712 15.2205 13.9095 15.2828 13.9609C15.3451 14.0123 15.3964 14.0757 15.4337 14.1473C15.471 14.2189 15.4935 14.2973 15.4999 14.3778C15.5063 14.4583 15.4965 14.5393 15.471 14.6159C15.4455 14.6925 15.4048 14.7633 15.3514 14.8238C14.4693 15.8918 13.299 16.6839 11.9797 17.106C10.6604 17.5281 9.2477 17.5624 7.9095 17.2048C6.5713 16.8472 5.36398 16.1127 4.43113 15.0888C3.49828 14.0648 2.87915 12.7945 2.6474 11.4289H3.87262Z" fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_2485_250">
                            <rect width="19.2759" height="19.2759" fill="white" transform="translate(0.137924 0.586182)" />
                        </clipPath>
                    </defs>
                </svg>
            </button><br>

            <button data-action="btn-terminado" class="btn botonOrganizador btnTerminOrg" data-bs-toggle="tooltip" title="Terminar">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path d="M14.5763 8.07626C14.8052 7.85833 15.1098 7.73785 15.4258 7.74024C15.7419 7.74263 16.0446 7.86771 16.2702 8.08908C16.4958 8.31045 16.6266 8.61079 16.6349 8.92674C16.6433 9.24269 16.5286 9.54953 16.315 9.78251L9.83126 17.8913C9.71977 18.0113 9.58521 18.1077 9.43563 18.1746C9.28604 18.2415 9.1245 18.2775 8.96067 18.2806C8.79684 18.2836 8.63408 18.2536 8.48212 18.1923C8.33016 18.131 8.19212 18.0396 8.07626 17.9238L3.77651 13.624C3.65677 13.5124 3.56073 13.3779 3.49412 13.2284C3.42751 13.0789 3.39169 12.9175 3.3888 12.7539C3.38591 12.5902 3.41602 12.4277 3.47731 12.2759C3.53861 12.1242 3.62984 11.9863 3.74557 11.8706C3.8613 11.7548 3.99916 11.6636 4.15092 11.6023C4.30267 11.541 4.46522 11.5109 4.62886 11.5138C4.7925 11.5167 4.95389 11.5525 5.10339 11.6191C5.25288 11.6857 5.38744 11.7818 5.49901 11.9015L8.90176 15.3026L14.5438 8.11201L14.5763 8.07626ZM13.0813 16.4288L14.5763 17.9238C14.6921 18.0394 14.83 18.1305 14.9818 18.1916C15.1336 18.2528 15.2962 18.2828 15.4598 18.2797C15.6235 18.2767 15.7848 18.2407 15.9343 18.174C16.0837 18.1073 16.2182 18.0111 16.3296 17.8913L22.8166 9.78251C22.9332 9.66746 23.0253 9.53011 23.0876 9.37866C23.1499 9.2272 23.181 9.06476 23.1791 8.90101C23.1771 8.73727 23.1423 8.57559 23.0765 8.42563C23.0107 8.27567 22.9154 8.14051 22.7962 8.02821C22.677 7.91591 22.5364 7.82878 22.3828 7.77202C22.2292 7.71526 22.0657 7.69004 21.9022 7.69786C21.7386 7.70569 21.5783 7.74639 21.4308 7.81755C21.2833 7.8887 21.1517 7.98885 21.0438 8.11201L15.4001 15.3026L14.612 14.5129L13.0813 16.4288Z" fill="white" />
                </svg>
            </button><br>
            <button data-action="pedido" class="btn botonOrganizador btnRevertirOrg" style="margin-top: 30px;" data-bs-toggle="tooltip" title="Revertir">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <g clip-path="url(#clip0_2485_321)">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.77589 4.20044C11.0778 4.20094 12.3445 4.62322 13.3862 5.40402C14.428 6.18483 15.1888 7.28213 15.5546 8.53158C15.9204 9.78102 15.8716 11.1154 15.4154 12.3347C14.9593 13.5541 14.1203 14.5929 13.0243 15.2954C11.9282 15.998 10.6341 16.3266 9.3356 16.2319C8.03715 16.1373 6.80431 15.6245 5.82174 14.7704C4.83917 13.9163 4.15976 12.7668 3.88527 11.4942C3.61078 10.2215 3.75598 8.89424 4.29913 7.71106C4.35825 7.5672 4.35944 7.40606 4.30245 7.26135C4.24546 7.11664 4.13471 6.99959 3.99337 6.93468C3.85204 6.86977 3.69108 6.86204 3.54417 6.91311C3.39727 6.96418 3.27582 7.07009 3.20523 7.20868C2.55347 8.62857 2.3793 10.2214 2.70879 11.7486C3.03828 13.2758 3.8537 14.6551 5.03291 15.68C6.21212 16.7049 7.69164 17.3201 9.24985 17.4335C10.8081 17.5469 12.3611 17.1525 13.6763 16.3092C14.9915 15.4659 15.9981 14.2192 16.5453 12.7558C17.0924 11.2925 17.1508 9.6912 16.7115 8.19188C16.2723 6.69257 15.3591 5.37592 14.1088 4.43916C12.8584 3.5024 11.3382 2.99596 9.77589 2.9957V4.20044Z" fill="white" />
                        <path d="M9.77587 5.96658V1.22954C9.77585 1.17231 9.75952 1.11627 9.7288 1.06798C9.69808 1.0197 9.65424 0.981164 9.60241 0.956893C9.55059 0.932623 9.49292 0.923621 9.43616 0.930941C9.3794 0.938262 9.3259 0.961602 9.28193 0.998229L6.43874 3.36675C6.40484 3.39502 6.37757 3.43039 6.35886 3.47036C6.34014 3.51033 6.33044 3.55393 6.33044 3.59806C6.33044 3.6422 6.34014 3.68579 6.35886 3.72576C6.37757 3.76573 6.40484 3.8011 6.43874 3.82937L9.28193 6.19789C9.3259 6.23452 9.3794 6.25786 9.43616 6.26518C9.49292 6.2725 9.55059 6.2635 9.60241 6.23923C9.65424 6.21496 9.69808 6.17642 9.7288 6.12814C9.75952 6.07985 9.77585 6.02381 9.77587 5.96658Z" fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_2485_321">
                            <rect width="19.2759" height="19.2759" fill="white" transform="translate(0.137939 0.586212)" />
                        </clipPath>
                    </defs>
                </svg>
            </button><br>
            <?php echo view('procesosTerminados'); ?>
        </div>
        <div class="column" id="col4">
            <div class="cabecera">
                <div style="display: inline-block; vertical-align: middle;">
                    <select id="maquinaFilterCol4" class="form-control d-inline-block" onchange="filtrarProcesosPorMaquina(this.value);">
                        <option value="">Todas las máquinas</option>
                        <?php if (isset($maquinas)) : ?>
                            <?php
                            usort($maquinas, function ($a, $b) {
                                return strcmp($a['nombre'], $b['nombre']);
                            });
                            ?>
                            <?php foreach ($maquinas as $maquina) : ?>
                                <option value="<?= esc($maquina['id_maquina']) ?>"><?= esc($maquina['nombre']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div style="display: inline-block; vertical-align: middle; margin-left: 10px">
                    <button id="clearMachineFilter" class="boton btnEliminarfiltros" onclick="eliminarFiltroMaquina()">
                        Quitar Filtros
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                            <path d="M7.66752 7.27601C7.41731 7.27601 7.17736 7.37593 7.00044 7.5538C6.82351 7.73166 6.72412 7.9729 6.72412 8.22444V9.17287C6.72412 9.4244 6.82351 9.66564 7.00044 9.84351C7.17736 10.0214 7.41731 10.1213 7.66752 10.1213H8.13922V18.6571C8.13922 19.1602 8.338 19.6427 8.69184 19.9984C9.04569 20.3542 9.5256 20.554 10.026 20.554H15.6864C16.1868 20.554 16.6667 20.3542 17.0205 19.9984C17.3744 19.6427 17.5732 19.1602 17.5732 18.6571V10.1213H18.0449C18.2951 10.1213 18.535 10.0214 18.712 9.84351C18.8889 9.66564 18.9883 9.4244 18.9883 9.17287V8.22444C18.9883 7.9729 18.8889 7.73166 18.712 7.5538C18.535 7.37593 18.2951 7.27601 18.0449 7.27601H14.743C14.743 7.02447 14.6436 6.78324 14.4667 6.60537C14.2898 6.42751 14.0498 6.32758 13.7996 6.32758H11.9128C11.6626 6.32758 11.4226 6.42751 11.2457 6.60537C11.0688 6.78324 10.9694 7.02447 10.9694 7.27601H7.66752ZM10.4977 11.0697C10.6228 11.0697 10.7428 11.1197 10.8312 11.2086C10.9197 11.2975 10.9694 11.4182 10.9694 11.5439V18.1829C10.9694 18.3087 10.9197 18.4293 10.8312 18.5182C10.7428 18.6072 10.6228 18.6571 10.4977 18.6571C10.3726 18.6571 10.2526 18.6072 10.1642 18.5182C10.0757 18.4293 10.026 18.3087 10.026 18.1829V11.5439C10.026 11.4182 10.0757 11.2975 10.1642 11.2086C10.2526 11.1197 10.3726 11.0697 10.4977 11.0697ZM12.8562 11.0697C12.9813 11.0697 13.1013 11.1197 13.1897 11.2086C13.2782 11.2975 13.3279 11.4182 13.3279 11.5439V18.1829C13.3279 18.3087 13.2782 18.4293 13.1897 18.5182C13.1013 18.6072 12.9813 18.6571 12.8562 18.6571C12.7311 18.6571 12.6111 18.6072 12.5227 18.5182C12.4342 18.4293 12.3845 18.3087 12.3845 18.1829V11.5439C12.3845 11.4182 12.4342 11.2975 12.5227 11.2086C12.6111 11.1197 12.7311 11.0697 12.8562 11.0697ZM15.6864 11.5439V18.1829C15.6864 18.3087 15.6367 18.4293 15.5482 18.5182C15.4598 18.6072 15.3398 18.6571 15.2147 18.6571C15.0896 18.6571 14.9696 18.6072 14.8811 18.5182C14.7927 18.4293 14.743 18.3087 14.743 18.1829V11.5439C14.743 11.4182 14.7927 11.2975 14.8811 11.2086C14.9696 11.1197 15.0896 11.0697 15.2147 11.0697C15.3398 11.0697 15.4598 11.1197 15.5482 11.2086C15.6367 11.2975 15.6864 11.4182 15.6864 11.5439Z" fill="white" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="resultados">
                <table id="sortableTable" class="table scrollgordo">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllCol4" class="selectAll"></th>
                            <th class="columna-id">
                                <input type="text" id="idSearchInputCol4" class="form-control d-inline-block" style="width: 70%; font-size: 1em; border: 1px solid #989A9C;" placeholder="Parte ID" onkeyup="filtrarPorIdCol4();">
                            </th>
                            <th>
                                <select id="clienteFilterCol4" style="width: 100%;" onchange="filtrarPorClienteCol4(this.value);">
                                    <option value="">Cliente</option>
                                    <?php if (isset($clientes)) : ?>
                                        <?php foreach ($clientes as $cliente) : ?>
                                            <option value="<?= esc($cliente['nombre_cliente']) ?>"><?= esc($cliente['nombre_cliente']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </th>
                            <th>Medidas</th>
                            <th>
                                <select id="productoFilterCol4" style="width: 100%;" onchange="filtrarPorProducto(this.value, 4);">
                                    <option value="">Producto</option>
                                    <?php if (isset($productos)) : ?>
                                        <?php foreach ($productos as $producto) : ?>
                                            <option value="<?= esc($producto['nombre_producto']) ?>"><?= esc($producto['nombre_producto']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>

                            </th>
                            <th>Pzas</th>
                            <th>Proceso</th>
                            <th>Base</th>
                            <th>Entrega</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lineasEstado3 as $linea) : ?>
                            <tr class="linea" data-nombre-cliente="<?= esc($linea['cliente']) ?>" data-nombre-proceso="<?= esc($linea['proceso']); ?>" data-nombre-producto="<?= esc($linea['producto']); ?>" data-id-maquina="<?= $linea['id_maquina']; ?>" data-estado="<?= esc($linea['guardado']) ? 'guardado' : 'no-guardado'; ?>">
                                <td><input type="checkbox" class="checkboxCol4" name="selectedLineCol4[]"></td>
                                <td><strong><?= $linea['id_linea_pedido']; ?></strong></td>
                                <td><?= $linea['cliente'] ?></td>
                                <td><?= $linea['medidas'] ?></td>
                                <td><?= $linea['producto'] ?></td>
                                <td><?= $linea['n_piezas'] ?></td>
                                <td>
                                    <?= esc($linea['proceso']); ?>
                                    <?php if ($linea['restriccion'] !== null && $linea['restriccion'] !== '0' && $linea['restriccion'] !== '') : ?>
                                        <form action="/procesos_pedidos/eliminarRestriccion" method="POST" style="display:inline;">
                                            <input type="hidden" name="id_relacion" value="<?= $linea['id_relacion']; ?>">
                                            <!-- Añadimos el onclick al botón para mostrar un alert de confirmación -->
                                            <button type="submit" class="btn btn-icon" title="Eliminar restricción" onclick="return confirmarEliminacion();">
                                                <span>🔒</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                                <td><?= $linea['base'] ?></td>
                                <td><?= $linea['fecha'] ?></td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="printableArea" style="display: none;">
            <div id="fondo">
                <div id="printableContent">
                    <h1>Informe de Procesos en Máquinas</h1>
                    <?php foreach ($maquinas as $maquina) : ?>
                        <div>
                            <h2>Máquina: <?= esc($maquina['nombre']); ?></h2>
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Línea Pedido</th>
                                        <th>Cliente</th>
                                        <th>Producto</th>
                                        <th>Nº Piezas</th>
                                        <th>Base</th>
                                        <th>Medidas</th>
                                        <th>Proceso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lineasEstado3 as $linea) : ?>
                                        <?php if ($linea['id_maquina'] == $maquina['id_maquina']) : ?>
                                            <tr>
                                                <td><?= esc($linea['id_linea_pedido']); ?></td>
                                                <td><?= esc($linea['cliente']); ?></td>
                                                <td><?= esc($linea['producto']); ?></td>
                                                <td><?= esc($linea['n_piezas']); ?></td>
                                                <td><?= esc($linea['base']); ?></td>
                                                <td><?= esc($linea['medidas']); ?></td>
                                                <td><?= esc($linea['proceso']); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ocultarFilasSinFiltro();

        // Evento de cambio en el filtro de máquina
        document.getElementById('maquinaFilterCol4').addEventListener('change', function() {
            const selectedMachine = this.value;
            filtrarProcesosPorMaquina(selectedMachine);
        });

        // Evento para eliminar filtro de máquina
        document.getElementById('clearMachineFilter').addEventListener('click', function() {
            document.getElementById('maquinaFilterCol4').value = '';
            ocultarFilasSinFiltro();
        });
    });
    // Función para ocultar todas las filas al inicio
    function ocultarFilasSinFiltro() {
        document.querySelectorAll('#col4 tbody tr').forEach(function(row) {
            row.style.display = 'none';
        });
    }
    // Función para filtrar las filas por la máquina seleccionada
    function filtrarProcesosPorMaquina(machineId) {
        document.querySelectorAll('#col4 tbody tr').forEach(function(row) {
            const rowMachineId = row.getAttribute('data-id-maquina');
            if (rowMachineId === machineId || machineId === '') {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
<script>
    function printDiv(divId) {
        // Verificar si hay una máquina seleccionada
        if (!selectedMachineId) {
            alert('¡Seleccione una máquina antes de imprimir!');
            return;
        }

        // Generar contenido imprimible solo para la máquina seleccionada
        generarContenidoImprimible();

        const printContents = document.getElementById(divId).innerHTML;
        const printWindow = window.open('', '', 'height=600,width=800');

        printWindow.document.write(`
            <html>
                <head>
                    <title>Impresión</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 12px;
                            color: #333;
                        }
                        h1, h2 {
                            text-align: center;
                            margin-bottom: 20px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 8px;
                            text-align: left;
                        }
                        th {
                            background-color: #f2f2f2;
                        }
                        @media print {
                            @page {
                                margin: 1cm;
                            }
                            body {
                                margin: 0;
                                padding: 0;
                            }
                            header, footer {
                                position: fixed;
                                width: 100%;
                                background-color: #f8f8f8;
                                padding: 5px;
                                text-align: center;
                                font-size: 10px;
                            }
                            header {
                                top: 0;
                            }
                            footer {
                                bottom: 0;
                            }
                        }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
            </html>
        `);

        printWindow.document.close();
        printWindow.print();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Añadir evento de clic a cada fila de la tabla
        document.querySelectorAll('.linea').forEach(function(row) {
            row.addEventListener('click', function(event) {
                // Evitar que el evento se propague si se hace clic en el checkbox directamente
                if (event.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;
                }
            });
        });

        actualizarColores();
        actualizarColoresCol2();
        generarContenidoImprimible();
        seleccionarMaquinaGuardada();

        // Guardar el orden original de las filas
        const tbody = document.querySelector('#Tabla2 tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        originalOrder = rows.map((row, index) => ({
            element: row,
            index: index
        }));
    });

    function generarContenidoImprimible() {
        const maquinas = <?php echo json_encode($maquinas); ?>;
        const lineasEstado3 = <?php echo json_encode($lineasEstado3); ?>;

        // Obtener la fecha actual
        const fechaActual = new Date();
        const fechaFormateada = fechaActual.toLocaleDateString();

        const content = document.getElementById('printableContent');
        content.innerHTML = `<h1>Informe de Procesos en Máquinas - ${fechaFormateada}</h1>`;

        const maquinaSeleccionada = maquinas.find(maquina => maquina.id_maquina === selectedMachineId);
        if (maquinaSeleccionada) {
            const lineasMaquina = lineasEstado3.filter(linea => linea.id_maquina === selectedMachineId);

            if (lineasMaquina.length > 0) {
                const maquinaDiv = document.createElement('div');
                maquinaDiv.innerHTML = `<h2>Máquina: ${maquinaSeleccionada.nombre}</h2>`;

                const table = document.createElement('table');
                table.className = 'table table-sm table-hover';
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>ID Línea Pedido</th>
                            <th>Cliente</th>
                            <th>Producto</th>
                            <th>Nº Piezas</th>
                            <th>Base</th>
                            <th>Medidas</th>
                            <th>Proceso</th>
                           
                        </tr>
                    </thead>
                    <tbody></tbody>
                `;

                const tbody = table.querySelector('tbody');

                lineasMaquina.forEach(linea => {
                    if (!linea.restriccion || linea.restriccion === '0' || linea.restriccion === '') {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${linea.id_linea_pedido}</td>
                            <td>${linea.cliente}</td>
                            <td>${linea.producto}</td>
                            <td>${linea.n_piezas}</td>
                            <td>${linea.base}</td>
                            <td>${linea.medidas}</td>                 
                            <td>${linea.proceso}</td>
                           
                        `;
                        tbody.appendChild(row);
                    }
                });

                maquinaDiv.appendChild(table);
                content.appendChild(maquinaDiv);
            }
        }
    }
</script>

</body>

</html>
<script>
    // Variables globales
    let selectedMachineId = null;
    let selectedClientFilterCol2 = '';
    let selectedProcesoFilterCol2 = '';
    let selectedClientFilterCol4 = '';
    let selectedProcesoFilterCol4 = '';
    let selectedProductoFilterCol2 = '';
    let selectedProductoFilterCol4 = '';
    let idFilterCol2 = '';
    let idFilterCol4 = '';

    let sortable;
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    $(document).ready(function() {
        // Inicializar select2 en el select con id 'searchInput'
        $('#searchInput').select2({
            placeholder: 'Seleccione un proceso...',
            allowClear: true
        });

        // Inicializar select2 en col4 si col2 también lo tiene (mantener tu código existente)
        $('#maquinaFilterCol4').select2({
            placeholder: 'Seleccione una máquina...',
            allowClear: true
        });
    });

    $(document).ready(function() {
        // Manejar el clic en el botón "pedido"
        $('[data-action="pedido"]').click(function() {
            // Hacemos la petición AJAX para obtener los procesos con estado 4
            $.ajax({
                url: '<?= base_url('procesos_pedidos/getProcesosEstado4') ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var tabla = '';
                    $.each(response, function(index, proceso) {
                        tabla += '<tr>';
                        tabla += '<td>' + proceso.id_linea_pedido + '</td>';
                        tabla += '<td>' + proceso.nombre_proceso + '</td>';
                        tabla += '<td>' + proceso.nombre_producto + '</td>';
                        tabla += '<td><button class="btn boton btnEditar revertir-estado" data-id-relacion="' + proceso.id_relacion + '">Revertir </button></td>';
                        tabla += '</tr>';
                    });
                    $('#tablaProcesos').html(tabla);
                    $('#modalProcesos').modal('show');
                },
                error: function() {
                    alert('Error al cargar los datos.');
                }
            });
        });

        // Reactivar sortable después de ciertas acciones
        document.querySelectorAll('button[data-action]').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                if (action === 'confirm') {
                    confirmarProcesos();
                }
                inicializarSortable();
            });
        });
    });

    function inicializarSortable() {
        var el = document.getElementById('sortableTable').getElementsByTagName('tbody')[0];
        if (sortable) {
            sortable.destroy();
        }
        sortable = Sortable.create(el, {
            animation: 150,
            scroll: true,
            onStart: function(evt) {
                document.addEventListener('dragover', handleDragScroll);
            },
            onEnd: function(evt) {
                document.removeEventListener('dragover', handleDragScroll);
                guardarOrdenEnLocalStorage();
                actualizarOrdenProcesos();
            }
        });
    }

    function handleDragScroll(event) {
        const scrollContainer = document.getElementById('sortableTable').parentElement;
        const sensitivity = 30;
        const scrollSpeed = 10;

        const mouseY = event.clientY;
        const containerRect = scrollContainer.getBoundingClientRect();

        if (mouseY < containerRect.top + sensitivity) {
            scrollContainer.scrollTop -= scrollSpeed;
        } else if (mouseY > containerRect.bottom - sensitivity) {
            scrollContainer.scrollTop += scrollSpeed;
        }
    }

    function guardarOrdenEnLocalStorage() {
        const filas = document.querySelectorAll('#sortableTable tbody tr');
        let orden = Array.from(filas).map(fila => fila.getAttribute('data-id'));
        localStorage.setItem('ordenProcesos', JSON.stringify(orden));
    }

    // Cargar el orden desde localStorage
    function cargarOrdenDesdeLocalStorage() {
        let orden = localStorage.getItem('ordenProcesos');
        if (orden) {
            orden = JSON.parse(orden);
            const tbody = document.querySelector('#sortableTable tbody');
            orden.forEach(id => {
                const fila = document.querySelector(`#sortableTable tbody tr[data-id="${id}"]`);
                if (fila) {
                    tbody.appendChild(fila);
                }
            });
        }
    }

    // Función que asegura que Sortable se reinicie después de cargar el DOM
    $(document).ready(function() {
        cargarOrdenDesdeLocalStorage();
        inicializarSortable();
    });
    // Manejar el clic en el botón "Revertir Estado"
    $(document).on('click', '.revertir-estado', function() {
        var idRelacion = $(this).data('id-relacion');

        $.ajax({
            url: '<?= base_url('procesos_pedidos/actualizarEstadoYEliminarRestricciones/') ?>' + idRelacion,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    $('#modalProcesos').modal('hide');
                    location.reload();
                } else {
                    alert('Error al revertir el estado del proceso.');
                }
            },
        });
    });

    // Funciones de filtrado
    function aplicarFiltros(columna) {
        const tableRows = document.querySelectorAll(`#col${columna} tbody tr`);
        const clientFilter = columna === 2 ? selectedClientFilterCol2 : selectedClientFilterCol4;
        const procesoFilter = columna === 2 ? selectedProcesoFilterCol2 : selectedProcesoFilterCol4;
        const productoFilter = columna === 2 ? selectedProductoFilterCol2 : selectedProductoFilterCol4;
        const idFilter = columna === 2 ? idFilterCol2 : idFilterCol4;
        const maquinaFilter = columna === 4 ? selectedMachineId : null;

        tableRows.forEach(row => {
            const cliente = row.getAttribute('data-nombre-cliente');
            const proceso = row.getAttribute('data-nombre-proceso');
            const producto = row.getAttribute('data-nombre-producto');
            const idMaquina = row.getAttribute('data-id-maquina');
            const id = row.querySelector('td:nth-child(2)').textContent.trim();
            let display = true;

            if (clientFilter && cliente && !cliente.toLowerCase().includes(clientFilter)) {
                display = false;
            }
            if (procesoFilter && proceso && !proceso.toLowerCase().includes(procesoFilter)) {
                display = false;
            }
            if (productoFilter && producto && !producto.toLowerCase().includes(productoFilter)) {
                display = false;
            }
            if (idFilter && id && !id.toUpperCase().includes(idFilter)) {
                display = false;
            }
            if (columna === 4 && maquinaFilter && idMaquina !== maquinaFilter) {
                display = false;
            }


            row.style.display = display ? '' : 'none';
        });
    }


    function filtrarPorCliente(valor, columna) {
        if (columna === 2) {
            selectedClientFilterCol2 = valor.toLowerCase();
        } else {
            selectedClientFilterCol4 = valor.toLowerCase();
        }
        aplicarFiltros(columna);
    }

    function filtrarPorClienteCol4(valor) {
        selectedClientFilterCol4 = valor.toLowerCase();
        aplicarFiltros(4);
    }


    function filtrarPorProceso(valor, columna) {
        // Deseleccionar todos los checkboxes seleccionados previamente
        document.querySelectorAll(`#col${columna} input[type="checkbox"]:checked`).forEach(checkbox => {
            checkbox.checked = false;
        });
        if (columna === 2) {
            selectedProcesoFilterCol2 = valor.toLowerCase();
        } else {
            selectedProcesoFilterCol4 = valor.toLowerCase();
        }
        aplicarFiltros(columna);
    }

    function filtrarPorIdCol2() {
        var input = document.getElementById("idSearchInputCol2");
        idFilterCol2 = input.value.toUpperCase();
        aplicarFiltros(2);
    }

    function filtrarPorIdCol4() {
        var input = document.getElementById("idSearchInputCol4");
        idFilterCol4 = input.value.toUpperCase();
        aplicarFiltros(4);
    }

    function filtrarProcesosPorMaquina(idMaquina, nombreMaquina) {
        // Deseleccionar todos los checkboxes seleccionados previamente
        document.querySelectorAll('#col4 input[type="checkbox"]:checked').forEach(checkbox => {
            checkbox.checked = false;
        });

        selectedMachineId = idMaquina;

        document.querySelectorAll('#col4 .linea').forEach(row => {
            const estado = row.getAttribute('data-estado');
            const idMaquinaFila = row.getAttribute('data-id-maquina');
            const shouldDisplay = (estado === 'no-guardado' || idMaquinaFila === idMaquina);
            row.style.display = shouldDisplay ? '' : 'none';
        });

        if (sortable) {
            sortable.option("disabled", false); // Habilitar Sortable al seleccionar una máquina
        }
        aplicarFiltros(4);
    }


    function filtrarPorMedida(valor) {
        const tbody = document.querySelector('#Tabla2 tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        if (valor === "") {
            // Restaurar el orden original
            originalOrder.sort((a, b) => a.index - b.index).forEach(item => tbody.appendChild(item.element));
        } else {
            // Función para extraer la medida desde el texto
            const getMedida = (texto, tipo) => {
                const partes = texto.split('-').map(parte => parseFloat(parte.trim()) || 0);
                if (tipo === 'iniciales') {
                    return partes[0]; // Primera medida (antes del '-')
                } else if (tipo === 'finales') {
                    return partes[1] || 0; // Segunda medida (después del '-') o 0 si no existe
                }
                return 0;
            };

            // Función de comparación para ordenar las filas
            const compareFunction = (a, b) => {
                const medidaA = getMedida(a.querySelector('td:nth-child(4)').textContent, valor);
                const medidaB = getMedida(b.querySelector('td:nth-child(4)').textContent, valor);

                return medidaA - medidaB; // Orden ascendente
            };

            // Ordenar las filas usando la función de comparación
            rows.sort(compareFunction).forEach(row => tbody.appendChild(row));
        }
    }

    function filtrarPorProducto(valor, columna) {
        if (columna === 2) {
            selectedProductoFilterCol2 = valor.toLowerCase();
        } else {
            selectedProductoFilterCol4 = valor.toLowerCase();
        }
        aplicarFiltros(columna);
    }

    // Funciones de movimiento y confirmación
    function moverPedidos(selectorCheckbox, selectorTablaDestino) {
        document.querySelectorAll(selectorCheckbox).forEach(checkbox => {
            const filaOriginal = checkbox.closest('tr');
            const tablaDestino = document.querySelector(selectorTablaDestino);

            if (!tablaDestino) {
                console.error('El selector de la tabla destino no encontró ningún elemento:', selectorTablaDestino);
                return;
            }

            const nuevaFila = crearNuevaFila(filaOriginal);
            tablaDestino.appendChild(nuevaFila);
            filaOriginal.remove();
            nuevaFila.classList.add('fondo-rojo');
        });
        actualizarColores();
        actualizarColoresCol2();
    }

    function crearNuevaFila(filaOriginal) {
        const nuevaFila = document.createElement('tr');
        nuevaFila.className = 'linea';
        nuevaFila.setAttribute('data-id-maquina', selectedMachineId); // Asegura que el ID de la máquina esté correctamente asignado
        nuevaFila.setAttribute('data-nombre-proceso', filaOriginal.getAttribute('data-nombre-proceso'));
        nuevaFila.setAttribute('data-estado', 'no-guardado');

        const tdCheckbox = document.createElement('td');
        const nuevoCheckbox = document.createElement('input');
        nuevoCheckbox.type = 'checkbox';
        tdCheckbox.appendChild(nuevoCheckbox);
        nuevaFila.appendChild(tdCheckbox);

        Array.from(filaOriginal.children).slice(1).forEach(td => {
            nuevaFila.appendChild(td.cloneNode(true));
        });

        return nuevaFila;
    }

    function confirmarProcesos() {
        // Guardar la máquina seleccionada en el almacenamiento local antes de confirmar
        if (selectedMachineId) {
            localStorage.setItem('selectedMachineId', selectedMachineId);
        }
        const procesosActualizar = obtenerProcesos('#col4 tbody tr', true);
        const procesosRevertir = obtenerProcesos('#col2 tbody tr', false);

        if (procesosActualizar.length > 0) {
            actualizarProcesos(procesosActualizar);
        }

        if (procesosRevertir.length > 0) {
            revertirProcesos(procesosRevertir);
        }
    }

    function obtenerProcesos(selector, conOrden) {
        return Array.from(document.querySelectorAll(selector)).filter(fila => {
            const filaMaquinaId = fila.getAttribute('data-id-maquina');
            return filaMaquinaId && (conOrden ? filaMaquinaId === selectedMachineId : true);
        }).map((fila, index) => ({
            nombre_proceso: fila.getAttribute('data-nombre-proceso'),
            id_linea_pedido: fila.querySelector('td:nth-child(2)').textContent.trim(),
            id_maquina: conOrden ? selectedMachineId : fila.getAttribute('data-id-maquina'),
            orden: conOrden ? index + 1 : 0
        }));
    }


    function actualizarProcesos(procesos) {
        realizarPeticionAjax('<?php echo base_url('procesos_pedidos/actualizarEstadoProcesos'); ?>', procesos, () => {
            actualizarEstadoLineaPedido();
        });
    }

    function revertirProcesos(procesos) {
        realizarPeticionAjax('<?php echo base_url('procesos_pedidos/revertirEstadoProcesos'); ?>', procesos, () => {
            localStorage.setItem('reloadedFromConfirm', 'true');
            window.location.reload();
        });
    }

    function actualizarEstadoLineaPedido() {
        fetch('<?php echo base_url('procesos_pedidos/actualizarEstadoLineaPedido'); ?>', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    localStorage.setItem('reloadedFromConfirm', 'true');
                    window.location.reload();
                } else {
                    alert('Error al actualizar los estados de las líneas de pedido.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Funciones de utilidad
    function realizarPeticionAjax(url, procesos, callback) {
        $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                procesos: procesos
            }),
            success: function(response) {
                if (response.success) {
                    if (callback) callback();
                } else {
                    alert('Error al actualizar los procesos.');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error en la solicitud AJAX. Revisa la consola para más detalles.');
            }
        });
    }

    function mostrarTodasLasLineas() {
        document.querySelectorAll('#col4 .linea').forEach(linea => {
            linea.style.display = '';
        });
    }

    function actualizarColores() {
        document.querySelectorAll('#col4 .linea').forEach(fila => {
            fila.classList.toggle('sin-color', fila.getAttribute('data-guardado') === 'guardado');
            fila.classList.toggle('verde-tenue', fila.getAttribute('data-guardado') !== 'guardado');
        });
    }

    function actualizarColoresCol2() {
        document.querySelectorAll('#col2 .linea').forEach(fila => {
            fila.classList.toggle('proceso-col2', fila.getAttribute('data-guardado') !== 'guardado');
        });
    }

    function seleccionarMaquinaGuardada() {
        const savedMachineId = localStorage.getItem('selectedMachineId');
        if (savedMachineId) {
            const maquina = document.querySelector(`#maquinaFilterCol4 option[value="${savedMachineId}"]`);
            if (maquina) {
                maquina.selected = true;
                filtrarProcesosPorMaquina(savedMachineId);
            }
            localStorage.removeItem('selectedMachineId');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['#searchInput', '#clienteFilter', '#medidasFilter', '#productoFilterCol2', '#productoFilterCol4', '#clienteFilterCol4', '#searchInputCol4'].forEach(selector => {
            $(selector).select2();
        });

        seleccionarMaquinaGuardada();

        // Eventos de filtrado
        $('#searchInput').on('change', e => filtrarPorProceso(e.target.value, 2));
        $('#clienteFilter').on('change', e => filtrarPorCliente(e.target.value, 2));
        $('#clienteFilterCol4').on('change', e => filtrarPorCliente(e.target.value, 4));
        $('#searchInputCol4').on('change', e => filtrarPorProceso(e.target.value, 4));
        $('#medidasFilter').on('change', e => filtrarPorMedida(e.target.value));

        // Evento para limpiar filtros
        $('#clearFilters').on('click', () => {
            ['#searchInput', '#clienteFilter', '#productoFilterCol2', '#productoFilterCol4', '#clienteFilterCol4', '#medidasFilter', ].forEach(selector => {
                $(selector).val('').trigger('change');
            });
            $('#idSearchInputCol2').val('').trigger('keyup');
            if (sortable) sortable.option("disabled", true);
        });

        // Eventos de botones
        document.querySelectorAll('button[data-action]').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                if (action === 'move-right') {
                    if (!selectedMachineId) {
                        alert('¡Seleccione una máquina!');
                        return;
                    }
                    moverPedidos('input[type="checkbox"]:checked', '#col4 table tbody');
                } else if (action === 'move-left') {
                    moverPedidos('#col4 input[type="checkbox"]:checked', '#col2 table tbody');
                } else if (action === 'confirm') {
                    confirmarProcesos();
                }
            });
        });

        document.querySelectorAll('.selectAll').forEach(selectAllCheckbox => {
            selectAllCheckbox.addEventListener('click', function(event) {
                event.preventDefault(); 
                const columnId = this.id === 'selectAllCol2' ? 'Col2' : 'Col4';
                const checkboxes = document.querySelectorAll(`input[name="selectedLine${columnId}[]"]`);
                const isChecked = !this.classList.contains('highlight');
                checkboxes.forEach(checkbox => {
                    if (checkbox.offsetParent !== null) {
                        checkbox.checked = isChecked;
                    }
                });
                this.classList.toggle('highlight', isChecked);
            });
        });

        // Eventos de máquinas
        document.querySelectorAll('.maquina').forEach(maquina => {
            maquina.addEventListener('click', function() {
                selectedMachineId = this.getAttribute('data-id-maquina');
                filtrarProcesosPorMaquina(selectedMachineId, this.getAttribute('data-nombre'));
                if (sortable) sortable.option("disabled", false);
            });
        });

        // Evento para ver todo
        $('#verTodo').on('click', () => {
            selectedMachineId = null;
            selectedClientFilterCol4 = '';
            selectedProcesoFilterCol4 = '';
            $('#clienteFilterCol4, #searchInputCol4').val('').trigger('change');
            mostrarTodasLasLineas();
            document.getElementById('tituloProcesosEnMaquina').textContent = 'Procesos en máquinas';
            if (sortable) sortable.option("disabled", true);
        });

        // Inicializar Sortable
        var el = document.getElementById('sortableTable').getElementsByTagName('tbody')[0];
        sortable = Sortable.create(el, {
            animation: 150,
            onEnd: function(evt) {
                actualizarOrdenProcesos();
            }
        });

        document.querySelector('button[data-action="btn-terminado"]').addEventListener('click', function() {
            marcarComoTerminado(this);
        });

        sortable.option("disabled", true); // Deshabilitar Sortable inicialmente
    });

    // Eventos adicionales
    document.addEventListener('click', function(event) {
        if (event.target.matches('button[data-action="confirm"]')) {
            actualizarOrdenProcesos();
        }

        const targetTerminado = event.target.closest('button[data-action="btn-terminado"]');
        if (targetTerminado) {
            event.preventDefault();
            marcarComoTerminado(targetTerminado);
        }
    });

    function actualizarOrdenProcesos() {
        const filas = document.querySelectorAll('#sortableTable tbody tr');
        let ordenes = Array.from(filas)
            .filter(fila => fila.getAttribute('data-id-maquina') === selectedMachineId)
            .map((fila, index) => ({
                id_linea_pedido: fila.querySelector('td:nth-child(2)').textContent.trim(),
                nombre_proceso: fila.getAttribute('data-nombre-proceso').trim(),
                orden: index + 1,
                id_maquina: fila.getAttribute('data-id-maquina').trim()
            }));

        fetch('<?php echo base_url('procesos_pedidos/actualizarOrdenProcesos'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ordenes: ordenes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Orden actualizado correctamente.');
                } else {
                    alert('Error al actualizar el orden: ' + data.error);
                }
            });
    }


    function eliminarFiltroMaquina() {
        $('#maquinaFilterCol4').val('').trigger('change');
        selectedMachineId = null;
        $('#clienteFilterCol4').val('').trigger('change');
        $('#productoFilterCol4').val('').trigger('change');
        $('#idSearchInputCol4').val('');
        mostrarTodasLasLineas();
        if (sortable) {
            sortable.option("disabled", true);
        }
        aplicarFiltros(4);
    }

    function mostrarTodasLasLineas() {
        document.querySelectorAll('#col4 .linea').forEach(linea => {
            linea.style.display = '';
        });
    }

    function marcarComoTerminado(button) {
        event.stopPropagation();
        event.preventDefault();

        const selectedLines = document.querySelectorAll('#col4 input[name="selectedLineCol4[]"]:checked');
        let lineItems = [];
        let hasRestriction = false;

        selectedLines.forEach(line => {
            const row = line.closest('tr');
            const restriccion = row.querySelector('td:nth-child(8) span');

            if (restriccion) {
                hasRestriction = true;
            }

            lineItems.push({
                idLineaPedido: row.querySelector('td:nth-child(2)').textContent.trim(),
                nombreProceso: row.querySelector('td:nth-child(8)').textContent.trim()
            });
        });

        if (lineItems.length > 0) {
            button.disabled = true;

            if (selectedMachineId) {
                localStorage.setItem('selectedMachineId', selectedMachineId);
            }

            fetch('<?php echo base_url('procesos_pedidos/marcarTerminado'); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        lineItems: lineItems
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        let message = 'Uno o más procesos seleccionados tienen restricciones pendientes.\n\n';
                        data.procesosConRestricciones.forEach(item => {
                            message += `${item.nombre_proceso}\nRestringido por: ${item.restricciones.join(', ')}\n\n`;
                        });
                        alert(message);
                        window.location.reload();
                    } else {
                        localStorage.setItem('reloadedFromTerminar', 'true');
                        window.location.reload();
                    }
                })
                .catch(error => {
                    alert('Error al actualizar los estados.');
                })
                .finally(() => {
                    button.disabled = false;
                });
        }
    }

    function confirmarEliminacion() {
        var confirmacion = confirm("¿Estás seguro de eliminar las restricciones de este proceso?");
        if (confirmacion) {
            return true;
        } else {
            return false;
        }
    }
</script>