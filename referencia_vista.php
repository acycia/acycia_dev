<?php require_once('Connections/conexion1.php'); ?><?php
$colname_referenciaver = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referenciaver = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referenciaver = sprintf("SELECT * FROM referencia WHERE id_ref = %s", $colname_referenciaver);
$referenciaver = mysql_query($query_referenciaver, $conexion1) or die(mysql_error());
$row_referenciaver = mysql_fetch_assoc($referenciaver);
$totalRows_referenciaver = mysql_num_rows($referenciaver);

$colname_referencia_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_egp = sprintf("SELECT * FROM referencia, egp WHERE referencia.id_ref = %s AND referencia.n_egp_ref = egp.n_egp", $colname_referencia_egp);
$referencia_egp = mysql_query($query_referencia_egp, $conexion1) or die(mysql_error());
$row_referencia_egp = mysql_fetch_assoc($referencia_egp);
$totalRows_referencia_egp = mysql_num_rows($referencia_egp);

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_refs_clientes = "-1";
if (isset($_GET['id_ref'])) {
  $colname_refs_clientes = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refs_clientes = sprintf("SELECT * FROM ref_cliente, cliente WHERE ref_cliente.id_ref = %s AND ref_cliente.id_c=cliente.id_c", $colname_refs_clientes);
$refs_clientes = mysql_query($query_refs_clientes, $conexion1) or die(mysql_error());
$row_refs_clientes = mysql_fetch_assoc($refs_clientes);
$totalRows_refs_clientes = mysql_num_rows($refs_clientes);

$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s AND estado_arte_verif = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body>
<div align="center">
<table id="tablainterna">
  <tr>    
     <td colspan="4" id="principal">REFERENCIA ( BOLSA PLASTICA ) </td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="3" id="fuente3"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="INPRIMIR" border="0" /><?php $tipo=$_GET['tipo']; if($tipo=='1') { ?>	<a href="referencia_edit.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&n_egp=<?php echo $row_referenciaver['n_egp_ref']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="referencia_cliente.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" border="0"></a><?php } else{ ?><a href="acceso.php"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="acceso.php"><img src="images/cliente.gif" alt="EDITAR" border="0" /></a><?php } ?><a href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REF'S ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REF'S ACTIVAS" border="0" /></a><a href="disenoydesarrollo.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISEÑO Y DESARROLLO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
    </tr>
  <tr>
    <td id="fuente1">FECHA DE INGRESO </td>
    <td colspan="2" id="fuente1">RESPONSABLE</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['fecha_registro1_ref']; ?></td>
    <td colspan="2" nowrap id="fuente2"><?php echo $row_referenciaver['registro1_ref']; ?></td>
    </tr>
  <tr>
    <td id="fuente1">REFERENCIA - VERSION</td>
    <td id="fuente1">EGP N&ordm; </td>
    <td id="fuente1">COTIZACION N&ordm; </td>
  </tr>
  <tr>
    <td nowrap id="fuente2"><strong><?php echo $row_referenciaver['cod_ref']; ?> - 
      <?php echo $row_referenciaver['version_ref']; ?></strong></td>
    <td id="fuente2"><?php echo $row_referenciaver['n_egp_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['n_cotiz_ref']; ?></td>
  </tr>
  <tr>
    <td colspan="3" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="4" id="subppal2">DATOS GENERALES DE LA REFERENCIA </td>
    </tr>
  <tr>
    <td id="fuente1">ANCHO</td>
    <td id="fuente1">LARGO</td>
    <td id="fuente1">SOLAPA</td>
    <td id="fuente1">BOLSILLO PORTAGUIA</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['ancho_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['largo_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['solapa_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['bolsillo_guia_ref']; ?></td>
  </tr>
  <tr>
    <td id="fuente1">CALIBRE</td>
    <td id="fuente1">PESO MILLAR </td>
    <td id="fuente1">TIPO DE BOLSA </td>
    <td id="fuente1">ADHESIVO</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['calibre_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['peso_millar_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['tipo_bolsa_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['adhesivo_ref']; ?></td>
  </tr>
  <tr>
    <td id="fuente1">MATERIAL</td>
    <td id="fuente1">IMPRESION</td>
    <td id="fuente1">NUMERACION Y POSICIONES</td>
    <td id="fuente1">BARRAS &amp; FORMATO</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['material_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['impresion_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['num_pos_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['cod_form_ref']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">DATOS ESPECIFICOS DE LA REFERENCIA </td>
    </tr>
  <tr>
    <td id="fuente1">TIPO DE EXTRUSION </td>
    <td id="fuente1">PIGMENTO EXTERIOR </td>
    <td id="fuente1">PIGMENTO INTERIOR </td>
    <td id="fuente1">TIPO DE BOLSA </td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_ext_egp']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['pigm_ext_egp']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['pigm_int_epg']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_bolsa_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente1">TIPO DE SELLO </td>
    <td colspan="3" id="subppal2">IMPRESION</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_sello_egp']; ?></td>
    <td id="fuente7">COLOR 1 : <?php echo $row_referencia_egp['color1_egp']; ?></td>
    <td id="fuente7">PANTONE : <?php echo $row_referencia_egp['pantone1_egp']; ?></td>
    <td id="fuente7">UBICACION : <?php echo $row_referencia_egp['ubicacion1_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fecha_cad_egp" value="1">
      Fecha de Caducidad</td>
    <td id="fuente7">COLOR 2 : <?php echo $row_referencia_egp['color2_egp']; ?></td>
    <td id="fuente7">PANTONE : <?php echo $row_referencia_egp['pantone2_egp']; ?></td>
    <td id="fuente7">UBICACION : <?php echo $row_referencia_egp['ubicacion2_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="arte_sum_egp" value="1">
      Arte Suministrado</td>
    <td id="fuente7">COLOR 3 : <?php echo $row_referencia_egp['color3_egp']; ?></td>
    <td id="fuente7">PANTONE : <?php echo $row_referencia_egp['pantone3_egp']; ?></td>
    <td id="fuente7">UBICACION : <?php echo $row_referencia_egp['ubicacion3_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="orient_arte_egp" value="1">
      Solicita Orientacion</td>
    <td id="fuente7">COLOR 4 : <?php echo $row_referencia_egp['color4_egp']; ?></td>
    <td id="fuente7">PANTONE : <?php echo $row_referencia_egp['pantone4_egp']; ?></td>
    <td id="fuente7">UBICACION : <?php echo $row_referencia_egp['ubicacion4_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ent_logo_egp" value="1">
      Logos de la Entidad</td>
    <td id="fuente7">COLOR 5 : <?php echo $row_referencia_egp['color5_egp']; ?></td>
    <td id="fuente7">PANTONE : <?php echo $row_referencia_egp['pantone5_egp']; ?></td>
    <td id="fuente7">UBICACION : <?php echo $row_referencia_egp['ubicacion5_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente1">ARTE</td>
    <td id="fuente7">COLOR 6 : <?php echo $row_referencia_egp['color6_egp']; ?></td>
    <td id="fuente7">PANTONE : <?php echo $row_referencia_egp['pantone6_egp']; ?></td>
    <td id="fuente7">UBICACION : <?php echo $row_referencia_egp['ubicacion6_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"> <?php echo $row_ref_verif['userfile']; ?> </a></td>
    <td id="subppal2">POSICION</td>
    <td id="subppal2">TIPO DE NUMERACION </td>
    <td id="subppal2">BARRAS &amp; FORMATO</td>
  </tr>
  <tr>
    <td id="fuente1">ESTADO DEL ARTE </td>
    <td id="fuente8">SOLAPA TALONARIO RECIBO </td>
    <td id="fuente8"><?php echo $row_referencia_egp['tipo_solapatr_egp']; ?></td>
    <td id="fuente8"><?php echo $row_referencia_egp['cb_solapatr_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2"><?php $estado=$row_ref_verif['estado_arte_verif'];
	if($estado=='0') { echo "Pendiente"; }
	if($estado=='1') { echo "Rechazado"; }
	if($estado=='2') { echo "Aceptado"; }
	if($estado=='3') { echo "Anulado"; } ?>
	</td>
    <td id="fuente8">CINTA</td>
    <td id="fuente8"><?php echo $row_referencia_egp['tipo_cinta_egp']; ?></td>
    <td id="fuente8"><?php echo $row_referencia_egp['cb_cinta_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente1">Fecha Aprobaci&oacute;n Arte </td>
    <td id="fuente8">PRINCIPAL</td>
    <td id="fuente8"><?php echo $row_referencia_egp['tipo_principal_egp']; ?></td>
    <td id="fuente8"><?php echo $row_referencia_egp['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_ref_verif['fecha_aprob_arte_verif']; ?></td>
    <td id="fuente8">INFERIOR</td>
    <td id="fuente8"><?php echo $row_referencia_egp['tipo_inferior_egp']; ?></td>
    <td id="fuente8"><?php echo $row_referencia_egp['cb_inferior_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">CLIENTES ASIGNADOS A ESTA REFERENCIA </td>
    </tr>
  <tr>
    <td id="fuente1">CLIENTE</td>
    <td id="fuente1">DIRECCION</td>
    <td id="fuente1">PAIS / CIUDAD </td>
    <td id="fuente1">TELEFONO</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="fuente3"><?php echo $row_refs_clientes['nombre_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['direccion_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['pais_c']; ?> / <?php echo $row_refs_clientes['ciudad_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['telefono_c']; ?></td>
    </tr>
    <?php } while ($row_refs_clientes = mysql_fetch_assoc($refs_clientes)); ?>
  <tr>
    <td colspan="4" id="fondo">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" id="fuente1">FECHA ULTIMA MODIFICACION </td>
    <td colspan="2" id="fuente1">RESPONSABLE ULTIMA MODIFICACION </td>
    </tr>
  <tr>
    <td colspan="2" id="fuente2"><?php echo $row_referenciaver['fecha_registro2_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referenciaver['registro2_ref']; ?></td>
    </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($referenciaver);

mysql_free_result($referencia_egp);

mysql_free_result($usuario);

mysql_free_result($refs_clientes);

mysql_free_result($ref_verif);
?>
