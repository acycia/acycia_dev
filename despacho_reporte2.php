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

mysql_select_db($database_conexion1, $conexion1);
$query_lista = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$lista = mysql_query($query_lista, $conexion1) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

$maxRows_despacho_reporte = 20;
$pageNum_despacho_reporte = 0;
if (isset($_GET['pageNum_despacho_reporte'])) {
  $pageNum_despacho_reporte = $_GET['pageNum_despacho_reporte'];
}
$startRow_despacho_reporte = $pageNum_despacho_reporte * $maxRows_despacho_reporte;

mysql_select_db($database_conexion1, $conexion1);
$id_c = $_GET['id_c'];
//Filtra todos vacios
if($id_c == '0')
{
$query_despacho_reporte = "SELECT * FROM Tbl_tiquete_numeracion WHERE id_despacho IS NULL ORDER BY int_op_tn DESC";
}
//Filtra id_c lleno
if($id_c != '0')
{
$query_despacho_reporte = "SELECT * 
FROM Tbl_orden_produccion, Tbl_tiquete_numeracion
WHERE Tbl_orden_produccion.str_nit_op =  '$id_c'
AND Tbl_orden_produccion.id_op = Tbl_tiquete_numeracion.int_op_tn
AND Tbl_tiquete_numeracion.id_despacho IS NULL  
ORDER BY Tbl_tiquete_numeracion.int_op_tn DESC";
}
$query_limit_despacho_reporte = sprintf("%s LIMIT %d, %d", $query_despacho_reporte, $startRow_despacho_reporte, $maxRows_despacho_reporte);
$despacho_reporte = mysql_query($query_limit_despacho_reporte, $conexion1) or die(mysql_error());
$row_despacho_reporte = mysql_fetch_assoc($despacho_reporte);

if (isset($_GET['totalRows_despacho_reporte'])) {
  $totalRows_despacho_reporte = $_GET['totalRows_despacho_reporte'];
} else {
  $all_despacho_reporte = mysql_query($query_despacho_reporte);
  $totalRows_despacho_reporte = mysql_num_rows($all_despacho_reporte);
}
$totalPages_despacho_reporte = ceil($totalRows_despacho_reporte/$maxRows_despacho_reporte)-1;

$queryString_despacho_reporte = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_despacho_reporte") == false && 
        stristr($param, "totalRows_despacho_reporte") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_despacho_reporte = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_despacho_reporte = sprintf("&totalRows_despacho_reporte=%d%s", $totalRows_despacho_reporte, $queryString_despacho_reporte);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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
    <td colspan="2" align="center">
	<form action="despacho_reporte2.php" method="get" name="consulta">
	<table id="tabla1">
	  <tr>
	  <td id="subtitulo">REFERENCIAS SIN DESPACHO</td>
	  </tr>
	  <tr>
	  <td id="fuente2">
      <select name="id_c" id="id_c" style="width:350px">
        <option value="0"<?php if (!(strcmp("", $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>Seleccione Todos los Cliente</option>
        <?php
do {  
?>
        <option value="<?php echo $row_lista['nit_c']?>"<?php if (!(strcmp($row_lista['nit_c'], $_GET['id_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista['nombre_c']?></option>
        <?php
} while ($row_lista = mysql_fetch_assoc($lista));
  $rows = mysql_num_rows($lista);
  if($rows > 0) {
      mysql_data_seek($lista, 0);
	  $row_lista = mysql_fetch_assoc($lista);
  }
?>
      </select>
      <input type="submit" name="Submit" value="FILTRO" />      </td>
  </tr>
</table>
<table id="tabla1">
  <tr>
    <td colspan="2" id="dato1">&nbsp;</td>
    <td colspan="8"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
    <td id="dato3"><a href="despacho_reporte.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">O.P.</td>
    <td nowrap="nowrap" id="titulo4">N° DESPACHO</td>    
    <td nowrap="nowrap" id="titulo4">FECHA</td>
    <td nowrap="nowrap" id="titulo4">CAJAS</td>    
    <td nowrap="nowrap" id="titulo4">PAQUETES</td>    
    <td nowrap="nowrap" id="titulo4">UND/PAQ</td>
    <td nowrap="nowrap" id="titulo4">UND/CAJA</td>
    <td nowrap="nowrap" id="titulo4">BOLSAS</td>    
    <td nowrap="nowrap" id="titulo4">DESDE</td>
    <td nowrap="nowrap" id="titulo4">HASTA</td>
    <td nowrap="nowrap" id="titulo4">RESPONSABLE</td> 
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato3"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_despacho_reporte['int_op_tn']; ?></strong></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php if ($row_despacho_reporte['id_despacho']==NULL){echo "S/D";}; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_despacho_reporte['fecha_ingreso_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_despacho_reporte['int_caja_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_despacho_reporte['int_paquete_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_despacho_reporte['int_undxpaq_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_despacho_reporte['int_undxcaja_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_despacho_reporte['int_bolsas_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_despacho_reporte['int_desde_tn']; ?></a></td>
      <td id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_despacho_reporte['int_hasta_tn']; ?></a></td>
      <td nowrap="nowrap" id="dato2"><a href="despacho_direccion2.php?id_op=<?php echo $row_despacho_reporte['int_op_tn']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
	$empleado=$row_despacho_reporte['int_cod_empleado_tn'];
	$sqln="SELECT * FROM empleado WHERE codigo_empleado='$empleado'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_empleado'); $ca = htmlentities ($nombre_cliente_c); echo $ca; }
	else { echo "";	} ?>
    </a></td>
    </tr>
    <?php } while ($row_despacho_reporte = mysql_fetch_assoc($despacho_reporte)); ?>
</table>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_despacho_reporte > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_despacho_reporte=%d%s", $currentPage, 0, $queryString_despacho_reporte); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center" id="dato2"><?php if ($pageNum_despacho_reporte > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_despacho_reporte=%d%s", $currentPage, max(0, $pageNum_despacho_reporte - 1), $queryString_despacho_reporte); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_despacho_reporte < $totalPages_despacho_reporte) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_despacho_reporte=%d%s", $currentPage, min($totalPages_despacho_reporte, $pageNum_despacho_reporte + 1), $queryString_despacho_reporte); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_despacho_reporte < $totalPages_despacho_reporte) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_despacho_reporte=%d%s", $currentPage, $totalPages_despacho_reporte, $queryString_despacho_reporte); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table></td>
  </tr></table>
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

mysql_free_result($lista);

mysql_free_result($despacho_reporte);

?>
