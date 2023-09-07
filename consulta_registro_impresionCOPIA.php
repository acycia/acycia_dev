<?php require_once('Connections/conexion1.php'); ?>
<?php
if(isset($_GET['getClientId'])){
	
	mysql_select_db($database_conexion1, $conexion1);
	$query_sql = "SELECT * FROM TblImpresionRollo WHERE id_r='".$_GET['getClientId']."'";
	$res = mysql_query($query_sql, $conexion1) or die(mysql_error());	
    //DESPERDICIOS EN IMPRESION
	$query_sql2 = "SELECT COUNT(DISTINCT TblImpresionRollo.rollo_r) AS max_rolloI,SUM(TblImpresionRollo.metro_r) as metro, SUM(Tbl_reg_desperdicio.valor_desp_rd) as kilosD FROM TblImpresionRollo,Tbl_reg_desperdicio WHERE TblImpresionRollo.id_r='".$_GET['getClientId']."' AND TblImpresionRollo.id_op_r = Tbl_reg_desperdicio.op_rd AND TblImpresionRollo.fechaI_r = Tbl_reg_desperdicio.fecha_rd  AND Tbl_reg_desperdicio.id_proceso_rd='2'";
	$res2 = mysql_query($query_sql2, $conexion1) or die(mysql_error());
   //OPERAR
   if($inf = mysql_fetch_array($res)){
	  $horaentero= explode(" ",$inf["fechaI_r"]);
	  $fecha=$horaentero[0];
	  $hora=$horaentero[1];
      $fecha1 = $fecha."T".$hora;
	  $horaentero2= explode(" ",$inf["fechaF_r"]);
	  $fecha2=$horaentero2[0];
	  $hora2=$horaentero2[1];
      $fecha2 = $fecha2."T".$hora2;
	  
	  $total_kilos=$inf["kilos_r"]-$inf2["kilosD"];
	  
    echo "formObj.int_kilos_prod_rp.value = '".$inf["kilos_r"]."';\n";  
    echo "formObj.int_kilos_desp_rp.value = '".$inf2["kilosD"]."';\n";   
    echo "formObj.int_total_kilos_rp.value = '".$total_kilos."';\n"; 
	echo "formObj.int_cod_empleado_rp.value = '".$inf["cod_empleado_r"]."';\n";
	echo "formObj.int_cod_liquida_rp.value = '".$inf["cod_auxiliar_r"]."';\n";	
    echo "formObj.fecha_ini_rp.value = '".$fecha1."';\n";    
    echo "formObj.fecha_fin_rp.value = '".$fecha2."';\n";
	echo "formObj.int_metroxmin_rp.value = '".$inf["int_metroxmin_r"]."';\n";
	echo "formObj.int_metro_lineal_rp.value = '".$inf["metro_r"]."';\n";
	echo "formObj.rollo_rp.value = '".$inf["rollo_r"]."';\n";     
   }else{
    echo "formObj.int_total_kilos_rp.value = '';\n";  
	echo "formObj.int_kilos_desp_rp.value = '';\n";  
    echo "formObj.int_total_kilos_rp.value = '';\n"; 	
	echo "formObj.int_cod_empleado_rp.value = '';\n";
	echo "formObj.int_cod_liquida_rp.value = '';\n";	
    echo "formObj.fecha_ini_rp.value = '';\n";    
    echo "formObj.fecha_fin_rp.value = '';\n";
	echo "formObj.int_metroxmin_rp.value = '';\n";	  
	echo "formObj.int_metro_lineal_rp.value = '';\n";
	echo "formObj.rollo_rp.value = '';\n";      
  }    
}
?>