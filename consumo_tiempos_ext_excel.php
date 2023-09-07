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
header('Content-Disposition: attachment; filename="Tiempo Referencia por proceso.xls"');
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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
    //IMPRIME FECHAS SELECCIONADAS
	$fecha1 = $_GET['fecha_ini'];
    $fecha2 = $_GET['fecha_fin'];
	$id_op = $_GET['id_op'];
	$proceso='1';
	//Filtra todos vacios
	if($fecha1 == '' && $fecha2 == '' && $id_op == '')
	{
	$query_costos = "SELECT * FROM Tbl_orden_produccion WHERE b_estado_op > 0 AND DATE(fecha_registro_op) BETWEEN '$fecha1' AND '$fecha2' ORDER BY id_op DESC";	
	}
	//Filtra fecha lleno
	if($fecha1 != '' && $fecha2 != '' && $id_op == '')
	{
	$query_costos = "SELECT * FROM Tbl_orden_produccion WHERE b_estado_op > 0 AND DATE(fecha_registro_op) BETWEEN '$fecha1' AND '$fecha2' ORDER BY id_op DESC";	
	}
	//Filtra todo lleno
	if($fecha1 != '' && $fecha2 != '' && $id_op != '')
	{
	$query_costos = "SELECT * FROM Tbl_orden_produccion WHERE b_estado_op > 0 AND DATE(fecha_registro_op) BETWEEN '$fecha1' AND '$fecha2' AND int_cod_ref_op='$id_op' ORDER BY id_op DESC";	
	}
	$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
	$row_costos = mysql_fetch_assoc($costos);
	$totalRows_costos = mysql_num_rows($costos);	
 
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table id="Exportar_a_Excel" border="1">   
  <tr>
    <td colspan="11" nowrap="nowrap" id="dato2">TIEMPOS DE EXTRUSION</td>
  </tr>
<tr id="tr1">
  <td nowrap="nowrap" id="titulo4">ORDEN.P</td>
    <td nowrap="nowrap" id="titulo4">REF.</td>
     <td nowrap="nowrap" id="titulo4">EXTRUSION/kg</td>
     <td nowrap="nowrap" id="titulo4">DESP.</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas %</td>
    <td nowrap="nowrap" id="titulo4">Horas/Muertas</td>
    <td nowrap="nowrap" id="titulo4">Horas/Prep</td>
    <td nowrap="nowrap" id="titulo4">PROMEDIO</td>
    <td nowrap="nowrap" id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap" id="titulo4">FECHA FINAL</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato3"><?php echo $row_costos['id_op'];?></td>
    <td id="dato3"><?php echo $row_costos['int_cod_ref_op'];?></td>
       <td id="dato3"><?php 
	  $ref=$row_costos['id_op'];
	  $sqlex="SELECT SUM(int_kilos_prod_rp) AS totalKilos,fecha_ini_rp,fecha_fin_rp FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$proceso'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { 
	  $totalkilos=mysql_result($resultex,0,'totalKilos');
	  $fechaIni=mysql_result($resultex,0,'fecha_ini_rp'); 
	  $fechaFin=mysql_result($resultex,0,'fecha_fin_rp');  
	  echo numeros_format($totalkilos);}else{echo "0";} 
	  ?></td>
       <td id="dato3"><?php 
	    $id_op=$row_costos['id_op']; 
	    $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='$proceso'"; 
	    $resultexd=mysql_query($sqlexd); 
	    $numexd=mysql_num_rows($resultexd); 
	    if($numexd >= '1') 
	    { $kilos_exd=mysql_result($resultexd,0,'kgDespe'); echo numeros_format($kilos_exd); }else {echo "0";}
	  ?></td>       
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op'];
	  $sqlex="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$proceso'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $tHoras_ex=mysql_result($resultex,0,'horasT'); echo  $tHoras_ex; }	
	  else { 
	  echo "0";
	  }?></td>
      <td id="dato3"><?php 
      $totalext=horadecimal($tHoras_ex);
	  echo  $totalext; ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op']; 
	  $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND id_proceso_rt='$proceso'"; 
	  $resultexm=mysql_query($sqlexm); 
	  $numexm=mysql_num_rows($resultexm); 
	  if($numexm >= '1') 
	  { 
	  $horasM_ex=mysql_result($resultexm,0,'horasM');echo dosDecimalesSinMiles($horasM_ex/60);}
	  else
	  {echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op']; 
	  $sqlexp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND id_proceso_rtp='$proceso'"; 
	  $resultexp=mysql_query($sqlexp); 
	  $numexp=mysql_num_rows($resultexp); 
	  if($numexp >= '1') 
	  { $horasP_ex=mysql_result($resultexp,0,'horasP'); 
	  echo dosDecimalesSinMiles($horasP_ex/60); }
	  else
	  {echo "0";}
	  ?></td>
      <td id="dato3" nowrap="nowrap"><?php echo dosDecimalesSinMiles($totalkilos/$tHoras_ex);?></td>
      <td id="dato3" nowrap="nowrap"><?php echo quitarHora($fechaIni);?></td>
      <td id="dato3" nowrap="nowrap"><?php echo quitarHora($fechaFin);?></td>                        
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>