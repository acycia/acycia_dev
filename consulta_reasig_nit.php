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
$nit_nuevo=$_GET["nit_nuevo"];
$nit_viejo=$_GET["nit_viejo"];
//REASIGNACION NIT NIT NUEVO
if ($nit_nuevo!='')
{
  $nit_nuevo=trim($nit_nuevo);
  $nit_nuevo = ereg_replace("[^A-Za-z0-9-]", "", $nit_nuevo);
  $nit_nuevo=str_replace(' ', '', $nit_nuevo);

  $nit_nuevo = explode('-', $nit_nuevo);
  $nit_nuevo = $nit_nuevo[0].$nit_nuevo[1];
  
$resultado  = mysql_query("SELECT * FROM cliente WHERE nit_c = '$nit_nuevo'");
$row_cliente = mysql_fetch_assoc($resultado );
$totalRows_cliente = mysql_num_rows($resultado );	

if ( !ereg("^[0-9a-zA-z-]{1,100}$", $_GET["nit_nuevo"])) {	  
{ ?> <div id="numero2"><strong> <?php echo "CARACTERES NO PERMITIDOS, NO DIGITE CARACTERES ESPECIALES NI ESPACIOS"; ?> </strong></div> <?php }
}else		
if ($totalRows_cliente > 0)
{ ?> <div id="numero2"><strong> <?php echo "EL NIT NUEVO '$nit_nuevo' YA EXISTE! INGRESE EL NUEVO NIT!";?> 
<table id="tabla1">
  <tr id="tr2">
    <td colspan="3" id="nivel2">Esta es la informacion del cliente </td>
  </tr>
  <tr>
    <td id="detalle1"><strong>NIT : </strong><?php echo $row_cliente['nit_c']; ?></td>
    <td id="detalle1"><strong>PAIS / CIUDAD : </strong><?php echo $row_cliente['pais_c']; ?> / <?php echo $row_cliente['ciudad_c']; ?></td>    
  </tr>
    <tr>
    <td colspan="2" id="detalle1"><strong>NOMBRE: </strong><?php echo $row_cliente['nombre_c']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>CONTACTO COMERCIAL : </strong><?php echo $row_cliente['contacto_c']; ?></td>
  </tr>
  <tr>
    <td id="detalle1"><strong>TELEFONO : </strong><?php echo $row_cliente['telefono_c']; ?></td>
    <td id="detalle1"><strong>FAX : </strong><?php echo $row_cliente['fax_c']; ?></td>
  </tr>  
</table>
</strong></div>
<?php }else
if ($totalRows_cliente == 0) 
{ ?> <div id="acceso2"><strong> <?php echo "EL NUEVO NIT '$nit_nuevo' NO EXISTE CONTINUE";?> </strong></div> <?php }
}
//REASIGNACION NIT NIT VIEJO
if ($nit_viejo!='')
{
$resultado2  = mysql_query("SELECT * FROM cliente WHERE nit_c = '$nit_viejo'");
$row_cliente2 = mysql_fetch_assoc($resultado2 );
$totalRows_cliente2 = mysql_num_rows($resultado2 );	

if ( !ereg("^[0-9a-zA-z-]{1,100}$", $_GET["nit_viejo"])) {	  
{ ?> <div id="numero2"><strong> <?php echo "CARACTERES NO PERMITIDOS, NO DIGITE CARACTERES ESPECIALES NI ESPACIOS"; ?> </strong></div> <?php }
}else		
if (mysql_num_rows($resultado2) > 0)
{ ?> <div id="acceso2"><strong> <?php echo "EL NIT A CAMBIAR '$nit_viejo' SI EXISTE PUEDE CONTINUAR INGRESE EL NUEVO NIT";?> </strong></div> <?php }
else
if (mysql_num_rows($resultado2) == 0) 
{ ?> <div id="numero2"><strong> <?php echo "EL NIT A CAMBIAR '$nit_viejo' NO EXISTE VERIFIQUE";?> </strong></div> <?php }
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
mysql_free_result($usuario);mysql_close($conexion1);
?>