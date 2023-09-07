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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE egl SET responsable_egl=%s, codigo_usuario_egl=%s, fecha_egl=%s, hora_egl=%s, estructura_egl=%s, pigm_ext_egl=%s, pigm_int_egl=%s, ancho_egl=%s, calibre_egl=%s, peso_egl=%s, diametro_rollo_egl=%s, tto_corona_egl=%s, tipo_empaque_egl=%s, observ_material_egl=%s, repeticion_egl=%s, rodillo_egl=%s, codigo_barras_egl=%s, fotocelda_egl=%s, observ_impresion_egl=%s, arte_cliente_egl=%s, disenador_egl=%s, telefono_disenador_egl=%s, archivos_montaje_egl=%s, orientacion_arte_egl=%s, fecha_arte_egl=%s, observ_arte_egl=%s, embobinado_egl=%s, peso_rollo_egl=%s, ident_rollo_egl=%s, lugar_entrega_egl=%s, id_vendedor_egl=%s, observ_despacho_egl=%s, estado_egl=%s, cotiz_egl=%s, modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s WHERE n_egl=%s",
                       GetSQLValueString($_POST['responsable_egl'], "text"),
                       GetSQLValueString($_POST['codigo_usuario_egl'], "text"),
                       GetSQLValueString($_POST['fecha_egl'], "date"),
                       GetSQLValueString($_POST['hora_egl'], "text"),
                       GetSQLValueString($_POST['estructura_egl'], "text"),
                       GetSQLValueString($_POST['pigm_ext_egl'], "text"),
                       GetSQLValueString($_POST['pigm_int_egl'], "text"),
                       GetSQLValueString($_POST['ancho_egl'], "double"),
                       GetSQLValueString($_POST['calibre_egl'], "double"),
                       GetSQLValueString($_POST['peso_egl'], "double"),
                       GetSQLValueString($_POST['diametro_rollo_egl'], "double"),
                       GetSQLValueString($_POST['tto_corona_egl'], "text"),
                       GetSQLValueString($_POST['tipo_empaque_egl'], "text"),
                       GetSQLValueString($_POST['observ_material_egl'], "text"),
                       GetSQLValueString($_POST['repeticion_egl'], "int"),
                       GetSQLValueString($_POST['rodillo_egl'], "int"),
                       GetSQLValueString($_POST['codigo_barras_egl'], "text"),
                       GetSQLValueString($_POST['fotocelda_egl'], "int"),
                       GetSQLValueString($_POST['observ_impresion_egl'], "text"),
                       GetSQLValueString($_POST['arte_cliente_egl'], "int"),
                       GetSQLValueString($_POST['disenador_egl'], "text"),
                       GetSQLValueString($_POST['telefono_disenador_egl'], "text"),
                       GetSQLValueString($_POST['archivos_montaje_egl'], "int"),
                       GetSQLValueString($_POST['orientacion_arte_egl'], "int"),
                       GetSQLValueString($_POST['fecha_arte_egl'], "date"),
                       GetSQLValueString($_POST['observ_arte_egl'], "text"),
                       GetSQLValueString($_POST['embobinado_egl'], "int"),
                       GetSQLValueString($_POST['peso_rollo_egl'], "double"),
                       GetSQLValueString($_POST['ident_rollo_egl'], "text"),
                       GetSQLValueString($_POST['lugar_entrega_egl'], "text"),
                       GetSQLValueString($_POST['id_vendedor_egl'], "int"),
                       GetSQLValueString($_POST['observ_despacho_egl'], "text"),
                       GetSQLValueString($_POST['estado_egl'], "int"),
                       GetSQLValueString($_POST['cotiz_egl'], "int"),
                       GetSQLValueString($_POST['modificacion'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
                       GetSQLValueString($_POST['n_egl'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "egp_lamina_vista.php?n_egl=" . $_POST['n_egl'] . "";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_egl = "-1";
if (isset($_GET['n_egl'])) {
  $colname_egl = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_egl = sprintf("SELECT * FROM egl WHERE n_egl = %s", GetSQLValueString($colname_egl, "int"));
$egl = mysql_query($query_egl, $conexion1) or die(mysql_error());
$row_egl = mysql_fetch_assoc($egl);
$totalRows_egl = mysql_num_rows($egl);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
?>
<?php date_default_timezone_set("America/Bogota"); ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body oncontextmenu="return false">
<table id="tabla_formato"><tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
      <li><?php echo $row_usuario['nombre_usuario']; ?></li>
      <li><a href="egp_lamina.php">LISTADO EGL</a></li>
      <li><a href="egp_menu.php">MENU EGP</a></li>
      <li><a href="comercial.php">COMERCIAL</a></li>
      <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>
      </ul></div></div>
</td></tr></table>
<div align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('estructura_egl','','R','ancho_egl','','RisNum','calibre_egl','','RisNum','peso_egl','','RisNum','diametro_rollo_egl','','RisNum','tto_corona_egl','','R','repeticion_egl','','RisNum','rodillo_egl','','RisNum','peso_rollo_egl','','RisNum','lugar_entrega_egl','','R','id_vendedor_egl','','R');return document.MM_returnValue">
    <table id="tabla_formato2">
      <tr>
        <td nowrap id="codigo_formato_2">CODIGO: R1-F08</td>
        <td colspan="2" nowrap id="titulo_formato_2">ESPECIFICACION GENERAL DE LAMINAS (EGL)</td>
        <td nowrap id="codigo_formato_2">VERSION: 2</td>
      </tr>
      <tr>
        <td rowspan="6" id="logo_2"><img src="images/logoacyc.jpg"></td>
        <td colspan="2" id="numero2">Nº<strong> <?php echo $row_egl['n_egl']; ?></strong></td>
        <td id="numero2"><a href="egp_lamina_vista.php?n_egl=<?php echo $row_egl['n_egl']; ?>"><img src="images/hoja.gif" alt="VISTA" border="0" style="cursor:hand;" /></a><a href="javascript:eliminar('n_egl',<?php echo $row_egl['n_egl']; ?>,'egp_lamina_edit.php')"><img src="images/por.gif" border="0" style="cursor:hand;" alt="ELIMINAR" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" onClick="window.history.go()" /></td>
      </tr>
      <tr>
        <td colspan="2" nowrap id="nivel_2">REGISTRO INICIAL</td>
        <td nowrap id="nivel_2">ESTADO</td>
      </tr>
      <tr>
        <td colspan="2" nowrap id="detalle_2"><input name="responsable_egl" type="hidden" value="<?php echo htmlentities($row_egl['responsable_egl'], ENT_COMPAT, ''); ?>">
          <?php echo $row_egl['responsable_egl']; ?>
          <input name="codigo_usuario_egl" type="hidden" value="<?php echo htmlentities($row_egl['codigo_usuario_egl'], ENT_COMPAT, ''); ?>">
          <input name="fecha_egl" type="hidden" value="<?php echo htmlentities($row_egl['fecha_egl'], ENT_COMPAT, ''); ?>">
          <?php echo $row_egl['fecha_egl']; ?>
          <input name="hora_egl" type="hidden" value="<?php echo htmlentities($row_egl['hora_egl'], ENT_COMPAT, ''); ?>">
        <?php echo $row_egl['hora_egl']; ?></td>
        <td id="detalle_2"><input name="estado_egl" type="hidden" value="<?php echo htmlentities($row_egl['estado_egl'], ENT_COMPAT, ''); ?>">
        <?php switch($row_egl['estado_egl']) {
		      case 0: echo "PENDIENTE"; break;
			  case 1: echo "ACEPTADA"; break;
			  case 2: echo "OBSOLETA"; break; } ?></td>
      </tr>
      <tr>
        <td colspan="2" id="nivel_2">MODIFICACION</td>
        <td id="nivel_2">COTIZACION - REFERENCIA</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle_2"><input type="hidden" name="modificacion" value="<?php echo $row_usuario['nombre_usuario']; ?>"><?php echo $row_egl['modificacion']; ?>
          -
          <input type="hidden" name="fecha_modificacion" value="<?php echo date("Y/m/d"); ?>">
          <?php echo $row_egl['fecha_modificacion']; ?><input type="hidden" name="hora_modificacion" value="<?php echo date("g:i a") ?>"> <?php echo $row_egl['hora_modificacion']; ?></td>
        <td id="detalle_2"><input name="cotiz_egl" type="hidden" value="<?php echo htmlentities($row_egl['cotiz_egl'], ENT_COMPAT, ''); ?>">
        -          </td>
      </tr>
      <tr>
        <td colspan="3" id="dato_2">Alguna Inquietud o Comentario : <strong>info@acycia.com</strong></td>
      </tr>
      <tr>
        <td colspan="4" id="subtitulo2">ESPECIFICACION DEL MATERIAL</td>
      </tr>
      <tr>
        <td colspan="2" id="nivel_1">ESTRUCTURA</td>
        <td id="nivel_1">PIGMENTO EXTERIOR</td>
        <td id="nivel_1">PIGMENTO INTERIOR</td>
      </tr>
      <tr>
        <td colspan="2" id="dato_1"><select name="estructura_egl">
          <option value="COEXTRUSION PIGMENTADA" <?php if (!(strcmp("COEXTRUSION PIGMENTADA", $row_egl['estructura_egl']))) {echo "selected=\"selected\"";} ?>>COEXTRUSION PIGMENTADA</option>
          <option value="COEXTRUSION NATURAL" <?php if (!(strcmp("COEXTRUSION NATURAL", $row_egl['estructura_egl']))) {echo "selected=\"selected\"";} ?>>COEXTRUSION NATURAL</option>
          <option value="MONOCAPA PIGMENTADA" <?php if (!(strcmp("MONOCAPA PIGMENTADA", $row_egl['estructura_egl']))) {echo "selected=\"selected\"";} ?>>MONOCAPA PIGMENTADA</option>
          <option value="MONOCAPA NATURAL" <?php if (!(strcmp("MONOCAPA NATURAL", $row_egl['estructura_egl']))) {echo "selected=\"selected\"";} ?>>MONOCAPA NATURAL</option>
        </select></td>
        <td id="dato_1"><input name="pigm_ext_egl" type="text" value="<?php echo htmlentities($row_egl['pigm_ext_egl'], ENT_COMPAT, ''); ?>" size="20" maxlength="20"></td>
        <td id="dato_1"><input name="pigm_int_egl" type="text" value="<?php echo htmlentities($row_egl['pigm_int_egl'], ENT_COMPAT, ''); ?>" size="20" maxlength="20"></td>
      </tr>
      <tr>
        <td id="nivel_1">ANCHO</td>
        <td id="nivel_1">CALIBRE</td>
        <td id="nivel_1">PESO / ml</td>
        <td id="nivel_1" nowrap>DIAMETRO DEL ROLLO</td>
      </tr>
      <tr>
        <td id="dato_1"><input name="ancho_egl" type="text" id="ancho_egl" value="<?php echo htmlentities($row_egl['ancho_egl'], ENT_COMPAT, ''); ?>" size="10" maxlength="8"></td>
        <td id="dato_1"><input name="calibre_egl" type="text" id="calibre_egl" onBlur="calcular_egl()" value="<?php echo htmlentities($row_egl['calibre_egl'], ENT_COMPAT, ''); ?>" size="10" maxlength="8"></td>
        <td id="dato_1"><input name="peso_egl" type="text" id="peso_egl" value="<?php echo htmlentities($row_egl['peso_egl'], ENT_COMPAT, ''); ?>" size="10" maxlength="8"></td>
        <td id="dato_1"><input name="diametro_rollo_egl" type="text" id="diametro_rollo_egl" value="<?php echo htmlentities($row_egl['diametro_rollo_egl'], ENT_COMPAT, ''); ?>" size="10" maxlength="8"></td>
      </tr>
      <tr>
        <td colspan="2" id="nivel_1">TRATAMIENTO CORONA</td>
        <td colspan="2" id="nivel_1">TIPO DE EMPAQUE</td>
      </tr>
      <tr>
        <td colspan="2" id="dato_1"><input name="tto_corona_egl" type="text" id="tto_corona_egl" value="<?php echo htmlentities($row_egl['tto_corona_egl'], ENT_COMPAT, ''); ?>" size="20" maxlength="20"></td>
        <td colspan="2" id="dato_1"><select name="tipo_empaque_egl">
          <option value="AGUA" <?php if (!(strcmp("AGUA", $row_egl['tipo_empaque_egl']))) {echo "selected=\"selected\"";} ?>>AGUA</option>
          <option value="LECHE" <?php if (!(strcmp("LECHE", $row_egl['tipo_empaque_egl']))) {echo "selected=\"selected\"";} ?>>LECHE</option>
          <option value="INDUSTRIAL LIQUIDOS" <?php if (!(strcmp("INDUSTRIAL LIQUIDOS", $row_egl['tipo_empaque_egl']))) {echo "selected=\"selected\"";} ?>>INDUSTRIAL LIQUIDOS</option>
          <option value="INDUSTRIAL A GRANEL" <?php if (!(strcmp("INDUSTRIAL A GRANEL", $row_egl['tipo_empaque_egl']))) {echo "selected=\"selected\"";} ?>>INDUSTRIAL A GRANEL</option>
          <option value="OTROS" <?php if (!(strcmp("OTROS", $row_egl['tipo_empaque_egl']))) {echo "selected=\"selected\"";} ?>>OTROS</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="4" id="nivel_1">OBSERVACIONES</td>
      </tr>
      <tr>
        <td colspan="4" id="dato_1"><textarea name="observ_material_egl" cols="80" rows="2"><?php echo htmlentities($row_egl['observ_material_egl'], ENT_COMPAT, ''); ?></textarea></td>
      </tr>
      <tr>
        <td colspan="4" id="subtitulo2">ESPECIFICACION DE LA IMPRESION</td>
      </tr>
      <tr>
        <td id="nivel_1">N&ordm; REPETICIONES</td>
        <td id="nivel_1">RODILLO N&ordm;</td>
        <td id="nivel_1">CODIGO DE BARRAS</td>
        <td id="detalle_1"><input <?php if (!(strcmp($row_egl['fotocelda_egl'],1))) {echo "checked=\"checked\"";} ?> name="fotocelda_egl" type="checkbox" value="1">
        FOTOCELDA</td>
      </tr>
      <tr>
        <td id="dato_1"><input name="repeticion_egl" type="text" id="repeticion_egl" value="<?php echo htmlentities($row_egl['repeticion_egl'], ENT_COMPAT, ''); ?>" size="5" maxlength="5"></td>
        <td id="dato_1"><input name="rodillo_egl" type="text" id="rodillo_egl" value="<?php echo htmlentities($row_egl['rodillo_egl'], ENT_COMPAT, ''); ?>" size="5" maxlength="5"></td>
        <td id="dato_1"><input type="text" name="codigo_barras_egl" value="<?php echo htmlentities($row_egl['codigo_barras_egl'], ENT_COMPAT, ''); ?>" size="20"></td>
        <td id="detalle_1"><a href="javascript:" onClick="window.open('egp_lamina_colores.php?n_egl=<?php echo $row_egl['n_egl']; ?>','','height=200,width=550,scrollbars=1')">COLORES</a></td>
      </tr>
      <tr>
        <td colspan="4" id="nivel_1">OBSERVACIONES</td>
      </tr>
      <tr>
        <td colspan="4" id="dato_1"><textarea name="observ_impresion_egl" cols="80" rows="2"><?php echo htmlentities($row_egl['observ_impresion_egl'], ENT_COMPAT, ''); ?></textarea></td>
      </tr>
      <tr>
        <td colspan="4" id="subtitulo2">ESPECIFICACION DEL ARTE</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle_1"><input <?php if (!(strcmp($row_egl['arte_cliente_egl'],1))) {echo "checked=\"checked\"";} ?> name="arte_cliente_egl" type="checkbox" value="1">
          Arte suministrado por el cliente</td>
        <td colspan="2" id="nivel_1">DISEÑADOR</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle_1"><input <?php if (!(strcmp($row_egl['archivos_montaje_egl'],1))) {echo "checked=\"checked\"";} ?> name="archivos_montaje_egl" type="checkbox" value="1">
          El cliente entrega archivos para montaje</td>
        <td colspan="2" id="dato_1"><input name="disenador_egl" type="text" value="<?php echo htmlentities($row_egl['disenador_egl'], ENT_COMPAT, ''); ?>" size="30" maxlength="50"></td>
      </tr>
      <tr>
        <td colspan="2" id="detalle_1"><input <?php if (!(strcmp($row_egl['orientacion_arte_egl'],1))) {echo "checked=\"checked\"";} ?> name="orientacion_arte_egl" type="checkbox" value="1">
          Solicita orientacion en el arte</td>
        <td id="nivel_1">TELEFONO</td>
        <td id="nivel_1">FECHA DEL ARTE</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle_1"><a href="javascript:" onClick="window.open('egp_lamina_archivos.php?n_egl=<?php echo $row_egl['n_egl']; ?>','','height=200,width=350,scrollbars=1')">ARCHIVOS ADJUNTOS</a></td>
        <td id="dato_1"><input name="telefono_disenador_egl" type="text" value="<?php echo htmlentities($row_egl['telefono_disenador_egl'], ENT_COMPAT, ''); ?>" size="20" maxlength="20"></td>
        <td id="dato_1"><input type="text" name="fecha_arte_egl" value="<?php echo htmlentities($row_egl['fecha_arte_egl'], ENT_COMPAT, ''); ?>" size="10"></td>
      </tr>
      <tr>
        <td colspan="4" id="nivel_1">OBSERVACIONES</td>
      </tr>
      <tr>
        <td colspan="4" id="dato_1"><textarea name="observ_arte_egl" cols="80" rows="2"><?php echo htmlentities($row_egl['observ_arte_egl'], ENT_COMPAT, ''); ?></textarea></td>
      </tr>
      <tr>
        <td colspan="4" id="subtitulo2">ESPECIFICACION DEL DESPACHO</td>
      </tr>
      <tr>
        <td colspan="4" id="nivel_1">EMBOBINADO</td>
      </tr>
      <tr>
        <td id="logo_2"><img src="images/embobinado1.gif"></td>
        <td id="logo_2"><img src="images/embobinado2.gif"></td>
        <td id="logo_2"><img src="images/embobinado3.gif"></td>
        <td id="logo_2"><img src="images/embobinado4.gif"></td>
      </tr>
      <tr>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"1"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="1" checked></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"2"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="2"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"3"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="3"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"4"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="4"></td>
      </tr>
      <tr>
        <td id="logo_2"><img src="images/embobinado5.gif"></td>
        <td id="logo_2"><img src="images/embobinado6.gif"></td>
        <td id="logo_2"><img src="images/embobinado7.gif"></td>
        <td id="logo_2"><img src="images/embobinado8.gif"></td>
      </tr>
      <tr>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"5"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="5"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"6"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="6"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"7"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="7"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"8"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="8"></td>
      </tr>
      <tr>
        <td id="logo_2"><img src="images/embobinado9.gif"></td>
        <td id="logo_2"><img src="images/embobinado10.gif"></td>
        <td id="logo_2"><img src="images/embobinado11.gif"></td>
        <td id="logo_2"><img src="images/embobinado12.gif"></td>
      </tr>
      <tr>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"9"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="9"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"10"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="10"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"11"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="11"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"12"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="12"></td>
      </tr>
      <tr>
        <td id="logo_2"><img src="images/embobinado13.gif"></td>
        <td id="logo_2"><img src="images/embobinado14.gif"></td>
        <td id="logo_2"><img src="images/embobinado15.gif"></td>
        <td id="logo_2"><img src="images/embobinado16.gif"></td>
      </tr>
      <tr>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"13"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="13"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"14"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="14"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"15"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="15"></td>
        <td id="dato_2"><input <?php if (!(strcmp($row_egl['embobinado_egl'],"16"))) {echo "checked=\"checked\"";} ?> name="embobinado_egl" type="radio" value="16"></td>
      </tr>
      <tr>
        <td id="nivel_1">PESO / ROLLO</td>
        <td id="nivel_1">IDENT. DE ROLLOS</td>
        <td id="nivel_1">LUGAR DE ENTREGA</td>
        <td id="nivel_1">VENDEDOR        </td>
      </tr>
      <tr>
        <td id="dato_1"><input name="peso_rollo_egl" type="text" id="peso_rollo_egl" value="<?php echo htmlentities($row_egl['peso_rollo_egl'], ENT_COMPAT, ''); ?>" size="8" maxlength="8"></td>
        <td id="dato_1"><input name="ident_rollo_egl" type="text" value="<?php echo htmlentities($row_egl['ident_rollo_egl'], ENT_COMPAT, ''); ?>" size="20" maxlength="20"></td>
        <td id="dato_1"><input name="lugar_entrega_egl" type="text" id="lugar_entrega_egl" value="<?php echo htmlentities($row_egl['lugar_entrega_egl'], ENT_COMPAT, ''); ?>" size="20" maxlength="50"></td>
        <td id="dato_1"><label>
          <select name="id_vendedor_egl" id="id_vendedor_egl">
            <option value="" <?php if (!(strcmp("", $row_egl['id_vendedor_egl']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
            <?php
do {  
?>
            <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $row_egl['id_vendedor_egl']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
            <?php
} while ($row_vendedores = mysql_fetch_assoc($vendedores));
  $rows = mysql_num_rows($vendedores);
  if($rows > 0) {
      mysql_data_seek($vendedores, 0);
	  $row_vendedores = mysql_fetch_assoc($vendedores);
  }
?>
          </select>
        </label></td>
      </tr>
      <tr>
        <td colspan="4" id="nivel_1">OBSERVACIONES</td>
      </tr>
      <tr>
        <td colspan="4" id="dato_1"><textarea name="observ_despacho_egl" cols="80" rows="2"><?php echo htmlentities($row_egl['observ_despacho_egl'], ENT_COMPAT, ''); ?></textarea></td>
      </tr>
      <tr>
        <td colspan="4" id="dato_2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" id="dato_2"><input type="submit" value="GUARDAR Y FINALIZAR"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="n_egl" value="<?php echo $row_egl['n_egl']; ?>">
  </form>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($egl);

mysql_free_result($vendedores);
?>