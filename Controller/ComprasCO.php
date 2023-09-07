<?php
//Llamada al modelo
require_once("Models/CcomprasCO.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oComprasCO();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras.php");*/

class ComprascoController{

	private $ordenc;
    private $proveedores;
    private $insumo; 

	public function __CONSTRUCT(){
		$ordenc = new oComprasCO();
    }

    public function Index(){ 
    	$ordenc = new oComprasCO();//instanciamos la clase oComprasCO del Modelo Ccompras
    	self::Ccompras();
    }
 
    public function Control(){ 
        $proveedores = new oComprasCO();//instanciamos la clase oComprasCO del Modelo Ccompras
        $insumo = new oComprasCO();
        $ordenc = new oComprasCO(); 
        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        //$this->proformas=$proformas->Obtener('tbl_proceso_compras pc,tbl_proceso_compras_detalle pcd'," proceso='PROFORMA' AND pc.proforma=pcd.proforma and pc.proforma",$_REQUEST['id']);
        
        self::Ccompras();
    }


    public function Menu(){ 
        $ordenc = new oComprasCO();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'view_submenu_compras.php';
        self::Ccompras($vista);
    }

    public function Crud(){
 
    	$ordenc = new oComprasCO(); 
    	if(isset($_REQUEST['id']) && isset($_REQUEST['proceso'])){
            $proveedores = new oComprasCO();//instanciamos la clase oComprasCO del Modelo Ccompras
            $insumo = new oComprasCO();
            $maquina = new oComprasCO();
            $general = new oComprasCO(); 
            $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
            $this->insumo=$insumo->get_Insumo();
            $this->maquina=$maquina->get_Maquina(); 
    		$this->general=$general->Obtener(" tbl_proceso_compras_detalle  pc INNER JOIN tbl_proceso_compras pcd ON pc.proforma=pcd.proforma "," pc.". $_REQUEST['columna'] ." <>'' AND pc.proceso='". $_REQUEST['proceso'] ."' AND pc.proceso=pcd.proceso  AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            if(!$this->general)
                $this->general=$general->Obtener(' tbl_proceso_compras pc ',"pc.proceso='". $_REQUEST['proceso'] ."' AND  pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);


            /* echo '<pre>';
             var_dump($this->general);
            echo '<pre>'; */
    	} 

        self::Ccompras();//le digo que muestre en vista edit
    }

    public function Excel(){
        header('Pragma: public'); 
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
        header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
        header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
        header('Pragma: no-cache'); 
        header('Expires: 0'); 
        header('Content-Transfer-Encoding: none'); 
        header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
        header('Content-type: application/x-msexcel'); // This should work for the rest 
        header('Content-Disposition: attachment; filename="comisiones.xls"');

        $general = new oComprasCO();
        $this->general=$general->Obtener(" tbl_proceso_compras pc INNER JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma "," pc.". $_REQUEST['columna'] ." <>'' AND pc.proceso='". $_REQUEST['proceso'] ."' AND pc.proceso=pcd.proceso  AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            if(!$this->general)
                $this->general=$general->Obtener(' tbl_proceso_compras pc ',"pc.proceso='". $_REQUEST['proceso'] ."' AND  pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

        self::Ccompras();

    }


    public function Actualizar(){
       
           
             //$data = json_decode($_POST['datos']); 

             $this->proformas =  new oComprasCO(); 
             $this->proforma = $data; 
            
             $this->proformas->Update("tbl_proceso_compras", $_REQUEST['id'], $_REQUEST['valor'],$_REQUEST['columna'],$_REQUEST['proceso']); 
             
              
              header("Location:view_index.php?c=comprasCO&a=Crud&proceso=". $_REQUEST['proceso'] ."  ");  
    }

    public function Ccompras($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
    	  require_once("views/view_compras_co.php");
        }
    }
 

}



?>
