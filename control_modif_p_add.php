<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  session_unregister('MM_Username');
  session_unregister('MM_UserGroup');
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
session_start();
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
$id_ref=$_POST['id_ref_cm'];
$version_ref=$_POST['version_ref'];//nueva ver
$version_ref_verif_p=$_POST['version_ref_verif_p'];//vieja ver
$id_verif=$_POST['id_verif_cm'];
$sql1="UPDATE Tbl_referencia SET version_ref='$version_ref' WHERE id_ref='$id_ref'";
$sql2="UPDATE Tbl_verificacion_packing SET version_ref_verif_p='$version_ref_verif_p' WHERE id_verif_p='$id_verif'"; 

 $insertSQL = sprintf("INSERT INTO Tbl_control_modificaciones_p (id_cm, id_verif_cm, id_ref_cm, fecha_cm, responsable_cm, descripcion_cm) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_cm'], "int"),
                       GetSQLValueString($_POST['id_verif_cm'], "int"),
                       GetSQLValueString($_POST['id_ref_cm'], "int"),					   
                       GetSQLValueString($_POST['fecha_cm'], "date"),
                       GetSQLValueString($_POST['responsable_cm'], "text"),
                       GetSQLValueString($_POST['descripcion_cm'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $result1=mysql_query($sql1);
  $result2=mysql_query($sql2);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "verificacion_referencia_packing.php?id_ref=" . $_POST['id_ref_cm'] . "&id_verif_p=" . $_POST['id_verif_cm'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_usuario_comercial = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

mysql_select_db($database_conexion1, $conexion1);
$query_modificacion_add = "SELECT * FROM Tbl_control_modificaciones_p ORDER BY id_cm DESC";
$modificacion_add = mysql_query($query_modificacion_add, $conexion1) or die(mysql_error());
$row_modificacion_add = mysql_fetch_assoc($modificacion_add);
$totalRows_modificacion_add = mysql_num_rows($modificacion_add);

$colname_verif_modif = "-1";
if (isset($_GET['id_verif_p'])) {
  $colname_verif_modif = (get_magic_quotes_gpc()) ? $_GET['id_verif_p'] : addslashes($_GET['id_verif_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verif_modif = sprintf("SELECT * FROM Tbl_verificacion_packing, Tbl_referencia WHERE Tbl_verificacion_packing.id_verif_p = %s AND Tbl_verificacion_packing.id_ref_verif_p=Tbl_referencia.id_ref", $colname_verif_modif);
$verif_modif = mysql_query($query_verif_modif, $conexion1) or die(mysql_error());
$row_verif_modif = mysql_fetch_assoc($verif_modif);
$totalRows_verif_modif = mysql_num_rows($verif_modif);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
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
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td>
</tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario_comercial['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
	<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="4" id="titulo2">CONTROL DE MODIFICACIONES</td>
        </tr>
      <tr>
        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
        <td id="fuente2">Consecutivo N&ordm; <?php $num=$row_modificacion_add['id_cm']+1; echo $num; ?>
          <input name="id_cm" type="hidden" value="<?php $num=$row_modificacion_add['id_cm']+1; echo $num; ?>"></td>
        <td colspan="2" id="dato2"><a href="control_modificaciones_p.php"><img src="images/m.gif" alt="CONTROL MODIFICACIONES" title="CONTROL MODIFICACIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_verif_modif['id_ref_verif_p']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF"border="0" style="cursor:hand;" /></a><a href="verificacion_p.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="VERIFICACIONES" border="0" style="cursor:hand;" /></a></td>
      </tr>
      <tr id="tr1">
        <td id="fuente2"><input name="id_verif_cm" type="hidden" value="<?php echo $_GET['id_verif_p']; ?>">
          VERIFICACION</td>
        <td colspan="2" id="fuente2">FECHA REGISTRO </td>
      </tr>
      <tr>
        <td id="dato2"><a href="verificacion_packing_edit.php?id_verif_p=<?php echo $row_verif_modif['id_verif_p']; ?>"><?php echo $row_verif_modif['id_verif_p']; ?></a></td>
        <td colspan="2" id="dato2"><input type="text" name="fecha_cm" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente2">REFERENCIA</td>
        <td colspan="2" id="fuente2">RESPONSABLE</td>
      </tr>
      <tr>
        <td id="dato2"><input name="id_ref_cm" type="hidden" value="<?php echo $row_verif_modif['id_ref_verif_p']; ?>">
          <?php echo $row_verif_modif['cod_ref']; ?></td>
        <td colspan="2" id="dato2"><input name="responsable_cm" type="text" value="<?php echo $row_usuario_comercial['nombre_usuario']; ?>" size="30" readonly></td>
      </tr>
      <tr>
        <td id="numero2"><strong>VERSION C.M.</strong><input name="version_ref_verif_p" type="text" id="version_ref_verif_p" value="<?php echo $row_verif_modif['version_ref_verif_p']; ?>" size="2"></td>
        <td id="acceso2">VERSION REF
          <input name="version_ref" type="text" id="version_ref" value="<?php echo'0';echo $row_verif_modif['version_ref']+1; ?>" size="2"></td>
        <td id="numero1">La version es autoincrementable</td>
      </tr>
      <tr id="tr1">
        <td colspan="4" id="fuente2">          DESCRIPCION</td>
        </tr>
      <tr>
        <td colspan="4" id="dato2"><textarea name="descripcion_cm" cols="70" rows="5"></textarea>		</td>
        </tr>

      <tr>
        <td colspan="4" id="dato2"><input type="submit" value="ADD MODIFICACION"></td>
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
mysql_free_result($usuario_comercial);

mysql_free_result($modificacion_add);

mysql_free_result($verif_modif);
?>