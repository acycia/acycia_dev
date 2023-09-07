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
  $insertSQL = sprintf("INSERT INTO TblProveedorInsumo (id_p,id_in) VALUES (%s, %s)",
                       GetSQLValueString($_POST['id_p'],"int"),
                       GetSQLValueString($_POST['id_in'], "int"));
   mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "proveedor_insumo.php?id_p=" . $_POST['id_p'] . "";
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

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM TblProveedorInsumo,proveedor,insumo WHERE proveedor.id_p=%s AND  proveedor.id_p=TblProveedorInsumo.id_p AND TblProveedorInsumo.id_in=insumo.id_insumo ORDER BY TblProveedorInsumo.id_in ASC", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

$colname_insumo = "-1";
if (isset($_GET['id_p'])) {
  $colname_insumo  = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumos = sprintf("SELECT * FROM insumo WHERE estado_insumo ='0' AND id_insumo NOT IN(SELECT id_in FROM TblProveedorInsumo WHERE id_p=%s) ORDER BY descripcion_insumo ASC", $colname_insumo);
$insumos = mysql_query($query_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);
$totalRows_insumos = mysql_num_rows($insumos);

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
	<li><a href="proveedores.php">PROVEEDORES</a></li>	
	</ul></td>
</tr>  
  <tr>
    <td colspan="2" id="dato3"><a href="javascript:redireccionar()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
<a href="proveedores.php"><img src="images/p.gif" border="0" alt="REF'S ACTIVAS"/></a></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><?php if($row_proveedor['id_pi'] <> '') { ?>
      <table id="tabla1">
        <tr id="tr1">
          <td id="titulo4"><img src="images/por.gif" alt="ELIMINACION"/></td>
          <td id="titulo4"><a href="proveedores.php">PROVEEDOR</a></td>
          <td id="titulo4"><a href="insumos.php">INSUMO</a></td>
          <td id="titulo4">NIT</td>
          <td id="titulo4">PAIS / CIUDAD </td>
          <td id="titulo4">DIRECCION</td>
          <td id="titulo4">TELEFONO</td>
          </tr>
        <?php do { ?>
          <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
            <td id="dato2"><a href="javascript:eliminar1('id_pi',<?php echo $row_proveedor['id_pi']; ?>,'proveedor_insumo.php')"><img src="images/por.gif" alt="ELIMINAR" border="0"/></a></td>
            <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedor['id_p']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_proveedor['proveedor_p']; ?></a></td>
            <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedor['id_p']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_proveedor['descripcion_insumo']; ?></a></td>
            <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedor['id_p']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_proveedor['nit_p']; ?></a></td>
            <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedor['id_p']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_proveedor['pais_p']; ?> / <?php echo $row_proveedor['ciudad_p']; ?></a></td>
            <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedor['id_p']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_proveedor['direccion_p']; ?></a></td>
            <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedor['id_p']; ?>" style="text-decoration:none; color:#000000"><?php echo $row_proveedor['telefono_p']; ?></a></td>
            </tr>
          <?php } while ($row_proveedor = mysql_fetch_assoc($proveedor)); ?>
</table> <?php } ?></td></tr>
<tr>
<td colspan="2" align="center">
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table id="tabla1">
      <tr>
        <td colspan="2" id="dato1">Add insumo a proveedor</td>
      </tr>
      <tr>
        <td id="fuente1">           
          <select name="id_p" id="id_p" onchange="redireccionar('id_p',form1.id_p.value);" style="width:220px">
            <option value=""<?php if (!(strcmp("", $_GET['id_p']))) {echo "selected=\"selected\"";} ?>>PROVEEDORES</option>
            <?php
do {  
?>
            <option value="<?php echo $row_proveedores['id_p']?>"<?php if (!(strcmp($row_proveedores['id_p'], $_GET['id_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_proveedores['proveedor_p']?></option>
            <?php
} while ($row_proveedores = mysql_fetch_assoc($proveedores));
  $rows = mysql_num_rows($proveedores);
  if($rows > 0) {
      mysql_data_seek($proveedores, 0);
	  $row_proveedores = mysql_fetch_assoc($proveedores);
  }
?>
          </select></td>
        <td id="fuente1"><select name="id_in" style="width:220px" onBlur="DatosGestiones('22','id_p',form1.id_p.value);">
          <option value="">INSUMOS</option>
          <?php
do {  
?>
          <option value="<?php echo $row_insumos['id_insumo']?>"><?php echo $row_insumos['descripcion_insumo']?></option>
          <?php
} while ($row_insumos = mysql_fetch_assoc($insumos));
  $rows = mysql_num_rows($insumos);
  if($rows > 0) {
      mysql_data_seek($insumos, 0);
	  $row_insumos = mysql_fetch_assoc($insumos);
  }
?>
        </select></td>
      </tr>
      <tr id="tr3">
        <td colspan="2" id="dato2"><div id="resultado"></div></td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><input type="submit" value="ADD INSUMO"></td>
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

mysql_free_result($proveedores);

mysql_free_result($proveedor);
 
?>
