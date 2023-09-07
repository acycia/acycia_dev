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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {		
  $updateSQL = sprintf("UPDATE Tbl_refcliente SET id_c_rc=%s, str_nit_rc=%s, int_ref_ac_rc=%s, version_ref=%s, str_ref_cl_rc=%s, str_descripcion_rc=%s, fecha_rc=%s, str_responsable_rc=%s, int_estado_ref_rc=%s WHERE id_refcliente=%s",
                       
                       GetSQLValueString($_POST['id_c_rc'], "int"),
                       GetSQLValueString($_POST['str_nit_rc'], "text"),
                       GetSQLValueString($_POST['int_ref_ac_rc'], "int"),
                       GetSQLValueString($_POST['version_ref'], "text"),
                       GetSQLValueString($_POST['str_ref_cl_rc'], "text"),
                       GetSQLValueString($_POST['str_descripcion_rc'], "text"),
					   GetSQLValueString($_POST['fecha_rc'], "date"),
					   GetSQLValueString($_POST['str_responsable_rc'], "text"),
					   GetSQLValueString($_POST['int_estado_ref_rc'], "int"),
					   GetSQLValueString($_POST['id_refcliente'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "ref_ac_ref_cl_listado.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
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

$colname_ver_recliente = "-1";
if (isset($_GET['id_refcliente'])) 
{
  $colname_ver_recliente= (get_magic_quotes_gpc()) ? $_GET['id_refcliente'] : addslashes($_GET['id_refcliente']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_edit = sprintf("SELECT * FROM Tbl_refcliente WHERE id_refcliente=%s ORDER BY id_refcliente DESC", $colname_ver_recliente);
$ver_edit = mysql_query($query_ver_edit, $conexion1) or die(mysql_error());
$row_ver_edit = mysql_fetch_assoc($ver_edit);
$totalRows_ver_edit = mysql_num_rows($ver_edit);

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT * FROM Tbl_referencia WHERE estado_ref='1' order by id_ref desc";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
<tr><td id="nombreusuario"><?php echo $row_usuario_nuevo['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
           <li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
		   <li><a href="menu.php" target="_top">MENU PRINCIPAL</a></li>
            <li><a href="referencias.php" target="_top">REFERENCIAS</a></li>		       
</ul></td>
</tr>
<tr>
	<td colspan="2" align="center" id="linea1">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_rc','','R','str_ref_cl_rc','','R' );return document.MM_returnValue">
  <table id="tabla2">    
    <tr>
      <td width="162" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
      <td colspan="3" id="titulo2"><strong>ADICIONAR REF AC Y REF CLIENTE</strong></td>
      <td id="dato2"><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" title="RESTAURAR" onClick="window.history.go()" ><a href="ref_ac_ref_cl_listado.php"><img src="images/opciones.gif" alt="LISTADO DE REFERENCIAS CLIENTE"title="LISTADO DE REFERENCIAS CLIENTE" border="0" style="cursor:hand;"></a></td>
    </tr>
    <tr>
      <td id="fuente2">Fecha
        <!--<input name="id_refcliente" type="hidden" value="<?php $num=$row_ver_nuevo['id_refcliente']+1; echo $num; ?>">-->
        Registro</td>
      <td colspan="3" id="fuente1">Registrado por </td>
      </tr>
    <tr>
      <td id="dato2"><input name="fecha_rc" type="date" min="2000-01-02" value="<?php echo $row_ver_edit['fecha_rc'] ?>" size="10" required/></td>
      <td colspan="3" id="detalle1"><?php echo $row_ver_edit['str_responsable_rc']; ?>
        <input name="str_responsable_rc" type="hidden" id="str_responsable_rc" value="<?php echo $row_usuario['nombre_usuario']; ?>"></td>
      </tr>
    <tr>
      <td id="fuente2">&nbsp;</td>
      <td colspan="2" id="fuente2">&nbsp;</td>
      <td id="fuente2">&nbsp;</td>
    </tr>
    <tr>
      <td id="dato2">&nbsp;</td>
      <td colspan="2" id="dato2">&nbsp;</td>
      <td id="dato2">&nbsp;</td>
    </tr>
    <tr>
      <td id="dato2"><div id="existe"></div></td>
      <td colspan="3" id="dato2">&nbsp;</td>
      </tr>
    <tr id="tr1">
      <td id="titulo4">REF AC</td>
      <td id="titulo4">VERS.</td>
      <td id="titulo4">CLIENTE</td>
      <td id="titulo4">REF CLIENTE</td>
      <td id="titulo4">DESCRIPCION</td>
      <td id="titulo4">ESTADO</td>
      <td id="titulo4">ELIMINAR</td>
</tr>
    <tr><td id="dato1"><input type="text" name="int_ref_ac_rc" id="int_ref_ac_rc" value="<?php  echo $row_ver_edit['int_ref_ac_rc'] ?>" size="7" readonly></td>
      <td id="dato2"><input name="version_ref" value="<?php  echo $row_ver_edit['version_ref'] ?>" type="text" size="3" readonly></td>
            <td id="dato2"><input name="cliente" type="text" value="<?php 
	$id_c_rc=$row_ver_edit['id_c_rc'];
	$sqln="SELECT * FROM cliente WHERE id_c='$id_c_rc'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nombre_c=mysql_result($resultn,0,'nombre_c'); $ca = htmlentities ($nombre_c);echo $ca; }
	else { echo "";	} ?>" readonly></td>
      <td id="dato2"><input name="str_ref_cl_rc" type="text" required id="str_ref_cl_rc" value="<?php echo $row_ver_edit['str_ref_cl_rc'] ?>" size="10"onBlur="conMayusculas(this)"></td>
      <td id="dato2"><input name="str_descripcion_rc" type="text" id="str_descripcion_rc" value="<?php echo $row_ver_edit['str_descripcion_rc'] ?>" size="10"onBlur="conMayusculas(this)"></td>
      <td id="dato2"><select name="int_estado_ref_rc" id="int_estado_ref_rc">
        <option value="1"<?php if (!(strcmp(1, $row_ver_edit['int_estado_ref_rc']))) {echo "selected=\"selected\"";} ?>>ACTIVO</option>
        <option value="0"<?php if (!(strcmp(0, $row_ver_edit['int_estado_ref_rc']))) {echo "selected=\"selected\"";} ?>>INACTIVO</option>
      </select></td>
      <td id="dato2"><a href="javascript:eliminar1('id_refac_refcliente',<?php echo $row_ver_edit['id_refcliente']; ?>,'ref_ac_ref_cl_listado.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR CLIENTE"title="ELIMINAR CLIENTE" border="0"></a></td>      
      </tr>
    <tr>
      <td colspan="6" id="fuente2">Alguna inquietud o recomendaci&oacute;n favor comunicarse con sistemas@acycia.com        </td>
    </tr>    
    <tr>
      <td colspan="6" id="dato2"><input type="hidden" name="id_c_rc" id="id_c_rc" value="<?php echo $row_ver_edit['id_c_rc'];?>">
        <input type="hidden" name="str_nit_rc" id="str_nit_rc" value="<?php echo $row_ver_edit['str_nit_rc'];?>">
      
      <input type="hidden" name="id_refcliente" id="id_refcliente" value="<?php echo $row_ver_edit['id_refcliente']?>">
        <input type="hidden" name="MM_update" value="form1">
      <input name="submit" type="submit"  value="Editar Referencia"></td>
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

mysql_free_result($referencia);

mysql_free_result($cliente);
?>
