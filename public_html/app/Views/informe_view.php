<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>
<br>
<!-- ag-Grid CSS -->
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-grid.css">
<link rel="stylesheet" href="https://unpkg.com/ag-grid-community/styles/ag-theme-alpine.css">

<!-- ag-Grid JS -->
<script src="https://unpkg.com/ag-grid-community/dist/ag-grid-community.noStyle.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/libreria.css') ?>?v=<?= time() ?>">

<h2>Informes</h2>
<br>
<div class="d-flex justify-content-between mb-3">
    <button class="boton btnAdd" onclick="abrirModalAgregarInforme()">Añadir Informe
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
            <path
                d="M13 7C13.2155 7 13.4222 7.0856 13.5745 7.23798C13.7269 7.39035 13.8125 7.59701 13.8125 7.8125V12.6875H18.6875C18.903 12.6875 19.1097 12.7731 19.262 12.9255C19.4144 13.0778 19.5 13.2845 19.5 13.5C19.5 13.7155 19.4144 13.9222 19.262 14.0745C19.1097 14.2269 18.903 14.3125 18.6875 14.3125H13.8125V19.1875C13.8125 19.403 13.7269 19.6097 13.5745 19.762C13.4222 19.9144 13.2155 20 13 20C12.7845 20 12.5778 19.9144 12.4255 19.762C12.2731 19.6097 12.1875 19.403 12.1875 19.1875V14.3125H7.3125C7.09701 14.3125 6.89035 14.2269 6.73798 14.0745C6.5856 13.9222 6.5 13.7155 6.5 13.5C6.5 13.2845 6.5856 13.0778 6.73798 12.9255C6.89035 12.7731 7.09701 12.6875 7.3125 12.6875H12.1875V7.8125C12.1875 7.59701 12.2731 7.39035 12.4255 7.23798C12.5778 7.0856 12.7845 7 13 7Z"
                fill="white" />
        </svg>
    </button>
    <button id="clear-filters" class="boton btnEliminarfiltros">Quitar Filtros
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
            <path
                d="M7.54974 8.04975C7.62521 7.97408 7.71487 7.91405 7.81358 7.87309C7.91229 7.83213 8.01812 7.81105 8.12499 7.81105C8.23186 7.81105 8.33768 7.83213 8.43639 7.87309C8.5351 7.91405 8.62476 7.97408 8.70024 8.04975L13 12.3511L17.2997 8.04975C17.3753 7.97421 17.465 7.91428 17.5637 7.8734C17.6624 7.83251 17.7682 7.81147 17.875 7.81147C17.9818 7.81147 18.0876 7.83251 18.1863 7.8734C18.285 7.91428 18.3747 7.97421 18.4502 8.04975C18.5258 8.12529 18.5857 8.21497 18.6266 8.31368C18.6675 8.41238 18.6885 8.51816 18.6885 8.625C18.6885 8.73183 18.6675 8.83762 18.6266 8.93632C18.5857 9.03502 18.5258 9.12471 18.4502 9.20025L14.1489 13.5L18.4502 17.7997C18.5258 17.8753 18.5857 17.965 18.6266 18.0637C18.6675 18.1624 18.6885 18.2682 18.6885 18.375C18.6885 18.4818 18.6675 18.5876 18.6266 18.6863C18.5857 18.785 18.5258 18.8747 18.4502 18.9502C18.3747 19.0258 18.285 19.0857 18.1863 19.1266C18.0876 19.1675 17.9818 19.1885 17.875 19.1885C17.7682 19.1885 17.6624 19.1675 17.5637 19.1266C17.465 19.0857 17.3753 19.0258 17.2997 18.9502L13 14.6489L8.70024 18.9502C8.62469 19.0258 8.53501 19.0857 8.43631 19.1266C8.33761 19.1675 8.23182 19.1885 8.12499 19.1885C8.01815 19.1885 7.91237 19.1675 7.81366 19.1266C7.71496 19.0857 7.62528 19.0258 7.54974 18.9502C7.47419 18.8747 7.41427 18.785 7.37339 18.6863C7.3325 18.5876 7.31146 18.4818 7.31146 18.375C7.31146 18.2682 7.3325 18.1624 7.37339 18.0637C7.41427 17.965 7.47419 17.8753 7.54974 17.7997L11.8511 13.5L7.54974 9.20025C7.47407 9.12477 7.41404 9.03511 7.37308 8.9364C7.33212 8.83769 7.31104 8.73187 7.31104 8.625C7.31104 8.51813 7.33212 8.4123 7.37308 8.31359C7.41404 8.21488 7.47407 8.12522 7.54974 8.04975Z"
                fill="white" />
        </svg>
    </button>
