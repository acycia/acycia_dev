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
$str_numero_oc=$_GET["str_numero_oc"];
//echo 'variable'.$str_numero_oc;
//ORDEN COMPRA
if ($str_numero_oc!='')
{
$result = mysql_query("SELECT * FROM Tbl_orden_compra WHERE str_numero_oc = '".$_GET["str_numero_oc"]."'");
if ( !ereg("^[0-9a-zA-z-]{1,100}$", $_GET['str_numero_oc'])) {	  
{ ?> <div id="numero1"><strong> <?php echo "<input name='validar_oc' type='hidden' value='1'> Caracteres no permitidos, verifique!"; ?> </strong></div> <?php }
}else		
if (mysql_num_rows($result) > 0)
{ ?> <div id="numero1"><strong> <?php echo "<input name='validar_oc' type='hidden' value='1'> La orden de compra ya existe, <br> revise <a href='orden_compra_cl2.php' target='_self'>Aqui</a> en el menu modificacion o.c <br> para activarla de nuevo y modificarla";?> </strong></div> <?php }
else 
{ ?> <div id="acceso1"><strong> <?php echo "ORDEN DE COMPRA VALIDADA";?> </strong></div> <?php }
}
/*else 
{ 
echo "NINGUN REGISTRO SELECCIONADO"; 
}
*/
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>