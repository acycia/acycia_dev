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
//DATO
 $tipo_inv=$_GET["tipo"];
// DATOS PROVEEDOR
 switch($tipo_inv) {
	case 1: 
//REFERENCIAS
  $codigo=$_GET["cod_ref"];
  $cod_r=explode("-",$codigo);
  $cod_ref=$cod_r[0];//solamente referencia
  
$resultcosto = mysql_query("SELECT str_unidad_io,int_precio_io FROM Tbl_items_ordenc WHERE int_cod_ref_io = '$cod_ref' ORDER BY id_items DESC LIMIT 1");
$row_costo = mysql_fetch_assoc($resultcosto);
$totalRows_costo = mysql_num_rows($resultcosto);
if ($totalRows_costo > 0)
{ ?>
<input name="CostoUnd" type="number" id="CostoUnd" min="0" step="0.01" style="width:70px" required value="<?php echo $row_costo['int_precio_io']; ?>"/>

<input type="hidden" name="medida" value="<?php echo $row_costo['str_unidad_io']; ?>" />
<?php
}
else 
{ 
echo "No Existe!"; 
}
break;
	  case 2: 
//INSUMOS
$cod_ref=$_GET["cod_ref"]; 

$resultcosto = mysql_query("SELECT medida_insumo,valor_unitario_insumo FROM insumo WHERE id_insumo = '$cod_ref'");
$row_costo = mysql_fetch_assoc($resultcosto);
$totalRows_costo = mysql_num_rows($resultcosto);	
if ($totalRows_costo > 0)
{ ?>
<input name="CostoUnd" type="number" id="CostoUnd" min="0" step="0.01" style="width:70px" required value="<?php echo $row_costo['valor_unitario_insumo']; ?>"/>
<input type="hidden" name="medida" value="<?php echo $row_costo['medida_insumo']; ?>" />
<input type="hidden" name="MM_insert2" value="form1" />
<?php
}
else 
{ 
echo "No Existe!"; 
}
break;

case 3: 
echo "Producto en proceso Pendiente";
 break;
 case 4: 
echo "Materia Prima en proceso Pendiente";
 break;

 }
 
	  
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>