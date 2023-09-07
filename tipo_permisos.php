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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO permisos (id_registro, usuario, submenu, menu) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_registro'], "int"),
                       GetSQLValueString($_POST['usuario'], "text"),
                       GetSQLValueString($_POST['submenu'], "text"),
                       GetSQLValueString($_POST['menu'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "tipo_permisos.php?id_tipo=" . $_GET['id_tipo'] . "&id_menu=" . $_GET['id_menu'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE permisos SET usuario=%s, submenu=%s, menu=%s WHERE id_registro=%s",
                       GetSQLValueString($_POST['usuario'], "text"),
                       GetSQLValueString($_POST['submenu'], "text"),
                       GetSQLValueString($_POST['menu'], "text"),
                       GetSQLValueString($_POST['id_registro'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "tipo_permisos.php?id_tipo=" . $_GET['id_tipo'] . "&id_menu=" . $_GET['id_menu'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$conexion = new ApptivaDB();

$colname_usuario_admon = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_admon = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_admon = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_admon);
$usuario_admon = mysql_query($query_usuario_admon, $conexion1) or die(mysql_error());
$row_usuario_admon = mysql_fetch_assoc($usuario_admon);
$totalRows_usuario_admon = mysql_num_rows($usuario_admon);

$colname_tipo_usuario = "-1";
if (isset($_GET['id_tipo'])) {
  $colname_tipo_usuario = (get_magic_quotes_gpc()) ? $_GET['id_tipo'] : addslashes($_GET['id_tipo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_tipo_usuario = sprintf("SELECT * FROM tipo_user WHERE id_tipo = %s", $colname_tipo_usuario);
$tipo_usuario = mysql_query($query_tipo_usuario, $conexion1) or die(mysql_error());
$row_tipo_usuario = mysql_fetch_assoc($tipo_usuario);
$totalRows_tipo_usuario = mysql_num_rows($tipo_usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_menus = "SELECT * FROM menu";
$menus = mysql_query($query_menus, $conexion1) or die(mysql_error());
$row_menus = mysql_fetch_assoc($menus);
$totalRows_menus = mysql_num_rows($menus);

$colname_vermenu = "-1";
if (isset($_GET['id_menu'])) {
  $colname_vermenu = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_vermenu = sprintf("SELECT * FROM menu WHERE id_menu = %s", $colname_vermenu);
$vermenu = mysql_query($query_vermenu, $conexion1) or die(mysql_error());
$row_vermenu = mysql_fetch_assoc($vermenu);
$totalRows_vermenu = mysql_num_rows($vermenu);

$colname2_submenus = "-1";
if (isset($_GET['id_tipo'])) {
  $colname2_submenus = (get_magic_quotes_gpc()) ? $_GET['id_tipo'] : addslashes($_GET['id_tipo']);
}
$colname_submenus = "-1";
if (isset($_GET['id_menu'])) {
  $colname_submenus = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_submenus = sprintf("SELECT * FROM submenu WHERE submenu.id_menu_submenu = '%s' AND submenu.id_submenu NOT IN(SELECT permisos.submenu FROM permisos WHERE permisos.menu = '%s' AND permisos.usuario = '%s')", $colname_submenus,$colname_submenus,$colname2_submenus);
$submenus = mysql_query($query_submenus, $conexion1) or die(mysql_error());
$row_submenus = mysql_fetch_assoc($submenus);
$totalRows_submenus = mysql_num_rows($submenus);

$colname2_verpermisos = "-1";
if (isset($_GET['id_menu'])) {
  $colname2_verpermisos = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
$colname_verpermisos = "-1";
if (isset($_GET['id_tipo'])) {
  $colname_verpermisos = (get_magic_quotes_gpc()) ? $_GET['id_tipo'] : addslashes($_GET['id_tipo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verpermisos = sprintf("SELECT * FROM permisos, submenu WHERE permisos.usuario = '%s' AND permisos.menu = '%s' AND permisos.submenu =submenu.id_submenu ORDER BY permisos.id_registro", $colname_verpermisos,$colname2_verpermisos);
$verpermisos = mysql_query($query_verpermisos, $conexion1) or die(mysql_error());
$row_verpermisos = mysql_fetch_assoc($verpermisos);
$totalRows_verpermisos = mysql_num_rows($verpermisos);

$colname_editpermisos = "-1";
if (isset($_GET['id_submenu'])) {
  $colname_editpermisos = (get_magic_quotes_gpc()) ? $_GET['id_submenu'] : addslashes($_GET['id_submenu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editpermisos = sprintf("SELECT * FROM permisos WHERE submenu = '%s'", $colname_editpermisos);
$editpermisos = mysql_query($query_editpermisos, $conexion1) or die(mysql_error());
$row_editpermisos = mysql_fetch_assoc($editpermisos);
$totalRows_editpermisos = mysql_num_rows($editpermisos);

$colname_editsubmenus = "-1";
if (isset($_GET['id_menu'])) {
  $colname_editsubmenus = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
$colname2_editsubmenus = "-1";
if (isset($_GET['id_tipo'])) {
  $colname2_editsubmenus = (get_magic_quotes_gpc()) ? $_GET['id_tipo'] : addslashes($_GET['id_tipo']);
}
$colname3_editsubmenus = "-1";
if (isset($_GET['id_submenu'])) {
  $colname3_editsubmenus = (get_magic_quotes_gpc()) ? $_GET['id_submenu'] : addslashes($_GET['id_submenu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editsubmenus = sprintf("SELECT * FROM submenu WHERE submenu.id_menu_submenu = '%s' AND submenu.id_submenu NOT IN(SELECT permisos.submenu FROM permisos WHERE permisos.submenu <> '%s' AND permisos.menu = '%s' AND permisos.usuario = '%s')", $colname_editsubmenus,$colname3_editsubmenus,$colname_editsubmenus,$colname2_editsubmenus);
$editsubmenus = mysql_query($query_editsubmenus, $conexion1) or die(mysql_error());
$row_editsubmenus = mysql_fetch_assoc($editsubmenus);
$totalRows_editsubmenus = mysql_num_rows($editsubmenus);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

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
	<table class="table table-bordered table-sm">
	<tr>
	<td rowspan="7" id="dato2"><img src="images/logoacyc.jpg" /></td>
	<td id="fuente1"><strong>TIPO Y PERMISOS DE USUARIO</strong></td>
	<td id="dato2"><a href="tipo_usuario_editar.php?id_tipo=<?php echo $row_tipo_usuario['id_tipo']; ?>"><img src="images/menos.gif" alt="EDIT TIPO DE USUARIO" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar('id_tipo',<?php echo $row_tipo_usuario['id_tipo']; ?>,'tipo_permisos.php')"><img src="images/por.gif" alt="ELIMINAR TIPO DE USUARIO" border="0" style="cursor:hand;"/></a><a href="tipo_usuario_nuevo.php"><img src="images/mas.gif" alt="ADD NUEVO TIPO DE USUARIO" border="0" style="cursor:hand;" /></a><a href="tipos_usuario.php"><img src="images/cat.gif" alt="TIPOS DE USUARIO" border="0" style="cursor:hand;"  /></a><img src="images/ciclo1.gif" alt="RESTAURAR" style="cursor:hand;" onclick="window.history.go()" /></td>
	</tr>
	<tr>
	  <td colspan="2" id="fuente1">TIPO DE USUARIO :</td>
  </tr>
<tr>
  <td colspan="2" id="dato1"></td>
  </tr>
<tr>
  <td colspan="2" id="dato1"><strong><?php echo $row_tipo_usuario['id_tipo']; ?> . <?php echo $row_tipo_usuario['nombre_tipo']; ?></strong></td>
  </tr>
<tr>
  <td colspan="2" id="fuente1">CARACTERISTICAS DE ESTE TIPO DE USUARIO : </td>
  </tr>
<tr>
  <td colspan="2" id="dato1"><?php echo $row_tipo_usuario['observacion_tipo']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente1">Se recomienda que esta acci&oacute;n sea realizada por el administrador de SISADGE </td>
</tr>
<tr id="tr1">
  <td colspan="3" id="titulo2">PERMISOS DE ACCESO PARA ESTE TIPO DE USUARIO</td>
  </tr>
<tr id="tr3">
  <td colspan="3" id="dato2">  
  <?php if($_GET['id_menu'] == '') { ?>
  <table id="tabla6">
    <tr id="tr2">
      <td id="detalle1">ID</td>
      <td id="detalle1">MENU'S</td>
      <td id="detalle2">SUBMENU'S</td>
    </tr>
    <?php do { ?>
      <tr>
        <td id="detalle1"><?php echo $row_menus['id_menu']; ?></td>
          <td id="detalle1"><?php echo $row_menus['nombre_menu']; ?></td>
        <td id="detalle2"><a href="tipo_permisos.php?id_tipo=<?php echo $row_tipo_usuario['id_tipo']; ?>&amp;id_menu=<?php echo $row_menus['id_menu']; ?>"><?php $menu=$row_menus['id_menu']; $id_tipo=$_GET['id_tipo'];
		$submenu="SELECT * FROM permisos WHERE menu='$menu' AND usuario='$id_tipo'";
		$result2 = mysql_query($submenu);
		$num_rows = mysql_num_rows($result2);
		if($num_rows != '0')
		{ ?><img src="images/identico.gif" alt="SUBMENU'S" border="0" style="cursor:hand;"/>
          <?php } if($num_rows == '0' || $num_rows == '') { echo "- -"; } ?></a></td>
      </tr>
      <?php } while ($row_menus = mysql_fetch_assoc($menus)); ?>
  </table>
  <?php } ?>
  </td>
</tr>
<tr id="tr3">
  <td colspan="3" id="dato2"><?php if($row_vermenu['id_menu'] != '') { ?>
    <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
	<table id="tabla6">
  <tr id="tr2">
    <td id="fuente2">&nbsp;</td>
    <td id="fuente2"><?php echo $row_vermenu['nombre_menu']; ?>  <a href="tipo_permisos.php?id_tipo=<?php echo $row_tipo_usuario['id_tipo']; ?>&amp;id_menu=<?php echo $row_vermenu['id_menu']; ?>"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0"/></a></td>
    <td id="fuente2"><a href="tipo_permisos.php?id_tipo=<?php echo $row_tipo_usuario['id_tipo']; ?>">MENU'S</a></td>
  </tr>
  <tr>
    <td id="fuente1">ID</td>
    <td id="fuente1">SUBMENU'S</td>
    <td id="fuente2">&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="detalle1"><?php echo $row_verpermisos['id_registro']; ?></td>
        <td id="detalle1">- <?php echo $row_verpermisos['nombre_submenu']; ?></td>
      <td id="detalle2"><a href="tipo_permisos.php?id_tipo=<?php echo $row_verpermisos['usuario']; ?>&amp;id_menu=<?php echo $row_verpermisos['menu']; ?>&amp;id_submenu=<?php echo $row_verpermisos['submenu']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('id_registro',<?php echo $row_verpermisos['id_registro']; ?>,'tipo_permisos.php')"><img src="images/por.gif" alt="ELIMINAR TIPO DE USUARIO" border="0" style="cursor:hand;"/></a></td>
    </tr>
    <?php } while ($row_verpermisos = mysql_fetch_assoc($verpermisos)); ?>
<?php $id=$row_submenus['id_submenu']; if($id != '') { ?>
<tr>
      <td colspan="3" id="dato1"><input name="id_registro" type="text" size="2" />
        <input name="usuario" type="hidden" value="<?php echo $row_tipo_usuario['id_tipo']; ?>" />
        <input name="menu" type="hidden" value="<?php echo $row_vermenu['id_menu']; ?>" />
        <select name="submenu" id="submenu">
          <?php
do {  
?>
          <option value="<?php echo $row_submenus['id_submenu']?>"><?php echo $row_submenus['nombre_submenu']?></option>
          <?php
} while ($row_submenus = mysql_fetch_assoc($submenus));
  $rows = mysql_num_rows($submenus);
  if($rows > 0) {
      mysql_data_seek($submenus, 0);
	  $row_submenus = mysql_fetch_assoc($submenus);
  }
?>
        </select>        <input name="submit" type="submit" value="ADD ITEM" /></td>
      </tr><?php } ?>
      </table>
      <input type="hidden" name="MM_insert" value="form1">
    </form>	
	<?php } ?></td>
  </tr>
  <?php $permiso=$row_editpermisos['id_registro']; if($permiso != '') { ?>
  <tr id="tr3">
  <td colspan="3" id="dato2"><form method="post" name="form2" action="<?php echo $editFormAction; ?>">
      <table id="tabla6">
        <tr id="tr1">
          <td id="fuente1">EDITE ESTE ITEM</td>
        </tr>
        <tr>
          <td id="dato1"><input name="id_registro" type="text" id="id_registro" value="<?php echo $row_editpermisos['id_registro']; ?>" size="2" />
          <input name="usuario" type="hidden" value="<?php echo $row_editpermisos['usuario']; ?>">
            <input name="menu" type="hidden" id="menu" value="<?php echo $row_editpermisos['menu']; ?>" />
            <select name="submenu" id="submenu">
              <?php
do {  
?>
              <option value="<?php echo $row_editsubmenus['id_submenu']?>"<?php if (!(strcmp($row_editsubmenus['id_submenu'], $row_editpermisos['submenu']))) {echo "selected=\"selected\"";} ?>><?php echo $row_editsubmenus['nombre_submenu']?></option>
              <?php
} while ($row_editsubmenus = mysql_fetch_assoc($editsubmenus));
  $rows = mysql_num_rows($editsubmenus);
  if($rows > 0) {
      mysql_data_seek($editsubmenus, 0);
	  $row_editsubmenus = mysql_fetch_assoc($editsubmenus);
  }
?>
            </select>
            <input name="submit2" type="submit" value="Actualizar" /></td>
        </tr>
      </table>
      <input type="hidden" name="MM_update" value="form2">
  </form></td></tr><?php } ?></table>
</td></tr></table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($submenus);

mysql_free_result($verpermisos);

mysql_free_result($editpermisos);

mysql_free_result($editsubmenus);

mysql_free_result($usuario_admon);
mysql_free_result($tipo_usuario);

mysql_free_result($menus);

mysql_free_result($vermenu);
?>
