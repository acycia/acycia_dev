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
$query_codigo_bolsa = "SELECT * FROM material_terminado_bolsas ORDER BY codigo_bolsa ASC";
$codigo_bolsa = mysql_query($query_codigo_bolsa, $conexion1) or die(mysql_error());
$row_codigo_bolsa = mysql_fetch_assoc($codigo_bolsa);
$totalRows_codigo_bolsa = mysql_num_rows($codigo_bolsa);

mysql_select_db($database_conexion1, $conexion1);
$query_bolsas = "SELECT * FROM material_terminado_bolsas ORDER BY nombre_bolsa ASC";
$bolsas = mysql_query($query_bolsas, $conexion1) or die(mysql_error());
$row_bolsas = mysql_fetch_assoc($bolsas);
$totalRows_bolsas = mysql_num_rows($bolsas);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM Tbl_referencia order by id_ref desc";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

$maxRows_bolsa = 20;
$pageNum_bolsa = 0;
if (isset($_GET['pageNum_bolsa'])) {
  $pageNum_bolsa = $_GET['pageNum_bolsa'];
}
$startRow_bolsa = $pageNum_bolsa * $maxRows_bolsa;

mysql_select_db($database_conexion1, $conexion1);
$query_bolsa = "SELECT * FROM material_terminado_bolsas ORDER BY nombre_bolsa ASC";
$query_limit_bolsa = sprintf("%s LIMIT %d, %d", $query_bolsa, $startRow_bolsa, $maxRows_bolsa);
$bolsa = mysql_query($query_limit_bolsa, $conexion1) or die(mysql_error());
$row_bolsa = mysql_fetch_assoc($bolsa);

if (isset($_GET['totalRows_bolsa'])) {
  $totalRows_bolsa = $_GET['totalRows_bolsa'];
} else {
  $all_bolsa = mysql_query($query_bolsa);
  $totalRows_bolsa = mysql_num_rows($all_bolsa);
}
$totalPages_bolsa = ceil($totalRows_bolsa/$maxRows_bolsa)-1;

$queryString_bolsa = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_bolsa") == false && 
        stristr($param, "totalRows_bolsa") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_bolsa = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_bolsa = sprintf("&totalRows_bolsa=%d%s", $totalRows_bolsa, $queryString_bolsa);
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
<table align="center" id="tabla">
<tr align="center"><td>
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
	<form action="bolsas_busqueda1.php" method="get" name="consulta">
	<div align="center">
<table id="tabla1">
<tr>
  <td id="titulo2">FILTRO DE PRODUCTO TERMINADO ( BOLSAS ) </td>
  <td id="dato3"><a href="bolsas_busqueda.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="bolsas_add.php" target="_top"><img src="images/mas.gif" alt="ADD BOLSA" border="0" style="cursor:hand;"/></a><a href="bolsas.php" target="_top"><img src="images/b.gif" alt="BOLSAS" border="0" style="cursor:hand;"/></a></td>
</tr>
<tr>
  <td colspan="2" id="fuente2"><select name="codigobolsa" id="codigobolsa">
    <option value="0">CODIGO</option>
    <?php
do {  
?><option value="<?php echo $row_codigo_bolsa['id_bolsa']?>"><?php echo $row_codigo_bolsa['codigo_bolsa']?></option>
    <?php
} while ($row_codigo_bolsa = mysql_fetch_assoc($codigo_bolsa));
  $rows = mysql_num_rows($codigo_bolsa);
  if($rows > 0) {
      mysql_data_seek($codigo_bolsa, 0);
	  $row_codigo_bolsa = mysql_fetch_assoc($codigo_bolsa);
  }
?>
    </select>
  <select name="nombre_bolsa" id="nombre_bolsa">
    <option value="0">BOLSAS</option>
    <?php
do {  
?>
    <option value="<?php echo $row_bolsas['id_bolsa']?>"><?php echo $row_bolsas['nombre_bolsa']?></option>
    <?php
} while ($row_bolsas = mysql_fetch_assoc($bolsas));
  $rows = mysql_num_rows($bolsas);
  if($rows > 0) {
      mysql_data_seek($bolsas, 0);
	  $row_bolsas = mysql_fetch_assoc($bolsas);
  }
?>
    </select>
	<select name="id_ref_bolsa" id="id_ref_bolsa">
	  <option value="0">REF</option>
	  <?php
do {  
?>
	  <option value="<?php echo $row_referencias['id_ref']?>"><?php echo $row_referencias['cod_ref']?></option>
	  <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
    </select>
    <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.codigobolsa.value=='0' && consulta.nombre_bolsa.value=='0' && consulta.id_ref_bolsa.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</div>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td id="dato2"><input name="borrado" type="hidden" id="borrado" value="16" />
      <input name="Input" type="submit" value="X"/></td>
    <td colspan="4" id="dato1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> 
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
      <?php }
  if($id == '') { ?>
      <div id="dato1"> <?php echo "Si elimina una BOLSA, sera definitivamente."; ?> </div>      <?php }
  ?></td>
    </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td id="titulo4">CODIGO</td>
    <td id="titulo4">NOMBRE DE LA BOLSA </td>
    <td id="titulo4">REF</td>
    <td id="titulo4">DESCRIPCION</td>
    </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_bolsa['id_bolsa']; ?>" /></td>
      <td id="dato1"><a href="bolsas_vista.php?id_bolsa=<?php echo $row_bolsa['id_bolsa']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsa['codigo_bolsa']; ?></a></td>
      <td id="dato1"><a href="bolsas_vista.php?id_bolsa=<?php echo $row_bolsa['id_bolsa']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsa['nombre_bolsa']; ?></a></td>
      <td id="dato2"><a href="referencia_vista.php?id_ref=<?php echo $row_bolsa['id_ref_bolsa']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php $ref=$row_bolsa['id_ref_bolsa'];
	  if($ref!=''){
	$sqlref="SELECT * FROM referencia WHERE id_ref = $ref";
	$resultref= mysql_query($sqlref);
	$numref= mysql_num_rows($resultref);
	if($numref >='1') { $cod_ref=mysql_result($resultref,0,'cod_ref'); }
	echo $cod_ref; } ?>
      </a></td>
      <td id="dato1"><?php echo $row_bolsa['descripcion_bolsa']; ?></td>
    </tr>
    <?php } while ($row_bolsa = mysql_fetch_assoc($bolsa)); ?>
</table>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_bolsa > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_bolsa=%d%s", $currentPage, 0, $queryString_bolsa); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_bolsa > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_bolsa=%d%s", $currentPage, max(0, $pageNum_bolsa - 1), $queryString_bolsa); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_bolsa < $totalPages_bolsa) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_bolsa=%d%s", $currentPage, min($totalPages_bolsa, $pageNum_bolsa + 1), $queryString_bolsa); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_bolsa < $totalPages_bolsa) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_bolsa=%d%s", $currentPage, $totalPages_bolsa, $queryString_bolsa); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</form>
	</td>
  </tr>
</table></div>
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

mysql_free_result($codigo_bolsa);

mysql_free_result($bolsas);

mysql_free_result($referencias);

mysql_free_result($bolsa);
?>