<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
session_start();

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  session_unregister('MM_Username');
  session_unregister('MM_UserGroup');
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {


  $insertSQL = sprintf("INSERT INTO cliente (nit_c, nombre_c, tipo_c, fecha_ingreso_c, fecha_solicitud_c, rep_legal_c, telefono_c, direccion_c, fax_c, contacto_c, cargo_contacto_c, telefono_contacto_c, celular_contacto_c, cod_pais_c, cod_dpto_c, cod_ciudad_c, email_c, contacto_bodega_c, cargo_contacto_bodega_c, direccion_entrega, email_contacto_bodega_c, cod_pais_bodega_c, cod_dpto_bodega_c, cod_ciudad_bodega_c, telefono_bodega_c, fax_bodega_c, direccion_envio_factura_c, telefono_envio_factura_c, fax_envio_factura_c, observ_inf_c, contacto_dpto_pagos_c, telefono_dpto_pagos_c, fax_dpto_pagos_c, direccion_dpto_pagos_c, email_dpto_pagos_c, cupo_solicitado_c, forma_pago_c, `1ref_comercial_c`, tel_1ref_comercial_c, nombre_1ref_comercial_c, cupo_1ref_comercial_c, plazo_1ref_comercial_c, `2ref_comercial_c`, tel_2ref_comercial_c, nombre_2ref_comercial_c, cupo_2ref_comercial_c, plazo_2ref_comercial_c, `3ref_comercial_c`, tel_3ref_comercial_c, nombre_3ref_comercial_c, cupo_3ref_comercial_c, plazo_3ref_comercial_c, `1ref_bancaria_c`, telefono_1ref_bancaria_c, nombre_1ref_bancaria_c, `2ref_bancaria_c`, telefono_2ref_bancaria_c, nombre_2ref_bancaria_c, `3ref_bancaria_c`, telefono_3ref_bancaria_c, nombre_3ref_bancaria_c, observ_inf_finan_c, cupo_aprobado_c, plazo_aprobado_c, observ_aprob_finan_c, estado_comercial_c, asesor_comercial_c, codigo_asesor_comercial_c, observ_asesor_com_c, camara_comercio_c, referencias_bancarias_c, referencias_comerciales_c, estado_pyg_c, balance_general_c, flujo_caja_proy_c, fotocopia_declar_renta_c, fotocopia_declar_iva_c, otros_doc, observ_doc, estado_c, registrado_c, revisado_c, fecha_revision_c, email_comercial_c, otro_pago_c) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nit_c'], "text"),
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
                       GetSQLValueString($_POST['telefono_dpto_pagos_c'], "int"),
                       GetSQLValueString($_POST['fax_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['direccion_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['email_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['cupo_solicitado_c'], "double"),
                       GetSQLValueString($_POST['forma_pago_c'], "text"),
                       GetSQLValueString($_POST['ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['tel_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['nombre_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['ref_comercial_c2'], "text"),
                       GetSQLValueString($_POST['tel_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['nombre_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['ref_comercial_c3'], "text"),
                       GetSQLValueString($_POST['tel_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['nombre_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['telefono_1ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['nombre_1ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['ref_bancaria_c2'], "text"),
                       GetSQLValueString($_POST['telefono_2ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['nombre_2ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['ref_bancaria_c3'], "text"),
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
                       GetSQLValueString($_POST['otro_pago_c'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "listado_clientes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_usuario_perfil_cliente = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_perfil_cliente = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_perfil_cliente = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_perfil_cliente);
$usuario_perfil_cliente = mysql_query($query_usuario_perfil_cliente, $conexion1) or die(mysql_error());
$row_usuario_perfil_cliente = mysql_fetch_assoc($usuario_perfil_cliente);
$totalRows_usuario_perfil_cliente = mysql_num_rows($usuario_perfil_cliente);

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

$colname_ver_dpto_bodega = "1";
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo15 {color: #000066}
.Estilo23 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo28 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 11px;
}
.Estilo31 {color: #000066; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; }
.Estilo36 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
}
.Estilo45 {font-size: 12px}
.Estilo46 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.Estilo47 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #000066;
}
.Estilo55 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo56 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo59 {font-size: 11px}
.Estilo63 {color: #000066; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 11px; }
.Estilo66 {color: #000099; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 11px; }
.Estilo67 {color: #000066; font-family: Arial, Helvetica, sans-serif; font-size: 11px; }
.Estilo69 {font-weight: bold; color: #000066;}
.Estilo72 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo73 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo74 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 11px; }
.Estilo78 {font-size: 18px}
-->
</style>

<script language="javascript">
<!--
function consulta()
{
window.location ='perfil_cliente.php?cod_pais_c='+document.form1.cod_pais_c.value+
'&cod_dpto_c='+document.form1.cod_dpto_c.value+'&cod_ciudad_c='+
document.form1.cod_ciudad_c.value+'&nombre_c='+document.form1.nombre_c.value+'&fecha_solicitud_c='+
document.form1.fecha_solicitud_c.value+'&nit_c='+document.form1.nit_c.value+'&rep_legal_c='+
document.form1.rep_legal_c.value+'&tipo_c='+document.form1.tipo_c.value
+'&direccion_c='+document.form1.direccion_c.value+'&telefono_c='+document.form1.telefono_c.value
+'&fax_c='+document.form1.fax_c.value+'&email_c='+document.form1.email_c.value
+'&email_comercial_c='+document.form1.email_comercial_c.value
+'&contacto_c='+document.form1.contacto_c.value
+'&telefono_contacto_c='+document.form1.telefono_contacto_c.value
+'&cargo_contacto_c='+document.form1.cargo_contacto_c.value+'&celular_contacto_c='+
document.form1.celular_contacto_c.value


+'&cod_pais_bodega_c='+document.form1.cod_pais_bodega_c.value+'&cod_dpto_bodega_c='+
document.form1.cod_dpto_bodega_c.value+'&cod_ciudad_bodega_c='+
document.form1.cod_ciudad_bodega_c.value+'&contacto_bodega_c='+
document.form1.contacto_bodega_c.value+'&cargo_contacto_bodega_c='+
document.form1.cargo_contacto_bodega_c.value+'&email_contacto_bodega_c='+
document.form1.email_contacto_bodega_c.value+'&telefono_bodega_c='+
document.form1.telefono_bodega_c.value+'&fax_bodega_c='+
document.form1.fax_bodega_c.value+'&direccion_entrega='+
document.form1.direccion_entrega.value+'&telefono_envio_factura_c='+
document.form1.telefono_envio_factura_c.value+'&fax_envio_factura_c='+
document.form1.fax_envio_factura_c.value+'&direccion_envio_factura_c='+
document.form1.direccion_envio_factura_c.value+'&observ_inf_c='+
document.form1.observ_inf_c.value;
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
  for (i=0; i<(args.length-2); i+=3) { 
  test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' debe ser un e-mail.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' debe ser numerico.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' debe ser un intervalo numerico entre '+min+' y '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' esta vacio.\n'; }
  } if (errors) alert('Favor corregir los siguientes campos:\n'+errors);
  document.MM_returnValue = (errors == '');
  
}
//-->
</script>

</head>

<body>
<table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="356"><span class="Estilo55"><?php echo $row_usuario_perfil_cliente['nombre_usuario']; ?></span></td>
        <td width="357"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo56">Cerrar Sesi&oacute;n</a></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bgcolor="#666666">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_ingreso_c','','R','fecha_solicitud_c','','R','nombre_c','','R','nit_c','','R','rep_legal_c','','R','cod_pais_c','','R','email_c','','RisEmail','cod_ciudad_c','','R','email_comercial_c','','RisEmail','contacto_c','','R','telefono_contacto_c','','R','cargo_contacto_c','','R','email_contacto_bodega_c','','NisEmail','email_dpto_pagos_c','','NisEmail','asesor_comercial_c','','R','registrado_c','','R','direccion_c','','R');return document.MM_returnValue">
        <table width="735" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><strong><span class="Estilo47 Estilo73"><span class="Estilo78">PERFIL DEL CLIENTE</span></span></strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="2" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo23 Estilo15 Estilo45">Codigo: R1 - F07</div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo46">Versi&oacute;n: 0 </div></td>
          </tr>
          
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td width="130" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">*Fecha Ingreso</span></div></td>
            <td width="214" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo31 Estilo28">
              <input type="text" name="fecha_ingreso_c" value="<?php echo date("Y/m/d"); ?>" size="10">
            </div></td>
            <td width="160" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo63">*Fecha Solicitud </div></td>
            <td width="195" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input name="fecha_solicitud_c" type="text" id="fecha_solicitud_c"  
			value="<?php echo date("Y/m/d"); ?>" size="10"/>   		      
            </div></td>
          </tr>
          
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo66 Estilo72">INFORMACION GENERAL </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">*Raz&oacute;n Social</span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo36 Estilo15"><strong>
              <input type="text" name="nombre_c" value="<?php echo $_REQUEST['nombre_c']; ?>" size="32">
			
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo63">*Nit </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="nit_c" type="text" value="<?php echo $_REQUEST['nit_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">*Representate Legal</span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo63">
              <input type="text" name="rep_legal_c" value="<?php echo $_REQUEST['rep_legal_c']; ?>" size="32">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
            <div align="right" class="Estilo63">*Tipo Cliente</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">
            <select name="tipo_c">
              <option value="0" <?php if (!(strcmp(0, $_REQUEST['tipo_c']))) {echo "SELECTED";} ?>></option>
              <option value="Nacional"  <?php if (!(strcmp("Nacional", $_REQUEST['tipo_c']))) {echo "SELECTED";} ?>>Nacional</option>
              <option value="Extranjero"  <?php if (!(strcmp("Extranjero", $_REQUEST['tipo_c']))) {echo "SELECTED";} ?>>Extranjero</option>
            </select>
</span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">*Direcci&oacute;n</span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo63">
              <textarea name="direccion_c" cols="25"><?php echo $_REQUEST['direccion_c']; ?></textarea>
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo63">*Telefono</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_c" type="text" value="<?php echo $_REQUEST['telefono_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">*Pais</span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <input name="cod_pais_c" type="text" id="cod_pais_c" size="32">
              </label>
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Fax</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fax_c" type="text" value="<?php echo $_REQUEST['fax_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Provincia</span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <input name="cod_dpto_c" type="text" id="cod_dpto_c" size="32">
              </label>
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>*Email</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="email_c" type="text" value="<?php echo $_REQUEST['email_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">*Ciudad</span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <input name="cod_ciudad_c" type="text" id="cod_ciudad_c" size="32">
              </label>
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>*Email Comercial</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="email_comercial_c" type="text" id="email_comercial_c" value="<?php echo $_REQUEST['email_comercial_c']; ?>"size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">*Contacto Comercial </span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
                <input type="text" name="contacto_c" value="<?php echo $_REQUEST['contacto_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>*Telefono Comercial</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_contacto_c" type="text" value="<?php echo $_REQUEST['telefono_contacto_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">*Cargo </span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
                <input type="text" name="cargo_contacto_c" value="<?php echo $_REQUEST['cargo_contacto_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Celular Comercial</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="celular_contacto_c" type="text" value="<?php echo $_REQUEST['celular_contacto_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Contacto Bodega</span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="contacto_bodega_c" value="<?php echo $_REQUEST['contacto_bodega_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Email Bodega</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="email_contacto_bodega_c" type="text" value="<?php echo $_REQUEST['email_contacto_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Cargo </span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="cargo_contacto_bodega_c" value="<?php echo $_REQUEST['cargo_contacto_bodega_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Telefono Bodega</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_bodega_c" type="text" value="<?php echo $_REQUEST['telefono_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Pais Bodega </span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <input name="cod_pais_bodega_c" type="text" id="cod_pais_bodega_c" size="32">
              </label>
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Fax Bodega</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fax_bodega_c" type="text" value="<?php echo $_REQUEST['fax_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Provincia Bodega </span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <input name="cod_dpto_bodega_c" type="text" id="cod_dpto_bodega_c" size="32">
              </label>
            </div></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Direccion Entrega</span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
                <input type="text" name="direccion_entrega" value="<?php echo $_REQUEST['direccion_entrega']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Ciudad Bodega </span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67">
              <label>
              <input name="cod_ciudad_bodega_c" type="text" id="cod_ciudad_bodega_c" size="32">
              </label>
            </div></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Telefono Envio Factura </span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="telefono_envio_factura_c" value="<?php echo $_REQUEST['telefono_envio_factura_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Direcci&oacute;n Envio Factura </span></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="direccion_envio_factura_c" value="<?php echo $_REQUEST['direccion_envio_factura_c']; ?>" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Fax Envio Factura </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fax_envio_factura_c" type="text" value="<?php echo $_REQUEST['fax_envio_factura_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Observaciones</span></td>
            <td colspan="3" rowspan="2" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <textarea name="observ_inf_c" cols="70" rows="5"><?php echo $_REQUEST['observ_inf_c']; ?></textarea>
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
          </tr>
          
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo28 Estilo15 Estilo73">INFORMACION FINANCIERA</div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Contacto Dpto Pagos</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="contacto_dpto_pagos_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Telefono</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_dpto_pagos_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Direccion</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="direccion_dpto_pagos_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Fax</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fax_dpto_pagos_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Cupo Solicitado ($)</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="cupo_solicitado_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Email</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="email_dpto_pagos_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Forma de Pago</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <select name="forma_pago_c">
                  <option value="0"></option>
                  <option value="Cheque" >Cheque</option>
                  <option value="Consignacion" >Consignacion</option>
                  <option value="Transferencia" >Transferencia</option>
                  <option value="Otra" >Otra</option>
              </select>
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Otra Forma de Pago</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="otro_pago_c" type="text" id="otro_pago_c" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="2" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo31 Estilo36 Estilo15 Estilo73">
              <div align="center"><strong>REFERENCIAS COMERCIALES</strong></div>
            </div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo74">REFERENCIAS BANCARIAS </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Referencia 1 </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Referencia 1</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="ref_bancaria_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Telefono</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="tel_1ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Telefono</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_1ref_bancaria_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Nombre</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="nombre_1ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Nombre</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="nombre_1ref_bancaria_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Cupo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="cupo_1ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Referencia 2 </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="ref_bancaria_c2" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Plazo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="plazo_1ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Telefono</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_2ref_bancaria_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Referencia 2 </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="ref_comercial_c2" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Nombre</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="nombre_2ref_bancaria_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Telefono</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="tel_2ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Referencia 3 </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="ref_bancaria_c3" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Nombre</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="nombre_2ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Telefono</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="telefono_3ref_bancaria_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Cupo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="cupo_2ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Nombre</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="nombre_3ref_bancaria_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="25" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Plazo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="plazo_2ref_comercial_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Observaciones</strong></div></td>
            <td rowspan="6" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo36">
              <textarea name="observ_inf_finan_c" cols="25" rows="10"></textarea>
            </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Referencia 3 </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="ref_comercial_c3" value="" size="32">
            </strong></div></td>
            <td rowspan="5" bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Telefono</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="tel_3ref_comercial_c" value="" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Nombre</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="nombre_3ref_comercial_c" value="" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Cupo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="cupo_3ref_comercial_c" value="" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Plazo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="plazo_3ref_comercial_c" value="" size="32">
            </strong></div></td>
          </tr>
          
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo72 Estilo15 Estilo59"><strong>APROBACION  FINANCIERA</strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Cupo Aprobado</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="cupo_aprobado_c" value="" size="32">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Plazo Aprobado</strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="plazo_aprobado_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Observaciones</span></div></td>
            <td colspan="3" rowspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <textarea name="observ_aprob_finan_c" cols="70" rows="5"></textarea>
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
          </tr>
          
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo36"><span class="Estilo23">APROBACION COMERCIAL</span></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="26" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">*Estado Comercial</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <select name="estado_comercial_c">
                  <option value="Pendiente" >Pendiente</option>
                  <option value="Aceptado">Aceptado</option>
                  <option value="Rechazado">Rechazado</option>
                </select>
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Observaciones</strong></div></td>
            <td rowspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo36">
              <textarea name="observ_asesor_com_c" cols="25" rows="5"></textarea>
            </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">*Asesor Comercial</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="asesor_comercial_c" value="" size="32">
            </strong></div></td>
            <td rowspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo36"></div>              <div align="right" class="Estilo36"></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="24" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">Codigo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="text" name="codigo_asesor_comercial_c" value="" size="32">
            </strong></div></td>
          </tr>
          
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo74">DOCUMENTOS ADJUNTOS </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="checkbox" name="camara_comercio_c" value="" >
            Camara Comercio (vigente) </strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">
              <input type="checkbox" name="balance_general_c" value="" >
            Balance General (firmado por contador)</span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="checkbox" name="referencias_bancarias_c" value="" >
            Referencias Bancarias </strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">
              <input type="checkbox" name="flujo_caja_proy_c" value="" >
            Flujo de Caja Proyectado </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="checkbox" name="referencias_comerciales_c" value="" >
            Referencias Comerciales </strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">
              <input type="checkbox" name="fotocopia_declar_renta_c" value="" >
            Fotocopia Ultima Declaraci&oacute;n Renta</span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input type="checkbox" name="estado_pyg_c" value="" >
            Estado P&amp;G (firmado por contador)</strong></div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">
              <input type="checkbox" name="fotocopia_declar_iva_c" value="" >
            Fotocopia Declaraci&oacute;n IVA (3 ultimas) </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo36">
              <div align="left"><span class="Estilo69">
                <input type="checkbox" name="otros_doc" value="" >
                Otros</span></div>
            </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo63">Observaciones</span></td>
            <td colspan="3" rowspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo36">
              <label>
              <textarea name="observ_doc" cols="70" rows="5" id="observ_doc"></textarea>
              </label>
            </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
          </tr>
          
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo74">INFORMACION DE FORMATO </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">*Estado del Cliente </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <select name="estado_c">
                  <option value="Pendiente" >Pendiente</option>
                  <option value="Activo">Activo</option>
                  <option value="Retirado">Retirado</option>
                </select>
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Revisado por </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="revisado_c" type="text" value="" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo63">*Registrado por</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo67"><strong>
              <input name="registrado_c" type="text" onBlur="MM_validateForm('fecha_ingreso_c','','R','nombre_c','','R','nit_c','','RisNum','email_contacto_bodega_c','','NisEmail','telefono_bodega_c','','NisNum','fax_bodega_c','','NisNum','telefono_envio_factura_c','','NisNum','fax_envio_factura_c','','NisNum');return document.MM_returnValue" value="<?php echo $row_usuario_perfil_cliente['nombre_usuario']; ?>" size="32" readonly="true">
            </strong></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo67"><strong>Fecha Revision</strong></div></td>
          <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="fecha_revision_c" type="text" id="fecha_revision_c" value="<?php echo date("Y/m/d"); ?>" size="10"/>         </td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo36">
              <input type="submit" value="Insertar registro">
            </div></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>    </td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="131" height="34" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left"><a href="menu.php"><img src="home.gif" alt="Menu Principal" width="20" height="23" border="0"></a></div></td>
        <td width="225" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><a href="comercial.php" class="Estilo67 Estilo59 Estilo73">Gesti&oacute;n Comercial</a></div></td>
        <td width="240" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><a href="listado_clientes.php" class="Estilo56">Listado Maestro de Clientes </a></div></td>
        <td width="116" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><img src="firma3.bmp"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_perfil_cliente);

mysql_free_result($ver_pais);

mysql_free_result($ver_dpto);

mysql_free_result($ver_ciudad);

mysql_free_result($ver_dpto_bodega);

mysql_free_result($ver_municipio_bodega);
?>
