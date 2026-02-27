<?php
    // Calse que permite visualizar la informacion incial despues del login
    class Inicio extends Controllers
    {
			public $model;
			public $views;

        public function __construct()
				{
					parent::__construct();
					session_start();
					if (!isset($_SESSION['login'])) {
						header('Location: ' . base_url() . 'home');
						exit;
					}
				}


        public function inicio()
				{
					$data['page_tag'] = "Inicio | STAR SEGUIMIENTO";
					$data['page_title'] = "Página Principal";
					$data['page_name'] = "Inicio | STAR SEGUIMIENTO";
					$data['data_functions_js'] = "function_inicio.js";
					$data['app'] = "app.js";
					// $data['styles'] = "inicio.css";
					$this->views->getView($this,"inicio",$data);
				}

		// Method que permite recibir la peticion del FRONTEND y enviarla al BACKEND solicitando la informacion de los vehículos
				public function obtener_placas()
				{
					$htmlOptions = "";
					$arrData = $this->model->getDatosPlacas($_SESSION['id']);
					// dep($arrData);
					if(count($arrData) > 0){
						$htmlOptions ='<option value="0">'."Seleccione Placa".'</option>';
						for($i = 0; $i < count($arrData); $i++){
							$htmlOptions .='<option value="'.$arrData[$i]['id_movil'].'">'.$arrData[$i]['placa'].'</option>';
						}
					}
					echo $htmlOptions;
					die();
				}

		// Method que permite recibir la peticion del FRONTEND y enviarla al BACKEN solicitando la informacion de los conductores
				public function obtener_conductores()
				{
					$htmlOptions = "";
					$arrData = $this->model->getDatosConductores($_SESSION['id']);
					// dep($arrData);
					if(count($arrData) > 0){
						$htmlOptions ='<option value="0">'."Seleccione Conductor".'</option>';
						for($i = 0; $i < count($arrData); $i++){
							$htmlOptions .='<option value="'.$arrData[$i]['dni'].'">'.$arrData[$i]['nombre'].'</option>';
						}
					}
					echo $htmlOptions;
					die();
				}

		// Method que permite recibir los datos 
    }
?>