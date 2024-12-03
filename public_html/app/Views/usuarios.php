<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">
<br>
<h2>Usuarios</h2>
<div class="mb-3">

    <div class="botonSeparados">
        <button class="btn boton btnAdd" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Añadir Usuario
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                <path
                    d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z"
                    fill="white" />
            </svg>
        </button>

        <button id="clear-filters" class=" boton btnEliminarfiltros">
            Quitar Filtros
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                <path
                    d="M7.54974 8.04975C7.62521 7.97408 7.71487 7.91405 7.81358 7.87309C7.91229 7.83213 8.01812 7.81105 8.12499 7.81105C8.23186 7.81105 8.33768 7.83213 8.43639 7.87309C8.5351 7.91405 8.62476 7.97408 8.70024 8.04975L13 12.3511L17.2997 8.04975C17.3753 7.97421 17.465 7.91428 17.5637 7.8734C17.6624 7.83251 17.7682 7.81147 17.875 7.81147C17.9818 7.81147 18.0876 7.83251 18.1863 7.8734C18.285 7.91428 18.3747 7.97421 18.4502 8.04975C18.5258 8.12529 18.5857 8.21497 18.6266 8.31368C18.6675 8.41238 18.6885 8.51816 18.6885 8.625C18.6885 8.73183 18.6675 8.83762 18.6266 8.93632C18.5857 9.03502 18.5258 9.12471 18.4502 9.20025L14.1489 13.5L18.4502 17.7997C18.5258 17.8753 18.5857 17.965 18.6266 18.0637C18.6675 18.1624 18.6885 18.2682 18.6885 18.375C18.6885 18.4818 18.6675 18.5876 18.6266 18.6863C18.5857 18.785 18.5258 18.8747 18.4502 18.9502C18.3747 19.0258 18.285 19.0857 18.1863 19.1266C18.0876 19.1675 17.9818 19.1885 17.875 19.1885C17.7682 19.1885 17.6624 19.1675 17.5637 19.1266C17.465 19.0857 17.3753 19.0258 17.2997 18.9502L13 14.6489L8.70024 18.9502C8.62469 19.0258 8.53501 19.0857 8.43631 19.1266C8.33761 19.1675 8.23182 19.1885 8.12499 19.1885C8.01815 19.1885 7.91237 19.1675 7.81366 19.1266C7.71496 19.0857 7.62528 19.0258 7.54974 18.9502C7.47419 18.8747 7.41427 18.785 7.37339 18.6863C7.3325 18.5876 7.31146 18.4818 7.31146 18.375C7.31146 18.2682 7.3325 18.1624 7.37339 18.0637C7.41427 17.965 7.47419 17.8753 7.54974 17.7997L11.8511 13.5L7.54974 9.20025C7.47407 9.12477 7.41404 9.03511 7.37308 8.9364C7.33212 8.83769 7.31104 8.73187 7.31104 8.625C7.31104 8.51813 7.33212 8.4123 7.37308 8.31359C7.41404 8.21488 7.47407 8.12522 7.54974 8.04975Z"
                    fill="white" />
            </svg>
        </button>
    </div>
    <br>

    <!-- Tabla -->
    <div id="gridUsuarios" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

    <!-- Modal para añadir usuario -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Añadir Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="form-group">
                            <label for="nombre_usuario">Nombre:</label>
                            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="apellidos_usuario">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos_usuario" name="apellidos_usuario"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="dni">DNI</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="form-group">
                            <label for="seguridad_social">Nº Seguridad Social</label>
                            <input type="text" class="form-control" id="seguridad_social" name="seguridad_social"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                    </form>
                </div>
                <div class="modal-footer buttonsEditProductProveedAbajo">
                    <button type="button" class="btn boton volverButton" data-bs-dismiss="modal">Volver
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1096 14.2269 18.903 14.3125 18.6875 14.3125H9.27386L12.7627 17.7998C12.8383 17.8753 12.8982 17.965 12.9391 18.0637C12.98 18.1624 13.001 18.2682 13.001 18.375C13.001 18.4818 12.98 18.5876 12.9391 18.6863C12.8982 18.785 12.8383 18.8747 12.7627 18.9503C12.6872 19.0258 12.5975 19.0857 12.4988 19.1266C12.4001 19.1675 12.2943 19.1885 12.1875 19.1885C12.0807 19.1885 11.9749 19.1675 11.8762 19.1266C11.7775 19.0857 11.6878 19.0258 11.6122 18.9503L6.73724 14.0753C6.66157 13.9998 6.60154 13.9101 6.56058 13.8114C6.51962 13.7127 6.49854 13.6069 6.49854 13.5C6.49854 13.3931 6.51962 13.2873 6.56058 13.1886C6.60154 13.0899 6.66157 13.0002 6.73724 12.9248L11.6122 8.04975C11.7648 7.89719 11.9717 7.81148 12.1875 7.81148C12.4032 7.81148 12.6102 7.89719 12.7627 8.04975C12.9153 8.20232 13.001 8.40924 13.001 8.625C13.001 8.84076 12.9153 9.04769 12.7627 9.20025L9.27386 12.6875H18.6875C18.903 12.6875 19.1096 12.7731 19.262 12.9255C19.4144 13.0779 19.5 13.2845 19.5 13.5Z"
                                fill="white" />
                        </svg>
                    </button>
                    <button type="button" class="btn boton btnGuardar" id="saveUserBtn">Guardar Usuario
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                            <path
                                d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                                fill="white" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="gridUsuarios" class="ag-theme-alpine" style="height: 600px; width: 100%;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let gridApi; // Guardar la referencia de la API de ag-Grid

            const columnDefs = [
                {
                    headerName: "Acciones",
                    cellRenderer: params => `
                <a href="/usuarios/editar/${params.data.id}" class="btn botonTabla btnEditarTabla">Editar
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                    <path d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z" fill="white"/>
                    </svg></a>
                <button onclick="eliminarUsuario(${params.data.id})" class="btn botonTabla btnEliminarTabla">Eliminar
                  <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path d="M7.66753 6.776C7.41733 6.776 7.17737 6.87593 7.00045 7.05379C6.82353 7.23166 6.72414 7.47289 6.72414 7.72443V8.67286C6.72414 8.9244 6.82353 9.16563 7.00045 9.3435C7.17737 9.52136 7.41733 9.62129 7.66753 9.62129H8.13923V18.1571C8.13923 18.6602 8.33802 19.1427 8.69186 19.4984C9.0457 19.8541 9.52561 20.054 10.026 20.054H15.6864C16.1868 20.054 16.6667 19.8541 17.0206 19.4984C17.3744 19.1427 17.5732 18.6602 17.5732 18.1571V9.62129H18.0449C18.2951 9.62129 18.5351 9.52136 18.712 9.3435C18.8889 9.16563 18.9883 8.9244 18.9883 8.67286V7.72443C18.9883 7.47289 18.8889 7.23166 18.712 7.05379C18.5351 6.87593 18.2951 6.776 18.0449 6.776H14.743C14.743 6.52446 14.6436 6.28323 14.4667 6.10536C14.2898 5.9275 14.0498 5.82758 13.7996 5.82758H11.9128C11.6626 5.82758 11.4227 5.9275 11.2457 6.10536C11.0688 6.28323 10.9694 6.52446 10.9694 6.776H7.66753ZM10.4977 10.5697C10.6228 10.5697 10.7428 10.6197 10.8313 10.7086C10.9197 10.7975 10.9694 10.9182 10.9694 11.0439V17.6829C10.9694 17.8087 10.9197 17.9293 10.8313 18.0182C10.7428 18.1072 10.6228 18.1571 10.4977 18.1571C10.3726 18.1571 10.2526 18.1072 10.1642 18.0182C10.0757 17.9293 10.026 17.8087 10.026 17.6829V11.0439C10.026 10.9182 10.0757 10.7975 10.1642 10.7086C10.2526 10.6197 10.3726 10.5697 10.4977 10.5697ZM12.8562 10.5697C12.9813 10.5697 13.1013 10.6197 13.1898 10.7086C13.2782 10.7975 13.3279 10.9182 13.3279 11.0439V17.6829C13.3279 17.8087 13.2782 17.9293 13.1898 18.0182C13.1013 18.1072 12.9813 18.1571 12.8562 18.1571C12.7311 18.1571 12.6111 18.1072 12.5227 18.0182C12.4342 17.9293 12.3845 17.8087 12.3845 17.6829V11.0439C12.3845 10.9182 12.4342 10.7975 12.5227 10.7086C12.6111 10.6197 12.7311 10.5697 12.8562 10.5697ZM15.6864 11.0439V17.6829C15.6864 17.8087 15.6367 17.9293 15.5482 18.0182C15.4598 18.1072 15.3398 18.1571 15.2147 18.1571C15.0896 18.1571 14.9696 18.1072 14.8812 18.0182C14.7927 17.9293 14.743 17.8087 14.743 17.6829V11.0439C14.743 10.9182 14.7927 10.7975 14.8812 10.7086C14.9696 10.6197 15.0896 10.5697 15.2147 10.5697C15.3398 10.5697 15.4598 10.6197 15.5482 10.7086C15.6367 10.7975 15.6864 10.9182 15.6864 11.0439Z" fill="white"/>
                    </svg></button>
            `
                },
                { headerName: "Nombre", field: "nombre_usuario", sortable: true, filter: true },
                { headerName: "Apellidos", field: "apellidos_usuario", sortable: true, filter: true },
                { headerName: "DNI", field: "dni", sortable: true, filter: true },
                { headerName: "Seguridad Social", field: "seguridad_social", sortable: true, filter: true },
                { headerName: "Email", field: "email", sortable: true, filter: true },
                { headerName: "Teléfono", field: "telefono", sortable: true, filter: true },
                {
                    headerName: "Foto",
                    field: "userfoto",
                    cellRenderer: params => {
                        if (params.value) {
                            // Asegúrate de que el id_empresa esté disponible en los datos
                            const idEmpresa = params.data.id_empresa || ''; // Si no existe id_empresa, usará un string vacío
                            return `<img src="/public/assets/uploads/files/${idEmpresa}/usuarios/${params.value}" height="60">`;
                        }
                        return " ";
                    },
                    sortable: true,
                    filter: true
                },
            ];
            const gridOptions = {
                columnDefs: columnDefs,
                defaultColDef: {
                    flex: 1,
                    minWidth: 100,
                    sortable: true,
                    floatingFilter: true,
                    resizable: true
                },
                rowData: [],
                pagination: true,
                paginationPageSize: 10,
                domLayout: 'autoHeight',
                rowHeight: 60,
                onGridReady: params => {
                    gridApi = params.api;
                    fetchUsuarios(params.api);
                }
            };

            new agGrid.Grid(document.querySelector('#gridUsuarios'), gridOptions);

            document.querySelector('#clear-filters').addEventListener('click', function () {
                if (gridApi) {
                    gridApi.setFilterModel(null); // Elimina todos los filtros
                }
            });
        });

        function fetchUsuarios(gridApi) {
            fetch('/usuarios/getUsuarios')
                .then(response => response.json())
                .then(data => {
                    gridApi.applyTransaction({ add: data });
                })
                .catch(error => console.error('Error al cargar usuarios:', error));
        }

        // Guardar nuevo usuario
        document.querySelector('#saveUserBtn').addEventListener('click', function () {
            const formData = new FormData(document.querySelector('#addUserForm'));
            fetch('/usuarios/crearUsuario', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('#addUserModal').querySelector('.btn-close').click();
                        window.location.reload(); // Recargar la página
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch(error => console.error('Error al añadir usuario:', error));
        });

        function eliminarUsuario(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        fetch(`/usuarios/eliminar/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                console.error('Error al eliminar el usuario:', data.message);
            }
        })
        .catch(error => console.error('Error al eliminar usuario:', error));
    }
}


    </script>

    <?= $this->endSection() ?>