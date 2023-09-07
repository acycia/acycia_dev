<?php
//include_once("./Models/Mconnection.php"); 

class oSquimicaslist{
    private $db;
    private $consultar;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->consultar=array();
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
                    $this->consultar[]=$filas;
                }

            return $this->consultar;
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
            $consulta=$this->db->query("SELECT id_p, proveedor_p FROM proveedor WHERE tipo_provee='1' ORDER BY proveedor_p ASC");
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
            $control = $control =='NO' ? 'NO CONTROLADO' : 'CONTROLADO';
             //echo "SELECT id_insumo,descripcion_insumo, valor_unitario_insumo FROM insumo WHERE descripcion_insumo LIKE '%($control)%' ORDER BY descripcion_insumo ASC";die;
            $consulta=$this->db->query("SELECT id_insumo,descripcion_insumo, valor_unitario_insumo FROM insumo WHERE descripcion_insumo LIKE '%($control)%' ORDER BY descripcion_insumo ASC");
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


    public function Obtener($tabla,$columna,$id,$columna2='')
    {

        try 
        {
            if($tabla!='' && $columna!='' && $id!='' ){ 
                //echo "SELECT * FROM $tabla WHERE $columna = '$id' $columna2 ";die;
                $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna = '$id' $columna2 ");
               if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->consultar[]=$filas;
                }

            return $this->consultar;
               }
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function multiConsulta($consulta1,$consulta2='',$order='')
    {

        try 
        {
            if($consulta1!=''  ){ 
                  //echo " $consulta1 $consulta2 ";die;
                $stm = $this->db->query(" $consulta1 $consulta2 ");
               if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->consultar =$filas;//$this->consultar[]=$filas;
                }

            return $this->consultar;
               }
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }  

    public function multiConsultaRow($consulta1,$consulta2='',$order='')
    {

        try 
        {
            if($consulta1!=''  ){ 
                  //echo " $consulta1 $consulta2 ";die;
                $stm = $this->db->query(" $consulta1 $consulta2 ");
               if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->consultar[]=$filas;//$this->consultar[]=$filas;
                }

            return $this->consultar;
               }
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }    



    public function ObtenerFecha($tabla,$columna,$id,$columna2='',$id2='')
    {

        try 
        {
            if($tabla!='' && $columna!='' && $id!='' ){ 
                  //echo "SELECT * FROM $tabla WHERE $columna LIKE '%$id%'  $columna2 ";die;
                $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna LIKE '%$id%'  $columna2 ");
               if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->consultar[]=$filas;
                }

            return $this->consultar;
               }
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Listaritems($tabla, $order='', $columna='')
    {

        try 
        {

            if($tabla!='' ){ 
                //echo "SELECT * FROM $tabla $columna ORDER BY $order ASC ";die;
                $stm = $this->db->query("SELECT * FROM $tabla $columna ORDER BY $order ASC ");
               if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->consultar[]=$filas;
                }

            return $this->consultar;
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

    public function Registrar($tabla,$columnas, $data)
    { 

        try 
        {

                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
                
                //$arrayPHP['autorizacion'] = $arrayPHP['autorizacion']  == '' ? $arrayPHP['ccit_autorizacion'] : $arrayPHP['autorizacion'];//cuando se consulta año anterior y se ingresa item   
                $consulta=$this->db->query("SELECT * FROM $tabla WHERE autorizacion = '". $arrayPHP['autorizacion'] ."'  ");
                 while($filas=$consulta->fetch_assoc()){
                    $this->sicoq[]=$filas;
                 }

                if(is_null($this->sicoq)){
                   $stmt = $this->db->query("INSERT INTO $tabla ($columnas) VALUES ( '". $arrayPHP['autorizacion'] ."','". $arrayPHP['anyo'] ."','". $arrayPHP['kilospermitidosmes'] ."','". $arrayPHP['kilosdisponiblescompra'] ."','". $arrayPHP['totalingresados'] ."','". $arrayPHP['totalsalida'] ."','". $arrayPHP['totalinventario'] ."','". $arrayPHP['fecha_inicio'] ."','". $arrayPHP['fecha_vence'] ."','". $arrayPHP['userfile'] ."'  );"); 
                }else{
 
                   $stmt = $this->db->query("UPDATE $tabla SET anyo='". $arrayPHP['anyo'] ."', kilospermitidosmes='". $arrayPHP['kilospermitidosmes'] ."',kilosdisponiblescompra='". $arrayPHP['kilosdisponiblescompra'] ."',totalingresados='". $arrayPHP['totalingresados'] ."',totalsalida='". $arrayPHP['totalsalida'] ."',totalinventario='". $arrayPHP['totalinventario'] ."',fecha_inicio='". $arrayPHP['fecha_inicio'] ."',fecha_vence='". $arrayPHP['fecha_vence'] ."',userfile='". $arrayPHP['userfile'] ."' WHERE autorizacion = '". $arrayPHP['autorizacion'] ."';");  
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
               //$arrayPHP['autorizacion'] = $arrayPHP['autorizacion']  == '' ? $arrayPHP['ccit_autorizacion'] : $arrayPHP['autorizacion'];//cuando se consulta año anterior y se ingresa item     
               if( !(empty($arrayPHP['nombre'])) && !(empty($arrayPHP['proveedor'])) && !(empty($arrayPHP['factura'])) && !(empty($arrayPHP['autorizacion'])) && !(empty($arrayPHP['responsable'])) && !(empty($arrayPHP['revisado'])) && !(empty($arrayPHP['aprobado'])) && !(empty($arrayPHP['modificado'])) )  { 
                
                $arrayPHP['autorizacion'] = $arrayPHP['autorizacion']  == '' ? $arrayPHP['ccit_autorizacion'] : $arrayPHP['autorizacion'];//cuando se consulta año anterior y se ingresa item

                $stmt = $this->db->query("INSERT INTO $tabla ($columnas) VALUES ( '". $arrayPHP['nombre'] ."','". $arrayPHP['ingresokilos'] ."','". $arrayPHP['fecha_recepcion'] ."','". $arrayPHP['proveedor'] ."','". $arrayPHP['costound'] ."','". $arrayPHP['factura'] ."','". $arrayPHP['area'] ."','". $arrayPHP['fecha_salida'] ."','". $arrayPHP['salidakilos'] ."','". $arrayPHP['numeradora'] ."','". $arrayPHP['autorizacion'] ."','". $arrayPHP['ccit_autorizacion'] ."','". $arrayPHP['op'] ."','". $arrayPHP['controladas'] ."','". $arrayPHP['responsable'] ."','". $arrayPHP['revisado'] ."','". $arrayPHP['aprobado'] ."','". $arrayPHP['modificado'] ."'  );");
 
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
               $stm = $this->db->query("DELETE FROM tbl_sicoq WHERE $columna = '$id' "); 
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
