<?php require_once('Connections/conexion1.php'); ?>
<?php
session_start();
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
  $updateSQL = sprintf("UPDATE cliente SET nombre_c=%s, tipo_c=%s, fecha_ingreso_c=%s, fecha_solicitud_c=%s, rep_legal_c=%s, telefono_c=%s, direccion_c=%s, fax_c=%s, contacto_c=%s, cargo_contacto_c=%s, telefono_contacto_c=%s, celular_contacto_c=%s, cod_pais_c=%s, cod_dpto_c=%s, cod_ciudad_c=%s, email_c=%s, contacto_bodega_c=%s, cargo_contacto_bodega_c=%s, direccion_entrega=%s, email_contacto_bodega_c=%s, cod_pais_bodega_c=%s, cod_dpto_bodega_c=%s, cod_ciudad_bodega_c=%s, telefono_bodega_c=%s, fax_bodega_c=%s, direccion_envio_factura_c=%s, telefono_envio_factura_c=%s, fax_envio_factura_c=%s, observ_inf_c=%s, contacto_dpto_pagos_c=%s, telefono_dpto_pagos_c=%s, fax_dpto_pagos_c=%s, direccion_dpto_pagos_c=%s, email_dpto_pagos_c=%s, cupo_solicitado_c=%s, forma_pago_c=%s, tel_1ref_comercial_c=%s, nombre_1ref_comercial_c=%s, cupo_1ref_comercial_c=%s, plazo_1ref_comercial_c=%s, tel_2ref_comercial_c=%s, nombre_2ref_comercial_c=%s, cupo_2ref_comercial_c=%s, plazo_2ref_comercial_c=%s, tel_3ref_comercial_c=%s, nombre_3ref_comercial_c=%s, cupo_3ref_comercial_c=%s, plazo_3ref_comercial_c=%s, telefono_1ref_bancaria_c=%s, nombre_1ref_bancaria_c=%s, telefono_2ref_bancaria_c=%s, nombre_2ref_bancaria_c=%s, telefono_3ref_bancaria_c=%s, nombre_3ref_bancaria_c=%s, observ_inf_finan_c=%s, cupo_aprobado_c=%s, plazo_aprobado_c=%s, observ_aprob_finan_c=%s, estado_comercial_c=%s, asesor_comercial_c=%s, codigo_asesor_comercial_c=%s, observ_asesor_com_c=%s, camara_comercio_c=%s, referencias_bancarias_c=%s, referencias_comerciales_c=%s, estado_pyg_c=%s, balance_general_c=%s, flujo_caja_proy_c=%s, fotocopia_declar_renta_c=%s, fotocopia_declar_iva_c=%s, otros_doc=%s, observ_doc=%s, estado_c=%s, registrado_c=%s, revisado_c=%s, fecha_revision_c=%s, email_comercial_c=%s, otro_pago_c=%s WHERE nit_c=%s",
                       GetSQLValueString($_POST['nombre_c'], "text"),
                       GetSQLValueString($_POST['tipo_c'], "text"),
                       GetSQLValueString($_POST['fecha_ingreso_c'], "date"),
					   GetSQLValueString($_POST['fecha_solicitud_c'], "date"),                     
                       GetSQLValueString($_POST['rep_legal_c'], "text"),
                       GetSQLValueString($_POST['telefono_c'], "text"),
                       GetSQLValueString($_POST['direccion_c'], "text"),
                       GetSQLValueString($_POST['fax_c'], "text"),
                       GetSQLValueString($_POST['contacto_c'], "text"),
                       GetSQLValueString($_POST['cargo_contacto_c'], "text"),
                       GetSQLValueString($_POST['telefono_contacto_c'], "text"),
                       GetSQLValueString($_POST['celular_contacto_c'], "text"),
                       GetSQLValueString($_POST['cod_pais_c'], "text"),
                       GetSQLValueString($_POST['cod_dpto_c'], "text"),
                       GetSQLValueString($_POST['cod_ciudad_c'], "text"),
                       GetSQLValueString($_POST['email_c'], "text"),
                       GetSQLValueString($_POST['contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['cargo_contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['direccion_entrega'], "text"),
                       GetSQLValueString($_POST['email_contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['cod_pais_bodega_c'], "text"),
                       GetSQLValueString($_POST['cod_dpto_bodega_c'], "text"),
                       GetSQLValueString($_POST['cod_ciudad_bodega_c'], "text"),
                       GetSQLValueString($_POST['telefono_bodega_c'], "text"),
                       GetSQLValueString($_POST['fax_bodega_c'], "text"),
                       GetSQLValueString($_POST['direccion_envio_factura_c'], "text"),
                       GetSQLValueString($_POST['telefono_envio_factura_c'], "text"),
                       GetSQLValueString($_POST['fax_envio_factura_c'], "text"),
                       GetSQLValueString($_POST['observ_inf_c'], "text"),
                       GetSQLValueString($_POST['contacto_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['telefono_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['fax_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['direccion_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['email_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['cupo_solicitado_c'], "double"),
                       GetSQLValueString($_POST['forma_pago_c'], "text"),
                       GetSQLValueString($_POST['tel_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['nombre_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['tel_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['nombre_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['tel_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['nombre_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['telefono_1ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['nombre_1ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['telefono_2ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['nombre_2ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['telefono_3ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['nombre_3ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['observ_inf_finan_c'], "text"),
                       GetSQLValueString($_POST['cupo_aprobado_c'], "double"),
                       GetSQLValueString($_POST['plazo_aprobado_c'], "text"),
                       GetSQLValueString($_POST['observ_aprob_finan_c'], "text"),
                       GetSQLValueString($_POST['estado_comercial_c'], "text"),
                       GetSQLValueString($_POST['asesor_comercial_c'], "text"),
                       GetSQLValueString($_POST['codigo_asesor_comercial_c'], "int"),
                       GetSQLValueString($_POST['observ_asesor_com_c'], "text"),
                       GetSQLValueString(isset($_POST['camara_comercio_c']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['referencias_bancarias_c']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['referencias_comerciales_c']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['estado_pyg_c']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['balance_general_c']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['flujo_caja_proy_c']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fotocopia_declar_renta_c']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['fotocopia_declar_iva_c']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['otros_doc']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_doc'], "text"),
					   GetSQLValueString($_POST['estado_c'], "text"),
                       GetSQLValueString($_POST['registrado_c'], "text"),
                       GetSQLValueString($_POST['revisado_c'], "text"), 
					   GetSQLValueString($_POST['fecha_revision_c'], "date"),                    
                       GetSQLValueString($_POST['email_comercial_c'], "text"),
                       GetSQLValueString($_POST['otro_pago_c'], "text"),
                       GetSQLValueString($_POST['nit_c'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "listado_clientes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_ver = "-1";
if (isset($_GET['nit_c'])) {
  $colname_ver = (get_magic_quotes_gpc()) ? $_GET['nit_c'] : addslashes($_GET['nit_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver = sprintf("SELECT * FROM cliente WHERE nit_c = '%s'", $colname_ver);
$ver = mysql_query($query_ver, $conexion1) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

$colname_usuario_listado_clientes = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_listado_clientes = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_listado_clientes = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_listado_clientes);
$usuario_listado_clientes = mysql_query($query_usuario_listado_clientes, $conexion1) or die(mysql_error());
$row_usuario_listado_clientes = mysql_fetch_assoc($usuario_listado_clientes);
$totalRows_usuario_listado_clientes = mysql_num_rows($usuario_listado_clientes);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_pais = "SELECT * FROM pais ORDER BY pais ASC";
$ver_pais = mysql_query($query_ver_pais, $conexion1) or die(mysql_error());
$row_ver_pais = mysql_fetch_assoc($ver_pais);
$totalRows_ver_pais = mysql_num_rows($ver_pais);

$colname_ver_dpto = "1";
if (isset($_GET['cod_pais_c'])) {
  $colname_ver_dpto = (get_magic_quotes_gpc()) ? $_GET['cod_pais_c'] : addslashes($_GET['cod_pais_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_dpto = sprintf("SELECT * FROM provincia WHERE id_pais_provincia = '%s'", $colname_ver_dpto);
$ver_dpto = mysql_query($query_ver_dpto, $conexion1) or die(mysql_error());
$row_ver_dpto = mysql_fetch_assoc($ver_dpto);
$totalRows_ver_dpto = mysql_num_rows($ver_dpto);

$colname_ver_ciudad = "1";
if (isset($_GET['cod_dpto_c'])) {
  $colname_ver_ciudad = (get_magic_quotes_gpc()) ? $_GET['cod_dpto_c'] : addslashes($_GET['cod_dpto_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_ciudad = sprintf("SELECT * FROM municipio WHERE id_provincia_mun = '%s'", $colname_ver_ciudad);
$ver_ciudad = mysql_query($query_ver_ciudad, $conexion1) or die(mysql_error());
$row_ver_ciudad = mysql_fetch_assoc($ver_ciudad);
$totalRows_ver_ciudad = mysql_num_rows($ver_ciudad);

$colname_ver_dpto_bodega = "-1";
if (isset($_GET['cod_pais_bodega_c'])) {
  $colname_ver_dpto_bodega = (get_magic_quotes_gpc()) ? $_GET['cod_pais_bodega_c'] : addslashes($_GET['cod_pais_bodega_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_dpto_bodega = sprintf("SELECT * FROM provincia WHERE id_pais_provincia = '%s'", $colname_ver_dpto_bodega);
$ver_dpto_bodega = mysql_query($query_ver_dpto_bodega, $conexion1) or die(mysql_error());
$row_ver_dpto_bodega = mysql_fetch_assoc($ver_dpto_bodega);
$totalRows_ver_dpto_bodega = mysql_num_rows($ver_dpto_bodega);

$colname_ver_municipio_bodega = "1";
if (isset($_GET['cod_dpto_bodega_c'])) {
  $colname_ver_municipio_bodega = (get_magic_quotes_gpc()) ? $_GET['cod_dpto_bodega_c'] : addslashes($_GET['cod_dpto_bodega_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_municipio_bodega = sprintf("SELECT * FROM municipio WHERE id_provincia_mun = '%s'", $colname_ver_municipio_bodega);
$ver_municipio_bodega = mysql_query($query_ver_municipio_bodega, $conexion1) or die(mysql_error());
$row_ver_municipio_bodega = mysql_fetch_assoc($ver_municipio_bodega);
$totalRows_ver_municipio_bodega = mysql_num_rows($ver_municipio_bodega);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_d = "SELECT * FROM provincia ORDER BY provincia ASC";
$ver_d = mysql_query($query_ver_d, $conexion1) or die(mysql_error());
$row_ver_d = mysql_fetch_assoc($ver_d);
$totalRows_ver_d = mysql_num_rows($ver_d);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_m = "SELECT * FROM municipio ORDER BY municipio ASC";
$ver_m = mysql_query($query_ver_m, $conexion1) or die(mysql_error());
$row_ver_m = mysql_fetch_assoc($ver_m);
$totalRows_ver_m = mysql_num_rows($ver_m);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo17 {	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000099;
}
.Estilo31 {color: #000066; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; }
.Estilo36 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo37 {color: #000066}
.Estilo38 {
	color: #000066;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
.Estilo40 {font-size: 14px}
.Estilo41 {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 12px;
	color: #000066;
}
.Estilo43 {font-size: 12px}
.Estilo45 {
	color: #000000;
	font-size: 11px;
	font-family: Arial, Helvetica, sans-serif;
}
.Estilo48 {
	font-size: 12px;
	color: #000066;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo55 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo58 {font-size: 11px}
.Estilo62 {color: #000066; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 11px; }
.Estilo63 {font-family: Arial, Helvetica, sans-serif}
.Estilo65 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 11px; }
.Estilo66 {color: #000066; font-family: Arial, Helvetica, sans-serif; font-size: 11px; }
.Estilo67 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo69 {font-weight: bold; color: #000066;}
.Estilo74 {font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; color: #000066;}
.Estilo76 {
	font-size: 18px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
-->
</style>

<script language="JavaScript"> 
<!--
function consulta()
{
window.location ='perfil_cliente_editar.php?cod_pais_c='+document.form1.cod_pais_c.value+'&cod_dpto_c='+document.form1.cod_dpto_c.value+'&cod_ciudad_c='+document.form1.cod_ciudad_c.value+'&nombre_c='+document.form1.nombre_c.value+'&fecha_solicitud_c='+document.form1.fecha_solicitud_c.value+'&nit_c='+document.form1.nit_c.value+'&rep_legal_c='+document.form1.rep_legal_c.value+'&tipo_c='+document.form1.tipo_c.value+'&cod_pais_bodega_c='+document.form1.cod_pais_bodega_c.value+'&cod_dpto_bodega_c='+document.form1.cod_dpto_bodega_c.value+'&cod_ciudad_bodega_c='+document.form1.cod_ciudad_bodega_c.value+'&direccion_c='+document.form1.direccion_c.value+'&telefono_c='+document.form1.telefono_c.value+'&fax_c='+document.form1.fax_c.value+'&email_c='+document.form1.email_c.value+'&email_comercial_c='+document.form1.email_comercial_c.value+'&contacto_c='+document.form1.contacto_c.value+'&telefono_contacto_c='+document.form1.telefono_contacto_c.value+'&cargo_contacto_c='+document.form1.cargo_contacto_c.value+'&celular_contacto_c='+document.form1.celular_contacto_c.value+'&contacto_bodega_c='+document.form1.contacto_bodega_c.value+'&cargo_contacto_bodega_c='+document.form1.cargo_contacto_bodega_c.value+'&email_contacto_bodega_c='+document.form1.email_contacto_bodega_c.value+'&telefono_bodega_c='+document.form1.telefono_bodega_c.value+'&fax_bodega_c='+document.form1.fax_bodega_c.value+'&direccion_entrega='+document.form1.direccion_entrega.value+'&telefono_envio_factura_c='+document.form1.telefono_envio_factura_c.value+'&fax_envio_factura_c='+document.form1.fax_envio_factura_c.value+'&direccion_envio_factura_c='+document.form1.direccion_envio_factura_c.value+'&observ_inf_c='+document.form1.observ_inf_c.value;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' debe ser un e-mail.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' debe ser numerico.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' debe ser un intervalo entre '+min+' y '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' esta vacio.\n'; }
  } if (errors) alert('Favor corregir los siguientes campos:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>

</head>

<body>
<table width="737" height="100" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="5" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="2" bordercolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="454" height="19" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="Estilo48 Estilo37 Estilo37"><div align="left"><?php echo $row_usuario_listado_clientes['nombre_usuario']; ?></div></td>
          <td width="434" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo40 Estilo41"><a href="<?php echo $logoutAction ?>" class="Estilo55">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#666666">
    <td height="50" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="MM_validateForm('fecha_ingreso_c','','R','nombre_c','','R','nit_c','','R','rep_legal_c','','R','cod_pais_c','','R','email_c','','RisEmail','cod_ciudad_c','','R','email_comercial_c','','RisEmail','contacto_c','','R','telefono_contacto_c','','R','cargo_contacto_c','','R','asesor_comercial_c','','R','registrado_c','','R','direccion_c','','R');return document.MM_returnValue">
        <table width="726" height="50" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo15 Estilo17  Estilo37"><strong><span class="Estilo76">PERFIL DEL CLIENTE</span></strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo36 Estilo34 Estilo23 Estilo37 Estilo43"><strong>Codigo: R1 - F07</strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo38">Versi&oacute;n: 0 </div></td>
          </tr>
          
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td width="134" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Fecha Ingreso</span></div></td>
            <td width="223" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo31 Estilo28 Estilo58 Estilo63">
                <input name="fecha_ingreso_c" type="text" value="<?php echo $row_ver['fecha_ingreso_c']; ?>" size="10">
            </div></td>
            <td width="132" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo62">*Fecha Solicitud </div></td>
            <td width="221" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input name="fecha_solicitud_c" type="text" id="fecha_solicitud_c"  value="<?php echo $row_ver['fecha_solicitud_c']; ?>" size="10"/>            
            </div></td>
          </tr>
          
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo65">INFORMACION GENERAL </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Raz&oacute;n Social</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo15 Estilo63 Estilo58"><strong>
                <input type="text" name="nombre_c" value="<?php echo $row_ver['nombre_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo62">*Nit </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="nit_c" type="text" value="<?php echo $row_ver['nit_c']; ?>" size="32" readonly="true"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Representate Legal</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo62">
                <input type="text" name="rep_legal_c" value="<?php echo $row_ver['rep_legal_c']; ?>" size="32">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
            <div align="right" class="Estilo62">*Tipo Cliente</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo62">
              <select name="tipo_c" title="<?php echo $row_ver['tipo_c']; ?>">
                <option value="Nacional"  <?php if (!(strcmp("Nacional", $row_ver['tipo_c']))) {echo "SELECTED";} ?>>Nacional</option>
                <option value="Extranjero"  <?php if (!(strcmp("Extranjero", $row_ver['tipo_c']))) {echo "SELECTED";} ?>>Extranjero</option>
              </select>
            </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Direcci&oacute;n</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo62">
                <textarea name="direccion_c" cols="25"><?php echo $row_ver['direccion_c']; ?></textarea>
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo62">*Telefono</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_c" type="text" value="<?php echo $row_ver['telefono_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Pais</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66">
              <label>
              <input name="cod_pais_c" type="text" id="cod_pais_c" value="<?php echo $row_ver['cod_pais_c']; ?>" size="32">
              </label>
</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Fax</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fax_c" type="text" value="<?php echo $row_ver['fax_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Provincia</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66">
              <label>
              <input name="cod_dpto_c" type="text" id="cod_dpto_c" value="<?php echo $row_ver['cod_dpto_c']; ?>" size="32">
              </label>
</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>*Email</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="email_c" type="text" value="<?php echo $row_ver['email_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Ciudad</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66">
              <label>
              <input name="cod_ciudad_c" type="text" id="cod_ciudad_c" value="<?php echo $row_ver['cod_ciudad_c']; ?>" size="32">
              </label>
</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>*Email Comercial</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="email_comercial_c" type="text" id="email_comercial_c" value="<?php echo $row_ver['email_comercial_c']; ?>" size=32></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Contacto Comercial </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="contacto_c" value="<?php echo $row_ver['contacto_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>*Telefono Comercial</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_contacto_c" type="text" value="<?php echo $row_ver['telefono_contacto_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Cargo </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="cargo_contacto_c" value="<?php echo $row_ver['cargo_contacto_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Celular Comercial</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <div align="left" class="Estilo67">
                <input name="celular_contacto_c" type="text" id="celular_contacto_c" value="<?php echo $row_ver['celular_contacto_c']; ?>" size="32">
            </div></td></tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Contacto Bodega</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="contacto_bodega_c" value="<?php echo $row_ver['contacto_bodega_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Email Bodega</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="email_contacto_bodega_c" type="text" value="<?php echo $row_ver['email_contacto_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Cargo </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="cargo_contacto_bodega_c" value="<?php echo $row_ver['cargo_contacto_bodega_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Telefono Bodega</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_bodega_c" type="text" value="<?php echo $row_ver['telefono_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Pais Bodega </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <input name="cod_pais_bodega_c" type="text" id="cod_pais_bodega_c" value="<?php echo $row_ver['cod_pais_bodega_c']; ?>" size="32">
              </label>
</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Fax Bodega</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fax_bodega_c" type="text" value="<?php echo $row_ver['fax_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Provincia Bodega </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <input name="cod_dpto_bodega_c" type="text" id="cod_dpto_bodega_c" value="<?php echo $row_ver['cod_dpto_bodega_c']; ?>" size="32">
              </label>
</div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Direccion Entrega</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="direccion_entrega" value="<?php echo $row_ver['direccion_entrega']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Ciudad Bodega </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo45">
              <label>
              <input name="cod_ciudad_bodega_c" type="text" id="cod_ciudad_bodega_c" value="<?php echo $row_ver['cod_ciudad_bodega_c']; ?>" size="32">
              </label>
</div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Telefono Envio Factura </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="telefono_envio_factura_c" value="<?php echo $row_ver['telefono_envio_factura_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Direcci&oacute;n Envio Factura </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="direccion_envio_factura_c" value="<?php echo $row_ver['direccion_envio_factura_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Fax Envio Factura </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fax_envio_factura_c" type="text" value="<?php echo $row_ver['fax_envio_factura_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Observaciones</span></div></td>
            <td colspan="3" rowspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
              <textarea name="observ_inf_c" cols="70" rows="5"><?php echo $row_ver['observ_inf_c']; ?></textarea>
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo58"></span></td>
          </tr>
          
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo65">INFORMACION FINANCIERA</div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Contacto Dpto Pagos</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="contacto_dpto_pagos_c" value="<?php echo $row_ver['contacto_dpto_pagos_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Telefono</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_dpto_pagos_c" type="text" value="<?php echo $row_ver['telefono_dpto_pagos_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Direccion</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="direccion_dpto_pagos_c" value="<?php echo $row_ver['direccion_dpto_pagos_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Fax</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fax_dpto_pagos_c" type="text" value="<?php echo $row_ver['fax_dpto_pagos_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Cupo Solicitado ($)</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="cupo_solicitado_c" value="<?php echo $row_ver['cupo_solicitado_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Email</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="email_dpto_pagos_c" type="text" value="<?php echo $row_ver['email_dpto_pagos_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Forma de Pago</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <select name="forma_pago_c" title="<?php echo $row_ver['forma_pago_c']; ?>">
                  <option value="Cheque" >Cheque</option>
                  <option value="Consignacion" >Consignacion</option>
                  <option value="Transferencia" >Transferencia</option>
                  <option value="Otra" >Otra</option>
                </select>
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Otra Forma de Pago</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="otro_pago_c" type="text" id="otro_pago_c" value="<?php echo $row_ver['otro_pago_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo31 Estilo34 Estilo36 Estilo37 Estilo58">
                <div align="center"><strong>REFERENCIAS COMERCIALES</strong></div>
            </div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo65">REFERENCIAS BANCARIAS </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Referencia 1 </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="ref_comercial_c" value="<?php echo $row_ver['1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Referencia 1</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="ref_bancaria_c" type="text" value="<?php echo $row_ver['1ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Telefono</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="tel_1ref_comercial_c" value="<?php echo $row_ver['tel_1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Telefono</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_1ref_bancaria_c" type="text" value="<?php echo $row_ver['telefono_1ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Nombre</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="nombre_1ref_comercial_c" value="<?php echo $row_ver['nombre_1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Nombre</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="nombre_1ref_bancaria_c" type="text" value="<?php echo $row_ver['nombre_1ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Cupo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="cupo_1ref_comercial_c" value="<?php echo $row_ver['cupo_1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Referencia 2 </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="ref_bancaria_c2" type="text" value="<?php echo $row_ver['2ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Plazo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="plazo_1ref_comercial_c" value="<?php echo $row_ver['plazo_1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Telefono</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_2ref_bancaria_c" type="text" value="<?php echo $row_ver['telefono_2ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Referencia 2 </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="ref_comercial_c2" value="<?php echo $row_ver['2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Nombre</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="nombre_2ref_bancaria_c" type="text" value="<?php echo $row_ver['nombre_2ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Telefono</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="tel_2ref_comercial_c" value="<?php echo $row_ver['tel_2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Referencia 3 </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="ref_bancaria_c3" type="text" value="<?php echo $row_ver['3ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Nombre</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="nombre_2ref_comercial_c" value="<?php echo $row_ver['nombre_2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Telefono</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_3ref_bancaria_c" type="text" value="<?php echo $row_ver['telefono_3ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Cupo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="cupo_2ref_comercial_c" value="<?php echo $row_ver['cupo_2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Nombre</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="nombre_3ref_bancaria_c" type="text" value="<?php echo $row_ver['nombre_3ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="25" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Plazo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="plazo_2ref_comercial_c" value="<?php echo $row_ver['plazo_2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Observaciones</strong></div></td>
            <td rowspan="6" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo67">
              <textarea name="observ_inf_finan_c" cols="25" rows="10"><?php echo $row_ver['observ_inf_finan_c']; ?></textarea>
            </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Referencia 3 </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="ref_comercial_c3" value="<?php echo $row_ver['3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td rowspan="5" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo58"></span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Telefono</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="tel_3ref_comercial_c" value="<?php echo $row_ver['tel_3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Nombre</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="nombre_3ref_comercial_c" value="<?php echo $row_ver['nombre_3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Cupo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="cupo_3ref_comercial_c" value="<?php echo $row_ver['cupo_3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Plazo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="plazo_3ref_comercial_c" value="<?php echo $row_ver['plazo_3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo17 Estilo34 Estilo37 Estilo36 Estilo58"><strong>APROBACION FINANCIERA</strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Cupo Aprobado</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="cupo_aprobado_c" value="<?php echo $row_ver['cupo_aprobado_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Plazo Aprobado</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input name="plazo_aprobado_c" type="text" value="<?php echo $row_ver['plazo_aprobado_c']; ?>" size="32">
            </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Observaciones</span></div></td>
            <td colspan="3" rowspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <textarea name="observ_aprob_finan_c" cols="70" rows="5"><?php echo $row_ver['observ_aprob_finan_c']; ?></textarea>
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo58"></span></td>
          </tr>
          
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo67"><span class="Estilo74">APROBACION COMERCIAL</span></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="26" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Estado Comercial</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <select name="estado_comercial_c" title="<?php echo $row_ver['estado_comercial_c']; ?>">
                  <option value="Aceptado" >Aceptado</option>
                  <option value="Rechazado" >Rechazado</option>
                  <option value="Pendiente" >Pendiente</option>
                </select>
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Observaciones</strong></div></td>
            <td rowspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo67">
              <textarea name="observ_asesor_com_c" cols="25" rows="5"><?php echo $row_ver['observ_asesor_com_c']; ?></textarea>
            </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Asesor Comercial</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="asesor_comercial_c" value="<?php echo $row_ver['asesor_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td rowspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="24" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Codigo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input type="text" name="codigo_asesor_comercial_c" value="<?php echo $row_ver['codigo_asesor_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo65">DOCUMENTOS ADJUNTOS </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input <?php if (!(strcmp($row_ver['camara_comercio_c'],1))) {echo "checked";} ?> type="checkbox" name="camara_comercio_c" value="1" >
            Camara Comercio (vigente) </strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo62">
              <input <?php if (!(strcmp($row_ver['balance_general_c'],1))) {echo "checked";} ?> type="checkbox" name="balance_general_c" value="1" >
            Balance General (firmado por contador)</span></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input <?php if (!(strcmp($row_ver['referencias_bancarias_c'],1))) {echo "checked";} ?> type="checkbox" name="referencias_bancarias_c" value="1" >
            Referencias Bancarias </strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo62">
              <input <?php if (!(strcmp($row_ver['flujo_caja_proy_c'],1))) {echo "checked";} ?> type="checkbox" name="flujo_caja_proy_c" value="1" >
            Flujo de Caja Proyectado </span></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input <?php if (!(strcmp($row_ver['referencias_comerciales_c'],1))) {echo "checked";} ?> type="checkbox" name="referencias_comerciales_c" value="1" >
            Referencias Comerciales </strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo62">
              <input <?php if (!(strcmp($row_ver['fotocopia_declar_renta_c'],1))) {echo "checked";} ?> type="checkbox" name="fotocopia_declar_renta_c" value="1" >
            Fotocopia Ultima Declaraci&oacute;n Renta</span></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input name="estado_pyg_c" type="checkbox" value="1" <?php if (!(strcmp($row_ver['estado_pyg_c'],1))) {echo "checked";} ?> >
            Estado P&amp;G (firmado por contador)</strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo62">
              <input <?php if (!(strcmp($row_ver['fotocopia_declar_iva_c'],1))) {echo "checked";} ?> type="checkbox" name="fotocopia_declar_iva_c" value="1" >
            Fotocopia Declaraci&oacute;n IVA (3 ultimas) </span></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo67">
              <div align="left"><span class="Estilo69">
                <input <?php if (!(strcmp($row_ver['otros_doc'],1))) {echo "checked";} ?> type="checkbox" name="otros_doc" value="1" >
                Otros</span></div>
            </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">Observaciones</span></div></td>
            <td colspan="3" rowspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <textarea name="observ_doc" cols="70" rows="5" id="observ_doc"><?php echo $row_ver['observ_doc']; ?></textarea>
              </label>
            </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo65">INFORMACION DE FORMATO </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Estado del Cliente </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <select name="estado_c" title="<?php echo $row_ver['estado_c']; ?>">
                  <option value="Activo"  <?php if (!(strcmp("Activo", $row_ver['estado_c']))) {echo "SELECTED";} ?>>Activo</option>
                  <option value="Retirado"  <?php if (!(strcmp("Retirado", $row_ver['estado_c']))) {echo "SELECTED";} ?>>Retirado</option>
                  <option value="Pendiente"  <?php if (!(strcmp("Pendiente", $row_ver['estado_c']))) {echo "SELECTED";} ?>>Pendiente</option>
                </select>
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Revisado por </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="revisado_c" type="text" value="<?php echo $row_ver['revisado_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo62">*Registrado por</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo66"><strong>
                <input name="registrado_c" type="text" value="<?php echo $row_ver['registrado_c']; ?>" size="32" readonly="true">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo66"><strong>Fecha Revision</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
			<input name="fecha_revision_c" type="text" id="fecha_revision_c" value="<?php echo $row_ver['fecha_revision_c']; ?>" size="10"/>			</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo67">
                <input name="submit" type="submit" value="Insertar registro">
            </div></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
        <input type="hidden" name="MM_update" value="form1">
      </form>    </td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td height="5" bordercolor="#FFFFFF"><table width="725" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="51" height="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left"><a href="menu.php"><img src="home.gif" alt="Menu Principal" width="20" height="23" border="0"></a></div>         </td>
        <td width="148" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><a href="comercial.php" class="Estilo55">Gesti&oacute;n Comercial</a></div></td>
        <td width="148" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right">
          <div align="center"><a href="listado_clientes.php" class="Estilo55">Listado Clientes</a></div>
        </div></td>
        <td width="126" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><a href="imprimir_clientes.php?nit_c=<?php echo $row_ver['nit_c']; ?>" target="_blank" class="Estilo55">Vista Preliminar </a></div></td>
        <td width="127" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><a href="borrado_cliente.php?nit_c=<?php echo $row_ver['nit_c']; ?>" class="Estilo55">Eliminar Cliente </a></div></td>
        <td width="96" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><img src="firma3.bmp"></div></td>
      </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($ver);

mysql_free_result($usuario_listado_clientes);

mysql_free_result($ver_pais);

mysql_free_result($ver_dpto);

mysql_free_result($ver_ciudad);

mysql_free_result($ver_dpto_bodega);

mysql_free_result($ver_municipio_bodega);

mysql_free_result($ver_d);

mysql_free_result($ver_m);
?>
