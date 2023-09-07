<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  session_unregister('MM_Username');
  session_unregister('MM_UserGroup');
	
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
  $updateSQL = sprintf("UPDATE menu SET nombre_menu=%s, url=%s, ver_url=%s WHERE id_menu=%s",
                       GetSQLValueString($_POST['nombre_menu'], "text"),
                       GetSQLValueString($_POST['url'], "text"),
					   GetSQLValueString(isset($_POST['habilitar']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['id_menu'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "menu_nuevo2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario_admon = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_admon = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_admon = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_admon);
$usuario_admon = mysql_query($query_usuario_admon, $conexion1) or die(mysql_error());
$row_usuario_admon = mysql_fetch_assoc($usuario_admon);
$totalRows_usuario_admon = mysql_num_rows($usuario_admon);

$colname_ver_menu = "-1";
if (isset($_GET['id_menu'])) {
  $colname_ver_menu = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_menu = sprintf("SELECT * FROM menu WHERE id_menu = %s", $colname_ver_menu);
$ver_menu = mysql_query($query_ver_menu, $conexion1) or die(mysql_error());
$row_ver_menu = mysql_fetch_assoc($ver_menu);
$totalRows_ver_menu = mysql_num_rows($ver_menu);

$colname_ver_submenu = "-1";
if (isset($_GET['id_menu'])) {
  $colname_ver_submenu = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_submenu = sprintf("SELECT * FROM submenu WHERE id_menu_submenu = %s", $colname_ver_submenu);
$ver_submenu = mysql_query($query_ver_submenu, $conexion1) or die(mysql_error());
$row_ver_submenu = mysql_fetch_assoc($ver_submenu);
$totalRows_ver_submenu = mysql_num_rows($ver_submenu);
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('nombre_menu','','R','url','','R');return document.MM_returnValue">
<table class="table table-bordered table-sm">
<tr>
  <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
  <td id="fuente1"><strong>EDITE EL MENU</strong></td>
  <td id="dato2"><a href="javascript:eliminar('menu',<?php echo $row_ver_menu['id_menu']; ?>,'menu_editar.php')"><img src="images/por.gif"  alt="ELIMINAR MENU" border="0" style="cursor:hand;"/></a><a href="menu_nuevo.php"><img src="images/mas.gif" alt="ADD NUEVO MENU" border="0" style="cursor:hand;"/></a><a href="menu_nuevo2.php?id_menu= <?php echo $row_ver_menu['id_menu']; ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO SUBMENU'S" border="0"></a><a href="menu1.php"><img src="images/cat.gif" alt="LISTADO MENU'S" border="0" style="cursor:hand;"/></a><img src="images/ciclo1.gif" alt="RESTAURAR" width="18" height="18" style="cursor:hand;" onClick="window.history.go()" ></td>
</tr>
<tr>
  <td colspan="2" id="fuente1"><strong><?php echo $row_ver_menu['id_menu']; ?> . </strong>Menu
    <input name="textfield" type="text" value="<?php echo $row_ver_menu['id_menu']; ?>" size="5"></td>
  </tr>
<tr>
  <td colspan="2" id="dato1"><input name="nombre_menu" type="text" value="<?php echo $row_ver_menu['nombre_menu']; ?>" size="50"></td>
  </tr>
<tr>
  <td colspan="2" id="fuente1">Direccion, URL, Pagina del Menu </td>
  </tr>
<tr>
  <td colspan="2" id="dato1"><input name="url" type="text" value="<?php echo $row_ver_menu['url']; ?>" size="50">
      Habilitar Menu? si:
                <input <?php if (!(strcmp($row_ver_menu['ver_url'],1))) {echo "checked=\"checked\"";} ?> name="habilitar" type="checkbox" id="habilitar" value="1" /></td>
  </tr>
<tr>
  <td colspan="2" id="fuente1">Este registro esta relacionado con el Menu Principal.</td>
  </tr>
<tr>
  <td colspan="3" id="dato2">Nota: Se recomienda que esta acci&oacute;n sea realizada por el administrador del sistema SISADGE </td>
  </tr>
<tr>
<td colspan="3" id="dato2"><input type="submit" value="Actualizar Menu"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="form1">
<input type="hidden" name="id_menu" value="<?php echo $row_ver_menu['id_menu']; ?>">
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario_admon);

mysql_free_result($ver_menu);

mysql_free_result($ver_submenu);
?>
