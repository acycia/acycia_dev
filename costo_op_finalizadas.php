<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
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
$currentPage = $_SERVER["PHP_SELF"];

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

mysql_select_db($database_conexion1, $conexion1);
$query_lista = "SELECT id_op FROM Tbl_orden_produccion WHERE b_estado_op > 0 ORDER BY id_op DESC";
$lista = mysql_query($query_lista, $conexion1) or die(mysql_error());
$row_lista = mysql_fetch_assoc($lista);
$totalRows_lista = mysql_num_rows($lista);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

mysql_select_db($database_conexion1, $conexion1);
$query_mensual = "SELECT * FROM mensual ORDER BY id_mensual DESC";
$mensual = mysql_query($query_mensual, $conexion1) or die(mysql_error());
$row_mensual = mysql_fetch_assoc($mensual);
$totalRows_mensual = mysql_num_rows($mensual);

$maxRows_fichas_tecnicas = 31;
$pageNum_fichas_tecnicas = 0;
if (isset($_GET['pageNum_fichas_tecnicas'])) {
  $pageNum_fichas_tecnicas = $_GET['pageNum_fichas_tecnicas'];
}
$startRow_fichas_tecnicas = $pageNum_fichas_tecnicas * $maxRows_fichas_tecnicas;

mysql_select_db($database_conexion1, $conexion1);
$query_fichas_tecnicas = "SELECT * FROM Tbl_orden_produccion WHERE b_estado_op > 0 ORDER BY id_op DESC";
$query_limit_fichas_tecnicas = sprintf("%s LIMIT %d, %d", $query_fichas_tecnicas, $startRow_fichas_tecnicas, $maxRows_fichas_tecnicas);
$fichas_tecnicas = mysql_query($query_limit_fichas_tecnicas, $conexion1) or die(mysql_error());
$row_consumo = mysql_fetch_assoc($fichas_tecnicas);

if (isset($_GET['totalRows_fichas_tecnicas'])) {
  $totalRows_fichas_tecnicas = $_GET['totalRows_fichas_tecnicas'];
} else {
  $all_fichas_tecnicas = mysql_query($query_fichas_tecnicas);
  $totalRows_fichas_tecnicas = mysql_num_rows($all_fichas_tecnicas);
}
$totalPages_fichas_tecnicas = ceil($totalRows_fichas_tecnicas/$maxRows_fichas_tecnicas)-1;

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM Tbl_referencia order by id_ref desc";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

$queryString_fichas_tecnicas = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_fichas_tecnicas") == false && 
        stristr($param, "totalRows_fichas_tecnicas") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_fichas_tecnicas = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_fichas_tecnicas = sprintf("&totalRows_fichas_tecnicas=%d%s", $totalRows_fichas_tecnicas, $queryString_fichas_tecnicas);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
</head>
<body onload = "JavaScript: AutoRefresh (40000);">
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
 </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
	<form action="costo_op_finalizadas2.php" method="get" name="consulta">
	<table id="tabla1">
	  <tr>
	  <td id="subtitulo">LISTADO DE COSTOS</td>
	  </tr>
	  <tr>
	  <td id="fuente2">
      <select name="id_op" id="id_op">
        <option value="0">O.P.</option>
        <?php
do {  
?>
        <option value="<?php echo $row_lista['id_op']?>"><?php echo $row_lista['id_op']?></option>
        <?php
} while ($row_lista = mysql_fetch_assoc($lista));
  $rows = mysql_num_rows($lista);
  if($rows > 0) {
      mysql_data_seek($lista, 0);
	  $row_lista = mysql_fetch_assoc($lista);
  }
?>
      </select>
      <select name="id_ref" id="id_ref">
        <option value="0">REF</option>
        <?php
