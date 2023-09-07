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


$nuevafecha = date('Y-m-01');	
$fecha1=first_month_day();;
$fecha2= date("Y-m-d");
$proceso='1';
$maquina='1';

$maxRows_costos = 20;
$pageNum_costos = 0;
if (isset($_GET['pageNum_costos'])) {
  $pageNum_costos = $_GET['pageNum_costos'];
}
$startRow_costos = $pageNum_costos * $maxRows_costos;

mysql_select_db($database_conexion1, $conexion1);
$query_costos = "SELECT 
rt.id_rpt_rt as id_rpt_rt,
tp.id_rpt_rtp as id_rpt_rtp, 
rp.str_maquina_rp as str_maquina_rp,
rt.op_rt  as op_rt, 
sum(rt.valor_tiem_rt)  as valor_tiem_rt,
sum(tp.valor_prep_rtp) as valor_prep_rtp,
rt.fecha_rt as fecha_rt
FROM tbl_reg_produccion rp 
left join Tbl_reg_tiempo rt on rt.op_rt  = rp.id_op_rp
left join tbl_reg_tiempo_preparacion tp on tp.op_rtp  = rp.id_op_rp 
WHERE   DATE(rt.fecha_rt) BETWEEN '$fecha1' AND '$fecha2' AND rp.str_maquina_rp ='$maquina' AND rt.id_proceso_rt = '$proceso' ";
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
$query_orden_produccion = "SELECT * FROM maquina  ORDER BY nombre_maquina ASC";
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);

mysql_select_db($database_conexion1, $conexion1);
$query_proceso = "SELECT * FROM tipo_procesos ";
$proceso = mysql_query($query_proceso, $conexion1) or die(mysql_error());
$row_proceso = mysql_fetch_assoc($proceso);
$totalRows_proceso = mysql_num_rows($proceso);


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
<form action="extruder_tiempos_y_preparacion2.php" method="get" name="form1">
<table id="tabla1">
<tr>
<td colspan="3" owrap="nowrap" id="codigo" >CODIGO : R1 - F03</td>
<td colspan="3" nowrap="nowrap" id="titulo2" >TIEMPOS Y PREPARACION</td>
<td colspan="2" nowrap="nowrap" id="codigo" >VERSION : 2</td>
</tr>
<tr><td colspan="8" style="color: red;" nowrap="nowrap" id="titulo2" >*Debe seleccionar minimo fechas y proceso</td></tr>
<tr>
  <td colspan="8" owrap="nowrap" id="codigo3">&nbsp;</td> 
</tr>
<tr>
  <td colspan="8" id="fuente2">FECHA INICIO:
    <input name="fecha_ini" type="datetime-local" required="required" id="fecha_ini" min="2000-01-02" size="10" value="<?php echo date('Y-m-01');?>"/>
FECHA FIN:
<input name="fecha_fin" type="datetime-local" id="fecha_fin" min="2000-01-02" size="10" required="required" value="<?php echo fecha();?>"/> 
MAQUINA:
<select name="maquina" id="maquina">
  <option value="0">MAQUINA</option>
  <?php
do {  
?>
  <option value="<?php echo $row_orden_produccion['id_maquina']?>"><?php echo $row_orden_produccion['nombre_maquina']?></option>
  <?php
} while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion));
  $rows = mysql_num_rows($orden_produccion);
  if($rows > 0) {
      mysql_data_seek($orden_produccions, 0);
	  $row_orden_produccion = mysql_fetch_assoc($orden_produccion);
  }
?>
</select>
PROCESO:
<select name="proceso" id="proceso" required="required" >
 <option value="">PROCESO</option>
  <?php
do {  
?>
  <option value="<?php echo $row_proceso['id_tipo_proceso']?>"><?php echo $row_proceso['nombre_proceso']?></option>
  <?php
} while ($row_proceso = mysql_fetch_assoc($proceso));
  $rows = mysql_num_rows($proceso);
  if($rows > 0) {
      mysql_data_seek($proceso, 0);
    $row_proceso = mysql_fetch_assoc($proceso);
  }
