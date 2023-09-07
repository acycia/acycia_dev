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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
$id_turno=$_POST['id_turno'];
  $insertSQL = sprintf("INSERT INTO empleado_turno (id_empleado_turno, codigo_turno, horario_turno) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['id_empleado_turno'], "int"),
                       GetSQLValueString($_POST['codigo_turno'], "int"),
                       GetSQLValueString($_POST['horario_turno'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "turnos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE empleado_turno SET codigo_turno=%s, horario_turno=%s WHERE id_empleado_turno=%s",
                       GetSQLValueString($_POST['codigo_turno'], "int"),
                       GetSQLValueString($_POST['horario_turno'], "text"),
                       GetSQLValueString($_POST['id_empleado_turno'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "turnos.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    //$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    //$updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$conexion = new ApptivaDB();

$colname_usuario_admon = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_admon = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
 
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

mysql_select_db($database_conexion1, $conexion1);
$query_ver_turnos = "SELECT * FROM empleado_turno ORDER BY codigo_turno ASC";
$ver_turnos = mysql_query($query_ver_turnos, $conexion1) or die(mysql_error());
$row_ver_turnos = mysql_fetch_assoc($ver_turnos);
$totalRows_ver_turnos = mysql_num_rows($ver_turnos);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo_turno = "SELECT * FROM empleado_turno ORDER BY codigo_turno DESC";
$ultimo_turno = mysql_query($query_ultimo_turno, $conexion1) or die(mysql_error());
$row_ultimo_turno = mysql_fetch_assoc($ultimo_turno);
$totalRows_ultimo_turno = mysql_num_rows($ultimo_turno);

$colname_editar_turno = "-1";
if (isset($_GET['id_empleado_turno'])) {
  $colname_editar_turno = (get_magic_quotes_gpc()) ? $_GET['id_empleado_turno'] : addslashes($_GET['id_empleado_turno']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar_turno = sprintf("SELECT * FROM empleado_turno WHERE id_empleado_turno = %s", $colname_editar_turno);
$editar_turno = mysql_query($query_editar_turno, $conexion1) or die(mysql_error());
$row_editar_turno = mysql_fetch_assoc($editar_turno);
$totalRows_editar_turno = mysql_num_rows($editar_turno);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

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

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 

</head>
<body>
<?php echo $conexion->header('listas'); ?>
 
<table class="table table-bordered table-sm">
<tr align="center"><td>
<tr>
	<td align="center" colspan="2" id="linea1">
	<table class="table table-bordered table-sm">	
	<tr>
	  <td id="titulo2" width="80%">LISTADO DE TURNOS EN PLANTA </td>
	  <td id="titulo2" width="20%"><a href="empleados.php"><img src="images/e.gif" alt="EMPLEADOS" border="0" style="cursor:hand;"/></a><a href="empleado_tipo.php"><img src="images/identico.gif" alt="TIPOS DE EMPLEADO" border="0" style="cursor:hand;"/></a><img src="images/ciclo1.gif" alt="RESTAURAR" style="cursor:hand;" onclick="window.history.go()"></td>
	</tr>
  <?php $id_turno=$row_ver_turnos['id_empleado_turno'];
  if($id_turno!='')
  {
  ?>
  <tr>
    <td colspan="2" id="dato1">
      <table id="tabla5">
  <tr id="tr1">
  <td id="titulo4">TURNO</td>
<td id="titulo4">HORARIO</td>
<td id="titulo4">ACCION</td>
</tr><?php do { ?>
<tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8">
  <td id="detalle2"><?php echo $row_ver_turnos['codigo_turno']; ?></td>
<td id="detalle2"><?php echo $row_ver_turnos['horario_turno']; ?></td>
<td id="detalle2"><a href="turnos.php?id_empleado_turno=<?php echo $row_ver_turnos['id_empleado_turno']; ?>&amp;id_turno=1"><img src="images/menos.gif" alt="EDIT TURNO" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('id_empleado_turno',<?php echo $row_ver_turnos['id_empleado_turno']; ?>,'turnos.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a></td>
</tr>
<?php } while ($row_ver_turnos = mysql_fetch_assoc($ver_turnos)); ?>
<?php } ?>
</table></td>
    </tr>  
  <tr>
    <td colspan="2" id="fuente1">&nbsp;</td>
  </tr>
  <?php $id_turno=$_GET['id_turno']; if($id_turno=='1') { ?>
  <tr>
    <td colspan="2" id="fuente1">
      <form method="post" name="form1" action="<?php echo $editFormAction; ?>" onsubmit="MM_validateForm('codigo_turno','','RisNum','horario_turno','','R');return document.MM_returnValue">
        <table id="tabla35">
          <tr>
            <td id="fuente2">N&ordm;</td>
            <td id="fuente2">&nbsp;</td>
            <td id="fuente2">Modifique el horario de turno </td>
            <td id="fuente2">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="codigo_turno" value="<?php echo $row_editar_turno['codigo_turno']; ?>" size="2" onBlur="if (form1.codigo_turno.value) { DatosGestiones('4','codigo_turno',form1.codigo_turno.value); } else { alert('Debe digitar el numero del turno.'); }"/></td>
            <td id="dato2"><div id="resultado"></div></td>
            <td id="dato2"><input type="text" name="horario_turno" value="<?php echo $row_editar_turno['horario_turno']; ?>" size="20" /></td>
            <td id="dato2"><input name="submit" type="submit" value="Actualizar" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_empleado_turno" value="<?php echo $row_editar_turno['id_empleado_turno']; ?>">
      </form></td>
  </tr><?php } else { ?>  
  <tr>
  <td colspan="2" id="fuente1">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form2" onsubmit="MM_validateForm('codigo_turno','','RisNum','horario_turno','','R');return document.MM_returnValue">
	<table id="tabla35">
	<tr>
  <td id="fuente2">N&ordm;</td>
  <td id="fuente2">&nbsp;</td>
  <td id="fuente2">Digite el horario del turno </td>
  <td id="fuente2">Adicionar </td>
</tr>
<tr>
  <td id="dato2"><input name="codigo_turno" type="text" id="codigo_turno" size="2" value="<?php $num=$row_ultimo_turno['codigo_turno']; $num1=$num+1; echo $num1; ?>" onBlur="if (form2.codigo_turno.value) { DatosGestiones('4','codigo_turno',form2.codigo_turno.value); } else { alert('Debe digitar el numero del turno.'); }"></td>
  <td id="dato2"><div id="resultado"></div></td>
  <td id="dato2"><input name="id_empleado_turno" type="hidden" id="id_empleado_turno" />
  <input type="text" name="horario_turno" value="" size="20" /></td>
<td id="dato2"><input type="submit" value="Add Turno"></td>
</tr>
</table>
<input type="hidden" name="MM_insert" value="form2">
</form>  </td>
  </tr><?php } ?>
  <tr>
    <td colspan="2" id="fuente1">&nbsp;</td>
  </tr>
  </table>
   </td>
  </tr>
  </table>
</div>
</td>
</tr>
</table>
 
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario_admon);

mysql_free_result($ver_turnos);

mysql_free_result($ultimo_turno);

mysql_free_result($editar_turno);
?>