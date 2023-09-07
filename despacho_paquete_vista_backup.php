<?php require_once('Connections/conexion1.php'); ?><?php
if (!isset($_SESSION)) {
  session_start();
}
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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_op = "-1";
if (isset($_GET['id_op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
$colname_p = "-1";
if (isset($_GET['int_paquete_tn'])) {
  $colname_p = (get_magic_quotes_gpc()) ? $_GET['int_paquete_tn'] : addslashes($_GET['int_paquete_tn']);
}
$colname_faltantes_k = "-1";
if (isset($_GET['int_caja_tn'])) {
  $colname_faltantes_k = (get_magic_quotes_gpc()) ? $_GET['int_caja_tn'] : addslashes($_GET['int_caja_tn']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_vista_paquete = sprintf("SELECT * FROM Tbl_tiquete_numeracion_backup WHERE int_op_tn=%s AND int_paquete_tn=%s AND int_caja_tn=%s", $colname_op,$colname_p,$colname_faltantes_k);
$vista_paquete = mysql_query($query_vista_paquete, $conexion1) or die(mysql_error());
$row_vista_paquete = mysql_fetch_assoc($vista_paquete);
$totalRows_vista_paquete = mysql_num_rows($vista_paquete);

$colname_faltantes_op = "-1";
if (isset($_GET['id_op'])) {
  $colname_faltantes_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
$colname_faltantes_p = "-1";
if (isset($_GET['int_paquete_tn'])) {
  $colname_faltantes_p = (get_magic_quotes_gpc()) ? $_GET['int_paquete_tn'] : addslashes($_GET['int_paquete_tn']);
}
$colname_faltantes_k = "-1";
if (isset($_GET['int_caja_tn'])) {
  $colname_faltantes_k = (get_magic_quotes_gpc()) ? $_GET['int_caja_tn'] : addslashes($_GET['int_caja_tn']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_vista_faltantes = sprintf("SELECT * FROM Tbl_tiquete_numeracion_backup, Tbl_faltantes WHERE Tbl_tiquete_numeracion_backup.int_op_tn=%s AND Tbl_tiquete_numeracion_backup.int_paquete_tn=%s AND Tbl_tiquete_numeracion_backup.int_caja_tn=%s  AND Tbl_tiquete_numeracion_backup.int_op_tn=Tbl_faltantes.id_op_f AND Tbl_tiquete_numeracion_backup.int_paquete_tn=Tbl_faltantes.int_paquete_f AND Tbl_tiquete_numeracion_backup.int_caja_tn=Tbl_faltantes.int_caja_f ORDER BY Tbl_faltantes.int_inicial_f ASC", $colname_faltantes_op,$colname_faltantes_p,$colname_faltantes_k);
$vista_faltantes = mysql_query($query_vista_faltantes, $conexion1) or die(mysql_error());
$row_vista_faltantes = mysql_fetch_assoc($vista_faltantes);
$totalRows_vista_faltantes = mysql_num_rows($vista_faltantes);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC & CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<!--IMPRIME AL CARGAR POPUP-->
<SCRIPT language="javascript"> 
function imprimir()
{ if ((navigator.appName == "Netscape")) { window.print() ;
} 
else
{ var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6, -1); WebBrowser1.outerHTML = "";
}
}
</SCRIPT>
<style type="text/css">

 #oculto {
  display:none;
 
}
</style>
<script>
function cerrar(num) {

    window.close()
}
</script>
</head>
<body>
<div align="center" id="seleccion" onClick="cerrar('seleccion');return false"><!--onClick="javascript:imprSelec('seleccion')"-->
<table align="center" id="tabla4">
  <tr>
    <td colspan="4" id="fondo1">AC & CIA</td>
  </tr>
      <tr>
        <td colspan="4" nowrap="nowrap" id="stikers_titu">CONTROL DE NUMERACION </td>
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikers_fuent1">PAQUETE # </td>
    <td nowrap="nowrap" id="stikers_fuentN"><?php echo $row_vista_paquete['int_paquete_tn']; ?></td>
    <td nowrap id="stikers_fuent1">CAJA # </td>
    <td nowrap id="stikers_fuentN"><?php echo $row_vista_paquete['int_caja_tn']; ?></td>
    </tr>    
  <tr>
    <td colspan="2" nowrap="nowrap" id="stikers_fuent1">FECHA</td>
    <td colspan="2" nowrap id="stikers_fuent1"><?php echo $row_vista_paquete['fecha_ingreso_tn']; ?></td>
    </tr>
  <tr>
    <td colspan="2" nowrap="nowrap"id="stikers_fuent1">ORDEN P.</td>
    <td colspan="2" id="stikers_fuent1"><?php echo $row_vista_paquete['int_op_tn']; ?></td>
    </tr>
  <!--<tr>
    <td nowrap="nowrap" id="stikers_fuent1">BOLSAS</td>
    <td colspan="2" id="stikers_fuent1"><?php echo $row_vista_paquete['int_bolsas_tn']; ?></td>
    </tr>-->
      <tr>
    <td colspan="2" nowrap="nowrap" id="stikers_fuent1">UNIDADES  X PAQ.</td>
    <td colspan="2" id="stikers_fuent1"><?php echo $row_vista_paquete['int_undxpaq_tn']; ?></td>
    </tr>
    <tr>
     <td colspan="2" nowrap="nowrap"id="stikers_fuent1">UNIDADES X CAJA</td>
      <td colspan="2"id="stikers_fuent1"><?php echo $row_vista_paquete['int_undxcaja_tn'];?></td>
      </tr>
      <tr>
    <td colspan="2" nowrap="nowrap" id="stikers_fuent1">DESDE</td>
    <td colspan="2" id="stikers_fuentN"><?php echo $row_vista_paquete['int_desde_tn']; ?></td>
    </tr>
      <tr>
    <td colspan="2" nowrap="nowrap" id="stikers_fuent1">HASTA</td>
    <td colspan="2" id="stikers_fuentN"><?php echo $row_vista_paquete['int_hasta_tn']; ?></td>
    </tr>
      <tr>
    <td colspan="2" nowrap="nowrap" id="stikers_fuent1">CODIGO DE EMPLEADO</td>
    <td colspan="2" id="stikers_fuent1"><?php echo $row_vista_paquete['int_cod_empleado_tn']; ?></td>
    </tr>
    <tr>
    <td colspan="2" nowrap="nowrap" id="stikers_fuent1">CODIGO DE REVISOR</td>
    <td colspan="2" id="stikers_fuent1"><?php echo $row_vista_paquete['int_cod_rev_tn']; ?></td>
    </tr>      
    <?php if($row_vista_faltantes['int_inicial_f']!=''){ ?>
      <tr>
        <td colspan="4" nowrap="nowrap" id="stikers_subt2">FALTANTES</td>
      </tr>      
      <tr>
    <td width="52" colspan="4" id="stikers_fuentN"><?php  do { ?><?php echo $row_vista_faltantes['int_inicial_f']; ?> - <?php echo $row_vista_faltantes['int_final_f']. ", "; ?><?php } while ($row_vista_faltantes = mysql_fetch_assoc($vista_faltantes)); ?></td>
    </tr>
    <?php }?>
</table>
</div>
<div id="oculto">
<table width="200" border="0" align="center">
  <tr>
    <td><input name="cerrar" type="button" autofocus value="cerrar"onClick="cerrar('seleccion');return false" ></td>
  </tr>
</table>
</div>
</body>
</html>
<?php

mysql_free_result($usuario);

mysql_free_result($vista_paquete);

mysql_free_result($vista_faltantes);


?>
