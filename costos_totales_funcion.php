<?php /*PROGRAMACION ESTRUCTURADA FUNCIONES PHP*/
require_once('Connections/conexion1.php'); 
//--------------CALCULOS Y FORMULAS--------------------//
function FormulaCostos($fecha1, $fecha2){
mysql_select_db($database_conexion1, $conexion1);
$sqlemp="SELECT * FROM TblProcesoEmpleado WHERE fechainicial_empleado BETWEEN '$fecha1'
AND '$fecha2' AND fechafinal_empleado BETWEEN '$fecha1' AND '$fecha2' ORDER BY proceso_empleado ASC";	
$resultemp=mysql_query($sqlemp); 
$row_manodeobra = mysql_fetch_assoc($resultemp);
$totalRows_manodeobra = mysql_num_rows($resultemp);

	if ($row_manodeobra>='1') { 


do {
$proceso=$row_manodeobra['proceso_empleado']; 	
	$sqlpro="SELECT id_pa,valor_pa FROM TblProcesoAjuste WHERE id_proceso_pa=$proceso";
	$resultpro=mysql_query($sqlpro); $numpro=mysql_num_rows($resultpro);
	if ($numpro>='1') { 	
	$ajuste=mysql_result($resultpro,0,'valor_pa');
	 }
	 
$empleados=$row_manodeobra['proceso_empleado']; 	
	$sqlemp="SELECT COUNT(proceso_empleado) AS empleados FROM TblProcesoEmpleado WHERE proceso_empleado=$empleados";
	$resultemp=mysql_query($sqlemp); $numemp=mysql_num_rows($resultemp);
	if ($numemp>='1') { 
	$cant_empleado=mysql_result($resultemp,0,'empleados');
	}
	
$maquinas=$row_manodeobra['proceso_empleado']; 
	$sqlmaq="SELECT COUNT(proceso_maquina) AS maq FROM maquina WHERE proceso_maquina=$maquinas";
	$resultmaq=mysql_query($sqlmaq); 
	$nummaq=mysql_num_rows($resultmaq);
	if ($nummaq>='1') { 
	$Tmaquinas=mysql_result($resultmaq,0,'maq');
	}

	$p_empleado=$row_manodeobra['proceso_empleado']; 	
	$sqlchm="SELECT COUNT(proceso_empleado) AS registros, SUM(costo_empleado) AS costo FROM TblProcesoEmpleado WHERE proceso_empleado=$p_empleado";
	$resultchm=mysql_query($sqlchm); $numchm=mysql_num_rows($resultchm);
	if ($numchm>='1') { 
	$registros=mysql_result($resultchm,0,'registros'); 
	$dias_empleado=$row_manodeobra['dias_empleado'];//pendiente no imprime
	$cost=mysql_result($resultchm,0,'costo'); 
	$c_HoraM=$cost/($dias_empleado*8);
	$costoHoraM = ($c_HoraM/$registros); //para la operacion * operario x turno y maquina
	}
	
	$proceso=$row_manodeobra['proceso_empleado']; 	
	$sqlpro="SELECT id_pa,valor_pa FROM TblProcesoAjuste WHERE id_proceso_pa=$proceso";
	$resultpro=mysql_query($sqlpro); $numpro=mysql_num_rows($resultpro);
	if ($numpro>='1') { 
	//$id_pa=mysql_result($resultpro,0,'id_pa');
	$valor_pa=mysql_result($resultpro,0,'valor_pa'); 
	 }
	 
	$operarioxMaqu=$cant_empleado/$Tmaquinas;
	$operarioxTurnoM=($operarioxMaqu/$valor_pa);
	$costoxProceXLineaXT=($costoHoraM*$operarioxTurnoM);
	return $costoxProceXLineaXT;
			
} while ($row_manodeobra = mysql_fetch_assoc($resultemp));

	}
}
?>