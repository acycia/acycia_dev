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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO orden_produccion (fecha_pedido, fecha_entrega, numero_orden_compra, cliente, referencia_cliente, referencia_interna, cantidad, precio_venta, planchas_impresion, referencia_nueva, vendedor, comision, notas, registradopor, direccion_despacho) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fecha_pedido'], "date"),
                       GetSQLValueString($_POST['fecha_entrega'], "date"),
                       GetSQLValueString($_POST['numero_orden_compra'], "text"),
					   GetSQLValueString($_POST['cliente'], "int"),
                       GetSQLValueString($_POST['referencia_cliente'], "text"),
                       GetSQLValueString($_POST['referencia_interna'], "int"),
                       GetSQLValueString($_POST['cantidad'], "int"),
                       GetSQLValueString($_POST['precio_venta'], "text"),
                       GetSQLValueString($_POST['planchas_impresion'], "int"),
                       GetSQLValueString($_POST['referencia_nueva'], "int"),
                       GetSQLValueString($_POST['vendedor'], "int"),
                       GetSQLValueString($_POST['comision'], "text"),
                       GetSQLValueString($_POST['notas'], "text"),
                       GetSQLValueString($_POST['registradopor'], "int"),
                       GetSQLValueString($_POST['direccion_despacho'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "ordenconsultar.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/general.css" type="text/css">
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationRadio.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationRadio.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css">
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
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <p class="Estilo1">Registrar nueva orden de compra</p>
    <table align="center" >
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Fecha pedido:</td>
        <td><span id="sprytextfield1">
        <input type="text" name="fecha_pedido" value="<?php echo date('Y-m') ?>-" size="10">
        <span class="textfieldRequiredMsg">Valor requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span></span>aaaa-mm-dd</td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Fecha entrega:</td>
        <td><span id="sprytextfield2">
        <input type="text" name="fecha_entrega" value="<?php echo date('Y-m') ?>-" size="10">
        <span class="textfieldRequiredMsg">Valor requerido.</span><span class="textfieldInvalidFormatMsg">Formato invalido.</span></span>aaaa-mm-dd</td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Numero orden compra:</td>
        <td><span id="sprytextfield3">
          <input type="text" name="numero_orden_compra" value="" size="32">
        <span class="textfieldRequiredMsg">Valor requerido.</span></span></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Cliente</td>
        <td><span id="spryselect3">
          <label for="cliente"></label>
          <select name="cliente" id="cliente">
            <option value="-1">Seleccione el cliente</option>
            <?php
do {  
?>
            <option value="<?php echo $row_clientes['id_c']?>"><?php echo $row_clientes['nombre_c']?></option>
            <?php
} while ($row_clientes = mysql_fetch_assoc($clientes));
  $rows = mysql_num_rows($clientes);
  if($rows > 0) {
      mysql_data_seek($clientes, 0);
	  $row_clientes = mysql_fetch_assoc($clientes);
  }
?>
          </select>
          <span class="selectRequiredMsg">Por favor seleccione un cliente.</span><span class="selectInvalidMsg">Por favor seleccione un cliente.</span></span></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Referencia cliente:</td>
        <td><input type="text" name="referencia_cliente" value="" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Referencia_interna:</td>
        <td><span id="spryselect1">
          <select name="referencia_interna">
            <option value="-1" >Seleccione un valor</option>
            <option value="0" >sin referencia</option>
            <?php
do {  
?>
            <option value="<?php echo $row_referencias['id_ref']?>"><?php echo $row_referencias['cod_ref']?>-<?php echo $row_referencias['version_ref']?></option>
            <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
          </select>
        <span class="selectInvalidMsg">Por favor seleccione la referencia</span></span></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Cantidad:</td>
        <td><span id="sprytextfield4">
          <input type="text" name="cantidad" value="" size="32">
        <span class="textfieldRequiredMsg">Valor requerido</span></span></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Precio venta:</td>
        <td><span id="sprytextfield5">
          <input type="text" name="precio_venta" value="" size="32">
        <span class="textfieldRequiredMsg">Valor requerido</span></span></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Planchas_impresion:</td>
        <td valign="baseline"><span id="spryradio1">
          <label>
            <input type="radio" name="planchas_impresion" value="1" id="planchas_impresion_0">
            si</label>
          
          <label>
            <input type="radio" name="planchas_impresion" value="0" id="planchas_impresion_1">
            no</label>
          <br>
        <span class="radioRequiredMsg">Por favor hacer una selecci&oacute;n</span></span></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Referencia_nueva:</td>
        <td valign="baseline"><span id="spryradio2">
          <label>
            <input type="radio" name="referencia_nueva" value="1" id="referencia_nueva_0">
            si</label>
          
          <label>
            <input type="radio" name="referencia_nueva" value="0" id="referencia_nueva_1">
            no</label>
          <br>
        <span class="radioRequiredMsg">Por favor hacer una selecci&oacute;n</span></span></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Vendedor:</td>
        <td><span id="spryselect2">
          <select name="vendedor">
            <option value="-1" >Seleccione un asesor comercial</option>
            <?php
do {  
?>
            <option value="<?php echo $row_vendedor['id_usuario']?>"><?php echo $row_vendedor['nombre_usuario']?></option>
            <?php
} while ($row_vendedor = mysql_fetch_assoc($vendedor));
  $rows = mysql_num_rows($vendedor);
  if($rows > 0) {
      mysql_data_seek($vendedor, 0);
	  $row_vendedor = mysql_fetch_assoc($vendedor);
  }
?>
          </select>
        <span class="selectInvalidMsg">Seleccione uno</span><span class="selectRequiredMsg">Please select an item.</span></span></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Comision:</td>
        <td><input type="text" name="comision" value="" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Notas:</td>
        <td><input type="text" name="notas" value="" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">Direccion despacho:</td>
        <td><textarea name="direccion_despacho" cols="32" rows="4"></textarea></td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap class="Estilo1">&nbsp;</td>
        <td><input type="submit" value="Registrar Orden"></td>
      </tr>
    </table>
    <input name="registradopor" type="hidden" value="<?php echo $row_usuarioactivo['id_usuario']; ?>">
    <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "date", {validateOn:["blur"], format:"yyyy-mm-dd", useCharacterMasking:true});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "date", {format:"yyyy-mm-dd", validateOn:["blur"], useCharacterMasking:true});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["blur"]});
var spryradio1 = new Spry.Widget.ValidationRadio("spryradio1");
var spryradio2 = new Spry.Widget.ValidationRadio("spryradio2");
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {isRequired:false, invalidValue:"-1", validateOn:["blur"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2", {invalidValue:"-1", validateOn:["blur"]});
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3", {invalidValue:"-1", validateOn:["blur"]});
</script>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencias);

mysql_free_result($vendedor);

mysql_free_result($usuarioactivo);

mysql_free_result($clientes);
?>
