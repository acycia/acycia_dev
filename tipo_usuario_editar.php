<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
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
session_start();
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
$conexion = new ApptivaDB();

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tipo_user SET nombre_tipo=%s, observacion_tipo=%s WHERE id_tipo=%s",
                       GetSQLValueString($_POST['nombre_tipo'], "text"),
                       GetSQLValueString($_POST['observacion_tipo'], "text"),
                       GetSQLValueString($_POST['id_tipo'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "tipo_permisos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario_edit = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_edit = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_edit = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_edit);
$usuario_edit = mysql_query($query_usuario_edit, $conexion1) or die(mysql_error());
$row_usuario_edit = mysql_fetch_assoc($usuario_edit);
$totalRows_usuario_edit = mysql_num_rows($usuario_edit);

$colname_ver_u = "-1";
if (isset($_GET['id_tipo'])) {
  $colname_ver_u = (get_magic_quotes_gpc()) ? $_GET['id_tipo'] : addslashes($_GET['id_tipo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_u = sprintf("SELECT * FROM tipo_user WHERE id_tipo = %s", $colname_ver_u);
$ver_u = mysql_query($query_ver_u, $conexion1) or die(mysql_error());
$row_ver_u = mysql_fetch_assoc($ver_u);
$totalRows_ver_u = mysql_num_rows($ver_u);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>

<!-- desde aqui para listados nuevos -->
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<!-- select2 -->
<link href="select2/css/select2.min.css" rel="stylesheet"/>
<script src="select2/js/select2.min.js"></script>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
<?php echo $conexion->header('listas'); ?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('nombre_tipo','','R','observacion_tipo','','R');return document.MM_returnValue">
  <table class="table table-bordered table-sm">    
    <tr>
      <td rowspan="5" id="dato2"><img src="images/logoacyc.jpg"></td>
      <td id="fuente1"><strong>EDITE TIPO DE USUARIO</strong></td>
      <td id="dato2"><a href="javascript:eliminar('id_tipo',<?php echo $row_ver_u['id_tipo']; ?>,'tipo_usuario_editar.php')"><img src="images/por.gif" alt="ELIMINAR TIPO DE USUARIO" border="0" style="cursor:hand;"/></a><a href="tipo_usuario_nuevo.php"><img src="images/mas.gif" alt="ADD NUEVO TIPO DE USUARIO" border="0" style="cursor:hand;" /></a><a href="tipo_permisos.php?id_tipo=<?php echo $row_ver_u['id_tipo']; ?>"><img src="images/identico.gif" style="cursor:hand;" alt="TIPO Y PERMISOS" border="0" /></a><a href="tipos_usuario.php"><img src="images/cat.gif" alt="TIPOS DE USUARIO" border="0" style="cursor:hand;"  /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" onClick="window.history.go()" ></td>
    </tr>
    <tr>
      <td colspan="2" id="fuente1">*Tipo de Usuario</td>
      </tr>
    <tr>
      <td colspan="2" id="dato1"><input type="text" name="nombre_tipo" value="<?php echo $row_ver_u['nombre_tipo']; ?>" size="32"></td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1">*Caracteristicas del Tipo de Usuario </td>
      </tr>
    <tr>
      <td colspan="2" id="dato1"><textarea name="observacion_tipo" cols="55" rows="3"><?php echo $row_ver_u['observacion_tipo']; ?></textarea></td>
      </tr>    
    <tr>
      <td colspan="3" id="dato2">Nota: Se recomienda que esta acción sea realizada por el administrador del sistema SISADGE</td>
      </tr>
    <tr>
      <td colspan="3" id="dato2"><input type="submit" value="Actualizar Tipo de Usuario"></td>
      </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id_tipo" value="<?php echo $row_ver_u['id_tipo']; ?>">
  </form>
  </td></tr></table>
	<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario_edit);
mysql_free_result($ver_u);
?>
