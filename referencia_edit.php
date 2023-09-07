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
  $updateSQL = sprintf("UPDATE referencia SET cod_ref=%s, version_ref=%s, n_egp_ref=%s, n_cotiz_ref=%s, tipo_bolsa_ref=%s, material_ref=%s, ancho_ref=%s, largo_ref=%s, solapa_ref=%s, bolsillo_guia_ref=%s, calibre_ref=%s, peso_millar_ref=%s, impresion_ref=%s, num_pos_ref=%s, cod_form_ref=%s, adhesivo_ref=%s, estado_ref=%s, registro1_ref=%s, fecha_registro1_ref=%s, registro2_ref=%s, fecha_registro2_ref=%s WHERE id_ref=%s",
                       GetSQLValueString($_POST['cod_ref'], "text"),
                       GetSQLValueString($_POST['version_ref'], "text"),
                       GetSQLValueString($_POST['n_egp_ref'], "int"),
                       GetSQLValueString($_POST['n_cotiz_ref'], "int"),
                       GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),
                       GetSQLValueString($_POST['material_ref'], "text"),
                       GetSQLValueString($_POST['ancho_ref'], "double"),
                       GetSQLValueString($_POST['largo_ref'], "double"),
                       GetSQLValueString($_POST['solapa_ref'], "double"),
                       GetSQLValueString($_POST['bolsillo_guia_ref'], "double"),
                       GetSQLValueString($_POST['calibre_ref'], "double"),
                       GetSQLValueString($_POST['peso_millar_ref'], "double"),
                       GetSQLValueString($_POST['impresion_ref'], "text"),
                       GetSQLValueString($_POST['num_pos_ref'], "text"),
                       GetSQLValueString($_POST['cod_form_ref'], "text"),
                       GetSQLValueString($_POST['adhesivo_ref'], "text"),
                       GetSQLValueString($_POST['estado_ref'], "int"),
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
                       GetSQLValueString($_POST['registro2_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro2_ref'], "date"),
                       GetSQLValueString($_POST['id_ref'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "referencia_vista.php?id_ref=" . $_POST['id_ref'] . "&tipo=" . $_POST['tipo_usuario'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE egp SET tipo_ext_egp=%s, pigm_ext_egp=%s, pigm_int_epg=%s, tipo_bolsa_egp=%s, tipo_sello_egp=%s, color1_egp=%s, pantone1_egp=%s, ubicacion1_egp=%s, color2_egp=%s, pantone2_egp=%s, ubicacion2_egp=%s, color3_egp=%s, pantone3_egp=%s, ubicacion3_egp=%s, color4_egp=%s, pantone4_egp=%s, ubicacion4_egp=%s, color5_egp=%s, pantone5_egp=%s, ubicacion5_egp=%s, color6_egp=%s, pantone6_egp=%s, ubicacion6_egp=%s, tipo_solapatr_egp=%s, tipo_cinta_egp=%s, tipo_principal_egp=%s, tipo_inferior_egp=%s, cb_solapatr_egp=%s, cb_cinta_egp=%s, cb_principal_egp=%s, cb_inferior_egp=%s, fecha_cad_egp=%s, arte_sum_egp=%s, ent_logo_egp=%s, orient_arte_egp=%s, responsable_modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s WHERE n_egp=%s",
                       GetSQLValueString($_POST['tipo_ext_egp'], "text"),
                       GetSQLValueString($_POST['pigm_ext_egp'], "text"),
                       GetSQLValueString($_POST['pigm_int_epg'], "text"),
                       GetSQLValueString($_POST['tipo_bolsa_egp'], "text"),
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
                       GetSQLValueString($_POST['tipo_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['tipo_cinta_egp'], "text"),
                       GetSQLValueString($_POST['tipo_principal_egp'], "text"),
                       GetSQLValueString($_POST['tipo_inferior_egp'], "text"),
                       GetSQLValueString($_POST['cb_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['cb_cinta_egp'], "text"),
                       GetSQLValueString($_POST['cb_principal_egp'], "text"),
                       GetSQLValueString($_POST['cb_inferior_egp'], "text"),
                       GetSQLValueString($_POST['fecha_cad_egp'], "int"),
                       GetSQLValueString($_POST['arte_sum_egp'], "int"),
                       GetSQLValueString($_POST['ent_logo_egp'], "int"),
                       GetSQLValueString($_POST['orient_arte_egp'], "int"),
                       GetSQLValueString($_POST['responsable_modificacion'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
                       GetSQLValueString($_POST['n_egp'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "referencia_vista.php?id_ref=" . $_POST['id_ref'] . "&tipo=" . $_POST['tipo_usuario'] . "";
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

$colname_referencia_editar = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_editar = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_editar = sprintf("SELECT * FROM referencia WHERE id_ref = %s", $colname_referencia_editar);
$referencia_editar = mysql_query($query_referencia_editar, $conexion1) or die(mysql_error());
$row_referencia_editar = mysql_fetch_assoc($referencia_editar);
$totalRows_referencia_editar = mysql_num_rows($referencia_editar);

$colname_ver_egp = "-1";
if (isset($_GET['n_egp'])) {
  $colname_ver_egp = (get_magic_quotes_gpc()) ? $_GET['n_egp'] : addslashes($_GET['n_egp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_egp = sprintf("SELECT * FROM egp WHERE n_egp = %s", $colname_ver_egp);
$ver_egp = mysql_query($query_ver_egp, $conexion1) or die(mysql_error());
$row_ver_egp = mysql_fetch_assoc($ver_egp);
$totalRows_ver_egp = mysql_num_rows($ver_egp);

$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s AND estado_arte_verif = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);
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
	<li><a href="disenoydesarrollo.php">DISEÑOYDESARROLLO</a></li>	
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table id="tabla2">
      <tr id="tr2">
        <td colspan="4" id="titulo2">REFERENCIA ( BOLSA PLASTICA ) </td>
        </tr>
      <tr>
        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="3" id="dato1"><a href="referencia_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" /></a><a href="javascript:eliminar('id_ref',<?php echo $row_referencia_editar['id_ref']; ?>,'referencia_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0"></a><a href="referencia_cliente.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" border="0"></a><a href="disenoydesarrollo.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISEÑO Y DESARROLLO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><?php $ref=$row_referencia_editar['id_ref'];
	  $sqlrevision="SELECT * FROM revision WHERE id_ref_rev='$ref'";
	  $resultrevision= mysql_query($sqlrevision);
	  $row_revision = mysql_fetch_assoc($resultrevision);
	  $numrev= mysql_num_rows($resultrevision);
	  if($numrev >='1')
	  { ?><a href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev']; ?>" target="_top" ><img src="images/r.gif" alt="REVISION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="revision_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/r.gif" alt="ADD REVISION" border="0" style="cursor:hand;" /></a><?php } ?><a href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" border="0" style="cursor:hand;" /></a><?php $ref=$row_referencia_editar['id_ref'];
	  $sqlval="SELECT * FROM validacion WHERE id_ref_val='$ref'";
	  $resultval= mysql_query($sqlval);
	  $row_val = mysql_fetch_assoc($resultval);
	  $numval= mysql_num_rows($resultval);
	  if($numval >='1')
	  { ?><a href="validacion_vista.php?id_val=<?php echo $row_val['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="validacion_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/v.gif" alt="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } $ref=$row_referencia_editar['id_ref'];
	  $sqlft="SELECT * FROM ficha_tecnica WHERE id_ref_ft='$ref'";
	  $resultft= mysql_query($sqlft);
	  $row_ft = mysql_fetch_assoc($resultft);
	  $numft= mysql_num_rows($resultft);
	  if($numft >='1')
	  { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/f.gif" alt="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?></td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="dato2">Fecha Ingreso <input name="fecha_registro1_ref" type="text" value="<?php echo $row_referencia_editar['fecha_registro1_ref']; ?>" size="10" /></td>
        <td colspan="2" id="dato2">Ingresado por          
          <input name="registro1_ref" type="text" value="<?php echo $row_referencia_editar['registro1_ref']; ?>" size="30" /></td>
        </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><strong>REFERENCIA - VERSION</strong></td>
        <td nowrap="nowrap" id="fuente2">Estado</td>
        <td id="fuente2">Arte</td>
      </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><input name="cod_ref" type="text" value="<?php echo $row_referencia_editar['cod_ref']; ?>" size="5" /> 
          - 
            <input name="version_ref" type="text" value="<?php echo $row_referencia_editar['version_ref']; ?>" size="2" /></td>
        <td id="fuente2"><select name="estado_ref" id="estado_ref">
          <option value="0" <?php if (!(strcmp(0, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Inactiva</option><option value="1" <?php if (!(strcmp(1, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Activa</option>
        </select></td>
        <td id="dato2"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"> <?php echo $row_ref_verif['userfile']; ?> </a> </td>
      </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="fuente2">EGP N&ordm;</td>
        <td nowrap="nowrap" id="fuente2">Cotizaci&oacute;n N&ordm;</td>
        <td id="fuente2">Fecha Arte</td>
      </tr>
      <tr>
        <td id="dato2"><input name="n_egp_ref" type="text" value="<?php echo $row_referencia_editar['n_egp_ref']; ?>" size="5" /></td>
        <td id="dato2"><input name="n_cotiz_ref" type="text" value="<?php echo $row_referencia_editar['n_cotiz_ref']; ?>" size="5" /></td>
        <td id="dato2"><?php echo $row_ref_verif['fecha_aprob_arte_verif']; ?></td>
      </tr>
      <tr id="tr2">
        <td colspan="4" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">ANCHO</td>
        <td id="fuente1">LARGO</td>
        <td id="fuente1">SOLAPA</td>
        <td id="fuente1">BOLSILLO PORTAGUIA </td>
      </tr>
      <tr>
        <td id="dato1"><input name="ancho_ref" type="text" value="<?php echo $row_referencia_editar['ancho_ref']; ?>" size="10" /></td>
        <td id="dato1"><input name="largo_ref" type="text" value="<?php echo $row_referencia_editar['largo_ref']; ?>" size="10" /></td>
        <td id="dato1"><input name="solapa_ref" type="text" value="<?php echo $row_referencia_editar['solapa_ref']; ?>" size="10" /></td>
        <td id="dato1"><input name="bolsillo_guia_ref" type="text" value="<?php echo $row_referencia_editar['bolsillo_guia_ref']; ?>" size="10" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">CALIBRE</td>
        <td id="fuente1">PESO MILLAR </td>
        <td id="fuente1">TIPO DE BOLSA </td>
        <td id="fuente1">ADHESIVO</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="calibre_ref" value="<?php echo $row_referencia_editar['calibre_ref']; ?>" size="10" /></td>
        <td id="dato1"><input type="text" name="peso_millar_ref" value="<?php echo $row_referencia_editar['peso_millar_ref']; ?>" size="10" /></td>
        <td id="dato1"><select name="tipo_bolsa_ref" id="tipo_bolsa_ref">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Seguridad" <?php if (!(strcmp("Seguridad", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>Seguridad</option>
            <option value="Currier" <?php if (!(strcmp("Currier", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>Currier</option>
            <option value="Bolsa Plastica" <?php if (!(strcmp("Bolsa Plastica", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>Bolsa Plastica</option>
                        </select></td>
        <td id="dato1"><select name="adhesivo_ref" id="adhesivo_ref">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option><option value="Cinta Permanente" <?php if (!(strcmp("Cinta Permanente", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta Permanente</option>
          <option value="Cinta Resellable" <?php if (!(strcmp("Cinta Resellable", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta Resellable</option>
          <option value="Cinta de Seguridad" <?php if (!(strcmp("Cinta de Seguridad", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta de Seguridad</option>
                </select></td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">MATERIAL</td>
        <td colspan="2" id="fuente1">IMPRESION</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="material_ref" id="material_ref">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="Ldpe coestruido pigmentado"  <?php if (!(strcmp("Ldpe coestruido pigmentado", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido pigmentado</option>
          <option value="Ldpe coestruido sin pigmentos"  <?php if (!(strcmp("Ldpe coestruido sin pigmentos", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido sin pigmentos</option>
          <option value="Ldpe monocapa sin pigmentos"  <?php if (!(strcmp("Ldpe monocapa sin pigmentos", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa sin pigmentos</option>
          <option value="Ldpe monocapa pigmentado"  <?php if (!(strcmp("Ldpe monocapa pigmentado", $row_referencia_editar['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa pigmentado</option>
                </select></td>
        <td colspan="2" id="dato1"><input type="text" name="impresion_ref" value="<?php echo $row_referencia_editar['impresion_ref']; ?>" size="30" /></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">NUMERACION &amp; POSICIONES </td>
        <td colspan="2" id="fuente1">CODIGO DE BARRAS &amp; FORMATO </td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><input type="text" name="num_pos_ref" value="<?php echo $row_referencia_editar['num_pos_ref']; ?>" size="30" /></td>
        <td colspan="2" id="dato1"><select name="barra_formato_ref" id="barra_formato_ref">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cod_form_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN 128" <?php if (!(strcmp("EAN 128", $row_referencia_editar['cod_form_ref']))) {echo "selected=\"selected\"";} ?>>EAN 128</option>
                </select></td>
        </tr>
      <tr id="tr1">
        <td colspan="3" id="dato1">Ultima Actualizaci&oacute;n : 
          <input name="registro2_ref" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['registro2_ref']; ?>
           <input name="fecha_registro2_ref" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
           <?php echo $row_referencia_editar['fecha_registro2_ref']; ?>
           <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" /></td>
        <td id="dato2"><input name="submit" type="submit" value="Actualizar REF" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id_ref" value="<?php echo $row_referencia_editar['id_ref']; ?>">
  </form></td></tr><tr><td colspan="2" align="center">
  <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
    <table id="tabla2">
      <tr id="tr2">
        <td colspan="3" id="titulo4">DATOS ESPECIFICOS DE LA REFERENCIA ( EGP ) </td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">TIPO DE EXTRUSION</td>
        <td id="fuente1">PIGMENTO EXTERIOR</td>
        <td id="fuente1">PIGMENTO INTERIOR </td>
      </tr>
      <tr>
        <td id="dato1"><select name="tipo_ext_egp" id="tipo_ext_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="Coextrusion" <?php if (!(strcmp("Coextrusion", $row_ver_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>Coextrusion</option>
            <option value="Monocapa" <?php if (!(strcmp("Monocapa", $row_ver_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>Monocapa</option>
        </select></td>
        <td id="dato1"><input type="text" name="pigm_ext_egp" value="<?php echo $row_ver_egp['pigm_ext_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pigm_int_epg" value="<?php echo $row_ver_egp['pigm_int_epg']; ?>" size="20" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">TIPO DE BOLSA </td>
        <td id="fuente1">TIPO DE SELLO </td>
        <td id="fuente1"></td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="tipo_bolsa_egp" value="<?php echo $row_ver_egp['tipo_bolsa_egp']; ?>" size="20"></td>
        <td id="dato1"><select name="tipo_sello_egp" id="tipo_sello_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Plano" <?php if (!(strcmp("Plano", $row_ver_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Plano</option>
            <option value="Hilo" <?php if (!(strcmp("Hilo", $row_ver_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Hilo</option>
            <option value="Fondo" <?php if (!(strcmp("Fondo", $row_ver_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Fondo</option>
        </select></td>
        <td id="dato1"><input <?php if (!(strcmp($row_ver_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> name="fecha_cad_egp" type="checkbox" value="1" />
          Incluye Fecha de Caducidad </td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 1 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color1_egp" value="<?php echo $row_ver_egp['color1_egp']; ?>" size="20"></td>
        <td id="dato1"><input type="text" name="pantone1_egp" value="<?php echo $row_ver_egp['pantone1_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion1_egp" value="<?php echo $row_ver_egp['ubicacion1_egp']; ?>" size="20" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 2 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color2_egp" value="<?php echo $row_ver_egp['color2_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone2_egp" value="<?php echo $row_ver_egp['pantone2_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion2_egp" value="<?php echo $row_ver_egp['ubicacion2_egp']; ?>" size="20" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 3 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color3_egp" value="<?php echo $row_ver_egp['color3_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone3_egp" value="<?php echo $row_ver_egp['pantone3_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion3_egp" value="<?php echo $row_ver_egp['ubicacion3_egp']; ?>" size="20" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 4</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color4_egp" value="<?php echo $row_ver_egp['color4_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone4_egp" value="<?php echo $row_ver_egp['pantone4_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion4_egp" value="<?php echo $row_ver_egp['ubicacion4_egp']; ?>" size="20" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 5 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color5_egp" value="<?php echo $row_ver_egp['color5_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone5_egp" value="<?php echo $row_ver_egp['pantone5_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion5_egp" value="<?php echo $row_ver_egp['ubicacion5_egp']; ?>" size="20" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 6 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color6_egp" value="<?php echo $row_ver_egp['color6_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone6_egp" value="<?php echo $row_ver_egp['pantone6_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion6_egp" value="<?php echo $row_ver_egp['ubicacion6_egp']; ?>" size="20" /></td>
      </tr>
      <tr id="tr1">
        <td id="detalle2">POSICION</td>
        <td id="detalle2">TIPO DE NUMERACION </td>
        <td id="detalle2">FORMATO &amp; CODIGO DE BARAS </td>
      </tr>
      <tr>
        <td id="detalle1">Solapa TR </td>
        <td id="detalle2"><select name="tipo_solapatr_egp" id="tipo_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_egp['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_solapatr_egp" id="cb_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Cinta</td>
        <td id="detalle2"><select name="tipo_cinta_egp" id="tipo_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_egp['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_cinta_egp" id="cb_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Principal</td>
        <td id="detalle2"><select name="tipo_principal_egp" id="tipo_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_principal_egp" id="cb_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Inferior</td>
        <td id="detalle2"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_inferior_egp" id="cb_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ver_egp['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="arte_sum_egp" type="checkbox" value="1">
          Arte suministrado por el cliente </td>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="ent_logo_egp" type="checkbox" value="1" />
          Entrego Logos de la entidad</td>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orient_arte_egp" type="checkbox" value="1" />
          Solicito orientaci&oacute;n en el arte </td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">Ultima Modificaci&oacute;n : 
          <input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_ver_egp['responsable_modificacion']; ?>
          <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_ver_egp['fecha_modificacion']; ?>
          <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a"); ?>" />
          <?php echo $row_ver_egp['hora_modificacion']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" /></td>
        <td id="dato2"><input name="submit2" type="submit" value="Actualizar EGP" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form2">
    <input type="hidden" name="n_egp" value="<?php echo $row_ver_egp['n_egp']; ?>">
  </form></td></tr></table>
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

mysql_free_result($referencia_editar);

mysql_free_result($ver_egp);

mysql_free_result($ref_verif);
?>
