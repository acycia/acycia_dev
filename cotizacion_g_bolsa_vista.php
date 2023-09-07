<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

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
$query_cotizacion_cliente = sprintf("SELECT * FROM Tbl_cotizaciones, cliente WHERE Tbl_cotizaciones.N_cotizacion  = '%s' AND  Tbl_cotizaciones.Str_nit=cliente.nit_c", $colname_cotizacion_cliente);
$cotizacion_cliente = mysql_query($query_cotizacion_cliente, $conexion1) or die(mysql_error());
$row_cotizacion_cliente = mysql_fetch_assoc($cotizacion_cliente);
$totalRows_cotizacion_cliente = mysql_num_rows($cotizacion_cliente);
//IMPRIME EL TEXTO DE LA COTIZ
$colname_ver_texto = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_texto = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_texto = sprintf("SELECT * FROM Tbl_cotiza_bolsa_obs WHERE Tbl_cotiza_bolsa_obs.N_cotizacion = %s", $colname_ver_texto);
$ver_texto = mysql_query($query_ver_texto, $conexion1) or die(mysql_error());
$row_ver_texto  = mysql_fetch_assoc($ver_texto);
$totalRows_ver_texto=mysql_num_rows($ver_texto);
//nuevas
$colname_ver_nueva = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_nueva = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nueva = sprintf("SELECT * FROM Tbl_cotiza_bolsa,cliente 
WHERE
Tbl_cotiza_bolsa.N_cotizacion='%s' and
Tbl_cotiza_bolsa.Str_nit=cliente.nit_c and
Tbl_cotiza_bolsa.B_generica='0' ORDER BY Tbl_cotiza_bolsa.fecha_creacion DESC", $colname_ver_nueva);// andTbl_cotiza_bolsa.B_estado<>'2' desaparece las rechazadas de la vista
$ver_nueva = mysql_query($query_ver_nueva, $conexion1) or die(mysql_error());
$num1=mysql_num_rows($ver_nueva);
//existente
$colname_ver_existente = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_existente = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_existente = sprintf("SELECT * FROM Tbl_cotiza_bolsa,Tbl_referencia,cliente WHERE Tbl_cotiza_bolsa.N_cotizacion='%s' AND 
Tbl_cotiza_bolsa.N_referencia_c=Tbl_referencia.cod_ref and
Tbl_cotiza_bolsa.Str_nit=cliente.nit_c and
Tbl_cotiza_bolsa.B_generica='1' ORDER BY Tbl_cotiza_bolsa.fecha_creacion DESC", $colname_ver_existente);
$ver_existente = mysql_query($query_ver_existente, $conexion1) or die(mysql_error());
$num2=mysql_num_rows($ver_existente);
//FECHA Y REGISTRO
$colname_cotizacion_fc = "-1";
if (isset($_GET['N_cotizacion'])) {
  $colname_cotizacion_fc = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion_fc= sprintf("SELECT * FROM Tbl_cotiza_bolsa,Tbl_cotizaciones WHERE Tbl_cotizaciones.N_cotizacion=%s AND  Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_bolsa.N_cotizacion ORDER BY Tbl_cotiza_bolsa.fecha_creacion DESC", $colname_cotizacion_fc,$colname_cotizacion_fc);
$cotizacion_fc = mysql_query($query_cotizacion_fc, $conexion1) or die(mysql_error());
$row_cotizacion_fc = mysql_fetch_assoc($cotizacion_fc);
$totalRows_cotizacion_fc = mysql_num_rows($cotizacion_fc);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>


      <!-- jquery -->
      <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
      <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
      <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
      <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<Script> 
valor = 'none'; 
function Columnas() { 
if (valor) { 
tem_valor = ""; 
Boton.value = "Mostrar la Columna 2"; 
valor = false; 
} else { 
tem_valor = "none"; 
Boton.value = "Ocultar la Columna 2"; 
valor = true; 
} 
C21.style.display = tem_valor ; 
C22.style.display = tem_valor; 

} 
</Script> 
<body>
<div align="center">
<table id="tablaexterna" >
<tr>
<td><table class="panel panel-primary" >
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
        <td id="subppal4" width="50%">FECHA : <?php 
		$fecha1=$row_cotizacion_fc['fecha_creacion'];
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
      <tr>
        <td id="fuente3">&nbsp;</td>
        <td id="fuente3">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center">
          <?php if($num1!='0')//&&$ver_nueva['tipo_bolsa_ref']!="LAMINA"||$ver_nueva['tipo_bolsa_ref']!="PACKING LIST")
{ ?>
      <table class="panel panel-primary" >
        <tr>
          <td width="139" colspan="<?php echo $num1+1; ?>" nowrap  id="subppal2"><strong>REFERENCIAS NUEVAS<img id="Boton" src="images/mas.gif" alt="Mostrar Datos" title="Mostrar Datos" border="0" style="cursor:hand;" Onclick="Columnas();"/> 
            
          </strong></td>
        </tr>
        <tr>
          <td id="subppal4">REFERENCIA N</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td width="549" id="fuente2"><?php
		   $var=mysql_result($ver_nueva,$i,N_referencia_c);
		   $var2=mysql_result($ver_nueva,$i,N_cotizacion);
		   $var3=$row_cotizacion_cliente['nit_c'];
		   
		  echo $linc="<a href='cotizacion_general_bolsas_edit.php?N_cotizacion=$var2&amp;cod_ref=$var&amp;Str_nit=$var3&amp;tipo=$_GET[tipo]'><strong>".$var."</strong></a>";?></td>
          <?php } ?>        
        </tr> 
<!--<tr>
          <td id="subppal4">VERSION</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,version_ref); echo $var; ?>		  </td><?php } ?>
        </tr>-->
<!--<tr>
  <td id="subppal4">TIPO DE BOLSA</td>
  <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
  <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,tipo_bolsa_ref); echo $var; ?></td>
  <?php } ?>
