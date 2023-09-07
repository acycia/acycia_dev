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

	$und=$_POST['und'];
    foreach($und as $key=>$v)
    $a[]= $v;
	
	$id=$_POST['id_i_pmi'];
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
                       GetSQLValueString($c[$i], "int"),
                       GetSQLValueString($_POST['observ_pmi'], "text"),
                       GetSQLValueString($_POST['b_borrado_pmi'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
	}
  $insertGoTo = "produccion_caract_impresion_add.php";
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

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM Tbl_produccion_mezclas_impresion ORDER BY id_pmi DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_ref = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref=%s",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);

//SELECT REFERENCIA
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT DISTINCT id_ref_pmi,int_cod_ref_pmi FROM Tbl_produccion_mezclas_impresion  ORDER BY int_cod_ref_pmi DESC";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

//CARGA REF
$colname_ref_copia = "-1";
if (isset($_GET['ref'])) {
  $colname_ref_copia  = (get_magic_quotes_gpc()) ? $_GET['ref'] : addslashes($_GET['ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_copia = sprintf("SELECT * FROM Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi=%s",$colname_ref_copia);
$ref_copia = mysql_query($query_ref_copia, $conexion1) or die(mysql_error());
$row_ref_copia = mysql_fetch_assoc($ref_copia);
$totalRows_ref_copia = mysql_num_rows($ref_copia);


//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_insumo = "SELECT * FROM insumo WHERE clase_insumo='8' ORDER BY descripcion_insumo ASC";
$insumo = mysql_query($query_insumo, $conexion1) or die(mysql_error());
$row_insumo = mysql_fetch_assoc($insumo);
$totalRows_insumot = mysql_num_rows($insumo);
//LLAMA LAS UNIDADES DE IMPRESION
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM Tbl_insumo_impresion ORDER BY id_i ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);

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
        <td colspan="7" id="titulo2">PROCESO IMPRESION MEZCLAS</td>
        </tr>
      <tr>
        <td width="137" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="6" id="dato3"><a href="produccion_mezclas_impresion.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas_impresion.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="produccion_referencias_impresion.php"><img src="images/rp.gif" style="cursor:hand;" alt="LISTADO REF. POR PROCESO" title="LISTADO REF. POR PROCESO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
      <tr id="tr1">
        <td width="182" colspan="2" nowrap="nowrap" id="fuente1">Fecha Ingreso 
          <input name="fecha_registro_pmi" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus />
          </td>
        <td colspan="4" id="fuente1">
Ingresado por
  <input name="str_registro_pmi" type="text" id="str_registro_pmi" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/>
    <?php $numero=$row_ultimo['id_pmi']+1;  $numero; ?>
  <input type="hidden" name="id_pmi" id="id_pmi" value="<?php echo $numero; ?>"/></td>
        </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
        <td width="235" colspan="2" id="fuente2">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="fuente2">Referencia:</td>
        <td colspan="2" id="fuente2">Version:</td>
        <td colspan="2" id="dato1"><input type="button" name="GENERAR COPIA" id="check_sh1" value="GENERAR COPIA" onclick="show_hide(this)"/></td>
      </tr>
      <tr>
        <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="id_ref_pmi" id="id_ref_pmi" value="<?php echo $row_ref['id_ref'] ?>"/>
          <input type="hidden" name="int_cod_ref_pmi" id="int_cod_ref_pmi" value="<?php echo $row_ref['cod_ref']; ?>" />
          <?php echo $row_ref['cod_ref']; ?></td>
        <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="version_ref_pmi" id="version_ref_pmi" value="<?php echo $row_ref['version_ref']; ?>" />
          <?php echo $row_ref['version_ref']; ?></td>
        <td colspan="2" id="fuente2">
        <select name="ref" id="select_sh2" onchange="if(form1.ref.value){ consulta_ref_mezcla_impresion(); } else{ alert('Debe Seleccionar una REFERENCIA'); }"  style="visibility:hidden">
          <option value=""<?php if (!(strcmp("", $_GET['ref']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
          <?php
do {  
?>
          <option value="<?php echo $row_referencia['id_ref_pmi']?>"<?php if (!(strcmp($row_referencia['int_cod_ref_pmi'], $_GET['ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia['int_cod_ref_pmi']?></option>
          <?php
} while ($row_referencia = mysql_fetch_assoc($referencia));
  $rows = mysql_num_rows($referencia);
  if($rows > 0) {
      mysql_data_seek($referencia, 0);
	  $row_referencia = mysql_fetch_assoc($referencia);
  }
?>
        </select></td>
      </tr>
      <tr>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="2" id="dato2"><datalist id="dias"></datalist></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="titulo4">IMPRESION</td>
        </tr>
     <tr> 
     <td colspan="10">
     
   <table id="tablainterna"> 
   <tr>        
     <td id="detalle2">Material</td>
      <td nowrap id="detalle2">Unidad N&deg;1</td>
      <td nowrap id="detalle2">Unidad N&deg;2</td>
      <td nowrap id="detalle2">Unidad N&deg;3</td>
      <td nowrap id="detalle2">Unidad N&deg;4</td>
      <td nowrap id="detalle2">Unidad N&deg;5</td>
      <td nowrap id="detalle2">Unidad N&deg;6</td>
      <td nowrap id="detalle2">Unidad N&deg;7</td>
      <td nowrap id="detalle2">Unidad N&deg;8</td>
     </tr>
     <?php  for ($i=0;$i<=$totalRows_materia_prima-1;$i++) { ?>
     <?php  
	  $id_i=mysql_result($materia_prima,$i,id_i);
	  $nombre=mysql_result($materia_prima,$i,nombre_insumo_i);
      echo "<tr><td id='fuente3'>$nombre</td>";
	  echo "<td id='fuente1'><input name='und[]' type='hidden' size='2' value='1'/><input name='id_i_pmi[]' type='hidden' size='2' value='$id_i'/><input name='valor[]' type='text' size='2' value=''/></td>
	        <td id='fuente1'><input name='und[]' type='hidden' size='2' value='2'/><input name='id_i_pmi[]' type='hidden' size='2' value='$id_i'/><input name='valor[]' type='text' size='2' value=''/></td>
			<td id='fuente1'><input name='und[]' type='hidden' size='2' value='3'/><input name='id_i_pmi[]' type='hidden' size='2' value='$id_i'/><input name='valor[]' type='text' size='2' value=''/></td>
			<td id='fuente1'><input name='und[]' type='hidden' size='2' value='4'/><input name='id_i_pmi[]' type='hidden' size='2' value='$id_i'/><input name='valor[]' type='text' size='2' value=''/></td>
			<td id='fuente1'><input name='und[]' type='hidden' size='2' value='5'/><input name='id_i_pmi[]' type='hidden' size='2' value='$id_i'/><input name='valor[]' type='text' size='2' value=''/></td>
			<td id='fuente1'><input name='und[]' type='hidden' size='2' value='6'/><input name='id_i_pmi[]' type='hidden' size='2' value='$id_i'/><input name='valor[]' type='text' size='2' value=''/></td>
			<td id='fuente1'><input name='und[]' type='hidden' size='2' value='7'/><input name='id_i_pmi[]' type='hidden' size='2' value='$id_i'/><input name='valor[]' type='text' size='2' value=''/></td>
			<td id='fuente1'><input name='und[]' type='hidden' size='2' value='8'/><input name='id_i_pmi[]' type='hidden' size='2' value='$id_i'/><input name='valor[]' type='text' size='2' value=''/></td>
	        </tr>";
      }
      ?>
    <tr>
      <td id="fuente3">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td> 
      </tr>          
     </table>
     
       </td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="fuente2">OBSERVACIONES</td>
        </tr>
      <tr>
        <td colspan="10" id="fuente2"><textarea name="observ_pm" id="observ_pm" cols="45" rows="2"placeholder="OBSERVACIONES"onblur="conMayusculas(this)"></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="10" id="fuente2">
        <input type="hidden" name="id_ref" id="id_ref" value="<?php echo $row_ref['id_ref']; ?>"/>
        <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_ref['cod_ref']; ?>"/>
        <input type="hidden" name="id_proceso" id="id_proceso" value="2"/>
          <input type="hidden" name="b_borrado_pmi" id="b_borrado_pmi" value="0"/><input type="submit" name="SIGUIENTE" id="SIGUIENTE" value="SIGUIENTE" /></td>
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
mysql_free_result($ultimo);
mysql_free_result($referencia);
mysql_free_result($materia_prima);
mysql_free_result($formula_ref);
mysql_free_result($ref_copia);
mysql_free_result($ref);

?>
