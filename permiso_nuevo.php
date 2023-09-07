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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO permisos (id_registro, usuario, submenu, menu) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_registro'], "int"),
                       GetSQLValueString($_POST['usuario'], "text"),
                       GetSQLValueString($_POST['submenu'], "text"),
                       GetSQLValueString($_POST['menu'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
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
$query_ver_nuevo = "SELECT * FROM menu ORDER BY id_menu DESC";
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_tipo_user = "SELECT * FROM tipo_user ORDER BY id_tipo ASC";
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
$query_ver_submenu = sprintf("SELECT * FROM submenu WHERE id_menu_submenu = %s", $colname_ver_submenu);
$ver_submenu = mysql_query($query_ver_submenu, $conexion1) or die(mysql_error());
$row_ver_submenu = mysql_fetch_assoc($ver_submenu);
$totalRows_ver_submenu = mysql_num_rows($ver_submenu);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_permisos = "SELECT * FROM permisos ORDER BY id_registro DESC";
$ver_permisos = mysql_query($query_ver_permisos, $conexion1) or die(mysql_error());
$row_ver_permisos = mysql_fetch_assoc($ver_permisos);
$totalRows_ver_permisos = mysql_num_rows($ver_permisos);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo43 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #000066;
	font-size: 18px;
}
.Estilo57 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo58 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo67 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo68 {color: #000066}
.Estilo72 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #000066; font-size: 12px; }
.Estilo73 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo74 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
<script language="javascript">
<!--
function consulta()
{
window.location ='permiso_nuevo.php?menu='+document.form1.menu.value+'&submenu='+document.form1.submenu.value+'&usuario='+document.form1.usuario.value;
}
//-->
</script>
</head>

<body>
<table width="737" height="413" border="2" align="center" cellspacing="3">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="727" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="452"><span class="Estilo57"><?php echo $row_usuario_admon['nombre_usuario']; ?></span></td>
        <td width="434"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo58">Cerrar Sesi&oacute;n</a> </div></td>
      </tr>
    </table></td>
  </tr>
  <tr bgcolor="#999999">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#999999">
    <td height="29" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><span class="Estilo35 Estilo36 Estilo27 Estilo33 Estilo34 Estilo43">CREAR PERMISO </span></div></td>
  </tr>
  <tr bordercolor="#999999" bgcolor="#CCCCCC">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><form method="post" name="form1" action="<?php echo $editFormAction; ?>">
          <table width="451" border="0" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
              <td width="89" align="right" nowrap bgcolor="#E9E9E9"><span class="Estilo72">N&ordm;</span></td>
              <td width="346" bgcolor="#E9E9E9"><input type="text" name="id_registro" value="<?php
$num=$row_ver_permisos['id_registro']+1; 
 echo $num; ?>" size="10"></td>
            </tr>
            <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
              <td align="right" nowrap bgcolor="#E9E9E9"><span class="Estilo72">Tipo Usuario</span></td>
              <td bgcolor="#E9E9E9"><select name="usuario">
                  <option value="0" <?php if (!(strcmp(0, $_GET['usuario']))) {echo "SELECTED";} ?>></option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_tipo_user['id_tipo']?>"<?php if (!(strcmp($row_ver_tipo_user['id_tipo'], $_GET['usuario']))) {echo "SELECTED";} ?>><?php echo $row_ver_tipo_user['nombre_tipo']?></option>
                  <?php
} while ($row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user));
  $rows = mysql_num_rows($ver_tipo_user);
  if($rows > 0) {
      mysql_data_seek($ver_tipo_user, 0);
	  $row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
  }
?>
                </select>              </td>
            <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
              <td align="right" nowrap bgcolor="#E9E9E9"><span class="Estilo72">Menu</span></td>
              <td bgcolor="#E9E9E9"><select name="menu" id="menu" onBlur="consulta()">
                  <option value="0" <?php if (!(strcmp(0, $_GET['menu']))) {echo "SELECTED";} ?>></option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_menu['id_menu']?>"<?php if (!(strcmp($row_ver_menu['id_menu'], $_GET['menu']))) {echo "SELECTED";} ?>><?php echo $row_ver_menu['nombre_menu']?></option>
                  <?php
} while ($row_ver_menu = mysql_fetch_assoc($ver_menu));
  $rows = mysql_num_rows($ver_menu);
  if($rows > 0) {
      mysql_data_seek($ver_menu, 0);
	  $row_ver_menu = mysql_fetch_assoc($ver_menu);
  }
?>
                </select>              </td>
            <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
              <td align="right" nowrap bgcolor="#E9E9E9"><span class="Estilo72">Submenu</span></td>
              <td bgcolor="#E9E9E9"><select name="submenu" id="submenu" onBlur="consulta()">
                  <option value="0" <?php if (!(strcmp(0, $_GET['submenu']))) {echo "SELECTED";} ?>></option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_submenu['id_submenu']?>"<?php if (!(strcmp($row_ver_submenu['id_submenu'], $_GET['submenu']))) {echo "SELECTED";} ?>><?php echo $row_ver_submenu['nombre_submenu']?></option>
                  <?php
} while ($row_ver_submenu = mysql_fetch_assoc($ver_submenu));
  $rows = mysql_num_rows($ver_submenu);
  if($rows > 0) {
      mysql_data_seek($ver_submenu, 0);
	  $row_ver_submenu = mysql_fetch_assoc($ver_submenu);
  }
?>
                </select>              </td>
            <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
              <td colspan="2" align="right" nowrap bgcolor="#E9E9E9"><div align="center">
                  <input name="submit" type="submit" value="Insertar registro">
              </div></td>
            </tr>
          </table>
          <input type="hidden" name="MM_insert" value="form1">
      </form>
    <p>&nbsp;</p></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="31" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="729" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="143" height="21" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo67 Estilo73">
          <div align="center"><a href="menu.php" class="Estilo68">Menu</a></div>
        </div></td>
          <td width="205" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo74">
            <div align="center"><a href="Administrador.php" class="Estilo68">Administrador</a></div>
          </div></td>
          <td width="212" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo74">
            <div align="center"><a href="permisos.php" class="Estilo68">Listado de Permisos </a></div>
          </div></td>
          <td width="146" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><img src="firma3.bmp"></div></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_admon);

mysql_free_result($ver_nuevo);

mysql_free_result($ver_tipo_user);

mysql_free_result($ver_menu);

mysql_free_result($ver_submenu);

mysql_free_result($ver_permisos);
?>
