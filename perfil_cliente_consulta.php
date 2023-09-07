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
if (isset($_GET["nit_c"]))   // busqueda del nit del cliente
{ //1
if($_GET["uno"] == 1)  //ADD
{ //2
// Enviar la consulta para ver si el nit recibido existe
$resultado = mysql_query("SELECT * FROM cliente WHERE nit_c = '".$_GET["nit_c"]."'");
}  //-2
if($_GET["uno"] == 2)  //EDIT
{ //3
// Enviar la consulta para ver si el nit recibido existe
$resultado = mysql_query("SELECT * FROM cliente WHERE nit_c = '".$_GET["nit_c"]."' AND id_c <> '".$_GET["id"]."'");
}  //-3
if (mysql_num_rows($resultado) > 0)
{  //4
$existe="EXISTE";
$existe2=1;
}  //-4
else 
{  //5
$existe="VALIDADO";
$existe2=0;
}  //-5
?>
<input type="text" name="nit_c" value="<?php if($existe2=="1") { echo ""; } if($existe2=="0") { echo $_GET["nit_c"]; } ?>" size="20" onfocus="<?php if($existe2=="1") { ?> muestra1() <?php } if($existe2=="0") { ?> muestra2() <?php } ?>"><br>
<?php
echo $existe;
}  //-1
exit();
mysql_free_result($usuario);
?>