<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
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


$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
//echo 'ingreso';die;
   $updateSQL = sprintf("UPDATE Tbl_reg_produccion SET id_proceso_rp=%s, id_op_rp=%s, int_cod_ref_rp=%s, version_ref_rp=%s, int_kilos_prod_rp=%s, int_kilos_desp_rp=%s, int_total_kilos_rp=%s, porcentaje_op_rp=%s, int_metro_lineal_rp=%s, int_total_rollos_rp=%s, total_horas_rp=%s, rodamiento_rp=%s, horas_muertas_rp=%s, horas_prep_rp=%s, str_maquina_rp=%s, str_responsable_rp=%s, fecha_fin_rp=%s, int_kilosxhora_rp=%s, int_cod_empleado_rp=%s, int_cod_liquida_rp=%s, costo=%s WHERE id_rp=%s",
                       GetSQLValueString($_POST['id_proceso_rp'], "int"),
                       GetSQLValueString($_POST['id_op_rp'], "int"),
                       GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
                       GetSQLValueString($_POST['version_ref_rp'], "int"),
                       GetSQLValueString($_POST['int_kilos_prod_rp'], "double"),
                       GetSQLValueString($_POST['int_kilos_desp_rp'], "double"),
					   GetSQLValueString($_POST['int_total_kilos_rp'], "double"),
					   GetSQLValueString($_POST['porcentaje'], "int"),
					   GetSQLValueString($_POST['int_metro_lineal_rp'], "int"),
					   GetSQLValueString($_POST['int_total_rollos_rp'], "int"),						   
                       GetSQLValueString($_POST['total_horas_rp'], "text"),
					   GetSQLValueString($_POST['tiempoOptimo_rp'], "text"),
                       GetSQLValueString($_POST['valor_tiem_rt'], "text"),
					   GetSQLValueString($_POST['valor_prep_rtp'], "text"),
                       GetSQLValueString($_POST['str_maquina_rp'], "text"),
                       GetSQLValueString($_POST['str_responsable_rp'], "text"), 
                       GetSQLValueString($_POST['fecha_fin_rp'], "date"),
					   GetSQLValueString($_POST['int_kilosxhora_rp'], "double"),
					   GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
					   GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),
					   GetSQLValueString($_POST['costo'], "int"),			   
                       GetSQLValueString($_POST['id_rp'], "int"));
					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());					   

  $updateSQL2 = sprintf("UPDATE Tbl_produccion_mezclas SET int_ref1_rpm_pm=%s,int_ref1_tol5_porc1_pm=%s,int_ref2_rpm_pm=%s,int_ref2_tol5_porc2_pm=%s,int_ref3_rpm_pm=%s,int_ref3_tol5_porc3_pm=%s WHERE id_pm=%s", 
					   GetSQLValueString($_POST['int_ref1_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref1_tol5_porc1_pm'], "double"),
					   GetSQLValueString($_POST['int_ref2_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref2_tol5_porc2_pm'], "double"),
					   GetSQLValueString($_POST['int_ref3_rpm_pm'], "text"),
					   GetSQLValueString($_POST['int_ref3_tol5_porc3_pm'], "double"),
					   GetSQLValueString($_POST['id_pm'], "int"));
					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error()); 					   
 		   
					  
  $updateSQL4 = sprintf("UPDATE Tbl_orden_produccion SET b_estado_op='1', b_visual_op='0' WHERE id_op=%s",
					   GetSQLValueString($_POST['id_op_rp'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result4 = mysql_query($updateSQL4, $conexion1) or die(mysql_error());
  
 /* $updateGoTo = "produccion_registro_extrusion_vista.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));*/
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//numero id_rp
$colname_rp= "-1";
if (isset($_GET['id_rp'])) {
  $colname_rp = (get_magic_quotes_gpc()) ? $_GET['id_rp'] : addslashes($_GET['id_rp']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rp = sprintf("SELECT * FROM Tbl_reg_produccion WHERE id_rp='%s' AND id_proceso_rp='1' ORDER BY fecha_ini_rp ASC",$colname_rp);
$rp_edit= mysql_query($query_rp, $conexion1) or die(mysql_error());
$row_rp_edit = mysql_fetch_assoc($rp_edit);
$totalRows_rp_edit = mysql_num_rows($rp_edit);

$fechaR=$row_rp_edit['fecha_ini_rp'];
$fechaF=$row_rp_edit['fecha_fin_rp'];
$Rollo=$row_rp_edit['rollo_rp'];
$Rollo_parcial=$row_rp_edit['parcial'];;
 
//LLENA CAMPOS DE MEZCLAS
$colname_mezcla= "-1";
if (isset($_GET['id_op'])) {
  $colname_mezcla = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_mezcla = sprintf("SELECT * FROM Tbl_orden_produccion,Tbl_produccion_mezclas WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.int_cod_ref_op=Tbl_produccion_mezclas.int_cod_ref_pm ",$colname_mezcla);
$mezcla = mysql_query($query_mezcla, $conexion1) or die(mysql_error());
$row_mezcla = mysql_fetch_assoc($mezcla);
$totalRows_mezcla = mysql_num_rows($mezcla);

 //CARGA LOS TIEMPOS MUERTOS 
$colname_tiempoMuerto= "-1";
if (isset($_GET['id_op'])) {
  $colname_tiempoMuerto = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoMuerto = sprintf("SELECT * FROM Tbl_reg_tiempo WHERE op_rt=%s AND id_proceso_rt='1' ORDER BY id_rpt_rt ASC",$colname_tiempoMuerto);
$tiempoMuerto = mysql_query($query_tiempoMuerto, $conexion1) or die(mysql_error());
$row_tiempoMuerto = mysql_fetch_assoc($tiempoMuerto);
$totalRows_tiempoMuerto = mysql_num_rows($tiempoMuerto);
//CARGA LOS TIEMPOS PREPARACION 
mysql_select_db($database_conexion1, $conexion1);
$query_tiempoPreparacion = sprintf("SELECT * FROM Tbl_reg_tiempo_preparacion WHERE op_rtp=%s AND id_proceso_rtp='1'  ORDER BY id_rpt_rtp ASC",$colname_tiempoMuerto);
$tiempoPreparacion  = mysql_query($query_tiempoPreparacion , $conexion1) or die(mysql_error());
$row_tiempoPreparacion  = mysql_fetch_assoc($tiempoPreparacion );
$totalRows_tiempoPreparacion  = mysql_num_rows($tiempoPreparacion );
//CARGA LOS TIEMPOS  DESPERDICIOS
mysql_select_db($database_conexion1, $conexion1);
$query_desperdicio = sprintf("SELECT * FROM Tbl_reg_desperdicio WHERE op_rd=%s AND id_proceso_rd='1' ORDER BY id_rpd_rd ASC",$colname_tiempoMuerto);// AND fecha_rd='$fechaR'
$desperdicio = mysql_query($query_desperdicio, $conexion1) or die(mysql_error());
$row_desperdicio = mysql_fetch_assoc($desperdicio);
$totalRows_desperdicio = mysql_num_rows($desperdicio);
//CARGA LOS KILOS PRODUCIDOS
// AND fecha_rkp BETWEEN '$fechaR' AND '$fechaF'
 
mysql_select_db($database_conexion1, $conexion1);
$query_producido = sprintf("SELECT * FROM Tbl_reg_kilo_producido WHERE op_rp='%s' AND id_proceso_rkp='1' ORDER BY id_rpp_rp ASC",$colname_tiempoMuerto);
$producido = mysql_query($query_producido, $conexion1) or die(mysql_error());
$row_producido = mysql_fetch_assoc($producido);
$totalRows_producido = mysql_num_rows($producido);

$tienekilosconsumo = $conexion->llenarCampos("tbl_reg_kilo_producido", "WHERE op_rp='".$_GET['id_op']."' AND fecha_rkp BETWEEN '$fechaR' AND '$fechaF'  AND id_proceso_rkp='1' ", " ", "SUM(valor_prod_rp) AS totalconsumo ");

 $totalconsumo_parcial=0;
 if($Rollo_parcial > 1){
  
  $producido_parcial = $conexion->llenarCampos("tbl_reg_kilo_producido", "WHERE op_rp='".$_GET['id_op']."' AND fecha_rkp BETWEEN '$fechaR' AND '$fechaF' AND id_proceso_rkp='1' ", " ", "SUM(valor_prod_rp) AS totalconsumo_parcial ");
 

  $totalconsumo_parcial= $producido_parcial['totalconsumo_parcial'] ;
 } 
 
//SELECT TIEMPOS DESPERDICIO TIPO DESPERDICIO 3
mysql_select_db($database_conexion1, $conexion1);
$query_tipo_insumo = "SELECT * FROM insumo WHERE clase_insumo='4' GROUP BY id_insumo ASC";
$tipo_insumo= mysql_query($query_tipo_insumo, $conexion1) or die(mysql_error());
$row_tipo_insumo= mysql_fetch_assoc($tipo_insumo);
$totalRows_tipo_insumo = mysql_num_rows($tipo_insumo);

$colname_ref = "-1";
 if (isset($_GET['id_op'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_orden_produccion, Tbl_referencia WHERE id_op=%s AND int_cod_ref_op=Tbl_referencia.cod_ref ",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
//SUMA TOTAL DE KILOS EXTRUIDOS POR O.P
/*$colname_totalKilos= "-1";
if (isset($_GET['id_op'])) {
  $colname_totalKilos = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_totalKilos = sprintf("SELECT * FROM Tbl_reg_produccion WHERE id_op_rp='%s' AND id_proceso_rp='1'",$colname_totalKilos);
$totalKilos = mysql_query($query_totalKilos, $conexion1) or die(mysql_error());
$row_totalKilos = mysql_fetch_assoc($totalKilos);
$totalRows_totalKilos = mysql_num_rows($totalKilos);*/
//ORDEN DE PRODUCCION TRAE KILOS TOTALES
 
/*mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado WHERE tipo_empleado='4' AND estado_empleado= ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);*/
//ORDEN DE PRODUCCION
$colname_op= "-1";
if (isset($_GET['id_op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = sprintf("SELECT * FROM Tbl_orden_produccion WHERE id_op='%s' AND b_borrado_op='0' ORDER BY id_op DESC",$colname_op);
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='1' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);



$row_codigo_empleado = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(4) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
  
$row_revisor = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(4) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');

?>

        <?php
//KILOS ROLLO 
// AND fechaI_r BETWEEN '$fechaR' AND '$fechaF' AND fechaF_r  BETWEEN '$fechaR' AND '$fechaF' 
        $id_op=$row_rp_edit['id_op_rp'];
        mysql_select_db($database_conexion1, $conexion1);
        $query_sql = "SELECT SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$id_op'  AND  fechaI_r  >= '$fechaR' AND  fechaF_r <= '$fechaF'";
        $res = mysql_query($query_sql, $conexion1) or die(mysql_error()); 
        if($inf = mysql_fetch_array($res)){ 
         $kilosDRollos=dosDecimalesSinMiles($inf["kilos"]);
       }
//DESPERDICIO 
 //si es una liquidacion parcial
  

   $desperdicio_parcial = $conexion->llenarCampos("Tbl_reg_desperdicio", "WHERE op_rd='".$_GET['id_op']."' AND fecha_rd BETWEEN '$fechaR' AND '$fechaF' AND id_proceso_rd='1' ", " ", "SUM(valor_desp_rd) AS desperdicio ");
  $kilosDesper=dosDecimalesSinMiles($desperdicio_parcial['desperdicio']); 

      /* mysql_select_db($database_conexion1, $conexion1);
       $query_sql_D = "SELECT SUM(valor_desp_rd) AS desperdicio FROM Tbl_reg_desperdicio WHERE op_rd='$id_op' AND id_proceso_rd='1' AND fecha_rd='$fechaR'  ";// AND fecha_rd='$fechaR'  es importante para los parciales para q no traiga desperdicios del anterior parcial
       $res_D = mysql_query($query_sql_D, $conexion1) or die(mysql_error());  
       if($inf_D = mysql_fetch_array($res_D)){ 
         $kilosDesper=dosDecimalesSinMiles($inf_D["desperdicio"]); 
       }*/
 
         ?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<link rel="stylesheet" type="text/css" href="css/general.css"/>
<script type="text/javascript" src="AjaxControllers/js/envioForms.js"></script> 
<!-- <script type="text/javascript" src="AjaxControllers/js/envioListado.js"></script> -->
<script type="text/javascript" src="AjaxControllers/js/deleteGeneral.js"></script>

<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<!-- jquery -->
<script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

<!-- jQuery -->
<script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>
 

</head>
<body onload="getSumT();kilosxHora2();nobackbutton();">
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table ><!-- id="tabla1" -->
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                </div>
                <!-- <div class="panel-heading" align="left" ></div> --><!--color azul-->
                <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <?php if( ($tienekilosconsumo['totalconsumo']!='') && ($tienekilosconsumo['totalconsumo']==$kilosDRollos+$kilosDesper) ):  ?>
                    
                    <li><?php echo $_SESSION['Usuario']; ?></li>
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <?php else: ?>
                      <li><?php echo $_SESSION['Usuario']; ?></li>  
                      <li><a href="#" alt="NO HA GUARDADO CONSUMO" title="NO HA GUARDADO CONSUMO" onClick="sinconsumo('id_salirExt','<?php echo $row_rp_edit['id_rp'];?>','produccion_registro_extrusion_listado.php','','Si se sale sin guardar el consumo, se perdera toda la información ! Id: ',eliminacionAlSalirPopUp)">CERRAR SESION</a></li>
                      <li><a href="#" alt="NO HA GUARDADO CONSUMO" title="NO HA GUARDADO CONSUMO" onClick="sinconsumo('id_salirExt','<?php echo $row_rp_edit['id_rp'];?>','produccion_registro_extrusion_listado.php','','Si se sale sin guardar el consumo, se perdera toda la información ! Id: ',eliminacionAlSalirPopUp)">MENU PRINCIPAL</a></li>
                    <?php endif; ?> 
                  </ul>
                </div> 
                <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12"> 
                    </div>
                  </div>
                  <div align="center">
  <form action="view_index.php?c=cextruder&a=Guardar" method="POST" enctype="multipart/form-data" name="form1" id="form1"  ><!-- onSubmit="return(validaCampos())" onSubmit="enviodeForms(validaTodo())"-->
    <table class="table table-bordered table-sm">
      <tr id="tr1">
        <td colspan="10" id="titulo2">REGISTRO DE EXTRUSION</td>
        </tr>
      <tr>
        <td width="137" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
        <td colspan="8" id="dato3">
       <?php if( ($tienekilosconsumo['totalconsumo']!='') && ($tienekilosconsumo['totalconsumo']==$kilosDRollos+$kilosDesper) ):  ?>
        <a href="javascript:eliminar1('id_rel',<?php echo $row_rp_edit['id_rp']; ?>,'produccion_registro_extrusion_edit.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a>
        <a href="produccion_registro_extrusion_vista.php?id_op_rp=<?php echo $row_rp_edit['id_op_rp']; ?>&id_rp=<?php echo $row_rp_edit['id_rp']; ?>&amp;tipo=<?php echo $row_usuario['tipo_usuario']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL"title="MENU PRINCIPAL" border="0"/></a>
        <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0"style="cursor:hand;"/></a>
        <?php else: ?>
           <img onClick="sinconsumo('id_salirExt','<?php echo $row_rp_edit['id_rp'];?>','produccion_registro_extrusion_listado.php','','Si se sale sin guardar el consumo, se perdera toda la información ! Id: ',eliminacionAlSalirPopUp)" src="images/por.gif" alt="NO HA GUARDADO CONSUMO" title="NO HA GUARDADO CONSUMO" border="0" style="cursor:hand;"/> 
           <img onClick="sinconsumo('id_salirExt','<?php echo $row_rp_edit['id_rp'];?>','produccion_registro_extrusion_listado.php','','Si se sale sin guardar el consumo, se perdera toda la información ! Id: ',eliminacionAlSalirPopUp)" src="images/hoja.gif" alt="NO HA GUARDADO CONSUMO" title="NO HA GUARDADO CONSUMO" border="0" /> 
           <img onClick="sinconsumo('id_salirExt','<?php echo $row_rp_edit['id_rp'];?>','produccion_registro_extrusion_listado.php','','Si se sale sin guardar el consumo, se perdera toda la información ! Id: ',eliminacionAlSalirPopUp)" src="images/identico.gif" alt="NO HA GUARDADO CONSUMO" title="NO HA GUARDADO CONSUMO" style="cursor:hand;" border="0"/> 
           <img onClick="sinconsumo('id_salirExt','<?php echo $row_rp_edit['id_rp'];?>','produccion_registro_extrusion_listado.php','','Si se sale sin guardar el consumo, se perdera toda la información ! Id: ',eliminacionAlSalirPopUp)" src="images/ciclo1.gif" alt="NO HA GUARDADO CONSUMO" title="NO HA GUARDADO CONSUMO" border="0"style="cursor:hand;"/> 
      <?php endif; ?>
      </td>
        </tr>
      <tr id="tr1">
        <td width="182" colspan="2" nowrap="nowrap" id="dato2">ORDEN DE PRODUCCION</td>
        <td colspan="6" id="dato3">
          
          Ingresado por
          <input name="str_responsable_rp" type="hidden" id="str_responsable_rp" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/>
          <input name="str_responsable_rp2" type="text" id="str_responsable_rp2" value="<?php echo $row_rp_edit['str_responsable_rp']; ?>" size="27" readonly="readonly"/></td>
        </tr>
      <tr id="tr3">
        <td colspan="2" nowrap="nowrap" id="numero2"><input name="id_op_rp" type="hidden" value="<?php echo $row_rp_edit['id_op_rp']; ?>" />
          <?php echo $row_rp_edit['id_op_rp'];?></td>
        <td width="126" colspan="2" nowrap="nowrap" id="numero2"><input name="id_rp" type="hidden" value="<?php echo $row_rp_edit['id_rp']; ?>" /></td>
        <td width="235" colspan="4" id="fuente2">&nbsp;</td>
      </tr>
      <tr id="tr1">
        <td colspan="2" nowrap="nowrap" id="dato2">REFERENCIA</td>
        <td colspan="6" id="dato2"><output  onforminput="value=weight.value">VERSION</output></td>
        </tr>
      <tr>
        <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_rp_edit['int_cod_ref_rp'];?></td>
        <td colspan="6" nowrap="nowrap" id="numero2"><?php echo $row_rp_edit['version_ref_rp'];?></td>
        </tr>
      <tr>
        <td colspan="2" id="dato2">&nbsp;</td>
        <td colspan="6" id="dato2">&nbsp;</td>
        </tr>
      <tr id="tr1">
        <td colspan="10" id="titulo4">DETALLE          </td>
        </tr>
      <tr >
        <td colspan="2" id="fuente1"><em>Kilos Brutos: </em></td>
        <td id="fuente1">
         <?php echo $kilosDRollos; ?>
          <input type="number" name="int_kilos_prod_rp" id="int_kilos_prod_rp" min="0"step="any"size="12" required="required" value="<?php echo  $kilosDRollos+$kilosDesper;/*$row_rp_edit['int_kilos_prod_rp'];*/?>" style="width:80px" autofocus="autofocus" onchange="kilosxHora2();" readonly="readonly">
          <input name="kilos_op" type="hidden" id="kilos_op" value="<?php echo $row_orden_produccion['int_kilos_op']; ?>" ><!--onclick="getSumP();"-->
        </td>
        <td id="fuente1">Total Desp.</td>
        <td id="fuente1">

        <input type="number" step="0.01" name="int_kilos_desp_rp" id="int_kilos_desp_rp" min="0" size="12" required="required" style="width:80px"  value="<?php echo $kilosDesper; ?>" readonly="readonly"/><!--onclick="getSumD();"--></td>
        <td nowrap="nowrap" colspan="2" id="fuente1"><em>Kilos de Rollos</em>:</td>
        <td colspan="3" id="fuente1"><input name="int_total_kilos_rp" type="number" id="int_total_kilos_rp" style="width:80px" min="0"step="any" onclick="getSumT();" value="<?php  //echo $kilosDRollos; ?>"size="12"  readonly="readonly"/></td>
        </tr>
      <tr >
        <td colspan="10" id="fuente1"><strong>Nota:</strong>Si elimina la liquidacion, se eliminara tambien los desperdicios y tiempos muertos</td>
        </tr>
      <tr >
        <td colspan="2" id="fuente5">&nbsp;</td>
        <td id="fuente5">&nbsp;</td>
        <td id="fuente5">&nbsp;</td>
        <td id="fuente5">&nbsp;</td>
        <td nowrap="nowrap" colspan="2" id="fuente5">&nbsp;</td>
        <td colspan="3" id="fuente5">&nbsp;</td>
      </tr>
<tr id="tr1">
      <td colspan="14" id="titulo4">CONSUMOS</td>
      </tr>
    <tr>
      <td colspan="14" id="fuente1"><a href="javascript:verFoto('produccion_registro_extrusion_detalle_add.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp']?>','820','270')">
        </a><a href="javascript:verFoto('produccion_regist_extru_kilos_prod.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp']?>','1100','740')">
        <input type="button" name="check_sh2" id="check_sh3" class="botonGeneral" value="Detalle Kilos Producidos"/>
        </a>
        <a href="javascript:verFoto('produccion_registro_extrusion_detalle_add.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp']?>','820','270')"></a>

        <strong id="verAlert" style=" display:none; color:red;" > Es liquidación parcial ! continue...</strong>
        <!-- <input type="button" name="Tiempos Desperdicio" id="check_sh1"class="botonGeneral" value="Tiempos Desperdicio"/>
        </a>        
        <input type="button" name="check_sh" id="check_sh2" value="Mostrar" onclick="mostrardiv1()"/>
        <input type="button" value="Ocultar" onclick="ocultardiv1()" /> -->
      </td>
      </tr>
<tr>
      <td colspan="16" id="fuente2">&nbsp;</td>
      </tr>              
      <tr>
        <td colspan="10" id="fuente1">
          <?php $TMP=0; ?>
  <fieldset> <legend>Registro de Tiempos y Desperdicios</legend>
<table width="100%"  border="0" id="flotante">
<?php if($row_tiempoMuerto['id_rpt_rt']!='') {?>
    <tr>
      <td nowrap id="detalle2"><strong>Tiempos Muertos- Tipo</strong></td>
      <td nowrap id="detalle2"><strong>Tiempos Muertos- Minutos</strong></td>
      <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      
    </tr>
        <?php  for ($x=0;$x<=$totalRows_tiempoMuerto-1;$x++){ ?>
         <tr>
          <td id="fuente1">
            <?php $id1=mysql_result($tiempoMuerto,$x,id_rpt_rt); 
              $id_tm=$id1;
              $sqltm="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_tm'";
              $resulttm= mysql_query($sqltm);
              $numtm= mysql_num_rows($resulttm);
              if($numtm >='1')
              { 
              $nombreM = mysql_result($resulttm, 0, 'nombre_rtp');
			  echo $nombreM; }?>
          
        </td>
          <td id="fuente1"><?php $var=mysql_result($tiempoMuerto,$x,valor_tiem_rt); echo $var; $TM=$TM+$var;?></td>
          <td id="fuente1"><a href="javascript:eliminar_rte('id_rte',<?php $delrt=mysql_result($tiempoMuerto,$x,id_rt); echo $delrt; ?>,'produccion_registro_extrusion_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0"></a>
      </td>
          </tr>
          <?php }?>
          <tr>
          <td id="fuente3">TOTAL</td>
          <td id="fuente1"><strong><?php echo $TM;?></strong></td>
          <td id="fuente3">&nbsp;</td>
          </tr>
           <?php } ?>
           <?php if($row_tiempoPreparacion['id_rpt_rtp']!='') {?>
           
    <tr>
      <td nowrap id="detalle2"><strong>Tiempos Preparacion - Tipo</strong></td>
      <td nowrap id="detalle2"><strong>Tiempos Preparacion - Minutos</strong></td>
      <td nowrap id="detalle2"><strong>ELIMINA</strong></td>
      
    </tr>           
     <?php for ($o=0;$o<=$totalRows_tiempoPreparacion-1;$o++){ ?>
    <tr>
     <td id="fuente1">
      <?php $id2=mysql_result($tiempoPreparacion,$o,id_rpt_rtp); 
     	  $id_rtp = $id2;
     	  $sqlrtp="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rtp'";
     	  $resultrtp= mysql_query($sqlrtp);
     	  $numrtp= mysql_num_rows($resultrtp);
     	  if($numrtp >='1')
     	  { 
     	  $nombreP = mysql_result($resultrtp, 0, 'nombre_rtp');echo $nombreP; } ?>
      
    </td>
          <td id="fuente1"> <?php $var2=mysql_result($tiempoPreparacion,$o,valor_prep_rtp); echo $var2; $TP+=$var2;?></td>
          <td id="fuente1"><a href="javascript:eliminar_rte('id_rpe',<?php $delrp=mysql_result($tiempoPreparacion,$o,id_rt); echo $delrp; ?>,'produccion_registro_extrusion_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0"></a></td>
          </tr>
          <?php }?>
          <tr>
          <td id="fuente3">TOTAL</td>
          <td id="fuente1"><strong><?php echo $TP; ?></strong></td>
          <td id="fuente3">&nbsp;</td>
          </tr>
		  <?php }?>
          <?php if($row_desperdicio['id_rpd_rd']!='') {?>
      <tr> 
      <td nowrap id="detalle2"><strong>Desperdicios - Tipo</strong></td>
      <td nowrap id="detalle2"><strong>Desperdicios - Kilos</strong></td>
      <td nowrap id="detalle2"><strong>ELIMINA</strong></td>      
      </tr>          
          <?php  for ($m=0;$m<=$totalRows_desperdicio-1;$m++){ ?> 
        <tr>
          <td id="fuente1">
          <?php  
				  $id3=mysql_result($desperdicio,$m,id_rpd_rd); 
				  $id_rpd = $id3;
				  $sqlrtd="SELECT nombre_rtp FROM Tbl_reg_tipo_desperdicio WHERE id_rtp='$id_rpd'";
				  $resultrtd= mysql_query($sqlrtd);
				  $numrtd= mysql_num_rows($resultrtd);
				  if($numrtd >='1')
				  { 
				  $nombreD = mysql_result($resultrtd, 0, 'nombre_rtp'); 
				  echo $nombreD;
           }
          ?>
          </td>
          <td id="fuente1"><?php $var3=mysql_result($desperdicio,$m,valor_desp_rd); echo $var3; $TD=$TD+$var3; ?></td>
         <td id="fuente1"><a href="javascript:eliminar_rte('id_rde',<?php $delrd=mysql_result($desperdicio,$m,id_rd); echo $delrd; ?>,'produccion_registro_extrusion_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0"></a></td>
         </tr> 
		      <?php } ?>   
          <tr>
          <td id="fuente3">TOTAL</td>
          <td id="fuente1"><strong><?php echo $TD; ?></strong></td>
          <td id="fuente3">&nbsp;</td>
          </tr>
		  <?php }?>
         
 
          <?php if($row_producido['id_rpp_rp']!='') { ?>
            <tr> 
              <td nowrap id="detalle2"><strong>Materia Prima - Tipo</strong></td>
              <td nowrap id="detalle2"><strong>Materia Prima - Kilos</strong></td>
              <td nowrap id="detalle2"><strong>ELIMINA</strong></td>      
            </tr>      
          <?php for ($z=0;$z<=$totalRows_producido-1;$z++){ ?> 
         <tr>
          <td id="fuente1">
            <?php  
   				  $id4=mysql_result($producido,$z,id_rpp_rp);
   				  $id_mp = $id4; 

   				  $sqlrmp="SELECT descripcion_insumo FROM insumo WHERE id_insumo='$id_mp'";
   				  $resultrmp= mysql_query($sqlrmp);
   				  $numrmp = mysql_num_rows($resultrmp);
   				  if($numrmp >='1')
   				  { 
   				  $nombreMP = mysql_result($resultrmp, 0, 'descripcion_insumo'); 
   				   echo $nombreMP; }
            ?>
          </td>
         <td id="fuente1">
          <?php 

             $var4=mysql_result($producido,$z,valor_prod_rp); 
             echo $var4; $TMP=$TMP+$var4; ?>
            <strong>
               <input name="valor_prod_rp[]" type="hidden" step="0.01" min="0" value="<?php  echo $var4; ?>" />
            </strong></td>
         <td id="fuente1">
          <?php if($_SESSION['superacceso']): ?>
          <a href="javascript:eliminar_rte('id_ipe',<?php $delip=mysql_result($producido,$z,id_rkp); echo $delip; ?>,'produccion_registro_extrusion_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0"></a>
          <?php else: ?>
            <a href="javascript:noEliminar('id','produccion_registro_extrusion_edit.php')"><img src="images/masazul.PNG" style="cursor:hand;" alt="ELIMINAR " title="ELIMINAR" border="0"></a>
        <?php endif; ?>
        </td>
         </tr> 
		 <?php }?>  
          <tr>
          <td id="fuente3">TOTAL</td>
          <td id="fuente1"><strong><?php echo $TMP;?></strong></td>
          <td id="fuente3">&nbsp;</td>
          </tr>
    <?php }?>
    <tr>
      <td>
        <?php if($totalconsumo_parcial=='0' || $totalconsumo_parcial==''){ $totalconsumo_parcial=0;} ?> 
      <input name="materiaPrima" id="materiaPrima" style="width:60px" type="hidden" value="<?php  echo $TMP=$totalconsumo_parcial=='0' ? $TMP : $totalconsumo_parcial; ?>" readonly='readonly' />
      <?php if( $totalconsumo_parcial > 0) 
         {
            echo "Kilos Parciales: ".$totalconsumo_parcial;
         }
       ?>
      </td> 
    </tr>       
    </table>
    </fieldset>        
        </td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="4" id="dato1"></td>
      </tr>
      <tr>
        <td colspan="10" id="detalle2"><a href="javascript:verFoto('produccion_regist_extru_kilos_prod_edit.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>&rollo=<?php echo $row_rp_edit['rollo_rp']?>&amp;fecha=<?php echo $row_rp_edit['fecha_ini_rp'] ?>&amp;id_ref=<?php echo $row_rp_edit['id_ref_rp'] ?>','1100','640')"> Editar los valores de las unidades ingresadas</a></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">Maquina</td>
        <td colspan="2" id="fuente1">
          <select name="str_maquina_rp" id="str_maquina_rp" >
          <?php
           do {  
           ?>
                     <option value="<?php echo $row_maquinas['id_maquina']?>"<?php if (!(strcmp($row_maquinas['id_maquina'], $row_rp_edit['str_maquina_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_maquinas['nombre_maquina']?></option>
                     <?php
           } while ($row_maquinas = mysql_fetch_assoc($maquinas));
             $rows = mysql_num_rows($maquinas);
             if($rows > 0) {
                 mysql_data_seek($maquinas, 0);
           	  $row_maquinas = mysql_fetch_assoc($maquinas);
             }
           ?>
        </select>
          <strong>
          <input name="valor_tiem_rt" id="horasmuertas" type="hidden" size="6" value="<?php if($TM==''){echo 0;}else{echo $TM;} ?>"/>
          <input name="valor_prep_rtp" id="horasprep" type="hidden" size="6" value="<?php if($TP==''){echo 0;}else{echo $TP;} ?>"/>
          </strong></td>
        <td colspan="6" id="fuente1">
          <select name="int_cod_empleado_rp" id="montaje" style="width:120px">
          <option value=""<?php if (!(strcmp("", $row_rp_edit['int_cod_empleado_rp']))) {echo "selected=\"selected\"";} ?>>Montaje</option>
              <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
                <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rp_edit['int_cod_empleado_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
              <?php } ?>
            </select>  
        -
        <select name="int_cod_liquida_rp" id="liquida" style="width:120px" >
          <option value=""<?php if (!(strcmp("", $row_rp_edit['int_cod_liquida_rp']))) {echo "selected=\"selected\"";} ?> >Liquida</option>
            <?php  foreach($row_revisor as $row_revisor ) { ?>
              <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_revisor['codigo_empleado'], $row_rp_edit['int_cod_liquida_rp']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['codigo_empleado']." - ".$row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado']?></option>
            <?php } ?>
          </select>

 
         </td>
      </tr>           
      <tr>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="2" id="dato1"></td>
        <td colspan="4" id="dato1"></td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">Fecha Inicial</td>
        <td colspan="2" id="fuente1"><input name="fecha_ini_rp" id="fecha_ini_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rp_edit['fecha_ini_rp']); ?>" onchange="kilosxHora2();" readonly="readonly"/></td>
       
        <td colspan="6" id="fuente1"><input name="int_metro_lineal_rp" type="number" id="metro_r" placeholder="Metro Lineal" style="width:116px" min="0"step="any"  onclick="" value="<?php echo $row_rp_edit['int_metro_lineal_rp']; ?>"/>
        <input name="metro_op" type="hidden" id="metro_op" value="<?php echo $row_orden_produccion['metroLineal_op']; ?>" >
Metro lineal  de O.P:<?php echo $row_orden_produccion['metroLineal_op']; ?></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1">Fecha Final</td>
        <td colspan="2" id="fuente1"><input name="fecha_fin_rp" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rp_edit['fecha_fin_rp']);?>" onchange="kilosxHora2();" required readonly="readonly"/></td>
        <td colspan="6" id="fuente1"><input name="int_total_rollos_rp" type="number" required="required" readonly="readonly" id="int_total_rollos_rp" placeholder="Total Rollos" style="width:116px" min="0"step="any"  onclick="" value="<?php echo $row_rp_edit['int_total_rollos_rp']; ?>" />
Total Rollos</td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Total Horas Trabajadas</td>
        <td colspan="2" id="fuente1"> 
        <input name="total_horas_rp" type="text" id="horas_real" value="<?php echo $row_rp_edit['total_horas_rp']; ?>"size="19"  required="required" onBlur="kilosxHora2();"/></td>
        <td colspan="6" id="fuente1"><input type="number" name="int_kilosxhora_rp" id="int_kilosxhora_rp" min="1" step="any" style="width:116px" onBlur="kilosxHora2();" value="<?php echo $row_rp_edit['int_kilosxhora_rp']; ?>" required="required"/>
          Kilos  x Hora</td>
        </tr>
         <tr id="tr1">
        <td colspan="2" id="fuente1">Porcentaje  O.P</td>
        <td colspan="2" id="fuente1"><input id="porcentaje" name="porcentaje" type="number" value="<?php echo $row_rp_edit['porcentaje_op_rp']; ?>" min="0" max="100" step="1" style="width:40px" required="required" readonly="readonly"/>
        %</td>
        <td colspan="6" id="fuente1"><input type="text" name="tiempoOptimo_rp" id="tiempoOptimo_rp" value="<?php echo $row_rp_edit['rodamiento_rp']; ?>" size="12"  required="required"/> <!--onclick="kilosxHora2();"-->
          Tiempo Optimo</td>
        </tr>
      <tr>
        <td colspan="4" id="fuente1"><?php 
 	  $id_op=$row_rp_edit['id_op_rp'];
  	  //COSTO DE LA O.P EN EXTRUDER
	  $sqlexh="SELECT total_horas_rp AS horasT FROM Tbl_reg_produccion WHERE id_op_rp='$id_op' AND id_proceso_rp = '1'"; 
	  $resultexh=mysql_query($sqlexh); 
	  $numexh=mysql_num_rows($resultexh); 
	  if($numexh >= '1') 
	  { $tHoras_ex=mysql_result($resultexh,0,'horasT'); 
		$horas_ext = horadecimalUna($tHoras_ex); 	}  
	  
 	  $sqlcostoMP="SELECT Tbl_reg_kilo_producido.id_rpp_rp, insumo.valor_unitario_insumo AS VALORKILO, Tbl_reg_kilo_producido.valor_prod_rp AS CANTKILOS FROM insumo,Tbl_reg_kilo_producido WHERE insumo.id_insumo = Tbl_reg_kilo_producido.id_rpp_rp AND Tbl_reg_kilo_producido.id_proceso_rkp='1' AND Tbl_reg_kilo_producido.op_rp = '$id_op'"; 
	  $resultcostoMP=mysql_query($sqlcostoMP); 
	  $numcostoMP=mysql_num_rows($resultcostoMP); 
	  $row_valoresMP = mysql_fetch_assoc($resultcostoMP);
	  $contValor=0;
	  $contCant=0;
	  do{
		  $valorMP = $row_valoresMP['VALORKILO'];
		  $KilosMP = $row_valoresMP['CANTKILOS'];//TODOS LOS KILOS REGISTRADOS CON DESPERDICIOS
          $valorItem=$valorMP*$KilosMP;//cada item cuanto vale un kilo
	      $contValor+=$valorItem;//ACUMULA VALOR POR ITEM
		  $contCant+=$KilosMP;//ACUMULA CANTIDAD POR ITEM
    } while ($row_valoresMP = mysql_fetch_assoc($resultcostoMP));
	      $contValor=$contValor;//DATO PARA EL CAMPO COSTO MP
	      $kiloMPEXT = ($contValor); //COSTO KILO DE MP   
  	  //GASTOS GENERALES
 	  $sqlextru="SELECT COUNT(DISTINCT rollo_r) AS rollos, DATE_FORMAT(fechaI_r,'%Y-%m-%d') AS FECHA, SUM(metro_r) AS metros,SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$id_op' ORDER BY fechaI_r ASC"; 
	  $resultextru=mysql_query($sqlextru); 
	  $numextru=mysql_num_rows($resultextru); 
	  if($numextru >= '1') 
	  { 
  		$FECHA_NOVEDAD_EXT=mysql_result($resultextru,0,'FECHA');
 	  } 
   	  $sqlgeneral="SELECT * FROM `TblDistribucionHoras` ORDER BY `fecha` DESC LIMIT 1";//ORDER BY fecha DESC
	  $resultgeneral= mysql_query($sqlgeneral);
	  $numgeneral= mysql_num_rows($resultgeneral);
 	  //PARA TODOS LOS PROCESOS
	  if($numgeneral >='1')
	  { 
	  $TiempomeExt =  mysql_result($resultgeneral, 0, 'extrusion');
      //EXTRUDER
	  $costoUnHGga_ext = mysql_result($resultgeneral, 0, 'gga_ext');
	  $costoUnHCif_ext = mysql_result($resultgeneral, 0, 'cif_ext');
	  $costoUnHGgv_ext = mysql_result($resultgeneral, 0, 'ggv_ext');
	  $costoUnHGgf_ext = mysql_result($resultgeneral, 0, 'ggf_ext');
 	  $cifyggaExt=($costoUnHGga_ext+$costoUnHCif_ext+$costoUnHGgv_ext+$costoUnHGgf_ext);
	  }else{$TiempomeExt='0';} 

	//SUELDOS DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
	$sqlbasicoExt="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado NOT IN(4,5,6,7,8,9,10)";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos SE AGREGO b.estado_empleado='1' AND
	$resultbasicoExt=mysql_query($sqlbasicoExt);
    $operario_ext_demas=mysql_result($resultbasicoExt,0,'operarios');
	$sueldo_basExt=mysql_result($resultbasicoExt,0,'SUELDO'); //sueldos del mes 
	$auxilio_basExt=mysql_result($resultbasicoExt,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_basExt=mysql_result($resultbasicoExt,0,'APORTES'); //aportes del mes 
 	//$horasmes_bas=mysql_result($resultbasico,0,'HORASMES');//LO EQUIVALENTE A LAS HORAS QUE SE TRABAJAN EN UN MES REAL 186,6666667 SE ENCUENTRA EN FACTOR
	$operarios_basExt=mysql_result($resultbasicoExt,0,'operarios');//CANTIDAD DE OPERARIOS 
	$horasdia_basExt=mysql_result($resultbasicoExt,0,'HORADIA');//esto es 8 
	 	 
	//NOVEDAD DE TODOS LOS EMPLEADOS FUERA DE PROCESO 
 	$sqlnovbasicoExt="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado NOT IN(4,5,6,7,8,9,10) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-31')";//NOT IN(4,5,6,7,8,9,10) son los que estan fuera de los procesos
	$resultnovbasicoExt=mysql_query($sqlnovbasicoExt);	
	$pago_novbasicoExt=mysql_result($resultnovbasicoExt,0,'pago'); 
	$extras_novbasicoExt=mysql_result($resultnovbasicoExt,0,'extras');  
	$recargo_novbasicoExt=mysql_result($resultnovbasicoExt,0,'recargo');
	$festivo_novbasicoExt=mysql_result($resultnovbasicoExt,0,'festivos');
	$horasmes_Ext='240';//240 mientras se define horas al mes
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
  	$valorhoraxoperExtDemas = sueldoMes($sueldo_basExt,$auxilio_basExt,$aportes_basExt,$horasmes_Ext,$horasdia_basExt,$recargo_novbasicoExt,$festivo_novbasicoExt); 
	$valorHoraExtDemas  = ($valorhoraxoperExtDemas/$operario_ext_demas)/3;//total H se divide por # de operarios de fuera de los procesos  
  
  	//SUELDOS DE TODOS LOS EMPLEADOS DENTRO DE EXTRUSION 
	$sqlbasicoExt="SELECT COUNT(a.codigo_empleado) AS operarios,(a.horas_empleado) AS HORADIA, SUM(b.sueldo_empleado) AS SUELDO, SUM(b.aux_empleado) AS AUXILIO, SUM(c.total) AS APORTES
FROM empleado a 
LEFT JOIN TblProcesoEmpleado b ON a.codigo_empleado=b.codigo_empleado  
LEFT JOIN TblAportes c ON a.codigo_empleado=c.codigo_empl 
WHERE b.estado_empleado='1' AND a.tipo_empleado IN(4)";//IN(4) son extrusion
	$resultbasicoExt=mysql_query($sqlbasicoExt);
	$operario_Ext=mysql_result($resultbasicoExt,0,'operarios');
	$sueldo_basExt=mysql_result($resultbasicoExt,0,'SUELDO'); //sueldos del mes 
	$auxilio_basExt=mysql_result($resultbasicoExt,0,'AUXILIO'); //auxilios trans del mes 	  
	$aportes_basExt=mysql_result($resultbasicoExt,0,'APORTES'); //aportes del mes 
	$horasdia_basExt=mysql_result($resultbasicoExt,0,'HORADIA');//esto es 8 
	$horasmes_Ext='240';//240 mientras se define horas al mes
	 //FIN	 
	 //NOVEDAD DE ESE MES DE TODOS LOS EMPLEADOS DENTRO DE EXTRUSION 
  	$sqlnovbasicoExt="SELECT SUM(b.pago_acycia) as pago,SUM(b.horas_extras) as extras,SUM(b.recargos) as recargo,SUM(b.festivos) AS festivos 
FROM empleado a 
LEFT JOIN TblNovedades b ON a.codigo_empleado=b.codigo_empleado 
WHERE a.tipo_empleado IN(4) AND b.fecha BETWEEN DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-01') AND DATE_FORMAT('$FECHA_NOVEDAD_EXT', '%Y-%m-31')";//IN(4)novedad extrusion 
	$resultnovbasicoExt=mysql_query($sqlnovbasicoExt);	
	$pago_novbasicoExt=mysql_result($resultnovbasicoExt,0,'pago'); 
	$extras_novbasicoExt=mysql_result($resultnovbasicoExt,0,'extras');  
	$recargo_novbasicoExt=mysql_result($resultnovbasicoExt,0,'recargo');
	$festivo_novbasicoExt=mysql_result($resultnovbasicoExt,0,'festivos');
	//FIN
	//OPERO TODOS LOS SUELDOS ETC, PARA SACAR EL COSTO HORA DE LOS FUERA DE PROCESOS
 	$valorhoraTodosExt = sueldoMes($sueldo_basExt,$auxilio_basExt,$aportes_basExt,$horasmes_Ext,$horasdia_basExt,$recargo_novbasicoExt,$festivo_novbasicoExt);
	$tkilos_ex = $contCant;
	$kiloXHoraExt=($tkilos_ex/$horas_ext);//kilo x hora para los cif y gga
	$valorHoraExt = ($valorhoraTodosExt/$operario_Ext);//total H se divide por # de operarios de extrusion	  
 	$costokiloInsumoExt=($kiloMPEXT/$tkilos_ex);//$ costo de 1 kilos mp
	$manoObraExt=($horas_ext*($valorHoraExt+$valorHoraExtDemas))/$tkilos_ex;//$ costo de 1 kilo mano obra
	$valorkilocifyggaExt=($cifyggaExt/$kiloXHoraExt);//$kiloXHoraExt valor por hora de cif y gga  
 	 $COSTOHORAKILO = ($costokiloInsumoExt+$manoObraExt+$valorkilocifyggaExt);
 	 ?>
    <input name="costo" id="costo" type="hidden" size="6" value="<?php  echo $COSTOHORAKILO;?>" readonly="readonly"/>
      </td>
        <td colspan="6" id="fuente1">&nbsp;</td>
    </tr>        
      <tr id="tr1">
      <td colspan="10" id="titulo4"><strong>RPM - %
       </strong></td>
      </tr>
    <tr>
      <td colspan="2" id="fuente2">TORNILLO A</td>
      <td colspan="2" id="fuente2">TORNILLO B</td>
      <td colspan="2" id="fuente2">TORNILLO C</td>
      <td colspan="4" id="fuente3"><input type="button" class="botonGeneral" onclick="enviodeFormulario()" name="idGuardar" id="idGuardar" value="GUARDAR"/></td>
      </tr>
    <tr>
      <td id="fuente1">RPM</td>
      <td id="fuente1">%</td>
      <td id="fuente1">RPM</td>
      <td id="fuente1">%</td>
      <td id="fuente1">RPM</td>
      <td id="fuente1">%</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
    <tr>
      <td id="fuente1"><input type="text" name="int_ref1_rpm_pm" id="int_ref1_rpm_pm" min="0"step="any"size="7" required="required" value="<?php echo $row_mezcla['int_ref1_rpm_pm']; ?>" /></td>
      <td id="fuente1"><input name="int_ref1_tol5_porc1_pm"  type="text"  id="int_ref1_tol5_porc1_pm" placeholder="%" size="2" required="required" value="<?php echo $row_mezcla['int_ref1_tol5_porc1_pm'] ?>"/></td>
      <td id="fuente1"><input type="text" name="int_ref2_rpm_pm" id="int_ref2_rpm_pm" min="0"step="any"size="7" required="required" value="<?php echo $row_mezcla['int_ref2_rpm_pm']; ?>" /></td>
      <td id="fuente1"><input name="int_ref2_tol5_porc2_pm"  type="text"  id="int_ref2_tol5_porc2_pm" placeholder="%" size="2" required="required" value="<?php echo $row_mezcla['int_ref2_tol5_porc2_pm'] ?>"/></td>
      <td id="fuente1"><input type="text" name="int_ref3_rpm_pm" id="int_ref3_rpm_pm" min="0"step="any"size="7" required="required" value="<?php echo $row_mezcla['int_ref3_rpm_pm']; ?>"/></td>
      <td id="fuente1"><input name="int_ref3_tol5_porc3_pm"  type="text"  id="int_ref3_tol5_porc3_pm" placeholder="%" size="2" required="required" value="<?php echo $row_mezcla['int_ref3_tol5_porc3_pm'] ?>"/>
        <input name="id_pm" type="hidden" id="id_pm" value="<?php echo $row_mezcla['id_pm']; ?>" /></td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="2" id="fuente1">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="14" id="fuente4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="14" id="fuente1">&nbsp;</td>
    </tr>   
      
    </tr>           
      <tr id="tr1">
        <td colspan="10" id="dato2"><input name="id_proceso_rp" type="hidden" id="id_proceso_rp" value="1" />          
          <input name="int_cod_ref_rp" type="hidden" id="int_cod_ref_rp" value="<?php echo $row_rp_edit['int_cod_ref_rp']; ?>" />
          <input name="version_ref_rp" type="hidden" id="version_ref_rp" value="<?php echo $row_rp_edit['version_ref_rp']; ?>" />
          <input name="id_ref_rp" type="hidden" id="id_ref_rp" value="<?php echo $row_rp_edit['id_ref_rp']; ?>" />
          <input type="hidden" name="rollo_rp" id="rollo_rp" value="<?php echo $row_rp_edit['rollo_rp']; ?>" />
          <input type="hidden" name="parcial" id="parcial" value="<?php echo $row_rp_edit['parcial']; ?>" />
        <input name="estado" id="estado" type="hidden" value="<?php echo $row_orden_produccion['b_estado_op']; ?>" />
      </td>
      </tr>
  </table>
    <input type="hidden" name="MM_insert" value="MM_update" />
    </form>
<?php echo $conexion->header('footer'); ?>

<div id="content"></div> <!-- este bloquea pantalla evitando duplicidad -->
</body>
</html>
<script type="text/javascript">

  //evita q se vaya el form con enter
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
      if(e.keyCode == 13) {
        e.preventDefault();
      }
    }))
  });//fin

  kilosxHora2();
 
 
   $(document).ready(function(){

   
    

            var consumos_parcial = "<?php echo $totalconsumo_parcial;?>"; 
            var kilosT = $("#int_kilos_prod_rp").val(); 
            var MateriasT = $("#materiaPrima").val() ; //'<?php echo $TMP ;?>';
        
            if(consumos_parcial > '0'){
                swal("Alert", "Es liquidación Parcial  ! continue... :)", "warning"); 
                $("#verAlert").show(); 
                setTimeout(function() { $("#verAlert").fadeOut();},4000);
                  
            }

               
            if( kilosT != MateriasT ){  
                 
                       $("#idGuardar").addClass("botonGeneralGris");
                       //$('#idGuardar').prop('disabled', true);
  
                       return false;
            }  
           
           
 
 });
   
   
   function enviodeFormulario(){ 
        var resul =validaTodo();

         enviodeForms(resul);
 
         
   }

/* function enviodeFormulario(){ 
      var form = $("#form1").serialize();
      var vista = 'produccion_registro_extrusion_edit.php';
      var resul =validaTodo();
      if(resul){
         enviovarListados(form,vista);  
      }
 }*/ 

/* function UpdatesExtruder(vid,valores){

   ids='id';//coloque la columna del id a actualizar
   valorid = ''+vid; 
   tabla='tbl_destinatarios';
   url='view_index.php?c=cgeneral&a=Actualizar'; //la envio en campo proceso
 
   actualizapaso(ids,valorid,valores,tabla,url);   

 }*/

 
  
</script>
 <style type="text/css">
.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 3200;
    background: url('images/loadingcircle4.gif') 50% 50% no-repeat rgb(250,250,250);
    background-size: 5% 10%;/*tamaño del gif*/
    -moz-opacity:65;
    opacity:0.65;

}
</style>
<?php
mysql_free_result($usuario);
mysql_free_result($rp_edit);
mysql_free_result($maquinas);
mysql_free_result($mezcla);
mysql_free_result($ref);
mysql_free_result($tiempoMuerto);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($producido);
mysql_free_result($tipo_insumo);
//mysql_free_result($totalKilos);
mysql_free_result($codigo_empleado);
?>
