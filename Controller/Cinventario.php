<?php
//Llamada al modelo
require_once("Models/Minventario.php");
include('funciones/adjuntar.php'); 

class CinventarioController{

	private $inventar;
    private $proveedores;
    private $insumo;
    private $proformas;

	public function __CONSTRUCT(){
		$inventar = new oInventario();
    }

    public function Index(){ 
    	$inventar = new oInventario();//instanciamos la clase oInventario del Modelo MInventario
    	self::Minventario();
    }
 
    public function Inicio(){  
        $insumo = new oInventario();//instanciamos la clase oInventario del Modelo MInventario  
        $general = new oInventario(); 
        $refcero = new oInventario();
        $despacho = new oInventario();   
  

        //$this->general=$general->get_Ref(' tbl_inventario ',"","ORDER BY CONVERT(referencia, SIGNED INTEGER) ASC","*" );
        
        $fechaActual = date('Y-m-d');

        $this->refcero=$refcero->get_Ref(" tbl_remision_detalle tabla1 INNER JOIN tbl_inventario tabla2 ON tabla1.int_ref_io_rd = tabla2.referencia AND tabla1.fecha_rd ='$fechaActual' ","","GROUP BY tabla1.int_ref_io_rd ORDER BY tabla1.int_ref_io_rd ASC"," if((tabla2.disponible - SUM(tabla1.int_cant_rd)) <= 0, tabla2.referencia,'') AS refcero" ); 

        $this->general=$general->get_Ref(" tbl_inventario tabla1 WHERE tabla1.fecha ='$fechaActual' ","","ORDER BY CONVERT(referencia, SIGNED INTEGER) ASC"," tabla1.referencia,tabla1.fecha,tabla1.inventario,tabla1.despacho, tabla1.disponible " );
     

         

        $vista = 'view_inventario.php';

        self::Minventario($vista);
    }


    public function Menu(){ 
        $inventar = new oInventario();
  
        header('Location:menu.php');  
    }

 
    public function Crud(){
    	$inventar = new oInventario(); 
    	if(isset($_REQUEST['id'])){
        
            $insumo = new oInventario(); 
            $general = new oInventario();
            $refcero = new oInventario();   
            $this->insumo=$insumo->get_Insumo();  
            
            $this->general=$general->get_Ref(" tbl_inventario tabla1 WHERE tabla1.". $_REQUEST['columna'] ."='". $_REQUEST['id'] ."' ","","ORDER BY CONVERT(referencia, SIGNED INTEGER) ASC"," tabla1.referencia,tabla1.fecha,tabla1.inventario,tabla1.despacho, tabla1.disponible " );

            $fechaActual = date('Y-m-d');

            $this->refcero=$refcero->get_Ref(" tbl_remision_detalle tabla1 INNER JOIN tbl_inventario tabla2 ON tabla1.int_ref_io_rd = tabla2.referencia AND tabla1.fecha_rd ='$fechaActual' ","","GROUP BY tabla1.int_ref_io_rd ORDER BY tabla1.int_ref_io_rd ASC"," if((tabla2.disponible - SUM(tabla1.int_cant_rd)) <= 0, tabla2.referencia,'') AS refcero" ); 
 

    	}

        $vista = 'view_inventario.php';
        self::Minventario($vista);//le digo que muestre en vista edit
    }
 



    public function Guardar($vista=''){
 
    	$this->proformas =  new oInventario(); 
        $this->general = $_REQUEST;
 
         $respuesta = $this->proformas->Registrar("tbl_inventario", "referencia,fecha,inventario,despacho,disponible ", $this->general);
         
   
        if($_REQUEST['alert']){

          echo $respuesta;die;//para q norecargue y muestre el alert 
        }

        header('Location:view_index.php?c=cinventario&a=Crud&columna=autorizacion&id='.$_REQUEST['autorizacion']);  
    }



    public function Editar(){
            
            
             $this->inventario =  new oInventario(); 
             $this->inventarios = $_REQUEST;
             //ids,valorid,valores,tabla,url  
             $respuesta =  $this->logs->Update($_REQUEST['tabla'], $this->inventarios);  
             echo $respuesta;  
    }


    public function Eliminar(){
            

             $this->inventario =  new oInventario(); 
             $this->inventarios = $_REQUEST;
             $this->inventario->Delete("tbl_inventario", $_REQUEST['id'], $_REQUEST['columna'],$_REQUEST['proceso'], $_REQUEST['master']); 
             
              header("Location:view_index.php?c=cinventario&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id']." ");  
    }

    public function Minventario($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
    	  require_once("views/view_inventario.php");
        }
    }
 


}



?>
