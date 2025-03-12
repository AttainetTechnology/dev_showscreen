<?php //print_r($presentes); ?>
<?= $cabecera; ?>

<body class="page-presentes" onload="startTime()">
   <?= $menu; ?>

   <div class="fondo-empleados">
      <div class="container-fluid d-flex flex-row flex-wrap">
         <!-- Comienza el loop -->
         <?php foreach ($presentes as $presente): ?>
            <a href="<?= base_url('salir'); ?>/<?= $presente['id_empleado']; ?>">
               <div class="tarjeta-empleado 
      <?php if ($presente['extras'] == 1) { ?>
         tarjeta_horasextras
      <?php } ?>">
                  <div class="foto_empleado">
                     <?php if (!empty($presente['userfoto'])) { ?>
                        <img
                           data-src="<?= base_url('public/assets/uploads/files/' . session()->get('id') . '/usuarios/' . $presente['userfoto']); ?>">
                     <?php } else { ?>
                        <img data-src="<?= base_url('public/assets/uploads/files/silueta.png') ?>">
                     <?php } ?>
                  </div>
                  <div class="nombre-empleado"><?= $presente['nombre_usuario']; ?>    <?= $presente['apellidos_usuario']; ?>
                  </div>
                  <?php
                  $originalDate = $presente['entrada'];
                  $newDate = date("G:i", strtotime($originalDate));
                  ?>
                  <div class="hora_entrada">Hora: <?= $newDate; ?></div>
                  <br>
                  <?php if (!empty($presente['maquinas'])): ?>
                     <div class="maquinas">
                        <?php foreach ($presente['maquinas'] as $maquina): ?>
                           <span><?= $maquina['nombre']; ?></span>
                        <?php endforeach; ?>
                     </div>
                  <?php endif; ?>
               </div>
            </a>
         <?php endforeach; ?>
         <!-- Fin del loop -->
      </div>
   </div>

   <?= $pie; ?>
   <?= $recarga_hora; ?>

   <script>
      window.history.replaceState({}, document.title, "<?= base_url('presentes'); ?>");
   </script>