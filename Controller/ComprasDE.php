<?php
//Llamada al modelo
require_once("Models/CcomprasDE.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oComprasDE();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras_em.php");*/

class ComprasDEController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;

	public function __CONSTRUCT(){
		$ordenc = new oComprasDE();
    }

    public function Index(){ 
    	$ordenc = new oComprasDE();//instanciamos la clase oComprasDE del Modelo CcomprasDE
    	self::CcomprasDE();
    }
 
    public function Detalle(){ 
        $proveedores = new oComprasDE();//instanciamos la clase oComprasDE del Modelo CcomprasDE
        $insumo = new oComprasDE();
        $ordenc = new oComprasDE();
        $maquina = new oComprasDE();

        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        $this->maquina=$maquina->get_Maquina();
 
        self::CcomprasDE();
    }


    public function Menu(){ 
        $ordenc = new oComprasDE();
        //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
        $vista = 'view_submenu_compras.php';
        self::CcomprasDE($vista);
    }

    public function Crud(){
    	$proformas = new oComprasDE(); 
    	if(isset($_REQUEST['id'])){
            $proveedores = new oComprasDE();//instanciamos la clase oComprasDE del Modelo CcomprasDE
            $insumo = new oComprasDE(); 
            $maquina = new oComprasDE();
            $general = new oComprasDE();
            $proformasPrincipal = new oComprasDE();
            $proformas = new oComprasDE();
            $factura = new oComprasDE();
            $mercancia = new oComprasDE();
            $detalle = new oComprasDE();
            $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
            $this->insumo=$insumo->get_Insumo();
            $this->maquina=$maquina->get_Maquina();
            $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='DETALLE EMBARQUE' AND pc.".$_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->general)
              $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='ENTRADA FACTURA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
             

            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='DETALLE EMBARQUE' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            
            if(!$this->proformasPrincipal)
            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

            if(!$this->proformasPrincipal)
            $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

    		$this->proformas=$proformas->Obtener(' tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma',"pc.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            $this->factura=$factura->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            $this->mercancia=$mercancia->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA MERCANCIA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
            $this->detalle=$detalle->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='DETALLE EMBARQUE' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
    	}

        self::CcomprasDE();//le digo que muestre en vista edit
    }

        public function Guardar($vista=''){
     
            $directorio = ROOT."pdfprocesocompras/";

            
            
            
            

            /*$tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto1,$_FILES['adjunto']['tmp_name'],'NUEVOS');
            $tieneadjunto2 = adjuntarArchivo('', $directorio, $adjunto2,$_FILES['adjunto2']['tmp_name'],'NUEVOS');
            $tieneadjunto3 = adjuntarArchivo('', $directorio, $adjunto3,$_FILES['adjunto3']['tmp_name'],'NUEVOS');
            $tieneadjunto4 = adjuntarArchivo('', $directorio, $adjunto4,$_FILES['adjunto4']['tmp_name'],'NUEVOS');
            $tieneadjunto5 = adjuntarArchivo('', $directorio, $adjunto5,$_FILES['adjunto5']['tmp_name'],'NUEVOS');*/
     
            //$proformas = new oCompras();
            $this->proformas =  new oComprasDE(); 
            $this->proforma = $_REQUEST;
            

            $archivofinal = $_POST['userfilegen'].','; 


            $adjunto1 = ($_FILES['adjunto']['name']); 
            if($_FILES['adjunto']['name'] != "") { 
              $porciones1 = explode(".", $_FILES['adjunto']['name']);
              $adjunto1 = "DET01". $_REQUEST['factura'] . "." . $porciones1[1];
              $tieneadjunto1 = adjuntarArchivoOK($_POST['userfile1'], $directorio, $adjunto1,$_FILES['adjunto'],'UPDATES');  
              $archivofinal .= $tieneadjunto1.',';
            }

            $adjunto2 = ($_FILES['adjunto2']['name']); 
            if($_FILES['adjunto2']['name'] != "") { 
              $porciones2 = explode(".", $_FILES['adjunto2']['name']);
              $adjunto2 = "DET02". $_REQUEST['factura'] . "." . $porciones2[1];
              $tieneadjunto2 = adjuntarArchivoOK($_POST['userfile2'], $directorio, $adjunto2,$_FILES['adjunto2'],'UPDATES');  
              $archivofinal .= $tieneadjunto2.',';
            }

            $adjunto3 = ($_FILES['adjunto3']['name']); 
            if($_FILES['adjunto3']['name'] != "") { 
              $porciones3 = explode(".", $_FILES['adjunto3']['name']);
              $adjunto3 = "DET03". $_REQUEST['factura'] . "." . $porciones3[1];
              $tieneadjunto3 = adjuntarArchivoOK($_POST['userfile3'], $directorio, $adjunto3,$_FILES['adjunto3'],'UPDATES');  
              $archivofinal .= $tieneadjunto3.',';
            }

            $adjunto4 = ($_FILES['adjunto4']['name']); 
            if($_FILES['adjunto4']['name'] != "") { 
              $porciones4 = explode(".", $_FILES['adjunto4']['name']);
              $adjunto4 = "DET04". $_REQUEST['factura'] . "." . $porciones4[1];
              $tieneadjunto4 = adjuntarArchivoOK($_POST['userfile4'], $directorio, $adjunto4,$_FILES['adjunto4'],'UPDATES');  
              $archivofinal .= $tieneadjunto4.',';
            }

            $adjunto5 = ($_FILES['adjunto5']['name']); 
            if($_FILES['adjunto5']['name'] != "") { 
              $porciones5 = explode(".", $_FILES['adjunto5']['name']);
              $adjunto5 = "DET05". $_REQUEST['factura'] . "." . $porciones5[1];
              $tieneadjunto5 = adjuntarArchivoOK($_POST['userfile5'], $directorio, $adjunto5,$_FILES['adjunto5'],'UPDATES');  
              $archivofinal .= $tieneadjunto5;
            }






/*            if(isset($_FILES['adjunto']) && $_FILES['adjunto']['name'] != ""){
            $nombre1 = str_replace(' ', '', $_FILES['adjunto']['name']);
            $porciones1 = explode(".", $nombre1);
            $adjunto1 = "DET01". $_REQUEST['factura'] . "." . $porciones1[1];  
                if($_POST['userfile1'] != '') { 
                  $tieneadjunto1 = adjuntarArchivo($_POST['userfile1'], $directorio, $adjunto1,$_FILES['adjunto']['tmp_name'],'UPDATES');  
                 }
                $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto1,$_FILES['adjunto']['tmp_name'],'NUEVOS');
                $archivofinal .= $tieneadjunto1.','.$_POST['userfile2'].','.$_POST['userfile3'].','.$_POST['userfile4'].','.$_POST['userfile5'];
            }

            if(isset($_FILES['adjunto2']) && $_FILES['adjunto2']['name'] != ""){
            $nombre2 = str_replace(' ', '', $_FILES['adjunto2']['name']);
            $porciones2 = explode(".", $nombre2);
            $adjunto2 = "DET02". $_REQUEST['factura'] . "." . $porciones2[1]; 
                if($_POST['userfile2'] != '') {
                  $tieneadjunto2 = adjuntarArchivo($_POST['userfile2'], $directorio, $adjunto2,$_FILES['adjunto2']['tmp_name'],'UPDATES');  
                 } 
                $tieneadjunto2 = adjuntarArchivo('', $directorio, $adjunto2,$_FILES['adjunto2']['tmp_name'],'NUEVOS'); 
                $archivofinal .= $_POST['userfile1'].','.$tieneadjunto2.','.$_POST['userfile3'].','.$_POST['userfile4'].','.$_POST['userfile5'];
            } 

            if(isset($_FILES['adjunto3']) && $_FILES['adjunto3']['name'] != ""){ 
                $nombre3 = str_replace(' ', '', $_FILES['adjunto3']['name']);
                    $porciones3 = explode(".", $nombre3);
                $adjunto3 = "DET03". $_REQUEST['factura'] . "." . $porciones3[1];
                if($_POST['userfile3'] != '') {
                  $tieneadjunto3 = adjuntarArchivo($_POST['userfile3'], $directorio, $adjunto3,$_FILES['adjunto3']['tmp_name'],'UPDATES');  
                 } 
                $tieneadjunto3 = adjuntarArchivo('', $directorio, $adjunto3,$_FILES['adjunto3']['tmp_name'],'NUEVOS');   
                $archivofinal .= $_POST['userfile1'].','.$_POST['userfile2'].','.$tieneadjunto3.','.$_POST['userfile4'].','.$_POST['userfile5'];
            }

            if(isset($_FILES['adjunto4']) && $_FILES['adjunto4']['name'] != ""){ 
            $nombre4 = str_replace(' ', '', $_FILES['adjunto4']['name']);
                $porciones4 = explode(".", $nombre4);
            $adjunto4 = "DET04". $_REQUEST['factura'] . "." . $porciones4[1];
                if($_POST['userfile4'] != '') {
                  $tieneadjunto4 = adjuntarArchivo($_POST['userfile4'], $directorio, $adjunto4,$_FILES['adjunto4']['tmp_name'],'UPDATES');  
                 }
                $tieneadjunto4 = adjuntarArchivo('', $directorio, $adjunto4,$_FILES['adjunto4']['tmp_name'],'NUEVOS'); 
                $archivofinal .= $_POST['userfile1'].','.$_POST['userfile2'].','.$_POST['userfile3'].','.$tieneadjunto4.','.$_POST['userfile5'];
            } 

            if(isset($_FILES['adjunto5']) && $_FILES['adjunto5']['name'] != ""){
                $nombre5 = str_replace(' ', '', $_FILES['adjunto5']['name']);
                    $porciones5 = explode(".", $nombre5);
                $adjunto5 = "DET05". $_REQUEST['factura'] . "." . $porciones5[1];
                if($_POST['userfile5'] != '') {
                  $tieneadjunto5 = adjuntarArchivo($_POST['userfile5'], $directorio, $adjunto5,$_FILES['adjunto5']['tmp_name'],'UPDATES');  
                 }
                $tieneadjunto5 = adjuntarArchivo('', $directorio, $adjunto5,$_FILES['adjunto5']['tmp_name'],'NUEVOS'); 
                $archivofinal .= $_POST['userfile1'].','.$_POST['userfile2'].','.$_POST['userfile3'].','.$_POST['userfile4'].','.$tieneadjunto5;
            } */

            
            $this->proforma['adjunto']= $archivofinal;

            $this->proformas->Registrar("tbl_proceso_compras", "proforma,pedido,factura,proceso,proveedor,fecha,bodega,tipopedido,tipoinsumo,maquina,plazo,valorplazo,fecha_plazo,adjunto,bl,fecha_bl,fecha_zar,fecha_eta,puerto_lleg,num_contenedor,tam_contenedor,usuario,estado",$_REQUEST['columna'],$_REQUEST['id'], $this->proforma);
            $this->proformas->RegistrarItems("tbl_proceso_compras_detalle", "proforma,pedido,factura,proceso,cantidad,medida,code,descripcion,moneda,precio,precio_total,incoterm,valoricot,estado,bodega ", $this->proforma); 
            header('Location:view_index.php?c=comprasDE&a=Crud&columna=factura&id='.$_REQUEST['factura']); 
        }

    public function Actualizar(){
       
           
             //$data = json_decode($_POST['datos']);    

             $this->proformas =  new oComprasDE(); 
             $this->proforma = $data; 
             
             $this->proformas->UpdateItems("tbl_proceso_compras_detalle", $_REQUEST['id'], $_REQUEST['valor'],$_REQUEST['columna'],$_REQUEST['proceso']); 
             
              
              header("Location:view_index.php?c=comprasDE&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
    }   
   

    public function Eliminar(){
              

             $this->proformas =  new oComprasDE(); 
             $this->proforma = $_REQUEST;
             $this->proformas->Delete("tbl_proceso_compras_detalle", $_REQUEST['id'], $_REQUEST['columna'],$_REQUEST['proceso'],$_REQUEST['master']); 
             
              header("Location:view_index.php?c=comprasDE&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
    }

    public function CcomprasDE($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  
        }
        else{
    	  require_once("views/view_compras_de.php");
        }
    }
/*
    public function listadonormal(){ 
    	require_once("views/view_compras_de.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_orden_compra_historico' );
    }*/


}



?>
