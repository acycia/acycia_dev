<?php
//Llamada al modelo
require_once("Models/Inicio.php"); 
/* 

$ordenc=new Inicio();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras.php");*/

class ControlergenController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;

	public function __CONSTRUCT(){
		$ordenc = new Inicio();
    }

    public function Index(){ 
    	$ordenc = new Inicio();//instanciamos la clase Inicio del Modelo Ccompras
    	self::Inicio();
    }
  

    public function Menu(){ 
        $ordenc = new Inicio();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = $_REQUEST['view'];//'view_compras_exp.php';
        self::Inicio($vista);
    }
 
 
    public function Inicio($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
    	  require_once("menu.php");
        }
    }
/*
    public function listadonormal(){ 
    	require_once("views/view_compras.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_orden_compra_historico' );
    }*/


}



?>
