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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php
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

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$maxRows_orden_produccion = 20;
$pageNum_orden_produccion = 0;
if (isset($_GET['pageNum_orden_produccion'])) {
  $pageNum_orden_produccion = $_GET['pageNum_orden_produccion'];
}
$startRow_orden_produccion = $pageNum_orden_produccion * $maxRows_orden_produccion;

mysql_select_db($database_conexion1, $conexion1);
$op = $_GET['op'];
$id_ref = $_GET['id_ref'];
$cliente = $_GET['cliente'];
$proceso = $_GET['proceso'];
$mes = $_GET['mes'];
$anyo = $_GET['anyo'];
//Filtra todos vacios
if($op== '0' && $id_ref=='0' && $cliente == '0' && $proceso == 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY id_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0'" );
}
//Filtra proceso y año
if($op== '0' && $id_ref=='0' && $cliente == '0' && $proceso != 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY id_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0'  AND b_estado_op='$proceso'" );
}
//Filtra proceso y año, op
if($op!= '0' && $id_ref=='0' && $cliente == '0' && $proceso != 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY id_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND id_op='$op' AND b_estado_op='$proceso'" );
}
//Filtra proceso y año, ref
if($op== '0' && $id_ref!='0' && $cliente == '0' && $proceso != 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY id_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND int_cod_ref_op='$id_ref' AND b_estado_op='$proceso'" );
}
//FILTRA CLIENTE LLENO Y AÑO, PROCESO
if($op == '0' && $id_ref=='0' && $cliente != '0' && $proceso != 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND int_cliente_op='$cliente'" );
}


