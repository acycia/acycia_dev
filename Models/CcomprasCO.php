<?php
require_once 'Models/CcomprasCO.php';

class oComprasCO{
    private $db;
    private $ordenc;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->ordenc=array();
        /*$this->proveedores=array();
        $this->insumo=array();*/

    }
 

    public function get_Provee(){

        try 
        {
            $consulta=$this->db->query("SELECT id_p, proveedor_p FROM proveedor ORDER BY proveedor_p ASC");
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
            $consulta=$this->db->query("SELECT id_insumo,descripcion_insumo, valor_unitario_insumo FROM insumo ORDER BY descripcion_insumo ASC");
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


    public function Obtener($tabla,$columna,$id)
    {
    
        try 
        {
            if($tabla!='' && $columna!='' && $id!=''){ 
                $stm = $this->db->query("SELECT *  FROM $tabla WHERE $columna = '$id' ");
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

    public function Update($tabla,$id,$valor,$columna,$proceso)
    { 
        try 
        { 
            $fecha = date("Y-m-d H:i:s");  
        
            $update = $this->db->query("UPDATE $tabla SET pago = 'SI', fecha_pago = '$fecha' WHERE id = $id " ); 
          die;//dejarlo para q no bote error
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
