<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>ATTAINET - INTRANET</title>

    <!-- Cargamos Bootstrap v5.02 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="<?= base_url("public/assets/css/menu_lateral.css") ?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?= base_url('public/assets/css/attainet.css') ?>?v=<?= time() ?>">

    <!-- Cargo FAVICON  -->
    <?php
    helper('controlacceso');
    $data = datos_user();
    $favicon = null;

    if ($data !== null && isset($data['favicon'])) {
        $favicon = $data['favicon'];
    }
    ?>
    <link rel="icon" href="<?= base_url("public/assets/uploads/files/" . $id_empresa . "/favicon/" . $favicon) ?>"
        type="image/gif">
</head>

<body>
    <div class="row">
        <?php
        // Cargamos el contenido del Output (GroceryCrud)
        if (!empty($output)) {
            echo $output;
        }
        // End Grocery CRUD
        // Cargamos la sección CONTENIDO
        if (!empty($this->renderSection('content'))) {
            echo $this->renderSection('content');
        }
        // Fin sección CONTENIDO
        ?>
        <!-- FIN DEL CONTENIDO DINÁMICO -->
    </div>

    <!-- Cargo Scripts -->
    <!-- jQuery (Opcional, requerido por Bootstrap) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap Bundle (JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Js de Grocery CRUD -->
    <?php
    if (!empty($js_files)) {
        foreach ($js_files as $file) { ?>
            <script src="<?= $file ?>"></script>
        <?php }
    }
    ?>
</body>

</html>