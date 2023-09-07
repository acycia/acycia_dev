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
  $insertSQL = sprintf("INSERT INTO proveedor (nit_p, proveedor_p, tipo_p, direccion_p, pais_p, dpto_p, ciudad_p, telefono_p, fax_p, contacto_p, celular_c_p, email_c_p, contribuyentes_p, autoretenedores_p, regimen_p, prod_serv_p, directo_p, punto_dist_p, forma_pago_p, otra_p, sist_calidad_p, norma_p, certificado_p, frecuencia_p, analisis_p, muestra_p, orden_compra_p, mayor_p, tiempo_agil_p, tiempo_p, entrega_p, metodos_p, flete_p, requisito_p, plan_mejora_p, aspecto_p, precios_p, otro_caso_p, asesor_com_p, nombre_asesor_p, limite_min_p, cuanto_p, proceso_p, encuestador_p, cargo_enc_p, fecha_diligencia_p, calificacion_p) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['nit_p'], "text"),
                       GetSQLValueString($_POST['proveedor_p'], "text"),
                       GetSQLValueString($_POST['tipo_p'], "text"),
                       GetSQLValueString($_POST['direccion_p'], "text"),
                       GetSQLValueString($_POST['pais_p'], "text"),
                       GetSQLValueString($_POST['dpto_p'], "text"),
                       GetSQLValueString($_POST['ciudad_p'], "text"),
                       GetSQLValueString($_POST['telefono_p'], "text"),
                       GetSQLValueString($_POST['fax_p'], "text"),
                       GetSQLValueString($_POST['contacto_p'], "text"),
                       GetSQLValueString($_POST['celular_c_p'], "text"),
                       GetSQLValueString($_POST['email_c_p'], "text"),
                       GetSQLValueString(isset($_POST['contribuyentes_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['autoretenedores_p']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['regimen_p'], "text"),
                       GetSQLValueString($_POST['prod_serv_p'], "text"),
                       GetSQLValueString($_POST['directo_p'], "int"),
                       GetSQLValueString($_POST['punto_dist_p'], "text"),
                       GetSQLValueString($_POST['forma_pago_p'], "int"),
                       GetSQLValueString($_POST['otra_p'], "text"),
                       GetSQLValueString($_POST['sist_calidad_p'], "int"),
                       GetSQLValueString($_POST['norma_p'], "text"),
                       GetSQLValueString($_POST['certificado_p'], "int"),
                       GetSQLValueString($_POST['frecuencia_p'], "text"),
                       GetSQLValueString($_POST['analisis_p'], "int"),
                       GetSQLValueString($_POST['muestra_p'], "text"),
                       GetSQLValueString($_POST['orden_compra_p'], "int"),
                       GetSQLValueString($_POST['mayor_p'], "text"),
                       GetSQLValueString($_POST['tiempo_agil_p'], "int"),
                       GetSQLValueString($_POST['tiempo_p'], "text"),
                       GetSQLValueString($_POST['entrega_p'], "int"),
                       GetSQLValueString($_POST['metodos_p'], "text"),
                       GetSQLValueString($_POST['flete_p'], "int"),
                       GetSQLValueString($_POST['requisito_p'], "text"),
                       GetSQLValueString($_POST['plan_mejora_p'], "int"),
                       GetSQLValueString($_POST['aspecto_p'], "text"),
                       GetSQLValueString($_POST['precios_p'], "int"),
                       GetSQLValueString($_POST['otro_caso_p'], "text"),
                       GetSQLValueString($_POST['asesor_com_p'], "int"),
                       GetSQLValueString($_POST['nombre_asesor_p'], "text"),
                       GetSQLValueString($_POST['limite_min_p'], "int"),
                       GetSQLValueString($_POST['cuanto_p'], "text"),
                       GetSQLValueString($_POST['proceso_p'], "int"),
                       GetSQLValueString($_POST['encuestador_p'], "text"),
                       GetSQLValueString($_POST['cargo_enc_p'], "text"),
					   GetSQLValueString($_POST['fecha_diligencia_p'], "date"),
                      
                       GetSQLValueString($_POST['calificacion_p'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "listado_proveedor.php";
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

mysql_select_db($database_conexion1, $conexion1);
$tipo_usuario=$row_usuario_comercial['tipo_usuario'];
$query_ver_sub_menu = "SELECT distinct(id_submenu),nombre_submenu,url,submenu FROM submenu,permisos,usuario WHERE permisos.menu='3' AND permisos.submenu=submenu.id_submenu and permisos.usuario='$tipo_usuario'";
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
.Estilo18 {color: #000066}
.Estilo106 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066;}
.Estilo114 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; color: #000066; }
.Estilo122 {color: #000066; font-weight: bold;}
.Estilo126 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold; color: #000066; }
.Estilo129 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo130 {font-family: Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; color: #000066; }
.Estilo132 {
	color: #000066;
	font-weight: bold;
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo133 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo140 {font-size: 12}
.Estilo141 {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	color: #FF0000;
	font-weight: bold;
}
.Estilo46 {	font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
	color: #000066;
}
.Estilo143 {
	font-size: 18px;
	color: #000066;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo144 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000066;
	font-weight: bold;
}
.Estilo147 {font-size: 12px; color: #000066; font-family: Arial, Helvetica, sans-serif;}
-->
</style>
<script language="JavaScript" >
<!--
function validarCampo1()
{
    $nit=document.form1.nit_p.value;
    if ($nit.length < 7)
    {
    alert('El NIT o ID del proveedor debe ser como mínimo de 6 caracteres. Ejemplo: 890.915.756-6  ó  57.290.228');  
    form1.nit_p.focus(); return true;
    }
	else
	{
	window.location ='seleccion_proveedor.php?nit_p='+document.form1.nit_p.value+'&proveedor_p='+document.form1.proveedor_p.value;
	}	
}

function consulta()
{
window.location ='seleccion_proveedor.php?tipo_p='+document.form1.tipo_p.value+
'&pais_p='+document.form1.pais_p.value+
'&dpto_p='+document.form1.dpto_p.value+
'&ciudad_p='+document.form1.ciudad_p.value+
'&proveedor_p='+document.form1.proveedor_p.value+
'&nit_p='+document.form1.nit_p.value+
'&direccion_p='+document.form1.direccion_p.value+
'&pais_p='+document.form1.pais_p.value+
'&telefono_p='+document.form1.telefono_p.value+
'&fax_p='+document.form1.fax_p.value+
'&contacto_p='+document.form1.contacto_p.value+
'&celular_c_p='+document.form1.celular_c_p.value+
'&email_c_p='+document.form1.email_c_p.value+
'&regimen_p='+document.form1.regimen_p.value+
'&contribuyentes_p='+document.form1.contribuyentes_p.value+
'&autoretenedores_p='+document.form1.autoretenedores_p.value;
}

function calcular()
{
$contador=0;
$var11=parseInt(document.form1.directo_p.value);
if($var11=="5" || $var11=="3" || $var11=="1")
{
++$contador;
}
$var12=parseInt(document.form1.forma_pago_p.value);
if($var12=="5" || $var12=="3" || $var12=="1")
{
++$contador;
}
$var13=parseInt(document.form1.sist_calidad_p.value);
if($var13=="5" || $var13=="3" || $var13=="1")
{
++$contador;
}
$var14=parseInt(document.form1.certificado_p.value);
if($var14=="5" || $var14=="3" || $var14=="1")
{
++$contador;
}
$var15=parseInt(document.form1.analisis_p.value);
if($var15=="5" || $var15=="3" || $var15=="1")
{
++$contador;
}
$var16=parseInt(document.form1.orden_compra_p.value);
if($var16=="5" || $var16=="3" || $var16=="1")
{
++$contador;
}
$var17=parseInt(document.form1.tiempo_agil_p.value);
if($var17=="5" || $var17=="3" || $var17=="1")
{
++$contador;
}
$var18=parseInt(document.form1.entrega_p.value);
if($var18=="5" || $var18=="3" || $var18=="1")
{
++$contador;
}
$var19=parseInt(document.form1.flete_p.value);
if($var19=="5" || $var19=="1")
{
++$contador;
}
$var20=parseInt(document.form1.plan_mejora_p.value);
if($var20=="5" || $var20=="1")
{
++$contador;
}
$var21=parseInt(document.form1.precios_p.value);
if($var21=="5" || $var21=="3" || $var21=="1")
{
++$contador;
}
$var22=parseInt(document.form1.asesor_com_p.value);
if($var22=="5" || $var22=="3" || $var22=="1")
{
++$contador;
}
$var23=parseInt(document.form1.limite_min_p.value);
if($var23=="5" || $var23=="1")
{
++$contador;
}
$var24=parseInt(document.form1.proceso_p.value);
if($var24=="5" || $var24=="1")
{
++$contador;
}

$var25=parseInt($contador);
$var26=$var25*5;
$var27=$var11+$var12+$var13+$var14+$var15+$var16+$var17+$var18+$var19+$var20+$var21+$var22+$var23+$var24;
$var28=($var27/$var26)*100;
$num = String($var28);
$ind = $num.indexOf('.') + 3;
$add = $num.charAt($num.indexOf('.') + 3);
$num = $num.substring(0, $ind);
if($add == '-1'){
$num = Number($num);
}
else if($add > 4){
$num = Number($num) + .01;
}
document.form1.calificacion_p.value=$num;
}

function consulta1()
{
window.location ='seleccion_proveedor.php?pais_p='+document.form1.pais_p.value+
'&dpto_p='+document.form1.dpto_p.value+
'&ciudad_p='+document.form1.ciudad_p.value+
'&proveedor_p='+document.form1.proveedor_p.value+
'&nit_p='+document.form1.nit_p.value+
'&direccion_p='+document.form1.direccion_p.value+
'&tipo_p='+document.form1.tipo_p.value+
'&telefono_p='+document.form1.telefono_p.value+
'&fax_p='+document.form1.fax_p.value+
'&contacto_p='+document.form1.contacto_p.value+
'&celular_c_p='+document.form1.celular_c_p.value+
'&email_c_p='+document.form1.email_c_p.value+
'&regimen_p='+document.form1.regimen_p.value+
'&contribuyentes_p='+document.form1.contribuyentes_p.value+
'&autoretenedores_p='+document.form1.autoretenedores_p.value;
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
  } if (errors) alert('Favor corregir:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body>
<table width="730" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td bgcolor="#FFFFFF"><table width="730" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="356"><div align="left" class="Estilo106"><?php echo $row_usuario_comercial['nombre_usuario']; ?></div></td>
        <td width="366"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo114">Cerrar Sesi&oacute;n</a></div></td>
      </tr>
    </table></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('proveedor_p','','R','nit_p','','R','direccion_p','','R','pais_p','','R','telefono_p','','R','ciudad_p','','R','email_c_p','','NisEmail','contacto_p','','R','encuestador_p','','R','cargo_enc_p','','R','fecha_diligencia_p','','R');return document.MM_returnValue">
        <table width="730" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr valign="baseline" bgcolor="#FFFFFF">
            <td width="703" bordercolor="#FFFFFF"><table width="730" border="1" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              <tr>
                <td colspan="2" bordercolor="#DFDFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo143">SELECCION DE PROVEEDORES </div></td>
                </tr>
              <tr>
                <td width="356" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo144">CODIGO : A3-F03 </div></td>
                <td width="358" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo144">VERSION : 0 </div></td>
              </tr>
              
            </table></td>
          </tr>
          <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
            <td><table width="730" border="1" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              <tr>
                <td colspan="4" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo132">I. INFORMACION COMERCIAL</div></td>
                </tr>
              <tr>
                <td width="130" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">*RAZON SOCIAL</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo126">
                  <input type="text" name="proveedor_p" value="<?php echo $_REQUEST['proveedor_p']; ?>" size="30">
                </span></div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* NIT - C.C. - ID </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF">
				<div align="left"><span class="Estilo126">
				<?php //Validacion del Nit del Proveedor.
				$nit=$_GET['nit_p'];				
				mysql_select_db($database_conexion1, $conexion1);								
				if($nit != '')
				{				
				$sql2="SELECT * FROM proveedor WHERE nit_p='$nit'";
				$result2=mysql_query($sql2);
				$num_nit=mysql_num_rows($result2);
				}				
				?>
				<input type="text" name="nit_p" onchange="validarCampo1()" value="<?php if($num_nit == '0') { echo $_GET['nit_p']; } ?>" size="20">
				  <?php if($num_nit == '0')
				  { echo 'VALIDADO'; }					 						
				  if($num_nit != '0' && $num_nit != '')
				  { echo 'EXISTE!!!!'; } ?>
                </span></div></td>
              </tr>
              <tr>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* DIRECCION</div></td>
                <td width="221" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo126">
                  <input name="direccion_p" type="text" value="<?php echo $_REQUEST['direccion_p']; ?>" size="30">
                </span></div></td>
                <td width="142" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* TIPO</div></td>
                <td width="209" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo122">
                  <select name="tipo_p" onblur="consulta()">
                    <option value="0" <?php if (!(strcmp(0, $_REQUEST['tipo_p']))) {echo "selected=\"selected\"";} ?>>*</option>
                    <option value="B (No Critico)" <?php if (!(strcmp("B (No Critico)", $_REQUEST['tipo_p']))) {echo "selected=\"selected\"";} ?>>B (No Critico)</option>
                    <option value="A (Critico)" <?php if (!(strcmp("A (Critico)", $_REQUEST['tipo_p']))) {echo "selected=\"selected\"";} ?>>A (Critico)</option>
                    <option value="A y B" <?php if (!(strcmp("A y B", $_REQUEST['tipo_p']))) {echo "selected=\"selected\"";} ?>>A y B</option>
                  </select>
                </span></div></td>
              </tr>
              <tr>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* PAIS</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                    <input name="pais_p" type="text" id="pais_p" value="<?php echo $_REQUEST['pais_p']; ?>" size="30">
                </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* TELEFONO</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF">                  <div align="left">
                  <input type="text" name="telefono_p" value="<?php echo $_REQUEST['telefono_p'] ?>" size="20">                
                </div></td>
              </tr>
              <tr>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* PROVINCIA</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                    <input name="dpto_p" type="text" id="dpto_p" value="<?php echo $_REQUEST['dpto_p']; ?>" size="30">
                </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* FAX</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF">                  <div align="left">
                  <input type="text" name="fax_p" value="<?php echo $_REQUEST['fax_p'] ?>" size="20">                
                </div></td>
              </tr>
              <tr>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* CIUDAD</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                    <input name="ciudad_p" type="text" id="ciudad_p" value="<?php echo $_REQUEST['ciudad_p']; ?>" size="30">
                </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* EMAIL</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo126">
                  <input type="text" name="email_c_p" value="<?php echo $_REQUEST['email_c_p'] ?>" size="20">
                </span></div></td>
              </tr>
              <tr>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">*CONTACTO </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo126">
                  <input type="text" name="contacto_p" value="<?php echo $_REQUEST['contacto_p'] ?>" size="30">
                </span></div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"> * CELULAR</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo126">
                  <input type="text" name="celular_c_p" value="<?php echo $_REQUEST['celular_c_p'] ?>" size="20">
                </span></div></td>
              </tr>
              <tr>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="contribuyentes_p" type="checkbox" id="contribuyentes_p" value="1">
                  <span class="Estilo114">CONTRIBUYENTES</span></div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="autoretenedores_p" type="checkbox" id="autoretenedores_p" value="1">
                  <span class="Estilo114">AUTORETENEDORES</span></div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF" class="Estilo147"><div align="left">* REGIMEN</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF">
                  <div align="left">
                    <select name="regimen_p">
                      <option value="" <?php if (!(strcmp("", $_REQUEST['regimen_p']))) {echo "selected=\"selected\"";} ?>>*</option>
                      <option value="Comun" <?php if (!(strcmp("Comun", $_REQUEST['regimen_p']))) {echo "selected=\"selected\"";} ?>>Comun</option>
                      <option value="Simplificado" <?php if (!(strcmp("Simplificado", $_REQUEST['regimen_p']))) {echo "selected=\"selected\"";} ?>>Simplificado</option>
                    </select>
                  </div></td>
              </tr>

            </table></td>
          </tr>

          <tr valign="baseline">
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="730" border="1" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              <tr>
                <td colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo132">II. INFORMACION DEL PROCESO - PRODUCTO / SERVICIO </div></td>
                </tr>
              <tr>
                <td width="130" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">* Productos o Servicios que Suministra. </div></td>
                <td width="584" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <textarea name="prod_serv_p" cols="60" rows="2"></textarea>
                </div></td>
              </tr>
            </table></td>
          </tr>			 
			<?php
			$var10=$_REQUEST['tipo_p'];
			if($var10=='B (No Critico)')
			{		
			?>
          <tr valign="baseline">
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo141">NO se registra la encuesta para la calificación por que es un proveedor TIPO B (NO CRITICO)</div></td>
          </tr>
          <?php 
		  }
		  else
		  {?>
		  <tr valign="baseline">
		    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="730" border="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              <tr>
                <td colspan="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo132">III. ENCUESTA PARA CALIFICACION DEL PROVEEDOR</div></td>
                </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo144">1.</span><span class="Estilo147"> Es una empresa que ofrece directamente sus productos y/o servicios,los subcontrata o tiene distribuidores?</span></div></td>
                </tr>
              <tr>
                <td width="168" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="directo_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Directo</option>
                    <option value="3">Distribuidor</option>
                    <option value="1">Subcontrata</option>
                  </select></div></td>
                <td width="287" bordercolor="#FFFFFF" bgcolor="#FFFFFF" class="Estilo147"><div align="right">Puntos de Distribuci&oacute;n ? </div></td>
                <td width="253" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="punto_dist_p" type="text" value="" size="40"></div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>2</strong>. Ofrece Formas de Pago ? </div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="forma_pago_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">30 a 60 dias</option>
                    <option value="3">15 a 29 dias</option>
                    <option value="1">Contado a 14 dias</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">Otra, Cual ? </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="otra_p" type="text" value="" size="40"></div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>3</strong>. Tiene Sistema de Gesti&oacute;n de Calidad certificado? </div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="sist_calidad_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Si</option>
                    <option value="3">En proceso</option>
                    <option value="1">No</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">Con cual Norma y que porcentaje de Avance ?</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="norma_p" type="text" value="" size="40">
                </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo147"><strong>4</strong>. Entrega certificado de calidad de sus productos con cada despacho (insumos) u ofrece garantia al servicio? </span></div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="certificado_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Si</option>
                    <option value="3">Algunas veces</option>
                    <option value="1">No</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">Con que frecuencia ?</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF">
                  <div align="left">
                    <input name="frecuencia_p" type="text" value="" size="40">
                  </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>5</strong>. Realiza analisis de control de calidad a cada lote de material ?</div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="analisis_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Si</option>
                    <option value="3">Por muestreo</option>
                    <option value="1">No</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo147">Si es por muestra, cada cuanto?</span></div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF">
                  <div align="left">
                    <input name="muestra_p" type="text" value="" size="40">
                  </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo147"><strong>6</strong>. Requiere orden de compra con anterioridad ? </span></div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                  <div align="left">
                    <select name="orden_compra_p" onChange="calcular()">
                      <option value="0"></option>
                      <option value="5">1 a 15 d&iacute;as</option>
                      <option value="3">16 a 30 d&iacute;as</option>
                      <option value="1">Mayor a 30 d&iacute;as</option>
                    </select>
                  </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo147">Si es mayor a 30 en cuanto tiempo?</span></div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="mayor_p" type="text" value="" size="40">
                </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left"><span class="Estilo147"><strong>7</strong>. Tiene establecido un tiempo para la agilidad de respuesta ante un reclamo?</span></div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">
				  <div align="left">
				    <select name="tiempo_agil_p" onChange="calcular()">
				      <option value="0"></option>
				      <option value="5">El mismo dia</option>
				      <option value="3">1 semana</option>
				      <option value="1">1 mes</option>
				      </select>
				    </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">Cuanto tiempo ?</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF">
                  <div align="left">
                    <input name="tiempo_p" type="text" value="" size="40">
                  </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>8</strong>. Realiza entrega del producto o servicio en las instalaciones de la empresa? </div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">                  <select name="entrega_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Si</option>
                    <option value="3">Con intermediario</option>
                    <option value="1">No</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">Otros metodos ?</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="metodos_p" type="text" value="" size="40">
                </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>9</strong>. El flete correspondiente a la entrega corre por parte del proveedor?</div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">                  <select name="flete_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Si</option>
                    <option value="1">No</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">&oacute; cuando se establece ese requisito ?</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="requisito_p" type="text" value="" size="40">
                </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>10</strong>. Tiene establecido un plan de mejora para el producto, servicios y/o sus procesos? </div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="plan_mejora_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Si</option>
                    <option value="1">No</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">En que aspectos ?</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="aspecto_p" type="text" value="" size="40">
                </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>11</strong>. Maneja listado de precios actualizado ?</div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="precios_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Anual</option>
                    <option value="3">Semestral</option>
                    <option value="1">Otro (&lt; 6 meses)</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">En caso de otro, explique.</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="otro_caso_p" type="text" value="" size="40"></div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>12</strong>. Asigna asesores comerciales a cada empresa?</div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="asesor_com_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Si</option>
                    <option value="3">No</option>
                  </select></div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">Nombre ? </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="nombre_asesor_p" type="text" value="" size="40">
                </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>13</strong>. Tiene limites minimos de pedido?</div></td>
                </tr>
              <tr>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">                  <select name="limite_min_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">No</option>
                    <option value="1">Si</option>
                  </select>
                </div></td>
                <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo147">Cuanto ? </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input name="cuanto_p" type="text" value="" size="40">
                </div></td>
              </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147"><strong>14</strong>. Cuentan con un proceso definido para preservar y manejar el material o equipo suministrado por el cliente ?</div></td>
                </tr>
              <tr>
                <td colspan="3" bordercolor="#DFDFFF" bgcolor="#FFFFFF"><div align="left">
                  <select name="proceso_p" onChange="calcular()">
                    <option value="0"></option>
                    <option value="5">Si</option>
                    <option value="1">No</option>
                  </select></div></td>
                </tr>
            </table></td>
		    </tr>     
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="justify" class="Estilo147"><strong>Nota</strong>: En algunos casos puede que su empresa no aplique a alguno de los items anteriores. Por ejemplo, si la pregunta hace referencia a un producto (tangible) y su empresa es de servicios, si es el caso por favor se&ntilde;ale la casilla <strong>NO</strong> de la columna <strong> No Aplica</strong></div></td>
            </tr> 
		  <?php
		  }
		  ?>      
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="730" border="1" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              <tr>
                <td width="116" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">REGISTRADO POR </div></td>
                <td width="233" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="encuestador_p" value="<?php echo $row_usuario_comercial['nombre_usuario']; ?>" size="30">
                </div></td>
                <td width="140" bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">CALIFICACION (%) </div></td>
                <td width="213" bordercolor="#DFDFFF" bgcolor="#ECF5FF">
                  <div align="left">
                    <input name="calificacion_p" type="text" size="20" readonly="true">
                    </div></td>
              </tr>
              <tr>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">CARGO</div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left">
                  <input type="text" name="cargo_enc_p" value="" size="30">
                </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo147">FECHA (aaaa-mm-dd) </div></td>
                <td bordercolor="#DFDFFF" bgcolor="#ECF5FF">
                  <div align="left">
                    <input type="text" name="fecha_diligencia_p" id="fecha_diligencia_p" value="<?php echo date("Y/m/d"); ?>" size="20">
                  </div></td>
              </tr>
              <tr>
                <td colspan="4" bordercolor="#DFDFFF" bgcolor="#FFFFFF">
                  <div align="center">
                    <input name="submit" type="submit" value="Insertar registro">
                    </div></td>
                </tr>
            </table></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
    </div></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="31" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="730" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="85" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left"><a href="menu.php"><img src="home.gif" alt="Menu Principal" width="21" height="22" border="0"></a></div></td>
        <td width="265" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo114 Estilo129">
          <div align="center"><a href="compras.php" class="Estilo18">Gesti&oacute;n Compras </a></div>
        </div></td>
        <td width="216" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo114 Estilo129"><a href="listado_proveedor.php" class="Estilo18">Listado Proveedores </a></div></td>
        <td width="141" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo46"><img src="firma3.bmp"></span></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_comercial);

mysql_free_result($ver_sub_menu);
?>
