<?php require_once('Connections/conexion1.php'); ?>
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
//header('Last-Modified: ' . gm('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revali');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel');
header('Content-type: application/x-msexcel');
header('Content-Disposition: attachment; filename="TIEMPOS Y PREPARACION proceso.xls"'); 
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

 
    //IMPRIME FECHAS SELECCIONADAS
$fecha1 = str_replace("T", " ", $_GET['fecha_ini']);
$fecha2 = str_replace("T", " ", $_GET['fecha_fin']);
$proceso= $_GET['proceso'];
$maquina= $_GET['maquina'];
$tipo = $_GET['tipo']; 
//==================TIEMPOS MUERTOS=======================
//Filtra todos vacios
if($fecha1 == '0' && $fecha2 == '0' && $maquina == '0' && $proceso == ''  && $tipo == '0' )
{
$query_costos = "SELECT
rt.id_rpt_rt as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
rt.valor_tiem_rt  as valor_tiem_rt, 
rt.fecha_rt as fecha_rt 
FROM tbl_reg_produccion rp 
left join  Tbl_reg_tiempo rt on rt.op_rt  = rp.id_op_rp 
WHERE   (rt.fecha_rt) BETWEEN '1' AND '1' AND rp.str_maquina_rp ='1' AND rt.id_proceso_rt = '1' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc"; 
}
//Filtra fecha 
if($fecha1 != '0' && $fecha2 != '0' && $maquina == '0' && $proceso == '' && $tipo == '1')
{
$query_costos = "SELECT
rt.id_rpt_rt as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
rt.valor_tiem_rt  as valor_tiem_rt, 
rt.fecha_rt as fecha_rt 
FROM tbl_reg_produccion rp 
left join  Tbl_reg_tiempo rt on rt.op_rt  = rp.id_op_rp 
WHERE   (rt.fecha_rt) BETWEEN '$fecha1' AND '$fecha2' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc";  
}
//Filtra fechas maquina
if($fecha1 != '0' && $fecha2 != '0' && $maquina != '0' && $proceso == '' && $tipo == '1')
{
$query_costos = "SELECT
rt.id_rpt_rt as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
rt.valor_tiem_rt  as valor_tiem_rt, 
rt.fecha_rt as fecha_rt 
FROM tbl_reg_produccion rp 
left join  Tbl_reg_tiempo rt on rt.op_rt  = rp.id_op_rp 
WHERE   (rt.fecha_rt) BETWEEN '$fecha1' AND '$fecha2' AND rp.str_maquina_rp ='$maquina' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc"; 
}
//Filtra fecha proceso
if($fecha1 != '0' && $fecha2 != '0' && $maquina == '0' && $proceso != '0'  && $tipo == '1')
{
$query_costos = "SELECT
rt.id_rpt_rt as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
rt.valor_tiem_rt  as valor_tiem_rt, 
rt.fecha_rt as fecha_rt 
FROM tbl_reg_produccion rp 
left join  Tbl_reg_tiempo rt on rt.op_rt  = rp.id_op_rp 
WHERE   (rt.fecha_rt) BETWEEN '$fecha1' AND '$fecha2' AND rt.id_proceso_rt = '$proceso' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc"; 
}
//Filtra maquina y proceso
if($fecha1 != '0' && $fecha2 != '0' && $maquina != '0' && $proceso != '0'  && $tipo == '1')
{
$query_costos = "SELECT
rt.id_rpt_rt as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
rt.valor_tiem_rt  as valor_tiem_rt, 
rt.fecha_rt as fecha_rt 
FROM tbl_reg_produccion rp 
left join  Tbl_reg_tiempo rt on rt.op_rt  = rp.id_op_rp  
WHERE   (rt.fecha_rt) BETWEEN '$fecha1' AND '$fecha2' AND rp.str_maquina_rp ='$maquina' AND rt.id_proceso_rt = '$proceso' AND rp.id_proceso_rp = '$proceso' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc"; 
}

