<?php
require_once 'Models/Occomercial.php';

class oComercial{
    private $db;
    private $ordenc;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->ordenc=array();

    }

    public function get_Listar(){

        try 
        {
            $consulta=$this->db->query("SELECT * FROM tbl_orden_compra WHERE fecha_ingreso_oc > ('2021-06-18') ;");
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



    public function RegistrarGen($tabla,$columnas, $data)
    { 

    foreach($columnas as $key){
        $columnasCant = implode(', ', $columnas);
    }
        $valores = Cadenas::quitoYagregocomas($data);
    
        //echo "INSERT INTO $tabla ($columnasCant) VALUES ($valores);";die;
        try 
        {

                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco);
    
                $stmt = $this->db->query("INSERT INTO $tabla ($columnasCant) VALUES ($valores);"); 
              
       
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
                $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['id_pedido'] ."','". $arrayPHP['str_numero_oc'] ."','". $arrayPHP['id_c_oc'] ."','". $arrayPHP['str_nit_oc'] ."','". $arrayPHP['fecha_ingreso_oc'] ."','". $arrayPHP['fecha_entrega_oc'] ."','". $arrayPHP['str_condicion_pago_oc'] ."','". $arrayPHP['str_observacion_oc'] ."','". $arrayPHP['int_total_oc'] ."','". $arrayPHP['b_facturas_oc'] ."','". $arrayPHP['b_num_remision_oc'] ."','". $arrayPHP['b_factura_cirel_oc'] ."','". $arrayPHP['str_dir_entrega_oc'] ."','". $arrayPHP['str_archivo_oc'] ."','". $arrayPHP['adjunto2'] ."','". $arrayPHP['adjunto3'] ."','". $arrayPHP['str_elaboro_oc'] ."','". $arrayPHP['str_aprobo_oc'] ."','". $arrayPHP['b_estado_oc'] ."','". $arrayPHP['str_responsable_oc'] ."','". $arrayPHP['b_borrado_oc'] ."','". $arrayPHP['salida_oc'] ."','". $arrayPHP['b_oc_interno'] ."','". $arrayPHP['vta_web_oc'] ."','". $arrayPHP['expo_oc'] ."','". $arrayPHP['autorizado'] ."','". $arrayPHP['tb_pago'] ."','". $arrayPHP['factura_oc'] ."','". $arrayPHP['entrega_fac'] ."','". $arrayPHP['fecha_cierre_fac'] ."','". $arrayPHP['comprobante_ent'] ."','". $arrayPHP['estado_cartera'] ."','". $arrayPHP['tipo_pago_cartera'] ."','". $arrayPHP['valor_cartera'] ."','". $_SESSION['Usuario'] . '-' . $Date ."'  );");
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
                $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '" . $arrayPHP['id_items'] . "', '" . $arrayPHP['id_pedido_io'] . "', '" . $arrayPHP['str_numero_io'] . "', '" . $arrayPHP['int_consecutivo_io'] . "', '" . $arrayPHP['int_cod_ref_io'] . "', '" . $arrayPHP['id_mp_vta_io'] . "', '" . $arrayPHP['int_cod_cliente_io'] . "', '" . $arrayPHP['int_cantidad_io'] . "', '" . $arrayPHP['int_cantidad_rest_io'] . "', '" . $arrayPHP['str_unidad_io'] . "', '" . $arrayPHP['fecha_entrega_io'] . "', '" . $arrayPHP['fecha_modif_io'] . "', '" . $arrayPHP['responsable_modif_io'] . "', '" . $arrayPHP['trm'] . "', '" . $arrayPHP['int_precio_trm'] . "',  '" . $arrayPHP['int_precio_io'] . "', '" . $arrayPHP['int_total_item_io'] . "', '" . $arrayPHP['str_moneda_io'] . "', '" . $arrayPHP['str_direccion_desp_io'] . "', '" . $arrayPHP['int_vendedor_io'] . "', '" . $arrayPHP['int_comision_io'] . "', '" . $arrayPHP['int_nombre_io'] . "', '" . $arrayPHP['b_estado_io'] . "', '" . $arrayPHP['cobra_cyrel'] . "', '" . $arrayPHP['cobra_flete'] . "', '" . $arrayPHP['precio_flete'] . "', '". $_SESSION['Usuario'] . '-' . $Date ."'  );"); 

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
            $stm = $this->db->query("DELETE FROM tbl_orden_compra WHERE id_pedido = $id");                      

            $stm->execute(array($id));
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function Actualizar(oComercial $data)
    {
        try 
        {
            $sql = "UPDATE tbl_orden_compra SET 
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


class Cadenas {
 
   static function quitoYagregocomas($data){
       foreach($data as $key){
           
           $datosCant .=  "'" .$key ."'," ;//agrego comillas y comas
           $cadena = substr($datosCant, 0, -1);//quito ultima coma

       }
       return $cadena;
    }
 
}

 
?>
