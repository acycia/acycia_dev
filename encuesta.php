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

  $insertSQL = sprintf("INSERT INTO encuesta_msc (n_msc, nit_c_msc, fecha_msc, entrega_pedido_msc, documentacion_msc, servicio_msc, empaque_msc, agilidad_msc, respaldo_msc, servicio_comercial_msc, orientacion_msc, respuesta_msc, desarrollo_msc, innovaciones_msc, tamaños_msc, seguridad_msc, legibilidad_msc, resistencia_msc, fuerza_msc, empaque_solic_msc, entrega_msc, posicion_msc, suministro_msc, otros_suministros_msc, recomendaciones_msc, puntaje_msc) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_msc'], "int"),
                       GetSQLValueString($_POST['nit_c_msc'], "text"),
					   GetSQLValueString($_POST['fecha_msc'], "date"),
                       GetSQLValueString($_POST['entrega_pedido_msc'], "text"),
                       GetSQLValueString($_POST['documentacion_msc'], "text"),
                       GetSQLValueString($_POST['servicio_msc'], "text"),
                       GetSQLValueString($_POST['empaque_msc'], "text"),
                       GetSQLValueString($_POST['agilidad_msc'], "text"),
                       GetSQLValueString($_POST['respaldo_msc'], "text"),
                       GetSQLValueString($_POST['servicio_comercial_msc'], "text"),
                       GetSQLValueString($_POST['orientacion_msc'], "text"),
                       GetSQLValueString($_POST['respuesta_msc'], "text"),
                       GetSQLValueString($_POST['desarrollo_msc'], "text"),
                       GetSQLValueString($_POST['innovaciones_msc'], "text"),
                       GetSQLValueString($_POST['tamaos_msc'], "text"),
                       GetSQLValueString($_POST['seguridad_msc'], "text"),
                       GetSQLValueString($_POST['legibilidad_msc'], "text"),
                       GetSQLValueString($_POST['resistencia_msc'], "text"),
                       GetSQLValueString($_POST['fuerza_msc'], "text"),
                       GetSQLValueString($_POST['empaque_solic_msc'], "text"),
                       GetSQLValueString($_POST['entrega_msc'], "text"),
                       GetSQLValueString($_POST['posicion_msc'], "text"),
                       GetSQLValueString($_POST['suministro_msc'], "text"),
                       GetSQLValueString($_POST['otros_suministros_msc'], "text"),
                       GetSQLValueString($_POST['recomendaciones_msc'], "text"),
                       GetSQLValueString($_POST['puntaje_msc'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "encuesta_detalle.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

$colname_n_encuesta = "1";
if (isset($_GET['nit_c_msc'])) {
  $colname_n_encuesta = (get_magic_quotes_gpc()) ? $_GET['nit_c_msc'] : addslashes($_GET['nit_c_msc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_n_encuesta = sprintf("SELECT * FROM encuesta_msc WHERE nit_c_msc = '%s' ORDER BY n_msc DESC", $colname_n_encuesta);
$n_encuesta = mysql_query($query_n_encuesta, $conexion1) or die(mysql_error());
$row_n_encuesta = mysql_fetch_assoc($n_encuesta);
$totalRows_n_encuesta = mysql_num_rows($n_encuesta);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = "SELECT * FROM encuesta_msc ORDER BY n_msc DESC";
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
.Estilo12 {font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"}
.Estilo14 {color: #000066}
.Estilo15 {
	font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS";
	color: #000066;
	font-weight: bold;
}
.Estilo29 {
	color: #000066;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.Estilo33 {color: #000066; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo37 {color: #000066; font-weight: bold; font-family: Geneva, Arial, Helvetica, sans-serif; }
.Estilo50 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; }
.Estilo52 {font-size: 12px; color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo53 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo36 {	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 18px;
}
.Estilo41 {	font-weight: bold;
	font-size: 12px;
	color: #000066;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo42 {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.Estilo54 {font-family: Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; font-size: 12px; }
.Estilo58 {color: #000066; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; font-size: 12; font-weight: bold; }
.Estilo68 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #000066; }
.Estilo70 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo71 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo72 {color: #000066; font-size: 11px;}
.Estilo73 {font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
	color: #000066;
}
.Estilo74 {color: #FF0000}
.Estilo76 {
	font-size: 14px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	color: #000066;
}
-->
</style>
<script language="JavaScript" src="calendar2.js"></script>
<script language="JavaScript" >
<!--
function calcular()
{
document.form1.puntaje_msc.value=parseInt(document.form1.entrega_pedido_msc.value)+parseInt(document.form1.documentacion_msc.value)+parseInt(document.form1.servicio_msc.value)+parseInt(document.form1.empaque_msc.value)+parseInt(document.form1.agilidad_msc.value)+parseInt(document.form1.respaldo_msc.value)+parseInt(document.form1.servicio_comercial_msc.value)+parseInt(document.form1.orientacion_msc.value)+parseInt(document.form1.respuesta_msc.value)+parseInt(document.form1.desarrollo_msc.value)+parseInt(document.form1.innovaciones_msc.value)+parseInt(document.form1.tamaos_msc.value)+parseInt(document.form1.seguridad_msc.value)+parseInt(document.form1.legibilidad_msc.value)+parseInt(document.form1.resistencia_msc.value)+parseInt(document.form1.fuerza_msc.value)+parseInt(document.form1.empaque_solic_msc.value)+parseInt(document.form1.entrega_msc.value);
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
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' es obligatorio.\n'; }
  } if (errors) alert('Favor coregir los siguientes campos:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body>
<table width="739" height="10" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="450"><span class="Estilo52"><?php echo $row_usuario_encuesta['nombre_usuario']; ?></span></td>
          <td width="433"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo53">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#999999">
        <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo33 Estilo36">ENCUESTA PARA LA MEDICION DE LA SATISFACCI&Oacute;N DEL CLIENTE </div></td>
      </tr>
      <tr bgcolor="#999999">
        <td width="441" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo41">Codigo: R1-F04 </div></td>
        <td width="442" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo37 Estilo42">Versi&oacute;n: 0 </div></td>
      </tr>
    </table></td>
  </tr>
  
  <tr bgcolor="#FFFFFF">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td width="125" bgcolor="#ECF5FF"><div align="right" class="Estilo54">Raz&oacute;n Social </div></td>
        <td width="221" bgcolor="#ECF5FF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['nombre_c']; ?></div></td>
        <td width="122" bgcolor="#ECF5FF"><div align="right" class="Estilo54">Tipo Cliente </div></td>
        <td width="228" bgcolor="#ECF5FF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['tipo_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bgcolor="#FFFFFF"><div align="right" class="Estilo54">Nit</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['nit_c']; ?></div></td>
        <td width="122" bgcolor="#FFFFFF"><div align="right" class="Estilo54">Telefono</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['telefono_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td height="17" bgcolor="#ECF5FF"><div align="right" class="Estilo54">Direccion</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['direccion_c']; ?></div></td>
        <td bgcolor="#ECF5FF"><div align="right" class="Estilo54">Fax</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['fax_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bgcolor="#FFFFFF"><div align="right" class="Estilo54">Contacto Comercial </div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['contacto_c']; ?></div></td>
        <td bgcolor="#FFFFFF"><div align="right" class="Estilo54">Pais</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['cod_pais_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bgcolor="#ECF5FF"><div align="right" class="Estilo54">Celular</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['celular_contacto_c']; ?></div></td>
        <td bgcolor="#ECF5FF"><div align="right" class="Estilo54">Provincia</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['cod_dpto_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td height="17" bgcolor="#FFFFFF"><div align="right" class="Estilo54">Email</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['email_comercial_c']; ?></div></td>
        <td bgcolor="#FFFFFF"><div align="right" class="Estilo54">Ciudad</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo50"><?php echo $row_datos_cliente['cod_ciudad_c']; ?></div></td>
      </tr>
    </table>      </td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('n_msc','','RisNum','fecha_msc','','R','puntaje_msc','','R');return document.MM_returnValue">
        <table width="735" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td width="191" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo54"><span class="Estilo58">
              <input name="nit_c_msc" type="hidden" value="<?php echo $row_datos_cliente['nit_c']; ?>">
            </span>Encuesta <span class="Estilo74">N&ordm;</span></span></div></td>
            <td width="158" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <div align="left" class="Estilo54">
                <input name="n_msc" type="text" value="<?php
$num=$row_ver_nuevo['n_msc']+1; 
 echo $num; ?>" size="10" readonly="true">
            </div></td>
            <td width="189" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo54">Fecha Encuesta </span></div></td>
            <td width="160" bordercolor="#FFFFFF" bgcolor="#ECF5FF">
			  <div align="left">
			    <input name="fecha_msc" type="text" id="fecha_msc" value="<?php echo date("Y/m/d"); ?>" size="10">			
	        </div></td>
          </tr>
          
          <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
            <td colspan="4" align="right" bgcolor="#FFFFFF">
            <div align="center" class="Estilo76">
              <div align="left">I. SATISFACI&Oacute;N:</div>
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <div align="justify" class="Estilo29">Por favor califique, los siguientes atributos de acuerdo a como percibe usted el desempe&ntilde;o de nuestra empresa &quot; Alberto Cadavid &amp; C&iacute;a. S.A. &quot;. </div>
            </div></td>
          </tr>
          
          <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999" class="Estilo12">
            <td colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo54">LOG&Iacute;STICA</div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">a. Entrega de pedidos seg&uacute;n la fecha pactada. </div>              <div align="left" class="Estilo54">                     </div></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">
              <select name="entrega_pedido_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
                </select>
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">
              <div align="left">b.Claridad en la documentaci&oacute;n (remisi&oacute;n, lista de empaque, factura).</div>
            </div>
            <div align="left" class="Estilo54">             </div></td>
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">
              <select name="documentacion_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
                </select>
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">c.Servicio prestado por el transportador. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="servicio_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">d. Forma de empaque </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="empaque_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          
          <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999" class="Estilo12">
            <td colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo54">COMERCIAL</div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">a. Agilidad en atenci&oacute;n y soluci&oacute;n a quejas y reclamos.</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="agilidad_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">b. Respaldo ante emergencias. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="respaldo_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">c. Servicio prestado por el &aacute;rea comercial.</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="servicio_comercial_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          
          <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999" class="Estilo12">
            <td colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo54">SERVICIO TECNICO </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">a. Orientaci&oacute;n en especificaciones t&eacute;cnicas y de seguridad de la bolsa. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="orientacion_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">b. Repuesta ante inquietudes de dise&ntilde;o. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="respuesta_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">c. Desarrollo de artes.</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="desarrollo_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">d. propuesta sobre innovaciones y desarrollos de nuevos productos. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="innovaciones_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          
          <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999" class="Estilo12">
            <td colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo15 Estilo29">CALIDAD (Cumplimiento de las especificaciones del producto) </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">a. Tama&ntilde;os y Calibres seg&uacute;n lo pactado.</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="tamaos_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">b. Seguridad y confiabilidad en el sistema de cierre autoadhesivo (cinta). </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="seguridad_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">c. Legibilidad en numeraci&oacute;n y c&ograve;digo de barras. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="legibilidad_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">d. Resistencia mec&aacute;nica de la bolsa. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="resistencia_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">e. Fuerza de los sellos al calor. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="fuerza_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">f. Unidad de empaque solicitada. </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="empaque_solic_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">g. Entrega de cantidades solicitadas.</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="entrega_msc" onChange="calcular()">
                <option value="0"></option>
                <option value="1">Malo</option>
                <option value="2">Deficiente</option>
                <option value="3">Normal</option>
                <option value="4">Bueno</option>
                <option value="5">Muy bueno</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo54">Puntaje de satisfacci&oacute;n:</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
              <input name="puntaje_msc" type="text" id="puntaje_msc" size="10" readonly="true">
            </div></td>
          </tr>
          
          <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#999999">
            <td colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo76">II. Oportunidad </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">a. Seg&uacute;n su opini&oacute;n, indique como est&aacute; posicionada nuestra empresa frente al resto de sus <br>proveedores?</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="posicion_msc">
                <option value="No es relevante" >No es relevante</option>
                <option value="Escasa diferenciación" >Escasa diferenciación</option>
                <option value="En competencia" >En competencia</option>
                <option value="Posición destacada" >Posición destacada</option>
                <option value="Liderazgo" >Liderazgo</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">b. Por que motivos su opci&oacute;n es nuestra empresa en el suministro de empaques de <br>seguridad? </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF">
              <span class="Estilo54">
              <select name="suministro_msc">
                <option value="Calidad en servicio" >Calidad en servicio</option>
                <option value="Confiabilidad" >Confiabilidad</option>
                <option value="Precio" >Precio</option>
                <option value="Otro" >Otro</option>
              </select>
            </span>            </td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC">
            <td colspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo54">Si su motivo es otro suministro, defina cual?</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><input name="otros_suministros_msc" type="text" value="" size="20"></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo54">c. Que recomendaciones nos dar&iacute;a usted para mejorar su nivel de satisfacci&oacute;n?</div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC" class="Estilo12">
            <td colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo58">
              <textarea name="recomendaciones_msc" cols="80"></textarea>
            </div></td>
          </tr>
          <tr valign="baseline" bordercolor="#CCCCCC" bgcolor="#999999" class="Estilo12">
            <td colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo58">
              <input type="submit" value="Insertar registro">
            </div></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
          </form>    </td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="2" bgcolor="#FFFFFF"><table width="735" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="136"><div align="center" class="Estilo68 Estilo70"><a href="comercial.php" class="Estilo14">Gesti&oacute;n Comercial</a></div></td>
        <td width="188"><div align="right" class="Estilo53">
          <div align="center"><a href="listado_clientes.php" class="Estilo14">Listado Clientes</a></div>
        </div></td>
        <td width="154"><div align="center" class="Estilo71"><a href="bus_encuesta.php" class="Estilo72">Busqueda</a></div></td>
        <td width="129"><div align="center" class="Estilo53"><a href="encuesta_detalle.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo14">Encuestas</a></div></td>
        <td width="100"><div align="right"><span class="Estilo73"><img src="firma3.bmp"></span></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="JavaScript">
		<!-- // create calendar object(s) just after form tag closed
			 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
			 // note: you can have as many calendar objects as you need for your application
			var cal1 = new calendar2(document.forms['form1'].elements['fecha_msc']);
			cal1.year_scroll = true;
			cal1.time_comp = false;
		//-->
</script>
</body>
</html>
<?php
mysql_free_result($usuario_encuesta);

mysql_free_result($datos_cliente);

mysql_free_result($n_encuesta);

mysql_free_result($ver_nuevo);
?>
