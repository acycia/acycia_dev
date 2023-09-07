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
$id_op=$_GET["id_op"];
$id_op_cierre=$_GET["id_op_cierre"];
$id_op_continua=$_GET["id_op_continua"];
//echo 'variable'.$str_numero_oc;
//ORDEN COMPRA
if ($id_op!='')
{
$resultado = mysql_query("SELECT * FROM Tbl_orden_produccion, Tbl_caract_proceso WHERE Tbl_orden_produccion.id_op='$id_op' AND  Tbl_orden_produccion.int_cod_ref_op=Tbl_caract_proceso.id_cod_ref_cp AND Tbl_orden_produccion.b_borrado_op='0'");
if ( !ereg("^[0-9a-zA-z-]{1,100}$", $_GET['id_op'])) {	  
{ ?> <div id="numero1"><strong> <?php echo "CARACTERES NO PERMITIDOS, NO DIGITE CARACTERES ESPECIALES NI ESPACIOS"; ?><input name="retorno_mensaje" type="hidden" value="1"> </strong></div> <?php }
}else		
if (mysql_num_rows($resultado) > 0)
{ ?> <div id="acceso2"><strong> <?php echo "LA ORDEN DE PRODUCCION SI EXISTE Y TIENE LAS MEZCLAS";?> <input name="retorno_mensaje" type="hidden" value="0"></strong></div> <?php }
else 
{ ?> <div id="numero2"><strong> <?php echo "ORDEN DE PRODUCCION NO EXISTE, O NO CONTIENE LAS MEZCLAS";?><input name="retorno_mensaje" type="hidden" value="1"> </strong></div> <?php }
}
if ($id_op_continua!='')
{
$resultado = mysql_query("UPDATE  Tbl_orden_produccion SET b_estado_op='4' WHERE id_op='$id_op_continua'");	
?> <div id="acceso2"><strong> <?php echo "LA O.P QUEDO EN ESTADO DE CONTINUIDAD";?></strong></div> <?php
}
if ($id_op_cierre!='')
{
$resultado = mysql_query("UPDATE  Tbl_orden_produccion SET b_estado_op='5' WHERE id_op='$id_op_cierre'");	
?> <div id="numero2"><strong> <?php echo "LA O.P QUEDO FINALIZADA";?></strong></div> <?php
}

?>
</body>
</html>
<?php
mysql_free_result($usuario);
?>