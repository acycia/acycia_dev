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


$conexion = new ApptivaDB();

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

$maxRows_usuario = 10;
$pageNum_usuario = 0;
if (isset($_GET['pageNum_usuario'])) {
  $pageNum_usuario = $_GET['pageNum_usuario'];
}
$startRow_usuario = $pageNum_usuario * $maxRows_usuario;

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

if (isset($_GET['totalRows_usuario'])) {
  $totalRows_usuario = $_GET['totalRows_usuario'];
} else {
  $all_usuario = mysql_query($query_usuario);
  $totalRows_usuario = mysql_num_rows($all_usuario);
}
$totalPages_usuario = ceil($totalRows_usuario/$maxRows_usuario)-1;
 
$colname_ref = "-1";
if (isset($_GET['id_pm'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_produccion_mezclas, Tbl_referencia WHERE Tbl_produccion_mezclas.id_pm='%s' AND Tbl_produccion_mezclas.id_ref_pm=Tbl_referencia.id_ref AND Tbl_produccion_mezclas.b_borrado_pm='0'",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
 

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
<?php 


$row_mezclaycaract_impresion = $conexion->llenarCampos("tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref"," WHERE cp.cod_ref= '".$_GET['cod_ref']."' AND cp.proceso=2 ", "","*"); 

$row_mezclaycaract_extruder = $conexion->llenarCampos("tbl_caracteristicas_prod cp LEFT JOIN tbl_produccion_mezclas pm ON pm.int_cod_ref_pm = cp.cod_ref"," WHERE cp.cod_ref= '".$_GET['cod_ref']."' AND cp.proceso='1' ", "","*"); 

$row_extruder = $conexion->llenarCampos("tbl_produccion_mezclas pm"," WHERE pm.int_cod_ref_pm= '".$_GET['cod_ref']."' AND pm.id_proceso='1' ", "","*"); 
$row_ref = $conexion->llenarCampos("tbl_produccion_mezclas cp"," WHERE cp.id_pm= '".$_GET['id_pm']."' ", "","*"); 
$row_impresion=$conexion->llenarCampos("tbl_produccion_mezclas cp"," WHERE cp.id_proceso=2 AND cp.int_cod_ref_pm= '".$_GET['cod_ref']."' ", "","*"); 
$row_materia_prima=$conexion->get_materiaPrima('insumo'," WHERE clase_insumo='8' "," ORDER BY descripcion_insumo ASC" );
$maquinas = $conexion->get_Maquina(); 
$anilox = $conexion->get_Anilox();
 

 $id_ref=$_GET['id_ref'];

$row_cualexiste=$conexion->llenarCampos("tbl_produccion_mezclas tmi "," WHERE tmi.id_proceso = 2 AND tmi.id_ref_pm= '".$id_ref."' ", "","id_ref_pm"); 
 

//CARGA UNIDAD 1
$colname_unidad_uno = "-1";
if (isset($id_ref)) {
  $colname_unidad_uno  = (get_magic_quotes_gpc()) ? $id_ref : addslashes($id_ref);
}
mysql_select_db($database_conexion1, $conexion1);
$query_caract_valor = ("SELECT * FROM Tbl_caracteristicas, Tbl_caracteristicas_valor WHERE Tbl_caracteristicas_valor.id_ref_cv='$id_ref' AND Tbl_caracteristicas.id_c=Tbl_caracteristicas_valor.id_c_cv AND Tbl_caracteristicas.proceso_c='2' ORDER BY Tbl_caracteristicas_valor.id_cv ASC");
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
 

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">


<link rel="stylesheet" type="text/css" href="css/general.css"/>
<link rel="stylesheet" type="text/css" href="css/formato.css"/>
<link rel="stylesheet" type="text/css" href="css/desplegable.css" />


<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<title>SISADGE AC & CIA</title>
</head>
<body onload="extrusoraNumero()" >
<div align="center">
<table  id="tablainterna" cellspacing="0" cellpadding="0">
  <tr>    
     <td colspan="18" id="principal">HOJA MAESTRA DE PROCESOS</td>
  </tr>
  <tr>
    <td rowspan="7" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="3" id="dato3">MENU</a></td>
    <td colspan="7" id="dato3"><!--<a href="produccion_caract_extrusion_mezcla_edit.php?id_ref=<?php echo $row_extruder['id_ref_pm']; ?>&amp;id_pm=<?php echo $row_extruder['id_pm']; ?>"><img src="images/menos.gif" alt="EDITAR"title="EDITAR" border="0" /></a>-->
      <?php if ($row_usuario['tipo_usuario']=='1') {?>
      <a href="produccion_mezclas.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a>
      <!-- <a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /> -->
      <?php }?>
      </a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU" border="0"/></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="INPRIMIR"title="INPRIMIR" /></td>
    </tr>
  <tr>
    <td colspan="3" id="dato3">Menu de Produccion</td>
    <td colspan="7" id="dato3"><?php if ($row_usuario['tipo_usuario']=='1') {?>
	<?php $ref=$row_ref['id_ref'];
	  $sqlpm="SELECT * FROM Tbl_produccion_mezclas WHERE id_ref_pm='$ref' and id_proceso='1'";
	  $resultpm= mysql_query($sqlpm);
	  $row_pm = mysql_fetch_assoc($resultpm);
	  $numpm= mysql_num_rows($resultpm);
	  $id_pm = mysql_result($resultpm, 0, 'id_pm');
	  
	  $sqlcv="SELECT * FROM Tbl_caracteristicas_prod WHERE int_cod_ref_pm='$ref' and id_proceso_cv='1'";
	  $resultcv= mysql_query($sqlcv);
	  $row_cv = mysql_fetch_assoc($resultcv);
	  $numcv= mysql_num_rows($resultcv);
	  	  
	  if($numpm >='1' && $numcv >='1')
	  { ?>
      <a href="view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $row_referencia_egp['cod_ref'];?>"><img src="images/e.gif" style="cursor:hand;" alt="EXTRUSION" title="EXTRUSION" border="0" /></a> 
      <?php } else if($numpm =='0' && $numcv =='0'){ ?>
      <a href="produccion_mezclas_add.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&cod_ref=<?php echo $row_referencia_egp['cod_ref']; ?>"><img src="images/e_rojo.gif" style="cursor:hand;" alt="ADD FORMULA EXTRUSION" title="ADD FORMULA EXTRUSION" border="0" /></a>
      <?php } else if($numpm >='1' && $numcv =='0'){ ?>
      <a href="produccion_caract_extrusion_mezcla_edit.php?id_ref=<?php echo $row_referencia_egp['id_ref']; ?>&id_pm=<?php echo $id_pm; ?>&cod_ref=<?php echo $row_referencia_egp['cod_ref']; ?>"><img src="images/c_rojo.gif" style="cursor:hand;" alt="ADD CARACTERISTICAS" title="ADD CARACTERISTICAS" border="0" /></a>
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
     <?php }?> 
   </td>
  </tr>
  <tr>
    <td colspan="3" id="subppal2">FECHA DE INGRESO </td>
    <td colspan="7" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td colspan="3" id="fuente2"><?php echo $row_extruder['fecha_registro_pm']; ?></td>
    <td colspan="7" nowrap id="fuente2"><?php echo $row_extruder['str_registro_pm']; ?></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">Referencia</td>
    <td colspan="7" id="subppal2">Version</td>
    </tr>
  <tr>
    <td colspan="3" nowrap id="fuente2"><?php echo $row_extruder['int_cod_ref_pm']; ?></td>
    <td colspan="7" id="fuente2"><?php echo $row_extruder['version_ref_pm']; ?></td>
    </tr>
  <tr>
    <td colspan="18" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="18" id="principal">ESPECIFICACIONES DE MEZCLA EN EXTRUSION</td>
    </tr>
  <tr>
    <td rowspan="2" id="subppal2">EXT-1 </td>
    <td colspan="3" id="subppal2">TORNILLO A</td>
    <td colspan="3" id="subppal2">TORNILLO B</td>
    <td colspan="4" id="subppal2">TORNILLO C</td>
    </tr>
  <tr>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
    <td id="subppal3">Referencia</td>
    <td colspan="5" id="subppal2">%</td>
  </tr>
  <tr>
    <td id="fuente2">Tolva A</td>
    <td id="fuente2"><?php 
	    $idinsumo=$row_extruder['int_ref1_tol1_pm']; 
		$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref1_tol1_porc1_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo=$row_extruder['int_ref2_tol1_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref2_tol1_porc2_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo= $row_extruder['int_ref3_tol1_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}		
	 ?></td>
    <td colspan="5" id="fuente2"><?php echo $row_extruder['int_ref3_tol1_porc3_pm']; ?></td>
  </tr> 
<tr>
    <td id="fuente2">Tolva B</td>
    <td id="fuente2"><?php $idinsumo = $row_extruder['int_ref1_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}
	 ?> 
   </td>
    <td id="fuente2"><?php echo $row_extruder['int_ref1_tol2_porc1_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_extruder['int_ref2_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref2_tol2_porc2_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_extruder['int_ref3_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td colspan="5" id="fuente2"><?php echo $row_extruder['int_ref3_tol2_porc3_pm']; ?></td>
  </tr>
  <tr>
    <td id="fuente2">Tolva C</td>
    <td id="fuente2"><?php $idinsumo =  $row_extruder['int_ref1_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref1_tol3_porc1_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_extruder['int_ref2_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref2_tol3_porc2_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_extruder['int_ref3_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td colspan="5" id="fuente2"><?php echo $row_extruder['int_ref3_tol3_porc3_pm']; ?></td>
  </tr>
  <tr>
    <td id="fuente2">Tolva D</td>
    <td id="fuente2"><?php $idinsumo =  $row_extruder['int_ref1_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref1_tol4_porc1_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_extruder['int_ref2_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref2_tol4_porc2_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_extruder['int_ref3_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td colspan="5" id="fuente2"><?php echo $row_extruder['int_ref3_tol4_porc3_pm']; ?></td>
  </tr>
  <tr>
    <td id="fuente2">RPM - %</td>
    <td id="fuente2"><?php echo $row_extruder['int_ref1_rpm_pm']; ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref1_tol5_porc1_pm']; ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref2_rpm_pm']; ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref2_tol5_porc2_pm']; ?></td>
    <td id="fuente2"><?php echo $row_extruder['int_ref3_rpm_pm']; ?></td>
    <td colspan="5" id="fuente2"><?php echo $row_extruder['int_ref3_tol5_porc3_pm']; ?></td>
  </tr>
  <tr>
    <td id="fuente2">&nbsp;</td>
    <td id="subppal3">PRESENTACION</td>
    <td id="subppal3">TRATAMIENTO</td>
    <td id="subppal3">PESO MILLAR</td>
    <td colspan="7" id="fuente2">&nbsp;</td>
    </tr>
  <tr>
    <td id="fuente2">&nbsp;</td>
    <td id="fuente2"><?php echo $row_referencia_egp['Str_presentacion']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['Str_tratamiento']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['peso_millar_ref']; ?></td>
    <td colspan="7" id="fuente2">&nbsp;</td>
    </tr>  
<tr>
  <td colspan="18" id="fuente2">Observaciones de Mezclas: <?php echo $row_extruder['observ_pm']; ?></td>
</tr>

<tr id="tr1">
      <td colspan="18" id="principal">CARACTERISTICAS DE EXTRUSION </td>
    </tr>  
     <tr>
      <td colspan="18" id="fuente2"> 
       Estrusora :<strong> <?php echo $row_mezclaycaract_extruder['extrusora_mp']; ?> </strong>
     </td>
    </tr>
    <tr id="tr1">
      <td colspan="3" id="fuente1">Opcion No 1</td>
      <td colspan="2" id="fuente2">Calibre</td>
      <td colspan="6" id="fuente2">Ancho material</td>
      </tr>
    <tr>
      <td colspan="2" id="fuente1">
      Boquilla de Extrusion</td>
      <td id="fuente1"> 
        <?php echo $row_mezclaycaract_extruder['campo_1'];?> </td>
      <td id="fuente1">Calibre</td>
      <td id="fuente1">Micras</td>
      <td colspan="6" id="fuente1">&nbsp;Ancho</td>
      </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1">Relacion Soplado (RS)</td>
      <td id="fuente1"> 
        <?php echo $row_mezclaycaract_extruder['campo_2'];?></td>
      <td id="fuente1">
         <?php echo $row_mezclaycaract_extruder['campo_3'];?> </td>
      <td id="fuente1"> 
        <label for="micrass"></label>
        <?php echo $row_mezclaycaract_extruder['campo_6'];?></td>
      <td colspan="6" id="fuente1">  
        <?php echo $row_mezclaycaract_extruder['campo_7'];?> 
      </td>
      </tr>
    <tr>
      <td colspan="2" rowspan="2" id="fuente1">Altura Linea    Enfriamiento</td>
      <td colspan="2" rowspan="2" id="fuente1"> 
        <?php echo $row_mezclaycaract_extruder['campo_8'];?></td>
      <td id="fuente1">Presentacion</td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="5" id="fuente1">Peso Millar</td>
      </tr>
    <tr>
      <td id="fuente1">
        <?php echo $row_mezclaycaract_extruder['campo_9'];?></td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="5" id="fuente1">
        <?php echo $row_mezclaycaract_extruder['campo_10'];?></td>
      </tr>
    <tr id="tr1">
      <td rowspan="2" id="fuente1">Velocidad de Halado</td>
      <td colspan="2" id="fuente1">Tratamiento Corona</td>
      <td colspan="4" id="fuente2">Ubicaci&oacute;n Tratamiento</td>
      <td colspan="4" id="fuente1">Pigmentaci&oacute;n</td>
    </tr>
    <tr>
      <td id="fuente1">Potencia</td>
      <td id="fuente1">
        <?php echo $row_mezclaycaract_extruder['campo_11'];?>
       </td>
      <td id="fuente1">Cara Interior</td>
      <td colspan="2" id="fuente1">
         <?php echo $row_mezclaycaract_extruder['campo_12'];?>
        </td>
      <td id="fuente1">Interior
       </td>
      <td colspan="4" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_13'];?></td>
    </tr>
    <tr>
      <td id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_14'];?></td>
      <td id="fuente1">Dinas</td>
      <td id="fuente1">
        <?php echo $row_mezclaycaract_extruder['campo_15'];?>
      </td>
      <td id="fuente1">Cara Exterior</td>
      <td colspan="2" id="fuente1">
        <?php echo $row_mezclaycaract_extruder['campo_16'];?>
       </td>
      <td id="fuente1">Exterior
        </td>
      <td colspan="4" id="fuente1">
        <?php echo $row_mezclaycaract_extruder['campo_17'];?>
      </td>
    </tr>
    <tr id="tr1">
      <td rowspan="2" id="fuente1">% Aire Anillo Enfriamiento</td>
      <td colspan="3" id="fuente2">Tension</td>
      <td colspan="7" id="fuente1">&nbsp;</td>
    </tr>
    <tr id="tr1" class="zonaextruder1" style="display: none;">
      <td colspan="2"id="fuente1">Sec Take Off</td>
      <td colspan="2"id="fuente1">Winder A</td>
      <td colspan="2"id="fuente1">Winder B</td>
      <td colspan="4" id="fuente1" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr id="tr1" class="zonaextruder2" style="display: none;">
      <td colspan="2"id="fuente1">Calandia</td>
      <td colspan="2"id="fuente1">Colapsador</td>
      <td colspan="2"id="fuente1">Embobinador Ext.</td>
      <td colspan="5"id="fuente1" nowrap="nowrap">Embobinador Int.</td>
    </tr> 
    <tr>
      <td  id="fuente1">
         <?php echo $row_mezclaycaract_extruder['campo_18'];?>
      </td>
      <td colspan="2"id="fuente1" class="zonaextruder1" style="display: none;">
        <?php echo $row_mezclaycaract_extruder['campo_19'];?>
      </td>
      <td colspan="2"id="fuente1" class="zonaextruder1" style="display: none;">
        <?php echo $row_mezclaycaract_extruder['campo_20'];?>
      </td>
      <td colspan="5"id="fuente1" class="zonaextruder1" style="display: none;">
        <?php echo $row_mezclaycaract_extruder['campo_21'];?>
       </td>

       <td colspan="2"id="fuente1" class="zonaextruder2" style="display: none;">
         <?php echo $row_mezclaycaract_extruder['campo_54'];?> 
       </td>
       <td colspan="2"id="fuente1" class="zonaextruder2" style="display: none;">
         <?php echo $row_mezclaycaract_extruder['campo_55'];?> 
       </td>
       <td colspan="2"id="fuente1" class="zonaextruder2" style="display: none;">
          <?php echo $row_mezclaycaract_extruder['campo_56'];?> 
       </td>
       <td colspan="5" id="fuente1" class="zonaextruder2" style="display: none;" nowrap="nowrap">
         <?php echo $row_mezclaycaract_extruder['campo_57'];?> 
       </td> 

       <td  id="fuente1" class="zonaextruder1" style="display: none;" nowrap="nowrap" >&nbsp;</td>
    </tr>
    <tr>
      <td colspan="18" id="fuente1"><strong> Nota:</strong> Favor entregar al proceso siguiente el material debidamente identificado seg&uacute;n el documento    correspondiente para cada rollo de material.</td>
      
    </tr> 
    <tr id="tr1">
      <td colspan="8" id="principal">TEMPERATURAS DE FUNDIDO EN TORNILLOS Y CABEZAL</td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">&nbsp;</td>
      <td colspan="2"id="fuente1">TORNILLO A</td>
      <td colspan="2"id="fuente1">TORNILLO B</td>
      <td id="fuente1">TORNILLO C</td>
      <td id="fuente1">Cabezal (Die Head)</td>
      <td colspan="4" id="fuente1">&deg;C</td>
    </tr>
    <tr>
      <td id="fuente1">Barrel Zone 1</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_22'];?></td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_23'];?>
       </td>
      <td id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_24'];?>
       </td>
      <td colspan="1" id="fuente1">Share Lower</td>
      <td colspan="4" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_25'];?>
      </td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">Barrel Zone 2</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_26'];?>
      </td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_27'];?>
      </td>
      <td id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_28'];?>
        </td>
      <td colspan="1" id="fuente1">Share Upper</td>
      <td colspan="4" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_29'];?>
       </td>
    </tr>
    <tr>
      <td id="fuente1">Barrel Zone 3</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_30'];?>
        </td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_31'];?>
       </td>
      <td id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_32'];?>
      </td>
      <td colspan="1" id="fuente1">L-Die</td>
      <td colspan="4" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_33'];?>
       </td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">Barrel Zone 4</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_34'];?>
       </td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_35'];?>
        </td>
      <td id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_36'];?>
        </td>
      <td colspan="1" id="fuente1">V- Die</td>
      <td colspan="4" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_37'];?>
       </td>
    </tr>
    <tr>
      <td id="fuente1">Filter Front</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_38'];?>
       </td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_39'];?>
        </td>
      <td id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_40'];?>
        </td>
      <td colspan="1" id="fuente1">Die Head</td>
      <td colspan="4" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_41'];?>
        </td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">Filter Back</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_42'];?>
        </td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_43'];?>
        </td>
      <td id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_44'];?>
        </td>
      <td colspan="1" id="fuente1">Die Lid</td>
      <td colspan="4" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_45'];?>
        </td>
    </tr>
    <tr>
      <td id="fuente1">Sec- Barrel</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_46'];?>
         </td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_47'];?>
        </td>
      <td id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_48'];?>
        </td>
      <td colspan="1" id="fuente1">Die Center Lower</td>
      <td colspan="4" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_49'];?>
        </td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">Melt Temp &deg;C</td>
        <td colspan="2"id="fuente1"> <?php echo $row_mezclaycaract_extruder['campo_50'];?>
        </td>
      <td colspan="2"id="fuente1">
         <?php echo $row_mezclaycaract_extruder['campo_51'];?>
        </td>
      <td id="fuente1">
         <?php echo $row_mezclaycaract_extruder['campo_52'];?>
        </td>
      <td id="fuente1">Die Center Upper</td>
      <td colspan="4" id="fuente1">
         <?php echo $row_mezclaycaract_extruder['campo_53'];?>
     </td>
    </tr>

    <tr class="zonaextruder2" style="display: none;" >
      <td id="fuente1">Zona 5</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_58'];?>
        </td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_59'];?>
        </td>
      <td colspan="5" id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_60'];?>
        </td> 
    </tr>
    <tr id="tr1" class="zonaextruder2" style="display: none;" >
      <td id="fuente1">Zona 6</td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_61'];?>
        </td>
      <td colspan="2"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_62'];?>
        </td>
      <td colspan="5"id="fuente1"><?php echo $row_mezclaycaract_extruder['campo_63'];?>
        </td> 
    </tr>

  <tr>    
    <td colspan="18" id="principal"> MEZCLAS Y CARACTERISTICAS DE IMPRESION</td>
  </tr>
 


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
                                                      <td nowrap id="fuente1"><strong>UNIDAD 1</strong></td>
                                                      <td nowrap id="fuente2">DESCRIPCION</td>
                                                      <td nowrap id="fuente1">VALOR</td>
                                                      </tr>
                                                    <?php  for ($y=0;$y<=5;$y++) { ?><!--for ($y=0;$y<=$totalRows_unidad_uno-1;$y++)// TRAE TODOS LOS REGISTROS--> 
                                                    <tr>
                                                      <td  nowrap id="talla1"><?php $id_m=mysql_result($unidad_uno,$y,str_nombre_m);echo $id_m; ?></td>
                                                      <td  id="talla1"><?php $var=mysql_result($unidad_uno,$y,descripcion_insumo); echo $var;?></td>           
                                                      <td id="talla3"><?php $var1=mysql_result($unidad_uno,$y,str_valor_pmi); echo $var1; ?></td>         
                                                      </tr>  <?php  } ?>                                        
                                                    </table> 
                                                  <?php  } ?>       
                                                  </div>
                                                  <div id="cajon1">
                                                  <?php if($totalRows_unidad_dos!='0') { ?>               
                                                  <table>
                                                    <tr id="tr1">
                                                      <td nowrap id="fuente1"><strong>UNIDAD 2</strong></td>
                                                      <td nowrap id="fuente2">DESCRIPCION</td>
                                                      <td nowrap id="fuente1">VALOR</td>
                                                      </tr>
                                                    <?php  for ($x=0;$x<=5;$x++) { ?><tr>
                                                      <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_dos,$x,str_nombre_m);echo $id_m; ?></td>           
                                                      <td  id="talla1"><?php $var=mysql_result($unidad_dos,$x,descripcion_insumo); echo $var;?></td>           
                                                      <td id="talla3"><?php $var1=mysql_result($unidad_dos,$x,str_valor_pmi); echo $var1; ?></td>         
                                                      </tr>  <?php  } ?>                                        
                                                    </table>
                                                  <?php  } ?>       
                                                 </div>
                                                 <div id="cajon1">
                                                <?php if($totalRows_unidad_tres!='0') { ?>
                                                  <table>
                                                    <tr id="tr1">
                                                      <td nowrap id="fuente1"><strong>UNIDAD 3</strong></td>
                                                      <td nowrap id="fuente2">DESCRIPCION</td>
                                                      <td nowrap id="fuente1">VALOR</td>
                                                      </tr>
                                                    <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                      
                                                      <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_tres,$y,str_nombre_m);echo $id_m; ?></td>
                                                      <td id="talla1"><?php $var=mysql_result($unidad_tres,$y,descripcion_insumo); echo $var;?></td>
                                                      <td id="talla3"><?php $var1=mysql_result($unidad_tres,$y,str_valor_pmi); echo $var1; ?></td>
                                                      </tr>
                                                    <?php  } ?>
                                                    </table>
                                                  <?php  } ?> 
                                          </div>
                                                 <div id="cajon1">
                                          <?php if($totalRows_unidad_cuatro!='0') { ?>
                                                  <table>
                                                    <tr id="tr1">
                                                      <td nowrap id="fuente1"><strong>UNIDAD 4</strong></td>
                                                      <td nowrap id="fuente2">DESCRIPCION</td>
                                                      <td nowrap id="fuente1">VALOR</td>
                                                      </tr>
                                                    <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                      
                                                      <td nowrap  id="talla1"><?php $id_m=mysql_result($unidad_cuatro,$y,str_nombre_m);echo $id_m; ?></td>
                                                      <td   id="talla1"><?php $var=mysql_result($unidad_cuatro,$y,descripcion_insumo); echo $var; ?></td>
                                                      <td id="talla3"><?php $var=mysql_result($unidad_cuatro,$y,str_valor_pmi); echo $var; ?></td>
                                                      </tr>
                                                    <?php  } ?>
                                                    </table>
                                                  <?php  } ?> 
                                                  </div>
                                                 <div id="cajon1">
                                                 <?php if($totalRows_unidad_cinco!='0') { ?>
                                                  <table>
                                                    <tr id="tr1">
                                                      <td nowrap id="fuente1"><strong>UNIDAD 5</strong></td>
                                                      <td nowrap id="fuente2">DESCRIPCION</td>
                                                      <td nowrap id="fuente1">VALOR</td>
                                                      </tr>
                                                    <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                      
                                                      <td  nowrap id="talla1"><?php $id_m=mysql_result($unidad_cinco,$y,str_nombre_m);echo $id_m; ?></td>
                                                      <td   id="talla1"><?php $var=mysql_result($unidad_cinco,$y,descripcion_insumo); echo $var;?></td>
                                                      <td id="talla3"><?php $var=mysql_result($unidad_cinco,$y,str_valor_pmi); echo $var; ?></td>
                                                      </tr>
                                                    <?php  } ?>
                                                    </table>
                                                  <?php  } ?>
                                                  </div>
                                                 <div id="cajon1"> 
                                         <?php if($totalRows_unidad_seis!='0') { ?>
                                                  <table>
                                                    <tr id="tr1">
                                                      <td nowrap id="fuente1"><strong>UNIDAD 6</strong></td>
                                                      <td nowrap id="fuente2">DESCRIPCION</td>
                                                      <td nowrap id="fuente1">VALOR</td>
                                                      </tr>
                                                    <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                      
                                                      <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_seis,$y,str_nombre_m);echo $id_m; ?></td>
                                                      <td  id="talla1"><?php $var=mysql_result($unidad_seis,$y,descripcion_insumo); echo $var;?></td>
                                                      <td id="talla3"><?php $var=mysql_result($unidad_seis,$y,str_valor_pmi); echo $var; ?></td>
                                                      </tr>
                                                    <?php  } ?>
                                                    </table>
                                                  <?php  } ?>
                                                  </div>
                                                 <div id="cajon1"> 
                                         <?php if($totalRows_unidad_siete!='0') { ?>
                                                  <table>
                                                    <tr id="tr1">
                                                      <td nowrap id="fuente1"><strong>UNIDAD 7</strong></td>
                                                      <td nowrap id="fuente2">DESCRIPCION</td>
                                                      <td nowrap id="fuente1">VALOR</td>
                                                      </tr>
                                                    <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                      
                                                      <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_siete,$y,str_nombre_m);echo $id_m; ?></td>
                                                      <td  id="talla1"><?php $var=mysql_result($unidad_siete,$y,descripcion_insumo); echo $var;?></td>
                                                      <td id="talla3"><?php $var=mysql_result($unidad_siete,$y,str_valor_pmi); echo $var; ?></td>
                                                      </tr>
                                                    <?php  } ?>
                                                    </table>
                                                  <?php  } ?>
                                                  </div>
                                                 <div id="cajon1">
                                                  <?php if($totalRows_unidad_ocho!='0') { ?>
                                                  <table>
                                                    <tr id="tr1">
                                                      <td nowrap id="fuente1"><strong>UNIDAD 8</strong></td>
                                                      <td nowrap id="fuente2">DESCRIPCION</td>
                                                      <td nowrap id="fuente1">VALOR</td>
                                                      </tr>
                                                    <?php  for ($y=0;$y<=5;$y++) { ?><tr>
                                                      
                                                      <td nowrap id="talla1"><?php $id_m=mysql_result($unidad_ocho,$y,str_nombre_m);echo $id_m;?></td>
                                                      <td  id="talla1"><?php $var=mysql_result($unidad_ocho,$y,descripcion_insumo); echo $var; ?></td>
                                                      <td id="talla3"><?php $var=mysql_result($unidad_ocho,$y,str_valor_pmi); echo $var; ?></td>
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
                        <tr>
                            <?php  for ($x=0;$x<=$totalRows_caract_valor-1;$x++) { ?>          
                                 <td width="130" id="talla1"><?php $id_cv=mysql_result($caract_valor,$x,id_cv); $var=mysql_result($caract_valor,$x,str_nombre_caract_c);if($var!=''){echo $var;}else{echo "MP eliminada";} ?>                                             
                                <?php $valor=mysql_result($caract_valor,$x,str_valor_cv); echo $valor;?>
                                 </td>
                                <?php  } ?>
                        </tr>
                       <?php elseif(isset($_GET['cod_ref'])&& $_GET['cod_ref']!=''): ?> 

     <!--  INICIA MEZCLAS DE EXTRUDER -->
                      <tr>
                        <td colspan="18" >
                            <table style="width: 100%">
                                  <tr>
                                   <td colspan="18" id="fuente1"> 
                                    Impresora : <?php echo $row_impresion['extrusora_mp'];?>
                                  </td>
                                 </tr>
                                <tr id="tr1">
                                  <td rowspan="2" id="fuente1"> </td>
                                  <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 1</b> </td>
                                  <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 2</b> </td>
                                  <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 3</b> </td>
                                  <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 4</b> </td>
                                  <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 5</b> </td>
                                  <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 6</b> </td>
                                  <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 7</b> </td>
                                  <td nowrap="nowrap" colspan="2" id="fuente1"><b>UNIDAD 8</b> </td> 
                                 </tr> 
                                 <tr>
                                    <td></td>
                                 </tr> 
                                <tr id="tr1">
                                 <td id="fuente1"><b>COLORES</b></td>
                                  <td  id="fuente1">
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
                                 <td id="fuente1">
                                     <?php echo $row_impresion['int_ref1_tol1_porc1_pm']; ?> 
                                  </td>
                                 <td id="fuente1">
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
                                 <td id="fuente1">
                                       <?php echo $row_impresion['int_ref3_tol3_porc3_pm']; ?>
                                 </td>
                                 <td id="fuente1">
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
                                 <td id="fuente1">
                                       <?php echo $row_mezclaycaract_impresion['campo_2']; ?>
                                 </td>
                                 <td id="fuente1"> 
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
                                 <td id="fuente1">
                                       <?php echo $row_mezclaycaract_impresion['campo_4']; ?>
                                 </td>

                                      
                                 <td id="fuente1"> 
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
                                      <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_6']; ?>
                                      </td>
                                      <td id="fuente1"> 
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
                                      <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_8']; ?>
                                      </td>
                                      <td id="fuente1"> 
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
                                      <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_10']; ?>
                                      </td>
                                      <td id="fuente1"> 
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
                                      <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_12']; ?>
                                       </td>  

                                </tr>

                                <tr>
                                  <td id="fuente1"><b>MEZCLAS</b></td>
                                  <td id="fuente1"> 
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
                                  <td id="fuente1">
                                    <?php echo $row_impresion['int_ref1_tol2_porc1_pm']; ?>
                                  </td> 
                                   <td id="fuente1"> 
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
                                  <td id="fuente1">
                                      <?php echo $row_impresion['int_ref3_tol4_porc3_pm']; ?>
                                    </td>

                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_14']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                      <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_16']; ?>
                                      </td>
                                   
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_18']; ?>
                                      </td>
                                     <td id="fuente1"> 
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
                                     <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_20']; ?>
                                      </td>
                                     <td id="fuente1"> 
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
                                      <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_22']; ?>
                                      </td>
                                       <td id="fuente1"> 
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
                                     <td id="fuente1">
                                        <?php echo $row_mezclaycaract_impresion['campo_24']; ?>
                                      </td>

                                  </tr>
                          
                                <tr id="tr1">
                                  <td id="fuente1"></td>
                                  <td id="fuente1"> 
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
                                  <td id="fuente1">
                                    <?php echo $row_impresion['int_ref1_tol3_porc1_pm']; ?>
                                  </td>
                                 <td id="fuente1"> 
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
                                 <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_26']; ?>
                                   </td>
                                 <td id="fuente1"> 
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
                                 <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_28']; ?>
                                   </td>
                                 <td id="fuente1"> 
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
                                  <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_30']; ?>
                                   </td>
                                 
                                 <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_32']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_34']; ?>
                                      </td>
                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_36']; ?>
                                   </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_38']; ?>
                                    </td> 

                                </tr>

                                <tr>
                                  <td id="fuente1"></td>
                                  <td id="fuente1">
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
                                  <td id="fuente1">
                                    <?php echo $row_impresion['int_ref1_tol4_porc1_pm']; ?>
                                  </td>
                                 <td id="fuente1"> 
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
                                  <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_40']; ?>
                                   </td>
                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_42']; ?>
                                     </td>
                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_44']; ?>
                                    </td>
                                   
                                   <td id="fuente1"> 
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
                                    <td id="fuente1">
                                       <?php echo $row_mezclaycaract_impresion['campo_46']; ?>
                                     </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_48']; ?>
                                     </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_50']; ?>
                                     </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_52']; ?>
                                     </td>   
                                 
                               </tr> 

                                <tr>
                                  <td id="fuente1"></td>
                                    <td id="fuente1">
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
                                    <td id="fuente1">
                                      <?php echo $row_impresion['int_ref2_tol1_porc2_pm']; ?>
                                    </td>
                                  <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_54']; ?>
                                    </td>
                                  <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_56']; ?>
                                    </td>
                                   <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_58']; ?>
                                    </td>
                                   
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_60']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_62']; ?>
                                     </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_64']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_66']; ?>
                                     </td>
                                       
                                </tr>
                                <tr>
                                  <td id="fuente1"><b>ALCOHOL</b></td>
                                    <td id="fuente1">
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
                                    <td id="fuente1">
                                      <?php echo $row_impresion['int_ref2_tol2_porc2_pm']; ?>
                                    </td>
                                  <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_68']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_70']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_72']; ?>
                                    </td>
                                 
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_74']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_76']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_78']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_80']; ?>
                                    </td> 
                                       
                                </tr>

                                <tr>
                                  <td id="fuente1"><b>ACETATO</b> NPA</td>
                                    <td id="fuente1">
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
                                    <td id="fuente1"> 
                                    <?php echo $row_impresion['int_ref2_tol3_porc2_pm']; ?>
                                    </td>
                                  <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_82']; ?>
                                      </td> 
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_84']; ?>
                                      </td> 
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_86']; ?>
                                      </td> 
                                 
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_88']; ?>
                                      </td> 
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_90']; ?>
                                      </td> 
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_92']; ?>
                                      </td> 
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_94']; ?>
                                    </td> 
                                     
                                </tr> 

                                <tr>
                                  <td id="fuente1"><b>METOXIPROPANOL</b></td>
                                    <td id="fuente1">
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
                                    <td id="fuente1">
                                      <?php echo $row_impresion['int_ref2_tol4_porc2_pm']; ?>
                                    </td>
                                  <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_96']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_98']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_100']; ?>
                                      </td>
                                  
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_102']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_104']; ?>
                                      </td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_106']; ?>
                                   </td>
                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_108']; ?>
                                    </td>

                                </tr>

                                <tr>
                                  <td id="fuente1"><b>VISCOSIDAD</b></td>
                                  <td colspan="2" id="fuente1"> 
                                    <?php echo $row_mezclaycaract_impresion['int_ref1_rpm_pm']; ?>
                                  </td> 
                                  <td colspan="2" id="fuente1">
                                    <?php echo $row_mezclaycaract_impresion['int_ref1_tol5_porc1_pm']; ?>
                                  </td>
                                  <td colspan="2" id="fuente1">
                                    <?php echo $row_mezclaycaract_impresion['int_ref2_rpm_pm']; ?>
                                  </td>
                                  <td colspan="2" id="fuente1">
                                    <?php echo $row_mezclaycaract_impresion['int_ref2_tol5_porc2_pm']; ?>
                                  </td>
                                  <td colspan="2" id="fuente1">
                                    <?php echo $row_mezclaycaract_impresion['int_ref3_rpm_pm']; ?>
                                  </td>
                                  <td colspan="2" id="fuente1">
                                    <?php echo $row_mezclaycaract_impresion['int_ref3_tol5_porc3_pm']; ?>
                                  </td>
                                  <td colspan="2" id="fuente1">
                                    <?php echo $row_mezclaycaract_impresion['campo_137']; ?>
                                  </td>
                                  <td colspan="2" id="fuente1">
                                    <?php echo $row_mezclaycaract_impresion['campo_138']; ?>
                                  </td>
                                </tr>

                                <tr>
                                  <td id="fuente1"><b>ANILOX</b></td>
                                    <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_impresion['int_ref3_tol1_porc3_pm']; ?>
                                    </td>
                                  <td id="fuente1">
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_110']; ?>
                                   </td>
                                  <td id="fuente1">
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_112']; ?>
                                   </td>
                                  <td id="fuente1">
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
                                  <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_114']; ?>
                                   </td> 

                                  <td id="fuente1">
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_116']; ?>
                                    </td>
                                    <td id="fuente1">
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_118']; ?>
                                      </td>
                                    <td id="fuente1">
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_120']; ?>
                                      </td>
                                    <td id="fuente1">
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_122']; ?>
                                    </td>

                                </tr>

                                <tr>
                                  <td id="fuente1"><b>BCM</b></td>
                                    <td id="fuente1">
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
                                    <td  id="fuente1">
                                      <?php echo $row_impresion['int_ref3_tol2_porc3_pm']; ?>
                                    </td>
                                  <td id="fuente1"> 
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
                                  <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_124']; ?>
                                   </td>
                                  <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_126']; ?>
                                    </td>
                                  <td id="fuente1"> 
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
                                    <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_128']; ?>
                                   </td>
                                    
                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_130']; ?>
                                   </td>
                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_132']; ?>
                                   </td>
                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_134']; ?>
                                   </td>
                                   <td id="fuente1"> 
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
                                   <td id="fuente1">
                                      <?php echo $row_mezclaycaract_impresion['campo_136']; ?>
                                   </td>

                                </tr> 

                                <tr>
                                  <td colspan="18" id="fuente1">
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
         <!--  INICIA MEZCLAS DE EXTRUDER -->                  

      <?php endif;?> 



   
  <br>
