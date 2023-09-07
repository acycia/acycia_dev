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

$colname_vista_egl = "-1";
if (isset($_GET['n_egl'])) {
  $colname_vista_egl = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_vista_egl = sprintf("SELECT * FROM egl WHERE n_egl = %s", GetSQLValueString($colname_vista_egl, "int"));
$vista_egl = mysql_query($query_vista_egl, $conexion1) or die(mysql_error());
$row_vista_egl = mysql_fetch_assoc($vista_egl);
$totalRows_vista_egl = mysql_num_rows($vista_egl);

$colname_colores = "-1";
if (isset($_GET['n_egl'])) {
  $colname_colores = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_colores = sprintf("SELECT * FROM egl_colores WHERE n_egl = %s", GetSQLValueString($colname_colores, "int"));
$colores = mysql_query($query_colores, $conexion1) or die(mysql_error());
$row_colores = mysql_fetch_assoc($colores);
$totalRows_colores = mysql_num_rows($colores);

$colname_archivos = "-1";
if (isset($_GET['n_egl'])) {
  $colname_archivos = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_archivos = sprintf("SELECT * FROM egl_archivos WHERE n_egl = %s", GetSQLValueString($colname_archivos, "int"));
$archivos = mysql_query($query_archivos, $conexion1) or die(mysql_error());
$row_archivos = mysql_fetch_assoc($archivos);
$totalRows_archivos = mysql_num_rows($archivos);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tablainterna">
<tr>
  <td width="2463" id="subppal">CODIGO : R1 - F08</td>
  <td colspan="2" nowrap="nowrap" id="principal">ESPECIFICACION GENERAL DE LA LAMINA</td>
  <td width="141" id="subppal">VERSION : 2 </td>
</tr>
<tr>
  <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
  <td colspan="2" nowrap="nowrap" id="fondo">EGL N&deg; <strong><?php echo $row_vista_egl['n_egl']; ?></strong></td>
  <td id="noprint" align="center"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="egp_lamina_edit.php?n_egl=<?php echo $row_vista_egl['n_egl']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="egp_lamina.php"><img src="images/cat.gif"alt="LISTADO EGL"border="0"style="cursor:hand;"/></a><a href="comercial.php"><img src="images/opciones.gif" style="cursor:hand;" alt="COMERCIAL" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
</tr>
<tr>
  <td colspan="2" nowrap="nowrap" id="fuente1">REGISTRO INICIAL</td>
  <td id="fuente1">ESTADO</td>
</tr>
<tr>
  <td colspan="2" nowrap="nowrap" id="fuente2"><?php echo $row_vista_egl['responsable_egl']; ?> / <?php echo $row_vista_egl['fecha_egl']; ?> / <?php echo $row_vista_egl['hora_egl']; ?></td>
  <td id="fuente2"><?php switch($row_vista_egl['estado_egl']) {
		      case 0: echo "PENDIENTE"; break;
			  case 1: echo "ACEPTADA"; break;
			  case 2: echo "OBSOLETA"; break; } ?>
  </td>
</tr>
<tr>
  <td colspan="2" nowrap="nowrap" id="fuente1">MODIFICACION</td>
  <td nowrap="nowrap" id="fuente1">COTIZACION - REFERENCIA</td>
  </tr>
<tr>
  <td colspan="2" nowrap="nowrap" id="fuente2"><?php echo $row_vista_egl['modificacion']; ?> / <?php echo $row_vista_egl['fecha_modificacion']; ?> / <?php echo $row_vista_egl['hora_modificacion']; ?></td>
  <td id="fuente2">&nbsp;</td>
</tr>
<tr>
  <td colspan="3" nowrap="nowrap" id="fondo6">Alguna Inquietud o Comentario : <strong>info@acycia.com</strong></td>
  </tr>
<tr>
  <td colspan="4" id="subtitulo2">ESPECIFICACION DEL MATERIAL</td>
  </tr>
<tr>
  <td colspan="4" id="fuente3"><strong>ESTRUCTURA: </strong><?php echo $row_vista_egl['estructura_egl']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><strong>PIGMENTO EXTERIOR: </strong><?php echo $row_vista_egl['pigm_ext_egl']; ?></td>
  <td colspan="2" id="fuente3"><strong>PIGMENTO INTERIOR: </strong><?php echo $row_vista_egl['pigm_int_egl']; ?></td>
  </tr>
<tr>
  <td id="fuente3"><strong>ANCHO: </strong><?php echo $row_vista_egl['ancho_egl']; ?></td>
  <td width="195" id="fuente3"><strong>CALIBRE: </strong><?php echo $row_vista_egl['calibre_egl']; ?></td>
  <td width="223" id="fuente3"><strong>PESO / ML: </strong><?php echo $row_vista_egl['peso_egl']; ?></td>
  <td id="fuente3"><strong>DIAMETRO: </strong><?php echo $row_vista_egl['diametro_rollo_egl']; ?></td>
</tr>
<tr>
  <td colspan="2" id="fuente3"><strong>TRATAMIENTO CORONA: </strong><?php echo $row_vista_egl['tto_corona_egl']; ?></td>
  <td colspan="2" id="fuente3"><strong>TIPO DE EMPAQUE: </strong><?php echo $row_vista_egl['tipo_empaque_egl']; ?></td>
  </tr>
<tr>
  <td colspan="4" id="fuente3"><strong>OBSERVACIONES: </strong><?php echo $row_vista_egl['observ_material_egl']; ?></td>
  </tr>
<tr>
  <td colspan="4" id="subtitulo2">ESPECIFICACION DE LA IMPRESION</td>
  </tr>
<tr>
  <td id="fuente3"><strong>N&ordm; REPETICIONES: </strong><?php echo $row_vista_egl['repeticion_egl']; ?></td>
  <td id="fuente3"><strong>RODILLO N&ordm;: </strong><?php echo $row_vista_egl['repeticion_egl']; ?></td>
  <td id="fuente3"><strong>CODIGO DE BARRAS: </strong><?php echo $row_vista_egl['codigo_barras_egl']; ?></td>
  <td id="fuente3"><?php if($row_vista_egl['fotocelda_egl'] == '1') { ?><img src="images/palomita.gif">
    <?php } else { ?> <img src="images/vacio.gif"><?php } ?><strong> FOTOCELDA</strong></td>
</tr>
<tr>
  <td colspan="4" id="fondo">
  <?php if($row_colores['id_color']!='') { ?>
  <table id="tabla2">
  <tr>
    <td id="fuente1">COLOR</td>
    <td id="fuente1">PANTONE</td>
    <td id="fuente1">UBICACION</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="dato1"><?php echo $row_colores['color']; ?></td>
      <td id="dato1"><?php echo $row_colores['pantone']; ?></td>
      <td id="dato1"><?php echo $row_colores['ubicacion']; ?></td>
    </tr>
    <?php } while ($row_colores = mysql_fetch_assoc($colores)); ?>
  </table>
  <?php } else { echo "* NO TIENE COLORES *"; } ?>
  </td>
  </tr>  
<tr>
  <td colspan="4" id="fuente3"><strong>OBSERVACIONES: </strong><?php echo $row_vista_egl['observ_impresion_egl']; ?></td>
  </tr>
<tr>
  <td colspan="4" id="subtitulo2">ESPECIFICACION DEL ARTE</td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><?php if($row_vista_egl['arte_cliente_egl'] == '1') { ?><img src="images/palomita.gif"><?php } else { ?> <img src="images/vacio.gif"><?php } ?><strong> Arte suministrado por el cliente</strong></td>
  <td colspan="2" id="fuente3"><strong>DISEÑADOR: </strong><?php echo $row_vista_egl['disenador_egl']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><?php if($row_vista_egl['archivos_montaje_egl'] == '1') { ?><img src="images/palomita.gif"><?php } else { ?> <img src="images/vacio.gif"><?php } ?><strong> El cliente entrega archivos para montaje</strong></td>
  <td colspan="2" id="fuente3"><strong>TELEFONO: </strong><?php echo $row_vista_egl['telefono_disenador_egl']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><?php if($row_vista_egl['orientacion_arte_egl'] == '1') { ?><img src="images/palomita.gif"><?php } else { ?> <img src="images/vacio.gif"><?php } ?><strong> Solicita orientacion en el arte</strong></td>
  <td colspan="2" id="fuente3"><strong>FECHA DEL ARTE: </strong><?php echo $row_vista_egl['fecha_arte_egl']; ?></td>
  </tr>
<tr>
  <td colspan="4" id="fondo">
  <?php if($row_archivos['id_archivo'] != '') { ?>
  <table>
    <?php do { ?>
      <tr>
        <td id="dato1"><a href="javascript:verFoto('egplamina/<?php echo $row_archivos['archivo']; ?>','610','490')"><?php echo $row_archivos['archivo']; ?></a></td>
      </tr>
      <?php } while ($row_archivos = mysql_fetch_assoc($archivos)); ?>
  </table>
  <?php } else { echo "* NO HAY ARCHIVOS ADJUNTOS *";} ?>
  </td>
</tr>
<tr>
  <td colspan="4" id="fuente3"><strong>OBSERVACIONES: </strong><?php echo $row_vista_egl['observ_arte_egl']; ?></td>
  </tr>
<tr>
  <td colspan="4" id="subtitulo2">ESPECIFICACION DEL DESPACHO</td>
  </tr>
<tr>
  <td colspan="2" id="fuente2">TIPO DE EMBOBINADO</td>
  <td colspan="2" id="fuente3">&nbsp;</td>
  </tr>
<tr>
  <td colspan="2" rowspan="4" id="fondo2"><?php switch($row_vista_egl['embobinado_egl']) {
	  case 0: echo "VACIO"; break;
	  case 1: ?><img src="images/embobinado1.gif"><?php break;
	  case 2: ?><img src="images/embobinado2.gif"><?php break;
	  case 3: ?><img src="images/embobinado3.gif"><?php break;
	  case 4: ?><img src="images/embobinado4.gif"><?php break;
	  case 5: ?><img src="images/embobinado5.gif"><?php break;
	  case 6: ?><img src="images/embobinado6.gif"><?php break;
	  case 7: ?><img src="images/embobinado7.gif"><?php break;
	  case 8: ?><img src="images/embobinado8.gif"><?php break;
	  case 9: ?><img src="images/embobinado9.gif"><?php break;
	  case 10: ?><img src="images/embobinado10.gif"><?php break;
	  case 11: ?><img src="images/embobinado11.gif"><?php break;
	  case 12: ?><img src="images/embobinado12.gif"><?php break;
	  case 13: ?><img src="images/embobinado13.gif"><?php break;
	  case 14: ?><img src="images/embobinado14.gif"><?php break;
	  case 15: ?><img src="images/embobinado15.gif"><?php break;
	  case 16: ?><img src="images/embobinado16.gif"><?php break;
	  } ?>
  </td>
  <td colspan="2" id="fuente3"><strong>PESO / ROLLO: </strong><?php echo $row_vista_egl['peso_rollo_egl']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><strong>IDENTIFICACION DEL ROLLO :</strong><?php echo $row_vista_egl['ident_rollo_egl']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><strong>LUGAR DE ENTREGA: </strong><?php echo $row_vista_egl['lugar_entrega_egl']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="fuente3"><strong>VENDEDOR: </strong>
  <?php $vendedor=$row_vista_egl['id_vendedor_egl']; 
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
  <td colspan="4" id="fuente3"><strong>OBSERVACIONES: </strong><?php echo $row_vista_egl['observ_despacho_egl']; ?></td>
  </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($vista_egl);

mysql_free_result($colores);

mysql_free_result($archivos);
?>
