<?php require_once('Connections/conexion1.php'); ?>
<?php 
 $id_op_e=$_GET['id_op'];
 $fecha_e=$_GET['fecha'];
 $hora_e=$_GET['hora'];
mysql_select_db($database_conexion1, $conexion1);
$query_existente = "SELECT * FROM Tbl_reg_produccion WHERE id_op_rp='$id_op_e' AND id_proceso_rp='1' AND fecha_ini_rp='$fecha_e' AND hora_ini_rp='$hora_e'";
$existente= mysql_query($query_existente, $conexion1) or die(mysql_error());
$row_existente= mysql_fetch_assoc($existente);
$totalRows_existente = mysql_num_rows($existente);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" onSubmit="return validacion_select();">
  <tr>
  <td>
  <table>
<tr>

<td colspan="2" id="fuente1">Kilos Producidos </td>
    <td colspan="11" id="fuente1"><input type="number" name="int_kilos_prod_rp" id="int_kilos_prod_rp" min="0"step="any"size="12" required="required" style="width:80px"  autofocus="autofocus" readonly="readonly" onblur="getSumP();" onclick="return validacion_select();" value="<?php echo $row_existente['int_kilos_prod_rp'] ?>"/>
      Kilos Desperdiciados
      <input type="number" name="int_kilos_desp_rp" id="int_kilos_desp_rp" min="0"step="any"size="12" required="required" readonly="readonly" style="width:80px"  onclick="getSumD();getSumT();" value="<?php echo $row_existente['int_kilos_desp_rp'] ?>"/>
      Kilos Reales:
      <input type="number" name="int_total_kilos_rp" id="int_total_kilos_rp" min="0"step="any"size="12" required="required" readonly="readonly" style="width:80px"  onblur="getSumT();" value="<?php echo $row_existente['int_total_kilos_rp'] ?>"/></td>
  </tr>
  <tr>
    <td colspan="2" id="dato1"></td>
    <td colspan="3" id="dato1"></td>
    <td colspan="6" id="dato1"></td>
    <td colspan="2" id="dato1"></td>
  </tr>
  <tr id="tr1">
    <td colspan="13" id="titulo4">Tiempos y Desperdiciados</td>
  </tr>
  <tr>
    <td colspan="13" id="fuente2"><a href="javascript:verFoto('produccion_regist_extru_kilos_prod.php?id_op=<?php echo $row_orden_produccion['id_op'] ?>','820','470')">
      <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Detalle Kilos Producidos"/>
      </a><a href="javascript:verFoto('produccion_registro_extrusion_detalle_add.php?id_op=<?php echo $row_orden_produccion['id_op'] ?>','820','270')">
        <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Tiempos Desperdicio"/>
        </a>
      <input type="button" name="check_sh" id="check_sh2" value="Mostrar" onclick="mostrardiv1()"/>
      <input type="button" value="Ocultar" onclick="ocultardiv1()" /></td>
  </tr>
  <tr>
    <td colspan="4" id="fuente2">&nbsp;</td>
    <td colspan="2" id="fuente2">&nbsp;</td>
    <td colspan="10" id="fuente2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="13" id="fuente2"><table  width="100%"  border="0" id="flotante">
      <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Tiempos Muertos- Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Tiempos Muertos- Minutos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($k=0;$k<=$totalRows_tiempoMuerto-1;$k++) { ?>
      <tr>
        <td id="detalle2"><input name="id_rpt_rt[]" type="hidden" id="id_rpt_rt[]" value="<?php $var=mysql_result($tiempoMuerto,$k,id_rpt_rt); echo $var; ?>" size="6"/>
          <input name="valor_tiem_rt[]" type="hidden" size="6" value="<?php $var=mysql_result($tiempoMuerto,$k,valor_tiem_rt); echo $var; ?>"/>
          <?php $var=mysql_result($tiempoMuerto,$k,id_rpt_rt); 
	  $id_tm=$var;
	  $sqltm="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
	  $resulttm= mysql_query($sqltm);
	  $numtm= mysql_num_rows($resulttm);
	  if($numtm >='1')
	  { 
	  $nombre = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombre; }?></td>
        <td id="detalle2"><?php $var2=mysql_result($tiempoMuerto,$k,valor_tiem_rt); echo $var2; ?></td>
        <td id="detalle2"><a href="javascript:eliminar_rt('id_rt',<?php $delrt=mysql_result($tiempoMuerto,$k,id_rt); echo $delrt; ?>,'produccion_registro_extrusion_add.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <?php } ?>
      <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Tiempos Preparacion - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Tiempos Preparacion - Minutos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($x=0;$x<=$totalRows_tiempoPreparacion-1;$x++) { ?>
      <tr>
        <td id="detalle2"><input name="id_rpt_rtp[]" type="hidden" id="id_rpt_rtp[]" value="<?php $var=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); echo $var; ?>" size="6"/>
          <input name="valor_prep_rtp[]" type="hidden" size="6" value="<?php $var=mysql_result($tiempoPreparacion,$x,valor_prep_rtp); echo $var; ?>"/>
          <?php $var=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); 
	  $id_rtp=$var;
	  $sqlrtp="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
	  $resultrtp= mysql_query($sqlrtp);
	  $numrtp= mysql_num_rows($resultrtp);
	  if($numrtp >='1')
	  { 
	  $nombre = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombre; }?></td>
        <td id="detalle2"><?php $var2=mysql_result($tiempoPreparacion,$x,valor_prep_rtp); echo $var2; ?></td>
        <td id="detalle2"><a href="javascript:eliminar_rp('id_rp',<?php $delrp=mysql_result($tiempoPreparacion,$x,id_rt); echo $delrp; ?>,'produccion_registro_extrusion_add.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <?php } ?>
      <?php if($row_desperdicio['id_rpd_rd']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Desperdicios - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Desperdicios - Kilos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($i=0;$i<=$totalRows_desperdicio-1;$i++) { ?>
      <tr>
        <td id="detalle2"><input name="id_rpd_rd[]" type="hidden" id="id_rpd_rd[]" value="<?php $vd=mysql_result($desperdicio,$i,id_rpd_rd); echo $vd; ?>" size="6"/>
          <input name="valor_desp_rd[]" type="hidden" id="valor_desp_rd[]" value="<?php $vd2=mysql_result($desperdicio,$i,valor_desp_rd); echo $vd2; ?>" size="6" onblur="getSumD();"/>
          <?php $var1=mysql_result($desperdicio,$i,id_rpd_rd); 
	  $id_rpd=$var1;
	  $sqlrtd="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
	  $resultrtd= mysql_query($sqlrtd);
	  $numrtd= mysql_num_rows($resultrtd);
	  if($numrtd >='1')
	  { 
	  $nombre2 = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombre2; }?></td>
        <td id="detalle2"><?php $var3=mysql_result($desperdicio,$i,valor_desp_rd); echo $var3; ?></td>
        <td id="detalle2"><a href="javascript:eliminar_rd('id_rd',<?php $delrd=mysql_result($desperdicio,$i,id_rd); echo $delrd; ?>,'produccion_registro_extrusion_add.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <?php } ?>
      <?php if($row_producido['id_rpp_rp']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Producido - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Producido - Kilos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($y=0;$y<=$totalRows_producido-1;$y++) { ?>
      <tr>
        <td id="detalle2"><input name="id_rpp_rp[]" type="hidden" id="id_rpp_rp[]" value="<?php $vd=mysql_result($producido,$y,id_rpp_rp); echo $vd; ?>" size="6"/>
          <input name="valor_prod_rp[]" type="hidden" id="valor_prod_rp[]" value="<?php $vd2=mysql_result($producido,$y,valor_prod_rp); echo $vd2; ?>" size="6" onblur="getSumP();"/>
          <?php $prod=mysql_result($producido,$y,id_rpp_rp); 
	  $id_rpp=$prod;
	  $sqlri="SELECT * FROM insumo WHERE id_insumo='$id_rpp' AND clase_insumo='4'";
	  $resultri= mysql_query($sqlri);
	  $numri= mysql_num_rows($resultri);
	  if($numri >='1')
	  { 
	  $nombre3 = mysql_result($resultri, 0, 'descripcion_insumo'); echo $nombre3; }?></td>
        <td id="detalle2"><?php $var4=mysql_result($producido,$y,valor_prod_rp); echo $var4; ?></td>
        <td id="detalle2"><a href="javascript:eliminar_ip('id_ip',<?php $delip=mysql_result($producido,$y,id_rkp); echo $delip; ?>,'produccion_registro_extrusion_add.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <?php } ?>
    </table></td>
  </tr>
  <tr id="tr1">
    <td colspan="2" id="fuente1">Maquina</td>
    <td colspan="3" id="fuente1"><select name="str_maquina_rp" id="str_maquina_rp">
      <?php
do {  
?>
      <option value="<?php echo $row_maquinas['id_maquina']?>"><?php echo $row_maquinas['nombre_maquina']?></option>
      <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
    </select></td>
    <td colspan="8" id="fuente1"><select name="int_cod_empleado_rp" id="operario" onblur="if(form1.int_cod_empleado_rp.value=='') { alert('Debe Seleccionar quien Monta')}" style="width:120px">
      <option value="">Montaje</option>
      <option value="0">Ninguno</option>
      <?php
do {  
?>
      <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"><?php echo $row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
      <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
    </select>
      -
      <select name="int_cod_liquida_rp" id="revisor" onblur="if(form1.int_cod_liquida_rp.value=='') { alert('Debe Seleccionar quien Liquida')}" style="width:120px">
        <option value="">Liquida</option>
        <option value="0">Ninguno</option>
        <?php
do {  
?>
        <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"><?php echo $row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
        <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
      </select></td>
  </tr>
  <tr>
    <td colspan="2" id="dato1"></td>
    <td colspan="3" id="dato1"></td>
    <td colspan="6" id="dato1"></td>
    <td colspan="2" id="dato1"></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1">Fecha Inicial</td>
    <td colspan="3" id="fuente1"><input name="fecha_ini_rp" type="date" min="2000-01-02" value="<?php echo $row_existente['fecha_ini_rp'] ?>" size="10" required="required"/></td>
    <td colspan="8" id="fuente1"><input type="time" name="hora_ini_rp" value="<?php echo $row_existente['hora_ini_rp'] ?>" required="required"/>
      Hora inicio</td>
  </tr>
  <tr id="tr1">
    <td colspan="2" id="fuente1">Fecha Final</td>
    <td colspan="3" id="fuente1"><input name="fecha_fin_rp" type="date" min="2000-01-02"size="10"  value="<?php echo $row_existente['fecha_fin_rp'] ?>"/></td>
    <td colspan="8" id="fuente1"><input type="time" name="hora_fin_rp"  value="<?php echo $row_existente['hora_fin_rp'] ?>"/>
      Hora fin</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1">Total Horas Trabajadas</td>
    <td colspan="3" id="fuente1"><input name="total_horas_rp" type="text" id="total_horas_rp" size="19" onclick="restarFechas();" value="<?php echo $row_existente['total_horas_rp'] ?>"/></td>
    <td colspan="8" id="fuente1"><input type="hidden" name="horas_rp" id="horas_rp" readonly="readonly"  size="7"/>
      <input type="number" name="int_metro_lineal_rp" id="int_metro_lineal_rp" min="0"step="any" required="required" placeholder="Metro Lineal" style="width:116px"  onclick="getSumT();" value="<?php echo $row_existente['int_metro_lineal_rp'] ?>"/>
      Metro lineal</td>
  </tr>
  <tr id="tr1">
    <td colspan="2" id="fuente1">Kilos Producidos x Hora</td>
    <td colspan="3" id="fuente1"><input name="int_kilosxhora_rp" type="number" required="required" id="int_kilosxhora_rp" min="0"step="any" onclick="restarFechas();" value="<?php echo $row_existente['int_kilosxhora_rp'] ?>"size="12" readonly="readonly"/></td>
    <td colspan="8" id="fuente1"><strong>
      <input name="int_total_rollos_rp" type="number" required="required" id="int_total_rollos_rp" placeholder="Total Rollos" style="width:116px" min="0"step="any"  onclick="getSumT();" value="<?php echo $row_existente['int_total_rollos_rp'] ?>"/>
    </strong>      Total Rollos</td>
    </tr>
    </table>
 </td>
  </tr>
</form>
</body>
</html>