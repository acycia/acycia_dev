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
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

$maxRows_caracteristicas = 50;
$pageNum_caracteristicas = 0;
if (isset($_GET['pageNum_caracteristicas'])) {
  $pageNum_caracteristicas = $_GET['pageNum_caracteristicas'];
}
$startRow_caracteristicas = $pageNum_caracteristicas * $maxRows_caracteristicas;

mysql_select_db($database_conexion1, $conexion1);
//select * FROM `Tbl_caracteristicas_valor` LEFT   join Tbl_caracteristicas  on Tbl_caracteristicas_valor.id_c_cv= Tbl_caracteristicas.id_c ORDER BY Tbl_caracteristicas_valor.fecha_registro_cv DESC
$query_caracteristicas = "SELECT * FROM Tbl_caracteristicas_valor, Tbl_caracteristicas WHERE Tbl_caracteristicas_valor.id_c_cv=Tbl_caracteristicas.id_c 
AND Tbl_caracteristicas_valor.b_borrado_cv='0' AND id_proceso_cv='1' ORDER BY Tbl_caracteristicas_valor.fecha_registro_cv DESC";
$query_limit_caracteristicas = sprintf("%s LIMIT %d, %d", $query_caracteristicas, $startRow_caracteristicas, $maxRows_caracteristicas);
$caracteristicas = mysql_query($query_limit_caracteristicas, $conexion1) or die(mysql_error());
$row_caracteristicas = mysql_fetch_assoc($caracteristicas);

if (isset($_GET['totalRows_caracteristicas'])) {
  $totalRows_caracteristicas = $_GET['totalRows_caracteristicas'];
} else {
  $all_caracteristicas = mysql_query($query_caracteristicas);
  $totalRows_caracteristicas = mysql_num_rows($all_caracteristicas);
}
$totalPages_caracteristicas = ceil($totalRows_caracteristicas/$maxRows_caracteristicas)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_id_cv = "SELECT DISTINCT cod_ref_cv,id_ref_cv,id_proceso_cv FROM Tbl_caracteristicas_valor WHERE b_borrado_cv='0' AND id_proceso_cv='1' ORDER BY cod_ref_cv DESC";
$id_cv = mysql_query($query_id_cv, $conexion1) or die(mysql_error());
$row_id_cv = mysql_fetch_assoc($id_cv);
$totalRows_id_cv = mysql_num_rows($id_cv);

$queryString_caracteristicas = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_caracteristicas") == false && 
        stristr($param, "totalRows_caracteristicas") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_caracteristicas = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_caracteristicas = sprintf("&totalRows_caracteristicas=%d%s", $totalRows_caracteristicas, $queryString_caracteristicas);

session_start();
 ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
  <li><a href="menu.php">DISENOYDESARROLLO</a></li>
 </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
	<form action="produccion_referencias2.php" method="get" name="consulta">
	<table id="tabla1">
      <tr id="tr1">
	  <td nowrap="nowrap" id="codigo">CODIGO: R2-F01</td>
	  <td nowrap="nowrap" id="titulo2">PRODUCCION</td>
	  <td nowrap="nowrap" id="codigo">VERSION: 0</td>
	  </tr>
	  <tr>
	  <td colspan="3" id="subtitulo">LISTADO DE REFERENCIAS ACTIVAS POR PROCESO</td>
	  </tr>
	  <tr>
	  <td colspan="3" id="fuente2"><select name="id_cv" id="id_cv">
	    <option value="0">REFERENCIA N°</option>
        <?php
do {  
?>
        <option value="<?php echo $row_id_cv['id_ref_cv']?>"><?php echo $row_id_cv['cod_ref_cv']?></option>
        <?php
} while ($row_id_cv = mysql_fetch_assoc($id_cv));
  $rows = mysql_num_rows($id_cv);
  if($rows > 0) {
      mysql_data_seek($id_cv, 0);
	  $row_id_cv = mysql_fetch_assoc($id_cv);
  }
?>
    </select>
      <select name="fecha" id="fecha">
        <option value="0">ANUAL</option>
        <?php
do {  
?>
        <option value="<?php echo $row_ano['anual']?>"><?php echo $row_ano['anual']?></option>
        <?php
} while ($row_ano = mysql_fetch_assoc($ano));
  $rows = mysql_num_rows($ano);
  if($rows > 0) {
      mysql_data_seek($ano, 0);
	  $row_ano = mysql_fetch_assoc($ano);
  }
?>
      </select>
      <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_cv.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
      </td>
  </tr>
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td colspan="9" id="dato3"><a href="referencias.php"><img src="images/mas.gif" alt="ADD MEZCLA Y CARACTERISTICAS" title="ADD MEZCLA Y CARACTERISTICAS" border="0" style="cursor:hand;"/></a><a href="produccion_mezclas.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="produccion_referencias.php"><img src="images/rp.gif" style="cursor:hand;" alt="LISTADO REF. POR PROCESO" title="LISTADO REF. POR PROCESO" border="0" /></a><a href="produccion_referencias.php"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    </tr>
  <tr>
    <td colspan="2" id="dato1"><input name="Input" type="submit" value="Inactivar o Activar"/></td>
    <td colspan="7"><?php $id=$_GET['id'];
