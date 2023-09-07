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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_bolsas = "SELECT * FROM material_terminado_bolsas ORDER BY nombre_bolsa ASC";
$bolsas = mysql_query($query_bolsas, $conexion1) or die(mysql_error());
$row_bolsas = mysql_fetch_assoc($bolsas);
$totalRows_bolsas = mysql_num_rows($bolsas);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
</head>
<body>
<div align="center">
<table id="tabla4"><tr><td colspan="2" id="cabecera"><img src="images/cabecera.jpg"></td>
  </tr>
  <tr>
    <td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
    <td id="cabezamenu">
<ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
<li><a href="menu.php" target="_top">MENU PRINCIPAL</a></li>
<li><a href="compras.php" target="_top">GESTION COMPRAS</a></li>
<li><a href="bolsas_busqueda.php" target="_top">FILTRO</a></li>
</ul>
</td>
    </tr>
</table><div id="linea1">
<table id="tabla3">
  <tr id="tr1">
<td id="subtitulo">PRODUCTO TERMINADO  ( BOLSAS )</td>
<td id="dato2"><a href="bolsas_add.php" target="_top"><img src="images/mas.gif" alt="ADD BOLSA" border="0" style="cursor:hand;"/></a><a href="bolsas_oc.php" target="_top"><img src="images/o.gif" alt="O.C.(BOLSAS)" border="0" style="cursor:hand;"/></a><a href="bolsas_verificaciones.php" target="_top"><img src="images/v.gif" alt="VERIFICACIONES(BOLSAS)" border="0" style="cursor:hand;" /></a></td>
  </tr>
</table>
</div>
<table id="tabla3">
  <tr>
    <td height="19" class="centrado2">CODIGO</td>
    <td class="centrado2">NOMBRE</td>
    <td class="textocentrado">DESCRIPCION</td>
    <td class="centrado2">REFERENCIA</td>
    <td class="centrado2">MEDIDA</td>
    <td class="textocentrado">OBSERVACION</td>
    </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($bolsas);
?>
