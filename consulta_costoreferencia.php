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
$id_ref=$_GET["id_ref"];
// DATOS PROVEEDOR
if ($id_ref!='') 
{ 
$resultp = mysql_query("SELECT cod_ref,version_ref,tipo_bolsa_ref,n_cotiz_ref FROM Tbl_referencia WHERE id_ref = '$id_ref'");
$row_referencia = mysql_fetch_assoc($resultp);
$totalRows_referencia = mysql_num_rows($resultp);
if ($totalRows_referencia > 0)
{ ?>
<table id="tabla1">
  <tr>
    <td id="detalle1"><strong>CODIGO REF : </strong><?php echo $row_referencia['cod_ref']; ?>
    <input type="hidden" name="cod_cref" id="cod_cref" value="<?php echo $row_referencia['cod_ref']; ?>">
    <input type="hidden" name="codigo_cref" id="codigo_cref" value="<?php echo $row_referencia['cod_ref']; ?>"></td>
    <td id="detalle1"><strong>VERSION : </strong> <input name="version_cref" type="number" step="01" placeholder="00" min="0" required style="width:40px" max="20" value="<?php echo $row_referencia['version_ref']; ?>"/> </td>    
  </tr>
  <tr>
    <td colspan="2" id="detalle1"><strong>DESCRIPCION : </strong><?php echo $row_referencia['tipo_bolsa_ref']; ?>
    <input type="hidden" name="descripcion_cref" id="descripcion_cref" value="<?php echo $row_referencia['tipo_bolsa_ref']; ?>"></td>
  </tr>
  <tr>
    <td id="detalle1"><strong>UNIDAD : </strong>
    
    <?php	  $cliente=$row_referencia['n_cotiz_ref'];
	  $sqlinv="SELECT cliente.id_c,cliente.nombre_c,cliente.nit_c,Tbl_cotizaciones.Str_tipo FROM Tbl_cotizaciones, cliente WHERE Tbl_cotizaciones.N_cotizacion='$cliente' AND Tbl_cotizaciones.Str_nit=cliente.nit_c ORDER BY Tbl_cotizaciones.N_cotizacion DESC LIMIT 1";
	  $resultinv=mysql_query($sqlinv); 
	  $numinv=mysql_num_rows($resultinv);
	  echo $strTipo=mysql_result($resultinv,0,'Tbl_cotizaciones.Str_tipo');
	   ?>
    
    <input type="hidden" name="unidad_cref" id="unidad_cref" value="<?php echo $strTipo;?>"></td>
    <td id="detalle1"><strong>CLIENTE : </strong><?php  

	  if($numinv >= '1') 
	  { 
	  $nombre_cliente=mysql_result($resultinv,0,'cliente.nombre_c');
	   
	  echo $nombre_cliente;
	  $id_c=mysql_result($resultinv,0,'cliente.id_c');
	  }
	?>
    <input type="hidden" name="cliente_cref" id="cliente_cref" value="<?php echo $nombre_cliente; ?>"></td>
  </tr>
  <tr>
    <td id="detalle1"><strong>COSTO REF:</strong><input name="costo_und_cref" type="number" id="costo_und_cref" min="0" step="0.01" style="width:100px" required value="0.00"/></td>
    <td id="detalle1">&nbsp;</td>
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