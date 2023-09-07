<?php
//Llamada al modelo 
require_once("Models/Msellado.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oMsellado();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras_em.php");*/


class CselladoController{

  private $ordenc;
    private $proveedores;
    private $insumo;
    private $proformas;
  public function __CONSTRUCT(){
    $ordenc = new oMsellado();
    }

    public function Index(){ 
      $ordenc = new oMsellado();//instanciamos la clase oMsellado del Modelo Msellado
      self::Msellado();
    }


    public function Inicio(){  
        echo 'Test1-Inicio';
         $conexion = new oMsellado();
         
        //CUANDO ARRANCA DESDE CERO EL INGRESO DE TIQUETES O CAMBIO DE CAJA

         if (isset($_GET[mi_var_array]))//viene del listado
                     
         {      
               $a=stripslashes ($_GET[mi_var_array]);
                     
               $mi_array=unserialize($a);
                     
              /* foreach ($mi_array AS $clave => $valor)
                     
                       echo "$clave ----> $valor <br>"; 
              */           
         }  
         
          

          $row_usuario = $conexion->llenarCampos('usuario',"WHERE usuario='$colname_usuario'", '','*');       

          //ID NUMERACION
           //$row_tiquete_num = $conexion->llenarCampos("tbl_numeracion", "WHERE int_op_n=".$mi_array['int_op_tn']." ", "", "*");
          //$row_ultimo = $conexion->buscarId('tbl_numeracion','id_numeracion');      

          //LISTA EMPLEADOS
          //SELECT * FROM empleado a INNER JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado ORDER BY a.codigo_empleado DESC
          $row_codigo_empleado = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(7,9) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC'); 
           
          $row_revisor = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(7,9) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');      

          
          $row_op = $conexion->llenaSelect('tbl_orden_produccion','WHERE tbl_orden_produccion.id_op NOT IN(SELECT tbl_numeracion.int_op_n FROM tbl_numeracion )', 'ORDER BY tbl_orden_produccion.id_op DESC','tbl_orden_produccion.id_op');       

          $row_caja_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE ref_tn='".$mi_array['int_cod_ref_op']."' ", " ORDER BY id_tn DESC, int_op_tn DESC,int_hasta_tn DESC ", "int_hasta_tn");

          if(isset($mi_array['int_op_tn'])&& $mi_array['int_op_tn']!=''){     
          //CARGA DATOS DE OP      
          $row_op_carga = $conexion->llenarCampos('tbl_orden_produccion','WHERE id_op='.$mi_array['int_op_tn'], '','*');    
          //LISTA DE CAJAS      
          $row_caja_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$mi_array['int_op_tn']."'", "ORDER BY int_caja_tn DESC LIMIT 1", "DISTINCT int_caja_tn,int_op_tn ");      

          $select_caja_num = $conexion->llenaSelect('tbl_tiquete_numeracion',"WHERE int_op_tn='".$mi_array['int_op_tn']."'",'ORDER BY int_caja_tn DESC', "DISTINCT int_caja_tn,int_op_tn");       
          //CONSECUTIVO DE PAQUETES      
          $row_paquete = $conexion->llenarCampos('tbl_orden_produccion pro,tbl_numeracion nume','WHERE pro.int_cod_ref_op=nume.cod_ref_n AND pro.id_op='.$mi_array['int_op_tn'],"ORDER BY nume.int_op_n DESC LIMIT 1","nume.int_hasta_n, nume.int_paquete_n, nume.int_caja_n"); 

          
 

          }


