<?php

namespace App\Controllers;

require_once 'vendor/autoload.php';

class Login extends LoginController
{
    public function index()
    {
        include_once(__DIR__ . '/../../clean_sessions.php');
        helper('controlacceso');
        control_acceso();
        return redirect()->to('/index');
    }

    public function google_login()
    {
        $gClient = new \Google_Client();
        $gClient->setHttpClient(new \GuzzleHttp\Client(['verify' => __DIR__ . '/../../cacert.pem']));
        $gClient->setClientId('76432046723-b6fuespefbntj888her8v4koi71f8h2a.apps.googleusercontent.com');
        $gClient->setClientSecret('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');
        $gClient->setRedirectUri('https://dev.showscreen.app/google_login');
        $gClient->addScope("https://www.googleapis.com/auth/userinfo.email");
        $gClient->addScope("https://www.googleapis.com/auth/userinfo.profile");

        $google_oauthV2 = new \Google_Service_Oauth2($gClient);

        if (isset($_GET['code'])) {
            $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
            if (!isset($token['error'])) {
                $_SESSION['token'] = $token;
                $gClient->setAccessToken($token);
            } else {
                error_log("Error fetching access token: " . $token['error']);
                $authUrl = $gClient->createAuthUrl();
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit;
            }
        } elseif (isset($_SESSION['token'])) {
            $gClient->setAccessToken($_SESSION['token']);
            if ($gClient->isAccessTokenExpired()) {
                $authUrl = $gClient->createAuthUrl();
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit;
            }
        }

        if ($gClient->getAccessToken()) {
            try {
                $userProfile = $google_oauthV2->userinfo->get();
                $userModel = new \App\Models\Usuarios1_Model();
                $user = $userModel->where('email', $userProfile['email'])->first();

                if ($user) {
                    // Iniciar la sesión y redirigir al usuario
                    $config = model('App\Models\Home_model');
                    $database_data = $config->find($user['id_empresa']);
                    $new_db = [
                        'DSN'      => '',
                        'hostname' => 'localhost',
                        'username' => $database_data['db_user'],
                        'password' => $database_data['db_password'],
                        'database' => $database_data['db_name'],
                        'DBDriver' => 'MySQLi',
                        'DBPrefix' => '',
                        'pConnect' => false,
                        'DBDebug'  => (ENVIRONMENT !== 'development'),
                        'charset'  => 'utf8',
                        'DBCollat' => 'utf8_general_ci',
                        'swapPre'  => '',
                        'encrypt'  => false,
                        'compress' => false,
                        'strictOn' => false,
                        'failover' => [],
                        'port'     => 3306
                    ];
                    $session = session();
                    $sess_array = array(
                        'id_user' => $user['id'],
                        'username' => $user['username'],
                        'nivel' => $user['nivel_acceso'],
                        'id_empresa' => $user['id_empresa'],
                        'new_db' => $new_db,
                        'url_logo' => $database_data['logo_empresa'],
                        'empresa' => $database_data['id'],
                        'nombre_empresa' => $database_data['nombre_empresa'],
                        'favicon' => $database_data['favicon'],
                        'logo_fichajes' => $database_data['logo_fichajes']
                    );
                    $session->set(array('logged_in' => $sess_array));
                    
                    // Añadir script para cerrar la ventana emergente y recargar la página principal
                    echo "<script>
                        window.opener.location.reload();
                        window.close();
                    </script>";
                    exit;
                } else {
                    session()->setFlashdata('error', 'No account exists for the provided email.');
                    $authUrl = $gClient->createAuthUrl();
                    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                    exit;
                }
            } catch (Exception $e) {
                session()->setFlashdata('error', 'Failed to retrieve user data from Google.');
                return redirect()->to('login');
            }
        } else {
            $authUrl = $gClient->createAuthUrl();
            header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
            exit;
        }
    }

    public function crea_sesion($record)
    {
        // Crear un array con los datos de conexión a la BDD del cliente
        $config = model('App\Models\Home_model');
        $database_data = $config->find($record->id_empresa);
        $new_db = [
            'DSN'      => '',
            'hostname' => 'localhost',
            'username' => $database_data['db_user'],
            'password' => $database_data['db_password'],
            'database' => $database_data['db_name'],
            'DBDriver' => 'MySQLi',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug'  => (ENVIRONMENT !== 'development'),
            'charset'  => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre'  => '',
            'encrypt'  => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port'     => 3306
        ];
        $url_logo = $database_data['logo_empresa'];
        $logo_fichajes = $database_data['logo_fichajes'];
        $favicon = $database_data['favicon'];
        $empresa = $database_data['id'];
        $nombre_empresa = $database_data['nombre_empresa'];
        $session = session();
        $sess_array = array(
            'id_user' => $record->id,
            'username' => $record->username,
            'nivel' => $record->nivel_acceso,
            'id_empresa' => $record->id_empresa,
            'new_db' => $new_db,
            'url_logo' => $url_logo,
            'empresa' => $empresa,
            'nombre_empresa' => $nombre_empresa,
            'favicon' => $favicon,
            'logo_fichajes' => $logo_fichajes
        );

        $session->set(array('logged_in' => $sess_array));
        // Redirigir basado en el nivel de acceso
        return redirect()->to('/index');
    }
}
