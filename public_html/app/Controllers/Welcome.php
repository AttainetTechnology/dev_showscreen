<?php

namespace App\Controllers;

class Welcome extends BaseController
{

	function __construct()
	{
		$validation = \Config\Services::validation();
		$session = session();
		if (empty($session->get('logged_in'))) {
			return redirect()->to('Login');
		}

		/* Standard Libraries of codeigniter are required */
		$database = \Config\Services::database();
		helper('url');
		/* ------------------ */


	}
	public function index()
	{
		return view('welcome_message');
	}
}