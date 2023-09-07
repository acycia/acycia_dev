<?php
require_once 'Models/MmezclasIm.php';
class oMmezclasIm{
    private $db;
    private $ordenc;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->ordenc=array();
        /*$this->proveedores=array();
        $this->insumo=array();*/

    }

    public function llenaListas($tabla, $condicion, $orden='', $distinct=''){ 
      //echo "SELECT $distinct FROM $tabla $condicion $orden";die;
      $resultado = $this->db->query("SELECT $distinct FROM $tabla $condicion $orden") or die($this->db->error);

      if($resultado) 
        return self::getResultados($resultado);
      return false;
      $resultado->free();
      $resultado->close();
    }
 
    public function get_Maquina(){   

        try 
        {
            $consulta=$this->db->query("SELECT * FROM maquina WHERE proceso_maquina='2' ORDER BY nombre_maquina ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->maquina[]=$filas;
            }
    
            return $this->maquina;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function get_Anilox(){   

        try 
        {   
            $consulta=$this->db->query("SELECT * FROM anilox ORDER BY descripcion_insumo ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->anilox[]=$filas;
            }
    
            return $this->anilox;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function get_materiaPrima($tabla, $condicion='', $order){   

        try 
        {
            $consulta=$this->db->query("SELECT * FROM $tabla $condicion $order");
            while($filas=$consulta->fetch_assoc()){
                $this->maquina[]=$filas;
            }
    
            return $this->maquina;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function get_CopiaRef($tabla, $condicion='', $order){

        try 
        {
            $consulta=$this->db->query("SELECT * FROM $tabla $condicion $order ");
            while($filas=$consulta->fetch_assoc()){
                $this->maquina[]=$filas;
            }
    
            return $this->maquina;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Obtener($tabla,$columna,$id)
    {

        try 
        { 
           // echo "SELECT * FROM $tabla WHERE $columna = '$id' ";die;
            if($tabla!='' && $columna!='' && $id!=''){ 
                $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna = '$id' ");
                while($filas=$stm->fetch_assoc()){
                    $this->ordenc[]=$filas;
                }
            return $this->ordenc;
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Registrar($tabla,$columna,$filtro,$id, $data)
    { 

        try 
        {
                 
                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
 
                $consulta=$this->db->query("SELECT * FROM $tabla WHERE ".$filtro." ='$id' and proceso='2' ");
                if($consulta){
                 while($filas=$consulta->fetch_assoc()){
                    $this->existe[]=$filas;
                 }

                }
                if(is_null($this->existe)){ 
                  
                  $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '".$arrayPHP['cod_ref']."','".$arrayPHP['fecha_registro']."','".$arrayPHP['usuario']."','".$arrayPHP['modifico']."','".$arrayPHP['fecha_modif']."','".$arrayPHP['extrusora']."', '".$arrayPHP['proceso']."', '".$arrayPHP['campo_1']."','".$arrayPHP['campo_2']."','".$arrayPHP['campo_3']."','".$arrayPHP['campo_4']."','".$arrayPHP['campo_5']."','".$arrayPHP['campo_6']."','".$arrayPHP['campo_7']."','".$arrayPHP['campo_8']."','".$arrayPHP['campo_9']."','".$arrayPHP['campo_10']."','".$arrayPHP['campo_11']."','".$arrayPHP['campo_12']."','".$arrayPHP['campo_13']."','".$arrayPHP['campo_14']."','".$arrayPHP['campo_15']."','".$arrayPHP['campo_16']."','".$arrayPHP['campo_17']."','".$arrayPHP['campo_18']."','".$arrayPHP['campo_19']."','".$arrayPHP['campo_20']."','".$arrayPHP['campo_21']."','".$arrayPHP['campo_22']."','".$arrayPHP['campo_23']."','".$arrayPHP['campo_24']."','".$arrayPHP['campo_25']."','".$arrayPHP['campo_26']."','".$arrayPHP['campo_27']."','".$arrayPHP['campo_28']."','".$arrayPHP['campo_29']."','".$arrayPHP['campo_30']."','".$arrayPHP['campo_31']."','".$arrayPHP['campo_32']."','".$arrayPHP['campo_33']."','".$arrayPHP['campo_34']."','".$arrayPHP['campo_35']."','".$arrayPHP['campo_36']."','".$arrayPHP['campo_37']."','".$arrayPHP['campo_38']."','".$arrayPHP['campo_39']."','".$arrayPHP['campo_40']."','".$arrayPHP['campo_41']."','".$arrayPHP['campo_42']."','".$arrayPHP['campo_43']."','".$arrayPHP['campo_44']."','".$arrayPHP['campo_45']."','".$arrayPHP['campo_46']."','".$arrayPHP['campo_47']."','".$arrayPHP['campo_48']."','".$arrayPHP['campo_49']."','".$arrayPHP['campo_50']."','".$arrayPHP['campo_51']."','".$arrayPHP['campo_52']."','".$arrayPHP['campo_53']."','".$arrayPHP['campo_54']."','".$arrayPHP['campo_55']."','".$arrayPHP['campo_56']."','".$arrayPHP['campo_57']."','".$arrayPHP['campo_58']."','".$arrayPHP['campo_59']."','".$arrayPHP['campo_60']."','".$arrayPHP['campo_61']."','".$arrayPHP['campo_62']."','".$arrayPHP['campo_63']."','".$arrayPHP['campo_64']."','".$arrayPHP['campo_65']."','".$arrayPHP['campo_66']."','".$arrayPHP['campo_67']."','".$arrayPHP['campo_68']."','".$arrayPHP['campo_69']."','".$arrayPHP['campo_70']."','".$arrayPHP['campo_71']."','".$arrayPHP['campo_72']."','".$arrayPHP['campo_73']."','".$arrayPHP['campo_74']."','".$arrayPHP['campo_75']."','".$arrayPHP['campo_76']."','".$arrayPHP['campo_77']."','".$arrayPHP['campo_78']."','".$arrayPHP['campo_79']."','".$arrayPHP['campo_80']."','".$arrayPHP['campo_81']."','".$arrayPHP['campo_82']."','".$arrayPHP['campo_83']."','".$arrayPHP['campo_84']."','".$arrayPHP['campo_85']."','".$arrayPHP['campo_86']."','".$arrayPHP['campo_87']."','".$arrayPHP['campo_88']."','".$arrayPHP['campo_89']."','".$arrayPHP['campo_90']."','".$arrayPHP['campo_91']."','".$arrayPHP['campo_92']."','".$arrayPHP['campo_93']."','".$arrayPHP['campo_94']."','".$arrayPHP['campo_95']."','".$arrayPHP['campo_96']."','".$arrayPHP['campo_97']."','".$arrayPHP['campo_98']."','".$arrayPHP['campo_99']."','".$arrayPHP['campo_100']."','".$arrayPHP['campo_101']."','".$arrayPHP['campo_102']."','".$arrayPHP['campo_103']."','".$arrayPHP['campo_104']."','".$arrayPHP['campo_105']."','".$arrayPHP['campo_106']."','".$arrayPHP['campo_107']."','".$arrayPHP['campo_108']."','".$arrayPHP['campo_109']."','".$arrayPHP['campo_110']."','".$arrayPHP['campo_111']."','".$arrayPHP['campo_112']."','".$arrayPHP['campo_113']."','".$arrayPHP['campo_114']."','".$arrayPHP['campo_115']."','".$arrayPHP['campo_116']."','".$arrayPHP['campo_117']."','".$arrayPHP['campo_118']."','".$arrayPHP['campo_119']."','".$arrayPHP['campo_120']."','".$arrayPHP['campo_121']."','".$arrayPHP['campo_122']."','".$arrayPHP['campo_123']."','".$arrayPHP['campo_124']."','".$arrayPHP['campo_125']."','".$arrayPHP['campo_126']."','".$arrayPHP['campo_127']."','".$arrayPHP['campo_128']."','".$arrayPHP['campo_129']."','".$arrayPHP['campo_130']."','".$arrayPHP['campo_131']."','".$arrayPHP['campo_132']."','".$arrayPHP['campo_133']."','".$arrayPHP['campo_134']."','".$arrayPHP['campo_135']."','".$arrayPHP['campo_136']."','".$arrayPHP['campo_137']."','".$arrayPHP['campo_138']."','".$arrayPHP['campo_139']."','".$arrayPHP['campo_140']."','".$arrayPHP['campo_141']."','".$arrayPHP['campo_142']."','".$arrayPHP['campo_143']."','".$arrayPHP['campo_144']."','".$arrayPHP['campo_145']."','".$arrayPHP['campo_146']."' );");
 
                }else{ 
 
                    $updatepro = $this->db->query("UPDATE $tabla SET fecha_registro='". $arrayPHP['fecha_registro'] ."',usuario='". $arrayPHP['usuario'] ."',modifico='". $arrayPHP['modifico'] ."',fecha_modif='". $arrayPHP['fecha_modif'] ."',extrusora='". $arrayPHP['extrusora'] ."', proceso='".$arrayPHP['proceso'] ."',campo_1='". $arrayPHP['campo_1'] ."',campo_2='". $arrayPHP['campo_2'] ."',campo_3='". $arrayPHP['campo_3'] ."',campo_4='". $arrayPHP['campo_4'] ."',campo_5='". $arrayPHP['campo_5'] ."',campo_6='". $arrayPHP['campo_6'] ."',campo_7='". $arrayPHP['campo_7'] ."',campo_8='". $arrayPHP['campo_8'] ."',campo_9='". $arrayPHP['campo_9'] ."',campo_10='". $arrayPHP['campo_10'] ."',campo_11='". $arrayPHP['campo_11'] ."',campo_12='". $arrayPHP['campo_12'] ."',campo_13='". $arrayPHP['campo_13'] ."',campo_14='". $arrayPHP['campo_14'] ."',campo_15='". $arrayPHP['campo_15'] ."',campo_16='". $arrayPHP['campo_16'] ."',campo_17='". $arrayPHP['campo_17'] ."',campo_18='". $arrayPHP['campo_18'] ."',campo_19='". $arrayPHP['campo_19'] ."',campo_20='". $arrayPHP['campo_20'] ."',campo_21='". $arrayPHP['campo_21'] ."',campo_22='". $arrayPHP['campo_22'] ."',campo_23='". $arrayPHP['campo_23'] ."',campo_24='". $arrayPHP['campo_24'] ."',campo_25='". $arrayPHP['campo_25'] ."',campo_26='". $arrayPHP['campo_26'] ."',campo_27='". $arrayPHP['campo_27'] ."',campo_28='". $arrayPHP['campo_28'] ."',campo_29='". $arrayPHP['campo_29'] ."',campo_30='". $arrayPHP['campo_30'] ."',campo_31='". $arrayPHP['campo_31'] ."',campo_32='". $arrayPHP['campo_32'] ."',campo_33='". $arrayPHP['campo_33'] ."',campo_34='". $arrayPHP['campo_34'] ."',campo_35='". $arrayPHP['campo_35'] ."',campo_36='". $arrayPHP['campo_36'] ."',campo_37='". $arrayPHP['campo_37'] ."',campo_38='". $arrayPHP['campo_38'] ."',campo_39='". $arrayPHP['campo_39'] ."',campo_40='". $arrayPHP['campo_40'] ."',campo_41='". $arrayPHP['campo_41'] ."',campo_42='". $arrayPHP['campo_42'] ."',campo_43='". $arrayPHP['campo_43'] ."',campo_44='". $arrayPHP['campo_44'] ."',campo_45='". $arrayPHP['campo_45'] ."',campo_46='". $arrayPHP['campo_46'] ."',campo_47='". $arrayPHP['campo_47'] ."',campo_48='". $arrayPHP['campo_48'] ."',campo_49='". $arrayPHP['campo_49'] ."',campo_50='". $arrayPHP['campo_50'] ."',campo_51='". $arrayPHP['campo_51'] ."',campo_52='". $arrayPHP['campo_52'] ."',campo_53='". $arrayPHP['campo_53'] ."',campo_54='". $arrayPHP['campo_54'] ."',campo_55='". $arrayPHP['campo_55'] ."',campo_56 = '".$arrayPHP['campo_56']."',campo_57 = '".$arrayPHP['campo_57']."',campo_58 = '".$arrayPHP['campo_58']."',campo_59 = '".$arrayPHP['campo_59']."',campo_60 = '".$arrayPHP['campo_60']."',campo_61 = '".$arrayPHP['campo_61']."',campo_62 = '".$arrayPHP['campo_62']."',campo_63 = '".$arrayPHP['campo_63']."',campo_64 = '".$arrayPHP['campo_64']."',campo_65 = '".$arrayPHP['campo_65']."',campo_66 = '".$arrayPHP['campo_66']."',campo_67='".$arrayPHP['campo_67']."',campo_68='".$arrayPHP['campo_68']."',campo_69='".$arrayPHP['campo_69']."',campo_70='".$arrayPHP['campo_70']."',campo_71='".$arrayPHP['campo_71']."',campo_72='".$arrayPHP['campo_72']."',campo_73='".$arrayPHP['campo_73']."',campo_74='".$arrayPHP['campo_74']."',campo_75='".$arrayPHP['campo_75']."',campo_76='".$arrayPHP['campo_76']."',campo_77='".$arrayPHP['campo_77']."',campo_78='".$arrayPHP['campo_78']."',campo_79='".$arrayPHP['campo_79']."',campo_80='".$arrayPHP['campo_80']."',campo_81='".$arrayPHP['campo_81']."',campo_82='".$arrayPHP['campo_82']."',campo_83='".$arrayPHP['campo_83']."',campo_84='".$arrayPHP['campo_84']."',campo_85='".$arrayPHP['campo_85']."',campo_86='".$arrayPHP['campo_86']."',campo_87='".$arrayPHP['campo_87']."',campo_88='".$arrayPHP['campo_88']."',campo_89='".$arrayPHP['campo_89']."',campo_90='".$arrayPHP['campo_90']."',campo_91='".$arrayPHP['campo_91']."',campo_92='".$arrayPHP['campo_92']."',campo_93='".$arrayPHP['campo_93']."',campo_94='".$arrayPHP['campo_94']."',campo_95='".$arrayPHP['campo_95']."',campo_96='".$arrayPHP['campo_96']."',campo_97='".$arrayPHP['campo_97']."',campo_98='".$arrayPHP['campo_98']."',campo_99='".$arrayPHP['campo_99']."',campo_100='".$arrayPHP['campo_100']."',campo_101='".$arrayPHP['campo_101']."',campo_102='".$arrayPHP['campo_102']."',campo_103='".$arrayPHP['campo_103']."',campo_104='".$arrayPHP['campo_104']."',campo_105='".$arrayPHP['campo_105']."',campo_106='".$arrayPHP['campo_106']."',campo_107='".$arrayPHP['campo_107']."',campo_108='".$arrayPHP['campo_108']."',campo_109='".$arrayPHP['campo_109']."',campo_110='".$arrayPHP['campo_110']."',campo_111='".$arrayPHP['campo_111']."',campo_112='".$arrayPHP['campo_112']."',campo_113='".$arrayPHP['campo_113']."',campo_114='".$arrayPHP['campo_114']."',campo_115='".$arrayPHP['campo_115']."',campo_116='".$arrayPHP['campo_116']."',campo_117='".$arrayPHP['campo_117']."',campo_118='".$arrayPHP['campo_118']."',campo_119='".$arrayPHP['campo_119']."',campo_120='".$arrayPHP['campo_120']."',campo_121='".$arrayPHP['campo_121']."',campo_122='".$arrayPHP['campo_122']."',campo_123='".$arrayPHP['campo_123']."',campo_124='".$arrayPHP['campo_124']."',campo_125='".$arrayPHP['campo_125']."',campo_126='".$arrayPHP['campo_126']."',campo_127='".$arrayPHP['campo_127']."',campo_128='".$arrayPHP['campo_128']."',campo_129='".$arrayPHP['campo_129']."',campo_130='".$arrayPHP['campo_130']."',campo_131='".$arrayPHP['campo_131']."',campo_132='".$arrayPHP['campo_132']."',campo_133='".$arrayPHP['campo_133']."',campo_134='".$arrayPHP['campo_134']."',campo_135='".$arrayPHP['campo_135']."',campo_136='".$arrayPHP['campo_136']."',campo_137='".$arrayPHP['campo_137']."',campo_138='".$arrayPHP['campo_138']."',campo_139='".$arrayPHP['campo_139']."',campo_140='".$arrayPHP['campo_140']."',campo_141='".$arrayPHP['campo_141']."',campo_142='".$arrayPHP['campo_142']."',campo_143='".$arrayPHP['campo_143']."',campo_144='".$arrayPHP['campo_144']."',campo_145='".$arrayPHP['campo_145']."',campo_146='".$arrayPHP['campo_146']."' WHERE ".$filtro." = '". $id ."'  and proceso='2';" );
                }
                /*echo '<pre>';
                   var_dump($stmt) ;
                echo '<pre>';die; */

       
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function RegistrarMezclas($tabla,$columna,$filtro,$id, $data)
    { 


        try 
        {
            $array_codificado = UtilHelper::arrayEncode($data);
            $array_deco = UtilHelper::arrayDecode($array_codificado); 
            $arrayPHP =  ($array_deco) ;
            
            $consulta=$this->db->query("SELECT * FROM $tabla WHERE ".$filtro." ='$id' and id_proceso='2' ");
            if($consulta){
             while($filas=$consulta->fetch_assoc()){
                $this->existe[]=$filas;
             }

            }

            if(is_null($this->existe)){ 
   
         

              $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['id_proceso'] ."','". $arrayPHP['fecha_registro_pm'] ."','". $arrayPHP['str_registro_pm'] ."','". $arrayPHP['id_ref_pm'] ."','". $arrayPHP['int_cod_ref_pm'] ."','". $arrayPHP['version_ref_pm'] ."','". $arrayPHP['int_ref1_tol1_pm'] ."','". $arrayPHP['int_ref1_tol1_porc1_pm'] ."','". $arrayPHP['int_ref2_tol1_pm'] ."','". $arrayPHP['int_ref2_tol1_porc2_pm'] ."','". $arrayPHP['int_ref3_tol1_pm'] ."','". $arrayPHP['int_ref3_tol1_porc3_pm'] ."','". $arrayPHP['int_ref1_tol2_pm'] ."','". $arrayPHP['int_ref1_tol2_porc1_pm'] ."','". $arrayPHP['int_ref2_tol2_pm'] ."','". $arrayPHP['int_ref2_tol2_porc2_pm'] ."','". $arrayPHP['int_ref3_tol2_pm'] ."','". $arrayPHP['int_ref3_tol2_porc3_pm'] ."','". $arrayPHP['int_ref1_tol3_pm'] ."','". $arrayPHP['int_ref1_tol3_porc1_pm'] ."','". $arrayPHP['int_ref2_tol3_pm'] ."','". $arrayPHP['int_ref2_tol3_porc2_pm'] ."','". $arrayPHP['int_ref3_tol3_pm'] ."','". $arrayPHP['int_ref3_tol3_porc3_pm'] ."','". $arrayPHP['int_ref1_tol4_pm'] ."','". $arrayPHP['int_ref1_tol4_porc1_pm'] ."','". $arrayPHP['int_ref2_tol4_pm'] ."','". $arrayPHP['int_ref2_tol4_porc2_pm'] ."','". $arrayPHP['int_ref3_tol4_pm'] ."','". $arrayPHP['int_ref3_tol4_porc3_pm'] ."','". $arrayPHP['int_ref1_rpm_pm'] ."','". $arrayPHP['int_ref1_tol5_porc1_pm'] ."','". $arrayPHP['int_ref2_rpm_pm'] ."','". $arrayPHP['int_ref2_tol5_porc2_pm'] ."','". $arrayPHP['int_ref3_rpm_pm'] ."','". $arrayPHP['int_ref3_tol5_porc3_pm'] ."','". $arrayPHP['extrusora_mp'] ."','". $arrayPHP['observ_pm'] ."','". $arrayPHP['b_borrado_pm'] ."' );");
             

            }else{ 
                $updatepro = $this->db->query("UPDATE $tabla SET id_proceso = '". $arrayPHP['id_proceso'] ."',fecha_registro_pm = '". $arrayPHP['fecha_registro_pm'] ."',str_registro_pm = '". $arrayPHP['str_registro_pm'] ."',int_ref1_tol1_pm = '". $arrayPHP['int_ref1_tol1_pm'] ."',int_ref1_tol1_porc1_pm = '". $arrayPHP['int_ref1_tol1_porc1_pm'] ."',int_ref2_tol1_pm = '". $arrayPHP['int_ref2_tol1_pm'] ."',int_ref2_tol1_porc2_pm = '". $arrayPHP['int_ref2_tol1_porc2_pm'] ."',int_ref3_tol1_pm = '". $arrayPHP['int_ref3_tol1_pm'] ."',int_ref3_tol1_porc3_pm = '". $arrayPHP['int_ref3_tol1_porc3_pm'] ."',int_ref1_tol2_pm = '". $arrayPHP['int_ref1_tol2_pm'] ."',int_ref1_tol2_porc1_pm = '". $arrayPHP['int_ref1_tol2_porc1_pm'] ."',int_ref2_tol2_pm = '". $arrayPHP['int_ref2_tol2_pm'] ."',int_ref2_tol2_porc2_pm = '". $arrayPHP['int_ref2_tol2_porc2_pm'] ."',int_ref3_tol2_pm = '". $arrayPHP['int_ref3_tol2_pm'] ."',int_ref3_tol2_porc3_pm = '". $arrayPHP['int_ref3_tol2_porc3_pm'] ."',int_ref1_tol3_pm = '". $arrayPHP['int_ref1_tol3_pm'] ."',int_ref1_tol3_porc1_pm = '". $arrayPHP['int_ref1_tol3_porc1_pm'] ."',int_ref2_tol3_pm = '". $arrayPHP['int_ref2_tol3_pm'] ."',int_ref2_tol3_porc2_pm = '". $arrayPHP['int_ref2_tol3_porc2_pm'] ."',int_ref3_tol3_pm = '". $arrayPHP['int_ref3_tol3_pm'] ."',int_ref3_tol3_porc3_pm = '". $arrayPHP['int_ref3_tol3_porc3_pm'] ."',int_ref1_tol4_pm = '". $arrayPHP['int_ref1_tol4_pm'] ."',int_ref1_tol4_porc1_pm = '". $arrayPHP['int_ref1_tol4_porc1_pm'] ."',int_ref2_tol4_pm = '". $arrayPHP['int_ref2_tol4_pm'] ."',int_ref2_tol4_porc2_pm = '". $arrayPHP['int_ref2_tol4_porc2_pm'] ."',int_ref3_tol4_pm = '". $arrayPHP['int_ref3_tol4_pm'] ."',int_ref3_tol4_porc3_pm = '". $arrayPHP['int_ref3_tol4_porc3_pm'] ."',int_ref1_rpm_pm = '". $arrayPHP['int_ref1_rpm_pm'] ."',int_ref1_tol5_porc1_pm = '". $arrayPHP['int_ref1_tol5_porc1_pm'] ."',int_ref2_rpm_pm = '". $arrayPHP['int_ref2_rpm_pm'] ."',int_ref2_tol5_porc2_pm = '". $arrayPHP['int_ref2_tol5_porc2_pm'] ."',int_ref3_rpm_pm = '". $arrayPHP['int_ref3_rpm_pm'] ."',int_ref3_tol5_porc3_pm = '". $arrayPHP['int_ref3_tol5_porc3_pm'] ."',extrusora_mp = '". $arrayPHP['extrusora_mp'] ."',observ_pm = '". $arrayPHP['observ_pm'] ."',b_borrado_pm = '". $arrayPHP['b_borrado_pm'] ."' WHERE ".$filtro." = '". $id ."'  and id_proceso='2' ;" );
              
            }
            /*echo '<pre>';
               var_dump($stmt) ;
            echo '<pre>';die; */
 

     
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function RegistrarTintas($tabla,$columna,$filtro,$id, $data)
    { 


        try 
        {
            $array_codificado = UtilHelper::arrayEncode($data);
            $array_deco = UtilHelper::arrayDecode($array_codificado); 
            $arrayPHP =  ($array_deco) ;
          
                $id=($arrayPHP['id_i']);
                   foreach($id as $key=>$v)
                   $a[]= $v;
                
                $cant=($arrayPHP['cant']);
                   foreach($cant as $key=>$v)
                   $b[]= $v;

               for($x=0; $x<count($a); $x++){
                  if($a[$x]!=''&&$b[$x]!=''){ 
                           
                          $sqlcostoMP="SELECT valor_unitario_insumo AS valorkilo FROM insumo WHERE ".$filtro." =  $a[$x]"; 
                          $resultcostoMP=mysql_query($sqlcostoMP);  
                          $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
                          $valorMP = $row_valoresMP['valorkilo'];
 
                     $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $a[$x] ."','". $b[$x] ."','". $arrayPHP['op_rp'] ."','". $arrayPHP['int_rollo_rkp'] ."','". $arrayPHP['id_proceso_rkp'] ."','". $arrayPHP['fecha_rkp'] ."','". $valorMP ."' );");
                  }
                }
    
             /*echo '<pre>'; 
               var_dump($row_valoresMP['valorkilo']);
            echo '<pre>';die;*/
 
     
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }



    public function Update($data)
    {
        try 
        {
         
             $updatepro = $this->db->query($data); echo '<br>';

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function UpdateItems($tabla,$id,$valor,$columna,$proceso)
    { 
        try 
        {  
            self::Update("UPDATE $tabla SET $columna ='$valor' WHERE  id = $id " ); 
          die;//dejarlo para q no bote error
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Delete($tabla,$id,$columna,$proceso,$master)
    {
        try 
        { 
            if($master==1){
                 
              //Elimina Maestro 
               $stm = $this->db->query("DELETE FROM tbl_proceso_compras WHERE $columna = '$id'  AND proceso = '$proceso'"); 
              //Elimina Items
               $stmi = $this->db->query("DELETE FROM $tabla WHERE $columna = '$id' AND proceso = '$proceso'");                
            }else{
               //Elimina Items
               $stmi = $this->db->query("DELETE FROM $tabla WHERE id = $id ");      

            }
           

           
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

      public function getResultados($arreglo)
      {
        $rows = array();
      while($row = $arreglo->fetch_array(MYSQLI_BOTH))//MYSQLI_ASSOC array asociativo, MYSQLI_NUM array numérico
      {
        $rows[] = $row;
      }

      return $rows;
    }


}

class UtilHelper {
   /* Crea un string codificado a partir de un array
   * @param Array array: array asociativo clave => valor
   * @return cadena de texto con el array listo para insertarse en BD
   */
   static function arrayEncode($array){
      return base64_encode(json_encode($array));
  }

   /* Crea un array a partir de un string codificado
   * @param String array_texto : string codificado de un array asociativo clave => valor
   * @return Array php
   */
   static function arrayDecode($array){
      return json_decode((base64_decode($array)),true);
  }
}
?>
