<?php
require_once 'Models/Msellado.php';

class oMsellado{
    private $db;
    private $ordenc;


    public function __construct(){
        $this->conexion=Conectar::conexion();
        $this->ordenc=array();
        /*$this->proveedores=array();
        $this->insumo=array();*/

    }
 
     //BUSCAR UNO
    public function buscar($tabla, $columna, $condicion){
      //echo "SELECT * FROM $tabla WHERE  $columna = '{$condicion}' ORDER BY $columna DESC";die;
      $resultado = $this->conexion->query("SELECT * FROM $tabla WHERE  $columna = '{$condicion}' ORDER BY $columna DESC") or die($this->conexion->error);
           if($resultado)
               $fila = $resultado->fetch_assoc();//mysqli_fetch_assoc($resultado)
               $total = $fila; 
           return $total;
         return false;
      $resultado->free();
      $resultado->close();
    }


    public function Obtener($tabla,$columna,$id)
    {

        try 
        {
            if($tabla!='' && $columna!='' && $id!=''){ 
                $stm = $this->conexion->query("SELECT * FROM $tabla WHERE $columna = '$id' ");
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


    //LLENA LISTAS CON ASSOC
    public function buscarTres($tabla, $columnas, $condicion='', $order=''){
      //echo "SELECT $columnas FROM $tabla $condicion $order";die;
      $resultado = $this->conexion->query("SELECT $columnas FROM $tabla $condicion $order") or die($this->conexion->error);
      if($resultado)
        $fila = $resultado->fetch_assoc();//mysqli_fetch_assoc($resultado)
      $total = $fila; 
      return $total;
      return false;
      $resultado->free();
      $resultado->close();
    }

     //LLENAR CAMPOS
    public function llenarCampos($tabla, $condicion, $orden='', $distinct='' ){  
      //echo "SELECT $distinct FROM $tabla $condicion $orden ";die;
      $resultado = $this->conexion->query("SELECT $distinct FROM $tabla $condicion $orden ") or die($this->conexion->error);
      if($resultado)
        $fila = $resultado->fetch_assoc();//mysqli_fetch_assoc($resultado)
      $total = $fila; 
      return $total;
      return false;
      $resultado->free();
      $resultado->close();
    }

    //LLENA COMBOS CONVIERTE 
     public function llenaSelect($tabla, $condicion='', $orden='' ){  
       $resultado = $this->conexion->query("SELECT * FROM $tabla $condicion $orden ") or die($this->conexion->error); 
       if($resultado) 
         //return $resultado->fetch_array(MYSQLI_BOTH);//MYSQLI_BOTH muestra numerico y asociativo 
         return self::getResultados($resultado);
       return false; 
       $resultado->free();
       $resultado->close();
     }


    public function get_Provee(){

        try 
        {
            $consulta=$this->conexion->query("SELECT id_p, proveedor_p FROM proveedor ORDER BY proveedor_p ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->proveedores[]=$filas;
            }
 
            return $this->proveedores;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function get_Insumo(){

        try 
        {
            $consulta=$this->conexion->query("SELECT id_insumo,descripcion_insumo, valor_unitario_insumo FROM insumo ORDER BY descripcion_insumo ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->insumo[]=$filas;
            }
    
            return $this->insumo;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function get_Maquina(){

        try 
        {
            $consulta=$this->conexion->query("SELECT * FROM maquina WHERE proceso_maquina <>'0' ORDER BY nombre_maquina ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->maquina[]=$filas;
            }
    
            return $this->maquina;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }



    public function Registrar($tabla,$columna,$data)
    { 

        try 
        {
            
             $array_codificado = UtilHelper::arrayEncode($data);
             $array_deco = UtilHelper::arrayDecode($array_codificado); 
             $arrayPHP =  ($array_deco) ;
   
             $stmt = $this->conexion->query("INSERT INTO $tabla ($columna) VALUES (  '".$_POST['int_op_tn']."','".$_POST['fecha_ingreso_tn']."','".$_POST['hora_tn']."','".$_POST['int_bolsas_tn']."','".$_POST['int_undxpaq_tn']."','".$_POST['int_undxcaja_tn']."','".$_POST['int_desde_tn']."','".$_POST['int_hasta_tn']."','".$_POST['int_cod_empleado_tn']."','".$_POST['int_cod_rev_tn']."','".$_POST['contador_tn']."','".$_POST['int_paquete_tn']."','".$_POST['int_caja_tn']."','".$_POST['pesot']."','".$_POST['ref_tn']."','".$_POST['tienefaltantes']."' );"); 
       
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function RegistrarAdd($tabla,$columna,$data)
    { 

        try 
        {
            
             $array_codificado = UtilHelper::arrayEncode($data);
             $array_deco = UtilHelper::arrayDecode($array_codificado); 
             $arrayPHP =  ($array_deco) ;
    
             $stmt = $this->conexion->query("INSERT INTO $tabla ($columna) VALUES ('".$arrayPHP['id_tn_n']."','".$arrayPHP['fecha_ingreso_tn']."','".$arrayPHP['int_op_tn']."','".$arrayPHP['cod_ref_n']."','".$arrayPHP['int_bolsas_tn']."','".$arrayPHP['int_undxpaq_tn']."','".$arrayPHP['int_undxcaja_tn']."','".$arrayPHP['int_desde_tn']."','".$arrayPHP['int_hasta_tn']."','".$arrayPHP['int_cod_empleado_tn']."','".$arrayPHP['int_cod_rev_tn']."','".$arrayPHP['contador_tn']."','".$arrayPHP['int_paquete_tn']."','".$arrayPHP['int_caja_tn']."','".$arrayPHP['b_borrado_n']."','".$arrayPHP['existeTiq_n']."' );"); 


        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }



    public function RegistrarFaltantes($tabla,$columna, $arrayPHP)
    { 

        try 
        {
             $int_desde_f=@$arrayPHP['int_desde_f'];
             $int_hasta_f=@$arrayPHP['int_hasta_f'];
             $int_total_f=@$arrayPHP['int_total_f'];  
          
             for ($a=0,$b=0,$c=0;$b<count($int_hasta_f);$a++,$b++,$c++)
             {
          
                //if(  !(empty($int_hasta_f[$a])) && !(empty($int_desde_f[$b]))  )  { 
                       
                      $desde = str_replace("'", '-', $int_desde_f[$a]);
                      $hasta = str_replace("'", '-', $int_hasta_f[$b]);

                      $stmt = $this->conexion->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['id_tn'] ."', '". $arrayPHP['int_op_tn'] ."', '" . $arrayPHP['int_paquete_tn'] . "', '" . $arrayPHP['int_caja_tn'] . "', '" . $desde  . "', '" . $hasta . "', '" . $int_total_f[$c] . "', '" . $arrayPHP['tipodesperdicio_f'] . "' );");
            

                  // } 
            } 

     
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }




    public function Update($tabla,$sets,$condicion,$columna="",$columna2="",$columna3="",$data)
    {
        try 
        { 
            $array_codificado = UtilHelper::arrayEncode($data);
            $array_deco = UtilHelper::arrayDecode($array_codificado); 
            $arrayPHP =  ($array_deco) ;
            $updatepro = $this->conexion->query("UPDATE $tabla SET $sets $condicion");//Update registro tbl_tiquete_numeracion 

             //$updatListado = $this->conexion->query("UPDATE tbl_numeracion SET id_tn_n = '". $arrayPHP['id_tn'] ."',cod_ref_n = '". $arrayPHP['ref_tn'] ."', int_bolsas_n = '". $arrayPHP['int_bolsas_tn'] ."',int_undxpaq_n = '". $arrayPHP['int_undxpaq_tn'] ."',int_undxcaja_n = '". $arrayPHP['int_undxcaja_tn'] ."',int_desde_n = '". $arrayPHP['int_desde_tn'] ."',int_hasta_n = '". $arrayPHP['int_hasta_tn'] ."',int_cod_empleado_n = '". $arrayPHP['int_cod_empleado_tn'] ."',int_cod_rev_n = '". $arrayPHP['int_cod_rev_tn'] ."',int_paquete_n = '". $arrayPHP['int_paquete_tn'] ."',int_caja_n = '". $arrayPHP['int_caja_tn'] ."' WHERE int_op_n = '". $arrayPHP['int_op_tn'] ."' ;" );//Update registro tbl_numeracion
 

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }

    }




    public function Consulta($tabla,$columna,$id,$columna2='',$id2='',$order='')
    {
        try 
        { 
      
          //Muestra grid Registro
           $Registro=self::buscarList($tabla,$columna,$id,$columna2,$id2,$order);
           //$Registro esto es Array
           if($Registro){
              echo json_encode( $Registro ); 
            exit();
           }else{
              echo 0;
             exit();
              }
         } catch (Exception $e) 
         {
             die($e->getMessage());
         }
     }




    public function ConsultaPaquetes($tabla,$columna,$id,$columna2='',$id2='',$order=''){
             try 
             { 
             
               //Muestra grid Registro
                $Registro=self::buscarList($tabla,$columna,$id,$columna2,$id2,$order);
                //$Registro esto es Array
                if($Registro){
                   echo json_encode( $Registro ); 
                 exit();
                }else{
                   echo 0;
                  exit();
                   }
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

    public function Delete($tabla,$id,$columna,$op='',$caja='')
    {

        try 
        { 
             // echo "DELETE FROM $tabla WHERE $columna = '$id' ";die;
              //Elimina Maestro 
               $stm = $this->conexion->query("DELETE FROM $tabla WHERE $columna = '$id' ");                
               return  $stm; 
           
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }



    public function DeleteFaltantes($tabla,$where)
    { 
        try 
        {  
            //echo "DELETE FROM $tabla WHERE $where ";die;
            $stm = $this->conexion->query("DELETE FROM $tabla WHERE $where ");     
            return  $stm; 

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }



    //BUSCAR ROW VARIOS CON ID
     public function buscarList($tabla,$columna,$id,$columna2='',$id2='',$order=''){
      if($columna2 !='' && $id2 !=''){
        $otracolumna = " AND $columna2 = '{$id2}' " ; 
      }else{
        $otracolumna = "";
      }
       //echo "SELECT * FROM $tabla WHERE  $columna = '{$id}' $otracolumna  $order " ; die;
       $resultado = $this->conexion->query("SELECT * FROM $tabla WHERE  $columna = '{$id}' $otracolumna  $order " ) or die($this->conexion->error);
       if($resultado) 
          
         return self::getResultados($resultado);
       return false;
       $resultado->free();
       $resultado->close();
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
