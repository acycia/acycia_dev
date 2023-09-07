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
//CONVERTIR EL ID_C EN NIT_C
$id=$_POST['id_c']; 
mysql_select_db($database_conexion1, $conexion1);
$query_nit_ver = ("SELECT * FROM cliente WHERE id_c = '$id'");
$nit_ver = mysql_query($query_nit_ver, $conexion1) or die(mysql_error());
$row_nit_ver = mysql_fetch_assoc($nit_ver);
$totalRows_nit_ver = mysql_num_rows($nit_ver);
//FIN
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Tbl_cliente_referencia (id_refcliente,N_referencia, N_cotizacion, Str_nit) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_refcliente'],"int"),
                       GetSQLValueString($_POST['N_referencia'], "int"),
                       GetSQLValueString($_POST['N_cotizacion'], "int"),
                       GetSQLValueString($row_nit_ver['nit_c'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "referencia_cliente.php?id_ref=" . $_POST['id_ref'] . "";
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

$colname_referencia = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM Tbl_referencia, Tbl_cotizaciones, cliente WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.n_cotiz_ref = Tbl_cotizaciones.N_cotizacion AND Tbl_cotizaciones.Str_nit = cliente.nit_c", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_cliente = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE cliente.nit_c NOT IN (SELECT Tbl_cliente_referencia.Str_nit FROM Tbl_cliente_referencia WHERE Tbl_cliente_referencia.N_referencia = %s) ORDER BY cliente.nombre_c ASC", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$colname_clientes = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_clientes = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_clientes = sprintf("SELECT * FROM Tbl_cliente_referencia, cliente WHERE Tbl_cliente_referencia.N_referencia= %s AND Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY cliente.nombre_c ASC", $colname_clientes);
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM Tbl_cliente_referencia ORDER BY id_refcliente DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_referencia_ver = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_ver = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_ver = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref = %s", $colname_referencia_ver);
$referencia_ver = mysql_query($query_referencia_ver, $conexion1) or die(mysql_error());
$row_referencia_ver = mysql_fetch_assoc($referencia_ver);
$totalRows_referencia_ver = mysql_num_rows($referencia_ver);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center">
<img src="images/cabecera.jpg"></td>
</tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu">
<ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>	
	</ul></td>
</tr>  
  <tr>
    <td colspan="2" id="dato3"><a href="referencia_bolsa_edit.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&amp;n_egp=<?php echo $row_referencia['n_egp_ref']; ?>"><img src="images/menos.gif" border="0" alt="REFERENCIA"/></a><a href="referencia_bolsa_vista.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" border="0" alt="VISTA IMPRESION"/></a><a href="referencias.php"><img src="images/a.gif" border="0" alt="REF'S ACTIVAS"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" border="0" alt="REF'S INACTIVAS"/></a></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1">Referencia creada para el cliente <?php echo $row_referencia['nombre_c']; ?> en la Cotizacion Nº <?php echo $row_referencia['n_cotiz_ref']; ?></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><?php if($row_clientes['id_refcliente'] <> '') { ?>
	<table id="tabla1">
	<tr id="tr1">
    <td id="titulo4"><img src="images/por.gif" alt="ELIMINACION"/></td>
    <td id="titulo4"><a href="listado_clientes.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/cliente.gif" border="0" alt="CLIENTES"/></a>CLIENTE</td>
    <td id="titulo4">NIT</td>
    <td id="titulo4">PAIS / CIUDAD </td>
    <td id="titulo4">DIRECCION</td>
    <td id="titulo4">TELEFONO</td>
    </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><a href="javascript:eliminar('id_refcliente',<?php echo $row_clientes['id_refcliente']; ?>,'referencia_cliente.php')"><img src="images/por.gif" alt="ELIMINAR" border="0"/></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_clientes['id_c']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_clientes['nombre_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_clientes['id_c']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_clientes['nit_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_clientes['id_c']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_clientes['pais_c']; ?> / <?php echo $row_clientes['ciudad_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_clientes['id_c']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_clientes['direccion_c']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_clientes['id_c']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_clientes['telefono_c']; ?></a></td>
      </tr>
    <?php } while ($row_clientes = mysql_fetch_assoc($clientes)); ?>
</table> <?php } ?></td></tr>
<tr>
<td colspan="2" align="center">
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table id="tabla1">
      <tr>
        <td id="dato1">Para Add otro cliente a esta referencia</td>
      </tr>
      <tr>
        <td id="fuente1"><input name="id_refcliente" type="hidden" value="<?php $num=$row_ultimo['id_refcliente']+1; echo $num; ?>">
          <input name="N_referencia" type="hidden" value="<?php echo $row_referencia_ver['cod_ref']; ?>" />
          <input name="N_cotizacion" type="hidden" value="<?php echo $row_referencia_ver['n_cotiz_ref']; ?>" />            
          <select name="id_c" id="id_c" onBlur="DatosGestiones('1','id_c',form1.id_c.value);">
            <option value="">SELECCIONE</option>
            <?php
do {  
?>
            <option value="<?php echo $row_cliente['id_c']?>"><?php echo $row_cliente['nombre_c']?></option>
            <?php
} while ($row_cliente = mysql_fetch_assoc($cliente));
  $rows = mysql_num_rows($cliente);
  if($rows > 0) {
      mysql_data_seek($cliente, 0);
	  $row_cliente = mysql_fetch_assoc($cliente);
  }
?>
          </select></td>
      </tr>      
      <tr id="tr3">
        <td id="dato2"><div id="resultado"></div></td>
      </tr>
      <tr>
        <td id="dato1"><input type="submit" value="ADD CLIENTE"></td>
        </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
  </form></td></tr></table>
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

mysql_free_result($clientes);

mysql_free_result($ultimo);

mysql_free_result($referencia_ver);
?>
