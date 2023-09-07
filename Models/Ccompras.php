<?php
require_once 'Models/Ccompras.php';

class oCompras{
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

    public function Registrar($tabla,$columna, $data)
    { 

        try 
        {
    
                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
                
                 
                $consulta=$this->db->query("SELECT * FROM $tabla WHERE proforma = '". $arrayPHP['proforma'] ."' AND proceso= '". $arrayPHP['proceso'] ."' ");
                 while($filas=$consulta->fetch_assoc()){
                    $this->insumo[]=$filas;
                 }

                if(is_null($this->insumo)){
                   $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['proforma'] ."','". $arrayPHP['pedido'] ."','". $arrayPHP['factura'] ."','". $arrayPHP['proceso'] ."','". $arrayPHP['proveedor'] ."','". $arrayPHP['fecha'] ."','". $arrayPHP['bodega'] ."','". $arrayPHP['tipopedido'] ."','". $arrayPHP['tipoinsumo'] ."','". $arrayPHP['maquina'] ."','". $arrayPHP['plazo'] ."','". $arrayPHP['valorplazo'] ."','" . $arrayPHP['adjunto'] . "','" . $arrayPHP['usuario'] . "','" . $arrayPHP['estado'] . "'  );"); 
                }


       
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function RegistrarItems($tabla,$columna, $arrayPHP)
    { 

        try 
        { 
      
   
            $cantidad=$arrayPHP['cantidad'];
            $code=$arrayPHP['code'];
            $descripcion=$arrayPHP['descripcion'];
            $moneda=$arrayPHP['moneda'];
            $precio=$arrayPHP['precio'];
            $precio_total=$arrayPHP['precio_total'];
            $incoterm=$arrayPHP['incoterm'];
            $valoricot=$arrayPHP['valoricot'];
            $estado=$arrayPHP['estado'];
            $medida=$arrayPHP['medida'];

            for ($d=0,$e=0,$f=0,$g=0,$h=0,$i=0,$j=0,$k=0,$l=0;$d<count($cantidad);$d++,$e++,$f++,$g++,$h++,$i++,$j++,$k++,$l++){
  
               if( !(empty($cantidad[$d])) && !(empty($code[$e]))&& !(empty($descripcion[$f]))&& !(empty($moneda[$g]))&& !(empty($precio[$h]))&& !(empty($precio_total[$i]))  )  { 
                $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['proforma'] ."', '" . $arrayPHP['pedido'] . "', '" . $arrayPHP['factura'] . "', '" . $arrayPHP['proceso'] . "', '" . $cantidad[$d] . "', '" . $medida[$l] . "', '" . $code[$e] . "', '" . $descripcion[$f] . "', '" . $moneda[$g] . "', '" . $precio[$h] . "', '" . $precio_total[$i] . "', '" . $incoterm[$j] . "', '" . $valoricot[$k] . "', '" . $arrayPHP['estado'] . "' );");
 

                  } 
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
