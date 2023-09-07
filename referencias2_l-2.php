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

//BOLSA
mysql_select_db($database_conexion1, $conexion1);
$query_referencianueva =
"SELECT * FROM Tbl_cotiza_bolsa WHERE Tbl_cotiza_bolsa.B_estado = '1' AND Tbl_cotiza_bolsa.B_generica = '0' AND  Tbl_cotiza_bolsa.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)";
$referencianueva = mysql_query($query_referencianueva, $conexion1) or die(mysql_error());
$row_referencianueva = mysql_fetch_assoc($referencianueva);
$totalRows_referencianueva = mysql_num_rows($referencianueva);
//PACKING
mysql_select_db($database_conexion1, $conexion1);
$query_referencianueva2 =
"SELECT * FROM Tbl_cotiza_packing WHERE Tbl_cotiza_packing.B_estado = '1' AND Tbl_cotiza_packing.B_generica = '0' AND Tbl_cotiza_packing.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)";
$referencianueva2 = mysql_query($query_referencianueva2, $conexion1) or die(mysql_error());
$row_referencianueva2 = mysql_fetch_assoc($referencianueva2);
$totalRows_referencianueva2 = mysql_num_rows($referencianueva2);
//LAMINAS
mysql_select_db($database_conexion1, $conexion1);
$query_referencianueva3 =
"SELECT * FROM Tbl_cotiza_laminas WHERE Tbl_cotiza_laminas.B_estado = '1' AND Tbl_cotiza_laminas.B_generica = '0' AND Tbl_cotiza_laminas.N_referencia_c NOT IN(SELECT Tbl_referencia.cod_ref FROM  Tbl_referencia)";
$referencianueva3 = mysql_query($query_referencianueva3, $conexion1) or die(mysql_error());
$row_referencianueva3 = mysql_fetch_assoc($referencianueva3);
$totalRows_referencianueva3 = mysql_num_rows($referencianueva3);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<li><a href="referencia_copia.php">REFERENCIAS</a></li>
<li><a href="referencia_copia.php" target="_top">FILTRO</a></li>
<li><a href="referencias_l.php" target="_top">LAMINAS</a></li>
<li><a href="referencias_p.php" target="_top">PACKING LIST</a></li>
<li><a href="referencias.php" target="_top">BOLSAS</a></li>
<li><a href="referencias_l" target="_top">&nbsp;</a></li>
</ul>
</td>
    </tr>
</table><div id="linea1">
<table id="tabla3">
  <tr id="tr1">
<td id="acceso2"><strong>LISTADO DE REFERENCIAS ACTIVAS</strong><?php if($row_referencianueva3['N_referencia_c'] <> '') { ?> <a href="referencia_nueva1.php" target="_top"><img src="images/falta.gif" alt="REFERENCIAS NUEVAS" title="REFERENCIAS NUEVAS" border="0" style="cursor:hand;"></a><?php } ?>
  <?php $id=$_GET['id']; if($id=='1') { echo "REFERENCIA ELIMINADA"; } ?></td>
<td id="dato3"><a href="referencias_l.php" target="_top"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS"border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php" target="_top"><img src="images/i.gif" alt="REF'S INACTIVAS"title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_l.php" target="_top"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES"border="0" style="cursor:hand;" /></a><a href="verificacion_l.php" target="_top"><img src="images/v.gif" alt="VERIFICACIONES"title="VERIFICACIONES" border="0" style="cursor:hand;" /></a><a href="control_modificaciones_l.php" target="_top"><img src="images/m.gif" alt="MODIFICACIONES"title="MODIFICACIONES" border="0" style="cursor:hand;" /></a><a href="validacion_l.php" target="_top"><img src="images/v.gif" alt="VALIDACIONES" title="VALIDACIONES"border="0" style="cursor:hand;" /></a><a href="ficha_tecnica_l.php" target="_top"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS"border="0" style="cursor:hand;" /></a></td>
  </tr>
</table>
</div>
<table id="tabla3">
  <tr>
    <td class="centrado5">REF</td>
    <td class="centrado5">VERSION</td>
	<td class="centrado5">COTIZ</td>
    <td class="Estilo2">TIPO </td>
	<td class="Estilo1">MATERIAL</td>
    <td class="centrado5">ARTE</td>
    <td class="Estilo2">FECHA ARTE</td>
	<td class="Estilo5">CLIENTES</td>
	<td class="Estilo5">REV</td>
	<td class="Estilo5">VER</td>
	<td class="Estilo5">C.M.</td>
	<td class="Estilo5">VAL</td>
	<td class="Estilo5">FT</td>     
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencianueva);
?>