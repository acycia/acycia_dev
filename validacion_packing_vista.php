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

$colname_validacion = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM Tbl_validacion_packing WHERE id_val_p = %s", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_referencia_revision = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_referencia_revision = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_revision = sprintf("SELECT * FROM Tbl_validacion_packing, Tbl_referencia WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_ref_val_p = Tbl_referencia.id_ref", $colname_referencia_revision);
$referencia_revision = mysql_query($query_referencia_revision, $conexion1) or die(mysql_error());
$row_referencia_revision = mysql_fetch_assoc($referencia_revision);
$totalRows_referencia_revision = mysql_num_rows($referencia_revision);

$colname_verificacion = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM Tbl_validacion_packing, Tbl_verificacion_packing WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_ref_val_p = Tbl_verificacion_packing.id_ref_verif_p ", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_revision = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM Tbl_validacion_packing, Tbl_revision_packing WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_rev_val_p = Tbl_revision_packing.id_rev_p", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

$colname_egp = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM Tbl_validacion_packing, Tbl_referencia, Tbl_egp WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_ref_val_p = Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_egp);
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_egp = mysql_num_rows($ref_egp);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM Tbl_validacion_packing, TblFichaTecnica WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_ref_val_p = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_val_p'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_val_p'] : addslashes($_GET['id_val_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM Tbl_validacion_packing,TblCertificacion WHERE Tbl_validacion_packing.id_val_p = %s AND Tbl_validacion_packing.id_ref_val_p = TblCertificacion.idref",$colname_certificacion_ref);
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
          <td colspan="2" id="fondo"><strong>II. VALIDACION PACKING LIST<?php echo $row_validacion['id_val_p']; ?></strong></td>
          <td nowrap="nowrap" id="fondo"><a href="validacion_packing_edit.php?id_val_p=<?php echo $_GET['id_val_p']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0"/></a><a href="referencias_p.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_packing_vista.php?id_rev_p=<?php echo $row_revision['id_rev_p']; ?>"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val_p'] == '') { ?> <a href="validacion_packing_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION"title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_packing_vista.php?id_val_p=<?php echo $row_validacion['id_val_p']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft'] == '') { ?>
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
          <td id="fuente2"><?php echo $row_validacion['fecha_val_p']; ?></td>
          <td colspan="2" id="fuente2"><?php echo $row_validacion['responsable_val_p']; ?></td>
        </tr>
        <tr id="tr1">
          <td id="subppal2">REFERENCIA</td>
          <td id="subppal2"><!--EGP N&ordm;--></td>
          <td id="subppal2">COTIZACION N&ordm;</td>
        </tr>
        <tr>
          <td id="fuente2">REF :
            <input name="id_ref_val_p" type="hidden" value="<?php echo $row_referencia_revision['id_ref']; ?>" />
          <strong><?php echo $row_referencia_revision['cod_ref']; ?> - <?php echo $row_validacion['version_ref_val_p']; ?></strong><?php if ($row_referencia_revision['version_ref'] != $row_validacion['version_ref_val_p']) echo " Cambiar a vers: ".$row_referencia_revision['version_ref']?></td>
          <td id="fuente2">VERIFICACION : <a href="verificacion_packing_vista.php?id_verif_p=<?php echo $row_verificacion['id_verif_p']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_verificacion['id_verif_p']; ?></strong></a> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACIONES X REF" border="0" style="cursor:hand;" ></a></td>
          <td id="fuente2"><?php echo $row_referencia_revision['n_cotiz_ref']; ?></td>
        </tr>
        <tr>
          <td id="fuente2">REVISION
            <input name="id_rev_val_p" type="hidden" value="<?php echo $row_revision['id_rev_p']; ?>" />
: <a href="revision_packing_vista.php?id_rev_p=<?php echo $row_revision['id_rev_p']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_revision['id_rev_p']; ?></strong></a></td>
          <td colspan="2" id="fuente2">&nbsp;</td>
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
              <td id="fuente3"><input type="checkbox" name="ancho_val_p" value="1"<?php if (!(strcmp($row_validacion['ancho_val_p'],1))) {echo "checked=\"checked\"";} ?>/>
Ancho</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_ancho_val_p']; if( $row_verificacion['observ_ancho_verif_p']!=''){echo " ". $row_verificacion['observ_ancho_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3">Largo: <?php echo $row_referencia_revision['largo_ref']; ?></td>
              <td id="fuente3"><input type="checkbox" name="largo_val_p" value="1"<?php if (!(strcmp($row_validacion['largo_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Largo</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_largo_val_p'];if( $row_verificacion['observ_largo_verif_p']!=''){echo " ".$row_verificacion['observ_largo_verif_p'];} ?></td>
            </tr>            
            <tr>
              <td id="fuente3">Calibre: <?php echo $row_referencia_revision['calibre_ref']; ?></td>
              <td id="fuente3"><input type="checkbox" name="calibre_val_p" value="1"<?php if (!(strcmp($row_validacion['calibre_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Calibre</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_calibre_val_p'];if( $row_verificacion['observ_calibre_verif_p']!=''){echo " ". $row_verificacion['observ_calibre_verif_p'];}?></td>
            </tr>
<tr>
              <td id="fuente3">Presentacion: <?php echo $row_referencia_revision['Str_presentacion']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_presentacion_val_p" value="1"<?php if (!(strcmp($row_validacion['b_presentacion_val_p'],1))) {echo "checked=\"checked\"";} ?>>
                Presentacion</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_presentacion_val_p'];if( $row_verificacion['observ_presentacion_verif_p']!=''){echo " ". $row_verificacion['observ_presentacion_verif_p'];}?></td>
            </tr>
<tr>
  <td id="fuente3">Revisi&oacute;n Ortografica:
    <?php if ($row_referencia_revision['revi_ortog_val_p']=='0'){echo "NO";}else {echo"SI";} ?></td>
  <td id="fuente3"><input type="checkbox" name="revi_ortog_val_p" value="1"<?php if (!(strcmp($row_validacion['revi_ortog_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Revisi&oacute;n Ortografica</td>
  <td id="fuente3">- <?php echo $row_validacion['observ_revi_ortog_val_p'];echo " "; echo $row_verificacion['observ_revi_ortog_verif_p']; ?></td>
</tr>
<tr>
  <td id="fuente3">Revisi&oacute;n Textos:
    <?php if ($row_referencia_revision['rev_textos_val_p']=='0'){echo "NO";}else {echo"SI";} ?></td>
  <td id="fuente3"><input type="checkbox" name="rev_textos_val_p" value="1"<?php if (!(strcmp($row_validacion['rev_textos_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Revisi&oacute;n Textos</td>
  <td id="fuente3">- <?php echo $row_validacion['observ_rev_textos_val_p'];echo " "; echo $row_verificacion['observ_rev_textos_verif_p']; ?></td>
</tr>            
            <tr>
              <td id="fuente3">Pigmento Exterior: <?php echo $row_referencia_revision['Str_lamina1_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="color_ext_val_p" value="1"<?php if (!(strcmp($row_validacion['color_ext_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Color Extrusi&oacute;n Exterior</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_color_ext_val_p'];if( $row_verificacion['observ_color_ext_verif_p']!=''){echo " ". $row_verificacion['observ_color_ext_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3">Pigmento Interior: <?php echo $row_referencia_revision['Str_lamina2_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="color_int_val_p" value="1"<?php if (!(strcmp($row_validacion['color_int_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Color Extrusi&oacute;n Interior</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_int_val_p'];if( $row_verificacion['observ_int_verif_p']!=''){echo " ". $row_verificacion['observ_int_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3">Color Material Adhesivos:</td>
              <td id="fuente3"><input name="color_material_val_p" type="checkbox" id="color_material_val_p" value="1"<?php if (!(strcmp($row_validacion['color_material_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Color Material Adhesivo</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_color_material_val_p'];if( $row_verificacion['observ_color_material_verif_p']!=''){echo " ". $row_verificacion['observ_color_material_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3">Boca de Entrada: <?php echo $row_referencia_revision['Str_boca_entr_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_boca_entr_val_p" value="1"<?php if (!(strcmp($row_validacion['b_boca_entr_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Boca de Entrada</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_boca_entr_val_p'];if( $row_verificacion['observ_boca_entr_verif_p']!=''){echo " ". $row_verificacion['observ_boca_entr_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3">Ubicacion de la Entrada: <?php echo $row_referencia_revision['Str_entrada_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_entrada_val_p" value="1"<?php if (!(strcmp($row_validacion['b_entrada_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Ubicacion de la Entrada</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_entrada_val_p'];if( $row_verificacion['observ_entrada_verif_p']!=''){echo " ". $row_verificacion['observ_entrada_verif_p'];}?></td>
            </tr>
<tr>
              <td id="fuente3">Lamina 1: <?php echo $row_referencia_revision['Str_lamina1_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_lamina1_val_p" value="1"<?php if (!(strcmp($row_validacion['b_lamina1_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Lamina 1</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_lamina1_val_p'];if( $row_verificacion['observ_lamina1_verif_p']!=''){echo " ". $row_verificacion['observ_lamina1_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3">Lamina 2: <?php echo $row_referencia_revision['Str_lamina2_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_lamina2_val_p" value="1"<?php if (!(strcmp($row_validacion['b_lamina2_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Lamina 2</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_lamina2_val_p'];if( $row_verificacion['observ_lamina2_verif_p']!=''){echo " ". $row_verificacion['observ_lamina2_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3">Rodillo: <?php echo $row_revision['int_rodillo_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_rodillo_val_p" value="1"<?php if (!(strcmp($row_validacion['b_rodillo_val_p'],1))) {echo "checked=\"checked\"";} ?>>
Rodillo</td>
              <td id="fuente3">- <?php echo $row_validacion['observ_rodillo_val_p'];if( $row_verificacion['observ_rodillo_verif_p']!=''){echo " ". $row_verificacion['observ_rodillo_verif_p'];}?></td>
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
              <td id="fuente2"><input name="1color_val_p" type="checkbox" id="1color_val_p" value="1"<?php if (!(strcmp($row_validacion['1color_val_p'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_1color_val_p'];if( $row_verificacion['observ_1color_verif_p']!=''){echo " ". $row_verificacion['observ_1color_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>2</strong> : <?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone2_egp']; ?></td>
              <td id="fuente2"><input name="2color_val_p" type="checkbox" id="2color_val_p" value="1"<?php if (!(strcmp($row_validacion['2color_val_p'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_2color_val_p'];if( $row_verificacion['observ_2color_verif_p']!=''){echo " ". $row_verificacion['observ_2color_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>3</strong> : <?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone3_egp']; ?></td>
              <td id="fuente2"><input name="3color_val_p" type="checkbox" id="3color_val_p" value="1"<?php if (!(strcmp($row_validacion['3color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_3color_val_p'];if( $row_verificacion['observ_3color_verif_p']!=''){echo " ". $row_verificacion['observ_3color_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>4</strong> : <?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone4_egp']; ?></td>
              <td id="fuente2"><input name="4color_val_p" type="checkbox" id="4color_val_p" value="1"<?php if (!(strcmp($row_validacion['4color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_4color_val_p'];if( $row_verificacion['observ_4color_verif_p']!=''){echo " ". $row_verificacion['observ_4color_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>5</strong> : <?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone5_egp']; ?></td>
              <td id="fuente2"><input name="5color_val_p" type="checkbox" id="5color_val_p" value="1"<?php if (!(strcmp($row_validacion['5color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_5color_val_p'];if( $row_verificacion['observ_5color_verif_p']!=''){echo " ". $row_verificacion['observ_5color_verif_p'];}?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>6</strong> : <?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone6_egp']; ?></td>
              <td id="fuente2"><input name="6color_val_p" type="checkbox" id="6color_val_p" value="1"<?php if (!(strcmp($row_validacion['6color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_6color_val_p'];if( $row_verificacion['observ_6color_verif_p']!=''){echo " ". $row_verificacion['observ_6color_verif_p'];}?></td>
            </tr>
              <tr>
              <td id="fuente3"><strong>7</strong> : <?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone7_egp']; ?></td>
              <td id="fuente2"><input name="7color_val_p" type="checkbox" id="7color_val_p" value="1"<?php if (!(strcmp($row_validacion['7color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_7color_val_p'];if( $row_verificacion['observ_7color_verif_p']!=''){echo " ". $row_verificacion['observ_7color_verif_p'];}?></td>
            </tr>
              <tr>
              <td id="fuente3"><strong>8</strong> : <?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone8_egp']; ?></td>
              <td id="fuente2"><input name="8color_val_p" type="checkbox" id="8color_val_p" value="1"<?php if (!(strcmp($row_validacion['8color_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_8color_val_p'];if( $row_verificacion['observ_8color_verif_p']!=''){echo " ". $row_verificacion['observ_8color_verif_p'];}?></td>
            </tr>                        
            <tr>
              <td colspan="2" id="fuente3">MARCA DE FOTOCELDA </td>
              <td id="fuente2"><input type="checkbox" name="marca_foto_val_p" value="1"<?php if (!(strcmp($row_validacion['marca_foto_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php  if( $row_verificacion['marca_foto_verif_p']!=''){echo " ". $row_verificacion['marca_foto_verif_p'];}else{echo $row_validacion['marca_foto_val_p'];}?></td>
            </tr>
            <tr>
              <td colspan="2" id="fuente3">REFERENCIA</td>
              <td id="detalle2"><input <?php if (!(strcmp($row_validacion['ref_val_p'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ref_val_p" value="1"></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_ref_val_p'];if( $row_verificacion['observ_ref_verif_p']!=''){echo " ". $row_verificacion['observ_ref_verif_p'];}?></td>
            </tr>
<tr>
              <td colspan="2"id="fuente3">PAGINA WEB:
              <?php if($row_referencia_revision['num_paginaw_val_p']=='0'){echo "NO";}else{echo"SI";} ?></td>
            <td id="fuente2"><input type="checkbox" name="num_paginaw_val_p" value="1"<?php if (!(strcmp($row_validacion['num_paginaw_val_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_validacion['observ_num_paginaw_val_p'];if( $row_verificacion['observ_num_paginaw_verif_p']!=''){echo " ". $row_verificacion['observ_num_paginaw_verif_p'];}?></td>
            </tr>            
          </table></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><table id="tablainterna">
            
          </table></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">OBSERVACIONES GENERALES </td>
        </tr>
        <tr>
          <td colspan="4" id="fuente3"><?php  if($row_ref_egp['observacion5_egp']!=''){echo "Obs. Referencia:".$row_ref_egp['observacion5_egp'];echo" ";}?>
          <?php echo $row_validacion['str_obs_general_p']; echo " ".$row_revision['str_obs_general_p']; ?></td>
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
          <td colspan="2" id="fuente2"><?php $muestra=$row_validacion['userfile_p']; ?>
          <a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"> <?php echo $muestra;?></a></td>
          <td id="fuente2"><?php if($row_validacion['estado_arte_val_p'] == '0') { echo "Pendiente"; } if($row_validacion['estado_arte_val_p'] == '1') { echo "Rechazado"; } if($row_validacion['estado_arte_val_p'] == '2') { echo "Aceptado"; } if($row_validacion['estado_arte_val_p'] == '3') { echo "Anulado"; } ?></td>
          <td id="fuente2"><?php echo $row_validacion['responsable_val_p']; ?></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">ULTIMA ACTUALIZACION </td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">- <?php echo $row_validacion['fecha_edit_val_p']; ?> -</td>
          <td colspan="2" id="fuente2">- <?php echo $row_validacion['responsable_edit_val_p']; ?>- </td>
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

mysql_free_result($verificacion);

mysql_free_result($revision);

//mysql_free_result($ficha_tecnica);
?>
