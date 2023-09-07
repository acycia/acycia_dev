<?php require_once('Connections/conexion1.php'); ?>
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
$fecha1=$_POST['fecha_msc'];
$mes1=substr($fecha1,0,2);
$dia1=substr($fecha1,3,2);
$ano1=substr($fecha1,6,4);
$fecha1=$ano1."/".$mes1."/".$dia1;
  $insertSQL = sprintf("INSERT INTO encuesta_msc (n_msc, nit_c_msc, fecha_msc, entrega_pedido_msc, documentacion_msc, servicio_msc, empaque_msc, agilidad_msc, respaldo_msc, servicio_comercial_msc, orientacion_msc, respuesta_msc, desarrollo_msc, innovaciones_msc, tamaños_msc, seguridad_msc, legibilidad_msc, resistencia_msc, fuerza_msc, empaque_solic_msc, entrega_msc, posicion_msc, suministro_msc, otros_suministros_msc, recomendaciones_msc) VALUES (%s, %s,'$fecha1', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_msc'], "int"),
                       GetSQLValueString($_POST['nit_c_msc'], "text"),
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
                       GetSQLValueString($_POST['recomendaciones_msc'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "listado_clientes.php";
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

$colname_ver_encuestas = "1";
if (isset($_GET['nit_c'])) {
  $colname_ver_encuestas = (get_magic_quotes_gpc()) ? $_GET['nit_c'] : addslashes($_GET['nit_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_encuestas = sprintf("SELECT * FROM encuesta_msc WHERE nit_c_msc = '%s' ORDER BY fecha_msc DESC", $colname_ver_encuestas);
$ver_encuestas = mysql_query($query_ver_encuestas, $conexion1) or die(mysql_error());
$row_ver_encuestas = mysql_fetch_assoc($ver_encuestas);
$totalRows_ver_encuestas = mysql_num_rows($ver_encuestas);
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
	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
	font-weight: bold;
	font-size: 12px;
}
.Estilo31 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo33 {color: #000066; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo35 {
	color: #000099;
	font-family: Geneva, Arial, Helvetica, sans-serif;
}
.Estilo36 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 18px;
}
.Estilo38 {color: #000099; font-family: Geneva, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo39 {font-size: 10px}
.Estilo52 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo53 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo67 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo58 {font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
	color: #000066;
}
.Estilo69 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000066; }
.Estilo71 {color: #FF0000}
-->
</style>
<script language="JavaScript" src="calendar2.js"></script>
</head>

<body>
<table width="735" height="50" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="26" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3">
        <tr>
          <td width="363"><div align="left"><span class="Estilo52"><?php echo $row_usuario_encuesta['nombre_usuario']; ?></span></div></td>
          <td width="359"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo53">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="2" bordercolor="#FFFFFF" bgcolor="#ECF5FF"><div align="center"><span class="Estilo33 Estilo36">LISTADO DE ENCUESTAS POR CLIENTE </span></div></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="2" bordercolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td width="115" bgcolor="#FFFFFF"><div align="right" class="Estilo15">Raz&oacute;n Social </div></td>
        <td width="235" bgcolor="#FFFFFF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['nombre_c']; ?></div></td>
        <td width="105" bgcolor="#FFFFFF"><div align="right" class="Estilo15">Tipo Cliente </div></td>
        <td width="246" bgcolor="#FFFFFF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['tipo_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Nit</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['nit_c']; ?></div></td>
        <td width="105" bgcolor="#ECF5FF"><div align="right" class="Estilo15">Telefono</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['telefono_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td height="17" bgcolor="#FFFFFF"><div align="right" class="Estilo15">Direccion</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['direccion_c']; ?></div></td>
        <td bgcolor="#FFFFFF"><div align="right" class="Estilo15">Fax</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['fax_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Contacto Comercial </div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['contacto_c']; ?></div></td>
        <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Pais</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['cod_pais_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bgcolor="#FFFFFF"><div align="right" class="Estilo15">Celular</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['celular_contacto_c']; ?></div></td>
        <td bgcolor="#FFFFFF"><div align="right" class="Estilo15">Provincia</div></td>
        <td bgcolor="#FFFFFF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['cod_dpto_c']; ?></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#CCCCCC">
        <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Email</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['email_comercial_c']; ?></div></td>
        <td bgcolor="#ECF5FF"><div align="right" class="Estilo15">Ciudad</div></td>
        <td bgcolor="#ECF5FF"><div align="left" class="Estilo69"><?php echo $row_datos_cliente['cod_ciudad_c']; ?></div></td>
      </tr>
    </table>      </td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="2" bordercolor="#FFFFFF"><table width="735" border="0" align="center" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="184" height="34"><div align="center" class="Estilo53"><a href="comercial.php" class="Estilo14">Gesti&oacute;n Comercial</a></div></td>
        <td width="169"><div align="right" class="Estilo53">
          <div align="center"><a href="listado_clientes.php" class="Estilo14">Listado Clientes</a></div>
        </div></td>
        <td width="125"><div align="center" class="Estilo53"><a href="bus_encuesta.php" class="Estilo14">Busqueda</a></div></td>
        <td width="143"><div align="center" class="Estilo53"><a href="encuesta.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo71">*Nueva Encuesta</a></div></td>
        <td width="86"><div align="right"><span class="Estilo58"><img src="firma3.bmp"></span></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td height="2"><table width="735" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC" bgcolor="#CCCCCC">
      <tr bgcolor="#CCCCCC" class="Estilo38">
        <td width="154" bordercolor="#CCCCCC" bgcolor="#ECF5FF"><div align="center" class="Estilo67"><span class="Estilo14">FECHA ENCUESTA </span></div></td>
        <td width="202" bordercolor="#CCCCCC" bgcolor="#ECF5FF"><div align="center" class="Estilo67"><span class="Estilo14">NUMERO DE ENCUESTA </span></div></td>
        <td width="210" bordercolor="#CCCCCC" bgcolor="#ECF5FF"><div align="center" class="Estilo67"><span class="Estilo14">PUNTAJE DE SATISFACI&Oacute;N</span></div></td>
        <td width="30" bordercolor="#CCCCCC" bgcolor="#ECF5FF" class="Estilo35 Estilo39 Estilo14 Estilo12"><div align="center" class="Estilo15">VER</div></td>
        <td width="58" bordercolor="#CCCCCC" bgcolor="#ECF5FF" class="Estilo35 Estilo39 Estilo14 Estilo12"><div align="center" class="Estilo15">IMPRIMIR</div></td>
        <td width="54" bordercolor="#CCCCCC" bgcolor="#ECF5FF" class="Estilo35 Estilo39 Estilo14 Estilo12"><div align="center" class="Estilo15">ELIMINAR</div></td>
      </tr>
      <?php 
	  $i=0;
	  ?>
      <?php do { ?>
        <tr <?php if ($i%2==0) {?> style="background:#FFFFFF" <?php }else {?>style="background:#ECF5FF" <?php } 
	  $i++;		  ?>>
          <td><div align="center" class="Estilo67"><?php echo $row_ver_encuestas['fecha_msc']; ?></div></td>
          <td><div align="center" class="Estilo67"><?php echo $row_ver_encuestas['n_msc']; ?></div></td>
          <td><div align="center" class="Estilo67"><?php echo $row_ver_encuestas['puntaje_msc']; ?></div></td>
          <td><div align="center" class="Estilo67">
            <div align="center"><a href="ver_encuesta.php?n_msc=<?php echo $row_ver_encuestas['n_msc']; ?>&nit_c=<?php echo $row_ver_encuestas['nit_c_msc']; ?>"><img src="hoja.gif" width="18" height="18" border="0"></a></div>
          </div></td>
          <td><div align="center" class="Estilo67"><a href="imprimir_encuesta.php?n_msc=<?php echo $row_ver_encuestas['n_msc']; ?>&nit_c=<?php echo $row_ver_encuestas['nit_c_msc']; ?>" target="new"><img src="impresor.gif" width="18" height="18" border="0"></a></div></td>
          <td><div align="center" class="Estilo67"><a href="borrado_encuesta.php?n_msc=<?php echo $row_ver_encuestas['n_msc']; ?>&nit_c=<?php echo $row_ver_encuestas['nit_c_msc']; ?>"><img src="eliminar.gif" width="18" height="18" border="0"></a></div></td>
      </tr>
      <?php } while ($row_ver_encuestas = mysql_fetch_assoc($ver_encuestas)); ?>
    </table>    </td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($usuario_encuesta);

mysql_free_result($datos_cliente);

mysql_free_result($n_encuesta);

mysql_free_result($ver_nuevo);

mysql_free_result($ver_encuestas);
?>
