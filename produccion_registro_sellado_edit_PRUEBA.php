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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL2 = sprintf("UPDATE TblSelladoRollo SET id_op_r=%s, ref_r=%s, bolsas_r=%s, metro_r=%s, metroIni_r=%s, kilos_r=%s, reproceso_r=%s, rollo_r=%s,  maquina_r=%s, numIni_r=%s, numFin_r=%s, cod_empleado_r=%s, cod_auxiliar_r=%s, turno_r=%s, fechaI_r=%s, fechaF_r=%s, kilopendiente_r=%s, rolloParcial_r=%s WHERE id_r=%s",
                       GetSQLValueString($_POST['id_op_rp'], "int"),
                       GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
                       GetSQLValueString($_POST['bolsa_rp'], "int"),
					   GetSQLValueString($_POST['metro_r2'], "int"),
					   GetSQLValueString($_POST['metroIni_r'], "int"),
					   GetSQLValueString($_POST['int_total_kilos_rp'], "double"),
					   GetSQLValueString($_POST['reproceso'], "double"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
                       GetSQLValueString($_POST['str_maquina_rp'], "int"),
                       GetSQLValueString($_POST['n_ini_rp'], "text"),
                       GetSQLValueString($_POST['n_fin_rp'], "text"),
                       GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
                       GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),
                       GetSQLValueString($_POST['turno_rp'], "int"),
                       GetSQLValueString($_POST['fecha_ini_rp'], "text"),
                       GetSQLValueString($_POST['fecha_fin_rp'], "text"),
					   GetSQLValueString($_POST['kilopendiente'], "double"),
					   GetSQLValueString(isset($_POST['rolloParcial_r']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['id_r'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result12 = mysql_query($updateSQL2, $conexion1) or die(mysql_error()); 	
	
	
    $id_op=$_POST['id_op_rp']; 
	$rolloN=$_POST['rollo_rp'];
	$sqltotal="SELECT MIN(`fechaI_r`) AS fechaini, rollo_r, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`fechaF_r`, `fechaI_r`)))) TotalHoras, SUM(`bolsas_r`) AS BOLSAS, SUM(`metro_r`) AS METROS, ROUND(SUM(`kilos_r`)) AS KILOS FROM `TblSelladoRollo` WHERE id_op_r= '$id_op' AND rollo_r='$rolloN' GROUP BY `rollo_r` ASC";
	$resulttotal= mysql_query($sqltotal);
	$numtotal= mysql_num_rows($resulttotal);
	if($numtotal >='1') {
	//$fechainicial=mysql_result($resulttotal,0,'fechaini');
	$total_horas=mysql_result($resulttotal,0,'TotalHoras');
	$total_bolsas=mysql_result($resulttotal,0,'BOLSAS');
	$total_metros=mysql_result($resulttotal,0,'METROS');
	$total_kilos=mysql_result($resulttotal,0,'KILOS'); 
	 }
   $updateSQL = sprintf("UPDATE Tbl_reg_produccion SET id_proceso_rp=%s, id_op_rp=%s, id_ref_rp=%s, int_cod_ref_rp=%s, version_ref_rp=%s, placa_rp=%s, bolsa_rp=%s, lam1_rp=%s, lam2_rp=%s, turno_rp=%s, rollo_rp=%s, n_ini_rp=%s, n_fin_rp=%s, int_kilos_prod_rp=%s, int_kilos_desp_rp=%s, int_total_kilos_rp=%s, porcentaje_op_rp=%s, int_metro_lineal_rp=%s, int_total_rollos_rp=%s, total_horas_rp=%s, rodamiento_rp=%s, horas_muertas_rp=%s, horas_prep_rp=%s, str_maquina_rp=%s, str_responsable_rp=%s, fecha_ini_rp=%s, fecha_fin_rp=%s, int_kilosxhora_rp=%s, int_metroxmin_rp=%s, int_cod_empleado_rp=%s,int_cod_liquida_rp=%s, kiloFaltante_rp=%s WHERE id_rp=%s",
                       GetSQLValueString($_POST['id_proceso_rp'], "int"),
                       GetSQLValueString($_POST['id_op_rp'], "int"),
					   GetSQLValueString($_POST['id_ref_rp'], "int"),
                       GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
                       GetSQLValueString($_POST['version_ref_rp'], "int"),
					   GetSQLValueString($_POST['placa_rp'], "text"),
					   GetSQLValueString($total_bolsas, "int"),
					   GetSQLValueString($_POST['lam1_rp'], "double"),
					   GetSQLValueString($_POST['lam2_rp'], "double"),
					   GetSQLValueString($_POST['turno_rp'], "int"), 
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['n_ini_rp'], "text"),
					   GetSQLValueString($_POST['n_fin_rp'], "text"),					   
                       GetSQLValueString($_POST['kiloimpre'], "double"),
                       GetSQLValueString($_POST['int_kilos_desp_rp'], "text"),
					   GetSQLValueString($total_kilos, "double"),
					   GetSQLValueString($_POST['porcentaje'], "int"),
					   GetSQLValueString($total_metros, "int"),
					   GetSQLValueString($_POST['int_total_rollos_rp'], "int"),					   
                       GetSQLValueString($total_horas, "text"),
					   GetSQLValueString($_POST['rodamiento_rp'], "text"),
					   GetSQLValueString($_POST['horas_muertas_rp'], "text"), 
					   GetSQLValueString($_POST['horas_prep_rp'], "text"),              
                       GetSQLValueString($_POST['str_maquina_rp'], "text"),
                       GetSQLValueString($_POST['str_responsable_rp'], "text"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"),
                       GetSQLValueString($_POST['fecha_fin_rp'], "date"),
					   GetSQLValueString($_POST['int_kilosxhora_rp'], "double"),
					   GetSQLValueString($_POST['int_metroxmin_rp'], "double"),
 					   GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
					   GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),
					   GetSQLValueString($_POST['kiloFaltante'], "double"),		   
                       GetSQLValueString($_POST['id_rp'], "int"));
				   					   					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());	
  

 
 //DESPERDICIOS Y TIEMPOS
 if (!empty ($_POST['id_rpt'])&&!empty ($_POST['valor_tiem_rt'])){
    foreach($_POST['id_rpt'] as $key=>$v)
    $a[]= $v;
    foreach($_POST['valor_tiem_rt'] as $key=>$v)
    $b[]= $v;
    $c= $_POST['id_op_rp'];	
	
	for($i=0; $i<count($a); $i++) {
		  if(!empty($a[$i])&&!empty($b[$i])){ //no salga error con campos vacios
 $insertSQLt = sprintf("INSERT INTO Tbl_reg_tiempo (id_rpt_rt,valor_tiem_rt,op_rt,int_rollo_rt,id_proceso_rt,fecha_rt) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($a[$i], "int"),
                       GetSQLValueString($b[$i], "int"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso_rp'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultt = mysql_query($insertSQLt, $conexion1) or die(mysql_error());
		  }
	}
}
if (!empty ($_POST['id_rtp'])&&!empty ($_POST['valor_prep_rtp'])){
    foreach($_POST['id_rtp'] as $key=>$n)
    $h[]= $n;
    foreach($_POST['valor_prep_rtp'] as $key=>$n)
    $l[]= $n;
    $c= $_POST['id_op_rp'];	
	
	for($x=0; $x<count($h); $x++) {
		  if(!empty($h[$x])&&!empty($l[$x])){ //no salga error con campos vacios
 $insertSQLp = sprintf("INSERT INTO Tbl_reg_tiempo_preparacion (id_rpt_rtp,valor_prep_rtp,op_rtp,int_rollo_rtp,id_proceso_rtp,fecha_rtp) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($h[$x], "int"),
                       GetSQLValueString($l[$x], "int"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso_rp'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultp = mysql_query($insertSQLp, $conexion1) or die(mysql_error());
		  }
	}
}
if (!empty ($_POST['id_rpd'])&&!empty ($_POST['valor_desp_rd'])){
    foreach($_POST['id_rpd'] as $key=>$k)
    $f[]= $k;
    foreach($_POST['valor_desp_rd'] as $key=>$k)
    $g[]= $k;
    $c= $_POST['id_op_rp'];	
	
	for($s=0; $s<count($f); $s++) {
		  if(!empty($f[$s])&&!empty($g[$s])){ //no salga error con campos vacios
 $insertSQLd = sprintf("INSERT INTO Tbl_reg_desperdicio (id_rpd_rd,valor_desp_rd,op_rd,int_rollo_rd,id_proceso_rd,fecha_rd) VALUES (%s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($f[$s], "int"),
                       GetSQLValueString($g[$s], "double"),
					   GetSQLValueString($c, "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['id_proceso_rp'], "int"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"));
  mysql_select_db($database_conexion1, $conexion1);
  $Resultd = mysql_query($insertSQLd, $conexion1) or die(mysql_error());
		  }
	}
}   				    
  $updateGoTo = "produccion_registro_sellado_total_vista_PRUEBA.php?id_op=" . $_POST['id_op_rp'] . "";// "&id_r=" . $_POST['id_r'] .
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
//INSERT
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
  //ROLLO SELLADO
$colname_rollo_sellado_edit = "-1";
if (isset($_GET['id_r'])) {
  $colname_rollo_sellado_edit = (get_magic_quotes_gpc()) ? $_GET['id_r'] : addslashes($_GET['id_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_sellado_edit = sprintf("SELECT * FROM TblSelladoRollo,Tbl_reg_produccion WHERE Tbl_reg_produccion.id_proceso_rp='4' AND TblSelladoRollo.id_r='%s' AND TblSelladoRollo.id_op_r = Tbl_reg_produccion.id_op_rp AND  TblSelladoRollo.rollo_r=Tbl_reg_produccion.rollo_rp",$colname_rollo_sellado_edit);
$rollo_sellado_edit = mysql_query($query_rollo_sellado_edit, $conexion1) or die(mysql_error());
$row_rollo_sellado_edit = mysql_fetch_assoc($rollo_sellado_edit);
$totalRows_rollo_sellado_edit = mysql_num_rows($rollo_sellado_edit);

/*
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_limpio = sprintf("SELECT * FROM TblSelladoRollo WHERE TblSelladoRollo.id_r=%s  ",$colname_rollo_sellado_edit);
$rollo_limpio = mysql_query($query_rollo_limpio, $conexion1) or die(mysql_error());
$rollo_limpio = mysql_fetch_assoc($rollo_limpio);
$totalRows_rollo_limpio = mysql_num_rows($rollo_limpio);*/

$id_op = $row_rollo_sellado_edit['id_op_r'];
$rolloNum = $row_rollo_sellado_edit['rollo_r'];
 
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='3' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
//CODIGO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado WHERE tipo_empleado IN(7,9) ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);
  
//SOLO EL METRO DE IMPRESION
 mysql_select_db($database_conexion1, $conexion1);
$query_rollo_impresion_edit = sprintf("SELECT * FROM TblImpresionRollo WHERE TblImpresionRollo.id_op_r='$id_op' AND rollo_r= '$rolloNum'",$colname_rollo_impresion_edit);
$rollo_impresion_edit = mysql_query($query_rollo_impresion_edit, $conexion1) or die(mysql_error());
$row_rollo_impresion_edit = mysql_fetch_assoc($rollo_impresion_edit);
$totalRows_rollo_impresion_edit = mysql_num_rows($rollo_impresion_edit);
//CARGA LOS STANDBY 
  mysql_select_db($database_conexion1, $conexion1);
$query_standBy = sprintf("SELECT *, SUM(`valor_tiem_rt`) AS standby  FROM Tbl_reg_tiempo WHERE Tbl_reg_tiempo.op_rt='$id_op' AND Tbl_reg_tiempo.id_proceso_rt='4' AND int_rollo_rt='$rolloNum' AND id_rpt_rt='141' GROUP BY id_rpt_rt ASC",$colname_standBy);
$standBy = mysql_query($query_standBy, $conexion1) or die(mysql_error());
$row_standBy = mysql_fetch_assoc($standBy);
$totalRows_standBy = mysql_num_rows($standBy);
//CARGA LOS TIEMPOS MUERTOS 
 $colname_tiempoMuerto= "-1";
if (isset($id_op)) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $id_op : addslashes($id_op);
}
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT *, valor_tiem_rt AS muertos FROM Tbl_reg_tiempo WHERE op_rt='%s' AND int_rollo_rt = '$rolloNum' AND id_proceso_rt='4' ORDER BY id_rpt_rt ASC",$colname_tiempoMuerto);
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT *, valor_prep_rtp AS preparacion FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='%s' AND int_rollo_rtp = '$rolloNum' AND id_proceso_rtp='4' ORDER BY id_rpt_rtp ASC",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS KILOS DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT *, valor_desp_rd AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd=%s AND int_rollo_rd = $rolloNum AND id_proceso_rd='4' ORDER BY id_rpd_rd ASC",$colname_tiempoMuerto);
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);
//CARGA LOS TIEMPOS KILOS PRODUCIDOS
mysql_select_db($database_conexion1, $conexion1);
$query_producido = sprintf("SELECT *, SUM(`valor_prod_rp`) AS producido FROM  Tbl_reg_kilo_producido WHERE op_rp=%s AND id_proceso_rkp='4' AND int_rollo_rkp = $rolloNum  ORDER BY id_rpp_rp ASC",$colname_tiempoMuerto);//AND id_rpp_rp NOT IN (1406,1407,1655,1656,1657)
$producido = mysql_query($query_producido, $conexion1) or die(mysql_error());
$row_producido = mysql_fetch_assoc($producido);
$totalRows_producido = mysql_num_rows($producido);
//CARGA LOS DINAMICOS
 mysql_select_db($database_conexion1, $conexion1);
$query_tiempo_muertos = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='1' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$tiempo_muertos = mysql_query($query_tiempo_muertos, $conexion1) or die(mysql_error());
$row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos);
$totalRows_tiempo_muertos = mysql_num_rows($tiempo_muertos);

mysql_select_db($database_conexion1, $conexion1);
$query_tiempo_preparacion = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='2' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$tiempo_preparacion = mysql_query($query_tiempo_preparacion, $conexion1) or die(mysql_error());
$row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion);
$totalRows_tiempo_preparacion = mysql_num_rows($tiempo_preparacion);

mysql_select_db($database_conexion1, $conexion1);
$query_desperdicios = "SELECT * FROM Tbl_reg_tipo_desperdicio WHERE Tbl_reg_tipo_desperdicio.id_proceso_rtd='4' AND Tbl_reg_tipo_desperdicio.codigo_rtp='3' ORDER BY Tbl_reg_tipo_desperdicio.nombre_rtp ASC";
$desperdicios = mysql_query($query_desperdicios, $conexion1) or die(mysql_error());
$row_desperdicios = mysql_fetch_assoc($desperdicios);
$totalRows_desperdicios = mysql_num_rows($desperdicios);
 
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM Tbl_orden_produccion,Tbl_referencia  WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script> 
<script type="text/javascript" src="js/ajax.js"> </script>
<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposM() {
	var i=0;
 	var d = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rpt[]");
	file0.options[i] = new Option('T.Muertos','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_tiempo_muertos['nombre_rtp']?>','<?php echo $row_tiempo_muertos['id_rtp']?>');
	i++;
    <?php
        } while ($row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos));
         $rows = mysql_num_rows($tiempo_muertos);
             if($rows > 0) {
                 mysql_data_seek($tiempo_muertos, 0);
               $row_tiempo_muertos = mysql_fetch_assoc($tiempo_muertos);
        }?> 		
	file0.setAttribute("style", "width:60px" );
	d.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_tiem_rt[]");
	file.setAttribute("min", "0" );
	file.setAttribute("placeholder", "Tiempo minutos" );
	file.setAttribute("style", "width:60px" );
	file.setAttribute("onChange", "restakilosT()" );
	file.setAttribute("onBlur", "kilosxHora2()" )
	d.appendChild(file); 
	
	
 	document.getElementById("moreUploads").appendChild(d);
 	upload_number++;
}
</script>
<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposP() {
	var i=0;
 	var e = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rtp[]");
	file0.options[i] = new Option('T.Preparacion','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_tiempo_preparacion['nombre_rtp']?>','<?php echo $row_tiempo_preparacion['id_rtp']?>');
	i++;
    <?php
        } while ($row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion));
         $rows = mysql_num_rows($tiempo_preparacion);
             if($rows > 0) {
                 mysql_data_seek($tiempo_preparacion, 0);
               $row_tiempo_preparacion = mysql_fetch_assoc($tiempo_preparacion);
        }?> 
	file0.setAttribute("style", "width:60px" );
	e.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_prep_rtp[]");
	file.setAttribute("min", "0" );
	file.setAttribute("placeholder", "Tiempo minutos" );
	file.setAttribute("style", "width:60px" );
	file.setAttribute("onChange", "restakilosT()" );
	file.setAttribute("onBlur", "kilosxHora2()");	
	e.appendChild(file); 
	
 	document.getElementById("moreUploads2").appendChild(e);
 	upload_number++;
}
</script>
<script language="javascript" type="text/javascript">
var upload_number=1;
	function tiemposD() {
	var i=0;
 	var f = document.createElement("div");
	var file0 = document.createElement("select");
 	file0.setAttribute("name", "id_rpd[]");
	file0.options[i] = new Option('Desperdicio','');
	i++;
	<?php do { ?>
    file0.options[i] = new Option('<?php echo $row_desperdicios['nombre_rtp']?>','<?php echo $row_desperdicios['id_rtp']?>');
	i++;
    <?php
        } while ($row_desperdicios = mysql_fetch_assoc($desperdicios));
         $rows = mysql_num_rows($desperdicios);
             if($rows > 0) {
                 mysql_data_seek($desperdicios, 0);
               $row_desperdicios = mysql_fetch_assoc($desperdicios);
        }?>
	file0.setAttribute("style", "width:60px" );
	f.appendChild(file0);
 	var file = document.createElement("input");
 	file.setAttribute("type", "number");
 	file.setAttribute("name", "valor_desp_rd[]" );
	file.setAttribute("min", "0" );
	file.setAttribute("step", "0.01" );
	file.setAttribute("placeholder", "Kilos" );
	file.setAttribute("style", "width:60px" );
	file.setAttribute("onChange", "restakilosD();kiloComparativoSell()" );
	file.setAttribute("onBlur", "kilosxHora2()" );	
	f.appendChild(file); 
	
 	document.getElementById("moreUploads3").appendChild(f);
 	upload_number++;
}
</script>
 </head>
