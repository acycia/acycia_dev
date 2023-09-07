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

$colname_bolsas = "-1";
if (isset($_GET['id_bolsa'])) {
  $colname_bolsas = (get_magic_quotes_gpc()) ? $_GET['id_bolsa'] : addslashes($_GET['id_bolsa']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsas = sprintf("SELECT * FROM material_terminado_bolsas WHERE id_bolsa = %s", $colname_bolsas);
$bolsas = mysql_query($query_bolsas, $conexion1) or die(mysql_error());
$row_bolsas = mysql_fetch_assoc($bolsas);
$totalRows_bolsas = mysql_num_rows($bolsas);
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
<table id="tabla2">
  <tr>
    <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="bolsas_edit.php?id_bolsa=<?php echo $row_bolsas['id_bolsa']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="bolsas_add.php"><img src="images/mas.gif" alt="ADD BOLSA" border="0" style="cursor:hand;"/></a><a href="bolsas.php"><img src="images/b.gif" alt="BOLSAS" border="0" /></a><a href="bolsas_busqueda.php"><img src="images/embudo.gif" style="cursor:hand;" alt="FILTRO" border="0" /></a><a href="bolsas_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="O.C. (BOLSAS)" border="0" /></a><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
  </tr>
</table>
<table id="tabla1">
<tr><td align="center">
<table id="tabla2">
<tr><td rowspan="3" id="fondo2"><img src="images/logoacyc.jpg" /></td>
<td nowrap="nowrap" id="titulo">PRODUCTO TERMINADO</td>
</tr>
<tr>
  <td id="titulo">BOLSAS</td>
  </tr>
<tr>
  <td id="fondo2">CONSECUTIVO N&deg; <?php echo $row_bolsas['id_bolsa']; ?></td>
  </tr>
<tr>
  <td id="subtitulo1">CODIGO DE LA BOLSA</td>
  <td id="subtitulo1">NOMBRE DE LA BOLSA</td>
  </tr>
<tr>
  <td id="dato1"><?php echo $row_bolsas['codigo_bolsa']; ?></td>
  <td id="dato1"><?php echo $row_bolsas['nombre_bolsa']; ?></td>
  </tr>
<tr>
  <td id="subtitulo1">REF. DEL PRODUCTO </td>
  <td id="subtitulo1">UNIDAD DE MEDIDA</td>
  </tr>
<tr>
  <td id="dato1"><?php $ref=$row_bolsas['id_ref_bolsa'];
	if($ref!='') { 
	 $sqlref="SELECT * FROM Tbl_referencia WHERE id_ref='$ref'";
	  $resultref= mysql_query($sqlref);
	  $numref= mysql_num_rows($resultref);
	  if($numref >='1')
	  { 
	  $referencia = mysql_result($resultref, 0, 'cod_ref');
	  echo $referencia;
	  } } ?> &nbsp;</td>
  <td id="dato1"><?php $medida=$row_bolsas['id_medida_bolsa']; 
	  if($medida!='') { 
	  $sqlmedida="SELECT * FROM medida WHERE id_medida='$medida'";
	  $resultmedida= mysql_query($sqlmedida);
	  $numedida= mysql_num_rows($resultmedida);
	  if($numedida >='1')
	  { 
	  $nombre_medida = mysql_result($resultmedida, 0, 'nombre_medida');
	  echo $nombre_medida;
	  } } ?>&nbsp;</td>
  </tr>
<tr>
  <td colspan="2" id="subtitulo1">DESCRIPCION DE LA BOLSA </td>
  </tr>
<tr>
  <td colspan="2" id="dato1"><?php echo $row_bolsas['descripcion_bolsa']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="dato1"><strong>OBSERVACIONES : </strong><?php echo $row_bolsas['observacion_bolsa']; ?></td>
  </tr>
</table>
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($bolsas);
?>
