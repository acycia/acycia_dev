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
  $updateSQL = sprintf("UPDATE TblCostoExportacion SET id_c_ce=%s, fecha_pre_ce=%s, fecha_ven_ce=%s, fecha_mod_ce=%s, pedido_ce=%s, incoterm_ce=%s, lugarExp_ce=%s, zona_ce=%s, consignado_ce=%s, cond_pago_ce=%s, subtotal_ce=%s, flete_ce=%s, seguro_ce=%s, total_ce=%s, observacion_ce=%s, responsable_ce=%s WHERE n_ce=%s",
                       GetSQLValueString($_POST['id_c_ce'], "int"),
                       GetSQLValueString($_POST['fecha_pre_ce'], "date"),
                       GetSQLValueString($_POST['fecha_ven_ce'], "date"),
					   GetSQLValueString($_POST['fecha_mod_ce'], "date"),
                       GetSQLValueString($_POST['pedido_ce'], "text"),
                       GetSQLValueString($_POST['incoterm_ce'], "text"),
                       GetSQLValueString($_POST['lugarExp_ce'], "text"),
                       GetSQLValueString($_POST['zona_ce'], "text"),
                       GetSQLValueString($_POST['consignado_ce'], "text"),
                       GetSQLValueString($_POST['cond_pago_ce'], "text"),
                       GetSQLValueString($_POST['subtotal_ce'], "double"),
					   GetSQLValueString($_POST['flete_ce'], "double"),
					   GetSQLValueString($_POST['seguro_ce'], "double"),
                       GetSQLValueString($_POST['total_ce'], "double"),
                       GetSQLValueString($_POST['observacion_ce'], "text"),
                       GetSQLValueString($_POST['responsable_ce'], "text"),
                       GetSQLValueString($_POST['n_ce'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "costo_exportacion_vista.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_costoExp = "-1";
if (isset($_GET['n_ce'])) {
  $colname_costoExp = (get_magic_quotes_gpc()) ? $_GET['n_ce'] : addslashes($_GET['n_ce']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_costoExp = sprintf("SELECT * FROM TblCostoExportacion WHERE n_ce = '%s'", $colname_costoExp);
$costoExp = mysql_query($query_costoExp, $conexion1) or die(mysql_error());
$row_costoExp = mysql_fetch_assoc($costoExp);
$totalRows_costoExp = mysql_num_rows($costoExp);

mysql_select_db($database_conexion1, $conexion1);
$query_clientes = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

$colname_cliente = "-1";
if (isset($_GET['id_c_ce'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['id_c_ce'] : addslashes($_GET['id_c_ce']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE id_c = '%s'", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$colname_detalle = "-1";
if (isset($_GET['n_ce'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['n_ce'] : addslashes($_GET['n_ce']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_detalle = sprintf("SELECT * FROM TblCostoExportacionDetalle, Tbl_referencia WHERE TblCostoExportacionDetalle.n_ce_det = '%s' AND TblCostoExportacionDetalle.id_ref_det=Tbl_referencia.id_ref", $colname_detalle);
$detalle = mysql_query($query_detalle, $conexion1) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);

$colname_datos_oc = "-1";
if (isset($_GET['id_c_ce'])) {
  $colname_datos_oc = (get_magic_quotes_gpc()) ? $_GET['id_c_ce'] : addslashes($_GET['id_c_ce']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_datos_oc = sprintf("SELECT str_numero_oc,b_borrado_oc FROM Tbl_orden_compra WHERE id_c_oc = '%s' AND b_borrado_oc='0' ORDER BY fecha_ingreso_oc DESC", $colname_datos_oc);
$datos_oc = mysql_query($query_datos_oc, $conexion1) or die(mysql_error());
$row_datos_oc = mysql_fetch_assoc($datos_oc);
$totalRows_datos_oc = mysql_num_rows($datos_oc);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="costo_exportacion_listado.php">FACTURAS</a></li>
  </ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1"><form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="MM_validateForm('fecha_pre_ce','','R','fecha_ven_ce','','R',pedido_ce','','R','cond_pago_oc','','R','subtotal_ce','','R','total_ce','','R','lugar_entrega_oc','','R');return document.MM_returnValue">
      <table id="tabla2">
          <tr id="tr1">
            <td nowrap id="codigo">CODIGO : A3 - F02</td>
            <td colspan="2" nowrap id="titulo2">FACTURACION</td>
            <td nowrap id="codigo">VERSION : 1 </td>
          </tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td colspan="2" id="subtitulo"><strong>BOLSAS, PACKING, LAMINA</strong></td>
            <td id="dato2"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('id_n_ce',<?php echo $row_costoExp['n_ce']; ?>,'costo_exportacion_edit.php')"><img src="images/por.gif" alt="ELIMINAR EXP." title="ELIMINAR EXP." border="0" style="cursor:hand;"/></a><a href="costo_exportacion_vista.php?n_ce=<?php echo $row_costoExp['n_ce']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="costo_exportacion_listado.php"><img src="images/e.gif" alt="LISTADO FACTURAS" title="LISTADO FACTURAS" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td colspan="2" id="numero2">FACTURA N&deg; <strong><?php echo $row_costoExp['n_ce']; ?>
              <input name="n_ce" type="hidden" min="000" value="<?php echo $row_costoExp['n_ce'];?>">
            </strong></td>
            <td id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Fecha Preparacion:</td>
            <td id="fuente1">Fecha Vencimiento : </td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><input name="fecha_pre_ce" type="date" id="fecha_pre_ce" value="<?php echo $row_costoExp['fecha_pre_ce']; ?>" size="10"></td>
            <td id="dato1"><input name="fecha_ven_ce" type="date" id="fecha_ven_ce" value="<?php echo $row_costoExp['fecha_ven_ce']; ?>" size="10"></td>
          </tr>
          <tr>
            <td colspan="3" id="fuente1">Seleccione el Cliente</td>
            </tr>
          <tr>
            <td colspan="3" id="dato1"><select name="id_c_ce" id="id_c_ce" style="width:300px" onChange="consultaexportacion()">
                <option value="0" <?php if (!(strcmp(0, $_GET['id_c_ce']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
                <?php
				do {  
				?>
				<option value="<?php echo $row_clientes['id_c']?>"<?php if (!(strcmp($row_clientes['id_c'], $_GET['id_c_ce']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clientes['nombre_c']?></option>
								<?php
				} while ($row_clientes = mysql_fetch_assoc($clientes));
				  $rows = mysql_num_rows($clientes);
				  if($rows > 0) {
					  mysql_data_seek($clientes, 0);
					  $row_clientes = mysql_fetch_assoc($clientes);
				  }
				?>
                        </select></td>
            </tr>
           <tr>
            <td colspan="4" id="fuente2">INFORMACION DEL CLIENTE</td>
            </tr>            
          <tr id="tr1">
            <td colspan="4" id="detalle1">
            <table id="tabla1">
     <td colspan="2" id="detalle1">NIT : <?php echo $row_cliente['nit_c']; ?></td>
      <td colspan="2" id="detalle1">Telefono : <?php echo $row_cliente['telefono_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Contacto Comercial : <?php echo $row_cliente['contacto_c']; ?></td>
      <td colspan="2" id="detalle1">Fax : <?php echo $row_cliente['fax_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" nowrap id="detalle1">Cargo : <?php echo $row_cliente['cargo_contacto_c']; ?></td>
      <td colspan="2" id="detalle1">Pa&iacute;s : <?php echo $row_cliente['pais_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Email : <?php echo $row_cliente['email_comercial_c']; ?></td>
      <td colspan="2" id="detalle1">Ciudad : <?php echo $row_cliente['ciudad_c']; ?></td>
    </tr>
    <tr>
      <td colspan="4" id="detalle1">Direcci&oacute;n : <?php echo $row_cliente['direccion_c']; ?></td>
            </table></td>
            </tr>       
            <tr>
              <td colspan="4" id="fuente2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="4" id="fuente2">INFORMACION ADICIONAL</td>
            </tr>

          <tr>
            <td id="fuente1">Pedido N&deg;</td>
    <td id="dato1"><select name="pedido_ce" style="width:154px"  onChange="DatosGestiones3('4','pedido_ce',form1.pedido_ce.value);">
    <option value="0"<?php if (!(strcmp("", $row_datos_oc['str_numero_oc']))) {echo "selected=\"selected\"";} ?>>Orde de Compra</option>
      <?php
	do {  
	?>
		  <option value="<?php echo $row_datos_oc['str_numero_oc']?>"<?php if (!(strcmp($row_datos_oc['str_numero_oc'], $row_datos_oc['str_numero_oc']))) {echo "selected=\"selected\"";} ?>><?php echo $row_datos_oc['str_numero_oc']?></option>
		  <?php
	} while ($row_datos_oc = mysql_fetch_assoc($datos_oc));
	  $rows = mysql_num_rows($datos_oc);
	  if($rows > 0) {
		  mysql_data_seek($datos_oc, 0);
		  $row_datos_oc = mysql_fetch_assoc($datos_oc);
	  }
	?>
    </select></td>
            <td id="dato1">Incoterm</td>
            <td id="fuente1"><input name="incoterm_ce" type="text" id="incoterm_ce" value="<?php echo $row_costoExp['incoterm_ce']; ?>" size="20" onBlur="conMayusculas(this)"></td>
            </tr>
		  <tr>
		    <td id="fuente1">Lugar Expedicion		      </td>
		    <td id="dato1"><input name="lugarExp_ce" type="text" id="lugarExp_ce" value="<?php if($row_costoExp['lugarExp_ce']==''){echo $row_costoExp['lugarExp_ce'];}else{echo "MEDELLIN";} ?>" size="20" onBlur="conMayusculas(this)"></td>
		    <td id="dato1">Zona </td>
		    <td id="fuente1"><input name="zona_ce" type="text" id="zona_ce" value="<?php echo $row_costoExp['zona_ce']; ?>" size="20" onBlur="conMayusculas(this)"></td>
		    </tr>
          <tr>
		    <td id="fuente1">Consignado A</td>
		    <td id="dato1"><input name="consignado_ce" type="text" id="consignado_ce" value="<?php if($row_costoExp['consignado_ce']==''){echo $row_cliente['nit_c'];}else{echo $row_costoExp['consignado_ce'];} ?>" size="20" onBlur="conMayusculas(this)"></td>
		    <td id="dato1">Forma de Pago</td>
		    <td id="fuente1"><select name="cond_pago_ce" id="cond_pago_ce" style="width:154px">
		      <option>*</option>
		        <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
		        <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
		        <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias</option>
		        <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias</option>
		        <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias</option>
		        <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias</option>
		        <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias</option>
		        <option value="PAGO A 120 DIAS"<?php if (!(strcmp("PAGO A 120 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 120 Dias</option>
                <option value="PAGO A 150 DIAS"<?php if (!(strcmp("PAGO A 150 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 150 Dias</option>
                <option value="PAGO A 180 DIAS"<?php if (!(strcmp("PAGO A 180 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 180 Dias</option>
                <option value="PAGO A 210 DIAS"<?php if (!(strcmp("PAGO A 210 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 210 Dias</option>
                <option value="PAGO A 240 DIAS"<?php if (!(strcmp("PAGO A 240 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 240 Dias</option>
                <option value="PAGO A 270 DIAS"<?php if (!(strcmp("PAGO A 270 DIAS", $row_costoExp['cond_pago_ce']))) {echo "selected=\"selected\"";} ?>>Pago a 270 Dias</option>
                </select></td>
		    </tr>
            <tr>
              <td colspan="4" id="fuente2">&nbsp;</td>
            </tr>
            <tr>
            <td colspan="4" id="fuente2">INFORMACION DE LA O.C</td>
            </tr>
            <tr>
            <td colspan="4" id="dato2"><div id="resultado_generador"></div></td>
            </tr>                     
           <tr>
		    <td id="fuente1">&nbsp;</td>
		    <td colspan="2" id="dato1">&nbsp;</td>
		    <td id="fuente1">&nbsp;</td>
            </tr> 
            <tr>
            <td colspan="4" id="dato2"><strong><a href="javascript:verFoto('costo_exportacion_add_detalle.php?n_ce=<?php echo $row_costoExp['n_ce']; ?>&id_c_ce=<?php echo $_GET['id_c_ce']; ?>','950','330')">* ADD ITEM *</a></strong></td>
          </tr>          
		  <?php if($row_detalle['id_det']!='') { ?>
          <tr id="tr2">
            <td colspan="4" id="dato2"><table id="tabla1">
              <tr>
                <td id="nivel2">&nbsp;</td>
                <td id="nivel2">CODIGO</td>
                <td id="nivel2">CANT.</td>
                <td id="nivel2">DESCRIPCION</td>
                <td id="nivel2">MEDIDA</td>
                <td id="nivel2">PRECIO UND/MILL.</td>
                <td id="nivel2">TOTAL</td>
                </tr>
              <?php do { ?>
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                  <td id="talla1"><a href="javascript:eliminar1('id_det_ce',<?php echo $row_detalle['id_det']; ?>,'costo_exportacion_edit.php')"><img src="images/por.gif" alt="ELIMINAR REF" title="ELIMINAR REF" border="0" style="cursor:hand;"/></a></td>
                  <td id="talla1"><a href="javascript:verFoto('costo_exportacion_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_c_ce=<?php echo $_GET['id_c_ce']; ?>','950','330')" target="_top" style="text-decoration:none; color:#000000">
				  <?php 
				   $id_det_ce=$row_detalle['id_ref_det'];
			
					$sqldetalle="SELECT cod_ref FROM Tbl_referencia WHERE id_ref='$id_det_ce'";
					$resultdetalle= mysql_query($sqldetalle);
					$numdetalle= mysql_num_rows($resultdetalle);
					if($numdetalle >='1')
					{
					$cod_ref= mysql_result($resultdetalle, 0, 'cod_ref');
                      echo $cod_ref;
					}else{echo '';}
				  ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('costo_exportacion_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_c_ce=<?php echo $_GET['id_c_ce']; ?>','950','330')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['cantidad_det']; ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('costo_exportacion_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_c_ce=<?php echo $_GET['id_c_ce']; ?>','950','330')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['descripcion_det']; ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('costo_exportacion_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_c_ce=<?php echo $_GET['id_c_ce']; ?>','950','330')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['medida_det'];?> </a></td>
                  <td id="talla3"><a href="javascript:verFoto('costo_exportacion_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_c_ce=<?php echo $_GET['id_c_ce']; ?>','950','330')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['precio_unid_det']; ?></a></td>
                  <td id="talla3"><a href="javascript:verFoto('costo_exportacion_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_c_ce=<?php echo $_GET['id_c_ce']; ?>','950','330')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['valor_total_det']; $subtotal=$subtotal+$row_detalle['valor_total_det'];?></a></td>
                </tr>
                <?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>

            </table></td>
            </tr><?php } ?>
			<tr>
            <td colspan="4" id="fuente1">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="4" id="dato1">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="3" id="fuente1">OBSERVACIONES</td>            
            <td id="fuente3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" rowspan="4" id="fuente1"><textarea name="observacion_ce" cols="50" rows="3" id="observacion_ce"><?php echo $row_costoExp['observacion_ce']; ?></textarea></td>
            <td id="fuente3"><strong>SUBTOTAL</strong>
              <input name="subtotal_ce" type="number" style="width:80px" min="0.00" step="0.01" id="subtotal_ce" onBlur="totalce()"  required value="<?php echo $subtotal; ?>" size="10"></td>
          </tr>
          <tr>            
            <td id="fuente3">FLETES
              <input name="flete_ce" type="number" style="width:80px" min="0.00" step="0.01" id="flete_ce" onBlur="totalce()" required value="<?php if($row_costoExp['flete_ce']==''){echo "0";}else{echo $row_costoExp['flete_ce'];} ?>" size="10"></td>
          </tr>
          <tr>
            <td id="fuente3">SEGURO
              <input name="seguro_ce" type="number" style="width:80px" min="0.00" step="0.01" id="seguro_ce" onBlur="totalce()" required value="<?php if($row_costoExp['seguro_ce']==''){echo "0";}else{echo $row_costoExp['seguro_ce'];} ?>" size="10"></td>
          </tr>
          <tr>
            <td id="fuente3"><strong>TOTAL</strong>
              <input name="total_ce" type="number" style="width:80px" min="0.00" step="0.01" id="total_ce" onBlur="totalce()" required value="<?php echo $row_costoExp['total_ce']; ?>" size="10"></td>
          </tr>
          <tr>
            <td id="fuente1">VENDEDOR</td>
            <td colspan="2" id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato1"><input name="responsable_ce" type="text" id="responsable_ce" onKeyUp="conMayusculas(this)" value="<?php echo $row_costoExp['responsable_ce']; ?>" size="30" readonly></td>
            <td colspan="2" id="dato1">&nbsp;</td>
            <td id="dato1">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="fuente2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="fuente2"><input type="submit" value="FINALIZAR O.C."></td>
            </tr>
        </table>
        <input name="fecha_mod_ce" type="hidden" id="fecha_mod_ce" value="<?php echo date("Y-m-d"); ?>" size="10">
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="n_oc" value="<?php echo $row_costoExp['n_oc']; ?>">
      </form></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>
</table>
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

mysql_free_result($costoExp);

mysql_free_result($clientes);

mysql_free_result($cliente);

mysql_free_result($detalle);
?>