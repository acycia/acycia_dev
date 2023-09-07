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
$query_referencia_editar = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref = %s", $colname_referencia_editar);
$referencia_editar = mysql_query($query_referencia_editar, $conexion1) or die(mysql_error());
$row_referencia_editar = mysql_fetch_assoc($referencia_editar);
$totalRows_referencia_editar = mysql_num_rows($referencia_editar);

$colname_ver_egp = "-1";
if (isset($_GET['n_egp'])) {
  $colname_ver_egp = (get_magic_quotes_gpc()) ? $_GET['n_egp'] : addslashes($_GET['n_egp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_egp = sprintf("SELECT * FROM Tbl_egp WHERE n_egp = %s", $colname_ver_egp);
$ver_egp = mysql_query($query_ver_egp, $conexion1) or die(mysql_error());
$row_ver_egp = mysql_fetch_assoc($ver_egp);
$totalRows_ver_egp = mysql_num_rows($ver_egp);

$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT * FROM Tbl_verificacion_packing WHERE id_ref_verif_p = %s AND estado_arte_verif_p = '2'", $colname_ref_verif);
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
        <td colspan="4" id="titulo2">REFERENCIA ( PACKING LIST) </td>
        </tr>
      <tr>
        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="3" id="dato3"><a href="referencia_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" /></a><a href="javascript:eliminar('id_ref',<?php echo $row_referencia_editar['id_ref']; ?>,'referencia_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0"></a><a href="referencia_cliente.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" border="0"></a><a href="disenoydesarrollo.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISEÑO Y DESARROLLO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><?php $ref=$row_referencia_editar['id_ref'];
	  $sqlrevision="SELECT * FROM Tbl_revision_packing WHERE id_ref_rev_p='$ref'";
	  $resultrevision= mysql_query($sqlrevision);
	  $row_revision = mysql_fetch_assoc($resultrevision);
	  $numrev= mysql_num_rows($resultrevision);
	  if($numrev >='1')
	  { ?><a href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev_p']; ?>" target="_top" ><img src="images/r.gif" alt="REVISION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="revision_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/r.gif" alt="ADD REVISION" border="0" style="cursor:hand;" /></a><?php } ?><a href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" border="0" style="cursor:hand;" /></a><?php $ref=$row_referencia_editar['id_ref'];
	  $sqlval="SELECT * FROM Tbl_validacion_packing WHERE id_ref_val_p='$ref'";
	  $resultval= mysql_query($sqlval);
	  $row_val = mysql_fetch_assoc($resultval);
	  $numval= mysql_num_rows($resultval);
	  if($numval >='1')
	  { ?><a href="validacion_vista.php?id_val=<?php echo $row_val['id_val_p']; ?>"><img src="images/v.gif" alt="VALIDACION" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="validacion_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/v.gif" alt="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } $ref=$row_referencia_editar['id_ref'];
	  $sqlft="SELECT * FROM TblFichaTecnica WHERE id_ref_ft='$ref'";
	  $resultft= mysql_query($sqlft);
	  $row_ft = mysql_fetch_assoc($resultft);
	  $numft= mysql_num_rows($resultft);
	  if($numft >='1')
	  { ?><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else { ?><a href="ficha_tecnica_add.php?id_ref=<?php echo $ref; ?>" target="_top"><img src="images/f.gif" alt="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?></td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="dato2">Fecha Ingreso 
          <input name="fecha_registro1_ref" type="text" id="fecha_b" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
        <td colspan="2" id="dato3">
          Ingresado por          
          <input name="registro1_ref" type="text" value="<?php echo $row_referencia_editar['registro1_ref']; ?>" size="27" /></td>
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
        <td id="dato2"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile_p'];?>','610','490')"> <?php echo $row_ref_verif['userfile_p']; ?> </a> </td>
      </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="fuente2">Cotizaci&oacute;n N&ordm;</td>
        <td nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td id="fuente2">Fecha Arte</td>
      </tr>
      <tr>
        <td id="dato2"><input name="n_cotiz_ref" type="text" value="<?php echo $row_referencia_editar['n_cotiz_ref']; ?>" size="5" /></td>
        <td id="dato2">&nbsp;</td>
        <td id="dato2"><?php echo $row_ref_verif['fecha_aprob_arte_verif_p']; ?></td>
      </tr>
      <tr id="tr2">
        <td colspan="4" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">ANCHO</td>
        <td id="fuente1">LARGO</td>
        <td id="fuente1">CALIBRE</td>
        <td id="fuente1">Boca de Entrada:</td>
      </tr>
      <tr>
        <td id="dato1"><input name="ancho_ref" type="text" value="<?php echo $row_referencia_editar['ancho_ref']; ?>" size="10" /></td>
        <td id="dato1"><input name="largo_ref" type="text" value="<?php echo $row_referencia_editar['largo_ref']; ?>" size="10" /></td>
        <td id="dato1"><input type="text" name="calibre_ref" value="<?php echo $row_referencia_editar['calibre_ref']; ?>" size="10" /></td>
        <td id="dato1"><select name="Str_boca_entr" id="Str_boca_entr">
          <option>*</option>
          <option value="HORIZONTAL"<?php if (!(strcmp("HORIZONTAL", $row_packing['Str_boca_entrada']))) {echo "selected=\"selected\"";} ?>>HORIZONTAL</option>
          <option value="VERTICAL"<?php if (!(strcmp("VERTICAL", $row_packing['Str_boca_entrada']))) {echo "selected=\"selected\"";} ?>>VERTICAL</option>
        </select></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">Ubicacion de la Entrada:</td>
        <td id="fuente1">Lamina 1 (Adhesivo)</td>
        <td id="fuente1">Lamina 2</td>
      </tr>
      <tr>
        <td id="dato1">&nbsp;</td>
        <td id="dato1"><select name="Str_entrada_p" id="Str_entrada_p">
          <option>*</option>
          <option value="ANVERSO"<?php if (!(strcmp("ANVERSO", $row_packing['Str_ubica_entrada']))) {echo "selected=\"selected\"";} ?>>ANVERSO</option>
          <option value="REVERSO"<?php if (!(strcmp("REVERSO", $row_packing['Str_ubica_entrada']))) {echo "selected=\"selected\"";} ?>>REVERSO</option>
        </select></td>
        <td id="dato1"><select name="Str_lamina1_p" id="Str_lamina1_p">
          <option>*</option>
          <option value="PIGMENTADO"<?php if (!(strcmp("PIGMENTADO", $row_packing['Str_lam1']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO</option>
          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_packing['Str_lam1']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
        </select></td>
        <td id="dato1"><select name="Str_lamina2_p" id="Str_lamina2_p">
          <option>*</option>
          <option value="PIGMENTADO"<?php if (!(strcmp("PIGMENTADO", $row_packing['Str_lam1']))) {echo "selected=\"selected\"";} ?>>PIGMENTADO</option>
          <option value="TRANSPARENTE"<?php if (!(strcmp("TRANSPARENTE", $row_packing['Str_lam2']))) {echo "selected=\"selected\"";} ?>>TRANSPARENTE</option>
        </select></td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">IMPRESION</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><input type="text" name="impresion_ref" value="<?php echo $row_referencia_editar['impresion_ref']; ?>" size="30" /></td>
        <td colspan="2" id="dato1">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1">&nbsp;</td>
        <td colspan="2" id="dato1">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="3" id="dato1">Ultima Actualizaci&oacute;n : 
          <input name="registro2_ref" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['registro2_ref']; ?>
           <input name="fecha_registro2_ref" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
           <?php echo $row_referencia_editar['fecha_registro2_ref']; ?>
           <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" /></td>
        <td id="dato2">&nbsp;</td>
      </tr>
    </table>
      
        
        <table id="tabla2">
      <tr id="tr2">
        <td colspan="3" id="titulo4">DATOS ESPECIFICOS DE LA REFERENCIA</td>
        </tr>
      <tr>
        <td id="fuente1">Colores</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1"></td>
      </tr>
      <tr>
        <td id="dato1"><select name="N_colores_impresion" id="N_colores_impresion"tag="tag" tabindex="1\">
          <option>Colores</option>
          <option value="1"<?php if (!(strcmp("1", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>1</option>
          <option value="2"<?php if (!(strcmp("2", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>2</option>
          <option value="3"<?php if (!(strcmp("3", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>3</option>
          <option value="4"<?php if (!(strcmp("4", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>4</option>
          <option value="5"<?php if (!(strcmp("5", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>5</option>
          <option value="6"<?php if (!(strcmp("6", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>6</option>
          <option value="7"<?php if (!(strcmp("7", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>7</option>
          <option value="8"<?php if (!(strcmp("8", $row_bolsa['N_colores_impresion']))) {echo "selected=\"selected\"";} ?>>8</option>
        </select></td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">TIPO DE BOLSA </td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1"></td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="tipo_bolsa_egp" value="<?php echo $row_ver_egp['tipo_bolsa_egp']; ?>" size="20"></td>
        <td id="dato1">&nbsp;</td>
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
        <td id="fuente1">Color 7</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color7_egp" value="<?php echo $row_ver_egp['color7_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone7_egp" value="<?php echo $row_ver_egp['pantone7_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion7_egp" value="<?php echo $row_ver_egp['ubicacion7_egp']; ?>" size="20" /></td>
      </tr>  
      <tr id="tr1">
        <td id="fuente1">Color 8</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color8_egp" value="<?php echo $row_ver_egp['color8_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone8_egp" value="<?php echo $row_ver_egp['pantone8_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion8_egp" value="<?php echo $row_ver_egp['ubicacion8_egp']; ?>" size="20" /></td>
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
        <td id="dato2"><input name="submit" type="submit" value="Actualizar REFERENCIA" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="id_ref" value="<?php echo $row_referencia_editar['id_ref']; ?>">
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
