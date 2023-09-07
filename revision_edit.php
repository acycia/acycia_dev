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
  $updateSQL = sprintf("UPDATE revision SET id_ref_rev=%s, fecha_rev=%s, responsable_rev=%s, translape_rev=%s, capa_rev=%s, peso_max_rev=%s, presentacion_rev=%s, num_rodillos_rev=%s, repeticion_rev=%s, tipo_elong_rev=%s, valor_tipo_elong_rev=%s, recibir_muestra_rev=%s, recibir_artes_rev=%s, recibir_textos_rev=%s, orientacion_textos_rev=%s, cinta_afecta_rev=%s, valor_cinta_afecta_rev=%s, entregar_arte_elong_rev=%s, orientacion_total_arte_rev=%s, observacion_rev=%s, actualizado_rev=%s, fecha_actualizado_rev=%s WHERE id_rev=%s",
                       GetSQLValueString($_POST['id_ref_rev'], "int"),
                       GetSQLValueString($_POST['fecha_rev'], "date"),
                       GetSQLValueString($_POST['responsable_rev'], "text"),
                       GetSQLValueString($_POST['translape_rev'], "int"),
                       GetSQLValueString($_POST['capa_rev'], "text"),
                       GetSQLValueString($_POST['peso_max_rev'], "double"),
                       GetSQLValueString($_POST['presentacion_rev'], "text"),
                       GetSQLValueString($_POST['num_rodillos_rev'], "int"),
                       GetSQLValueString($_POST['repeticion_rev'], "int"),
                       GetSQLValueString($_POST['tipo_elong_rev'], "text"),
                       GetSQLValueString($_POST['valor_tipo_elong_rev'], "double"),
                       GetSQLValueString(isset($_POST['recibir_muestra_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['recibir_artes_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['recibir_textos_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orientacion_textos_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['cinta_afecta_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['valor_cinta_afecta_rev'], "double"),
                       GetSQLValueString(isset($_POST['entregar_arte_elong_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orientacion_total_arte_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observacion_rev'], "text"),
                       GetSQLValueString($_POST['actualizado_rev'], "text"),
                       GetSQLValueString($_POST['fecha_actualizado_rev'], "date"),
                       GetSQLValueString($_POST['id_rev'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "revision_vista.php?id_rev=" . $_POST['id_rev'] . "";
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

$colname_revision_edit = "-1";
if (isset($_GET['id_rev'])) {
  $colname_revision_edit = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision_edit = sprintf("SELECT * FROM revision WHERE id_rev = %s", $colname_revision_edit);
$revision_edit = mysql_query($query_revision_edit, $conexion1) or die(mysql_error());
$row_revision_edit = mysql_fetch_assoc($revision_edit);
$totalRows_revision_edit = mysql_num_rows($revision_edit);

$colname_revision_ref = "-1";
if (isset($_GET['id_rev'])) {
  $colname_revision_ref = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision_ref = sprintf("SELECT * FROM revision, Tbl_referencia, Tbl_egp WHERE revision.id_rev = %s AND revision.id_ref_rev = Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_revision_ref);
$revision_ref = mysql_query($query_revision_ref, $conexion1) or die(mysql_error());
$row_revision_ref = mysql_fetch_assoc($revision_ref);
$totalRows_revision_ref = mysql_num_rows($revision_ref);

$colname_validacion = "-1";
if (isset($_GET['id_rev'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM revision, validacion WHERE revision.id_rev = %s AND revision.id_ref_rev = validacion.id_ref_val", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_rev'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM revision, TblFichaTecnica WHERE revision.id_rev = %s AND revision.id_ref_rev = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_rev'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM revision,TblCertificacion WHERE revision.id_rev = %s AND revision.id_ref_rev = TblCertificacion.idref",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref);  

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tabla"><tr align="center"><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1">
<tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>
</ul>
</td></tr>
<tr><td colspan="2" align="center"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="MM_validateForm('translape_rev','RisNum','peso_max_rev','RisNum','num_rodillos_rev','','RisNum','repeticion_rev','','RisNum','tipo_elong_rev','','R','valor_tipo_elong_rev','','NisNum','valor_cinta_afecta_rev','','NisNum');return document.MM_returnValue">
      <table id="tabla2">
	  <tr id="tr1">
      <td id="codigo">CÓDIGO: R1-F01</td>
      <td colspan="2" nowrap="nowrap" id="titulo2">PLAN DE DISE&Ntilde;O Y DESARROLLO </td>
      <td id="codigo">VERSION: 3</td>
	  </tr>
        <tr>
          <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
          <td colspan="2" id="subtitulo">EDITAR - I. REVISION <?php echo $row_revision_edit['id_rev']; ?></td>
          <td id="dato2"><a href="revision_vista.php?id_rev=<?php echo $row_revision_edit['id_rev']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('id_rev',<?php echo $row_revision_edit['id_rev']; ?>,'revision_edit.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;" /></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia.php?id_ref=<?php echo $row_revision_ref['id_ref_rev']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a><?php $val=$row_validacion['id_val']; if($val == '') { ?> <a href="validacion_add.php?id_ref=<?php echo $row_revision_ref['id_ref_rev']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php $ft=$row_ficha_tecnica['n_ft']; if($ft == '') { ?>
      <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_revision_ref['id_ref_rev']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else{ ?>
      <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>      <?php } ?>
      <?php if($row_certificacion['idcc']=='') { ?>
          <a href="certificacion_add.php?id_ref=<?php echo $row_revision_ref['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_revision_ref['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
          </a><?php } ?></td>
        </tr>
        <tr id="tr1">
          <td id="fuente2">FECHA REGISTRO </td>
          <td colspan="2" id="fuente2">RESPONSABLE</td>
          </tr>
        <tr>
          <td id="dato2"><input name="fecha_rev" type="text" value="<?php echo $row_revision_edit['fecha_rev']; ?>" size="10" /></td>
          <td colspan="2" id="dato2"><input name="responsable_rev" type="text" value="<?php echo $row_revision_edit['responsable_rev']; ?>" size="30" /></td>
          </tr>
        <tr id="tr1">
          <td id="fuente2">REFERENCIA</td>
          <td id="fuente2">EGP N°</td>
          <td id="fuente2">COTIZACION N°</td>
          </tr>
        <tr>
          <td id="dato2"><input name="id_ref_rev" type="hidden" value="<?php echo $row_revision_edit['id_ref_rev']; ?>" /><strong><?php echo $row_revision_ref['cod_ref']; ?> - <?php echo $row_revision_ref['version_ref']; ?></strong></td>
          <td id="dato2"><?php echo $row_revision_ref['n_egp_ref']; ?></td>
          <td id="dato2"><?php echo $row_revision_ref['n_cotiz_ref']; ?></td>
        </tr>
        <tr>
          <td id="dato2"><?php if($row_revision_ref['estado_ref'] == '1') { ?> <div id="acceso2"><?php echo "Activa"; ?></div> <?php } else { ?> <div id="numero2"> <?php echo "Inactiva"; ?> </div> <?php } ?></td>
          <td colspan="2" id="dato2">&nbsp;</td>
          </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
        </tr>
        <tr id="tr1">
          <td id="fuente1">ANCHO</td>
          <td id="fuente1">LARGO</td>
          <td id="fuente1">SOLAPA</td>
          <td id="fuente1">BOLSILLO PORTAGUIA </td>
        </tr>
        <tr>
          <td id="dato1"><?php echo $row_revision_ref['ancho_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['largo_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['solapa_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['bolsillo_guia_ref']; ?></td>
        </tr>
        <tr id="tr1">
          <td id="fuente1">CALIBRE</td>
          <td id="fuente1">PESO MILLAR </td>
          <td id="fuente1">TIPO DE BOLSA </td>
          <td id="fuente1">ADHESIVO</td>
        </tr>
        <tr>
          <td id="dato1"><?php echo $row_revision_ref['calibre_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['peso_millar_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['tipo_bolsa_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['adhesivo_ref']; ?></td>
        </tr>
        <tr id="tr1">
          <td id="fuente1">MATERIAL</td>
          <td id="fuente1">IMPRESION</td>
          <td id="fuente1">NUM. &amp; POSICIONES </td>
          <td id="fuente1">CODIGO BARRAS &amp; FORM. </td>
        </tr>
        <tr>
          <td id="dato1"><?php echo $row_revision_ref['material_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['impresion_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['num_pos_ref']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['cod_form_ref']; ?></td>
        </tr>
        <tr id="tr1">
          <td id="fuente1">TRANSLAPE</td>
          <td id="fuente1">PESO MAXIMO APLICADO </td>
          <td id="fuente1">CAPA</td>
          <td id="fuente1">PRESENTACION</td>
        </tr>
        
        <tr>
          <td id="dato1"><input type="text" name="translape_rev" value="<?php echo $row_revision_edit['translape_rev']; ?>" size="20" /></td>
          <td id="dato1"><input type="text" name="peso_max_rev" value="<?php echo $row_revision_edit['peso_max_rev']; ?>" size="20" /></td>
          <td id="dato1"><select name="capa_rev">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_revision_edit['capa_rev']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="A sobre B" <?php if (!(strcmp("A sobre B", $row_revision_edit['capa_rev']))) {echo "selected=\"selected\"";} ?>>A sobre B</option>
              <option value="B sobre A" <?php if (!(strcmp("B sobre A", $row_revision_edit['capa_rev']))) {echo "selected=\"selected\"";} ?>>B sobre A</option>
          </select></td>
          <td id="dato1"><select name="presentacion_rev">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_revision_edit['presentacion_rev']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Lamina" <?php if (!(strcmp("Lamina", $row_revision_edit['presentacion_rev']))) {echo "selected=\"selected\"";} ?>>Lamina</option>
            <option value="Tubular" <?php if (!(strcmp("Tubular", $row_revision_edit['presentacion_rev']))) {echo "selected=\"selected\"";} ?>>Tubular</option>
            <option value="Semitubular" <?php if (!(strcmp("Semitubular", $row_revision_edit['presentacion_rev']))) {echo "selected=\"selected\"";} ?>>Semitubular</option>
          </select></td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo4">DATOS ESPECIFICOS DE LA REFERENCIA</td>
        </tr>
        <tr id="tr1">
          <td id="fuente1">TIPO EXTRUSION </td>
          <td id="fuente1">PIGMENTO EXTERIOR</td>
          <td id="fuente1">PIGMENTO INTERIOR </td>
          <td id="fuente1">TIPO SELLO </td>
        </tr>
        <tr>
          <td id="dato1"><?php echo $row_revision_ref['tipo_ext_egp']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['pigm_ext_egp']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['pigm_int_epg']; ?></td>
          <td id="dato1"><?php echo $row_revision_ref['tipo_sello_egp']; ?></td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="fuente2"><strong>MATERIAL A IMPRIMIR</strong></td>
        </tr>
        
        <tr>
          <td id="detalle1">Color 1: <?php echo $row_revision_ref['color1_egp']; ?></td>
          <td colspan="2" id="detalle1">Pantone: <?php echo $row_revision_ref['pantone1_egp']; ?></td>
          <td id="detalle1">Ubicacion: <?php echo $row_revision_ref['ubicacion1_egp']; ?></td>
        </tr>
        <tr>
          <td id="detalle1">Color 2: <?php echo $row_revision_ref['color2_egp']; ?></td>
          <td colspan="2" id="detalle1">Pantone: <?php echo $row_revision_ref['pantone2_egp']; ?></td>
          <td id="detalle1">Ubicacion: <?php echo $row_revision_ref['ubicacion2_egp']; ?></td>
        </tr>
        <tr>
          <td id="detalle1">Color 3: <?php echo $row_revision_ref['color3_egp']; ?></td>
          <td colspan="2" id="detalle1">Pantone: <?php echo $row_revision_ref['pantone3_egp']; ?></td>
          <td id="detalle1">Ubicacion: <?php echo $row_revision_ref['ubicacion3_egp']; ?></td>
        </tr>
        <tr>
          <td id="detalle1">Color 4: <?php echo $row_revision_ref['color4_egp']; ?></td>
          <td colspan="2" id="detalle1">Pantone: <?php echo $row_revision_ref['pantone4_egp']; ?></td>
          <td id="detalle1">Ubicacion: <?php echo $row_revision_ref['ubicacion4_egp']; ?></td>
        </tr>
        <tr>
          <td id="detalle1">Color 5: <?php echo $row_revision_ref['color5_egp']; ?></td>
          <td colspan="2" id="detalle1">Pantone: <?php echo $row_revision_ref['pantone5_egp']; ?></td>
          <td id="detalle1">Ubicacion: <?php echo $row_revision_ref['ubicacion5_egp']; ?></td>
        </tr>
        <tr>
          <td id="detalle1">Color 6: <?php echo $row_revision_ref['color6_egp']; ?></td>
          <td colspan="2" id="detalle1">Pantone: <?php echo $row_revision_ref['pantone6_egp']; ?></td>
          <td id="detalle1">Ubicacion: <?php echo $row_revision_ref['ubicacion6_egp']; ?></td>
        </tr>
        <tr>
          <td id="detalle1">Color 7: <?php echo $row_revision_ref['color7_egp']; ?></td>
          <td colspan="2" id="detalle1">Pantone: <?php echo $row_revision_ref['pantone7_egp']; ?></td>
          <td id="detalle1">Ubicacion: <?php echo $row_revision_ref['ubicacion7_egp']; ?></td>
        </tr>
        <tr>
          <td id="detalle1">Color 8: <?php echo $row_revision_ref['color8_egp']; ?></td>
          <td colspan="2" id="detalle1">Pantone: <?php echo $row_revision_ref['pantone8_egp']; ?></td>
          <td id="detalle1">Ubicacion: <?php echo $row_revision_ref['ubicacion8_egp']; ?></td>
        </tr>
        
        <tr id="tr1">
          <td colspan="2" id="fuente2"><strong>POSICION</strong></td>
          <td id="fuente2"><strong>TIPO NUMERACION</strong></td>
          <td id="fuente2"><strong>FORM. CODIGO DE BARRAS</strong></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1">Posicion de la Solapa Talonario Recibo </td>
          <td id="detalle1"><?php echo $row_revision_ref['tipo_solapatr_egp']; ?></td>
          <td id="detalle1"><?php echo $row_revision_ref['cb_solapatr_egp']; ?></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1">Posicion de la Cinta</td>
          <td id="detalle1"><?php echo $row_revision_ref['tipo_cinta_egp']; ?></td>
          <td id="detalle1"><?php echo $row_revision_ref['cb_cinta_egp']; ?></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1">Posicion del Principal</td>
          <td id="detalle1"><?php echo $row_revision_ref['tipo_principal_egp']; ?></td>
          <td id="detalle1"><?php echo $row_revision_ref['cb_principal_egp']; ?></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1">Posicion del Inferior</td>
          <td id="detalle1"><?php echo $row_revision_ref['tipo_inferior_egp']; ?></td>
          <td id="detalle1"><?php echo $row_revision_ref['cb_inferior_egp']; ?></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1">Posicion del Liner</td>
          <td id="detalle1"><?php echo $row_revision_ref['tipo_liner_egp']; ?></td>
          <td id="detalle1"><?php echo $row_revision_ref['cb_liner_egp']; ?></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1">Posicion del Bolsillo</td>
          <td id="detalle1"><?php echo $row_revision_ref['tipo_bols_egp']; ?></td>
          <td id="detalle1"><?php echo $row_revision_ref['cb_bols_egp']; ?></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1"><?php echo $row_revision_ref['tipo_nom_egp']; ?></td>
          <td id="detalle1"><?php echo $row_revision_ref['tipo_otro_egp']; ?></td>
          <td id="detalle1"><?php echo $row_revision_ref['cb_otro_egp']; ?></td>
        </tr>                
        <tr id="tr1">
          <td colspan="4" id="titulo4">INFORMACION DE PRODUCCION SOBRE NEGATIVOS Y CYREL</td>
        </tr>
        <tr id="tr1">
          <td id="fuente1">Numero del Rodillo (cm) </td>
          <td id="fuente1">Repeticiones x revoluci&oacute;n </td>
          <td id="fuente1">Tipo de Elongaci&oacute;n </td>
          <td id="fuente1">Valor </td>
        </tr>        
        <tr>
          <td id="dato1"><input type="text" name="num_rodillos_rev" value="<?php echo $row_revision_edit['num_rodillos_rev']; ?>" size="20" /></td>
          <td id="dato1"><input type="text" name="repeticion_rev" value="<?php echo $row_revision_edit['repeticion_rev']; ?>" size="20" /></td>
          <td id="dato1"><input type="text" name="tipo_elong_rev" value="<?php echo $row_revision_edit['tipo_elong_rev']; ?>" size="20" /></td>
          <td id="dato1"><input type="text" name="valor_tipo_elong_rev" value="<?php echo $row_revision_edit['valor_tipo_elong_rev']; ?>" size="20" /></td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo4">ARTE</td>
          </tr>
        <tr>
          <td colspan="2" id="detalle1"><input type="checkbox" name="recibir_muestra_rev" value="1"  <?php if (!(strcmp($row_revision_edit['recibir_muestra_rev'],1))) {echo "@@checked@@";} ?> />
            Se recibe bosquejo o muestra fisica del cliente. </td>
          <td colspan="2" id="detalle1"><input type="checkbox" name="recibir_artes_rev" value="1"  <?php if (!(strcmp($row_revision_edit['recibir_artes_rev'],1))) {echo "@@checked@@";} ?> />
            Se recibe arte completo del cliente o logos.</td>
          </tr>
        <tr>
          <td colspan="2" id="detalle1"><input type="checkbox" name="recibir_textos_rev" value="1"  <?php if (!(strcmp($row_revision_edit['recibir_textos_rev'],1))) {echo "@@checked@@";} ?> />
            Se reciben solo textos por el cliente.</td>
          <td colspan="2" id="detalle1"><input type="checkbox" name="orientacion_textos_rev" value="1"  <?php if (!(strcmp($row_revision_edit['orientacion_textos_rev'],1))) {echo "@@checked@@";} ?>>
            Se solicita orientaci&oacute;n en textos de seguridad.</td>
          </tr>
        <tr>
          <td colspan="2" id="detalle1"><input type="checkbox" name="cinta_afecta_rev" value="1"  <?php if (!(strcmp($row_revision_edit['cinta_afecta_rev'],1))) {echo "@@checked@@";} ?> />
            La cinta afecta la altura de la solapa.</td>
          <td colspan="2" id="detalle1">Valor Indicado : 
            <input type="text" name="valor_cinta_afecta_rev" value="<?php echo $row_revision_edit['valor_cinta_afecta_rev']; ?>" size="10" /></td>
          </tr>
        <tr>
          <td colspan="2" id="detalle1"><input type="checkbox" name="entregar_arte_elong_rev" value="1"  <?php if (!(strcmp($row_revision_edit['entregar_arte_elong_rev'],1))) {echo "@@checked@@";} ?> />
Se debe entregar arte incluyendo elongaci&oacute;n.</td>
          <td colspan="2" id="detalle1"><input type="checkbox" name="orientacion_total_arte_rev" value="1"  <?php if (!(strcmp($row_revision_edit['orientacion_total_arte_rev'],1))) {echo "@@checked@@";} ?> />
            Se solicita orientaci&oacute;n total en el arte.</td>
          </tr>
        <tr>
          <td colspan="4" id="detalle1">Nota: La cinta puede afectar la altura de la bolsa si esta tiene solapa. El arte debe de explicar muy bien si esta incluida.</td>
          </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo4">OBSERVACIONES</td>
          </tr>
        <tr>
          <td colspan="4" id="dato2"><textarea name="observacion_rev" cols="80" rows="3"><?php  if($row_revision_ref['observacion5_egp']!=''){echo "Obs. Referencia:".$row_revision_ref['observacion5_egp'];echo" ";}?><?php echo $row_revision_ref['observacion_rev']; ?></textarea></td>
          </tr>
        <tr id="tr1">
          <td colspan="2" id="fuente2">ULTIMA ACTUALIZACION</td>
          <td colspan="2" id="fuente2">FECHA  ULTIMA ACTUALIZACION </td>
          </tr>
        <tr>
          <td colspan="2" id="dato2">-  
            <input name="actualizado_rev" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
            <?php echo $row_revision_edit['actualizado_rev']; ?> - </td>
          <td colspan="2" id="dato2">-  
            <input name="fecha_actualizado_rev" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
            <?php echo $row_revision_edit['fecha_actualizado_rev']; ?> - </td>
          </tr>
        <tr>
          <td colspan="4" id="dato2"><input type="submit" value="Actualizar REVISION"></td>
          </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
      <input type="hidden" name="id_rev" value="<?php echo $row_revision_edit['id_rev']; ?>">
    </form></td>
</tr>
</table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
 </td>
  </tr>  
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($revision_edit);

mysql_free_result($revision_ref);

mysql_free_result($validacion);

mysql_free_result($ficha_tecnica);
?>
