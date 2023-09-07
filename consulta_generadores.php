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

//CONSULTA LA EXISTENCIA DEL COSTO POR FECHA
$id_generadores_gv=$_GET['id_generadores_gv'];
/*$maquina_gv=$_GET['maquina_gv'];*/
$fecha1=$_GET['fecha_ini_gv'];
$fecha2=$_GET['fecha_fin_gv'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form method="POST" name="form1" action="<?php echo $editFormAction; ?>" >
<?php 
mysql_select_db($database_conexion1, $conexion1);
if ($id_generadores_gv!=''&& $fecha1!='' && $fecha2!='' && $fecha1 < $fecha2)
{
$result = mysql_query ("SELECT  id_generadores_gv,fecha_ini_gv, fecha_fin_gv  FROM Tbl_generadores_valor  WHERE id_generadores_gv='$id_generadores_gv' AND fecha_ini_gv >= '$fecha1' AND fecha_fin_gv <='$fecha2'");	
if (mysql_num_rows($result) !='')
{ ?> <div id="numero1"><strong> <?php echo "Ya se ingreso un registro con las mismas caracteristicas ! verifique";?><input name="envio" id="envio" type="hidden" value="1"> </strong></div>  <?php  }
else 
{ ?> <div id="acceso1"><strong> <?php echo "EL RANGO DE FECHAS ES CORRECTO PUEDE CONTINUAR";?> </strong></div> <?php }
}
else
if ($fecha1 > $fecha2 || $fecha1 == $fecha2){ 
?> <div id="numero1"><strong><?php echo " LA FECHA FINAL DEBE SER MAYOR A LA FECHA INICIAL !";?><input name="envio" id="envio" type="hidden" value="1"> </strong></div> <?php }
?>
</form>
</body>
</html>
<?php
mysql_free_result($usuario);
?>