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
$fecha1=$_POST['fecha_pm'];
$mes1=substr($fecha1,0,2);
$dia1=substr($fecha1,3,2);
$ano1=substr($fecha1,6,4);
$fecha1=$ano1."/".$mes1."/".$dia1;
  $insertSQL = sprintf("INSERT INTO plan_mejora (n_pm, nit_p_pm, plan_mejora_pm, responsable_pm, fecha_pm, cumplimiento_pm) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_pm'], "int"),
                       GetSQLValueString($_POST['nit_p_pm'], "text"),
                       GetSQLValueString($_POST['plan_mejora_pm'], "text"),
                       GetSQLValueString($_POST['responsable_pm'], "text"), 
					   GetSQLValueString($_POST['fecha_pm'], "date"),                     
                       GetSQLValueString($_POST['cumplimiento_pm'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "proveedor_mejoras.php?nit_p=" . $row_ver_proveedor['nit_p'] . "";
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

$colname_ver_proveedor = "-1";
if (isset($_GET['nit_p'])) {
  $colname_ver_proveedor = (get_magic_quotes_gpc()) ? $_GET['nit_p'] : addslashes($_GET['nit_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_proveedor = sprintf("SELECT * FROM proveedor WHERE nit_p = '%s'", $colname_ver_proveedor);
$ver_proveedor = mysql_query($query_ver_proveedor, $conexion1) or die(mysql_error());
$row_ver_proveedor = mysql_fetch_assoc($ver_proveedor);
$totalRows_ver_proveedor = mysql_num_rows($ver_proveedor);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_mejora = "SELECT * FROM plan_mejora ORDER BY n_pm DESC";
$ver_mejora = mysql_query($query_ver_mejora, $conexion1) or die(mysql_error());
$row_ver_mejora = mysql_fetch_assoc($ver_mejora);
$totalRows_ver_mejora = mysql_num_rows($ver_mejora);

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
.Estilo30 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo31 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo34 {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 18px;
	color: #000066;
}
.Estilo33 {color: #000066; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.Estilo46 {font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
	color: #000066;
}
.Estilo36 {color: #000066}
.Estilo47 {color: #FF0000}
.Estilo48 {font-size: 12px}
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
  } if (errors) alert('No inserto correctamente por que:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
</head>

<body>
<table width="735" height="50" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bordercolor="#FFFFFF" bgcolor="#1B3781">
    <td width="729" height="23"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td><table width="735" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="353"><div align="left" class="Estilo30"><?php echo $row_usuario_comercial['nombre_usuario']; ?></div></td>
        <td width="358"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo31">Cerrar Sesi&oacute;n</a></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td height="2" bgcolor="#ECF5FF"><div align="center" class="Estilo34">PLAN DE MEJORA </div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td height="5" bgcolor="#FFFFFF"><div align="center">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('n_pm','','RisNum','responsable_pm','','R','fecha_pm','','R','plan_mejora_pm','','R');return document.MM_returnValue">
        <table width="735" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr valign="baseline">
            <td width="93" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo33 Estilo47 Estilo48">N&ordm;</div></td>
            <td width="259" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
              <input name="n_pm" type="text" value="<?php
$num=$row_ver_mejora['n_pm']+1; 
 echo $num; ?>" size="10" readonly="true">
              <input name="nit_p_pm" type="hidden" value="<?php echo $row_ver_proveedor['nit_p']; ?>">
            </div></td>
            <td width="121" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo33">Plan de mejora</div></td>
            <td width="230" rowspan="4" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <textarea name="plan_mejora_pm" cols="25" rows="6"></textarea>
            </div>              
              <div align="left"></div>            <div align="left"></div></td>
          </tr>
          
          <tr valign="baseline">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo33">Responsable</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <input name="responsable_pm" type="text" value="<?php echo $row_usuario_comercial['nombre_usuario']; ?>" size="30" readonly="true">
            </div></td>
            <td rowspan="3" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF">&nbsp;</td>
            </tr>
          <tr valign="baseline">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo33">Fecha</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
                <input type="text" name="fecha_pm" value="<?php echo date("Y/m/d"); ?>" size="10" readonly="true">
            </div></td>
            </tr>
          <tr valign="baseline">
            <td align="right" nowrap bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo33">Cumplimiento</div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <select name="cumplimiento_pm">
                  <option value="N.A.">N.A.</option>
                  <option value="Si">Si</option>
                  <option value="No">No</option>
                  <option value="Algunas veces">Algunas veces</option>
                  </select>
            </div></td>
            </tr>
          
          <tr valign="baseline">
            <td height="26" colspan="4" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#FFFFFF">
              <div align="center">
                <input type="submit" value="Insertar registro">
              </div></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
    </div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td height="2"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="74"><div align="left"><a href="menu.php"><img src="home.gif" alt="Menu Principal" width="20" height="20" border="0"></a></div></td>
        <td width="144"><div align="right" class="Estilo31">
          <div align="center"><a href="compras.php" class="Estilo36">Gesti&oacute;n Compras</a> </div>
        </div></td>
        <td width="255"><div align="center" class="Estilo31"><a href="listado_proveedor.php" class="Estilo36">Listado Proveedores </a></div></td>
        <td width="148"><div align="center" class="Estilo31"><a href="proveedor_mejoras.php?nit_p=<?php echo $row_ver_proveedor['nit_p']; ?>" class="Estilo36">Listado  Mejoras</a></div></td>
        <td width="86"><div align="right"><span class="Estilo46"><img src="firma3.bmp"></span></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="JavaScript">
		<!-- // create calendar object(s) just after form tag closed
			 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
			 // note: you can have as many calendar objects as you need for your application
			var cal1 = new calendar2(document.forms['form1'].elements['fecha_pm']);
			cal1.year_scroll = true;
			cal1.time_comp = false;
		//-->
</script>
</body>
</html>
<?php
mysql_free_result($usuario_comercial);

mysql_free_result($ver_proveedor);

mysql_free_result($ver_mejora);

mysql_free_result($ver_sub_menu);
?>