</tr>-->
<tr>
  <td id="subppal4">MATERIAL</td>
  <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
  <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,Str_tipo_coextrusion); echo $var; ?></td>
  <?php } ?>
</tr>               
        <tr>
          <td id="subppal4">ANCHO(cm)  </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,N_ancho); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">ALTO(cm) </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,N_alto); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">FUELLE(cm)</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_fuelle); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CALIBRE</td>
         <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,N_calibre); echo $var; ?></td>
          <?php } ?>
        </tr>
         <tr>
          <td id="subppal4">SOLAPA(cm) 
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?> 
          <?php } ?>
        </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,N_solapa);
        $varsencilla=mysql_result($ver_existente,$j,b_solapa_caract_ref);
         echo $var.' '; 
         switch ($varsencilla) {
             case '0':
                echo '';
               break;
              case '1':
                echo 'Doble';
               break;
              case '2':
                echo 'Sencilla';
               break;
             
             default:
               // code...
               break;
           } ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">BOLSILLO PORTAGUIA(cm) </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_bolsillo); if($var==0){echo "NO";}else{echo "SI";} ?></td>
          <?php } ?>
        </tr>
        
        <tr Id="C21" style="display: none;">
          <td id="subppal4">PESO MILLAR</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,peso_millar_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">IMPRESION</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_impresion); if($var==0){echo "NO";}else{echo "SI";} ?></td>
          <?php } ?>
        </tr> 
        <tr>
          <td id="subppal4">LATERAL</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,Str_sellado_lateral); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">FONDO</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_fondo);if($var==0){ echo "NO";}else {echo "SI";} ?></td>
          <?php } ?>
        </tr> 
        <tr>
          <td id="subppal4">TROQUEL/PRECORTE</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_troquel);if($var==0){ echo "NO";}else {echo "SI";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">TIPO DE ADHESIVO</td>
  <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
  <td id="fuente2"><?php $var1=mysql_result($ver_nueva,$i,B_sellado_seguridad); 
  $var2=mysql_result($ver_nueva,$i,B_sellado_permanente); 
  $var3=mysql_result($ver_nueva,$i,B_sellado_resellable); 
  $var4=mysql_result($ver_nueva,$i,B_sellado_hotm); 
  if($var1==1){echo "CINTA DE SEGURIDAD";}else if($var2==1){echo "CINTA PERMANENTE";}else if($var3==1){echo "CINTA RESELLABLE";}else if($var4==1){echo "HOT MELT";}else{echo "N.A";}?></td>
  <?php } ?>
        </tr>                                  
        <tr>
          <td id="subppal4">NUMERACION</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_numeracion);if($var==0){ echo "NO";}else {echo "SI";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CODIGO DE BARRAS</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_codigo_b); if($var==0){ echo "NO";}else {echo "SI";} ?></td>
          <?php } ?>
        </tr>        
        <!--<tr>
          <td id="subppal4">TIPO DE ADHESIVO</td>
          <?php // for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php //$var=mysql_result($ver_nueva,$i,B_sellado_seguridad);if($var=="1") {echo "SELLADO DE SEGURIDAD";}else {$var=mysql_result($ver_nueva,$i,B_sellado_permanente);if($var=="1") {echo "SELLADO PERMANENTE";}else{$var=mysql_result($ver_nueva,$i,B_sellado_resellable);if($var=="1") {echo "SELLADO RESELLABLE";}else{$var=mysql_result($ver_nueva,$i,B_sellado_hotm);if($var=="1") {echo "SELLADO HOTMEL";}else{echo "NO APLICA";}?></td>
          <?php //} ?>
        </tr>-->
        <tr>
          <td id="subppal4">CANTIDA SOLICITADA</td>
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
          <td id="subppal4">PRECIO DE VENTA + IMPUESTO </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,N_precio_old); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">UNIDAD DE VENTA </td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4"> CYREL</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_cyreles); if($var=='0'){ echo "ACYCIA"; }else if($var=='1'){echo "SE LE FACTURA";}else if($var==''){echo "N.A";} ?></td><?php } ?>
        </tr> 
          <tr>
            <td id="subppal4">PLAZO PAGO</td>
            <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
            <td id="fuente2"><?php $plazon=mysql_result($ver_nueva,$i,Str_plazo); echo $plazon; ?></td><?php } ?>
          </tr>
          <tr>
          <td id="subppal4">ESTADO</td>
          <?php  for ($i=0;$i<=$num1-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$i,B_estado); if($var=='1'){ echo "ACEPTADA"; }else if($var=='0'){echo "PENDIENTE";}else if($var=='2'){echo "RECHAZADA";}else if($var == '3') {echo "OBSOLETA";}  ?></td>
          <?php } ?>
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
          <td width="139" colspan="<?php echo $num2+1; ?>" nowrap  id="subppal2"><strong>REFERENCIAS EXISTENTES <button id="Boton" src="images/mas.gif" alt="Mostrar Datos" title="Mostrar Datos" border="0" style="cursor:hand;" Onclick="Columnas();"> <em>ver peso millar</em></button> <img /></strong></td>
        </tr>
        <tr>
          <td id="subppal4">REFERENCIA N</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td width="549" id="fuente2"><?php
		   $var=mysql_result($ver_existente,$j,N_referencia_c);
		   $var2=mysql_result($ver_existente,$j,N_cotizacion);
		   $var3=$row_cotizacion_cliente['nit_c'];
		   
		  echo $linc="<a href='cotizacion_general_bolsas_edit.php?N_cotizacion=$var2&amp;cod_ref=$var&amp;Str_nit=$var3&amp;tipo=$_GET[tipo]'><strong>".$var."</strong></a>";?></td>
          <?php } ?>        
        </tr> 