<tr>  
  <td colspan="18" id="principal">CARACTERISTICAS EN SELLADO</td>
    </tr>
  <tr>
    <td id="subppal2">ANCHO</td>
    <td id="subppal2">LARGO</td>
    <td colspan="3" id="subppal2">SOLAPA</td>
    <td colspan="6" id="subppal2">BOLSILLO PORTAGUIA</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['ancho_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['largo_ref']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['solapa_ref']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['bolsillo_guia_ref']; ?></td>
  </tr>
  <tr>
    <td id="subppal2">CALIBRE</td>
    <td id="subppal2">PESO MILLAR </td>
    <td colspan="3" id="subppal2">TIPO DE BOLSA </td>
    <td colspan="6" id="subppal2">ADHESIVO</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['calibre_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['peso_millar_ref']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_bolsa_ref']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['adhesivo_ref']; ?></td>
  </tr>
  <tr>
    <td rowspan="2" id="subppal2">PRESENTACION</td>
    <td rowspan="2" id="subppal2">TRATAMIENTO CORONA</td>
    <td colspan="9" id="subppal2">Bolsillo Portaguia</td>
    </tr>
  <tr>
    <td id="subppal2"> (Ubicacion)</td>
    <td id="subppal2">(Forma)</td>
    <td id="subppal2">Cant/Traslape</td>
    <td id="subppal">Calibre/Bols</td>
    <td id="subppal"> Lamina 1</td>
    <td colspan="4" id="subppal">Lamina 2</td>
  </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['Str_presentacion']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['Str_tratamiento']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['str_bols_ub_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['str_bols_fo_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['B_cantforma']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['calibreBols_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_1_ref']; ?></td>
    <td colspan="4" id="fuente2"><?php echo $row_referencia_egp['bol_lamina_2_ref']; ?></td>
  </tr>
<tr>
    <td id="fuente1">&nbsp;</td>
    <td colspan="10" id="fuente1">&nbsp;</td>
    </tr>
   <tr>	
      <td rowspan="2" id="subppal2">MARGENES</td>
      <td id="subppal2">Izquierda mm</td>
      <td id="fuente1"><?php echo $row_referencia_egp['margen_izq_imp_egp']; ?></td>
      <td colspan="2" id="subppal2">Rep. en Ancho</td>
      <td colspan="2" id="fuente1"><?php echo $row_referencia_egp['margen_anc_imp_egp']; ?></td>
      <td colspan="4" id="fuente1">de: <?php echo $row_referencia_egp['margen_anc_mm_imp_egp']; ?> mm</td>
    </tr>
      <tr>
        <td id="subppal2">Derecha mm</td>
        <td id="fuente1"><?php echo $row_referencia_egp['margen_der_imp_egp']; ?></td>
        <td colspan="2" id="subppal2">Rep. Perimetro</td>
        <td colspan="2" id="fuente1"><?php echo $row_referencia_egp['margen_peri_imp_egp']; ?></td>
        <td colspan="4" id="fuente1">de: <?php echo $row_referencia_egp['margen_per_mm_imp_egp']; ?> mm</td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="subppal2"><strong>Z</strong></td>
        <td id="fuente1"><?php echo $row_referencia_egp['margen_z_imp_egp']; ?></td>
        <td colspan="8" id="fuente1">&nbsp;</td>
    </tr>  
  <tr>
    <td id="subppal2">TIPO DE SELLO </td>
    <td id="subppal2">UNIDADES X CAJA</td>
    <td id="subppal2">UNIDADES X PAQUETE</td>
    <td colspan="2" id="subppal2">PRECORTE(Bolsillo Portaguia)</td>
    <td colspan="6" id="subppal2">&nbsp;</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referencia_egp['tipo_sello_egp']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_1_ref']; ?></td>
    <td id="fuente2"><?php echo $row_referencia_egp['bol_lamina_2_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php if($row_referencia_egp['B_troque']=='1') {echo "SI";}else{echo "NO";}; ?></td>
    <td colspan="6" id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">POSICION</td>
    <td colspan="3" id="subppal">TIPO DE NUMERACION </td>
    <td colspan="6" id="subppal">BARRAS &amp; FORMATO</td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">SOLAPA TALONARIO RECIBO </td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_solapatr_egp']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['cb_solapatr_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">CINTA</td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_cinta_egp']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['cb_cinta_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">SUPERIOR</td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_superior_egp']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['cb_superior_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">PRINCIPAL</td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_principal_egp']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['cb_principal_egp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal">INFERIOR</td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_inferior_egp']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['cb_inferior_egp']; ?></td>              
  </tr>
  <tr>
    <td colspan="2" id="subppal">LINER</td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_liner_egp']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['cb_liner_egp']; ?></td>              
  </tr>
  <tr>
    <td colspan="2" id="subppal">BOLSILLO</td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_bols_egp']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['cb_bols_egp']; ?></td>              
  </tr>
  <tr>
    <td colspan="2" id="subppal">Otro: <?php echo $row_referencia_egp['tipo_nom_egp']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_referencia_egp['tipo_otro_egp']; ?></td>
    <td colspan="6" id="fuente2"><?php echo $row_referencia_egp['cb_otro_egp']; ?></td>              
  </tr> 
  <tr>
    <td colspan="2" id="subppal4">&nbsp;</td>
    <td colspan="3" id="subppal4">&nbsp;</td>
    <td colspan="6" id="subppal4">&nbsp;</td>
  </tr>
  </table>
  <br><br>     
</div>

</body>
</html>
<script type="text/javascript">
 
    function extrusoraNumero(){ 
      var extrusora_mp =  "<?php echo $row_mezclaycaract_impresion['extrusora_mp']; ?>";
      if(extrusora_mp == "1 Maquina Extrusora") { 
         $('.zonaextruder1').show();
         $('.zonaextruder2').hide(); 

      }else if(extrusora_mp == "2 Maquina Extrusora"){  
 
         $('.zonaextruder1').hide();
         $('.zonaextruder2').show();  
      }
    }

</script>

<?php
mysql_free_result($usuario);
mysql_free_result($editar_m);
mysql_free_result($res);
mysql_free_result($unidad_uno);
mysql_free_result($unidad_dos);
mysql_free_result($unidad_tres);
mysql_free_result($unidad_cuatro);
mysql_free_result($unidad_cinco);
mysql_free_result($unidad_seis);
mysql_free_result($unidad_siete);
mysql_free_result($unidad_ocho);
mysql_free_result($caract_valor);
?>
