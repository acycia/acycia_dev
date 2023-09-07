<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php

$conexion = new ApptivaDB();
if(isset($_GET['getClientId'])&& $_GET['getClientId'] !=''){

  if($resultado=$conexion->buscarList("TblExtruderRollo","id_r",$_GET['getClientId'])){
 
    echo json_encode( $resultado); 

  } 
  exit();
}else{
    echo '';
}
 
/*if(isset($_GET['getClientId'])){
	
	mysql_select_db($database_conexion1, $conexion1);
	$query_sql = "SELECT rollo_r AS rollo, SUM(metro_r) AS metros, SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_r='".$_GET['getClientId']."' GROUP BY rollo_r ASC";
	$res = mysql_query($query_sql, $conexion1) or die(mysql_error());	
    //OPERAR 
   if($inf = mysql_fetch_array($res)){
   	   if($inf["rollo"]!=''){
        	echo "formObj.rollo_r.value = '".$inf["rollo"]."';\n";  
            echo "formObj.metro_r.value = '".$inf["metros"]."';\n"; 
        	echo "formObj.metro_r2.value = '".$inf["metros"]."';\n";
        	echo "formObj.kilos_r.value = '".$inf["kilos"]."';\n";
        	echo "formObj.int_total_kilos_rp.value = '".$inf["kilos"]."';\n";
            }else{ 
        	echo "formObj.rollo_r.value = '';\n"; 
            echo "formObj.metro_r.value = '';\n";
        	echo "formObj.metro_r2.value = '';\n"; 
        	echo "formObj.kilos_r.value = '';\n"; 		  
        	echo "formObj.int_total_kilos_rp.value = '';\n";
           }    

   	   }
}*/
?>