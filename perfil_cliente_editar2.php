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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$fecha1=$_POST['fecha_solicitud_c'];
$fecha2=$_POST['fecha_revision_c'];
$mes1=substr($fecha1,0,2);
$dia1=substr($fecha1,3,2);
$ano1=substr($fecha1,6,4);
$mes2=substr($fecha2,0,2);
$dia2=substr($fecha2,3,2);
$ano2=substr($fecha2,6,4);
$fecha1=$ano1."/".$mes1."/".$dia1;
$fecha2=$ano2."/".$mes2."/".$dia2;

  $updateSQL = sprintf("UPDATE cliente SET nombre_c=%s, tipo_c=%s, fecha_ingreso_c=%s, fecha_solicitud_c='$fecha1', rep_legal_c=%s, telefono_c=%s, direccion_c=%s, fax_c=%s, contacto_c=%s, cargo_contacto_c=%s, telefono_contacto_c=%s, celular_contacto_c=%s, cod_pais_c=%s, cod_dpto_c=%s, cod_ciudad_c=%s, email_c=%s, contacto_bodega_c=%s, cargo_contacto_bodega_c=%s, direccion_entrega=%s, email_contacto_bodega_c=%s, cod_pais_bodega_c=%s, cod_dpto_bodega_c=%s, cod_ciudad_bodega_c=%s, telefono_bodega_c=%s, fax_bodega_c=%s, direccion_envio_factura_c=%s, telefono_envio_factura_c=%s, fax_envio_factura_c=%s, observ_inf_c=%s, contacto_dpto_pagos_c=%s, telefono_dpto_pagos_c=%s, fax_dpto_pagos_c=%s, direccion_dpto_pagos_c=%s, email_dpto_pagos_c=%s, cupo_solicitado_c=%s, forma_pago_c=%s, tel_1ref_comercial_c=%s, nombre_1ref_comercial_c=%s, cupo_1ref_comercial_c=%s, plazo_1ref_comercial_c=%s, tel_2ref_comercial_c=%s, nombre_2ref_comercial_c=%s, cupo_2ref_comercial_c=%s, plazo_2ref_comercial_c=%s, tel_3ref_comercial_c=%s, nombre_3ref_comercial_c=%s, cupo_3ref_comercial_c=%s, plazo_3ref_comercial_c=%s, telefono_1ref_bancaria_c=%s, nombre_1ref_bancaria_c=%s, telefono_2ref_bancaria_c=%s, nombre_2ref_bancaria_c=%s, telefono_3ref_bancaria_c=%s, nombre_3ref_bancaria_c=%s, observ_inf_finan_c=%s, cupo_aprobado_c=%s, plazo_aprobado_c=%s, observ_aprob_finan_c=%s, estado_comercial_c=%s, asesor_comercial_c=%s, codigo_asesor_comercial_c=%s, observ_asesor_com_c=%s, camara_comercio_c=%s, referencias_bancarias_c=%s, referencias_comerciales_c=%s, estado_pyg_c=%s, balance_general_c=%s, flujo_caja_proy_c=%s, fotocopia_declar_renta_c=%s, fotocopia_declar_iva_c=%s, otros_doc=%s, estado_c=%s, registrado_c=%s, revisado_c=%s, fecha_revision_c='$fecha2', email_comercial_c=%s, otro_pago_c=%s WHERE nit_c=%s",
                       GetSQLValueString($_POST['nombre_c'], "text"),
                       GetSQLValueString($_POST['tipo_c'], "text"),
                       GetSQLValueString($_POST['fecha_ingreso_c'], "date"),
                      
                       GetSQLValueString($_POST['rep_legal_c'], "text"),
                       GetSQLValueString($_POST['telefono_c'], "int"),
                       GetSQLValueString($_POST['direccion_c'], "text"),
                       GetSQLValueString($_POST['fax_c'], "int"),
                       GetSQLValueString($_POST['contacto_c'], "text"),
                       GetSQLValueString($_POST['cargo_contacto_c'], "text"),
                       GetSQLValueString($_POST['telefono_contacto_c'], "int"),
                       GetSQLValueString($_POST['celular_contacto_c'], "int"),
                       GetSQLValueString($_POST['cod_pais_c'], "int"),
                       GetSQLValueString($_POST['cod_dpto_c'], "int"),
                       GetSQLValueString($_POST['cod_ciudad_c'], "int"),
                       GetSQLValueString($_POST['email_c'], "text"),
                       GetSQLValueString($_POST['contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['cargo_contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['direccion_entrega'], "text"),
                       GetSQLValueString($_POST['email_contacto_bodega_c'], "text"),
                       GetSQLValueString($_POST['cod_pais_bodega_c'], "int"),
                       GetSQLValueString($_POST['cod_dpto_bodega_c'], "int"),
                       GetSQLValueString($_POST['cod_ciudad_bodega_c'], "int"),
                       GetSQLValueString($_POST['telefono_bodega_c'], "int"),
                       GetSQLValueString($_POST['fax_bodega_c'], "int"),
                       GetSQLValueString($_POST['direccion_envio_factura_c'], "text"),
                       GetSQLValueString($_POST['telefono_envio_factura_c'], "int"),
                       GetSQLValueString($_POST['fax_envio_factura_c'], "int"),
                       GetSQLValueString($_POST['observ_inf_c'], "text"),
                       GetSQLValueString($_POST['contacto_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['telefono_dpto_pagos_c'], "int"),
                       GetSQLValueString($_POST['fax_dpto_pagos_c'], "int"),
                       GetSQLValueString($_POST['direccion_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['email_dpto_pagos_c'], "text"),
                       GetSQLValueString($_POST['cupo_solicitado_c'], "double"),
                       GetSQLValueString($_POST['forma_pago_c'], "text"),
                       GetSQLValueString($_POST['tel_1ref_comercial_c'], "int"),
                       GetSQLValueString($_POST['nombre_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_1ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['tel_2ref_comercial_c'], "int"),
                       GetSQLValueString($_POST['nombre_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_2ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['tel_3ref_comercial_c'], "int"),
                       GetSQLValueString($_POST['nombre_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['cupo_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['plazo_3ref_comercial_c'], "text"),
                       GetSQLValueString($_POST['telefono_1ref_bancaria_c'], "int"),
                       GetSQLValueString($_POST['nombre_1ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['telefono_2ref_bancaria_c'], "int"),
                       GetSQLValueString($_POST['nombre_2ref_bancaria_c'], "text"),
                       GetSQLValueString($_POST['telefono_3ref_bancaria_c'], "int"),
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
                       GetSQLValueString($_POST['estado_c'], "text"),
                       GetSQLValueString($_POST['registrado_c'], "text"),
                       GetSQLValueString($_POST['revisado_c'], "text"),
            
                       GetSQLValueString($_POST['otra_forma_c'], "text"),
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

$colname_ver = "1";
if (isset($_GET['nit_c'])) {
  $colname_ver = (get_magic_quotes_gpc()) ? $_GET['nit_c'] : addslashes($_GET['nit_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver = sprintf("SELECT * FROM cliente WHERE nit_c = '%s'  ", $colname_ver);
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
.Estilo7 {font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
	font-style: italic;
	color: #000066;
}
.Estilo14 {	font-size: 14px;
	font-family: Georgia, "Times New Roman", Times, serif;
	font-weight: bold;
	color: #000099;
}
.Estilo17 {	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000099;
}
.Estilo31 {color: #000066; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; }
.Estilo33 {color: #000066; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; font-weight: bold; }
.Estilo35 {color: #000099; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo36 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo37 {color: #000066}
.Estilo38 {
	color: #000066;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
.Estilo39 {font-weight: bold}
.Estilo40 {font-size: 14px}
.Estilo41 {font-family: Georgia, "Times New Roman", Times, serif}
.Estilo42 {
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size: 14px;
	font-weight: bold;
}
.Estilo43 {font-size: 12px}
.Estilo44 {font-size: 18px}
.Estilo45 {color: #000000}
-->
</style>
<script language="JavaScript" src="calendar2.js"></script>
<script language="JavaScript" src="calendar1.js"></script> 
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
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>

</head>

<body>
<table width="925" border="5" align="center" cellspacing="3">
  <tr bgcolor="#CCCCCC">
    <td width="188" height="100"><a href="menu.php"><img name="logo_acyc" src="logo_acyc.gif" width="188" height="103" border="0" id="index_r1_c1" alt="" /></a></td>
    <td width="720"><a href="menu.php"><img name="index_r1_c2" src="index_r1_c2.gif" width="707" height="103" border="0" id="index_r1_c2" alt="" /></a></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td colspan="2"><table width="907" border="1" cellspacing="3">
        <tr>
          <td width="454"><span class="Estilo14"><?php echo $row_usuario_listado_clientes['nombre_usuario']; ?></span></td>
          <td width="434"><div align="right" class="Estilo40 Estilo41"><a href="<?php echo $logoutAction ?>"><strong>Cerrar Sesi&oacute;n</strong></a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#666666">
    <td colspan="2">
      <form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
        <table width="902" align="center">
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="4" align="right" nowrap><div align="center" class="Estilo15 Estilo17  Estilo37"><strong><span class="Estilo44">PERFIL DEL CLIENTE</span> <span class="Estilo40">(Actualizar)</span> </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="2" align="right" nowrap bgcolor="#999999"><div align="center" class="Estilo36 Estilo34 Estilo23 Estilo37 Estilo43"><strong>Codigo: R1 - F07</strong></div></td>
            <td colspan="2"><div align="center" class="Estilo38">Versi&oacute;n: 0 </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Fecha Ingreso</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31 Estilo28">
                <input type="date" name="fecha_ingreso_c" value="<?php echo $row_ver['fecha_ingreso_c']; ?>" size="32">
            </div></td>
            <td><div align="right" class="Estilo31"><strong>Fecha Solicitud </strong></div></td>
            <td><input name="fecha_solicitud_c" type="date" id="fecha_solicitud_c"  value="<?php 

$mes1=substr($row_ver['fecha_solicitud_c'] ,5,2);
$dia1=substr($row_ver['fecha_solicitud_c'] ,8,2);
$ano1=substr($row_ver['fecha_solicitud_c'] ,0,4);

$fecha1=$mes1."/".$dia1."/".$ano1;
echo $fecha1;

?>" size="32" readonly="true" />
              <a href="javascript:cal1.popup();" ><img src="cal.gif" width="16" height="16" border="0" align="top">
			  </a></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="4" align="right" nowrap bgcolor="#999999"><div align="center" class="Estilo35">INFORMACION GENERAL </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Raz&oacute;n Social</span></td>
            <td align="right" nowrap><div align="left" class="Estilo36 Estilo15"><strong>
                <input type="text" name="nombre_c" value="<?php echo $row_ver['nombre_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Nit </strong></div></td>
            <td><input name="nit_c" type="text" value="<?php echo $row_ver['nit_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Representate Legal</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="rep_legal_c" value="<?php echo $row_ver['rep_legal_c']; ?>" size="32">
            </strong></div></td>
            <td>
              <div align="right" class="Estilo31"><strong>Tipo Cliente</strong></div></td>
            <td><span class="Estilo33">
              <select name="tipo_c" title="<?php echo $row_ver['tipo_c']; ?>">
                <option value="Nacional"  <?php if (!(strcmp("Nacional", $row_ver['tipo_c']))) {echo "SELECTED";} ?>>Nacional</option>
                <option value="Extranjero"  <?php if (!(strcmp("Extranjero", $row_ver['tipo_c']))) {echo "SELECTED";} ?>>Extranjero</option>
              </select>
            </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Direcci&oacute;n</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="direccion_c" value="<?php echo $row_ver['direccion_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Telefono</strong></div></td>
            <td><input name="telefono_c" type="text" value="<?php echo $row_ver['telefono_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Pais</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31">				<?php
$var=$_GET['cod_pais_c'];
if ($var<>'')
{

?>                <span class="Estilo45"><?php echo $row_ver['pais']; ?></span><strong><strong><strong><strong><strong><strong><strong><strong>
                <select name="cod_pais_c" id="select7" onBlur="consulta()">
                  <option value="0" <?php if (!(strcmp(0, $_GET['cod_pais_c']))) {echo "SELECTED";} ?>></option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_pais['id_pais']?>"<?php if (!(strcmp($row_ver_pais['id_pais'], $_GET['cod_pais_c']))) {echo "SELECTED";} ?>><?php echo $row_ver_pais['pais']?></option>
                  <?php
} while ($row_ver_pais = mysql_fetch_assoc($ver_pais));
  $rows = mysql_num_rows($ver_pais);
  if($rows > 0) {
      mysql_data_seek($ver_pais, 0);
	  $row_ver_pais = mysql_fetch_assoc($ver_pais);
  }
?>
                </select>
<?php
}
else
{
?>
                <strong><strong><strong><strong><strong><strong><strong><strong>
                <select name="cod_pais_c" id="cod_pais_c" onBlur="consulta()">
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_pais['id_pais']?>"<?php if (!(strcmp($row_ver_pais['id_pais'], $row_ver['cod_pais_c']))) {echo "SELECTED";} ?>><?php echo $row_ver_pais['pais']?></option>
                  <?php
} while ($row_ver_pais = mysql_fetch_assoc($ver_pais));
  $rows = mysql_num_rows($ver_pais);
  if($rows > 0) {
      mysql_data_seek($ver_pais, 0);
	  $row_ver_pais = mysql_fetch_assoc($ver_pais);
  }
?>
                </select>
<?php
}
?>
                </strong></strong></strong></strong></strong></strong></strong></strong> </strong></strong></strong></strong> </strong></strong> </strong> </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Fax</strong></div></td>
            <td><input name="fax_c" type="text" value="<?php echo $row_ver['fax_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Provincia</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31">                                <span class="Estilo45">
              <?php
$var=$_GET['cod_pais_c'];
if ($var<>'')
{

?>
              <?php echo $row_ver['provincia']; ?></span><strong><strong><strong><strong><strong><strong>
                <select name="cod_dpto_c" id="select2" onBlur="consulta()">
                  <option value="0" <?php if (!(strcmp(0, $_GET['cod_dpto_c']))) {echo "SELECTED";} ?>></option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_dpto['id_provincia']?>"<?php if (!(strcmp($row_ver_dpto['id_provincia'], $_GET['cod_dpto_c']))) {echo "SELECTED";} ?>><?php echo $row_ver_dpto['provincia']?></option>
                  <?php
} while ($row_ver_dpto = mysql_fetch_assoc($ver_dpto));
  $rows = mysql_num_rows($ver_dpto);
  if($rows > 0) {
      mysql_data_seek($ver_dpto, 0);
	  $row_ver_dpto = mysql_fetch_assoc($ver_dpto);
  }
?>
                </select>
                <strong><strong><strong><strong><strong><strong><strong><strong>
                <?php
}
else
{
?>
                </strong></strong></strong></strong></strong></strong></strong></strong>
                <strong><strong><strong><strong><strong><strong>
                <select name="cod_dpto_c" id="select8" onBlur="consulta()">
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_d['id_provincia']?>"<?php if (!(strcmp($row_ver_d['id_provincia'], $row_ver['cod_dpto_c']))) {echo "SELECTED";} ?>><?php echo $row_ver_d['provincia']?></option>
                  <?php
} while ($row_ver_d = mysql_fetch_assoc($ver_d));
  $rows = mysql_num_rows($ver_d);
  if($rows > 0) {
      mysql_data_seek($ver_d, 0);
	  $row_ver_d = mysql_fetch_assoc($ver_d);
  }
?>
                </select>
                <strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong>
                <?php
}
?>
                </strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong> </strong></strong></strong></strong></strong></strong> </strong></strong></strong> </strong></strong> </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Email</strong></div></td>
            <td><input name="email_c" type="text" value="<?php echo $row_ver['email_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Ciudad</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31">                                <span class="Estilo45">
              <?php
$var=$_GET['cod_pais_c'];
if ($var<>'')
{

?>
              <?php echo $row_ver['municipio']; ?></span><strong><strong><strong><strong><strong><strong>
                <select name="cod_ciudad_c" id="select3" >
                  <option value="0" <?php if (!(strcmp(0, $_GET['cod_ciudad_c']))) {echo "SELECTED";} ?>></option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_ciudad['id_municipio']?>"<?php if (!(strcmp($row_ver_ciudad['id_municipio'], $_GET['cod_ciudad_c']))) {echo "SELECTED";} ?>><?php echo $row_ver_ciudad['municipio']?></option>
                  <?php
} while ($row_ver_ciudad = mysql_fetch_assoc($ver_ciudad));
  $rows = mysql_num_rows($ver_ciudad);
  if($rows > 0) {
      mysql_data_seek($ver_ciudad, 0);
	  $row_ver_ciudad = mysql_fetch_assoc($ver_ciudad);
  }
?>
                </select>
                <strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong>
                <?php
}
else
{
?>
                </strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong>
                <strong><strong><strong><strong><strong><strong>
                <select name="cod_ciudad_c" id="select9" >
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_m['id_municipio']?>"<?php if (!(strcmp($row_ver_m['id_municipio'], $row_ver['cod_ciudad_c']))) {echo "SELECTED";} ?>><?php echo $row_ver_m['municipio']?></option>
                  <?php
} while ($row_ver_m = mysql_fetch_assoc($ver_m));
  $rows = mysql_num_rows($ver_m);
  if($rows > 0) {
      mysql_data_seek($ver_m, 0);
	  $row_ver_m = mysql_fetch_assoc($ver_m);
  }
?>
                </select>
                <strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong><strong>
                <?php
}
?>
                </strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong></strong> </strong></strong></strong></strong></strong></strong> </strong></strong></strong> </strong></strong> </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Email Comercial</strong></div></td>
            <td><input name="email_comercial_c" type="text" id="email_comercial_c" value="<?php echo $row_ver['email_comercial_c']; ?>" size=32></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Contacto Comercial </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="contacto_c" value="<?php echo $row_ver['contacto_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Telefono Comercial</strong></div></td>
            <td><input name="telefono_contacto_c" type="text" value="<?php echo $row_ver['telefono_contacto_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Cargo </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="cargo_contacto_c" value="<?php echo $row_ver['cargo_contacto_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Celular Comercial</strong></div></td>
            <td><input name="celular_contacto_c" type="text" value="<?php echo $row_ver['celular_contacto_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Contacto Bodega</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="contacto_bodega_c" value="<?php echo $row_ver['contacto_bodega_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Email Bodega</strong></div></td>
            <td><input name="email_contacto_bodega_c" type="text" value="<?php echo $row_ver['email_contacto_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Cargo </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="cargo_contacto_bodega_c" value="<?php echo $row_ver['cargo_contacto_bodega_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Telefono Bodega</strong></div></td>
            <td><input name="telefono_bodega_c" type="text" value="<?php echo $row_ver['telefono_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Pais Bodega </span></td>
            <td align="right" nowrap><div align="left"><span class="Estilo31 Estilo45"><strong>                <strong><strong>                </strong></strong></strong></span><span class="Estilo31">
              <?php
$var=$_GET['cod_pais_bodega_c'];
if ($var<>'')
{

?>
            </span><span class="Estilo45"><?php

$pais_b=$row_ver['cod_pais_bodega_c']; 
$sql="select * from pais where id_pais='$pais_b'";
$result=mysql_query($sql);
$nom=mysql_result($result,0,'pais');
echo $nom;
?><span class="Estilo31 Estilo45"><strong><strong><strong>
                <select name="cod_pais_bodega_c" id="select4" onBlur="consulta()">
                  <option value="0" <?php if (!(strcmp(0, $_GET['cod_pais_bodega_c']))) {echo "SELECTED";} ?>></option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_pais['id_pais']?>"<?php if (!(strcmp($row_ver_pais['id_pais'], $_GET['cod_pais_bodega_c']))) {echo "SELECTED";} ?>><?php echo $row_ver_pais['pais']?></option>
                  <?php
} while ($row_ver_pais = mysql_fetch_assoc($ver_pais));
  $rows = mysql_num_rows($ver_pais);
  if($rows > 0) {
      mysql_data_seek($ver_pais, 0);
	  $row_ver_pais = mysql_fetch_assoc($ver_pais);
  }
?>
                </select>
            </strong></strong></strong></span></span>  <strong><strong><strong><strong><strong><strong><strong><strong>
            <?php
}
else
{
?>
            </strong></strong></strong></strong></strong></strong></strong></strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Fax Bodega</strong></div></td>
            <td><input name="fax_bodega_c" type="text" value="<?php echo $row_ver['fax_bodega_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Provincia Bodega </span></td>
            <td align="right" nowrap><div align="left"><span class="Estilo31 Estilo45"><strong>                <strong><strong><strong>                </strong></strong></strong></strong></span><span class="Estilo45"><?php echo $row_ver['cod_dpto_bodega_c']; ?><span class="Estilo31 Estilo45"><strong><strong><strong><strong>
            <select name="cod_dpto_bodega_c" id="select5" onBlur="consulta()">
              <option value="0" <?php if (!(strcmp(0, $_GET['cod_dpto_bodega_c']))) {echo "SELECTED";} ?>></option>
              <?php
do {  
?>
              <option value="<?php echo $row_ver_dpto_bodega['id_provincia']?>"<?php if (!(strcmp($row_ver_dpto_bodega['id_provincia'], $_GET['cod_dpto_bodega_c']))) {echo "SELECTED";} ?>><?php echo $row_ver_dpto_bodega['provincia']?></option>
              <?php
} while ($row_ver_dpto_bodega = mysql_fetch_assoc($ver_dpto_bodega));
  $rows = mysql_num_rows($ver_dpto_bodega);
  if($rows > 0) {
      mysql_data_seek($ver_dpto_bodega, 0);
	  $row_ver_dpto_bodega = mysql_fetch_assoc($ver_dpto_bodega);
  }
?>
            </select>
            </strong></strong></strong></strong></span></span>  </div></td>
            <td align="right" nowrap><span class="Estilo33">Direccion Entrega</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="direccion_entrega" value="<?php echo $row_ver['direccion_entrega']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Ciudad Bodega </span></td>
            <td align="right" nowrap><div align="left" class="Estilo45">                <?php echo $row_ver['cod_ciudad_bodega_c']; ?>
            <select name="cod_ciudad_bodega_c" id="select6" >
              <option value="0"></option>
              <?php
do {  
?>
              <option value="<?php echo $row_ver_municipio_bodega['id_municipio']?>"><?php echo $row_ver_municipio_bodega['municipio']?></option>
              <?php
} while ($row_ver_municipio_bodega = mysql_fetch_assoc($ver_municipio_bodega));
  $rows = mysql_num_rows($ver_municipio_bodega);
  if($rows > 0) {
      mysql_data_seek($ver_municipio_bodega, 0);
	  $row_ver_municipio_bodega = mysql_fetch_assoc($ver_municipio_bodega);
  }
?>
            </select>
</div></td>
            <td align="right" nowrap><span class="Estilo33">Telefono Envio Factura </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="telefono_envio_factura_c" value="<?php echo $row_ver['telefono_envio_factura_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Direcci&oacute;n Envio Factura </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="direccion_envio_factura_c" value="<?php echo $row_ver['direccion_envio_factura_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Fax Envio Factura </strong></div></td>
            <td><input name="fax_envio_factura_c" type="text" value="<?php echo $row_ver['fax_envio_factura_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Observaciones</span></td>
            <td colspan="3" rowspan="2" align="right" nowrap><div align="left" class="Estilo31"><strong>
                <textarea name="observ_inf_c" cols="82" rows="5"><?php echo $row_ver['observ_inf_c']; ?></textarea>
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="4" align="right" nowrap bgcolor="#999999"><div align="center" class="Estilo34 Estilo39">INFORMACION FINANCIERA</div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Contacto Dpto Pagos</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="contacto_dpto_pagos_c" value="<?php echo $row_ver['contacto_dpto_pagos_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Telefono</strong></div></td>
            <td><input name="telefono_dpto_pagos_c" type="text" value="<?php echo $row_ver['telefono_dpto_pagos_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Direccion</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="direccion_dpto_pagos_c" value="<?php echo $row_ver['direccion_dpto_pagos_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Fax</strong></div></td>
            <td><input name="fax_dpto_pagos_c" type="text" value="<?php echo $row_ver['fax_dpto_pagos_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Cupo Solicitado ($)</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="cupo_solicitado_c" value="<?php echo $row_ver['cupo_solicitado_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Email</strong></div></td>
            <td><input name="email_dpto_pagos_c" type="text" value="<?php echo $row_ver['email_dpto_pagos_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Forma de Pago</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <select name="forma_pago_c" title="<?php echo $row_ver['forma_pago_c']; ?>">
                  <option value="Cheque" >Cheque</option>
                  <option value="Consignacion" >Consignacion</option>
                  <option value="Transferencia" >Transferencia</option>
                  <option value="Otra" >Otra</option>
                </select>
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Otra Forma de Pago</strong></div></td>
            <td><input name="otro_pago_c" type="text" id="otro_pago_c" value="<?php echo $row_ver['otro_pago_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="2" align="right" nowrap><div align="left" class="Estilo31 Estilo40 Estilo34">
                <div align="center"><strong>REFERENCIAS COMERCIALES</strong></div>
            </div></td>
            <td colspan="2"><div align="center" class="Estilo17"><strong>REFERENCIAS BANCARIAS </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Referencia 1 </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="ref_comercial_c" value="<?php echo $row_ver['1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Referencia 1</strong></div></td>
            <td><input name="ref_bancaria_c" type="text" value="<?php echo $row_ver['1ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Telefono</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="tel_1ref_comercial_c" value="<?php echo $row_ver['tel_1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Telefono</strong></div></td>
            <td><input name="telefono_1ref_bancaria_c" type="text" value="<?php echo $row_ver['telefono_1ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Nombre</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="nombre_1ref_comercial_c" value="<?php echo $row_ver['nombre_1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Nombre</strong></div></td>
            <td><input name="nombre_1ref_bancaria_c" type="text" value="<?php echo $row_ver['nombre_1ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Cupo</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="cupo_1ref_comercial_c" value="<?php echo $row_ver['cupo_1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Referencia 2 </strong></div></td>
            <td><input name="ref_bancaria_c2" type="text" value="<?php echo $row_ver['2ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Plazo</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="plazo_1ref_comercial_c" value="<?php echo $row_ver['plazo_1ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Telefono</strong></div></td>
            <td><input name="telefono_2ref_bancaria_c" type="text" value="<?php echo $row_ver['telefono_2ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Referencia 2 </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="ref_comercial_c2" value="<?php echo $row_ver['2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Nombre</strong></div></td>
            <td><input name="nombre_2ref_bancaria_c" type="text" value="<?php echo $row_ver['nombre_2ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Telefono</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="tel_2ref_comercial_c" value="<?php echo $row_ver['tel_2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Referencia 3 </strong></div></td>
            <td><input name="ref_bancaria_c3" type="text" value="<?php echo $row_ver['3ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Nombre</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="nombre_2ref_comercial_c" value="<?php echo $row_ver['nombre_2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Telefono</strong></div></td>
            <td><input name="telefono_3ref_bancaria_c" type="text" value="<?php echo $row_ver['telefono_3ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Cupo</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="cupo_2ref_comercial_c" value="<?php echo $row_ver['cupo_2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Nombre</strong></div></td>
            <td><input name="nombre_3ref_bancaria_c" type="text" value="<?php echo $row_ver['nombre_3ref_bancaria_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="25" align="right" nowrap><span class="Estilo33">Plazo</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="plazo_2ref_comercial_c" value="<?php echo $row_ver['plazo_2ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Observaciones</strong></div></td>
            <td rowspan="6"><textarea name="observ_inf_finan_c" cols="25" rows="10"><?php echo $row_ver['observ_inf_finan_c']; ?></textarea></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Referencia 3 </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="ref_comercial_c3" value="<?php echo $row_ver['3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td rowspan="5">&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Telefono</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="tel_3ref_comercial_c" value="<?php echo $row_ver['tel_3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Nombre</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="nombre_3ref_comercial_c" value="<?php echo $row_ver['nombre_3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Cupo</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="cupo_3ref_comercial_c" value="<?php echo $row_ver['cupo_3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Plazo</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="plazo_3ref_comercial_c" value="<?php echo $row_ver['plazo_3ref_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap bgcolor="#999999"><div align="center" class="Estilo17 Estilo34"><strong>APROBACION FINANCIERA</strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Cupo Aprobado</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="cupo_aprobado_c" value="<?php echo $row_ver['cupo_aprobado_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Plazo Aprobado</strong></div></td>
            <td><input name="plazo_aprobado_c" type="text" value="<?php echo $row_ver['plazo_aprobado_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Observaciones</span></td>
            <td colspan="3" rowspan="2" align="right" nowrap><div align="left" class="Estilo31"><strong>
                <textarea name="observ_aprob_finan_c" cols="82" rows="5"><?php echo $row_ver['observ_aprob_finan_c']; ?></textarea>
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#999999">
            <td colspan="4" align="right" nowrap><div align="center"><span class="Estilo35">APROBACION COMERCIAL</span></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="26" align="right" nowrap><span class="Estilo33">Estado Comercial</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <select name="estado_comercial_c" title="<?php echo $row_ver['estado_comercial_c']; ?>">
                  <option value="Aceptado" >Aceptado</option>
                  <option value="Rechazado" >Rechazado</option>
                  <option value="Pendiente" >Pendiente</option>
                </select>
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Observaciones</strong></div></td>
            <td rowspan="3"><textarea name="observ_asesor_com_c" cols="25" rows="5"><?php echo $row_ver['observ_asesor_com_c']; ?></textarea></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Asesor Comercial</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="asesor_comercial_c" value="<?php echo $row_ver['asesor_comercial_c']; ?>" size="32">
            </strong></div></td>
            <td rowspan="2"><div align="right"></div>
                <div align="right"></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td height="24" align="right" nowrap><span class="Estilo33">Codigo</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="codigo_asesor_comercial_c" value="<?php echo $row_ver['codigo_asesor_comercial_c']; ?>" size="32">
            </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap bgcolor="#999999"><div align="center" class="Estilo17"><strong>DOCUMENTOS ADJUNTOS </strong></div></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap>&nbsp;</td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input <?php if (!(strcmp($row_ver['camara_comercio_c'],1))) {echo "checked";} ?> type="checkbox" name="camara_comercio_c" value="1" >
                Camara Comercio (vigente) </strong></div></td>
            <td rowspan="3"><div align="right"></div>
                <div align="right"></div>
                <div align="right"></div></td>
            <td><span class="Estilo33">
              <input <?php if (!(strcmp($row_ver['balance_general_c'],1))) {echo "checked";} ?> type="checkbox" name="balance_general_c" value="1" >
              Balance General (firmado por contador)</span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap>&nbsp;</td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input <?php if (!(strcmp($row_ver['referencias_bancarias_c'],1))) {echo "checked";} ?> type="checkbox" name="referencias_bancarias_c" value="1" >
                Referencias Bancarias </strong></div></td>
            <td><span class="Estilo33">
              <input <?php if (!(strcmp($row_ver['flujo_caja_proy_c'],1))) {echo "checked";} ?> type="checkbox" name="flujo_caja_proy_c" value="1" >
              Flujo de Caja Proyectado </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap>&nbsp;</td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input <?php if (!(strcmp($row_ver['referencias_comerciales_c'],1))) {echo "checked";} ?> type="checkbox" name="referencias_comerciales_c" value="1" >
                Referencias Comerciales </strong></div></td>
            <td><span class="Estilo33">
              <input <?php if (!(strcmp($row_ver['fotocopia_declar_renta_c'],1))) {echo "checked";} ?> type="checkbox" name="fotocopia_declar_renta_c" value="1" >
              Fotocopia Ultima Declaraci&oacute;n Renta</span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap>&nbsp;</td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input name="estado_pyg_c" type="checkbox" value="1" <?php if (!(strcmp($row_ver['estado_pyg_c'],1))) {echo "checked";} ?> >
                Estado P&amp;G (firmado por contador)</strong></div></td>
            <td><div align="center"><span class="Estilo33">
                <input <?php if (!(strcmp($row_ver['otros_doc'],1))) {echo "checked";} ?> type="checkbox" name="otros_doc" value="1" >
                Otros</span></div></td>
            <td><span class="Estilo33">
              <input <?php if (!(strcmp($row_ver['fotocopia_declar_iva_c'],1))) {echo "checked";} ?> type="checkbox" name="fotocopia_declar_iva_c" value="1" >
              Fotocopia Declaraci&oacute;n IVA (3 ultimas) </span></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap>&nbsp;</td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Estado del Cliente </span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <select name="estado_c" title="<?php echo $row_ver['estado_c']; ?>">
                  <option value="Activo"  <?php if (!(strcmp("Activo", $row_ver['estado_c']))) {echo "SELECTED";} ?>>Activo</option>
                  <option value="Retirado"  <?php if (!(strcmp("Retirado", $row_ver['estado_c']))) {echo "SELECTED";} ?>>Retirado</option>
                  <option value="Pendiente"  <?php if (!(strcmp("Pendiente", $row_ver['estado_c']))) {echo "SELECTED";} ?>>Pendiente</option>
                </select>
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Revisado por </strong></div></td>
            <td><input name="revisado_c" type="date" value="<?php echo $row_ver['revisado_c']; ?>" size="32"></td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td align="right" nowrap><span class="Estilo33">Registrado por</span></td>
            <td align="right" nowrap><div align="left" class="Estilo31"><strong>
                <input type="text" name="registrado_c" value="<?php echo $row_ver['registrado_c']; ?>" size="32">
            </strong></div></td>
            <td><div align="right" class="Estilo31"><strong>Fecha Revision</strong></div></td>
            <td><input name="fecha_revision_c" type="date" size="32" id="fecha_revision_c" value="<?php 

$mes2=substr($row_ver['fecha_revision_c'] ,5,2);
$dia2=substr($row_ver['fecha_revision_c'] ,8,2);
$ano2=substr($row_ver['fecha_revision_c'] ,0,4);
$fecha2=$mes2."/".$dia2."/".$ano2;
echo $fecha2;
?>" readonly="true" />
              <a href="javascript:cal2.popup();" ><img src="cal.gif" width="16" height="16" border="0" align="top">
			  </a> 
		    </td>
          </tr>
          <tr valign="baseline" bgcolor="#CCCCCC">
            <td colspan="4" align="right" nowrap><div align="center">
                <input name="submit" type="submit" value="Insertar registro">
            </div></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
        <input type="hidden" name="MM_update" value="form1">
      </form>
      <table width="906" border="1" cellspacing="3">
        <tr bgcolor="#CCCCCC">
          <td width="442"><span class="Estilo14"><a href="comercial.php">Gestion Comercial </a></span></td>
          <td width="445"><div align="right" class="Estilo17"><strong><a href="listado_clientes.php" class="Estilo42">Listado Clientes</a></strong></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td colspan="2"><div align="center" class="Estilo7">M@rcsoft</div></td>
  </tr>
</table>
<script language="JavaScript">
		<!-- // create calendar object(s) just after form tag closed
			 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
			 // note: you can have as many calendar objects as you need for your application
			var cal1 = new calendar2(document.forms['form1'].elements['fecha_solicitud_c']);
			cal1.year_scroll = true;
			cal1.time_comp = false;
			var cal2 = new calendar2(document.forms['form1'].elements['fecha_revision_c']);
			cal2.year_scroll = true;
			cal2.time_comp = false;
		//-->
</script>
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
