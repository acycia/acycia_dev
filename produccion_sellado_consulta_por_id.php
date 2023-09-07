<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
//CODIGO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT codigo_empleado,nombre_empleado,tipo_empleado FROM empleado WHERE tipo_empleado='7' OR tipo_empleado='9' ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);
//CODIGO MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='3' ORDER BY maquina.nombre_maquina ASC";
$maquinas = mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
//consulta los datos del empleado por su id
$id_r=$_POST['id_r'];
mysql_select_db($database_conexion1, $conexion1); 
$query_rollos_edit = ("SELECT * FROM TblSelladoRollo WHERE TblSelladoRollo.id_r=$id_r");
$rollos_edit = mysql_query($query_rollos_edit, $conexion1) or die(mysql_error());
$row = mysql_fetch_array($rollos_edit);

//valores de las consultas
    $op=$row['id_op_r'];
	$ref=$row['ref_r'];
	$a=$row['turno_r'];
	$b=$row['cod_empleado_r'];
	$c=$row['cod_auxiliar_r'];
	$d=$row['fechaI_r'];
	$e=$row['fechaF_r'];
	$f=$row['numIni_r'];
	$g=$row['numFin_r'];
	$h=$row['bolsas_r'];
	$l=$row['kilos_r'];
	$k=$row['reproceso_r'];
	$i=$row['maquina_r'];
	$j=$row['rollo_r'];

//muestra los datos consultados en los campos del formulario
?>
<form name="form2" action="" method="POST" onsubmit="enviaDatosRollos(); return false"><!--SE ACTUALIZA EN produccion_actualizacion_sellado.php-->
  <table border="0">
    <tr>
        <td nowrap id="fuente1">TURNO N&deg;</td>
        <td nowrap id="fuente1">Operario</td>
        <td colspan="2" nowrap id="fuente1">Auxiliar</td>
        <td colspan="2" nowrap id="fuente1">Hora Inicio(hora militar)</td>
        <td colspan="2" nowrap id="fuente1">Hora Final(hora militar)</td>
        <td nowrap id="fuente1">Total/Tiempo</td>
       </tr>
      <tr>
        <td><input name="id_r" type="hidden" value="<?php echo $id_r; ?>" />
        <input name="ref_r" type="hidden" value="<?php echo $ref; ?>" />
        <input name="id_op_r" type="hidden" value="<?php echo $op; ?>" />
          <input type="number" name="turno_r" id="turno_r" min="1" max="6" style="width:40px" value="<?php echo $a?>" required /></td>
        <td><select name="cod_empleado_r" id="cod_empleado_r" style="width:100px">
            <option value=""<?php if (!(strcmp("", $b))) {echo "selected=\"selected\"";} ?>>Operario</option>
            <?php
			do {  
			?>
            <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $b))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
            <?php
			} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
			  $rows = mysql_num_rows($codigo_empleado);
			  if($rows > 0) {
				  mysql_data_seek($codigo_empleado, 0);
				  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
			  }
			?>
          </select></td>
        <td colspan="2"><select name="cod_auxiliar_r" id="cod_auxiliar_r" style="width:100px" >
            <option value=""<?php if (!(strcmp("", $c))) {echo "selected=\"selected\"";} ?>>Auxiliar</option>
            <?php
do {  
?>
            <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $c))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
            <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
          </select></td>
        <td colspan="2"><input name="fechaI_r" id="fecha_ini_rp" size="15" value="<?php echo muestradatelocal($d);?>" type="datetime-local" required/></td>
        <td colspan="2"><input name="fechaF_r" id="fecha_fin_rp" size="15" value="<?php echo muestradatelocal($e);?>" type="datetime-local" required onblur="validacion_select_fecha();" /></td>
        <td id="fuente2"><?php echo RestarFechas($d,$e); ?></td>
<tr>
         <td nowrap id="fuente1"># Rollo</td>
        <td nowrap id="fuente1">Nro. Inicio</td>
        <td nowrap id="fuente1">Nro. Final</td>
        <td nowrap id="fuente1">Cant. Bolsas</td>
        <td nowrap id="fuente1">Kilos<strong>
        <input type="hidden" size="2" name="peso_millar_op" id="peso_millar_op" value="<?php echo $row_ref['int_pesom_op']?>" />
        </strong></td>
        <td nowrap id="fuente1">Reproceso</td>
        <td nowrap id="fuente1">Maquina</td>
        <td nowrap id="fuente1">Eliminar</td>
        <td nowrap id="fuente1">&nbsp;</td>       
  </tr>
  <tr>      
        <td><input name="rollo_r" id="rollo_r" min="1" style="width:40px" type="number" value="<?php echo $j; ?>" required="required" /></td>
        <td><input name="numIni_r" type="text" required pattern="[0-9a-zA-Z]{0,20}" title="Este no parece un Dato válida verifique solo cadena entre letras y numeros sin espacios" id="numIni_r" size="12" onblur="conMayusculas(this);" value="<?php echo $f?>"/></td>
        <td><input name="numFin_r" type="text" required pattern="[0-9a-zA-Z]{0,20}" title="Este no parece un Dato válida verifique solo cadena entre letras y numeros sin espacios" id="numFin_r" onblur="conMayusculas(this)" value="<?php echo $g?>" size="12" /></td>
        <td><input name="bolsas_r" type="number" id="bolsas_r" style="width:60px" required="required" value="<?php echo $h ?>" onchange="metrosakilos();"/></td>
        <td><input name="kilos_r" type="number" id="kilos_r" style="width:60px"  min="0" step="0.01" required value="<?php echo $l ?>" onclick="metrosakilos();"/></td>
        <td><input name="reproceso_r" type="number" id="reproceso_r" style="width:60px" required="required" value="<?php echo $k ?>" /></td>
        <td><select name="maquina_r" size="1" id="maquina_r" style="width:60px">
          <option value=""<?php if (!(strcmp("", $i))) {echo "selected=\"selected\"";} ?>>Maquina</option>
          <?php
do {  
?>
          <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $i))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
          <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  } 
?>
        </select></td>
        <td><input name="borrar" type="checkbox" value="" onclick="if (form2.borrar.checked) { alert('Se eliminara tiempos, desperdicios y el rollo liquidado');}" /></td>
        <td><input type="submit" name="Submit" value="Actualizar" /></td>
        </tr>
  </table>
</form>