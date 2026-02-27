   <script type="text/javascript">
      const base_url = "<?= base_url(); ?>";
   </script>
 <!-- Essential javascripts for application to work-->
    <script src="<?= media(); ?>/js/plugins/jquery-3.3.1.min.js"></script>
    <script src="<?= media(); ?>/js/plugins/moment.min.js"></script>
    <script src="<?= media(); ?>/js/plugins/popper.min.js"></script>
    <script src="<?= media(); ?>/js/plugins/bootstrap.min.js"></script>
    
    <script src="<?= media(); ?>/js/plugins/main.js"></script>

    <script src="<?= media(); ?>/js/plugins/bootstrap-3.3.7.min.js"></script>
    <script src="<?= media(); ?>/js/plugins/bootstrap-datetimepicker.min.js"></script>
 
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?= media(); ?>/js/plugins/pace.min.js"></script>

    <script type="text/javascript" src="<?= media(); ?>/js/plugins/sweetalert.min.js"></script>
   
    <script type="text/javascript" src="<?= media(); ?>/js/plugins/bootstrap-select.min.js"></script>

        <!-- Anexar script para siganture  -->
      <script type="text/javascript" src="<?= media(); ?>/js/plugins/signature_pad.umd.min.js"></script>

     <script type="text/javascript" src="<?= media() ?>/js/local/<?= $data['data_functions_js']; ?>"></script>
     <script type="text/javascript" src="<?= media() ?>/js/<?= $data['app']; ?>"></script>
     
   
 
  </body>
</html>