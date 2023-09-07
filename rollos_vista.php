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

$colname_rollo = "-1";
if (isset($_GET['id_rollo'])) {
  $colname_rollo = (get_magic_quotes_gpc()) ? $_GET['id_rollo'] : addslashes($_GET['id_rollo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo = sprintf("SELECT * FROM materia_prima_rollos WHERE id_rollo = %s", $colname_rollo);
$rollo = mysql_query($query_rollo, $conexion1) or die(mysql_error());
$row_rollo = mysql_fetch_assoc($rollo);
$totalRows_rollo = mysql_num_rows($rollo);
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
    <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="rollos_edit.php?id_rollo=<?php echo $row_rollo['id_rollo']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="rollos_add.php"><img src="images/mas.gif" alt="ADD ROLLO" border="0" style="cursor:hand;"/></a><a href="rollos.php"><img src="images/r.gif" alt="ROLLOS" border="0" /></a><a href="rollos_busqueda.php"><img src="images/embudo.gif" style="cursor:hand;" alt="FILTRO" border="0" /></a><a href="rollos_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="O.C. (ROLLOS)" border="0" /></a><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
  </tr>
</table>
<table id="tabla1"><tr><td align="center">
<table id="tabla2">
<tr><td colspan="2" rowspan="3" id="fondo2"><img src="images/logoacyc.jpg" /></td>
<td colspan="2" id="titulo">MATERIA PRIMA </td>
</tr>
<tr>
  <td colspan="2" id="titulo">ROLLOS</td>
</tr>
<tr>
  <td colspan="2" id="fondo2">CONSECUTIVO N&deg; <?php echo $row_rollo['id_rollo']; ?></td>
</tr>
<tr>
  <td colspan="2" id="subtitulo1">CODIGO DEL ROLLO </td>
  <td colspan="2" id="subtitulo1">NOMBRE DEL ROLLO </td>
</tr>
<tr>
  <td colspan="2" id="dato1"><?php echo $row_rollo['cod_rollo']; ?></td>
  <td colspan="2" id="dato1"><?php echo $row_rollo['nombre_rollo']; ?></td>
</tr>
<tr>
  <td colspan="2" id="subtitulo1">REF. DEL PRODUCTO </td>
  <td id="subtitulo1">PRESENTACION DEL ROLLO </td>
  <td id="subtitulo1">TIPO</td>
</tr>
<tr>
  <td colspan="2" id="dato1"><?php $ref=$row_rollo['ref_prod_rollo'];
	if($ref!='') { 
	 $sqlref="SELECT * FROM referencia WHERE id_ref='$ref'";
	  $resultref= mysql_query($sqlref);
	  $numref= mysql_num_rows($resultref);
	  if($numref >='1')
	  { 
	  $referencia = mysql_result($resultref, 0, 'cod_ref');
	  echo $referencia;
	  } } ?></td>
  <td id="dato1"><?php echo $row_rollo['presentacion_rollo']; ?></td>
  <td id="dato1"><?php $tipo=$row_rollo['tipo_rollo']; 
	  if($tipo!='') { 
	  $sqltipo="SELECT * FROM tipo WHERE id_tipo='$tipo'";
	  $resultipo= mysql_query($sqltipo);
	  $numtipo= mysql_num_rows($resultipo);
	  if($numtipo >='1')
	  { 
	  $nombre_tipo = mysql_result($resultipo, 0, 'nombre_tipo');
	  echo $nombre_tipo;
	  } } ?></td>
</tr>
<tr>
  <td id="subtitulo1">ANCHO</td>
  <td id="subtitulo1">CALIBRE</td>
  <td id="subtitulo1">UNIDAD DE MEDIDA</td>
  <td id="subtitulo1">TRATAMIENTO</td>
</tr>
<tr>
  <td id="dato1"><?php echo $row_rollo['ancho_rollo']; ?></td>
  <td id="dato1"><?php echo $row_rollo['calibre_rollo']; ?></td>
  <td id="dato1"><?php $medida=$row_rollo['medida_rollo']; 
	  if($medida!='') { 
	  $sqlmedida="SELECT * FROM medida WHERE id_medida='$medida'";
	  $resultmedida= mysql_query($sqlmedida);
	  $numedida= mysql_num_rows($resultmedida);
	  if($numedida >='1')
	  { 
	  $nombre_medida = mysql_result($resultmedida, 0, 'nombre_medida');
	  echo $nombre_medida;
	  } } ?></td>
  <td id="dato1"><?php echo $row_rollo['tratamiento_rollo']; ?></td>
</tr>
<tr>
  <td colspan="4" id="dato1"><strong>OBSERVACIONES : </strong><?php echo $row_rollo['observacion_rollo']; ?></td>
  </tr>
</table>
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($rollo);
?>
