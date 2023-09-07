<?php require_once('Connections/conexion1.php'); ?><?php
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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_orden_compra = "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM Tbl_orden_compra WHERE str_numero_oc = '%s' AND b_borrado_oc='0'", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

$colname_cliente_oc = "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_cliente_oc = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente_oc = sprintf("SELECT * FROM Tbl_orden_compra, cliente WHERE Tbl_orden_compra.str_numero_oc = '%s' AND Tbl_orden_compra.str_nit_oc = cliente.nit_c AND Tbl_orden_compra.b_borrado_oc='0'", $colname_cliente_oc);
$cliente_oc = mysql_query($query_cliente_oc, $conexion1) or die(mysql_error());
$row_cliente_oc = mysql_fetch_assoc($cliente_oc);
$totalRows_cliente_oc = mysql_num_rows($cliente_oc);

$colname_detalle = "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_detalle = sprintf("SELECT * FROM Tbl_items_ordenc WHERE Tbl_items_ordenc.str_numero_io = '%s' ORDER BY id_items ASC", $colname_detalle);
$detalle = mysql_query($query_detalle, $conexion1) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);
//REMISIONES X ITEMS
$colname_remision= "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_remision = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision = sprintf("SELECT * FROM Tbl_orden_compra,Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_orden_compra.str_numero_oc = '%s' AND Tbl_orden_compra.b_borrado_oc='0' AND Tbl_orden_compra.str_numero_oc=Tbl_remision_detalle.str_numero_oc_rd AND  Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_items_ordenc.id_items ASC", $colname_remision);
$remision = mysql_query($query_remision, $conexion1) or die(mysql_error());
$row_remision = mysql_fetch_assoc($remision);
$totalRows_remision = mysql_num_rows($remision);
//IMRPIME EL NOMBRE DEL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tabla3">
  <tr>
    <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" /><?php if($row_cliente_oc['str_nit_oc']=='') { ?><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_orden_compra['str_numero_oc']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><?php } else { ?><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_orden_compra['str_numero_oc']; ?>&id_oc=<?php echo $_GET['id_oc']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><a href="orden_compra_cl.php"><img src="images/o.gif" style="cursor:hand;" alt="ORDENES DE COMPRA" title="LISTADO DE ORDENES DE COMPRA" border="0" /></a><?php } ?> <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR" onClick="window.close() "/></a></td>
  </tr>
</table>
<table id="tabla1">
  <tr><td height="538" align="center">
<table id="tabla3">
<tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
<td id="titulo">ORDEN DE COMPRA</td>
</tr>

<tr>
  <td id="titular2">CLIENTES</td>
</tr>
<tr>
  <td id="numero2">N° <strong><?php echo $row_orden_compra['str_numero_oc']; ?></strong></td>
</tr>
<tr>
  <td id="fondo2">ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6<br>Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
</tr>
</table>
<table id="tabla3" >
  <tr>
    <td id="dato1"><strong>NIT : </strong><?php echo $row_cliente_oc['nit_c']; ?></td>
    <td id="dato1"><strong>PAIS / CIUDAD : </strong><?php echo $row_cliente_oc['pais_c']; ?> / <?php echo $row_cliente_oc['ciudad_c']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="dato1"><strong>CLIENTE : </strong><?php echo $row_cliente_oc['nombre_c']; ?></td>
  </tr>
  <tr>
    <td id="dato1"><strong>FECHA DE PEDIDO : </strong><?php echo $row_cliente_oc['fecha_ingreso_oc']; ?></td>
    <td id="dato1"><strong>TELEFONO : </strong><?php echo $row_cliente_oc['telefono_c']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="dato1"><strong>DIRECCION:</strong><?php $dir =htmlentities($row_cliente_oc['direccion_c']);echo $dir; ?></td>
  </tr>
  <tr>
    <td id="dato1"><strong>CONTACTO COMERCIAL : </strong><?php echo $row_cliente_oc['contacto_c']; ?></td>
    <td id="dato1"><strong>FAX : </strong><?php echo $row_cliente_oc['fax_c']; ?></td>
  </tr>
  <tr>
    <td id="dato1"><strong>EMAIL COMERCIAL: </strong><?php echo $row_cliente_oc['email_comercial_c']; ?></td>
    <td id="dato1"><strong>CONDICIONES DE PAGO : </strong><?php echo $row_cliente_oc['str_condicion_pago_oc']; ?></td>
  </tr>
