<?php
//Llamada al modelo
require_once("Models/McomprasLQU.php");
include('funciones/adjuntar.php'); 

class ComprasLQUController{

	private $general;

	public function __CONSTRUCT(){
		$general = new oComprasLQU();
    }

    public function Index(){ 
    	$general = new oComprasLQU();//instanciamos la clase oComprasLQU del Modelo McomprasLQU
    	self::McomprasLQU();
    }
 
    public function Liquidacion(){ 
        $proformas = new oComprasLQU();
        self::McomprasLQU();
    }


    public function Menu(){ 
        $general = new oComprasLQU(); 
        $vista = 'view_submenu_compras.php';
        self::McomprasLQU($vista);
    }

    public function Crud(){
    	/*$proformas = new oComprasLQU(); 
    	if(isset($_REQUEST['id'])){
            $general = new oComprasLQU();
            $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='PROFORMA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            if(!$this->general)
              $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='ENTRADA FACTURA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
 
    	}*/

        self::McomprasLQU();//le digo que muestre en vista edit
    }

    public function Guardar($vista=''){

        $directorio = ROOT."pdfprocesocompras/";

        $this->proformas =  new oComprasLQU(); 
        $this->proforma = $_REQUEST; 

        $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
        $nombre1 = str_replace(' ', '', $_FILES['adjunto1']['name']);

        $extension = pathinfo($nombre, PATHINFO_EXTENSION);
        $extension1 = pathinfo($nombre1, PATHINFO_EXTENSION);

        $nombre = "Liquidacion_de_importacion" . "." . $extension;
        $nombre1 = "Control_de_operaciones" .  "." . $extension1;
       
        if($_FILES['adjunto']['name']!=''){
           if( ($extension=='xlsx' || $extension=='xls') ){
               $mensaje = "Se cargo el archivo de nombre: ". $nombre; 
               $this->proforma['adjunto'] = adjuntarArchivo('', $directorio, $nombre,$_FILES['adjunto']['tmp_name'],'NUEVOS'); 
               $mostrar = 1; 

           } else  {
               $mensaje = "No se pudo guardar el archivos Liquidacion no es Excel:  .xlsx o .xls";  
               $mostrar = 0; 
           }

        }
        
        if($_FILES['adjunto1']['name']!=''){
           if( ($extension1=='xlsx' || $extension1=='xls') ){ 
               $mensaje = "Se cargo el archivo de nombre: " . $nombre1;
               $this->proforma['adjunto1'] = adjuntarArchivo('', $directorio, $nombre1,$_FILES['adjunto1']['tmp_name'],'NUEVOS');
               $mostrar = 1; 
           } else  {
               $mensaje = "No se pudo guardar el archivos Control no es Excel:  .xlsx o .xls";  
               $mostrar = 0; 
           }
        }
            //$this->proformas->Registrar("tbl_proceso_compras", "proforma,pedido,factura,proceso,proveedor,fecha,bodega,tipopedido,tipoinsumo,maquina,plazo,valorplazo,fecha_plazo,adjunto,bl,fecha_bl,fecha_zar,fecha_eta,puerto_lleg,usuario,estado", $this->proforma);
            //$this->proformas->RegistrarItems("tbl_proceso_compras_detalle", "proforma,pedido,factura,proceso,cantidad,medida,code,descripcion,moneda,precio,precio_total,incoterm,valoricot,estado ", $this->proforma); 

            header("Location:view_index.php?c=comprasLQU&a=Crud&columna=proforma&id=".$mensaje."&mostrar=".$mostrar ); 
    }

 

    public function McomprasLQU($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  
        }
        else{
    	  require_once("views/view_compras_lqu.php");
        }
    }
 
}



?>
