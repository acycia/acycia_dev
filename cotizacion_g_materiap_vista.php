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
//IMPRIME INFO CLIENTE
$colname_cotizacion_cliente = "-1";
if (isset($_GET['N_cotizacion'])) {
  $colname_cotizacion_cliente = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cotizacion_cliente = sprintf("SELECT * FROM Tbl_cotizaciones, cliente WHERE Tbl_cotizaciones.N_cotizacion  = '%s' AND  Tbl_cotizaciones.Str_nit=cliente.nit_c",$colname_cotizacion_cliente);
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
$query_ver_texto = sprintf("SELECT * FROM Tbl_cotiza_materia_p_obs WHERE Tbl_cotiza_materia_p_obs.N_cotizacion = %s", $colname_ver_texto);
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
$query_ver_nueva = sprintf("SELECT *
FROM Tbl_cotiza_materia_p,cliente 
WHERE
Tbl_cotiza_materia_p.N_cotizacion='%s' and
Tbl_cotiza_materia_p.Str_nit=cliente.nit_c and
Tbl_cotiza_materia_p.B_generica='0'", $colname_ver_nueva);//Tbl_cotiza_materia_p.B_estado<>'2
$ver_nueva = mysql_query($query_ver_nueva, $conexion1) or die(mysql_error());
$num1=mysql_num_rows($ver_nueva);
//existente
$colname_ver_existente = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_existente = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_existente = sprintf("SELECT *
FROM Tbl_cotiza_materia_p,cliente 
WHERE
Tbl_cotiza_materia_p.N_cotizacion='%s' and
Tbl_cotiza_materia_p.Str_nit=cliente.nit_c and
Tbl_cotiza_materia_p.B_generica='1'", $colname_ver_existente);
$ver_existente = mysql_query($query_ver_existente, $conexion1) or die(mysql_error());
$row_existente = mysql_fetch_assoc($ver_existente);
$num2=mysql_num_rows($ver_existente);
//CLIENTE_REFERENCIA
$colname_ver_ref = "1";
if (isset($_GET['N_cotizacion'])) 
{
  $colname_ver_ref = (get_magic_quotes_gpc()) ? $_GET['N_cotizacion'] : addslashes($_GET['N_cotizacion']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_ref = sprintf("SELECT * FROM Tbl_cotiza_materia_p,Tbl_cotizaciones WHERE Tbl_cotizaciones.N_cotizacion=%s AND  Tbl_cotizaciones.N_cotizacion=Tbl_cotiza_materia_p.N_cotizacion", $colname_ver_ref);
$ver_ref = mysql_query($query_ver_ref, $conexion1) or die(mysql_error());
$row_ver_ref = mysql_fetch_assoc($ver_ref);
$ref=mysql_num_rows($ver_ref);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/vista.js"></script>

<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  
<body>
<div align="center">
<table id="tablaexterna">
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
		$fecha1=$row_ver_ref['fecha_creacion'];
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
        <td id="subppal4" width="50%">REGISTRO : 
          <?php $vendedor=$row_ver_ref['Str_usuario'];
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
    <td align="center"><?php if($num1!='0')
{ ?>

<table id="tablainterna" >
        <tr>
          <td width="158" colspan="<?php echo $num1+1; ?>" nowrap id="subppal2"><strong>REFERENCIAS NUEVAS</strong></td>
          </tr>
        <tr>
          <td id="subppal4">REFERENCIA</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php 
		  $var=mysql_result($ver_nueva,$j,Str_referencia);
		  $var2=mysql_result($ver_nueva,$j,N_cotizacion);
		  $var3=mysql_result($ver_nueva,$j,N_referencia_c);
		  $var4=mysql_result($ver_nueva,$j,nit_c);
		 
		  $sql_select="SELECT Str_nombre FROM Tbl_mp_vta WHERE id_mp_vta='$var'";
$result_select= mysql_query($sql_select);
$num_select= mysql_num_rows($result_select);
if($num_select>='1') { 
 $id_nombre=mysql_result($result_select,0,'Str_nombre'); }
		  
		  echo $linc="<a href='cotizacion_general_materia_prima_edit.php?N_cotizacion=$var2&amp;cod_ref=$var3&amp;Str_nit=$var4&amp;tipo=$_GET[tipo]'><strong>".$id_nombre."</strong></a>";?>
                
          </td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CANTIDAD(und)</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
		  <td width="530" id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,N_cantidad); echo $var; ?>		  </td><?php } ?>
        </tr>
        <tr>
          <td id="subppal4">INCOTERMS</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
		  <td id="fuente2">
		  <?php $incot=mysql_result($ver_nueva,$j,Str_incoterms); if($incot!='') {echo $incot;}else{echo "sin incoterm";} ?>	
          </td><?php } ?>
        </tr>
        <tr>
          <td id="subppal4">PRECIO($)</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_nueva,$j,N_precio_vnta); echo $var; ?></td><?php } ?>
        </tr>
        <!--<tr>
          <td id="subppal4">REFERENCIA N&deg; </td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php
		   $var=mysql_result($ver_nueva,$j,N_referencia_c);
		   $var2=mysql_result($ver_nueva,$j,N_cotizacion);
		   $var3=$row_cotizacion_cliente['nit_c'];
		   
		  echo $linc="<a href='cotizacion_general_materia_prima_edit.php?N_cotizacion=$var2&amp;cod_ref=$var&amp;Str_nit=$var3&amp;tipo=$_GET[tipo]'><strong>".$var."</strong></a>";?></td><?php } ?>
        </tr>-->
      <tr>
          <td id="subppal4">UNIDAD DE VENTA</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_nueva,$j,Str_unidad_vta); echo $var; ?></td>
          <?php } ?>
        </tr>
      <tr>
        <td id="subppal4">PLAZO DE PAGO</td>
        <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
        <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,Str_plazo); echo $var; ?></td><?php } ?>
      </tr>
      <tr>
          <td id="subppal4">ARCHIVO ADJUNTO MP</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,Str_linc); if($var!=''){ ?><a href="javascript:verFoto('archivosc/archivos_pdf_mp/<?php echo $var ?>','610','490')" target="_blank"><?php echo $var ?></a><?php }else    echo "N/A";?></td><?php } ?>
        </tr> 
          <tr>
          <td id="subppal4">ESTADO</td>
          <?php  for ($j=0;$j<=$num1-1;$j++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_nueva,$j,B_estado); if($var=='1'){ echo "ACEPTADA"; }else if($var=='0'){echo "PENDIENTE";}else if($var=='2'){echo "RECHAZADA";}else if($var == '3') {echo "OBSOLETA";} ?></td>
          <?php } ?>
        </tr>                      
      </table>
      <?php }?></td>
  </tr>
      <td align="center"><?php if($num2!='0')
{ ?>
      <table id="tablainterna" >
        <tr>
          <td width="158" colspan="<?php echo $num2+1; ?>" nowrap id="subppal2"><strong>REFERENCIAS GENERICAS</strong></td>
          </tr>
        <tr>
          <td id="subppal4">REFERENCIA</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,Str_referencia); 
		  $sql_select="SELECT Str_nombre FROM Tbl_mp_vta WHERE id_mp_vta='$var'";