do {  
?>
        <option value="<?php echo $row_referencias['id_ref']?>"><?php echo $row_referencias['cod_ref']?></option>
        <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
      </select>
      
      <select name="estado" id="estado" style="width:100px" >
        <option value="" selected="selected">Estado</option>
        <!--<option value="0">INGRESADA</option>-->
        <option value="1">EXTRUSION</option>
        <option value="2">IMPRESION</option>
        <option value="3">REFILADO</option>
        <option value="4">SELLADO</option>
        <option value="5">FINALIZADA</option>
       </select>

       
      <select name="mensual" id="mensual" >
        <option value="0">MENSUAL</option>
        <?php
    do {  
    ?>
        <option value="<?php echo $row_mensual['id_mensual']?>"><?php echo $row_mensual['mensual']?></option>
        <?php
    } while ($row_mensual = mysql_fetch_assoc($mensual));
      $rows = mysql_num_rows($mensual);
      if($rows > 0) {
          mysql_data_seek($mensual, 0);
          $row_mensual = mysql_fetch_assoc($mensual);
      }
    ?>
      </select>
      <select name="fecha" id="fecha"> 
      <option value="0">ANUAL</option>
        <?php
do {  
?>
        <option value="<?php echo $row_ano['anual']?>"><?php echo $row_ano['anual']?></option>
        <?php
} while ($row_ano = mysql_fetch_assoc($ano));
  $rows = mysql_num_rows($ano);
  if($rows > 0) {
      mysql_data_seek($ano, 0);
	  $row_ano = mysql_fetch_assoc($ano);
  }
?>
      </select>
      <input type="submit" name="Submit" value="FILTRO" onClick="if(consulta.id_op.value=='0' && consulta.id_ref.value=='0' && consulta.estado.value=='' && consulta.mensual.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/>      </td>
  </tr>
</table>
</form>
 <table id="tabla1">
  <tr>
    <td colspan="2" id="dato1">EXTRUSION </td>
    <td colspan="3">&nbsp; </td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato1">IMPRESION</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato1">SELLADO</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
  </tr>  
  <tr id="tr1">
    <td id="titulo4">O.P</td>
    <td nowrap="nowrap" id="titulo4">Ref-</td>
    <td nowrap="nowrap" id="titulo4">Tipo de bolsa</td>
    <td nowrap="nowrap" id="titulo4">Bolsas </td>
    <td nowrap="nowrap" id="titulo4">Kilos Ext</td>
    <td nowrap="nowrap" id="titulo4">V. kilo MP </td>
    <td nowrap="nowrap" id="titulo4">H. de Ext</td>
    <td nowrap="nowrap" id="titulo4">Costo kilo Ext</td>
    <td nowrap="nowrap" id="titulo4">Desp</td>
    <td nowrap="nowrap" id="titulo4">K. Inicial Imp</td>
    <td nowrap="nowrap" id="titulo4">V. ins. de Imp</td>
    <td nowrap="nowrap" id="titulo4">H. de Imp</td>
    <td nowrap="nowrap" id="titulo4">Desp</td>
    <td nowrap="nowrap" id="titulo4">Costo kilo Imp </td>
    <td nowrap="nowrap" id="titulo4">k. Inicial Sell </td>
    <td nowrap="nowrap" id="titulo4">V.  Ins de Sell</td>
    <td nowrap="nowrap" id="titulo4">H. de Sell</td>
    <td nowrap="nowrap" id="titulo4">Desp</td>
    <td nowrap="nowrap" id="titulo4">Costo kilo sell</td> 
    <td nowrap="nowrap" id="titulo4">costo MP</td>
    <td nowrap="nowrap" id="titulo4">Repro</td>
    <td nowrap="nowrap" id="titulo4">Costo  Bolsa</td>
    <td nowrap="nowrap" id="titulo4">Precio vta </td>
    <td nowrap="nowrap" id="titulo4">Rent</td>
     <td nowrap="nowrap" id="titulo4">total</td>
    <td nowrap="nowrap" id="titulo4">Estado</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
        <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_consumo['id_op']; ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_consumo['int_cod_ref_op']; ?></a><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"></a></td>
      <td id="dato4" nowrap><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_consumo['str_tipo_bolsa_op'];?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000">
	  <?php 
      $id_op=$row_consumo['id_op']; 
	  $sqlex="SELECT SUM(bolsa_rp) AS bolsa_rp, SUM(int_metro_lineal_rp) AS int_metro_lineal_rp FROM Tbl_reg_produccion WHERE `id_op_rp`='$id_op' AND id_proceso_rp = '4'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { 
	   $metros_imp = mysql_result($resultex,0,'int_metro_lineal_rp'); 
	  echo $bolsas_sell=mysql_result($resultex,0,'bolsa_rp'); 
 	  }else{echo $bolsas_sell="0";$metros_imp='0';}	
        ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php 
  		//kilos utilizados
        $id_op=$row_consumo['id_op'];
        $sqlexk="SELECT SUM(valor_prod_rp) AS kilosT FROM Tbl_reg_kilo_producido WHERE op_rp='$id_op' AND id_proceso_rkp='1'"; 
        $resultexk=mysql_query($sqlexk); 
        $numexk=mysql_num_rows($resultexk); 
        if($numexk >= '1') 
        { echo $tkilos_ex=mysql_result($resultexk,0,'kilosT'); 
        }else{echo $tkilos_ex="0";}	
        ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> 
        <?php 
	   	  //COSTO MATERIAS PRIMAS
