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
  $updateSQL = sprintf("UPDATE  Tbl_egp SET responsable_egp=%s, codigo_usuario=%s, fecha_egp=%s, hora_egp=%s, color1_egp=%s, pantone1_egp=%s, ubicacion1_egp=%s, color2_egp=%s, pantone2_egp=%s, ubicacion2_egp=%s, color3_egp=%s, pantone3_egp=%s, ubicacion3_egp=%s, color4_egp=%s, pantone4_egp=%s, ubicacion4_egp=%s, color5_egp=%s, pantone5_egp=%s, ubicacion5_egp=%s, color6_egp=%s, pantone6_egp=%s, ubicacion6_egp=%s, color7_egp=%s, pantone7_egp=%s, ubicacion7_egp=%s, color8_egp=%s, pantone8_egp=%s, ubicacion8_egp=%s,
 observacion5_egp=%s, responsable_modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s WHERE n_egp=%s",
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['codigo_usuario'], "text"),
					   GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
					   GetSQLValueString($_POST['hora_modificacion'], "text"),					   
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
                       GetSQLValueString($_POST['color7_egp'], "text"),
                       GetSQLValueString($_POST['pantone7_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion7_egp'], "text"),
                       GetSQLValueString($_POST['color8_egp'], "text"),
                       GetSQLValueString($_POST['pantone8_egp'], "text"),
                       GetSQLValueString($_POST['ubicacion8_egp'], "text"),					   					   
                       GetSQLValueString($_POST['observacion5_egp'], "text"),
                       GetSQLValueString($_POST['responsable_modificacion'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
					   GetSQLValueString($_POST['cod_ref'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateSQL2 = sprintf("UPDATE Tbl_referencia SET impresion_ref=%s, registro1_ref=%s, fecha_registro1_ref=%s, registro2_ref=%s, fecha_registro2_ref=%s WHERE  id_ref='%s'",
                       GetSQLValueString($_POST['impresion_ref'], "text"), 
					   GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
                       GetSQLValueString($_POST['registro2_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro2_ref'], "date"),
					   GetSQLValueString($_POST['id_ref'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());  	  

echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
echo "<script type=\"text/javascript\">window.close();</script>";
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
//EL ID_REF ES ENVIADO DESDE VISTA DE REFERENCIA
$colname_referencia_editar = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_editar = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_editar = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref = '%s' and Tbl_referencia.cod_ref=Tbl_egp.n_egp", $colname_referencia_editar);
$referencia_editar = mysql_query($query_referencia_editar, $conexion1) or die(mysql_error());
$row_referencia_editar = mysql_fetch_assoc($referencia_editar);
$totalRows_referencia_editar = mysql_num_rows($referencia_editar);

$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = '%s' AND estado_arte_verif = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
  <td id="cabezamenu">
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="7" id="titulo2">REFERENCIA ( BOLSA PLASTICA ) </td>
        </tr>
      <tr>
        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="6" id="dato3">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="dato2">Fecha Ingreso 
          <input name="fecha_registro1_ref" readonly="readonly" type="text" value="<?php echo $row_referencia_editar['fecha_registro1_ref']; ?>" size="10" /></td>
        <td colspan="5" id="dato3">
          <input type="hidden" name="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario'] ?>" />
          Ingresado por
<input name="registro1_ref" type="text" value="<?php echo $row_referencia_editar['registro1_ref']; ?>" size="27" readonly="readonly" /></td>
        </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><strong>REFERENCIA - VERSION</strong></td>
        <td colspan="3" nowrap="nowrap" id="fuente2">Estado</td>
        <td colspan="2" id="fuente2">Arte</td>
      </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><input name="cod_ref" type="text" value="<?php echo $row_referencia_editar['cod_ref']; ?>" size="5" readonly="readonly"/> 
          - 
            <input name="version_ref" type="text" value="<?php echo $row_referencia_editar['version_ref']; ?>" size="2" readonly="readonly" /></td>
        <td colspan="3" id="fuente2"><select name="estado_ref" id="estado_ref" disabled="disabled">
          <option value="0" <?php if (!(strcmp(0, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Inactiva</option><option value="1" <?php if (!(strcmp(1, $row_referencia_editar['estado_ref']))) {echo "selected=\"selected\"";} ?>>Activa</option>
        </select></td>
        <td colspan="2" id="dato2"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"> <?php echo $row_ref_verif['userfile']; ?> </a> </td>
      </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="fuente2">Cotizaci&oacute;n N&ordm;</td>
        <td colspan="3" nowrap="nowrap" id="fuente2">Referencia Generica</td>
        <td colspan="2" id="fuente2">Fecha Arte</td>
      </tr>
      <tr>
        <td id="dato2"><input name="n_cotiz_ref" type="text" value="<?php echo $row_referencia_editar['n_cotiz_ref']; ?>" size="5" readonly="readonly" /></td>
        <td colspan="3" id="dato2"><select name="B_generica" disabled="disabled" id="B_generica">
          <option value=""<?php if (!(strcmp('', $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>></option></option>
          <option value="1" <?php if (!(strcmp(1, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0" <?php if (!(strcmp(0, $row_referencia_editar['B_generica']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
        <td colspan="2" id="dato2"><?php echo $row_referencia_editar['fecha_aprob_arte_verif']; ?></td>
      </tr>
      <tr id="tr1">
        <td colspan="7" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
        </tr>
      <!--<tr id="tr1">
        <td id="fuente1">ANCHO (cms)</td>
        <td id="fuente1">LARGO (cms)</td>
        <td colspan="3" id="fuente1">SOLAPA  (cms)</td>
        <td colspan="2" id="fuente1">BOLSILLO PORTAGUIA </td>
      </tr>
      <tr>
        <td id="dato1"><input name="ancho_ref" type="text" id="ancho_ref" value="<?php echo $row_referencia_editar['ancho_ref']; ?>" size="10" /></td>
        <td id="dato1"><input name="largo_ref" type="text" id="largo_ref" value="<?php echo $row_referencia_editar['largo_ref']; ?>" size="10" /></td>
        <td colspan="2" id="dato1">
        <p><input type="radio" name="valora" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],2))) {echo "checked=\"checked\"";} ?> value="2" onClick="calcular_pesom();"/>Sencilla<br />
        <input type="radio" name="valora" <?php if (!(strcmp($row_referencia_editar['b_solapa_caract_ref'],1))) {echo "checked=\"checked\"";} ?> value="1" onClick="calcular_pesom();"/>Doble<br /></p></td>
        <td id="dato1"><input name="solapa_ref" type="text" id="solapa_ref" value="<?php echo $row_referencia_editar['solapa_ref']; ?>" size="10" onblur="validarRadio(),calcular_pesom()"/></td>
        <td colspan="2" id="dato1"><input name="bolsillo_guia_ref" type="text" id="bolsillo_guia_ref" value="<?php echo $row_referencia_editar['bolsillo_guia_ref']; ?>" size="10" onBlur="mostrarBols(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">CALIBRE (mills)</td>
        <td id="fuente1"> FUELLE  (cms)</td>
        <td colspan="3" id="fuente1">PESO MILLAR</td>
        <td colspan="2" id="fuente1">ADHESIVO</td>
      </tr>
      <tr>
        <td id="dato1"><input name="calibre_ref" type="text" id="calibre_ref" onBlur="calcular_pesom()" value="<?php echo $row_referencia_editar['calibre_ref']; ?>" size="10"/></td>
        <td id="dato1"><input name="B_fuelle" type="text" id="B_fuelle" value="<?php echo $row_referencia_editar['N_fuelle']?>" size="10" /></td>
        <td colspan="3" id="dato1"><input name="peso_millar_ref" type="text" id="peso_millar_ref" onBlur="calcular_pesom();" value="<?php echo $row_referencia_editar['peso_millar_ref']; ?>" size="10" readonly="readonly"/></td>
        <td colspan="2" id="dato1"><select name="adhesivo_ref" id="adhesivo_ref" style="width:100px">
          <option value="N.A." <?php if (!(strcmp("0", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
          <option value="CINTA PERMANENTE" <?php if (!(strcmp("CINTA PERMANENTE", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA PERMANENTE</option>
          <option value="CINTA RESELLABLE" <?php if (!(strcmp("CINTA RESELLABLE", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA RESELLABLE</option>
          <option value="CINTA DE SEGURIDAD" <?php if (!(strcmp("CINTA DE SEGURIDAD", $row_referencia_editar['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA DE SEGURIDAD</option>
        </select></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">TIPO DE BOLSA</td>
        <td id="fuente1">TIPO DE SELLO</td>
        <td colspan="3" id="fuente1">TROQUEL/PRECORTE</td>
        <td colspan="2" id="fuente1">FONDO</td>
      </tr>
      <tr>
        <td id="fuente1"><select name="tipo_bolsa_ref" id="tipo_bolsa_ref" style="width:100px"> 
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="SEGURIDAD" <?php if (!(strcmp("SEGURIDAD", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
          <option value="CURRIER" <?php if (!(strcmp("CURRIER", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>CURRIER</option>
          <option value="BOLSA PLASTICA" <?php if (!(strcmp("BOLSA PLASTICA", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
          <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $row_referencia_editar['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
        </select></td>
        <td id="fuente1"><select name="tipo_sello_egp" id="tipo_sello_egp" style="width:100px">
          <option></option>
          <option value="N/A"<?php if (!(strcmp("N/A", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>N/A</option>
          <option value="HILO"<?php if (!(strcmp("HILO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO</option>
          <option value="PLANO"<?php if (!(strcmp("PLANO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>PLANO</option>
          <option value="HILO/PLANO"<?php if (!(strcmp("HILO/PLANO", $row_referencia_editar['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO/PLANO</option>
        </select></td>
        <td colspan="3" id="fuente1"><select name="B_troquel" id="B_troquel" style="width:50px">
          <option value=""<?php if (!(strcmp("*", $row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>*</option>
          <option value="1"<?php if (!(strcmp("1", $row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_referencia_editar['B_troquel']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
        <td colspan="2" id="fuente1"><select name="B_fondo" id="B_fondo"style="width:50px">
          <option value=""<?php if (!(strcmp("*", $row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>*</option>
          <option value="1"<?php if (!(strcmp("1", $row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>SI</option>
          <option value="0"<?php if (!(strcmp("0",$row_referencia_editar['B_fondo']))) {echo "selected=\"selected\"";} ?>>NO</option>
        </select></td>
      </tr>
      <tr id="tr1">
        <td rowspan="2" id="fuente1">PRESENTACION</td>
        <td rowspan="2" id="fuente1">TRATAMIENTO</td>
        <td colspan="5" id="fuente2">Bolsillo Portaguia</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">(Ubicacion)</td>
        <td id="fuente1">(Forma)</td>
        <td id="fuente1">(Lamina 1)</td>
        <td id="fuente1">(Lamina 2)</td>
      </tr>
      <tr>
        <td id="dato1"><select name="Str_presentacion" id="Str_presentacion" style="width:100px">
          <option value=""></option>
          <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="LAMINA" <?php if (!(strcmp('LAMINA', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>LAMINA</option>
          <option value="TUBULAR" <?php if (!(strcmp('TUBULAR', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>TUBULAR</option>
       <option value="SEMITUBULAR" <?php if (!(strcmp('SEMITUBULAR', $row_referencia_editar['Str_presentacion']))) {echo "selected=\"selected\"";} ?>>SEMITUBULAR</option>
        </select></td>
        <td id="dato1"><select name="Str_tratamiento" id="Str_tratamiento" style="width:100px">
          <option value="N.A"<?php if (!(strcmp('N.A', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>N.A</option>
          <option value="UNA CARA" <?php if (!(strcmp('UNA CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>UNA CARA</option>
          <option value="DOBLE CARA" <?php if (!(strcmp('DOBLE CARA', $row_referencia_editar['Str_tratamiento']))) {echo "selected=\"selected\"";} ?>>DOBLE CARA</option>
        </select></td>
        <td colspan="2" id="dato1"><select name="str_bols_ub_ref" id="str_bols_ub_ref" style="width:50px">
          <option value="">N.A.</option>
          <option value="ANVERSO"<?php if (!(strcmp('ANVERSO', $row_referencia_editar['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Anverso</option>
          <option value="REVERSO"<?php if (!(strcmp('REVERSO', $row_referencia_editar['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Reverso</option>
        </select></td>
        <td id="dato1"><select name="str_bols_fo_ref" id="str_bols_fo_ref" style="width:50px">
          <option value="">N.A.</option>
          <option value="TRANSLAPE"<?php if (!(strcmp('TRANSLAPE', $row_referencia_editar['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Translape</option>
          <option value="RESELLABLE"<?php if (!(strcmp('RESELLABLE', $row_referencia_editar['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Resellable</option>
        </select></td>
        <td id="dato1"><input name="bol_lamina_1_ref" id="bol_lamina_1_ref" style="width:50px" min="0"step="0.01" type="number" size="5" value="<?php echo $row_referencia_editar['bol_lamina_1_ref'] ?>" /></td>
        <td id="dato1"><input name="bol_lamina_2_ref" id="bol_lamina_2_ref" style="width:50px" min="0"step="0.01" type="number" size="5" value="<?php echo $row_referencia_editar['bol_lamina_2_ref'] ?>" /></td>
        </tr>-->
      <tr id="tr1">
        <td height="44" colspan="7" id="dato1">Ultima Actualizaci&oacute;n : 
          <input name="registro2_ref" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['registro2_ref']; ?>
          <input name="fecha_registro2_ref" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_referencia_editar['fecha_registro2_ref']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" /></td>
      </tr>
    </table>
      
        
        <table id="tabla2">
      <tr id="tr1">
        <td colspan="3" id="titulo4">DATOS ESPECIFICOS DE LA REFERENCIA</td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">TIPO DE EXTRUSION</td>
        <td id="fuente1">PIGMENTO EXTERIOR</td>
        <td id="fuente1">PIGMENTO INTERIOR </td>
      </tr>
      <tr>
        <td id="dato1"><select name="tipo_ext_egp" id="tipo_ext_egp" disabled="disabled">
          <option value="COEXTRUSION" <?php if (!(strcmp("COEXTRUSION", $row_ver_cot['tipo_ext_ref']))) {echo "selected=\"selected\"";} ?>>COEXTRUSION</option>
          <option value="MONOCAPA" <?php if (!(strcmp("MONOCAPA", $row_ver_cot['tipo_ext_ref']))) {echo "selected=\"selected\"";} ?>>MONOCAPA</option>        
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_cot['tipo_ext_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
        </select></td>
        <td id="dato1"><input name="pigm_ext_egp" type="text" onKeyUp="conMayusculas(this)" value="<?php echo $row_referencia_editar['pigm_ext_egp']; ?>" size="20" readonly="readonly"/></td>
        <td id="dato1"><input name="pigm_int_epg" type="text" onKeyUp="conMayusculas(this)" value="<?php echo $row_referencia_editar['pigm_int_epg']; ?>" size="20" readonly="readonly"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Numero de Colores</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1"></td>
      </tr>
      <tr>
        <td id="dato1"><input type="number" name="impresion_ref" size="5" min="1" id="impresion_ref"  style="width:40px"value="<?php echo $row_referencia_editar['impresion_ref'] ?>" /> </td>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 1 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color1_egp" value="<?php echo $row_referencia_editar['color1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone1_egp" value="<?php echo $row_referencia_editar['pantone1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion1_egp" value="<?php echo $row_referencia_editar['ubicacion1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 2 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color2_egp" value="<?php echo $row_referencia_editar['color2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone2_egp" value="<?php echo $row_referencia_editar['pantone2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion2_egp" value="<?php echo $row_referencia_editar['ubicacion2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 3 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color3_egp" value="<?php echo $row_referencia_editar['color3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone3_egp" value="<?php echo $row_referencia_editar['pantone3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion3_egp" value="<?php echo $row_referencia_editar['ubicacion3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 4</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color4_egp" value="<?php echo $row_referencia_editar['color4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone4_egp" value="<?php echo $row_referencia_editar['pantone4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion4_egp" value="<?php echo $row_referencia_editar['ubicacion4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 5 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color5_egp" value="<?php echo $row_referencia_editar['color5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone5_egp" value="<?php echo $row_referencia_editar['pantone5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion5_egp" value="<?php echo $row_referencia_editar['ubicacion5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 6 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color6_egp" value="<?php echo $row_referencia_editar['color6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone6_egp" value="<?php echo $row_referencia_editar['pantone6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion6_egp" value="<?php echo $row_referencia_editar['ubicacion6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 7</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color7_egp" value="<?php echo $row_referencia_editar['color7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone7_egp" value="<?php echo $row_referencia_editar['pantone7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion7_egp" value="<?php echo $row_referencia_editar['ubicacion7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>  
      <tr id="tr1">
        <td id="fuente1">Color 8</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color8_egp" value="<?php echo $row_referencia_editar['color8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone8_egp" value="<?php echo $row_referencia_editar['pantone8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion8_egp" value="<?php echo $row_referencia_editar['ubicacion8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>           
      <tr id="tr1">
        <!--<td id="detalle2">POSICION</td>
        <td id="detalle2">TIPO DE NUMERACION </td>
        <td id="detalle2">FORMATO &amp; CODIGO DE BARAS </td>
      </tr>
      <tr>
        <td id="detalle1">Solapa TR </td>
        <td id="detalle2"><select name="tipo_solapatr_egp" id="tipo_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
  <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_solapatr_egp" id="cb_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select>
        </td>
      </tr>
      <tr>
        <td id="detalle1">Cinta</td>
        <td id="detalle2"><select name="tipo_cinta_egp" id="tipo_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
        </select></td>
        <td id="detalle2"><select name="cb_cinta_egp" id="cb_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>          
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Superior</td>
        <td id="detalle2"><select name="tipo_superior_egp" id="tipo_superior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_superior_egp" id="cb_superior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Principal</td>
        <td id="detalle2"><select name="tipo_principal_egp" id="tipo_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_principal_egp" id="cb_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
        </select>
 <input type="text" name="cb_principal_egp" id="cb_principal_egp" list="cb_p"/>
    <datalist id="cb_p">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_principal_egp']))) {echo $row_referencia_editar['cb_principal_egp'];} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_principal_egp']))) {echo $row_referencia_editar['cb_principal_egp'];} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_principal_egp']))) {echo $row_referencia_editar['cb_principal_egp'];} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_principal_egp']))) {echo $row_referencia_editar['cb_principal_egp'];} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_principal_egp']))) {echo $row_referencia_editar['cb_principal_egp'];} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_principal_egp']))) {echo $row_referencia_editar['cb_principal_egp'];} ?>>CODE39</option>         
          
    </datalist>        
        
        </td>
      </tr>
      <tr>
        <td id="detalle1">Inferior</td>
        <td id="detalle2"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_inferior_egp" id="cb_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
          
        </select></td>
      </tr>
      <tr>
        <td id="detalle3">Comienza en </td>
        <td id="detalle2"><input type="text" name="comienza_egp" value="<?php echo $row_referencia_editar['comienza_egp']; ?>" size="20" onKeyUp="return ValNumero(this)"/></td>
        <td id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> name="fecha_cad_egp" type="checkbox" value="1" />
Incluir Fecha de Caducidad </td>
      </tr>
      <tr id="tr1">
        <td colspan="2" nowrap="nowrap" id="dato1">Cyreles ?: <?php if ($row_referencia_editar['B_cyreles']==1){ echo "SI ";}else {echo "NO";}?>
          Se Facturan Artes y Planchas</td>
        <td nowrap="nowrap" id="dato4">&nbsp;</td>
      </tr>
      <tr>
<td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="arte_sum_egp" type="checkbox" value="1" />
          Arte Suministrado por el Cliente</td>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="ent_logo_egp" type="checkbox" value="1" />
          Entrega Logos de la Entidad</td>
        <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_referencia_editar['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orient_arte_egp" type="checkbox" value="1" />
          Solicita Orientaci&oacute;n en el Arte</td>
      </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="detalle4">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="detalle2">Adjuntar Artes, Logos o Archivos suministrado, solo archivos pdf </td>
        <td id="detalle2">Dise&ntilde;ador</td>
      </tr>
      <tr>
        <td colspan="2" rowspan="3" id="detalle2"><table border="0">
            <tr>
              <td id="dato1"><input name="arte1" type="hidden" value="<?php echo $row_referencia_editar['archivo1'];?>" /><a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo1'];?>','610','490')"><?php if ($row_referencia_editar['archivo1']!="")echo "Arte1";?></a></td><td id="dato2"><input type="file" name="archivo1"size="20" /></td>              
            </tr>
            <tr>
              <td id="dato1"><input name="arte2" type="hidden" value="<?php echo $row_referencia_editar['archivo2'];?>" />
              <a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo2'];?>','610','490')"><?php if ($row_referencia_editar['archivo2']!="")echo "Arte2";?></a></td>
              <td id="dato2"><input type="file" name="archivo2"size="20"/></td>              
            </tr>
            <tr>
              <td id="dato1"><input name="arte3" type="hidden" value="<?php echo $row_referencia_editar['archivo3'];?>" />
                <a href="javascript:verFoto('egpbolsa/<?php echo $row_referencia_editar['archivo3'];?>','610','490')"><?php if ($row_referencia_editar['archivo3']!="")echo "Arte3";?></a></td>
              <td id="dato2"><input type="file" name="archivo3" size="20"/></td>              
            </tr>
        </table></td>
        <td id="detalle2"><input type="text" name="disenador_egp" value="<?php echo $row_referencia_editar['disenador_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="detalle2">Telefono </td>
      </tr>
      <tr>
        <td id="detalle2"><input type="text" name="telef_disenador_egp" value="<?php echo $row_referencia_editar['telef_disenador_egp']; ?>" size="20" onKeyUp="return ValNumero(this)"/></td>
      </tr>
      <tr>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
      </tr>       
      <tr id="tr1">
        <td id="dato1">Unidades por Paquete </td>
        <td id="dato1">Unidades por Caja </td>
        <td id="dato1">&nbsp;</td>
      </tr>
      <tr>
        <td id="dato1"><input type="number" name="unids_paq_egp" value="<?php echo $row_referencia_editar['unids_paq_egp']; ?>" required="required" size="20" /></td>
        <td id="dato1"><input type="number" name="unids_caja_egp" value="<?php echo $row_referencia_editar['unids_caja_egp']; ?>" required="required" size="20" /></td>
        <td id="dato1">&nbsp;</td>-->
      </tr>
      <tr>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
        <td id="dato1">&nbsp;</td>
      </tr>      
      <tr id="tr1">
        <td colspan="3" id="fuente1">Observaciones</td>
      </tr>
      <tr>
        <td colspan="3" id="dato1"><textarea name="observacion5_egp" cols="75" rows="2" id="observacion5_egp"onKeyUp="conMayusculas(this)"><?php echo $row_referencia_editar['observacion5_egp']; ?></textarea></td>
      <tr>
        <td colspan="3" id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">Ultima Modificaci&oacute;n : 
          <input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_referencia_editar['responsable_modificacion']; ?>
          <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_referencia_editar['fecha_modificacion']; ?>
          <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a"); ?>" />
          <?php echo $row_referencia_editar['hora_modificacion']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" />
          <input name="codigo_usuario" type="hidden" id="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario']; ?>" /></td>
        <td id="dato2"><input name="submit" type="submit" value="EDITAR REFERENCIA" /></td>
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

mysql_free_result($ref_verif);

mysql_free_result($referencia_editar);

?>
