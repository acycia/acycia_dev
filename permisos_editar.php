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

  $updateGoTo = "permisos.php";
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

mysql_select_db($database_conexion1, $conexion1);
$query_ver_tipo_user = "SELECT * FROM tipo_user";
$ver_tipo_user = mysql_query($query_ver_tipo_user, $conexion1) or die(mysql_error());
$row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
$totalRows_ver_tipo_user = mysql_num_rows($ver_tipo_user);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_menu = "SELECT * FROM menu ORDER BY id_menu ASC";
$ver_menu = mysql_query($query_ver_menu, $conexion1) or die(mysql_error());
$row_ver_menu = mysql_fetch_assoc($ver_menu);
$totalRows_ver_menu = mysql_num_rows($ver_menu);

$colname_ver_submenu = "1";
if (isset($_GET['menu'])) {
  $colname_ver_submenu = (get_magic_quotes_gpc()) ? $_GET['menu'] : addslashes($_GET['menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_submenu = sprintf("SELECT * FROM submenu WHERE id_menu_submenu = %s ORDER BY id_submenu ASC", $colname_ver_submenu);
$ver_submenu = mysql_query($query_ver_submenu, $conexion1) or die(mysql_error());
$row_ver_submenu = mysql_fetch_assoc($ver_submenu);
$totalRows_ver_submenu = mysql_num_rows($ver_submenu);

$colname_ver_permisos = "1";
if (isset($_GET['id_registro'])) {
  $colname_ver_permisos = (get_magic_quotes_gpc()) ? $_GET['id_registro'] : addslashes($_GET['id_registro']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_permisos = sprintf("SELECT * FROM permisos WHERE id_registro = %s", $colname_ver_permisos);
$ver_permisos = mysql_query($query_ver_permisos, $conexion1) or die(mysql_error());
$row_ver_permisos = mysql_fetch_assoc($ver_permisos);
$totalRows_ver_permisos = mysql_num_rows($ver_permisos);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
<table width="669" border="0" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
<tr>
<td colspan="3"><?php echo $row_ver_permisos['id_registro']; ?></td>
</tr>
<tr>
<td>Tipo Usuario</td>
<td colspan="2">
<select name="usuario"><?php
do {  
?><option value="<?php echo $row_ver_tipo_user['id_tipo']?>"<?php if (!(strcmp($row_ver_tipo_user['id_tipo'], $row_ver_permisos['usuario']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ver_tipo_user['nombre_tipo']?></option>
                <?php
} while ($row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user));
  $rows = mysql_num_rows($ver_tipo_user);
  if($rows > 0) {
      mysql_data_seek($ver_tipo_user, 0);
	  $row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
  }
?>
</select></td>
<tr>
<td>Menu</td>
<td><?php 
$menu=$row_ver_permisos['menu']; 
$sql="select nombre_menu from menu where id_menu='$menu'";
$result=mysql_query($sql);
$menu1=mysql_result($result,0,'nombre_menu');
echo $menu1;
?></td>
<td>
<select name="menu" id="menu" onBlur="consulta1()">
<option value="0" <?php if (!(strcmp(0, $_GET['menu']))) {echo "selected=\"selected\"";} ?>></option><?php do {  
?><option value="<?php echo $row_ver_menu['id_menu']?>"<?php if (!(strcmp($row_ver_menu['id_menu'], $_GET['menu']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ver_menu['nombre_menu']?></option>
                <?php
} while ($row_ver_menu = mysql_fetch_assoc($ver_menu));
  $rows = mysql_num_rows($ver_menu);
  if($rows > 0) {
      mysql_data_seek($ver_menu, 0);
	  $row_ver_menu = mysql_fetch_assoc($ver_menu);
  }
?></select>
</td>
<tr>
<td>Submenu</td>
<td>
<?php 
$submenu=$row_ver_permisos['submenu']; 
$sql2="select nombre_submenu from submenu where id_submenu='$submenu'";
$result2=mysql_query($sql2);
$submenu1=mysql_result($result2,0,'nombre_submenu');
echo $submenu1; ?></td>
<td>
<select name="submenu" id="submenu"><option value="0" <?php if (!(strcmp(0, $row_ver_submenu['nombre_submenu']))) {echo "SELECTED";} ?>></option>
                <?php
do {  
?>
                <option value="<?php echo $row_ver_submenu['id_submenu']?>"<?php if (!(strcmp($row_ver_submenu['id_submenu'], $row_ver_submenu['nombre_submenu']))) {echo "SELECTED";} ?>><?php echo $row_ver_submenu['nombre_submenu']?></option>
                <?php
} while ($row_ver_submenu = mysql_fetch_assoc($ver_submenu));
  $rows = mysql_num_rows($ver_submenu);
  if($rows > 0) {
      mysql_data_seek($ver_submenu, 0);
	  $row_ver_submenu = mysql_fetch_assoc($ver_submenu);
  }
?>
              </select>
			  </td>
			  <tr>
            <td colspan="3"><input type="submit" value="Actualizar registro"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_registro" value="<?php echo $row_ver_permisos['id_registro']; ?>">
      </form>
</body>
</html>
<?php
mysql_free_result($usuario_admon);

mysql_free_result($ver_tipo_user);

mysql_free_result($ver_menu);

mysql_free_result($ver_submenu);

mysql_free_result($ver_permisos);
?>
