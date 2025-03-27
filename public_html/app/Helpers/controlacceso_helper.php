<?php
if (!function_exists('check_access_level')) {

    function check_access_level() {
        $session = session();
        
        if ($session->get('logged_in')) {
            $userData = $session->get('logged_in');  
            $userLevel = $userData['nivel'];
            
            // Si el nivel del usuario es 1, establece la URL de redirección
            if ((int)$userLevel === 1) {
                // Establece la URL de redirección
                $session->setFlashdata('redirect', base_url('Rutas_transporte/rutas'));
                return true;  // Indica que se debe redirigir
            }
        }
        // Si no está logueado o no se cumple la condición, no hace nada
        return false;  // No hay necesidad de redirigir
    }
}

function control_acceso() {
    $session = session();
    if (empty($session->get('logged_in'))) { 
        loginpage();
    } else {
        $nivelusuario = usuario_sesion();
        $nivel = $nivelusuario['nivel'];
        //echo "El usuario tiene el nivel: ".$nivel;
        if ($nivel < '2') {
            //echo "El usuario no tiene permisos";
            nivel1(); 
        } else {
            pagina_inicio();  
        }
    }
}

// Esta función comprueba si el usuario está logueado 
// si lo está no hace nada, si no tiene nivel lo lleva a su pagina
function control_login() {
    //echo "<pre>";
    //print_r($data);
    //exit;
    $session = session();
    if (empty($session->get('logged_in'))) { 
        loginpage();
    } else {
        $nivelusuario = usuario_sesion();
        $nivel = $nivelusuario['nivel'];
        //echo "El usuario tiene el nivel: ".$nivel;
        if ($nivel < '2') {
            //echo "El usuario no tiene permisos";
            nivel1(); 
        } else {
            return $nivel; 
        }
    }
}

// Comprueba que es superadmin
function superadmin() {
    $nivelusuario = usuario_sesion();
    $nivel = $nivelusuario['nivel'];
    if ($nivel < '9') {
        header('Location: '.base_url().'/select_empresa');
        exit;
    } else {
        return $nivel; 
    }
}

// Página de inicio
function pagina_inicio() {
    header('Location: '.base_url().'Index');
    exit;
}

function nivel1() {
    $currentURL = current_url(); // Obtén la URL actual
    $expectedURL = base_url().'Rutas_transporte/rutas'; // La URL a la que quieres redirigir
    $allowedURLs = array(
        base_url(), 
        base_url().'home/logout', 
        base_url().'rutas_transporte/rutas/entregado', 
        base_url().'rutas_transporte/rutas/pendiente',
        base_url().'Rutas_transporte/save',

    );
    $session = session();
    $hasLoggedIn = $session->get('hasLoggedIn');

    // Si el usuario acaba de iniciar sesión, establece la variable de sesión 'hasLoggedIn' en true
    if (!$hasLoggedIn) {
        $session->set('hasLoggedIn', true);
    }

    // Si la URL actual no es la esperada y no está en la lista de URLs permitidas, redirige
    if ($currentURL !== $expectedURL && !in_array($currentURL, $allowedURLs)) {

        if (!$hasLoggedIn) {
            header('Location: '.$expectedURL);
            exit();
             } else {
            return redirect()->to($expectedURL);
            exit();
         
        }
    }
}

// Login page
function loginpage() {
    // If no session, redirect to login page
    helper(array('form'));
    helper('logo');
    $logourl = logo();
    $data['logo'] = $logourl;
    echo view('login_view', $data);
    exit(); 
}

// Obtener los datos de sesión del usuario
function usuario_sesion() {
    $session = session();
    if ($session->get('logged_in')) { 
        $session_data = $session->get('logged_in');
        return $session_data;
        exit;
    }
}
function datos_user() {
    $userdata = usuario_sesion();
    return $userdata;
    exit;
}
?>
