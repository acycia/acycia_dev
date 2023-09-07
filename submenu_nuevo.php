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
  $insertSQL = sprintf("INSERT INTO submenu (id_submenu, id_menu_submenu, nombre_submenu, url) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_submenu'], "int"),
                       GetSQLValueString($_POST['id_menu_submenu'], "int"),
                       GetSQLValueString($_POST['nombre_submenu'], "text"),
                       GetSQLValueString($_POST['url'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "menu_editar.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = "SELECT * FROM submenu ORDER BY id_submenu DESC";
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);

$colname_ver_menu = "-1";
if (isset($_GET['id_menu'])) {
  $colname_ver_menu = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_menu = sprintf("SELECT * FROM menu WHERE id_menu = %s", $colname_ver_menu);
$ver_menu = mysql_query($query_ver_menu, $conexion1) or die(mysql_error());
$row_ver_menu = mysql_fetch_assoc($ver_menu);
$totalRows_ver_menu = mysql_num_rows($ver_menu);
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
.Estilo60 {font-size: 18}
.Estilo68 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo69 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo71 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo72 {color: #000066}
.Estilo78 {font-family: Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; font-size: 12px; }
.Estilo79 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo80 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
</head>

<body>
<table width="743" height="336" border="2" align="center" cellspacing="3">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="728" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="452" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><span class="Estilo68"><?php echo $row_usuario_admon['nombre_usuario']; ?></span></td>
        <td width="434" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo69">Cerrar Sesi&oacute;n</a> </div></td>
      </tr>
    </table></td>
  </tr>
  <tr bgcolor="#999999">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><span class="Estilo35 Estilo36 Estilo27 Estilo33 Estilo34 Estilo43">ADICIONAR SUBMENU</span></div></td>
  </tr>
  <tr bordercolor="#999999" bgcolor="#CCCCCC">
    <td height="147" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><form method="post" name="form1" action="<?php echo $editFormAction; ?>">
          <br><table width="381" border="1" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <tr valign="baseline">
              <td width="150" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#E8E8E8"><span class="Estilo78">Submenu N&ordm;</span></td>
              <td width="215" bordercolor="#FFFFFF" bgcolor="#E8E8E8"><input type="text" name="id_submenu" value="<?php
$num=$row_ver_nuevo['id_submenu']+1; 
 echo $num; ?>" size="10">
                  <input name="id_menu_submenu" type="hidden" value="<?php echo $row_ver_menu['id_menu']; ?>"></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#E8E8E8"><span class="Estilo78">Nombre del Submenu</span></td>
              <td bordercolor="#FFFFFF" bgcolor="#E8E8E8"><input type="text" name="nombre_submenu" value="" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#E8E8E8"><span class="Estilo78">Pagina</span></td>
              <td bordercolor="#FFFFFF" bgcolor="#E8E8E8"><input type="text" name="url" value="" size="32"></td>
            </tr>
            <tr valign="baseline">
              <td colspan="2" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#E8E8E8"><div align="center"><span class="Estilo60"></span>
                      <input name="submit" type="submit" value="Insertar registro">
              </div></td>
            </tr>
          </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>      </td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="31" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="730" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="120" height="21" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo71 Estilo79">
          <div align="center"><a href="menu.php" class="Estilo72">Menu</a></div>
        </div></td>
          <td width="232" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo80">
            <div align="center"><a href="Administrador.php" class="Estilo72">Administrador</a></div>
          </div></td>
          <td width="207" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo80">
            <div align="center"><a href="menu_editar.php?id_menu=<?php echo $row_ver_nuevo['id_menu_submenu']; ?>" class="Estilo72">Listado Submenu</a></div>
          </div></td>
          <td width="147" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><img src="firma3.bmp"></div></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_admon);

mysql_free_result($ver_nuevo);

mysql_free_result($ver_menu);
?>
