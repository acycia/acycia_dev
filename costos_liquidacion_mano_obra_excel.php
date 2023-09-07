<?php require_once('Connections/conexion1.php'); ?>
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="LiquidacionManoObra.xls"');
?>
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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
$currentPage = $_SERVER["PHP_SELF"];
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$tipoListado = $_GET['tipoListado'];//variable de control del case
  switch ($tipoListado) {
    case "1":
mysql_select_db($database_conexion1, $conexion1);
$query_costos = "SELECT * FROM TblProcesoEmpleado WHERE estado_empleado='1' ORDER BY proceso_empleado ASC";
$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
$row_costos = mysql_fetch_assoc($costos);
$totalRows_costos = mysql_num_rows($costos);
      break;    
  }
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table id="Exportar_a_Excel" border="1">   
  <tr>
    <td colspan="10" nowrap="nowrap" align="center">LIQUIDACION MANO DE OBRA</td>
  </tr>
<tr id="tr1">
  <td id="titulo4" nowrap>Nombre</td>
  <td id="titulo4" nowrap>Cargo</td>
  <td id="titulo4" nowrap>Dias Laborados</td>
  <td id="titulo4" nowrap>Total Periodo</td>
  <td id="titulo4" nowrap>Costo Hora</td>
  <td id="titulo4" nowrap>M&aacute;quinas</td>
  <td id="titulo4" nowrap>Total Operarios</td>
  <td id="titulo4" nowrap>Operarios por Máquina</td>
  <td id="titulo4" nowrap>Operarios por turno y Máquina</td>
  <td id="titulo4" nowrap>Costo Hora Proceso x Línea y turno</td> 
  </tr>
<?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato1" nowrap>
      <?php $codigo_empleado=$row_costos['codigo_empleado']; 	
	$sqlemp="SELECT nombre_empleado, apellido_empleado FROM empleado WHERE codigo_empleado='$codigo_empleado'";
	$resultemp=mysql_query($sqlemp); $numemp=mysql_num_rows($resultemp);
	if ($numemp>='1') { 
	$nombre_empleado=mysql_result($resultemp,0,'nombre_empleado');$apellido_empleado=mysql_result($resultemp,0,'apellido_empleado');  
	echo $nombre_empleado." ".$apellido_empleado; }?>
    </td>   
    <td id="dato2">
    <?php $proceso_empleado=$row_costos['proceso_empleado']; 	
	$sql2="SELECT * FROM tipo_procesos WHERE id_tipo_proceso=$proceso_empleado";
	$result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
	if ($num2>='1') { $proceso_empleado=mysql_result($result2,0,'nombre_proceso'); 
	echo $proceso_empleado; }?>
    </td>
    <td id="dato2"><?php echo $row_costos['dias_empleado'];?></td>
    <td id="dato2"><?php $costoEmp= $row_costos['costo_empleado'];echo numeros_format($costoEmp) ?></td>
    <td id="dato2"><?php $costoHora=$row_costos['costo_empleado'];echo numeros_format($costoHora) ?></td>
    <td id="dato2">
      <?php $maquinas=$row_costos['proceso_empleado']; 
	$sqlmaq="SELECT COUNT(proceso_maquina) AS maq FROM maquina WHERE proceso_maquina=$maquinas";
	$resultmaq=mysql_query($sqlmaq); 
	$nummaq=mysql_num_rows($resultmaq);
	if ($nummaq>='1') { 
	$Tmaquinas=mysql_result($resultmaq,0,'maq');
	echo $Tmaquinas;
	}
	?></td>
    <td id="dato2" >
    <?php $empleados=$row_costos['proceso_empleado']; 	
	$sqlemp="SELECT COUNT(proceso_empleado) AS empleados FROM TblProcesoEmpleado WHERE proceso_empleado=$empleados";
	$resultemp=mysql_query($sqlemp); $numemp=mysql_num_rows($resultemp);
	if ($numemp>='1') { 
	$cant_empleado=mysql_result($resultemp,0,'empleados'); //cantidad de empleados por proceso 
	echo $cant_empleado; }
	?></td>
    <td id="dato2"><?php $operarioxMaqu=$cant_empleado/$Tmaquinas;echo $operarioxMaqu;//tomados de las dos anteriores columnas?></a></td> 
    <td id="dato2"><?php $operarioxTurnoM=($operarioxMaqu/$valor_pa);echo numeros_format($operarioxTurnoM); ?></td>
    <td id="dato2"><?php $costoxPxLxT=($costoHoraM*$operarioxTurnoM);echo "$  ";echo numeros_format($costoxPxLxT);?></td>  
    </tr>
  <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>