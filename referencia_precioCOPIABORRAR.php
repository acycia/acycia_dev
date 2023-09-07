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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
$currentPage = $_SERVER["PHP_SELF"];

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$maxRows_cotizacion = 20;
$pageNum_cotizacion = 0;
if (isset($_GET['pageNum_cotizacion'])) {
  $pageNum_cotizacion = $_GET['pageNum_cotizacion'];
}
$startRow_cotizacion = $pageNum_cotizacion * $maxRows_cotizacion;

mysql_select_db($database_conexion1, $conexion1);
$cod_ref = $_GET['cod_ref'];
$id_c = $_GET['id_c'];
$tipo_ref = $_GET['tipo_ref'];
$tipo_bols = $_GET['tipo_bols'];
$ancho = $_GET['ancho'];
$largo = $_GET['largo'];
$calibre = $_GET['calibre'];
//operacion rango de ancho y largo
$min='3'; $max='3';
$anchomin = $ancho-$min;
$anchomax = $max+$ancho;
$largomin = $largo-$min;
$largomax = $max+$largo;
//calibre
$minc='1'; $maxc='1';
$calibmin = $calibre-$minc;
$calibmax = $maxc+$calibre;

//Filtra todos vacios

$BD= PrecioRef($cod_ref); //la funcion define que tipo de ref es bolsa, lamina, packing