/*	if($id >= '3') { ?> <div id="acceso1"> <?php echo "No se ha seleccionado"; ?> </div> <?php } 
if($id >= '2') { ?> <div id="acceso1"> <?php echo "ACTIVACION CORRECTA"; ?> </div> <?php }*/	
  if($id >= '1') { ?>
      <div id="acceso1"> <?php echo "CAMBIADA A INACTIVA"; ?></div>
      <?php }/*
  if($id == '0') { ?><div id="numero1"> <?php echo "No se ha seleccionado"; ?> </div> <?php }*/?></td>
    </tr>  
  <tr id="tr1">
    <td id="titulo4"><!--<input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/>-->&nbsp;</td>
    <td nowrap="nowrap" id="titulo4">REF</td>
    <td nowrap="nowrap" id="titulo4">VERS. REF</td>
    <td nowrap="nowrap" id="titulo4">N° CARACT</td>
    <td nowrap="nowrap" id="titulo4">CARACTERITICA</td>
    <td nowrap="nowrap" id="titulo4">VALOR</td>
    <td nowrap="nowrap" id="titulo4">FECHA</td>
    <td nowrap="nowrap" id="titulo4">RESPONSABLE</td>
    <td nowrap="nowrap" id="titulo4">ESTADO</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato2"><?php if($row_caracteristicas['b_borrado_cv']=='0'){?><input name="borrado" type="hidden" id="borrado" value="33" /><input name="borrar[]" type="checkbox" value="<?php echo $row_caracteristicas['id_ref_cv']; ?>" /><?php }?></td>
      <td id="dato2"><a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_caracteristicas['id_ref_cv']; ?>&amp;id_pm=<?php echo $row_caracteristicas['id_pm_cv']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_caracteristicas['cod_ref_cv']; ?></a></td>
      <td id="dato2"><a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_caracteristicas['id_ref_cv']; ?>&amp;id_pm=<?php echo $row_caracteristicas['id_pm_cv']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_caracteristicas['version_ref_cv']; ?></a></td>
      <td id="dato2"><a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_caracteristicas['id_ref_cv']; ?>&amp;id_pm=<?php echo $row_caracteristicas['id_pm_cv']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_caracteristicas['id_c_cv']; ?></a></td>
      <td id="dato1"><a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_caracteristicas['id_ref_cv']; ?>&amp;id_pm=<?php echo $row_caracteristicas['id_pm_cv']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad = htmlentities ($row_caracteristicas['str_nombre_caract_c']);echo $cad; ?></a></td>
      <td id="dato2"><a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_caracteristicas['id_ref_cv']; ?>&amp;id_pm=<?php echo $row_caracteristicas['id_pm_cv']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_caracteristicas['str_valor_cv']; ?></a></td>
      <td nowrap="nowrap"id="dato2"><a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_caracteristicas['id_ref_cv']; ?>&amp;id_pm=<?php echo $row_caracteristicas['id_pm_cv']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_caracteristicas['fecha_registro_cv']; ?></a></td>
      <td id="dato2"><a href="produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $row_caracteristicas['id_ref_cv']; ?>&amp;id_pm=<?php echo $row_caracteristicas['id_pm_cv']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $cad = htmlentities($row_caracteristicas['str_registro_cv']); echo $cad; ?></a></td>
    <td id="dato2"><?php if($row_caracteristicas['b_borrado_cv']=='0'){echo "ACTIVA";}else?><?php if($row_caracteristicas['b_borrado_cv']=='1'){?><input name="activar3" type="hidden" id="activar3" value="activar3" /><input name="activa[]" type="checkbox" value="<?php echo $row_caracteristicas['id_ref_cv']; ?>" /> ACTIVAR?<?php }?></td>
    </tr>
    <?php } while ($row_caracteristicas = mysql_fetch_assoc($caracteristicas)); ?>
</table>
<table id="tabla1">
  <tr>
    <td id="dato2"><?php if ($pageNum_caracteristicas > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_caracteristicas=%d%s", $currentPage, 0, $queryString_caracteristicas); ?>">Primero</a>
        <?php } // Show if not first page ?>
    </td>
    <td id="dato2"><?php if ($pageNum_caracteristicas > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_caracteristicas=%d%s", $currentPage, max(0, $pageNum_caracteristicas - 1), $queryString_caracteristicas); ?>">Anterior</a>
        <?php } // Show if not first page ?>
    </td>
    <td id="dato2"><?php if ($pageNum_caracteristicas < $totalPages_caracteristicas) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_caracteristicas=%d%s", $currentPage, min($totalPages_caracteristicas, $pageNum_caracteristicas + 1), $queryString_caracteristicas); ?>">Siguiente</a>
        <?php } // Show if not last page ?>
    </td>
    <td id="dato2"><?php if ($pageNum_caracteristicas < $totalPages_caracteristicas) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_caracteristicas=%d%s", $currentPage, $totalPages_caracteristicas, $queryString_caracteristicas); ?>">&Uacute;ltimo</a>
        <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</form></td></tr></table>
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

mysql_free_result($ano);

mysql_free_result($caracteristicas);

mysql_free_result($id_cv);
?>