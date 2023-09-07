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

$maxRows_registros = 7;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 
 
$row_registros = $conexion->buscarListar("evaluacion_anual","*","ORDER BY id_eva DESC","",$maxRows_registros,$pageNum_registros,"" );

mysql_select_db($database_conexion1, $conexion1);
$query_evaluaciones = "SELECT * FROM evaluacion_anual ORDER BY id_eva DESC";
$evaluaciones = mysql_query($query_evaluaciones, $conexion1) or die(mysql_error());
$row_evaluaciones = mysql_fetch_assoc($evaluaciones);
$totalRows_evaluaciones = mysql_num_rows($evaluaciones);

if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('evaluacion_anual'); 
} 
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;


$queryString_registros = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_registros") == false && 
        stristr($param, "totalRows_registros") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_registros = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_registros = sprintf("&totalRows_registros=%d%s", $totalRows_registros, $queryString_registros);

?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
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
        <td colspan="4" id="titulo1">EVALUACION DE DESEMPE&Ntilde;O DE PROVEEDORES (ANUAL) </td>
        <td id="dato2"><a href="evaluacion_anual.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="evaluacion_proveedores_anual_add.php" target="_top"><img src="images/mas.gif" alt="ADD EVALUACION FINAL" border="0" style="cursor:hand;"/></a><a href="proveedores.php" target="_top"><img src="images/p.gif" alt="PROVEEDORES" border="0" style="cursor:hand;"/></a></td>
      </tr>
      <tr id="tr2">
        <td id="titulo4">N&deg;</td>
        <td id="titulo4">FECHA INICIAL </td>
        <td id="titulo4">FECHA FINAL </td>
        <td id="titulo4">FECHA REGISTRO </td>
        <td id="titulo4">RESPONSABLE</td>
      </tr>
      <?php foreach($row_registros as $row_evaluaciones) {  ?>
        <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
            <td id="dato2"><a href="evaluacion_proveedores_anual_vista.php?id_eva=<?php echo $row_evaluaciones['id_eva']; ?>&desde=<?php echo $row_evaluaciones['fecha_desde_eva']; ?>&hasta=<?php echo $row_evaluaciones['fecha_hasta_eva']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['id_eva']; ?></a></td>
          <td id="dato2"><a href="evaluacion_proveedores_anual_vista.php?id_eva=<?php echo $row_evaluaciones['id_eva']; ?>&desde=<?php echo $row_evaluaciones['fecha_desde_eva']; ?>&hasta=<?php echo $row_evaluaciones['fecha_hasta_eva']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['fecha_desde_eva']; ?></a></td>
          <td id="dato2"><a href="evaluacion_proveedores_anual_vista.php?id_eva=<?php echo $row_evaluaciones['id_eva']; ?>&desde=<?php echo $row_evaluaciones['fecha_desde_eva']; ?>&hasta=<?php echo $row_evaluaciones['fecha_hasta_eva']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['fecha_hasta_eva']; ?></a></td>
          <td id="dato2"><a href="evaluacion_proveedores_anual_vista.php?id_eva=<?php echo $row_evaluaciones['id_eva']; ?>&desde=<?php echo $row_evaluaciones['fecha_desde_eva']; ?>&hasta=<?php echo $row_evaluaciones['fecha_hasta_eva']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['fecha_realizacion_eva']; ?></a></td>
          <td id="dato2"><a href="evaluacion_proveedores_anual_vista.php?id_eva=<?php echo $row_evaluaciones['id_eva']; ?>&desde=<?php echo $row_evaluaciones['fecha_desde_eva']; ?>&hasta=<?php echo $row_evaluaciones['fecha_hasta_eva']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_evaluaciones['responsable_eva']; ?></a></td>
        </tr>
        <?php } ?>
      <tr>
        <td colspan="5" id="dato2">&nbsp;</td>
        </tr>
    </table>
            <!-- tabla para paginacion opcional -->
            <table border="0" width="50%" align="center">
              <tr>
                <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
                <?php } // Show if not first page ?>
              </td>
              <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
              <?php } // Show if not first page ?>
            </td>
            <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
            <?php } // Show if not last page ?>
          </td>
          <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
        </td>
      </tr>
    </table>
    <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($evaluaciones);
?>