<?php
require_once 'Models/Mremision.php';

class oRemision{
    private $db;
    private $ordenc;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->ordenc=array();

    }

    public function get_Listar(){

        try 
        {
            $consulta=$this->db->query("SELECT * FROM tbl_remisiones WHERE fecha_ingreso_oc > ('2021-06-18') ;");
            while($filas=$consulta->fetch_assoc()){
                $this->ordenc[]=$filas;
            }

            return $this->ordenc;
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

 

    public function Obtener($tabla,$columna,$id)
    {

        try 
        { 
            //echo"SELECT * FROM $tabla WHERE $columna = $id ORDER BY $columna DESC";
            $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna = $id ORDER BY $columna DESC");
            if($stm){
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



    public function ObtenerId($tabla,$columna,$columna2,$id)
    {

        try 
        { 
            $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna = $id ORDER BY $columna2 DESC LIMIT 1");
            if($stm){
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
            foreach ($data as $data) {
                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
                $Date = date("Y-m-d H:i:s"); 
                $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['int_remision'] ."','". $arrayPHP['str_numero_oc_r'] ."','". $arrayPHP['fecha_r'] ."','". $arrayPHP['str_encargado_r'] ."','". $arrayPHP['str_transportador_r'] ."','". $arrayPHP['str_guia_r'] ."','". $arrayPHP['str_elaboro_r'] ."','". $arrayPHP['str_aprobo_r'] ."','". $arrayPHP['str_observacion_r'] ."','". $arrayPHP['factura_r'] ."','". $arrayPHP['b_borrado_r'] ."','". $arrayPHP['ciudad_pais'] ."','". $_SESSION['Usuario'] . '-' . $Date ."'  );");
                if($arrayPHP['autorizado']){

                   $hoy = date("Y-m-d H:i:s");  
                   $usuario = $_SESSION['Usuario'];
                   $logs = $this->db->query("INSERT INTO tbl_logs (codigo_id, descrip, fecha, modificacion, usuario) VALUES ('". $arrayPHP['id_pedido'] ."','OC','$hoy','autorizado SI','$usuario')" );
              
                }

            }
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


    public function RegistrarItems($tabla,$columna, $data)
    { 

        try 
        {
            foreach ($data as $data) {
                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
                $Date = date("Y-m-d H:i:s"); 
                $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '" . $arrayPHP['int_remision_r_rd'] . "', '" . $arrayPHP['str_numero_oc_rd'] . "', '" . $arrayPHP['fecha_rd'] . "', '" . $arrayPHP['int_item_io_rd'] . "', '" . $arrayPHP['int_caja_rd'] . "', '" . $arrayPHP['int_mp_io_rd'] . "', '" . $arrayPHP['int_ref_io_rd'] . "', '" . $arrayPHP['str_ref_cl_io_rd'] . "', '" . $arrayPHP['int_numd_rd'] . "', '" . $arrayPHP['int_numh_rd'] . "', '" . $arrayPHP['int_cant_rd'] . "', '" . $arrayPHP['int_peso_rd'] . "', '" . $arrayPHP['int_pesoneto_rd'] . "', '" . $arrayPHP['int_total_cajas_rd'] . "', '" . $arrayPHP['int_tolerancia_rd'] . "',  '" . $arrayPHP['str_direccion_desp_rd'] . "', '" . $arrayPHP['estado_rd'] . "','". $_SESSION['Usuario'] . '-' . $Date ."'  );"); 

            }
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Eliminar($id)
    {
        try 
        {
            $stm = $this->db->query("DELETE FROM tbl_remisiones WHERE id_pedido = $id");                      

            $stm->execute(array($id));
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Actualizar(oRemision $data)
    {
        try 
        {
            $sql = "UPDATE tbl_remisiones SET 
            str_numero_oc         = ?, 
            fecha_ingreso_oc      = ?,
            str_condicion_pago_oc = ? 
            WHERE id_pedido = ?";

            $this->db->prepare($sql)
            ->execute(
                array( 
                    $data->__GET('str_numero_oc'),
                    $data->__GET('fecha_ingreso_oc'),
                    $data->__GET('str_condicion_pago_oc'),
                    $data->__GET('id_pedido')
                )
            );
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
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
