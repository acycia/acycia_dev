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
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {	
	//UPDATE MEZCLA DE IMPRESION
	$pmi=($_POST['id_pmi']);
    foreach($pmi as $key=>$v)
    $a[]= $v;
	
	$id=($_POST['id']);
    foreach($id as $key=>$v)
    $b[]= $v;
	
	$valor=($_POST['valor']);
    foreach($valor as $key=>$v)
    $c[]= $v;
		
	for($x=0; $x<count($b); $x++) 
    {
		//if($a[$x]!=''&&$b[$x]!=''&&$c[$x]!=''){			 
  $updateSQL = sprintf("UPDATE Tbl_produccion_mezclas_impresion SET fecha_registro_pmi=%s, str_registro_pmi=%s, id_i_pmi=%s,  str_valor_pmi=%s,  observ_pmi=%s WHERE id_pmi=%s",                      
                       GetSQLValueString($_POST['fecha_registro_pmi'], "date"),
                       GetSQLValueString($_POST['str_registro_pmi'], "text"),
					   GetSQLValueString($b[$x], "text"),
					   GetSQLValueString($c[$x], "text"),
                       GetSQLValueString($_POST['observ_pmi'], "text"),
                       GetSQLValueString($a[$x], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
	}//llave de for    
	//}	
	//UPDATE CARACTERISTICAS VALOR
	$id_cv=($_POST['id_cv']);
    foreach($id_cv as $key=>$v)
    $d[]= $v;
	
	$valor_cv=($_POST['valor_cv']);
    foreach($valor_cv as $key=>$v)
    $e[]= $v;
	
	for($x=0; $x<count($e); $x++){	
	if($d[$x]!=''&&$e[$x]!=''){		
  $updateSQL2 = sprintf("UPDATE Tbl_caracteristicas_valor SET str_valor_cv=%s, fecha_registro_cv=%s, str_registro_cv=%s WHERE id_cv=%s",
					   GetSQLValueString($e[$x], "text"),
					   GetSQLValueString($_POST['fecha_registro_pmi'], "date"),
					   GetSQLValueString($_POST['str_registro_pmi'], "text"),
					   GetSQLValueString($d[$x], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());
	   }//llave de for
	}//if
     $updateGoTo = "produccion_registro_impresion_vista.php?id_ref=" . $_POST['id_ref'] . "&id_rp=" . $_POST['id_rp'] ."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));		
}	//FIN SI UPDATE FORM 2
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {	
  $updateSQL3 = sprintf("UPDATE Tbl_reg_produccion SET id_proceso_rp=%s, id_op_rp=%s, int_cod_ref_rp=%s, version_ref_rp=%s, rollo_rp=%s, int_kilos_prod_rp=%s, int_kilos_desp_rp=%s, int_total_kilos_rp=%s, int_totalKilos_tinta_rp=%s, porcentaje_op_rp=%s, int_metro_lineal_rp=%s, int_total_rollos_rp=%s, total_horas_rp=%s, rodamiento_rp=%s, horas_muertas_rp=%s, horas_prep_rp=%s, str_maquina_rp=%s, str_responsable_rp=%s, fecha_ini_rp=%s, fecha_fin_rp=%s, int_kilosxhora_rp=%s,int_metroxmin_rp=%s, int_cod_empleado_rp=%s, int_cod_liquida_rp=%s WHERE id_rp=%s",
                       GetSQLValueString($_POST['id_proceso_rp'], "int"),
                       GetSQLValueString($_POST['id_op_rp'], "int"),
                       GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
                       GetSQLValueString($_POST['version_ref_rp'], "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
                       GetSQLValueString($_POST['int_kilos_prod_rp'], "double"),
                       GetSQLValueString($_POST['int_kilos_desp_rp'], "double"),
					   GetSQLValueString($_POST['int_total_kilos_rp'], "double"),
					   GetSQLValueString($_POST['int_totalKilos_tinta_rp'], "double"),
					   GetSQLValueString($_POST['porcentaje'], "int"),
					   GetSQLValueString($_POST['int_metro_lineal_rp'], "int"),
					   GetSQLValueString($_POST['int_total_rollos_rp'], "int"),	
                       GetSQLValueString($_POST['total_horas_rp'], "text"),
					   GetSQLValueString($_POST['tiempoOptimo_rp'], "text"),
					   GetSQLValueString($_POST['valor_tiem_rt'], "text"), 
					   GetSQLValueString($_POST['valor_prep_rtp'], "text"),              
                       GetSQLValueString($_POST['str_maquina_rp'], "text"),
                       GetSQLValueString($_POST['str_responsable_rp'], "text"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"),
                       GetSQLValueString($_POST['fecha_fin_rp'], "date"),
					   GetSQLValueString($_POST['int_kilosxhora_rp'], "double"),
					   GetSQLValueString($_POST['int_metroxmin_rp'], "double"),	
					   GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
					   GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),
					   GetSQLValueString($_POST['id_rp'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($updateSQL3, $conexion1) or die(mysql_error());		

     $updateGoTo = "produccion_registro_impresion_vista.php?id_ref=" . $_POST['id_ref_rp'] . "&id_rp=" . $_POST['id_rp'] ."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));		 
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
//EDITAR REGISTRO
$colname_rp= "-1";
if (isset($_GET['id_rp'])) {
  $colname_rp = (get_magic_quotes_gpc()) ? $_GET['id_rp'] : addslashes($_GET['id_rp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rp = sprintf("SELECT * FROM Tbl_reg_produccion WHERE id_rp=%s AND id_proceso_rp='2'",$colname_rp,$colname_rp_f);
$rp_edit= mysql_query($query_rp, $conexion1) or die(mysql_error());
$row_rp_edit = mysql_fetch_assoc($rp_edit);
$totalRows_rp_edit = mysql_num_rows($rp_edit);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='2' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);

$colname_ref = "-1";
if (isset($_GET['id_op'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_orden_produccion, Tbl_referencia WHERE Tbl_orden_produccion.id_op=%s AND Tbl_orden_produccion.int_cod_ref_op=Tbl_referencia.cod_ref",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
//SUMA TOTAL DE KILOS EXTRUIDOS POR O.P
$colname_totalKilos= "-1";
if (isset($_GET['id_op'])) {
  $colname_totalKilos = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_totalKilos = sprintf("SELECT * FROM Tbl_reg_produccion WHERE  id_op_rp=%s AND id_proceso_rp='2' ORDER BY rollo_rp DESC",$colname_totalKilos);
$totalKilos = mysql_query($query_totalKilos, $conexion1) or die(mysql_error());
$row_totalKilos = mysql_fetch_assoc($totalKilos);
$totalRows_totalKilos = mysql_num_rows($totalKilos);
//CARGA LOS TIEMPOS MUERTOS 
$rolloNum=$row_rp_edit['rollo_rp'];
 $colname_tiempoMuerto= "-1";
if (isset($_GET['id_op'])) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT *, SUM(`valor_tiem_rt`) AS muertos FROM Tbl_reg_tiempo WHERE op_rt=%s AND int_rollo_rt = $rolloNum AND id_proceso_rt='2' GROUP BY id_rpt_rt ASC",$colname_tiempoMuerto);
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT *, SUM(`valor_prep_rtp`) AS preparacion FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=%s AND int_rollo_rtp = $rolloNum AND id_proceso_rtp='2' GROUP BY id_rpt_rtp ASC",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS KILOS DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT *, SUM(`valor_desp_rd`) AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd=%s AND int_rollo_rd = $rolloNum AND id_proceso_rd='2' GROUP BY `id_rpd_rd` ASC",$colname_tiempoMuerto);
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);
//CARGA LOS TISTAS UTILIZADAS
mysql_select_db($database_conexion1, $conexion1);
$query_totalTintas = sprintf("SELECT *, SUM(`valor_prod_rp`) AS tintas FROM Tbl_reg_kilo_producido WHERE  op_rp=%s AND id_proceso_rkp='2' GROUP BY id_rpp_rp ASC",$colname_tiempoMuerto);
$totalTintas = mysql_query($query_totalTintas, $conexion1) or die(mysql_error());
$row_totalTintas = mysql_fetch_assoc($totalTintas);
$totalRows_totalTintas = mysql_num_rows($totalTintas);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//LLAMA LAS UNIDADES DE IMPRESION
$colname_caract = "-1";
if (isset($_GET['id_op'])) {
  $colname_caract  = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_caract_valor = sprintf("SELECT * FROM Tbl_orden_produccion ,Tbl_caracteristicas, Tbl_caracteristicas_valor WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op= Tbl_caracteristicas_valor.id_ref_cv AND Tbl_caracteristicas.id_c=Tbl_caracteristicas_valor.id_c_cv AND Tbl_caracteristicas.proceso_c='2' 
ORDER BY Tbl_caracteristicas_valor.id_cv ASC",$colname_caract);
$caract_valor = mysql_query($query_caract_valor, $conexion1) or die(mysql_error());
$row_caract_valor = mysql_fetch_assoc($caract_valor);
$totalRows_caract_valor = mysql_num_rows($caract_valor);

$id_ref=$row_rp_edit['id_ref_rp'];//PARA LAS UNIDADES
//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 1
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_uno = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='1' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m";
$unidad_uno = mysql_query($query_unidad_uno, $conexion1) or die(mysql_error());
$row_unidad_uno = mysql_fetch_assoc($unidad_uno);
$totalRows_unidad_uno = mysql_num_rows($unidad_uno);
//CARGA UNIDADES
 mysql_select_db($database_conexion1, $conexion1);
$query_unidad_dos = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='2' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m";
$unidad_dos = mysql_query($query_unidad_dos, $conexion1) or die(mysql_error());
$row_unidad_dos = mysql_fetch_assoc($unidad_dos);
$totalRows_unidad_dos = mysql_num_rows($unidad_dos);
//CARGA UNIDAD 3
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_tres = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='3' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m";
$unidad_tres = mysql_query($query_unidad_tres, $conexion1) or die(mysql_error());
$row_unidad_tres = mysql_fetch_assoc($unidad_tres);
$totalRows_unidad_tres = mysql_num_rows($unidad_tres);
//CARGA UNIDAD 4
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cuatro = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='4' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m";
$unidad_cuatro = mysql_query($query_unidad_cuatro, $conexion1) or die(mysql_error());
$row_unidad_cuatro = mysql_fetch_assoc($unidad_cuatro);
$totalRows_unidad_cuatro = mysql_num_rows($unidad_cuatro);
//CARGA UNIDAD 5
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cinco = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='5' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m";
$unidad_cinco = mysql_query($query_unidad_cinco, $conexion1) or die(mysql_error());
$row_unidad_cinco = mysql_fetch_assoc($unidad_cinco);
$totalRows_unidad_cinco = mysql_num_rows($unidad_cinco);
//CARGA UNIDAD 6
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_seis = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='6' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m";
$unidad_seis = mysql_query($query_unidad_seis, $conexion1) or die(mysql_error());
$row_unidad_seis = mysql_fetch_assoc($unidad_seis);
$totalRows_unidad_seis = mysql_num_rows($unidad_seis);
//CARGA UNIDAD 7
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_siete = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='7' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m";
$unidad_siete = mysql_query($query_unidad_siete, $conexion1) or die(mysql_error());
$row_unidad_siete = mysql_fetch_assoc($unidad_siete);
$totalRows_unidad_siete = mysql_num_rows($unidad_siete);
//CARGA UNIDAD 8
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_ocho = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='8' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m";
$unidad_ocho = mysql_query($query_unidad_ocho, $conexion1) or die(mysql_error());
$row_unidad_ocho = mysql_fetch_assoc($unidad_ocho);
$totalRows_unidad_ocho = mysql_num_rows($unidad_ocho);
//CODIGO EMPLEADO
/*mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado WHERE tipo_empleado IN(5,10) ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);*/
$row_codigo_empleado = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(5,10) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
  
$row_revisor = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(5,10) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
//MEZCLA IMPRESION
mysql_select_db($database_conexion1, $conexion1);
$query_mezcla = ("SELECT * FROM Tbl_produccion_mezclas_impresion");
$mezcla = mysql_query($query_mezcla, $conexion1) or die(mysql_error());
$row_mezcla = mysql_fetch_assoc($mezcla);
$totalRows_mezcla = mysql_num_rows($mezcla);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript"> 
function demoShow()
{
	document.getElementsByName("check").style.display="hidden";
<!--document.form1.check_sh.disabled= true
<!--document.form1.liquida.visible= true -->
}
</script>

</head>
<body onload="kilosxHora2();getSumD();getSumT();getSumK();">
<div align="center">
<table align="center" id="tabla">
<tr align="center">
<td>
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
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="produccion_registro_impresion_listado.php">LISTADO IMPRESION</a></li>		
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" onSubmit="return (tintasVacio() && validacion_unodelosdos_imp() && validacion_select_fecha())">
    <table id="tabla2">
        <tr id="tr1">
          <td colspan="10" id="titulo2">REGISTRO DE IMPRESION</td>
        </tr>
        <tr>
          <td width="137" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
          <td colspan="7" id="dato3"><a href="javascript:eliminar1('id_rliq',<?php echo $_GET['id_rp']; ?>,'produccion_impresion_listado_rollos.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a><a href="produccion_registro_impresion_vista.php?id_op=<?php echo $row_rp_edit['id_op_rp']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a>
          <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
        </tr>
        <tr id="tr1">
          <td width="182" colspan="2" nowrap="nowrap" id="dato2">ORDEN DE PRODUCCION</td>
          <td colspan="4" id="dato3"> Ingresado por
            <input name="str_responsable_rp" type="text" id="str_responsable_rp" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
        </tr>
        <tr id="tr3">
          <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_rp_edit['id_op_rp'];?></td>
          <td width="126" colspan="2" nowrap="nowrap" id="fuente4"><input name="id_rp" type="hidden" value="<?php echo $row_rp_edit['id_rp']; ?>" /> </td>
          <td width="235" id="fuente4">&nbsp;</td> 
        </tr>
        <tr id="tr1">
          <td colspan="2" nowrap="nowrap" id="dato2">REFERENCIA</td>
          <td colspan="4" id="dato2">VERSION</td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_rp_edit['int_cod_ref_rp'];?></td>
          <td colspan="4" nowrap="nowrap" id="numero2"><?php echo $row_rp_edit['version_ref_rp'];?></td>
        </tr>
        <tr>
          <td colspan="2" id="dato1">&nbsp;</td>
          <td colspan="4" id="dato1">&nbsp;</td>
        </tr>
        <tr id="tr1">
          <td colspan="10" id="titulo4">DETALLE</td>
        </tr>
        <tr >
          <td id="fuente1">Kilos de Extruder</td>
          <td id="fuente1"><input type="number" name="int_kilos_prod_rp" id="int_kilos_prod_rp" min="0" step="any" required="required" readonly="readonly" style="width:80px" autofocus="autofocus"  value="<?php echo $row_rp_edit['int_kilos_prod_rp'];?>" onchange="kilosxHora2();"/></td>
          <td id="fuente1"> Kilos Desperdiciados Materia Prima </td>
          <td colspan="2" id="fuente1"><?php 
		  $id_op=$row_ref['id_op'];
		  $rollo_rp=$row_rp_edit['rollo_op'];
		  $sqldes="SELECT COUNT(valor_desp_rd) AS desp FROM Tbl_reg_desperdicio WHERE op_rd=$id_op AND int_rollo_rd=$rollo_rp AND id_proceso_rd='2'"; 
            $resultdes=mysql_query($sqldes); 
            $numdes=mysql_num_rows($resultdes); 
            if($numdes >= '1') 
            {$Desper=mysql_result($resultdes,0,'desp');
			}?>            
            <input type="number" name="int_kilos_desp_rp" id="int_kilos_desp_rp" min="0"step="any" required="required" style="width:80px"  onclick="getSumD();getSumT();"  value="<?php echo $row_rp_edit['int_kilos_desp_rp']; ?>"/></td>
          <td id="fuente1">Kilos Reales: </td>
          <td colspan="3" id="fuente1"><input name="int_total_kilos_rp" type="number" required="required" id="int_total_kilos_rp"  style="width:80px" min="0"step="any"  onclick="getSumT();" value="" readonly="readonly"/></td>
        </tr>
        <?php if($row_totalTintas['op_rp']!='') {?>
        <tr >
          <td id="fuente1">Peso Solvente y tintas Klg</td>
          <td id="fuente1"><input name="int_totalKilos_tinta_rp" type="number" id="int_totalKilos_tinta_rp" style="width:80px" min="0"step="any" onclick="getSumK();" value="<?php echo $row_totalTintas['tintas']; ?>"/></td>
          <td id="fuente1">&nbsp;</td>
          <td colspan="2" id="fuente1">&nbsp;</td>
          <td id="fuente1">&nbsp;</td>
          <td colspan="3" id="fuente1">&nbsp;</td>
        </tr>
        <?php }?>
        <tr>
          <td colspan="2" id="dato1"></td>
          <td colspan="2" id="dato1"></td>
          <td colspan="2" id="dato1"></td>
          <td id="dato1"></td>
        </tr>
        <tr>
        <td colspan="12" id="fuente1">Nota: los kilos x hora es el resultado de kilos de extruder dividido horas trabajadas menos los tiempos muertos</td>
         </tr>
        <tr id="tr1">
          <td colspan="10" id="titulo4">CONSUMOS</td>
        </tr>
        <!--<tr>
          <td colspan="10" id="fuente2"><input type="button" id="liquida" onclick="mostrardiv3()" value="Liquidar O.P" />
          <a href="javascript:verFoto('produccion_regist_impre_kilos_prod.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&rollo=<?php echo $row_rp_edit['rollo_rp']?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp']?>&amp;id_ref=<?php echo $row_ref['id_ref'] ?>','840','640')">
            <input type="button" name="check_sh" id="check" style="display:none" value="Reporte Tintas" />
            </a><a href="javascript:verFoto('produccion_registro_impresion_detalle_add.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&rollo=<?php echo $row_rp_edit['rollo_rp']?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp']?>','820','270')">
              <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Tiempos Desperdicio"/>
              </a>
            <input type="button" name="check_sh" id="check_sh2" value="Mostrar" onclick="mostrardiv1()"/>
            <input type="button" value="Ocultar" onclick="ocultardiv1()" /></td>
        </tr>-->
        <tr>
           </tr>
        <tr>
          <td colspan="9" id="fuente1">
          <fieldset> <legend>Registro de Tiempos y Desperdicios</legend>
            <table  width="100%"  border="0" id="flotante">
              <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
              <tr>
              <td nowrap id="detalle2"><strong>Tiempos Muertos - Tipo</strong></td>
              <td nowrap id="detalle2"><strong>Tiempos Muertos - Minutos</strong></td>
              <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
            </tr>
            <?php for ($k=0;$k<=$totalRows_tiempoMuerto-1;$k++) { ?>
            <tr>
              <td id="fuente1"><?php $id1=mysql_result($tiempoMuerto,$k,id_rpt_rt); 
              $id_tm=$id1;
              $sqltm="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
              $resulttm= mysql_query($sqltm);
              $numtm= mysql_num_rows($resulttm);
              if($numtm >='1')
              { 
              $nombre = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombre; }?></td>
              <td id="fuente1"><?php $var1=mysql_result($tiempoMuerto,$k,valor_tiem_rt); echo $var1; $totalMM=$totalMM+$var1;?></td>
              <td id="fuente1"><a href="javascript:eliminar_rtei('id_rtei',<?php $delrt=mysql_result($tiempoMuerto,$k,id_rt); echo $delrt; ?>,'produccion_registro_extrusion_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
            </tr><?php } ?>
            <tr>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1"><strong>
            <?php echo $totalMM;  ?>
          </strong></td>
          <td id="fuente1">&nbsp;</td>
          </tr>
            <?php } ?>
            <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
            <tr>
              <td nowrap id="detalle2"><strong>Tiempos Preparacion - Tipo</strong></td>
              <td nowrap id="detalle2"><strong>Tiempos Preparacion - Minutos</strong></td>
              <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
            </tr>
            <?php for ($x=0;$x<=$totalRows_tiempoPreparacion-1;$x++) { ?>
            <tr>
              <td id="fuente1">
              <?php $id2=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); 
           	  $id_rtp=$id2;
           	  $sqlrtp="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
           	  $resultrtp= mysql_query($sqlrtp);
           	  $numrtp= mysql_num_rows($resultrtp);
           	  if($numrtp >='1')
           	  { 
	             $nombre = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombre; }?></td>
              <td id="fuente1"><?php $var2=mysql_result($tiempoPreparacion,$x,valor_prep_rtp); echo $var2; $totalMD=$totalMD+$var2; ?></td>
              <td id="fuente1"><a href="javascript:eliminar_rtei('id_rpei',<?php $delrp=mysql_result($tiempoPreparacion,$x,id_rt); echo $delrp; ?>,'produccion_registro_extrusion_add.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a> </td>
            </tr><?php } ?>
            <tr>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1"><strong>
            <?php echo $totalMD; ?>
          </strong></td>
          <td id="fuente1">&nbsp;</td>
          </tr>
            <?php } ?>
            <?php if($row_desperdicio['id_rpd_rd']!='') {?>
            <tr>
              <td nowrap id="detalle2"><strong>Desperdicios - Tipo</strong></td>
              <td nowrap id="detalle2"><strong>Desperdicios - Kilos</strong></td>
              <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
            </tr>
            <?php for ($i=0;$i<=$totalRows_desperdicio-1;$i++) { ?>
            <tr>
              <td id="fuente1"><?php $id3=mysql_result($desperdicio,$i,id_rpd_rd); 
				  $id_rpd=$id3;
				  $sqlrtd="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
				  $resultrtd= mysql_query($sqlrtd);
				  $numrtd= mysql_num_rows($resultrtd);
				  if($numrtd >='1')
				  { 
				  $nombre2 = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombre2; }?></td>
              <td id="fuente1"><?php $var3=mysql_result($desperdicio,$i,desperdicio); echo $var3; $totalMDD=$totalMDD+$var3;?>
                <input name="valor_desp_rd[]" type="hidden" id="valor_desp_rd[]" value="<?php echo $var3; ?>" size="6" onclick="getSumD();"/></td>
              <td id="fuente1"><a href="javascript:eliminar_rtei('id_rdei',<?php $delrd=mysql_result($desperdicio,$i,id_rd); echo $delrd; ?>,'produccion_registro_extrusion_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
            </tr><?php } ?>
            <tr>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1" above><strong>
            <?php if($totalMDD!=''){echo $totalMDD;}else{echo "0";} ?>
          </strong></td>
          <td id="fuente1">&nbsp;</td>
          </tr>
            <?php } ?>
            <?php if($row_totalTintas['op_rp']!='') {?>
            <tr>
              <td colspan="3" nowrap id="detalle2"><strong>Gasto Tintas - </strong><strong> Solventes</strong></td>
            </tr>
 <tr id="tr1">
        <td colspan="2" nowrap="nowrap"id="detalle2">Tintas - Solventes</td>
        <td nowrap="nowrap"id="detalle2">Kilos Ingresados</td>
      </tr>           
      <?php  do{ ?> 
       <tr>         
       <td colspan="2" id="fuente1">
      <?php 
	  $nombreMP=$row_totalTintas['id_rpp_rp']; 
	  $sqlins="SELECT id_insumo,descripcion_insumo FROM insumo WHERE id_insumo='$nombreMP'";
	  $resultins= mysql_query($sqlins);
	  $numins= mysql_num_rows($resultins);
	  if($numins >='1')
	  { 
	  $d_insu = mysql_result($resultins, 0, 'descripcion_insumo');echo $d_insu; }?></td>
      <td id="fuente1"><?php $Cant=$row_totalTintas['valor_prod_rp']; echo $Cant;?></td>      
       </tr>
       <?php } while ($row_totalTintas = mysql_fetch_assoc($totalTintas)); ?>
       <tr>
       <td colspan="3" id="detalle2"><a href="javascript:verFoto('produccion_regist_impre_kilos_prod_edit.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&rollo=<?php echo $row_rp_edit['rollo_rp']?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp'] ?>&amp;id_ref=<?php echo $row_ref['id_ref'] ?>','840','640')"> Editar los valores de las unidades ingresadas</a></td>
       </tr>
	   <?php } ?>
            <tr>
            <td> <!--SE UTILIZA PARA EL TOTAL DE KILOS TINTA EN JS-->
                <?php  for ($y=0;$y<=$totalRows_totalTintas-1;$y++) { ?>
                <input name="id_rpp_rp[]" type="hidden" id="id_rpp_rp[]" value="<?php $id4=mysql_result($totalTintas,$y,id_rpp_rp); echo $id4; ?>" size="6"/>
                <input name="valor_tinta_rp[]" type="hidden" id="valor_tinta_rp[]" value="<?php $var4=mysql_result($totalTintas,$y,valor_prod_rp); echo $var4; ?>" size="6"/></td>
            <?php } ?>
            </tr>
      </table>
          </fieldset>
          </td>
        </tr>
        <tr id="tr1">
          <td colspan="2" id="fuente1">Maquina</td>
          <td colspan="2" id="fuente1"><select name="str_maquina_rp" id="maquina" onblur="if(form1.str_maquina_rp.value=='') { alert('Debe Seleccionar una maquina')}">
            <?php
do {  
?>
            <option value="<?php echo $row_maquinas['id_maquina']?>"><?php echo $row_maquinas['nombre_maquina']?></option>
            <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
          </select>
            <strong>
            <input name="valor_tiem_rt" id="horasmuertas" type="hidden" size="6" value="<?php if($totalMM==''){echo 0;}else{echo $totalMM;} ?>"/>
            <input name="valor_prep_rtp" id="horasprep" type="hidden" size="6" value="<?php if($totalMD==''){echo 0;}else{echo $totalMD;} ?>"/>
            <input name="valor_desp_rd" id="valor_desp_rd" type="hidden" size="6" value="<?php if($totalMDD==''){echo 0;}else{echo $totalMDD;} ?>"/>
            </strong></td>
          <td colspan="4" id="fuente1">
            
            <select name="int_cod_empleado_rp" id="operario"  onblur=" validacion_unodelosdos_imp()" style="width:120px">
              <option value=""<?php if (!(strcmp("", $row_rp_edit['int_cod_empleado_rp']))) {echo "selected=\"selected\"";} ?>>Operario</option>
                  <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
                    <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rp_edit['int_cod_empleado_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
                  <?php } ?>
            </select>  
            -
            <select name="int_cod_liquida_rp" id="auxiliar" style="width:120px" onblur="validacion_unodelosdos_imp()">
              <option value=""<?php if (!(strcmp("", $row_rp_edit['int_cod_liquida_rp']))) {echo "selected=\"selected\"";} ?>>Auxiliar</option>
                <?php  foreach($row_revisor as $row_revisor ) { ?>
                  <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_revisor['codigo_empleado'], $row_rp_edit['int_cod_liquida_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['codigo_empleado']." - ".$row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado']?></option>
                <?php } ?>
              </select>

          </td>
        </tr>
        <tr>
          <td colspan="2" id="dato1"></td>
          <td colspan="2" id="dato1"></td>
          <td colspan="2" id="dato1"></td>
          <td id="dato1"></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1">Fecha Inicial</td>
          <td colspan="2" id="fuente1"><input name="fecha_ini_rp" id="fecha_ini_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rp_edit['fecha_ini_rp']); ?>" required/></td>
          <td colspan="4" id="fuente1"><input name="metro_r" type="number" id="metro_r" min="1" style="width:100px" value="<?php echo $row_rp_edit['int_metro_lineal_rp'];?>" required="required" onchange="kilosxHora2()" readonly="readonly"/>
            <input name="int_metro_lineal_rp" type="number" readonly="readonly" required="required" id="metro_r2" placeholder="Metro Lineal" style="width:116px" min="0"step="any" value="<?php echo $row_rp_edit['int_metro_lineal_rp']; ?>"/>
Metro lineal (Extrusion)</td>
        </tr>
        <tr id="tr1">
          <td colspan="2" id="fuente1">Fecha Final</td>
          <td colspan="2" id="fuente1"><input name="fecha_fin_rp" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rp_edit['fecha_fin_rp']); ?>" required onblur="validacion_select_fecha();" onchange="kilosxHora2();"/></td>
          <td colspan="4" id="fuente1"> <?php 
		     $id_op=$row_ref['id_op'];
             $sqlre="SELECT COUNT(DISTINCT rollo_r) AS max_rolloE,SUM(metro_r) as metro_r, SUM(kilos_r) as kilos_r FROM TblExtruderRollo WHERE id_op_r=$id_op"; 
            $resultre=mysql_query($sqlre); 
            $numre=mysql_num_rows($resultre); 
            if($numre >= '1') 
            { 
			$max_rolloE =mysql_result($resultre,0,'max_rolloE');  
			$kilosR=mysql_result($resultre,0,'kilos_r'); 
			$metrosE=mysql_result($resultre,0,'metro_r'); 
			}
 		
			?><input type="number" name="rollo_rp" id="rollo_rp" min="0"step="any" required="required" placeholder="Rollos" style="width:46px" value="<?php echo $row_rp_edit['rollo_rp'];?>" onclick="getSumT();"/>
de
  <input name="int_total_rollos_rp" type="number" id="int_total_rollos_rp" placeholder="Total Rollos" style="width:46px" min="0"step="any" readonly="readonly" onclick="getSumT();" value="<?php echo $row_rp_edit['int_total_rollos_rp'];?>"/>
Total Rollos ( Extrusion)</td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1">Total Horas Trabajadas</td>
          <td colspan="2" id="fuente1"><?php $fechaini=$row_rp_edit['fecha_ini_rp'];$fechafin=$row_rp_edit['fecha_fin_rp'];?>
          <input name="total_horas_rp" type="text" id="total_horas_rp" readonly="readonly" style="width:116px"value="<?php echo $row_rp_edit['total_horas_rp']; ?>"  onclick="kilosxHora2();"/></td>
          <td colspan="4" id="fuente1"><!--<input type="text" name="horas_rp" id="horas_rp" readonly="readonly"  size="7"/>-->
            <input name="sumaTiempos" type="hidden" id="sumaTiempos" readonly="readonly" size="19" value="<?php echo $totalMM+$totalMD ?>"/> 
            <input name="tiempoOptimo_rp" type="text" id="tiempoOptimo_rp" readonly="readonly" style="width:116px" value="<?php echo $row_rp_edit['tiempoOptimo_rp']; ?>"/> 
            Tiempo Total Rodamiento</td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1">Metros x Minutos</td>
          <td colspan="2" id="fuente1"><input name="int_metroxmin_rp" type="number" required="required" id="metroxmin" min="0"step="any" onclick="kilosxHora2();" value="<?php echo $row_rp_edit['int_metroxmin_rp']; ?>" style="width:116px" /><!--mlxHora();--></td>
          <td colspan="2" id="fuente1">Tiempo M:
          <?php		 
		  echo $totalMM;?></td>
          <td id="fuente1">Kilos Desp:
          <?php echo $totalMDD;?></td>
          <td id="fuente1">&nbsp;</td>
        </tr>
        <tr id="tr1">
          <td colspan="2" id="fuente1">Kilos  x Hora</td>
          <td colspan="2" id="fuente1"><input name="int_kilosxhora_rp" id="int_kilosxhora_rp" type="number" required="required" min="0"step="any"  value="<?php echo $row_rp_edit['int_kilosxhora_rp']; ?>" style="width:116px" onclick="kilosxHora2();" readonly="readonly"/></td> 
          <td colspan="2" id="fuente1">Tiempo P:
          <?php echo $totalMD;?></td>
          <td colspan="2"  id="fuente1">&nbsp;</td> 
        </tr>     
  <tr>
    <td colspan="10" id="fuente1">&nbsp;</td>
  </tr>
       
        <tr>
          <td colspan="10" id="fuente1"><input type="button" value="Modificar Mezclas" onclick="mostrardiv2()" />
            <input type="button" value="Ocultar" onclick="ocultardiv2()" /></td>
        </tr>
        <tr>
          <td colspan="10" id="fuente2"><input type="submit" name="ENVIAR" id="ENVIAR" value="EDITAR" /></td>
        </tr>
        <tr>
          <td colspan="10"><input type="hidden" name="id_pm_cv" id="id_pm_cv" value="0"/>
            <input type="hidden" name="version_ref_cv" id="version_ref_cv" value="<?php echo $row_ref['version_ref']; ?>" />
            <input type="hidden" name="id_proceso_cv" id="id_proceso_cv" value="2"/>
            <input type="hidden" name="cod_ref_cv" id="cod_ref_cv" value="<?php echo $row_ref['cod_ref']; ?>"/>
            <input type="hidden" name="fecha_registro_cv" id="fecha_registro_cv"  value="<?php echo date("Y-m-d"); ?>"/>
            <input type="hidden" name="str_registro_cv" id="str_registro_cv" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
            <input type="hidden" name="b_borrado_cv" id="b_borrado_cv" value="0"/>
            <!--fin mezcla caract -->
        </tr>
        <tr id="tr1">
          <td colspan="10" id="dato2"><input name="porcentaje" type="hidden" id="porcentaje" value="<?php echo $row_rp_edit['porcentaje_op_rp']; ?>" />
            <input name="id_proceso_rp" type="hidden" id="id_proceso_rp" value="2" />
            <input name="id_op_rp" type="hidden" id="id_op_rp" value="<?php echo $row_rp_edit['id_op_rp']; ?>" />
            <input type="hidden" name="id_ref_rp" id="id_ref_rp" value="<?php echo $row_rp_edit['id_ref_rp']; ?>"/>
            <input name="int_cod_ref_rp" type="hidden" id="int_cod_ref_rp" value="<?php echo $row_rp_edit['int_cod_ref_rp']; ?>" />
            <input name="version_ref_rp" type="hidden" id="version_ref_rp" value="<?php echo $row_ref['version_ref']; ?>" />
            <input name="kilos_op" type="hidden" id="kilos_op" value="<?php echo $row_ref['int_kilos_op']; ?>" />
            <?php  for ($TK=0;$TK<=$totalRows_totalKilos-1;$TK++) { ?>
            <input name="kilos_impreso[]" type="hidden" id="kilos_impreso[]" value="<?php $tK=mysql_result($totalKilos,$TK,int_total_kilos_rp); echo $tK; ?>" />
            <?php } ?>
            <input type="hidden" name="MM_update" value="form1" /></td>
        </tr>
      </table>
      </form>
 
 <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form2">   
    <!--tabla de MEZCLA DE IMPRESION -->
    <table id="flotante2" style="display:none"><!--style="display:none"-->
      <tr id="tr1">
          <td colspan="11" id="titulo">MEZCLAS DE  IMPRESION 
            <input name="str_registro_pmi" type="hidden" id="str_registro_pmi" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/>
            <input name="fecha_registro_pmi" type="hidden"  id="fecha_registro_pmi" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" />
            <input type="hidden" name="id_ref_pmi" id="id_ref_pmi" value="<?php echo $row_ref['id_ref']; ?>"/>
            <input type="hidden" name="int_cod_ref_pmi" id="int_cod_ref_pmi" value="<?php echo $row_ref['cod_ref'] ?>"/>
            <input type="hidden" name="version_ref_pmi" id="version_ref_pmi" value="<?php echo $row_ref['version_ref'] ?>"/></td>
        </tr>
       <tr>  
         <td nowrap="nowrap" valign="top"><table>
           <?php  if ($row_unidad_uno!='') { ?>
           <tr>
                 <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 1</td>
                 <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                 </tr>
           <?php }?>
           <?php  for ($x=0;$x<=$totalRows_unidad_uno-1 ;$x++) { ?>
           <tr>
             <td id="fuente3"><?php $id=mysql_result($unidad_uno,$x,id_i_pmi);$id_pmi=mysql_result($unidad_uno,$x,id_pmi);$id_m=mysql_result($unidad_uno,$x,str_nombre_m);echo $id_m;?></td>
             <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
               <select name="id[]" id="id[]" style="width:60px">
                 <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                 <?php
					do {  
					?>
                 <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                 <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
               </select>
               <input name="valor[]" style="width:60px" min="0"step="0.01" type="number" id="valor[]" value="<?php $valor=mysql_result($unidad_uno,$x,str_valor_pmi); echo $valor;?>"/></td>
           </tr>
           <?php  } ?>
         </table></td> 
         
         <td valign="top"><table>
    <?php  if ($row_unidad_dos!='') { ?>
    <tr>
        <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 2</td>
        <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
        </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_dos-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_dos,$x,id_i_pmi);$id_pmi=mysql_result($unidad_dos,$x,id_pmi); $id_m=mysql_result($unidad_dos,$x,str_nombre_m);echo $id_m;?></td>
      <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
        <select name="id[]" id="id[]" style="width:60px">
          <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
        </select>
        <input name="valor[]" style="width:60px" min="0"step="0.01" type="number" id="valor[]" value="<?php $valor=mysql_result($unidad_dos,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table></td> 
         
         <td valign="top"><table>
             <?php  if ($row_unidad_tres!='') { ?>
             <tr>
             <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 3</td>
             <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
             </tr>
             <?php }?>
             <?php  for ($x=0;$x<=$totalRows_unidad_tres-1 ;$x++) { ?>
             <tr>
               <td id="fuente3"><?php $id=mysql_result($unidad_tres,$x,id_i_pmi);$id_pmi=mysql_result($unidad_tres,$x,id_pmi);$id_m=mysql_result($unidad_tres,$x,str_nombre_m);echo $id_m;?></td>
               <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
               <select name="id[]" id="id[]" style="width:60px">
                 <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                   <?php
					do {  
					?>
                   <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                   <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
                 </select>
                 <input name="valor[]" style="width:60px" min="0"step="0.01" type="number" id="valor[]" value="<?php $valor=mysql_result($unidad_tres,$x,str_valor_pmi); echo $valor;?>"/>            </td>
               </tr>
             <?php  } ?>
             </table></td>
                                                
       </tr>     
      <tr>
        <td id="fuente1" valign="top">
       <table>
    <?php  if ($row_unidad_cuatro!='') { ?>
    <tr>
      <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 4</td>
      <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
      </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_cuatro-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_cuatro,$x,id_i_pmi);$id_pmi=mysql_result($unidad_cuatro,$x,id_pmi); $id_m=mysql_result($unidad_cuatro,$x,str_nombre_m);echo $id_m;?></td>
      <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
        </select>
        <input name="valor[]" style="width:60px" min="0"step="0.01" type="number" id="valor[]" value="<?php $valor=mysql_result($unidad_cuatro,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table> 
        </td>
        <td id="fuente1" valign="top">
       <table>
    <?php  if ($row_unidad_cinco!='') { ?>
    <tr>
        <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 5</td>
        <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
        </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_cinco-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_cinco,$x,id_i_pmi);$id_pmi=mysql_result($unidad_cinco,$x,id_pmi); $id_m=mysql_result($unidad_cinco,$x,str_nombre_m);echo $id_m;?></td>
      <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
        </select>
        <input name="valor[]" style="width:60px" min="0"step="0.01" type="number" id="valor[]" value="<?php $valor=mysql_result($unidad_cinco,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table> 
        </td>
        <td id="fuente1" valign="top">
        <table>
    <?php  if ($row_unidad_seis!='') { ?>
    <tr>
               <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 6</td>
               <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
               </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_seis-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_seis,$x,id_i_pmi);$id_pmi=mysql_result($unidad_seis,$x,id_pmi);$id_m=mysql_result($unidad_seis,$x,str_nombre_m);echo $id_m;?></td>
      <td nowrap="nowrap"id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
      </select>
        <input name="valor[]" style="width:60px" min="0"step="0.01" type="number" id="valor[]" value="<?php $valor=mysql_result($unidad_seis,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table>
        </td>
      </tr>
      <tr>
        <td id="fuente1" valign="top">
        <table>
    <?php  if ($row_unidad_siete!='') { ?>
    <tr>
      <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 7</td>
      <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
      </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_siete-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_siete,$x,id_i_pmi);$id_pmi=mysql_result($unidad_siete,$x,id_pmi);$id_m=mysql_result($unidad_siete,$x,str_nombre_m);echo $id_m;?></td>
      <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
  <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
  <?php
					do {  
					?>
  <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
  <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
</select>
        <input name="valor[]" style="width:60px" min="0"step="0.01" type="number" id="valor[]" value="<?php $valor=mysql_result($unidad_siete,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table>
        </td>
        <td id="fuente1" valign="top">
        <table>
    <?php  if ($row_unidad_ocho!='') { ?>
    <tr>
              <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 8</td>
              <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
              </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_ocho-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_ocho,$x,id_i_pmi);$id_pmi=mysql_result($unidad_ocho,$x,id_pmi);$id_m=mysql_result($unidad_ocho,$x,str_nombre_m);echo $id_m;?></td>
      <td nowrap="nowrap"id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value="0"<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
      </select>
        <input name="valor[]" style="width:60px" min="0"step="0.01" type="number" id="valor[]" value="<?php $valor=mysql_result($unidad_ocho,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table>
        </td>
      </tr>
      <tr>
        <td colspan="15" id="fuente2"><textarea name="observ_pmi" id="observ_pmi" cols="80" rows="2"placeholder="OBSERVACIONES" onblur="conMayusculas(this)"><?php $con="select DISTINCT id_ref_pmi,int_cod_ref_pmi,b_borrado_pmi,observ_pmi  from Tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$id_ref' AND b_borrado_pmi='0'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['observ_pmi'];?></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="15" id="fuente2">
        <input type="hidden" name="id_ref" id="id_ref" value="<?php echo $row_ref['id_ref']; ?>"/>
        <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_ref['cod_ref']; ?>"/>
        <input type="hidden" name="id_proceso" id="id_proceso" value="2"/>
        <input type="hidden" name="b_borrado_pmi" id="b_borrado_pmi" value="0"/></td>
      </tr>
        <tr><td colspan="7" >
   <table>      
      <tr id="tr1">
        <td colspan="13" id="titulo4">CARACTERISTICAS</td>
        </tr> 
         <tr>
          <?php  for ($x=0;$x<=$totalRows_caract_valor-1;$x++) { ?>          
          <td id="fuente1"><?php $id_cv=mysql_result($caract_valor,$x,id_cv); $var=mysql_result($caract_valor,$x,str_nombre_caract_c); echo $var; ?>                                             
          <input name="id_cv[]" type="hidden" value="<?php echo $id_cv; ?>" /><input name="valor_cv[]" type="number" style="width:47px" min="0"step="1"  placeholder="Cant/Und" size="5"value="<?php $valor=mysql_result($caract_valor,$x,str_valor_cv); echo $valor;?>"/>
          </td>
         <?php  } ?>
         </tr>
         </table><?php if($row_caract_valor['id_ref_cv']=='') {?><a target="new" href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_ref['id_ref'];?>&amp;cod_ref=<?php echo $row_ref['cod_ref']; ?>">Agregar Mezcla</a><?php }?>
    </td></tr>                                                    
      <tr>
      <td colspan="10" id="fuente2"><input type="hidden" name="MM_update" value="form2" />
        <input type="submit" name="ACTUALIZAR" id="ACTUALIZAR" value="ACTUALIZAR MEZCLA"/></td>
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
<b class="spiffy1"><b></b></b>
</b></div> 
</td></tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($orden_produccion);
mysql_free_result($rp_edit);
mysql_free_result($maquinas);
mysql_free_result($ref);
mysql_free_result($tiempoMuerto);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($totalTintas);
mysql_free_result($materia_prima);
mysql_free_result($caract_valor);
mysql_free_result($unidad_uno);
mysql_free_result($unidad_dos);
mysql_free_result($unidad_tres);
mysql_free_result($unidad_cuatro);
mysql_free_result($unidad_cinco);
mysql_free_result($unidad_seis);
mysql_free_result($unidad_siete);
mysql_free_result($unidad_ocho);
mysql_free_result($codigo_empleado);
mysql_free_result($totalKilos);

?>
