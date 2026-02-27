<?php 

	class HomeModel extends Pgsql
	{

		private $user;
		private $pass;
		public function __construct()
		{
			parent::__construct();
		}

		public function login($user, $pass)
		{
			// Asigancion de variablas
			$this->user = $user;
			$this->pass = $pass;

			// Consulta que permite saber si las credenciales son correctas
			// $chech_user = "SELECT id_cliente, nombre, estado 
			//  				FROM public.clientes 
			// 				WHERE usuario = '$this->user' 
			//  				AND pass = '$this->pass'
			//  				AND estado = 1 ";
			$chech_user = "SELECT id_cliente, nombre, estado
											FROM public.clientes c
											INNER JOIN modulos.pre_reg_credenciales pc ON idusuario = id_cliente 
											WHERE info_user = '$this->user'
											AND info_pass = '$this->pass'
											AND estado = 1";
			 				
			$result = $this->select_all($chech_user);
			if(empty($result)){
				return false;
			}else{
				return $result;
			}
		}
	}

?>