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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_op = $conexion->llenaSelect('Tbl_orden_produccion',"WHERE b_estado_op > '0'","ORDER BY id_op DESC");  

 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

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
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
  <script>
        $(document).ready(function() { $(".busqueda").select2(); });
    </script> 
<?php echo $conexion->header('listas'); ?>
<form action="despacho_direccion2.php" method="get" name="form1" >
<table class="table">
<tr>
<td id="titulo2">CREACION DE DESPACHO</td>
</tr>
<tr>
  <td id="titulo2"> REF/O.C/O.P
    <?php if (isset($_GET['id_op'])) {$_GET['id_op'];}else{$_GET['id_op']= '';} ?>
    <select class="busqueda selectsGrande"  name="id_op" id="id_op" onChange="if(form1.id_op.value) { consulta_sellado_op(); }else { alert('Debe Seleccionar una O.P')}">
       <option value=""<?php if (!(strcmp("", $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>Seleccione REF / O.C / O.P</option>
       <?php  foreach($row_op as $row_op ) { ?>
        <option value="<?php echo $row_op['id_op']?>"<?php if (!(strcmp($row_op['id_op'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_op['int_cod_ref_op']." / O.C:".$row_op['str_numero_oc_op']." / O.P: ".$row_op['id_op']?></option>
    <?php } ?>  
    </select>


  <a href="despacho_faltantes.php"><img src="images/o.gif" style="cursor:hand;" alt="DESPACHOS FALTANTES" title="DESPACHOS FALTANTES" border="0" /></a>
</td>
  </tr>
<tr>
  <td id="fuente2"><div id="resultado"></div></td>
</tr>
</table>
</form>
<table border="0" width="50%" align="center">
  <!--<tr>
    <td width="23%" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, 0, $queryString_numeracion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_numeracion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, max(0, $pageNum_numeracion - 1), $queryString_numeracion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, min($totalPages_numeracion, $pageNum_numeracion + 1), $queryString_numeracion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_numeracion < $totalPages_numeracion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_numeracion=%d%s", $currentPage, $totalPages_numeracion, $queryString_numeracion); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>-->
</table>
</td>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
 

<?php
mysql_free_result($usuario);

//mysql_free_result($numeracion);

mysql_free_result($op);
?>