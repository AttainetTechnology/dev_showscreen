<?php

namespace App\Controllers;

class Home extends BaseController
{
  public function index()
  {
      return redirect()->to('https://dev.showscreen.app/index');
  }
    function logout()
    {
    $session = session();
    $session->removeTempdata('logged_in');
    $session->destroy();
    //If no session, redirect to login page
      header('Location: '.base_url());
      exit;
    }
}

