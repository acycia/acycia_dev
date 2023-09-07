<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?><?php


$conexion = new ApptivaDB();

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
$query_referencia_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp ORDER BY Tbl_referencia.n_cotiz_ref DESC", $colname_referencia_egp);
$referencia_egp = mysql_query($query_referencia_egp, $conexion1) or die(mysql_error());
$row_referencia_egp = mysql_fetch_assoc($referencia_egp);
$totalRows_referencia_egp = mysql_num_rows($referencia_egp);

$colname_refs_clientes = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_refs_clientes = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
/*$colname_refs_clientes2 = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_refs_clientes2 = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}*/
mysql_select_db($database_conexion1, $conexion1);
$query_refs_clientes = sprintf("SELECT * FROM Tbl_cliente_referencia, cliente WHERE Tbl_cliente_referencia.N_referencia = '%s' AND  Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY Tbl_cliente_referencia.N_cotizacion DESC", $colname_refs_clientes,$colname_refs_clientes2);// Tbl_cliente_referencia.N_cotizacion='%s' AND
$refs_clientes = mysql_query($query_refs_clientes, $conexion1) or die(mysql_error());
$row_refs_clientes = mysql_fetch_assoc($refs_clientes);
$totalRows_refs_clientes = mysql_num_rows($refs_clientes);

$N_cotizacion=$_GET['N_cotizacion'];
$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s AND estado_arte_verif = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);

