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

$maxRows_proveedores = 20;
$pageNum_proveedores = 0;
if (isset($_GET['pageNum_proveedores'])) {
  $pageNum_proveedores = $_GET['pageNum_proveedores'];
}
$startRow_proveedores = $pageNum_proveedores * $maxRows_proveedores;

mysql_select_db($database_conexion1, $conexion1);
$id_p = $_GET['id_p'];
$tipo_p = $_GET['tipo_p'];
$estado_p = $_GET['estado_p'];
//Filtra todos vacios
if($id_p == '0' && $tipo_p == '0' )
{
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
}
if($id_p != '0' && $tipo_p == '0')
{
$query_proveedores = "SELECT * FROM proveedor WHERE id_p='$id_p'";
}
if($id_p == '0' && $tipo_p != '0')
{
$query_proveedores = "SELECT * FROM proveedor WHERE tipo_p='$tipo_p'";
}
if($id_p == '0' && $tipo_p == '0' && $estado_p!= '0')
{
$query_proveedores = "SELECT * FROM proveedor WHERE estado_p='$estado_p'";
}
if($id_p == '0' && $tipo_p != '0' && $estado_p!= '0')
{
$query_proveedores = "SELECT * FROM proveedor WHERE tipo_p='$tipo_p' AND estado_p='$estado_p'";
}
$query_limit_proveedores = sprintf("%s LIMIT %d, %d", $query_proveedores, $startRow_proveedores, $maxRows_proveedores);
$proveedores = mysql_query($query_limit_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);

if (isset($_GET['totalRows_proveedores'])) {
  $totalRows_proveedores = $_GET['totalRows_proveedores'];
} else {
  $all_proveedores = mysql_query($query_proveedores);
  $totalRows_proveedores = mysql_num_rows($all_proveedores);
}
$totalPages_proveedores = ceil($totalRows_proveedores/$maxRows_proveedores)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_tipo = "SELECT * FROM tipo ORDER BY nombre_tipo ASC";
$tipo = mysql_query($query_tipo, $conexion1) or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
$totalRows_tipo = mysql_num_rows($tipo);

mysql_select_db($database_conexion1, $conexion1);
$query_lista = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$lista = mysql_query($query_lista, $conexion1) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

$queryString_proveedores = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_proveedores") == false && 
        stristr($param, "totalRows_proveedores") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_proveedores = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_proveedores = sprintf("&totalRows_proveedores=%d%s", $totalRows_proveedores, $queryString_proveedores);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
  <td id="cabezamenu">
<ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
<form action="proveedor_busqueda1.php" method="get" name="consulta">
<div align="center">
<table id="tabla1">
<tr>
  <td id="titulo2">FILTRO DE PROVEEDORES </td>
  <td id="dato3"><a href="proveedor_add.php" target="_top"><img src="images/mas.gif" alt="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="proveedor_busqueda.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="proveedores.php" target="_top"><img src="images/cat.gif" alt="PROVEEDORES" border="0" style="cursor:hand;"/></a></td>
</tr>
<tr>
  <td colspan="2" id="fuente2"><select name="id_p" id="id_p" style="width:150px">
    <option value="0" <?php if (!(strcmp(0, $_GET['id_p']))) {echo "selected=\"selected\"";} ?>>PROVEEDOR</option>
    <?php
