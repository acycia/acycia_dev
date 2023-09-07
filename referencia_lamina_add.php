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
 $insertSQL = sprintf("INSERT INTO Tbl_referencia (cod_ref, version_ref, n_egp_ref,  n_cotiz_ref, tipo_bolsa_ref, material_ref, ancho_ref, N_repeticion_l, N_diametro_max_l, N_peso_max_l, N_cantidad_metros_r_l, N_embobinado_l, calibre_ref, peso_millar_ref, impresion_ref, estado_ref, registro1_ref, fecha_registro1_ref, registro2_ref, fecha_registro2_ref, B_generica ) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                       GetSQLValueString($_POST['cod_ref'], "text"),
                       GetSQLValueString($_POST['version_ref'], "text"),
                       GetSQLValueString($_POST['cod_ref'], "int"),
                       GetSQLValueString($_POST['n_cotiz_ref'], "int"),
					   GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),
                       GetSQLValueString($_POST['material_ref'], "text"),
                       GetSQLValueString($_POST['ancho_ref'], "double"),
                       GetSQLValueString($_POST['N_repeticion_ref'], "double"),
					   GetSQLValueString($_POST['N_diametro_max_l'], "double"),
					   GetSQLValueString($_POST['N_peso_max_l'], "int"),
					   GetSQLValueString($_POST['N_cantidad_metros_r_l'], "double"),
					   GetSQLValueString($_POST['N_embobinado'], "text"),
                       GetSQLValueString($_POST['calibre_ref'], "double"),
					   GetSQLValueString($_POST['peso_ref'], "double"),					   
                       GetSQLValueString($_POST['impresion_ref'], "text"),
                       GetSQLValueString($_POST['estado_ref'], "int"),
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
                       GetSQLValueString($_POST['registro2_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro2_ref'], "date"),
					   GetSQLValueString($_POST['B_generica'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

/*  $updateGoTo = "referencia_lamina_vista.php?id_ref=" . $_POST['id_ref'] . "&tipo=" . $_POST['tipo_usuario'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {*/
  $insertSQL2  = sprintf("INSERT INTO Tbl_egp (n_egp, responsable_egp, codigo_usuario, fecha_egp, hora_egp, estado_egp, ancho_egp, calibre_egp, tipo_ext_egp, pigm_ext_egp, pigm_int_epg, cantidad_egp, color1_egp, pantone1_egp, ubicacion1_egp, color2_egp, pantone2_egp, ubicacion2_egp, color3_egp, pantone3_egp, ubicacion3_egp, color4_egp, pantone4_egp, ubicacion4_egp, color5_egp, pantone5_egp, ubicacion5_egp, color6_egp, pantone6_egp, ubicacion6_egp,color7_egp, pantone7_egp, ubicacion7_egp,color8_egp, pantone8_egp, ubicacion8_egp, tipo_solapatr_egp, tipo_cinta_egp, tipo_principal_egp, tipo_inferior_egp, cb_solapatr_egp, cb_cinta_egp, cb_principal_egp, cb_inferior_egp, comienza_egp, fecha_cad_egp, arte_sum_egp, ent_logo_egp, orient_arte_egp, disenador_egp, telef_disenador_egp, observacion5_egp, responsable_modificacion, fecha_modificacion, hora_modificacion, vendedor) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cod_ref'], "int"),
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['codigo_usuario'], "text"),
					   GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
					   GetSQLValueString($_POST['hora_modificacion'], "text"),
                       GetSQLValueString($_POST['estado_ref'], "int"),
					   GetSQLValueString($_POST['ancho_ref'], "int"),
					   GetSQLValueString($_POST['calibre_ref'], "int"),
					   GetSQLValueString($_POST['tipo_ext_egp'], "text"),
                       GetSQLValueString($_POST['pigm_ext_egp'], "text"),
                       GetSQLValueString($_POST['pigm_int_epg'], "text"),					  
					   GetSQLValueString($_POST['cantidad_egp'], "text"),
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
					   	
                       GetSQLValueString($_POST['tipo_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['tipo_cinta_egp'], "text"),
                       GetSQLValueString($_POST['tipo_principal_egp'], "text"),
                       GetSQLValueString($_POST['tipo_inferior_egp'], "text"),
                       GetSQLValueString($_POST['cb_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['cb_cinta_egp'], "text"),
                       GetSQLValueString($_POST['cb_principal_egp'], "text"),
                       GetSQLValueString($_POST['cb_inferior_egp'], "text"),
					   GetSQLValueString($_POST['comienza_egp'], "text"),
					   					   				   					   
                       GetSQLValueString(isset($_POST['fecha_cad_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['arte_sum_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['ent_logo_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orient_arte_egp']) ? "true" : "", "defined","1","0"),
					   
                       GetSQLValueString($_POST['disenador_egp'], "text"),
                       GetSQLValueString($_POST['telef_disenador_egp'], "text"),
					   					   
                       GetSQLValueString($_POST['observacion5_egp'], "text"),
                       GetSQLValueString($_POST['responsable_modificacion'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
					   GetSQLValueString($_POST['vendedor'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());

$insertSQL5 = sprintf("INSERT INTO Tbl_cliente_referencia(N_referencia,N_cotizacion,Str_nit) VALUES (%s, %s, %s)",
GetSQLValueString($_POST['cod_ref'], "int"),
GetSQLValueString($_POST['n_cotiz_ref'], "int"),
GetSQLValueString($_POST['Str_nit'], "text"));
mysql_select_db($database_conexion1, $conexion1);
$Result5 = mysql_query($insertSQL5, $conexion1) or die(mysql_error());
//ACTUALIZA EL NUMERO DE REFERENCIA EN LA TABLA LAMINAS PARA QUE QUEDE IGUAL AL NUMERO DE LA TABLA TBL_CLIENTE_REFERENCIA Y ASI PODERLO CONSULTAR EN LISTADO DE REFERENCIAS
/* $cod_ref =$_POST['cod_ref']; 
 $cotiz=$_POST['n_cotiz_ref']; 	  
$insertSQL6 = sprintf("UPDATE Tbl_cotiza_laminas SET N_referencia_c=%s WHERE N_cotizacion='$cotiz' ",
GetSQLValueString($_POST['cod_ref'], "int"),
GetSQLValueString($_POST['n_cotiz_ref'], "int"));
mysql_select_db($database_conexion1, $conexion1);
$Result6 = mysql_query($insertSQL6, $conexion1) or die(mysql_error());*/
  $updateGoTo = "referencia_lamina_vista.php?cod_ref=" . $_POST['cod_ref'] . "&tipo=" . $_POST['tipo_usuario'] . "";
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

$colname_ver_ref = "-1";
if (isset($_GET['N_cotizacion'])) {
  $colname_ver_ref= (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
$colname_ver_codrefe= "-1";
if (isset($_GET['cod_refe'])) {
  $colname_ver_codrefe = (get_magic_quotes_gpc()) ? $_GET['cod_refe'] : addslashes($_GET['cod_refe']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_ref = sprintf("SELECT * FROM Tbl_cotiza_laminas,Tbl_referencia WHERE Tbl_cotiza_laminas.N_cotizacion = '%s' and Tbl_cotiza_laminas.N_referencia_c='%s'", $colname_ver_ref,$colname_ver_codrefe);
$ver_ref = mysql_query($query_ver_ref, $conexion1) or die(mysql_error());
$row_ver_ref = mysql_fetch_assoc($ver_ref);
$totalRows_ver_ref = mysql_num_rows($ver_ref);

/*$colname_ver_nref = "-1";
if (isset($_GET['N_cotizacion'])) {
  $colname_ver_nref= (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}*/
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nref = sprintf("SELECT * FROM Tbl_cliente_referencia ORDER BY N_referencia DESC",$colname_ver_nref);
$ver_nref = mysql_query($query_ver_nref, $conexion1) or die(mysql_error());
$row_ver_nref = mysql_fetch_assoc($ver_nref);
$totalRows_ver_nref = mysql_num_rows($ver_nref);

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
$query_ref_verif = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s AND estado_arte_verif = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);

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
  <td id="cabezamenu"><ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="disenoydesarrollo.php">DISEÑOYDESARROLLO</a></li>	
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="MM_validateForm('ancho_ref','','RisNum','N_repeticion_ref','','RisNum','calibre_ref','','RisNum','N_diametro_max_l','','RisNum','N_peso_max_l','','RisNum','N_cantidad_metros_r_l','','RisNum','peso_ref','','RisNum','N_embobinado','','R','cantidad_egp','','R','B_generica','','RisNum');return document.MM_returnValue">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="4" id="titulo2">REFERENCIA ( LAMINA ) </td>
        </tr>
      <tr>
        <td width="138" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="3" id="dato3"><a href="referencia_vista.php?id_ref=<?php echo $row_referencia_editar['id_ref']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" /></a><a href="disenoydesarrollo.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISEÑO Y DESARROLLO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><?php $ref=$row_referencia_editar['id_ref'];
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
        <td width="178" nowrap="nowrap" id="dato2">Fecha Ingreso 
          <input name="fecha_registro1_ref" type="text" id="fecha_b" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
        <td colspan="2" id="dato3">
          <input type="hidden" name="codigo_usuario" value="<?php echo $row_usuario['codigo_usuario'] ?>" />
          Ingresado por
<input name="registro1_ref" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" /></td>
        </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><strong>REFERENCIA - VERSION</strong></td>
        <td width="129" nowrap="nowrap" id="fuente2">Estado</td>
        <td width="235" id="fuente2">Arte</td>
      </tr>
      <tr id="tr3">
        <td nowrap="nowrap" id="fuente2"><input name="cod_ref" type="text" value="<?php echo $row_ver_nref['N_referencia']+1; ?>" size="5" readonly="readonly"/> 
          - 
       <input name="version_ref" type="text" value="00" size="2" /></td>
        <td id="fuente2"><select name="estado_ref" id="estado_ref">
          <option value="1" <?php if (!(strcmp(1, $row_ver_nref ['estado_ref']))) {echo "selected=\"selected\"";} ?>>Activa</option><option value="0" <?php if (!(strcmp(0, $row_ver_nref ['estado_ref']))) {echo "selected=\"selected\"";} ?>>Inactiva</option>
        </select></td>
        <td id="dato2"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"> <?php echo $row_ref_verif['userfile']; ?> </a> </td>
      </tr>
      <tr id="tr1">
        <td nowrap="nowrap" id="fuente2">Cotizaci&oacute;n N&ordm;</td>
        <td nowrap="nowrap" id="fuente2">Referencia Generica</td>
        <td id="fuente2">Fecha Arte</td>
      </tr>
      <tr>
        <td id="dato2"><input name="n_cotiz_ref" type="text" value="<?php echo $row_ver_ref['N_cotizacion']; ?>" size="5" readonly="readonly"/></td>
        <td id="dato2"><select name="B_generica" id="B_generica" onblur="if(form1.B_generica.value) { genericalamina(); } else{ alert('Debe Seleccionar GENERICA'); }">
          <option value=""></option>
          <option value="1">SI</option>
          <option value="0">NO</option>
        </select></td>
        <td id="dato2"><?php echo $row_ref_verif['fecha_aprob_arte_verif']; ?></td>
      </tr>
      <tr id="tr1">
        <td colspan="4" id="titulo4">DATOS GENERALES DE LA REFERENCIA </td>
        </tr>
      <tr id="tr1">
        <td id="fuente1">ANCHO</td>
        <td id="fuente1">REPETICION</td>
        <td id="fuente1">CALIBRE</td>
        <td id="fuente1">Diametro Maximo x Rollo  (cms)</td>
      </tr>
      <tr>
        <td id="dato1"><input name="ancho_ref" type="text" id="ancho_ref" value="<?php echo $row_ver_ref['N_ancho']?>" size="10"/></td>
        <td id="dato1"><input name="N_repeticion_ref" type="text" id="N_repeticion_ref"  value="<?php echo $row_ver_ref['N_repeticion']; ?>" size="10"/></td>
        <td id="dato1"><input name="calibre_ref" type="text" id="calibre_ref" onBlur="calcular_pesoml()" value="<?php echo $row_ver_ref['N_calibre']; ?>" size="10"/></td>
        <td id="dato1"><input name="N_diametro_max_l" type="text" id="N_diametro_max_l" size="10" value="<?php echo $row_ver_ref['N_diametro_max']?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Peso Maximo x Rollo(kgr)</td>
        <td id="fuente1">Cantidad Metros x Rollo</td>
        <td id="fuente1">PESO / ml</td>
        <td id="fuente1">Tipo de Embobinado</td>
      </tr>
      <tr>
        <td id="dato1"><input name="N_peso_max_l" type="text" id="N_peso_max_l" size="10" value="<?php echo $row_ver_ref['N_peso_max'] ?>"/></td>
        <td id="dato1"><input name="N_cantidad_metros_r_l" type="text" id="N_cantidad_metros_r_l" size="10" value="<?php echo $row_ver_ref['N_cantidad_metros_r']?>"/></td>
        <td id="dato1"><?php		
        $var1=$row_ver_ref['N_ancho'];
        $var2=$row_ver_ref['N_calibre'];
        $var3=($var1);
        $var4=($var2);
		$peso=(24*($var4));	
	    $ps=($peso*100)/100;
        $psm= number_format($ps,2);
        ?>          <input name="peso_ref" type="text" id="peso_ref" value="<?php echo $psm; ?>" size="10" maxlength="8" readonly="readonly"/></td>
        <td id="dato1"><input type="text" name="N_embobinado" id="N_embobinado"value="<?php echo $row_ver_ref['N_embobinado'] ?>" size="2" maxlength="2"onkeyup="conMayusculas(this)"/>          <a href="javascript:verFoto('embobinado_lamina.php','575','510')" >Ver Cuadro</a></td>
      </tr>
      <tr id="tr1">
        <td colspan="4" id="fuente1">Cantidad</td>
        </tr>
      <tr>
        <td colspan="4" id="dato1">
        <!--FUNCION PARA IMPRIMIR EL CALCULO DE PESO/ML--><!--FIN FUNCION PARA IMPRIMIR EL CALCULO DE PESO/ML-->
        <input name="cantidad_egp" type="text" id="cantidad_egp" value="<?php echo $row_ver_ref['N_cantidad']; ?>" size="10"/></td>
        </tr>
      <tr id="tr1">
        <td colspan="4" id="dato1"><input name="registro2_ref" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
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
        <td id="dato1"><select name="tipo_ext_egp" id="tipo_ext_egp">
  <option value="COEXTRUSION" <?php if (!(strcmp("COEXTRUSION", $row_ver_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>COEXTRUSION</option>
          <option value="MONOCAPA" <?php if (!(strcmp("MONOCAPA", $row_ver_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>MONOCAPA</option>        
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
        </select></td>
        <td id="dato1"><input type="text" name="pigm_ext_egp" value="<?php echo $row_ver_ref['Str_capa_ext_coext']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pigm_int_epg" value="<?php echo $row_ver_ref['Str_capa_inter_coext']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Colores</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1"></td>
      </tr>
      <tr>
        <td id="dato1"><?php echo  "Lleva ".$row_ver_ref['N_colores_impresion']." Colores"?></td>
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
        <td id="dato1"><input type="text" name="color1_egp" value="<?php echo $row_ver_egp['color1_egp']; ?>" size="20"onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone1_egp" value="<?php echo $row_ver_egp['pantone1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion1_egp" value="<?php echo $row_ver_egp['ubicacion1_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 2 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color2_egp" value="<?php echo $row_ver_egp['color2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone2_egp" value="<?php echo $row_ver_egp['pantone2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion2_egp" value="<?php echo $row_ver_egp['ubicacion2_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 3 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color3_egp" value="<?php echo $row_ver_egp['color3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone3_egp" value="<?php echo $row_ver_egp['pantone3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion3_egp" value="<?php echo $row_ver_egp['ubicacion3_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 4</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color4_egp" value="<?php echo $row_ver_egp['color4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone4_egp" value="<?php echo $row_ver_egp['pantone4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion4_egp" value="<?php echo $row_ver_egp['ubicacion4_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 5 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color5_egp" value="<?php echo $row_ver_egp['color5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone5_egp" value="<?php echo $row_ver_egp['pantone5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion5_egp" value="<?php echo $row_ver_egp['ubicacion5_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 6 </td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color6_egp" value="<?php echo $row_ver_egp['color6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone6_egp" value="<?php echo $row_ver_egp['pantone6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion6_egp" value="<?php echo $row_ver_egp['ubicacion6_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Color 7</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color7_egp" value="<?php echo $row_ver_egp['color7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone7_egp" value="<?php echo $row_ver_egp['pantone7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion7_egp" value="<?php echo $row_ver_egp['ubicacion7_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>  
      <tr id="tr1">
        <td id="fuente1">Color 8</td>
        <td id="fuente1">Pantone</td>
        <td id="fuente1">Ubicaci&oacute;n</td>
      </tr>
      <tr>
        <td id="dato1"><input type="text" name="color8_egp" value="<?php echo $row_ver_egp['color8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="pantone8_egp" value="<?php echo $row_ver_egp['pantone8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
        <td id="dato1"><input type="text" name="ubicacion8_egp" value="<?php echo $row_ver_egp['ubicacion8_egp']; ?>" size="20" onKeyUp="conMayusculas(this)"/></td>
      </tr>
      <tr>
        <td colspan="2" nowrap="nowrap" id="dato1">Cyreles ?:
          <?php if ($row_ver_ref['B_cyreles']==1){ echo "SI ";}else {echo "NO";}?>
Se Facturan Artes y Planchas</td>
        <td nowrap="nowrap" id="dato4">&nbsp;</td>
      </tr>
      <tr>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="arte_sum_egp" type="checkbox" value="1">
          Arte suministrado por el cliente </td>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="ent_logo_egp" type="checkbox" value="1" />
          Entrego Logos de la entidad</td>
        <td nowrap="nowrap" id="dato1"><input <?php if (!(strcmp($row_ver_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orient_arte_egp" type="checkbox" value="1" />
          Solicito orientaci&oacute;n en el arte </td>
      </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="fuente5">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td id="detalle2">POSICION</td>
        <td id="detalle2">TIPO DE NUMERACION </td>
        <td id="detalle2">FORMATO &amp; CODIGO DE BARAS </td>
      </tr>
      <tr>
        <td id="detalle1">Solapa TR </td>
        <td id="detalle2"><select name="tipo_solapatr_egp" id="tipo_solapatr_egp">
          <option value="N.A.">N.A.</option>
          <option value="Normal">Normal</option>
  <option value="CCTV">CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_solapatr_egp" id="cb_solapatr_egp">
          <option value="N.A.">N.A.</option>
<option value="EAN128">EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Cinta</td>
        <td id="detalle2"><select name="tipo_cinta_egp" id="tipo_cinta_egp">
          <option value="N.A.">N.A.</option>
          <option value="Normal">Normal</option>
        </select></td>
        <td id="detalle2"><select name="cb_cinta_egp" id="cb_cinta_egp">
          <option value="N.A.">N.A.</option>
<option value="EAN128">EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Principal</td>
        <td id="detalle2"><select name="tipo_principal_egp" id="tipo_principal_egp">
          <option value="N.A.">N.A.</option>
          <option value="Normal">Normal</option>
  <option value="CCTV">CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_principal_egp" id="cb_principal_egp">
          <option value="N.A.">N.A.</option>
<option value="EAN128">EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle1">Inferior</td>
        <td id="detalle2"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
          <option value="N.A.">N.A.</option>
          <option value="Normal">Normal</option>
  <option value="CCTV">CCTV</option>
        </select></td>
        <td id="detalle2"><select name="cb_inferior_egp" id="cb_inferior_egp">
          <option value="N.A.">N.A.</option>
          <option value="EAN128">EAN128</option>
        </select></td>
      </tr>
      <tr>
        <td id="detalle3">Comienza en </td>
        <td id="detalle2"><input type="text" name="comienza_egp" value="" size="20" onKeyUp="return ValNumero(this)"/></td>
        <td id="detalle1"><input name="fecha_cad_egp" type="checkbox" id="fecha_cad_egp" value="1" />
Incluir Fecha de Caducidad </td>
      </tr>      
      <tr>
        <td id="fuente1">Dise&ntilde;ador</td>
        <td id="fuente1">Telefono </td>
        <td id="fuente1">&nbsp;</td>
        
        </tr>
      <tr>
        <td id="fuente6"><input type="text" name="disenador_egp" value="" size="20" /></td>
        <td id="fuente6"><input type="text" name="telef_disenador_egp" value="" size="20" onkeyup="return ValNumero(this)"/></td>
        <td id="fuente6">&nbsp;</td>
        
        </tr>
      <tr>
        <td colspan="5" id="fuente4">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="5" id="fuente1">Observaciones</td>
      </tr>
      <tr>
        <td colspan="3" nowrap="nowrap" id="dato1"><textarea name="observacion5_egp" cols="75" rows="2"onKeyUp="conMayusculas(this)"><?php echo $row_ver_egp['observacion5_egp']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">
        <input name="ref_gen" type="hidden" value="<?php echo $_GET['cod_refe']; ?>" />
        <input name="responsable_modificacion" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
          <?php echo $row_ver_egp['responsable_modificacion']; ?>
          <input name="fecha_modificacion" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
          <?php echo $row_ver_egp['fecha_modificacion']; ?>
          <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a"); ?>" />
          <?php echo $row_ver_egp['hora_modificacion']; ?>
          <input name="tipo_usuario" type="hidden" id="tipo_usuario" value="<?php echo $row_usuario['tipo_usuario']; ?>" /></td>
        <td id="dato2"><input name="submit" type="submit" value="CREAR REFERENCIA" /></td>
      </tr>
    </table>
        <input type="hidden" name="tipo_bolsa_ref" id="tipo_bolsa_ref" value="LAMINA" />
        <input type="hidden" name="vendedor" id="vendedor" value="<?php echo $row_ver_ref['Str_usuario']?>"/>
        <input type="hidden" name="MM_insert" value="form1">
    <input type="hidden" name="Str_nit" value="<?php echo $row_ver_ref['Str_nit']; ?>">
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

mysql_free_result($ver_ref);

mysql_free_result($ver_nref);

mysql_free_result($ref_verif);
?>
