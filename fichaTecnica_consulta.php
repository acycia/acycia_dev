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

$colname_insumoFicha= "-1";
if (isset($_GET['ref'])) {
  $colname_insumoFicha= (get_magic_quotes_gpc()) ? $_GET['ref'] : addslashes($_GET['ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumoFicha= sprintf("SELECT id_insumo, codigo_insumo, descripcion_insumo  FROM insumo WHERE id_insumo = '%s'", $colname_insumoFicha);
$insumoFicha = mysql_query($query_insumoFicha, $conexion1) or die(mysql_error());
$row_insumoFicha= mysql_fetch_assoc($insumoFicha);
$totalRows_insumoFicha= mysql_num_rows($insumoFicha);
?>
<?php 
//************************** Definicion Tipo de Usuario
$id_ref=$_GET["ref"];
if($id_ref!='')
{
	
?> <div id="acceso1"><td id="fuente1"><strong> <?php echo "REF. ",$row_insumoFicha["codigo_insumo"];?>  CANT: <input name="cantAditivo_ft" type="number" style="width:50px" min="0" step="1" value="" /> % </strong></td></div> <?php 
?>
<?php
}
mysql_free_result($usuario);

mysql_free_result($insumoFicha);
?>
