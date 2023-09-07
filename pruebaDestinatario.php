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
  $insertSQL = sprintf("INSERT INTO Tbl_Destinatarios (nit, nombre_responsable, direccion, telefono, ciudad ) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_c_cotiz'], "text"),
                       GetSQLValueString($_POST['n_cotiz'], "text"),
                       GetSQLValueString($_POST['responsable_cotiz'], "text"),
                       GetSQLValueString($_POST['fecha_cotiz'], "date"),
                       GetSQLValueString($_POST['hora_cotiz'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
  
  $resultcli = mysql_query("SELECT * FROM Tbl_Destinatarios WHERE nit = '$id_c_cotiz'");
$row_cliente = mysql_fetch_assoc($resultcli);
$totalRows_cliente = mysql_num_rows($resultcli);

  $insertGoTo = "pruebaDestinatario.php?id_c_cotiz=" . $_POST['id_c_cotiz'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = "SELECT * FROM cotizacion ORDER BY n_cotiz DESC";
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM Tbl_Destinatarios ORDER BY nombre_responsable ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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
  <li><a href="comercial.php">GESTION COMERCIAL</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
	<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('id_c_cotiz','','R');return document.MM_returnValue">
  <table id="tabla2">
    <tr id="tr1">
      <td nowrap id="codigo" width="25%">CODIGO : R1 - F01</td>
      <td nowrap id="titulo2" width="50%">COTIZACION</td>
      <td nowrap id="codigo" width="25%">VERSION : 0 </td>
    </tr>
    <tr>
      <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
      <td id="fuente2"><strong>BOLSA DE SEGURIDAD</strong></td>
      <td id="dato2"><a href="cotizacion_bolsa.php"><img src="images/cat.gif" alt="COTIZACIONES" border="0" style="cursor:hand;"/></a><a href="cotizacion_menu.php"><img src="images/opciones.gif" alt="MENU COTIZACION" border="0" style="cursor:hand;"/></a></td>
    </tr>
    <tr>
      <td id="numero2">N&deg; <input name="n_cotiz" type="hidden" value="<?php $num=$row_cotizacion['n_cotiz']+1; echo $num; ?>"> <strong><?php echo $num; ?></strong></td>
      <td id="fuente1">Fecha :</td>
      </tr>
    <tr>
      <td id="fuente1">Responsable del Registro: </td>
      <td id="fuente1"><input name="fecha_cotiz" type="text" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
    </tr>    
    <tr>
      <td id="fuente1"><input name="responsable_cotiz" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30"></td>
      <td id="fuente1">Hora :        </td>
    </tr>
    <tr>
      <td id="fuente1">Seleccione el Cliente a Cotizar</td>
      <td id="fuente1"><input name="hora_cotiz" type="text" value="<?php echo date("g:i a") ?>" size="10"></td>
    </tr>
    <tr>
      <td colspan="2" id="dato1"><input name="id_c_cotiz" type="text" onBlur="DatosGestiones('1','id_c_cotiz',form1.id_c_cotiz.value);"></td>
      </tr>   
    <tr id="tr1">
      <td colspan="3"><div id="resultado"></div></td>
      </tr>    
    <tr>
      <td colspan="3" id="dato2"><input type="submit" value="SIGUIENTE"></td>
      </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
</td>
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
<?php echo "variable: ".$editFormAction; 
echo "idcotiz  :".$_POST['id_c_cotiz'];
echo "id  :".$row_cliente['id_c'];
?>
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion);

mysql_free_result($cliente);
?>