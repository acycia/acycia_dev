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

$colname_cliente = "-1";
if (isset($_GET['id_c_rc2'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['id_c_rc2'] : addslashes($_GET['id_c_rc2']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE id_c = %s", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
?>
<?php 
//************************** Definicion Tipo de Usuario
$id_c=$_GET["id_c_rc2"];
if($id_c!='')
{
	//echo $row_cliente["nit_c"]; 
?>
<input name="str_nit_rc[]" type="hidden" id="str_nit_rc2" value="<?php echo $row_cliente["nit_c"];?>" size="20">
<?php
}
mysql_free_result($usuario);

mysql_free_result($cliente);

?>
