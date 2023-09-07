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
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_proceso = "SELECT * FROM tipo_procesos ORDER BY id_tipo_proceso ASC";
$procesos = mysql_query($query_proceso, $conexion1) or die(mysql_error());
$row_proceso = mysql_fetch_assoc($procesos);
$totalRows_proceso = mysql_num_rows($procesos);
//FECHAS DE IMPRESION
$nuevafecha = date('Y-m-01');	
$fecha1=$nuevafecha;
$fecha2= date("Y-m-d");

$maxRows_costos = 20;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$query_costos = "SELECT *
FROM Tbl_orden_produccion,Tbl_reg_produccion 
WHERE Tbl_orden_produccion.b_estado_op > 0 AND Tbl_orden_produccion.id_op=Tbl_reg_produccion.id_op_rp AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1'
AND  '$fecha2' and
DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1'
AND  '$fecha2'
GROUP BY Tbl_reg_produccion.int_cod_ref_rp DESC";
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
<form action="costos_listado_ref_xproceso2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td colspan="3" owrap="nowrap" id="codigo" width="25%">CODIGO : R1 - F03</td>
<td colspan="6" nowrap="nowrap" id="titulo2" width="50%">CONSUMO DE M.P UTILIZADOS POR O.P</td>
<td colspan="3" nowrap="nowrap" id="codigo" width="25%">VERSION : 2</td>
</tr>
<tr>
  <td colspan="12" id="fuente2">FECHA INICIO:
   <input name="fecha_ini" type="date" id="fecha_ini" required="required"  min="2000-01-02" size="10" value="<?php echo first_month_day();?>"/>
FECHA FIN:
<input name="fecha_fin" type="date" id="fecha_fin" min="2000-01-02" size="10" required="required" value="<?php echo last_month_day();?>" onChange="if(form1.fecha_ini.value && form1.fecha_fin.value) { consulta_ref_xproceso(); }else { alert('Debe seleccionar las dos fechas')}"/>
O.P
<select name="id_op" id="id_op">
  <option value=""<?php if (!(strcmp('', $_GET['id_op']))) {echo "selected=\"selected\"";} ?>>OP</option>
  <?php
do {  
?>
  <option value="<?php echo $row_orden_produccion['id_op']?>"><?php echo $row_orden_produccion['id_op']?></option>
  <?php
} while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion));
  $rows = mysql_num_rows($orden_produccion);
  if($rows > 0) {
      mysql_data_seek($orden_produccions, 0);
	  $row_orden_produccion = mysql_fetch_assoc($orden_produccion);
  }
