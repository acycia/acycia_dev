<?php require_once('Connections/conexion1.php'); ?>
<?php
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php 
mysql_select_db($database_conexion1, $conexion1);
// DATOS
$op=$_GET["op"];  
$rollo=$_GET['rollo']; 
if ($op!='')
{
$sqlr="SELECT id_op_r, MAX(rollo_r) AS maxrollo FROM TblExtruderRollo WHERE id_op_r='$op' ORDER BY rollo_r DESC LIMIT 1"; 
$resultr=mysql_query($sqlr);
$numr=mysql_num_rows($resultr);
$max_rollo=mysql_result($resultr,0,'maxrollo');

$resultado = mysql_query("SELECT id_op_r,rollo_r FROM TblRefiladoRollo WHERE id_op_r = '$op' AND rollo_r='$rollo'");
$numsel= mysql_num_rows($resultado);
if ($numsel > 0 )
{ ?> <div id="numero1"><strong> <?php echo "ESTE ROLLO YA EXISTE!"; ?> </strong></div> <?php }
else  if ($max_rollo >$rollo ){ ?> <div id="acceso1"><strong> <?php echo "PUEDE CONTINUAR..."; ?> </strong></div> <?php } 
}else{ 
echo "NINGUN ROLLO INGRESADO"; //primer if
}
exit();
?>

</body>
</html>
<?php
mysql_free_result($usuario);
?>