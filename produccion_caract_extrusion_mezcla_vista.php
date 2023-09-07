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

$colname_editar_m = "-1";
if (isset($_GET['id_pm'])) 
{
  $colname_editar_m= (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar_m = sprintf("SELECT * FROM Tbl_produccion_mezclas WHERE Tbl_produccion_mezclas.id_pm=%s AND Tbl_produccion_mezclas.b_borrado_pm='0'",$colname_editar_m);
$editar_m = mysql_query($query_editar_m, $conexion1) or die(mysql_error());
$row_editar_m = mysql_fetch_assoc($editar_m);
$totalRows_editar_m = mysql_num_rows($editar_m);

$colname_ref = "-1";
if (isset($_GET['id_pm'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_produccion_mezclas, Tbl_referencia WHERE Tbl_produccion_mezclas.id_pm=%s AND Tbl_produccion_mezclas.id_ref_pm=Tbl_referencia.id_ref AND Tbl_produccion_mezclas.b_borrado_pm='0'",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
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
     <td colspan="7" id="principal">PROCESO EXTRUSION MEZCLAS</td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="6" id="dato3"><a href="produccion_caract_extrusion_mezcla_edit.php?id_ref=<?php echo $row_editar_m['id_ref_pm']; ?>&amp;id_pm=<?php echo $row_editar_m['id_pm']; ?>&amp;cod_ref=<?php echo $row_editar_m['int_cod_ref_pm']; ?>"><img src="images/menos.gif" alt="EDITAR"title="EDITAR" border="0" /></a><a href="produccion_mezclas.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a>
   	  <?php
	  $ref_cv=$row_editar_m['id_ref_pm'];
	  $sqlcv="SELECT int_id_ref_mm, id_proceso_mm FROM Tbl_maestra_mezcla_caract WHERE int_id_ref_mm='$ref_cv' AND id_proceso_mm='1'"; 
	  $resultcv= mysql_query($sqlcv);
	  $numcv= mysql_num_rows($resultcv);
	  if($numcv < '1')
	  { ?>
      <a href="produccion_caract_extrusion_add.php?id_ref=<?php echo $row_ref['id_ref']; ?>&id_pm=<?php echo $row_editar_m['id_pm']; ?>" ><img src="images/c_rojo.gif" style="cursor:hand;" alt="FALTA CARACTERISTICA EXTRUSION" title="FALTA CARACTERISTICA EXTRUSION" border="0" /></a>
      <?php } else{ ?>
      <a href="produccion_caract_extrusion_mezcla_edit.php?id_ref=<?php echo $row_editar_m['id_ref_pm']; ?>&amp;id_pm=<?php echo $row_editar_m['id_pm']; ?>&amp;cod_ref=<?php echo $row_editar_m['int_cod_ref_pm']; ?>"><img src="images/c.gif" alt="EDITAR"title="EDITAR" border="0" /></a>
	  <?php }?>
    <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU" border="0"/></a><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="INPRIMIR"title="INPRIMIR" /></a></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">FECHA DE INGRESO </td>
    <td colspan="3" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td colspan="3" id="fuente2"><?php echo $row_editar_m['fecha_registro_pm'] ?></td>
    <td colspan="3" nowrap id="fuente2"><?php echo $row_editar_m['str_registro_pm']; ?></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">Referencia</td>
    <td colspan="3" id="subppal2">Version</td>
    </tr>
  <tr>
    <td colspan="3" nowrap id="fuente2"><?php echo $row_editar_m['int_cod_ref_pm']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_editar_m['version_ref_pm']; ?></td>
    </tr>
  <tr>
    <td colspan="6" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="7" id="subppal2">ESPECIFICACIONES DE MEZCLA</td>
    </tr>
  <tr>
    <td rowspan="2" id="subppal2">EXT-1 </td>
    <td colspan="2" id="subppal2">TORNILLO A</td>
    <td colspan="2" id="subppal2">TORNILLO B</td>
    <td colspan="2" id="subppal2">TORNILLO C</td>
    </tr>
  <tr>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
    <td id="subppal3">Referencia</td>
    <td id="subppal2">%</td>
  </tr>
  <tr>
    <td id="fuente2">Tolva A</td>
    <td id="fuente2"><?php 
	    $idinsumo=$row_editar_m['int_ref1_tol1_pm']; 
		$sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol1_porc1_pm']; ?></td>
    <td id="fuente2"><?php $idinsumo=$row_editar_m['int_ref2_tol1_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol1_porc2_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo= $row_editar_m['int_ref3_tol1_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}		
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol1_porc3_pm']; ?></td>
  </tr> 
<tr>
    <td id="fuente2">Tolva B</td>
    <td id="fuente2"><?php $idinsumo = $row_editar_m['int_ref1_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol2_porc1_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref2_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol2_porc2_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref3_tol2_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol2_porc3_pm'] ?></td>
  </tr>
  <tr>
    <td id="fuente2">Tolva C</td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref1_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol3_porc1_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref2_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol3_porc2_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref3_tol3_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol3_porc3_pm'] ?></td>
  </tr>
  <tr>
    <td id="fuente2">Tolva D</td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref1_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol4_porc1_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref2_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol4_porc2_pm'] ?></td>
    <td id="fuente2"><?php $idinsumo =  $row_editar_m['int_ref3_tol4_pm'];
	    $sqlm="SELECT descripcion_insumo FROM insumo WHERE insumo.id_insumo='$idinsumo'"; 
		$resultm=mysql_query($sqlm); 
		$numm=mysql_num_rows($resultm); 
		if($numm >= '1') 
		{ $nombreMezcla=mysql_result($resultm,0,'descripcion_insumo'); echo $nombreMezcla; }
		else { echo "";	
		}	
	 ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol4_porc3_pm'] ?></td>
  </tr>
  <tr>
    <td id="fuente2">RPM - %</td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_rpm_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref1_tol5_porc1_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_rpm_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref2_tol5_porc2_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_rpm_pm'] ?></td>
    <td id="fuente2"><?php echo $row_editar_m['int_ref3_tol5_porc3_pm'] ?></td>
  </tr> 
<tr>
  <td colspan="7" id="fuente3">Observaciones de Mezclas: <?php echo $row_editar_m['observ_pm'] ?></td>
</tr>
</table>

<table id="tablainterna">
  <tr>    
     <td colspan="9" id="principal">CARACTERISTICAS DE EXTRUSION </td>
  </tr>
  <!--<tr>
    <td colspan="4" id="subppal2">FECHA DE INGRESO </td>
    <td colspan="4" id="subppal2">RESPONSABLE</td>
  </tr>
  <tr>
    <td colspan="4" id="fuente2">fecha</td>
    <td colspan="4" nowrap id="fuente2">respon</td>
    </tr>-->
  <tr>
    <td colspan="4" id="subppal2">Opcion No 1</td>
    <td colspan="3" id="subppal2">Calibre</td>
    <td colspan="2" id="subppal2">Ancho material</td>
  </tr>
  <tr >
    <td colspan="2" id="subppal2">Boquilla de Extrusion</td>
    <td id="subppal2">Relacion Soplado (RS)</td>
    <td id="subppal2">Altura Linea Enfriamiento</td>
    <td colspan="2" id="subppal2">Calibre</td>
    <td id="subppal2">Micras</td>
    <td colspan="2" id="subppal2">&nbsp;Ancho</td>
    </tr>
    
    
   <?php  $id_ref=$_GET['id_ref']?>
<tr>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='3'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='4'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='5'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='6'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='7'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='1'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    </tr>
<tr>
  <td colspan="4" id="fuente">&nbsp;</td>
  <td id="subppal2">PRESENTACION</td>
  <td id="fuente2"><?php echo $row_ref['Str_presentacion']; ?></td>
  <td id="subppal2">PESO MILLAR</td>
  <td colspan="2" id="fuente2"><?php echo $row_ref['peso_millar_ref']; ?></td>
</tr> 
<tr>
  <td colspan="2" id="subppal2">Velocidad de Halado</td>
  <td colspan="2" id="subppal2">Tratamiento Corona</td>
  <td colspan="3" id="subppal2">Ubicaci&oacute;n Tratamiento</td>
  <td colspan="2" id="subppal2">Pigmentaci&oacute;n</td>
</tr>
    <tr>
      <td colspan="2" rowspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='8'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
      <td id="fuente3">Potencia: </td>
      <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='9'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
      <td colspan="2" id="fuente3">Cara Interior: </td>
      <td id="fuente3"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='11'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
      <td id="fuente3"> Interior: </td>
      <td id="fuente3"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='13'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    </tr>
    <tr>
      <td id="fuente3">Dinas: </td>
      <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='10'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
      <td colspan="2" id="fuente3">Cara Exterior: </td>
      <td id="fuente3"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='12'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
      <td id="fuente3">Exterior: </td>
      <td id="fuente3"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='14'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    </tr>
    <tr>
    <td colspan="2" id="subppal2">% Aire Anillo Enfriamiento</td>
    <td colspan="2" id="subppal2">Tension</td>
    <td colspan="5" id="subppal2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" rowspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='15'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
      <td id="fuente2">Sec Take Off</td>
      <td id="fuente2">Winder A</td>
      <td colspan="5" id="fuente2">Winder B</td>
    </tr>
    <tr>
      <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='16'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
      <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='17'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
      <td colspan="5" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='18'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    </tr>
    <tr>
      <td colspan="9" id="subppal2"><strong>TEMPERATURAS DE FUNDIDO EN TORNILLOS Y CABEZAL</strong></td>
    </tr>
    <tr>
      <td id="subppal2">&nbsp;</td>
      <td id="subppal2">TORNILLO A</td>
      <td id="subppal2">TORNILLO B</td>
      <td id="subppal2">TORNILLO C</td>
      <td colspan="3" id="subppal2">Cabezal (Die Head)</td>
      <td colspan="2" id="subppal2">&deg;C</td>
    </tr>
    <tr>
    <td id="fuente3">Barrel Zone 1</td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='19'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='20'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='21'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="3" id="fuente3">Share Lower</td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='43'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
  </tr> 
<tr>
    <td id="fuente3">Barrel Zone 2</td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='22'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='23'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='24'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="3" id="fuente3">Share Upper</td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='44'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
  </tr>
<tr>
    <td id="fuente3">Barrel Zone 3</td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='25'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='26'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='27'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="3" id="fuente3">L-Die</td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='45'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
  </tr>
<tr>
    <td id="fuente3">Barrel Zone 4</td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='28'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='29'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='30'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="3" id="fuente3">V- Die</td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='46'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
  </tr>
<tr>
    <td id="fuente3">Filter Front</td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='31'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='32'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='33'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="3" id="fuente3">Die Head</td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='47'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
  </tr>
<tr>
    <td id="fuente3">Filter Back</td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='34'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='35'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='36'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="3" id="fuente3">Die Lid</td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='48'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
  </tr>
<tr>
    <td id="fuente3">Sec- Barrel</td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='37'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='38'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='39'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="3" id="fuente3">Die Center Lower</td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='49'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
  </tr>
<tr>
    <td id="fuente3">Melt Temp &deg;C</td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='40'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='41'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='42'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
    <td colspan="3" id="fuente3">Die Center Upper</td>
    <td colspan="2" id="fuente2"><?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0'AND id_c_cv='50'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?></td>
  </tr> 
  <tr>
    <td colspan="9" id="subppal2">&nbsp;</td>
    </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($editar_m);
mysql_free_result($res);
?>
