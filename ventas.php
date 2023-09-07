<?php 
require_once('Connections/conexion1.php'); 
mysql_select_db($database_conexion1, $conexion1);
$orden=$_GET['orden'];
if($orden=='2')
{
$query_referencias_ventas = "SELECT * FROM referencia,ref_cliente,cliente WHERE referencia.id_ref=ref_cliente.id_ref AND ref_cliente.id_c=cliente.id_c AND referencia.estado_ref='1' ORDER BY referencia.cod_ref ASC";
}
else 
{
$query_referencias_ventas = "SELECT * FROM referencia,ref_cliente,cliente WHERE referencia.id_ref=ref_cliente.id_ref AND ref_cliente.id_c=cliente.id_c AND referencia.estado_ref='1' ORDER BY cliente.nombre_c ASC";
}
$referencias_ventas = mysql_query($query_referencias_ventas, $conexion1) or die(mysql_error());
$row_referencias_ventas = mysql_fetch_assoc($referencias_ventas);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8959-i" />
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
</head>
<body>
<table id="tabla3">
  <tr id="tr1">
    <td colspan="18" id="subtitulo2">RELACION DE VENTAS POR CLIENTE</td>    
  </tr>
  <tr id="tr2">
    <td id="detalle2" width="2%">Nro</td>
    <td id="detalle2" width="18%"><a href="ventas.php">CLIENTE</a></td>
    <td id="detalle2" width="5%"><a href="ventas.php?orden=2">REF</a></td>
    <td id="detalle2" width="5%">TIPO</td>
    <td id="detalle2" width="15%">MATERIAL</td>
    <td id="detalle2" width="5%">ANCHO</td>
    <td id="detalle2" width="5%">LARGO</td>
    <td id="detalle2" width="5%">SOLAPA</td>
    <td id="detalle2" width="5%">CALIBRE</td>
    <td id="detalle2" width="5%">PESO</td>
    <td id="detalle2" width="5%">PRECIO  </td>
    <td id="detalle2" width="5%">UNIDAD</td>
    <td id="detalle2" width="5%">TERM</td>
    <td id="detalle2" width="5%">COTIZ</td>
    <td id="detalle2" width="10%">PLAZO</td>
    <td id="detalle2" width="10%">FECHA</td>
    <td id="detalle2" width="10%">VENDEDOR</td>
    <td id="detalle2" width="5%">(%)</td>
  </tr>
  <?php $nro_venta=0; ?>
  <?php do { ?>
    <tr>
      <td id="detalle3"><?php $nro_venta = $nro_venta+1; echo $nro_venta; ?></td>
      <td id="detalle1"><?php echo $row_referencias_ventas['nombre_c']; ?></td>
      <td nowrap="nowrap" id="detalle1"><?php echo $row_referencias_ventas['cod_ref']; ?> - <?php echo $row_referencias_ventas['version_ref']; ?></td>
      <td id="detalle1"><?php echo $row_referencias_ventas['tipo_bolsa_ref']; ?></td>
      <td id="detalle1"><?php echo $row_referencias_ventas['material_ref']; ?></td>
      <td id="detalle3"><?php echo $row_referencias_ventas['ancho_ref']; ?></td>
      <td id="detalle3"><?php echo $row_referencias_ventas['largo_ref']; ?></td>
      <td id="detalle3"><?php echo $row_referencias_ventas['solapa_ref']; ?></td>
      <td id="detalle3"><?php echo $row_referencias_ventas['calibre_ref']; ?></td>
      <td id="detalle3"><?php echo $row_referencias_ventas['peso_millar_ref']; ?></td>
      <td id="detalle3"><?php //DATOS
	  $referencia=$row_referencias_ventas['id_ref']; 
	  $cliente=$row_referencias_ventas['id_c'];
	  if($referencia!='' && $cliente!='') 
	  { //1
	  $sql_ref_cli="SELECT * FROM cotizacion,cotizacion_existente
WHERE cotizacion_existente.id_ref_ce='$referencia' AND cotizacion_existente.n_cotiz_ce=cotizacion.n_cotiz AND cotizacion.id_c_cotiz='$cliente' ORDER BY cotizacion.n_cotiz DESC";
	  $result_ref_cli= mysql_query($sql_ref_cli);
	  $num_ref_cli= mysql_num_rows($result_ref_cli);
	  if($num_ref_cli >='1') 
	  { //2
	  $cotizacion=mysql_result($result_ref_cli, 0, 'n_cotiz');
	  $precio_venta = mysql_result($result_ref_cli, 0, 'precio_venta_ce'); 
	  $unidad= mysql_result($result_ref_cli, 0, 'unidad_ce'); 
	  $incoterm= mysql_result($result_ref_cli, 0, 'incoterm_ce');
	  $forma_pago= mysql_result($result_ref_cli, 0, 'forma_pago_ce'); 
	  $vendedor = mysql_result($result_ref_cli, 0, 'vendedor');
	  $comision = mysql_result($result_ref_cli, 0, 'comision');
	  } //fin 2
	  else 
	  { //3
	  $sql_ref="SELECT * FROM referencia WHERE id_ref='$referencia'";
	  $result_ref= mysql_query($sql_ref);
	  $num_ref= mysql_num_rows($result_ref);
	  if($num_ref >='1') 
	  { //4
	   $ref = mysql_result($result_ref,0,'cod_ref');
	   $sql_ref_cli2="SELECT * FROM cotizacion,cotizacion_nueva
WHERE cotizacion_nueva.cod_ref_cn='$ref' AND cotizacion_nueva.n_cotiz_cn=cotizacion.n_cotiz AND cotizacion.id_c_cotiz='$cliente' ORDER BY cotizacion.n_cotiz DESC";
	  $result_ref_cli2= mysql_query($sql_ref_cli2);
	  $num_ref_cli2= mysql_num_rows($result_ref_cli2);
	  if($num_ref_cli2 >='1') 
	  { //5
	  $cotizacion=mysql_result($result_ref_cli2, 0, 'n_cotiz');
	  $precio_venta = mysql_result($result_ref_cli2, 0, 'precio_venta_cn'); 
	  $unidad= mysql_result($result_ref_cli2, 0, 'unidad_cn');
	  $incoterm= mysql_result($result_ref_cli2, 0, 'incoterm_cn');
	  $forma_pago= mysql_result($result_ref_cli2, 0, 'forma_pago_cn');
	  $vendedor = mysql_result($result_ref_cli2, 0, 'vendedor');
	  $comision = mysql_result($result_ref_cli2, 0, 'comision');
	  }  //FIN 5 
	 } //FIN 4
	 } //FIN 3
	 echo "$ ".$precio_venta;
	 } //FIN 1 }
	 ?></td>
      <td id="detalle1"><?php echo $unidad; ?></td>
      <td id="detalle1"><?php echo $incoterm; ?></td>
      <td id="detalle2"><?php echo $cotizacion; ?></td>
      <td id="detalle1"><?php echo $forma_pago; ?></td>
      <td id="detalle2" nowrap="nowrap"><?php if($cotizacion!='') { 
	  $sql_fecha="SELECT * FROM cotizacion WHERE n_cotiz='$cotizacion'";
	  $result_fecha= mysql_query($sql_fecha); $num_fecha= mysql_num_rows($result_fecha);
	  if($num_fecha >='1') { $fecha_cotiz=mysql_result($result_fecha, 0, 'fecha_cotiz'); } 
	  echo $fecha_cotiz; } ?>      
      </td>
      <td id="detalle1"><?php if($vendedor!='') {
	  $sql_vendedor="SELECT * FROM vendedor WHERE id_vendedor='$vendedor'";
	  $result_vendedor= mysql_query($sql_vendedor);
	  $num_vendedor= mysql_num_rows($result_vendedor);
	  if($num_vendedor >='1') { $nombre_vendedor = mysql_result($result_vendedor, 0, 'nombre_vendedor'); echo $nombre_vendedor; } } ?></td>
      <td id="detalle3"><?php echo $comision; ?></td>
    </tr>
    <?php } while ($row_referencias_ventas = mysql_fetch_assoc($referencias_ventas)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($referencias_ventas);
?>
