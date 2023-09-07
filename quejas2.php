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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf("INSERT INTO analisis_qr_detalle (n_qr, cod_ref_qr, descripcion_qr, seguimiento_qr, valor_provisionado_qr, valor_definitivo_qr) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_qr'], "int"),
                       GetSQLValueString($_POST['cod_ref_qr'], "text"),
                       GetSQLValueString($_POST['descripcion_qr'], "text"),
                       GetSQLValueString($_POST['seguimiento_qr'], "text"),
                       GetSQLValueString($_POST['valor_provisionado_qr'], "double"),
                       GetSQLValueString($_POST['valor_definitivo_qr'], "double"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "quejas2.php?nit_c=" . $row_datos_cliente['nit_c'] . "&n_qr=" . $row_ver_quejas['n_qr'] . "";
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
if (isset($_GET['n_qr'])) {
  $colname_ver_detalle_queja = (get_magic_quotes_gpc()) ? $_GET['n_qr'] : addslashes($_GET['n_qr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_detalle_queja = sprintf("SELECT * FROM analisis_qr_detalle WHERE n_qr = %s", $colname_ver_detalle_queja);
$ver_detalle_queja = mysql_query($query_ver_detalle_queja, $conexion1) or die(mysql_error());
$row_ver_detalle_queja = mysql_fetch_assoc($ver_detalle_queja);
$totalRows_ver_detalle_queja = mysql_num_rows($ver_detalle_queja);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_referencias = "SELECT * FROM referencia";
$ver_referencias = mysql_query($query_ver_referencias, $conexion1) or die(mysql_error());
$row_ver_referencias = mysql_fetch_assoc($ver_referencias);
$totalRows_ver_referencias = mysql_num_rows($ver_referencias);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo15 {font-weight: bold; font-family: "Agency FB", Algerian, Arial, "Arial Black", "Arial Narrow", "Arial Rounded MT Bold", "Arial Unicode MS", "Baskerville Old Face", "Bauhaus 93", "Bell MT", "Berlin Sans FB", "Berlin Sans FB Demi", "Bernard MT Condensed", "Blackadder ITC", "Bodoni MT", "Bodoni MT Black", "Bodoni MT Condensed", "Bodoni MT Poster Compressed", "Book Antiqua", "Bookman Old Style", "Century Gothic", "Comic Sans MS"; color: #000066;}
.Estilo25 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; }
.Estilo26 {font-size: 10px}
.Estilo41 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo41 {font-weight: bold; color: #000066; font-size: 12px;}
.Estilo49 {font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #000000; }
.Estilo52 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.Estilo54 {
	font-size: 18px;
	font-family: Arial, Helvetica, sans-serif;
	color: #000066;
	font-weight: bold;
}
.Estilo55 {
	font-size: 12px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000066;
}
.Estilo56 {color: #000066; font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo59 {font-weight: bold; font-family: Arial, Helvetica, sans-serif; color: #000066; font-size: 12px; }
.Estilo62 {font-size: 12}
.Estilo65 {font-weight: bold; font-family: Arial, Helvetica, sans-serif; color: #990000; font-size: 12px; }
.Estilo68 {font-size: 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }
.Estilo69 {color: #000066}
.Estilo87 {font-size: 10px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
	color: #000066;
}
-->
</style>
</head>

<body>
<table width="738" height="100" border="2" align="center" cellspacing="3">
  <tr bgcolor="#1B3781">
    <td height="23" bordercolor="#FFFFFF"><img src="images/cabecera.jpg" alt="" width="626" height="80"></td>
  </tr>
  <tr bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="728" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr>
          <td width="364" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><span class="Estilo55"><?php echo $row_usuario_quejas['nombre_usuario']; ?></span></td>
          <td width="351" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><div align="right"><a href="<?php echo $logoutAction ?>" class="Estilo56">Cerrar Sesi&oacute;n</a></div></td>
        </tr>
    </table></td>
  </tr>
  <tr bordercolor="#CCCCCC" bgcolor="#CCCCCC">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="727" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td colspan="2" bgcolor="#F4F4F4"><div align="center" class="Estilo54">QUEJAS Y RECLAMOS </div></td>
      </tr>
      <tr>
        <td width="442" bgcolor="#F4F4F4"><div align="center" class="Estilo41">Codigo: A5-F03</div></td>
        <td width="442" bgcolor="#F4F4F4"><div align="center" class="Estilo41">Version: 0</div></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#666666" bgcolor="#FFFFFF">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="725" border="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr bgcolor="#CCCCCC">
        <td width="116" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo15 Estilo52">Nit. </div></td>
        <td width="239" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['nit_c']; ?></span></td>
        <td width="112" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Telefono</div></td>
        <td width="240" bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['telefono_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Razon Social </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['nombre_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Fax</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['fax_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Tipo Cliente </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['tipo_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Ciudad</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['cod_ciudad_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Contacto Comercial </div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['contacto_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Direcci&oacute;n</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['direccion_c']; ?></span></td>
      </tr>
      <tr bgcolor="#CCCCCC">
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Cargo</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['cargo_contacto_c']; ?></span></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><div align="right" class="Estilo59">Celular</div></td>
        <td bordercolor="#FFFFFF" bgcolor="#F4F4F4"><span class="Estilo49"><?php echo $row_datos_cliente['celular_contacto_c']; ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr bordercolor="#FFFFFF" bgcolor="#FFFFFF">
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <table width="723" border="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
        <tr valign="baseline" bordercolor="#FFFFFF" bgcolor="#CCCCCC">
          <td width="137" align="right" nowrap bgcolor="#F2F2F2"><div align="right" class="Estilo59">Queja o Reclamo N&ordm; </div></td>
          <td width="121" bgcolor="#F2F2F2"><span class="Estilo62">
            <input name="n_qr" type="text" value="<?php echo $row_ver_quejas['n_qr']; ?>" size="10" readonly>
            <input name="nit_c_qr" type="hidden" value="<?php echo $row_ver_quejas['nit_c_qr']; ?>">
          </span></td>
          <td width="148" bgcolor="#F2F2F2"><div align="right" class="Estilo62">
            <div align="center"><span class="Estilo59">Fecha</span>
              <input name="fecha_reclamo_qr" type="text" value="<?php echo $row_ver_quejas['fecha_reclamo_qr']; ?>" size="10" readonly>
            </div>
          </div></td>
          <td width="194" bgcolor="#F2F2F2"><div align="right" class="Estilo62"><span class="Estilo59">Forma de Queja o Reclamo</span></div></td>
          <td width="101" bgcolor="#F2F2F2"><span class="Estilo62">
            <select name="forma_qr" disabled="disabled">
              <option value="" <?php if (!(strcmp("", $row_ver_quejas['forma_qr']))) {echo "selected=\"selected\"";} ?>>.</option>
              <option value="Verbal" <?php if (!(strcmp("Verbal", $row_ver_quejas['forma_qr']))) {echo "selected=\"selected\"";} ?>>Verbal</option>
              <option value="Escrita" <?php if (!(strcmp("Escrita", $row_ver_quejas['forma_qr']))) {echo "selected=\"selected\"";} ?>>Escrita</option>
            </select>
          </span></td>
        </tr>
      </table>
      <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
        <table width="99%" border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF" bgcolor="#FFFFFF" >
          <tr bordercolor="#FFFFFF" bgcolor="#999999">
            <td width="24%" bordercolor="#FFFFFF" bgcolor="#CCCCCC" class="Estilo15"><div align="center"><span class="Estilo25">
            REFERENCIA</span></div></td>
            <td width="76%" bordercolor="#FFFFFF" bgcolor="#CCCCCC" class="Estilo15"><div align="center"><span class="Estilo25">DESCRIPCION</span></div></td>
          </tr>
          <?php do { ?>
          <tr bordercolor="#CCCCCC" bgcolor="#FFFFFF">
            <td bordercolor="#FFFFFF" bgcolor="#EFEFEF"><div align="left" class="Estilo52">
              <div align="center"><?php echo $row_ver_detalle_queja['cod_ref_qr']; ?></div>
            </div></td>
            <td bordercolor="#FFFFFF" bgcolor="#EFEFEF"><span class="Estilo52"><?php echo $row_ver_detalle_queja['descripcion_qr']; ?></span></td>
          </tr>
          <?php } while ($row_ver_detalle_queja = mysql_fetch_assoc($ver_detalle_queja)); ?>
          <tr bordercolor="#999999" bgcolor="#FFFFFF">
            <td bordercolor="#FFFFFF" bgcolor="#EFEFEF"><div align="center">                <span class="Estilo25">
                <select name="cod_ref_qr" class="Estilo25" id="cod_ref_qr">
                  <option value="0"></option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_ver_referencias['cod_ref']?>"><?php echo $row_ver_referencias['cod_ref']?></option>
                  <?php
} while ($row_ver_referencias = mysql_fetch_assoc($ver_referencias));
  $rows = mysql_num_rows($ver_referencias);
  if($rows > 0) {
      mysql_data_seek($ver_referencias, 0);
	  $row_ver_referencias = mysql_fetch_assoc($ver_referencias);
  }
?>
                </select>
                <input name="n_qr" type="hidden" id="n_qr22" value="<?php echo $row_ver_quejas['n_qr']; ?>">
            </span></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#EFEFEF">
              <div align="left">
                  <textarea name="descripcion_qr" cols="65"></textarea>
            </div></td></tr>
          <tr bordercolor="#999999" bgcolor="#CCCCCC">
            <td height="26" bordercolor="#FFFFFF" bgcolor="#EFEFEF"><div align="center"><a href="quejas_detalle.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo65">TERMINAR</a></div></td>
            <td bordercolor="#FFFFFF" bgcolor="#EFEFEF"><div align="center"><span class="Estilo26">
              <input name="submit" type="submit" value="Insertar registro">
            </span></div></td>
          </tr>
        </table>
        <p>
          <input type="hidden" name="MM_insert" value="form2">
    </p>
      </form>    </td>
  </tr>
  
  <tr bordercolor="#FFFFFF" bgcolor="#999999">
    <td height="23" bordercolor="#FFFFFF" bgcolor="#FFFFFF"><table width="728" border="0" cellspacing="3" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
      <tr>
        <td width="158" height="21"><div align="center" class="Estilo68"><a href="comercial.php" class="Estilo69">Gesti&oacute;n Comercial</a></div></td>
        <td width="127"><div align="center" class="Estilo68"><a href="bus_queja.php" class="Estilo69">Busqueda</a></div></td>
        <td width="153"><div align="center" class="Estilo68"><a href="quejas_detalle.php?nit_c=<?php echo $row_datos_cliente['nit_c']; ?>" class="Estilo69">Quejas </a></div></td>
        <td width="140"><div align="center" class="Estilo68"><a href="listado_clientes.php" class="Estilo69">Listado Clientes</a></div></td>
        <td width="122"><div align="right"><span class="Estilo87"><img src="firma3.bmp"></span></div></td>
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

mysql_free_result($ver_referencias);
?>
