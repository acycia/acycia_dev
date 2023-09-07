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

$maxRows_egp = 30;
$pageNum_egp = 0;
if (isset($_GET['pageNum_egp'])) {
  $pageNum_egp = $_GET['pageNum_egp'];
}
$startRow_egp = $pageNum_egp * $maxRows_egp;

mysql_select_db($database_conexion1, $conexion1);
$query_egp = "SELECT * FROM Tbl_egp WHERE estado_egp <> '2' ORDER BY n_egp DESC";
$query_limit_egp = sprintf("%s LIMIT %d, %d", $query_egp, $startRow_egp, $maxRows_egp);
$egp = mysql_query($query_limit_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);

if (isset($_GET['totalRows_egp'])) {
  $totalRows_egp = $_GET['totalRows_egp'];
} else {
  $all_egp = mysql_query($query_egp);
  $totalRows_egp = mysql_num_rows($all_egp);
}
$totalPages_egp = ceil($totalRows_egp/$maxRows_egp)-1;

$queryString_egp = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_egp") == false && 
        stristr($param, "totalRows_egp") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_egp = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_egp = sprintf("&totalRows_egp=%d%s", $totalRows_egp, $queryString_egp);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
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
  <li><a href="comercial.php">GESTION COMERCIAL</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
	<form action="delete_listado.php" method="get" name="seleccion">
	<table id="tabla1">
	<tr>
	  <td colspan="7" id="titulo2">ESPECIFICACION GENERAL DEL PRODUCTO - BOLSA PLASTICA </td>
  </tr>
<tr>
  <td colspan="2" id="fuente1"><input name="" type="submit" value="Delete"/>
    <input name="borrado" type="hidden" id="borrado" value="1" /></td>
  <td colspan="2"><?php $id=$_GET['id']; if($id >= '1') { ?> <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php } 
  if($id == '0') { ?> <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div><?php } ?></td>
  <td colspan="3" id="fuente3"><a href="egp_bolsa_obsoletos.php"><img src="images/i.gif" alt="EGP'S INACTIVAS" border="0" style="cursor:hand;"></a><a href="egp_bolsa_add.php"><!--<img src="images/mas.gif" alt="ADD EGP-BOLSA" border="0" style="cursor:hand;">--></a><a href="egp_menu.php"><img src="images/opciones.gif" alt="MENU EGP'S" border="0" style="cursor:hand;"></a><a href="egp_bolsa.php"><img src="images/ciclo1.gif" alt="CARGAR LISTADO" border="0" style="cursor:hand;"/></a><a href="cotizacion_bolsa.php"><img src="images/c.gif" style="cursor:hand;" alt="COTIZACIONES" border="0"/></a></td>
  </tr>

<tr id="tr1">
  <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
  <td id="titulo4">EGP N&deg;</td>
  <td id="titulo4">FECHA</td>
  <td id="titulo4">RESPONSABLE</td>
  <td id="titulo4">EXTRUSION</td>
  <td id="titulo4">ESTADO</td>
  <td id="titulo4">REFERENCIA</td>
</tr><?php do { ?><tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_egp['n_egp']; ?>" /></td>
    <td id="dato3"><a href="egp_bolsa_vista.php?n_egp=<?php echo $row_egp['n_egp']; ?>&pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&totalRows_egp=<?php echo $_GET['totalRows_egp'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_egp['n_egp']; ?></a></td>
    <td id="dato2"><a href="egp_bolsa_vista.php?n_egp=<?php echo $row_egp['n_egp']; ?>&amp;pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_egp['fecha_egp']; ?></a></td>
    <td id="dato1"><a href="egp_bolsa_vista.php?n_egp=<?php echo $row_egp['n_egp']; ?>&amp;pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_egp['responsable_egp']; ?></a></td>
    <td id="dato1"><a href="egp_bolsa_vista.php?n_egp=<?php echo $row_egp['n_egp']; ?>&amp;pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_egp['tipo_ext_egp']; ?></a></td>
    <td id="dato1"><a href="egp_bolsa_vista.php?n_egp=<?php echo $row_egp['n_egp']; ?>&amp;pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp'];?>" target="_top" style="text-decoration:none; color:#000000">
      <?php $estado=$row_egp['estado_egp']; if($estado == '0') { echo "Pendiente"; } if($estado == '1') { echo "Aceptada"; } ?>
    </a></td>
    <td id="dato1">
	<?php
	$n_egp=$row_egp['n_egp'];
	$estado=$row_egp['estado_egp'];
	if($estado=='0' || $estado=='') 
	{ 
	echo '- -'; 
	}
	else
	{
	$sql2="SELECT * FROM Tbl_referencia WHERE n_egp_ref='$n_egp'";
	$result2=mysql_query($sql2);
	$num2=mysql_num_rows($result2);
	if ($num2 >= '1')
	{
	$referencia=mysql_result($result2,0,'cod_ref');
	echo $referencia;
	}
	} ?></td>
</tr><?php } while ($row_egp = mysql_fetch_assoc($egp)); ?>
</table>
</form>
</td>
  </tr>
</table>
  <table id="tabla1">
    <tr>
      <td id="dato1" width="25%"><?php if ($pageNum_egp > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_egp=%d%s", $currentPage, 0, $queryString_egp); ?>">Primero</a>
            <?php } // Show if not first page ?>
      </td>
      <td id="dato1" width="25%"><?php if ($pageNum_egp > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_egp=%d%s", $currentPage, max(0, $pageNum_egp - 1), $queryString_egp); ?>">Anterior</a>
            <?php } // Show if not first page ?>
      </td>
      <td id="dato1" width="25%"><?php if ($pageNum_egp < $totalPages_egp) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_egp=%d%s", $currentPage, min($totalPages_egp, $pageNum_egp + 1), $queryString_egp); ?>">Siguiente</a>
            <?php } // Show if not last page ?>
      </td>
      <td id="dato1" width="25%"><?php if ($pageNum_egp < $totalPages_egp) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_egp=%d%s", $currentPage, $totalPages_egp, $queryString_egp); ?>">&Uacute;ltimo</a>
            <?php } // Show if not last page ?>
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

mysql_free_result($egp);
?>
