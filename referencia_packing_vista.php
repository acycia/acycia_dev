<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?><?php


$conexion = new ApptivaDB();

$colname_referenciaver = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referenciaver = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
/*$colname_referenciaver2 = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_referenciaver2 = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}*/
mysql_select_db($database_conexion1, $conexion1);
$query_referenciaver = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref = '%s' ORDER BY Tbl_referencia.n_cotiz_ref DESC", $colname_referenciaver );
$referenciaver = mysql_query($query_referenciaver, $conexion1) or die(mysql_error());
$row_referenciaver = mysql_fetch_assoc($referenciaver);
$totalRows_referenciaver = mysql_num_rows($referenciaver);

$colname_referencia_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_referencia_egp);
$referencia_egp = mysql_query($query_referencia_egp, $conexion1) or die(mysql_error());
$row_referencia_egp = mysql_fetch_assoc($referencia_egp);
$totalRows_referencia_egp = mysql_num_rows($referencia_egp);

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

/*$colname_refs_clientes = "-1";
if (isset($_GET['Str_nit'])) {
  $colname_refs_clientes = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refs_clientes = sprintf("SELECT * FROM cliente WHERE nit_c='%s'", $colname_refs_clientes);
$refs_clientes = mysql_query($query_refs_clientes, $conexion1) or die(mysql_error());
$row_refs_clientes = mysql_fetch_assoc($refs_clientes);
$totalRows_refs_clientes = mysql_num_rows($refs_clientes);*/

$N_cotizacion=$_GET['N_cotizacion'];

$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT * FROM Tbl_verificacion_packing WHERE id_ref_verif_p = %s AND estado_arte_verif_p = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);
$colname_refs_clientes = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_refs_clientes = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
/*$colname_refs_clientes2 = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_refs_clientes2 = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}*/
mysql_select_db($database_conexion1, $conexion1);
$query_refs_clientes = sprintf("SELECT * FROM Tbl_cliente_referencia, cliente WHERE Tbl_cliente_referencia.N_referencia = '%s' AND   Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY Tbl_cliente_referencia.N_cotizacion DESC", $colname_refs_clientes,$colname_refs_clientes2);//Tbl_cliente_referencia.N_cotizacion='%s' AND
$refs_clientes = mysql_query($query_refs_clientes, $conexion1) or die(mysql_error());
$row_refs_clientes = mysql_fetch_assoc($refs_clientes);
$totalRows_refs_clientes = mysql_num_rows($refs_clientes);
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
     <td colspan="4" id="principal">REFERENCIA ( PACKING LIST) </td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="3" id="dato3"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" />
	<?php $tipo=$_GET['tipo']; if($tipo=='1'|| $tipo==2|| $tipo==3) { ?><a href="referencia_packing_edit.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&n_egp=<?php echo $row_referenciaver['n_egp_ref']; ?>"><img src="images/menos.gif" alt="EDITAR" title"EDITAR"border="0" /></a>
    <a href="referencia_cliente.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&cod_ref=<?php echo $row_referenciaver['cod_ref'];?>"><img src="images/cliente.gif" alt="CLIENTES" title="CLIENTES" border="0"></a>
    <?php } else{ ?>
    <a href="acceso.php"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a>
      <?php } ?>
      <?php $ref=$row_referenciaver['id_ref'];
	  $sqlcv="SELECT id_ref_cv FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$ref'";
	  $resultcv= mysql_query($sqlcv);
	  $row_cv = mysql_fetch_assoc($resultcv);
	  $numcv= mysql_num_rows($resultcv);
	  if($numcv >='1')
	  { ?>       
    <a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&id_pm=<?php echo $row_cv['id_pm_cv']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="EXTRUSION" title="EXTRUSION" border="0" /></a>
	<?php } else{ ?> 
    <a href="produccion_mezclas_add.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&cod_ref=<?php echo $row_referenciaver['cod_ref']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="ADD FORMULA EXTRUSION" title="ADD FORMULA EXTRUSION" border="0" /></a>      
	<?php } ?>
    <a href="referencias_p.php"><img src="images/a.gif" style="cursor:hand;" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" /></a>
