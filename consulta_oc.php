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
$id_oc=$_GET["id_oc"];
// DATOS PROVEEDOR
if ($id_oc!='') 
{ 
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = ("SELECT * FROM cliente WHERE id_c = $id_oc");
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
if ($totalRows_cliente > 0)
{ ?>

<table id="tabla1"><!--se cambio tabla2 x tabla1-->
              <tr>
            <td id="dato1" width="50%"><strong>NIT : </strong><?php echo $row_cliente['nit_c']; ?></td>
            <td id="dato1" width="50%"><strong>PAIS / CIUDAD : </strong><?php  $cad=htmlentities ($row_cliente['pais_c']);echo $cad; ?> / <?php $cad2=htmlentities ($row_cliente['ciudad_c']); echo $cad2;?></td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><strong>NOMBRE DE LA EMPRESA: </strong><?php $cad4=htmlentities ($row_cliente['nombre_c']);echo $cad4; ?></td>
            </tr>
          <tr>
            <td id="dato1"><strong>DIRECCCION:</strong> <?php  $cad1 = htmlentities ($row_cliente['direccion_c']); echo $cad1; ?>
            <input name="dir_c" type="hidden" value="<?php echo $cad1; ?>"></td>
            <td id="dato1"><strong>TELEFONO:</strong><?php echo $row_cliente['telefono_c']; ?></td>
            </tr>
          <tr>
            <td id="dato1"><strong>CONTACTO COMERCIAL:</strong><?php echo $row_cliente['contacto_c']; ?></td>
            <td id="dato1"><strong>TEL COMERCIAL:</strong><?php echo $row_cliente['telefono_contacto_c']; ?></td>
          </tr>
          <tr>
            <td id="dato1"><strong>EMAIL COMERCIAL: </strong><?php echo $row_cliente['email_comercial_c']; ?></td>
            <td id="dato1"><strong>CONDICIONES DE PAGO:</strong>
              <select name="str_condicion_pago_oc" id="str_condicion_pago_oc">
              <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
                <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
                <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias </option>
                <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias </option>
                <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias </option>
                <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias </option>
                <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias </option>
                <option value="PAGO A 120 DIAS"<?php if (!(strcmp("PAGO A 120 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 120 Dias </option>
              </select>
              <input name="id_c_oc" id="id_c_oc" type="hidden" value="<?php echo $row_cliente['id_c']; ?>"> 
              <input name="nit_c" type="hidden" id="nit_c" value="<?php echo $row_cliente['nit_c'] ?>"></td>
          </tr>
          <tr>
            <td id="dato1"><strong>DIRECCION ENTREGA DE FACTURA:</strong>
              <textarea cols="40" name="str_dir_entrega_oc" id="str_dir_entrega_oc" onKeyUp="conMayusculas(this)" rows="2"><?php echo $row_cliente['direccion_envio_factura_c'];?></textarea></td>
            <td id="dato1"><?php if ($row_cliente['id_c']!='') { ?>
              <a href="perfil_cliente_edit.php?id_c=<?php echo $row_cliente['id_c'] ?>" target="_blank">ACTUALIZAR PERFIL CLIENTE</a>
              <?php }?></td>
          </tr>
          <tr>
            <tr>
                 <td id="dato1"><strong>Se entrega Factura? </strong>
                 <select name="entrega_fac" id="entrega_fac" >
                   <option value="SI">SI</option>
                   <option value="NO">NO</option>
                  <option value="">Seleccione...</option>
                 </select>
               </td>
                 <td id="dato1"><strong>Fecha Cierre Facturacion:</strong><input type="date" name="fecha_cierre_fac" id="fecha_cierre_fac" value="" size="10">
                 </td>
            </tr>
            <tr>
              <td id="dato1"><strong>Adjuntar Comprobante? </strong>
                 <select name="comprobante_ent" id="comprobante_ent" > 
                   <option value="NO">NO</option>
                   <option value="SI">SI</option>
                   <option value="">Seleccione...</option>
                 </select>
               </td>
            </tr>
          </tr>
          <tr>
            <td colspan="2" id="dato2"><strong><?php  if ($row_cliente['id_c']!=''){ ?>
            <input id="additem" class="botonGeneral" onclick="guardaRegistro()" value="ADD ITEM">
             <!--<a href="javascript:envio()"><img src="images/mas.gif" alt="ADD ITEM"title="ADD ITEM" border="0" style="cursor:hand;"  /> AGREGAR ITEM</a>--><?php }?>
            </strong></td>
            </tr>
          <tr>
            <td colspan="2" id="dato1">&nbsp;</td>
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
mysql_free_result($usuario);mysql_close($conexion1);
?>