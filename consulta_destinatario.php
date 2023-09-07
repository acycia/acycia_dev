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
$nit_c=$_GET["nit_c"];
$id_c_cotiz=$_GET["id_c_cotiz"];
$cod_ref_cn=$_GET["cod_ref_cn"];
$id_c=$_GET['id_c'];
//CLIENTE
if ($nit_c!='')
{
$resultado = mysql_query("SELECT * FROM cliente WHERE nit_c = '".$_GET["nit_c"]."'");
if (mysql_num_rows($resultado) > 0)
{ ?> <div id="numero1"><strong> <?php echo "EL NIT EXISTE, FAVOR HACER REVISION"; ?> </strong></div> <?php }
else 
{ ?> <div id="acceso1"><strong> <?php echo "NIT VALIDADO, PUEDE CONTINUAR REGISTRANDO"; ?> </strong></div> <?php }
}
// DATOS CLIENTE
if ($id_c_cotiz!='') 
{ 
$resultcli = mysql_query("SELECT * FROM Tbl_Destinatarios WHERE nit = '$id_c_cotiz'");
$row_cliente = mysql_fetch_assoc($resultcli);
$totalRows_cliente = mysql_num_rows($resultcli);
if ($totalRows_cliente > 0)
{ ?>
<table id="tabla1">
  <tr>
    <td id="detalle1">NIT : <?php echo $row_cliente['nit']; ?></td>
    <td id="detalle1">Telefono : <?php echo $row_cliente['telefono']; ?></td>
    <td id="detalle1">Fax : <?php echo $row_cliente['telefono']; ?></td>
  </tr>
  <tr>
    <td colspan="3" id="detalle1">Direcci&oacute;n : <?php echo $row_cliente['telefono']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Pa&iacute;s : <?php echo $row_cliente['telefono']; ?></td>
    <td id="detalle1">Ciudad : <?php echo $row_cliente['telefono']; ?></td>
    <td id="detalle1">Email : <?php echo $row_cliente['telefono']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">Contacto Comercial : <?php echo $row_cliente['telefono']; ?></td>
    <td id="detalle1">Cargo : <?php echo $row_cliente['telefono']; ?></td>
  </tr>
  <tr>
    <td colspan="3" id="detalle1">Para agregar el detalle de la cotizacion de click en siguiente </td>
  </tr>
</table>
<?php
}
else 
{ 
echo "NINGUN REGISTRO SELECCIONADO"; 
}
}
//REFERENCIA
/*if ($cod_ref_cn!='')
{
$resultado = mysql_query("SELECT * FROM referencia WHERE cod_ref = '$cod_ref_cn'");
if (mysql_num_rows($resultado) > 0)
{ echo "EXISTE!! Favor Cambiarla"; }
else 
{
echo "VALIDADO, continue registrando";
}
}*/
//REFERENCIA CLIENTE
/*if ($id_c_cotiz!='') 
{ 
$resultcli = mysql_query("SELECT * FROM Tbl_Destinatarios WHERE nit = '$id_c_cotiz'");
$row_cliente = mysql_fetch_assoc($resultcli);
$totalRows_cliente = mysql_num_rows($resultcli);
if ($totalRows_cliente > 0)
{ ?>
<table id="tabla1">
  <tr id="tr3">
    <td id="detalle1">NIT : <?php echo $row_cliente['nit']; ?></td>
    <td id="detalle1">Telefono : <?php echo $row_cliente['telefono']; ?></td>
    <td id="detalle1">Fax : <?php echo $row_cliente['telefono']; ?></td>
  </tr>
  <tr id="tr3">
    <td colspan="3" id="detalle1">Direcci&oacute;n : <?php echo $row_cliente['telefono']; ?></td>
  </tr>
  <tr id="tr3">
    <td id="detalle1">Pa&iacute;s : <?php echo $row_cliente['telefono']; ?></td>
    <td id="detalle1">Ciudad : <?php echo $row_cliente['telefono']; ?></td>
    <td id="detalle1">Email : <?php echo $row_cliente['telefono']; ?></td>
  </tr>
  <tr id="tr3">
    <td colspan="2" id="detalle1">Contacto Comercial : <?php echo $row_cliente['telefono']; ?></td>
    <td id="detalle1">Cargo : <?php echo $row_cliente['telefono']; ?></td>
  </tr>
</table>
<?php
}
else 
{ 
echo "NINGUN CLIENTE SELECCIONADO"; 
}
}*/
exit();
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>