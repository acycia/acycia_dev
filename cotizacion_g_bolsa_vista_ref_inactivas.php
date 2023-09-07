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
if (isset($_GET['N_cotizacion'])) {
  $colname_cotizacion_cliente = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion_cliente = sprintf("SELECT * FROM Tbl_referencia,Tbl_cotiza_bolsa, cliente WHERE Tbl_referencia.n_cotiz_ref = '%s' AND Tbl_referencia.n_cotiz_ref=Tbl_cotiza_bolsa.N_cotizacion and   Tbl_cotiza_bolsa.Str_nit=cliente.nit_c", $colname_cotizacion_cliente);
$cotizacion_cliente = mysql_query($query_cotizacion_cliente, $conexion1) or die(mysql_error());
$row_cotizacion_cliente = mysql_fetch_assoc($cotizacion_cliente);
$totalRows_cotizacion_cliente = mysql_num_rows($cotizacion_cliente);

$colname_ver_nueva = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_nueva = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nueva = sprintf("SELECT DISTINCT 
Tbl_cliente_referencia.N_referencia,Tbl_cliente_referencia.N_cotizacion,
Tbl_referencia.id_ref, 
Tbl_referencia.n_cotiz_ref,
Tbl_referencia.cod_ref,
Tbl_referencia.version_ref,
Tbl_referencia.n_egp_ref,
Tbl_referencia.tipo_bolsa_ref,
Tbl_referencia.material_ref,
Tbl_referencia.ancho_ref,
Tbl_referencia.N_repeticion_l,
Tbl_referencia.N_diametro_max_l,
Tbl_referencia.N_peso_max_l,
Tbl_referencia.N_cantidad_metros_r_l,
Tbl_referencia.N_embobinado_l,
Tbl_referencia.Str_referencia_m,
Tbl_referencia.Str_linc_m,
Tbl_referencia.largo_ref,
Tbl_referencia.solapa_ref,
Tbl_referencia.bolsillo_guia_ref,
Tbl_referencia.calibre_ref,
Tbl_referencia.peso_millar_ref,
Tbl_referencia.Str_boca_entr_p,
Tbl_referencia.Str_entrada_p,
Tbl_referencia.Str_lamina1_p,
Tbl_referencia.Str_lamina2_p,
Tbl_referencia.B_troquel,
Tbl_referencia.N_fuelle,
Tbl_referencia.B_fondo,
Tbl_referencia.impresion_ref,
Tbl_referencia.num_pos_ref,
Tbl_referencia.cod_form_ref,
Tbl_referencia.adhesivo_ref,
Tbl_referencia.estado_ref,
Tbl_referencia.registro1_ref,
Tbl_referencia.fecha_registro1_ref,
Tbl_referencia.registro2_ref,
Tbl_referencia.fecha_registro2_ref,
Tbl_referencia.B_generica,
Tbl_cotiza_bolsa.N_cotizacion,
Tbl_cotiza_bolsa.N_referencia_c,
Tbl_cotiza_bolsa.Str_nit,
Tbl_cotiza_bolsa.N_ancho,
Tbl_cotiza_bolsa.N_alto,	
Tbl_cotiza_bolsa.B_fuelle,	
Tbl_cotiza_bolsa.N_calibre,	
Tbl_cotiza_bolsa.B_troquel,	
Tbl_cotiza_bolsa.B_bolsillo,
Tbl_cotiza_bolsa.N_tamano_bolsillo,	
Tbl_cotiza_bolsa.N_solapa,	
Tbl_cotiza_bolsa.Str_moneda,	
Tbl_cotiza_bolsa.N_precio,
Tbl_cotiza_bolsa.Str_unidad_vta,	
Tbl_cotiza_bolsa.Str_plazo,	
Tbl_cotiza_bolsa.Str_incoterms,	
Tbl_cotiza_bolsa.Str_tipo_coextrusion,	
Tbl_cotiza_bolsa.Str_capa_ext_coext,	
Tbl_cotiza_bolsa.Str_capa_inter_coext,	
Tbl_cotiza_bolsa.N_cant_impresion,	
Tbl_cotiza_bolsa.B_impresion,	
Tbl_cotiza_bolsa.N_colores_impresion,	
Tbl_cotiza_bolsa.B_cyreles,	
Tbl_cotiza_bolsa.B_sellado_seguridad,	
Tbl_cotiza_bolsa.B_sellado_permanente,	
Tbl_cotiza_bolsa.B_sellado_resellable,	
Tbl_cotiza_bolsa.B_sellado_hotm,	
Tbl_cotiza_bolsa.Str_sellado_lateral,	
Tbl_cotiza_bolsa.B_fondo	B_codigo_b,	
Tbl_cotiza_bolsa.B_numeracion,	
Tbl_cotiza_bolsa.fecha_creacion,	
Tbl_cotiza_bolsa.Str_usuario,	
Tbl_cotiza_bolsa.N_comision,	
Tbl_cotiza_bolsa.B_estado,
Tbl_cotizaciones.N_cotizacion,
Tbl_cotizaciones.Str_nit,
Tbl_cotizaciones.Str_tipo
FROM Tbl_cliente_referencia
LEFT  JOIN Tbl_cotiza_bolsa ON Tbl_cliente_referencia.N_cotizacion =
Tbl_cotiza_bolsa.N_cotizacion  and
Tbl_cliente_referencia.N_referencia = Tbl_cotiza_bolsa.N_referencia_c


LEFT JOIN Tbl_referencia ON 
Tbl_referencia.cod_ref = Tbl_cliente_referencia.N_referencia



INNER join Tbl_cotizaciones ON   Tbl_cotiza_bolsa.N_cotizacion  =
Tbl_cotizaciones.N_cotizacion and
Tbl_cotizaciones.Str_tipo='BOLSA' 

WHERE Tbl_cliente_referencia.N_cotizacion=  '%s' and  
Tbl_referencia.estado_ref='0' and 
Tbl_referencia.B_generica='0'", $colname_ver_nueva);
$ver_nueva = mysql_query($query_ver_nueva, $conexion1) or die(mysql_error());
$num1=mysql_num_rows($ver_nueva);

$colname_ver_existente = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_existente = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_existente = sprintf("SELECT DISTINCT 
Tbl_cliente_referencia.N_referencia,Tbl_cliente_referencia.N_cotizacion,
Tbl_referencia.id_ref, 
Tbl_referencia.n_cotiz_ref,
Tbl_referencia.cod_ref,
Tbl_referencia.version_ref,
Tbl_referencia.n_egp_ref,
Tbl_referencia.tipo_bolsa_ref,
Tbl_referencia.material_ref,
Tbl_referencia.ancho_ref,
Tbl_referencia.N_repeticion_l,
Tbl_referencia.N_diametro_max_l,
Tbl_referencia.N_peso_max_l,
Tbl_referencia.N_cantidad_metros_r_l,
Tbl_referencia.N_embobinado_l,
Tbl_referencia.Str_referencia_m,
Tbl_referencia.Str_linc_m,
Tbl_referencia.largo_ref,
Tbl_referencia.solapa_ref,
Tbl_referencia.bolsillo_guia_ref,
Tbl_referencia.calibre_ref,
Tbl_referencia.peso_millar_ref,
Tbl_referencia.Str_boca_entr_p,
Tbl_referencia.Str_entrada_p,
Tbl_referencia.Str_lamina1_p,
Tbl_referencia.Str_lamina2_p,
Tbl_referencia.B_troquel,
Tbl_referencia.N_fuelle,
Tbl_referencia.B_fondo,
Tbl_referencia.impresion_ref,
Tbl_referencia.num_pos_ref,
Tbl_referencia.cod_form_ref,
Tbl_referencia.adhesivo_ref,
Tbl_referencia.estado_ref,
Tbl_referencia.registro1_ref,
Tbl_referencia.fecha_registro1_ref,
Tbl_referencia.registro2_ref,
Tbl_referencia.fecha_registro2_ref,
Tbl_referencia.B_generica,
Tbl_cotiza_bolsa.N_cotizacion,
Tbl_cotiza_bolsa.N_referencia_c,
Tbl_cotiza_bolsa.Str_nit,
Tbl_cotiza_bolsa.N_ancho,
Tbl_cotiza_bolsa.N_alto,	
Tbl_cotiza_bolsa.B_fuelle,	
Tbl_cotiza_bolsa.N_calibre,	
Tbl_cotiza_bolsa.B_troquel,	
Tbl_cotiza_bolsa.B_bolsillo,
Tbl_cotiza_bolsa.N_tamano_bolsillo,	
Tbl_cotiza_bolsa.N_solapa,	
Tbl_cotiza_bolsa.Str_moneda,	
Tbl_cotiza_bolsa.N_precio,
Tbl_cotiza_bolsa.Str_unidad_vta,	
Tbl_cotiza_bolsa.Str_plazo,	
Tbl_cotiza_bolsa.Str_incoterms,	
Tbl_cotiza_bolsa.Str_tipo_coextrusion,	
Tbl_cotiza_bolsa.Str_capa_ext_coext,	
Tbl_cotiza_bolsa.Str_capa_inter_coext,	
Tbl_cotiza_bolsa.N_cant_impresion,	
Tbl_cotiza_bolsa.B_impresion,	
Tbl_cotiza_bolsa.N_colores_impresion,	
Tbl_cotiza_bolsa.B_cyreles,	
Tbl_cotiza_bolsa.B_sellado_seguridad,	
Tbl_cotiza_bolsa.B_sellado_permanente,	
Tbl_cotiza_bolsa.B_sellado_resellable,	
Tbl_cotiza_bolsa.B_sellado_hotm,	
Tbl_cotiza_bolsa.Str_sellado_lateral,	
Tbl_cotiza_bolsa.B_fondo	B_codigo_b,	
Tbl_cotiza_bolsa.B_numeracion,	
Tbl_cotiza_bolsa.fecha_creacion,	
Tbl_cotiza_bolsa.Str_usuario,	
Tbl_cotiza_bolsa.N_comision,	
Tbl_cotiza_bolsa.B_estado,
Tbl_cotizaciones.N_cotizacion,
Tbl_cotizaciones.Str_nit,
Tbl_cotizaciones.Str_tipo
FROM Tbl_cliente_referencia
LEFT  JOIN Tbl_cotiza_bolsa ON Tbl_cliente_referencia.N_cotizacion =
Tbl_cotiza_bolsa.N_cotizacion  and
Tbl_cliente_referencia.N_referencia = Tbl_cotiza_bolsa.N_referencia_c


LEFT JOIN Tbl_referencia ON 
Tbl_referencia.cod_ref = Tbl_cliente_referencia.N_referencia



INNER join Tbl_cotizaciones ON   Tbl_cotiza_bolsa.N_cotizacion  =
Tbl_cotizaciones.N_cotizacion and
Tbl_cotizaciones.Str_tipo='BOLSA' 

WHERE Tbl_cliente_referencia.N_cotizacion=  '%s' and 

Tbl_referencia.estado_ref='0'and 
Tbl_referencia.B_generica='1'", $colname_ver_existente);
$ver_existente = mysql_query($query_ver_existente, $conexion1) or die(mysql_error());
$num2=mysql_num_rows($ver_existente);
//FECHA Y REGISTRO
$colname_cotizacion_fc = "-1";
if (isset($_GET['N_cotizacion'])) {
  $colname_cotizacion_fc = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion_fc= sprintf("SELECT * FROM Tbl_cotiza_bolsa,Tbl_cotizaciones WHERE  Tbl_cotiza_bolsa.N_cotizacion=%s AND  Tbl_cotiza_bolsa.N_cotizacion=Tbl_cotizaciones.N_cotizacion ", $colname_cotizacion_fc,$colname_cotizacion_fc);
$cotizacion_fc = mysql_query($query_cotizacion_fc, $conexion1) or die(mysql_error());
$row_cotizacion_fc = mysql_fetch_assoc($cotizacion_fc);
$totalRows_cotizacion_fc = mysql_num_rows($cotizacion_fc);
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
        <td colspan="2"><div id="titulo1">COTIZACION N&deg; <?php echo $row_cotizacion_cliente['N_cotizacion']; ?></div>
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
        <td id="subppal4" width="50%">FECHA : <?php 
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
        <td id="subppal4" width="50%">REGISTRO : <?php $vendedor=$row_cotizacion_fc['Str_usuario'];
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
    <td align="center">
          <?php if($num1!='0')//&&$ver_nueva['tipo_bolsa_ref']!="LAMINA"||$ver_nueva['tipo_bolsa_ref']!="PACKING LIST")
{ ?>
      <table id="tablainterna" >
        <tr>
          <td width="139" colspan="<?php echo $num1+1; ?>" nowrap  id="subppal2"><strong>REFERENCIAS NUEVAS 
            
          </strong></td>
        </tr>
        <tr>
          <td id="subppal4">REFERENCIA N° </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td width="549" id="fuente2"><?php
		   $var=mysql_result($ver_nueva,$i,cod_ref);
		   $var2=mysql_result($ver_nueva,$i,n_cotiz_ref);
		   $var3=$row_cotizacion_cliente['nit_c'];
		   
		  echo $linc="<a href='referencia_bolsa_vista.php?cod_ref=$var&amp;Str_nit=$var3&amp;n_cotiz=$var2&amp;tipo=$row_usuario[tipo_usuario]'><strong>".$var."</strong></a>";?></td>
          <?php } ?>        
        </tr> 
<tr>
          <td id="subppal4">VERSION</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,version_ref); echo $var; ?>		  </td><?php } ?>
        </tr>
<tr>
  <td id="subppal4">TIPO DE BOLSA</td>
  <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
  <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,tipo_bolsa_ref); echo $var; ?></td>
  <?php } ?>
