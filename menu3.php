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
$query_ver_menu = "SELECT * FROM menu";
$ver_menu = mysql_query($query_ver_menu, $conexion1) or die(mysql_error());
$row_ver_menu = mysql_fetch_assoc($ver_menu);
$totalRows_ver_menu = mysql_num_rows($ver_menu);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="css/listado.css" type="text/css">
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table id="tabla1">
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
    <td class="Estilo9"><a href="menu_nuevo2.php?id_menu= <?php echo $row_ver_menu['id_menu']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_menu['id_menu']; ?></a></td>
    <td class="Estilo7"><a href="menu_nuevo2.php?id_menu= <?php echo $row_ver_menu['id_menu']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_menu['nombre_menu']; ?></a></td>
    <td class="Estilo10"><a href="menu_nuevo2.php?id_menu= <?php echo $row_ver_menu['id_menu']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_menu['url']; ?></a></td>
	<td class="Estilo9"><a href="menu_nuevo2.php?id_menu= <?php echo $row_ver_menu['id_menu']; ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/identico.gif" style="cursor:hand;" alt="SUBMENU'S" border="0"></a></td>
  </tr>
  <?php } while ($row_ver_menu = mysql_fetch_assoc($ver_menu)); ?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario_admon);
mysql_free_result($ver_menu);
?>
