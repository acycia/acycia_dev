<?php
//Llamada al modelo
require_once("Models/CcomprasFA.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oComprasFA();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras_em.php");*/

class ComprasFAController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;
	public function __CONSTRUCT(){
		$ordenc = new oComprasFA();
    }

    public function Index(){ 
    	$ordenc = new oComprasFA();//instanciamos la clase oComprasFA del Modelo CcomprasFA
    	self::CcomprasFA();
    }
 
    public function Factura(){ 
        $proveedores = new oComprasFA();//instanciamos la clase oComprasFA del Modelo CcomprasFA
        $insumo = new oComprasFA();
        $ordenc = new oComprasFA();
        $maquina = new oComprasFA();

        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        $this->maquina=$maquina->get_Maquina();
 
        self::CcomprasFA();
    }


    public function Menu(){ 
        $ordenc = new oComprasFA();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'view_submenu_compras.php';
        self::CcomprasFA($vista);
    }

    public function Crud(){

    	$proformas = new oComprasFA(); 
    	if(isset($_REQUEST['id'])){
            $proveedores = new oComprasFA();//instanciamos la clase oComprasFA del Modelo CcomprasFA
            $insumo = new oComprasFA(); 
            $maquina = new oComprasFA();
            $general = new oComprasFA();
            $proformasPrincipal = new oComprasFA();
            $proformas = new oComprasFA();
            $factura = new oComprasFA();
            $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
            $this->insumo=$insumo->get_Insumo();
            $this->maquina=$maquina->get_Maquina();
            $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='ENTRADA FACTURA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            if(!$this->general)
              $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='PROFORMA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            
            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->proformasPrincipal)
            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
              
            
    		$this->proformas=$proformas->Obtener(' tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma',"pc.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            $this->factura=$factura->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
    	}

        self::CcomprasFA();//le digo que muestre en vista edit
    }


        public function Guardar($vista=''){
     
            $directorio = ROOT."pdfprocesocompras/";
            $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
            $porciones = explode(".", $nombre);
            $adjunto = "FAC". $_REQUEST['factura'] . "." . $porciones[1];

            //$proformas = new oCompras();
            $this->proformas =  new oComprasFA(); 
            $this->proforma = $_REQUEST;

            if($nombre!=''){ 
            $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
            $this->proforma['adjunto']= $tieneadjunto1;  
            }else{
               $this->proforma['adjunto'] = $_REQUEST['userfile'];
            }
 
            $this->proformas->Registrar("tbl_proceso_compras","proforma,pedido,factura,proceso,proveedor,fecha,bodega,tipopedido,tipoinsumo,maquina,plazo,valorplazo,fecha_plazo,adjunto,usuario,estado",$_REQUEST['columna'],$_REQUEST['id'], $this->proforma);
            $this->proformas->RegistrarItems("tbl_proceso_compras_detalle", "proforma,pedido,factura,proceso,cantidad,medida,code,descripcion,moneda,precio,precio_total,incoterm,valoricot,estado,bodega",$this->proforma); 
            header('Location:view_index.php?c=comprasFA&a=Crud&columna=factura&id='.$_REQUEST['factura']);  
        }
 
 public function Actualizar(){
    
        
          //$data = json_decode($_POST['datos']); 

          $this->proformas =  new oComprasFA(); 
          $this->proforma = $data; 
          
          $this->proformas->UpdateItems("tbl_proceso_compras_detalle", $_REQUEST['id'], $_REQUEST['valor'],$_REQUEST['columna'],$_REQUEST['proceso']); 
          
           
           header("Location:view_index.php?c=comprasFA&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
 }


 public function Eliminar(){
        

          $this->proformas =  new oComprasFA(); 
          $this->proforma = $_REQUEST;
          $this->proformas->Delete("tbl_proceso_compras_detalle", $_REQUEST['id'], $_REQUEST['columna'],$_REQUEST['proceso'],$_REQUEST['master']); 
          
           header("Location:view_index.php?c=comprasFA&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
 }


    public function CcomprasFA($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  
        }
        else{
    	  require_once("views/view_compras_fa.php");
        }
    }
/*
    public function listadonormal(){ 
    	require_once("views/view_compras_em.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_orden_compra_historico' );
    }*/


}



?>
