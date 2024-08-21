<div class="container-sm menu_transporte mt-3">
    <div class="row">
        <div class="col-8 logo_transporte">
            <!-- Mostrar logo -->

            <img src="<?php
                        $session = session();
                        $session_data = $session->get('logged_in');
                        $id_empresa = $session_data['id_empresa'];
                        echo base_url('public/assets/uploads/files/' . $url_logo);
                        ?>">

        </div>
        <div class="col-4 text-end">
            <!-- Resto del código de la vista -->


            <?php
            // Obtener sesión
            $session = session();
            $session_data = $session->get('logged_in');
            // Mostrar botón del menú desplegable del usuario si el nivel de acceso es mayor a 1
            if ($session_data && array_key_exists('nivel', $session_data) && $session_data['nivel'] > 1) {
            ?>
                <!-- Botón del menú desplegable del usuario -->
                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-user fa-fw"></i>
                    <?php
                    // Mostrar nombre del usuario si existe en la sesión
                    if (array_key_exists('id_user', $session_data)) {
                        // Obtener una instancia del controlador
                        $controller = new \App\Controllers\Rutas();
                        // Obtener el nombre del usuario
                        $nombre_usuario = $controller->obtenerNombreTransportistaPorId($session_data['id_user']);
                        echo $nombre_usuario;
                    }
                    ?>
                </button>
                <!-- Menú desplegable del usuario (inicialmente oculto) -->
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2" id="userDropdown" style="display: none; position: absolute; top: 100%; left: 0; transform: translateY(5px);">
                    <?php
                    // Obtener datos de usuarios con nivel de acceso 1 y de la misma empresa
                    $datosUsuario = new \App\Models\Usuarios2_Model();
                    $usuarios = $datosUsuario->where('nivel_acceso', 1)->where('id_empresa', $session_data['id_empresa'])->findAll();

                    // Mostrar lista de usuarios
                    foreach ($usuarios as $usuario) {
                        if (array_key_exists('id', $usuario)) {
                            // Obtener el nombre del usuario
                            $nombre_usuario = $controller->obtenerNombreTransportistaPorId($usuario['id']);
                            echo '<a class="dropdown-item" href="#" data-id="' . $usuario['id'] . '" data-nivel_acceso="' . $usuario['nivel_acceso'] . '">' . $nombre_usuario . '</a></br>';
                        }
                    }

                    // Mostrar el usuario actual en la lista
                    if (array_key_exists('id_user', $session_data)) {
                        $nombre_usuario = $controller->obtenerNombreTransportistaPorId($session_data['id_user']);
                        $nivel_acceso = array_key_exists('nivel_acceso', $session_data) ? $session_data['nivel_acceso'] : 0;
                        echo '<a class="dropdown-item" href="#" data-id_user="' . $session_data['id_user'] . '" data-nivel_acceso="' . $nivel_acceso . '">' . $nombre_usuario . '</a></br>';
                        // Si el nivel de acceso es 1, mostrar la opción de editar acceso
                        if ($session_data['nivel'] == 1) {
                            echo '<a class="dropdown-item" href="https://dev.showscreen.app/usuarios/' . $session_data['id_user'] . '">Editar Acceso</a></br>';
                        }
                    }
                    ?>
                </div>
            <?php } // Fin de la condición 
            ?>


        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Selecciona todos los elementos del dropdown
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        const dropdownMenuButton2 = document.querySelector('#dropdownMenuButton2');
        const userDropdown = document.querySelector('#userDropdown');
        const location = window.location;

        // Añade evento de click a cada elemento del dropdown
        dropdownItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault(); // Previene la acción por defecto del enlace
                if (this.textContent === 'Editar Acceso') {
                    location.href = this.getAttribute('href');
                } else {
                    // Obtiene los atributos y contenido del elemento seleccionado
                    const id = this.getAttribute('data-id');
                    const nombre_usuario = this.textContent;
                    const nivel_acceso = this.getAttribute('data-nivel_acceso');
                    // Guarda los datos seleccionados en el almacenamiento local
                    localStorage.setItem('selectedNombreUsuario', nombre_usuario);
                    localStorage.setItem('selectedId', id);
                    localStorage.setItem('selectedNivelAcceso', nivel_acceso);
                    // Actualiza el botón del menú con el nombre de usuario seleccionado
                    dropdownMenuButton2.innerHTML = '<i class="fa fa-user fa-fw"></i> ' + nombre_usuario;
                    userDropdown.style.display = 'none';
                    // Redirecciona después de un segundo dependiendo del nivel de acceso
                    setTimeout(function() {
                        location.href = nivel_acceso == 1 ?
                            "<?php echo base_url('rutas_transporte/rutas?transportista='); ?>" + id :
                            "<?php echo base_url('rutas_transporte/rutas'); ?>";
                    }, 1000);
                }
            });
        });

        // Evento que se dispara al cargar la ventana
        window.addEventListener('load', function() {
            let selectedNombreUsuario = localStorage.getItem('selectedNombreUsuario');
            let selectedId = localStorage.getItem('selectedId');
            // Si no hay datos guardados, utiliza los datos de sesión
            if (!selectedNombreUsuario || !selectedId) {
                selectedNombreUsuario = '<?php echo $nombre_usuario; ?>';
                selectedId = '<?php echo $session_data['id_user']; ?>';
                localStorage.setItem('selectedNombreUsuario', selectedNombreUsuario);
                localStorage.setItem('selectedId', selectedId);
            }
            // Actualiza el botón del menú con el nombre de usuario guardado
            dropdownMenuButton2.innerHTML = '<i class="fa fa-user fa-fw"></i> ' + selectedNombreUsuario;
            // Elimina el elemento del dropdown que coincide con el usuario seleccionado
            dropdownItems.forEach(item => {
                if (item.textContent === selectedNombreUsuario) {
                    item.remove();
                }
            });
        });

        // Alterna la visibilidad del dropdown al hacer click en el botón del menú
        dropdownMenuButton2.addEventListener('click', function() {
            userDropdown.style.display = userDropdown.style.display === 'none' ? 'block' : 'none';
        });

        // Cerrar el dropdown si se hace clic fuera de él
        document.addEventListener('click', function(event) {
            const isClickInside = dropdownMenuButton2.contains(event.target) || userDropdown.contains(event.target);

            if (!isClickInside) {
                userDropdown.style.display = 'none';
            }
        });

    });
</script>