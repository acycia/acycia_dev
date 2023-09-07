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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
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


  $insertSQL = sprintf("INSERT INTO revision (id_rev, nit_c_rev, cod_ref_rev, n_egp_rev, fecha_rev, tipo_bolsa_egp, translape_rev, capa_rev, peso_max_rev, presentacion_rev, color1_egp, ubicacion1_egp, pantone1_egp, color2_egp, ubicacion2_egp, pantone2_egp, color3_egp, ubicacion3_egp, pantone3_egp, color4_egp, ubicacion4_egp, pantone4_egp, num_rodillos_rev, repeticion_rev, tipo_elong_rev, valor_tipo_elong_rev, recibir_muestra_rev, recibir_artes_rev, recibir_textos_rev, orientacion_textos_rev, cinta_afecta_rev, valor_cinta_afecta_rev, entregar_arte_elong_rev, orientacion_total_arte_rev, pos_tal_recibo_rev, pos_cinta_seg_rev, pos_ppal_rev, pos_inf_rev, alt_tal_recibo_rev, alt_cinta_seg_rev, alt_ppal_rev, alt_inf_rev, cod_barras_recibo_rev, cod_barras_cinta_seg_rev, cod_barras_ppal_rev, cod_barras_inf_rev, formato_tal_recibo_rev, formato_cinta_seg_rev, formato_ppal_rev, formato_inf_rev, observacion_rev, responsable_rev) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_rev'], "int"),
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
                       GetSQLValueString($_POST['responsable_rev'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $result2=mysql_query($sql2, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "revision_detalle.php?cod_ref=" . $row_ver_referencia['cod_ref'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

$colname_ver_referencia = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_ver_referencia = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_referencia = sprintf("SELECT * FROM referencia, egp, cliente WHERE referencia.cod_ref = '%s' and  referencia.n_egp_ref=egp.n_egp and egp.nit_c_egp=cliente.nit_c", $colname_ver_referencia);
$ver_referencia = mysql_query($query_ver_referencia, $conexion1) or die(mysql_error());
$row_ver_referencia = mysql_fetch_assoc($ver_referencia);
$totalRows_ver_referencia = mysql_num_rows($ver_referencia);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = "SELECT * FROM revision ORDER BY id_rev DESC";
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

mysql_select_db($database_conexion1, $conexion1);
$tipo_usuario=$row_usuario_comercial['tipo_usuario'];
$query_ver_sub_menu = "SELECT distinct(id_submenu),nombre_submenu,url,submenu FROM submenu,permisos,usuario WHERE permisos.menu='1' AND permisos.submenu=submenu.id_submenu and permisos.usuario='$tipo_usuario'";
$ver_sub_menu = mysql_query($query_ver_sub_menu, $conexion1) or die(mysql_error());
$row_ver_sub_menu = mysql_fetch_assoc($ver_sub_menu);
$totalRows_ver_sub_menu = mysql_num_rows($ver_sub_menu);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo14 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
	color: #000066;
}
.Estilo49 {
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
.Estilo64 {color: #000066}
.Estilo65 {
	font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS";
	font-weight: bold;
	color: #000066;
	font-size: 11px;
}
.Estilo70 {color: #000000}
.Estilo82 {font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; color: #000066;}
.Estilo92 {font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; color: #000066; font-size: 12px; }
.Estilo93 {
	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
	font-size: 12px;
}
.Estilo99 {font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; font-weight: bold; color: #000066; font-size: 12px; }
.Estilo104 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000066; }
.Estilo105 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo107 {font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; color: #000066; font-size: 12px; }
.Estilo108 {
	font-size: 11px;
	font-family: Arial, Helvetica, sans-serif;
}
.Estilo113 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #000066;}
.Estilo114 {font-family: Arial, Helvetica, sans-serif}
.Estilo115 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #000066; font-size: 12px; }
.Estilo116 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo120 {font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #000066; font-size: 11px; }
.Estilo121 {font-size: 11px}
.Estilo125 {font-family: Arial, Helvetica, sans-serif; color: #990000; font-size: 12px; }
.Estilo126 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo127 {color: #FF0000}
.Estilo128 {font-size: 12px}
.Estilo130 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000033; }
.Estilo133 {font-size: 14px}
.Estilo136 {font-family: Arial, Helvetica, sans-serif; font-size: 10px;}
.Estilo137 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
<script language="JavaScript" src="calendar2.js"></script>
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
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' debe ser un email.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' debe ser numerico.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' debe ser un intervalo entre '+min+' y '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' es obligatorio.\n'; }
  } if (errors) alert('Favor corregir los siguientes campos:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body>
<table width="735" height="100" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td width="753" height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="450"><span class="Estilo104"><?php echo $row_usuario_comercial['nombre_usuario']; ?></span></td>
        <td width="433"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo105">Cerrar Sesi&oacute;n</a></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="50" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" height="100" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
<tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <td width="722" colspan="2" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <tr>
      <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo14">PLAN DE DISE&Ntilde;O Y DESARROLLO </div></td>
      </tr>
    <tr>
      <td width="351" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo107 Estilo114 Estilo128">Codigo: R2-F01 </div></td>
      <td width="371" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo115 Estilo128">Versi&oacute;n: 2 </div></td>
    </tr>
    
  </table></td>
</tr>
          <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
            <td height="21" colspan="2" bgcolor="#FFFFFF"><div align="center" class="Estilo14">1. REVISION</div></td>
          </tr>
          
          <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
            <td height="100" colspan="2" bgcolor="#FFFFFF"><div align="center">
              <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('id_rev','','RisNum','fecha_rev','','R','cod_ref_rev','','R','nit_c_rev','','R','responsable_rev','','R');return document.MM_returnValue">
                <table width="735" height="50" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                  
                  <tr valign="baseline" bordercolor="#FFFFFF">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">*Revisi&oacute;n <span class="Estilo127">N&ordm;</span></span></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo115">
                      <input name="id_rev" id="id_rev" type="text" size="10" value="<?php
$num=$row_ver_nuevo['id_rev']+1; 
 echo $num; ?>"readonly="true">
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">*Fecha </span></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo99">
                      <input type="text" id="fecha_rev" name="fecha_rev" size="10" value="<?php echo date("Y/m/d"); ?>" >
				    </div></td>
                  </tr>
                  
                  <tr valign="baseline" bordercolor="#FFFFFF">
                    <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo92 Estilo126">INFORMACION GENERAL DEL CLIENTE </div></td>
                  </tr>
                  
                  <tr valign="baseline">
                    <td width="124" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115">*Referencia</span></div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
                      <input name="cod_ref_rev" type="hidden" value="<?php echo $row_referencia['cod_ref']; ?>">
                      <span class="Estilo133"><?php echo $row_referencia['cod_ref']; ?></span></div></td>
                    <td width="151" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115"> EGP <span class="Estilo127">N&ordm;</span> </span></div></td>
                    <td width="233" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo82 Estilo116">
                      <label>
                      <input name="n_egp_rev" type="hidden" id="n_egp_rev" value="<?php echo $row_referencia['n_egp_ref']; ?>">
                      </label>
                      <?php echo $row_referencia['n_egp_ref']; ?></div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115">Cliente</span></div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
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
					<input name="nit_c_rev" type="hidden" value="<?php 
					  $nit=$row_ver_referencia['nit_c']; 
					  if($nit<>'')
					  {
					  echo $nit;
					  }
					  else
					  {
					  echo $row_referencia['nit_c_ref'];
					  } ?>">
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115">Cotizaci&oacute;n <span class="Estilo127">N&ordm;</span></span></div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
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

                  <tr valign="baseline">
                    <td colspan="5" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo115 Estilo126">INFORMACION GENERAL DE LA BOLSA </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Ancho</span></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116 Estilo64"><?php echo $row_referencia['ancho_ref']; ?></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Tipo de Bolsa </span></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo130"><?php echo $row_referencia['tipo_bolsa_ref']; ?></div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Largo</span></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93"><?php echo $row_referencia['largo_ref']; ?></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Material</span></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo130"><?php echo $row_referencia['material_ref']; ?></div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Solapa</span></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93"><?php echo $row_referencia['solapa_ref']; ?></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Uso de la Bolsa </span></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <label>
                      <input name="tipo_bolsa_egp" type="text" id="tipo_bolsa_egp" value="<?php 
					  $tipo=$row_ver_referencia['tipo_bolsa_egp'];
					   if($tipo<>'')
					   {
					   echo $tipo;
					   } ?>" size="20">
                      </label>
                    </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Bolsillo Portaguia </span></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93"><?php echo $row_referencia['bolsillo_guia_ref']; ?></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Translape</span></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <input name="translape_rev" type="text" value="" size="20">
                    </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Capa</span></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo49">
                      <select name="capa_rev">
                        <option value="N.A.">N.A.</option>
                        <option value="A sobre B">A sobre B</option>
                        <option value="B sobre A">B sobre A</option>
                        </select>
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><span class="Estilo115">Peso Max. Aplicado </span></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <input name="peso_max_rev" type="text" value="" size="20">
                    kilos</div></td>
                  </tr>
                  
                  <tr valign="baseline">
                    <td colspan="5" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo107">MATERIAL A IMPRIMIR </div></td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td colspan="3" align="right" bgcolor="#ECF5FF"><div align="center" class="Estilo115">COLORES DE IMPRESION </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF" class="Estilo93">Presentaci&oacute;n</td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <select name="presentacion_rev">
                        <option value="N.A.">N.A.</option>
                        <option value="Lamina">Lamina</option>
                        <option value="Tubular">Tubular</option>
                        <option value="Semitubular">Semitubular</option>
                      </select>
                    </div></td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td align="right" bgcolor="#ECF5FF"><div align="right" class="Estilo108"><span class="Estilo64">Color 1 </span></div></td>
                    <td width="142" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo136">
                      <label>
                      <input name="color1_egp" type="text" id="color1_egp" value="<?php 
					  $color1=$row_ver_referencia['color1_egp'];
					  if($color1<>'')
					  {
					  echo $color1;
					  }
					   ?>" size="15">
                      </label>
                    </div></td>
                    <td width="61" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo113">Ubicaci&oacute;n</span></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo136">
                      <input name="ubicacion1_egp" type="text" id="ubicacion1_egp" value="<?php echo $row_ver_referencia['ubicacion1_egp']; 
					  /*if($ubicacion1<>'')
					  {
					  echo $ubicacion;
					  }*/?>" size="15">
                    </div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo116">
                      <div align="left" class="Estilo64"><span class="Estilo121">Pantone  <span class="Estilo70">. </span></span>
                        <input name="pantone1_egp" type="text" id="pantone1_egp" value="<?php 
						$pantone1=$row_ver_referencia['pantone1_egp']; 
						if($pantone1<>'')
						{
						echo $pantone1;
						}?>" size="15">
                      </div>
                    </div></td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo113">Color 2 </span></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo136">
                      <input name="color2_egp" type="text" id="color2_egp" value="<?php
					  $color2=$row_ver_referencia['color2_egp'];
					  if($color2<>'')
					  {
					  echo $color2;
					  } ?>" size="15">
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo113">Ubicaci&oacute;n</span></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo136">
                      <input name="ubicacion2_egp" type="text" id="ubicacion2_egp" value="<?php 
					  $ubicacion2=$row_ver_referencia['ubicacion2_egp']; 
					  if($ubicacion2<>'')
					  {
					  echo $ubicacion2;
					  }?>" size="15">
                    </div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo116">
                      <div align="left"><span class="Estilo64"><span class="Estilo121">Pantone . </span></span>
                        <input name="pantone2_egp" type="text" id="pantone2_egp" value="<?php
						$pantone2=$row_ver_referencia['pantone2_egp'];
						if($pantone2<>'')
						{
						echo $pantone2;
						} ?>" size="15">
                      </div>
                    </div></td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo113">Color 3 </span></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                      <input name="color3_egp" type="text" id="color3_egp" value="<?php
					  $color3=$row_ver_referencia['color3_egp'];
					  if($color3<>'')
					  {
					  echo $color3;
					  } ?>" size="15">
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo113">Ubicaci&oacute;n</span></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo136">
                      <input name="ubicacion3_egp" type="text" id="ubicacion3_egp" value="<?php $ubicacion3=$row_ver_referencia['ubicacion3_egp'];
					   if($ubicacion3<>'')
					   {
					   echo $ubicacion3;
					   } ?>" size="15">
                    </div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo116">
                      <div align="left"><span class="Estilo64"><span class="Estilo121">Pantone . </span></span>
                        <input name="pantone3_egp" type="text" id="pantone3_egp" value="<?php
						$pantone3=$row_ver_referencia['pantone3_egp']; 
						if($pantone3<>'')
						{
						echo $pantone3;
						}
						?>" size="15">
                      </div>
                    </div></td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo113">Color 4 </span></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo136">
                      <input name="color4_egp" type="text" id="color4_egp" value="<?php
					  $color4=$row_ver_referencia['color4_egp']; 
					  if($color4<>'')
					  {
					  echo $color4;
					  }?>" size="15">
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo113">Ubicaci&oacute;n</span></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo136">
                      <input name="ubicacion4_egp" type="text" id="ubicacion4_egp" value="<?php 
					  $ubicacion4=$row_ver_referencia['ubicacion4_egp'];
					  if($ubicacion4<>'')
					  {
					  echo $ubicacion4;
					  } ?>" size="15">
                    </div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo116">
                      <div align="left"><span class="Estilo64"><span class="Estilo121">Pantone . </span></span>
                        <input name="pantone4_egp" type="text" id="pantone4_egp" value="<?php 
						$pantone4=$row_ver_referencia['pantone4_egp'];
						if($pantone4<>'')
						{
						echo $pantone4;
						} ?>" size="15">
                      </div>
                    </div></td>
                  </tr>
                  
                  <tr valign="baseline">
                    <td colspan="5" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo107">INFORMACION DE PRODUCCION SOBRE NEGATIVOS Y CYREL </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Rodillo N&ordm; </span></div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <input type="text" name="num_rodillos_rev" value="" size="15">
                      <span class="Estilo64">cms</span></div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Repeticiones x Revoluci&oacute;n </span></div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <input type="text" name="repeticion_rev" value="" size="20">
                    </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Tipo de Elongaci&oacute;n </span></div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <select name="tipo_elong_rev">
                        <option value="N.A.">N.A.</option>
                        <option value="A lo ancho">A lo ancho</option>
                        <option value="A lo largo">A lo largo</option>
                        </select>
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Valor</span></div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <input type="text" name="valor_tipo_elong_rev" value="" size="20">
                    </div></td>
                  </tr>
                  
                  <tr valign="baseline">
                    <td colspan="5" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo107">ARTE</div></td>
                  </tr>

                  <tr valign="baseline">
                    <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
                      <div align="left">
                        <input name="recibir_muestra_rev" type="checkbox" value="" >
                        Se recibe bosquejo o muestra fisica del cliente. </div>
                    </div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116"><span class="Estilo64">
                      <input name="recibir_artes_rev" type="checkbox" value="" >
                    Se recibe arte completo del cliente o logos. </span></div></td>
                  </tr>
                  <tr valign="baseline">
                    <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
                      <input name="recibir_textos_rev" type="checkbox" value="" >
                    Se reciben solo textos por el cliente. </div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116"><span class="Estilo64">
                      <input name="orientacion_textos_rev" type="checkbox" value="" >
                    Se solicita orientaci&oacute;n en textos de seguridad. </span></div></td>
                  </tr>
                  <tr valign="baseline">
                    <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
                      <input name="cinta_afecta_rev" type="checkbox" value="" >
                    La cinta afecta la altura de la solapa.</div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo93">Indique Valor </span><span class="Estilo116">
                      <input type="text" name="valor_cinta_afecta_rev" value="" size="20">
                    </span></div></td>
                  </tr>
                  <tr valign="baseline">
                    <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
                      <input name="entregar_arte_elong_rev" type="checkbox" value="" >
                    Se debe entregar arte incluyendo elongaci&oacute;n. </div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116"><span class="Estilo64">
                      <input name="orientacion_total_arte_rev" type="checkbox" value="" >
                    Se solicita orientaci&oacute;n total en el arte.</span> </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td colspan="5" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
                      <div align="center">Nota: La cinta puede afectar la altura de la bolsa si esta tiene solapa. El arte debe de explicar muy bien si esta incluida. </div>
                    </div></td>
                  </tr>
                  
                  <tr valign="baseline">
                    <td colspan="5" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo115 Estilo126">NUMERACION Y CODIGOS DE BARRAS (posiciones, tipos y formato) </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo65 Estilo116 Estilo121">POSICIONES</div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo120">ALTURA NUMERO </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo120">CODIGO DE BARRAS </div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo120">TIPO DE FORMATO </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Talonario Recibo
                      <input type="checkbox" name="pos_tal_recibo_rev" value="" > 
                    </span></div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF">
                      <div align="center" class="Estilo116">
                        <select name="alt_tal_recibo_rev">
                          <option value="N.A.">N.A.</option>
                          <option value="Normal">Normal</option>
                          <option value="CCTV">CCTV</option>
                        </select>
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Talonario Recibo
                      <input type="checkbox" name="cod_barras_recibo_rev" value="" >
                    </span></div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo116">
                      <label>
                      <input name="formato_tal_recibo_rev" type="text" id="formato_tal_recibo_rev" size="20">
                      </label>
                    </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Cinta Seguridad 
                          <input type="checkbox" name="pos_cinta_seg_rev" value="" >
                    </span></div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo116"> 
                        <select name="alt_cinta_seg_rev">
                            <option value="N.A.">N.A.</option>
                            <option value="Normal">Normal</option>
                            <option value="CCTV">CCTV</option>
                        </select>
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Cinta Seguridad 
                      <input type="checkbox" name="cod_barras_cinta_seg_rev" value="" >
                    </span></div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo116">
                      <input name="formato_cinta_seg_rev" type="text" id="formato_cinta_seg_rev" size="20">
                    </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Principal 
                      <input type="checkbox" name="pos_ppal_rev" value="" >
                    </span></div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo116"> 
                        <select name="alt_ppal_rev">
                            <option value="N.A.">N.A.</option>
                            <option value="Normal">Normal</option>
                            <option value="CCTV">CCTV</option>
                        </select>
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Principal
                      <input type="checkbox" name="cod_barras_ppal_rev" value="" >
                    </span></div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo116">
                      <input name="formato_ppal_rev" type="text" id="formato_ppal_rev" size="20">
                    </div></td>
                  </tr>
                  <tr valign="baseline">
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Inferior 
                      <input type="checkbox" name="pos_inf_rev" value="" >
                    </span></div></td>
                    <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo116"> 
                        <select name="alt_inf_rev">
                            <option value="N.A.">N.A.</option>
                            <option value="Normal">Normal</option>
                            <option value="CCTV">CCTV</option>
                        </select>
                    </div></td>
                    <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo93">Inferior
                      <input type="checkbox" name="cod_barras_inf_rev" value="" >
                    </span></div></td>
                    <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo116">
                      <input name="formato_inf_rev" type="text" id="formato_inf_rev" size="20">
                    </div></td>
                  </tr>

                  <tr valign="baseline">
                    <td colspan="5" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo107">OBSERVACIONES GENERALES </div></td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td colspan="5" align="right" bgcolor="#ECF5FF">                      <div align="left" class="Estilo93">
                      <div align="center">1. Se debe de entregar arte y montaje mecanico para la elaboraci&oacute;n de negativos.</div>
                    </div>                      </td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td height="17" colspan="5" align="right" bgcolor="#ECF5FF">                      <div align="left" class="Estilo93">
                        <div align="center">2. Se deben de dejar los espacios reservados para la numeraci&oacute;n en el arte.</div>
                    </div>                      </td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115">Otras observaciones</span></div></td>
                    <td colspan="4" rowspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo93">
                      <textarea name="observacion_rev" cols="60" rows="5"></textarea>
                    </div>                      </td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td height="21" align="right" bgcolor="#ECF5FF"><span class="Estilo114"></span></td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                    <td align="right" bgcolor="#ECF5FF"><div align="right"><span class="Estilo115">*Registrado por </span></div></td>
                    <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo116">
                      <input name="responsable_rev" type="text" value="<?php echo $row_usuario_comercial['nombre_usuario']; ?>" size="40" readonly="true">
                    </div>                      </td>
                  </tr>
                  
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
                    <td colspan="5" align="right" bgcolor="#ECF5FF"><div align="justify" class="Estilo125">
                      <div align="center">Si inserta este registro los datos referentes a la especificaci&oacute;n general del producto seran actualizadas automaticamente. </div>
                    </div></td>
                  </tr>
                  <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
                    <td colspan="5" align="right" bgcolor="#FFFFFF"><div align="center" class="Estilo116">                      
                        <input type="submit" value="Insertar registro">
                    </div></td>
                  </tr>
                </table>
                <input type="hidden" name="MM_insert" value="form1">
              </form>
            </div></td>
          </tr>
        </table>        </td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="23" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="95" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo108">
            <div align="center"><a href="menu.php"><span class="Estilo64"><img src="home.gif" alt="Menu Principal" width="22" height="23"></span></a></div>
        </div></td>
        <td width="178" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo113"><a href="referencias.php" class="Estilo137">Listado Referencias </a></div></td>
        <td width="169" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo113"><a href="revision_detalle.php?cod_ref=<?php echo $row_ver_referencia['cod_ref']; ?>" class="Estilo137">Detalle Revisi&oacute;n</a> </div></td>
        <td width="177" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo113">
          <div align="center"><a href="disenoydesarrollo.php" class="Estilo137">Dise&ntilde;o y Desarrollo </a></div>
        </div></td>
        <td width="88" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><img src="firma3.bmp"></div></td>
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

mysql_free_result($ver_sub_menu);
?>
