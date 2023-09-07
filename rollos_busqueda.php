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

$maxRows_rollos = 20;
$pageNum_rollos = 0;
if (isset($_GET['pageNum_rollos'])) {
  $pageNum_rollos = $_GET['pageNum_rollos'];
}
$startRow_rollos = $pageNum_rollos * $maxRows_rollos;

mysql_select_db($database_conexion1, $conexion1);
$query_rollos = "SELECT * FROM materia_prima_rollos ORDER BY nombre_rollo ASC";
$query_limit_rollos = sprintf("%s LIMIT %d, %d", $query_rollos, $startRow_rollos, $maxRows_rollos);
$rollos = mysql_query($query_limit_rollos, $conexion1) or die(mysql_error());
$row_rollos = mysql_fetch_assoc($rollos);

if (isset($_GET['totalRows_rollos'])) {
  $totalRows_rollos = $_GET['totalRows_rollos'];
} else {
  $all_rollos = mysql_query($query_rollos);
  $totalRows_rollos = mysql_num_rows($all_rollos);
}
$totalPages_rollos = ceil($totalRows_rollos/$maxRows_rollos)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_codigo = "SELECT * FROM materia_prima_rollos ORDER BY cod_rollo ASC";
$codigo = mysql_query($query_codigo, $conexion1) or die(mysql_error());
$row_codigo = mysql_fetch_assoc($codigo);
$totalRows_codigo = mysql_num_rows($codigo);

mysql_select_db($database_conexion1, $conexion1);
$query_nombre = "SELECT * FROM materia_prima_rollos ORDER BY nombre_rollo ASC";
$nombre = mysql_query($query_nombre, $conexion1) or die(mysql_error());
$row_nombre = mysql_fetch_assoc($nombre);
$totalRows_nombre = mysql_num_rows($nombre);

mysql_select_db($database_conexion1, $conexion1);
$query_referencia = "SELECT * FROM referencia order by id_ref desc";
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$queryString_rollos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rollos") == false && 
        stristr($param, "totalRows_rollos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rollos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rollos = sprintf("&totalRows_rollos=%d%s", $totalRows_rollos, $queryString_rollos);
?>
<html>
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
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
	<form action="rollos_busqueda1.php" method="get" name="consulta">
	<div align="center">
<table id="tabla1">
<tr>
  <td id="titulo2">FILTRO DE MATERIA PRIMA ( ROLLOS ) </td>
  <td id="dato3"><a href="rollos_busqueda.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="rollos_add.php" target="_top"><img src="images/mas.gif" alt="ADD ROLLO" border="0" style="cursor:hand;"/></a><a href="rollos.php" target="_top"><img src="images/r.gif" alt="ROLLOS" border="0" style="cursor:hand;"/></a></td>
</tr>
<tr>
  <td colspan="2" id="fuente2"><select name="cod_rollo" id="cod_rollo">
    <option value="0">CODIGO</option>
    <?php
do {  
?>
    <option value="<?php echo $row_codigo['cod_rollo']?>"><?php echo $row_codigo['cod_rollo']?></option>
    <?php
} while ($row_codigo = mysql_fetch_assoc($codigo));
  $rows = mysql_num_rows($codigo);
  if($rows > 0) {
      mysql_data_seek($codigo, 0);
	  $row_codigo = mysql_fetch_assoc($codigo);
  }
?>
    </select>
  <select name="nombre_rollo" id="nombre_rollo">
    <option value="0">ROLLO</option>
    <?php
do {  
?>
    <option value="<?php echo $row_nombre['nombre_rollo']?>"><?php echo $row_nombre['nombre_rollo']?></option>
    <?php
} while ($row_nombre = mysql_fetch_assoc($nombre));
  $rows = mysql_num_rows($nombre);
  if($rows > 0) {
      mysql_data_seek($nombre, 0);
	  $row_nombre = mysql_fetch_assoc($nombre);
  }
?>
    </select>
	<select name="ref_prod_rollo" id="ref_prod_rollo">
	  <option value="0">REF</option>
	  <?php
do {  
?>
	  <option value="<?php echo $row_referencia['id_ref']?>"><?php echo $row_referencia['cod_ref']?></option>
	  <?php
} while ($row_referencia = mysql_fetch_assoc($referencia));
  $rows = mysql_num_rows($referencia);
  if($rows > 0) {
      mysql_data_seek($referencia, 0);
	  $row_referencia = mysql_fetch_assoc($referencia);
  }
?>
    </select>
    <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.cod_rollo.value=='0' && consulta.nombre_rollo.value=='0' && consulta.ref_prod_rollo.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</div>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td id="dato2"><input name="borrado" type="hidden" id="borrado" value="13" />
      <input name="Input" type="submit" value="X"/></td>
    <td colspan="7" id="dato1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> 
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
      <?php }
  if($id == '') { ?>
      <div id="dato1"> <?php echo "Si elimina un ROLLO, sera definitivamente."; ?> </div>      <?php }
  ?></td>
    </tr>  
  <tr id="tr2">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td id="titulo4">CODIGO</td>
    <td id="titulo4">NOMBRE DEL ROLLO </td>
    <td id="titulo4">REF</td>
    <td id="titulo4">PRESENTACION</td>
    <td id="titulo4">ANCHO</td>
    <td id="titulo4">CALIBRE</td>
    <td id="titulo4">TRATAMIENTO</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_rollos['id_rollo']; ?>" /></td>
      <td id="dato1"><a href="rollo_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['cod_rollo']; ?></a></td>
      <td id="dato1"><a href="rollo_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['nombre_rollo']; ?></a></td>
      <td id="dato2"><a href="referencia_vista.php?id_ref=<?php echo $row_rollos['ref_prod_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $ref=$row_rollos['ref_prod_rollo'];
	  if($ref!=''){
	$sqlref="SELECT * FROM referencia WHERE id_ref = $ref";
	$resultref= mysql_query($sqlref);
	$numref= mysql_num_rows($resultref);
	if($numref >='1') { $cod_ref=mysql_result($resultref,0,'cod_ref'); }
	echo $cod_ref; } ?></a></td>
      <td id="dato2"><a href="rollo_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['presentacion_rollo']; ?></a></td>
      <td id="dato3"><a href="rollo_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['ancho_rollo']; ?></a></td>
      <td id="dato3"><a href="rollo_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['calibre_rollo']; ?></a></td>
      <td id="dato2"><a href="rollo_vista.php?id_rollo=<?php echo $row_rollos['id_rollo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos['tratamiento_rollo']; ?></a></td>
    </tr>
    <?php } while ($row_rollos = mysql_fetch_assoc($rollos)); ?>
</table>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="fuente2"><?php if ($pageNum_rollos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rollos=%d%s", $currentPage, 0, $queryString_rollos); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="fuente2"><?php if ($pageNum_rollos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rollos=%d%s", $currentPage, max(0, $pageNum_rollos - 1), $queryString_rollos); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="fuente2"><?php if ($pageNum_rollos < $totalPages_rollos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rollos=%d%s", $currentPage, min($totalPages_rollos, $pageNum_rollos + 1), $queryString_rollos); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="fuente2"><?php if ($pageNum_rollos < $totalPages_rollos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rollos=%d%s", $currentPage, $totalPages_rollos, $queryString_rollos); ?>">&Uacute;ltimo</a>
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

mysql_free_result($rollos);

mysql_free_result($codigo);

mysql_free_result($nombre);

mysql_free_result($referencia);
?>