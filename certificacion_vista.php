<?php require_once('Connections/conexion1.php'); ?>
<?php
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_referencia = "-1";
if (isset($_GET['idcc'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['idcc'] : addslashes($_GET['idcc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM TblCertificacion,Tbl_referencia, Tbl_egp WHERE TblCertificacion.idcc = '%s' AND TblCertificacion.idref = Tbl_referencia.id_ref  AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_ficha_tecnica = "-1";
if (isset($_GET['idcc'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['idcc'] : addslashes($_GET['idcc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM TblCertificacion,TblFichaTecnica WHERE TblCertificacion.idcc = '%s' AND TblCertificacion.idref = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion = "-1";
if (isset($_GET['idcc'])) {
  $colname_certificacion = (get_magic_quotes_gpc()) ? $_GET['idcc'] : addslashes($_GET['idcc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion = sprintf("SELECT * FROM TblCertificacion WHERE TblCertificacion.idcc='%s'",$colname_certificacion);
$certificacion = mysql_query($query_certificacion, $conexion1) or die(mysql_error());
$row_certificacion = mysql_fetch_assoc($certificacion);
$totalRows_certificacion = mysql_num_rows($certificacion);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="center">
<table id="tablainterna">
<tr>
  <td align="center" id="linea1">
    <table id="tabla2">
      <tr id="tr1">
          <td colspan="2" id="subppal">CODIGO: R4-F15</td>
          <td colspan="3" id="principal">CERTIFICADO DE CALIDAD</td>
          <td colspan="2" id="subppal">VERSION: 2 </td>
          </tr>
      <tr>
        <td rowspan="2" id="dato2"><img src="images/logoacyc.jpg" /></td>
        <td colspan="6" id="dato3"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="certificacion_edit.php?idcc=<?php echo $row_certificacion['idcc']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" style="cursor:hand;" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" title="RESTAURAR" onClick="window.history.go()"><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="certificacion_listado.php?id_ref=<?php echo $row_certificacion['idref']; ?>"><img src="images/opciones.gif" style="cursor:hand;" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0"/></a>
        <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
      <tr>
        <td colspan="6" id="numero2">CERT N. <?php echo $row_certificacion['codref']; ?></td>
        </tr>
      <tr>
        <td id="fuente1">CLIENTE</td>
        <td colspan="6" id="fuente1">
          <?php 
			$id_c=$row_certificacion['idc'];
			$sqln="SELECT nombre_c FROM cliente WHERE id_c='$id_c'"; 
			$resultn=mysql_query($sqln); 
			$numn=mysql_num_rows($resultn); 
			if($numn >= '1') 
			{ $cliente_c=mysql_result($resultn,0,'nombre_c'); echo formaiso($cliente_c); }?></td>
        </tr>
      <tr>
        <td id="fuente1">ORDEN DE PRODUCCION</td>
        <td id="fuente1"><?php echo $row_certificacion['op']; ?></td>
        <td colspan="2" id="fuente1">FICHA TECNICA / REFERENCIA</td>
        <td id="fuente1"><strong><?php echo $row_certificacion['codref'];?>-<?php echo $row_certificacion['versref']; ?></strong></td>
        <td id="fuente1">REF-CLIENTE</td>
        <td id="fuente1"><?php echo $row_certificacion['refCliente']; ?></td>
        </tr>
      <tr>
        <td id="fuente1">ORDEN DE COMPRA</td>
        <td id="fuente1"><?php echo $row_certificacion['oc']; ?></td>
        <td colspan="2" id="fuente1">CALIBRE &mu;m</td>
        <td id="fuente1"><?php $calibrem = ($row_referencia['calibre_ref']*25.4); echo $calibrem; ?></td>
        <td id="fuente1">FACTURA N&deg;</td>
        <td id="fuente1"><?php echo $row_certificacion['factura']; ?></td>
        </tr>
      <tr id="tr1">
        <td colspan="7" id="principal">DIMENSIONES DE
          <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){echo "LAMINAS";}else if($row_referencia['tipo_bolsa_ref']=='PACKING LIST'){echo "PACKING LIST";}else{echo "BOLSAS";}?></td>
        </tr>
      <tr>
        <td rowspan="2" id="subppal">ESPECIFICACIONES</td>
        <td rowspan="2" id="subppal">VALOR</td>
        <td colspan="2" id="subppal">VARIACION</td>
        <td rowspan="2" id="subppal">VALORES OBTENIDOS</td>
        <td rowspan="2" id="subppal">METODO DE ENSAYO</td>
        <td rowspan="2" id="subppal">UNIDADES</td>
        </tr>
      <tr>
        <td id="fuente1">MAX</td>
        <td id="fuente1">MIN</td>
        </tr>
      <tr>
        <td id="fuente1">ANCHO</td>
        <td id="fuente1"><?php $ancho = ($row_referencia['ancho_ref']*10);echo $ancho; ?></td>
        <td id="fuente1"><?php echo $ancho+10; ?></td>
        <td id="fuente1"><?php echo $ancho-10; ?></td>
        <td id="fuente2"><?php echo $row_certificacion['anchvaloptenido']; ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">mm</td>
        </tr>
      <tr>
        <td id="fuente1"><?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){$largo = $row_referencia['N_repeticion_l'];  echo 'REPETICION';}else{$largo = ($row_referencia['largo_ref']*10);echo 'LARGO';}?></td>
        <td id="fuente1"><?php echo $largo; ?></td>
        <td id="fuente1"><?php echo $largo+10; ?></td>
        <td id="fuente1"><?php echo $largo-10; ?></td>
        <td id="fuente2"><?php echo $row_certificacion['largvaloptenido']; ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">mm</td>
        </tr>
      <?php if($row_referencia['tipo_bolsa_ref']!='LAMINA'){ ?>
      <tr>
        <td id="fuente1">SOLAPA
          <?php if (!(strcmp($row_referencia['b_solapa_caract_ref'],2))){echo "sencilla";} if (!(strcmp($row_referencia['b_solapa_caract_ref'],1))){echo "Doble";}?></td>
        <td id="fuente1"><?php $solapa=$row_referencia['solapa_ref']*10;echo $solapa; ?></td>
        <td id="fuente1"><?php echo $solapa+10; ?></td>
        <td id="fuente1"><?php echo $solapa-10; ?></td>
        <td id="fuente2"><?php echo $row_certificacion['solvaloptenido']; ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">mm</td>
        </tr>
      <tr>
        <td id="fuente1">FUELLE</td>
        <td id="fuente1"><?php echo $row_referencia['N_fuelle']*10; ?></td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente2"><?php echo $row_certificacion['fuellvaloptenido']; ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">mm</td>
        </tr>
      <?php }?>
      <tr>
        <td id="fuente1">CALIBRE</td>
        <td id="fuente1"><?php echo $calibrem; ?></td>
        <td id="fuente1"><?php $calibremi = ($calibrem+($calibrem*10)/100); echo $calibremi;?></td>
        <td id="fuente1"><?php $calibrem = ($calibrem-($calibrem*10)/100); echo $calibrem;?></td>
        <td id="fuente2"><?php echo $row_certificacion['calvaloptenido']; ?></td>
        <td id="fuente1">ASTM D-6988-08</td>
        <td id="fuente1">&mu;m: (micras)</td>
        </tr>
      <tr id="tr1">
        <td colspan="7" id="principal"><strong>PROPIEDADES MECANICAS</strong></td>
        </tr>
      <tr id="tr1">
        <td id="subppal">ANALISIS</td>
        <td colspan="2" id="subppal">MAXIMO / MINIMO</td>
        <td colspan="2" id="subppal">VALORES OBTENIDOS</td>
        <td id="subppal">NORMAL DE ENSAYO</td>
        <td id="subppal">UNIDAD</td>
        </tr>
      <tr>
        <td id="fuente1">Tensi&oacute;n TD</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['PtensionTd_ft']; ?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['tenstdvaloptenido']; ?></td>
        <td id="fuente1">ASTM D-882-02</td>
        <td id="detalle2">Newton</td>
        </tr>
      <tr>
        <td id="fuente1">Tensi&oacute;n MD</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['PtensionMd_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['tensmdvaloptenido']; ?></td>
        <td id="fuente1">ASTM D-882-02</td>
        <td id="detalle2">Newton</td>
        </tr>
      <tr>
        <td id="fuente1">Elongaci&oacute;n TD </td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['elongTd_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['elongtdvaloptenido']; ?></td>
        <td id="fuente1">ASTM D-882-02</td>
        <td id="detalle2">%</td>
        </tr>
      <tr>
        <td id="fuente1">Elongaci&oacute;n MD </td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['elongMd_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['elongmdvaloptenido']; ?></td>
        <td id="fuente1">ASTM D-882-02</td>
        <td id="detalle2">%</td>
        </tr>
      <tr>
        <td id="fuente1">Factor de Rompimiento TD</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['FrompTd_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['factortdvaloptenido']; ?></td>
        <td id="fuente1">ASTM D-882-02</td>
        <td id="detalle2">Mpa</td>
        </tr>
      <tr>
        <td id="fuente1">Factor de Rompimiento MD</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['FrompMd_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['factormdvaloptenido']; ?></td>
        <td id="fuente1">ASTM D-882-02</td>
        <td id="detalle2">Mpa</td>
        </tr>
      <tr>
        <td id="fuente1">Coeficiente Din&aacute;mico Cara/Cara</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CDinamCaraMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CDinamCaraMin_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['coefdimccval']; ?></td>
        <td id="fuente1">ASTM 1894</td>
        <td id="detalle2">N.A.</td>
        </tr>
      <tr>
        <td id="fuente1">Coeficiente Din&aacute;mico Dorso/Dorso</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CDinamDorsoMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CDinamDorsoMin_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['coefdimddval']; ?></td>
        <td id="fuente1">ASTM 1894</td>
        <td id="detalle2">N.A.</td>
        </tr>
      <!--<tr>
        <td id="fuente1">Coeficiente Est&aacute;tico Cara/Cara</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CEstCaraMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CEstCaraMin_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['coefestccval']; ?></td>
        <td id="fuente1">ASTM 1894</td>
        <td id="detalle2">N.A.</td>
        </tr>
      <tr>
        <td id="fuente1">Coeficiente Est&aacute;tico Dorso/Dorso</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CEstDorsoMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CEstDorsoMin_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['coefestddval']; ?></td>
        <td id="fuente1">ASTM 1894</td>
        <td id="detalle2">N.A.</td>
        </tr>-->
      <tr>
        <td id="fuente1">Impacto al Dardo</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['ImpacDardo_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['impatdval']; ?></td>
        <td id="fuente1">ASTM D-1709</td>
        <td id="detalle2">Gramos</td>
        </tr>
      <tr>
        <td id="fuente1">Tensi&oacute;n Superficial</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['TsuperfMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['TsuperfMin_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['tensionsval']; ?></td>
        <td id="fuente1">ASTM D-2578-09</td>
        <td id="detalle2">Dinas</td>
        </tr>
      <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){ ?>
      <tr>
        <td id="fuente1">Temperatura de Selle</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['TselleMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['TselleMin_ft'];?></td>
        <td colspan="2" id="detalle2"><?php echo $row_certificacion['tempeselleval']; ?></td>
        <td id="fuente1">ASTM F 88</td>
        <td id="detalle2">&deg;C</td>
        </tr>
      <?php }?>
      <tr id="tr1">
        <td colspan="8" id="principal">ANALISIS DE CINTA DE SEGURIDAD</td>
        </tr>
      <!-- <tr>
        <td id="subppal">REFERENCIA</td>
        <td id="fuente1"><?php echo $row_certificacion['referencia']; ?></td>
        <td id="subppal">LOTE N&deg;</td>
        <td colspan="2" id="fuente1"><?php echo $row_certificacion['lotenum']; ?></td>
        <td id="subppal">ENSAYO N&deg;</td>
        <td id="fuente1"><?php echo $row_certificacion['ensayonum']; ?></td>
        </tr> -->
      <tr id="tr1">
        <td colspan="2" id="subppal">PARAMETROS</td>
        <td id="subppal">CUMPLE</td>
        <td colspan="2" id="subppal">NO CUMPLE</td>
        <td id="subppal">METODO DE ENSAYO</td>
        <td id="subppal">UNIDADES</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">APARIENCIA</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['aparcump'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"><?php if (!(strcmp($row_certificacion['aparcump'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">EVIDENCIA EN FRIO</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['evidfriocump'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"><?php if (!(strcmp($row_certificacion['evidfriocump'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">EVIDENCIA EN CALOR</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['evidcalorcump'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"><?php if (!(strcmp($row_certificacion['evidcalorcump'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">EVIDENCIA SOLVENTES</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['evidsolvcumple'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"><?php if (!(strcmp($row_certificacion['evidsolvcumple'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">EVID. TEMP. AMBIENTE</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['evidtambcumple'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"> <?php if (!(strcmp($row_certificacion['evidtambcumple'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr id="tr1">
        <td colspan="8" id="principal">PARAMETROS DE EXTRUSION E IMPRESION</td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="subppal">PARAMETROS</td>
        <td id="subppal">CUMPLE</td>
        <td colspan="2" id="subppal">NO CUMPLE</td>
        <td id="subppal">METODO DE ENSAYO</td>
        <td id="subppal">UNIDADES</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">PIGMENTACION</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['pigmcump'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"><?php if (!(strcmp($row_certificacion['pigmcump'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">COLORES-TONALIDAD</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['colortoncump'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"><?php if (!(strcmp($row_certificacion['colortoncump'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">TEXTOS</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['textcump'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"><?php if (!(strcmp($row_certificacion['textcump'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">CODIGO DE BARRAS</td>
        <td id="fuente1"><?php if (!(strcmp($row_certificacion['codigbarcump'],1))) {echo "X";} ?></td>
        <td colspan="2" id="fuente1"><?php if (!(strcmp($row_certificacion['codigbarcump'],0))) {echo "";} ?></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">N.A.</td>
        </tr>
      <tr id="tr1">
        <td colspan="7" id="principal">OBSERVACIONES</td>
        </tr>
      <tr>
        <td colspan="7" id="fuente1"><?php echo $row_certificacion['observ']; ?></td>
        </tr>
      <tr>
        <td colspan="7" id="fuente1"><p>Vida &Uacute;til: 12 a 18 meses m&aacute;ximo despu&eacute;s de fecha de producci&oacute;n.</p></td>
        </tr>
      <tr>
        <td colspan="7" id="dato1"><table border="0" id="tablainterna" >
          <tr>
            <td id="fuente2">ESTADO CERT</td>
            <td id="fuente2">FECHA MODIF. </td>
            <td id="fuente2">MODIFICADO POR </td>
            <td id="fuente2">APROBO</td>
            </tr>
          <tr>
            <td id="fuente1" ><?php if (!(strcmp("0", $row_certificacion['estado']))) {echo "Activa";} ?>
              <?php if (!(strcmp("1", $row_certificacion['estado']))) {echo "Inactiva";} ?></td>
            <td id="fuente1"><?php echo $row_certificacion['fechamodifico'];?></td>
            <td id="fuente1"><?php echo $row_usuario['nombre_usuario']; ?></td>
            <td id="fuente1"><?php echo $row_certificacion['jefeplanta'] ?></td>
            </tr>
          </table></td>
        </tr>
      <tr id="tr1">
        <td colspan="7" id="dato2">&nbsp;</td>
        </tr>
      </table>
  </td>
</tr>
</table>
</div>
</div>
</td>
</tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencia);

mysql_free_result($ficha_tecnica);

mysql_free_result($certificacion);

?>