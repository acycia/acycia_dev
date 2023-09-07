<?php
require_once("Models/Referencias.php");
//include_once("./Models/Mconnection.php");
 
class Referencias{
    private $db;
    private $referencia;

    public function __construct(){
        $this->db=Conectar::conexion();
        $this->referencia=array();

    }

    public function get_Listar(){

        try 
        {
            $consulta=$this->db->query("SELECT * FROM tbl_referencia_historico ");
            while($filas=$consulta->fetch_assoc()){
                $this->referencia[]=$filas;
            }

            return $this->referencia;
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
            while($filas=$stm->fetch_assoc()){
                $this->referencia[]=$filas;
            }

            return $this->referencia;
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
                $stmt = $this->db->query("INSERT INTO $tabla ($columna) VALUES ( '". $arrayPHP['id_ref'] ."','". $arrayPHP['cod_ref'] ."','". $arrayPHP['version_ref'] ."','". $arrayPHP['n_egp_ref'] ."','". $arrayPHP['n_cotiz_ref'] ."','". $arrayPHP['tipo_bolsa_ref'] ."','". $arrayPHP['material_ref'] ."','". $arrayPHP['Str_presentacion'] ."','". $arrayPHP['Str_tratamiento'] ."','". $arrayPHP['ancho_ref'] ."','". $arrayPHP['N_repeticion_l'] ."','". $arrayPHP['N_diametro_max_l'] ."','". $arrayPHP['N_peso_max_l'] ."','". $arrayPHP['N_cantidad_metros_r_l'] ."','". $arrayPHP['N_embobinado_l'] ."','". $arrayPHP['Str_referencia_m'] ."','". $arrayPHP['Str_linc_m'] ."','". $arrayPHP['largo_ref'] ."','". $arrayPHP['solapa_ref'] ."','". $arrayPHP['b_solapa_caract_ref'] ."','". $arrayPHP['bolsillo_guia_ref'] ."','". $arrayPHP['str_bols_ub_ref'] ."','". $arrayPHP['str_bols_fo_ref'] ."','". $arrayPHP['B_cantforma'] ."','". $arrayPHP['bol_lamina_1_ref'] ."','". $arrayPHP['bol_lamina_2_ref'] ."','". $arrayPHP['calibre_ref'] ."','". $arrayPHP['peso_millar_ref'] ."','". $arrayPHP['Str_boca_entr_p'] ."','". $arrayPHP['Str_entrada_p'] ."','". $arrayPHP['Str_lamina1_p'] ."','". $arrayPHP['Str_lamina2_p'] ."','". $arrayPHP['B_troquel'] ."','". $arrayPHP['B_precorte'] ."','". $arrayPHP['N_fuelle'] ."','". $arrayPHP['B_fondo'] ."','". $arrayPHP['impresion_ref'] ."','". $arrayPHP['num_pos_ref'] ."','". $arrayPHP['cod_form_ref'] ."','". $arrayPHP['adhesivo_ref'] ."','". $arrayPHP['estado_ref'] ."','". $arrayPHP['registro1_ref'] ."','". $arrayPHP['fecha_registro1_ref'] ."','". $arrayPHP['registro2_ref'] ."','". $arrayPHP['fecha_registro2_ref'] ."','". $arrayPHP['B_generica'] ."','". $arrayPHP['calibreBols_ref'] ."','". $arrayPHP['peso_millar_bols'] ."','". $arrayPHP['precorte_cuerpo'] ."','". $arrayPHP['precorte_solapa'] ."','". $arrayPHP['tipoLamina_ref'] ."','". $arrayPHP['tipoCinta_ref'] ."','". $_SESSION['Usuario'] . '-' . $Date ."','". $arrayPHP['valor_impuesto'] ."' );"); 

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

    public function Actualizar(Referencias $data)
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
?>
