<?php require_once('Connections/conexion1.php'); ?>
<?php
$colname_revision_vista = "-1";
if (isset($_GET['id_rev'])) {
  $colname_revision_vista = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision_vista = sprintf("SELECT * FROM revision, Tbl_referencia, Tbl_egp WHERE revision.id_rev = %s AND revision.id_ref_rev = Tbl_referencia.id_ref AND  Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_revision_vista);
$revision_vista = mysql_query($query_revision_vista, $conexion1) or die(mysql_error());
$row_revision_vista = mysql_fetch_assoc($revision_vista);
$totalRows_revision_vista = mysql_num_rows($revision_vista);

$colname_rev_ref = "-1";
if (isset($_GET['id_rev'])) {
  $colname_rev_ref = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rev_ref = sprintf("SELECT * FROM revision WHERE id_rev = %s", $colname_rev_ref);
$rev_ref = mysql_query($query_rev_ref, $conexion1) or die(mysql_error());
$row_rev_ref = mysql_fetch_assoc($rev_ref);
$totalRows_rev_ref = mysql_num_rows($rev_ref);

$colname_validacion = "-1";
if (isset($_GET['id_rev'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM revision, validacion WHERE revision.id_rev = %s AND revision.id_ref_rev = validacion.id_ref_val", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_rev'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM revision, TblFichaTecnica WHERE revision.id_rev = %s AND revision.id_ref_rev = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_rev'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_rev'] : addslashes($_GET['id_rev']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM revision,TblCertificacion WHERE revision.id_rev = %s AND revision.id_ref_rev = TblCertificacion.idref",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref); 

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body><div align="center">
<table id="tablainterna">
  <tr>
    <td nowrap="nowrap" id="subppal">CODIGO : R1 - F01 </td>
    <td colspan="2" nowrap="nowrap" id="principal">PLAN DE DISE&Ntilde;O &amp; DESARROLLO </td>
    <td nowrap="nowrap" id="subppal">VERSION : 3</td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg" /></td>
    <td colspan="2" id="fondo"><strong>I. REVISION <?php echo $row_rev_ref['id_rev']; ?></strong></td>
    <td id="fondo"><a href="revision_edit.php?id_rev=<?php echo $_GET['id_rev']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia.php?id_ref=<?php echo $row_revision_vista['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php $val=$row_validacion['id_val']; if($val == '') { ?> <a href="validacion_add.php?id_ref=<?php echo $row_revision_vista['id_ref_rev']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php $ft=$row_ficha_tecnica['n_ft']; if($ft == '') { ?>
      <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_revision_vista['id_ref_rev']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else{ ?>
      <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } ?>
      <?php if($row_certificacion['idcc']=='') { ?>
          <a href="certificacion_add.php?id_ref=<?php echo $row_revision_vista['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_revision_vista['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
          </a><?php } ?></td>
  </tr>
  <tr>
    <td id="subppal2">FECHA INGRESO </td>
    <td colspan="2" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_rev_ref['fecha_rev']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_rev_ref['responsable_rev']; ?></td>
    </tr>
  <tr>
    <td id="subppal2">REFERENCIA</td>
    <td id="subppal2">EGP N&ordm;</td>
    <td id="subppal2">COTIZACION N&ordm;</td>
  </tr>
  <tr>
    <td id="fuente2"><strong><?php echo $row_revision_vista['cod_ref']; ?> - <?php echo $row_revision_vista['version_ref']; ?></strong></td>
    <td id="fuente2"><?php echo $row_revision_vista['n_egp_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['n_cotiz_ref']; ?></td>
  </tr>
  <tr>
    <td id="fondo"><?php if($row_revision_vista['estado_ref'] == '1') { echo "Activa"; } else { echo "Inactiva"; } ?></td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">DATOS GENERALES DE LA REFERENCIA </td>
  </tr>
  <tr>
    <td id="subppal2">ANCHO</td>
    <td id="subppal2">LARGO</td>
    <td id="subppal2">SOLAPA</td>
    <td id="subppal2">BOLSILLO PORTAGUIA </td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['ancho_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['largo_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['solapa_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['bolsillo_guia_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">CALIBRE</td>
    <td id="subppal2">PESO MILLAR </td>
    <td id="subppal2">TIPO DE BOLSA </td>
    <td id="subppal2">ADHESIVO</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['calibre_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['peso_millar_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_bolsa_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['adhesivo_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">MATERIAL</td>
    <td id="subppal2">IMPRESION</td>
    <td id="subppal2">NUM. &amp; POSICIONES </td>
    <td id="subppal2">CODIGO BARRAS &amp; FORM. </td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['material_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['impresion_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['num_pos_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['cod_form_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">TRANSLAPE</td>
    <td id="subppal2">PESO MAXIMO APLICADO </td>
    <td id="subppal2">CAPA</td>
    <td id="subppal2">PRESENTACION</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['translape_rev']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['peso_max_rev']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['capa_rev']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['presentacion_rev']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">DATOS ESPECIFICOS DE LA REFERENCIA </td>
  </tr>
  <tr>
    <td id="subppal2">TIPO EXTRUSION </td>
    <td id="subppal2">PIGMENTO EXTERIOR</td>
    <td id="subppal2">PIGMENTO INTERIOR </td>
    <td id="subppal2">TIPO SELLO </td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_ext_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['pigm_ext_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['pigm_int_epg']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_sello_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">MATERIAL A IMPRIMIR</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">COLOR 1 : <?php echo $row_revision_vista['color1_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone1_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion1_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">COLOR 2 : <?php echo $row_revision_vista['color2_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone2_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion2_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">COLOR 3 : <?php echo $row_revision_vista['color3_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone3_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion3_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3"> COLOR 4 : <?php echo $row_revision_vista['color4_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone4_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion4_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">COLOR 5 : <?php echo $row_revision_vista['color5_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone5_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion5_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">COLOR 6 : <?php echo $row_revision_vista['color6_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone6_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion6_egp']; ?></td>
  </tr>
  <tr>
  <tr>
    <td colspan="2" id="fuente3">COLOR 7 : <?php echo $row_revision_vista['color7_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone7_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion7_egp']; ?></td>
  </tr>
  <tr>
  <tr>
    <td colspan="2" id="fuente3">COLOR 8 : <?php echo $row_revision_vista['color8_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone8_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion8_egp']; ?></td>
  </tr>
  <tr>    
    <td colspan="2" id="subppal2">POSICION</td>
    <td id="subppal2">TIPO DE NUMERACION </td>
    <td id="subppal2">FORMATO &amp; CODIGO DE BARRAS </td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">Posicion de la Solapa Talonario Recibo </td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_solapatr_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['cb_solapatr_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">Posicion de la Cinta </td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_cinta_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['cb_cinta_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">Posicion del Principal</td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_principal_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">Posicion del Inferior </td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_inferior_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['cb_inferior_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">Posicion del Liner </td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_liner_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['cb_liner_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">Posicion del Bolsillo</td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_bols_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['cb_bols_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3"><?php echo $row_revision_vista['tipo_nom_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_otro_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['cb_otro_egp']; ?></td>
  </tr>      
  <tr>
    <td colspan="4" id="subppal2">INFORMACION DE PRODUCCION SOBRE NEGATIVOS Y CYREL</td>
  </tr>
  <tr>
    <td id="subppal2">Numero del Rodillo (cm) </td>
    <td id="subppal2">Repeticiones por Revolucion </td>
    <td id="subppal2">Tipo de Elongacion </td>
    <td id="subppal2">Valor</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['num_rodillos_rev']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['repeticion_rev']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_elong_rev']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['valor_tipo_elong_rev']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">ARTE</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['recibir_muestra_rev'],1))) {echo "checked=\"checked\"";} ?> name="recibir_muestra_rev" type="checkbox" value="1" />
    Se recibe bosquejo o muestra fisica del cliente.</td>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['recibir_artes_rev'],1))) {echo "checked=\"checked\"";} ?> name="recibir_artes_rev" type="checkbox" value="1" />
Se recibe arte completo del cliente o logos.</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">      <input name="recibir_textos_rev" type="checkbox" id="recibir_textos_rev" value="1" <?php if (!(strcmp($row_revision_vista['recibir_textos_rev'],1))) {echo "checked=\"checked\"";} ?> />
    Se reciben solo textos por el cliente.</td>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['orientacion_textos_rev'],1))) {echo "checked=\"checked\"";} ?> name="orientacion_textos_rev" type="checkbox" value="1" />
      Se solicita orientaci&oacute;n en textos de seguridad.</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['cinta_afecta_rev'],1))) {echo "checked=\"checked\"";} ?> name="cinta_afecta_rev" type="checkbox" value="1" />
    La cinta afecta la altura de la solapa.</td>
    <td colspan="2" id="fuente3">Valor Indicado :  <?php echo $row_revision_vista['valor_cinta_afecta_rev']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['entregar_arte_elong_rev'],1))) {echo "checked=\"checked\"";} ?> name="entregar_arte_elong_rev" type="checkbox" value="1" />
      Se debe entregar arte incluyendo elongaci&oacute;n.</td>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['orientacion_total_arte_rev'],1))) {echo "checked=\"checked\"";} ?> name="orientacion_total_arte_rev" type="checkbox" value="1" />
      Se solicita orientaci&oacute;n total en el arte.</td>
  </tr>
  
  <tr>
    <td colspan="4" id="subppal2">OBSERVACIONES DE LA REVISION </td>
  </tr>
  <tr>
    <td colspan="4" id="fuente3"><?php  if($row_revision_vista['observacion5_egp']!=''){echo "Obs. Referencia:".$row_revision_vista['observacion5_egp'];echo" ";}?><?php echo $row_revision_vista['observacion_rev']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal2">ULTIMA ACTUALIZACION </td>
    <td colspan="2" id="subppal2">FECHA DE ULTIMA ACTUALIZACION </td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2">- <?php echo $row_rev_ref['actualizado_rev']; ?> - </td>
    <td colspan="2" id="fuente2">- <?php echo $row_rev_ref['fecha_actualizado_rev']; ?> - </td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($revision_vista);

mysql_free_result($rev_ref);

mysql_free_result($validacion);

mysql_free_result($ficha_tecnica);
?>