<!--<tr>
          <td id="subppal4">VERSION</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,version_ref); echo $var; ?>		  </td><?php } ?>
        </tr>-->
<!--<tr>
  <td id="subppal4">TIPO DE BOLSA</td>
  <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
  <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,tipo_bolsa_ref); echo $var; ?></td>
  <?php } ?>
</tr>-->
<tr>
  <td id="subppal4">MATERIAL</td>
  <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
  <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,Str_tipo_coextrusion); echo $var; ?></td>
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
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_fuelle); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CALIBRE</td>
         <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,calibre_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">SOLAPA(cm)
            <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
            <?php $var=mysql_result($ver_existente,$j,b_solapa_caract_ref); 
             ?> 
            <?php } ?> 
          </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,solapa_ref);
          $varsencilla=mysql_result($ver_existente,$j,b_solapa_caract_ref);
           echo $var.' '; 
           switch ($varsencilla) {
               case '0':
                  echo '';
                 break;
                case '1':
                  echo 'Doble';
                 break;
                case '2':
                  echo 'Sencilla';
                 break;
               
               default:
                 // code...
                 break;
             } 
           ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">BOLSILLO PORTAGUIA(cm) </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,bolsillo_guia_ref); if($var==0){echo "NO";}else{echo "SI";} ?></td>
          <?php } ?>
        </tr>
        
        <tr Id="C21" style="display: none;"> 
          <td id="subppal4">PESO MILLAR</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,peso_millar_ref); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4"> IMPRESION</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_impresion); if($var==0){echo "NO";}else{echo "SI";}?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">LATERAL</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,Str_sellado_lateral); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">FONDO</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_fondo);if($var==0){ echo "NO";}else {echo "SI";} ?></td>
          <?php } ?>
        </tr> 
        <tr>
          <td id="subppal4">TROQUEL/PRECORTE</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_troquel);if($var==0){ echo "NO";}else if($var==1){echo "SI";}else{"NO CONTIENE";} ?></td>
          <?php } ?>
        </tr>          
        <tr>
          <td id="subppal4">CODIGO DE BARRAS</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_codigo_b); if($var==0){ echo "NO";}else {echo "SI";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">NUMERACION</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_numeracion);if($var==0){ echo "NO";}else {echo "SI";} ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">TIPO DE ADHESIVO </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,adhesivo_ref); echo $var; ?>          </td>
          <?php } ?>
        </tr>        
        <!--<tr>
          <td id="subppal4">TIPO DE ADHESIVO</td>
          <?php // for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php //$var=mysql_result($ver_existente,$j,B_sellado_seguridad);if($var=="1") {echo "SELLADO DE SEGURIDAD";}else {$var=mysql_result($ver_existente,$j,B_sellado_permanente);if($var=="1") {echo "SELLADO PERMANENTE";}else{$var=mysql_result($ver_existente,$j,B_sellado_resellable);if($var=="1") {echo "SELLADO RESELLABLE";}else{$var=mysql_result($ver_existente,$j,B_sellado_hotm);if($var=="1") {echo "SELLADO HOTMEL";}else{echo "NO APLICA";}?></td>
          <?php //} ?>
        </tr>-->
        <tr>
          <td id="subppal4">CANTIDA SOLICITADA</td>
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
          <td id="subppal4">PRECIO DE VENTA  + IMPUESTO</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,N_precio_old); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">UNIDAD DE VENTA </td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CYREL</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_cyreles); if($var=='0'){ echo "ACYCIA"; }else if($var=='1'){echo "SE LE FACTURA";}else if($var==''){echo "N.A";}  ?>		  </td><?php } ?>
        </tr>
        <tr>
            <td id="subppal4">PLAZO PAGO</td>
            <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
            <td id="fuente2"><?php $plazoe=mysql_result($ver_existente,$j,Str_plazo); echo $plazoe; ?></td><?php } ?>
          </tr>
          <tr>
          <td id="subppal4">ESTADO</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_estado); if($var=='1'){ echo "ACEPTADA"; }else if($var=='0'){echo "PENDIENTE";}else if($var=='2'){echo "RECHAZADA";}else if($var == '3') {echo "OBSOLETA";} ?></td>
          <?php } ?>
        </tr>                         
        <!--<tr>
          <td id="subppal4">GENERICA</td>
          <?php  for ($j=0;$j<=$num2-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$j,B_generica); if($var=='1'){ echo "SI"; }else if($var=='0'){echo "NO";} ?></td>
          <?php } ?>
        </tr>-->                                                           
      </table>
      <?php }?>
        </tr>                                                           
      </table>
     </td>
  </tr>
  <tr>
    <td id="justificar"><strong>IMPORTANTE</strong>:  Las cantidades entregadas pueden variar en un 10%. Los calibres un 10% y en la  altura de la bolsa como en el ancho la variaci&oacute;n aceptada es de 5 mm. Las condiciones  comerciales para la elaboraci&oacute;n de este pedido son:<br>
      1. Orden de compra  debidamente aprobada incluyendo en ella este numero de cotizaci&oacute;n comos se&ntilde;al  de aprobaci&oacute;n de nuestros t&eacute;rminos y condiciones.<br>2. Arte aprobado y  firmado.<br>3. El costo de los  artes y cyreles se factura solo por una sola vez. Modificaciones al arte no son  posibles hasta terminar con toda la producci&oacute;n acordada. En caso contrario  cualquier modificaci&oacute;n acarrear&iacute;an nuevo cobro de elaboraci&oacute;n de artes y  Cyreles.<br>4. El precio de  venta hay que adicionarle el IVA correspondiente.<br>Quedamos  pendientes de sus comentarios al respecto y recuerde que el tiempo de  entrega se empieza a contar desde la recepci&oacute;n de la orden de compra y del arte  aprobado debidamente diligenciada por parte de ustedes.</td>
  </tr>
  <tr>
    <td id="justificar"><strong>P.D.</strong> Esta oferta es valida por 30 días siempre y cuando no cambien los costos de las materias primas de tal manera que afecten sensiblemente los costos.
            <?php if($totalRows_ver_texto!='0')
           { ?>
            <table id="tablainterna" >
<!--        <tr>
          <td width="54" colspan="<?php echo $totalRows_ver_texto+1; ?>" nowrap  id="justificar"><strong>REF. 
            
          </strong></td>
          <?php  for ($k=0;$k<=$totalRows_ver_texto-1;$k++) { ?>
          <td width="634"><?php  $var=mysql_result($ver_texto,$k,N_referencia_c);  { echo $var; }?></td> <?php } ?>
        </tr>-->
          <tr>
          <td width="139" colspan="<?php echo $totalRows_ver_texto+1; ?>" nowrap  id="justificar"><strong>OBSERVACIONES 
            
          </strong></td>
          </tr>
          <tr>
          <?php  for ($k=0;$k<=$totalRows_ver_texto-1;$k++) { ?>
          <td><?php  $var=mysql_result($ver_texto,$k,texto);  { echo $var; }?>          </td> 
		  <?php } ?>
          </tr>        
          </table>
          <?php }?>
          </td>   
  </tr>  
