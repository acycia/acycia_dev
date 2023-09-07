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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

 $updateSQL = sprintf("UPDATE TblNovedades SET dias_incapacidad=%s,dias_faltantes=%s, pago_acycia=%s,pago_eps=%s, horas_extras=%s,recargos=%s,festivos=%s,fecha=%s WHERE id_nov=%s",                    
					   GetSQLValueString($_POST['dias_incapacidad'], "int"),
					   GetSQLValueString($_POST['dias_faltantes'], "int"),
					   GetSQLValueString($_POST['pago_acycia'], "double"),
					   GetSQLValueString($_POST['pago_eps'], "double"),
					   GetSQLValueString($_POST['horas_extras'], "int"),
					   GetSQLValueString($_POST['recargos'], "double"),
					   GetSQLValueString($_POST['festivos'], "double"),
					   GetSQLValueString($_POST['fecha'], "date"),
					   GetSQLValueString($_POST['id_nov'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result = mysql_query($updateSQL, $conexion1) or die(mysql_error());

//ACTUALIZAR DIAS EMPLEADO
/*$dias_reportados= $_POST['dias_incapacidad'];
  $updateSQL = sprintf("UPDATE TblProcesoEmpleado SET dias_empleado=dias_empleado-$dias_reportados WHERE codigo_empleado=%s",
					   GetSQLValueString($dias_reportados, "int"),
					   GetSQLValueString($_POST['codigo_empleado'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());*/
  
echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
echo "<script type=\"text/javascript\">window.close();</script>";

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
//EMPLEADO NOVEDADES
$colname_novedad= "-1";
if (isset($_GET['id_nov'])) {
  $colname_novedad = (get_magic_quotes_gpc()) ? $_GET['id_nov'] : addslashes($_GET['id_nov']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_novedad = sprintf("SELECT * FROM TblNovedades,TblProcesoEmpleado WHERE TblNovedades.id_nov=%s AND TblNovedades.codigo_empleado=TblProcesoEmpleado.codigo_empleado",$colname_novedad);
$novedad = mysql_query($query_novedad, $conexion1) or die(mysql_error());
$row_novedad = mysql_fetch_assoc($novedad);
$totalRows_novedad = mysql_num_rows($novedad);
//LISTAR
$colname_listar= "-1";
if (isset($_GET['cod'])) {
  $colname_listar = (get_magic_quotes_gpc()) ? $_GET['cod'] : addslashes($_GET['cod']);
}
$fechafin =$_GET['fecha'];
mysql_select_db($database_conexion1, $conexion1);
$query_listar = sprintf("SELECT * FROM TblNovedades WHERE codigo_empleado=%s ORDER BY fecha DESC",$colname_listar);
$listar = mysql_query($query_listar, $conexion1) or die(mysql_error());
$rows_listar = mysql_fetch_assoc($listar);
$totalRows_listar = mysql_num_rows($listar);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
</head>
<body>
<div align="center">
  <table align="center" id="tabla">
    <tr align="center">
      <td><div> <b class="spiffy"> <b class="spiffy1"><b></b></b> <b class="spiffy2"><b></b></b> <b class="spiffy3"></b> <b class="spiffy4"></b> <b class="spiffy5"></b></b>
          <div class="spiffy_content">
            <table id="tabla1">
              <tr>
                <td colspan="2" align="center"><img src="images/cabecera.jpg"></td>
              </tr>
              <tr>
                <td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
              </tr>
              <tr>
                <td colspan="2" align="center" id="linea1"><form name="form1" method="post" action="<?php echo $editFormAction; ?>">
                    <table id="tabla2">
                      <tr>
                        <td colspan="8" id="subtitulo">EDITAR NOVEDADES
                          <input type="hidden" name="id_nov" id="id_nov" value="<?php echo $row_novedad['id_nov']; ?>"></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Codigo Empleado</td>
                        <td colspan="6" id="fuente1"><input name="codigo_empleado" type="number" id="codigo_empleado" value="<?php echo $row_novedad['codigo_empleado']?>" readonly></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Sueldo</td>
                        <td colspan="6" id="fuente1"><input type="number" name="sueldo" id="sueldo" value="<?php echo $row_novedad['sueldo']?>" readonly ></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Auxilio Trans</td>
                        <td colspan="6" id="fuente1"><input type="number" name="aux_empleado" id="aux_empleado" value="<?php echo $row_novedad['aux_empleado']?>" readonly ></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Dias de Incapacidad
                          <input name="porc_incapacidad" style="width:60px" type="number" id="porc_incapacidad" placeholder="%" step="0.01" min="0" value="66.67" onChange="novedadEmpleado()">
                          <input name="dia_ley" style="width:40px" type="number" id="dia_ley" placeholder="dia" step="1" min="0" value="2" onChange="novedadEmpleado()"></td>
                        <td colspan="6" id="fuente1"><input name="dias_incapacidad" type="number" id="dias_incapacidad" min="0" placeholder="Dias" onChange="novedadEmpleado()" value="<?php echo $row_novedad['dias_incapacidad']?>">
                          Acycia
                          <input name="pago_acycia" style="width:90px" type="number" id="pago_acycia" placeholder="$" step="0.01" min="0" value="<?php echo $row_novedad['pago_acycia']?>">
                          Eps
                          <input name="pago_eps" style="width:90px" type="number" id="pago_eps" placeholder="$" step="0.01" min="0" value="<?php echo $row_novedad['pago_eps']?>"></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Dias No Laborados</td>
                        <td colspan="6" id="fuente1"><input name="dias_faltantes" type="number" id="dias_faltantes" min="0" placeholder="No Laborados" ></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Horas Extras</td>
                        <td colspan="6" id="fuente1"><input type="number" name="horas_extras" id="horas_extras" min="0" placeholder="$" value="<?php echo $row_novedad['horas_extras']?>" ></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Recargos</td>
                        <td colspan="6" id="fuente1"><input type="number" name="recargos" id="recargos" min="0" step="0.01" placeholder="$" value="<?php echo $row_novedad['recargos']?>" ></td>
                      </tr>
                      <tr>
                        <td colspan="2" id="fuente1">Festivos Laborados</td>
                        <td colspan="6" id="fuente1"><input type="number" name="festivos" id="festivos" min="0" placeholder="$" value="<?php echo $row_novedad['festivos']?>" > </td>
                      </tr>
<tr>
                        <td colspan="2" id="fuente1">Fecha de la Novedad</td>
                        <td colspan="6" id="fuente1"><input type="date" name="fecha" id="fecha" value="<?php echo $row_novedad['fecha']?>" ></td>
                      </tr>                      
                      <tr>
                        <td colspan="8" id="fuente1"><?php $id=$_GET['id']; if($id >= '1') { ?>
                          <div id="acceso1"> <?php echo "ELIMINACION CORRECTA"; ?> </div>
                          <?php } 
                if($id == '0') { ?>
                          <div id="numero1"> <?php echo "NO SE PUDO ELIMINAR"; ?> </div>
                          <?php } ?></td>
                      </tr>
                      <?php if($rows_listar['id_nov']!='') {?>
                      <tr>
                        <td colspan="8" id="fuente1"><p><em><strong>Lista de novedades:</strong></em></p></td>
                      </tr>
                      <tr>
                        <td id="fuente1"><p><em>Codigo Empleado</em></td>
                        <td id="fuente1"><p><em>sueldo</em></td>
                        <td id="fuente1"><p><em>dias incapacidad</em></td>
                        <td id="fuente1"><p><em>horas extras</em></td>
                        <td id="fuente1"><p><em>recargos</em></td>
                        <td id="fuente1"><p><em>festivos</em></td>
                        <td id="fuente1"><p><em>fecha</em></td>
                        <td id="fuente1"><p><em>eliminar</em></td>
                      </tr>
                      <?php do {?>
                      <tr>
                        <td id="fuente1"><a href="novedades_edit.php?id_nov=<?php echo $rows_listar['id_nov']; ?>&cod=<?php echo $rows_listar['codigo_empleado']; ?>" target="_self"><em><?php echo $rows_listar['id_nov']?></em></a></td>
                        <td id="fuente1"><a href="novedades_edit.php?id_nov=<?php echo $rows_listar['id_nov']; ?>&cod=<?php echo $rows_listar['codigo_empleado']; ?>" target="_self"><em><?php echo $rows_listar['sueldo']?></em></a></td>
                        <td id="fuente1"><a href="novedades_edit.php?id_nov=<?php echo $rows_listar['id_nov']; ?>&cod=<?php echo $rows_listar['codigo_empleado']; ?>" target="_self"><em><?php echo $rows_listar['dias_incapacidad']?></em></a></td>
                        <td id="fuente1"><a href="novedades_edit.php?id_nov=<?php echo $rows_listar['id_nov']; ?>&cod=<?php echo $rows_listar['codigo_empleado']; ?>" target="_self"><em><?php echo $rows_listar['horas_extras']?></em></a></td>
                        <td id="fuente1"><a href="novedades_edit.php?id_nov=<?php echo $rows_listar['id_nov']; ?>&cod=<?php echo $rows_listar['codigo_empleado']; ?>" target="_self"><em><?php echo $rows_listar['recargos']?></em></a></td>
                        <td id="fuente1"><a href="novedades_edit.php?id_nov=<?php echo $rows_listar['id_nov']; ?>&cod=<?php echo $rows_listar['codigo_empleado']; ?>" target="_self"><em><?php echo $rows_listar['festivos']?></em></a></td>
                        <td id="fuente1"><a href="novedades_edit.php?id_nov=<?php echo $rows_listar['id_nov']; ?>&cod=<?php echo $rows_listar['codigo_empleado']; ?>" target="_self"><em><?php echo $rows_listar['fecha']?></em></a></td>
                        <td id="fuente1"><a href="javascript:eliminar1('id_nov',<?php echo $rows_listar['id_nov'];?>,'proceso_empleados_listado.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR NOVEDAD"
title="ELIMINAR NOVEDAD" border="0"></a></td>
                      </tr>
                      <?php } while ($rows_listar = mysql_fetch_assoc($listar)); ?>
                      <?php }?>
                      <tr>
                        <td colspan="8" id="fuente1">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="8" id="fuente1">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="8" id="fuente2"><input type="hidden" name="MM_update" value="form1" /></td>
                      </tr>
                      <tr>
                        <td colspan="8" id="fuente2"><input type="submit" name="Guardar" id="Guardar" value="Actualizar Novedad"></td>
                      </tr>
                      <tr>
                        <td colspan="8" id="dato2"></td>
                      </tr>
                    </table>
                </form></td>
              </tr>
              <tr>
                <td colspan="7" align="center">&nbsp;</td>
              </tr>
            </table>
          </div>
          <b class="spiffy"> <b class="spiffy5"></b> <b class="spiffy4"></b> <b class="spiffy3"></b> <b class="spiffy2"><b></b></b> <b class="spiffy1"><b></b></b></b></div></td>
    </tr>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

?>