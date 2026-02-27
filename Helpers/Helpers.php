<?php 

	
	function base_url()
	{
		return BASE_URL;
	}

	function media()
	{
		return BASE_URL."Assets";
	}

	function headerAdmin($data = "")
	{
		$view_header = "Views/Template/header_admin.php";
		require_once($view_header);
	}

	function footerAdmin($data = "")
	{
		$view_footer = "Views/Template/footer_admin.php";
		require_once($view_footer);
	}
	
	function dep($data)
	{
		$format = print_r("<pre>");
		$format .= print_r($data);
		$format .= print_r("</pre>");
		return $format;
	}

	function getModal(string $nameModal, $data)
	{
		$view_modal = "Views/Template/Modals/{$nameModal}.php";
		require_once($view_modal);
	}

	function strClean($strCadena)
	{
		$string = preg_replace(['/\s+/','/^\s|\s$/'], [' ',''], $strCadena);
		$string = trim($string);
		$string = stripcslashes($string);
		$string = str_ireplace("<script>", "", $string);
		$string = str_ireplace("</script>", "", $string);
		$string = str_ireplace("<script src>", "", $string);
		$string = str_ireplace("<script type>", "", $string);
		$string = str_ireplace("SELECT * FROM", "", $string);
		$string = str_ireplace("DELETE FROM", "", $string);
		$string = str_ireplace("INSERT INTO","", $string);
		$string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
		$string = str_ireplace("DROP TABLE", "", $string);
		$string = str_ireplace("OR '1'='1","", $string);
		$string = str_ireplace('OR "1"="1"', "", $string);
		$string = str_ireplace('OR ´1´=´1´', "", $string);
		$string = str_ireplace("is NULL; --", "", $string);
		$string = str_ireplace("is NULL; --", "", $string);
		$string = str_ireplace("LIKE '","", $string);
		$string = str_ireplace('LIKE "',"", $string);
		$string = str_ireplace("LIKE ´","", $string);
		$string = str_ireplace("OR 'a'='a","", $string);
		$string = str_ireplace('OR "a"="a"', "", $string);
		$string = str_ireplace("OR ´a´=´a", "", $string);
		$string = str_ireplace("OR ´a´=´a", "", $string);
		$string = str_ireplace("--","", $string);
		$string = str_ireplace("-","", $string);
		$string = str_ireplace("^", "", $string);
		$string = str_ireplace("[", "", $string);
		$string = str_ireplace("]", "", $string);
		$string = str_ireplace("==", "", $string);
		return $string;
	}

	function passGenerator($length=10)
	{
		$pass = "";
		$longitudPass = $length;
		$cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
		$longitudCadena = strlen($cadena);
		for($i=1; $i<=$longitudPass; $i++)
		{
			$pos = rand(0,$longitudCadena-1);
			$pass.= substr($cadena,$pos,1);
		}
		return $pass;
	}

	function token()
	{
		$r1 = bin2hex(random_bytes(10));
		$r2 = bin2hex(random_bytes(10));
		$r3 = bin2hex(random_bytes(10));
		$r4 = bin2hex(random_bytes(10));
		$token = $r1.'-'.$r2.'-'.$r3.'-'.$r4;
		return $token;
	}

	function formatMoney($cantidad){
		$cantidad = number_format($cantidad,0,SPD,SPM);
		return $cantidad;
	}

	
	function sistemas_comunes(): array
	{
			return [
					'Sistema Electrico' => [
						'Bateria',
						'Alternador',
						'Motor de Arranque',
						'Luces Medias',
						'Luces Altas',
						'Luces Bajas',
						'Direccionales Delanteras',
						'Direccionales Traseras',
						'Luces de Freno',
						'Luces de Retroceso',
						'Luces de Emergencia',
						'Placa Iluminada',
						'Luces Antiniebla Delanteras',
					],
					'Sistema de Liquidos'=>[
						'Nivel de Aceite de Motor',
						'Nivel de Liquido de Frenos',
						'Nivel de Agua de Radiador',
						'Nivel de Aceite Hidraulico',
						'Fugas de A.C.P.M',
						'Fugas de Agua',
						'Fugas de Aceite de Transmision',
						'Fugas de Aceite de Motor',
						'Fugas de Aceite de Caja',
						'Fugas de Liquido de Frenos'
					],
					'Sistema de Motor' => [
						'Nivel de Aceite',
						'Correas',
						'Freno de motor',
						'Mangueras y Conexiones',
						'Tapa Tanque de Agua ( Radiador )',
						'Ventilador',
						'Filtro de Aire',
						'Filtro de Aire Acondicionado',
						'Filtro de Combustible',
						'Fugas Visibles ( Combustible y Aceite )',
						'Radiador',
					],
					'Sistema de Frenos' => [
						'Frenos Delanteros',
						'Frenos Traseros',
						'Freno de Estacionamiento',
						'Pedal de Freno',
					],
					'Tablero de Indicadores' => [
						'Instrumentos',
						'Luces de Tablero',
						'Nivel de Combustible',
						'Odometro',
						'Pito',
						'Tacometro',
						'Velocimetro',
						'Indicador de Temperatura',
						'Indicador de Presion de Aceite',
						'Indicador de Presion de Aire'
					],
					'Sistema de Suspensión'=>[
						'Estado de la Dirección',
						'Estado de la Suspensión delantera',
						'Estado de la Suspensión trasera',
					],
					'Estado Llantas'=>[
						'Llantas Delanteras',
						'Llantas Traseras',
						'Llanta de Repuesto',
						'Presion de Aire',
						'Rines',
						'Esparragos',
						'Pernos de Seguridad'
					],
					'Seguridad Pasiva'=>[
						'Cinturones de Seguridad',
						'Airbags',
						'Chasis y Carroceria',
						'Cristales (Parabrisas y Ventanas)',
						'Apoyacabezas',
						'Espejo Retrovisor',
						'Espejos Laterales',
						'Aire Acondicionado',
					],
					'Equipo de Carretera' => [
						'Extintor',
						'Botiquin de Primeros Auxilios',
						'Triangulos de Seguridad',
						'Gato y Herramientas',
						'Chaleco Reflectivo',
					],
					'Botiquin de Emergencia'=>[
						'Vendas',
						'Antisepticos',
						'Analgesicos',
						'Guantes Desechables',
						'Tijeras',
						'Pinzas',
					],
					'Sistema de Documentos' =>[
						'Licencia de Conducción',
						'SOAT',
						'Revisión Tecnomecánica',
						'Tarjeta de Propiedad',
						'Póliza de Seguro',
						'Permisos Especiales ( Aplica )',
					],
					'SST ( Seguridad y Salud en el Trabajo )'=>[
						'Señalización de Seguridad',
						'Condiciones de Trabajo Seguro',
						'Elementos de Protección Personal (EPP)',
						'Limpieza y Orden del Vehículo',

					]
			];
	}

	function checklist_por_tipo_vehiculo(string $tipoVehiculo): array
	{
			$tipoVehiculo = strtolower(trim($tipoVehiculo));

			$porTipo = [

					'pesado' => [
							'Remolque' => [
									'Sistema de Frenos del Remolque',
									'Luces del Remolque',
									'Acople del Remolque',
									'Estado de la Carroceria del Remolque',
									'Estado de las Llantas del Remolque',
									'Estado Quinta Rueda',
									'Estado King Pin',
									'Estado Tornamesa',
									'Acople 7 Vias', 
									'Defensas Laterales'
							],
							'Diferenciales' => [
								'Chumaceras de Cardan',
								'Fugas de Aceite',
								'Estado de Sellos',
								'Ruidos y Vibraciones',
								'Crusetas',
								'Desgaste ( Virutas Metalicas )',
							]
					],

					'mixer' => [
						'Trompo Mezclador' => [
							'Fugas Motor Hidraulico',
							'Fugas Bomba Hidraulica',
							'Cardan',
							'Fugas Reductor Ruido',
							'Nivel de Aceite',
							'Filtro',
							'Conexiones (Tubos y Mangueras)',
							'Rodillos',
							'Guayas',
							'Embudo, Canales',
							'Velocidad de giro',
							'Tanque de Agua',
							'Limpieza General',
							'Recipiente Dosificador Aditivos',
						]
					],
					'maquinaria' => [
						'Sistema Maquina' => [
							'Borde de Ataque del Cucharon',
							'Brazos del Bastidor',
							'Cilindros Hidraulicos',
							'Conexiones Hidraulicas',
							'Transmisión Caja de Transferencia',
							'Peldanos y Agarraderas',
							'Aceite del Diferencial y Mando Final',
							'Ejes Mandos Finales Diferenciales'
						]
					]
			];

			return array_replace_recursive(
					sistemas_comunes(),
					$porTipo[$tipoVehiculo] ?? []
			);
	}

?>