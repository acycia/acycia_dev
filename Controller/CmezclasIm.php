<?php
//Llamada al modelo
require_once("Models/MmezclasIm.php");
include('funciones/adjuntar.php'); 
/* 

$ordenc=new oMmezclasIm();
$datos=$ordenc->get_ordenc();
require_once("views/view_compras_em.php");*/

class cmezclasimController{

	private $ordenc;
    private $proveedores;
    private $insumo;
    private $mezclas;
	public function __CONSTRUCT(){
		$ordenc = new oMmezclasIm();
    }

    public function Index(){ 
    	$ordenc = new oMmezclasIm();//instanciamos la clase oMmezclasIm del Modelo ViewMezclas
    	self::ViewMezclas();
    }
 
    public function Exterior(){ 
        $proveedores = new oMmezclasIm();//instanciamos la clase oMmezclasIm del Modelo ViewMezclas
        $insumo = new oMmezclasIm();
        $ordenc = new oMmezclasIm();
        $maquina = new oMmezclasIm();

        $this->proveedores=$proveedores->get_Provee();//aqui llamo las funciones del modelo
        $this->insumo=$insumo->get_Insumo();
        $this->maquina=$maquina->get_Maquina();
 
        self::ViewMezclas();
    }

    public function Salir(){ 
        $ordenc = new oMmezclasIm();
        $vista = 'produccion_registro_extrusion_listado.php';
        self::ViewMezclas($vista);
    }


    public function Mezcla(){ 

            $mezclas = new oMmezclasIm(); 
            if(isset($_REQUEST['cod_ref'])){
        
                $maquinas = new oMmezclasIm();
                $row_mezcla = new oMmezclasIm();
                $anilox = new oMmezclasIm();
                $row_materia_prima = new oMmezclasIm();
                $row_caract = new oMmezclasIm();
                $row_editar_m = new oMmezclasIm();
                $row_referencia = new oMmezclasIm();
                $row_referencia_copia = new oMmezclasIm();
                if($_REQUEST['cod_refcopia']){
                    $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=2 AND cp.cod_ref ", "".$_REQUEST['cod_refcopia']."");
                    $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_refcopia'].""); 
                }else{
                    $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=2 AND cp.cod_ref ", "".$_REQUEST['cod_ref']."");
                    $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
                } 
 
                $this->row_materia_prima=$row_materia_prima->get_materiaPrima('insumo'," WHERE clase_insumo='8'  AND estado_insumo='0' "," ORDER BY descripcion_insumo ASC" );

                $this->row_referencia_copia=$row_referencia_copia->get_CopiaRef('tbl_produccion_mezclas pm '," WHERE id_proceso=2 "," ORDER BY CONVERT(int_cod_ref_pm, SIGNED INTEGER) DESC" );
        
                $this->row_referencia=$row_referencia->Obtener("tbl_referencia,tbl_egp", "tbl_referencia.estado_ref=1 AND tbl_referencia.n_egp_ref=tbl_egp.n_egp AND tbl_referencia.cod_ref ",  "".$_REQUEST['cod_ref'].""); 

                $this->row_editar_m = $row_editar_m->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
                 

                $this->maquinas = $maquinas->get_Maquina(); 

                $this->anilox = $anilox->get_Anilox(); 

                 /* echo 
                  var_dump($this->maquinas);die;*/
        
            }


         
                header('Location:view_index.php?c=cmezclasIm&a=Carat&cod_ref='.$_REQUEST['cod_ref']); 
                //self::ViewMezclas("view_index.php?c=cmezclasIm&a=Carat&cod_ref=".$_REQUEST['cod_ref']);
    }


