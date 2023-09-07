<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); 


$conexion = new ApptivaDB();

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

/*$colname_vista_egl = "-1";
if (isset($_GET['n_egl'])) {
  $colname_vista_egl = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_vista_egl = sprintf("SELECT * FROM egl WHERE n_egl = %s", GetSQLValueString($colname_vista_egl, "int"));
$vista_egl = mysql_query($query_vista_egl, $conexion1) or die(mysql_error());
$row_vista_egl = mysql_fetch_assoc($vista_egl);
$totalRows_vista_egl = mysql_num_rows($vista_egl);*/

/*$colname_colores = "-1";
if (isset($_GET['n_egl'])) {
  $colname_colores = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_colores = sprintf("SELECT * FROM egl_colores WHERE n_egl = %s", GetSQLValueString($colname_colores, "int"));
$colores = mysql_query($query_colores, $conexion1) or die(mysql_error());
$row_colores = mysql_fetch_assoc($colores);
$totalRows_colores = mysql_num_rows($colores);*/

/*$colname_archivos = "-1";
if (isset($_GET['n_egl'])) {
  $colname_archivos = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_archivos = sprintf("SELECT * FROM egl_archivos WHERE n_egl = %s", GetSQLValueString($colname_archivos, "int"));
$archivos = mysql_query($query_archivos, $conexion1) or die(mysql_error());
$row_archivos = mysql_fetch_assoc($archivos);
$totalRows_archivos = mysql_num_rows($archivos);*/
//desde
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_referenciaver = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referenciaver = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referenciaver = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref = '%s' ORDER BY Tbl_referencia.n_cotiz_ref DESC", $colname_referenciaver,$colname_referenciaver2);
$referenciaver = mysql_query($query_referenciaver, $conexion1) or die(mysql_error());
$row_referenciaver = mysql_fetch_assoc($referenciaver);
$totalRows_referenciaver = mysql_num_rows($referenciaver);

$colname_referencia_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_vista_egl = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_referencia_egp);
$vista_egl = mysql_query($query_vista_egl, $conexion1) or die(mysql_error());
$row_vista_egl= mysql_fetch_assoc($vista_egl);
$totalRows_vista_egl = mysql_num_rows($vista_egl);

$N_cotizacion=$_GET['N_cotizacion'];

$colname_refs_clientes = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_refs_clientes = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
/*$colname_refs_clientes2 = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_refs_clientes2 = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}*/
mysql_select_db($database_conexion1, $conexion1);
$query_refs_clientes = sprintf("SELECT * FROM Tbl_cliente_referencia, cliente WHERE Tbl_cliente_referencia.N_referencia = '%s' AND Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY Tbl_cliente_referencia.N_cotizacion DESC", $colname_refs_clientes,$colname_refs_clientes2);//AND Tbl_cliente_referencia.N_cotizacion='%s'
$refs_clientes = mysql_query($query_refs_clientes, $conexion1) or die(mysql_error());
$row_refs_clientes = mysql_fetch_assoc($refs_clientes);
$totalRows_refs_clientes = mysql_num_rows($refs_clientes);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tablainterna">
<tr>
  <td width="2463" id="subppal">CODIGO : R1 - F08</td>
  <td colspan="4" nowrap="nowrap" id="principal">ESPECIFICACION GENERAL DE LA LAMINA</td>
  <td width="141" id="subppal">VERSION : 2 </td>
