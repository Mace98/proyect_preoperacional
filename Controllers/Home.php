<?php 
	
	/**
	 * Clase que permite visualizar la pagina de login de la aplicacion
	 */
	class Home extends Controllers
	{
		public $model;
		public $views;
		
		public	function __construct()
		{
			session_start();
			parent::__construct();
			// Evitar redirección automática si es una petición AJAX a login
			
		}

		public function home()
		{
			$data['page_tag'] = "INICIO";
			$data['page_title'] = "Página Principal";
			$data['page_name'] = "INICIO";
			$data['data_functions_js'] = "function_login.js";
			$data['app'] = "app.js";
			$this->views->getView($this,"Home",$data);
		}

		public function session()
		{
			$json = file_get_contents('php://input');
			$params = json_decode($json, true);
			// Variables de la peticion
			$user = strtoupper($params['user']);
			$pass = strtoupper($params['pass']);
			// Realizar la peticion a la Backend para validar el usuario y contraseña

			$data = $this->model->login($user, $pass);
			
			if($data){
				$_SESSION['id'] 	= $data[0]['id_cliente'];
				$_SESSION['nombre'] = $data[0]['nombre'];
				$_SESSION['estado'] = $data[0]['estado'];
				$_SESSION['login']  = true;
				$json = array('status' => 'success', 'message' => 'Bienvenido(a) '. $data[0]['nombre']);
			}else{
				$json = array('status' => 'error', 'message' => 'Login fallido');
			}
			echo json_encode($json, JSON_UNESCAPED_UNICODE);
			// echo json_encode($params);
		}
	}

?>