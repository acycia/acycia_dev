<?php
require_once 'Models/Mgeneral.php';
include('funciones/adjuntar.php'); 
require_once('envio_correo/envio_correos.php'); 

class omGeneral{
    private $db;
    private $logs;
 
    public function __construct(){
        $this->db=Conectar::conexion();
        $this->logs=array(); 

    }
  
 

  //BUSCAR ID CONSECUTIVO
    public function buscarIds($tabla, $columna ){

            try 
            { 
                
                $stm = $this->db->query("SELECT $columna as id FROM $tabla ORDER BY $columna DESC LIMIT 1");
                if($stm){
                while($filas=$stm->fetch_assoc()){
                    $this->logs=$filas;
                }
      
                return $this->logs["id"];

                }
            } catch (Exception $e) 
            {
                die($e->getMessage());
            }
        }



    public function UpdateGen($columna,$valorid,$valores,$tabla)
    { 

         
        try 
        { 
            $fecha = date("Y-m-d");  
             
            //$valores = Cadenas::quitoYagregocomas($valores);
           // echo ("UPDATE $tabla SET $valores WHERE $columna = $valorid ");die;
            $update = $this->db->query("UPDATE $tabla SET $valores WHERE $columna = $valorid ");
            
          //die;//dejarlo para q no bote error
        } catch (Exception $e) 
        {
             $update=0;
            die($e->getMessage());
        }
         return $update;
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




    public function RegistrarLogs($tabla,$campos,$data,$vista='',$modificacion)
    { 

        try 
        {
                $array_codificado = UtilHelper::arrayEncode($data);
                $array_deco = UtilHelper::arrayDecode($array_codificado); 
                $arrayPHP =  ($array_deco) ;
     
                $hoy = date("Y-m-d H:i:s");  
                $usuario = $_SESSION['Usuario'];
                $id = $arrayPHP['id_c'];
          
                  $stmt = $this->db->query("INSERT INTO $tabla ($campos) VALUES ( '". $id ."','". $vista ."','". $hoy ."','". $modificacion ."','". $usuario ."' );");
              
               echo $stmt;
       
        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

 

    public function ConsultarLogsExiste($tabla='',$columna='',$valorid='' )
    {

        try 
        {
 
            if($tabla!='' && $columna!='' && $valorid!=''){ 
              $this->fechas=array(); 
                $fecha = $this->db->query("SELECT DATE_FORMAT(fecha, '%Y-%m-%d') as fecha FROM $tabla WHERE descrip='CLIENTE' AND $columna = '$valorid' ORDER BY fecha DESC LIMIT 1 "); 
                  while($filas=$fecha->fetch_assoc()){
                      $this->fechas[]=$filas;
                  }
                  $hoy = date("Y-m-d H:i:s");  
                  $fecharegistro =$this->fechas[0]["fecha"]; 
                  $unmes = date("Y-m-d H:i:s",strtotime($fecharegistro."+ 1 month"));  

                  $stm = $this->db->query("SELECT *,DATE_FORMAT(fecha, '%Y-%m-%d') fechanueva FROM $tabla WHERE descrip='CLIENTE' AND $columna = '$valorid' and '$hoy' BETWEEN '$fecharegistro' AND '$unmes' ORDER BY fecha DESC LIMIT 1 "); 
                  while($filas=$stm->fetch_assoc()){
                      $this->logs[]=$filas;
                  }
                
               return $this->logs[0]["fechanueva"];  
            }

        } catch (Exception $e)  {
            die($e->getMessage());
        }
    }



    public function ConsultarLogs($tabla='',$columna='',$valorid='')
    {

        try 
        {
           $hoy = date("Y-m-d H:i:s");  
    
            if($tabla!='' && $columna!='' && $valorid!=''){ 
                $stm = $this->db->query("SELECT * FROM $tabla WHERE $columna = '$valorid'  and fecha <= '$hoy' ");
                while($filas=$stm->fetch_assoc()){
                    $this->logs[]=$filas;
                }  
            return $this->logs;
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

    public function ConsultarNormal($tabla='',$where='', $columna='',$columna2='',$valorid='',$sms='',$fecha='', $order='',$columnaretor='')
    {
 
        try 
        {
      
         
            if($tabla!='' && $columna!='' && $valorid!=''){ 
                //echo "SELECT $columnaretor FROM $tabla $where   $columna = '$valorid' $order  ";die;
                $stm = $this->db->query("SELECT $columnaretor FROM $tabla $where   $columna = '$valorid' $order  ");
               
                 
                if($stm) 
                  return self::getResultados($stm);
                return false;
                $resultado->free();
                $resultado->close();
 
             
                
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }

        public function ConsultarTodos($tabla,$distinct,$where='', $order='' )
    {
 
        try 
        {
      
         
            if($tabla!='' && $where!='' ){ 
               //echo "SELECT $distinct FROM $tabla $where ";die;
                $stm = $this->db->query("SELECT $distinct as id  FROM $tabla $where ");
               
                 
                if($stm) 
                  return self::getResultados($stm);
                return false;
                $resultado->free();
                $resultado->close();
 
             
                
            }

        } catch (Exception $e) 
        {
            die($e->getMessage());
        }
    }


     public function envioCorreos($sms){

       
         //envio correo
         $enviar = new EnvioEmails();    
         $to =  'auxcartera@acycia.com';//'auxcartera@acycia.com';
         $to2 = ''; 
         $file = ''; 
         $from = 'Urgente Cliente Modificado';
         $asunto = "Se Modifico un Cliente por Comercial";
         $body='Saludos, ' . $sms . ' <br> Este Cliente fue modificado por Comercial! Verifique que se Modificó <br>  en el listado de Clientes';
         //fin
 
           $envioCorreo = $enviar->enviar($to,$to2,$file,$from,$asunto,$body,"");
       
     }



     public function Eliminar($ids,$column,$proceso,$tabla )
     { 
         try 
         { 
             if($proceso=='Eliminar'){
              
                 $delete = $this->db->query("DELETE FROM $tabla WHERE $column = $ids ");

             } 
             
           //die;//dejarlo para q no bote error
         } catch (Exception $e) 
         {
              $delete=0;
             die($e->getMessage());
         }
          return $delete;
     }


     //BUSCAR REGISTROS
       public function buscarCampos($tabla, $condicion, $orden='', $distinct=''){

               try 
               { 
                    //echo "SELECT $distinct FROM $tabla $condicion $orden";die;
                   $stm = $this->db->query("SELECT $distinct FROM $tabla $condicion $orden");
                   if($stm){
                   while($filas=$stm->fetch_assoc()){
                       $this->logs=$filas;
                   }
         
                   return $this->logs;

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




   public function adjuntarArchivoG($tieneadjunto='', $directorio, $nuevoadjunto, $tmp_name, $tipoejecutar ){

       /*$tamano_archivo = $_FILES[$nuevoadjunto]['size'];//1048576 es una mega 
       $tipo_archivo = $_FILES[$nuevoadjunto]['type'];*/
         //if (!((strpos($tipo_archivo, "pdf")) && ($tamano_archivo < 10485770))) 


       if ($nuevoadjunto != "") { 
          if($tipoejecutar == 'UPDATES' || $tipoejecutar == 'NUEVOS'){

                 //UPDATE DEL ARCHIVO ELN EL SERVIDOR 
                 if($tieneadjunto != ""){
                   if (file_exists($directorio.$tieneadjunto)) 
                   { 
                      unlink($directorio.$tieneadjunto); 
                   }  
                 } 
                  
             $tieneadjunto2 = str_replace(' ', '', $nuevoadjunto);
             $archivo_temporal = $tmp_name;

             if (!copy($archivo_temporal,$directorio.$tieneadjunto2)) {
                $error = "Error al enviar el Archivo";
             } else { 
                $tieneadjunto = $tieneadjunto2; 
             }

             return $tieneadjunto;              

          } 

       }else{
          return $tieneadjunto;
       }
     




    }


    public function buscarListar($tabla, $condicion='', $order){   

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
