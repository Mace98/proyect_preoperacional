<?php

class Logout
	{
		public function __construct()
		{
			session_start();
			session_unset();
			session_destroy();

			if (isset($_SESSION['id'])) {
				//$this->model->setRemenberSesion($_SESSION['id'], null);
			}

			session_destroy();
			//setcookie("remember_token", "", time() - 3600, "/");

			header("Location: " . base_url());
			exit;



		}
		
	}
?>