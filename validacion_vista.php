<?php require_once('Connections/conexion1.php'); ?>
<?php
$colname_validacion = "-1";
if (isset($_GET['id_val'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_val'] : addslashes($_GET['id_val']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM validacion WHERE id_val = %s", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_referencia = "-1";
if (isset($_GET['id_val'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_val'] : addslashes($_GET['id_val']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM validacion, Tbl_referencia WHERE validacion.id_val = %s AND validacion.id_ref_val = Tbl_referencia.id_ref", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_verificacion = "-1";
if (isset($_GET['id_val'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_val'] : addslashes($_GET['id_val']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM validacion, verificacion WHERE validacion.id_val = %s AND validacion.id_ref_val = verificacion.id_ref_verif AND verificacion.estado_arte_verif = '2'", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_revision = "-1";
if (isset($_GET['id_val'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['id_val'] : addslashes($_GET['id_val']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM validacion, revision WHERE validacion.id_val = %s AND validacion.id_rev_val = revision.id_rev", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

$colname_egp = "-1";
if (isset($_GET['id_val'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['id_val'] : addslashes($_GET['id_val']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egp = sprintf("SELECT * FROM validacion, Tbl_referencia, Tbl_egp WHERE validacion.id_val = %s AND validacion.id_ref_val = Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_egp);
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_val'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_val'] : addslashes($_GET['id_val']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM validacion, TblFichaTecnica WHERE validacion.id_val = '%s' AND validacion.id_ref_val=TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_val'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_val'] : addslashes($_GET['id_val']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM validacion,TblCertificacion WHERE validacion.id_val = %s AND validacion.id_ref_val = TblCertificacion.idref",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref); 
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tablainterna" border="0">
  <tr>
    <td nowrap="nowrap" id="subppal">CODIGO : R1 - F01 </td>
    <td colspan="2" nowrap="nowrap" id="principal">PLAN DE DISE&Ntilde;O &amp; DESARROLLO </td>
    <td nowrap="nowrap" id="subppal">VERSION : 4</td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="2" id="fondo"><strong>III. VALIDACION # <?php echo $row_validacion['id_val']; ?></strong></td>
    <td id="fondo"><a href="validacion_edit.php?id_val=<?php echo $_GET['id_val']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/v.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><a href="validacion.php"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>        <?php if($row_certificacion['idcc']=='') { ?>
        <a href="certificacion_add.php?id_ref=<?php echo $row_referencia['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_referencia['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
        </a><?php } ?></td>
  </tr>
  <tr>
    <td id="subppal2">FECHA</td>
    <td colspan="2" id="subppal2">RESPONSABLE DEL REGISTRO </td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_validacion['fecha_val']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_validacion['responsable_val']; ?></td>
    </tr>
  <tr>
    <td id="fuente3">REFERENCIA : <strong><?php echo $row_referencia['cod_ref']; ?> - <?php echo $row_validacion['version_val'] ?></strong> <?php if ($row_referencia['version_ref'] != $row_validacion['version_val']) echo " Cambiar a vers: ".$row_referencia['version_ref']?></td>
    <td id="fuente3"><!--EGP : --><?php //echo $row_referencia['n_egp_ref']; ?></td>
    <td id="fuente3">COTIZACION : <?php echo $row_referencia['n_cotiz_ref']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">REVISION : <?php echo $row_revision['id_rev']; ?></td>
    <td id="fuente3">VERIFICACION : <?php echo $row_verificacion['id_verif']; ?></td>
    <td id="fuente3">CLIENTES : <a href="referencia_cliente.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&cod_ref=<?php echo $row_referencia['cod_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" border="0"></a></td>
  </tr>
  <tr>
    <td id="fuente2"><strong><?php echo $row_ficha_tecnica['cod_ft']; ?></strong></td>
    <td id="fuente3">ARTE : <?php $muestra= $row_verificacion['userfile']; ?><a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"> <?php echo $muestra;?></a></td>
    <td id="fuente3">ORDEN DE PROD. : <?php echo $row_validacion['n_op_val']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">LISTADO DE VALIDACION DE PARAMETROS GENERALES (Cumple Si/No) </td>
    </tr>
  <tr>
    <td id="subppal2">DATO</td>
    <td id="subppal2">CUMPLIMIENTO</td>
    <td colspan="2" id="subppal2">OBSERVACIONES</td>
    </tr>
  <tr>
    <td id="fuente3">Ancho : <?php echo $row_referencia['ancho_ref']; ?></td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['ancho_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ancho_val" value="1" >
      Ancho(10 mm/+-)</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_ancho_val'];echo " ";echo $row_verificacion['observ_ancho_verif']; ?></td>
    </tr>
  <tr>
    <td id="fuente3">Largo : <?php echo $row_referencia['largo_ref']; ?></td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['altura_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="altura_val" value="1" >
      Largo(10 mm)</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_altura_val'];echo " ";echo $row_verificacion['observ_largo_verif']; ?></td>
    </tr>
  <tr>
    <td id="fuente3">Solapa : <?php echo $row_referencia['solapa_ref']; ?></td>
    <td id="fuente3"><input <?php if (!(strcmp($row_egp['solapa_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="solapa_val" value="1" >
      Solapa(10 mm)</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_solapa_val'];echo " ";echo $row_verificacion['observ_solapa_verif']; ?></td>
    </tr>
  <tr>
    <td id="fuente3">Calibre : <?php echo $row_referencia['calibre_ref']; ?></td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['calibre_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="calibre_val" value="1" >
      Calibre(10%) </td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_calibre_val']; echo " ";echo $row_verificacion['observ_logo_borde_verif'];?></td>
    </tr>
  <tr>
    <td id="fuente3"> Dist. Logos/Bordes </td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['dist_logo_borde_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="dist_logo_borde_val" value="1" >
      Dist. Logos/Bordes </td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_dist_logo_borde_val'];echo " ";echo $row_verificacion['observ_logo_borde_verif']; ?></td>
    </tr>
  <tr>
    <td id="fuente3">Revisi&oacute;n Textos</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['rev_texto_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="rev_texto_val" value="1" >
Revisi&oacute;n Textos</td>
    <td colspan="2" id="fuente3">-<?php echo $row_validacion['observ_rev_texto_val']; echo " ";echo $row_verificacion['observ_rev_textos_verif']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">Revisi&oacute;n Ortografica</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['rev_ortog_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="rev_ortog_val" value="1" >
Revisi&oacute;n Ortografica</td>
    <td colspan="2" id="fuente3">-<?php echo $row_validacion['observ_rev_ortog_val']; echo " "; echo $row_verificacion['observ_rev_ortog_verif'];?></td>
  </tr>
  <tr>
    <td id="fuente3">Bolsillo Portagu&iacute;a : <?php echo $row_referencia['bolsillo_guia_ref']; ?></td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['rev_portag_val'],1))) {echo "checked=\"checked\"";} ?> name="rev_portag_val" type="checkbox" id="rev_portag_val" value="1" />
Bolsillo Portaguia</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_extru_val']; echo " "; echo $row_verificacion['observ_portag_verif'];?></td>
    </tr>
  <tr>
    <td id="fuente3">Tipo Extrusi&oacute;n : <?php echo $row_egp['tipo_ext_egp']; ?></td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['rev_extru_val'],1))) {echo "checked=\"checked\"";} ?> name="rev_extru_val" type="checkbox" id="rev_extru_val" value="1" />
Tipo Extrusi&oacute;n</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_color_ext_val']; echo " ";echo $row_verificacion['observ_extru_verif']; ?></td>
    </tr>
  <tr>
    <td id="fuente3">Pigmento Exterior : <?php echo $row_egp['pigm_ext_egp']; ?></td>
    <td nowrap id="fuente3"><input <?php if (!(strcmp($row_validacion['color_ext_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color_ext_val" value="1" >
      Color Extrusi&oacute;n Exterior</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_color_ext_val'];echo " ";echo $row_verificacion['observ_color_ext_verif']; ?></td>
    </tr>
  <tr>
    <td id="fuente3">Pigmento Interior : <?php echo $row_egp['pigm_int_epg']; ?></td>
    <td nowrap id="fuente3"><input <?php if (!(strcmp($row_validacion['color_int_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color_int_val" value="1" >
      Color Extrusi&oacute;n Interior</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_color_int_val'];echo " ";echo $row_verificacion['observ_color_int_verif']; ?></td>
    </tr>
  <tr>
    <!--<td colspan="4" id="subppal2">VALIDACION DE RESISTENCIA Y PARAMETROS DE EXTRUSION (Cumple Si/No)</td>
    </tr>
  <tr>
    <td id="subppal2">VARIABLE</td>
    <td id="subppal2">ESTANDAR</td>
    <td colspan="2" id="subppal2">OBSERVACIONES</td>
    </tr>
  <tr>
    <td id="fuente3">Resistencia MD</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['resist_md_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="resist_md_val" value="1" >
  &gt; 3 gr / mic</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_resist_md_val']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">Resistencia TD</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['resist_td_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="resist_td_val" value="1" >
  &gt; 6 gr / mic </td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_resist_td_val']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">Angulo Deslizamiento</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['angulo_desliz_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="angulo_desliz_val" value="1" >
  &gt; 18 &deg; </td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_angulo_desliz_val']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">Fuerza de Sello</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['fuerza_sello_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fuerza_sello_val" value="1" >
  &gt; 30 gr / mic</td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_fuerza_sello_val']; ?></td>-->
  </tr>
  <tr>
    <td colspan="4" id="subppal2">VALIDACION DE COLORES DE IMPRESION (Cumple Si / No) </td>
    </tr>
  <tr>
    <td id="subppal2">VARIABLE</td>
    <td id="subppal2">DATO</td>
    <td id="subppal2">PANTONE</td>
    <td colspan="2" id="subppal2">OBSERVACIONES</td>
  </tr>
  <tr>
    <td id="fuente3">COLOR 1</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['color1_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color1_val" value="1" >
        <?php echo $row_egp['color1_egp']; ?></td>
    <td id="fuente3"> <?php echo $row_egp['pantone1_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_color1_val'];echo " ";echo $row_verificacion['observ_1color_verif']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">COLOR 2</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['color2_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color2_val" value="1" >
        <?php echo $row_egp['color2_egp']; ?></td>
        <td id="fuente3"> <?php echo $row_egp['pantone2_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_color2_val'];echo " ";echo $row_verificacion['observ_2color_verif'];  ?></td>
  </tr>
  <tr>
    <td id="fuente3">COLOR 3</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['color3_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color3_val" value="1" >
        <?php echo $row_egp['color3_egp']; ?></td>
        <td id="fuente3"> <?php echo $row_egp['pantone3_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_color3_val']; echo " ";echo $row_verificacion['observ_3color_verif'];?></td>
  </tr>
  <tr>
    <td id="fuente3">COLOR 4</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['color4_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color4_val" value="1" >
        <?php echo $row_egp['color4_egp']; ?></td>
        <td id="fuente3"> <?php echo $row_egp['pantone4_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_color4_val']; echo " ";echo $row_verificacion['observ_4color_verif']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">COLOR 5</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['color5_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color5_val" value="1" >
        <?php echo $row_egp['color5_egp']; ?></td>
        <td id="fuente3"> <?php echo $row_egp['pantone5_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observacion_color5_val'];echo " ";echo $row_verificacion['observ_5color_verif']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">COLOR 6</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['color6_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color6_val" value="1" >
        <?php echo $row_egp['color6_egp']; ?></td>
        <td id="fuente3"> <?php echo $row_egp['pantone6_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observacion_color6_val'];echo " ";echo $row_verificacion['observ_6color_verif']; ?></td>
  </tr>
    <tr>
    <td id="fuente3">COLOR 7</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['color7_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color7_val" value="1" >
        <?php echo $row_egp['color7_egp']; ?></td>
        <td id="fuente3"> <?php echo $row_egp['pantone7_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observacion_color7_val']; echo " ";echo $row_verificacion['observ_7color_verif'];?></td>
  </tr>
    <tr>
    <td id="fuente3">COLOR 8</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['color8_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color8_val" value="1" >
        <?php echo $row_egp['color8_egp']; ?></td>
        <td id="fuente3"> <?php echo $row_egp['pantone8_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observacion_color8_val'];  echo " ";echo $row_verificacion['observ_8color_verif'];?></td>
  </tr>
  <tr>
    <td id="fuente3">Marca de Fotocelda </td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['marca_foto_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="marca_foto_val" value="1" ></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_marca_foto_val']; echo " ";echo $row_verificacion['observ_marca_foto_verif'];?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">VALIDACION DE NUMERACION (Cumple Si / No) </td>
    </tr>
  <tr>
    <td id="subppal2">POSICIONES</td>
    <td id="subppal2">DATO</td>
    <td colspan="2" id="subppal2">OBSERVACIONES</td>
  </tr>
  <tr>
    <td id="fuente3">Solapa TR </td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['num_tal_rec_val'],1))) {echo "checked=\"checked\"";} ?> name="num_tal_rc_val" type="checkbox" id="num_tal_rc_val" value="1" >
        <?php echo $row_egp['tipo_solapatr_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_num_tal_rec_val'];echo " ";echo $row_verificacion['observ_alt_tal_rec_verif']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">Cinta de Seguridad</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['num_cinta_seg_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="num_cinta_seg_val" value="1" >
        <?php echo $row_egp['tipo_cinta_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_num_cinta_seg_val']; echo " ";echo $row_verificacion['observ_alt_cinta_seg_verif'];?></td>
  </tr>
  <tr>
    <td id="fuente3">Principal</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['num_ppal_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="num_ppal_val" value="1" >
        <?php echo $row_egp['tipo_principal_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_num_ppal_val'];echo " ";echo $row_verificacion['observ_alt_ppal_verif']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">Inferior</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['num_inf_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="num_inf_val" value="1" >
        <?php echo $row_egp['tipo_inferior_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_num_inf_val'];echo " ";echo $row_verificacion['observ_alt_inf_verif']; ?></td>
  </tr>
  <tr>
              <td id="fuente3">Liner: </td>
      <td id="fuente1"><input <?php if (!(strcmp($row_verificacion['num_liner_val'],1))) {echo "checked=\"checked\"";} ?> name="num_liner_val" type="checkbox" id="num_liner_val" value="1">
      <?php echo $row_egp['tipo_liner_egp']; ?></td>
      <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_alt_liner_verif'];echo " ";echo $row_validacion['observ_num_liner_val']; ?></td>
    </tr>
            <tr>
              <td id="fuente3">Bolsillo: </td>
              <td id="fuente1"><input <?php if (!(strcmp($row_verificacion['num_bols_val'],1))) {echo "checked=\"checked\"";} ?> name="num_bols_val" type="checkbox" id="num_bols_val" value="1">
              <?php echo $row_egp['tipo_bols_egp']; ?></td>
              <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_alt_bols_verif'];echo " ";echo $row_validacion['observ_num_bols_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><?php echo $row_egp['otro_nom_val']; ?>:</td>
              <td id="fuente1"><input name="num_otro_val" <?php if (!(strcmp($row_verificacion['num_otro_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" id="alt_otro_verif" value="1">
              <?php echo $row_egp['tipo_otro_egp']; ?></td>
              <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_alt_otro_verif'];echo " ";echo $row_validacion['observ_num_otro_val']; ?></td>
            </tr>  
  <tr>
    <td id="fuente3">Fecha de Caducidad</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['num_fecha_cad_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="num_fecha_cad_val" value="1" ><?php echo $row_validacion['num_fecha_cad_val'] ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_num_fecha_val']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">VALIDACION DE CODIGO DE BARRAS (Si / No)</td>
    </tr>
  <tr id="tr2">
    <td id="subppal2">POSICIONES</td>
    <td id="subppal2">DATO</td>
    <td colspan="2" id="subppal2">OBSERVACIONES</td>
  </tr>
  <tr>
    <td id="fuente3">Solapa TR </td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['cod_tal_rec_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="cod_tal_rec_val" value="1" >
        <?php echo $row_egp['cb_solapatr_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_cod_tal_rec_val']; echo " ";echo $row_verificacion['observ_form_tal_rec_verif'];?></td>
  </tr>
  <tr>
    <td id="fuente3">Cinta de Seguridad</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['cod_cinta_seg_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="cod_cinta_seg_val" value="1" >
        <?php echo $row_egp['cb_cinta_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_cod_cinta_seg_val']; echo " ";echo $row_verificacion['observ_form_cinta_seg_verif'];?></td>
  </tr>
  <tr>
    <td id="fuente3">Principal</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['cod_ppal_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="cod_ppal_val" value="1" >
        <?php echo $row_egp['cb_principal_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_cod_ppal_val']; echo " ";echo $row_verificacion['observ_form_ppal_verif']; ?></td>
  </tr>
  <tr>
    <td id="fuente3">Inferior</td>
    <td id="fuente3"><input <?php if (!(strcmp($row_validacion['cod_inf_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="cod_inf_val" value="1" >
        <?php echo $row_egp['cb_inferior_egp']; ?></td>
    <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_cod_inf_val']; echo " ";echo $row_verificacion['observ_alt_inf_verif']; ?></td>
  </tr>
<tr>
              <td id="fuente3">Liner:                </td>
              <td id="fuente1"><input name="cod_liner_val" type="checkbox" id="cod_liner_val" value="1" <?php if (!(strcmp($row_validacion['cod_liner_val'],1))) {echo "checked=\"checked\"";} ?> />
      <?php echo $row_egp['cb_liner_egp']; ?></td>
              <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_form_liner_verif'];echo " ";echo $row_validacion['observ_cod_liner_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Bolsillo:                </td>
              <td id="fuente1"><input <?php if (!(strcmp($row_validacion['cod_bols_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="cod_bols_val" value="1" />
              <?php echo $row_egp['cb_bols_egp']; ?></td>
              <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_form_bols_verif'];echo " ";echo $row_validacion['observ_cod_bols_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><?php echo $row_egp['tipo_nom_egp']; ?>:</td>
              <td id="fuente1"><input name="cod_otro_val" type="checkbox" id="cod_otro_val" value="1" <?php if (!(strcmp($row_validacion['num_otro_val'],1))) {echo "checked=\"checked\"";} ?> />
              <?php echo $row_egp['cb_otro_egp']; ?></td>
              <td colspan="2" id="fuente3">- <?php echo $row_validacion['observ_form_otro_verif'];echo " ";echo $row_validacion['observ_cod_otro_val']; ?></td>
            </tr>  
  <tr>
    <td colspan="4" id="subppal2">OBSERVACIONES GENERALES </td>
    </tr>
  <tr>
    <td colspan="4" id="fuente3">1. Se debe de dejar una muestra de la bolsa terminada para archivo de producci&oacute;n y calidad. </td>
    </tr>
  <tr>
    <td colspan="4" id="subppal2">OTRAS OBSERVACIONES </td>
    </tr>
  <tr>
    <td colspan="4" id="fuente3"><?php echo $row_validacion['otras_observ_val']; ?></td>
    </tr>
  <tr>
    <td colspan="2" id="subppal2">FECHA DE MODIFICACION </td>
    <td colspan="2" id="subppal2">RESPONSABLE DE LA MODIFICACION </td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2"><?php echo $row_validacion['fecha_actualizado_val']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_validacion['actualizado_val']; ?></td>
  </tr>
</table>

</div>
</body>
</html>
<?php
mysql_free_result($validacion);

mysql_free_result($referencia);

mysql_free_result($verificacion);

mysql_free_result($revision);

mysql_free_result($egp);

mysql_free_result($ficha_tecnica);
?>