<?php $ref=$row_referenciaver['id_ref'];
	  $sqlrevision="SELECT * FROM Tbl_revision_packing WHERE id_ref_rev_p='$ref'";
	  $resultrevision= mysql_query($sqlrevision);
	  $row_revision = mysql_fetch_assoc($resultrevision);
	  $numrev= mysql_num_rows($resultrevision);
	  if($numrev >='1')
	  { ?><a href="revision_packing_vista.php?id_rev_p=<?php echo $row_revision['id_rev_p']; ?>" target="_top" ><img src="images/r.gif" alt="REVISION"title="REVISION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="revision_packing_add.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/r.gif" alt="REVISION"title="REVISION" border="0" style="cursor:hand;"></a><?php } ?></a>
      
<?php //$ref=$row_referenciaver['id_ref'];
	  $sqlverif="SELECT * FROM Tbl_verificacion_packing WHERE id_ref_verif_p='$ref'";
	  $resultverif= mysql_query($sqlverif);
	  $row_verif = mysql_fetch_assoc($resultverif);
	  $numverif= mysql_num_rows($resultverif);
	  if($numverif >='1')
	  { ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VERIFICACION"title="VERIFICACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VERIFICACION"title="VERIFICACION" border="0" style="cursor:hand;"></a> <?php }?> 
	  <?php  
      //$ref=$row_referencias['id_ref'];
	  $sqlcm="SELECT * FROM Tbl_control_modificaciones_p WHERE id_ref_cm='$ref'";
	  $resultcm= mysql_query($sqlcm);
	  $numcm= mysql_num_rows($resultcm);
	  if($numcm >='1')
	  { ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/m.gif" alt="MODIFICACION"title="MODIFICACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="verificacion_referencia_packing.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/m.gif" alt="MODIFICACION"title="MODIFICACION" border="0" style="cursor:hand;"></a> <?php } ?>	  

<?php //$ref=$row_referenciaver['id_ref'];
	  $sqlval="SELECT * FROM Tbl_validacion_packing WHERE id_ref_val_p='$ref'";
	  $resultval= mysql_query($sqlval);
	  $row_val = mysql_fetch_assoc($resultval);
	  $numval= mysql_num_rows($resultval);
	  $id_ref_val=$row_val['id_ref_val_p'];
	  $version=$row_val['version_val_p'];
	  $sqlverif2="SELECT * FROM Tbl_verificacion_packing WHERE id_ref_verif_p='$id_ref_val' and version_ref_verif_p='$version' and estado_arte_verif_p != '2'";
	  $resultverif2= mysql_query($sqlverif2);
	  $row_verif2 = mysql_fetch_assoc($resultverif2);
	  $numverif2= mysql_num_rows($resultverif2);	  
	  if($numverif2 >='1')
	  { ?><a href="validacion_packing_vista.php?id_val_p=<?php echo $row_val['id_val_p']; ?>" target="_top"><img src="images/v_rojo.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;"></a> <?php }else if($numval >='1')
	  { ?> <a href="validacion_packing_vista.php?id_val_p=<?php echo $row_val['id_val_p']; ?>" target="_top"><img src="images/v.gif" alt="VALIDACION"title="VALIDACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="validacion_packing_add.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VALIDACION"title="VALIDACION" border="0" style="cursor:hand;"></a> <?php } ?>

      <?php //$ref=$row_referencias['id_ref'];
	  $sqlft="SELECT * FROM TblFichaTecnica WHERE id_ref_ft='$ref'";
	  $resultft= mysql_query($sqlft);
	  $row_ft = mysql_fetch_assoc($resultft);
	  $numft= mysql_num_rows($resultft);
	  if($numft >='1')
	  { ?> <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>" target="_top"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a> <?php } ?>    
    
    
    <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><a href="disenoydesarrollo.php"></a><a href="referencia_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR"title="SALIR" onClick="window.close()"/></a></td>
    </tr>
  <tr>
    <td id="subppal2">FECHA DE INGRESO </td>
    <td colspan="2" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['fecha_registro1_ref']; ?></td>
    <td colspan="2" nowrap id="fuente2"><?php echo $row_referenciaver['registro1_ref']; ?></td>
    </tr>
  <tr>
    <td id="subppal2">REFERENCIA - VERSION</td>
    <td colspan="2" id="subppal2">COTIZACION N&ordm; </td>
    </tr>
  <tr>
    <td nowrap id="fuente2"><strong><?php echo $row_referenciaver['cod_ref']; ?> - 
      <?php echo $row_referenciaver['version_ref']; ?></strong></td>
    <td colspan="2" id="fuente2"><?php echo $row_refs_clientes['N_cotizacion']; ?></td>
    </tr>
  <tr>
    <td colspan="3" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="4" id="subppal2">DATOS GENERALES DE LA REFERENCIA </td>
    </tr>
  <tr>
    <td id="subppal2">ANCHO</td>
    <td id="subppal2">LARGO</td>
    <td id="subppal2">SOLAPA</td>
    <td id="subppal2">CALIBRE</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['ancho_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['largo_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['solapa_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['calibre_ref']; ?></td>
    </tr>
<tr>
    <td id="subppal2">Boca de Entrada</td>
    <td id="subppal2">PESO MILLAR</td>
    <td id="subppal2">Lamina 1 (Adhesivo)</td>
    <td id="subppal2">Lamina 2</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['Str_boca_entr_p']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['peso_millar_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['Str_lamina1_p']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['Str_lamina2_p']; ?></td>
    </tr>    
        
  <tr>
    <td id="subppal2">Ubicacion de la Entrada:</td>
    <td id="subppal2">&nbsp;</td>
    <td id="subppal2">PRESENTACION</td>
    <td id="subppal2">TRATAMIENTO CORONA</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['Str_entrada_p']; ?></td>
    <td id="fuente2">&nbsp;</td>
    <td id="fuente2"><?php echo $row_referenciaver['Str_presentacion']; ?></td>
    <td id="fuente2"><?php echo $row_referenciaver['Str_tratamiento']; ?></td>
    </tr>    
  <tr>
    <td colspan="4" id="subppal2">DATOS ESPECIFICOS DE LA REFERENCIA </td>
  </tr>
  <tr>
        <td colspan="9" id="fuente1">
            IMPUESTO $  <strong><?php echo $row_referenciaver['valor_impuesto']?></strong>
        </td>
      </tr>
  <tr>
    <td id="subppal2">TIPO DE SELLO </td>
    <td colspan="3" id="subppal2">IMPRESION</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_sello_egp']; ?></td>
    <td id="fuente3">COLOR 1 : <?php echo $row_referencia_egp['color1_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone1_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone1_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion1_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fecha_cad_egp" value="1">
      Fecha de Caducidad</td>
    <td id="fuente3">COLOR 2 : <?php echo $row_referencia_egp['color2_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone2_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone2_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion2_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="arte_sum_egp" value="1">
      Arte Suministrado</td>
    <td id="fuente3">COLOR 3 : <?php echo $row_referencia_egp['color3_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone3_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone3_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion3_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="orient_arte_egp" value="1">
      Solicita Orientacion</td>
    <td id="fuente3">COLOR 4 : <?php echo $row_referencia_egp['color4_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone4_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone4_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion4_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ent_logo_egp" value="1">
      Logos de la Entidad</td>
    <td id="fuente3">COLOR 5 : <?php echo $row_referencia_egp['color5_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone5_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone5_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion5_egp']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">ARTE</td>
    <td id="fuente3">COLOR 6 : <?php echo $row_referencia_egp['color6_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone6_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone6_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion6_egp']; ?></td>
  </tr>
    <tr>
    <td rowspan="3" id="fuente3"><a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile_p'];?>','610','490')"> <?php echo $row_ref_verif['userfile_p']; ?> </a></td>
    <td id="fuente3">COLOR 7:  <?php echo $row_referencia_egp['color7_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone7_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone7_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion7_egp']; ?></td>
  </tr>
    <tr>
    <td id="fuente3">COLOR 8 : <?php echo $row_referencia_egp['color8_egp']; ?></td>
    <td id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone8_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone8_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion8_egp']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">POSICION</td>
    <td id="subppal2">TIPO DE NUMERACION </td>
    <td id="subppal2">BARRAS &amp; FORMATO</td>
  </tr>
  <tr>
    <td id="subppal2">ESTADO DEL ARTE </td>
    <td id="subppal2">PRINCIPAL</td>
    <td id="fuente3"><?php echo $row_referencia_egp['tipo_principal_egp']; ?></td>
    <td id="fuente3"><?php echo $row_referencia_egp['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2"><?php $estado=$row_ref_verif['estado_arte_verif_p'];
	if($estado=='0') { echo "Pendiente"; }
	if($estado=='1') { echo "Rechazado"; }
	if($estado=='2') { echo "Aceptado"; }
	if($estado=='3') { echo "Anulado"; } ?>
	</td>
    <td id="subppal2">INFERIOR</td>
    <td id="fuente3"><?php echo $row_referencia_egp['tipo_inferior_egp']; ?></td>
    <td id="fuente3"><?php echo $row_referencia_egp['cb_inferior_egp']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">Fecha Aprobaci&oacute;n Arte </td>
    <td id="subppal2">COMIENZA EN</td>
    <td colspan="2" id="fuente3"><?php echo $row_referencia_egp['comienza_egp']; ?></td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_ref_verif['fecha_aprob_arte_verif_p']; ?></td>
    <td colspan="2" id="subppal2">Medida de la Caja</td>
    <td id="fuente1">
        <?php $id_insumo = $row_referencia_egp['marca_cajas_egp'];
		$sqln="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$id_insumo'"; 
		$resultn=mysql_query($sqln); 
		$numn=mysql_num_rows($resultn); 
		if($numn >= '1') 
		{ $descripcion=mysql_result($resultn,0,'descripcion_insumo'); echo $descripcion; }
		else { echo "";	
		}?>
    </td>
    </tr>
<tr>
    <td id="subppal2">Unidades por Paquete</td>
    <td id="fuente2"><?php echo $row_referencia_egp['unids_paq_egp']; ?></td>
    <td id="subppal2">Unidades por Caja </td>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['unids_caja_egp']; ?></td>

    </tr>    
  <tr>
    <td colspan="4" id="subppal2">CLIENTES ASIGNADOS A ESTA REFERENCIA </td>
    </tr>
  <tr>
    <td id="subppal2">CLIENTE</td>
    <td id="subppal2">DIRECCION</td>
    <td id="subppal2">PAIS / CIUDAD </td>
    <td id="subppal2">TELEFONO</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="fuente3"><?php echo $row_refs_clientes['nombre_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['direccion_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['pais_c']; ?> / <?php echo $row_refs_clientes['ciudad_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['telefono_c']; ?></td>
    </tr>
    <?php } while ($row_refs_clientes = mysql_fetch_assoc($refs_clientes)); ?>
  <tr>
    <td colspan="4" id="fondo">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" id="subppal2">FECHA ULTIMA MODIFICACION </td>
    <td colspan="2" id="subppal2">RESPONSABLE ULTIMA MODIFICACION </td>
    </tr>
  <tr>
    <td colspan="2" id="fuente2"><?php echo $row_referenciaver['fecha_registro2_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referenciaver['registro2_ref']; ?></td>
    </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($referenciaver);

mysql_free_result($referencia_egp);

mysql_free_result($usuario);

mysql_free_result($refs_clientes);

//mysql_free_result($ref_verif);
?>
