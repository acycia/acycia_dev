<?php require_once('Connections/conexion1.php'); ?><?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "usuario.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//IMPRIME CAMPOS MAESTRA
$colname_extrusion_vista= "-1";
if (isset($_GET['id_op'])) {
  $colname_extrusion_vista = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
$colname_extrusion_vista_f= "-1";
if (isset($_GET['fecha_ini_rp'])) {
  $colname_extrusion_vista_f = (get_magic_quotes_gpc()) ? $_GET['fecha_ini_rp'] : addslashes($_GET['fecha_ini_rp']);
}

mysql_select_db($database_conexion1, $conexion1);
$query_extrusion_vista = sprintf("SELECT * FROM Tbl_reg_produccion WHERE Tbl_reg_produccion.id_op_rp=%s AND id_proceso_rp='4' AND fecha_ini_rp='%s'",$colname_extrusion_vista,$colname_extrusion_vista_f);
$sellado_vista = mysql_query($query_extrusion_vista, $conexion1) or die(mysql_error());
$row_sellado_vista = mysql_fetch_assoc($sellado_vista);
$totalRows_extrusion_vista = mysql_num_rows($sellado_vista);
//CARGA LOS TIEMPOS MUERTOS 
$colname_tiempoMuerto= "-1";
if (isset($_GET['id_op'])) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}

mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT * FROM Tbl_reg_tiempo WHERE Tbl_reg_tiempo.op_rt=%s AND Tbl_reg_tiempo.id_proceso_rt='4' ",$colname_tiempoMuerto);
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT * FROM Tbl_reg_tiempo_preparacion WHERE Tbl_reg_tiempo_preparacion.op_rtp=%s AND Tbl_reg_tiempo_preparacion.id_proceso_rtp='4'",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS TIEMPOS  DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT * FROM Tbl_reg_desperdicio WHERE Tbl_reg_desperdicio.op_rd=%s AND Tbl_reg_desperdicio.id_proceso_rd='4'",$colname_tiempoMuerto);
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);
//CARGA LOS TIEMPOS KILOS PRODUCIDOS
mysql_select_db($database_conexion1, $conexion1);
$query_producido = sprintf("SELECT * FROM  Tbl_reg_kilo_producido WHERE Tbl_reg_kilo_producido.op_rp=%s AND Tbl_reg_kilo_producido.id_proceso_rkp='4'",$colname_tiempoMuerto);
$producido = mysql_query($query_producido, $conexion1) or die(mysql_error());
$row_producido = mysql_fetch_assoc($producido);
$totalRows_producido = mysql_num_rows($producido);
//LLENA CAMPOS DE MEZCLAS
$colname_mezcla= "-1";
if (isset($_GET['id_op'])) {
  $colname_mezcla = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_mezcla = sprintf("SELECT b_estado_op FROM Tbl_orden_produccion WHERE Tbl_orden_produccion.id_op=%s ",$colname_mezcla);
$mezcla = mysql_query($query_mezcla, $conexion1) or die(mysql_error());
$row_mezcla = mysql_fetch_assoc($mezcla);
$totalRows_mezcla = mysql_num_rows($mezcla);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script type="text/javascript" src="js/formato.js"></script>
<title>SISADGE AC & CIA</title>
</head>
<body>
<div align="center">
<table id="tablainterna">
  <tr>    
     <td colspan="9" id="principal">REGISTRO DE PRODUCCION EN SELLADO</td>
  </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td colspan="4" id="fuente3"><a href="produccion_registro_sellado_edit.php?id_op=<?php echo $row_sellado_vista['id_op_rp']; ?>&amp;fecha_ini_rp=<?php echo $row_sellado_vista['fecha_ini_rp']; ?>&amp;hora_ini_rp=<?php echo $row_sellado_vista['hora_ini_rp']; ?>&amp;rollo=<?php echo $row_sellado_vista['rollo_rp']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/menos.gif" alt="EDIT EXTRUSION" title="EDIT EXTRUSION" border="0" /></a><a href="produccion_sellado_listado_rollos.php?id_op_r=<?php echo  $_GET['id_op'] ?>"><img src="images/completo.gif" style="cursor:hand;" alt="ESTIQUER" title="ESTIQUER" border="0"/></a><a href="menu.php"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    <td colspan="4" id="fuente3"><?php if($row_mezcla['b_estado_op']=='5'){echo "Finalizada";}else{ ?>
        <h4 style="color:#F00"><?php echo "En proceso";}?> </h4></td>
    </tr>
  <tr>
    <td colspan="4" id="subppal2">FECHA DE INGRESO </td>
    <td id="subppal2">TURNOS</td>
    <td colspan="3" id="subppal2">RESPONSABLE </td>
    </tr>
  <tr>
    <td colspan="4" id="fuente2"><?php echo $row_sellado_vista['fecha_ini_rp']; ?></td>
    <td id="fuente2"><a href="javascript:popUp('produccion_sellado_listado_rollos.php?id_op_r=<?php echo $_GET['id_op']; ?>','1300','400')" target="_self"><em>ver turnos</em></a></td>
    <td colspan="3" nowrap id="fuente2">       
      <?php echo $row_sellado_vista['str_responsable_rp']; ?></td>
    </tr>
  <tr>
    <td colspan="3" id="subppal2">Orden de Produccion</td>
    <td id="subppal2">Consumo Total</td>
    <td colspan="4" id="subppal2">Referencia - version</td>
    </tr>
  <tr>
    <td colspan="3" nowrap id="fuente2"><strong><?php echo $row_sellado_vista['id_op_rp'] ?></strong></td>
    <td nowrap id="fuente2"><a href="javascript:popUp('produccion_registro_sellado_total_vista.php?id_op=<?php echo $_GET['id_op']; ?>','800','600')"><i>ver consumo Total o.p</i></a></td>
    <td colspan="4" id="fuente2"><strong><?php echo $row_sellado_vista['int_cod_ref_rp'] ?>-<?php echo $row_sellado_vista['version_ref_rp'] ?></strong></td>
    </tr>
  <tr>
    <td colspan="8" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
    </tr>
  <tr>
    <td colspan="9" id="subppal2"><strong>SELLADO / TIEMPOS</strong></td>
    </tr>
<tr>
      <td colspan="9" id="fuente1"><strong>Nota:</strong> Informacion suministrada segun el ingreso de turnos del operario y hay que tener encuenta que los codigos de operarios no se deben repetir en auxiliar</td>
    </tr>    
      <tr>
      <td nowrap id="fuente1">OPERARIOS</td>
      <td nowrap id="fuente1">CODIGO</td>
      <td nowrap id="fuente1">AUXILIAR</td>
      <td nowrap id="fuente1">CODIGO</td>
      <td nowrap id="fuente1">TURNO</td>
      <td nowrap id="fuente1">RANGO INICIAL</td>
      <td nowrap id="fuente1">RANGO FINAL</td>
      <td nowrap id="fuente1">TIEMPO TOTAL</td>
      <td id="fuente1">BOLSAS</td>
    </tr>
    <tr>
      <?php 	 
	    $horasOpmes=$row_sellado_vista['id_op_rp'];
	    $resultOpmes = mysql_query("SELECT `cod_empleado_r`,`cod_auxiliar_r`, `turno_r`, DATE_FORMAT(MIN(`fechaI_r`), '%k.%i.%s') AS TIEMPOINI, DATE_FORMAT(MAX(`fechaF_r`),'%k.%i.%s') AS TIEMPOFIN, TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE, SUM(`bolsas_r`) AS BOLSAS FROM `TblSelladoRollo` WHERE `id_op_r`= '$horasOpmes' GROUP BY `fechaI_r`,`cod_empleado_r`,`cod_auxiliar_r` ASC");
		$numOpmes=mysql_num_rows($resultOpmes); //enviar código MySQL
		while ($row=mysql_fetch_array($resultOpmes)) { //Bucle para ver todos los registros
			  $empleado=$row['cod_empleado_r']; //datos del campo teléfono
		      $aux=$row['cod_auxiliar_r']; //datos del campo nombre			  
			  $turno=$row['turno_r']; //datos del campo email
			  $tiempoini=$row['TIEMPOINI']; //datos del campo email
			  $tiempofin=$row['TIEMPOFIN']; //datos del campo email
			  $tiempototal=$row['TIEMPODIFE']; //datos del campo email
			  $bolsas=$row['BOLSAS']; //datos del campo email
			  ?>
      <td id="fuente1"><?php
      $id_emp=$empleado; 
	  $sqlemp="SELECT nombre_empleado FROM empleado WHERE codigo_empleado='$id_emp'";
	  $resultemp= mysql_query($sqlemp);
	  $numemp= mysql_num_rows($resultemp);
	  if($numemp >='1')
	  { 
	  $operarios = mysql_result($resultemp, 0, 'nombre_empleado');
	  echo $operarios; 
	  }   //visualizar datos
	   ?></td>
      <td nowrap id="fuente1"><?php
       echo "$empleado"; //visualizar datos
	   ?></td>
       <td id="fuente1" nowrap><?php
	  $id_aux=$aux; 
	  $sqlaux="SELECT nombre_empleado FROM empleado WHERE codigo_empleado='$id_aux'";
	  $resultaux= mysql_query($sqlaux);
	  $numaux= mysql_num_rows($resultaux);
	  if($numaux >='1')
	  { 
	  $auxiliar = mysql_result($resultaux, 0, 'nombre_empleado');
	  echo $auxiliar; 
	  }  //visualizar datos
	   ?></td>
      <td nowrap id="fuente1"><?php
       echo "$aux"; //visualizar datos
	   ?></td>
      <td id="fuente1"><?php
			  echo $turno; //visualizar datos
	   ?></td>
      <td id="fuente1"><strong>
        <?php
			  echo $tiempoini; //visualizar datos
	   ?>
        </strong></td>
      <td id="fuente1"><strong>
        <?php
			  echo $tiempofin; //visualizar datos
	   ?>
        </strong></td>
      <td id="fuente1"><strong>
        <?php
			  $totaltiempo = horadecimalUna($tiempototal);echo $totaltiempo;
			  $totaltiem += $totaltiempo; //visualizar datos
	   ?>
      </strong></td>
      <td id="fuente1"><?php
			  echo $bolsas; //visualizar datos
	   ?></td>
    </tr>
       <?php echo "<br/>"; }
	   ?>     
<tr>
  <td id="subppal2">Placa Rollo</td>
  <td id="subppal2">Peso  Rollo Aprox.</td>
  <td id="subppal2">Bolsas x Rollo</td>
  <td id="subppal2">Lamina 1 klg</td>
  <td id="subppal2">Lamina 2 klg</td>
  <td id="subppal2">Turno</td>
  <td colspan="3" id="subppal2">Rollo</td>
</tr>
  <tr>
    <td id="fuente2"><?php echo $row_sellado_vista['placa_rp']; ?></td>
    <td id="fuente2"><?php echo $row_sellado_vista['int_kilos_prod_rp']; ?></td>
    <td id="fuente2"><?php echo $row_sellado_vista['bolsa_rp']; ?></td>
    <td id="fuente2"><?php echo $row_sellado_vista['lam1_rp']; ?></td>
    <td id="fuente2"><?php echo $row_sellado_vista['lam2_rp']; ?></td>
    <td id="fuente2"><?php echo $row_sellado_vista['turno_rp']; ?></td>
    <td colspan="3" id="fuente2"><?php echo $row_sellado_vista['rollo_rp']; ?></td>
  </tr>    
  <tr>
    <td colspan="9" id="subppal2"><strong>Tiempos y Desperdiciados</strong></td>
    </tr>
  <tr>
  <td colspan="9" id="fuente2">
    <table border="0" id="tablainterna">
      <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
    <tr>
      <td nowrap id="detalle2"><strong>Tiempos Muertos- Tipo</strong></td>
      <td nowrap id="detalle2"><strong>Tiempos Muertos- Minutos</strong></td>
      
    </tr>
      <?php  for ($k=0;$k<=$totalRows_tiempoMuerto-1;$k++) { ?>
        <tr>
      <td id="detalle2">
        <?php $var=mysql_result($tiempoMuerto,$k,id_rpt_rt); 
	  $id_tm=$var;
	  $sqltm="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
	  $resulttm= mysql_query($sqltm);
	  $numtm= mysql_num_rows($resulttm);
	  if($numtm >='1')
	  { 
	  $nombre = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombre; }?>
      </td>
      <td id="detalle2"><?php $var1=mysql_result($tiempoMuerto,$k,valor_tiem_rt); echo $var1; $totalM+=$var1;?></td>
	 </tr> 
	 
	 <?php } ?><?php } ?>
           <tr>
        <td id="detalle3"><strong>TOTAL</strong></td>
        <td id="fuente2"><strong><?php echo $totalM; ?></strong></td>
        </tr>
       <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
    <tr>
      <td nowrap id="detalle2"><strong>Tiempos Preparacion- Tipo</strong></td>
      <td nowrap id="detalle2"><strong>Tiempos Preparacion - Minutos</strong></td>
      
    </tr>
      <?php  for ($x=0;$x<=$totalRows_tiempoPreparacion-1;$x++) { ?>
        <tr>
      <td id="detalle2">
        <?php $var=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); 
	  $id_rtp=$var;
	  $sqlrtp="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
	  $resultrtp= mysql_query($sqlrtp);
	  $numrtp= mysql_num_rows($resultrtp);
	  if($numrtp >='1')
	  { 
	  $nombre = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombre; }?>
      </td>
      <td id="detalle2"><?php $var2=mysql_result($tiempoPreparacion,$x,valor_prep_rtp); echo $var2; $totalP+=$var2; ?></td>
	 </tr> <?php } ?> <?php } ?>
     <tr>
        <td id="detalle3"><strong>TOTAL</strong></td>
        <td id="fuente2"><strong><?php echo $totalP; ?></strong></td>
        </tr> 
      <?php if($row_desperdicio['id_rpd_rd']!='') {?>  
      <tr> 
      <td nowrap id="detalle2"><strong>Desperdicios - Tipo</strong></td>
      <td nowrap id="detalle2"><strong>Desperdicios - Kilos</strong></td>      
      </tr>
      <?php  for ($i=0;$i<=$totalRows_desperdicio-1;$i++) { ?>
      <tr>
      <td id="detalle2">
        <?php $var1=mysql_result($desperdicio,$i,id_rpd_rd); 
	  $id_rpd=$var1;
	  $sqlrtd="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
	  $resultrtd= mysql_query($sqlrtd);
	  $numrtd= mysql_num_rows($resultrtd);
	  if($numrtd >='1')
	  { 
	  $nombre2 = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombre2; }?>
       </td>
      <td id="detalle2"><?php $var3=mysql_result($desperdicio,$i,valor_desp_rd); echo $var3; $totalD+=$var3; ?></td>
	  
    </tr><?php } ?><?php } ?>
    <tr>
        <td id="detalle3"><strong>TOTAL</strong></td>
        <td id="fuente2"><strong><?php echo $totalD; ?></strong></td>
        </tr>
       <?php if($row_producido['id_rpp_rp']!='') {?>
<tr> 
      <td nowrap id="detalle2"><strong>Producidos - Tipo</strong></td>
      <td nowrap id="detalle2"><strong>Producidos - Kilos</strong></td>      
      </tr>
      <?php  for ($j=0;$j<=$totalRows_producido-1;$j++) { ?>
      <tr>
      <td id="detalle2">
        <?php $var1=mysql_result($producido,$j,id_rpp_rp); 
	  $id_rkp=$var1;
	  $sqlrkp="SELECT * FROM insumo WHERE id_insumo='$id_rkp'";
	  $resultrkp= mysql_query($sqlrkp);
	  $numrkp= mysql_num_rows($resultrkp);
	  if($numrkp >='1')
	  { 
	  $nombre4 = mysql_result($resultrkp, 0, 'descripcion_insumo'); echo $nombre4; }?>
       </td>
      <td id="detalle2"><?php $var4=mysql_result($producido,$j,valor_prod_rp); echo $var4; $totalPRO+=$var4; ?></td>  
    </tr> <?php } ?><?php } ?>  
    <tr>
        <td id="detalle3"><strong>TOTAL</strong></td>
        <td id="fuente2"><strong><?php echo $totalPRO; ?></strong></td>
        </tr> 
  </table></td>
  </tr>
    <tr>
      <td colspan="9" id="subppal2"><strong>LIQUIDACION POR ROLLO</strong></td>
    </tr>
        <tr>
      <td colspan="9" id="fuente1"><strong>Nota:</strong> Informacion suministrada segun la liquidacion de la o.p hecha por el operario..</td>    
   </tr>  
  <tr>
    <td id="subppal2">Kilos Desperdiciados</td>
    <td id="subppal2">Total Kilos</td>
    <td id="subppal2"> Horas Trabajadas</td>
    <td id="subppal2">Tiempo Muertos - Minutos</td>
    <td id="subppal2">Tiempos Preparacion - Minutos</td>
    <td id="subppal2">Maquina</td>
    <td colspan="3" id="subppal2">Kilos Por Hora</td>
  </tr>
  <tr>
    <td id="fuente2"><strong><?php echo $totalD; ?></strong></td>
    <td id="fuente2"><?php if($row_sellado_vista['int_total_kilos_rp']!=''){echo $row_sellado_vista['int_total_kilos_rp'];}else{echo "0.00";} ?></td>
    <td id="fuente2"><?php echo $totaltiem;?> </td>
    <td id="fuente2"><strong><?php echo $totalM; ?></strong></td>
    <td id="fuente2"><strong><?php echo $totalP; ?></strong></td>
    <td id="fuente2"><?php echo $row_sellado_vista['str_maquina_rp']; ?></td>
    <td colspan="3" id="fuente2"><strong>
      <?php $totalHora = $totaltiem - (($totalM+$totalP)/60); ?>
    </strong>      <?php    
	$kilosHora = ($row_sellado_vista['int_total_kilos_rp'] + $totaldes)/$totalHora; echo $kilosHora; ?></td>
  </tr>
 
  <tr>
    <td colspan="2" id="subppal2">Fecha Inicial y Hora Inicial</td>
    <td colspan="2" id="subppal2">Fecha Final y Hora Final</td>
    <td id="subppal2">Metro Lineal</td>
    <td colspan="4" id="subppal2">Total Rollos</td>
  </tr>    
  <tr>
    <td colspan="2" id="fuente2"><?php echo $row_sellado_vista['fecha_ini_rp']; ?></td>
    <td colspan="2" id="fuente2"><?php echo $row_sellado_vista['fecha_fin_rp']; ?></td>
    <td id="fuente2"><?php echo $row_sellado_vista['int_metro_lineal_rp']; ?></td>
    <td  colspan="4" id="fuente2">
	<?php echo $row_sellado_vista['int_total_rollos_rp']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="subppal2">&nbsp;</td>
    <td colspan="2" id="subppal2">&nbsp;</td>
    <td id="subppal2">&nbsp;</td>
    <td  colspan="4" id="subppal2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2">&nbsp;</td>
    <td colspan="2" id="fuente2">&nbsp;</td>
    <td id="fuente2">&nbsp;</td>
    <td  colspan="4" id="fuente2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="9" id="subppal">&nbsp;</td>
    </tr>  
</table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);
mysql_free_result($tiempoMuerto);
mysql_free_result($sellado_vista);
mysql_free_result($mezcla);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($producido);

?>