if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho == '0' && $largo == '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia order by id_ref desc";
}
//Filtra cod ref lleno
if($cod_ref != '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho == '0' && $largo == '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE cod_ref='$cod_ref'  ORDER BY fecha_registro1_ref DESC";
}
//Filtra cliente lleno
/*if($id_c != '0' && $cod_ref == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho == '0' && $largo == '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia, Tbl_cotizaciones WHERE Tbl_cotizaciones.Str_nit='$id_c' and Tbl_referencia.Str_nit  ORDER BY Tbl_referencia.fecha_registro1_ref DESC";
}
//Filtra cod y cliente llenos
if($cod_ref != '0' && $id_c != '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho == '0' && $largo == '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE cod_ref='$cod_ref' ORDER BY fecha_registro1_ref DESC";
}
//Filtra tipo_ref
if($cod_ref == '0' && $id_c == '0' && $tipo_ref != '' && $tipo_bols == '0' && $ancho == '0' && $largo == '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE B_generica ='$tipo_ref' order by id_ref desc";
}
//Filtra tipo bolsa
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols != '0' && $ancho == '0' && $largo == '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE material_ref='$tipo_bols' order by id_ref desc";
}
//Filtra ancho
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho != '0' && $largo == '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE ancho_ref BETWEEN $anchomin AND $anchomax ORDER BY ancho_ref DESC";
}
//Filtra largo
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho == '0' && $largo != '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE largo_ref BETWEEN $largomin AND $largomax ORDER BY largo_ref DESC";
}
//Filtra calibre
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho == '0' && $largo == '0' && $calibre != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE calibre_ref BETWEEN '$calibmin' AND '$calibmax' ORDER BY calibre_ref DESC";
}
//Filtra cliente, tipo ref, tipo bolsa, ancho, largo, calibre
if($cod_ref == '0' && $id_c != '0' && $tipo_ref != '' && $tipo_bols != '0' && $ancho != '0' && $largo != '0' && $calibre != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE Str_nit='$id_c' AND B_generica ='$tipo_ref'  AND Str_tipo_coextrusion ='$tipo_bols' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax  AND N_calibre BETWEEN $calibmin AND $calibmax ORDER BY N_referencia_c DESC";
}
//Filtra  tipo ref, tipo bolsa, ancho, largo, calibre
if($cod_ref == '0' && $id_c == '0' && $tipo_ref != '' && $tipo_bols != '0' && $ancho != '0' && $largo != '0' && $calibre != '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND B_generica ='$tipo_ref'  AND Str_tipo_coextrusion ='$tipo_bols' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax AND N_calibre BETWEEN $calibmin AND $calibmax ORDER BY N_referencia_c DESC";
}
//Filtra  tipo ref, ancho, largo, calibre
if($cod_ref == '0' && $id_c == '0' && $tipo_ref != '' && $tipo_bols == '0' && $ancho != '0' && $largo != '0' && $calibre != '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND B_generica ='$tipo_ref' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax AND N_calibre BETWEEN $calibmin AND $calibmax ORDER BY N_referencia_c DESC";
}
//Filtra  tipo ref, ancho, largo
if($cod_ref == '0' && $id_c == '0' && $tipo_ref != '' && $tipo_bols == '0' && $ancho != '0' && $largo != '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND B_generica ='$tipo_ref' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax ORDER BY N_referencia_c DESC";
}
//Filtra  tipo bolsa, ancho, largo, calibre
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols != '0' && $ancho != '0' && $largo != '0' && $calibre != '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND Str_tipo_coextrusion ='$tipo_bols' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax AND N_calibre BETWEEN $calibmin AND $calibmax ORDER BY N_referencia_c DESC";
}
//Filtra  tipo bolsa, ancho, largo
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols != '0' && $ancho != '0' && $largo != '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND Str_tipo_coextrusion ='$tipo_bols' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax ORDER BY N_referencia_c DESC";
}
//Filtra  ancho, largo, calibre
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho != '0' && $largo != '0' && $calibre != '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax AND N_calibre BETWEEN $calibmin AND $calibmax ORDER BY N_ancho DESC";
}
//Filtra  ancho, largo
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho != '0' && $largo != '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax ORDER BY N_ancho DESC";
}
//Filtra largo, calibre
if($cod_ref == '0' && $id_c == '0' && $tipo_ref == '' && $tipo_bols == '0' && $ancho == '0' && $largo != '0' && $calibre != '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND N_alto BETWEEN $largomin AND N_calibre BETWEEN $calibmin AND $calibmax ORDER BY N_alto DESC";
}
//Filtra  tipo ref,tipo bolsa 
if($cod_ref == '0' && $id_c == '0' && $tipo_ref != '' && $tipo_bols != '0' && $ancho == '0' && $largo == '0' && $calibre == '0')
{
$query_cotizacion = "SELECT * FROM $BD WHERE B_estado <> '2' AND B_generica ='$tipo_ref' AND Str_tipo_coextrusion ='$tipo_bols' AND N_ancho BETWEEN $anchomin AND $anchomax AND N_alto BETWEEN $largomin AND $largomax  AND N_calibre BETWEEN $calibmin AND $calibmax ORDER BY N_referencia_c DESC";
}*/

$query_limit_cotizacion = sprintf("%s LIMIT %d, %d", $query_cotizacion, $startRow_cotizacion, $maxRows_cotizacion);
$cotizacion = mysql_query($query_limit_cotizacion, $conexion1) or die(mysql_error());
$row_cotizacion = mysql_fetch_assoc($cotizacion);

if (isset($_GET['totalRows_cotizacion'])) {
  $totalRows_cotizacion = $_GET['totalRows_cotizacion'];
} else {
  $all_cotizacion = mysql_query($query_cotizacion);
  $totalRows_cotizacion = mysql_num_rows($all_cotizacion);
}
$totalPages_cotizacion = ceil($totalRows_cotizacion/$maxRows_cotizacion)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_numero = "SELECT * FROM Tbl_referencia WHERE estado_ref='1' order by id_ref desc";
$numero = mysql_query($query_numero, $conexion1) or die(mysql_error());
$row_numero = mysql_fetch_assoc($numero);
$totalRows_numero = mysql_num_rows($numero);