</tr>
<tr>
  <td rowspan="8" id="fuente2"><img src="images/logoacyc.jpg"/></td>
  <td colspan="5" nowrap="nowrap" id="dato3"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" />
    <?php $ref=$row_referenciaver['id_ref'];
	  $sqlcv="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$ref'";
	  $resultcv= mysql_query($sqlcv);
	  $row_cv = mysql_fetch_assoc($resultcv);
	  $numcv= mysql_num_rows($resultcv);
	  if($numcv >='1')
	  { ?>       
    <a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&id_pm=<?php echo $row_cv['id_pm_cv']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="EXTRUSION" title="EXTRUSION" border="0" /></a><?php } else{ ?> <a href="produccion_mezclas_add.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&cod_ref=<?php echo $row_referenciaver['cod_ref']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="ADD FORMULA EXTRUSION" title="ADD FORMULA EXTRUSION" border="0" /></a> <?php } ?>  
    
    <a href="referencia_lamina_edit.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&n_egp=<?php echo $row_referenciaver['n_egp_ref']; ?>"><img src="images/menos.gif" alt="EDITAR" title"EDITAR"border="0" /></a>
    <a href="referencia_cliente.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&cod_ref=<?php echo $row_referenciaver['cod_ref'];?>"><img src="images/cliente.gif" alt="CLIENTES" title="CLIENTES" border="0"></a>
  <?php $ref=$row_referenciaver['id_ref'];
	  $sqlrevision="SELECT * FROM Tbl_revision_lamina WHERE id_ref_rev_l='$ref'";
	  $resultrevision= mysql_query($sqlrevision);
	  $row_revision = mysql_fetch_assoc($resultrevision);
	  $numrev= mysql_num_rows($resultrevision);
	  if($numrev >='1')
	  { ?><a href="revision_lamina_vista.php?id_rev_l=<?php echo $row_revision['id_rev_l']; ?>" target="_top" ><img src="images/r.gif" alt="REVISION"title="REVISION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="revision_lamina_add.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/r.gif" alt="REVISION"title="REVISION" border="0" style="cursor:hand;"></a><?php } ?></a>
    
  <?php //$ref=$row_referenciaver['id_ref'];
	  $sqlverif="SELECT * FROM Tbl_verificacion_lamina WHERE id_ref_verif_l='$ref'";
	  $resultverif= mysql_query($sqlverif);
	  $row_verif = mysql_fetch_assoc($resultverif);
	  $numverif= mysql_num_rows($resultverif);
	  if($numverif >='1')
	  { ?> <a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VERIFICACION"title="VERIFICACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VERIFICACION"title="VERIFICACION" border="0" style="cursor:hand;"></a> <?php }?> 
    <?php  
      //$ref=$row_referencias['id_ref'];
	  $sqlcm="SELECT * FROM Tbl_control_modificaciones_l WHERE id_ref_cm='$ref'";
	  $resultcm= mysql_query($sqlcm);
	  $numcm= mysql_num_rows($resultcm);
	  if($numcm >='1')
	  { ?> <a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/m.gif" alt="MODIFICACION"title="MODIFICACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/m.gif" alt="MODIFICACION"title="MODIFICACION" border="0" style="cursor:hand;"></a> <?php } ?>	  
    
  <?php //$ref=$row_referenciaver['id_ref'];
	  $sqlval="SELECT * FROM Tbl_validacion_lamina WHERE id_ref_val_l='$ref'";
	  $resultval= mysql_query($sqlval);
	  $row_val = mysql_fetch_assoc($resultval);
	  $numval= mysql_num_rows($resultval);
	  $id_ref_val=$row_val['id_ref_val_l'];
	  $version=$row_val['version_val_l'];
	  $sqlverif2="SELECT * FROM Tbl_verificacion_lamina WHERE id_ref_verif_l='$id_ref_val' and version_ref_verif_l='$version' and estado_arte_verif_l != '2'";
	  $resultverif2= mysql_query($sqlverif2);
	  $row_verif2 = mysql_fetch_assoc($resultverif2);
	  $numverif2= mysql_num_rows($resultverif2);	  
	  if($numverif2 >='1')
	  { ?><a href="validacion_lamina_vista.php?id_val_l=<?php echo $row_val['id_val_l']; ?>" target="_top"><img src="images/v_rojo.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;"></a> <?php }else if($numval >='1')
	  { ?> <a href="validacion_lamina_vista.php?id_val_l=<?php echo $row_val['id_val_l']; ?>" target="_top"><img src="images/v.gif" alt="VALIDACION"title="VALIDACION" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="validacion_lamina_add.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/v.gif" alt="VALIDACION"title="VALIDACION" border="0" style="cursor:hand;"></a> <?php } ?>
    
    <?php //$ref=$row_referencias['id_ref'];
	  $sqlft="SELECT * FROM ficha_tecnica WHERE id_ref_ft='$ref'";
	  $resultft= mysql_query($sqlft);
	  $row_ft = mysql_fetch_assoc($resultft);
	  $numft= mysql_num_rows($resultft);
	  if($numft >='1')
	  { ?> <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ft['n_ft']; ?>" target="_top"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a> <?php } else{ ?> <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>" target="_top"><img src="images/f.gif" alt="FICHA TECNICA"title="FICHA TECNICA" border="0" style="cursor:hand;"></a> <?php } ?>  
    
  <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><a href="referencia_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><a href="disenoydesarrollo.php"></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR"title="SALIR" onClick="window.close() "/></a></td>
  </tr>
<tr>
  <td colspan="5" nowrap="nowrap" id="fondo4"><?php $ref=$row_vista_egl['id_ref'];
	  $sqlpm="SELECT * FROM Tbl_produccion_mezclas WHERE id_ref_pm='$ref' and id_proceso='1'";
	  $resultpm= mysql_query($sqlpm);
	  $row_pm = mysql_fetch_assoc($resultpm);
	  $numpm= mysql_num_rows($resultpm);
	  if($numpm >='1')
	  { ?>
      <a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_vista_egl['id_ref']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="EXTRUSION" title="EXTRUSION" border="0" /></a>
      <?php } else{ ?>
      <a href="produccion_mezclas_add.php?id_ref=<?php echo $row_vista_egl['id_ref']; ?>&cod_ref=<?php echo $row_vista_egl['cod_ref']; ?>"><img src="images/e_rojo.gif" style="cursor:hand;" alt="ADD FORMULA EXTRUSION" title="ADD FORMULA EXTRUSION" border="0" /></a>
      <?php } ?>    
      
      <?php
	  $ref_cv=$row_vista_egl['id_ref'];
	  $sqlcv="SELECT int_id_ref_mm, id_proceso_mm FROM Tbl_maestra_mezcla_caract WHERE int_id_ref_mm='$ref_cv' AND id_proceso_mm='2'"; 
	  $resultcv= mysql_query($sqlcv);
	  $numcv= mysql_num_rows($resultcv);
	  if($numcv < '1')
	  { ?>
      <a href="produccion_caract_extrusion_add.php?id_ref=<?php echo $row_vista_egl['id_ref']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>" ><img src="images/c_rojo.gif" style="cursor:hand;" alt="FALTA CARACTERISTICA EXTRUSION" title="FALTA CARACTERISTICA EXTRUSION" border="0" /></a>
      <?php } else{?>
      <a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_vista_egl['id_ref']; ?>&id_pm=<?php echo $row_pm['id_pm']; ?>" ><img src="images/c.gif" style="cursor:hand;" alt="FALTA CARACTERISTICA EXTRUSION" title="FALTA CARACTERISTICA EXTRUSION" border="0" /></a>
      <?php }?>
      
      <?php $ref=$row_vista_egl['id_ref'];
	  $sqlci="SELECT DISTINCT id_ref_pmi,id_proceso FROM Tbl_produccion_mezclas_impresion WHERE id_ref_pmi='$ref' and id_proceso='2'";
	  $resultci= mysql_query($sqlci);
	  $row_ci = mysql_fetch_assoc($resultci);
	  $numci= mysql_num_rows($resultci);
	  if($numci >='1')
	  { ?>
      <a href="produccion_caract_impresion_vista.php?id_ref=<?php echo $row_vista_egl['id_ref']; ?>"><img src="images/i.gif" style="cursor:hand;" alt="IMPRESION" title="IMPRESION" border="0" /></a>
      <?php } else{ ?>
      <a href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_vista_egl['id_ref']; ?>&cod_ref=<?php echo $row_vista_egl['cod_ref']; ?>"><img src="images/i_rojo.gif" style="cursor:hand;" alt="ADD FORMULA IMPRESION" title="ADD FORMULA IMPRESION" border="0" /></a>
      <?php } ?>
      <a href="produccion_caract_sellado_vista.php?id_ref=<?php echo $row_vista_egl['id_ref']; ?>"><img src="images/s.gif" style="cursor:hand;" alt="SELLADO" title="SELLADO" border="0" /></a>
    </td>
</tr>
<tr>
  <td colspan="5" nowrap="nowrap" id="fondo">REFERENCIA N&deg; <strong><?php echo $row_referenciaver['cod_ref']; ?>-<?php echo $row_referenciaver['version_ref']; ?></strong></td>
  </tr>
<tr>
  <td nowrap="nowrap" id="subppal2">REGISTRO INICIAL NOMBRE</td>
  <td colspan="3" nowrap="nowrap" id="subppal2">REGISTRO INICIAL FECHA</td>
  <td id="subppal2">ESTADO</td>
</tr>
<tr>
  <td nowrap="nowrap" id="fuente2"><?php echo $row_referenciaver['registro1_ref']; ?></td>
  <td colspan="3" nowrap="nowrap" id="fuente2"><?php echo $row_referenciaver['fecha_registro1_ref']; ?></td>
  <td id="fuente2"><?php switch($row_vista_egl['estado_egp']) {
		      case 2: echo "PENDIENTE"; break;
			  case 1: echo "ACTIVA"; break;
			  case 0: echo "INACTIVA"; break; } ?>
  </td>
</tr>
<tr>
  <td nowrap="nowrap" id="subppal2">ULTIMA MODIFICACION NOMBRE</td>
  <td colspan="3" nowrap="nowrap" id="subppal2">ULTIMA MODIFICACION FECHA</td>
  <td nowrap="nowrap" id="subppal2">COTIZACION - REFERENCIA</td>
  </tr>
<tr>
  <td nowrap="nowrap" id="fuente2"><?php echo $row_referenciaver['registro2_ref']; ?></td>
  <td colspan="3" nowrap="nowrap" id="fuente2"><?php echo $row_vista_egl['fecha_registro2_ref']; ?></td>
  <td id="fuente2"><?php echo $row_refs_clientes['N_cotizacion']; ?>-<strong><?php echo $row_referenciaver['cod_ref']; ?></strong></td>
</tr>
<tr>
  <td colspan="5" nowrap="nowrap" id="fondo6">Alguna Inquietud o Comentario : <strong>info@acycia.com</strong></td>
  </tr>
<tr>
  <td colspan="6" id="subppal2">ESPECIFICACION DEL MATERIAL</td>
  </tr>
<tr>
  <td colspan="6" id="fuente3"><strong>ESTRUCTURA: </strong><?php echo $row_vista_egl['tipo_ext_egp']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><strong>MATERIAL: </strong><?php echo $row_vista_egl['material_ref']; ?></td>
  <td colspan="2" id="fuente3"><strong>PIGMENTO EXTERIOR: </strong><?php echo $row_vista_egl['pigm_ext_egp']; ?></td>
  <td colspan="2" id="fuente3"><strong>PIGMENTO INTERIOR: </strong><?php echo $row_vista_egl['pigm_int_epg']; ?></td>
  </tr>
<tr>
  <td id="fuente3"><strong>ANCHO: </strong><?php echo $row_vista_egl['ancho_egp']; ?></td>
  <td width="195" id="fuente3"><strong>CALIBRE: </strong><?php echo $row_vista_egl['calibre_egp']; ?></td>
  <td width="223" colspan="3" id="fuente3"><strong>PESO / ML: </strong><?php echo $row_vista_egl['peso_millar_ref']; ?></td>
  <td id="fuente3"><strong>DIAMETRO: </strong><?php echo $row_vista_egl['N_diametro_max_l']; ?></td>
</tr>
<tr>
  <td id="fuente3"><strong>PRESENTACION: </strong><?php echo $row_vista_egl['Str_presentacion']; ?></td>
  <td id="fuente3"><strong>ANCHO ROLLO: </strong><?php echo $row_vista_egl['ancho_rollo']; ?></td>
  <td colspan="3" id="fuente3"><strong>TRATAMIENTO/CORONA: </strong><?php echo $row_vista_egl['Str_tratamiento']; ?></td>
  <td id="fuente3"><strong>CANTIDAD METROS: </strong><?php echo $row_vista_egl['N_cantidad_metros_r_l']; ?></td>
  </tr>
<tr>
  <td colspan="6" id="fuente3"><strong>OBSERVACIONES: </strong><?php echo $row_vista_egl['observacion5_egp']; ?></td>
  </tr>
<tr>
  <td colspan="6" id="subppal2">ESPECIFICACION DE LA IMPRESION</td>
  </tr>
<tr>
  <td rowspan="6" id="fuente3"><strong>N&ordm; REPETICIONES: </strong><?php echo $row_vista_egl['N_repeticion_l']; ?></td>
  <td rowspan="6" id="fuente3"><strong>RODILLO N&ordm;: </strong><?php echo $row_vista_egl['N_repeticion_l']; ?></td>
</tr>
<tr>
  <td colspan="2" id="fuente3">TIPO DE NUMERACION </td>
  <td colspan="2" id="fuente3"><strong>FORMATO &amp; CODIGO DE BARRAS</strong></td>
</tr>
<tr>
  <td id="fuente3"><strong>Solapa TR</strong></td>
  <td id="fuente3"><?php echo $row_vista_egl['tipo_solapatr_egp']; ?></td>
  <td colspan="2" id="fuente3"><?php echo $row_vista_egl['cb_solapatr_egp']; ?></td>
  </tr>
<tr>
  <td id="fuente3"><strong>Cinta</strong></td>
  <td id="fuente3"><?php echo $row_vista_egl['tipo_cinta_egp']; ?></td>
  <td colspan="2" id="fuente3"><?php echo $row_vista_egl['cb_cinta_egp']; ?></td>
  </tr>
<tr>
  <td id="fuente3"><strong>Principal</strong></td>
  <td id="fuente3"><?php echo $row_vista_egl['tipo_principal_egp']; ?></td>
  <td colspan="2" id="fuente3"><?php echo $row_vista_egl['cb_principal_egp']; ?></td>
  </tr>
<tr>
  <td id="fuente3"><strong>Inferior</strong></td>
  <td id="fuente3"><?php echo $row_vista_egl['tipo_inferior_egp']; ?></td>
  <td colspan="2" id="fuente3"><?php echo $row_vista_egl['cb_inferior_egp']; ?></td>
  </tr>
<tr>
  <td colspan="6" id="subppal2">IMPRESION</td>
</tr>
<tr>
  <td id="fondo2"><strong>TIPO DE EMBOBINADO</strong></td>
</tr>
<tr>

  <td rowspan="9" id="fuente2"><?php switch($row_referenciaver['N_embobinado_l']) {
	  case 0: echo "VACIO"; break;
	  case 1: ?>
    <img src="images/embobinado1.gif">
    <?php break;
	  case 2: ?>
    <img src="images/embobinado2.gif">
    <?php break;
	  case 3: ?>
    <img src="images/embobinado3.gif">
    <?php break;
	  case 4: ?>
    <img src="images/embobinado4.gif">
    <?php break;
	  case 5: ?>
    <img src="images/embobinado5.gif">
    <?php break;
	  case 6: ?>
    <img src="images/embobinado6.gif">
    <?php break;
	  case 7: ?>
    <img src="images/embobinado7.gif">
    <?php break;
	  case 8: ?>
    <img src="images/embobinado8.gif">
    <?php break;
	  case 9: ?>
    <img src="images/embobinado9.gif">
    <?php break;
	  case 10: ?>
    <img src="images/embobinado10.gif">
    <?php break;
	  case 11: ?>
    <img src="images/embobinado11.gif">
    <?php break;
	  case 12: ?>
    <img src="images/embobinado12.gif">
    <?php break;
	  case 13: ?>
    <img src="images/embobinado13.gif">
    <?php break;
	  case 14: ?>
    <img src="images/embobinado14.gif">
    <?php break;
	  case 15: ?>
    <img src="images/embobinado15.gif">
    <?php break;
	  case 16: ?>
    <img src="images/embobinado16.gif">
    <?php break;
	  } ?></td>
  </tr>
<tr>
  <td id="dato1">COLOR 1 : <?php echo $row_vista_egl['color1_egp']; ?></td>
  <td colspan="3" id="dato1">PANTONE : <?php if($row_vista_egl['pantone1_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_vista_egl['pantone1_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
  <td id="dato1">UBICACION : <?php echo $row_vista_egl['ubicacion1_egp']; ?></td>
</tr>
<tr>
  <td id="dato1">COLOR 2 : <?php echo $row_vista_egl['color2_egp']; ?></td>
  <td colspan="3" id="dato1">PANTONE : <?php if($row_vista_egl['pantone2_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_vista_egl['pantone2_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
  <td id="dato1">UBICACION : <?php echo $row_vista_egl['ubicacion2_egp']; ?></td>
</tr>
<tr>
  <td id="dato1">COLOR 3 : <?php echo $row_vista_egl['color3_egp']; ?></td>
  <td colspan="3" id="dato1">PANTONE : <?php if($row_vista_egl['pantone3_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_vista_egl['pantone3_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
  <td id="dato1">UBICACION : <?php echo $row_vista_egl['ubicacion3_egp']; ?></td>
</tr>
<tr>
  <td id="dato1">COLOR 4 : <?php echo $row_vista_egl['color4_egp']; ?></td>
  <td colspan="3" id="dato1">PANTONE : <?php if($row_vista_egl['pantone4_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_vista_egl['pantone4_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
  <td id="dato1">UBICACION : <?php echo $row_vista_egl['ubicacion4_egp']; ?></td>
</tr>
<tr>
  <td id="dato1">COLOR 5 : <?php echo $row_vista_egl['color5_egp']; ?></td>
  <td colspan="3" id="dato1">PANTONE : <?php if($row_vista_egl['pantone5_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_vista_egl['pantone5_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
  <td id="dato1">UBICACION : <?php echo $row_vista_egl['ubicacion5_egp']; ?></td>
</tr>
<tr>
  <td id="dato1">COLOR 6 : <?php echo $row_vista_egl['color6_egp']; ?></td>
  <td colspan="3" id="dato1">PANTONE : <?php if($row_vista_egl['pantone6_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_vista_egl['pantone6_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
  <td id="dato1">UBICACION : <?php echo $row_vista_egl['ubicacion6_egp']; ?></td>
</tr>
<tr>
  <td id="dato1">COLOR 7 : <?php echo $row_vista_egl['color7_egp']; ?></td>
  <td colspan="3" id="dato1">PANTONE : <?php if($row_vista_egl['pantone7_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_vista_egl['pantone7_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
  <td id="dato1">UBICACION : <?php echo $row_vista_egl['ubicacion7_egp']; ?></td>
</tr>
<tr>
  <td id="dato1">COLOR 8 : <?php echo $row_vista_egl['color8_egp']; ?></td>
  <td colspan="3" id="dato1">PANTONE : <?php if($row_vista_egl['pantone8_egp']){  $insumo1=$conexion->llenarCampos('insumo', "WHERE id_insumo=".$row_vista_egl['pantone8_egp'], "ORDER BY id_insumo DESC LIMIT 1"," descripcion_insumo" ); echo $insumo1['descripcion_insumo'];} ?></td>
  <td id="dato1">UBICACION : <?php echo $row_vista_egl['ubicacion8_egp']; ?></td>
</tr>
<tr>
  <td id="fondo3">&nbsp;</td>
  <td id="dato1">COMIENZA EN:</td>
  <td colspan="3" id="dato1"><?php echo $row_vista_egl['comienza_egp']; ?></td>
  <td id="dato1"><?php if($row_vista_egl['fecha_cad_egp'] == '1') { ?>
    <img src="images/palomita.gif">
    <?php } else { ?>
    <img src="images/vacio.gif">
    <?php } ?>
    <strong>FECHA CADUCIDAD</strong></td>
  </tr>  
<tr>
  <td colspan="6" id="fuente3">&nbsp;</td>
</tr>
  <tr>
  <td colspan="6" id="subppal2">ESPECIFICACION DEL ARTE</td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><?php if($row_vista_egl['arte_sum_egp'] == '1') { ?><img src="images/palomita.gif"><?php } else { ?> <img src="images/vacio.gif"><?php } ?><strong> Arte suministrado por el cliente</strong></td>
  <td colspan="4" id="fuente3"><strong>DISEÑADOR: </strong><?php echo $row_vista_egl['disenador_egp']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><?php if($row_vista_egl['ent_logo_egp'] == '1') { ?><img src="images/palomita.gif"><?php } else { ?> <img src="images/vacio.gif"><?php } ?><strong> El cliente entrega archivos para montaje</strong></td>
  <td colspan="4" id="fuente3"><strong>TELEFONO: </strong><?php echo $row_vista_egl['telef_disenador_egp']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><?php if($row_vista_egl['orient_arte_egp'] == '1') { ?><img src="images/palomita.gif"><?php } else { ?> <img src="images/vacio.gif"><?php } ?><strong> Solicita orientacion en el arte</strong></td>
  <td colspan="4" id="fuente3"><strong>FECHA DEL ARTE: </strong><?php echo $row_vista_egl['fecha_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="6" id="subppal2">CLIENTES ASIGNADOS A ESTA REFERENCIA </td>
    </tr>
  <tr>
    <td id="subppal2">CLIENTE</td>
    <td id="subppal2">DIRECCION</td>
    <td colspan="3" id="subppal2">PAIS / CIUDAD </td>
    <td id="subppal2">TELEFONO</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="fuente3"><?php echo $row_refs_clientes['nombre_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['direccion_c']; ?></td>
      <td colspan="3" id="fuente3"><?php echo $row_refs_clientes['pais_c']; ?> / <?php echo $row_refs_clientes['ciudad_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['telefono_c']; ?></td>
    </tr>
    <?php } while ($row_refs_clientes = mysql_fetch_assoc($refs_clientes)); ?>
<tr>
  <td colspan="6" id="fondo">&nbsp;</td>
</tr>
<tr>
  <td colspan="6" id="fuente3"><strong>OBSERVACIONES: </strong><?php echo $row_vista_egl['observacion5_egp']; ?></td>
</tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($vista_egl);

//mysql_free_result($colores);

//mysql_free_result($archivos);
?>
