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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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

<!-- select2 -->
<link href="select2/css/select2.min.css" rel="stylesheet"/>
<script src="select2/js/select2.min.js"></script>

<!-- css Bootstrap-->
<link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> 


</head>
<body>
  <?php echo $conexion->header('listas'); ?>
  <table class="table table-bordered table-sm">
    <tr>
      <td colspan="4" id="subtitulo">ADMINISTRAR COSTOS GENERALES</td>
    </tr>
    <tr>
      <td id="subtitulo">VALORES</td>
      <td id="subtitulo">AJUSTES</td>
      <td id="subtitulo">CONSULTAS</td>
      <td id="subtitulo">&nbsp;</td>
    </tr>
    <tr id="tr3">
      <td id="detalle1"><a href="costos_generadores_cif_gga.php">GENERADORES</a></td>
      <td id="detalle1"><!--<a href="proceso_ajuste_listado.php">AJUSTE POR PROCESO</a>--><a href="factor_prestacional_add.php">FACTOR PRESTACIONAL</a></td>
      <td id="detalle1"><a href="costos_listado_ref_xproceso.php">KILOS M.P POR O.P.</a></td>
      <td id="detalle1">&nbsp;</td>
    </tr>
    <tr id="tr3">
      <td id="detalle1"><a href="costos_listado_gga.php">VALOR DEL GGA Y CIF</a></td>
      <td id="detalle1"><a href="proceso_empleados_listado.php">EMPLEADO POR PROCESO</a></td>
      <td id="detalle1"><a href="produccion_registro_extrusion_listado_xkilos.php">MOVIMIENTO DE MATERIAS PRIMAS</a><a href="costos_listado_ggaycif.php"></a></td>
      <td id="detalle1">&nbsp;</td>
    </tr>
    <tr id="tr3">
      <td id="detalle1"><!--<a href="costos_listado_ggaycif.php">GGA Y CIF UNIDAD PROMEDIO</a>--><a href="costo_exportacion_listado.php">EXPORTACION</a></td>
      <td id="detalle1"><a href="aportes.php">APORTES</a><a href="proceso_ajuste_costo_expo_add.php"></a><a href="proceso_empleados_listado.php"></a></td>
      <td id="detalle1"><a href="costos_listado_ref_xproceso_tiempos.php">TIEMPOS  POR O.P.</a></td>
      <td id="detalle1">&nbsp;</td>
    </tr>
    <tr id="tr3">
      <td id="detalle1"><a href="costo_referencia_listado.php">COSTO POR REFERENCIA</a><a href="costo_exportacion_listado.php"></a></td>
      <td id="detalle1"><!--<a href="proceso_ajuste_costo_expo_add.php">AJUSTE PARA EXPORTACION</a><a href="insumos.php"></a>--><a href="insumos.php">INSUMOS</a></td>
      <td id="detalle1"><!--<a href="costos_referencia_cm.php">REFERENCIA POR CM&sup2;</a><a href="costos_liquidacion_mano_obra.php">LIQUIDACI&Oacute;N DE MANO DE OBRA</a>--><a href="costos_op_gastosycif.php">DISTRIBUCION GASTOS Y CIF</a></td>
      <td id="detalle1">&nbsp;</td>
    </tr>
    <tr id="tr3">
      <td id="detalle1"><!--<a href="costo_referencia_listado.php">costo_referencia_listado</a>--></td>
      <td id="detalle1"><a href="inventario.php">INVENTARIO</a></td>
      <td id="detalle1"><!--<a href="costos_op_listado.php">COSTOS</a><a href="costos_liquidacion_mano_obra.php"></a><a href="costos_producto_terminado.php">COSTOS POR O.P.</a>-->
        <a href="costo_op_finalizadas.php">COSTOS</a></td>
        <td id="detalle1">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td id="detalle1"><a href="aportes.php"></a></td>
        <td id="detalle1"><a href="produccion_op_ordenconsultar.php">O.P FINALIZADAS</a></td>
        <td id="detalle1"><a href="pChart/index.html">GRAFICAS</a></td>
        <td id="detalle1">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td id="detalle1">&nbsp;</td>
        <td id="detalle1">&nbsp;</td>
        <td id="detalle1"><a href="costos_totales.php"></a></td>
        <td id="detalle1">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td id="detalle1">&nbsp;</td>
        <td id="detalle1">&nbsp;</td>
        <td id="detalle1"><!--<a href="manteni.php">KARDEX</a>--></td>
        <td id="detalle1">&nbsp;</td>
      </tr>
      <tr id="tr3">
        <td id="detalle1">&nbsp;</td>
        <td id="detalle1">&nbsp;</td>
        <td id="detalle1"><!--<a href="manteni.php">STOCK</a>--></td>
        <td id="detalle1">&nbsp;</td>
      </tr>  
    </table>

    <?php echo $conexion->header('footer'); ?>
  </body>
</html>
<?php
mysql_free_result($usuario);
?>
