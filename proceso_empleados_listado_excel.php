<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
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
header('Content-Disposition: attachment; filename="Empleados.xls"');
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

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 


$maxRows_proceso_empleado = 50;
$pageNum_proceso_empleado = 0;
if (isset($_GET['pageNum_proceso_empleado'])) {
  $pageNum_proceso_empleado = $_GET['pageNum_proceso_empleado'];
}
$startRow_proceso_empleado = $pageNum_proceso_empleado * $maxRows_proceso_empleado;


//VARIABLES PARA LAS NOVEDADES MENSUALES
$id_todo = $_GET['id_todo'];
$ano = $_GET['anual'];
$mensual = $_GET['mensual'];
$estado = $_GET['estado'];
$dia1 = '01';
$dia2 = '30';
$fechaInicio=$ano."-".$mensual."-".$dia1;
$fechaFin=$ano."-".$mensual."-".$dia2;
 
  switch ($id_todo) {
    case "1":
//Filtra Todo vacios
   $rows_empleado = $conexion->buscarListar("empleado a INNER JOIN TblProcesoEmpleado b on a.codigo_empleado=b.codigo_empleado","*","ORDER BY a.codigo_empleado DESC","",$maxRows_proceso_empleado,$pageNum_proceso_empleado,"" );
 break;
     case "2":
//Filtra Todo vacios
if($ano =='0' && $mensual=='0' && $estado!='2')
{
  $rows_empleado = $conexion->buscarListar("empleado a INNER JOIN TblProcesoEmpleado b on a.codigo_empleado=b.codigo_empleado","*","ORDER BY a.codigo_empleado DESC","",$maxRows_proceso_empleado,$pageNum_proceso_empleado," WHERE b.estado_empleado='$estado' " ); 
}
if($ano !='0' && $mensual!='0' && $estado!='2')
{
  $rows_empleado = $conexion->buscarListar("empleado a INNER JOIN TblProcesoEmpleado b on a.codigo_empleado=b.codigo_empleado","*","ORDER BY a.codigo_empleado DESC","",$maxRows_proceso_empleado,$pageNum_proceso_empleado," WHERE b.estado_empleado='$estado' AND b.fechainicial_empleado BETWEEN '$fechaInicio' and '$fechaFin' " ); 
}
if($ano !='0' && $mensual!='0' && $estado=='2')
{
  $rows_empleado = $conexion->buscarListar("empleado a INNER JOIN TblProcesoEmpleado b on a.codigo_empleado=b.codigo_empleado","*","ORDER BY a.codigo_empleado DESC","",$maxRows_proceso_empleado,$pageNum_proceso_empleado," WHERE b.fechainicial_empleado BETWEEN '$fechaInicio' and '$fechaFin' " );
}
if($ano =='0' && $mensual=='0' && $estado=='2')
{
 $rows_empleado = $conexion->buscarListar("empleado a INNER JOIN TblProcesoEmpleado b on a.codigo_empleado=b.codigo_empleado","*","ORDER BY a.codigo_empleado DESC","",$maxRows_proceso_empleado,$pageNum_proceso_empleado," WHERE b.estado_empleado='$estado'" ); 
}
 
 break;
  }
 

mysql_select_db($database_conexion1, $conexion1);
$query_mensual = "SELECT * FROM mensual ORDER BY id_mensual DESC";
$mensual = mysql_query($query_mensual, $conexion1) or die(mysql_error());
$row_mensual = mysql_fetch_assoc($mensual);
$totalRows_mensual = mysql_num_rows($mensual);

mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

if (isset($_GET['totalRows_proceso_empleado'])) {
  $totalRows_proceso_empleado = $_GET['totalRows_proceso_empleado'];
} else {
  $totalRows_proceso_empleado = $conexion->conteo('empleado'); 
} 
$totalPages_proceso_empleado = ceil($totalRows_proceso_empleado/$maxRows_proceso_empleado)-1;
 