//==================TIEMPOS PREPARACION=======================
//Filtra fecha 
if($fecha1 != '0' && $fecha2 != '0' && $maquina == '0' && $proceso == '' && $tipo == '2')
{
$query_costos = "SELECT
rt.id_rpt_rt as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
rt.valor_tiem_rt  as valor_tiem_rt, 
rt.fecha_rt as fecha_rt 
FROM tbl_reg_produccion rp 
left join tbl_reg_tiempo_preparacion tp on tp.op_rtp  = rp.id_op_rp  
WHERE   (rt.fecha_rt) BETWEEN '$fecha1' AND '$fecha2' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc";  
}
//Filtra fechas maquina
if($fecha1 != '0' && $fecha2 != '0' && $maquina != '0' && $proceso == '' && $tipo == '2')
{
$query_costos = "SELECT
rt.id_rpt_rt as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
rt.valor_tiem_rt  as valor_tiem_rt, 
rt.fecha_rt as fecha_rt 
FROM tbl_reg_produccion rp 
left join tbl_reg_tiempo_preparacion tp on tp.op_rtp  = rp.id_op_rp  
WHERE   (rt.fecha_rt) BETWEEN '$fecha1' AND '$fecha2' AND rp.str_maquina_rp ='$maquina' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc"; 
}
//Filtra fecha proceso
if($fecha1 != '0' && $fecha2 != '0' && $maquina == '0' && $proceso != '0'  && $tipo == '2')
{
$query_costos = "SELECT
rt.id_rpt_rt as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
rt.valor_tiem_rt  as valor_tiem_rt, 
rt.fecha_rt as fecha_rt 
FROM tbl_reg_produccion rp 
left join tbl_reg_tiempo_preparacion tp on tp.op_rtp  = rp.id_op_rp  
WHERE   (rt.fecha_rt) BETWEEN '$fecha1' AND '$fecha2' AND rt.id_proceso_rt = '$proceso' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc"; 
}
//Filtra maquina y proceso
if($fecha1 != '0' && $fecha2 != '0' && $maquina != '0' && $proceso != '0'  && $tipo == '2')
{
$query_costos = "SELECT
tp.id_rpt_rtp as id_rpt_rt,
rp.int_cod_ref_rp as int_cod_ref_rp,
rp.str_maquina_rp as str_maquina_rp, 
rp.id_op_rp as op_rt,
tp.valor_prep_rtp  as valor_tiem_rt, 
tp.fecha_rtp as fecha_rt
FROM tbl_reg_produccion rp 
left join tbl_reg_tiempo_preparacion tp on tp.op_rtp  = rp.id_op_rp  
WHERE   (tp.fecha_rtp) BETWEEN '$fecha1' AND '$fecha2' AND rp.str_maquina_rp ='$maquina' AND tp.id_proceso_rtp = '$proceso' AND rp.id_proceso_rp  = '$proceso' group by op_rt,id_rpt_rt order by int_cod_ref_rp desc"; 
}

	$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
	$row_costos = mysql_fetch_assoc($costos);
	$totalRows_costos = mysql_num_rows($costos);	
 
 mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = "SELECT * FROM maquina  ORDER BY id_maquina ASC";
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);

mysql_select_db($database_conexion1, $conexion1);
$query_proceso = "SELECT * FROM tipo_procesos ";
$proceso = mysql_query($query_proceso, $conexion1) or die(mysql_error());
$row_proceso = mysql_fetch_assoc($proceso);
$totalRows_proceso = mysql_num_rows($proceso);

 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table id="Exportar_a_Excel" border="1">   
  <tr>
    <td colspan="11" nowrap="nowrap" id="dato2">TIEMPOS MUERTOS Y PREPARACION </td>
  </tr>
<tr id="tr1">
    <td nowrap="nowrap" id="titulo4">ITEM </td> 
   <td nowrap="nowrap" id="titulo4">VALOR </td> 
    <td nowrap="nowrap" id="titulo4">ORDEN.P</td>
    <td nowrap="nowrap" id="titulo4">REF</td> 
   <td nowrap="nowrap" id="titulo4">MAQUINA</td>
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
      <td id="dato3"><?php 
        $id_rpt_rt = $row_costos['id_rpt_rt'];
        $op_rt  = $row_costos['op_rt'];
        $proceso= $_GET['proceso'];
        if($_GET['tipo']=='1'){
        $sqlvalor="SELECT SUM(valor_tiem_rt) AS tiempom FROM Tbl_reg_tiempo WHERE id_rpt_rt='$id_rpt_rt' and op_rt='$op_rt' AND id_proceso_rt='$proceso' GROUP BY id_rpt_rt ASC";
        }else{
        $sqlvalor="SELECT SUM(valor_prep_rtp) AS tiempom FROM Tbl_reg_tiempo_preparacion WHERE id_rpt_rtp='$id_rpt_rt' and  op_rtp='$op_rt' AND id_proceso_rtp='$proceso' GROUP BY id_rpt_rtp ASC";
        }
        $resultvalor=mysql_query($sqlvalor); 
        $numvalor=mysql_num_rows($resultvalor);
        if ($numvalor>='1') { 
        $valor=mysql_result($resultvalor,0,'tiempom'); 
         echo $valor;  
        }
    ?></td>
      <td id="dato3"><?php echo $row_costos['op_rt'];?></td>
      <td id="dato3"><?php echo $row_costos['int_cod_ref_rp'];?></td>
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
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>