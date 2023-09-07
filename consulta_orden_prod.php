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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php 
mysql_select_db($database_conexion1, $conexion1);
// DATOS
		$cod_r=$_GET["cod_r"];
		$caja=$_GET["caja"];  
		$paq=$_GET["paq"];    
		$medida=$_GET['medida'];
		
		if ($cod_r!=''&& $caja!=''&& $paq!=''&&$medida!='')
		{
		$sqlEditFD="UPDATE Tbl_egp SET unids_caja_egp='$caja',unids_paq_egp='$paq',marca_cajas_egp='$medida' WHERE n_egp='$cod_r'"; 
		$resultEditFD=mysql_query($sqlEditFD);
		$sqlEditOP="UPDATE Tbl_orden_produccion SET int_undxcaja_op='$caja', int_undxpaq_op='$paq' WHERE int_cod_ref_op='$cod_r'"; 
		$resultEditOP=mysql_query($sqlEditOP);
		?> 
		<div id="numero1"><strong> <?php echo "Los campos und x caja, und x paquete y medida de la caja se actualizo en la ref: $cod_r Y OP con exito"; ?> </strong></div>             
		<?php }else {?> <div id="acceso1"><strong> <?php echo "NO SE CAMBIO NINGUN REGISTRO"; ?> </strong></div> 
		<?php } exit();?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>