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
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE Tbl_produccion_mezclas SET id_proceso=%s,fecha_registro_pm=%s,str_registro_pm=%s,id_ref_pm=%s,int_cod_ref_pm=%s,version_ref_pm=%s, int_ref1_tol1_pm=%s,
int_ref1_tol1_porc1_pm=%s,int_ref2_tol1_pm=%s,int_ref2_tol1_porc2_pm=%s,int_ref3_tol1_pm=%s,int_ref3_tol1_porc3_pm=%s,int_ref1_tol2_pm=%s,int_ref1_tol2_porc1_pm=%s,int_ref2_tol2_pm=%s,
int_ref2_tol2_porc2_pm=%s,int_ref3_tol2_pm=%s,int_ref3_tol2_porc3_pm=%s,int_ref1_tol3_pm=%s,int_ref1_tol3_porc1_pm=%s,
int_ref2_tol3_pm=%s,int_ref2_tol3_porc2_pm=%s,int_ref3_tol3_pm=%s,int_ref3_tol3_porc3_pm=%s,int_ref1_tol4_pm=%s,int_ref1_tol4_porc1_pm=%s,int_ref2_tol4_pm=%s,int_ref2_tol4_porc2_pm=%s,
int_ref3_tol4_pm=%s,int_ref3_tol4_porc3_pm=%s, int_ref1_rpm_pm=%s, int_ref1_tol5_porc1_pm=%s, int_ref2_rpm_pm=%s, int_ref2_tol5_porc2_pm=%s, int_ref3_rpm_pm=%s, int_ref3_tol5_porc3_pm=%s, observ_pm=%s, b_borrado_pm=%s WHERE id_pm=%s", 
                       GetSQLValueString($_POST['id_proceso'], "int"),
                       GetSQLValueString($_POST['fecha_registro_pm'], "date"),
					   GetSQLValueString($_POST['str_registro_pm'], "text"),
					   GetSQLValueString($_POST['id_ref_pm'], "int"),
					   GetSQLValueString($_POST['int_cod_ref_pm'], "text"),
					   GetSQLValueString($_POST['version_ref_pm'], "text"),					   
					   GetSQLValueString($_POST['int_ref1_tol1_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol1_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol1_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol1_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol1_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol1_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol2_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol2_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol2_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol2_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol2_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol2_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol3_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol3_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol3_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol3_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol3_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol3_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_tol4_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol4_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_tol4_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol4_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_tol4_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol4_porc3_pm'], "double"),
					   GetSQLValueString($_POST['int_ref1_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol5_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol5_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol5_porc3_pm'], "double"),
					   GetSQLValueString($_POST['observ_pm'], "text"),
					   GetSQLValueString($_POST['b_borrado_pm'], "int"),
					   GetSQLValueString($_POST['id_pm'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "produccion_caract_extrusion_mezcla_vista.php?id_pm=" . $_POST['id_pm'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
	//$id_ref=$_GET['id_ref'];
	//aray de id_c_cv osea names
	$name=array($_POST['n1'],$_POST['n3'],$_POST['n4'],$_POST['n5'],$_POST['n6'],$_POST['n7'],$_POST['n8'],$_POST['n9'],$_POST['n10'],$_POST['n11'],$_POST['n12'],$_POST['n13'],$_POST['n14'],$_POST['n15'],$_POST['n16'],$_POST['n17'],$_POST['n18'],$_POST['n19'],$_POST['n20'],$_POST['n21'],$_POST['n22'],$_POST['n23'],$_POST['n24'],$_POST['n25'],$_POST['n26'],$_POST['n27'],$_POST['n28'],$_POST['n29'],$_POST['n30'],$_POST['n31'],$_POST['n32'],$_POST['n33'],$_POST['n34'],$_POST['n35'],$_POST['n36'],$_POST['n37'],$_POST['n38'],$_POST['n39'],$_POST['n40'],$_POST['n41'],$_POST['n42'],$_POST['n43'],$_POST['n44'],$_POST['n45'],$_POST['n46'],$_POST['n47'],$_POST['n48'],$_POST['n49'],$_POST['n50']);	
    foreach($name as $key=>$value)
    $b[]= $value;	
	//aray del valor de los names por id_c_cv 
	$valor=array($_POST['1'],$_POST['3'],$_POST['4'],$_POST['5'],$_POST['6'],$_POST['7'],$_POST['8'],$_POST['9'],$_POST['10'],$_POST['11'],$_POST['12'],$_POST['13'],$_POST['14'],$_POST['15'],$_POST['16'],$_POST['17'],$_POST['18'],$_POST['19'],$_POST['20'],$_POST['21'],$_POST['22'],$_POST['23'],$_POST['24'],$_POST['25'],$_POST['26'],$_POST['27'],$_POST['28'],$_POST['29'],$_POST['30'],$_POST['31'],$_POST['32'],$_POST['33'],$_POST['34'],$_POST['35'],$_POST['36'],$_POST['37'],$_POST['38'],$_POST['39'],$_POST['40'],$_POST['41'],$_POST['42'],$_POST['43'],$_POST['44'],$_POST['45'],$_POST['46'],$_POST['47'],$_POST['48'],$_POST['49'],$_POST['50']);	
    foreach($valor as $key=>$value)
    $a[]= $value;
	//for para guardar refistro por campo
	for($i=0; $i<count($a); $i++) 
    {		
  $updateSQL = sprintf("UPDATE Tbl_caracteristicas_valor SET id_proceso_cv=%s, id_c_cv=%s, id_ref_cv=%s, cod_ref_cv=%s, version_ref_cv=%s, str_valor_cv=%s, id_pm_cv=%s, fecha_registro_cv=%s, str_registro_cv=%s, b_borrado_cv=%s WHERE id_c_cv=%s AND id_ref_cv=%s",
                       //GetSQLValueString($_POST['id_cv'], "int"),
					   GetSQLValueString($_POST['id_proceso_cv'], "int"),
					   GetSQLValueString($b[$i], "int"),
					   GetSQLValueString($_POST['id_ref_cv'], "int"),
					   GetSQLValueString($_POST['cod_ref_cv'], "int"),
					   GetSQLValueString($_POST['version_ref_cv'], "text"),
					   GetSQLValueString($a[$i], "text"),
					   GetSQLValueString($_POST['id_pm_cv'], "int"),
					   GetSQLValueString($_POST['fecha_registro_cv'], "date"),
                       GetSQLValueString($_POST['str_registro_cv'], "text"),
					   GetSQLValueString($_POST['b_borrado_cv'], "int"),
					   GetSQLValueString($b[$i], "int"),
					   GetSQLValueString($_POST['id_ref_cv'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
	}//llave de for
  $updateGoTo = "produccion_caract_extrusion_mezcla_vista.php?id_c=" . $_POST['id_c'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_editar_m = "-1";
if (isset($_GET['id_pm'])) 
{
  $colname_editar_m= (get_magic_quotes_gpc()) ? $_GET['id_pm'] : addslashes($_GET['id_pm']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_editar_m = sprintf("SELECT * FROM Tbl_produccion_mezclas WHERE id_pm='%s'",$colname_editar_m);
$editar_m = mysql_query($query_editar_m, $conexion1) or die(mysql_error());
$row_editar_m = mysql_fetch_assoc($editar_m);
$totalRows_editar_m = mysql_num_rows($editar_m);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='4' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//SELECT REFERENCIA
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_copia = "SELECT * FROM Tbl_produccion_mezclas WHERE Tbl_produccion_mezclas.id_ref_pm IN (SELECT Tbl_caracteristicas_valor.id_ref_cv FROM Tbl_caracteristicas_valor WHERE id_proceso_cv='1' ORDER BY Tbl_caracteristicas_valor.cod_ref_cv  DESC)";
$referencia_copia = mysql_query($query_referencia_copia, $conexion1) or die(mysql_error());
$row_referencia_copia = mysql_fetch_assoc($referencia_copia);
$totalRows_referencia_copia = mysql_num_rows($referencia_copia);

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
//LLAMA EL TIPO DE MEZCLA DE LA REFERENCIA
$colname_formula_ref = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_formula_ref  = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_formula_ref = sprintf("SELECT DISTINCT int_cod_ref_io,int_nombre_io FROM Tbl_items_ordenc WHERE int_cod_ref_io=%s AND int_nombre_io<>''",$colname_formula_ref);
$formula_ref = mysql_query($query_formula_ref, $conexion1) or die(mysql_error());
$row_formula_ref = mysql_fetch_assoc($formula_ref);
$totalRows_formula_ref = mysql_num_rows($formula_ref);
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
window.onbeforeunload = confirmaSalida;  

function confirmaSalida()   {    
       if (str_encargado_r.value==""||str_transportador_r.value==""||str_guia_r.value==""||str_aprobo_r.value=="") {

          return "¿Esta Seguro de Salir de la pagina. Si has hecho algun cambio sin grabar vas a perder todos los datos?";  
       }
}
</script>
<!--CONFIRMACION AL DARLE CLICK EN SALIR BOTON-->
</head>
<body>
<?php echo $conexion->header('vistas'); ?>
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
    <table id="table table-bordered table-sm">
      <tr id="tr1">
        <td colspan="7" id="titulo2">PROCESO EXTRUSION MEZCLAS</td>
        </tr>
      <tr>
        <td width="137" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
          <td colspan="6" id="dato3"><a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $_GET['id_ref']; ?>&amp;id_pm=<?php echo $row_editar_m['id_pm']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION"title="VISTA IMPRESION"  border="0" /></a><a href="produccion_mezclas.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="manteni.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISE&Ntilde;O Y DESARROLLO" title="LISTADO MEZCLAS Y CARACTERISTICAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU"  border="0"/></a></td>
        </tr>
        <tr id="tr1">
          <td width="182" colspan="2" nowrap="nowrap" id="fuente1">Fecha Ingreso
            <input name="fecha_registro_pm" type="date" min="2000-01-02" value="<?php echo $row_editar_m['fecha_registro_pm'] ?>" size="10" autofocus /></td>
          <td colspan="4" id="fuente1"> Ingresado por
            <input name="str_registro_pm" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/>
            <input type="hidden" name="id_proceso" id="id_proceso" value="<?php echo $row_editar_m['id_proceso']; ?>"/></td>
        </tr>
        <tr id="tr3">
          <td colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
          <td width="126" colspan="2" nowrap="nowrap" id="fuente2">&nbsp;</td>
          <td width="235" colspan="2" id="fuente2">&nbsp;</td>
        </tr>
        <tr id="tr3">
          <td colspan="2" nowrap="nowrap" id="fuente2">Referencia:</td>
          <td colspan="2" id="fuente2"><output  onforminput="value=weight.value">Version:</output></td>
          <td colspan="2" id="dato1">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="id_ref_pm" id="id_ref_pm" value="<?php echo $row_editar_m['id_ref_pm'] ?>"/>
            <input type="hidden" name="int_cod_ref_pm" id="int_cod_ref_pm" value="<?php echo $row_editar_m['int_cod_ref_pm']; ?>" />
            <?php echo $row_editar_m['int_cod_ref_pm']; ?></td>
          <td colspan="2" nowrap="nowrap" id="numero2"><input type="hidden" name="version_ref_pm" id="version_ref_pm" value="<?php echo $row_editar_m['version_ref_pm']; ?>" />
            <?php echo $row_editar_m['version_ref_pm']; ?></td>
          <td colspan="2" id="fuente2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" id="dato2">&nbsp;</td>
          <td colspan="2" id="dato2">&nbsp;</td>
          <td colspan="2" id="dato2"><datalist id="dias"></datalist></td>
        </tr>
        <tr id="tr1">
          <td colspan="10" id="titulo4">EXTRUSION</td>
        </tr>
        <tr id="tr1">
        <td rowspan="3" id="fuente1">EXT-1          
        </td>
        <td colspan="2" id="fuente1">TORNILLO A</td>
        <td colspan="2" id="fuente1">TORNILLO B</td>
        <td colspan="2" id="fuente1">TORNILLO C</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
        <td id="fuente1">Referencia</td>
        <td id="fuente1">%</td>
      </tr>
      <tr>
        <td id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Tolva A</td>
        <td id="fuente1"><select name="int_ref1_tol1_pm" id="int_ref1_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref1_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol1_porc1_pm" style="width:60px" min="0"step="0.01" type="number" required="required" id="int_ref1_tol1_porc1_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref1_tol1_porc1_pm']; ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol1_pm" id="int_ref2_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref2_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol1_porc2_pm" style="width:60px" min="0"step="0.01" type="number"  required="required" id="int_ref2_tol1_porc2_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref2_tol1_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol1_pm" id="int_ref3_tol1_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref3_tol1_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol1_porc3_pm" style="width:60px" min="0"step="0.01" type="number" required="required" id="int_ref3_tol1_porc3_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref3_tol1_porc3_pm'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1">Tolva B</td>
        <td id="fuente1"><select name="int_ref1_tol2_pm" id="int_ref1_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref1_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol2_porc1_pm" style="width:60px" min="0"step="0.01" type="number"  required="required" id="int_ref1_tol2_porc1_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref1_tol2_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol2_pm" id="int_ref2_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref2_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol2_porc2_pm" style="width:60px" min="0"step="0.01" type="number"  required="required" id="int_ref2_tol2_porc2_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref2_tol2_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol2_pm" id="int_ref3_tol2_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref3_tol2_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol2_porc3_pm" style="width:60px" min="0"step="0.01" type="number" required="required" id="int_ref3_tol2_porc3_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref3_tol2_porc3_pm'] ?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">Tolva C</td>
        <td id="fuente1"><select name="int_ref1_tol3_pm" id="int_ref1_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref1_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol3_porc1_pm" style="width:60px" min="0"step="0.01" type="number" required="required" id="int_ref1_tol3_porc1_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref1_tol3_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol3_pm" id="int_ref2_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref2_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol3_porc2_pm" style="width:60px" min="0"step="0.01" type="number" required="required" id="int_ref2_tol3_porc2_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref2_tol3_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol3_pm" id="int_ref3_tol3_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref3_tol3_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol3_porc3_pm" style="width:60px" min="0"step="0.01" type="number" required="required" id="int_ref3_tol3_porc3_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref3_tol3_porc3_pm'] ?>"/></td>
      </tr>
      <tr>
        <td id="fuente1">Tolva D</td>
        <td id="fuente1"><select name="int_ref1_tol4_pm" id="int_ref1_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref1_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref1_tol4_porc1_pm" style="width:60px" min="0"step="0.01" type="number"  required="required" id="int_ref1_tol4_porc1_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref1_tol4_porc1_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref2_tol4_pm" id="int_ref2_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref2_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref2_tol4_porc2_pm" style="width:60px" min="0"step="0.01" type="number" required="required" id="int_ref2_tol4_porc2_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref2_tol4_porc2_pm'] ?>"/></td>
        <td id="fuente1"><select name="int_ref3_tol4_pm" id="int_ref3_tol4_pm" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_editar_m['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>>Referencia MP</option>
          <?php
do {  
?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $row_editar_m['int_ref3_tol4_pm']))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
        </select></td>
        <td id="fuente1"><input name="int_ref3_tol4_porc3_pm" style="width:60px" min="0"step="0.01" type="number" required="required" id="int_ref3_tol4_porc3_pm" placeholder="%" value="<?php echo $row_editar_m['int_ref3_tol4_porc3_pm'] ?>"/></td>
      </tr>
      <tr id="tr1">
        <td id="fuente1">RPM - %</td>
        <td id="fuente1"><input id="int_ref1_rpm_pm" name="int_ref1_rpm_pm" style="width:117px" type="number" min="0" step="1" value="<?php echo $row_editar_m['int_ref1_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref1_tol5_porc1_pm"  style="width:60px" min="0"step="0.01" type="number"  id="int_ref1_tol5_porc1_pm" placeholder="%" required="required"value="<?php echo $row_editar_m['int_ref1_tol5_porc1_pm'] ?>"/></td>
        <td id="fuente1"><input id="int_ref2_rpm_pm" name="int_ref2_rpm_pm" style="width:117px" type="number" min="0" step="1" value="<?php echo $row_editar_m['int_ref2_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref2_tol5_porc2_pm"  style="width:60px" min="0"step="0.01" type="number"  id="int_ref2_tol5_porc2_pm" placeholder="%" required="required"value="<?php echo $row_editar_m['int_ref2_tol5_porc2_pm'] ?>"/></td>
        <td id="fuente1"><input id="int_ref3_rpm_pm" name="int_ref3_rpm_pm" style="width:117px" type="number" min="0" step="1" value="<?php echo $row_editar_m['int_ref3_rpm_pm'] ?>"/></td>
        <td id="fuente1"><input name="int_ref3_tol5_porc3_pm" style="width:60px" min="0"step="0.01" type="number"  id="int_ref3_tol5_porc3_pm" placeholder="%" required="required"value="<?php echo $row_editar_m['int_ref3_tol5_porc3_pm'] ?>"/></td>
      </tr>
        <tr>
          <td id="fuente1"></td>
          <td colspan="2" id="fuente1"></td>
          <td colspan="2" id="fuente1"></td>
          <td colspan="2" id="fuente1"></td>
        </tr>
<tr id="tr1">
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">NOMBRE FORMULA</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">&nbsp;</td>
        <td colspan="6" id="fuente1"><textarea name="f" cols="45" rows="1" readonly="readonly" id="f"><?php 
			  $id_nombre=$row_formula_ref['int_nombre_io'];
			  $sqlnom="SELECT * FROM Tbl_formula_nombres WHERE id='$id_nombre'";
			  $resultnom= mysql_query($sqlnom);
			  $numnom= mysql_num_rows($resultnom);
			  if($numnom >='1')
			  { 
			  $nombre = mysql_result($resultnom, 0, 'nombre_fn');  echo $nombre;
			  }else{echo " ";}
		      ?>
          </textarea>  
          Informacion del Item de la O.C</td>
        </tr>        
        <tr id="tr1">
          <td colspan="10" id="fuente2">OBSERVACIONES</td>
        </tr>
        <tr>
          <td colspan="10" id="fuente2"><textarea name="observ_pm" id="observ_pm" cols="80" rows="2"placeholder="OBSERVACIONES"onblur="conMayusculas(this)"><?php echo $row_editar_m['observ_pm'] ?></textarea></td>
        </tr>
        <tr id="tr1">
          <td colspan="10" id="fuente2"><input type="hidden" name="id_pm" value="<?php echo $row_editar_m['id_pm']; ?>" />
            <input type="hidden" name="b_borrado_pm" value="<?php echo $row_editar_m['b_borrado_pm']; ?>" />
            <input type="submit" name="Actualizar Mezclas" class="botonGeneral" id="Actualizar Mezclas" value="Actualizar Mezclas" /></td>
        </tr>
      </table>
    <input type="hidden" name="MM_update" value="form1">
    </form> 
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form2">
    <table id="table table-bordered table-sm">
      <tr id="tr1">
          <td colspan="10" id="titulo">CARACTERISTICAS DE EXTRUSION <?php  $id_ref=$_GET['id_ref']?></td>
        </tr>
        <!--<tr id="tr1">
          <td width="137" colspan="3" id="fuente1">Fecha Ingreso
            <input name="fecha_registro_c" type="date" min="2000-01-02" value="<?php echo $row_editar_m['fecha_registro_pm'] ?>" size="10" /></td>
          <td colspan="7" nowrap="nowrap" id="fuente1">Ingresado por
            <input name="str_registro_c" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
        </tr>-->
        <tr>
          <td colspan="5" id="fuente3">&nbsp;</td>
          <td colspan="2" id="fuente4"><input type="button" class="botonGeneral" name="GENERAR COPIA" id="check_sh1" value="GENERAR COPIA" onclick="show_hide(this)"/></td>
          <td colspan="3" id="fuente4"><select name="id_ref" id="select_sh2" onchange="if(form2.id_ref.value){ consulta_ref_temperatura_edit();} else{ alert('Debe Seleccionar una REFERENCIA'); }"  style="visibility:hidden">
            <option value=""<?php if (!(strcmp("", $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>Referencia</option>
            <?php
do {  
?>
            <option value="<?php echo $row_referencia_copia['id_ref_pm']?>"<?php if (!(strcmp($row_referencia_copia['int_cod_ref_pm'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia_copia['int_cod_ref_pm']?></option>
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
        <tr id="tr1">
      <td colspan="5" id="fuente1">Opcion No 1
        <input type="hidden" name="id_ref_cv" id="id_ref_cv" value="<?php echo $row_editar_m['id_ref_pm'] ?>"/>
        <input type="hidden" name="cod_ref_cv" id="cod_ref_cv" value="<?php echo $row_editar_m['int_cod_ref_pm'] ?>"/>
        <input type="hidden" name="version_ref_cv" id="version_ref_cv" value="<?php echo $row_editar_m['version_ref_pm'] ?>"/>
        <input type="hidden" name="fecha_registro_cv" id="fecha_registro_cv"  value="<?php echo $row_editar_m['fecha_registro_pm'] ?>"/>
        <input type="hidden" name="str_registro_cv" id="str_registro_cv" value="<?php echo $row_usuario['nombre_usuario']; ?>" /></td>
      <td colspan="2" id="fuente2">Calibre</td>
      <td colspan="3" id="fuente2">Ancho material</td>
    </tr>
      
    <tr>
      <td colspan="3" id="fuente1">
      Boquilla de Extrusion</td>
      <td colspan="2" id="fuente1"><input type="hidden" name="n3" value="3" />        <input name="3" id="3" type="text"   placeholder="Boquilla" size="10" value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='3'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>" required="required"/></td>
      <td id="fuente1">Calibre</td>
      <td id="fuente1">Micras</td>
      <td colspan="3" id="fuente1">&nbsp;Ancho</td>
      </tr>
    <tr id="tr1">
      <td colspan="3" id="fuente1">Relacion Soplado (RS)</td>
      <td colspan="2" id="fuente1"><input type="hidden" name="n4" value="4"/><input name="4"  type="text"  id="4" placeholder="Relacion Soplado" size="10" value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='4'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>" required="required"/></td>
      <td id="fuente1"><input type="hidden" name="n6" value="6" />        <input name="6"  type="text"  id="6" placeholder="Milesimas"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='6'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>" size="10" readonly="readonly" /></td>
      <td id="fuente1"><input type="hidden" name="n7" value="7" />        <input name="7"  type="text"  id="7" placeholder="Micras" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='7'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>" readonly="readonly"/></td>
      <td colspan="3" id="fuente1"><input type="hidden" name="n1" value="1" /><input name="1"  type="text"  id="1" placeholder="Micras" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='1'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/></td>
      </tr>
    <tr>
      <td colspan="3" rowspan="2" id="fuente1">Altura Linea    Enfriamiento</td>
      <td colspan="2" rowspan="2" id="fuente1"><input type="hidden" name="n5" value="5" />        <input name="5"  type="text"  id="5" placeholder="Altura Linea" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='5'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>" required="required"/></td>
      <td id="fuente1">Presentacion</td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="3" id="fuente1">Peso Millar</td>
      </tr>
    <tr>
      <td id="fuente5"><input name="presentacion_ref" type="text" id="presentacion_ref" value="<?php echo $row_ref['Str_presentacion']; ?>" size="10" readonly="readonly" /></td>
      <td id="fuente5">&nbsp;</td>
      <td colspan="3" id="fuente5"><input name="tipoBolsa_ref" type="text" id="tipoBolsa_ref" value="<?php echo $row_ref['peso_millar_ref']; ?>" size="10" readonly="readonly" /></td>
      </tr>
    <tr id="tr1">
      <td rowspan="2" id="fuente1">Velocidad de Halado</td>
      <td colspan="2" id="fuente1">Tratamiento Corona</td>
      <td colspan="5" id="fuente2">Ubicaci&oacute;n Tratamiento</td>
      <td colspan="2" id="fuente1">Pigmentaci&oacute;n</td>
    </tr>
    <tr>
      <td id="fuente1">Potencia</td>
      <td id="fuente1"><input name="9"  type="text"  id="9" placeholder="Potencia" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='9'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input type="hidden" name="n9" value="9" /></td>
      <td id="fuente1">Cara Interior</td>
      <td colspan="4" id="fuente1"><input name="11"  type="text"  id="11" size="16"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='11'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>" />
        <input name="n11" type="hidden" id="n11" value="11" /></td>
      <td id="fuente1">Interior
        <input name="n13" type="hidden" id="n13" value="13" /></td>
      <td id="fuente1"><input name="13"  type="text"  id="13" placeholder="Pig Interior"onblur="conMayusculas(this)"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='13'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>" size="10"/></td>
    </tr>
    <tr>
      <td id="fuente1"><input type="hidden" name="n8" value="8" />        <input name="8"  type="text"  placeholder="Velocidad Helado" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='8'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/></td>
      <td id="fuente1">Dinas</td>
      <td id="fuente1"><input name="10"  type="text"  id="10" placeholder="Dinas" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='10'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input type="hidden" name="n10" value="10" /></td>
      <td id="fuente1">Cara Exterior</td>
      <td colspan="4" id="fuente1"><input name="12"  type="text"  id="12" size="16" value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='12'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"onblur="conMayusculas(this)"/>
        <input name="n12" type="hidden" id="n12" value="12" /></td>
      <td id="fuente1">Exterior
        <input name="n14" type="hidden" id="n14" value="14" /></td>
      <td id="fuente1"><input name="14"  type="text"  id="14" placeholder="Pig Exterior"onblur="conMayusculas(this)"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='14'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>" size="10"/></td>
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
      <td id="fuente1"><input name="15"  type="text"  id="15" placeholder="Aire Anillo" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='15'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n15" type="hidden" id="n15" value="15" /></td>
      <td id="fuente1"><input name="16"  type="text"  id="16" placeholder="Sec Take" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='16'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n16" type="hidden" id="n16" value="16" /></td>
      <td id="fuente1"><input name="17"  type="text"  id="17" placeholder="Winder A" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='17'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n17" type="hidden" id="n17" value="17" /></td>
      <td id="fuente1"><input name="18"  type="text"  id="18" placeholder="Winder B" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='18'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
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
      <td colspan="2"id="fuente1"><input name="19"  type="text"  id="19" placeholder="Tor A" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='19'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n19" type="hidden" id="n19" value="19" /></td>
      <td colspan="2"id="fuente1"><input name="20"  type="text"  id="20" placeholder="Tor B" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='20'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n20" type="hidden" id="n20" value="20" /></td>
      <td colspan="2"id="fuente1"><input name="21"  type="text"  id="21" placeholder="Tor C" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='21'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n21" type="hidden" id="n21" value="21" /></td>
      <td colspan="1" id="fuente1">Share Lower</td>
      <td colspan="1" id="fuente1"><input name="43"  type="text"  id="43" placeholder="Share Lower" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='43'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n43" type="hidden" id="n43" value="43" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">Barrel Zone 2</td>
      <td colspan="2"id="fuente1"><input name="22"  type="text"  id="22" placeholder="Tor A" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='22'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n22" type="hidden" id="n22" value="22" /></td>
      <td colspan="2"id="fuente1"><input name="23"  type="text"  id="23" placeholder="Tor B" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='23'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n23" type="hidden" id="n23" value="23" /></td>
      <td colspan="2"id="fuente1"><input name="24"  type="text"  id="24" placeholder="Tor C" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='24'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n24" type="hidden" id="n24" value="24" /></td>
      <td colspan="1" id="fuente1">Share Upper</td>
      <td colspan="1" id="fuente1"><input name="44"  type="text"  id="44" placeholder="Share Upper" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='44'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n44" type="hidden" id="n44" value="44" /></td>
    </tr>
    <tr>
      <td colspan="2"id="fuente1">Barrel Zone 3</td>
      <td colspan="2"id="fuente1"><input name="25"  type="text"  id="25" placeholder="Tor A" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='25'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n25" type="hidden" id="n25" value="25" /></td>
      <td colspan="2"id="fuente1"><input name="26"  type="text"  id="26" placeholder="Tor B" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='26'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n26" type="hidden" id="n26" value="26" /></td>
      <td colspan="2"id="fuente1"><input name="27"  type="text"  id="27" placeholder="Tor C" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='27'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n27" type="hidden" id="n27" value="27" /></td>
      <td colspan="1" id="fuente1">L-Die</td>
      <td colspan="1" id="fuente1"><input name="45"  type="text"  id="45" placeholder="L-Die" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='45'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n45" type="hidden" id="n45" value="45" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">Barrel Zone 4</td>
      <td colspan="2"id="fuente1"><input name="28"  type="text"  id="28" placeholder="Tor A" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='28'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n28" type="hidden" id="n28" value="28" /></td>
      <td colspan="2"id="fuente1"><input name="29"  type="text"  id="29" placeholder="Tor B" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='29'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input type="hidden" name="n29" value="29" /></td>
      <td colspan="2"id="fuente1"><input name="30"  type="text"  id="30" placeholder="Tor C" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='30'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n30" type="hidden" id="n30" value="30" /></td>
      <td colspan="1" id="fuente1">V- Die</td>
      <td colspan="1" id="fuente1"><input name="46"  type="text"  id="46" placeholder="V- Die" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='46'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n46" type="hidden" id="n46" value="46" /></td>
    </tr>
    <tr>
      <td colspan="2"id="fuente1">Filter Front</td>
      <td colspan="2"id="fuente1"><input name="31"  type="text"  id="31" placeholder="Tor A" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='31'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n31" type="hidden" id="n31" value="31" /></td>
      <td colspan="2"id="fuente1"><input name="32"  type="text"  id="32" placeholder="Tor B" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='32'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n32" type="hidden" id="n32" value="32" /></td>
      <td colspan="2"id="fuente1"><input name="33"  type="text"  id="33" placeholder="Tor C" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='33'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n33" type="hidden" id="n33" value="33" /></td>
      <td colspan="1" id="fuente1">Die Head</td>
      <td colspan="1" id="fuente1"><input name="47"  type="text"  id="47" placeholder="Die Head" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='47'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n47" type="hidden" id="n47" value="47" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">Filter Back</td>
      <td colspan="2"id="fuente1"><input name="34"  type="text"  id="34" placeholder="Tor A" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='34'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n34" type="hidden" id="n34" value="34" /></td>
      <td colspan="2"id="fuente1"><input name="35"  type="text"  id="35" placeholder="Tor B" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='35'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n35" type="hidden" id="n35" value="35" /></td>
      <td colspan="2"id="fuente1"><input name="36"  type="text"  id="36" placeholder="Tor C" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='36'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n36" type="hidden" id="n36" value="36" /></td>
      <td colspan="1" id="fuente1">Die Lid</td>
      <td colspan="1" id="fuente1"><input name="48"  type="text"  id="48" placeholder="Die Lid" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='48'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n48" type="hidden" id="n48" value="48" /></td>
    </tr>
    <tr>
      <td colspan="2"id="fuente1">Sec- Barrel</td>
      <td colspan="2"id="fuente1"><input name="37"  type="text"  id="37" placeholder="Tor A" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='37'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n37" type="hidden" id="n37" value="37" /></td>
      <td colspan="2"id="fuente1"><input name="38"  type="text"  id="38" placeholder="Tor B" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='38'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n38" type="hidden" id="n38" value="38" /></td>
      <td colspan="2"id="fuente1"><input name="39"  type="text"  id="39" placeholder="Tor C" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='39'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n39" type="hidden" id="n39" value="39" /></td>
      <td colspan="1" id="fuente1">Die Center Lower</td>
      <td colspan="1" id="fuente1"><input name="49"  type="text"  id="49" placeholder="Die Center Lower" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='49'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n49" type="hidden" id="n49" value="49" /></td>
    </tr>
    <tr id="tr1">
      <td colspan="2"id="fuente1">Melt Temp &deg;C</td>
      <td colspan="2"id="fuente1"><input name="40"  type="text"  id="40" placeholder="Tor A" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='40'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n40" type="hidden" id="n40" value="40" /></td>
      <td colspan="2"id="fuente1"><input name="41"  type="text"  id="41" placeholder="Tor B" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='41'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n41" type="hidden" id="n41" value="41" /></td>
      <td colspan="2"id="fuente1"><input name="42"  type="text"  id="42" placeholder="Tor C" size="10"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='42'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n42" type="hidden" id="n42" value="42" /></td>
      <td colspan="1" id="fuente1">Die Center Upper</td>
      <td colspan="1" id="fuente1"><input name="50"  type="text"  id="50" placeholder="Die Center Upper" size="5"value="<?php $con="select * from Tbl_caracteristicas_valor WHERE id_ref_cv=$id_ref AND b_borrado_cv='0' AND id_c_cv='50'";$res=mysql_query($con);$num=mysql_fetch_assoc($res);echo $num['str_valor_cv'];?>"/>
        <input name="n50" type="hidden" id="n50" value="50" /></td>
    </tr>
    <tr>
      <td colspan="10"id="fuente1">Estos son valores de referencia que pueden cambiar de acuerdo    a velocidad, temperatura ambiente, calibre, etc.</td>
    </tr>
    <tr id="tr1">
      <td colspan="10"id="fuente1">&nbsp;</td>
    </tr>
    <tr id="tr1">
          <td colspan="10" id="fuente2"><input type="hidden" name="id_proceso_cv" id="id_proceso_cv" value="<?php echo $row_editar_m['id_proceso'] ?>"/>
            <input type="hidden" name="id_pm_cv" id="id_pm_cv" value="<?php echo $row_editar_m['id_pm'] ?>"/>
            <input type="hidden" name="b_borrado_cv" id="b_borrado_cv" value="<?php echo $row_editar_m['b_borrado_pm'] ?>"/>            
            <input name="submit" type="submit" class="botonFinalizar" value="Actualizar Caracteristicas" /></td>
        </tr>
    </table>
    <input type="hidden" name="MM_update" value="form2">
    </form>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);
//mysql_free_result($referencia);
mysql_free_result($editar_m);
mysql_free_result($referencia_copia);
mysql_free_result($materia_prima);
?>
