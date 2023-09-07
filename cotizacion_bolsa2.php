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
$n_cotiz = $_GET['n_cotiz'];
$id_c = $_GET['id_c'];
$fecha = $_GET['fecha'];
//Filtra todos vacios
if($n_cotiz == '0' && $id_c == '0' && $fecha == '0')
{
$query_cotizacion = "SELECT * FROM cotizacion ORDER BY n_cotiz DESC";
}
//Filtra cotizacion lleno
if($n_cotiz != '0' && $id_c == '0' && $fecha == '0')
{
$query_cotizacion = "SELECT * FROM cotizacion WHERE n_cotiz='$n_cotiz' ORDER BY n_cotiz DESC";
}
//Filtra cliente lleno
if($id_c != '0' && $n_cotiz == '0' && $fecha == '0')
{
$query_cotizacion = "SELECT * FROM cotizacion WHERE id_c_cotiz='$id_c' ORDER BY n_cotiz DESC";
}
//Filtra fecha lleno
if($fecha != '0' && $id_c == '0' && $n_cotiz == '0'  )
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_cotizacion = "SELECT * FROM cotizacion WHERE fecha_cotiz >= '$fecha1' and fecha_cotiz < '$fecha2' ORDER BY n_cotiz DESC";
}
//Filtra fecha y cliente lleno
if($fecha != '0' && $id_c != '0' && $n_cotiz == '0'  )
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_cotizacion = "SELECT * FROM cotizacion WHERE id_c_cotiz='$id_c' and fecha_cotiz >= '$fecha1' and fecha_cotiz < '$fecha2' ORDER BY n_cotiz DESC";
}
//Filtra cotizacion y fecha lleno
if($n_cotiz != '0' && $fecha != '0' && $id_c == '0')
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_cotizacion = "SELECT * FROM cotizacion WHERE n_cotiz='$n_cotiz' and fecha_cotiz >= '$fecha1' and fecha_cotiz < '$fecha2' ORDER BY n_cotiz DESC";
}
//Filtra cotizacion y cliente lleno
if($n_cotiz != '0' && $id_c != '0' && $fecha == '0')
{
$query_cotizacion = "SELECT * FROM cotizacion WHERE n_cotiz='$n_cotiz' and id_c_cotiz='$id_c' ORDER BY n_cotiz DESC";
}
//Filtra Todos llenos
if($n_cotiz != '0' && $id_c != '0' && $fecha != '0')
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$query_cotizacion = "SELECT * FROM cotizacion WHERE n_cotiz='$n_cotiz' and id_c_cotiz='$id_c' and fecha_cotiz >= '$fecha1' and fecha_cotiz < '$fecha2' ORDER BY n_cotiz DESC";
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
$query_numero = "SELECT * FROM cotizacion ORDER BY n_cotiz DESC";
$numero = mysql_query($query_numero, $conexion1) or die(mysql_error());
$row_numero = mysql_fetch_assoc($numero);
$totalRows_numero = mysql_num_rows($numero);

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body>
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
<li><a href="comercial.php">GESTION COMERCIAL</a></li></ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
	<form action="cotizacion_bolsa2.php" method="get" name="consulta" >
	<table id="tabla1">
<tr>
<td width="25%" nowrap="nowrap" id="codigo">CODIGO: R1 - F03</td>
<td width="50%" nowrap="nowrap" id="titulo2">SEGUIMIENTO A COTIZACIONES ( BOLSA )</td>
<td width="25%" nowrap="nowrap" id="codigo">VERSION: 1</td>
</tr>
<tr>
  <td colspan="3" id="dato2"><select name="n_cotiz" id="n_cotiz">
    <option value="0" <?php if (!(strcmp(0, $_GET['n_cotiz']))) {echo "selected=\"selected\"";} ?>>Cotizacion</option>
    <?php
do {  
?><option value="<?php echo $row_numero['n_cotiz']?>"<?php if (!(strcmp($row_numero['n_cotiz'], $_GET['n_cotiz']))) {echo "selected=\"selected\"";} ?>><?php echo $row_numero['n_cotiz']?></option>
    <?php
} while ($row_numero = mysql_fetch_assoc($numero));
  $rows = mysql_num_rows($numero);
  if($rows > 0) {
      mysql_data_seek($numero, 0);
	  $row_numero = mysql_fetch_assoc($numero);
  }
?>
    </select><select name="id_c" id="id_c">
      <option value="0" <?php if (!(strcmp(0, $_GET['id_c']))) {echo "selected=\"selected\"";} ?>>Cliente</option>
      <?php
