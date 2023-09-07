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
$query_cliente = "SELECT * FROM cliente ORDER BY nombre_c ASC";
$cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);
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
}
else
{
echo "VALIDADO";
}
}
//************************** Definicion Tipo de Usuario
if($_GET["tipo_usuario"] == 10)
{
?>
<select name="codigo_usuario">
        <?php
do {  
?>
        <option value="<?php echo $row_cliente['id_c']?>"><?php echo $row_cliente['nombre_c']?></option>
        <?php
} while ($row_cliente = mysql_fetch_assoc($cliente));
  $rows = mysql_num_rows($cliente);
  if($rows > 0) {
      mysql_data_seek($cliente, 0);
	  $row_cliente = mysql_fetch_assoc($cliente);
  }
?>
            </select>
<?php
}
mysql_free_result($usuario);

mysql_free_result($cliente);
?>