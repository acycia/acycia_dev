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
$query_verificaciones = "SELECT * FROM verificacion_rollos ORDER BY n_vr DESC";
$verificaciones = mysql_query($query_verificaciones, $conexion1) or die(mysql_error());
$row_verificaciones = mysql_fetch_assoc($verificaciones);
$totalRows_verificaciones = mysql_num_rows($verificaciones);

mysql_select_db($database_conexion1, $conexion1);
$query_ordenes_compra = "SELECT * FROM orden_compra_rollos ORDER BY n_ocr DESC";
$ordenes_compra = mysql_query($query_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);
$totalRows_ordenes_compra = mysql_num_rows($ordenes_compra);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

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
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

$maxRows_rollos_verificaciones = 20;
$pageNum_rollos_verificaciones = 0;
if (isset($_GET['pageNum_rollos_verificaciones'])) {
  $pageNum_rollos_verificaciones = $_GET['pageNum_rollos_verificaciones'];
}
$startRow_rollos_verificaciones = $pageNum_rollos_verificaciones * $maxRows_rollos_verificaciones;

mysql_select_db($database_conexion1, $conexion1);
$query_rollos_verificaciones = "SELECT * FROM verificacion_rollos ORDER BY n_vr DESC";
$query_limit_rollos_verificaciones = sprintf("%s LIMIT %d, %d", $query_rollos_verificaciones, $startRow_rollos_verificaciones, $maxRows_rollos_verificaciones);
$rollos_verificaciones = mysql_query($query_limit_rollos_verificaciones, $conexion1) or die(mysql_error());
$row_rollos_verificaciones = mysql_fetch_assoc($rollos_verificaciones);

if (isset($_GET['totalRows_rollos_verificaciones'])) {
  $totalRows_rollos_verificaciones = $_GET['totalRows_rollos_verificaciones'];
} else {
  $all_rollos_verificaciones = mysql_query($query_rollos_verificaciones);
  $totalRows_rollos_verificaciones = mysql_num_rows($all_rollos_verificaciones);
}
$totalPages_rollos_verificaciones = ceil($totalRows_rollos_verificaciones/$maxRows_rollos_verificaciones)-1;

$queryString_rollos_verificaciones = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rollos_verificaciones") == false && 
        stristr($param, "totalRows_rollos_verificaciones") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rollos_verificaciones = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rollos_verificaciones = sprintf("&totalRows_rollos_verificaciones=%d%s", $totalRows_rollos_verificaciones, $queryString_rollos_verificaciones);
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
<form action="rollos_verificacion1.php" method="get" name="consulta">
<div align="center">
<table id="tabla1">
<tr>
  <td id="titulo2">VERIFICACIONES ( ROLLOS )</td>
  </tr>
<tr>
  <td id="fuente2"><select name="n_vr" id="n_vr">
    <option value="0">VERIF.</option>
    <?php
do {  
?>
    <option value="<?php echo $row_verificaciones['n_vr']?>"><?php echo $row_verificaciones['n_vr']?></option>
    <?php
} while ($row_verificaciones = mysql_fetch_assoc($verificaciones));
  $rows = mysql_num_rows($verificaciones);
  if($rows > 0) {
      mysql_data_seek($verificaciones, 0);
	  $row_verificaciones = mysql_fetch_assoc($verificaciones);
  }
?>
    </select>
  <select name="n_ocr" id="n_ocr">
    <option value="0">O.C.</option>
    <?php
do {  
?>
    <option value="<?php echo $row_ordenes_compra['n_ocr']?>"><?php echo $row_ordenes_compra['n_ocr']?></option>
    <?php
} while ($row_ordenes_compra = mysql_fetch_assoc($ordenes_compra));
  $rows = mysql_num_rows($ordenes_compra);
  if($rows > 0) {
      mysql_data_seek($ordenes_compra, 0);
	  $row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);
  }
?>
  </select>
  <select name="id_p" id="id_p">
    <option value="0">PROVEEDOR</option>
    <?php
