<?php
//Llamada al modelo
require_once("Models/McomprasEX.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oComprasEX();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras_em.php");*/

class ComprasEXController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;
	public function __CONSTRUCT(){
		$ordenc = new oComprasEX();
    }

    public function Index(){ 
    	$ordenc = new oComprasEX();//instanciamos la clase oComprasEX del Modelo CcomprasEX
    	self::CcomprasEX();
    }
 
    public function Exterior(){ 
        $proveedores = new oComprasEX();//instanciamos la clase oComprasEX del Modelo CcomprasEX
        $insumo = new oComprasEX();
        $ordenc = new oComprasEX();
        $maquina = new oComprasEX();

        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        $this->maquina=$maquina->get_Maquina();
 
        self::CcomprasEX();
    }


    public function Menu(){ 
        $ordenc = new oComprasEX();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'view_compras_ex.php';
        self::CcomprasEX($vista);
    }

    public function Crud(){

    	$proformas = new oComprasEX(); 
    	if(isset($_REQUEST['id'])){
 
            $general = new oComprasEX(); 
            $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='EXPO' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']); 
  
    	}

        self::CcomprasEX();//le digo que muestre en vista edit
    }


        public function Guardar($vista=''){
     
            $directorio = ROOT."pdfprocesocompras/";
            $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
                $porciones = explode(".", $nombre);
            $adjunto = "EXP". $_REQUEST['factura'] . "." . $porciones[1];
            $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
     
            //$proformas = new oCompras();
            $this->proformas =  new oComprasEX(); 
            $this->proforma = $_REQUEST;
            $this->proforma['adjunto']= $tieneadjunto1; 

            $this->proformas->Registrar("tbl_proceso_compras","factura,proceso,proveedor,fecha,tipopedido,bl,fecha_factura,dex,fecha_dex,pago,fecha_pago,fleteseguro,comentarios,valor_fact,adjunto,usuario,estado", $this->proforma); 
            $mostrar = 1;
            header('Location:view_index.php?c=comprasEX&a=Crud&columna=factura&id='.$_REQUEST['factura']."&mostrar=".$mostrar);  
        }
 

    public function CcomprasEX($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  
        }
        else{
    	  require_once("views/view_compras_ex.php");
        }
    }
 

}



?>
