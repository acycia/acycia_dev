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

$maxRows_cotizacion = 20;
$pageNum_cotizacion = 0;
if (isset($_GET['pageNum_cotizacion'])) {
  $pageNum_cotizacion = $_GET['pageNum_cotizacion'];
}
$startRow_cotizacion = $pageNum_cotizacion * $maxRows_cotizacion;

mysql_select_db($database_conexion1, $conexion1);
$ref = $_GET['ref'];
//Filtra ref vacio
if($ref== '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE estado_ref='1' order by id_ref desc";
}
//FILTRA REF LLENO
if($ref != '0')
{
$query_cotizacion = "SELECT * FROM Tbl_referencia WHERE cod_ref='$ref' AND estado_ref='1' order by id_ref desc";
}
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
$query_numero = "SELECT * FROM Tbl_referencia  WHERE estado_ref='1' order by id_ref desc";
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
    <td colspan="2"  id="linea1">
<form action="costos_referencia_cm2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td nowrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
<td colspan="4" nowrap="nowrap" id="titulo2" width="50%">REFERENCIAS POR CM&sup2;</td>
<td nowrap="nowrap" id="codigo" width="25%">VERSION : 2</td>
</tr>
<tr>
  <td colspan="6" id="fuente2"><select name="ref" id="ref" onChange="if(form1.ref.value) { consulta_cm(); }else { alert('Debe Seleccionar una REF')}">
    <option value="0"<?php if (!(strcmp("", $_GET['ref']))) {echo "selected=\"selected\"";} ?>>Seleccione la Referencia</option>
    <?php
do {  
?><option value="<?php echo $row_numero['cod_ref']?>"<?php if (!(strcmp($row_numero['cod_ref'], $_GET['ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_numero['cod_ref']?></option>
    <?php
} while ($row_numero = mysql_fetch_assoc($numero));
  $rows = mysql_num_rows($numero);
  if($rows > 0) {
      mysql_data_seek($numero, 0);
	  $row_numero = mysql_fetch_assoc($numero);
  }
?>
    </select></td>
  </tr>
</table>
<table id="tabla1">
  <tr>
    <td colspan="6" id="dato3"><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">N&deg; REF</td>
    <td nowrap="nowrap" id="titulo4">NOMBRE REF</td>
    <td nowrap="nowrap" id="titulo4">ANCHO</td>
    <td nowrap="nowrap" id="titulo4">LARGO</td>
    <td nowrap="nowrap" id="titulo4">SOLAPA</td>
    <td nowrap="nowrap"id="titulo4">CM&sup2;</td>

  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><?php echo $row_cotizacion['cod_ref']; ?></td>
      <td id="dato2"><a href="ref_ac_ref_cliente_add.php" target="new"  style="text-decoration:none; color:#000000">
        <?php 
	  $ref=$row_cotizacion['cod_ref'];
	  $sqln="SELECT * FROM  Tbl_refcliente WHERE int_ref_ac_rc=$ref"; 
	  $resultn=mysql_query($sqln); 
	  $numn=mysql_num_rows($resultn); 
	  if($numn >= '1') 
	  { $nombre_ref=mysql_result($resultn,0,'str_ref_cl_rc'); $nr = $nombre_ref; echo $nr; }
	  else { echo "crear nombre";	
	  }?>
      </a></td>      
      <td id="dato2"><?php echo $row_cotizacion['ancho_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['largo_ref']; ?></td>
      <td id="dato2"><?php echo $row_cotizacion['solapa_ref']; ?></td>
      <td id="dato2"><?php $cm_b=($row_cotizacion['ancho_ref']*$row_cotizacion['largo_ref']); $cm_s=($row_cotizacion['ancho_ref']*$row_cotizacion['solapa_ref']); echo $cm_b+$cm_s; ?></td>
    </tr>
    <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
</table>
<table id="tabla1">
  <tr>
    <td id="dato1" width="25%"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, 0, $queryString_cotizacion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, max(0, $pageNum_cotizacion - 1), $queryString_cotizacion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, min($totalPages_cotizacion, $pageNum_cotizacion + 1), $queryString_cotizacion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
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