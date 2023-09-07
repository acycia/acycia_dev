<?php require_once('Connections/conexion1.php'); ?>
<?php
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
  $colname_sellado_vista= "-1";
  if (isset($_GET['id_op'])) {
	$colname_sellado_vista = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
  }  
  mysql_select_db($database_conexion1, $conexion1);
  $query_sellado_vista = sprintf("SELECT * FROM Tbl_reg_produccion WHERE id_op_rp='%s' AND id_proceso_rp='4' ORDER BY rollo_rp ASC",$colname_sellado_vista);
  $sellado_vista = mysql_query($query_sellado_vista, $conexion1) or die(mysql_error());
  $row_sellado_vista = mysql_fetch_assoc($sellado_vista);
  $totalRows_sellado_vista = mysql_num_rows($sellado_vista);
 //CARGA LOS STANDBY 
$colname_standBy= "-1";
if (isset($_GET['id_op'])) {
  $colname_standBy = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
 mysql_select_db($database_conexion1, $conexion1);
$query_standBy = sprintf("SELECT *, SUM(`valor_tiem_rt`) AS standby  FROM Tbl_reg_tiempo WHERE Tbl_reg_tiempo.op_rt=%s AND Tbl_reg_tiempo.id_proceso_rt='4' AND id_rpt_rt='141' GROUP BY id_rpt_rt ASC",$colname_standBy);
$standBy = mysql_query($query_standBy, $conexion1) or die(mysql_error());
$row_standBy = mysql_fetch_assoc($standBy);
$totalRows_standBy = mysql_num_rows($standBy); 
  //CARGA LOS TIEMPOS MUERTOS 
  $colname_op = "-1";
  if (isset($_GET['id_op'])) {
	$colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
  }
  mysql_select_db($database_conexion1, $conexion1);
  $query_tiempoMuerto = sprintf("SELECT *, SUM(`valor_tiem_rt`) AS muertos FROM Tbl_reg_tiempo WHERE Tbl_reg_tiempo.op_rt=%s AND id_rpt_rt <> '141' AND Tbl_reg_tiempo.id_proceso_rt='4' GROUP BY id_rpt_rt ASC",$colname_op);
  $tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
  $row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
  $totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
  //CARGA LOS TIEMPOS PREPARACION 
  mysql_select_db($database_conexion1, $conexion1);
  $query_tiempoPreparacion = sprintf("SELECT *, SUM(`valor_prep_rtp`) AS preparacion FROM Tbl_reg_tiempo_preparacion WHERE Tbl_reg_tiempo_preparacion.op_rtp=%s AND Tbl_reg_tiempo_preparacion.id_proceso_rtp='4' GROUP BY id_rpt_rtp ASC",$colname_op);
  $tiempoPreparacion  = mysql_query($query_tiempoPreparacion, $conexion1) or die(mysql_error());
  $row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion);
  $totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion);
  //CARGA LOS TIEMPOS  DESPERDICIOS
  mysql_select_db($database_conexion1, $conexion1);
  $query_desperdicio = sprintf("SELECT *, SUM(`valor_desp_rd`) AS desperdicio FROM Tbl_reg_desperdicio WHERE Tbl_reg_desperdicio.op_rd=%s AND Tbl_reg_desperdicio.id_proceso_rd='4' GROUP BY `id_rpd_rd` ASC",$colname_op);
  $desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
  $row_desperdicio = mysql_fetch_assoc($desperdicio);
  $totalRows_desperdicio = mysql_num_rows($desperdicio);
  
  //CARGA LOS TIEMPOS KILOS PRODUCIDOS
