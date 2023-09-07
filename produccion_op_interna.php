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
//INSERT
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Tbl_orden_produccion (id_op, fecha_registro_op,fecha_entrega_op, str_responsable_op, str_numero_oc_op, int_cod_ref_op, id_ref_op, version_ref_op, int_cotiz_op, str_entrega_op, str_nit_op, int_cliente_op, int_desperdicio_op, int_cantidad_op, str_tipo_bolsa_op, int_pesom_op, str_matrial_op, str_presentacion_op, metroLineal_op, int_kilos_op, int_calibre_op, int_ancho_rollo_op, int_micras_op, str_interno_op, str_externo_op, str_tratamiento_op, int_undxcaja_op, int_undxpaq_op,undxpaqreal, numInicio_op, observ_extru_op,maquina_imp_op, 
    kls_req_imp_op, mts_req_imp_op, margen_izq_imp_op, margen_anc_imp_op, margen_anc_mm_imp_op, margen_der_imp_op, margen_peri_imp_op, margen_per_mm_imp_op, margen_z_imp_op, observ_impre_op, mts_cinta_sellado_op, kls_sellado_op, kls_sellado_bol_op, und_prod_sellado_op, observ_sellado_op, b_estado_op, b_borrado_op, b_visual_op,id_termica_op, cinta_termica_op, numeracion_inicial,imprimiop,lote,charfin) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
    GetSQLValueString($_POST['int_desperdicio_op'], "text"),
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
    GetSQLValueString($_POST['undxpaqreal'], "int"),
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
    GetSQLValueString($_POST['kls_sellado_bol_op'], "double"),
    GetSQLValueString($_POST['und_prod_sellado_op'], "double"),
    GetSQLValueString($_POST['observ_sellado_op'], "text"),             
    GetSQLValueString($_POST['b_estado_op'], "int"),
    GetSQLValueString($_POST['b_borrado_op'], "int"),
    GetSQLValueString($_POST['b_visual_op'], "int"),
    GetSQLValueString($_POST['id_termica_op'], "int"),
    GetSQLValueString($_POST['cinta_termica_op'], "double"),
    GetSQLValueString($_POST['numInicio_op'], "text"),
    GetSQLValueString($_POST['imprimiop'], "text"),
    GetSQLValueString($_POST['lote'], "text"),
    GetSQLValueString($_POST['charfin'], "text"));
