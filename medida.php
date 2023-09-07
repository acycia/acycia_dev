<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?>
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
  $insertSQL = sprintf("INSERT INTO medida (id_medida, nombre_medida) VALUES (%s, %s)",
                       GetSQLValueString($_POST['id_medida'], "int"),
                       GetSQLValueString($_POST['nombre_medida'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "medida.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE medida SET nombre_medida=%s WHERE id_medida=%s",
                       GetSQLValueString($_POST['nombre_medida'], "text"),
                       GetSQLValueString($_POST['id_medida'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "medida.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$conexion = new ApptivaDB();

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
$query_medida = "SELECT * FROM medida ORDER BY nombre_medida ASC";
$medida = mysql_query($query_medida, $conexion1) or die(mysql_error());
$row_medida = mysql_fetch_assoc($medida);
$totalRows_medida = mysql_num_rows($medida);

$colname_medida_edit = "-1";
if (isset($_GET['id_medida'])) {
  $colname_medida_edit = (get_magic_quotes_gpc()) ? $_GET['id_medida'] : addslashes($_GET['id_medida']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_medida_edit = sprintf("SELECT * FROM medida WHERE id_medida = %s", $colname_medida_edit);
$medida_edit = mysql_query($query_medida_edit, $conexion1) or die(mysql_error());
$row_medida_edit = mysql_fetch_assoc($medida_edit);
$totalRows_medida_edit = mysql_num_rows($medida_edit);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<!-- desde aqui para listados nuevos -->
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>


<!-- select2 -->
<link href="select2/css/select2.min.css" rel="stylesheet"/>
<script src="select2/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<?php echo $conexion->header('listas'); ?>
<table align="center" class="table table-bordered table-sm">
  <tr>
    <td >
 <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
        <table align="center">
          <tr>
            <td id="fuente2">N&deg;</td>
            <td id="fuente2">MEDIDA</td>
            <td id="fuente2">DELETE</td>
          </tr>
          <?php do { ?>
            <tr >
              <td id="detalle1"><?php echo $row_medida['id_medida']; ?></td>
              <td id="detalle1"><a href="medida.php?id_medida=<?php echo $row_medida['id_medida']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_medida['nombre_medida']; ?></td>
              <td id="detalle2"><a href="javascript:eliminar('id_medida',<?php echo $row_medida['id_medida']; ?>,'medida.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a></td>
            </tr>
            <?php } while ($row_medida = mysql_fetch_assoc($medida)); ?>
          <tr>
            <td id="dato2"><input name="id_medida" type="text" id="id_medida" value="" size="5" /></td>
            <td colspan="2" id="dato2"><input name="nombre_medida" type="text" id="nombre_medida" value="" size="30" /></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2"><input type="submit" value="ADD MEDIDA"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form></td>
    <td id="dato2"><?php $id_medida= $_GET['id_medida']; if($id_medida!='' && $id_medida!='0'){ ?>
      <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
        <table align="center">
          <tr>
            <td id="fuente2">N&deg;</td>
            <td id="fuente2">EDITE LA MEDIDA DEL INSUMO </td>
          </tr>
          <tr>
            <td id="dato2"><input name="id_medida" type="text" id="id_medida" value="<?php echo $row_medida_edit['id_medida']; ?>" size="5" /></td>
            <td id="dato2"><input type="text" name="nombre_medida" value="<?php echo $row_medida_edit['nombre_medida']; ?>" size="30"></td>
          </tr>
          <tr>
            <td id="dato2">&nbsp;</td>
            <td id="dato2"><input type="submit" value="ACTUALIZAR MEDIDA"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form2">
        <input type="hidden" name="id_medida" value="<?php echo $row_medida_edit['id_medida']; ?>">
      </form><?php } ?></td>
  </tr>
</table>
	</td>
</tr>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($medida);

mysql_free_result($medida_edit);
?>
