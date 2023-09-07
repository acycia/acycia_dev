<?php require_once('Connections/conexion1.php'); ?><?php
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
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_id_d = "-1";
if (isset($_GET['id_d'])) {
  $colname_id_d = (get_magic_quotes_gpc()) ? $_GET['id_d'] : addslashes($_GET['id_d']);
}

mysql_select_db($database_conexion1, $conexion1);
$query_despacho_direccion = sprintf("SELECT * FROM Tbl_despacho WHERE Tbl_despacho.id_d=%s", $colname_id_d);
$despacho_direccion = mysql_query($query_despacho_direccion, $conexion1) or die(mysql_error());
$row_despacho_direccion = mysql_fetch_assoc($despacho_direccion);
$totalRows_despacho_direccion = mysql_num_rows($despacho_direccion);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script language="Javascript">
/*  function imprSelec(nombre)
  {
  window.print();
  window.close();
  }*/
</script>
<style type="text/css">
@media print {
    div,a {display:none}
    .ver {display:block}
    .nover {display:none}
}
</style>
<script>
function impre(num) {
    document.getElementById(num).className="ver";
    print();
    document.getElementById(num).className="nover";
}
</script>
<title>SISADGE AC & CIA</title>
</head>
<body >
<div align="center" id="seleccion" onclick="impre('seleccion');return false">
<table id="tabla5" cellspacing="0" cellpadding="0">
  <tr>
   <td id="fuentNE"><table id="tabla" cellspacing="0" cellpadding="0">
    <td colspan="3" id="stikersC_titu">AC & CIA</td>
    </tr>
  <tr>
    <td colspan="3" nowrap="nowrap" style="border-bottom: 3px solid #000000;" id="stikersC_titu_grande">DESPACHOS</td>
    </tr>
  <tr>
    <td colspan="3" ><strong>CLIENTE:</strong>      <?php 
    $nit=$row_despacho_direccion['cliente_d']; 
	$sqlc="SELECT * FROM cliente WHERE cliente.nit_c='$nit'"; 
	$resultc=mysql_query($sqlc); 
	$numc=mysql_num_rows($resultc); 
	if($numc >= '1') 
	{ 
	$nombre_c=mysql_result($resultc,0,'nombre_c');echo $nombre_c; }
	else { echo "";	} ?></td>
    </tr>
  <tr>
    <td colspan="3" ><strong>DIRECCION:</strong><?php echo $row_despacho_direccion['direccion_d']; ?></td>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuent1">CIUDAD</td>
   <!-- <td nowrap="nowrap" id="stikersC_fuent1">Cant/und</td>-->
    <td nowrap="nowrap" id="stikersC_fuent1">ORDEN DE COMPRA</td>
    </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN"><?php echo $row_despacho_direccion['ciudad_d']; ?></td>
   <!-- <td nowrap="nowrap" id="stikersC_fuentN"><?php echo $row_despacho_direccion['cantidad_d']?></td>-->
    <td nowrap="nowrap" id="stikersC_fuentN"><?php echo ". ".$row_despacho_direccion['oc_d']; ?></td>
  </tr>
  <tr>
    <td colspan="3" nowrap="nowrap" id="stikersC_fuent1">REF. CLIENTE</td>
    </tr>
<tr>
    <td colspan="3" nowrap="nowrap" id="stikersC_fuentN"><?php echo $row_despacho_direccion['ref_d']; ?></td>
    </tr>  
   <?php if($row_despacho_direccion['desde_d']!='' || $row_despacho_direccion['hasta_d']!='') {?>  
  <tr>
    <td nowrap="nowrap" id="stikersC_fuent1">NUM. DESDE</td>
    <td colspan="2" nowrap="nowrap" id="stikersC_fuent1">NUM.HASTA</td>
  </tr>
  <tr>
    <td nowrap="nowrap" id="stikersC_fuentN"><?php echo $row_despacho_direccion['desde_d']; ?></td>
    <td colspan="2" nowrap="nowrap" id="stikersC_fuentN"><?php echo $row_despacho_direccion['hasta_d']; ?></td>
  </tr>
  <?php  } ?>
  <!--<tr>
    <td nowrap="nowrap" id="stikersC_fuent1">Total Cajas: </td>
    <td colspan="2" nowrap="nowrap" id="stikersC_fuentN"><?php echo $row_despacho_direccion['cajas_d']; ?></td>
   </tr>-->
</table></td>
  </tr>
</table>
</div>
<table width="200" border="0" align="center">
  <tr>
    <td><a href="javascript:history.back(1)"><<< REGRESAR</a></td>
  </tr>
</table>
</body>
</html>
<?php

mysql_free_result($usuario);

mysql_free_result($despacho_direccion);

?>
