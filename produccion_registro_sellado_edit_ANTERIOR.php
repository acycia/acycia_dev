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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	$totalk=$_POST['int_kilos_prod_rp']-$_POST['int_kilos_desp_rp'];
  $updateSQL = sprintf("UPDATE Tbl_reg_produccion SET id_proceso_rp=%s, id_op_rp=%s,id_ref_rp=%s, int_cod_ref_rp=%s, version_ref_rp=%s, placa_rp=%s, bolsa_rp=%s, lam1_rp=%s, lam2_rp=%s, turno_rp=%s, rollo_rp=%s, n_ini_rp=%s, n_fin_rp=%s, int_kilos_prod_rp=%s, int_kilos_desp_rp=%s, int_total_kilos_rp=%s, porcentaje_op_rp=%s, int_metro_lineal_rp=%s, int_total_rollos_rp=%s, total_horas_rp=%s, rodamiento_rp=%s, horas_muertas_rp=%s, horas_prep_rp=%s, str_maquina_rp=%s, str_responsable_rp=%s, fecha_ini_rp=%s, fecha_fin_rp=%s, int_kilosxhora_rp=%s,int_cod_empleado_rp=%s,int_cod_liquida_rp=%s WHERE id_rp=%s",
                       GetSQLValueString($_POST['id_proceso_rp'], "int"),
                       GetSQLValueString($_POST['id_op_rp'], "int"),
					   GetSQLValueString($_POST['id_ref_rp'], "int"),
                       GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
                       GetSQLValueString($_POST['version_ref_rp'], "int"),
					   GetSQLValueString($_POST['placa_rp'], "text"),
					   GetSQLValueString($_POST['bolsa_rp'], "int"),
					   GetSQLValueString($_POST['lam1_rp'], "double"),
					   GetSQLValueString($_POST['lam2_rp'], "double"),
					   GetSQLValueString($_POST['turno_rp'], "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
					   GetSQLValueString($_POST['n_ini_rp'], "text"),
					   GetSQLValueString($_POST['n_fin_rp'], "text"),					   
                       GetSQLValueString($_POST['int_kilos_prod_rp'] , "double"),
                       GetSQLValueString($_POST['int_kilos_desp_rp'], "text"),
					   GetSQLValueString($totalk, "double"),
					   GetSQLValueString($_POST['porcentaje'], "int"),
					   GetSQLValueString($_POST['int_metro_lineal_rp'], "int"),
					   GetSQLValueString($_POST['int_total_rollos_rp'], "int"),					   
                       GetSQLValueString($_POST['total_horas_rp'], "text"),
					   GetSQLValueString($_POST['rodamiento_rp'], "text"),
					   GetSQLValueString($_POST['valor_tiem_rt'], "text"), 
					   GetSQLValueString($_POST['valor_prep_rt'], "text"),              
                       GetSQLValueString($_POST['str_maquina_rp'], "text"),
                       GetSQLValueString($_POST['str_responsable_rp'], "text"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"),
                       GetSQLValueString($_POST['fecha_fin_rp'], "date"),
					   GetSQLValueString($_POST['int_kilosxhora_rp'], "double"),
					   GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
					   GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),			   
                       GetSQLValueString($_POST['id_rp'], "int"));
				   					   					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());	
  				    
  $updateGoTo = "produccion_registro_sellado_total_vista.php?id_op=" . $_POST['id_op_rp'] ."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
