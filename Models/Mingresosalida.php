<?php
 

class oIngresosalida{
    private $db;
    private $ordenc;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->ordenc=array();
        /*$this->proveedores=array();
        $this->insumo=array();*/

    }
 

     //BUSCAR ID CONSECUTIVO
    public function buscarId($tabla, $columna ){

      try 
        {
       // echo "SELECT $columna AS id FROM $tabla ORDER BY $columna DESC LIMIT 0,1";die;
     
            if($tabla!='' && $columna!='' ){ 
                //echo "SELECT * FROM $tabla WHERE $columna = '$id' ";die;
               $stm = $this->db->query("SELECT $columna AS id FROM $tabla ORDER BY $columna DESC LIMIT 0,1") ;
               if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->ordenc[]=$filas;
                }

            return $this->ordenc;
               }
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function get_Provee(){

        try 
        {
            $consulta=$this->db->query("SELECT id_p, proveedor_p FROM proveedor  ORDER BY proveedor_p ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->proveedores[]=$filas;
            }
  
            return $this->proveedores;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function get_Insumo($control=''){

        try 
        {
          
             //echo "SELECT id_insumo,descripcion_insumo, valor_unitario_insumo FROM insumo WHERE descripcion_insumo LIKE '%($control)%' ORDER BY descripcion_insumo ASC";die;
            $consulta=$this->db->query("SELECT id_insumo,descripcion_insumo, valor_unitario_insumo FROM insumo WHERE clase_insumo IN (28) ORDER BY descripcion_insumo ASC");
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
            $consulta=$this->db->query("SELECT * FROM maquina WHERE proceso_maquina <>'0' ORDER BY nombre_maquina ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->maquina[]=$filas;
            }
    
            return $this->maquina;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function get_Ref($tabla, $where='', $order='',$columna=''){

        try 
        {
             //echo "SELECT $columna FROM $tabla $where $order ";die;
            $consulta=$this->db->query("SELECT $columna FROM $tabla $where $order ");
            if($consulta){
                 while($filas=$consulta->fetch_assoc()){
                     $this->proceso[]=$filas;
                  }
              return $this->proceso;
            }
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }



    public function Obtener($tabla,$columna,$id,$columna2='')
    {

        try 
        {
            if($tabla!='' && $columna!='' && $id!='' ){ 
                
             if($columna=="fecharecepcion"){
                 //echo "SELECT * FROM $tabla WHERE YEAR($columna) = '$id' $columna2 ";die;
                 $stm = $this->db->query("SELECT * FROM $tabla WHERE YEAR($columna) = '$id' $columna2 ");   
               }else{
                 // echo "SELECT * FROM $tabla WHERE $columna = '$id' $columna2 ";die;
                 $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna = '$id' $columna2 ");
            }

               if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->ordenc[]=$filas;
                }

            return $this->ordenc;
               }
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function Listaritems($tabla, $order='',$group='',$where='', $columna='')
    {

        try 
        {

            if($tabla!='' ){ 
                 //echo "SELECT $columna FROM $tabla  $where $group $order ";die;
                $stm = $this->db->query("SELECT $columna FROM $tabla  $where $group $order ");// WHERE clase_insumo IN (28) 
               if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->ordenc[]=$filas;
                }

            return $this->ordenc;
               }
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function get_Proceso(){

        try 
        {
            $consulta=$this->db->query("SELECT * FROM tipo_procesos ORDER BY id_tipo_proceso DESC");
            while($filas=$consulta->fetch_assoc()){
                $this->proceso[]=$filas;
            }
    
            return $this->proceso;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function RegistrarVerif($tabla,$columnas, $data)
    { 

        try 
        {

                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
                 $consulta=$this->db->query("SELECT * FROM $tabla WHERE oc = '". $arrayPHP['n_oc_vi'] ."' AND nombre='".$arrayPHP['id_insumo_vi']."' ");
                 while($filas=$consulta->fetch_assoc()){
                    $this->sicoq[]=$filas;
                 }
                 
                 $IdUpdate = $this->sicoq[0]['id_i']; 
                 $ingresoKilos = $this->sicoq[0]['ingresokilos'];
                 if($arrayPHP['cantidad_recibida_bk']!='')//si se esta editando desde verificacion insumo edit 
                 {
                   $ingresoKilos = ($ingresoKilos - $arrayPHP['cantidad_recibida_bk']);
                 } 
                   $ingresoKilos = ($ingresoKilos + $arrayPHP['cantidad_recibida_vi']);
           
                 $salidakilos=$this->sicoq[0]['salidakilos']=='' ? 0 : $this->sicoq[0]['salidakilos'];
                 $inventariofinal=$this->sicoq[0]['inventariofinal']=='' ? 0 : $this->sicoq[0]['inventariofinal'];
                 $totalkConsumo = $ingresoKilos - ($salidakilos+$inventariofinal); 

                if(is_null($IdUpdate)){

                   $stmt = $this->db->query("INSERT INTO $tabla ($columnas) VALUES ( '". $arrayPHP['id_insumo_vi'] ."','". $ingresoKilos ."','". $arrayPHP['fecha_registro_vi'] ."','". $arrayPHP['n_oc_vi'] ."','0','$salidakilos','". $inventariofinal ."','". $totalkConsumo ."','". $arrayPHP['registro_vi'] ."','". $arrayPHP['registro_vi'] ."' );");
                }else{
             
                   $stmt = $this->db->query("UPDATE $tabla SET nombre='". $arrayPHP['id_insumo_vi'] ."',  ingresokilos='". $ingresoKilos ."', fecharecepcion='". $arrayPHP['fecha_registro_vi'] ."', oc='". $arrayPHP['n_oc_vi'] ."', fechasalida='0', salidakilos='$salidakilos', inventariofinal='". $inventariofinal ."', totalconsumo='". $totalkConsumo ."', responsable='". $arrayPHP['registro_vi'] ."', modificado='". $_SESSION['Usuario'] ."' WHERE id_i = '". $IdUpdate ."';");  
                   echo $stmt; 
                } 
       
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function Registrar($tabla,$columnas, $data)
    { 

        try 
        {

                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
                 $consulta=$this->db->query("SELECT * FROM $tabla WHERE oc = '". $arrayPHP['oc'] ."' AND nombre='".$arrayPHP['nombre']."' ");
                 while($filas=$consulta->fetch_assoc()){
                    $this->sicoq[]=$filas;
                 }
                 
                 $IdUpdate = $this->sicoq[0]['id_i'];
                 $ingresoKilos = ($this->sicoq[0]['ingresokilos'] + $arrayPHP['ingresokilos']);
                 $salidakilos=$this->sicoq[0]['salidakilos'] + $arrayPHP['salidakilos'];
                 $inventariofinal=$this->sicoq[0]['inventariofinal']=='' ? 0 : $this->sicoq[0]['inventariofinal'];
                 $totalkConsumo = ($salidakilos+$inventariofinal); //$ingresoKilos - 

                if(is_null($IdUpdate)){

                   $stmt = $this->db->query("INSERT INTO $tabla ($columnas) VALUES (  '". $arrayPHP['id_i'] ."', '". $arrayPHP['nombre'] ."','$ingresoKilos','". $arrayPHP['fecharecepcion'] ."','". $arrayPHP['oc'] ."','". $arrayPHP['fechasalida'] ."','$salidakilos','$inventariofinal','$totalkConsumo','". $arrayPHP['responsable'] ."','". $arrayPHP['modificado'] ."' );");
                }else{
             
                   $stmt = $this->db->query("UPDATE $tabla SET nombre='". $arrayPHP['nombre'] ."',  ingresokilos='$ingresoKilos', fecharecepcion='". $arrayPHP['fecharecepcion'] ."', oc='". $arrayPHP['oc'] ."', fechasalida='". $arrayPHP['fechasalida'] ."', salidakilos='$salidakilos', inventariofinal='$inventariofinal', totalconsumo='$totalkConsumo', responsable='". $arrayPHP['responsable'] ."', modificado='". $_SESSION['Usuario'] ."' WHERE id_i = '". $IdUpdate ."';");  
                   echo $stmt; 
                } 
       
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function RegistrarItems($tabla,$columnas, $arrayPHP)
    { 

        try 
        { 
               
               if( !(empty($arrayPHP['id_i']))  )  { 
   
                $stmt = $this->db->query("INSERT INTO $tabla ($columnas) VALUES ( '". $arrayPHP['id_i'] ."', '". $arrayPHP['nombre'] ."','". $arrayPHP['ingresokilos'] ."','". $arrayPHP['fecharecepcion'] ."','". $arrayPHP['oc'] ."','". $arrayPHP['fechasalida'] ."','". $arrayPHP['salidakilos'] ."','". $arrayPHP['inventariofinal'] ."','". $arrayPHP['totalconsumo'] ."','". $arrayPHP['responsable'] ."','". $arrayPHP['modificado'] ."' );");
 
                  } 
          
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function Update($ids,$valorid,$name,$valorc,$tabla)
    { 
        try 
        { 
            $fecha = date("Y-m-d");  
             
            $update = $this->db->query("UPDATE $tabla SET $name= '$valorc' WHERE $ids = $valorid ");
            
          //die;//dejarlo para q no bote error
        } catch (Exception $e) 
        {
             $update=0;
            die($e->getMessage());
        }
         return $update;
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
               $stm = $this->db->query("DELETE FROM tbl_ingresosalida WHERE $columna = '$id' "); 
              //Elimina Items
               $stmi = $this->db->query("DELETE FROM $tabla WHERE $columna = '$id' ");                
            }else{
               //Elimina Items
               $stmi = $this->db->query("DELETE FROM $tabla WHERE id_i = $id ");      

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
