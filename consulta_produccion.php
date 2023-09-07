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
/*----------------------------------------------*/
/*-----------------DATOS------------------------*/
$codigo_empleado=$_GET["codigo_empleado"];
$codigo_turno=$_GET['codigo_turno'];
$codigo_maquina=$_GET['codigo_maquina'];
/*----------------------------------------------*/
/*-----------------CODIGO EMPLEADO----------------------*/
if ($codigo_empleado!='')
{
$resultado = mysql_query("SELECT * FROM empleado WHERE codigo_empleado='$codigo_empleado'");
if (mysql_num_rows($resultado) > 0) { ?>
<div id="numero1"><strong><?php echo "EXISTE!!"; ?></strong></div> <?php } else { ?> <div id="acceso1"><strong> <?php echo "CORRECTO"; ?> </strong></div> <?php } }
/*------------------FIN DEL CODIGO EMPLEADO-----------------------*/
/*--------------------------------------------------------*/
/*------------------CODIGO DEL TURNO----------------------*/
if ($codigo_turno!='')
{
$resultado = mysql_query("SELECT * FROM empleado_turno WHERE codigo_turno='$codigo_turno'");
if (mysql_num_rows($resultado) > 0) 
{ ?>
<div id="numero1"><strong><?php echo "EXISTE!!"; ?></strong></div>
<?php } }
/*--------------FIN DEL CODIGO EMPLEADO--------------*/
/*---------------------------------------------------*/
/*--------------CODIGO DE LA MAQUINA------------------*/
if ($codigo_maquina!='')
{
$resultado = mysql_query("SELECT * FROM maquina WHERE codigo_maquina='$codigo_maquina'");
if (mysql_num_rows($resultado) > 0) 
{ ?>
<div id="numero1"><strong><?php echo "EXISTE!!"; ?></strong></div>
<?php } }
/*--------------FIN DE CODIGO MAQUINA--------------*/
/*---------------------------------------------------*/
exit();
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>