<?php require_once('Connections/conexion1.php'); ?>
<?php
//variables POST
mysql_select_db($database_conexion1, $conexion1);
    $id_op=$_POST['id_op_r'];
    $id_r=$_POST['id_r'];
	$bor=$_POST['borrar'];
	$ref_r=$_POST['ref_r'];
	$h=$_POST['turno_r'];
	$f=$_POST['cod_empleado_r'];
	$g=$_POST['cod_auxiliar_r'];
	$i=$_POST['fechaI_r'];
	$j=$_POST['fechaF_r'];
	$d=$_POST['numIni_r'];
	$e=$_POST['numFin_r'];
	$a=$_POST['bolsas_r'];
	$l=$_POST['kilos_r'];
	$k=$_POST['reproceso_r'];
	$c=$_POST['maquina_r'];
	$b=$_POST['rollo_r'];

//actualiza los datos del empleados
/*ELIMINAR INGRESO DE TURNOS EN SELLADO*/
if($bor == '1')
{	


$sqlsell="SELECT id_r,rollo_r,id_op_r FROM TblSelladoRollo WHERE id_r='$id_r'";
$resultsell= mysql_query($sqlsell);
$numsell= mysql_num_rows($resultsell);
if($numsell >='1')
{
$id_rol= mysql_result($resultsell,0, 'id_r');
$id_op= mysql_result($resultsell,0, 'id_op_r');
$rollo_r = mysql_result($resultsell,0, 'rollo_r');
$sqlrollo="DELETE FROM TblSelladoRollo WHERE id_r = '$id_r'";
$resultrollo=mysql_query($sqlrollo);
//desperdicios
$sqldes="DELETE FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND int_rollo_rd='$rollo_r' AND id_proceso_rd=4";
$resultdes=mysql_query($sqldes);
$sqltiempo="DELETE FROM Tbl_reg_tiempo WHERE op_rt='$id_op' AND int_rollo_rt='$rollo_r' AND id_proceso_rt=4";
$resulttiempo=mysql_query($sqltiempo);
$sqltiempop="DELETE FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$id_op' AND int_rollo_rtp='$rollo_r' AND id_proceso_rtp=4";
$resulttiempop=mysql_query($sqltiempop);
$sqlkilos="DELETE FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND int_rollo_rkp='$rollo_r' AND id_proceso_rkp=4";
$resultkilos=mysql_query($sqlkilos);
//liquidado
$sqlliquidado="DELETE FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND rollo_rp='$rollo_r' AND id_proceso_rp='4'";
$resultliquidado=mysql_query($sqlliquidado); 
 include("location:produccion_sellado_stiker_rollo_add.php?id_op_r='$id_op'");
}
}else{
	/*ACTUALIZAR LAS ENTRADAS EN EL INVENTARIO*/ 
	$updateSQL = "UPDATE TblInventarioListado SET Entrada=(Entrada - SaldoInicial)+ $a WHERE Cod_ref = $ref_r";
    mysql_query ($updateSQL, $conexion1);
  
    $updateSQL1="UPDATE TblSelladoRollo SET bolsas_r='$a', kilos_r='$l', reproceso_r='$k', rollo_r='$b', maquina_r='$c', numIni_r='$d', numFin_r='$e', cod_empleado_r='$f', cod_auxiliar_r='$g', turno_r='$h', fechaI_r='$i', fechaF_r='$j' WHERE id_r='$id_r'";
    mysql_query ($updateSQL1, $conexion1);
include("location:produccion_sellado_stiker_rollo_add.php?id_op_r='$id_op'");
//include('produccion_sellado_consulta_stiker.php');
  
}
?>