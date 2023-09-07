<?php require_once('Connections/conexion1.php'); ?><?php
$colname_referenciaver = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_referenciaver = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referenciaver = sprintf("SELECT * FROM Tbl_referencia WHERE cod_ref = %s", $colname_referenciaver);
$referenciaver = mysql_query($query_referenciaver, $conexion1) or die(mysql_error());
$row_referenciaver = mysql_fetch_assoc($referenciaver);
$totalRows_referenciaver = mysql_num_rows($referenciaver);

$colname_referencia_egp = "-1";
if (isset($_GET['cod_ref'])) {
  $colname_referencia_egp = (get_magic_quotes_gpc()) ? $_GET['cod_ref'] : addslashes($_GET['cod_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.cod_ref = %s AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_referencia_egp);
$referencia_egp = mysql_query($query_referencia_egp, $conexion1) or die(mysql_error());
$row_referencia_egp = mysql_fetch_assoc($referencia_egp);
$totalRows_referencia_egp = mysql_num_rows($referencia_egp);

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_refs_clientes = "-1";
if (isset($_GET['Str_nit'])) {
  $colname_refs_clientes = (get_magic_quotes_gpc()) ? $_GET['Str_nit'] : addslashes($_GET['Str_nit']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_refs_clientes = sprintf("SELECT * FROM cliente WHERE nit_c='%s'", $colname_refs_clientes);
$refs_clientes = mysql_query($query_refs_clientes, $conexion1) or die(mysql_error());
$row_refs_clientes = mysql_fetch_assoc($refs_clientes);
$totalRows_refs_clientes = mysql_num_rows($refs_clientes);

$N_cotizacion=$_GET['N_cotizacion'];
/*$colname_ref_verif = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ref_verif = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_verif = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s AND estado_arte_verif = '2'", $colname_ref_verif);
$ref_verif = mysql_query($query_ref_verif, $conexion1) or die(mysql_error());
$row_ref_verif = mysql_fetch_assoc($ref_verif);
$totalRows_ref_verif = mysql_num_rows($ref_verif);*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body>
<div align="center">
<table id="tablainterna">
  <tr>    
     <td colspan="4" id="principal">REFERENCIA ( MATERIA PRIMA) </td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="3" id="fuente3"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="INPRIMIR" border="0" /><?php $tipo=$_GET['tipo']; if($tipo=='1') { ?><a href="referencia_materiap_edit.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>&n_egp=<?php echo $row_referenciaver['n_egp_ref']; ?>&N_cotizacion=<?php echo $N_cotizacion; ?>"><img src="images/menos.gif" alt="EDITAR" title"EDITAR"border="0" /></a><a href="referencia_cliente.php?id_ref=<?php echo $row_referenciaver['id_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" border="0"></a><?php } else{ ?><a href="acceso.php"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="acceso.php"><img src="images/cliente.gif" alt="EDITAR" border="0" /></a><?php } ?><a href="referencias.php"><img src="images/a.gif" style="cursor:hand;" alt="REF'S ACTIVAS" border="0" /></a><a href="referencias_inactivas.php"><img src="images/i.gif" style="cursor:hand;" alt="REF'S ACTIVAS" border="0" /></a><a href="disenoydesarrollo.php"><img src="images/opciones.gif" style="cursor:hand;" alt="DISEÑO Y DESARROLLO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
    </tr>
  <tr>
    <td id="subppal2">FECHA DE INGRESO </td>
    <td colspan="2" id="subppal2">RESPONSABLE</td>
    </tr>
  <tr>
    <td id="fuente2"><?php echo $row_referenciaver['fecha_registro1_ref']; ?></td>
    <td colspan="2" nowrap id="fuente2"><?php echo $row_referenciaver['registro1_ref']; ?></td>
    </tr>
  <tr>
    <td id="subppal2">REFERENCIA - VERSION</td>
    <td colspan="2" id="subppal2">COTIZACION N&ordm; </td>
    </tr>
  <tr>
    <td nowrap id="fuente2"><strong><?php echo $row_referenciaver['cod_ref']; ?> - 
      <?php echo $row_referenciaver['version_ref']; ?></strong></td>
    <td colspan="2" id="fuente2"><?php echo $row_referenciaver['n_cotiz_ref']; ?></td>
    </tr>
  <tr>
    <td colspan="3" id="fondo">Alguna Inquietud o Comentario : info@acycia.com </td>
    </tr>
  <tr>
    <td colspan="4" id="subppal2">DATOS GENERALES DE LA REFERENCIA </td>
    </tr>
  <tr>
    <td id="subppal2">MATERIA PRIMA</td>
    <td id="subppal2">&nbsp;</td>
    <td id="subppal2">&nbsp;</td>
    <td id="subppal2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente3"><?php echo $row_referenciaver['Str_referencia_m']; ?></td>
    <td id="fuente2">&nbsp;</td>
    <td id="fuente2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="subppal2">ARCHIVO ADJUNTO</td>
    </tr>
  <tr>
    <td colspan="4" id="fuente2"><?php if($row_referenciaver['Str_linc_m']!=''){ ?>
      <a href="javascript:verFoto('archivosc/archivos_pdf_mp/<?php echo $row_referenciaver['Str_linc_m'] ?>','610','490')" target="_blank"><?php echo $row_referenciaver['Str_linc_m'] ?></a>
      <?php }else  echo "<span class='rojo'>No tiene archivos adjuntos</span>";  ?>
      <input type="hidden" name="Str_linc" id="Str_linc" value="<?php echo $row_referenciaver['Str_linc_m'] ?>"/></td>
    </tr>
  <tr>
    <td id="subppal2">CLIENTE</td>
    <td id="subppal2">DIRECCION</td>
    <td id="subppal2">PAIS / CIUDAD </td>
    <td id="subppal2">TELEFONO</td>
  </tr>
  <?php do { ?>
    <tr>
      <td id="fuente3"><?php echo $row_refs_clientes['nombre_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['direccion_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['pais_c']; ?> / <?php echo $row_refs_clientes['ciudad_c']; ?></td>
      <td id="fuente3"><?php echo $row_refs_clientes['telefono_c']; ?></td>
    </tr>
    <?php } while ($row_refs_clientes = mysql_fetch_assoc($refs_clientes)); ?>
  <tr>
    <td colspan="4" id="fondo">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="2" id="subppal2">FECHA ULTIMA MODIFICACION </td>
    <td colspan="2" id="subppal2">RESPONSABLE ULTIMA MODIFICACION </td>
    </tr>
  <tr>
    <td colspan="2" id="fuente2"><?php echo $row_referenciaver['fecha_registro2_ref']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_referenciaver['registro2_ref']; ?></td>
    </tr>
</table>
</div>
</body>
</html>
<?php
mysql_free_result($referenciaver);

mysql_free_result($referencia_egp);

mysql_free_result($usuario);

mysql_free_result($refs_clientes);

//mysql_free_result($ref_verif);
?>
