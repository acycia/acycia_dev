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
?><html>
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
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php" target="_top">MENU PRINCIPAL</a></li>
<li><a href="costos_generales.php" target="_top">COSTOS GENERALES</a></li>
<li><a href="costos_listado.php" target="_top">FILTRO</a></li>
</ul>
</td>
    </tr>
</table>
  <table id="tabla3">
  <tr id="tr1">
<td colspan="19" id="titulo2">
<!--FECHA:
<input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" required value="<?php echo $fecha2;?>" />
<input type="submit" name="submit" id="submit" value="Consultar" />-->
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" >
<input type="button" value="Exportar a Excel" onClick="window.location = 'costos_producto terminado_excel.php?fecha1=<?php echo $fecha1 ?>&amp;fecha2=<?php echo $fecha2 ?>'" /><a href="javascript:location.reload()">
<img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a>
</form></td>
</tr>
 <tr>
    <td colspan="19" id="titulo2">&nbsp;</td>
    </tr>
 
<tr>
     
      <td colspan="5" id="titulo5">EXTRUSION</td>
      <td colspan="4" id="titulo5">IMPRESION</td>
      <td colspan="4" id="titulo5">REFILADO</td>
      <td colspan="7" id="titulo5">SELLADO</td>
    </tr>
    <tr>
    <td class="centrado5">O.P.</td>
    <td class="centrado5">FECHA</td>
	<!--<td class="centrado5">REF.</td>
	<td class="centrado5">CLIENTE</td>
    <td class="centrado5">PRODUCTO</td>
	<td class="centrado5">KILOS PROGRAMADOS</td>
    <td class="centrado5">BOLSAS PROGRAMADAS</td>-->
    <td class="centrado5">KILOS EXTRUIDOS</td> 
    <td class="centrado5">KILOS DESPERDICIO EXTRUSION</td> 
    <td class="centrado5">HORAS DE EXTRUSION</td>
    <td class="centrado5">TIEMPOS PERDIDOS</td> 
    <td class="centrado5">KILOS IMPRESOS</td> 
    <td class="centrado5">KILOS DESPERDICIO IMPRESOS</td> 
    <td class="centrado5">HORAS DE IMPRESOS</td>
    <td class="centrado5">TIEMPOS PERDIDOS</td>  
    <td class="centrado5">KILOS REFILADO</td> 
    <td class="centrado5">KILOS DESPERDICIO REFILADO</td> 
    <td class="centrado5">HORAS DE REFILADO</td>
    <td class="centrado5">TIEMPOS PERDIDOS</td>
    <td class="centrado5">KILOS SELLADO</td>
    <td class="centrado5">KILOS DESPERDICIO SELLADO</td>   
    <td class="centrado5">HORAS DE SELLADO</td>
    <td class="centrado5">TIEMPOS PERDIDOS</td>
    <td class="centrado5">BOLSAS</td>
    <td class="centrado5">RENTABILIDAD</td>
    <td class="centrado5">ESTADO ACTUAL</td> 
    </tr>
  </table>  
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
?>
