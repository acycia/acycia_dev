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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
echo 'envio normal';die;
 $insertSQL = sprintf("INSERT INTO Tbl_reg_produccion (id_rp,id_proceso_rp, id_op_rp,id_ref_rp, int_cod_ref_rp, version_ref_rp, rollo_rp, int_kilos_prod_rp, int_kilos_desp_rp,int_total_kilos_rp, porcentaje_op_rp, int_metro_lineal_rp, int_total_rollos_rp, total_horas_rp, rodamiento_rp, str_maquina_rp, str_responsable_rp, fecha_ini_rp, fecha_fin_rp, int_kilosxhora_rp,int_cod_empleado_rp,int_cod_liquida_rp,parcial) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
   GetSQLValueString($_POST['id_rp'], "int"), 
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
   GetSQLValueString($_POST['tiempoOptimo_rp'], "text"),             
   GetSQLValueString($_POST['str_maquina_rp'], "text"),
   GetSQLValueString($_POST['str_responsable_rp'], "text"),
   GetSQLValueString($_POST['fecha_ini_rp'], "date"),
   GetSQLValueString($_POST['fecha_fin_rp'], "date"),
   GetSQLValueString($_POST['int_kilosxhora_rp'], "double"),
   GetSQLValueString($_POST['int_cod_empleado_rp'], "int"),
   GetSQLValueString($_POST['int_cod_liquida_rp'], "int"),
   GetSQLValueString($_POST['parcial'], "text"));
					   
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
					   
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
 
					   					   
  $updateSQL4 = sprintf("UPDATE Tbl_orden_produccion SET b_estado_op='1', b_visual_op='0',f_coextruccion=DATE(%s) WHERE id_op=%s",
                       GetSQLValueString($_POST['fecha_fin_rp'], "date"),
					   GetSQLValueString($_POST['id_op_rp'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result4 = mysql_query($updateSQL4, $conexion1) or die(mysql_error());	
  				   
  /*  $insertGoTo = "produccion_registro_extrusion_edit.php?id_rp=" . $_POST['id_rp'] ."&id_op=" . $_POST['id_op_rp'] ."";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  } 
  header(sprintf("Location: %s", $insertGoTo));*/
}
//INSERT

$conexion = new ApptivaDB();//consultas


$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//CONSULTA SI TIENE REGISTROS LIQUIDADOS
$colname_rp= "-1";
if (isset($_GET['id_op'])) {
  $colname_rp = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rp = sprintf("SELECT rollo_rp FROM Tbl_reg_produccion WHERE id_op_rp=%s AND id_proceso_rp='1' ORDER BY rollo_rp DESC",$colname_rp);
$rp_edit= mysql_query($query_rp, $conexion1) or die(mysql_error());
$row_rp_edit = mysql_fetch_assoc($rp_edit);
$totalRows_rp_edit = mysql_num_rows($rp_edit);

//VARIABLE IMPORTANTE PARA LA IMRPESION DE ROLLOS
$rollo_liqu = empty($row_rp_edit['rollo_rp']) ? '0' : $row_rp_edit['rollo_rp'];

//SUMA TOTAL DE KILOS EXTRUIDOS POR O.P
$colname_totalKilos= "-1";
if (isset($_GET['id_op'])) {
  $colname_totalKilos = (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_totalKilos = sprintf("SELECT * FROM TblExtruderRollo WHERE TblExtruderRollo.id_op_r='%s' ORDER BY fechaI_r ASC LIMIT 100",$colname_totalKilos);
$totalKilos = mysql_query($query_totalKilos, $conexion1) or die(mysql_error());// AND rollo_r > $rollo_liqu
$row_totalKilos = mysql_fetch_assoc($totalKilos);
$totalRows_totalKilos = mysql_num_rows($totalKilos);

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
//IMPRIME CAMPOS DE LA REFERENCIA PRESENTACION, PESO MILLAR, ID_REF
$colname_ref = "-1";
if (isset($_GET['id_op'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_op'] : addslashes($_GET['id_op']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_orden_produccion, Tbl_referencia WHERE Tbl_orden_produccion.id_op='%s' AND Tbl_orden_produccion.int_cod_ref_op=Tbl_referencia.cod_ref ",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);

//CODIGO EMPLEADO
$row_codigo_empleado = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(4) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
  
$row_revisor = $conexion->llenaSelect('empleado a INNER JOIN TblProcesoEmpleado b ','ON a.codigo_empleado=b.codigo_empleado WHERE a.tipo_empleado IN(4) AND b.estado_empleado=1 ','ORDER BY a.nombre_empleado ASC');
/*mysql_select_db($database_conexion1, $conexion1);
$query_codigo_empleado = "SELECT * FROM empleado WHERE tipo_empleado='4' ORDER BY nombre_empleado ASC";
$codigo_empleado = mysql_query($query_codigo_empleado, $conexion1) or die(mysql_error());
$row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
$totalRows_codigo_empleado = mysql_num_rows($codigo_empleado); */

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT id_rp FROM Tbl_reg_produccion ORDER BY id_rp DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo_parcial = sprintf("SELECT parcial FROM Tbl_reg_produccion where id_op_rp='%s' and id_proceso_rp='1' order by parcial DESC",$colname_ref);
$ultimo_parcial = mysql_query($query_ultimo_parcial, $conexion1) or die(mysql_error());
$row_ultimo_parcial = mysql_fetch_assoc($ultimo_parcial);
$totalRows_ultimo_parcial = mysql_num_rows($ultimo_parcial);


  

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<link rel="stylesheet" type="text/css" href="css/general.css"/>

<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script> 
<script type="text/javascript" src="AjaxControllers/js/envioForms.js"></script> 

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
<body onload="javascript:kilosxHora2();"> 
<?php echo $conexion->header('vistas'); ?>

  <!-- <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="form1" onSubmit="return(rollosVacio())"> -->
 
<form action="view_index.php?c=cextruder&a=Guardar" method="POST" enctype="multipart/form-data" name="form1" id="form1"  >
<table class="table table-bordered table-sm">
  <tr id="tr1">
    <td colspan="12" id="titulo2">REGISTRO DE EXTRUSION</td>
  </tr>
  <tr>
    <td width="137" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
    <td colspan="10" id="dato3">
      <a href="produccion_extrusion_listado_rollos.php?id_op_r=<?php echo $_GET['id_op'];?>" ><img src="images/opciones.gif" style="cursor:hand;" alt="LISTADO O.P" title="LISTADO O.P" border="0"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a></td>
  </tr>
  <tr id="tr1">
    <td width="182" colspan="5" nowrap="nowrap" id="dato2">ORDEN DE PRODUCCION</td>
    <td colspan="5" id="dato3"> Ingresado por
      <input name="str_responsable_rp" type="text" id="str_responsable_rp" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
  </tr>
  <tr id="tr3">
    <td colspan="5" nowrap="nowrap" id="numero2"><?php echo $row_orden_produccion['id_op'];?></td>
    <td width="126" colspan="3" nowrap="nowrap" id="fuente2"></td>
    <td width="235" colspan="2" id="fuente2">&nbsp;</td>
  </tr>
  <tr id="tr1">
    <td colspan="5" nowrap="nowrap" id="dato2">REFERENCIA</td>
    <td colspan="5" id="dato2">VERSION</td>
  </tr>
  <tr>
    <td colspan="5" nowrap="nowrap" id="numero2"><?php echo $row_orden_produccion['int_cod_ref_op'];?></td>
    <td colspan="5" nowrap="nowrap" id="numero2"><?php echo $row_orden_produccion['version_ref_op'];?></td>
  </tr>
  <tr>
    <td colspan="5" id="dato2">&nbsp;</td>
    <td colspan="5" id="dato2">&nbsp;</td>
  </tr>
  <tr id="tr1">
    <td colspan="12" id="titulo4">DETALLE</td>
  </tr>
  <tr >
    <td colspan="2" id="fuente1">&nbsp;</td>
    <td colspan="10" id="fuente1"><input type="text" name="int_kilos_prod_rp" id="int_kilos_prod_rp" min="0"step="any" style="width:80px"  autofocus="autofocus" readonly="readonly"  /> 
      <em>Kilos de Rollos</em>
      <input type="hidden" name="int_kilos_desp_rp" id="int_kilos_desp_rp" min="0"step="any" style="width:80px" value="0"/>
      <input type="hidden" name="int_total_kilos_rp" id="int_total_kilos_rp" min="0"step="any" style="width:80px" />
       <?php $ultipo_reg = $row_ultimo['id_rp']+1?><?php $ult_parcial = $row_ultimo_parcial['parcial']+1?>
      <input type="hidden" name="id_rp" id="id_rp" value="<?php echo $ultipo_reg; ?>" />
      <input type="hidden" name="parcial" id="parcial" value="<?php echo $ult_parcial; ?>" /> </td>
  </tr>
  <tr>
    <td colspan="2" id="dato1"></td>
    <td colspan="5" id="dato1"></td>
    <td colspan="3" id="dato1"></td>
    <td colspan="2" id="dato1"></td>
  </tr>
  <tr>
    <td colspan="12" id="fuente1"><strong>NOTA:</strong>Importante cuando quede la o.p parcial se debe ingresar solamente los rollos que se van a liquidar en ese momento.</td>
  </tr>
  <tr id="tr1">
    <td colspan="2" id="fuente1">Maquina</td>
    <td colspan="5" id="fuente1">
      <select name="str_maquina_rp" id="str_maquina_rp"  >
      <option value="">Maquina</option>
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
    </select>
  </td> 
           <td colspan="5" id="fuente1"> 
              <select name="int_cod_empleado_rp" id="montaje" style="width:120px"  >
                 <option value="">Montaje</option>
                  <?php  foreach($row_codigo_empleado as $row_codigo_empleado ) { ?>
                    <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"<?php if (!(strcmp($row_codigo_empleado['codigo_empleado'], $rew_operarios['cod_empleado_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
                  <?php } ?>
                </select>  
            -
            <select name="int_cod_liquida_rp" id="liquida" style="width:120px" >
        <option value="">Liquida</option>
                <?php  foreach($row_revisor as $row_revisor ) { ?>
                  <option value="<?php echo $row_revisor['codigo_empleado']?>"<?php if (!(strcmp($row_revisor['codigo_empleado'], $rew_operarios['cod_auxiliar_r']))) {echo "selected=\"selected\"";} ?>><?php echo $row_revisor['codigo_empleado']." - ".$row_revisor['nombre_empleado']." ".$row_revisor['apellido_empleado']?></option>
                <?php } ?>
              </select>

        </td>

    <!-- <td colspan="5" id="fuente1"><select name="int_cod_empleado_rp" id="montaje" style="width:120px" >
      <option value="">Montaje</option>
      <?php
do {  
?>
      <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
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
      <select name="int_cod_liquida_rp" id="liquida" style="width:120px" >
        <option value="">Liquida</option>
        <?php
do {  
?>
        <option value="<?php echo $row_codigo_empleado['codigo_empleado']?>"><?php echo $row_codigo_empleado['codigo_empleado']." - ".$row_codigo_empleado['nombre_empleado']." ".$row_codigo_empleado['apellido_empleado']?></option>
        <?php
} while ($row_codigo_empleado = mysql_fetch_assoc($codigo_empleado));
  $rows = mysql_num_rows($codigo_empleado);
  if($rows > 0) {
      mysql_data_seek($codigo_empleado, 0);
	  $row_codigo_empleado = mysql_fetch_assoc($codigo_empleado);
  }
?>
      </select></td> -->
  </tr>
  <tr>
    <td colspan="2" id="dato1"></td>
    <td colspan="5" id="dato1"></td>
    <td colspan="3" id="dato1"></td>
    <td colspan="2" id="dato1"></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1">Fecha Inicial</td>
    <td colspan="5" id="fuente1"><input name="fecha_ini_rp"  id="fecha_ini_rp" type="datetime-local" min="2000-01-02" size="10" required="required" onchange="kilosxHora2();" readonly="readonly"/></td> 
    <td colspan="5" id="fuente1"><input type="hidden" name="horas_rp" id="horas_rp" readonly="readonly"  size="7"/>
       <?php $id_r=$_GET['id_op'];
            $sqlr="SELECT COUNT(rollo_r) AS rollo, SUM(metro_r) AS metros FROM TblExtruderRollo WHERE id_op_r=$id_r"; 
            $resultr=mysql_query($sqlr); 
            $numr=mysql_num_rows($resultr); 
            if($numr >= '1') 
            {$max_rollo=mysql_result($resultr,0,'rollo');
			 $max_metros=mysql_result($resultr,0,'metros');
			}?>
      <input type="number" name="int_metro_lineal_rp" id="metro_r" min="0"step="any" required="required" placeholder="Metro Lineal" style="width:116px"  value="<?php if($max_metros==0){echo "";}else{echo $max_metros;} ?>"/>
      <input name="metro_op" type="hidden" id="metro_op" value="<?php echo $row_orden_produccion['metroLineal_op']; ?>" />
      Metro lineal de <?php echo $row_orden_produccion['metroLineal_op']; ?></td>
  </tr>
  <tr id="tr1">
    <td colspan="2" id="fuente1">Fecha Final</td>
    <td colspan="5" id="fuente1"><input name="fecha_fin_rp" id="fecha_fin_rp"  type="datetime-local" min="2000-01-02"size="10" onChange="kilosxHora2()" readonly="readonly"/></td>
    <td colspan="5" id="fuente1"><input type="number" name="rollo_rp" id="rollo_rp"  step="any" required="required" readonly="readonly" value="<?php echo $rollo_liqu;?>" style="width:55px"/>
      <input type="number" name="int_total_rollos_rp" id="int_total_rollos_rp"  step="any" required="required" readonly="readonly" placeholder="Total Rollos" value="<?php if($max_rollo==0){echo "";}else{echo $max_rollo;} ?>" style="width:55px"/>
Total Rollos</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1">Porcentaje  O.P</td>
    <td colspan="5" id="fuente1"><input id="porcentaje" name="porcentaje" type="number" value="<?php echo $row_orden_produccion['int_desperdicio_op']; ?>" min="0" max="100" step="1" style="width:40px" required="required" readonly="readonly"/>
%</td>
    <td colspan="5" id="fuente1"><input type="text" name="tiempoOptimo_rp" id="tiempoOptimo_rp"  size="12" >
    Tiempo Optimo</td>
  </tr>
  <tr>
    <td colspan="2" id="fuente1"><input name="total_horas_rp" type="hidden" id="total_horas_rp" size="19" value=""  onclick="kilosxHora2()"/>
      <input type="hidden" name="int_kilosxhora_rp" id="int_kilosxhora_rp" min="0"step="any" size="12" value="" />
      <strong>
      <input name="valor_tiem_rt" id="horasmuertas" type="hidden" size="6" value="0"/>
      <input name="valor_prep_rtp" id="horasprep" type="hidden" size="6" value="0"/>
      </strong></td>
    <td colspan="5" id="fuente1">&nbsp;</td>
    <td colspan="5" id="fuente1">00:00:00</td>
  </tr>
  <tr id="tr1">
    <td colspan="12" id="titulo4">Registros Existentes</td>
  </tr>
  <tr>
    <td colspan="12" id="fuente1">
<!--   <?php 
      $fechaini='11/20/2013 15:00'; 
	  $fechafin='11/21/2013 18:30'; 
	  
      $horaini='14:20'; 
      $horafin='18:40';
		 
		 
		 ?>
       <input type="datetime-local" name="fechaini" id="fechaini" value="<?php echo $fechaini; ?>"  />
       <input type="datetime-local" name="fechafin" id="fechafin" value="<?php echo $fechafin; ?>"  />
       total f
       <input type="text" name="fecha" id="fecha" size="7" value="<?php //echo RestarFechas($fechaini,$fechafin); ?>"/>-->
         
     <!-- <input type="datetime-local" name="horaini" id="horaini" value="<?php echo $horaini; ?>" />
      <input type="datetime-local" name="horafin" id="horafin" value="<?php echo $horafin; ?>" />
      Tota h
      <input type="text" name="hora" id="hora" size="7" onblur="imprimeTotalFecha();" value=""/>-->

</td>
  </tr>


  <tr id="tr1">
    <td nowrap="nowrap"id="titulo1">METROS</td>
    <td nowrap="nowrap"id="titulo1">KILOS</td>
    <td nowrap="nowrap"id="titulo1">ROLLO</td>
    <td nowrap="nowrap"id="titulo1">FECHA INICIAL</td>
    <td colspan="2" nowrap="nowrap"id="titulo1">FECHA FINAL</td>
    </tr>   
  <tr>
    <td colspan="12" id="fuente1">
 <?php do { ?>
  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
  <td id="fuente1"><?php $metrosT+=$row_totalKilos['metro_r']; echo $row_totalKilos['metro_r']; ?></td>
  <td id="fuente1"><?php $kilosT+=$row_totalKilos['kilos_r'];echo $row_totalKilos['kilos_r']; ?></td>
  <td id="fuente2"><?php echo $row_totalKilos['rollo_r']; ?></td>
  <td nowrap="nowrap" id="fuente1"><?php echo $row_totalKilos['fechaI_r']; ?></td>
  <td nowrap="nowrap" id="fuente2">
    <a href="javascript:getClientData('clientId','<?php echo $row_totalKilos['id_op_r']; ?>','rollo','<?php echo $rollo_liqu; ?>','fechaF','<?php echo $row_totalKilos['fechaF_r']; ?>','parcial','<?php echo $ult_parcial; ?>')" style="text-decoration:none;"><?php echo $row_totalKilos['fechaF_r']; ?> 
    </a> 
  </td>
  <td nowrap="nowrap" id="fuente1">
  <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    <td colspan="10" id="fuente1"></td>
  </tr>
  <?php } while ($row_totalKilos = mysql_fetch_assoc($totalKilos)); ?>
 

  <tr>
    <td id="fuente1"><?php echo $metrosT;?></td>
    <td id="fuente1"><?php echo $kilosT;?></td>
    <td id="fuente1">&nbsp;</td>
    <td id="fuente1">&nbsp;</td>
    <td colspan="8" id="fuente1">&nbsp;</td>
    </tr>  
  <tr id="tr1">
    <td colspan="12" id="titulo4"><strong>RPM - % </strong></td>
  </tr>
  <tr>
    <td colspan="2" id="fuente2">TORNILLO A</td>
    <td colspan="5" id="fuente2">TORNILLO B</td>
    <td id="fuente2">TORNILLO C</td>
    <td colspan="4" id="fuente2">&nbsp;</td>
    </tr>
  <tr>
    <td id="fuente1">RPM</td>
    <td id="fuente1">%</td>
    <td colspan="4" id="fuente1">RPM</td>
    <td id="fuente1">%</td>
    <td id="fuente1">RPM</td>
    <td id="fuente1">%</td>
    <td colspan="3" id="fuente1">&nbsp;</td>
    </tr>
  <tr>
    <td id="fuente1"><input type="text" name="int_ref1_rpm_pm"  id="int_ref1_rpm_pm" min="0"step="any"size="7" required="required" value="<?php echo $row_mezcla['int_ref1_rpm_pm']; ?>" /></td>
    <td id="fuente1"><input name="int_ref1_tol5_porc1_pm"  type="text"  id="int_ref1_tol5_porc1_pm" placeholder="%" size="2" required="required" value="<?php echo $row_mezcla['int_ref1_tol5_porc1_pm'] ?>"/></td>
    <td colspan="4" id="fuente1"><input type="text" name="int_ref2_rpm_pm" id="int_ref2_rpm_pm" min="0"step="any"size="7" required="required" value="<?php echo $row_mezcla['int_ref2_rpm_pm']; ?>" /></td>
    <td id="fuente1"><input name="int_ref2_tol5_porc2_pm"  type="text"  id="int_ref2_tol5_porc2_pm" placeholder="%" size="2" required="required" value="<?php echo $row_mezcla['int_ref2_tol5_porc2_pm'] ?>"/></td>
    <td id="fuente1"><input type="text" name="int_ref3_rpm_pm" id="int_ref3_rpm_pm" min="0"step="any"size="7" required="required" value="<?php echo $row_mezcla['int_ref3_rpm_pm']; ?>"/>
      <input name="id_pm" type="hidden" id="id_pm" value="<?php echo $row_mezcla['id_pm']; ?>" /></td>
    <td id="fuente1"><input name="int_ref3_tol5_porc3_pm"  type="text"  id="int_ref3_tol5_porc3_pm" placeholder="%" size="2" required="required" value="<?php echo $row_mezcla['int_ref3_tol5_porc3_pm'] ?>"/></td>
    <td colspan="3" id="fuente1">&nbsp;</td>
    </tr>
  <tr>
    <td colspan="12" id="fuente4">&nbsp;</td>
  </tr>
<!--  <tr>
    <td colspan="10" id="fuente2"><input type="button" value="Modificar Temperaturas" onclick="mostrardiv2()" />
      <input type="button" value="Ocultar" onclick="ocultardiv2()" /></td>
  </tr>-->
  <tr>
    <td colspan="12" id="fuente1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="12">&nbsp; 
      
      </td>
  </tr>
  <tr id="tr1">
    <td colspan="12" id="dato2">
      <input type="hidden" name="MM_insert" value="MM_insert" />
      <input name="id_proceso_rp" type="hidden" id="id_proceso_rp" value="1" />
      <input name="id_op_rp" type="hidden" id="id_op_rp" value="<?php echo $row_orden_produccion['id_op']; ?>" />
      <input name="id_ref_rp" type="hidden" id="id_ref_rp" value="<?php echo $row_ref['id_ref']; ?>" />
      <input name="int_cod_ref_rp" type="hidden" id="int_cod_ref_rp" value="<?php echo $row_orden_produccion['int_cod_ref_op']; ?>" />
      <input name="version_ref_rp" type="hidden" id="version_ref_rp" value="<?php echo $row_orden_produccion['version_ref_op']; ?>" />
      <input name="kilos_op" type="hidden" id="kilos_op" value="<?php echo $row_orden_produccion['int_kilos_op']; ?>" />
      <input name="materiaPrima" id="materiaPrima" type="hidden" value=""/>  
      <input name="estado" id="estado" type="hidden" value="1" />
      <?php  //PARA VALIDAR QUE NO SEAN MAS KILOS Q LA O.P
	  for ($TK=0;$TK<=$totalRows_totalKilos-1;$TK++) { ?>
      <input name="kilos_extruido[]" type="hidden" id="kilos_extruido[]" value="<?php $tK=mysql_result($totalKilos,$TK,int_total_kilos_rp); echo $tK; ?>" />
      <?php } ?>
      <input type="button" class="botonGeneral" name="ENVIAR" id="ENVIAR" value="SIGUIENTE" onclick="enviodeFormulario()" value="GUARDAR"/><!--onClick="envio_form(this);"--></td> 
  </tr>
</table>
 
  </form>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<script type="text/javascript">
  function enviodeFormulario(){ 
    //var kilos = kiloMetroBajosExtruder() ;
     
       var resul =validaTodo(); 
       if(resul==true)
        enviodeForms(resul);
  
        
  }
 
</script>
<?php
mysql_free_result($usuario);
mysql_free_result($mezcla);
mysql_free_result($ref);
mysql_free_result($orden_produccion);
mysql_free_result($maquinas); 
mysql_free_result($totalKilos);
mysql_free_result($codigo_empleado);
?>
