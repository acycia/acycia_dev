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
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO egl (n_egl, responsable_egl, codigo_usuario_egl, fecha_egl, hora_egl, estructura_egl, pigm_ext_egl, pigm_int_egl, ancho_egl, calibre_egl, peso_egl, diametro_rollo_egl, tto_corona_egl, tipo_empaque_egl, observ_material_egl, estado_egl) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_egl'], "int"),
                       GetSQLValueString($_POST['responsable_egl'], "text"),
                       GetSQLValueString($_POST['codigo_usuario_egl'], "text"),
                       GetSQLValueString($_POST['fecha_egl'], "date"),
                       GetSQLValueString($_POST['hora_egl'], "text"),
                       GetSQLValueString($_POST['estructura_egl'], "text"),
                       GetSQLValueString($_POST['pigm_ext_egl'], "text"),
                       GetSQLValueString($_POST['pigm_int_egl'], "text"),
                       GetSQLValueString($_POST['ancho_egl'], "double"),
                       GetSQLValueString($_POST['calibre_egl'], "double"),
                       GetSQLValueString($_POST['peso_egl'], "double"),
                       GetSQLValueString($_POST['diametro_rollo_egl'], "double"),
                       GetSQLValueString($_POST['tto_corona_egl'], "text"),
                       GetSQLValueString($_POST['tipo_empaque_egl'], "text"),
                       GetSQLValueString($_POST['observ_material_egl'], "text"),
                       GetSQLValueString($_POST['estado_egl'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "egp_lamina_edit.php?n_egl=" . $_POST['n_egl'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM egl ORDER BY n_egl DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);
?>
<?php date_default_timezone_set("America/Bogota"); ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body oncontextmenu="return false">
<table id="tabla_formato"><tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
      <li><?php echo $row_usuario['nombre_usuario']; ?></li>
      <li><a href="egp_lamina.php">LISTADO EGL</a></li>
      <li><a href="egp_menu.php">MENU EGP</a></li>
      <li><a href="comercial.php">COMERCIAL</a></li>
      <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
      </ul></div></div>
</td></tr></table>
<div align="center">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_egl','','R','hora_egl','','R','responsable_egl','','R','codigo_usuario_egl','','R','estructura_egl','','R','ancho_egl','','RisNum','calibre_egl','','RisNum','peso_egl','','RisNum','diametro_rollo_egl','','RisNum','tto_corona_egl','','R','tipo_empaque_egl','','R');return document.MM_returnValue">
    <table id="tabla_formato2">
      <tr>
        <td id="codigo_formato_2">CODIGO: R1-F08</td>
        <td colspan="2" nowrap id="titulo_formato_2">ESPECIFICACION GENERAL DE LAMINAS (EGL)</td>
        <td id="codigo_formato_2">VERSION: 2</td>
      </tr>
      <tr>
        <td rowspan="6" id="logo_2"><img src="images/logoacyc.jpg"></td>
        <td colspan="2">&nbsp;</td>
        <td id="numero2">Nº<strong>
          <input name="n_egl" type="hidden" value="<?php $num=$row_ultimo['n_egl']+1; echo $num; ?>" />
          <?php echo $num; ?>
          <input name="estado_egl" type="hidden" value="0">
        </strong></td>
      </tr>
      <tr>
        <td id="dato_1">Fecha Registro</td>
        <td id="dato_1">Hora Registro</td>
        <td id="dato_1">&nbsp;</td>
      </tr>
      <tr>
        <td id="dato_1"><input name="fecha_egl" type="text" id="fecha_egl" value="<?php echo date("Y/m/d"); ?>" size="10" readonly="true"></td>
        <td id="dato_1"><input name="hora_egl" type="text" id="hora_egl" value="<?php echo date("g:i a") ?>" size="10" readonly="true"></td>
        <td id="dato_1">&nbsp;</td>
      </tr>
      <tr>
        <td id="dato_1">Responsable</td>
        <td id="dato_1">Usuario</td>
        <td id="dato_1">&nbsp;</td>
      </tr>
      <tr>
        <td id="dato_1"><input name="responsable_egl" type="text" id="responsable_egl" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" readonly="true"></td>
        <td id="dato_1"><input name="codigo_usuario_egl" type="text" id="codigo_usuario_egl" value="<?php echo $row_usuario['codigo_usuario']; ?>" size="10" readonly="true"></td>
        <td id="dato_1">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" id="subtitulo2">ESPECIFICACION DEL MATERIAL</td>
      </tr>
      <tr>
        <td colspan="2" nowrap id="nivel_1">ESTRUCTURA</td>
        <td nowrap id="nivel_1">PIGMENTO EXTERIOR</td>
        <td nowrap id="nivel_1">PIGMENTO INTERIOR</td>
      </tr>
      <tr>
        <td colspan="2" id="dato_1"><select name="estructura_egl">
          <option>Seleccione</option>
          <option value="COEXTRUSION PIGMENTADA">COEXTRUSION PIGMENTADA</option>
          <option value="COEXTRUSION NATURAL">COEXTRUSION NATURAL</option>
          <option value="MONOCAPA PIGMENTADA">MONOCAPA PIGMENTADA</option>
          <option value="MONOCAPA NATURAL">MONOCAPA NATURAL</option>
        </select></td>
        <td id="dato_1"><input name="pigm_ext_egl" type="text" value="" size="20" maxlength="20"></td>
        <td id="dato_1"><input name="pigm_int_egl" type="text" value="" size="20" maxlength="20"></td>
      </tr>
      <tr>
        <td id="nivel_1">ANCHO</td>
        <td id="nivel_1">CALIBRE</td>
        <td id="nivel_1">PESO / ml</td>
        <td id="nivel_1" nowrap>DIAMETRO DEL ROLLO</td>
      </tr>
      <tr>
        <td id="dato_1"><input name="ancho_egl" type="text" id="ancho_egl" value="" size="10" maxlength="8"></td>
        <td id="dato_1"><input name="calibre_egl" type="text" id="calibre_egl" onBlur="calcular_egl()" value="" size="10" maxlength="8"></td>
        <td id="dato_1"><input name="peso_egl" type="text" id="peso_egl" value="" size="10" maxlength="8"></td>
        <td id="dato_1"><input name="diametro_rollo_egl" type="text" id="diametro_rollo_egl" value="" size="10" maxlength="8"></td>
      </tr>
      <tr>
        <td colspan="2" id="nivel_1">TRATAMIENTO CORONA</td>
        <td colspan="2" id="nivel_1">TIPO DE EMPAQUE</td>
      </tr>
      <tr>
        <td colspan="2" id="dato_1"><input name="tto_corona_egl" type="text" id="tto_corona_egl" value="" size="20" maxlength="20"></td>
        <td colspan="2" id="dato_1"><select name="tipo_empaque_egl">
          <option>Seleccione</option>
          <option value="AGUA">AGUA</option>
          <option value="LECHE">LECHE</option>
          <option value="INDUSTRIAL LIQUIDOS">INDUSTRIAL LIQUIDOS</option>
          <option value="INDUSTRIAL A GRANEL">INDUSTRIAL A GRANEL</option>
          <option value="OTROS">OTROS</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="4" id="nivel_1">OBSERVACIONES</td>
      </tr>
      <tr>
        <td colspan="4" id="dato_1"><textarea name="observ_material_egl" cols="80" rows="2"></textarea></td>
      </tr>
      <tr>
        <td colspan="4">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" id="dato_2"><input type="submit" value="SIGUIENTE"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
  </form>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($ultimo);
?>