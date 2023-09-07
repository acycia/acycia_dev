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

$colname_rollo_cola = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_rollo_cola = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_refilado = sprintf("SELECT * FROM TblRefiladoRollo WHERE TblRefiladoRollo.id_op_r=%s ORDER BY rollo_r DESC",$colname_rollo_cola);
$rollo_refilado = mysql_query($query_rollo_refilado, $conexion1) or die(mysql_error());
$row_rollo_refilado = mysql_fetch_assoc($rollo_refilado);
$totalRows_rollo_refilado = mysql_num_rows($rollo_refilado);

  session_start();
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
 </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
	<table id="tabla1">
	  <tr>
	  <td id="subtitulo">LISTADO DE ROLLOS</td>
	  </tr>
	  <tr>
	  <td id="fuente2">&nbsp;</td>
  </tr>
</table>

<table id="tabla1">  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">ROLLO N&deg;</td>
    <td nowrap="nowrap" id="titulo4">O.P.</td>
    <td nowrap="nowrap" id="titulo4">FECHA INICIO</td>
    <td nowrap="nowrap" id="titulo4">FECHA FIN</td>
    <td nowrap="nowrap" id="titulo4">FECHA IMPRESION</td>
    <td nowrap="nowrap" id="titulo4">CODIGO OPERARIO</td>
    <td nowrap="nowrap" id="titulo4">CODIGO AUXILIAR</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><a href="produccion_refilado_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_refilado['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_refilado['rollo_r']; ?></a></td>
      <td id="dato2"><a href="produccion_refilado_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_refilado['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_refilado['id_op_r']; ?></a></td>
      <td id="dato2"><a href="produccion_refilado_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_refilado['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_refilado['fechaI_r']; ?></a></td>
      <td id="dato2"><a href="produccion_refilado_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_refilado['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_refilado['fechaF_r']; ?></a></td>      
      <td id="dato2"><a href="produccion_refilado_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_refilado['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_refilado['fechaV_r']; ?></a></td>      
      <td id="dato1"><a href="produccion_refilado_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_refilado['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_refilado['cod_empleado_r']; ?></a></td>
      <td id="dato1"><a href="produccion_refilado_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_refilado['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_refilado['cod_auxiliar_r']; ?></a></td>
    </tr> 
    <?php } while ($row_rollo_refilado = mysql_fetch_assoc($rollo_refilado)); ?>
</table>
</td>
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

mysql_free_result($rollo_refilado);

?>
