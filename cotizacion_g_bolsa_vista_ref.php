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
//IMPRIME INFORMACION DEL CLIENTE Y DE LA COTIZACION
$colname_cotizacion_cliente = "-1";
if (isset($_GET['Str_nit'])) {
  $colname_cotizacion_cliente = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion_cliente = sprintf("SELECT * FROM Tbl_cotiza_bolsa, cliente WHERE Tbl_cotiza_bolsa.Str_nit = '%s'AND Tbl_cotiza_bolsa.Str_nit = cliente.nit_c ",$colname_cotizacion_cliente);
$cotizacion_cliente = mysql_query($query_cotizacion_cliente, $conexion1) or die(mysql_error());
$row_cotizacion_cliente = mysql_fetch_assoc($cotizacion_cliente);
$totalRows_cotizacion_cliente = mysql_num_rows($cotizacion_cliente);
//IMPRIME EL TEXTO DE LA COTIZ
$colname_ver_nueva = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_nueva = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_texto = sprintf("SELECT * FROM Tbl_cotiza_bolsa,Tbl_cotiza_bolsa_obs WHERE Tbl_cotiza_bolsa_obs.N_cotizacion = %s", $colname_ver_nueva);
$ver_texto = mysql_query($query_ver_texto, $conexion1) or die(mysql_error());
$row_ver_texto  = mysql_fetch_assoc($ver_texto);
$totalRows_ver_texto=mysql_num_rows($ver_texto);
//IMPRIME CAMPOS DESCRIPCION
$colname_ver_existente = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_existente = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
$cod_ref=$_GET['cod_ref'];

mysql_select_db($database_conexion1, $conexion1);
$query_ver_existente = sprintf ("select *
FROM Tbl_cotiza_bolsa
WHERE N_referencia_c='$cod_ref' and N_cotizacion= '%s'",$colname_ver_existente);
$edit_ref_b = mysql_query($query_ver_existente,  $conexion1) or die(mysql_error());
$ver_existente  = mysql_fetch_assoc($edit_ref_b );
$num2  = mysql_num_rows($edit_ref_b);
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
        <td colspan="2"><div id="titulo1">COTIZACION N&deg; <?php echo $_GET['N_cotizacion']; ?></div>
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
        <td id="subppal2" width="50%">FECHA : <?php 
		$fecha1=$row_cotizacion_cliente['fecha_creacion'];
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
        <td id="subppal2" width="50%">REGISTRO : 
          <?php $vendedor=$row_cotizacion_cliente['Str_usuario'];
  if($vendedor!='')
  {
  $sqlvendedor="SELECT * FROM vendedor WHERE id_vendedor ='$vendedor'";
  $resultvendedor= mysql_query($sqlvendedor);
  $numvendedor= mysql_num_rows($resultvendedor);
  if($numvendedor >='1') 
  { 
  $nombre_vendedor = mysql_result($resultvendedor,0,'nombre_vendedor'); 
  echo $nombre_vendedor;
  }
  }
  ?></td>
      </tr>
      <tr>
        <td id="fuente6">CLIENTE : <?php echo $row_cotizacion_cliente['nombre_c']; ?></td>
        <td id="fuente6">NIT : <?php echo $row_cotizacion_cliente['nit_c']; ?></td>
      </tr>
      <tr>
        <td id="fuente6">PAIS / CIUDAD :<?php echo $row_cotizacion_cliente['pais_c']; ?> / <?php echo $row_cotizacion_cliente['ciudad_c']; ?></td>
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
    <td align="center">
          <?php if($num2!='0')
{ ?>
      <table id="tablainterna" >
        <tr>
          <td width="143" colspan="<?php echo $num2+1; ?>" nowrap id="subppal2"><strong>ESPECIFICACIONES </strong></td>
        </tr>              
        <tr>
          <td id="subppal4">ANCHO(cm)  </td>
          <td width="545"  id="fuente2"><?php  echo $ver_existente['N_ancho'];?></td>
        </tr>
        <tr>
          <td id="subppal4">ALTO(cm) </td>
          <td id="fuente2"><?php  echo $ver_existente['N_alto'];?></td>
        </tr>
        <tr>
          <td id="subppal4">FUELLE(cm)</td>
          <td id="fuente2"><?php  echo $ver_existente['B_fuelle'];?></td>
        </tr>
        <tr>
          <td id="subppal4">CALIBRE (micras) </td>
          <td id="fuente2"><?php  echo $ver_existente['N_calibre'];?></td>
        </tr>
        <tr>
          <td id="subppal4">TROQUEL</td>
          <td id="fuente2"><?php  $var= $ver_existente['B_troquel']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
        </tr>
        <tr>
          <td id="subppal4">BOLSILLO PORTAGUIA(cm) </td>
          <td id="fuente2"><?php $var= $ver_existente['B_bolsillo']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
        </tr>
        <tr>
          <td id="subppal4">TAMAÑO BOLSILLO(cm) </td>
          <td id="fuente2"><?php  echo $ver_existente['N_tamano_bolsillo'];?></td>
        </tr>
        <tr>
          <td id="subppal4">SOLAPA</td>
          <td id="fuente2"><?php  echo $ver_existente['N_solapa'];?></td>
        </tr>
        <tr>
          <td id="subppal4">PRECIO($)</td>
          <td id="fuente2"><?php  echo $ver_existente['N_precio'];?></td>
        </tr>
        <tr>
          <td id="subppal4">UNIDAD VENTA</td>
          <td id="fuente2"><?php  echo $ver_existente['Str_unidad_vta'];?></td>
        </tr>
        <tr>
          <td id="subppal4">PLAZO DE PAGO</td>
          <td id="fuente2"><?php  echo $ver_existente['Str_plazo'];?></td>
        </tr>          
        <tr>
          <td id="subppal4">INCOTERMS</td>
          <td id="fuente2"><?php  echo $ver_existente['Str_incoterms'];?></td>
        </tr>
        <tr>
          <td id="subppal4">TIPO/COEXTRUSION </td>
          <td id="fuente2"><?php  echo $ver_existente['Str_tipo_coextrusion'];?></td>
        </tr>
        <tr>
          <td id="subppal4">CAPA EXTERNA COEXTRUSION</td>
          <td id="fuente2"><?php  echo $ver_existente['Str_capa_ext_coext'];?></td>        
        </tr>
        <tr>
          <td id="subppal4">CAPA INTERNA COEXTRUSION</td>
          <td id="fuente2"><?php  echo $ver_existente['Str_capa_inter_coext'];?></td>        
        </tr>          
        <tr>
          <td id="subppal4">CANTIDAD IMPRESION</td>
          <td id="fuente2"><?php  echo $ver_existente['N_cant_impresion'];?></td>              
        </tr>
        <tr>
          <td id="subppal4">COLORES IMPRESION</td>
          <td id="fuente2"><?php  $var= $ver_existente['N_colores_impresion']; echo $var;?></td>          
        </tr>
        <tr>
          <td id="subppal4">CYRELES</td>
          <td id="fuente2"><?php  $var= $ver_existente['B_cyreles']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>    
        </tr> 
        <tr>
          <td id="subppal4">SELLADO SEGURIDAD</td>
          <td id="fuente2"><?php  $var= $ver_existente['B_sellado_seguridad']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
        </tr>
        <tr>
          <td id="subppal4">SELLADO PERMANENTE</td>
          <td id="fuente2"><?php  $var= $ver_existente['B_sellado_permanente']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
        </tr>
        <tr>
          <td id="subppal4">SELLADO RESELLABLE</td>
          <td id="fuente2"><?php  $var= $ver_existente['B_sellado_resellable']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
        </tr>
        <tr>
          <td id="subppal4">SELLADO HOTMELT</td>
          <td id="fuente2"><?php  $var= $ver_existente['B_sellado_hotm']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
        </tr> 
        <tr>
          <td id="subppal4">SELLADO LATERAL</td>
          <td id="fuente2"><?php  echo $ver_existente['Str_sellado_lateral'];  ?></td>          
        </tr>
        <tr>
          <td id="subppal4">FONDO</td>
          <td id="fuente2"><?php  $var= $ver_existente['B_fondo']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td> 
        </tr>  
        <tr>
          <td id="subppal4">CODIGO DE BARRAS</td>
          <td id="fuente2"><?php  $var= $ver_existente['B_codigo_b']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td> 
        </tr>
        <tr>
          <td id="subppal4">NUMERACION</td>
          <td id="fuente2"><?php  $var=$ver_existente['B_numeracion']; if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td> 
        </tr>                                                     
      </table>
      <?php }?></td>
  </tr>
  <tr>
    <td id="justificar"><strong>IMPORTANTE</strong>:  Las cantidades entregadas pueden variar en un 10%. Los calibres un 10% y en la  altura de la bolsa como en el ancho la variaci&oacute;n aceptada es de 5 mm. Las condiciones  comerciales para la elaboraci&oacute;n de este pedido son:<br>
      1. Orden de compra  debidamente aprobada incluyendo en ella este numero de cotizaci&oacute;n comos se&ntilde;al  de aprobaci&oacute;n de nuestros t&eacute;rminos y condiciones.<br>2. Arte aprobado y  firmado.<br>3. El costo de los  artes y cyreles se factura solo por una sola vez. Modificaciones al arte no son  posibles hasta terminar con toda la producci&oacute;n acordada. En caso contrario  cualquier modificaci&oacute;n acarrear&iacute;an nuevo cobro de elaboraci&oacute;n de artes y  Cyreles.<br>4. El precio de  venta hay que adicionarle el IVA correspondiente.<br>Quedamos  pendientes de sus comentarios al respecto y recuerde que el tiempo de  entrega se empieza a contar desde la recepci&oacute;n de la orden de compra y del arte  aprobado debidamente diligenciada por parte de ustedes.</td>
  </tr>
  <tr>
    <td id="justificar"><strong><?php echo $row_ver_texto['texto']; ?></strong></td>
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
    <td id="noprint" align="center"><?php if($_GET['tipo']=='1') { ?><a href="cotizacion_general_bolsas_edit.php?Str_nit=<?php echo $_GET['Str_nit']; ?>&N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR"border="0" /></a><a href="referencia_bolsa_add.php?N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>"><img src="images/rf.gif" alt="REFERENCIA"title="CREAR REFERENCIA"  border="0" /></a><?php } ?><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="IMPRIMIR"title="IMPRIMIR" /><?php if($_GET['tipo']=='1') { ?><a href="cotizacion_general_menu.php"><img src="images/mas.gif" alt="ADD COTIZACION"title="ADD COTIZACION" border="0" style="cursor:hand;"/></a><?php } ?><a href="comercial.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMERCIAL" border="0" title="COMERCIAL"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"title="MENU PRINCIPAL"/></a><a href="cotizacion_general_menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR"onclick="window.close() "/></a></td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion_cliente);

mysql_free_result($ver_texto);

mysql_free_result($edit_ref_b);

//mysql_free_result($ver_ref);

//mysql_free_result($cotizacion);
?>