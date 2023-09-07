<?php require_once('Connections/conexion1.php'); ?>
<?php
$colname_cliente = "-1";
if (isset($_GET['id_c'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['id_c'] : addslashes($_GET['id_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE id_c = %s", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_ver_materia = "-1";
if (isset($_GET['id_c'])) 
{
  $colname_ver_materia= (get_magic_quotes_gpc()) ? $_GET['id_c'] : addslashes($_GET['id_c']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_materia = sprintf("SELECT * FROM Tbl_Destinatarios WHERE  id_d='%s '",$colname_ver_materia);
$ver_materia = mysql_query($query_ver_materia, $conexion1) or die(mysql_error());
$num4=mysql_num_rows($ver_materia);
//CODIGO PARA RECIBIR EL ARRAY ENVIADO DESDE PERFIL EDIT PARA BORRAR BODEGA CHULEADA
if($_GET['array']!=''){
function array_recibe($url_array) { 
    $tmp = stripslashes($url_array); 
    $tmp = urldecode($tmp); 
    $tmp = unserialize($tmp); 
   return $tmp; 
} 
$array=$_GET['array'];
if(count($array)) {
$array=array_recibe($array); 
foreach ($array as $indice => $valor){ 
$sql = "DELETE FROM Tbl_Destinatarios WHERE id='$valor'";
$resultado = mysql_query ($sql, $conexion1);
header("location:perfil_cliente_vista.php?tipo_usuario=" . $_GET["tipo_usuario"] ."&nit_c=" . $_GET["nit_c"]."&id_c=" . $_GET["id_c"]);
echo $indice." = ".$valor."<br>";
print_r($array); 
} 
}
}
//FIN CODIGO

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tablainterna2">
  <tr>
    <td id="subppal">C&Oacute;DIGO: R1-F07</td>
     <td colspan="2" id="principal">PERFIL DE CLIENTES</td>
     <td id="subppal">VERSION: 1</td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="2" id="fondo">
      <input name="id_c" type="hidden" id="id_c" value="<?php echo $row_cliente['id_c']; ?>" /></td><td id="noprint"><a href="perfil_cliente_edit.php?id_c=<?php echo $row_cliente['id_c']; ?>"><img src="images/menos.gif" alt="EDITAR"title="EDITAR" border="0" /></a><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="INPRIMIR"title="INPRIMIR" /><?php $tipo=$_GET['tipo_usuario']; if($tipo=='1' || $tipo=='11') { ?><a href="listado_clientes.php"><img src="images/cat.gif"style="cursor:hand;" alt="LISTADO CLIENTES"title="LISTADO CLIENTES" border="0"/></a><?php } ?><a href="comercial.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMERCIAL"title="GESTION COMERCIAL" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR"onclick="window.close() "/></td>
  </tr>
  <tr>
    <td id="subppal2">Fecha Ingreso :
      <?php if($row_cliente['fecha_ingreso_c']==''){echo "- -";} else{echo $row_cliente['fecha_ingreso_c']; }?></td>
    <td id="subppal2">Cliente N&deg; <?php echo $row_cliente['id_c']; ?></td>
    <td id="subppal2">Fecha Solicitud :
      <?php if($row_cliente['fecha_solicitud_c']==''){echo "- -";} else{echo $row_cliente['fecha_solicitud_c']; } ?></td>
  </tr>
  <tr>
    <td id="subppal2">NIT</td>
    <td colspan="2" id="subppal2">Raz&oacute;n Social </td>
    </tr>
  <tr>
    <td id="fuente11"><?php if($row_cliente['nit_c']==''){echo "- -";} else{echo $row_cliente['nit_c']; } ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['nombre_c']==''){echo "- -";} else{echo $row_cliente['nombre_c']; } ?></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="3" id="fuente11">
	<?php if($row_cliente['bolsa_plastica_c']=='1') { echo "BOLSA PLASTICA"; } ?>
	<?php if($row_cliente['lamina_c']=='1') { echo "  LAMINA"; } ?>
	<?php if($row_cliente['cinta_c']=='1') { echo "  CINTA"; } ?>
	<?php if($row_cliente['packing_list_c']=='1') { echo "  PACKING LIST"; } ?></td>
    </tr>
  
  <tr>
    <td colspan="2" id="subppal2">Representante Legal </td>
    <td id="subppal2">Tipo de Cliente </td>
    <td id="subppal2">Indicativo-Telefono (s) -Extension</td>
    </tr>
  <tr>
    <td colspan="2" id="fuente11"><?php if($row_cliente['rep_legal_c']==''){echo "- -";} else{echo $row_cliente['rep_legal_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['tipo_c']==''){echo "- -";} else{echo $row_cliente['tipo_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['telefono_c']==''){echo "- -";} else{echo $row_cliente['telefono_c'];} ?></td>
    </tr>
  <tr>
    <td id="subppal2">Pa&iacute;s</td>
    <td id="subppal2">Ciudad</td>
    <td colspan="2" id="subppal2">Indicativo-Fax -Extension</td>
    </tr>
  <tr>
    <td id="fuente11"><?php if($row_cliente['pais_c']==''){echo "- -";} else{echo $row_cliente['pais_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['ciudad_c']==''){echo "- -";} else{echo $row_cliente['ciudad_c']; } ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['fax_c']==''){echo "- -";} else{echo $row_cliente['fax_c']; } ?></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">Direcci&oacute;n Comercial </td>
    <td id="subppal2">Email Comercial </td>
  </tr>
  <tr>
    <td colspan="3" id="fuente11"><?php if($row_cliente['direccion_c']==''){echo "- -";} else{echo $row_cliente['direccion_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['email_comercial_c']==''){echo "- -";} else{echo $row_cliente['email_comercial_c']; } ?></td>
  </tr>
  <tr>
    <td id="subppal2">Contacto Comercial </td>
    <td id="subppal2">Cargo</td>
    <td id="subppal2">Celular</td>
    <td id="subppal2">Email Bodega </td>
  </tr>
  <tr>
    <td id="fuente11"><?php if($row_cliente['contacto_c']==''){echo "- -";} else{echo $row_cliente['contacto_c'];} ?></td>
    <td id="fuente11"><?php if($row_cliente['cargo_contacto_c']==''){echo "- -";} else{echo $row_cliente['cargo_contacto_c'];} ?></td>
    <td id="fuente11"><?php if($row_cliente['celular_contacto_c'] == ''){ echo "- -";} else{ echo $row_cliente['celular_contacto_c']; }; ?></td>
    <td id="fuente11"><?php if($row_cliente['email_contacto_bodega_c']==''){echo "- -";} else{echo $row_cliente['email_contacto_bodega_c']; } ?></td>
  </tr>
  <tr>
    <td id="subppal2">Direcci&oacute;n Envio Factura </td>
    <td id="subppal2">Email Factura Electronica</td>
    <td id="subppal2">Ind-Telefono Envio Factura-Ext. </td>
    <td id="subppal2">Ind-Fax Envio Factura-Ext. </td>
  </tr>
  <tr>
    <td  id="fuente11"><?php if($row_cliente['direccion_envio_factura_c']==''){echo "- -";} else{echo $row_cliente['direccion_envio_factura_c'];} ?></td>
    <td id="fuente11"><?php echo $row_cliente['email_factura_c']; ?></td>
    <td id="fuente11"><?php if($row_cliente['telefono_envio_factura_c']==''){echo "- -";} else{echo $row_cliente['telefono_envio_factura_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['fax_envio_factura_c']==''){echo "- -";} else{echo $row_cliente['fax_envio_factura_c']; } ?></td>
  </tr>
  
  <tr>
    <td colspan="4" id="fuente3">Observaciones : <?php echo $row_cliente['observ_inf_c']; ?></td>
    </tr>
    
    <?php if($num4!='0'){?>
  <tr>  
    <td colspan="4" id="subppal2">INFORMACION DE DESPACHO DE BODEGAS</td>
  </tr>
  
  <td colspan="5">
<table id="tablainterna"  align="left">
  <tr >
    <td id="subppal2">Nombre</td>
    <td id="subppal2">Direccion de Despachos</td>
    <td id="subppal2">Ciudad de Despachos</td>
    <td id="subppal2">Indicativo - Telefono(s) - Extension</td>
  </tr><?php  for ($k=0;$k<=$num4-1;$k++) { ?>
  <tr>
    
    <td id="fuente1" ><?php $var=mysql_result($ver_materia,$k,nombre_responsable);  echo utf8_encode($var); ?></td>
      
    <td id="fuente1"><?php $var=mysql_result($ver_materia,$k,direccion);  echo utf8_encode($var); ?></td>
    
    <td id="fuente1"><?php $var=mysql_result($ver_materia,$k,ciudad);  echo utf8_encode($var); ?></td>
   
    <td id="fuente1"><?php $var=mysql_result($ver_materia,$k,indicativo);  echo $var; ?>/<?php $var=mysql_result($ver_materia,$k,telefono);  echo $var; ?>/<?php $var=mysql_result($ver_materia,$k,extension);  echo $var; ?></td>
    
  </tr><?php } ?>

</table> 
  </td>
  <?php  }?> 
  
  <tr>
    <td colspan="4" id="subppal2">INFORMACION FINANCIERA </td>
    </tr>
  <tr>
    <td id="subppal2">Contacto Dpto Pagos </td>
    <td colspan="2" id="subppal2">Indicativo-Telefono (s) -Extension</td>
    <td id="subppal2">Indicativo-Fax -Extension</td>
  </tr>
  <tr>
    <td id="fuente11"><?php if($row_cliente['contacto_dpto_pagos_c']==''){echo "- -";} else{echo $row_cliente['contacto_dpto_pagos_c']; } ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['telefono_dpto_pagos_c']==''){echo "- -";} else{echo $row_cliente['telefono_dpto_pagos_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['fax_dpto_pagos_c']==''){echo "- -";} else{echo $row_cliente['fax_dpto_pagos_c']; } ?></td>
  </tr>
  <tr>
    <td id="subppal2">Email</td>
    <td id="subppal2">Cupo Solicitado $ </td>
    <td id="subppal2">Forma de Pago </td>
    <td id="subppal2">Otra Forma de Pago </td>
  </tr>
  <tr>
    <td id="fuente11"><?php if($row_cliente['email_dpto_pagos_c']==''){echo "- -";} else{echo $row_cliente['email_dpto_pagos_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['cupo_solicitado_c']==''){echo "- -";} else{echo $row_cliente['cupo_solicitado_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['forma_pago_c']==''){echo "- -";} else{echo $row_cliente['forma_pago_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['otro_pago_c']==''){echo "- -";} else{echo $row_cliente['otro_pago_c']; } ?></td>
  </tr>
  
  <tr>
    <td id="subppal2">Referencias Comerciales </td>
    <td colspan="2" id="subppal2">Indicativo-Telefono (s) -Extension</td>
    <td id="subppal2">Cupo / Plazo </td>
  </tr>
  <tr>
    <td id="fuente11">1. <?php echo $row_cliente['1ref_comercial_c']; ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['tel_1ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['tel_1ref_comercial_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['cupo_1ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['cupo_1ref_comercial_c']; } ?> / <?php if($row_cliente['plazo_1ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['plazo_1ref_comercial_c']; } ?></td>
  </tr>
  <tr>
    <td id="fuente11">2. <?php echo $row_cliente['2ref_comercial_c']; ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['tel_2ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['tel_2ref_comercial_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['cupo_2ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['cupo_2ref_comercial_c']; } ?> / <?php if($row_cliente['plazo_2ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['plazo_2ref_comercial_c']; } ?></td>
  </tr>
  <tr>
    <td id="fuente11">3. <?php echo $row_cliente['3ref_comercial_c']; ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['tel_3ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['tel_3ref_comercial_c']; } ?></td>
    <td id="fuente11"><?php if($row_cliente['cupo_3ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['cupo_3ref_comercial_c']; } ?> / <?php if($row_cliente['plazo_3ref_comercial_c']==''){echo "- -";} else{echo $row_cliente['plazo_3ref_comercial_c']; } ?></td>
  </tr>
  <tr>
    <td id="subppal2">Referencias Bancarias </td>
    <td id="subppal2">Indicativo-Telefono (s) -Extension</td>
    <td colspan="2" id="subppal2">Nombre</td>
    </tr>
  <tr>
    <td id="fuente11">1. <?php echo $row_cliente['1ref_bancaria_c']; ?></td>
    <td id="fuente11"><?php if($row_cliente['telefono_1ref_bancaria_c']==''){echo "- -";} else{echo $row_cliente['telefono_1ref_bancaria_c']; } ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['nombre_1ref_bancaria_c']==''){echo "- -";} else{echo $row_cliente['nombre_1ref_bancaria_c']; } ?></td>
    </tr>
  <tr>
    <td id="fuente11">2. <?php echo $row_cliente['2ref_bancaria_c']; ?></td>
    <td id="fuente11"><?php if($row_cliente['telefono_2ref_bancaria_c']==''){echo "- -";} else{echo $row_cliente['telefono_2ref_bancaria_c']; } ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['nombre_2ref_bancaria_c']==''){echo "- -";} else{echo $row_cliente['nombre_2ref_bancaria_c']; } ?></td>
    </tr>
  <tr>
    <td id="fuente11">3. <?php echo $row_cliente['3ref_bancaria_c']; ?></td>
    <td id="fuente11"><?php if($row_cliente['telefono_3ref_bancaria_c']==''){echo "- -";} else{echo $row_cliente['telefono_3ref_bancaria_c']; } ?></td>
    <td colspan="2" id="fuente11"><?php if($row_cliente['nombre_3ref_bancaria_c']==''){echo "- -";} else{echo $row_cliente['nombre_3ref_bancaria_c']; } ?></td>
    </tr>
  <tr>
    <td colspan="4" id="fuente3">Observaciones :  
      <?php echo $row_cliente['observ_inf_finan_c']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">APROBACION FINANCIERA </td>
  </tr>
  <tr>
    <td id="subppal2">Cupo Aprobado </td>
    <td id="fuente11"><?php if($row_cliente['cupo_aprobado_c']==''){echo "- -";} else{echo $row_cliente['cupo_aprobado_c']; } ?></td>
    <td id="subppal2">Plazo Aprobado </td>
    <td id="fuente11"><?php if($row_cliente['plazo_aprobado_c']==''){echo "- -";} else{echo $row_cliente['plazo_aprobado_c']; } ?></td>
  </tr>
  <tr>
    <td colspan="4" id="fuente11">Observaciones: <?php echo $row_cliente['observ_aprob_finan_c']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">APROBACION COMERCIAL </td>
  </tr>
  <tr>
    <td id="subppal2">Estado Comercial </td>
    <td id="fuente11"><?php if($row_cliente['estado_comercial_c']==''){echo "- -";} else{echo $row_cliente['estado_comercial_c']; } ?></td>
    <td id="subppal2">Asesor Comercial</td>
    <td id="fuente11">
	<?php if($row_cliente['asesor_comercial_c']=='')
	{echo "- -"; } 
	else { 
	$vendedor=$row_cliente['asesor_comercial_c']; 
	$sqlvendedor="SELECT * FROM vendedor where id_vendedor='$vendedor'"; 
	$resultvendedor=mysql_query($sqlvendedor); 
	$totalvendedor = mysql_num_rows($resultvendedor);
	if($totalvendedor >= '1') {
	$nombrevendedor=mysql_result($resultvendedor,0,'nombre_vendedor');
	echo $nombrevendedor; } } ?></td>
  </tr>
  <tr>
    <td id="subppal2">Impuesto Adicional? </td>
    <td id="fuente11"><?php if($row_cliente['impuesto']==''){echo "- -";} else{echo $row_cliente['impuesto']==1?'SI':"NO"; } ?></td>
    <td id="subppal2">Adjunto PDF Impuesto: </td>
    <td id="fuente11"><a href="javascript:verFoto('archivosc/impuesto/<?php echo $row_cliente['pdf_impuesto'] ?>','610','490')"> 
                <?php if($row_cliente['pdf_impuesto']!='') echo "Impuesto"; ?>
              </a></td>
  </tr>
  <tr>
    <td colspan="4" id="fuente3">Observaciones: <?php echo $row_cliente['observ_asesor_com_c']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">DOCUMENTOS ADJUNTOS </td>
  </tr>
  <tr><?php if($row_cliente['camara_comercio_c']!='' ||$row_cliente['estado_pyg_c']!=''||$row_cliente['referencias_bancarias_c']!=''||$row_cliente['flujo_caja_proy_c']!='') {?>
    <td id="fuente11"><a href="javascript:verFoto('archivosc/<?php echo $row_cliente['camara_comercio_c'] ?>','610','490')">
      <?php if($row_cliente['camara_comercio_c']!='') echo "Camara Comercio"?>
    </a></td>
    <td id="fuente11"><a href="javascript:verFoto('archivosc/<?php echo $row_cliente['estado_pyg_c'] ?>','610','490')">
      <?php if($row_cliente['estado_pyg_c']!='') echo "Estado PYG"?>
    </a></td>
    <td id="fuente11"><a href="javascript:verFoto('archivosc/<?php echo $row_cliente['referencias_bancarias_c'] ?>','610','490')">
      <?php if($row_cliente['referencias_bancarias_c']!='') echo "Proteccion de Datos"?>
    </a></td>   
    <td id="fuente11"><a href="javascript:verFoto('archivosc/<?php echo $row_cliente['flujo_caja_proy_c'] ?>','610','490')">
      <?php if($row_cliente['flujo_caja_proy_c']!='') echo "Flujo Caja"?>
    </a></td><?php }?>   
  </tr>
  <tr><?php if($row_cliente['balance_general_c']!=''||$row_cliente['fotocopia_declar_iva_c']!=''||$row_cliente['referencias_comerciales_c']!=''||$row_cliente['otros_doc_c']!='') {?>
    <td id="fuente11"><a href="javascript:verFoto('archivosc/<?php echo $row_cliente['balance_general_c'] ?>','610','490')">
      <?php if($row_cliente['balance_general_c']!='') echo "Rut"?>
    </a></td>
    <td id="fuente11"><a href="javascript:verFoto('archivosc/<?php echo $row_cliente['fotocopia_declar_iva_c'] ?>','610','490')">
      <?php if($row_cliente['fotocopia_declar_iva_c']!='') echo "Declaracion"?>
    </a></td>     
    <td id="fuente11"><a href="javascript:verFoto('archivosc/<?php echo $row_cliente['referencias_comerciales_c'] ?>','610','490')">
      <?php if($row_cliente['referencias_comerciales_c']!='') echo "Ref. Comerciales"?>
    </a></td>     
    <td id="fuente11"><a href="javascript:verFoto('archivosc/<?php echo $row_cliente['otros_doc_c'] ?>','610','490')">
      <?php if($row_cliente['otros_doc_c']!='') echo "Otros"?>
    </a></td> 
     <?php }?> 
  </tr> 
  <tr>
    <td colspan="4" id="fuente3">Observaciones : <?php echo $row_cliente['observ_doc_c']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">&nbsp;</td>
  </tr>
  <tr>
    <td id="subppal2">Estado Cliente </td>
    <td id="subppal2">Registrado</td>
    <td id="subppal2">Revisado </td>
    <td id="subppal2">Fecha Revisi&oacute;n </td>
  </tr>
  <tr>
    <td id="fuente11"><?php echo $row_cliente['estado_c']; ?></td>
    <td id="fuente11"><?php echo $row_cliente['registrado_c']; ?></td>
    <td id="fuente11"><?php echo $row_cliente['revisado_c']; ?></td>
    <td id="fuente11"><?php echo $row_cliente['fecha_revision_c']; ?></td>
  </tr>
  <tr>
    <td colspan="4" id="fuente2"><form id="form1" name="form1" method="post" action="">
    </form></td>
    </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($cliente);

mysql_free_result($ver_materia);

mysql_free_result($usuario);
?>
