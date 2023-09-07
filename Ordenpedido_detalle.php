<?php require_once('Connections/conexion1.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


$variable = $_GET['id'];
mysql_select_db($database_conexion1, $conexion1);
$query_orden = "SELECT orden_produccion.id     , orden_produccion.fecha_pedido     , orden_produccion.fecha_entrega     , orden_produccion.numero_orden_compra     , orden_produccion.referencia_cliente     , referencia.cod_ref     , referencia.version_ref     , cliente.nombre_c     , cliente.id_c     , usuario.nombre_usuario AS vendedor     ,  orden_produccion.cantidad     , orden_produccion.precio_venta     , 
orden_produccion.registradopor,
orden_produccion.planchas_impresion     , orden_produccion.referencia_nueva     , orden_produccion.orden_produccion     , orden_produccion.f_coextruccion     , orden_produccion.f_impresion     , orden_produccion.f_sellada     , orden_produccion.f_despacho     , orden_produccion.comision     , orden_produccion.notas     , orden_produccion.direccion_despacho FROM acycia_intranet.orden_produccion     INNER JOIN acycia_intranet.cliente          ON (orden_produccion.cliente = cliente.id_c)     INNER JOIN acycia_intranet.usuario          ON (orden_produccion.vendedor = usuario.id_usuario) 
   INNER JOIN acycia_intranet.referencia          ON (orden_produccion.referencia_interna = referencia.id_ref) WHERE (orden_produccion.id = '$variable')";
$orden = mysql_query($query_orden, $conexion1) or die(mysql_error());
$row_orden = mysql_fetch_assoc($orden);
$totalRows_orden = mysql_num_rows($orden);

$registardor = $row_orden['registradopor'];
mysql_select_db($database_conexion1, $conexion1);
$query_registrador = "SELECT nombre_usuario FROM usuario WHERE id_usuario = '$registardor'";
$registrador = mysql_query($query_registrador, $conexion1) or die(mysql_error());
$row_registrador = mysql_fetch_assoc($registrador);
$totalRows_registrador = mysql_num_rows($registrador);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
</script>
</head>

<body>
<table align="center" class="detalles" id="arriba_1">
  <tr>
    <td colspan="4" ><strong class="Estilo1">Registro Nº <?php echo $row_orden['id']; ?></strong></td>
  </tr>
      <tr>
        <td><strong>Fecha pedido</strong></td>
        <td><?php echo $row_orden['fecha_pedido']; ?></td>
        <td><strong>Fecha entrega</strong></td>
        <td><?php echo $row_orden['fecha_entrega']; ?></td>
      </tr>
      <tr>
        <td><strong>Orden de compra Nº</strong></td>
        <td><?php echo $row_orden['numero_orden_compra']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Referencia cliente</strong></td>
        <td><?php echo $row_orden['referencia_cliente']; ?></td>
        <td><strong>REF Interna</strong></td>
        <td><?php echo $row_orden['cod_ref']; ?> - <?php echo $row_orden['version_ref']; ?></td>
      </tr>
      <tr>
        <td><strong>Cliente</strong></td>
        <td><a href="perfil_cliente_vista.php?id_c= <?php echo $row_orden['id_c']; ?>&amp;tipo_usuario=1" target="_blank"><?php echo $row_orden['nombre_c']; ?></a></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr><td><strong>vendedor</strong></td>
        <td><?php echo $row_orden['vendedor']; ?></td>
        <td><strong>Registrado por</strong></td>
        <td><?php echo $row_registrador['nombre_usuario']; ?></td>
      </tr>
      <tr><td><strong>cantidad</strong></td>
        <td><?php echo  number_format($row_orden['cantidad']); ?></td>
        <td><strong>precio_venta</strong></td>
        <td><?php echo $row_orden['precio_venta']; ?></td>
      </tr>
      <tr>
        <td><strong>Planchas impresion</strong></td>
        <td><?php if($row_orden['planchas_impresion']>0){echo "si"; }else{ echo "no";} ?></td>
        <td><strong>Referencia nueva</strong></td>
        <td><?php if($row_orden['referencia_nueva']>0){echo "si"; }else{ echo "no";} ?></td>
      </tr>
      <tr>
        <td><strong>orden produccion</strong></td>
        <td><?php if($row_orden['orden_produccion'] == ""){ ?>
          <a href="#">Registar Numero de orden</a>
<?php }else{ ?><?php echo $row_orden['orden_produccion'] ?><?php } ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Fecha coextruccion</strong></td>
        <td><?php if($row_orden['f_coextruccion'] == ""){?>
          <a href="#" onclick="MM_openBrWindow('ordeningresar_fecha.php?tipo=1&id=<?php echo $row_orden['id']; ?>','','width=400,height=200')">Registarr Fecha</a>
        <?php }else{  ?><?php echo $row_orden['f_coextruccion'] ?><?php }?></td>
        <td><strong>Fecha impresión</strong></td>
        <td><?php if($row_orden['f_impresion'] == ""){?>
          <a href="#" onclick="MM_openBrWindow('ordeningresar_fecha.php?tipo=2&id=<?php echo $row_orden['id']; ?>','','width=400,height=200')">Registarr Fecha</a>
        <?php }else{  ?><?php echo $row_orden['f_impresion'] ?><?php }?>
		
</td>
      </tr>
      <tr>
        <td><strong>Fecha sellada</strong></td>
        <td><?php if($row_orden['f_sellada'] == ""){?>
          <a href="#" onclick="MM_openBrWindow('ordeningresar_fecha.php?tipo=3&id=<?php echo $row_orden['id']; ?>','','width=400,height=200')">Registarr Fecha</a>
        <?php }else{  ?><?php echo $row_orden['f_sellada'] ?><?php }?></td>
        <td><strong>Fecha despacho</strong></td>
        <td><?php if($row_orden['f_despacho'] == ""){?>
          <a href="#" onclick="MM_openBrWindow('ordeningresar_fecha.php?tipo=4&id=<?php echo $row_orden['id']; ?>','','width=400,height=200')">Registarr Fecha</a>
        <?php }else{  ?><?php echo $row_orden['f_despacho'] ?><?php }?></td>
      </tr>
      <tr>
        <td><strong>Comisión</strong></td>
        <td><?php echo $row_orden['comision']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Notas</strong></td>
        <td><?php echo $row_orden['notas']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Dirección despacho</strong></td>
        <td><?php echo $row_orden['direccion_despacho']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
  </tr>
  <?php do { ?>
    <?php } while ($row_orden = mysql_fetch_assoc($orden)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($registrador);

mysql_free_result($orden);
?>
