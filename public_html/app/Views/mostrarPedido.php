<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<!-- Estilos y Scripts -->
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">

<h2 class="titlepedidosmostrar">Pedidos</h2>
<div class="botonSeparados">
    <a href="<?= base_url('pedidos/add') ?>" class="btn boton btnAdd" style="margin-left: 25px;">Añadir Pedido
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
            <path
                d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z"
                fill="white" />
        </svg>
    </a>
    <button id="clear-filters" class="btn boton btnEliminarfiltros">Quitar Filtros
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
            <path
                d="M7.54974 8.04975C7.62521 7.97408 7.71487 7.91405 7.81358 7.87309C7.91229 7.83213 8.01812 7.81105 8.12499 7.81105C8.23186 7.81105 8.33768 7.83213 8.43639 7.87309C8.5351 7.91405 8.62476 7.97408 8.70024 8.04975L13 12.3511L17.2997 8.04975C17.3753 7.97421 17.465 7.91428 17.5637 7.8734C17.6624 7.83251 17.7682 7.81147 17.875 7.81147C17.9818 7.81147 18.0876 7.83251 18.1863 7.8734C18.285 7.91428 18.3747 7.97421 18.4502 8.04975C18.5258 8.12529 18.5857 8.21497 18.6266 8.31368C18.6675 8.41238 18.6885 8.51816 18.6885 8.625C18.6885 8.73183 18.6675 8.83762 18.6266 8.93632C18.5857 9.03502 18.5258 9.12471 18.4502 9.20025L14.1489 13.5L18.4502 17.7997C18.5258 17.8753 18.5857 17.965 18.6266 18.0637C18.6675 18.1624 18.6885 18.2682 18.6885 18.375C18.6885 18.4818 18.6675 18.5876 18.6266 18.6863C18.5857 18.785 18.5258 18.8747 18.4502 18.9502C18.3747 19.0258 18.285 19.0857 18.1863 19.1266C18.0876 19.1675 17.9818 19.1885 17.875 19.1885C17.7682 19.1885 17.6624 19.1675 17.5637 19.1266C17.465 19.0857 17.3753 19.0258 17.2997 18.9502L13 14.6489L8.70024 18.9502C8.62469 19.0258 8.53501 19.0857 8.43631 19.1266C8.33761 19.1675 8.23182 19.1885 8.12499 19.1885C8.01815 19.1885 7.91237 19.1675 7.81366 19.1266C7.71496 19.0857 7.62528 19.0258 7.54974 18.9502C7.47419 18.8747 7.41427 18.785 7.37339 18.6863C7.3325 18.5876 7.31146 18.4818 7.31146 18.375C7.31146 18.2682 7.3325 18.1624 7.37339 18.0637C7.41427 17.965 7.47419 17.8753 7.54974 17.7997L11.8511 13.5L7.54974 9.20025C7.47407 9.12477 7.41404 9.03511 7.37308 8.9364C7.33212 8.83769 7.31104 8.73187 7.31104 8.625C7.31104 8.51813 7.33212 8.4123 7.37308 8.31359C7.41404 8.21488 7.47407 8.12522 7.54974 8.04975Z"
                fill="white" />
        </svg>
    </button>
</div>
<br>

<?php
$estadoMap = [
    "0" => "Pendiente de material",
    "1" => "Falta Material",
    "2" => "Material recibido",
    "3" => "En Máquinas",
    "4" => "Terminado",
    "5" => "Entregado",
    "6" => "Anulado"
];



function iconoEstado($svg, $colorFondo, $tooltip) {
    return '<span style="
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: ' . $colorFondo . ';
        border-radius: 6px;
        padding: 4px;
        cursor: pointer;
    " title="' . htmlspecialchars($tooltip) . '">' .
        str_replace('fill="yellow"', 'fill="black"', $svg) .
    '</span>';
}


$abiertaIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-bell" viewBox="0 0 16 16">
<path d="M8 16a2 2 0 0 0 1.985-1.75H6.015A2 2 0 0 0 8 16zm.7-14.75a.5.5 0 0 1 .6.5v.5c0 .276-.224.5-.5.5h-.4a.5.5 0 0 1-.5-.5v-.5a.5.5 0 0 1 .5-.5h.3zm-3.5 0a.5.5 0 0 1 .5.5v.5c0 .276-.224.5-.5.5h-.3a.5.5 0 0 1-.5-.5v-.5a.5.5 0 0 1 .5-.5h.3zm6.3 1.5a.5.5 0 0 1 .5.5v.5c0 .276-.224.5-.5.5h-.3a.5.5 0 0 1-.5-.5v-.5a.5.5 0 0 1 .5-.5h.3zM8 1a5.978 5.978 0 0 1 4.546 2.09c.346.41.654.87.91 1.364.256.494.46 1.02.61 1.564.15.544.234 1.11.234 1.682v2.5l1.5 1.5v.5H1v-.5l1.5-1.5v-2.5c0-.572.084-1.138.234-1.682.15-.544.354-1.07.61-1.564.256-.494.564-.954.91-1.364A5.978 5.978 0 0 1 8 1z"/>
</svg>';
?>

