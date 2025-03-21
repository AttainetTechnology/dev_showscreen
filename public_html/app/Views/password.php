<?= $this->extend('layouts/main')?>

<?= $this->section('content')?>

<style>


        .password .gc-close-button, .card, .pt-3, .py-3, .gc-loading, .loading-opacity.hidden, .options-on-save{
            display: none !important;
        }
        
 </style>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    $(document).ready(function() {
        $('.gc-form-standalone-body').hide();
    
        var userId = window.location.href.split('/').pop();
    
        var checkExist = setInterval(function() {
            if ($('.gc-form-standalone-body').length) {
                clearInterval(checkExist);
    
                $.get('https://dev.showscreen.app/usuarios/getNombreUsuario/' + userId, function(response) {
                    $('<div class="form-group"><label></label><input type="text" readonly class="form-control" value="' + response.nombre_usuario + '"></div>').insertBefore('.gc-form-standalone-body');
  
                    $('.gc-form-standalone-body').show();
                }).fail(function() {
                    console.error('Error al obtener el nombre del usuario');
                });
            }
        }, 100); 
    });
</script>
<script>
    $(document).on('click', '.gc-close-button', function(e) {
        e.preventDefault();
        window.location.href = 'https://dev.showscreen.app/usuarios/';
    });
</script>
<?= $this->endSection()?>