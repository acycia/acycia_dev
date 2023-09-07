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
header('Content-Disposition: attachment; filename="MateriaPrima.xls"');
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
//VARIABLES DE CONTROL
	$fecha1 = $_GET['fecha_ini'];
	$fecha2 = $_GET['fecha_fin'];
	$id_op = $_GET['id_op'];
    $tipoListado = $_GET['tipoListado'];//variable de control del case
	
    switch ($tipoListado) {
    case "1":
	//IMPRIME TODO EL DETALLE
	mysql_select_db($database_conexion1, $conexion1);
	//Filtra Todo lleno
	if($fecha1 != '' && $fecha2 != '' &&  $id_op!='' )
	{
	$query_costos = "SELECT insumo.id_insumo,insumo.codigo_insumo,insumo.descripcion_insumo,insumo.stok_insumo FROM insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo=Tbl_reg_kilo_producido.id_rpp_rp AND DATE(Tbl_reg_kilo_producido.fecha_rkp) BETWEEN '$fecha1' AND '$fecha2' AND Tbl_reg_kilo_producido.op_rp=$id_op AND DATE(Tbl_reg_kilo_producido.fecha_rkp) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_reg_kilo_producido.id_rpp_rp DESC";	
	}
	//Filtra Fechas lleno
	if($fecha1 != '' && $fecha2 != '' &&  $id_op=='' )
	{
	$query_costos = "SELECT insumo.id_insumo,insumo.codigo_insumo,insumo.descripcion_insumo,insumo.stok_insumo FROM insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo=Tbl_reg_kilo_producido.id_rpp_rp AND DATE(Tbl_reg_kilo_producido.fecha_rkp) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_reg_kilo_producido.id_rpp_rp DESC";	
	}
	$costos = mysql_query($query_costos, $conexion1) or die(mysql_error());
	$row_costos = mysql_fetch_assoc($costos);
	$totalRows_costos = mysql_num_rows($costos);
      break;    
    case "2":
    //IMPRIME TOTALES POR MATERIA PRIMA
//Filtra Todo lleno
if($fecha1 != '' && $fecha2 != '' &&  $id_op!='' )
{
$query_costos = "SELECT insumo.id_insumo,insumo.codigo_insumo,insumo.descripcion_insumo,insumo.stok_insumo FROM insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo=Tbl_reg_kilo_producido.id_rpp_rp AND DATE(Tbl_reg_kilo_producido.fecha_rkp) BETWEEN '$fecha1' AND '$fecha2' AND Tbl_reg_kilo_producido.op_rp=$id_op AND DATE(Tbl_reg_kilo_producido.fecha_rkp) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_reg_kilo_producido.id_rpp_rp DESC";	
}
//Filtra Fechas lleno
if($fecha1 != '' && $fecha2 != '' &&  $id_op=='' )
{
$query_costos = "SELECT insumo.id_insumo,insumo.codigo_insumo,insumo.descripcion_insumo,insumo.stok_insumo FROM insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo=Tbl_reg_kilo_producido.id_rpp_rp AND DATE(Tbl_reg_kilo_producido.fecha_rkp) BETWEEN '$fecha1' AND '$fecha2' GROUP BY Tbl_reg_kilo_producido.id_rpp_rp DESC";	
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
    <td colspan="6" nowrap="nowrap" id="dato2">CONSUMO DE MATERIAS PRIMAS POR PROCESO ENTRE FECHA INICIAL:<?php echo $_GET['fecha_ini'] ?> Y FECHA FINAL: <?php echo $_GET['fecha_fin'] ?></td>
  </tr>
  <tr id="tr1">
    <td rowspan="2" nowrap="nowrap" id="titulo4">MATERIA PRIMA</td> 
     
    <td colspan="2" nowrap="nowrap" id="titulo4">ENTRADA</td>
    <td colspan="2" nowrap="nowrap" id="titulo4">SALIDA</td>
    <td colspan="2" nowrap="nowrap" id="titulo4">INVENTARIO FINAL</td>
   </tr>
   <tr id="tr1">
    
    <td nowrap="nowrap" id="titulo4">CANTIDAD</td>
    <td nowrap="nowrap" id="titulo4">VALOR TOTAL</td>
    <td nowrap="nowrap" id="titulo4">CANTIDAD</td>
    <td nowrap="nowrap" id="titulo4">SALIDA TOTAL</td>
    <td nowrap="nowrap" id="titulo4">CANTIDAD</td>
    <td nowrap="nowrap" id="titulo4">VALOR TOTAL</td>
  </tr>
  <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
    <td id="dato1" nowrap="nowrap"><?php 
 	   echo $nombre=$row_costos['descripcion_insumo'];
 	  ?></td>
  
      <td id="dato1"><?php 
	  //referencias producto terminado
/*	  $cod_ref=$row_costos['op_rp'];
	  $sqlinv="SELECT SUM(bolsas_r) AS entrada FROM TblSelladoRollo WHERE id_op_r=$cod_ref= AND reproceso_r = '0' AND DATE(fechaI_r) BETWEEN '$fecha1' AND '$fecha2'";
	  $resultinv=mysql_query($sqlinv); 	
	  $numinv=mysql_num_rows($resultinv); 
	  if($numinv >= '1') 
	  { $entrada=mysql_result($resultinv,0,'entrada');}*/
      //maeria prima
	  $id_rpp=$row_costos['id_insumo'];
	  $sqlinv="SELECT SUM(ingreso_ing) AS cantidad, valor_und_ing AS valor FROM TblIngresos WHERE id_insumo_ing='$id_rpp' AND fecha_ing BETWEEN '$fecha1' AND '$fecha2' ORDER BY fecha_ing DESC"; 
	  $resultinv=mysql_query($sqlinv); 
	  $numinv=mysql_num_rows($resultinv); 
	  if($numinv >= '1') 
	  { $entrada=mysql_result($resultinv,0,'cantidad');
	    $valor_und=mysql_result($resultinv,0,'valor');
	  echo  numeros_format($entrada);
	  }
	  ?></td>     
      <td id="dato1"><?php
	   $costoEntrada=$valor_und;
	   $TotalcostoEntrada=$costoEntrada*$entrada;
	   echo numeros_format($TotalcostoEntrada);
 	  ?></td>
      <td id="dato1"><?php
	  $sqlsal="SELECT SUM(valor_prod_rp) AS salida FROM Tbl_reg_kilo_producido WHERE id_rpp_rp = '$id_rpp' AND DATE(fecha_rkp) BETWEEN '$fecha1' AND '$fecha2' GROUP BY id_rpp_rp DESC";
	  $resultsal=mysql_query($sqlsal); 	
	  $numsal=mysql_num_rows($resultsal); 
	  if($numsal >= '1') 
	  { $salidas=mysql_result($resultsal,0,'salida');}
 	  echo numeros_format($salidas);
	  ?></td>
      <td id="dato1"><?php 
      $TotalcostoConsumo=$costoEntrada*$salidas;
	  echo numeros_format($TotalcostoConsumo);
	  ?></td>
      <td id="dato1"><?php
	  $stok = $row_costos['stok_insumo'];
	  $saldo_final=(($entrada)-$salidas);
	  if($saldo_final<$stok){
      echo "<span class='rojo_normal'>". numeros_format($saldo_final) ."</spam>";}else{echo numeros_format($saldo_final);}
	  ?></td>
      <td id="dato1"><?php 
 $TotalcostoSaldoF=$saldo_final*$costoEntrada; echo numeros_format($TotalcostoSaldoF);
	  ?></td>
      <td id="dato1">
      <?php 
 $TotalcostoSaldoF=$costoSaldoF*$costoEntrada; echo numeros_format($TotalcostoSaldoF);
	  ?>
      </td>      
    </tr>
    <?php } while ($row_costos = mysql_fetch_assoc($costos)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($numero);

mysql_free_result($insumo);

mysql_free_result($proceso);

mysql_free_result($costos);

?>