<?php
//Llamada al modelo
require_once("Models/Mmezclas.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oMmezclas();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras_em.php");*/

class cmezclasController{

    private $ordenc;
    private $proveedores;
    private $insumo;
    private $mezclas;
    public function __CONSTRUCT(){
        $ordenc = new oMmezclas();
    }

    public function Index(){ 
        $ordenc = new oMmezclas();//instanciamos la clase oMmezclas del Modelo ViewMezclas
        self::ViewMezclas();
    }
 
    public function Exterior(){ 
        $proveedores = new oMmezclas();//instanciamos la clase oMmezclas del Modelo ViewMezclas
        $insumo = new oMmezclas();
        $ordenc = new oMmezclas();
        $maquina = new oMmezclas();

        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        $this->maquina=$maquina->get_Maquina();
 
        self::ViewMezclas();
    }

    public function Salir(){ 
        $ordenc = new oMmezclas();
        $vista = 'produccion_registro_extrusion_listado.php';
        self::ViewMezclas($vista);
    }


    public function Mezcla(){ 
            $mezclas = new oMmezclas(); 
            if(isset($_REQUEST['cod_ref'])){
        
                $maquinas = new oMmezclas();
                $row_mezcla = new oMmezclas();
                $row_materia_prima = new oMmezclas();
                $row_caract = new oMmezclas();
                $row_editar_m = new oMmezclas();
                $row_referencia = new oMmezclas();
                $row_referencia_copia = new oMmezclas();
                if($_REQUEST['cod_refcopia']){
                    $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=1 AND cp.cod_ref ", "".$_REQUEST['cod_refcopia']."");
                    $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=1 AND int_cod_ref_pm ", "".$_REQUEST['cod_refcopia'].""); 
                }else{
                    $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=1 AND cp.cod_ref ", "".$_REQUEST['cod_ref']."");
                    $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=1 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
                }
 
                $this->row_materia_prima=$row_materia_prima->get_materiaPrima('insumo'," WHERE clase_insumo='4' AND estado_insumo='0' "," ORDER BY descripcion_insumo ASC" );

                $this->row_referencia_copia=$row_referencia_copia->get_CopiaRef('tbl_produccion_mezclas pm '," WHERE id_proceso=1 "," ORDER BY CONVERT(int_cod_ref_pm, SIGNED INTEGER) DESC" );
        
                $this->row_referencia=$row_referencia->Obtener("tbl_referencia,tbl_egp", "tbl_referencia.estado_ref=1 AND tbl_referencia.n_egp_ref=tbl_egp.n_egp AND tbl_referencia.cod_ref ",  "".$_REQUEST['cod_ref'].""); 

                $this->row_editar_m = $row_editar_m->Obtener('tbl_produccion_mezclas',"id_proceso=1 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
                 

                $this->maquinas = $maquinas->get_Maquina(); 

                 /* echo 
                  var_dump($this->maquinas);die;*/
        
            }
         
                header('Location:view_index.php?c=cmezclas&a=Carat&cod_ref='.$_REQUEST['cod_ref']); 
                //self::ViewMezclas("view_index.php?c=cmezclas&a=Carat&cod_ref=".$_REQUEST['cod_ref']);
    }


    public function Carat(){

        $mezclas = new oMmezclas(); 
        if(isset($_REQUEST['cod_ref'])){
  
            $maquinas = new oMmezclas();
            $row_mezcla = new oMmezclas();
            $row_materia_prima = new oMmezclas();
            $row_caract = new oMmezclas();
            $row_editar_m = new oMmezclas();
            $row_referencia = new oMmezclas();
            $row_referencia_copia = new oMmezclas(); 

            if($_REQUEST['cod_refcopia']){
                $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=1 AND cp.cod_ref ", "".$_REQUEST['cod_refcopia']."");
                $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=1 AND int_cod_ref_pm ", "".$_REQUEST['cod_refcopia'].""); 
            }else{
                $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=1 AND cp.cod_ref ", "".$_REQUEST['cod_ref']."");
                $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=1 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
            }

            $this->row_materia_prima=$row_materia_prima->get_materiaPrima('insumo'," WHERE clase_insumo='4' AND estado_insumo='0' "," ORDER BY descripcion_insumo ASC" );

            $this->row_referencia_copia=$row_referencia_copia->get_CopiaRef('tbl_produccion_mezclas pm '," WHERE id_proceso=1 "," ORDER BY CONVERT(int_cod_ref_pm, SIGNED INTEGER) DESC" );
 
            $this->row_referencia=$row_referencia->Obtener("tbl_referencia,tbl_egp", "tbl_referencia.estado_ref=1 AND tbl_referencia.n_egp_ref=tbl_egp.n_egp AND tbl_referencia.cod_ref ",  "".$_REQUEST['cod_ref']."");
 
            $this->row_editar_m = $row_editar_m->Obtener('tbl_produccion_mezclas',"id_proceso=1 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
 
            $this->maquinas = $maquinas->get_Maquina(); 
              



        }
           
           self::ViewCaract();
           //self::ViewMezclas('produccion_caract_extruder_mezcla_vista.php?cod_ref='.$_REQUEST['cod_ref']);//le digo que muestre en vista edit
    }


        public function Guardar(){

            $directorio = ROOT."pdfprocesocompras/";
            $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
                $porciones = explode(".", $nombre);
            $adjunto = "EXP". $_REQUEST['factura'] . "." . $porciones[1];
            $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
     
            //$mezclas = new oCompras();
            $this->mezclas =  new oMmezclas(); 
            $this->proforma = $_REQUEST;
            $this->proforma['adjunto']= $tieneadjunto1;  
            
            //guarda caracteristicas
            $this->mezclas->Registrar("tbl_caracteristicas_prod", "cod_ref,fecha_registro,usuario,modifico,fecha_modif,extrusora,proceso,campo_1,campo_2,campo_3,campo_4,campo_5,campo_6,campo_7,campo_8,campo_9,campo_10,campo_11,campo_12,campo_13,campo_14,campo_15,campo_16,campo_17,campo_18,campo_19,campo_20,campo_21,campo_22,campo_23,campo_24,campo_25,campo_26,campo_27,campo_28,campo_29,campo_30,campo_31,campo_32,campo_33,campo_34,campo_35,campo_36,campo_37,campo_38,campo_39,campo_40,campo_41,campo_42,campo_43,campo_44,campo_45,campo_46,campo_47,campo_48,campo_49,campo_50,campo_51,campo_52,campo_53,campo_54,campo_55,campo_56,campo_57,campo_58,campo_59,campo_60,campo_61,campo_62,campo_63,campo_64,campo_65,campo_66", "cod_ref",$_POST['cod_ref'],  $this->proforma);

            //actualiza mezclas
            self::GuardarMezcla();
  

            header('Location:view_index.php?c=cmezclas&a=Carat&cod_ref='.$_REQUEST['cod_ref']); 
        }

        public function GuardarMezcla(){

            $directorio = ROOT."pdfprocesocompras/";
            $nombre = str_replace(' ', '', $_FILES['adjunto']['name']);
                $porciones = explode(".", $nombre);
            $adjunto = "EXP". $_REQUEST['factura'] . "." . $porciones[1];
            $tieneadjunto1 = adjuntarArchivo('', $directorio, $adjunto,$_FILES['adjunto']['tmp_name'],'NUEVOS');
        
            //$mezclas = new oCompras();
            $this->mezclas =  new oMmezclas(); 
            $this->proforma = $_REQUEST;
            $this->proforma['adjunto']= $tieneadjunto1;  

            $this->mezclas->RegistrarMezclas("tbl_produccion_mezclas", "id_proceso,fecha_registro_pm,str_registro_pm,id_ref_pm,int_cod_ref_pm,version_ref_pm,int_ref1_tol1_pm,int_ref1_tol1_porc1_pm,int_ref2_tol1_pm,int_ref2_tol1_porc2_pm,int_ref3_tol1_pm,int_ref3_tol1_porc3_pm,int_ref1_tol2_pm,int_ref1_tol2_porc1_pm,int_ref2_tol2_pm,int_ref2_tol2_porc2_pm,int_ref3_tol2_pm,int_ref3_tol2_porc3_pm,int_ref1_tol3_pm,int_ref1_tol3_porc1_pm,int_ref2_tol3_pm,int_ref2_tol3_porc2_pm,int_ref3_tol3_pm,int_ref3_tol3_porc3_pm,int_ref1_tol4_pm,int_ref1_tol4_porc1_pm,int_ref2_tol4_pm,int_ref2_tol4_porc2_pm,int_ref3_tol4_pm,int_ref3_tol4_porc3_pm,int_ref1_rpm_pm,int_ref1_tol5_porc1_pm,int_ref2_rpm_pm,int_ref2_tol5_porc2_pm,int_ref3_rpm_pm,int_ref3_tol5_porc3_pm,extrusora_mp,observ_pm,b_borrado_pm", "int_cod_ref_pm",$_POST['cod_ref'],  $this->proforma); 
                   
            header('Location:view_index.php?c=cmezclas&a=Mezcla&cod_ref='.$_REQUEST['cod_ref']); 
        }


        public function Update($vista=''){ 
 
 
            $this->mezclas =  new oMmezclas(); 
            $this->mezcla = $_REQUEST;
            //$this->mezcla['adjunto']= $tieneadjunto1; 
            $resulrt = $conexion->Actualizar("tbl_produccion_mezclas", "fecha_registro_cv='".$_REQUEST['fecha_registro_cv']."',str_registro_cv='".$_REQUEST['str_registro_cv']."',campo_1='".$_REQUEST['campo_1']."',campo_2='".$_REQUEST['campo_2']."',campo_3='".$_REQUEST['campo_3']."',campo_4='".$_REQUEST['campo_4']."',campo_5='".$_REQUEST['campo_5']."',campo_6='".$_REQUEST['campo_6']."',campo_7='".$_REQUEST['campo_7']."',campo_8='".$_REQUEST['campo_8']."',campo_9='".$_REQUEST['campo_9']."',campo_10='".$_REQUEST['campo_10']."',campo_11='".$_REQUEST['campo_11']."',campo_12='".$_REQUEST['campo_12']."',campo_13='".$_REQUEST['campo_13']."',campo_14='".$_REQUEST['campo_14']."',campo_15='".$_REQUEST['campo_15']."',campo_16='".$_REQUEST['campo_16']."',campo_17='".$_REQUEST['campo_17']."',campo_18='".$_REQUEST['campo_18']."',campo_19='".$_REQUEST['campo_19']."',campo_20='".$_REQUEST['campo_20']."',campo_21='".$_REQUEST['campo_21']."',campo_22='".$_REQUEST['campo_22']."',campo_23='".$_REQUEST['campo_23']."',campo_24='".$_REQUEST['campo_24']."',campo_25='".$_REQUEST['campo_25']."',campo_26='".$_REQUEST['campo_26']."',campo_27='".$_REQUEST['campo_27']."',campo_28='".$_REQUEST['campo_28']."',campo_29='".$_REQUEST['campo_29']."',campo_30='".$_REQUEST['campo_30']."',campo_31='".$_REQUEST['campo_31']."',campo_32='".$_REQUEST['campo_32']."',campo_33='".$_REQUEST['campo_33']."',campo_34='".$_REQUEST['campo_34']."',campo_35='".$_REQUEST['campo_35']."',campo_36='".$_REQUEST['campo_36']."',campo_37='".$_REQUEST['campo_37']."',campo_38='".$_REQUEST['campo_38']."',campo_39='".$_REQUEST['campo_39']."',campo_40='".$_REQUEST['campo_40']."',campo_41='".$_REQUEST['campo_41']."',campo_42='".$_REQUEST['campo_42']."',campo_43='".$_REQUEST['campo_43']."',campo_44='".$_REQUEST['campo_44']."',campo_45='".$_REQUEST['campo_45']."',campo_46='".$_REQUEST['campo_46']."',campo_47='".$_REQUEST['campo_47']."',campo_48='".$_REQUEST['campo_48']."',campo_49='".$_REQUEST['campo_49']."',campo_50='".$_REQUEST['campo_50']."',campo_51='".$_REQUEST['campo_51']."',campo_52='".$_REQUEST['campo_52']."',campo_53='".$_REQUEST['campo_53']."',campo_54='".$_REQUEST['campo_54']."',campo_55='".$_REQUEST['campo_55']."'campo_56 = '".$_REQUEST['campo_56']."',campo_57 = '".$_REQUEST['campo_57']."',campo_58 = '".$_REQUEST['campo_58']."',campo_59 = '".$_REQUEST['campo_59']."',campo_60 = '".$_REQUEST['campo_60']."',campo_61 = '".$_REQUEST['campo_61']."',campo_62 = '".$_REQUEST['campo_62']."',campo_63 = '".$_REQUEST['campo_63']."',campo_64 = '".$_REQUEST['campo_64']."',campo_65 = '".$_REQUEST['campo_65']."',campo_66 = '".$_REQUEST['campo_66']."' ", " id_pm='".$_REQUEST['id_pm']."'", $this->mezcla);
            //self::ViewMezclas($vista);

        }
     

    public function ViewCaract($vista=''){ 
        if($vista){
          require_once("views/".$vista);  
        }
        else{  
            require_once("views/produccion_caract_extruder_add.php" ); 
        }
    }

    public function ViewMezclas($vista=''){ 
        if($vista){ 
        
          require_once($vista);  
        }
        else{ 
           require_once("views/produccion_caract_extruder_mezcla_vista.php" );
           //require_once("views/produccion_caract_extruder_add.php" );
        }
    }
 

}



?>
