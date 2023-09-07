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
/*if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
    $updateGoTo = "cotizacion_general_materia_prima_ref.php?id_mp_vta=" . $_POST["Str_referencia_m"];
  header(sprintf("Location: %s", $updateGoTo));
}*/
?>
<?php
include('rud_cotizaciones/rud_cotizacion_materia_p.php');//SISTEMA RUW PARA LA BASE DE DATOS 
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

/*mysql_select_db($database_conexion1, $conexion1);
$query_egp = "SELECT * FROM egp ORDER BY n_egp DESC";
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);*/

mysql_select_db($database_conexion1, $conexion1);
$query_verlinc = "SELECT * FROM Tbl_mp_vta ORDER BY id_mp_vta";
$verlinc = mysql_query($query_verlinc, $conexion1) or die(mysql_error());
$row_verlinc = mysql_fetch_assoc($verlinc);
$totalRows_verlinc = mysql_num_rows($verlinc);

mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM Tbl_cotiza_materia_p ORDER BY N_cotizacion DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);

mysql_select_db($database_conexion1, $conexion1);
$query_ref= "SELECT * FROM Tbl_cliente_referencia ORDER BY N_referencia DESC";
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/adjuntos.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="comercial.php">GESTION COMERCIAL</a></li>
</ul>
  </td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="cotizacion_general_materia_prima_ref.php" method="post" enctype="multipart/form-data" name="form1" onsubmit="MM_validateForm('Str_referencia_m','','R');return document.MM_returnValue"><table id="tabla2">
      <tr id="tr1">
        <td width="181" nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
        <td width="228" nowrap="nowrap" id="titulo2">Referencia  Materia Prima</td>
        <td width="275" nowrap="nowrap" id="codigo">VERSION: 2 </td>
      </tr>
      <tr>
        <td rowspan="9" id="fuente2"><img src="images/logoacyc.jpg"></td>
        <td id="numero2">&nbsp;</td>
        <td id="fuente2"><a href="egp_bolsa.php"><img src="images/a.gif" style="cursor:hand;" alt="EGP'S ACTIVAS" border="0" /></a><a href="egp_bolsa_obsoletos.php"><img src="images/i.gif" style="cursor:hand;" alt="EGP'S INACTIVAS" border="0" /></a><a href="egp_bolsa.php"><img src="images/opciones.gif" style="cursor:hand;" alt="MENU EGP'S" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
      </tr>
      <tr>
        <td id="titulo2">&nbsp;</td>
        <td id="numero1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">Fecha  Ingreso</td>
        <td id="fuente1">Hora Ingreso</td>
      </tr>
      <tr>
        <td id="fuente1"><input name="fecha_m" type="text" id="fecha_m" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
        <td id="fuente1"><input name="hora_m" type="text" id="hora_m" value="<?php echo date("g:i a") ?>" size="10" readonly="true" /></td>
      </tr>
      <tr>
        <td id="fuente1">Ingresado por</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1"><input name="Str_usuario" type="text" id="Str_usuario" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" readonly="true" /></td>
        <td id="dato4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">&nbsp;</td>
        </tr>
      <tr>
        <td id="dato1"><a href="cotizacion_general_menu.php">Regresar&lt;&lt;&lt;</a></td>
        <td id="dato1">Crear Cliente <a href="perfil_cliente_add.php" target="_self">Aqu&iacute;</a></td>
        </tr>      
      <tr id="tr1">
        <td colspan="3" id="titulo2">REFERENCIA  MATERIA PRIMA</td>
      </tr>
      <tr id="tr1">
        <td colspan="4" id="titulo1">REFERENCIAS</td>
        </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">Nombre de la Referencia</td>
        <td id="fuente1"> Adjuntar Archivo</td>
        <td id="fuente1"></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="Str_nombre_r" type="text" id="Str_nombre_r" size="28" maxlength="70"onKeyUp="conMayusculas(this)"/></td>
        <td colspan="2" id="fuente1"><input type="file" name="Str_archivo_r" id="Str_archivo_r" /></td>
        </tr>
      <tr>
        <td id="fuente4">&nbsp;</td>
        <td id="fuente4">&nbsp;</td>
        <td id="fuente4">      
        </td>
      </tr>
      <tr>
        <td colspan="3" id="dato1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" id="fuente2"><input name="valor" type="hidden" value="3" />          <input name="submit" type="submit" onclick="MM_validateForm('Str_nombre_r','','R');return document.MM_returnValue"value="GUARDAR" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
  </form></td>
  </tr>
</table>
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
mysql_free_result($usuario);
mysql_free_result($cliente);
mysql_free_result($vendedores);
mysql_free_result($cotizacion);
mysql_free_result($ref);
?>
