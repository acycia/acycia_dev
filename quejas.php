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
mysql_select_db($database_conexion1, $conexion1);
$query_ver_num_queja = "SELECT * FROM analisis_qr ORDER BY n_qr DESC";
$ver_num_queja = mysql_query($query_ver_num_queja, $conexion1) or die(mysql_error());
$row_ver_num_queja = mysql_fetch_assoc($ver_num_queja);
$totalRows_ver_num_queja = mysql_num_rows($ver_num_queja);
$num=$row_ver_num_queja['n_qr'];
$num=$num+1;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  $insertSQL = sprintf("INSERT INTO analisis_qr (n_qr, fecha_reclamo_qr, nit_c_qr, causas_qr, forma_qr, accion_correctiva_qr, accion_preventiva_qr) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_qr'], "int"),
					   GetSQLValueString($_POST['fecha_reclamo_qr'], "date"),
                       GetSQLValueString($_POST['nit_c_qr'], "text"),
                       GetSQLValueString($_POST['causas_qr'], "text"),
                       GetSQLValueString($_POST['forma_qr'], "text"),
                       GetSQLValueString($_POST['accion_correctiva_qr'], "text"),
                       GetSQLValueString($_POST['accion_preventiva_qr'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "quejas2.php?nit_c=" . $row_datos_cliente['nit_c'] . "&n_qr=" . $num . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_usuario_quejas = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_quejas = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_quejas = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_quejas);
$usuario_quejas = mysql_query($query_usuario_quejas, $conexion1) or die(mysql_error());
$row_usuario_quejas = mysql_fetch_assoc($usuario_quejas);
$totalRows_usuario_quejas = mysql_num_rows($usuario_quejas);

$colname_datos_cliente = "1";
if (isset($_GET['nit_c'])) {
  $colname_datos_cliente = (get_magic_quotes_gpc()) ? $_GET['nit_c'] : addslashes($_GET['nit_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_datos_cliente = sprintf("SELECT * FROM cliente WHERE nit_c = '%s'", $colname_datos_cliente);
$datos_cliente = mysql_query($query_datos_cliente, $conexion1) or die(mysql_error());
$row_datos_cliente = mysql_fetch_assoc($datos_cliente);
$totalRows_datos_cliente = mysql_num_rows($datos_cliente);


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo17 {
	font-size: 18px;
	font-weight: bold;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo40 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo41 {font-weight: bold; color: #000066; font-size: 12px;}
.Estilo44 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo56 {font-weight: bold; font-family: Arial, Helvetica, sans-serif; color: #000066; font-size: 12px; }
.Estilo58 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #000066;}
.Estilo59 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; }
.Estilo87 {font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
	color: #000066;
}
.Estilo91 {color: #000066}
.Estilo92 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
<script language="JavaScript" src="calendar2.js"></script>
<script language="JavaScript" type="text/JavaScript">
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
<table width="705" border="2" align="center" cellspacing="3">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="images/cabecera.jpg" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="729" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="357" height="19" class="Estilo58"><?php echo $row_usuario_quejas['nombre_usuario']; ?></td>
          <td width="359"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo59">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td colspan="2" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="center"><span class="Estilo17"> QUEJAS Y RECLAMOS </span></div></td>
      </tr>
      <tr>
        <td width="446" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="center" class="Estilo40"><span class="Estilo41">Codigo: A5-F03</span></div></td>
        <td width="438" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="center" class="Estilo40"><span class="Estilo41">Version: 0</span></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="101" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="725" border="0" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="122" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Nit. </div></td>
        <td width="231" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['nit_c']; ?></span></td>
        <td width="112" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Telefono</div></td>
        <td width="242" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['telefono_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Razon Social </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['nombre_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Fax</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['fax_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Tipo Cliente </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['tipo_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Ciudad</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['cod_ciudad_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Contacto Comercial </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['contacto_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Direcci&oacute;n</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['direccion_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Cargo</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['cargo_contacto_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo56">Celular</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo44"><?php echo $row_datos_cliente['celular_contacto_c']; ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('n_qr','','RisNum','fecha_reclamo_qr','','R');return document.MM_returnValue">
  <table width="724" border="2" align="center" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC">
            <td width="120" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#EFEFEF"><span class="Estilo56">*Queja o Reclamo N&ordm;</span></td>
            <td width="90" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#EFEFEF"><div align="left" class="Estilo56">
              <input name="n_qr" type="text" value="<?php echo $num;  ?>" size="10" readonly>
              <input name="nit_c_qr" type="hidden" value="<?php echo $row_datos_cliente['nit_c']; ?>">
            </div></td>
            <td width="145" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#EFEFEF"><span class="Estilo56">*Fecha</span></td>
            <td width="113" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#EFEFEF"><div align="left"><span class="Estilo56">
              <input name="fecha_reclamo_qr" type="text" id="fecha_reclamo_qr" size="10" value="<?php echo date("Y/m/d"); ?>">
            </span></div></td>
            <td width="133" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#EFEFEF"><span class="Estilo56">*Forma de Queja</span></td>
            <td width="107" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#EFEFEF"><div align="left"><span class="Estilo44">
              <select name="forma_qr">
                <option>.</option>
                <option value="Verbal">Verbal</option>
                <option value="Escrita">Escrita</option>
              </select>
            </span></div></td>
          </tr>
          <tr valign="baseline" bordercolor="#999999" bgcolor="#CCCCCC">
            <td colspan="6" align="right" nowrap bordercolor="#FFFFFF" bgcolor="#EFEFEF">
              <div align="center" class="Estilo44">
                <input type="submit" value="Insertar registro">
            </div></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>    </td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="136" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo92"><a href="comercial.php" class="Estilo91">Gesti&oacute;n Comercial</a></div></td>
        <td width="143" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo92"><a href="bus_queja.php" class="Estilo91">Busqueda</a></div></td>
        <td width="143" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo59"><a href="quejas_detalle.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo91">Quejas </a></div></td>
        <td width="147" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo59">
          <div align="center"><a href="listado_clientes.php" class="Estilo91">Listado Clientes</a></div>
        </div></td>
        <td width="129" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo87"><img src="firma3.bmp"></span></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<script language="JavaScript">
		<!-- // create calendar object(s) just after form tag closed
			 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
			 // note: you can have as many calendar objects as you need for your application
			var cal1 = new calendar2(document.forms['form1'].elements['fecha_reclamo_qr']);
			cal1.year_scroll = true;
			cal1.time_comp = false;
		//-->
</script>
</body>
</html>
<?php
mysql_free_result($usuario_quejas);

mysql_free_result($datos_cliente);

mysql_free_result($ver_num_queja);
?>
