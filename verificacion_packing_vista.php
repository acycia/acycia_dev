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
if (isset($_GET['id_verif_p'])) {
  $colname_referencia_revision = (get_magic_quotes_gpc()) ? $_GET['id_verif_p'] : addslashes($_GET['id_verif_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_revision = sprintf("SELECT * FROM Tbl_verificacion_packing, Tbl_referencia, Tbl_revision_packing WHERE Tbl_verificacion_packing.id_verif_p = %s AND Tbl_verificacion_packing.id_ref_verif_p=Tbl_referencia.id_ref AND  Tbl_referencia.id_ref = Tbl_revision_packing.id_ref_rev_p ", $colname_referencia_revision);
$referencia_revision = mysql_query($query_referencia_revision, $conexion1) or die(mysql_error());
$row_referencia_revision = mysql_fetch_assoc($referencia_revision);
$totalRows_referencia_revision = mysql_num_rows($referencia_revision);

$colname_ref_egp = "-1";
if (isset($_GET['id_verif_p'])) {
  $colname_ref_egp = (get_magic_quotes_gpc()) ? $_GET['id_verif_p'] : addslashes($_GET['id_verif_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM Tbl_verificacion_packing, Tbl_referencia, Tbl_egp WHERE Tbl_verificacion_packing.id_verif_p = %s AND Tbl_verificacion_packing.id_ref_verif_p=Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp", $colname_ref_egp);
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_ref_egp = mysql_num_rows($ref_egp);

$colname_validacion = "-1";
if (isset($_GET['id_verif_p'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_verif_p'] : addslashes($_GET['id_verif_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM Tbl_verificacion_packing, Tbl_validacion_packing WHERE Tbl_verificacion_packing.id_verif_p = %s AND Tbl_verificacion_packing.id_ref_verif_p = Tbl_validacion_packing.id_ref_val_p", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_verif_p'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_verif_p'] : addslashes($_GET['id_verif_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM Tbl_verificacion_packing, TblFichaTecnica WHERE Tbl_verificacion_packing.id_verif_p = %s AND Tbl_verificacion_packing.id_ref_verif_p = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_verif_p'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_verif_p'] : addslashes($_GET['id_verif_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM Tbl_verificacion_packing,TblCertificacion WHERE Tbl_verificacion_packing.id_verif_p = %s AND Tbl_verificacion_packing.id_ref_verif_p= TblCertificacion.idref",$colname_certificacion_ref);
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
          <td colspan="2" id="fondo"><strong>II. VERIFICACION <?php echo $row_referencia_revision['id_verif_p']; ?></strong></td>
          <td nowrap="nowrap" id="fondo"><a href="verificacion_packing_edit.php?id_verif_p=<?php echo $_GET['id_verif_p']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0"/></a><a href="referencias_p.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_packing_vista.php?id_rev_p=<?php echo $row_referencia_revision['id_rev_p']; ?>"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val_p'] == '') { ?> <a href="validacion_packing_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION"title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_packing_vista.php?id_val_p=<?php echo $row_validacion['id_val_p']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft'] == '') { ?>
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
          <td id="fuente2"><?php echo $row_referencia_revision['fecha_verif_p']; ?></td>
          <td colspan="2" id="fuente2"><?php echo $row_referencia_revision['responsable_verif_p']; ?></td>
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
              <td id="fuente3"><input type="checkbox" name="ancho_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['ancho_verif_p'],1))) {echo "checked=\"checked\"";} ?>/>
Ancho</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_ancho_verif_p']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Largo: <?php echo $row_referencia_revision['largo_ref']; ?></td>
              <td id="fuente3"><input type="checkbox" name="largo_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['largo_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Largo</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_largo_verif_p']; ?></td>
            </tr>            
            <tr>
              <td id="fuente3">Calibre: <?php echo $row_referencia_revision['calibre_ref']; ?></td>
              <td id="fuente3"><input type="checkbox" name="calibre_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['calibre_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Calibre</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_calibre_verif_p']; ?></td>
            </tr>
<tr>
              <td id="fuente3">Presentacion: <?php echo $row_referencia_revision['Str_presentacion']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_presentacion_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['b_presentacion_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
                Presentacion</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_presentacion_verif_p'];echo ""; echo $row_validacion['observ_rev_textos_val_p']; ?></td>
            </tr>
<tr>
  <td id="fuente3">Revisi&oacute;n Ortografica:
    <?php if ($row_referencia_revision['revi_ortog_verif_p']=='0'){echo "NO";}else {echo"SI";} ?></td>
  <td id="fuente3"><input type="checkbox" name="revi_ortog_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['revi_ortog_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Revisi&oacute;n Ortografica</td>
  <td id="fuente3">- <?php echo $row_referencia_revision['observ_rev_textos_verif_p'];echo " "; echo $row_validacion['observ_rev_ortog_val_p']; ?></td>
</tr>
<tr>
  <td id="fuente3">Revisi&oacute;n Textos:
    <?php if ($row_referencia_revision['rev_textos_verif_p']=='0'){echo "NO";}else {echo"SI";} ?></td>
  <td id="fuente3"><input type="checkbox" name="rev_textos_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['rev_textos_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Revisi&oacute;n Textos</td>
  <td id="fuente3">- <?php echo $row_referencia_revision['observ_rev_textos_verif_p']; ?></td>
</tr>            
            <tr>
              <td id="fuente3">Pigmento Exterior: <?php echo $row_referencia_revision['Str_lamina1_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="color_ext_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['color_ext_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Color Extrusi&oacute;n Exterior</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_color_ext_verif_p']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Pigmento Interior: <?php echo $row_referencia_revision['Str_lamina2_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="color_int_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['color_int_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Color Extrusi&oacute;n Interior</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_int_verif_p']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Color Material Adhesivos:
              <?php if ($row_referencia_revision['color_material_verif_p']=='0'){echo "NO";}else {echo"SI";} ?></td>
              <td id="fuente3"><input name="color_material_verif_p" type="checkbox" id="color_material_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['color_material_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Color Material Adhesivo</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_color_material_verif_p']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Boca de Entrada: <?php echo $row_referencia_revision['Str_boca_entr_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_boca_entr_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['b_boca_entr_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Boca de Entrada</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_boca_entr_verif_p']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Ubicacion de la Entrada: <?php echo $row_referencia_revision['Str_entrada_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_entrada_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['b_entrada_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Ubicacion de la Entrada</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_entrada_verif_p']; ?></td>
            </tr>
<tr>
              <td id="fuente3">Lamina 1: <?php echo $row_referencia_revision['Str_lamina1_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_lamina1_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['b_lamina1_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Lamina 1</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_lamina1_verif_p']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Lamina 2: <?php echo $row_referencia_revision['Str_lamina2_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_lamina2_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['b_lamina2_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Lamina 2</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_lamina2_verif_p']; ?></td>
            </tr>
            <tr>
              <td id="fuente3">Rodillo: <?php echo $row_referencia_revision['int_rodillo_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_rodillo_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['b_rodillo_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
Rodillo</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_rodillo_verif_p']; ?></td>
            </tr>
              <tr>
              <td id="fuente3">Repeticion: <?php echo $row_referencia_revision['int_repeticion_p']; ?></td>
              <td id="fuente3"><input type="checkbox" name="b_repeticion_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['b_repeticion_verif_p'],1))) {echo "checked=\"checked\"";} ?>>
                Repeticion</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_repeticion_verif_p'];?></td>
            </tr>                       
             <tr>
              <td id="fuente3">Numero de Pistas: <?php echo $row_referencia_revision['int_numerop_p']; ?></td>
            <td id="fuente3"><input type="checkbox" name="b_numerop_p" value="1"<?php if (!(strcmp($row_referencia_revision['b_numerop_p'],1))) {echo "checked=\"checked\"";} ?>>
              Numero de Pistas</td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_numerop_p']; ?></td>
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
              <td id="fuente2"><input name="1color_verif_p" type="checkbox" id="1color_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['1color_verif_p'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_1color_verif_p']; echo " ";echo $row_validacion['observ_color1_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>2</strong> : <?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone2_egp']; ?></td>
              <td id="fuente2"><input name="2color_verif_p" type="checkbox" id="2color_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['2color_verif_p'],1))) {echo "checked=\"checked\"";} ?>/></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_2color_verif_p']; echo " ";echo $row_validacion['observ_color2_val']?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>3</strong> : <?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone3_egp']; ?></td>
              <td id="fuente2"><input name="3color_verif_p" type="checkbox" id="3color_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['3color_verif_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_3color_verif_p'];echo " ";echo $row_validacion['observ_color3_val']; ?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>4</strong> : <?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone4_egp']; ?></td>
              <td id="fuente2"><input name="4color_verif_p" type="checkbox" id="4color_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['4color_verif_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_4color_verif_p'];echo " ";echo $row_validacion['observ_color4_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>5</strong> : <?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone5_egp']; ?></td>
              <td id="fuente2"><input name="5color_verif_p" type="checkbox" id="5color_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['5color_verif_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_5color_verif_p']; echo " ";echo $row_validacion['observacion_color5_val'];?></td>
            </tr>
            <tr>
              <td id="fuente3"><strong>6</strong> : <?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone6_egp']; ?></td>
              <td id="fuente2"><input name="6color_verif_p" type="checkbox" id="6color_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['6color_verif_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_6color_verif_p']; echo " ";echo $row_validacion['observacion_color6_val']; ?></td>
            </tr>
              <tr>
              <td id="fuente3"><strong>7</strong> : <?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone7_egp']; ?></td>
              <td id="fuente2"><input name="7color_verif_p" type="checkbox" id="7color_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['7color_verif_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_7color_verif_p'];echo " ";echo $row_validacion['observacion_color7_val'];?></td>
            </tr>
              <tr>
              <td id="fuente3"><strong>8</strong> : <?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="fuente3">- <?php echo $row_ref_egp['pantone8_egp']; ?></td>
              <td id="fuente2"><input name="8color_verif_p" type="checkbox" id="8color_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['8color_verif_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_8color_verif_p'];echo " ";echo $row_validacion['observacion_color8_val'];?></td>
            </tr>                        
            <tr>
              <td colspan="2" id="fuente3">MARCA DE FOTOCELDA </td>
              <td id="fuente2"><input type="checkbox" name="marca_foto_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['marca_foto_verif_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_marca_foto_verif_p'];echo " "; echo $row_validacion['observ_marca_foto_val']; ?></td>
            </tr>
            <tr>
              <td colspan="2" id="fuente3">REFERENCIA</td>
              <td id="detalle2"><input <?php if (!(strcmp($row_referencia_revision['ref_verif_p'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ref_verif_p" value="1"></td>
              <td id="fuente3">- <?php  echo $row_referencia_revision['observ_ref_verif_p']; ?></td>
            </tr>
<tr>
              <td colspan="2"id="fuente3">Pagina Web:
              <?php if($row_referencia_revision['num_paginaw_verif_p']=='0'){echo "NO";}else{echo"SI";} ?></td>
            <td id="fuente2"><input type="checkbox" name="num_paginaw_verif_p" value="1"<?php if (!(strcmp($row_referencia_revision['num_paginaw_verif_p'],1))) {echo "checked=\"checked\"";} ?>></td>
              <td id="fuente3">- <?php echo $row_referencia_revision['observ_num_paginaw_verif_p']; ?></td>
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
          <?php echo $row_referencia_revision['str_obs_general_p']; ?></td>
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
          <td colspan="2" id="fuente2"><?php $muestra=$row_referencia_revision['userfile_p']; ?>
          <a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"> <?php echo $muestra;?></a></td>
          <td id="fuente2"><?php if($row_referencia_revision['estado_arte_verif_p'] == '0') { echo "Pendiente"; } if($row_referencia_revision['estado_arte_verif_p'] == '1') { echo "Rechazado"; } if($row_referencia_revision['estado_arte_verif_p'] == '2') { echo "Aceptado"; } if($row_referencia_revision['estado_arte_verif_p'] == '3') { echo "Anulado"; } ?></td>
          <td id="fuente2"><?php echo $row_referencia_revision['responsable_verif_p']; ?></td>
        </tr>
        <tr>
          <td colspan="4" id="subppal">ULTIMA ACTUALIZACION </td>
        </tr>
        <tr>
          <td colspan="2" id="fuente2">- <?php echo $row_referencia_revision['fecha_edit_verif_p']; ?> -</td>
          <td colspan="2" id="fuente2">- <?php echo $row_referencia_revision['responsable_edit_verif_p']; ?>- </td>
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

//mysql_free_result($ficha_tecnica);
?>
