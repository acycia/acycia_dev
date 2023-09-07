<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE orden_produccion SET fecha_pedido=%s, fecha_entrega=%s, numero_orden_compra=%s, referencia_cliente=%s, referencia_interna=%s, cantidad=%s, precio_venta=%s, planchas_impresion=%s, referencia_nueva=%s, orden_produccion=%s, f_coextruccion=%s, f_impresion=%s, f_sellada=%s, f_despacho=%s, vendedor=%s, comision=%s, notas=%s, registradopor=%s, direccion_despacho=%s, cliente=%s WHERE id=%s",
                       GetSQLValueString($_POST['fecha_pedido'], "date"),
                       GetSQLValueString($_POST['fecha_entrega'], "date"),
                       GetSQLValueString($_POST['numero_orden_compra'], "text"),
                       GetSQLValueString($_POST['referencia_cliente'], "text"),
                       GetSQLValueString($_POST['referencia_interna'], "int"),
                       GetSQLValueString($_POST['cantidad'], "int"),
                       GetSQLValueString($_POST['precio_venta'], "text"),
                       GetSQLValueString($_POST['planchas_impresion'], "int"),
                       GetSQLValueString($_POST['referencia_nueva'], "int"),
                       GetSQLValueString($_POST['orden_produccion'], "text"),
                       GetSQLValueString($_POST['f_coextruccion'], "date"),
                       GetSQLValueString($_POST['f_impresion'], "date"),
                       GetSQLValueString($_POST['f_sellada'], "date"),
                       GetSQLValueString($_POST['f_despacho'], "date"),
                       GetSQLValueString($_POST['vendedor'], "int"),
                       GetSQLValueString($_POST['comision'], "text"),
                       GetSQLValueString($_POST['notas'], "text"),
                       GetSQLValueString($_POST['registradopor'], "int"),
                       GetSQLValueString($_POST['direccion_despacho'], "text"),
                       GetSQLValueString($_POST['cliente'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "ordenconsultar.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT id_ref, cod_ref, version_ref FROM referencia WHERE estado_ref = 1 ORDER BY cod_ref ASC";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedor = "SELECT id_usuario, nombre_usuario FROM usuario ORDER BY nombre_usuario ASC";
$vendedor = mysql_query($query_vendedor, $conexion1) or die(mysql_error());
$row_vendedor = mysql_fetch_assoc($vendedor);
$totalRows_vendedor = mysql_num_rows($vendedor);

$colname_usuarioactivo = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuarioactivo = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuarioactivo = sprintf("SELECT id_usuario FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuarioactivo, "text"));
$usuarioactivo = mysql_query($query_usuarioactivo, $conexion1) or die(mysql_error());
$row_usuarioactivo = mysql_fetch_assoc($usuarioactivo);
$totalRows_usuarioactivo = mysql_num_rows($usuarioactivo);

mysql_select_db($database_conexion1, $conexion1);
$query_clientes = "SELECT id_c, nombre_c FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

$colname_ordenproduccion = "-1";
if (isset($_GET['id'])) {
  $colname_ordenproduccion = $_GET['id'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_ordenproduccion = sprintf("SELECT * FROM orden_produccion WHERE id = %s", GetSQLValueString($colname_ordenproduccion, "int"));
$ordenproduccion = mysql_query($query_ordenproduccion, $conexion1) or die(mysql_error());
$row_ordenproduccion = mysql_fetch_assoc($ordenproduccion);
$totalRows_ordenproduccion = mysql_num_rows($ordenproduccion);
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/general.css" type="text/css">
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
</head>
<body oncontextmenu="return false">
  <table id="tabla_formato"><tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
       <li><?php echo $row_usuario['nombre_usuario']; ?></li>
           
       <li><a href="Ordenpedido.php" target="_top">ORDENES</a></li>
       <li><a href="menu.php" target="_top">MENU</a></li>
       <li><a href="<?php echo $logoutAction ?>" target="_top">SALIR</a></li>  
      </ul></div></div>
   </td></tr></table>
<p class="Estilo2">Editar Pedido</p>
<p>&nbsp;</p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center">
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Fecha Pedido:</td>
      <td><span id="sprytextfield1">
      <input type="text" name="fecha_pedido" value="<?php echo htmlentities($row_ordenproduccion['fecha_pedido'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10">
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
        aaaa-mm-dd</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Fecha Entrega:</td>
      <td><span id="sprytextfield2">
      <input type="text" name="fecha_entrega" value="<?php echo htmlentities($row_ordenproduccion['fecha_entrega'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10">
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
      aaaa-mm-dd</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Numero orden compra:</td>
      <td><input type="text" name="numero_orden_compra" value="<?php echo htmlentities($row_ordenproduccion['numero_orden_compra'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Referencia Cliente:</td>
      <td><input type="text" name="referencia_cliente" value="<?php echo htmlentities($row_ordenproduccion['referencia_cliente'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Referencia interna:</td>
      <td><select name="referencia_interna">
        <?php 
do {  
?>
        <option value="<?php echo $row_referencias['id_ref']?>" <?php if (!(strcmp($row_referencias['id_ref'], htmlentities($row_ordenproduccion['referencia_interna'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>><?php echo $row_referencias['cod_ref']?></option>
        <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
?>
      </select></td>
    <tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Cantidad:</td>
      <td><input type="text" name="cantidad" value="<?php echo htmlentities($row_ordenproduccion['cantidad'], ENT_COMPAT, 'iso-8859-1'); ?>" size="15"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Precio venta:</td>
      <td><input type="text" name="precio_venta" value="<?php echo htmlentities($row_ordenproduccion['precio_venta'], ENT_COMPAT, 'iso-8859-1'); ?>" size="15"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Planchas impresion:</td>
      <td valign="baseline"><input type="radio" name="planchas_impresion" value="1" <?php if (!(strcmp(htmlentities($row_ordenproduccion['planchas_impresion'], ENT_COMPAT, 'iso-8859-1'),1))) {echo "checked=\"checked\"";} ?>>
            si<input type="radio" name="planchas_impresion" value="0" <?php if (!(strcmp(htmlentities($row_ordenproduccion['planchas_impresion'], ENT_COMPAT, 'iso-8859-1'),0))) {echo "checked=\"checked\"";} ?>>
            no</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Referencia_nueva:</td>
      <td valign="baseline"><input type="radio" name="referencia_nueva" value="1" <?php if (!(strcmp(htmlentities($row_ordenproduccion['referencia_nueva'], ENT_COMPAT, 'iso-8859-1'),1))) {echo "checked=\"checked\"";} ?>>
            Si<input type="radio" name="referencia_nueva" value="0" <?php if (!(strcmp(htmlentities($row_ordenproduccion['referencia_nueva'], ENT_COMPAT, 'iso-8859-1'),0))) {echo "checked=\"checked\"";} ?>>
            No</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Orden produccion:</td>
      <td><input type="text" name="orden_produccion" value="<?php echo htmlentities($row_ordenproduccion['orden_produccion'], ENT_COMPAT, 'iso-8859-1'); ?>" size="15"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Fecha coextruccion:</td>
      <td><span id="sprytextfield3">
      <input type="text" name="f_coextruccion" value="<?php echo htmlentities($row_ordenproduccion['f_coextruccion'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
      aaaa-mm-dd</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Fecha impresion:</td>
      <td><span id="sprytextfield4">
      <input type="text" name="f_impresion" value="<?php echo htmlentities($row_ordenproduccion['f_impresion'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
      aaaa-mm-dd</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Fecha sellada:</td>
      <td><span id="sprytextfield5">
      <input type="text" name="f_sellada" value="<?php echo htmlentities($row_ordenproduccion['f_sellada'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
      aaaa-mm-dd</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Fecha despacho:</td>
      <td><span id="sprytextfield6">
      <input type="text" name="f_despacho" value="<?php echo htmlentities($row_ordenproduccion['f_despacho'], ENT_COMPAT, 'iso-8859-1'); ?>" size="10">
<span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
      aaaa-mm-dd</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Vendedor:</td>
      <td><select name="vendedor">
        <?php 
do {  
?>
        <option value="<?php echo $row_vendedor['id_usuario']?>" <?php if (!(strcmp($row_vendedor['id_usuario'], htmlentities($row_ordenproduccion['vendedor'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>><?php echo $row_vendedor['nombre_usuario']?></option>
        <?php
} while ($row_vendedor = mysql_fetch_assoc($vendedor));
?>
      </select></td>
    <tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Comision:</td>
      <td><input type="text" name="comision" value="<?php echo htmlentities($row_ordenproduccion['comision'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Notas:</td>
      <td><input type="text" name="notas" value="<?php echo htmlentities($row_ordenproduccion['notas'], ENT_COMPAT, 'iso-8859-1'); ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Registradopor:</td>
      <td><select name="registradopor">
        <?php 
do {  
?>
        <option value="<?php echo $row_usuario['id_usuario']?>" <?php if (!(strcmp($row_usuario['id_usuario'], htmlentities($row_ordenproduccion['registradopor'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>><?php echo $row_usuario['nombre_usuario']?></option>
        <?php
} while ($row_usuario = mysql_fetch_assoc($usuario));
?>
      </select></td>
    <tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Direccion_despacho:</td>
      <td><textarea name="direccion_despacho" cols="32" rows="4"><?php echo htmlentities($row_ordenproduccion['direccion_despacho'], ENT_COMPAT, 'iso-8859-1'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">Cliente:</td>
      <td><select name="cliente">
        <?php 
do {  
?>
        <option value="<?php echo $row_clientes['id_c']?>" <?php if (!(strcmp($row_clientes['id_c'], htmlentities($row_ordenproduccion['cliente'], ENT_COMPAT, 'iso-8859-1')))) {echo "SELECTED";} ?>><?php echo $row_clientes['nombre_c']?></option>
        <?php
} while ($row_clientes = mysql_fetch_assoc($clientes));
?>
      </select></td>
    <tr>
    <tr valign="baseline">
      <td align="right" nowrap class="Estilo1">&nbsp;</td>
      <td><input type="submit" value="Actualizar Orden"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
  <input type="hidden" name="id" value="<?php echo $row_ordenproduccion['id']; ?>">
</form>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "date", {format:"yyyy-mm-dd", validateOn:["blur"], useCharacterMasking:true});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"yyyy-mm-dd", validateOn:["blur"], useCharacterMasking:true});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "date", {format:"yyyy-mm-dd", validateOn:["blur"], isRequired:false, useCharacterMasking:true});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "date", {format:"yyyy-mm-dd", validateOn:["blur"], isRequired:false, useCharacterMasking:true});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "date", {format:"yyyy-mm-dd", validateOn:["blur"], isRequired:false, useCharacterMasking:true});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "date", {format:"yyyy-mm-dd", validateOn:["blur"], isRequired:false, useCharacterMasking:true});
</script>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencias);

mysql_free_result($vendedor);

mysql_free_result($usuarioactivo);

mysql_free_result($clientes);

mysql_free_result($ordenproduccion);
?>
