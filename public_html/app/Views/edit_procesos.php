<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?= $this->include('partials/amiga') ?>

<link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/botones.css') ?>?v=<?= time() ?>">
<br> <br>
    <div class="botonSeparados">
        <a href="<?= base_url('procesos/restriccion/' . $previous_proceso_id); ?>" id="prev-link"
            class="botonMover "><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
            </svg></a>
        <a href="<?= base_url('procesos/restriccion/' . $next_proceso_id); ?>" id="next-link" class="botonMover "> <svg
                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
            </svg></a>
    </div>
    <br>

    <form id="edit-form" action="<?= base_url('procesos/restriccion/' . $proceso_principal['id_proceso']); ?>"
        method="post">
        <input type="hidden" name="redirect_url" value="<?= current_url(); ?>">
       
        <h2 class="text-center mb-4"><?= $proceso_principal['nombre_proceso'] ?></h2>
        <div class="buttonsEditProductProveedAbajo">
            <a href="<?= base_url('procesos'); ?>" class="btn boton volverButton">Volver
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M19.5 13C19.5 13.2155 19.4144 13.4221 19.262 13.5745C19.1096 13.7269 18.903 13.8125 18.6875 13.8125H9.27386L12.7627 17.2997C12.8383 17.3753 12.8982 17.465 12.9391 17.5637C12.98 17.6624 13.001 17.7682 13.001 17.875C13.001 17.9818 12.98 18.0876 12.9391 18.1863C12.8982 18.285 12.8383 18.3747 12.7627 18.4502C12.6872 18.5258 12.5975 18.5857 12.4988 18.6266C12.4001 18.6675 12.2943 18.6885 12.1875 18.6885C12.0807 18.6885 11.9749 18.6675 11.8762 18.6266C11.7775 18.5857 11.6878 18.5258 11.6122 18.4502L6.73724 13.5752C6.66157 13.4998 6.60154 13.4101 6.56058 13.3114C6.51962 13.2127 6.49854 13.1069 6.49854 13C6.49854 12.8931 6.51962 12.7873 6.56058 12.6886C6.60154 12.5899 6.66157 12.5002 6.73724 12.4247L11.6122 7.54974C11.7648 7.39717 11.9717 7.31146 12.1875 7.31146C12.4032 7.31146 12.6102 7.39717 12.7627 7.54974C12.9153 7.7023 13.001 7.90923 13.001 8.12499C13.001 8.34075 12.9153 8.54767 12.7627 8.70024L9.27386 12.1875H18.6875C18.903 12.1875 19.1096 12.2731 19.262 12.4255C19.4144 12.5778 19.5 12.7845 19.5 13Z"
                        fill="white" />
                </svg>
            </a>
            <button type="submit" class="boton btnAdd">Guardar Cambios
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27" fill="none">
                    <path
                        d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                        fill="white" />
                </svg>
            </button>
        </div>

        <div class="form-group">
            <label for="nombre_proceso">Nombre del Proceso</label>
            <input type="text" class="form-control" id="nombre_proceso" name="nombre_proceso"
                value="<?= esc($proceso_principal['nombre_proceso']); ?>" required>
        </div>
        <div class="form-group">
            <label for="estado_proceso">Estado del Proceso</label>
            <select class="form-control" id="estado_proceso" name="estado_proceso" required>
                <option value="1" <?= $proceso_principal['estado_proceso'] == '1' ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?= $proceso_principal['estado_proceso'] == '0' ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
        <br>
        <h3 class="text-center mb-4">Restricciones <?= $proceso_principal['nombre_proceso'] ?></h3>

        <!-- Campo de búsqueda -->
        <div class="form-group">
            <label for="search-proceso"></label>
            <input type="text" class="form-control" id="search-proceso" placeholder="Busca el nombre del proceso">
        </div>

        <?php
        $restricciones_actuales = explode(',', $proceso_principal['restriccion'] ?? '');
        ?>
        <div class="row" id="proceso-container">
            <?php foreach ($procesos as $proceso): ?>
                <?php
                $is_restricted = in_array($proceso['id_proceso'], $restricciones_actuales);
                ?>
                <div class="col-md-4 mb-3 proceso-item" data-nombre="<?= strtolower($proceso['nombre_proceso']) ?>">
                    <div class="card proceso-box <?= $is_restricted ? 'selected border-primary shadow' : '' ?>"
                        data-id="<?= $proceso['id_proceso'] ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= $proceso['nombre_proceso'] ?></h5>
                        </div>
                    </div>
                    <input type="checkbox" name="restricciones[]" value="<?= $proceso['id_proceso'] ?>" class="d-none"
                        <?= $is_restricted ? 'checked' : '' ?>>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="buttonsEditProductProveedAbajo">
            <a href="<?= base_url('procesos'); ?>" class="btn boton volverButton">Volver
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 26" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M19.5 13C19.5 13.2155 19.4144 13.4221 19.262 13.5745C19.1096 13.7269 18.903 13.8125 18.6875 13.8125H9.27386L12.7627 17.2997C12.8383 17.3753 12.8982 17.465 12.9391 17.5637C12.98 17.6624 13.001 17.7682 13.001 17.875C13.001 17.9818 12.98 18.0876 12.9391 18.1863C12.8982 18.285 12.8383 18.3747 12.7627 18.4502C12.6872 18.5258 12.5975 18.5857 12.4988 18.6266C12.4001 18.6675 12.2943 18.6885 12.1875 18.6885C12.0807 18.6885 11.9749 18.6675 11.8762 18.6266C11.7775 18.5857 11.6878 18.5258 11.6122 18.4502L6.73724 13.5752C6.66157 13.4998 6.60154 13.4101 6.56058 13.3114C6.51962 13.2127 6.49854 13.1069 6.49854 13C6.49854 12.8931 6.51962 12.7873 6.56058 12.6886C6.60154 12.5899 6.66157 12.5002 6.73724 12.4247L11.6122 7.54974C11.7648 7.39717 11.9717 7.31146 12.1875 7.31146C12.4032 7.31146 12.6102 7.39717 12.7627 7.54974C12.9153 7.7023 13.001 7.90923 13.001 8.12499C13.001 8.34075 12.9153 8.54767 12.7627 8.70024L9.27386 12.1875H18.6875C18.903 12.1875 19.1096 12.2731 19.262 12.4255C19.4144 12.5778 19.5 12.7845 19.5 13Z"
                        fill="white" />
                </svg>
            </a>
            <button type="submit" class="boton btnAdd">Guardar Cambios
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 26 27" fill="none">
                    <path
                        d="M7.11751 6.91875C6.86324 6.91875 6.61937 7.01976 6.43957 7.19956C6.25977 7.37936 6.15876 7.62322 6.15876 7.8775V19.3825C6.15876 19.6368 6.25977 19.8806 6.43957 20.0604C6.61937 20.2402 6.86324 20.3413 7.11751 20.3413H18.6225C18.8768 20.3413 19.1207 20.2402 19.3005 20.0604C19.4803 19.8806 19.5813 19.6368 19.5813 19.3825V7.8775C19.5813 7.62322 19.4803 7.37936 19.3005 7.19956C19.1207 7.01976 18.8768 6.91875 18.6225 6.91875H14.3081C14.0539 6.91875 13.81 7.01976 13.6302 7.19956C13.4504 7.37936 13.3494 7.62322 13.3494 7.8775V14.8697L15.8862 12.3319C15.9763 12.2418 16.0983 12.1913 16.2256 12.1913C16.3529 12.1913 16.475 12.2418 16.565 12.3319C16.655 12.4219 16.7056 12.544 16.7056 12.6712C16.7056 12.7985 16.655 12.9206 16.565 13.0106L13.2094 16.3663C13.1649 16.4109 13.112 16.4463 13.0537 16.4705C12.9955 16.4947 12.9331 16.5071 12.87 16.5071C12.807 16.5071 12.7445 16.4947 12.6863 16.4705C12.628 16.4463 12.5751 16.4109 12.5306 16.3663L9.17499 13.0106C9.13042 12.9661 9.09506 12.9132 9.07094 12.8549C9.04682 12.7967 9.03441 12.7343 9.03441 12.6712C9.03441 12.6082 9.04682 12.5458 9.07094 12.4876C9.09506 12.4293 9.13042 12.3764 9.17499 12.3319C9.21956 12.2873 9.27247 12.2519 9.33071 12.2278C9.38894 12.2037 9.45136 12.1913 9.51439 12.1913C9.57742 12.1913 9.63983 12.2037 9.69807 12.2278C9.7563 12.2519 9.80921 12.2873 9.85379 12.3319L12.3906 14.8697V7.8775C12.3906 7.36895 12.5927 6.88122 12.9523 6.52162C13.3119 6.16202 13.7996 5.96 14.3081 5.96H18.6225C19.1311 5.96 19.6188 6.16202 19.9784 6.52162C20.338 6.88122 20.54 7.36895 20.54 7.8775V19.3825C20.54 19.8911 20.338 20.3788 19.9784 20.7384C19.6188 21.098 19.1311 21.3 18.6225 21.3H7.11751C6.60896 21.3 6.12124 21.098 5.76164 20.7384C5.40203 20.3788 5.20001 19.8911 5.20001 19.3825V7.8775C5.20001 7.36895 5.40203 6.88122 5.76164 6.52162C6.12124 6.16202 6.60896 5.96 7.11751 5.96H9.51439C9.64153 5.96 9.76346 6.0105 9.85336 6.1004C9.94326 6.19031 9.99376 6.31224 9.99376 6.43937C9.99376 6.56651 9.94326 6.68844 9.85336 6.77834C9.76346 6.86824 9.64153 6.91875 9.51439 6.91875H7.11751Z"
                        fill="white" />
                </svg>
            </button>
        </div>
        <br>

    </form>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        let isDirty = false;
        const form = document.getElementById('edit-form');
        const inputs = form.querySelectorAll('input, select');
        const searchInput = document.getElementById('search-proceso');
        const procesoItems = document.querySelectorAll('.proceso-item');
        const nombreProcesoInput = document.getElementById('nombre_proceso');
        // Validar que el nombre del proceso no contenga puntos
        form.addEventListener('submit', function (event) {
            let nombreProceso = nombreProcesoInput.value;

            if (nombreProceso.includes('.')) {
                alert('No se permite el uso de puntos en el nombre del proceso.');
                event.preventDefault(); // Evita que el formulario se envíe
                return;
            }
            nombreProcesoInput.value = nombreProceso.toUpperCase();
        });
        // Detectar cambios en los campos del formulario
        inputs.forEach(function (input) {
            input.addEventListener('change', function () {
                isDirty = true;
            });
        });
        // Interceptar clicks en las flechas de navegación
        document.getElementById('prev-link').addEventListener('click', function (event) {
            if (isDirty && !confirm('Tienes cambios sin guardar. ¿Estás seguro de que deseas salir sin guardar?')) {
                event.preventDefault();
            }
        });

        document.getElementById('next-link').addEventListener('click', function (event) {
            if (isDirty && !confirm('Tienes cambios sin guardar. ¿Estás seguro de que deseas salir sin guardar?')) {
                event.preventDefault();
            }
        });
        // Detectar clicks en los cuadros de restricción para marcar el formulario como modificado
        document.querySelectorAll('.proceso-box').forEach(function (box) {
            box.addEventListener('click', function () {
                this.classList.toggle('selected');
                this.classList.toggle('border-primary');
                this.classList.toggle('shadow');
                var checkbox = this.nextElementSibling;
                checkbox.checked = !checkbox.checked;
                isDirty = true; // Marcar el formulario como modificado
            });
        });
        // Filtrar procesos por nombre
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            procesoItems.forEach(function (item) {
                const nombreProceso = item.getAttribute('data-nombre');
                if (nombreProceso.includes(query)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
<style>
    .proceso-box {
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .proceso-box.selected {
        background-color: #f0f8ff;
    }

    .proceso-box:hover {
        box-shadow: 0 0 11px rgba(33, 33, 33, .2);
    }
</style>
<?= $this->endSection() ?>