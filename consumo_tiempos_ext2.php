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
$proceso='1';

//Filtra todos vacios
if($fecha1 == '' && $fecha2 == '' && $id_op == '')
{
$query_costos = "SELECT * FROM Tbl_orden_produccion WHERE b_estado_op > 0 AND DATE(fecha_registro_op) BETWEEN '$fecha1' AND '$fecha2' ORDER BY id_op DESC";	
}
//Filtra fecha lleno
if($fecha1 != '' && $fecha2 != '' && $id_op == '')
{
$query_costos = "SELECT * FROM Tbl_orden_produccion WHERE b_estado_op > 0 AND DATE(fecha_registro_op) BETWEEN '$fecha1' AND '$fecha2' ORDER BY id_op DESC";	
}
//Filtra todo lleno
if($fecha1 != '' && $fecha2 != '' && $id_op != '')
{
$query_costos = "SELECT * FROM Tbl_orden_produccion WHERE b_estado_op > 0 AND DATE(fecha_registro_op) BETWEEN '$fecha1' AND '$fecha2' AND int_cod_ref_op='$id_op' ORDER BY id_op DESC";	
}
$query_limit_costos = sprintf("%s LIMIT %d, %d", $query_costos, $startRow_costos, $maxRows_costos);
$costos = mysql_query($query_limit_costos,$conexion1) or die(mysql_error());
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
$query_orden_produccion = "SELECT cod_ref FROM Tbl_referencia order by id_ref desc";
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
<form action="consumo_tiempos_ext2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td colspan="3" nowrap="nowrap" id="codigo" >CODIGO : R1 - F03</td>
<td colspan="3" nowrap="nowrap" id="titulo2" >TIEMPOS DE EXTRUSION</td>
<td colspan="2" nowrap="nowrap" id="codigo" >VERSION : 2</td>
</tr>
<tr>
  <td colspan="3" nowrap="nowrap" id="codigo3">&nbsp;</td>
  <td colspan="3" nowrap="nowrap" id="titulo2">&nbsp;</td>
  <td colspan="2" nowrap="nowrap" id="codigo3">&nbsp;</td>
</tr>
<tr>
  <td colspan="8" id="fuente2">FECHA INICIO:
    <input name="fecha_ini" type="date" id="fecha_ini" required="required"  min="2000-01-02" size="10" value="<?php echo $_GET['fecha_ini']; ?>"/>
