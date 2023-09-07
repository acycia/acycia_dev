<?php require_once('Connections/conexion1.php'); ?>
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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_bolsa_oc = "-1";
if (isset($_GET['n_ocb'])) {
  $colname_bolsa_oc = (get_magic_quotes_gpc()) ? $_GET['n_ocb'] : addslashes($_GET['n_ocb']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa_oc = sprintf("SELECT * FROM orden_compra_bolsas WHERE n_ocb = %s", $colname_bolsa_oc);
$bolsa_oc = mysql_query($query_bolsa_oc, $conexion1) or die(mysql_error());
$row_bolsa_oc = mysql_fetch_assoc($bolsa_oc);
$totalRows_bolsa_oc = mysql_num_rows($bolsa_oc);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
</head>
<body>
<div align="center">
<table id="tabla2">
  <tr>
    <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="bolsas_oc_edit.php?n_ocb=<?php echo $row_bolsa_oc['n_ocb']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="bolsas_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="O.C. (BOLSAS)" border="0" /></a><a href="bolsas.php"><img src="images/b.gif" alt="BOLSAS" border="0" /></a><a href="bolsas_busqueda.php"><img src="images/embudo.gif" style="cursor:hand;" alt="FILTRO" border="0" /></a><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
  </tr>
</table>
<table id="tabla1"><tr><td align="center">
<table id="tabla2">
<tr>
  <td colspan="5" id="fondo2"><table id="tabla2">
<tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg" /></td>
<td nowrap="nowrap" id="titulo">ORDEN DE COMPRA </td>
</tr>
<tr>
  <td id="titular2">PRODUCTO TERMINADO (BOLSAS)</td>
  </tr>
<tr>
  <td id="numero2">N&deg; <strong><?php echo $row_bolsa_oc['n_ocb']; ?></strong></td>
  </tr>
<tr>
  <td id="fondo2">ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6<br>
    Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
</tr>
</table></td>
  </tr>
<tr>
<td id="subtitulo2" width="50%">FECHA DE PEDIDO : <?php echo $row_bolsa_oc['fecha_pedido_ocb']; ?></td>
<td width="50%" colspan="2" id="subtitulo2">FECHA DE ENTREGA : <?php echo $row_bolsa_oc['fecha_entrega_ocb']; ?></td>
</tr>
<tr>
  <td colspan="3" id="dato1">Proveedor : <?php $proveedor=$row_bolsa_oc['id_p_ocb'];
	if($proveedor!='')
	{
	$sqlp="SELECT * FROM proveedor WHERE id_p ='$proveedor'";
	$resultp= mysql_query($sqlp);
	$nump= mysql_num_rows($resultp);
	if($nump >='1')
	{ 
	$nombre = mysql_result($resultp,0,'proveedor_p');
	$nit_p = mysql_result($resultp,0,'nit_p');
	$pais_p = mysql_result($resultp,0,'pais_p');
	$ciudad_p = mysql_result($resultp,0,'ciudad_p');
    $telefono_p = mysql_result($resultp,0,'telefono_p');
    $fax_p = mysql_result($resultp,0,'fax_p');
	$contacto_p = mysql_result($resultp,0,'contacto_p');
	echo $nombre;
	} } ?>  </td>
  </tr>
<tr>
  <td id="dato1">Nit : <?php echo $nit_p; ?></td>
  <td id="dato1" colspan="2">Pais / Ciudad  : <?php echo $ciudad_p; ?> / <?php echo $pais_p; ?></td>
  </tr>
<tr>
  <td id="dato1">Contacto Comercial  : <?php echo $contacto_p; ?></td>
  <td id="dato1" colspan="2">Telefono : <?php echo $telefono_p; ?></td>
  </tr>
<tr>
  <td id="dato1">Condiciones de Pago : <?php echo $row_bolsa_oc['condiciones_pago_ocb']; ?></td>
  <td id="dato1" colspan="2">Fax : <?php echo $fax_p; ?></td>
  </tr>
<tr>
  <td colspan="3" id="subtitulo2">PRODUCTO TERMINADO SOLICITADO </td>
  </tr>
<tr>
  <td id="dato1"><?php $id_bolsa=$row_bolsa_oc['id_bolsa_ocb'];
	if($id_bolsa!='')
	{
	$sqlbolsa="SELECT * FROM material_terminado_bolsas WHERE id_bolsa ='$id_bolsa'";
	$resultbolsa= mysql_query($sqlbolsa);
	$numbolsa= mysql_num_rows($resultbolsa);
	if($numbolsa >='1')
	{ 
	$nombre = mysql_result($resultbolsa,0,'nombre_bolsa');
	$codigo = mysql_result($resultbolsa,0,'codigo_bolsa');
	$medida = mysql_result($resultbolsa,0,'id_medida_bolsa');
	echo $nombre;
	} } ?></td>
  <td id="dato1" width="25%">Valor Unitario</td>
  <td id="dato3" width="25%">$ <?php echo $row_bolsa_oc['valor_unitario_ocb']; ?></td>
</tr>
<tr>
  <td id="dato1">Codigo : <?php echo $codigo; ?></td>
  <td id="dato1">Valor Neto</td>
  <td id="dato3">$ <?php echo $row_bolsa_oc['valor_neto_ocb']; ?></td>
</tr>
<tr>
  <td id="dato1">Unidad de Medida :
    <?php if($medida!='') { 
    $sqlm="SELECT * FROM medida WHERE id_medida ='$medida'";
	$resultm= mysql_query($sqlm);
	$numedida= mysql_num_rows($resultm);
	if($numedida >='1') { $nombre_medida = mysql_result($resultm,0,'nombre_medida');
	echo $nombre_medida; } } ?></td>
  <td id="dato1">Valor IVA</td>
  <td id="dato3">$ <?php echo $row_bolsa_oc['valor_iva_ocb']; ?></td>
</tr>
<tr>
  <td id="dato1">Cantidad Pedida : <?php echo $row_bolsa_oc['cantidad_ocb']; ?></td>
  <td id="dato1">Valor Total</td>
  <td id="dato3">$ <?php echo $row_bolsa_oc['valor_total_ocb']; ?></td>
</tr>
<tr>
  <td colspan="3" id="subtitulo2">ESPECIFICACIONES TECNICAS DEL PRODUCTO TERMINADO </td>
  </tr>
<tr>
  <td id="dato1">Pedido : <?php $pedido=$row_bolsa_oc['pedido_ocb']; if($pedido=='0') { echo "Nuevo"; } if($pedido=='1') { echo "Reimpresion"; } ?></td>
  <td colspan="2" id="dato1">Referencia del Producto : <?php $id_ref=$row_bolsa_oc['id_ref_ocb']; if($id_ref!='') { 
    $sqlr="SELECT * FROM Tbl_referencia WHERE id_ref ='$id_ref'";
	$resultr= mysql_query($sqlr);
	$numr= mysql_num_rows($resultr);
	if($numr >='1') 
	{ 
	$cod_ref = mysql_result($resultr,0,'cod_ref');
	$n_egp_ref = mysql_result($resultr,0,'n_egp_ref');
	echo $cod_ref; } } ?></td>
</tr>
<tr>
  <td id="dato1"><input <?php if (!(strcmp($row_bolsa_oc['muestra_ocb'],1))) {echo "checked=\"checked\"";} ?> name="muestra_ocb" type="checkbox" id="muestra_ocb" value="1"> 
    Se entrega muestra</td>
  <td colspan="2" id="fondo1">DIMENSIONES DE LA BOLSA FORMADA </td>
</tr>
<tr>
  <td id="fondo1">FORMAS DE EMPAQUE</td>
  <td id="dato1">Ancho (cm) : <?php echo $row_bolsa_oc['ancho_ocb']; ?></td>
  <td id="dato1">Fuelle/Fondo (cm) : <?php echo $row_bolsa_oc['fuelle_ocb']; ?></td>
</tr>
<tr>
  <td id="dato1">Paquetes : <?php echo $row_bolsa_oc['paquetes_ocb']; ?></td>
  <td id="dato1">Largo (cm) : <?php echo $row_bolsa_oc['largo_ocb']; ?></td>
  <td id="dato1">Calibre (micras) : <?php echo $row_bolsa_oc['calibre_micras_ocb']; ?></td>
</tr>
<tr>
  <td id="dato1">Cajas : <?php echo $row_bolsa_oc['cajas_ocb']; ?></td>
  <td id="dato1">Solapa (cm) : <?php echo $row_bolsa_oc['solapa_ocb']; ?></td>
  <td id="dato1">Calibre (milas) : <?php echo $row_bolsa_oc['calibre_ocb']; ?></td>
</tr><?php if($pedido=='0') { ?>
<tr>
  <td colspan="3" id="subtitulo2">ESPECIFICACIONES TECNICAS DE LA IMPRESION </td>
  </tr>
<tr>
  <td colspan="3" id="fondo1">COLORES DE IMPRESION
  <?php if($n_egp_ref != '') {
  $sqlegp="SELECT * FROM Tbl_egp WHERE n_egp ='$n_egp_ref'";
  $resultegp= mysql_query($sqlegp);
  $numegp= mysql_num_rows($resultegp);
  if($numegp >='1') { 
	$color1_egp = mysql_result($resultegp,0,'color1_egp');
	$pantone1_egp = mysql_result($resultegp,0,'pantone1_egp');
	$color2_egp = mysql_result($resultegp,0,'color2_egp');
	$pantone2_egp = mysql_result($resultegp,0,'pantone2_egp');
	$color3_egp = mysql_result($resultegp,0,'color3_egp');
	$pantone3_egp = mysql_result($resultegp,0,'pantone3_egp');
	$color4_egp = mysql_result($resultegp,0,'color4_egp');
	$pantone4_egp = mysql_result($resultegp,0,'pantone4_egp');
	$color5_egp = mysql_result($resultegp,0,'color5_egp');
	$pantone5_egp = mysql_result($resultegp,0,'pantone5_egp');
	$color6_egp = mysql_result($resultegp,0,'color6_egp');
	$pantone6_egp = mysql_result($resultegp,0,'pantone6_egp');
	$color7_egp = mysql_result($resultegp,0,'color7_egp');
	$pantone7_egp = mysql_result($resultegp,0,'pantone7_egp');
	$color8_egp = mysql_result($resultegp,0,'color8_egp');
	$pantone8_egp = mysql_result($resultegp,0,'pantone8_egp');	
	} } ?>  </td>
</tr>
<tr>
  <td colspan="3" id="fondo2"><table id="tabla2">
    <tr>
      <td id="detalle1"><strong>COLOR 1 : </strong><?php echo $color1_egp; ?></td>
      <td id="detalle1"><strong>PANTONE 1 : </strong><?php echo $pantone1_egp; ?></td>
      <td id="detalle1"><strong>COLOR 4 : </strong><?php echo $color4_egp; ?></td>
      <td id="detalle1"><strong>PANTONE 4 : </strong><?php echo $pantone4_egp; ?></td>
    </tr>
    <tr>
      <td id="detalle1"><strong>COLOR 2 : </strong><?php echo $color2_egp; ?></td>
      <td id="detalle1"><strong>PANTONE 2 : </strong><?php echo $pantone2_egp; ?></td>
      <td id="detalle1"><strong>COLOR 5 : </strong><?php echo $color5_egp; ?></td>
      <td id="detalle1"><strong>PANTONE 5 : </strong><?php echo $pantone5_egp; ?></td>
    </tr>
    <tr>
      <td id="detalle1"><strong>COLOR 3 : </strong><?php echo $color3_egp; ?></td>
      <td id="detalle1"><strong>PANTONE 3 : </strong><?php echo $pantone3_egp; ?></td>
      <td id="detalle1"><strong>COLOR 6 : </strong><?php echo $color6_egp; ?></td>
      <td id="detalle1"><strong>PANTONE 6 : </strong><?php echo $pantone6_egp; ?></td>
    </tr>
    <tr>
      <td id="detalle1"><strong>COLOR 7 : </strong><?php echo $color7_egp; ?></td>
      <td id="detalle1"><strong>PANTONE 7 : </strong><?php echo $pantone7_egp; ?></td>
      <td id="detalle1"><strong>COLOR 8 : </strong><?php echo $color8_egp; ?></td>
      <td id="detalle1"><strong>PANTONE 8 : </strong><?php echo $pantone8_egp; ?></td>
    </tr>   
  </table></td>
  </tr>
<tr>
  <td id="dato1"><input <?php if (!(strcmp($row_bolsa_oc['anexa_arte_ocb'],1))) {echo "checked=\"checked\"";} ?> name="anexa_arte_ocb" type="checkbox" id="anexa_arte_ocb" value="1">
    Se anexa arte </td>
  <td colspan="2" id="dato1"><input <?php if (!(strcmp($row_bolsa_oc['negativo_ocb'],1))) {echo "checked=\"checked\"";} ?> name="negativo_ocb" type="checkbox" id="negativo_ocb" value="1">
    Se entrega negativo </td>
  </tr>
<tr>
  <td id="dato1"><input <?php if (!(strcmp($row_bolsa_oc['anexa_arte_ocb'],1))) {echo "checked=\"checked\"";} ?> name="anexa_arte_impreso_ocb" type="checkbox" id="anexa_arte_impreso_ocb" value="1">
    Se anexa arte impreso </td>
  <td colspan="2" id="dato1"><input <?php if (!(strcmp($row_bolsa_oc['cirel_ocb'],1))) {echo "checked=\"checked\"";} ?> name="cirel_ocb" type="checkbox" id="cirel_ocb" value="1">
    Se entrega cirel </td>
  </tr>
<tr>
  <td colspan="3" id="dato1">Repetici&oacute;n Rodillo (cm/pulg) : <?php echo $row_bolsa_oc['rodillo_ocb']; ?></td>
  </tr><?php } ?>
<tr>
  <td colspan="3" id="subtitulo2">OBSERVACIONES GENERALES </td>
  </tr>
<tr>
  <td colspan="3" id="justificar">1. Favor remitirse a las especificaciones t&eacute;cnicas del material y de impresi&oacute;n durante la producci&oacute;n. <br>
  2. Es muy importante que las caracter&iacute;sticas t&eacute;cnicas de la bolsa se respeten. <br>
  En caso de alguna duda comuniquela inmediatamente.<br>
  3. NO DEBE DE APARECER  EL LOGO DEL IMPRESOR POR NING&Uacute;N MOTIVO. <br>
              4. Debe de revisar bien los sellos y la resistencia de estos.</td>
  </tr>
<tr>
  <td colspan="3" id="dato1"><strong>OTRAS OBSERVACIONES</strong> : <?php echo $row_bolsa_oc['observaciones_ocb']; ?></td>
  </tr>
<tr>
  <td id="subtitulo2">ELABORADO</td>
  <td id="subtitulo2" colspan="2">APROBADO</td>
  </tr>
<tr>
  <td id="dato2"><?php echo $row_bolsa_oc['registrado_ocb']; ?></td>
  <td id="dato2" colspan="2">- <?php echo $row_bolsa_oc['aprobado_ocb']; ?> - </td>
  </tr>
</table>
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($bolsa_oc);
?>
