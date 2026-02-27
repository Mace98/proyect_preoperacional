<?php 
  headerAdmin($data); 
  

?>
   
    <main class="app-content">
      <div class="app-title">
        <div>
          <h1> <i class="fa fa-dashboard"></i> <?php echo $data['page_title'] ?> &nbsp;</h1>
        </div>
      </div>

      <div class="card">
        <div class="card-body">
          
          <!-- <form name="formpreo" id="formpreo" > -->
          <form id="formpreo" action="<?= base_url() ?>formulario" method="POST">
            <!-- Crear input invisible donde se almace los valores en texto de los campos seleccionados -->
             <input type="hidden" name="conductorText" id="conductorText">
             <input type="hidden" name="placaText" id="placaText">
            <!-- Informacion del Vehiculo -->
            <div class="row">
              <div class="col-sm-12 form-group">
                <label for="placa">Placa</label>
                <select name="placainicio" id="placainicio" class="form-control " required></select>
              </div>
            </div>
            <!-- Informacion del Odometro -->
            <div class="row">
              <div class="col-sm-12 form-group">
                <label for="odometro">Odometro</label>
                <input type="number" class="form-control form-control-sm" name="kmactual" id="kmactual" required autocomplete="off">
              </div>
            </div>
            <!-- Informacion del conductor -->
            <div class="row">
              <div class="col-sm-12 form-group">
                <label for="conductor">Conductor</label>
                <select name="conductor" id="conductor" class="form-control" required></select>
              </div>
            </div>
            <!-- Informacion de Tipo Vehiculo -->
            <div class="row">
              <div class="col-sm-12 form-group">
                <label for="tipovehiculo">Tipo Vehiculo</label>
                <select name="tipovehiculo" id="tipovehiculo" class="form-control ">
                  <option value="0">Seleccione Tipo</option>
                  <option value="liviano">Vehículo liviano</option>
                  <option value="personal">Transporte de personal</option>
                  <option value="pesado">Vehículo pesado</option>
                  <option value="maquinaria">Maquinaria pesada</option>
                  <option value="mixer">Equipo especial</option>
                </select>
              </div>
            </div>
            

            <div class="row">
              <div class="col-sm-4 form-group d-flex justify-content-end ">
                <!-- <label for="GENERAR"></label> -->
                <button type="submit" class="btn btn-form-control form-control-sm btn-primary ">GENERAR</button>
              </div>
            </div>

          </form>
        </div>
      </div>


      
    </main>

    <?php footerAdmin($data); ?>