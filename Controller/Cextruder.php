<?php
//Llamada al modelo
require_once("Models/Mextruder.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oMextruder();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras_em.php");*/

class cextruderController{

    private $ordenc;
    private $proveedores;
    private $insumo;
    private $mezclas;
    public function __CONSTRUCT(){
        $ordenc = new oMextruder();
    }

    public function Index(){ 
        $ordenc = new oMextruder();//instanciamos la clase oMextruder del Modelo ViewExtruder
        self::ViewExtruder();
    }
 
    public function Exterior(){ 
        $proveedores = new oMextruder();//instanciamos la clase oMextruder del Modelo ViewExtruder
        $insumo = new oMextruder();
        $ordenc = new oMextruder();
        $maquina = new oMextruder();

        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        $this->maquina=$maquina->get_Maquina();
 
        self::ViewExtruder();
    }

    public function Salir(){ 
        $ordenc = new oMextruder();
        $vista = 'produccion_registro_extrusion_listado.php';
        self::ViewExtruder($vista);
    }


        public function Extruder(){ 
                $mezclas = new oMextruder(); 
                if(isset($_REQUEST['id_rp'])){
             
                    $idop =$_REQUEST['id_op_rp'];
                    $id=$_REQUEST['id_rp'];
                  
                    header('Location: '."produccion_registro_extrusion_vista.php?id_op_rp=$idop&id_rp=$id");
                    //self::ViewExtruder($vista);
                  }
              
       }
  

 

        public function Guardar(){
 
           
            $this->form =  new oMextruder(); 
            $this->proforma = $_REQUEST;  
            
            //guarda caracteristicas
 
            $result = $this->form->Registrar("tbl_reg_produccion", "id_rp,id_proceso_rp, id_op_rp,id_ref_rp, int_cod_ref_rp, version_ref_rp,rollo_rp, int_kilos_prod_rp, int_kilos_desp_rp, int_total_kilos_rp, porcentaje_op_rp, int_metro_lineal_rp, int_total_rollos_rp, total_horas_rp, rodamiento_rp, horas_muertas_rp, horas_prep_rp, str_maquina_rp, str_responsable_rp,fecha_ini_rp, fecha_fin_rp, int_kilosxhora_rp, int_cod_empleado_rp, int_cod_liquida_rp, costo, parcial", "id_rp",$_POST['id_rp'],  $this->proforma);

            //actualiza form
             self::GuardarMezcla();
             self::ActualizaOp($_REQUEST['id_op_rp']);
            
            if($result && $_REQUEST['MM_insert']=="MM_update"){
                 
               header('Location:view_index.php?c=cextruder&a=Extruder&id_op_rp='.$_REQUEST['id_op_rp'].'&id_rp='.$_REQUEST['id_rp']); 
   
             }else{
               
            header('Location:'."produccion_registro_extrusion_edit.php?id_op=".$_REQUEST['id_op_rp']."&id_rp=".$_REQUEST['id_rp']."&tipo=1 "); 
             }
        }

        public function GuardarMezcla(){
 
            $this->form =  new oMextruder(); 
            $this->proforma = $_REQUEST; 
            
            $this->form->RegistrarMezclas("tbl_produccion_mezclas", "int_ref1_rpm_pm,int_ref1_tol5_porc1_pm,int_ref2_rpm_pm,int_ref2_tol5_porc2_pm,int_ref3_rpm_pm,int_ref3_tol5_porc3_pm", "id_pm",$_POST['id_pm'],  $this->proforma); 
                   
            //header('Location:view_index.php?c=cextruder&a=Extruder&id_op_rp='.$_REQUEST['id_op_rp'].'&id_rp='.$_REQUEST['id_rp']); 
        }

        public function ActualizaOp($op){
        
            $this->form =  new oMextruder(); 
            $this->proforma = $_REQUEST; 
            $estado = $_REQUEST['estado'];
            $this->form->Update("UPDATE tbl_orden_produccion SET b_estado_op='$estado', b_visual_op='0' WHERE id_op=$op");  
        }

