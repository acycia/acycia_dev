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
include('funciones/funciones_php.php');//distintas funciones
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$valor=$_POST['nombre_generadores'];
$funcionMayusc= ($_POST['nombre_generadores']);
  $insertSQL = sprintf("INSERT INTO Tbl_generadores (id_generadores,codigo,nombre_generadores,categoria_generadores) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_generadores'], "int"),
					   GetSQLValueString($_POST['codigo'], "text"),
                       GetSQLValueString($funcionMayusc, "text"),
                       GetSQLValueString($_POST['categoria_generadores'], "text"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "costos_generadores_cif_gga.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
$valor=$_POST['nombre_generadores'];
$funcionMayusc= ($_POST['nombre_generadores']);
  $updateSQL = sprintf("UPDATE Tbl_generadores SET  codigo=%s, nombre_generadores=%s,categoria_generadores=%s WHERE id_generadores=%s",
                       GetSQLValueString($_POST['codigo'], "text"),
                       GetSQLValueString($funcionMayusc, "text"),
                       GetSQLValueString($_POST['categoria_generadores'], "text"),
                       GetSQLValueString($_POST['id_generadores'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "costos_generadores_cif_gga.php?editar=0";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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
$query_ultimo = "SELECT * FROM Tbl_generadores ORDER BY Tbl_generadores.id_generadores DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_generadores = "SELECT * FROM Tbl_generadores ORDER BY  id_generadores ASC";
$generadores = mysql_query($query_generadores, $conexion1) or die(mysql_error());
$row_generadores = mysql_fetch_assoc($generadores);
$totalRows_generadores = mysql_num_rows($generadores);

mysql_select_db($database_conexion1, $conexion1);
$query_tipo = "SELECT * FROM TblGenerador ORDER BY nombre_gen ASC";
$tipo = mysql_query($query_tipo, $conexion1) or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
$totalRows_tipo = mysql_num_rows($tipo);
  
$colname_generadores_edit = "-1";
if (isset($_GET['id_generadores'])) {
  $colname_generadores_edit = (get_magic_quotes_gpc()) ? $_GET['id_generadores'] : addslashes($_GET['id_generadores']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_generadores_edit = sprintf("SELECT * FROM Tbl_generadores WHERE Tbl_generadores.id_generadores = %s", $colname_generadores_edit);
$generadores_edit = mysql_query($query_generadores_edit, $conexion1) or die(mysql_error());
$row_generadores_edit = mysql_fetch_assoc($generadores_edit);
$totalRows_generadores_edit = mysql_num_rows($generadores_edit);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
<td id="cabezamenu"><ul id="menuhorizontal">
           <li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
           <li><a href="menu.php">MENU PRINCIPAL</a></li>
		   <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
</ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
	<table border="0" id="tabla1">
  <tr>
    <td colspan="2" id="fuente1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "ERROR AL ELIMINAR"; ?> </div> <?php }?></td>
    </tr>
  <tr>
    <td id="dato1"><form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
        <fieldset> <legend>Registro de Generadores</legend> 
        <table>
          <tr>
            <td id="fuente3">&nbsp;</td>
            <td id="fuente3">&nbsp;</td>
            <td id="fuente3">&nbsp;</td>
            <td id="fuente3">&nbsp;</td>
            <td id="fuente3">&nbsp;</td>
          </tr>
          <tr>
            <td id="fuente2">Item</td>
            <td id="fuente2">CODIGO</td>
            <td id="fuente2">NOMBRE GENERADOR</td>
            <td id="fuente2">CATEG.</td>
            <td id="fuente2">DELETE</td>
          </tr>
          <?php do { ?>
            <tr id="tr3">
              <td id="detalle1"><a href="costos_generadores_cif_gga.php?id_generadores=<?php echo $row_generadores['id_generadores']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_generadores['id_generadores']; ?></a></td>
              <td id="detalle1"><a href="costos_generadores_cif_gga.php?id_generadores=<?php echo $row_generadores['id_generadores']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_generadores['codigo']; ?></a></td>
              <td id="detalle1"><a href="costos_generadores_cif_gga.php?id_generadores=<?php echo $row_generadores['id_generadores']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_generadores['nombre_generadores']; ?></a></td>
              <td id="detalle2"><a href="costos_generadores_cif_gga.php?id_generadores=<?php echo $row_generadores['id_generadores']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_generadores['categoria_generadores']; ?></a></td>
              <td id="detalle2"><a href="javascript:eliminar('id_genera',<?php echo $row_generadores['id_generadores']; ?>,'costos_generadores_cif_gga.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a></td>
            </tr>
            <?php } while ($row_generadores = mysql_fetch_assoc($generadores)); ?>
          <tr>
            <td id="dato2"><input name="id_generadores" type="text" id="id_generadores" value="<?php echo $row_ultimo['id_generadores']+1;?>" size="2" /></td>
            <td id="dato2"><input name="codigo" type="number" id="codigo" style="width:100px" /></td>
            <td id="dato2"><input name="nombre_generadores" type="text" id="nombre_generadores" onkeypress="return primeraMayusc(event,this)" value="" size="40" onBlur="conMayusculas(this)" /></td>
            <td id="dato2"><select name="categoria_generadores" style="width:60px">
              <?php
do {  
?>
              <option value="<?php echo $row_tipo['nombre_gen']?>"><?php echo $row_tipo['nombre_gen']?></option>
              <?php
} while ($row_tipo = mysql_fetch_assoc($tipo));
  $rows = mysql_num_rows($tipo);
  if($rows > 0) {
      mysql_data_seek($tipo, 0);
	  $row_tipo = mysql_fetch_assoc($tipo);
  }
?>
            </select></td>
            <td id="dato2">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="5" id="dato2"><input type="submit" value="ADICIONAR GENERADOR"></td>
            </tr>
        </table>
        </fieldset>
        <input type="hidden" name="MM_insert" value="form1">
      </form></td>
    <td id="dato1" valign="top"><?php $editar=$_GET['editar']; $id_generadores= $_GET['id_generadores']; if($id_generadores!='' && $id_generadores!='0' && $editar!='0') { ?>
    <form method="POST" name="form2" action="<?php echo $editFormAction; ?>">
        <fieldset> <legend>Editar Generador</legend>
        <table align="center" >
          <tr>
            <td id="fuente4">&nbsp;</td>
            <td id="fuente4">&nbsp;</td>
            <td id="fuente4">&nbsp;</td>
            <td id="fuente4">&nbsp;</td>
          </tr>
          <tr>
            <td id="fuente2">Item</td>
            <td id="fuente2">CODIGO</td>
            <td id="fuente2">EDITAR NOMBRE GENERADOR </td>
            <td id="fuente2"> CATEG. </td>
          </tr>
          <tr>
            <td id="fuente1"><input name="id_generadores" type="text" id="id_generadores" value="<?php echo $row_generadores_edit['id_generadores']; ?>" readonly="readonly" size="2"/></td>
            <td id="fuente1"><input name="codigo" type="number" value="<?php echo $row_generadores_edit['codigo']; ?>" style="width:100px" /></td>
            <td id="fuente1"><input name="nombre_generadores" type="text" onkeypress="return primeraMayusc(event,this)" onBlur="conMayusculas(this)"   value="<?php echo $row_generadores_edit['nombre_generadores']; ?>"  size="25" maxlength="55"></td>
            <td id="fuente"><select name="categoria_generadores" style="width:60px">
            <option value=""<?php if (!(strcmp("",$row_generadores_edit['categoria_generadores']))) {echo "selected=\"selected\"";} ?>></option>
              <?php
do {  
?>
              <option value="<?php echo $row_tipo['nombre_gen']?>"<?php if (!(strcmp($row_tipo['nombre_gen'], $row_generadores_edit['categoria_generadores']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipo['nombre_gen']?></option>
              <?php
} while ($row_tipo = mysql_fetch_assoc($tipo));
  $rows = mysql_num_rows($tipo);
  if($rows > 0) {
      mysql_data_seek($tipo, 0);
	  $row_tipo = mysql_fetch_assoc($tipo);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input name="submit" type="submit" value="ACTUALIZAR GENERADOR" /></td>
            </tr>
        </table>
        </fieldset>
        <input type="hidden" name="MM_update" value="form2">
    </form><?php } ?></td>
  </tr>
</table>
	</td>
</tr></table>
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

mysql_free_result($generadores);

mysql_free_result($generadores_edit);

mysql_free_result($ultimo);


?>
