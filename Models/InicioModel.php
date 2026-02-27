<?php 

    class InicioModel extends Pgsql
    {
        private $cliente;
        public function __construct()
        {
            parent::__construct();
        }
        // Method que permite realizar la peticion obteniendo los datos de los vehículos de acuerdo al usuario
        public function getDatosPlacas(int $cliente)
        {
            $this->cliente = $cliente;

            $query = "SELECT id_movil, placa FROM public.moviles WHERE (id_cliente = $this->cliente OR id_empresa = $this->cliente) AND estado = 1";
            $response = $this->select_all($query);
            return $response;
        }

        // Method que permite realizar la peticion obteniendo los datos de los conductores de acuerdo al usuario
        public function getDatosConductores(int $cliente)
        {
            $this->cliente = $cliente;
            $query_select = "SELECT dni, nombre FROM modulos.pre_reg_conductores WHERE idcliente = $this->cliente";
            // echo $query_select;
            $response = $this->select_all($query_select);
            return $response;
        }
        
    }

?>