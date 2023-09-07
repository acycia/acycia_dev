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
//INSERT
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
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
	<li><a href="produccion_mezclas_add.php">EXTRUSION</a></li>		
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="10" id="titulo2">CARACTERISTICAS DE EXTRUSION </td>
        </tr>
      <tr>
        <td width="137" colspan="3" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="7" id="dato3"><a href="vista.php?id_ref=<?php echo $row['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
      <tr id="tr1">
        <td width="182" colspan="3" nowrap="nowrap" id="fuente1">Fecha Ingreso 
          <input name="fecha_registro_pce" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus />
<!--    atributos para autofocus     min y max

Como su propio nombre indica, este par de atributos permiten establecer un límite inferior y superior para los valores que se pueden introducir en un campo de formulario numérico, como los tipos de entrada numéricos, de rango, fecha y hora (sí, hasta se pueden utilizar para establecer los límites superior e inferior para las fechas: por ejemplo, en un formulario de reserva de viajes podrías limitar el selector de fechas para que sólo permita al usuario seleccionar fechas futuras). Para entradas range, de hecho min y max son necesarios para definir los valores que se devuelven cuando se envía el formulario. El código es bastante simple e intuitivo:

<input type="number" … min="1" max="10">
step

El atributo step se puede utilizar con un valor de entrada numérico para dictar la granularidad de los valores que se pueden introducir. Por ejemplo, es posible que desees que los usuarios introduzcan una hora determinada, pero sólo en incrementos de 30 minutos. En este caso, podemos usar el atributo step, teniendo en cuenta que para entradas time el valor del atributo está en segundos:

<input type="time" … step="1800">-->
          </td>
        <td colspan="4" id="fuente1">
Ingresado por
  <input name="registro_pce" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/>
  <input type="hidden" name="id_pce" id="id_pce" value="<?php echo $row['id_pce']+1; ?>"/></td>
        </tr>
      <tr id="tr3">
        <td colspan="3" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="235" colspan="2" id="fuente2">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td colspan="3" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td colspan="2" id="fuente2"><output  onforminput="value=weight.value"></output></td>
        <td colspan="2" id="dato2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td colspan="2" id="fuente2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2"><datalist id="dias"></datalist></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="titulo4">EXTRUSION</td>
        </tr>
      <tr id="tr1">
        <td colspan="3" id="fuente1">Opcion No 1</td>
        <td colspan="2" id="fuente1">Opcion No 2</td>
        <td colspan="5" id="fuente2">Calibre</td>
        </tr>
      <tr>
        <td id="fuente1">Boquilla de Extrusion</td>
        <td colspan="2" id="fuente1"><input name="boquilla1_pce"  type="text" placeholder="Boquilla" required="required" size="10"/></td>
        <td id="fuente1">Boquilla de Extrusi&oacute;n</td>
        <td id="fuente1"><input name="boquilla2_pce"  type="text" placeholder="Boquilla" required="required" size="10"/></td>
        <td colspan="4" id="fuente1">Mil&eacute;simas</td>
        <td id="fuente1">Micras</td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">Relacion Soplado    (RS)</td>
        <td colspan="2" id="fuente1"><input name="relacion_sopl1_pce"  type="text" placeholder="Relacion Soplado" required="required" size="10"/></td>
        <td id="fuente1">Relaci&oacute;n Soplado (RS)</td>
        <td id="fuente1"><input name="relacion_sopl2_pce"  type="text" placeholder="Relacion Soplado" required="required" size="10"/></td>
        <td colspan="4" id="fuente1"><input name="milesima_pce"  type="text" placeholder="Milesimas" required="required" size="10"/></td>
        <td id="fuente1"><input name="micras_pce"  type="text" placeholder="Micras" required="required" size="10"/></td>
      </tr>
      <tr>
        <td id="fuente1">Altura Linea    Enfriamiento</td>
        <td colspan="2" id="fuente1"><input name="altura_linea_pce"  type="text" placeholder="Altura Linea" required="required" size="10"/></td>
        <td id="fuente1">Altura Linea Enfriamiento</td>
        <td id="fuente1"><input name="altura_linea2_pce"  type="text" placeholder="Altura Linea" required="required" size="10"/></td>
        <td colspan="5" id="fuente1">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td rowspan="2" id="fuente1">Velocidad de Halado</td>
        <td colspan="2" id="fuente1">Tratamiento Corona</td>
        <td colspan="5" id="fuente1">Ubicaci&oacute;n Tratamiento</td>
        <td colspan="2" id="fuente1">Pigmentaci&oacute;n</td>
        </tr>
