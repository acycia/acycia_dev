<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
if(isset($_GET['getClientId'])){
	//getrollo importante esta variable ya que limita la sumas de kilos y metros
 mysql_select_db($database_conexion1, $conexion1); 
 //SELECT *, SUM(kilos_r) AS kilos, SUM(metro_r) AS metros, (fechaI_r) AS fechaI, (fechaF_r) AS fechaF FROM TblExtruderRollo WHERE id_op_r='".$_GET['getClientId']."' AND fechaI_r BETWEEN '".$_GET['getfechaI']."' AND  '".$_GET['getfechaF']."'  AND fechaF_r BETWEEN '".$_GET['getfechaI']."' AND  '".$_GET['getfechaF']."' 
$query_sql = "SELECT cod_empleado_r, MAX(rollo_r) AS rollo, SUM(kilos_r) AS kilos, SUM(metro_r) AS metros, (fechaI_r) AS fechaI, MAX(fechaF_r) AS fechaF, TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM TblExtruderRollo WHERE id_op_r='".$_GET['getClientId']."' AND fechaF_r <= '".$_GET['getfechaF']."'";// AND rollo_r > '".$_GET['getrollo']."' 
	$res = mysql_query($query_sql, $conexion1) or die(mysql_error());
 
	//TIEMPO OPTIMO
$query_sql2 = mysql_query ("SELECT TIMEDIFF(MAX(`fechaF_r`),  MIN(`fechaI_r`)) AS TIEMPODIFE_P FROM TblExtruderRollo WHERE id_op_r='".$_GET['getClientId']."' AND fechaF_r <= '".$_GET['getfechaF']."'  GROUP BY `fechaI_r` ASC"); 
     $numOpmes=mysql_num_rows($query_sql2);
	 while ($row=mysql_fetch_array($query_sql2)) {  
		$horaopti =  ($row["TIEMPODIFE_P"]);
         list($h, $m, $s) = explode(':', $horaopti); 
          $secun += ($h * 3600) + ($m * 60) + $s; 
  		//paso de nuevo a formato horas
		$horas = floor($secun / 3600);
	     $minutos = floor(($secun - ($horas * 3600)) / 60);
	     $segundos = $secun - ($horas * 3600) - ($minutos * 60);
 
	     $horaoptima = $horas . ':' . $minutos . ":" . $segundos;
 
		//$horaoptima = date('H:s:i',mktime($secun)); 
	 }
   if($inf = mysql_fetch_array($res)){

		  $KILOS_T= number_format($inf["kilos"], 2, '.', '');
		  $fechaFin=$_GET['getfechaF'];
		  $horaentero= explode(" ",$inf["fechaI"]);
		  $fecha=$horaentero[0]; 
		  $hora=$horaentero[1];
		  $fecha1 = $fecha."T".$hora; 
		  $horaentero2= explode(" ",$fechaFin);
		  $fecha2=$horaentero2[0];
		  $hora2=$horaentero2[1];
		  $fecha2 = $fecha2."T".$hora2;	
 		  $totalkilos=($KILOS_T-0); 

           $minuTH = ($minutos/60); 
 		 $minuTH = round($minutos, 2); 
 		 $kilosxhora= $horas.".".$minuTH;
 		 $kilosxhora = round($totalkilos/$kilosxhora, 2);
		  
		echo "formObj.int_kilos_prod_rp.value = '".$KILOS_T."';\n";    
		echo "formObj.int_kilos_desp_rp.value = '0';\n";    
		echo "formObj.int_total_kilos_rp.value = '".$totalkilos."';\n";
		echo "formObj.int_cod_empleado_rp.value = '".$inf["cod_empleado_r"]."';\n";
		echo "formObj.int_cod_liquida_rp.value = '".$inf["cod_auxiliar_r"]."';\n";		
		echo "formObj.fecha_ini_rp.value = '".$fecha1."';\n";    
		echo "formObj.fecha_fin_rp.value = '".$fecha2."';\n"; 
		echo "formObj.rollo_rp.value = '".$inf["rollo"]."';\n"; 
		echo "formObj.tiempoOptimo_rp.value = '".$horaoptima."';\n"; 
		echo "formObj.total_horas_rp.value = '".$horaoptima."';\n"; 
		echo "formObj.int_kilosxhora_rp.value = '".$kilosxhora."';\n";      
		echo "formObj.int_metro_lineal_rp.value = '".$inf["metros"]."';\n";
	   }else{
		echo "formObj.int_kilos_prod_rp.value = '';\n";    
		echo "formObj.int_kilos_desp_rp.value = '';\n";    
		echo "formObj.int_total_kilos_rp.value = '';\n";	
		echo "formObj.int_cod_empleado_rp.value = '';\n";
		echo "formObj.int_cod_liquida_rp.value = '';\n";	
		echo "formObj.fecha_ini_rp.value = '';\n";    
		echo "formObj.fecha_fin_rp.value = '';\n";
		echo "formObj.tiempoOptimo_rp.value = '';\n";
		echo "formObj.total_horas_rp.value = '';\n"; 
		echo "formObj.int_kilosxhora_rp.value = '';\n"; 
		echo "formObj.rollo_rp.value = '';\n";      
		echo "formObj.int_metro_lineal_rp.value = '';\n";  
  }    
}
?>