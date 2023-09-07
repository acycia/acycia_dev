<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once('funciones/funciones_php.php');

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

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor WHERE id_p = %s", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

$colname_evaluacion_vista = "-1";
if (isset($_GET['id_ev'])) {
  $colname_evaluacion_vista = (get_magic_quotes_gpc()) ? $_GET['id_ev'] : addslashes($_GET['id_ev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_evaluacion_vista = sprintf("SELECT * FROM evaluacion_proveedor WHERE id_ev = %s", $colname_evaluacion_vista);
$evaluacion_vista = mysql_query($query_evaluacion_vista, $conexion1) or die(mysql_error());
$row_evaluacion_vista = mysql_fetch_assoc($evaluacion_vista);
$totalRows_evaluacion_vista = mysql_num_rows($evaluacion_vista);

$desde_verificaciones_insumos = "-1";
if (isset($_GET['desde'])) {
  $desde_verificaciones_insumos = (get_magic_quotes_gpc()) ? $_GET['desde'] : addslashes($_GET['desde']);
}
$hasta_verificaciones_insumos = "-1";
if (isset($_GET['hasta'])) {
  $hasta_verificaciones_insumos = (get_magic_quotes_gpc()) ? $_GET['hasta'] : addslashes($_GET['hasta']);
}
$colname_verificaciones_insumos = "-1";
if (isset($_GET['id_p'])) {
  $colname_verificaciones_insumos = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificaciones_insumos = sprintf("SELECT * FROM verificacion_insumos WHERE id_p_vi = '%s' AND fecha_vi >= '%s' AND fecha_vi <= '%s' ORDER BY fecha_vi ASC", $colname_verificaciones_insumos,$desde_verificaciones_insumos,$hasta_verificaciones_insumos);
$verificaciones_insumos = mysql_query($query_verificaciones_insumos, $conexion1) or die(mysql_error());
$row_verificaciones_insumos = mysql_fetch_assoc($verificaciones_insumos);
$totalRows_verificaciones_insumos = mysql_num_rows($verificaciones_insumos);

$desde_rollo_verificacion = "-1";
if (isset($_GET['desde'])) {
  $desde_rollo_verificacion = (get_magic_quotes_gpc()) ? $_GET['desde'] : addslashes($_GET['desde']);
}
$hasta_rollo_verificacion = "-1";
if (isset($_GET['hasta'])) {
  $hasta_rollo_verificacion = (get_magic_quotes_gpc()) ? $_GET['hasta'] : addslashes($_GET['hasta']);
}
$colname_rollo_verificacion = "-1";
if (isset($_GET['id_p'])) {
  $colname_rollo_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_verificacion = sprintf("SELECT * FROM verificacion_rollos WHERE id_p_vr = '%s' AND fecha_recibo_vr >= '%s' AND  fecha_recibo_vr <= '%s' ORDER BY fecha_recibo_vr ASC", $colname_rollo_verificacion,$desde_rollo_verificacion,$hasta_rollo_verificacion);
$rollo_verificacion = mysql_query($query_rollo_verificacion, $conexion1) or die(mysql_error());
$row_rollo_verificacion = mysql_fetch_assoc($rollo_verificacion);
$totalRows_rollo_verificacion = mysql_num_rows($rollo_verificacion);

$desde_bolsa_verificacion = "-1";
if (isset($_GET['desde'])) {
  $desde_bolsa_verificacion = (get_magic_quotes_gpc()) ? $_GET['desde'] : addslashes($_GET['desde']);
}
$hasta_bolsa_verificacion = "-1";
if (isset($_GET['hasta'])) {
  $hasta_bolsa_verificacion = (get_magic_quotes_gpc()) ? $_GET['hasta'] : addslashes($_GET['hasta']);
}
$colname_bolsa_verificacion = "-1";
if (isset($_GET['id_p'])) {
  $colname_bolsa_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa_verificacion = sprintf("SELECT * FROM verificacion_bolsas WHERE id_p_vb = '%s' AND fecha_recibido_vb >= '%s' AND  fecha_recibido_vb <= '%s' ORDER BY fecha_recibido_vb ASC", $colname_bolsa_verificacion,$desde_bolsa_verificacion,$hasta_bolsa_verificacion);
$bolsa_verificacion = mysql_query($query_bolsa_verificacion, $conexion1) or die(mysql_error());
$row_bolsa_verificacion = mysql_fetch_assoc($bolsa_verificacion);
$totalRows_bolsa_verificacion = mysql_num_rows($bolsa_verificacion);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
</head>
<body>
<div align="center">
<table id="tabla2">
  <tr>
    <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="evaluacion_proveedor_carta.php?id_ev=<?php echo $row_evaluacion_vista['id_ev']; ?>&tipo_evaluacion=<?php echo $row_proveedor['tipo_servicio_p']; ?>"><img src="images/carta.gif" alt="CARTA" title="PRODUCTO-SERVICIO" target="_blank" border="0" style="cursor:hand;" ></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="evaluacion_proveedor_edit.php?id_p=<?php echo $row_evaluacion_vista['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>&id_ev=<?php echo $_GET['id_ev']; ?>&desde=<?php echo $row_evaluacion_vista['periodo_desde_ev']; ?>&hasta=<?php echo $row_evaluacion_vista['periodo_hasta_ev']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="evaluacion_proveedor.php?id_p=<?php echo $row_evaluacion_vista['id_p_ev']; ?>&evaluacion=<?php echo $_GET['evaluacion']; ?>"><img src="images/cat.gif" alt="EVALUACIONES" border="0" /></a><a href="evaluacion_proveedor.php"><img src="images/e.gif" alt="CAMBIAR PROVEEDOR" border="0" style="cursor:hand;"/></a><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
  </tr>
</table>
<table id="tablailimitada" align="center">
<tr>
  <td id="fondo2">  
  <table id="tabladetalle">
<tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
<td id="titular2">EVALUACION DE DESEMPE&Ntilde;O DEL PROVEEDOR <?php echo $row_proveedor['tipo_servicio_p'];?></td>
</tr>

<tr>
  <td id="asignado"><?php echo $row_proveedor['proveedor_p']; ?></td>
</tr>
<tr>
  <td id="numero2">N. <?php echo $row_evaluacion_vista['n_ev']; ?></td>
</tr>
<tr>
  <td id="fondo2">ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6<br>Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
</tr>
</table>
<table id="tabla2">
<tr>
  <td id="subtitulo2">PERIODO DESDE </td>
<td id="subtitulo2">HASTA</td>
<td id="subtitulo2">RESPONSABLE</td>
<td id="subtitulo2">FECHA</td>
</tr>
<tr>
  <td id="dato2"><?php echo $row_evaluacion_vista['periodo_desde_ev']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['periodo_hasta_ev']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['responsable_registro_ev']; ?></td>
  <td id="dato2"><?php echo $row_evaluacion_vista['fecha_registro_ev']; ?></td>
  </tr>
</table></td>
</tr>
<?php 
$desde=$_GET['desde'];
$hasta=$_GET['hasta'];
if($desde != '' && $hasta != '') { 
$vi=$row_verificaciones_insumos['n_vi'];
$vr=$row_rollo_verificacion['n_vr'];
$vb=$row_bolsa_verificacion['n_vb'];
if($vi!='' || $vr!='' || $vb!='') { ?>
<tr>
  <td id="fondo2"><table id="tabladetalle">
  <tr>
    <td rowspan="2" id="nivel2">VERIF.</td>
    <td rowspan="2" id="nivel2">FECHA</td>
    <td rowspan="2" id="nivel2">O.C.</td>
    <td rowspan="2" id="nivel2">MATERIAL</td>
    <td rowspan="2" id="nivel2">SOLICITADO</td>
    <td rowspan="2" id="nivel2">PEDIDO</td>
    <td rowspan="2" id="nivel2">ENTREGA</td>
    <td rowspan="2" id="nivel2">TIEMPO (DIAS)</td>
    <td rowspan="2" id="nivel2">DOCUMENTO</td>
    <td colspan="4" id="nivel2">OPORTUNIDAD  (&lt;=0 dias) </td>
    <td colspan="3" id="nivel2">CANTIDAD (&gt;=90%) </td>
    <td colspan="3" id="nivel2">CALIDAD (&gt;=95%) </td>
    <td colspan="3" id="nivel2">SERVICIO (&gt;=75%) </td>
    </tr>
  <tr>
    <td id="nivel2">RECIBE</td>
    <td id="nivel2">FECHA</td>
    <td id="nivel2">ATRASO</td>
    <td id="nivel2">CUMPLE</td>
    <td id="nivel2">ENCONTRADA</td>
    <td id="nivel2">%</td>
    <td id="nivel2">CUMPLE</td>
    <td id="nivel2">APARIENCIA</td>
    <td id="nivel2">%</td>
    <td id="nivel2">CUMPLE</td>
    <td id="nivel2">CALIFICACION</td>
    <td id="nivel2">%</td>
    <td id="nivel2">CUMPLE</td>
  </tr>

  <?php if($row_verificaciones_insumos['n_vi']!='') { ?>
  <tr>
      <td colspan="11" id="fondo1">Insumos Criticos</td>
      <td colspan="11" id="fondo1">+ 7 DIAS</td>
   </tr>
            <?php do { ?>
              <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
                <td id="detalle3"><?php echo $row_verificaciones_insumos['n_vi']; 
          	  $verificacion1=$row_verificaciones_insumos['n_vi']; 
          		  if($verificacion1!='') { $suma_vr=$suma_vr+1; } ?></td>
                <td nowrap id="detalle2"><?php echo $row_verificaciones_insumos['fecha_vi']; ?></td>
                <td id="detalle3"><?php echo $row_verificaciones_insumos['n_oc_vi']; ?></td>      
                <td nowrap id="abajo1"><?php $id_insumo=$row_verificaciones_insumos['id_insumo_vi'];
          	$sql2="SELECT * FROM insumo WHERE id_insumo='$id_insumo'";
          	$result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
          	if ($num2 >='1') { $descripcion_insumo=mysql_result($result2,0,'descripcion_insumo');	
          	} echo $descripcion_insumo; ?></td>
                <td id="detalle3"><?php echo $row_verificaciones_insumos['cantidad_solicitada_vi']; ?></td>      
                <td nowrap id="detalle2"><?php $n_oc=$row_verificaciones_insumos['n_oc_vi'];
          	$sql2="SELECT * FROM orden_compra WHERE n_oc='$n_oc'";
          	$result2=mysql_query($sql2);
          	$num2=mysql_num_rows($result2);
          	if ($num2 >='1') {
          	$fecha_pedido=mysql_result($result2,0,'fecha_pedido_oc');
          	$fecha_entrega=mysql_result($result2,0,'fecha_entrega_oc');
          	} 
            echo $fecha_pedido; ?>
              
            </td>
                <td nowrap id="detalle2">
                  <?php echo $fecha_entrega; ?>
                </td>
                <td id="detalle2">
                  <?php if($fecha_pedido!='' && $fecha_entrega!='') {
          			  //defino fecha 1 
          			  $ano1 = substr($fecha_pedido,0,4);
          			  $mes1 = substr($fecha_pedido,5,2); 
          			  $dia1 = substr($fecha_pedido,8,2); 
          			  //defino fecha 2 
          			  $ano2 = substr($fecha_entrega,0,4); 
          			  $mes2 = substr($fecha_entrega,5,2); 
          			  $dia2 = substr($fecha_entrega,8,2); 
          			  //calculo timestam de las dos fechas 
          			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
          			  $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
          			  //resto a una fecha la otra 
          			  $segundos_diferencia = $timestamp1 - $timestamp2; 
          			  //echo $segundos_diferencia; 
          			  //convierto segundos en días 
          			  $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
          			  //obtengo el valor absoulto de los días (quito el posible signo negativo)
          			  $dias_diferencia = abs($dias_diferencia); 
          			  //quito los decimales a los días de diferencia 
          			  $dias_diferencia= floor($dias_diferencia);			 
          			  echo $dias_diferencia; } ?></td>      
          	  <td id="detalle2">
                <?php 
          	       $factura=$row_verificaciones_insumos['factura_vi'];
          	       $remision=$row_verificaciones_insumos['remision_vi'];
          	  if($factura!='') { echo $factura; } else{ echo $remision; } ?> 
              </td>
                <td id="detalle2"><?php echo $row_verificaciones_insumos['entrega_vi']; ?></td>
                <td nowrap id="detalle2"><?php echo $row_verificaciones_insumos['fecha_registro_vi']; ?></td>
                <td id="detalle2" title="RESTA REGISTRO VERIFICACION  - FECHA RECIBIDO DE O.C CON 7 DIAS DE GRACIA">
                <?php 
                $fecha_recibe=$row_verificaciones_insumos['fecha_registro_vi'];
                $fecha_recibe = RestaDias($fecha_recibe,'7');

          			if($fecha_recibe!='' && $fecha_entrega!='') {
          			  //defino fecha 1 
          			  $ano1 = substr($fecha_recibe,0,4);
          			  $mes1 = substr($fecha_recibe,5,2); 
          			  $dia1 = substr($fecha_recibe,8,2); 
          			  //defino fecha 2 
          			  $ano2 = substr($fecha_entrega,0,4); 
          			  $mes2 = substr($fecha_entrega,5,2); 
          			  $dia2 = substr($fecha_entrega,8,2); 
          			  //calculo timestam de las dos fechas 
          			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
          			  $timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
          			  //resto a una fecha la otra 
          			  $segundos_diferencia = $timestamp1 - $timestamp2; 
          			  //echo $segundos_diferencia; 
          			  //convierto segundos en días 
          			  $dias_diferencia2 = $segundos_diferencia / (60 * 60 * 24); 
          			  //obtengo el valor absoulto de los días (quito el posible signo negativo)
          			  //$dias_diferencia2 = abs($dias_diferencia2);	   
          			  //quito los decimales a los días de diferencia 
          			  $dias_diferencia2= floor($dias_diferencia2);
          			  echo $dias_diferencia2; } ?></td>
                <td id="detalle2"><?php $entrega=$row_verificaciones_insumos['entrega_vi'];
          			if($entrega=='TOTAL' && $dias_diferencia2=='0')
          			{ echo "Si"; $oportunos=$oportunos+1; }
          			if($entrega=='TOTAL' && $dias_diferencia2 < '0')
          			{ echo "Si"; $oportunos=$oportunos+1; }			
          			if($entrega=='TOTAL' && $dias_diferencia2 > '0')
          			{ echo "No"; $noportunos=$noportunos+1; }
          			if($entrega=='PARCIAL' && $dias_diferencia2 == '0')
          			{ echo "Si"; $oportunos=$oportunos+1; }			
          			if($entrega=='PARCIAL' && $dias_diferencia2 < '0')
          			{ echo "Si"; $oportunos=$oportunos+1; }
          			if($entrega=='PARCIAL' && $dias_diferencia2 > '0')
          			{ echo "No"; $noportunos=$noportunos+1; } ?></td>
                <td id="detalle3"><?php echo $row_verificaciones_insumos['cantidad_recibida_vi']; ?></td>
                <td nowrap id="detalle3"><?php	
          	  $cant1=$row_verificaciones_insumos['cantidad_solicitada_vi'];
          	  $cant2=$row_verificaciones_insumos['cantidad_recibida_vi'];
          	  if($cant1!='' && $cant2!='') { $cant3=($cant2/$cant1)*100; $cant3=round($cant3*100)/100; 
          	  if($cant3>'100') { $cant3=100; } } else { $cant3=0; } echo $cant3; ?>%</td>
                <td id="detalle2"><?php	if($cant3=='90') { echo "Si"; $cumple=$cumple+1; }
          	  if($cant3>'90') { echo "Si"; $cumple=$cumple+1; }
          	  if($cant3<'90') { echo "No"; $nocumple=$nocumple+1; } ?></td>
                <td id="detalle3"><?php $apariencia=$row_verificaciones_insumos['apariencia_vi'];
          	if($apariencia=='0') { echo "Mala"; }
          	if($apariencia=='0.5') { echo "Regular"; }
          	if($apariencia=='1') { echo "Buena"; } ?></td>
                <td id="detalle3"><?php 
          	  if($apariencia=='0') { $porcentaje_conf=0; echo "0%"; }
          	  if($apariencia=='0.5') { $porcentaje_conf=50; echo "50%"; }
          	  if($apariencia=='1') { $porcentaje_conf=100; echo "100%"; } ?></td>
                <td id="detalle2"><?php if($porcentaje_conf>='95') { echo "Si"; $abuena=$abuena+1; }
          	  if($porcentaje_conf<'95') { echo "No"; $amala=$amala+1; } ?></td>
                <td id="detalle3"><?php $cump_servicio=$row_verificaciones_insumos['servicio_vi'];
          	  echo $cump_servicio; ?></td>
                <td nowrap id="detalle3"><?php if($cump_servicio!='') { $porc_servicio=($cump_servicio/10)*100;
          	  $porc_servicio=round($porc_servicio*100)/100; } else { $porc_servicio=0; } echo $porc_servicio; ?>%</td>
                <td id="detalle2"><?php if($porc_servicio>='75') { echo "Si"; $serviciosi=$serviciosi+1; }
          	  if($porc_servicio<'75') { echo "No"; $serviciono=$serviciono+1; } ?></td>
          	  </tr><?php } while ($row_verificaciones_insumos = mysql_fetch_assoc($verificaciones_insumos)); ?><?php } ?><?php if($row_rollo_verificacion['n_vr']!='') { ?>
          	<tr>	



	  <tr>
        <td colspan="22" id="fondo1">Materia Prima Rollos </td>
      </tr>
      <?php do { ?>
        <tr>
          <td id="detalle3"><?php echo $row_rollo_verificacion['n_vr']; ?></td>
          <td id="detalle2"><?php echo $row_rollo_verificacion['fecha_recibo_vr']; ?></td>
          <td id="detalle3"><?php echo $row_rollo_verificacion['n_ocr_vr']; ?></td>
          <td nowrap id="detalle1"><?php $id_rollo=$row_rollo_verificacion['id_rollo_vr'];
	$sql2="SELECT * FROM materia_prima_rollos WHERE id_rollo='$id_rollo'";
	$result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
	if ($num2 >='1') { $nombre_rollo=mysql_result($result2,0,'nombre_rollo'); } ?>
            <?php echo $nombre_rollo; ?></td>
          <td id="detalle3"><?php echo $row_rollo_verificacion['cantidad_solicitada_vr']; ?></td>
          <td id="detalle3"><?php $n_ocr=$row_rollo_verificacion['n_ocr_vr'];
	$sql2="SELECT * FROM orden_compra_rollos WHERE n_ocr='$n_ocr'";
	$result2=mysql_query($sql2); $num2=mysql_num_rows($result2);
	if ($num2 >='1') {
	$fecha_pedido=mysql_result($result2,0,'fecha_pedido_ocr');
	$fecha_entrega=mysql_result($result2,0,'fecha_entrega_ocr'); } ?><?php echo $fecha_pedido; ?></td>
          <td id="detalle2"><?php echo $fecha_entrega; ?></td>
          <td id="detalle2"><?php if($fecha_pedido!='' && $fecha_entrega!='') {
			  //defino fecha 1 
			  $ano1 = substr($fecha_pedido,0,4);
			  $mes1 = substr($fecha_pedido,5,2); 
			  $dia1 = substr($fecha_pedido,8,2); 
			  //defino fecha 2 
			  $ano2 = substr($fecha_entrega,0,4); 
			  $mes2 = substr($fecha_entrega,5,2); 
			  $dia2 = substr($fecha_entrega,8,2); 
			  //calculo timestam de las dos fechas 
			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			  $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
			  //resto a una fecha la otra 
			  $segundos_diferencia = $timestamp1 - $timestamp2; 
			  //echo $segundos_diferencia; 
			  //convierto segundos en d&iacute;as 
			  $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
			  //obtengo el valor absoulto de los d&iacute;as (quito el posible signo negativo)
			  $dias_diferencia = abs($dias_diferencia); 
			  //quito los decimales a los d&iacute;as de diferencia 
			  $dias_diferencia= floor($dias_diferencia);			 
			  echo $dias_diferencia; } ?></td>
          <td id="detalle2"><?php 
	  $factura=$row_rollo_verificacion['factura_vr'];
	  $remision=$row_rollo_verificacion['remision_vr'];
	  if($factura!='') { echo $factura; } else{ echo $remision; } ?></td>
          <td id="detalle2"><?php if($row_rollo_verificacion['entrega_vr']=='0') { echo "PARCIAL"; }
		if($row_rollo_verificacion['entrega_vr']=='1') { echo "TOTAL"; } ?></td>
          <td id="detalle2"><?php echo $row_rollo_verificacion['fecha_recibo_vr']; ?></td>
          <td id="detalle2"><?php $fecha_recibe=$row_rollo_verificacion['fecha_recibo_vr'];
			if($fecha_recibe!='' && $fecha_entrega!='') {
			  //defino fecha 1 
			  $ano1 = substr($fecha_recibe,0,4);
			  $mes1 = substr($fecha_recibe,5,2); 
			  $dia1 = substr($fecha_recibe,8,2); 
			  //defino fecha 2 
			  $ano2 = substr($fecha_entrega,0,4); 
			  $mes2 = substr($fecha_entrega,5,2); 
			  $dia2 = substr($fecha_entrega,8,2); 
			  //calculo timestam de las dos fechas 
			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			  $timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
			  //resto a una fecha la otra 
			  $segundos_diferencia = $timestamp1 - $timestamp2; 
			  //echo $segundos_diferencia; 
			  //convierto segundos en d&iacute;as 
			  $dias_diferencia2 = $segundos_diferencia / (60 * 60 * 24); 
			  //obtengo el valor absoulto de los d&iacute;as (quito el posible signo negativo)
			  //$dias_diferencia2 = abs($dias_diferencia2);	   
			  //quito los decimales a los d&iacute;as de diferencia 
			  $dias_diferencia2= floor($dias_diferencia2);
			  echo $dias_diferencia2; } ?></td>
          <td id="detalle2"><?php $entrega=$row_rollo_verificacion['entrega_vr'];
		  if($entrega=='1' && $dias_diferencia2=='0') 
		  { echo "Si"; $oportunos=$oportunos+1; }
		  if($entrega=='1' && $dias_diferencia2<'0')
		  { echo "Si"; $oportunos=$oportunos+1; }
		  if($entrega=='1' && $dias_diferencia2>'0')
		  { echo "No"; $noportunos=$noportunos+1; }
          if($entrega=='0' && $dias_diferencia2=='0')
		  { echo "Si";	$oportunos=$oportunos+1; }
		  if($entrega=='0' && $dias_diferencia2<'0')
		  { echo "Si";	$oportunos=$oportunos+1; }
		  if($entrega=='0' && $dias_diferencia2>'0')
		  { echo "No"; $noportunos=$noportunos+1; }	?></td>
          <td id="detalle3"><?php echo $row_rollo_verificacion['cantidad_encontrada_vr']; ?></td>
          <td nowrap id="detalle3"><?php //Porcentaje de cumplimiento
		$cant1=$row_rollo_verificacion['cantidad_solicitada_vr'];
		$cant2=$row_rollo_verificacion['cantidad_encontrada_vr'];
		if($cant1!='' && $cant2!='') {
		   $cant3 = ($cant2/$cant1)*100;
		   $cant3=round($cant3*100)/100;
		   if($cant3 > '100') { $cant3=100; } } else{ $cant3=0; } echo $cant3; ?>%</td>
          <td id="detalle2"><?php 
			if($cant3>='90') { echo "Si"; $cumple=$cumple+1;	}
			if($cant3<'90') { echo "No"; $nocumple=$nocumple+1; } ?></td>
          <td id="detalle3"><?php $conformidad=0; $cont=0;		
		 $cantidad=$row_rollo_verificacion['cantidad_cumple_vr'];
		 if($cantidad!='2') { $conformidad=$cantidad+$conformidad; $cont=$cont+1; }
		 $calibre=$row_rollo_verificacion['calibre_cumple_vr'];
		 if($calibre!='2') { $conformidad=$calibre+$conformidad; $cont=$cont+1; }
		 $peso=$row_rollo_verificacion['peso_cumple_vr'];
		 if($peso!='2') { $conformidad=$peso+$conformidad; $cont=$cont+1; }
		 $ancho=$row_rollo_verificacion['ancho_cumple_vr'];
		 if($ancho!='2') { $conformidad=$ancho+$conformidad; $cont=$cont+1; }
		 $repeticion=$row_rollo_verificacion['rodillo_cumple_vr'];
		 if($repeticion!='2') { $conformidad=$repeticion+$conformidad; $cont=$cont+1; }
		 $tto=$row_rollo_verificacion['tratamiento_cump_vr'];
		 if($tto!='2') { $conformidad=$tto+$conformidad; $cont=$cont+1; }
		 $md=$row_rollo_verificacion['md_cumple_vr'];
		 if($md!='2') { $conformidad=$md+$conformidad; $cont=$cont+1; }
		 $td=$row_rollo_verificacion['td_cumple_vr'];
		 if($td!='2') { $conformidad=$td+$conformidad; $cont=$cont+1; }
		 $angulo=$row_rollo_verificacion['angulo_cumple_vr'];
		 if($angulo!='2') { $conformidad=$angulo+$conformidad; $cont=$cont+1; }
		 $fuerza=$row_rollo_verificacion['fuerzaselle_cumple_vr'];
		 if($fuerza!='2') { $conformidad=$fuerza+$conformidad; $cont=$cont+1; }
		 $apariencia=$row_rollo_verificacion['apariencia_cumple_vr'];
		 if($apariencia!='2') { $conformidad=$apariencia+$conformidad; $cont=$cont+1; }
		 $sellos=$row_rollo_verificacion['resistencia_sellos_cumple_vr'];
		 if($sellos!='2') { $conformidad=$sellos+$conformidad; $cont=$cont+1; }
		 $impresion=$row_rollo_verificacion['impresion_cumple_vr'];
		 if($impresion!='2') { $conformidad=$impresion+$conformidad; $cont=$cont+1; }
		 $color=$row_rollo_verificacion['color_cumple_vr'];
		 if($color!='2') { $conformidad=$color+$conformidad; $cont=$cont+1; }
		 $tinta=$row_rollo_verificacion['tinta_cumple_vr'];
		 if($tinta!='2') { $conformidad=$tinta+$conformidad; $cont=$cont+1; }
		 echo $conformidad; ?></td>
          <td nowrap id="detalle3"><?php if($conformidad!='') {
		  $porcentaje1=($conformidad/$cont)*100; $porcentaje_conf=floor($porcentaje1); } 
		  else { echo $porcentaje_conf=0; } echo $porcentaje_conf; ?>%</td>
          <td id="detalle2"><?php if($porcentaje_conf>='95') { echo "Si"; $abuena=$abuena+1; }
			  if($porcentaje_conf<'95') { echo "No"; $amala=$amala+1; } ?></td>
          <td id="detalle3"><?php $cump_servicio=$row_rollo_verificacion['servicio_vr']; 
		  echo $cump_servicio; ?></td>
          <td nowrap id="detalle3"><?php if($cump_servicio!='') {
			  $porc_servicio=($cump_servicio/10)*100; $porc_servicio=floor($porc_servicio); }
			  else{ $porc_servicio=0; } echo $porc_servicio; ?>%</td>
          <td id="detalle2"><?php if($porc_servicio>='75') { echo "Si"; $serviciosi=$serviciosi+1; }
		  if($porc_servicio<'75') { echo "No"; $serviciono=$serviciono+1; } ?></td>
        </tr>
        <?php } while ($row_rollo_verificacion = mysql_fetch_assoc($rollo_verificacion)); ?>
      <?php } ?><?php if($row_bolsa_verificacion['n_vb']!='') { ?>
	  <tr>
        <td colspan="22" id="fondo1">Producto Terminado Bolsas</td>
      </tr>
      <?php do { ?>
        <tr>
          <td id="detalle3"><?php echo $row_bolsa_verificacion['n_vb']; ?></td>
          <td id="detalle3"><?php echo $row_bolsa_verificacion['fecha_recibido_vb']; ?></td>
          <td id="detalle3"><?php echo $row_bolsa_verificacion['n_ocb_vb']; ?></td>
          <td id="detalle1"><?php $id_bolsa=$row_bolsa_verificacion['id_bolsa_vb'];
	  $sqlbolsa="SELECT * FROM material_terminado_bolsas WHERE id_bolsa='$id_bolsa'";
	  $resultbolsa=mysql_query($sqlbolsa);
	  $numbolsa=mysql_num_rows($resultbolsa);
	  if($numbolsa>='1') { $nombre_bolsa=mysql_result($resultbolsa,0,'nombre_bolsa'); }
	  echo $nombre_bolsa; ?></td>
          <td id="detalle3"><?php echo $row_bolsa_verificacion['cantidad_solicitada_vb']; ?></td>
          <td id="detalle2"><?php $n_ocb=$row_bolsa_verificacion['n_ocb_vb'];
		$sqlocb="SELECT * FROM orden_compra_bolsas WHERE n_ocb='$n_ocb'";
		$resultocb=mysql_query($sqlocb);
		$numocb=mysql_num_rows($resultocb);
		if($numocb>='1') {
			$fecha_pedido=mysql_result($resultocb,0,'fecha_pedido_ocb');
			$fecha_entrega=mysql_result($resultocb,0,'fecha_entrega_ocb');
		} echo $fecha_pedido; ?></td>
          <td id="detalle2"><?php echo $fecha_entrega; ?></td>
          <td id="detalle2"><?php if($fecha_pedido!='' && $fecha_entrega!='') {
			  //defino fecha 1 
			  $ano1 = substr($fecha_pedido,0,4);
			  $mes1 = substr($fecha_pedido,5,2); 
			  $dia1 = substr($fecha_pedido,8,2); 
			  //defino fecha 2 
			  $ano2 = substr($fecha_entrega,0,4); 
			  $mes2 = substr($fecha_entrega,5,2); 
			  $dia2 = substr($fecha_entrega,8,2); 
			  //calculo timestam de las dos fechas 
			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			  $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2); 
			  //resto a una fecha la otra 
			  $segundos_diferencia = $timestamp1 - $timestamp2; 
			  //echo $segundos_diferencia; 
			  //convierto segundos en d&iacute;as 
			  $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); 
			  //obtengo el valor absoulto de los d&iacute;as (quito el posible signo negativo)
			  $dias_diferencia = abs($dias_diferencia); 
			  //quito los decimales a los d&iacute;as de diferencia 
			  $dias_diferencia= floor($dias_diferencia);			 
			  echo $dias_diferencia; } ?></td>
          <td id="detalle2"><?php $factura=$row_bolsa_verificacion['factura_vb'];
	  $remision=$row_bolsa_verificacion['remision_vb'];
	  if($factura!='') { echo $factura; } else { echo $remision; } ?></td>
          <td id="detalle2"><?php if($row_bolsa_verificacion['entrega_vb']=='0') { echo "PARCIAL"; }
	  if($row_bolsa_verificacion['entrega_vb']=='1') { echo "TOTAL"; } ?></td>
          <td id="detalle2"><?php echo $row_bolsa_verificacion['fecha_recibido_vb']; ?></td>
          <td id="detalle2"><?php $fecha_recibe=$row_bolsa_verificacion['fecha_recibido_vb'];
			if($fecha_recibe!='' && $fecha_entrega!='') {
			  //defino fecha 1 
			  $ano1 = substr($fecha_recibe,0,4);
			  $mes1 = substr($fecha_recibe,5,2); 
			  $dia1 = substr($fecha_recibe,8,2); 
			  //defino fecha 2 
			  $ano2 = substr($fecha_entrega,0,4); 
			  $mes2 = substr($fecha_entrega,5,2); 
			  $dia2 = substr($fecha_entrega,8,2); 
			  //calculo timestam de las dos fechas 
			  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1); 
			  $timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2); 
			  //resto a una fecha la otra 
			  $segundos_diferencia = $timestamp1 - $timestamp2; 
			  //echo $segundos_diferencia; 
			  //convierto segundos en d&iacute;as 
			  $dias_diferencia2 = $segundos_diferencia / (60 * 60 * 24); 
			  //obtengo el valor absoulto de los d&iacute;as (quito el posible signo negativo)
			  //$dias_diferencia2 = abs($dias_diferencia2);	   
			  //quito los decimales a los d&iacute;as de diferencia 
			  $dias_diferencia2= floor($dias_diferencia2);
			  echo $dias_diferencia2; } ?></td>
          <td id="detalle2"><?php $entrega=$row_bolsa_verificacion['entrega_vb'];
		  if($entrega=='1' && $dias_diferencia2=='0')
		  { echo "Si"; $oportunos=$oportunos+1; }
		  if($entrega=='1' && $dias_diferencia2<'0')
		  { echo "Si"; $oportunos=$oportunos+1; }
		  if($entrega=='1' && $dias_diferencia2>'0')
		  { echo "No"; $noportunos=$noportunos+1; }
		  if($entrega=='0' && $dias_diferencia2=='0')
		  { echo "Si"; $oportunos=$oportunos+1; }
		  if($entrega=='0' && $dias_diferencia2<'0')
		  { echo "Si"; $oportunos=$oportunos+1; }
		  if($entrega=='0' && $dias_diferencia2>'0')
		  { echo "No"; $noportunos=$noportunos+1; } ?></td>
          <td id="detalle3"><?php echo $row_bolsa_verificacion['cantidad_encontrada_vb']; ?></td>
          <td nowrap id="detalle3"><?php //Porcentaje de cumplimiento
		$cant1=$row_bolsa_verificacion['cantidad_solicitada_vb'];
		$cant2=$row_bolsa_verificacion['cantidad_encontrada_vb'];
		if($cant1!='' && $cant2!='') { $cant3=($cant2/$cant1)*100; $cant3=round($cant3*100)/100;
		if($cant3>'100') { $cant3=100; } } else{ $cant3=0; } echo $cant3; ?>%</td>
          <td id="detalle2"><?php if($cant3>='90') { echo "Si"; $cumple=$cumple+1;	}
			if($cant3<'90') { echo "No"; $nocumple=$nocumple+1; } ?></td>
          <td id="detalle3"><?php $conformidad=0; $cont=0;
	  $cantidad=$row_bolsa_verificacion['cantidad_cumple_vb'];
	  if($cantidad!='2') { $conformidad=$cantidad+$conformidad; $cont=$cont+1; }
	  $calibre=$row_bolsa_verificacion['calibre_cumple_vb'];
	  if($calibre!='2') { $conformidad=$calibre+$conformidad; $cont=$cont+1; }
	  $ancho=$row_bolsa_verificacion['ancho_cumple_vb'];
	  if($ancho!='2') { $conformidad=$ancho+$conformidad; $cont=$cont+1; }
	  $largo=$row_bolsa_verificacion['largo_cumple_vb'];
	  if($largo!='2') { $conformidad=$largo+$conformidad; $cont=$cont+1; }
	  $solapa=$row_bolsa_verificacion['solapa_cumple_vb'];
	  if($solapa!='2') { $conformidad=$solapa+$conformidad; $cont=$cont+1; }
	  $fuelle=$row_bolsa_verificacion['fuelle_cumple_vb'];
	  if($fuelle!='2') { $conformidad=$fuelle+$conformidad; $cont=$cont+1; }
	  $empaque=$row_bolsa_verificacion['empaque_cumple_vb'];
	  if($empaque!='2') { $conformidad=$empaque+$conformidad; $cont=$cont+1; }
	  $apariencia=$row_bolsa_verificacion['apariencia_cumple_vb'];
	  if($apariencia!='2') { $conformidad=$apariencia+$conformidad; $cont=$cont+1; }
	  $resistencia=$row_bolsa_verificacion['resistencia_cumple_vb'];
	  if($resistencia!='2') { $conformidad=$resistencia+$conformidad; $cont=$cont+1; }
	  $tratamiento=$row_bolsa_verificacion['tratamiento_cumple_vb'];
	  if($tratamiento!='2') { $conformidad=$tratamiento+$conformidad; $cont=$cont+1; }
	  echo $conformidad; ?></td>
          <td nowrap id="detalle3"><?php $porcentaje1=($conformidad/$cont)*100;			  
			  $porcentaje_conf=floor($porcentaje1); echo $porcentaje_conf; ?>%</td>
          <td id="detalle2"><?php if($porcentaje_conf>='95') { echo "Si"; $abuena=$abuena+1; }
			  if($porcentaje_conf<'95') { echo "No"; $amala=$amala+1; } ?></td>
          <td id="detalle3"><?php $cump_servicio=$row_bolsa_verificacion['servicio_vb'];
		  echo $cump_servicio; ?></td>
          <td nowrap id="detalle3"><?php $porc_servicio=($cump_servicio/10)*100;
			  $porc_servicio = floor($porc_servicio); echo $porc_servicio; ?>%</td>
          <td id="detalle2"><?php if($porc_servicio>='75') { echo "Si"; $serviciosi=$serviciosi+1; }
		  if($porc_servicio<'75') { echo "No"; $serviciono=$serviciono+1; } ?></td>
        </tr><?php } while ($row_bolsa_verificacion = mysql_fetch_assoc($bolsa_verificacion)); ?>
		<?php } ?>
	  <tr>
        <td colspan="22" id="fondo1">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="22" id="fondo1">Se evaluan cuatro clases de evaluacion   sobre un 25% cada uno, para completar 100% del porcentaje final.</td>
        </tr>
      <tr>
        <td colspan="2" id="fondo4">ORDEN COMPRA  </td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_oc_ev']; ?></td>
        <td colspan="9" nowrap id="fondo4">OPORTUNOS</td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_oportunos_ev']; ?></td>
        <td colspan="2" id="fondo4">CUMPLE</td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_cumple_ev']; ?></td>
        <td colspan="2" id="fondo4">CONFORME</td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_conforme_ev']; ?></td>
        <td colspan="2" id="fondo4">ATENCION</td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_atencion_ev']; ?></td>
      </tr>
      <tr>
        <td colspan="2" id="fondo4">VERIFICACIONES </td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_verificacion_ev']; ?></td>
        <td colspan="9" nowrap id="fondo4">NO OPORTUNOS </td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_no_oportunos_ev']; ?></td>
        <td colspan="2" id="fondo4">NO CUMPLE </td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_no_cumple_ev']; ?></td>
        <td colspan="2" nowrap id="fondo4">NO CONFORME </td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_no_conforme_ev']; ?></td>
        <td colspan="2" nowrap id="fondo4">NO ATENCION </td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['total_no_atencion_ev']; ?></td>
      </tr>
      <tr>
        <td colspan="3" id="fondo4">&nbsp;</td>
        <td colspan="9" nowrap id="fondo4">% OPORTUNOS </td>
        <td nowrap id="detalle3"><?php echo $row_evaluacion_vista['porcentaje_oportunos_ev']; ?>%</td>
        <td colspan="2" id="fondo4">% CUMPLE </td>
        <td nowrap id="detalle3"><?php echo $row_evaluacion_vista['porcentaje_cumple_ev']; ?>%</td>
        <td colspan="2" id="fondo4">% CONFORME </td>
        <td nowrap id="detalle3"><?php echo $row_evaluacion_vista['porcentaje_conforme_ev']; ?>%</td>
        <td colspan="2" nowrap id="fondo4">% ATENCION </td>
        <td nowrap id="detalle3"><?php echo $row_evaluacion_vista['porcentaje_atencion_ev']; ?>%</td>
      </tr>
      <tr>
        <td colspan="2" id="fondo4">% CALIFICACION </td>
        <td id="detalle3"><?php echo $row_evaluacion_vista['porcentaje_final_ev']; ?>%</td>
        <td colspan="19" nowrap id="fondo1">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="22" id="justificar"><?php echo $row_evaluacion_vista['calificacion_texto_ev']; ?></td>
        </tr>       
</table></td></tr><?php } else { ?>
<tr>
<td id="asignado">No hay datos registrados referentes a esta evaluacion.</td>
</tr><?php } } ?>
<tr><td id="linea1" align="center">  
<table id="tabla2">
  <tr>
    <td id="subtitulo1">CODIGO : A3 - F05</td>
    <td id="subtitulo2">PRODUCTOS CRITICOS</td>
    <td id="subtitulo3">VERSION : 1</td>
  </tr>
</table>
</td>
</tr>
</table>
</div> 
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($proveedores);

mysql_free_result($proveedor);

mysql_free_result($evaluacion_vista);

mysql_free_result($verificaciones_insumos);

mysql_free_result($rollo_verificacion);

mysql_free_result($bolsa_verificacion);
?>