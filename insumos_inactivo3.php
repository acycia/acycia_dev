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

mysql_select_db($database_conexion1, $conexion1);
$query_insumos = "SELECT * FROM insumo WHERE estado_insumo ='1' ORDER BY descripcion_insumo ASC";
$insumos = mysql_query($query_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);
$totalRows_insumos = mysql_num_rows($insumos);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link rel="StyleSheet" href="css/formato.css" type="text/css">
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body><div align="center">
<table id="tabla3">
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF" bordercolor="#ACCFE8"> 
      <td class="Estilo3"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['codigo_insumo']; ?></a></td>
      <td class="texto"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['descripcion_insumo']; ?></a></td>
      <td class="Estilo3"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $clase_insumo=$row_insumos['clase_insumo'];
	  $sqlclase="SELECT * FROM clase WHERE id_clase='$clase_insumo'";
	  $resultclase= mysql_query($sqlclase);
	  $numclase= mysql_num_rows($resultclase);
	  if($numclase >='1')
	  { 
	  $clase = mysql_result($resultclase, 0, 'nombre_clase'); 	  
	  if($clase != '')  { echo $clase; } } ?></a></td>
      <td class="Estilo3"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $medida_insumo=$row_insumos['medida_insumo'];
	$sqlmedida="SELECT * FROM medida WHERE id_medida = $medida_insumo";
	$resultmedida= mysql_query($sqlmedida);
	$numedida= mysql_num_rows($resultmedida);
	if($numedida >='1') { $medida_insumo=mysql_result($resultmedida,0,'nombre_medida'); }
	echo $medida_insumo; ?></a></td>
      <td class="Estilo3"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php $tipo_insumo=$row_insumos['tipo_insumo'];
	$sqltipo="SELECT * FROM tipo WHERE id_tipo = $tipo_insumo";
	$resultipo= mysql_query($sqltipo);
	$numtipo= mysql_num_rows($resultipo);
	if($numtipo >='1') { $tipo_insumo=mysql_result($resultipo,0,'nombre_tipo'); }
	echo $tipo_insumo;
	 ?></a></td>
      <td class="derecha2">$  <a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['valor_unitario_insumo']; ?></a></td>
      <td class="derecha2"><a href="insumo_edit.php?id_insumo=<?php echo $row_insumos['id_insumo']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_insumos['stok_insumo']; ?></a></td>
    </tr>
    <?php } while ($row_insumos = mysql_fetch_assoc($insumos)); ?>
</table></div>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($insumos);
?>
