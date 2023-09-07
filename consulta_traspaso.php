<?php require_once('Connections/conexion1.php'); ?>
<?php 
  
 if(isset($_GET['getClientId'])){
	 
	$divide = explode ('-',$_GET['getClientId']);
	$id_r=$divide[0];
	$proceso=$divide[1];
	 
	 
	 
	 if($proceso == '1')
	 {$tabla ="TblExtruderRollo";
	 }else if($proceso == '2'){
	  $tabla ="TblImpresionRollo";
	 }
 	mysql_select_db($database_conexion1, $conexion1);
	$query_sql = "SELECT id_op_r AS op, rollo_r AS rollo, kilos_r  AS kilos FROM $tabla WHERE id_r='$id_r'";
	$res = mysql_query($query_sql, $conexion1) or die(mysql_error());
    
	
 //OPERAR
	// $inf["kilos"] 
   if($inf = mysql_fetch_array($res)){
 
    $op = $inf["op"];
	$rollo_num = $inf["rollo"];//para impresion
   if($proceso == '1')
	 {
 	  /*$sqlstand="SELECT COUNT($tabla.rollo_r) AS rollos, SUM(Tbl_reg_desperdicio.valor_desp_rd) AS kilosd FROM $tabla,Tbl_reg_desperdicio WHERE $tabla.id_op_r='$op' AND $tabla.id_op_r=Tbl_reg_desperdicio.op_rd AND Tbl_reg_desperdicio.id_proceso_rd = '$proceso'";
	  $resultstand= mysql_query($sqlstand);
	  $numstand = mysql_num_rows($resultstand);
	  
	  $rollos = mysql_result($resultstand, 0, 'rollos');
	  $kilosd = mysql_result($resultstand, 0, 'kilosd');
	  $tkilosd=($kilosd / $rollos);
	  $tkilos=($inf["kilos"]-$tkilosd);*/  
	  $tkilos=($inf["kilos"]);//el rollo ya esta guardado sin desperdicio en extruder
	  }else if($proceso == '2'){
 	  $sqlstand="SELECT Tbl_reg_desperdicio.valor_desp_rd AS kilosd FROM $tabla,Tbl_reg_desperdicio WHERE $tabla.id_op_r='$op' AND Tbl_reg_desperdicio.int_rollo_rd='$rollo_num' AND $tabla.id_op_r=Tbl_reg_desperdicio.op_rd AND Tbl_reg_desperdicio.id_proceso_rd = '$proceso'";
	  $resultstand= mysql_query($sqlstand);
	  $numstand = mysql_num_rows($resultstand);	 
	  
 	  $kilosd = mysql_result($resultstand, 0, 'kilosd'); 
	  $tkilos=($inf["kilos"]-$kilosd);    
	 }
 	   	
     
  
	echo "formObj.kilo_origen.value = '".$tkilos."';\n"; 
	echo "formObj.kilo_destino.value = '".$tkilos."';\n";   

    }else{ 
	echo "formObj.kilo_origen.value = '';\n"; 
    echo "formObj.kilo_destino.value = '';\n"; 
   }    
}
  
?>