$result_select= mysql_query($sql_select);
$num_select= mysql_num_rows($result_select);
if($num_select>='1') { 
echo $id_nombre=mysql_result($result_select,0,'Str_nombre'); }
 ?></td>
          <?php } ?>
        </tr>
        <tr>
          <td id="subppal4">CANTIDAD(und)</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
		  <td width="530" id="fuente2">
		  <?php $var=mysql_result($ver_existente,$i,N_cantidad); echo $var; ?>		  </td><?php }?>
        </tr>
        <tr>
          <td id="subppal4">INCOTERMS</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
		  <td id="fuente2">
		  <?php $incotEx=mysql_result($ver_existente,$i,Str_incoterms); if($incotEx!='') {echo $incotEx;}else{echo "sin incoterm";} ?>  </td><?php }?>
        </tr>
        <tr>
          <td id="subppal4">PRECIO($)</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_existente,$i,Str_moneda); echo $var; ?>/<?php $var=mysql_result($ver_existente,$i,N_precio_vnta); echo $var; ?></td><?php }?>
        </tr>
        <!--<tr>
          <td id="subppal4">REFERENCIA N&deg; </td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php
		   $var=mysql_result($ver_existente,$i,N_referencia_c);
		   $var2=mysql_result($ver_existente,$i,N_cotizacion);
		   $var3=$row_cotizacion_cliente['nit_c'];
		   
		  echo $linc="<a href='cotizacion_general_materia-prima_edit.php?N_cotizacion=$var2&amp;cod_ref=$var&amp;Str_nit=$var3&amp;tipo=$_GET[tipo]'><strong>".$var."</strong></a>";?></td><?php }?>
        </tr>-->
      <tr>
          <td id="subppal4">UNIDAD DE VENTA</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2">
		  <?php $var=mysql_result($ver_existente,$i,Str_unidad_vta); echo $var; ?></td><?php }?>
        </tr>
      <tr>
        <td id="subppal4">PLAZO DE PAGO</td>
        <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
        <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,Str_plazo); echo $var; ?></td><?php }?>
      </tr>
      <tr>
          <td id="subppal4">ARCHIVO ADJUNTO MP</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,Str_linc); if($var!=''){ ?><a href="javascript:verFoto('archivosc/archivos_pdf_mp/<?php echo $var ?>','610','490')" target="_blank"><?php echo $var ?></a><?php }else    echo "N/A";?></td><?php }?>
        </tr> 
          <tr>
          <td id="subppal4">ESTADO</td>
          <?php  for ($i=0;$i<=$num2-1;$i++) { ?>
          <td id="fuente2"><?php $var=mysql_result($ver_existente,$i,B_estado); if($var=='1'){ echo "ACEPTADA"; }else if($var=='0'){echo "PENDIENTE";}else if($var=='2'){echo "RECHAZADA";}else if($var == '3') {echo "OBSOLETA";} ?></td>
          <?php } ?>
        </tr>                      
      </table>
      <?php }?></td>
  </tr>
  <tr>
    <td id="justificar"><strong>IMPORTANTE</strong>:  Las cantidades entregadas pueden variar en un 10%. Los calibres un 10% y en la  altura de la bolsa como en el ancho la variaci&oacute;n aceptada es de 5 mm. Las condiciones  comerciales para la elaboraci&oacute;n de este pedido son:<br>
      1. Orden de compra  debidamente aprobada incluyendo en ella este numero de cotizaci&oacute;n comos se&ntilde;al  de aprobaci&oacute;n de nuestros t&eacute;rminos y condiciones.<br>2. Arte aprobado y  firmado.<br>3. El costo de los  artes y cyreles se factura solo por una sola vez. Modificaciones al arte no son  posibles hasta terminar con toda la producci&oacute;n acordada. En caso contrario  cualquier modificaci&oacute;n acarrear&iacute;an nuevo cobro de elaboraci&oacute;n de artes y  Cyreles.<br>4. El precio de  venta hay que adicionarle el IVA correspondiente.<br>Quedamos  pendientes de sus comentarios al respecto y recuerde que el tiempo de  entrega se empieza a contar desde la recepci&oacute;n de la orden de compra y del arte  aprobado debidamente diligenciada por parte de ustedes.     
      </td>
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
    <td id="noprint" align="center"><?php if($_GET['tipo']=='1' || $_GET['tipo']=='2') { ?> <a href="cotizacion_general_materia_prima_edit.php?N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>&Str_nit=<?php echo $row_cotizacion_cliente['Str_nit']; ?>&cod_ref=<?php echo $row_ver_ref['N_referencia_c']; ?>"><img src="images/menos.gif" alt="EDICION"title="EDITAR" border="0" style="cursor:hand;"></a><?php } ?>
      <a href="cotizacion_general_materia_prima_ref.php?N_cotizacion=<?php echo $_GET['N_cotizacion']; ?>&Str_nit=<?php echo $row_cotizacion_cliente['Str_nit']; ?>"><img src="images/mas.gif" alt="ADD A LA COTIZACION"title="ADD A LA COTIZACION" border="0" style="cursor:hand;"/></a><?php if($_GET['tipo']=='1' || $_GET['tipo']=='2') { ?>
      <img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR"title="IMPRIMIR" />      <a href="cotizacion_general_menu.php"></a><?php } ?><a href="cotizacion_copia.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO COTIZACIONES"title="LISTADO COTIZACIONES" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><a href="cotizacion_general_menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR"title="SALIR" onclick="window.close() "/></a></td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($cotizacion_cliente);

mysql_free_result($ver_texto);

mysql_free_result($ver_existente);

mysql_free_result($ver_ref);

?>