    public function Carat(){

    	$mezclas = new oMmezclasIm(); 
    	if(isset($_REQUEST['cod_ref'])){
  
            $maquinas = new oMmezclasIm();
            $row_mezcla = new oMmezclasIm();
            $anilox = new oMmezclasIm();
            $row_materia_prima = new oMmezclasIm();
            $row_caract = new oMmezclasIm();
            $row_editar_m = new oMmezclasIm();
            $row_referencia = new oMmezclasIm();
            $row_referencia_copia = new oMmezclasIm(); 

            if($_REQUEST['cod_refcopia']){
                $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=2 AND cp.cod_ref ", "".$_REQUEST['cod_refcopia']."");
                $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_refcopia'].""); 
            }else{
                $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=2 AND cp.cod_ref ", "".$_REQUEST['cod_ref']."");
                $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
            }

            $this->row_materia_prima=$row_materia_prima->get_materiaPrima('insumo'," WHERE clase_insumo='8'  AND estado_insumo='0' "," ORDER BY descripcion_insumo ASC" );

            $this->row_referencia_copia=$row_referencia_copia->get_CopiaRef('tbl_produccion_mezclas pm '," WHERE id_proceso=2 "," ORDER BY CONVERT(int_cod_ref_pm, SIGNED INTEGER) DESC" );
 
            $this->row_referencia=$row_referencia->Obtener("tbl_referencia,tbl_egp", "tbl_referencia.estado_ref=1 AND tbl_referencia.n_egp_ref=tbl_egp.n_egp AND tbl_referencia.cod_ref ",  "".$_REQUEST['cod_ref']."");
 
            $this->row_editar_m = $row_editar_m->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
 
            $this->maquinas = $maquinas->get_Maquina(); 

            $this->anilox = $anilox->get_Anilox(); 
              



    	}
     
           self::ViewCaract();
           
    }

    public function Tintas(){

        $mezclas = new oMmezclasIm(); 
        if(isset($_REQUEST['cod_ref'])){
    
            $maquinas = new oMmezclasIm();
            $row_mezcla = new oMmezclasIm();
            $anilox = new oMmezclasIm();
            $row_materia_prima = new oMmezclasIm();
            $row_caract = new oMmezclasIm();
            $row_editar_m = new oMmezclasIm();
            $row_referencia = new oMmezclasIm();
            $row_referencia_copia = new oMmezclasIm(); 

            if($_REQUEST['cod_refcopia']){
                $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=2 AND cp.cod_ref ", "".$_REQUEST['cod_refcopia']."");
                $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_refcopia'].""); 
            }else{
                $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=2 AND cp.cod_ref ", "".$_REQUEST['cod_ref']."");
                $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
            }

            $this->row_materia_prima=$row_materia_prima->get_materiaPrima('insumo'," WHERE clase_insumo='8'  AND estado_insumo='0' "," ORDER BY descripcion_insumo ASC" );

            $this->row_referencia_copia=$row_referencia_copia->get_CopiaRef('tbl_produccion_mezclas pm '," WHERE id_proceso=2 "," ORDER BY CONVERT(int_cod_ref_pm, SIGNED INTEGER) DESC" );
    
            $this->row_referencia=$row_referencia->Obtener("tbl_referencia,tbl_egp", "tbl_referencia.estado_ref=1 AND tbl_referencia.n_egp_ref=tbl_egp.n_egp AND tbl_referencia.cod_ref ",  "".$_REQUEST['cod_ref']."");
    
            $this->row_editar_m = $row_editar_m->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
    
            $this->maquinas = $maquinas->get_Maquina(); 

            $this->anilox = $anilox->get_Anilox();  

        }
           
           self::ViewCaract("view_produccion_registro_tintas.php");
           
    }


