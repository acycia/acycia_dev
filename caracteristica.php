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
if ($str_numero_oc!='')
{
$result = mysql_query("SELECT * FROM Tbl_caracteristicas ORDER BY id_c ASC");	 
	  $sql = " SELECT * FROM Tbl_caracteristicas ORDER BY id_c ASC ";
    $res = mysql_query($sql);
   
    while ($reg = mysql_fetch_assoc($res)){
       echo "<br />";
       echo "<div id='acceso1'>".utf8_encode($reg['str_nombre_caract_c'])."</div>";
       echo "<hr />";
    }
}
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>