</tr>
<tr>
  <td id="subppal4">MATERIAL</td>
  <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
  <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,material_ref); echo $var; ?></td>
  <?php } ?>
</tr>               
        <tr>
          <td id="subppal4">ANCHO(cm)  </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,ancho_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">ALTO(cm) </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,largo_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">FUELLE(cm)</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,N_fuelle); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CALIBRE (micras) </td>
         <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,calibre_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
<tr>
          <td id="subppal4">SOLAPA</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,solapa_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">BOLSILLO PORTAGUIA(cm) </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,bolsillo_guia_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        
        <tr>
          <td id="subppal4">PESO MILLAR</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,peso_millar_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">COLORES IMPRESION</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,impresion_ref); echo $var." COLORES"; ?></td>
          <?php } ?>
        </tr>  
        <tr>
          <td id="subppal4">CODIGO DE BARRAS</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,cod_form_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">NUMERACION</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,num_pos_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">TIPO DE ADHESIVO</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,adhesivo_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CANTIDA MINIMA </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,N_cant_impresion); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">INCOTERMS</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,Str_incoterms); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">MONEDA DE NEGOCIACION </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,Str_moneda); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">PRECIO DE VENTA </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,N_precio); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">UNIDAD DE VENTA </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">COSTO CYREL</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_cyreles); if($var=='1'){ echo "ACYCIA"; }else if($var=='0'){echo "CLIENTE";}else if($var=='2'){echo "N.A";} ?>		  </td><?php } ?>
        </tr>                 
        <!--<tr>
          <td id="subppal4">GENERICA</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_generica); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>-->                                                           
      </table>
      <?php }?>
          <?php if($num2!='0')//&&$ver_nueva['tipo_bolsa_ref']!="LAMINA"||$ver_nueva['tipo_bolsa_ref']!="PACKING LIST")
{ ?>
      <table id="tablainterna" >
        <tr>
          <td width="139" colspan="<?php echo $num2+1; ?>" nowrap  id="subppal2"><strong>REFERENCIAS GENERICAS 
            
          </strong></td>
        </tr>
        <tr>
          <td id="subppal4">REFERENCIA N° </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td width="549" id="fuente2"><?php
		   $var=mysql_result($ver_existente,$j,cod_ref);
		   $var2=mysql_result($ver_existente,$j,n_cotiz_ref);
		   $var3=$row_cotizacion_cliente['nit_c'];
		   
		  echo $linc="<a href='referencia_bolsa_vista.php?cod_ref=$var&amp;Str_nit=$var3&amp;n_cotiz=$var2&amp;tipo=$row_usuario[tipo_usuario]'><strong>".$var."</strong></a>";?></td>
          <?php } ?>        
        </tr> 