    public function editaTintas(){
        $mezclas = new oMmezclasIm(); 
        if(isset($_REQUEST['cod_ref'])){
    
            $maquinas = new oMmezclasIm();
            $row_mezcla = new oMmezclasIm();
            $anilox = new oMmezclasIm();
            $row_materia_prima = new oMmezclasIm();
            $row_caract = new oMmezclasIm();
            $row_editar_m = new oMmezclasIm();
            $row_referencia = new oMmezclasIm();
            $row_referencia_copia = new oMmezclasIm(); 
            $row_totalTintas =  new oMmezclasIm(); 

            if($_REQUEST['cod_refcopia']){
                $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=2 AND cp.cod_ref ", "".$_REQUEST['cod_refcopia']."");
                $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_refcopia'].""); 
            }else{
                $this->row_caract = $row_caract->Obtener('tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref'," proceso=2 AND cp.cod_ref ", "".$_REQUEST['cod_ref']."");
                $this->row_mezcla=$row_mezcla->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
            }

            $this->row_materia_prima=$row_materia_prima->get_materiaPrima('insumo'," WHERE clase_insumo='8'  AND estado_insumo='0' "," ORDER BY descripcion_insumo ASC" );

            $this->row_referencia_copia=$row_referencia_copia->get_CopiaRef('tbl_produccion_mezclas pm '," WHERE id_proceso=2 "," ORDER BY CONVERT(int_cod_ref_pm, SIGNED INTEGER) DESC" );
    
            $this->row_referencia=$row_referencia->Obtener("tbl_referencia,tbl_egp", "tbl_referencia.estado_ref=1 AND tbl_referencia.n_egp_ref=tbl_egp.n_egp AND tbl_referencia.cod_ref ",  "".$_REQUEST['cod_ref']."");
    
            $this->row_editar_m = $row_editar_m->Obtener('tbl_produccion_mezclas',"id_proceso=2 AND int_cod_ref_pm ", "".$_REQUEST['cod_ref'].""); 
    
            $this->maquinas = $maquinas->get_Maquina(); 

            $this->anilox = $anilox->get_Anilox();  
 
 

            /*echo '<pre>';
               var_dump($this->row_mezcla);
            echo '<pre>';die;*/
        }
           
           //header('Location:views/view_produccion_registro_tintas_edit.php?c=cmezclasIm&a=Carat&cod_ref='.$_REQUEST['cod_ref']); 
           self::ViewCaract("view_produccion_registro_tintas_edit.php");
           
    }


        public function Guardar(){
 
            //$mezclas = new oCompras();
            $this->mezclas =  new oMmezclasIm(); 
            $this->proforma = $_REQUEST;  
            /*echo '<pre>';
             var_dump($this->proforma);
            echo '<pre>';*/

            //guarda caracteristicas
            $this->mezclas->Registrar("tbl_caracteristicas_prod", "cod_ref,fecha_registro,usuario,modifico,fecha_modif,extrusora,proceso,campo_1,campo_2,campo_3,campo_4,campo_5,campo_6,campo_7,campo_8,campo_9,campo_10,campo_11,campo_12,campo_13,campo_14,campo_15,campo_16,campo_17,campo_18,campo_19,campo_20,campo_21,campo_22,campo_23,campo_24,campo_25,campo_26,campo_27,campo_28,campo_29,campo_30,campo_31,campo_32,campo_33,campo_34,campo_35,campo_36,campo_37,campo_38,campo_39,campo_40,campo_41,campo_42,campo_43,campo_44,campo_45,campo_46,campo_47,campo_48,campo_49,campo_50,campo_51,campo_52,campo_53,campo_54,campo_55,campo_56,campo_57,campo_58,campo_59,campo_60,campo_61,campo_62,campo_63,campo_64,campo_65,campo_66,campo_67,campo_68,campo_69,campo_70,campo_71,campo_72,campo_73,campo_74,campo_75,campo_76,campo_77,campo_78,campo_79,campo_80,campo_81,campo_82,campo_83,campo_84,campo_85,campo_86,campo_87,campo_88,campo_89,campo_90,campo_91,campo_92,campo_93,campo_94,campo_95,campo_96,campo_97,campo_98,campo_99,campo_100,campo_101,campo_102,campo_103,campo_104,campo_105,campo_106,campo_107,campo_108,campo_109,campo_110,campo_111,campo_112,campo_113,campo_114,campo_115,campo_116,campo_117,campo_118,campo_119,campo_120,campo_121,campo_122,campo_123,campo_124,campo_125,campo_126,campo_127,campo_128,campo_129,campo_130,campo_131,campo_132,campo_133,campo_134,campo_135,campo_136,campo_137,campo_138,campo_139,campo_140,campo_141,campo_142,campo_143,campo_144,campo_145,campo_146", "cod_ref",$_POST['cod_ref'],  $this->proforma);
            //actualiza mezclas
            self::GuardarMezcla();   

            header('Location:view_index.php?c=cmezclasIm&a=Carat&cod_ref='.$_REQUEST['cod_ref']); 
        }

