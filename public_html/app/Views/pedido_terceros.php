<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/partes.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>">

<div id="fondo">
    <?php
    $volver = isset($_GET['pg2']) ? $_GET['pg2'] : 'javascript:window.close();';
    ?>
    <!-- Botón SOLO imprimir -->
<button type="button" class="boton btnImprimir" onclick="printOnly('printableArea')">
    Imprimir pedido
    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none" style="vertical-align: middle; margin-left: 8px;">
        <!-- ...icono... -->
    </svg>
</button>

<!-- Botón imprimir y marcar como enviado -->
<button type="button" class="boton btnEditar" onclick="printAndMarkEnviado('printableArea', <?= $pedido->id_ped_terceros ?>)">
    Imprimir y marcar pedido como enviado
    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
        <!-- ...icono... -->
    </svg>
</button>

<script>
// Imprimir solo el área
function printOnly(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var originalContents = document.body.innerHTML;
    var headContents = document.head.innerHTML; // Captura los estilos y scripts del head

    document.body.innerHTML = printContents;
    document.head.innerHTML = headContents; // Vuelve a poner el head por si se pierde

    window.print();

    document.body.innerHTML = originalContents;
    document.head.innerHTML = headContents; // Restaurar head por seguridad
    window.location.reload();
}

// Imprimir y marcar como enviado
function printAndMarkEnviado(divId, id_ped_terceros) {
    printOnly(divId);
    fetch('<?= base_url('Pedidos_terceros/marcarenviado/') ?>' + id_ped_terceros, {
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

    <div id="printableArea" style="background-color: #fff; max-width: 1240px; margin: 0 auto; padding: 40px 60px; margin: 40px auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); min-height: 90vh;">
        
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
                ?>" class="logo_partes" style="max-width:200px;"><br>
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
        <div id="contenedor_pedido" style="height:30vh;width:100%;">
        <h1>PEDIDO A PROVEEDOR</h1>
        <hr style="max-width:100%!Important;">
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
        <hr style="max-width:100%!Important;">
        </div>
        <div style="font-size: 16px; color: #000; margin-top: 20px; height:25vh;">
            Firma del proveedor: <br><br><br><br><br><br><br>Fecha:
        </div>
        <div id="footer" style=" text-align: center; font-size: 14px; color: #666; padding: 20px; border-top: 1px solid #999;">
            <strong style="font-size:14px;">www.offertiles.com</strong><br>
            <p>Este documento es un pedido a proveedor y no constituye una factura. Por favor, revise que los materiales entregados coinciden el pedido realizado.<br>
            Pedido realizado por: <strong style="font-size:14px;"> <?= esc($pedido->nombre_usuario) ?> <?= esc($pedido->apellidos_usuario) ?></strong> | Fecha de impresión: <strong style="font-size:14px;"><?= date('d/m/Y') ?></strong></p>
        </div>
    </div>
    <!-- /#Printable area -->
</div> <!-- Fondo -->
  