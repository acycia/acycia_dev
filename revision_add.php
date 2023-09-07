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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO revision (id_rev, id_ref_rev, fecha_rev, responsable_rev, translape_rev, capa_rev, peso_max_rev, presentacion_rev, num_rodillos_rev, repeticion_rev, tipo_elong_rev, valor_tipo_elong_rev, recibir_muestra_rev, recibir_artes_rev, recibir_textos_rev, orientacion_textos_rev, cinta_afecta_rev, valor_cinta_afecta_rev, entregar_arte_elong_rev, orientacion_total_arte_rev, observacion_rev, actualizado_rev, fecha_actualizado_rev) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_rev'], "int"),
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
                       GetSQLValueString(isset($_POST['entregar_arte_elong_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['entregar_arte_elong_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orientacion_total_arte_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observacion_rev'], "text"),
                       GetSQLValueString($_POST['actualizado_rev'], "text"),
                       GetSQLValueString($_POST['fecha_actualizado_rev'], "date"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "revision_vista.php?id_rev=" . $_POST['id_rev'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM revision ORDER BY id_rev DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_referencia = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_validacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM validacion WHERE id_ref_val = %s", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM TblFichaTecnica WHERE id_ref_ft = %s", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_ref'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM TblCertificacion WHERE TblCertificacion.idref='%s'",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
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
<tr><td colspan="2" align="center">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="MM_validateForm('responsable_rev','','R');return document.MM_returnValue">
  <table id="tabla2">
    <tr id="tr1">
      <td id="codigo">CÓDIGO: R1-F01</td>
      <td colspan="2" id="titulo2">PLAN DE DISE&Ntilde;O Y DESARROLLO </td>
      <td id="codigo">VERSION: 3</td>
    </tr>
    <tr>
      <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
      <td colspan="2" id="subtitulo">ADD - I. REVISION 
        <input name="id_rev" type="hidden" value="<?php $num=$row_ultimo['id_rev']+1; echo $num; ?>" /><?php echo $num; ?></strong></td>
      <td id="dato2"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a><?php $val=$row_validacion['id_val']; if($val == '') { ?> <a href="validacion_add.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION"  title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php $ft=$row_ficha_tecnica['n_ft']; if($ft == '') { ?>
      <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else{ ?>
      <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>      <?php } ?>
      <?php if($row_certificacion['idcc']=='') { ?>
          <a href="certificacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
          </a><?php } ?>
      </td>
    </tr>
    <tr id="tr1">
      <td id="fuente2">FECHA REGISTRO </td>
      <td colspan="2" id="fuente2">RESPONSABLE</td>
      </tr>
    <tr>
      <td id="dato2"><input name="fecha_rev" type="text" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
      <td colspan="2" id="dato2"><input name="responsable_rev" type="text" id="responsable_rev" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" /></td>
      </tr>
    <tr id="tr1">
      <td id="fuente2">REFERENCIA</td>
      <td id="fuente2">EGP N&deg; </td>
      <td id="fuente2">COTIZACION N&deg; </td>
    </tr>
    <tr>
      <td id="dato2"><strong>
        <input name="id_ref_rev" type="hidden" value="<?php echo $row_referencia['id_ref']; ?>" />
        <?php echo $row_referencia['cod_ref']; ?> - <?php echo $row_referencia['version_ref']; ?> </strong></td>
      <td id="dato2"><?php echo $row_referencia['n_egp_ref']; ?></td>
      <td id="dato2"><?php echo $row_referencia['n_cotiz_ref']; ?></td>
    </tr>
    <tr>
      <td><?php if($row_referencia['estado_ref'] == '1') { ?> <div id="acceso2"><?php echo "Activa"; ?></div> <?php } else { ?> <div id="numero2"> <?php echo "Inactiva"; ?> </div> <?php } ?></td>
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
      <td id="dato1"><?php echo $row_referencia['ancho_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['largo_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['solapa_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['bolsillo_guia_ref']; ?></td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">CALIBRE</td>
      <td id="fuente1">PESO MILLAR </td>
      <td id="fuente1">TIPO DE BOLSA </td>
      <td id="fuente1">ADHESIVO</td>
    </tr>
    <tr>
      <td id="dato1"><?php echo $row_referencia['calibre_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['peso_millar_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['tipo_bolsa_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['adhesivo_ref']; ?></td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">MATERIAL</td>
      <td id="fuente1">IMPRESION</td>
      <td id="fuente1">NUM. &amp; POSICIONES </td>
      <td id="fuente1">CODIGO BARRAS &amp; FORM. </td>
    </tr>
    <tr>
      <td id="dato1"><?php echo $row_referencia['material_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['impresion_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['num_pos_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['cod_form_ref']; ?></td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">TRANSLAPE</td>
      <td id="fuente1">PESO MAXIMO APLICADO </td>
      <td id="fuente1">CAPA</td>
      <td id="fuente1">PRESENTACION</td>
    </tr>
    <tr>
      <td id="dato1"><input type="text" name="translape_rev" value="" size="20" /></td>
      <td id="dato1"><input type="text" name="peso_max_rev" value="" size="20" /></td>
      <td id="dato1"><select name="capa_rev">
          <option value="N.A.">N.A.</option>
          <option value="A sobre B">A sobre B</option>
          <option value="B sobre A">B sobre A</option>
        </select></td>
      <td id="dato1"><select name="presentacion_rev">
          <option value=""></option>
          <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="LAMINA" <?php if (!(strcmp('LAMINA', $row_referencia['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>LAMINA</option>
          <option value="TUBULAR" <?php if (!(strcmp('TUBULAR', $row_referencia['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>TUBULAR</option>
       <option value="SEMITUBULAR" <?php if (!(strcmp('SEMITUBULAR', $row_referencia['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>SEMITUBULAR</option>
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
      <td id="dato1"><?php echo $row_referencia['tipo_ext_egp']; ?></td>
      <td id="dato1"><?php echo $row_referencia['pigm_ext_egp']; ?></td>
      <td id="dato1"><?php echo $row_referencia['pigm_int_epg']; ?></td>
      <td id="dato1"><?php echo $row_referencia['tipo_sello_egp']; ?></td>
    </tr>
    <tr id="tr1">
      <td colspan="4" id="fuente2"><strong>MATERIAL A IMPRIMIR</strong></td>
      </tr>
    <tr>
      <td id="detalle1">Color 1: <?php echo $row_referencia['color1_egp']; ?></td>
      <td colspan="2" id="detalle1">Pantone: <?php echo $row_referencia['pantone1_egp']; ?></td>
      <td id="detalle1">Ubicacion: <?php echo $row_referencia['ubicacion1_egp']; ?></td>
    </tr>
    <tr>
      <td id="detalle1">Color 2: <?php echo $row_referencia['color2_egp']; ?></td>
      <td colspan="2" id="detalle1">Pantone: <?php echo $row_referencia['pantone2_egp']; ?></td>
      <td id="detalle1">Ubicacion: <?php echo $row_referencia['ubicacion2_egp']; ?></td>
    </tr>
    <tr>
      <td id="detalle1">Color 3: <?php echo $row_referencia['color3_egp']; ?></td>
      <td colspan="2" id="detalle1">Pantone: <?php echo $row_referencia['pantone3_egp']; ?></td>
      <td id="detalle1">Ubicacion: <?php echo $row_referencia['ubicacion3_egp']; ?></td>
    </tr>
    <tr>
      <td id="detalle1">Color 4: <?php echo $row_referencia['color4_egp']; ?></td>
      <td colspan="2" id="detalle1">Pantone: <?php echo $row_referencia['pantone4_egp']; ?></td>
      <td id="detalle1">Ubicacion: <?php echo $row_referencia['ubicacion4_egp']; ?></td>
    </tr>
    <tr>
      <td id="detalle1">Color 5: <?php echo $row_referencia['color5_egp']; ?></td>
      <td colspan="2" id="detalle1">Pantone: <?php echo $row_referencia['pantone5_egp']; ?></td>
      <td id="detalle1">Ubicacion: <?php echo $row_referencia['ubicacion5_egp']; ?></td>
    </tr>
    <tr>
      <td id="detalle1">Color 6: <?php echo $row_referencia['color6_egp']; ?></td>
      <td colspan="2" id="detalle1">Pantone: <?php echo $row_referencia['pantone6_egp']; ?></td>
      <td id="detalle1">Ubicacion: <?php echo $row_referencia['ubicacion6_egp']; ?></td>
    </tr>
    <tr>
      <td id="detalle1">Color 7: <?php echo $row_referencia['color7_egp']; ?></td>
      <td colspan="2" id="detalle1">Pantone: <?php echo $row_referencia['pantone7_egp']; ?></td>
      <td id="detalle1">Ubicacion: <?php echo $row_referencia['ubicacion7_egp']; ?></td>
    </tr>
    <tr>
      <td id="detalle1">Color 8: <?php echo $row_referencia['color8_egp']; ?></td>
      <td colspan="2" id="detalle1">Pantone: <?php echo $row_referencia['pantone8_egp']; ?></td>
      <td id="detalle1">Ubicacion: <?php echo $row_referencia['ubicacion8_egp']; ?></td>
    </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente2"><strong>POSICION</strong></td>
      <td id="fuente2"><strong>TIPO NUMERACION</strong></td>
      <td id="fuente2"><strong>FORM. CODIGO DE BARRAS</strong></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Posicion de la Solapa Talonario Recibo </td>
      <td id="detalle1"><?php echo $row_referencia['tipo_solapatr_egp']; ?></td>
      <td id="detalle1"><?php echo $row_referencia['cb_solapatr_egp']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Posicion de la Cinta</td>
      <td id="detalle1"><?php echo $row_referencia['tipo_cinta_egp']; ?></td>
      <td id="detalle1"><?php echo $row_referencia['cb_cinta_egp']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Posicion del Principal</td>
      <td id="detalle1"><?php echo $row_referencia['tipo_principal_egp']; ?></td>
      <td id="detalle1"><?php echo $row_referencia['cb_principal_egp']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Posicion del Inferior</td>
      <td id="detalle1"><?php echo $row_referencia['tipo_inferior_egp']; ?></td>
      <td id="detalle1"><?php echo $row_referencia['cb_inferior_egp']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Posicion del Liner</td>
      <td id="detalle1"><?php echo $row_referencia['tipo_liner_egp']; ?></td>
      <td id="detalle1"><?php echo $row_referencia['cb_liner_egp']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Posicion del Bolsillo</td>
      <td id="detalle1"><?php echo $row_referencia['tipo_bols_egp']; ?></td>
      <td id="detalle1"><?php echo $row_referencia['cb_bols_egp']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><?php echo $row_referencia['tipo_nom_egp']; ?></td>
      <td id="detalle1"><?php echo $row_referencia['tipo_otro_egp']; ?></td>
      <td id="detalle1"><?php echo $row_referencia['cb_otro_egp']; ?></td>
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
      <td id="dato1"><input type="text" name="num_rodillos_rev" value="" size="20" /></td>
      <td id="dato1"><input type="text" name="repeticion_rev" value="" size="20" /></td>
      <td id="dato1"><select name="tipo_elong_rev">
          <option value="N.A.">N.A.</option>
          <option value="A lo ancho">A lo ancho</option>
          <option value="A lo largo">A lo largo</option>
        </select></td>
      <td id="dato1"><input type="text" name="valor_tipo_elong_rev" value="" size="20" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="4" id="titulo4">ARTE</td>
      </tr>
    <tr>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_referencia['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="recibir_muestra_rev" type="checkbox" value="1">
Se recibe bosquejo o muestra fisica del cliente. </td>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_referencia['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="recibir_artes_rev" type="checkbox" value="1">
Se recibe arte completo del cliente o logos.</td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input name="recibir_textos_rev" type="checkbox" value="1">
Se reciben solo textos por el cliente.</td>
      <td colspan="2" id="detalle1"><input name="orientacion_textos_rev" type="checkbox" value="1">
Se solicita orientaci&oacute;n en textos de seguridad.</td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input name="cinta_afecta_rev" type="checkbox" value="1">
La cinta afecta la altura de la solapa.</td>
      <td colspan="2" id="detalle1">Indique Valor <input type="text" name="valor_cinta_afecta_rev" value="" size="20" /></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input name="entregar_arte_elong_rev" type="checkbox" value="1">
Se debe entregar arte incluyendo elongaci&oacute;n.</td>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_referencia['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orientacion_total_arte_rev" type="checkbox" value="1">
Se solicita orientaci&oacute;n total en el arte.</td>
      </tr>
    <tr>
      <td colspan="4" id="detalle1">Nota: La cinta puede afectar la altura de la bolsa si esta tiene solapa. El arte debe de explicar muy bien si esta incluida.</td>
      </tr>
    <tr id="tr1">
      <td colspan="4" id="titulo4">OBSERVACIONES</td>
      </tr>
    <tr>
      <td colspan="4" id="dato2"><textarea name="observacion_rev" cols="80" rows="3"><?php echo $row_referencia['observacion5_egp']; ?></textarea></td>
      </tr>
    <tr>
      <td colspan="4" id="dato2"><input name="fecha_actualizado_rev" type="hidden" value="" />
        <input name="actualizado_rev" type="hidden" value="" />
        <input type="submit" value="ADD REVISION"></td>
      </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form></td></tr>
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

mysql_free_result($ultimo);

mysql_free_result($referencia);

mysql_free_result($validacion);

mysql_free_result($ficha_tecnica);
?>