<tr>
          <td id="subppal4">VERSION</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,version_ref); echo $var; ?>		  </td><?php } ?>
        </tr>
<tr>
  <td id="subppal4">TIPO DE BOLSA</td>
  <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
  <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,tipo_bolsa_ref); echo $var; ?></td>
  <?php } ?>
</tr>
<tr>
  <td id="subppal4">MATERIAL</td>
  <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
  <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,material_ref); echo $var; ?></td>
  <?php } ?>
</tr>               
        <tr>
          <td id="subppal4">ANCHO(cm)  </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,ancho_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">ALTO(cm) </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,largo_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">FUELLE(cm)</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,N_fuelle); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CALIBRE (micras) </td>
         <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,calibre_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
<tr>
          <td id="subppal4">SOLAPA</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,solapa_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">BOLSILLO PORTAGUIA(cm) </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,bolsillo_guia_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        
        <tr>
          <td id="subppal4">PESO MILLAR</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,peso_millar_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">COLORES IMPRESION</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,impresion_ref); echo $var." COLORES"; ?></td>
          <?php } ?>
        </tr>  
        <tr>
          <td id="subppal4">CODIGO DE BARRAS</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,cod_form_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">NUMERACION</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,num_pos_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">TIPO DE ADHESIVO</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,adhesivo_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CANTIDA MINIMA </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,N_cant_impresion); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">INCOTERMS</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,Str_incoterms); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">MONEDA DE NEGOCIACION </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,Str_moneda); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">PRECIO DE VENTA </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,N_precio); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">UNIDAD DE VENTA </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">COSTO CYREL</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_cyreles); if($var=='1'){ echo "ACYCIA"; }else if($var=='0'){echo "CLIENTE";}else if($var=='2'){echo "N.A";} ?>		  </td><?php } ?>
        </tr>                 
        <!--<tr>
          <td id="subppal4">GENERICA</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_generica); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>-->                                                           
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
    <td id="noprint" align="center"><?php if($_GET['tipo']=='1') { ?>
      <a href="cotizacion_general_bolsas_edit.php?Str_nit=<?php echo $_GET['Str_nit']; ?>&N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR"border="0" /></a><a href="referencia_bolsa_add.php?N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>&Str_nit=<?php echo $_GET['Str_nit']; ?>&cod_refe=<?php echo $row_cotizacion_fc['N_referencia_c']; ?>"><img src="images/rf.gif" alt="REFERENCIA" title="CREAR REFERENCIA"  border="0" /></a>
      <?php } ?>
      <img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="IMPRIMIR"title="IMPRIMIR" />
      <?php if($_GET['tipo']=='1') { ?>
      <a href="cotizacion_general_menu.php"><img src="images/mas.gif" alt="ADD COTIZACION"title="ADD COTIZACION" border="0" style="cursor:hand;"/></a>
      <?php } ?>
      <a href="comercial.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMERCIAL" border="0" title="COMERCIAL"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"title="MENU PRINCIPAL"/></a><a href="cotizacion_general_menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR"onclick="window.close() "/></a></td>
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

//mysql_free_result($cotizacion);
?>