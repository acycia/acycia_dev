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
	//IMPRIME TODO UN AÑO
	$fecha = date('Y-m-d');$nuevafecha = strtotime ( '-1 year' , strtotime ( $fecha ) ) ;$nuevafecha = date ( 'Y-m-d' , $nuevafecha );	
	$fecha1Toda=$nuevafecha;
	$fecha2Toda= date("Y-m-d");
  switch ($tipoListado) {
    case "1":
	mysql_select_db($database_conexion1, $conexion1);
	//Filtra fecha lleno
	if($fecha1Toda != '' && $fecha2Toda != '')
	{	
	$query_costos = "SELECT DATE(Tbl_reg_produccion.fecha_ini_rp) AS FECHA,Tbl_reg_produccion.id_op_rp, Tbl_reg_produccion.id_proceso_rp, Tbl_reg_produccion.int_cod_ref_rp,  Tbl_reg_produccion.int_kilos_prod_rp, Tbl_reg_produccion.int_kilos_desp_rp,   Tbl_reg_produccion.fecha_fin_rp 
FROM Tbl_orden_produccion,Tbl_reg_produccion 
WHERE Tbl_orden_produccion.b_estado_op > 0 AND Tbl_orden_produccion.id_op=Tbl_reg_produccion.id_op_rp AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1Toda'
AND  '$fecha2Toda' and
DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1Toda'
AND  '$fecha2Toda'
GROUP BY Tbl_reg_produccion.int_cod_ref_rp DESC";
	}
	$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
	$row_costos = mysql_fetch_assoc($costos);
	$totalRows_costos = mysql_num_rows($costos);
      break;    
    case "2":
	mysql_select_db($database_conexion1, $conexion1);
	//Filtra fecha lleno
	if($fecha1 != '' && $fecha2 != '')
	{
	$query_costos = "SELECT DATE(Tbl_reg_produccion.fecha_ini_rp) AS FECHA,Tbl_reg_produccion.id_op_rp, Tbl_reg_produccion.id_proceso_rp, Tbl_reg_produccion.int_cod_ref_rp,  Tbl_reg_produccion.int_kilos_prod_rp, Tbl_reg_produccion.int_kilos_desp_rp,   Tbl_reg_produccion.fecha_fin_rp 
FROM Tbl_orden_produccion,Tbl_reg_produccion 
WHERE Tbl_orden_produccion.b_estado_op > 0 AND Tbl_orden_produccion.id_op=Tbl_reg_produccion.id_op_rp AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1'
AND  '$fecha2' and
DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1'
AND  '$fecha2'
GROUP BY Tbl_reg_produccion.int_cod_ref_rp DESC";	
	}
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
    <td colspan="24" nowrap="nowrap" id="dato2">TIEMPOS DE ORDEN DE PRODUCCION POR PROCESO</td>
  </tr>
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">ORDEN P.</td>
    <td nowrap="nowrap" id="titulo4">REF.</td>
    <td nowrap="nowrap" id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap" id="titulo4">FECHA FINAL</td>
    <td nowrap="nowrap" id="titulo4">EXTRUSION/kg</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Muertos</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Preparacion</td>
    <td nowrap="nowrap" id="titulo4">IMPRESION/kg</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Muertos</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Preparacion</td>
    <td nowrap="nowrap" id="titulo4">REFILADO/kg</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Muertos</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Preparacion</td>
    <td nowrap="nowrap" id="titulo4">SELLADO/kg</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Muertos</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Preparacion</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato2"><?php echo $row_costos['id_op_rp'] ?></td>
      <td id="dato3"><?php echo $row_costos['int_cod_ref_rp'];?></td>
      <td nowrap="nowrap" id="dato3"><?php echo $row_costos['FECHA'];?></td>
      <td nowrap="nowrap" id="dato3"><?php echo $row_costos['fecha_fin_rp'];?></td>       
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlex="SELECT SUM(int_kilos_prod_rp) AS kgT FROM Tbl_reg_produccion WHERE `id_op_rp`='$ref' AND DATE(fecha_ini_rp) BETWEEN '$fecha1' AND '$fecha2' AND DATE(fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' AND id_proceso_rp='1'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $kgT_ex=mysql_result($resultex,0,'kgT'); echo  numeros_format($kgT_ex);} if ($kgT_ex==NULL) {echo "0";}
	  ?></td>       
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlex="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='1'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $tHoras_ex=mysql_result($resultex,0,'horasT'); echo  $tHoras_ex; }if ($tHoras_ex==NULL) {echo "00:00:00";}	
	  else { 
	  }?></td>
      <td id="dato3"><?php 
      $totalsec=horadecimal($tHoras_ex);
	  echo $porcscond_ex = $totalsec; ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND fecha_rt='$fecha' AND id_proceso_rt='1'"; 
	  $resultexm=mysql_query($sqlexm); 
	  $numexm=mysql_num_rows($resultexm); 
	  if($numexm >= '1') 
	  { $horasM_ex=mysql_result($resultexm,0,'horasM'); echo $horasM_ex; }if ($horasM_ex==NULL){ echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlexp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND fecha_rtp='$fecha' AND id_proceso_rtp='1'"; 
	  $resultexp=mysql_query($sqlexp); 
	  $numexp=mysql_num_rows($resultexp); 
	  if($numexp >= '1') 
	  { $horasP_ex=mysql_result($resultexp,0,'horasP'); echo $horasP_ex; }if ($horasP_ex==NULL) {echo "0";}
	  ?></td>                        
      <td id="dato3">
	  <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlim="SELECT SUM(int_kilos_prod_rp) AS kgT FROM Tbl_reg_produccion WHERE `id_op_rp`='$ref' AND DATE(fecha_ini_rp) BETWEEN '$fecha1' AND '$fecha2' AND DATE(fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' AND id_proceso_rp='2'"; 
	  $resultim=mysql_query($sqlim); 
	  $numim=mysql_num_rows($resultim); 
	  if($numim >= '1') 
	  { $kgT_im=mysql_result($resultim,0,'kgT'); echo numeros_format($kgT_im); }if ($kgT_im==NULL) {echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlim="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='2'";
	  $resultim=mysql_query($sqlim); 
	  $numim=mysql_num_rows($resultim); 
	  if($numim >= '1') 
	  { $tHoras_im=mysql_result($resultim,0,'horasT'); echo  $tHoras_im;}if ($tHoras_im==NULL) {echo "00:00:00";}
	  ?></td>
      <td id="dato3"><?php 
	  $totalsec=horadecimal($tHoras_im);  
	  echo $porcscond_im = $totalsec; ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlimm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND fecha_rt='$fecha' AND id_proceso_rt='2'"; 
	  $resultimm=mysql_query($sqlimm); 
	  $numimm=mysql_num_rows($resultimm); 
	  if($numimm >= '1') 
	  { $horasM_imm=mysql_result($resultimm,0,'horasM'); echo $horasM_imm; }if ($horasM_imm==NULL) {echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlimp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND fecha_rtp='$fecha' AND id_proceso_rtp='2'"; 
	  $resultimp=mysql_query($sqlimp); 
	  $numimp=mysql_num_rows($resultimp); 
	  if($numimp >= '1') 
	  { $horasP_im=mysql_result($resultimp,0,'horasP'); echo $horasP_im; }if ($horasP_im==NULL) {echo "0";}
	  ?></td>
      <td id="dato3"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlre="SELECT SUM(int_kilos_prod_rp) AS kgT FROM Tbl_reg_produccion WHERE `id_op_rp`='$ref' AND DATE(fecha_ini_rp) BETWEEN '$fecha1' AND '$fecha2' AND DATE(fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' AND id_proceso_rp='3'"; 
	  $resultre=mysql_query($sqlre); 
	  $numre=mysql_num_rows($resultre); 
	  if($numre >= '1') 
	  { $kgT_re=mysql_result($resultre,0,'kgT'); echo numeros_format($kgT_re); }if ($kgT_re==NULL) {echo "0";}
	  ?></td>
      <td id="dato3"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlret="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='3'";
	  $resultret=mysql_query($sqlret); 
	  $numret=mysql_num_rows($resultret); 
	  if($numret >= '1') 
	  { $tHoras_ret=mysql_result($resultret,0,'horasT'); echo  $tHoras_ret; }if ($tHoras_ret==NULL) {echo "00:00:00";}
	  ?></td>
      <td id="dato3"><?php 
	  $totalsec=horadecimal($tHoras_ret);	  
	  echo $porcscond_ret = $totalsec; ?></td>
      <td id="dato3"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlret="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND fecha_rt='$fecha' AND id_proceso_rt='3'";  
	  $resultret=mysql_query($sqlret); 
	  $numret=mysql_num_rows($resultret); 
	  if($numret >= '1') 
	  { $horasM_ret=mysql_result($resultret,0,'horasM'); echo $horasM_ret; }if ($horasM_ret==NULL) {echo "0";}
	  ?></td>
      <td id="dato3"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlrep="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND fecha_rtp='$fecha' AND id_proceso_rtp='3'";  
	  $resultrep=mysql_query($sqlrep); 
	  $numrep=mysql_num_rows($resultrep); 
	  if($numrep >= '1') 
	  { $horasP_rep=mysql_result($resultrep,0,'horasP'); echo $horasP_rep; }if ($horasP_rep==NULL) {echo "0";}
	  ?></td>                    
      <td id="dato3">
	  <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlse="SELECT SUM(int_kilos_prod_rp) AS kgT FROM Tbl_reg_produccion WHERE `id_op_rp`='$ref' AND DATE(fecha_ini_rp) BETWEEN '$fecha1' AND '$fecha2' AND DATE(fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' AND id_proceso_rp='4'"; 
	  $resultse=mysql_query($sqlse); 
	  $numse=mysql_num_rows($resultse); 
	  if($numse >= '1') 
	  { $kgT_se=mysql_result($resultse,0,'kgT'); echo numeros_format($kgT_se); }if ($kgT_se==NULL) {echo "0";}
	  ?></td>
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlse="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='4'";
	  $resultse=mysql_query($sqlse); 
	  $numse=mysql_num_rows($resultse); 
	  if($numse >= '1') 
	  { $tHoras_se=mysql_result($resultse,0,'horasT'); echo  $tHoras_se; }if ($tHoras_se==NULL) {echo "00:00:00";}
	  ?></td>
      <td id="dato3"><?php 
	  $totalsec=horadecimal($tHoras_se);	  
	  echo $porcscond_se = $totalsec; ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlsem="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND fecha_rt='$fecha' AND id_proceso_rt='4'";  
	  $resultsem=mysql_query($sqlsem); 
	  $numsem=mysql_num_rows($resultsem); 
	  if($numsem >= '1') 
	  { $horasM_sem=mysql_result($resultsem,0,'horasM'); echo $horasM_sem; }if ($horasM_sem==NULL) {echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $fecha=$row_costos['fecha_ini_rp'];
	  $sqlsep="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND fecha_rtp='$fecha' AND id_proceso_rtp='4'";  
	  $resultsep=mysql_query($sqlsep); 
	  $numsep=mysql_num_rows($resultsep); 
	  if($numsep >= '1') 
	  { $horasP_se=mysql_result($resultsep,0,'horasP'); echo $horasP_se; }if ($horasP_se==NULL) {echo "0";}
	  ?></td>                                     
    </tr> 
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>