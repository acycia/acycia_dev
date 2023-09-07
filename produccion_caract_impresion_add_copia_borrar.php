<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
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

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

	$und=array($_POST['und']);
    foreach($und as $key=>$v)
    $a[]= $v;
	
	$id=array($_POST['1'],$_POST['2'],$_POST['3'],$_POST['4'],$_POST['5'],$_POST['6'],$_POST['7'],$_POST['8'],$_POST['9'],$_POST['10'],$_POST['11'],$_POST['12'],$_POST['13'],$_POST['14'],$_POST['15'],$_POST['16']);
    foreach($id as $key=>$v)
    $b[]= $v;
	
	$valor=$_POST['valor'];
    foreach($valor as $key=>$v)
    $c[]= $v;	
		
	for($i=0; $i<count($b); $i++) 
    {
		if($a[$i]!=''&&$b[$i]!=''&&$c[$i]!=''){			 
  $insertSQL = sprintf("INSERT INTO Tbl_produccion_mezclas_impresion (id_proceso, fecha_registro_pmi, str_registro_pmi, id_ref_pmi, int_cod_ref_pmi, version_ref_pmi, und, id_i_pmi, int_valor_pmi, observ_pmi, b_borrado_pmi) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       
                       GetSQLValueString($_POST['id_proceso'], "int"),
                       GetSQLValueString($_POST['fecha_registro_pmi'], "date"),
                       GetSQLValueString($_POST['str_registro_pmi'], "text"),
                       GetSQLValueString($_POST['id_ref_pmi'], "int"),
                       GetSQLValueString($_POST['int_cod_ref_pmi'], "int"),
                       GetSQLValueString($_POST['version_ref_pmi'], "int"),
                       GetSQLValueString($a[$i], "int"),
					   GetSQLValueString($b[$i], "int"),
                       GetSQLValueString($c[$i], "double"),
                       GetSQLValueString($_POST['observ_pmi'], "text"),
                       GetSQLValueString($_POST['b_borrado_pmi'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
	}
  $insertGoTo = "produccion_caract_impresion_vista.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
          }
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//SELECT REFERENCIA
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_copia = "SELECT DISTINCT id_ref_pmi,int_cod_ref_pmi,id_proceso FROM Tbl_produccion_mezclas_impresion WHERE id_proceso='2' ORDER BY int_cod_ref_pmi DESC";
$referencia_copia = mysql_query($query_referencia_copia, $conexion1) or die(mysql_error());
$row_referencia_copia = mysql_fetch_assoc($referencia_copia);
$totalRows_referencia_copia = mysql_num_rows($referencia_copia);
//COLORES
mysql_select_db($database_conexion1, $conexion1);
$query_color = sprintf("SELECT * FROM Tbl_color_impresion ORDER BY id_ci ASC");
$color = mysql_query($query_color, $conexion1) or die(mysql_error());
$row_color = mysql_fetch_assoc($color);
$totalRows_color = mysql_num_rows($color);
//CONSULTA COLOR EGP
$colname_ref = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref=%s AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp ",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
//CARGA REF COPIA
$colname_ref_copia = "-1";
if (isset($_GET['ref'])) {
  $colname_ref_copia  = (get_magic_quotes_gpc()) ? $_GET['ref'] : addslashes($_GET['ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_copia = sprintf("SELECT * FROM Tbl_produccion_mezclas_impresion WHERE id_ref_pmi=%s",$colname_ref_copia);
$ref_copia = mysql_query($query_ref_copia, $conexion1) or die(mysql_error());
$row_ref_copia = mysql_fetch_assoc($ref_copia);
$totalRows_ref_copia = mysql_num_rows($ref_copia);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='8' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//MEZCLA IMPRESION
$colname_mezcla = "-1";
if (isset($_GET['id_pmi'])) {
  $colname_mezcla = (get_magic_quotes_gpc()) ? $_GET['id_pmi'] : addslashes($_GET['id_pmi']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_mezcla = sprintf("SELECT * FROM Tbl_produccion_mezclas_impresion WHERE id_pmi = %s AND b_borrado_pmi='0'", $colname_mezcla);
$mezcla = mysql_query($query_mezcla, $conexion1) or die(mysql_error());
$row_mezcla = mysql_fetch_assoc($mezcla);
$totalRows_mezcla = mysql_num_rows($mezcla);
//LLAMA LAS MEZCLAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo_impresion = "SELECT * FROM Tbl_mezclas ORDER BY id_m ASC";
$insumo_impresion= mysql_query($query_insumo_impresion, $conexion1) or die(mysql_error());
$row_insumo_impresion = mysql_fetch_assoc($insumo_impresion);
$totalRows_insumo_impresion = mysql_num_rows($insumo_impresion);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript">
function show_hide() {
if(document.getElementById('check_sh1').checked) {
document.getElementById('select_sh2').style.display = "none";
document.getElementById('select_sh2').disabled = true;
} else {

document.getElementById('select_sh2').style.visibility = "visible";
document.getElementById('select_sh2').style.display = "block";
document.getElementById('select_sh2').disabled = false;
}
}
</script>
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
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><!--<ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	<li><a href="menu.php">MENU PRINCIPAL</a></li>
	<li><a href="produccion_mezclas_add.php">EXTRUSION</a></li>		
	</ul>-->
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">   
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="9" id="titulo2">CARACTERISTICAS DE  IMPRESION</td>
        </tr>
      <tr>
        <td width="137" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="8" id="dato3"><a href="produccion_mezclas.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="produccion_referencias.php"><img src="images/rp.gif" style="cursor:hand;" alt="LISTADO REF. POR PROCESO" title="LISTADO REF. POR PROCESO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
      <tr id="tr1">
        <td width="182" colspan="2" nowrap="nowrap" id="fuente1">Fecha Ingreso 
          <input name="fecha_registro_cv" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus />
          </td>
        <td colspan="6" id="fuente1">
Ingresado por
  <input name="str_registro_cv" type="text" id="str_registro_cv" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
        </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="235" colspan="4" id="fuente2">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">Referencia</td>
        <td colspan="2" id="fuente2">Version</td>
        <td colspan="4" id="dato1"><input type="button" name="GENERAR COPIA" id="check_sh1" value="GENERAR COPIA" onclick="show_hide(this)"/></td>
      </tr>
      <tr>
        <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="id_ref_cv" id="id_ref_cv" value="<?php echo $row_ref['id_ref']; ?>"/>
          <input type="hidden" name="cod_ref_cv" id="cod_ref_cv" value="<?php echo $row_ref['cod_ref'] ?>"/>
          <?php echo $row_ref['cod_ref']; ?></td>
        <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="version_ref_cv" id="version_ref_cv" value="<?php echo $row_ref['version_ref'] ?>"/>
          <?php echo $row_ref['version_ref']; ?></td>
        <td colspan="4" id="fuente2"><select name="ref" id="select_sh2" onchange="if(form1.ref.value){ consulta_ref_impresion_add(); } else{ alert('Debe Seleccionar una REFERENCIA'); }"  style="visibility:hidden">
          <option value=""<?php if (!(strcmp("", $_GET['ref']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
          <?php
do {  
?>
          <option value="<?php echo $row_referencia_copia['id_ref_cv']?>"<?php if (!(strcmp($row_referencia_copia['cod_ref_cv'], $_GET['ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia_copia['cod_ref_cv']?></option>
          <?php
} while ($row_referencia_copia = mysql_fetch_assoc($referencia_copia));
  $rows = mysql_num_rows($referencia_copia);
  if($rows > 0) {
      mysql_data_seek($referencia_copia, 0);
	  $row_referencia_copia = mysql_fetch_assoc($referencia_copia);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="4" id="dato2"><datalist id="dias"></datalist></td>
      </tr>
      <tr id="tr1">
        <td colspan="12" id="titulo4">IMPRESION<?php $id_ref=$_GET['ref'];?></td>
        </tr>
      <tr>
        <td nowrap="nowrap"id="fuente1">IMP-1</td>
        <td nowrap="nowrap"id="fuente1">UNIDAD 1</td>
        <td nowrap="nowrap"id="fuente1">UNIDAD 2</td>
        <td nowrap="nowrap" id="fuente1">UNIDAD 3</td>
        <td nowrap="nowrap"id="fuente1">UNIDAD 4</td>
        <td nowrap="nowrap"id="fuente1">UNIDAD 5</td>
        <td nowrap="nowrap"id="fuente1">UNIDAD 6</td>
        <td nowrap="nowrap"id="fuente1">UNIDAD 7</td>
        <td  nowrap="nowrap"id="fuente1">UNIDAD 8</td>
     </tr>
     
     <tr> 
     <td>
     
   <table id="tablainterna"> 
   <tr>        
     <td id="detalle2">Material</td>
     </tr>
     <?php  for ($i=0;$i<=$totalRows_insumo_impresion-1;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      echo "<tr><td id='fuente3'>$nombre</td></tr>";
      }
      ?>        
     </table>
     
       </td>
       <td>
    <table id="tablainterna"> 
     <?php  for ($i=0;$i<=0;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      ?>
     <tr>
        <td id="fuente1"><input name="und[]" type="hidden" id="und2" value="1" />          <input name="1[]"  type="text"  id="1[]" placeholder="Color" size="3"value="<?php echo $row_ref['color1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="2[]" id="2[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="3[]" id="3[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
        <td id="fuente1"><select name="4[]" id="4[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="5[]"  type="text"  id="5[]" placeholder="Pant" size="3" value="<?php echo $row_ref['pantone1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="6[]" id="6[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='56'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="7[]" id="7[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='57'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="8[]"  type="text"  id="8[]" placeholder="Anilox" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='58'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="9[]"  type="text"  id="9[]" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="10[]"  type="text"  id="10[]" placeholder="Alcohol" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="11[]"  type="text"  id="11[]" placeholder="Acetato" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="12[]"  type="text"  id="12[]" placeholder="Solvente" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="13[]"  type="text"  id="13[]" placeholder="Barnis" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="14[]"  type="text"  id="14[]" placeholder="Base" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="15[]"  type="text"  id="15[]" placeholder="Flejes" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="16[]"  type="text"  id="16[]" placeholder="Opturador" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            
      <tr>	  
	  <?php }?>        
     </table>
     
       </td>      
       <td><table id="tablainterna"> 
     <?php  for ($i=0;$i<=0;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      ?>
     <tr>
        <td id="fuente1"><input name="1[]"  type="text"  id="1[]" placeholder="Color" size="3"value="<?php echo $row_ref['color1_egp'] ?>"/>
          <input name="und" type="hidden" id="und" value="2" /></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="2[]" id="2[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="3[]" id="3[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
        <td id="fuente1"><select name="4[]" id="4[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="5[]"  type="text"  id="5[]" placeholder="Pant" size="3" value="<?php echo $row_ref['pantone1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="6[]" id="6[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='56'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="7[]" id="7[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='57'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="8[]"  type="text"  id="8[]" placeholder="Anilox" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='58'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="9[]"  type="text"  id="9[]" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="10[]"  type="text"  id="10[]" placeholder="Alcohol" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="11[]"  type="text"  id="11[]" placeholder="Acetato" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="12[]"  type="text"  id="12[]" placeholder="Solvente" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="13[]"  type="text"  id="13[]" placeholder="Barnis" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="14[]"  type="text"  id="14[]" placeholder="Base" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="15[]"  type="text"  id="15[]" placeholder="Flejes" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="16[]"  type="text"  id="16[]" placeholder="Opturador" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            
      <tr>	  
	  <?php 
      }
      ?>        
     </table></td> 

       <td><table id="tablainterna"> 
     <?php  for ($i=0;$i<=0;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      ?>
     <tr>
        <td id="fuente1"><input name="1[]"  type="text"  id="1[]" placeholder="Color" size="3"value="<?php echo $row_ref['color1_egp'] ?>"/>
          <input name="und" type="hidden" id="und" value="3" /></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="2[]" id="2[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="3[]" id="3[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
        <td id="fuente1"><select name="4[]" id="4[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="5[]"  type="text"  id="5[]" placeholder="Pant" size="3" value="<?php echo $row_ref['pantone1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="6[]" id="6[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='56'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="7[]" id="7[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='57'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="8[]"  type="text"  id="8[]" placeholder="Anilox" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='58'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="9[]"  type="text"  id="9[]" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="10[]"  type="text"  id="10[]" placeholder="Alcohol" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="11[]"  type="text"  id="11[]" placeholder="Acetato" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="12[]"  type="text"  id="12[]" placeholder="Solvente" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="13[]"  type="text"  id="13[]" placeholder="Barnis" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="14[]"  type="text"  id="14[]" placeholder="Base" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="15[]"  type="text"  id="15[]" placeholder="Flejes" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="16[]"  type="text"  id="16[]" placeholder="Opturador" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            
      <tr>	  
	  <?php 
      }
      ?>        
     </table></td>      
       <td><table id="tablainterna"> 
     <?php  for ($i=0;$i<=0;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      ?>
     <tr>
        <td id="fuente1"><input name="1[]"  type="text"  id="1[]" placeholder="Color" size="3"value="<?php echo $row_ref['color1_egp'] ?>"/>
          <input name="und" type="hidden" id="und" value="4" /></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="2[]" id="2[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="3[]" id="3[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
        <td id="fuente1"><select name="4[]" id="4[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="5[]"  type="text"  id="5[]" placeholder="Pant" size="3" value="<?php echo $row_ref['pantone1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="6[]" id="6[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='56'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="7[]" id="7[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='57'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="8[]"  type="text"  id="8[]" placeholder="Anilox" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='58'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="9[]"  type="text"  id="9[]" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="10[]"  type="text"  id="10[]" placeholder="Alcohol" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="11[]"  type="text"  id="11[]" placeholder="Acetato" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="12[]"  type="text"  id="12[]" placeholder="Solvente" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="13[]"  type="text"  id="13[]" placeholder="Barnis" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="14[]"  type="text"  id="14[]" placeholder="Base" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="15[]"  type="text"  id="15[]" placeholder="Flejes" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="16[]"  type="text"  id="16[]" placeholder="Opturador" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            
      <tr>	  
	  <?php 
      }
      ?>        
     </table></td>        
      <td><table id="tablainterna"> 
     <?php  for ($i=0;$i<=0;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      ?>
     <tr>
        <td id="fuente1"><input name="1[]"  type="text"  id="1[]" placeholder="Color" size="3"value="<?php echo $row_ref['color1_egp'] ?>"/>
          <input name="und" type="hidden" id="und" value="5" /></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="2[]" id="2[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="3[]" id="3[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
        <td id="fuente1"><select name="4[]" id="4[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="5[]"  type="text"  id="5[]" placeholder="Pant" size="3" value="<?php echo $row_ref['pantone1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="6[]" id="6[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='56'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="7[]" id="7[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='57'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="8[]"  type="text"  id="8[]" placeholder="Anilox" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='58'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="9[]"  type="text"  id="9[]" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="10[]"  type="text"  id="10[]" placeholder="Alcohol" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="11[]"  type="text"  id="11[]" placeholder="Acetato" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="12[]"  type="text"  id="12[]" placeholder="Solvente" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="13[]"  type="text"  id="13[]" placeholder="Barnis" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="14[]"  type="text"  id="14[]" placeholder="Base" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="15[]"  type="text"  id="15[]" placeholder="Flejes" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="16[]"  type="text"  id="16[]" placeholder="Opturador" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            
      <tr>	  
	  <?php 
      }
      ?>        
     </table></td>    
      <td><table id="tablainterna"> 
     <?php  for ($i=0;$i<=0;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      ?>
     <tr>
        <td id="fuente1"><input name="1[]"  type="text"  id="1[]" placeholder="Color" size="3"value="<?php echo $row_ref['color1_egp'] ?>"/>
          <input name="und" type="hidden" id="und" value="6" /></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="2[]" id="2[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="3[]" id="3[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
        <td id="fuente1"><select name="4[]" id="4[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="5[]"  type="text"  id="5[]" placeholder="Pant" size="3" value="<?php echo $row_ref['pantone1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="6[]" id="6[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='56'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="7[]" id="7[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='57'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="8[]"  type="text"  id="8[]" placeholder="Anilox" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='58'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="9[]"  type="text"  id="9[]" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="10[]"  type="text"  id="10[]" placeholder="Alcohol" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="11[]"  type="text"  id="11[]" placeholder="Acetato" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="12[]"  type="text"  id="12[]" placeholder="Solvente" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="13[]"  type="text"  id="13[]" placeholder="Barnis" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="14[]"  type="text"  id="14[]" placeholder="Base" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="15[]"  type="text"  id="15[]" placeholder="Flejes" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="16[]"  type="text"  id="16[]" placeholder="Opturador" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            
      <tr>	  
	  <?php 
      }
      ?>        
     </table></td>    
      <td><table id="tablainterna"> 
     <?php  for ($i=0;$i<=0;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      ?>
     <tr>
        <td id="fuente1"><input name="1[]"  type="text"  id="1[]" placeholder="Color" size="3"value="<?php echo $row_ref['color1_egp'] ?>"/>
          <input name="und" type="hidden" id="und" value="7" /></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="2[]" id="2[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="3[]" id="3[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
        <td id="fuente1"><select name="4[]" id="4[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="5[]"  type="text"  id="5[]" placeholder="Pant" size="3" value="<?php echo $row_ref['pantone1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="6[]" id="6[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='56'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="7[]" id="7[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='57'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="8[]"  type="text"  id="8[]" placeholder="Anilox" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='58'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="9[]"  type="text"  id="9[]" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="10[]"  type="text"  id="10[]" placeholder="Alcohol" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="11[]"  type="text"  id="11[]" placeholder="Acetato" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="12[]"  type="text"  id="12[]" placeholder="Solvente" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="13[]"  type="text"  id="13[]" placeholder="Barnis" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="14[]"  type="text"  id="14[]" placeholder="Base" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="15[]"  type="text"  id="15[]" placeholder="Flejes" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="16[]"  type="text"  id="16[]" placeholder="Opturador" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            
      <tr>	  
	  <?php 
      }
      ?>        
     </table></td>    
      <td><table id="tablainterna"> 
     <?php  for ($i=0;$i<=0;$i++) { ?>
     <?php  
	  $id_m=mysql_result($insumo_impresion,$i,id_m);
	  $nombre=mysql_result($insumo_impresion,$i,str_nombre_m);
      ?>
     <tr>
        <td id="fuente1"><input name="1[]"  type="text"  id="1[]" placeholder="Color" size="3"value="<?php echo $row_ref['color1_egp'] ?>"/>
          <input name="und" type="hidden" id="und" value="8" /></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="2[]" id="2[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['52']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="3[]" id="3[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['53']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
        <td id="fuente1"><select name="4[]" id="4[]" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>>Color</option>
            <?php
do {  
?>
            <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['54']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
            <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="5[]"  type="text"  id="5[]" placeholder="Pant" size="3" value="<?php echo $row_ref['pantone1_egp'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="6[]" id="6[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='56'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><select name="7[]" id="7[]" style="width:50px">
          <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='57'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="8[]"  type="text"  id="8[]" placeholder="Anilox" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='58'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="9[]"  type="text"  id="9[]" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="10[]"  type="text"  id="10[]" placeholder="Alcohol" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="11[]"  type="text"  id="11[]" placeholder="Acetato" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="12[]"  type="text"  id="12[]" placeholder="Solvente" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="13[]"  type="text"  id="13[]" placeholder="Barnis" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="14[]"  type="text"  id="14[]" placeholder="Base" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="15[]"  type="text"  id="15[]" placeholder="Flejes" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            <tr>
        <td id="fuente1"><input name="16[]"  type="text"  id="16[]" placeholder="Opturador" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='59'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
            
      <tr>	  
	  <?php 
      }
      ?>        
     </table></td>                                
             
      </tr>     
     
     
 

      <tr>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td rowspan="3" id="fuente1">Estacion 1</td>
        <td rowspan="3" id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td rowspan="3" id="fuente1">&nbsp;</td>
        <td rowspan="3" id="fuente1">&nbsp;</td>
        <td colspan="2" rowspan="3" id="fuente1">&nbsp;</td>
        <td rowspan="3" id="fuente1">&nbsp;</td>
        <td colspan="2" rowspan="3" id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td rowspan="3" id="fuente1">Estacion 2</td>
        <td rowspan="3" id="fuente1"><input name="n60" type="hidden" id="n60" value="60" />          <input name="60"  type="text"  id="60" placeholder="Color" size="3"value="<?php echo $row_ref['color2_egp'] ?>"/></td>
        <td id="fuente1"><input name="n61" type="hidden" id="n61" value="61" />
          <select name="61" id="61" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['61']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['61']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n64" type="hidden" id="n64" value="64" />          <input name="64"  type="text"  id="64" placeholder="Pant" size="3"value="<?php echo $row_ref['pantone2_egp'] ?>"/></td>
        <td rowspan="3" id="fuente1"><input name="n65" type="hidden" id="n65" value="65" />
          <select name="65" id="65" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='65'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var2=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var2))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var2))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n66" type="hidden" id="n66" value="66" />
          <select name="66" id="66" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='66'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n67" type="hidden" id="n67" value="67" />          <input name="67"  type="text"  id="67" placeholder="Anilox" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='67'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n68" type="hidden" id="n68" value="68" />          <input name="68"  type="text"  id="68" placeholder="Visc" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='68'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><input name="n62" type="hidden" id="n62" value="62" />
          <select name="62" id="62" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['62']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['62']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><input name="n63" type="hidden" id="n63" value="63" />
          <select name="63" id="63" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['63']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['63']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td rowspan="3" id="fuente1">Estacion 3</td>
        <td rowspan="3" id="fuente1"><input name="n69" type="hidden" id="n69" value="69" />          <input name="69"  type="text"  id="69" placeholder="Color" size="3"value="<?php echo $row_ref['color3_egp'] ?>"/></td>
        <td id="fuente1"><input name="n70" type="hidden" id="n70" value="70" />
          <select name="70" id="70" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['70']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['70']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n73" type="hidden" id="n73" value="73" />          <input name="73"  type="text"  id="73" placeholder="Pant" size="3"value="<?php echo $row_ref['pantone3_egp'] ?>"/></td>
        <td rowspan="3" id="fuente1"><input name="n74" type="hidden" id="n74" value="74" />
          <select name="74" id="74" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='74'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n75" type="hidden" id="n75" value="75" />
          <select name="75" id="75" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='75'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n76" type="hidden" id="n76" value="76" />          <input name="76"  type="text"  id="76" placeholder="Anilox" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='76'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n77" type="hidden" id="n77" value="77" />          <input name="77"  type="text"  id="77" placeholder="Visc" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='77'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="n71" type="hidden" id="n71" value="71" />
          <select name="71" id="71" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['71']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['71']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="n72" type="hidden" id="n72" value="72" />
          <select name="72" id="72" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['72']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['72']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr id="tr1">
        <td rowspan="3" id="fuente1">Estacion 4</td>
        <td rowspan="3" id="fuente1"><input name="n78" type="hidden" id="n78" value="78" />          <input name="78"  type="text"  id="78" placeholder="Color" size="3"value="<?php echo $row_ref['color4_egp'] ?>"/></td>
        <td id="fuente1"><input name="n79" type="hidden" id="n79" value="79" />
          <select name="79" id="79" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['79']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['79']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n82" type="hidden" id="n82" value="82" />          <input name="82"  type="text"  id="82" placeholder="Pant" size="3"value="<?php echo $row_ref['pantone4_egp'] ?>"/></td>
        <td rowspan="3" id="fuente1"><input name="n83" type="hidden" id="n83" value="83" />
          <select name="83" id="83" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='83'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var3=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var3))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var3))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n84" type="hidden" id="n84" value="84" />
          <select name="84" id="84" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='84'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var4=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var4))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n85" type="hidden" id="n85" value="85" />          <input name="85"  type="text"  id="85" placeholder="Anilox" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='85'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n86" type="hidden" id="n86" value="86" />          <input name="86"  type="text"  id="86" placeholder="Visc" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='86'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><input name="n80" type="hidden" id="n80" value="80" />
          <select name="80" id="80" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['80']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['80']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><input name="n81" type="hidden" id="n81" value="81" />
          <select name="81" id="81" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['81']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['81']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td rowspan="3" id="fuente1">Estacion 5</td>
        <td rowspan="3" id="fuente1"><input name="n87" type="hidden" id="n87" value="87" />          <input name="87"  type="text"  id="87" placeholder="Color" size="3"value="<?php echo $row_ref['color5_egp'] ?>"/></td>
        <td id="fuente1"><input name="n88" type="hidden" id="n88" value="88" />
          <select name="88" id="88" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['88']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['88']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n91" type="hidden" id="n91" value="91" />          <input name="91"  type="text"  id="91" placeholder="Pant" size="3"value="<?php echo $row_ref['pantone5_egp'] ?>"/></td>
        <td rowspan="3" id="fuente1"><input name="n92" type="hidden" id="n92" value="92" />
          <select name="92" id="92" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='92'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n93" type="hidden" id="n93" value="93" />
          <select name="93" id="93" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='93'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n94" type="hidden" id="n94" value="94" />          <input name="94"  type="text"  id="94" placeholder="Anilox" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='94'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n95" type="hidden" id="n95" value="95" />          <input name="95"  type="text"  id="95" placeholder="Visc" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='95'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="n89" type="hidden" id="n89" value="89" />
          <select name="89" id="89" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['89']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['89']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="n90" type="hidden" id="n90" value="90" />
          <select name="90" id="90" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['90']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['90']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
      </tr>
<tr id="tr1">
        <td rowspan="3" id="fuente1">Estacion 6</td>
        <td rowspan="3" id="fuente1"><input name="n96" type="hidden" id="n96" value="96" />          <input name="96"  type="text"  id="96" placeholder="Color" size="3"value="<?php echo $row_ref['color6_egp'] ?>"/></td>
        <td id="fuente1"><input name="n97" type="hidden" id="n97" value="97" />
          <select name="97" id="97" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['97']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['97']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n100" type="hidden" id="n100" value="100" />          <input name="100"  type="text"  id="100" placeholder="Pant" size="3"value="<?php echo $row_ref['pantone6_egp'] ?>"/></td>
        <td rowspan="3" id="fuente1"><input name="n101" type="hidden" id="n101" value="101" />
          <select name="101" id="101" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='101'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n102" type="hidden" id="n102" value="102" />
          <select name="102" id="102" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='102'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td rowspan="3" id="fuente1"><input name="n103" type="hidden" id="n103" value="103" />          <input name="103"  type="text"  id="103" placeholder="Anilox" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='103'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n104" type="hidden" id="n104" value="104" />          <input name="104"  type="text"  id="104" placeholder="Visc" size="3" value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='104'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
<tr id="tr1">
  <td id="fuente1"><input name="n98" type="hidden" id="n98" value="98" />
    <select name="98" id="98" style="width:50px">
    <option value=""<?php if (!(strcmp("", $row_ref_copia['98']))) {echo "selected=\"selected\"";} ?>>Color</option>
    <?php
do {  
?>
    <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['98']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
    <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
  </select></td>
</tr>
<tr id="tr1">
  <td id="fuente1"><input name="n99" type="hidden" id="n99" value="99" />
    <select name="99" id="99" style="width:50px">
    <option value=""<?php if (!(strcmp("", $row_ref_copia['99']))) {echo "selected=\"selected\"";} ?>>Color</option>
    <?php
do {  
?>
    <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['99']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
    <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
  </select></td>
</tr>
      <tr>
        <td rowspan="3" id="fuente1">Estacion 7</td>
        <td rowspan="3" id="fuente1"><input name="n105" type="hidden" id="n105" value="105" />          <input name="105"  type="text"  id="105" placeholder="Color" size="3"value="<?php echo $row_ref['color7_egp'] ?>"/></td>
        <td id="fuente1"><input name="n106" type="hidden" id="n106" value="106" />
          <select name="106" id="106" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['106']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['106']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
        <td rowspan="3" id="fuente1"><input name="n109" type="hidden" id="n109" value="109" />          <input name="109"  type="text"  id="109" placeholder="Pant" size="3"value="<?php echo $row_ref['pantone7_egp'] ?>"/></td>
        <td rowspan="3" id="fuente1"><input name="n110" type="hidden" id="n110" value="110" />
          <select name="110" id="110" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='110'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var4=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var4))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var4))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n111" type="hidden" id="n111" value="111" />
          <select name="111" id="111" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='111'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
        <td rowspan="3" id="fuente1"><input name="n112" type="hidden" id="n112" value="112" />          <input name="112"  type="text"  id="112" placeholder="Anilox" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='112'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n113" type="hidden" id="n113" value="113" />          <input name="113"  type="text"  id="113" placeholder="Visc" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='113'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="n107" type="hidden" id="n107" value="107" />
          <select name="107" id="107" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['107']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['107']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
      <tr>
        <td id="fuente1"><input name="n108" type="hidden" id="n108" value="108" />
          <select name="108" id="108" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['108']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['108']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
      <tr id="tr1">
        <td rowspan="3" id="fuente1">Estacion 8</td>
        <td rowspan="3" id="fuente1"><input name="n114" type="hidden" id="n114" value="114" />          <input name="114"  type="text"  id="114" placeholder="Color" size="3"value="<?php echo $row_ref['color8_egp'] ?>"/></td>
        <td id="fuente1"><input name="n115" type="hidden" id="n115" value="115" />
          <select name="115" id="115" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['115']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['115']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
        <td rowspan="3" id="fuente1"><input name="n118" type="hidden" id="n118" value="118" />          <input name="118"  type="text"  id="118" placeholder="Pant" size="3"value="<?php echo $row_ref['pantone8_egp'] ?>"/></td>
        <td rowspan="3" id="fuente1"><input name="n119" type="hidden" id="n119" value="119" />
          <select name="119" id="119" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='119'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n120" type="hidden" id="n120" value="120" />
          <select name="120" id="120" style="width:50px">
         <?php if($id_ref!=''){$cons="SELECT * FROM Tbl_caracteristicas_valor WHERE id_ref_cv='$id_ref' AND id_c_cv='120'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res); $var=$num['str_valor_cv'];}?>
          <option value=""<?php if (!(strcmp("", $var))) {echo "selected=\"selected\"";} ?>>Ref.Stick</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['descripcion_insumo']?>"<?php if (!(strcmp($row_materia_prima['descripcion_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
      </select></td>
        <td rowspan="3" id="fuente1"><input name="n121" type="hidden" id="n121" value="121" />          <input name="121"  type="text"  id="121" placeholder="Anilox" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='121'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
        <td colspan="2" rowspan="3" id="fuente1"><input name="n122" type="hidden" id="n122" value="122" />          <input name="122"  type="text"  id="122" placeholder="Visc" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='122'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><input name="n116" type="hidden" id="n116" value="116" />
          <select name="116" id="116" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['116']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['116']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1"><input name="n117" type="hidden" id="n117" value="117" />
          <select name="117" id="117" style="width:50px">
          <option value=""<?php if (!(strcmp("", $row_ref_copia['117']))) {echo "selected=\"selected\"";} ?>>Color</option>
          <?php
do {  
?>
          <option value="<?php echo $row_color['color_ci']?>"<?php if (!(strcmp($row_color['color_ci'], $row_ref_copia['117']))) {echo "selected=\"selected\"";} ?>><?php echo $row_color['color_ci']?></option>
          <?php
} while ($row_color = mysql_fetch_assoc($color));
  $rows = mysql_num_rows($color);
  if($rows > 0) {
      mysql_data_seek($color, 0);
	  $row_color = mysql_fetch_assoc($color);
  }
?>
      </select></td>
      </tr>      
      <tr>
        <td id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="2" id="fuente1"></td>
        <td colspan="4" id="fuente1"></td>
      </tr>
<tr>
  <td rowspan="2" id="fuente1">Cantidad de Unidades</td>
      <td rowspan="2" id="fuente1"><input name="n123" type="hidden" id="n123" value="123" />        <input name="123"  type="text"  id="123" placeholder="Cant/Und" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='123'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      <td colspan="2" rowspan="2" id="fuente1">Cms</td>
      <td rowspan="2" id="fuente2">Arte Aprobado</td>
      <td colspan="2" rowspan="2" id="fuente2">Entrega con Bobinado N.</td>
      <td id="fuente1">Velocidad M/m</td>
      <td id="fuente1"><input name="n124" type="hidden" id="n124" value="124" />        <input name="124"  type="text"  id="124" placeholder="Visc M/m" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='124'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
    </tr>
    <tr>
      <td id="fuente1">Temp Secado &deg;C</td>
      <td id="fuente1"><input name="n125" type="hidden" id="n125" value="125" />        <input name="125"  type="text"  id="125" placeholder="Temp Sec" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='125'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">Rodillo de Ancho</td>
      <td id="fuente1"><input name="n126" type="hidden" id="n126" value="126" />        <input name="126"  type="text"  id="126" placeholder="Rodill" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='126'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      <td colspan="2" id="fuente1">Cms Per&iacute;metro</td>
      <td id="fuente2"><input name="n127" type="hidden" id="n127" value="127" />        <input name="127"  type="text"  id="127" placeholder="Arte Ap" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='127'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      <td colspan="2" id="fuente2"><input name="n128" type="hidden" id="n128" value="128" />        <input name="128"  type="text"  id="128" placeholder="Entrega B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='128'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      <td id="fuente2">Guia Fotocelda</td>
      <td id="fuente1"><input name="n129" type="hidden" id="n129" value="129" />        <input name="129"  type="text"  id="129" placeholder="Guia Fot" size="3"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='129'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
    </tr>
    <tr>
      <td colspan="2" id="fuente1">Repeticion en Perimetro</td>
      <td colspan="2" id="fuente1"><input name="n130" type="hidden" id="n130" value="130" />        <input name="130"  type="text"  id="130" placeholder="Repet" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='130'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/></td>
      <td colspan="3" id="fuente1">&nbsp;</td>
      <td colspan="2" id="fuente2">Ver montaje en Dise&ntilde;o y Desarrollo</td>
      
      <tr>
        <td colspan="12" id="fuente2">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="12" id="fuente2">
        <input type="hidden" name="id_ref" id="id_ref" value="<?php echo $row_ref['id_ref']; ?>"/>
        <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_ref['cod_ref']; ?>"/>
        <input type="hidden" name="id_pm_cv" id="id_pm_cv" value="<?php echo $row_mezcla['id_pm'] ?>"/>
        <input type="hidden" name="id_proceso_cv" id="id_proceso_cv" value="2"/>
        <input type="hidden" name="b_borrado_cv" id="b_borrado_cv" value="0"/>
        <input type="submit" name="GUARDAR" id="GUARDAR" value="GUARDAR" /></td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
    </form>
  </td></tr></table>
  
  
  </div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($referencia_copia);

mysql_free_result($color);

mysql_free_result($ref);

mysql_free_result($ref_copia);

mysql_free_result($materia_prima);

?>
