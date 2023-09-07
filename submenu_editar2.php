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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE Tbl_submenu_submenu SET id_sub_menu=%s, id_menu_submenu=%s, nombre_submenu=%s, url=%s, ver_url=%s  WHERE id_submenu=%s",
                       GetSQLValueString($_POST['id_sub_menu'], "int"),
					   GetSQLValueString($_POST['id_menu_submenu'], "int"),
                       GetSQLValueString($_POST['nombre_submenu'], "text"),
                       GetSQLValueString($_POST['url'], "text"),
					   GetSQLValueString(isset($_POST['habilitar']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['id_submenu'], "int"));  
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "menu_nuevo3.php?id_menu=" . $row_ver_submenu['id_menu_submenu'] . "";
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

$colname_ver_submenu = "-1";
if (isset($_GET['id_submenu'])) {
  $colname_ver_submenu = (get_magic_quotes_gpc()) ? $_GET['id_submenu'] : addslashes($_GET['id_submenu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_submenu = sprintf("SELECT * FROM submenu WHERE id_submenu = %s", $colname_ver_submenu);
$ver_submenu = mysql_query($query_ver_submenu, $conexion1) or die(mysql_error());
$row_ver_submenu = mysql_fetch_assoc($ver_submenu);
$totalRows_ver_submenu = mysql_num_rows($ver_submenu);

$colname_menu = "-1";
if (isset($_GET['id_menu'])) {
  $colname_menu = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_menu = sprintf("SELECT * FROM menu WHERE id_menu = %s", $colname_menu);
$menu = mysql_query($query_menu, $conexion1) or die(mysql_error());
$row_menu = mysql_fetch_assoc($menu);
$totalRows_menu = mysql_num_rows($menu);

$colname_ver_submenu_sub = "-1";
if (isset($_GET['id_sub_submenu'])) {
  $colname_ver_submenu_sub = (get_magic_quotes_gpc()) ? $_GET['id_sub_submenu'] : addslashes($_GET['id_submenu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_submenu_sub = sprintf("SELECT * FROM Tbl_submenu_submenu WHERE id_submenu = %s", $colname_ver_submenu_sub);
$ver_submenu_sub = mysql_query($query_ver_submenu_sub, $conexion1) or die(mysql_error());
$row_ver_submenu_sub = mysql_fetch_assoc($ver_submenu_sub);
$totalRows_ver_submenu_sub = mysql_num_rows($ver_submenu_sub);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<div align="center">
      <table id="tabla2">          
          <tr>
            <td rowspan="7" id="dato2"><img src="images/logoacyc.jpg" /></td>
            <td id="fuente2"><strong>EDITE EL SUBMENU</strong></td>
            <td id="dato2"><a href="javascript:eliminar2('id_submenu',<?php echo $row_ver_submenu['id_submenu']; ?>,'submenu_editar.php','id_menu',<?php echo $row_ver_submenu['id_menu_submenu']; ?>)"><img src="images/por.gif" alt="ELIMINAR SUBMENU" border="0" style="cursor:hand;"/></a><a href="menu_nuevo2.php?id_menu=<?php echo $row_ver_submenu['id_menu_submenu']; ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/identico.gif" style="cursor:hand;" alt="LISTADO SUBMENU'S" border="0"></a><a href="menu1.php"><img src="images/cat.gif" alt="LISTADO MENU'S" border="0" style="cursor:hand;"/></a><img src="images/ciclo1.gif" alt="RESTAURAR" width="18" height="18" style="cursor:hand;" onClick="window.history.go()" ></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">* Nombre del Sub-Submenu N&deg; 
              <input name="id_submenu" type="text" id="id_submenu" value="<?php echo $row_ver_submenu_sub['id_submenu']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><input type="text" name="nombre_submenu" value="<?php echo $row_ver_submenu_sub['nombre_submenu']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">* Direcci&oacute;n, URL, Pagina del Submenu </td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><input type="text" name="url" value="<?php echo $row_ver_submenu_sub['url']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Menu <strong><?php echo $row_menu['nombre_menu']; ?></strong> N&deg;
              <input name="id_menu_submenu" type="text" value="<?php echo $row_menu['id_menu']; ?>" size="5" /></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Sub-Menu <strong><?php echo $row_ver_submenu['nombre_submenu']; ?></strong> N&deg;
              <input name="id_sub_menu" type="text" value="<?php echo $row_ver_submenu['id_submenu']; ?>" size="5" />
              
                Habilitar Submenu? si:
                <input <?php if (!(strcmp($row_ver_submenu_sub['ver_url'],1))) {echo "checked=\"checked\"";} ?> name="habilitar" type="checkbox" id="habilitar" value="1" /></td>
          </tr>
		  <tr>
		    <td colspan="3" id="dato2">Nota: Se recomienda que esta acci&oacute;n sea realizada por el administrador del sistema SISADGE </td>
	    </tr>
		  <tr>
            <td colspan="3" id="dato2"><input type="submit" value="Actualizar Submenu"></td>
          </tr>
  </table>
  </div>
        <input type="hidden" name="MM_update" value="form2">
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
mysql_free_result($ver_submenu);

mysql_free_result($menu);
?>