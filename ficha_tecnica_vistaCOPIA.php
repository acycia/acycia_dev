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
          <tr>
           <td id="subppal">CODIGO: R2-T01</td> 
            <td colspan="2" id="principal">FICHA TECNICA</td>
            <td id="subppal">VERSION: 2 </td>
          </tr>
          <tr>
            <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg" /></td>
            <td colspan="2" id="fondo"><strong><?php echo $row_ficha_tecnica['cod_ft']; ?></strong></td>
            <td id="fondo"><a href="ficha_tecnica_edit.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" style="cursor:hand;" /></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" /><a href="referencias.php"><img src="images/a.gif" border="0" style="cursor:hand;" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" border="0" style="cursor:hand;" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" /></a><a href="ficha_tecnica.php"><img src="images/f.gif" style="cursor:hand;" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" /></a><a href="disenoydesarrollo.php"><img src="images/identico.gif" style="cursor:hand;" alt="DISEÑO Y DESARROLLO" title="DISEÑO Y DESARROLLO" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR" onClick="window.close() "/></td>
          </tr>
          <tr>
            <td id="subppal2">REFERENCIA </td>
            <td id="fuente2"><!--EGP N&deg; <?php //echo $row_ficha_tecnica['n_egp_ft']; ?>--></td>
            <td id="fuente2">REVISION N&deg; <?php echo $row_ficha_tecnica['id_rev_ft']; ?></td>
          </tr>
          <tr>
            <td id="fuente2"><strong><?php echo $row_referencia['cod_ref']; ?> - <?php echo $row_referencia['version_ref']; ?></strong></td>
            <td id="fuente2">VERIFICACION N&deg; <?php echo $row_verificacion['id_verif']; ?></td>
            <td id="fuente2">VALIDACION N&deg; <?php echo $row_validacion['id_val']; ?></td>
          </tr>
          <tr>
            <td id="subppal2">FECHA DE ELABORACION</td>
            <td colspan="2" id="subppal2">ELABORADA POR </td>
    </tr>
          <tr>
            <td id="fuente2"><?php echo $row_ficha_tecnica['fecha_ft']; ?></td>
            <td colspan="2" id="fuente2"><?php echo $row_ficha_tecnica['adicionado_ft']; ?></td>
    </tr>
          <tr>
            <td colspan="3" id="dato1">&nbsp;</td>
    </tr>
          <tr>
            <td colspan="4" id="subppal2">CARACTERISTICAS GENERALES DE LA BOLSA TERMINADA</td>
    </tr>
          <tr id="tr1">
            <td id="subppal2">ANCHO</td>
            <td id="subppal2">LARGO</td>
            <td id="subppal2">SOLAPA</td>
            <td id="subppal2">CALIBRE</td>
          </tr>
          <tr>
            <td id="fuente2"><?php echo $row_referencia['ancho_ref']; ?></td>
            <td id="fuente2"><?php echo $row_referencia['largo_ref']; ?></td>
            <td id="fuente2"><?php echo $row_referencia['solapa_ref']; ?></td>
            <td id="fuente2"><?php echo $row_referencia['calibre_ref']; ?></td>
          </tr>
          <tr id="tr1">
            <td id="subppal2">BOLSILLO PORTAGUIA </td>
            <td id="subppal2">PESO MILLAR </td>
            <td id="subppal2">PESO mt. LINEAL (g) </td>
            <td id="subppal2">CALIBRE (micras) </td>
          </tr>
          <tr>
            <td id="fuente2"><?php echo $row_referencia['bolsillo_guia_ref']; ?></td>
            <td id="fuente2"><?php echo $row_referencia['peso_millar_ref']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['peso_g_ft']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['calibre_micras_ft']; ?></td>
          </tr>
          <tr>
            <td colspan="4" id="fuente3"><strong>IMPORTANTE:</strong> La tolerancia en las medidas pueden variar en 1 cm en altura, 5 mm en ancho y un 10% en calibre. La altura util de la bolsa no esta determinada en la altura total, para averiguar este dato debe de restarse la solapa. </td>
    </tr>
          <tr>
            <td colspan="4" id="subppal2">EXTRUSION</td>
    </tr>
          <tr>
            <td colspan="4" id="fuente2"><strong>RESISTENCIA (VALORES CRITICOS)</strong></td>
    </tr>
          <tr>
            <td id="subppal2">ANALISIS</td>
            <td id="subppal2">METODO</td>
            <td id="subppal2">VALOR MINIMO </td>
            <td id="subppal2">TOLERANCIA</td>
          </tr>
          <tr>
            <td id="fuente3">Resistencia al Razgado MD </td>
            <td id="fuente3">ASTDM-D1922</td>
            <td id="fuente3">&gt; 3 gr / mic </td>
            <td id="fuente3"><?php echo $row_ficha_tecnica['tolerancia_md_ft']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">Resistencia al Razgado TD </td>
            <td id="fuente3">ASTDM-D1922</td>
            <td id="fuente3">&gt; 6 gr / mic </td>
            <td id="fuente3"><?php echo $row_ficha_tecnica['tolerancia_td_ft']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">Deslizamiento Estatico </td>
            <td id="fuente3">Min 18 </td>
            <td id="fuente3">18 grados </td>
            <td id="fuente3"><?php echo $row_ficha_tecnica['tolerancia_te_ft']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">Fuerza de Sello </td>
            <td id="fuente3">G / Pulg </td>
            <td id="fuente3">&gt;= 30 gr / mic </td>
            <td id="fuente3"><?php echo $row_ficha_tecnica['tolerancia_fs_ft']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">Ancho</td>
            <td id="fuente3">Flexometro</td>
            <td id="fuente3">&gt;= 45 </td>
            <td id="fuente3"><?php echo $row_ficha_tecnica['tolerancia_ancho_ft']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">Calibre</td>
            <td id="fuente3">Comparador de Caratula </td>
            <td id="fuente3">&gt;= 1 </td>
            <td id="fuente3"><?php echo $row_ficha_tecnica['tolerancia_calibre_ft']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">Tratamiento Corona </td>
            <td id="fuente3">Lapiz Tratador </td>
            <td id="fuente3">38 Dinas </td>
            <td id="fuente3"><?php echo $row_ficha_tecnica['tolerancia_tc_ft']; ?></td>
          </tr>
          <tr>
            <td colspan="4" id="fuente3"><strong>NOTAS</strong>: Los colores de extrusi&oacute;n pueden variar ligeramente de los anotados. se indican solamente como valor de referencia. </td>
    </tr>
          <tr>
            <td id="subppal2">TIPO EXTRUSION </td>
            <td id="subppal2">PIGMENTO INTERIOR </td>
            <td id="subppal2">PIGMENTO EXTERIOR </td>
            <td id="subppal2">PRESENTACION</td>
          </tr>
          <tr>
            <td id="fuente2"><?php echo $row_egp['tipo_ext_egp']; ?></td>
            <td id="fuente2"><?php echo $row_egp['pigm_int_epg']; ?></td>
            <td id="fuente2"><?php echo $row_egp['pigm_ext_egp']; ?></td>
            <td id="fuente2"><?php echo $row_revision['presentacion_rev']; ?></td>
          </tr>
          <tr>
            <td colspan="4" id="subppal2">IMPRESION</td>
    </tr>
          <tr>
            <td id="subppal2">ARTE</td>
            <td id="subppal2">MODIFICACION ARTE </td>
            <td id="subppal2">METODO  INSPECCION </td>
            <td id="subppal2">TIPO DE BOLSA </td>
          </tr>
          <tr>
            <td id="fuente2">[ <?php $archivo=$row_verificacion['userfile']; ?><a href="javascript:verFoto('archivo/<?php echo $archivo;?>','610','490')"> <?php echo $archivo;?></a> ]</td>
            <td id="fuente2"><?php echo $row_verificacion['fecha_aprob_arte_verif']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['metodo_arte']; ?></td>
            <td id="fuente2"><?php echo $row_referencia['tipo_bolsa_ref']; ?></td>
          </tr>
          <tr>
            <td id="subppal2">RODILLO</td>
            <td id="subppal2">PI&Ntilde;ON</td>
            <td id="subppal2">CANTIDAD RODILLOS </td>
            <td id="subppal2">CARAS</td>
          </tr>
          <tr>
            <td id="fuente2"><?php echo $row_revision['num_rodillos_rev']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['pinon_ft']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['cant_rod_ft']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['cara_ft']; ?></td>
          </tr>
          <tr>
            <td id="subppal2">COLORES</td>
            <td id="subppal2">PANTONE</td>
            <td id="subppal2">COLORES</td>
            <td id="subppal2">PANTONE</td>
          </tr>
          <tr>
            <td id="fuente3">1. <?php echo $row_egp['color1_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['pantone1_egp']; ?></td>
            <td id="fuente3">4. <?php echo $row_egp['color4_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['pantone4_egp']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">2. <?php echo $row_egp['color2_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['pantone2_egp']; ?></td>
            <td id="fuente3">5. <?php echo $row_egp['color5_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['pantone5_egp']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">3. <?php echo $row_egp['color3_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['pantone3_egp']; ?></td>
            <td id="fuente3">6. <?php echo $row_egp['color6_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['pantone6_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="4" id="subppal2">SELLADO</td>
    </tr>
          <tr>
            <td id="subppal2">POSICION</td>
            <td id="subppal2">TIPO DE NUMERACION </td>
            <td id="subppal2">FORMATO CB </td>
            <td id="subppal2">TIPO DE ADHESIVO </td>
          </tr>
          <tr>
            <td id="fuente3">SOLAPA TR </td>
            <td id="fuente3">- <?php echo $row_egp['tipo_solapatr_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['cb_solapatr_egp']; ?></td>
            <td id="fuente2"><?php echo $row_referencia['adhesivo_ref']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">CINTA</td>
            <td id="fuente3">- <?php echo $row_egp['tipo_cinta_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['cb_cinta_egp']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['adhesivo_ref_ft']; ?></td>
          </tr>
          <tr>
            <td id="fuente3">PRINCIPAL</td>
            <td id="fuente3">- <?php echo $row_egp['tipo_principal_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['cb_principal_egp']; ?></td>
            <td id="subppal2">TIPO DE SELLO </td>
          </tr>
          <tr>
            <td id="fuente3">INFERIOR</td>
            <td id="fuente3">- <?php echo $row_egp['tipo_inferior_egp']; ?></td>
            <td id="fuente3">- <?php echo $row_egp['cb_inferior_egp']; ?></td>
            <td id="fuente2"><?php echo $row_egp['tipo_sello_egp']; ?></td>
          </tr>
          <tr>
            <td colspan="4" id="subppal2">EMPAQUE</td>
    </tr>
          <tr>
            <td id="fuente3"><input <?php if (!(strcmp($row_ficha_tecnica['control_ft'],1))) {echo "checked=\"checked\"";} ?> name="control_ft" type="checkbox" value="1">
              Control Numeraci&oacute;n </td>
            <td id="fuente3"><input <?php if (!(strcmp($row_ficha_tecnica['lista_emp_ft'],1))) {echo "checked=\"checked\"";} ?> name="lista_emp_ft" type="checkbox" value="1">
              List / Emp </td>
            <td colspan="2" id="subppal2">Dimensiones de la Caja</td>
          </tr>
          <tr>
            <td id="fuente3"><input <?php if (!(strcmp($row_ficha_tecnica['inserto_ft'],1))) {echo "checked=\"checked\"";} ?> name="inserto_ft" type="checkbox" value="1">
              Insertos Especiales</td>
            <td id="fuente3"><input <?php if (!(strcmp($row_ficha_tecnica['dist_ciud_ft'],1))) {echo "checked=\"checked\"";} ?> name="dist_ciud_ft" type="checkbox" value="1">
              Dist. Ciudades </td>
            <td colspan="2" id="fuente2"><?php $insumo=$row_ficha_tecnica['dim_caja_ft'];
			if($insumo != '')
			{
			$sqlinsumo="SELECT * FROM insumo WHERE id_insumo ='$insumo'";
			$resultinsumo= mysql_query($sqlinsumo);
			$numinsumo= mysql_num_rows($resultinsumo);
			if($numinsumo >='1')
			{ 
			$dimension = mysql_result($resultinsumo,0,'dimension_insumo');
			echo $dimension;
			}
			} ?></td>
          </tr>
          <tr>
            <td id="subppal2">Unids x Paquete</td>
            <td id="subppal2">Unids x Caja</td>
            <td id="subppal2">Paquetes x Caja</td>
            <td id="subppal2">Peso Bruto (Kg)</td>
          </tr>
          <tr>
            <td id="fuente2"><?php echo $row_egp['unids_paq_egp']; ?></td>
            <td id="fuente2"><?php echo $row_egp['unids_caja_egp']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['paq_caja_ft']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['peso_bruto_ft']; ?></td>
          </tr>
          <tr>
            <td colspan="4" id="subppal2">CONDICIONES DE USO Y ALMACENAMIENTO</td>
    </tr>
          <tr>
            <td colspan="4" id="fuente3"><strong>Vida Util:</strong> 12 a 18 meses maximo despues de fecha de producci&oacute;n.</td>
    </tr>
          <tr>
            <td colspan="2" id="fuente3"><strong>Temp. Almacenamiento:</strong> 18 a 35 &deg;C - 55% +- 5%RH </td>
            <td colspan="2" id="fuente3"><strong>Resistencia Maxima</strong> (Kg)             <?php echo $row_ficha_tecnica['resistencia_maxima_ft']; ?></td>
    </tr>
          <tr>
            <td colspan="2" id="fuente3">Condiciones de Almacenamiento </td>
            <td colspan="2" id="fuente3">Metodos de Inspecci&oacute;n </td>
    </tr>
          <tr>
            <td colspan="2" id="fuente3">1. Se deben de guardar en cajas o en paquetes protegiendo del polvo y humedad.<br>
2. No exponer a los rayos directos del sol ni al agua.<br>
3. Evitar el contacto con solventes o vapores que afecten el adhesivo.<br>
4. Siempre dar rotaci&oacute;n a los lotes antiguos para evitar caducidad.<br>
5. Evitar en el transporte el roce entre paquetes de bolsas.<br>
6. Conservar el control de numeraci&oacute;n por paquete para la trazabilidad.</td>
            <td colspan="2" id="fuente3">1. Analisis de laboratorio a la cinta de seguridad.<br>
2. Pruebas mecanicas de laboratorio material extruido.<br>
3. Pruebas de manipulacion y resistencia producto final.</td>
    </tr>
          <tr>
            <td colspan="4" id="fuente3"><strong>Notas:</strong> 1. El valor de Resistencia Maxima se toma de acuerdo a los valores obtenidos en el laboratorio. Se recomienda realizar pruebas y ensayos antes de determinar el peso a empacar. </td>
    </tr>
          <tr>
            <td id="subppal2">ESTADO FT</td>
            <td id="subppal2">FECHA MODIF. </td>
            <td colspan="2" id="subppal2">MODIFICADO POR </td>
    </tr>
          <tr>
            <td id="fuente2"><?php echo $row_ficha_tecnica['estado_ft']; ?></td>
            <td id="fuente2"><?php echo $row_ficha_tecnica['fecha_modif_ft']; ?></td>
            <td colspan="2" id="fuente2"><?php echo $row_ficha_tecnica['addcambio_ft']; ?></td>
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