<body onload="kilosxHora2();restakilosT();kiloComparativoSell();"><!-- -->
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="produccion_registro_sellado_listado.php">LISTADO SELLADO</a></li>		
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" onSubmit="return (validacion_unodelosdos_sell() || validacion_select_fecha());">
 <table id="tabla2">
  <tr id="tr1">
    <td colspan="4" id="titulo2">REGISTRO DEL ROLLO EN SELLADO
    <?php 
			$id_op=$row_rollo_sellado_edit['id_op_r'];
            $sqlre="SELECT COUNT(DISTINCT rollo_r) AS max_rolloI,SUM(metro_r) as metro_r, SUM(kilos_r) as kilos_r FROM TblImpresionRollo WHERE id_op_r=$id_op"; 
            $resultre=mysql_query($sqlre); 
            $numre=mysql_num_rows($resultre); 
            if($numre >= '1') 
            { 
			$max_rolloI =mysql_result($resultre,0,'max_rolloI'); 
			$metrosI=mysql_result($resultre,0,'metro_r'); 
			} 
			//$nuevosMetros = regladetres($kilosR,$metrosE,$kilosT);//metros nuevos para impresion 		
			?>
      <input type="hidden" name="id_r" id="idrollo" value="<?php echo $row_rollo_sellado_edit['id_r']; ?>" /></td>
  </tr>
  <tr>
    <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
    <td id="dato2"><?php echo $row_rollo_sellado_edit['rollo_r'];if ($max_rolloI!='') {echo " de ".$max_rolloI;} ?></td>
    <td id="dato3"> <!--id_rliqs--><a href="javascript:eliminar1('id_rolloparcial',<?php echo $_GET['id_r'];?> ,'produccion_registro_sellado_listado_add.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR ROLLO LIQUIDADO"
