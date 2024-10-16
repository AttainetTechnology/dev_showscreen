
<?= $this->extend('layouts/ventana_flotante')?>
<?= $this->section('content')?>
	<div class="pt-3 px-3 contactos">
            <!-- Creo este estilo para quitar el botón de "cerrar" -->
            <style>
            .contactos .gc-close-button {
                display: none!Important;
            }
            </style>
                <? echo $output; ?>
            </div>
<?= $this->endSection()?>