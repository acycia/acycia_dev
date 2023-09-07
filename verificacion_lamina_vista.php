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
if (isset($_GET['id_verif_l'])) {
  $colname_referencia_revision = (get_magic_quotes_gpc()) ? $_GET['id_verif_l'] : addslashes($_GET['id_verif_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_revision = sprintf("SELECT * FROM Tbl_verificacion_lamina, Tbl_referencia, Tbl_revision_lamina WHERE Tbl_verificacion_lamina.id_verif_l = %s AND Tbl_verificacion_lamina.id_ref_verif_l=Tbl_referencia.id_ref AND  Tbl_referencia.id_ref = Tbl_revision_lamina.id_ref_rev_l ", $colname_referencia_revision);
$referencia_revision = mysql_query($query_referencia_revision, $conexion1) or die(mysql_error());
$row_referencia_revision = mysql_fetch_assoc($referencia_revision);
$totalRows_referencia_revision = mysql_num_rows($referencia_revision);

$colname_ref_egp = "-1";
if (isset($_GET['id_verif_l'])) {
  $colname_ref_egp = (get_magic_quotes_gpc()) ? $_GET['id_verif_l'] : addslashes($_GET['id_verif_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM Tbl_verificacion_lamina, Tbl_referencia, Tbl_egp WHERE Tbl_verificacion_lamina.id_verif_l = %s AND Tbl_verificacion_lamina.id_ref_verif_l=Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp", $colname_ref_egp);
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_ref_egp = mysql_num_rows($ref_egp);

$colname_validacion = "-1";
if (isset($_GET['id_verif_l'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_verif_l'] : addslashes($_GET['id_verif_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM Tbl_verificacion_lamina, Tbl_validacion_lamina WHERE Tbl_verificacion_lamina.id_verif_l = %s AND Tbl_verificacion_lamina.id_ref_verif_l = Tbl_validacion_lamina.id_ref_val_l", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_verif_l'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_verif_l'] : addslashes($_GET['id_verif_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM Tbl_verificacion_lamina, TblFichaTecnica WHERE Tbl_verificacion_lamina.id_verif_l = %s AND Tbl_verificacion_lamina.id_ref_verif_l = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_verif_l'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_verif_l'] : addslashes($_GET['id_verif_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM Tbl_verificacion_lamina,TblCertificacion WHERE Tbl_verificacion_lamina.id_verif_l = %s AND Tbl_verificacion_lamina.id_ref_verif_l= TblCertificacion.idref",$colname_certificacion_ref);
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
          <td colspan="2" id="fondo"><strong>II. VERIFICACION <?php echo $row_referencia_revision['id_verif_l']; ?></strong></td>
          <td nowrap="nowrap" id="fondo"><a href="verificacion_lamina_edit.php?id_verif_l=<?php echo $_GET['id_verif_l']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_referencia_revision['id_rev_l']; ?>"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val_l'] == '') { ?> <a href="validacion_lamina_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION"title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_lamina_vista.php?id_val_l=<?php echo $row_validacion['id_val_l']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft'] == '') { ?>
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
          <td id="fuente2"><?php echo $row_referencia_revision['fecha_verif_l']; ?></td>
          <td colspan="2" id="fuente2"><?php echo $row_referencia_revision['responsable_verif_l']; ?></td>
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
              <td id="fuente3"><input type="checkbox" name="ancho_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['ancho_verif_l'],1))) {echo "checked=\"checked\"";} ?>/>
Ancho</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_ancho_verif_l']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Largo: <?php echo $row_referencia_revision['largo_ref']; ?></td>
              <td id="fuente3"><input type="checkbox" name="largo_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['largo_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
Largo</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_largo_verif_l']; ?></td>
            </tr>            
            <tr>
              <td id="fuente3">Calibre: <?php echo $row_referencia_revision['calibre_ref']; ?></td>
              <td id="fuente3"><input type="checkbox" name="calibre_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['calibre_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
Calibre</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_calibre_verif_l']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Revisi&oacute;n Ortografica:
              <?php if ($row_referencia_revision['revi_ortog_verif_l']=='0'){echo "NO";}else {echo"SI";} ?></td>
              <td id="fuente3"><input type="checkbox" name="revi_ortog_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['revi_ortog_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
Revisi&oacute;n Ortografica</td>
              <td id="fuente3">-<?php echo $row_referencia_revision['observ_rev_textos_verif_l'];echo " "; echo $row_validacion['observ_rev_texto_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Revisi&oacute;n Textos:
              <?php if ($row_referencia_revision['rev_textos_verif_l']=='0'){echo "NO";}else {echo"SI";} ?></td>
              <td id="fuente3"><input type="checkbox" name="rev_textos_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['rev_textos_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
Revisi&oacute;n Textos</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_rev_textos_verif_l']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Tipo Extrusi&oacute;n: <?php echo $row_ref_egp['tipo_ext_egp']; ?></td>
              <td id="fuente3"><input name="rev_extru_verif_l" type="checkbox" id="rev_extru_verif" value="1"<?php if (!(strcmp($row_referencia_revision['rev_extru_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
Tipo Extrusion </td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_rev_extru_verif_l']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Pigmento Exterior: <?php echo $row_ref_egp['pigm_ext_egp']; ?></td>
              <td id="fuente3"><input type="checkbox" name="color_ext_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['color_ext_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
              Color Extrusi&oacute;n Exterior</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_color_ext_verif_l']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Pigmento Interior: <?php echo $row_ref_egp['pigm_int_epg']; ?></td>
              <td id="fuente3"><input type="checkbox" name="color_int_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['color_int_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
              Color Extrusi&oacute;n Interior</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_int_verif_l']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">No. Pistas: <?php echo $row_referencia_revision['int_numero_p_l'];?></td>
              <td id="fuente3"><input type="checkbox" name="num_pista_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['num_pista_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
No. Pistas</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_num_pista_verif_l']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">No. Repeticiones: <?php echo $row_referencia_revision['int_repeticion_l']; ?></td>
              <td id="fuente3"><input type="checkbox" name="num_repetic_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['num_repetic_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
No. Repeticiones</td>
              <td id="fuente3">-<?php echo $row_referencia_revision['observ_num_repetic_verif_l']; ?></td>
            </tr>
            
            <tr>
              <td id="fuente3">Embobinado:
                <?php switch($row_referencia_revision['N_embobinado_l']) {
	  case 0: echo "VACIO"; break;
	  case 1: ?>
                <img src="images/embobinado1.gif" alt="">
                <?php break;
	  case 2: ?>
                <img src="images/embobinado2.gif" alt="">
                <?php break;
	  case 3: ?>
                <img src="images/embobinado3.gif" alt="">
                <?php break;
	  case 4: ?>
                <img src="images/embobinado4.gif" alt="">
                <?php break;
	  case 5: ?>
                <img src="images/embobinado5.gif" alt="">
                <?php break;
	  case 6: ?>
                <img src="images/embobinado6.gif" alt="">
                <?php break;
	  case 7: ?>
                <img src="images/embobinado7.gif" alt="">
                <?php break;
	  case 8: ?>
                <img src="images/embobinado8.gif" alt="">
                <?php break;
	  case 9: ?>
                <img src="images/embobinado9.gif" alt="">
                <?php break;
	  case 10: ?>
                <img src="images/embobinado10.gif" alt="">
                <?php break;
	  case 11: ?>
                <img src="images/embobinado11.gif" alt="">
                <?php break;
	  case 12: ?>
                <img src="images/embobinado12.gif" alt="">
                <?php break;
	  case 13: ?>
                <img src="images/embobinado13.gif" alt="">
                <?php break;
	  case 14: ?>
                <img src="images/embobinado14.gif" alt="">
                <?php break;
	  case 15: ?>
                <img src="images/embobinado15.gif" alt="">
                <?php break;
	  case 16: ?>
                <img src="images/embobinado16.gif" alt="">
              <?php break;
	  } ?></td>
              <td id="fuente3"><input type="checkbox" name="rev_enbob_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['rev_enbob_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
Embobinado</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_rev_enbob_verif_l']; ?></td>
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
              <td id="fuente2"><input name="1color_verif_l" type="checkbox" id="1color_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['1color_verif_l'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_1color_verif_l']; echo " ";echo $row_validacion['observ_color1_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>2</strong> : <?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone2_egp']; ?></td>
              <td id="fuente2"><input name="2color_verif_l" type="checkbox" id="2color_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['2color_verif_l'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_2color_verif_l']; echo " ";echo $row_validacion['observ_color2_val']?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>3</strong> : <?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone3_egp']; ?></td>
              <td id="fuente2"><input name="3color_verif_l" type="checkbox" id="3color_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['3color_verif_l'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_3color_verif_l'];echo " ";echo $row_validacion['observ_color3_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>4</strong> : <?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone4_egp']; ?></td>
              <td id="fuente2"><input name="4color_verif_l" type="checkbox" id="4color_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['4color_verif_l'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_4color_verif_l'];echo " ";echo $row_validacion['observ_color4_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>5</strong> : <?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone5_egp']; ?></td>
              <td id="fuente2"><input name="5color_verif_l" type="checkbox" id="5color_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['5color_verif_l'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_5color_verif_l']; echo " ";echo $row_validacion['observacion_color5_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>6</strong> : <?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone6_egp']; ?></td>
              <td id="fuente2"><input name="6color_verif_l" type="checkbox" id="6color_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['6color_verif_l'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_6color_verif_l']; echo " ";echo $row_validacion['observacion_color6_val']; ?></td>
            </tr>
              <tr>
              <td id="fuente3"><strong>7</strong> : <?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone7_egp']; ?></td>
              <td id="fuente2"><input name="7color_verif_l" type="checkbox" id="7color_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['7color_verif_l'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_7color_verif_l'];echo " ";echo $row_validacion['observacion_color7_val'];?></td>
            </tr>
              <tr>
              <td id="fuente3"><strong>8</strong> : <?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone8_egp']; ?></td>
              <td id="fuente2"><input name="8color_verif_l" type="checkbox" id="8color_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['8color_verif_l'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_8color_verif_l'];echo " ";echo $row_validacion['observacion_color8_val'];?></td>
            </tr>                        
            <tr>
              <td colspan="2" id="fuente3">MARCA DE FOTOCELDA </td>
              <td id="fuente2"><input type="checkbox" name="marca_foto_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['marca_foto_verif_l'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_marca_foto_verif_l'];echo " "; echo $row_validacion['observ_marca_foto_val']; ?></td>
            </tr>
            <tr>
              <td colspan="2" id="fuente3">REFERENCIA</td>
              <td id="detalle2"><input <?php if (!(strcmp($row_referencia_revision['ref_verif_l'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ref_verif_l" value="1"></td>
              <td id="fuente3">- <?php  echo $row_referencia_revision['observ_ref_verif_l']; ?></td>
            </tr>
<tr>
              <td id="fuente3">Pagina Web:
              <?php if($row_referencia_revision['num_paginaw_verif_l']=='0'){echo "NO";}else{echo"SI";} ?></td>
              <td id="fuente3"><input type="checkbox" name="num_paginaw_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['num_paginaw_verif_l'],1))) {echo "checked=\"checked\"";} ?>>
Pagina Web</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_num_paginaw_verif_l']; ?></td>
            </tr>            
          </table></td>
        </tr>
        <tr id="tr1">
          <td colspan="4" id="subppal">VERIFICACION DE CODIGO DE BARRAS (Cumple Si / No)</td>
        </tr>
        <tr>
          <td colspan="4" align="center"><table id="tablainterna">
            <tr id="tr2">
              <td id="subppal2">PREIMPRESO</td>
              <td id="subppal2">NUMERO</td>
              <td id="subppal2">CUMPLE</td>
              <td id="subppal2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="fuente2"><?php if ($row_referencia_revision['b_preimp_verif_l']=='0'){echo "NO";}else {echo"SI";} ?></td>
              <td id="fuente2"><?php echo $row_referencia_revision['int_numero_l']; ?></td>
              <td id="fuente2"><input type="checkbox" name="b_preimp_verif_l" value="1"<?php if (!(strcmp($row_referencia_revision['b_preimp_verif_l'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_b_preimp_verif_l']; ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">OBSERVACIONES GENERALES </td>
        </tr>
        <tr>
          <td colspan="4" id="fuente3"><?php  if($row_ref_egp['observacion5_egp']!=''){echo "Obs. Referencia:".$row_ref_egp['observacion5_egp'];echo" ";}?>
          <?php echo $row_referencia_revision['observacion_verif_l']; ?>
          <?php echo $row_referencia_revision['observacion_rev_l']; ?></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">ARTE</td>
        </tr>
        <tr>
          <td colspan="2" id="subppal2">Nombre del Arte
          </td>
          <td id="subppal2">Estado del Arte </td>
          <td id="subppal2">Fecha Aprobaci&oacute;n</td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2"><?php $muestra=$row_referencia_revision['userfile_l']; ?>
          <a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"> <?php echo $muestra;?></a></td>
          <td id="fuente2"><?php if($row_referencia_revision['estado_arte_verif_l'] == '0') { echo "Pendiente"; } if($row_referencia_revision['estado_arte_verif_l'] == '1') { echo "Rechazado"; } if($row_referencia_revision['estado_arte_verif_l'] == '2') { echo "Aceptado"; } if($row_referencia_revision['estado_arte_verif_l'] == '3') { echo "Anulado"; } ?></td>
          <td id="fuente2"><?php echo $row_referencia_revision['responsable_verif_l']; ?></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">ULTIMA ACTUALIZACION </td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">- <?php echo $row_referencia_revision['fecha_edit_verif_l']; ?> -</td>
          <td colspan="2" id="fuente2">- <?php echo $row_referencia_revision['responsable_edit_verif_l']; ?>- </td>
        </tr>
  </table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencia_revision);

mysql_free_result($ref_egp);

//mysql_free_result($validacion);

//mysql_free_result($ficha_tecnica);
?>
