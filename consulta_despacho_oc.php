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
$str_numero_r=$_GET["str_numero_r"];
//echo 'variable'.$str_numero_oc;
//ORDEN COMPRA
if ($str_numero_r!='')
{
$resultado = mysql_query("SELECT * FROM Tbl_orden_compra WHERE str_numero_oc = '".$_GET["str_numero_r"]."'");
if ( !ereg("^[0-9a-zA-z-]{1,100}$", $_GET['str_numero_r'])) {	  
{ ?> <div id="numero1"><strong> <?php echo "CARACTERES NO PERMITIDOS, NO DIGITE CARACTERES ESPECIALES NI ESPACIOS"; ?> </strong></div> <?php }
}else		
if (mysql_num_rows($resultado) > 0)
{ ?> <div id="numero2"><strong> <?php echo "LA ORDEN DE COMPRA SI EXISTE";?> </strong></div> <?php }
else 
{ ?> <div id="acceso1"><strong> <?php echo "ORDEN DE COMPRA VALIDADO, PUEDE CONTINUAR REGISTRANDO";?> </strong></div> <?php }
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