//INSERT
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//IMPRIME LOS CAMPOS DE EXTRUSION LINEAL Y ROLLOS
$colname_rp= "-1";
if (isset($_GET['id_rp'])) {
  $colname_rp = (get_magic_quotes_gpc()) ? $_GET['id_rp'] : addslashes($_GET['id_rp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_registro_edit =sprintf( "SELECT * FROM Tbl_reg_produccion WHERE id_rp=%s AND id_proceso_rp='4' ORDER BY id_rp DESC",$colname_rp);
$registro_edit = mysql_query($query_registro_edit, $conexion1) or die(mysql_error());
$row_registro_edit = mysql_fetch_assoc($registro_edit);
$totalRows_registro_edit = mysql_num_rows($registro_edit);

$rollo_num = $row_registro_edit['rollo_rp'];
//CARGA LOS STANDBY 
$colname_standBy= "-1";
if (isset($_GET['id_op'])) {
  $colname_standBy = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
 mysql_select_db($database_conexion1, $conexion1);
$query_standBy = sprintf("SELECT *, SUM(`valor_tiem_rt`) AS standby  FROM Tbl_reg_tiempo WHERE Tbl_reg_tiempo.op_rt=%s AND Tbl_reg_tiempo.id_proceso_rt='4' AND int_rollo_rt='$rollo_num' AND id_rpt_rt='141' GROUP BY id_rpt_rt ASC",$colname_standBy);
$standBy = mysql_query($query_standBy, $conexion1) or die(mysql_error());
$row_standBy = mysql_fetch_assoc($standBy);
$totalRows_standBy = mysql_num_rows($standBy);
//CARGA LOS TIEMPOS MUERTOS 
$colname_tiempoMuerto= "-1";
if (isset($_GET['id_op'])) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
 mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT *, SUM(`valor_tiem_rt`) AS muertos  FROM Tbl_reg_tiempo WHERE op_rt=%s AND id_rpt_rt <> '141' AND id_proceso_rt='4' AND int_rollo_rt='$rollo_num' GROUP BY id_rpt_rt ASC",$colname_tiempoMuerto);//141 standby
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT *, SUM(`valor_prep_rtp`) AS preparacion FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=%s AND id_proceso_rtp='4' AND int_rollo_rtp='$rollo_num' GROUP BY id_rpt_rtp ASC",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS TIEMPOS  DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT *, SUM(`valor_desp_rd`) AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd=%s AND id_proceso_rd='4' AND int_rollo_rd='$rollo_num' GROUP BY `id_rpd_rd` ASC",$colname_tiempoMuerto);
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);
//CARGA LOS TIEMPOS KILOS PRODUCIDOS
mysql_select_db($database_conexion1, $conexion1);
$query_producido = sprintf("SELECT *, SUM(`valor_prod_rp`) AS producido FROM  Tbl_reg_kilo_producido WHERE op_rp=%s AND id_proceso_rkp='4' AND id_rpp_rp NOT IN (1406,1407,1655,1656,1657) GROUP BY id_rpp_rp ASC",$colname_tiempoMuerto);
$producido = mysql_query($query_producido, $conexion1) or die(mysql_error());
$row_producido = mysql_fetch_assoc($producido);
$totalRows_producido = mysql_num_rows($producido);
//SUMA TOTAL DE KILOS EXTRUIDOS POR O.P
$colname_totalKilos= "-1";
if (isset($_GET['id_op'])) {
  $colname_totalKilos = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_totalKilos = sprintf("SELECT * FROM Tbl_reg_produccion WHERE id_op_rp=%s AND id_proceso_rp='4' ORDER BY rollo_rp DESC",$colname_totalKilos);
$totalKilos = mysql_query($query_totalKilos, $conexion1) or die(mysql_error());
$row_totalKilos = mysql_fetch_assoc($totalKilos);
$totalRows_totalKilos = mysql_num_rows($totalKilos);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='3' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
//CODIGO EMPLEADO
mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado WHERE tipo_empleado IN(7,9) ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);
//ORDEN DE PRODUCCION DEFINE TOTAL KILOS INGRESADOS
$colname_op= "-1";
if (isset($_GET['id_op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = sprintf("SELECT * FROM Tbl_orden_produccion WHERE id_op=%s AND b_borrado_op='0' ORDER BY id_op DESC",$colname_op);
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script> 
<script type="text/javascript" src="js/ajax.js"> </script>
</head>
<body onload="kilosxHora3();"><!--onload="kilosxHora3();"-->
<div align="center">
<table align="center" id="tabla"><tr align="center"><td height="920">
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
	<li><a href="produccion_registro_sellado_listado.php">LISTADO SELLADO</a></li>		
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" onSubmit="return ( validacion_unodelosdos_sell() && validacion_select_fecha());">
 
<table id="tabla2">
  <tr id="tr1">
    <td colspan="13" id="titulo2">REGISTRO DEL ROLLO EN SELLADO</td>
  </tr>
  <tr>
    <td width="137" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
    <td colspan="11" id="dato3">
    <a href="javascript:eliminar_liq('id_rliqs',<?php echo 0;?>,'produccion_registro_sellado_listado_add.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR ROLLO LIQUIDADO"
title="ELIMINAR ROLLO LIQUIDADO" border="0" /></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="produccion_registro_sellado_total_vista.php?id_op=<?php echo $row_registro_edit['id_op_rp']; ?>"><img src="images/hoja.gif" alt="VISTA SELLADA" title="VISTA SELLADA" border="0" /></a><a href="produccion_registro_sellado_listado.php"><img src="images/opciones.gif" alt="LISTADO SELLADAS"title="LISTADO SELLADAS" border="0" style="cursor:hand;" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
  </tr>
  <tr id="tr1">
    <td width="182" colspan="3" nowrap="nowrap" id="dato2">ORDEN DE PRODUCCION</td>
    <td colspan="8" id="dato3"> Ingresado por
      <input name="str_responsable_rp" type="text" id="str_responsable_rp" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
  </tr>
  <tr id="tr3">
    <td colspan="3" nowrap="nowrap" id="numero2"><?php echo $row_registro_edit['id_op_rp'];?></td>
    <td width="126" colspan="6" nowrap="nowrap" id="fuente2"><input name="id_rp" type="hidden" id="id_rp" value="<?php echo $row_registro_edit['id_rp']; ?>" /></td>
    <td width="235" colspan="2" id="fuente2">&nbsp;</td>
  </tr>
  <tr id="tr1">
    <td colspan="3" nowrap="nowrap" id="dato2">REFERENCIA</td>
    <td colspan="8" id="dato2">VERSION</td>
  </tr>
  <tr>
    <td colspan="3" nowrap="nowrap" id="numero2"><?php echo $row_registro_edit['int_cod_ref_rp'];?></td>
    <td colspan="8" nowrap="nowrap" id="numero2"><?php echo $row_registro_edit['version_ref_rp'];?></td>
  </tr>
  <tr>
    <td colspan="3" id="dato2"><input type="hidden" name="ancho" id="ancho" style="width:80px" value="<?php echo $row_orden_produccion['int_ancho_rollo_op'];?>"/>
      <input type="hidden" name="calibre" id="calibre"  style="width:80px" value="<?php echo $row_orden_produccion['int_calibre_op'];?>" /></td>
    <td colspan="8" id="dato2">&nbsp;</td>
  </tr> 
  <tr id="tr1">  
    <td colspan="13" id="titulo4">DETALLE CONSUMO</td>
  </tr>
 <tr >
   <td colspan="2"id="fuente1">Placa Rollo</td>
   <td id="fuente1"><input name="placa_rp" type="text" id="placa_rp" style="width:80px" required="required" autofocus="autofocus" value="<?php echo $row_registro_edit['placa_rp'];?>" onchange="kilosxHora3();"/></td>
   <td id="fuente1">Peso  Aprox. Rollo </td>
   <td colspan="5" id="fuente1">
     <input type="number" name="int_kilos_prod_rp" id="int_kilos_prod_rp" min="0"step="any" size="12" required="required" style="width:80px" value="<?php echo $row_registro_edit['int_kilos_prod_rp'];?>" onchange="kilosxHora3();" />
     <input type="hidden" name="kilos_r" id="kilos_r" min="0.10" step="any"size="12" required="required" style="width:80px" value="<?php echo redondear_decimal($divide);?>" /></td>
   <td id="fuente1">Bolsas x Rollo</td>
   <td colspan="3" id="fuente1"><input name="bolsa_rp" type="number" id="bolsa_rp" style="width:80px" required="required" value="<?php echo $row_registro_edit['bolsa_rp'];?>"/></td>
 </tr>
 <tr >
   <td colspan="2" id="fuente1">Lamina 1 kg</td>
   <td id="fuente1"><input name="lam1_rp" type="number" id="lam1_rp" style="width:80px" required="required" min="0" step="0.01" value="<?php echo $row_registro_edit['lam1_rp'];?>" /></td>
   <td id="fuente1">Lamina 2 klg</td>
   <td colspan="5" id="fuente6"><input name="lam2_rp" type="number" id="lam2_rp" style="width:80px" required="required" min="0" step="0.01" value="<?php echo $row_registro_edit['lam2_rp'];?>"/></td>
   <td id="fuente1">Turno </td>
   <td colspan="3" id="fuente6"><input name="turno_rp" type="number" id="turno_rp" style="width:80px" required="required" max="7" min="1" step="1" value="<?php echo $row_registro_edit['turno_rp'];?>" readonly="readonly"/></td>
 </tr>
 <tr >
   <td colspan="2" id="fuente1">&nbsp;</td>
   <td id="fuente1">&nbsp;</td>
   <td id="fuente1">&nbsp;</td>
   <td colspan="5" id="fuente1">&nbsp;</td>
   <td id="fuente1">&nbsp;</td>
   <td colspan="3" id="fuente1">&nbsp;</td>
 </tr>
 <tr >
   <td colspan="2" id="fuente1"><input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Tiempos y Desperdicio" onclick="if(form1.fecha_ini_rp.value=='' || form1.rollo_rp.value=='' || form1.bolsa_rp.value=='') { alert('DEBE SELECCIONAR FECHA INICIO, ROLLO Y CANTIDAD DE BOLSAS'); }else{verFoto('produccion_registro_sellado_detalle_add.php?idop='+document.getElementById('id_op_rp').value+'&amp;fecha='+document.getElementById('fecha_ini_rp').value+'&amp;rollo='+document.getElementById('rollo_rp').value+'&amp;bolsas='+document.getElementById('bolsa_rp').value+'','850','450')}"/></td>
   <td id="fuente1">&nbsp;</td>
   <td id="fuente1">&nbsp;</td>
   <td colspan="5" id="fuente1">&nbsp;</td>
   <td id="fuente1">&nbsp;</td>
   <td colspan="3" id="fuente1">&nbsp;</td>
 </tr> 
  <tr>
    <td colspan="2" id="dato1"></td>
    <td colspan="3" id="dato1"></td>
    <td colspan="6" id="dato1"></td>
    <td colspan="2" id="dato1"></td>
  </tr>
  <tr id="tr1">
    <td colspan="13" id="titulo4">CONSUMOS</td>
  </tr>
  <!--<tr>
    <td colspan="13" id="fuente2"><a href="javascript:verFoto('produccion_regist_sellado_kilos_prod.php?id_op=<?php echo $row_registro_edit['id_op_rp'] ?>&rollo=<?php echo $row_registro_edit['rollo_rp']?>&fecha=<?php echo $row_registro_edit['fecha_ini_rp']?>','820','470')">
      <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Detalle consumo"/>
      </a><a href="javascript:verFoto('produccion_registro_sellado_detalle_add.php?id_op=<?php echo $row_registro_edit['id_op_rp'] ?>&rollo=<?php echo $row_registro_edit['rollo_rp']?>&fecha=<?php echo $row_registro_edit['fecha_ini_rp']?>','820','270')">
        <input type="button" name="Tiempos Desperdicio" id="check_sh1" value="Tiempos Desperdicio"/>
        </a>
      <input type="button" name="check_sh" id="check_sh2" value="Mostrar" onclick="mostrardiv1()"/>
      <input type="button" value="Ocultar" onclick="ocultardiv1()" /></td>
  </tr>-->
  <tr>
    <td colspan="13" id="fuente2">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="13" id="fuente1">
    <fieldset> <legend>Registro de Tiempos y Desperdicios</legend>
    <table  width="100%"  border="0" id="flotante">
      <?php if($row_standBy['id_rpt_rt']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Fin de Semana - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Fin de Semana - Minutos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA </strong></td>
      </tr>
      <?php  for ($s=0;$s<=$totalRows_standBy-1;$s++) { ?>
      <tr>
        <td id="fuente1">
          <?php  
	  $id_stand=mysql_result($standBy,$s,id_rpt_rt); 
	  $sqlstand="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_stand'";
	  $resultstand= mysql_query($sqlstand);
	  $numstand = mysql_num_rows($resultstand);
	  if($numstand >='1')
	  { 
	  echo $Nombrestandby = mysql_result($resultstand, 0, 'nombre_rtp'); }?></td>
        <td id="fuente1"><?php $varST=mysql_result($standBy,$s,standby);echo $varST; $totalST+=$varST; ?></td>
        <td id="fuente1"><a href="javascript:eliminar_rts('id_rts',<?php $delrt=mysql_result($standBy,$k,id_rt); echo $delrt;?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?> 
      <tr>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
            <?php if($totalST!=''){echo $totalST;}else{echo "0";}  ?>
            = <?php echo redondear_decimal($totalST/60); ?> Horas</strong></td>
          <td id="fuente1">&nbsp;</td>
          </tr>   
    <?php } ?>  
      <?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Tiempos Muertos - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Tiempos Muertos - Minutos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($k=0;$k<=$totalRows_tiempoMuerto-1;$k++) { ?>
      <tr>
        <td id="fuente1">
          <?php $id1=mysql_result($tiempoMuerto,$k,id_rpt_rt); 
	  $id_tm=$id1;
	  $sqltm="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
	  $resulttm= mysql_query($sqltm);
	  $numtm= mysql_num_rows($resulttm);
	  if($numtm >='1')
	  { 
	  $nombre1 = mysql_result($resulttm, 0, 'nombre_rtp');echo $nombre1; }?></td>
        <td id="fuente1"><?php $var1=mysql_result($tiempoMuerto,$k,muertos);echo $var1; $totalTM+=$var1; ?>
        <!--<input name="standby[]" id="standby[]" type="hidden" value="<?php  echo $nombre1; ?> "/>--></td>
        <td id="fuente1"><a href="javascript:eliminar_rts('id_rts',<?php $delrt=mysql_result($tiempoMuerto,$k,id_rt); echo $delrt; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <tr>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
            <?php if($totalTM!=''){echo $totalTM;}else{echo "0";}  ?>
            </strong>           </td>
          <td id="fuente1">&nbsp;</td>
          </tr>
      <?php } ?>
      <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Tiempos Preparacion - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Tiempos Preparacion - Minutos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($x=0;$x<=$totalRows_tiempoPreparacion-1;$x++) { ?>
      <tr>
        <td id="fuente1"><?php $id2=mysql_result($tiempoPreparacion,$x,id_rpt_rtp); 
	  $id_rtp=$id2;
	  $sqlrtp="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
	  $resultrtp= mysql_query($sqlrtp);
	  $numrtp= mysql_num_rows($resultrtp);
	  if($numrtp >='1')
	  { 
	  $nombre2 = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombre2; }?></td>
        <td id="fuente1"><?php $var2=mysql_result($tiempoPreparacion,$x,preparacion); echo $var2;$totalTP+=$var2; ?></td>
        <td id="fuente1"><a href="javascript:eliminar_rts('id_rps',<?php $delrp=mysql_result($tiempoPreparacion,$x,id_rt); echo $delrp; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <tr>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
			<?php if($totalTP!=''){echo $totalTP;}else{echo "0";}  ?>
            </strong>           </td>
          <td id="fuente1">&nbsp;</td>
          </tr>
      <?php } ?>
      <?php if($row_desperdicio['id_rpd_rd']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Desperdicios - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Desperdicios - Kilos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($i=0;$i<=$totalRows_desperdicio-1;$i++) { ?>
      <tr>
        <td id="fuente1"><?php $id3=mysql_result($desperdicio,$i,id_rpd_rd); 
	  $id_rpd=$id3;
	  $sqlrtd="SELECT * FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
	  $resultrtd= mysql_query($sqlrtd);
	  $numrtd= mysql_num_rows($resultrtd);
	  if($numrtd >='1')
	  { 
	  $nombre3 = mysql_result($resultrtd, 0, 'nombre_rtp'); echo $nombre3; }?></td>
        <td id="fuente1"><?php $var3=mysql_result($desperdicio,$i,desperdicio); echo $var3; $totalTD+=$var3;?></td>
        <td id="fuente1"><a href="javascript:eliminar_rts('id_rds',<?php $delrd=mysql_result($desperdicio,$i,id_rd); echo $delrd; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <tr>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
            <?php if($totalTD!=''){echo $totalTD;}else{echo "0";}  ?>
            <input name="valor_desp_rd" type="hidden" id="valor_desp_rd" value="<?php echo $totalTD; ?>" size="6"/>
            </strong>           </td>
          <td id="fuente1">&nbsp;</td>
          </tr>
      <?php } ?>
      <?php if($row_producido['id_rpp_rp']!='') {?>
      <tr>
        <td nowrap id="detalle2"><strong>Insumos  - Tipo</strong></td>
        <td nowrap id="detalle2"><strong>Mts - Kilos</strong></td>
        <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      </tr>
      <?php  for ($y=0;$y<=$totalRows_producido-1;$y++) { ?>
      <tr>
        <td id="fuente1"><?php $id4=mysql_result($producido,$y,id_rpp_rp); 
	  $id_rpp=$id4;
	  $sqlri="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$id_rpp' AND clase_insumo IN(1,2,5)";
	  $resultri= mysql_query($sqlri);
	  $numri= mysql_num_rows($resultri);
	  if($numri >='1')
	  { 
	  $nombre4 = mysql_result($resultri, 0, 'descripcion_insumo'); echo $nombre4; }?></td>
        <td id="fuente1"><?php $var4=mysql_result($producido,$y,producido); echo $var4; $totalMM+=$var4;?></td>
        <td id="fuente1"><a href="javascript:eliminar_rts('id_ips',<?php $delip=mysql_result($producido,$y,id_rkp); echo $delip; ?>,'produccion_registro_sellado_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0" /></a></td>
      </tr>
      <?php } ?>
      <tr>
          <td id="fuente1">TOTAL</td>
          <td id="fuente1">
            <strong>
            <?php if($totalMM!=''){echo $totalMM;}else{echo "0";}  ?>
            <input name="valor_prod_rp" type="hidden" id="valor_prod_rp" value="<?php echo $totalMM; ?>" size="6" onblur="getSumP();"/>
            </strong>           </td>
          <td id="fuente1">&nbsp;</td>
          </tr>
      <?php } ?>
    </table>
      </fieldset>
      </td>
  </tr>
  <tr id="tr1">
    <td colspan="2" id="fuente1">Maquina<strong>
      <input name="standby" type="hidden" id="standby" value="<?php if($totalST!=''){echo $totalST;}else{echo "0";}  ?>" size="5"/>
      <input name="valor_tiem_rt"  id="valor_tiem_rt" type="hidden" size="5" value="<?php if($totalTM==''){echo 0;}else{echo $totalTM;} ?>"/>
      <input name="valor_prep_rt" id="valor_prep_rt" type="hidden" size="5" value="<?php if($totalTP==''){echo 0;}else{echo $totalTP;}?>"/>
    </strong></td>
    <td colspan="3" id="fuente1"><select name="str_maquina_rp" id="str_maquina_rp" style="width:145px">
      <?php
do {  
?>
      <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_registro_edit['str_maquina_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
      <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
    </select></td>
    <td colspan="8" id="fuente1"><select name="int_cod_empleado_rp" id="operario"onBlur="validacion_unodelosdos_sell();" style="width:120px">
      <option value=""<?php if (!(strcmp("", $row_registro_edit['int_cod_empleado_rp']))) {echo "selected=\"selected\"";} ?>>Operario</option>
      <?php
do {  
?>
      <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_registro_edit['int_cod_empleado_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
      <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
    </select>
      -
      <select name="int_cod_liquida_rp" style="width:120px" id="auxiliar" onBlur="validacion_unodelosdos_sell();">
        <option value=""<?php if (!(strcmp("", $row_registro_edit['int_cod_liquida_rp']))) {echo "selected=\"selected\"";} ?>>Revisor</option>
        <?php
do {  
?>
        <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_registro_edit['int_cod_liquida_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
        <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
      </select></td>
  </tr>
  <tr>
    <td colspan="2" id="dato1"></td>
    <td colspan="3" id="dato1"></td>
    <td colspan="6" id="dato1"></td>
    <td colspan="2" id="dato1"></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1">Fecha Inicial</td> 
    <td colspan="3" id="fuente1"><input name="fecha_ini_rp" id="fecha_ini_rp" type="datetime-local"  min="2000-01-02" value="<?php echo muestradatelocal($row_registro_edit['fecha_ini_rp']);?>" size="15" required /></td>
    <td colspan="8" id="fuente1"><input type="number" name="rollo_rp" id="rollo_rp" min="0" step="any" required="required" placeholder="Rollos" style="width:46px" value="<?php echo $row_registro_edit['rollo_rp'];?>" onclick="getSumT();"/>
de
  <input name="int_total_rollos_rp" type="number" id="int_total_rollos_rp" placeholder="Rollos" style="width:46px" min="0"step="any" value="<?php echo $row_registro_edit['int_total_rollos_rp'];?>" readonly="readonly"/>
Total Rollos</td>
  </tr>
  <tr id="tr1">
    <td colspan="2" id="fuente1">Fecha Final</td>
    <td colspan="3" id="fuente1"><input name="fecha_fin_rp" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_registro_edit['fecha_fin_rp']);?>"size="15" onblur="validacion_select_fecha();" required onchange="kilosxHora3();"/></td>
    <td colspan="8" id="fuente1"> 
    <input name="int_metro_lineal_rp" type="number" required="required" id="metro_r" style="width:116px" min="0" step="any" value="<?php echo $row_registro_edit['int_metro_lineal_rp']; ?>" onChange="kilosxHora3()" readonly="readonly"/>
Metro lineal </td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1">Total Horas Trabajadas</td>
    <td colspan="3" id="fuente1"><?php $fechaini=$row_registro_edit['fecha_ini_rp'];$fechafin=$row_registro_edit['fecha_fin_rp'];?>
      <input  name="rodamiento_rp" id="rodamiento_rp" type="text" required="required"   value="<?php echo $row_orden_produccion['rodamiento_rp']; ?>" size="15" onclick="kilosxHora3();"/> 
      <input name="total_horas_rp" type="hidden" id="total_horas_rp" required="required" readonly="readonly" value="<?php echo $row_registro_edit['total_horas_rp']; ?>" size="15"/>
      <input id="porcentaje" name="porcentaje" type="hidden" value="<?php echo $row_registro_edit['porcentaje_op_rp']; ?>" min="0" max="100" step="1" style="width:40px" required="required" readonly="readonly" onblur="return validacion_kilos();"/></td>
    <td colspan="8" id="fuente1"><input name="int_kilosxhora_rp" type="number" required="required" id="int_kilosxhora_rp" min="0.10" step="any"  value="<?php echo $row_registro_edit['int_kilosxhora_rp']; ?>"   style="width:116px" onclick="kilosxHora3();"  />
      Kilos x Hora</td>
  </tr>
  <tr id="tr1">
    <td colspan="2" id="fuente1">Numeracion Inicial</td>
    <td colspan="3" id="fuente1"><input name="n_ini_rp" type="text" id="n_ini_rp" required="required" onBlur="conMayusculas(this);" value="<?php echo $row_registro_edit['n_ini_rp'];?>" size="15" /></td>
    <td colspan="8" id="fuente1"><input name="n_fin_rp" type="text" id="n_fin_rp" required="required" onBlur="conMayusculas(this)" value="<?php echo $row_registro_edit['n_fin_rp'];?>" style="width:116px" /> 
      Numeracion Final</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1">Desperdicio MP</td>
    <td colspan="3" id="fuente1"><input type="number" name="int_kilos_desp_rp" id="int_kilos_desp_rp" min="0"step="any" style="width:135px" value="<?php if($row_registro_edit['int_kilos_desp_rp']!=''){echo $row_registro_edit['int_kilos_desp_rp'];}else{echo $totalTD;};?>" onchange="kilosxHora3();"  /></td>
    <td colspan="8" id="fuente1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="13" id="fuente3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="13"><!--tabla de caracteristicas y temperaturas--></td>
  </tr>
  <tr id="tr1">
    <td colspan="13" id="dato2"><input type="hidden" name="horas_muertas_rp" id="horasmuertas"  size="12"  value="0" />
      <input type="hidden" name="horas_prep_rp" id="horasprep"  size="12" value="0" /><input name="id_proceso_rp" type="hidden" id="id_proceso_rp" value="4" />
      
      <input name="id_op_rp" type="hidden" id="id_op_rp" value="<?php echo $row_registro_edit['id_op_rp']; ?>" />
      <input name="id_ref_rp" type="hidden" id="id_ref_rp" value="<?php echo $row_registro_edit['id_ref_rp']; ?>" />
      <input name="int_cod_ref_rp" type="hidden" id="int_cod_ref_rp" value="<?php echo $row_registro_edit['int_cod_ref_rp']; ?>" />
      <input name="version_ref_rp" type="hidden" id="version_ref_rp" value="<?php echo $row_registro_edit['version_ref_rp']; ?>" />
      <input name="kilos_op" type="hidden" id="kilos_op" value="<?php echo $row_orden_produccion['int_kilos_op']; ?>" />
      <?php  for ($r=0;$r<=$totalRows_totalKilos-1;$r++) { ?>
      <input name="kilos_sellado[]" type="hidden" id="kilos_sellado[]" value="<?php $tK=mysql_result($totalKilos,$r,int_total_kilos_rp); echo $tK; ?>" />
      <?php } ?>  
      <input type="submit" name="ENVIAR" id="ENVIAR" value="EDITAR" />
      <input type="hidden" name="MM_update" value="form1" /></td>
  </tr>
</table>
 
  </form>
  </td></tr></table>
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
mysql_free_result($orden_produccion);
mysql_free_result($maquinas);
mysql_free_result($tiempoMuerto);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($producido);
mysql_free_result($totalKilos);
mysql_free_result($codigo_empleado);
?>