/*  mysql_select_db($database_conexion1, $conexion1);
  $query_producido = sprintf("SELECT *, SUM(`valor_prod_rp`) AS producido FROM  Tbl_reg_kilo_producido WHERE Tbl_reg_kilo_producido.op_rp=%s AND Tbl_reg_kilo_producido.id_proceso_rkp='4' AND id_rpp_rp NOT IN (1406,1407,1655,1656,1657) GROUP BY id_rpp_rp ASC",$colname_op);//medidas bolsillo 1406,1407,1655,1656,1657 
  $producido = mysql_query($query_producido, $conexion1) or die(mysql_error());
  $row_producido = mysql_fetch_assoc($producido);
  $totalRows_producido = mysql_num_rows($producido);*/
  
  //LLENA CAMPOS DE MEZCLAS
  $colname_mezcla= "-1";
  if (isset($_GET['id_op'])) {
	$colname_mezcla = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
  }
  mysql_select_db($database_conexion1, $conexion1);
  $query_mezcla = sprintf("SELECT * FROM Tbl_orden_produccion,Tbl_produccion_mezclas WHERE Tbl_orden_produccion.id_op=%s AND Tbl_orden_produccion.b_borrado_op='0' AND Tbl_orden_produccion.int_cod_ref_op=Tbl_produccion_mezclas.int_cod_ref_pm ",$colname_mezcla);
  $mezcla = mysql_query($query_mezcla, $conexion1) or die(mysql_error());
  $row_mezcla = mysql_fetch_assoc($mezcla);
  $totalRows_mezcla = mysql_num_rows($mezcla);
  //ESTADO DE LA O.P