do {  
?><option value="<?php echo $row_cliente['id_c']?>"<?php if (!(strcmp($row_cliente['id_c'], $_GET['id_c']))) {echo "selected=\"selected\"";} ?>><?php echo $row_cliente['nombre_c']?></option>
      <?php
} while ($row_cliente = mysql_fetch_assoc($cliente));
  $rows = mysql_num_rows($cliente);
  if($rows > 0) {
      mysql_data_seek($cliente, 0);
	  $row_cliente = mysql_fetch_assoc($cliente);
  }
?>
    </select><select name="fecha" id="fecha">
      <option value="0" <?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>Año</option>
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
    </select><input type="submit" name="Submit" value="FILTRO" onclick="if(consulta.n_cotiz.value=='0' && consulta.id_c.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>    
    <a href="cotizacion_bolsa.php"></a></td>
  </tr>
</table>
</form>
<form action="delete_listado.php" method="get" name="seleccion">
<table id="tabla1">
  <tr>
    <td colspan="2" id="dato1"><input name="Input" type="submit" value="Delete"/>
      <input name="borrado" type="hidden" id="borrado" value="2" /></td>
    <td colspan="3" nowrap="nowrap"><?php $id=$_GET['id']; 
  if($id >= '1') { ?>
      <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
      <?php }
  if($id == '0') { ?>
      <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
      <?php }?></td>
    <td id="dato2"><?php if($row_usuario['tipo_usuario'] != '11') { ?><a href="cotizacion_bolsa_add.php"><img src="images/mas.gif" alt="ADD COTIZACION" border="0" style="cursor:hand;"/></a><?php } ?><a href="cotizacion_menu.php"><img src="images/opciones.gif" alt="MENU COTIZACION" border="0" style="cursor:hand;"/></a><a href="cotizacion_bolsa.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="egp_bolsa.php"><img src="images/e.gif" style="cursor:hand;" alt="EGP'S BOLSA" border="0"/></a></td>
  </tr>  
  <tr id="tr2">
    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
    <td nowrap="nowrap" id="titulo4">N°</td>
    <td nowrap="nowrap" id="titulo4">FECHA</td>
    <td nowrap="nowrap" id="titulo4">HORA</td>
    <td nowrap="nowrap" id="titulo4">CLIENTE</td>
    <td nowrap="nowrap" id="titulo4">RESPONSABLE</td>
  </tr>
  <?php do { ?>
 <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><?php if($row_cotizacion['n_cotiz']!='') { ?><input name="cotiz[]" type="checkbox" value="<?php echo $row_cotizacion['n_cotiz']; ?>" /><?php } ?></td>
      <td id="dato3"><a href="cotizacion_bolsa_vista.php?n_cotiz= <?php echo $row_cotizacion['n_cotiz']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['n_cotiz']; ?></a></td>
      <td id="dato2"><a href="cotizacion_bolsa_vista.php?n_cotiz= <?php echo $row_cotizacion['n_cotiz']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['fecha_cotiz']; ?></a></td>
      <td id="dato2"><a href="cotizacion_bolsa_vista.php?n_cotiz= <?php echo $row_cotizacion['n_cotiz']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['hora_cotiz']; ?></a></td>
      <td id="dato1"><a href="perfil_cliente_vista.php?id_c= <?php echo $row_cotizacion['id_c_cotiz']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $id_c=$row_cotizacion['id_c_cotiz'];
	  $sql2="SELECT * FROM cliente WHERE id_c='$id_c'";
	  $result2=mysql_query($sql2);
	  $num2=mysql_num_rows($result2);
	  if ($num2 >= '1')
	  	{
		$nombre_c=mysql_result($result2,0,'nombre_c');
		echo $nombre_c;
		}
		else
		{
		echo "";
		} ?></a></td>
      <td id="dato1"><a href="cotizacion_bolsa_vista.php?n_cotiz= <?php echo $row_cotizacion['n_cotiz']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_cotizacion['responsable_cotiz']; ?></a></td>
    </tr>
    <?php } while ($row_cotizacion = mysql_fetch_assoc($cotizacion)); ?>
</table>
<table id="tabla1">
  <tr>
    <td id="dato2" width="25%"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, 0, $queryString_cotizacion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato2" width="25%"><?php if ($pageNum_cotizacion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, max(0, $pageNum_cotizacion - 1), $queryString_cotizacion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato2" width="25%"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_cotizacion=%d%s", $currentPage, min($totalPages_cotizacion, $pageNum_cotizacion + 1), $queryString_cotizacion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato2" width="25%"><?php if ($pageNum_cotizacion < $totalPages_cotizacion) { // Show if not last page ?>
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

mysql_free_result($cliente);

mysql_free_result($ano);
?>