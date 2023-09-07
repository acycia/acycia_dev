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
  $updateSQL = sprintf("UPDATE Tbl_revision_packing SET id_rev_p=%s, id_ref_rev_p=%s, fecha_rev_p=%s, responsable_rev_p=%s, int_anchot_p=%s, int_numerop_p=%s, int_rodillo_p=%s, int_repeticion_p=%s, b_recibir_muestra_rev_p=%s, b_recibir_artes_rev_p=%s, b_recibir_textos_rev_p=%s, b_orientacion_total_arte_rev_p=%s, b_entregar_arte_elong_rev_p=%s, str_obs_general_p=%s, actualizado_rev_p=%s, fecha_actualizado_rev_p=%s WHERE id_rev_p=%s",
                       GetSQLValueString($_POST['id_rev_p'], "int"),
                       GetSQLValueString($_POST['id_ref_rev_p'], "int"),
                       GetSQLValueString($_POST['fecha_rev_p'], "date"),
                       GetSQLValueString($_POST['responsable_rev_p'], "text"),
					   GetSQLValueString($_POST['int_anchot_p'], "double"),
                       GetSQLValueString($_POST['int_numerop_p'], "double"),
                       GetSQLValueString($_POST['int_rodillo_p'], "double"),
					   GetSQLValueString($_POST['int_repeticion_p'], "double"),   
                       GetSQLValueString(isset($_POST['b_recibir_muestra_rev_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['b_recibir_artes_rev_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['b_recibir_textos_rev_p']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($_POST['b_orientacion_total_arte_rev_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['b_entregar_arte_elong_rev_p']) ? "true" : "", "defined","1","0"),                      
                       GetSQLValueString($_POST['str_obs_general_p'], "text"),
                       GetSQLValueString($_POST['actualizado_rev_p'], "text"),
                       GetSQLValueString($_POST['fecha_actualizado_rev_p'], "date"),
					   GetSQLValueString($_POST['id_rev_p'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "revision_packing_vista.php?id_rev_p=" . $_POST['id_rev_p'] . "";
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
if (isset($_GET['id_rev_p'])) {
  $colname_revision_edit = (get_magic_quotes_gpc()) ? $_GET['id_rev_p'] : addslashes($_GET['id_rev_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision_edit = sprintf("SELECT * FROM Tbl_revision_packing WHERE id_rev_p = %s", $colname_revision_edit);
$revision_edit = mysql_query($query_revision_edit, $conexion1) or die(mysql_error());
$row_revision_edit = mysql_fetch_assoc($revision_edit);
$totalRows_revision_edit = mysql_num_rows($revision_edit);

$colname_revision_ref = "-1";
if (isset($_GET['id_rev_p'])) {
  $colname_revision_ref = (get_magic_quotes_gpc()) ? $_GET['id_rev_p'] : addslashes($_GET['id_rev_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision_ref = sprintf("SELECT * FROM Tbl_revision_packing, Tbl_referencia, Tbl_egp WHERE Tbl_revision_packing.id_rev_p = %s AND Tbl_revision_packing.id_ref_rev_p = Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_revision_ref);
$revision_ref = mysql_query($query_revision_ref, $conexion1) or die(mysql_error());
$row_revision_ref = mysql_fetch_assoc($revision_ref);
$totalRows_revision_ref = mysql_num_rows($revision_ref);

$colname_validacion = "-1";
if (isset($_GET['id_rev_p'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_rev_p'] : addslashes($_GET['id_rev_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM Tbl_revision_packing, Tbl_validacion_packing WHERE Tbl_revision_packing.id_rev_p = %s AND Tbl_revision_packing.id_ref_rev_p = Tbl_validacion_packing.id_ref_val_p", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_rev_p'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_rev_p'] : addslashes($_GET['id_rev_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM Tbl_revision_packing, TblFichaTecnica WHERE Tbl_revision_packing.id_rev_p = %s AND Tbl_revision_packing.id_ref_rev_p = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_rev_p'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_rev_p'] : addslashes($_GET['id_rev_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM Tbl_revision_packing,TblCertificacion WHERE Tbl_revision_packing.id_rev_p = %s AND Tbl_revision_packing.id_ref_rev_p = TblCertificacion.idref",$colname_certificacion_ref);
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
<tr><td colspan="2" align="center">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="MM_validateForm('fecha_rev_p','','R','responsable_rev','','R','int_numero_p_p','','RisNum','int_rodillo_p','','RisNum','int_anchot_p','','RisNum','int_repeticion_p','','RisNum','int_numero_p','','RisNum');return document.MM_returnValue">
  <table id="tabla2">
    <tr id="tr1">
      <td id="codigo">CÓDIGO: R1-F01</td>
      <td colspan="2" id="titulo2">PLAN DE DISE&Ntilde;O Y DESARROLLO </td>
      <td id="codigo">VERSION: 3</td>
    </tr>
    <tr>
      <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
      <td colspan="2" id="subtitulo">ADD - I. REVISION PACKING LIST        <?php echo $row_revision_edit['id_rev_p']; ?></strong></td>
      <td id="dato2"><a href="javascript:eliminar1('id_rev_p',<?php echo $row_revision_edit['id_rev_p']; ?>,'revision_packing_edit.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;" /></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_revision_ref['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a>
        <?php $val=$row_validacion['id_val_p']; if($val == '') { ?>
        <a href="validacion_packing_add.php?id_ref=<?php echo $row_revision_ref['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a>
        <?php } else{ ?>
        <a href="validacion_packing_vista.php?id_val_p=<?php echo $row_validacion['id_val_p']; ?>"><img src="images/v.gif" alt="VALIDACION"  title="VALIDACION" border="0" style="cursor:hand;" /></a>
        <?php } ?>
        <?php $ft=$row_ficha_tecnica['n_ft']; if($ft == '') { ?>
        <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_revision_ref['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a>
        <?php } else{ ?>
        <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>
        <?php } ?>
        <?php if($row_certificacion['idcc']=='') { ?>
        <a href="certificacion_add.php?id_ref=<?php echo $row_revision_ref['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_revision_ref['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
        </a><?php } ?>
          </td>
    </tr>
    <tr id="tr1">
      <td id="fuente2">FECHA REGISTRO </td>
      <td colspan="2" id="fuente2">RESPONSABLE</td>
      </tr>
    <tr>
      <td id="dato2"><input name="fecha_rev_p" type="text" id="fecha_rev_p" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
      <td colspan="2" id="dato2"><input name="responsable_rev_p" type="text" id="responsable_rev_p" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" readonly="readonly"/></td>
      </tr>
    <tr id="tr1">
      <td id="fuente2">REFERENCIA</td>
      <td id="fuente2">&nbsp;</td>
      <td id="fuente2">COTIZACION N&deg; </td>
    </tr>
    <tr>
      <td id="dato2"><strong>
        <input name="id_ref_rev_p" type="hidden" value="<?php echo $row_revision_ref['id_ref']; ?>" />
        <?php echo $row_revision_ref['cod_ref']; ?> - <?php echo $row_revision_ref['version_ref']; ?> </strong></td>
      <td id="dato2">&nbsp;</td>
      <td id="dato2"><?php echo $row_revision_ref['n_cotiz_ref']; ?></td>
    </tr>
    <tr>
      <td><?php if($row_revision_ref['estado_ref'] == '1') { ?> <div id="acceso2"><?php echo "Activa"; ?></div> <?php } else { ?> <div id="numero2"> <?php echo "Inactiva"; ?> </div> <?php } ?></td>
      <td colspan="2" id="dato2">&nbsp;</td>
      </tr>
    <tr id="tr1">
      <td colspan="4" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
      </tr>
    <tr id="tr1">
      <td id="fuente1">ANCHO</td>
      <td id="fuente1">LARGO</td>
      <td id="fuente1">CALIBRE</td>
      <td id="fuente1">PRESENTACION</td>
    </tr>
    <tr>
      <td id="dato1"><?php echo $row_revision_ref['ancho_ref']; ?></td>
      <td id="dato1"><?php echo $row_revision_ref['largo_ref']; ?></td>
      <td id="dato1"><?php echo $row_revision_ref['calibre_ref']; ?></td>
      <td id="dato1"><?php echo $row_revision_ref['Str_presentacion']; ?></td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">BOCA ENTRADA</td>
      <td id="fuente1">UBICACION ENTRADA</td>
      <td id="fuente1">LAMINA 1</td>
      <td id="fuente1">LAMINA 2</td>
    </tr>
    <tr>
      <td id="dato1"><?php echo $row_revision_ref['Str_boca_entr_p']; ?></td>
      <td id="dato1"><?php echo $row_revision_ref['Str_entrada_p']; ?></td>
      <td id="dato1"><?php echo $row_revision_ref['Str_lamina1_p']; ?></td>
      <td id="dato1"><?php echo $row_revision_ref['Str_lamina2_p']; ?></td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">ANCHO TOTAL</td>
      <td id="fuente1">NUMERO DE PISTAS</td>
      <td id="fuente1">RODILLO</td>
      <td id="fuente1">REPETICION</td>
      </tr>
    <tr>
      <td id="dato1"><input type="text" name="int_anchot_p" id="int_anchot_p" value="<?php echo $row_revision_edit['int_anchot_p']; ?>" /></td>
      <td id="dato1"><input type="text" name="int_numerop_p" id="int_numerop_p" value="<?php echo $row_revision_edit['int_numerop_p']; ?>" /></td>
      <td id="dato1"><input type="text" name="int_rodillo_p" id="int_rodillo_p" value="<?php echo $row_revision_edit['int_rodillo_p']; ?>" /></td>
      <td id="dato1"><input type="text" name="int_repeticion_p" id="int_repeticion_p" value="<?php echo $row_revision_edit['int_repeticion_p']; ?>" /></td>
      </tr>
    <tr>
      <td id="dato1">&nbsp;</td>
      <td id="dato1">&nbsp;</td>
      <td colspan="2" id="dato1">&nbsp;</td>
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
    <tr >
      <td colspan="4" id="dato2">&nbsp;</td>
    </tr>
    <tr id="tr1">
      <td colspan="4" id="titulo4">ARTE</td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_revision_edit['b_recibir_muestra_rev_p'],1))) {echo "checked=\"checked\"";} ?> name="b_recibir_muestra_rev_p" type="checkbox" value="1" />
Se recibe bosquejo o muestra fisica del cliente. </td>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_revision_edit['b_recibir_artes_rev_p'],1))) {echo "checked=\"checked\"";} ?> name="b_recibir_artes_rev_p" type="checkbox" value="1" />
Se recibe arte completo del cliente o logos.</td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_revision_edit['b_recibir_textos_rev_p'],1))) {echo "checked=\"checked\"";} ?> name="b_recibir_textos_rev_p" type="checkbox" value="1" />
Se reciben solo textos por el cliente.</td>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_revision_edit['b_orientacion_total_arte_rev_p'],1))) {echo "checked=\"checked\"";} ?> name="b_orientacion_total_arte_rev_p" type="checkbox" value="1" />
        Se solicita orientaci&oacute;n total en el arte.</td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_revision_edit['b_entregar_arte_elong_rev_p'],1))) {echo "checked=\"checked\"";} ?> name="b_entregar_arte_elong_rev_p" type="checkbox" value="1" />
        Se debe entregar arte incluyendo elongaci&oacute;n.</td>
      <td colspan="2" id="detalle1">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" id="detalle1">&nbsp;</td>
      </tr>
    <tr id="tr1">
      <td colspan="4" id="titulo4">OBSERVACIONES</td>
    </tr>
    <tr>
      <td colspan="4" id="dato2"><textarea name="str_obs_general_p" cols="80" rows="3"><?php  if($row_revision_ref['observacion5_egp']!=''){echo "Obs. Referencia:".$row_revision_ref['observacion5_egp'];echo" ";}?><?php echo $row_revision_edit['str_obs_general_p']; ?></textarea></td>
      </tr>
    <tr>
      <td colspan="4" id="dato2"><input name="fecha_actualizado_rev_p" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
        <input name="actualizado_rev_p" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
        <input type="submit" value="EDITAR REVISION"></td>
      </tr>
  </table>
  <input name="id_rev_p" type="hidden" value="<?php echo $row_revision_edit['id_rev_p']; ?>" />
  <input type="hidden" name="MM_update" value="form1">
</form></td></tr>
<tr>
  <td colspan="2" align="center">&nbsp;</td>
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

//mysql_free_result($ficha_tecnica);
?>
