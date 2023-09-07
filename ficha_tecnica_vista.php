<?php require_once('Connections/conexion1.php'); ?>
<?php

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_ficha_tecnica = "-1";
if (isset($_GET['n_ft'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM TblFichaTecnica WHERE n_ft = %s", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_referencia = "-1";
if (isset($_GET['n_ft'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM TblFichaTecnica, Tbl_referencia WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_ref_ft = Tbl_referencia.id_ref", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_verificacion = "-1";
if (isset($_GET['n_ft'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM TblFichaTecnica, verificacion WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_ref_ft = verificacion.id_ref_verif AND verificacion.estado_arte_verif = '2'", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_validacion = "-1";
if (isset($_GET['n_ft'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM TblFichaTecnica, validacion WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_ref_ft = validacion.id_ref_val", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_egp = "-1";
if (isset($_GET['n_ft'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egp = sprintf("SELECT * FROM TblFichaTecnica, Tbl_egp WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.n_egp_ft = Tbl_egp.n_egp", $colname_egp);
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);

$colname_revision = "-1";
if (isset($_GET['n_ft'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM TblFichaTecnica, revision WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_rev_ft = revision.id_rev", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);
//REF CLIENTE
$colname_refcliente = "-1";
if (isset($_GET['n_ft'])) {
  $colname_refcliente = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refcliente = sprintf("SELECT TblFichaTecnica.id_ref_ft,Tbl_referencia.cod_ref,Tbl_refcliente.id_refcliente,Tbl_refcliente.int_ref_ac_rc,Tbl_refcliente.str_ref_cl_rc,Tbl_refcliente.str_descripcion_rc 
FROM TblFichaTecnica,Tbl_referencia,Tbl_refcliente WHERE TblFichaTecnica.n_ft = '%s' AND TblFichaTecnica.id_ref_ft=Tbl_referencia.id_ref and Tbl_referencia.cod_ref=Tbl_refcliente.int_ref_ac_rc", $colname_refcliente);
$refcliente = mysql_query($query_refcliente, $conexion1) or die(mysql_error());
$row_refcliente = mysql_fetch_assoc($refcliente);
$totalRows_refcliente = mysql_num_rows($refcliente);

$colname_certificacion_ref = "-1";
if (isset($_GET['n_ft'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['n_ft'] : addslashes($_GET['n_ft']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM TblFichaTecnica,TblCertificacion WHERE TblFichaTecnica.n_ft = %s AND TblFichaTecnica.id_ref_ft = TblCertificacion.idref",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tablainterna">
          <tr id="tr1">
            <td colspan="2" id="subppal">CODIGO: FT-02</td>
            <td colspan="6" id="principal">FICHA TECNICA 
            <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){echo "LAMINAS";}else if($row_referencia['tipo_bolsa_ref']=='PACKING LIST'){echo "PACKING LIST";}else{echo "BOLSAS";}?></td>
            <td colspan="2" id="subppal">VERSION: 1</td>
          </tr>
          <tr>
            <td colspan="2" rowspan="2" id="dato2"><img src="images/logoacyc.jpg" /></td>
            <td colspan="8" id="dato3"><a href="ficha_tecnica_edit.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" style="cursor:hand;" /></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" /><a href="referencias.php"><img src="images/a.gif" border="0" style="cursor:hand;" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" border="0" style="cursor:hand;" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" /></a><a href="ficha_tecnica.php"><img src="images/f.gif" style="cursor:hand;" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" /></a>
            <?php if($row_certificacion['idcc']=='') { ?>
          <a href="certificacion_add.php?id_ref=<?php echo $row_referencia['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_referencia['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
          </a><?php } ?><a href="ficha_tecnica_busqueda.php"><img src="images/opciones.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="ficha_tecnica_busqueda.php"></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR" onClick="window.close() "/></td>
          </tr>
          <tr>
            <td colspan="8" id="numero2"><?php echo $row_referencia['cod_ft']; ?></td>
          </tr>
          <tr id="tr1">
            <td colspan="10" id="principal">CARACTERISTICAS GENERALES</td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">Cliente:</td>
            <td colspan="8" id="fuente1">
            <?php 
			$id_c=$row_ficha_tecnica['cliente_ft'];
			$sqln="SELECT nombre_c FROM cliente WHERE id_c='$id_c'"; 
			$resultn=mysql_query($sqln); 
			$numn=mysql_num_rows($resultn); 
			if($numn >= '1') 
			{ $cliente_c=mysql_result($resultn,0,'nombre_c'); echo $cliente_c; }?></td>
		 </tr>
          <tr>
            <td colspan="2" id="fuente1">Referencia: </td>
            <td id="fuente1"><?php echo $row_referencia['cod_ref']; ?></td>
            <td id="fuente1">Ref. Cliente</td>
            <td id="fuente1"><?php echo $row_refcliente['str_ref_cl_rc'];?></td>
            <td colspan="3" id="fuente1">Fecha: </td>
            <td colspan="2" id="fuente1"><?php echo date("Y-m-d");  ?></td>
          </tr>
          <tr>
            <td id="fuente1">Calibre &mu;m:</td>
            <td id="fuente1"><?php $calibrem = ($row_referencia['calibre_ref']*25.4); echo $calibrem; ?></td>
            <td colspan="2" id="fuente1">Calibre m&aacute;ximo &mu;m:</td>
            <td id="fuente1"><?php $calibremi = ($calibrem+($calibrem*10)/100); echo $calibremi;?></td>
            <td colspan="3" id="fuente1">Calibre m&iacute;nimo &mu;m:</td>
            <td colspan="2" id="fuente1"><?php $calibrem = ($calibrem-($calibrem*10)/100); echo $calibrem;?></td>
          </tr>
          <tr>
            <td id="fuente1">Calibre mils:</td>
            <td id="fuente1"><?php echo $row_referencia['calibre_ref']; ?></td>
            <td colspan="2" id="fuente1">Calibre m&aacute;ximo mils:</td>
            <td id="fuente1"><?php echo ($row_referencia['calibre_ref']+($row_referencia['calibre_ref']*10)/100); ?></td>
            <td colspan="3" id="fuente1">Calibre m&iacute;nimo mils:</td>
            <td colspan="2" id="fuente1"><?php echo ($row_referencia['calibre_ref']-($row_referencia['calibre_ref']*10)/100); ?></td>
          </tr>
          <tr>
            <td id="fuente1">Ancho mm:</td>
            <td id="fuente1"><?php $ancho = ($row_referencia['ancho_ref']*10);echo $ancho; ?></td>
            <td colspan="2" id="fuente1">Ancho M&aacute;ximo mm:</td>
            <td id="fuente1"><?php echo $ancho+10; ?></td>
            <td colspan="3" id="fuente1">Ancho m&iacute;nimo mm:</td>
            <td colspan="2" id="fuente1"><?php echo $ancho-10; ?></td>
          </tr>
          <tr>
            <td id="fuente1"><?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){ $largo = $row_referencia['N_repeticion_l']; echo 'REPETICION';}else{ $largo = ($row_referencia['largo_ref']*10);echo 'LARGO';}?>
            mm:</td>
            <td id="fuente1"><?php echo $largo; ?></td>
            <td colspan="2" id="fuente1">Largo M&aacute;ximo mm:</td>
            <td id="fuente1"><?php echo $largo+10; ?></td>
            <td colspan="3" id="fuente1">Largo m&iacute;nimo mm:</td>
            <td colspan="2" id="fuente1"><?php echo $largo-10; ?></td>
          </tr>
           <?php if($row_referencia['tipo_bolsa_ref']!='LAMINA'){ ?><tr>
            <td id="fuente1">Solapa (± 10mm) <?php if (!(strcmp($row_referencia['b_solapa_caract_ref'],2))){echo "sencilla";} if (!(strcmp($row_referencia['b_solapa_caract_ref'],1))){echo "Doble";}?>:</td>
            <td id="fuente1"><?php echo $row_referencia['solapa_ref']*10; ?></td>
            <td colspan="2" id="fuente1">Fuelle (&plusmn; 10mm):</td>
            <td id="fuente1"><?php echo $row_referencia['N_fuelle']*10; ?></td>
            <td colspan="3" id="fuente1"><strong>Ancho &Uacute;til (&plusmn; 10 mm)</strong></td>
            <td colspan="2" id="fuente1"><?php echo ($ancho-15); ?></td>
            </tr><?php }?>
          <tr>
            <td colspan="2" id="fuente1">Pigmento Cara Externa:</td>
            <td colspan="2" id="fuente1"><?php echo $row_egp['pigm_ext_egp']; ?></td>
            <td id="fuente1">&nbsp;</td>
            <td colspan="3" id="fuente1">Tipo Extrusi&oacute;n:</td>
            <td colspan="2" id="fuente1"><?php echo $row_egp['tipo_ext_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Pigmento Cara Interna:</td>
            <td colspan="2" id="fuente1"><?php echo $row_egp['pigm_int_epg']; ?></td>
            <td id="fuente1">&nbsp;</td>
            <td colspan="3" id="fuente1">Aplicaci&oacute;n:</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['tipo_bolsa_ref']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Peso Millar Bolsa:</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['peso_millar_ref']; ?></td>
            <td id="fuente1">&nbsp;</td>
            <td colspan="3" id="fuente1">Peso Millar Bolsillo:</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['peso_millar_bols']; ?></td>
          </tr>
            <tr id="tr1"> 
              <td colspan="10" id="detalle2">IMPRESIONES</td> 
            </tr>
            <tr> 
              <td colspan="5" id="detalle1">Cantidad de Colores que intervienen</td>
              <td colspan="5" id="detalle1">Segun Arte Aprobado</td> 
            </tr>
          <!-- <tr id="tr1">
            <td colspan="2" id="subppal">UNIDAD</td>
            <td colspan="2" id="subppal">COLOR</td>
            <td colspan="4" id="subppal">UNIDAD</td>
            <td colspan="2" id="subppal">COLOR</td>
          </tr>

          <tr>
            <td colspan="2" id="detalle1">1</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color1_egp']; ?></td>
            <td colspan="4" id="detalle1">5</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color5_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">2</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color2_egp']; ?></td>
            <td colspan="4" id="detalle1">6</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color6_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">3</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color3_egp']; ?></td>
            <td colspan="4" id="detalle1">7</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color7_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">4</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color4_egp']; ?></td>
            <td colspan="4" id="detalle1">8</td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['color8_egp']; ?></td>
          </tr> -->
          <tr>
            <td colspan="10" id="fuente3"><strong>IMPORTANTE: </strong>El m&eacute;todo de revisi&oacute;n y aseguramiento de la calidad de impresi&oacute;n es visual por medio de la tabla
              de pantones, la tolerancia en la medida puede variar 10 mm en la altura esta combinada con el fuelle y la
              solapa si lleva, 10 mm en el ancho y 10% en el calibre, la altura &uacute;til de la bolsa no est&aacute; determinada en la
              altura total, para obtener este dato debe restarle la solapa si la tiene y el &aacute;rea del selle lateral.</td>
            </tr>
          <tr id="tr1">
            <td colspan="10" id="principal"><strong>PROPIEDADES MECANICAS</strong></td>
            </tr>
          <tr id="tr1">
            <td colspan="2" id="subppal">ANALISIS</td>
            <td colspan="2" id="subppal">MAXIMO</td>
            <td colspan="2" id="subppal">MINIMO</td>
            <td colspan="2" id="subppal">UNIDAD</td>
            <td colspan="2" id="subppal">NORMAL</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Propiedades de Tensi&oacute;n TD</td>
            <td colspan="4" id="detalle2">&ge; <?php echo $row_ficha_tecnica['PtensionTd_ft']?></td>
            <td colspan="2" id="detalle2">Newton</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Propiedades de Tensi&oacute;n MD</td>
            <td colspan="4" id="detalle2">&ge; <?php echo $row_ficha_tecnica['PtensionMd_ft']?></td>
            <td colspan="2" id="detalle2">Newton</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Elongaci&oacute;n TD </td>
            <td colspan="4" id="detalle2">&ge; <?php echo $row_ficha_tecnica['elongTd_ft']?></td>
            <td colspan="2" id="detalle2">%</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Elongaci&oacute;n MD </td>
            <td colspan="4" id="detalle2">&ge; <?php echo $row_ficha_tecnica['elongMd_ft']?></td>
            <td colspan="2" id="detalle2">%</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Factor de Rompimiento TD</td>
            <td colspan="4" id="detalle2">&ge; <?php echo $row_ficha_tecnica['FrompTd_ft']?></td>
            <td colspan="2" id="detalle2">Mpa</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Factor de Rompimiento MD</td>
            <td colspan="4" id="detalle2">&ge; <?php echo $row_ficha_tecnica['FrompMd_ft']?></td>
            <td colspan="2" id="detalle2">Mpa</td>
            <td colspan="2" id="detalle2">ASTM D-882-02</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Coeficiente Din&aacute;mico Cara/Cara</td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CDinamCaraMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CDinamCaraMin_ft']?></td>
            <td colspan="2" id="detalle2">N.A.</td>
            <td colspan="2" id="detalle2">ASTM 1894</td>
          </tr>
           <tr>
             <td colspan="2" id="detalle1">Coeficiente Din&aacute;mico Dorso/Dorso</td>
             <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CDinamDorsoMax_ft']?></td>
             <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CDinamDorsoMin_ft']?></td>
             <td colspan="2" id="detalle2">N.A.</td>
             <td colspan="2" id="detalle2">ASTM 1894</td>
           </tr>
          <!--<tr>
            <td colspan="2" id="detalle1">Coeficiente Est&aacute;tico Cara/Cara</td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CEstCaraMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CEstCaraMin_ft']?></td>
            <td colspan="2" id="detalle2">N.A.</td>
            <td colspan="2" id="detalle2">ASTM 1894</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Coeficiente Est&aacute;tico Dorso/Dorso</td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CEstDorsoMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CEstDorsoMin_ft']?></td>
            <td colspan="2" id="detalle2">N.A.</td>
            <td colspan="2" id="detalle2">ASTM 1894</td>
          </tr>-->
          <tr>
            <td colspan="2" id="detalle1">Impacto al Dardo</td>
            <td colspan="4" id="detalle2">&ge; <?php echo $row_ficha_tecnica['ImpacDardo_ft']?></td>
            <td colspan="2" id="detalle2">Gramos</td>
            <td colspan="2" id="detalle2">ASTM D-1709</td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Tensi&oacute;n Superficial</td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['TsuperfMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['TsuperfMin_ft']?></td>
            <td colspan="2" id="detalle2">Dinas</td>
            <td colspan="2" id="detalle2">ASTM D-2578-09</td>
          </tr>
           <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){ ?><!-- <tr>
            <td colspan="2" id="detalle1">Temperatura de Selle</td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['TselleMax_ft'];?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['TselleMin_ft'];?></td>
            <td colspan="2" id="detalle2">&deg;C</td>
            <td colspan="2" id="detalle2">ASTM F 88</td>
          </tr> --><?php }?>            
          <tr>
            <td colspan="10" id="fuente3"><strong>NOTAS</strong>: Estos son valores estad&iacute;sticos obtenidos en nuestro laboratorio y se anexan en este informe solo como orientaci&oacute;n. No comprometen a AC&amp;CIA S.A. como datos absolutos y pueden ser modificados seg&uacute;n el criterio aportado por el laboratorio, los valores m&aacute;ximo y m&iacute;nimo para cada propiedad est&aacute;n especificados en el certificado de calidad.</td>
            </tr>
            <?php if($row_referencia['tipo_bolsa_ref']!='LAMINA'){?>
          <tr id="tr1">
            <td colspan="10" id="principal">CONDICIONES DE FABRICACION EN SELLADO</td>
            </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1">Tipo de Sello:</td>
            <td colspan="2" id="fuente1"><?php echo $row_egp['tipo_sello_egp']; ?></td>
            <td colspan="2" id="fuente1">Tama&ntilde;o del selle mm:            </td>
            <td colspan="2" id="fuente1"><?php echo $row_ficha_tecnica['SelloTamano1_ft']?>               &le;</td>
            <td colspan="2" id="fuente1"><?php echo $row_ficha_tecnica['SelloTamano2_ft']?> &ge;</td>
            </tr> 
          <tr>
            <td colspan="2" rowspan="2" id="fuente1">Tipo de Cierre:</td>
            <td colspan="2" rowspan="2" id="fuente1"><?php echo $row_referencia['tipo_bolsa_ref']; ?></td>
            <td id="fuente1">Cinta de Seguridad:</td>
            <td id="fuente1"><?php if (!(strcmp("N.A", $row_referencia['adhesivo_ref']))) {echo "N.A";} ?>
              <?php if (!(strcmp("CINTA DE SEGURIDAD", $row_referencia['adhesivo_ref']))) {echo "CINTA DE SEGURIDAD";} ?>
            </td>
            <td colspan="2" id="fuente1">Ancho Liner mm:</td>
            <td colspan="2" id="fuente1"><?php echo $row_ficha_tecnica['SegAnchoLiner_ft']?></td>
          </tr>
          <tr>
            <td id="fuente1">Hot melt:</td>
            <td id="fuente1"><?php if (!(strcmp("N.A", $row_referencia['adhesivo_ref']))) {echo "N.A";} ?>
              <?php if (!(strcmp("HOT MELT", $row_referencia['adhesivo_ref']))) {echo "HOT MELT";} ?>
            </td>
            <td colspan="2" id="fuente1">Ancho Liner mm:</td>
            <td colspan="2" id="fuente1"><?php echo $row_ficha_tecnica['HotAnchoLiner_ft']?></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">&nbsp;</td>
            <td colspan="2" id="fuente1">Troqueles:</td>
            <td colspan="2" id="fuente1"><?php if (!(strcmp("", $row_referencia['B_troquel']))) {echo "N.A";} ?>
              <?php if (!(strcmp("1", $row_referencia['B_troquel']))) {echo "SI";} ?>
            <?php if (!(strcmp("0", $row_referencia['B_troquel']))) {echo "NO";} ?></td>
            <td id="fuente1">Perforaciones:</td>
            <td id="fuente1"><?php if (!(strcmp("1", $row_ficha_tecnica['perforacion_ft']))) {echo "SI";} ?>
            <?php if (!(strcmp("0", $row_ficha_tecnica['perforacion_ft']))) {echo "NO";} ?></td>
            <td id="fuente1">Precorte:</td>
            <td id="fuente1"><?php echo $row_referencia['B_precorte']; ?></td>
          </tr>
          <tr id="tr1">
            <td colspan="2" rowspan="2" id="fuente1">Bolsillo Portaguia: <?php echo $row_referencia['bolsillo_guia_ref']; ?></td>
            <td colspan="3" id="fuente1">&nbsp;</td>
            <td id="fuente1">Traslape:</td>
            <td id="fuente1"><?php echo $row_referencia['str_bols_fo_ref']; ?></td>
            <td id="fuente1"><?php echo $row_referencia['B_cantforma']; ?></td>
            <td id="fuente1">Ubicacion:</td>
            <td id="fuente1"><?php echo $row_referencia['str_bols_ub_ref']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Lamina 1</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['bol_lamina_1_ref']; ?></td>
            <td colspan="2" id="fuente1">Lamina 2</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['bol_lamina_2_ref']; ?></td>
          </tr>
          <tr>
            <td colspan="5" id="fuente1">Reacion de adherencia de la CINTA DE SEGURIDAD  &ge; a 300 s</td>
            <td colspan="5" id="fuente1">Reacion de adherencia del HOT MELT &ge; a 20 s</td>
            </tr>           
          <tr id="tr1">
            <td colspan="10" id="principal">NUMERACION</td>
          </tr>
          <tr id="tr1">
            <td colspan="2" id="detalle2">POSICION</td>
            <td colspan="2" id="detalle2">ESTILO</td>
            <td colspan="2" id="detalle2">m&aacute;ximo mm</td>
            <td colspan="2" id="detalle2">m&iacute;nimo mm</td>
            <td colspan="2" id="detalle2">FORMATO CB </td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">SOLAPA TR </td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_solapatr_egp']; ?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['SolapMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['SolapMin_ft']?></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_solapatr_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">CINTA</td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_cinta_egp']; ?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CintaMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['CintaMin_ft']?></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_cinta_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">PRINCIPAL</td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_principal_egp']; ?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['PrincMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['PrincMin_ft']?></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_principal_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">INFERIOR</td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_inferior_egp']; ?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['InferMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['InferMin_ft']?></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_inferior_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">LINER</td>
            <td colspan="2" id="detalle1">- <?php echo $row_egp['tipo_liner_egp']; ?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['LinerMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['LinerMin_ft']?></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['cb_liner_egp']; ?></td>
          </tr>
    <tr>
            <td colspan="2" id="detalle1">BLSILLO</td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_bols_egp']; ?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['BolsMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['BolsMin_ft']?></td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['cb_bols_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">Otros:<?php echo $row_egp['tipo_nom_egp']; ?></td>
            <td colspan="2" id="detalle1">-<?php echo $row_egp['tipo_otro_egp']; ?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['OtroMax_ft']?></td>
            <td colspan="2" id="detalle2"><?php echo $row_ficha_tecnica['OtroMin_ft']?></td>
            <td colspan="2" id="detalle1"><?php echo $row_egp['cb_otro_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="10" id="fuente3"><strong>NOTAS</strong>: En la numeraci&oacute;n las dimensiones var&iacute;an de acuerdo al tama&ntilde;o de la bolsa, y pueden ser modificadas seg&uacute;n la necesidad del proceso, estilo esta dado por sus caracter&iacute;sticas de tama&ntilde;o y forma as&iacute;: Normal: contador alfanum&eacute;rico, Doble: contador alfanum&eacute;rico impreso a doble repetici&oacute;n en una misma bolsa y CCTV n&uacute;mero de gran car&aacute;cter.(el estilo normal y el doble pueden llevar o no c&oacute;digo de barras.)</td>
            </tr>
          <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){?><tr>
            <td colspan="10" id="principal">CONDICIONES DE EMPAQUE</td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">Peso Max Rollo (kg):</td>
            <td colspan="2" id="fuente1"><?php echo $row_referencia['N_peso_max_l'] ?></td>
            <td colspan="2" id="fuente1">Diametro (mm):</td>
            <td id="fuente1"><?php echo $row_referencia['N_diametro_max_l'] ?></td>
            <td colspan="2" id="fuente1">Bobinado No:</td>
            <td id="fuente1"><?php echo $row_referencia['N_embobinado_l'] ?></td>
          </tr>
          <?php }?>
       <?php if($row_ficha_tecnica['aditivo_ft']!=''){ ?> 
       <tr>
         <td colspan="10" id="subppal2">TIPO DE ADITIVO</td>
         </tr>
       <tr>
        <td colspan="5" id="fuente1">
		    <?php 
			$aditivo=$row_ficha_tecnica['aditivo_ft'];
			$sqlad="SELECT id_insumo, codigo_insumo, descripcion_insumo FROM insumo WHERE id_insumo='$aditivo'"; 
			$resultad=mysql_query($sqlad); 
			$numad=mysql_num_rows($resultad); 
			if($numad >= '1') 
			{ $desc_insumo=mysql_result($resultad,0,'descripcion_insumo'); echo $desc_insumo;
			$codigo=mysql_result($resultad,0,'codigo_insumo'); }?>
			</td>
        <td colspan="5" id="fuente1"><?php echo " <strong>REF:</strong> ".$codigo."  <strong>CANT:</strong> ".$row_ficha_tecnica['cantAditivo_ft'];?></td>
        </tr> 
         <?php }?> 
          <?php }?>                    
          <tr id="tr1">
            <td colspan="10" id="principal">CONDICIONES DE USO Y ALMACENAMIENTO <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){echo "Y TRANSPORTE"; }?></td>
          </tr>
          <tr>
            <td colspan="10" id="fuente3">1. Se deben de guardar en cajas o en paquetes protegiendo del polvo y humedad.
              <br>2. No exponer a los rayos directos del sol ni al agua.
              <br>3. Evitar el contacto con solventes o vapores que afecten o contaminen el adhesivo (bolsas seguridad, courrier).
              <br>4. Siempre dar rotaci&oacute;n a los lotes antiguos para evitar caducidad.
              <br>5. Evitar en el transporte el roce entre paquetes de bolsas.
              <br>6. Conservar el control de numeraci&oacute;n y/o control de empaque por paquete para la trazabilidad.</td>
            </tr>
        <tr id="tr1">
            <td colspan="10" id="principal">OBSERVACIONES</td>
          </tr>
          <tr>
            <td colspan="10" id="detalle1"><p>Vida &Uacute;til: 12 a 18 meses m&aacute;ximo despu&eacute;s de fecha de producci&oacute;n.</p></td>
            </tr>            
          <!--<tr id="tr1">
            <td colspan="2" id="fuente1">ESTADO FT</td>
            <td colspan="2" id="fuente1">FECHA MODIF. </td>
            <td colspan="6" id="fuente1">MODIFICADO POR </td>
          </tr>
          <tr>
            <td colspan="2" id="fuente3"><?php echo $row_ficha_tecnica['estado_ft']?></td>
            <td colspan="2" id="fuente3"><?php echo $row_ficha_tecnica['fechaModif_ft']?></td>
            <td colspan="6" id="fuente3"><?php echo $row_ficha_tecnica['addCambio_ft']?></td>
            </tr>-->
          <tr id="tr1">
            <td colspan="10" id="dato1"><p>APROBO: <strong><?php echo $row_ficha_tecnica['aprobo_ft'];?></strong></p>
            <p>&nbsp;</p></td>
            </tr>
        </table>
<p>&nbsp;</p>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ficha_tecnica);

mysql_free_result($referencia);

mysql_free_result($verificacion);

mysql_free_result($validacion);

mysql_free_result($egp);

mysql_free_result($revision);
?>