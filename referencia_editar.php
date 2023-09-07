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

  $updateSQL = sprintf("UPDATE referencia SET n_egp_ref=%s, nit_c_ref=%s, n_cotiz_ref=%s, tipo_bolsa_ref=%s, material_ref=%s, ancho_ref=%s, largo_ref=%s, solapa_ref=%s, bolsillo_guia_ref=%s, calibre_ref=%s, peso_millar_ref=%s, impresion_ref=%s, nombre_arte_ref=%s, num_pos_ref=%s, cod_barras_formato_ref=%s, adhesivo_ref=%s, fecha_aprobacion_arte_ref=%s WHERE cod_ref=%s",
                       GetSQLValueString($_POST['n_egp_ref'], "int"),
					   GetSQLValueString($_POST['nit_c_ref'], "text"),
					   GetSQLValueString($_POST['n_cotiz_ref'], "int"),
                       GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),
                       GetSQLValueString($_POST['material_ref'], "text"),
                       GetSQLValueString($_POST['ancho_ref'], "double"),
                       GetSQLValueString($_POST['largo_ref'], "double"),
                       GetSQLValueString($_POST['solapa_ref'], "double"),
                       GetSQLValueString($_POST['bolsillo_guia_ref'], "double"),
                       GetSQLValueString($_POST['calibre_ref'], "double"),
                       GetSQLValueString($_POST['peso_millar_ref'], "double"),
                       GetSQLValueString($_POST['impresion_ref'], "text"),
                       GetSQLValueString($_POST['nombre_arte_ref'], "text"),
                       GetSQLValueString($_POST['num_pos_ref'], "text"),
                       GetSQLValueString($_POST['cod_barras_formato_ref'], "text"),
                       GetSQLValueString($_POST['adhesivo_ref'], "text"),  
					   GetSQLValueString($_POST['fecha_aprobacion_arte_ref'], "date"),              
                       GetSQLValueString($_POST['cod_ref'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
    $updateGoTo = "referencias.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario_referencia = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_referencia = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_referencia = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_referencia);
$usuario_referencia = mysql_query($query_usuario_referencia, $conexion1) or die(mysql_error());
$row_usuario_referencia = mysql_fetch_assoc($usuario_referencia);
$totalRows_usuario_referencia = mysql_num_rows($usuario_referencia);

$colname_ver_referencia = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_ver_referencia = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_referencia = sprintf("SELECT * FROM referencia WHERE cod_ref = '%s'", $colname_ver_referencia);
$ver_referencia = mysql_query($query_ver_referencia, $conexion1) or die(mysql_error());
$row_ver_referencia = mysql_fetch_assoc($ver_referencia);
$totalRows_ver_referencia = mysql_num_rows($ver_referencia);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo47 {
	color: #000066;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 18px;
}
.Estilo86 {color: #000066}
.Estilo89 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo90 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo91 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo96 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo100 {color: #000066; font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo107 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #990000; }
.Estilo58 {font-size: 11px}
.Estilo77 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo109 {color: #FF0000}
.Estilo111 {
	font-size: 12px;
	color: #000066;
	font-weight: bold;
}
.Estilo114 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; }
.Estilo116 {
	font-size: 14px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FF0000;
}
.Estilo117 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
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
<script language="JavaScript" src="calendar2.js"></script>
<script language="javascript">
function calcular()
{
$var1=document.form1.largo_ref.value;
$var2=document.form1.solapa_ref.value;
$var3=parseInt($var1);
$var4=parseInt($var2);
$var5=document.form1.ancho_ref.value*(($var3+$var4))*document.form1.calibre_ref.value*0.00467;
$var5=parseFloat($var5);
$var5=Math.round($var5*100)/100 ;
document.form1.peso_millar_ref.value=$var5;
}
</script>
<script LANGUAGE="JavaScript">
<!--
function detener(){
return true
}
window.onerror=detener

function verFoto(img, ancho, alto){
  derecha=(screen.width-ancho)/2;
  arriba=(550-alto)/2;
  string="toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width="+ancho+",height="+alto+",left="+derecha+",top="+arriba+"";
  fin=window.open(img,"",string);
}

function popUp(URL, ancho, alto) {
day = new Date();
id = day.getTime();
derecha=(screen.width-ancho)/2;
arriba=(screen.height-alto)/2;
ventana="toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width="+ancho+",height="+alto+",left="+derecha+",top="+arriba+"";
eval("page" + id + " = window.open(URL, '" + id + "', '" + ventana + "');");
}
// -->
</script>
</head>

<body>
<table width="735" height="10" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" colspan="2" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td width="378" height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left"><span class="Estilo90"><?php echo $row_usuario_referencia['nombre_usuario']; ?></span></div></td>
    <td width="345" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo91">Cerrar Sesi&oacute;n</a> </div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="26" colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo47"> REFERENCIA </div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="23" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('n_egp_ref','','RisNum','ancho_ref','','RisNum','largo_ref','','RisNum','solapa_ref','','RisNum','bolsillo_guia_ref','','RisNum','calibre_ref','','RisNum','peso_millar_ref','','RisNum','impresion_ref','','R','num_pos_ref','','R','cod_barras_formato_ref','','R');return document.MM_returnValue">
        <table width="735" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="justify" class="Estilo107"> Recuerde que cualquier actualización realizada no afectara los datos de Gestión Comercial, ya que esta es informaci&oacute;n individual.</div></td>
          </tr>
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo100"><span class="Estilo77">*Referencia</span> </span><span class="Estilo116"> <?php echo $row_ver_referencia['cod_ref']; ?></span></div></td>
            </tr>
          <tr valign="baseline">
            <td width="136" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo77"><span class="Estilo73 Estilo77 Estilo111">*Cliente</span></span></div></td>
            <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo90">
              <input name="nit_c_ref" type="hidden" id="nit_c_ref" value="<?php echo $row_ver_referencia['nit_c_ref']; ?>">
              <?php 
				mysql_select_db($database_conexion1, $conexion1);
				$nit=$row_ver_referencia['nit_c_ref'];
				if($nit != '')
				{
				if($nit != 'Varios')
				{				
				$sql2="SELECT * FROM cliente WHERE nit_c='$nit'";
				$result2=mysql_query($sql2);
				echo mysql_result($result2,0,"nombre_c"); 
				}
				else
				{
				echo "Varios";
				}
				}
				?>
            </div></td>
            </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo96">
              <div align="right"><span class="Estilo77 Estilo73"><strong><span class="Estilo86">*Egp</span> <span class="Estilo109">N&ordm;</span> </strong></span></div>
            </div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input name="n_egp_ref" type="text" id="n_egp_ref" value="<?php echo $row_ver_referencia['n_egp_ref']; ?>" size="10" readonly="true">
            </div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo114">Primera Cotizaci&oacute;n </div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <label>
              <input name="n_cotiz_ref" type="text" id="n_cotiz_ref" value="<?php echo $row_ver_referencia['n_cotiz_ref']; ?>" size="10" readonly="true">
              </label>
            </div></td>
          </tr>
		   <tr valign="baseline">
		     <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114"><span class="Estilo81">*Tipo de Bolsa</span></div></td>
            <td width="156" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left"><span class="Estilo96">
              <select name="tipo_bolsa_ref" id="tipo_bolsa_ref" title="<?php echo $row_ver_referencia['tipo_bolsa_ref']; ?>">
                <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_referencia['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="Seguridad" <?php if (!(strcmp("Seguridad", $row_ver_referencia['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>Seguridad</option>
                <option value="Currier" <?php if (!(strcmp("Currier", $row_ver_referencia['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>Currier</option>
              </select>
            </span></div></td>
            <td width="189" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114"><span class="Estilo81">*Material</span></div></td>
            <td width="211" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo96">
              <select name="material_ref" id="material_ref" title="<?php echo $row_ver_referencia['material_ref']; ?>">
                <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_referencia['material_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="Ldpe coestruido pigmentado" <?php if (!(strcmp("Ldpe coestruido pigmentado", $row_ver_referencia['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido pigmentado</option>
                <option value="Ldpe coestruido sin pigmentos" <?php if (!(strcmp("Ldpe coestruido sin pigmentos", $row_ver_referencia['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido sin pigmentos</option>
                <option value="Ldpe monocapa sin pigmentos" <?php if (!(strcmp("Ldpe monocapa sin pigmentos", $row_ver_referencia['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa sin pigmentos</option>
                <option value="Ldpe monocapa pigmentado" <?php if (!(strcmp("Ldpe monocapa pigmentado", $row_ver_referencia['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa pigmentado</option>
              </select>
            </div></td>
		   </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo114"><span class="Estilo73 ">*Ancho</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo96">
              <input type="text" name="ancho_ref" value="<?php echo $row_ver_referencia['ancho_ref']; ?>" size="10" onBlur="calcular()">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo114"><span class="Estilo82">*Impresion</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo96">
              <input type="text" name="impresion_ref" value="<?php echo $row_ver_referencia['impresion_ref']; ?>" size="30">
            </div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114"><span class="Estilo73 ">*Largo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo96">
              <input type="text" name="largo_ref" value="<?php echo $row_ver_referencia['largo_ref']; ?>" size="10" onBlur="calcular()">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114"><span class="Estilo82">Nombre del Arte</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo96">
              <input name="nombre_arte_ref" type="text" value="<?php echo $row_ver_referencia['nombre_arte_ref']; ?>" size="15" readonly="true">
              <small><font color="#808080">[ </font>
              <?php $archivo= $row_ver_referencia['nombre_arte_ref'];?>
              <a href="javascript:verFoto('archivo/<?php echo $archivo;?>','610','490')"> <?php echo $archivo;?></a><font color="#808080"> ]</font></small></div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo114"><span class="Estilo73 ">*Solapa</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo96">
              <input type="text" name="solapa_ref" value="<?php echo $row_ver_referencia['solapa_ref']; ?>" size="10" onBlur="calcular()">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo114"><span class="Estilo82">*Numeraci&oacute;n &amp; Posiciones</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo96">
                <input type="text" name="num_pos_ref" value="<?php echo $row_ver_referencia['num_pos_ref']; ?>" size="30">
            </div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114"><span class="Estilo73 ">*Bolsillo Porta Guia</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo96">
              <input type="text" name="bolsillo_guia_ref" value="<?php echo $row_ver_referencia['bolsillo_guia_ref']; ?>" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114"><span class="Estilo82">*Codigo Barras &amp; Formato</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo96">
                <label>
                <select name="cod_barras_formato_ref" id="cod_barras_formato_ref">
                  <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_referencia['cod_barras_formato_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="EAN 128" <?php if (!(strcmp("EAN 128", $row_ver_referencia['cod_barras_formato_ref']))) {echo "selected=\"selected\"";} ?>>EAN 128</option>
                </select>
                </label>
            </div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo114"><span class="Estilo73 ">*Calibre</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo96">
              <input type="text" name="calibre_ref" value="<?php echo $row_ver_referencia['calibre_ref']; ?>" size="10"  onBlur="calcular()">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo114"><span class="Estilo82">*Tipo de Adhesivo</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo96">
                <select name="adhesivo_ref" id="adhesivo_ref" title="<?php echo $row_ver_referencia['adhesivo_ref']; ?>">
                  <option value="N.A." <?php if (!(strcmp("N.A.", $row_ver_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="Cinta de Seguridad" <?php if (!(strcmp("Cinta de Seguridad", $row_ver_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta de Seguridad</option>
                  <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_ver_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
                  <option value="Cinta Permanente" <?php if (!(strcmp("Cinta Permanente", $row_ver_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta Permanente</option>
                  <option value="Cinta Resellable" <?php if (!(strcmp("Cinta Resellable", $row_ver_referencia['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta Resellable</option>
                </select>
            </div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114"><span class="Estilo73 ">*Peso Millar</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo96">
              <input type="text" name="peso_millar_ref" value="<?php echo $row_ver_referencia['peso_millar_ref']; ?>" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114"><span class="Estilo82">Fecha de Aprobaci&oacute;n Arte</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo96">
              <input name="fecha_aprobacion_arte_ref" type="text" id="fecha_aprobacion_arte_ref" value="<?php echo $row_ver_referencia['fecha_aprobacion_arte_ref']; ?>" size="10"/>
              aaaa-mm-dd</div></td>
          </tr>
          
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo96">
              <input type="submit" value="Actualizar registro">
            </div></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="cod_ref" value="<?php echo $row_ver_referencia['cod_ref']; ?>">
      </form>
      </div></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="31" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" cellspacing="3">
      <tr>
        <td width="102"><div align="left" class="Estilo89">
            <div align="center"><a href="menu.php" class="Estilo86"><img src="home.gif" alt="Menu Principal" width="22" height="23"></a></div>
        </div></td>
        <td width="253"><div align="center" class="Estilo89"><a href="referencias.php" class="Estilo117">Listado de Referencias </a></div></td>
        <td width="265"><div align="right" class="Estilo89">
            <div align="center"><a href="disenoydesarrollo.php" class="Estilo117">Dise&ntilde;o y Desarrollo </a></div>
        </div></td>
        <td width="92"><div align="right"><span class="Estilo58"><img src="firma3.bmp"></span></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="JavaScript">
		<!-- // create calendar object(s) just after form tag closed
			 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
			 // note: you can have as many calendar objects as you need for your application
			var cal1 = new calendar2(document.forms['form1'].elements['fecha_aprobacion_arte_ref']);
			cal1.year_scroll = true;
			cal1.time_comp = false;
		//-->
</script>
</body>
</html>
<?php
mysql_free_result($usuario_referencia);

mysql_free_result($ver_referencia);
?>