mysql_select_db($database_conexion1, $conexion1);
$Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
                                       
  $insertSQL2 = sprintf("INSERT INTO Tbl_op_proceso (id_op,id_proceso) VALUES (%s, %s)",
                       GetSQLValueString($_POST['id_op'], "int"),
             GetSQLValueString($_POST['id_proceso'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());
  
  $insertSQL3 = sprintf("INSERT INTO Tbl_orden_compra_interna (numero_ocI) VALUES (%s)",
                       GetSQLValueString($_POST['str_numero_oc_op'], "text")); 

  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($insertSQL3, $conexion1) or die(mysql_error());  
  
  $insertGoTo = "produccion_op_vista.php?id_op=" . $_POST['id_op'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


$conexion = new ApptivaDB();//consultas
 
//ORDEN DE PRODUCCION
$row_orden_produccion = $conexion->buscarId("tbl_orden_produccion", "id_op");
//CARGA REF
$row_ref = $conexion->llenaSelect("tbl_referencia", "WHERE estado_ref='1' ", "ORDER BY n_egp_ref  DESC" );
 
//CARGA O.C INTERNA
if($_GET['int_cod_ref_op']!='')
$row_oc = $conexion->buscarId("tbl_orden_compra_interna", "numero_ocI");
 
//PRECIOS O.C
if($_GET['int_cod_ref_op']!='')
$row_precio = $conexion->llenarCampos("tbl_items_ordenc oci", "WHERE oci.int_cod_ref_io='".$_GET['int_cod_ref_op']."' ", "  ", " oci.int_precio_io ");

//CARGA CON O.C
if($_GET['int_cod_ref_op']!='')
$row_datos_oc = $conexion->llenarCampos("Tbl_referencia,Tbl_egp", "WHERE Tbl_referencia.cod_ref='".$_GET['int_cod_ref_op']."' AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp  AND Tbl_referencia.estado_ref='1'", " ORDER BY Tbl_referencia.cod_ref DESC ", " * ");

//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 1
$id_ref=$row_datos_oc['id_ref'];
//CARGA UNIDAD 1
if($id_ref!='')
//$row_mezclas = $conexion->llenaSelect("insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion", "WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='".$id_ref."' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo ", " ");

//MAQUINAS
$row_maquinas = $conexion->llenaSelect('maquina', "WHERE proceso_maquina='2' ","ORDER BY id_maquina DESC");
 
//PARA SUMAR CANTIDAD YA INGRESADOS DE LA O.C Y SU ITEM
if($_GET['int_cod_ref_op']!='')
$row_margenes = $conexion->llenarCampos("tbl_referencia,tbl_egp", "WHERE Tbl_referencia.cod_ref='".$_GET['int_cod_ref_op']."' AND Tbl_referencia.cod_ref=Tbl_egp.n_egp  ", " ORDER BY Tbl_referencia.cod_ref DESC ", " * ");
 
$row_clientes = $conexion->llenaSelect('cliente', " ","ORDER BY nombre_c ASC");
 
if($_GET['int_cliente_op']!='')
$row_nit = $conexion->llenarCampos("cliente", "WHERE id_c='".$_GET['int_cliente_op']."' ", "  ", " nit_c ");

//INSUMOS TIPO DE CAJA
$row_insumo = $conexion->llenaSelect('insumo', "WHERE clase_insumo IN ('2') ","ORDER BY descripcion_insumo ASC");
 
//INSUMOS TIPO CINTA TERMICA

$row_insumo3 = $conexion->llenaSelect('insumo', "WHERE clase_insumo IN ('30','33','32') AND estado_insumo='0' ","ORDER BY descripcion_insumo ASC");

$refop = $_GET['int_cod_ref_op'] == '' ? 0 : $_GET['int_cod_ref_op'];
if($refop!='')
$row_referencia = $conexion->llenarCampos('tbl_referencia as ref', "  WHERE ref.cod_ref='".$refop."' ", '',"ref.id_ref,ref.n_egp_ref,ref.tipoCinta_ref" );

//arte
if($id_ref!='')
$row_ref_verif = $conexion->llenarCampos("verificacion", "WHERE id_ref_verif='".$id_ref."' ", "ORDER BY version_ref_verif DESC", " userfile,estado_arte_verif ");

if($_GET['int_cod_ref_op']!='')
$row_numeraciones = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE ref_tn='".$_GET['int_cod_ref_op']."' ","  ORDER BY int_caja_tn DESC, int_paquete_tn DESC", "int_hasta_tn"); //ORDER BY id_tn DESC, int_op_tn DESC,int_hasta_tn DESC

if($_GET['int_cod_ref_op']!='')
$row_ultima_numeraciones = $conexion->llenarCampos('tbl_orden_produccion', " WHERE int_cod_ref_op='".$_GET['int_cod_ref_op']."' ", " ORDER BY  id_op  DESC ","undxpaqreal,numeracion_inicial" ); 

$row_mezclaycaract_impresion = $conexion->llenarCampos("tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref"," WHERE cp.cod_ref= '".$_GET['int_cod_ref_op']."'  AND cp.proceso=2 ", "","*"); 
$row_impresion=$conexion->llenarCampos("tbl_produccion_mezclas cp"," WHERE cp.id_proceso=2 AND cp.int_cod_ref_pm= '".$_GET['int_cod_ref_op']."' ", "","*"); 


/*$row_porcentajeDespExtr = $conexion->llenarCampos('tbl_reg_produccion', " WHERE  id_op_rp in(9992,9891,9833,9696) and int_cod_ref_rp='".$_GET['int_cod_ref_op']."' AND id_proceso_rp='1' AND DATE_SUB(NOW(),INTERVAL 1 YEAR)"," ORDER BY fecha_ini_rp DESC "," `id_op_rp`,`id_proceso_rp`,`int_cod_ref_rp`,`rollo_rp`, `fecha_ini_rp`, sum(int_kilos_prod_rp) as kiloint, SUM(int_kilos_desp_rp) as kilosdesp,   ROUND((sum(int_kilos_desp_rp) * 100 / SUM(int_kilos_prod_rp)),2) as result" );
echo 'Result E: '.$row_porcentajeDespExtr['result'].'<br>';
//Consulto porcentaje desperdicio Impresion
$row_porcentajeDespImp = $conexion->llenarCampos('tbl_reg_produccion', " WHERE  id_op_rp in(9992,9891,9833,9696) and int_cod_ref_rp='".$_GET['int_cod_ref_op']."' AND id_proceso_rp='2' AND DATE_SUB(NOW(),INTERVAL 1 YEAR)"," ORDER BY fecha_ini_rp DESC "," `id_op_rp`,`id_proceso_rp`,`int_cod_ref_rp`,`rollo_rp`, `fecha_ini_rp`, sum(int_kilos_prod_rp) as kiloint, SUM(int_kilos_desp_rp) as kilosdesp,   ROUND((sum(int_kilos_desp_rp) * 100 / SUM(int_kilos_prod_rp)),2) as result" );
echo 'Result I: '.$row_porcentajeDespImp['result'].'<br>';
//Consulto porcentaje desperdicio Sellado
$row_porcentajeDespSell = $conexion->llenarCampos('tbl_reg_produccion', " WHERE  id_op_rp in(9992,9891,9833,9696) and int_cod_ref_rp='".$_GET['int_cod_ref_op']."' AND id_proceso_rp='4' AND DATE_SUB(NOW(),INTERVAL 1 YEAR)"," ORDER BY fecha_ini_rp DESC "," `id_op_rp`,`id_proceso_rp`,`int_cod_ref_rp`,`rollo_rp`, `fecha_ini_rp`, sum(int_kilos_prod_rp) as kiloint, SUM(int_kilos_desp_rp) as kilosdesp,   ROUND((sum(int_kilos_desp_rp) * 100 / SUM(int_kilos_prod_rp)),2) as result" );
echo 'Result S: '.$row_porcentajeDespSell['result'].'<br>';
 echo 'total porc= '.(($row_porcentajeDespExtr['result']+$row_porcentajeDespImp['result']+$row_porcentajeDespSell['result'])/3);*/




//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 1
$id_ref=$row_datos_oc['id_ref'];
 
$row_cualexiste=$conexion->llenarCampos("tbl_produccion_mezclas tmi "," WHERE tmi.id_proceso = 2 AND tmi.id_ref_pm= '".$id_ref."' ", "","id_ref_pm"); 
//CARGA UNIDAD 1
$colname_unidad_uno = "-1";
if (isset($id_ref)) {
  $colname_unidad_uno  = (get_magic_quotes_gpc()) ? $id_ref : addslashes($id_ref);
}
mysql_select_db($database_conexion1, $conexion1);
$query_caract_valor = ("SELECT * FROM Tbl_caracteristicas, Tbl_caracteristicas_valor WHERE Tbl_caracteristicas_valor.id_ref_cv='$id_ref' AND Tbl_caracteristicas.id_c=Tbl_caracteristicas_valor.id_c_cv AND Tbl_caracteristicas.proceso_c='2' ORDER BY Tbl_caracteristicas_valor.id_cv ASC");
$caract_valor = mysql_query($query_caract_valor, $conexion1) or die(mysql_error());
$row_caract_valor = mysql_fetch_assoc($caract_valor);
$totalRows_caract_valor = mysql_num_rows($caract_valor);

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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">


<link rel="stylesheet" type="text/css" href="css/general.css"/>
<link rel="stylesheet" type="text/css" href="css/formato.css"/>
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />


<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<!-- Select3 Nuevo -->
<meta charset="UTF-8">
<!-- jQuery -->
<script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>

<!-- select2 css -->
<link href='select3/assets/plugin/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

<!-- select2 script -->
<script src='select3/assets/plugin/select2/dist/js/select2.min.js'></script>
<!-- Styles -->
<link rel="stylesheet" href="select3/assets/css/style.css">
<!-- Fin Select3 Nuevo -->

<!--VALIDAR EL ENVIO DE FORMULARIO-->
<script type="text/javascript">
function funcion(){ 
/*if(document.form1.mensajeLickRef.value=='1' && document.form1.tipoLamina.value=='1'){ 
alert("La referencia no tiene formula o esta incompleta, debe crearla y Debe agregar el tipo de lamina para bolsillo en la referencia");
return false;
} else
if(document.form1.mensajeLickRef.value=='1')
{  
alert("La referencia no tiene formula o esta incompleta, debe crearla");
return false; 
  } else */
if(document.form1.tipoLamina.value=='1')
{  
alert("Debe agregar el tipo de lamina para bolsillo en la referencia");
return false;
}
  return true;
 }
</script>
<script type="text/javascript">
function alerta(){
  swal({   
   title: "ACTUALIZAR?",   
   text: "Desea Actualizar?, se actualizara caja, paquetes y medidas de la caja tanto en la Referencia como en la O.P",   
   type: "warning",   
   showCancelButton: true,   
   confirmButtonColor: "#DD6B55",   
   confirmButtonText: "Si, Actualizar!",   
   cancelButtonText: "No, Actualizar!",   
   closeOnConfirm: false,   
   closeOnCancel: false }, 
   function(isConfirm){   
     if (isConfirm) {  
       swal("Actualizado!", "El registro se ha Actualizado.", "success"); 
       DatosGestiones3('11','cod_r',document.form1.int_cod_ref_op.value,'&caja',document.form1.int_undxcaja_op.value,'&paq',document.form1.int_undxpaq_op.value,'&medida',document.form1.marca_cajas_egp.value);
     } else {     
       swal("Cancelado", "has cancelado :)", "error");
       //window.history.go();
     } 
   }); 

 
}
</script>
</head>
<!--onLoad="return funcion();"-->
<body onload="hastaordenP();calcular_op(); metrosAkilos();">

  <body>
    <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
      <div align="center">
        <table id="tabla1">
          <tr>
           <td align="center">
             <div class="row-fluid">
               <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
                 <div class="panel panel-primary">
                   <div class="panel-heading"><h2>ORDEN DE PRODUCCI&Oacute;N INTERNA</h2></div>
                    
                 <div class="panel-body"> 
                 <table  align="center" id="tabla_borde"  style="padding-top: 2%;"><tr>
                 <td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
                 <tr><td id="nombreusuario"><?php echo $_SESSION['Usuario']; ?></td>
                   <td id="cabezamenu"><ul id="menuhorizontal">
                   <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                   <li><a href="menu.php">MENU PRINCIPAL</a></li>
                   <li><a href="produccion_mezclas.php" target="new">MEZCLAS</a></li>    
                   </ul>
                 </td>
                 </tr>  
                   <tr>
                     <td colspan="2" align="center">
                   <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1"  onsubmit="return (funcion()&& validacion_tipocinta());">
                     <table id="tabla2">
                       <tr id="tr1">
                         <td colspan="11" id="titulo2">ORDEN DE PRODUCCI&Oacute;N</td>
                         </tr>
                       <tr>
                         <td width="137" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
                         <td colspan="9" id="dato3"><a href="referencias.php" target="new"><img src="images/a.gif" style="cursor:hand;" alt="ADD MEZCLAS" title="ADD MEZCLAS" border="0" /></a>
                         <a href="produccion_ordenes_produccion_listado.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO O.P"title="LISTADO O.P" border="0" /></a>
                         <a href="menu.php" ><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
                         </tr>
                       <tr id="tr1">
                         <td width="182" colspan="2" nowrap="nowrap" id="dato2">Fecha Registro O.P
                           <input name="fecha_registro_op" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10"/>
                           </td>
                         <td colspan="6" id="dato3">
                           Ingresado por
                           <input name="str_responsable_op" type="text" value="<?php echo $_SESSION['Usuario']; ?>" size="27" readonly="readonly"/></td>
                         </tr>
                       <tr id="tr3">
                         <td colspan="2" nowrap="nowrap" id="fuente2">ORDEN DE PRODUCCION N&deg;</td>
                         <td  colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
                         <td nowrap="nowrap" colspan="2" id="fuente2">FECHA ENTREGA </td>
                         <td colspan="2" id="fuente1"><strong>ARTE</strong></td>
                       </tr>
                       <tr id="tr3">
                         <td colspan="2" nowrap="nowrap" id="numero2"><?php $op_numero=$row_orden_produccion['id']+1; ?>
                           <input name="id_op" type="number" style="width:100px" value="<?php echo $op_numero; ?>" readonly="readonly" /></td>
                         <td colspan="2" id="fuente2">&nbsp;</td>
                         <td colspan="2" id="dato2"><input name="fecha_entrega_op" required="required" type="date" value="" /></td>
                         <td colspan="2" id="fuente1"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"><?php echo $row_ref_verif['userfile']; ?></a></td>
                       </tr>
                       <tr id="tr1">
                         <td colspan="9" nowrap="nowrap" id="fuente2">&nbsp;</td>
                         </tr>
                       <tr>
                         <td colspan="4" id="dato3">
                          <?php $cod=$_GET['int_cod_ref_op'];$idr=$row_datos_oc['id_ref'];
                          if($_GET['int_cod_ref_op']!=''){         
                             $cod_ref=$cod;
                             $resultm=$conexion->llenarCampos("tbl_caracteristicas_prod "," WHERE cod_ref= '".$cod_ref."'  AND proceso = '1' ", "ORDER BY cod_ref DESC LIMIT 1","*"); 
                             
                             if($resultm['cod_ref'] =='' ){
                        ?>
                           <a href="view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $cod_ref;?>" target="_blank" style="text-decoration:none; color:#000000"><strong>Agregar la formula</strong></a>
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
                         <td id="talla1">CLIENTE <?php echo $row_nit['nit_c']; ?></td>
                         <td colspan="2" id="talla1">ORDEN DE COMPRA N&deg;</td>
                         <td id="talla1">REFERENCIA - VERSION</td>
                         <td id="talla1">PRECIO O.C</td>
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
                         <td id="fuente1">
                          <select id='opciones2' name='int_cliente_op' style="width:220px" onchange="if(form1.int_cod_ref_op.value) { consulta_ref_op_interna(); } else{ alert('Debe Seleccionar una REFERENCIA'); }">
                            <option value='0'<?php if (!(strcmp(0, $_GET['int_cliente_op']))) {echo "selected=\"selected\"";} ?>>- CLIENTE -</option>
                               <?php  foreach($row_clientes as $row_clientes ) { ?>
                              <option value="<?php echo $row_clientes['id_c']?>"<?php if (!(strcmp($row_clientes['id_c'], $_GET['int_cliente_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clientes['nombre_c'];?> </option>
                           <?php } ?>
                          </select>
  
                         <input type="hidden" name="str_nit_op" id="str_nit_op"  value="<?php echo $row_nit['nit_c']; ?>"/></td>
                         <td colspan="2" id="fuente1"><?php $numocI= $row_oc['id']+1; ?>        
                         <input name="str_numero_oc_op" onfocus="onfocus" type="number" style="width:80px" readonly="readonly" value="<?php echo $numocI; ?>"/> 

                                
                         </td>
                         <td  nowrap="nowrap" id="fuente1"> 

                          <a href="javascript:verFoto('referencia_bolsa_edit.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&n_egp=<?php echo  $row_referencia['n_egp_ref']; ?>','1100','850')"><em><?php echo $_GET['int_cod_ref_op']; ?></em></a>

                          <select name="int_cod_ref_op" id="int_cod_ref_op" class="selectsMini" onchange="if(form1.int_cod_ref_op.value) { consulta_ref_op_interna(); } else{ alert('Debe Seleccionar una REFERENCIA'); }">
                             <option value=""<?php if (!(strcmp("", $_GET['int_cod_ref_op']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
                              <?php  foreach($row_ref as $row_ref ) { ?>
                             <option value="<?php echo $row_ref['cod_ref']?>"<?php if (!(strcmp($row_ref['cod_ref'], $_GET['int_cod_ref_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ref['cod_ref']?></option>
                          <?php } ?>
                          </select> 
                           -
                   <input name="version_ref_op" type="number" id="version_ref_op" min="0" max="9" size="2" value="<?php echo $row_datos_oc['version_ref']; ?>" readonly="readonly" required="required" /></td>
                         <td  nowrap="nowrap" id="fuente1"><?php echo $row_precio['int_precio_io']; ?></td>
                         <td colspan="2" id="fuente1"><input type="number" style="width:60px" min="0" step="1" name="int_cotiz_op" id="int_cotiz_op" value="<?php echo $row_datos_oc['n_cotiz_ref']; ?>" required="required"/>

                         <a href="javascript:verFoto('control_tablas.php?n_cotiz=<?php echo $row_datos_oc['n_cotiz_ref']; ?>&cod_ref=<?php echo $_GET['int_cod_ref_op']; ?>&Str_nit=<?php echo $row_nit['nit_c']; ?>&case=<?php echo "6"; ?>','850','850')"><em><?php echo $row_datos_oc['n_cotiz_ref'];?></em></a>
                       </td>
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
                         <td  nowrap="nowrap" id="talla1">EXT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELL&nbsp;&nbsp; DESPERDICIOS</td>
                         <td colspan="2" id="talla1"><?php if (!(strcmp("LAMINA", $row_datos_oc['tipo_bolsa_ref']))) {echo "KILOS SOLICITADOS";}else{echo "UNIDADES SOLICITADAS";} ?></td>
                         <td colspan="2" id="talla1">TIPO DE BOLSA</td>
                         <td colspan="2" id="talla1">PESO MILLAR</td>
                         <td colspan="2" id="talla1">METROS LINEAL</td>
                       </tr>
                       <tr>
                         <td nowrap="nowrap" id="talla1">
                           <input id="extruderpor" name="extruderpor" type="text" value="0" readonly="readonly" style="width:40px"/> 
                           <input id="impreporc" name="impreporc" type="text" value="0" readonly="readonly" style="width:40px"/> 
                           <input id="selladoporc" name="selladoporc" type="text" value="0" readonly="readonly" style="width:40px"/> = 

                          <input id="int_desperdicio_op" name="int_desperdicio_op" type="text" value="0" min="0" max="50"  style="width:40px" required="required" onchange="calcular_op();validacion_tipocinta()"/> 
                           %<strong></strong>
                         </td>
                         <td colspan="2" id="fuente1"><strong>
                           <input type="number" name="int_cantidad_op" id="int_cantidad_op" value="" style="width:80px" onBlur="calcular_op()" step="0.01" required="required"/>
                         </strong></td>
                         <td colspan="2" id="fuente1"><select name="str_tipo_bolsa_op" id="str_tipo_bolsa_op" onchange="if(form1.str_tipo_bolsa_op.value=='PACKING LIST') { swal('PUEDE EDITAR EL METRO LINEAL YA QUE ES UN PACKING LIST')}else if(form1.str_tipo_bolsa_op.value=='BOLSA TROQUELADA'){anchoRolloRefOp();}else{calcular_op()}">
                           <option value="N.A." <?php if (!(strcmp("N.A.", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                           <option value="SEGURIDAD" <?php if (!(strcmp("SEGURIDAD", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
                           <option value="CURRIER" <?php if (!(strcmp("CURRIER", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>CURRIER</option>
                           <option value="BOLSA PLASTICA" <?php if (!(strcmp("BOLSA PLASTICA", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
                           <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
                           <option value="PACKING LIST" <?php if (!(strcmp("PACKING LIST", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>PACKING LIST</option>
                           <option value="LAMINA" <?php if (!(strcmp("LAMINA", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>LAMINA</option><option value="BOLSA TROQUELADA" <?php if (!(strcmp("BOLSA TROQUELADA", $row_datos_oc['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA TROQUELADA</option>
                         </select></td>
                         <td colspan="2" id="fuente1"><input id="int_pesom_op" name="int_pesom_op" style="width:60px" type="number" min="0" step="0.01" value="<?php echo $row_datos_oc['peso_millar_ref']; ?>" required="required" onBlur="calcular_op();"/></td>
                         <td colspan="2" id="fuente1"><input id="metroLineal_op" name="metroLineal_op" style="width:70px" type="number" min="0" size="5" step="0.01" required="required" onblur="if(form1.str_tipo_bolsa_op.value=='PACKING LIST') { alert('PUEDE EDITAR EL METRO LINEAL YA QUE ES UN PACKING LIST')}else if(form1.str_tipo_bolsa_op.value=='BOLSA TROQUELADA'){anchoRolloRefOp();}else{calcular_op()}"/></td>
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
                         <td colspan="2" id="fuente1"><select name="str_presentacion_op" id="str_presentacion_op" onchange="if(form1.str_tipo_bolsa_op.value=='BOLSA TROQUELADA'){anchoRolloRefOp();}else{calcular_op();}" >
                           <option value="N.A"<?php if (!(strcmp('N.A', $row_datos_oc['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>N.A</option>
                           <option value="LAMINA" <?php if (!(strcmp('LAMINA', $row_datos_oc['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>LAMINA</option>
                           <option value="TUBULAR" <?php if (!(strcmp('TUBULAR', $row_datos_oc['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>TUBULAR</option>
                           <option value="SEMITUBULAR" <?php if (!(strcmp('SEMITUBULAR', $row_datos_oc['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>SEMITUBULAR</option>
                         </select></td>
                         <td colspan="2" id="talla1">CANT. KLS REQUERIDOS</td>
                         <td colspan="2" id="fuente1"><input id="int_kilos_op" name="int_kilos_op" type="number" min="0" style="width:60px" step="0.01" readonly="readonly" required="required"/></td>
                         <td colspan="2" id="talla1">ANCHO DEL ROLLO</td>
                         </tr>
                       <tr>
                         <td id="talla1">PIGMENTO INTERNO</td>
                         <td colspan="2" id="talla1">PIGMENTO EXTERNO</td>
                         <td colspan="2" id="talla1">CALIBRE</td>
                         <td colspan="2" id="fuente1"><input id="int_calibre_op" name="int_calibre_op" style="width:60px" type="number" min="0" step="0.01" value="<?php echo $row_datos_oc['calibre_ref'] ?>" onblur="calcular_op()" required="required"/></td>
                         <td colspan="2" id="fuente1"><input id="int_ancho_rollo_op" name="int_ancho_rollo_op" style="width:50px" type="number" min="0"max="500" size="5" step="0.01" required="required" onblur="if(form1.str_tipo_bolsa_op.value=='BOLSA TROQUELADA'){anchoRolloRefOp();}else{calcular_op();}" value="<?php echo $row_datos_oc['ancho_rollo']; ?>"/></td>
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
                         <td colspan="11" id="fuente1"><textarea name="observ_extru_op" style="width: 100%" rows="3"></textarea></td>
                         </tr>
                       <tr id="tr1">
                         <td colspan="11" id="titulo4">CONDICIONES DE FABRICACI&Oacute;N EN IMPRESION</td>
                         </tr>
                         <tr>
                         <td id="talla1">IMPRIME EN MAQUINA:</td>
                         <td id="fuente1">
                         <select name="maquina_imp_op" id="maquina_imp_op" class="selectsMini"> 
                             <?php  foreach($row_maquinas as $row_maquinas ) { ?>
                            <option value="<?php echo $row_maquinas['id_maquina']?>"><?php echo $row_maquinas['nombre_maquina']?></option>
                         <?php } ?>
                         </select>
                         </td>
                         <td id="talla1">KLS MATERIAL REQUERIDO:</td>
                         <td colspan="2" id="fuente1"><input name="kls_req_imp_op" id="kls_req_imp_op" style="width:70px" type="number" min="0" step="0.01" onblur="calcular_op();"/></td>
                         <td colspan="2" id="talla1">METROS APROXIMADOS:</td>
                         <td colspan="2" id="fuente1"><input name="mts_req_imp_op" id="mts_req_imp_op" style="width:70px" type="number" min="0" step="0.01" /></td>
                       </tr>
                          <tr>
                           <td colspan="9" id="talla2"><hr><hr /></td>
                           </tr>
                       <tr>  
                         <td rowspan="2" id="talla1">MARGENES</td>
                         <td id="talla1">Izquierda mm</td>
                         <td id="fuente1"><input name="margen_izq_imp_op" id="margen_izq_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_izq_imp_egp'];?>"/></td> 
                         <td id="talla1">Rep. en Ancho</td>
                         <td id="fuente1"><input name="margen_anc_imp_op" id="margen_anc_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_anc_imp_egp']?>"/></td>
                         <td colspan="2" id="talla2">de</td>
                         <td id="fuente1"><input name="margen_anc_mm_imp_op" id="margen_anc_mm_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_anc_mm_imp_egp']?>"/></td>
                         <td id="talla1">mm</td>
                       </tr>
                       <tr>
                         <td id="talla1">Derecha mm</td>
                         <td id="fuente1"><input name="margen_der_imp_op" id="margen_der_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_der_imp_egp']?>"/></td>
                         <td id="talla1">Rep. Perimetro</td>
                         <td id="fuente1"><input name="margen_peri_imp_op" id="margen_peri_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_peri_imp_egp']?>"/></td>
                         <td colspan="2" id="talla2">de</td>
                         <td id="fuente1"><input name="margen_per_mm_imp_op" id="margen_per_mm_imp_op" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_margenes['margen_per_mm_imp_egp']?>"/></td>
                         <td id="talla1">mm</td>
                       </tr>
                       <tr>
                         <td id="talla1">&nbsp;</td>
                         <td id="talla1"><strong>Z</strong></td>
                         <td id="fuente1">
                          <input name="margen_z_imp_op" id="margen_z_imp_op" style="width:50px" type="number" min="0" step="0.01" value="<?php echo $row_margenes['margen_z_imp_egp']?>"/>
                         </td>
                         <td colspan="6" id="talla1">&nbsp;</td>
                         </tr>
                     <!-- <tr>  
                          <td colspan="11"> 
                               <div id="cajon1">
                                  <table>
                                   <?php  foreach($row_mezclas as $row_mezclas ) { ?>
                                      <tr id="tr1">
                                          <td nowrap id="fuente1"><strong>UNIDAD <?php echo $row_mezclas['und'];?> </strong></td>
                                          <td nowrap id="fuente2">DESCRIPCION</td>
                                          <td nowrap id="fuente1">VALOR</td>
                                      </tr>     
                                      <tr>
                                          <td  nowrap id="talla1"><?php echo $row_mezclas['str_nombre_m'];?></td>
                                          <td  id="talla1"><?php echo $row_mezclas['descripcion_insumo'];?></td>           
                                          <td id="talla3"><?php echo $row_mezclas['str_valor_pmi'];?></td>         
                                      </tr>
                                    <?php  } ?>
                                  </table> 
                               </div> 
                           </td> 
                       </tr>-->
                      <?php if($row_cualexiste['id_ref_pm']=='') : ?> 
                        <tr id="tr1">
                             <td colspan="9" id="fuente2"><strong>CARACTERISTICAS DE IMPRESION</strong></td>
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
                       <!-- INICIA CARACTERISTICAS -->
                        <tr id="tr1">
                          <td colspan="100%" id="fuente2"><strong>CARACTERISTICAS</strong> </td>
                        </tr> 
                        <tr>
                            <?php  for ($x=0;$x<=$totalRows_caract_valor-1;$x++) { ?>          
                                 <td width="130" id="talla1"><?php $id_cv=mysql_result($caract_valor,$x,id_cv); $var=mysql_result($caract_valor,$x,str_nombre_caract_c);if($var!=''){echo $var;}else{echo "MP eliminada";} ?>                                             
                                <?php $valor=mysql_result($caract_valor,$x,str_valor_cv); echo $valor;?>
                                 </td>
                                <?php  } ?>
                        </tr> 

                       <?php elseif(isset($_GET['int_cod_ref_op'])&& $_GET['int_cod_ref_op']!=''): ?> 


                       <!--  INICIA MEZCLAS DE IMPRESION -->
 
                                        <tr>
                                          <td colspan="18" >
                                              <table style="width: 100%">
                                                    <tr>
                                                     <td colspan="18" id="fuente1"> 
                                                      Impresora : <?php echo $row_impresion['extrusora_mp'];?>
                                                    </td>
                                                   </tr>
                                                  <tr id="tr1">
                                                    <td rowspan="2" id="fuente1"> </td>
                                                    <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 1</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 2</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 3</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 4</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 5</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 6</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 7</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 8</b> </td> 
                                                   </tr> 
                                                   <tr>
                                                      <td></td>
                                                   </tr> 
                                                  <tr id="tr1">
                                                   <td id="talla1"><b>COLORES</b></td>
                                                    <td  id="talla1">
                                                        <?php $idinsumo=$row_impresion['int_ref1_tol1_pm']; ?> 
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>  
                                                    </td>
                                                   <td id="talla1">
                                                       <?php echo $row_impresion['int_ref1_tol1_porc1_pm']; ?> 
                                                    </td>
                                                   <td id="talla1">
                                                       <?php $idinsumo =  $row_impresion['int_ref3_tol3_pm']; ?>
                                                       <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                   <td id="talla1">
                                                         <?php echo $row_impresion['int_ref3_tol3_porc3_pm']; ?>
                                                   </td>
                                                   <td id="talla1">
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_1']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                   </td>
                                                   <td id="talla1">
                                                         <?php echo $row_mezclaycaract_impresion['campo_2']; ?>
                                                   </td>
                                                   <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_3']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                   </td>
                                                   <td id="talla1">
                                                         <?php echo $row_mezclaycaract_impresion['campo_4']; ?>
                                                   </td>

                                                        
                                                   <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_5']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_6']; ?>
                                                        </td>
                                                        <td id="talla1"> 
                                                             <?php $idinsumo = $row_mezclaycaract_impresion['campo_7']; ?>
                                                             <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_8']; ?>
                                                        </td>
                                                        <td id="talla1"> 
                                                             <?php $idinsumo = $row_mezclaycaract_impresion['campo_9']; ?>
                                                             <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_10']; ?>
                                                        </td>
                                                        <td id="talla1"> 
                                                             <?php $idinsumo = $row_mezclaycaract_impresion['campo_11']; ?>
                                                             <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_12']; ?>
                                                         </td>  

                                                  </tr>

                                                  <tr>
                                                    <td id="talla1"><b>MEZCLAS</b></td>
                                                    <td id="talla1"> 
                                                      <?php $idinsumo=$row_impresion['int_ref1_tol2_pm'];
                                                            $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                          $resultm=mysql_query($sqlm); 
                                                          $numm=mysql_num_rows($resultm); 
                                                          if($numm >= '1') 
                                                          { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                          else { echo ""; 
                                                          } 
                                                      ?> 
                                                  </td>
                                                    <td id="talla1">
                                                      <?php echo $row_impresion['int_ref1_tol2_porc1_pm']; ?>
                                                    </td> 
                                                     <td id="talla1"> 
                                                           <?php $idinsumo = $row_impresion['int_ref3_tol4_pm']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                    <td id="talla1">
                                                        <?php echo $row_impresion['int_ref3_tol4_porc3_pm']; ?>
                                                      </td>

                                                     <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_13']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                     <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_14']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_15']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_16']; ?>
                                                        </td>
                                                     
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_17']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_18']; ?>
                                                        </td>
                                                       <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_19']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                       <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_20']; ?>
                                                        </td>
                                                       <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_21']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_22']; ?>
                                                        </td>
                                                         <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_23']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                       <td id="talla1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_24']; ?>
                                                        </td>

                                                    </tr>
                                            
                                                  <tr id="tr1">
                                                    <td id="talla1"></td>
                                                    <td id="talla1"> 
                                                      <?php $idinsumo=$row_impresion['int_ref1_tol3_pm'];
                                                            $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                          $resultm=mysql_query($sqlm); 
                                                          $numm=mysql_num_rows($resultm); 
                                                          if($numm >= '1') 
                                                          { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                          else { echo ""; 
                                                          } 
                                                      ?> 
                                                  </td>
                                                    <td id="talla1">
                                                      <?php echo $row_impresion['int_ref1_tol3_porc1_pm']; ?>
                                                    </td>
                                                   <td id="talla1"> 
                                                       <?php $idinsumo = $row_mezclaycaract_impresion['campo_25']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                   <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_26']; ?>
                                                     </td>
                                                   <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_27']; ?>
                       
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                   <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_28']; ?>
                                                     </td>
                                                   <td id="talla1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_29']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                    <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_30']; ?>
                                                     </td>
                                                   
                                                   <td id="talla1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_31']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_32']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_33']; ?>
                       
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_34']; ?>
                                                        </td>
                                                     <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_35']; ?>

                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_36']; ?>
                                                     </td>
                                                      <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_37']; ?>

                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_38']; ?>
                                                      </td> 

                                                  </tr>

                                                  <tr>
                                                    <td id="talla1"></td>
                                                    <td id="talla1">
                                                    <?php $idinsumo=$row_impresion['int_ref1_tol4_pm'];
                                                            $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                          $resultm=mysql_query($sqlm); 
                                                          $numm=mysql_num_rows($resultm); 
                                                          if($numm >= '1') 
                                                          { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                          else { echo ""; 
                                                          } 
                                                      ?> 
                                                     </td>
                                                    <td id="talla1">
                                                      <?php echo $row_impresion['int_ref1_tol4_porc1_pm']; ?>
                                                    </td>
                                                   <td id="talla1"> 
                                                       <?php $idinsumo = $row_mezclaycaract_impresion['campo_39']; ?>
                                                       <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                    <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_40']; ?>
                                                     </td>
                                                     <td id="talla1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_41']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                     </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_42']; ?>
                                                       </td>
                                                     <td id="talla1"> 
                                                       <?php $idinsumo = $row_mezclaycaract_impresion['campo_43']; ?>
                                                       <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_44']; ?>
                                                      </td>
                                                     
                                                     <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_45']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="talla1">
                                                         <?php echo $row_mezclaycaract_impresion['campo_46']; ?>
                                                       </td>
                                                      <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_47']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                     </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_48']; ?>
                                                       </td>
                                                      <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_49']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_50']; ?>
                                                       </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_51']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_52']; ?>
                                                       </td>   
                                                   
                                                 </tr> 

                                                  <tr>
                                                    <td id="talla1"></td>
                                                      <td id="talla1">
                                                        <?php $idinsumo=$row_impresion['int_ref2_tol1_pm'];
                                                            $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                          $resultm=mysql_query($sqlm); 
                                                          $numm=mysql_num_rows($resultm); 
                                                          if($numm >= '1') 
                                                          { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                          else { echo ""; 
                                                          } 
                                                      ?> 
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_impresion['int_ref2_tol1_porc2_pm']; ?>
                                                      </td>
                                                    <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_53']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_54']; ?>
                                                      </td>
                                                    <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_55']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_56']; ?>
                                                      </td>
                                                     <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_57']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_58']; ?>
                                                      </td>
                                                     
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_59']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_60']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_61']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_62']; ?>
                                                       </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_63']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_64']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_65']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_66']; ?>
                                                       </td>
                                                         
                                                  </tr>
                                                  <tr id="tr1">
                                                    <td id="talla1"><b>ALCOHOL</b></td>
                                                      <td id="talla1">
                                                          <?php $idinsumo=$row_impresion['int_ref2_tol2_pm'];
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="talla1">
                                                        <?php echo $row_impresion['int_ref2_tol2_porc2_pm']; ?>
                                                      </td>
                                                    <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_67']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_68']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_69']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_70']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_71']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_72']; ?>
                                                      </td>
                                                   
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_73']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_74']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_75']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_76']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_77']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_78']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_79']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_80']; ?>
                                                      </td> 
                                                         
                                                  </tr>

                                                  <tr>
                                                    <td id="talla1"><b>ACETATO</b> NPA</td>
                                                      <td id="talla1">
                                                          <?php $idinsumo=$row_impresion['int_ref2_tol3_pm'];
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="talla1"> 
                                                      <?php echo $row_impresion['int_ref2_tol3_porc2_pm']; ?>
                                                      </td>
                                                    <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_81']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_82']; ?>
                                                        </td> 
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_83']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_84']; ?>
                                                        </td> 
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_85']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_86']; ?>
                                                        </td> 
                                                   
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_87']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_88']; ?>
                                                        </td> 
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_89']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_90']; ?>
                                                        </td> 
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_91']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_92']; ?>
                                                        </td> 
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_93']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_94']; ?>
                                                      </td> 
                                                       
                                                  </tr> 

                                                  <tr id="tr1">
                                                    <td id="talla1"><b>METOXIPROPANOL</b></td>
                                                      <td id="talla1">
                                                          <?php $idinsumo=$row_impresion['int_ref2_tol4_pm'];
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_impresion['int_ref2_tol4_porc2_pm']; ?>
                                                      </td>
                                                    <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_95']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_96']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_97']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_98']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_99']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_100']; ?>
                                                        </td>
                                                    
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_101']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_102']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_103']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_104']; ?>
                                                        </td>
                                                      <td id="talla1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_105']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_106']; ?>
                                                     </td>
                                                     <td id="talla1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_107']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_108']; ?>
                                                      </td>

                                                  </tr>

                                                  <tr>
                                                    <td id="talla1"><b>VISCOSIDAD</b></td>
                                                    <td colspan="2" id="talla1"> 
                                                      <?php echo $row_mezclaycaract_impresion['int_ref1_rpm_pm']; ?>
                                                    </td> 
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref1_tol5_porc1_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref2_rpm_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref2_tol5_porc2_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref3_rpm_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref3_tol5_porc3_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_137']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_138']; ?>
                                                    </td>
                                                  </tr>

                                                  <tr id="tr1">
                                                    <td id="talla1"><b>ANILOX</b></td>
                                                      <td id="talla1"> 
                                                            <?php $idinsumo=$row_impresion['int_ref3_tol1_pm'];
                                                                $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                              $resultm=mysql_query($sqlm); 
                                                              $numm=mysql_num_rows($resultm); 
                                                              if($numm >= '1') 
                                                              { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                              else { echo ""; 
                                                              } 
                                                          ?> 
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_impresion['int_ref3_tol1_porc3_pm']; ?>
                                                      </td>
                                                    <td id="talla1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_109']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_110']; ?>
                                                     </td>
                                                    <td id="talla1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_111']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_112']; ?>
                                                     </td>
                                                    <td id="talla1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_113']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                    <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_114']; ?>
                                                     </td> 

                                                    <td id="talla1">
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_115']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_116']; ?>
                                                      </td>
                                                      <td id="talla1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_117']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_118']; ?>
                                                        </td>
                                                      <td id="talla1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_119']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_120']; ?>
                                                        </td>
                                                      <td id="talla1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_121']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_122']; ?>
                                                      </td>

                                                  </tr>

                                                  <tr>
                                                    <td id="talla1"><b>BCM</b></td>
                                                      <td id="talla1">
                                                          <?php $idinsumo=$row_impresion['int_ref3_tol2_pm'];
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                      <td  id="talla1">
                                                        <?php echo $row_impresion['int_ref3_tol2_porc3_pm']; ?>
                                                      </td>
                                                    <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_123']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                    <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_124']; ?>
                                                     </td>
                                                    <td id="talla1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_125']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_126']; ?>
                                                      </td>
                                                    <td id="talla1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_127']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_128']; ?>
                                                     </td>
                                                      
                                                     <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_129']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_130']; ?>
                                                     </td>
                                                     <td id="talla1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_131']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_132']; ?>
                                                     </td>
                                                     <td id="talla1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_133']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_134']; ?>
                                                     </td>
                                                     <td id="talla1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_135']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                     <td id="talla1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_136']; ?>
                                                     </td>

                                                  </tr> 

                                                  <tr id="tr1">
                                                    <td colspan="18" id="talla1">
                                                       Observacion: <?php echo $row_impresion['observ_pm']; ?> 
                                                    </td>
                                                  </tr> 

                                            <!-- INICIA CARACTERISTICAS -->
                                             <tr id="tr1">
                                               <td colspan="100%" id="fuente2"><strong>CARACTERISTICAS</strong> </td>
                                             </tr>  
                                                  <tr>
                                                    <td colspan="2" id="talla1">Cantidad de Unidades</td>
                                                    <td  nowrap="nowrap" id="talla1">Temp Secado Grados C</td>
                                                    <td colspan="3" id="talla1">Repeticion de Ancho</td>
                                                    <td colspan="3" id="talla1">Rep. Perimetro</td>
                                                    <td colspan="3" id="talla1">Arte Aprobado (0 SI, 1 NO)</td>
                                                    <td colspan="2" id="talla1">Z</td>
                                                    <td colspan="2" id="talla1">Guia Fotocelda (0 SI, 1 NO)</td>
                                                    <td colspan="2" id="talla1">Velocidad Maquina</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_139']; ?>
                                                    </td>
                                                    <td id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_140']; ?>
                                                    </td>
                                                    <td colspan="3" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_141']; ?>
                                                    </td>
                                                    <td colspan="3" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_142']; ?>
                                                    </td>
                                                    <td colspan="3" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_143']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_144']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_145']; ?>
                                                    </td>
                                                    <td colspan="2" id="talla1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_146']; ?>
                                                    </td>
                                                  </tr>
                                            </table>

                                          </td>
                                        </tr> 

                                        <?php endif;?> 
                           <!--  INICIA MEZCLAS DE EXTRUDER -->

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
                         <td colspan="11" id="fuente1"><textarea name="observ_impre_op" style="width: 100%" rows="3" id="observ_impre_op"></textarea></td>
                         </tr>
                       <tr>
                         <td colspan="11" id="fuente5">&nbsp;</td>
                         </tr>
                                 <!--SELLADO NO APARECE SI ES LAMINA EXCEPTO UND X CAJAS Y PAQUETES-->
                  
                 <tr id="tr1">
                         <td colspan="11" id="titulo4">CONDICIONES DE FABRICACI&Oacute;N EN SELLADO</td>
                         </tr>        
                         
                       <tr>
                         <td colspan="11">
               <table>
                   <tr>
                     <td id="talla1"><strong>ANCHO</strong></td>
                     <td id="talla1"><strong>LARGO</strong></td>
                     <td id="talla1" nowrap="nowrap"><strong>SOLAPA
                         (<?php if ($row_datos_oc['b_solapa_caract_ref']==2) {echo "Sencilla";}else if ($row_datos_oc['b_solapa_caract_ref']==1){echo "Doble";}else {echo "";} ?>
                         <input type="hidden" name="valor_s" id="valor_s" value="<?php echo $row_datos_oc['b_solapa_caract_ref']; ?>" />)
                     </strong></td>
                     <td id="talla1">&nbsp;</td>
                     <td id="talla1"><strong>BOLSILLO PORTAGUIA</strong></td>
                     <td id="talla1"><strong>ADHESIVO</strong></td>
                     <td id="talla1">TIPO ADHESIVO </td>
                     <td id="talla1">&nbsp;</td>
                   </tr>
                   <tr>
                     <td id="talla1"><?php echo $row_datos_oc['ancho_ref']; ?>
                       <input type="hidden" name="ancho" id="ancho" value="<?php echo $row_datos_oc['ancho_ref']; ?>"/></td>
                     <td id="talla1"><?php echo $row_datos_oc['largo_ref']; ?>
                       <input type="hidden" name="largo" id="largo" value="<?php echo $row_datos_oc['largo_ref']; ?>" /></td>
                     <td id="talla1"><?php echo $row_datos_oc['solapa_ref']; ?>
                       <input type="hidden" name="solapa" id="solapa" value="<?php echo $row_datos_oc['solapa_ref']; ?>" /></td>
                     <td id="talla1">&nbsp;</td>
                     <td id="talla1"><?php echo $row_datos_oc['bolsillo_guia_ref']; ?></td>
                     <td id="talla1"><input type="hidden" name="adhesivo_ref" id="adhesivo_ref" value="<?php echo $row_datos_oc['adhesivo_ref']; ?>" />
                       <?php echo $row_datos_oc['adhesivo_ref']; ?></td>
                     <td id="talla1"><input type="hidden" name="tipoCinta_ref" id="tipoCinta_ref" value="<?php echo $row_datos_oc['tipoCinta_ref']; ?>" />
                       <?php  
                       $tipoadh=$row_datos_oc['tipoCinta_ref'];
                       $sqladhesivo="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$tipoadh'"; 
                       $resultadhesivo=mysql_query($sqladhesivo); 
                       $numadhesivo=mysql_num_rows($resultadhesivo); 
                       if($numadhesivo >= '1'){
                         echo $tipoBols=mysql_result($resultadhesivo,0,'descripcion_insumo');
                       }?></td>
                     <td id="talla1">&nbsp;</td>
                   </tr>
                   <tr>
                     <td id="talla1"><strong>CALIBRE</strong></td>
                     <td id="talla1"><strong>PESO MILLAR</strong></td>
                     <td id="talla1"><strong>TIPO DE BOLSA </strong></td>
                     <td id="talla1">&nbsp;</td>
                     <td id="talla1">FUELLE</td>
                     <td id="talla1"><strong> </strong></td>
                     <td colspan="2" id="talla1">Tipo /Lamina Bolsillo</td>
                   </tr>
                   <tr>
                     <td id="talla1"><?php echo $row_datos_oc['calibre_ref']; ?></td>
                     <td id="talla1"><?php echo $row_datos_oc['peso_millar_ref']; ?></td>
                     <td id="talla1"><?php echo $row_datos_oc['tipo_bolsa_ref']; ?></td>
                     <td id="talla1">&nbsp;</td>
                     <td id="talla1"><?php echo $row_datos_oc['N_fuelle']; ?>
                       <input type="hidden" name="fuelle" id="fuelle" value="<?php echo $row_datos_oc['N_fuelle']; ?>" /></td>
                     <td id="talla1">&nbsp; </td>
                     <td colspan="2" id="talla1"><?php if($row_datos_oc['bolsillo_guia_ref']!='0.00' && $row_datos_oc['tipoLamina_ref']=='0') {?>
                       <input name="tipoLamina" type="hidden" value="1" onblur="funcion();"/>
                       <?php }else {
                       $tipolam=$row_datos_oc['tipoLamina_ref'];
                       $sqlinsumos="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$tipolam'"; 
                       $resultinsumos=mysql_query($sqlinsumos); 
                       $numinsumos=mysql_num_rows($resultinsumos); 
                       if($numinsumos >= '1'){
                       echo  $tipoBols=mysql_result($resultinsumos,0,'descripcion_insumo');
                       }
                     }?></td>
                   </tr>
                   <tr>
                     <td rowspan="2" id="talla1"><strong>PRESENTACION</strong></td>
                     <td rowspan="2" id="talla1"><strong>TRATAMIENTO CORONA</strong></td>
                     <td colspan="6" id="talla2"><strong>Bolsillo Portaguia</strong></td>
                     </tr>
                   <tr>
                     <td id="talla1"> <strong>(Ubicacion)
                       <input type="hidden" name="lam1" id="lam1" value="<?php if ($row_datos_oc['bol_lamina_1_ref']==''){echo '0';}else{echo $row_datos_oc['bol_lamina_1_ref'];} ?>" />
                       <input type="hidden" name="lam2" id="lam2" value="<?php if ($row_datos_oc['bol_lamina_2_ref']==''){echo '0';}else{echo $row_datos_oc['bol_lamina_2_ref'];} ?>" />
                     </strong></td>
                     <td id="talla1">&nbsp;</td>
                     <td id="talla1">Calibre Bols.
                       <input type="number" name="calibre_bols" id="calibre_bols" value="<?php if ($row_datos_oc['calibreBols_ref']==''){echo "0";}else{echo $row_datos_oc['calibreBols_ref'];}?>" style="width:50px" min="0" step="0.01" <?php if ($row_datos_oc['bol_lamina_1_ref'] > '0' || $row_datos_oc['bol_lamina_2_ref'] > '0') { ?>required="required" title="Debe agregar el calibre del bolsillo en la referencia" <?php }  ?> onblur="return metrosAkilos()"/></td>
                     <td id="talla1"><strong>(Forma)</strong></td>
                     <td id="talla1"><strong>(Lamina 1)</strong></td>
                     <td id="talla1"><strong>(Lamina 2)</strong></td>
                   </tr>
                   <tr>
                     <td id="talla1"><?php echo $row_datos_oc['Str_presentacion']; ?></td>
                     <td id="talla1"><?php echo $row_datos_oc['Str_tratamiento']; ?></td>
                     <td id="talla1"><?php echo $row_datos_oc['str_bols_ub_ref']; ?></td>
                     <td id="talla1">&nbsp;</td>
                     <td id="talla1"><?php echo $row_datos_oc['peso_millar_bols']; ?></td>
                     <td id="talla1"><?php echo $row_datos_oc['str_bols_fo_ref']; ?></td>
                     <td id="talla1"><?php echo $row_datos_oc['bol_lamina_1_ref']; ?></td>
                     <td id="talla1"><?php echo $row_datos_oc['bol_lamina_2_ref']; ?></td>
                   </tr>
                   <tr>
                     <td id="talla1"><strong>TIPO DE SELLO:</strong></td>
                     <td id="talla1"><strong>UNIDADES X CAJA:</strong></td>
                     <td id="talla1"><strong>UNIDADES X</strong></td>
                     <td id="talla1"><strong>UNID. X PAQ. REAL</strong></td>
                     <td id="talla1">MEDIDA DE LA CAJA</td>
                     <td id="talla1"><strong>PRECORTE (Bolsillo Portaguia):</strong></td>
                     <td id="talla1" nowrap="nowrap" >Lote</td>
                     <td id="talla1" nowrap="nowrap">Numeracion Actual Sellado:</td>
                     </tr>
                   <tr>
                     <td id="talla1"><?php echo $row_datos_oc['tipo_sello_egp']; ?></td>
                     <td id="talla1"><input name="int_undxcaja_op" style="width:50px" type="number" id="int_undxcaja_op" value="<?php echo $row_datos_oc['unids_caja_egp']; ?>" size="12" min="0" onBlur="alerta()"/></td>
                     <td id="talla1"><input name="int_undxpaq_op" style="width:50px" type="number" id="int_undxpaq_op" value="<?php echo $row_datos_oc['unids_paq_egp']; ?>" size="12" min="0" onBlur="alerta()"/></td>
                     <td id="talla1"><input name="undxpaqreal" style="width:50px" type="number" id="undxpaqreal" value="<?php echo $row_ultima_numeraciones['undxpaqreal']; ?>" size="12" min="0" onblur="alerta()"/></td>
                     <td id="talla1">
                      <select name="marca_cajas_egp" id="opciones" style="width:100px" onchange="return primeraletra(this),alerta()" >
                         <option value="0"<?php if (!(strcmp(0, $row_datos_oc['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
                          <?php  foreach($row_insumo as $row_insumo ) { ?>
                         <option value="<?php echo $row_insumo['id_insumo']?>"<?php if (!(strcmp($row_insumo['id_insumo'], $row_datos_oc['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo['descripcion_insumo']?></option>
                      <?php } ?>
                      </select>
                     </td>
                     <td id="talla1"><?php if($row_datos_oc['B_troque']=='1') {echo "SI";}else{echo "NO";}; ?></td>
                    <td id="talla1"><input name="lote" style="width:70px" type="text" id="lote" value="<?php echo $op_numero; ?>" placeholder="Lote" /></td>
                     <td id="talla1"> 
                     <input name="numInicio_op" type="text" id="numInicio_op" style="width:80px" min="0" step="1" value="<?php echo $row_numeraciones['int_hasta_tn']!='' ? $row_numeraciones['int_hasta_tn'] : "0"; ?>" onBlur="hastaordenP();" /><input class="charfin" style=" width:40px" type="text" name="charfin" autofocus id="charfin" value="" onchange="conMayusculas(this)" > 
                    </td> 
                   </tr>
                   <tr>
                     <td colspan="8" id="talla3">Tiene Faltantes? 
                      <select name="imprimiop" id="imprimiop" required="required" >
                         <option value=""<?php if (!(strcmp('', $row_datos_oc['imprimiop']))) {echo "selected=\"selected\"";} ?>>Selecione</option>

                         <option value="0"<?php if (!(strcmp(0, $row_datos_oc['imprimiop']))) {echo "selected=\"selected\"";} ?>>SI</option>
                         <option value="1" <?php if (!(strcmp(1, $row_datos_oc['imprimiop']))) {echo "selected=\"selected\"";} ?>>NO</option> 
                      </select>
                     </td>
                     <td colspan="7" id="talla1">&nbsp;<div id="resultado_generador"></div></td>
                     </tr>
                   <tr>
                     <td colspan="2" id="talla1"><strong>POSICION</strong></td>
                     <td colspan="4" id="talla1"><strong>TIPO DE NUMERACION </strong></td>
                     <td colspan="2" id="talla1"><strong>BARRAS &amp; FORMATO</strong></td>
                   </tr>
                   <tr>
                     <td colspan="2" id="talla1"><strong>Solapa Talonario Recibo</strong></td>
                     <td colspan="4" id="talla1"><?php echo $row_datos_oc['tipo_solapatr_egp']; ?></td>
                     <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_solapatr_egp']; ?></td>
                   </tr>
                   <tr>
                     <td colspan="2" id="talla1"><strong>Cinta</strong></td>
                     <td colspan="4" id="talla1"><?php echo $row_datos_oc['tipo_cinta_egp']; ?></td>
                     <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_cinta_egp']; ?></td>
                   </tr>
                   <tr>
                     <td colspan="2" id="talla1"><strong>Superior</strong></td>
                     <td colspan="4" id="talla1"><?php echo $row_datos_oc['tipo_superior_egp']; ?></td>
                     <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_superior_egp']; ?></td>
                   </tr>
                   <tr>
                     <td colspan="2" id="talla1"><strong>Principal</strong></td>
                     <td colspan="4" id="talla1"><?php echo $row_datos_oc['tipo_principal_egp']; ?></td>
                     <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_principal_egp']; ?></td>
                   </tr>
                   <tr>
                     <td colspan="2" id="talla1"><strong>Inferior</strong></td>
                     <td colspan="4" id="talla1"><?php echo $row_datos_oc['tipo_inferior_egp']; ?></td>
                     <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_inferior_egp']; ?></td>
                   </tr>
                 <tr>
                     <td colspan="2" id="talla1"><strong>Liner</strong></td>
                     <td colspan="4" id="talla1"><?php echo $row_datos_oc['tipo_liner_egp']; ?></td>
                     <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_liner_egp']; ?></td>
                   </tr>
                   <tr>
                     <td colspan="2" id="talla1"><strong>Bolsillo</strong></td>
                     <td colspan="4" id="talla1"><?php echo $row_datos_oc['tipo_bols_egp']; ?></td>
                     <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_bols_egp']; ?></td>
                   </tr>
                   <tr>
                     <td colspan="2" id="talla1">&nbsp;<?php echo $row_datos_oc['tipo_nom_egp']; ?></td>
                     <td colspan="4" id="talla1"><?php echo $row_datos_oc['tipo_otro_egp']; ?></td>
                     <td colspan="2" id="talla1"><?php echo $row_datos_oc['cb_otro_egp']; ?></td>
                   </tr>  
                   <tr>
                     <td colspan="2" id="talla1">METROS CINTA / LINER</td>
                     <td id="talla1">KILOS A SELLAR</td>
                     <td id="talla1">&nbsp;</td>
                     <td id="talla1">KILOS A SELLAR DE BOLSILLO</td>
                     <td id="talla1">UNIDADES A PRODUCIR</td>
                     <td id="talla1">CINTA TERMICA</td>
                     <td id="talla1">Ancho</td>
                   </tr>
                   <tr>
                     <td colspan="2" id="talla1"><input name="mts_cinta_sellado_op" id="mts_cinta_sellado_op" style="width:70px" type="number" min="0" size="5" step="0.01" onblur="calcular_op();"/></td>
                     <td id="talla1"><input name="kls_sellado_op" id="kls_sellado_op" style="width:70px" type="number" min="0" size="5" step="0.01" onblur="calcular_op();"/></td>
                     <td id="talla1">&nbsp;</td>
                     <td id="talla1"><input name="kls_sellado_bol_op" id="kls_sellado_bol_op" style="width:70px" type="number" min="0" size="5" step="0.01" value="" onclick="return metrosAkilos()"/></td>
                     <td id="talla1"><input name="und_prod_sellado_op" type="number" id="und_prod_sellado_op" style="width:70px" required="required" min="0" step="0.01" value=""/></td>
                     <td id="talla1"><?php //if($row_datos_oc['ancho_ref'] < '40') {$id_insumo="1343";}else if($row_datos_oc['ancho_ref'] >= '40'){$id_insumo="1701";} ?>
                        <!--<select name="id_termica_op" id="id_termica_op" style="width:100px">
                         <option value="">N.A</option>
                         <option value="1343"<?php if($id_insumo=="1343") {echo "selected=\"selected\"";}?> >CINTA DE SEGURIDAD AZUL 28MM</option>
                         <option value="1701" <?php if($id_insumo=="1701") {echo "selected=\"selected\"";} ?>>CINTA DE SEGURIDAD AZUL 38MM</option>
                       </select>-->

                       <select name="id_termica_op" id="id_termica_op" style="width:100px" >
                          <option value=""<?php if(!(strcmp("", $row_referencia['tipoCinta_ref']))) {echo "selected=\"selected\"";}?> >N.A</option>
                           <?php  foreach($row_insumo3 as $row_insumo3 ) { ?>
                          <option value="<?php echo $row_insumo3['id_insumo']?>"<?php if (!(strcmp($row_insumo3['id_insumo'], $row_referencia['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo3['descripcion_insumo']?></option>
                       <?php } ?>
                       </select>
                     </td>
                     <td id="talla1"><input type="number" name="cinta_termica_op" id="cinta_termica_op" value="<?php echo $row_datos_oc['cinta_termica_op']; ?>" style="width:50px" min="0" step="0.01" required="required"/></td>
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
                         <td colspan="11" id="fuente1"><textarea name="observ_sellado_op" style="width: 100%" rows="3" id="observ_sellado_op"></textarea></td>
                         </tr>
                        <tr>
                         <td colspan="11" id="fuente1">&nbsp;</td>
                       </tr>
                       <tr id="tr1">
                         <td colspan="11" id="dato2"><input name="id_proceso" type="hidden" value="1" />
                           <input name="b_estado_op" type="hidden" value="0" />
                           <input name="b_borrado_op" type="hidden" id="b_borrado_op" value="0" />
                           <input name="id_ref_op" type="hidden" id="id_ref_op" value="<?php echo $row_datos_oc['id_ref'] ?>" />
                           <input type="hidden" name="MM_insert" value="form1" />          
                           <input type="submit" class="botonGeneral" name="GUARDAR" id="GUARDAR" value="GUARDAR"  /></td>
                       </tr>
                     </table>
                     </form>
                   </td></tr></table>


                 </div> <!-- contenedor -->

   </div>
  </div>
  </div>
  </div>
  </td>
  </tr>
  </table>
  </div> 
  </div>
 
  </body>



 
</body>
</html>
<script type="text/javascript">
 
  //$(window).on("load", consultaPorcentajesOp($("#int_cod_ref_op").val()));  

   $(document).ready(function(){
     if($("#int_cod_ref_op").val()!=''){
       consultaPorcentajesOp($("#int_cod_ref_op").val()); 
     }
 
   
    });



 
</script>
<script>

 $(document).ready(function(){  
  
        $('#opciones2').select2({ 
            ajax: {
                url: "select3/proceso.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        palabraClave: params.term, // search term
                        var1:"id_c,nombre_c,nit_c",//campo normal para usar
                        var2:"cliente",//tabla
                        var3:"",//where
                        var4:"ORDER BY nombre_c ASC",
                        var5:"id_c",//clave
                        var6:"nombre_c"//columna a buscar
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
 
        $('#int_cod_ref_op').select2({ 
            ajax: {
                url: "select3/proceso.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        palabraClave: params.term, // search term
                        var1:"cod_ref",
                        var2:"tbl_referencia",
                        var3:" estado_ref='1'",//where
                        var4:"ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC",
                        var5:"cod_ref",
                        var6:"cod_ref"//columna a buscar
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

 
 });  


/*  $(document).ready(function(){
   
    anchodelRollo();
});*/

</script>

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
