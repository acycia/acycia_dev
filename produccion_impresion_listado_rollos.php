<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
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

$conexion = new ApptivaDB();

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
$query_rollo_estrusion = sprintf("SELECT * FROM TblImpresionRollo WHERE TblImpresionRollo.id_op_r=%s ORDER BY rollo_r DESC",$colname_rollo_cola);
$rollo_estrusion = mysql_query($query_rollo_estrusion, $conexion1) or die(mysql_error());
$row_rollo_estrusion = mysql_fetch_assoc($rollo_estrusion);
$totalRows_rollo_estrusion = mysql_num_rows($rollo_estrusion);
 
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>



</head>
<body>
<?php echo $conexion->header('listas'); ?>
<table id="tabla1">
  <tr>
    <td colspan="2" align="center">
	<table class="table table-bordered table-sm">
	  <tr>
	  <td id="subtitulo">LISTADO DE ROLLOS</td>
	  </tr>
	  <tr>
	  <td id="fuente2"><a href="produccion_impresion_stiker_rollo_add.php?id_op_r=<?php echo $_GET['id_op_r']; ?>"><img src="images/mas.gif" alt="ADD TURNO"title="ADD TURNO" border="0" style="cursor:hand;"/></a></td>
  </tr>
</table>

<table id="tabla1">  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">ROLLO N&deg;</td>
    <td nowrap="nowrap" id="titulo4">O.P.</td>
    <td nowrap="nowrap" id="titulo4">KILOS</td>
    <td nowrap="nowrap" id="titulo4">METROS</td>
    <td nowrap="nowrap" id="titulo4">FECHA INICIO</td>
    <td nowrap="nowrap" id="titulo4">FECHA FIN</td>
    <td nowrap="nowrap" id="titulo4">FECHA IMPRESION</td>
    <td nowrap="nowrap" id="titulo4">COD OPER</td>
    <td nowrap="nowrap" id="titulo4">COD AUX</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['rollo_r']; ?></a></td>
      <td id="dato2"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['id_op_r']; ?></a></td>
      <td id="dato3"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['kilos_r']; $TKILOS+=$row_rollo_estrusion['kilos_r']; ?></a></td>
      <td id="dato3"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['metro_r'];$TMETROS+=$row_rollo_estrusion['metro_r'];?></a></td>
      <td id="dato2"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['fechaI_r']; ?></a></td>
      <td id="dato2"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['fechaF_r']; ?></a></td>      
      <td id="dato2"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['fechaV_r']; ?></a></td>      
      <td id="dato1"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['cod_empleado_r']; ?></a></td>
      <td id="dato1"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['cod_auxiliar_r']; ?></a></td>
    </tr> 
    <?php } while ($row_rollo_estrusion = mysql_fetch_assoc($rollo_estrusion)); ?>
    <tr bgcolor="#FFFFFF">
      <td id="dato1">&nbsp;</td>
      <td id="dato3">TOTAL:</td>
      <td id="dato3"><strong><?php echo $TKILOS; ?></strong></td>
      <td id="dato3"><strong><?php echo $TMETROS; ?></strong></td>
      <td id="dato1">&nbsp;</td>
      <td id="dato1">&nbsp;</td>
      <td id="dato1">&nbsp;</td>
      <td id="dato1">&nbsp;</td>
      <td id="dato1">&nbsp;</td>
    </tr>
</table>
     </td>
  </tr>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($rollo_estrusion);

?>
