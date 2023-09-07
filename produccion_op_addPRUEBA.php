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
//INSERT
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Tbl_orden_produccion (id_op, fecha_registro_op,fecha_entrega_op, str_responsable_op, str_numero_oc_op, int_cod_ref_op, id_ref_op, version_ref_op, int_cotiz_op, str_entrega_op, str_nit_op, int_cliente_op, int_desperdicio_op, int_cantidad_op, str_tipo_bolsa_op, int_pesom_op, str_matrial_op, str_presentacion_op, metroLineal_op, int_kilos_op, int_calibre_op, int_ancho_rollo_op, int_micras_op, str_interno_op, str_externo_op, str_tratamiento_op, int_undxcaja_op, int_undxpaq_op, numInicio_op, observ_extru_op,maquina_imp_op, 
  kls_req_imp_op, mts_req_imp_op, margen_izq_imp_op, margen_anc_imp_op, margen_anc_mm_imp_op, margen_der_imp_op, margen_peri_imp_op, margen_per_mm_imp_op, margen_z_imp_op, observ_impre_op, mts_cinta_sellado_op, kls_sellado_op, und_prod_sellado_op, observ_sellado_op, b_estado_op, b_borrado_op, b_visual_op) 
  VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_op'], "int"),
                       GetSQLValueString($_POST['fecha_registro_op'], "date"),
					   GetSQLValueString($_POST['fecha_entrega_op'], "date"),
                       GetSQLValueString($_POST['str_responsable_op'], "text"),
                       GetSQLValueString($_POST['str_numero_oc_op'], "text"),
                       GetSQLValueString($_POST['int_cod_ref_op'], "text"),
					   GetSQLValueString($_POST['id_ref_op'], "int"),
                       GetSQLValueString($_POST['version_ref_op'], "int"),
                       GetSQLValueString($_POST['int_cotiz_op'], "int"),
                       GetSQLValueString($_POST['str_entrega_op'], "text"),
                       GetSQLValueString($_POST['str_nit_op'], "text"),
                       GetSQLValueString($_POST['int_cliente_op'], "int"),
                       GetSQLValueString($_POST['int_desperdicio_op'], "double"),
                       GetSQLValueString($_POST['int_cantidad_op'], "double"),
                       GetSQLValueString($_POST['str_tipo_bolsa_op'], "text"),
                       GetSQLValueString($_POST['int_pesom_op'], "double"),
                       GetSQLValueString($_POST['str_matrial_op'], "text"),
                       GetSQLValueString($_POST['str_presentacion_op'], "text"),
					   GetSQLValueString($_POST['metroLineal_op'], "double"),
                       GetSQLValueString($_POST['int_kilos_op'], "double"),
                       GetSQLValueString($_POST['int_calibre_op'], "double"),
                       GetSQLValueString($_POST['int_ancho_rollo_op'], "double"),
                       GetSQLValueString($_POST['int_micras_op'], "double"),
                       GetSQLValueString($_POST['str_interno_op'], "text"),
                       GetSQLValueString($_POST['str_externo_op'], "text"),
                       GetSQLValueString($_POST['str_tratamiento_op'], "text"),
					   GetSQLValueString($_POST['int_undxcaja_op'], "int"),
					   GetSQLValueString($_POST['int_undxpaq_op'], "int"),
					   GetSQLValueString($_POST['numInicio_op'], "text"),
                       GetSQLValueString($_POST['observ_extru_op'], "text"),
					   GetSQLValueString($_POST['maquina_imp_op'], "text"),
					   GetSQLValueString($_POST['kls_req_imp_op'], "double"),
					   GetSQLValueString($_POST['mts_req_imp_op'], "double"),
					   GetSQLValueString($_POST['margen_izq_imp_op'], "text"),
					   GetSQLValueString($_POST['margen_anc_imp_op'], "text"),
					   GetSQLValueString($_POST['margen_anc_mm_imp_op'], "text"),
					   GetSQLValueString($_POST['margen_der_imp_op'], "text"),
					   GetSQLValueString($_POST['margen_peri_imp_op'], "text"),
					   GetSQLValueString($_POST['margen_per_mm_imp_op'], "text"),
					   GetSQLValueString($_POST['margen_z_imp_op'], "double"),
					   GetSQLValueString($_POST['observ_impre_op'], "text"),
					   GetSQLValueString($_POST['mts_cinta_sellado_op'], "double"),
					   GetSQLValueString($_POST['kls_sellado_op'], "double"),
					   GetSQLValueString($_POST['und_prod_sellado_op'], "double"),
					   GetSQLValueString($_POST['observ_sellado_op'], "text"),					   
					   GetSQLValueString($_POST['b_estado_op'], "int"),
					   GetSQLValueString($_POST['b_borrado_op'], "int"),
					   GetSQLValueString($_POST['b_visual_op'], "int"));
					   					   					   
  $insertSQL2 = sprintf("INSERT INTO Tbl_op_proceso (id_op,id_proceso) VALUES (%s, %s)",
                       GetSQLValueString($_POST['id_op'], "int"),
					   GetSQLValueString($_POST['id_proceso'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
  
  
  
  $insertGoTo = "produccion_op_vista.php?id_op=" . $_POST['id_op'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
//ORDEN DE PRODUCCION
mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = "SELECT * FROM Tbl_orden_produccion ORDER BY id_op DESC";
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);
//CARGA REF
$colname_item_oc = "-1";
if (isset($_GET['str_numero_oc_op'])) {
  $colname_item_oc  = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc_op'] : addslashes($_GET['str_numero_oc_op']);
}
mysql_select_db($database_conexion1, $conexion1);//CREO UN DISTINCT PORQUE CREAR LA MISMA REFERENCIA CON VARIAS DIRECCIONES
$query_ref_items = sprintf("SELECT DISTINCT 
Tbl_orden_compra.str_nit_oc, 
Tbl_orden_compra.id_c_oc, 
Tbl_orden_compra.b_borrado_oc,
Tbl_orden_compra.str_numero_oc,
Tbl_items_ordenc.str_numero_io,
Tbl_items_ordenc.int_cod_ref_io 
FROM Tbl_orden_compra,Tbl_items_ordenc WHERE Tbl_orden_compra.str_numero_oc='%s' AND Tbl_orden_compra.str_numero_oc=Tbl_items_ordenc.str_numero_io AND Tbl_orden_compra.b_borrado_oc='0' ORDER BY Tbl_items_ordenc.int_cod_ref_io  DESC",$colname_item_oc);
$ref_items = mysql_query($query_ref_items, $conexion1) or die(mysql_error());
$row_ref_items = mysql_fetch_assoc($ref_items);
$totalRows_ref_items = mysql_num_rows($ref_items);
//CARGA O.C
mysql_select_db($database_conexion1, $conexion1);
$query_oc = "SELECT str_numero_oc, b_estado_oc, b_borrado_oc FROM Tbl_orden_compra WHERE b_estado_oc='1' AND b_borrado_oc='0' ORDER BY str_numero_oc DESC";
$oc= mysql_query($query_oc, $conexion1) or die(mysql_error());
$row_oc = mysql_fetch_assoc($oc);
$totalRows_oc = mysql_num_rows($oc);
//CARGA CON O.C
$colname_datos_oc = "-1";
if (isset($_GET['str_numero_oc_op'])) {
  $colname_datos_oc = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc_op'] : addslashes($_GET['str_numero_oc_op']);
}
$colname_ref_oc = "-1";
if (isset($_GET['int_cod_ref_op'])) {
  $colname_ref_oc = (get_magic_quotes_gpc()) ? $_GET['int_cod_ref_op'] : addslashes($_GET['int_cod_ref_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_datos_oc = sprintf("SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc,Tbl_referencia,Tbl_egp WHERE Tbl_orden_compra.str_numero_oc='%s' AND Tbl_orden_compra.str_numero_oc=Tbl_items_ordenc.str_numero_io AND Tbl_items_ordenc.int_cod_ref_io='%s' AND Tbl_items_ordenc.int_cod_ref_io=Tbl_referencia.cod_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp AND Tbl_referencia.estado_ref='1' ORDER BY Tbl_referencia.cod_ref DESC",$colname_datos_oc,$colname_ref_oc);
$datos_oc = mysql_query($query_datos_oc, $conexion1) or die(mysql_error());
$row_datos_oc = mysql_fetch_assoc($datos_oc);
$totalRows_datos_oc = mysql_num_rows($datos_oc);

//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 1
$id_ref=$row_datos_oc['id_ref'];
//CARGA UNIDAD 1
$colname_unidad_uno = "-1";
if (isset($id_ref)) {
  $colname_unidad_uno  = (get_magic_quotes_gpc()) ? $id_ref : addslashes($id_ref);
}
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_uno = sprintf("select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='1' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_uno = mysql_query($query_unidad_uno, $conexion1) or die(mysql_error());
$row_unidad_uno = mysql_fetch_assoc($unidad_uno);
$totalRows_unidad_uno = mysql_num_rows($unidad_uno);
$row_uno = mysql_fetch_array($unidad_uno);
//CARGA UNIDAD 2
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_dos = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='2' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_dos = mysql_query($query_unidad_dos, $conexion1) or die(mysql_error());
$row_unidad_dos = mysql_fetch_assoc($unidad_dos);
$totalRows_unidad_dos = mysql_num_rows($unidad_dos);
//CARGA UNIDAD 3
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_tres = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='3' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_tres = mysql_query($query_unidad_tres, $conexion1) or die(mysql_error());
$row_unidad_tres = mysql_fetch_assoc($unidad_tres);
$totalRows_unidad_tres = mysql_num_rows($unidad_tres);
//CARGA UNIDAD 4
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cuatro = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='4' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_cuatro = mysql_query($query_unidad_cuatro, $conexion1) or die(mysql_error());
$row_unidad_cuatro = mysql_fetch_assoc($unidad_cuatro);
$totalRows_unidad_cuatro = mysql_num_rows($unidad_cuatro);
//CARGA UNIDAD 5
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cinco = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='5' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_cinco = mysql_query($query_unidad_cinco, $conexion1) or die(mysql_error());
$row_unidad_cinco = mysql_fetch_assoc($unidad_cinco);
$totalRows_unidad_cinco = mysql_num_rows($unidad_cinco);
//CARGA UNIDAD 6
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_seis = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='6' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_seis = mysql_query($query_unidad_seis, $conexion1) or die(mysql_error());
$row_unidad_seis = mysql_fetch_assoc($unidad_seis);
$totalRows_unidad_seis = mysql_num_rows($unidad_seis);
//CARGA UNIDAD 7
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_siete = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='7' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_siete = mysql_query($query_unidad_siete, $conexion1) or die(mysql_error());
$row_unidad_siete = mysql_fetch_assoc($unidad_siete);
$totalRows_unidad_siete = mysql_num_rows($unidad_siete);
//CARGA UNIDAD 8
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_ocho = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='8' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_ocho = mysql_query($query_unidad_ocho, $conexion1) or die(mysql_error());
$row_unidad_ocho = mysql_fetch_assoc($unidad_ocho);
$totalRows_unidad_ocho = mysql_num_rows($unidad_ocho);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='2' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
//PARA SUMAR CANTIDAD YA INGRESADOS DE LA O.C Y SU ITEM
$colname_margenes_ref = "-1";
if (isset($_GET['int_cod_ref_op'])) {
  $colname_margenes_ref = (get_magic_quotes_gpc()) ? $_GET['int_cod_ref_op'] : addslashes($_GET['int_cod_ref_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_margenes = sprintf("SELECT * FROM Tbl_orden_produccion WHERE int_cod_ref_op=%s ORDER BY fecha_registro_op DESC LIMIT 1",$colname_margenes_ref);
$margenes = mysql_query($query_margenes, $conexion1) or die(mysql_error());
$row_margenes = mysql_fetch_assoc($margenes);
$totalRows_margenes = mysql_num_rows($margenes);
//ULTIMA NUMERACION
$colname_numeracion = "-1";
if (isset($_GET['int_cod_ref_op'])) {
  $colname_numeracion = (get_magic_quotes_gpc()) ? $_GET['int_cod_ref_op'] : addslashes($_GET['int_cod_ref_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_numeracion = sprintf("SELECT Tbl_tiquete_numeracion.int_hasta_tn FROM Tbl_tiquete_numeracion,Tbl_orden_produccion WHERE Tbl_orden_produccion.int_cod_ref_op='%s' 
AND Tbl_orden_produccion.id_op=Tbl_tiquete_numeracion.int_op_tn ORDER BY Tbl_tiquete_numeracion.id_tn DESC LIMIT 1",$colname_numeracion);
$numeracion = mysql_query($query_numeracion, $conexion1) or die(mysql_error());
$row_numeracion = mysql_fetch_assoc($numeracion);
$totalRows_numeracion = mysql_num_rows($numeracion);
//INSUMOS TIPO DE CAJA
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo WHERE clase_insumo='5' ORDER BY  descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<!--VALIDAR EL ENVIO DE FORMULARIO-->
<script type="text/javascript">
/*var carac=document.form1.numInicio_op.value;
function numeracion(carac){
	var num="",caden="",l="",b="",c="",d="",e="",g="",h="",desde="", sal="",sal2="",cadena="";
		var caract =carac.toUpperCase().replace(/\s/g,'');//a mayusculas,reemplaza espacios		
		var z=(caract.search(/AA1F|AA1G|AA1H|AA1I|AA1J|AA1K|AA1L|AA1M|AA1N|AA1B|AA1C|AA1D|AA1E/i));
		if(z=='0'){
		var v = caract; 
        var data = v.substring(0,4);
		var num = v.substring(4); 
		cadena=data;
		desde=num+ parseInt(1);	
		return [  desde, cadena ]; 		
		}else
		//------------------SOLO NUMEROS---------------//		
		var n=(caract.search(/\d+/g));//d solo numeros
		if( n=='0'){			
		var v = caract; 
		var num = v.substring(0);
		var vacia="";	 
		desde=num+ parseInt(1); 
		return [ desde+ parseInt(1), vacia ];		
        }else
		//------------------LETRAS AL INICIO-----------// 
		var l=(caract.search(/\w+/g));//w alfanumericos
		if(l=='0' && z!='0'&& n!='0'){
		//caract.match(/\d+/g).join('');
		l=caract.match(/\D+/g); //D acepta diferente de numeros
		cadena=l;
		num=caract.match(/\d+/g); //d acepta solo numeros
		desde=num + parseInt(1);
		return [ desde+ parseInt(1), cadena ];		 		
		}//fin if
}*/
function funcion(){	
if(form1.mensajeLickRef.value=='1'){ 
alert("La referencia no tiene formula o esta incompleta, debe crearla");
return false;
}else {return true;} 

}
</script>
<script type="text/javascript">
function alerta(){
	msn=confirm("Desea Actualizar?, se actualizara caja, paquetes y medidas de la caja tanto en la Referencia como en la O.P")
	if(msn==true){
	  DatosGestiones3('11','cod_r',document.form1.int_cod_ref_op.value,'&caja',document.form1.int_undxcaja_op.value,'&paq',document.form1.int_undxpaq_op.value,'&medida',document.form1.marca_cajas_egp.value);

	}else if (msn == false){window.history.go(); } 
	
}
</script>
</head>
<!--onLoad="return funcion();"-->
<body onload="calcular_op(),hastaordenP();">
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
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="produccion_mezclas.php" target="new">MEZCLAS</a></li>		
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return funcion();">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="11" id="titulo2">ORDEN DE PRODUCCI&Oacute;N</td>
        </tr>
      <tr>
        <td width="137" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="9" id="dato3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="referencias.php" target="new"><img src="images/a.gif" style="cursor:hand;" alt="ADD MEZCLAS" title="ADD MEZCLAS" border="0" /></a>
        <a href="produccion_ordenes_produccion_listado.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO O.P"title="LISTADO O.P" border="0" /></a>
        <a href="menu.php" ><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
      <tr id="tr1">
        <td width="182" colspan="2" nowrap="nowrap" id="dato2">Fecha Registro O.P
          <input name="fecha_registro_op" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10"/>
          </td>
        <td colspan="6" id="dato3">
          Ingresado por
          <input name="str_responsable_op" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
        </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">ORDEN DE PRODUCCION N&deg;</td>
        <td  colspan="2" nowrap="nowrap" id="fuente2">FECHA INGRESO O.C.</td>
        <td nowrap="nowrap" colspan="2" id="fuente2">FECHA ENTREGA O.C.</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="numero2"><?php $op_numero=$row_orden_produccion['id_op']+1; $op_numero;?>
          <input name="id_op" type="number" style="width:100px" value="<?php echo $op_numero; ?>" readonly="readonly"/></td>
        <td colspan="2" id="fuente2"><?php echo $row_datos_oc['fecha_ingreso_oc']; ?></td>
        <td colspan="2" id="dato2"><input name="fecha_entrega_op" type="date" required="required" value="<?php echo $row_datos_oc['fecha_entrega_io']; ?>" /></td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="9" nowrap="nowrap" id="fuente2">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="4" id="dato3"><?php $cod=$_GET['int_cod_ref_op'];$idr=$row_datos_oc['id_ref'];
		if($_GET['int_cod_ref_op']!=''){					
			$cod_ref=$cod;
			$sqlm="SELECT * FROM Tbl_maestra_mezcla_caract WHERE int_cod_ref_mm=$cod_ref  AND id_proceso_mm='1'"; 
			$resultm=mysql_query($sqlm); 
			$numm=mysql_num_rows($resultm);			
			if($numm < '1'){
			 ?>
          <a href="produccion_mezclas_add.php?id_ref=<?php echo $id_ref;?>&cod_ref=<?php echo $cod;?>" target="_seft" style="text-decoration:none; color:#000000"><strong class="rojo_inteso">Agregar la formula</strong></a>
          <input name="mensajeLickRef" type="hidden" value="1" />
          <input name="id_ref_pm" type="hidden" value="<?php echo $idr;?>" />
          <?php }
			  } ?></td>
        <td colspan="2" id="dato3">Prioridad:</td>
       <td colspan="2" id="dato1"><select name="b_visual_op" style="width:40px">
         <option value="0">0</option>
         <option value="1">1</option>
         <option value="2">2</option>
         <option value="3">3</option>
         <option value="4">4</option>
         <option value="5">5</option>
       </select></td>
      </tr>
      <tr id="tr1">
        <td colspan="11" id="titulo4"></td>
        </tr>
<tr id="tr1">
        <td colspan="11" id="titulo4">ESPECIFICACIONES</td>
        </tr>        
      <tr>
        <td id="talla1">CLIENTE</td>
        <td colspan="2" id="talla1">ORDEN DE COMPRA N&deg;</td>
        <td colspan="2" id="talla1">REFERENCIA - VERSION</td>
        <td colspan="2" id="talla1">COTIZACION N&deg;</td>
        <td colspan="2" id="talla1">TIPO DE ENTREGA</td>
      </tr>
      <tr>
        <td id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr>
        <td id="fuente1"><input type="hidden" name="str_nit_op" id="str_nit_op"  value="<?php echo $row_ref_items['str_nit_oc']; ?>"/>
          <input type="hidden" name="int_cliente_op" id="int_cliente_op"  value="<?php echo $row_ref_items['id_c_oc']; ?>"/>
          <?php $id_c=$row_ref_items['id_c_oc'];
	$sqln="SELECT * FROM cliente WHERE id_c='$id_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nombre_c=mysql_result($resultn,0,'nombre_c'); $ca = $nombre_c; echo $ca; }
	else { echo "Cliente !";	} ?></td>
        <td colspan="2" id="fuente1"><select name="str_numero_oc_op" autofocus id="str_numero_oc_op" style="width:130px" onchange="if(form1.str_numero_oc_op.value) { consulta_oc_op(); } else{ alert('Debe Seleccionar una O.C'); }">
          <option value="0"<?php if (!(strcmp("", $_GET['str_numero_oc_op']))) {echo "selected=\"selected\"";} ?>>Orde de Compra</option>
          <?php
do {  
?>
          <option value="<?php echo $row_oc['str_numero_oc']?>"<?php if (!(strcmp($row_oc['str_numero_oc'], $_GET['str_numero_oc_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_oc['str_numero_oc']?></option>
          <?php
} while ($row_oc = mysql_fetch_assoc($oc));
  $rows = mysql_num_rows($oc);
  if($rows > 0) {
      mysql_data_seek($oc, 0);
	  $row_oc = mysql_fetch_assoc($oc);
  }
?>
        </select></td>
        <td colspan="2"  nowrap="nowrap" id="fuente1"><select name="int_cod_ref_op" id="int_cod_ref_op" onchange="if(form1.int_cod_ref_op.value) { consulta_ref_op(); } else{ alert('Debe Seleccionar una REFERENCIA'); }" style="width:50px">
          <option value=""<?php if (!(strcmp("", $_GET['int_cod_ref_op']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
          <?php
do {  
?>
          <option value="<?php echo $row_ref_items['int_cod_ref_io']?>"<?php if (!(strcmp($row_ref_items['int_cod_ref_io'], $_GET['int_cod_ref_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ref_items['int_cod_ref_io']?></option>
          <?php
} while ($row_ref_items = mysql_fetch_assoc($ref_items));
  $rows = mysql_num_rows($ref_items);
  if($rows > 0) {
      mysql_data_seek($ref_items, 0);
	  $row_ref_items = mysql_fetch_assoc($ref_items);
  }
?>
          </select>
          -
  <input name="version_ref_op" type="number" id="version_ref_op" min="0" max="9" size="2" value="<?php echo $row_datos_oc['version_ref']; ?>" readonly="readonly" required="required" /></td>
        <td colspan="2" id="fuente1"><input type="number" style="width:60px" min="0" step="1" name="int_cotiz_op" id="int_cotiz_op" value="<?php echo $row_datos_oc['n_cotiz_ref'] ?>" required="required"/></td>
        <td colspan="2" id="fuente1"><select name="str_entrega_op" id="str_entrega_op">
          <option value="N.A">N.A</option>
          <option value="PARCIAL">PARCIAL</option>
          <option value="TOTAL">TOTAL</option>
        </select></td>
      </tr>
      <tr>
        <td id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td colspan="11" id="titulo4">CONDICIONES DE FABRICACI&Oacute;N EN EXTRUSI&Oacute;N </td>
      </tr>
      <tr>
        <td id="talla1">DESPERDICIO</td>
        <td colspan="2" id="talla1"><?php if (!(strcmp("LAMINA", $row_datos_oc['tipo_bolsa_ref']))) {echo "KILOS SOLICITADOS";}else{echo "UNIDADES SOLICITADAS";} ?></td>
        <td colspan="2" id="talla1">TIPO DE BOLSA</td>
        <td colspan="2" id="talla1">PESO MILLAR</td>
        <td colspan="2" id="talla1">METROS LINEAL</td>
      </tr>
      <tr>
        <td id="talla1"><input id="int_desperdicio_op" name="int_desperdicio_op" type="number" value="" min="0" max="50" step="1" style="width:40px" required="required" onchange="calcular_op()"/>
          %<strong></strong></td>
        <td colspan="2" id="fuente1"><strong>
          <input type="number" name="int_cantidad_op" id="int_cantidad_op" value="<?php echo $row_datos_oc['int_cantidad_rest_io']?>" style="width:80px" onchange="calcular_op()" step="0.01" required="required"/>
        </strong></td>
        <td colspan="2" id="fuente1"><select name="str_tipo_bolsa_op" id="str_tipo_bolsa_op">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="SEGURIDAD" <?php if (!(strcmp("SEGURIDAD", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
          <option value="CURRIER" <?php if (!(strcmp("CURRIER", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>CURRIER</option>
          <option value="BOLSA PLASTICA" <?php if (!(strcmp("BOLSA PLASTICA", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
          <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
          <option value="PACKING LIST" <?php if (!(strcmp("PACKING LIST", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>PACKING LIST</option>
          <option value="LAMINA" <?php if (!(strcmp("LAMINA", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>LAMINA</option>
        </select></td>
        <td colspan="2" id="fuente1"><input id="int_pesom_op" name="int_pesom_op" style="width:60px" type="number" min="0" step="0.01" value="<?php echo $row_datos_oc['peso_millar_ref'] ?>" required="required" /></td>
        <td colspan="2" id="fuente1"><input id="metroLineal_op" name="metroLineal_op" style="width:70px" type="number" min="0" size="5" step="0.01" required="required" onblur="calcular_op();"/></td>
      </tr>
      <tr>
        <td id="talla1">MATERIAL</td>
        <td colspan="2" id="talla1">PRESENTACION</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        </tr>
      <tr>
        <td id="fuente1"><input type="text" name="str_matrial_op" id="str_matrial_op" size="12" value="<?php echo $row_datos_oc['material_ref']; ?>" /></td>
        <td colspan="2" id="fuente1"><select name="str_presentacion_op" id="str_presentacion_op" onchange="calcular_op();">
          <option value="N.A"<?php if (!(strcmp('N.A', $row_datos_oc['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="LAMINA" <?php if (!(strcmp('LAMINA', $row_datos_oc['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>LAMINA</option>
          <option value="TUBULAR" <?php if (!(strcmp('TUBULAR', $row_datos_oc['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>TUBULAR</option>
          <option value="SEMITUBULAR" <?php if (!(strcmp('SEMITUBULAR', $row_datos_oc['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>SEMITUBULAR</option>
        </select></td>
        <td colspan="2" id="talla1">CANT. KLS REQUERIDOS</td>
        <td colspan="2" id="fuente1"><input id="int_kilos_op" name="int_kilos_op" type="number" min="0" style="width:60px" step="0.01" readonly="readonly"required="required"/></td>
        <td colspan="2" id="talla1">ANCHO DEL ROLLO</td>
        </tr>
      <tr>
        <td id="talla1">PIGMENTO INTERNO</td>
        <td colspan="2" id="talla1">PIGMENTO EXTERNO</td>
        <td colspan="2" id="talla1">CALIBRE</td>
        <td colspan="2" id="fuente1"><input id="int_calibre_op" name="int_calibre_op" readonly="readonly" style="width:60px" type="number" min="0" step="0.01" value="<?php echo $row_datos_oc['calibre_ref'] ?>" onblur="calcular_op()" required="required"/></td>
        <td colspan="2" id="fuente1"><input id="int_ancho_rollo_op" name="int_ancho_rollo_op" style="width:70px" type="number" min="0"max="500" size="5" step="0.01" required="required" onblur="calcular_op();"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="str_interno_op" type="text" id="str_interno_op" value="<?php echo $row_datos_oc['pigm_int_epg']; ?>" size="12" /></td>
        <td colspan="2" id="fuente1"><input name="str_externo_op" type="text" id="str_externo_op" value="<?php echo $row_datos_oc['pigm_ext_egp']; ?>" size="12" /></td>
        <td colspan="2" id="talla1">MICRAS</td>
        <td colspan="2" id="fuente1"><input id="int_micras_op" name="int_micras_op" style="width:60px" type="number" min="0" step="0.01" readonly="readonly" required="required"/></td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" id="fuente1">&nbsp;</td>
        <td colspan="2" id="talla1">TRATAMIENTO CORONA</td>
        <td colspan="4" id="fuente1"><select name="str_tratamiento_op" id="str_tratamiento_op">
          <option value="N.A"<?php if (!(strcmp('N.A', $row_datos_oc['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="UNA CARA" <?php if (!(strcmp('UNA CARA', $row_datos_oc['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
          <option value="DOBLE CARA" <?php if (!(strcmp('DOBLE CARA', $row_datos_oc['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
        </select></td>
        </tr>      
      <tr id="tr1">
        <td colspan="11" id="titulo1">Observacion en Extrusion</td>
        </tr>      
      <tr>
        <td id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
      </tr>
      <tr id="tr1">
        <td colspan="11" id="fuente1"><textarea name="observ_extru_op" cols="80" rows="1"></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="11" id="titulo4">CONDICIONES DE FABRICACI&Oacute;N EN IMPRESION</td>
        </tr>
        <tr>
        <td id="talla1">IMPRIME EN MAQUINA:</td>
        <td id="fuente1"><select name="maquina_imp_op" id="maquina_imp_op" style="width:100px">
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
          </select></td>
        <td id="talla1">KLS MATERIAL REQUERIDO:</td>
        <td colspan="2" id="fuente1"><input name="kls_req_imp_op" id="kls_req_imp_op" style="width:70px" type="number" min="0" step="0.01" onblur="calcular_op();"/></td>
        <td colspan="2" id="talla1">METROS APROXIMADOS:</td>
        <td colspan="2" id="fuente1"><input name="mts_req_imp_op" id="mts_req_imp_op" style="width:70px" type="number" min="0" step="0.01" /></td>
      </tr>
         <?php if ($row_datos_oc['tipo_bolsa_ref']=="LAMINA") { ?>
      <tr>
        <td id="talla1"><strong>UNIDADES X CAJA:</strong></td>
        <td id="talla1"><input name="int_undxcaja_op" style="width:50px" type="number" id="int_undxcaja_op" value="<?php echo $row_datos_oc['unids_caja_egp']; ?>" size="12" min="0"/></td>
        <td id="talla1"><strong>UNIDADES X PAQUETE:</strong></td>
        <td colspan="2" id="talla1"><input name="int_undxpaq_op" style="width:50px" type="number" id="int_undxpaq_op" value="<?php echo $row_datos_oc['unids_paq_egp']; ?>" size="12" min="0"/></td>
        <td colspan="6" id="talla1">&nbsp;</td>
        </tr>
        <?php }?>     
        <tr>
          <td colspan="9" id="talla2"><hr><hr/></td>
          </tr>
      <tr>	
        <td rowspan="2" id="talla1">MARGENES</td>
        <td id="talla1">Izquierda mm</td>
        <td id="fuente1"><input name="margen_izq_imp_op" id="margen_izq_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_izq_imp_op'];?>"/></td>
        <td id="talla1">Rep. en Ancho</td>
        <td id="fuente1"><input name="margen_anc_imp_op" id="margen_anc_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_anc_imp_op']?>"/></td>
        <td colspan="2" id="talla2">de</td>
        <td id="fuente1"><input name="margen_anc_mm_imp_op" id="margen_anc_mm_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_anc_mm_imp_op']?>"/></td>
        <td id="talla1">mm</td>
      </tr>
      <tr>
        <td id="talla1">Derecha mm</td>
        <td id="fuente1"><input name="margen_der_imp_op" id="margen_der_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_der_imp_op']?>"/></td>
        <td id="talla1">Rep. Perimetro</td>
        <td id="fuente1"><input name="margen_peri_imp_op" id="margen_peri_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_peri_imp_op']?>"/></td>
        <td colspan="2" id="talla2">de</td>
        <td id="fuente1"><input name="margen_per_mm_imp_op" id="margen_per_mm_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_per_mm_imp_op']?>"/></td>
        <td id="talla1">mm</td>
      </tr>
      <tr>
        <td id="talla1">&nbsp;</td>
        <td id="talla1"><strong>Z</strong></td>
        <td id="fuente1"><input name="margen_z_imp_op" id="margen_z_imp_op" style="width:50px" type="number" min="0" step="0.01" value="<?php echo $row_margenes['margen_z_imp_op']?>"/></td>
        <td colspan="6" id="talla1">&nbsp;</td>
        </tr>
      <tr>  
        <td colspan="11">
          <table>
            <tr> 
              <td valign="top">
              <div id="cajon1">
                <?php if($totalRows_unidad_uno!='0') { ?>              
                
                <table>
                  <tr id="tr1">
                    <td nowrap id="fuente1"><strong>UNIDAD 1</strong></td>
                    <td nowrap id="fuente2">DESCRIPCION</td>
                    <td nowrap id="fuente1">VALOR</td>
                    </tr>
                  <?php  for ($y=0;$y<=5;$y++) { ?><!--for ($y=0;$y<=$totalRows_unidad_uno-1;$y++)// TRAE TODOS LOS REGISTROS--> 
                  <tr>
                    <td  nowrap id="talla1"><?php $id_m=mysql_result($unidad_uno,$y,str_nombre_m);echo $id_m; ?></td>
                    <td  id="talla1"><?php $var=mysql_result($unidad_uno,$y,descripcion_insumo); echo $var;?></td>           
                    <td id="talla3"><?php $var1=mysql_result($unidad_uno,$y,str_valor_pmi); echo $var1; ?></td>         
                    </tr>  <?php  } ?>                                        
                  </table> 
                <?php  } ?>       
                </div>
                <div id="cajon1">
                <?php if($totalRows_unidad_dos!='0') { ?>               
                <table>
                  <tr id="tr1">
                    <td nowrap id="fuente1"><strong>UNIDAD 2</strong></td>
                    <td nowrap id="fuente2">DESCRIPCION</td>
                    <td nowrap id="fuente1">VALOR</td>
                    </tr>
                  <?php  for ($x=0;$x<=5;$x++) { ?><tr>
                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_dos,$x,str_nombre_m);echo $id_m; ?></td>           
                    <td  id="talla1"><?php $var=mysql_result($unidad_dos,$x,descripcion_insumo); echo $var;?></td>           
                    <td id="talla3"><?php $var1=mysql_result($unidad_dos,$x,str_valor_pmi); echo $var1; ?></td>         
                    </tr>  <?php  } ?>                                        
                  </table>
                <?php  } ?>       
               </div>
               <div id="cajon1">
              <?php if($totalRows_unidad_tres!='0') { ?>
                <table>
                  <tr id="tr1">
                    <td nowrap id="fuente1"><strong>UNIDAD 3</strong></td>
                    <td nowrap id="fuente2">DESCRIPCION</td>
                    <td nowrap id="fuente1">VALOR</td>
                    </tr>
                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                    
                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_tres,$y,str_nombre_m);echo $id_m; ?></td>
                    <td id="talla1"><?php $var=mysql_result($unidad_tres,$y,descripcion_insumo); echo $var;?></td>
                    <td id="talla3"><?php $var1=mysql_result($unidad_tres,$y,str_valor_pmi); echo $var1; ?></td>
                    </tr>
                  <?php  } ?>
                  </table>
                <?php  } ?> 
				 </div>
               <div id="cajon1">
				 <?php if($totalRows_unidad_cuatro!='0') { ?>
                <table>
                  <tr id="tr1">
                    <td nowrap id="fuente1"><strong>UNIDAD 4</strong></td>
                    <td nowrap id="talla1">DESCRIPCION</td>
                    <td nowrap id="fuente1">VALOR</td>
                    </tr>
                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                    
                    <td nowrap  id="talla1"><?php $id_m=mysql_result($unidad_cuatro,$y,str_nombre_m);echo $id_m; ?></td>
                    <td   id="fuente1"><?php $var=mysql_result($unidad_cuatro,$y,descripcion_insumo); echo $var; ?></td>
                    <td id="talla3"><?php $var=mysql_result($unidad_cuatro,$y,str_valor_pmi); echo $var; ?></td>
                    </tr>
                  <?php  } ?>
                  </table>
                <?php  } ?> 
                </div>
               <div id="cajon1">
               <?php if($totalRows_unidad_cinco!='0') { ?>
                <table>
                  <tr id="tr1">
                    <td nowrap id="fuente1"><strong>UNIDAD 5</strong></td>
                    <td nowrap id="fuente2">DESCRIPCION</td>
                    <td nowrap id="fuente1">VALOR</td>
                    </tr>
                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                    
                    <td  nowrap id="talla1"><?php $id_m=mysql_result($unidad_cinco,$y,str_nombre_m);echo $id_m; ?></td>
                    <td   id="talla1"><?php $var=mysql_result($unidad_cinco,$y,descripcion_insumo); echo $var;?></td>
                    <td id="talla3"><?php $var=mysql_result($unidad_cinco,$y,str_valor_pmi); echo $var; ?></td>
                    </tr>
                  <?php  } ?>
                  </table>
                <?php  } ?>
                </div>
               <div id="cajon1"> 
				<?php if($totalRows_unidad_seis!='0') { ?>
                <table>
                  <tr id="tr1">
                    <td nowrap id="fuente1"><strong>UNIDAD 6</strong></td>
                    <td nowrap id="fuente2">DESCRIPCION</td>
                    <td nowrap id="fuente1">VALOR</td>
                    </tr>
                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                    
                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_seis,$y,str_nombre_m);echo $id_m; ?></td>
                    <td  id="talla1"><?php $var=mysql_result($unidad_seis,$y,descripcion_insumo); echo $var;?></td>
                    <td id="talla3"><?php $var=mysql_result($unidad_seis,$y,str_valor_pmi); echo $var; ?></td>
                    </tr>
                  <?php  } ?>
                  </table>
                <?php  } ?>
                </div>
               <div id="cajon1"> 
				<?php if($totalRows_unidad_siete!='0') { ?>
                <table>
                  <tr id="tr1">
                    <td nowrap id="fuente1"><strong>UNIDAD 7</strong></td>
                    <td nowrap id="fuente2">DESCRIPCION</td>
                    <td nowrap id="fuente1">VALOR</td>
                    </tr>
                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                    
                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_siete,$y,str_nombre_m);echo $id_m; ?></td>
                    <td  id="talla1"><?php $var=mysql_result($unidad_siete,$y,descripcion_insumo); echo $var;?></td>
                    <td id="talla3"><?php $var=mysql_result($unidad_siete,$y,str_valor_pmi); echo $var; ?></td>
                    </tr>
                  <?php  } ?>
                  </table>
                <?php  } ?>
                </div>
               <div id="cajon1">
                <?php if($totalRows_unidad_ocho!='0') { ?>
                <table>
                  <tr id="tr1">
                    <td nowrap id="fuente1"><strong>UNIDAD 8</strong></td>
                    <td nowrap id="fuente2">DESCRIPCION</td>
                    <td nowrap id="fuente1">VALOR</td>
                    </tr>
                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                    
                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_ocho,$y,str_nombre_m);echo $id_m;?></td>
                    <td  id="talla1"><?php $var=mysql_result($unidad_ocho,$y,descripcion_insumo); echo $var; ?></td>
                    <td id="talla3"><?php $var=mysql_result($unidad_ocho,$y,str_valor_pmi); echo $var; ?></td>
                    </tr>
                  <?php  } ?>
                  </table>
                <?php  } ?>
                </div>
               </td>
              </tr>
            </table> 
          
          </td>
      </tr>
      <tr id="tr1">
        <td colspan="11" id="titulo1">Observacion en Impresion</td>
        </tr>      
      <tr>
        <td id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
      </tr>
      <tr id="tr1">
        <td colspan="11" id="fuente1"><textarea name="observ_impre_op" cols="80" rows="1" id="observ_impre_op"></textarea></td>
        </tr>
      <tr>
        <td colspan="11" id="fuente5">&nbsp;</td>
        </tr>
                <!--SELLADO NO APARECE SI ES LAMINA EXCEPTO UND X CAJAS Y PAQUETES-->
                
        <?php if ((strcmp("LAMINA", $row_datos_oc['tipo_bolsa_ref']))) { ?>
<tr id="tr1">
        <td colspan="11" id="titulo4">CONDICIONES DE FABRICACI&Oacute;N EN SELLADO</td>
        </tr>        
        
      <tr>
        <td colspan="11">
        <table>
  <tr>
    <td id="talla1"><strong>ANCHO</strong></td>
    <td id="talla1"><strong>LARGO</strong></td>
    <td colspan="3" id="talla1"><strong>SOLAPA 
      (
      <?php if ($row_datos_oc['b_solapa_caract_ref']==2) {echo "Sencilla";}else if ($row_datos_oc['b_solapa_caract_ref']==1){echo "Doble";}else {echo "";} ?>
      <input type="hidden" name="valor_s" id="valor_s" value="<?php echo $row_datos_oc['b_solapa_caract_ref']; ?>" />
      )</strong></td>
    <td colspan="2" id="talla1"><strong>BOLSILLO PORTAGUIA</strong></td>
  </tr>
  <tr>
    <td id="talla1"><?php echo $row_datos_oc['ancho_ref']; ?>
      <input type="hidden" name="ancho" id="ancho" value="<?php echo $row_datos_oc['ancho_ref']; ?>"/></td>
    <td id="talla1"><?php echo $row_datos_oc['largo_ref']; ?>
      <input type="hidden" name="largo" id="largo" value="<?php echo $row_datos_oc['largo_ref']; ?>" /></td>
    <td id="talla1"><?php echo $row_datos_oc['solapa_ref']; ?>
      <input type="hidden" name="solapa" id="solapa" value="<?php echo $row_datos_oc['solapa_ref']; ?>" /></td>
    <td id="talla1">&nbsp;</td>
    <td id="talla1">&nbsp;</td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['bolsillo_guia_ref']; ?></td>
  </tr>
  <tr>
    <td id="talla1"><strong>CALIBRE</strong></td>
    <td id="talla1"><strong>PESO MILLAR</strong></td>
    <td id="talla1"><strong>TIPO DE BOLSA </strong></td>
    <td id="talla1">&nbsp;</td>
    <td id="talla1">FUELLE</td>
    <td colspan="2" id="talla1"><strong>ADHESIVO</strong></td>
  </tr>
  <tr>
    <td id="talla1"><?php echo $row_datos_oc['calibre_ref']; ?></td>
    <td id="talla1"><?php echo $row_datos_oc['peso_millar_ref']; ?></td>
    <td id="talla1"><?php echo $row_datos_oc['tipo_bolsa_ref']; ?></td>
    <td id="talla1">&nbsp;</td>
    <td id="talla1"><?php echo $row_datos_oc['N_fuelle']; ?>
      <input type="hidden" name="fuelle" id="fuelle" value="<?php echo $row_datos_oc['N_fuelle']; ?>" /></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['adhesivo_ref']; ?></td>
  </tr>
  <tr>
    <td rowspan="2" id="talla1"><strong>PRESENTACION</strong></td>
    <td rowspan="2" id="talla1"><strong>TRATAMIENTO CORONA</strong></td>
    <td colspan="5" id="talla2"><strong>Bolsillo Portaguia</strong></td>
    </tr>
  <tr>
    <td id="talla1"> <strong>(Ubicacion)</strong></td>
    <td id="talla1">Peso Millar Bols.
      <input type="hidden" name="calibre_bols" id="calibre_bols" value="<?php echo $row_datos_oc['calibreBols_ref']; ?>" /></td>
    <td id="talla1"><strong>(Forma)</strong></td>
    <td id="talla1"><strong>(Lamina 1)</strong></td>
    <td id="talla1"><strong>(Lamina 2)</strong></td>
  </tr>
  <tr>
    <td id="talla1"><?php echo $row_datos_oc['Str_presentacion']; ?></td>
    <td id="talla1"><?php echo $row_datos_oc['Str_tratamiento']; ?></td>
    <td id="talla1"><?php echo $row_datos_oc['str_bols_ub_ref']; ?></td>
    <td id="talla1"><?php echo $row_datos_oc['peso_millar_bols']; ?></td>
    <td id="talla1"><?php echo $row_datos_oc['str_bols_fo_ref']; ?></td>
    <td id="talla1"><input type="hidden" name="lam1" id="lam1" value="<?php echo $row_datos_oc['bol_lamina_1_ref']; ?>" />
      <?php echo $row_datos_oc['bol_lamina_1_ref']; ?></td>
    <td id="talla1"><input type="hidden" name="lam2" id="lam2" value="<?php echo $row_datos_oc['bol_lamina_2_ref']; ?>" />
      <?php echo $row_datos_oc['bol_lamina_2_ref']; ?></td>
  </tr>
  <tr>
    <td id="talla1"><strong>TIPO DE SELLO:</strong></td>
    <td id="talla1"><strong>UNIDADES X CAJA:</strong></td>
    <td id="talla1"><strong>UNIDADES X PAQUETE:</strong></td>
    <td id="talla1">MEDIDA DE LA CAJA</td>
    <td id="talla1"><strong>PRECORTE (Bolsillo Portaguia):</strong></td>
    <td id="talla1">Nueva Numeracion inicial:</td>
    <td id="talla1">Ultima Numeracion:</td>
    </tr>
  <tr>
    <td id="talla1"><?php echo $row_datos_oc['tipo_sello_egp']; ?></td>
    <td id="talla1"><input name="int_undxcaja_op" style="width:50px" type="number" id="int_undxcaja_op" value="<?php echo $row_datos_oc['unids_caja_egp']; ?>" required="required" size="12" min="0"  onBlur="alerta()"/></td>
    <td id="talla1"><input name="int_undxpaq_op" style="width:50px" type="number" id="int_undxpaq_op" value="<?php echo $row_datos_oc['unids_paq_egp']; ?>" required="required" size="12" min="0"  onBlur="alerta()"/></td>
    <td id="talla1"><select name="marca_cajas_egp" id="opciones" style="width:100px" onBlur="return primeraletra(this),alerta()">
      <option value=""<?php if (!(strcmp(0, $row_datos_oc['marca_cajas_egp']))) {echo "selected=\"selected\"";}?>>SELECCIONE</option>
      <?php
do {  
?>
      <option value="<?php echo $row_insumo['id_insumo']?>"<?php if (!(strcmp($row_insumo['id_insumo'], $row_datos_oc['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo['descripcion_insumo']?></option>
      <?php
} while ($row_insumo = mysql_fetch_assoc($insumo));
  $rows = mysql_num_rows($insumo);
  if($rows > 0) {
      mysql_data_seek($insumo, 0);
	  $row_insumo = mysql_fetch_assoc($insumo);
  }
?>
    </select></td>
    <td id="talla1"><?php if($row_datos_oc['B_troque']=='1') {echo "SI";}else{echo "NO";}; ?></td>
    <td id="talla1">
    <input name="numInicio_op" type="text" id="numInicio_op" style="width:120px" min="0" step="1" value="<?php if($row_numeracion['int_hasta_tn']!=''){ echo $row_numeracion['int_hasta_tn'];}else{echo "0";} ?>" required="required" onChange="conMayusculas(this),hastaordenP(this);" /></td>
    <td id="talla1"><?php echo $row_numeracion['int_hasta_tn']; ?></td>
  </tr>
  <tr>
    <td id="talla4">&nbsp;</td>
    <td colspan="6" id="talla4">&nbsp;<div id="resultado_generador"></div></td>
    </tr>
  <tr>
    <td colspan="2" id="talla1"><strong>POSICION</strong></td>
    <td colspan="3" id="talla1"><strong>TIPO DE NUMERACION </strong></td>
    <td colspan="2" id="talla1"><strong>BARRAS &amp; FORMATO</strong></td>
  </tr>
  <tr>
    <td colspan="2" id="talla1"><strong>Solapa Talonario Recibo</strong></td>
    <td colspan="3" id="talla1"><?php echo $row_datos_oc['tipo_solapatr_egp']; ?></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_solapatr_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="talla1"><strong>Cinta</strong></td>
    <td colspan="3" id="talla1"><?php echo $row_datos_oc['tipo_cinta_egp']; ?></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_cinta_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="talla1"><strong>Superior</strong></td>
    <td colspan="3" id="talla1"><?php echo $row_datos_oc['tipo_superior_egp']; ?></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_superior_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="talla1"><strong>Principal</strong></td>
    <td colspan="3" id="talla1"><?php echo $row_datos_oc['tipo_principal_egp']; ?></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="talla1"><strong>Inferior</strong></td>
    <td colspan="3" id="talla1"><?php echo $row_datos_oc['tipo_inferior_egp']; ?></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_inferior_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="talla1"><strong>Liner</strong></td>
    <td colspan="3" id="talla1"><?php echo $row_datos_oc['tipo_liner_egp']; ?></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_liner_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="talla1"><strong>Bolsillo</strong></td>
    <td colspan="3" id="talla1"><?php echo $row_datos_oc['tipo_bols_egp']; ?></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_bols_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="talla1">&nbsp;<?php echo $row_datos_oc['tipo_nom_egp']; ?></td>
    <td colspan="3" id="talla1"><?php echo $row_datos_oc['tipo_otro_egp']; ?></td>
    <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_otro_egp']; ?></td>
  </tr>  
  <tr>
    <td colspan="2" id="talla1">METROS CINTA / LINER</td>
    <td id="talla1">KILOS A SELLAR BOLSA</td>
    <td colspan="2" id="talla1">KILOS A SELLAR DE BOLSILLO</td>
    <td colspan="2" id="talla1">UNIDADES A PRODUCIR</td>
  </tr>
  <tr>
    <td colspan="2" id="talla1"><input name="mts_cinta_sellado_op" id="mts_cinta_sellado_op" style="width:70px" type="number" min="0" size="5" step="0.01" onblur="calcular_op();"/></td>
    <td id="talla1"><input name="kls_sellado_op" id="kls_sellado_op" style="width:70px" type="number" min="0" size="5" step="0.01" onblur="calcular_op();"/></td>
    <td colspan="2" id="talla1"><input name="kls_sellado_bol_op" id="kls_sellado_bol_op" onclick="metrosAkilos()" style="width:70px" type="number" min="0" size="5" step="0.01"/></td>
    <td colspan="2" id="talla1"><input name="und_prod_sellado_op" type="number" id="und_prod_sellado_op" style="width:70px" min="0" step="0.01" value="<?php echo $row_datos_oc['int_cantidad_io']?>"/></td>
  </tr>
        </table>
        </td>
      </tr>
      <tr>
        <td colspan="11" id="talla1"><strong>Nota:</strong> Si en el campo de la version de la referencia no coninciden es porque no han hecho la verificacion u modificacion en dise&ntilde;o y desarrollo.</td>
      </tr>     
      <tr id="tr1">
        <td colspan="11" id="titulo1">Observacion en Sellado</td>
        </tr>      
      <tr>
        <td id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
      </tr>
      <tr id="tr1">
        <td colspan="11" id="fuente1"><textarea name="observ_sellado_op" cols="80" rows="1" id="observ_sellado_op"></textarea></td>
        </tr>
        <?php }?>
      <tr>
        <td colspan="11" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="11" id="dato2"><input name="id_proceso" type="hidden" value="1" />
          <input name="b_estado_op" type="hidden" value="0" />
          <input name="b_borrado_op" type="hidden" id="b_borrado_op" value="0" />
          <input name="id_ref_op" type="hidden" id="id_ref_op" value="<?php echo $row_datos_oc['id_ref'] ?>" />
          <input type="hidden" name="MM_insert" value="form1" />          <input type="submit" name="GUARDAR" id="GUARDAR" value="GUARDAR" /></td>
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
mysql_free_result($ref_items);
mysql_free_result($oc);
mysql_free_result($datos_oc);
mysql_free_result($ref_mm);
mysql_free_result($materia_prima);
mysql_free_result($unidad_uno);
mysql_free_result($unidad_dos);
mysql_free_result($unidad_tres);
mysql_free_result($unidad_cuatro);
mysql_free_result($unidad_cinco);
mysql_free_result($unidad_seis);
mysql_free_result($unidad_siete);
mysql_free_result($unidad_ocho);
mysql_free_result($maquinas);
?>