$queryString_cotizacion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_cotizacion") == false && 
        stristr($param, "totalRows_cotizacion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_cotizacion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_cotizacion = sprintf("&totalRows_cotizacion=%d%s", $totalRows_cotizacion, $queryString_cotizacion);

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
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
  <td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
	<form action="referencia_precio2.php" method="get" name="consulta" >
	<table id="tabla1">
<tr>
<td colspan="6" nowrap="nowrap" id="codigo">CODIGO: R1 - F03</td>
<td colspan="4" nowrap="nowrap" id="titulo2">REFERENCIAS COTIZADAS</td>
<td colspan="2" nowrap="nowrap" id="codigo">VERSION: 2</td>
</tr>
<tr>
  <td colspan="12" id="dato2"><select name="cod_ref" id="cod_ref">
    <option value="0" <?php if (!(strcmp(0, $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>>Seleccione la Referencia</option>
    <?php
do {  
?>
<option value="<?php echo $row_numero['cod_ref']?>"<?php if (!(strcmp($row_numero['cod_ref'], $_GET['cod_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_numero['cod_ref']?></option>
    <?php
} while ($row_numero = mysql_fetch_assoc($numero));
  $rows = mysql_num_rows($numero);
  if($rows > 0) {
      mysql_data_seek($numero, 0);
	  $row_numero = mysql_fetch_assoc($numero);
  }
?>
    </select>
    <select name="id_c" id="id_c"style="width:350px">
      <option value="0" <?php if (!(strcmp(0, $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>Seleccione el Cliente</option>
      <?php
do {  
?>
      <option value="<?php echo $row_cliente['nit_c']?>"<?php if (!(strcmp($row_cliente['nit_c'], $_GET['id_c']))) {echo "selected=\"selected\"";} ?>><?php $cad = htmlentities($row_cliente['nombre_c']);echo $cad;?></option>
      <?php
} while ($row_cliente = mysql_fetch_assoc($cliente));
  $rows = mysql_num_rows($cliente);
  if($rows > 0) {
      mysql_data_seek($cliente, 0);
	  $row_cliente = mysql_fetch_assoc($cliente);
  }
?>
    </select>
    <select name="tipo_ref" id="tipo_ref">
      <option value=""<?php if (!(strcmp("", $_GET['tipo_ref']))) {echo "selected=\"selected\"";} ?>>Todas ref.</option>
      <option value="0"<?php if (!(strcmp("0", $_GET['tipo_ref']))) {echo "selected=\"selected\"";} ?>>Existentes</option>
      <option value="1"<?php if (!(strcmp("1", $_GET['tipo_ref']))) {echo "selected=\"selected\"";} ?>>Genericas</option>
    </select>
    <select name="tipo_bols" id="tipo_bols">
      <option value="0"<?php if (!(strcmp("0", $_GET['tipo_bols']))) {echo "selected=\"selected\"";} ?>>Tipo polsa</option>
      <option value="NATURAL"<?php if (!(strcmp("NATURAL", $_GET['tipo_bols']))) {echo "selected=\"selected\"";} ?>>Natural</option>
      <option value="PIGMENTADO"<?php if (!(strcmp("PIGMENTADO", $_GET['tipo_bols']))) {echo "selected=\"selected\"";} ?>>Pigmentada</option>
    </select>
    Ancho
    <input type="number" name="ancho" id="ancho" min="0.00" step="0.01" style="width:40px" value="<?php echo $_GET['ancho']; ?>"/>
    Largo
    <input type="number" name="largo" id="largo" min="0.00" step="0.01" style="width:40px" value="<?php echo $_GET['largo']; ?>"/>
    Calibre
    <input type="number" name="calibre" id="calibre" min="0.00" step="0.01" style="width:40px" value="<?php echo $_GET['calibre']; ?>"/>    
    <input type="submit" name="Submit" value="FILTRO" />    
    <a href="cotizacion_bolsa.php"></a></td>
  </tr>
</table>
<table id="tabla1">
  <tr>
    <td colspan="2"></td>
    <td colspan="3"></td>
    <td colspan="8" id="dato3"><?php if($row_usuario['tipo_usuario'] != '11') { ?>
      <a href="cotizacion_general_menu.php"><img src="images/mas.gif" alt="ADD COTIZACION" title="ADD COTIZACION" border="0" style="cursor:hand;"/></a><a href="cotizaciones_clientes.php"></a><?php } ?>
      <a href="referencias.php" target="_top"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS"border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php" target="_top"><img src="images/i.gif" alt="REF'S INACTIVAS"title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="cotizacion_general_menu.php"></a><a href="referencia_precio.php"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">N&deg; REF</td>
    <td nowrap="nowrap" id="titulo4">TIPO REF</td>
    <td nowrap="nowrap" id="titulo4">COTIZ</td>
    <td nowrap="nowrap" id="titulo4">Cliente</td>
    <td nowrap="nowrap" id="titulo4">TIPO</td>
    <td nowrap="nowrap" id="titulo4">BOLSA</td>
    <td nowrap="nowrap" id="titulo4">Ancho</td>
    <td nowrap="nowrap" id="titulo4">Largo</td>
    <td nowrap="nowrap" id="titulo4">Solapa</td>
    <td nowrap="nowrap" id="titulo4">Bolsillo</td>
    <td nowrap="nowrap" id="titulo4">Calibre</td>
    <td nowrap="nowrap" id="titulo4">Precio $</td>
    <td nowrap="nowrap" id="titulo4">Fecha Creacion</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;cod_ref=<?php echo $row_cotizacion['cod_ref']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "8"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['cod_ref']; ?></a></td>
       <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['n_cotiz_ref']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000">
	  <?php 
		if($row_cotizacion['B_generica']=='0'){echo "Existente";}else{echo "Generica";};
	  ?></a>
      </td>     
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_cotizacion']; ?></a></td>
      <td id="talla1"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000">
	  <?php 
        $nit_c=$row_cotizacion['Str_nit'];
        $sqln="SELECT nombre_c FROM cliente WHERE nit_c='$nit_c'"; 
        $resultn=mysql_query($sqln); 
        $numn=mysql_num_rows($resultn); 
        if($numn >= '1') 
        { $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); echo $nit_cliente_c; }
	  ?>
      </a></td>      
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['Str_tipo_coextrusion']; ?></a></td>
      <td id="dato2">	  <?php 
        echo $row_cotizacion['tipo_bolsa_ref'];
	  ?></td>
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_ancho']; ?></a></td> 
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_alto']; ?></a></td>
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_solapa']; ?></a></td>
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php if($row_cotizacion['N_tamano_bolsillo']!=''){ echo $row_cotizacion['N_tamano_bolsillo'];}else{echo "0.00";} ?></a></td>
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_calibre']; ?></a></td>
      <td id="dato2"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['N_precio'],$row_cotizacion['N_precio_vnta'];; ?></a></td>
      <td id="dato1"><a href="control_tablas.php?n_cotiz=<?php echo $row_cotizacion['N_cotizacion']; ?>&amp;cod_ref=<?php echo $row_cotizacion['N_referencia_c']; ?>&Str_nit=<?php echo $row_cotizacion['Str_nit']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>&case=<?php echo "7"; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['fecha_creacion']; ?></a></td>
    </tr>
    <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
</table>
<table id="tabla1">
  <tr>
    <td id="dato2" colspan="3"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, 0, $queryString_cotizacion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato2" colspan="3"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, max(0, $pageNum_cotizacion - 1), $queryString_cotizacion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato2" colspan="3"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, min($totalPages_cotizacion, $pageNum_cotizacion + 1), $queryString_cotizacion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato2" colspan="3"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, $totalPages_cotizacion, $queryString_cotizacion); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</form>
</td>
  </tr>
</table>
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

mysql_free_result($cotizacion);

mysql_free_result($numero);

?>