<tr>
  <td id="fuente1">Potencia</td>
  <td id="fuente1"><input name="potencia_pce"  type="text" placeholder="Potencia" required="required" size="5"/></td>
        <td colspan="2" id="fuente1">Cara Interior</td>
        <td colspan="3" id="fuente1"><input name="cara_int_pce"  type="text" placeholder="Cara Interior" required="required" size="5"/></td>
        <td id="fuente1">Exterior</td>
        <td id="fuente1"><input name="pig_int_pce"  type="text" placeholder="Pig Interior" required="required" size="10"/></td>
      </tr>
<tr>
        <td id="fuente1"><input name="velocidad_hel_pce"  type="text" placeholder="Velocidad Helado" required="required" size="10"/></td>
        <td id="fuente1">Dinas</td>
        <td id="fuente1"><input name="dinas_pce"  type="text" placeholder="Dinas" required="required" size="5"/></td>
        <td colspan="2" id="fuente1">Cara Exterior</td>
        <td colspan="3" id="fuente1"><input name="cara_ext_pce"  type="text" placeholder="Cara Exterior" required="required" size="5"/></td>
        <td id="fuente1">Interior</td>
        <td id="fuente1"><input name="pig_ext_pce"  type="text" placeholder="Pig Exterior" required="required" size="10"/></td>
      </tr>
<tr id="tr1">
        <td rowspan="2" id="fuente1">% Aire Anillo Enfriamiento</td>
        <td colspan="3" id="fuente2">Tension</td>
        <td colspan="6" id="fuente1">&nbsp;</td>
        </tr>                  
<tr id="tr1">
  <td id="fuente1">Sec Take Off</td>
  <td id="fuente1">Winder A</td>
        <td id="fuente1">Winder B</td>
        <td colspan="6" id="fuente1">Nota:</td>
        </tr> 
<tr>
        <td id="fuente1"><input name="aire_ani_pce"  type="text" placeholder="Aire Anillo" required="required" size="10"/></td>
        <td id="fuente1"><input name="tension_sec_pce"  type="text" placeholder="Sec Take" required="required" size="5"/></td>
        <td id="fuente1"><input name="tension_wina_pce"  type="text" placeholder="Winder A" required="required" size="5"/></td>
        <td id="fuente1"><input name="tension_winb_pce"  type="text" placeholder="Winder B" required="required" size="5"/></td>
        <td colspan="6" id="fuente1">Favor entregar al proceso    siguiente el material debidamente identificado seg&uacute;n el documento    correspondiente para cada rollo de material.</td>
        </tr>           
      <tr>
        <td colspan="10" id="fuente1">&nbsp;</td>
      </tr>
