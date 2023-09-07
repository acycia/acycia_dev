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
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<?php 
// DATOS
$id_c_ce=$_GET["id_c_ce"];	
$id_ref_det=$_GET["id_ref_det"];
$id_ref_det_edit=$_GET["id_ref_det_edit"];
$pedido_ce=$_GET["pedido_ce"];		
// DATOS CLIENTE
if ($id_c_ce!='') 
{ 
$resultcli = mysql_query("SELECT * FROM cliente WHERE id_c = '$id_c_ce'");
$row_cliente = mysql_fetch_assoc($resultcli);
$totalRows_cliente = mysql_num_rows($resultcli);
if ($totalRows_cliente > 0)
{ ?>
  <tr>
    <td colspan="2" align="center" id="linea1">
<table id="tabla1">
     <td colspan="2" id="detalle1">NIT : <?php echo $row_cliente['nit_c']; ?></td>
      <td colspan="2" id="detalle1">Telefono : <?php echo $row_cliente['telefono_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Contacto Comercial : <?php echo $row_cliente['contacto_c']; ?></td>
      <td colspan="2" id="detalle1">Fax : <?php echo $row_cliente['fax_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" nowrap id="detalle1">Cargo : <?php echo $row_cliente['cargo_contacto_c']; ?></td>
      <td colspan="2" id="detalle1">Pa&iacute;s : <?php echo $row_cliente['pais_c']; ?></td>
    </tr>
    <tr>
      <td colspan="2" id="detalle1">Email : <?php echo $row_cliente['email_comercial_c']; ?></td>
      <td colspan="2" id="detalle1">Ciudad : <?php echo $row_cliente['ciudad_c']; ?></td>
    </tr>
    <tr>
      <td colspan="4" id="detalle1">Direcci&oacute;n : <?php echo $row_cliente['direccion_c']; ?></td>            
            
            </table>
               </td>
            </tr>    
<?php
}
else 
{ 
echo "NINGUN REGISTRO SELECCIONADO"; 
}
}
// DATOS REFERENCIA
if ($id_ref_det!='') 
{
$resultreferencia = mysql_query("SELECT cod_ref,tipo_bolsa_ref,material_ref,ancho_ref,largo_ref,calibre_ref FROM Tbl_referencia WHERE id_ref = '$id_ref_det'");
$row_referencia = mysql_fetch_assoc($resultreferencia);
$totalRows_referencia = mysql_num_rows($resultreferencia);
if ($totalRows_referencia> 0)
{ 
$cod_ref=mysql_result($resultreferencia,0,'cod_ref');

mysql_select_db($database_conexion1, $conexion1);
$query_cotiz = "SELECT N_referencia_c,Str_nit,N_precio_vnta, Str_unidad_vta, Str_moneda FROM Tbl_cotiza_materia_p WHERE N_referencia_c='$cod_ref' 
   UNION (SELECT N_referencia_c,Str_nit,N_precio, Str_unidad_vta, Str_moneda FROM Tbl_cotiza_bolsa WHERE N_referencia_c='$cod_ref' ORDER BY fecha_creacion DESC LIMIT 1)
   UNION (SELECT N_referencia_c,Str_nit,N_precio_k, Str_unidad_vta, Str_moneda FROM Tbl_cotiza_laminas WHERE N_referencia_c='$cod_ref' ORDER BY fecha_creacion DESC LIMIT 1) 
   UNION (SELECT N_referencia_c,Str_nit,N_precio_vnta, Str_unidad_vta, Str_moneda FROM Tbl_cotiza_packing WHERE N_referencia_c='$cod_ref' ORDER BY fecha_creacion DESC LIMIT 1)";
$cotiz = mysql_query($query_cotiz, $conexion1) or die(mysql_error());
$row_cotiz = mysql_fetch_assoc($cotiz);
$totalRows_cotiz = mysql_num_rows($row_cotiz);
?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="MM_validateForm('id_ref_det','','R','precio_unid_det','','R','valor_total_det','','R');return document.MM_returnValue" >
<table id="tabla1">
  <tr>
    <td id="dato1"><strong>TIPO : </strong><?php echo $row_referencia['tipo_bolsa_ref'];?></td> 
    <td id="dato1"><strong>MATERIAL : </strong><?php echo $row_referencia['material_ref'];?></td> 
    <td id="dato1"><strong>ANCHO : </strong><?php echo $row_referencia['ancho_ref'];?></td>
    <td id="dato1"><strong>LARGO : </strong><?php echo $row_referencia['largo_ref'];?></td> 
    <td id="dato1"><strong>CALIBRE : </strong><?php echo $row_referencia['calibre_ref'];?></td>      
  </tr>
  <tr>
            <td id="fuente1">MEDIDA</td>
            <td id="fuente1">CANTIDAD</td>
            <td id="fuente1">PRECIO/UND/MILLAR</td>
            <td id="fuente1">TOTAL</td>
          </tr>
          <tr>
            <td id="dato1"><select name="medida_det" id="medida_det">
              <option value="UNIDAD"<?php if (!(strcmp("PRECIO UNITARIO", $row_cotiz['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>UNIDAD</option>
              <option value="MILLAR"<?php if (!(strcmp("PRECIO MILLAR", $row_cotiz['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>MILLAR</option>
              <option value="PAQUETE"<?php if (!(strcmp("PRECIO PAQUETE", $row_cotiz['Str_unidad_vta']))){echo "selected=\"selected\"";} ?>>PAQUETE</option>
              <option value="KILO"<?php if (!(strcmp("KILO", $row_cotiz['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>KILOS</option>
            </select></td>
            <td id="dato1"><input type="number" name="cantidad_det" style="width:80px" min="0" step="0.0001" value="" onBlur="detalle_ce()"></td>
            <td id="dato1"><input type="number" name="precio_unid_det" style="width:80px" min="0.00" step="0.01" value="<?php echo $row_cotiz['N_precio'];echo $row_cotiz['N_precio_k'];echo $row_cotiz['N_precio_vnta'];?>" onBlur="detalle_ce()"></td>
            <td id="dato1"><input type="number" name="valor_total_det" style="width:80px" min="0.00" step="0.01" value="" onBlur="detalle_ce()"></td> 
            <tr>
            <td colspan="4" id="dato1">DESCRIPCION</td>
            </tr>
            <tr>
            <td colspan="4" id="dato1"><textarea type="text" name="descripcion_det" cols="50" rows="2" ><?php echo $row_referencia['tipo_bolsa_ref'],", ",$row_referencia['material_ref'],", " ,$row_referencia['ancho_ref'],"cm x " ,$row_referencia['largo_ref'],"cm , CALIBRE ",$row_referencia['calibre_ref'];?></textarea> </td>
            </tr>       
          </tr>
</table>
</form>
<?php
}
else 
{ 
echo "NINGUN REGISTRO SELECCIONADO"; 
}
}
// DATOS REFERENCIA EDIT
if ($id_ref_det_edit!='') 
{
$resultreferencia = mysql_query("SELECT cod_ref,tipo_bolsa_ref,material_ref,ancho_ref,largo_ref,calibre_ref FROM Tbl_referencia WHERE id_ref = '$id_ref_det_edit'");
$row_referencia = mysql_fetch_assoc($resultreferencia);
$totalRows_referencia = mysql_num_rows($resultreferencia);
if ($totalRows_referencia> 0)
{ 
$cod_ref=mysql_result($resultreferencia,0,'cod_ref');

mysql_select_db($database_conexion1, $conexion1);
$query_cotiz = "SELECT N_referencia_c,Str_nit,N_precio_vnta, Str_unidad_vta, Str_moneda FROM Tbl_cotiza_materia_p WHERE N_referencia_c='$cod_ref' 
   UNION (SELECT N_referencia_c,Str_nit,N_precio, Str_unidad_vta, Str_moneda FROM Tbl_cotiza_bolsa WHERE N_referencia_c='$cod_ref' LIMIT 1)
   UNION (SELECT N_referencia_c,Str_nit,N_precio_k, Str_unidad_vta, Str_moneda FROM Tbl_cotiza_laminas WHERE N_referencia_c='$cod_ref' LIMIT 1) 
   UNION (SELECT N_referencia_c,Str_nit,N_precio_vnta, Str_unidad_vta, Str_moneda FROM Tbl_cotiza_packing WHERE N_referencia_c='$cod_ref' LIMIT 1)";
$cotiz = mysql_query($query_cotiz, $conexion1) or die(mysql_error());
$row_cotiz = mysql_fetch_assoc($cotiz);
$totalRows_cotiz = mysql_num_rows($row_cotiz);
?>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="MM_validateForm('id_ref_det_edit','','R','precio_unid_det','','R','cantidad_det','','R','valor_total_det','','R');return document.MM_returnValue" >
<table id="tabla1">
  <tr>
    <td id="dato1"><strong>TIPO : </strong><?php echo $row_referencia['tipo_bolsa_ref'];?></td> 
    <td id="dato1"><strong>MATERIAL : </strong><?php echo $row_referencia['material_ref'];?></td> 
    <td id="dato1"><strong>ANCHO : </strong><?php echo $row_referencia['ancho_ref'];?></td>
    <td id="dato1"><strong>LARGO : </strong><?php echo $row_referencia['largo_ref'];?></td> 
    <td id="dato1"><strong>CALIBRE : </strong><?php echo $row_referencia['calibre_ref'];?></td>      
  </tr>
  <tr>
            <td id="fuente1">MEDIDA</td>
            <td id="fuente1">PRECIO/UND/MILLAR</td>
            <td id="fuente1">&nbsp;</td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato1"><select name="medida_det" id="medida_det">
              <option value="UNIDAD"<?php if (!(strcmp("PRECIO UNITARIO", $row_cotiz['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>UNIDAD</option>
              <option value="MILLAR"<?php if (!(strcmp("PRECIO MILLAR", $row_cotiz['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>MILLAR</option>
              <option value="PAQUETE"<?php if (!(strcmp("PRECIO PAQUETE", $row_cotiz['Str_unidad_vta']))){echo "selected=\"selected\"";} ?>>PAQUETE</option>
              <option value="KILO"<?php if (!(strcmp("KILO", $row_cotiz['Str_unidad_vta']))) {echo "selected=\"selected\"";} ?>>KILOS</option>
            </select></td>
            <td id="dato1"><input type="number" name="precio_unid_det" style="width:80px" min="0.00" step="0.01" value="<?php echo $row_cotiz['N_precio'];echo $row_cotiz['N_precio_k'];echo $row_cotiz['N_precio_vnta'];?>" onchange="detalle_ce()"></td>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">&nbsp;</td>       
          </tr>
</table>
</form>
<?php
}
else 
{ 
echo "NINGUN REGISTRO SELECCIONADO"; 
}
}
// DATOS O.C
if ($pedido_ce!='') 
{

mysql_select_db($database_conexion1, $conexion1);
$query_ordencompra = "SELECT * FROM Tbl_items_ordenc WHERE Tbl_items_ordenc.str_numero_io = '$pedido_ce' ORDER BY id_items ASC ";
$ordencompra= mysql_query($query_ordencompra, $conexion1) or die(mysql_error());
$row_ordencompra= mysql_fetch_assoc($ordencompra);
$totalRows_ordencompra = mysql_num_rows($ordencompra);
 ?>

          <tr id="tr2">
            <td colspan="4" id="dato2"><table id="tabla1">
              <tr>
                <td id="nivel2">ITEM</td>
                <td id="nivel2">REF. AC</td>
                <td id="nivel2">REF. MP</td>
                <td id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">CANT.</td>
                <td id="nivel2">CANT. RESTANTE</td>
                <td id="nivel2">UNIDADES</td>
                <td id="nivel2">FECHA ENTREGA</td>
                <td id="nivel2">PRECIO / VENTA</td>
                <td id="nivel2">TOTAL ITEM</td>
                <td id="nivel2">MONEDA</td>
                <td nowrap="nowrap"id="nivel2">FACTURADO</td>
                </tr>
              <?php do { ?>
                <tr>
                  <td id="talla2"><?php echo $row_ordencompra['int_consecutivo_io']; ?></td>
                  <td id="talla1"><?php echo $row_ordencompra['int_cod_ref_io']; ?></td>
                  <td id="talla1"><?php $mp=$row_ordencompra['id_mp_vta_io'];

					$sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
					$resultmp= mysql_query($sqlmp);
					$nump= mysql_num_rows($resultmp);
					if($nump >='1')
					{ 
					$nombre_mp = mysql_result($resultmp,0,'str_nombre');
					echo $nombre_mp;
					}else {echo "N.A";}?></td>
                  <td id="talla1"><?php echo $row_ordencompra['int_cod_cliente_io']; ?></td>
                  <td id="talla2"><?php echo $row_ordencompra['int_cantidad_io']; ?></td>
                  <td id="talla2"><?php if($row_ordencompra['int_cantidad_rest_io']==''){echo '0.00';}else{echo $row_ordencompra['int_cantidad_rest_io'];} ?></td>
                  <td id="talla1"><?php echo $row_ordencompra['str_unidad_io']; ?></td>
                  <td id="talla1"><?php echo $row_ordencompra['fecha_entrega_io']; ?></td>
                  <td id="talla1"><?php echo $row_ordencompra['int_precio_io']; ?></td>
                  <td id="talla1"><?php echo $row_ordencompra['int_total_item_io'];$subtotal=$subtotal+$row_ordencompra['int_total_item_io'];?></td>
                  <td id="talla1"><?php echo $row_ordencompra['str_moneda_io']; ?></td>
                  <td nowrap="nowrap"id="talla1"><?php if($row_ordencompra['b_estado_io']=='2'){echo "Facturado";}else{echo "No Facturado";} ?></td>
                </tr>
                <?php } while ($row_ordencompra = mysql_fetch_assoc($ordencompra)); ?>
            </table>
              </td>
            </tr>
<?php

}
exit();
?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>