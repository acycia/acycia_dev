<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?><?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "usuario.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
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

//ORDEN DE PRODUCCION
$colname_op= "-1";
if (isset($_GET['id_op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = sprintf("SELECT * FROM Tbl_orden_produccion WHERE id_op=%s AND b_borrado_op='0' ORDER BY id_op DESC",$colname_op);
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);

$_GET['int_cod_ref_op']= $row_orden_produccion['int_cod_ref_op'];
//precios o.c
$colname_precio= "-1";
if (isset($_GET['id_op'])) {
  $colname_precio = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_precio = sprintf("SELECT Tbl_items_ordenc.int_precio_io FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op=Tbl_items_ordenc.int_cod_ref_io AND Tbl_orden_produccion.int_cliente_op <> 73",$colname_precio);
$precio = mysql_query($query_precio, $conexion1) or die(mysql_error());
$row_precio = mysql_fetch_assoc($precio);
$totalRows_precio = mysql_num_rows($precio);
//CARGA CON O.P Y REF
$colname_ref= "-1";
if (isset($_GET['id_op'])) {
  $colname_ref = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_op = sprintf("SELECT * FROM Tbl_orden_produccion,Tbl_referencia,Tbl_egp WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp AND Tbl_referencia.estado_ref='1'",$colname_ref);
$ref_op = mysql_query($query_ref_op, $conexion1) or die(mysql_error());
$row_ref_op = mysql_fetch_assoc($ref_op);
$totalRows_ref_op = mysql_num_rows($ref_op);
 
//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 1
$id_ref=$row_orden_produccion['id_ref_op'];
 
$row_ref_verif = $conexion->llenarCampos("verificacion", "WHERE id_ref_verif='".$id_ref."' ", "ORDER BY version_ref_verif DESC", " userfile,estado_arte_verif ");
//LLAMA LAS TINTAS DE IMPRESION
 
$id_op = $row_orden_produccion['id_op']; 
$sqldesc ="SELECT id_rpp_rp,valor_prod_rp FROM Tbl_reg_kilo_producido WHERE op_rp ='$id_op' AND id_proceso_rkp='2' ORDER BY id_rkp ASC"; 
$resultdesc =mysql_query($sqldesc); 
$numdesc = mysql_num_rows($resultdesc); 
$row_desc = mysql_fetch_assoc($resultdesc);


$row_mezclaycaract_impresion = $conexion->llenarCampos("tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref"," WHERE cp.cod_ref= '".$_GET['int_cod_ref_op']."' AND cp.proceso=2", "","*"); 

$row_impresion=$conexion->llenarCampos("tbl_produccion_mezclas cp","WHERE cp.id_proceso=2 AND cp.int_cod_ref_pm= '".$_GET['int_cod_ref_op']."' ", "","*"); 
  

//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 1
 
 
$row_cualexiste=$conexion->llenarCampos("tbl_produccion_mezclas tmi "," WHERE tmi.id_proceso = 2 AND tmi.id_ref_pm= '".$id_ref."' ", "","id_ref_pm"); 
 
//CARGA UNIDAD 1
$colname_unidad_uno = "-1";
if (isset($id_ref)) {
  $colname_unidad_uno  = (get_magic_quotes_gpc()) ? $id_ref : addslashes($id_ref);
}
mysql_select_db($database_conexion1, $conexion1);
$query_caract_valor = "SELECT * FROM Tbl_caracteristicas, Tbl_caracteristicas_valor WHERE Tbl_caracteristicas_valor.id_ref_cv='$id_ref' AND Tbl_caracteristicas.id_c=Tbl_caracteristicas_valor.id_c_cv AND Tbl_caracteristicas.proceso_c='2' ORDER BY Tbl_caracteristicas_valor.id_cv ASC";
$caract_valor = mysql_query($query_caract_valor, $conexion1) or die(mysql_error());
$row_caract_valor = mysql_fetch_assoc($caract_valor);
$totalRows_caract_valor = mysql_num_rows($caract_valor);

mysql_select_db($database_conexion1, $conexion1);
$query_unidad_uno = sprintf("select * from insumo, Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='1' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_uno = mysql_query($query_unidad_uno, $conexion1) or die(mysql_error());
$row_unidad_uno = mysql_fetch_assoc($unidad_uno);
$totalRows_unidad_uno = mysql_num_rows($unidad_uno);
$row_uno = mysql_fetch_array($unidad_uno);
//CARGA UNIDAD 2
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_dos = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='2' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_dos = mysql_query($query_unidad_dos, $conexion1) or die(mysql_error());
$row_unidad_dos = mysql_fetch_assoc($unidad_dos);
$totalRows_unidad_dos = mysql_num_rows($unidad_dos);
//CARGA UNIDAD 3
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_tres = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='3' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_tres = mysql_query($query_unidad_tres, $conexion1) or die(mysql_error());
$row_unidad_tres = mysql_fetch_assoc($unidad_tres);
$totalRows_unidad_tres = mysql_num_rows($unidad_tres);
//CARGA UNIDAD 4
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cuatro = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='4' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_cuatro = mysql_query($query_unidad_cuatro, $conexion1) or die(mysql_error());
$row_unidad_cuatro = mysql_fetch_assoc($unidad_cuatro);
$totalRows_unidad_cuatro = mysql_num_rows($unidad_cuatro);
//CARGA UNIDAD 5
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cinco = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='5' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_cinco = mysql_query($query_unidad_cinco, $conexion1) or die(mysql_error());
$row_unidad_cinco = mysql_fetch_assoc($unidad_cinco);
$totalRows_unidad_cinco = mysql_num_rows($unidad_cinco);
//CARGA UNIDAD 6
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_seis = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='6' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_seis = mysql_query($query_unidad_seis, $conexion1) or die(mysql_error());
$row_unidad_seis = mysql_fetch_assoc($unidad_seis);
$totalRows_unidad_seis = mysql_num_rows($unidad_seis);
//CARGA UNIDAD 7
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_siete = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='7' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_siete = mysql_query($query_unidad_siete, $conexion1) or die(mysql_error());
$row_unidad_siete = mysql_fetch_assoc($unidad_siete);
$totalRows_unidad_siete = mysql_num_rows($unidad_siete);
//CARGA UNIDAD 8
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_ocho = sprintf("select * from insumo,Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='8' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m AND Tbl_produccion_mezclas_impresion.id_i_pmi=insumo.id_insumo",$colname_unidad_uno);
$unidad_ocho = mysql_query($query_unidad_ocho, $conexion1) or die(mysql_error());
$row_unidad_ocho = mysql_fetch_assoc($unidad_ocho);
$totalRows_unidad_ocho = mysql_num_rows($unidad_ocho);
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
<table id="tablainterna" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" rowspan="8" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="3" id="subtitulo2">ORDEN DE PRODUCCION N&deg;  <?php echo $row_orden_produccion['id_op']; ?></td>
    <td colspan="4" id="detalle3"><?php if($row_usuario['tipo_usuario']=='1' || $row_usuario['tipo_usuario']=='2') {?><a href="produccion_op_edit.php?id_op=<?php echo $_GET['id_op']; ?>"><img src="images/menos.gif" alt="EDITAR"title="EDITAR" border="0" /></a><a href="produccion_ordenes_produccion_listado.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO O.P"title="LISTADO O.P" border="0" /></a><?php  }?><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU subtitulo2"title="MENU subtitulo2" border="0"/></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="INPRIMIR"title="INPRIMIR" /> <a href="produccion_registro_extrusion_listado.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>"><img src="images/e.gif" style="cursor:hand;" alt="ADD EXTRUSION" title="ADD EXTRUSION" border="0" /></a></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle2">FECHA DE INGRESO O.P</td>
    <td  nowrap colspan="2" id="detalle2">FECHA DE ENTREGA O.P</td>
    <td colspan="3" id="detalle2">Responsable</td>
  </tr>
  <tr>
    <td colspan="2" id="detalle2"><?php echo $row_orden_produccion['fecha_registro_op']; ?></td>
    <td  nowrap colspan="2" id="detalle2"><?php echo $row_orden_produccion['fecha_entrega_op']; ?></td>
    <td colspan="3" id="detalle2"><?php echo $row_orden_produccion['str_responsable_op']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle2">FECHA DE INGRESO O.C</td>
    <td  nowrap colspan="2" id="detalle2">FECHA DE ENTREGA O.C</td>
    <td colspan="3" id="detalle2">ARTE</td>
    </tr>
  <tr>
    <td colspan="2" id="detalle2">
      <?php $numer_oc=$row_orden_produccion['str_numero_oc_op'];
			$sqloc="SELECT * FROM Tbl_orden_compra WHERE str_numero_oc='$numer_oc'"; 
			$resultoc=mysql_query($sqloc); 
			$numoc=mysql_num_rows($resultoc); 
			if($numoc >= '1') 
			{ 
      $nombre_oc=mysql_result($resultoc,0,'fecha_ingreso_oc'); 
       $fech_oc = $nombre_oc; echo $fech_oc ; 
       $nit_oc=mysql_result($resultoc,0,'str_nit_oc');  
      }
			?></td>
    <td colspan="2" id="detalle2"><?php 
	        $numer_io=$row_orden_produccion['str_numero_oc_op'];
			$ref_io=$row_orden_produccion['int_cod_ref_op'];
			$sqlio="SELECT * FROM Tbl_items_ordenc WHERE str_numero_io='$numer_io' AND int_cod_ref_io='$ref_io'"; 
			$resultio=mysql_query($sqlio); 
			$numio=mysql_num_rows($resultio); 
			if($numio >= '1') 
			{ $nombre_io=mysql_result($resultio,0,'fecha_entrega_io'); $fech_io = $nombre_io; echo $fech_io; }
			?>
            </td>
    <td colspan="3" id="detalle2">
      <a href="javascript:verFoto('archivo/<?php echo $row_ref_verif['userfile'];?>','610','490')"><?php echo $row_ref_verif['userfile']; ?></a> 
    </td>
    </tr>
  <tr>
    <td colspan="2" id="detalle2">CLIENTE</td>
    <td colspan="1" id="detalle2">O.C. N&deg;</td>
    <td id="detalle2">COTIZ. N&deg;</td>
    <td  id="detalle2">REF - VER</td>
    <td nowrap id="detalle2">PRECIO</td>
    <td nowrap id="detalle2">TIPO DE ENTREGA</td>
    </tr>
  <tr>
    <td colspan="2" nowrap id="detalle2"><?php $id_c=$row_orden_produccion['int_cliente_op'];
			$sqln="SELECT * FROM cliente WHERE id_c='$id_c'"; 
			$resultn=mysql_query($sqln); 
			$numn=mysql_num_rows($resultn); 
			if($numn >= '1') 
			{ $nombre_c=mysql_result($resultn,0,'nombre_c'); $ca = $nombre_c; echo $ca; }
			else { echo "Cliente !";	} ?>   
      </td>
    <td id="detalle2"><a href="javascript:verFoto('control_tablas.php?n_cotiz=<?php echo $row_orden_produccion['int_cotiz_op']; ?>&cod_ref=<?php echo $row_orden_produccion['int_cod_ref_op']; ?>&Str_nit=<?php echo $nit_oc; ?>&case=<?php echo "6"; ?>','850','850')"><em>O.C. <?php echo $numer_oc; ?></em></a> 
       </td>
    <td id="detalle2"><?php echo $row_orden_produccion['int_cotiz_op']; ?></td>
    <td id="detalle2"><a href="javascript:verFoto('referencia_bolsa_edit.php?id_ref=<?php echo $row_orden_produccion['id_ref_op']; ?>&n_egp=<?php echo $row_ref_op['n_egp_ref']; ?>','1100','850')"><em><?php echo $row_orden_produccion['int_cod_ref_op']; ?></em></a> - <?php echo $row_orden_produccion['version_ref_op']; ?></td>
    <td id="detalle2"><?php echo $row_precio['int_precio_io']; ?></td>
    <td id="detalle2"><?php echo $row_orden_produccion['str_entrega_op']; ?></td>
    </tr>
  <tr>
    <td colspan="8" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
    </tr>
      
      <tr>
        <td colspan="9" id="subtitulo2">CONDICIONES DE FABRICACI&Oacute;N EN EXTRUSI&Oacute;N </td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">DESPERDICIO</td>
        <td colspan="2" id="detalle1">UNIDADES SOLICITADAS</td>
        <td colspan="2" id="detalle1">TIPO DE BOLSA</td>
        <td id="detalle1">PESO MILLAR</td>
        <td colspan="2" id="detalle1">Prioridad</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['int_desperdicio_op']; ?>          %<strong></strong></td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['int_cantidad_op']; ?></td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['str_tipo_bolsa_op']; ?></td>
        <td id="detalle1"><?php echo $row_orden_produccion['int_pesom_op']; ?></td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['b_visual_op']; ?></td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">MATERIAL</td>
        <td colspan="2" id="detalle1">PRESENTACION</td>
        <td colspan="2" id="detalle1">CANT. KLS REQUERIDOS</td>
        <td id="detalle1"><?php echo $row_orden_produccion['int_kilos_op']; ?></td>
        <td colspan="2" id="detalle1">METROS LINEAL</td>
        </tr>
      <tr>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['str_matrial_op']; ?></td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['str_presentacion_op']; ?></td>
        <td colspan="2" id="detalle1">CALIBRE</td>
        <td id="detalle1"><?php echo $row_orden_produccion['int_calibre_op']; ?></td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['metroLineal_op']; ?></td>
        </tr>
      <tr>
        <td colspan="2" id="detalle1">PIGMENTO INTERNO</td>
        <td colspan="2" id="detalle1">PIGMENTO EXTERNO</td>
        <td colspan="2" id="detalle1">MICRAS</td>
        <td id="detalle1"><?php echo $row_orden_produccion['int_micras_op']; ?></td>
        <td colspan="2" nowrap="nowrap" id="detalle1">ANCHO DEL ROLLO</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['str_interno_op']; ?></td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['str_externo_op']; ?></td>
        <td colspan="2" id="detalle1">TRATAMIENTO CORONA</td>
        <td id="detalle1"><?php echo $row_orden_produccion['str_tratamiento_op']; ?></td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['int_ancho_rollo_op']; ?></td>
      </tr>      
      <tr >
        <td colspan="9" id="subppal2">Observacion en Extrusion</td>
        </tr>      
      <tr >
        <td colspan="9" id="detalle1"><?php echo $row_orden_produccion['observ_extru_op']; ?>&nbsp;</td>
        </tr>
      <tr >
        <td colspan="9" id="subtitulo2">CONDICIONES DE FABRICACI&Oacute;N EN IMPRESION</td>
        </tr>
        <tr>
        <td colspan="2" id="detalle1">IMPRIME EN MAQUINA:</td>
        <td  nowrap id="detalle1"><?php $id_m=$row_orden_produccion['maquina_imp_op'];
			$sqlm="SELECT * FROM maquina WHERE id_maquina='$id_m'"; 
			$resultm=mysql_query($sqlm); 
			$numm=mysql_num_rows($resultm); 
			if($numm >= '1') 
			{ $nombre_m=mysql_result($resultm,0,'nombre_maquina'); $cm = $nombre_m; echo $cm; }
			 ?></td>
        <td  nowrap id="detalle1">KLS MATERIAL REQUERIDO:</td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['kls_req_imp_op']; ?></td>
        <td nowrap id="detalle1">METROS APROXIMADOS:</td>
        <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['mts_req_imp_op']; ?></td>
      </tr>
      <?php if ($row_orden_produccion['str_tipo_bolsa_op']=="LAMINA") { ?>
        <tr>
          <td colspan="2" id="detalle1"><strong>UNIDADES X CAJA:</strong></td>
          <td  nowrap id="detalle1"><?php echo $row_orden_produccion['int_undxcaja_op']; ?></td>
          <td  nowrap id="detalle1"><strong>UNIDADES X PAQUETE:</strong></td>
          <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['int_undxpaq_op']; ?></td>
          <td nowrap id="detalle1">&nbsp;</td>
          <td colspan="2" id="detalle1">&nbsp;</td>
        </tr>
        <?php }?>
      <tr>	
        <td colspan="2" id="detalle1">MARGENES</td>
        <td  nowrap id="detalle1">Izq. mm</td>
        <td id="detalle1"><?php echo $row_orden_produccion['margen_izq_imp_op']; ?></td>
        <td id="detalle1">Rep. en Ancho</td>
        <td id="detalle1"><?php echo $row_orden_produccion['margen_anc_imp_op']; ?></td>
        <td id="detalle1">de</td>
        <td id="detalle1"><?php echo $row_orden_produccion['margen_anc_mm_imp_op']; ?></td>
        <td id="detalle1">mm</td>
      </tr>
      <tr>
        <td id="detalle1"><strong>Z</strong></td>
        <td id="detalle1"><?php echo $row_orden_produccion['margen_z_imp_op']; ?></td>
        <td nowrap id="detalle1">Der. mm</td>
        <td id="detalle1"><?php echo $row_orden_produccion['margen_der_imp_op']; ?></td>
        <td id="detalle1">Rep. Perimetro</td>
        <td id="detalle1"><?php echo $row_orden_produccion['margen_peri_imp_op']; ?></td>
        <td id="detalle1">de</td>
        <td id="detalle1"><?php echo $row_orden_produccion['margen_per_mm_imp_op']; ?></td>
        <td id="detalle1">mm</td>
      </tr>
      <tr>  
       
       
          
                               
                                     <?php if($row_cualexiste['id_ref_pm']=='') : ?>
                                     <tr id="tr1">
                                          <td colspan="9" id="fuente2"><strong>CARACTERISTICAS DE IMPRESION</strong></td>
                                      </tr>
                                      <tr>  
                                        <td colspan="11">
                                          <table>
                                            <tr> 
                                              <td valign="top">
                                              <div id="cajon1">
                                                <?php if($totalRows_unidad_uno!='0') { ?>                             
                                                <table>
                                                  <tr id="tr1">
                                                    <td nowrap id="detalle1"><strong>UNIDAD 1</strong></td>
                                                    <td nowrap id="detalle2">DESCRIPCION</td>
                                                    <td nowrap id="detalle1">VALOR</td>
                                                    </tr>
                                                  <?php  for ($y=0;$y<=5;$y++) { ?><!--for ($y=0;$y<=$totalRows_unidad_uno-1;$y++)// TRAE TODOS LOS REGISTROS--> 
                                                  <tr>
                                                    <td  nowrap id="talla1"><?php $id_m=mysql_result($unidad_uno,$y,str_nombre_m);echo $id_m; ?></td>
                                                    <td  id="talla1"><?php $var=mysql_result($unidad_uno,$y,descripcion_insumo); echo $var;?></td>           
                                                    <td id="talla1"><?php $var1=mysql_result($unidad_uno,$y,str_valor_pmi); echo $var1; ?></td>         
                                                    </tr>  <?php  } ?>                                        
                                                  </table> 
                                                <?php  } ?>       
                                                </div>
                                                <div id="cajon1">
                                                <?php if($totalRows_unidad_dos!='0') { ?>               
                                                <table>
                                                  <tr id="tr1">
                                                    <td nowrap id="detalle1"><strong>UNIDAD 2</strong></td>
                                                    <td nowrap id="detalle2">DESCRIPCION</td>
                                                    <td nowrap id="detalle1">VALOR</td>
                                                    </tr>
                                                  <?php  for ($x=0;$x<=5;$x++) { ?><tr>
                                                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_dos,$x,str_nombre_m);echo $id_m; ?></td>           
                                                    <td  id="talla1"><?php $var=mysql_result($unidad_dos,$x,descripcion_insumo); echo $var;?></td>           
                                                    <td id="talla1"><?php $var1=mysql_result($unidad_dos,$x,str_valor_pmi); echo $var1; ?></td>         
                                                    </tr>  <?php  } ?>                                        
                                                  </table>
                                                <?php  } ?>       
                                               </div>
                                               <div id="cajon1">
                                              <?php if($totalRows_unidad_tres!='0') { ?>
                                                <table>
                                                  <tr id="tr1">
                                                    <td nowrap id="detalle1"><strong>UNIDAD 3</strong></td>
                                                    <td nowrap id="detalle2">DESCRIPCION</td>
                                                    <td nowrap id="detalle1">VALOR</td>
                                                    </tr>
                                                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                    
                                                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_tres,$y,str_nombre_m);echo $id_m; ?></td>
                                                    <td id="talla1"><?php $var=mysql_result($unidad_tres,$y,descripcion_insumo); echo $var;?></td>
                                                    <td id="talla1"><?php $var1=mysql_result($unidad_tres,$y,str_valor_pmi); echo $var1; ?></td>
                                                    </tr>
                                                  <?php  } ?>
                                                  </table>
                                                <?php  } ?> 
                                        </div>
                                               <div id="cajon1">
                                        <?php if($totalRows_unidad_cuatro!='0') { ?>
                                                <table>
                                                  <tr id="tr1">
                                                    <td nowrap id="detalle1"><strong>UNIDAD 4</strong></td>
                                                    <td nowrap id="talla1">DESCRIPCION</td>
                                                    <td nowrap id="detalle1">VALOR</td>
                                                    </tr>
                                                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                    
                                                    <td nowrap  id="talla1"><?php $id_m=mysql_result($unidad_cuatro,$y,str_nombre_m);echo $id_m; ?></td>
                                                    <td   id="detalle1"><?php $var=mysql_result($unidad_cuatro,$y,descripcion_insumo); echo $var; ?></td>
                                                    <td id="talla1"><?php $var=mysql_result($unidad_cuatro,$y,str_valor_pmi); echo $var; ?></td>
                                                    </tr>
                                                  <?php  } ?>
                                                  </table>
                                                <?php  } ?> 
                                                </div>
                                               <div id="cajon1">
                                               <?php if($totalRows_unidad_cinco!='0') { ?>
                                                <table>
                                                  <tr id="tr1">
                                                    <td nowrap id="detalle1"><strong>UNIDAD 5</strong></td>
                                                    <td nowrap id="detalle2">DESCRIPCION</td>
                                                    <td nowrap id="detalle1">VALOR</td>
                                                    </tr>
                                                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                    
                                                    <td  nowrap id="talla1"><?php $id_m=mysql_result($unidad_cinco,$y,str_nombre_m);echo $id_m; ?></td>
                                                    <td   id="talla1"><?php $var=mysql_result($unidad_cinco,$y,descripcion_insumo); echo $var;?></td>
                                                    <td id="talla1"><?php $var=mysql_result($unidad_cinco,$y,str_valor_pmi); echo $var; ?></td>
                                                    </tr>
                                                  <?php  } ?>
                                                  </table>
                                                <?php  } ?>
                                                </div>
                                               <div id="cajon1"> 
                                       <?php if($totalRows_unidad_seis!='0') { ?>
                                                <table>
                                                  <tr id="tr1">
                                                    <td nowrap id="detalle1"><strong>UNIDAD 6</strong></td>
                                                    <td nowrap id="detalle2">DESCRIPCION</td>
                                                    <td nowrap id="detalle1">VALOR</td>
                                                    </tr>
                                                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                    
                                                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_seis,$y,str_nombre_m);echo $id_m; ?></td>
                                                    <td  id="talla1"><?php $var=mysql_result($unidad_seis,$y,descripcion_insumo); echo $var;?></td>
                                                    <td id="talla1"><?php $var=mysql_result($unidad_seis,$y,str_valor_pmi); echo $var; ?></td>
                                                    </tr>
                                                  <?php  } ?>
                                                  </table>
                                                <?php  } ?>
                                                </div>
                                               <div id="cajon1"> 
                                       <?php if($totalRows_unidad_siete!='0') { ?>
                                                <table>
                                                  <tr id="tr1">
                                                    <td nowrap id="detalle1"><strong>UNIDAD 7</strong></td>
                                                    <td nowrap id="detalle2">DESCRIPCION</td>
                                                    <td nowrap id="detalle1">VALOR</td>
                                                    </tr>
                                                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                    
                                                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_siete,$y,str_nombre_m);echo $id_m; ?></td>
                                                    <td  id="talla1"><?php $var=mysql_result($unidad_siete,$y,descripcion_insumo); echo $var;?></td>
                                                    <td id="talla1"><?php $var=mysql_result($unidad_siete,$y,str_valor_pmi); echo $var; ?></td>
                                                    </tr>
                                                  <?php  } ?>
                                                  </table>
                                                <?php  } ?>
                                                </div>
                                               <div id="cajon1">
                                                <?php if($totalRows_unidad_ocho!='0') { ?>
                                                <table>
                                                  <tr id="tr1">
                                                    <td nowrap id="detalle1"><strong>UNIDAD 8</strong></td>
                                                    <td nowrap id="detalle2">DESCRIPCION</td>
                                                    <td nowrap id="detalle1">VALOR</td>
                                                    </tr>
                                                  <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                    
                                                    <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_ocho,$y,str_nombre_m);echo $id_m;?></td>
                                                    <td  id="talla1"><?php $var=mysql_result($unidad_ocho,$y,descripcion_insumo); echo $var; ?></td>
                                                    <td id="talla1"><?php $var=mysql_result($unidad_ocho,$y,str_valor_pmi); echo $var; ?></td>
                                                    </tr>
                                                  <?php  } ?>
                                                  </table>
                                                <?php  } ?>
                                                </div>
                                               </td>
                                              </tr>
                                            </table> 
                                          
                                          </td>
                                      </tr>
                                         <!-- INICIA CARACTERISTICAS -->
                                        <tr id="tr1">
                                          <td colspan="100%" id="fuente2"><strong>CARACTERISTICAS</strong> </td>
                                        </tr> 
                                          <tr >
                                              <?php  for ($x=0;$x<=$totalRows_caract_valor-1;$x++) { ?>          
                                                   <td width="130" id="fuente3"><?php $id_cv=mysql_result($caract_valor,$x,id_cv); $var=mysql_result($caract_valor,$x,str_nombre_caract_c);if($var!=''){echo $var;}else{echo "MP eliminada";} ?>                                             
                                                  <?php $valor=mysql_result($caract_valor,$x,str_valor_cv); echo $valor;?>
                                                   </td>
                                                  <?php  } ?>
                                               <td id="fuente3">&nbsp;</td>
                                          </tr>  

                                     <?php else: ?> 


                                          <td colspan="18" >
                                              <table style="width: 100%">
                                                    <tr>
                                                     <td colspan="18" id="detalle1"> 
                                                      Impresora : <?php echo $row_impresion['extrusora_mp'];?>
                                                    </td>
                                                   </tr>
                                                  <tr id="tr1">
                                                    <td rowspan="2" id="detalle1"> </td>
                                                    <td nowrap="nowrap" colspan="2" id="detalle1"><b>UNIDAD 1</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="detalle1"><b>UNIDAD 2</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="detalle1"><b>UNIDAD 3</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="detalle1"><b>UNIDAD 4</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="detalle1"><b>UNIDAD 5</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="detalle1"><b>UNIDAD 6</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="detalle1"><b>UNIDAD 7</b> </td>
                                                    <td nowrap="nowrap" colspan="2" id="detalle1"><b>UNIDAD 8</b> </td> 
                                                   </tr> 
                                                   <tr>
                                                      <td></td>
                                                   </tr> 
                                                  <tr id="tr1">
                                                   <td id="detalle1"><b>COLORES</b></td>
                                                    <td  id="detalle1">
                                                        <?php $idinsumo=$row_impresion['int_ref1_tol1_pm']; ?> 
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>  
                                                    </td>
                                                   <td id="detalle1">
                                                       <?php echo $row_impresion['int_ref1_tol1_porc1_pm']; ?> 
                                                    </td>
                                                   <td id="detalle1">
                                                       <?php $idinsumo =  $row_impresion['int_ref3_tol3_pm']; ?>
                                                       <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                   <td id="detalle1">
                                                         <?php echo $row_impresion['int_ref3_tol3_porc3_pm']; ?>
                                                   </td>
                                                   <td id="detalle1">
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_1']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                   </td>
                                                   <td id="detalle1">
                                                         <?php echo $row_mezclaycaract_impresion['campo_2']; ?>
                                                   </td>
                                                   <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_3']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                   </td>
                                                   <td id="detalle1">
                                                         <?php echo $row_mezclaycaract_impresion['campo_4']; ?>
                                                   </td>

                                                        
                                                   <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_5']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_6']; ?>
                                                        </td>
                                                        <td id="detalle1"> 
                                                             <?php $idinsumo = $row_mezclaycaract_impresion['campo_7']; ?>
                                                             <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_8']; ?>
                                                        </td>
                                                        <td id="detalle1"> 
                                                             <?php $idinsumo = $row_mezclaycaract_impresion['campo_9']; ?>
                                                             <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_10']; ?>
                                                        </td>
                                                        <td id="detalle1"> 
                                                             <?php $idinsumo = $row_mezclaycaract_impresion['campo_11']; ?>
                                                             <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_12']; ?>
                                                         </td>  

                                                  </tr>

                                                  <tr>
                                                    <td id="detalle1"><b>MEZCLAS</b></td>
                                                    <td id="detalle1"> 
                                                      <?php $idinsumo=$row_impresion['int_ref1_tol2_pm'];
                                                            $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                          $resultm=mysql_query($sqlm); 
                                                          $numm=mysql_num_rows($resultm); 
                                                          if($numm >= '1') 
                                                          { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                          else { echo ""; 
                                                          } 
                                                      ?> 
                                                  </td>
                                                    <td id="detalle1">
                                                      <?php echo $row_impresion['int_ref1_tol2_porc1_pm']; ?>
                                                    </td> 
                                                     <td id="detalle1"> 
                                                           <?php $idinsumo = $row_impresion['int_ref3_tol4_pm']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                    <td id="detalle1">
                                                        <?php echo $row_impresion['int_ref3_tol4_porc3_pm']; ?>
                                                      </td>

                                                     <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_13']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                     <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_14']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_15']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_16']; ?>
                                                        </td>
                                                     
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_17']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_18']; ?>
                                                        </td>
                                                       <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_19']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                       <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_20']; ?>
                                                        </td>
                                                       <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_21']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                        <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_22']; ?>
                                                        </td>
                                                         <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_23']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                       <td id="detalle1">
                                                          <?php echo $row_mezclaycaract_impresion['campo_24']; ?>
                                                        </td>

                                                    </tr>
                                            
                                                  <tr id="tr1">
                                                    <td id="detalle1"></td>
                                                    <td id="detalle1"> 
                                                      <?php $idinsumo=$row_impresion['int_ref1_tol3_pm'];
                                                            $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                          $resultm=mysql_query($sqlm); 
                                                          $numm=mysql_num_rows($resultm); 
                                                          if($numm >= '1') 
                                                          { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                          else { echo ""; 
                                                          } 
                                                      ?> 
                                                  </td>
                                                    <td id="detalle1">
                                                      <?php echo $row_impresion['int_ref1_tol3_porc1_pm']; ?>
                                                    </td>
                                                   <td id="detalle1"> 
                                                       <?php $idinsumo = $row_mezclaycaract_impresion['campo_25']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                   <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_26']; ?>
                                                     </td>
                                                   <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_27']; ?>
                       
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                   <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_28']; ?>
                                                     </td>
                                                   <td id="detalle1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_29']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                    <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_30']; ?>
                                                     </td>
                                                   
                                                   <td id="detalle1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_31']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_32']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_33']; ?>
                       
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_34']; ?>
                                                        </td>
                                                     <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_35']; ?>

                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_36']; ?>
                                                     </td>
                                                      <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_37']; ?>

                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_38']; ?>
                                                      </td> 

                                                  </tr>

                                                  <tr>
                                                    <td id="detalle1"></td>
                                                    <td id="detalle1">
                                                    <?php $idinsumo=$row_impresion['int_ref1_tol4_pm'];
                                                            $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                          $resultm=mysql_query($sqlm); 
                                                          $numm=mysql_num_rows($resultm); 
                                                          if($numm >= '1') 
                                                          { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                          else { echo ""; 
                                                          } 
                                                      ?> 
                                                     </td>
                                                    <td id="detalle1">
                                                      <?php echo $row_impresion['int_ref1_tol4_porc1_pm']; ?>
                                                    </td>
                                                   <td id="detalle1"> 
                                                       <?php $idinsumo = $row_mezclaycaract_impresion['campo_39']; ?>
                                                       <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                    <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_40']; ?>
                                                     </td>
                                                     <td id="detalle1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_41']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                     </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_42']; ?>
                                                       </td>
                                                     <td id="detalle1"> 
                                                       <?php $idinsumo = $row_mezclaycaract_impresion['campo_43']; ?>
                                                       <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_44']; ?>
                                                      </td>
                                                     
                                                     <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_45']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="detalle1">
                                                         <?php echo $row_mezclaycaract_impresion['campo_46']; ?>
                                                       </td>
                                                      <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_47']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                     </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_48']; ?>
                                                       </td>
                                                      <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_49']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_50']; ?>
                                                       </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_51']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_52']; ?>
                                                       </td>   
                                                   
                                                 </tr> 

                                                  <tr>
                                                    <td id="detalle1"></td>
                                                      <td id="detalle1">
                                                        <?php $idinsumo=$row_impresion['int_ref2_tol1_pm'];
                                                            $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                          $resultm=mysql_query($sqlm); 
                                                          $numm=mysql_num_rows($resultm); 
                                                          if($numm >= '1') 
                                                          { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                          else { echo ""; 
                                                          } 
                                                      ?> 
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_impresion['int_ref2_tol1_porc2_pm']; ?>
                                                      </td>
                                                    <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_53']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_54']; ?>
                                                      </td>
                                                    <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_55']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_56']; ?>
                                                      </td>
                                                     <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_57']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_58']; ?>
                                                      </td>
                                                     
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_59']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_60']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_61']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_62']; ?>
                                                       </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_63']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_64']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_65']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_66']; ?>
                                                       </td>
                                                         
                                                  </tr>
                                                  <tr id="tr1">
                                                    <td id="detalle1"><b>ALCOHOL</b></td>
                                                      <td id="detalle1">
                                                          <?php $idinsumo=$row_impresion['int_ref2_tol2_pm'];
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                      </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_impresion['int_ref2_tol2_porc2_pm']; ?>
                                                      </td>
                                                    <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_67']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_68']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_69']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_70']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_71']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_72']; ?>
                                                      </td>
                                                   
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_73']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_74']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_75']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_76']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_77']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_78']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_79']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_80']; ?>
                                                      </td> 
                                                         
                                                  </tr>

                                                  <tr>
                                                    <td id="detalle1"><b>ACETATO</b> NPA</td>
                                                      <td id="detalle1">
                                                          <?php $idinsumo=$row_impresion['int_ref2_tol3_pm'];
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="detalle1"> 
                                                      <?php echo $row_impresion['int_ref2_tol3_porc2_pm']; ?>
                                                      </td>
                                                    <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_81']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_82']; ?>
                                                        </td> 
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_83']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_84']; ?>
                                                        </td> 
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_85']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_86']; ?>
                                                        </td> 
                                                   
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_87']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_88']; ?>
                                                        </td> 
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_89']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_90']; ?>
                                                        </td> 
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_91']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_92']; ?>
                                                        </td> 
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_93']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_94']; ?>
                                                      </td> 
                                                       
                                                  </tr> 

                                                  <tr id="tr1">
                                                    <td id="detalle1"><b>METOXIPROPANOL</b></td>
                                                      <td id="detalle1">
                                                          <?php $idinsumo=$row_impresion['int_ref2_tol4_pm'];
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_impresion['int_ref2_tol4_porc2_pm']; ?>
                                                      </td>
                                                    <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_95']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_96']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_97']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_98']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_99']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_100']; ?>
                                                        </td>
                                                    
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_101']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_102']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_103']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_104']; ?>
                                                        </td>
                                                      <td id="detalle1"> 
                                                           <?php $idinsumo = $row_mezclaycaract_impresion['campo_105']; ?>
                                                           <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_106']; ?>
                                                     </td>
                                                     <td id="detalle1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_107']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_108']; ?>
                                                      </td>

                                                  </tr>

                                                  <tr>
                                                    <td id="detalle1"><b>VISCOSIDAD</b></td>
                                                    <td colspan="2" id="detalle1"> 
                                                      <?php echo $row_mezclaycaract_impresion['int_ref1_rpm_pm']; ?>
                                                    </td> 
                                                    <td colspan="2" id="detalle1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref1_tol5_porc1_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="detalle1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref2_rpm_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="detalle1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref2_tol5_porc2_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="detalle1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref3_rpm_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="detalle1">
                                                      <?php echo $row_mezclaycaract_impresion['int_ref3_tol5_porc3_pm']; ?>
                                                    </td>
                                                    <td colspan="2" id="detalle1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_137']; ?>
                                                    </td>
                                                    <td colspan="2" id="detalle1">
                                                      <?php echo $row_mezclaycaract_impresion['campo_138']; ?>
                                                    </td>
                                                  </tr>

                                                  <tr id="tr1">
                                                    <td id="detalle1"><b>ANILOX</b></td>
                                                      <td id="detalle1"> 
                                                            <?php $idinsumo=$row_impresion['int_ref3_tol1_pm'];
                                                                $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                              $resultm=mysql_query($sqlm); 
                                                              $numm=mysql_num_rows($resultm); 
                                                              if($numm >= '1') 
                                                              { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                              else { echo ""; 
                                                              } 
                                                          ?> 
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_impresion['int_ref3_tol1_porc3_pm']; ?>
                                                      </td>
                                                    <td id="detalle1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_109']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_110']; ?>
                                                     </td>
                                                    <td id="detalle1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_111']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_112']; ?>
                                                     </td>
                                                    <td id="detalle1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_113']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                    <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_114']; ?>
                                                     </td> 

                                                    <td id="detalle1">
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_115']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_116']; ?>
                                                      </td>
                                                      <td id="detalle1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_117']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_118']; ?>
                                                        </td>
                                                      <td id="detalle1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_119']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_120']; ?>
                                                        </td>
                                                      <td id="detalle1">
                                                          <?php $idinsumo = $row_mezclaycaract_impresion['campo_121']; ?>
                                                          <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_122']; ?>
                                                      </td>

                                                  </tr>

                                                  <tr>
                                                    <td id="detalle1"><b>BCM</b></td>
                                                      <td id="detalle1">
                                                          <?php $idinsumo=$row_impresion['int_ref3_tol2_pm'];
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?> 
                                                    </td>
                                                      <td  id="detalle1">
                                                        <?php echo $row_impresion['int_ref3_tol2_porc3_pm']; ?>
                                                      </td>
                                                    <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_123']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                    <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_124']; ?>
                                                     </td>
                                                    <td id="detalle1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_125']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                    </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_126']; ?>
                                                      </td>
                                                    <td id="detalle1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_127']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                      </td>
                                                      <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_128']; ?>
                                                     </td>
                                                      
                                                     <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_129']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_130']; ?>
                                                     </td>
                                                     <td id="detalle1"> 
                                                         <?php $idinsumo = $row_mezclaycaract_impresion['campo_131']; ?>
                                                         <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_132']; ?>
                                                     </td>
                                                     <td id="detalle1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_133']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_134']; ?>
                                                     </td>
                                                     <td id="detalle1"> 
                                                        <?php $idinsumo = $row_mezclaycaract_impresion['campo_135']; ?>
                                                        <?php 
                                                              $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
                                                            $resultm=mysql_query($sqlm); 
                                                            $numm=mysql_num_rows($resultm); 
                                                            if($numm >= '1') 
                                                            { $nombreInsumo=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreInsumo; }
                                                            else { echo ""; 
                                                            } 
                                                        ?>
                                                     </td>
                                                     <td id="detalle1">
                                                        <?php echo $row_mezclaycaract_impresion['campo_136']; ?>
                                                     </td>

                                                  </tr> 

                                                  <tr id="tr1">
                                                    <td colspan="18" id="detalle1">
                                                       Observacion: <?php echo $row_impresion['observ_pm']; ?> 
                                                    </td>
                                                  </tr> 

                                           
                                                         <!-- INICIA CARACTERISTICAS -->
                                                         <tr id="tr1">
                                                           <td colspan="100%" id="fuente2"><strong>CARACTERISTICAS</strong> </td>
                                                         </tr>  
                                                               <tr>
                                                                 <td colspan="2" id="talla1">Cantidad de Unidades</td>
                                                                 <td  nowrap="nowrap" id="talla1">Temp Secado Grados C</td>
                                                                 <td colspan="3" id="talla1">Repeticion de Ancho</td>
                                                                 <td colspan="3" id="talla1">Rep. Perimetro</td>
                                                                 <td colspan="3" id="talla1">Arte Aprobado (0 SI, 1 NO)</td>
                                                                 <td colspan="2" id="talla1">Z</td>
                                                                 <td colspan="2" id="talla1">Guia Fotocelda (0 SI, 1 NO)</td>
                                                                 <td colspan="2" id="talla1">Velocidad Maquina</td>
                                                               </tr>
                                                               <tr>
                                                                 <td colspan="2" id="talla1">
                                                                   <?php echo $row_mezclaycaract_impresion['campo_139']; ?>
                                                                 </td>
                                                                 <td id="talla1">
                                                                   <?php echo $row_mezclaycaract_impresion['campo_140']; ?>
                                                                 </td>
                                                                 <td colspan="3" id="talla1">
                                                                   <?php echo $row_mezclaycaract_impresion['campo_141']; ?>
                                                                 </td>
                                                                 <td colspan="3" id="talla1">
                                                                   <?php echo $row_mezclaycaract_impresion['campo_142']; ?>
                                                                 </td>
                                                                 <td colspan="3" id="talla1">
                                                                   <?php echo $row_mezclaycaract_impresion['campo_143']; ?>
                                                                 </td>
                                                                 <td colspan="2" id="talla1">
                                                                   <?php echo $row_mezclaycaract_impresion['campo_144']; ?>
                                                                 </td>
                                                                 <td colspan="2" id="talla1">
                                                                   <?php echo $row_mezclaycaract_impresion['campo_145']; ?>
                                                                 </td>
                                                                 <td colspan="2" id="talla1">
                                                                   <?php echo $row_mezclaycaract_impresion['campo_146']; ?>
                                                                 </td>
                                                               </tr>
                                                         </table>

                                                       </td>
                                                     </tr> 

                                           <?php endif;?> 

       
                           <!--  INICIA MEZCLAS DE EXTRUDER -->
          
          </td>
      </tr>
      <?php if($row_desc['id_rpp_rp']!=''){?>
      <tr>
        <td colspan="9" id="subtitulo2">TINTAS REGISTRADAS</td>
      </tr>
       <tr>
           <td colspan="5" id="detalle1"><strong>INSUMO</strong></td>
         <td colspan="4" id="detalle1"><strong>CONSUMO</strong></td>
    </tr>
      <?php 
            do{ ?>
           <tr>
           <td colspan="5" id="detalle1"> 
		   <?php  
        $id_rpp = $row_desc['id_rpp_rp'];
			  $sqlexh="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$id_rpp'"; 
			  $resultexh=mysql_query($sqlexh); 
			  $numexh=mysql_num_rows($resultexh); 
			  if($numexh >= '1') 
			  { 
          echo $descrip=mysql_result($resultexh,0,'descripcion_insumo');
 			  }		   
 		   ?> </td> 
           <td colspan="4" id="detalle1"> <?php  echo $row_desc['valor_prod_rp'];?> </td> 
 
          </tr>
          <?php 
          } while ($row_desc = mysql_fetch_assoc($resultdesc));
           ?>
       <tr>
       <?php }?>
        <td colspan="9" id="subppal2">Observacion en Impresion</td>
        </tr>      
      <tr>
        <td colspan="9" id="detalle1"><?php echo $row_orden_produccion['observ_impre_op']; ?>&nbsp;</td>
        </tr> 
         <!--SELLADO NO APARECE SI ES LAMINA-->
        <?php //if ((strcmp("LAMINA", $row_orden_produccion['str_tipo_bolsa_op']))) { ?>
         <tr>
        <td colspan="9" id="subtitulo2">CONDICIONES DE FABRICACI&Oacute;N EN SELLADO</td>
        </tr>        
        
      <tr>
        <td colspan="9">
        <table width="100%">
  <tr>
    <td id="detalle1"><strong>ANCHO</strong></td>
    <td id="detalle1"><strong>LARGO</strong></td>
    <td id="detalle1"><strong>SOLAPA</strong></td>
    <td id="detalle1">&nbsp;</td>
    <td id="detalle1"><strong>BOLSILLO PORTAGUIA</strong></td>
    <td id="detalle1"><strong>ADHESIVO</strong></td>
    <td id="detalle1"><strong>TIPO ADHESIVO</strong></td>
    <td id="detalle1">&nbsp;</td>
  </tr>
  <tr>
    <td id="detalle1"><?php echo $row_ref_op['ancho_ref']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['largo_ref']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['solapa_ref']; ?>      <?php if ($row_ref_op['b_solapa_caract_ref']==2) {echo "Sencilla";}else if ($row_ref_op['b_solapa_caract_ref']==1){echo "Doble";}else {echo "";} ?></td>
    <td id="detalle1">&nbsp;</td>
    <td id="detalle1"><?php echo $row_ref_op['bolsillo_guia_ref']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['adhesivo_ref']; ?></td>
    <td id="detalle1"><?php  
		  $tipoadh=$row_ref_op['tipoCinta_ref'];
		  $sqladhesivo="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$tipoadh'"; 
		  $resultadhesivo=mysql_query($sqladhesivo); 
		  $numadhesivo=mysql_num_rows($resultadhesivo); 
		  if($numadhesivo >= '1'){
	      echo $tipoBols=mysql_result($resultadhesivo,0,'descripcion_insumo');
			}?></td>
    <td id="detalle1">&nbsp;</td>
  </tr>
  <tr>
    <td id="detalle1"><strong>CALIBRE</strong></td>
    <td id="detalle1"><strong>PESO MILLAR</strong></td>
    <td id="detalle1"><strong>TIPO DE BOLSA </strong></td>
    <td id="detalle1">&nbsp;</td>
    <td id="detalle1"><strong>FUELLE</strong></td>
    <td id="detalle1">&nbsp;</td>
    <td colspan="2" id="detalle1"><strong>Tipo /Lamina Bolsillo</strong></td>
  </tr>
  <tr>
    <td id="detalle1"><?php echo $row_ref_op['calibre_ref']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['peso_millar_ref']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['tipo_bolsa_ref']; ?></td>
    <td id="detalle1">&nbsp;</td>
    <td id="detalle1"><?php echo $row_ref_op['N_fuelle']; ?></td>
    <td id="detalle1">&nbsp;</td>
    <td colspan="2" id="detalle1"><?php
	      $tipolam=$row_ref_op['tipoLamina_ref'];
		  $sqlinsumos="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$tipolam'"; 
		  $resultinsumos=mysql_query($sqlinsumos); 
		  $numinsumos=mysql_num_rows($resultinsumos); 
		  if($numinsumos >= '1'){
			echo  $tipoBols=mysql_result($resultinsumos,0,'descripcion_insumo');
			  }
      ?></td>
  </tr>
  <tr>
    <td rowspan="2" id="detalle1"><strong>PRESENTACION</strong></td>
    <td rowspan="2" id="detalle1"><strong>TRATAMIENTO CORONA</strong></td>
    <td colspan="6" id="detalle1"><strong>Bolsillo Portaguia</strong></td>
    </tr>
  <tr>
    <td id="detalle1"> <strong>(Ubicacion)</strong></td>
    <td id="detalle1">&nbsp;</td>
    <td id="detalle1"><strong>Calibre Bols.</strong></td>
    <td id="detalle1"><strong>(Forma)</strong></td>
    <td id="detalle1"><strong>(Lamina 1)</strong></td>
    <td id="detalle1"><strong>(Lamina 2)</strong></td>
  </tr>
  <tr>
    <td id="detalle1"><?php echo $row_ref_op['Str_presentacion']; ?>&nbsp;</td>
    <td id="detalle1"><?php echo $row_ref_op['Str_tratamiento']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['str_bols_ub_ref']; ?></td>
    <td id="detalle1">&nbsp;</td>
    <td id="detalle1"><?php echo $row_ref_op['calibreBols_ref']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['str_bols_fo_ref']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['bol_lamina_1_ref']; ?></td>
    <td id="detalle1"><?php echo $row_ref_op['bol_lamina_2_ref']; ?></td>
  </tr>
  <tr>
    <td id="detalle1"><strong>TIPO DE SELLO:</strong></td>
    <td id="detalle1"><strong>UNIDADES X CAJA:</strong></td>
    <td id="detalle1"><strong>UNIDADES X</strong></td>
    <td id="detalle1"><strong>UNID. X PAQ. REAL</strong></td>
    <td id="detalle1"><strong>MEDIDA DE LA CAJA</strong></td>
    <td id="detalle1"><strong>PRECORTE (Bolsillo Portaguia):</strong></td>
    <td id="detalle1" ><strong>Lote</strong></td>
    <td id="detalle1" nowrap="nowrap"><strong>Numeracion Actual Sellado:</strong></td>
    </tr>
  <tr> 
    <td id="detalle1"><?php echo $row_ref_op['tipo_sello_egp']; ?></td>
    <td id="detalle1"><?php echo $row_orden_produccion['int_undxcaja_op']; ?></td>
    <td id="detalle1"><?php echo $row_orden_produccion['int_undxpaq_op']; ?></td>
    <td id="detalle1"><?php echo $row_orden_produccion['undxpaqreal']; ?></td>
    <td id="detalle1"><?php $medicaja=$row_ref_op['marca_cajas_egp']; 
		    $sqlCAJ="SELECT descripcion_insumo FROM insumo WHERE id_insumo ='$medicaja'"; 
			  $resultCAJ=mysql_query($sqlCAJ); 
			  $numCAJ=mysql_num_rows($resultCAJ); 
			if($numCAJ >= '1') 
			   { 
          $nombre_CAJ=mysql_result($resultCAJ,0,'descripcion_insumo'); echo $nombre_CAJ ;
         }
	    ?>
     </td> 
    <td id="detalle1"><?php if($row_ref_op['B_troque']=='1') {echo "SI";}else{echo "NO";}; ?></td>
    <td id="detalle1"><strong> <?php echo $row_ref_op['lote']; ?> </strong></td>
    <td id="detalle1"><strong><?php echo $row_orden_produccion['numInicio_op']; ?><?php echo $row_orden_produccion['charfin']; ?></strong>
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" id="form1" onsubmit="">
          <input name="numInicio_op" type="hidden" id="numInicio_op" style="width:100px" readonly="readonly" min="0" step="1" value="<?php echo $row_orden_produccion['numInicio_op']; ?>" onChange="conMayusculas(this) ;" size="5" />
      </form>
    </td>
    
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>POSICION</strong></td>
    <td colspan="4" id="detalle1"><strong>TIPO DE NUMERACION </strong></td>
    <td  id="detalle1"><strong>BARRAS &amp; FORMATO</strong></td>
    <td id="detalle1">Lleva Faltante: 
     <b> <?php echo $row_orden_produccion['imprimiop']==1? 'NO' : 'SI'; ?></b>
    </td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>Solapa Talonario Recibo</strong></td>
    <td colspan="4" id="detalle1"><?php echo $row_ref_op['tipo_solapatr_egp']; ?></td>
    <td colspan="2" id="detalle1"><?php echo $row_ref_op['cb_solapatr_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>Cinta</strong></td>
    <td colspan="4" id="detalle1"><?php echo $row_ref_op['tipo_cinta_egp']; ?></td>
    <td colspan="2" id="detalle1"><?php echo $row_ref_op['cb_cinta_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>Superior</strong></td>
    <td colspan="4" id="detalle1"><?php echo $row_ref_op['tipo_superior_egp']; ?></td>
    <td colspan="2" id="detalle1"><?php echo $row_ref_op['cb_superior_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>Principal</strong></td>
    <td colspan="4" id="detalle1"><?php echo $row_ref_op['tipo_principal_egp']; ?></td>
    <td colspan="2" id="detalle1"><?php echo $row_ref_op['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>Inferior</strong></td>
    <td colspan="4" id="detalle1"><?php echo $row_ref_op['tipo_inferior_egp']; ?></td>
    <td colspan="2" id="detalle1"><?php echo $row_ref_op['cb_inferior_egp']; ?></td>
  </tr>
 <tr>
    <td colspan="2" id="detalle1"><strong>Liner</strong></td>
    <td colspan="4" id="detalle1"><?php echo $row_ref_op['tipo_liner_egp']; ?></td>
    <td colspan="2" id="detalle1"><?php echo $row_ref_op['cb_liner_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>Bolsillo</strong></td>
    <td colspan="4" id="detalle1"><?php echo $row_ref_op['tipo_bols_egp']; ?></td>
    <td colspan="2" id="detalle1"><?php echo $row_ref_op['cb_bols_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">&nbsp;<?php echo $row_ref_op['tipo_nom_egp']; ?></td>
    <td colspan="4" id="detalle1"><?php echo $row_ref_op['tipo_otro_egp']; ?></td>
    <td colspan="2" id="detalle1"><?php echo $row_ref_op['cb_otro_egp']; ?></td>
  </tr> 
  <tr>
    <td colspan="2" id="detalle1">METROS CINTA / LINER</td>
    <td id="detalle1">KILOS A SELLAR</td>
    <td id="detalle1">&nbsp;</td>
    <td id="detalle1">KILOS A SELLAR DE BOLSILLO</td>
    <td id="detalle1">UNIDADES A PRODUCIR</td>
    <td id="detalle1">CINTA TERMICA</td>
    <td id="detalle1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><?php echo $row_orden_produccion['mts_cinta_sellado_op']; ?>&nbsp;</td>
    <td id="detalle1"><?php echo $row_orden_produccion['kls_sellado_op']; ?></td>
    <td id="detalle1">&nbsp;</td>
    <td id="detalle1"><?php echo $row_orden_produccion['kls_sellado_bol_op']; ?></td>
    <td id="detalle1"><?php echo $row_orden_produccion['und_prod_sellado_op']; ?></td>
    <td id="detalle1"><?php $id_ter=$row_orden_produccion['id_termica_op'];
			$sqlterm="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$id_ter'"; 
			$resultterm=mysql_query($sqlterm); 
			$numterm=mysql_num_rows($resultterm); 
			if($numterm >= '1') 
			{ $nombre_term=mysql_result($resultterm,0,'descripcion_insumo');  echo $nombre_term; }
			 ?></td>
    <td id="detalle1"><?php echo $row_orden_produccion['cinta_termica_op']; ?></td>
  </tr>
         </table>
        </td>
      </tr>
          
      <tr >
        <td colspan="9" id="subppal2">Observacion en Sellado</td>
        </tr>      
      <tr >
        <td colspan="9" id="detalle1"><?php echo $row_orden_produccion['observ_sellado_op']; ?>&nbsp;</td>
        </tr>
        <?php //}?>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($orden_produccion);
mysql_free_result($unidad_uno);
mysql_free_result($unidad_dos);
mysql_free_result($unidad_tres);
mysql_free_result($unidad_cuatro);
mysql_free_result($unidad_cinco);
mysql_free_result($unidad_seis);
mysql_free_result($unidad_siete);
mysql_free_result($unidad_ocho);
mysql_free_result($caract_valor);
mysql_close($conexion1);
?>