        public function GuardarMezcla(){
 
        
            //$mezclas = new oCompras();
            $this->mezclas =  new oMmezclasIm(); 
            $this->proforma = $_REQUEST;   
         
            $this->mezclas->RegistrarMezclas("tbl_produccion_mezclas", "id_proceso,fecha_registro_pm,str_registro_pm,id_ref_pm,int_cod_ref_pm,version_ref_pm,int_ref1_tol1_pm,int_ref1_tol1_porc1_pm,int_ref2_tol1_pm,int_ref2_tol1_porc2_pm,int_ref3_tol1_pm,int_ref3_tol1_porc3_pm,int_ref1_tol2_pm,int_ref1_tol2_porc1_pm,int_ref2_tol2_pm,int_ref2_tol2_porc2_pm,int_ref3_tol2_pm,int_ref3_tol2_porc3_pm,int_ref1_tol3_pm,int_ref1_tol3_porc1_pm,int_ref2_tol3_pm,int_ref2_tol3_porc2_pm,int_ref3_tol3_pm,int_ref3_tol3_porc3_pm,int_ref1_tol4_pm,int_ref1_tol4_porc1_pm,int_ref2_tol4_pm,int_ref2_tol4_porc2_pm,int_ref3_tol4_pm,int_ref3_tol4_porc3_pm,int_ref1_rpm_pm,int_ref1_tol5_porc1_pm,int_ref2_rpm_pm,int_ref2_tol5_porc2_pm,int_ref3_rpm_pm,int_ref3_tol5_porc3_pm,extrusora_mp,observ_pm,b_borrado_pm", "int_cod_ref_pm",$_POST['cod_ref'],  $this->proforma); 
                   
            header('Location:view_index.php?c=cmezclasIm&a=Mezcla&cod_ref='.$_REQUEST['cod_ref']); 
        }


        public function GuardarTintas(){
        
        
            //$mezclas = new oCompras();
            $this->tintas =  new oMmezclasIm(); 
            $this->proforma = $_REQUEST;   
       
            $this->tintas->RegistrarTintas("tbl_reg_kilo_producido", "id_rpp_rp,valor_prod_rp,op_rp,int_rollo_rkp,id_proceso_rkp,fecha_rkp,costo_mp", "id_insumo",$_POST['cod_ref'], $this->proforma); 
                   
            header('Location:view_index.php?c=cmezclasIm&a=Tintas&cod_ref='.$_REQUEST['cod_ref'].'&rollo='.$_REQUEST['int_rollo_rkp'].'&id_op='.$_REQUEST['op_rp'].'&fecha='.$_REQUEST['fecha_rkp'] ); 
        }