do {  
?>
    <option value="<?php echo $row_proveedores['id_p']?>"><?php echo $row_proveedores['proveedor_p']?></option>
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
    <option value="0">ROLLO</option>
    <?php
do {  
?>
    <option value="<?php echo $row_rollos['id_rollo']?>"><?php echo $row_rollos['nombre_rollo']?></option>
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
    <select name="fecha" id="fecha">
      <option value="0">A&Ntilde;O</option>
      <?php
do {  
?><option value="<?php echo $row_ano['anual']?>"><?php echo $row_ano['anual']?></option>
      <?php
} while ($row_ano = mysql_fetch_assoc($ano));
  $rows = mysql_num_rows($ano);
  if($rows > 0) {
      mysql_data_seek($ano, 0);
	  $row_ano = mysql_fetch_assoc($ano);
  }
?>
    </select>
    <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.n_vr.value=='0' && consulta.n_ocr.value=='0' && consulta.id_p.value=='0' && consulta.id_rollo.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
  </tr>
</table>
</div>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td id="dato2"><input name="borrado" type="hidden" id="borrado" value="15" />
      <input name="Input" type="submit" value="X"/></td>
    <td colspan="7" id="dato1"><?php $id=$_GET['id']; 
  if($id >= '1') { ?> 
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
      <?php }
  if($id == '') { ?>
      <div id="dato1"> <?php echo "Si elimina una verificacion, sera definitivamente."; ?> </div>      <?php }
  ?></td>
    <td id="dato2"><a href="rollos_verificacion.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="rollos_oc.php" target="_top"><img src="images/o.gif" alt="O.C. ROLLO" border="0" style="cursor:hand;"/></a><a href="rollos.php" target="_top"><img src="images/r.gif" alt="ROLLOS" border="0" style="cursor:hand;"/></a></td>
    </tr>  
  <tr id="tr1">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td id="titulo4">VERIF.</td>
    <td id="titulo4">O.C.</td>
    <td id="titulo4">PROVEEDOR</td>
    <td id="titulo4">NOMBRE DEL ROLLO</td>
    <td id="titulo4">REF. </td>
    <td id="titulo4">FECHA RECIBO </td>
    <td id="titulo4">FACTURA</td>
    <td id="titulo4">ENTREGA</td>
    </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_rollos_verificaciones['n_vr']; ?>" /></td>
      <td id="dato3"><strong><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_rollos_verificaciones['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos_verificaciones['n_vr']; ?></a></strong></td>
      <td id="dato3"><a href="rollos_oc_vista.php?n_ocr=<?php echo $row_rollos_verificaciones['n_ocr_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos_verificaciones['n_ocr_vr']; ?></a></td>
      <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_rollos_verificaciones['id_p_vr']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php $id_p=$row_rollos_verificaciones['id_p_vr'];
	  if($id_p!=''){
	$sqlp="SELECT * FROM proveedor WHERE id_p = $id_p";
	$resultp= mysql_query($sqlp);
	$nump= mysql_num_rows($resultp);
	if($nump >='1') { $proveedor_p=mysql_result($resultp,0,'proveedor_p'); }
	echo $proveedor_p; } ?>
      </a></td>
      <td id="dato1"><a href="rollos_vista.php?id_rollo=<?php echo $row_rollos_verificaciones['id_rollo_vr']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php $id_rollo=$row_rollos_verificaciones['id_rollo_vr'];
	  if($id_rollo!=''){
	$sqlrollo="SELECT * FROM materia_prima_rollos WHERE id_rollo = $id_rollo";
	$resultrollo= mysql_query($sqlrollo);
	$numrollo= mysql_num_rows($resultrollo);
	if($numrollo >='1') { $nombre_rollo=mysql_result($resultrollo,0,'nombre_rollo'); }
	echo $nombre_rollo; } ?>
      </a></td>
      <td id="dato2"><a href="referencia_vista.php?id_ref=<?php echo $row_rollos_verificaciones['id_ref_vr']; ?>" target="_top" style="text-decoration:none; color:#000000">
        <?php $ref=$row_rollos_verificaciones['id_ref_vr'];
	  if($ref!=''){
	$sqlref="SELECT * FROM Tbl_referencia WHERE id_ref = $ref";
	$resultref= mysql_query($sqlref);
	$numref= mysql_num_rows($resultref);
	if($numref >='1') { $cod_ref=mysql_result($resultref,0,'cod_ref'); }
	echo $cod_ref; } ?>
      </a></td>
      <td id="dato2"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_rollos_verificaciones['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos_verificaciones['fecha_recibo_vr']; ?></a></td>
      <td id="dato2"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_rollos_verificaciones['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollos_verificaciones['factura_vr']; ?></a></td>
      <td id="dato2"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_rollos_verificaciones['n_vr']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $entrega=$row_rollos_verificaciones['entrega_vr']; if($entrega == '0') { echo "PARCIAL"; } if($entrega == '1') { echo "TOTAL"; } ?></a></td>
    </tr>
    <?php } while ($row_rollos_verificaciones = mysql_fetch_assoc($rollos_verificaciones)); ?>
</table>
</form>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_rollos_verificaciones > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rollos_verificaciones=%d%s", $currentPage, 0, $queryString_rollos_verificaciones); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_rollos_verificaciones > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rollos_verificaciones=%d%s", $currentPage, max(0, $pageNum_rollos_verificaciones - 1), $queryString_rollos_verificaciones); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_rollos_verificaciones < $totalPages_rollos_verificaciones) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rollos_verificaciones=%d%s", $currentPage, min($totalPages_rollos_verificaciones, $pageNum_rollos_verificaciones + 1), $queryString_rollos_verificaciones); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_rollos_verificaciones < $totalPages_rollos_verificaciones) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rollos_verificaciones=%d%s", $currentPage, $totalPages_rollos_verificaciones, $queryString_rollos_verificaciones); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table></td></tr></table>
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

mysql_free_result($verificaciones);

mysql_free_result($ordenes_compra);

mysql_free_result($proveedores);

mysql_free_result($rollos);

mysql_free_result($referencias);

mysql_free_result($ano);

mysql_free_result($rollos_verificaciones);
?>