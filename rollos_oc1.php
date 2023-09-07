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

$maxRows_rollos_oc = 20;
$pageNum_rollos_oc = 0;
if (isset($_GET['pageNum_rollos_oc'])) {
  $pageNum_rollos_oc = $_GET['pageNum_rollos_oc'];
}
$startRow_rollos_oc = $pageNum_rollos_oc * $maxRows_rollos_oc;

mysql_select_db($database_conexion1, $conexion1);
$n_ocr = $_GET['n_ocr'];
$id_p = $_GET['id_p'];
$id_rollo = $_GET['id_rollo'];
$id_ref = $_GET['id_ref'];
$fecha = $_GET['fecha'];
//Filtra todos vacios
if($n_ocr == '0' && $id_p == '0' && $id_rollo == '0' && $id_ref == '0' && $fecha == '0')
{
$query_rollos_oc = "SELECT * FROM orden_compra_rollos ORDER BY n_ocr DESC";
}
//Filtra OC lleno
if($n_ocr != '0' && $id_p == '0' && $id_rollo == '0' && $id_ref == '0' && $fecha == '0')
{
$query_rollos_oc = "SELECT * FROM orden_compra_rollos WHERE n_ocr='$n_ocr' ORDER BY n_ocr DESC";
}
//Filtra proveedor lleno
if($n_ocr == '0' && $id_p != '0' && $id_rollo == '0' && $id_ref == '0' && $fecha == '0')
{
$query_rollos_oc = "SELECT * FROM orden_compra_rollos WHERE id_p_ocr='$id_p' ORDER BY n_ocr DESC";
}
//Filtra rollo lleno
if($n_ocr == '0' && $id_p == '0' && $id_rollo != '0' && $id_ref == '0' && $fecha == '0')
{
$query_rollos_oc = "SELECT * FROM orden_compra_rollos WHERE id_rollo_ocr='$id_rollo' ORDER BY n_ocr DESC";
}
//Filtra referencia lleno
if($n_ocr == '0' && $id_p == '0' && $id_rollo == '0' && $id_ref != '0' && $fecha == '0')
{
$query_rollos_oc = "SELECT * FROM orden_compra_rollos WHERE id_ref_ocr='$id_ref' ORDER BY n_ocr DESC";
}
//Filtra fecha lleno
if($n_ocr == '0' && $id_p == '0' && $id_rollo == '0' && $id_ref == '0' && $fecha != '0')
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_rollos_oc = "SELECT * FROM orden_compra_rollos WHERE fecha_pedido_ocr >= '$fecha1' AND fecha_pedido_ocr < '$fecha2' ORDER BY n_ocr DESC";
}
$query_limit_rollos_oc = sprintf("%s LIMIT %d, %d", $query_rollos_oc, $startRow_rollos_oc, $maxRows_rollos_oc);
$rollos_oc = mysql_query($query_limit_rollos_oc, $conexion1) or die(mysql_error());
$row_rollos_oc = mysql_fetch_assoc($rollos_oc);

if (isset($_GET['totalRows_rollos_oc'])) {
  $totalRows_rollos_oc = $_GET['totalRows_rollos_oc'];
} else {
  $all_rollos_oc = mysql_query($query_rollos_oc);
  $totalRows_rollos_oc = mysql_num_rows($all_rollos_oc);
}
$totalPages_rollos_oc = ceil($totalRows_rollos_oc/$maxRows_rollos_oc)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_ordenes_compra = "SELECT * FROM orden_compra_rollos ORDER BY n_ocr DESC";
$ordenes_compra = mysql_query($query_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);
$totalRows_ordenes_compra = mysql_num_rows($ordenes_compra);

mysql_select_db($database_conexion1, $conexion1);
$query_rollos = "SELECT * FROM materia_prima_rollos ORDER BY nombre_rollo ASC";
$rollos = mysql_query($query_rollos, $conexion1) or die(mysql_error());
$row_rollos = mysql_fetch_assoc($rollos);
$totalRows_rollos = mysql_num_rows($rollos);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM Tbl_referencia order by id_ref desc";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

$queryString_rollos_oc = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rollos_oc") == false && 
        stristr($param, "totalRows_rollos_oc") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rollos_oc = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rollos_oc = sprintf("&totalRows_rollos_oc=%d%s", $totalRows_rollos_oc, $queryString_rollos_oc);
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
<form action="rollos_oc1.php" method="get" name="consulta">
<div align="center">
<table id="tabla1">
<tr>
  <td id="titulo2">ORDENES DE COMPRA  ( ROLLOS ) </td>
  </tr>
