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
?>
<?php 
mysql_select_db($database_conexion1, $conexion1);  // Seleccionar la Base de Datos
//// busqueda del usuario
if (isset($_GET["usuario"]))   
{ 
$resultado1 = mysql_query("SELECT * FROM usuario WHERE usuario = '".$_GET["usuario"]."'");
if (mysql_num_rows($resultado1) > 0)
{ 
echo "EXISTE";
$id=1;
}
else
{
echo "VALIDADO";
$id=0;
}
}?>
<input name="id" type="hidden" id="id" value="<?php echo $id ?>" />
<?php
mysql_free_result($usuario);
?>