FECHA FIN:
<input name="fecha_fin" type="date" id="fecha_fin" required="required" min="2000-01-02" size="10" value="<?php echo $_GET['fecha_fin']; ?>"/>
REF:
<select name="id_op" id="id_op">
  <option value=""<?php if (!(strcmp('', $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>REF</option>
  <?php
do {  
?>
  <option value="<?php echo $row_orden_produccion['cod_ref']?>"<?php if (!(strcmp($row_orden_produccion['cod_ref'], $_GET['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_orden_produccion['cod_ref']?></option>
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
    <td colspan="4" id="dato1">&nbsp; </td> 
    <td colspan="2" id="dato3"><a href="produccion_registro_extrusion_listado.php"><img src="images/e.gif" alt="LISTADO EXTRUSION"title="LISTADO EXTRUSION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_impresion_listado.php"><img src="images/i.gif" alt="LISTADO IMPRESION"title="LISTADO IMPRESION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_sellado_listado.php"><img src="images/s.gif" alt="LISTADO SELLADO"title="LISTADO SELLADO" border="0" style="cursor:hand;" /></a><a href="costos_listado_ref_xproceso.php"></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;" /></a><a href="costos_listado_ref_xproceso.php"><img src="images/rp.gif" alt="LISTADO REF KILOS X PROCESO"title="LISTADO REF KILOS X PROCESO" border="0" style="cursor:hand;" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td> 
    <td id="dato3">&nbsp;</td>
    <td id="dato3"><input type="button" value="Descarga Excel" onClick="window.location = 'consumo_tiempos_ext_excel.php?fecha_ini=<?php echo $_GET['fecha_ini'] ?>&fecha_fin=<?php echo $_GET['fecha_fin'] ?>&id_op=<?php echo $_GET['id_op']?>'" /></td>
  </tr>
 </table>
<table id="tabla1" >    
    <tr id="tr1">
      <td nowrap="nowrap" id="titulo4">ORDEN.P</td>
    <td nowrap="nowrap" id="titulo4">REF.</td>
     <td nowrap="nowrap" id="titulo4">EXTRUSION/kg</td>
     <td nowrap="nowrap" id="titulo4">DESP.</td>
    <td nowrap="nowrap" id="titulo4">Tiempo Total</td>
    <td nowrap="nowrap" id="titulo4">Horas %</td>
    <td nowrap="nowrap" id="titulo4">Horas/Muertas</td>
    <td nowrap="nowrap" id="titulo4">Horas/Prep</td>
    <td nowrap="nowrap" id="titulo4">PROMEDIO</td>
    <td nowrap="nowrap" id="titulo4">FECHA INICIAL</td>
    <td nowrap="nowrap" id="titulo4">FECHA FINAL</td>
    </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato3"><?php echo $row_costos['id_op'];?></td>
    <td id="dato3"><?php echo $row_costos['int_cod_ref_op'];?></td>
       <td id="dato3"><?php 
	  $ref=$row_costos['id_op'];
	  $sqlex="SELECT SUM(int_kilos_prod_rp) AS totalKilos,fecha_ini_rp ,fecha_fin_rp FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$proceso'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { 
	  $totalkilos=mysql_result($resultex,0,'totalKilos');
	  $fechaIni=mysql_result($resultex,0,'fecha_ini_rp'); 
	  $fechaFin=mysql_result($resultex,0,'fecha_fin_rp');  
	  echo numeros_format($totalkilos);}else{echo "0";} 
	  ?></td>
       <td id="dato3"><?php 
	    $id_op=$row_costos['id_op']; 
	    $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='$proceso'"; 
	    $resultexd=mysql_query($sqlexd); 
	    $numexd=mysql_num_rows($resultexd); 
	    if($numexd >= '1') 
	    { $kilos_exd=mysql_result($resultexd,0,'kgDespe'); echo numeros_format($kilos_exd); }else {echo "0";}
	  ?></td>       
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op'];
	  $sqlex="SELECT SEC_TO_TIME(SUM( TIME_TO_SEC(`total_horas_rp`)))  AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$proceso'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $tHoras_ex=mysql_result($resultex,0,'horasT'); echo  $tHoras_ex; }	
	  else { 
	  echo "0";
	  }?></td>
      <td id="dato3"><?php 
      $totalext=horadecimal($tHoras_ex);
	  echo  $totalext; ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op']; 
	  $sqlexm="SELECT SUM(valor_tiem_rt) AS horasM FROM Tbl_reg_tiempo WHERE op_rt='$ref' AND id_proceso_rt='$proceso'"; 
	  $resultexm=mysql_query($sqlexm); 
	  $numexm=mysql_num_rows($resultexm); 
	  if($numexm >= '1') 
	  { 
	  $horasM_ex=mysql_result($resultexm,0,'horasM');echo dosDecimalesSinMiles($horasM_ex/60);}
	  else
	  {echo "0";}
	  ?></td> 
      <td id="dato3">
      <?php 
	  $ref=$row_costos['id_op']; 
	  $sqlexp="SELECT SUM(valor_prep_rtp) AS horasP FROM Tbl_reg_tiempo_preparacion WHERE op_rtp='$ref' AND id_proceso_rtp='$proceso'"; 
	  $resultexp=mysql_query($sqlexp); 
	  $numexp=mysql_num_rows($resultexp); 
	  if($numexp >= '1') 
	  { $horasP_ex=mysql_result($resultexp,0,'horasP'); 
	  echo dosDecimalesSinMiles($horasP_ex/60); }
	  else
	  {echo "0";}
	  ?></td>
      <td id="dato3" nowrap="nowrap"><?php echo dosDecimalesSinMiles($totalkilos/$tHoras_ex);?></td>
      <td id="dato3" nowrap="nowrap"><?php echo quitarHora($fechaIni);?></td>
      <td id="dato3" nowrap="nowrap"><?php echo quitarHora($fechaFin);?></td>                        
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