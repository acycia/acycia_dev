<?php 
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');//se debe dejar para traer variables globales
 
  class ApptivaDB{    
  private $host   = DB_HOST;
  private $usuario= DB_USER;
  private $clave  = DB_PASS;
  private $db     = DB_DATABASE;
  public  $conexion;
  
  public function __construct(){
    $this->conexion = new mysqli($this->host, $this->usuario, $this->clave,$this->db)
    or die(mysql_error());
    $this->conexion->set_charset("utf8");
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

   //BUSCAR DOS
  public function buscarDos($tabla, $columna, $condicion, $columna2, $condicion2){ 
    $resultado = $this->conexion->query("SELECT * FROM $tabla WHERE $columna = '{$condicion}' AND $columna2 = '{$condicion2}' ORDER BY $columna DESC") or die($this->conexion->error); 
    if($resultado)
      $fila = $resultado->fetch_assoc();//mysqli_fetch_assoc($resultado)
    $total = $fila; 
    return $total;
    return false;
    $resultado->free();
    $resultado->close();
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

   //BUSCAR ID CONSECUTIVO
  public function buscarId($tabla, $columna ){
    //echo "SELECT $columna AS id FROM $tabla ORDER BY $columna DESC LIMIT 0,1";
    $resultado = $this->conexion->query("SELECT $columna AS id FROM $tabla ORDER BY $columna DESC LIMIT 0,1") or die($this->conexion->error);
    if($resultado) 
       $resultfin = $resultado->fetch_assoc();//mysqli_fetch_assoc($resultado)
      return $resultfin;//si quiero enviar $resultfin['id'] o $resultfin[$columna] con nombre de la columna o $resultfin y se recibe $resultfin['id']
      return false;
      $resultado->free();
      $resultado->close();
    } 

     //LLENAR CAMPOS
    public function llenarCampos($tabla, $condicion, $orden='', $distinct='' ){  
      //echo "SELECT $distinct FROM $tabla $condicion $orden  ";die;
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

     //LLENA LISTADOS CON FOREACH
      public function llenaListas($tabla, $condicion, $orden='', $distinct=''){ 
        //echo "SELECT $distinct FROM $tabla $condicion $orden";die;
        $resultado = $this->conexion->query("SELECT $distinct FROM $tabla $condicion $orden") or die($this->conexion->error);

        if($resultado) 
          return self::getResultados($resultado);
        return false;
        $resultado->free();
        $resultado->close();
      }

   //BUSCAR ROW VARIOS CON ID
    public function buscarList($tabla, $columna, $condicion){
      $resultado = $this->conexion->query("SELECT * FROM $tabla WHERE  $columna = {$condicion}") or die($this->conexion->error);

      if($resultado) 
        return self::getResultados($resultado);
      return false;
      $resultado->free();
      $resultado->close();
    }

   //LISTAR VARIOS SIN ID TRAE TODOS OPCION GROUP ORDER Y COLUMNAS, COLUMNAS DISTINCT ETC
    public function buscarListar($tabla, $asterisco, $orden='', $group='', $maxRows_registros='' , $pageNum_registros='', $condicion='' ){
      //echo "SELECT $asterisco FROM $tabla $condicion $group $orden";die;
      $startRow_registros = $pageNum_registros * $maxRows_registros;
      $sql = "SELECT $asterisco FROM $tabla $condicion $group $orden ";  
      //echo $sql;die;
      $query_limit_registros = sprintf("%s LIMIT %d, %d", $sql, $startRow_registros, $maxRows_registros);
      
      $resultado = $this->conexion->query($query_limit_registros) or die($this->conexion->error);
      if($resultado) 
        //return $resultado->fetch_array(MYSQLI_BOTH);//MYSQLI_BOTH muestra numerico y asociativo 
        return self::getResultados($resultado);
      return false; 
      $resultado->free();
      $resultado->close();
    }
 
   //CONTADOR PARA LISTAS
    public function conteo($tabla){
      $resultado = $this->conexion->query("SELECT COUNT(*) FROM $tabla ") or die($this->conexion->error);
      if($resultado)
        $resultfin = $resultado->fetch_row(); 
      return $resultfin[0];
      return false;
      $resultado->free();
      $resultado->close();
    } 

   //INSERTAR
    public function insertar($tabla,$columna, $datos){
      //echo "INSERT INTO $tabla ($columna) VALUES ($datos) ";die;
      $resultado =    $this->conexion->query("INSERT INTO $tabla ($columna) VALUES ($datos) ") or die($this->conexion->error);
      if($resultado)
        return true;
      return false;
    } 

  //BORRAR
    public function borrar($tabla, $condicion){    
      $resultado  =   $this->conexion->query("DELETE FROM $tabla WHERE $condicion") or die($this->conexion->error);
      if($resultado)
        return true;
      return false;
    }

  //ACTUALIZAR
    public function actualizar($tabla, $campos, $condicion){   
    //echo "UPDATE $tabla SET $campos WHERE $condicion";die; 
      $resultado  =   $this->conexion->query("UPDATE $tabla SET $campos WHERE $condicion") or die($this->conexion->error);
      if($resultado)
        return true;
      return false;        
    } 

  //CONTADOR 
   public function conteoRegistro($tabla,$columna,$condicion='',$order=''){
    //echo "SELECT COUNT($columna) as total FROM $tabla $condicion $order ";die; 
     $resultado = $this->conexion->query("SELECT COUNT($columna) as total FROM $tabla $condicion $order ") or die($this->conexion->error);
     if($resultado)
       $fila = $resultado->fetch_assoc();//mysqli_fetch_assoc($resultado)
     $total = $fila; 
     return $total;
     return false;
     $resultado->free();
     $resultado->close();
   } 

  //CONTADOR 
   public function conteos($tabla,$columna,$condicion='',$order=''){
    //echo "SELECT COUNT(DISTINCT($columna)) as total FROM $tabla $condicion $order ";die; 
     $resultado = $this->conexion->query("SELECT COUNT(DISTINCT($columna)) as total FROM $tabla $condicion $order ") or die($this->conexion->error);
     if($resultado)
       $fila = $resultado->fetch_assoc();//mysqli_fetch_assoc($resultado)
     $total = $fila; 
     return $total;
     return false;
     $resultado->free();
     $resultado->close();
   } 


   public function get_materiaPrima($tabla, $condicion='', $order){   

       try 
       {   
           //echo "SELECT * FROM $tabla $condicion $order";die;
           $consulta=$this->conexion->query("SELECT * FROM $tabla $condicion $order");
           while($filas=$consulta->fetch_assoc()){
               $this->maquina[]=$filas;
           }
   
           return $this->maquina;
       } catch (Exception $e) 
       {
           die($e->getMessage());
       }
   }

  
  public function get_Maquina(){   

      try 
      {
          $consulta=$this->conexion->query("SELECT * FROM maquina WHERE proceso_maquina='2' ORDER BY nombre_maquina ASC");
          while($filas=$consulta->fetch_assoc()){
              $this->maquina[]=$filas;
          }
  
          return $this->maquina;
      } catch (Exception $e) 
      {
          die($e->getMessage());
      }
  }


  public function get_Anilox(){   

      try 
      {   
          $consulta=$this->conexion->query("SELECT * FROM anilox ORDER BY descripcion_insumo ASC");
          while($filas=$consulta->fetch_assoc()){
              $this->anilox[]=$filas;
          }
  
          return $this->anilox;
      } catch (Exception $e) 
      {
          die($e->getMessage());
      }
  }


    public function multiConsultas($consulta1,$consulta2,$order='')
    {

        try 
        {
            if($consulta1!='' && $consulta2!=''  ){ 
               //echo " $consulta1 ($consulta2) ";die;
                $stm = $this->db->query(" $consulta1 ($consulta2) ");
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
 
  public function header($vista='')
    {
       try 
         { 
          switch ($vista) {
            case 'listas':
              $page = require_once($_SERVER['DOCUMENT_ROOT'].'/acycia_dev/view_cabecera.php'); 
              break;
            case 'footer':
              $page = require_once($_SERVER['DOCUMENT_ROOT'].'/acycia_dev/view_footer.php'); 
              break;
            case 'vistas':
              $page = require_once($_SERVER['DOCUMENT_ROOT'].'/acycia_dev/view_cabecerav.php'); 
              break;
            default:
              $page = require_once($_SERVER['DOCUMENT_ROOT'].'/acycia_dev/view_cabecera.php'); 
              break;
          } 
        

         } catch (Exception $e) {
               die($e->getMessage());
        }

    }


  public function llenaCombos()
    {
     
     // Numero de registros
     $numero_de_registros = 100;

      

      $var1=$_POST['var1']=='' ? "" : $_POST['var1'];
      $var2=$_POST['var2']=='' ? "" : $_POST['var2'];
      $var3=$_POST['var3']=='' ? "" : $_POST['var3'];
      $var4=$_POST['var4']=='' ? "" : $_POST['var4']; 
      $var5=$_POST['var5']=='' ? "" : $_POST['var5'];
      $var6=$_POST['var6']=='' ? "" : $_POST['var6'];  
     if(!isset($_POST['palabraClave']) ){
      // Obtener registros
      $where  = $var3 == "" ? "" : "WHERE " . $var3;

      $sql = "SELECT $var1, $var5 as id, $var6 as descrip FROM ".$var2." ". $where." ". $var4; 
    
      $query_limit_registros = sprintf("%s LIMIT %d, %d", $sql, 1, $numero_de_registros);
      $usersList = $this->conexion->query($query_limit_registros) or die($this->conexion->error);

     }else{

      $search = $_POST['palabraClave'];// Palabra a buscar
      
      // Obtener registros
     
         $var3 = $var3 == "" ? "" : " AND ".$var3;

      if($search)
       $where = "WHERE ".$var6. " LIKE " . ' "%'.$search.'%" ' . $var3;
       
       $stmt=$this->conexion->query("SELECT $var1, $var5 as id, $var6 as descrip FROM   ".$var2." $where  ". $var4);
       if($stmt) 
         $usersList = self::getResultados($stmt);
       
       
     }
      
      
     $response = array();

     // Leer la informacion
     foreach($usersList as $user){
      $response[] = array(
        "id" => $user['id'],
        "text" => $user['descrip']
      );
     }

     echo json_encode($response);
     
    /* $usersList->free();
     $usersList->close();*/
    

    }
 
    //convierte a Array
      public function getResultados($arreglo)
      {
        $rows = array();
      while($row = $arreglo->fetch_array(MYSQLI_BOTH))//MYSQLI_ASSOC array asociativo, MYSQLI_NUM array numérico
      {
        $rows[] = $row;
      }

      return $rows;
    }


    //PRECIO POR REFERENCIA
    public function TipoTabla($valor,$BD){
    $id_ref=$valor;
    

        $resultado = $this->conexion->query("SELECT tipo_bolsa_ref FROM Tbl_referencia WHERE cod_ref = '$id_ref' ") or die($this->conexion->error); 
      if($resultado)
        $tipo_bolsa_ref = $resultado->fetch_assoc(); 
      
    //DEVUELVE LA BASE DE DATOS CORRESPONDIENTE AL TIPO
     $tipo_bolsa_ref = $tipo_bolsa_ref['tipo_bolsa_ref'];
     $BD="Tbl_cotiza_bolsa";
    if($tipo_bolsa_ref!='LAMINAS'||$tipo_bolsa_ref!='LAMINA'||$tipo_bolsa_ref!='PACKING LIST')
    {
      $BD="Tbl_cotiza_bolsa";
    }
    if ($tipo_bolsa_ref=='LAMINA'||$tipo_bolsa_ref=='LAMINAS')
    {
      $BD="Tbl_cotiza_laminas";
    }
    if($tipo_bolsa_ref=='PACKING LIST')
    {
      $BD="Tbl_cotiza_packing";
    } 
    
      return $BD;
     
    }


}
?>