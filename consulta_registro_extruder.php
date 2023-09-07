<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS //FIN
if(isset($_GET['getClientId'])){
	//getrollo importante esta variable ya que limita la sumas de kilos y metros
 mysql_select_db($database_conexion1, $conexion1);  



//TIEMPO OPTIMO NORMAL
 if(isset($_GET['getparcial']) && $_GET['getparcial'] > 1 ){
      $tiempoOptimoParcial = "AND rollo_r > " . $_GET['getrollo'];
      $metrosParcial = "AND rollo_r > " . $_GET['getrollo'];
 }else{
 	   $tiempoOptimoParcial = ""; 
 	   $metrosParcial = "";
 }
$query_sql2 = mysql_query ("SELECT TIMEDIFF(MAX(`fechaF_r`),  MIN(`fechaI_r`)) AS TIEMPODIFE_P FROM TblExtruderRollo WHERE id_op_r='".$_GET['getClientId']."' AND fechaF_r <= '".$_GET['getfechaF']."' $tiempoOptimoParcial  GROUP BY `fechaI_r` ASC"); 
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

//DATOS GENERALES DEL ROLLO SEA NORMAL
$query_sql = "SELECT cod_empleado_r, MAX(rollo_r) AS rollo, SUM(kilos_r) AS kilos, SUM(metro_r) AS metros, (fechaI_r) AS fechaI, MAX(fechaF_r) AS fechaF, TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM TblExtruderRollo WHERE id_op_r='".$_GET['getClientId']."' AND fechaF_r <= '".$_GET['getfechaF']."' $metrosParcial"; 
	$res = mysql_query($query_sql, $conexion1) or die(mysql_error());

     if($inf = mysql_fetch_array($res)){

		  $fechaFin=$_GET['getfechaF'];
		  $horaentero= explode(" ",$inf["fechaI"]);
		  $fecha=$horaentero[0]; 
		  $hora=$horaentero[1];
		  $fecha1 = $fecha."T".$hora; 

		  $horaentero2= explode(" ",$fechaFin);
		  $fecha2=$horaentero2[0];
		  $hora2=$horaentero2[1];
		  $fecha2 = $fecha2."T".$hora2;	
 		  

         $minuTH = ($minutos/60); 
   		$minuTH = round($minutos, 2); 
   		$kilosxhora= $horas.".".$minuTH;
   		$kilosxhora = round($totalkilos/$kilosxhora, 2);

      }
//FECHA INICIAL DEL ROLLO DONDE INICIA EL PARCIAL EXCLUSIVA SI ES PARCIAL
//$_GET['getrollo'] CON ESTE ROLLO SE SABE DONDE QUEDO EL PRIMEER PARCIAL
$query_fechaparcial = "SELECT cod_empleado_r, MAX(rollo_r) AS rollo, SUM(kilos_r) AS kilos, SUM(metro_r) AS metros, (fechaI_r) AS fechaI, MAX(fechaF_r) AS fechaF, TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE FROM TblExtruderRollo WHERE id_op_r='".$_GET['getClientId']."' AND fechaF_r <= '".$_GET['getfechaF']."' AND rollo_r > '".$_GET['getrollo']."' ";
	$res_fechaparcial = mysql_query($query_fechaparcial, $conexion1) or die(mysql_error());

     if($inf_fechaparcial = mysql_fetch_array($res_fechaparcial)){
 
        $KILOS_T_PARCIAL= number_format($inf_fechaparcial["kilos"], 2, '.', '');
        $horaentero= explode(" ",$inf_fechaparcial["fechaI"]);
        $fecha=$horaentero[0]; 
        $hora=$horaentero[1];
        $fecha_parcial = $fecha."T".$hora; 

        $minuTH = ($minutos/60); 
  		  $minuTH = round($minutos, 2); 
  		  $kilosxhora= $horas.".".$minuTH;
  		  $kilosxhora = round($totalkilos/$kilosxhora, 2);

     }
//FIN INICIAL PARCIAL

 
       if(isset($_GET['getparcial']) && $_GET['getparcial'] > 1 ){
       	 $KILOS_T= $KILOS_T_PARCIAL;
       	 $fechaInicial = $fecha_parcial;
       }else{
       	 $KILOS_T= number_format($inf["kilos"], 2, '.', '');
       	 $fechaInicial = $fecha1;
       }
 		 
	if($inf_fechaparcial || $inf ){

		echo "formObj.int_kilos_prod_rp.value = '".$KILOS_T."';\n";    
		echo "formObj.int_kilos_desp_rp.value = '0';\n";    
		echo "formObj.int_total_kilos_rp.value = '".$KILOS_T."';\n";
		echo "formObj.int_cod_empleado_rp.value = '".$inf["cod_empleado_r"]."';\n";
		echo "formObj.int_cod_liquida_rp.value = '".$inf["cod_auxiliar_r"]."';\n";		
		echo "formObj.fecha_ini_rp.value = '".$fechaInicial."';\n";    
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