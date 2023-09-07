<?php
//Llamada al modelo
require_once("Models/Mingresosalida.php");
include('funciones/adjuntar.php'); 

class CingresosalidaController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;

	public function __CONSTRUCT(){
		$ordenc = new oIngresosalida();
    }

    public function Index(){ 
    	$ordenc = new oIngresosalida();//instanciamos la clase oIngresosalida del Modelo Mingresosalida
    	self::Mingresosalida();
    }
 
    public function Menu(){ 
        $ordenc = new oIngresosalida();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        header('Location:menu.php');  
    }

    public function Inicio(){ 
        $proveedores = new oIngresosalida();//instanciamos la clase oIngresosalida del Modelo Mingresosalida
        $insumo = new oIngresosalida(); 
        $proceso = new oIngresosalida();
        $general = new oIngresosalida();
        $items = new oIngresosalida(); 
        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo(0); 
        $this->proceso=$proceso->get_Proceso();

      
             $this->general=$general->buscarId(' tbl_ingresosalida_items '," id_i " );
 
        
        $anyoActual = date('Y');

        $this->items=$items->Listaritems(' tbl_ingresosalida_items pcd '," ORDER BY pcd.nombre ASC "," GROUP BY pcd.nombre ", " WHERE YEAR(pcd.fecharecepcion) ='$anyoActual' OR YEAR(pcd.fechasalida) ='$anyoActual' "," *, SUM(ingresokilos) as ingresokilos, SUM(salidakilos) as salidakilos, SUM(inventariofinal) as inventariofinal, SUM(ingresokilos) - (SUM(salidakilos) +SUM(inventariofinal)) as totalconsumo " );

        $this->refcero=self::stockMinimo();
 
        $vista = 'view_ingresosalida.php';

        self::Mingresosalida($vista);
    }
 

    public function Crud(){
    	$ordenc = new oIngresosalida(); 
    	if(isset($_REQUEST['id'])){
            
            $proveedores = new oIngresosalida();
            $insumo = new oIngresosalida();
            $proceso = new oIngresosalida();
            $general = new oIngresosalida();
            $items = new oIngresosalida(); 
              
 
            $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
            $this->insumo=$insumo->get_Insumo(0);
            $this->proceso=$proceso->get_Proceso();
             
            $anyoActual = date('Y');
    		//$this->items=$items->Listaritems(' tbl_ingresosalida_items pcd ',"pcd.nombre", " WHERE YEAR(pcd.fecharecepcion) ='$anyoActual' " );

            $this->general=$general->buscarId(' tbl_ingresosalida_items '," id_i " );
         
            $this->items=$items->Obtener(' tbl_ingresosalida_items ', $_REQUEST['columna'],$_REQUEST['id']);
 
            $this->refcero=self::stockMinimo();
            

             
    	}

        $vista = 'view_ingresosalida.php';
        self::Mingresosalida($vista);//le digo que muestre en vista edit
    }
 


    public function stockMinimo($insumoid=''){
        
        $refcero = new oIngresosalida();
        $anyoActual = date('Y');
        $this->refcero=$refcero->get_Ref(" tbl_ingresosalida_items  WHERE YEAR(fecharecepcion) ='$anyoActual' ","","GROUP BY  nombre ORDER BY  fecharecepcion DESC"," if( SUM(inventariofinal)  <  20,  nombre,'' ) AS insumoveinte" ); 
        return $this->refcero;

    }


    public function Guardar($vista=''){
 
        /*$directorio = ROOT."pdfsicoq/";
        $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
        $porciones = explode(".", $nombre);
        $adjunto = "SICOQ". $_REQUEST['autorizacion'] . "." . $porciones[1];
        $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');*/
 
        
    	$this->proformas =  new oIngresosalida(); 
        $this->general = $_REQUEST;
        
        /*if($nombre!=''){ 
        $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
        $this->general['userfile']= $tieneadjunto1;  
        }else{
            $tieneadjunto2 = adjuntarArchivo($this->general['userfile'], $directorio, $_FILES['adjunto']['tmp_name'],$_FILES['adjunto']['tmp_name'],'UPDATES');
           $this->general['userfile'] = $tieneadjunto2;
        }  */  
       

        $this->proformas->Registrar("tbl_ingresosalida_items", "id_i,nombre,ingresokilos,fecharecepcion,oc,fechasalida,salidakilos,inventariofinal,totalconsumo,responsable,modificado", $this->general);  
   
        if($_REQUEST['alert']){

          echo $respuesta;die;//para q norecargue y muestre el alert 
        }

        header('Location:view_index.php?c=cingresosalida&a=Crud&columna=id_i&id='.$_REQUEST['id_i']);  
    }



    public function Editar(){
            
            
             $this->sicoq =  new oIngresosalida(); 
             $this->sicoqs = $_REQUEST;
             //ids,valorid,valores,tabla,url  
             $respuesta =  $this->logs->Update($_REQUEST['tabla'], $this->sicoqs);  
             echo $respuesta; 
             //$respuesta =  $this->logs->Update($_REQUEST['ids'],$_REQUEST['valorid'],$_REQUEST['name'],$_REQUEST['valorc'],$_REQUEST['tabla']);  
              //header("Location:view_index.php?c=csicoq&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ."");
    }


    public function Eliminar(){
            

             $this->sicoq =  new oIngresosalida(); 
             $this->sicoqs = $_REQUEST;
             $this->sicoq->Delete("tbl_ingresosalida_items", $_REQUEST['id'], $_REQUEST['columna'],$_REQUEST['proceso'], $_REQUEST['master']); 
             
              header("Location:view_index.php?c=cingresosalida&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
    }

    public function Mingresosalida($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
    	  require_once("views/view_ingresosalida.php");
        }
    }
 


}



?>
