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

$colname_submenu_select = "-1";
if (isset($_GET['id_menu'])) {
  $colname_submenu_select = (get_magic_quotes_gpc()) ? $_GET['id_menu'] : addslashes($_GET['id_menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_submenu_select = sprintf("SELECT * FROM submenu WHERE id_menu_submenu = %s ORDER BY id_submenu ASC", $colname_submenu_select);
$submenu_select = mysql_query($query_submenu_select, $conexion1) or die(mysql_error());
$row_submenu_select = mysql_fetch_assoc($submenu_select);
$totalRows_submenu_select = mysql_num_rows($submenu_select);
?>
<?php 
mysql_select_db($database_conexion1, $conexion1);  // Seleccionar la Base de Datos
//// busqueda de Submenu's
if (isset($_GET["id_menu"]))   
{ 
$resultado1 = mysql_query("SELECT * FROM submenu WHERE id_menu_submenu = '".$_GET["id_menu"]."'");
if (mysql_num_rows($resultado1) > 0)
{ ?>
<select name="submenu">
  <option value="">Seleccione</option>
  <?php
do {  
?>
  <option value="<?php echo $row_submenu_select['id_submenu']?>"><?php echo $row_submenu_select['nombre_submenu']?></option>
  <?php
} while ($row_submenu_select = mysql_fetch_assoc($submenu_select));
  $rows = mysql_num_rows($submenu_select);
  if($rows > 0) {
      mysql_data_seek($submenu_select, 0);
	  $row_submenu_select = mysql_fetch_assoc($submenu_select);
  }
?>
</select>
<?php
}
else
{
echo "CERO REGISTROS";
}
}?>
<?php
mysql_free_result($usuario);

mysql_free_result($submenu_select);
?>