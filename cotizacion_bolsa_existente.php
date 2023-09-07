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
  $updateSQL = sprintf("UPDATE referencia SET cod_ref=%s, version_ref=%s, n_egp_ref=%s, n_cotiz_ref=%s, tipo_bolsa_ref=%s, material_ref=%s, ancho_ref=%s, largo_ref=%s, solapa_ref=%s, bolsillo_guia_ref=%s, calibre_ref=%s, peso_millar_ref=%s, impresion_ref=%s, num_pos_ref=%s, cod_form_ref=%s, adhesivo_ref=%s, estado_ref=%s, registro1_ref=%s, fecha_registro1_ref=%s, registro2_ref=%s, fecha_registro2_ref=%s WHERE id_ref=%s",
                       GetSQLValueString($_POST['cod_ref'], "text"),
                       GetSQLValueString($_POST['version_ref'], "int"),
                       GetSQLValueString($_POST['n_egp_ref'], "int"),
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
                       GetSQLValueString($_POST['num_pos_ref'], "text"),
                       GetSQLValueString($_POST['cod_form_ref'], "text"),
                       GetSQLValueString($_POST['adhesivo_ref'], "text"),
                       GetSQLValueString($_POST['estado_ref'], "int"),
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
                       GetSQLValueString($_POST['registro2_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro2_ref'], "date"),
                       GetSQLValueString($_POST['id_ref'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "cotizacion_bolsa_existente.php?n_cotiz=" . $_POST['n_cotiz'] . "&id_ref=" . $_POST['ref'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
$fecha_cotiz=$_POST['fecha_cotiz'];
$fecha= date("Y-m-d");
$hora= date("g:i a");
$n_cotiz=$_POST['n_cotiz_ce'];
$registro=$_POST['registro'];
if($fecha != $fecha_cotiz)
{
$sql2="UPDATE cotizacion SET fecha_modif='$fecha', hora_modif='$hora', responsable_modif='$registro' WHERE n_cotiz='$n_cotiz'";
}

  $insertSQL = sprintf("INSERT INTO cotizacion_existente (n_ce, n_cotiz_ce, id_ref_ce, cant_min_ce, tiempo_entrega_ce, incoterm_ce, precio_venta_ce, moneda_ce, unidad_ce, forma_pago_ce, entrega_ce, costo_cirel_ce, vendedor, comision) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_ce'], "int"),
                       GetSQLValueString($_POST['n_cotiz_ce'], "int"),
                       GetSQLValueString($_POST['id_ref_ce'], "int"),
                       GetSQLValueString($_POST['cant_min_ce'], "text"),
                       GetSQLValueString($_POST['tiempo_entrega_ce'], "text"),
                       GetSQLValueString($_POST['incoterm_ce'], "text"),
                       GetSQLValueString($_POST['precio_venta_ce'], "text"),
                       GetSQLValueString($_POST['moneda_ce'], "text"),
                       GetSQLValueString($_POST['unidad_ce'], "text"),
                       GetSQLValueString($_POST['forma_pago_ce'], "text"),
                       GetSQLValueString($_POST['entrega_ce'], "text"),
                       GetSQLValueString($_POST['costo_cirel_ce'], "text"),
                       GetSQLValueString($_POST['vendedor'], "int"),
                       GetSQLValueString($_POST['comision'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $result2=mysql_query($sql2);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "cotizacion_bolsa_edit.php?n_cotiz=" . $_POST['n_cotiz_ce'] . "&id_c_cotiz=" . $_POST['id_c_cotiz'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_cotizacion = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_cotizacion = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion = sprintf("SELECT * FROM cotizacion WHERE n_cotiz = %s", $colname_cotizacion);
$cotizacion = mysql_query($query_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);
$totalRows_cotizacion = mysql_num_rows($cotizacion);

$colname_referencia_edit = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_edit = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_edit = sprintf("SELECT * FROM referencia WHERE id_ref = %s", $colname_referencia_edit);
$referencia_edit = mysql_query($query_referencia_edit, $conexion1) or die(mysql_error());
$row_referencia_edit = mysql_fetch_assoc($referencia_edit);
$totalRows_referencia_edit = mysql_num_rows($referencia_edit);

$colname1_referencias = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname1_referencias = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
$colname_referencias = "-1";
if (isset($_GET['id_c'])) {
  $colname_referencias = (get_magic_quotes_gpc()) ? $_GET['id_c'] : addslashes($_GET['id_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencias = sprintf("SELECT * FROM ref_cliente, referencia WHERE ref_cliente.id_c = %s AND ref_cliente.id_ref = referencia.id_ref AND ref_cliente.id_ref NOT IN(SELECT cotizacion_existente.id_ref_ce FROM cotizacion_existente WHERE cotizacion_existente.n_cotiz_ce = %s)", $colname_referencias,$colname1_referencias);
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

mysql_select_db($database_conexion1, $conexion1);
$query_clientes = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

mysql_select_db($database_conexion1, $conexion1);
$query_ce_num = "SELECT * FROM cotizacion_existente ORDER BY n_ce DESC";
$ce_num = mysql_query($query_ce_num, $conexion1) or die(mysql_error());
$row_ce_num = mysql_fetch_assoc($ce_num);
$totalRows_ce_num = mysql_num_rows($ce_num);

mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr><td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
 <td id="cabezamenu">
 <ul id="menuhorizontal">
 <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>     
 <li><a href="menu.php">MENU PRINCIPAL</a></li>           
 <li><a href="comercial.php">GESTION COMERCIAL</a></li>
 </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
	<table id="tabla2">
<tr id="tr1">
  <td id="titulo3">COTIZACION N&deg; <?php echo $row_cotizacion['n_cotiz']; ?></td>
  <td id="titulo3"><a href="cotizacion_bolsa_edit.php?n_cotiz=<?php echo $row_cotizacion['n_cotiz']; ?>&id_c_cotiz=<?php echo $row_cotizacion['id_c_cotiz']; ?>"><img src="images/menos.gif" alt="COTIZACION ACTUAL" border="0" style="cursor:hand;"/></a><a href="cotizacion_bolsa.php"><img src="images/cat.gif" alt="COTIZACIONES" border="0" style="cursor:hand;"/></a><a href="cotizacion_menu.php"><img src="images/opciones.gif" alt="MENU COTIZACION" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a></td>
</tr>
<tr><td id="detalle2">
<div align="center">
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
      <table id="tabla6">        
        <tr>
          <td id="fuente2"><input name="n_cotiz" type="hidden" id="n_cotiz" value="<?php echo $_GET['n_cotiz']; ?>"><strong>REFERENCIA</strong><input name="id_c" type="hidden" id="id_c" value="<?php echo $_GET['id_c']; ?>"></td>
          <td id="fuente2">VERSION </td>
        </tr>
        <tr>
          <td id="dato2"><select name="ref" id="ref" onBlur="if(form1.ref.value) { consultaref(); } else{ alert('Debe Seleccionar una REFERENCIA'); }">
            <option value="" <?php if (!(strcmp("", $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>Select</option>
            <?php
do {  
?>
            <option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
            <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
            </select>
            <input name="cod_ref" type="hidden" value="<?php echo $row_referencia_edit['cod_ref']; ?>"></td>
          <td id="dato2"><?php echo $row_referencia_edit['version_ref']; ?>
            <input name="version_ref" type="hidden" id="version_ref" value="<?php echo $row_referencia_edit['version_ref']; ?>"></td>
        </tr>
        
        <tr>
          <td id="fuente2">EGP N&deg;
            <input name="n_egp_ref" type="hidden" value="<?php echo $row_referencia_edit['n_egp_ref']; ?>"><?php echo $row_referencia_edit['n_egp_ref']; ?></td>
          <td id="fuente2">Cotizaci&oacute;n N&deg;
            <input name="n_cotiz_ref" type="hidden" value="<?php echo $row_referencia_edit['n_cotiz_ref']; ?>"><?php echo $row_referencia_edit['n_cotiz_ref']; ?></td>
        </tr>
        <tr>
          <td colspan="2" id="detalle1"><input name="registro1_ref" type="hidden" value="<?php echo $row_referencia_edit['registro1_ref']; ?>">
            Registrado: <?php echo $row_referencia_edit['registro1_ref']; ?>
            <input name="fecha_registro1_ref" type="hidden" value="<?php echo $row_referencia_edit['fecha_registro1_ref']; ?>">
            <?php echo $row_referencia_edit['fecha_registro1_ref']; ?></td>
          </tr>        
        <tr>
          <td id="fuente2">Tipo Bolsa </td>
          <td id="fuente2">Adhesivo</td>
        </tr>
        <tr>
          <td id="dato2"><select name="tipo_bolsa_ref" id="tipo_bolsa_ref">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_edit['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Seguridad" <?php if (!(strcmp("Seguridad", $row_referencia_edit['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>Seguridad</option>
              <option value="Currier" <?php if (!(strcmp("Currier", $row_referencia_edit['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>Currier</option>
              <option value="Bolsa Plastica" <?php if (!(strcmp("Bolsa Plastica", $row_referencia_edit['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>Bolsa Plastica</option>
          </select></td>
          <td id="dato2"><select name="adhesivo_ref" id="adhesivo_ref">
              <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_edit['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
              <option value="Cinta de Seguridad" <?php if (!(strcmp("Cinta de Seguridad", $row_referencia_edit['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta de Seguridad</option>
              <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_referencia_edit['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
              <option value="Cinta Permanente" <?php if (!(strcmp("Cinta Permanente", $row_referencia_edit['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta Permanente</option>
              <option value="Cinta Resellable" <?php if (!(strcmp("Cinta Resellable", $row_referencia_edit['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>Cinta Resellable</option>
          </select></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Material</td>
          </tr>
        <tr>
          <td colspan="2" id="dato2"><select name="material_ref" id="material_ref">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_edit['material_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Ldpe coestruido pigmentado"  <?php if (!(strcmp("Ldpe coestruido pigmentado", $row_referencia_edit['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido pigmentado</option>
            <option value="Ldpe coestruido sin pigmentos"  <?php if (!(strcmp("Ldpe coestruido sin pigmentos", $row_referencia_edit['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe coestruido sin pigmentos</option>
            <option value="Ldpe monocapa sin pigmentos"  <?php if (!(strcmp("Ldpe monocapa sin pigmentos", $row_referencia_edit['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa sin pigmentos</option>
            <option value="Ldpe monocapa pigmentado"  <?php if (!(strcmp("Ldpe monocapa pigmentado", $row_referencia_edit['material_ref']))) {echo "selected=\"selected\"";} ?>>Ldpe monocapa pigmentado</option></select></td>
          </tr>
        <tr>
          <td id="fuente2">Ancho</td>
          <td id="fuente2">Largo</td>
        </tr>
        <tr>
          <td id="dato2"><input type="text" name="ancho_ref" value="<?php echo $row_referencia_edit['ancho_ref']; ?>" size="10"></td>
          <td id="dato2"><input type="text" name="largo_ref" value="<?php echo $row_referencia_edit['largo_ref']; ?>" size="10"></td>
        </tr>
        <tr>
          <td id="fuente2">Solapa</td>
          <td id="fuente2">Bolsillo</td>
        </tr>
        <tr>
          <td id="dato2"><input type="text" name="solapa_ref" value="<?php echo $row_referencia_edit['solapa_ref']; ?>" size="10"></td>
          <td id="dato2"><input type="text" name="bolsillo_guia_ref" value="<?php echo $row_referencia_edit['bolsillo_guia_ref']; ?>" size="10"></td>
        </tr>
        <tr>
          <td id="fuente2">Calibre</td>
          <td id="fuente2">Peso Millar</td>
        </tr>
        <tr>
          <td id="dato2"><input type="text" name="calibre_ref" value="<?php echo $row_referencia_edit['calibre_ref']; ?>" size="10"></td>
          <td id="dato2"><input type="text" name="peso_millar_ref" value="<?php echo $row_referencia_edit['peso_millar_ref']; ?>" size="10"></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Impresi&oacute;n</td>
          </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="impresion_ref" value="<?php echo $row_referencia_edit['impresion_ref']; ?>" size="30"></td>
          </tr>
        <tr>
          <td colspan="2" id="fuente2">Numeraci&oacute;n &amp; Posiciones </td>
          </tr>
        <tr>
          <td colspan="2" id="dato2"><input type="text" name="num_pos_ref" value="<?php echo $row_referencia_edit['num_pos_ref']; ?>" size="30"></td>
          </tr>
        <tr>
          <td id="fuente2">Barras &amp; Formato</td>
          <td id="fuente2">Estado de la Referencia </td>
        </tr>
        <tr>
          <td id="dato2"><select name="cod_form_ref" id="cod_form_ref">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_edit['cod_form_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
<option value="EAN 128" <?php if (!(strcmp("EAN 128", $row_referencia_edit['cod_form_ref']))) {echo "selected=\"selected\"";} ?>>EAN 128</option>
          </select></td>
          <td id="dato2"><select name="estado_ref" id="estado_ref">
            <option value="" <?php if (!(strcmp("", $row_referencia_edit['estado_ref']))) {echo "selected=\"selected\"";} ?>>*</option>
            <option value="0" <?php if (!(strcmp(0, $row_referencia_edit['estado_ref']))) {echo "selected=\"selected\"";} ?>>INACTIVA</option>
              <option value="1" <?php if (!(strcmp(1, $row_referencia_edit['estado_ref']))) {echo "selected=\"selected\"";} ?>>ACTIVA</option>
          </select></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">Ultima Actualización</td>
          </tr>
        <tr>
          <td colspan="2" id="dato2">- 
            <input name="registro2_ref" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>"><?php echo $row_referencia_edit['registro2_ref']; ?> - <input name="fecha_registro2_ref" type="hidden" value="<?php echo date("Y-m-d"); ?>"> 
            <?php echo $row_referencia_edit['fecha_registro2_ref']; ?> - </td>
          </tr>        
        <tr>
          <td colspan="2" id="dato2"><input type="submit" value="Actualizar REFERENCIA"></td>
          </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
      <input type="hidden" name="id_ref" value="<?php echo $row_referencia_edit['id_ref']; ?>">
    </form>
	</div>
	</td>
	<td id="detalle2">
	<div align="center">
	<form method="post" name="form2" action="<?php echo $editFormAction; ?>">
	<table id="tabla6">          
          <tr>
            <td id="fuente2"><input name="n_ce" type="hidden" value="<?php $num=$row_ce_num['n_ce']+1; echo $num; ?>">
              <input name="n_cotiz_ce" type="hidden" value="<?php echo $_GET['n_cotiz']; ?>">
              <input name="id_ref_ce" type="hidden" value="<?php echo $_GET['id_ref']; ?>">
              <input name="id_c_cotiz" type="hidden" value="<?php echo $row_cotizacion['id_c_cotiz']; ?>"><input name="fecha_cotiz" type="hidden" value="<?php echo $row_cotizacion['fecha_cotiz']; ?>">
<input name="registro" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>">
              Cantidad Minima </td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="cant_min_ce" value="" size="30"></td>
          </tr>
          <tr>
            <td id="fuente2">Tiempo de Entrega </td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="tiempo_entrega_ce" value="" size="30"></td>
          </tr>
          <tr>
            <td id="dato2"><a href="javascript:verFoto('archivos/INCOTERMS.doc','610','490')">INCOTERM</a>
			<select name="incoterm_ce" id="incoterm_ce">
                <option value="N.A.">N.A.</option>
                <option value="CFR">CFR</option>
                <option value="CIF">CIF</option>
                <option value="CIP">CIP</option>
                <option value="CPT">CPT</option>
                <option value="DAF">DAF</option>
                <option value="DDP">DDP</option>
                <option value="DDU">DDU</option>
                <option value="DEQ">DEQ</option>
                <option value="DES">DES</option>
                <option value="EXW">EXW</option>
                <option value="FAS">FAS</option>
                <option value="FCA">FCA</option>
                <option value="FOB">FOB</option>
              </select></td>
          </tr>          
          <tr>
            <td id="fuente2">Precio de Venta </td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="precio_venta_ce" value="" size="30"></td>
          </tr>
          <tr>
            <td id="fuente2">Moneda</td>
            </tr>
          <tr>
            <td id="dato2"><select name="moneda_ce" id="moneda_ce">
              <option value="N.A.">N.A.</option>
              <option value="COL $">COL $</option>
              <option value="USD $">USD $</option>
            </select></td>
            </tr>
          <tr>
            <td id="fuente2">Unidad</td>
          </tr>
          <tr>
            <td id="dato2"><select name="unidad_ce" id="unidad_ce">
              <option value="N.A.">N.A.</option>
              <option value="Unitario">Unitario</option>
              <option value="Millar">Millar</option>
            </select></td>
          </tr>
          <tr>
            <td id="fuente2">Forma de Pago </td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="forma_pago_ce" value="" size="30"></td>
          </tr>
          <tr>
            <td id="fuente2">Entrega</td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="entrega_ce" value="" size="30"></td>
          </tr>
          <tr>
            <td id="fuente2">Costo del Cirel </td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="costo_cirel_ce" value="" size="30"></td>
          </tr>
          <tr>
            <td id="fuente2">Vendedor</td>
          </tr>
          <tr>
            <td id="dato2"><select name="vendedor" id="vendedor">
              <option value="">*</option>
              <?php
do {  
?>
              <option value="<?php echo $row_vendedores['id_vendedor']?>"><?php echo $row_vendedores['nombre_vendedor']?></option>
              <?php
} while ($row_vendedores = mysql_fetch_assoc($vendedores));
  $rows = mysql_num_rows($vendedores);
  if($rows > 0) {
      mysql_data_seek($vendedores, 0);
	  $row_vendedores = mysql_fetch_assoc($vendedores);
  }
?>
            </select></td>
          </tr>
          <tr>
            <td id="fuente2">Comision</td>
          </tr>
          <tr>
            <td id="dato2"><input name="comision" type="text" id="comision" size="30"></td>
          </tr>
          <tr>
            <td id="dato2"><input type="submit" value="Add REFERENCIA"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form2">
      </form>
	  </div>
	  </td>
  </tr>
</table>
</td></tr></table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion);

mysql_free_result($referencia_edit);

mysql_free_result($referencias);

mysql_free_result($clientes);

mysql_free_result($ce_num);

mysql_free_result($vendedores);
?>