<tr id="tr1">
        <td colspan="10" id="titulo4">TEMPERATURAS DE FUNDIDO EN TORNILLOS Y CABEZAL</td>
        </tr>
      <tr id="tr1">
        <td colspan="2"id="fuente1">&nbsp;</td>
        <td colspan="2"id="fuente1">TORNILLO A</td>
        <td colspan="2"id="fuente1">TORNILLO B</td>
        <td colspan="2"id="fuente1">TORNILLO C</td>
        <td colspan="1" id="fuente1">Cabezal (Die Head)</td>
        <td colspan="1" id="fuente1">&deg;C</td>
      </tr>
      <tr>
        <td colspan="2"id="fuente1">Barrel Zone 1</td>
        <td colspan="2"id="fuente1"><input name="tor1_bz1_pce"  type="text" placeholder="Tor A" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_bz1_pce"  type="text" placeholder="Tor B" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor3_bz1_pce"  type="text" placeholder="Tor C" required="required" size="10"/></td>
        <td colspan="1" id="fuente1">Share Lower</td>
        <td colspan="1" id="fuente1"><input name="cabezal_sl_pce"  type="text" placeholder="Share Lower" required="required" size="5"/></td>
      </tr>
      <tr id="tr1">
        <td colspan="2"id="fuente1">Barrel Zone 2</td>
        <td colspan="2"id="fuente1"><input name="tor1_bz2_pce"  type="text" placeholder="Tor A" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_bz2_pce"  type="text" placeholder="Tor B" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_bz2_pce"  type="text" placeholder="Tor C" required="required" size="10"/></td>
        <td colspan="1" id="fuente1">Share Upper</td>
        <td colspan="1" id="fuente1"><input name="cabezal_su_pce"  type="text" placeholder="Share Upper" required="required" size="5"/></td>
      </tr>
      <tr>
        <td colspan="2"id="fuente1">Barrel Zone 3</td>
        <td colspan="2"id="fuente1"><input name="tor1_bz3_pce"  type="text" placeholder="Tor A" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_bz3_pce"  type="text" placeholder="Tor B" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor3_bz3_pce"  type="text" placeholder="Tor C" required="required" size="10"/></td>
        <td colspan="1" id="fuente1">L-Die</td>
        <td colspan="1" id="fuente1"><input name="cabezal_ld_pce"  type="text" placeholder="L-Die" required="required" size="5"/></td>
      </tr>
      <tr id="tr1">
        <td colspan="2"id="fuente1">Barrel Zone 4</td>
        <td colspan="2"id="fuente1"><input name="tor1_bz4_pce"  type="text" placeholder="Tor A" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_bz4_pce"  type="text" placeholder="Tor B" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor4_bz4_pce"  type="text" placeholder="Tor C" required="required" size="10"/></td>
        <td colspan="1" id="fuente1">V- Die</td>
        <td colspan="1" id="fuente1"><input name="cabezal_vd_pce"  type="text" placeholder="V- Die" required="required" size="5"/></td>
      </tr>
      <tr>
        <td colspan="2"id="fuente1">Filter Front</td>
        <td colspan="2"id="fuente1"><input name="tor1_ff1_pce"  type="text" placeholder="Tor A" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_ff1_pce"  type="text" placeholder="Tor B" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor3_ff1_pce"  type="text" placeholder="Tor C" required="required" size="10"/></td>
        <td colspan="1" id="fuente1">Die Head</td>
        <td colspan="1" id="fuente1"><input name="cabezal_dh_pce"  type="text" placeholder="Die Head" required="required" size="5"/></td>
      </tr>
      <tr id="tr1">
        <td colspan="2"id="fuente1">Filter Back</td>
        <td colspan="2"id="fuente1"><input name="tor1_ff2_pce"  type="text" placeholder="Tor A" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_ff2_pce"  type="text" placeholder="Tor B" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor3_ff2_pce"  type="text" placeholder="Tor C" required="required" size="10"/></td>
        <td colspan="1" id="fuente1">Die Lid</td>
        <td colspan="1" id="fuente1"><input name="cabezal_dl_pce"  type="text" placeholder="Die Lid" required="required" size="5"/></td>
      </tr>
      <tr>
        <td colspan="2"id="fuente1">Sec- Barrel</td>
        <td colspan="2"id="fuente1"><input name="tor1_sb_pce"  type="text" placeholder="Tor A" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_sb_pce"  type="text" placeholder="Tor B" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor3_sb_pce"  type="text" placeholder="Tor C" required="required" size="10"/></td>
        <td colspan="1" id="fuente1">Die Center Lower</td>
        <td colspan="1" id="fuente1"><input name="cabezal_dcl_pce"  type="text" placeholder="Die Center Lower" required="required" size="5"/></td>
      </tr>
      <tr id="tr1">
        <td colspan="2"id="fuente1">Melt Temp &deg;C</td>
        <td colspan="2"id="fuente1"><input name="tor1_mt_pce"  type="text" placeholder="Tor A" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor2_mt_pce"  type="text" placeholder="Tor B" required="required" size="10"/></td>
        <td colspan="2"id="fuente1"><input name="tor3_mt_pce"  type="text" placeholder="Tor C" required="required" size="10"/></td>
        <td colspan="1" id="fuente1">Die Center Upper</td>
        <td colspan="1" id="fuente1"><input name="cabezal_dcu_pce"  type="text" placeholder="Die Center Upper" required="required" size="5"/></td>
      </tr>
      <tr>
        <td colspan="10"id="fuente1">Estos son valores de referencia que pueden cambiar de acuerdo    a velocidad, temperatura ambiente, calibre, etc.</td>
        </tr>
      <tr id="tr1">
        <td colspan="10"id="fuente1">&nbsp;</td>
        </tr>                              
      <tr>
        <td colspan="10" id="fuente1">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="10" id="fuente2">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="10" id="fuente2"><textarea name="observ_pce" id="observ_pce" cols="45" rows="5"placeholder="OBSERVACIONES"></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="fuente2"><input type="submit" name="GUARDAR" id="GUARDAR" value="GUARDAR" /></td>
      </tr>
    </table>
    </form>
  </td></tr></table>
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
?>
