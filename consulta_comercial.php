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
$query_n_ciudad = "SELECT * FROM Tbl_ciudades_col ";
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
$nit_c=$_GET["nit_c"];
$nombre_c=$_GET["nombre_c"];
$id_c_cotiz=$_GET["id_c_cotiz"];
$cod_ref_cn=$_GET["cod_ref_cn"];
$id_c=$_GET['id_c'];
//NIT
if ($nit_c!='')
{

  $nit_c=trim($_GET['nit_c']);
  $nit_c = ereg_replace("[^A-Za-z0-9-]", "", $nit_c);
  $nit_c=str_replace(' ', '', $nit_c);

  $nit_c = explode('-', $nit_c);
  $nit_c = $nit_c[0].$nit_c[1];
  
$resultado = mysql_query("SELECT nit_c,nombre_c FROM cliente WHERE nit_c = '".$nit_c."'");
$row_cliente = mysql_fetch_assoc($resultado);
$totalRows_cliente = mysql_num_rows($resultado);
if ($totalRows_cliente > 0)
{ 
$sunombre=$row_cliente['nombre_c'];
}
if (mysql_num_rows($resultado) > 0)
{ ?> <div id="numero1"><strong> <?php echo "EL nit existe, a nombre de: ".$sunombre; return false;?> </strong></div> 
<?php }
else
{ ?> <div id="acceso1"><strong> <?php echo "El nit es correcto continue"; return true;?> </strong></div> <?php }
}
//NOMBRE
if ($nombre_c!='')
{
//$resultado2 = mysql_query("SELECT nombre_c FROM cliente WHERE (INSTR(nombre_c,'$nombre_c') > 0)");
//$resultado2 = mysql_query("SELECT nombre_c FROM cliente WHERE nombre_c REGEXP '[[:<:]]".$nombre_c."[[:>:]]'");
$resultado2 = mysql_query("SELECT nombre_c FROM cliente WHERE nombre_c LIKE '%$nombre_c%'");
$row_cliente = mysql_fetch_assoc($resultado2);
$totalRows_cliente = mysql_num_rows($resultado2);
if ($totalRows_cliente > 0)
{ 
$sunombre=$row_cliente['nombre_c'];
}	
if (mysql_num_rows($resultado2) > 0)
{ ?> <div id="numero1"><strong> <?php echo "Existe nombre similar: $sunombre"; return false;?> </strong></div> <?php 
}
else
{ ?> <div id="acceso1"><strong> <?php echo "El nombre es correcto continue"; return true;?> </strong></div> <?php }	
}			
// DATOS CLIENTE
if ($id_c_cotiz!='') 
{ 
$resultcli = mysql_query("SELECT * FROM cliente WHERE id_c = '$id_c_cotiz'");
$row_cliente = mysql_fetch_assoc($resultcli);
$totalRows_cliente = mysql_num_rows($resultcli);
if ($totalRows_cliente > 0)
{ ?>
<table id="tabla1">
  <tr>
    <td id="detalle1">NIT : <?php echo $row_cliente['nit_c']; ?></td>
    <td id="detalle1">Telefono : <?php echo $row_cliente['telefono_c']; ?></td>
    <td id="detalle1">Fax : <?php echo $row_cliente['fax_c']; ?></td>
  </tr>
  <tr>
    <td colspan="3" id="detalle1">Direcci&oacute;n : <?php echo $row_cliente['direccion_c']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Pa&iacute;s : <?php echo $row_cliente['pais_c']; ?></td>
    <td id="detalle1">Ciudad : <?php echo $row_cliente['ciudad_c']; ?></td>
    <td id="detalle1">Email : <?php echo $row_cliente['email_comercial_c']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">Contacto Comercial : <?php echo $row_cliente['contacto_c']; ?></td>
    <td id="detalle1">Cargo : <?php echo $row_cliente['cargo_contacto_c']; ?></td>
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
if ($cod_ref_cn!='')
{
$resultado = mysql_query("SELECT * FROM Tbl_referencia WHERE cod_ref = '$cod_ref_cn'");
if (mysql_num_rows($resultado) > 0)
{ echo "EXISTE!! Favor Cambiarla"; }
else 
{
echo "VALIDADO, continue registrando";
}
}
//REFERENCIA CLIENTE
if ($id_c!='') 
{ 
$resultcli = mysql_query("SELECT * FROM cliente WHERE id_c = '$id_c'");
$row_cliente = mysql_fetch_assoc($resultcli);
$totalRows_cliente = mysql_num_rows($resultcli);
if ($totalRows_cliente > 0)
{ ?>
<table id="tabla1">
  <tr id="tr3">
    <td id="detalle1">NIT : <?php echo $row_cliente['nit_c']; ?></td>
    <td id="detalle1">Telefono : <?php echo $row_cliente['telefono_c']; ?></td>
    <td id="detalle1">Fax : <?php echo $row_cliente['fax_c']; ?></td>
  </tr>
  <tr id="tr3">
    <td colspan="3" id="detalle1">Direcci&oacute;n : <?php echo $row_cliente['direccion_c']; ?></td>
  </tr>
  <tr id="tr3">
    <td id="detalle1">Pa&iacute;s : <?php echo $row_cliente['pais_c']; ?></td>
    <td id="detalle1">Ciudad : <?php echo $row_cliente['ciudad_c']; ?></td>
    <td id="detalle1">Email : <?php echo $row_cliente['email_comercial_c']; ?></td>
  </tr>
  <tr id="tr3">
    <td colspan="2" id="detalle1">Contacto Comercial : <?php echo $row_cliente['contacto_c']; ?></td>
    <td id="detalle1">Cargo : <?php echo $row_cliente['cargo_contacto_c']; ?></td>
  </tr>
</table>
<?php
}
else 
{ 
echo "NINGUN CLIENTE SELECCIONADO"; 
}
}
exit();
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>