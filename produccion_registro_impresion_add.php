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
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {	
	//UPDATE MEZCLA DE IMPRESION
	$pmi=($_POST['id_pmi']);
    foreach($pmi as $key=>$v)
    $a[]= $v;
	
	$id=($_POST['id']);
    foreach($id as $key=>$v)
    $b[]= $v;
	
	$valor=($_POST['valor']);
    foreach($valor as $key=>$v)
    $c[]= $v;
		
	for($x=0; $x<count($b); $x++) 
    {
		//if($a[$x]!=''&&$b[$x]!=''&&$c[$x]!=''){			 
  $updateSQL = sprintf("UPDATE Tbl_produccion_mezclas_impresion SET fecha_registro_pmi=%s, str_registro_pmi=%s, id_i_pmi=%s,  str_valor_pmi=%s,  observ_pmi=%s WHERE id_pmi=%s",                      
                       GetSQLValueString($_POST['fecha_registro_pmi'], "date"),
                       GetSQLValueString($_POST['str_registro_pmi'], "text"),
					   GetSQLValueString($b[$x], "text"),
					   GetSQLValueString($c[$x], "text"),
                       GetSQLValueString($_POST['observ_pmi'], "text"),
                       GetSQLValueString($a[$x], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
	}//llave de for    
	//}	
	
	//UPDATE CARACTERISTICAS VALOR
	$id_cv=($_POST['id_cv']);
    foreach($id_cv as $key=>$v)
    $d[]= $v;
	
	$valor_cv=($_POST['valor_cv']);
    foreach($valor_cv as $key=>$v)
    $e[]= $v;
	
	for($x=0; $x<count($e); $x++){	
	if($d[$x]!=''&&$e[$x]!=''){		
  $updateSQL2 = sprintf("UPDATE Tbl_caracteristicas_valor SET str_valor_cv=%s, fecha_registro_cv=%s, str_registro_cv=%s WHERE id_cv=%s",
					   GetSQLValueString($e[$x], "text"),
					   GetSQLValueString($_POST['fecha_registro_pmi'], "date"),
					   GetSQLValueString($_POST['str_registro_pmi'], "text"),
					   GetSQLValueString($d[$x], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());
	   }//llave de for
	}//if
     $insertGoTo = "produccion_registro_impresion_edit.php?id_ref=" . $_POST['id_ref_rp'] . "&fecha_ini_rp=" . $_POST['fecha_ini_rp'] .  "&rollo=" . $_POST['rollo_rp'] ."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
   }
  header(sprintf("Location: %s", $insertGoTo)); 	
}	//FIN SI UPDATE FORM 2	
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $tiemposM='';$tiemposP='';
  $insertSQL3 = sprintf("INSERT INTO Tbl_reg_produccion ( id_proceso_rp, id_op_rp, id_ref_rp, int_cod_ref_rp, version_ref_rp, rollo_rp, int_kilos_prod_rp, int_kilos_desp_rp, int_total_kilos_rp, porcentaje_op_rp, int_metro_lineal_rp, int_total_rollos_rp, total_horas_rp, horas_muertas_rp, horas_prep_rp, str_maquina_rp, str_responsable_rp, fecha_ini_rp, fecha_fin_rp, int_kilosxhora_rp,int_metroxmin_rp,int_cod_empleado_rp,int_cod_liquida_rp) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",                      
                       GetSQLValueString($_POST['id_proceso_rp'], "int"),
                       GetSQLValueString($_POST['id_op_rp'], "int"),
					   GetSQLValueString($_POST['id_ref_rp'], "int"),
                       GetSQLValueString($_POST['int_cod_ref_rp'], "text"),
                       GetSQLValueString($_POST['version_ref_rp'], "int"),
					   GetSQLValueString($_POST['rollo_rp'], "int"),
                       GetSQLValueString($_POST['int_kilos_prod_rp'], "double"),
                       GetSQLValueString($_POST['int_kilos_desp_rp'], "double"),
					   GetSQLValueString($_POST['int_total_kilos_rp'], "double"),
					   GetSQLValueString($_POST['porcentaje'], "int"),
					   GetSQLValueString($_POST['int_metro_lineal_rp'], "int"),
					   GetSQLValueString($_POST['int_total_rollos_rp'], "int"),					   
                       GetSQLValueString($_POST['total_horas_rp'], "text"),
					   GetSQLValueString($tiemposM, "text"), 
					   GetSQLValueString($tiemposP, "text"),              
                       GetSQLValueString($_POST['str_maquina_rp'], "text"),
                       GetSQLValueString($_POST['str_responsable_rp'], "text"),
					   GetSQLValueString($_POST['fecha_ini_rp'], "date"),
                       GetSQLValueString($_POST['fecha_fin_rp'], "date"),
					   GetSQLValueString($_POST['int_kilosxhora_rp'], "double"),
					   GetSQLValueString($_POST['int_metroxmin_rp'], "double"),					   
					   GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
					   GetSQLValueString($_POST['int_cod_liquida_rp'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result3 = mysql_query($insertSQL3, $conexion1) or die(mysql_error());	
	
  $updateSQL4 = sprintf("UPDATE Tbl_orden_produccion SET b_estado_op='2',f_impresion=%s WHERE id_op=%s",
                       GetSQLValueString($_POST['fecha_fin_rp'], "date"),
					   GetSQLValueString($_POST['id_op_rp'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result4 = mysql_query($updateSQL4, $conexion1) or die(mysql_error());	
  
     $insertGoTo = "produccion_registro_impresion_edit.php?id_op=" . $_POST['id_op_rp'] . "&fecha_ini_rp=" . $_POST['fecha_ini_rp'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
 
  header(sprintf("Location: %s", $insertGoTo)); 		 
  }	
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
//ORDEN DE PRODUCCION
$colname_op= "-1";
if (isset($_GET['id_op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = sprintf("SELECT * FROM Tbl_orden_produccion WHERE id_op=%s AND b_borrado_op='0' ORDER BY id_op DESC",$colname_op);
$orden_produccion = mysql_query($query_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);
$totalRows_orden_produccion = mysql_num_rows($orden_produccion);
//MAQUINAS
mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina WHERE proceso_maquina='2' ORDER BY id_maquina DESC";
$maquinas= mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);

//REFERENCIA ENVIO A VISTA
$colname_ref = "-1";
if (isset($_GET['id_op'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_orden_produccion, Tbl_referencia WHERE Tbl_orden_produccion.id_op=%s AND Tbl_orden_produccion.int_cod_ref_op=Tbl_referencia.cod_ref ",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='8' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
//LLAMA LAS UNIDADES DE IMPRESION
$id_ref=$row_ref['id_ref'];
mysql_select_db($database_conexion1, $conexion1);
$query_caract_valor = "SELECT * FROM Tbl_caracteristicas, Tbl_caracteristicas_valor WHERE Tbl_caracteristicas_valor.id_ref_cv='$id_ref' AND Tbl_caracteristicas.id_c=Tbl_caracteristicas_valor.id_c_cv AND Tbl_caracteristicas.proceso_c='2' ORDER BY Tbl_caracteristicas_valor.id_cv ASC";
$caract_valor = mysql_query($query_caract_valor, $conexion1) or die(mysql_error());
$row_caract_valor = mysql_fetch_assoc($caract_valor);
$totalRows_caract_valor = mysql_num_rows($caract_valor);
//LLAMA LAS MEZCLAS DE IMPRESION UNIDAD 1
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_uno = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='1' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ";
$unidad_uno = mysql_query($query_unidad_uno, $conexion1) or die(mysql_error());
$row_unidad_uno = mysql_fetch_assoc($unidad_uno);
$totalRows_unidad_uno = mysql_num_rows($unidad_uno);
//CARGA UNIDAD 2
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_dos = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='2' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ";
$unidad_dos = mysql_query($query_unidad_dos, $conexion1) or die(mysql_error());
$row_unidad_dos = mysql_fetch_assoc($unidad_dos);
$totalRows_unidad_dos = mysql_num_rows($unidad_dos);
//CARGA UNIDAD 3
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_tres = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='3' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ";
$unidad_tres = mysql_query($query_unidad_tres, $conexion1) or die(mysql_error());
$row_unidad_tres = mysql_fetch_assoc($unidad_tres);
$totalRows_unidad_tres = mysql_num_rows($unidad_tres);
//CARGA UNIDAD 4
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cuatro = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='4' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ";
$unidad_cuatro = mysql_query($query_unidad_cuatro, $conexion1) or die(mysql_error());
$row_unidad_cuatro = mysql_fetch_assoc($unidad_cuatro);
$totalRows_unidad_cuatro = mysql_num_rows($unidad_cuatro);
//CARGA UNIDAD 5
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_cinco = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='5' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ";
$unidad_cinco = mysql_query($query_unidad_cinco, $conexion1) or die(mysql_error());
$row_unidad_cinco = mysql_fetch_assoc($unidad_cinco);
$totalRows_unidad_cinco = mysql_num_rows($unidad_cinco);
//CARGA UNIDAD 6
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_seis = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='6' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ";
$unidad_seis = mysql_query($query_unidad_seis, $conexion1) or die(mysql_error());
$row_unidad_seis = mysql_fetch_assoc($unidad_seis);
$totalRows_unidad_seis = mysql_num_rows($unidad_seis);
//CARGA UNIDAD 7
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_siete = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='7' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ";
$unidad_siete = mysql_query($query_unidad_siete, $conexion1) or die(mysql_error());
$row_unidad_siete = mysql_fetch_assoc($unidad_siete);
$totalRows_unidad_siete = mysql_num_rows($unidad_siete);
//CARGA UNIDAD 8
mysql_select_db($database_conexion1, $conexion1);
$query_unidad_ocho = "select * from Tbl_mezclas, Tbl_produccion_mezclas_impresion WHERE Tbl_produccion_mezclas_impresion.id_ref_pmi='$id_ref' AND Tbl_produccion_mezclas_impresion.b_borrado_pmi='0'  AND Tbl_produccion_mezclas_impresion.und='8' AND Tbl_produccion_mezclas_impresion.id_m=Tbl_mezclas.id_m ";
$unidad_ocho = mysql_query($query_unidad_ocho, $conexion1) or die(mysql_error());
$row_unidad_ocho = mysql_fetch_assoc($unidad_ocho);
$totalRows_unidad_ocho = mysql_num_rows($unidad_ocho);

//SUMA TOTAL DE KILOS EXTRUIDOS POR O.P Y EL CONSECUTIVO DEL NUMERO DE ROLLO
$colname_Rollo= "-1";
if (isset($_GET['id_op'])) {
  $colname_Rollo = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_Rollo = sprintf("SELECT * FROM TblImpresionRollo WHERE id_op_r='%s' GROUP BY rollo_r DESC",$colname_Rollo);
$Rollos = mysql_query($query_Rollo, $conexion1) or die(mysql_error());
$row_Rollo = mysql_fetch_assoc($Rollos);
$totalRows_Rollo = mysql_num_rows($Rollos);
//ULTIMO ROLLO
$colname_rollo_impr = "-1";
if (isset($_GET['id_op'])) {
  $colname_rollo_impr = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_impr = sprintf("SELECT rollo_r,kilos_r,cod_empleado_r,cod_auxiliar_r,fechaI_r,fechaF_r FROM TblImpresionRollo WHERE id_op_r=%s ORDER BY rollo_r DESC LIMIT 1",$colname_rollo_impr);
$rollo_impr = mysql_query($query_rollo_impr, $conexion1) or die(mysql_error());
$row_rollo_impr = mysql_fetch_assoc($rollo_impr);
$totalRows_rollo_impr = mysql_num_rows($rollo_impr);
//CODIGO EMPLEADO
/*mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado WHERE tipo_empleado IN(5,10) ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado);
*/
$row_codigo_empleado = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(5,10) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
  
$row_revisor = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(5,10) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/ajax_impresion.js"> </script>
</head>
<body>
<div align="center">
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
	<li><a href="produccion_registro_impresion_listado.php">LISTADO IMPRESION</a></li>		
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" onSubmit="return (  validacion_unodelosdos_imp() && validacion_select_fecha())" >
    <table id="tabla2">
    <tr>
    <td colspan="13" id="fuente2">
        <tr id="tr1">
      <td colspan="13" id="titulo2">REGISTRO DE IMPRESION</td>
    </tr>
    <tr>
      <td width="137" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
      <td colspan="10" id="dato3"><a href="produccion_registro_impresion_listado.php"><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO O.P" title="LISTADO O.P" border="0"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
    </tr>
    <tr id="tr1">
      <td width="182" colspan="2" nowrap="nowrap" id="dato2">ORDEN DE PRODUCCION</td>
      <td colspan="7" id="dato3"> 
        Ingresado por
<input name="str_responsable_rp" type="text" id="str_responsable_rp" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
    </tr>
    <tr id="tr3">
      <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_orden_produccion['id_op'];?></td>
      <td width="126" colspan="5" nowrap="nowrap" id="fuente2"></td>
      <td width="235" id="fuente2">&nbsp;</td>
    </tr>
    <tr id="tr1">
      <td colspan="2" nowrap="nowrap" id="dato2">REFERENCIA</td>
      <td colspan="7" id="dato2">VERSION</td>
      </tr>
    <tr>
      <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_orden_produccion['int_cod_ref_op'];?></td>
      <td colspan="7" nowrap="nowrap" id="numero2"><?php echo $row_orden_produccion['version_ref_op'];?></td>
      </tr>
    <tr>
      <td colspan="2" id="dato2">&nbsp;</td>
      <td colspan="7" id="dato2">&nbsp;</td>
      </tr>
    <tr id="tr1">
      <td colspan="13" id="titulo4">DETALLE</td>
    </tr>
    <tr>
      <td id="fuente1">Kilo Rollo Impreso 
            <?php $id_r=$_GET['id_op'];
            $sqlre="SELECT COUNT(rollo_r) AS rolloE, SUM(metro_r) AS metrosE FROM TblExtruderRollo WHERE id_op_r=$id_r"; 
            $resultre=mysql_query($sqlre); 
            $numre=mysql_num_rows($resultre); 
            if($numre >= '1') 
            {$max_rolloE=mysql_result($resultre,0,'rolloE');
			}?>  
            <?php $id_r=$_GET['id_op'];
            $sqlr="SELECT COUNT(rollo_r) AS rolloI, SUM(metro_r) AS metrosI, SUM(kilos_r) AS kilosI FROM TblImpresionRollo WHERE id_op_r=$id_r"; 
            $resultr=mysql_query($sqlr); 
            $numr=mysql_num_rows($resultr); 
            if($numr >= '1') 
            {$max_rolloI=mysql_result($resultr,0,'rolloI');
			 $max_metrosI=mysql_result($resultr,0,'metrosI');
			 $max_kilosI=mysql_result($resultr,0,'kilosI');		 
			}
			//$divide=($max_kilosI/$max_rolloE); 
			 ?> 
          </td> 
      <td id="fuente1"><input type="number" name="int_kilos_prod_rp" id="int_kilos_prod_rp" min="0"step="any" required="required" style="width:80px"  autofocus="autofocus" value="<?php echo $row_rollo_impr['kilos_r']; ?>" onchange="kilosxHora2();"/></td>
      <td id="fuente1">        Kilos Desperdiciados Materia Prima          </td>
      <td colspan="5" id="fuente1"><input type="number" name="int_kilos_desp_rp" id="int_kilos_desp_rp" min="0"step="any" required="required" style="width:80px" onblur="getSumT()" value="0"/></td>
      <td id="fuente1">Kilos Reales:        </td>
      <td colspan="3" id="fuente1"><input type="number" name="int_total_kilos_rp" id="int_total_kilos_rp" min="0"step="any" required="required"  style="width:80px"  onblur="return validacion_kilos_Impresos();" value="0"/>
        <input type="hidden" name="kilos_r" id="kilos_r" size="12" value="<?php echo $row_rollo_impr['kilos_r']; ?>"/></td>
      </tr>
      <!--<tr >
      <td id="fuente1">Total materia Prima Klg</td>
      <td id="fuente1"><input name="int_totalKilos_tinta_rp" type="text" id="int_totalKilos_tinta_rp" style="width:80px" min="0"step="any" onblur="getSumK();tintasVacio();" readonly="readonly" /></td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="5" id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      <td colspan="3" id="fuente1">&nbsp;</td>
      </tr>-->
    <tr>
      <td colspan="2" id="dato1"></td>
      <td colspan="2" id="dato1"></td>
      <td colspan="5" id="dato1"></td>
      <td id="dato1"></td>
    </tr>
    <tr>
      <td colspan="15" id="fuente2">&nbsp;</td>
      </tr>
    <tr>
      <td colspan="12" id="fuente2">     
      </td>
      </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1">Maquina</td>
      <td colspan="2" id="fuente1"><select name="str_maquina_rp" id="str_maquina_rp">
        <?php
do {  
?>
        <option value="<?php echo $row_maquinas['id_maquina']?>"><?php echo $row_maquinas['nombre_maquina']?></option>
        <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
      </select></td>
      <td colspan="7" id="fuente1">
       

       <select name="int_cod_empleado_rp" id="operario" onblur=" validacion_unodelosdos_imp();" style="width:120px">
           <option value=""<?php if (!(strcmp("", $row_rollo_impr['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>>Operario</option>
              <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
                <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $row_rollo_impr['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
              <?php } ?>
        </select>

   -
   <select name="int_cod_liquida_rp" id="auxiliar" style="width:120px" onblur="validacion_unodelosdos_imp();">
     <option value=""<?php if (!(strcmp("", $row_rollo_impr['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>>Auxiliar</option>
       <?php  foreach($row_revisor as $row_revisor ) { ?>
         <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_revisor['codigo_empleado'], $row_rollo_impr['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['codigo_empleado']." - ".$row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado']?></option>
       <?php } ?>
     </select>
 </td>
    </tr>
    <tr>
      <td colspan="2" id="dato1"></td>
      <td colspan="2" id="dato1"></td>
      <td colspan="5" id="dato1"></td>
      <td id="dato1"></td>
    </tr>
    <tr>
      <td colspan="2" id="fuente1">Fecha Inicial</td>
      <td colspan="2" id="fuente1"><input name="fecha_ini_rp" id="fecha_ini_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rollo_impr['fechaI_r']); ?>" style=" width:210px" required/></td>
      <td colspan="7" id="fuente1">
      <input name="int_metro_lineal_rp" type="number" required="required" id="int_metro_lineal_rp" placeholder="Metro Lineal" style="width:116px" min="0"step="any"  onclick="getSumT();" value="<?php echo $max_metrosI / $max_rolloI; ?>"/>
Metro lineal (Extrusion)</td>
    </tr>
    <tr id="tr1">
      <td colspan="2" id="fuente1">Fecha Final</td>
      <td colspan="2" id="fuente1"><input name="fecha_fin_rp" id="fecha_fin_rp" type="datetime-local" min="2000-01-02" value="<?php echo muestradatelocal($row_rollo_impr['fechaF_r']); ?>" style=" width:210px" onblur="validacion_select_fecha();" required onChange="kilosxHora2()"/></td>
      <td colspan="7" id="fuente1">
      <input type="number" name="rollo_rp" id="rollo_rp" min="0"step="any" required="required" placeholder="Rollos" style="width:46px" value="<?php  echo $row_rollo_impr['rollo_r']; ?>"/>
de
  <input name="int_total_rollos_rp" type="number" id="int_total_rollos_rp" placeholder="Total Rollos" style="width:46px" min="0"step="any" value="<?php if($max_rolloE=='0'){echo " ";}else {echo $max_rolloE; }?>" />
Total Rollos (Extrusion)</td>
    </tr>
     <tr>
       <td colspan="2" id="fuente1"><input id="porcentaje" name="porcentaje" type="hidden" value="<?php echo $row_orden_produccion['int_desperdicio_op']; ?>" min="0" max="100" step="1" style="width:40px" required="required" readonly="readonly"/>
          
         <input type="hidden" name="int_metroxmin_rp" id="int_metroxmin_rp" min="0"step="any" required="required" readonly="readonly"  onclick="kilosxHora2();"/>
          Kilos Producidos x Hora</td>
       <td colspan="2" id="fuente1"><input name="int_kilosxhora_rp" type="text" id="int_kilosxhora_rp"  size="12" value="" onclick="kilosxHora2()" required="required"/></td>
       <td colspan="7" id="fuente1"><input name="total_horas_rp" type="text" id="total_horas_rp"  size="12" value="" onclick="kilosxHora2()" required="required"/>
         Total Horas Trabajadas</td>
     </tr> 
<tr id="tr1">
  <td colspan="13" id="titulo4">Registros Existentes</td>
</tr>
<tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap"id="titulo4">ROLLO</td>
    <td nowrap="nowrap"id="titulo4">FECHA INICIAL</td>
  </tr>          
  <tr>
    <td colspan="13" id="fuente1">
 <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
  <td id="fuente2"><?php echo $row_Rollo['id_op_r']; ?></td>
  <td id="fuente2"><?php echo $row_Rollo['kilos_r']; ?></td>
  <td id="fuente2"><?php echo $row_Rollo['rollo_r']; ?></td>
  <td nowrap="nowrap" id="fuente2"><a href="javascript:getClientData('clientId','<?php echo $row_Rollo['id_r']; ?>','rollo','<?php echo $row_Rollo['rollo_r']; ?>')"><?php echo $row_Rollo['fechaI_r']; ?></a>
  <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a>
  </td>
    <td colspan="13" id="fuente1"></td>
  </tr>
  <?php } while ($row_Rollo = mysql_fetch_assoc($Rollos)); ?>      
    <tr>
      <td colspan="13" id="fuente3">&nbsp;</td>
    </tr>
        <tr>
      <td colspan="13" id="fuente3">&nbsp;</td>
    </tr>
      
    <tr>
      <td colspan="13" id="fuente2"><input type="button" value="Modificar Mezclas" onclick="mostrardiv2()" />
        <input type="button" value="Ocultar" onclick="ocultardiv2()" /></td>
      </tr>
    <tr>
      <td colspan="13" id="fuente3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="13">
    <input type="hidden" name="id_pm_cv" id="id_pm_cv" value="0"/>
    <input type="hidden" name="version_ref_cv" id="version_ref_cv" value="<?php echo $row_ref['version_ref']; ?>" />
    <input type="hidden" name="id_proceso_cv" id="id_proceso_cv" value="2"/>
    <input type="hidden" name="cod_ref_cv" id="cod_ref_cv" value="<?php echo $row_ref['cod_ref']; ?>"/>
    <input type="hidden" name="fecha_registro_cv" id="fecha_registro_cv"  value="<?php echo date("Y-m-d"); ?>"/>
    <input type="hidden" name="str_registro_cv" id="str_registro_cv" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
    <input type="hidden" name="b_borrado_cv" id="b_borrado_cv" value="0"/>
    <!--fin mezcla caract --></td>
    </tr>
    <tr id="tr1">
      <td colspan="13" id="dato2">
        <input name="id_proceso_rp" type="hidden" id="id_proceso_rp" value="2" />
        <input name="id_op_rp" type="hidden" id="id_op_rp" value="<?php echo $row_orden_produccion['id_op']; ?>" />
        <input name="id_ref_rp" type="hidden" id="id_ref_rp" value="<?php echo $row_ref['id_ref']; ?>" />
        <input name="int_cod_ref_rp" type="hidden" id="int_cod_ref_rp" value="<?php echo $row_orden_produccion['int_cod_ref_op']; ?>" />
        <input name="version_ref_rp" type="hidden" id="version_ref_rp" value="<?php echo $row_orden_produccion['version_ref_op']; ?>" />
          <input name="kilos_op" type="hidden" id="kilos_op" value="<?php echo $row_orden_produccion['int_kilos_op']; ?>" />
          <?php  for ($r=0;$r<=$totalRows_Rollo-1;$r++) { ?>
          <input name="kilos_impreso[]" type="hidden" id="kilos_impreso[]" value="<?php $tK=mysql_result($Rollos,$r,int_total_kilos_rp); echo $tK; ?>" />
          <?php } ?>
        <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
    <tr>
      <td colspan="13" id="fuente2"><input type="submit" name="ENVIAR" id="ENVIAR" value="SIGUIENTE" onClick="envio_form(this);"/></td>
      </tr>
    </table>
</form>
<!--      <a href="javascript:verFoto('produccion_registro_impresion_detalle_add.php?id_op=<?php echo $row_rp_edit['id_op_rp'] ?>','820','270')"></a></td>
-->

 <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form2"> 
      <!--TABLA DE MEZCLA DE IMPRESION  -->
    <table id="flotante2" style="display:none" ><!--style="display:none"-->
      <tr id="tr1">
          <td colspan="11" id="titulo">MEZCLAS DE IMPRESION
            <input name="str_registro_pmi" type="hidden" id="str_registro_pmi" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/>
            <input name="fecha_registro_pmi" type="hidden"  id="fecha_registro_pmi" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" />
            <input type="hidden" name="id_ref_pmi" id="id_ref_pmi" value="<?php echo $row_ref['id_ref']; ?>"/>
            <input type="hidden" name="int_cod_ref_pmi" id="int_cod_ref_pmi" value="<?php echo $row_ref['cod_ref'] ?>"/>
            <input type="hidden" name="version_ref_pmi" id="version_ref_pmi" value="<?php echo $row_ref['version_ref'] ?>"/>
            </td>
        </tr>
       <tr>  
         <td nowrap="nowrap" valign="top">
         <table>
           <?php  if ($row_unidad_uno!='') { ?>
               <tr>
                 <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 1</td>
                 <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
                 </tr>
           <?php }?>
           <?php  for ($x=0;$x<=$totalRows_unidad_uno-1 ;$x++) { ?>
           <tr>
             <td id="fuente3"><?php $id=mysql_result($unidad_uno,$x,id_i_pmi);$id_pmi=mysql_result($unidad_uno,$x,id_pmi);$id_m=mysql_result($unidad_uno,$x,str_nombre_m);echo $id_m;?></td>
             <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
               <select name="id[]" id="id[]" style="width:60px">
                 <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                 <?php
					do {  
					?>
                 <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                 <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
               </select>
               <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_uno,$x,str_valor_pmi); echo $valor;?>"/></td>
           </tr>
           <?php  } ?>
         </table></td> 
         
         <td valign="top"><table>
    <?php  if ($row_unidad_dos!='') { ?>
    <tr>
        <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 2</td>
        <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
        </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_dos-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_dos,$x,id_i_pmi);$id_pmi=mysql_result($unidad_dos,$x,id_pmi); $id_m=mysql_result($unidad_dos,$x,str_nombre_m);echo $id_m;?></td>
      <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
        <select name="id[]" id="id[]" style="width:60px">
          <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
        </select>
        <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_dos,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table></td> 
         
         <td valign="top"><table>
             <?php  if ($row_unidad_tres!='') { ?>
             <tr>
             <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 3</td>
             <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
             </tr>
             <?php }?>
             <?php  for ($x=0;$x<=$totalRows_unidad_tres-1 ;$x++) { ?>
             <tr>
               <td id="fuente3"><?php $id=mysql_result($unidad_tres,$x,id_i_pmi);$id_pmi=mysql_result($unidad_tres,$x,id_pmi);$id_m=mysql_result($unidad_tres,$x,str_nombre_m);echo $id_m;?></td>
               <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
               <select name="id[]" id="id[]" style="width:60px">
                 <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
                   <?php
					do {  
					?>
                   <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
                   <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
                 </select>
                 <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_tres,$x,str_valor_pmi); echo $valor;?>"/>            </td>
               </tr>
             <?php  } ?>
             </table></td>                               
       </tr>     
       <tr>
         <td id="fuente1">
         <table>
    <?php  if ($row_unidad_cuatro!='') { ?>
    <tr>
      <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 4</td>
      <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
      </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_cuatro-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_cuatro,$x,id_i_pmi);$id_pmi=mysql_result($unidad_cuatro,$x,id_pmi); $id_m=mysql_result($unidad_cuatro,$x,str_nombre_m);echo $id_m;?></td>
      <td nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
        </select>
        <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_cuatro,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table>
         </td>
         <td id="fuente1">
         <table>
    <?php  if ($row_unidad_cinco!='') { ?>
    <tr>
        <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 5</td>
        <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
        </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_cinco-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_cinco,$x,id_i_pmi);$id_pmi=mysql_result($unidad_cinco,$x,id_pmi); $id_m=mysql_result($unidad_cinco,$x,str_nombre_m);echo $id_m;?></td>
      <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
        </select>
        <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_cinco,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table>
         </td>
         <td id="fuente1">
        <table>
    <?php  if ($row_unidad_seis!='') { ?>
    <tr>
               <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 6</td>
               <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
               </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_seis-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_seis,$x,id_i_pmi);$id_pmi=mysql_result($unidad_sis,$x,id_pmi);$id_m=mysql_result($unidad_seis,$x,str_nombre_m);echo $id_m;?></td>
      <td nowrap="nowrap"id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
      </select>
        <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_seis,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table> 
         </td>
       </tr>
       <tr>
        <td id="fuente1">
        <table>
    <?php  if ($row_unidad_siete!='') { ?>
    <tr>
      <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 7</td>
      <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
      </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_siete-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_siete,$x,id_i_pmi);$id_pmi=mysql_result($unidad_siete,$x,id_pmi);$id_m=mysql_result($unidad_siete,$x,str_nombre_m);echo $id_m;?></td>
      <td  nowrap="nowrap" id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
      </select>
        <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_siete,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table>
        </td>
        <td id="fuente1">
        <table>
    <?php  if ($row_unidad_ocho!='') { ?>
    <tr>
              <td valign="top" nowrap="nowrap"id="fuente3">UNIDAD 8</td>
              <td colspan="2" valign="top" nowrap="nowrap"id="fuente3">%</td>
              </tr>
    <?php }?>
    <?php  for ($x=0;$x<=$totalRows_unidad_ocho-1 ;$x++) { ?>
    <tr>
      <td id="fuente3"><?php $id=mysql_result($unidad_ocho,$x,id_i_pmi);$id_pmi=mysql_result($unidad_ocho,$x,id_pmi);$id_m=mysql_result($unidad_ocho,$x,str_nombre_m);echo $id_m;?></td>
      <td nowrap="nowrap"id="fuente3"><input name="id_pmi[]" type="hidden" id="id_pmi[]" value="<?php echo $id_pmi; ?>" />
      <select name="id[]" id="id[]" style="width:60px">
        <option value=""<?php if (!(strcmp("", $id))) {echo "selected=\"selected\"";} ?>>Ref</option>
          <?php
					do {  
					?>
          <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $id))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
          <?php
					} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
					  $rows = mysql_num_rows($materia_prima);
					  if($rows > 0) {
						  mysql_data_seek($materia_prima, 0);
						  $row_materia_prima = mysql_fetch_assoc($materia_prima);
					  }
                     ?>
      </select>
        <input name="valor[]"  style="width:60px" min="0"step="0.01" type="number"  id="valor[]" value="<?php $valor=mysql_result($unidad_ocho,$x,str_valor_pmi); echo $valor;?>"/>            </td>
      </tr>
    <?php  } ?>
    </table>
        </td>
      </tr>
      <tr>
        <td colspan="15" id="fuente2"><textarea name="observ_pmi" id="observ_pmi" cols="80" rows="2"placeholder="OBSERVACIONES"onblur="conMayusculas(this)"><?php if($id_ref!=''){$cons="SELECT * FROM Tbl_produccion_mezclas_impresion WHERE int_cod_ref_pmi='$id_ref' AND und='1' AND id_i_pmi='1'";$res=mysql_query($cons);$num=mysql_fetch_assoc($res);echo $num['observ_pmi'];}?></textarea></td>
      </tr>
      <tr id="tr1">
        <td colspan="15" id="fuente2">
        <input type="hidden" name="id_ref" id="id_ref" value="<?php echo $row_ref['id_ref']; ?>"/>
        <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_ref['cod_ref']; ?>"/>
        <input type="hidden" name="id_proceso" id="id_proceso" value="2"/>
        <input type="hidden" name="b_borrado_pmi" id="b_borrado_pmi" value="0"/></td>
      </tr>
     <tr><td colspan="7" >
   <table>      
      <tr id="tr1">
        <td colspan="13" id="titulo4">CARACTERISTICAS</td>
        </tr> 
         <tr>
          <?php  for ($x=0;$x<=$totalRows_caract_valor-1;$x++) { ?>          
          <td width="137" id="fuente1"><?php $id_cv=mysql_result($caract_valor,$x,id_cv); $var=mysql_result($caract_valor,$x,str_nombre_caract_c); echo $var; ?>                                             
          <input name="id_cv[]" type="hidden" value="<?php echo $id_cv; ?>" /><input name="valor_cv[]" type="number" style="width:47px" min="0"step="1"  placeholder="Cant/Und" size="5"value="<?php $valor=mysql_result($caract_valor,$x,str_valor_cv); echo $valor;?>"/>
          </td>
         <?php  } ?>
         </tr>
         </table><?php if($row_caract_valor['id_ref_cv']=='') {?><a target="new" href="produccion_caract_impresion_add.php?id_ref=<?php echo $row_ref['id_ref'];?>&amp;cod_ref=<?php echo $row_ref['cod_ref']; ?>">Agregar Mezcla</a><?php }?>  
        </td></tr>                                                    
      <tr>
      <td colspan="10" id="fuente2"><input type="hidden" name="MM_update" value="form2" />
        <input type="submit" name="ACTUALIZAR" id="ACTUALIZAR" value="ACTUALIZAR MEZCLA"/></td>
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
mysql_free_result($extru);
mysql_free_result($maquinas);
mysql_free_result($ref);
mysql_free_result($tiempoMuerto);
mysql_free_result($tiempoPreparacion);
mysql_free_result($desperdicio);
mysql_free_result($totalTintas);
mysql_free_result($materia_prima);
mysql_free_result($caract_valor);
?>
