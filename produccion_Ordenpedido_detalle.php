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


$variable = $_GET['id_op'];
mysql_select_db($database_conexion1, $conexion1);
$query_orden = "SELECT Tbl_orden_produccion.id_op, Tbl_orden_produccion.fecha_registro_op, Tbl_orden_produccion.fecha_entrega_op, Tbl_orden_produccion.str_numero_oc_op, Tbl_orden_produccion.str_tipo_bolsa_op,  Tbl_orden_produccion.int_cod_ref_op,  Tbl_orden_produccion.id_ref_op, Tbl_referencia.cod_ref, Tbl_referencia.version_ref, Tbl_referencia.B_generica, cliente.nombre_c, cliente.id_c, Tbl_orden_produccion.int_cantidad_op, Tbl_orden_produccion.str_responsable_op, Tbl_orden_produccion.f_coextruccion, 
Tbl_orden_produccion.f_impresion, Tbl_orden_produccion.f_sellada, Tbl_orden_produccion.f_despacho 
FROM Tbl_orden_produccion INNER JOIN cliente  
INNER JOIN Tbl_referencia ON (Tbl_orden_produccion.id_ref_op = Tbl_referencia.id_ref) WHERE (Tbl_orden_produccion.id_op = '$variable')";
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
    <td colspan="4" ><strong class="Estilo1">Registro Nº <?php echo $row_orden['id_op']; ?></strong></td>
  </tr>
      <tr>
        <td><strong>Fecha pedido</strong></td>
        <td><?php echo $row_orden['fecha_registro_op']; ?></td>
        <td><strong>Fecha entrega</strong></td>
        <td><?php echo $row_orden['fecha_entrega_op']; ?></td>
      </tr>
      <tr>
        <td><strong>Orden de compra Nº</strong></td>
        <td><?php echo $row_orden['str_numero_oc_op']; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Referencia cliente</strong></td>
        <td><?php echo $row_orden['str_tipo_bolsa_op']; ?></td>
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
        <td><?php echo $row_orden['str_responsable_op']; ?></td>
      </tr>
      <tr><td><strong>cantidad</strong></td>
        <td><?php echo  number_format($row_orden['int_cantidad_op']); ?></td>
        <td><strong>precio_venta</strong></td>
        <td><?php echo $row_orden['precio_venta']; ?></td>
      </tr>
      <tr>
        <td><strong>Planchas impresion</strong></td>
        <td><?php if($row_orden['planchas_impresion']>0){echo "si"; }else{ echo "no";} ?></td>
        <td><strong>Referencia nueva</strong></td>
        <td><?php if($row_orden['B_generica']>0){echo "si"; }else{ echo "no";} ?></td>
      </tr>
      <tr>
        <td><strong>orden produccion</strong></td>
        <td><?php if($row_orden['id_op'] == ""){ ?>
          <a href="#">Registrar Numero de orden</a>
<?php }else{ ?><?php echo $row_orden['id_op'] ?><?php } ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><strong>Fecha coextruccion</strong></td>
        <td><?php if($row_orden['f_coextruccion'] == ""){?>
          <a href="#" onclick="MM_openBrWindow('ordeningresar_fecha.php?tipo=1&id=<?php echo $row_orden['id_op']; ?>','','width=400,height=200')">Registrar Fecha</a>
        <?php }else{  ?><?php echo $row_orden['f_coextruccion'] ?><?php }?></td>
        <td><strong>Fecha impresión</strong></td>
        <td><?php if($row_orden['f_impresion'] == ""){?>
          <a href="#" onclick="MM_openBrWindow('ordeningresar_fecha.php?tipo=2&id=<?php echo $row_orden['id_op']; ?>','','width=400,height=200')">Registrar Fecha</a>
        <?php }else{  ?><?php echo $row_orden['f_impresion'] ?><?php }?>
		
</td>
      </tr>
      <tr>
        <td><strong>Fecha sellada</strong></td>
        <td><?php if($row_orden['f_sellada'] == ""){?>
          <a href="#" onclick="MM_openBrWindow('ordeningresar_fecha.php?tipo=3&id=<?php echo $row_orden['id_op']; ?>','','width=400,height=200')">Registrar Fecha</a>
        <?php }else{  ?><?php echo $row_orden['f_sellada'] ?><?php }?></td>
        <td><strong>Fecha despacho</strong></td>
        <td><?php if($row_orden['f_despacho'] == ""){?>
          <a href="#" onclick="MM_openBrWindow('ordeningresar_fecha.php?tipo=4&id=<?php echo $row_orden['id_op']; ?>','','width=400,height=200')">Registrar Fecha</a>
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