$colname_estado= "-1";
if (isset($_GET['id_op'])) {
  $colname_estado = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_estado = sprintf("SELECT id_op, b_estado_op FROM Tbl_orden_produccion WHERE Tbl_orden_produccion.id_op=%s",$colname_estado);
$estado = mysql_query($query_estado, $conexion1) or die(mysql_error());
$row_estado = mysql_fetch_assoc($estado);
$totalRows_estado = mysql_num_rows($estado);

$colname_ref = "-1";
if (isset($_GET['id_op'])) {
  $colname_ref = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT Tbl_referencia.ancho_ref,Tbl_referencia.largo_ref,Tbl_referencia.bol_lamina_1_ref,Tbl_referencia.bol_lamina_2_ref,Tbl_referencia.calibreBols_ref,Tbl_referencia.adhesivo_ref,Tbl_referencia.tipoLamina_ref FROM Tbl_orden_produccion,Tbl_referencia WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.id_ref_op = Tbl_referencia.id_ref", $colname_ref);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

 //EXISTE LA LIQUIDACION
/*  $colname_liquidado = "-1";
  if (isset($_GET['id_op'])) {
	$colname_liquidado = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
  }
mysql_select_db($database_conexion1, $conexion1);
$query_liquidado = sprintf("SELECT Tbl_reg_produccion.rollo_rp as rollo_rp FROM TblSelladoRollo,Tbl_reg_produccion WHERE Tbl_reg_produccion.id_proceso_rp='4' AND TblSelladoRollo.id_op_r=%s AND TblSelladoRollo.id_op_r = Tbl_reg_produccion.id_op_rp AND TblSelladoRollo.rollo_r=Tbl_reg_produccion.rollo_rp",$colname_liquidado);
$rollo_liquidado = mysql_query($query_liquidado, $conexion1) or die(mysql_error());
$row_liquidado = mysql_fetch_assoc($rollo_liquidado);
$totalRows_liquidado = mysql_num_rows($rollo_liquidado);*/

//ROLLO SELLADOS
$horasOpmes=$_GET['id_op'];
$resultOpmes = mysql_query("SELECT  id_r, `rollo_r`,  `cod_empleado_r`,`cod_auxiliar_r`, `turno_r`, DATE_FORMAT(MIN(`fechaI_r`), '%k.%i.%s') AS TIEMPOINI, DATE_FORMAT(MAX(`fechaF_r`),'%k.%i.%s') AS TIEMPOFIN, TIMEDIFF(MAX(`fechaF_r`), MIN(`fechaI_r`)) AS TIEMPODIFE, SUM(`kilos_r`) AS KILOS, SUM(reproceso_r) AS reproceso, SUM(`bolsas_r`) AS BOLSAS, rolloParcial_r FROM `TblSelladoRollo` WHERE `id_op_r`= '$horasOpmes' GROUP BY `fechaI_r`,`cod_empleado_r`,`cod_auxiliar_r` ASC");
$numOpmes=mysql_num_rows($resultOpmes); //enviar código MySQL
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
      <td colspan="11" id="principal">REGISTRO DE PRODUCCION EN SELLADO</td>
    </tr>
    <tr>
      <td colspan="5" rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
      <td colspan="2" id="fuente3"><a href="produccion_sellado_listado_rollos.php?id_op_r=<?php echo  $_GET['id_op'] ?>"><img src="images/completo.gif" style="cursor:hand;" alt="ESTIQUER" title="ESTIQUER" border="0"/></a><a href="menu.php"><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
      <td colspan="4" id="fuente3"><strong>ESTADO O.P:</strong>
        <?php if($row_estado['b_estado_op']=='5'){echo "Finalizada";}else{ ?>
        <h4 style="color:#F00">En proceso
          <?php }?>
        </h4></td>
    </tr>
    <tr>
      <td colspan="2" id="subppal2">RESPONSABLE</td>
      <td colspan="4" id="subppal2">TURNOS</td>
    </tr>
    <tr>
      <td colspan="2"id="fuente2"><?php echo $row_sellado_vista['str_responsable_rp']; ?></td>
      <td nowrap colspan="4"id="fuente2"><a href="javascript:popUp('produccion_sellado_stiker_rollo_add.php?id_op_r=<?php echo $_GET['id_op']; ?>','1000','600')"><em>ver turnos</em></a></td>
    </tr>
    <tr>
      <td colspan="2" id="subppal2">Orden de Produccion</td> 
      <td id="subppal2">Ancho</td>
      <td colspan="3" id="subppal2">Referencia - version</td>
    </tr>
    <tr>
      <td colspan="2" nowrap id="fuente2"><strong><?php echo $row_sellado_vista['id_op_rp'] ?></strong></td> 
      <td nowrap id="fuente2"> <?php echo $row_referencia['ancho_ref'];?><!--<a href="javascript:popUp('produccion_registro_sellado_vista.php?id_ref=<?php echo $row_orden_produccion['int_cod_ref_rp'];?>&amp;id_op=<?php echo $row_sellado_vista['id_op_rp'];?>&fecha_ini_rp=<?php echo $row_sellado_vista['fecha_ini_rp'];?>','800','600')" target="_self"><em>ver Detalle</em></a>--></td>
      <td colspan="3" id="fuente2"><strong><?php echo $row_sellado_vista['int_cod_ref_rp'] ?>-<?php echo $row_sellado_vista['version_ref_rp'] ?></strong></td>
    </tr>
    <tr>
      <td colspan="6" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
    </tr>
    <tr>
      <td colspan="11" id="subppal2"><strong>SELLADO / TIEMPOS</strong></td>
    </tr>
    <tr>
      <td colspan="11" id="fuente1"><strong>Nota:</strong> Informacion suministrada segun el ingreso de turnos del operario y hay que tener encuenta que los codigos de operarios no se deben repetir en auxiliar</td>
    </tr>
    <tr>
       <td nowrap id="fuente1">CODIGO OPERARIOS</td>
      <td nowrap id="fuente1">CODIGO AUXILIAR</td>
      <td nowrap id="fuente1">TURNO</td>
      <td nowrap id="fuente1">REPROCESO</td>
      <td nowrap id="fuente1">RANGO INICIAL</td>
      <td nowrap id="fuente1">RANGO FINAL</td>
      <td nowrap id="fuente1">TIEMPO TOTAL</td>
      <td nowrap id="fuente1">ROLLO</td>
      <td nowrap id="fuente1">BOLSAS</td>
      <td nowrap id="fuente1">PARCIAL?</td>
      <td nowrap id="fuente1">EDITAR</td> 
    </tr>
    <?php 	 

		while ($row=mysql_fetch_array($resultOpmes)) { //Bucle para ver todos los registros
?>
    <tr>
       <td id="fuente1"><?php
	  $empleado=$row['cod_empleado_r']; 
             echo $empleado; //visualizar datos
	   ?></td>
      <td id="fuente1"><?php
	  $aux=$row['cod_auxiliar_r']; 
             echo $aux; //visualizar datos
	   ?></td>
      <td id="fuente1"><?php
	  $turno=$row['turno_r']; 
			  echo $turno; //visualizar datos
	   ?></td>
      <td id="fuente1"><?php
	           $reproceso=$row['reproceso'];
			  echo $reproceso;$totalreproceso+=$reproceso; //visualizar datos
	   ?></td>
      <td id="fuente1"><strong>
         <?php
		$tiempoini=$row['TIEMPOINI']; 
			  echo $tiempoini; //visualizar datos
	   ?>
      </strong></td>
      <td id="fuente1"><strong>
        <?php
		$tiempofin=$row['TIEMPOFIN']; 
			  echo $tiempofin; //visualizar datos
	   ?>
      </strong></td>
      <td id="fuente1"><strong>
        <?php
		$tiempototal=$row['TIEMPODIFE']; 
			  $totaltiempo = horadecimalUna($tiempototal);
			  echo $totaltiempo;
			  $totaltiem += $totaltiempo; //visualizar datos
	   ?>
      </strong></td>
      <td id="fuente1"><?php
              echo $Rollo=$row['rollo_r']; //visualizar datos
	   ?></td>
      <td id="fuente1"><?php
	  $bolsas=$row['BOLSAS'];
	  echo $bolsas; $totalbolsas+=$bolsas; //visualizar datos
	   ?></td>
      <td nowrap id="fuente1"><?php
	  $id_rollo=$row['id_r'];
 	  $rolloParcial=$row['rolloParcial_r'];//es parcial
	  //$rolloliquidado=$row_liquidado['rollo_rp'];//existe liquidacion

      $sqlliquid="SELECT Tbl_reg_produccion.rollo_rp as rollo_rp FROM Tbl_reg_produccion,TblSelladoRollo WHERE TblSelladoRollo.id_r='$id_rollo' AND TblSelladoRollo.id_op_r=Tbl_reg_produccion.id_op_rp AND TblSelladoRollo.rollo_r=Tbl_reg_produccion.rollo_rp"; 
	  $resultliquid=mysql_query($sqlliquid); 
	  $numliquid=mysql_num_rows($resultliquid);  
	 if($rolloParcial=='1' && $numliquid == 0){ ?>
     <a href="produccion_registro_sellado_parcial_edit.php?id_op=<?php echo $_GET['id_op']; ?>&id_r=<?php echo $id_rollo; ?>" target="new">Liquidar Parcial</a>
	 <?php }else{?>
 <a href="produccion_registro_sellado_parcial.php?id_op=<?php echo $_GET['id_op']; ?>&id_r=<?php echo $id_rollo; ?>"  target="new">Guardar Parcial</a> 
 <?php  }?>   
     </td>
      <td nowrap id="fuente1"><?php if($numliquid > '0' ){ ?>
     <a href="produccion_registro_sellado_parcial_edit.php?id_op=<?php echo $_GET['id_op']; ?>&id_r=<?php echo $id_rollo; ?>" target="new">Editar</a>
	 <?php } ?></td>
       
    </tr>
    <?php  }//fin while ?>
    <tr>
      <td colspan="2" id="detalle3" >&nbsp;</td>
       <td id="detalle3" >Totales:</td>
      <td id="detalle1" ><?php echo $totalreproceso;?></td>
      <td id="detalle3" >&nbsp;</td>
      <td id="fuente3" >Totales:</td>
      <td id="detalle1" ><?php echo $totaltiem;?></td>
      <td id="detalle1" ><?php echo $totalbolsas;?></td>
      <td colspan="3" id="fuente3" >&nbsp;</td>
    </tr>
    <tr>
      <td colspan="11" id="subppal2">Tiempos y Desperdicios</td>
    </tr>
    <tr>
      <td colspan="11" id="fuente2"><table border="0" id="tablaexpande">
          <?php if($row_standBy['id_rpt_rt']!='') {?>
          <tr>
           <!-- <td nowrap id="subppal2"><strong>Rollo N&deg;</strong></td>-->
            <td colspan="5" nowrap id="subppal2"><strong>Fin de Semana - Tipo</strong></td>
            <td colspan="4" nowrap id="subppal2"><strong> Fin de Semana - Minutos</strong></td>
          </tr>
          <?php  do{ ?>
            <tr>
           <!--   <td id="detalle2"><?php echo $row_standBy['int_rollo_rt']; ?></td>-->
              <td colspan="5" id="detalle1"><?php $varST=$row_standBy['id_rpt_rt']; 
		$id_stand=$varST;
		$sqltstand="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_stand'";
		$resultstand= mysql_query($sqltstand);
		$numstand= mysql_num_rows($resultstand);
		if($numstand >='1')
		{ 
		$nombreST = mysql_result($resultstand, 0, 'nombre_rtp');echo $nombreST; }?></td>
              <td colspan="4" id="detalle2"><?php $varST=$row_standBy['standby']; echo $varST;$totalST=$totalST+$varST; ?></td>
            </tr>
            <?php } while ($row_standBy = mysql_fetch_assoc($standBy)); ?>
          <tr>
            <!--<td id="detalle3">&nbsp;</td>-->
            <td colspan="5" id="detalle3"><strong>TOTAL</strong></td>
            <td colspan="4" id="fuente2"><strong><?php echo $totalST; ?> =  <?php echo redondear_decimal($totalST/60); ?> Horas</strong></td>
          </tr>
          <?php } ?>      
          <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
          <tr>
           <!-- <td nowrap id="subppal2"><strong>Rollo N&deg;</strong></td>-->
            <td colspan="5" nowrap id="subppal2"><strong>Tiempos Muertos - Tipo</strong></td>
            <td colspan="4" nowrap id="subppal2"><strong>Tiempos Muertos - Minutos</strong></td>
          </tr>
          <?php  do{ ?>
            <tr>
           <!--   <td id="detalle2"><?php echo $row_tiempoMuerto['int_rollo_rt']; ?></td>-->
              <td colspan="5" id="detalle1"><?php $varM=$row_tiempoMuerto['id_rpt_rt']; 
		$id_tm=$varM;
		$sqltm="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
		$resulttm= mysql_query($sqltm);
		$numtm= mysql_num_rows($resulttm);
		if($numtm >='1')
		{ 
		$nombreM = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombreM; }?> </td>
              <td colspan="4" id="detalle2"><?php $var1=$row_tiempoMuerto['muertos']; echo $var1;$totalM=$totalM+$var1; ?></td>
            </tr>
            <?php } while ($row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto)); ?>
          <tr>
            <!--<td id="detalle3">&nbsp;</td>-->
            <td colspan="5" id="detalle3"><strong>TOTAL</strong></td>
            <td colspan="4" id="fuente2"><strong><?php echo $totalM; ?></strong></td>
          </tr>
          <?php } ?>
          <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
          <tr>
            <!--<td nowrap id="subppal2"><strong>Rollo N&deg;</strong></td>-->
            <td colspan="5" nowrap id="subppal2"><strong>Tiempos Preparacion - Tipo</strong></td>
            <td colspan="4" nowrap id="subppal2"><strong>Tiempos Preparacion - Minutos</strong></td>
          </tr>
          <?php  do { ?>
            <tr>
             <!-- <td id="detalle2"><?php echo $row_tiempoPreparacion['int_rollo_rtp']; ?></td>-->
              <td colspan="5" id="detalle1"><?php $varP=$row_tiempoPreparacion['id_rpt_rtp']; 
		$id_rtp=$varP;
		$sqlrtp="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
		$resultrtp= mysql_query($sqlrtp);
		$numrtp= mysql_num_rows($resultrtp);
		if($numrtp >='1')
		{ 
		$nombreP = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombreP; }?></td>
              <td colspan="4" id="detalle2"><?php $var2=$row_tiempoPreparacion['preparacion']; echo $var2; $totalP=$totalP+$var2;  ?></td>
            </tr>
            <?php } while ($row_tiempoPreparacion = mysql_fetch_assoc($tiempoPreparacion)); ?>
          <tr>
            <!--<td id="detalle3">&nbsp;</td>-->
            <td colspan="5" id="detalle3"><strong>TOTAL</strong></td>
            <td colspan="4" id="fuente2"><strong><?php echo $totalP; ?></strong></td>
          </tr>
          <?php } ?>
          <?php if($row_desperdicio['id_rpd_rd']!='') {?>
          <tr>
          <!--  <td nowrap id="subppal2"><strong>Rollo N&deg;</strong></td>-->
            <td colspan="5" nowrap id="subppal2"><strong>Desperdicios - Tipo</strong></td>
            <td colspan="4" nowrap id="subppal2"><strong>Desperdicios - Kilos</strong></td>
          </tr>
          <?php do{ ?>
            <tr>
             <!-- <td id="detalle2"><?php echo $row_desperdicio['int_rollo_rd']; ?></td>-->
              <td colspan="5" id="detalle1"><?php $varD=$row_desperdicio['id_rpd_rd']; 
		$id_rpd=$varD;
		$sqlrtd="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
		$resultrtd= mysql_query($sqlrtd);
		$numrtd= mysql_num_rows($resultrtd);
		if($numrtd >='1')
		{ 
		$nombreD = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombreD; }?></td>
              <td colspan="4" id="detalle2"><?php $var3=$row_desperdicio['desperdicio']; echo $var3;$totalD=$totalD+$var3; ?></td>
            </tr>
            <?php } while ($row_desperdicio = mysql_fetch_assoc($desperdicio)); ?>
          <tr>
             <td colspan="5" id="detalle3" above><strong>TOTAL</strong></td>
            <td colspan="4" id="fuente2" above><strong><?php echo $totalD; ?></strong></td>
          </tr>
          <?php } ?>
          <?php //if($row_producido['id_rpp_rp']!='') {?>
          <!--<tr>
             <td colspan="2" nowrap id="subppal2"><strong>Cinta / Liner</strong></td>
            <td colspan="2" nowrap id="subppal2"><strong>Cantidad <?php if($row_referencia['adhesivo_ref']=="HOT MELT"){echo "Solo Pega ";}?> Utilizada</strong></td>
          </tr>
          <?php //do{ ?>
            <tr>
               <td colspan="2" id="detalle1"><?php 
		$id_op=$row_estado['id_op']; 
		$sqlrkp="SELECT insumo.medida_insumo, insumo.descripcion_insumo, Tbl_reg_kilo_producido.id_rpp_rp FROM  insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='4' AND Tbl_reg_kilo_producido.op_rp = $id_op AND insumo.id_insumo NOT IN (1406,1407,1655,1656,1657)";
		$resultrkp= mysql_query($sqlrkp);
		$numrkp= mysql_num_rows($resultrkp);
		if($numrkp >='1')
		{ 
		 $medida_insumo = mysql_result($resultrkp, 0, 'medida_insumo');
		 $nombre4 = mysql_result($resultrkp, 0, 'descripcion_insumo'); echo $nombre4; }
		$sqlmedida="SELECT * FROM medida WHERE id_medida = $medida_insumo";
		$resultmedida= mysql_query($sqlmedida);
		$numedida= mysql_num_rows($resultmedida);
		if($numedida >='1') { 
		$medida=mysql_result($resultmedida,0,'nombre_medida'); }		
		?></td>
              <td id="detalle3"><?php echo $medida;?></td>
              <td id="detalle2"><?php 		  
 				$insumo=$row_producido['producido'];//adhesivo ya esta en kilos
 			   echo $insumo; 
			    $totalInsumo+=$insumo; ?></td>
            </tr>
            <?php //} while ($row_producido = mysql_fetch_assoc($producido)); ?>
          <tr>
           
            <td colspan="2" id="detalle3" above><strong>TOTAL</strong></td>
            <td id="detalle2" above>&nbsp;</td>
            <td id="detalle2" above><strong><?php 
 			echo numeros_format($totalInsumo); ?></strong></td>
          </tr>-->
          <?php //} ?>
 
          
          <!--<tr>
           
            <td colspan="5" nowrap id="subppal2"><strong>Bolsillo</strong></td>
            <td colspan="4" nowrap id="subppal2"><strong>Cantidad  Utilizados / mts</strong></td>
          </tr>
            <tr>
              <td colspan="5" id="detalle1"><?php 
			 $tipoLm = $row_referencia['tipoLamina_ref'];
 		$sqlrkp="SELECT descripcion_insumo FROM  insumo WHERE id_insumo = '$tipoLm'";
		$resultrkp= mysql_query($sqlrkp);
		$numrkp= mysql_num_rows($resultrkp);
		if($numrkp >='1')
		{ 
		 echo $descripcion_insumo = mysql_result($resultrkp, 0, 'descripcion_insumo'); }
   		?></td>
              <td colspan="4" id="detalle2"><?php 
             $metros=($row_referencia['ancho_ref'] * $totalbolsas);//METROS LINEAL
			 $ancholam=($row_referencia['bol_lamina_1_ref'] + $row_referencia['bol_lamina_2_ref']);	   
			 $totalBols =  pasarMillar($ancholam,$row_referencia['calibreBols_ref'],$metros); 
			 //echo $totalBols;
			 echo $metros/100; 
 			  ?></td>
            </tr>-->
         
        </table></td>
    <tr>
      <td colspan="11" id="subppal2"><strong>LIQUIDACION POR ROLLO</strong></td>
    </tr>
    <tr>
      <td colspan="11" id="fuente1"><strong>Nota:</strong> Informacion suministrada segun la liquidacion de la o.p hecha por el operario..</td>
    </tr>
    
    <tr>
      <td nowrap="nowrap" id="subppal2">Rollo N°</td>
      <td nowrap="nowrap" id="subppal2">Kilos Utilizados</td>
      <td nowrap="nowrap" id="subppal2">Desperdicio</td>
      <td nowrap="nowrap" id="subppal2">Desp.sistema</td>
      <td nowrap="nowrap" id="subppal2">Tiempo Gastado</td>
      <td nowrap="nowrap" id="subppal2">T.Muertos</td>
      <td nowrap="nowrap" id="subppal2">T.Prepa</td>
      <td nowrap="nowrap" id="subppal2">Tiempo Rodamiento</td>
      <td nowrap="nowrap" id="subppal2">Bolsas</td>
      <!--<td nowrap="nowrap" id="subppal2">Metro Lineal</td>-->
      <td colspan="2" nowrap="nowrap" id="subppal2">Kilos Por Hora</td>
     <!-- <td nowrap="nowrap" id="subppal2">Editar</td>-->
    </tr>
    <?php $rollos=1;  do{?>
      <tr>
        <td id="fuente2"><?php echo $rollos=$row_sellado_vista['rollo_rp'];
	$trollos=$rollos;
     $rollos++;?></td>
        <td id="fuente2"><?php echo $row_sellado_vista['int_kilos_prod_rp']; $totalkp+=$row_sellado_vista['int_kilos_prod_rp'];?></td>
        <td id="fuente2"><?php echo $row_sellado_vista['int_kilos_desp_rp']; $totalkd+=$row_sellado_vista['int_kilos_desp_rp'];?></td>
        <td id="fuente2"><?php echo $row_sellado_vista['kiloFaltante_rp']; $totalkf+=$row_sellado_vista['kiloFaltante_rp'];?></td>
        <td id="fuente2"><?php /*$totalestiempo=sumarMinutoHoras($row_sellado_vista['total_horas_rp'],$row_sellado_vista['horas_muertas_rp'],$row_sellado_vista['horas_prep_rp']);*/
		echo $row_sellado_vista['total_horas_rp'];
	  $totalh+=horadecimalUna($row_sellado_vista['total_horas_rp'] );?></td>
        <td id="fuente2"><?php echo $row_sellado_vista['horas_muertas_rp']; $totaltm+=$row_sellado_vista['horas_muertas_rp'];?></td>
        <td id="fuente2"><?php echo $row_sellado_vista['horas_prep_rp']; $totaltp+=$row_sellado_vista['horas_prep_rp'];?></td>
        <td id="fuente2"><?php  
		echo $row_sellado_vista['rodamiento_rp'];
	  $totalhr+=horadecimalUna($row_sellado_vista['rodamiento_rp'] );?></td>
        <td id="fuente2"><?php echo $bolsas=$row_sellado_vista['bolsa_rp'];
	  $bolsaR+=$bolsas;?></td> 
        	<!--  <td id="fuente2"> </td>-->
        <td colspan="2" id="fuente2"><?php echo $KILOXHORA=$row_sellado_vista['int_kilosxhora_rp']; 
/*$mtroL=$row_sellado_vista['int_metro_lineal_rp'];
        $mtroLineal+=$mtroL;	*/	
		$totalkh+=$KILOXHORA;
		 ?></td>
<!--        <td id="fuente2">
      <?php 
 	  $id_rp=$row_sellado_vista['id_rp'];
      $sqlrp="SELECT TblSelladoRollo.id_r AS id_r,Tbl_reg_produccion.id_op_rp,Tbl_reg_produccion.rollo_rp FROM Tbl_reg_produccion,TblSelladoRollo WHERE Tbl_reg_produccion.id_rp='$id_rp' AND Tbl_reg_produccion.id_op_rp=TblSelladoRollo.id_op_r AND Tbl_reg_produccion.rollo_rp=TblSelladoRollo.rollo_r AND TblSelladoRollo.rolloParcial_r <> 1"; 
	  $resultrp=mysql_query($sqlrp); 
	  $numrp=mysql_num_rows($resultrp); 
	  if($numrp >= '1') 
	  { 
	  $id_r=mysql_result($resultrp,0,'id_r'); 
	 
    ?>
    <a href="produccion_registro_sellado_edit_PRUEBA.php?id_r=<?php echo $id_r; ?>"  target="_blank">Editar</a> 
    
     <?php   }else{ echo "Liquidado";?>
     
     
     <?php  } ?>
     
        </td>-->
      </tr>
      <?php } while ($row_sellado_vista = mysql_fetch_assoc($sellado_vista)); ?>
    <tr>
      <td id="detalle1"><strong>TOTALES
        <?php $totalHora = $totaltiem - (($totalM+$totalP)/60);// tiempo optimo sin desperdicios ?>
        </strong></td>
      <td id="fuente2"><strong><?php echo $totalkp; ?></strong></td>
      <td id="fuente2"><strong><?php echo $totalkd; ?></strong></td>
      <td id="fuente2"><strong><?php echo $totalkf; ?></strong></td>
      <td id="fuente2"><strong><?php echo redondear_decimal($totalh); ?></strong></td>
      <td id="fuente2"><strong><?php echo $totaltm; ?></strong></td>
      <td id="fuente2"><strong><?php echo $totaltp; ?></strong></td>
      <td id="fuente2"><strong><?php echo redondear_decimal($totalhr); ?></strong></td>
      <td id="fuente2"><strong><?php echo $bolsaR;?></strong></td>
      <!--<td id="fuente2"><strong><?php echo $mtroLineal;?></strong></td-->
      <td colspan="2" id="fuente2"><strong>
        <?php  
 	 echo redondear_decimal($totalkh); 
  
	 ?>
        </strong></td> 
    </tr>
    
    <tr>
      <td colspan="11" id="fuente2">&nbsp;</td>
    </tr>