        public function Update($vista=''){ 
 
 
            $this->mezclas =  new oMmezclasIm(); 
            $this->mezcla = $_REQUEST;
            //$this->mezcla['adjunto']= $tieneadjunto1; 
            $resulrt = $conexion->Actualizar("tbl_produccion_mezclas", "fecha_registro_cv='".$_REQUEST['fecha_registro_cv']."',str_registro_cv='".$_REQUEST['str_registro_cv']."',campo_1='".$_REQUEST['campo_1']."',campo_2='".$_REQUEST['campo_2']."',campo_3='".$_REQUEST['campo_3']."',campo_4='".$_REQUEST['campo_4']."',campo_5='".$_REQUEST['campo_5']."',campo_6='".$_REQUEST['campo_6']."',campo_7='".$_REQUEST['campo_7']."',campo_8='".$_REQUEST['campo_8']."',campo_9='".$_REQUEST['campo_9']."',campo_10='".$_REQUEST['campo_10']."',campo_11='".$_REQUEST['campo_11']."',campo_12='".$_REQUEST['campo_12']."',campo_13='".$_REQUEST['campo_13']."',campo_14='".$_REQUEST['campo_14']."',campo_15='".$_REQUEST['campo_15']."',campo_16='".$_REQUEST['campo_16']."',campo_17='".$_REQUEST['campo_17']."',campo_18='".$_REQUEST['campo_18']."',campo_19='".$_REQUEST['campo_19']."',campo_20='".$_REQUEST['campo_20']."',campo_21='".$_REQUEST['campo_21']."',campo_22='".$_REQUEST['campo_22']."',campo_23='".$_REQUEST['campo_23']."',campo_24='".$_REQUEST['campo_24']."',campo_25='".$_REQUEST['campo_25']."',campo_26='".$_REQUEST['campo_26']."',campo_27='".$_REQUEST['campo_27']."',campo_28='".$_REQUEST['campo_28']."',campo_29='".$_REQUEST['campo_29']."',campo_30='".$_REQUEST['campo_30']."',campo_31='".$_REQUEST['campo_31']."',campo_32='".$_REQUEST['campo_32']."',campo_33='".$_REQUEST['campo_33']."',campo_34='".$_REQUEST['campo_34']."',campo_35='".$_REQUEST['campo_35']."',campo_36='".$_REQUEST['campo_36']."',campo_37='".$_REQUEST['campo_37']."',campo_38='".$_REQUEST['campo_38']."',campo_39='".$_REQUEST['campo_39']."',campo_40='".$_REQUEST['campo_40']."',campo_41='".$_REQUEST['campo_41']."',campo_42='".$_REQUEST['campo_42']."',campo_43='".$_REQUEST['campo_43']."',campo_44='".$_REQUEST['campo_44']."',campo_45='".$_REQUEST['campo_45']."',campo_46='".$_REQUEST['campo_46']."',campo_47='".$_REQUEST['campo_47']."',campo_48='".$_REQUEST['campo_48']."',campo_49='".$_REQUEST['campo_49']."',campo_50='".$_REQUEST['campo_50']."',campo_51='".$_REQUEST['campo_51']."',campo_52='".$_REQUEST['campo_52']."',campo_53='".$_REQUEST['campo_53']."',campo_54='".$_REQUEST['campo_54']."',campo_55='".$_REQUEST['campo_55']."'campo_56 = '".$_REQUEST['campo_56']."',campo_57 = '".$_REQUEST['campo_57']."',campo_58 = '".$_REQUEST['campo_58']."',campo_59 = '".$_REQUEST['campo_59']."',campo_60 = '".$_REQUEST['campo_60']."',campo_61 = '".$_REQUEST['campo_61']."',campo_62 = '".$_REQUEST['campo_62']."',campo_63 = '".$_REQUEST['campo_63']."',campo_64 = '".$_REQUEST['campo_64']."',campo_65 = '".$_REQUEST['campo_65']."',campo_66 = '".$_REQUEST['campo_66']."',campo_67='".$_REQUEST['campo_67']."',campo_68='".$_REQUEST['campo_68']."',campo_69='".$_REQUEST['campo_69']."',campo_70='".$_REQUEST['campo_70']."',campo_71='".$_REQUEST['campo_71']."',campo_72='".$_REQUEST['campo_72']."',campo_73='".$_REQUEST['campo_73']."',campo_74='".$_REQUEST['campo_74']."',campo_75='".$_REQUEST['campo_75']."',campo_76='".$_REQUEST['campo_76']."',campo_77='".$_REQUEST['campo_77']."',campo_78='".$_REQUEST['campo_78']."',campo_79='".$_REQUEST['campo_79']."',campo_80='".$_REQUEST['campo_80']."',campo_81='".$_REQUEST['campo_81']."',campo_82='".$_REQUEST['campo_82']."',campo_83='".$_REQUEST['campo_83']."',campo_84='".$_REQUEST['campo_84']."',campo_85='".$_REQUEST['campo_85']."',campo_86='".$_REQUEST['campo_86']."',campo_87='".$_REQUEST['campo_87']."',campo_88='".$_REQUEST['campo_88']."',campo_89='".$_REQUEST['campo_89']."',campo_90='".$_REQUEST['campo_90']."',campo_91='".$_REQUEST['campo_91']."',campo_92='".$_REQUEST['campo_92']."',campo_93='".$_REQUEST['campo_93']."',campo_94='".$_REQUEST['campo_94']."',campo_95='".$_REQUEST['campo_95']."',campo_96='".$_REQUEST['campo_96']."',campo_97='".$_REQUEST['campo_97']."',campo_98='".$_REQUEST['campo_98']."',campo_99='".$_REQUEST['campo_99']."',campo_100='".$_REQUEST['campo_100']."',campo_101='".$_REQUEST['campo_101']."',campo_102='".$_REQUEST['campo_102']."',campo_103='".$_REQUEST['campo_103']."',campo_104='".$_REQUEST['campo_104']."',campo_105='".$_REQUEST['campo_105']."',campo_106='".$_REQUEST['campo_106']."',campo_107='".$_REQUEST['campo_107']."',campo_108='".$_REQUEST['campo_108']."',campo_109='".$_REQUEST['campo_109']."',campo_110='".$_REQUEST['campo_110']."',campo_111='".$_REQUEST['campo_111']."',campo_112='".$_REQUEST['campo_112']."',campo_113='".$_REQUEST['campo_113']."',campo_114='".$_REQUEST['campo_114']."',campo_115='".$_REQUEST['campo_115']."',campo_116='".$_REQUEST['campo_116']."',campo_117='".$_REQUEST['campo_117']."',campo_118='".$_REQUEST['campo_118']."',campo_119='".$_REQUEST['campo_119']."',campo_120='".$_REQUEST['campo_120']."',campo_121='".$_REQUEST['campo_121']."',campo_122='".$_REQUEST['campo_122']."',campo_123='".$_REQUEST['campo_123']."',campo_124='".$_REQUEST['campo_124']."',campo_125='".$_REQUEST['campo_125']."',campo_126='".$_REQUEST['campo_126']."',campo_127='".$_REQUEST['campo_127']."',campo_128='".$_REQUEST['campo_128']."',campo_129='".$_REQUEST['campo_129']."',campo_130='".$_REQUEST['campo_130']."',campo_131='".$_REQUEST['campo_131']."',campo_132='".$_REQUEST['campo_132']."',campo_133='".$_REQUEST['campo_133']."',campo_134='".$_REQUEST['campo_134']."',campo_135='".$_REQUEST['campo_135']."',campo_136='".$_REQUEST['campo_136']."',campo_137='".$_REQUEST['campo_137']."',campo_138='".$_REQUEST['campo_138']."',campo_139='".$_REQUEST['campo_139']."',campo_140='".$_REQUEST['campo_140']."',campo_141='".$_REQUEST['campo_141']."',campo_142='".$_REQUEST['campo_142']."',campo_143='".$_REQUEST['campo_143']."',campo_144='".$_REQUEST['campo_144']."',campo_145='".$_REQUEST['campo_145']."',campo_146='".$_REQUEST['campo_146']."' ", " id_pm='".$_REQUEST['id_pm']."'", $this->mezcla);
            //self::ViewMezclas($vista);

        }
     

    public function ViewCaract($vista=''){ 
        if($vista){
          require_once("views/".$vista);  
        }
        else{  
 
            require_once("views/produccion_caract_impresion_add.php" ); 
        }
    }

    public function ViewMezclas($vista=''){ 
        if($vista){ 
        
          require_once($vista);  
        }
        else{ 
           require_once("views/produccion_caract_impresion_mezcla_vista.php" );
           //require_once("views/produccion_caract_impresion_add.php" );
        }
    }
 

}



?>
