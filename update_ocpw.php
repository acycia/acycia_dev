<?php require_once('Connections/conexion1.php'); ?>
<?php
mysql_select_db($database_conexion1, $conexion1);

//FUNCION PARA LIMPIAR VARIABLES PARA ESCAPAR DE ALGUNOS DATOS PARA PASARLO A MYSQL
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
//------------------------------------------------------------------
//--------------------------INVENTARIOS-----------------------------


  $update = $_REQUEST['enviolocal'];//PUEDE SER POST O GET
  
  $enviolocal = $_POST['enviolocal'];

 if( $enviolocal != ''){
echo 'aqui';
     $sqlproceso="UPDATE tbl_orden_compra SET envio='1' WHERE id_pedido = '$enviolocal'";
     $resultproceso=mysql_query($sqlproceso);   

     $resul='Actualizado correctamente!';
     header("location:orden_compra_cl2.php?resul=$resul");
  
 }


  //echo 'Bien!';

?>