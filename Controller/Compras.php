<?php
//Llamada al modelo
require_once("Models/Ccompras.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oCompras();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras.php");*/

class ComprasController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;

	public function __CONSTRUCT(){
		$ordenc = new oCompras();
    }

    public function Index(){ 
    	$ordenc = new oCompras();//instanciamos la clase oCompras del Modelo Ccompras
    	self::Ccompras();
    }
 
    public function Proforma(){ 
        $proveedores = new oCompras();//instanciamos la clase oCompras del Modelo Ccompras
        $insumo = new oCompras();
        $maquina = new oCompras();
        $ordenc = new oCompras();
        $proformas = new oCompras();
        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        $this->maquina=$maquina->get_Maquina();
        //$this->proformas=$proformas->Obtener('tbl_proceso_compras pc,tbl_proceso_compras_detalle pcd'," proceso='PROFORMA' AND pc.proforma=pcd.proforma and pc.proforma",$_REQUEST['id']);
        
        self::Ccompras();
    }


    public function Menu(){ 
        $ordenc = new oCompras();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'view_submenu_compras.php';
        self::Ccompras($vista);
    }

    public function Crud(){
    	$ordenc = new oCompras(); 
    	if(isset($_REQUEST['id'])){
            $proveedores = new oCompras();//instanciamos la clase oCompras del Modelo Ccompras
            $insumo = new oCompras();
            $maquina = new oCompras();
            $general = new oCompras();
            $proformas = new oCompras();
            $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
            $this->insumo=$insumo->get_Insumo();
            $this->maquina=$maquina->get_Maquina();
            //$this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='PROFORMA' AND pc.proforma",$_REQUEST['id']);
    		$this->proformas=$proformas->Obtener(' tbl_proceso_compras_detalle pcd '," pcd.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            $this->general=$general->Obtener(' tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma '," pcd.proceso='PROFORMA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
    	}

        self::Ccompras();//le digo que muestre en vista edit
    }
 
    public function Guardar($vista=''){
 
        $directorio = ROOT."pdfprocesocompras/";
        $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
            $porciones = explode(".", $nombre);
        $adjunto = "PRO". $_REQUEST['proforma'] . "." . $porciones[1];
        $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
 
        //$proformas = new oCompras();
    	$this->proformas =  new oCompras(); 
        $this->proforma = $_REQUEST;
        
        if($nombre!=''){ 
        $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
        $this->proforma['adjunto']= $tieneadjunto1;  
        }else{
           $this->proforma['adjunto'] = $_REQUEST['userfile'];
        }
        
        $this->proformas->Registrar("tbl_proceso_compras", "proforma,pedido,factura,proceso,proveedor,fecha,bodega,tipopedido,tipoinsumo,maquina,plazo,valorplazo,adjunto,usuario,estado", $this->proforma);
        $this->proformas->RegistrarItems("tbl_proceso_compras_detalle", "proforma,pedido,factura,proceso,cantidad,medida,code,descripcion,moneda,precio,precio_total,incoterm,valoricot,estado ", $this->proforma);  
        
        header('Location:view_index.php?c=compras&a=Crud&columna=proforma&id='.$_REQUEST['proforma']);  
    }

    public function Eliminar(){
           

             $this->proformas =  new oCompras(); 
             $this->proforma = $_REQUEST;
             $this->proformas->Delete("tbl_proceso_compras_detalle", $_REQUEST['id'], $_REQUEST['columna'],$_REQUEST['proceso'],$_REQUEST['master']); 
             
              header("Location:view_index.php?c=compras&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
    }
/*    public function Eliminar(){
    	$this->ordenc->Eliminar($_REQUEST['id']);//aqui llamo las funciones del modelo
    	header('Location: index.php');
    }*/

    public function Ccompras($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
    	  require_once("views/view_compras.php");
        }
    }
/*
    public function listadonormal(){ 
    	require_once("views/view_compras.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_orden_compra_historico' );
    }*/


}



?>
