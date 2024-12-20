<?= $this->extend('layouts/main')?>

<?= $this->section('content')?>

<style>

    /* Oculta cajas y botones de GC por defecto */

        .password .gc-close-button, .card, .pt-3, .py-3, .gc-loading, .loading-opacity.hidden, .options-on-save{
            display: none !important;
        }
        
 </style>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    $(document).ready(function() {
        // Oculta el formulario hasta que el nombre del usuario esté cargado
        $('.gc-form-standalone-body').hide();
    
        // Obtiene el ID del usuario de la URL
        var userId = window.location.href.split('/').pop();
    
        // Retrasa la ejecución del código hasta que el elemento .gc-form-standalone-body esté cargado
        var checkExist = setInterval(function() {
            if ($('.gc-form-standalone-body').length) {
                clearInterval(checkExist);
    
                // Hace una petición AJAX para obtener el nombre del usuario
                $.get('https://dev.showscreen.app/usuarios/getNombreUsuario/' + userId, function(response) {
                    // Añade el nombre del usuario justo antes del elemento .gc-form-standalone-body
                    $('<div class="form-group"><label></label><input type="text" readonly class="form-control" value="' + response.nombre_usuario + '"></div>').insertBefore('.gc-form-standalone-body');
  
                    // Muestra el formulario una vez que el nombre del usuario esté cargado
                    $('.gc-form-standalone-body').show();
                }).fail(function() {
                    // Maneja el error de la petición AJAX
                    console.error('Error al obtener el nombre del usuario');
                });
            }
        }, 100); // verifica cada 100ms
    });
</script>
<script>
    $(document).on('click', '.gc-close-button', function(e) {
        e.preventDefault();
        window.location.href = 'https://dev.showscreen.app/usuarios/';
    });
</script>
<?= $this->endSection()?>