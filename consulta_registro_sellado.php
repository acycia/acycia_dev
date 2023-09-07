<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?>
 
<?php

$conexion = new ApptivaDB();

if(isset($_GET['getClientId'])&&$_GET['getClientId']!=''){ 

	$registros=$conexion->llenaListas("TblImpresionRollo","WHERE id_r = ".$_GET['getClientId']." ","","id_r");
 
    	if($registros[0][0]>'1'){	
    		   $inf=$conexion->llenaListas("TblImpresionRollo","WHERE id_r = ".$_GET['getClientId']." ","","id_op_r, ref_r, rollo_r AS rollo, SUM(metro_r) AS metros, SUM(kilos_r) AS kilos");  
    	}else{
    		   $inf=$conexion->llenaListas("TblExtruderRollo","WHERE id_r = ".$_GET['getClientId']." ","","id_op_r, ref_r, rollo_r AS rollo, SUM(metro_r) AS metros, SUM(kilos_r) AS kilos");  
    	} 
 
        	$id_op=$inf[0][0];//op
    	if($inf[0][0]>'1')
    	{    
          $inf3=$conexion->llenarCampos("tbl_reg_produccion ","WHERE id_op_rp = '$id_op' AND id_proceso_rp='4' ","ORDER BY rollo_rp DESC LIMIT 1"," fecha_fin_rp, str_maquina_rp" );         
       
         	$horaentero2 = explode(" ",$inf3["fecha_fin_rp"]);         
         	$fecha2=$horaentero2[0];
         	$hora2=$horaentero2[1];
         	$maquina=$inf3["str_maquina_rp"];
            $operario=$inf3["cod_empleado_r"];
            $revisor=$inf3["cod_auxiliar_r"];

         	$inf4=$conexion->llenarCampos("TblSelladoRollo ","WHERE id_op_r ='$id_op'" ,"ORDER BY fechaF_r DESC LIMIT 1","numFin_r " );  
         	$numeracionIn=$inf4["numFin_r"]+1;   
            
            if(!$inf4){
              //si no se ha guardado ningun rollo que traiga numeracion de la o.p
              $inf5=$conexion->llenarCampos("tbl_orden_produccion","WHERE  id_op = '$id_op'  ", "ORDER BY id_op DESC ","numInicio_op");  
              $inf5["numInicio_op"];
            }

         	$fecha2 = $fecha2."T".$hora2;  
         	$placa=$id_op."-".$inf[0][2];   
 
          //agrego al array
            $inf[0]["placa_rp"]=$placa;
         	$inf[0]["str_maquina_rp"]=$maquina;
         	$inf[0]["fecha_ini_rp"]=$fecha2;
         	$inf[0]["n_ini_rp"]=$inf5["numInicio_op"]=='' ? $numeracionIn : $inf5["numInicio_op"];
            $inf[0]["numInicioControl"]=$inf5["numInicio_op"]=='' ? $numeracionIn : $inf5["numInicio_op"];
            $inf[0]["int_cod_empleado_rp"]=$operario;  
            $inf[0]["int_cod_liquida_rp"]=$revisor;       
 
         	echo json_encode($inf); 
    
     }
    
 } 


?>