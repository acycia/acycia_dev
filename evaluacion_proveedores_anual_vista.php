<?php require_once('Connections/conexion1.php'); ?>
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
<script type="text/javascript" src="AjaxControllers/js/envioListado.js"></script>
</head>
<body>
<div align="center">
<table id="tabla2">
  <tr>
    <td><input type="image" src="images/excel.png" type="submit" onClick="envioexcell();"></td>
  </tr>
      
  <tr>
    <td id="noprint" align="right">
      <img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="evaluacion_proveedores_anual_edit.php?id_eva=<?php echo $_GET['id_eva']; ?>&desde=<?php echo $row_evaluacion_vista['fecha_desde_eva']; ?>&hasta=<?php echo $row_evaluacion_vista['fecha_hasta_eva']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="evaluacion_anual.php"><img src="images/cat.gif" alt="EVALUACIONES" border="0" /></a><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
  </tr>
</table>
<form name="form1" id="form1" method="get" action="evaluacion_proveedores_anual_excel.php">
<table id="tablailimitada" align="center">
<tr>
  <td height="183" id="fondo2">  
  <table id="tabladetalle">
<tr>
<td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
<td colspan="2" id="titular2">EVALUACION DE DESEMPE&Ntilde;O DE PROVEEDORES (ANUAL) </td>
</tr>
<tr>
  <td id="fondo2">Codigo : A3-F05</td>
  <td id="fondo2">Version : 2</td>
</tr>
<tr>
  <td colspan="2" id="numero2">N&deg; <?php echo $row_evaluacion_vista['id_eva']; ?></td>
</tr>
<tr>
  <td colspan="2" id="fondo2">ALBERTO CADAVID R &amp; CIA S.A.  Nit: 890915756-6<br />
    Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
</tr>
</table>
<table id="tabla2">
<tr>
  <td id="subtitulo2">PERIODO DESDE </td>
<td id="subtitulo2">HASTA</td>
<td id="subtitulo2">RESPONSABLE</td>
<td id="subtitulo2">FECHA</td>
<!-- <td id="subtitulo2">EXCEL</td> -->
</tr>
<tr>
  <td id="dato2"><?php echo $row_evaluacion_vista['fecha_desde_eva']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['fecha_hasta_eva']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['responsable_eva']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['fecha_realizacion_eva']; ?></td>
   
<!--   <td rowspan="4" >
    <input type="image" src="images/excel.png" type="submit" onClick="envioexcell();">
  </td> -->
</tr>
</table>
</td></tr>
<tr>
  <td id="fondo2">
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
      <td id="detalle1"><?php echo eliminar_tildes($row_evaluacion_proveedor['calificacion_texto_ev']); ?></td>
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
</table>
</form>
</div>
</body>
</html>
<script>
  function envioexcell(){
    var form = "id_eva=<?php echo $_GET['id_eva']; ?>&desde=<?php echo $_GET['desde']; ?>&hasta=<?php echo $_GET['hasta']; ?>";

     
    var vista = 'evaluacion_proveedores_anual_excel.php';
    
       enviovarListados(form,vista); 
  }
</script>
<?php
mysql_free_result($usuario);

mysql_free_result($evaluacion_proveedor);

mysql_free_result($evaluacion_vista);
?>
