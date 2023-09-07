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
$id_p=$_GET["id_p"];
$id_insumo=$_GET["id_insumo"];
$nit_p=$_GET["nit_p"];
$proveedor_p=$_GET["proveedor_p"];
// DATOS PROVEEDOR
if ($id_p!='') 
{ 
$resultp = mysql_query("SELECT * FROM proveedor WHERE id_p = '$id_p'");
$row_proveedor = mysql_fetch_assoc($resultp);
$totalRows_proveedor = mysql_num_rows($resultp); 
if ($totalRows_proveedor > 0)
{ ?>
<table id="tabla1">
  <tr>
    <td id="detalle1"><strong>NIT : </strong><?php echo $row_proveedor['nit_p']; ?></td>
    <td id="detalle1"><strong>PAIS / CIUDAD : </strong><?php echo $row_proveedor['pais_p']; ?> / <?php echo $row_proveedor['ciudad_p']; ?></td>    
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>CONTACTO COMERCIAL : </strong><?php echo $row_proveedor['contacto_p']; ?></td>
  </tr>
  <tr>
    <td id="detalle1"><strong>TELEFONO : </strong><?php echo $row_proveedor['telefono_p']; ?></td>
    <td id="detalle1"><strong>FAX : </strong><?php echo $row_proveedor['fax_p']; ?></td>
  </tr>  
  <tr id="tr2">
    <td colspan="3" id="detalle2">Para agregar el detalle de la O.C. de click en siguiente </td>
  </tr>
</table>
<?php
}
else 
{ 
echo "NINGUN REGISTRO SELECCIONADO"; 
}
}

//NIT
if ($nit_p!='')
{
	//[0-9a-zA-z]{6,13}\[-]{1}\[0-9]{1}
	//[0-9a-zA-z-]{1,13}
$resultado = mysql_query("SELECT nit_p FROM proveedor WHERE nit_p = '$nit_p'");
/*if ( !ereg("^[0-9a-zA-z]{5,15}[-]{1}[0-9]{1,3}$", $_GET['nit_p'])) 	   
{ ?> <div id="numero1"><strong> <?php echo "No digite caracteres especiales, minimo 5 datos antes del -\n y debe tener un solo digito despues del guion, \n maximo 14 datos"; return false;?> </strong></div> <?php 
}
else*/	
if (mysql_num_rows($resultado) > 0)
{ ?> <div id="numero1"><strong> <?php echo "EL nit existe, reviselo"; return false;?> </strong></div> <?php }
else
{ ?> <div id="acceso1"><strong> <?php echo "El nit es correcto continue"; return true;?> </strong></div> <?php }
}
//NOMBRE
if ($proveedor_p!='')
{
$resultado2 = mysql_query("SELECT proveedor_p FROM proveedor WHERE proveedor_p LIKE '%$proveedor_p%'");	
if (mysql_num_rows($resultado2) > 0)
{ ?> <div id="numero1"><strong> <?php echo "EL Nombre existe, reviselo"; return false;?> </strong></div> <?php 
}
else
{ ?> <div id="acceso1"><strong> <?php echo "El nombre es correcto continue"; return true;?> </strong></div> <?php }	
}

// DATOS INSUMO
if ($id_insumo!='') 
{
$resultinsumo = mysql_query("SELECT * FROM insumo WHERE id_insumo = '$id_insumo'");
$row_insumo = mysql_fetch_assoc($resultinsumo);
$totalRows_insumo = mysql_num_rows($resultinsumo);
if ($totalRows_insumo> 0)
{ ?>
<table id="tabla1">
  <tr>
    <td id="dato2"><strong>CODIGO : </strong><?php echo $row_insumo['codigo_insumo']; ?></td>
    <td id="dato2"><strong>MEDIDA : </strong><?php $id_medida=$row_insumo['medida_insumo']; if($id_medida != '') { $resultmedida = mysql_query("SELECT * FROM medida WHERE id_medida = '$id_medida'");
$row_medida = mysql_fetch_assoc($resultmedida);
$totalRows_medida = mysql_num_rows($resultmedida);
if ($totalRows_medida > 0)
{ echo $row_medida['nombre_medida']; } } ?></td>    
    <td id="dato2"><strong>VALOR UNITARIO </strong>
      <input type="number" name="valor_unitario_det" style="width:120px" min="0" step="0.0001" placeholder="max 0.0001"  value="<?php echo $row_insumo['valor_unitario_insumo']; ?>" onBlur="detalle()"> <span style="color: red;" >Ojo este valor modifica el insumo</span>
    </td>
  </tr>
</table>
<?php
}
else 
{ 
echo "NINGUN REGISTRO SELECCIONADO"; 
}
}
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>