?>
</select>
<input type="submit" name="submit" id="submit" value="Consultar" /></td>
  </tr>
  <tr>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td id="dato3">&nbsp;</td>
    <td colspan="5" id="dato3"><a href="produccion_registro_extrusion_listado.php"><img src="images/e.gif" alt="LISTADO EXTRUSION"title="LISTADO EXTRUSION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_impresion_listado.php"><img src="images/i.gif" alt="LISTADO IMPRESION"title="LISTADO IMPRESION" border="0" style="cursor:hand;" /></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    <td colspan="2" id="dato3"><input type="button" value="Excel Completo" onclick="window.location = 'costos_listado_ref_xproceso_excel.php?tipoListado=1'" /></td>
    </tr>  
  <tr id="tr1">
    <td nowrap="nowrap" id="titulo4">ORDEN P.</td>
    <td nowrap="nowrap" id="titulo4">REF.</td>
    <td nowrap="nowrap" id="titulo4">ROLLOS</td>
    <td nowrap="nowrap" id="titulo4">FECHA INGRESO</td>
    <td nowrap="nowrap" id="titulo4">EXTRUSION/kg</td>
    <td nowrap="nowrap" id="titulo4">DESP/kg</td>
    <td nowrap="nowrap" id="titulo4">IMPRESION/kg</td>
    <td nowrap="nowrap" id="titulo4">DESP/kg</td>
    <td nowrap="nowrap" id="titulo4">REFILADO/kg</td>
    <td nowrap="nowrap" id="titulo4">DESP/kg</td>
    <td nowrap="nowrap" id="titulo4">SELLADO/kg</td>
    <td nowrap="nowrap" id="titulo4">DESP/kg</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato2"><?php echo $row_costos['id_op_rp'] ?></td>
      <td id="dato2"><?php echo $row_costos['int_cod_ref_rp'];?></td>
      <td nowrap="nowrap" id="dato2"><?php
	  $proceso= $row_costos['id_proceso_rp'];
	  $op=$row_costos['id_op_rp'];
	  switch ($proceso) {
	  case 1: $BD="SELECT COUNT(rollo_r) AS rollo FROM TblExtruderRollo WHERE id_op_r='$op'";break;
	  case 2: $BD="SELECT COUNT(rollo_r) AS rollo FROM TblImpresionRollo WHERE id_op_r='$op'";break;
	  case 3: $BD="SELECT COUNT(rollo_r) AS rollo FROM TblRefiladoRollo WHERE id_op_r='$op'";break;
	  case 4: $BD="SELECT (rollo_r) AS rollo FROM TblSelladoRollo WHERE id_op_r='$op' GROUP BY `rollo_r`";break;//se agrupa porque se guarda tanto por rollo como por turno
	  } 
	  $sqlroll=$BD; 
	  $resultroll=mysql_query($sqlroll); 
	  $numroll=mysql_num_rows($resultroll); 
	  if($numroll >= '1') 
	  { $rollo=mysql_result($resultroll,0,'rollo'); echo $rollo;}else {echo "no existe";}
	  ?></td>
      <td nowrap="nowrap" id="dato2"><?php echo quitarHora($row_costos['fecha_ini_rp']);?></td>      
      <td id="dato2">
      <?php 
	  $op=$row_costos['id_op_rp'];
	  $sqlex="SELECT SUM(valor_prod_rp) AS kge FROM Tbl_reg_kilo_producido WHERE op_rp='$op' AND id_proceso_rkp='1'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $kilos_ex=mysql_result($resultex,0,'kge'); $materiaP=mysql_result($resultex,0,'id_rpp_rp');echo numeros_format($kilos_ex); }else {echo "0";}
	  ?></td>
      <td id="dato2">
      <?php 
	  $op=$row_costos['id_op_rp'];
	  $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$op' AND id_proceso_rd='1'"; 
	  $resultexd=mysql_query($sqlexd); 
	  $numexd=mysql_num_rows($resultexd); 
	  if($numexd >= '1') 
	  { $kilos_exd=mysql_result($resultexd,0,'kgDespe'); echo numeros_format($kilos_exd); }else {echo "0";}
	  ?></td>            
      <td id="dato2">
	  <?php 
	  $op=$row_costos['id_op_rp'];
	  $sqlim="SELECT SUM(int_kilos_prod_rp) AS kgi FROM Tbl_reg_produccion WHERE id_op_rp='$op' AND id_proceso_rp='2'"; 
	  $resultim=mysql_query($sqlim); 
	  $numim=mysql_num_rows($resultim); 
	  if($numim >= '1') 
	  { $kilos_im=mysql_result($resultim,0,'kgi'); echo numeros_format($kilos_im); }else {echo "0";}
	  ?></td> 
      <td id="dato2">
      <?php 
	  $op=$row_costos['id_op_rp'];
	  $sqlimd="SELECT SUM(int_kilos_desp_rp) AS kgDespi FROM Tbl_reg_produccion WHERE id_op_rp='$op' AND id_proceso_rp='2'";
	  $resultimd=mysql_query($sqlimd); 
	  $numimd=mysql_num_rows($resultimd); 
	  if($numimd >= '1') 
	  { $kilos_imd=mysql_result($resultimd,0,'kgDespi'); echo numeros_format($kilos_imd); }else  {echo "0";}	
	  ?></td>
      <td id="dato2"><?php 
	  $op=$row_costos['id_op_rp'];
	  $sqlre="SELECT SUM(int_kilos_prod_rp) AS kgi FROM Tbl_reg_produccion WHERE id_op_rp='$op' AND id_proceso_rp='3'"; 
	  $resultre=mysql_query($sqlre); 
	  $numim=mysql_num_rows($resultre); 
	  if($numre >= '1') 
	  { $kilos_re=mysql_result($resultre,0,'kgi'); echo numeros_format($kilos_re); }else {echo "0";}
	  ?></td>
      <td id="dato2"><?php 
	  $op=$row_costos['id_op_rp'];
	  $sqlred="SELECT SUM(int_kilos_desp_rp) AS kgDespi FROM Tbl_reg_produccion WHERE id_op_rp='$op' AND id_proceso_rp='3'";
	  $resultred=mysql_query($sqlred); 
	  $numred=mysql_num_rows($resultred); 
	  if($numred >= '1') 
	  { $kilos_exd=mysql_result($resultred,0,'kgDespi'); echo numeros_format($kilos_red); }else  {echo "0";}	
	  ?></td>           
      <td id="dato2">
	  <?php 
	  $op=$row_costos['id_op_rp'];
	  $sqlse="SELECT SUM(int_kilos_prod_rp) AS kgs FROM Tbl_reg_produccion WHERE id_op_rp='$op' AND id_proceso_rp='4'";  
	  $resultse=mysql_query($sqlse); 
	  $numse=mysql_num_rows($resultse); 
	  if($numse >= '1') 
	  { $kilos_se=mysql_result($resultse,0,'kgs'); echo numeros_format($kilos_se); }else {echo "0";}
	  ?></td>
      <td id="dato2">
      <?php 
	  $op=$row_costos['id_op_rp'];
	  $sqlexd="SELECT SUM(int_kilos_desp_rp) AS kgDesps FROM Tbl_reg_produccion WHERE id_op_rp='$op' AND id_proceso_rp='4'";
	  $resultexd=mysql_query($sqlexd); 
	  $numexd=mysql_num_rows($resultexd); 
	  if($numexd >= '1') 
	  { $kilos_sed=mysql_result($resultexd,0,'kgDesps'); echo numeros_format($kilos_sed); }else {echo "0";}
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