</table>
<?php if(($row_remision['id_rd']!='')){ ?>
<table id="tabla3">
  <tr>
    <td colspan="23" id="nivel2">RELACION O.C. - REMISION</td>
  </tr>
  <tr>
                <td id="nivel2">ITEM N°</td>
                <td id="nivel2">REF. AC</td>
                <td id="nivel2">REF. MP</td>
                <td id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">CANTIDAD</td>
                <td id="nivel2">CANTIDAD RESTANTE</td>
                <td id="nivel2">UNIDADES</td>
                <td id="nivel2">FECHA DE ENTREGA</td>
                <td id="nivel2">PRECIO / VENTA</td>
                <td id="nivel2">TOTAL ITEM $</td>
                <td id="nivel2">MONEDA</td>
                <td id="nivel2">DIRECCION ENTREGA</td>
                <td id="nivel2">VENDEDOR</td>
                <td id="nivel2">COMI. %</td>                 
                <td id="nivel2">REMISION N°</td>
                <td id="nivel2">REF. AC </td>
                <td id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">RANGOS</td>
                <td id="nivel2">DESDE</td>
                <td id="nivel2">HASTA</td>
                <td id="nivel2">UNIDADES</td>
                <td id="nivel2">PESO</td>
                <td id="nivel2">PESO/N</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="detalle2"><?php echo $row_remision['int_consecutivo_io']; ?></td>
      <td id="detalle2"><?php echo $row_remision['int_cod_ref_io']; ?></td>
      <td id="detalle2"><?php $mp=$row_remision['id_mp_vta_io'];
		if($mp!='')
		{
		$sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
		$resultmp= mysql_query($sqlmp);
		$nump= mysql_num_rows($resultmp);
		if($nump >='1')
		{ 
		$nombre_mp = mysql_result($resultmp,0,'str_nombre');
		echo $nombre_mp;
		} } ?></td>
      <td id="detalle2"><?php echo $row_remision['int_cod_cliente_io']; ?></td>
      <td id="detalle2"><?php  echo number_format($row_remision['int_cantidad_io'], 0, ",", "."); ?></td>
      <td id="detalle2"><?php if($row_remision['int_cantidad_rest_io']==''){echo '0';}else{$cantrest=$row_remision['int_cantidad_io']-($totalrm+$row_remision['int_cant_rd']);echo $cantrest;} ?></td>
      <td id="detalle2"><?php echo $row_remision['str_unidad_io']; ?></td>
      <td id="detalle2"><?php echo $row_remision['fecha_entrega_io']; ?></td>
      <td id="detalle2"><?php echo $row_remision['int_precio_io']; ?></td>
      <td id="detalle2"><?php  $tota=$row_remision['int_total_item_io'];echo number_format($tota, 2, ",", ".");  ?></td>
      <td id="detalle2"><?php echo $row_remision['str_moneda_io']; ?></td>
      <td id="detalle2"><?php echo $row_remision['str_direccion_desp_io']; ?></td>
      <td id="detalle2"><?php $vendedor=$row_remision['int_vendedor_io'];
	if($vendedor!='')
	{
	$sqlvendedor="SELECT * FROM vendedor WHERE id_vendedor ='$vendedor'";
	$resultvendedor= mysql_query($sqlvendedor);
	$nuvendedor= mysql_num_rows($resultvendedor);
	if($nuvendedor >='1')
	{ 
	$nombre_vendedor = mysql_result($resultvendedor,0,'nombre_vendedor');
	echo $nombre_vendedor;
	} } ?></td>    
      <td id="detalle2"><?php echo $row_remision['int_comision_io']; ?></td>
      <!--VARIABLES DE REMISION X ITEMS-->
      <td id="detalle2"><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision_r_rd'] ?>" target="_blank"><?php echo $row_remision['int_remision_r_rd']; ?></a></td>      
      <td id="detalle2"><?php echo $row_remision['int_ref_io_rd']; ?></td>
      <td id="detalle2"><?php echo $row_remision['str_ref_cl_io_rd']; ?></td>
      <td id="detalle2"><?php echo $row_remision['int_caja_rd']; ?></td>
      <td id="detalle2"><?php echo $row_remision['int_numd_rd']; ?></td>
      <td id="detalle2"><?php echo $row_remision['int_numh_rd']; ?></td>
      <td id="detalle2"><?php $cant=$row_remision['int_cant_rd'];echo number_format($cant, 0, ",", "."); ?></td>
      <td id="detalle2"><?php echo $row_remision['int_peso_rd']; $peso=(double)$peso+$row_remision['int_peso_rd']; ?></td>
      <td id="detalle2"><?php echo $row_remision['int_pesoneto_rd'];$peson=(double)$peson+$row_remision['int_pesoneto_rd']; ?></td>      
    </tr>
    <tr>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle1">Sub Total $</td>
      <td id="detalle2"><?php 
	  $subtotal=$row_remision['int_cant_rd']*$row_remision['int_precio_io'];
      echo number_format($subtotal, 2, ",", "."); 
	  ?></td>
      <td id="detalle4"><?php $acumula+=$subtotal; ?></td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle1">Sub Total $</td>
      <td id="detalle2"><?php echo number_format($row_remision['int_cant_rd'], 0, ",", "."); $totalrm=$totalrm+$row_remision['int_cant_rd']; ?></td>      
      <td id="detalle2"><?php echo $peso; ?></td>
      <td id="detalle2"><?php echo $peson; ?></td>
    </tr>
<?php } while ($row_remision = mysql_fetch_assoc($remision)); ?> 
  
