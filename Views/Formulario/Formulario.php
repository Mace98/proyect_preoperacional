<!-- Informacion del encabezado -->
<?php
    headerAdmin($data);
?>

<!-- Informacionn del cuerpo del proyecto -->

<main class="app-content">
    <div class="card">
        <div class="card-body">
            <form name="formFormulario" id="formFormulario" enctype="multipart/form-data">
              <input type="hidden" name="tipovehiculo" value="<?= $data['tipovehiculo'] ?>">
              <input type="hidden" name="conductor" value="<?= $data['conductor'] ?>">
              <input type="hidden" name="placa" value="<?= $data['placa'] ?>">
              <input type="hidden" name="kmactual" value="<?= $data['kmactual'] ?>">

              
             <h3>Formulario Pre Operacional</h3>
             <!-- Informacion del formulario -->
                <div class="row">
                    <div class="col-xs-6 col-sm-6 form-group">
                        <label for="placa">Placa</label>
                        <input type="text" class="form-control form-control-sm" name="placaText" id="placaText"  value="<?= $data['placaText'] ?>" readonly>
                    </div>
                    <div class="col-xs-6 col-sm-6 form-group">
                      <label for="conductor">Conductor</label>
                      <input type="text" class="form-control form-control-sm" name="conductorText" id="conductorText" value="<?= $data['conductorText'] ?>" readonly>
                    </div>
                </div>
                <!-- Informacion del formulario -->
                <div class="row">
                  <div class="col-xs-6 col-sm-6 form-group">
                    <label for="Odometro">Km Actual</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $data['kmactual'] ?>" readonly>
                  </div>
                  <div class="col-xs-6 col-sm-6 form-group">
                    <label for="tipo">Tipo Vehículo</label>
                    <input type="text" class="form-control form-control-sm" value="<?= $data['tipovehiculo'] ?>" readonly>
                  </div>
                </div>

                <!-- Formulario inicial teniendo en cuenta las especificaciones -->
                 <div class="row">
                  <div class="col-xs-12 col-sm-12">
                   
                   <?php foreach ($data['checklistSeguridad'] as $sistema => $items): ?>
                    <div class="sistema-bloque">
                      <input type="hidden" name="porcentaje_sistema[<?= $sistema ?>]" class="input-score" value="0">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th><?= $sistema ?></th>
                                    <th class="text-center">B</th>
                                    <th class="text-center">R</th>
                                    <th class="text-center">M</th>
                                    <th class="text-center">N/A</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $i => $item): ?>
                                    <tr>
                                        <td><?= $item ?></td>
                                        <td class="text-center">
                                            <input type="radio" class="check-item" name="check[<?= $sistema ?>][<?= $i ?>]" value="B" data-peso="1" required>
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" class="check-item" name="check[<?= $sistema ?>][<?= $i ?>]" value="R" data-peso="0.5">
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" class="check-item" name="check[<?= $sistema ?>][<?= $i ?>]" value="M" data-peso="0">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary btn-foto d-none">
                                                📷
                                            </button>

                                            <input type="file"
                                                  name="foto_novedad[<?= $sistema ?>][<?= $i ?>]"
                                                  accept="image/*"
                                                  capture="environment"
                                                  class="input-foto d-none">
                                            
                                              <!-- Agregar input que me permite saber el nombre del item -->
                                              <input type="hidden" name="item_nombre[<?= $sistema ?>][<?= $i ?>]" value="<?= $item ?>">
                                            
                                        </td>
                                        <td class="text-center">
                                            <input type="radio" class="check-item" name="check[<?= $sistema ?>][<?= $i ?>]" value="NA" data-peso="omitir">
                                        </td>
                                        
                                    </tr>
                                    
                                    
                                      <!-- Informacion que permite capturar la informacion de las variables -->
                                        <input type="hidden" name="items[<?= $sistema ?>][<?= $i ?>]" value="<?= htmlspecialchars($item) ?>">
                                        <input type="hidden" name="valores_check[<?= $sistema ?>][<?= $i ?>]" value="" class="valor-check-hidden" data-sistema="<?= $sistema ?>" data-index="<?= $i ?>">
                
                                <?php endforeach; ?>
                                
                            </tbody>
                            <input type="hidden" name="porcentaje_total" id="porcentaje_total">
                        </table>
                        <div class="alert alert-info">
                            Puntaje de <?= $sistema ?>: <strong class="score-display">0</strong>%
                            <small>(Excluyendo N/A)</small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                 </div>
                 <!-- Agregat item para las observaciones -->
                  <div class="row">
                    <div class="col-xs-12 col-sm-12 form-group">
                      <label for="observaciones">Observaciones</label>
                      <textarea name="observaciones" id="observaciones" class="form-control" rows="4" placeholder="Escriba las observaciones aqui..."></textarea>
                    </div>
                  </div>

                  <!-- Agregar el campo para la firma del documento -->
                   <div class="row">
                    <div class="col-xs-12 col-sm-12 form-group">
                      <canvas id="signature-pad"  style="border:1px solid #ccc; width:100%; height:200px;"></canvas>
                      <input type="hidden" name="firma" id="firma">
                      <button type="button" id="clear">Limpiar</button>             
                    </div>
                   </div>
                  
                  <div class="row">
                    <div class="col-sm-4 form-group d-flex justify-content-end ">
                      <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                  </div>
            </form>
        </div>
    </div>
</main>


<!-- Informacion del pie de pagina -->
 <?php
    footerAdmin($data);
?>