title="ELIMINAR ROLLO LIQUIDADO" border="0" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="produccion_registro_sellado_total_vista.php?id_op=<?php echo $row_rollo_sellado_edit['id_op_r']; ?>"><img src="images/hoja.gif" alt="VISTA SELLADA" title="VISTA SELLADA" border="0" /></a><a href="produccion_registro_sellado_listado.php"><img src="images/opciones.gif" alt="LISTADO SELLADAS"title="LISTADO SELLADAS" border="0" style="cursor:hand;" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
    <td id="dato3">&nbsp;</td>
  </tr>
  <tr id="tr1">
    <td colspan="2"  nowrap="nowrap" id="dato2">ORDEN DE PRODUCCION</td>
    <td id="dato3"> Ingresado por
      <input name="str_responsable_rp" type="text" id="str_responsable_rp" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="15" readonly="readonly"/></td>
  </tr>
  <tr id="tr3">
    <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_rollo_sellado_edit['id_op_r'];?></td>
    <td nowrap="nowrap" id="fuente2"><input name="id_rp" type="hidden" id="id_rp" value="<?php echo $row_rollo_sellado_edit['id_rp']; ?>" /></td> 
  </tr>
  <tr id="tr1">
    <td colspan="2" nowrap="nowrap" id="dato2">REFERENCIA</td>
    <td id="dato2">VERSION</td>
  </tr>
  <tr>
    <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_rollo_sellado_edit['int_cod_ref_rp'];?></td>
    <td nowrap="nowrap" id="numero2"><?php echo $row_rollo_sellado_edit['version_ref_rp'];?></td>
  </tr>
  <tr>
   <td colspan="2" id="dato2"><input type="hidden" name="ancho" id="ancho" style="width:80px" value="<?php echo $row_referencia['ancho_ref'];?>"/>
      <input type="hidden" name="calibre" id="calibre"  style="width:80px" value="<?php echo $row_referencia['calibre_ref'];?>" /></td>
    <td id="dato2">&nbsp;</td>
  </tr> 
  <tr id="tr1">
    <td colspan="4" id="titulo4">DETALLE CONSUMO</td>
  </tr>
  <tr>
    <td colspan="4" id="fuente2">&nbsp; 
      </td>
  </tr>
 <tr>
   <td id="fuente1"><p>Placa Rollo</p>
     <p>
  <input type="text" name="placa_rp" id="placa_rp" style="width:80px" required="required" readonly="readonly" value="<?php echo $row_rollo_sellado_edit['placa_rp'];?>"/>
     </p></td> 
   <td id="fuente1"><p>Peso inicial/Total</p>
     <p>
  <input type="hidden" name="kiloimpre" id="kiloimpre" style="width:80px" value="<?php echo $row_rollo_impresion_edit['kilos_r'];?>"/>
  <input name="int_kilos_prod_rp" type="text" id="int_kilos_prod_rp" min="1.00" step="0.01" style="width:50px" value="<?php echo $row_rollo_sellado_edit['kilos_r']+$row_rollo_sellado_edit['kilopendiente_r']; ?>" required="required"   readonly="readonly"/>
  <input type="number" name="int_total_kilos_rp" id="int_total_kilos_rp" min="0.10" step="any" style="width:60px" required="required" value="<?php echo $row_rollo_sellado_edit['kilos_r'];?>" readonly="readonly" />
     </p></td> 
   <td id="fuente1"><p>Bolsas x Rollo
     </p>
     <p>
       <input type="number" name="bolsa_rp" min="1" id="bolsa_rp" style="width:80px" required="required" onchange="kiloComparativoSell();" value="<?php echo $row_rollo_sellado_edit['bolsas_r'];?>"/>
     </p></td>
   <td id="fuente1"><p>Reproceso</p>
     <p>
       <input name="reproceso" id="reproceso" type="number" min="0.00" step="any" style="width:80px"  required="required" value="<?php echo $row_rollo_sellado_edit['reproceso_r']; ?>" onblur="reprocesoSell();" onchange="kiloComparativoSell();"/>
     </p></td>
 </tr>
 <tr>
   <td id="fuente1">&nbsp;</td>
    <td colspan="2" id="fuente1"> 
      <input type="number" name="kilopendiente" id="kilopendiente" style="width:118px" required="required" min="0.00" step="any" placeholder="total kilo oper" value="<?php echo $row_rollo_sellado_edit['kilopendiente_r'];?>" readonly="readonly"/>
      kilos parcial Pendientes</td>
   <td id="fuente1"><input type="number" name="turno_rp" id="turno_rp" min="1" max="7" step="1" required="required" style="width:80px" value="<?php echo $row_rollo_sellado_edit['turno_r'];?>"/>
     Turno</td>
 </tr>
  <tr>
      <td colspan="4" id="fuente2">&nbsp;</td>
      </tr>
  <tr id="tr1">
    <td id="fuente1">Maquina<strong>
   <!--   <input name="standby" id="standby" type="hidden" value="<?php echo $row_rollo_sellado_edit['standby'];?>" size="5"/>
      <input name="valor_tiem_rt" id="valor_tiem_rt" type="hidden" size="5" value="0"/>
      <input name="valor_prep_rt" id="valor_prep_rt" type="hidden" size="5" value="0"/>-->
    </strong></td>
    <td id="fuente1"><select name="str_maquina_rp" id="maquina" style="width:145px" onblur="validacion_unodelosdos_sell()">
      <?php
