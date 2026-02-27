
<?php
// <!-- Clase que permite visualizar la informacion de los tipos de formularios -->
// Requerir el autoload de Dompdf (asegúrate de haber ejecutado `composer require dompdf/dompdf`)


	use Dompdf\Dompdf;
	use Dompdf\Options;
	// use PhpOffice\PhpSpreadsheet\Spreadsheet;
	// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

	class Formulario extends Controllers
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

        // Method que permite visualizar la informacion del formulario
        public function formulario()
				{
						$tipoVehiculo = isset($_POST['tipovehiculo']) ? $_POST['tipovehiculo'] : null;

						$data = [		
									'page_tag'   => "Formulario",
									'page_title' => "Pre-Operacional",
									'page_name'  => "Formulario",
									'placa'      => isset($_POST['placainicio']) ? $_POST['placainicio'] : null,
									'placaText'  => isset($_POST['placaText']) ? $_POST['placaText'] : null,
									'conductor'  => isset($_POST['conductor']) ? $_POST['conductor'] : null,
									'conductorText' => isset($_POST['conductorText']) ? $_POST['conductorText'] : null,
									'kmactual'   => isset($_POST['kmactual']) ? $_POST['kmactual'] : null,
									'tipovehiculo' => $tipoVehiculo,
									'checklistSeguridad' => checklist_por_tipo_vehiculo($tipoVehiculo),
									'data_functions_js' => "function_formulario.js",
									'app' => "app.js"
						];

						$this->views->getView($this, "formulario", $data);
				}

				
				// Permite captural la informacion base del formulario
				public function formulario_base()
				{
						if ($_SERVER['REQUEST_METHOD'] === 'POST') {

								// --- capturas (las dejo igual, están bien) ---
								$placa = $_POST['placa'] ?? "";
								$placaText = $_POST['placaText'] ?? "";
								$conductor = $_POST['conductor'] ?? "";
								$conductorText = $_POST['conductorText'] ?? "";
								$kmactual = $_POST['kmactual'] ?? "";
								$tipovehiculo = $_POST['tipovehiculo'] ?? null;
								$novedad = $_POST['observaciones'] ?? "";
								// 
								
								// Validar que la imagen tomada por el dispositivo este presente y sea una imagen valida
								
								// Anexar la informacion de la firma digital 
								// ============== START INFORMACION DE LA FIRMA ===================================
								$firmaDigital = $_POST['firma'] ?? null;

								if (empty($firmaDigital)) {
										echo json_encode([
												'status' => false,
												'msg' => 'La firma es obligatoria'
										]);
										return;
								}

								$firmaDigital = $_POST['firma'];

								$firmaBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $firmaDigital);
								$firmaBase64 = str_replace(' ', '+', $firmaBase64);
								$firmaBinaria = base64_decode($firmaBase64, true);

								if ($firmaBinaria === false) {
										throw new Exception('Firma inválida');
								}

								$directorioFirmas = 'uploads/firmas/';
								if (!is_dir($directorioFirmas)) {
										mkdir($directorioFirmas, 0777, true);
								}

								$nombreFirma = 'firma_' . time() . '.png';
								$rutaFirma = $directorioFirmas . $nombreFirma;

								file_put_contents($rutaFirma, $firmaBinaria);
								// =============== END INFORMACION DE LA FIRMA ===================================

								// =============== START PORCENTAJES ====================================

								$porcentajesSistemas = $_POST['porcentaje_sistema'] ?? [];
								$detalles = $_POST['check'] ?? [];
								$items = $_POST['items'] ?? [];

								$checkDetalles = [];
								foreach ($detalles as $sistema => $checks) {
										foreach ($checks as $index => $valor) {
												$checkDetalles[] = [
														"sistema" => $sistema,
														"item" => $items[$sistema][$index] ?? 'Item desconocido',
														"valor" => $valor
												];
										}
								}

								$porcentajesLimpios = [];
								foreach ($porcentajesSistemas as $sistema => $valor) {
										$porcentajesLimpios[strClean($sistema)] = floatval($valor);
								}
								// ==================== END PORCENTAJES =======================================================

								// ============== START FOTOS DE NOVEDADES ====================================
								// Validar la informacion de las fotos de novedades
								$fotosNovedades = $_FILES['foto_novedad'] ?? null;
								$rutasFotos = [];
								
								if( isset($fotosNovedades) ) {
									// Ubicacion para guardar las fotos de novedades
									$carpeta = 'uploads/novedades/';

									if(!file_exists($carpeta)){
											mkdir($carpeta, 0777, true);
									}

									foreach($_FILES['foto_novedad']['tmp_name'] as $sistema => $items) {
										foreach( $items as $index => $tmp_name ) {
											if(isset($_POST['check'][$sistema][$index]) && $_POST['check'][$sistema][$index] === "M"	&& !empty($tmp_name)){
													
												$itemNombre = $_POST['item_nombre'][$sistema][$index];
								
												$extension = pathinfo(
														$_FILES['foto_novedad']['name'][$sistema][$index],
														PATHINFO_EXTENSION
												);

												$nombreArchivo = time() . "_" . $sistema . "_" . $itemNombre . "." . $extension;
												$rutaFinal = $carpeta . $nombreArchivo;			
												
												

												if (move_uploaded_file($tmp_name, $rutaFinal)) {
														$rutasFotos[] = $rutaFinal;
												}
											}
										}
									}	
								}

								// ================ END FOTOS DE NOVEDADES ====================================
								// ================ START REGISTRO DE INFORMACION EN LA BASE DE DATOS ============================

								$arr = $this->model->registrar_formulario_base(
										$placa,
										$placaText,
										$conductor,
										$conductorText,
										$kmactual,
										$tipovehiculo,
										$porcentajesLimpios,
										$checkDetalles,
										$novedad,
										$rutaFirma,
										$rutasFotos,
										$_SESSION['id']
								);

								if ($arr['status']) {

										// generar PDF SOLO guardando
										$rutaPdf = $this->generar_pdf($arr['id_inspeccion'], false);
										$this->model->actualizar_ruta_pdf($arr['id_inspeccion'], $rutaPdf);

										// Ruta que permite generar el excel 
										$rutaexcel = $this->generar_excel($arr['id_inspeccion']);
										$this->model->actualizar_ruta_excel($arr['id_inspeccion'], $rutaexcel);


										echo json_encode([
												'status' => true,
												'id_inspeccion' => $arr['id_inspeccion'],
												'pdf_url' => base_url() . $rutaPdf,
												'msg' => 'Formulario registrado y PDF generado'
										], JSON_UNESCAPED_UNICODE);

								} else {
										echo json_encode([
												'status' => false,
												'msg' => 'Error al registrar formulario'
										], JSON_UNESCAPED_UNICODE);
								}
						}

						// =============== END REGISTRO DE INFORMACION EN LA BASE DE DATOS ============================
				}

				// Método para generar el PDF del formulario
				public function generar_pdf($id_inspeccion, $mostrar = true)
				{
						$id = intval($id_inspeccion);

						$result = $this->model->get_inspeccion_by_id($id);
						if (!$result['status']) {
								return false;
						}

						$data_pdf = $result['data'];

						ob_start();
						require_once(dirname(__DIR__) . '/Views/Formulario/pdf_template.php');
						$html = ob_get_clean();

						require_once(dirname(__DIR__) . '/vendor/autoload.php');

						$dompdf = new \Dompdf\Dompdf();
						$options = new Options();
						$options->set('isRemoteEnabled', true);
						$options->set('isHtml5ParserEnabled', true);
						$options->set('chroot', $_SERVER['DOCUMENT_ROOT']);

						$dompdf = new Dompdf($options);

						$dompdf->loadHtml($html);
						$dompdf->setPaper('A4', 'portrait');
						$dompdf->render();

						$dir = dirname(__DIR__) . '/Uploads/pdfs';
						if (!is_dir($dir)) {
								mkdir($dir, 0777, true);
						}

						$filename = "inspeccion_{$id}.pdf";
						$fullpath = $dir . '/' . $filename;

						$relativePath = 'Uploads/pdfs/' . $filename;

						file_put_contents($fullpath, $dompdf->output());

						if ($mostrar) {
								$dompdf->stream($filename, ["Attachment" => 0]);
								exit;
						}

						return $relativePath;

				}

				// Metodo que permite generar archivo en excel del formulario
				public function generar_excel($id_inspeccion)
				{
						$id = intval($id_inspeccion);

						$result = $this->model->get_inspeccion_by_id($id);
						if (!$result['status']) {
								return false;
						}

						$data_excel = $result['data'];
						// dep($data_excel);

						require_once(dirname(__DIR__) . '/vendor/autoload.php');

						$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
						$sheet = $spreadsheet->getActiveSheet();

						// 🔹 Título
						$sheet->setCellValue('A1', 'PREOPERACIONAL VEHICULAR');
						$sheet->mergeCells('A1:C1');
						$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

						// 🔹 Información general
						$sheet->setCellValue('A3', 'INSPECTOR:');
						$sheet->setCellValue('B3', $data_excel['base']['conductor_id']);

						$sheet->setCellValue('A4', 'FECHA:');
						$sheet->setCellValue('B4', $data_excel['base']['fecha_registro']);

						$sheet->setCellValue('A6', 'PLACA:');
						$sheet->setCellValue('B6', $data_excel['base']['vehiculo_id']);

						$sheet->setCellValue('A7', 'KILOMETRAJE:');
						$sheet->setCellValue('B7', $data_excel['base']['kilometraje']);

						$sheet->setCellValue('A8', 'TIPO:');
						$sheet->setCellValue('B8', $data_excel['base']['tipo_vehiculo']);

						// Aplicar negrilla a la información general
						foreach (range(3, 6) as $row) {
								$sheet->getStyle("A$row")->getFont()->setBold(true);
								// Centrar solo la column B
								$sheet->getStyle("B$row")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						}

						// 🔹 Tabla encabezado
						$fila = 9;

						// Porcentajes por sistemas
						$sheet->setCellValue('A'.$fila, 'SISTEMA');
						$sheet->setCellValue('B'.$fila, 'PUNTAJE OBTENIDO');
						$sheet->getStyle("A$fila:B$fila")->getFont()->setBold(true);

						// Detalles de los porcentajes
						$fila++;
						foreach ($data_excel['porcentajes'] as $p) {
								$sheet->setCellValue('A'.$fila, $p['nombre_sistema']);
								$sheet->setCellValue('B'.$fila, $p['puntaje_obtenido']);
								$fila++;
						}

						// Tabla detalles de items
						$fila += 2; // Dejar un espacio

						$sheet->setCellValue('A'.$fila, 'SISTEMA');
						$sheet->setCellValue('B'.$fila, 'NOMBRE');
						$sheet->setCellValue('C'.$fila, 'ESTADO');
						$sheet->getStyle("A$fila:C$fila")->getFont()->setBold(true);

						$fila++;

						// 🔹 Detalle dinámico
						foreach ($data_excel['detalles'] as $item) {
								$sheet->setCellValue('A'.$fila, $item['sistema_pertenece']);
								$sheet->setCellValue('B'.$fila, $item['item_nombre']);
								$sheet->setCellValue('C'.$fila, $item['estado_valor']);
								$fila++;
						}

						// Dejar espacio para las novedades
						$fila += 2;
						$sheet->setCellValue('A'.$fila, 'NOVEDADES:');
						$sheet->mergeCells("A$fila:C$fila");
						$sheet->getStyle("A$fila")->getFont()->setBold(true);

						$fila++;
						foreach ($data_excel["base"] as $key => $value) {
								if ($key === 'novedades') {
										$sheet->setCellValue('A'.$fila, $value);
										$sheet->mergeCells("A$fila:C$fila");
										break;
								}
						}

						// Dejar espacio para la firma digital
						$fila += 4;
						$sheet->setCellValue('A'.$fila, 'FIRMA DIGITAL:');
						$sheet->getStyle("A$fila")->getFont()->setBold(true);

						// Insertar la imagen de la firma digital
						$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
						$drawing->setName('Firma Digital');
						$drawing->setDescription('Firma Digital');
						// Tener en cuenta que la ruta de cambiar al momento de subir a produccion
						$drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/proyect_preoperacional/' . $data_excel['base']['firma_path']);
						// $drawing->setPath($_SERVER['DOCUMENT_ROOT'] . '/demos/proyect_preoperacional/' . $data_excel['base']['firma_path']);

						$drawing->setCoordinates('A'.($fila+1)); // Colocar la imagen en la fila siguiente a la firma
						$drawing->setWorksheet($sheet);


						foreach (range('A','C') as $col) {
								$sheet->getColumnDimension($col)->setAutoSize(true);
						}

						// 🔹 Guardar archivo
						$dir = dirname(__DIR__) . '/Uploads/excel';
						if (!is_dir($dir)) {
								mkdir($dir, 0777, true);
						}

						$filename = "inspeccion_{$id}.xlsx";
						$fullpath = $dir . '/' . $filename;

						$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
						$writer->save($fullpath);

						$relativePath = 'Uploads/excel/' . $filename;

						return $relativePath;
				}
				

    }

?>