/* 	  $sqlcostoMP="SELECT Tbl_reg_kilo_producido.id_rpp_rp, insumo.valor_unitario_insumo AS VALORKILO, Tbl_reg_kilo_producido.valor_prod_rp AS CANTKILOS FROM insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='1' AND Tbl_reg_kilo_producido.op_rp = '$id_op'"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	  $contValor=0;
	  $contCant=0;
	  do{
		  $valorMP = $row_valoresMP['VALORKILO'];
		  $KilosMP = $row_valoresMP['CANTKILOS'];//TODOS LOS KILOS REGISTRADOS CON DESPERDICIOS
          $valorItem=$valorMP*$KilosMP;//cada item cuanto vale un kilo
	      $contValor+=$valorItem;//ACUMULA VALOR POR ITEM
		  $contCant+=$KilosMP;//ACUMULA CANTIDAD POR ITEM
    } while ($row_valoresMP = mysql_fetch_assoc($resultcostoMP));
	      $contValor=$contValor;//DATO PARA EL CAMPO COSTO MP
	      $kiloMPEXT = ($contValor); //COSTO KILO DE MP 
 		 echo numeros_format($kiloMPEXT/$contCant);*/
		   ?>
        <?php
	  //HISTORIAL VALOR MP
	    $id_opcosto=$row_consumo['id_op'];
	    $sqlCMP="SELECT valor_prod_rp, costo_mp FROM  Tbl_reg_kilo_producido WHERE Tbl_reg_kilo_producido.id_proceso_rkp='1' AND Tbl_reg_kilo_producido.op_rp = '$id_op'"; 
	   $resultCMP=mysql_query($sqlCMP); 
	   $numCMP=mysql_num_rows($resultCMP); 
	   $row_CMP = mysql_fetch_assoc($resultCMP);
	   $valorTE=0;
	 do{
          $undMP = $row_CMP['valor_prod_rp'];
		  $CMP = $row_CMP['costo_mp'];
		  $valorMP=$undMP*$CMP;
		  $valorTE+=$valorMP;//ACUMULA VALOR POR ITEM 
    } while ($row_CMP = mysql_fetch_assoc($resultCMP));
       echo $kiloMPEXT=numeros_format($valorTE/$tkilos_ex);
 /*        $sqlcostoE="SELECT COUNT(`id_rp`) AS items, SUM(`costo`) AS costoT FROM Tbl_reg_produccion WHERE id_op_rp='$id_opcosto' AND id_proceso_rp='1'"; 
        $resultcostoE=mysql_query($sqlcostoE); 
        $numcostoE=mysql_num_rows($resultcostoE); 
        if($numcostoE >= '1') 
        { 
		 $costoE=mysql_result($resultcostoE,0,'costoT');
		 $itemsE=mysql_result($resultcostoE,0,'items');  
		 echo ($costoE/$itemsE);
        }*/
		
	   ?>
      </a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> 
      <?php 
      $id_op=$row_consumo['id_op'];
	  $sqlexh="SELECT total_horas_rp AS horasT, costo AS costo FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp = '1'"; 
	  $resultexh=mysql_query($sqlexh); 
	  $numexh=mysql_num_rows($resultexh); 
	  if($numexh >= '1') 
	  { echo $tHoras_ex=mysql_result($resultexh,0,'horasT');
	    $costo_ex=mysql_result($resultexh,0,'costo');
		$horas_ext = horadecimalUna($tHoras_ex);
 	  }else{echo "0";}	
        ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php //echo $costo_ex;
	  if($tkilos_ex !='')
      {
 	  $sqlextru="SELECT COUNT(DISTINCT rollo_r) AS rollos, DATE_FORMAT(fechaI_r,'%Y-%m-%d') AS FECHA, SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$id_op' ORDER BY fechaI_r ASC"; 
	  $resultextru=mysql_query($sqlextru); 
	  $numextru=mysql_num_rows($resultextru); 
	  if($numextru >= '1') 
	  { 
  		$FECHA_NOVEDAD_EXT=mysql_result($resultextru,0,'FECHA');
 	  } 
   	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras` ORDER BY `fecha` DESC LIMIT 1";//ORDER BY fecha DESC
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
 	  //PARA TODOS LOS PROCESOS
	  if($numgeneral >='1')
	  { 
	  $TiempomeExt =  mysql_result($resultgeneral, 0, 'extrusion');
      //EXTRUDER
	  $costoUnHGga_ext = mysql_result($resultgeneral, 0, 'gga_ext');
	  $costoUnHCif_ext = mysql_result($resultgeneral, 0, 'cif_ext');
	  $costoUnHGgv_ext = mysql_result($resultgeneral, 0, 'ggv_ext');
	  $costoUnHGgf_ext = mysql_result($resultgeneral, 0, 'ggf_ext');
 	  $cifyggaExt=($costoUnHGga_ext+$costoUnHCif_ext+$costoUnHGgv_ext+$costoUnHGgf_ext);
	  }else{$TiempomeExt='0';} 

	//SUELDOS DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
	$sqlbasicoExt="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado NOT IN(4,5,6,7,8,9,10)";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos SE AGREGO b.estado_empleado='1' AND
	$resultbasicoExt=mysql_query($sqlbasicoExt);
    $operario_ext_demas=mysql_result($resultbasicoExt,0,'operarios');
	$sueldo_basExt=mysql_result($resultbasicoExt,0,'SUELDO'); //sueldos del mes 
	$auxilio_basExt=mysql_result($resultbasicoExt,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_basExt=mysql_result($resultbasicoExt,0,'APORTES'); //aportes del mes 
 	//$horasmes_bas=mysql_result($resultbasico,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 SE ENCUENTRA EN FACTOR
	$operarios_basExt=mysql_result($resultbasicoExt,0,'operarios');//CANTIDAD DE OPERARIOS 
	$horasdia_basExt=mysql_result($resultbasicoExt,0,'HORADIA');//esto es 8 
	 	 
	//NOVEDAD DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
 	$sqlnovbasicoExt="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado NOT IN(4,5,6,7,8,9,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-31')";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos
	$resultnovbasicoExt=mysql_query($sqlnovbasicoExt);	
	$pago_novbasicoExt=mysql_result($resultnovbasicoExt,0,'pago'); 
	$extras_novbasicoExt=mysql_result($resultnovbasicoExt,0,'extras');  
	$recargo_novbasicoExt=mysql_result($resultnovbasicoExt,0,'recargo');
	$festivo_novbasicoExt=mysql_result($resultnovbasicoExt,0,'festivos');
	$horasmes_Ext='240';//240 mientras se define horas al mes
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
  	$valorhoraxoperExtDemas = sueldoMes($sueldo_basExt,$auxilio_basExt,$aportes_basExt,$horasmes_Ext,$horasdia_basExt,$recargo_novbasicoExt,$festivo_novbasicoExt); 
	$valorHoraExtDemas  = ($valorhoraxoperExtDemas/$operario_ext_demas)/3;//total H se divide por # de operarios de fuera de los procesos  
  
  	//SUELDOS DE TODOS LOS EMPLEADOS DENTRO DE EXTRUSION 
	$sqlbasicoExt="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado IN(4)";//IN(4) son extrusion
	$resultbasicoExt=mysql_query($sqlbasicoExt);
	$operario_Ext=mysql_result($resultbasicoExt,0,'operarios');
	$sueldo_basExt=mysql_result($resultbasicoExt,0,'SUELDO'); //sueldos del mes 
	$auxilio_basExt=mysql_result($resultbasicoExt,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_basExt=mysql_result($resultbasicoExt,0,'APORTES'); //aportes del mes 
	$horasdia_basExt=mysql_result($resultbasicoExt,0,'HORADIA');//esto es 8 
	$horasmes_Ext='240';//240 mientras se define horas al mes
	 //FIN	 
	 //NOVEDAD DE ESE MES DE TODOS LOS EMPLEADOS DENTRO DE EXTRUSION 
  	$sqlnovbasicoExt="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado IN(4) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-31')";//IN(4)novedad extrusion 
	$resultnovbasicoExt=mysql_query($sqlnovbasicoExt);	
	$pago_novbasicoExt=mysql_result($resultnovbasicoExt,0,'pago'); 
	$extras_novbasicoExt=mysql_result($resultnovbasicoExt,0,'extras');  
	$recargo_novbasicoExt=mysql_result($resultnovbasicoExt,0,'recargo');
	$festivo_novbasicoExt=mysql_result($resultnovbasicoExt,0,'festivos');
	//FIN
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
 	$valorhoraTodosExt = sueldoMes($sueldo_basExt,$auxilio_basExt,$aportes_basExt,$horasmes_Ext,$horasdia_basExt,$recargo_novbasicoExt,$festivo_novbasicoExt);
	$kiloXHoraExt=($tkilos_ex/$horas_ext);//kilo x hora para los cif y gga
	$valorHoraExt = ($valorhoraTodosExt/$operario_Ext);//total H se divide por # de operarios de extrusion	  
 	$costokiloInsumoExt=($kiloMPEXT/$tkilos_ex);//$ costo de 1 kilos mp
	$manoObraExt=($horas_ext*($valorHoraExt+$valorHoraExtDemas))/$tkilos_ex;//$ costo de 1 kilo mano obra
	$valorkilocifyggaExt=($cifyggaExt/$kiloXHoraExt);//$kiloXHoraExt valor por hora de cif y gga  
 	 $costoExtrusion = ($costokiloInsumoExt+$manoObraExt+$valorkilocifyggaExt);
	 echo numeros_format($costoExtrusion);
	 }
 	  ?></strong></a> </td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php 
 	    $id_op=$row_consumo['id_op'];
	    $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='1'"; 
	    $resultexd=mysql_query($sqlexd); 
	    $numexd=mysql_num_rows($resultexd); 
	    if($numexd >= '1') 
	    {echo $kilos_exd=mysql_result($resultexd,0,'kgDespe');} ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php //echo $KILOSREALESIMP =($tkilos_ex-$kilos_exd);
  		//EXISTE IMPRESION
        $id_op=$row_consumo['id_op'];
        $sqlimpk="SELECT SUM(int_kilos_prod_rp) AS kilosT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='2'"; 
        $resultimpk=mysql_query($sqlimpk); 
        $numimpk=mysql_num_rows($resultimpk); 
        if($numimpk >= '1') 
        { 
		echo $KILOSREALESIMP=mysql_result($resultimpk,0,'kilosT'); 
        }
 	  ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> 
        <?php 	 
/*	  $id_op=$row_consumo['id_op'];
 	  $sqlcostoMP="SELECT Tbl_reg_kilo_producido.id_rpp_rp, insumo.valor_unitario_insumo AS VALORKILO, Tbl_reg_kilo_producido.valor_prod_rp AS CANTKILOS FROM  insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='2' AND Tbl_reg_kilo_producido.op_rp = '$id_op'"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	  $contValorI=0;
	  $contCantI=0;
	  do{
	  $valorMP = $row_valoresMP['VALORKILO'];
	  $KilosMP = $row_valoresMP['CANTKILOS'] ;
      $valorItem=$valorMP*$KilosMP;//cada item cuanto vale un kilo
	  $contValorI+=$valorItem;//ACUMULA VALOR POR ITEM
    } while ($row_valoresMP = mysql_fetch_assoc($resultcostoMP));
	  $COSTOTINTA = ($contValorI); 
	  echo numeros_format($COSTOTINTA);//COSTO KILO DE TINTA*/	
	  ?>
        <?php
	    $sqlCMP="SELECT valor_prod_rp, costo_mp FROM  Tbl_reg_kilo_producido WHERE Tbl_reg_kilo_producido.id_proceso_rkp='2' AND Tbl_reg_kilo_producido.op_rp = '$id_op'"; 
	   $resultCMP=mysql_query($sqlCMP); 
	   $numCMP=mysql_num_rows($resultCMP); 
	   $row_CMP = mysql_fetch_assoc($resultCMP);
	   $valorTI=0;
	 do{
          $undMP = $row_CMP['valor_prod_rp'];
		  $CMP = $row_CMP['costo_mp'];
		  $valorMP=$undMP*$CMP;
		  $valorTI+=$CMP;//ACUMULA VALOR POR ITEM 
    } while ($row_CMP = mysql_fetch_assoc($resultCMP));
       echo $COSTOTINTA=numeros_format($valorTI);	  
/*	    $id_opcosto=$row_consumo['id_op'];
        $sqlcostoI="SELECT COUNT(`id_rp`) AS items, SUM(`costo`) AS costoT FROM Tbl_reg_produccion WHERE id_op_rp='$id_opcosto' AND id_proceso_rp='2'"; 
        $resultcostoI=mysql_query($sqlcostoI); 
        $numcostoI=mysql_num_rows($resultcostoI); 
        if($numcostoI >= '1') 
        { 
		 $costoI=mysql_result($resultcostoI,0,'costoT');
		 $itemsI=mysql_result($resultcostoI,0,'items');  
		 echo ($costoI/$itemsI);
        }*/
	   ?>
       </a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php  
	  $sqlimph="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(total_horas_rp))) AS horasT, SUM(costo) AS Tcosto FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp = '2'"; 
	  $resultimph=mysql_query($sqlimph); 
	  $numimph=mysql_num_rows($resultimph); 
	  if($numimph >= '1') 
	  { 
	  $tHoras_imp=mysql_result($resultimph,0,'horasT');
	  $costo_imp=mysql_result($resultimph,0,'Tcosto'); 
	  $horas_imp = horadecimalUna($tHoras_imp);
  	  echo $tHoras_imp;
  	  }
        ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php 	  
	  $sqldespimp="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='2'"; 
	  $resultdespimp=mysql_query($sqldespimp); 
	  $numdespimp=mysql_num_rows($resultdespimp); 
	  if($numdespimp >= '1') 
	  { echo $kilos_despimp=mysql_result($resultdespimp,0,'kgDespe');} ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php //echo $costo_imp;
	 /* if($KILOSREALESIMP !="")
	    { */
	  $sqlimp="SELECT DATE_FORMAT(fechaI_r,'%Y-%m-%d') AS FECHA FROM TblImpresionRollo WHERE id_op_r='$id_op' ORDER BY fechaI_r ASC"; 
	  $resultimp=mysql_query($sqlimp); 
	  $numimp=mysql_num_rows($resultimp); 
	  if($numimp >= '1') 
	   { 
	   $FECHA_NOVEDAD_IMP=mysql_result($resultimp,0,'FECHA');
 	   }
	   
  	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras`  ORDER BY `fecha` DESC LIMIT 1";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
	  if($numgeneral >='1')
	  { 
 	  $TiempomeImp =  mysql_result($resultgeneral, 0, 'impresion');
	  //IMPRESION
	  $costoUnHGga_imp = mysql_result($resultgeneral, 0, 'gga_imp');
	  $costoUnHCif_imp = mysql_result($resultgeneral, 0, 'cif_imp');
	  $costoUnHGgv_imp = mysql_result($resultgeneral, 0, 'ggv_imp');
	  $costoUnHGgf_imp = mysql_result($resultgeneral, 0, 'ggf_imp');
	  $cifyggaImp=($costoUnHGga_imp+$costoUnHCif_imp+$costoUnHGgv_imp+$costoUnHGgf_imp);
	  }else{$TiempomeImp='0';} 

	//SUELDOS DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
	$sqlbasico="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado NOT IN(4,5,6,7,8,9,10)";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos SE AGREGO b.estado_empleado='1' AND
	$resultbasico=mysql_query($sqlbasico);
    $operario_imp_demas=mysql_result($resultbasico,0,'operarios');
	$sueldo_bas=mysql_result($resultbasico,0,'SUELDO'); //sueldos del mes 
	$auxilio_bas=mysql_result($resultbasico,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_bas=mysql_result($resultbasico,0,'APORTES'); //aportes del mes 
 	//$horasmes_bas=mysql_result($resultbasico,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 SE ENCUENTRA EN FACTOR
	$operarios_bas=mysql_result($resultbasico,0,'operarios');//CANTIDAD DE OPERARIOS 
	$horasdia_bas=mysql_result($resultbasico,0,'HORADIA');//esto es 8 
	 	 
	//NOVEDAD DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
 	$sqlnovbasico="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado NOT IN(4,5,6,7,8,9,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-31')";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos
	$resultnovbasico=mysql_query($sqlnovbasico);	
	$pago_novbasico=mysql_result($resultnovbasico,0,'pago'); 
	$extras_novbasico=mysql_result($resultnovbasico,0,'extras');  
	$recargo_novbasico=mysql_result($resultnovbasico,0,'recargo');
	$festivo_novbasico=mysql_result($resultnovbasico,0,'festivos');
	$horasmes_imp='240';//240 mientras se define horas al mes
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
  	$valorhoraxoperImpDemas = sueldoMes($sueldo_bas,$auxilio_bas,$aportes_bas,$horasmes_imp,$horasdia_bas,$recargo_novbasico,$festivo_novbasico); 
	$valorHoraImpDemas = ($valorhoraxoperImpDemas/$operario_imp_demas)/3;//total Horas se divide por # de operarios de fuera de los procesos dividido en 3 q son los procesos
  
  	//SUELDOS DE TODOS LOS EMPLEADOS DENTRO DE IMPRESION 
	$sqlbasico="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado IN(5,10)";//IN(5,10) son impresion
	$resultbasico=mysql_query($sqlbasico);
	$operario_imp=mysql_result($resultbasico,0,'operarios');
	$sueldo_bas=mysql_result($resultbasico,0,'SUELDO'); //sueldos del mes 
	$auxilio_bas=mysql_result($resultbasico,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_bas=mysql_result($resultbasico,0,'APORTES'); //aportes del mes 
	$horasdia_bas=mysql_result($resultbasico,0,'HORADIA');//esto es 8 
	$horasmes_imp='240';//240 mientras se define horas al mes
	 //FIN	 
	 //NOVEDAD DE ESE MES DE TODOS LOS EMPLEADOS DENTRO DE IMPRESION 
  	$sqlnovbasico="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado IN(5,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_IMP', '%Y-%m-31')";//IN(5,10)novedad impresion 
	$resultnovbasico=mysql_query($sqlnovbasico);	
	$pago_novbasico=mysql_result($resultnovbasico,0,'pago'); 
	$extras_novbasico=mysql_result($resultnovbasico,0,'extras');  
	$recargo_novbasico=mysql_result($resultnovbasico,0,'recargo');
	$festivo_novbasico=mysql_result($resultnovbasico,0,'festivos');
	//FIN
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
 	$valorhoraTodosImp = sueldoMes($sueldo_bas,$auxilio_bas,$aportes_bas,$horasmes_imp,$horasdia_bas,$recargo_novbasico,$festivo_novbasico);
	$kiloXHora=($KILOSREALESIMP/$horas_imp);//kilo x hora para los cif y gga
	$valorHoraImp = ($valorhoraTodosImp/$operario_imp);//total Horas se divide por # de operarios de Impresion	  
  	$costokiloInsumo=($COSTOTINTA/$KILOSREALESIMP);//$ costo de 1 kilos mp
	$manoObra=($horas_imp*($valorHoraImp+$valorHoraImpDemas))/$KILOSREALESIMP;//$ costo de 1 kilo mano obra en 1 hora
  	$valorkilocifygga=($cifyggaImp/$kiloXHora);// $kiloXHora valor por hora de cif y gga  
 	 $costoImpresion = ($costokiloInsumo+$manoObra+$valorkilocifygga);
	 echo numeros_format($costoImpresion);
	 //}
  	  ?></strong></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php //echo $KILOSREALESSELL =($KILOSREALESIMP-$kilos_despimp);
	    //echo $KILOSREALESSELL =($tkilos_ex-$kilos_exd);
  		//EXISTE SELLADO
        $id_op=$row_consumo['id_op'];
        $sqlsellk="SELECT SUM(int_kilos_prod_rp) AS kilosT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp='4'"; 
        $resultsellk=mysql_query($sqlsellk); 
        $numsellk=mysql_num_rows($resultsellk); 
        if($numsellk >= '1') 
        { 
		echo $KILOSREALESSELL=mysql_result($resultsellk,0,'kilosT'); 
        }
       ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> 
        <?php 
	  	      //MATERIA PRIMA
	   	  mysql_select_db($database_conexion1, $conexion1);
		  $queryref = "SELECT * FROM Tbl_orden_produccion,Tbl_referencia  WHERE Tbl_orden_produccion.id_op='$id_op' AND Tbl_orden_produccion.id_ref_op=Tbl_referencia.id_ref";
		  $resultref=mysql_query($queryref); 
		  $numcostoMP=mysql_num_rows($resultref); 
		  $row_referencia = mysql_fetch_assoc($resultref);
   		 
		  $tipo_cinta = $row_referencia['tipoCinta_ref'];//TIPO DE CINTA O LINER
		  $id_ter=$row_referencia['id_termica_op'];

		  //SI LLEVA CINTA TERMICA
 	      $sqlterm="SELECT valor_unitario_insumo FROM insumo WHERE id_insumo='$id_ter'"; 
		  $resultterm=mysql_query($sqlterm); 
		  $numterm=mysql_num_rows($resultterm);  
 		  if($numterm >= '1')  
		  { 
		  $valor_term=mysql_result($resultterm,0,'valor_unitario_insumo');
		  $RealmetrosTermica = ($row_referencia['cinta_termica_op']*$bolsas_sell);//por el ancho real de la cinta termica
		  $costoTermica=($RealmetrosTermica * $valor_term);
		  }else{$costoTermica='0';}

  		  $tipo = $row_referencia['adhesivo_ref'];//HOTMELT O CINTA
  		  if($tipo=='HOT MELT')//EVALUO QUE SEA HOT PORQ SE COSTEA EN KILO
          {
		  //LINER
		  $sqlliner="SELECT `id_insumo`,`valor_unitario_insumo` FROM `insumo` WHERE `id_insumo` = '$tipo_cinta'";
		  $resultliner=mysql_query($sqlliner);
          $valorLiner = mysql_result($resultliner,0,'valor_unitario_insumo');
		  $costoliner = $metros_imp * $valorLiner;//valor liner por metro lineal
		  //PEGANTE
		  $sqlpega="SELECT `id_insumo`,`valor_unitario_insumo` FROM `insumo` WHERE `id_insumo` = '1695'";//1695 codigo del pegante aplicado es general
		  $resultpega=mysql_query($sqlpega);
          $valorpega = mysql_result($resultpega,0,'valor_unitario_insumo');//VALOR DEL KILO DE PEGA
		  $metrosakilospega=adhesivos($tipo,$metros_imp);//1.2 LOS GRAMOS EN 1 METRO LINEAL Y 1000 GRAMOS EN 1 KILO 
 		  $costopega = ($metrosakilospega * $valorpega);
  		  $costoHotmelOcinta = ($costoliner+$costopega);// El precio total de hotmelt
		  }else{
		  //CINTA SEGURIDAD
 		  $sqlcostoMP="SELECT valor_unitario_insumo AS VALORMETRO FROM insumo WHERE insumo.id_insumo = '$tipo_cinta'"; 
		  $resultcostoMP=mysql_query($sqlcostoMP); 
		  $numcostoMP=mysql_num_rows($resultcostoMP); 
		  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
		  if($numcostoMP >= '1')  
		  { 
		  $valorMPcinta = $row_valoresMP['VALORMETRO'];
		  $costoHotmelOcinta = $metros_imp * $valorMPcinta;//esto pasa a dinero
 		  } 			  
 		 }
		  //SUMA CINTA SEGURIDAD Y TERMICA, BOLSILLO, HOTMEL
 		  $pesoMbols = millarBolsillo($row_referencia['ancho_ref'],$row_referencia['largo_ref'],$row_referencia['calibreBols_ref']) ;
	      $tipoLm = $row_referencia['tipoLamina_ref'];
	      $sqlrbols="SELECT valor_unitario_insumo FROM insumo WHERE id_insumo = '$tipoLm'";
	      $resultrbols= mysql_query($sqlrbols);
	      $numrbols = mysql_num_rows($resultrbols);
	      if($numrbols >='1')
	      { 
	      $valor_bols = mysql_result($resultrbols, 0, 'valor_unitario_insumo'); 
		  $costo_bols = ($valor_bols*$pesoMbols);
 	      }else{$costo_bols="0";}	
		   $COSTOMPSELLADO=($costoTermica+$costoHotmelOcinta+$costo_bols);
   		   echo numeros_format($COSTOMPSELLADO);//  si es cinta con todo
		  //FIN	
		  ?> </a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php  
	  $sqlsellh="SELECT COUNT(int_total_rollos_rp) AS Trollos, SEC_TO_TIME(SUM(TIME_TO_SEC(total_horas_rp))) AS horasT, SUM(costo) AS Tcosto FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp = '4'"; 
	  $resultsellh=mysql_query($sqlsellh); 
	  $numsellh=mysql_num_rows($resultsellh); 
	  if($numsellh >= '1') 
	  { 
	  $tHoras_sell=mysql_result($resultsellh,0,'horasT');
	  $costo_sell=mysql_result($resultsellh,0,'Tcosto'); 
	  $Trollos=mysql_result($resultsellh,0,'Trollos'); 
	  $horas_sell = horadecimalUna($tHoras_sell);
 	  echo $tHoras_sell;
  	  }
        ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php 	  
	  $sqldespsell="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='4'"; 
	  $resultdespsell=mysql_query($sqldespsell); 
	  $numdespsell=mysql_num_rows($resultdespsell); 
	  if($numdespsell >= '1') 
	  { 
	  echo $kilos_despsell=mysql_result($resultdespsell,0,'kgDespe');
	  }
	   ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong>
        <?php 
		/*if($KILOSREALESSELL !="")
	    {*/
		  //echo ($costo_sell/$Trollos); 
		  $sqlsell="SELECT DATE_FORMAT(fechaI_r,'%Y-%m-%d') AS FECHA FROM TblSelladoRollo WHERE id_op_r='$id_op' ORDER BY fechaI_r ASC"; 
		  $resultsell=mysql_query($sqlsell); 
		  $numsell=mysql_num_rows($resultsell); 
		  if($numsell >= '1') 
		   { 
		   $FECHA_NOVEDAD_SELL=mysql_result($resultsell,0,'FECHA');
		   }
   
  	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras` ORDER BY `fecha` DESC LIMIT 1";
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
	  if($numgeneral >='1')
	  { 
 	  $TiempomeSell =  mysql_result($resultgeneral, 0, 'sellado');
	  //SELLADO
	  $costoUnHGga_sell = mysql_result($resultgeneral, 0, 'gga_sell');
	  $costoUnHCif_sell = mysql_result($resultgeneral, 0, 'cif_sell');
	  $costoUnHGgv_sell = mysql_result($resultgeneral, 0, 'ggv_sell');
	  $costoUnHGgf_sell = mysql_result($resultgeneral, 0, 'ggf_sell');
	  $cifyggaSell=($costoUnHGga_sell+$costoUnHCif_sell+$costoUnHGgv_sell+$costoUnHGgf_sell);
	  }else{$TiempomeSell='0';} 

	//SUELDOS DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
	$sqlbasicoSell="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado NOT IN(4,5,6,7,8,9,10)";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos SE AGREGO b.estado_empleado='1' AND
	$resultbasicoSell=mysql_query($sqlbasicoSell);
    $operario_sell_demas=mysql_result($resultbasicoSell,0,'operarios');
	$sueldo_basSell=mysql_result($resultbasicoSell,0,'SUELDO'); //sueldos del mes 
	$auxilio_basSell=mysql_result($resultbasicoSell,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_basSell=mysql_result($resultbasicoSell,0,'APORTES'); //aportes del mes 
 	//$horasmes_bas=mysql_result($resultbasico,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 SE ENCUENTRA EN FACTOR
	$operarios_basSell=mysql_result($resultbasicoSell,0,'operarios');//CANTIDAD DE OPERARIOS 
	$horasdia_basSell=mysql_result($resultbasicoSell,0,'HORADIA');//esto es 8 
	 	 
	//NOVEDAD DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
 	$sqlnovbasicoSell="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado NOT IN(4,5,6,7,8,9,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-31')";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos
	$resultnovbasicoSell=mysql_query($sqlnovbasicoSell);	
	$pago_novbasicoSell=mysql_result($resultnovbasicoSell,0,'pago'); 
	$extras_novbasicoSell=mysql_result($resultnovbasicoSell,0,'extras');  
	$recargo_novbasicoSell=mysql_result($resultnovbasicoSell,0,'recargo');
	$festivo_novbasicoSell=mysql_result($resultnovbasicoSell,0,'festivos');
	$horasmes_sell='240';//240 mientras se define horas al mes
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
  	$valorhoraxoperSellDemas = sueldoMes($sueldo_basSell,$auxilio_basSell,$aportes_basSell,$horasmes_sell,$horasdia_basSell,$recargo_novbasicoSell,$festivo_novbasicoSell); 
	$valorHoraSellDemas = ($valorhoraxoperSellDemas/$operario_sell_demas)/3;//total Horas se divide por # de operarios de fuera de los procesos dividido en 3 q son los procesos
  
  	//SUELDOS DE TODOS LOS EMPLEADOS DENTRO DE SELLADO 
	$sqlbasicoSell="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado IN(7,9)";//IN(7,9) son sellado
	$resultbasicoSell=mysql_query($sqlbasicoSell);
	$operario_sell=mysql_result($resultbasicoSell,0,'operarios');
	$sueldo_basSell=mysql_result($resultbasicoSell,0,'SUELDO'); //sueldos del mes 
	$auxilio_basSell=mysql_result($resultbasicoSell,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_basSell=mysql_result($resultbasicoSell,0,'APORTES'); //aportes del mes 
	$horasdia_basSell=mysql_result($resultbasicoSell,0,'HORADIA');//esto es 8 
	$horasmes_sell='240';//240 mientras se define horas al mes
	 //FIN	 
	 //NOVEDAD DE ESE MES DE TODOS LOS EMPLEADOS DENTRO DE SELLADO 
  	$sqlnovbasicoSell="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado IN(7,9) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_SELL', '%Y-%m-31')";//IN(7,9)novedad sellado
	$resultnovbasicoSell=mysql_query($sqlnovbasicoSell);	
	$pago_novbasicoSell=mysql_result($resultnovbasicoSell,0,'pago'); 
	$extras_novbasicoSell=mysql_result($resultnovbasicoSell,0,'extras');  
	$recargo_novbasicoSell=mysql_result($resultnovbasicoSell,0,'recargo');
	$festivo_novbasicoSell=mysql_result($resultnovbasicoSell,0,'festivos');
	//FIN
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
 	$valorhoraTodosSell = sueldoMes($sueldo_basSell,$auxilio_basSell,$aportes_basSell,$horasmes_sell,$horasdia_basSell,$recargo_novbasicoSell,$festivo_novbasicoSell);
	$kiloXHoraSell=($KILOSREALESSELL/$horas_sell);//kilo x hora para los cif y gga
	$valorHoraSell = ($valorhoraTodosSell/$operario_sell);//total Horas se divide por # de operarios de Sellado	  
  	$costokiloInsumoSell=($COSTOMPSELLADO/$KILOSREALESSELL);//$ costo de 1 kilos mp
	$manoObraSell=($horas_sell*($valorHoraSell+$valorHoraSellDemas))/$KILOSREALESSELL;//$ costo de 1 kilo mano obra en 1 hora
  	$valorkilocifyggaSell=($cifyggaSell/$kiloXHoraSell);//valor por hora de cif y gga  
	 
 	 $costoSellado =($costokiloInsumoSell+$manoObraSell+$valorkilocifyggaSell);
	 echo numeros_format($costoSellado);
	// }//solamente imprime si tiene kilos en sellado
  	  ?></strong></a></td>
      <td id="dato3"><?php
	    $sqlCMP="SELECT valor_prod_rp, costo_mp FROM  Tbl_reg_kilo_producido WHERE Tbl_reg_kilo_producido.id_proceso_rkp='4' AND Tbl_reg_kilo_producido.op_rp = '$id_op'"; 
	   $resultCMP=mysql_query($sqlCMP); 
	   $numCMP=mysql_num_rows($resultCMP); 
	   $row_CMP = mysql_fetch_assoc($resultCMP);
	   $valorTS=0;
	 do{
          $undMP = $row_CMP['valor_prod_rp'];
		  $CMP = $row_CMP['costo_mp'];
		  $valorMP=$undMP*$CMP;
		  $valorTS+=$CMP;//ACUMULA VALOR POR ITEM 
    } while ($row_CMP = mysql_fetch_assoc($resultCMP));
       echo $valorTS;		  
/*	    $id_opcosto=$row_consumo['id_op'];
        $sqlcostoS="SELECT COUNT(`id_rp`) AS items, SUM(`costo`) AS costoT FROM Tbl_reg_produccion WHERE id_op_rp='$id_opcosto' AND id_proceso_rp='4'"; 
        $resultcostoS=mysql_query($sqlcostoS); 
        $numcostoS=mysql_num_rows($resultcostoS); 
        if($numcostoS >= '1') 
        { 
		 $costoS=mysql_result($resultcostoS,0,'costoT');
		 $itemsS=mysql_result($resultcostoS,0,'items');  
		 echo numeros_format($costoS/$itemsS);
        }*/
	   ?></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php 
	  $sqlrepro="SELECT SUM(reproceso_r) AS reproceso FROM TblSelladoRollo WHERE id_op_r='$id_op' ORDER BY fechaI_r ASC"; 
	  $resultrepro=mysql_query($sqlrepro); 
	  $numrepro=mysql_num_rows($resultrepro); 
	  if($numrepro >= '1') 
	  {  echo $reproceso_sell=mysql_result($resultrepro,0,'reproceso');
	  }?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000">
	  <?php
	  $costokiloBolsa=($costoExtrusion+$costoImpresion+$costoSellado);//$ kilo en sellado
	  if($KILOSREALESSELL !=''){
	  $costotoalBolsa = (($KILOSREALESSELL-$kilos_despsell)* $costokiloBolsa);//$total o.p
	  $costoBolsa=($costotoalBolsa/$bolsas_sell);//$costo bolsa
	  echo redondear_decimal_operar($costoBolsa);
	  }
 	  ?></a></td>
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php  
              	  $sqlcotiz="SELECT Tbl_items_ordenc.trm AS trm,Tbl_items_ordenc.str_unidad_io AS medida,Tbl_items_ordenc.int_precio_io AS precio,Tbl_items_ordenc.str_moneda_io AS moneda,Tbl_orden_produccion.int_undxpaq_op FROM Tbl_orden_produccion,Tbl_items_ordenc WHERE Tbl_orden_produccion.id_op = $id_op  AND Tbl_orden_produccion.int_cod_ref_op = Tbl_items_ordenc.int_cod_ref_io ORDER BY Tbl_items_ordenc.fecha_entrega_io DESC LIMIT 1";				
				  $resultcotiz=mysql_query($sqlcotiz); 
				  $numcotiz=mysql_num_rows($resultcotiz); 
				  $moneda=mysql_result($resultcotiz,0,'moneda');
				  $medida=mysql_result($resultcotiz,0,'medida');
				  $precioCotiz=mysql_result($resultcotiz,0,'precio');
				  $trmCotiz=mysql_result($resultcotiz,0,'trm');
                  $undPaquetes=mysql_result($resultcotiz,0,'int_undxpaq_op'); //unidad x paquetes
				  echo /*$moneda.' '.*/$precioCotiz;
				  $precioCotiz_sell = unidadMedida($medida,$precioCotiz,$undPaquetes,$trmCotiz);
				 ?></a></td>
       <td id="dato3" nowrap><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php 
	   //rentabilidad 
	   if($KILOSREALESSELL !="")
	   {
	   $utilidadSell=($precioCotiz_sell-$costoBolsa); 
  	   $rentabil = porcentaje2($precioCotiz_sell,$utilidadSell,0);
 	   if($rentabil < 0) {?>
       <h4 style="color:#F00"> <?php echo $rentabil.' %';?> </h4>
       <?php }else{ echo $rentabil.' %'; 
	    }
	   }
	   ?></a></td> 
      <td id="dato3"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo numeros_format($costoExtrusion+$costoImpresion+$costoSellado);?></strong></a></td>
      <td id="dato4"><a href="costos_op_add.php?id_op=<?php echo $row_consumo['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
        <?php $estados=$row_consumo['b_estado_op']; 
						switch ($estados){
							case 0: echo "INGRESADA";
							break;
							case 1: echo "EXTRUSION";
							break;
							case 2: echo "IMPRESION";
							break;
							case 3: echo "REFILADO";
							break;
							case 4: echo "SELLADO";
							break;
							case 5: echo "FINALIZADA";
							break;							
							}
	    ?>
      </a></td>
    </tr>
    <?php } while ($row_consumo = mysql_fetch_assoc($fichas_tecnicas)); ?>
</table>
 
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_fichas_tecnicas > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_fichas_tecnicas=%d%s", $currentPage, 0, $queryString_fichas_tecnicas); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center" id="dato2"><?php if ($pageNum_fichas_tecnicas > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_fichas_tecnicas=%d%s", $currentPage, max(0, $pageNum_fichas_tecnicas - 1), $queryString_fichas_tecnicas); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_fichas_tecnicas < $totalPages_fichas_tecnicas) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_fichas_tecnicas=%d%s", $currentPage, min($totalPages_fichas_tecnicas, $pageNum_fichas_tecnicas + 1), $queryString_fichas_tecnicas); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center" id="dato2"><?php if ($pageNum_fichas_tecnicas < $totalPages_fichas_tecnicas) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_fichas_tecnicas=%d%s", $currentPage, $totalPages_fichas_tecnicas, $queryString_fichas_tecnicas); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table></td>
  </tr></table>
  </div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($lista);

mysql_free_result($fichas_tecnicas);

mysql_free_result($referencias);
?>
