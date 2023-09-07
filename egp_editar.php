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
  $updateSQL = sprintf("UPDATE egp SET fecha_egp=%s, nit_c_egp=%s, ancho_egp=%s, largo_egp=%s, solapa_egp=%s, largo_cang_egp=%s, calibre_egp=%s, tipo_ext_egp=%s, pigm_ext_egp=%s, pigm_int_epg=%s, adhesivo_egp=%s, tipo_bolsa_egp=%s, cantidad_egp=%s, tipo_sello_egp=%s, observacion1_egp=%s, color1_egp=%s, pantone1_egp=%s, ubicacion1_egp=%s, color2_egp=%s, pantone2_egp=%s, ubicacion2_egp=%s, color3_egp=%s, pantone3_egp=%s, ubicacion3_egp=%s, color4_egp=%s, pantone4_egp=%s, ubicacion4_egp=%s, observacion2_egp=%s, ppal_cctv_egp=%s, ppal_normal_egp=%s, num_cinta_egp=%s, num_inf_egp=%s, comienza_egp=%s, observacion3_egp=%s, fecha_cad_egp=%s, cod_barra_egp=%s, form_egp=%s, arte_sum_egp=%s, orient_arte_egp=%s, disenador_egp=%s, telef_disenador_egp=%s, ent_logo_egp=%s, unids_paq_egp=%s, unids_caja_egp=%s, marca_cajas_egp=%s, lugar_entrega_egp=%s, responsable_egp=%s, observacion4_egp=%s WHERE n_egp=%s",
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
                       GetSQLValueString($_POST['cantidad_egp'], "int"),
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
                       GetSQLValueString($_POST['ppal_cctv_egp'], "int"),
					   GetSQLValueString($_POST['ppal_normal_egp'], "int"),
                       GetSQLValueString($_POST['num_cinta_egp'], "int"),
                       GetSQLValueString($_POST['num_inf_egp'], "int"),
                       GetSQLValueString($_POST['comienza_egp'], "text"),
                       GetSQLValueString($_POST['observacion3_egp'], "text"),
                       GetSQLValueString($_POST['fecha_cad_egp'], "int"),
                       GetSQLValueString($_POST['cod_barra_egp'], "int"),
                       GetSQLValueString($_POST['form_egp'], "text"),
                       GetSQLValueString($_POST['arte_sum_egp'], "int"),
                       GetSQLValueString($_POST['orient_arte_egp'], "int"),
                       GetSQLValueString($_POST['disenador_egp'], "text"),
                       GetSQLValueString($_POST['telef_disenador_egp'], "text"),
                       GetSQLValueString($_POST['ent_logo_egp'], "int"),
                       GetSQLValueString($_POST['unids_paq_egp'], "text"),
                       GetSQLValueString($_POST['unids_caja_egp'], "text"),
                       GetSQLValueString($_POST['marca_cajas_egp'], "text"),
                       GetSQLValueString($_POST['lugar_entrega_egp'], "text"),
                       GetSQLValueString($_POST['responsable_egp'], "text"),
                       GetSQLValueString($_POST['observacion4_egp'], "text"),
                       GetSQLValueString($_POST['n_egp'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "imprimir_egp.php?n_egp=" . $row_n_egp['n_egp'] . "&nit_c=" . $row_n_egp['nit_c_egp'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario_encuesta = "1";
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

$colname_n_egp = "1";
if (isset($_GET['n_egp'])) {
  $colname_n_egp = (get_magic_quotes_gpc()) ? $_GET['n_egp'] : addslashes($_GET['n_egp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_n_egp = sprintf("SELECT * FROM egp WHERE n_egp = '%s'", $colname_n_egp);
$n_egp = mysql_query($query_n_egp, $conexion1) or die(mysql_error());
$row_n_egp = mysql_fetch_assoc($n_egp);
$totalRows_n_egp = mysql_num_rows($n_egp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo14 {color: #000066}
.Estilo54 {font-size: 12px}
.Estilo58 {color: #000066; font-weight: bold; }
.Estilo63 {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066;}
.Estilo64 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo65 {font-family: Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; font-size: 11px; }
.Estilo72 {font-family: Arial, Helvetica, sans-serif}
.Estilo75 {font-family: Arial, Helvetica, sans-serif; color: #000000; font-size: 12px; }
.Estilo78 {
	font-size: 16px;
	font-weight: bold;
}
.Estilo82 {color: #000066; font-size: 12px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo83 {color: #000000}
.Estilo88 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; }
.Estilo93 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo94 {font-size: 11px}
.Estilo95 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo96 {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo97 {
	color: #FF0000;
	font-size: 16px;
}
.Estilo69 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo76 {font-weight: bold; color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo100 {color: #000066; font-weight: bold; font-family: Arial, Helvetica, sans-serif;}
-->
</style>

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
<table width="735" height="100" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="450" class="Estilo14 Estilo54 Estilo63"><?php echo $row_usuario_encuesta['nombre_usuario']; ?></td>
          <td width="433"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo64" >Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  
  <tr bordercolor="#CCCCCC" bgcolor="#CCCCCC">
    <td height="10" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="728" border="0" align="center" cellspacing="3" bordercolor="#EAEAEA" bgcolor="#EAEAEA">
      <tr bgcolor="#CCCCCC">
        <td colspan="4" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo14"><span class="Estilo36 Estilo72 Estilo78">ESPECIFICACION GENERAL DEL PRODUCTO</span></div></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo82"><span class="Estilo42">Codigo: R1-F08</span></div></td>
        <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo82"><span class="Estilo40 Estilo49">Versi&oacute;n: 1 </span></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td colspan="4" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
            <div align="center" class="Estilo88">DATOS DEL CLIENTE </div>
        </div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td width="112" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo65"><span class="Estilo14"><strong>Nit</strong></span></div></td>
        <td width="211" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="Estilo75"><div align="left" class="Estilo54 Estilo83"><?php echo $row_datos_cliente['nit_c']; ?> </div></td>
        <td width="152" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo65">Tipo Cliente </div></td>
        <td width="230" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo75"><?php echo $row_datos_cliente['tipo_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo65"><span class="Estilo58">Raz&oacute;n Social </span></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF" class="Estilo75"><div align="left"><?php echo $row_datos_cliente['nombre_c']; ?></div></td>
        <td width="152" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo65">Telefono</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo75"><?php echo $row_datos_cliente['telefono_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td height="17" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo65">Direcci&oacute;n</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="Estilo75"><div align="left"><?php echo $row_datos_cliente['direccion_c']; ?></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo65">Fax</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo75"><?php echo $row_datos_cliente['fax_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo65">Contacto Comercial </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF" class="Estilo75"><div align="left"><?php echo $row_datos_cliente['contacto_c']; ?></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo65">Pais</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo75"><?php echo $row_datos_cliente['cod_pais_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo65">Celular</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="Estilo75"><div align="left"><?php echo $row_datos_cliente['celular_contacto_c']; ?></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo65">Provincia</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo75"><?php echo $row_datos_cliente['cod_dpto_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo65">Email</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF" class="Estilo75"><div align="left"><?php echo $row_datos_cliente['email_comercial_c']; ?></div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo65">Ciudad</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo75"><?php echo $row_datos_cliente['cod_ciudad_c']; ?></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td height="10" bordercolor="#FFFFFF"><form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table width="728" bordercolor="#EAEAEA" bgcolor="#EAEAEA">
          <tr valign="baseline">
            <td colspan="2" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo96"><span class="Estilo14">Especificaci&oacute;n N&ordm; </span><span class="Estilo97"><?php echo $row_n_egp['n_egp']; ?></span></span>
              <input name="nit_c_egp" type="hidden" value="<?php echo $row_n_egp['nit_c_egp']; ?>">
            </div></td>
            <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo82">Fecha</span> 
              <input type="text" name="fecha_egp" value="<?php echo $row_n_egp['fecha_egp']; ?>" size="10">
            </div></td>
          </tr>

          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center">
              <div align="center" class="Estilo60 Estilo75 Estilo88">ESPECIFICACI&Oacute;N DEL MATERIAL</div>
            </div></td>
          </tr>
          <tr valign="baseline">
            <td width="130" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo82">*Ancho</span></div></td>
            <td width="223" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input type="text" name="ancho_egp" value="<?php echo $row_n_egp['ancho_egp']; ?>" size="10">
            </div></td>
            <td width="138" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">*Tipo Extrusi&oacute;n</span></div></td>
            <td width="217" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><label>
              <select name="tipo_ext_egp" id="tipo_ext_egp">
                <option value="N.A." <?php if (!(strcmp("N.A.", $row_n_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="Coextrusion" <?php if (!(strcmp("Coextrusion", $row_n_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>Coextrusion</option>
                <option value="Monocapa" <?php if (!(strcmp("Monocapa", $row_n_egp['tipo_ext_egp']))) {echo "selected=\"selected\"";} ?>>Monocapa</option>
              </select>
            </label></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo82">*Largo</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input type="text" name="largo_egp" value="<?php echo $row_n_egp['largo_egp']; ?>" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">*Pigmento Exterior</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input type="text" name="pigm_ext_egp" value="<?php echo $row_n_egp['pigm_ext_egp']; ?>" size="10"></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo82">*Solapa</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input type="text" name="solapa_egp" value="<?php echo $row_n_egp['solapa_egp']; ?>" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">*Pigmento Interior</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input type="text" name="pigm_int_epg" value="<?php echo $row_n_egp['pigm_int_epg']; ?>" size="10"></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo82">*Alt. Canguro </span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">              <div align="left">
              <input type="text" name="largo_cang_egp" value="<?php echo $row_n_egp['largo_cang_egp']; ?>" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">*Adhesivo</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><label>
              <select name="adhesivo_egp" id="adhesivo_egp">
                <option value="N.A." <?php if (!(strcmp("N.A.", $row_n_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="Cinta seguridad" <?php if (!(strcmp("Cinta seguridad", $row_n_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta seguridad</option>
                <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_n_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
                <option value="Cinta permanente" <?php if (!(strcmp("Cinta permanente", $row_n_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta permanente</option>
                <option value="Cinta resellable" <?php if (!(strcmp("Cinta resellable", $row_n_egp['adhesivo_egp']))) {echo "selected=\"selected\"";} ?>>Cinta resellable</option>
              </select>
            </label></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo82">*Calibre</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input type="text" name="calibre_egp" value="<?php echo $row_n_egp['calibre_egp']; ?>" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">Uso de la bolsa</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input type="text" name="tipo_bolsa_egp" value="<?php echo $row_n_egp['tipo_bolsa_egp']; ?>" size="10"></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo82">*Cantidad</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input type="text" name="cantidad_egp" value="<?php echo $row_n_egp['cantidad_egp']; ?>" size="10">
            </div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">Tipo Sello </span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><label>
              <select name="tipo_sello_egp" id="tipo_sello_egp">
                <option value="N.A." <?php if (!(strcmp("N.A.", $row_n_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="Plano" <?php if (!(strcmp("Plano", $row_n_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Plano</option>
                <option value="Hilo" <?php if (!(strcmp("Hilo", $row_n_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Hilo</option>
                <option value="Fondo" <?php if (!(strcmp("Fondo", $row_n_egp['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>Fondo</option>
              </select>
            </label></td>
          </tr>

          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo82">Observaci&oacute;n</span></div></td>
            <td colspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <textarea name="observacion1_egp" cols="66" rows="2"><?php echo $row_n_egp['observacion1_egp']; ?></textarea>
            </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#EAEAEA">
            <td colspan="4" align="right" bordercolor="#FFFFFF"><table width="726" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
              <tr>
                <td colspan="6" align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo69"><span class="Estilo76">ESPECIFICACION DE LA IMPRESION</span></div></td>
                </tr>
              <tr>
                <td width="98" align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">Color 1</span></div></td>
                <td width="129" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="color1_egp" value="<?php echo $row_n_egp['color1_egp']; ?>" size="10">
                </div></td>
                <td width="121" align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">Pantone</span></div></td>
                <td width="115" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="pantone1_egp" value="<?php echo $row_n_egp['pantone1_egp']; ?>" size="10">
                </div></td>
                <td width="109" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo54"><span class="Estilo100">Ubicacion</span></div></td>
                <td width="121" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="ubicacion1_egp" value="<?php echo $row_n_egp['ubicacion1_egp']; ?>" size="10">
                </div></td>
              </tr>
              <tr>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">Color 2</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="color2_egp" value="<?php echo $row_n_egp['color2_egp']; ?>" size="10">
                </div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">Pantone</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="pantone2_egp" value="<?php echo $row_n_egp['pantone2_egp']; ?>" size="10">
                </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo54"><span class="Estilo100">Ubicacion</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="ubicacion2_egp" value="<?php echo $row_n_egp['ubicacion2_egp']; ?>" size="10">
                </div></td>
              </tr>
              <tr>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo70"><span class="Estilo60">Color 3</span></span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="color3_egp" value="<?php echo $row_n_egp['color3_egp']; ?>" size="10">
                </div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">Pantone</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="pantone3_egp" value="<?php echo $row_n_egp['pantone3_egp']; ?>" size="10">
                </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo54"><span class="Estilo100">Ubicacion</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="ubicacion3_egp" value="<?php echo $row_n_egp['ubicacion3_egp']; ?>" size="10">
                </div></td>
              </tr>
              <tr>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo70"><span class="Estilo60">Color 4</span></span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="color4_egp" value="<?php echo $row_n_egp['color4_egp']; ?>" size="10">
                </div></td>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo82"><span class="Estilo60">Pantone</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="pantone4_egp" value="<?php echo $row_n_egp['pantone4_egp']; ?>" size="10">
                </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo54"><span class="Estilo100">Ubicacion</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="ubicacion4_egp" value="<?php echo $row_n_egp['ubicacion4_egp']; ?>" size="10">
                </div></td>
              </tr>
              
            </table></td>
          </tr>
		 <tr>
                <td align="right" valign="baseline" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
                  <div align="right" class="Estilo82">Observaci&oacute;n</div>
            </div></td>
                <td colspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF">
                  <div align="left">
                    <textarea name="observacion2_egp" cols="66" rows="2"><?php echo $row_n_egp['observacion2_egp']; ?></textarea>
            </div></td>
          </tr>
          <tr valign="baseline" bgcolor="#EAEAEA">
            <td colspan="4" align="right" bordercolor="#FFFFFF"><table width="726" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
              <tr>
                <td colspan="5" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><span class="Estilo69"><span class="Estilo76">ESPECIFICACION DE LA NUMERACION </span></span></div></td>
                </tr>
              <tr>
                <td width="114" height="25" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><label></label>
                  <div align="left">
                    <input <?php if (!(strcmp($row_n_egp['ppal_cctv_egp'],1))) {echo "checked=\"checked\"";} ?> name="ppal_cctv_egp" type="checkbox" id="ppal_cctv_egp" value="1">
                  <span class="Estilo82">                  Ppal CCTV</span></div></td>
                <td width="115" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input <?php if (!(strcmp($row_n_egp['ppal_normal_egp'],1))) {echo "checked=\"checked\"";} ?> name="ppal_normal_egp" type="checkbox" id="ppal_normal_egp" value="1">
                  <span class="Estilo65">Ppal Normal</span></div></td>
                <td width="237" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo65">
                  <input <?php if (!(strcmp($row_n_egp['num_cinta_egp'],1))) {echo "checked=\"checked\"";} ?> name="num_cinta_egp" type="checkbox" value="1">
                  Numero en la Cinta</div></td>
                <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo65">
                  <input <?php if (!(strcmp($row_n_egp['num_inf_egp'],1))) {echo "checked=\"checked\"";} ?> name="num_inf_egp" type="checkbox" value="1">
                  Numero Inferior</span></div></td>
                </tr>
              <tr>
                <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo65">
                  <input <?php if (!(strcmp($row_n_egp['cod_barra_egp'],1))) {echo "checked=\"checked\"";} ?> name="cod_barra_egp" type="checkbox" value="1">
                  Codigo de Barras</div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo65">
                  <input <?php if (!(strcmp($row_n_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> name="fecha_cad_egp" type="checkbox" value="1">
                  Incluir fecha de caducidad de la bolsa</span></div></td>
                <td width="116" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo65">Comienza en</span></div></td>
                <td width="118" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="comienza_egp" value="<?php echo $row_n_egp['comienza_egp']; ?>" size="10">
                </div></td>
              </tr>


            </table></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
              <div align="right" class="Estilo82">Observaci&oacute;n</div>
            </div></td>
            <td colspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <textarea name="observacion3_egp" cols="66" rows="2"><?php echo $row_n_egp['observacion3_egp']; ?></textarea>
            </div></td>
          </tr>
          
          <tr valign="baseline" bgcolor="#EAEAEA">
            <td colspan="4" align="right" bordercolor="#FFFFFF"><table width="726" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
              <tr>
                <td colspan="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo69"><span class="Estilo76">ESPECIFICACION DEL ARTE</span></div></td>
                </tr>
              <tr>
                <td width="236" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input <?php if (!(strcmp($row_n_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> name="arte_sum_egp" type="checkbox" value="1">
                  <span class="Estilo65">Arte suministrado por el cliente.</span></div></td>
                <td width="237" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo65">Persona Encargada del dise&ntilde;o</span></div></td>
                <td width="235" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="disenador_egp" type="text" value="<?php echo $row_n_egp['disenador_egp']; ?>" size="20">
                </div></td>
              </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input <?php if (!(strcmp($row_n_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> name="orient_arte_egp" type="checkbox" value="1">
                  <span class="Estilo65">Solicita orientaci&oacute;n en el arte. </span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo65">Telefono</span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="telef_disenador_egp" value="<?php echo $row_n_egp['telef_disenador_egp']; ?>" size="20">
                </div></td>
              </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input <?php if (!(strcmp($row_n_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> name="ent_logo_egp" type="checkbox" value="1">
                  <span class="Estilo65">El cliente entrega logos de la entidad. </span></div></td>
                <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
              <div align="right" class="Estilo82">Observaci&oacute;n</div>
            </div></td>
            <td colspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><textarea name="form_egp" cols="66" rows="2"><?php echo $row_n_egp['form_egp']; ?></textarea></td>
          </tr>
          <tr valign="baseline" bgcolor="#EAEAEA">
            <td colspan="4" align="right" bordercolor="#FFFFFF"><table width="726" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#EAEAEA">
              <tr>
                <td colspan="4" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo69"><span class="Estilo76">ESPECIFICACION DEL DESPACHO</span></div></td>
                </tr>
              <tr>
                <td width="128" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo65">Unidad por Paquete</span></div></td>
                <td width="102" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="unids_paq_egp" value="<?php echo $row_n_egp['unids_paq_egp']; ?>" size="10">
                </div></td>
                <td width="239" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo65">Unidad por Caja </span></div></td>
                <td width="234" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="unids_caja_egp" value="<?php echo $row_n_egp['unids_caja_egp']; ?>" size="10">
                </div></td>
              </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo65">Marca de cajas </span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="marca_cajas_egp" value="<?php echo $row_n_egp['marca_cajas_egp']; ?>" size="10">
                </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo65">Lugar de entrega de mercancia </span></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="lugar_entrega_egp" value="<?php echo $row_n_egp['lugar_entrega_egp']; ?>" size="10">
                </div></td>
              </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo65">*Registrado por </span></div></td>
                <td colspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="responsable_egp" value="<?php echo $row_n_egp['responsable_egp']; ?>" size="32">
                </div></td>
                </tr>
            </table></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center">
              <div align="right" class="Estilo82">Observaci&oacute;n</div>
            </div></td>
            <td colspan="3" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><textarea name="observacion4_egp" cols="66" rows="2"><?php echo $row_n_egp['observacion4_egp']; ?></textarea></td>
          </tr>
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" onClick="MM_validateForm('fecha_egp','','R','ancho_egp','','RisNum','largo_egp','','RisNum','pigm_ext_egp','','R','solapa_egp','','RisNum','pigm_int_epg','','R','largo_cang_egp','','RisNum','calibre_egp','','RisNum','tipo_bolsa_egp','','R','cantidad_egp','','RisNum','responsable_egp','','R');return document.MM_returnValue">
              <input type="submit" value="Actualizar registro">
            </div></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="n_egp" value="<?php echo $row_n_egp['n_egp']; ?>">
    </form>
    </td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="44" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="131" height="34" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo93 Estilo94"><a href="comercial.php" class="Estilo14">Gesti&oacute;n Comercial </a></div></td>
        <td width="140" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo95"><a href="listado_clientes.php" class="Estilo14">Listado Clientes </a></div></td>
        <td width="143" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo95"><a href="bus_egp.php" class="Estilo14">Nueva Busqueda</a></div></td>
        <td width="75" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo95"><a href="egp_detalle.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo14">Egps</a></div></td>
        <td width="152" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center"><a href="imprimir_egp.php?n_egp=<?php echo $row_n_egp['n_egp']; ?>&nit_c=<?php echo $row_n_egp['nit_c_egp']; ?>" class="Estilo64">Vista Preliminar </a></div></td>
        <td width="60" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><img src="firma3.bmp" alt="Menu Principal"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="JavaScript">
		<!-- // create calendar object(s) just after form tag closed
			 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
			 // note: you can have as many calendar objects as you need for your application
			var cal1 = new calendar2(document.forms['form3'].elements['fecha_egp']);
			cal1.year_scroll = true;
			cal1.time_comp = false;
		//-->
</script>
</body>
</html>
<?php
mysql_free_result($n_egp);

mysql_free_result($usuario_encuesta);

mysql_free_result($datos_cliente);
?>
