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

$colname_egp = "-1";
if (isset($_GET['n_egp'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['n_egp'] : addslashes($_GET['n_egp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egp = sprintf("SELECT * FROM Tbl_egp WHERE n_egp = %s", $colname_egp);
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
</head>
<body>
<div align="center">
<table id="tablainterna">
<tr>
  <td id="subppal">Codigo R1 - F08 </td>
  <td nowrap="nowrap" id="principal">EGP - BOLSA DE SEGURIDAD </td>
  <td id="subppal">Versi&oacute;n : 2 </td>
</tr>
<tr>
  <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
  <td id="fondo">EGP N&deg; <?php echo $row_egp['n_egp']; ?></td>
  <td id="noprint"><a href="egp_bolsa_edit.php?n_egp=<?php echo $row_egp['n_egp']; ?>&amp;pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp'];?>"><img src="images/menos.gif" alt="EDITAR" border="0" style="cursor:hand;" /></a><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="INPRIMIR" /><a href="egp_bolsa_add.php"><img src="images/mas.gif" alt="ADD EGP-BOLSA" border="0" style="cursor:hand;"></a><a href="egp_bolsa.php?pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp']; ?>"><img src="images/a.gif" border="0" style="cursor:hand;" alt="EGP'S ACTIVAS" /></a><a href="egp_bolsa_obsoletos.php?pageNum_egp=<?php echo $_GET['pageNum_egp']; ?>&amp;totalRows_egp=<?php echo $_GET['totalRows_egp']; ?>"><img src="images/i.gif" border="0" style="cursor:hand;" alt="EGP'S OBSOLETAS" /></a><a href="egp_menu.php"><img src="images/opciones.gif" style="cursor:hand;" alt="MENU EGP'S" border="0" /></a><a href="cotizacion_bolsa.php"><img src="images/c.gif" style="cursor:hand;" alt="COTIZACIONES" border="0"/></a><a href="comercial.php"><img src="images/identico.gif" style="cursor:hand;" alt="GESTION COMERCIAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onclick="window.close() "/></td>
</tr>
<tr>
  <td nowrap="nowrap" id="fuente1">FECHA : <?php echo $row_egp['fecha_egp']; ?></td>
  <td nowrap="nowrap" id="fuente1">HORA : <?php echo $row_egp['hora_egp']; ?></td>
  </tr>
<tr>
  <td id="fuente2">ESTADO :
    <?php $estado=$row_egp['estado_egp']; 
		if($estado == '0') { echo "Pendiente"; } 
		if($estado == '1'){ echo "Aceptado"; }
		if($estado == '2') { echo "Obsoleta"; } ?></td>
  <td id="fuente2">REF :
    <?php
	$n_egp=$row_egp['n_egp'];
	$estado=$row_egp['estado_egp'];
	if($estado=='0' || $estado=='') 
	{ 
	echo '- -'; 
	}
	if($estado=='1')
	{
	$sql2="SELECT * FROM Tbl_referencia WHERE n_egp_ref='$n_egp'";
	$result2=mysql_query($sql2);
	$num2=mysql_num_rows($result2);
	if ($num2 >= '1')
	{
	$referencia=mysql_result($result2,0,'cod_ref');
	echo $referencia;
	}
	} ?></td>
  </tr>
<tr>
  <td id="fuente1">REGISTRADO POR</td>
  <td id="fuente1"> USUARIO</td>
  </tr>
<tr>
  <td id="fuente2"><?php echo $row_egp['responsable_egp']; ?></td>
  <td id="fuente2"><?php $codigo=$row_egp['codigo_usuario']; if($codigo=='ACYCIA') { echo "ACYCIA"; } else { echo "CLIENTE COMERCIAL"; } ?></td>
  </tr>
<tr>
  <td colspan="2" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
  </tr>
<tr>
  <td colspan="3" id="subppal2">ESPECIFICACION DEL MATERIAL </td>
  </tr>
<tr>
  <td id="fuente1">ANCHO</td>
  <td id="fuente1">LARGO</td>
  <td id="fuente1">SOLAPA</td>
</tr>
<tr>
  <td id="fuente2"><?php echo $row_egp['ancho_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['largo_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['solapa_egp']; ?></td>
</tr>
<tr>
  <td id="fuente1">LARGO DEL CANGURO </td>
  <td id="fuente1">CALIBRE</td>
  <td id="fuente1">TIPO EXTRUSION </td>
</tr>
<tr>
  <td id="fuente2"><?php echo $row_egp['largo_cang_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['calibre_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['tipo_ext_egp']; ?></td>
</tr>
<tr>
  <td id="fuente1">PIGMENTO EXTERIOR </td>
  <td id="fuente1">PIGMENTO INTERIOR </td>
  <td id="fuente1">ADHESIVO</td>
</tr>
<tr>
  <td id="fuente2"><?php echo $row_egp['pigm_ext_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['pigm_int_epg']; ?></td>
  <td id="fuente2"><?php echo $row_egp['adhesivo_egp']; ?></td>
</tr>
<tr>
  <td id="fuente1">TIPO BOLSA </td>
  <td id="fuente1">CANTIDAD</td>
  <td id="fuente1">TIPO SELLO </td>
</tr>
<tr>
  <td id="fuente2"><?php echo $row_egp['tipo_bolsa_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['cantidad_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['tipo_sello_egp']; ?></td>
</tr>
<tr>
  <td colspan="3" id="fuente3">OBSERVACIONES : <?php echo $row_egp['observacion1_egp']; ?></td>
  </tr>
<tr>
  <td colspan="3" id="subppal2">ESPECIFICACION DE LA IMPRESION</td>
  </tr>
<tr>
  <td id="fuente3">COLOR 1 : <?php echo $row_egp['color1_egp']; ?></td>
  <td id="fuente3">PANTONE : <?php echo $row_egp['pantone1_egp']; ?></td>
  <td id="fuente3">UBICACION : <?php echo $row_egp['ubicacion1_egp']; ?></td>
</tr>
<tr>
  <td id="fuente3">COLOR 2 : <?php echo $row_egp['color2_egp']; ?></td>
  <td id="fuente3">PANTONE : <?php echo $row_egp['pantone2_egp']; ?></td>
  <td id="fuente3">UBICACION : <?php echo $row_egp['ubicacion2_egp']; ?></td>
</tr>
<tr>
  <td id="fuente3">COLOR 3 : <?php echo $row_egp['color3_egp']; ?></td>
  <td id="fuente3">PANTONE : <?php echo $row_egp['pantone3_egp']; ?></td>
  <td id="fuente3">UBICACION : <?php echo $row_egp['ubicacion3_egp']; ?></td>
</tr>
<tr>
  <td id="fuente3">COLOR 4 : <?php echo $row_egp['color4_egp']; ?></td>
  <td id="fuente3">PANTONE : <?php echo $row_egp['pantone4_egp']; ?></td>
  <td id="fuente3">UBICACION : <?php echo $row_egp['ubicacion4_egp']; ?></td>
</tr>
<tr>
  <td id="fuente3">COLOR 5 : <?php echo $row_egp['color5_egp']; ?></td>
  <td id="fuente3">PANTONE : <?php echo $row_egp['pantone5_egp']; ?></td>
  <td id="fuente3">UBICACION : <?php echo $row_egp['ubicacion5_egp']; ?></td>
</tr>
<tr>
  <td id="fuente3">COLOR 6 : <?php echo $row_egp['color6_egp']; ?></td>
  <td id="fuente3">PANTONE : <?php echo $row_egp['pantone6_egp']; ?></td>
  <td id="fuente3">UBICACION : <?php echo $row_egp['ubicacion6_egp']; ?></td>
</tr>

<tr>
  <td colspan="3" id="fuente3">OBSERVACIONES : <?php echo $row_egp['observacion2_egp']; ?></td>
  </tr>
<tr>
  <td colspan="3" id="subppal2">ESPECIFICACION DE LA NUMERACION </td>
  </tr>
<tr>
  <td id="fuente1">POSICION</td>
  <td id="fuente1">TIPO DE NUMERACION </td>
  <td id="fuente1">FORMATO CB </td>
</tr>
<tr>
  <td id="fuente2">SOLAPA TR </td>
  <td id="fuente2"><?php echo $row_egp['tipo_solapatr_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['cb_solapatr_egp']; ?></td>
</tr>
<tr>
  <td id="fuente2">CINTA</td>
  <td id="fuente2"><?php echo $row_egp['tipo_cinta_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['cb_cinta_egp']; ?></td>
</tr>
<tr>
  <td id="fuente2">PRINCIPAL</td>
  <td id="fuente2"><?php echo $row_egp['tipo_principal_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['cb_principal_egp']; ?></td>
</tr>
<tr>
  <td id="fuente2">INFERIOR</td>
  <td id="fuente2"><?php echo $row_egp['tipo_inferior_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['cb_inferior_egp']; ?></td>
</tr>
<tr>
  <td colspan="2" id="fuente3">COMIENZA EN : <?php echo $row_egp['comienza_egp']; ?></td>
  <td nowrap="nowrap" id="fuente3"><input name="checkbox" type="checkbox" disabled="true" value="1" <?php if (!(strcmp($row_egp['fecha_cad_egp'],1))) {echo "checked=\"checked\"";} ?> />Incluir Fecha de Caducidad</td>
</tr>
<tr>
  <td colspan="3" id="fuente3">OBSERVACIONES : <?php echo $row_egp['observacion3_egp']; ?></td>
</tr>
<tr>
  <td colspan="3" id="subppal2">ESPECIFICACION DE ARTE </td>
</tr>
<tr>
  <td nowrap="nowrap" id="fuente3"><input name="checkbox2" type="checkbox" disabled="true" value="1" <?php if (!(strcmp($row_egp['arte_sum_egp'],1))) {echo "checked=\"checked\"";} ?> />
    Arte Suministrado por el Cliente</td>
  <td nowrap="nowrap" id="fuente3"><input name="checkbox5" type="checkbox" disabled="true" value="1" <?php if (!(strcmp($row_egp['orient_arte_egp'],1))) {echo "checked=\"checked\"";} ?> />
    Solicita Orientacion en el Arte </td>
  <td nowrap="nowrap" id="fuente3"><input name="checkbox4" type="checkbox" disabled="true" value="1" <?php if (!(strcmp($row_egp['ent_logo_egp'],1))) {echo "checked=\"checked\"";} ?> />
    Entrega Logos de la Entidad </td>
</tr>
<tr>
  <td id="fuente3">Archivo 1: <a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo1'];?>','610','490')"><?php echo $row_egp['archivo1'];?></a></td>
  <td id="fuente3">Archivo 2: <a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo2'];?>','610','490')"><?php echo $row_egp['archivo2']; ?></a></td>
  <td id="fuente3">Archivo 3: <a href="javascript:verFoto('egpbolsa/<?php echo $row_egp['archivo3'];?>','610','490')"><?php echo $row_egp['archivo3']; ?></a></td>
</tr>
<tr>
  <td colspan="2" id="fuente3">PERSONA ENCARGADA DEL DISE&Ntilde;O  : <?php echo $row_egp['disenador_egp']; ?></td>
  <td id="fuente3">TELEFONO : <?php echo $row_egp['telef_disenador_egp']; ?></td>
</tr>
<tr>
  <td colspan="3" id="fuente3">OBSERVACIONES : <?php echo $row_egp['observacion4_egp']; ?></td>
</tr>
<tr>
  <td colspan="3" id="subppal2">ESPECIFICACION DE DESPACHO </td>
</tr>
<tr>
  <td id="fuente1">UNIDADES POR PAQUETE </td>
  <td id="fuente1">UNIDADES POR CAJA </td>
  <td id="fuente1">MARCA DE CAJAS</td>
</tr>
<tr>
  <td id="fuente2"><?php echo $row_egp['unids_paq_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['unids_caja_egp']; ?></td>
  <td id="fuente2"><?php echo $row_egp['marca_cajas_egp']; ?></td>
</tr>
<tr>
  <td colspan="2" id="fuente3">LUGAR DE ENTREGA DE LA MERCANCIA  : <?php echo $row_egp['lugar_entrega_egp']; ?></td>
  <td id="fuente3">Vendedor : 
  <?php $vendedor=$row_egp['vendedor'];
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
  <td colspan="3" id="fuente3">OBSERVACIONES : <?php echo $row_egp['observacion5_egp']; ?></td>
</tr>
<tr>
  <td id="fuente1">MODIFICADA POR </td>
  <td id="fuente1">FECHA MODIFICACION </td>
  <td id="fuente1">HORA MODIFICACION</td>
</tr>
<tr>
  <td id="fuente2">- <?php echo $row_egp['responsable_modificacion']; ?> - </td>
  <td id="fuente2">- <?php echo $row_egp['fecha_modificacion']; ?> - </td>
  <td id="fuente2">- <?php echo $row_egp['hora_modificacion']; ?>-</td>
</tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($egp);
?>
