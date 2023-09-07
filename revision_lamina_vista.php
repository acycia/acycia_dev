<?php require_once('Connections/conexion1.php'); ?>
<?php
$colname_revision_vista = "-1";
if (isset($_GET['id_rev_l'])) {
  $colname_revision_vista = (get_magic_quotes_gpc()) ? $_GET['id_rev_l'] : addslashes($_GET['id_rev_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision_vista = sprintf("SELECT * FROM Tbl_revision_lamina, Tbl_referencia, Tbl_egp WHERE Tbl_revision_lamina.id_rev_l = %s AND Tbl_revision_lamina.id_ref_rev_l = Tbl_referencia.id_ref AND  Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_revision_vista);
$revision_vista = mysql_query($query_revision_vista, $conexion1) or die(mysql_error());
$row_revision_vista = mysql_fetch_assoc($revision_vista);
$totalRows_revision_vista = mysql_num_rows($revision_vista);

$colname_rev_ref = "-1";
if (isset($_GET['id_rev_l'])) {
  $colname_rev_ref = (get_magic_quotes_gpc()) ? $_GET['id_rev_l'] : addslashes($_GET['id_rev_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rev_ref = sprintf("SELECT * FROM Tbl_revision_lamina WHERE id_rev_l = %s", $colname_rev_ref);
$rev_ref = mysql_query($query_rev_ref, $conexion1) or die(mysql_error());
$row_rev_ref = mysql_fetch_assoc($rev_ref);
$totalRows_rev_ref = mysql_num_rows($rev_ref);

$colname_validacion = "-1";
if (isset($_GET['id_rev_l'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_rev_l'] : addslashes($_GET['id_rev_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM Tbl_revision_lamina, Tbl_validacion_lamina WHERE Tbl_revision_lamina.id_rev_l = %s AND Tbl_revision_lamina.id_ref_rev_l = Tbl_validacion_lamina.id_ref_val_l", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_rev_l'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_rev_l'] : addslashes($_GET['id_rev_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM Tbl_revision_lamina, TblFichaTecnica WHERE Tbl_revision_lamina.id_rev_l = %s AND Tbl_revision_lamina.id_ref_rev_l  = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_rev_l'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_rev_l'] : addslashes($_GET['id_rev_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM Tbl_revision_lamina,TblCertificacion WHERE Tbl_revision_lamina.id_rev_l = %s AND Tbl_revision_lamina.id_ref_rev_l = TblCertificacion.idref",$colname_certificacion_ref);
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
    <td colspan="2" id="fondo"><strong>I. REVISION <?php echo $row_rev_ref['id_rev_l']; ?></strong></td>
    <td id="fondo"><a href="revision_lamina_edit.php?id_rev_l=<?php echo $_GET['id_rev_l']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_revision_vista['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION" title="VERIFICACION" border="0" style="cursor:hand;" /></a>
      <?php $val=$row_validacion['id_val_l']; if($val == '') { ?>
      <a href="validacion_lamina_add.php?id_ref=<?php echo $row_revision_vista['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a>
      <?php } else{ ?>
      <a href="validacion_lamina_vista.php?id_val_l=<?php echo $row_validacion['id_val_l']; ?>"><img src="images/v.gif" alt="VALIDACION"  title="VALIDACION" border="0" style="cursor:hand;" /></a>
      <?php } ?>
      <?php $ft=$row_ficha_tecnica['n_ft']; if($ft == '') { ?>
      <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_revision_ref['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a>
      <?php } else{ ?>
      <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>
      <?php } ?>
      <?php if($row_certificacion['idcc']=='') { ?>
       <a href="certificacion_add.php?id_ref=<?php echo $row_revision_vista['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_revision_vista['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
      </a><?php } ?></td>
  </tr>
  <tr>
    <td id="subppal2">FECHA INGRESO </td>
    <td colspan="2" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_rev_ref['fecha_rev_l']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_rev_ref['responsable_rev_l']; ?></td>
    </tr>
  <tr>
    <td id="subppal2">REFERENCIA</td>
    <td id="subppal2">&nbsp;</td>
    <td id="subppal2">COTIZACION N&ordm;</td>
  </tr>
  <tr>
    <td id="fuente2"><strong><?php echo $row_revision_vista['cod_ref']; ?> - <?php echo $row_revision_vista['version_ref']; ?></strong></td>
    <td id="fuente2">&nbsp;</td>
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
    <td id="subppal2">CALIBRE</td>
    <td id="subppal2">PESO MILLAR </td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['ancho_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['largo_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['calibre_ref']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['peso_millar_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">NUMERO DE PISTAS</td>
    <td id="subppal2">RODILLO</td>
    <td id="subppal2">TRATAMIENTO</td>
    <td id="subppal2">IMPRESION</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['int_numero_p_l'] ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['int_rodillo_l'] ?></td>
    <td id="fuente2"><?php if (!(strcmp("0", $row_revision_vista['b_tratamiento_l']))) {echo "NO";}else{echo"SI";} ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['impresion_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">ANCHO TOTAL</td>
    <td id="subppal2">REPETICION</td>
    <td colspan="2" id="subppal2">EMBOBINADO
      <input type="hidden" name="int_embob_rev_l" id="int_embob_rev_l" value="<?php echo $row_revision_ref['N_embobinado_l']; ?>" /></td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['int_anchot_l'] ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['int_repeticion_l'] ?></td>
    <td colspan="2" rowspan="2" id="fuente2"><?php switch($row_revision_vista['N_embobinado_l']) {
	  case 0: echo "VACIO"; break;
	  case 1: ?>
      <img src="images/embobinado1.gif" />
      <?php break;
	  case 2: ?>
      <img src="images/embobinado2.gif" />
      <?php break;
	  case 3: ?>
      <img src="images/embobinado3.gif" />
      <?php break;
	  case 4: ?>
      <img src="images/embobinado4.gif" />
      <?php break;
	  case 5: ?>
      <img src="images/embobinado5.gif" />
      <?php break;
	  case 6: ?>
      <img src="images/embobinado6.gif" />
      <?php break;
	  case 7: ?>
      <img src="images/embobinado7.gif" />
      <?php break;
	  case 8: ?>
      <img src="images/embobinado8.gif" />
      <?php break;
	  case 9: ?>
      <img src="images/embobinado9.gif" />
      <?php break;
	  case 10: ?>
      <img src="images/embobinado10.gif" />
      <?php break;
	  case 11: ?>
      <img src="images/embobinado11.gif" />
      <?php break;
	  case 12: ?>
      <img src="images/embobinado12.gif" />
      <?php break;
	  case 13: ?>
      <img src="images/embobinado13.gif" />
      <?php break;
	  case 14: ?>
      <img src="images/embobinado14.gif" />
      <?php break;
	  case 15: ?>
      <img src="images/embobinado15.gif" />
      <?php break;
	  case 16: ?>
      <img src="images/embobinado16.gif" />
      <?php break;
	  } ?></td>
    </tr>
  <tr>
    <td colspan="2" id="fuente2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="4" id="subppal2">DATOS ESPECIFICOS DE LA REFERENCIA </td>
  </tr>
  <tr>
    <td id="subppal2">TIPO EXTRUSION </td>
    <td id="subppal2">PIGMENTO EXTERIOR</td>
    <td colspan="2" id="subppal2">PIGMENTO INTERIOR </td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_revision_vista['tipo_ext_egp']; ?></td>
    <td id="fuente2"><?php echo $row_revision_vista['pigm_ext_egp']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_revision_vista['pigm_int_epg']; ?></td>
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
    <td colspan="2" id="fuente3">COLOR 7 : <?php echo $row_revision_vista['color7_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone7_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion7_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">COLOR 8 : <?php echo $row_revision_vista['color8_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php echo $row_revision_vista['pantone8_egp']; ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_revision_vista['ubicacion8_egp']; ?></td>
  </tr>
  <tr>    
    <td colspan="4" id="subppal2"><strong>CODIGO DE BARRAS </strong></td>
  </tr>
  <tr>
    <td id="fuente3">PREIMPRESO</td>
    <td id="fuente3"><?php if (!(strcmp("0", $row_revision_vista['b_preimp_l']))) {echo "NO";}else{"SI";} ?></td>
    <td id="detalle3">NUMERO</td>
    <td id="detalle1"><?php echo  $row_revision_vista['int_numero_l']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">OBSERVACIONES CODIGO DE BARRAS</td>
  </tr>
  <tr>
    <td colspan="4" id="fuente3"><?php echo  $row_revision_vista['str_obs_general_l']; ?></td>
    </tr>
  <tr>
    <td colspan="4" id="subppal2">ARTE</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['b_recibir_muestra_rev_l'],1))) {echo "checked=\"checked\"";} ?> name="recibir_muestra_rev" type="checkbox" value="1" />
    Se recibe bosquejo o muestra fisica del cliente.</td>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['b_recibir_artes_rev_l'],1))) {echo "checked=\"checked\"";} ?> name="recibir_artes_rev" type="checkbox" value="1" />
Se recibe arte completo del cliente o logos.</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3">      <input name="recibir_textos_rev" type="checkbox" id="recibir_textos_rev" value="1" <?php if (!(strcmp($row_revision_vista['b_recibir_textos_rev_l'],1))) {echo "checked=\"checked\"";} ?> />
    Se reciben solo textos por el cliente.</td>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['b_orientacion_total_arte_rev_l'],1))) {echo "checked=\"checked\"";} ?> name="orientacion_total_arte_rev" type="checkbox" value="1" />
Se solicita orientaci&oacute;n total en el arte.</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3"><input <?php if (!(strcmp($row_revision_vista['b_entregar_arte_elong_rev_l'],1))) {echo "checked=\"checked\"";} ?> name="entregar_arte_elong_rev" type="checkbox" value="1" />
Se debe entregar arte incluyendo elongaci&oacute;n.</td>
    <td colspan="2" id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="fuente3">&nbsp;</td>
    </tr>
  
  <tr>
    <td colspan="4" id="subppal2">OBSERVACIONES</td>
  </tr>
  <tr>
    <td colspan="4" id="fuente3"><?php  if($row_revision_vista['observacion5_egp']!=''){echo "Obs. Referencia:".$row_revision_vista['observacion5_egp'];echo" ";}?><?php echo $row_revision_vista['str_obs_general_l']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal2">ULTIMA ACTUALIZACION </td>
    <td colspan="2" id="subppal2">FECHA DE ULTIMA ACTUALIZACION </td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2">- <?php echo $row_rev_ref['actualizado_rev_l']; ?> - </td>
    <td colspan="2" id="fuente2">- <?php echo $row_rev_ref['fecha_actualizado_rev_l']; ?> - </td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($revision_vista);

mysql_free_result($rev_ref);

mysql_free_result($validacion);

//mysql_free_result($ficha_tecnica);
?>
