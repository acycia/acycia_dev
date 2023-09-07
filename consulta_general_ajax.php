<?php require_once('Connections/conexion1.php'); ?>
<?php 
$valorswitch=$_GET['valorswitch'];
$valor1=$_GET['valor1'];
$valor2=$_GET['valor2'];
$valor3=$_GET['valor3'];

switch($valorswitch) {
	  case 1:
	mysql_select_db($database_conexion1, $conexion1);
	$query_sql = "SELECT SUM(`int_total_f`) AS cantidadFaltan FROM `Tbl_faltantes` WHERE `id_op_f` = '".$_GET['valor1']."' AND `int_inicial_f` BETWEEN '".$_GET['valor2']."' AND '".$_GET['valor3']."' AND `int_final_f` BETWEEN '".$_GET['valor2']."' AND '".$_GET['valor3']."'";
	$res = mysql_query($query_sql, $conexion1) or die(mysql_error());	  
    if($inf = mysql_fetch_array($res)){	
    $theValue=$inf["cantidadFaltan"];
	  echo "Faltantes en el turno= ".$theValue; 
	  return $theValue;
     }
    break;  	  
     }
?>