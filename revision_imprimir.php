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
$fecha1=$_POST['fecha_rev'];
$mes1=substr($fecha1,0,2);
$dia1=substr($fecha1,3,2);
$ano1=substr($fecha1,6,4);
$fecha1=$ano1."/".$mes1."/".$dia1;

  $updateSQL = sprintf("UPDATE revision SET nit_c_rev=%s, cod_ref_rev=%s, fecha_rev='$fecha1', translape_rev=%s, capa_rev=%s, peso_max_rev=%s, presentacion_rev=%s, num_rodillos_rev=%s, repeticion_rev=%s, tipo_elong_rev=%s, valor_tipo_elong_rev=%s, recibir_muestra_rev=%s, recibir_artes_rev=%s, recibir_textos_rev=%s, orientacion_textos_rev=%s, cinta_afecta_rev=%s, valor_cinta_afecta_rev=%s, entregar_arte_elong_rev=%s, orientacion_total_arte_rev=%s, pos_tal_recibo_rev=%s, pos_cinta_seg_rev=%s, pos_ppal_rev=%s, pos_inf_rev=%s, alt_tal_recibo_rev=%s, alt_cinta_seg_rev=%s, alt_ppal_rev=%s, alt_inf_rev=%s, cod_barras_recibo_rev=%s, cod_barras_cinta_seg_rev=%s, cod_barras_ppal_rev=%s, cod_barras_inf_rev=%s, formato_tal_recibo_rev=%s, formato_cinta_seg_rev=%s, formato_ppal_rev=%s, formato_inf_rev=%s, observacion_rev=%s, responsable_rev=%s WHERE id_rev=%s",
                       GetSQLValueString($_POST['nit_c_rev'], "text"),
                       GetSQLValueString($_POST['cod_ref_rev'], "text"),                       
                       GetSQLValueString($_POST['translape_rev'], "int"),
                       GetSQLValueString($_POST['capa_rev'], "text"),
                       GetSQLValueString($_POST['peso_max_rev'], "double"),
                       GetSQLValueString($_POST['presentacion_rev'], "text"),
                       GetSQLValueString($_POST['num_rodillos_rev'], "int"),
                       GetSQLValueString($_POST['repeticion_rev'], "int"),
                       GetSQLValueString($_POST['tipo_elong_rev'], "text"),
                       GetSQLValueString($_POST['valor_tipo_elong_rev'], "double"),
                       GetSQLValueString(isset($_POST['recibir_muestra_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['recibir_artes_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['recibir_textos_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orientacion_textos_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['cinta_afecta_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['valor_cinta_afecta_rev'], "int"),
                       GetSQLValueString(isset($_POST['entregar_arte_elong_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orientacion_total_arte_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['pos_tal_recibo_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['pos_cinta_seg_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['pos_ppal_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['pos_inf_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['alt_tal_recibo_rev'], "text"),
                       GetSQLValueString($_POST['alt_cinta_seg_rev'], "text"),
                       GetSQLValueString($_POST['alt_ppal_rev'], "text"),
                       GetSQLValueString($_POST['alt_inf_rev'], "text"),
                       GetSQLValueString(isset($_POST['cod_barras_recibo_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['cod_barras_cinta_seg_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['cod_barras_ppal_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['cod_barras_inf_rev']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['formato_tal_recibo_rev'], "text"),
                       GetSQLValueString($_POST['formato_cinta_seg_rev'], "text"),
                       GetSQLValueString($_POST['formato_ppal_rev'], "text"),
                       GetSQLValueString($_POST['formato_inf_rev'], "text"),
                       GetSQLValueString($_POST['observacion_rev'], "text"),
                       GetSQLValueString($_POST['responsable_rev'], "text"),
                       GetSQLValueString($_POST['id_rev'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "revision_detalle.php?cod_ref=" . $row_ver_nuevo['cod_ref_rev'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario_comercial = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

$colname_ver_referencia = "1";
if (isset($_GET['cod_ref'])) {
  $colname_ver_referencia = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_referencia = sprintf("SELECT * FROM referencia, egp, cliente WHERE referencia.cod_ref = '%s' and referencia.n_egp_ref=egp.n_egp and egp.nit_c_egp=cliente.nit_c", $colname_ver_referencia);
$ver_referencia = mysql_query($query_ver_referencia, $conexion1) or die(mysql_error());
$row_ver_referencia = mysql_fetch_assoc($ver_referencia);
$totalRows_ver_referencia = mysql_num_rows($ver_referencia);

$colname_ver_nuevo = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_ver_nuevo = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = sprintf("SELECT * FROM revision WHERE cod_ref_rev = '%s'", $colname_ver_nuevo);
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);

$colname_referencia = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM referencia WHERE cod_ref = '%s'", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<style type="text/css">
<!--
.Estilo70 {color: #000000}
.Estilo110 {font-size: 18px; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif;}
.Estilo112 {color: #000000; font-size: 10px; }
.Estilo113 {font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold;}
.Estilo115 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo117 {font-family: Arial, Helvetica, sans-serif}
.Estilo118 {font-size: 11px}
.Estilo119 {font-weight: bold; font-family: Arial, Helvetica, sans-serif;}
.Estilo120 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #000000;
	font-size: 12px;
}
.Estilo122 {font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo124 {font-size: 10px}
.Estilo126 {font-size: 10px; font-family: Arial, Helvetica, sans-serif; }
.Estilo128 {font-weight: bold}
.Estilo130 {font-weight: bold}
.Estilo131 {font-weight: bold}
.Estilo133 {font-family: Arial, Helvetica, sans-serif; font-size: 11px;}
.Estilo109 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo137 {
	font-family: Arial, Helvetica, sans-serif;
	color: #000000;
	font-size: 11px;
	font-weight: bold;
}
.Estilo138 {font-size: 12px}
.Estilo139 {
	font-size: 14px;
	font-weight: bold;
	color: #990000;
}
-->
</style>
<script type="text/JavaScript">
<!--
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
<table width="647" height="100" border="1" align="center" cellspacing="3">
  
  <tr bordercolor="#CCCCCC" bgcolor="#FFFFFF">
    <td width="101" height="23" rowspan="3"><img src="logo_acyc.gif" width="101" height="80" /></td>
    <td colspan="2"><div align="center" class="Estilo70"><span class="Estilo110">PLAN DE DISE&Ntilde;O Y DESARROLLO </span></div></td>
  </tr>
  <tr bordercolor="#CCCCCC" bgcolor="#FFFFFF">
    <td width="301" bordercolor="#FFFFFF"><div align="center" class="Estilo112"><span class="Estilo113">CODIGO: R2-F01 </span></div></td>
    <td width="283" bordercolor="#FFFFFF"><div align="center" class="Estilo112"><span class="Estilo113">VERSION: 2</span></div></td>
  </tr>
  <tr bordercolor="#CCCCCC" bgcolor="#FFFFFF">
    <td colspan="2"><div align="center" class="Estilo70"><span class="Estilo110">1. REVISION</span></div></td>
  </tr>
  <tr bordercolor="#CCCCCC" bgcolor="#FFFFFF">
    <td height="50" colspan="3"><table width="697" border="1" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td colspan="3" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo118">
          <div align="center"><span class="Estilo119">Revisi&oacute;n N&ordm; <span class="Estilo139"><?php echo $row_ver_nuevo['id_rev']; ?></span></span></div>
        </div></td>
        <td width="150" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right"><span class="Estilo115">Fecha</span></div></td>
        <td width="177" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left"><span class="Estilo109"><?php echo $row_ver_nuevo['fecha_rev']; ?></span></div></td>
      </tr>
      <tr>
        <td colspan="5" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo118"><span class="Estilo120">INFORMACION GENERAL DEL CLIENTE </span></div></td>
        </tr>
      <tr>
        <td width="130" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo117 Estilo118"><strong>Referencia</strong></div></td>
        <td colspan="2" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left"><span class="Estilo117 Estilo138"><?php echo $row_ver_nuevo['cod_ref_rev']; ?></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>EGP N&ordm;</strong></span></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo109"><?php echo $row_ver_nuevo['n_egp_rev']; ?></span></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Cliente</strong></span></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo109 Estilo118 Estilo117"><span class="Estilo109">
          <?php 
					$cliente=$row_ver_referencia['nombre_c']; 
					if($cliente=='')
					{
					echo "Clientes Varios";
					}
					else
					{
					echo $cliente;
					}
					?>
        </span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo109"><strong>Cotizaci&oacute;n N&ordm;</strong></span></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo109 Estilo118 Estilo117"><span class="Estilo109">
          <?php 
					$cotizacion=$row_referencia['n_cotiz_ref']; 
					if($cotizacion=='')
					{
					echo "No";
					}
					else
					{
					echo $cotizacion;
					}
					?>
        </span></div></td>
      </tr>
      <tr>
        <td colspan="5" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo118"><span class="Estilo122">INFORMACION GENERAL DE LA BOLSA </span></div></td>
        </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Ancho</strong></span></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo116 Estilo99 Estilo118 Estilo117"><?php echo $row_referencia['ancho_ref']; ?></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo115">Tipo de Bolsa</span></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo133"><?php echo $row_ver_referencia['tipo_bolsa_ref']; ?></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Largo</strong></span></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo116 Estilo99 Estilo118 Estilo117"><?php echo $row_referencia['largo_ref']; ?></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo115">Material</span></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo133"><?php echo $row_ver_referencia['material_ref']; ?></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Solapa</strong></span></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo116 Estilo99 Estilo118 Estilo117"><?php echo $row_referencia['solapa_ref']; ?></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo115">Uso de la Bolsa</span></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo133"><?php echo $row_ver_nuevo['tipo_bolsa_egp']; ?></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Bolsillo Portagu&iacute;a</strong></span></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo116 Estilo99 Estilo118 Estilo117"><?php echo $row_referencia['bolsillo_guia_ref']; ?></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo115">Translape</span></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo133"><?php echo $row_ver_nuevo['translape_rev']; ?></span></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Capa</strong></span></td>
        <td colspan="2" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo118 Estilo117"><?php echo $row_ver_nuevo['capa_rev']; ?></span></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo115">Peso Maximo Aplicado </span></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo133"><?php echo $row_ver_nuevo['peso_max_rev']; ?></span></td>
      </tr>
      <tr>
        <td colspan="5" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo122">MATERIAL A IMPRIMIR </span></div></td>
        </tr>
      <tr>
        <td colspan="3" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo70"><span class="Estilo122">COLORES DE IMPRESION</span></div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo137">*Presentaci&oacute;n</div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo133"><?php echo $row_ver_nuevo['presentacion_rev']; ?></span></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo117 Estilo128 Estilo118">
          <div align="right"><strong>Color 1</strong></div>
        </div></td>
        <td width="152" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo117 Estilo124"><?php $color1=$row_ver_nuevo['color1_egp'];
		if($color1<>'')
		{
		echo $color1;
		}
		else
		{
		echo "- -";
		} ?></div></td>
        <td width="48" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo115"><span class="Estilo128">Ubicaci&oacute;n</span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo126"><?php $ub1=$row_ver_nuevo['ubicacion1_egp'];
		if($ub1<>'')
		{
		echo $ub1;
		}
		else
		{
		echo "- -";
		} ?></div></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo118 Estilo117"><span class="Estilo70 Estilo130"><strong>Pantone .</strong></span><span class="Estilo124"><?php $pan1=$row_ver_nuevo['pantone1_egp'];
		if($pan1<>'')
		{
		echo $pan1;
		}
		else
		{
		echo "- -";
		} ?></span></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right"><span class="Estilo117 Estilo128 Estilo118"><strong>Color 2</strong></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo117 Estilo124"><?php $color2=$row_ver_nuevo['color2_egp'];
		if($color2<>'')
		{
		echo $color2;
		}
		else
		{
		echo "- -";
		} ?></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo115"><span class="Estilo128">Ubicaci&oacute;n</span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo126"><?php $ub2=$row_ver_nuevo['ubicacion2_egp'];
		if($ub2<>'')
		{
		echo $ub2;
		}
		else
		{
		echo "- -";
		} ?></div></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo118 Estilo117"><span class="Estilo130"><strong>Pantone .</strong></span><span class="Estilo124"><?php $pan2=$row_ver_nuevo['pantone2_egp'];
		if($pan2<>'')
		{
		echo $pan2;
		}
		else
		{
		echo "- -";
		} ?></span></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right"><span class="Estilo117 Estilo128 Estilo118"><strong>Color 3</strong></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo126"><?php $color3=$row_ver_nuevo['color3_egp'];
		if($color3<>'')
		{
		echo $color3;
		}
		else
		{
		echo "- -";
		} ?></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo115"><span class="Estilo128">Ubicaci&oacute;n</span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo126"><?php $ub3=$row_ver_nuevo['ubicacion3_egp'];
		if($ub3<>'')
		{
		echo $ub3;
		}
		else
		{
		echo "- -";
		} ?></div></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo118 Estilo117"><span class="Estilo106"><strong>Pantone .</strong></span><span class="Estilo124"><?php $pan3=$row_ver_nuevo['pantone3_egp'];
		if($pan3<>'')
		{
		echo $pan3;
		}
		else
		{
		echo "- -";
		} ?></span></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right"><span class="Estilo117 Estilo118 Estilo128"><strong>Color 4</strong></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo126"><?php $color4=$row_ver_nuevo['color4_egp'];
		if($color4<>'')
		{
		echo $color4;
		}
		else
		{
		echo "- -";
		} ?></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo115"><span class="Estilo128">Ubicaci&oacute;n</span></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo126"><?php $ub4=$row_ver_nuevo['ubicacion4_egp'];
		if($ub4<>'')
		{
		echo $ub4;
		}
		else
		{
		echo "- -";
		} ?></div></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo118 Estilo117"><span class="Estilo106"><strong>Pantone .</strong></span><span class="Estilo124"><?php $pan4=$row_ver_nuevo['pantone4_egp'];
		if($pan4<>'')
		{
		echo $pan4;
		}
		else
		{
		echo "- -";
		} ?></span></div></td>
      </tr>
      <tr>
        <td colspan="5" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo120">INFORMACION DE PRODUCCION SOBRE NEGATIVOS Y CYREL</span></div></td>
        </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>*Rodillo N&ordm;</strong></span></td>
        <td colspan="2" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo133"><?php echo $row_ver_nuevo['num_rodillos_rev']; ?></span></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo118 Estilo117"><span class="Estilo118"><strong>*Repeticiones x Revoluci&oacute;n</strong></span></span></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo133"><?php echo $row_ver_nuevo['repeticion_rev']; ?></span></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>*Tipo de Elongaci&oacute;n</strong></span></td>
        <td colspan="2" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo133"><?php echo $row_ver_nuevo['tipo_elong_rev']; ?></span></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo118 Estilo117"><span class="Estilo118"><strong>*Valor</strong></span></span></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo133"><?php echo $row_ver_nuevo['valor_tipo_elong_rev']; ?></span></td>
      </tr>
      <tr>
        <td colspan="5" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo122">ARTE</div></td>
        </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><input name="recibir_muestra_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['recibir_muestra_rev'],"1"))) {echo "checked";} ?> /></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo118 Estilo117 Estilo131">
            <div align="left">Se recibe bosquejo o muestra<br />
              fisica del cliente.</div>
        </div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><input name="recibir_artes_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['recibir_artes_rev'],"1"))) {echo "checked";} ?> /></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo115">Se recibe arte completo del<br />
          cliente o logos.</div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><input name="recibir_textos_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['recibir_textos_rev'],"1"))) {echo "checked";} ?> /></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo117 Estilo118"><strong>Se reciben solo textos por el cliente. </strong></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><input name="orientacion_textos_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['orientacion_textos_rev'],"1"))) {echo "checked";} ?> /></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo115">Se solicita orientaci&oacute;n en <br />
          textos de seguridad.</div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><input name="cinta_afecta_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['cinta_afecta_rev'],"1"))) {echo "checked";} ?> /></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo117 Estilo118"><strong>La cinta afecta la altura de la solapa.</strong></div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right"><span class="Estilo120">Indique Valor</span></div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo133"><?php echo $row_ver_nuevo['valor_cinta_afecta_rev']; ?></span></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><input name="entregar_arte_elong_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['entregar_arte_elong_rev'],"1"))) {echo "checked";} ?> /></td>
        <td colspan="2" align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo117 Estilo118"><strong>Se debe entregar arte incluyendo <br />
  elongaci&oacute;n.</strong></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><input name="orientacion_total_arte_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['orientacion_total_arte_rev'],"1"))) {echo "checked";} ?> /></td>
        <td valign="baseline" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left" class="Estilo115">Se solicita orientaci&oacute;n total <br />
          en el arte.</div></td>
      </tr>
      <tr valign="baseline" bgcolor="#CCCCCC">
        <td colspan="5" align="right" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo133">Nota: La cinta puede afectar la altura de la bolsa si esta tiene solapa. El arte debe de explicar muy bien si esta incluida. </div></td>
      </tr>
      <tr valign="baseline" bgcolor="#999999">
        <td colspan="5" align="right" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo122">NUMERACION Y CODIGOS DE BARRAS (posiciones, tipos y formato)</div></td>
      </tr>
      
      <tr valign="baseline" bgcolor="#CCCCCC">
        <td align="right" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo117 Estilo118"><strong>POSICIONES</strong></div></td>
        <td colspan="2" align="right" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo117 Estilo118" ><strong>ALTURA NUMERO </strong></div></td>
        <td align="right" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo117 Estilo118"><strong>CODIGO DE BARRAS </strong></div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo117 Estilo118"><strong>TIPO DE FORMATO </strong></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Talonario Recibo
            <input name="pos_tal_recibo_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['pos_tal_recibo_rev'],"1"))) {echo "checked";} ?> />
        </strong></span></td>
        <td colspan="2" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo133"><?php echo $row_ver_nuevo['alt_tal_recibo_rev']; ?></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo117 Estilo118"><strong>Talonario Recibo
              <input name="cod_barras_recibo_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['cod_barras_recibo_rev'],"1"))) {echo "checked";} ?> />
                </strong></div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo133"><?php echo $row_ver_nuevo['formato_tal_recibo_rev']; ?></span></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Cinta de Seguridad
            <input name="pos_cinta_seg_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['pos_cinta_seg_rev'],"1"))) {echo "checked";} ?> />
        </strong></span></td>
        <td colspan="2" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo133"><?php echo $row_ver_nuevo['alt_cinta_seg_rev']; ?></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo117 Estilo118"><strong>Cinta de Seguridad
              <input name="cod_barras_cinta_seg_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['cod_barras_cinta_seg_rev'],"1"))) {echo "checked";} ?> />
                </strong></div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo133"><?php echo $row_ver_nuevo['formato_cinta_seg_rev']; ?></span></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Principal
            <input name="pos_ppal_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['pos_ppal_rev'],"1"))) {echo "checked";} ?> />
        </strong></span></td>
        <td colspan="2" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo133"><?php echo $row_ver_nuevo['alt_ppal_rev']; ?></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo117 Estilo118"><strong>Principal
              <input name="cod_barras_ppal_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['cod_barras_ppal_rev'],"1"))) {echo "checked";} ?> />
                </strong></div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo133"><?php echo $row_ver_nuevo['formato_ppal_rev']; ?></span></div></td>
      </tr>
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo117 Estilo118"><strong>Inferior
            <input name="pos_inf_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['pos_inf_rev'],"1"))) {echo "checked";} ?> />
        </strong></span></td>
        <td colspan="2" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo133"><?php echo $row_ver_nuevo['alt_inf_rev']; ?></span></div></td>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="right" class="Estilo117 Estilo118"><strong>Inferior
              <input name="cod_barras_inf_rev" type="checkbox" disabled="disabled" value="1"  <?php if (!(strcmp($row_ver_nuevo['cod_barras_inf_rev'],"1"))) {echo "checked";} ?> />
                </strong></div></td>
        <td bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center"><span class="Estilo133"><?php echo $row_ver_nuevo['formato_inf_rev']; ?></span></div></td>
      </tr>
      <tr valign="baseline" bgcolor="#999999">
        <td colspan="5" align="right" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo122">OBSERVACIONES GENERALES</div></td>
      </tr>
      <tr valign="baseline" bgcolor="#CCCCCC">
        <td colspan="5" align="right" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo133">1. Se debe de entregar arte y montaje mecanico para la elaboraci&oacute;n de negativos.</div></td>
      </tr>
      <tr valign="baseline" bgcolor="#CCCCCC">
        <td colspan="5" align="right" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="center" class="Estilo133">2. Se deben de dejar los espacios reservados para la numeraci&oacute;n en el arte.</div></td>
      </tr>
      
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo115">Otras observaciones</span></td>
        <td colspan="4" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="justify"><span class="Estilo133"><?php $obs=$row_ver_nuevo['observacion_rev'];
		if($obs<>'')
		{
		echo $obs;
		}
		else
		{
		echo "- -";
		} ?></span></div></td>
        </tr>
      
      <tr>
        <td align="right" valign="baseline" nowrap="nowrap" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><span class="Estilo115">*Registrado por</span></td>
        <td colspan="4" bordercolor="#CCCCCC" bgcolor="#FFFFFF"><div align="left"><span class="Estilo133"><?php echo $row_ver_nuevo['responsable_rev']; ?></span></div></td>
        </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($usuario_comercial);

mysql_free_result($ver_referencia);

mysql_free_result($ver_nuevo);

mysql_free_result($referencia);
?>
