<?php

  class FormularioModel extends Pgsql
  {

      public $placa;
      public $id_conductor;
      public $movil;
      public $conductor;
      public $kmactual;
      public $tipovehiculo;
      public $porcentajesLimpios;
      public $detalles;
      public $fecha;
      public $items;
      public $valoresCheck;
      public $novedad;
      public $firmaDigital;
      public $id_inspeccion;
      public $ruta_pdf;
      public $usuario_id;
      public $ruta_excel;
      public $ruta_fotos;


      public function __construct()
      {
          parent::__construct();
      }

        // Obtener una inspección completa por id (cabecera, porcentajes, detalles)
        public function get_inspeccion_by_id($id)
        {
          $id = intval($id);

          $query_base = "SELECT id_inspeccion, vehiculo_id, conductor_id, kilometraje, tipo_vehiculo, fecha_registro, novedades, firma_path FROM modulos.pre_inspecciones WHERE id_inspeccion = $id";
          $base = $this->select($query_base);

          if (!$base) {
            return [ 'status' => false, 'msg' => 'Inspección no encontrada' ];
          }

          $query_porc = "SELECT nombre_sistema, puntaje_obtenido FROM modulos.pre_inspeccion_porcentajes WHERE id_inspeccion = $id";
          $porcentajes = $this->select_all($query_porc);

          $query_det = "SELECT sistema_pertenece, item_nombre, estado_valor FROM modulos.pre_inspeccion_detalles WHERE id_inspeccion = $id";
          $detalles = $this->select_all($query_det);

          // Consultar la ruta de las fotos de novedades asociadas a esta inspección
          $query_fotos = "SELECT sistema, img_path FROM modulos.pre_fotos_novedad WHERE id_inspeccion = $id";
          $fotosNovedad = $this->select_all($query_fotos);


          $data = [
            'base' => $base,
            'porcentajes' => $porcentajes,
            'detalles' => $detalles,
            'fotosNovedad' => $fotosNovedad
          ];

          return [ 'status' => true, 'data' => $data ];
        }

      // Metodo para registrar la informacion base del formulario
      public function registrar_formulario_base($movil, $placa, $conductor, $conductorText, $kmactual, $tipovehiculo, $porcentajesLimpios, $detalles, $novedad, $firmaDigital, $rutasFotos, $id_usuario)
      {
          // 1. Asignación de propiedades
          $this->movil = $movil; 
          $this->placa = $placa;
          $this->conductor = $conductor;
          $this->kmactual = $kmactual;
          $this->tipovehiculo = $tipovehiculo;
          $this->porcentajesLimpios = $porcentajesLimpios;
          $this->detalles = $detalles;
          $this->novedad = $novedad;
          $this->firmaDigital = $firmaDigital;
          // dep($this->detalles);
          $this->fecha = date('Y-m-d H:i:s');
          $this->usuario_id = $id_usuario;
          $this->ruta_fotos = $rutasFotos;

          // 2. Insertar Cabecera (PostgreSQL RETURNING)
          $query_base = "INSERT INTO modulos.pre_inspecciones(vehiculo_id, conductor_id, kilometraje, tipo_vehiculo, fecha_registro, novedades, firma_path, id_cliente) 
                        VALUES (?,?,?,?,?,?,?,?) RETURNING id_inspeccion";
          
          $params_base = [ $this->placa, $this->conductor, (int)$this->kmactual, $this->tipovehiculo, $this->fecha, $this->novedad, $this->firmaDigital, $this->usuario_id ];
        //   Validar captura de los datos a insertar
        // dep($params_base);
          $idInspeccion = $this->insert($query_base, $params_base);

          // 3. Validar inserción inicial
          if( $idInspeccion )
          {
              // 4. Insertar los porcentajes (Resumen de bloques)
              foreach ($this->porcentajesLimpios as $sistema => $valor) {
                  $query_porc = "INSERT INTO modulos.pre_inspeccion_porcentajes (id_inspeccion, nombre_sistema, puntaje_obtenido) 
                                VALUES (?,?,?)";
                  $params_porc = [ $idInspeccion, $sistema, (float)$valor ];
                    // Validar captura de los datos a insertar
                  
                  $this->insert($query_porc, $params_porc);
              }

              // 5. Insertar los detalles (Estado de cada ítem individual)
              foreach ($detalles as $detalle) {
                  $informacion_sistema = $detalle['sistema'];
                  $query_detalle = "INSERT INTO modulos.pre_inspeccion_detalles (id_inspeccion, sistema_pertenece, item_nombre, estado_valor) 
                                    VALUES (?,?,?,?)";
                  $params_detalle = [ $idInspeccion, $detalle['sistema'], $detalle['item'], $detalle['valor'] ];
                  $this->insert($query_detalle, $params_detalle);
              }

              // 5.1 Insertar las rutas de las fotos de novedades (si existen)
              if (!empty($this->ruta_fotos)) {
                  foreach ($this->ruta_fotos as $foto) {
                      $query_foto = "INSERT INTO modulos.pre_fotos_novedad (id_inspeccion, sistema, img_path, fecha_registro) VALUES (?, ?, ?, ?)";
                      $params_foto = [ $idInspeccion, $informacion_sistema, $foto, date('Y-m-d H:i:s') ];
                      $this->insert($query_foto, $params_foto);
                  }
              }

              // 6. RETORNO FINAL (Solo después de haber guardado TODO)
              $return =  [ 'status' => true, 'id_inspeccion' => $idInspeccion ];
          }
          else
          {
             $return =  [ 'status' => false, 'msg' => 'Error al registrar la inspección base.' ];
          }

          return $return;
      }

      // Metodo para actualizar la ruta del pdf generado en la inspeccion
      public function actualizar_ruta_pdf($id, $rutaPdf)
      {
          // Variables 
          $this->id_inspeccion = $id;
          $this->ruta_pdf = $rutaPdf;
          
          $sql = "UPDATE modulos.pre_inspecciones SET pdf_path = ? WHERE id_inspeccion = ?";
          $arrData = [$rutaPdf, $id];
          return $this->update($sql, $arrData);
      }

      // Metodo que permite actualizar la ruta del excel generado en la inspeccion
      public function actualizar_ruta_excel($id, $rutaExcel)
      {
          // Variables 
          $this->id_inspeccion = $id;
          $this->ruta_excel = $rutaExcel;
          
          $sql = "UPDATE modulos.pre_inspecciones SET excel_path = ? WHERE id_inspeccion = ?";
          $arrData = [$rutaExcel, $id];
          return $this->update($sql, $arrData);
      } 


  }

?>