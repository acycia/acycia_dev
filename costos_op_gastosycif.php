<?php require_once('Connections/conexion1.php');
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
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
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

		$registro=$_POST['consulta']; 
		mysql_select_db($database_conexion1, $conexion1);
		$sqlv="SELECT fecha FROM TblDistribucionHoras WHERE fecha = '$registro'";
		$resultcn=mysql_query($sqlv);
        $numcn=mysql_num_rows($resultcn);
		if($numcn >='1')
		{	
   $updateSQL = sprintf("UPDATE TblDistribucionHoras SET extrusion=%s, impresion=%s, refilado=%s, sellado=%s, total=%s, fecha=%s, gga_ext=%s, gga_imp=%s, gga_ref=%s, gga_sell=%s, ggf_ext=%s, ggf_imp=%s, ggf_ref=%s, ggf_sell=%s, ggv_ext=%s, ggv_imp=%s, ggv_ref=%s, ggv_sell=%s, cif_ext=%s, cif_imp=%s, cif_ref=%s, cif_sell=%s WHERE fecha =%s", 
					   GetSQLValueString($_POST['extrusion'], "double"),
                       GetSQLValueString($_POST['impresion'], "double"),
					   GetSQLValueString($_POST['refilado'], "double"),
                       GetSQLValueString($_POST['sellado'], "double"),
                       GetSQLValueString($_POST['total'], "double"),
					   GetSQLValueString($_POST['fecha'], "text"),
					   GetSQLValueString($_POST['gga_ext'], "double"),
					   GetSQLValueString($_POST['gga_imp'], "double"),
					   GetSQLValueString($_POST['gga_ref'], "double"),
					   GetSQLValueString($_POST['gga_sell'], "double"),
					   GetSQLValueString($_POST['ggf_ext'], "double"),
					   GetSQLValueString($_POST['ggf_imp'], "double"),
					   GetSQLValueString($_POST['ggf_ref'], "double"),
					   GetSQLValueString($_POST['ggf_sell'], "double"),
					   GetSQLValueString($_POST['ggv_ext'], "double"),
					   GetSQLValueString($_POST['ggv_imp'], "double"),
					   GetSQLValueString($_POST['ggv_ref'], "double"),
					   GetSQLValueString($_POST['ggv_sell'], "double"),
					   GetSQLValueString($_POST['cif_ext'], "double"),
					   GetSQLValueString($_POST['cif_imp'], "double"),
					   GetSQLValueString($_POST['cif_ref'], "double"),
					   GetSQLValueString($_POST['cif_sell'], "double"),
					   GetSQLValueString($_POST['fecha'], "text"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL, $conexion1) or die(mysql_error());  
  $id=1;      
  $insertGoTo = "costos_op_gastosycif.php?id=" . $id. "";
  header(sprintf("Location: %s", $insertGoTo));

  		}else{
  $insertSQL = sprintf("INSERT INTO TblDistribucionHoras (id_dh, extrusion, impresion, refilado, sellado, total,  fecha, gga_ext, gga_imp, gga_ref, gga_sell, ggf_ext, ggf_imp, ggf_ref, ggf_sell, ggv_ext, ggv_imp, ggv_ref, ggv_sell, cif_ext, cif_imp, cif_ref, cif_sell) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_dh'], "int"),
					   GetSQLValueString($_POST['extrusion'], "double"),
                       GetSQLValueString($_POST['impresion'], "double"),
					   GetSQLValueString($_POST['refilado'], "double"),
                       GetSQLValueString($_POST['sellado'], "double"),
                       GetSQLValueString($_POST['total'], "double"),
					   GetSQLValueString($_POST['fecha'], "text"),
					   GetSQLValueString($_POST['gga_ext'], "double"),
					   GetSQLValueString($_POST['gga_imp'], "double"),
					   GetSQLValueString($_POST['gga_ref'], "double"),
					   GetSQLValueString($_POST['gga_sell'], "double"),
					   GetSQLValueString($_POST['ggf_ext'], "double"),
					   GetSQLValueString($_POST['ggf_imp'], "double"),
					   GetSQLValueString($_POST['ggf_ref'], "double"),
					   GetSQLValueString($_POST['ggf_sell'], "double"),
					   GetSQLValueString($_POST['ggv_ext'], "double"),
					   GetSQLValueString($_POST['ggv_imp'], "double"),
					   GetSQLValueString($_POST['ggv_ref'], "double"),
					   GetSQLValueString($_POST['ggv_sell'], "double"),
					   GetSQLValueString($_POST['cif_ext'], "double"),
					   GetSQLValueString($_POST['cif_imp'], "double"),
					   GetSQLValueString($_POST['cif_ref'], "double"),
					   GetSQLValueString($_POST['cif_sell'], "double"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result = mysql_query($insertSQL, $conexion1) or die(mysql_error());  
  $id = 0;      
  $insertGoTo = "costos_op_gastosycif.php?id=" . $id. "";
  header(sprintf("Location: %s", $insertGoTo));
  } 
}
?>
<?php


$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
 
 	  $fecha=$_GET['consulta'];
 	  if($fecha==''){
	  $fechanow=fecha(); 
	  $fechafin = last_month_day($fechanow);
$query_costos_gga = ("SELECT * FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGA' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv = '0' ORDER BY Tbl_generadores.categoria_generadores DESC");	  
	  }
	  else
	  {$fechafin = last_month_day2($fecha); 
mysql_select_db($database_conexion1, $conexion1);
$query_costos_gga = ("SELECT * FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGA' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND fecha_fin_gv BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' ORDER BY Tbl_generadores.categoria_generadores DESC");
	  }
$costos_gga = mysql_query($query_costos_gga, $conexion1) or die(mysql_error());
$row_costos_gga = mysql_fetch_assoc($costos_gga);
$totalRows_costos_gga = mysql_num_rows($costos_gga);

if($fecha==''){
$query_costos_ggf = ("SELECT * FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGF'  AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv = '0' ORDER BY Tbl_generadores.categoria_generadores DESC");
}else{
mysql_select_db($database_conexion1, $conexion1);
$query_costos_ggf = ("SELECT * FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGF' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND fecha_fin_gv BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' ORDER BY Tbl_generadores.categoria_generadores DESC");
}
$costos_ggf = mysql_query($query_costos_ggf, $conexion1) or die(mysql_error());
$row_costos_ggf = mysql_fetch_assoc($costos_ggf);
$totalRows_costos_ggf = mysql_num_rows($costos_ggf);

if($fecha==''){
$query_costos_ggv = ("SELECT * FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGV' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv = '0' ORDER BY Tbl_generadores.categoria_generadores DESC");	
}else{
mysql_select_db($database_conexion1, $conexion1);
$query_costos_ggv = ("SELECT * FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='GGV' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv  AND fecha_fin_gv BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' ORDER BY Tbl_generadores.categoria_generadores DESC");
}
$costos_ggv = mysql_query($query_costos_ggv, $conexion1) or die(mysql_error());
$row_costos_ggv = mysql_fetch_assoc($costos_ggv);
$totalRows_costos_ggv = mysql_num_rows($costos_ggv);

if($fecha==''){
$query_costos_cif = ("SELECT * FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='CIF' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv AND Tbl_generadores_valor.estado_gv = '0' ORDER BY Tbl_generadores.categoria_generadores DESC");	
}else{
mysql_select_db($database_conexion1, $conexion1);
$query_costos_cif = ("SELECT * FROM Tbl_generadores, Tbl_generadores_valor WHERE Tbl_generadores.categoria_generadores='CIF' AND Tbl_generadores.id_generadores=Tbl_generadores_valor.id_generadores_gv  AND fecha_fin_gv BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' ORDER BY Tbl_generadores.categoria_generadores DESC");
}
$costos_cif = mysql_query($query_costos_cif, $conexion1) or die(mysql_error());
$row_costos_cif = mysql_fetch_assoc($costos_cif);
$totalRows_costos_cif = mysql_num_rows($costos_cif);

if($fecha==''){
$query_ultimo = "SELECT * FROM TblDistribucionHoras ORDER BY fecha DESC";
}else{
mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM TblDistribucionHoras WHERE fecha BETWEEN DATE_FORMAT('$fechafin', '%Y-%m') AND DATE_FORMAT('$fechafin', '%Y-%m') ORDER BY fecha DESC";
}
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
<script>
function consultahoras(){
	var cons=document.form1.consulta.value;
	window.location ='costos_op_gastosycif.php?consulta='+cons;
	}
</script>
</head>
<body>
<div align="center">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" >
  <table id="tabla1">
  <tr>
    <td id="subppal3"><?php echo $row_usuario['nombre_usuario']; ?></td>
    <td colspan="15" id="principal">DISTRIBUCION DE GASTOS Y CIF</td>
    </tr>
  <tr>
    <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td colspan="10" id="dato3"><a href="javascript:location.reload()">
      <img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" /><a href="costos_generales.php"><img src="images/opciones.gif" style="cursor:hand;" alt="COSTOS GENERALES" title="COSTOS GENERALES" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR"title="SALIR" onclick="window.close() "/></a></td>
    </tr>
  <tr>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td id="fondo">&nbsp;</td>
    <td colspan="2" id="subppal2">Fecha</td>
    <td colspan="6" id="subppal2">Horas Proceso Mes:     </td>
    <td colspan="2" id="subppal2"><h4 style="color:#F00"><?php 
	 echo date("F", strtotime($fechafin));
/*  	  $sqlExt="SELECT TIMEDIFF(MAX(fecha_fin_rp),MIN(fecha_ini_rp)) AS TIEMPOMES FROM `Tbl_reg_produccion` WHERE `id_proceso_rp`='1' AND `fecha_ini_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' AND `fecha_fin_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'"; 
	  $resultExt=mysql_query($sqlExt); 
	  $numExt=mysql_num_rows($resultExt); 
	  if($numExt >= '1')
	  { 
	  $TiempomesExt=mysql_result($resultExt,0,'TIEMPOMES');
	  $TiempomesExtdecimal=horadecimalUna($TiempomesExt);
 	  }else{echo "0";}	
	  //IMPRESION
	  $sqlImp="SELECT TIMEDIFF(MAX(fecha_fin_rp),MIN(fecha_ini_rp)) AS TIEMPOMES FROM `Tbl_reg_produccion` WHERE `id_proceso_rp`= '2' AND `fecha_ini_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' AND `fecha_fin_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'"; 
	  $resultImp=mysql_query($sqlImp); 
	  $numImp=mysql_num_rows($resultImp); 
	  if($numImp >= '1')
	  { 
	  $TiempomesImp=mysql_result($resultImp,0,'TIEMPOMES');
	  $TiempomesImpdecimal=horadecimalUna($TiempomesImp);
 	  }else{echo "0";}	
	  //SELLADO
	  $sqlSell="SELECT TIMEDIFF(MAX(fecha_fin_rp),MIN(fecha_ini_rp)) AS TIEMPOMES FROM `Tbl_reg_produccion` WHERE `id_proceso_rp`='4' AND `fecha_ini_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin' AND `fecha_fin_rp` BETWEEN DATE_FORMAT('$fechafin', '%Y-%m-01') AND '$fechafin'"; 
	  $resultSell=mysql_query($sqlSell); 
	  $numSell=mysql_num_rows($resultSell); 
	  if($numSell >= '1')
	  { 
	  $TiempomesSell=mysql_result($resultSell,0,'TIEMPOMES');
	  $TiempomesSelldecimal=horadecimalUna($TiempomesSell);
 	  }else{echo "0";}	*/    
 	  ?></h4></td>
    </tr>
  <tr>
    <td id="fondo">&nbsp;</td>
    <td colspan="4" id="fondo">Consultar</td>
    <td id="fondo">&nbsp;</td>
    <td colspan="2" id="fuente2">A&ntilde;o / Mes</td>
    <td id="fuente2">Extrusi&oacute;n <?php $extruder=$row_ultimo['extrusion']; ?></td>
    <td id="fuente2">Impresi&oacute;n
      <?php $impresion=$row_ultimo['impresion']; ?></td>
    <td id="fuente2">Refilado
      <?php $refilado=$row_ultimo['refilado']; ?></td>
    <td id="fuente2">Sellado
      <?php $sellado=$row_ultimo['sellado']; ?></td>
    <td nowrap id="fuente2">total</td>
    <td colspan="2" nowrap id="fuente2">Guardar</td>
    </tr>
    
  <tr>
    <td id="fondo">&nbsp;</td>
    <td colspan="4" id="fuente1"><input name="consulta" id="consulta" style="width:150px;" type="month" step="1" min="2013-12" value="<?php if($_GET['consulta']==''){echo  date('Y-m');}else{echo $_GET['consulta'];} ?>" required="required" onchange="consultahoras();"/></td>
    <td id="fondo">&nbsp;</td>
    
    <td colspan="2" id="fuente2"><input name="fecha" style="width:150px;" type="month" step="1" min="2013-12" value="<?php if($_GET['consulta']==''){echo $row_ultimo['fecha'];}else{echo $_GET['consulta'];} ?>" required="required"/> </td>
    <td id="fuente2"><input name="extrusion" style="width:70px;" type="number" value="<?php echo $row_ultimo['extrusion']; ?>" required="required" step="0.01"/></td>
    <td id="fuente2"><input name="impresion" style="width:70px;" type="number" value="<?php echo $row_ultimo['impresion']; ?>" required="required" step="0.01"/>
      </td>
    <td id="fuente2"><input name="refilado" style="width:70px;" type="number" value="<?php echo $row_ultimo['refilado']; ?>" required="required" step="0.01"/></td>
    <td id="fuente2"><input name="sellado" style="width:70px;" type="number" value="<?php echo $row_ultimo['sellado']; ?>" required="required" step="0.01"/></td>
    <td nowrap id="fuente2"><?php echo $total_proceso=$extruder+$impresion+$refilado+$sellado;?>
      <input name="total" style="width:70px;" type="hidden" value="<?php echo $total_proceso; ?>" step="0.01"/>
      <input type="hidden" name="MM_insert" value="form1">
      <input type="hidden" name="id_dh" value="<?php echo $row_ultimo['id_dh']+1;?>" /></td>
    <td colspan="2" nowrap id="fuente2"><input type="submit" value="GUARDAR" /></td>
    </tr>
    
  <tr>
    <td id="fondo">&nbsp;</td>
    <td colspan="14" id="fondo"><?php  
	  //PORCENTAJE DE DISTRIBUCION PARA CADA PROCESO
	  $porc_ext =  round(porcentaje2($total_proceso,$extruder)); 
	  $porc_imp =  round(porcentaje2($total_proceso,$impresion));
	  $porc_ref =  round(porcentaje2($total_proceso,$refilado));
	  $porc_sell = round(porcentaje2($total_proceso,$sellado));?></td>
    </tr>
  <tr>
    <td id="fondo2"><?php 
	//TMRM DOLAR
$url="http://steamcommunity.com/id/vancete/stats/TF2";
$ch = curl_init();

curl_setopt ($ch, CURLOPT_URL,$url);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$page = trim(curl_exec($ch));


$pos1=strpos($page,"Accumulated Points:");
$pos1=strpos($page,'whiteText">',$pos1);
$pos1=$pos1+11;

$pos2=strpos($page,"</span>",$pos1);

$puntuacion=substr($page,$pos1,$pos2-$pos1);
//echo "puntuacion :".$puntuacion; 

//modificando entonces el codigo quedaria de esta forma :

$url="http://dportal.banrep.gov.co/j2ee/encuesta/jsp/trm_del_dia.jsp";
$ch = curl_init();

curl_setopt ($ch, CURLOPT_URL,$url);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$page = trim(curl_exec($ch));


$pos1=strpos($page,'<B>');

$pos1=$pos1;

$pos2=strpos($page,"</B>",$pos1);

$puntuacion=substr($page,$pos1,$pos2-$pos1);
//echo "TRM :".$puntuacion; 
?></td>
    <td colspan="14" id="fondo"><!--<form action="<?php echo $editFormAction; ?>" method="get" name="form1" ><input name="fecha" id="fecha" min="2000-01-02" size="15" type='date' value="<?php if($fecha==''){echo $fechafin;}else {echo $fecha;}?>" onblur="consultafech();"/></form>-->
   <?php $id=$_GET['id']; 
  if($id >= '1') { ?> <div id="numero1"> <?php echo "La fecha existia y se actualizo correctamente!"; ?> </div> <?php }
  if($id == '0') { ?><div id="numero1"> <?php echo "Se guardo correctamente"; ?> </div> <?php }?></td>
  </tr>
  <tr>
    <td colspan="16" id="fondo">Alguna Inquietud o Comentario : sistemas@acycia.com </td>
  </tr>
  <tr>
    <td colspan="16" id="subtitulo">GASTOS ADMINISTRATIVOS DEL MES DE: <? echo date("F", strtotime($row_costos_gga['fecha_ini_gv'])); ?></td>
  </tr>
  <tr>
    <td id="subppal2">GENERADOR</td>
    <td id="subppal2">VALOR</td>
    <td id="subppal2">CATEGORIA</td>
    <td id="subppal2">EXTRUSION</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">IMPRESION</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">REFILADO</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">SELLADO</td>
    <td id="subppal2">FIJO</td>
    <td colspan="2" id="subppal2">COSTO UNIT/HORA</td>
  </tr>
  <?php  
	do{
?>
  <tr>
    <td id="fuente1"><?php  
 
				  $id_g=$row_costos_gga['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre = mysql_result($resultgen, 0, 'nombre_generadores');echo $nombre; }else{echo "N.A";
				  }?></td>
    <td id="fuente1"><?php echo redondear_entero_puntos($row_costos_gga['valor_gv']); $totales_gga+=$row_costos_gga['valor_gv'] ?></td>
    <td id="fuente1"><?php  
				  $id_g=$row_costos_gga['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre_cat = mysql_result($resultgen, 0, 'categoria_generadores');echo $nombre_cat; }else{echo "N.A";
				  }?></td>
    <td id="fuente1"><?php  echo $porc_ext;?>
      %</td>
    <td id="fuente1"><?php $fijo_gga_ext = round(porcentaje($row_costos_gga['valor_gv'],$porc_ext));$total_fijo_gga_ext+=$fijo_gga_ext;echo $fijo_gga_ext?></td>
    <td id="fuente1"><?php $costo_gga_ext = ($fijo_gga_ext / $extruder);$total_costo_gga_ext+=$costo_gga_ext;echo round($costo_gga_ext);?></td>
    <td id="fuente1"><?php  echo $porc_imp;?>
      %</td>
    <td id="fuente1"><?php $fijo_gga_imp = round(porcentaje($row_costos_gga['valor_gv'],$porc_imp));$total_fijo_gga_imp+=$fijo_gga_imp;echo $fijo_gga_imp;?></td>
    <td id="fuente1"><?php $costo_gga_imp =  ($fijo_gga_imp / $impresion);$total_costo_gga_imp+=$costo_gga_imp;echo round($costo_gga_imp);?></td>
    <td id="fuente1"><?php echo $porc_ref;?>
      %</td>
    <td id="fuente1"><?php $fijo_gga_ref = round(porcentaje($row_costos_gga['valor_gv'],$porc_ref));$total_fijo_gga_ref+=$fijo_gga_ref;echo $fijo_gga_ref;?></td>
    <td id="fuente1"><?php $costo_gga_ref = ($fijo_gga_ref / $refilado);$total_costo_gga_ref+=$costo_gga_ref;echo round($costo_gga_ref);?></td>
    <td id="fuente1"><?php  echo $porc_sell;?>
      %</td>
    <td id="fuente1"><?php $fijo_gga_sell = round(porcentaje($row_costos_gga['valor_gv'],$porc_sell));$total_fijo_gga_sell+=$fijo_gga_sell;echo $fijo_gga_sell;?></td>
    <td colspan="2" id="fuente1"><?php $costo_gga_sell = ($fijo_gga_sell / $sellado);$total_costo_gga_sell+=$costo_gga_sell;echo round($costo_gga_sell);?></td>
  </tr>
  <?php } while ($row_costos_gga = mysql_fetch_assoc($costos_gga)); ?>
  <tr>
    <td id="dato3"><strong>TOTAL</strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($totales_gga);?></strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_gga_ext); ?></strong></td>
    <td id="fuente1"><strong>     
      <?php echo redondear_entero_puntos($total_costo_gga_ext); ?><input type="hidden" name="gga_ext" value="<?php echo $total_costo_gga_ext;?>"/></strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_gga_imp); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_gga_imp); ?>
      <input type="hidden" name="gga_imp" value="<?php echo $total_costo_gga_imp;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_gga_ref); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_gga_ref); ?>
      <input type="hidden" name="gga_ref" value="<?php echo $total_costo_gga_ref;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_gga_sell); ?></strong></td>
    <td colspan="2" id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_gga_sell); ?>
      <input type="hidden" name="gga_sell" value="<?php echo $total_costo_gga_sell;?>"/>
    </strong></td>
  </tr>
  <tr>
    <td colspan="16" id="subtitulo">GASTOS FINANCIEROS DEL MES DE: <? echo date("F", strtotime($row_costos_ggf['fecha_ini_gv'])); ?></td>
  </tr>
  <tr>
    <td id="subppal2">GENERADOR</td>
    <td id="subppal2">VALOR</td>
    <td id="subppal2">CATEGORIA</td>
    <td id="subppal2">EXTRUSION</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">IMPRESION</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">REFILADO</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">SELLADO</td>
    <td id="subppal2">FIJO</td>
    <td colspan="2" id="subppal2">COSTO UNIT/HORA</td>
  </tr>
  <?php  
	do{
?>
  <tr>
    <td id="fuente1"><?php  
 
				  $id_g=$row_costos_ggf['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre = mysql_result($resultgen, 0, 'nombre_generadores');echo $nombre; }else{echo "N.A";
				  }?></td>
    <td id="fuente1"><?php echo redondear_entero_puntos($row_costos_ggf['valor_gv']); $totales_ggf+=$row_costos_ggf['valor_gv'] ?></td>
    <td id="fuente1"><?php  
				  $id_g=$row_costos_ggf['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre_cat = mysql_result($resultgen, 0, 'categoria_generadores');echo $nombre_cat; }else{echo "N.A";
				  }?></td>
    <td id="fuente1"><?php  echo $porc_ext;?>
      %</td>
    <td id="fuente1"><?php $fijo_ggf_ext = round(porcentaje($row_costos_ggf['valor_gv'],$porc_ext));$total_fijo_ggf_ext+=$fijo_ggf_ext;echo $fijo_ggf_ext;?></td>
    <td id="fuente1"><?php $costo_ggf_ext = ($fijo_ggf_ext / $extruder);$total_costo_ggf_ext+=$costo_ggf_ext;echo round($costo_ggf_ext);?></td>
    <td id="fuente1"><?php  echo $porc_imp;?>
      %</td>
    <td id="fuente1"><?php $fijo_ggf_imp = round(porcentaje($row_costos_ggf['valor_gv'],$porc_imp));$total_fijo_ggf_imp+=$fijo_ggf_imp;echo $fijo_ggf_imp;?></td>
    <td id="fuente1"><?php $costo_ggf_imp = ($fijo_ggf_imp / $impresion);$total_costo_ggf_imp+=$costo_ggf_imp;echo round($costo_ggf_imp);?></td>
    <td id="fuente1"><?php echo $porc_ref;?>
      %</td>
    <td id="fuente1"><?php $fijo_ggf_ref = round(porcentaje($row_costos_ggf['valor_gv'],$porc_ref));$total_fijo_ggf_ref+=$fijo_ggf_ref;echo $fijo_ggf_ref;?></td>
    <td id="fuente1"><?php $costo_ggf_ref = ($fijo_ggf_ref / $refilado);$total_costo_ggf_ref+=$costo_ggf_ref;echo round($costo_ggf_ref);?></td>
    <td id="fuente1"><?php echo $porc_sell;?>
      %</td>
    <td id="fuente1"><?php $fijo_ggf_sell = round(porcentaje($row_costos_ggf['valor_gv'],$porc_sell));$total_fijo_ggf_sell+=$fijo_ggf_sell;echo $fijo_ggf_sell;?></td>
    <td colspan="2" id="fuente1"><?php $costo_ggf_sell =  ($fijo_ggf_sell / $sellado);$total_costo_ggf_sell+=$costo_ggf_sell;echo round($costo_ggf_sell);?></td>
  </tr>
  <?php } while ($row_costos_ggf = mysql_fetch_assoc($costos_ggf)); ?>
  <tr>
    <td id="dato3"><strong>TOTAL</strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($totales_ggf);?></strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_ggf_ext); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_ggf_ext); ?>
      <input type="hidden" name="ggf_ext" value="<?php echo $total_costo_ggf_ext;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_ggf_imp); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_ggf_imp); ?>
      <input type="hidden" name="ggf_imp" value="<?php echo $total_costo_ggf_imp;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_ggf_ref); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_ggf_ref); ?>
      <input type="hidden" name="ggf_ref" value="<?php echo $total_costo_ggf_ref;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_ggf_sell); ?></strong></td>
    <td colspan="2" id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_ggf_sell); ?>
      <input type="hidden" name="ggf_sell" value="<?php echo $total_costo_ggf_sell;?>"/>
    </strong></td>
  </tr>
  <tr>
    <td colspan="16" id="subtitulo">GASTOS VENTAS DEL MES DE: <? echo date("F", strtotime($row_costos_ggv['fecha_ini_gv'])); ?></td>
  </tr>
  <tr>
    <td id="subppal2">GENERADOR</td>
    <td id="subppal2">VALOR</td>
    <td id="subppal2">CATEGORIA</td>
    <td id="subppal2">EXTRUSION</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">IMPRESION</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">REFILADO</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">SELLADO</td>
    <td id="subppal2">FIJO</td>
    <td colspan="2" id="subppal2">COSTO UNIT/HORA</td>
  </tr>
  <?php  
	do{
?>
  <tr>
    <td id="fuente1"><?php  
 
				  $id_g=$row_costos_ggv['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre = mysql_result($resultgen, 0, 'nombre_generadores');echo $nombre; }else{echo "N.A";
				  }?></td>
    <td id="fuente1"><?php echo redondear_entero_puntos($row_costos_ggv['valor_gv']); $totales_ggv+=$row_costos_ggv['valor_gv'] ?></td>
    <td id="fuente1"><?php  
				  $id_g=$row_costos_ggv['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre_cat = mysql_result($resultgen, 0, 'categoria_generadores');echo $nombre_cat; }else{echo "N.A";
				  }?></td>
    <td id="fuente1"><?php echo $porc_ext;?>
      %</td>
    <td id="fuente1"><?php $fijo_ggv_ext = round(porcentaje($row_costos_ggv['valor_gv'],$porc_ext));$total_fijo_ggv_ext+=$fijo_ggv_ext;echo $fijo_ggv_ext?></td>
    <td id="fuente1"><?php $costo_ggv_ext = ($fijo_ggv_ext / $extruder);$total_costo_ggv_ext+=$costo_ggv_ext;echo round($costo_ggv_ext);?></td>
    <td id="fuente1"><?php echo $porc_imp;?>
      %</td>
    <td id="fuente1"><?php $fijo_ggv_imp = round(porcentaje($row_costos_ggv['valor_gv'],$porc_imp));$total_fijo_ggv_imp+=$fijo_ggv_imp;echo $fijo_ggv_imp;?></td>
    <td id="fuente1"><?php $costo_ggv_imp = ($fijo_ggv_imp / $impresion);$total_costo_ggv_imp+=$costo_ggv_imp;echo round($costo_ggv_imp);?></td>
    <td id="fuente1"><?php echo $porc_ref;?>
      %</td>
    <td id="fuente1"><?php $fijo_ggv_ref = round(porcentaje($row_costos_ggv['valor_gv'],$porc_ref));$total_fijo_ggv_ref+=$fijo_ggv_ref;echo $fijo_ggv_ref;?></td>
    <td id="fuente1"><?php $costo_ggv_ref = ($fijo_ggv_ref / $refilado);$total_costo_ggv_ref+=$costo_ggv_ref;echo round($costo_ggv_ref);?></td>
    <td id="fuente1"><?php  echo $porc_sell;?>
      %</td>
    <td id="fuente1"><?php $fijo_ggv_sell = round(porcentaje($row_costos_ggv['valor_gv'],$porc_sell));$total_fijo_ggv_sell+=$fijo_ggv_sell;echo $fijo_ggv_sell;?></td>
    <td colspan="2" id="fuente1"><?php $costo_ggv_sell =  ($fijo_ggv_sell / $sellado);$total_costo_ggv_sell+=$costo_ggv_sell;echo round($costo_ggv_sell);?></td>
  </tr>
  <?php } while ($row_costos_ggv = mysql_fetch_assoc($costos_ggv)); ?>
  <tr>
    <td id="dato3"><strong>TOTAL</strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($totales_ggv);?></strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_ggv_ext); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_ggv_ext); ?>
      <input type="hidden" name="ggv_ext" value="<?php echo $total_costo_ggv_ext;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_ggv_imp); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_ggv_imp); ?>
      <input type="hidden" name="ggv_imp" value="<?php echo $total_costo_ggv_imp;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_ggv_ref); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_ggv_ref); ?>
      <input type="hidden" name="ggv_ref" value="<?php echo $total_costo_ggv_ref;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_ggv_sell); ?></strong></td>
    <td colspan="2" id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_ggv_sell); ?>
      <input type="hidden" name="ggv_sell" value="<?php echo $total_costo_ggv_sell;?>"/>
    </strong></td>
  </tr>
  <tr>
    <td colspan="16" id="subtitulo">GASTOS INDIRECTOS DE FABRICACION DEL MES DE: <? echo date("F", strtotime($row_costos_cif['fecha_ini_gv'])); ?></td>
  </tr>
  <tr>
    <td id="subppal2">GENERADOR</td>
    <td id="subppal2">VALOR</td>
    <td id="subppal2">CATEGORIA</td>
    <td id="subppal2">EXTRUSION</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">IMPRESION</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">REFILADO</td>
    <td id="subppal2">FIJO</td>
    <td id="subppal2">COSTO UNIT/HORA</td>
    <td id="subppal2">SELLADO</td>
    <td id="subppal2">FIJO</td>
    <td colspan="2" id="subppal2">COSTO UNIT/HORA</td>
  </tr>
  <?php  
	do{
?>
  <tr>
    <td id="fuente1"><?php  
 
				  $id_g=$row_costos_cif['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre = mysql_result($resultgen, 0, 'nombre_generadores');echo $nombre; }else{echo "N.A";
				  }?></td>
    <td id="fuente1"><?php echo redondear_entero_puntos($row_costos_cif['valor_gv']); $totales_cif+=$row_costos_cif['valor_gv'] ?></td>
    <td id="fuente1"><?php  
				  $id_g=$row_costos_cif['id_generadores_gv'];
				  $sqlgen="SELECT * FROM Tbl_generadores WHERE id_generadores='$id_g'";
				  $resultgen= mysql_query($sqlgen);
				  $numgen= mysql_num_rows($resultgen);
				  if($numgen >='1')
				  { 
				  $nombre_cat = mysql_result($resultgen, 0, 'categoria_generadores');echo $nombre_cat; }else{echo "N.A";
				  }?></td>
    <td id="fuente1"><?php  echo $porc_ext;?>
      %</td>
    <td id="fuente1"><?php $fijo_cif_ext = round(porcentaje($row_costos_cif['valor_gv'],$porc_ext));$total_fijo_cif_ext+=$fijo_cif_ext;echo $fijo_cif_ext?></td>
    <td id="fuente1"><?php $costo_cif_ext = ($fijo_cif_ext / $extruder);$total_costo_cif_ext+=$costo_cif_ext;echo round($costo_cif_ext);?></td>
    <td id="fuente1"><?php  echo $porc_imp;?>
      %</td>
    <td id="fuente1"><?php $fijo_cif_imp = round(porcentaje($row_costos_cif['valor_gv'],$porc_imp));$total_fijo_cif_imp+=$fijo_cif_imp;echo $fijo_cif_imp;?></td>
    <td id="fuente1"><?php $costo_cif_imp = ($fijo_cif_imp / $impresion);$total_costo_cif_imp+=$costo_cif_imp;echo round($costo_cif_imp);?></td>
    <td id="fuente1"><?php   echo $porc_ref;?>
      %</td>
    <td id="fuente1"><?php $fijo_cif_ref = round(porcentaje($row_costos_cif['valor_gv'],$porc_ref));$total_fijo_cif_ref+=$fijo_cif_ref;echo $fijo_cif_ref;?></td>
    <td id="fuente1"><?php $costo_cif_ref = ($fijo_cif_ref / $refilado);$total_costo_cif_ref+=$costo_cif_ref;echo round($costo_cif_ref);?></td>
    <td id="fuente1"><?php  echo $porc_sell;?>
      %</td>
    <td id="fuente1"><?php $fijo_cif_sell = round(porcentaje($row_costos_cif['valor_gv'],$porc_sell));$total_fijo_cif_sell+=$fijo_cif_sell;echo $fijo_cif_sell;?></td>
    <td colspan="2" id="fuente1"><?php $costo_cif_sell = ($fijo_cif_sell / $sellado);$total_costo_cif_sell+=$costo_cif_sell;echo round($costo_cif_sell);?></td>
  </tr>
  <?php } while ($row_costos_cif = mysql_fetch_assoc($costos_cif)); ?>
  <tr>
    <td id="dato3"><strong>TOTAL</strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($totales_cif);?></strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_cif_ext); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_cif_ext); ?>
      <input type="hidden" name="cif_ext" value="<?php echo $total_costo_cif_ext;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_cif_imp); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_cif_imp); ?>
      <input type="hidden" name="cif_imp" value="<?php echo $total_costo_cif_imp;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_cif_ref); ?></strong></td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_cif_ref); ?>
      <input type="hidden" name="cif_ref" value="<?php echo $total_costo_cif_ref;?>"/>
    </strong></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1"><strong><?php echo redondear_entero_puntos($total_fijo_cif_sell); ?></strong></td>
    <td colspan="2" id="fuente1"><strong><?php echo redondear_entero_puntos($total_costo_cif_sell); ?>
      <input type="hidden" name="cif_sell" value="<?php echo $total_costo_cif_sell;?>"/>
    </strong></td>
  </tr>
  <tr>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td colspan="2" id="fuente1">&nbsp;</td>
  </tr>
  <tr>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td id="subtitulo">&nbsp;</td>
    <td colspan="2" id="subtitulo"><?php echo $_GET['consulta'];?></td>
  </tr>
  </table>
  </form>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($costos_gga);

mysql_free_result($costos_ggf);

mysql_free_result($costos_ggv);

mysql_free_result($costos_cif);
?>
