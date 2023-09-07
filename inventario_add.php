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

//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN


$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/inventario_ajax.js"></script> 
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<div align="center">
<table id="tabla4" align="center">
<tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1" align="center"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario_admon['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
            <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
			<li><a href="menu.php">MENU PRINCIPAL</a></li>
            <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
</ul></td>
</tr>
<tr>
	<td align="center" colspan="2" id="linea1">
	<form action="" method="POST" enctype="multipart/form-data" name="form1" onsubmit="enviarDatosEmpleado(); return false">
	  <table id="tabla2">
	  <tr>
	    <td colspan="2" id="fuente1">
	      <table id="tabla3">
	        <tr id="tr1">
	          <td nowrap="nowrap" id="codigo">CODIGO : R1-F08 </td>
	          <td colspan="5" nowrap="nowrap" id="titulo2">ADICIONAR MP Y REF AL INVENTARIO </td>
	          </tr>
	        <tr>
	          <td rowspan="11" id="dato2"><img src="images/logoacyc.jpg" /></td>
	          <td colspan="5" id="dato3"><a href="inventario.php"><img src="images/opciones.gif" alt="LISTADO INVENTARIOS" title="ENTRADAS MATERIAS PRIMA" border="0" style="cursor:hand;" /></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" title="INSUMOS" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" border="0" onclick="window.history.go()" /></td>
	          </tr>
	        <tr id="tr1">
	          <td colspan="2" id="fuente1">Fecha  Ingreso</td>
	          <td colspan="3" id="fuente1">Responsable</td>
	          </tr>
	        <tr>
	          <td colspan="2" id="fuente1"><input name="Fecha" id="Fecha" type="date" required="required" min="2014-10-01" value="<?php echo date ( 'Y-m-d' ); ?>" style="width:150px"/>	</td>
	          <td colspan="3" id="fuente1"><input name="Responsable" type="text" id="Responsable" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" readonly="readonly" /></td>
	          </tr>
	        <tr id="tr1">
	          <td colspan="5" id="fuente2">TIPO PRODUCTO</td>
	          </tr>
	        <tr>
	          <td colspan="5" id="fuente1"><?php generaRef(); ?></td>
	          </tr>
	        <tr id="tr1">
	          <td colspan="5" id="fuente2">SELECCIONE MATERIA PRIMA</td>
	          </tr>
	        <tr>
	          <td colspan="5" id="fuente1"><div style="width:300px;">
	            <select disabled="disabled" name="codigo_inv" id="codigo_inv" style="width:300px" >
	              <option value="0">Selecciona opci&oacute;n...</option>
	              </select>
	            </div></td>
	          </tr>
	        <tr>
	          <td colspan="6" id="fuente2">&nbsp;</td>
	          </tr>
	        <tr id="tr1">
	          <td id="titulo1">INICIAL</td>
	          <td id="titulo1">ENTRADA</td>
	          <td id="titulo1">VALOR MEDIDA</td>
	          <td id="titulo1">ACEPTADAS</td>
	          <td id="titulo1">GUARDA</td>
	          </tr>
	        <tr>
	          <td id="dato1"><input name="SaldoFinal" type="number" id="SaldoFinal" min="0" step="0.01" style="width:70px" required="required" value="0.00" onchange="DatosGestiones3('8','cod_ref',form1.codigo_inv.value,'&tipo',form1.tipo_inv.value);"/>
              </td>
	          <td id="dato1"><input name="Entrada" type="number" id="Entrada" min="0" step="0.01" style="width:70px" required="required" value="0.00"/></td>
	          <td id="dato1"><div id="resultado_generador"><input name="CostoUnd" type="number" id="CostoUnd" min="0" step="0.01" style="width:70px" required="required" value="0.00" readonly="readonly"/></div></td>
	          <td id="dato1"><select name="Acep" id="Acep">
	            <option value="0">Conforme</option>
	            <option value="1">No Conforme</option>
	            </select></td>
	          <td id="dato1"><input type="submit" name="Submit" value="Grabar" /></td>
	          </tr>
	        <tr>
	          <td colspan="6" id="dato4">&nbsp;</td>
	          </tr>
	        <tr id="tr1">
	          <td colspan="6" id="titulo2">INGRESOS FECHA: <?php echo date("Y-m-d"); ?></td>
	          </tr>
	          <tr>
	            <td nowrap="nowrap" colspan="6" id="dato2"><div id="resultado"><?php include('inventario_consulta.php');?></div></td>
	            </tr>
	          <tr>
	            <td nowrap="nowrap" colspan="6" id="dato2">&nbsp;</td>
	            </tr>
	          <tr>
	            <td nowrap="nowrap" colspan="6" id="dato2"><a href="inventario.php">Ir a Inventario</a></td>
	            </tr>
	        <tr>
	          <td colspan="6" id="dato2"><input type="hidden" name="MM_insert" value="form1" /></td>
	          </tr>
	        </table>
	    </td>
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
mysql_free_result($ultimo);

?>