/*$colname_refs_clientes = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_refs_clientes = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refs_clientes = sprintf("SELECT * FROM Tbl_cliente_referencia, cliente WHERE Tbl_cliente_referencia.N_referencia = %s AND Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY cliente.nombre_c Asc", $colname_refs_clientes);
$refs_clientes = mysql_query($query_refs_clientes, $conexion1) or die(mysql_error());
$row_refs_clientes = mysql_fetch_assoc($refs_clientes);
$totalRows_refs_clientes = mysql_num_rows($refs_clientes);*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC & CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tablainterna">
  <tr>    
     <td colspan="10" id="principal">REFERENCIA ( BOLSA PLASTICA ) </td>
  </tr>
  <tr>
    <td rowspan="7" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td id="dato3">Menu de Dise&ntilde;o y Desarrollo</td>
    <td colspan="8" id="dato3"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" />
      <a class="editar" href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
      <?php $tipo=$_GET['tipo']; if($tipo=='1' || $tipo=='2'|| $tipo==3) { ?>
      <a class="editar" href="referencia_bolsa_edit.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&n_egp=<?php echo $row_referencia_egp['n_egp_ref']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a> <a class="editar" href="referencia_cliente.php?id_ref=<?php echo $row_referencia_egp['id_ref'];?>&cod_ref=<?php echo $row_referencia_egp['cod_ref'];?>"><img src="images/cliente.gif" alt="CLIENTES" title="CLIENTES" border="0"></a>
      <?php } else{ ?>
      <a class="editar" href="acceso.php"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a>
      <?php } ?>
      <a class="editar" href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" /></a><a class="editar" href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" /></a>
      <?php $ref=$row_referencia_egp['id_ref'];
	  $sqlrevision="SELECT * FROM revision WHERE id_ref_rev='$ref'";
	  $resultrevision= mysql_query($sqlrevision);
	  $row_revision = mysql_fetch_assoc($resultrevision);
	  $numrev= mysql_num_rows($resultrevision);
	  if($numrev >='1')
	  { ?>
      <a class="editar" href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev']; ?>" target="_top" ><img src="images/r.gif" alt="REVISION"title="REVISION" border="0" style="cursor:hand;"></a>
      <?php } else { ?>
      <a class="editar" href="revision_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>" target="_top"><img src="images/r.gif" alt="REVISION"title="REVISION" border="0" style="cursor:hand;"></a>
      <?php } ?>
      <?php //$ref=$row_referencia_egp['id_ref'];
	  $sqlverif="SELECT * FROM verificacion WHERE id_ref_verif='$ref'";
	  $resultverif= mysql_query($sqlverif);
	  $row_verif = mysql_fetch_assoc($resultverif);
	  $numverif= mysql_num_rows($resultverif);
	  if($numverif >='1')
	  { ?>
      <a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VERIFICACION"title="VERIFICACION" border="0" style="cursor:hand;"></a>
      <?php } else{ ?>
      <a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VERIFICACION"title="VERIFICACION" border="0" style="cursor:hand;"></a>
      <?php }?>
      <?php  
      //$ref=$row_referencias['id_ref'];
	  $sqlcm="SELECT * FROM control_modificaciones WHERE id_ref_cm='$ref'";
	  $resultcm= mysql_query($sqlcm);
	  $numcm= mysql_num_rows($resultcm);
	  if($numcm >='1')
	  { ?>
      <a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>" target="_top"><img src="images/m.gif" alt="VERIFICACION"title="MODIFICACION" border="0" style="cursor:hand;"></a>
      <?php } else{ ?>
      <a class="editar" href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>" target="_top"><img src="images/m.gif" alt="VERIFICACION"title="MODIFICACION" border="0" style="cursor:hand;"></a>
      <?php } ?>
      <?php //$ref=$row_referencia_egp['id_ref'];
	  $sqlval="SELECT * FROM validacion WHERE id_ref_val='$ref'";
	  $resultval= mysql_query($sqlval);
	  $row_val = mysql_fetch_assoc($resultval);
	  $numval= mysql_num_rows($resultval);
	  $id_ref_val=$row_val['id_ref_val'];
	  $version=$row_val['version_val'];
	  $sqlverif2="SELECT * FROM verificacion WHERE id_ref_verif='$id_ref_val' and version_ref_verif='$version' and estado_arte_verif != '2'";
	  $resultverif2= mysql_query($sqlverif2);
	  $row_verif2 = mysql_fetch_assoc($resultverif2);
	  $numverif2= mysql_num_rows($resultverif2);	  
	  if($numverif2 >='1')
	  { ?>
      <a class="editar" href="validacion_vista.php?id_val=<?php echo $row_val['id_val']; ?>" target="_top"><img src="images/v_rojo.gif" alt="VALIDACION" border="0" style="cursor:hand;"></a>
      <?php }else if($numval >='1')
	  { ?>
      <a class="editar" href="validacion_vista.php?id_val=<?php echo $row_val['id_val']; ?>" target="_top"><img src="images/v.gif" alt="VALIDACION"title="VALIDACION" border="0" style="cursor:hand;"></a>
      <?php } else{ ?>
      <a class="editar" href="validacion_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VALIDACION"title="VALIDACION" border="0" style="cursor:hand;"></a>
      <?php } ?>
      <?php //$ref=$row_referencias['id_ref'];
	  $sqlft="SELECT * FROM ficha_tecnica WHERE id_ref_ft='$ref'";
	  $resultft= mysql_query($sqlft);
	  $row_ft = mysql_fetch_assoc($resultft);
	  $numft= mysql_num_rows($resultft);
	  if($numft >='1')
	  { ?>
      <a class="editar" href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>" target="_top"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a>
      <?php } else{ ?>
      <a class="editar" href="ficha_tecnica_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>" target="_top"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a>
      <?php } ?>
      <a class="editar" href="referencia_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><a class="editar" href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><a class="editar" href="disenoydesarrollo.php"></a><a class="editar" href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR"title="SALIR" onClick="window.close() "/></a></td>
    </tr>
  <tr>
    <td id="dato3">Menu de Produccion</td>
    <td colspan="8" id="dato3">
    <?php $ref=$row_referencia_egp['id_ref'];
   	  $sqlpm="SELECT id_pm FROM tbl_produccion_mezclas WHERE id_ref_pm='$ref' and id_proceso='1'";//antigua-nueva
   	  $resultpm= mysql_query($sqlpm);
   	  $row_pm = mysql_fetch_assoc($resultpm);
   	  $numpm= mysql_num_rows($resultpm);
	  if($numpm >='1')
	   { ?>
      <a class="editar" href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="EXTRUSION" title="EXTRUSION" border="0" /></a>
      <?php } else { ?>
      <a class="editar" href="produccion_mezclas_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&cod_ref=<?php echo $row_referencia_egp['cod_ref']; ?>"><img src="images/e_rojo.gif" style="cursor:hand;" alt="ADD FORMULA EXTRUSION" title="ADD FORMULA EXTRUSION" border="0" /></a>
    <?php } ?>    
      
    <?php
  	  $id_ref_pr=$row_referencia_egp['cod_ref'];
  	  $sqlcv="SELECT int_id_ref_mm, id_proceso_mm FROM tbl_maestra_mezcla_caract WHERE int_cod_ref_mm='$id_ref_pr' AND id_proceso_mm ='1'";//antigua
  	  $resultcv= mysql_query($sqlcv);//tbl_maestra_mezcla_caract
  	  $numcv= mysql_num_rows($resultcv);
	  if($numcv < '1')
	  { ?>
          <a class="editar" href="produccion_caract_extrusion_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>" ><img src="images/c_rojo.gif" style="cursor:hand;" alt="FALTA CARACTERISTICA EXTRUSION" title="FALTA CARACTERISTICA EXTRUSION" border="0" /></a>
    <?php } else {?>
          <a class="editar" href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>" ><img src="images/c.gif" style="cursor:hand;" alt="FALTA CARACTERISTICA EXTRUSION" title="FALTA CARACTERISTICA EXTRUSION" border="0" /></a>
    <?php } ?>
      


    <?php $ref=$row_referencia_egp['id_ref'];
   	  $sqlci="SELECT DISTINCT id_ref_pmi,id_proceso FROM tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$ref' AND id_proceso='2'";//antigua
   	  $resultci= mysql_query($sqlci);
   	  $row_ci = mysql_fetch_assoc($resultci);
   	  $numci= mysql_num_rows($resultci);   

      $id_ref_pr=$row_referencia_egp['cod_ref'];
      $sqloca="SELECT * FROM tbl_caracteristicas_prod WHERE cod_ref='$id_ref_pr' AND proceso = '2' ORDER BY cod_ref DESC LIMIT 1";//nueva
      $resultca = mysql_query($sqloca); 
      $numca=mysql_num_rows($resultca); 
      $id_codp = mysql_result($resultca, 0, 'cod_ref');  
    ?>
    <?php if( $numci >='1' && $numca ==0 ) : ?>
      <a class="editar" href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>"><img src="images/i.gif" style="cursor:hand;" alt="IMPRESION" title="IMPRESION" border="0" /></a>      
     <?php endif; ?>      
	  <?php if( ($numci ==0  ||  $numci >='1') && $numca >='1' ) { ?> 

      <a class="editar" href="javascript:popUp('view_index.php?c=cmezclasIm&a=Mezcla&cod_ref=<?php echo $row_referencia_egp['cod_ref'];?>','870','710')"><img src="images/i.gif" style="cursor:hand;" alt="VISUALIZAR MEZCLAS NUEVAS" title="VISUALIZAR MEZCLAS NUEVAS" border="0" /></a>

      <?php } else if( $numci >='1' && $numca ==0 ) { ?> 
        
      <a class="editar" href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>"><img src="images/i.gif" style="cursor:hand;" alt="MEZCLAS IMPRESION ANTIGUA" title="MEZCLAS IMPRESION ANTIGUA" border="0" /></a> 

      <a class="editar" href="javascript:popUp('view_index.php?c=cmezclasIm&a=Mezcla&cod_ref=<?php echo $id_ref_pr;?>','870','710')"><img src="images/i_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /></a>

      <?php } else if( $numci ==0 && $numca ==0 ) :  ?> 

               <a class="editar" href="javascript:popUp('view_index.php?c=cmezclasIm&a=Mezcla&cod_ref=<?php echo $id_ref_pr;?>','870','710')"><img src="images/i_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /></a>
      <?php endif; ?>   


      <a class="editar" href="produccion_caract_sellado_vista.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>"><img src="images/s.gif" style="cursor:hand;" alt="SELLADO" title="SELLADO" border="0" /></a>


    </td>
    </tr>
  <tr>
    <td id="subppal2">FECHA DE INGRESO </td>
    <td colspan="8" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['fecha_registro1_ref']; ?></td>
    <td colspan="8" nowrap id="fuente2"><?php echo $row_referencia_egp['registro1_ref']; ?></td>
    </tr>
  <tr>
    <td id="subppal2">REFERENCIA - VERSION</td>
    <td colspan="8" id="subppal2">COTIZACION N&ordm; </td>
    </tr>
  <tr>
    <td nowrap id="fuente2"><strong><?php echo $row_referencia_egp['cod_ref']; ?> - 
      <?php echo $row_referencia_egp['version_ref']; ?></strong></td>
    <td colspan="8" id="fuente2"><?php echo $row_refs_clientes['N_cotizacion']; ?></td>
    </tr>
  <tr>
    <td colspan="10" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="10" id="subppal2">DATOS GENERALES DE LA REFERENCIA </td>
    </tr>
  <tr>
    <td id="subppal2">ANCHO</td>
    <td id="subppal2">LARGO</td>
    <td colspan="2" id="subppal2">SOLAPA</td>
    <td colspan="2" id="subppal2">Doble/Sencilla</td>
    <td colspan="4" id="subppal2">BOLSILLO PORTAGUIA</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['ancho_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['largo_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['solapa_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php if (!(strcmp($row_referencia_egp['b_solapa_caract_ref'],2))) {echo "Sencilla";} ?>
    <?php if (!(strcmp($row_referencia_egp['b_solapa_caract_ref'],1))) {echo "Doble";} ?>
    <?php if (!(strcmp($row_referencia_egp['b_solapa_caract_ref'],0))) {echo "N/A";} ?></td>
    <td colspan="4" id="fuente2"><?php echo $row_referencia_egp['bolsillo_guia_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">CALIBRE</td>
    <td id="subppal2">PESO MILLAR </td>
    <td colspan="4" id="subppal2">FUELLE</td>
    <td colspan="2" id="subppal2">ADHESIVO</td>
    <td colspan="2" id="subppal2">Tipo Adhesivo</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['calibre_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['peso_millar_ref']; ?></td>
    <td colspan="4" id="fuente2"><?php echo $row_referencia_egp['N_fuelle']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['adhesivo_ref']; ?></td>
    <td colspan="2" id="fuente2">      <?php $idcinta=$row_referencia_egp['tipoCinta_ref'];
	  $sqlcinta="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$idcinta'";
	  $resultcinta= mysql_query($sqlcinta);
	  $row_cinta = mysql_fetch_assoc($resultcinta);
	  $numcinta= mysql_num_rows($resultcinta);
	  if($numcinta >='1')
	  {  echo $row_cinta['descripcion_insumo']; 
	  }
	  ?></td>
  </tr>
  <tr>
    <td id="subppal2">TIPO DE BOLSA </td>
    <td id="subppal2">TIPO DE SELLO</td>
    <td colspan="3" id="subppal2">TROQUEL</td>
    <td id="subppal2">PRECORTE</td>
    <td id="subppal2">pre./cuerpo</td>
    <td id="subppal2">pre.e/solapa</td>
    <td colspan="2" id="subppal2">FONDO</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_bolsa_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_sello_egp']; ?></td>
    <td colspan="3" id="fuente2"><?php if (!(strcmp("", $row_referencia_egp['B_troquel']))) {echo "N.A";} ?>
             <?php if (!(strcmp("1", $row_referencia_egp['B_troquel']))) {echo "SI";} ?>
      <?php if (!(strcmp("0",$row_referencia_egp['B_troquel']))) {echo "NO";} ?></td>
    <td id="fuente2"><?php if (!(strcmp("1", $row_referencia_egp['B_precorte']))) {echo "SI";}else{echo "NO"; }?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['precorte_cuerpo']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['precorte_solapa']; ?></td>
    <td colspan="2" id="fuente2"><?php if (!(strcmp("", $row_referencia_egp['B_fondo']))) {echo "N.A";} ?>
      <?php if (!(strcmp("1", $row_referencia_egp['B_fondo']))) {echo "SI";} ?>
      <?php if (!(strcmp("0",$row_referencia_egp['B_fondo']))) {echo "NO";} ?></td>
  </tr>
  <tr>
    <td rowspan="2" id="subppal2">PRESENTACION</td>
    <td rowspan="2" id="subppal2">ANCHO ROLLO:</td>
    <td rowspan="2" id="subppal2">TRATAMIENTO CORONA</td>
    <td colspan="8" id="subppal2">Bolsillo Portaguia</td>
    </tr>
  <tr>
    <td id="subppal2"> (Ubicacion)</td>
    <td id="subppal2">(Forma)</td>
    <td id="subppal2">Cant/Traslape</td>
    <td id="subppal2">Peso Millar Bolsillo</td>
    <td id="subppal">Calibre/Bols</td>
    <td id="subppal">Lamina 1</td>
    <td id="subppal">Lamina 2</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['Str_presentacion']; ?></td>
    <td id="fuente3"><?php echo $row_referencia_egp['ancho_rollo']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['Str_tratamiento']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['str_bols_ub_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['str_bols_fo_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['B_cantforma']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['peso_millar_bols']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['calibreBols_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_1_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_2_ref']; ?></td>
  </tr>
  <tr>
    <td id="fuente1">&nbsp;</td>
    <td colspan="10" id="fuente1">&nbsp;</td>
    </tr>
   <tr>	
        <td rowspan="2" id="subppal2">MARGENES</td>
        <td id="subppal2">Izquierda mm</td>
      <td id="fuente1"><?php echo $row_referencia_egp['margen_izq_imp_egp']; ?></td>
        <td id="subppal2">Rep. en Ancho</td>
      <td colspan="2" id="fuente1"><?php echo $row_referencia_egp['margen_anc_imp_egp']; ?></td>
        <td id="fuente1">de</td>
      <td id="fuente1"><?php echo $row_referencia_egp['margen_anc_mm_imp_egp']; ?></td>
      <td colspan="2" id="fuente1">mm</td>
    </tr>
      <tr>
        <td id="subppal2">Derecha mm</td>
        <td id="fuente1"><?php echo $row_referencia_egp['margen_der_imp_egp']; ?></td>
        <td id="subppal2">Rep. Perimetro</td>
        <td colspan="2" id="fuente1"><?php echo $row_referencia_egp['margen_peri_imp_egp']; ?></td>
        <td id="fuente1">de</td>
        <td id="fuente1"><?php echo $row_referencia_egp['margen_per_mm_imp_egp']; ?></td>
        <td colspan="2" id="fuente1">mm</td>
      </tr>
      <tr  id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="subppal2"><strong>Z</strong></td>
        <td id="fuente1"><?php echo $row_referencia_egp['margen_z_imp_egp']; ?></td>
        <td colspan="10" id="fuente1">IMPUESTO $  <strong><?php echo $row_referencia_egp['valor_impuesto']?></strong>
        </td>
    </tr>    
  <tr>
    <td colspan="10" id="subppal2">DATOS ESPECIFICOS DE LA REFERENCIA </td>
  </tr>
  <tr>
    <td id="subppal2">MATERIAL</td>
    <td id="subppal2">PIGMENTO EXTERIOR </td>
    <td colspan="8" id="subppal2">PIGMENTO INTERIOR </td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['material_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['pigm_ext_egp']; ?></td>
    <td colspan="8" id="fuente2"><?php echo $row_referencia_egp['pigm_int_epg']; ?></td>
    </tr>
  <tr>
    <td id="subppal2">TIPO DE SELLO </td>
    <td colspan="10" id="subppal2">IMPRESION</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_sello_egp']; ?></td>
    <td id="fuente3">COLOR 1 : <?php echo $row_referencia_egp['color1_egp']; ?></td>
    <td colspan="4" id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone1_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone1_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td colspan="4" id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion1_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="fecha_cad_egp" value="1">
      Fecha de Caducidad</td>
    <td id="fuente3">COLOR 2 : <?php echo $row_referencia_egp['color2_egp']; ?></td>
    <td colspan="4" id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone2_egp']) {$insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone2_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td colspan="4" id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion2_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="arte_sum_egp" value="1">
      Arte Suministrado</td>
    <td id="fuente3">COLOR 3 : <?php echo $row_referencia_egp['color3_egp']; ?></td>
    <td colspan="4" id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone3_egp']) {$insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone3_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?>  </td>
    <td colspan="4" id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion3_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="orient_arte_egp" value="1">
      Solicita Orientacion</td>
    <td id="fuente3">COLOR 4 : <?php echo $row_referencia_egp['color4_egp']; ?></td>
    <td colspan="4" id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone4_egp']) {$insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone4_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td colspan="4" id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion4_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente3"><input <?php if (!(strcmp($row_referencia_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="ent_logo_egp" value="1">
      Logos de la Entidad</td>
    <td id="fuente3">COLOR 5 : <?php echo $row_referencia_egp['color5_egp']; ?></td>
    <td colspan="4" id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone5_egp']) {$insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone5_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td colspan="4" id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion5_egp']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">ARTE</td>
    <td id="fuente3">COLOR 6 : <?php echo $row_referencia_egp['color6_egp']; ?></td>
    <td colspan="4" id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone6_egp']) {$insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone6_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td colspan="4" id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion6_egp']; ?></td>
  </tr>
    <tr>
    <td rowspan="3" id="fuente2"><a class="editar" href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"> <?php echo $row_ref_verif['userfile']; ?> </a></td>
    <td id="fuente3">COLOR 7 : <?php echo $row_referencia_egp['color7_egp']; ?></td>
    <td colspan="4" id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone7_egp']){$insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone7_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo']; } ?></td>
    <td colspan="4" id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion7_egp']; ?></td>
  </tr>
    <tr>
    <td id="fuente3">COLOR 8 : <?php echo $row_referencia_egp['color8_egp']; ?></td>
    <td colspan="4" id="fuente3">PANTONE : <?php if($row_referencia_egp['pantone8_egp']) {$insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_referencia_egp['pantone8_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
    <td colspan="4" id="fuente3">UBICACION : <?php echo $row_referencia_egp['ubicacion8_egp']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">POSICION</td>
    <td colspan="4" id="subppal2">TIPO DE NUMERACION </td>
    <td colspan="4" id="subppal2">BARRAS &amp; FORMATO</td>
  </tr>
  <tr>
    <td id="subppal2">ESTADO DEL ARTE </td>
    <td id="subppal2">SOLAPA TALONARIO RECIBO </td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['tipo_solapatr_egp']; ?></td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['cb_solapatr_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2"><?php $estado=$row_ref_verif['estado_arte_verif'];
	if($estado=='0') { echo "Pendiente"; }
	if($estado=='1') { echo "Rechazado"; }
	if($estado=='2') { echo "Aceptado"; }
	if($estado=='3') { echo "Anulado"; } ?>
	</td>
    <td id="subppal2">CINTA</td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['tipo_cinta_egp']; ?></td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['cb_cinta_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2">&nbsp;</td>
    <td id="subppal2">SUPERIOR</td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['tipo_superior_egp']; ?></td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['cb_superior_egp']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">Fecha Aprobaci&oacute;n Arte </td>
    <td id="subppal2">PRINCIPAL</td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['tipo_principal_egp']; ?></td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_ref_verif['fecha_aprob_arte_verif']; ?></td>
    <td id="subppal2">INFERIOR</td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['tipo_inferior_egp']; ?></td>
    <td colspan="4" id="fuente3"><?php echo $row_referencia_egp['cb_inferior_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2">&nbsp;</td>
    <td id="subppal2">LINER</td>
    <td colspan="4" id="fuente1"><?php echo $row_referencia_egp['tipo_liner_egp']; ?></td>
    <td colspan="4" id="fuente1"><?php echo $row_referencia_egp['cb_liner_egp']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">Numeracion Comienza en:</td>
    <td id="subppal2">BOLSILLO</td>
    <td colspan="4" id="fuente1"><?php echo $row_referencia_egp['tipo_bols_egp']; ?></td>
    <td colspan="4" id="fuente1"><?php echo $row_referencia_egp['cb_bols_egp']; ?></td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['comienza_egp']; ?></td>
    <td id="subppal2">Otro: &nbsp;<?php echo $row_referencia_egp['tipo_nom_egp']; ?></td>
    <td colspan="4" id="fuente1"><?php echo $row_referencia_egp['tipo_otro_egp']; ?></td>
    <td colspan="4" id="fuente1"><?php echo $row_referencia_egp['cb_otro_egp']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">Unidades por Paquete</td>
    <td id="fuente2"><?php echo $row_referencia_egp['unids_paq_egp']; ?></td>
    <td id="subppal2">Unidades por Caja </td>
    <td colspan="4" id="fuente2"><?php echo $row_referencia_egp['unids_caja_egp']; ?></td>
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
    <td colspan="10" id="subppal2">CLIENTES ASIGNADOS A ESTA REFERENCIA </td>
    </tr>
  <tr>
    <td id="subppal2">CLIENTE</td>
    <td id="subppal2">DIRECCION</td>
    <td colspan="4" id="subppal2">PAIS / CIUDAD </td>
    <td colspan="4" id="subppal2">TELEFONO</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="fuente3"><?php echo $row_refs_clientes['nombre_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['direccion_c']; ?></td>
      <td colspan="4" id="fuente3"><?php echo $row_refs_clientes['pais_c']; ?> / <?php echo $row_refs_clientes['ciudad_c']; ?></td>
      <td colspan="4" id="fuente3"><?php echo $row_refs_clientes['telefono_c']; ?></td>
    </tr>
    <?php } while ($row_refs_clientes = mysql_fetch_assoc($refs_clientes)); ?>
  <tr>
    <td colspan="10" id="fondo">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" id="subppal2">FECHA ULTIMA MODIFICACION </td>
    <td colspan="8" id="subppal2">RESPONSABLE ULTIMA MODIFICACION </td>
    </tr>
  <tr>
    <td colspan="2" id="fuente2"><?php echo $row_referencia_egp['fecha_registro2_ref']; ?></td>
    <td colspan="8" id="fuente2"><?php echo $row_referencia_egp['registro2_ref']; ?></td>
    </tr>
</table>
</div>
</body>
</html>
<script type="text/javascript">
    $(document).ready(function(){
    var editar =  "<?php echo $_SESSION['no_edita'];?>";
  
    if( editar==0 ){
       
      $("input").attr('disabled','disabled'); 
      $("textarea").attr('disabled','disabled');
      /*$("select").attr('disabled','disabled');*/
      $("button").attr('disabled','disabled');


       $('a').each(function() { 
        $(".editar").attr('href', '#');
      }); 
                swal("No Autorizado", "Sin permisos para editar :)", "error"); 
    } 
  });
</script>
<?php
mysql_free_result($referencia_egp);

mysql_free_result($usuario);

mysql_free_result($refs_clientes);

mysql_free_result($ref_verif);

?>
