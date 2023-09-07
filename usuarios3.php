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
$colname_usuario_usuarios = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_usuarios = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_usuarios = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_usuarios);
$usuario_usuarios = mysql_query($query_usuario_usuarios, $conexion1) or die(mysql_error());
$row_usuario_usuarios = mysql_fetch_assoc($usuario_usuarios);
$totalRows_usuario_usuarios = mysql_num_rows($usuario_usuarios);

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
</head>
<body>
<div align="center">
<table id="tabla2"><?php do { ?>
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
</div>
</body>
</html>
<?php
mysql_free_result($usuario_usuarios);

mysql_free_result($ver);

mysql_free_result($ver_tipo_user);
?>
