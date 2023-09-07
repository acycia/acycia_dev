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
$id_r=$_GET["id_r"];  
$fecha1=$_GET['fechaI'];
$proce="4";
	  $horaentero= explode("T",$fecha1);
	  $fecha=$horaentero[0];
	  $hora=$horaentero[1];
      $FechaEdit = $fecha." ".$hora;	
 
if ($id_r!=''&&$FechaEdit!='')
{
$sqlregP="SELECT id_op_r,fechaI_r FROM TblSelladoRollo WHERE id_r='$id_r'";
$resultregP= mysql_query($sqlregP);
$numregP= mysql_num_rows($resultregP);	
 
 
if ($numregP > 0 ){

$id_op_r = mysql_result($resultregP, 0, 'id_op_r');	
$fecha_ini_r = mysql_result($resultregP, 0, 'fechaI_r');

$sqlEditFD="UPDATE TblSelladoRollo SET fechaI_r='$FechaEdit' WHERE id_r='$id_r'"; 
$resultEditFD=mysql_query($sqlEditFD); 	

$sqlreRP="UPDATE Tbl_reg_produccion SET fecha_ini_rp='$FechaEdit' WHERE fecha_ini_rp ='$fecha_ini_r' AND id_proceso_rp='$proce' AND id_op_rp='$id_op_r'";
$resultreRP= mysql_query($sqlreRP);
$numreRP= mysql_num_rows($resultreRP);
 
$sqlEditFT="UPDATE Tbl_reg_tiempo SET fecha_rt='$FechaEdit' WHERE fecha_rt='$fecha_ini_r' AND id_proceso_rt='$proce' AND op_rt='$id_op_r'"; 
$resultEditFT=mysql_query($sqlEditFT);

$sqlEditFP="UPDATE Tbl_reg_tiempo_preparacion SET fecha_rtp='$FechaEdit' WHERE fecha_rtp='$fecha_ini_r' AND id_proceso_rtp='$proce' AND op_rtp='$id_op_r'"; 
$resultEditFP=mysql_query($sqlEditFP);

$sqlEditFD="UPDATE Tbl_reg_desperdicio SET fecha_rd='$FechaEdit' WHERE fecha_rd='$fecha_ini_r' AND id_proceso_rd='$proce' AND op_rd='$id_op_r'"; 
$resultEditFD=mysql_query($sqlEditFD);

$sqlEditFK="UPDATE Tbl_reg_kilo_producido SET fecha_rkp='$FechaEdit' WHERE fecha_rkp='$fecha_ini_r' AND id_proceso_rkp='$proce' AND op_rp='$id_op_r'"; 
$resultEditFK=mysql_query($sqlEditFK);

?> 
<div id="acceso3"><strong> <?php echo "CAMBIO DE FECHA EXITOSO"; ?> </strong></div> <?php }
else {?> <div id="numero3"><strong> <?php echo "NO SE CAMBIO NINGUN REGISTRO"; ?> </strong></div> <?php } 
}else{ 
echo "NINGUN CAMBIO INGRESADO"; //primer if
}
exit();
?>

</body>
</html>
<?php
mysql_free_result($usuario);
?>