<?php
//Llamada al modelo
require_once("Models/McomercialList.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new mComercialList();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras.php");*/

class ComercialListController{

	private $ordenc;
    private $proveedores;
    private $insumo; 

	public function __CONSTRUCT(){
		$ordenc = new mComercialList();
    }

    public function Index(){ 
    	$ordenc = new mComercialList();//instanciamos la clase mComercialList del Modelo ComercialList
    	self::ComercialList();
    }
 
    public function Control(){ 
        $proveedores = new mComercialList();//instanciamos la clase mComercialList del Modelo ComercialList
        $insumo = new mComercialList();
        $ordenc = new mComercialList(); 
        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        //$this->proformas=$proformas->Obtener('tbl_proceso_compras pc,tbl_proceso_compras_detalle pcd'," proceso='PROFORMA' AND pc.proforma=pcd.proforma and pc.proforma",$_REQUEST['id']);
        
        self::ComercialList();
    }


    public function Menu(){ 
        $ordenc = new mComercialList();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'orden_compra_cl2.php';
        self::ComercialList($vista);
    }
 

    public function Actualizar(){
       
           
             //$data = json_decode($_POST['datos']); 

             $this->proformas =  new mComercialList(); 
             $this->proforma = $data; 
          
             $this->proformas->Update("tbl_orden_compra", $_REQUEST['id'],$_REQUEST['proceso']);  
             
             header("Location:orden_compra_cl2.php");  
    }

    public function ComercialList($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
    	  require_once("orden_compra_cl2.php");
        }
    }
 

}



?>
