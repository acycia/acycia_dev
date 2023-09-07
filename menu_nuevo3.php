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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO Tbl_submenu_submenu (id_submenu, id_sub_menu, id_menu_submenu, nombre_submenu, url, ver_url) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_submenu'], "int"),
					   GetSQLValueString($_POST['id_sub_menu'], "int"),
                       GetSQLValueString($_POST['id_menu_submenu'], "int"),					   
                       GetSQLValueString($_POST['nombre_submenu'], "text"),
                       GetSQLValueString($_POST['url'], "text"),
					   GetSQLValueString(isset($_POST['habilitar']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "menu_nuevo3.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

$colname_ver_nuevo = "-1";
if (isset($_GET['id_menu'])) {
  $colname_ver_nuevo = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = sprintf("SELECT * FROM menu WHERE id_menu = %s", $colname_ver_nuevo);
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);

$colname_ver_submenu = "-1";
if (isset($_GET['id_submenu'])) {
  $colname_ver_submenu = (get_magic_quotes_gpc()) ? $_GET['id_submenu'] : addslashes($_GET['id_submenu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_submenu = sprintf("SELECT * FROM submenu WHERE id_submenu = %s", $colname_ver_submenu);
$ver_submenu = mysql_query($query_ver_submenu, $conexion1) or die(mysql_error());
$row_ver_submenu = mysql_fetch_assoc($ver_submenu);
$totalRows_ver_submenu = mysql_num_rows($ver_submenu);

$colname_ver_submenu_sub = "-1";
if (isset($_GET['id_submenu'])) {
  $colname_ver_submenu_sub = (get_magic_quotes_gpc()) ? $_GET['id_submenu'] : addslashes($_GET['id_submenu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_submenu_sub = sprintf("SELECT * FROM Tbl_submenu_submenu WHERE id_sub_menu = %s ORDER BY id_submenu ASC", $colname_ver_submenu_sub);
$ver_submenu_sub = mysql_query($query_ver_submenu_sub, $conexion1) or die(mysql_error());
$row_ver_submenu_sub = mysql_fetch_assoc($ver_submenu_sub);
$totalRows_ver_submenu_sub = mysql_num_rows($ver_submenu_sub);

$colname_ver_submenu_sub = "-1";
if (isset($_GET['id_menu'])) {
  $colname_ver_submenu_sub = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo2 =("SELECT * FROM Tbl_submenu_submenu ORDER BY id_submenu DESC");
$ver_nuevo2 = mysql_query($query_ver_nuevo2, $conexion1) or die(mysql_error());
$row_ver_nuevo2 = mysql_fetch_assoc($ver_nuevo2);
$totalRows_ver_nuevo2 = mysql_num_rows($ver_nuevo2);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table id="tabla4" align="center">
<tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1" align="center"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario_admon['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
            <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
			<li><a href="menu.php">MENU PRINCIPAL</a></li>
            <li><a href="administrador.php">ADMINISTRADOR</a></li>            
</ul></td>
</tr>
<tr>
	<td align="center" colspan="2" id="linea1">
	<form action="<?php echo $editFormAction; ?>" method="post" name="form2" onsubmit="MM_validateForm('nombre_submenu','','R','url','','R');return document.MM_returnValue">
	<table id="tabla2">
	<tr>
	  <td colspan="3" rowspan="9" id="dato2"><img src="images/logoacyc.jpg" /></td>
  <td id="fuente1"><strong>SUBMENU'S - SUBMENU'S</strong></td>
  <td id="dato2"><a href="menu_editar.php?id_menu=<?php echo $row_ver_nuevo['id_menu']; ?>"><img src="images/menos.gif" alt="EDIT MENU" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar('menu',<?php //echo $row_ver_nuevo['id_menu']; ?>,'menu_nuevo2.php')"><img src="images/por.gif"  alt="ELIMINAR MENU" border="0" style="cursor:hand;"/></a><a href="menu_nuevo.php"><img src="images/mas.gif" alt="ADD NUEVO MENU" border="0" style="cursor:hand;"/></a><a href="menu_nuevo2.php?id_menu=<?php echo $row_ver_nuevo['id_menu']; ?>"><img src="images/cat.gif" alt="LISTADO MENU'S" border="0" style="cursor:hand;"/></a><img src="images/ciclo1.gif" alt="RESTAURAR" style="cursor:hand;" onclick="window.history.go()"></td>
</tr>
<tr>
  <td colspan="2" id="fuente1">Menu : <strong><?php echo $row_ver_nuevo['id_menu']; ?>. </strong><?php echo $row_ver_nuevo['nombre_menu']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente1">URL del Menu : <?php echo $row_ver_nuevo['url']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente1">Submenu : <strong><?php echo $row_ver_submenu['id_submenu']; ?>. </strong><?php echo $row_ver_submenu['nombre_submenu']; ?></td>
</tr>
<tr>
  <td colspan="2" id="fuente1">URL del Submenu : <?php echo $row_ver_submenu['url']; ?></td>
</tr>
<tr>
  <td colspan="2" id="fuente1">&nbsp;</td>
  </tr>
<tr>
  <td colspan="2" id="dato1">Los MENU'S pertenecen al menu principal y los SUBMENU'S al menu secundario y los Submenu del Submenu</td>
</tr>
<tr>
  <td colspan="2" id="dato1">Se recomienda que esta acci&oacute;n sea realizada por el administrador de SISADGE . </td>
</tr>
<tr>
  <td colspan="2" id="dato1">&nbsp;</td>
</tr>

  <?php $submenu_sub=$row_ver_submenu_sub['id_submenu'];
  if($submenu_sub!='')
  {
  ?>
<tr id="tr2">
  <td id="titulo4">N°</td>
  <td id="titulo4">Habilitado</td>
<td id="titulo4">SUB SUBMENU'S</td>
<td id="titulo4">PAGINA</td>
<td id="titulo4">ACCION</td>
</tr><?php do { ?>
<tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">
  <td id="detalle1"><?php echo $row_ver_submenu_sub['id_submenu']; ?></td>
  <td id="detalle2"><?php if (!(strcmp($row_ver_submenu_sub['ver_url'],1))) {echo "SI";}else echo "NO"; ?></td>
<td id="detalle1"><a href="submenu_editar2.php?id_menu=<?php echo $row_ver_submenu['id_menu_submenu']; ?>&amp;id_submenu=<?php echo $row_ver_submenu['id_submenu']; ?>&amp;id_sub_submenu=<?php echo $row_ver_submenu_sub['id_submenu']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_submenu_sub['nombre_submenu']; ?></a></td>

<td id="detalle1"><a href="submenu_editar2.php?id_menu=<?php echo $row_ver_submenu['id_menu_submenu']; ?>&amp;id_submenu=<?php echo $row_ver_submenu['id_submenu']; ?>&amp;id_sub_submenu=<?php echo $row_ver_submenu_sub['id_submenu']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_submenu_sub['url']; ?></a></td>
<td id="detalle2"><a href="submenu_editar2.php?id_menu=<?php echo $row_ver_submenu['id_menu_submenu']; ?>&amp;id_submenu=<?php echo $row_ver_submenu['id_submenu']; ?>&amp;id_sub_submenu=<?php echo $row_ver_submenu_sub['id_submenu']; ?>"><img src="images/menos.gif" alt="EDIT SUBMENU" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar2('id_sub_submenu',<?php echo $row_ver_submenu_sub['id_submenu']; ?>.<?php echo $row_ver_submenu_sub['id_sub_menu']; ?>,'menu_nuevo3.php','id_menu',<?php echo $row_ver_submenu_sub['id_menu_submenu']; ?>)"><img src="images/por.gif" alt="ELIMINAR SUBMENU" border="0" style="cursor:hand;"/></a></td>
</tr><?php } while ($row_ver_submenu_sub = mysql_fetch_assoc($ver_submenu_sub)); ?>
<?php
} ?>
<tr>
  <td id="fuente2">N&deg;</td>
  <td id="fuente2">Habilitar </td>
<td id="fuente2">Digite el submenu nuevo </td>
<td id="fuente2">Direccion, URL, Pagina del Submenu </td>
<td id="fuente2">Adicionar Nuevo</td>
</tr>
<tr>
  <td id="dato2"><input name="id_submenu" type="text" value="<?php
$num=$row_ver_nuevo2['id_submenu']+1; echo $num; ?>" size="2" /></td>
  <td id="dato2"><input name="habilitar" type="checkbox" id="habilitar" value="1" checked="checked" /></td>
<td id="dato2"><input name="id_menu_submenu" type="hidden" value="<?php echo $row_ver_nuevo['id_menu']; ?>" />
  <input name="id_sub_menu" type="hidden" value="<?php echo $row_ver_submenu['id_submenu']; ?>" />
  <input type="text" name="nombre_submenu" value="" size="25" /></td>
<td id="dato2"><input name="url" type="text" id="url" value="" size="30" /></td>
<td id="dato2"><input type="submit" value="Add Submenu"></td>
</tr>
<tr id="tr1">
<td colspan="5" id="dato2"><a href="menu_nuevo2.php">FINALIZAR REGISTRO DE MENU - SUB SUBMENU'S </a></td>
</tr>
</table>
<input type="hidden" name="MM_insert" value="form2">
</form>
</td></tr></table>
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
mysql_free_result($ver_nuevo);
mysql_free_result($ver_submenu);
mysql_free_result($ver_nuevo2);
?>