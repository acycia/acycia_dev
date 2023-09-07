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
  $updateSQL = sprintf("UPDATE egp SET responsable_egp=%s, codigo_usuario=%s, fecha_egp=%s, hora_egp=%s, estado_egp=%s, ancho_egp=%s, largo_egp=%s, solapa_egp=%s, largo_cang_egp=%s, calibre_egp=%s, tipo_ext_egp=%s, pigm_ext_egp=%s, pigm_int_epg=%s, adhesivo_egp=%s, tipo_bolsa_egp=%s, cantidad_egp=%s, tipo_sello_egp=%s, observacion1_egp=%s, color1_egp=%s, pantone1_egp=%s, ubicacion1_egp=%s, color2_egp=%s, pantone2_egp=%s, ubicacion2_egp=%s, color3_egp=%s, pantone3_egp=%s, ubicacion3_egp=%s, color4_egp=%s, pantone4_egp=%s, ubicacion4_egp=%s, color5_egp=%s, pantone5_egp=%s, ubicacion5_egp=%s, color6_egp=%s, pantone6_egp=%s, ubicacion6_egp=%s, observacion2_egp=%s, tipo_solapatr_egp=%s, tipo_cinta_egp=%s, tipo_principal_egp=%s, tipo_inferior_egp=%s, cb_solapatr_egp=%s, cb_cinta_egp=%s, cb_principal_egp=%s, cb_inferior_egp=%s, comienza_egp=%s, fecha_cad_egp=%s, observacion3_egp=%s, arte_sum_egp=%s, ent_logo_egp=%s, orient_arte_egp=%s, archivo1=%s, archivo2=%s, archivo3=%s, disenador_egp=%s, telef_disenador_egp=%s, observacion4_egp=%s, unids_paq_egp=%s, unids_caja_egp=%s, marca_cajas_egp=%s, lugar_entrega_egp=%s, observacion5_egp=%s, responsable_modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s, vendedor=%s WHERE n_egp=%s",
                       GetSQLValueString($_POST['responsable_egp'], "text"),
                       GetSQLValueString($_POST['codigo_usuario'], "text"),
                       GetSQLValueString($_POST['fecha_egp'], "date"),
                       GetSQLValueString($_POST['hora_egp'], "text"),
                       GetSQLValueString($_POST['estado_egp'], "text"),
                       GetSQLValueString($_POST['ancho_egp'], "double"),
                       GetSQLValueString($_POST['largo_egp'], "double"),
                       GetSQLValueString($_POST['solapa_egp'], "double"),
                       GetSQLValueString($_POST['largo_cang_egp'], "double"),
                       GetSQLValueString($_POST['calibre_egp'], "double"),
                       GetSQLValueString($_POST['tipo_ext_egp'], "text"),
                       GetSQLValueString($_POST['pigm_ext_egp'], "text"),
                       GetSQLValueString($_POST['pigm_int_epg'], "text"),
                       GetSQLValueString($_POST['adhesivo_egp'], "text"),
                       GetSQLValueString($_POST['tipo_bolsa_egp'], "text"),
                       GetSQLValueString($_POST['cantidad_egp'], "text"),
                       GetSQLValueString($_POST['tipo_sello_egp'], "text"),
                       GetSQLValueString($_POST['observacion1_egp'], "text"),
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
                       GetSQLValueString($_POST['observacion2_egp'], "text"),
                       GetSQLValueString($_POST['tipo_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['tipo_cinta_egp'], "text"),
                       GetSQLValueString($_POST['tipo_principal_egp'], "text"),
                       GetSQLValueString($_POST['tipo_inferior_egp'], "text"),
                       GetSQLValueString($_POST['cb_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['cb_cinta_egp'], "text"),
                       GetSQLValueString($_POST['cb_principal_egp'], "text"),
                       GetSQLValueString($_POST['cb_inferior_egp'], "text"),
                       GetSQLValueString($_POST['comienza_egp'], "text"),
                       GetSQLValueString($_POST['fecha_cad_egp'], "int"),
                       GetSQLValueString($_POST['observacion3_egp'], "text"),
                       GetSQLValueString($_POST['arte_sum_egp'], "int"),
                       GetSQLValueString($_POST['ent_logo_egp'], "int"),
                       GetSQLValueString($_POST['orient_arte_egp'], "int"),
                       GetSQLValueString($_POST['archivo1'], "text"),
                       GetSQLValueString($_POST['archivo2'], "text"),
                       GetSQLValueString($_POST['archivo3'], "text"),
                       GetSQLValueString($_POST['disenador_egp'], "text"),
                       GetSQLValueString($_POST['telef_disenador_egp'], "text"),
                       GetSQLValueString($_POST['observacion4_egp'], "text"),
                       GetSQLValueString($_POST['unids_paq_egp'], "text"),
                       GetSQLValueString($_POST['unids_caja_egp'], "text"),
                       GetSQLValueString($_POST['marca_cajas_egp'], "text"),
                       GetSQLValueString($_POST['lugar_entrega_egp'], "text"),
                       GetSQLValueString($_POST['observacion5_egp'], "text"),
                       GetSQLValueString($_POST['responsable_modificacion'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
                       GetSQLValueString($_POST['vendedor'], "int"),
                       GetSQLValueString($_POST['n_egp'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "cotizacion_bolsa_nueva_edit.php?n_cn=" . $_POST['n_cn'] . "&n_cotiz=" . $_POST['n_cotiz'] . "&n_egp=" . $_POST['n_egp'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
$fecha_cotiz=$_POST['fecha_cotiz'];
$fecha= date("Y-m-d");
$hora= date("g:i a");
$n_cotiz=$_POST['n_cotiz_cn'];
$registro=$_POST['registro'];
$estado_cn=$_POST['estado_cn'];
$n_egp_cn=$_POST['n_egp_cn'];
$n_egp_cn2=$_POST['n_egp_cn2'];
if($fecha != $fecha_cotiz)
{
$sql2="UPDATE cotizacion SET fecha_modif='$fecha', hora_modif='$hora', responsable_modif='$registro' WHERE n_cotiz='$n_cotiz'";
}
if($n_egp_cn != $n_egp_cn2)
{
$sql3="UPDATE egp SET egp.estado_egp='0' WHERE n_egp='$n_egp_cn2'";
}
if($estado_cn == '1')
{
$sql4="UPDATE egp SET egp.estado_egp='1' WHERE n_egp='$n_egp_cn'";
}
if($estado_cn == '0')
{
$sql5="UPDATE egp SET egp.estado_egp='0' WHERE n_egp='$n_egp_cn'";
}			  
  $updateSQL = sprintf("UPDATE cotizacion_nueva SET n_cotiz_cn=%s, n_egp_cn=%s, cod_ref_cn=%s, tipo_bolsa_cn=%s, material_cn=%s, ancho_cn=%s, largo_cn=%s, solapa_cn=%s, bolsillo_guia_cn=%s, calibre_cn=%s, peso_millar_cn=%s, impresion_cn=%s, num_pos_cn=%s, barra_formato_cn=%s, adhesivo_cn=%s, cant_min_cn=%s, tiempo_entrega_cn=%s, incoterm_cn=%s, precio_venta_cn=%s, moneda_cn=%s, unidad_cn=%s, forma_pago_cn=%s, entrega_cn=%s, costo_cirel_cn=%s, estado_cn=%s, vendedor=%s, comision=%s WHERE n_cn=%s",
                       GetSQLValueString($_POST['n_cotiz_cn'], "int"),
                       GetSQLValueString($_POST['n_egp_cn'], "int"),
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
                       GetSQLValueString($_POST['vendedor'], "int"),
                       GetSQLValueString($_POST['comision'], "text"),
                       GetSQLValueString($_POST['n_cn'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $result2=mysql_query($sql2);
  $result3=mysql_query($sql3);
  $result4=mysql_query($sql4);
  $result5=mysql_query($sql5);
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

$colname_cotizacion = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_cotizacion = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = sprintf("SELECT * FROM cotizacion WHERE n_cotiz = %s", $colname_cotizacion);
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

$colname_egp = "-1";
if (isset($_GET['n_egp'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['n_egp'] : addslashes($_GET['n_egp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egp = sprintf("SELECT * FROM egp WHERE n_egp = %s", $colname_egp);
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);

$colname_egps = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_egps = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egps = sprintf("SELECT * FROM egp WHERE egp.estado_egp = '0' AND egp.n_egp NOT IN(SELECT cotizacion_nueva.n_egp_cn FROM cotizacion_nueva WHERE n_cotiz_cn='%s') ORDER BY n_egp DESC", $colname_egps);
$egps = mysql_query($query_egps, $conexion1) or die(mysql_error());
$row_egps = mysql_fetch_assoc($egps);
$totalRows_egps = mysql_num_rows($egps);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);

$colname_nueva = "-1";
if (isset($_GET['n_cn'])) {
  $colname_nueva = (get_magic_quotes_gpc()) ? $_GET['n_cn'] : addslashes($_GET['n_cn']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_nueva = sprintf("SELECT * FROM cotizacion_nueva WHERE n_cn = %s", $colname_nueva);
$nueva = mysql_query($query_nueva, $conexion1) or die(mysql_error());
$row_nueva = mysql_fetch_assoc($nueva);
$totalRows_nueva = mysql_num_rows($nueva);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
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
  <li><a href="comercial.php">GESTION COMERCIAL</a></li>
  </ul>  
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
<table id="tabla2">
<tr id="tr1">
  <td height="20" id="titulo3">COTIZACION N° <?php echo $row_cotizacion['n_cotiz']; ?></td>
  <td height="20" id="titulo3"><a href="cotizacion_bolsa_edit.php?n_cotiz=<?php echo $row_cotizacion['n_cotiz']; ?>&id_c_cotiz=<?php echo $row_cotizacion['id_c_cotiz']; ?>"><img src="images/menos.gif" style="cursor:hand;" border="0" alt="COTIZACION ACTUAL" /></a><a href="javascript:eliminar1('n_cn',<?php echo $row_nueva['n_cn']; ?>,'cotizacion_bolsa_nueva_edit.php')"><img src="images/por.gif" style="cursor:hand;" border="0" alt="ELIMINAR ITEM" /></a><a href="cotizacion_bolsa.php"><img src="images/cat.gif" alt="COTIZACIONES" border="0" style="cursor:hand;"/></a><a href="cotizacion_menu.php"><img src="images/opciones.gif" alt="MENU COTIZACION" border="0" style="cursor:hand;"/></a><a href="egp_bolsa.php"><img src="images/a.gif" alt="EGP'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="egp_bolsa_obsoletos.php"><img src="images/i.gif" alt="EGP'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="egp_bolsa.php"><img src="images/e.gif" style="cursor:hand;" alt="EGP'S BOLSA" border="0"/></a></td>
</tr>
<tr><td id="detalle2">
<div align="center">
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
      <table id="tabla6">
        <tr>
          <td colspan="3" nowrap="nowrap" id="fuente2">Si cambia el N&deg; EGP debe Actualizar Referencia
            <input name="n_cn" type="hidden" id="n_cn" value="<?php echo $_GET['n_cn']; ?>" />
            <input name="n_cotiz" type="hidden" id="n_cotiz" value="<?php echo $_GET['n_cotiz']; ?>" /></td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap" id="dato2"> EGP N° <input name="n_egp_cn1" type="hidden" id="n_egp_cn1" value="<?php echo $row_nueva['n_egp_cn']; ?>" />
            <?php echo $row_nueva['n_egp_cn']; ?>
            <select name="n_egp1" id="n_egp1" onblur="if(form1.n_egp1.value) { consultaegp2(); } else{ consultaegp3(); }">
              <option value="" <?php if (!(strcmp("", $_GET['n_egp']))) {echo "selected=\"selected\"";} ?>>Cambiar</option>
              <?php
do {  
?>
              <option value="<?php echo $row_egps['n_egp']?>"<?php if (!(strcmp($row_egps['n_egp'], $_GET['n_egp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_egps['n_egp']?></option>
              <?php
} while ($row_egps = mysql_fetch_assoc($egps));
  $rows = mysql_num_rows($egps);
  if($rows > 0) {
      mysql_data_seek($egps, 0);
	  $row_egps = mysql_fetch_assoc($egps);
  }
?>
            </select></td>
          <td id="dato2"><input name="fecha_egp" type="hidden" value="<?php echo $row_egp['fecha_egp']; ?>" />
            <input name="hora_egp" type="hidden" value="<?php echo $row_egp['hora_egp']; ?>" />
            <?php echo $row_egp['fecha_egp']; ?><?php echo $row_egp['hora_egp']; ?></td>
        </tr>
        <tr>
          <td colspan="3" id="detalle2"><input name="responsable_egp" type="hidden" value="<?php echo $row_egp['responsable_egp']; ?>" />
            <input name="codigo_usuario" type="hidden" value="<?php echo $row_egp['codigo_egp']; ?>" />
            Registrado por: <?php echo $row_egp['responsable_egp']; ?>
            <input name="estado_egp" type="hidden" value="<?php echo $row_egp['estado_egp']; ?>" /></td>
          </tr>
        <tr>
          <td colspan="2" id="fuente2">Ancho</td>
          <td id="fuente2">Largo</td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="ancho_egp" value="<?php echo $row_egp['ancho_egp']; ?>" size="10" /></td>
          <td id="dato2"><input type="text" name="largo_egp" value="<?php echo $row_egp['largo_egp']; ?>" size="10" /></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Solapa</td>
          <td id="fuente2">Bolsillo</td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="solapa_egp" value="<?php echo $row_egp['solapa_egp']; ?>" size="10" /></td>
          <td id="dato2"><input type="text" name="largo_cang_egp" value="<?php echo $row_egp['largo_cang_egp']; ?>" size="10" /></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Calibre</td>
          <td id="fuente2">Tipo Extrusion </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="calibre_egp" value="<?php echo $row_egp['calibre_egp']; ?>" size="10" /></td>
          <td id="dato2"><select name="tipo_ext_egp">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Coextrusion"  <?php if (!(strcmp("Coextrusion", $row_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>Coextrusion</option>
            <option value="Monocapa"  <?php if (!(strcmp("Monocapa", $row_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>Monocapa</option>
          </select></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Pigmento Exterior</td>
          <td id="fuente2">Pigmento Interior</td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="pigm_ext_egp" value="<?php echo $row_egp['pigm_ext_egp']; ?>" size="10" /></td>
          <td id="dato2"><input type="text" name="pigm_int_epg" value="<?php echo $row_egp['pigm_int_epg']; ?>" size="10" /></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Adhesivo</td>
          <td id="fuente2">Tipo Bolsa </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><select name="adhesivo_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Cinta seguridad"  <?php if (!(strcmp("Cinta seguridad", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta seguridad</option>
              <option value="HOT MELT"  <?php if (!(strcmp("HOT MELT", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
              <option value="Cinta permanente"  <?php if (!(strcmp("Cinta permanente", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta permanente</option>
              <option value="Cinta resellable"  <?php if (!(strcmp("Cinta resellable", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta resellable</option>
                    </select></td>
          <td id="dato2"><input type="text" name="tipo_bolsa_egp" value="<?php echo $row_egp['tipo_bolsa_egp']; ?>" size="10" /></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Cantidad</td>
          <td id="fuente2">Tipo Sello </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="cantidad_egp" value="<?php echo $row_egp['cantidad_egp']; ?>" size="10" /></td>
          <td id="dato2"><select name="tipo_sello_egp" id="tipo_sello_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Plano" <?php if (!(strcmp("Plano", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Plano</option>
              <option value="Hilo" <?php if (!(strcmp("Hilo", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Hilo</option>
              <option value="Fondo" <?php if (!(strcmp("Fondo", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Fondo</option>
            </select>
              <input name="observacion1_egp" type="hidden" value="<?php echo $row_egp['observacion1_egp']; ?>" />
              <input name="observacion2_egp" type="hidden" value="<?php echo $row_egp['observacion2_egp']; ?>" /></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Color 1 </td>
          <td id="fuente2">Color 2 </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input name="color1_egp" type="text" value="<?php echo $row_egp['color1_egp']; ?>" size="10" />
              <input name="pantone1_egp" type="hidden" value="<?php echo $row_egp['pantone1_egp']; ?>" />
              <input name="ubicacion1_egp" type="hidden" value="<?php echo $row_egp['ubicacion1_egp']; ?>" /></td>
          <td id="dato2"><input name="color2_egp" type="text" value="<?php echo $row_egp['color2_egp']; ?>" size="10" />
              <input name="pantone2_egp" type="hidden" value="<?php echo $row_egp['pantone2_egp']; ?>" />
              <input name="ubicacion2_egp" type="hidden" value="<?php echo $row_egp['ubicacion2_egp']; ?>" /></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Color 3 </td>
          <td id="fuente2">Color 4 </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input name="color3_egp" type="text" value="<?php echo $row_egp['color3_egp']; ?>" size="10" />
              <input type="hidden" name="pantone3_egp" value="<?php echo $row_egp['pantone3_egp']; ?>" size="10" />
              <input name="ubicacion3_egp" type="hidden" value="<?php echo $row_egp['ubicacion3_egp']; ?>" /></td>
          <td id="dato2"><input name="color4_egp" type="text" value="<?php echo $row_egp['color4_egp']; ?>" size="10" />
              <input type="hidden" name="pantone4_egp" value="<?php echo $row_egp['pantone4_egp']; ?>" size="10" />
              <input type="hidden" name="ubicacion4_egp" value="<?php echo $row_egp['ubicacion4_egp']; ?>" size="10" /></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Color 5 </td>
          <td id="fuente2">Color 6 </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input name="color5_egp" type="text" value="<?php echo $row_egp['color5_egp']; ?>" size="10" />
              <input name="pantone5_egp" type="hidden" value="<?php echo $row_egp['pantone5_egp']; ?>" />
              <input name="ubicacion5_egp" type="hidden" value="<?php echo $row_egp['ubicacion5_egp']; ?>" /></td>
          <td id="dato2"><input name="color6_egp" type="text" value="<?php echo $row_egp['color6_egp']; ?>" size="10" />
              <input name="pantone6_egp" type="hidden" value="<?php echo $row_egp['pantone6_egp']; ?>" />
              <input name="ubicacion6_egp" type="hidden" value="<?php echo $row_egp['ubicacion6_egp']; ?>" /></td>
        </tr>        
        <tr id="tr2">
          <td id="titulo4">Posici&oacute;n</td>
          <td id="titulo4">Numeraci&oacute;n</td>
          <td id="titulo4">Formato CB </td>
        </tr>
        <tr>
          <td id="detalle2">Solapa TR </td>
          <td id="detalle2"><select name="tipo_solapatr_egp" id="tipo_solapatr_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Normal" <?php if (!(strcmp("Normal", $row_egp['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
              <option value="CCTV" <?php if (!(strcmp("CCTV", $row_egp['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
          </select></td>
          <td id="detalle2"><select name="cb_solapatr_egp" id="cb_solapatr_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="EAN128" <?php if (!(strcmp("EAN128", $row_egp['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          </select></td>
        </tr>
        <tr>
          <td id="detalle2">Cinta</td>
          <td id="detalle2"><select name="tipo_cinta_egp" id="tipo_cinta_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Normal" <?php if (!(strcmp("Normal", $row_egp['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
              <option value="CCTV" <?php if (!(strcmp("CCTV", $row_egp['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
          </select></td>
          <td id="detalle2"><select name="cb_cinta_egp" id="cb_cinta_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="EAN128" <?php if (!(strcmp("EAN128", $row_egp['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          </select></td>
        </tr>
        <tr>
          <td id="detalle2">Principal</td>
          <td id="detalle2"><select name="tipo_principal_egp" id="tipo_principal_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Normal" <?php if (!(strcmp("Normal", $row_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
              <option value="CCTV" <?php if (!(strcmp("CCTV", $row_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
          </select></td>
          <td id="detalle2"><select name="cb_principal_egp" id="cb_principal_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="EAN128" <?php if (!(strcmp("EAN128", $row_egp['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          </select></td>
        </tr>
        <tr>
          <td id="detalle2">Inferior</td>
          <td id="detalle2"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Normal" <?php if (!(strcmp("Normal", $row_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
              <option value="CCTV" <?php if (!(strcmp("CCTV", $row_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
          </select></td>
          <td id="detalle2"><select name="cb_inferior_egp" id="cb_inferior_egp">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="EAN128" <?php if (!(strcmp("EAN128", $row_egp['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
            </select>
              <input name="comienza_egp" type="hidden" value="<?php echo $row_egp['comienza_egp']; ?>" /></td>
        </tr>        
        <tr>
          <td colspan="2" id="detalle1"><input type="checkbox" name="fecha_cad_egp" value="1" <?php if (!(strcmp($row_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> />
              <input name="observacion3_egp" type="hidden" value="<?php echo $row_egp['observacion3_egp']; ?>" />
            Fecha Caducidad</td>
          <td id="detalle1"><input type="checkbox" name="arte_sum_egp" value="1" <?php if (!(strcmp($row_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> />
            Arte Suministrado </td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1"><input type="checkbox" name="ent_logo_egp" value="1" <?php if (!(strcmp($row_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> />
            Entrega Logos </td>
          <td id="detalle1"><input type="checkbox" name="orient_arte_egp" value="1" <?php if (!(strcmp($row_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> />
            Orientaci&oacute;n en el Arte </td>
        </tr>
        <tr>
          <td colspan="3" id="dato2">Archivos :
            <input name="archivo1" type="hidden" value="<?php echo $row_egp['archivo1']; ?>" />
              <a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo1'];?>','610','490')"><?php echo $row_egp['archivo1']; ?></a> ,
            <input name="archivo2" type="hidden" value="<?php echo $row_egp['archivo2']; ?>" />
              <a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo2'];?>','610','490')"><?php echo $row_egp['archivo2']; ?></a> ,
            <input name="archivo3" type="hidden" value="<?php echo $row_egp['archivo3']; ?>" />
            <a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo3'];?>','610','490')"><?php echo $row_egp['archivo3']; ?></a></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2"><input name="disenador_egp" type="hidden" value="<?php echo $row_egp['disenador_egp']; ?>" />
              <input name="telef_disenador_egp" type="hidden" value="<?php echo $row_egp['telef_disenador_egp']; ?>" />
              <input name="observacion4_egp" type="hidden" value="<?php echo $row_egp['observacion4_egp']; ?>" />
            Unids x paquete </td>
          <td id="fuente2">Unids x Caja </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="unids_paq_egp" value="<?php echo $row_egp['unids_paq_egp']; ?>" size="10" /></td>
          <td id="dato2"><input type="text" name="unids_caja_egp" value="<?php echo $row_egp['unids_caja_egp']; ?>" size="10" /></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Marca de Cajas </td>
          <td id="fuente2">Lugar Entrega </td>
        </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="marca_cajas_egp" value="<?php echo $row_egp['marca_cajas_egp']; ?>" size="10" /></td>
          <td id="dato2"><input type="text" name="lugar_entrega_egp" value="<?php echo $row_egp['lugar_entrega_egp']; ?>" size="10" />
              <input name="observacion5_egp" type="hidden" value="<?php echo $row_egp['observacion5_egp']; ?>" /></td>
        </tr>        
        <tr>
          <td colspan="3" id="fuente2">Vendedor
            <select name="vendedor" id="vendedor">
              <option value="" <?php if (!(strcmp("", $row_egp['vendedor']))) {echo "selected=\"selected\"";} ?>>*</option>
              <?php
do {  
?>
              <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $row_egp['vendedor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
              <?php
} while ($row_vendedores = mysql_fetch_assoc($vendedores));
  $rows = mysql_num_rows($vendedores);
  if($rows > 0) {
      mysql_data_seek($vendedores, 0);
	  $row_vendedores = mysql_fetch_assoc($vendedores);
  }
?>
            </select></td>
        </tr>
        <tr>
          <td colspan="3" id="detalle1">Ultima Modificaci&oacute;n: <?php echo $row_egp['responsable_modificacion']; ?>
              <input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" /></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle2"><?php echo $row_egp['fecha_modificacion']; ?>
              <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>" /></td>
          <td id="detalle2"><?php echo $row_egp['hora_modificacion']; ?>
              <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a") ?>" /></td>
        </tr>        
        <tr>
          <td colspan="3" id="dato2"><input type="submit" value="Actualizar EGP"></td>
          </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
      <input type="hidden" name="n_egp" value="<?php echo $row_egp['n_egp']; ?>">
    </form>
	</div>
	</td>
<td id="detalle2">
<div align="center">
<form method="post" name="form2" action="<?php echo $editFormAction; ?>">
    <table id="tabla6">
      <tr>
        <td colspan="2" id="fuente2">Solo debe ser 3 numeros  </td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2"><input name="n_cotiz_cn" type="hidden" value="<?php echo $row_nueva['n_cotiz_cn']; ?>" />
            <input name="n_egp_cn" type="hidden" value="<?php echo $row_egp['n_egp']; ?>" />
            <input name="n_egp_cn2" type="hidden" id="n_egp_cn2" value="<?php echo $row_nueva['n_egp_cn']; ?>" /><strong>REFERENCIA</strong><input name="id_c_cotiz" type="hidden" id="id_c_cotiz" value="<?php echo $row_cotizacion['id_c_cotiz']; ?>" />
          <input name="fecha_cotiz" type="hidden" value="<?php echo $row_cotizacion['fecha_cotiz']; ?>" />
          <input name="registro" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><div id="resultado"></div>
            <input type="text" name="cod_ref_cn" value="<?php echo $row_nueva['cod_ref_cn']; ?>" size="5" onblur="if (form2.cod_ref_cn.value) { DatosGestiones('1','cod_ref_cn',form2.cod_ref_cn.value); } else { alert('Debe digitar la REFERENCIA para validar su existencia en la BD'); }"><br /><img src="images/ciclo1.gif" style="cursor:hand;" border="0" alt="RESTAURAR" onclick="window.history.go()" /></td>
      </tr>
      <tr>
        <td id="fuente2">Ancho</td>
        <td id="fuente2">Largo</td>
      </tr>
      <tr>
        <td id="dato2"><input type="text" name="ancho_cn" value="<?php echo $row_nueva['ancho_cn']; ?>" size="10" onblur="calcular()" /></td>
        <td id="dato2"><input type="text" name="largo_cn" value="<?php echo $row_nueva['largo_cn']; ?>" size="10" onblur="calcular()" /></td>
      </tr>
      <tr>
        <td id="fuente2">Solapa</td>
        <td id="fuente2">Bolsillo</td>
      </tr>
      <tr>
        <td id="dato2"><input type="text" name="solapa_cn" value="<?php echo $row_nueva['solapa_cn']; ?>" size="10" onblur="calcular()" /></td>
        <td id="dato2"><input type="text" name="bolsillo_guia_cn" value="<?php echo $row_nueva['bolsillo_guia_cn']; ?>" size="10" /></td>
      </tr>
      <tr>
        <td id="fuente2">Calibre</td>
        <td id="fuente2">Peso Millar </td>
      </tr>
      <tr>
        <td id="dato2"><input type="text" name="calibre_cn" value="<?php echo $row_nueva['calibre_cn']; ?>" size="10" onblur="calcular()" /></td>
        <td id="dato2"><input type="text" name="peso_millar_cn" value="<?php echo $row_nueva['peso_millar_cn']; ?>" size="10" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Tipo de Bolsa </td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><select name="tipo_bolsa_cn">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Seguridad" <?php if (!(strcmp("Seguridad", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>Seguridad</option>
            <option value="Currier" <?php if (!(strcmp("Currier", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>Currier</option>
            <option value="Bolsa Plastica" <?php if (!(strcmp("Bolsa Plastica", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>Bolsa Plastica</option>
            <option value="Moneda" <?php if (!(strcmp("Moneda", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>Moneda</option>
            <option value="Moneda-HOT MELT" <?php if (!(strcmp("Moneda-HOT MELT", $row_nueva['tipo_bolsa_cn']))) {echo "selected=\"selected\"";} ?>>Moneda-HOT MELT</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Codigo Barras &amp; Formato </td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><select name="barra_formato_cn" id="barra_formato_cn">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['barra_formato_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN 128" <?php if (!(strcmp("EAN 128", $row_nueva['barra_formato_cn']))) {echo "selected=\"selected\"";} ?>>EAN 128</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Material</td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><select name="material_cn" id="material_cn">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="Ldpe coestruido pigmentado"  <?php if (!(strcmp("Ldpe coestruido pigmentado", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido pigmentado</option>
          <option value="Ldpe coestruido sin pigmentos"  <?php if (!(strcmp("Ldpe coestruido sin pigmentos", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido sin pigmentos</option>
          <option value="Ldpe monocapa sin pigmentos"  <?php if (!(strcmp("Ldpe monocapa sin pigmentos", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa sin pigmentos</option>
          <option value="Ldpe monocapa pigmentado"  <?php if (!(strcmp("Ldpe monocapa pigmentado", $row_nueva['material_cn']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa pigmentado</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Tipo de Adhesivo </td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><select name="adhesivo_cn" id="adhesivo_cn">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Cinta de Seguridad" <?php if (!(strcmp("Cinta de Seguridad", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>Cinta de Seguridad</option><option value="Cinta Permanente" <?php if (!(strcmp("Cinta Permanente", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>Cinta Permanente</option>
            <option value="Cinta Resellable" <?php if (!(strcmp("Cinta Resellable", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>Cinta Resellable</option>
            <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_nueva['adhesivo_cn']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
          </select></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Impresi&oacute;n</td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><input type="text" name="impresion_cn" value="<?php echo $row_nueva['impresion_cn']; ?>" size="30" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Numeraci&oacute;n &amp; Posiciones </td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><input type="text" name="num_pos_cn" value="<?php echo $row_nueva['num_pos_cn']; ?>" size="30" /></td>
      </tr>
      <tr>
        <td id="fuente2">Cantidad Minima </td>
        <td id="fuente2">Tiempo de Entrega </td>
      </tr>
      <tr>
        <td id="dato2"><input type="text" name="cant_min_cn" value="<?php echo $row_nueva['cant_min_cn']; ?>" size="10" /></td>
        <td id="dato2"><input type="text" name="tiempo_entrega_cn" value="<?php echo $row_nueva['tiempo_entrega_cn']; ?>" size="10" /></td>
      </tr>
      <tr>
        <td id="fuente2">INCOTERM</td>
        <td id="fuente2">Precio de Venta </td>
      </tr>
      <tr>
        <td id="dato2"><select name="incoterm_cn" id="incoterm_cn">
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
        <td id="dato2"><input type="text" name="precio_venta_cn" value="<?php echo $row_nueva['precio_venta_cn']; ?>" size="10" /></td>
      </tr>
      <tr>
        <td id="fuente2">Moneda</td>
        <td id="fuente2">Unidad</td>
      </tr>
      <tr>
        <td id="dato2"><select name="moneda_cn" id="moneda_cn">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['moneda_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="COL $" <?php if (!(strcmp("COL $", $row_nueva['moneda_cn']))) {echo "selected=\"selected\"";} ?>>COL $</option>
          <option value="USD $" <?php if (!(strcmp("USD $", $row_nueva['moneda_cn']))) {echo "selected=\"selected\"";} ?>>USD $</option>
        </select></td>
        <td id="dato2"><select name="unidad_cn" id="unidad_cn">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_nueva['unidad_cn']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Unitario" <?php if (!(strcmp("Unitario", $row_nueva['unidad_cn']))) {echo "selected=\"selected\"";} ?>>Unitario</option>
          <option value="Millar" <?php if (!(strcmp("Millar", $row_nueva['unidad_cn']))) {echo "selected=\"selected\"";} ?>>Millar</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Forma de Pago</td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><input type="text" name="forma_pago_cn" value="<?php echo $row_nueva['forma_pago_cn']; ?>" size="30" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Entrega</td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><input type="text" name="entrega_cn" value="<?php echo $row_nueva['entrega_cn']; ?>" size="30" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Costo del Cirel </td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><input type="text" name="costo_cirel_cn" value="<?php echo $row_nueva['costo_cirel_cn']; ?>" size="30" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente2">Vendedor
          <select name="vendedor" id="vendedor">
            <option value="" <?php if (!(strcmp("", $row_nueva['vendedor']))) {echo "selected=\"selected\"";} ?>>*</option>
            <?php
do {  
?>
            <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $row_nueva['vendedor']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
            <?php
} while ($row_vendedores = mysql_fetch_assoc($vendedores));
  $rows = mysql_num_rows($vendedores);
  if($rows > 0) {
      mysql_data_seek($vendedores, 0);
	  $row_vendedores = mysql_fetch_assoc($vendedores);
  }
?>
          </select></td>
      </tr>
      <tr>
        <td id="fuente2">Comision</td>
        <td id="fuente2">Estado</td>
      </tr>
      <tr>
        <td id="dato2"><input name="comision" type="text" id="comision" value="<?php echo $row_nueva['comision']; ?>" size="15" /></td>
        <td id="dato2"><select name="estado_cn" id="estado_cn">
          <option value="0" <?php if (!(strcmp(0, $row_nueva['estado_cn']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
          <option value="1" <?php if (!(strcmp(1, $row_nueva['estado_cn']))) {echo "selected=\"selected\"";} ?>>Aceptada</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" id="dato2">Cambiar el estado si el cliente aprobo la Referencia </td>
      </tr>
      <tr>
        <td colspan="2" id="dato2"><input name="submit" type="submit" value="Actualizar Referencia" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form2">
    <input type="hidden" name="n_cn" value="<?php echo $row_nueva['n_cn']; ?>">
  </form>
  </div>
  </td>
  </tr>
</table></td></tr></table>
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

mysql_free_result($cotizacion);

mysql_free_result($egp);

mysql_free_result($egps);

mysql_free_result($vendedores);

mysql_free_result($nueva);
?>
