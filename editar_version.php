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
	
  $logoutGoTo = "menu.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php require_once('Connections/conexion1.php'); ?><?php
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
$colname_usuario_perfil_cliente = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_perfil_cliente = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_perfil_cliente = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_perfil_cliente);
$usuario_perfil_cliente = mysql_query($query_usuario_perfil_cliente, $conexion1) or die(mysql_error());
$row_usuario_perfil_cliente = mysql_fetch_assoc($usuario_perfil_cliente);
$totalRows_usuario_perfil_cliente = mysql_num_rows($usuario_perfil_cliente);

$colname_ver_version = "1";
if (isset($_GET['id_version'])) {
  $colname_ver_version = (get_magic_quotes_gpc()) ? $_GET['id_version'] : addslashes($_GET['id_version']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_version = sprintf("SELECT * FROM version WHERE id_registro_version = %s", $colname_ver_version);
$ver_version = mysql_query($query_ver_version, $conexion1) or die(mysql_error());
$row_ver_version = mysql_fetch_assoc($ver_version);
$totalRows_ver_version = mysql_num_rows($ver_version);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo7 {font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
	font-style: italic;
	color: #000066;
}
.Estilo14 {
	font-size: 16px;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
	color: #000099;
}
.Estilo17 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000099;
	font-weight: bold;
}
-->
</style>
<script language="JavaScript1.2" type="text/javascript" src="mm_css_menu.js"></script>
 <link rel="stylesheet" type="text/css" media="all" href="librerias/jscalendar-1.0/calendar-win2k-cold-1.css" title="win2k-cold-1" />

  <!-- main calendar program -->
  <script type="text/javascript" src="librerias/jscalendar-1.0/calendar.js"></script>

  <!-- language for the calendar -->
  <script type="text/javascript" src="librerias/jscalendar-1.0/lang/calendar-en.js"></script>

  <!-- the following script defines the Calendar.setup helper function, which makes
       adding a calendar a matter of 1 or 2 lines of code. -->
  <script type="text/javascript" src="librerias/jscalendar-1.0/calendar-setup.js"></script>
  <style type="text/css">
<!--
.Estilo1 {	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
	font-weight: bold;
}
.Estilo3 {
	color: #000066;
	font-weight: bold;
	font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS";
}
.Estilo43 {
	font-size: 14px;
	font-weight: bold;
	font-family: Georgia, "Times New Roman", Times, serif;
}
.Estilo44 {font-size: 14px}
.Estilo45 {
	font-family: Georgia, "Times New Roman", Times, serif;
	color: #000066;
}
.Estilo47 {color: #000066}
.Estilo48 {
	font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS";
	font-weight: bold;
}
-->
  </style>
<script language="javascript">
function llamar()
{
window.location='javascript:history.back()'
}

</script>
</head>

<body>
<table width="925" border="5" align="center" cellspacing="3">
  <tr bgcolor="#CCCCCC">
    <td width="188" height="100"><a href="menu.php"><img name="logo_acyc" src="logo_acyc.gif" width="188" height="103" border="0" id="index_r1_c1" alt="" /></a></td>
    <td width="720"><a href="menu.php"><img name="index_r1_c2" src="index_r1_c2.gif" width="707" height="103" border="0" id="index_r1_c2" alt="" /></a></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td colspan="2"><table width="907" border="1" cellspacing="3">
      <tr>
        <td width="454"><span class="Estilo14 Estilo44"><?php echo $row_usuario_perfil_cliente['nombre_usuario']; ?></span></td>
        <td width="434"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo43">Cerrar Sesion</a></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bgcolor="#999999">
    <td colspan="2">&nbsp;
      <p>&nbsp;</p></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td colspan="2"><div align="center" class="Estilo7">M@rcsoft</div></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_perfil_cliente);

mysql_free_result($ver_version);
?>