</div>

<div id="informesGrid" class="ag-theme-alpine" style="height: 400px; width: 100%;"></div>

<!-- Modal único para agregar o editar informe -->
<div class="modal fade" id="addInformeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addInformeModalLabel">Añadir Informe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addInformeForm">
                    <input type="hidden" id="id_informe" name="id_informe">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="desde" class="form-label">Desde</label>
                        <input type="date" class="form-control" id="desde" name="desde" required>
                    </div>
                    <div class="mb-3">
                        <label for="hasta" class="form-label">Hasta</label>
                        <input type="date" class="form-control" id="hasta" name="hasta" required>
                    </div>
                    <br>
                    <div class="mb-3">
                    <input type="checkbox" id="ausencias" name="ausencias" value="1">
                    <label for="ausencias" class="form-label" style="margin-left: 8px;"> Ausencias</label>
                    </div>
                    <div class="mb-3">
                    <input type="checkbox" id="vacaciones" name="vacaciones" value="1">
                    <label for="vacaciones" class="form-label"  style="margin-left: 8px;">Vacaciones</label>
                    </div>
                    <div class="mb-3">
                    <input type="checkbox" id="extras" name="extras" value="1">
                    <label for="extras" class="form-label"  style="margin-left: 8px;">Extras</label>
                    </div>
                    <div class="mb-3">
                    <input type="checkbox" id="incidencias" name="incidencias" value="1">
                    <label for="incidencias" class="form-label"  style="margin-left: 8px;">Incidencias</label>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="boton btnGuardar" onclick="guardarInforme()">Guardar
                            Informe <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27"
                                fill="none">
                                <path
                                    d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                                    fill="white" />
                            </svg></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const columnDefs = [
            {
                headerName: "Acciones",
                field: "acciones",
                cellRenderer: params => `
                 <button onclick="editarInforme(${params.data.id_informe})" class="btn botonTabla btnEditarTabla">Editar
                   <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 15 16" fill="none">
                    <path d="M14.7513 1.98301C14.8352 2.07186 14.8823 2.19218 14.8823 2.31763C14.8823 2.44307 14.8352 2.5634 14.7513 2.65224L13.8145 3.64186L12.0182 1.74604L12.955 0.756413C13.0392 0.66756 13.1534 0.617645 13.2725 0.617645C13.3916 0.617645 13.5058 0.66756 13.59 0.756413L14.7513 1.98207V1.98301ZM13.1795 4.31109L11.3833 2.41526L5.26424 8.87435C5.21481 8.92651 5.1776 8.99013 5.15557 9.06014L4.43256 11.3484C4.41945 11.3901 4.41759 11.4349 4.42719 11.4776C4.43678 11.5204 4.45746 11.5595 4.48691 11.5906C4.51635 11.6217 4.55341 11.6435 4.59393 11.6536C4.63446 11.6637 4.67685 11.6618 4.71638 11.6479L6.88448 10.8849C6.95073 10.8619 7.011 10.823 7.06052 10.7711L13.1795 4.31109Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0.352905 13.6526C0.352905 14.049 0.510356 14.4291 0.790621 14.7093C1.07089 14.9896 1.45101 15.1471 1.84736 15.1471H12.8067C13.203 15.1471 13.5832 14.9896 13.8634 14.7093C14.1437 14.4291 14.3011 14.049 14.3011 13.6526V7.67479C14.3011 7.54267 14.2487 7.41596 14.1552 7.32254C14.0618 7.22912 13.9351 7.17664 13.803 7.17664C13.6709 7.17664 13.5442 7.22912 13.4507 7.32254C13.3573 7.41596 13.3048 7.54267 13.3048 7.67479V13.6526C13.3048 13.7847 13.2524 13.9114 13.1589 14.0048C13.0655 14.0983 12.9388 14.1508 12.8067 14.1508H1.84736C1.71524 14.1508 1.58853 14.0983 1.49511 14.0048C1.40169 13.9114 1.34921 13.7847 1.34921 13.6526V2.69328C1.34921 2.56116 1.40169 2.43445 1.49511 2.34103C1.58853 2.24761 1.71524 2.19512 1.84736 2.19512H8.32333C8.45544 2.19512 8.58215 2.14264 8.67557 2.04922C8.76899 1.9558 8.82148 1.82909 8.82148 1.69697C8.82148 1.56486 8.76899 1.43815 8.67557 1.34473C8.58215 1.25131 8.45544 1.19882 8.32333 1.19882H1.84736C1.45101 1.19882 1.07089 1.35627 0.790621 1.63654C0.510356 1.9168 0.352905 2.29692 0.352905 2.69328V13.6526Z" fill="white"/>
                    </svg></button>
                    <button onclick="abrirInforme(${params.data.id_informe})" class="btn botonTabla btnImprimirTabla">Infome
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="15" viewBox="0 0 12 15" fill="none">
                    <path d="M3.22626 4.25C3.11108 4.25 3.00063 4.29575 2.91919 4.37719C2.83775 4.45863 2.79199 4.56909 2.79199 4.68427C2.79199 4.79944 2.83775 4.9099 2.91919 4.99134C3.00063 5.07278 3.11108 5.11853 3.22626 5.11853H8.43747C8.55264 5.11853 8.6631 5.07278 8.74454 4.99134C8.82598 4.9099 8.87173 4.79944 8.87173 4.68427C8.87173 4.56909 8.82598 4.45863 8.74454 4.37719C8.6631 4.29575 8.55264 4.25 8.43747 4.25H3.22626ZM2.79199 6.42134C2.79199 6.30616 2.83775 6.1957 2.91919 6.11426C3.00063 6.03282 3.11108 5.98707 3.22626 5.98707H8.43747C8.55264 5.98707 8.6631 6.03282 8.74454 6.11426C8.82598 6.1957 8.87173 6.30616 8.87173 6.42134C8.87173 6.53651 8.82598 6.64697 8.74454 6.72841C8.6631 6.80985 8.55264 6.8556 8.43747 6.8556H3.22626C3.11108 6.8556 3.00063 6.80985 2.91919 6.72841C2.83775 6.64697 2.79199 6.53651 2.79199 6.42134ZM3.22626 7.72414C3.11108 7.72414 3.00063 7.76989 2.91919 7.85133C2.83775 7.93277 2.79199 8.04323 2.79199 8.15841C2.79199 8.27358 2.83775 8.38404 2.91919 8.46548C3.00063 8.54692 3.11108 8.59267 3.22626 8.59267H8.43747C8.55264 8.59267 8.6631 8.54692 8.74454 8.46548C8.82598 8.38404 8.87173 8.27358 8.87173 8.15841C8.87173 8.04323 8.82598 7.93277 8.74454 7.85133C8.6631 7.76989 8.55264 7.72414 8.43747 7.72414H3.22626ZM3.22626 9.46121C3.11108 9.46121 3.00063 9.50696 2.91919 9.5884C2.83775 9.66984 2.79199 9.7803 2.79199 9.89547C2.79199 10.0106 2.83775 10.1211 2.91919 10.2025C3.00063 10.284 3.11108 10.3297 3.22626 10.3297H5.83186C5.94704 10.3297 6.0575 10.284 6.13894 10.2025C6.22038 10.1211 6.26613 10.0106 6.26613 9.89547C6.26613 9.7803 6.22038 9.66984 6.13894 9.5884C6.0575 9.50696 5.94704 9.46121 5.83186 9.46121H3.22626Z" fill="black"/>
                    <path d="M0.620667 2.51292C0.620667 2.05222 0.803679 1.61039 1.12944 1.28462C1.45521 0.958861 1.89704 0.775848 2.35774 0.775848L9.30601 0.775848C9.76671 0.775848 10.2085 0.958861 10.5343 1.28462C10.8601 1.61039 11.0431 2.05222 11.0431 2.51292V12.9353C11.0431 13.396 10.8601 13.8379 10.5343 14.1636C10.2085 14.4894 9.76671 14.6724 9.30601 14.6724H2.35774C1.89704 14.6724 1.45521 14.4894 1.12944 14.1636C0.803679 13.8379 0.620667 13.396 0.620667 12.9353V2.51292ZM9.30601 1.64438H2.35774C2.12739 1.64438 1.90647 1.73589 1.74359 1.89877C1.58071 2.06165 1.4892 2.28257 1.4892 2.51292V12.9353C1.4892 13.1657 1.58071 13.3866 1.74359 13.5495C1.90647 13.7124 2.12739 13.8039 2.35774 13.8039H9.30601C9.53636 13.8039 9.75728 13.7124 9.92016 13.5495C10.083 13.3866 10.1745 13.1657 10.1745 12.9353V2.51292C10.1745 2.28257 10.083 2.06165 9.92016 1.89877C9.75728 1.73589 9.53636 1.64438 9.30601 1.64438Z" fill="black"/>
                    </svg></button>
                    <button onclick="eliminarInforme(${params.data.id_informe})" class="btn botonTabla btnEliminarTabla">Eliminar
                     <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path d="M7.66753 6.776C7.41733 6.776 7.17737 6.87593 7.00045 7.05379C6.82353 7.23166 6.72414 7.47289 6.72414 7.72443V8.67286C6.72414 8.9244 6.82353 9.16563 7.00045 9.3435C7.17737 9.52136 7.41733 9.62129 7.66753 9.62129H8.13923V18.1571C8.13923 18.6602 8.33802 19.1427 8.69186 19.4984C9.0457 19.8541 9.52561 20.054 10.026 20.054H15.6864C16.1868 20.054 16.6667 19.8541 17.0206 19.4984C17.3744 19.1427 17.5732 18.6602 17.5732 18.1571V9.62129H18.0449C18.2951 9.62129 18.5351 9.52136 18.712 9.3435C18.8889 9.16563 18.9883 8.9244 18.9883 8.67286V7.72443C18.9883 7.47289 18.8889 7.23166 18.712 7.05379C18.5351 6.87593 18.2951 6.776 18.0449 6.776H14.743C14.743 6.52446 14.6436 6.28323 14.4667 6.10536C14.2898 5.9275 14.0498 5.82758 13.7996 5.82758H11.9128C11.6626 5.82758 11.4227 5.9275 11.2457 6.10536C11.0688 6.28323 10.9694 6.52446 10.9694 6.776H7.66753ZM10.4977 10.5697C10.6228 10.5697 10.7428 10.6197 10.8313 10.7086C10.9197 10.7975 10.9694 10.9182 10.9694 11.0439V17.6829C10.9694 17.8087 10.9197 17.9293 10.8313 18.0182C10.7428 18.1072 10.6228 18.1571 10.4977 18.1571C10.3726 18.1571 10.2526 18.1072 10.1642 18.0182C10.0757 17.9293 10.026 17.8087 10.026 17.6829V11.0439C10.026 10.9182 10.0757 10.7975 10.1642 10.7086C10.2526 10.6197 10.3726 10.5697 10.4977 10.5697ZM12.8562 10.5697C12.9813 10.5697 13.1013 10.6197 13.1898 10.7086C13.2782 10.7975 13.3279 10.9182 13.3279 11.0439V17.6829C13.3279 17.8087 13.2782 17.9293 13.1898 18.0182C13.1013 18.1072 12.9813 18.1571 12.8562 18.1571C12.7311 18.1571 12.6111 18.1072 12.5227 18.0182C12.4342 17.9293 12.3845 17.8087 12.3845 17.6829V11.0439C12.3845 10.9182 12.4342 10.7975 12.5227 10.7086C12.6111 10.6197 12.7311 10.5697 12.8562 10.5697ZM15.6864 11.0439V17.6829C15.6864 17.8087 15.6367 17.9293 15.5482 18.0182C15.4598 18.1072 15.3398 18.1571 15.2147 18.1571C15.0896 18.1571 14.9696 18.1072 14.8812 18.0182C14.7927 17.9293 14.743 17.8087 14.743 17.6829V11.0439C14.743 10.9182 14.7927 10.7975 14.8812 10.7086C14.9696 10.6197 15.0896 10.5697 15.2147 10.5697C15.3398 10.5697 15.4598 10.6197 15.5482 10.7086C15.6367 10.7975 15.6864 10.9182 15.6864 11.0439Z" fill="white"/>
                    </svg></button>
                `,
                filter: false,
                minWidth: 300
            },
            { headerName: "Título", field: "titulo", filter: 'agTextColumnFilter', minWidth: 150 },
            {
                headerName: "Desde",
                field: "desde",
                filter: 'agTextColumnFilter',
                minWidth: 120,
                valueFormatter: params => formatDate(params.value),
                valueGetter: params => params.data.desde ? new Date(params.data.desde) : null,
            },
            {
                headerName: "Hasta",
                field: "hasta",
                filter: 'agTextColumnFilter',
                minWidth: 120,
                valueFormatter: params => formatDate(params.value),
                valueGetter: params => params.data.hasta ? new Date(params.data.hasta) : null,
            }
        ];

        const gridOptions = {
            columnDefs: columnDefs,
            defaultColDef: {
                flex: 1,
                sortable: true,
                filter: true,
                floatingFilter: true,
                resizable: true
            },
            rowData: [],
            pagination: true,
            paginationPageSize: 10,
            domLayout: 'autoHeight',
            rowHeight: 60,
            localeText: {
                noRowsToShow: 'No hay Informes disponibles.'
            },
            onGridReady: function (params) {
                fetchInformesData(params.api);
            }
        };

        const gridDiv = document.querySelector('#informesGrid');
        new agGrid.Grid(gridDiv, gridOptions);

        document.getElementById('clear-filters').addEventListener('click', () => {
            gridOptions.api.setFilterModel(null);
            gridOptions.api.onFilterChanged();
        });
    });

    function fetchInformesData(gridApi) {
        fetch('<?= base_url("informes/getInformes") ?>')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    gridApi.applyTransaction({ add: data });
                } else {
                    console.error('Los datos recibidos no son un array:', data);
                }
            })
            .catch(error => console.error('Error al cargar los datos:', error));
    }

    function abrirModalAgregarInforme() {
        $('#addInformeModalLabel').text('Añadir Informe');
        $('#id_informe').val('');
        $('#addInformeForm')[0].reset();
        $('#addInformeModal').modal('show');
    }
    function guardarInforme() {
        const formData = $('#addInformeForm').serializeArray();

        // Convertir los checkboxes en un formato adecuado
        const formDataWithCheckboxes = {};
        formData.forEach(field => {
            formDataWithCheckboxes[field.name] = field.value === "on" ? 1 : field.value;
        });

        const idInforme = $('#id_informe').val();
        const url = idInforme ? `<?= base_url("informes/actualizarInforme") ?>/${idInforme}` : '<?= base_url("informes/agregarInforme") ?>';

        $.post(url, formDataWithCheckboxes)
            .done(response => {
                if (response.success) {
                    $('#addInformeModal').modal('hide');
                    location.reload();
                } else {
                    alert(response.message);
                }
            });
    }

    function editarInforme(id) {
        $.get(`<?= base_url("informes/getInforme") ?>/${id}`, data => {
            $('#addInformeModalLabel').text('Editar Informe');
            $('#id_informe').val(data.id_informe);
            $('#titulo').val(data.titulo);
            $('#desde').val(data.desde);
            $('#hasta').val(data.hasta);

            // Actualizar los checkboxes según los valores de la tabla
            $('#ausencias').prop('checked', data.ausencias == 1);
            $('#vacaciones').prop('checked', data.vacaciones == 1);
            $('#extras').prop('checked', data.extras == 1);
            $('#incidencias').prop('checked', data.incidencias == 1);

            $('#addInformeModal').modal('show');
        });
    }

    function eliminarInforme(id) {
        if (confirm('¿Deseas eliminar este informe?')) {
            $.post(`<?= base_url("informes/eliminarInforme") ?>/${id}`)
                .done(response => {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                });
        }
    }

    function abrirInforme(id) {
        window.location.href = `<?= base_url("informe_detalle") ?>/${id}`;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear().toString().slice(-2);
        return `${day}/${month}/${year}`;
    }
</script>

<?= $this->endSection() ?>