do {  
?>
      <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_rollo_sellado_edit['maquina_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
      <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
    </select></td>
    <td id="fuente1"><select name="int_cod_empleado_rp" id="operario"  style="width:120px"onblur="validacion_unodelosdos_sell();" >
     <option value=""<?php if (!(strcmp("", $row_rollo_sellado_edit['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>>Operario</option>
      <?php
do {  
?>
     <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_sellado_edit['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
      <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
    </select></td>
    <td id="fuente1"> 
      <select name="int_cod_liquida_rp" style="width:120px" id="auxiliar" >
        <option value=""<?php if (!(strcmp("", $row_rollo_sellado_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>>Revisor</option>
        <?php
do {  
?>
        <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_sellado_edit['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
        <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
      </select></td>
    </tr>
  <tr>
    <td colspan="2" id="dato1"></td>
    <td colspan="3" id="dato1"></td>
    <td colspan="3" id="dato1"></td>
    <td colspan="2" id="dato1"></td>
  </tr>
  <tr>
    <td id="fuente1">Fecha Inicial</td>
    <td colspan="2" id="fuente1"><input name="fecha_ini_rp" id="fecha_ini_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rollo_sellado_edit['fechaI_r']);?>" size="15" required readonly="readonly" onclick="kilosxHora2();"/></td>
    <td id="fuente1"><input type="number" name="rollo_rp" id="rollo_rp" min="0"step="any" required="required" readonly="readonly" placeholder="Rollos" style="width:46px" value="<?php echo $row_rollo_sellado_edit['rollo_r'];?>"/>
de
  <?php 
	  $id_op_r=$row_rollo_sellado_edit['id_op_r'];
	  $sqlExt="SELECT COUNT(rollo_r) AS rollo_ex FROM TblExtruderRollo WHERE id_op_r='$id_op_r'";
	  $resultExt= mysql_query($sqlExt);
	  $numExt= mysql_num_rows($resultExt);
	  if($numExt >='1')
	  { 
	  $rollo_ex = mysql_result($resultExt, 0, 'rollo_ex'); 
	  }else{echo $rollo_ex='0';}
	     
 	  $id_op_r=$row_rollo_sellado_edit['id_op_r'];
	  $sqlPro="SELECT COUNT(DISTINCT rollo_rp) AS rollo_pro FROM Tbl_reg_produccion WHERE Tbl_reg_produccion.id_op_rp='$id_op_r' AND id_proceso_rp='2'";
	  $resultPro= mysql_query($sqlPro);
	  $numPro= mysql_num_rows($resultPro);
	  if($numPro >='1')
	  { 
	  $rollo_pro = mysql_result($resultPro, 0, 'rollo_pro');//este  dice de cuantos en total 
	  }else{ $rollo_pro='0';}	  
	  if(($rollo_pro+1) > $rollo_ex){$rollos_iguales='0';}// + 1 porque debe ser mayor
	  ?>
  <input name="int_total_rollos_rp" type="number" id="int_total_rollos_rp" placeholder="Rollos" style="width:46px" min="0"step="any" value="<?php echo $row_rollo_sellado_edit['int_total_rollos_rp'];?>" readonly="readonly"/>
Total Rollos </td>
  </tr>
  <tr id="tr1">
    <td id="fuente1">Fecha Final</td>
    <td colspan="2" id="fuente1"><input name="fecha_fin_rp" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" size="15" required  onChange="kilosxHora2()" onBlur="validacion_select_fecha();" value="<?php echo muestradatelocal($row_rollo_sellado_edit['fechaF_r']);//muestradatelocal?>"/></td>
    <td id="fuente1"><p>Metro lineal
  </p>
      <p>
        <input name="metroIni_r" type="number" id="metroIni_r" min="1" style="width:60px" value="<?php echo $METROPEND=($row_rollo_sellado_edit['metroIni_r']+$row_rollo_sellado_edit['metro_r']); ?>" required="required" readonly="readonly" />
        <input name="metro_r" type="number" id="metro_r" min="1" style="width:60px" value="<?php echo $row_rollo_sellado_edit['metro_r'];?>" required="required" />
        <input name="metro_r2" id="metro_r2" type="number" required="required" style="width:50px" min="0" step="any" value="<?php 
			 echo $METROPEND-$row_rollo_sellado_edit['metro_r'];?>"  readonly="readonly"/>
      </p>
      </td>
  </tr>
 <tr>
    <td id="fuente1">Total Horas Trabajadas</td>
    <td colspan="2" id="fuente1">
      <input name="total_horas_rp" id="total_horas_rp" type="text" required="required" readonly="readonly" placeholder="total horas" value="<?php echo $row_rollo_sellado_edit['total_horas_rp'];?>" size="15"/>
      <input name="rodamiento_rp" id="tiempoOptimo_rp" type="hidden" size="15" onclick="kilosxHora2();" required="required" placeholder="rodamientos"  value="<?php echo $row_rollo_sellado_edit['rodamiento_rp'];?>" readonly="readonly"/></td>
    <td id="fuente1"><!--<input type="number" name="bolsaRep_rp" id="bolsaRep_rp" style="width:116px" onclick="kiloComparativoSell()" readonly="readonly"/>
      Bolsas Reproceso--></td>
  </tr> 
  <tr id="tr1">
    <td id="fuente1">Numeracion Inicial</td>
    <td colspan="2" id="fuente1"><input type="text" name="n_ini_rp" id="n_ini_rp" size="15" required="required" onBlur="conMayusculas(this);" value="<?php echo $row_rollo_sellado_edit['numIni_r'];?>"/>
      <input id="porcentaje" name="porcentaje" type="hidden" value="<?php echo $row_rollo_sellado_edit['porcentaje_op_rp']; ?>" min="0" max="100" step="1" style="width:40px" required="required" readonly="readonly"/></td>
    <td id="fuente1"><input type="text" name="n_fin_rp" id="n_fin_rp" style="width:116px" required="required" onBlur="conMayusculas(this)" value="<?php echo $row_rollo_sellado_edit['numFin_r'];?>"/> 
      Numeracion Final </td>
  </tr>
  <tr>
    <td id="fuente1">Desperdicio </td>
    <td colspan="2" id="fuente1"> 
      <input type="text" name="int_kilos_desp_rp" id="int_kilos_desp_rp" min="0" step="any" required="required" size="7" placeholder="Desp.oper" value="<?php if($row_rollo_sellado_edit['int_kilos_desp_rp"']==''){echo "0";}else{echo $row_rollo_sellado_edit['int_kilos_desp_rp"'];}?>" readonly="readonly" />
      <input type="number" name="kiloFaltante" id="kiloFaltante" style="width:50px" required="required" min="0.00" step="any" placeholder="total kilo oper" value="<?php echo $row_rollo_sellado_edit['kilopendiente_r'];?>" readonly="readonly"/></td>
    <td id="fuente1"><input name="int_kilosxhora_rp" type="number" required="required" id="int_kilosxhora_rp" min="0.10" step="any" value="<?php echo $row_rollo_sellado_edit['int_kilosxhora_rp'];?>" style="width:116px" onclick="kilosxHora2();" readonly="readonly"/>
      Kilos*Hora</td>
  </tr>
  <tr>
    <td id="fuente1">Rollo Parcial</td>
    <td id="fuente1"><input type="checkbox" name="rolloParcial_r" id="rolloParcial_r" onchange="kiloComparativoSell();" <?php if (!(strcmp($row_rollo_sellado_edit['rolloParcial_r'],1))) {echo "checked=\"checked\"";} ?> value="0" class="largerCheckbox"/> 
      </td>
    <td id="fuente1">&nbsp;      </td>
    <td id="dato4">&nbsp;</td>
  </tr>
    <tr>
    <td id="dato4">&nbsp;</td>
    <td id="dato4">&nbsp;</td>
    <td id="dato4">&nbsp;</td>
    <td id="dato4"><input type="submit" name="ENVIAR" id="ENVIAR" value="EDITAR" /></td>
  </tr>
  <tr id="tr1">
            <td id="dato1">&nbsp;</td>
            <td id="dato1">Desperdicios</td>
            <td id="dato1">Tiempos Muertos</td>
            <td id="dato1">Tiempos Preparacion</td>
           </tr>
          <tr>
          <td id="dato1">&nbsp;</td>
          <td id="dato1"><input type="button" name="button3" id="button3" value="Crear otra fila" onClick="if(form1.bolsa_rp.value=='' || form1.fecha_ini_rp.selectedIndex=='' || form1.fecha_fin_rp.value=='') { alert('DEBE AGREGAR BOLSAS Y FECHAS PARA GUARDAR LOS DESPERDICIOS')}else{tiemposD()}" style="width:125px"/></td>
          <td id="dato1"><input type="button" name="button" id="button" value="Crear otra fila" onClick="if(form1.bolsa_rp.value=='' || form1.fecha_ini_rp.selectedIndex=='' || form1.fecha_fin_rp.value=='') { alert('DEBE AGREGAR BOLSAS Y FECHAS PARA GUARDAR LOS TIEMPOS MUERTOS')}else{tiemposM()}" style="width:125px"/></td>
          <td  id="dato1"><input type="button" name="button2" id="button2" value="Crear otra fila" onClick="if(form1.bolsa_rp.value=='' || form1.fecha_ini_rp.selectedIndex=='' || form1.fecha_fin_rp.value=='') { alert('DEBE AGREGAR BOLSAS Y FECHAS PARA GUARDAR LOS TIEMPOS DE PREPARACION')}else{tiemposP()}" style="width:125px"/></td> 
  </tr>
  <tr>
           <td id="dato1" ><!--<div id="moreUploads4"></div>--></td>
            <td id="dato1"><div id="moreUploads3"></div></td>
            <td id="dato1"><div id="moreUploads" ></div></td>
            <td id="dato1" ><div id="moreUploads2"></div></td>
            
    </tr>           
    <tr>
     <td colspan="2" id="dato1"></td>
    <td colspan="2" id="dato1"></td> 
  </tr>
  <tr id="tr1">
    <td colspan="4" id="titulo4">CONSUMOS</td>
  </tr>
  <!--<tr>
    <td colspan="13" id="fuente2"><a href="javascript:verFoto('produccion_regist_sellado_kilos_prod.php?id_op=<?php echo $row_rollo_sellado_edit['id_op_r'] ?>&rollo=<?php echo $row_rollo_sellado_edit['rollo_rp']?>&fecha=<?php echo $row_rollo_sellado_edit['fecha_ini_rp']?>','820','470')">
      <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Detalle consumo"/>
      </a><a href="javascript:verFoto('produccion_registro_sellado_detalle_add.php?id_op=<?php echo $row_rollo_sellado_edit['id_op_r'] ?>&rollo=<?php echo $row_rollo_sellado_edit['rollo_rp']?>&fecha=<?php echo $row_rollo_sellado_edit['fecha_ini_rp']?>','820','270')">
        <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Tiempos Desperdicio"/>
        </a>
      <input type="button" name="check_sh" id="check_sh2" value="Mostrar" onclick="mostrardiv1()"/>
      <input type="button" value="Ocultar" onclick="ocultardiv1()" /></td>
  </tr>-->
  <tr>
    <td colspan="4" id="fuente2">&nbsp;</td>
    </tr>
      <?php if($row_standBy['id_rpt_rt']!='') {?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td nowrap id="detalle2"><strong>Fin de Semana - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Fin de Semana - Minutos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA </strong></td>
      </tr>
      <?php  for($s=0;$s<=$totalRows_standBy-1;$s++) { ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td id="fuente1">
      <?php  
	  $id_stand=mysql_result($standBy,$s,id_rpt_rt); 
	  $sqlstand="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_stand'";
	  $resultstand= mysql_query($sqlstand);
	  $numstand = mysql_num_rows($resultstand);
	  if($numstand >='1')
	  {echo $Nombrestandby = mysql_result($resultstand, 0, 'nombre_rtp'); }?></td>
        <td id="fuente1"><?php $varST=mysql_result($standBy,$s,standby);echo $varST; $totalST+=$varST; ?></td>
        <td id="fuente1"><!--ELIMINAR ESTA FUNCION eliminar_rts-->
        <a href="javascript:eliminar_rts('id_rts',<?php $delrt=mysql_result($standBy,$k,id_rt); echo $delrt;?>,'id_rs',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit_PRUEBA.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?> 
      <tr>
      <td id="fuente1">&nbsp;</td>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
            <?php if($totalST!=''){echo $totalST;}else{echo "0";}  ?>
            = <?php echo redondear_decimal($totalST/60); ?> Horas</strong></td>
          <td id="fuente1">&nbsp;</td>
          </tr>   
    <?php } ?>  
      <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td nowrap id="detalle2"><strong>Tiempos Muertos - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Tiempos Muertos - Minutos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($k=0;$k<=$totalRows_tiempoMuerto-1;$k++) { ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td id="fuente1">
          <?php $id1=mysql_result($tiempoMuerto,$k,id_rpt_rt); 
	  $id_tm=$id1;
	  $sqltm="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
	  $resulttm= mysql_query($sqltm);
	  $numtm= mysql_num_rows($resulttm);
	  if($numtm >='1')
	  { 
	  $nombre1 = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombre1; }?></td>
        <td id="fuente1"><?php $var1=mysql_result($tiempoMuerto,$k,muertos);echo $var1; $totalTM+=$var1; ?>
        <!--<input name="standby[]" id="standby[]" type="hidden" value="<?php  echo $nombre1; ?> "/>--></td>
        <td id="fuente1"><a href="javascript:eliminar5('id_rts',<?php $delrt=mysql_result($tiempoMuerto,$k,id_rt); echo $delrt; ?>,'id_rs',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit_PRUEBA.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
            <?php if($totalTM!=''){echo $totalTM;}else{echo "0";}  ?>
            </strong>           <strong>
            <input name="valor_tiem_rt[]" type="hidden" id="valor_tiem_rt[]" value="<?php echo $totalTM; ?>" size="6" />
            </strong></td>
          <td id="fuente1">&nbsp;</td>
          </tr>
      <?php } ?>
      <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td nowrap id="detalle2"><strong>Tiempos Preparacion - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Tiempos Preparacion - Minutos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($x=0;$x<=$totalRows_tiempoPreparacion-1;$x++) { ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td id="fuente1"><?php $id2=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); 
	  $id_rtp=$id2;
	  $sqlrtp="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
	  $resultrtp= mysql_query($sqlrtp);
	  $numrtp= mysql_num_rows($resultrtp);
	  if($numrtp >='1')
	  { 
	  $nombre2 = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombre2; }?></td>
        <td id="fuente1"><?php $var2=mysql_result($tiempoPreparacion,$x,preparacion); echo $var2;$totalTP+=$var2; ?></td>
        <td id="fuente1"><a href="javascript:eliminar5('id_rps',<?php $delrp=mysql_result($tiempoPreparacion,$x,id_rt); echo $delrp; ?>,'id_rs',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit_PRUEBA.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a>
         </td>
      </tr>
      <?php } ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
			<?php if($totalTP!=''){echo $totalTP;}else{echo "0";}  ?>
            </strong>           <strong>
            <input name="valor_prep_rtp[]" type="hidden" id="valor_prep_rtp[]" value="<?php echo $totalTP; ?>" size="6" />
            </strong></td>
          <td id="fuente1">&nbsp;</td>
          </tr>
      <?php } ?>
      <?php if($row_desperdicio['id_rpd_rd']!='') {?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td nowrap id="detalle2"><strong>Desperdicios - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Desperdicios - Kilos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($i=0;$i<=$totalRows_desperdicio-1;$i++) { ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td id="fuente1"><?php $id3=mysql_result($desperdicio,$i,id_rpd_rd); 
	  $id_rpd=$id3;
	  $sqlrtd="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
	  $resultrtd= mysql_query($sqlrtd);
	  $numrtd= mysql_num_rows($resultrtd);
	  if($numrtd >='1')
	  { 
	  $nombre3 = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombre3; }?></td>
        <td id="fuente1"><?php $var3=mysql_result($desperdicio,$i,desperdicio); echo $var3; $totalTD+=$var3;?></td>
        <td id="fuente1"><a href="javascript:eliminar5('id_rds',<?php $delrd=mysql_result($desperdicio,$i,id_rd); echo $delrd; ?>,'id_rs',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit_PRUEBA.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
            <?php if($totalTD!=''){echo $totalTD;}else{echo "0";}  ?>
            <input name="valor_desp_rd[]" type="hidden" id="valor_desp_rd[]" value="<?php echo $totalTD; ?>" size="6"/>
            </strong>           </td>
          <td id="fuente1">&nbsp;</td>
          </tr>
      <?php } ?>
      <?php if($row_producido['id_rpp_rp']!='') {?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td nowrap id="detalle2"><strong>Insumos  - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Mts - Kilos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($y=0;$y<=$totalRows_producido-1;$y++) { ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
        <td id="fuente1"><?php $id4=mysql_result($producido,$y,id_rpp_rp); 
	  $id_rpp=$id4;
	  $sqlri="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$id_rpp' AND clase_insumo IN(1,2,5)";
	  $resultri= mysql_query($sqlri);
	  $numri= mysql_num_rows($resultri);
	  if($numri >='1')
	  { 
	  $nombre4 = mysql_result($resultri, 0, 'descripcion_insumo'); echo $nombre4; }?></td> 
        <td id="fuente1"><?php $var4=mysql_result($producido,$y,producido); echo $var4; $totalMM+=$var4;?></td>
        <td id="fuente1"><a href="javascript:eliminar_rts('id_ips',<?php $delip=mysql_result($producido,$y,id_rkp); echo $delip; ?>,'id_rs',<?php echo $row_rollo_sellado_edit['id_r']; ?>,'produccion_registro_sellado_edit_PRUEBA.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <tr>
      <td id="fuente1">&nbsp;</td>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
            <?php if($totalMM!=''){echo $totalMM;}else{echo "0";}  ?>
            <input name="valor_prod_rp" type="hidden" id="valor_prod_rp" value="<?php echo $totalMM; ?>" size="6" onblur="getSumP();"/>
            </strong>           </td>
          <td id="fuente1">&nbsp;</td>
          </tr>
      <?php } ?>    

  <tr>
    <td colspan="4" id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4"><!--tabla de caracteristicas y temperaturas--></td>
  </tr>
  <tr id="tr1">
    <td colspan="4" id="dato2"><strong>
      <input type="hidden" name="horas_muertas_rp" id="horasmuertas"  size="12"  value="<?php echo $row_rollo_sellado_edit['horas_muertas_rp']; ?>" />
      <input type="hidden" name="horas_prep_rp" id="horasprep"  size="12" value="<?php echo $row_rollo_sellado_edit['horas_prep_rp']; ?>" />
      <input name="id_op_rp" type="hidden" id="id_op_rp" value="<?php echo $row_rollo_sellado_edit['id_op_rp']; ?>" />
      <input name="id_ref_rp" type="hidden" id="id_ref_rp" value="<?php echo $row_referencia['id_ref']; ?>" />
      <input name="int_cod_ref_rp" type="hidden" id="int_cod_ref_rp" value="<?php echo $row_rollo_sellado_edit['int_cod_ref_rp']; ?>" />
      <input name="version_ref_rp" type="hidden" id="version_ref_rp" value="<?php echo $row_rollo_sellado_edit['version_ref_rp']; ?>" />
      <input name="id_proceso_rp" type="hidden" id="id_proceso_rp" value="4" />
      <input name="int_metroxmin_rp" id="metroxmin" type="hidden" size="5" value="<?php echo $row_rollo_sellado_edit['int_metroxmin_rp'];?>"/>
      <input type="hidden" name="MM_update" value="form1" />
      </strong></td>
  </tr>
</table>
 
  </form>
  </td></tr></table>
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
mysql_free_result($orden_produccion);
mysql_free_result($maquinas);
mysql_free_result($tiempoMuerto);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($producido);
mysql_free_result($totalKilos);
mysql_free_result($codigo_empleado);
?>
