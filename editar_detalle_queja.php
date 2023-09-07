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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE analisis_qr_detalle SET n_qr=%s, cod_ref_qr=%s, descripcion_qr=%s, seguimiento_qr=%s, valor_provisionado_qr=%s, valor_definitivo_qr=%s WHERE id_det_qr=%s",
                       GetSQLValueString($_POST['n_qr'], "int"),
                       GetSQLValueString($_POST['cod_ref_qr'], "text"),
                       GetSQLValueString($_POST['descripcion_qr'], "text"),
                       GetSQLValueString($_POST['seguimiento_qr'], "text"),
                       GetSQLValueString($_POST['valor_provisionado_qr'], "double"),
                       GetSQLValueString($_POST['valor_definitivo_qr'], "double"),
                       GetSQLValueString($_POST['id_det_qr'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "ver_queja.php?nit_c=" . $row_datos_cliente['nit_c'] . "&n_qr=" . $row_ver_quejas['n_qr'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_ver_quejas = "1";
if (isset($_GET['n_qr'])) {
  $colname_ver_quejas = (get_magic_quotes_gpc()) ? $_GET['n_qr'] : addslashes($_GET['n_qr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_quejas = sprintf("SELECT * FROM analisis_qr WHERE n_qr = %s", $colname_ver_quejas);
$ver_quejas = mysql_query($query_ver_quejas, $conexion1) or die(mysql_error());
$row_ver_quejas = mysql_fetch_assoc($ver_quejas);
$totalRows_ver_quejas = mysql_num_rows($ver_quejas);

$colname_ver_detalle_queja = "1";
if (isset($_GET['id_det_qr'])) {
  $colname_ver_detalle_queja = (get_magic_quotes_gpc()) ? $_GET['id_det_qr'] : addslashes($_GET['id_det_qr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_detalle_queja = sprintf("SELECT * FROM analisis_qr_detalle WHERE id_det_qr = %s", $colname_ver_detalle_queja);
$ver_detalle_queja = mysql_query($query_ver_detalle_queja, $conexion1) or die(mysql_error());
$row_ver_detalle_queja = mysql_fetch_assoc($ver_detalle_queja);
$totalRows_ver_detalle_queja = mysql_num_rows($ver_detalle_queja);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo15 {font-weight: bold; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; color: #000066;}
.Estilo25 {color: #000066}
.Estilo47 {font-size: 12px}
.Estilo52 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; }
.Estilo56 {font-family: Arial, Helvetica, sans-serif}
.Estilo57 {color: #000066; font-weight: bold;}
.Estilo45 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo46 {font-size: 18px}
.Estilo58 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo59 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo61 {font-weight: bold; font-family: Arial, Helvetica, sans-serif; color: #000066; font-size: 12px; }
.Estilo87 {font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
	color: #000066;
}
.Estilo88 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
-->
</style>
</head>

<body>
<table width="731" height="100" border="2" align="center" cellspacing="3">
  <tr bgcolor="#CCCCCC">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#1B3781"><img src="logo_acyc.gif" width="101" height="80"><img src="index_r1_c2.gif" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="725" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="356"><span class="Estilo58"><?php echo $row_usuario_quejas['nombre_usuario']; ?></span></td>
          <td width="356"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo59">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="723" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bordercolor="#FFFFFF" bgcolor="#999999">
        <td colspan="2" bgcolor="#F4F4F4"><div align="center" class="Estilo45 Estilo46 Estilo25"><strong><span class="Estilo17"> COSTO DE QUEJAS Y RECLAMOS </span></strong></div></td>
      </tr>
      <tr bordercolor="#FFFFFF" bgcolor="#999999">
        <td width="446" bgcolor="#F4F4F4"><div align="center" class="Estilo45 Estilo40 Estilo25 Estilo47"><strong><span class="Estilo41">Codigo: A5-F03</span></strong></div></td>
        <td width="438" bgcolor="#F4F4F4"><div align="center" class="Estilo45 Estilo40 Estilo25 Estilo47"><strong><span class="Estilo41">Version: 0</span></strong></div></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#CCCCCC" bgcolor="#CCCCCC">
    <td height="106" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="723" border="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="117" bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Nit. </div></td>
        <td width="235" bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['nit_c']; ?></span></td>
        <td width="107" bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Telefono</div></td>
        <td width="246" bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['telefono_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Razon Social </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['nombre_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Fax</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['fax_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Tipo Cliente </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['tipo_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Ciudad</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['cod_ciudad_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Contacto Comercial </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['contacto_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Direcci&oacute;n</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['direccion_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td height="22" bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Cargo</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['cargo_contacto_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><div align="right" class="Estilo61">Celular</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F3F3F3"><span class="Estilo52"><?php echo $row_datos_cliente['celular_contacto_c']; ?></span></td>
      </tr>
    </table>
    <div align="center"></div></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="50" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <div align="left">
        <table width="723" border="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
          <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
            <td width="116" align="right" nowrap bgcolor="#F0F0F0"><div align="right" class="Estilo52"><span class="Estilo57">Queja  N&ordm;</span></div></td>
            <td width="86" bgcolor="#F0F0F0"><div align="left" class="Estilo52">
                <input name="n_qr" type="text" value="<?php echo $row_ver_quejas['n_qr']; ?>" size="10" readonly="true">
                <input name="nit_c_qr" type="hidden" value="<?php echo $row_ver_quejas['nit_c_qr']; ?>">
            </div></td>
            <td width="146" bgcolor="#F0F0F0"><div align="right" class="Estilo52"><span class="Estilo57">Fecha</span></div></td>
            <td width="108" bgcolor="#F0F0F0"><div align="left" class="Estilo52">
                <input name="fecha_reclamo_qr" type="text" value="<?php echo $row_ver_quejas['fecha_reclamo_qr']; ?>" size="10" readonly="true">
            </div></td>
            <td width="102" bgcolor="#F0F0F0"><div align="right" class="Estilo52"><span class="Estilo57">Forma de Queja</span></div></td>
            <td width="139" bgcolor="#F0F0F0"><span class="Estilo52">
              <label>
              <input name="forma_qr" type="text" id="forma_qr" value="<?php echo $row_ver_quejas['forma_qr']; ?>" size="15" readonly="true">              
              </label>
            </span></td>
          </tr>
        </table>
      </div>
          <form action="<?php echo $editFormAction; ?>" method="POST" name="form1">

            <div align="left">
              <table width="720" border="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
                <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                  <td width="115" align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="right"><span class="Estilo56 Estilo25 Estilo47"><strong>Referencia</strong></span></div></td>
                <td width="595" align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="left" class="Estilo52">
                  <input name="cod_ref_qr" type="text" value="<?php echo $row_ver_detalle_queja['cod_ref_qr']; ?>" size="20" readonly="true">
                  <input name="id_det_qr" type="hidden" id="id_det_qr2" value="<?php echo $row_ver_detalle_queja['id_det_qr']; ?>">
                  <input name="n_qr" type="hidden" id="n_qr" value="<?php echo $row_ver_detalle_queja['n_qr']; ?>">
                  </div></td>
              </tr>
                <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                  <td align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><span class="Estilo15 Estilo47 Estilo56">Descripci&oacute;n</span></td>
                <td rowspan="2" align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="left" class="Estilo52">
                  <textarea name="descripcion_qr" cols="60" rows="4"><?php echo $row_ver_detalle_queja['descripcion_qr']; ?></textarea>
                  </div></td>
              </tr>
                <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                  <td align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0">&nbsp;</td>
              </tr>
                <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                  <td align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="right" class="Estilo52"><span class="Estilo57">Seguimiento
                      
                  </span></div></td>
                <td rowspan="2" align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="left" class="Estilo52"><span class="Estilo57">
                  <textarea name="seguimiento_qr" cols="60" rows="4"><?php echo $row_ver_detalle_queja['seguimiento_qr']; ?></textarea>
                  </span></div></td>
              </tr>
                <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                  <td height="21" align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0">&nbsp;</td>
              </tr>
                <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                  <td align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><span class="Estilo15 Estilo47 Estilo56">Valor Provisionado</span></td>
                <td align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="left" class="Estilo52"><span class="Estilo57">
                  <input type="text" name="valor_provisionado_qr" value="<?php echo $row_ver_detalle_queja['valor_provisionado_qr']; ?>" size="20">
                  </span></div></td>
              </tr>
                <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                  <td align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="right" class="Estilo52"><span class="Estilo57">Valor Definitivo
                  </span></div></td>
                <td align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="left" class="Estilo52"><span class="Estilo57">
                  <input type="text" name="valor_definitivo_qr" value="<?php echo $row_ver_detalle_queja['valor_definitivo_qr']; ?>" size="20">
                  </span></div></td>
              </tr>
                <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
                  <td height="26" colspan="2" align="right" nowrap bordercolor="#999999" bgcolor="#F0F0F0"><div align="center" class="Estilo52">
                    <input name="submit" type="submit" value="Actualizar registro">
                  </div></td>
                </tr>
              </table>
              <input type="hidden" name="MM_update" value="form1">
            </div>
    </form>    </td></tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="44" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="726" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="167" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="left" class="Estilo88" >
            <div align="center"><a href="comercial.php" class="Estilo25">Gesti&oacute;n Comercial</a></div>
        </div></td>
        <td width="164" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo88"><a href="javascript:history.back();" class="Estilo25">Volver a la Queja </a></div></td>
        <td width="136" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="center" class="Estilo88"><a href="quejas_detalle.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo25">Quejas</a></div></td>
        <td width="133" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right" class="Estilo88">
          <div align="center"><a href="listado_clientes.php" class="Estilo25">Listado Clientes</a></div>
        </div></td>
        <td width="133" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><span class="Estilo87"><img src="firma3.bmp"></span></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario_quejas);

mysql_free_result($datos_cliente);

mysql_free_result($ver_quejas);

mysql_free_result($ver_detalle_queja);
?>
