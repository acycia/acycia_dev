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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE ver_egp SET responsable_ver_egp=%s, codigo_usuario=%s, fecha_ver_egp=%s, hora_ver_egp=%s, estado_ver_egp=%s, ancho_ver_egp=%s, largo_ver_egp=%s, solapa_ver_egp=%s, largo_cang_ver_egp=%s, calibre_ver_egp=%s, tipo_ext_ver_egp=%s, pigm_ext_ver_egp=%s, pigm_int_epg=%s, adhesivo_ver_egp=%s, tipo_bolsa_ver_egp=%s, cantidad_ver_egp=%s, tipo_sello_ver_egp=%s, observacion1_ver_egp=%s, color1_ver_egp=%s, pantone1_ver_egp=%s, ubicacion1_ver_egp=%s, color2_ver_egp=%s, pantone2_ver_egp=%s, ubicacion2_ver_egp=%s, color3_ver_egp=%s, pantone3_ver_egp=%s, ubicacion3_ver_egp=%s, color4_ver_egp=%s, pantone4_ver_egp=%s, ubicacion4_ver_egp=%s, color5_ver_egp=%s, pantone5_ver_egp=%s, ubicacion5_ver_egp=%s, color6_ver_egp=%s, pantone6_ver_egp=%s, ubicacion6_ver_egp=%s, observacion2_ver_egp=%s, tipo_solapatr_ver_egp=%s, tipo_cinta_ver_egp=%s, tipo_principal_ver_egp=%s, tipo_inferior_ver_egp=%s, cb_solapatr_ver_egp=%s, cb_cinta_ver_egp=%s, cb_principal_ver_egp=%s, cb_inferior_ver_egp=%s, comienza_ver_egp=%s, fecha_cad_ver_egp=%s, observacion3_ver_egp=%s, arte_sum_ver_egp=%s, ent_logo_ver_egp=%s, orient_arte_ver_egp=%s, archivo1=%s, archivo2=%s, archivo3=%s, disenador_ver_egp=%s, telef_disenador_ver_egp=%s, observacion4_ver_egp=%s, unids_paq_ver_egp=%s, unids_caja_ver_egp=%s, marca_cajas_ver_egp=%s, lugar_entrega_ver_egp=%s, observacion5_ver_egp=%s, responsable_modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s WHERE n_ver_egp=%s",
                       GetSQLValueString($_POST['responsable_ver_egp'], "text"),
                       GetSQLValueString($_POST['codigo_usuario'], "text"),
                       GetSQLValueString($_POST['fecha_ver_egp'], "date"),
                       GetSQLValueString($_POST['hora_ver_egp'], "text"),
                       GetSQLValueString($_POST['estado_ver_egp'], "int"),
                       GetSQLValueString($_POST['ancho_ver_egp'], "double"),
                       GetSQLValueString($_POST['largo_ver_egp'], "double"),
                       GetSQLValueString($_POST['solapa_ver_egp'], "double"),
                       GetSQLValueString($_POST['largo_cang_ver_egp'], "double"),
                       GetSQLValueString($_POST['calibre_ver_egp'], "double"),
                       GetSQLValueString($_POST['tipo_ext_ver_egp'], "text"),
                       GetSQLValueString($_POST['pigm_ext_ver_egp'], "text"),
                       GetSQLValueString($_POST['pigm_int_epg'], "text"),
                       GetSQLValueString($_POST['adhesivo_ver_egp'], "text"),
                       GetSQLValueString($_POST['tipo_bolsa_ver_egp'], "text"),
                       GetSQLValueString($_POST['cantidad_ver_egp'], "text"),
                       GetSQLValueString($_POST['tipo_sello_ver_egp'], "text"),
                       GetSQLValueString($_POST['observacion1_ver_egp'], "text"),
                       GetSQLValueString($_POST['color1_ver_egp'], "text"),
                       GetSQLValueString($_POST['pantone1_ver_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion1_ver_egp'], "text"),
                       GetSQLValueString($_POST['color2_ver_egp'], "text"),
                       GetSQLValueString($_POST['pantone2_ver_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion2_ver_egp'], "text"),
                       GetSQLValueString($_POST['color3_ver_egp'], "text"),
                       GetSQLValueString($_POST['pantone3_ver_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion3_ver_egp'], "text"),
                       GetSQLValueString($_POST['color4_ver_egp'], "text"),
                       GetSQLValueString($_POST['pantone4_ver_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion4_ver_egp'], "text"),
                       GetSQLValueString($_POST['color5_ver_egp'], "text"),
                       GetSQLValueString($_POST['pantone5_ver_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion5_ver_egp'], "text"),
                       GetSQLValueString($_POST['color6_ver_egp'], "text"),
                       GetSQLValueString($_POST['pantone6_ver_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion6_ver_egp'], "text"),
                       GetSQLValueString($_POST['observacion2_ver_egp'], "text"),
                       GetSQLValueString($_POST['tipo_solapatr_ver_egp'], "text"),
                       GetSQLValueString($_POST['tipo_cinta_ver_egp'], "text"),
                       GetSQLValueString($_POST['tipo_principal_ver_egp'], "text"),
                       GetSQLValueString($_POST['tipo_inferior_ver_egp'], "text"),
                       GetSQLValueString($_POST['cb_solapatr_ver_egp'], "text"),
                       GetSQLValueString($_POST['cb_cinta_ver_egp'], "text"),
                       GetSQLValueString($_POST['cb_principal_ver_egp'], "text"),
                       GetSQLValueString($_POST['cb_inferior_ver_egp'], "text"),
                       GetSQLValueString($_POST['comienza_ver_egp'], "text"),
                       GetSQLValueString(isset($_POST['fecha_cad_ver_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observacion3_ver_egp'], "text"),
                       GetSQLValueString(isset($_POST['arte_sum_ver_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['ent_logo_ver_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orient_arte_ver_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['archivo1'], "text"),
                       GetSQLValueString($_POST['archivo2'], "text"),
                       GetSQLValueString($_POST['archivo3'], "text"),
                       GetSQLValueString($_POST['disenador_ver_egp'], "text"),
                       GetSQLValueString($_POST['telef_disenador_ver_egp'], "text"),
                       GetSQLValueString($_POST['observacion4_ver_egp'], "text"),
                       GetSQLValueString($_POST['unids_paq_ver_egp'], "text"),
                       GetSQLValueString($_POST['unids_caja_ver_egp'], "text"),
                       GetSQLValueString($_POST['marca_cajas_ver_egp'], "text"),
                       GetSQLValueString($_POST['lugar_entrega_ver_egp'], "text"),
                       GetSQLValueString($_POST['observacion5_ver_egp'], "text"),
                       GetSQLValueString($_POST['responsable_modificacion'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
                       GetSQLValueString($_POST['n_ver_egp'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "cotizacion_bolsa_nueva.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE cotizacion_nueva SET n_cotiz_cn=%s, n_ver_egp_cn=%s, cod_ref_cn=%s, tipo_bolsa_cn=%s, material_cn=%s, ancho_cn=%s, largo_cn=%s, solapa_cn=%s, bolsillo_guia_cn=%s, calibre_cn=%s, peso_millar_cn=%s, impresion_cn=%s, num_pos_cn=%s, barra_formato_cn=%s, adhesivo_cn=%s, cant_min_cn=%s, tiempo_entrega_cn=%s, incoterm_cn=%s, precio_venta_cn=%s, moneda_cn=%s, unidad_cn=%s, forma_pago_cn=%s, entrega_cn=%s, costo_cirel_cn=%s, estado_cn=%s WHERE n_cn=%s",
                       GetSQLValueString($_POST['n_cotiz_cn'], "int"),
                       GetSQLValueString($_POST['n_ver_egp_cn'], "int"),
                       GetSQLValueString($_POST['cod_ref_cn'], "text"),
                       GetSQLValueString($_POST['tipo_bolsa_cn'], "text"),
                       GetSQLValueString($_POST['material_cn'], "text"),
                       GetSQLValueString($_POST['ancho_cn'], "double"),
                       GetSQLValueString($_POST['largo_cn'], "double"),
                       GetSQLValueString($_POST['solapa_cn'], "double"),
                       GetSQLValueString($_POST['bolsillo_guia_cn'], "double"),
                       GetSQLValueString($_POST['calibre_cn'], "double"),
                       GetSQLValueString($_POST['peso_millar_cn'], "double"),
                       GetSQLValueString($_POST['impresion_cn'], "text"),
                       GetSQLValueString($_POST['num_pos_cn'], "text"),
                       GetSQLValueString($_POST['barra_formato_cn'], "text"),
                       GetSQLValueString($_POST['adhesivo_cn'], "text"),
                       GetSQLValueString($_POST['cant_min_cn'], "text"),
                       GetSQLValueString($_POST['tiempo_entrega_cn'], "text"),
                       GetSQLValueString($_POST['incoterm_cn'], "text"),
                       GetSQLValueString($_POST['precio_venta_cn'], "text"),
                       GetSQLValueString($_POST['moneda_cn'], "text"),
                       GetSQLValueString($_POST['unidad_cn'], "text"),
                       GetSQLValueString($_POST['forma_pago_cn'], "text"),
                       GetSQLValueString($_POST['entrega_cn'], "text"),
                       GetSQLValueString($_POST['costo_cirel_cn'], "text"),
                       GetSQLValueString($_POST['estado_cn'], "int"),
                       GetSQLValueString($_POST['n_cn'], "int"));

  mysql_select_db($database_conexion1, $conexion1); 
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "cotizacion_bolsa_edit.php?n_cotiz=" . $_POST['n_cotiz_cn'] . "&id_c_cotiz=" . $_POST['id_c_cotiz'] . "";
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

$colname_ver_ver_egp = "-1";
if (isset($_GET['ver_egp'])) {
  $colname_ver_ver_egp = (get_magic_quotes_gpc()) ? $_GET['ver_egp'] : addslashes($_GET['ver_egp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_ver_egp = sprintf("SELECT * FROM ver_egp WHERE n_ver_egp = %s", $colname_ver_ver_egp);
$ver_ver_egp = mysql_query($query_ver_ver_egp, $conexion1) or die(mysql_error());
$row_ver_ver_egp = mysql_fetch_assoc($ver_ver_egp);
$totalRows_ver_ver_egp = mysql_num_rows($ver_ver_egp);

$colname_ver_egps = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_ver_egps = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_egps = sprintf("SELECT * FROM ver_egp WHERE ver_egp.estado_ver_egp = '0' AND ver_egp.n_ver_egp NOT IN(SELECT cotizacion_nueva.n_ver_egp_cn FROM cotizacion_nueva WHERE n_cotiz_cn='%s') ORDER BY n_ver_egp DESC", $colname_ver_egps);
$ver_egps = mysql_query($query_ver_egps, $conexion1) or die(mysql_error());
$row_ver_egps = mysql_fetch_assoc($ver_egps);
$totalRows_ver_egps = mysql_num_rows($ver_egps);

$colname_cotizacion = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_cotizacion = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = sprintf("SELECT * FROM cotizacion WHERE n_cotiz = %s", $colname_cotizacion);
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

$colname_nueva = "-1";
if (isset($_GET['n_cn'])) {
  $colname_nueva = (get_magic_quotes_gpc()) ? $_GET['n_cn'] : addslashes($_GET['n_cn']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_nueva = sprintf("SELECT * FROM cotizacion_nueva WHERE n_cn = %s", $colname_nueva);
$nueva = mysql_query($query_nueva, $conexion1) or die(mysql_error());
$row_nueva = mysql_fetch_assoc($nueva);
$totalRows_nueva = mysql_num_rows($nueva);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body bgcolor="#F0F7FC">
<div align="center">
<table id="tabla1" align="center">
  <tr>  
    <td><div id="cabecera"></div>
        <div id="menuh">
          <ul>
		    <li><a href="cotizacion_menu.php">SEGUIMIENTO A COTIZACIONES</a></li>	            
            <li><a href="cotizacion_bolsa.php">LISTADO COTIZACIONES</a></li>		  		    
			<li><a href="cotizacion_bolsa_edit.php?n_cotiz=<?php echo $row_cotizacion['n_cotiz']; ?>&id_c_cotiz=<?php echo $row_cotizacion['id_c_cotiz']; ?>">COTIZACION ACTUAL</a></li>
			<li><a href="cotizacion_bolsa.php">DELETE ACTUAL</a></li>        				
			<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
          </ul>
        </div></td>
  </tr>
</table>
<table id="tabla1">
  <tr>
    <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1">
        <table align="center" id="tabla4">
          <tr>
            <td colspan="3" nowrap id="fuente1">Es obligatorio el N&deg; EGP para una referencia nueva
              <input name="n_cn" type="hidden" id="n_cn" value="<?php echo $_GET['n_cn']; ?>">
              <input name="n_cotiz" type="hidden" id="n_cotiz" value="<?php echo $row_cotizacion['n_cotiz']; ?>"></td>
            </tr>
          <tr>
            <td colspan="2" nowrap id="numeral">N&deg; <?php echo $row_nueva['n_ver_egp_cn']; ?> 
              <select name="ver_egp" id="ver_egp" onBlur="consultaver_egp2()">
                <option value="0" <?php if (!(strcmp(0, $_GET['n_ver_egp']))) {echo "selected=\"selected\"";} ?>>EGP</option>
<?php
do {  
?>
                <option value="<?php echo $row_ver_egps['n_ver_egp']?>"<?php if (!(strcmp($row_ver_egps['n_ver_egp'], $_GET['n_ver_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ver_egps['n_ver_egp']?></option>
                <?php
} while ($row_ver_egps = mysql_fetch_assoc($ver_egps));
  $rows = mysql_num_rows($ver_egps);
  if($rows > 0) {
      mysql_data_seek($ver_egps, 0);
	  $row_ver_egps = mysql_fetch_assoc($ver_egps);
  }
?>
                </select>
              <a href="cotizacion_bolsa_nueva_edit.php?n_cn=<?php echo $row_nueva['n_cn']; ?>&n_cotiz=<?php echo $row_nueva['n_cotiz_cn']; ?>&n_ver_egp=<?php echo $row_nueva['n_ver_egp_cn']; ?>"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0"></a></td>
            <td id="dato"><?php echo $row_ver_egp['fecha_ver_egp']; ?><input name="fecha_ver_egp" type="hidden" value="<?php echo $row_ver_egp['fecha_ver_egp']; ?>">
              <input name="hora_ver_egp" type="hidden" value="<?php echo $row_ver_egp['hora_ver_egp']; ?>">
              <input name="estado_ver_egp" type="hidden" value="<?php echo $row_ver_egp['estado_ver_egp']; ?>"><?php echo $row_ver_egp['hora_ver_egp']; ?></td></tr>
          <tr>
            <td colspan="3" id="datos"><input name="responsable_ver_egp" type="hidden" value="<?php echo $row_ver_egp['responsable_ver_egp']; ?>">
              <input name="codigo_usuario" type="hidden" value="<?php echo $row_ver_egp['codigo_ver_egp']; ?>">
              Responsable : <?php echo $row_ver_egp['responsable_ver_egp']; ?></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">Ancho</td>
            <td id="fuente1">Largo</td>
            </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="ancho_ver_egp" value="<?php echo $row_ver_egp['ancho_ver_egp']; ?>" size="10"></td>
            <td id="dato"><input type="text" name="largo_ver_egp" value="<?php echo $row_ver_egp['largo_ver_egp']; ?>" size="10"></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">Solapa</td>
            <td id="fuente1">Bolsillo</td>
            </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="solapa_ver_egp" value="<?php echo $row_ver_egp['solapa_ver_egp']; ?>" size="10"></td>
            <td id="dato"><input type="text" name="largo_cang_ver_egp" value="<?php echo $row_ver_egp['largo_cang_ver_egp']; ?>" size="10"></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">Calibre</td>
            <td id="fuente1">Tipo Extrusion </td>
            </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="calibre_ver_egp" value="<?php echo $row_ver_egp['calibre_ver_egp']; ?>" size="10"></td>
            <td id="dato"><select name="tipo_ext_ver_egp">
                <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_ext_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="Coextrusion"  <?php if (!(strcmp("Coextrusion", $row_ver_egp['tipo_ext_ver_egp']))) {echo "selected=\"selected\"";} ?>>Coextrusion</option>
                <option value="Monocapa"  <?php if (!(strcmp("Monocapa", $row_ver_egp['tipo_ext_ver_egp']))) {echo "selected=\"selected\"";} ?>>Monocapa</option>
              </select></td>
            </tr>

          <tr>
            <td colspan="2" id="fuente1">Pigmento Exterior</td>
            <td id="fuente1">Pigmento Interior </td>
            </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="pigm_ext_ver_egp" value="<?php echo $row_ver_egp['pigm_ext_ver_egp']; ?>" size="10"></td>
            <td id="dato"><input type="text" name="pigm_int_epg" value="<?php echo $row_ver_egp['pigm_int_epg']; ?>" size="10"></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">Adhesivo</td>
            <td id="fuente1">Tipo Bolsa </td>
            </tr>
          <tr>
            <td colspan="2"><select name="adhesivo_ver_egp">
                <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['adhesivo_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="Cinta seguridad"  <?php if (!(strcmp("Cinta seguridad", $row_ver_egp['adhesivo_ver_egp']))) {echo "selected=\"selected\"";} ?>>Cinta seguridad</option>
                <option value="HOT MELT"  <?php if (!(strcmp("HOT MELT", $row_ver_egp['adhesivo_ver_egp']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
                <option value="Cinta permanente"  <?php if (!(strcmp("Cinta permanente", $row_ver_egp['adhesivo_ver_egp']))) {echo "selected=\"selected\"";} ?>>Cinta permanente</option>
                <option value="Cinta resellable"  <?php if (!(strcmp("Cinta resellable", $row_ver_egp['adhesivo_ver_egp']))) {echo "selected=\"selected\"";} ?>>Cinta resellable</option>
              </select></td>
            <td><input type="text" name="tipo_bolsa_ver_egp" value="<?php echo $row_ver_egp['tipo_bolsa_ver_egp']; ?>" size="10"></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">Cantidad</td>
            <td id="fuente1">Tipo Sello </td>
            </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="cantidad_ver_egp" value="<?php echo $row_ver_egp['cantidad_ver_egp']; ?>" size="10"></td>
            <td id="dato"><select name="tipo_sello_ver_egp" id="tipo_sello_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_sello_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Plano" <?php if (!(strcmp("Plano", $row_ver_egp['tipo_sello_ver_egp']))) {echo "selected=\"selected\"";} ?>>Plano</option>
              <option value="Hilo" <?php if (!(strcmp("Hilo", $row_ver_egp['tipo_sello_ver_egp']))) {echo "selected=\"selected\"";} ?>>Hilo</option>
              <option value="Fondo" <?php if (!(strcmp("Fondo", $row_ver_egp['tipo_sello_ver_egp']))) {echo "selected=\"selected\"";} ?>>Fondo</option>
            </select>
              <input name="observacion1_ver_egp" type="hidden" value="<?php echo $row_ver_egp['observacion1_ver_egp']; ?>">
              <input name="observacion2_ver_egp" type="hidden" value="<?php echo $row_ver_egp['observacion2_ver_egp']; ?>"></td>
            </tr>

          <tr>
            <td colspan="2" id="fuente1">Color 1 </td>
            <td id="fuente1">Color 2 </td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><input name="color1_ver_egp" type="text" value="<?php echo $row_ver_egp['color1_ver_egp']; ?>" size="10">
              <input name="pantone1_ver_egp" type="hidden" value="<?php echo $row_ver_egp['pantone1_ver_egp']; ?>">
              <input name="ubicacion1_ver_egp" type="hidden" value="<?php echo $row_ver_egp['ubicacion1_ver_egp']; ?>"></td>
            <td id="dato"><input name="color2_ver_egp" type="text" value="<?php echo $row_ver_egp['color2_ver_egp']; ?>" size="10">
              <input name="pantone2_ver_egp" type="hidden" value="<?php echo $row_ver_egp['pantone2_ver_egp']; ?>">
              <input name="ubicacion2_ver_egp" type="hidden" value="<?php echo $row_ver_egp['ubicacion2_ver_egp']; ?>"></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Color 3 </td>
            <td id="fuente1">Color 4 </td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><input name="color3_ver_egp" type="text" value="<?php echo $row_ver_egp['color3_ver_egp']; ?>" size="10">
              <input type="hidden" name="pantone3_ver_egp" value="<?php echo $row_ver_egp['pantone3_ver_egp']; ?>" size="10">
              <input name="ubicacion3_ver_egp" type="hidden" value="<?php echo $row_ver_egp['ubicacion3_ver_egp']; ?>"></td>
            <td id="dato"><input name="color4_ver_egp" type="text" value="<?php echo $row_ver_egp['color4_ver_egp']; ?>" size="10">
              <input type="hidden" name="pantone4_ver_egp" value="<?php echo $row_ver_egp['pantone4_ver_egp']; ?>" size="10">
              <input type="hidden" name="ubicacion4_ver_egp" value="<?php echo $row_ver_egp['ubicacion4_ver_egp']; ?>" size="10"></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Color 5 </td>
            <td id="fuente1">Color 6 </td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><input name="color5_ver_egp" type="text" value="<?php echo $row_ver_egp['color5_ver_egp']; ?>" size="10">
              <input name="pantone5_ver_egp" type="hidden" value="<?php echo $row_ver_egp['pantone5_ver_egp']; ?>">
              <input name="ubicacion5_ver_egp" type="hidden" value="<?php echo $row_ver_egp['ubicacion5_ver_egp']; ?>"></td>
            <td id="dato"><input name="color6_ver_egp" type="text" value="<?php echo $row_ver_egp['color6_ver_egp']; ?>" size="10">
              <input name="pantone6_ver_egp" type="hidden" value="<?php echo $row_ver_egp['pantone6_ver_egp']; ?>">
              <input name="ubicacion6_ver_egp" type="hidden" value="<?php echo $row_ver_egp['ubicacion6_ver_egp']; ?>"></td>
          </tr>
          <tr>
            <td id="fuente1">Posici&oacute;n</td>
            <td id="fuente1">Numeraci&oacute;n</td>
            <td id="fuente1">Formato CB </td>
          </tr>
          <tr>
            <td id="subtitulo2">Solapa TR </td>
            <td id="subtitulo2"><select name="tipo_solapatr_ver_egp" id="tipo_solapatr_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_solapatr_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_solapatr_ver_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
              <option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_egp['tipo_solapatr_ver_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
            </select></td>
            <td id="subtitulo2"><select name="cb_solapatr_ver_egp" id="cb_solapatr_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_solapatr_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_solapatr_ver_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
            </select></td>
          </tr>
          <tr>
            <td id="subtitulo2">Cinta</td>
            <td id="subtitulo2"><select name="tipo_cinta_ver_egp" id="tipo_cinta_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_cinta_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_cinta_ver_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_egp['tipo_cinta_ver_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
            </select></td>
            <td id="subtitulo2"><select name="cb_cinta_ver_egp" id="cb_cinta_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_cinta_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_cinta_ver_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
            </select></td>
          </tr>
          <tr>
            <td id="subtitulo2">Principal</td>
            <td id="subtitulo2"><select name="tipo_principal_ver_egp" id="tipo_principal_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_principal_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_principal_ver_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_egp['tipo_principal_ver_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
            </select></td>
            <td id="subtitulo2"><select name="cb_principal_ver_egp" id="cb_principal_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_principal_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_principal_ver_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
            </select></td>
          </tr>
          <tr>
            <td id="subtitulo2">Inferior</td>
            <td id="subtitulo2"><select name="tipo_inferior_ver_egp" id="tipo_inferior_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_inferior_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_inferior_ver_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_egp['tipo_inferior_ver_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
            </select></td>
            <td id="subtitulo2"><select name="cb_inferior_ver_egp" id="cb_inferior_ver_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_inferior_ver_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_inferior_ver_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
            </select>
              <input name="comienza_ver_egp" type="hidden" value="<?php echo $row_ver_egp['comienza_ver_egp']; ?>"></td>
          </tr>
          
          <tr>
            <td colspan="2" id="chulito"><input type="checkbox" name="fecha_cad_ver_egp" value="1" <?php if (!(strcmp($row_ver_egp['fecha_cad_ver_egp'],1))) {echo "checked=\"checked\"";} ?>><input name="observacion3_ver_egp" type="hidden" value="<?php echo $row_ver_egp['observacion3_ver_egp']; ?>">Fecha Caducidad</td>
            <td id="chulito"><input type="checkbox" name="arte_sum_ver_egp" value="1" <?php if (!(strcmp($row_ver_egp['arte_sum_ver_egp'],1))) {echo "checked=\"checked\"";} ?>>
              Arte Suministrado </td>
            </tr>

          <tr>
            <td colspan="2" id="chulito"><input type="checkbox" name="ent_logo_ver_egp" value="1" <?php if (!(strcmp($row_ver_egp['ent_logo_ver_egp'],1))) {echo "checked=\"checked\"";} ?>>
              Entrega Logos </td>
            <td id="chulito"><input type="checkbox" name="orient_arte_ver_egp" value="1" <?php if (!(strcmp($row_ver_egp['orient_arte_ver_egp'],1))) {echo "checked=\"checked\"";} ?>>
              Orientaci&oacute;n en el Arte </td>
            </tr>

          <tr>
            <td colspan="3" id="dato">Archivos :
              <input name="archivo1" type="hidden" value="<?php echo $row_ver_egp['archivo1']; ?>">
              <a href="javascript:verFoto('ver_egpbolsa/<?php echo $row_ver_egp['archivo1'];?>','610','490')"><?php echo $row_ver_egp['archivo1']; ?></a> , 
              <input name="archivo2" type="hidden" value="<?php echo $row_ver_egp['archivo2']; ?>">
              <a href="javascript:verFoto('ver_egpbolsa/<?php echo $row_ver_egp['archivo2'];?>','610','490')"><?php echo $row_ver_egp['archivo2']; ?></a> , 
              <input name="archivo3" type="hidden" value="<?php echo $row_ver_egp['archivo3']; ?>">
              <a href="javascript:verFoto('ver_egpbolsa/<?php echo $row_ver_egp['archivo3'];?>','610','490')"><?php echo $row_ver_egp['archivo3']; ?></a></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1"><input name="disenador_ver_egp" type="hidden" value="<?php echo $row_ver_egp['disenador_ver_egp']; ?>">
              <input name="telef_disenador_ver_egp" type="hidden" value="<?php echo $row_ver_egp['telef_disenador_ver_egp']; ?>">
              <input name="observacion4_ver_egp" type="hidden" value="<?php echo $row_ver_egp['observacion4_ver_egp']; ?>">
              Unids x paquete </td>
            <td id="fuente1">Unids x Caja </td>
            </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="unids_paq_ver_egp" value="<?php echo $row_ver_egp['unids_paq_ver_egp']; ?>" size="10"></td>
            <td id="dato"><input type="text" name="unids_caja_ver_egp" value="<?php echo $row_ver_egp['unids_caja_ver_egp']; ?>" size="10"></td>
            </tr>

          <tr>
            <td colspan="2" id="fuente1">Marca de Cajas </td>
            <td id="fuente1">Lugar Entrega </td>
            </tr>
          <tr>
            <td colspan="2"><input type="text" name="marca_cajas_ver_egp" value="<?php echo $row_ver_egp['marca_cajas_ver_egp']; ?>" size="10"></td>
            <td><input type="text" name="lugar_entrega_ver_egp" value="<?php echo $row_ver_egp['lugar_entrega_ver_egp']; ?>" size="10">
              <input name="observacion5_ver_egp" type="hidden" value="<?php echo $row_ver_egp['observacion5_ver_egp']; ?>"></td>
            </tr>

          <tr>
            <td colspan="3" id="fuente1">Modificación</td>
            </tr>
          <tr>
            <td colspan="2" id="dato">- <?php echo $row_ver_egp['responsable_modificacion']; ?> - <input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>"></td>
            <td id="dato"><?php echo $row_ver_egp['fecha_modificacion']; ?><input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>">
              <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a") ?>"> - <?php echo $row_ver_egp['hora_modificacion']; ?></td>
            </tr>

          <tr>
            <td colspan="3" id="boton"><input type="submit" value="Actualizar EGP"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="n_ver_egp" value="<?php echo $row_ver_egp['n_ver_egp']; ?>">
      </form></td>
    <td><form method="post" name="form2" action="<?php echo $editFormAction; ?>">
        <table id="tabla4">
          <tr>
            <td colspan="2" id="fuente1">La Referencia debe ser UNICA </td>
          </tr>
          <tr>
            <td colspan="2" id="logo"><input name="registro" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>">
              <input name="fecha_cotiz" type="hidden" value="<?php echo $row_cotizacion['fecha_cotiz']; ?>">
              <input name="hora_cotiz" type="hidden" id="hora_cotiz" value="<?php echo $row_cotizacion['hora_cotiz']; ?>">
              <input name="id_c_cotiz" type="hidden" id="id_c_cotiz" value="<?php echo $row_cotizacion['id_c_cotiz']; ?>">
              <input name="n_cotiz_cn" type="hidden" value="<?php echo $_GET['n_cotiz_cn']; ?>">
              <input name="n_ver_egp_cn" type="text" value="<?php echo $row_ver_egp['n_ver_egp']; ?>" size="5">
              <input name="n_ver_egp_cn1" type="text" id="n_ver_egp_cn1" value="<?php echo $row_nueva['n_ver_egp_cn'];  ?>" size="5">
              REFERENCIA</td>
          </tr>
          <tr>
            <td colspan="2" id="consulta"><div id="resultado"></div>
              <input type="text" name="cod_ref_cn" value="<?php echo $row_nueva['cod_ref_cn']; ?>" size="30" onBlur="if (form2.cod_ref_cn.value) { DatosGestiones('1','cod_ref_cn',form2.cod_ref_cn.value); } else { alert('Debe digitar la REFERENCIA para validar su existencia en la BD'); }" ></td>
          </tr>
          <tr>
            <td colspan="2" id="consulta"><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" onClick="window.history.go()"></td>
          </tr>
          <tr>
            <td id="fuente1">Ancho</td>
            <td id="fuente1">Largo</td>
          </tr>
          <tr>
            <td id="dato"><input type="text" name="ancho_cn" value="<?php echo $row_nueva['ancho_cn']; ?>" size="10" onBlur="calcular()"></td>
            <td id="dato"><input type="text" name="largo_cn" value="<?php echo $row_nueva['largo_cn']; ?>" size="10" onBlur="calcular()"></td>
          </tr>
          <tr>
            <td id="fuente1">Solapa</td>
            <td id="fuente1">Bolsillo</td>
          </tr>
          <tr>
            <td id="dato"><input type="text" name="solapa_cn" value="<?php echo $row_nueva['solapa_cn']; ?>" size="10" onBlur="calcular()"></td>
            <td id="dato"><input type="text" name="bolsillo_guia_cn" value="<?php echo $row_nueva['bolsillo_guia_cn']; ?>" size="10"></td>
          </tr>
          <tr>
            <td id="fuente1">Calibre</td>
            <td id="fuente1">Peso Millar </td>
          </tr>
          <tr>
            <td id="dato"><input type="text" name="calibre_cn" value="<?php echo $row_nueva['calibre_cn']; ?>" size="10" onBlur="calcular()"></td>
            <td id="dato"><input type="text" name="peso_millar_cn" value="<?php echo $row_nueva['peso_millar_cn']; ?>" size="10"></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Tipo de Bolsa </td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><select name="tipo_bolsa_cn">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Seguridad" <?php if (!(strcmp("Seguridad", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>Seguridad</option>
                <option value="Currier" <?php if (!(strcmp("Currier", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>Currier</option>
            </select></td>
          </tr>
          
          <tr>
            <td colspan="2" id="fuente1">Codigo Barras &amp; Formato </td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><select name="barra_formato_cn" id="barra_formato_cn">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['barra_formato_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="EAN 128" <?php if (!(strcmp("EAN 128", $row_nueva['barra_formato_cn']))) {echo "selected=\"selected\"";} ?>>EAN 128</option>
            </select></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Material</td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><select name="material_cn" id="material_cn">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Ldpe coestruido pigmentado"  <?php if (!(strcmp("Ldpe coestruido pigmentado", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido pigmentado</option>
                <option value="Ldpe coestruido sin pigmentos"  <?php if (!(strcmp("Ldpe coestruido sin pigmentos", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido sin pigmentos</option>
                <option value="Ldpe monocapa sin pigmentos"  <?php if (!(strcmp("Ldpe monocapa sin pigmentos", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa sin pigmentos</option>
                <option value="Ldpe monocapa pigmentado"  <?php if (!(strcmp("Ldpe monocapa pigmentado", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa pigmentado</option>
            </select></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Tipo de Adhesivo </td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><select name="adhesivo_cn" id="adhesivo_cn">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Cinta de Seguridad" <?php if (!(strcmp("Cinta de Seguridad", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>Cinta de Seguridad</option>
              <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
              <option value="Cinta Permanente" <?php if (!(strcmp("Cinta Permanente", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>Cinta Permanente</option>
                <option value="Cinta Resellable" <?php if (!(strcmp("Cinta Resellable", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>Cinta Resellable</option>
            </select></td>
          </tr>
          
          <tr>
            <td colspan="2" id="fuente1">Impresi&oacute;n</td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="impresion_cn" value="<?php echo $row_nueva['impresion_cn']; ?>" size="30"></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Numeraci&oacute;n &amp; Posiciones </td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="num_pos_cn" value="<?php echo $row_nueva['num_pos_cn']; ?>" size="30"></td>
          </tr>
          <tr>
            <td id="fuente1">Cantidad Minima </td>
            <td id="fuente1">Tiempo de Entrega </td>
          </tr>
          <tr>
            <td id="dato"><input type="text" name="cant_min_cn" value="<?php echo $row_nueva['cant_min_cn']; ?>" size="10"></td>
            <td id="dato"><input type="text" name="tiempo_entrega_cn" value="<?php echo $row_nueva['tiempo_entrega_cn']; ?>" size="10"></td>
          </tr>
          <tr>
            <td id="fuente1">INCOTERM</td>
            <td id="fuente1">Precio de Venta </td>
          </tr>
          <tr>
            <td id="dato"><select name="incoterm_cn" id="incoterm_cn">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="CFR" <?php if (!(strcmp("CFR", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>CFR</option>
                <option value="CIF" <?php if (!(strcmp("CIF", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>CIF</option>
                <option value="CIP" <?php if (!(strcmp("CIP", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>CIP</option>
                <option value="CPT" <?php if (!(strcmp("CPT", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>CPT</option>
                <option value="DAF" <?php if (!(strcmp("DAF", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>DAF</option>
                <option value="DDP" <?php if (!(strcmp("DDP", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>DDP</option>
                <option value="DDU" <?php if (!(strcmp("DDU", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>DDU</option>
                <option value="DEQ" <?php if (!(strcmp("DEQ", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>DEQ</option>
                <option value="DES" <?php if (!(strcmp("DES", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>DES</option>
                <option value="EXW" <?php if (!(strcmp("EXW", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>EXW</option>
                <option value="FAS" <?php if (!(strcmp("FAS", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>FAS</option>
                <option value="FCA" <?php if (!(strcmp("FCA", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>FCA</option>
                <option value="FOB" <?php if (!(strcmp("FOB", $row_nueva['incoterm_cn']))) {echo "selected=\"selected\"";} ?>>FOB</option>
            </select></td>
            <td id="dato"><input type="text" name="precio_venta_cn" value="<?php echo $row_nueva['precio_venta_cn']; ?>" size="10"></td>
          </tr>
          <tr>
            <td id="fuente1">Moneda</td>
            <td id="fuente1">Unidad</td>
          </tr>
          <tr>
            <td id="dato"><select name="moneda_cn" id="moneda_cn">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['moneda_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="COL $" <?php if (!(strcmp("COL $", $row_nueva['moneda_cn']))) {echo "selected=\"selected\"";} ?>>COL $</option>
              <option value="USD $" <?php if (!(strcmp("USD $", $row_nueva['moneda_cn']))) {echo "selected=\"selected\"";} ?>>USD $</option>
            </select></td>
            <td id="dato"><select name="unidad_cn" id="unidad_cn">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['unidad_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Unitario" <?php if (!(strcmp("Unitario", $row_nueva['unidad_cn']))) {echo "selected=\"selected\"";} ?>>Unitario</option>
              <option value="Millar" <?php if (!(strcmp("Millar", $row_nueva['unidad_cn']))) {echo "selected=\"selected\"";} ?>>Millar</option>
            </select></td>
          </tr>
          
          <tr>
            <td height="19" colspan="2" id="fuente1">Forma de Pago</td>
          </tr>
          <tr>
            <td colspan="2" id="dato"><input type="text" name="forma_pago_cn" value="<?php echo $row_nueva['forma_pago_cn']; ?>" size="30"></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Entrega</td>
          </tr>
          <tr>
            <td colspan="2"><input type="text" name="entrega_cn" value="<?php echo $row_nueva['entrega_cn']; ?>" size="30"></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Costo del Cirel </td>
          </tr>
          <tr>
            <td colspan="2"><input type="text" name="costo_cirel_cn" value="<?php echo $row_nueva['costo_cirel_cn']; ?>" size="30"></td>
          </tr>
          <tr>
            <td colspan="2" id="consulta">Inicialmente una referencia nueva tiene un estado pendiente, en espera de la aceptacion del cliente.
              <input name="estado_cn" type="hidden" id="estado_cn" value="<?php echo $row_nueva['estado_cn']; ?>"></td>
          </tr>
          <tr>
            <td colspan="2" id="boton"><input type="submit" value="Actualizar Referencia"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form2">
        <input type="hidden" name="n_cn" value="<?php echo $row_nueva['n_cn']; ?>">
      </form></td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ver_ver_egp);

mysql_free_result($ver_egps);

mysql_free_result($cotizacion);

mysql_free_result($nueva);
?>