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
  $insertSQL = sprintf("INSERT INTO Tbl_revision_lamina (id_rev_l, id_ref_rev_l, fecha_rev_l, responsable_rev_l,int_numero_p_l,int_rodillo_l,
b_tratamiento_l,int_anchot_l,int_repeticion_l,int_embob_rev_l,b_preimp_l,int_numero_l,str_obs_codigo_l,b_recibir_muestra_rev_l,b_recibir_artes_rev_l,b_recibir_textos_rev_l,b_orientacion_total_arte_rev_l,b_entregar_arte_elong_rev_l,str_obs_general_l,actualizado_rev_l,fecha_actualizado_rev_l) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_rev_l'], "int"),
                       GetSQLValueString($_POST['id_ref_rev_l'], "int"),
                       GetSQLValueString($_POST['fecha_rev_l'], "date"),
                       GetSQLValueString($_POST['responsable_rev_l'], "text"),
                       GetSQLValueString($_POST['int_numero_p_l'], "double"),
                       GetSQLValueString($_POST['int_rodillo_l'], "double"),
                       GetSQLValueString($_POST['b_tratamiento_l'], "int"),
                       GetSQLValueString($_POST['int_anchot_l'], "double"),
                       GetSQLValueString($_POST['int_repeticion_l'], "double"),
					   GetSQLValueString($_POST['int_embob_rev_l'], "int"),
                       GetSQLValueString($_POST['b_preimp_l'], "int"),
                       GetSQLValueString($_POST['int_numero_l'], "int"),
					   GetSQLValueString($_POST['str_obs_codigo_l'], "text"),	   
                       GetSQLValueString(isset($_POST['b_recibir_muestra_rev_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['b_recibir_artes_rev_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['b_recibir_textos_rev_l']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($_POST['b_orientacion_total_arte_rev_l']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['b_entregar_arte_elong_rev_l']) ? "true" : "", "defined","1","0"),                      
                       GetSQLValueString($_POST['str_obs_general_l'], "text"),
                       GetSQLValueString($_POST['actualizado_rev_l'], "text"),
                       GetSQLValueString($_POST['fecha_actualizado_rev_l'], "date"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "revision_lamina_vista.php?id_rev_l=" . $_POST['id_rev_l'] . "";
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
$query_ultimo = "SELECT * FROM Tbl_revision_lamina ORDER BY id_rev_l DESC";
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
$query_validacion = sprintf("SELECT * FROM Tbl_validacion_lamina  WHERE id_ref_val_l = %s", $colname_validacion);
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="MM_validateForm('fecha_rev_l','','R','int_numero_p_l','','RisNum','int_rodillo_l','','RisNum','int_anchot_l','','RisNum','int_repeticion_l','','RisNum','int_numero_l','','NisNum');return document.MM_returnValue">
  <table id="tabla2">
    <tr id="tr1">
      <td id="codigo">C�DIGO: R1-F01</td>
      <td colspan="2" id="titulo2">PLAN DE DISE&Ntilde;O Y DESARROLLO </td>
      <td id="codigo">VERSION: 3</td>
    </tr>
    <tr>
      <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
      <td colspan="2" id="subtitulo">ADD - I. REVISION LAMINA
        <input name="id_rev_l" type="hidden" value="<?php $num=$row_ultimo['id_rev_l']+1; echo $num; ?>" /><?php echo $num; ?></strong></td>
      <td id="dato2"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a>
        <?php $val=$row_validacion['id_val_l']; if($val == '') { ?>
        <a href="validacion_lamina_add.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a>
        <?php } else{ ?>
        <a href="validacion_lamina_vista.php?id_val_l=<?php echo $row_validacion['id_val_l']; ?>"><img src="images/v.gif" alt="VALIDACION"  title="VALIDACION" border="0" style="cursor:hand;" /></a>
        <?php } ?>
        <?php $ft=$row_ficha_tecnica['n_ft']; if($ft == '') { ?>
        <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a>
        <?php } else{ ?>
        <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>
        <?php } ?>
        <?php if($row_certificacion['idcc']=='') { ?>
          <a href="certificacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
          </a><?php } ?></td>
    </tr>
    <tr id="tr1">
      <td id="fuente2">FECHA REGISTRO </td>
      <td colspan="2" id="fuente2">RESPONSABLE</td>
      </tr>
    <tr>
      <td id="dato2"><input name="fecha_rev_l" type="text" id="fecha_rev_l" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
      <td colspan="2" id="dato2"><input name="responsable_rev_l" type="text" id="responsable_rev_l" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" readonly="readonly"/></td>
      </tr>
    <tr id="tr1">
      <td id="fuente2">REFERENCIA</td>
      <td id="fuente2">&nbsp;</td>
      <td id="fuente2">COTIZACION N&deg; </td>
    </tr>
    <tr>
      <td id="dato2"><strong>
        <input name="id_ref_rev_l" type="hidden" value="<?php echo $row_referencia['id_ref']; ?>" />
        <?php echo $row_referencia['cod_ref']; ?> - <?php echo $row_referencia['version_ref']; ?> </strong></td>
      <td id="dato2">&nbsp;</td>
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
      <td id="fuente1">CALIBRE</td>
      <td id="fuente1">PESO MILLAR </td>
    </tr>
    <tr>
      <td id="dato1"><?php echo $row_referencia['ancho_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['largo_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['calibre_ref']; ?></td>
      <td id="dato1"><?php echo $row_referencia['peso_millar_ref']; ?></td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">NUMERO DE PISTAS</td>
      <td id="fuente1">RODILLO</td>
      <td id="fuente1">TRATAMIENTO</td>
      <td id="fuente1">IMPRESION COLORES</td>
    </tr>
    <tr>
      <td id="dato1"><input type="text" name="int_numero_p_l" id="int_numero_p_l" /></td>
      <td id="dato1"><input type="text" name="int_rodillo_l" id="int_rodillo_l" /></td>
      <td id="dato1"><select name="b_tratamiento_l" id="b_tratamiento_l">
        <option value="0">NO</option>
        <option value="1">SI</option>
        </select></td>
      <td id="dato1"><?php echo $row_referencia['impresion_ref']; ?></td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">ANCHO TOTAL</td>
      <td id="fuente1">REPETICION</td>
      <td colspan="2" id="fuente1">EMBOBINADO
        <input type="hidden" name="int_embob_rev_l" id="int_embob_rev_l" value="<?php echo $row_referencia['N_embobinado_l']; ?>" /></td>
      </tr>
    <tr>
      <td id="dato1"><input type="text" name="int_anchot_l" id="int_anchot_l" /></td>
      <td id="dato1"><input type="text" name="int_repeticion_l" id="int_repeticion_l" /></td>
      <td colspan="2" rowspan="2" id="detalle1"><?php switch($row_referencia['N_embobinado_l']) {
	  case 0: echo "VACIO"; break;
	  case 1: ?>
    <img src="images/embobinado1.gif">
    <?php break;
	  case 2: ?>
    <img src="images/embobinado2.gif">
    <?php break;
	  case 3: ?>
    <img src="images/embobinado3.gif">
    <?php break;
	  case 4: ?>
    <img src="images/embobinado4.gif">
    <?php break;
	  case 5: ?>
    <img src="images/embobinado5.gif">
    <?php break;
	  case 6: ?>
    <img src="images/embobinado6.gif">
    <?php break;
	  case 7: ?>
    <img src="images/embobinado7.gif">
    <?php break;
	  case 8: ?>
    <img src="images/embobinado8.gif">
    <?php break;
	  case 9: ?>
    <img src="images/embobinado9.gif">
    <?php break;
	  case 10: ?>
    <img src="images/embobinado10.gif">
    <?php break;
	  case 11: ?>
    <img src="images/embobinado11.gif">
    <?php break;
	  case 12: ?>
    <img src="images/embobinado12.gif">
    <?php break;
	  case 13: ?>
    <img src="images/embobinado13.gif">
    <?php break;
	  case 14: ?>
    <img src="images/embobinado14.gif">
    <?php break;
	  case 15: ?>
    <img src="images/embobinado15.gif">
    <?php break;
	  case 16: ?>
    <img src="images/embobinado16.gif">
    <?php break;
	  } ?></td>
      </tr>
    <tr>
      <td colspan="2" id="dato3">&nbsp;</td>
      </tr>
    <tr id="tr1">
      <td colspan="4" id="titulo4">DATOS ESPECIFICOS DE LA REFERENCIA</td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">TIPO EXTRUSION </td>
      <td id="fuente1">PIGMENTO EXTERIOR</td>
      <td colspan="2" id="fuente1">PIGMENTO INTERIOR </td>
      </tr>
    <tr>
      <td id="dato1"><?php echo $row_referencia['tipo_ext_egp']; ?></td>
      <td id="dato1"><?php echo $row_referencia['pigm_ext_egp']; ?></td>
      <td colspan="2" id="dato1"><?php echo $row_referencia['pigm_int_epg']; ?></td>
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
      <td colspan="4" id="fuente2"><strong>CODIGO DE BARRAS </strong></td>
      </tr>
    <tr>
      <td id="detalle3">PREIMPRESO</td>
      <td id="detalle1"><select name="b_preimp_l" id="b_preimp_l">
        <option value="0">NO</option>
        <option value="1">SI</option>
      </select></td>
      <td id="detalle3">NUMERO</td>
      <td id="detalle1"><input type="text" name="int_numero_l" id="int_numero_l" /></td>
      </tr>
    <tr id="tr1">
      <td colspan="4" id="titulo4">OBSERVACIONES <strong>CODIGO DE BARRAS</strong></td>
    </tr>
    <tr >
      <td colspan="4" id="dato2"><textarea name="str_obs_codigo_l" cols="80" rows="3"></textarea></td>
      </tr>
    <tr id="tr1">
      <td colspan="4" id="titulo4">ARTE</td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_referencia['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="b_recibir_muestra_rev_l" type="checkbox" value="1">
Se recibe bosquejo o muestra fisica del cliente. </td>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_referencia['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="b_recibir_artes_rev_l" type="checkbox" value="1">
Se recibe arte completo del cliente o logos.</td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input name="b_recibir_textos_rev_l" type="checkbox" value="1">
Se reciben solo textos por el cliente.</td>
      <td colspan="2" id="detalle1"><input <?php if (!(strcmp($row_referencia['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="b_orientacion_total_arte_rev_l" type="checkbox" value="1" />
        Se solicita orientaci&oacute;n total en el arte.</td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1"><input name="b_entregar_arte_elong_rev_l" type="checkbox" value="1" />
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
      <td colspan="4" id="dato2"><textarea name="str_obs_general_l" cols="80" rows="3"><?php echo $row_referencia['observacion5_egp']; ?></textarea></td>
      </tr>
    <tr>
      <td colspan="4" id="dato2"><input name="fecha_actualizado_rev_l" type="hidden" value="" />
        <input name="actualizado_rev_l" type="hidden" value="" />
        <input type="submit" value="ADD REVISION"></td>
      </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
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

//mysql_free_result($ultimo);

//mysql_free_result($referencia);

//mysql_free_result($validacion);

//mysql_free_result($ficha_tecnica);
?>
