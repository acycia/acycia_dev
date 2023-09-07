<?php require_once('Connections/conexion1.php'); ?>
<?php
mysql_select_db($database_conexion1, $conexion1);
/*----------VARIABLES------------*/
/*----------DESPACHOS--------*/
$accion=$_POST['accion']; 
/*----------------------------------------*/
/*--------------ACCIONES------------------*/
/*----------------------------------------*/
/*------------DESPACHO DIRECCION---------------*/
//INICIO SWITCH
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

/*$imprimi=$_POST['id_d'];
echo $imprimi;*/
switch($accion) {
case '1':
$vacio=$_POST['cambiar'];
if($vacio!='' && $_POST['id_d']){
  $insertSQL = sprintf("INSERT INTO Tbl_despacho (id_d, id_op, oc_d, ref_d, fecha_d, cliente_d, ciudad_d, direccion_d, cajas_d, cantidad_d,desde_d,hasta_d) 
  VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
              GetSQLValueString($_POST['id_d'], "int"),
              GetSQLValueString($_POST['id_op'], "int"),
			  GetSQLValueString($_POST['oc_d'], "text"),
			  GetSQLValueString($_POST['ref_d'], "text"),
              GetSQLValueString($_POST['fecha_d'], "date"),
              GetSQLValueString($_POST['cliente_d'], "text"),
              GetSQLValueString($_POST['ciudad_d'], "text"),
              GetSQLValueString($_POST['direccion_d'], "text"),
              GetSQLValueString($_POST['cajas_d'], "int"),
              GetSQLValueString($_POST['cantidad_d'], "int"),
			  GetSQLValueString($_POST['desde_d'], "text"),
			  GetSQLValueString($_POST['hasta_d'], "text"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
                        //INSERTA EL ID_D DE DESPACHO EN TBL_TIQUETE_NUMERACION
              $id_d=$_POST['id_d'];
			  if(count($_POST['cambiar'])) {
			  foreach ($_POST['cambiar'] as $v) {
			  $sql = "UPDATE Tbl_tiquete_numeracion SET id_despacho='$id_d' WHERE id_tn='$v'";
			  $resultado = mysql_query($sql, $conexion1) or die(mysql_error());
			  mysql_select_db($database_conexion1, $conexion1);
			  $id=1;			  
			    }
			  }      
  $insertGoTo = "despacho_direccion_vista.php?id_d=" . $_POST['id_d'] . "&" . "id=" . $id ."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  
  }else{
  $id=0;
  $insertGoTo = "despacho_direccion2.php?id_op=" . $_POST['id_op'] . "&" . "id=" . $id ."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
	  
	  }//SOLAMENTE SE GUARDA SI HA SELECCIONADO ALGUNA CAJA O PAQUETE
}

?>