do {  
?><option value="<?php echo $row_lista['id_p']?>"<?php if (!(strcmp($row_lista['id_p'], $_GET['id_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista['proveedor_p']?></option>
      <?php
} while ($row_lista = mysql_fetch_assoc($lista));
  $rows = mysql_num_rows($lista);
  if($rows > 0) {
      mysql_data_seek($lista, 0);
	  $row_lista = mysql_fetch_assoc($lista);
  }
?>
    </select><select name="tipo_p" id="tipo_p">
      <option value="0" <?php if (!(strcmp(0, $_GET['tipo_p']))) {echo "selected=\"selected\"";} ?>>TIPO</option><?php
do {  
?><option value="<?php echo $row_tipo['id_tipo']?>"<?php if (!(strcmp($row_tipo['id_tipo'], $_GET['tipo_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_tipo['nombre_tipo']?></option>
      <?php
} while ($row_tipo = mysql_fetch_assoc($tipo));
  $rows = mysql_num_rows($tipo);
  if($rows > 0) {
      mysql_data_seek($tipo, 0);
	  $row_tipo = mysql_fetch_assoc($tipo);
  }
?>
    </select>
    <select name="estado_p" size="1" id="estado_p" >
      <option value="ACTIVO"<?php if (!(strcmp("ACTIVO", $_GET['estado_p']))) {echo "selected=\"selected\"";} ?>>ACTIVO</option>
      <option value="INACTIVO"<?php if (!(strcmp("INACTIVO", $_GET['estado_p']))) {echo "selected=\"selected\"";} ?>>INACTIVO</option>
    </select>    <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_p.value=='0' && consulta.tipo_p.value=='0' && consulta.estado_p.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</div>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td id="dato2"><input name="borrado" type="hidden" id="borrado" value="9" />
      <input name="Input" type="submit" value="X"/></td>
    <td colspan="9" id="dato1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> 
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
      <?php }
  if($id == '') { ?>
      <div id="dato1"> <?php echo "Si elimina el proveedor, tambien eliminara los registros de selección y mejora respectivos"; ?> </div>      <?php }
  ?></td>
    </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td id="titulo4">PROVEEDOR</td>
    <td id="titulo4">TIPO</td>
    <td id="titulo4"><a href="proveedor_seleccion_edit.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top"><img src="images/e.gif" alt="ENCUESTA" border="0" /></a></td>
    <td id="titulo4">FECHA I. </td>
    <td id="titulo4">%INICIAL</td>
    <td id="titulo4">FECHA F. </td>
    <td id="titulo4">%FINAL</td>
    <td id="titulo4"><img src="images/m.gif" alt="MEJORAS" border="0"/></td>
    <td id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_proveedores['id_p']; ?>" /></td>
      <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['proveedor_p']; ?></a></td>
      <td id="dato1"><?php $tipo_p=$row_proveedores['tipo_p']; 
	$sqltipo="SELECT * FROM tipo WHERE id_tipo = $tipo_p";
	$resultipo= mysql_query($sqltipo);
	$numtipo= mysql_num_rows($resultipo);
	if($numtipo >='1') { $tipo_p=mysql_result($resultipo,0,'nombre_tipo'); }
	echo $tipo_p;
	 ?></td>
      <td id="dato2"><?php $id_p=$row_proveedores['id_p']; 
	$sqlp="SELECT * FROM proveedor_seleccion WHERE id_p_seleccion = $id_p";
	$resultp= mysql_query($sqlp);
	$nump= mysql_num_rows($resultp);
	if($nump >='1')
	{ ?>
          <a href="proveedor_seleccion_edit.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top"><img src="images/e.gif" alt="ENCUESTA" border="0" style="cursor:hand;"/></a>        <?php
	$primera_calificacion_p=mysql_result($resultp,0,'primera_calificacion_p');
	$fecha_encuesta_p=mysql_result($resultp,0,'fecha_encuesta_p');
	$ultima_calificacion_p=mysql_result($resultp,0,'ultima_calificacion_p');
	$fecha_ultima_calificacion_p=mysql_result($resultp,0,'fecha_ultima_calificacion_p');
	} else if($row_proveedores['tipo_p'] != '2') { ?>
          <a href="proveedor_seleccion_add.php?id_p=<?php echo $row_proveedores['id_p']; ?>"><img src="images/falta.gif" alt="FALTA ENCUESTA" border="0"></a>          <?php }
	?></td>
      <td id="dato2"><?php echo $fecha_encuesta_p; ?></td>
      <td id="dato2"><?php echo $primera_calificacion_p; ?></td>
      <td id="dato2"><?php echo $fecha_ultima_calificacion_p; ?></td>
      <td id="dato2"><?php echo $ultima_calificacion_p; ?></td>
      <td id="dato2"><?php $id_p=$row_proveedores['id_p']; 
	$sqlpm="SELECT * FROM proveedor_mejora WHERE id_p_pm = $id_p";
	$resultpm= mysql_query($sqlpm);
	$numpm= mysql_num_rows($resultpm);
	if($numpm >='1')
	{ ?>
          <a href="proveedor_mejoras.php?id_p=<?php echo $row_proveedores['id_p']; ?>" target="_top"><img src="images/m.gif" alt="MEJORA" border="0" style="cursor:hand;"/></a>        <?php }	?></td>
      <td id="dato2"><a href="proveedor_vista.php?id_p=<?php echo $row_proveedores['id_p']; ?>"target="_top" style="text-decoration:none; color:#000000"><?php echo $row_proveedores['estado_p']; ?></a></td>
    </tr>
    <?php } while ($row_proveedores = mysql_fetch_assoc($proveedores)); ?>
</table>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_proveedores > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_proveedores=%d%s", $currentPage, 0, $queryString_proveedores); ?>">Primero</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_proveedores > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_proveedores=%d%s", $currentPage, max(0, $pageNum_proveedores - 1), $queryString_proveedores); ?>">Anterior</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_proveedores < $totalPages_proveedores) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_proveedores=%d%s", $currentPage, min($totalPages_proveedores, $pageNum_proveedores + 1), $queryString_proveedores); ?>">Siguiente</a>
        <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_proveedores < $totalPages_proveedores) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_proveedores=%d%s", $currentPage, $totalPages_proveedores, $queryString_proveedores); ?>">&Uacute;ltimo</a>
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

mysql_free_result($proveedores);

mysql_free_result($tipo);

mysql_free_result($lista);
?>