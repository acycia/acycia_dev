<?php
//Llamada al modelo
require_once("Models/CcomprasEM.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oComprasEM();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras_em.php");*/

class ComprasEMController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;

	public function __CONSTRUCT(){
		$ordenc = new oComprasEM();
    }

    public function Index(){ 
    	$ordenc = new oComprasEM();//instanciamos la clase oComprasEM del Modelo CcomprasEM
    	self::CcomprasEM();
    }
 
    public function Mercancia(){ 
        $proveedores = new oComprasEM();//instanciamos la clase oComprasEM del Modelo CcomprasEM
        $insumo = new oComprasEM();
        $ordenc = new oComprasEM();
        $maquina = new oComprasEM();

        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        $this->maquina=$maquina->get_Maquina();
 
        self::CcomprasEM();
    }


    public function Menu(){ 
        $ordenc = new oComprasEM();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'view_submenu_compras.php';
        self::CcomprasEM($vista);
    }

    public function Crud(){
    	$proformas = new oComprasEM(); 
    	if(isset($_REQUEST['id'])){
            $proveedores = new oComprasEM();//instanciamos la clase oComprasEM del Modelo CcomprasEM
            $insumo = new oComprasEM(); 
            $maquina = new oComprasEM();
            $general = new oComprasEM();
            $factura = new oComprasEM();
            $proformasPrincipal = new oComprasEM();
            $proformas = new oComprasEM();
            $mercancia = new oComprasEM();
            $detalle = new oComprasEM();
            $embarque = new oComprasEM();

            $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
            $this->insumo=$insumo->get_Insumo();
            $this->maquina=$maquina->get_Maquina();
            $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='ENTRADA MERCANCIA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->general)
              $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='DETALLE EMBARQUE' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->general)
              $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='ENTRADA FACTURA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->general)
              $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='PROFORMA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            
            
            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA MERCANCIA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->proformasPrincipal)
            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='DETALLE EMBARQUE' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->proformasPrincipal)
            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->proformasPrincipal)
            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

    		$this->proformas=$proformas->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            $this->factura=$factura->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            $this->mercancia=$mercancia->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA MERCANCIA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            $this->detalle=$detalle->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='DETALLE EMBARQUE' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            $this->embarque=$embarque->Obtener(' tbl_proceso_compras pc',"pc.proceso='DETALLE EMBARQUE' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

         /*   echo '<pre>';
            var_dump($this->detalle);die;
            echo '<pre>';*/


    	}

        self::CcomprasEM();//le digo que muestre en vista edit
    }

    public function Guardar($vista=''){
     
            $directorio = ROOT."pdfprocesocompras/";
            $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
            $porciones = explode(".", $nombre);
            $adjunto = "ENM". $_REQUEST['factura'] . "." . $porciones[1];
            $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
     
            //$proformas = new oCompras();
            $this->proformas =  new oComprasEM(); 
            $this->proforma = $_REQUEST;

            if($nombre!=''){ 
            $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
            $this->proforma['adjunto']= $tieneadjunto1;  
            }else{
               $this->proforma['adjunto'] = $_REQUEST['userfile'];
            } 

            $this->proformas->Registrar("tbl_proceso_compras", "proforma,pedido,factura,proceso,proveedor,fecha,bodega,tipopedido,tipoinsumo,maquina,plazo,valorplazo,fecha_plazo,adjunto,declara,fecha_dec,trm,bl,fecha_bl,fecha_zar,fecha_eta,puerto_lleg,fleteseguro,num_contenedor,tam_contenedor,fob,valor_fact,usuario,estado",$_REQUEST['columna'],$_REQUEST['id'], $this->proforma);
            
            $this->proformas->RegistrarItems("tbl_proceso_compras_detalle", "proforma,pedido,factura,proceso,cantidad,medida,code,descripcion,moneda,precio,precio_total,incoterm,valoricot,estado,bodega ", $this->proforma); 

            header('Location:view_index.php?c=comprasEM&a=Crud&columna=factura&id='.$_REQUEST['factura']);  
    }


    public function Actualizar(){
       
           
             //$data = json_decode($_POST['datos']); 

             $this->proformas =  new oComprasEM(); 
             $this->proforma = $data; 
             
             $this->proformas->UpdateItems("tbl_proceso_compras_detalle", $_REQUEST['id'], $_REQUEST['valor'],$_REQUEST['columna'],$_REQUEST['proceso']); 
             
              
              header("Location:view_index.php?c=comprasEM&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
    }


    public function Eliminar(){
           

             $this->proformas =  new oComprasEM(); 
             $this->proforma = $_REQUEST;
             $this->proformas->Delete("tbl_proceso_compras_detalle", $_REQUEST['id'], $_REQUEST['columna'],$_REQUEST['proceso'],$_REQUEST['master']); 
             
              header("Location:view_index.php?c=comprasEM&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
    }
 

    public function CcomprasEM($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  
        }
        else{
    	  require_once("views/view_compras_em.php");
        }
    }
 



}



?>
