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
$id_p=$_GET['id_p'];
			
// DATOS CLIENTE
if ($id_p!='') 
{ 
$resultcli = mysql_query("SELECT * FROM proveedor WHERE id_p = '$id_p'");
$row_cliente = mysql_fetch_assoc($resultcli);
$totalRows_cliente = mysql_num_rows($resultcli);
if ($totalRows_cliente > 0)
{ ?>
<table id="tabla1">
  <tr>
    <td id="detalle1">NIT : <?php echo $row_cliente['nit_p']; ?></td>
    <td id="detalle1">Telefono : <?php echo $row_cliente['telefono_p']; ?></td>
    <td id="detalle1">Fax : <?php echo $row_cliente['fax_p']; ?></td>
  </tr>
  <tr>
    <td colspan="3" id="detalle1">Direcci&oacute;n : <?php echo $row_cliente['direccion_p']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Pa&iacute;s : <?php echo $row_cliente['pais_p']; ?></td>
    <td id="detalle1">Ciudad : <?php echo $row_cliente['ciudad_p']; ?></td>
    <td id="detalle1">Email : <?php echo $row_cliente['email_comercial_p']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">Contacto Comercial : <?php echo $row_cliente['contacto_p']; ?></td>
    <td id="detalle1">Proveedor: <?php echo $row_cliente['proveedor_p']; ?> </td>
  </tr>
  <tr>
    <td colspan="3" id="detalle1">&nbsp; </td>
  </tr>
</table>
<?php
}
else 
{ 
echo "NINGUN REGISTRO SELECCIONADO"; 
}
}
 
exit();
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>