</table>
</td>
</tr>
</table>
<table id="tablainterna" align="center">
  <tr>
    <td id="noprint" align="center"> 
      <a href="cotizacion_general_bolsas_edit.php?N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>&Str_nit=<?php echo $row_cotizacion_cliente['Str_nit']; ?>&cod_ref=<?php echo $row_cotizacion_fc['N_referencia_c']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR"border="0" /></a>
      <a href="cotizacion_general_bolsa_generica.php?N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>&Str_nit=<?php echo $row_cotizacion_cliente['Str_nit']; ?>"><img src="images/mas.gif" alt="ADD A LA COTIZACION"title="ADD A LA COTIZACION" border="0" style="cursor:hand;"/></a>
     
      <img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="IMPRIMIR"title="IMPRIMIR" />
      <?php if($_SESSION['acceso']) { ?>
      <a href="cotizacion_general_menu.php"></a>
      <?php } ?>
      <a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES" border="0" title="LISTADO COTIZACIONES"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"title="MENU PRINCIPAL"/></a><a href="cotizacion_general_menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR"onclick="window.close() "/></a></td>
  </tr>
</table>
</div>
</body>
</html>

<script>
 $(document).ready(function(){

   var editar =  "<?php echo $_SESSION['no_edita'];?>";
 
   if(editar==0){

     $("input").attr('disabled','disabled');
     $("textarea").attr('disabled','disabled');
     $("select").attr('disabled','disabled'); 

     $('a').each(function() { 
       $(this).attr('href', '#');
     });
              //swal("No Autorizado", "Sin permisos para editar :)", "error"); 
   }
 });
</script> 
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion_cliente);

mysql_free_result($ver_nueva);

mysql_free_result($ver_existente);

//mysql_free_result($cotizacion);
?>