<div id="pedidoTable" class="ag-theme-alpine" style="height: 400px; width: 100%;"></div>

<script>
    // Función para copiar al portapapeles
    function copyToClipboard(value) {
        const textArea = document.createElement('textarea');
        textArea.value = value;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
    }
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Iniciando Ag-Grid para pedidos...');

        const columnDefs = [
            {
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: function (params) {
                    const editBtn = `<a href="<?= base_url('pedidos/edit/') ?>${params.data.id_pedido}" class="btn botonTabla btnEditarTabla">Editar
                      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                    <path d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z" fill="white"/>
                    </svg></a>`;
                    
                     // Mostrar el botón "Imprimir" solo si bt_imprimir es igual a 1
                    const printBtn = params.data.bt_imprimir == 1
                        ? `<a href="<?= base_url('pedidos/print/') ?>${params.data.id_pedido}" class="btn botonTabla btnImprimirTabla" target="_blank">Imprimir
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                            <path d="M8.71593 4.72729C8.16741 4.72729 7.64136 4.95853 7.2535 5.37014C6.86564 5.78174 6.64774 6.34 6.64774 6.9221V9.11691H5.61365C5.06514 9.11691 4.53909 9.34814 4.15123 9.75975C3.76337 10.1714 3.54547 10.7296 3.54547 11.3117L3.54547 14.6039C3.54547 15.186 3.76337 15.7443 4.15123 16.1559C4.53909 16.5675 5.06514 16.7987 5.61365 16.7987H6.64774V17.8961C6.64774 18.4782 6.86564 19.0365 7.2535 19.4481C7.64136 19.8597 8.16741 20.0909 8.71593 20.0909H14.9205C15.469 20.0909 15.995 19.8597 16.3829 19.4481C16.7708 19.0365 16.9887 18.4782 16.9887 17.8961V16.7987H18.0227C18.5713 16.7987 19.0973 16.5675 19.4852 16.1559C19.873 15.7443 20.0909 15.186 20.0909 14.6039V11.3117C20.0909 10.7296 19.873 10.1714 19.4852 9.75975C19.0973 9.34814 18.5713 9.11691 18.0227 9.11691H16.9887V6.9221C16.9887 6.34 16.7708 5.78174 16.3829 5.37014C15.995 4.95853 15.469 4.72729 14.9205 4.72729H8.71593ZM7.68184 6.9221C7.68184 6.63105 7.79078 6.35192 7.98471 6.14612C8.17864 5.94032 8.44167 5.8247 8.71593 5.8247H14.9205C15.1947 5.8247 15.4578 5.94032 15.6517 6.14612C15.8456 6.35192 15.9546 6.63105 15.9546 6.9221V9.11691H7.68184V6.9221ZM8.71593 12.4091C8.16741 12.4091 7.64136 12.6404 7.2535 13.052C6.86564 13.4636 6.64774 14.0218 6.64774 14.6039V15.7013H5.61365C5.3394 15.7013 5.07637 15.5857 4.88244 15.3799C4.68851 15.1741 4.57956 14.895 4.57956 14.6039V11.3117C4.57956 11.0207 4.68851 10.7415 4.88244 10.5357C5.07637 10.3299 5.3394 10.2143 5.61365 10.2143H18.0227C18.297 10.2143 18.56 10.3299 18.754 10.5357C18.9479 10.7415 19.0568 11.0207 19.0568 11.3117V14.6039C19.0568 14.895 18.9479 15.1741 18.754 15.3799C18.56 15.5857 18.297 15.7013 18.0227 15.7013H16.9887V14.6039C16.9887 14.0218 16.7708 13.4636 16.3829 13.052C15.995 12.6404 15.469 12.4091 14.9205 12.4091H8.71593ZM15.9546 14.6039V17.8961C15.9546 18.1872 15.8456 18.4663 15.6517 18.6721C15.4578 18.8779 15.1947 18.9935 14.9205 18.9935H8.71593C8.44167 18.9935 8.17864 18.8779 7.98471 18.6721C7.79078 18.4663 7.68184 18.1872 7.68184 17.8961V14.6039C7.68184 14.3129 7.79078 14.0337 7.98471 13.8279C8.17864 13.6221 8.44167 13.5065 8.71593 13.5065H14.9205C15.1947 13.5065 15.4578 13.6221 15.6517 13.8279C15.8456 14.0337 15.9546 14.3129 15.9546 14.6039Z" fill="black" fill-opacity="0.6" />
                        </svg></a>`
                        : '';

                    const deleteBtn = params.data.allowDelete ? `<a href="<?= base_url('pedidos/delete/') ?>${params.data.id_pedido}" class="btn botonTabla btnEliminarTabla" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">Eliminar
                       <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path d="M7.66753 6.776C7.41733 6.776 7.17737 6.87593 7.00045 7.05379C6.82353 7.23166 6.72414 7.47289 6.72414 7.72443V8.67286C6.72414 8.9244 6.82353 9.16563 7.00045 9.3435C7.17737 9.52136 7.41733 9.62129 7.66753 9.62129H8.13923V18.1571C8.13923 18.6602 8.33802 19.1427 8.69186 19.4984C9.0457 19.8541 9.52561 20.054 10.026 20.054H15.6864C16.1868 20.054 16.6667 19.8541 17.0206 19.4984C17.3744 19.1427 17.5732 18.6602 17.5732 18.1571V9.62129H18.0449C18.2951 9.62129 18.5351 9.52136 18.712 9.3435C18.8889 9.16563 18.9883 8.9244 18.9883 8.67286V7.72443C18.9883 7.47289 18.8889 7.23166 18.712 7.05379C18.5351 6.87593 18.2951 6.776 18.0449 6.776H14.743C14.743 6.52446 14.6436 6.28323 14.4667 6.10536C14.2898 5.9275 14.0498 5.82758 13.7996 5.82758H11.9128C11.6626 5.82758 11.4227 5.9275 11.2457 6.10536C11.0688 6.28323 10.9694 6.52446 10.9694 6.776H7.66753ZM10.4977 10.5697C10.6228 10.5697 10.7428 10.6197 10.8313 10.7086C10.9197 10.7975 10.9694 10.9182 10.9694 11.0439V17.6829C10.9694 17.8087 10.9197 17.9293 10.8313 18.0182C10.7428 18.1072 10.6228 18.1571 10.4977 18.1571C10.3726 18.1571 10.2526 18.1072 10.1642 18.0182C10.0757 17.9293 10.026 17.8087 10.026 17.6829V11.0439C10.026 10.9182 10.0757 10.7975 10.1642 10.7086C10.2526 10.6197 10.3726 10.5697 10.4977 10.5697ZM12.8562 10.5697C12.9813 10.5697 13.1013 10.6197 13.1898 10.7086C13.2782 10.7975 13.3279 10.9182 13.3279 11.0439V17.6829C13.3279 17.8087 13.2782 17.9293 13.1898 18.0182C13.1013 18.1072 12.9813 18.1571 12.8562 18.1571C12.7311 18.1571 12.6111 18.1072 12.5227 18.0182C12.4342 17.9293 12.3845 17.8087 12.3845 17.6829V11.0439C12.3845 10.9182 12.4342 10.7975 12.5227 10.7086C12.6111 10.6197 12.7311 10.5697 12.8562 10.5697ZM15.6864 11.0439V17.6829C15.6864 17.8087 15.6367 17.9293 15.5482 18.0182C15.4598 18.1072 15.3398 18.1571 15.2147 18.1571C15.0896 18.1571 14.9696 18.1072 14.8812 18.0182C14.7927 17.9293 14.743 17.8087 14.743 17.6829V11.0439C14.743 10.9182 14.7927 10.7975 14.8812 10.7086C14.9696 10.6197 15.0896 10.5697 15.2147 10.5697C15.3398 10.5697 15.4598 10.6197 15.5482 10.7086C15.6367 10.7975 15.6864 10.9182 15.6864 11.0439Z" fill="white"/>
                    </svg></a>` : '';
                    return `${editBtn} ${printBtn} ${deleteBtn}`;
                },
                minWidth: 260,
                filter: false
            },
            {
                headerName: "ID Pedido",
                field: "id_pedido",
                filter: 'agTextColumnFilter',
                flex: 1,
                minWidth: 140,
                cellRenderer: function (params) {
                    const copyBtn = `<button class="copy-btn botonTabla btnCopiar" onclick="copyToClipboard('${params.value}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 0 2 2H6a2 2 0 0 0-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
                    </svg></button>`;
                    return `${params.value} ${copyBtn}`;
                }
            },
            {
                headerName: "Cliente",
                field: "cliente",
                filter: 'agTextColumnFilter',
                flex: 2,
                cellRenderer: function (params) {
                    return params.value;
                }
            },

            { headerName: "Referencia", field: "referencia", filter: 'agTextColumnFilter', flex: 1 },
            { headerName: "Albarán", field: "albaran", filter: 'agTextColumnFilter', flex: 1 }, 
            { headerName: "Estado", field: "estado", filter: 'agTextColumnFilter', flex: 1 },
            {
                headerName: "Fecha Entrada",
                field: "fecha_entrada",
                filter: 'agTextColumnFilter',
                flex: 1,
                valueFormatter: formatDate
            },
            {
                headerName: "Fecha Entrega",
                field: "fecha_entrega",
                filter: 'agTextColumnFilter',
                flex: 1,
                valueFormatter: formatDate
            },
            { headerName: "Usuario", field: "nombre_usuario", filter: 'agTextColumnFilter', flex: 1 },
            { headerName: "Total", field: "total", filter: 'agTextColumnFilter', flex: 1 }
        ];

        const rowData = [
            <?php foreach ($pedidos as $pedido): ?> {
                id_pedido: "<?= $pedido->id_pedido ?>",
                fecha_entrada: "<?= date('Y-m-d', strtotime($pedido->fecha_entrada)) ?>",
                fecha_entrega: "<?= date('Y-m-d', strtotime($pedido->fecha_entrega)) ?>",
                cliente: `<?php
                $tooltip = '';
                if ($pedido->estado_incidencia == 1) {
                    $tooltip = 'Abierta: ' . $pedido->incidencia;
                } elseif ($pedido->estado_incidencia == 2) {
                    $tooltip = 'En espera: ' . $pedido->incidencia;
                }

                echo ($pedido->estado_incidencia == 2
                    ? iconoEstado($abiertaIcon, '#00bfff', $tooltip)
                    : ($pedido->estado_incidencia == 1
                        ? iconoEstado($abiertaIcon, 'orange', $tooltip)
                        : '')
                );
                ?> <?= esc($pedido->nombre_cliente) ?>`,


                referencia: "<?= $pedido->referencia ?>",
                albaran: "<?= $pedido->albaran ?>",
                estado: "<?= $estadoMap[$pedido->estado] ?>",
                nombre_usuario: "<?= $pedido->nombre_usuario ?>",
                total: "<?= $pedido->total_pedido ?>€",
                bt_imprimir: <?= $pedido->bt_imprimir ?>,
                allowDelete: <?= json_encode($allow_delete) ?>
            },
            <?php endforeach; ?>
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            rowData: rowData,
            pagination: true,
            paginationPageSize: 10,
            defaultColDef: {
                sortable: true,
                filter: true,
                floatingFilter: true,
                resizable: true
            },
            rowHeight: 60,
            domLayout: 'autoHeight',
            localeText: {
                noRowsToShow: 'No hay registros disponibles.'
            },
            onGridReady: function (params) {
                params.api.sizeColumnsToFit();
                const savedFilterModel = localStorage.getItem('gridFilterModel');
                if (savedFilterModel) {
                    params.api.setFilterModel(JSON.parse(savedFilterModel));
                }
            },
            onFilterChanged: function (params) {
                const filterModel = params.api.getFilterModel();
                localStorage.setItem('gridFilterModel', JSON.stringify(filterModel));
            },
            getRowClass: function (params) {
                switch (params.data.estado) {
                    case "Pendiente de material": return 'estado0';
                    case "Falta Material": return 'estado1';
                    case "Material recibido": return 'estado2';
                    case "En Máquinas": return 'estado3';
                    case "Terminado": return 'estado4';
                    case "Entregado": return 'estado5';
                    case "Anulado": return 'estado6';
                    default: return '';
                }
            },
        };

        const eGridDiv = document.querySelector('#pedidoTable');
        if (!eGridDiv) {
            console.error('El contenedor del grid no se encontró en el DOM.');
            return;
        }
        new agGrid.Grid(eGridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });

        function formatDate(params) {
            if (!params.value) return '';
            const date = new Date(params.value);
            return ('0' + date.getDate()).slice(-2) + '/' + ('0' + (date.getMonth() + 1)).slice(-2) + '/' + date.getFullYear();
        }
    });
</script>

<?= $this->endSection() ?>