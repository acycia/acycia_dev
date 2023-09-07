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

$colname_referencia_ver = "-1";
if (isset($_GET['int_ref_ac_rc'])) {
  $colname_referencia_ver = (get_magic_quotes_gpc()) ? $_GET['int_ref_ac_rc'] : addslashes($_GET['int_ref_ac_rc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_ver = sprintf("SELECT * FROM Tbl_referencia WHERE cod_ref = %s", $colname_referencia_ver);
$referencia_ver = mysql_query($query_referencia_ver, $conexion1) or die(mysql_error());
$row_referencia_ver = mysql_fetch_assoc($referencia_ver);
$totalRows_referencia_ver = mysql_num_rows($referencia_ver);

mysql_select_db($database_conexion1, $conexion1);
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
?>
<?php 
//************************** Definicion Tipo de Usuario
$id_ref=$_GET["int_ref_ac_rc"];
if($id_ref!='')
{
	echo $row_referencia_ver["version_ref"]; 
?>
<input name="version_ref[]" type="hidden" id="version_ref" value="<?php echo $row_referencia_ver["version_ref"];?>" size="20">
<?php
}
mysql_free_result($usuario);

mysql_free_result($cliente);

mysql_free_result($referencia_ver);
?>
