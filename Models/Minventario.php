<?php
 

class oInventario{
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


 

    public function get_Insumo(){

        try 
        {
           
            $consulta=$this->db->query("SELECT id_insumo,descripcion_insumo, valor_unitario_insumo FROM insumo WHERE descripcion_insumo ORDER BY descripcion_insumo ASC");
            while($filas=$consulta->fetch_assoc()){
                $this->insumo[]=$filas;
            }
    
            return $this->insumo;
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
            while($filas=$consulta->fetch_assoc()){
                $this->proceso[]=$filas;
            }
    
            return $this->proceso;
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
                // echo "SELECT * FROM $tabla WHERE $columna = '$id' $columna2 ";die;
                $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna = '$id' $columna2 ");
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
 

    public function Registrar($tabla,$columnas, $data)
    { 

        try 
        {

                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
                
                  
                $consulta=$this->db->query("SELECT * FROM $tabla WHERE autorizacion = '". $arrayPHP['autorizacion'] ."'  ");
                 while($filas=$consulta->fetch_assoc()){
                    $this->sicoq[]=$filas;
                 }

                if(is_null($this->sicoq)){
                   $stmt = $this->db->query("INSERT INTO $tabla ($columnas) VALUES ( '". $arrayPHP['referencia'] ."','". $arrayPHP['fecha'] ."','". $arrayPHP['inventario'] ."','". $arrayPHP['despacho'] ."','". $arrayPHP['disponible'] ."' );"); 
                }else{
 
                   $stmt = $this->db->query("UPDATE $tabla SET referencia='". $arrayPHP['referencia'] ."', fecha='". $arrayPHP['fecha'] ."',inventario='". $arrayPHP['inventario'] ."',despacho='". $arrayPHP['despacho'] ."',disponible='". $arrayPHP['disponible'] ."' ;");  
                   echo $stmt; 
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
