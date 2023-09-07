<?php require_once('Connections/conexion1.php'); ?>
<?php
 header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="evaluacion_proveedor.xls"'); 
?>
<?php
require_once('funciones/funciones_php.php');

if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "usuario.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$desde_evaluacion_proveedor = "-1";
if (isset($_GET['desde'])) {
  $desde_evaluacion_proveedor = (get_magic_quotes_gpc()) ? $_GET['desde'] : addslashes($_GET['desde']);
}
$hasta_evaluacion_proveedor = "-1";
if (isset($_GET['hasta'])) {
  $hasta_evaluacion_proveedor = (get_magic_quotes_gpc()) ? $_GET['hasta'] : addslashes($_GET['hasta']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_evaluacion_proveedor = sprintf("SELECT * FROM evaluacion_proveedor WHERE periodo_desde_ev>='%s' AND periodo_hasta_ev<='%s' ORDER BY n_ev ASC", $desde_evaluacion_proveedor,$hasta_evaluacion_proveedor);
$evaluacion_proveedor = mysql_query($query_evaluacion_proveedor, $conexion1) or die(mysql_error());
$row_evaluacion_proveedor = mysql_fetch_assoc($evaluacion_proveedor);
$totalRows_evaluacion_proveedor = mysql_num_rows($evaluacion_proveedor);

$colname_evaluacion_vista = "-1";
if (isset($_GET['id_eva'])) {
  $colname_evaluacion_vista = (get_magic_quotes_gpc()) ? $_GET['id_eva'] : addslashes($_GET['id_eva']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_evaluacion_vista = sprintf("SELECT * FROM evaluacion_anual WHERE id_eva = %s", $colname_evaluacion_vista);
$evaluacion_vista = mysql_query($query_evaluacion_vista, $conexion1) or die(mysql_error());
$row_evaluacion_vista = mysql_fetch_assoc($evaluacion_vista);
$totalRows_evaluacion_vista = mysql_num_rows($evaluacion_vista);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
</head>
<body>
<div align="center">
 
<table id="tablailimitada" align="center">
<tr>
  <td height="183" id="fondo2">  
 
<table id="tabla2">
<tr>
  <td id="subtitulo2">PERIODO DESDE </td>
<td id="subtitulo2">HASTA</td>
<td id="subtitulo2">RESPONSABLE</td>
<td id="subtitulo2">FECHA</td>
</tr>
<tr>
  <td id="dato2"><?php echo $row_evaluacion_vista['fecha_desde_eva']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['fecha_hasta_eva']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['responsable_eva']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['fecha_realizacion_eva']; ?></td>
  </tr>
 
  <table id="tabladetalle">
  <tr>
    <td rowspan="2" id="nivel2">N&deg;</td>
    <td rowspan="2" id="nivel2">PROVEEDOR</td>
    <td rowspan="2" id="nivel2">SERVICIO/PRODUCTO</td>
    <td colspan="4" id="detalle2">VALORACION VARIABLES DE CONTROL </td>
    <td rowspan="2" id="nivel2">TOTAL</td>
    <td rowspan="2" id="nivel2">PLAN DE ACCION</td>
    <td rowspan="2" id="nivel2">RESPONSABLE</td>
    <td rowspan="2" id="nivel2">FECHA<br>IMPLEMENTACION </td>
  </tr>
  <tr>
    <td id="nivel2">ENTREGA</td>
    <td id="nivel2">CANTIDAD</td>
    <td id="nivel2">CALIDAD</td>
    <td id="nivel2">SERVICIO</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="detalle3"><?php echo $row_evaluacion_proveedor['n_ev']; ?></td>
      <td id="detalle1"><?php $id_p=$row_evaluacion_proveedor['id_p_ev'];
      $sql2="SELECT * FROM proveedor WHERE id_p='$id_p'";
      $result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
      if($num2 >='1') { $nombre_p=mysql_result($result2,0,'proveedor_p'); } echo $nombre_p; ?> 
    </td>
  <td nowrap id="detalle1"><?php echo $row_evaluacion_proveedor['evaluacion']; ?></td>
      <td nowrap="nowrap" id="detalle3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_oportunos_ev']); ?>%</td>
      <td nowrap="nowrap" id="detalle3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_cumple_ev'])=='' ? "N.A" : str_replace('.',',',$row_evaluacion_proveedor['porcentaje_cumple_ev']).'%';?></td>
      <td nowrap="nowrap" id="detalle3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_conforme_ev']); ?>%</td>
      <td nowrap="nowrap" id="detalle3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_atencion_ev']); ?>%</td>
      <td nowrap="nowrap" id="detalle3"><?php echo str_replace('.',',',$row_evaluacion_proveedor['porcentaje_final_ev']); ?>%</td>
      <td id="detalle1"><?php echo  eliminar_tildes($row_evaluacion_proveedor['calificacion_texto_ev']); ?></td>
      <td id="detalle1"><?php echo $row_evaluacion_proveedor['responsable_registro_ev']; ?></td>
      <td id="detalle2"><?php echo $row_evaluacion_proveedor['fecha_registro_ev']; ?></td>
    </tr>
    <?php } while ($row_evaluacion_proveedor = mysql_fetch_assoc($evaluacion_proveedor)); ?>
  </table>
  <table id="tabla2">
<tr>
  <td id="subtitulo1">OBSERVACIONES</td>
</tr>
<tr><td id="dato1">- <?php echo $row_evaluacion_vista['observacion_eva']; ?></td></tr>
</table>
</table></div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($evaluacion_proveedor);

mysql_free_result($evaluacion_vista);
?>
