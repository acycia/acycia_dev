<?php require_once('Connections/conexion1.php'); ?>
<?php

if(isset($_GET['getClientId']) && isset($_GET['getRollo'])){
	
	 mysql_select_db($database_conexion1, $conexion1);
	//informacion general del turno y rollo
	$query_sql = "SELECT *,SUM(bolsas_r) AS bolsas_r, SUM(kilos_r) AS kilos, SUM(reproceso_r) AS reproceso, MAX(turno_r) AS turno_r,
MIN(numIni_r) AS numIni_r, MAX(numFin_r) AS numFin_r,
MIN(fechaI_r) AS fechaI_r, MAX(fechaF_r) AS fechaF_r,
TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM TblSelladoRollo WHERE id_op_r='".$_GET['getClientId']."' AND rollo_r='".$_GET['getRollo']."' GROUP BY rollo_r DESC";
	$res = mysql_query($query_sql, $conexion1) or die(mysql_error());	
	//desperdicio del rollo o turno
	$query_sql2 = "SELECT SUM(valor_desp_rd) AS desper FROM Tbl_reg_desperdicio WHERE op_rd='".$_GET['getClientId']."' AND int_rollo_rd='".$_GET['getRollo']."' AND id_proceso_rd='4' GROUP BY `int_rollo_rd` DESC";
	$res2 = mysql_query($query_sql2, $conexion1) or die(mysql_error());
	$inf2 = mysql_fetch_array($res2);
if ($inf2["desper"]==''){$inf2["desper"]=0;}
	//lamina del rollo o turno
	$query_sql3 = "SELECT SUM(valor_prod_rp) AS lamina FROM Tbl_reg_kilo_producido WHERE op_rp='".$_GET['getClientId']."' AND int_rollo_rkp='".$_GET['getRollo']."' AND id_rpp_rp IN (1406,1407,1655,1656,1657) GROUP BY `int_rollo_rkp` DESC";
	$res3 = mysql_query($query_sql3, $conexion1) or die(mysql_error());
	$inf3 = mysql_fetch_array($res3);
	$valor_lam=$inf3["lamina"]/2;
	//SE EVALUA LOS KILOS Y METROS DE IMPRESION PARA SELLADO Y SI NO HAY IMPRESION SE TRAEN DESDE EXTRUSION
 	$query_sql4 = "SELECT SUM(int_kilos_desp_rp) AS desp, SUM(int_total_kilos_rp) AS kilos, SUM(int_metro_lineal_rp) AS metros FROM Tbl_reg_produccion WHERE id_op_rp='".$_GET['getClientId']."' AND rollo_rp='".$_GET['getRollo']."' AND id_proceso_rp='2' GROUP BY rollo_rp DESC";
	$res4 = mysql_query($query_sql4, $conexion1) or die(mysql_error());
	$inf4 = mysql_fetch_array($res4);
	$numImp= mysql_num_rows($res4);
	if($numImp < '1')
	{ 
 	$query_sql4 = "SELECT rollo_r AS rollo, SUM(metro_r) AS metros, SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_r='".$_GET['getClientId']."' GROUP BY rollo_r ASC";
	$res4 = mysql_query($query_sql4, $conexion1) or die(mysql_error());
	$inf4 = mysql_fetch_array($res4);
 	} 
  if($inf = mysql_fetch_array($res)){
	  $horaentero= explode(" ",$inf["fechaI_r"]);
	  $fecha=$horaentero[0];
	  $hora=$horaentero[1];
      $fecha1 = $fecha."T".$hora;
	  $horaentero2= explode(" ",$inf["fechaF_r"]);
	  $fecha2=$horaentero2[0];
	  $hora2=$horaentero2[1];
      $fecha2 = $fecha2."T".$hora2;	
	echo "formObj.placa_rp.value = '".$inf["id_op_r"]."-".$inf["rollo_r"]."';\n";
	echo "formObj.int_kilos_prod_rp.value = '".$inf["kilos"]."';\n";  
    echo "formObj.bolsa_rp.value = '".$inf["bolsas_r"]."';\n";
	echo "formObj.lam1_rp.value = '".$valor_lam."';\n";
	echo "formObj.lam2_rp.value = '".$valor_lam."';\n";
	echo "formObj.turno_rp.value = '".$inf["turno_r"]."';\n";
	echo "formObj.str_maquina_rp.value = '".$inf["maquina_r"]."';\n";
	echo "formObj.int_cod_empleado_rp.value = '".$inf["cod_empleado_r"]."';\n";
	echo "formObj.int_cod_liquida_rp.value = '".$inf["cod_auxiliar_r"]."';\n";	
    echo "formObj.fecha_ini_rp.value = '".$fecha1."';\n";    
    echo "formObj.fecha_fin_rp.value = '".$fecha2."';\n"; 
	echo "formObj.int_metro_lineal_rp.value = '".$inf4["metros"]."';\n";	
	echo "formObj.total_horas_rp.value = '".$inf["TIEMPODIFE"]."';\n"; 
    echo "formObj.reproceso.value = '".$inf["reproceso"]."';\n"; 
	echo "formObj.rollo_rp.value = '".$inf["rollo_r"]."';\n";
	/*echo "formObj.int_total_rollos_rp.value = '".$inf["rollos"]."';\n";*/
	echo "formObj.n_ini_rp.value = '".$inf["numIni_r"]."';\n";	
	echo "formObj.n_fin_rp.value = '".$inf["numFin_r"]."';\n";
	echo "formObj.int_kilos_desp_rp.value = '".$inf2["desper"]."';\n";  
		        
  }else{
    
	echo "formObj.placa_rp.value = '';\n";
	echo "formObj.int_kilos_prod_rp.value = '';\n"; 
	echo "formObj.bolsa_rp.value = '';\n";	
	echo "formObj.lam1_rp.value = '';\n";
	echo "formObj.lam2_rp.value = '';\n";
	echo "formObj.turno_rp.value = '';\n"; 
	echo "formObj.str_maquina_rp.value = '';\n";	
	echo "formObj.int_cod_empleado_rp.value = '';\n";
	echo "formObj.int_cod_liquida_rp.value = '';\n";	
	echo "formObj.fecha_ini_rp.value = '';\n";
	echo "formObj.fecha_fin_rp.value = '';\n";
	echo "formObj.int_metro_lineal_rp.value = '';\n";	
	echo "formObj.total_horas_rp.value = '';\n";
	echo "formObj.reproceso.value = '';\n";
	echo "formObj.rollo_rp.value = '';\n";
	/*echo "formObj.int_total_rollos_rp.value = '';\n";*/
	echo "formObj.n_ini_rp.value = '';\n";
	echo "formObj.n_fin_rp.value = '';\n";  
	echo "formObj.int_kilos_desp_rp.value = '';\n";  
      
  }    
}
?>