//FILTRA OP LLENO
if($op != '0' && $id_ref=='0' && $cliente == '0' && $proceso == 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY id_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND id_op='$op'" );
}
//FILTRA PROCESO LLENO
if($op == '0' && $id_ref=='0' && $cliente == '0' && $proceso != '0' && $proceso != 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND b_estado_op='$proceso'" );
}
//FILTRA OP LLENO Y AÑO TODOS
if($op != '0' && $id_ref=='0' && $cliente == '0'  && $proceso != '0' && $proceso == 'todos' && ($mes == '0' && $anyo == '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY id_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND id_op='$op'" );
}
//FILTRA REF LLENO Y AÑO TODOS
if($op == '0' && $id_ref!='0' && $cliente == '0'  && $proceso != '0' && $proceso == 'todos' && ($mes == '0' && $anyo == '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND int_cod_ref_op='$id_ref'" );
}
//FILTRA CLIENTE LLENO Y AÑO TODOS
if($op == '0' && $id_ref=='0' && $cliente != '0'  && $proceso != '0' && $proceso == 'todos' && ($mes == '0' && $anyo == '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND int_cliente_op='$cliente'" );
}
//FILTRA PROCESO LLENO Y AÑO TODOS
if($op == '0' && $id_ref=='0' && $cliente == '0' && $proceso != '0' && ($mes == '0' && $anyo == '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND b_estado_op='$proceso'" );
}
//FILTRA AÑO TODOS
if($op == '0' && $id_ref=='0' && $cliente == '0'  && $proceso != '0' && $proceso == 'todos' && ($mes == '0' && $anyo == '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' " );
}
//&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&   FILTROS SI UTILIZA AÑO &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
//FILTRA REF LLENO SIN MES CON AÑO
if($op == '0' && $id_ref!='0' && $cliente == '0'  && $proceso != '0' && $proceso == 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND int_cod_ref_op='$id_ref' AND YEAR(fecha_registro_op)='".$anyo."' " );
}
//FILTRA CLIENTE LLENO SIN MES CON AÑO
if($op == '0' && $id_ref=='0' && $cliente != '0'  && $proceso != '0' && $proceso == 'todos' && ($mes == '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND int_cliente_op='$cliente' AND YEAR(fecha_registro_op)='".$anyo."' " );
}
//FILTRA REF LLENO CON MES
if($op == '0' && $id_ref!='0' && $cliente == '0'  && $proceso != '0' && $proceso == 'todos' && ($mes != '0' && $anyo == '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND int_cod_ref_op='$id_ref' AND MONTH(fecha_registro_op)='".$mes."' " );
}
//FILTRA REF LLENO CON MES Y AÑO
if($op == '0' && $id_ref!='0' && $cliente == '0'  && $proceso != '0' && $proceso == 'todos' && ($mes != '0' && $anyo != '0'))
{
$row_orden_produccion = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY fecha_registro_op DESC","",$maxRows_orden_produccion,$pageNum_orden_produccion," WHERE b_borrado_op='0' AND int_cod_ref_op='$id_ref' AND MONTH(fecha_registro_op)='".$mes."' AND YEAR(fecha_registro_op)='".$anyo."'  " );
}
 


if (isset($_GET['totalRows_orden_produccion'])) {
  $totalRows_orden_produccion = $_GET['totalRows_orden_produccion'];
} else {
  $totalRows_orden_produccion = $conexion->conteo('Tbl_orden_produccion'); 
} 
$totalPages_orden_produccion = floor($totalRows_orden_produccion/$maxRows_orden_produccion)-1;
 
$queryString_orden_produccion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_orden_produccion") == false && 
        stristr($param, "totalRows_orden_produccion") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_orden_produccion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_orden_produccion = sprintf("&totalRows_orden_produccion=%d%s", $totalRows_orden_produccion, $queryString_orden_produccion);

$row_listado_op = $conexion->llenaSelect('Tbl_orden_produccion','','ORDER BY Tbl_orden_produccion.id_op DESC');
$row_clientes = $conexion->llenaSelect('cliente','','ORDER BY cliente.nombre_c ASC');
$row_proceso = $conexion->llenaSelect('tipo_procesos','','ORDER BY tipo_procesos.nombre_proceso ASC');
$row_referencias = $conexion->llenaSelect('Tbl_referencia','','ORDER BY id_ref desc');
$row_mensual = $conexion->llenaSelect('mensual','','ORDER BY id_mensual ASC');

$row_anual = $conexion->llenaSelect('anual','','ORDER BY id_anual DESC');
  
 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>

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

  <!-- select2 -->
  <!-- <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script> -->

   <!-- Select3 Nuevo -->
 <meta charset="UTF-8">
 <!-- jQuery -->
 <script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>

 <!-- select2 css -->
 <link href='select3/assets/plugin/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

 <!-- select2 script -->
 <script src='select3/assets/plugin/select2/dist/js/select2.min.js'></script>
 <!-- Styles -->
 <link rel="stylesheet" href="select3/assets/css/style.css">
 <!-- Fin Select3 Nuevo -->

</head>
<body onload = "JavaScript: AutoRefresh (80000);">
<div align="center">
    <script>
      //$(document).ready(function() { $(".busqueda").select2(); });
  </script>
  <div align="center">
    <table style="width: 80%"><!-- id="tabla1" -->
      <tr>
       <td align="center">
         <div class="row-fluid">
           <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
             <div class="panel panel-primary">
              <div class="panel-heading" align="left" ></div><!--color azul-->
              <div class="row" >
                  <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
              </div>
              <div class="panel-heading" align="left" ></div><!--color azul-->
               <div id="cabezamenu">
                <ul id="menuhorizontal">
                  <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                  <li><a href="menu.php">MENU PRINCIPAL</a></li>
                </ul>
             </div> 
             <div class="panel-body">
               <br> 
               <div class="container">
                <div class="row">
                  <div class="span12"> 
             </div>
           </div>
<form action="produccion_ordenes_produccion_listado2.php" method="get" name="form1">
<table >
<tr>
<td id="titulo2">LISTADO DE  ORDENES DE PRODUCCION</td>
</tr>
<tr>
  <td id="titulo2">&nbsp;</td>
  </tr>
<tr>
  <td id="titulo1">
    <div class="row">
     <strong >OP: </strong> 
          <select id='op' name='op' class="selectsMini">
            <option value='0'<?php if (!(strcmp(0, $_GET['op']))) {echo "selected=\"selected\"";} ?>>- O.P -</option>
          </select>
     
     <strong >REF: </strong>
         <select id='id_ref' name='id_ref' class="selectsMini">
           <option value='0'<?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>- REF: -</option>
         </select>
     
     <strong >CLIENTES: </strong>
         <select id='cliente' name='cliente' class="selectsGrande">
           <option value='0'<?php if (!(strcmp(0, $_GET['cliente']))) {echo "selected=\"selected\"";} ?>>- CLIENTE -</option>
         </select>
    <strong >PROCESO: </strong>
      <select name="proceso" id="proceso" class="busqueda selectsMini">
         <option value="todos"<?php if (!(strcmp("todos", $_GET['proceso']))) {echo "selected=\"selected\"";} ?>>PROCESO</option>
         <option value="0"<?php if (!(strcmp("0", $_GET['proceso']))) {echo "selected=\"selected\"";} ?>>Ingresadas</option>
             <?php  foreach($row_proceso as $row_proceso ) { ?>
          <option value="<?php echo $row_proceso['id_tipo_proceso']; ?>"<?php if (!(strcmp($row_proceso['id_tipo_proceso'], $_GET['proceso']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_proceso['nombre_proceso']); ?> 
        </option>
      <?php } ?>
      </select>

    <strong >MES: </strong> 
         <select id='mes' name='mes' class="" onchange="consultaPorcentajesOpList();">
            <option value="0">MES</option>
                  <?php  foreach($row_mensual as $row_mensual ) { ?>
               <option value="<?php echo $row_mensual['id_mensual']; ?>"<?php if (!(strcmp($row_mensual['id_mensual'], $_GET['mes']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_mensual['mensual']); ?> </option>
           <?php } ?>
         </select>

    <strong >AÑO: </strong> 
         <select id='anyo' name='anyo' class="" onchange="consultaPorcentajesOpList();">
              <option value="0">TODOS</option>
                  <?php  foreach($row_anual as $row_anual ) { ?>
               <option value="<?php echo $row_anual['anual']; ?>"<?php if (!(strcmp($row_anual['anual'], $_GET['anyo']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anual['anual']); ?> </option>
           <?php } ?>
         </select>
  </div>
    </td>
</tr>
<tr>
  <td id="titulo2"><input class="botonUpdate" type="submit" name="consultar" id="consultar" value="consultar" onClick="consultaPorcentajesOpList();"></td>
</tr>
<tr>
  <td  nowrap="nowrap" id="talla1">EXT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELL&nbsp;&nbsp; DESPERDICIOS</td>
</tr>
<tr>
  <td>
    <input id="extruder" name="extruder" type="text" value="0" readonly="readonly" style="width:40px"/> 
    <input id="impre" name="impre" type="text" value="0" readonly="readonly" style="width:40px"/> 
    <input id="sellado" name="sellado" type="text" value="0" readonly="readonly" style="width:40px"/> = 
    <input id="desperdiciototal" name="desperdiciototal" type="text" value="0" min="0" max="50"  style="width:40px" readonly="readonly"onchange="consultaPorcentajesOpList();"/> % 
  </td> 
</tr>
</table>
<table >
<tr>
    <td id="dato1"><img src="images/falta.gif" alt="O.P INGRESADA"title="O.P INGRESADA" border="0" style="cursor:hand;"/>Ingresada</td>
    <td id="dato1"><img src="images/extruir.gif" alt="O.P EXTRUSION" title="O.P EXTRUSION" width="22" height="20" border="0" style="cursor:hand;"/>Extrusion</td>
    <td id="dato1"><img src="images/imprimir.gif" alt="O.P IMPRESION"title="O.P IMPRESION" width="22" height="20" border="0" style="cursor:hand;"/>Impresion</td>
    <td id="dato1"><img src="images/refilado.gif" alt="O.P REFILADO"title="O.P REFILADO" width="22" height="20" border="0" style="cursor:hand;"/>Refilado</td>
    <td id="dato1"><img src="images/sellar.gif" alt="O.P SELLADO"title="O.P SELLADO" width="22" height="20" border="0" style="cursor:hand;"/>Sellado</td>
    <td id="dato1"><img src="images/accept.png" alt="O.P SELLADO"title="O.P SELLADO" width="22" height="20" border="0" style="cursor:hand;"/>Finalizada</td>
    <td id="dato1">&nbsp;</td>
    <td id="dato1">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" id="dato1">Nota: si en el estado aparece la letra 'E', quiere decir que falta ingresarle la mezcla y caracteristica de la referencia en Extrusion.</td>
    <td id="dato1">&nbsp;</td>
    <td colspan="3" id="dato2"><a href="produccion_op_interna.php"><img src="images/mas_r.gif" alt="ADD O.P INTERNA" title="ADD O.P INTERNA" border="0" style="cursor:hand;"/></a><a href="produccion_ordenes_produccion_listado_inactivo.php" target="_top"><img src="images/i.gif" alt="O.P. INACTIVAS"title="O.P INACTIVAS" border="0" style="cursor:hand;"/></a><a href="produccion_op_estados.php"><img src="images/p.gif" style="cursor:hand;" alt="LISTADO PROGRAMADAS" title="LISTADO PROGRAMADAS" border="0" /></a><a href="produccion_op_ordenconsultar.php"><img src="images/accept.png" style="cursor:hand;" alt="O.P FINALIZADAS" title="O.P FINALIZADAS" border="0" /></a> <a href="produccion_ordenes_produccion_listado.php"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
    </tr>  
  <tr id="tr1">
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>    
    <td nowrap="nowrap"id="titulo4">FECHA INGRESO</td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">RESPONSABLE</td>
    <td nowrap="nowrap"id="titulo4">ESTADO</td>
  </tr>
   <?php foreach($row_orden_produccion as $row_orden_produccion) {  ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td nowrap="nowrap"id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_orden_produccion['id_op']; ?></strong></a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['int_cod_ref_op']; ?></a></td>
      <td nowrap="nowrap" id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['version_ref_op']; ?></a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['fecha_registro_op']; ?></a></td>
      <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000">
        <?php 
	$nit_c=$row_orden_produccion['int_cliente_op'];
	$sqln="SELECT * FROM cliente WHERE id_c='$nit_c'"; 
	$resultn=mysql_query($sqln); 
	$numn=mysql_num_rows($resultn); 
	if($numn >= '1') 
	{ $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
	else { echo "";	} ?>
      </a></td>
      <td nowrap="nowrap"id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['str_responsable_op']; ?></a></td>
      <td id="dato2">
	<?php 
	$id_op=$row_orden_produccion['id_op'];
	$sqlop="SELECT * FROM Tbl_orden_produccion, Tbl_caract_proceso WHERE Tbl_orden_produccion.id_op='$id_op' AND  Tbl_orden_produccion.int_cod_ref_op=Tbl_caract_proceso.id_cod_ref_cp AND Tbl_caract_proceso.id_proceso='1' AND Tbl_orden_produccion.b_borrado_op='0'"; 
	$resultop=mysql_query($sqlop); 
	$numop=mysql_num_rows($resultop);			
	if($numop >= '1'){		
	$estado = mysql_result($resultop, 0, 'b_estado_op');
	$id_ref_op = mysql_result($resultop, 0, 'id_ref_op'); 
	}else { ?>
      <?php 
	$id_ref_op=$row_orden_produccion['id_ref_op'];
	$sqlm="SELECT * FROM Tbl_produccion_mezclas WHERE Tbl_produccion_mezclas.id_ref_pm='$id_ref_op'"; 
	$resultm=mysql_query($sqlm); 
	$numm=mysql_num_rows($resultm);			
	if($numm >= '1'){		
	$id_pm = mysql_result($resultm, 0, 'id_pm');
; 
	?>    
    
    <a href="produccion_caract_extrusion_add.php?id_pm=<?php echo $id_pm;?>&amp;id_ref=<?php echo $id_ref_op;?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/e.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUSION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUSION" border="0" /></a><?php } }
    if ($numop >= '1' && $estado=='0') {?>
    <a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/falta.gif" alt="O.P INGRESADA "title="O.P INGRESADA" border="0" style="cursor:hand;"/></a><?php }else if ($numop >= '1' && $estado=='1') {?>
    <a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/extruir.gif" width="28" height="18"alt="O.P EXTRUIDA "title="O.P EXTRUIDA" border="0" style="cursor:hand;"/></a>
  <?php }else if ($numop >= '1' && $estado=='2') {?>
    <a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/imprimir.gif" width="28" height="20" alt="O.P IMPRESA"title="O.P IMPRESA" border="0" style="cursor:hand;"/></a>
  <?php }	else if ($numop >= '1' && $estado=='3') {?>
    <a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/refilado.gif" width="28" height="20" alt="O.P REFILADO"title="O.P REFILADO" border="0" style="cursor:hand;"/></a>
  <?php }	else if ($numop >= '1' && $estado=='4') {?>
    <a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/sellar.gif" width="28" height="20" alt="O.P SELLADO"title="O.P SELLADO" border="0" style="cursor:hand;"/></a>
  <?php }else if ($numop >= '1' && $estado=='5') {?>
    <a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op'];?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/accept.png" style="cursor:hand;" alt="O.P FINALIZADAS" title="O.P FINALIZADAS" border="0" /></a>
    <?php }	?></td>
    </tr>
    <?php } ?>
</table>
</form>
<br>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, 0, $queryString_orden_produccion); ?>">Primero</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="31%" id="dato2"><?php if ($pageNum_orden_produccion > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, max(0, $pageNum_orden_produccion - 1), $queryString_orden_produccion); ?>">Anterior</a>
          <?php } // Show if not first page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion < $totalPages_orden_produccion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, min($totalPages_orden_produccion, $pageNum_orden_produccion + 1), $queryString_orden_produccion); ?>">Siguiente</a>
          <?php } // Show if not last page ?>
    </td>
    <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion < $totalPages_orden_produccion) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, $totalPages_orden_produccion, $queryString_orden_produccion); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
    </td>
  </tr>
</table>     
    </td>
   </tr> 
 </div> <!-- contenedor -->

  </div>
 </div>
 </div>
 </div>
 </td>
 </tr>
 </table> 
 </div>
</body>
</html>

<script>
 $(document).ready(function(){  
 
        $('#op').select2({ 
            ajax: {
                url: "select3/proceso.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        palabraClave: params.term, // search term
                        var1:"id_op",//campo normal para usar
                        var2:"tbl_orden_produccion",//tabla
                        var3:"",//where
                        var4:"ORDER BY id_op DESC",
                        var5:"id_op",//clave
                        var6:"id_op"//columna a buscar
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });


        $('#id_ref').select2({ 
            ajax: {
                url: "select3/proceso.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        palabraClave: params.term, // search term
                        var1:"cod_ref",//campo normal para usar
                        var2:"tbl_referencia",//tabla
                        var3:" estado_ref='1'",//where
                        var4:"ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC",
                        var5:"cod_ref",//clave
                        var6:"cod_ref"//columna a buscar
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });


    $('#cliente').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"id_c",//campo normal para usar
                    var2:"cliente",//tabla
                    var3:"",//where
                    var4:"ORDER BY nombre_c ASC",
                    var5:"id_c",//clave
                    var6:"nombre_c"//columna a buscar
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            cache: true
        }
    });

  });

 $(document).ready(function(){

var fecha = new Date();
var anyolis = fecha.getFullYear(); 

 var meslis = "<?php echo $_GET['mes']; ?>";
 var anyolisphp = "<?php echo $_GET['anyo']; ?>";
 var ref = "<?php echo $_GET['id_ref']; ?>";
 var ref = ref =='' ? $("#id_ref").val() : ref;
 var ops = "<?php echo $_GET['op']; ?>"
 var ops = ops =='' ? $("#op").val() : ops;
 var anyolis = anyolisphp=='' ? anyolis :  anyolisphp;
 var cliente = "<?php echo $_GET['cliente']; ?>"
 var cliente = cliente =='' ? $("#cliente").val() : cliente;
   if(meslis!='0' || anyolis!='0' || anyolis=='0' || ref !=0 || ops !=0 || cliente!=0){
    
     consultaPorcentajesOpList(meslis,anyolis,ref,ops,cliente); 

   }
  });
</script>

<?php
mysql_free_result($usuario);

mysql_free_result($orden_produccion);

mysql_free_result($listado_op);

mysql_free_result($clientes);

mysql_free_result($proceso);

mysql_close($conexion1);
?>