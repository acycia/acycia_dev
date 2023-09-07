<?php require_once('Connections/conexion1.php'); ?>
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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_referencia_revision = "-1";
if (isset($_GET['id_verif'])) {
  $colname_referencia_revision = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_revision = sprintf("SELECT * FROM verificacion, Tbl_referencia, revision WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif=Tbl_referencia.id_ref AND  Tbl_referencia.id_ref = revision.id_ref_rev ", $colname_referencia_revision);
$referencia_revision = mysql_query($query_referencia_revision, $conexion1) or die(mysql_error());
$row_referencia_revision = mysql_fetch_assoc($referencia_revision);
$totalRows_referencia_revision = mysql_num_rows($referencia_revision);

$colname_ref_egp = "-1";
if (isset($_GET['id_verif'])) {
  $colname_ref_egp = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM verificacion, Tbl_referencia, Tbl_egp WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif=Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp", $colname_ref_egp);
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_ref_egp = mysql_num_rows($ref_egp);

$colname_validacion = "-1";
if (isset($_GET['id_verif'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM verificacion, validacion WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif = validacion.id_ref_val", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_verif'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM verificacion, TblFichaTecnica WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_verif'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM verificacion,TblCertificacion WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif = TblCertificacion.idref",$colname_certificacion_ref);
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
<body><div align="center">
<table id="tablainterna">
<tr>
    <td nowrap="nowrap" id="subppal">CODIGO : R1 - F01 </td>
    <td colspan="2" nowrap="nowrap" id="principal">PLAN DE DISE&Ntilde;O &amp; DESARROLLO </td>
    <td nowrap="nowrap" id="subppal">VERSION : 4</td>
</tr>
<tr>
<td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
          <td colspan="2" id="fondo"><strong>II. VERIFICACION <?php echo $row_referencia_revision['id_verif']; ?></strong></td>
          <td nowrap="nowrap" id="fondo"><a href="verificacion_edit.php?id_verif=<?php echo $_GET['id_verif']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_vista.php?id_rev=<?php echo $row_referencia_revision['id_rev']; ?>"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val'] == '') { ?> <a href="validacion_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION"title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft'] == '') { ?>
      <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else{ ?>
      <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>      <?php } ?>
              <?php if($row_certificacion['idcc']=='') { ?>
        <a href="certificacion_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
        </a><?php } ?></td>
    </tr>
        <tr>
          <td id="subppal2">FECHA DE REGISTRO </td>
          <td colspan="2" id="subppal2">RESPONSABLE</td>
    </tr>
        <tr>
          <td id="fuente2"><?php echo $row_referencia_revision['fecha_verif']; ?></td>
          <td colspan="2" id="fuente2"><?php echo $row_referencia_revision['responsable_verif']; ?></td>
        </tr>
        <tr id="tr1">
          <td id="subppal2">REFERENCIA</td>
          <td id="subppal2"><!--EGP N&ordm;--></td>
          <td id="subppal2">COTIZACION N&ordm;</td>
        </tr>
        <tr>
          <td id="fuente2"><strong><?php echo $row_referencia_revision['cod_ref']; ?> - <?php echo $row_referencia_revision['version_ref']; ?></strong></td><td id="fuente2"><?php //echo $row_referencia_revision['n_egp_ref']; ?></td>
          <td id="fuente2"><?php echo $row_referencia_revision['n_cotiz_ref']; ?></td>
        </tr>
        <tr>
          <td id="fondo"><?php if($row_referencia_revision['estado_ref'] == '1') { echo "Activa"; } else { echo "Inactiva"; } ?></td>
          <td colspan="2" id="fondo">&nbsp;</td>
    </tr>
        <tr>
          <td colspan="4" id="subppal2">LISTADO DE VERIFICACION DE PARAMETROS GENERALES (Cumple Si / No)</td>
    </tr>
        <tr>
          <td colspan="4" align="center"><table id="tablainterna">
            <tr id="tr1">
              <td id="subppal2">DATO</td>
              <td id="subppal2">CUMPLE</td>
              <td id="subppal2">OBSERVACION</td> 
            </tr>
            <tr>
              <td id="fuente3">Ancho: <?php echo $row_referencia_revision['ancho_ref']; ?></td>
              <td id="fuente3"><input name="ancho_verif" type="checkbox" value="1" <?php if (!(strcmp($row_referencia_revision['ancho_verif'],1))) {echo "checked=\"checked\"";} ?> />
                Ancho</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_ancho_verif'];echo " ";echo $row_validacion['observ_ancho_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3">Largo: <?php echo $row_referencia_revision['largo_ref']; ?></td>
              <td id="fuente3"><input name="largo_verif" type="checkbox" value="1" <?php if (!(strcmp($row_referencia_revision['largo_verif'],1))) {echo "checked=\"checked\"";} ?>>
                Largo</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_largo_verif'];echo " "; echo $row_validacion['observ_altura_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Solapa: <?php echo $row_referencia_revision['solapa_ref']; ?></td>
              <td id="fuente3"><input <?php if (!(strcmp($row_referencia_revision['solapa_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="solapa_verif" value="1">
                Solapa</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_solapa_verif']; echo " "; echo $row_validacion['observ_solapa_val'];?></td>
            </tr>            
            <tr>
              <td id="fuente3">Distribuci&oacute;n entre Logos y Bordes</td>
              <td id="fuente3"><input <?php if (!(strcmp($row_referencia_revision['dist_logo_borde_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="dist_logo_borde_verif" value="1">
Distribuci&oacute;n entre Logos y Bordes </td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_logo_borde_verif']; echo " "; echo $row_validacion['observ_dist_logo_borde_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3">Revisi&oacute;n Textos</td>
              <td id="fuente3"><input <?php if (!(strcmp($row_referencia_revision['rev_textos_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="rev_textos_verif" value="1">
Revisi&oacute;n Textos </td>
              <td id="fuente3">-<?php echo $row_referencia_revision['observ_rev_textos_verif'];echo " "; echo $row_validacion['observ_rev_texto_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Revisi&oacute;n Ortografica</td>
              <td id="fuente3"><input <?php if (!(strcmp($row_referencia_revision['rev_ortog_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="rev_ortog_verif" value="1">
Revisi&oacute;n Ortografica </td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_rev_ortog_verif'];echo " ";echo $row_validacion['observ_rev_ortog_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Bolsillo Portagu&iacute;a: <?php echo $row_referencia_revision['bolsillo_guia_ref']; ?></td>
              <td id="fuente3"><input <?php if (!(strcmp($row_referencia_revision['rev_portag_verif'],1))) {echo "checked=\"checked\"";} ?> name="rev_portag_verif" type="checkbox" id="rev_portag_verif" value="1" />
Bolsillo Portaguia</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_portag_verif'];echo " ";echo $row_validacion['observ_portag_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Tipo Extrusi&oacute;n: <?php echo $row_ref_egp['tipo_ext_egp']; ?></td>
              <td id="fuente3"><input <?php if (!(strcmp($row_referencia_revision['rev_extru_verif'],1))) {echo "checked=\"checked\"";} ?> name="rev_extru_verif" type="checkbox" id="rev_extru_verif" value="1" />
Tipo Extrusi&oacute;n</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_extru_verif']; echo " ";echo $row_validacion['observ_extru_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3">Pigmento Exterior: <?php echo $row_ref_egp['pigm_ext_egp']; ?></td>
              <td id="fuente3"><input <?php if (!(strcmp($row_referencia_revision['color_ext_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color_ext_verif" value="1">
                Color Extrusi&oacute;n Exterior </td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_color_ext_verif'];echo " "; echo $row_validacion['observ_color_ext_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Pigmento Interior: <?php echo $row_ref_egp['pigm_int_epg']; ?></td>
              <td id="fuente3"><input <?php if (!(strcmp($row_referencia_revision['color_int_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="color_int_verif" value="">
                Color Extrusi&oacute;n Interior </td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_color_int_verif'];echo " ";echo $row_validacion['observ_color_int_val']; ?></td>
            </tr>
          </table></td>
    </tr>
        <tr>
          <td colspan="4" id="subppal2">VERIFICACION DE COLORES DE IMPRESION (Cumple Si / No)</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><table id="tablainterna">
            <tr>
              <td id="subppal2">COLOR</td>
              <td id="subppal2">PANTONE</td>
              <td id="subppal2">CUMPLE</td>
              <td id="subppal2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="fuente3"><strong>1</strong> : <?php echo $row_ref_egp['color1_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone1_egp']; ?></td>
              <td id="fuente2"><input name="1color_verif" type="checkbox" id="1color_verif" value="1" <?php if (!(strcmp($row_referencia_revision['1color_verif'],1))) {echo "checked=\"checked\"";} ?> /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_1color_verif']; echo " ";echo $row_validacion['observ_color1_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>2</strong> : <?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone2_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['2color_verif'],1))) {echo "checked=\"checked\"";} ?> name="2color_verif" type="checkbox" id="2color_verif" value="1" /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_2color_verif']; echo " ";echo $row_validacion['observ_color2_val']?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>3</strong> : <?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone3_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['3color_verif'],1))) {echo "checked=\"checked\"";} ?> name="3color_verif" type="checkbox" id="3color_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_3color_verif'];echo " ";echo $row_validacion['observ_color3_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>4</strong> : <?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone4_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['4color_verif'],1))) {echo "checked=\"checked\"";} ?> name="4color_verif" type="checkbox" id="4color_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_4color_verif'];echo " ";echo $row_validacion['observ_color4_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>5</strong> : <?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone5_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['5color_verif'],1))) {echo "checked=\"checked\"";} ?> name="5color_verif" type="checkbox" id="5color_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_5color_verif']; echo " ";echo $row_validacion['observacion_color5_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>6</strong> : <?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone6_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['6color_verif'],1))) {echo "checked=\"checked\"";} ?> name="6color_verif" type="checkbox" id="6color_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_6color_verif']; echo " ";echo $row_validacion['observacion_color6_val']; ?></td>
            </tr>
              <tr>
              <td id="fuente3"><strong>7</strong> : <?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone7_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['7color_verif'],1))) {echo "checked=\"checked\"";} ?> name="7color_verif" type="checkbox" id="7color_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_7color_verif'];echo " ";echo $row_validacion['observacion_color7_val'];?></td>
            </tr>
              <tr>
              <td id="fuente3"><strong>8</strong> : <?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone8_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['8color_verif'],1))) {echo "checked=\"checked\"";} ?> name="8color_verif" type="checkbox" id="8color_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_8color_verif'];echo " ";echo $row_validacion['observacion_color8_val'];?></td>
            </tr>                        
            <tr>
              <td id="fuente3">MARCA DE FOTOCELDA </td>
              <td id="fuente3">&nbsp;</td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['marca_foto_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="marca_foto_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_marca_foto_verif'];echo " "; echo $row_validacion['observ_marca_foto_val']; ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">VERIFICACION DE NUMERACION (Cumple Si / No)</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><table id="tablainterna">
            <tr>
              <td id="subppal2">POSICIONES</td>
              <td id="subppal2">CUMPLE</td>
              <td id="subppal2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="fuente3">Talonario Recibo: <?php echo $row_ref_egp['tipo_solapatr_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['alt_tal_rec_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="alt_tal_rec_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_alt_tal_rec_verif'];echo " ";echo $row_validacion['observ_num_tal_rec_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3">Cinta de Seguridad: <?php echo $row_ref_egp['tipo_cinta_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['alt_cinta_seg_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="alt_cinta_seg_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_alt_cinta_seg_verif'];echo " ";echo $row_validacion['observ_num_cinta_seg_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3">Principal: <?php echo $row_ref_egp['tipo_principal_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['alt_ppal_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="alt_ppal_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_alt_ppal_verif'];echo " ";echo $row_validacion['observ_num_ppal_val'];
			  ?></td>
            </tr>
            <tr>
              <td id="fuente3">Inferior: <?php echo $row_ref_egp['tipo_inferior_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['alt_inf_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="alt_inf_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_alt_inf_verif'];echo " ";echo $row_validacion['observ_num_inf_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Liner: <?php echo $row_ref_egp['tipo_liner_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_verificacion_edit['alt_liner_verif'],1))) {echo "checked=\"checked\"";} ?> name="alt_liner_verif" type="checkbox" id="alt_liner_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_alt_liner_verif'];echo " ";echo $row_validacion['observ_alt_liner_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Bolsillo: <?php echo $row_ref_egp['tipo_bols_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_verificacion_edit['alt_bols_verif'],1))) {echo "checked=\"checked\"";} ?> name="alt_bols_verif" type="checkbox" id="alt_bols_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_alt_bols_verif'];echo " ";echo $row_validacion['observ_alt_bols_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><?php echo $row_ref_egp['tipo_nom_egp']; ?>:<?php echo $row_ref_egp['tipo_otro_egp']; ?></td>
              <td id="fuente2"><input name="alt_otro_verif" <?php if (!(strcmp($row_verificacion_edit['alt_otro_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" id="alt_otro_verif" value="1"></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_alt_otro_verif'];echo " ";echo $row_validacion['observ_alt_otro_val']; ?></td>
            </tr>            
          </table></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">VERIFICACION DE CODIGO DE BARRAS (Cumple Si / No)</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><table id="tablainterna">
            <tr>
              <td id="subppal2">POSICIONES</td>
              <td id="subppal2">CUMPLE</td>
              <td id="subppal2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="fuente3">Talonario Recibo:
                <?php echo $row_ref_egp['cb_solapatr_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['form_tal_rec_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_tal_rec_verif" value="1" /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_form_tal_rec_verif']; echo " ";echo $row_validacion['observ_cod_tal_rec_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3">Cinta de Seguridad:
                <?php echo $row_ref_egp['cb_cinta_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['form_cinta_seg_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_cinta_seg_verif" value="1" /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_form_cinta_seg_verif']; echo " ";echo $row_validacion['observ_cod_cinta_seg_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3">Principal:
                <?php echo $row_ref_egp['cb_principal_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['form_ppal_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_ppal_verif" value="1" /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_form_ppal_verif']; echo " ";echo $row_validacion['observ_cod_ppal_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3">Inferior:                <?php echo $row_ref_egp['cb_inferior_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['form_inf_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_inf_verif" value="1" /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_form_inf_verif'];echo " ";echo $row_validacion['observ_cod_inf_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Liner:                <?php echo $row_ref_egp['cb_bols_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['form_liner_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_inf_verif" value="1" /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_form_liner_verif'];echo " ";echo $row_validacion['observ_form_liner_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Bolsillo:                <?php echo $row_ref_egp['cb_inferior_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['form_bols_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_inf_verif" value="1" /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_form_bols_verif'];echo " ";echo $row_validacion['observ_form_bols_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><?php echo $row_ref_egp['tipo_nom_egp']; ?>:<?php echo $row_ref_egp['cb_otro_egp']; ?></td>
              <td id="fuente2"><input <?php if (!(strcmp($row_referencia_revision['form_otro_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_inf_verif" value="1" /></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_form_otro_verif'];echo " ";echo $row_validacion['observ_form_otro_val']; ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">VERIFICACION DE CIRELES (Cumple con la Entrega Si / No)</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><table id="tablainterna">
            <tr>
              <td id="subppal2">COLORES</td>
              <td id="subppal2">CUMPLE</td>              
              <td id="subppal2">OBSERVACION</td>              
            </tr>
            <tr>
              <td id="fuente3"><strong>1. </strong><?php echo $row_ref_egp['color1_egp']; ?></td>
              <td id="fuente2"><?php if($row_referencia_revision['1color_cirel'] == '1') { ?> 
			    <img src="images/cumple.gif"><?php } else { ?>
			    <img src="images/opcion3.gif"><?php } ?></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_1color']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>2. </strong><?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="fuente2"><?php if($row_referencia_revision['2color_cirel'] == '1') { ?>
                <img src="images/cumple.gif">
                <?php } else { ?>
                <img src="images/opcion3.gif">
              <?php } ?></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_2color']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>3. </strong><?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="fuente2"><?php if($row_referencia_revision['3color_cirel'] == '1') { ?>
                <img src="images/cumple.gif">
                <?php } else { ?><img src="images/opcion3.gif">
                <?php } ?></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_3color']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>4. </strong><?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="fuente2"><?php if($row_referencia_revision['4color_cirel'] == '1') { ?>
                <img src="images/cumple.gif">
                <?php } else { ?><img src="images/opcion3.gif">
                <?php } ?></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_4color']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>5. </strong><?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="fuente2"><?php if($row_referencia_revision['5color_cirel'] == '1') { ?>
                <img src="images/cumple.gif">
                <?php } else { ?><img src="images/opcion3.gif">
                <?php } ?></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_5color']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>6. </strong><?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="fuente2"><?php if($row_referencia_revision['6color_cirel'] == '1') { ?>
                <img src="images/cumple.gif">
                <?php } else { ?><img src="images/opcion3.gif">
              <?php } ?></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_6color']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>7. </strong><?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="fuente2"><?php if($row_referencia_revision['7color_cirel'] == '1') { ?>
                <img src="images/cumple.gif">
                <?php } else { ?><img src="images/opcion3.gif">
              <?php } ?></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_7color']; ?></td>
            </tr> 
            <tr>
              <td id="fuente3"><strong>8. </strong><?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="fuente2"><?php if($row_referencia_revision['8color_cirel'] == '1') { ?>
                <img src="images/cumple.gif">
                <?php } else { ?><img src="images/opcion3.gif">
              <?php } ?></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_8color']; ?></td>
            </tr>                      
            <tr>
              <td id="fuente3">MANGA</td>
              <td id="fuente2"><?php echo $row_referencia_revision['rodillo_cirel']; ?> cm</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_rodillo']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">PISTAS</td>
              <td id="fuente2"><?php echo $row_referencia_revision['pistas_cirel']; ?> cm</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_pistas']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">REPETICION</td>
              <td id="fuente2"><?php echo $row_referencia_revision['repeticion_cirel']; ?> cm</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_repeticion']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">DISTANCIA ENTRE GUIAS</td>
              <td id="fuente2"><?php echo $row_referencia_revision['distancia_logos_cirel']; ?> mm</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_distancia_logos']; ?></td>
            </tr>
            <!-- <tr>
              <td id="fuente3">CONCORDANCIA DE TEXTO</td>
              <td id="fuente2"><?php if($row_referencia_revision['concuerda_texto_cirel'] == '1') { ?>
                <img src="images/cumple.gif">
                <?php } else { ?><img src="images/opcion3.gif">
                 <?php } ?>
              </td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observacion_concuerda_texto']; ?></td>
            </tr> -->
            </table>
            <table id="tablainterna">
            <tr>
              <td id="subppal2">FECHA DE ENTREGA</td>
              <td id="subppal2">REGISTRO</td>
              <td id="subppal2">MODIFICACION</td>
            </tr>
            <tr>
              <td id="fuente2"><?php echo $row_referencia_revision['fecha_entrega_cirel']; ?></td>
              <td id="fuente2"><?php echo $row_referencia_revision['registro_cirel']." ".$row_referencia_revision['fecha_registro_cirel']; ?></td>
              <td id="fuente2"><?php echo $row_referencia_revision['modificacion_cirel']." ".$row_referencia_revision['fecha_modificacion_cirel']; ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">OBSERVACIONES GENERALES </td>
        </tr>
        <tr>
          <td colspan="4" id="fuente3"><?php echo $row_referencia_revision['observacion_verif']; ?></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">ARTE</td>
        </tr>
        <tr>
          <td colspan="2" id="subppal2">Nombre del Arte
          </td>
          <td id="subppal2">Estado del Arte <!--<a href="javascript:verFoto('<?php $prueba="https://www.dropbox.com/s/vmh6c8divqzt2tc/645-00.pdf"; echo $prueba;?>','610','490')"> <?php echo $prueba;?></a>--></td>
          <td id="subppal2">Fecha Aprobaci&oacute;n</td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2"><?php $muestra= $row_referencia_revision['userfile']; ?><a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"> <?php echo $muestra;?></a></td>
          <td id="fuente2"><?php if($row_referencia_revision['estado_arte_verif'] == '0') { echo "Pendiente"; } if($row_referencia_revision['estado_arte_verif'] == '1') { echo "Rechazado"; } if($row_referencia_revision['estado_arte_verif'] == '2') { echo "Aceptado"; } if($row_referencia_revision['estado_arte_verif'] == '3') { echo "Anulado"; } ?></td>
          <td id="fuente2"><?php echo $row_referencia_revision['fecha_aprob_arte_verif']; ?></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">ULTIMA ACTUALIZACION </td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">- <?php echo $row_referencia_revision['fecha_edit_verif']; ?> -</td>
          <td colspan="2" id="fuente2">- <?php echo $row_referencia_revision['responsable_edit_verif']; ?>- </td>
        </tr>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencia_revision);

mysql_free_result($ref_egp);

mysql_free_result($validacion);

mysql_free_result($ficha_tecnica);
?>
