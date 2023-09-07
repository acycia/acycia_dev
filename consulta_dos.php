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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php 
mysql_select_db($database_conexion1, $conexion1);
/*---------------DATOS EXTERNOS-----------------*/
$id_pedido=$_GET['id_pedido'];
$n_cotiz_pedido=$_GET['n_cotiz_pedido'];
$codigo_maquina=$_GET['codigo_maquina'];
$id_maquina=$_GET['id_maquina'];
/*-------------CONSULTAS--------------*/
/*------------------------------------*/
/*-----------CONSULTA MAQUINAS--------*/
if($id_maquina!='' && $codigo_maquina!='')
{ $resultado=mysql_query("SELECT * FROM maquina WHERE codigo_maquina='$codigo_maquina' AND id_maquina<>'$id_maquina'");
if (mysql_num_rows($resultado) > 0) { ?><div id="numero1"><strong><?php echo "EXISTE!!"; ?></strong></div> <?php } }
/*------------------------------------*/
/* pedido_bolsa_detalle.php */
if($id_pedido!='' && $n_cotiz_pedido!='')
{
$resultnueva=mysql_query("SELECT * FROM referencia,cotizacion_nueva WHERE referencia.cod_ref=cotizacion_nueva.cod_ref_cn AND cotizacion_nueva.n_cotiz_cn='$n_cotiz_pedido'");
$row_nueva=mysql_fetch_assoc($resultnueva);
$totalRows_nueva=mysql_num_rows($resultnueva);
$resultexiste=mysql_query("SELECT * FROM referencia,cotizacion_existente WHERE cotizacion_existente.n_cotiz_ce='$n_cotiz_pedido' AND cotizacion_existente.id_ref_ce=referencia.id_ref");
$row_existe=mysql_fetch_assoc($resultexiste);
$totalRows_existe=mysql_num_rows($resultexiste); ?>
<table id="tabla6"><tr>
<td valign="top" id="numero1">
<table>
<tr>
  <td id="fuente2"> REFERENCIA NUEVA </td>
</tr>
<?php if($totalRows_nueva>0) { ?>
<?php do { ?><tr>
<td id="detalle2"><?php /* Consulta de id_ref nueva en pedido_detalle*/
$id_ref1=$row_nueva['id_ref'];
$resultref1=mysql_query("SELECT * FROM pedido_detalle WHERE pedido_detalle.id_ref_pedido='$id_ref1' AND pedido_detalle.id_pedido='$id_pedido'");
$row_ref1=mysql_fetch_assoc($resultref1);
$totalRows_ref1=mysql_num_rows($resultref1);
if($totalRows_ref1>0) { ?><input type="radio" name="id_ref_pedido" id="id_ref_pedido" disabled="disabled"><?php } else{ ?><input type="radio" name="id_ref_pedido" id="id_ref_pedido" onClick="DatosTres('id_ref_pedido',<?php echo $id_ref1; ?>,'n_cotiz_pedido',n_cotiz.value,'id_pedido',id_pedido.value)"><?php } echo $row_nueva['cod_ref_cn']; ?>
</td>
</tr><?php } while ($row_nueva = mysql_fetch_assoc($resultnueva)); ?>
<?php } else{ ?><tr id="tr2"><td id="detalle2"> No hay REF'S</td></tr><?php } ?>
</table>
</td>
<td valign="top" id="numero1">
<table>
<tr>
  <td id="fuente2"> REFERENCIA EXISTENTE </td>
</tr>
<?php if($totalRows_existe>0) { ?>
<?php do { ?><tr>
<td id="detalle2"><?php /* Consulta de id_ref existe en pedido_detalle*/
$id_ref2=$row_existe['id_ref']; 
$resultref2=mysql_query("SELECT * FROM pedido_detalle WHERE pedido_detalle.id_ref_pedido='$id_ref2' AND pedido_detalle.id_pedido='$id_pedido'");
$row_ref2=mysql_fetch_assoc($resultref2);
$totalRows_ref2=mysql_num_rows($resultref2);
if($totalRows_ref2>0) { ?><input type="radio" name="id_ref_pedido" id="id_ref_pedido" disabled="disabled"><?php } else { ?><input type="radio" name="id_ref_pedido" id="id_ref_pedido" onClick="DatosTres('id_ref_pedido',<?php echo $id_ref2; ?>,'n_cotiz_pedido',n_cotiz.value,'id_pedido',id_pedido.value)"><?php } echo $row_existe['cod_ref']; ?></td>
</tr><?php } while ($row_existe = mysql_fetch_assoc($resultexiste)); ?>
<?php } else { ?><tr id="tr2"><td id="detalle2">No hay REF'S</td></tr><?php } ?>
</table>
</td>
</tr>
</table>
<?php } /* cerrar:id_pedido and $n_cotiz_pedido <> '' */ 
exit(); ?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>