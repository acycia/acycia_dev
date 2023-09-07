<?php require_once('Connections/conexion1.php'); ?>
<?php
header('Pragma: public'); 
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past    
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1 
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1 
header('Pragma: no-cache'); 
header('Expires: 0'); 
header('Content-Transfer-Encoding: none'); 
header('Content-Type: application/vnd.ms-excel'); // This should work for IE & Opera 
header('Content-type: application/x-msexcel'); // This should work for the rest 
header('Content-Disposition: attachment; filename="Kilos Referencia por proceso.xls"');
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
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

$tipoListado = $_GET['tipoListado'];//variable de control del case
    //IMPRIME FECHAS SELECCIONADAS
	$fecha1 = $_GET['fecha_ini'];
    $fecha2 = $_GET['fecha_fin'];
	//IMPRIME TODO UN AÑO
	$nuevafecha =date('Y-m-01');	
	$fecha1Toda=$nuevafecha;
	$fecha2Toda= date("Y-m-d");
  switch ($tipoListado) {
    case "1": //LISTADO COMPLETO
	mysql_select_db($database_conexion1, $conexion1);
	//Filtra fecha lleno Y PROCESO TODO EL MES
	if($fecha1Toda != '' && $fecha2Toda != '')
	{	
	$query_costos = "SELECT *
FROM Tbl_orden_produccion,Tbl_reg_produccion 
WHERE Tbl_orden_produccion.b_estado_op > 0 AND Tbl_orden_produccion.id_op=Tbl_reg_produccion.id_op_rp AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1Toda'
AND  '$fecha2Toda' and
DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1Toda'
AND  '$fecha2Toda'
GROUP BY Tbl_reg_produccion.int_cod_ref_rp DESC";
	}
	$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
	$row_costos = mysql_fetch_assoc($costos);
	$totalRows_costos = mysql_num_rows($costos);
      break;    
    case "2": //LISTADO POR FECHA
	mysql_select_db($database_conexion1, $conexion1);
	//Filtra fecha lleno Y PROCESO LAS FECHAS SELECCIONADAS
	if($fecha1 != '' && $fecha2 != '')
	{
	$query_costos = "SELECT *
FROM Tbl_orden_produccion,Tbl_reg_produccion 
WHERE Tbl_orden_produccion.b_estado_op > 0 AND Tbl_orden_produccion.id_op=Tbl_reg_produccion.id_op_rp AND DATE(Tbl_reg_produccion.fecha_ini_rp) BETWEEN '$fecha1'
AND  '$fecha2' and
DATE(Tbl_reg_produccion.fecha_fin_rp) BETWEEN '$fecha1'
AND  '$fecha2'
GROUP BY Tbl_reg_produccion.int_cod_ref_rp DESC";	
	}
	$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
	$row_costos = mysql_fetch_assoc($costos);
	$totalRows_costos = mysql_num_rows($costos);
      break;		  
  }
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table id="Exportar_a_Excel" border="1">   
  <tr>
    <td colspan="14" nowrap="nowrap" align="center">CONSUMO DE M.P UTILIZADOS POR O.P FECHA DESDE: <?php echo $_GET['fecha_ini'] ?> HASTA: <?php echo $_GET['fecha_fin'] ?></td>
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
      <td id="dato2"><?php
	  $proceso= $row_costos['id_proceso_rp'];
	  $ref=$row_costos['id_op_rp'];
	  switch ($proceso) {
	  case 1: $BD="SELECT COUNT(rollo_r) AS rollo FROM TblExtruderRollo WHERE id_op_r='$ref'";
	  $procesoEx=1;$procesoIm=2;$procesoRe=3;$procesoSe=4;break;
	  case 2: $BD="SELECT COUNT(rollo_r) AS rollo FROM TblImpresionRollo WHERE id_op_r='$ref'";
	  $procesoEx=1;$procesoIm=2;$procesoRe=3;$procesoSe=4;break;
	  case 3: $BD="SELECT COUNT(rollo_r) AS rollo FROM TblRefiladoRollo WHERE id_op_r='$ref'";
	  $procesoEx=1;$procesoIm=2;$procesoRe=3;$procesoSe=4;break;
	  case 4: $BD="SELECT (rollo_r) AS rollo FROM TblSelladoRollo WHERE id_op_r='$ref' GROUP BY `rollo_r`";
	  $procesoEx=1;$procesoIm=2;$procesoRe=3;$procesoSe=4;break;//se agrupa porque se guarda por rollo como por turno
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
	  $procesoEx=1;
	  $ref=$row_costos['id_op_rp'];
	  $sqlex="SELECT SUM(valor_prod_rp) AS kge FROM Tbl_reg_kilo_producido WHERE op_rp='$ref' AND id_proceso_rkp='$procesoEx'"; 
	  $resultex=mysql_query($sqlex); 
	  $numex=mysql_num_rows($resultex); 
	  if($numex >= '1') 
	  { $kilos_ex=mysql_result($resultex,0,'kge'); $materiaP=mysql_result($resultex,0,'id_rpp_rp');echo numeros_format($kilos_ex); }else {echo "0,00";}
	  ?></td>
      <td id="dato2">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlexd="SELECT SUM(valor_desp_rd) AS kgDespe FROM Tbl_reg_desperdicio WHERE op_rd='$ref' AND id_proceso_rd='$procesoEx'"; 
	  $resultexd=mysql_query($sqlexd); 
	  $numexd=mysql_num_rows($resultexd); 
	  if($numexd >= '1') 
	  { $kilos_exd=mysql_result($resultexd,0,'kgDespe'); echo numeros_format($kilos_exd); }else {echo "0,00";}
	  ?></td>            
      <td id="dato2">
	  <?php 
	  $procesoIm=2;
	  $ref=$row_costos['id_op_rp'];
	  $sqlim="SELECT SUM(int_kilos_prod_rp) AS kgi FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$procesoIm'"; 
	  $resultim=mysql_query($sqlim); 
	  $numim=mysql_num_rows($resultim); 
	  if($numim >= '1') 
	  { $kilos_im=mysql_result($resultim,0,'kgi'); echo numeros_format($kilos_im); }else {echo "0,00";}
	  ?></td> 
      <td id="dato2"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlimd="SELECT SUM(int_kilos_desp_rp) AS kgDespi FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$procesoIm'";
	  $resultimd=mysql_query($sqlimd); 
	  $numimd=mysql_num_rows($resultimd); 
	  if($numimd >= '1') 
	  { $kilos_imd=mysql_result($resultimd,0,'kgDespi'); echo numeros_format($kilos_imd); }else  {echo "0,00";}	
	  ?></td>
      <td id="dato2"><?php
	  $procesoRe=3; 
	  $ref=$row_costos['id_op_rp'];
	  $sqlre="SELECT SUM(int_kilos_prod_rp) AS kgi FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$procesoRe'"; 
	  $resultre=mysql_query($sqlre); 
	  $numim=mysql_num_rows($resultre); 
	  if($numre >= '1') 
	  { $kilos_re=mysql_result($resultre,0,'kgi'); echo numeros_format($kilos_re); }else {echo "0,00";}
	  ?></td>
      <td id="dato2"><?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlred="SELECT SUM(int_kilos_desp_rp) AS kgDespi FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$procesoRe'";
	  $resultred=mysql_query($sqlred); 
	  $numred=mysql_num_rows($resultred); 
	  if($numred >= '1') 
	  { $kilos_exd=mysql_result($resultred,0,'kgDespi'); echo numeros_format($kilos_red); }else  {echo "0,00";}	
	  ?></td>           
      <td id="dato2">
	  <?php 
	  $procesoSe=4;
	  $ref=$row_costos['id_op_rp'];
	  $sqlse="SELECT SUM(int_kilos_prod_rp) AS kgs FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$procesoSe'";  
	  $resultse=mysql_query($sqlse); 
	  $numse=mysql_num_rows($resultse); 
	  if($numse >= '1') 
	  { $kilos_se=mysql_result($resultse,0,'kgs'); echo numeros_format($kilos_se); }else {echo "0,00";}
	  ?></td>
      <td id="dato2">
      <?php 
	  $ref=$row_costos['id_op_rp'];
	  $sqlexd="SELECT SUM(int_kilos_desp_rp) AS kgDesps FROM Tbl_reg_produccion WHERE id_op_rp='$ref' AND id_proceso_rp='$procesoSe'";
	  $resultexd=mysql_query($sqlexd); 
	  $numexd=mysql_num_rows($resultexd); 
	  if($numexd >= '1') 
	  { $kilos_sed=mysql_result($resultexd,0,'kgDesps'); echo numeros_format($kilos_sed); }else {echo "0,00";}
	  ?>      </td>      
    </tr>
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos);

?>