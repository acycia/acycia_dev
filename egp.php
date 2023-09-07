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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {

  $insertSQL = sprintf("INSERT INTO egp (n_egp, fecha_egp, nit_c_egp, ancho_egp, largo_egp, solapa_egp, largo_cang_egp, calibre_egp, tipo_ext_egp, pigm_ext_egp, pigm_int_epg, adhesivo_egp, tipo_bolsa_egp, cantidad_egp, tipo_sello_egp, observacion1_egp, color1_egp, pantone1_egp, ubicacion1_egp, color2_egp, pantone2_egp, ubicacion2_egp, color3_egp, pantone3_egp, ubicacion3_egp, color4_egp, pantone4_egp, ubicacion4_egp, observacion2_egp, ppal_cctv_egp, ppal_normal_egp, num_cinta_egp, num_inf_egp, comienza_egp, observacion3_egp, fecha_cad_egp, cod_barra_egp, form_egp, arte_sum_egp, orient_arte_egp, disenador_egp, telef_disenador_egp, ent_logo_egp, unids_paq_egp, unids_caja_egp, marca_cajas_egp, lugar_entrega_egp, responsable_egp, observacion4_egp) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_egp'], "int"),
					   GetSQLValueString($_POST['fecha_egp'], "date"),					   
                       GetSQLValueString($_POST['nit_c_egp'], "text"),
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
					   GetSQLValueString($_POST['observacion2_egp'], "text"),
                       GetSQLValueString(isset($_POST['ppal_cctv_egp']) ? "true" : "", "defined","1","0"),
					    GetSQLValueString(isset($_POST['ppal_normal_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['num_cinta_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['num_inf_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['comienza_egp'], "text"),
					   GetSQLValueString($_POST['observacion3_egp'], "text"),
                       GetSQLValueString(isset($_POST['fecha_cad_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['cod_barra_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['form_egp'], "text"),
                       GetSQLValueString(isset($_POST['arte_sum_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['orient_arte_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['disenador_egp'], "text"),
                       GetSQLValueString($_POST['telef_disenador_egp'], "text"),
                       GetSQLValueString(isset($_POST['ent_logo_egp']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['unids_paq_egp'], "text"),
					   GetSQLValueString($_POST['unids_caja_egp'], "text"),
                       GetSQLValueString($_POST['marca_cajas_egp'], "text"),
                       GetSQLValueString($_POST['lugar_entrega_egp'], "text"),
                       GetSQLValueString($_POST['responsable_egp'], "text"),
					   GetSQLValueString($_POST['observacion4_egp'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "imprimir_egp.php?n_egp=" . $_POST['n_egp'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_usuario_encuesta = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_encuesta = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_encuesta = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_encuesta);
$usuario_encuesta = mysql_query($query_usuario_encuesta, $conexion1) or die(mysql_error());
$row_usuario_encuesta = mysql_fetch_assoc($usuario_encuesta);
$totalRows_usuario_encuesta = mysql_num_rows($usuario_encuesta);

$colname_datos_cliente = "1";
if (isset($_GET['nit_c'])) {
  $colname_datos_cliente = (get_magic_quotes_gpc()) ? $_GET['nit_c'] : addslashes($_GET['nit_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_datos_cliente = sprintf("SELECT * FROM cliente WHERE nit_c = '%s'", $colname_datos_cliente);
$datos_cliente = mysql_query($query_datos_cliente, $conexion1) or die(mysql_error());
$row_datos_cliente = mysql_fetch_assoc($datos_cliente);
$totalRows_datos_cliente = mysql_num_rows($datos_cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = "SELECT * FROM egp ORDER BY n_egp DESC";
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo14 {color: #000066}
.Estilo36 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
	font-weight: bold;
}
.Estilo40 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo42 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.Estilo49 {font-size: 12px}
.Estilo57 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066;}
.Estilo59 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #000066;}
.Estilo60 {font-family: Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; font-size: 11px; }
.Estilo62 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000066;
}
.Estilo64 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
.Estilo69 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo70 {font-family: Arial, Helvetica, sans-serif}
.Estilo71 {font-weight: bold; color: #000066;}
.Estilo72 {color: #000000}
.Estilo74 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #000066; }
.Estilo75 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo76 {font-weight: bold; color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo78 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000066; font-weight: bold; }
.Estilo80 {color: #FF0000}
-->
</style>
<script language="JavaScript" src="calendar2.js"></script>
<script language="JavaScript">
<!--
function consulta()
{
window.location ='egp.php?nit_c='+document.form1.nit_c.value;
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
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' debe ser un email.\n';
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
<script language="JavaScript" src="calendar2.js"></script>
</head>

<body>
<table width="728" height="100" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td width="792" height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="360" class="Estilo14 Estilo49 Estilo57 Estilo14"><div align="left"><?php echo $row_usuario_encuesta['nombre_usuario']; ?></div></td>
          <td width="362"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo59">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bordercolor="#666666" bgcolor="#CCCCCC">
    <td height="10" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td colspan="4" bgcolor="#ECF5FF"><div align="center" class="Estilo14"><span class="Estilo36">ESPECIFICACION GENERAL DEL PRODUCTO</span></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td colspan="2" bgcolor="#FFFFFF"><div align="center" class="Estilo14"><span class="Estilo42">Codigo: R1-F08</span></div></td>
        <td colspan="2" bgcolor="#FFFFFF"><div align="center" class="Estilo14"><span class="Estilo40 Estilo49">Versi&oacute;n: 1 </span></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td colspan="4" bgcolor="#ECF5FF"><div align="center">
          <div align="center" class="Estilo60 Estilo75">DATOS DEL CLIENTE </div>
        </div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td width="115" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo60"><strong>Nit</strong></div></td>
        <td width="230" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo49 Estilo62 Estilo72"><?php echo $row_datos_cliente['nit_c']; ?> </div></td>
        <td width="97" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo60">Tipo Cliente </div></td>
        <td width="252" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['tipo_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td height="17" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo60"><strong>Raz&oacute;n Social </strong></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['nombre_c']; ?></div></td>
        <td width="97" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo60">Telefono</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['telefono_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td height="18" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo60">Direccion</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['direccion_c']; ?></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo60">Fax</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['fax_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo60">Contacto Comercial </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['contacto_c']; ?></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo60">Pais</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['cod_pais_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo60">Celular</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['celular_contacto_c']; ?></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo60">Provincia</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['cod_dpto_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo60">Email</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['email_comercial_c']; ?></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo60">Ciudad</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo64"><?php echo $row_datos_cliente['cod_ciudad_c']; ?></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td height="100" bordercolor="#FFFFFF">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form2" onSubmit="MM_validateForm('n_egp','','RisNum','fecha_egp','','R','ancho_egp','','RisNum','largo_egp','','RisNum','pigm_ext_egp','','R','solapa_egp','','RisNum','pigm_int_epg','','R','largo_cang_egp','','RisNum','calibre_egp','','RisNum','cantidad_egp','','R','responsable_egp','','R');return document.MM_returnValue">
        <table width="735" border="0" align="center" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td colspan="2" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo78">*Especificaci&oacute;n<span class="Estilo80"> N&ordm;</span></span>
                <input name="n_egp" type="text" value="<?php
$num=$row_ver_nuevo['n_egp']+1; 
 echo $num; ?>" size="10" readonly="true">
              <input name="nit_c_egp" type="hidden" value="<?php echo $row_datos_cliente['nit_c']; ?>">
            </div></td>
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo78">*Fecha</span>
                <input name="fecha_egp" type="text" id="fecha_egp" value="<?php echo date("Y/m/d"); ?>" size="10" >
            </div></td>
          </tr>
          
          <tr valign="baseline" bordercolor="#666666" bgcolor="#999999">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo60 Estilo75">ESPECIFICACION DEL MATERIAL</div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td width="122" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Ancho</span></div></td>
            <td width="230" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <input name="ancho_egp" type="text" value="" size="10">
            </div></td>
            <td width="117" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Tipo Extrusi&oacute;n</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo60">
                <select name="tipo_ext_egp">
                  <option value="N.A.">N.A.</option>
                  <option value="Coextrusion" >Coextrusion</option>
                  <option value="Monocapa" >Monocapa</option>
                </select>
            </span></div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Largo</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <input name="largo_egp" type="text" value="" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Pigmento Exterior</span></div></td>
            <td width="245" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <input name="pigm_ext_egp" type="text" value="" size="10">
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Solapa</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <input name="solapa_egp" type="text" value="" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Pigmento Interior</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <input name="pigm_int_epg" type="text" value="" size="10">
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Alt. del Canguro</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <input name="largo_cang_egp" type="text" value="" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Adhesivo</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo60">
                <select name="adhesivo_egp">
                  <option value="N.A.">N.A.</option>
                  <option value="Cinta seguridad" >Cinta seguridad</option>
                  <option value="HOT MELT" >HOT MELT</option>
                  <option value="Cinta permanente" >Cinta permanente</option>
                  <option value="Cinta resellable" >Cinta resellable</option>
                </select>
            </span> </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Calibre</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <input name="calibre_egp" type="text" value="" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Uso de la bolsa</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <input name="tipo_bolsa_egp" type="text" value="" size="10">
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td height="24" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Cantidad</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                <div align="left">
                  <input type="text" name="cantidad_egp" value="" size="10">
                </div>
            </div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Tipo Sello </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <div align="left">
                <select name="tipo_sello_egp" id="tipo_sello_egp">
                  <option value="N.A.">N.A.</option>
                  <option value="Plano">Plano</option>
                  <option value="Hilo">Hilo</option>
                  <option value="Fondo">Fondo</option>
                </select>
              </div>
            <label></label></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
              <div align="right"><span class="Estilo60">Observaci&oacute;n</span></div>
            </div></td>
            <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <textarea name="observacion1_egp" cols="66" rows="2" id="observacion1_egp"></textarea>
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#EAEAEA">
            <td colspan="4" align="right" bordercolor="#FFFFFF"><table width="726" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
              <tr>
                <td colspan="6" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo69"><span class="Estilo76">ESPECIFICACION DE LA IMPRESION</span></div></td>
                </tr>
              <tr>
                <td width="93" align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo70"></span><span class="Estilo60">Color 1</span></div></td>
                <td width="87" align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                    <input type="text" name="color1_egp" value="" size="10">
                </div></td>
                <td width="135" align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Pantone</span></div></td>
                <td width="124" align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo69"><span class="Estilo71">
                    <input type="text" name="pantone1_egp" value="" size="10">
                </span></div></td>
                <td width="120" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Ubicacion</span></div></td>
                <td valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo60">
                    <input name="ubicacion1_egp" type="text" id="ubicacion1_egp" value="" size="10">
                </span></div></td>
              </tr>
              <tr>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo70"></span><span class="Estilo60">Color 2</span></div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                    <input type="text" name="color2_egp" value="" size="10">
                </div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Pantone</span></div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                    <input type="text" name="pantone2_egp" value="" size="10">
                </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Ubicacion</span></div></td>
                <td valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo60">
                    <input name="ubicacion2_egp" type="text" id="ubicacion2_egp" value="" size="10">
                </span></div></td>
              </tr>
              <tr>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo70"><span class="Estilo60">Color 3</span></span></div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                    <input type="text" name="color3_egp" value="" size="10">
                </div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Pantone</span></div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                    <input type="text" name="pantone3_egp" value="" size="10">
                </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Ubicacion</span></div></td>
                <td valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo60">
                    <input name="ubicacion3_egp" type="text" id="ubicacion3_egp" value="" size="10">
                </span></div></td>
              </tr>
              <tr>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo70"><span class="Estilo60">Color 4</span></span></div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                    <input type="text" name="color4_egp" value="" size="10">
                </div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Pantone</span></div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                    <input type="text" name="pantone4_egp" value="" size="10">
                </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Ubicacion</span></div></td>
                <td valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo60">
                    <input name="ubicacion4_egp" type="text" id="ubicacion4_egp" value="" size="10">
                </span></div></td>
              </tr>
            </table></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
              <div align="right"><span class="Estilo60">Observaci&oacute;n</span></div>
            </div></td>
            <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <label>
              <textarea name="observacion2_egp" cols="66" rows="2" id="observacion2_egp"></textarea>
              </label>
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#EAEAEA">
            <td colspan="4" align="right" bordercolor="#FFFFFF"><table width="726" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
              <tr>
                <td colspan="5" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><span class="Estilo69"><span class="Estilo76">ESPECIFICACION DE LA NUMERACION </span></span></div></td>
                </tr>
              <tr>
                <td width="116" bordercolor="#FFFFFF" bgcolor="#ECF5FF">
                  
                    <div align="left">
                      <input name="ppal_cctv_egp" type="checkbox" value="" >
                  <span class="Estilo60">  Ppal CCTV</span></div></td>
                <td width="133" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><label>
                  <div align="left">
                    <input name="ppal_normal_egp" type="checkbox" id="ppal_normal_egp">
                  <span class="Estilo60">Ppal Normal</span></div>                  </label></td>
                <td width="236" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="num_cinta_egp" type="checkbox" value="" >
                  <span class="Estilo60">Numero en la Cinta</span></div></td>
                <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
                  <input name="num_inf_egp" type="checkbox" value="" >
                  <span class="Estilo60">Numero Inferior</span></div></td>
              </tr>
              
              <tr>
                <td height="24" colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="cod_barra_egp" type="checkbox" value="" >
                  <span class="Estilo60">Codigo de Barras</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo60">
                  <input type="checkbox" name="fecha_cad_egp" value="" >
                  Incluir fecha de caducidad de la bolsa</span></div></td>
                <td width="84" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Comienza en</span></div></td>
                <td width="129" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="comienza_egp" type="text" value="" size="10">
                </div></td>
              </tr>
            </table></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Observaci&oacute;n</span></div></td>
            <td colspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              
              <div align="left">
                  <textarea name="form_egp" cols="66" rows="2"></textarea>
            </div></td>
          </tr>
          
          <tr valign="baseline" bordercolor="#666666" bgcolor="#EAEAEA">
            <td colspan="4" align="right" bordercolor="#FFFFFF"><table width="726" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
              <tr>
                <td colspan="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo69"><span class="Estilo76">ESPECIFICACION DEL ARTE</span></div></td>
                </tr>
              <tr>
                <td width="258" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="arte_sum_egp" type="checkbox" value="" >
                  <span class="Estilo60">Arte suministrado por el cliente.</span></div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Persona Encargada del dise&ntilde;o</span></div></td>
                <td valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="disenador_egp" type="text" value="" size="20">
                </div></td>
              </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="orient_arte_egp" type="checkbox" value="" >
                  <span class="Estilo60">Solicita orientaci&oacute;n en el arte. </span></div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Telefono</span></div></td>
                <td valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="telef_disenador_egp" type="text" value="" size="20">
                </div></td>
              </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="ent_logo_egp" type="checkbox" value="" >
                  <span class="Estilo60">El cliente entrega logos de la entidad. </span></div></td>
                <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Observaci&oacute;n</span></div></td>
            <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <textarea name="observacion3_egp" cols="66" rows="2" id="observacion3_egp"></textarea>
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#EAEAEA">
            <td colspan="4" align="right" bordercolor="#FFFFFF"><table width="726" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
              <tr>
                <td colspan="4" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo69"><span class="Estilo76">ESPECIFICACION DEL DESPACHO</span></div></td>
                </tr>
              <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
                <td width="117" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Unidad por Paquete</span></div></td>
                <td width="133" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo60">
                    <input type="text" name="unids_paq_egp" value="" size="10">
                </div></td>
                <td width="242" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Unidad por Caja </span></div></td>
                <td width="211" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo60">
                    <input name="unids_caja_egp" type="text" id="unids_caja_egp" value="" size="10">
                </span></div></td>
              </tr>
              <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
                <td height="24" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Marca de cajas </span></div></td>
                <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                    <input name="marca_cajas_egp" type="text" value="" size="10">
                </div></td>
                <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">Lugar de entrega de mercancia </span></div></td>
                <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                    <div align="left" class="Estilo60">
                      <input type="text" name="lugar_entrega_egp" value="" size="10">
                    </div>
                </div></td>
              </tr>
              <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
                <td height="24" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo60">*Registrado por </span></div></td>
                <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="responsable_egp" type="text" value="<?php echo $row_usuario_encuesta['nombre_usuario']; ?>" size="32" readonly="true">
                </div></td>
                </tr>
              
            </table></td>
          </tr>
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
              <div align="right"><span class="Estilo60">Observaci&oacute;n</span></div>
            </div></td>
            <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <textarea name="observacion4_egp" cols="66" rows="2" id="observacion4_egp"></textarea>
            </div></td>
          </tr>
          
          <tr valign="baseline" bordercolor="#666666" bgcolor="#CCCCCC">
            <td height="26" colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo69">                  <input type="submit" value="Insertar registro">
            </div></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form2">
    </form>    </td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="152"><div align="center" class="Estilo74 Estilo75"><a href="comercial.php" class="Estilo14">Gesti&oacute;n Comercial </a></div></td>
        <td width="203"><div align="right" class="Estilo74 Estilo75">
          <div align="center"><a href="listado_clientes.php" class="Estilo14">Listado Clientes</a> </div>
        </div></td>
        <td width="141"><div align="center" class="Estilo74 Estilo75"><a href="bus_egp.php" class="Estilo14" >Busqueda</a></div></td>
        <td width="124"><div align="center" class="Estilo74 Estilo75"><a href="egp_detalle.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo14">Egps</a></div></td>
        <td width="87"><div align="right"><img src="firma3.bmp"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="JavaScript">
		<!-- // create calendar object(s) just after form tag closed
			 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
			 // note: you can have as many calendar objects as you need for your application
			var cal1 = new calendar2(document.forms['form2'].elements['fecha_egp']);
			cal1.year_scroll = true;
			cal1.time_comp = false;
		//-->
</script>
</body>
</html>
<?php
mysql_free_result($usuario_encuesta);

mysql_free_result($datos_cliente);

mysql_free_result($ver_nuevo);
?>
