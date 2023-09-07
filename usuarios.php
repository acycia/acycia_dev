<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?><?php
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
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario_usuarios); 
mysql_select_db($database_conexion1, $conexion1);
$query_ver = "SELECT * FROM usuario ORDER BY usuario ASC";
$ver = mysql_query($query_ver, $conexion1) or die(mysql_error());
$row_ver = mysql_fetch_assoc($ver);
$totalRows_ver = mysql_num_rows($ver);

mysql_select_db($database_conexion1, $conexion1);
$query_ver_tipo_user = "SELECT * FROM tipo_user";
$ver_tipo_user = mysql_query($query_ver_tipo_user, $conexion1) or die(mysql_error());
$row_ver_tipo_user = mysql_fetch_assoc($ver_tipo_user);
$totalRows_ver_tipo_user = mysql_num_rows($ver_tipo_user);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
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
<?php echo $conexion->header('listas'); ?>
  <table class="table table-bordered table-sm">
  <tr>
    <td id="titulo2">LISTADO DE USUARIOS </td>
    <td id="titulo2"><a href="usuario_nuevo.php" target="_top"><img src="images/mas.gif" alt="ADD USUARIO" border="0" style="cursor:hand;" ></a></td>
  </tr>
</table>
<table class="table table-bordered table-sm">
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
        <td class="Estilo4"><a href="usuario_editar.php?id_usuario=<?php echo $row_ver['id_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_ver['usuario']; ?> </a> </td>
      <td class="Estilo4"><a href="usuario_editar.php?id_usuario=<?php echo $row_ver['id_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_ver['nombre_usuario']; ?> </a> </td>
      <td class="Estilo4">
    <?php $tipo2=$row_ver['tipo_usuario'];
    $sql2="SELECT * FROM tipo_user WHERE id_tipo='$tipo2'";
    $result2=mysql_query($sql2);
    $num2=mysql_num_rows($result2);
    if ($num2 >= '1')
    {
    $tipo3=mysql_result($result2,0,'nombre_tipo'); 
    }
    ?>
    <a href="usuario_editar.php?id_usuario=<?php echo $row_ver['id_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $tipo3; ?> </a> </td>
      <td class="Estilo4">
    <?php $tipo_user=$row_ver['tipo_usuario'];
    $codigo=$row_ver['codigo_usuario'];
    if($tipo_user=='10')
    {
    if($codigo!='0')
    {   
    $sql3="SELECT * FROM cliente WHERE id_c='$codigo'";
    $result3=mysql_query($sql3);
    $num3=mysql_num_rows($result3);
    if($num3 >= '1')
    {
    $codigo2=mysql_result($result3,0,'nombre_c');
    }
    }
    if($codigo=='0')
    {
    $codigo2='NO EXISTE';
    }
    }
    else
    {
    $codigo2=$row_ver['codigo_usuario'];
    }
    ?>
    <a href="usuario_editar.php?id_usuario=<?php echo $row_ver['id_usuario']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $codigo2; ?> </a> </td>
    </tr>
    <?php } while ($row_ver = mysql_fetch_assoc($ver)); ?>
</table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario_usuarios);

mysql_free_result($ver);

mysql_free_result($ver_tipo_user);
?>
