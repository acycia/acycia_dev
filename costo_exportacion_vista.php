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

$colname_costoExp = "-1";
if (isset($_GET['n_ce'])) {
  $colname_costoExp = (get_magic_quotes_gpc()) ? $_GET['n_ce'] : addslashes($_GET['n_ce']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_costoExp = sprintf("SELECT * FROM TblCostoExportacion WHERE n_ce = %s", $colname_costoExp);
$costoExp = mysql_query($query_costoExp, $conexion1) or die(mysql_error());
$row_costoExp = mysql_fetch_assoc($costoExp);
$totalRows_costoExp = mysql_num_rows($costoExp);

$colname_cliente = "-1";
if (isset($_GET['n_ce'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['n_ce'] : addslashes($_GET['n_ce']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM TblCostoExportacion, cliente WHERE TblCostoExportacion.n_ce = '%s' AND TblCostoExportacion.id_c_ce = cliente.id_c", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$colname_detalle = "-1";
if (isset($_GET['n_ce'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['n_ce'] : addslashes($_GET['n_ce']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_detalle = sprintf("SELECT * FROM TblCostoExportacionDetalle WHERE TblCostoExportacionDetalle.n_ce_det = '%s'", $colname_detalle);
$detalle = mysql_query($query_detalle, $conexion1) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#tabla1 tr td #tabla2 tr #fondo2 p {
	text-align: left;
}
</style>
<script type="text/javascript" src="js/vista.js"></script>
</head>
<body>
<div align="center">
<table id="tabla2">
  <tr>
    <td id="noprint" align="right"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="costo_exportacion_edit.php?n_ce=<?php echo $row_costoExp['n_ce'];?>&id_c_ce=<?php echo $row_cliente['id_c_ce']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="costo_exportacion_listado.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO FACTURAS" title="LISTADO FACTURAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
  </tr>
</table>
<table id="tabla1"><tr><td align="center">
<table id="tabla2">
<tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
<td id="titulo1">FACTURA EXPORTACION</td>
</tr>

<tr>
  <td id="titular2">&nbsp;</td>
</tr>
<tr>
  <td id="numero2"> FACTURA N° <strong><?php echo $row_costoExp['n_ce']; ?></strong></td>
</tr>
<tr>
  <td id="fondo1">&nbsp;</td>
</tr>
</table>
<table id="tabla2">
<tr>
  <td colspan="4" id="dato2">INFORMACION DEL CLIENTE</td>
  </tr>
<tr>
     <td colspan="2" id="dato1">NIT : <?php echo $row_cliente['nit_c']; ?></td>
      <td colspan="2" id="dato1">Telefono : <?php echo $row_cliente['telefono_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="dato1">Contacto Comercial : <?php echo $row_cliente['contacto_c']; ?></td>
      <td colspan="2" id="dato1">Fax : <?php echo $row_cliente['fax_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" nowrap id="dato1">Cargo : <?php echo $row_cliente['cargo_contacto_c']; ?></td>
      <td colspan="2" id="dato1">Pa&iacute;s : <?php echo $row_cliente['pais_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="dato1">Email : <?php echo $row_cliente['email_comercial_c']; ?></td>
      <td colspan="2" id="dato1">Ciudad : <?php echo $row_cliente['ciudad_c']; ?></td>
    </tr>
    <tr>
      <td colspan="4" id="dato1">Direcci&oacute;n : <?php echo $row_cliente['direccion_c']; ?></td>
    </tr>
            <tr>
              <td colspan="4" id="dato2">INFORMACION ADICIONAL</td>
            </tr>

          <tr>
            <td id="fuente1">Pedido N&deg;</td>
    <td id="dato1"><?php echo $row_costoExp['pedido_ce'];?>
     
    </select></td>
            <td id="dato1">Incoterm</td>
            <td id="fuente1"><?php echo $row_costoExp['incoterm_ce']; ?></td>
            </tr>
		  <tr>
		    <td id="fuente1">Lugar Expedicion		      </td>
		    <td id="dato1"><?php echo $row_costoExp['lugarExp_ce']; ?></td>
		    <td id="dato1">Zona </td>
		    <td id="fuente1"><?php echo $row_costoExp['zona_ce']; ?></td>
		    </tr>
          <tr>
		    <td id="fuente1">Consignado A</td>
		    <td id="dato1"><?php echo $row_costoExp['consignado_ce']; ?></td>
		    <td id="dato1">Forma de Pago</td>
		    <td id="fuente1"><?php echo $row_costoExp['cond_pago_ce'];?>
              </td>
		    </tr>    
</table>
<table id="tabla2">
              <tr>
                <td id="nivel2">CODIGO</td>
                <td id="nivel2">CANT.</td>
                <td id="nivel2">DESCRIPCION</td>
                <td id="nivel2">MEDIDA</td>
                <td id="nivel2">PRECIO UND/MILL.</td>
                <td id="nivel2">TOTAL</td>
          </tr>
  <?php do { ?>
                <tr>
                  <td id="detalle1">
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
				  ?></td>
                  <td id="detalle1"><?php echo $row_detalle['cantidad_det']; ?></td>
                  <td id="detalle1"><?php echo $row_detalle['descripcion_det']; ?></td>
                  <td id="detalle1"><?php echo $row_detalle['medida_det'];?></td>
                  <td id="detalle3"><?php echo $row_detalle['precio_unid_det']; ?></td>
                  <td id="detalle3"><?php echo $row_detalle['valor_total_det']; $subtotal=$subtotal+$row_detalle['valor_total_det'];?></td>
                </tr>
    <?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>
                <tr>
                  <td colspan="6" id="fondo1">&nbsp;</td>
                </tr>	
    <tr>
	  <td colspan="3" id="nivel1">OBSERVACIONES</td>
	  <td colspan="2" id="nivel3"><strong>SUBTOTAL</strong></td>
	  <td id="detalle3"><?php echo $row_costoExp['subtotal_ce']; ?></td>
	  </tr>
	<tr>
	  <td colspan="3" rowspan="3" id="detalle1">- <?php echo $row_costoExp['observacion_ce']; ?> - </td>
	  <td colspan="2" id="nivel3">FLETES</td>
	  <td id="detalle3"><?php echo $row_costoExp['flete_ce'];?></td></tr>
	<tr>
	  <td colspan="2" id="nivel3">SEGURO</td>
	  <td id="detalle3"><?php echo $row_costoExp['seguro_ce'];?></td>
	  </tr>
	<tr>
	  <td colspan="2" id="nivel3">TOTAL</td>
	  <td id="detalle3"><?php echo $row_costoExp['total_ce']; ?></td>
	  </tr>
</table>
<table id="tabla2">
  <tr>
    <td id="nivel2">ELABORADO POR </td>
    <td id="nivel2"><p>FIRMA &amp; SELLO ACYCIA </p>    </td>
    </tr>
  <tr>
    <td id="detalle2"><?php echo $row_costoExp['responsable_ce']; ?></td>
    <td id="detalle2">&nbsp;</td>
    </tr>
</table>
<table id="tabla2">
  <tr>
    <td colspan="3" id="fondo1"><p><strong>Esta mercancia fue fabricada en Medellin - Colombia y la moneda de fabricacion es el Dolar Americano.</strong>      </p>
      <p>OBSERVACIONES: FACTURA VENCIDA CAUSA INTERES POR MORA MENSUAL A LA TASA MAXIMA LEGAL PERMITIDA. CONTRIBUYENTES INDUSTRIAL - COMERCIO EN MEDELLIN ACT. ECO. 5131 TARIFA 08.0. ESTA FACTURA CAMBIARIA SE ASIMILA EN SUS EFECTOS A UNA LETRA DE CAMBIO (ART. 772 C. DE C.)</p>
      <p>PARA CANCELAR, FAVOR GIRAR CHEQUE EN DOLARES AMERICANOS A NOMBRE DE ALBERTO CADAVID R. &amp; CIA. S.A. O CONSIGNAR EN NUESTRA CUENTA CORRIENTE EN LA SIGUIENTE DIRECCION.</p>
      <p>&nbsp;</p></td>
    </tr>
  <tr>
    <td colspan="3" id="fondo2">CITIBANK N.Y. (U.S.A) ABA 021000089 SWIFT CITIUS 33 CHIP 0008<br>Beneficiario: Bancolombia Cayman Cuenta N&deg; 36016071lk<br> Para Credito Final a: Alberto Cadavid R. &amp; CIA. S.A. Cuenta N&deg; 70471</td>
  </tr>
  <tr>
    <td colspan="3" id="fondo2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" id="fondo2"><strong>ALBERTO CADAVID R. &amp; CIA. S.A.<br>
    Nit. 890.915.756-6<br>
    _________________________________________________________________________<br>
    PBX: (57-4) 3112144 - FAX: (57-4) 3524330  - www.acycia.com<br>
    Carrera 45 # 14 - 15 Sector Barrio Colombia<br></strong>
    Medellin - Colombia</td>
  </tr>
  <tr>
    <td id="fondo1">CODIGO : A3 - F02</td>
    <td id="fondo1">&nbsp;</td>
    <td id="fondo3">VERSION : 1</td>
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

mysql_free_result($orden_compra);

mysql_free_result($proveedor_oc);

mysql_free_result($detalle);
?>