<tr>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="nivel2">TOTALES </td>
      <td id="detalle2"><strong><?php echo number_format($acumula, 2, ",", ".");?></strong></td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
    <td colspan="2" id="fondo1">Facturado
    <?php if($row_orden_compra['b_estado_oc']=='4'){echo $facpot="Parcial";}else if($row_orden_compra['b_estado_oc']=='5'){echo $facpot="Total";};?></td>
    <td id="nivel2">TOTALES</td>
    <td id="detalle2"><strong><?php echo number_format($totalrm, 0, ",", "."); ?></strong></td>
    <td id="detalle4">&nbsp;</td>
    <td id="detalle4">&nbsp;</td>
  </tr>
    </table>    
    <?php }?>
    
    
<!-- //INICIA E IMPRIME SI NO TIENE REMISIONES -->  
<?php if(($row_detalle['id_items']!='')){ ?>
<table id="tabla3">
  <tr>
    <td colspan="14" id="nivel2">DETALLE O.C.</td>
    </tr>
  <tr>
                <td id="nivel2">ITEM N°</td>
                <td id="nivel2">REF. AC</td>
                <td id="nivel2">REF. MP</td>
                <td id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">CANTIDAD</td>
                <td id="nivel2">CANTIDAD RESTANTE</td>
                <td id="nivel2">UNIDADES</td>
                <td id="nivel2">FECHA DE ENTREGA</td>
                <td id="nivel2">PRECIO / VENTA</td>
                <td id="nivel2">TOTAL ITEM</td>
                <td id="nivel2">MONEDA</td>
                <td id="nivel2">VENDEDOR</td>
                <td id="nivel2">COMI. %</td>                 
  </tr>
  <?php do { ?>
    <tr>
      <td id="detalle2"><?php echo $row_detalle['int_consecutivo_io']; ?></td>
      <td id="detalle2"><?php echo $row_detalle['int_cod_ref_io']; ?></td>
      <td id="detalle2"><?php $mp=$row_detalle['id_mp_vta_io'];
		if($mp!='')
		{
		$sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
		$resultmp= mysql_query($sqlmp);
		$nump= mysql_num_rows($resultmp);
		if($nump >='1')
		{ 
		$nombre_mp = mysql_result($resultmp,0,'str_nombre');
		echo $nombre_mp;
		} } ?></td>
      <td id="detalle2"><?php echo $row_detalle['int_cod_cliente_io']; ?></td>
      <td id="detalle2"><?php echo $row_detalle['int_cantidad_io']; ?></td>
      <td id="detalle2"><?php if($row_detalle['int_cantidad_rest_io']==''){echo '0';}else{echo $row_detalle['int_cantidad_rest_io'];} ?></td>
      <td id="detalle2"><?php echo $row_detalle['str_unidad_io']; ?></td>
      <td id="detalle2"><?php echo $row_detalle['fecha_entrega_io']; ?></td>
      <td id="detalle2"><?php echo $row_detalle['int_precio_io']; ?></td>
      <td id="detalle2"><?php echo $row_detalle['int_total_item_io']; ?></td>
      <td id="detalle2"><?php echo $row_detalle['str_moneda_io']; ?></td>
      <td id="detalle2"><?php $vendedor=$row_detalle['int_vendedor_io'];
	if($vendedor!='')
	{
	$sqlvendedor="SELECT * FROM vendedor WHERE id_vendedor ='$vendedor'";
	$resultvendedor= mysql_query($sqlvendedor);
	$nuvendedor= mysql_num_rows($resultvendedor);
	if($nuvendedor >='1')
	{ 
	$nombre_vendedor = mysql_result($resultvendedor,0,'nombre_vendedor');
	echo $nombre_vendedor;
	} } ?></td>    
      <td id="detalle2"><?php echo $row_detalle['int_comision_io']; ?></td>     
    </tr>         
    <?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>       
    <?php }?>
	<tr>
	  <td id="nivel1">FACTURA CIRELES</td>
	  <td id="detalle2"><strong>
      <?php if ($row_orden_compra['b_factura_cirel_oc']=='0'){echo "NO";}else {echo "SI";}?>
	  </strong></td>
	  <td id="fondo1">&nbsp;</td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td >&nbsp;</td>
	  <td colspan="9">&nbsp;</td>
  </tr>
               
	<tr>
	  <td colspan="9" id="nivel1">OBSERVACIONES</td>
      <td  id="nivel1"><?php if($row_orden_compra['str_archivo_oc']==''){echo "Sin Archivo";}else{echo "ARCHIVO";}?></td>    
	  </tr>
	  <tr>
	  <td colspan="9" rowspan="3" id="detalle1">- <?php $obs= htmlentities($row_orden_compra['str_observacion_oc']);echo $obs; ?> - </td>
      <?php if($row_orden_compra['str_archivo_oc']!=''){?>
      <td id="detalle1"><a href="javascript:verFoto('pdfacturasoc/<?php  $muestra=$row_orden_compra['str_archivo_oc'];echo $muestra;?>','610','490')"> <?php echo $muestra;?></a></td>
      
    </tr>

<?php }?>
<table id="tabla3">
  <tr>
    <td id="nivel2">LUGAR DE ENTREGA </td>
    <td id="nivel2">ELABORADO POR </td>
    <td id="nivel2">APROBADO POR </td>
    <td id="nivel2"><p>FIRMA &amp; SELLO ACYCIA </p>
      </td>
  </tr>
  <tr>
    <td id="detalle2"><?php echo $row_orden_compra['str_dir_entrega_oc']; ?></td>
    <td id="detalle2"><?php echo $row_orden_compra['str_elaboro_oc']; ?></td>
    <td id="detalle2"><?php echo $row_orden_compra['str_aprobo_oc']; ?></td>
    <td id="detalle2">&nbsp;</td>
  </tr>
</table>
<table id="tabla3">
  <tr>
    <td id="fondo1">CODIGO : A3 - F02</td>
    <td id="fondo2">Favor citar este numero de Orden de Compra en la Factura.</td>
    <td id="fondo3">VERSION : 0</td>
  </tr>
</td>
</tr>
</table>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($orden_compra);

mysql_free_result($cliente_oc);

mysql_free_result($detalle);

mysql_free_result($vendedores);
?>