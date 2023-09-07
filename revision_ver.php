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
$egp=$_POST['n_egp_rev'];
$tipo_bolsa=$_POST['tipo_bolsa_egp'];
$color1=$_POST['color1_egp'];
$ubicacion1=$_POST['ubicacion1_egp'];
$pantone1=$_POST['pantone1_egp'];
$color2=$_POST['color2_egp'];
$ubicacion2=$_POST['ubicacion2_egp'];
$pantone2=$_POST['pantone2_egp'];
$color3=$_POST['color3_egp'];
$ubicacion3=$_POST['ubicacion3_egp'];
$pantone3=$_POST['pantone3_egp'];
$color4=$_POST['color4_egp'];
$ubicacion4=$_POST['ubicacion4_egp'];
$pantone4=$_POST['pantone4_egp'];

$sql2="UPDATE egp SET tipo_bolsa_egp='$tipo_bolsa',
color1_egp= '$color1', pantone1_egp= '$pantone1', ubicacion1_egp= '$ubicacion1', 
color2_egp= '$color2', pantone2_egp= '$pantone2', ubicacion2_egp= '$ubicacion2', 
color3_egp= '$color3', pantone3_egp= '$pantone3', ubicacion3_egp= '$ubicacion3', 
color4_egp= '$color4', pantone4_egp= '$pantone4', ubicacion4_egp= '$ubicacion4'
WHERE n_egp='$egp'";

  $updateSQL = sprintf("UPDATE revision SET nit_c_rev=%s, cod_ref_rev=%s, n_egp_rev=%s, fecha_rev=%s, tipo_bolsa_egp=%s, translape_rev=%s, capa_rev=%s, peso_max_rev=%s, presentacion_rev=%s, color1_egp=%s, ubicacion1_egp=%s, pantone1_egp=%s, color2_egp=%s, ubicacion2_egp=%s, pantone2_egp=%s, color3_egp=%s, ubicacion3_egp=%s, pantone3_egp=%s, color4_egp=%s, ubicacion4_egp=%s, pantone4_egp=%s, num_rodillos_rev=%s, repeticion_rev=%s, tipo_elong_rev=%s, valor_tipo_elong_rev=%s, recibir_muestra_rev=%s, recibir_artes_rev=%s, recibir_textos_rev=%s, orientacion_textos_rev=%s, cinta_afecta_rev=%s, valor_cinta_afecta_rev=%s, entregar_arte_elong_rev=%s, orientacion_total_arte_rev=%s, pos_tal_recibo_rev=%s, pos_cinta_seg_rev=%s, pos_ppal_rev=%s, pos_inf_rev=%s, alt_tal_recibo_rev=%s, alt_cinta_seg_rev=%s, alt_ppal_rev=%s, alt_inf_rev=%s, cod_barras_recibo_rev=%s, cod_barras_cinta_seg_rev=%s, cod_barras_ppal_rev=%s, cod_barras_inf_rev=%s, formato_tal_recibo_rev=%s, formato_cinta_seg_rev=%s, formato_ppal_rev=%s, formato_inf_rev=%s, observacion_rev=%s, responsable_rev=%s WHERE id_rev=%s",
                       GetSQLValueString($_POST['nit_c_rev'], "text"),
                       GetSQLValueString($_POST['cod_ref_rev'], "text"),
					   GetSQLValueString($_POST['n_egp_rev'], "int"),
					   GetSQLValueString($_POST['fecha_rev'], "date"), 
					   GetSQLValueString($_POST['tipo_bolsa_egp'], "text"),                        
                       GetSQLValueString($_POST['translape_rev'], "int"),
                       GetSQLValueString($_POST['capa_rev'], "text"),
                       GetSQLValueString($_POST['peso_max_rev'], "double"),
                       GetSQLValueString($_POST['presentacion_rev'], "text"),
					   GetSQLValueString($_POST['color1_egp'], "text"),
					   GetSQLValueString($_POST['ubicacion1_egp'], "text"),
					   GetSQLValueString($_POST['pantone1_egp'], "text"),
					   GetSQLValueString($_POST['color2_egp'], "text"),
					   GetSQLValueString($_POST['ubicacion2_egp'], "text"),
					   GetSQLValueString($_POST['pantone2_egp'], "text"),
					   GetSQLValueString($_POST['color3_egp'], "text"),
					   GetSQLValueString($_POST['ubicacion3_egp'], "text"),
					   GetSQLValueString($_POST['pantone3_egp'], "text"),
					   GetSQLValueString($_POST['color4_egp'], "text"),
					   GetSQLValueString($_POST['ubicacion4_egp'], "text"),
					   GetSQLValueString($_POST['pantone4_egp'], "text"),
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
  $result2=mysql_query($sql2, $conexion1);
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
.Estilo14 {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
	color: #000066;
}
.Estilo36 {	color: #000066;
	font-size: 16px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo64 {color: #000066}
.Estilo65 {font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; font-weight: bold; color: #000066; }
.Estilo99 {font-size: 12px}
.Estilo104 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo105 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo106 {color: #000066; font-size: 11px; }
.Estilo108 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #000066; }
.Estilo109 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo110 {color: #000000; font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo111 {
	color: #FF0000;
	font-size: 16px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo115 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #000066; }
.Estilo116 {font-family: Arial, Helvetica, sans-serif}
.Estilo121 {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
	font-weight: bold;
}
.Estilo128 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; }
.Estilo131 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000066; }
.Estilo139 {font-size: 11px}
.Estilo141 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #990000; }
.Estilo142 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #000066;
}
.Estilo143 {color: #FF0000}
.Estilo144 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo148 {font-family: Arial, Helvetica, sans-serif; color: #000066;}
.Estilo149 {font-family: Arial, Helvetica, sans-serif; font-size: 11px;}
.Estilo150 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
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
<script language="JavaScript" src="calendar2.js"></script>
</head>

<body>
<table width="735" height="100" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80" /><img src="index_r1_c2.gif" width="626" height="80" /></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3">
      <tr>
        <td width="357"><span class="Estilo105"><?php echo $row_usuario_comercial['nombre_usuario']; ?></span></td>
        <td width="356"><div align="right" class="Estilo104"><a href="<?php echo $logoutAction ?>" class="Estilo106">Cerrar Sesi&oacute;n</a></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="50" bordercolor="#FFFFFF"><table width="735" height="100" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="720" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo14">PLAN DE DISE&Ntilde;O Y DESARROLLO </div></td>
          </tr>
          <tr>
            <td width="444" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo108">Codigo : R2-F01 </div></td>
            <td width="432" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo108">Versi&oacute;n : 2 </div></td>
          </tr>
        </table></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td height="21" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo14">1. REVISION</div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td height="50" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center">
          <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="MM_validateForm('fecha_rev','','R','cod_ref_rev','','R','nit_c_rev','','R','responsable_rev','','R');return document.MM_returnValue">
            <table width="735" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC" class="Estilo65">
                <td colspan="3" align="right" bgcolor="#ECF5FF"><div align="center" class="Estilo109">*Revisi&oacute;n N&ordm;  <span class="Estilo111"><?php echo $row_ver_nuevo['id_rev']; ?></span></div>
                  <div align="left" class="Estilo110"></div></td>
                <td width="158" align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo109">*Fecha</span></div></td>
                <td width="186" bgcolor="#ECF5FF">                  <div align="left" class="Estilo109">
				<input type="text" name="fecha_rev" id="fecha_rev" value="<?php echo $row_ver_nuevo['fecha_rev']; ?>" size="10" />
                                              
                </div></td>
              </tr>
              
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999" class="Estilo36">
                <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo104">INFORMACION GENERAL DEL CLIENTE </div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td width="133" align="right" bgcolor="#ECF5FF"><div align="right" class="Estilo121">*Referencia:</div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <input name="cod_ref_rev" type="hidden" value="<?php echo $row_ver_nuevo['cod_ref_rev']; ?>" />
                  <span class="Estilo142"><?php echo $row_ver_nuevo['cod_ref_rev']; ?></span></div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">EGP <span class="Estilo143">N&ordm;</span></span></div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <label>
                  <input name="n_egp_rev" type="hidden" id="n_egp_rev" value="<?php echo $row_ver_nuevo['n_egp_rev']; ?>" />
                  </label>
                  <span class="Estilo64"><?php echo $row_ver_nuevo['n_egp_rev']; ?></span></div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Cliente</span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo109 Estilo64"><?php 
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
                  <input name="nit_c_rev" type="hidden" value="<?php echo $row_ver_nuevo['nit_c_rev']; ?>" />
                </div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Cotizaci&oacute;n <span class="Estilo143">N&ordm;</span></span></div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo109">
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
                </div></td>
              </tr>
              
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999" class="Estilo36">
                <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo109 Estilo144">INFORMACION GENERAL DE LA BOLSA </div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Ancho</span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo116 Estilo99 Estilo64"><?php echo $row_referencia['ancho_ref']; ?></div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Tipo de Bolsa</span></div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo116 Estilo99 Estilo64"><?php echo $row_referencia['tipo_bolsa_ref']; ?></div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Largo</span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo116 Estilo99 Estilo64"><?php echo $row_referencia['largo_ref']; ?></div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Material</span></div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo116 Estilo99 Estilo64"><?php echo $row_referencia['material_ref']; ?></div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Solapa</span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo116 Estilo99 Estilo64"><?php echo $row_referencia['solapa_ref']; ?></div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Uso de la Bolsa</span></div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <label>
                  <input name="tipo_bolsa_egp" type="text" id="tipo_bolsa_egp" value="<?php echo $row_ver_nuevo['tipo_bolsa_egp']; ?>" size="20" />
                  </label>
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">Bolsillo Portagu&iacute;a</span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo116 Estilo99 Estilo64"><?php echo $row_referencia['bolsillo_guia_ref']; ?></div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">*Translape</span></div></td>
                <td bgcolor="#ECF5FF">
                  <div align="left" class="Estilo109">
                    <input type="text" name="translape_rev" value="<?php echo $row_ver_nuevo['translape_rev']; ?>" size="20" />
                    </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">*Capa</span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <select name="capa_rev" title="<?php echo $row_ver_nuevo['capa_rev']; ?>">
                    <option value="" <?php if (!(strcmp("", $row_ver_nuevo['capa_rev']))) {echo "selected=\"selected\"";} ?>>*</option>
                    <option value="A sobre B" <?php if (!(strcmp("A sobre B", $row_ver_nuevo['capa_rev']))) {echo "selected=\"selected\"";} ?>>A sobre B</option>
                    <option value="B sobre A" <?php if (!(strcmp("B sobre A", $row_ver_nuevo['capa_rev']))) {echo "selected=\"selected\"";} ?>>B sobre A</option>
                  </select>
                </div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo121">*Peso Max. Aplicado </span></div></td>
                <td bgcolor="#ECF5FF">
                  <div align="left" class="Estilo109">
                    <input type="text" name="peso_max_rev" value="<?php echo $row_ver_nuevo['peso_max_rev']; ?>" size="20" />
                    kilos</div></td>
              </tr>
              
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999" class="Estilo36">
                <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo104">MATERIAL A IMPRIMIR </div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td colspan="3" align="right" nowrap bgcolor="#ECF5FF"><div align="center" class="Estilo121">COLORES DE IMPRESION</div></td>
                <td align="right" bgcolor="#ECF5FF" class="Estilo131">*Presentaci&oacute;n</td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <select name="presentacion_rev" id="presentacion_rev" title="<?php echo $row_ver_nuevo['presentacion_rev']; ?>">
                    <option value="" <?php if (!(strcmp("", $row_ver_nuevo['presentacion_rev']))) {echo "selected=\"selected\"";} ?>>*</option>
                    <option value="Lamina" <?php if (!(strcmp("Lamina", $row_ver_nuevo['presentacion_rev']))) {echo "selected=\"selected\"";} ?>>Lamina</option>
                    <option value="Tubular" <?php if (!(strcmp("Tubular", $row_ver_nuevo['presentacion_rev']))) {echo "selected=\"selected\"";} ?>>Tubular</option>
                    <option value="Semitubular" <?php if (!(strcmp("Semitubular", $row_ver_nuevo['presentacion_rev']))) {echo "selected=\"selected\"";} ?>>Semitubular</option>
                  </select>
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right" class="Estilo139">
                  <div align="right">Color 1</div>
                </div></td>
                <td width="132" align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo149">
                  <input name="color1_egp" type="text" id="color1_egp" value="<?php echo $row_ver_nuevo['color1_egp']; ?>" size="12" />
                </div></td>
                <td width="90" align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right" class="Estilo139"><span class="Estilo148">Ubicaci&oacute;n</span></div></td>
                <td align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo149">
                  <input name="ubicacion1_egp" type="text" id="ubicacion1_egp" value="<?php echo $row_ver_nuevo['ubicacion1_egp']; ?>" size="12" />
                </div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo149"><span class="Estilo64">Pantone.</span>
                    <input name="pantone1_egp" type="text" id="pantone1_egp" value="<?php echo $row_ver_nuevo['pantone1_egp']; ?>" size="12" />
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115">Color 2</span></div></td>
                <td align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo149">
                  <input name="color2_egp" type="text" id="color2_egp" value="<?php echo $row_ver_nuevo['color2_egp']; ?>" size="12" />
                </div></td>
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right" class="Estilo139"><span class="Estilo148">Ubicaci&oacute;n</span></div></td>
                <td align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo149">
                  <input name="ubicacion2_egp" type="text" id="ubicacion2_egp" value="<?php echo $row_ver_nuevo['ubicacion2_egp']; ?>" size="12" />
                </div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo149"><span class="Estilo64">Pantone.

                      <input name="pantone2_egp" type="text" id="pantone2_egp" value="<?php echo $row_ver_nuevo['pantone2_egp']; ?>" size="12" />
                </span></div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115">Color 3</span></div></td>
                <td align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo149">
                  <input name="color3_egp" type="text" id="color3_egp" value="<?php echo $row_ver_nuevo['color3_egp']; ?>" size="12" />
                </div></td>
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right" class="Estilo139"><span class="Estilo148">Ubicaci&oacute;n</span></div></td>
                <td align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo149">
                  <input name="ubicacion3_egp" type="text" id="ubicacion3_egp" value="<?php echo $row_ver_nuevo['ubicacion3_egp']; ?>" size="12" />
                </div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo149"><span class="Estilo64">Pantone.</span> 
                    <input name="pantone3_egp" type="text" id="pantone3_egp" value="<?php echo $row_ver_nuevo['pantone3_egp']; ?>" size="12" />
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115">Color 4</span></div></td>
                <td align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo149">
                  <input name="color4_egp" type="text" id="color4_egp" value="<?php echo $row_ver_nuevo['color4_egp']; ?>" size="12" />
                </div></td>
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right" class="Estilo139"><span class="Estilo148">Ubicaci&oacute;n</span></div></td>
                <td align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo149">
                  <input name="ubicacion4_egp" type="text" id="ubicacion4_egp" value="<?php echo $row_ver_nuevo['ubicacion4_egp']; ?>" size="12" />
                </div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo149"><span class="Estilo64">Pantone.</span> 
                    <input name="pantone4_egp" type="text" id="pantone4_egp" value="<?php echo $row_ver_nuevo['pantone4_egp']; ?>" size="12" />
                </div></td>
              </tr>
              
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
                <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo108">INFORMACION DE PRODUCCION SOBRE NEGATIVOS Y CYREL</div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo131">*Rodillo Nº</span></div></td>
                <td colspan="2" align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <input type="text" name="num_rodillos_rev" value="<?php echo $row_ver_nuevo['num_rodillos_rev']; ?>" size="20" />
                cms</div></td>
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right"><span class="Estilo131">*Repeticiones x Revoluci&oacute;n</span></div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <input type="text" name="repeticion_rev" value="<?php echo $row_ver_nuevo['repeticion_rev']; ?>" size="20" />                
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo131">*Tipo de Elongaci&oacute;n</span></div></td>
                <td colspan="2" align="right" nowrap bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <select name="tipo_elong_rev" title="<?php echo $row_ver_nuevo['tipo_elong_rev']; ?>">
                    <option value="" <?php if (!(strcmp("", $row_ver_nuevo['tipo_elong_rev']))) {echo "selected=\"selected\"";} ?>>*</option>
                    <option value="A lo ancho" <?php if (!(strcmp("A lo ancho", $row_ver_nuevo['tipo_elong_rev']))) {echo "selected=\"selected\"";} ?>>A lo ancho</option>
                    <option value="A lo largo" <?php if (!(strcmp("A lo largo", $row_ver_nuevo['tipo_elong_rev']))) {echo "selected=\"selected\"";} ?>>A lo largo</option>
                  </select>
                </div></td>
                <td align="right" nowrap="nowrap" bgcolor="#ECF5FF"><div align="right"><span class="Estilo131">*Valor</span></div></td>
                <td bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <input type="text" name="valor_tipo_elong_rev" value="<?php echo $row_ver_nuevo['valor_tipo_elong_rev']; ?>" size="20" />                
                </div></td>
              </tr>
              
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
                <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo105"><strong>ARTE</strong></div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td colspan="3" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo131">
                  <div align="left">
                    <input name="recibir_muestra_rev" type="checkbox" value="1"  <?php if (!(strcmp($row_ver_nuevo['recibir_muestra_rev'],"1"))) {echo "checked";} ?> />
                    Se recibe bosquejo o muestra fisica del cliente.</div>
                </div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo131">
                  <input name="recibir_artes_rev" type="checkbox" value="1"  <?php if (!(strcmp($row_ver_nuevo['recibir_artes_rev'],"1"))) {echo "checked";} ?> />
                  Se recibe arte completo del cliente o logos.</div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td colspan="3" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo131">
                  <input name="recibir_textos_rev" type="checkbox" value="1"  <?php if (!(strcmp($row_ver_nuevo['recibir_textos_rev'],"1"))) {echo "checked";} ?> />
                  Se reciben solo textos por el cliente. </div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo131">
                  <input name="orientacion_textos_rev" type="checkbox" value="1"  <?php if (!(strcmp($row_ver_nuevo['orientacion_textos_rev'],"1"))) {echo "checked";} ?> />
                  Se solicita orientaci&oacute;n en textos de seguridad.</div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td colspan="3" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo131">
                  <input name="cinta_afecta_rev" type="checkbox" value="1"  <?php if (!(strcmp($row_ver_nuevo['cinta_afecta_rev'],"1"))) {echo "checked";} ?> />
                  La cinta afecta la altura de la solapa.</div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo131">
                    Indique Valor
                      <input type="text" name="valor_cinta_afecta_rev" value="<?php echo $row_ver_nuevo['valor_cinta_afecta_rev']; ?>" size="20" />
                    </div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td colspan="3" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo131">
                  <input name="entregar_arte_elong_rev" type="checkbox" value="1"  <?php if (!(strcmp($row_ver_nuevo['entregar_arte_elong_rev'],"1"))) {echo "checked";} ?> />
                  Se debe entregar arte incluyendo elongaci&oacute;n.</div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo131">
                  <input name="orientacion_total_arte_rev" type="checkbox" value="1"  <?php if (!(strcmp($row_ver_nuevo['orientacion_total_arte_rev'],"1"))) {echo "checked";} ?> />
                  Se solicita orientaci&oacute;n total en el arte.</div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td colspan="5" align="right" bgcolor="#ECF5FF"><div align="center" class="Estilo131"> Nota: La cinta puede afectar la altura de la bolsa si esta tiene solapa. El arte debe de explicar muy bien si esta incluida. </div></td>
                </tr>
              
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
                <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo108">NUMERACION Y CODIGOS DE BARRAS (posiciones, tipos y formato)</div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="center" class="Estilo128">POSICIONES</div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF"><div align="center" class="Estilo128" >ALTURA NUMERO </div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="center" class="Estilo128">CODIGO DE BARRAS </div></td>
                <td bgcolor="#ECF5FF"><div align="center" class="Estilo128">TIPO DE FORMATO </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo131">Talonario Recibo
                  <input type="checkbox" name="pos_tal_recibo_rev" value="1"  <?php if (!(strcmp($row_ver_nuevo['pos_tal_recibo_rev'],"1"))) {echo "checked";} ?> />
                </span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF">
                  <div align="center" class="Estilo109">
                    <select name="alt_tal_recibo_rev" title="<?php echo $row_ver_nuevo['alt_tal_recibo_rev']; ?>">
                      <option value="" <?php if (!(strcmp("", $row_ver_nuevo['alt_tal_recibo_rev']))) {echo "selected=\"selected\"";} ?>>*</option>
                      <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_nuevo['alt_tal_recibo_rev']))) {echo "selected=\"selected\"";} ?>>Normal</option>
                      <option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_nuevo['alt_tal_recibo_rev']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
                      <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_nuevo['alt_tal_recibo_rev']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                    </select>
                    </div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right" class="Estilo131">Talonario Recibo
                        <input type="checkbox" name="cod_barras_recibo_rev" value="1"  <?php if (!(strcmp($row_ver_nuevo['cod_barras_recibo_rev'],"1"))) {echo "checked";} ?> />
                    </div></td>
                <td bgcolor="#ECF5FF"><div align="center" class="Estilo109">
                  <input type="text" name="formato_tal_recibo_rev" value="<?php echo $row_ver_nuevo['formato_tal_recibo_rev']; ?>" size="20" />
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo131">Cinta de Seguridad
                  <input type="checkbox" name="pos_cinta_seg_rev" value="1"  <?php if (!(strcmp($row_ver_nuevo['pos_cinta_seg_rev'],"1"))) {echo "checked";} ?> />
                </span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF">
                  <div align="center" class="Estilo109">
                    <select name="alt_cinta_seg_rev" title="<?php echo $row_ver_nuevo['alt_cinta_seg_rev']; ?>">
                      <option value="" <?php if (!(strcmp("", $row_ver_nuevo['alt_cinta_seg_rev']))) {echo "selected=\"selected\"";} ?>>*</option>
                      <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_nuevo['alt_cinta_seg_rev']))) {echo "selected=\"selected\"";} ?>>Normal</option>
                      <option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_nuevo['alt_cinta_seg_rev']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
                      <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_nuevo['alt_cinta_seg_rev']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                    </select>
                    </div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right" class="Estilo131">Cinta de Seguridad
                        <input type="checkbox" name="cod_barras_cinta_seg_rev" value="1"  <?php if (!(strcmp($row_ver_nuevo['cod_barras_cinta_seg_rev'],"1"))) {echo "checked";} ?> />
                    </div></td>
                <td bgcolor="#ECF5FF"><div align="center" class="Estilo109">
                  <input type="text" name="formato_cinta_seg_rev" value="<?php echo $row_ver_nuevo['formato_cinta_seg_rev']; ?>" size="20" />
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo131">Principal
                  <input type="checkbox" name="pos_ppal_rev" value="1"  <?php if (!(strcmp($row_ver_nuevo['pos_ppal_rev'],"1"))) {echo "checked";} ?> />
                </span></div></td>
                <th colspan="2" align="right" bgcolor="#ECF5FF">
                  <div align="center" class="Estilo109">
                    <select name="alt_ppal_rev" title="<?php echo $row_ver_nuevo['alt_ppal_rev']; ?>">
                      <option value="" <?php if (!(strcmp("", $row_ver_nuevo['alt_ppal_rev']))) {echo "selected=\"selected\"";} ?>>*</option>
                      <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_nuevo['alt_ppal_rev']))) {echo "selected=\"selected\"";} ?>>Normal</option>
                      <option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_nuevo['alt_ppal_rev']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
                      <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_nuevo['alt_ppal_rev']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                    </select>
                    </div></th>
                <td align="right" bgcolor="#ECF5FF"><div align="right" class="Estilo131">Principal
                        <input type="checkbox" name="cod_barras_ppal_rev" value="1"  <?php if (!(strcmp($row_ver_nuevo['cod_barras_ppal_rev'],"1"))) {echo "checked";} ?> />
                    </div></td>
                <td bgcolor="#ECF5FF"><div align="center" class="Estilo109">
                  <input type="text" name="formato_ppal_rev" value="<?php echo $row_ver_nuevo['formato_ppal_rev']; ?>" size="20" />
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo131">Inferior
                  <input type="checkbox" name="pos_inf_rev" value="1"  <?php if (!(strcmp($row_ver_nuevo['pos_inf_rev'],"1"))) {echo "checked";} ?> />
                </span></div></td>
                <td colspan="2" align="right" bgcolor="#ECF5FF">
                  <div align="center" class="Estilo109">
                    <select name="alt_inf_rev" title="<?php echo $row_ver_nuevo['alt_inf_rev']; ?>">
                      <option value="" <?php if (!(strcmp("", $row_ver_nuevo['alt_inf_rev']))) {echo "selected=\"selected\"";} ?>>*</option>
                      <option value="Normal" <?php if (!(strcmp("Normal", $row_ver_nuevo['alt_inf_rev']))) {echo "selected=\"selected\"";} ?>>Normal</option>
                      <option value="CCTV" <?php if (!(strcmp("CCTV", $row_ver_nuevo['alt_inf_rev']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
                      <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_nuevo['alt_inf_rev']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                    </select>
                    </div></td>
                <td align="right" bgcolor="#ECF5FF"><div align="right" class="Estilo131">Inferior
                        <input type="checkbox" name="cod_barras_inf_rev" value="1"  <?php if (!(strcmp($row_ver_nuevo['cod_barras_inf_rev'],"1"))) {echo "checked";} ?> />
                    </div></td>
                <td bgcolor="#ECF5FF"><div align="center" class="Estilo109">
                  <input type="text" name="formato_inf_rev" value="<?php echo $row_ver_nuevo['formato_inf_rev']; ?>" size="20" />
                </div></td>
              </tr>
              
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
                <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo108">OBSERVACIONES GENERALES</div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td colspan="5" align="right" bgcolor="#ECF5FF"><div align="center" class="Estilo131">1. Se debe de entregar arte y montaje mecanico para la elaboraci&oacute;n de negativos.</div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td colspan="5" align="right" bgcolor="#ECF5FF"><div align="center" class="Estilo131">2. Se deben de dejar los espacios reservados para la numeraci&oacute;n en el arte.</div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><span class="Estilo121">Otras observaciones</span></td>
                <td colspan="4" rowspan="2" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <textarea name="observacion_rev" cols="70" rows="5"><?php echo $row_ver_nuevo['observacion_rev']; ?></textarea>
                </div></td>
                </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF">&nbsp;</td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                <td align="right" bgcolor="#ECF5FF"><span class="Estilo121">*Registrado por</span></td>
                <td colspan="4" align="right" bgcolor="#ECF5FF"><div align="left" class="Estilo109">
                  <input name="responsable_rev" type="text" value="<?php echo $row_ver_nuevo['responsable_rev']; ?>" size="40" readonly="true" />
                </div></td>
                </tr>

              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
                <td height="17" colspan="5" align="right" bgcolor="#ECF5FF"><span class="Estilo141">Si modifica este registro los datos referentes a la especificaci&oacute;n general del producto seran actualizadas automaticamente. </span></td>
              </tr>
              <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
                <td height="26" colspan="5" align="right" bgcolor="#FFFFFF"><div align="center">
                  <input type="submit" value="Actualizar registro">
                </div></td>
                </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="id_rev" value="<?php echo $row_ver_nuevo['id_rev']; ?>">
          </form>
          </div></td>
      </tr>
    </table></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="42" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="105"><div align="center" class="Estilo115"><a href="menu.php" class="Estilo64"><img src="home.gif" alt="Menu Principal" width="21" height="22" /></a></div></td>
        <td width="157"><div align="center" class="Estilo115"><a href="referencias.php" class="Estilo150">Listado Referencias </a></div></td>
        <td width="171"><div align="center" class="Estilo115"><a href="revision_detalle.php?cod_ref=<?php echo $row_ver_nuevo['cod_ref_rev']; ?>" class="Estilo150">Detalle Revisi&oacute;n</a> </div></td>
        <td width="164"><div align="right" class="Estilo115">
          <div align="center"><a href="disenoydesarrollo.php" class="Estilo150">Dise&ntilde;o y Desarrollo </a></div>
        </div></td>
        <td width="110"><div align="right"><img src="firma3.bmp" /></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="JavaScript">
		<!-- // create calendar object(s) just after form tag closed
			 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
			 // note: you can have as many calendar objects as you need for your application
			var cal1 = new calendar2(document.forms['form1'].elements['fecha_rev']);
			cal1.year_scroll = true;
			cal1.time_comp = false;
		//-->
</script>

</body>
</html>
<?php
mysql_free_result($usuario_comercial);

mysql_free_result($ver_referencia);

mysql_free_result($ver_nuevo);

mysql_free_result($referencia);
?>
