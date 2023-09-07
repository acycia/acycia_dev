<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php
session_start();
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

$colname_usuario_usuarios = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_usuarios = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario_usuarios = $conexion->buscar('usuario','usuario',$colname_usuario); 

mysql_select_db($database_conexion1, $conexion1);
$query_ver_tipo_user = "SELECT * FROM tipo_user ORDER BY id_tipo ASC";
$ver_tipo_user = mysql_query($query_ver_tipo_user, $conexion1) or die(mysql_error());
$row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
$totalRows_ver_tipo_user = mysql_num_rows($ver_tipo_user);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="StyleSheet" href="css/formato.css" type="text/css">
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
<div align="center">
<?php echo $conexion->header('listas'); ?>
  <table class="table table-bordered table-sm">
    <tr id="tr1"><td id="subtitulo" colspan="4">LISTADO DE TIPOS DE USUARIO</td>
    </tr>    
    <tr>
      <td class="centrado5"><a href="tipo_usuario_nuevo.php" target="_top"><img src="images/mas.gif" style="cursor:hand;" alt="ADD TIPO DE USUARIO" border="0"></a> N&deg;</td>
      <td class="centrado2">TIPO DE USUARIO</td>
      <td class="textocentrado">CARACTERISTICAS</td>
      <td class="centrado5">PERMISOS</td>
    </tr>
  </table>
<table class="table table-bordered table-sm">
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
      <td class="derecha1"><a href="tipo_usuario_editar.php?id_tipo= <?php echo $row_ver_tipo_user['id_tipo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_tipo_user['id_tipo']; ?></a></td>
      <td class="Estilo4"><a href="tipo_usuario_editar.php?id_tipo= <?php echo $row_ver_tipo_user['id_tipo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_tipo_user['nombre_tipo']; ?></a></td>
      <td class="texto"><a href="tipo_usuario_editar.php?id_tipo= <?php echo $row_ver_tipo_user['id_tipo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ver_tipo_user['observacion_tipo']; ?></a></td>
      <td class="centrado6"><a href="tipo_permisos.php?id_tipo=<?php echo $row_ver_tipo_user['id_tipo']; ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/identico.gif" style="cursor:hand;" alt="PERMISOS" border="0"></a></td>
    </tr>
    <?php } while ($row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user)); ?>
</table>
</div>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario_usuarios);

mysql_free_result($ver_tipo_user);
?>
