<?php

namespace App\Controllers;
use App\Models\Usuarios2_Model;


class Acceso extends CrudAcceso
{

  function index($id_empresa)
  {
    //Creamos una nueva sesión pasándole los datos de la empresa
    $this->crea_sesion($id_empresa);
    //Redirigimos de nuevo al login
    return redirect()->to('login');
    $session = session();
    $sess_array = array(
      'id_user' => $id,
      'username' => $username,
      'nombre_usuario' => $nombre_usuario,
      'apellidos_usuario' => $apellidos_usuario,
      'nivel' => $nivel_acceso,
      'foto' => $userfoto,
      'user_activo' => $user_activo,
      'new_db' => $new_db,
      'url_logo' => $url_logo,
      'empresa' => $empresa,
      'nombre_empresa' => $nombre_empresa,
      'favicon' => $favicon,
      'logo_fichajes' => $logo_fichajes
    );

    // Obtener los datos de la sesión actual
    $oldSessionData = $session->get();

    // Combinar los datos de la sesión antigua con los nuevos datos de la sesión,
    $newSessionData = array_merge($oldSessionData, $sess_array);

    // Establecer los nuevos datos de la sesión
    $session->set(array('logged_in' => $newSessionData));
  }

  function crea_sesion($id_empresa)
  {
    $id_empresa = $id_empresa;
    //Conectamos la BDD del cliente
    $config = model('App\Models\Home_model');
    $database_data = $config->find($id_empresa);
    $new_db = [
      'DSN' => '',
      'hostname' => 'localhost',
      'username' => $database_data['db_user'],
      'password' => $database_data['db_password'],
      'database' => $database_data['db_name'],
      'DBDriver' => 'MySQLi',
      'DBPrefix' => '',
      'pConnect' => false,
      'DBDebug' => (ENVIRONMENT !== 'production'),
      'charset' => 'utf8',
      'DBCollat' => 'utf8_general_ci',
      'swapPre' => '',
      'encrypt' => false,
      'compress' => false,
      'strictOn' => false,
      'failover' => [],
      'port' => 3306
    ];

    $url_logo = $database_data['logo_empresa'];
    $favicon = $database_data['favicon'];
    $logo_fichajes = $database_data['logo_fichajes'];
    //Creo la variable "empresa" para tener la id de la empresa independiente del usuario al que esté asignada
    $empresa = $database_data['id'];
    $nombre_empresa = $database_data['nombre_empresa'];
    helper('controlacceso');
    // Obtener los datos del usuario y almacenarlos en $data
    $this->data = datos_user();

    // Creo una instancia del modelo de usuarios para sacar el nombre y la foto del user activo
    $datosUsuario = new Usuarios2_Model($this->db);
    $itemsUsuario = $datosUsuario->find($this->data['id_user']);

    if ($itemsUsuario !== null) {
      $this->data['nombre_usuario'] = $itemsUsuario['nombre_usuario'];
      $this->data['apellidos_usuario'] = $itemsUsuario['apellidos_usuario'];
      $this->data['userfoto'] = $itemsUsuario['userfoto'];
      $this->data['user_activo'] = $itemsUsuario['user_activo'];
    } else {
      // Si los datos del usuario no se encuentran en la base de datos, los obtenemos de la sesión
      $session = session();
      $sessionData = $session->get('logged_in');

      if (isset($sessionData)) {
        $this->data['nombre_usuario'] = $sessionData['nombre_usuario'];
        $this->data['apellidos_usuario'] = $sessionData['apellidos_usuario'];
        $this->data['userfoto'] = $sessionData['foto'];
        $this->data['user_activo'] = $sessionData['user_activo'];
      }
    }

    // echo "<pre>";
    // print_r($this->data); 
    // echo "</pre>";
    // exit;

    $id = $this->data['id_user'];
    $username = $this->data['username'];
    $apellidos_usuario = $this->data['apellidos_usuario'];
    $nombre_usuario = $this->data['nombre_usuario'];
    $nivel_acceso = $this->data['nivel'];
    $userfoto = $this->data['userfoto'];
    $user_activo = $this->data['user_activo'];

    $session = session();
    $sess_array = array(
      'id_user' => $id,
      'username' => $username,
      'nombre_usuario' => $nombre_usuario,
      'apellidos_usuario' => $apellidos_usuario,
      'nivel' => $nivel_acceso,
      'foto' => $userfoto,
      'user_activo' => $user_activo,
      'id_empresa' => $id_empresa,
      'new_db' => $new_db,
      'url_logo' => $url_logo,
      'empresa' => $empresa,
      'nombre_empresa' => $nombre_empresa,
      'favicon' => $favicon,
      'logo_fichajes' => $logo_fichajes
    );
    $session->set(array('logged_in' => $sess_array));

    //echo "<pre>";
    //print_r($sess_array); 
    //echo "</pre>";
  }
}