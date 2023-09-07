<?php require_once('Connections/conexion1.php'); ?>
<?php
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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
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

$maxRows_costos = 20;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$fecha1 = $_GET['fecha_ini'];
$fecha2 = $_GET['fecha_fin'];
$id_op = $_GET['id_op'];
//Filtra fecha lleno
if($fecha1 != '' && $fecha2 != '' && $id_op == '')
{
$query_costos = "SELECT DATE(Tbl_reg_produccion.fecha_ini_rp) AS FECHA,Tbl_reg_produccion.id_op_rp, Tbl_reg_produccion.id_proceso_rp, Tbl_reg_produccion.int_cod_ref_rp,  Tbl_reg_produccion.int_kilos_prod_rp, Tbl_reg_produccion.int_kilos_desp_rp,   Tbl_reg_produccion.fecha_fin_rp 
FROM Tbl_orden_produccion,Tbl_reg_produccion 
WHERE Tbl_orden_produccion.b_estado_op > 0 AND Tbl_orden_produccion.id_op=Tbl_reg_produccion.id_op_rp AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1'
AND  '$fecha2' and
DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1'
AND  '$fecha2'
GROUP BY Tbl_reg_produccion.int_cod_ref_rp DESC";	
}
//Filtra todo lleno
if($fecha1 != '' && $fecha2 != '' && $id_op != '')
{
$query_costos = "SELECT DATE(Tbl_reg_produccion.fecha_ini_rp) AS FECHA,Tbl_reg_produccion.id_op_rp, Tbl_reg_produccion.id_proceso_rp, Tbl_reg_produccion.int_cod_ref_rp,  Tbl_reg_produccion.int_kilos_prod_rp, Tbl_reg_produccion.int_kilos_desp_rp,   Tbl_reg_produccion.fecha_fin_rp 
FROM Tbl_orden_produccion,Tbl_reg_produccion 
WHERE Tbl_orden_produccion.b_estado_op > 0 AND Tbl_orden_produccion.id_op=$id_op AND Tbl_orden_produccion.id_op=Tbl_reg_produccion.id_op_rp AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1'
AND  '$fecha2' and
DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1'
AND  '$fecha2'
GROUP BY Tbl_reg_produccion.int_cod_ref_rp DESC";	
}
//Filtra op lleno
if($id_op != '')
{
$query_costos = "SELECT DATE(Tbl_reg_produccion.fecha_ini_rp) AS FECHA,Tbl_reg_produccion.id_op_rp, Tbl_reg_produccion.id_proceso_rp, Tbl_reg_produccion.int_cod_ref_rp,  Tbl_reg_produccion.int_kilos_prod_rp, Tbl_reg_produccion.int_kilos_desp_rp,   Tbl_reg_produccion.fecha_fin_rp 
FROM Tbl_orden_produccion,Tbl_reg_produccion 
WHERE Tbl_orden_produccion.b_estado_op > 0 AND Tbl_orden_produccion.id_op=$id_op AND Tbl_orden_produccion.id_op=Tbl_reg_produccion.id_op_rp 
GROUP BY Tbl_reg_produccion.int_cod_ref_rp DESC";	
}
$query_limit_costos = sprintf("%s LIMIT %d, %d", $query_costos, $startRow_costos, $maxRows_costos);
$costos = mysql_query($query_limit_costos, $conexion1) or die(mysql_error());
$row_costos = mysql_fetch_assoc($costos);

if (isset($_GET['totalRows_costos'])) {
  $totalRows_costos = $_GET['totalRows_costos'];
} else {
  $all_costos = mysql_query($query_costos);
  $totalRows_costos = mysql_num_rows($all_costos);
}
$totalPages_costos = ceil($totalRows_costos/$maxRows_costos)-1;

$queryString_costos = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_costos") == false && 
        stristr($param, "totalRows_costos") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_costos = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_costos = sprintf("&totalRows_costos=%d%s", $totalRows_costos, $queryString_costos);

mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = "SELECT id_op FROM Tbl_orden_produccion WHERE b_estado_op > 0 ORDER BY id_op DESC";
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion)
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body><div align="center">

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
   <td colspan="2" align="center" id="linea1">
<form action="costos_listado_ref_xproceso_tiempos2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td colspan="8" owrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
<td colspan="7" nowrap="nowrap" id="titulo2" width="50%">TIEMPOS DE ORDEN DE PRODUCCION POR PROCESO</td>
<td colspan="8" nowrap="nowrap" id="codigo" width="25%">VERSION : 2</td>
</tr>
<tr>
  <td colspan="8" owrap="nowrap" id="codigo3">&nbsp;</td>
  <td colspan="7" nowrap="nowrap" id="titulo2">&nbsp;</td>
  <td colspan="8" nowrap="nowrap" id="codigo3">&nbsp;</td>
</tr>
<tr>
  <td colspan="23" id="fuente2">FECHA INICIO:
    <input name="fecha_ini" type="date" id="fecha_ini" required="required"  min="2000-01-02" size="10" value="<?php echo $_GET['fecha_ini']; ?>"/>
FECHA FIN:
<input name="fecha_fin" type="date" id="fecha_fin" required="required" min="2000-01-02" size="10" value="<?php echo $_GET['fecha_fin']; ?>"/>
O.P
<select name="id_op" id="id_op">
  <option value=""<?php if (!(strcmp('', $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>OP</option>
  <?php
do {  
?>
  <option value="<?php echo $row_orden_produccion['id_op']?>"<?php if (!(strcmp($row_orden_produccion['id_op'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_orden_produccion['id_op']?></option>
  <?php
} while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion));
  $rows = mysql_num_rows($orden_produccion);
  if($rows > 0) {
      mysql_data_seek($orden_produccions, 0);
	  $row_orden_produccion = mysql_fetch_assoc($orden_produccion);
  }
?>
</select>
<input type="submit" name="submit" value="FILTRO"/></td>
  </tr>
  <tr>
    <td colspan="11" id="dato1">Nota: Los tiempos muertos y de preparacion estan en minutos y si algun proceso no aprarece debe estar en otra fecha o no procesado</td>
    <td id="dato3">&nbsp;</td>
    <td colspan="8" id="dato3"><a href="produccion_registro_extrusion_listado.php"><img src="images/e.gif" alt="LISTADO EXTRUSION"title="LISTADO EXTRUSION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_impresion_listado.php"><img src="images/i.gif" alt="LISTADO IMPRESION"title="LISTADO IMPRESION" border="0" style="cursor:hand;" /></a><a href="costos_listado_ref_xproceso.php"></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;" /></a><a href="costos_listado_ref_xproceso.php"><img src="images/rp.gif" alt="LISTADO REF KILOS X PROCESO"title="LISTADO REF KILOS X PROCESO" border="0" style="cursor:hand;" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3"><input type="button" value="Excel Completo" onclick="window.location = 'costos_listado_ref_xproceso_tiempos_excel.php?tipoListado=1'" /></td>
    <td id="dato3"><input type="button" value="Excel Fecha" onClick="window.location = 'costos_listado_ref_xproceso_tiempos_excel.php?tipoListado=2&fecha_ini=<?php echo $fecha1 ?>&fecha_fin=<?php echo $fecha2 ?>'" /></td>
  </tr>  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">ORDEN P.</td>
    <td nowrap="nowrap" id="titulo4">REF.</td>
    <td nowrap="nowrap" id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap" id="titulo4">EXTRUSION/kg</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas %</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Muertos</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Prep</td>
    <td nowrap="nowrap" id="titulo4">IMPRESION/kg</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Muertos</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Prep</td>
    <td nowrap="nowrap" id="titulo4">REFILADO/kg</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Muertos</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Prep</td>
    <td nowrap="nowrap" id="titulo4">SELLADO/kg</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Muertos</td>
    <td nowrap="nowrap" id="titulo4">Minutos/Prep</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato1"><?php echo $row_costos['id_op_rp'];?></td>
      <td id="dato3"><?php echo $row_costos['int_cod_ref_rp'];?></td>
      <td id="dato3" nowrap="nowrap"><?php echo $row_costos['FECHA'];?></td>
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlex="SELECT SUM(int_kilos_prod_rp) AS kgT FROM Tbl_reg_produccion WHERE `id_op_rp`='$ref' AND DATE(fecha_ini_rp) BETWEEN '$fecha1' AND '$fecha2' AND DATE(fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' AND id_proceso_rp='1'";
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $kgT_ex=mysql_result($resultex,0,'kgT'); echo numeros_format($kgT_ex);} if ($kgT_ex=='') {echo "0";}
	  ?></td>       
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlex="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='1'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $tHoras_ex=mysql_result($resultex,0,'horasT'); echo  $tHoras_ex; }if ($tHoras_ex=='') {echo "00:00:00";}	
	  else { echo "0";
	  }?></td>
      <td id="dato3"><?php 
      $totalext=horadecimal($tHoras_ex);
	  echo  $totalext; ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp']; 
	  $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND id_proceso_rt='1'"; 
	  $resultexm=mysql_query($sqlexm); 
	  $numexm=mysql_num_rows($resultexm); 
	  if($numexm >= '1') 
	  { $horasM_ex=mysql_result($resultexm,0,'horasM'); echo $horasM_ex; }if ($horasM_ex==NULL){ echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp']; 
	  $sqlexp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND id_proceso_rtp='1'"; 
	  $resultexp=mysql_query($sqlexp); 
	  $numexp=mysql_num_rows($resultexp); 
	  if($numexp >= '1') 
	  { $horasP_ex=mysql_result($resultexp,0,'horasP'); echo $horasP_ex; }if ($horasP_ex==NULL) {echo "0";}
	  ?></td>                        
      <td id="dato3">
	  <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlim="SELECT SUM(int_kilos_prod_rp) AS kgT FROM Tbl_reg_produccion WHERE `id_op_rp`='$ref' AND DATE(fecha_ini_rp) BETWEEN '$fecha1' AND '$fecha2' AND DATE(fecha_fin_rp) BETWEEN '$fecha1' AND '$fecha2' AND id_proceso_rp='2'"; 
	  $resultim=mysql_query($sqlim); 
	  $numim=mysql_num_rows($resultim); 
	  if($numim >= '1') 
	  { $kgT_im=mysql_result($resultim,0,'kgT'); echo numeros_format($kgT_im); }if ($kgT_im==NULL) {echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp']; 
	  $sqlim="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='2'";
	  $resultim=mysql_query($sqlim); 
	  $numim=mysql_num_rows($resultim); 
	  if($numim >= '1') 
	  { $tHoras_im=mysql_result($resultim,0,'horasT'); echo  $tHoras_im;}if ($tHoras_im==NULL) {echo "00:00:00";}
	  ?></td>
      <td id="dato3"><?php 
	  $totalimp=horadecimal($tHoras_im);  
	  echo $totalimp; ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp']; 
	  $sqlimm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND id_proceso_rt='2'"; 
	  $resultimm=mysql_query($sqlimm); 
	  $numimm=mysql_num_rows($resultimm); 
	  if($numimm >= '1') 
	  { $horasM_imm=mysql_result($resultimm,0,'horasM'); echo $horasM_imm; }if ($horasM_imm==NULL) {echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp']; 
	  $sqlimp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND id_proceso_rtp='2'"; 
	  $resultimp=mysql_query($sqlimp); 
	  $numimp=mysql_num_rows($resultimp); 
	  if($numimp >= '1') 
	  { $horasP_im=mysql_result($resultimp,0,'horasP'); echo $horasP_im; }if ($horasP_im==NULL) {echo "0";}
	  ?></td>
      <td id="dato3"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlre="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='3'"; 
	  $resultre=mysql_query($sqlre); 
	  $numre=mysql_num_rows($resultre); 
	  if($numre >= '1') 
	  { $kgT_re=mysql_result($resultre,0,'kgT'); echo numeros_format($kgT_re); }if ($kgT_re==NULL) {echo "0";}
	  ?></td>
      <td id="dato3"><?php 
	  $ref=$row_costos['id_op_rp']; 
	  $sqlret="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fechaF_r, fechaI_r )))) AS horasT FROM TblRefiladoRollo WHERE id_op_r='$ref'";
	  $resultret=mysql_query($sqlret); 
	  $numret=mysql_num_rows($resultret); 
	  if($numret >= '1') 
	  { $tHoras_ret=mysql_result($resultret,0,'horasT'); echo  $tHoras_ret; }if ($tHoras_ret==NULL) {echo "00:00:00";}
	  ?></td>
      <td id="dato3"><?php 
	  $totalref=horadecimal($tHoras_ret);	  
	  echo $totalref; ?></td>
      <td id="dato3"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlret="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND id_proceso_rt='3'";  
	  $resultret=mysql_query($sqlret); 
	  $numret=mysql_num_rows($resultret); 
	  if($numret >= '1') 
	  { $horasM_ret=mysql_result($resultret,0,'horasM'); echo $horasM_ret; }if ($horasM_ret==NULL) {echo "0";}
	  ?></td>
      <td id="dato3"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlrep="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND id_proceso_rtp='3'";  
	  $resultrep=mysql_query($sqlrep); 
	  $numrep=mysql_num_rows($resultrep); 
	  if($numrep >= '1') 
	  { $horasP_rep=mysql_result($resultrep,0,'horasP'); echo $horasP_rep; }if ($horasP_rep==NULL) {echo "0";}
	  ?></td>                    
      <td id="dato3">
	  <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlse="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`))) AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='4'"; 
	  $resultse=mysql_query($sqlse); 
	  $numse=mysql_num_rows($resultse); 
	  if($numse >= '1') 
	  { $kgT_se=mysql_result($resultse,0,'kgT'); echo numeros_format($kgT_se); }if ($kgT_se==NULL) {echo "0";}
	  ?></td>
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlse="SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(fechaF_r, fechaI_r )))) AS horasT FROM TblSelladoRollo WHERE id_op_r='$ref'";
	  $resultse=mysql_query($sqlse); 
	  $numse=mysql_num_rows($resultse); 
	  if($numse >= '1') 
	  { $tHoras_se=mysql_result($resultse,0,'horasT'); echo  $tHoras_se; }if ($tHoras_se==NULL) {echo "00:00:00";}
	  ?></td>
      <td id="dato3"><?php 
	  $totalsell=horadecimal($tHoras_se);	  
	  echo $totalsell; ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlsem="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND id_proceso_rt='4'";  
	  $resultsem=mysql_query($sqlsem); 
	  $numsem=mysql_num_rows($resultsem); 
	  if($numsem >= '1') 
	  { $horasM_sem=mysql_result($resultsem,0,'horasM'); echo $horasM_sem; }if ($horasM_sem==NULL) {echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op_rp']; 
	  $sqlsep="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND id_proceso_rtp='4'";  
	  $resultsep=mysql_query($sqlsep); 
	  $numsep=mysql_num_rows($resultsep); 
	  if($numsep >= '1') 
	  { $horasP_se=mysql_result($resultsep,0,'horasP'); echo $horasP_se; }if ($horasP_se==NULL) {echo "0";}
	  ?></td>                                     
    </tr>
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>
<table id="tabla1">
  <tr>
    <td id="dato1" width="25%"><?php if ($pageNum_costos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, 0, $queryString_costos); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, max(0, $pageNum_costos - 1), $queryString_costos); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, min($totalPages_costos, $pageNum_costos + 1), $queryString_costos); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td id="dato1" width="25%"><?php if ($pageNum_costos < $totalPages_costos) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_costos=%d%s", $currentPage, $totalPages_costos, $queryString_costos); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>
</form>
</td>
  </tr>
</table>
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

mysql_free_result($costos);

?>