<tr>
  <td id="fuente2"><select name="n_ocr" id="n_ocr">
    <option value="0" <?php if (!(strcmp(0, $_GET['n_ocr']))) {echo "selected=\"selected\"";} ?>>O.C.</option>
    <?php
do {  
?><option value="<?php echo $row_ordenes_compra['n_ocr']?>"<?php if (!(strcmp($row_ordenes_compra['n_ocr'], $_GET['n_ocr']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ordenes_compra['n_ocr']?></option>
    <?php
} while ($row_ordenes_compra = mysql_fetch_assoc($ordenes_compra));
  $rows = mysql_num_rows($ordenes_compra);
  if($rows > 0) {
      mysql_data_seek($ordenes_compra, 0);
	  $row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);
  }
?>
    </select><select name="id_p" id="id_p">
      <option value="0" <?php if (!(strcmp(0, $_GET['id_p']))) {echo "selected=\"selected\"";} ?>>PROVEEDOR</option>
      <?php
do {  
?><option value="<?php echo $row_proveedores['id_p']?>"<?php if (!(strcmp($row_proveedores['id_p'], $_GET['id_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_proveedores['proveedor_p']?></option>
      <?php
} while ($row_proveedores = mysql_fetch_assoc($proveedores));
  $rows = mysql_num_rows($proveedores);
  if($rows > 0) {
      mysql_data_seek($proveedores, 0);
	  $row_proveedores = mysql_fetch_assoc($proveedores);
  }
?>
    </select><br>
  <select name="id_rollo" id="id_rollo">
    <option value="0" <?php if (!(strcmp(0, $_GET['id_rollo']))) {echo "selected=\"selected\"";} ?>>ROLLO</option>
    <?php
do {  
?><option value="<?php echo $row_rollos['id_rollo']?>"<?php if (!(strcmp($row_rollos['id_rollo'], $_GET['id_rollo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rollos['nombre_rollo']?></option>
    <?php
} while ($row_rollos = mysql_fetch_assoc($rollos));
  $rows = mysql_num_rows($rollos);
  if($rows > 0) {
      mysql_data_seek($rollos, 0);
	  $row_rollos = mysql_fetch_assoc($rollos);
  }
?>
    </select>
	<select name="id_ref" id="id_ref">
	  <option value="0" <?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>REF</option>
	  <?php
do {  
?><option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
	  <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
    </select>
    <select name="fecha" id="fecha">
      <option value="0" <?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>A&Ntilde;O</option>
      <?php
do {  
?><option value="<?php echo $row_ano['anual']?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ano['anual']?></option>
      <?php
} while ($row_ano = mysql_fetch_assoc($ano));
  $rows = mysql_num_rows($ano);
  if($rows > 0) {
      mysql_data_seek($ano, 0);
	  $row_ano = mysql_fetch_assoc($ano);
  }
?>
    </select>
    <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.n_ocr.value=='0' && consulta.id_p.value=='0' && consulta.id_rollo.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</div>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td id="dato2"><input name="borrado" type="hidden" id="borrado" value="14" />
      <input name="Input" type="submit" value="X"/></td>
    <td colspan="6" id="dato1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> 
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
      <?php }
  if($id == '') { ?>
      <div id="dato1"> <?php echo "Si elimina una O.C., sera definitivamente."; ?> </div>      <?php }
  ?></td>
    <td colspan="2" id="dato2"><a href="rollos_oc.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="rollos_oc_add.php" target="_top"><img src="images/mas.gif" alt="ADD O.C. ROLLO" border="0" style="cursor:hand;"/></a><a href="rollos.php" target="_top"><img src="images/r.gif" alt="ROLLOS" border="0" style="cursor:hand;"/></a></td>
    </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td id="titulo4">N&deg;O.C.</td>
    <td id="titulo4">PROVEEDOR</td>
    <td id="titulo4">NOMBRE DEL ROLLO</td>
    <td id="titulo4">REF. </td>
    <td id="titulo4">FECHA. PED.</td>
    <td id="titulo4">FECHA ENT. </td>
    <td id="titulo4">PEDIDO</td>
    <td id="titulo4"><a href="rollos_verificacion.php"><img src="images/v.gif" border="0"></a></td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_rollos_oc['n_ocr']; ?>" /></td>
      <td id="dato2"><strong><a href="rollos_oc_vista.php?n_ocr=<?php echo $row_rollos_oc['n_ocr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos_oc['n_ocr']; ?></a></strong></td>
      <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_rollos_oc['id_p_ocr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $id_p=$row_rollos_oc['id_p_ocr'];
	  if($id_p!=''){
	$sqlp="SELECT * FROM proveedor WHERE id_p = $id_p";
	$resultp= mysql_query($sqlp);
	$nump= mysql_num_rows($resultp);
	if($nump >='1') { $proveedor_p=mysql_result($resultp,0,'proveedor_p'); }
	echo $proveedor_p; } ?>
      </a></td>
      <td id="dato1"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos_oc['id_rollo_ocr']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php $id_rollo=$row_rollos_oc['id_rollo_ocr']; if($id_rollo!='') {
	$sqlrollo="SELECT * FROM materia_prima_rollos WHERE id_rollo = $id_rollo";
	$resultrollo= mysql_query($sqlrollo);
	$numrollo= mysql_num_rows($resultrollo);
	if($numrollo >='1') { 
	$nombre_rollo=mysql_result($resultrollo,0,'nombre_rollo');
	$ref_prod_rollo=mysql_result($resultrollo,0,'ref_prod_rollo');  
	} } echo $nombre_rollo; ?>
      </a></td>
      <td id="dato2"><a href="referencia_vista.php?id_ref=<?php echo $row_rollos_oc['id_ref_ocr']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php $ref=$row_rollos_oc['id_ref_ocr'];
	  if($ref!=''){	$sqlref="SELECT * FROM Tbl_referencia WHERE id_ref = $ref";
	$resultref= mysql_query($sqlref);
	$numref= mysql_num_rows($resultref);
	if($numref >='1') { $cod_ref=mysql_result($resultref,0,'cod_ref'); } } echo $cod_ref; ?>
      </a></td>
      <td id="dato2"><a href="rollos_oc_vista.php?n_ocr=<?php echo $row_rollos_oc['n_ocr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos_oc['fecha_pedido_ocr']; ?></a></td>
      <td id="dato2"><a href="rollos_oc_vista.php?n_ocr=<?php echo $row_rollos_oc['n_ocr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos_oc['fecha_entrega_ocr']; ?></a></td>
      <td id="dato2"><a href="rollos_oc_vista.php?n_ocr=<?php echo $row_rollos_oc['n_ocr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos_oc['pedido_ocr']; ?></a></td>
      <td id="dato2"><a href="rollos_oc_verificacion.php?n_ocr=<?php echo $row_rollos_oc['n_ocr']; ?>"><?php $n_ocr=$row_rollos_oc['n_ocr']; if($n_ocr != '') { 
	  $sqlv="SELECT * FROM verificacion_rollos WHERE n_ocr_vr='$n_ocr' AND entrega_vr='1' ORDER BY n_vr DESC";
	  $resultv= mysql_query($sqlv);
	  $numv= mysql_num_rows($resultv);
	  if($numv >='1') { ?><img src="images/v.gif" border="0"><?php } else { ?><img src="images/falta.gif" border="0"><?php } } ?></a></td>
    </tr>
    <?php } while ($row_rollos_oc = mysql_fetch_assoc($rollos_oc)); ?>
</table>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="fuente2"><?php if ($pageNum_rollos_oc > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rollos_oc=%d%s", $currentPage, 0, $queryString_rollos_oc); ?>">Primero</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="fuente2"><?php if ($pageNum_rollos_oc > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rollos_oc=%d%s", $currentPage, max(0, $pageNum_rollos_oc - 1), $queryString_rollos_oc); ?>">Anterior</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="fuente2"><?php if ($pageNum_rollos_oc < $totalPages_rollos_oc) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rollos_oc=%d%s", $currentPage, min($totalPages_rollos_oc, $pageNum_rollos_oc + 1), $queryString_rollos_oc); ?>">Siguiente</a>
        <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="fuente2"><?php if ($pageNum_rollos_oc < $totalPages_rollos_oc) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rollos_oc=%d%s", $currentPage, $totalPages_rollos_oc, $queryString_rollos_oc); ?>">&Uacute;ltimo</a>
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

mysql_free_result($rollos_oc);

mysql_free_result($ordenes_compra);

mysql_free_result($rollos);

mysql_free_result($referencias);

mysql_free_result($proveedores);

mysql_free_result($ano);
?>