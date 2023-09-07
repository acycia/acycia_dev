<?php
//Llamada al modelo
require_once("Models/Occomercial.php");
/* 

$ordenc=new oComercial();
$datos=$ordenc->get_ordenc();
require_once("views/view_ocomercial.php");*/

class OcomercialController{

	private $ordenc;

	public function __CONSTRUCT(){
		$ordenc = new oComercial();  
        /*$this->ordenc=$ordenc->get_Listar();//aqui llamo las funciones del modelo
        self::Occomercial();*/
    }

    public function Index(){ 
    	$ordenc = new oComercial();  
    	$this->ordenc=$ordenc->get_Listar();//aqui llamo las funciones del modelo
    	self::Occomercial();
    }


    public function Crud(){
    	$ordenc = new oComercial();
    	if(isset($_REQUEST['id'])){
    		$this->ordenc = $ordenc->Obtener($_GET['tabla'],$_GET['columna'],$_REQUEST['id']);//aqui llamo las funciones del modelo
    	}
           /* echo '<pre>';
           var_dump($this->ordenc);*/
        self::Occomercial();//le digo que muestre en vista edit
    }

    public function Guardar(){
    	$ordenc = new oComercial();
    	$ordenc->id_pedido = $_REQUEST['id_pedido'];
    	$ordenc->str_numero_oc = 'pruebas';
    	$ordenc->fecha_ingreso_oc = $_REQUEST['fecha_ingreso_oc'];
    	$ordenc->str_condicion_pago_oc = $_REQUEST['str_condicion_pago_oc']; 
    	$ordenc->id_pedido > 0 ? $this->ordenc->Actualizar($ordenc) : $this->ordenc->Registrar($ordenc);

    	header('Location: index.php');
    }
   


     public function Guardaroc(){ 
         //INGRESO OC
         $myRollo = new oComercial();
         $nuevovalorRollo = new oComercial();
         
         $directorio = "pdfacturasoc/";
         $tieneadjunto1 = self::adjuntarArchivoG('', $directorio, $_FILES['str_archivo_oc']['name'],$_FILES['str_archivo_oc']['tmp_name'],'NUEVOS');
         $tieneadjunto2 = self::adjuntarArchivoG('', $directorio, $_FILES['adjunto2']['name'],$_FILES['adjunto2']['tmp_name'],'NUEVOS');
         $tieneadjunto3 = self::adjuntarArchivoG('', $directorio, $_FILES['adjunto3']['name'],$_FILES['adjunto3']['tmp_name'],'NUEVOS'); 
         

         $b_oc_interno=$_POST['b_oc_interno'];
         $cobra_flete=$_POST['cobra_flete'];
         
         if(($_POST['str_numero_oc'])){ 
          $myObject = new oComercial();
           $existeorden=$myObject->Obtener('tbl_orden_compra','str_numero_oc', " '".$_POST['str_numero_oc']."'  " );
         } 
         
         //evita error duplicado 
         if(($_POST['str_numero_oc']) && !$existeorden){
         $nuevovalorRollo = [ "str_numero_oc"=>$_POST['str_numero_oc'],"id_c_oc"=>$_POST['id_c_oc'],"str_nit_oc"=>$_POST['nit_c'],"fecha_ingreso_oc"=>$_POST['fecha_ingreso_oc'],"fecha_entrega_oc"=>$_POST['fecha_entrega_oc'],"str_condicion_pago_oc"=>$_POST['str_condicion_pago_oc'],"str_observacion_oc"=>$_POST['str_observacion_oc'],"int_total_oc"=>$_POST['int_total_oc'],"b_facturas_oc"=>$_POST['b_facturas_oc'],"b_num_remision_oc"=>$_POST['b_num_remision_oc'],"b_factura_cirel_oc"=>$_POST['b_factura_cirel_oc'],"str_dir_entrega_oc"=>$_POST['str_dir_entrega_oc'],"str_archivo_oc"=>$tieneadjunto1,"adjunto2"=>$tieneadjunto2,"adjunto3"=>$tieneadjunto3,"str_elaboro_oc"=>$_POST['str_elaboro_oc'],"str_aprobo_oc"=>$_POST['str_aprobo_oc'],"b_estado_oc"=>$_POST['b_estado_oc'],"str_responsable_oc"=>$_POST['str_responsable_oc'],"b_borrado_oc"=>$_POST['b_borrado_oc'],"salida_oc"=>$_POST['salida_oc'],"b_oc_interno"=>$b_oc_interno,"vta_web_oc"=>$_POST['vta_web_oc'],"expo_oc"=>$_POST['expo_oc'],"autorizado"=>$_POST['autorizado'],"entrega_fac"=>$_POST['entrega_fac'],"fecha_cierre_fac"=>$_POST['fecha_cierre_fac'],"comprobante_ent"=>$_POST['comprobante_ent'],"pago_pendiente"=>$_POST['pago_pendiente'],"cobra_flete"=>$cobra_flete,"precio_flete"=>$_POST['precio_flete'],"tipo_despacho"=>$_POST['tipo_despacho'],"fecha_autoriza"=>$_POST['fecha_ingreso_oc']
           ];

        $columnasRollo = ["str_numero_oc"=>"str_numero_oc","id_c_oc"=>"id_c_oc","str_nit_oc"=>"str_nit_oc","fecha_ingreso_oc"=>"fecha_ingreso_oc","fecha_entrega_oc"=>"fecha_entrega_oc","str_condicion_pago_oc"=>"str_condicion_pago_oc","str_observacion_oc"=>"str_observacion_oc","int_total_oc"=>"int_total_oc","b_facturas_oc"=>"b_facturas_oc","b_num_remision_oc"=>"b_num_remision_oc","b_factura_cirel_oc"=>"b_factura_cirel_oc","str_dir_entrega_oc"=>"str_dir_entrega_oc","str_archivo_oc"=>"str_archivo_oc","adjunto2"=>"adjunto2","adjunto3"=>"adjunto3","str_elaboro_oc"=>"str_elaboro_oc","str_aprobo_oc"=>"str_aprobo_oc","b_estado_oc"=>"b_estado_oc","str_responsable_oc"=>"str_responsable_oc","b_borrado_oc"=>"b_borrado_oc","salida_oc"=>"salida_oc","b_oc_interno"=>"b_oc_interno","vta_web_oc"=>"vta_web_oc","expo_oc"=>"expo_oc","autorizado"=>"autorizado","entrega_fac"=>"entrega_fac","fecha_cierre_fac"=>"fecha_cierre_fac","comprobante_ent"=>"comprobante_ent","pago_pendiente"=>"pago_pendiente","cobra_flete"=>"cobra_flete","precio_flete"=>"precio_flete","tipo_despacho"=>"tipo_despacho","fecha_autoriza"=>"fecha_autoriza"
        ];

            if(isset($_POST['str_numero_oc']) && $nuevovalorRollo){
               $respuesta = $myRollo->RegistrarGen("tbl_orden_compra", $columnasRollo, $nuevovalorRollo);
              }//FIN  
             
                  

             //HISTORICO
              if(isset($_POST['str_numero_oc'])){ 
               $historico=$myRollo->Obtener('tbl_orden_compra','str_numero_oc', " '".$_POST['str_numero_oc']."'  " );
             }      

             $myRollo->Registrar("tbl_orden_compra_historico", "id_pedido,str_numero_oc,id_c_oc,str_nit_oc,fecha_ingreso_oc,fecha_entrega_oc,str_condicion_pago_oc,str_observacion_oc,int_total_oc,b_facturas_oc,b_num_remision_oc,b_factura_cirel_oc,str_dir_entrega_oc,str_archivo_oc,adjunto2,adjunto3,str_elaboro_oc,str_aprobo_oc,b_estado_oc,str_responsable_oc,b_borrado_oc,salida_oc,b_oc_interno,vta_web_oc,expo_oc,autorizado,tb_pago,factura_oc,entrega_fac,fecha_cierre_fac,comprobante_ent,estado_cartera,tipo_pago_cartera,valor_cartera,modifico", $historico);

        }//Fin evita error duplicado
 
        return header('Location: orden_compra_cl_edit.php?str_numero_oc='.$_POST['str_numero_oc']."&id_oc=".$_POST['id_c_oc']);

     }