$queryString_proceso_empleado = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_proceso_empleado") == false && 
        stristr($param, "totalRows_proceso_empleado") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_proceso_empleado = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_proceso_empleado = sprintf("&totalRows_proceso_empleado=%d%s", $totalRows_proceso_empleado, $queryString_proceso_empleado);

 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<table id="Exportar_a_Excel" border="1">   
    <tr id="tr1">
                      <td id="titulo4">CODIGO</td>
                      <td id="titulo4">NOMBRE APELLIDO</td>
                      <td id="titulo4">CARGO</td>
                      <td id="titulo4">SUELDO</td>
                      <td id="titulo4">RECARGOS</td>
                      <td id="titulo4">APORTES</td>
                      <td id="titulo4">COSTO MES</td>
                      <td id="titulo4">VALOR HORA</td>
                      <td id="titulo4"> DIAS LABORADOS</td>
                      <td id="titulo4">FECHA INICIAL</td>
                      <td id="titulo4">FECHA RETIRO</td>
                      <td id="titulo4">EMPRESA</td>
                      <td id="titulo4">ESTADO</td>
                    </tr>
                    <?php foreach($rows_empleado as $rows_empleado) {  ?>
                    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                      <td id="dato1"><?php echo $rows_empleado['codigo_empleado'];?></td>
                      <td nowrap id="dato1">
                        <?php $codigo_empleado=$rows_empleado['codigo_empleado']; 	
	$sqlemp="SELECT nombre_empleado, apellido_empleado FROM empleado WHERE codigo_empleado='$codigo_empleado'";
	$resultemp=mysql_query($sqlemp); $numemp=mysql_num_rows($resultemp);
	if ($numemp>='1') { 
	$nombre_empleado=mysql_result($resultemp,0,'nombre_empleado');$apellido_empleado=mysql_result($resultemp,0,'apellido_empleado');  
	echo $nombre_empleado." ".$apellido_empleado; }?>
                        </td>
                      <td nowrap id="dato1">
                        <?php 
	        $cargo_empleado=$rows_empleado['codigo_empleado'];
	    	$sqlempt="SELECT empleado.empresa_empleado,empleado.tipo_empleado,empleado_tipo.nombre_tipo_empleado FROM empleado,empleado_tipo WHERE empleado.codigo_empleado=$cargo_empleado AND empleado_tipo.id_empleado_tipo=empleado.tipo_empleado";
			$resultempt=mysql_query($sqlempt); 
			$numempt=mysql_num_rows($resultempt);
			if ($numempt>='1') { 
			$empresa_empleado=mysql_result($resultempt,0,'empleado.empresa_empleado');
			$cargo_empleado=mysql_result($resultempt,0,'empleado_tipo.nombre_tipo_empleado'); echo $cargo_empleado;  
			}
	?>
                        </td>
                      <td id="dato3"><?php echo numeros_format($rows_empleado['sueldo_empleado']);?></td>
                      <td id="dato3">
                        <?php $novedades=$rows_empleado['codigo_empleado']; 	
	$sqlrecargos="SELECT SUM(pago_acycia) AS valoracycia, SUM(pago_eps) AS valoreps, SUM(dias_incapacidad) as dias, SUM(horas_extras) as horas,SUM(recargos) as recargos,SUM(festivos) as festivos
FROM TblNovedades
WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin' AND codigo_empleado=$novedades";
	$resultrecargos=mysql_query($sqlrecargos); 
	$numrecargos=mysql_num_rows($resultrecargos);
	if ($numrecargos >='1') {
    $valoracycia=mysql_result($resultrecargos,0,'valoracycia'); 
	$valoreps=mysql_result($resultrecargos,0,'valoreps');
	$dias_incapacidad=mysql_result($resultrecargos,0,'dias'); 
	$horas=mysql_result($resultrecargos,0,'horas');
	$recargos=mysql_result($resultrecargos,0,'recargos'); 
	$festivos=mysql_result($resultrecargos,0,'festivos');
	$pagoIncapacidad=$valoracycia;
	$total_recargos = $horas+$recargos+$festivos; echo $total_recargos;
	 }?>
                        </td>
                      <td id="dato3"><?php 
	$sueld=$rows_empleado['sueldo_empleado'];	
	$aux_trans=$rows_empleado['aux_empleado'];	
	?>
                        <?php 	
	$codigo_aporte=$rows_empleado['codigo_empleado'];
	$sqlaport="SELECT total FROM TblAportes WHERE codigo_empl=$codigo_aporte";
	$resultaport=mysql_query($sqlaport); 
	$numaport=mysql_num_rows($resultaport);
	if ($numaport>='1') { 
	$aport=mysql_result($resultaport,0,'total');  
	echo $aport; }
	?></td>
                      <td id="dato3"><?php 
	//sueldo mes
	//variables de control
	$sueld=$rows_empleado['sueldo_empleado'];	
	$aux_trans=$rows_empleado['aux_empleado'];					  
	$equivalenteadia=$rows_empleado['diasmes_reales'];	//esto es 280 dias  / 12 
	$diasreportados=$rows_empleado['dias_empleado'];
	$constantemes=30;
	//operaciones
	$costoMesNeto = sueldoMes($sueld,$aux_trans,$equivalenteadia,$diasreportados,$constantemes,$total_recargos,$aport,$pagoIncapacidad);
    echo numeros_format($costoMesNeto);
						  
/*	$valorMesBruto = sumar($total_recargos,$sueld,$aport,$aux_trans);//suma de todo la nomina al empleado
	//NOVEDADES
	$valor_por_dia=$valorMesBruto/$rows_empleado['dias_empleado'];//valor equivalente a un dia
	$totaldiaslaborados=$rows_empleado['dias_empleado']-$dias_incapacidad;//dias que trabajo realmente
	$costoMesNeto = ($totaldiaslaborados*$valor_por_dia)+$pagoIncapacidad;//lo que realmense se paga en la nomina
	//FIN NOVEDADES
	 echo numeros_format($costoMesNeto);	
	$horas_trabajadas_mes=(($totaldiaslaborados)*$rows_empleado['horas_empleado']);	//horas reales trabajadas*/
	?></td>
                      <td id="dato3">
                        <?php 
	$costo_hora = $costoMesNeto/$rows_empleado['horasmes_reales']; //para saber costo por hora
	echo redondear_entero_puntos($costo_hora) ?>
                        </td>
                      <td id="dato2"><?php echo $rows_empleado['dias_empleado']-$dias_incapacidad;?></td>
                      <td nowrap id="dato2"><?php echo $rows_empleado['fechainicial_empleado']; ?></td>
                      <td nowrap id="dato2"><?php echo $rows_empleado['fechafinal_empleado']; ?></td>
                      <td id="dato2"><?php echo $empresa_empleado; ?></td>
                      <td id="dato2">
                        <?php if($rows_empleado['estado_empleado']==0){echo "Inactivo";}else{echo "Activo";}?>
                        </td>
                  
  </tr>
   <?php } ?>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($proceso_empleado);

mysql_free_result($mensual);

mysql_free_result($ano);

?>