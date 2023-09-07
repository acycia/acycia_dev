<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
require_once("db/db.php");
require_once 'Models/Referencias.php';
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

$conexion = new ApptivaDB();



$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$nombre1=$_POST['arte1'];
$nombre2=$_POST['arte2'];
$nombre3=$_POST['arte3'];
if (isset($_FILES['archivo1']) && $_FILES['archivo1']['name'] != "") {
if($nombre1 != '') {
if (file_exists("egpbolsa/".$nombre1))
{ unlink("egpbolsa/".$nombre1); } 
}
$directorio = "egpbolsa/";
$nombre1 = $_FILES['archivo1']['name'];
$archivo_temporal = $_FILES['archivo1']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre1)) {
$error = "Error al enviar el Archivo";
} else { $imagen = "egpbolsa/".$nombre1; }
}
if (isset($_FILES['archivo2']) && $_FILES['archivo2']['name'] != "") {
if($nombre2 != '') {
if (file_exists("egpbolsa/".$nombre2))
{ unlink("egpbolsa/".$nombre2); } 
}
$directorio = "egpbolsa/";
$nombre2 = $_FILES['archivo2']['name'];
$archivo_temporal = $_FILES['archivo2']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre2)) {
$error = "Error al enviar el Archivo";
} else { $imagen = "egpbolsa/".$nombre2; }
}
if (isset($_FILES['archivo3']) && $_FILES['archivo3']['name'] != "") {
if($nombre3 != '') {
if (file_exists("egpbolsa/".$nombre3))
{ unlink("egpbolsa/".$nombre3); } 
}
$directorio = "egpbolsa/";
$nombre3 = $_FILES['archivo3']['name'];
$archivo_temporal = $_FILES['archivo3']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre3)) {
$error = "Error al enviar el Archivo";
} else { $imagen = "egpbolsa/".$nombre3; }
}
  $updateSQL = sprintf("UPDATE Tbl_egp SET responsable_egp=%s, codigo_usuario=%s, fecha_egp=%s, hora_egp=%s, estado_egp=%s, ancho_egp=%s, largo_egp=%s, solapa_egp=%s, largo_cang_egp=%s, calibre_egp=%s, tipo_ext_egp=%s, pigm_ext_egp=%s, pigm_int_epg=%s, adhesivo_egp=%s, tipo_bolsa_egp=%s, cantidad_egp=%s, tipo_sello_egp=%s, color1_egp=%s, pantone1_egp=%s, ubicacion1_egp=%s, color2_egp=%s, pantone2_egp=%s, ubicacion2_egp=%s, color3_egp=%s, pantone3_egp=%s, ubicacion3_egp=%s, color4_egp=%s,
  pantone4_egp=%s, ubicacion4_egp=%s, color5_egp=%s, pantone5_egp=%s, ubicacion5_egp=%s, color6_egp=%s, pantone6_egp=%s, ubicacion6_egp=%s, color7_egp=%s, pantone7_egp=%s, ubicacion7_egp=%s, color8_egp=%s, pantone8_egp=%s, ubicacion8_egp=%s, tipo_solapatr_egp=%s, tipo_cinta_egp=%s, tipo_superior_egp=%s, tipo_principal_egp=%s, tipo_inferior_egp=%s, tipo_liner_egp=%s, tipo_nom_egp=%s, tipo_bols_egp=%s, tipo_otro_egp=%s, cb_solapatr_egp=%s, cb_cinta_egp=%s, cb_superior_egp=%s, cb_principal_egp=%s, cb_inferior_egp=%s, cb_liner_egp=%s,
 cb_bols_egp=%s, cb_otro_egp=%s, comienza_egp=%s, fecha_cad_egp=%s, arte_sum_egp=%s, ent_logo_egp=%s, orient_arte_egp=%s, archivo1=%s, archivo2=%s, archivo3=%s, disenador_egp=%s, telef_disenador_egp=%s, unids_paq_egp=%s, unids_caja_egp=%s, marca_cajas_egp=%s, lugar_entrega_egp=%s, observacion5_egp=%s, margen_izq_imp_egp=%s, margen_anc_imp_egp=%s,margen_anc_mm_imp_egp=%s,margen_der_imp_egp=%s,margen_peri_imp_egp=%s,margen_per_mm_imp_egp=%s,margen_z_imp_egp=%s, responsable_modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s, vendedor=%s WHERE n_egp='%s'",
                       /*GetSQLValueString($_POST['cod_ref'], "int"),*/
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['codigo_usuario'], "text"),
             GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
             GetSQLValueString($_POST['hora_modificacion'], "text"),
                       GetSQLValueString($_POST['estado_ref'], "int"),
             GetSQLValueString($_POST['ancho_ref'], "double"),
             GetSQLValueString($_POST['largo_ref'], "double"),
             GetSQLValueString($_POST['solapa_ref'], "double"),
             GetSQLValueString($_POST['bolsillo_guia_ref'], "double"),
             GetSQLValueString($_POST['calibre_ref'], "double"),
             GetSQLValueString($_POST['material_ref'], "text"),
                       GetSQLValueString($_POST['pigm_ext_egp'], "text"),
                       GetSQLValueString($_POST['pigm_int_epg'], "text"),
             GetSQLValueString($_POST['adhesivo_ref'], "text"),                                  
                       GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),
             GetSQLValueString($_POST['cantidad_egp'], "int"),
                       GetSQLValueString($_POST['tipo_sello_egp'], "text"),
                       GetSQLValueString($_POST['color1_egp'], "text"),
                       GetSQLValueString($_POST['pantone1_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion1_egp'], "text"),
                       GetSQLValueString($_POST['color2_egp'], "text"),
                       GetSQLValueString($_POST['pantone2_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion2_egp'], "text"),
                       GetSQLValueString($_POST['color3_egp'], "text"),
                       GetSQLValueString($_POST['pantone3_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion3_egp'], "text"),
                       GetSQLValueString($_POST['color4_egp'], "text"),
                       GetSQLValueString($_POST['pantone4_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion4_egp'], "text"),
                       GetSQLValueString($_POST['color5_egp'], "text"),
                       GetSQLValueString($_POST['pantone5_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion5_egp'], "text"),
                       GetSQLValueString($_POST['color6_egp'], "text"),
                       GetSQLValueString($_POST['pantone6_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion6_egp'], "text"),
                       GetSQLValueString($_POST['color7_egp'], "text"),
                       GetSQLValueString($_POST['pantone7_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion7_egp'], "text"),
                       GetSQLValueString($_POST['color8_egp'], "text"),
                       GetSQLValueString($_POST['pantone8_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion8_egp'], "text"),                        
                       GetSQLValueString($_POST['tipo_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['tipo_cinta_egp'], "text"),
             GetSQLValueString($_POST['tipo_superior_egp'], "text"),
                       GetSQLValueString($_POST['tipo_principal_egp'], "text"),
                       GetSQLValueString($_POST['tipo_inferior_egp'], "text"),
             GetSQLValueString($_POST['tipo_liner_egp'], "text"),
             GetSQLValueString($_POST['tipo_nom_egp'], "text"),
             GetSQLValueString($_POST['tipo_bols_egp'], "text"),
             GetSQLValueString($_POST['tipo_otro_egp'], "text"),
                       GetSQLValueString($_POST['cb_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['cb_cinta_egp'], "text"),
             GetSQLValueString($_POST['cb_superior_egp'], "text"),
                       GetSQLValueString($_POST['cb_principal_egp'], "text"),
                       GetSQLValueString($_POST['cb_inferior_egp'], "text"),
             GetSQLValueString($_POST['cb_liner_egp'], "text"),
             GetSQLValueString($_POST['cb_bols_egp'], "text"),
             GetSQLValueString($_POST['cb_otro_egp'], "text"),
             GetSQLValueString($_POST['comienza_egp'], "text"),
                       GetSQLValueString(isset($_POST['fecha_cad_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['arte_sum_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['ent_logo_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orient_arte_egp']) ? "true" : "", "defined","1","0"),
             GetSQLValueString($nombre1, "text"),
             GetSQLValueString($nombre2, "text"),
             GetSQLValueString($nombre3, "text"),
                       GetSQLValueString($_POST['disenador_egp'], "text"),
                       GetSQLValueString($_POST['telef_disenador_egp'], "text"),
                       GetSQLValueString($_POST['unids_paq_egp'], "int"),
                       GetSQLValueString($_POST['unids_caja_egp'], "int"),
                       GetSQLValueString($_POST['marca_cajas_egp'], "text"),
                       GetSQLValueString($_POST['lugar_entrega_egp'], "text"),
                       GetSQLValueString($_POST['observacion5_egp'], "text"),
             GetSQLValueString($_POST['margen_izq_imp_egp'], "int"),
             GetSQLValueString($_POST['margen_anc_imp_egp'], "int"),
             GetSQLValueString($_POST['margen_anc_mm_imp_egp'], "int"),
             GetSQLValueString($_POST['margen_der_imp_egp'], "int"),
             GetSQLValueString($_POST['margen_peri_imp_egp'], "int"),
             GetSQLValueString($_POST['margen_per_mm_imp_egp'], "int"),
             GetSQLValueString($_POST['margen_z_imp_egp'], "double"),
                       GetSQLValueString($_POST['responsable_modificacion'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
             GetSQLValueString($_POST['vendedor'], "int"),
             GetSQLValueString($_POST['cod_ref'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
 
 
  $updateSQL2 = sprintf("UPDATE Tbl_referencia SET cod_ref=%s, version_ref=%s, n_egp_ref=%s, n_cotiz_ref=%s, tipo_bolsa_ref=%s, material_ref=%s, Str_presentacion=%s,Str_tratamiento=%s, ancho_ref=%s, largo_ref=%s, solapa_ref=%s, b_solapa_caract_ref=%s, bolsillo_guia_ref=%s, str_bols_ub_ref=%s, str_bols_fo_ref=%s, B_cantforma=%s, bol_lamina_1_ref=%s, bol_lamina_2_ref=%s, calibre_ref=%s, peso_millar_ref=%s, B_troquel=%s, B_precorte=%s, N_fuelle=%s, B_fondo=%s, impresion_ref=%s, num_pos_ref=%s, cod_form_ref=%s, adhesivo_ref=%s, estado_ref=%s, registro1_ref=%s, fecha_registro1_ref=%s, registro2_ref=%s, fecha_registro2_ref=%s, B_generica=%s, ancho_rollo=%s, calibreBols_ref=%s, peso_millar_bols=%s,precorte_cuerpo=%s, precorte_solapa=%s, tipoLamina_ref=%s,tipoCinta_ref=%s,valor_impuesto=%s WHERE id_ref='%s'",
                       /*GetSQLValueString($_POST['id_ref'], "int"),*/
                       GetSQLValueString($_POST['cod_ref'], "text"),
                       GetSQLValueString($_POST['version_ref'], "text"),
                       GetSQLValueString($_POST['cod_ref'], "int"),
                       GetSQLValueString($_POST['n_cotiz_ref'], "int"),
                       GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),
                       GetSQLValueString($_POST['material_ref'], "text"),
             GetSQLValueString($_POST['Str_presentacion'], "text"),
             GetSQLValueString($_POST['Str_tratamiento'], "text"),
                       GetSQLValueString($_POST['ancho_ref'], "double"),
                       GetSQLValueString($_POST['largo_ref'], "double"),
             GetSQLValueString($_POST['solapa_ref'], "double"),
             GetSQLValueString($_POST['valora'], "int"),                         
                       GetSQLValueString($_POST['bolsillo_guia_ref'], "double"),
             GetSQLValueString($_POST['str_bols_ub_ref'], "text"),
             GetSQLValueString($_POST['str_bols_fo_ref'], "text"),
             GetSQLValueString($_POST['B_cantforma'], "double"),
             GetSQLValueString($_POST['bol_lamina_1_ref'], "double"),
             GetSQLValueString($_POST['bol_lamina_2_ref'], "double"),
             GetSQLValueString($_POST['calibre_ref'], "double"),
             GetSQLValueString($_POST['peso_millar_ref'], "double"),
             GetSQLValueString($_POST['B_troquel'], "double"),
             GetSQLValueString($_POST['B_precorte'], "int"),
                       GetSQLValueString($_POST['B_fuelle'], "double"),
                       GetSQLValueString($_POST['B_fondo'], "double"),                
                       GetSQLValueString($_POST['impresion_ref'], "text"),            
             GetSQLValueString(isset($_POST['num_pos_ref']) ? "true" : "", "defined","1","0"),
             GetSQLValueString(isset($_POST['cod_form_ref']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['adhesivo_ref'], "text"),
                       GetSQLValueString($_POST['estado_ref'], "int"),
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
                       GetSQLValueString($_POST['registro2_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro2_ref'], "date"),
             GetSQLValueString($_POST['B_generica'], "text"),
             GetSQLValueString($_POST['ancho_rollo'], "text"),
             GetSQLValueString($_POST['calibreBols_ref'], "double"),
             GetSQLValueString($_POST['peso_millar_bols'], "double"),
             GetSQLValueString($_POST['precorte_cuerpo'], "int"),
             GetSQLValueString($_POST['precorte_solapa'], "int"),
             GetSQLValueString($_POST['tipoLamina_ref'], "int"),
             GetSQLValueString($_POST['tipoCinta_ref'], "int"),
             GetSQLValueString($_POST['valor_impuesto'], "text"),
             GetSQLValueString($_POST['id_ref'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());      

//GUARDADO DE HISTORICOS
$myObject = new Referencias();
$historico =  new Referencias();

if(isset($_POST['id_ref'])){ 
  $historico=$myObject->Obtener('tbl_referencia','id_ref',$_POST['id_ref']);
} 

if(isset($_POST['id_ref']) && $historico){
  $myObject->Registrar("tbl_referencia_historico", "id_ref,cod_ref,version_ref,n_egp_ref,n_cotiz_ref,tipo_bolsa_ref,material_ref,Str_presentacion,Str_tratamiento,ancho_ref,N_repeticion_l,N_diametro_max_l,N_peso_max_l,N_cantidad_metros_r_l,N_embobinado_l,Str_referencia_m,Str_linc_m,largo_ref,solapa_ref,b_solapa_caract_ref,bolsillo_guia_ref,str_bols_ub_ref,str_bols_fo_ref,B_cantforma,bol_lamina_1_ref,bol_lamina_2_ref,calibre_ref,peso_millar_ref,Str_boca_entr_p,Str_entrada_p,Str_lamina1_p,Str_lamina2_p,B_troquel,B_precorte,N_fuelle,B_fondo,impresion_ref,num_pos_ref,cod_form_ref,adhesivo_ref,estado_ref,registro1_ref,fecha_registro1_ref,registro2_ref,fecha_registro2_ref,B_generica,calibreBols_ref,peso_millar_bols,precorte_cuerpo,precorte_solapa,tipoLamina_ref,tipoCinta_ref,modifico,valor_impuesto", $historico);
}//FIN HISTORICO

  $updateGoTo = "referencia_bolsa_vista.php?cod_ref=" . $_POST['cod_ref'] . "&tipo=" . $_POST['tipo_usuario'] . "";
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
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

//EL ID_REF ES ENVIADO DESDE VISTA DE REFERENCIA
$colname_referencia_editar = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_editar = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_editar = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref = '%s' AND Tbl_referencia.cod_ref=Tbl_egp.n_egp", $colname_referencia_editar);
$referencia_editar = mysql_query($query_referencia_editar, $conexion1) or die(mysql_error());
$row_referencia_editar = mysql_fetch_assoc($referencia_editar);
$totalRows_referencia_editar = mysql_num_rows($referencia_editar);
//ARTE
$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT userfile,estado_arte_verif FROM verificacion WHERE id_ref_verif = '%s' AND estado_arte_verif = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);
//REF CLIENTE
$colname_refcliente = "-1";
if (isset($_GET['id_ref'])) {
  $colname_refcliente = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refcliente = sprintf("SELECT Tbl_referencia.cod_ref,Tbl_refcliente.id_refcliente,Tbl_refcliente.int_ref_ac_rc,Tbl_refcliente.str_ref_cl_rc,Tbl_refcliente.str_descripcion_rc FROM Tbl_referencia,Tbl_refcliente WHERE Tbl_referencia.id_ref = '%s' and Tbl_referencia.cod_ref=Tbl_refcliente.int_ref_ac_rc", $colname_refcliente);
$refcliente = mysql_query($query_refcliente, $conexion1) or die(mysql_error());
$row_refcliente = mysql_fetch_assoc($refcliente);
$totalRows_refcliente = mysql_num_rows($refcliente);

//INSUMOS CAJAS MEDIDA
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo WHERE clase_insumo IN ('2') ORDER BY  descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumo = mysql_num_rows($insumo);
//INSUMOS TIPO LAMINAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo2 = "SELECT id_insumo,codigo_insumo,descripcion_insumo FROM insumo WHERE clase_insumo IN ('31') ORDER BY descripcion_insumo ASC";
$insumo2 = mysql_query($query_insumo2, $conexion1) or die(mysql_error());
$row_insumo2 = mysql_fetch_assoc($insumo2);
$totalRows_insumo2 = mysql_num_rows($insumo2);
//INSUMOS TIPO ADHESIVO
mysql_select_db($database_conexion1, $conexion1);
$query_insumo3 = "SELECT id_insumo,descripcion_insumo FROM insumo WHERE clase_insumo IN ('30','33','32') AND estado_insumo='0' ORDER BY descripcion_insumo ASC";
$insumo3 = mysql_query($query_insumo3, $conexion1) or die(mysql_error());
$row_insumo3 = mysql_fetch_assoc($insumo3);
$totalRows_insumo3 = mysql_num_rows($insumo3);


$ref_cotiz = $row_referencia_editar['cod_ref'];

mysql_select_db($database_conexion1, $conexion1);
$query_cotiza = "SELECT tipo_bolsa FROM tbl_cotiza_bolsa WHERE N_referencia_c= '$ref_cotiz'" ;
$cotiza = mysql_query($query_cotiza, $conexion1) or die(mysql_error());
$row_cotiza = mysql_fetch_assoc($cotiza);
$totalRows_cotiza = mysql_num_rows($cotiza);


//EL ID_REF ES ENVIADO DESDE VISTA DE REFERENCIA
$colname_ref_cirel = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_cirel = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_cirel = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref = '%s' AND Tbl_referencia.cod_ref=Tbl_egp.n_egp", $colname_ref_cirel);
$ref_cirel = mysql_query($query_ref_cirel, $conexion1) or die(mysql_error());
$row_ref_cirel = mysql_fetch_assoc($ref_cirel);
$totalRows_ref_cirel = mysql_num_rows($ref_cirel);

//SELECTS COMBOS
 $materiasss=$conexion->llenaSelect('insumo',"WHERE clase_insumo='8' AND estado_insumo='0' ", "ORDER BY descripcion_insumo ASC","id_insumo, descripcion_insumo " );



$tippobolsa = $row_referencia_editar['tipo_bolsa_ref']=='' ? $row_cotiza['tipo_bolsa'] : $row_referencia_editar['tipo_bolsa_ref'];
 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>SISADGE AC &amp; CIA</title>

<link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
<body onload="javascript: mostrarBols(this);traslape();calcular_pesom()">
<?php echo $conexion->header('listas'); ?>
 <?php echo $row_usuario['nombre_usuario'] ?>
 <ul id="menuhorizontal"> 
    <!--SELLADO-->
    <?php $idref_e=$row_referencia_editar['id_ref'];
    $sqlpm="SELECT * FROM Tbl_produccion_mezclas WHERE id_ref_pm='$idref_e' and id_proceso='1'";
    $resultpm= mysql_query($sqlpm);
    $row_pm = mysql_fetch_assoc($resultpm);
    $numpm= mysql_num_rows($resultpm);
    if($numpm >='1')
    { ?>     
  <li><a href="produccion_caract_extrusion_mezcla_vista.php?id_c=<?php echo $row_pm['id_c_cv']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>">EXTRUSION</a></li><?php } else{ ?>
    <li><a href="produccion_mezclas_add.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&cod_ref=<?php echo $row_referencia_editar['cod_ref']; ?>">EXTRUSION</a></li>
    <?php } ?>
    <!--//IMPRESION-->
    <?php $idref_i=$row_referencia_editar['id_ref'];
    $sqlci="SELECT DISTINCT id_ref_pmi,id_proceso FROM Tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$idref_i' and id_proceso='2'";
    $resultci= mysql_query($sqlci);
    $row_ci = mysql_fetch_assoc($resultci);
    $numci= mysql_num_rows($resultci);
    if($numci >='1')
    { ?> 
      <li><a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>">IMPRESION</a></li><?php } else{ ?>
      <li><a href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&cod_ref=<?php echo $row_referencia_editar['cod_ref']; ?>">IMPRESION</a></li>
    <?php } ?> 
  </ul>
 
  <form action="<?php echo $editFormAction; ?>" method="post" onsubmit="return validacion_select_bolsillo();" enctype="multipart/form-data" name="form1" id="form1">
    <table class="table table-bordered table-sm">
      <tr id="tr1">
        <td colspan="8" id="titulo2">REFERENCIA ( BOLSA PLASTICA ) </td>
        </tr>
      <tr>
        <td rowspan="7" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="7" id="dato3"><a href="referencia_bolsa_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" /></a><a href="referencia_cliente.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" title="CLIENTES" border="0"></a><a href="referencia_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a>
    <?php $ref=$row_referencia_eitar['id_ref'];
    $sqlrevision="SELECT * FROM revision WHERE id_ref_rev='$ref'";
    $resultrevision= mysql_query($sqlrevision);
    $row_revision = mysql_fetch_assoc($resultrevision);
    $numrev= mysql_num_rows($resultrevision);
    if($numrev >='1')
    { ?><a href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev']; ?>" target="_top" ><img src="images/r.gif" alt="REVISION" border="0" title="REVISION" style="cursor:hand;"></a><?php } else { ?><a href="revision_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/r.gif" alt="ADD REVISION" title="ADD REVISION" border="0" style="cursor:hand;" /></a><?php } ?><a href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a><?php $ref=$row_referencia_editar['id_ref'];
    $sqlval="SELECT * FROM validacion WHERE id_ref_val='$ref'";
    $resultval= mysql_query($sqlval);
    $row_val = mysql_fetch_assoc($resultval);
    $numval= mysql_num_rows($resultval);
    if($numval >='1')
    { ?><a href="validacion_vista.php?id_val=<?php echo $row_val['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="validacion_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } $ref=$row_referencia_editar['id_ref'];
    $sqlft="SELECT * FROM ficha_tecnica WHERE id_ref_ft='$ref'";
    $resultft= mysql_query($sqlft);
    $row_ft = mysql_fetch_assoc($resultft);
    $numft= mysql_num_rows($resultft);
    if($numft >='1')
    { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?>
      <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="dato2">Fecha 
          <input name="fecha_registro1_ref" type="text" value="<?php echo $row_referencia_editar['fecha_registro1_ref']; ?>" size="10" readonly="readonly" /></td>
        <td colspan="6" id="dato3">
          <input type="hidden" name="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario'] ?>" />
          Ingresado por
     <input name="registro1_ref" type="text" value="<?php echo $row_referencia_editar['registro1_ref']; ?>" size="27" readonly="readonly" />
     </td>
        </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><strong>REFERENCIA - VERSION</strong></td>
        <td colspan="3" nowrap="nowrap" id="fuente2">Estado</td>
        <td colspan="3" id="fuente2">Arte</td>
      </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><input name="cod_ref" type="text" value="<?php echo $row_referencia_editar['cod_ref']; ?>" size="5" readonly="readonly"/> 
          - 
            <input name="version_ref" type="text" value="<?php echo $row_referencia_editar['version_ref']; ?>" size="2" /> 
            </td>
        <td colspan="3" id="fuente2"><select name="estado_ref" id="estado_ref">
          <option value="0" <?php if (!(strcmp(0, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Inactiva</option>
          <option value="1" <?php if (!(strcmp(1, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Activa</option>
        </select></td>
        <td colspan="3" id="dato2"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"> <?php echo $row_ref_verif['userfile']; ?> </a> </td>
      </tr>
      <tr>
        <td colspan="7" nowrap="nowrap" id="fuente1"><?php  if ($row_refcliente['id_refcliente']!="") {?>
        <a href="javascript:verFoto('ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente'];?>','840','370')"><?php echo "Ver nombre de la Ref Aquí"; ?></a><?php }else{?>
            <a href="javascript:verFoto('ref_ac_ref_cliente_add.php','840','390')"><?php echo "Agregue Nombre a la Ref Aquí"; ?></a><?php }?></td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="fuente2">Cotizaci&oacute;n N&ordm;</td>
        <td colspan="3" nowrap="nowrap" id="fuente2">Tipo Referencia</td>
        <td colspan="3" id="fuente2">Fecha Arte</td>
      </tr>
      <tr>
        <td id="dato2"><input name="n_cotiz_ref" type="text" value="<?php echo $row_referencia_editar['n_cotiz_ref']; ?>" size="5" readonly="readonly" /></td>
        <td colspan="3" id="dato2"><select name="B_generica" id="B_generica"onblur="if(form1.B_generica.value) { generica(); } else{ alert('Debe Seleccionar GENERICA'); }">
           
          <option value="0"<?php if (!(strcmp(0, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="1"<?php if (!(strcmp(1, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>Generica</option>
          <option value="2"<?php if (!(strcmp(2, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>Otros clientes</option>
 
        </select></td>
        <td colspan="3" id="dato2"><?php echo $row_referencia_editar['fecha_aprob_arte_verif']; ?></td>
      </tr>
      <tr id="tr1">
        <td colspan="8" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">ANCHO (cms)</td>
        <td id="fuente1">LARGO (cms)</td>
        <td colspan="3" id="fuente1">SOLAPA  (cms)</td>
        <td colspan="3" id="fuente1">BOLSILLO PORTAGUIA </td>
      </tr>
      <tr>
        <td id="dato1"><input name="ancho_ref" id="ancho_ref" type="number" style="width:90px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['ancho_ref']; ?>"/></td>
        <td id="dato1"><input name="largo_ref" id="largo_ref" type="number" style="width:90px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['largo_ref']; ?>" onChange="anchodelRollo()" /></td>
        <td colspan="2" id="dato1">
        <p><input type="radio" name="valora" id="ocultar" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],0))) {echo "checked=\"checked\"";} ?> value="0" onClick="return validarRadio(),calcular_pesom();" />N/A<br/>
        <input type="radio" name="valora" id="mostrar" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],2))) {echo "checked=\"checked\"";} ?> value="2" onClick="return validarRadio(),calcular_pesom();"/>Sencilla<br/>
        <input type="radio" name="valora" id="mostrar" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],1))) {echo "checked=\"checked\"";} ?> value="1" onClick="return validarRadio(),calcular_pesom();"/>Doble<br /></p></td>
        <td id="dato1">Solapa valor
          <input name="solapa_ref" id="solapa_ref" type="number" style="width:50px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['solapa_ref']=='' ? 0 :$row_referencia_editar['solapa_ref']; ?>" onblur="calcular_pesom()" onChange="anchodelRollo()"/></td>
        <td colspan="3" id="dato1"><input name="bolsillo_guia_ref" id="bolsillo_guia_ref" type="number" style="width:50px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['bolsillo_guia_ref']; ?>" onChange="mostrarBols(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">CALIBRE (mills)</td>
        <td id="fuente1"> FUELLE  (cms)</td>
        <td colspan="2" id="fuente1">PESO MILLAR</td>
        <td id="fuente1">ADHESIVO</td>
        <td colspan="3" id="fuente1">Tipo</td>
      </tr>
      <tr>
        <td id="dato1"><input name="calibre_ref" id="calibre_ref" type="number" style="width:90px" min="0.00" step="0.01" required="required" onChange="calcular_pesom()" value="<?php echo $row_referencia_editar['calibre_ref']; ?>"/></td>
        <td id="dato1"><input name="B_fuelle" id="B_fuelle" type="number" style="width:90px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['N_fuelle']?>" onChange="anchodelRollo()"/> 
       </td>
        <td colspan="2" id="dato1"><input name="peso_millar_ref" type="text" id="peso_millar_ref" onChange="calcular_pesom();" value="<?php echo $row_referencia_editar['peso_millar_ref']; ?>" size="10" readonly="readonly"/></td>
        <td id="dato1"><select name="adhesivo_ref" id="adhesivo" style="width:100px">
          <option value="N.A" <?php if (!(strcmp("N.A", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
          <option value="CINTA PERMANENTE" <?php if (!(strcmp("CINTA PERMANENTE", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA PERMANENTE</option>
          <option value="CINTA RESELLABLE" <?php if (!(strcmp("CINTA RESELLABLE", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA RESELLABLE</option>
          <option value="CINTA DE SEGURIDAD" <?php if (!(strcmp("CINTA DE SEGURIDAD", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA DE SEGURIDAD</option>
        </select></td>
        <td colspan="3" id="dato1"><select name="tipoCinta_ref" id="tipocinta" style="width:100px">
         <option value="" <?php if (!(strcmp("", $row_referencia_editar['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <?php
          do {  
           ?>
           <option value="<?php echo $row_insumo3['id_insumo']?>"<?php if (!(strcmp($row_insumo3['id_insumo'], $row_referencia_editar['tipoCinta_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo3['descripcion_insumo']?></option>
           <?php
         } while ($row_insumo3 = mysql_fetch_assoc($insumo3));
         $rows = mysql_num_rows($insumo3);
         if($rows > 0) {
           mysql_data_seek($insumo3, 0);
           $row_insumo3 = mysql_fetch_assoc($insumo3);
         }
         ?>
        </select></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">TIPO DE BOLSA</td>
        <td id="fuente1">TIPO DE SELLO</td>
        <td id="fuente1">TROQUEL</td>
        <td id="fuente1">PRECORTE</td>
        <td id="fuente1">pre./cuerpo</td>
        <td id="fuente1">pre.e/solapa</td>
        <td id="fuente1">FONDO</td>
      </tr>
      <tr>
        <td id="fuente1"><select name="tipo_bolsa_ref" id="tipo_bolsa_ref" style="width:100px" onChange="calcular_pesom();" onblur="anchoRolloRef();" > 
          <option value="SEGURIDAD" <?php if (!(strcmp("SEGURIDAD", $tippobolsa))) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
          <option value="CURRIER" <?php if (!(strcmp("CURRIER", $tippobolsa))) {echo "selected=\"selected\"";} ?>>CURRIER</option>
          <option value="BOLSA PLASTICA" <?php if (!(strcmp("BOLSA PLASTICA", $tippobolsa))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
          <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $tippobolsa))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
          <option value="COMPOSTABLE" <?php if (!(strcmp("COMPOSTABLE", $tippobolsa))) {echo "selected=\"selected\"";} ?>>COMPOSTABLE</option>
          <option value="BOLSA TROQUELADA" <?php if (!(strcmp("BOLSA TROQUELADA", $tippobolsa))) {echo "selected=\"selected\"";} ?>>BOLSA TROQUELADA</option>
        </select></td>
        <td id="fuente1">
          <select name="tipo_sello_egp" id="tipo_sello_egp" style="width:100px">
          <option></option>
          <option value="HILO"<?php if (!(strcmp("HILO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO</option>
          <option value="PLANO"<?php if (!(strcmp("PLANO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>PLANO</option>
          <option value="HILO/PLANO"<?php if (!(strcmp("HILO/PLANO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO/PLANO</option>
        </select>
      </td>
        <td id="fuente1">
          <select name="B_troquel" id="B_troquel" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="1"<?php if (!(strcmp("1", $row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select>
      </td>
        <td id="fuente1">
          <select name="B_precorte" id="B_precorte" style="width:50px">
          <option value="1"<?php if (!(strcmp("1", $row_referencia_editar['B_precorte']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_referencia_editar['B_precorte']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select>
      </td>
        <td id="fuente1">
          <input name="precorte_cuerpo" id="precorte_cuerpo" type="number" style="width:50px" min="0" max="7" step="1" required="required" value="<?php echo $row_referencia_editar['precorte_cuerpo']; ?>"/>
        </td>
        <td id="fuente1">
          <input name="precorte_solapa" id="precorte_solapa" type="number" style="width:50px" min="0" max="7" step="1" required="required" value="<?php echo $row_referencia_editar['precorte_solapa']; ?>"/>
        </td>
        <td id="fuente1">
          <select name="B_fondo" id="B_fondo" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="1"<?php if (!(strcmp("1", $row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select>
      </td>
      </tr>
      <tr id="tr1">
        <td rowspan="2" id="fuente1">PRESENTACION</td>
        <td rowspan="2" id="fuente1">TRATAMIENTO</td>
        <td colspan="5" id="fuente2">Bolsillo Portaguia</td>
        </tr>
      <tr>
        <td id="fuente1">(Ubicacion)</td>
        <td id="fuente1">Forma:</td>
        <td id="fuente1">Cant/Traslape</td>
        <td id="fuente1">Tipo /Lamina</td>
        <td id="fuente1">Lamina Bols.</td>
      </tr>
      <tr>
        <td id="fuente1">
          <select name="Str_presentacion" id="opciones2" style="width:100px" onchange="anchoRolloRef();">
          <option value="LAMINA" <?php if (!(strcmp('LAMINA', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>LAMINA</option>
          <option value="TUBULAR" <?php if (!(strcmp('TUBULAR', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>TUBULAR</option>
       <option value="SEMITUBULAR" <?php if (!(strcmp('SEMITUBULAR', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>SEMITUBULAR</option>
        </select>
        <br>
         <input name="ancho_rollo" id="ancho_rollo" style="width:100px" type="text" value=""  />Ancho Rollo 
      </td>
        <td id="fuente1"><select name="Str_tratamiento" id="Str_tratamiento" style="width:100px">
          <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="UNA CARA" <?php if (!(strcmp('UNA CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
          <option value="DOBLE CARA" <?php if (!(strcmp('DOBLE CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
        </select></td>
        <td id="fuente1"><select name="str_bols_ub_ref" id="str_bols_ub_ref" style="width:50px">
          <option value="">N.A.</option>
          <option value="ANVERSO"<?php if (!(strcmp('ANVERSO', $row_referencia_editar['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Anverso</option>
          <option value="REVERSO"<?php if (!(strcmp('REVERSO', $row_referencia_editar['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Reverso</option>
        </select></td>
        <td id="fuente1"><select name="str_bols_fo_ref" id="str_bols_fo_ref" style="width:50px"  onChange="traslape(this)">
          <option value="">N.A.</option>
          <option value="TRANSLAPE"<?php if (!(strcmp('TRANSLAPE', $row_referencia_editar['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Translape</option>
          <option value="RESELLABLE"<?php if (!(strcmp('RESELLABLE', $row_referencia_editar['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Resellable</option>
        </select></td>
        <td id="fuente1"><input name="B_cantforma" id="B_cantforma" disabled type="number" style="width:50px" min="0.00" step="0.01" required="required" value="<?php echo $row_referencia_editar['B_cantforma']; ?>"/>
          <input name="auxil" type="hidden" id="auxil" value="<?php echo $row_referencia_editar['B_cantforma']; ?>" /></td>
        <td id="dato1">
          <select name="tipoLamina_ref" id="tipolam" style="width:100px" onChange="medida_bolsillo(this);calcular_pesomBols()"><!--onblur="validacion_todos_select(this)"-->
          <option value="0"<?php if (!(strcmp("", $row_referencia_editar['tipoLamina_ref']))) {echo "selected=\"selected\"";} ?>>Tipo Lamina</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_insumo2['id_insumo']?>"<?php if (!(strcmp($row_insumo2['id_insumo'], $row_referencia_editar['tipoLamina_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo2['descripcion_insumo']?></option>
            <?php
          } while ($row_insumo2 = mysql_fetch_assoc($insumo2));
          $rows = mysql_num_rows($insumo2);
          if($rows > 0) {
            mysql_data_seek($insumo2, 0);
            $row_insumo2 = mysql_fetch_assoc($insumo2);
          }
          ?>
        </select></td>
        <td id="dato1">
          <span class="laminas" title="Edita superusuario"> Lamina1 <input name="bol_lamina_1_ref" id="valorlam" style="width:50px" min="0"step="0.01" type="number" required="required" onchange="calcular_pesomBols()" value="<?php if($row_referencia_editar['bol_lamina_1_ref']==''){echo '0.00';}else{echo $row_referencia_editar['bol_lamina_1_ref'];}?>" <?php if(!$_SESSION['superacceso']): ?> readonly="readonly" <?php endif; ?> />
        </span>
         </td>
        </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">Calibre/Bols</td>
        <td id="fuente1"><input name="calibreBols_ref" id="calibreBols_ref" type="number" style="width:50px" min="0.00" step="0.1" onchange="calcular_pesomBols()" value="<?php echo $row_referencia_editar['calibreBols_ref']; ?>"/></td>
        <td id="fuente1">Peso Millar Bols.</td>
        <td id="fuente1"><input name="peso_millar_bols" readonly="readonly" id="peso_millar_bols" type="number" style="width:50px" min="0.00" step="0.01" required="required" onclick="calcular_pesomBols()" value="<?php echo $row_referencia_editar['peso_millar_bols'] ?>"/></td>
        <td id="fuente1">
          <span class="laminas" title="Edita superusuario"> Lamina2 <input name="bol_lamina_2_ref" id="bol_lamina_2_ref" style="width:50px" min="0"step="0.01" type="number" required="required" onchange="calcular_pesomBols()" size="5" value="<?php if($row_referencia_editar['bol_lamina_2_ref']==''){echo '0.00';}else{echo $row_referencia_editar['bol_lamina_2_ref'];} ?>" <?php if(!$_SESSION['superacceso']): ?> readonly="readonly" <?php endif; ?>/>
            </span>
          </td>
        </tr>
        <tr id="tr1"> 
        <td rowspan="2" id="talla1">MARGENES</td>
        <td id="fuente1">Izquierda mm</td>
        <td id="fuente1"><input name="margen_izq_imp_egp" id="margen_izq_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_izq_imp_egp'];?>"/></td>
        <td id="fuente1">Rep. en Ancho</td>
        <td id="fuente1"><input name="margen_anc_imp_egp" id="margen_anc_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_anc_imp_egp']?>"/></td>
        <td id="fuente2">de</td>
        <td id="fuente1"><input name="margen_anc_mm_imp_egp" id="margen_anc_mm_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_anc_mm_imp_egp']?>"/>mm</td>
      </tr>
      <tr>
        <td id="fuente1">Derecha mm</td>
        <td id="fuente1"><input name="margen_der_imp_egp" id="margen_der_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_der_imp_egp']?>"/></td>
        <td id="fuente1">Rep. Perimetro</td>
        <td id="fuente1"><input name="margen_peri_imp_egp" id="margen_peri_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_peri_imp_egp']?>"/></td>
        <td id="fuente2">de</td>
        <td id="fuente1"><input name="margen_per_mm_imp_egp" id="margen_per_mm_imp_egp" style="width:50px" type="number" min="0" size="5" step="1" value="<?php echo $row_referencia_editar['margen_per_mm_imp_egp']?>"/>mm</td>
      </tr>
      <tr  id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1"><strong>Z</strong></td>
        <td id="fuente1"><input name="margen_z_imp_egp" id="margen_z_imp_egp" style="width:50px" type="number" min="0" step="0.01" value="<?php echo $row_referencia_editar['margen_z_imp_egp']?>"/></td>
        <td colspan="5" id="fuente1">IMPUESTO $  <strong><input name="valor_impuesto" id="valor_impuesto" style="width:50px" type="text" value="<?php echo $row_referencia_editar['valor_impuesto']?>" <?php if(!$_SESSION['superacceso']){ echo "readonly"; } ?> /></strong>
        </td>
        </tr>        
        
      <tr>
        <td height="44" colspan="8" id="dato1">Ultima Actualizaci&oacute;n : 
          <input name="registro2_ref" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['registro2_ref']; ?>
          <input name="fecha_registro2_ref" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_referencia_editar['fecha_registro2_ref']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" /></td>
      </tr>
    </table>
      
        
        <table class="table table-bordered table-sm">
      <tr >
        <td colspan="3" id="titulo4">DATOS ESPECIFICOS DE LA REFERENCIA</td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">MATERIAL</td>
        <td id="fuente1">PIGMENTO EXTERIOR</td>
        <td id="fuente1">PIGMENTO INTERIOR </td>
      </tr>
      <tr>
        <td id="dato1">
        <select name="material_ref" id="material_ref">
      <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
        <option value="PIGMENTADO B/N"<?php if (!(strcmp("PIGMENTADO B/N", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/N</option>
        <option value="PIGMENTADO B/B"<?php if (!(strcmp("PIGMENTADO B/B", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO B/B</option>
        
      </select> 
          <!--<select name="tipo_ext_egp" id="tipo_ext_egp">
        <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
        
          <option value="P.E.B.D-PIGMENTADO" <?php if (!(strcmp("P.E.B.D-PIGMENTADO", $row_referencia_editar['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>P.E.B.D - PIGMENTADO</option>        
          <option value="P.E.B.D-TRANSPARENTE" <?php if (!(strcmp("P.E.B.D-TRANSPARENTE", $row_referencia_editar['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>P.E.B.D - TRANSPARENTE</option>
          <option value="COEXTRUSION" <?php if (!(strcmp("COEXTRUSION", $row_referencia_editar['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>COEXTRUSION</option></select>-->
          
          <!--<input type="hidden" name="material_ref" id="material_ref" value="<?php echo $row_referencia_editar['material_ref'] ?>" />--></td>
        <td id="dato1"><input type="text" name="pigm_ext_egp" value="<?php echo $row_referencia_editar['pigm_ext_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pigm_int_epg" value="<?php echo $row_referencia_editar['pigm_int_epg']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Numero de Colores</td>
        <td id="fuente1"><input <?php if (!(strcmp($row_referencia_editar['num_pos_ref'],1))) {echo "checked=\"checked\"";} ?> name="num_pos_ref" type="checkbox" value="1" />
          Numeracion            </td>
        <td id="fuente1">Mezclas:</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="impresion_ref" size="5" id="impresion_ref" value="<?php echo $row_referencia_editar['impresion_ref'] ?>" />          <?php //echo  "Lleva ".$row_ver_ref['impresion_ref']." Colores"?></td>
        <td id="dato1"><input <?php if (!(strcmp($row_referencia_editar['cod_form_ref'],1))) {echo "checked=\"checked\"";} ?> name="cod_form_ref" type="checkbox" value="1" />
        Codigo de Barras </td>
        <td id="dato1">
          <em>
       <?php 

       $cod_ref=$row_referencia_editar['cod_ref'];
       $sqloca="SELECT * FROM tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas_impresion pm ON pm.int_cod_ref_pmi = cp.cod_ref WHERE cp.cod_ref='$cod_ref' AND cp.proceso=2 ORDER BY cp.proceso DESC LIMIT 1"; //nuevas mezclas
       $resultca = mysql_query($sqloca); 
       $existenuevamezcla=mysql_num_rows($resultca); 
       $refNueva = mysql_result($resultca, 0, 'cod_ref');
       $procesoNuevo = mysql_result($resultca, 0, 'proceso');


        $id_ref=$row_referencia_editar['id_ref'];
        $sqlop="SELECT id_ref_cp FROM Tbl_caract_proceso WHERE id_ref_cp='$id_ref' AND id_proceso='2' ORDER BY id_ref_cp DESC LIMIT 1"; 
        $resultop=mysql_query($sqlop); 
        $numop=mysql_num_rows($resultop); 


       if( $numop >= '1' ) { ?>
        
            <a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref'];?>" title="Actualizar Mezcla" target="_blank">Ver Mezclas-colores-Antigua</a> 
             <?php }else { ?>
 
             <a href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_referencia_editar['id_ref'];?>&cod_ref=<?php echo $row_referencia_editar['cod_ref'];?>" title="Actualizar Mezcla" target="_blank">Falta-Mezcla-Antigua</a> 
        /  <?php } ?>

      <?php if( $refNueva >= '1' && $procesoNuevo==2) { ?>
        <a href="javascript:popUp('view_index.php?c=cmezclasIm&a=Mezcla&cod_ref=<?php echo $row_referencia_editar['cod_ref'];?>','1500','700')">Ver Mezclas-colores-Nueva</a>
 
         <?php }else { ?> 

        <a href="javascript:popUp('view_index.php?c=cmezclasIm&a=Mezcla&cod_ref=<?php echo $row_referencia_editar['cod_ref'];?>','1500','700')">Falta-Mezcla-Nueva</a>
 
      <?php } ?>
     </em></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 1 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color1_egp" value="<?php echo $row_referencia_editar['color1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
          <select name="pantone1_egp" id="pantone1_egp" class="busqueda selectsGrande" >
             <option value=""<?php if (!(strcmp("", $row_referencia_editar['pantone1_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
             <?php foreach($materiasss as $row_materia_prima ) { ?>
                 <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_referencia_editar['pantone1_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
               </option>
             <?php } ?> 
           </select>
         </td>
        <td id="dato1"><input type="text" name="ubicacion1_egp" value="<?php echo $row_referencia_editar['ubicacion1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 2 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color2_egp" value="<?php echo $row_referencia_editar['color2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
          <select name="pantone2_egp" id="pantone2_egp" class="busqueda selectsGrande" >
             <option value=""<?php if (!(strcmp("", $row_referencia_editar['pantone2_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
             <?php foreach($materiasss as $row_materia_prima ) { ?>
                 <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_referencia_editar['pantone2_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
               </option>
             <?php } ?> 
           </select>
         </td>
        <td id="dato1"><input type="text" name="ubicacion2_egp" value="<?php echo $row_referencia_editar['ubicacion2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 3 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color3_egp" value="<?php echo $row_referencia_editar['color3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
          <select name="pantone3_egp" id="pantone3_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_referencia_editar['pantone3_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_referencia_editar['pantone3_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select></td>
        <td id="dato1"><input type="text" name="ubicacion3_egp" value="<?php echo $row_referencia_editar['ubicacion3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>      
      <tr id="tr1">
        <td id="fuente1">Color 4</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color4_egp" value="<?php echo $row_referencia_editar['color4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
          <select name="pantone4_egp" id="pantone4_egp" class="busqueda selectsGrande" >
             <option value=""<?php if (!(strcmp("", $row_referencia_editar['pantone4_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
             <?php foreach($materiasss as $row_materia_prima ) { ?>
                 <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_referencia_editar['pantone4_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
               </option>
             <?php } ?> 
           </select>
         </td>
        <td id="dato1"><input type="text" name="ubicacion4_egp" value="<?php echo $row_referencia_editar['ubicacion4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 5 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color5_egp" value="<?php echo $row_referencia_editar['color5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone5_egp" id="pantone5_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_referencia_editar['pantone5_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_referencia_editar['pantone5_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion5_egp" value="<?php echo $row_referencia_editar['ubicacion5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 6 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color6_egp" value="<?php echo $row_referencia_editar['color6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone6_egp" id="pantone6_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_referencia_editar['pantone6_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_referencia_editar['pantone6_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion6_egp" value="<?php echo $row_referencia_editar['ubicacion6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 7</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color7_egp" value="<?php echo $row_referencia_editar['color7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone7_egp" id="pantone7_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_referencia_editar['pantone7_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_referencia_editar['pantone7_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion7_egp" value="<?php echo $row_referencia_editar['ubicacion7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>  
      <tr id="tr1">
        <td id="fuente1">Color 8</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color8_egp" value="<?php echo $row_referencia_editar['color8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1">
        <select name="pantone8_egp" id="pantone8_egp" class="busqueda selectsGrande" >
           <option value=""<?php if (!(strcmp("", $row_referencia_editar['pantone8_egp']))) {echo "selected=\"selected\"";} ?>>COLOR</option> 
           <?php foreach($materiasss as $row_materia_prima ) { ?>
               <option value="<?php echo $row_materia_prima['id_insumo']; ?>"<?php if (!(strcmp($row_materia_prima['id_insumo'],$row_referencia_editar['pantone8_egp']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_materia_prima['descripcion_insumo']); ?> 
             </option>
           <?php } ?> 
         </select>
       </td>
        <td id="dato1"><input type="text" name="ubicacion8_egp" value="<?php echo $row_referencia_editar['ubicacion8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>           
      <tr id="tr1">
        <td id="detalle2">POSICION</td>
        <td id="detalle2">TIPO DE NUMERACION </td>
        <td id="detalle2">FORMATO &amp; CODIGO DE BARAS </td>
      </tr>
      <tr>
        <td id="detalle1">Solapa TR </td>
        <td id="detalle2"><select name="tipo_solapatr_egp" id="tipo_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
  <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_solapatr_egp" id="cb_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select>
        </td>
      </tr>
      <tr>
        <td id="detalle1">Cinta</td>
        <td id="detalle2"><select name="tipo_cinta_egp" id="tipo_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
        </select></td>
        <td id="detalle2"><select name="cb_cinta_egp" id="cb_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>          
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Superior</td>
        <td id="detalle2"><select name="tipo_superior_egp" id="tipo_superior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_superior_egp" id="cb_superior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Principal</td>
        <td id="detalle2"><select name="tipo_principal_egp" id="tipo_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_principal_egp" id="cb_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Inferior</td>
        <td id="detalle2"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_inferior_egp" id="cb_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
          
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Liner</td>
        <td id="detalle2"><select name="tipo_liner_egp" id="tipo_liner_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_liner_egp" id="cb_liner_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
          
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Bolsillo</td>
        <td id="detalle2"><select name="tipo_bols_egp" id="tipo_bols_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_bols_egp" id="cb_bols_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">        
        <input type="text" list="misdatos" name="tipo_nom_egp" id="tipo_nom_egp" value="<?php echo $row_referencia_editar['tipo_nom_egp']; ?>" onBlur="primeraletra(this)">
        <datalist id="misdatos">
         <option  label="Solapa TR" value="Solapa TR">
         <option  label="Cinta" value="Cinta">
         <option  label="Superior" value="Superior">
         <option  label="Principal" value="Principal">
         <option  label="Inferior" value="Inferior">
         <option  label="Liner" value="Liner">
         <option  label="Bolsillo" value="Bolsillo">
        </datalist></td>
        <td id="detalle2"><select name="tipo_otro_egp" id="tipo_otro_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_otro_egp" id="cb_otro_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select></td>
      </tr>      
      <tr>
        <td id="detalle3">Numeracion Comienza en </td>
        <td id="detalle2"><input type="text" name="comienza_egp" required="required" value="<?php echo $row_referencia_editar['comienza_egp']; ?>" size="20" onKeyUp="return ValNumero(this)"/></td>
        <td id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> name="fecha_cad_egp" type="checkbox" value="0" />
Incluir Fecha de Caducidad </td>
      </tr>
      <tr id="tr1">
        <td colspan="2" nowrap="nowrap" id="dato1">Cyreles ?: <?php if ($row_referencia_editar['B_cyreles']==1){ echo "SI ";}else {echo "NO";}?>
          Se Facturan Artes y Planchas</td>
        <td nowrap="nowrap" id="dato4">&nbsp;</td>
      </tr>
      <tr>
<td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="arte_sum_egp" type="checkbox" value="1" />
          Arte Suministrado por el Cliente</td>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="ent_logo_egp" type="checkbox" value="1" />
          Entrega Logos de la Entidad</td>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orient_arte_egp" type="checkbox" value="1" />
          Solicita Orientaci&oacute;n en el Arte</td>
      </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="detalle4">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="detalle2">Adjuntar Artes, Logos o Archivos suministrado, solo archivos pdf </td>
        <td id="detalle2">Dise&ntilde;ador</td>
      </tr>
      <tr>
        <td colspan="2" rowspan="3" id="detalle2">
          <table border="0">
            <tr>
              <td id="dato1"><input name="arte1" type="hidden" value="<?php echo $row_referencia_editar['archivo1'];?>" /><a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo1'];?>','610','490')"><?php if ($row_referencia_editar['archivo1']!="")echo "Arte1";?></a></td><td id="dato2"><input type="file" name="archivo1"size="20" /></td>              
            </tr>
            <tr>
              <td id="dato1"><input name="arte2" type="hidden" value="<?php echo $row_referencia_editar['archivo2'];?>" />
              <a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo2'];?>','610','490')"><?php if ($row_referencia_editar['archivo2']!="")echo "Arte2";?></a></td>
              <td id="dato2"><input type="file" name="archivo2"size="20"/></td>              
            </tr>
            <tr>
              <td id="dato1"><input name="arte3" type="hidden" value="<?php echo $row_referencia_editar['archivo3'];?>" />
                <a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo3'];?>','610','490')"><?php if ($row_referencia_editar['archivo3']!="")echo "Arte3";?></a></td>
              <td id="dato2"><input type="file" name="archivo3" size="20"/></td>              
            </tr>
        </table>
      </td>
        <td id="detalle2">
          <input type="text" name="disenador_egp" value="<?php echo $row_referencia_editar['disenador_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="detalle2">Telefono </td>
      </tr>
      <tr>
        <td id="detalle2"><input type="text" name="telef_disenador_egp" value="<?php echo $row_referencia_editar['telef_disenador_egp']; ?>" size="20" onKeyUp="return ValNumero(this)"/></td>
      </tr>
      <tr>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
      </tr>       
      <tr id="tr1">
        <td id="dato1">Unidades por Paquete </td>
        <td id="dato1">Unidades por Caja </td>
        <td id="dato1">Medida de la Caja</td>
      </tr>
      <tr>
        <td id="dato1"><input type="number" name="unids_paq_egp" value="<?php echo $row_referencia_editar['unids_paq_egp']; ?>" required="required" size="20" /></td>
        <td id="dato1"><input type="number" name="unids_caja_egp" value="<?php echo $row_referencia_editar['unids_caja_egp']; ?>" required="required" size="20" /></td>
        <td id="dato1"><!--<input type="text" list="misdatos2" name="marca_cajas_egp" id="marca_cajas_egp" value="<?php echo $row_referencia_editar['marca_cajas_egp']; ?>" onBlur="primeraletra(this)">
        <datalist id="misdatos2">
         <option  label="Pendiente" value="Pendiente">
         <option  label="Prenderia" value="Prenderia 24x21x21">
         <option  label="Av. Grande" value="Av Grande 46x30x21">
         <option  label="Av. pequeña" value="Av pequeña 38x25x19">
         <option  label="Standar" value="Standar 54x45x21">
         <option  label="Sobre" value="Sobre 39x28x1">
         <option  label="Bulto" value="Bulto 54x34x24">
        </datalist>-->
      <select name="marca_cajas_egp" id="opciones" style="width:150px"><!--onblur="validacion_todos_select(this)"-->
      <option value="NA"<?php if (!(strcmp(0, $row_referencia_editar['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>>NA</option>
        <?php
do {  
?>
        <option value="<?php echo $row_insumo['id_insumo']?>"<?php if (!(strcmp($row_insumo['id_insumo'], $row_referencia_editar['marca_cajas_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumo['descripcion_insumo']?></option>
        <?php
} while ($row_insumo = mysql_fetch_assoc($insumo));
  $rows = mysql_num_rows($insumo);
  if($rows > 0) {
      mysql_data_seek($insumo, 0);
    $row_insumo = mysql_fetch_assoc($insumo);
  }
?>
      </select></td>
      </tr>
      <tr>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
      </tr>      
      <tr id="tr1">
        <td colspan="3" id="fuente1">Observaciones</td>
      </tr>
      <tr>
        <td colspan="3" id="dato1"><textarea name="observacion5_egp" cols="75" rows="2" id="observacion5_egp"onKeyUp="conMayusculas(this)"><?php echo $row_referencia_editar['observacion5_egp']; ?></textarea></td>
      <tr>
        <td colspan="3" id="fuente1">&nbsp;</td>
      </tr>
         <tr id="tr1"> 
            <td colspan="3" id="fuente1">
               <br> 
              <a class="botonGMini" target="_blank"  href="view_index.php?c=creferencias&a=Crud&id=<?php echo $row_referencia_editar['id_ref']; ?>&columna=id_ref&tabla=tbl_referencia_historico">VER HISTORICO DE MODIFICACIONES</a>
              <P><br><br><br> </P> 
        </td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">Ultima Modificaci&oacute;n : 
          <input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['responsable_modificacion']; ?>
          <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_referencia_editar['fecha_modificacion']; ?>
          <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a"); ?>" />
          <?php echo $row_referencia_editar['hora_modificacion']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
          <input name="codigo_usuario" type="hidden" id="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario']; ?>" /></td>
        <td id="dato2">
          <input name="submit" type="submit" onclick="anchodelRollo()" value="EDITAR REFERENCIA" />
        </td>
      </tr>

    </table>
        <input type="hidden" name="vendedor" id="vendedor" value="<?php echo $row_referencia_editar['vendedor']?>"/>
        <input type="hidden" name="MM_update" value="form1">
    <!--<input type="hidden" name="Str_nit" value="<?php //echo $row_ver_ref['Str_nit']; ?>">--> 
    <input type="hidden" name="id_ref" value="<?php echo $row_referencia_editar['id_ref']; ?>">     
  </form>
  <?php echo $conexion->header('footer'); ?>
 

</body>
</html>
<script type="text/javascript">
  
  $(document).ready(function(){
    verLaminas();
    anchodelRollo();
});

$('#tipolam').on('change', function() { 
    verLaminas()
});

    function verLaminas(){
      if( $('#tipolam').val() > 0){
         $(".laminas").show(); 
    
       }else{
         //$(".laminas").hide(); 
       }
    }

 $(document).ready(function(){

   var editar =  "<?php echo $_SESSION['no_edita'];?>";
   var acceso =  "<?php echo $_SESSION['acceso'];?>";
   //var restriUsuarios =  "<?php echo $_SESSION['restriUsuarios'];?>";
 
   if( editar==0 && acceso==0){

     $("input").attr('disabled','disabled');
     $("textarea").attr('disabled','disabled');
     $("select").attr('disabled','disabled'); 

     $('a').each(function() { 
       $(this).attr('href', '#');
     });
              //swal("No Autorizado", "Sin permisos para editar :)", "error"); 
   }
 });


   

</script>
<?php

mysql_free_result($usuario);

mysql_free_result($ref_verif);

mysql_free_result($refcliente);

mysql_free_result($referencia_editar);

?>
