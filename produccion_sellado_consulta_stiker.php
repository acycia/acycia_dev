<?php require_once('Connections/conexion1.php'); ?>
<?php
//consulta todos los empleados
$id_op_r=$_GET['id_op_r'];
mysql_select_db($database_conexion1, $conexion1); 
$query_rollo_edit = ("SELECT * FROM TblSelladoRollo WHERE TblSelladoRollo.id_op_r='$id_op_r'");
$rollo_edit = mysql_query($query_rollo_edit, $conexion1) or die(mysql_error());
$row_rollo_edit = mysql_fetch_assoc($rollo_edit);
$totalRows_rollo_edit = mysql_num_rows($rollo_edit);

//muestra los datos consultados
//haremos uso de tabla para tabular los resultados
echo 'var:'.$id_op_r;
?>
<table>
<tr>
  <td nowrap id="fuente1">EDITAR</td>
        <td nowrap id="fuente1">TURNO N&deg;</td>
        <td nowrap id="fuente1">Operario</td>
        <td nowrap id="fuente1">Auxiliar</td>
        <td nowrap id="fuente1">Hora Inicio(hora militar)</td>
        <td nowrap id="fuente1">Hora Final(hora militar)</td>
        <td nowrap id="fuente1">Nro. Inicio</td>
        <td nowrap id="fuente1">Nro. Final</td>
        <td nowrap id="fuente1">Cant. Bolsas</td>
        <td nowrap id="fuente1">Kilos</td>
        <td nowrap id="fuente1">Reproceso kg</td>
        <td nowrap id="fuente1">Maquina</td>
      </tr>

<?php
//while ($row_rollo_sellado = mysql_fetch_assoc($rollo_sellado)); 
do{
	echo "	<tr>";
	//mediante el evento onclick llamaremos a la funcion PedirDatos(), la cual tiene como parametro
	//de entrada el ID del empleado
	echo " 		<td id='fuente1'><a style=\"text-decoration:underline;cursor:pointer;\" onclick=\"pedirDatos('".$row_rollo_edit['id_r']."')\">Editar</a></td>";
	echo " 		<td id='fuente2'>".$row_rollo_edit['turno_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['cod_empleado_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['cod_auxiliar_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['fechaI_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['fechaF_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['numIni_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['numFin_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['bolsas_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['kilos_r']."</td>";
	echo " 		<td id='fuente1'>".$row_rollo_edit['reproceso_r']."</td>";	
	echo " 		<td id='fuente1'>".
	  $id_maq=$row_rollo_edit['maquina_r'];
	  $sqlmaq='SELECT * FROM maquina WHERE id_maquina=$id_maq';
	  $resultmaq= mysql_query($sqlmaq);
	  $nummaq= mysql_num_rows($resultmaq);
	  if($nummaq >='1')
	  { 
	  $nombremaq = mysql_result($resultmaq, 0, 'nombre_maquina');echo $nombremaq; 
	  }"</td>";
	echo "	</tr>";
}while($row_rollo_edit = mysql_fetch_assoc($rollo_edit))
?>
</table>