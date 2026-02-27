<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Favicon -->
    <link rel="icon" href="<?= media() ?>/img/favicon.ico" type="image/x-icon">

    <title><?= $data['page_tag'] ?></title>
    
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=no, height=device-height, viewport-fit=cover"> -->
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1 height=device-height, viewport-fit=cover" >
    <meta name="description" content="PWA que permite a los usuarios de STAR SEGUIMIENTO Y CONTROL realizar los reportes de pre-operacionales de los vehículos.">
    <meta name="keywords" content="PWA, Monitoreo, Seguimiento GPS vehículos">
    <meta name="author" content="STAR SEGUIMIENTO Y CONTROL">
    <meta name="theme-color" content="rgb(255, 255, 255)">

    <meta name="mobile-web-app-capable" content="yes">

    <!-- CSS -->
   <link rel="stylesheet" href="<?= media() ?>/css/theme/style.css">
   <link rel="stylesheet" href="<?= media() ?>/css/theme/custom.min.css">

   <link rel="manifest" href="https://starseguimiento.co/proyect_preoperacional/manifest.json">

</head>

  <body>
    <div class="auth-wrapper">
      <div class="auth-content text-center">
        <img src="assets/img/logo-letras negras.png" alt="" class="img-fluid mb-4">
          <div class="card borderless">
            <div class="row align-items-center ">
              <div class="col-md-12">
                <form name="form-login" id="form-login">
                <div id="mensaje"></div>
                  <!-- <input type="hidden" class="form-control" id="infoToken" name="infoToken" > -->
                  
                  <div class="card-body">
                    <h4 class="mb-3 f-w-400">Iniciar Sesión</h4>
                    <hr>
                    <div class="form-group mb-3">
                      <input type="text" class="form-control" id="user-info" name="user-info" autofocus autocomplete="off" placeholder="Usuario">
                    </div>
                    <div class="form-group mb-4 position-relative">
                      <input type="password" class="form-control" id="pass-info" name="pass-info" autocomplete="off" placeholder="Contraseña">
                      <button type="button" class="btn-eye" onclick="togglePasswordVisibility()" aria-label="Mostrar/Ocultar Contraseña">
                        <i id="eye-icon" class="fa fa-eye"></i>
                      </button>
                    </div>
                   
                    <br>
                    <button class="btn btn-primary">Iniciar Sesión</button>
                    
                </form>
                
              </div>
            </div>
          </div>
          <button id="btnInstalar" style="display:none;" class="btn btn-info">Instalar aplicación</button>
    </div>
  </div>
   
  

  
  
  </body>

  <script>
    const base_url = "<?= base_url(); ?>";
  </script>

  <!-- Script STAR -->
  <script src="<?= media() ?>/js/local/<?= $data['data_functions_js'] ?>" defer></script>
  <script src="<?= media() ?>/js/<?= $data['app'] ?>" defer></script>
 
 

</html>