        public function Update($vista=''){ 
  
 
            $this->mezclas =  new oMextruder(); 
            $this->mezcla = $_REQUEST;
            //$this->mezcla['adjunto']= $tieneadjunto1; 
            $resulrt = $conexion->Actualizar("tbl_produccion_mezclas", "fecha_registro_cv='".$_REQUEST['fecha_registro_cv']."',str_registro_cv='".$_REQUEST['str_registro_cv']."',campo_1='".$_REQUEST['campo_1']."',campo_2='".$_REQUEST['campo_2']."',campo_3='".$_REQUEST['campo_3']."',campo_4='".$_REQUEST['campo_4']."',campo_5='".$_REQUEST['campo_5']."',campo_6='".$_REQUEST['campo_6']."',campo_7='".$_REQUEST['campo_7']."',campo_8='".$_REQUEST['campo_8']."',campo_9='".$_REQUEST['campo_9']."',campo_10='".$_REQUEST['campo_10']."',campo_11='".$_REQUEST['campo_11']."',campo_12='".$_REQUEST['campo_12']."',campo_13='".$_REQUEST['campo_13']."',campo_14='".$_REQUEST['campo_14']."',campo_15='".$_REQUEST['campo_15']."',campo_16='".$_REQUEST['campo_16']."',campo_17='".$_REQUEST['campo_17']."',campo_18='".$_REQUEST['campo_18']."',campo_19='".$_REQUEST['campo_19']."',campo_20='".$_REQUEST['campo_20']."',campo_21='".$_REQUEST['campo_21']."',campo_22='".$_REQUEST['campo_22']."',campo_23='".$_REQUEST['campo_23']."',campo_24='".$_REQUEST['campo_24']."',campo_25='".$_REQUEST['campo_25']."',campo_26='".$_REQUEST['campo_26']."',campo_27='".$_REQUEST['campo_27']."',campo_28='".$_REQUEST['campo_28']."',campo_29='".$_REQUEST['campo_29']."',campo_30='".$_REQUEST['campo_30']."',campo_31='".$_REQUEST['campo_31']."',campo_32='".$_REQUEST['campo_32']."',campo_33='".$_REQUEST['campo_33']."',campo_34='".$_REQUEST['campo_34']."',campo_35='".$_REQUEST['campo_35']."',campo_36='".$_REQUEST['campo_36']."',campo_37='".$_REQUEST['campo_37']."',campo_38='".$_REQUEST['campo_38']."',campo_39='".$_REQUEST['campo_39']."',campo_40='".$_REQUEST['campo_40']."',campo_41='".$_REQUEST['campo_41']."',campo_42='".$_REQUEST['campo_42']."',campo_43='".$_REQUEST['campo_43']."',campo_44='".$_REQUEST['campo_44']."',campo_45='".$_REQUEST['campo_45']."',campo_46='".$_REQUEST['campo_46']."',campo_47='".$_REQUEST['campo_47']."',campo_48='".$_REQUEST['campo_48']."',campo_49='".$_REQUEST['campo_49']."',campo_50='".$_REQUEST['campo_50']."',campo_51='".$_REQUEST['campo_51']."',campo_52='".$_REQUEST['campo_52']."',campo_53='".$_REQUEST['campo_53']."',campo_54='".$_REQUEST['campo_54']."',campo_55='".$_REQUEST['campo_55']."'campo_56 = '".$_REQUEST['campo_56']."',campo_57 = '".$_REQUEST['campo_57']."',campo_58 = '".$_REQUEST['campo_58']."',campo_59 = '".$_REQUEST['campo_59']."',campo_60 = '".$_REQUEST['campo_60']."',campo_61 = '".$_REQUEST['campo_61']."',campo_62 = '".$_REQUEST['campo_62']."',campo_63 = '".$_REQUEST['campo_63']."',campo_64 = '".$_REQUEST['campo_64']."',campo_65 = '".$_REQUEST['campo_65']."',campo_66 = '".$_REQUEST['campo_66']."' ", " id_pm='".$_REQUEST['id_pm']."'", $this->mezcla);
            //self::ViewExtruder($vista);

        }
     

    public function ViewCaract($vista=''){ 
        if($vista){
          require_once("views/".$vista);  
        }
        else{  
            require_once("views/produccion_registro_extrusion_vista.php" ); 
        }
    }

    public function ViewExtruder($vista=''){ 
         
        if($vista){ 
           
          require_once($vista);  
        }
        else{ 
           require_once("produccion_registro_extrusion_vista.php" ); 
        }
    }
 

}



?>