<tr>
      <td id="subppal2">Tiempos Muertos </td>
      <td id="subppal2">Tiempos Prep.</td>
      <td id="subppal2">Bolsillo /mts</td>
      <td id="subppal2">Kilos Producidos </td>
      <td id="subppal2">Desperdicio Total</td> 
      <td id="subppal2">Kilos Reales</td>
      <td id="subppal2">Cinta /mts (ancho*bolsas)</td>
      <td id="subppal2">Liner/mts</td>
      <td id="subppal2"> Hotmelt/kg</td>
      <td colspan="2" id="subppal2">Metro Lineal</td>
    </tr>
    <tr>
      <td id="fuente2"><strong><?php echo $totalM; ?> </strong></td>
      <td id="fuente2"><strong><?php echo $totalP; ?></strong></td>
      <td id="fuente2"><strong>
        <?php //echo $totalBols;
			echo  redondear_entero_puntos($metros/100); ?>
      </strong></td>
      <td id="fuente2"><strong><?php echo $totalkp; ?></strong></td>
      <td id="fuente2"><strong><?php echo $totalkd+$totalkf; ?></strong></td> 
      <td id="fuente2"><strong><?php echo redondear_decimal($totalkp-$totalD); ?></strong></td>
      <td id="fuente2"><strong>
        <?php
	   //COSTO SELLADO
	      //$metros=($totalbolsas *( $row_referencia['ancho_ref']/100));//BOLSAS POR ANCHO = METROS LINEALES
		  $metros = bolsasAprox2($row_referencia['ancho_ref'],$totalbolsas);
 		  $tipo = $row_referencia['adhesivo_ref']; 
    		  if($tipo=='HOT MELT')//EVALUO QUE SEA HOT PORQ ES KILO
          {
		  $hotmelt=adhesivos($tipo,$metros);//en kilos de pega
 		  $liner = $metros;//liner en metros lineal
 		  } else if($tipo=='N.A'){
           $cinta='N.A';}
		   else {
			  // si es cinta
 			  $cinta = $metros;//si es cinta o lo demas es en metros lineales
  		  } 
 	      echo redondear_entero_puntos($cinta);
	 ?>
      </strong></td>
      <td id="fuente2"><strong><?php echo redondear_entero_puntos($liner); ?></strong></td>
      <td id="fuente2"><strong><?php echo redondear_decimal_operar($hotmelt); ?></strong></td>
      <td colspan="2" id="fuente2"><strong><?php echo redondear_entero_puntos($metros); ?></strong></td>
    </tr>    
    <tr>
      <td colspan="11" id="subppal">&nbsp;</td>
    </tr>
  </table>
  </tr>
  </td>
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
