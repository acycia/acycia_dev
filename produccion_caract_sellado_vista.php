<?php require_once('Connections/conexion1.php'); ?><?php

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_referencia_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = '%s' AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_referencia_egp);
$referencia_egp = mysql_query($query_referencia_egp, $conexion1) or die(mysql_error());
$row_referencia_egp = mysql_fetch_assoc($referencia_egp);
$totalRows_referencia_egp = mysql_num_rows($referencia_egp);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body>
<div align="center">
<table id="tablainterna">
  <tr>    
     <td colspan="6" id="principal">CARACTERISTICAS EN SELLADO</td>
  </tr>
  <tr>
    <td rowspan="7" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td id="dato3">Menu de Dise&ntilde;o y Desarrollo</td>
    <td colspan="4" id="dato3"><a href="produccion_caract_sellado_edit.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" />
    <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR"title="SALIR" onClick="window.close() "/></a></td>
    </tr>
  <tr>
    <td id="dato3">Menu de Produccion</td>
    <td colspan="4" id="dato3"><?php $ref=$row_referencia_egp['id_ref'];
	  $sqlpm="SELECT * FROM Tbl_produccion_mezclas WHERE id_ref_pm='$ref' and id_proceso='1'";
	  $resultpm= mysql_query($sqlpm);
	  $row_pm = mysql_fetch_assoc($resultpm);
	  $numpm= mysql_num_rows($resultpm);
	  if($numpm >='1')
	  { ?>
      <a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="EXTRUSION" title="EXTRUSION" border="0" /></a>
      <?php } else{ ?>
      <a href="produccion_mezclas_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&cod_ref=<?php echo $row_referencia_egp['cod_ref']; ?>"><img src="images/e_rojo.gif" style="cursor:hand;" alt="ADD FORMULA EXTRUSION" title="ADD FORMULA EXTRUSION" border="0" /></a>
      <?php } ?>
      <?php $ref=$row_referencia_egp['id_ref'];
	  $sqlci="SELECT DISTINCT id_ref_pmi,id_proceso FROM Tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$ref' and id_proceso='2'";
	  $resultci= mysql_query($sqlci);
	  $row_ci = mysql_fetch_assoc($resultci);
	  $numci= mysql_num_rows($resultci);
	  if($numci >='1')
	  { ?>
      <a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>"><img src="images/i.gif" style="cursor:hand;" alt="IMPRESION" title="IMPRESION" border="0" /></a>
      <?php } else{ ?>
      <a href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&cod_ref=<?php echo $row_referencia_egp['cod_ref']; ?>"><img src="images/i_rojo.gif" style="cursor:hand;" alt="ADD FORMULA IMPRESION" title="ADD FORMULA IMPRESION" border="0" /></a>
      <?php } ?>
      <a href="produccion_caract_sellado_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>"><img src="images/s.gif" style="cursor:hand;" alt="SELLADO" title="SELLADO" border="0" /></a> 
	 </td>
    </tr>
  <tr>
    <td id="subppal2">FECHA DE INGRESO </td>
    <td colspan="4" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['fecha_registro1_ref']; ?></td>
    <td colspan="4" nowrap id="fuente2"><?php echo $row_referencia_egp['registro1_ref']; ?></td>
    </tr>
  <tr>
    <td colspan="5" id="subppal2">REFERENCIA - VERSION</td>
    </tr>
  <tr>
    <td colspan="5" nowrap id="fuente2"><strong><?php echo $row_referencia_egp['cod_ref']; ?> - 
      <?php echo $row_referencia_egp['version_ref']; ?></strong></td>
    </tr>
  <tr>
    <td colspan="5" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="6" id="principal">EPECIFICACIONES</td>
    </tr>
  <tr>
    <td id="subppal2">ANCHO</td>
    <td id="subppal2">LARGO</td>
    <td colspan="2" id="subppal2">SOLAPA</td>
    <td colspan="2" id="subppal2">BOLSILLO PORTAGUIA</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['ancho_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['largo_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['solapa_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['bolsillo_guia_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">CALIBRE</td>
    <td id="subppal2">PESO MILLAR </td>
    <td colspan="2" id="subppal2">TIPO DE BOLSA </td>
    <td colspan="2" id="subppal2">ADHESIVO</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['calibre_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['peso_millar_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['tipo_bolsa_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['adhesivo_ref']; ?></td>
  </tr>
  <tr>
    <td rowspan="2" id="subppal2">PRESENTACION</td>
    <td rowspan="2" id="subppal2">TRATAMIENTO CORONA</td>
    <td colspan="4" id="subppal2">Bolsillo Portaguia</td>
    </tr>
  <tr>
    <td id="subppal2"> (Ubicacion)</td>
    <td id="subppal2">(Forma)</td>
    <td id="subppal">Lamina 1</td>
    <td id="subppal">Lamina 2</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['Str_presentacion']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['Str_tratamiento']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['str_bols_ub_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['str_bols_fo_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_1_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_2_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">TIPO DE SELLO </td>
    <td id="subppal2">UNIDADES X CAJA</td>
    <td id="subppal2">UNIDADES X PAQUETE</td>
    <td id="subppal2">PRECORTE(Bolsillo Portaguia)</td>
    <td colspan="2" id="subppal2">&nbsp;</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_sello_egp']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_1_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_2_ref']; ?></td>
    <td id="fuente2"><?php if($row_referencia_egp['B_troque']=='1') {echo "SI";}else{echo "NO";}; ?></td>
    <td colspan="2" id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">POSICION</td>
    <td colspan="2" id="subppal">TIPO DE NUMERACION </td>
    <td colspan="2" id="subppal">BARRAS &amp; FORMATO</td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">SOLAPA TALONARIO RECIBO </td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['tipo_solapatr_egp']; ?></td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['cb_solapatr_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">CINTA</td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['tipo_cinta_egp']; ?></td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['cb_cinta_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">SUPERIOR</td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['tipo_superior_egp']; ?></td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['cb_superior_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">PRINCIPAL</td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['tipo_principal_egp']; ?></td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">INFERIOR</td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['tipo_inferior_egp']; ?></td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['cb_inferior_egp']; ?></td>
  </tr>
 <tr>
    <td colspan="2" id="subppal">LINER</td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_liner_egp']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['cb_liner_egp']; ?></td>              
  </tr>
  <tr>
    <td colspan="2" id="subppal">BOLSILLO</td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_bols_egp']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['cb_bols_egp']; ?></td>              
  </tr>
  <tr>
    <td colspan="2" id="subppal">Otro: <?php echo $row_referencia_egp['tipo_nom_egp']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_otro_egp']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['cb_otro_egp']; ?></td>              
  </tr> 
  <tr>
    <td colspan="2" id="subppal">FECHA ULTIMA MODIFICACION </td>
    <td colspan="4" id="subppal">RESPONSABLE ULTIMA MODIFICACION </td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['fecha_registro2_ref']; ?></td>
    <td colspan="4" id="fuente2"><?php echo $row_referencia_egp['registro2_ref']; ?></td>
    </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($referencia_egp);

mysql_free_result($usuario);
?>
