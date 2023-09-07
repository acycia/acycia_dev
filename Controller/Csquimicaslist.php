<?php
//Llamada al modelo
require_once 'Models/Msquimicaslist.php';
 

class CsquimicaslistController{
    
	private $consultar;
    private $proveedores;
    private $insumo;
    private $proformas;

	public function __CONSTRUCT(){
		$consultar = new oSquimicaslist();
    }

    public function Index(){ 

    	$consultar = new oSquimicaslist();//instanciamos la clase oSicoq del Modelo ViewVistas
    	self::ViewVistas();
    }

    public function Menu(){ 
        $consultar = new oSquimicaslist();
        //$this->consultar=$consultar->get_Menu();//aqui llamo las funciones del modelo
        header('Location:menu.php');  
    }

    public function Inicio(){ 
    
        $proveedores = new oSquimicaslist();//instanciamos la clase oSicoq del Modelo ViewVistas
        $insumo = new oSquimicaslist(); 
        $proceso = new oSquimicaslist();
        $general = new oSquimicaslist();
        $items = new oSquimicaslist();
        $consumo = new oSquimicaslist();
        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo('SI'); 
        $this->proceso=$proceso->get_Proceso();
    
        $this->items=$items->Listaritems(' insumo ', " descripcion_insumo ", " WHERE quimicos='SUSTANCIAS QUIMICAS' ");
 
        $vista = $_REQUEST['vista'];
 
        self::ViewVistas($vista);
    }

 
    public function Busqueda(){
           $proveedores = new oSquimicaslist();//instanciamos la clase oSicoq del Modelo ViewVistas
           $insumo = new oSquimicaslist(); 
           $proceso = new oSquimicaslist();
           $general = new oSquimicaslist();
           $items = new oSquimicaslist();
           $consumo = new oSquimicaslist();
           $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
           $this->proceso=$proceso->get_Proceso();
           $this->insumo=$insumo->get_Insumo('SI'); 
     
           $this->items=$items->Listaritems(' insumo ', " descripcion_insumo ", " WHERE quimicos='SUSTANCIAS QUIMICAS' AND ". $_REQUEST['columna'] ." = '". $_REQUEST['id'] ."' ");
          
           $vista = $_REQUEST['vista'];
           
           self::ViewVistas($vista);
    }
 



    public function Guardar($vista=''){
 
        $directorio = ROOT."pdfsicoq/";
        $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
        $porciones = explode(".", $nombre);
        $adjunto = "SICOQ". $_REQUEST['autorizacion'] . "." . $porciones[1];
        $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
 
        
    	$this->proformas =  new oSquimicaslist(); 
        $this->general = $_REQUEST;
        
        if($nombre!=''){ 
        $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
        $this->general['userfile']= $tieneadjunto1;  
        }else{
            $tieneadjunto2 = adjuntarArchivo($this->general['userfile'], $directorio, $_FILES['adjunto']['tmp_name'],$_FILES['adjunto']['tmp_name'],'UPDATES');
           $this->general['userfile'] = $tieneadjunto2;
        }    

        $autorizacion = $this->general['autorizacion']  == '' ? $this->general['ccit_autorizacion'] : $this->general['autorizacion'];//cuando se consulta año anterior ya que no se puede agregar el numero de autorizacion en el filtro
        $this->general['autorizacion'] = $autorizacion ;
         $respuesta = $this->proformas->Registrar("tbl_sicoq", "autorizacion,anyo,kilospermitidosmes,kilosdisponiblescompra,totalingresados,totalsalida,totalinventario,fecha_inicio,fecha_vence,userfile", $this->general);
        $this->proformas->RegistrarItems("tbl_sicoq_items", "nombre,ingresokilos,fecha_recepcion,proveedor,costound,factura,area,fecha_salida,salidakilos,numeradora,autorizacion,ccit_autorizacion,op,controladas,responsable,revisado,aprobado,modificado", $this->general);  
   
        if($_REQUEST['alert']){

          echo $respuesta;die;//para q norecargue y muestre el alert 
        }
        
        header('Location:view_index.php?c=csquimicaslist&a=Busqueda&columna=autorizacion&id='.$_REQUEST['autorizacion'].'&controladas='.$_REQUEST['controladas']);  
    }



    public function Editar(){
            
            
             $this->sicoq =  new oSquimicaslist(); 
             $this->sicoqs = $_REQUEST;
             //ids,valorid,valores,tabla,url  
             $respuesta =  $this->logs->Update($_REQUEST['tabla'], $this->sicoqs);  
             echo $respuesta;  
    }


    public function Eliminar(){
            

             $this->sicoq =  new oSquimicaslist(); 
             $this->sicoqs = $_REQUEST;
             $this->sicoq->Delete("tbl_sicoq_items", $_REQUEST['id'], $_REQUEST['columna'],$_REQUEST['proceso'], $_REQUEST['master']); 
             
              header("Location:view_index.php?c=csquimicaslist&a=Busqueda&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ."&controladas=" . $_REQUEST['controladas']. " ");  
    }

    public function ViewVistas($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  //header('Location:'.$vista);  
        }
        else{
    	  require_once("views/view_scquimicaslist.php");
        }
    }
 


}



?>
