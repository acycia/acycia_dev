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

$maxRows_costos = 20;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$ano = $_GET['fecha'];
$mensual = $_GET['mensual'];
//Filtra fecha lleno
if($mensual != '0')
{
$query_costos = "SELECT * FROM Tbl_generadores_valor WHERE YEAR(fecha_ini_gv) = '$ano' AND MONTH(fecha_ini_gv) = '$mensual' AND YEAR(fecha_fin_gv) = '$ano' AND MONTH(fecha_fin_gv) = '$mensual' GROUP BY fecha_ini_gv DESC";
}
$query_limit_costos = sprintf("%s LIMIT %d, %d", $query_costos, $startRow_costos, $maxRows_costos);
$costos = mysql_query($query_limit_costos, $conexion1) or die(mysql_error());
$row_costos = mysql_fetch_assoc($costos);

if (isset($_GET['totalRows_costos'])) {
  $totalRows_costos = $_GET['totalRows_costos'];
} else {
  $all_costos = mysql_query($query_costos);
  $totalRows_costos = mysql_num_rows($all_costos);
}
$totalPages_costos = ceil($totalRows_costos/$maxRows_costos)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_mensual = "SELECT * FROM mensual ORDER BY id_mensual DESC";
$mensual = mysql_query($query_mensual, $conexion1) or die(mysql_error());
$row_mensual = mysql_fetch_assoc($mensual);
$totalRows_mensual = mysql_num_rows($mensual);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

$queryString_costos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_costos") == false && 
        stristr($param, "totalRows_costos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_costos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_costos = sprintf("&totalRows_costos=%d%s", $totalRows_costos, $queryString_costos);

session_start();
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body><div align="center">

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
<li><a href="costos_generales.php">COSTOS GENERALES</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="costos_listado_gga2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td nowrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
<td nowrap="nowrap" id="titulo2" width="50%">LISTADO DE VALORES GGA Y CIF</td>
<td nowrap="nowrap" id="codigo" width="25%">VERSION : 2</td>
<td nowrap="nowrap" id="codigo" width="25%">&nbsp;</td>
</tr>
<tr>
  <td colspan="4" id="fuente2"><select name="fecha" id="fecha">
    <option value="0"<?php if (!(strcmp("", $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>ANUAL</option>
    <?php
do {  
?>
    <option value="<?php echo $row_ano['anual']?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['anual']?></option>
    <?php
} while ($row_ano = mysql_fetch_assoc($ano));
  $rows = mysql_num_rows($ano);
  if($rows > 0) {
      mysql_data_seek($ano, 0);
	  $row_ano = mysql_fetch_assoc($ano);
  }
?>
  </select>
    <select name="mensual" id="mensual" onChange="if(form1.mensual.value) { consulta_gga_mensual(); }else { alert('Debe Seleccionar una Mes')}">
      <option value="0"<?php if (!(strcmp("", $_GET['mensual']))) {echo "selected=\"selected\"";} ?>>MENSUAL</option>
      <?php
    do {  
    ?>
      <option value="<?php echo $row_mensual['id_mensual']?>"<?php if (!(strcmp($row_mensual['id_mensual'], $_GET['mensual']))) {echo "selected=\"selected\"";} ?>><?php echo $row_mensual['mensual']?></option>
      <?php
    } while ($row_mensual = mysql_fetch_assoc($mensual));
      $rows = mysql_num_rows($mensual);
      if($rows > 0) {
          mysql_data_seek($mensual, 0);
          $row_mensual = mysql_fetch_assoc($mensual);
      }
    ?>
    </select></td>
  </tr>
  <tr>
    <td colspan="4" id="dato3"><?php if($row_usuario['tipo_usuario'] != '11') { ?>
      <a href="costos_generadores_asignacion_cif_gga.php"><img src="images/mas.gif" alt="ADD VALORES GGA Y CIF" title="ADD VALORES GGA Y CIF" border="0" style="cursor:hand;"/></a><?php } ?>
      <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">GGA HASTA LA FECHA</td>
    <td nowrap="nowrap"id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap"id="titulo4">FECHA FINAL</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_costos['fecha_ini_gv']; ?>&fecha_fin_gv=<?php echo $row_costos['fecha_fin_gv']; ?>" target="new" style="text-decoration:none; color:#000000">GGA</a></td>
      <td id="dato2"><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_costos['fecha_ini_gv']; ?>&fecha_fin_gv=<?php echo $row_costos['fecha_fin_gv']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['fecha_ini_gv']; ?></a></td>
      <td id="dato2"><a href="costos_generadores_asignacion_cif_gga.php?fecha_ini_gv=<?php echo $row_costos['fecha_ini_gv']; ?>&fecha_fin_gv=<?php echo $row_costos['fecha_fin_gv']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_costos['fecha_fin_gv']; ?></a></td>
    <td nowrap="nowrap" id="dato2"><?php if($row_costos['estado_gv']=='0'){?><a href="delete.php?id_gga_fecha=<?php echo $row_costos['fecha_ini_gv']; ?>&estado_gga=1" onclick="estado_gastos()"> ACTIVO </a><?php }else if($row_costos['estado_gv']=='1'){?><a href="delete.php?id_gga_fecha=<?php echo $row_costos['fecha_ini_gv']; ?>&estado_gga=0" onclick="estado_gastos()"> INACTIVO <?php }?></a>
        <!--<a href="javascript:estado_gastos('id_gga_fecha',<?php echo $row_costos['fecha_ini_gv']; ?>,'costos_listado_gga.php')">CAMBIO ESTADO<?php echo $row_costos['fecha_ini_gv']; ?></a>--></td>
    </tr>
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>

<table id="tabla1">
  <tr>
    <td id="dato1" width="25%"><?php if ($pageNum_costos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, 0, $queryString_costos); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, max(0, $pageNum_costos - 1), $queryString_costos); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, min($totalPages_costos, $pageNum_costos + 1), $queryString_costos); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, $totalPages_costos, $queryString_costos); ?>">&Uacute;ltimo</a>
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

mysql_free_result($costos);

mysql_free_result($numero);

?>