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
$nombre1=$_POST['arte1'];
$nombre2=$_POST['arte2'];
$nombre3=$_POST['arte3'];
if (isset($_FILES['archivo1']) && $_FILES['archivo1']['name'] != "") {
if($nombre1 != '') {
if (file_exists("egpbolsa/".$nombre1))
{ unlink("egpbolsa/".$nombre1);	} 
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
{ unlink("egpbolsa/".$nombre2);	} 
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
{ unlink("egpbolsa/".$nombre3);	} 
}
$directorio = "egpbolsa/";
$nombre3 = $_FILES['archivo3']['name'];
$archivo_temporal = $_FILES['archivo3']['tmp_name'];
if (!copy($archivo_temporal,$directorio.$nombre3)) {
$error = "Error al enviar el Archivo";
} else { $imagen = "egpbolsa/".$nombre3; }
}
  $updateSQL = sprintf("UPDATE Tbl_egp SET responsable_egp=%s, codigo_usuario=%s, fecha_egp=%s, hora_egp=%s, estado_egp=%s, ancho_egp=%s, largo_egp=%s, solapa_egp=%s, largo_cang_egp=%s, calibre_egp=%s, tipo_ext_egp=%s, pigm_ext_egp=%s, pigm_int_epg=%s, adhesivo_egp=%s, tipo_bolsa_egp=%s, cantidad_egp=%s, tipo_sello_egp=%s, observacion1_egp=%s, color1_egp=%s, pantone1_egp=%s, ubicacion1_egp=%s, color2_egp=%s, pantone2_egp=%s, ubicacion2_egp=%s, color3_egp=%s, pantone3_egp=%s, ubicacion3_egp=%s, color4_egp=%s, pantone4_egp=%s, ubicacion4_egp=%s, color5_egp=%s, pantone5_egp=%s, ubicacion5_egp=%s, color6_egp=%s, pantone6_egp=%s, ubicacion6_egp=%s, observacion2_egp=%s, tipo_solapatr_egp=%s, tipo_cinta_egp=%s, tipo_principal_egp=%s, tipo_inferior_egp=%s, cb_solapatr_egp=%s, cb_cinta_egp=%s, cb_principal_egp=%s, cb_inferior_egp=%s, comienza_egp=%s, fecha_cad_egp=%s, observacion3_egp=%s, arte_sum_egp=%s, ent_logo_egp=%s, orient_arte_egp=%s, archivo1='$nombre1', archivo2='$nombre2', archivo3='$nombre3', disenador_egp=%s, telef_disenador_egp=%s, observacion4_egp=%s, unids_paq_egp=%s, unids_caja_egp=%s, marca_cajas_egp=%s, lugar_entrega_egp=%s, observacion5_egp=%s, responsable_modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s, vendedor=%s WHERE n_egp=%s",
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
                       GetSQLValueString($_POST['color6_egp'], "text"),
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
  $updateGoTo = "egp_bolsa_vista.php?n_egp=" . $_POST['n_egp'] . "&pageNum_egp=" . $_POST['pageNum_egp'] . "&totalRows_egp=" . $_POST['totalRows_egp'] . "";
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

$colname_egp = "-1";
if (isset($_GET['n_egp'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['n_egp'] : addslashes($_GET['n_egp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egp = sprintf("SELECT * FROM Tbl_egp WHERE n_egp = %s", $colname_egp);
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center"><table align="center" id="tabla"><tr align="center"><td>
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
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" enctype="multipart/form-data" onsubmit="MM_validateForm('ancho_egp','','RisNum','largo_egp','','RisNum','solapa_egp','','NisNum','largo_cang_egp','','NisNum','calibre_egp','','NisNum','lugar_entrega_egp','','R');return document.MM_returnValue">
    <table id="tabla2">
      <tr id="tr1">
        <td nowrap="nowrap" id="codigo">Codigo : R1 - F08 </td>
        <td nowrap="nowrap" id="titulo2">EGP - BOLSA DE SEGURIDAD </td>
        <td nowrap="nowrap" id="codigo">Versi&oacute;n : 2 </td>
      </tr>
      <tr>
        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
        <td id="numero2">N&deg; <strong><?php echo $row_egp['n_egp']; ?></strong></td>
        <td nowrap="nowrap" id="dato2"><a href="egp_bolsa_vista.php?n_egp=<?php echo $row_egp['n_egp']; ?>"><img src="images/hoja.gif" alt="VISTA" border="0" style="cursor:hand;" /></a><a href="javascript:eliminar('n_egp',<?php echo $row_egp['n_egp']; ?>,'egp_bolsa_edit.php')"><img src="images/por.gif" border="0" style="cursor:hand;" alt="ELIMINAR" /></a><?php if($row_egp['estado_egp']=='2') { ?><a href="egp_bolsa_obsoletos.php?pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp']; ?>"><img src="images/i.gif" border="0" style="cursor:hand;" alt="EGP'S INACTIVAS" /></a><?php } else { ?><a href="egp_bolsa.php?pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp']; ?>"><img src="images/a.gif" border="0" style="cursor:hand;" alt="EGP'S ACTIVAS" /></a><?php } ?><a href="egp_menu.php"><img src="images/opciones.gif" alt="MENU EGP'S" border="0" style="cursor:hand;"></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" onclick="window.history.go()" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Fecha Ingreso :          </td>
        <td nowrap="nowrap" id="fuente1">Hora Ingreso :          </td>
      </tr>
      <tr>
        <td id="fuente1"><input name="fecha_egp" type="text" value="<?php echo $row_egp['fecha_egp']; ?>" size="10" /></td>
        <td id="fuente1"><input name="hora_egp" type="text" value="<?php echo $row_egp['hora_egp']; ?>" size="10" /></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Estado :          </td>
        <td id="fuente1">REFERENCIA :          </td>
        </tr>
      <tr>
        <td id="fuente1"><select name="estado_egp" id="estado_egp">
          <option value="0" <?php if (!(strcmp(0, $row_egp['estado_egp']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
          <option value="1" <?php if (!(strcmp(1, $row_egp['estado_egp']))) {echo "selected=\"selected\"";} ?>>Aceptada</option>
          <option value="2" <?php if (!(strcmp(2, $row_egp['estado_egp']))) {echo "selected=\"selected\"";} ?>>Obsoleta</option>
        </select></td>
        <td id="fuente1"><?php
	$n_egp=$row_egp['n_egp'];
	$estado=$row_egp['estado_egp'];
	if($estado=='0' || $estado=='') 
	{ 
	echo '- -'; 
	}
	if($estado=='1')
	{
	$sql2="SELECT * FROM referencia WHERE n_egp_ref='$n_egp'";
	$result2=mysql_query($sql2);
	$num2=mysql_num_rows($result2);
	if ($num2 >= '1')
	{
	$referencia=mysql_result($result2,0,'cod_ref');
	echo $referencia;
	}
	} ?></td>
      </tr>
      <tr>
        <td id="fuente1">Ingresado por :
<input name="responsable_egp" type="hidden" value="<?php echo $row_egp['responsable_egp']; ?>" />
            <?php echo $row_egp['responsable_egp']; ?></td>
        <td id="fuente1">Tipo de Usuario :
          <input name="codigo_usuario" type="hidden" value="<?php echo $row_egp['codigo_usuario']; ?>" />
            <?php echo $row_egp['codigo_usuario']; ?></td>
      </tr>
      <tr id="tr1">
        <td colspan="3" id="titulo1">ESPECIFICACION DEL MATERIAL </td>
      </tr>
      <tr>
        <td id="fuente1">Ancho</td>
        <td id="fuente1">Largo</td>
        <td id="fuente1">Solapa</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="ancho_egp" value="<?php echo $row_egp['ancho_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="largo_egp" value="<?php echo $row_egp['largo_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="solapa_egp" value="<?php echo $row_egp['solapa_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td id="fuente1">Largo del Canguro </td>
        <td id="fuente1">Calibre</td>
        <td id="fuente1">Tipo Extrusi&oacute;n </td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="largo_cang_egp" value="<?php echo $row_egp['largo_cang_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="calibre_egp" value="<?php echo $row_egp['calibre_egp']; ?>" size="20" /></td>
        <td id="dato1"><select name="tipo_ext_egp" id="tipo_ext_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Coextrusion" <?php if (!(strcmp("Coextrusion", $row_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>Coextrusion</option>
          <option value="Monocapa" <?php if (!(strcmp("Monocapa", $row_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>Monocapa</option>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1">Pigmento Exterior </td>
        <td id="fuente1">Pigmento Interior </td>
        <td id="fuente1">Adhesivo</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="pigm_ext_egp" value="<?php echo $row_egp['pigm_ext_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pigm_int_epg" value="<?php echo $row_egp['pigm_int_epg']; ?>" size="20" /></td>
        <td id="dato1"><select name="adhesivo_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="Cinta seguridad"  <?php if (!(strcmp("Cinta seguridad", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta seguridad</option>
          <option value="HOT MELT"  <?php if (!(strcmp("HOT MELT", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
<option value="Cinta permanente"  <?php if (!(strcmp("Cinta permanente", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta permanente</option>
            <option value="Cinta resellable"  <?php if (!(strcmp("Cinta resellable", $row_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta resellable</option>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1">Tipo Bolsa </td>
        <td id="fuente1">Cantidad</td>
        <td id="fuente1">Tipo Sello </td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="tipo_bolsa_egp" value="<?php echo $row_egp['tipo_bolsa_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="cantidad_egp" value="<?php echo $row_egp['cantidad_egp']; ?>" size="20" /></td>
        <td id="dato1"><select name="tipo_sello_egp" id="tipo_sello_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Plano" <?php if (!(strcmp("Plano", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Plano</option>
            <option value="Hilo" <?php if (!(strcmp("Hilo", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Hilo</option>
            <option value="Fondo" <?php if (!(strcmp("Fondo", $row_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Fondo</option>
        </select></td>
      </tr>
      <tr>
        <td colspan="3" id="fuente1">Observaciones</td>
      </tr>
      <tr>
        <td colspan="3" id="dato1"><textarea name="observacion1_egp" cols="75" rows="2"><?php echo $row_egp['observacion1_egp']; ?></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="3" id="titulo1">ESPECIFICACION DE LA IMPRESION</td>
      </tr>
      <tr>
        <td id="fuente1">Color 1 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color1_egp" value="<?php echo $row_egp['color1_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone1_egp" value="<?php echo $row_egp['pantone1_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion1_egp" value="<?php echo $row_egp['ubicacion1_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td id="fuente1">Color 2 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color2_egp" value="<?php echo $row_egp['color2_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone2_egp" value="<?php echo $row_egp['pantone2_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion2_egp" value="<?php echo $row_egp['ubicacion2_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td id="fuente1">Color 3</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color3_egp" value="<?php echo $row_egp['color3_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone3_egp" value="<?php echo $row_egp['pantone3_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion3_egp" value="<?php echo $row_egp['ubicacion3_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td id="fuente1">Color 4 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color4_egp" value="<?php echo $row_egp['color4_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone4_egp" value="<?php echo $row_egp['pantone4_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion4_egp" value="<?php echo $row_egp['ubicacion4_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td id="fuente1">Color 5 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input name="color5_egp" type="text" id="color5_egp" value="<?php echo $row_egp['color5_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone5_egp" value="<?php echo $row_egp['pantone5_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion5_egp" value="<?php echo $row_egp['ubicacion5_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td id="fuente1">Color 6</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color6_egp" value="<?php echo $row_egp['color6_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="pantone6_egp" value="<?php echo $row_egp['pantone6_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="ubicacion6_egp" value="<?php echo $row_egp['ubicacion6_egp']; ?>" size="20" /></td>
      </tr>
      
      <tr>
        <td colspan="3" id="fuente1">Observaciones</td>
      </tr>
      <tr>
        <td colspan="3" id="dato1"><textarea name="observacion2_egp" cols="75" rows="2"><?php echo $row_egp['observacion2_egp']; ?></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="3" id="titulo1">ESPECIFICACION DE LA NUMERACION </td>
      </tr>
      <tr>
        <td id="detalle2">Posici&oacute;n</td>
        <td id="detalle2">Tipo de Numeraci&oacute;n </td>
        <td id="detalle2">Formato CB </td>
      </tr>
      <tr>
        <td id="detalle1">Solapa TR</td>
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
        <td id="detalle1">Cinta</td>
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
        <td id="detalle1">Principal</td>
        <td id="detalle2"><select name="tipo_principal_egp" id="tipo_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_egp['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_principal_egp" id="cb_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option><option value="EAN128" <?php if (!(strcmp("EAN128", $row_egp['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Inferior</td>
        <td id="detalle2"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
<option value="CCTV" <?php if (!(strcmp("CCTV", $row_egp['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_inferior_egp" id="cb_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_egp['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_egp['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Comienza en </td>
        <td id="detalle2"><input type="text" name="comienza_egp" value="<?php echo $row_egp['comienza_egp']; ?>" size="20" /></td>
        <td nowrap="nowrap" id="detalle2"><input <?php if (!(strcmp($row_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> name="fecha_cad_egp" type="checkbox" value="1" />
          Incluir Fecha Caducidad </td>
      </tr>
      <tr>
        <td colspan="3" id="fuente1">Observaciones</td>
      </tr>
      <tr>
        <td colspan="3" id="dato1"><textarea name="observacion3_egp" cols="75" rows="2"><?php echo $row_egp['observacion3_egp']; ?></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="3" id="titulo1">ESPECIFICACION DEL ARTE </td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="arte_sum_egp" type="checkbox" value="1" />
          Arte Suministrado por el Cliente </td>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="ent_logo_egp" type="checkbox" value="1" />
Entrega Logos de la Entidad </td>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orient_arte_egp" type="checkbox" value="1" />
Solicita Orientaci&oacute;n en el Arte </td>
      </tr>
      <tr>
        <td colspan="2" id="detalle2">Artes, Logos, Archivos Adjuntos</td>
        <td id="detalle2">Dise&ntilde;ador</td>
      </tr>
      <tr>
        <td colspan="2" rowspan="3" id="detalle2"><table border="0">
          <tr>
            <td id="dato1"><input name="arte1" type="hidden" value="<?php echo $row_egp['archivo1'];?>" /><a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo1'];?>','610','490')"><?php echo $row_egp['archivo1']; ?></a></td>
            <td id="dato2"><input name="archivo1" type="file" size="20" /></td>
          </tr>
          <tr>
            <td id="dato1"><input name="arte2" type="hidden" value="<?php echo $row_egp['archivo2'];?>" /><a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo2'];?>','610','490')"><?php echo $row_egp['archivo2']; ?></a></td>
            <td id="dato2"><input name="archivo2" type="file" size="20"/></td>
          </tr>
          <tr>
            <td id="dato1"><input name="arte3" type="hidden" value="<?php echo $row_egp['archivo3'];?>" /><a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo3'];?>','610','490')"><?php echo $row_egp['archivo3']; ?></a></td>
            <td id="dato2"><input name="archivo3" type="file" size="20"/></td>
          </tr>
        </table></td>
        <td id="detalle2"><input type="text" name="disenador_egp" value="<?php echo $row_egp['disenador_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td id="detalle2">Telefono</td>
      </tr>
      <tr>
        <td id="detalle2"><input type="text" name="telef_disenador_egp" value="<?php echo $row_egp['telef_disenador_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td colspan="3" id="fuente1">Observaciones</td>
      </tr>
      <tr>
        <td colspan="3" id="dato1"><textarea name="observacion4_egp" cols="75" rows="2"><?php echo $row_egp['observacion4_egp']; ?></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="3" id="titulo1">ESPECIFICACION DEL DESPACHO</td>
      </tr>
      <tr>
        <td id="fuente1">Unidades por Paquete </td>
        <td id="fuente1">Unidades por Caja </td>
        <td id="fuente1">Marca de Cajas </td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="unids_paq_egp" value="<?php echo $row_egp['unids_paq_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="unids_caja_egp" value="<?php echo $row_egp['unids_caja_egp']; ?>" size="20" /></td>
        <td id="dato1"><input type="text" name="marca_cajas_egp" value="<?php echo $row_egp['marca_cajas_egp']; ?>" size="20" /></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">Lugar Entrega de Mercanc&iacute;a </td>
        <td id="fuente1">Vendedor</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><input name="lugar_entrega_egp" type="text" value="<?php echo $row_egp['lugar_entrega_egp']; ?>" size="60" /></td>
        <td id="dato1"><select name="vendedor" id="vendedor">
            <option value="" <?php if (!(strcmp("", $row_egp['vendedor']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
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
        <td colspan="3" id="fuente1">Observaciones</td>
        </tr>
      <tr>
        <td colspan="3" id="dato1"><textarea name="observacion5_egp" cols="75" rows="2" id="observacion5_egp"><?php echo $row_egp['observacion5_egp']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td id="fuente2">Modificado por : </td>
        <td id="fuente2">Fecha Modificaci&oacute;n </td>
        <td id="fuente2">Hora Modificaci&oacute;n </td>
      </tr>
      <tr>
        <td id="dato2"><input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          - 
        <?php echo $row_egp['responsable_modificacion']; ?> - </td>
        <td id="dato2"><input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
        - <?php echo $row_egp['fecha_modificacion']; ?> -</td>
        <td id="dato2"><input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a") ?>" />
          - 
        <?php echo $row_egp['hora_modificacion']; ?> - </td>
      </tr>
      <tr>
        <td colspan="3" id="dato2"><input name="pageNum_egp" type="hidden" id="pageNum_egp" value="<?php echo $_GET['pageNum_egp']; ?>" />
          <input name="totalRows_egp" type="hidden" id="totalRows_egp" value="<?php echo $_GET['totalRows_egp']; ?>" />
          <input name="submit" type="submit" value="Guardar Modificaciones" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="n_egp" value="<?php echo $row_egp['n_egp']; ?>">
  </form>
  </td>
  </tr>
</table>
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
mysql_free_result($egp);
mysql_free_result($vendedores);
?>