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

  $insertSQL = sprintf("INSERT INTO referencia (cod_ref, n_egp_ref, nit_c_ref, n_cotiz_ref, tipo_bolsa_ref, material_ref, ancho_ref, largo_ref, solapa_ref, bolsillo_guia_ref, calibre_ref, peso_millar_ref, impresion_ref, nombre_arte_ref, num_pos_ref, cod_barras_formato_ref, adhesivo_ref, fecha_aprobacion_arte_ref) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['cod_ref'], "text"),
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
					   GetSQLValueString($_POST['fecha_aprobacion_arte_ref'], "date"));
                      

  mysql_select_db($database_conexion1, $conexion1);
   $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "referencias.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
.Estilo64 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo65 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo67 {font-size: 11px; font-family: Arial, Helvetica, sans-serif; }
.Estilo68 {color: #000066}
.Estilo73 {color: #000066; font-weight: bold; font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo74 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo76 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #990000; }
.Estilo77 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo78 {color: #FF0000}
.Estilo79 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
}
.Estilo81 {font-size: 12px; font-family: Verdana, Arial, Helvetica, sans-serif; color: #000066; font-weight: bold; }
.Estilo82 {font-weight: bold; font-size: 12px; color: #000066;}
.Estilo83 {color: #000066; font-family: Verdana, Arial, Helvetica, sans-serif; }
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
        if (isNaN(val)) errors+='- '+nm+' debe ser de contenido numerico.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' debe ser un intervalo entre '+min+' y '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' es obligatorio.\n'; }
  } if (errors) alert('Favor corregir los siguientes campos:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
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
//Carga la pagina para la validacion de la referencia
function consulta()
{
window.location ='referencia.php?cod_ref='+document.form1.cod_ref.value;
}
</script>
</head>

<body>
<table width="735" height="10" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" colspan="2" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td width="372" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><span class="Estilo64"><?php echo $row_usuario_referencia['nombre_usuario']; ?></span></td>
    <td width="309" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo65" >Cerrar Sesi&oacute;n</a> </div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td colspan="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo47">ADICIONAR REFERENCIA GENERICA </div></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
    <td height="23" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('cod_ref','','R','ancho_ref','','RisNum','largo_ref','','RisNum','solapa_ref','','RisNum','bolsillo_guia_ref','','RisNum','calibre_ref','','RisNum','peso_millar_ref','','RisNum','impresion_ref','','R','num_pos_ref','','R','cod_barras_formato_ref','','R');return document.MM_returnValue">
        <table width="735" align="center" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr valign="baseline">
            <td height="24" colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="justify" class="Estilo76">Por favor hacer cumplimiento de que solo puede ingresar las referencias genericas o las que son propias de la empresa AC&amp;Cia. </div></td>
            </tr>
          <tr valign="baseline">
            <td height="24" colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo74">
              <span class="Estilo73"><span class="Estilo77">*Referencia</span> </span>
              <?php 
				mysql_select_db($database_conexion1, $conexion1);
				$ref=$_GET['cod_ref'];
				if($ref != '')
				{				
				$sql2="SELECT * FROM referencia WHERE cod_ref='$ref'";
				$result2=mysql_query($sql2);
				$num_ref=mysql_num_rows($result2);				
				}
				?>
              <input type="text" name="cod_ref" onBlur="consulta()" value="<?php 
					if($num_ref == '' || $num_ref == '0')
					{
					echo $ref;
					}?>" size="20"> 
            </span><span class="Estilo79">
             - <?php if($num_ref == '' || $num_ref == '0')
					{
					echo 'El sistema valida la referencia';
					}
					else
					{
					echo 'LA REFERENCIA EXISTE !!!!';
					}?>
            </span></div></td>
            </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo77"><span class="Estilo73 Estilo77">*Cliente</span></span></div></td>
            <td colspan="3" align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left">
              <input name="nit_c_ref" type="text" id="nit_c_ref" value="Varios" size="10" readonly="true">
            </div></td>
            <tr valign="baseline">
            <td width="141" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo74">
              <div align="right"><span class="Estilo73 Estilo77">*Egp <span class="Estilo78">N&ordm;</span> </span></div>
            </div></td>
            <td width="158" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
              <label>
              <input name="n_egp_ref" type="text" id="n_egp_ref" value="0" size="10" readonly="true">
              </label>
            </div></td>
            <td width="194" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo68 Estilo79 Estilo114"><strong>Primera Cotizaci&oacute;n </strong></div></td>
            <td width="222" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left">
                <label>
                <input name="n_cotiz_ref" type="text" id="n_cotiz_ref" value="0" size="10" readonly="true">
                </label>
            </div></td>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo81">*Tipo de Bolsa</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left"><span class="Estilo74">
              <select name="tipo_bolsa_ref">
                <option value="N.A.">N.A.</option>
                <option value="Seguridad" >Seguridad</option>
                <option value="Currier" >Currier</option>
                            </select>
            </span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo81">*Material</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left"><span class="Estilo74">
              <select name="material_ref">
                <option value="N.A.">N.A.</option>
                <option value="Ldpe coestruido pigmentado" >Ldpe coestruido pigmentado</option>
                <option value="Ldpe coestruido sin pigmentos" >Ldpe coestruido sin pigmentos</option>
                <option value="Ldpe monocapa sin pigmentos" >Ldpe monocapa sin pigmentos</option>
                <option value="Ldpe monocapa pigmentado" >Ldpe monocapa pigmentado</option>
              </select>
            </span></div></td>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo73 Estilo77">*Ancho</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo74">
              <input type="text" name="ancho_ref" value="" size="10" onBlur="calcular()">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo77"><span class="Estilo82">*Impresion</span></div></td>
				
				<td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo74">
                  <input type="text" name="impresion_ref" value="" size="30">
                </div></td>				
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo73 Estilo77">*Largo</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo74">
              <input type="text" name="largo_ref" value="" size="10" onBlur="calcular()">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo77"><span class="Estilo82">Nombre del Arte</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo74">
              <input name="nombre_arte_ref" type="text" size="30" readonly="true">
            </div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo73 Estilo77">*Solapa</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo74">
              <input type="text" name="solapa_ref" value="" size="10" onBlur="calcular()">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo77"><span class="Estilo82">*Numeraci&oacute;n &amp; Posiciones</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo74">
              <input type="text" name="num_pos_ref" value="" size="30">
            </div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo73 Estilo77">*Bolsillo Porta Guia</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo74">
              <input type="text" name="bolsillo_guia_ref" value="" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo77"><span class="Estilo82">*Codigo Barras &amp; Formato</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo74">
              <label>
              <select name="cod_barras_formato_ref" id="cod_barras_formato_ref">
                <option value="N.A.">N.A.</option>
                <option value="EAN 128">EAN 128</option>
                            </select>
              </label>
            </div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right"><span class="Estilo73 Estilo77">*Calibre</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo74">
              <input type="text" name="calibre_ref" value="" size="10" onBlur="calcular()">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="right" class="Estilo77"><span class="Estilo82">*Tipo de Adhesivo</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="left" class="Estilo74">
              <select name="adhesivo_ref">
                <option value="N.A.">N.A.</option>
                <option value="Cinta de Seguridad">Cinta de Seguridad</option>
                <option value="HOT MELT" >HOT MELT</option>
                <option value="Cinta Permanente">Cinta Permanente</option>
                <option value="Cinta Resellable">Cinta Resellable</option>
                            </select>
            </div></td>
          </tr>
          <tr valign="baseline">
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo73 Estilo77">*Peso Millar</span></div></td>
            <td align="right" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo74">
              <input type="text" name="peso_millar_ref" value="" size="10">
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo77"><span class="Estilo82">Fecha de Aprobaci&oacute;n Arte</span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo74">
              <input type="text" name="fecha_aprobacion_arte_ref" value="" size="10" >
              <span class="Estilo68">aaaa-mm-dd</span></div></td>
          </tr>
          <tr valign="baseline">
            <td colspan="4" align="right" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center" class="Estilo74">
              <input type="submit" value="Insertar registro">
            </div></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
    </div></td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="31" colspan="2" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="727" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="127"><div align="left" class="Estilo67">
            <div align="center"><a href="menu.php" class="Estilo68"><img src="home.gif" alt="Menu Principal" width="23" height="24"></a></div>
        </div></td>
        <td width="226"><div align="center" class="Estilo67"><a href="referencias.php" class="Estilo83">Listado de Referencias </a></div></td>
        <td width="224"><div align="right" class="Estilo67">
            <div align="center"><a href="disenoydesarrollo.php" class="Estilo83">Dise&ntilde;o y Desarrollo </a></div>
        </div></td>
        <td width="127"><div align="right"><img src="firma3.bmp"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_referencia);
?>
