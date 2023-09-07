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

$colname_select_submenu = "-1";
if (isset($_GET['menu'])) {
  $colname_select_submenu = (get_magic_quotes_gpc()) ? $_GET['menu'] : addslashes($_GET['menu']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_select_submenu = sprintf("SELECT * FROM submenu WHERE id_menu_submenu = %s ORDER BY id_submenu ASC", $colname_select_submenu);
$select_submenu = mysql_query($query_select_submenu, $conexion1) or die(mysql_error());
$row_select_submenu = mysql_fetch_assoc($select_submenu);
$totalRows_select_submenu = mysql_num_rows($select_submenu);
?>
<?php 
mysql_select_db($database_conexion1, $conexion1);
//DATOS
$id_insumo=$_GET['id_insumo'];

//FICHA TECNICA
if($id_insumo != '')
{
$sqldato="SELECT * FROM insumo WHERE id_insumo='$id_insumo'";
$resultdato=mysql_query($sqldato);
if (mysql_num_rows($resultdato) > 0)
{ 
$peso=mysql_result($resultdato,0,'peso_insumo');
} 
?>
<input type="text" name="peso_caja" size="10" value="<?php echo $peso; ?>" onBlur="calcularft(form1.peso_millar.value,form1.unids_caja.value)">
<?php
}
?>
<?php
//Lista de Submenus de la pagina tipo_permisos.php
if (isset($_GET["menu"]))
{
if($_GET["menu"] == '0')
{
echo "SELECCIONE EL MENU";
}
else
{
$resultado1 = mysql_query("SELECT * FROM submenu WHERE id_menu_submenu = '".$_GET["menu"]."'");
if (mysql_num_rows($resultado1) > 0)
{ ?>
<select name="submenu">
  <option value="">Seleccione</option>
  <?php
do {  
?>
  <option value="<?php echo $row_select_submenu['id_submenu']?>"><?php echo $row_select_submenu['nombre_submenu']?></option>
  <?php
} while ($row_select_submenu = mysql_fetch_assoc($select_submenu));
  $rows = mysql_num_rows($select_submenu);
  if($rows > 0) {
      mysql_data_seek($select_submenu, 0);
	  $row_select_submenu = mysql_fetch_assoc($select_submenu);
  }
?>
</select>
<?php
}
else
{
echo "CERO REGISTROS";
}
}
}
?>
<?php
mysql_free_result($usuario);

mysql_free_result($select_submenu);
?>