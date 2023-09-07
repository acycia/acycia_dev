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
//CONSULTA PARA CIUDAD DE CLIENTE MATRICULA
mysql_select_db($database_conexion1, $conexion1);
$query_n_ciudad = "select * FROM Tbl_ciudades_col ";
$n_ciudad = mysql_query($query_n_ciudad, $conexion1) or die(mysql_error());
$row_n_ciudad = mysql_fetch_assoc($n_ciudad);
$totalRows_n_ciudad = mysql_num_rows($n_ciudad);
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
$idciudad=$_GET["idciudad"];  
$ciudad_c=$_GET['ciudad_c']; //variable para examinar indicativo de ciudad 
//CONTROL CIUDAD
if ($ciudad_c!='')
{
$resultad = mysql_query("SELECT * FROM Tbl_ciudades_col WHERE id_ciudad = '".$_GET["idciudad"]."'");
	
if (mysql_num_rows($resultad) > 0)
{ ?> <div id="numero1"><strong> <?php echo "OK PRIMERA OPCION"; ?> </strong></div> <?php }
else 
{ ?> <div id="acceso1"><strong> <?php echo "MAL SEGUNDA OPCION "; ?> </strong></div> <?php }
}
				



else 
{ 
echo "NINGUN CLIENTE SELECCIONADO"; 
}

exit();
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>