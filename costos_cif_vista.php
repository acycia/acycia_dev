<?php require_once('Connections/conexion1.php'); ?>
<?php
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

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor WHERE id_p = %s", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

$colname_fechaini = "-1";
if (isset($_GET['fecha_ini'])) {
  $colname_fechaini = (get_magic_quotes_gpc()) ? $_GET['fecha_ini'] : addslashes($_GET['fecha_ini']);
}
$colname_fechafin = "-1";
if (isset($_GET['fecha_fin'])) {
  $colname_fechafin = (get_magic_quotes_gpc()) ? $_GET['fecha_fin'] : addslashes($_GET['fecha_fin']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_costos_cif = sprintf("SELECT * FROM Tbl_costos_cif WHERE fecha_ini='%s' AND fecha_fin ='%s'",$colname_fechaini,$colname_fechafin);
$costos_cif = mysql_query($query_costos_cif, $conexion1) or die(mysql_error());
$row_costos_cif = mysql_fetch_assoc($costos_cif);
$totalRows_costos_cif = mysql_num_rows($costos_cif);
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
<table id="tablainterna">
<tr>    
     <td colspan="5" id="principal">COSTOS</td>
  </tr>
  <tr>
    <td rowspan="5" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="5" id="dato3"><a href="costos_cif_edit.php?fecha_ini=<?php echo $row_costos_cif['fecha_ini']; ?>&fecha_fin=<?php echo $row_costos_cif['fecha_fin']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" /><a href="#"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR"title="SALIR" onClick="window.close() "/></a></td>
    </tr>
  <tr>
    <td id="subppal2">&nbsp;</td>
    <td colspan="4" id="subppal2">&nbsp;</td>
    </tr>
  <tr>
    <td id="fuente2">&nbsp;</td>
    <td colspan="4" nowrap id="fuente2">&nbsp;</td>
    </tr>
  <tr>
    <td id="fuente2">&nbsp;</td>
    <td colspan="4" nowrap id="fuente2">&nbsp;</td>
    </tr>    
  <tr>
    <td id="fuente2">&nbsp;</td>
    <td colspan="4" id="fuente2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="5" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
  </tr>
  <tr>
    <td colspan="5" id="subtitulo">COSTO CIF UNIDAD PROMEDIO PRODUCIDA</td>
    </tr>
<tr>
    <td id="subppal2">Und Producidas Enero - Julio</td>
    <td colspan="2" id="subppal2">Und Producidas (MES)</td>
    <td colspan="2" id="subppal2">Costo CIF promedio Und Producida</td>
  </tr>
  <tr>
    <td id="fuente2">&nbsp;</td>
    <td colspan="2" id="fuente2">&nbsp;</td>
    <td colspan="2" id="fuente2">&nbsp;</td>
  </tr>    
</table>    

<table id="tablainterna">
 <tr>
  <td colspan="5" id="subtitulo">COSTO CIF POR CUARTILES</td>
  </tr>    
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="3" id="subppal3">Fecha inicial: </td>
        <td id="subppal3">fecha final:</td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="3" id="fuente1"><?php echo $row_costos_cif['fecha_ini']; ?></td>
        <td id="fuente1"><?php echo $row_costos_cif['fecha_fin']; ?></td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="4" rowspan="6" id="fuente1" valign="top">
        <table id="justificar" >
          <tr>
            <td id="subppal3">CIF Bolsa de M&aacute;ximo 250 cm&sup2;</td>
            <td id="subppal3">CIF Bolsa entre 250 y 500 cm&sup2;</td>
            <td id="subppal3">CIF Bolsa entre 501  y  1000 cm&sup2;</td>
            <td id="subppal3">CIF Bolsa entre 1001 y 4000 cm&sup2;</td>
            <?php  for ($x=0;$x<=$totalRows_costos_cif-1;$x++) { ?>
          </tr>
          <tr>
            <td id="fuente1">$  <?php $cp=mysql_result($costos_cif,$x,cif_250);echo $cp; ?></td>
            <td id="fuente1">$  <?php $aj=mysql_result($costos_cif,$x,cif_250_500);echo $aj; ?></td>
            <td id="fuente1">$  <?php $cr=mysql_result($costos_cif,$x,cif_501_1000);echo $cr; ?></td>
            <td id="fuente1">$  <?php $cm=mysql_result($costos_cif,$x,cif_1001_4000);echo $cm; ?></td>
          </tr>
          <?php  } ?>
        </table></td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo cif segun produccion promedio</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Ajustes</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo cif segun promedio real</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="subppal3">Costo cif segun produccion y ajuste</td>
      </tr> 
      
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($proveedor);

mysql_free_result($costos_cif);
?>
