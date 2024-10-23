<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/pedido.css') ?>?v=<?= time() ?>">

<div class="container mt-5 editpedido">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <h2 class="titleditPedido">Editar Pedido</h2>
    <div class="mb-3">
        <label for="acciones" class="form-label"></label>
        <div class="d-flex gap-2">
            <a href="<?= base_url('pedidos/print/' . $pedido->id_pedido . '?volver=' . urlencode(current_url())) ?>" class="btn btn-info btn-sm" target="_blank">
                <i class="fa fa-print"></i> Imprimir Pedido
            </a>
            <a href="<?= base_url('pedidos/parte_complejo/' . $pedido->id_pedido . '?volver=' . urlencode(current_url())) ?>" class="btn btn-secondary btn-sm" target="_blank">
                <i class="fa fa-truck"></i> Parte Complejo
            </a>
            <button type="button" class="btn btn-warning" id="openModal" data-bs-toggle="modal" data-bs-target="#myModal">
                <i class="fa fa-truck fa-fw"></i> Rutas de transporte
            </button>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Rutas de transporte</h4>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body" id="modalContent" style="padding: 0;">
                            <div class="text-center" id="loading">
                                <p>Cargando...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="<?= base_url('pedidos/entregar/' . $pedido->id_pedido) ?>" class="btn btn-success btn-sm">
                <i class="fa fa-check fa-fw"></i> Entregar Pedido
            </a>
            <a href="<?= base_url('pedidos/anular/' . $pedido->id_pedido) ?>" class="btn btn-danger btn-sm btn_anular">
                <i class="fa fa-trash fa-fw"></i> Anular Pedido
            </a>
        </div>
    </div>
    <form action="<?= base_url('pedidos/update/' . $pedido->id_pedido) ?>" method="post" class="formeditPedido">
        <div class="form-group">
            <label for="id_cliente">Empresa:</label>
            <select id="id_cliente" name="id_cliente" class="form-control" required>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>" <?= $pedido->id_cliente == $cliente['id_cliente'] ? 'selected' : '' ?>>
                        <?= $cliente['nombre_cliente'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="referencia">Referencia:</label>
            <input type="text" id="referencia" name="referencia" class="form-control" value="<?= esc($pedido->referencia) ?>">
        </div>
        <div class="form-group">
            <label for="fecha_entrada">Fecha de Entrada:</label>
            <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" value="<?= esc($pedido->fecha_entrada) ?>" required>
        </div>
        <div class="form-group">
            <label for="fecha_entrega">Fecha de Entrega:</label>
            <input type="date" id="fecha_entrega" name="fecha_entrega" class="form-control" value="<?= esc($pedido->fecha_entrega) ?>" required>
        </div>
        <div class="form-group">
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones" class="form-control" style="height: 60px;"><?= esc($pedido->observaciones) ?></textarea>
        </div>
        <div class="form-group" style="font-size:15px;">
            <label>ID del Pedido:</label>
            <strong><?= esc($pedido->id_pedido) ?></strong>
        </div>
        <div class="btnsEditPedido">
            <button type="submit" class="boton btnGuardar">
                Guardar Pedido
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27" fill="none">
                    <path d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z" fill="white" />
                </svg>
            </button>
            <a href="<?= base_url('/pedidos/enmarcha') ?>" class="boton volverButton">
                Volver
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 13C19.5 13.2155 19.4144 13.4221 19.262 13.5745C19.1096 13.7269 18.903 13.8125 18.6875 13.8125H9.27386L12.7627 17.2997C12.8383 17.3753 12.8982 17.465 12.9391 17.5637C12.98 17.6624 13.001 17.7682 13.001 17.875C13.001 17.9818 12.98 18.0876 12.9391 18.1863C12.8982 18.285 12.8383 18.3747 12.7627 18.4502C12.6872 18.5258 12.5975 18.5857 12.4988 18.6266C12.4001 18.6675 12.2943 18.6885 12.1875 18.6885C12.0807 18.6885 11.9749 18.6675 11.8762 18.6266C11.7775 18.5857 11.6878 18.5258 11.6122 18.4502L6.73724 13.5752C6.66157 13.4998 6.60154 13.4101 6.56058 13.3114C6.51962 13.2127 6.49854 13.1069 6.49854 13C6.49854 12.8931 6.51962 12.7873 6.56058 12.6886C6.60154 12.5899 6.66157 12.5002 6.73724 12.4247L11.6122 7.54974C11.7648 7.39717 11.9717 7.31146 12.1875 7.31146C12.4032 7.31146 12.6102 7.39717 12.7627 7.54974C12.9153 7.7023 13.001 7.90923 13.001 8.12499C13.001 8.34075 12.9153 8.54767 12.7627 8.70024L9.27386 12.1875H18.6875C18.903 12.1875 19.1096 12.2731 19.262 12.4255C19.4144 12.5778 19.5 12.7845 19.5 13Z" fill="white" />
                </svg>
            </a>
        </div>
    </form>
    <div class="form-group">
        <?php
        $estados_texto = [
            "0" => "Pendiente de material",
            "1" => "Falta Material",
            "2" => "Material recibido",
            "3" => "En Máquinas",
            "4" => "Terminado",
            "5" => "Entregado",
            "6" => "Anulado"
        ];
        ?>
        <h3 style="margin-left:5px; margin-top:-5px;">Líneas del Pedido</h3>
        <hr style="border: 5px solid #FFCC32; margin-top: 10px; margin-bottom: 20px;">
        <br>
        <div class="botonSeparados">
            <button type="button" class="boton btnAdd" id="openAddLineaPedidoModal" style="margin-left:35px;" data-id-pedido="<?= $pedido->id_pedido ?>">
                Añadir Línea de Pedido
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                    <path d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z" fill="white" />
                </svg>
            </button>
            <div>
                <button id="clear-filters" class="boton btnEliminarfiltros" style="margin-right: -30px;">
                    Quitar Filtros
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                        <path d="M7.54974 8.04975C7.62521 7.97408 7.71487 7.91405 7.81358 7.87309C7.91229 7.83213 8.01812 7.81105 8.12499 7.81105C8.23186 7.81105 8.33768 7.83213 8.43639 7.87309C8.5351 7.91405 8.62476 7.97408 8.70024 8.04975L13 12.3511L17.2997 8.04975C17.3753 7.97421 17.465 7.91428 17.5637 7.8734C17.6624 7.83251 17.7682 7.81147 17.875 7.81147C17.9818 7.81147 18.0876 7.83251 18.1863 7.8734C18.285 7.91428 18.3747 7.97421 18.4502 8.04975C18.5258 8.12529 18.5857 8.21497 18.6266 8.31368C18.6675 8.41238 18.6885 8.51816 18.6885 8.625C18.6885 8.73183 18.6675 8.83762 18.6266 8.93632C18.5857 9.03502 18.5258 9.12471 18.4502 9.20025L14.1489 13.5L18.4502 17.7997C18.5258 17.8753 18.5857 17.965 18.6266 18.0637C18.6675 18.1624 18.6885 18.2682 18.6885 18.375C18.6885 18.4818 18.6675 18.5876 18.6266 18.6863C18.5857 18.785 18.5258 18.8747 18.4502 18.9502C18.3747 19.0258 18.285 19.0857 18.1863 19.1266C18.0876 19.1675 17.9818 19.1885 17.875 19.1885C17.7682 19.1885 17.6624 19.1675 17.5637 19.1266C17.465 19.0857 17.3753 19.0258 17.2997 18.9502L13 14.6489L8.70024 18.9502C8.62469 19.0258 8.53501 19.0857 8.43631 19.1266C8.33761 19.1675 8.23182 19.1885 8.12499 19.1885C8.01815 19.1885 7.91237 19.1675 7.81366 19.1266C7.71496 19.0857 7.62528 19.0258 7.54974 18.9502C7.47419 18.8747 7.41427 18.785 7.37339 18.6863C7.3325 18.5876 7.31146 18.4818 7.31146 18.375C7.31146 18.2682 7.3325 18.1624 7.37339 18.0637C7.41427 17.965 7.47419 17.8753 7.54974 17.7997L11.8511 13.5L7.54974 9.20025C7.47407 9.12477 7.41404 9.03511 7.37308 8.9364C7.33212 8.83769 7.31104 8.73187 7.31104 8.625C7.31104 8.51813 7.33212 8.4123 7.37308 8.31359C7.41404 8.21488 7.47407 8.12522 7.54974 8.04975Z" fill="white" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Modal para añadir una nueva línea de pedido -->
        <div class="modal fade" id="addLineaPedidoModal" tabindex="-1" aria-labelledby="addLineaPedidoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLineaPedidoLabel">Añadir Línea de Pedido</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalBodyAddLineaPedido">
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="contenedor-tabla-boton" style="width: 100%;">
            <div id="lineasPedidoGrid" class="ag-theme-alpine" style="height: auto; width: 100%;"></div>
            <a href="<?= base_url('/pedidos/enmarcha') ?>" class="btn volverButton volverLineaPed">Volver</a>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const estadosTexto = <?= json_encode($estados_texto) ?>;
                const columnDefs = [{
                        headerName: 'Acciones',
                        field: 'acciones',
                        cellRenderer: renderActions,
                        cellClass: 'acciones-col',
                        minWidth: 250,
                        filter: false,
                    },
                    {
                        headerName: 'ID Línea',
                        field: 'id_lineapedido',
                        flex: 1,
                        maxWidth: 130,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Uds.',
                        field: 'n_piezas',
                        maxWidth: 100,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Base',
                        field: 'nom_base',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Producto',
                        field: 'nombre_producto',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Estado',
                        field: 'estado',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                        valueGetter: function(params) {
                            return estadosTexto[params.data.estado] || 'Estado desconocido';
                        },
                        valueFormatter: function(params) {
                            return estadosTexto[params.data.estado] || 'Estado desconocido';
                        }
                    },
                    {
                        headerName: 'Med. Inicial',
                        field: 'med_inicial',
                        flex: 1,
                        maxWidth: 130,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Med. Final',
                        field: 'med_final',
                        flex: 1,
                        maxWidth: 130,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                    },
                    {
                        headerName: 'Total',
                        field: 'total_linea',
                        flex: 1,
                        filter: 'agTextColumnFilter',
                        floatingFilter: true,
                        valueFormatter: params => `${params.value} €`,
                    },
                ];
                const rowData = <?= json_encode($lineas_pedido) ?>;

                function renderActions(params) {
                    const id = params.data.id_lineapedido;
                    return `
        <button class="btn botonTabla btnEditar" data-id="${id}" data-bs-toggle="modal" data-bs-target="#editarLineaModal">
        Editar
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
        <path d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z" fill="white"/>
        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z" fill="white"/>
        </svg> 
        </button>
        <button class="btn botonTabla btnImprimir" onclick="mostrarParte(${id})">
        Parte
         <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
        <path d="M8.71593 4.72729C8.16741 4.72729 7.64136 4.95853 7.2535 5.37014C6.86564 5.78174 6.64774 6.34 6.64774 6.9221V9.11691H5.61365C5.06514 9.11691 4.53909 9.34814 4.15123 9.75975C3.76337 10.1714 3.54547 10.7296 3.54547 11.3117L3.54547 14.6039C3.54547 15.186 3.76337 15.7443 4.15123 16.1559C4.53909 16.5675 5.06514 16.7987 5.61365 16.7987H6.64774V17.8961C6.64774 18.4782 6.86564 19.0365 7.2535 19.4481C7.64136 19.8597 8.16741 20.0909 8.71593 20.0909H14.9205C15.469 20.0909 15.995 19.8597 16.3829 19.4481C16.7708 19.0365 16.9887 18.4782 16.9887 17.8961V16.7987H18.0227C18.5713 16.7987 19.0973 16.5675 19.4852 16.1559C19.873 15.7443 20.0909 15.186 20.0909 14.6039V11.3117C20.0909 10.7296 19.873 10.1714 19.4852 9.75975C19.0973 9.34814 18.5713 9.11691 18.0227 9.11691H16.9887V6.9221C16.9887 6.34 16.7708 5.78174 16.3829 5.37014C15.995 4.95853 15.469 4.72729 14.9205 4.72729H8.71593ZM7.68184 6.9221C7.68184 6.63105 7.79078 6.35192 7.98471 6.14612C8.17864 5.94032 8.44167 5.8247 8.71593 5.8247H14.9205C15.1947 5.8247 15.4578 5.94032 15.6517 6.14612C15.8456 6.35192 15.9546 6.63105 15.9546 6.9221V9.11691H7.68184V6.9221ZM8.71593 12.4091C8.16741 12.4091 7.64136 12.6404 7.2535 13.052C6.86564 13.4636 6.64774 14.0218 6.64774 14.6039V15.7013H5.61365C5.3394 15.7013 5.07637 15.5857 4.88244 15.3799C4.68851 15.1741 4.57956 14.895 4.57956 14.6039V11.3117C4.57956 11.0207 4.68851 10.7415 4.88244 10.5357C5.07637 10.3299 5.3394 10.2143 5.61365 10.2143H18.0227C18.297 10.2143 18.56 10.3299 18.754 10.5357C18.9479 10.7415 19.0568 11.0207 19.0568 11.3117V14.6039C19.0568 14.895 18.9479 15.1741 18.754 15.3799C18.56 15.5857 18.297 15.7013 18.0227 15.7013H16.9887V14.6039C16.9887 14.0218 16.7708 13.4636 16.3829 13.052C15.995 12.6404 15.469 12.4091 14.9205 12.4091H8.71593ZM15.9546 14.6039V17.8961C15.9546 18.1872 15.8456 18.4663 15.6517 18.6721C15.4578 18.8779 15.1947 18.9935 14.9205 18.9935H8.71593C8.44167 18.9935 8.17864 18.8779 7.98471 18.6721C7.79078 18.4663 7.68184 18.1872 7.68184 17.8961V14.6039C7.68184 14.3129 7.79078 14.0337 7.98471 13.8279C8.17864 13.6221 8.44167 13.5065 8.71593 13.5065H14.9205C15.1947 13.5065 15.4578 13.6221 15.6517 13.8279C15.8456 14.0337 15.9546 14.3129 15.9546 14.6039Z" fill="black" fill-opacity="0.6"/>
        </svg>
        </button>
        <a href="<?= base_url('pedidos/deleteLinea/') ?>${id}" class="btn botonTabla btnEliminar btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar esta línea?');">
        Eliminar
         <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
        <path d="M7.66753 6.776C7.41733 6.776 7.17737 6.87593 7.00045 7.05379C6.82353 7.23166 6.72414 7.47289 6.72414 7.72443V8.67286C6.72414 8.9244 6.82353 9.16563 7.00045 9.3435C7.17737 9.52136 7.41733 9.62129 7.66753 9.62129H8.13923V18.1571C8.13923 18.6602 8.33802 19.1427 8.69186 19.4984C9.0457 19.8541 9.52561 20.054 10.026 20.054H15.6864C16.1868 20.054 16.6667 19.8541 17.0206 19.4984C17.3744 19.1427 17.5732 18.6602 17.5732 18.1571V9.62129H18.0449C18.2951 9.62129 18.5351 9.52136 18.712 9.3435C18.8889 9.16563 18.9883 8.9244 18.9883 8.67286V7.72443C18.9883 7.47289 18.8889 7.23166 18.712 7.05379C18.5351 6.87593 18.2951 6.776 18.0449 6.776H14.743C14.743 6.52446 14.6436 6.28323 14.4667 6.10536C14.2898 5.9275 14.0498 5.82758 13.7996 5.82758H11.9128C11.6626 5.82758 11.4227 5.9275 11.2457 6.10536C11.0688 6.28323 10.9694 6.52446 10.9694 6.776H7.66753ZM10.4977 10.5697C10.6228 10.5697 10.7428 10.6197 10.8313 10.7086C10.9197 10.7975 10.9694 10.9182 10.9694 11.0439V17.6829C10.9694 17.8087 10.9197 17.9293 10.8313 18.0182C10.7428 18.1072 10.6228 18.1571 10.4977 18.1571C10.3726 18.1571 10.2526 18.1072 10.1642 18.0182C10.0757 17.9293 10.026 17.8087 10.026 17.6829V11.0439C10.026 10.9182 10.0757 10.7975 10.1642 10.7086C10.2526 10.6197 10.3726 10.5697 10.4977 10.5697ZM12.8562 10.5697C12.9813 10.5697 13.1013 10.6197 13.1898 10.7086C13.2782 10.7975 13.3279 10.9182 13.3279 11.0439V17.6829C13.3279 17.8087 13.2782 17.9293 13.1898 18.0182C13.1013 18.1072 12.9813 18.1571 12.8562 18.1571C12.7311 18.1571 12.6111 18.1072 12.5227 18.0182C12.4342 17.9293 12.3845 17.8087 12.3845 17.6829V11.0439C12.3845 10.9182 12.4342 10.7975 12.5227 10.7086C12.6111 10.6197 12.7311 10.5697 12.8562 10.5697ZM15.6864 11.0439V17.6829C15.6864 17.8087 15.6367 17.9293 15.5482 18.0182C15.4598 18.1072 15.3398 18.1571 15.2147 18.1571C15.0896 18.1571 14.9696 18.1072 14.8812 18.0182C14.7927 17.9293 14.743 17.8087 14.743 17.6829V11.0439C14.743 10.9182 14.7927 10.7975 14.8812 10.7086C14.9696 10.6197 15.0896 10.5697 15.2147 10.5697C15.3398 10.5697 15.4598 10.6197 15.5482 10.7086C15.6367 10.7975 15.6864 10.9182 15.6864 11.0439Z" fill="white"/>
        </svg>
        </a>
    `;
                }
                const gridOptions = {
                    columnDefs: columnDefs,
                    rowData: rowData,
                    pagination: true,
                    paginationPageSize: 10,
                    headerHeight: 50,
                    floatingFiltersHeight: 40,
                    defaultColDef: {
                        sortable: true,
                        filter: true,
                        floatingFilter: true,
                        resizable: true,
                    },
                    domLayout: 'autoHeight',
                    rowHeight: 60,
                    localeText: {
                        noRowsToShow: 'No hay registros disponibles.'
                    },
                    onGridReady: function(params) {
                        params.api.sizeColumnsToFit();
                        window.gridApi = params.api;
                    },
                    getRowClass: function(params) {
                        const estadoTexto = estadosTexto[params.data.estado] || 'Estado desconocido';
                        switch (estadoTexto) {
                            case "Pendiente de material":
                                return 'estado0';
                            case "Falta Material":
                                return 'estado1';
                            case "Material recibido":
                                return 'estado2';
                            case "En Máquinas":
                                return 'estado3';
                            case "Terminado":
                                return 'estado4';
                            case "Entregado":
                                return 'estado5';
                            case "Anulado":
                                return 'estado6';
                            default:
                                return '';
                        }
                    }

                };
                const eGridDiv = document.querySelector('#lineasPedidoGrid');
                new agGrid.Grid(eGridDiv, gridOptions);
                document.querySelector('#clear-filters').addEventListener('click', function() {
                    if (window.gridApi) {
                        window.gridApi.setFilterModel(null);
                        window.gridApi.onFilterChanged();
                    }
                });
                document.querySelector('#reload-page').addEventListener('click', function() {
                    location.reload();
                });
                window.addEventListener('resize', function() {
                    if (window.gridApi) {
                        window.gridApi.sizeColumnsToFit();
                    }
                });
            });
            // Función para mostrar el modal del parte
            function mostrarParte(id_lineapedido) {
                $.ajax({
                    url: '<?= base_url("partes/print/") ?>' + id_lineapedido,
                    type: 'GET',
                    success: function(data) {
                        $('#modalParteContent').html(data);
                        $('#parteModal').modal('show');
                        // Almacenar en sessionStorage que el modal está abierto y el ID
                        sessionStorage.setItem('modalParteAbierto', 'true');
                        sessionStorage.setItem('modalParteId', id_lineapedido);
                    },
                    error: function() {
                        $('#modalParteContent').html('<p class="text-danger">Error al cargar el parte.</p>');
                        $('#parteModal').modal('show');
                        sessionStorage.setItem('modalParteAbierto', 'true');
                        sessionStorage.setItem('modalParteId', id_lineapedido);
                    }
                });
            }

            function printDiv(divId) {
                var printContents = document.getElementById(divId).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
        <!-- Modal para mostrar el Parte -->
        <div class="modal fade" id="parteModal" tabindex="-1" aria-labelledby="parteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="parteModalLabel">Parte de Trabajo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalParteContent">
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('#parteModal').on('hidden.bs.modal', function() {
                location.reload();
            });
        </script>
        <div class="modal fade" id="editarLineaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="overflow-y: hidden !important;">
                    <div class="modal-body" id="modalBodyEditarLineaPedido" style="overflow-y: auto !important;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#openModal').on('click', function() {
                var pedidoId = '<?= $pedido->id_pedido ?>';
                var clienteId = '<?= $pedido->id_cliente ?>';
                $('#modalContent').html('<div class="text-center"><p>Cargando...</p></div>');
                $.ajax({
                    url: '<?= base_url('Ruta_pedido/rutas') ?>/' + pedidoId + '/' + clienteId,
                    method: 'GET',
                    success: function(response) {
                        if (response.error) {
                            $('#modalContent').html('<div class="alert alert-danger">' + response.error + '</div>');
                            return;
                        }
                        $('#modalContent').html(`
                    <div id="rutasContainer">
                        <div id="botonesRuta" class="d-flex justify-content-between align-items-center botoneseditRuta botonesRuta">
                            <button type="button" class="btn btnAddRuta" id="openAddRuta" style="flex-grow: 0;">+ Añadir Ruta</button>
                <!-- Botón "Eliminar Filtros" dentro del modal -->
                <button id="clear-filters-rutas" class="btn btnEliminarfiltrosRuta" style="flex-grow: 0;">Eliminar Filtros</button>
                        </div>
                        <br>
                        <div id="gridRutas" class="ag-theme-alpine"  style="width: 100%;"></div>
                    </div>
                    <div id="addRutaForm" style="display:none;"></div>
                `);
                        initializeAgGrid(response.rutas, response.poblacionesMap, response.transportistas);
                        setupEventHandlers();
                    },
                    error: function() {
                        $('#modalContent').html('<div class="alert alert-danger">Error al cargar las rutas.</div>');
                    }
                });
            });

            // Función para inicializar ag-Grid
            function initializeAgGrid(rutas, poblacionesMap, transportistasMap) {
                var estadoMap = {
                    1: 'No preparado',
                    2: 'Recogido',
                    0: 'Pendiente'
                };
                var columnDefs = [{
                        headerName: "Acciones",
                        field: "acciones",
                        cellRenderer: function(params) {
                            var editBtn = `<button class="btn btnEditarRuta" data-id="${params.data.id_ruta}" onclick="editarRuta(${params.data.id_ruta})">
                <span class="material-symbols-outlined icono">edit</span>Editar</button>`;
                            var deleteBtn = `<button class="btn btnEliminarRuta" data-id="${params.data.id_ruta}" onclick="eliminarRuta(${params.data.id_ruta})">
                <span class="material-symbols-outlined icono">delete</span>Eliminar</button>`;
                            return `${editBtn} ${deleteBtn}`;
                        },
                        cellClass: 'acciones-col',
                        minWidth: 190,
                        filter: false
                    },
                    {
                        headerName: "Población",
                        field: "poblacion",
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    },
                    {
                        headerName: "Lugar",
                        field: "lugar",
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    },
                    {
                        headerName: "Recogida/Entrega",
                        field: "recogida_entrega",
                        minWidth: 190,
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    },
                    {
                        headerName: "Transportista",
                        field: "transportista",
                        minWidth: 150,
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    },
                    {
                        headerName: "Fecha",
                        field: "fecha_ruta",
                        flex: 1,
                        filter: 'agDateColumnFilter'
                    },
                    {
                        headerName: "Estado",
                        field: "estado_ruta",
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    }
                ];

                var rowData = rutas.map(function(ruta) {
                    return {
                        id_ruta: ruta.id_ruta,
                        poblacion: poblacionesMap[ruta.poblacion] || 'Desconocido',
                        lugar: ruta.lugar,
                        recogida_entrega: ruta.recogida_entrega == 1 ? 'Recogida' : 'Entrega',
                        transportista: transportistasMap[ruta.transportista] || 'No asignado',
                        fecha_ruta: ruta.fecha_ruta,
                        estado_ruta: estadoMap[ruta.estado_ruta] || 'Desconocido'
                    };
                });

                var gridDiv = document.querySelector('#gridRutas');
                var gridOptions = {
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
                    getRowStyle: function(params) {
                        if (params.data && params.data.estado_ruta === 'Recogido') {
                            return {
                                backgroundColor: '#dff0d8',
                                color: 'black'
                            };
                        }
                        return null;
                    },
                    onGridReady: function(params) {
                        params.api.sizeColumnsToFit();
                        $('#botonesRuta').show();
                        window.gridApiRutas = params.api;
                    },
                    rowHeight: 60,
                    domLayout: 'autoHeight',
                    localeText: {
                        noRowsToShow: 'No hay registros disponibles.'
                    }
                };

                new agGrid.Grid(gridDiv, gridOptions);
                $('#clear-filters-rutas').on('click', function() {
                    if (window.gridApiRutas) {
                        window.gridApiRutas.setFilterModel(null);
                        window.gridApiRutas.onFilterChanged();
                    }
                });
            }

            function setupEventHandlers() {
                $('#formNuevaRuta').on('submit', function(event) {
                    event.preventDefault();
                    $(this).unbind('submit').submit();
                });
                $('#openAddRuta').on('click', function() {
                    var pedidoId = '<?= $pedido->id_pedido ?>';
                    var clienteId = '<?= $pedido->id_cliente ?>';

                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/mostrarFormulario') ?>/' + pedidoId + '/' + clienteId,
                        method: 'GET',
                        success: function(response) {
                            $('#addRutaForm').html(response);
                            $('#addRutaForm').show();
                            $('#gridRutas, #botonesRuta').hide();
                            $('#rutasModalLabel').text('Añadir Ruta');
                        },
                        error: function() {
                            alert('Error al cargar el formulario de ruta.');
                        }
                    });
                });

                window.editarRuta = function(id_ruta) {
                    var pedidoId = '<?= $pedido->id_pedido ?>';
                    var clienteId = '<?= $pedido->id_cliente ?>';

                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/mostrarFormulario') ?>/' + pedidoId + '/' + clienteId,
                        method: 'GET',
                        success: function(response) {
                            $('#addRutaForm').html(response);
                            $('#gridRutas, #botonesRuta').hide();
                            $('#addRutaForm').show();
                            $('#rutasModalLabel').text('Editar Ruta');

                            $.ajax({
                                url: '<?= base_url('Ruta_pedido/obtenerRuta') ?>/' + id_ruta,
                                method: 'GET',
                                success: function(rutaResponse) {
                                    $('#poblacion').val(rutaResponse.poblacion);
                                    $('#lugar').val(rutaResponse.lugar);
                                    $('#recogida_entrega').val(rutaResponse.recogida_entrega);
                                    $('#transportista').val(rutaResponse.transportista);
                                    $('#fecha_ruta').val(rutaResponse.fecha_ruta);
                                    $('#observaciones').val(rutaResponse.observaciones);
                                    $('#id_ruta').val(rutaResponse.id_ruta);
                                    $('#estadoRutaDiv').show();
                                    $('#estado_ruta').val(rutaResponse.estado_ruta);
                                },
                                error: function() {
                                    alert('Error al cargar los datos de la ruta.');
                                }
                            });
                        },
                        error: function() {
                            alert('Error al cargar el formulario de ruta.');
                        }
                    });
                };
                $('#volverTabla').on('click', function() {
                    $('#addRutaForm').hide();
                    $('#gridRutas').show();
                    $('#rutasModalLabel').text('Rutas del Pedido');
                });
            }

            window.eliminarRuta = function(id_ruta) {
                if (confirm('¿Estás seguro de que deseas eliminar esta ruta?')) {
                    $.ajax({
                        url: '<?= base_url('Ruta_pedido/delete') ?>/' + id_ruta,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message);
                                cargarRutasModal();
                            } else {
                                cargarRutasModal();
                            }
                        },
                        error: function(xhr) {
                            alert('Error al eliminar la ruta: ' + xhr.responseText);
                        }
                    });
                }
            };

            function cargarRutasModal() {
                var pedidoId = '<?= $pedido->id_pedido ?>';
                var clienteId = '<?= $pedido->id_cliente ?>';

                $.ajax({
                    url: '<?= base_url('Ruta_pedido/rutas') ?>/' + pedidoId + '/' + clienteId,
                    method: 'GET',
                    success: function(response) {
                        if (response.error) {
                            $('#modalContent').html('<div class="alert alert-danger">' + response.error + '</div>');
                            return;
                        }
                        $('#modalContent').html(`
                <div id="rutasContainer">
                    <div id="botonesRuta"  class="d-flex justify-content-between align-items-center botoneseditRuta botonesRuta">
                        <button type="button" class="btn btnAddRuta" id="openAddRuta" style="flex-grow: 0;">+ Añadir Ruta</button>
                        <button id="clear-filters-rutas" class="btn btnEliminarfiltrosRuta" style="flex-grow: 0;">Eliminar Filtros</button>
                    </div>
                    <br>
                    <div id="gridRutas" class="ag-theme-alpine"  style="width: 100%;"></div>
                </div>
                <div id="addRutaForm" style="display:none;"></div>
            `);

                        initializeAgGrid(response.rutas, response.poblacionesMap, response.transportistas);
                        setupEventHandlers();
                    },
                    error: function() {
                        $('#modalContent').html('<div class="alert alert-danger">Error al cargar las rutas.</div>');
                    }
                });
            }

        });
        $(document).ready(function() {
            function abrirModalSiEsNecesario() {
                const urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('openModal')) {
                    $('#openModal').click();
                    urlParams.delete('openModal');
                    const newUrl = window.location.pathname + '?' + urlParams.toString();
                    window.history.replaceState({}, '', newUrl);
                }
            }
            abrirModalSiEsNecesario();

            function initializeAgGrid(rutas, poblacionesMap, transportistasMap) {
                var estadoMap = {
                    1: 'No preparado',
                    2: 'Recogido',
                    0: 'Pendiente'
                };

                var columnDefs = [{
                        headerName: "Acciones",
                        field: "acciones",
                        cellRenderer: function(params) {
                            var editBtn = `<button class="btn btnEditarRuta" data-id="${params.data.id_ruta}" onclick="editarRuta(${params.data.id_ruta})">
                    <span class="material-symbols-outlined icono">edit</span>Editar</button>`;
                            var deleteBtn = `<button class="btn btnEliminarRuta" data-id="${params.data.id_ruta}" onclick="eliminarRuta(${params.data.id_ruta})">
                    <span class="material-symbols-outlined icono">delete</span>Eliminar</button>`;
                            return `${editBtn} ${deleteBtn}`;
                        },
                        cellClass: 'acciones-col',
                        minWidth: 190,
                        filter: false
                    },
                    {
                        headerName: "Población",
                        field: "poblacion",
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    },
                    {
                        headerName: "Lugar",
                        field: "lugar",
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    },
                    {
                        headerName: "Recogida/Entrega",
                        field: "recogida_entrega",
                        minWidth: 190,
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    },
                    {
                        headerName: "Transportista",
                        field: "transportista",
                        minWidth: 150,
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    },
                    {
                        headerName: "Fecha",
                        field: "fecha_ruta",
                        flex: 1,
                        filter: 'agDateColumnFilter'
                    },
                    {
                        headerName: "Estado",
                        field: "estado_ruta",
                        flex: 1,
                        filter: 'agTextColumnFilter'
                    }
                ];

                var rowData = rutas.map(function(ruta) {
                    return {
                        id_ruta: ruta.id_ruta,
                        poblacion: poblacionesMap[ruta.poblacion] || 'Desconocido',
                        lugar: ruta.lugar,
                        recogida_entrega: ruta.recogida_entrega == 1 ? 'Recogida' : 'Entrega',
                        transportista: transportistasMap[ruta.transportista] || 'No asignado',
                        fecha_ruta: ruta.fecha_ruta,
                        estado_ruta: estadoMap[ruta.estado_ruta] || 'Desconocido'
                    };
                });

                var gridDiv = document.querySelector('#gridRutas');
                var gridOptions = {
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
                    onGridReady: function(params) {
                        params.api.sizeColumnsToFit();
                        $('#botonesRuta').show();
                    },
                    rowHeight: 60,
                    domLayout: 'normal',
                    onGridReady: function(params) {
                        params.api.sizeColumnsToFit();
                        window.gridApi = params.api;
                    },
                    localeText: {
                        noRowsToShow: 'No hay registros disponibles.'
                    }
                };

                var gridDiv = document.querySelector('#gridRutas');
                new agGrid.Grid(gridDiv, gridOptions);
                $('#clear-filters-rutas').on('click', function() {
                    if (window.gridApiRutas) {
                        window.gridApiRutas.setFilterModel(null);
                        window.gridApiRutas.onFilterChanged();
                    }
                });
            }

        });
        $(document).on('click', '.btnEditar', function() {
            var lineaId = $(this).data('id');

            $.ajax({
                url: '<?= base_url("pedidos/mostrarFormularioEditarLineaPedido") ?>/' + lineaId,
                method: 'GET',
                success: function(response) {
                    $('#modalBodyEditarLineaPedido').html(response);
                    $('#editarLineaModal').modal('show');
                },
                error: function() {
                    alert('Hubo un error al cargar el formulario. Por favor, intenta de nuevo.');
                }
            });
        });
        $(document).ready(function() {
            $('#openAddLineaPedidoModal').click(function() {
                var idPedido = $(this).data('id-pedido');
                $.ajax({
                    url: '<?= base_url("pedidos/mostrarFormularioAddLineaPedido") ?>/' + idPedido,
                    method: 'GET',
                    success: function(response) {
                        $('#modalBodyAddLineaPedido').html(response);
                        $('#addLineaPedidoModal').modal('show');
                    },
                    error: function() {
                        alert('Hubo un error al cargar el formulario. Por favor, intenta de nuevo.');
                    }
                });
            });
            $(document).on('submit', '#addLineaPedidoForm', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('No se pudo guardar la línea de pedido.');
                    }
                });
            });
        });
    </script>
    <?= $this->endSection() ?>