              require_once("views/view_sellado_numeracion_inicio.php");
    }

    public function Numeracion(){  
        echo 'Test1-';
         $conexion = new oMsellado();
         $insumo = new oMsellado();
         $this->insumo=$insumo->get_Insumo();
        //CUANDO ARRANCA DESDE CERO EL INGRESO DE TIQUETES O CAMBIO DE CAJA
      

         if (isset($_GET[mi_var_array]))//viene del listado
                     
         {      
               $a=stripslashes($_GET[mi_var_array]);
                     
               $mi_array=unserialize($a);
                     
              $mi_array['int_op_tn'] = $_GET['int_op_tn'];
              $mi_array['int_caja_tn'] = $_GET['int_caja_tn'];          
         } else {

            $mi_array['int_op_tn'] = $_GET['int_op_tn'];
            $mi_array['int_caja_tn'] = $_GET['int_caja_tn']; 

         }
        "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='3' AND estado_rtp='0' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";

        $this->insumo=$insumo->llenaSelect("tbl_reg_tipo_desperdicio","WHERE id_proceso_rtd='4' AND codigo_rtp='3' AND estado_rtp='0'","ORDER BY nombre_rtp ASC");

        $row_control_paquete = $conexion->buscar('tbl_orden_produccion','id_op',$mi_array['int_op_tn']); 
 
        $row_codigo_empleado = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(7,9) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC'); 
         
        $row_revisor = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(7,9) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
 
        $row_tiquete_num = $conexion->llenarCampos("tbl_numeracion", "WHERE int_op_n=".$mi_array['int_op_tn']." ", "", "*");


 
         //MUESTRA PAQUETES X CAJA
         $select_tiquete_num = $conexion->llenarCampos('tbl_tiquete_numeracion',"WHERE int_op_tn='".$mi_array['int_op_tn']."' ",' ORDER BY int_caja_tn DESC, int_paquete_tn DESC LIMIT 1',"id_tn,int_paquete_tn,int_caja_tn,int_hasta_tn,contador_tn,imprime");// AND int_caja_tn='".$mi_array['int_caja_tn']."' ORDER BY int_caja_tn DESC, int_paquete_tn DESC //Modificado 18-05-2022

        /*$paqueteF=$row_tiquete_num['int_paquete_n']=='' ? $select_tiquete_num['int_paquete_tn'] : $row_tiquete_num['int_paquete_n'];//se agrega para al cargar la vista este sume los faltantes al HASTA

        $faltantes = $conexion->llenarCampos("tbl_faltantes", "WHERE id_op_f=".$mi_array['int_op_tn']." AND int_caja_f='".$mi_array['int_caja_tn']."' AND int_paquete_f='".$paqueteF."' ", "", " SUM(int_total_f) as totalf ");*/
        
        
 
        /*if($row_control_paquete['int_cod_ref_op']=='096'){
            $id_op=$row_control_paquete['id_op'];
            $int_caja_t=($row_tiquete_num['int_caja_n']);
            $paqxca=($row_tiquete_num['int_undxcaja_n']/$row_tiquete_num['int_undxpaq_n']);    

            header ("Location: sellado_control_numeracion_edit_paqxcaja.php?id_op=$id_op&int_caja_tn=$int_caja_t&NumeroPaqxCaja=$paqxca&contador=1");
          }*/

        require_once("views/view_sellado_numeracion.php");
    }


 public function Menu(){ 
     $ordenc = new oMsellado();
     //$this->ordenc=$ordenc->get_Menu();//aqui llamo las funciones del modelo
     $vista = 'sellado_numeracion_listado.php';
     self::Msellado($vista);
 }


 public function Crud(){

  $proformas = new oMsellado(); 
  if(isset($_REQUEST['id'])){
         $proveedores = new oMsellado();//instanciamos la clase oMsellado del Modelo Msellado
         $insumo = new oMsellado(); 
         $maquina = new oMsellado();
         $general = new oMsellado();
         $proformasPrincipal = new oMsellado();
         $proformas = new oMsellado();
         $factura = new oMsellado();
         $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
         $this->insumo=$insumo->get_Insumo();
         $this->maquina=$maquina->get_Maquina();
         $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='ENTRADA FACTURA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
         if(!$this->general)
           $this->general=$general->Obtener(' tbl_proceso_compras pc',"pc.proceso='PROFORMA' AND pc.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
         
         $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);

         if(!$this->proformasPrincipal)
         $this->proformasPrincipal=$proformasPrincipal->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
           
         
    $this->proformas=$proformas->Obtener(' tbl_proceso_compras pc LEFT JOIN tbl_proceso_compras_detalle pcd ON pc.proforma=pcd.proforma',"pc.proceso='PROFORMA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
         $this->factura=$factura->Obtener(' tbl_proceso_compras_detalle pcd',"pcd.proceso='ENTRADA FACTURA' AND pcd.". $_REQUEST['columna'] ." ",$_REQUEST['id']);
  }

     self::Msellado();//le digo que muestre en vista edit
 }


 public function Guardar($vista=''){
 
         $this->proformas = new oMsellado();
         $this->tiquetes = new oMsellado();  
         $this->proforma = $_REQUEST;
         $row_control_paquete = new oMsellado();
 

         $row_control_paquete = $this->proformas->buscarTres('tbl_tiquete_numeracion',"id_tn,int_op_tn,int_bolsas_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn,int_cod_empleado_tn,int_cod_rev_tn,contador_tn,int_paquete_tn,int_caja_tn,ref_tn","  WHERE int_op_tn= '".$_REQUEST['int_op_tn']."'  AND int_caja_tn='".$_REQUEST['int_caja_tn']."' AND  int_paquete_tn= '".$_REQUEST['int_paquete_tn']."' ", "ORDER BY int_caja_tn DESC, int_paquete_tn DESC LIMIT 1"); 
          //si no exit el paquete pues lo registra
          if($row_control_paquete['id_tn']==''){ 

             $result = $this->proformas->Registrar("tbl_tiquete_numeracion","int_op_tn, fecha_ingreso_tn, hora_tn, int_bolsas_tn, int_undxpaq_tn, int_undxcaja_tn,  int_desde_tn, int_hasta_tn, int_cod_empleado_tn, int_cod_rev_tn, contador_tn, int_paquete_tn, int_caja_tn, pesot, ref_tn, imprime ", $this->proforma);
              
             $row_control_paquete = $this->proformas->buscarTres("tbl_tiquete_numeracion ","id_tn,int_op_tn,int_caja_tn", " WHERE int_op_tn= '".$_REQUEST['int_op_tn']."'  AND int_caja_tn='".$_REQUEST['int_caja_tn']."' AND  int_paquete_tn= '".$_REQUEST['int_paquete_tn']."'  LIMIT 1  " );
             //consulto para optenes id_tn para los faltantes
 

             $this->proforma['id_tn'] = $row_control_paquete["id_tn"];//para faltantes

             $guardaFalt = self::GuardarFaltante($this->proforma);

            //ACTUALIZO tbl_numeracion 
            $updatNumeracion = self::Actualizar("tbl_numeracion","id_tn_n = '". $row_control_paquete["id_tn"] ."', cod_ref_n = '". $_REQUEST['ref_tn'] ."', int_bolsas_n = '". $_REQUEST['int_bolsas_tn'] ."',int_undxpaq_n = '". $_REQUEST['int_undxpaq_tn'] ."',int_undxcaja_n = '". $_REQUEST['int_undxcaja_tn'] ."',int_desde_n = '". $_REQUEST['int_desde_tn'] ."',int_hasta_n = '". $_REQUEST['int_hasta_tn'] ."',int_cod_empleado_n = '". $_REQUEST['int_cod_empleado_tn'] ."',int_cod_rev_n = '". $_REQUEST['int_cod_rev_tn'] ."', contador_n='".$_REQUEST['contador_tn']."', int_paquete_n = '". $_REQUEST['int_paquete_tn'] ."',int_caja_n = '". $_REQUEST['int_caja_tn'] ."' ", " WHERE int_op_n = '". $_REQUEST['int_op_tn'] ."'");
               $this->proforma = new oMsellado();
              
                }else{

                    $this->proforma = $row_control_paquete;//actualizo con el modelo Consultado
                   //ACTUALIZO tbl_tiquete_numeracion
                   $Updateresult=self::Actualizar("tbl_tiquete_numeracion ", " int_op_tn='".$_REQUEST['int_op_tn']."',fecha_ingreso_tn='".$_REQUEST['fecha_ingreso_tn']."',hora_tn='".$_REQUEST['hora_tn']."',int_bolsas_tn='".$_REQUEST['int_bolsas_tn']."',int_undxpaq_tn='".$_REQUEST['int_undxpaq_tn']."',int_undxcaja_tn='".$_REQUEST['int_undxcaja_tn']."',int_desde_tn='".$_REQUEST['int_desde_tn']."',int_hasta_tn='".$_REQUEST['int_hasta_tn']."',int_cod_empleado_tn='".$_REQUEST['int_cod_empleado_tn']."',int_cod_rev_tn='".$_REQUEST['int_cod_rev_tn']."',contador_tn='".$_REQUEST['contador_tn']."',int_paquete_tn='".$_REQUEST['int_paquete_tn']."',int_caja_tn='".$_REQUEST['int_caja_tn']."',pesot='".$_REQUEST['pesot']."',ref_tn='".$_REQUEST['ref_tn']."',  imprime='".$_REQUEST['tienefaltantes']."' ", " WHERE id_tn=".$row_control_paquete['id_tn']." ", "id_tn", "int_op_tn", "int_caja_tn", $this->proforma);


           }
          
         

        //CONSULTAS DE PAQUETES O CAJAS
        //$this->proformas->Consulta("tbl_tiquete_numeracion ","id_tn",$_REQUEST['id_tn'],"","","");//Consulto registro principal

        $resultadoregis =  $this->tiquetes->Consulta("tbl_tiquete_numeracion ","id_tn",$row_control_paquete['id_tn'],"","","");//Consulto registro principal
 
            
         
        require_once("views/view_sellado_numeracion.php");   
 }



 public function GuardarAdd($vista=''){
 
         $this->proformas = new oMsellado();
         $this->tiquetes = new oMsellado();  
         $this->proforma = $_REQUEST;
         $row_control_paquete = new oMsellado();
 
         
         $result = $this->proformas->Registrar("tbl_tiquete_numeracion","int_op_tn, fecha_ingreso_tn, hora_tn, int_bolsas_tn, int_undxpaq_tn, int_undxcaja_tn,  int_desde_tn, int_hasta_tn, int_cod_empleado_tn, int_cod_rev_tn, contador_tn, int_paquete_tn, int_caja_tn, pesot, ref_tn, imprime ", $this->proforma);

         $row_control_paquete = $this->proformas->buscarTres("tbl_tiquete_numeracion ","id_tn,int_op_tn,int_caja_tn", " WHERE int_op_tn= '".$_REQUEST['int_op_tn']."'  AND int_caja_tn='".$_REQUEST['int_caja_tn']."' AND  int_paquete_tn= '".$_REQUEST['int_paquete_tn']."'  LIMIT 1  " ); 

         $this->proforma['id_tn'] = $row_control_paquete["id_tn"]; 

         self::GuardarFaltante($this->proforma);

         $this->proforma['id_tn_n'] = $row_control_paquete["id_tn"];
         $insertNumeracion = $this->proformas->RegistrarAdd("tbl_numeracion","id_tn_n, fecha_ingreso_n, int_op_n, cod_ref_n, int_bolsas_n, int_undxpaq_n, int_undxcaja_n,  int_desde_n, int_hasta_n, int_cod_empleado_n, int_cod_rev_n, contador_n, int_paquete_n, int_caja_n, b_borrado_n, existeTiq_n", $this->proforma);
      
/*         $compactada = $this->proformas->buscarTres('tbl_numeracion'," * ","  WHERE int_op_n= '".$_REQUEST['int_op_tn']."' ", ""); 
        

         $compactada=serialize($compactada); 

         $compactada=urlencode($compactada);
         header("Location:view_index.php?c=csellado&a=Numeracion&mi_var_array=$compactada"); */
         //require_once("views/view_sellado_numeracion.php");
 }



 public function GuardarFaltante($data){
        
         /*$this->proformas =  new oMsellado(); 
         $this->proforma = $_REQUEST;*/ 

         $this->proformas->RegistrarFaltantes("tbl_faltantes", "id_tn_f,id_op_f, int_paquete_f, int_caja_f, int_inicial_f, int_final_f, int_total_f,tipodesperdicio_f ",$data);

 
 }

 
 
 public function Consultar(){
          
          $this->tiquetes = new oMsellado(); 
         //MUESTRA PAQUETES X CAJA
     
          $this->tiquetes->Consulta("tbl_tiquete_numeracion ","int_op_tn",$_REQUEST['int_op_tn'],"int_caja_tn",$_REQUEST['int_caja_tn'], "ORDER BY int_caja_tn DESC,int_paquete_tn DESC ");//consulto paquetes
}

 

 public function ConsultarxId(){
          
          $this->tiquetes = new oMsellado(); 
         //MUESTRA PAQUETES X CAJA 

          $this->tiquetes->Consulta($_REQUEST['tabla'],$_REQUEST['columnna'],$_REQUEST['id'],"","",$_REQUEST['order']);//Consulto registro principal
       
}

 

 public function ConsultarUnSolo(){
          
          $this->tiquetes = new oMsellado(); 
         //MUESTRA PAQUETES X CAJA 
          $this->tiquetes->Consulta("tbl_tiquete_numeracion ","id_tn",$_REQUEST['id_tn'],"","","");//Consulto registro principal
}

 public function ConsultarTiquetxOP(){
          
          $this->tiquetes = new oMsellado(); 
         //MUESTRA PAQUETES X CAJA
     
          $this->tiquetes->Consulta("tbl_tiquete_numeracion ","int_op_tn",$_REQUEST['int_op_tn'],"","", " AND  imprime = '1'  GROUP BY int_caja_tn ORDER BY int_caja_tn DESC ");//consulto paquetes
}


 public function ConsultarFaltantes(){
          
          $this->tiquetes = new oMsellado(); 
         //MUESTRA FALTANTES X PAQUETE
     
          $this->tiquetes->Consulta("tbl_faltantes","id_tn_f",$_REQUEST['id_tn'],"","","");//consulto faltantes
}




 public function ConsultarOP(){
          
          $this->tiquetes = new oMsellado(); 
         //MUESTRA PAQUETES X CAJA 
          $this->tiquetes->Consulta("tbl_orden_produccion","id_op",$_REQUEST['int_op_tn'],"","", "ORDER BY id_op DESC ");//consulto paquetes
}


 public function ConsultarAdd(){
          
          $this->opes = new oMsellado();
          $this->tiquetes = new oMsellado(); 
         //MUESTRA PAQUETES X CAJA  
          $consulta = $this->opes->buscarTres("tbl_orden_produccion","int_cod_ref_op","  WHERE id_op= '".$_REQUEST['int_op_tn']."' ","ORDER BY id_op DESC ");//consulto paquetes
        
          $ref=$consulta["int_cod_ref_op"];
          $this->tiquetes->ConsultaPaquetes("tbl_orden_produccion op LEFT JOIN tbl_tiquete_numeracion tn  ON tn.ref_tn = op.int_cod_ref_op ","tn.ref_tn",$ref,"","", "ORDER BY CONVERT(tn.int_hasta_tn, SIGNED INTEGER) DESC LIMIT 1 ");//consulto paquetes desde ADD sellado

} 


 public function Eliminar(){
      
         
          $this->proformas =  new oMsellado();
          $faltantes =  new oMsellado();  
          $this->proforma = $_REQUEST;
          $consulta = new oMsellado();

          $this->proformas->Delete("tbl_tiquete_numeracion", $_REQUEST['id'],$_REQUEST['columna'],$_REQUEST['op'],$_REQUEST['caja']);
          
          //Consulto ultimo registro para actualizar tbl_numeracion
          $consulta = $this->proformas->buscarTres('tbl_tiquete_numeracion',"id_tn,int_op_tn,int_bolsas_tn,int_undxpaq_tn,int_undxcaja_tn,int_desde_tn,int_hasta_tn,int_cod_empleado_tn,int_cod_rev_tn,contador_tn,int_paquete_tn,int_caja_tn,ref_tn","  WHERE int_op_tn= '".$_REQUEST['op']."'   ", " ORDER BY int_caja_tn DESC,int_paquete_tn DESC LIMIT 1 "); // ORDER BY fecha_ingreso_tn DESC, hora_tn DESC, int_caja_tn DESC,int_paquete_tn DESC LIMIT 1
          //modificado 17/05/2022
       

          $resultdelet = $faltantes->DeleteFaltantes("tbl_faltantes"," id_tn_f= '". $_REQUEST['id'] ."' ");
         
 
          $updatNumeracion = self::Actualizar("tbl_numeracion","id_tn_n = '". $consulta["id_tn"] ."', cod_ref_n = '". $consulta['ref_tn'] ."', int_bolsas_n = '". $consulta['int_bolsas_tn'] ."',int_undxpaq_n = '". $consulta['int_undxpaq_tn'] ."',int_undxcaja_n = '". $consulta['int_undxcaja_tn'] ."',int_desde_n = '". $consulta['int_desde_tn'] ."',int_hasta_n = '". $consulta['int_hasta_tn'] ."',int_cod_empleado_n = '". $consulta['int_cod_empleado_tn'] ."',int_cod_rev_n = '". $consulta['int_cod_rev_tn'] ."',contador_n='".$consulta['contador_tn']."', int_paquete_n = '". $consulta['int_paquete_tn'] ."',int_caja_n = '". $consulta['int_caja_tn'] ."' ", " WHERE int_op_n = '". $consulta['int_op_tn'] ."'");
          //agregado 03/08/2022
          
          if($consulta==''){
       
             $this->proformas->Delete("tbl_numeracion", $_REQUEST['op'],"int_op_n","","");

          }
          
           
            $this->proformas->Consulta("tbl_tiquete_numeracion ","id_tn",$consulta["id_tn"],"");//Consulto registro principal
        
           //require_once("views/view_sellado_numeracion.php"); 
           //header("Location:view_index.php?c=csellado&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
 }

  public function EliminaCajas(){ 
      
           $this->eliminafaltante =  new oMsellado();
           $this->eliminaPrincipal =  new oMsellado();
           $this->modelosellado =  new oMsellado();
           $this->row_control_paquete = new oMsellado();
           $this->proformas =  new oMsellado();
           $consulta = new oMsellado();

          $this->eliminafaltante->DeleteFaltantes("tbl_faltantes"," id_op_f= '". $_REQUEST['idop'] ."' AND  int_caja_f = '". $_REQUEST['caja'] ."' "); 

          $oktiquete = $this->eliminaPrincipal->DeleteFaltantes("tbl_tiquete_numeracion"," int_op_tn= '". $_REQUEST['idop'] ."' AND  int_caja_tn = '". $_REQUEST['caja'] ."' "); 
          
          
        
          //$quedanregistros = $this->row_control_paquete->buscarTres('tbl_tiquete_numeracion',"int_op_tn","  WHERE int_op_tn= '".$_REQUEST['idop']."'  AND int_caja_tn='".$_REQUEST['caja']."' ", "");
   
           // if($quedanregistros['int_op_tn'] != '' ) {
 
             
             //ULTIMA CAJA
            $consulta = $this->row_control_paquete->buscarTres('tbl_tiquete_numeracion'," * ","  WHERE int_op_tn= '".$_REQUEST['idop']."' ", "ORDER BY  int_caja_tn DESC,int_paquete_tn DESC LIMIT 1"); 


            //ACTUALIZA CAJA PARA EL LISTADO Y ACTUALIZO LA NUMERACION FINAL
             if($consulta["id_tn"] !=''){
               $updatNumeracion = self::Actualizar("tbl_numeracion","id_tn_n = '". $consulta["id_tn"] ."', cod_ref_n = '". $consulta['ref_tn'] ."', int_bolsas_n = '". $consulta['int_bolsas_tn'] ."',int_undxpaq_n = '". $consulta['int_undxpaq_tn'] ."',int_undxcaja_n = '". $consulta['int_undxcaja_tn'] ."',int_desde_n = '". $consulta['int_desde_tn'] ."',int_hasta_n = '". $consulta['int_hasta_tn'] ."',int_cod_empleado_n = '". $consulta['int_cod_empleado_tn'] ."',int_cod_rev_n = '". $consulta['int_cod_rev_tn'] ."',contador_n='".$consulta['contador_tn']."',int_paquete_n = '". $consulta['int_paquete_tn'] ."',int_caja_n = '". $consulta['int_caja_tn'] ."' ", " WHERE int_op_n = '". $_REQUEST['idop'] ."'", "id_n", "int_op_n", "int_caja_n", $this->row_control_paquete);    
             }
          /*}else{

            $sqlexit=$this->modelosellado->DeleteFaltantes("tbl_numeracion"," int_op_n= '". $_REQUEST['idop'] ."' AND  int_caja_n = '". $_REQUEST['caja'] ."' "); 

          }*/

       
 
           //para recargar pagina y cerrar popUp
          if($consulta["id_tn"]!=''){
              echo $oktiquete; //resultado de eliminacion 1
          }else{
          
             $this->proformas->Delete("tbl_numeracion", $_REQUEST['idop'],"int_op_n","","");
             echo $oktiquete=2; //resultado de eliminacion 1
             //header("Location:view_index.php?c=csellado&a=Inicio"); 
          } 
       

  }


  public function Actualizar($tabla,$sets,$condicion,$columna="",$columna2="",$columna3="",$data=""){
           $this->proforma = new oMsellado();
      
           $this->proformas->Update($tabla,$sets,$condicion,$columna,$columna2,$columna3,$data);  
            //header("Location:view_index.php?c=comprasFA&a=Crud&columna=". $_REQUEST['columna'] ."&id=". $_REQUEST['id'] ." ");  
  }

    public function Msellado($vista=''){ 
        if($vista){ 
          require_once("views/".$vista);  
        }
        else{ 
        require_once("views/view_sellado_numeracion.php");
        }
    }
/*
    public function listadonormal(){ 
      require_once("views/view_compras_em.php?id=".$_REQUEST['id'].'&columna=id_pedido&tabla=tbl_orden_compra_historico' );
    }*/


}



?>
