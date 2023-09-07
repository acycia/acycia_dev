<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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

$colname_plan_mejora = "-1";
if (isset($_GET['id_p'])) {
  $colname_plan_mejora = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_plan_mejora = sprintf("SELECT * FROM proveedor_mejora WHERE id_p_pm = %s ORDER BY id_pm ASC", $colname_plan_mejora);
$plan_mejora = mysql_query($query_plan_mejora, $conexion1) or die(mysql_error());
$row_plan_mejora = mysql_fetch_assoc($plan_mejora);
$totalRows_plan_mejora = mysql_num_rows($plan_mejora);

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor WHERE id_p = %s", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
<li><a href="menu.php" target="_top">MENU PRINCIPAL</a></li>
<li><a href="compras.php" target="_top">GESTION COMPRAS</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<table id="tabla1" align="center">
  <tr>    
    <td colspan="4" nowrap id="subtitulo">PLAN DE MEJORAS  <a href="proveedor_mejora_add.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/mas.gif" alt="ADD PLAN MEJORA" border="0" style="cursor:hand;"></a><a href="proveedor_edit.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/menos.gif" alt="EDIT PROVEEDOR" border="0" style="cursor:hand;" /></a><a href="proveedores.php"><img src="images/cat.gif" alt="PROVEEDORES" border="0" style="cursor:hand;"></a><a href="proveedor_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"/></a></td>    
    </tr>  
  <tr id="tr3">
    <td colspan="4" id="detalle1"><strong>PROVEEDOR </strong>: <?php echo $row_proveedor['proveedor_p']; ?></td>
    </tr>
  <tr id="tr2">
    <td id="detalle2">PLAN MEJORA </td>
    <td id="detalle2">RESPONSABLE</td>
    <td nowrap="nowrap" id="detalle2">FECHA</td>
    <td id="detalle2">CUMPLIMIENTO</td>
    </tr>
  <?php do { ?>
    <tr id="tr3">
      <td id="detalle1"><a href="proveedor_mejora_edit.php?id_pm= <?php echo $row_plan_mejora['id_pm']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_plan_mejora['plan_mejora_pm']; ?></a></td>
      <td id="detalle1"><a href="proveedor_mejora_edit.php?id_pm= <?php echo $row_plan_mejora['id_pm']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_plan_mejora['responsable_pm']; ?></a></td>
      <td nowrap="nowrap" id="detalle2"><a href="proveedor_mejora_edit.php?id_pm= <?php echo $row_plan_mejora['id_pm']; ?>" target="_top" style="text-decoration:none; color:#000000">- <?php echo $row_plan_mejora['fecha_pm']; ?> -</a></td>
      <td id="detalle2"><a href="proveedor_mejora_edit.php?id_pm= <?php echo $row_plan_mejora['id_pm']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_plan_mejora['cumplimiento_pm']; ?></a></td>
    </tr>
    <?php } while ($row_plan_mejora = mysql_fetch_assoc($plan_mejora)); ?>
</table></td></tr></table></div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($plan_mejora);

mysql_free_result($proveedor);
?>
