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

$colname_cotizacion_cliente = "-1";
if (isset($_GET['n_cotiz'])) {
  $colname_cotizacion_cliente = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion_cliente = sprintf("SELECT * FROM cotizacion, cliente WHERE cotizacion.n_cotiz = '%s' AND cotizacion.id_c_cotiz = cliente.id_c", $colname_cotizacion_cliente);
$cotizacion_cliente = mysql_query($query_cotizacion_cliente, $conexion1) or die(mysql_error());
$row_cotizacion_cliente = mysql_fetch_assoc($cotizacion_cliente);
$totalRows_cotizacion_cliente = mysql_num_rows($cotizacion_cliente);

$colname_ver_nueva = "1";
if (isset($_GET['n_cotiz'])) 
{
  $colname_ver_nueva = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}

mysql_select_db($database_conexion1, $conexion1);
$query_ver_nueva = sprintf("SELECT * FROM cotizacion_nueva WHERE n_cotiz_cn = %s", $colname_ver_nueva);
$ver_nueva = mysql_query($query_ver_nueva, $conexion1) or die(mysql_error());
$num1=mysql_num_rows($ver_nueva);

$colname_ver_existente = "1";
if (isset($_GET['n_cotiz'])) 
{
  $colname_ver_existente = (get_magic_quotes_gpc()) ? $_GET['n_cotiz'] : addslashes($_GET['n_cotiz']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_existente = sprintf("SELECT * FROM cotizacion_existente,referencia WHERE cotizacion_existente.n_cotiz_ce=%s and cotizacion_existente.id_ref_ce=referencia.id_ref", $colname_ver_existente);
$ver_existente = mysql_query($query_ver_existente, $conexion1) or die(mysql_error());
$num2=mysql_num_rows($ver_existente);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<body>
<div align="center">
<table id="tablaexterna">
<tr>
<td><table id="tablainterna">
  <tr>
    <td><table id="tablainterna">
      <tr>
        <td rowspan="2" id="fondo" width="30%"><img src="images/logoacyc.jpg"></td>
        <td colspan="2"><div id="titulo1">COTIZACION N&deg; <?php echo $row_cotizacion_cliente['n_cotiz']; ?></div>
            <div id="fondo">ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6</strong><br>
              Carrera 45 No. 14 - 15  Tel: 311-21-44 Fax: 2664123  Medellin-Colombia<br>
              Emal: alvarocadavid@acycia.com</div></td>
      </tr>
      <tr>
        <td id="fondo_2">CODIGO : R1 - F03</td>
        <td id="fondo_2">VERSION : 0</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table id="tablainterna">
      <tr>
        <td id="fuente4" width="50%">FECHA : <?php 
		$fecha1=$row_cotizacion_cliente['fecha_cotiz'];
        $dia1=substr($fecha1,8,2);
		$mes1=substr($fecha1,5,2);
        $ano1=substr($fecha1,0,4);
		if($mes1=='01')
		{
		  echo "Enero"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='02')
		{
		  echo "Febrero"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='03')
		{
		  echo "Marzo"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='04')
		{
		  echo "Abril"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='05')
		{
		  echo "Mayo"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='06')
		{
		  echo "Junio"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='07')
		{
		  echo "Julio"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='08')
		{
		  echo "Agosto"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='09')
		{
		  echo "Septiembre"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='10')
		{
		  echo "Octubre"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='11')
		{
		  echo "Noviembre"."  ".$dia1."  "."de"."  ".$ano1;
		}
		if($mes1=='12')
		{
		  echo "Diciembre"."  ".$dia1."  "."de"."  ".$ano1;
		}
		?></td>
        <td id="fuente4" width="50%">REGISTRO : <?php echo $row_cotizacion_cliente['responsable_cotiz']; ?> </td>
      </tr>
      <tr>
        <td id="fuente6">CLIENTE : <?php echo $row_cotizacion_cliente['nombre_c']; ?></td>
        <td id="fuente6">NIT : <?php echo $row_cotizacion_cliente['nit_c']; ?></td>
      </tr>
      <tr>
        <td id="fuente6">PAIS / CIUDAD : <?php echo $row_cotizacion_cliente['pais_c']; ?> / <?php echo $row_cotizacion_cliente['ciudad_c']; ?></td>
        <td id="fuente6">TELEFONO : <?php echo $row_cotizacion_cliente['telefono_c']; ?></td>
      </tr>
      <tr>
        <td id="fuente6">EMAIL : <?php echo $row_cotizacion_cliente['email_comercial_c']; ?></td>
        <td id="fuente6">FAX : <?php echo $row_cotizacion_cliente['fax_c']; ?></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente6">DIRECCION : <?php echo $row_cotizacion_cliente['direccion_c']; ?></td>
        </tr>
      <tr>
        <td id="fuente6">CONTACTO COMERCIAL : <?php echo $row_cotizacion_cliente['contacto_c']; ?></td>
        <td id="fuente6">CARGO : <?php echo $row_cotizacion_cliente['cargo_contacto_c']; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"><?php if($num1!='0')
{ ?>
      <table id="tabladetalle" align="center">
        <tr>
          <td colspan="<?php echo $num1+1; ?>" nowrap id="fuente4"><strong>REFERENCIA NUEVA</strong></td>
          </tr>
        <tr>
          <td id="fuente7">REFERENCIA</td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,cod_ref_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TIPO DE BOLSA </td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,tipo_bolsa_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">MATERIAL</td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
		  <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,material_cn); echo $var; ?>		  </td> <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">ANCHO (cm) </td>
		  <?php for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,ancho_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">LARGO (cm) </td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,largo_cn); echo $var; ?>		  </td><?php } ?>
        </tr> 
        <tr>
          <td id="fuente7">SOLAPA (cm) </td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,solapa_cn);	echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">BOLSILLO PORTAGUIA (cm) </td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,bolsillo_guia_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CALIBRE (micras) </td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,calibre_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PESO MILLAR </td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
		  <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,peso_millar_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">IMPRESION</td>
		  <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,impresion_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">NUMERACION &amp; POSICION</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,num_pos_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CODIGO DE BARRAS </td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,barra_formato_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TIPO DE ADHESIVO </td>
         <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,adhesivo_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDA MINIMA </td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,cant_min_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TIEMPO DE ENTREGA </td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,tiempo_entrega_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">INCOTERM</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,incoterm_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO DE VENTA </td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,precio_venta_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">MONEDA DE NEGOCIACION </td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,moneda_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">UNIDAD DE VENTA </td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,unidad_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">FORMA DE PAGO </td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,forma_pago_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">ENTREGA</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,entrega_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="fuente7">COSTO CIREL</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,costo_cirel_cn); echo $var; ?>		  </td><?php } ?>
        </tr>
      </table>
      <?php }?>
      <?php if($num2!='0')
{ ?>
      <table id="tabladetalle" align="center">
        <tr>
          <td colspan="<?php echo $num2+1; ?>" nowrap id="fuente4"><strong>REFERENCIA EXISTENTE </strong></td>
        </tr>
        <tr>
          <td id="fuente7">REFERENCIA</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,cod_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TIPO DE BOLSA </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,tipo_bolsa_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">MATERIAL</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,material_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">ANCHO</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,ancho_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">LARGO</td>
         <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,largo_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">SOLAPA</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,solapa_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">BOLSILLO PORTAGUIA </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,bolsillo_guia_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CALIBRE</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,calibre_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PESO MILLAR </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,peso_millar_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">IMPRESION</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,impresion_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">NUMERACION &amp; POSICION</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,num_pos_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CODIGO DE BARRAS </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,cod_form_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TIPO DE ADHESIVO </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,adhesivo_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">CANTIDA MINIMA </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,cant_min_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">TIEMPO DE ENTREGA </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,tiempo_entrega_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">INCOTERM</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,incoterm_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">PRECIO DE VENTA </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,precio_venta_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">MONEDA DE NEGOCIACION </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,moneda_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">UNIDAD DE VENTA </td>
         <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,unidad_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">FORMA DE PAGO </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,forma_pago_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">ENTREGA</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,entrega_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="fuente7">COSTO CIREL</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,costo_cirel_ce); echo $var; ?>          </td>
          <?php } ?>
        </tr>
      </table>
      <?php }?></td>
  </tr>
  <tr>
    <td id="justificar"><strong>IMPORTANTE</strong>:  Las cantidades entregadas pueden variar en un 10%. Los calibres un 10% y en la  altura de la bolsa como en el ancho la variaci&oacute;n aceptada es de 5 mm. Las condiciones  comerciales para la elaboraci&oacute;n de este pedido son:<br>
      1. Orden de compra  debidamente aprobada incluyendo en ella este numero de cotizaci&oacute;n comos se&ntilde;al  de aprobaci&oacute;n de nuestros t&eacute;rminos y condiciones.<br>2. Arte aprobado y  firmado.<br>3. El costo de los  artes y cyreles se factura solo por una sola vez. Modificaciones al arte no son  posibles hasta terminar con toda la producci&oacute;n acordada. En caso contrario  cualquier modificaci&oacute;n acarrear&iacute;an nuevo cobro de elaboraci&oacute;n de artes y  Cyreles.<br>4. El precio de  venta hay que adicionarle el IVA correspondiente.<br>Quedamos  pendientes de sus comentarios al respecto y recuerde que el tiempo de  entrega se empieza a contar desde la recepci&oacute;n de la orden de compra y del arte  aprobado debidamente diligenciada por parte de ustedes.</td>
  </tr>
  <tr>
    <td id="justificar"><strong><?php echo $row_cotizacion_cliente['observacion_cotiz']; ?></strong></td>
  </tr>
  <tr>
    <td id="justificar"><strong>P.D.</strong> Esta oferta es valida por 30 días siempre y cuando no cambien los costos de las materias primas de tal manera que afecten sensiblemente los costos.</td>
  </tr>
</table>
</td>
</tr>
</table>
<table id="tablainterna" align="center">
  <tr>
    <td id="noprint" align="center"><?php if($_GET['tipo']=='1') { ?> <a href="cotizacion_bolsa_edit.php?n_cotiz=<?php echo $row_cotizacion_cliente['n_cotiz']; ?>&id_c_cotiz=<?php echo $row_cotizacion_cliente['id_c_cotiz']; ?>"><img src="images/menos.gif" alt="EDICION" border="0" style="cursor:hand;"></a><?php } ?><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="IMPRIMIR" /><?php if($_GET['tipo']=='1') { ?><a href="cotizacion_bolsa_add.php"><img src="images/mas.gif" alt="ADD COTIZACION" border="0" style="cursor:hand;"/></a><?php } ?><a href="cotizacion_bolsa.php"><img src="images/cat.gif" border="0" style="cursor:hand;" alt="COTIZACIONES"></a><a href="comercial.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMERCIAL" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onclick="window.close() "/></td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion_cliente);

mysql_free_result($ver_nueva);

mysql_free_result($ver_existente);
?>