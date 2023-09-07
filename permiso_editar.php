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
  $updateSQL = sprintf("UPDATE permisos SET usuario=%s, submenu=%s, menu=%s WHERE id_registro=%s",
                       GetSQLValueString($_POST['usuario'], "text"),
                       GetSQLValueString($_POST['submenu'], "text"),
                       GetSQLValueString($_POST['menu'], "text"),
                       GetSQLValueString($_POST['id_registro'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "tipo_permisos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario_admon = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_admon = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_admon = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_admon);
$usuario_admon = mysql_query($query_usuario_admon, $conexion1) or die(mysql_error());
$row_usuario_admon = mysql_fetch_assoc($usuario_admon);
$totalRows_usuario_admon = mysql_num_rows($usuario_admon);

$colname_permiso = "-1";
if (isset($_GET['id_registro'])) {
  $colname_permiso = (get_magic_quotes_gpc()) ? $_GET['id_registro'] : addslashes($_GET['id_registro']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_permiso = sprintf("SELECT * FROM permisos WHERE id_registro = %s", $colname_permiso);
$permiso = mysql_query($query_permiso, $conexion1) or die(mysql_error());
$row_permiso = mysql_fetch_assoc($permiso);
$totalRows_permiso = mysql_num_rows($permiso);

mysql_select_db($database_conexion1, $conexion1);
$query_select_menu = "SELECT * FROM menu";
$select_menu = mysql_query($query_select_menu, $conexion1) or die(mysql_error());
$row_select_menu = mysql_fetch_assoc($select_menu);
$totalRows_select_menu = mysql_num_rows($select_menu);

$colname_select_submenu = "-1";
if (isset($_GET['menu'])) {
  $colname_select_submenu = (get_magic_quotes_gpc()) ? $_GET['menu'] : addslashes($_GET['menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_select_submenu = sprintf("SELECT * FROM submenu WHERE id_menu_submenu = %s", $colname_select_submenu);
$select_submenu = mysql_query($query_select_submenu, $conexion1) or die(mysql_error());
$row_select_submenu = mysql_fetch_assoc($select_submenu);
$totalRows_select_submenu = mysql_num_rows($select_submenu);

$colname_tipo_usuario = "-1";
if (isset($_GET['id_tipo'])) {
  $colname_tipo_usuario = (get_magic_quotes_gpc()) ? $_GET['id_tipo'] : addslashes($_GET['id_tipo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_tipo_usuario = sprintf("SELECT * FROM tipo_user WHERE id_tipo = %s", $colname_tipo_usuario);
$tipo_usuario = mysql_query($query_tipo_usuario, $conexion1) or die(mysql_error());
$row_tipo_usuario = mysql_fetch_assoc($tipo_usuario);
$totalRows_tipo_usuario = mysql_num_rows($tipo_usuario);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
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
<div id="cabecera"><img src="images/cabecera.jpg"></div>
<div id="cabezamenu">
<ul id="menuhorizontal">
           <li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
           <li><a href="administrador.php">ADMINISTRADOR</a></li>
		   <li><a href="tipos_usuario.php">TIPOS USUARIO</a></li>
		   <li><a href="tipo_permisos.php?id_tipo=<?php echo $row_permiso['usuario']; ?>">TIPO Y PERMISOS</a></li>           
</ul>
</div>
<div id="nombreusuario"><?php echo $row_usuario_admon['nombre_usuario']; ?></div>
<div align="center">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="MM_validateForm('menu','','R','submenu','','R');return document.MM_returnValue">
  <table id="tabla2">    
    <tr>
      <td rowspan="7" id="dato2"><img src="images/logoacyc.jpg" /></td>
      <td id="fuente2"><strong>EDITE ESTE PERMISO</strong></td>
      <td id="dato2"><a href="javascript:eliminar2('id_registro',<?php echo $row_permiso['id_registro']; ?>,'permiso_editar.php','tipo',<?php echo $row_tipo_usuario['id_tipo']; ?>)"><img src="images/por.gif"  alt="ELIMINAR PERMISO" border="0" style="cursor:hand;"/></a><a href="tipo_permisos.php?id_tipo=<?php echo $row_tipo_usuario['id_tipo']; ?>"><img src="images/mas.gif" alt="ADD NUEVO PERMISO" border="0" style="cursor:hand;" /></a><a href="tipo_permisos.php?id_tipo=<?php echo $row_tipo_usuario['id_tipo']; ?>"><img src="images/identico.gif" style="cursor:hand;" alt="TIPO Y PERMISOS" border="0" /></a><a href="tipos_usuario.php"><img src="images/cat.gif" alt="TIPOS DE USUARIO" border="0" style="cursor:hand;" /></a><img src="images/ciclo1.gif" alt="RESTAURAR" style="cursor:hand;" onclick="window.history.go()" /></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">TIPO DE USUARIO : 
        <input name="usuario" type="hidden" value="<?php echo $row_permiso['usuario']; ?>" />
        <?php $tipo=$row_permiso['usuario'];
		$sql="select * from tipo_user where id_tipo='$tipo'";
		$result=mysql_query($sql);
		$tipo1=mysql_result($result,0,'nombre_tipo');
		if($tipo1!='' && $tipo1!='0')
		{
		echo $tipo1;
		}
		?></td>
      </tr>
    <tr>
      <td id="fuente1">MENU : </td>
      <td id="fuente1">SUBMENU : </td>
    </tr>
    <tr>
      <td id="dato1"><?php $menu=$row_permiso['menu'];
		$sql="select * from menu where id_menu='$menu'";
		$result=mysql_query($sql);
		$menu1=mysql_result($result,0,'nombre_menu');
		if($menu1!='' && $menu1!='0')
		{
		echo $menu1;
		}
		?></td>
      <td id="dato1"><?php $submenu=$row_permiso['submenu'];
		$sql="select * from submenu where id_submenu='$submenu'";
		$result=mysql_query($sql);
		$submenu1=mysql_result($result,0,'nombre_submenu');
		if($submenu1!='' && $submenu1!='0')
		{
		echo $submenu1;
		}
		?></td>
      </tr>
    <tr>
      <td id="fuente1"><select name="menu" id="menu" onblur="DatosConsulta('menu',form1.menu.value);">
        <option value="">Seleccione</option>
        <?php
do {  
?>
        <option value="<?php echo $row_select_menu['id_menu']?>"><?php echo $row_select_menu['nombre_menu']?></option>
        <?php
} while ($row_select_menu = mysql_fetch_assoc($select_menu));
  $rows = mysql_num_rows($select_menu);
  if($rows > 0) {
      mysql_data_seek($select_menu, 0);
	  $row_select_menu = mysql_fetch_assoc($select_menu);
  }
?>
      </select></td>
      <td id="fuente1"><div id="resultado"></div></td>
      </tr>
    <tr>
      <td colspan="2" id="dato1">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente2">Se recomienda que esta acci&oacute;n sea realizada por el administrador del Sistema SISADGE </td>
      </tr>
    <tr>
      <td colspan="3" id="dato2"><input name="submit" type="submit" value="Actualizar Permiso de Acceso" /></td>
      </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id_registro" value="<?php echo $row_permiso['id_registro']; ?>">
</form>
</div>
</div>
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
mysql_free_result($usuario_admon);

mysql_free_result($permiso);

mysql_free_result($select_menu);

mysql_free_result($select_submenu);

mysql_free_result($tipo_usuario);
?>