    public function Historico($vista=''){
    	$myObject = new oComercial();
    	$this->historico =  new oComercial();

    	if(isset($_REQUEST['id'])){ 
    		$this->historico=$myObject->Obtener('tbl_orden_compra','id_pedido',$_REQUEST['id']);
    	} 

        $myObject->Registrar("tbl_orden_compra_historico", "id_pedido,str_numero_oc,id_c_oc,str_nit_oc,fecha_ingreso_oc,fecha_entrega_oc,str_condicion_pago_oc,str_observacion_oc,int_total_oc,b_facturas_oc,b_num_remision_oc,b_factura_cirel_oc,str_dir_entrega_oc,str_archivo_oc,adjunto2,adjunto3,str_elaboro_oc,str_aprobo_oc,b_estado_oc,str_responsable_oc,b_borrado_oc,salida_oc,b_oc_interno,vta_web_oc,expo_oc,autorizado,tb_pago,factura_oc,entrega_fac,fecha_cierre_fac,comprobante_ent,estado_cartera,tipo_pago_cartera,valor_cartera,modifico", $this->historico);
  
      	 $vista =!'' ? header('Location:'.$vista)  : header('Location: index.php');
    }


    public function Eliminar(){
    	$this->ordenc->Eliminar($_REQUEST['id']);//aqui llamo las funciones del modelo
    	header('Location: index.php');
    }

    public function Occomercial(){ 
    	require_once("views/view_ocomercial.php");
    }

    public function listadonormal(){ 
    	require_once("views/view_ocomercial.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_orden_compra_historico' );
    }




    public function adjuntarArchivoG($tieneadjunto='', $directorio, $nuevoadjunto, $tmp_name, $tipoejecutar ){

       /*$tamano_archivo = $_FILES[$nuevoadjunto]['size'];//1048576 es una mega 
       $tipo_archivo = $_FILES[$nuevoadjunto]['type'];*/
         //if (!((strpos($tipo_archivo, "pdf")) && ($tamano_archivo < 10485770))) 


       if ($nuevoadjunto != "") { 
          if($tipoejecutar == 'UPDATES' || $tipoejecutar == 'NUEVOS'){

                 //UPDATE DEL ARCHIVO ELN EL SERVIDOR 
                 if($tieneadjunto != ""){
                   if (file_exists($directorio.$tieneadjunto)) 
                   { 
                      unlink($directorio.$tieneadjunto); 
                   }  
                 } 
                  
             $tieneadjunto2 = str_replace(' ', '', $nuevoadjunto);
             $archivo_temporal = $tmp_name;

             if (!copy($archivo_temporal,$directorio.$tieneadjunto2)) {
                $error = "Error al enviar el Archivo";
             } else { 
                $tieneadjunto = $tieneadjunto2; 
             }

             return $tieneadjunto;              

          } 

       }else{
          return $tieneadjunto;
       }
     




    }

}



?>
