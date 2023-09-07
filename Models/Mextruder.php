<?php
require_once 'Models/Mextruder.php';

class oMextruder{
    private $db;
    private $ordenc;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->ordenc=array();
        /*$this->proveedores=array();
        $this->insumo=array();*/

    }
 
    public function get_Maquina(){   

        try 
        {
            $consulta=$this->db->query("SELECT * FROM maquina WHERE proceso_maquina=1 ORDER BY nombre_maquina ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->maquina[]=$filas;
            }
    
            return $this->maquina;
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
 

    public function Obtener($tabla,$columna,$id)
    {

        try 
        { 

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
 
                $consulta=$this->db->query("SELECT * FROM $tabla WHERE $filtro = $id " );
                if($consulta){
                 while($filas=$consulta->fetch_assoc()){
                    $this->existe[]=$filas;
                 }

                }

                if(is_null($this->existe)){ 
                  $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( ". $arrayPHP['id_rp'] .", '". $arrayPHP['id_proceso_rp'] ."','". $arrayPHP['id_op_rp'] ."','". $arrayPHP['id_ref_rp'] ."','". $arrayPHP['int_cod_ref_rp'] ."','". $arrayPHP['version_ref_rp'] ."','". $arrayPHP['rollo_rp'] ."','". $arrayPHP['int_kilos_prod_rp'] ."','". $arrayPHP['int_kilos_desp_rp'] ."', '". $arrayPHP['int_total_kilos_rp'] ."', '". $arrayPHP['porcentaje'] ."','". $arrayPHP['int_metro_lineal_rp'] ."','". $arrayPHP['int_total_rollos_rp'] ."','". $arrayPHP['total_horas_rp'] ."','". $arrayPHP['tiempoOptimo_rp'] ."','','','". $arrayPHP['str_maquina_rp'] ."','". $arrayPHP['str_responsable_rp'] ."','". $arrayPHP['fecha_ini_rp'] ."', '". $arrayPHP['fecha_fin_rp'] ."','". $arrayPHP['int_kilosxhora_rp'] ."','". $arrayPHP['int_cod_empleado_rp'] ."','". $arrayPHP['int_cod_liquida_rp'] ."','". $arrayPHP['costo'] ."','". $arrayPHP['parcial'] ."' );");
      
                  return $stmt;
                }else{  
  
                    $updatepro = $this->db->query("UPDATE $tabla SET id_rp ='". $arrayPHP['id_rp'] ."',id_proceso_rp='". $arrayPHP['id_proceso_rp'] ."',id_op_rp='". $arrayPHP['id_op_rp'] ."',id_ref_rp='". $arrayPHP['id_ref_rp'] ."' ,int_cod_ref_rp='". $arrayPHP['int_cod_ref_rp'] ."',version_ref_rp='". $arrayPHP['version_ref_rp'] ."', rollo_rp='". $arrayPHP['rollo_rp'] ."',int_kilos_prod_rp='". $arrayPHP['int_kilos_prod_rp'] ."', int_kilos_desp_rp='".$arrayPHP['int_kilos_desp_rp'] ."',int_total_kilos_rp='". $arrayPHP['int_total_kilos_rp'] ."',porcentaje_op_rp='". $arrayPHP['porcentaje'] ."',int_metro_lineal_rp='". $arrayPHP['int_metro_lineal_rp'] ."',int_total_rollos_rp='". $arrayPHP['int_total_rollos_rp'] ."',total_horas_rp='". $arrayPHP['total_horas_rp'] ."',rodamiento_rp='". $arrayPHP['tiempoOptimo_rp'] ."',horas_muertas_rp='". $arrayPHP['valor_tiem_rt'] ."',horas_prep_rp='". $arrayPHP['valor_prep_rtp'] ."',str_maquina_rp='". $arrayPHP['str_maquina_rp'] ."',str_responsable_rp='". $arrayPHP['str_responsable_rp'] ."', fecha_ini_rp ='". $arrayPHP['fecha_ini_rp'] ."',fecha_fin_rp='". $arrayPHP['fecha_fin_rp'] ."',int_kilosxhora_rp='". $arrayPHP['int_kilosxhora_rp'] ."',int_cod_empleado_rp='". $arrayPHP['int_cod_empleado_rp'] ."',int_cod_liquida_rp='". $arrayPHP['int_cod_liquida_rp'] ."',costo='". $arrayPHP['costo'] ."', parcial='". $arrayPHP['parcial'] ."'   WHERE ".$filtro." =  '". $id ."' ");
                    return $updatepro;
                } 

       
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
            
            $consulta=$this->db->query("SELECT * FROM $tabla WHERE ".$filtro." ='$id' ");
            if($consulta){
             while($filas=$consulta->fetch_assoc()){
                $this->existe[]=$filas;
             }

            }
            
            if(is_null($this->existe)){ 
              $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['int_ref1_rpm_pm'] ."','". $arrayPHP['int_ref1_tol5_porc1_pm'] ."','". $arrayPHP['int_ref2_rpm_pm'] ."','". $arrayPHP['int_ref2_tol5_porc2_pm'] ."','". $arrayPHP['int_ref3_rpm_pm'] ."','". $arrayPHP['int_ref3_tol5_porc3_pm'] ."' );");
             

            }else{ 
           
                $updatepro = $this->db->query("UPDATE $tabla SET int_ref1_rpm_pm = '". $arrayPHP['int_ref1_rpm_pm'] ."',int_ref1_tol5_porc1_pm = '". $arrayPHP['int_ref1_tol5_porc1_pm'] ."',int_ref2_rpm_pm = '". $arrayPHP['int_ref2_rpm_pm'] ."',int_ref2_tol5_porc2_pm = '". $arrayPHP['int_ref2_tol5_porc2_pm'] ."',int_ref3_rpm_pm = '". $arrayPHP['int_ref3_rpm_pm'] ."',int_ref3_tol5_porc3_pm = '". $arrayPHP['int_ref3_tol5_porc3_pm'] ."' WHERE ".$filtro." = '". $id ."' " );
              
            } 
 

     
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
