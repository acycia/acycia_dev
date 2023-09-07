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

}
//INSERT
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
</script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>    
<script type="text/javascript" src="js/jquery-barcode-last.min.js"></script> 
<script type="text/javascript">
/*$(document).ready(function(){
    $("#bcTarget").barcode("1234567890128", "ean13"); 
});*/
</script>
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
        <td colspan="4" id="titulo2">&nbsp;</td>
        </tr>
      <tr>
        <td width="137" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="3" id="dato3"><a href="vista.php?id_ref=<?php echo $row['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
      <tr id="tr1">
        <td width="182" nowrap="nowrap" id="dato2">Fecha Ingreso 
          <input name="fecha_registro" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus />
<!--    atributos para autofocus     min y max

Como su propio nombre indica, este par de atributos permiten establecer un límite inferior y superior para los valores que se pueden introducir en un campo de formulario numérico, como los tipos de entrada numéricos, de rango, fecha y hora (sí, hasta se pueden utilizar para establecer los límites superior e inferior para las fechas: por ejemplo, en un formulario de reserva de viajes podrías limitar el selector de fechas para que sólo permita al usuario seleccionar fechas futuras). Para entradas range, de hecho min y max son necesarios para definir los valores que se devuelven cuando se envía el formulario. El código es bastante simple e intuitivo:

<input type="number" … min="1" max="10">
step

El atributo step se puede utilizar con un valor de entrada numérico para dictar la granularidad de los valores que se pueden introducir. Por ejemplo, es posible que desees que los usuarios introduzcan una hora determinada, pero sólo en incrementos de 30 minutos. En este caso, podemos usar el atributo step, teniendo en cuenta que para entradas time el valor del atributo está en segundos:

<input type="time" … step="1800">-->
          </td>
        <td colspan="2" id="dato3">
          <input type="hidden" name="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario'] ?>" />
          Ingresado por
<input name="registro" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
        </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2">n&uacute;meros</td>
        <td width="126" nowrap="nowrap" id="fuente2">control deslizante</td>
        <td width="235" id="fuente2">tiempo</td>
      </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><label for="numero">¿Cuanto?</label>
<input id="numero" name="numero" type="number" min="5" max="25" /></td>
        <td id="fuente2"><input type="range" ><output  onforminput="value=weight.value"></output></td>
        <td id="dato2">
<input type="time" ></td>
      </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="fuente2">color</td>
        <td nowrap="nowrap" id="fuente2"><label for="busqueda2">B&uacute;squeda con hist&oacute;rico</label></td>
        <td id="fuente2">lista e input</td>
      </tr>
      <tr>
        <td id="dato2"><input type="color"></td>
        <td id="dato2"><input type="search" name="busqueda2" id="busqueda2" results="5"/></td>
        
        <td id="dato2"><label for="diasemana">¿Que día de la semana es hoy?</label>
<input type="text" name="diasemana" id="diasemana" list="dias"/>
    <datalist id="dias">
        <option value="Lunes" />
        <option value="Martes" />
        <option value="Miércoles" />
        <option value="Jueves" />
        <option value="Viernes" />
        <option value="Sábado" />
        <option value="Domingo" />
    </datalist></td>
      </tr>
      <tr id="tr1">
        <td colspan="4" id="titulo4">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">RANGO
          <!--<input type="range" id="rangeexample" … >  <output onforminput="value=rangeexample.value" 
for="rangeexample"></output>-->

<input type="range" min="0" max="10" value="3" step="1" id="mislider" list="lista">
<datalist id="lista">
 <option value="0" label="0">
 <option value="2" label="2">
 <option value="4" label="4">
 <option value="6" label="6">
 <option value="8" label="8">
 <option value="10" label="10">
</datalist></td>
        <td id="fuente1">INFORMACION CAMPO
          <input  type="text" placeholder="Nombre xpp" /></td>
        <td id="fuente1">PROGRESO <progress> y <meter></td>
        <td id="fuente1">CAMPO OBLIGATORIO
          <input type="text"  required /></td>
      </tr>
      <tr>
        <td id="dato1"></td>
        <td id="dato1"></td>
        <td id="dato1"></td>
        <td id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">ESPECIFICO [a-z]{3}[0-9]{3}
          <input type="text" pattern="[a-z]{3}[0-9]{3}" /></td>
        <td id="fuente1">E-mail: <input type="email" name="email"></td>
        <td id="fuente1">Telephone: <input type="tel" name="usrtel"></td>
        <td id="fuente1">Select una semana:
          <input type="week" name="week_year" /></td>
      </tr>
      <tr>
        <td id="dato1"></td>
        <td id="dato1"></td>
        <td id="dato1"></td>
        <td id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><label for="correo">Correo electr&oacute;nico</label></td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1"><input id="correo" name="correo" type="email" />
          <label for="website">Sitio web</label>
          <input id="website" name="website" type="url" />
          <label for="telefono">N&uacute;mero de tel&eacute;fono</label>
          <input id="telefono" name="telefono" type="tel" /></td>
        <td id="fuente1"><div id="bcTarget"></div>   <input type="button" onclick='$("#bcTarget").barcode("1234567890128", "ean13",{barWidth:2, barHeight:30});' value="ean13"> </td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1"></td>
        <td id="fuente1"></td>
        <td id="fuente1"></td>
        <td id="fuente1"></td>
      </tr>
      <tr id="tr1">
        <td colspan="4" id="fuente1">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="4" id="dato1">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="4" id="dato2"><input type="submit" name="ENVIAR" id="ENVIAR" value="ENVIAR" /></td>
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