?>
</select>
TIPO TIEMPO:
<select name="tipo" id="tipo">  
   <option value="1">TIEMPOS MUERTOS</option>
   <option value="2">TIEMPOS PREPARACION</option>
</select>
<input type="submit" name="submit" value="FILTRO"/></td>
  </tr>
  <tr>
    <td colspan="4" id="dato1">&nbsp; </td> 
    <td colspan="3" id="dato3"><a href="produccion_registro_extrusion_listado.php"><img src="images/e.gif" alt="LISTADO EXTRUSION" title="LISTADO EXTRUSION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_impresion_listado.php"><img src="images/i.gif" alt="LISTADO IMPRESION" title="LISTADO IMPRESION" border="0" style="cursor:hand;" /></a><a href="produccion_registro_sellado_listado.php"><img src="images/s.gif" alt="LISTADO SELLADO" title="LISTADO SELLADO" border="0" style="cursor:hand;" /></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS" title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;" /></a><a href="costos_listado_ref_xproceso.php"><img src="images/rp.gif" alt="LISTADO REF KILOS X PROCESO" title="LISTADO REF KILOS X PROCESO" border="0" style="cursor:hand;" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>        
    <td colspan="2" id="dato3"><input type="button" value="Descarga Excel" onClick="window.location = 'extruder_tiempos_y_preparacion_excel.php?fecha_ini=<?php echo $_GET['fecha_ini'] ?>&fecha_fin=<?php echo $_GET['fecha_fin'] ?>&proceso=<?php echo $_GET['proceso']?>&maquina=<?php echo $_GET['maquina']?>'" /></td>
    </tr>
    </table>  
<table id="tabla1" >    
    <tr id="tr1">
      <td nowrap="nowrap" id="titulo4">ITEM TM</td> 
      <td nowrap="nowrap" id="titulo4">ITEM TP</td> 
      <td nowrap="nowrap" id="titulo4">ORDEN.P</td> 
     <td nowrap="nowrap" id="titulo4">MAQUINA</td>
     <td nowrap="nowrap" id="titulo4">VALOR TM</td>
     <td nowrap="nowrap" id="titulo4">VALOR TP</td>
    <td nowrap="nowrap" id="titulo4">FECHA</td> 

    </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
     <td id="dato3"><?php 
          $idtiem2=$row_costos['id_rpt_rt'];
        $sqlempt2="SELECT nombre_rtp FROM tbl_reg_tipo_desperdicio WHERE id_rtp='$idtiem2' ";
      $resultempt2=mysql_query($sqlempt2); 
      $numempt2=mysql_num_rows($resultempt2);
      if ($numempt2>='1') { 
      $items2=mysql_result($resultempt2,0,'nombre_rtp'); 
       echo $items2;  
      }
  ?></td>
    <td id="dato3"><?php echo $row_costos['valor_tiem_rt'];?></td>
    <td id="dato3"><?php 
          $idtiem=$row_costos['id_rpt_rtp'];
        $sqlempt="SELECT nombre_rtp FROM tbl_reg_tipo_desperdicio WHERE id_rtp='$idtiem' ";
      $resultempt=mysql_query($sqlempt); 
      $numempt=mysql_num_rows($resultempt);
      if ($numempt>='1') { 
      $items=mysql_result($resultempt,0,'nombre_rtp'); 
       echo $items;  
      }
  ?></td>
    <td id="dato3"><?php echo $row_costos['valor_prep_rtp'];?></td>
    <td id="dato3"><?php echo $row_costos['op_rt'];?></td>
    <td id="dato3"><?php 
           $idmaqu=$row_costos['str_maquina_rp'];
            $sqlempt2="SELECT nombre_maquina FROM maquina WHERE  id_maquina='$idmaqu'";
            $resultempt2=mysql_query($sqlempt2); 
            $numempt2=mysql_num_rows($resultempt2);
            if ($numempt2>='1') { 
            $items2=mysql_result($resultempt2,0,'nombre_maquina'); 
             echo $items2;  
            }
        ?></td>
    <td id="dato3"><?php echo $row_costos['fecha_rt'];?></td>
                       
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