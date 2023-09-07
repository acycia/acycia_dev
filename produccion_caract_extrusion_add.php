<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
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

$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	//aray de id_c_cv osea names
	$name=array($_POST['n1'],$_POST['n3'],$_POST['n4'],$_POST['n5'],$_POST['n6'],$_POST['n7'],$_POST['n8'],$_POST['n9'],$_POST['n10'],$_POST['n11'],$_POST['n12'],$_POST['n13'],$_POST['n14'],$_POST['n15'],$_POST['n16'],$_POST['n17'],$_POST['n18'],$_POST['n19'],$_POST['n20'],$_POST['n21'],$_POST['n22'],$_POST['n23'],$_POST['n24'],$_POST['n25'],$_POST['n26'],$_POST['n27'],$_POST['n28'],$_POST['n29'],$_POST['n30'],$_POST['n31'],$_POST['n32'],$_POST['n33'],$_POST['n34'],$_POST['n35'],$_POST['n36'],$_POST['n37'],$_POST['n38'],$_POST['n39'],$_POST['n40'],$_POST['n41'],$_POST['n42'],$_POST['n43'],$_POST['n44'],$_POST['n45'],$_POST['n46'],$_POST['n47'],$_POST['n48'],$_POST['n49'],$_POST['n50']);	
    foreach($name as $key=>$value)
    $b[]= $value;	
	//aray del valor de los names por id_c_cv 
	$valor=array($_POST['1'],$_POST['3'],$_POST['4'],$_POST['5'],$_POST['6'],$_POST['micrass'],$_POST['8'],$_POST['9'],$_POST['10'],$_POST['11'],$_POST['12'],$_POST['13'],$_POST['14'],$_POST['15'],$_POST['16'],$_POST['17'],$_POST['18'],$_POST['19'],$_POST['20'],$_POST['21'],$_POST['22'],$_POST['23'],$_POST['24'],$_POST['25'],$_POST['26'],$_POST['27'],$_POST['28'],$_POST['29'],$_POST['30'],$_POST['31'],$_POST['32'],$_POST['33'],$_POST['34'],$_POST['35'],$_POST['36'],$_POST['37'],$_POST['38'],$_POST['39'],$_POST['40'],$_POST['41'],$_POST['42'],$_POST['43'],$_POST['44'],$_POST['45'],$_POST['46'],$_POST['47'],$_POST['48'],$_POST['49'],$_POST['50']);	
    foreach($valor as $key=>$value)
    $a[]= $value;
	//for para guardar refistro por campo
	for($i=0; $i<count($a); $i++){		
  $insertSQL = sprintf("INSERT INTO Tbl_caracteristicas_valor ( id_proceso_cv, id_c_cv, id_ref_cv, cod_ref_cv, version_ref_cv, str_valor_cv, id_pm_cv, fecha_registro_cv, str_registro_cv, b_borrado_cv) VALUES (  %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )",
             //GetSQLValueString($_POST['id_cv'], "int"),
					   GetSQLValueString($_POST['id_proceso_cv'], "int"),
					   GetSQLValueString($b[$i], "int"),
					   GetSQLValueString($_POST['id_ref_cv'], "int"),
					   GetSQLValueString($_POST['cod_ref_cv'], "text"),
					   GetSQLValueString($_POST['version_ref_cv'], "text"),
					   GetSQLValueString($a[$i], "text"),
					   GetSQLValueString($_POST['id_pm_cv'], "int"),
					   GetSQLValueString($_POST['fecha_registro_cv'], "date"),
             GetSQLValueString($_POST['str_registro_cv'], "text"),
					   GetSQLValueString($_POST['b_borrado_cv'], "int"));
  
  $insertSQL2 = sprintf("INSERT INTO Tbl_caract_proceso(id_pm_cp, id_ref_cp, id_cod_ref_cp, id_proceso, id_caract ) VALUES ( %s, %s, %s, %s, %s )",
					   GetSQLValueString($_POST['id_pm_cv'], "int"),
					   GetSQLValueString($_POST['id_ref_cv'], "int"),
					   GetSQLValueString($_POST['cod_ref_cv'], "int"), 
					   GetSQLValueString($_POST['id_proceso_cv'], "int"),
					   GetSQLValueString($b[$i], "int"));
					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());		  

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());  
	}//llave de for

    //MAESTRA TBL_MAESTRA_MP
    $insertSQL4 = sprintf("INSERT INTO Tbl_maestra_mezcla_caract (int_id_ref_mm, int_cod_ref_mm, id_proceso_mm ) VALUES ( %s, %s, %s )",
					   GetSQLValueString($_POST['id_ref_cv'], "int"),
					   GetSQLValueString($_POST['cod_ref_cv'], "int"), 
					   GetSQLValueString($_POST['id_proceso_cv'], "int"));  
 
   mysql_select_db($database_conexion1, $conexion1);
   $Result4 = mysql_query($insertSQL4, $conexion1) or die(mysql_error()); 
   
  $insertGoTo = "produccion_caract_extrusion_mezcla_vista.php?version_ref_cv=" . $_POST['version_ref_cv'] . "";  
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
  
  
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_referencia = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.estado_ref='1' AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_mezcla = "-1";
if (isset($_GET['id_pm'])) {
  $colname_mezcla = (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_mezcla = sprintf("SELECT * FROM Tbl_produccion_mezclas WHERE id_pm = %s AND b_borrado_pm='0'", $colname_mezcla);
$mezcla = mysql_query($query_mezcla, $conexion1) or die(mysql_error());
$row_mezcla = mysql_fetch_assoc($mezcla);
$totalRows_mezcla = mysql_num_rows($mezcla);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM Tbl_caracteristicas_valor ORDER BY id_cv DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_caract = "SELECT Tbl_caracteristicas.id_c, Tbl_caracteristicas.str_nombre_caract_c FROM Tbl_caracteristicas";
$caract = mysql_query($query_caract, $conexion1) or die(mysql_error());
$row_caract = mysql_fetch_assoc($caract);
$totalRows_caract = mysql_num_rows($caract);

//SELECT REFERENCIA
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_copia = "SELECT * FROM Tbl_produccion_mezclas WHERE Tbl_produccion_mezclas.id_ref_pm IN (SELECT Tbl_caracteristicas_valor.id_ref_cv FROM Tbl_caracteristicas_valor WHERE id_proceso_cv='1' ORDER BY Tbl_caracteristicas_valor.cod_ref_cv DESC)";
$referencia_copia = mysql_query($query_referencia_copia, $conexion1) or die(mysql_error());
$row_referencia_copia = mysql_fetch_assoc($referencia_copia);
$totalRows_referencia_copia = mysql_num_rows($referencia_copia);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

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
<!--CODIGO DE CONFIRMACION CIERRE DE PAGINA WEB-->
<script type="text/javascript">
/*window.onbeforeunload = confirmaSalida;
if (resultado==1) {
var id_pm_c=<?php echo $_GET['id_pm'] ?>; 
window.location ="http://intranet.acycia.com/delete.php?rollback="+id_pm_c;
}
function confirmaSalida() {
//if (id_pm_c!="") {
	resultado = 1; 
   	//return resultado;
	return "¿Esta Seguro de Salir de la pagina. Si has hecho algun cambio sin grabar vas a perder todos los datos?"+resultado ;
	 
	//else { window.history.go(); } 
}*/
</script>
<script type="text/javascript">
function ce_micras()
{
calibre=parseFloat(document.form1.micras.value);
var z=(calibre).toFixed(2);
micra=(z)*25.4;
//var w = Math.round(z * Math.pow(10,2))/Math.pow(10,2);
document.form1.micrass.value=Math.round(micra*100)/100;
}
</script>
<!--CONFIRMACION AL DARLE CLICK EN SALIR BOTON-->
</head>
<body>
<?php echo $conexion->header('vistas'); ?>
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
    <table id="table table-bordered table-sm">
    <tr id="tr1">
      <td colspan="10" id="titulo2">CARACTERISTICAS DE EXTRUSION </td>
    </tr>
    <tr>
      <td width="137" colspan="3" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
      <td colspan="7" id="dato3"><a href="manteni.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISE&Ntilde;O Y DESARROLLO" title="LISTADO MEZCLAS Y CARACTERISTICAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
    </tr>
    <tr id="tr1">
      <td width="182" colspan="3" nowrap="nowrap" id="fuente1">Fecha Ingreso
        <input name="fecha_registro_cv" type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus /></td>
      <td colspan="4" id="fuente1"> Ingresado por
        <input name="str_registro_cv" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/>
        <?php //$numero=$row_ultimo['id_cv']+1;  $numero; ?>
        <!--<input type="hidden" name="id_cv" id="id_cv" value="<?php echo $numero; ?>"/>--></td>
    </tr>
    <tr>
      <td colspan="3" nowrap="nowrap" id="fuente2">&nbsp;</td>
      <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
      <td width="235" colspan="2" id="fuente2">&nbsp;</td>
    </tr>
    <tr id="tr1">
      <td colspan="3" nowrap="nowrap" id="fuente2">Referencia</td>
      <td colspan="2" id="fuente2">Version</td>
      <td colspan="2" id="dato1"><input type="button" name="GENERAR COPIA" class="botonGeneral" id="check_sh1" value="GENERAR COPIA" onclick="show_hide(this)"/></td>
    </tr>
    <tr>
      <td colspan="3" nowrap="nowrap" id="numero2"><input type="hidden" name="id_ref_cv" id="id_ref_cv" value="<?php echo $row_mezcla['id_ref_pm'] ?>"/>
        <input type="hidden" name="cod_ref_cv" id="cod_ref_cv" value="<?php echo $row_mezcla['int_cod_ref_pm'] ?>"/>
        <?php echo $row_mezcla['int_cod_ref_pm']; ?></td>
      <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="version_ref_cv" id="version_ref_cv" value="<?php echo $row_mezcla['version_ref_pm'] ?>"/>
        <?php echo $row_mezcla['version_ref_pm']; ?></td>
      <td colspan="2" id="fuente2"><select name="ref" id="select_sh2" onchange="if(form1.ref.value){ consulta_ref_temperatura(); } else{ alert('Debe Seleccionar una REFERENCIA'); }"  style="visibility:hidden">
        <option value=""<?php if (!(strcmp("", $_GET['ref']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
        <?php
do {  
?>
        <option value="<?php echo $row_referencia_copia['id_ref_pm']?>"<?php if (!(strcmp($row_referencia_copia['int_cod_ref_pm'], $_GET['ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia_copia['int_cod_ref_pm']?></option>
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
      <td colspan="3" id="dato2">&nbsp;</td>
      <td colspan="2" id="dato2">&nbsp;</td>
      <td colspan="2" id="dato2">&nbsp;</td>
    </tr>
    <tr id="tr1">
      <td colspan="10" id="titulo4">EXTRUSION<?php $id_ref=$_GET['ref'];?></td>
    </tr>
    <tr id="tr1">
      <td colspan="5" id="fuente1">Opcion No 1</td>
      <td colspan="2" id="fuente2">Calibre</td>
      <td colspan="3" id="fuente2">Ancho material</td>
      </tr>
    <tr>
      <td colspan="3" id="fuente1">
      Boquilla de Extrusion</td>
      <td colspan="2" id="fuente1"><input type="hidden" name="n3" value="3" />        <input name="3" id="3" type="text"   placeholder="Boquilla" size="10" required="required" onblur="ce_micras(this)"/></td>
      <td id="fuente1">Calibre</td>
      <td id="fuente1">Micras</td>
      <td colspan="3" id="fuente1">&nbsp;Ancho</td>
      </tr>
    <tr id="tr1">
      <td colspan="3" id="fuente1">Relacion Soplado (RS)</td>
      <td colspan="2" id="fuente1"><input type="hidden" name="n4" value="4"/>        <input name="4"  type="text"  id="4" placeholder="Relacion Soplado" size="10"required="required"/></td>
      <td id="fuente1"><input type="hidden" name="n6" value="6" />   <input name="6"  type="text"  id="6" placeholder="Milesimas" size="10"  value="<?php echo $row_referencia['calibre_ref']; ?>" onblur="ce_micras(this)" readonly="readonly"/><input type="hidden" name="micras" value="<?php echo $row_referencia['calibre_ref']; ?>"/></td>
      <td id="fuente1"><input type="hidden" name="n7" value="7" />   <input name="7"  type="hidden"  id="7"  size="10" />
        <label for="micrass"></label>
        <input type="text" name="micrass" id="micrass" placeholder="Micras" size="10" readonly="readonly"/></td>
      <td colspan="3" id="fuente1"><input type="hidden" name="n1" value="1" />        <input name="1"  type="text"  id="1" placeholder="Micras" size="10" value="<?php echo $row_referencia['ancho_ref']; ?>"/></td>
      </tr>
    <tr>
      <td colspan="3" rowspan="2" id="fuente1">Altura Linea    Enfriamiento</td>
      <td colspan="2" rowspan="2" id="fuente1"><input type="hidden" name="n5" value="5" />        <input name="5"  type="text"  id="5" placeholder="Altura Linea" size="10"required="required"/></td>
      <td id="fuente1">Presentacion</td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="3" id="fuente1">Peso Millar</td>
      </tr>
    <tr>
      <td id="fuente1"><input name="presentacion_ref" type="text" id="presentacion_ref" value="<?php echo $row_referencia['Str_presentacion']; ?>" size="10" readonly="readonly" /></td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="3" id="fuente1"><input name="tipoBolsa_ref" type="text" id="tipoBolsa_ref" value="<?php echo $row_referencia['peso_millar_ref']; ?>" size="14" readonly="readonly" /></td>
      </tr>
    <tr id="tr1">
      <td rowspan="2" id="fuente1">Velocidad de Halado</td>
      <td colspan="2" id="fuente1">Tratamiento Corona</td>
      <td colspan="5" id="fuente2">Ubicaci&oacute;n Tratamiento</td>
      <td colspan="2" id="fuente1">Pigmentaci&oacute;n</td>
    </tr>
    <tr>
      <td id="fuente1">Potencia</td>
      <td id="fuente1"><input name="9"  type="text"  id="9" placeholder="Potencia" size="5"/>
        <input type="hidden" name="n9" value="9" /></td>
      <td id="fuente1">Cara Interior</td>
      <td colspan="4" id="fuente1"><input name="11"  type="text"  id="11" size="16"value="<?php if ($row_referencia['Str_tratamiento']=="CARA INTERNA"){echo "CARA INTERNA";}else{echo "N/A";}?>"onblur="conMayusculas(this)"  />
        <input name="n11" type="hidden" id="n11" value="11" /></td>
      <td id="fuente1">Interior
        <input name="n13" type="hidden" id="n13" value="13" /></td>
      <td id="fuente1"><input name="13"  type="text"  id="13" placeholder="Pig Interior"onblur="conMayusculas(this)"value="<?php echo $row_referencia['pigm_int_epg']; ?>" size="10"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input type="hidden" name="n8" value="8" />        <input name="8"  type="text"  id="8" placeholder="Velocidad Helado" size="10"/></td>
      <td id="fuente1">Dinas</td>
      <td id="fuente1"><input name="10"  type="text"  id="10" placeholder="Dinas" size="5"/>
        <input type="hidden" name="n10" value="10" /></td>
      <td id="fuente1">Cara Exterior</td>
      <td colspan="4" id="fuente1"><input name="12"  type="text"  id="12" size="16" value="<?php if ($row_referencia['Str_tratamiento']=="CARA EXTERNA"){echo "CARA EXTERNA";}else{echo "N/A";}?>"onblur="conMayusculas(this)"/>
        <input name="n12" type="hidden" id="n12" value="12" /></td>
      <td id="fuente1">Exterior
        <input name="n14" type="hidden" id="n14" value="14" /></td>
      <td id="fuente1"><input name="14"  type="text"  id="14" placeholder="Pig Exterior"onblur="conMayusculas(this)"value="<?php echo $row_referencia['pigm_ext_egp']; ?>" size="10"/></td>
    </tr>
    <tr id="tr1">
      <td rowspan="2" id="fuente1">% Aire Anillo Enfriamiento</td>
      <td colspan="3" id="fuente2">Tension</td>
      <td colspan="6" id="fuente1">&nbsp;</td>
    </tr>
    <tr id="tr1">
      <td id="fuente1">Sec Take Off</td>
      <td id="fuente1">Winder A</td>
      <td id="fuente1">Winder B</td>
      <td colspan="6" id="fuente1">Nota:</td>
    </tr>
    <tr>
      <td id="fuente1"><input name="15"  type="text"  id="15" placeholder="Aire Anillo" size="10"/>
        <input name="n15" type="hidden" id="n15" value="15" /></td>
      <td id="fuente1"><input name="16"  type="text"  id="16" placeholder="Sec Take" size="5"/>
        <input name="n16" type="hidden" id="n16" value="16" /></td>
      <td id="fuente1"><input name="17"  type="text"  id="17" placeholder="Winder A" size="5"/>
        <input name="n17" type="hidden" id="n17" value="17" /></td>
      <td id="fuente1"><input name="18"  type="text"  id="18" placeholder="Winder B" size="5"/>
        <input name="n18" type="hidden" id="n18" value="18" /></td>
      <td colspan="6" id="fuente1">Favor entregar al proceso    siguiente el material debidamente identificado seg&uacute;n el documento    correspondiente para cada rollo de material.</td>
    </tr>
    <tr>
      <td colspan="10" id="fuente1">&nbsp;</td>
    </tr>
    <tr id="tr1">
      <td colspan="10" id="titulo4">TEMPERATURAS DE FUNDIDO EN TORNILLOS Y CABEZAL</td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">&nbsp;</td>
      <td colspan="2"id="fuente1">TORNILLO A</td>
      <td colspan="2"id="fuente1">TORNILLO B</td>
      <td colspan="2"id="fuente1">TORNILLO C</td>
      <td colspan="1" id="fuente1">Cabezal (Die Head)</td>
      <td colspan="1" id="fuente1">&deg;C</td>
    </tr>
    <tr>
      <td colspan="2"id="fuente1">Barrel Zone 1</td>
      <td colspan="2"id="fuente1"><input name="19"  type="text"  id="19" placeholder="Tor A" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='19'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n19" type="hidden" id="n19" value="19" /></td>
      <td colspan="2"id="fuente1"><input name="20"  type="text"  id="20" placeholder="Tor B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='20'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n20" type="hidden" id="n20" value="20" /></td>
      <td colspan="2"id="fuente1"><input name="21"  type="text"  id="21" placeholder="Tor C" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='21'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n21" type="hidden" id="n21" value="21" /></td>
      <td colspan="1" id="fuente1">Share Lower</td>
      <td colspan="1" id="fuente1"><input name="43"  type="text"  id="43" placeholder="Share Lower" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='43'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n43" type="hidden" id="n43" value="43" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">Barrel Zone 2</td>
      <td colspan="2"id="fuente1"><input name="22"  type="text"  id="22" placeholder="Tor A" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='22'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n22" type="hidden" id="n22" value="22" /></td>
      <td colspan="2"id="fuente1"><input name="23"  type="text"  id="23" placeholder="Tor B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='23'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n23" type="hidden" id="n23" value="23" /></td>
      <td colspan="2"id="fuente1"><input name="24"  type="text"  id="24" placeholder="Tor C" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='24'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n24" type="hidden" id="n24" value="24" /></td>
      <td colspan="1" id="fuente1">Share Upper</td>
      <td colspan="1" id="fuente1"><input name="44"  type="text"  id="44" placeholder="Share Upper" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='44'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n44" type="hidden" id="n44" value="44" /></td>
    </tr>
    <tr>
      <td colspan="2"id="fuente1">Barrel Zone 3</td>
      <td colspan="2"id="fuente1"><input name="25"  type="text"  id="25" placeholder="Tor A" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='25'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n25" type="hidden" id="n25" value="25" /></td>
      <td colspan="2"id="fuente1"><input name="26"  type="text"  id="26" placeholder="Tor B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='26'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n26" type="hidden" id="n26" value="26" /></td>
      <td colspan="2"id="fuente1"><input name="27"  type="text"  id="27" placeholder="Tor C" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='27'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n27" type="hidden" id="n27" value="27" /></td>
      <td colspan="1" id="fuente1">L-Die</td>
      <td colspan="1" id="fuente1"><input name="45"  type="text"  id="45" placeholder="L-Die" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='45'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n45" type="hidden" id="n45" value="45" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">Barrel Zone 4</td>
      <td colspan="2"id="fuente1"><input name="28"  type="text"  id="28" placeholder="Tor A" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='28'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n28" type="hidden" id="n28" value="28" /></td>
      <td colspan="2"id="fuente1"><input name="29"  type="text"  id="29" placeholder="Tor B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='29'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input type="hidden" name="n29" value="29" /></td>
      <td colspan="2"id="fuente1"><input name="30"  type="text"  id="30" placeholder="Tor C" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='30'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n30" type="hidden" id="n30" value="30" /></td>
      <td colspan="1" id="fuente1">V- Die</td>
      <td colspan="1" id="fuente1"><input name="46"  type="text"  id="46" placeholder="V- Die" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='46'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n46" type="hidden" id="n46" value="46" /></td>
    </tr>
    <tr>
      <td colspan="2"id="fuente1">Filter Front</td>
      <td colspan="2"id="fuente1"><input name="31"  type="text"  id="31" placeholder="Tor A" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='31'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n31" type="hidden" id="n31" value="31" /></td>
      <td colspan="2"id="fuente1"><input name="32"  type="text"  id="32" placeholder="Tor B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='32'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n32" type="hidden" id="n32" value="32" /></td>
      <td colspan="2"id="fuente1"><input name="33"  type="text"  id="33" placeholder="Tor C" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='33'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n33" type="hidden" id="n33" value="33" /></td>
      <td colspan="1" id="fuente1">Die Head</td>
      <td colspan="1" id="fuente1"><input name="47"  type="text"  id="47" placeholder="Die Head" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='47'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n47" type="hidden" id="n47" value="47" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">Filter Back</td>
      <td colspan="2"id="fuente1"><input name="34"  type="text"  id="34" placeholder="Tor A" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='34'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n34" type="hidden" id="n34" value="34" /></td>
      <td colspan="2"id="fuente1"><input name="35"  type="text"  id="35" placeholder="Tor B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='35'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n35" type="hidden" id="n35" value="35" /></td>
      <td colspan="2"id="fuente1"><input name="36"  type="text"  id="36" placeholder="Tor C" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='36'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n36" type="hidden" id="n36" value="36" /></td>
      <td colspan="1" id="fuente1">Die Lid</td>
      <td colspan="1" id="fuente1"><input name="48"  type="text"  id="48" placeholder="Die Lid" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='48'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n48" type="hidden" id="n48" value="48" /></td>
    </tr>
    <tr>
      <td colspan="2"id="fuente1">Sec- Barrel</td>
      <td colspan="2"id="fuente1"><input name="37"  type="text"  id="37" placeholder="Tor A" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='37'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n37" type="hidden" id="n37" value="37" /></td>
      <td colspan="2"id="fuente1"><input name="38"  type="text"  id="38" placeholder="Tor B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='38'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n38" type="hidden" id="n38" value="38" /></td>
      <td colspan="2"id="fuente1"><input name="39"  type="text"  id="39" placeholder="Tor C" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='39'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n39" type="hidden" id="n39" value="39" /></td>
      <td colspan="1" id="fuente1">Die Center Lower</td>
      <td colspan="1" id="fuente1"><input name="49"  type="text"  id="49" placeholder="Die Center Lower" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='49'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n49" type="hidden" id="n49" value="49" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">Melt Temp &deg;C</td>
      <td colspan="2"id="fuente1"><input name="40"  type="text"  id="40" placeholder="Tor A" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='40'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n40" type="hidden" id="n40" value="40" /></td>
      <td colspan="2"id="fuente1"><input name="41"  type="text"  id="41" placeholder="Tor B" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='41'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n41" type="hidden" id="n41" value="41" /></td>
      <td colspan="2"id="fuente1"><input name="42"  type="text"  id="42" placeholder="Tor C" size="10"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='42'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n42" type="hidden" id="n42" value="42" /></td>
      <td colspan="1" id="fuente1">Die Center Upper</td>
      <td colspan="1" id="fuente1"><input name="50"  type="text"  id="50" placeholder="Die Center Upper" size="5"value="<?php if($id_ref!=''){$con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='50'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];}?>"/>
        <input name="n50" type="hidden" id="n50" value="50" /></td>
    </tr>
    <tr>
      <td colspan="10"id="fuente1">Estos son valores de referencia que pueden cambiar de acuerdo    a velocidad, temperatura ambiente, calibre, etc.</td>
    </tr>
    <tr id="tr1">
      <td colspan="10"id="fuente1">&nbsp;</td>
    </tr>
    <tr id="tr1">
      <td colspan="10" id="fuente2"><input type="hidden" name="id_pm_cv" id="id_pm_cv" value="<?php echo $row_mezcla['id_pm'] ?>"/>
      <input type="hidden" name="id_proceso_cv" id="id_proceso_cv" value="<?php echo $row_mezcla['id_proceso'] ?>"/>
        <input type="hidden" name="b_borrado_cv" id="b_borrado_cv" value="<?php echo $row_mezcla['b_borrado_pm'] ?>"/>
        <input type="submit" class="botonFinalizar" name="GUARDAR" id="GUARDAR" value="GUARDAR" /></td>
    </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
    </form>
  <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($referencia);
mysql_free_result($referencia_copia); 
mysql_free_result($mezcla);
mysql_free_result($caract); 
mysql_free_result($ultimo);
?>
