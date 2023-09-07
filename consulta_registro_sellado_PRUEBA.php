<?php require_once('Connections/conexion1.php'); ?>
<?php
if(isset($_GET['getClientId'])){
	//TRAE LOS DE IMPRESION
	mysql_select_db($database_conexion1, $conexion1);
	$query_sql = "SELECT id_op_rp, id_ref_rp, rollo_rp AS rollo, SUM(int_metro_lineal_rp) AS metros, SUM(int_total_kilos_rp) AS kilos FROM Tbl_reg_produccion WHERE id_rp ='".$_GET['getClientId']."' ORDER BY rollo_rp ASC";
	$res = mysql_query($query_sql, $conexion1) or die(mysql_error()); 

    if($inf = mysql_fetch_array($res)){
 
    $id_op=$inf["id_op_rp"]; 
	$id_ref=$inf["id_ref_rp"];
	$metros=$inf["metros"]; 
	
    $query_sql3 = "SELECT fecha_fin_rp, str_maquina_rp FROM Tbl_reg_produccion WHERE id_op_rp = '$id_op' AND id_proceso_rp='4' ORDER BY rollo_rp DESC LIMIT 1";
	$res3 = mysql_query($query_sql3, $conexion1) or die(mysql_error());	
	$inf3 = mysql_fetch_array($res3);
 	$maquina=$inf3["str_maquina_rp"];
    $horaentero2 = explode(" ",$inf3["fecha_fin_rp"]);

    $query_sql4 = "SELECT numFin_r FROM TblSelladoRollo WHERE id_op_r='$id_op' ORDER BY fechaF_r DESC LIMIT 1";
	$res4 = mysql_query($query_sql4, $conexion1) or die(mysql_error());	
	$inf4 = mysql_fetch_array($res4);
	$numeracionIn=$inf4["numFin_r"]+1;
		
	$fecha2=$horaentero2[0];
	$hora2=$horaentero2[1];
    $fecha2 = $fecha2."T".$hora2;



  //SI ES PARCIAL
/* if($parcial==1){
 	$query_sql5 = "SELECT *,SUM(bolsas_r) AS bolsas_r, SUM(metro_r) AS metros, SUM(kilos_r) AS kilos, SUM(reproceso_r) AS reproceso, 
MIN(fechaI_r) AS fechaI_r, MAX(fechaF_r) AS fechaF_r,
TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM TblSelladoRollo WHERE id_op_r='$id_op' AND rollo_r='$ROLLO' GROUP BY rollo_r DESC";
	$res5 = mysql_query($query_sql5, $conexion1) or die(mysql_error());	
	$inf = mysql_fetch_array($res5);
	
	  $inf["kilos"];
	  $inf["rollo"];
	  $inf["metros"];
 	  $horaentero= explode(" ",$inf5["fechaI_r"]);
	  $fecha=$horaentero[0];
	  $hora=$horaentero[1];
      $fecha1 = $fecha."T".$hora;
	  $horaentero2= explode(" ",$inf5["fechaI_r"]);
	  $fecha2=$horaentero2[0];
	  $hora2=$horaentero2[1];
      $fecha1 = $fecha2."T".$hora2;	

 }*/

	  
	$placa=$inf["id_op_rp"]."-".$inf["rollo"];
    echo "formObj.placa_rp.value = '".$placa."';\n";  
    echo "formObj.int_kilos_prod_rp.value = '".$inf["kilos"]."';\n";
	echo "formObj.int_total_kilos_rp.value = '".$inf["kilos"]."';\n";
	echo "formObj.kiloimpre.value = '".$inf["kilos"]."';\n";  
	echo "formObj.str_maquina_rp.value = '".$maquina."';\n"; 
	echo "formObj.fecha_ini_rp.value = '".$fecha2."';\n";   
    echo "formObj.rollo_rp.value = '".$inf["rollo"]."';\n"; 
	echo "formObj.rollo_rp.value = '".$inf["rollo"]."';\n";  
	echo "formObj.metro_r.value = '".$inf["metros"]."';\n"; 
	echo "formObj.metro_r2.value = '".$inf["metros"]."';\n";
	echo "formObj.n_ini_rp.value = '".$numeracionIn."';\n"; 
     
  }else{
	echo "formObj.placa_rp.value = '';\n";   
    echo "formObj.int_kilos_prod_rp.value = '';\n";
	echo "formObj.int_total_kilos_rp.value = '';\n"; 
	echo "formObj.kiloimpre.value = '';\n";  
	echo "formObj.str_maquina_rp.value = '';\n";
	echo "formObj.fecha_ini_rp.value = '';\n";  
    echo "formObj.rollo_rp.value = '';\n"; 
 	echo "formObj.metro_r.value = '';\n";
    echo "formObj.metro_r2.value = '';\n"; 
	echo "formObj.n_ini_rp.value = '';\n"; 
  }    
}
?>