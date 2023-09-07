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

$maxRows_proceso_rollos = 99;
$pageNum_proceso_rollos = 0;
if (isset($_GET['pageNum_proceso_rollos'])) {
  $pageNum_proceso_rollos = $_GET['pageNum_proceso_rollos'];
}
$startRow_proceso_rollos = $pageNum_proceso_rollos * $maxRows_proceso_rollos;



$colname_rollo_cola = "-1";
if (isset($_GET['id_op_r'])) {
  $colname_rollo_cola = (get_magic_quotes_gpc()) ? $_GET['id_op_r'] : addslashes($_GET['id_op_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_estrusion = sprintf("SELECT * FROM TblExtruderRollo WHERE TblExtruderRollo.id_op_r=%s",$colname_rollo_cola, $startRow_proceso_rollos, $maxRows_proceso_rollos);
$rollo_estrusion = mysql_query($query_rollo_estrusion, $conexion1) or die(mysql_error());
$row_rollo_estrusion = mysql_fetch_assoc($rollo_estrusion);
$totalRows_rollo_estrusion = mysql_num_rows($rollo_estrusion);

if (isset($_GET['totalRows_proceso_rollos'])) {
  $totalRows_proceso_rollos = $_GET['totalRows_proceso_rollos'];
} else {
  $all_proceso_rollos = mysql_query($query_rollo_estrusion);
  $totalRows_proceso_rollos = mysql_num_rows($all_proceso_rollos);
}
$totalPages_proceso_rollos = ceil($totalRows_proceso_rollos/$maxRows_proceso_rollos)-1;

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
  <tr>
    <td colspan="2" align="center">
	<table class="table table-bordered table-sm">
	  <tr>
	  <td id="subtitulo">LISTADO DE ROLLOS</td>
	  </tr>
    <tr>
    <td id="subtitulo" style="color: red;" >*Para visualizar la flecha de Liquidacion debe seleccionar justo en el rollo que divide el parcial</td>
    </tr>
	  <tr>
	  <td id="fuente2"><a href="produccion_extrusion_stiker_rollo_add.php?id_op_r=<?php echo $_GET['id_op_r']; ?>"><img src="images/mas.gif" alt="ADD ROLLO"title="ADD ROLLO" border="0" style="cursor:hand;"/></a></td>
  </tr>
</table>

<table id="tabla1">  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">ROLLO N&deg;</td>
    <td nowrap="nowrap" id="titulo4">O.P.</td>
    <td nowrap="nowrap" id="titulo4">KILOS</td>
    <td nowrap="nowrap" id="titulo4">METRO</td>
    <td nowrap="nowrap" id="titulo4">FECHA INICIO</td>
    <td nowrap="nowrap" id="titulo4">FECHA FIN</td>
    <td nowrap="nowrap" id="titulo4">FECHA IMPRESION</td>
    <td nowrap="nowrap" id="titulo4">CODIGO OPERARIO</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="dato2"><a href="produccion_extrusion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['rollo_r']; ?></a></td>
      <td id="dato2"><a href="produccion_extrusion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['id_op_r']; ?></a></td>
      <td id="dato3"><a href="produccion_extrusion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['kilos_r']; $TKILOS+=$row_rollo_estrusion['kilos_r']; ?></a></td>
      <td id="dato2"><a href="produccion_extrusion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['metro_r']; $TMETROS+=$row_rollo_estrusion['metro_r']; ?></a></td>
      <td id="dato2"><a href="produccion_extrusion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['fechaI_r']; ?></a></td>
      <td id="dato2"><a href="produccion_extrusion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['fechaF_r']; ?></a></td>
      <td id="dato2"><a href="produccion_impresion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['fechaV_r']; ?></a></td> 
      <td id="dato1"><a href="produccion_extrusion_stiker_rollo_vista.php?id_r=<?php echo $row_rollo_estrusion['id_r']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_rollo_estrusion['cod_empleado_r']; ?></a></td>
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
    </tr> 

<table id="tabla3">
                    <tr>
                      <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_rollos > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_proceso_rollos=%d%s", $currentPage, 0, $queryString_proceso_rollos); ?>">Primero</a>
                          <?php } // Show if not first page ?></td>
                      <td width="31%" align="center" id="dato2"><?php if ($pageNum_proceso_rollos > 0) { // Show if not first page ?>
                          <a href="<?php printf("%s?pageNum_proceso_rollos=%d%s", $currentPage, max(0, $pageNum_proceso_rollos - 1), $queryString_proceso_rollos); ?>">Anterior</a>
                          <?php } // Show if not first page ?></td>
                      <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_rollos < $totalPages_proceso_rollos) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_proceso_rollos=%d%s", $currentPage, min($totalPages_proceso_rollos, $pageNum_proceso_rollos + 1), $queryString_proceso_rollos); ?>">Siguiente</a>
                          <?php } // Show if not last page ?></td>
                      <td width="23%" align="center" id="dato2"><?php if ($pageNum_proceso_rollos < $totalPages_proceso_rollos) { // Show if not last page ?>
                          <a href="<?php printf("%s?pageNum_proceso_rollos=%d%s", $currentPage, $totalPages_proceso_rollos, $queryString_proceso_rollos); ?>">&Uacute;ltimo</a>
                          <?php } // Show if not last page ?></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);
 
mysql_free_result($rollo_estrusion);
 
?>
