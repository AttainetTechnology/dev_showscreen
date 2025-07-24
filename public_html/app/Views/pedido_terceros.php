<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/partes.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>">

<div id="fondo">
    <?php
    $volver = isset($_GET['pg2']) ? $_GET['pg2'] : 'javascript:window.close();';
    ?>
    <a href="javascript:void(0);" onclick="imprimirYMarcarPedido();" class="boton btnEditar">
        Imprimir y marcar pedido como enviado
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
            <path d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z" fill="white" />
            <path fill-rule="evenodd" clip-rule="evenodd" d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z" fill="white" />
        </svg>
    </a>
    <script>
        function imprimirYMarcarPedido() {
            printAndCloseModal('printableArea');
            // AJAX para marcar como enviado
            fetch('<?= base_url('Pedidos_terceros/marcarenviado/' . $pedido->id_ped_terceros) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ estado: 1 })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('El pedido se ha marcado como enviado.');
                } else {
                    alert('No se pudo marcar el pedido como enviado.');
                }
            })
            .catch(() => {
                alert('Error al marcar el pedido como enviado.');
            });
        }
    </script>
    <button onclick="printAndCloseModal('printableArea')" class="boton btnImprimir">
        Imprimir pedido
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none" style="vertical-align: middle; margin-left: 8px;">
            <path d="M8.71593 4.72729C8.16741 4.72729 7.64136 4.95853 7.2535 5.37014C6.86564 5.78174 6.64774 6.34 6.64774 6.9221V9.11691H5.61365C5.06514 9.11691 4.53909 9.34814 4.15123 9.75975C3.76337 10.1714 3.54547 10.7296 3.54547 11.3117L3.54547 14.6039C3.54547 15.186 3.76337 15.7443 4.15123 16.1559C4.53909 16.5675 5.06514 16.7987 5.61365 16.7987H6.64774V17.8961C6.64774 18.4782 6.86564 19.0365 7.2535 19.4481C7.64136 19.8597 8.16741 20.0909 8.71593 20.0909H14.9205C15.469 20.0909 15.995 19.8597 16.3829 19.4481C16.7708 19.0365 16.9887 18.4782 16.9887 17.8961V16.7987H18.0227C18.5713 16.7987 19.0973 16.5675 19.4852 16.1559C19.873 15.7443 20.0909 15.186 20.0909 14.6039V11.3117C20.0909 10.7296 19.873 10.1714 19.4852 9.75975C19.0973 9.34814 18.5713 9.11691 18.0227 9.11691H16.9887V6.9221C16.9887 6.34 16.7708 5.78174 16.3829 5.37014C15.995 4.95853 15.469 4.72729 14.9205 4.72729H8.71593ZM7.68184 6.9221C7.68184 6.63105 7.79078 6.35192 7.98471 6.14612C8.17864 5.94032 8.44167 5.8247 8.71593 5.8247H14.9205C15.1947 5.8247 15.4578 5.94032 15.6517 6.14612C15.8456 6.35192 15.9546 6.63105 15.9546 6.9221V9.11691H7.68184V6.9221ZM8.71593 12.4091C8.16741 12.4091 7.64136 12.6404 7.2535 13.052C6.86564 13.4636 6.64774 14.0218 6.64774 14.6039V15.7013H5.61365C5.3394 15.7013 5.07637 15.5857 4.88244 15.3799C4.68851 15.1741 4.57956 14.895 4.57956 14.6039V11.3117C4.57956 11.0207 4.68851 10.7415 4.88244 10.5357C5.07637 10.3299 5.3394 10.2143 5.61365 10.2143H18.0227C18.297 10.2143 18.56 10.3299 18.754 10.5357C18.9479 10.7415 19.0568 11.0207 19.0568 11.3117V14.6039C19.0568 14.895 18.9479 15.1741 18.754 15.3799C18.56 15.5857 18.297 15.7013 18.0227 15.7013H16.9887V14.6039C16.9887 14.0218 16.7708 13.4636 16.3829 13.052C15.995 12.6404 15.469 12.4091 14.9205 12.4091H8.71593ZM15.9546 14.6039V17.8961C15.9546 18.1872 15.8456 18.4663 15.6517 18.6721C15.4578 18.8779 15.1947 18.9935 14.9205 18.9935H8.71593C8.44167 18.9935 8.17864 18.8779 7.98471 18.6721C7.79078 18.4663 7.68184 18.1872 7.68184 17.8961V14.6039C7.68184 14.3129 7.79078 14.0337 7.98471 13.8279C8.17864 13.6221 8.44167 13.5065 8.71593 13.5065H14.9205C15.1947 13.5065 15.4578 13.6221 15.6517 13.8279C15.8456 14.0337 15.9546 14.3129 15.9546 14.6039Z" fill="black" fill-opacity="0.6" />
        </svg>
    </button>
    <script>
        function printAndCloseModal(divId) {
            printDiv(divId);
            const modal = document.querySelector('.modal');
            if (modal) {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                modalInstance.hide();
            }
        }
    </script>

    <div id="printableArea" style="background-color: #fff; max-width: 1240px; margin: 0 auto; padding: 40px 60px; margin: 40px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); min-height: 90vh;">
        <style>
            @media print {
                html, body {
                height: auto;
                margin: 0;
                padding: 0;
                width: auto;
                }
                #printableArea {
                width: auto !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
                box-shadow: none !important;
                }

                @page {
                    size: A4 portrait;
                    margin: 40px;
                }
            }
            #cabecera {
                line-height: 1.2;
                font-size: 18px;
            }
            #cabecera strong {
                font-size: unset !important;
            }
        </style>
        <div style="display: flex;flex-direction: row;height: 280px;margin-bottom: 20px;" id="cabecera">
            <!-- Columna izquierda -->
            <div style="flex: 1; margin: 2px 0;margin-right: 40px;">
                <?php
                $data = datos_user();
                $logo = $data['url_logo'];
                ?>
                <img src="<?php
                    $session = session();
                    $session_data = $session->get('logged_in');
                    $id_empresa = $session_data['id_empresa'];
                    echo base_url('public/assets/uploads/files/' . $logo);
                ?>" class="logo_partes"><br>
                <div style="margin-top: 11px;">
                    <strong>ASISTENCIA CDC FACILITY MANAGEMENT S.L</strong><br>
                    B12884219<br>
                    C/ Barranc de Rátils 8-10, 12200 <br>
                    Onda, Castellón de la Plana<br>
                    España<br>
                    Tel: 964 095 117
                </div>
            </div>
            <!-- Columna derecha -->
            <div style="flex: 1; margin: 2px 0; text-align:right">
                <div style="margin-top: 33px;">
                    <div style="background: #000; color: #fff; padding: 20px; font-weight: bold; font-size: 20px; width: 200px; margin-left: auto; text-align: center;margin-bottom: 20px;">
                        <span style="font-size: 16px;">Ped:</span>
                        <span style="font-size: 24px;">
                            <?php echo $pedido->id_pedido_cliente; ?>-<?php echo $pedido->id_ped_terceros; ?>
                        </span>
                    </div>
                    <div>
                        Proveedor: <strong><?= esc($pedido->nombre_proveedor) ?></strong><br>
                        Fecha: <strong><?= date('d/m/Y', strtotime($pedido->fecha_creacion)) ?></strong><br>
                    </div>
                </div>
            </div>
        </div>
        <div id="contenedor_pedido" style="height:40vh;">
        <h1>PEDIDO A PROVEEDOR</h1>
        <hr>
            <div id="pedido_info" style="margin-bottom: 20px; display: flex; flex-direction: row; gap: 40px; align-items: flex-start;">
                <div style="flex: 1; text-align: left;">
                    Cantidad:<br>
                    <strong><?= esc($pedido->cantidad) ?></strong>
                </div>
                <div style="flex: 1; text-align: left;">
                    Ref:<br>
                    <strong><?= esc($pedido->ref_producto) ?></strong>
                </div>
                <div style="flex: 2; text-align: left;">
                    Observaciones:<br>
                    <strong><?= esc($pedido->observaciones) ?></strong>
                </div>
            </div>
        <hr>
        </div>
        <div style="font-size: 16px; color: #000; margin-top: 20px; height:20vh;">
            Firma del proveedor: <br><br><br><br><br><br><br>Fecha:
        </div>
        <div id="footer" style="margin-top: 80px; margin-bottom: 20px; text-align: center; font-size: 14px; color: #666; padding: 20px; border-top: 1px solid #999;">
            <strong style="font-size:14px;">www.offertiles.com</strong><br>
            <p>Este documento es un pedido a proveedor y no constituye una factura. Por favor, revise que los materiales entregados coinciden el pedido realizado.<br>
            Pedido realizado por: <strong style="font-size:14px;"> <?= esc($pedido->nombre_usuario) ?> <?= esc($pedido->apellidos_usuario) ?></strong> | Fecha de impresión: <strong style="font-size:14px;"><?= date('d/m/Y') ?></strong></p>
        </div>
    </div>
    <!-- /#Printable area -->
</div> <!-- Fondo -->
  