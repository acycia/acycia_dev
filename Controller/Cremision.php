<?php
//Llamada al modelo
require_once("Models/Mremision.php");
/* 

$ordenc=new oRemision();
$datos=$ordenc->get_ordenc();
require_once("views/view_remision.php");*/

class CRemisionController{

	private $ordenc;

	public function __CONSTRUCT(){
		$ordenc = new oRemision();  
        /*$this->ordenc=$ordenc->get_Listar();//aqui llamo las funciones del modelo
        self::viewRemision();*/
    }

    public function Index(){ 
    	$ordenc = new oRemision();  
    	$this->ordenc=$ordenc->get_Listar();//aqui llamo las funciones del modelo
    	self::viewRemision();
    }


    public function Crud(){
    	$ordenc = new oRemision();
    	if(isset($_REQUEST['id'])){ 
    		$this->ordenc = $ordenc->Obtener($_GET['tabla'],$_GET['columna'],$_REQUEST['id']);//aqui llamo las funciones del modelo 
    	}

        self::viewRemision();//le digo que muestre en vista edit
    }

    public function Guardar(){
    	$ordenc = new oRemision();
    	$ordenc->id_pedido = $_REQUEST['id_pedido'];
    	$ordenc->str_numero_oc = 'pruebas';
    	$ordenc->fecha_ingreso_oc = $_REQUEST['fecha_ingreso_oc'];
    	$ordenc->str_condicion_pago_oc = $_REQUEST['str_condicion_pago_oc']; 
    	$ordenc->id_pedido > 0 ? $this->ordenc->Actualizar($ordenc) : $this->ordenc->Registrar($ordenc);

    	header('Location: index.php');
    }


    public function Historico($vista=''){
    	$myObject = new oRemision();
    	$this->historico =  new oRemision();

    	if(isset($_REQUEST['id'])){ 
    		$this->historico=$myObject->Obtener('tbl_remisiones','id_pedido',$_REQUEST['id']);
    	} 

        $myObject->Registrar("tbl_remisiones_historico", "int_remision,str_numero_oc_r,fecha_r,str_encargado_r,str_transportador_r,str_guia_r,str_elaboro_r,str_aprobo_r,str_observacion_r,factura_r,b_borrado_r,ciudad_pais,modifico", $this->historico);
  
      	 $vista =!'' ? header('Location:'.$vista)  : header('Location: index.php');
    }


    public function Eliminar(){
    	$this->ordenc->Eliminar($_REQUEST['id']);//aqui llamo las funciones del modelo
    	header('Location: index.php');
    }

    public function viewRemision(){ 
    	require_once("views/view_remision.php");
    }

    public function listadonormal(){ 
    	require_once("views/view_remision.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_remisiones_historico' );
    }


}



?>
