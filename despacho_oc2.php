<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php

include('funciones/funciones_php.php'); 

//initialize the session
 
  session_start();
 
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
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_ordenes_compra = 20;
$pageNum_ordenes_compra = 0;
if (isset($_GET['pageNum_ordenes_compra'])) {
  $pageNum_ordenes_compra = $_GET['pageNum_ordenes_compra'];
}
$startRow_ordenes_compra = $pageNum_ordenes_compra * $maxRows_ordenes_compra;

mysql_select_db($database_conexion1, $conexion1);
$pendiente = $_GET['pendiente'];
$estado=$_GET['estado'];
$ref=$_GET['ref'];
$orden=$_GET['orden'];
$cliente=$_GET['cliente'];
$anual=$_GET['fecha'];
$mes=$_GET['mensual'];
$fecha = $anual.'-'.$mes.'-'.'01';
$autorizado=$_GET['autorizado'];
$vende=$_GET['vende'];

  if(!$_SESSION['acceso']){
     
     $soloinventario = "(Tbl_orden_compra.tipo_despacho is null or Tbl_orden_compra.tipo_despacho ='despacho') and ";
 
  }

//TODOS VACIOS
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente == '0' && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//ESTADO LLENO
if($estado != '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario b_estado_oc=$estado AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}


//REFERENCIA LLENO
if($estado == '0' && $ref != '0' && $orden == '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra, Tbl_items_ordenc WHERE $soloinventario Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io AND Tbl_items_ordenc.int_cod_ref_io = $ref AND Tbl_orden_compra.b_borrado_oc='0' AND Tbl_orden_compra.pago_pendiente='NO' ORDER BY Tbl_orden_compra.b_estado_oc, Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//ORDEN COMPRA LLENO
if($estado == '0' && $ref == '0' && $orden != '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario str_numero_oc = '$orden' AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//CLIENTE LLENO
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente != '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario id_c_oc = $cliente AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//ORDEN COMPRA Y CLIENTE LLENO
if($estado == '0' && $ref == '0' && $orden != '0' && $cliente != '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario str_numero_oc = '$orden' AND  id_c_oc = $cliente AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//REF Y CLIENTE LLENO
if($estado == '0' && $ref != '0' && $orden == '0' && $cliente != '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra, Tbl_items_ordenc WHERE $soloinventario Tbl_orden_compra.str_numero_oc =  Tbl_items_ordenc.str_numero_io AND Tbl_items_ordenc.int_cod_ref_io = $ref AND Tbl_orden_compra.id_c_oc = $cliente AND Tbl_orden_compra.b_borrado_oc='0' AND Tbl_orden_compra.pago_pendiente='NO' ORDER BY Tbl_orden_compra.b_estado_oc, Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//ESTADO Y CLIENTE LLENO
if($estado != '0' && $ref == '0' && $orden == '0' && $cliente != '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario b_estado_oc=$estado AND id_c_oc = $cliente AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//FECHA LLENO
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual != '0' && $mes != '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario fecha_ingreso_oc BETWEEN DATE_FORMAT('$fecha', '%Y-%m-01') AND DATE_FORMAT('$fecha', '%Y-%m-31') AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY fecha_autoriza DESC, id_pedido  DESC";
}
//ORDEN COMPRA, CLIENTE LLENO Y FECHA
if($estado == '0' && $ref == '0' && $orden != '0' && $cliente != '0' && $anual != '0' && $mes != '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario str_numero_oc = '$orden' AND  id_c_oc = $cliente AND fecha_ingreso_oc BETWEEN DATE_FORMAT('$fecha', '%Y-%m-01') AND DATE_FORMAT('$fecha', '%Y-%m-31') AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//REF Y FECHA LLENO
if($estado == '0' && $ref != '0' && $orden == '0' && $cliente == '0' && $anual != '0' && $mes != '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra, Tbl_items_ordenc WHERE $soloinventario Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io AND Tbl_items_ordenc.int_cod_ref_io = $ref AND Tbl_orden_compra.fecha_ingreso_oc BETWEEN DATE_FORMAT('$fecha', '%Y-%m-01') AND DATE_FORMAT('$fecha', '%Y-%m-31') AND Tbl_orden_compra.b_borrado_oc='0' AND Tbl_orden_compra.pago_pendiente='NO' ORDER BY Tbl_orden_compra.b_estado_oc, Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//REF Y AÑO 
if($estado == '0' && $ref != '0' && $orden == '0' && $cliente == '0' && $anual != '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra, Tbl_items_ordenc WHERE $soloinventario Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io AND Tbl_items_ordenc.int_cod_ref_io = $ref AND Tbl_orden_compra.fecha_ingreso_oc LIKE '%$anual%' AND Tbl_orden_compra.b_borrado_oc='0' ORDER BY Tbl_orden_compra.b_estado_oc, Tbl_orden_compra.fecha_ingreso_oc DESC";
 
}
//FECHA  Y ESTADO LLENO
if($estado != '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual != '0' && $mes != '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario b_estado_oc=$estado AND fecha_ingreso_oc BETWEEN DATE_FORMAT('$fecha', '%Y-%m-01') AND DATE_FORMAT('$fecha', '%Y-%m-31') AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//ESTADO, REF Y FECHA LLENO
if($estado != '0' && $ref != '0' && $orden == '0' && $cliente == '0' && $anual != '0' && $mes != '0' && $pendiente == '0'  && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra, Tbl_items_ordenc WHERE $soloinventario Tbl_orden_compra.b_estado_oc=$estado AND Tbl_orden_compra.str_numero_oc = Tbl_items_ordenc.str_numero_io AND Tbl_items_ordenc.int_cod_ref_io = $ref AND Tbl_orden_compra.fecha_ingreso_oc BETWEEN DATE_FORMAT('$fecha', '%Y-%m-01') AND DATE_FORMAT('$fecha', '%Y-%m-31') AND Tbl_orden_compra.b_borrado_oc='0' AND Tbl_orden_compra.pago_pendiente='NO' ORDER BY Tbl_orden_compra.b_estado_oc, Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//SOLAMENTE PENDIENTES LLENO
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente != '0' && $autorizado=='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE $soloinventario Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.b_borrado_oc='0' AND Tbl_orden_compra.pago_pendiente='NO' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//ESTADO GENERAL Y VENDEDOR LLENO
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente != '0' && $autorizado=='0' && $vende!='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra,Tbl_items_ordenc WHERE $soloinventario Tbl_orden_compra.id_pedido=Tbl_items_ordenc.id_pedido_io AND Tbl_items_ordenc.int_cantidad_rest_io $pendiente '0.00' AND Tbl_orden_compra.str_elaboro_oc='$vende' AND Tbl_orden_compra.b_borrado_oc='0' AND Tbl_orden_compra.pago_pendiente='NO' GROUP BY Tbl_orden_compra.str_numero_oc ORDER BY Tbl_orden_compra.fecha_ingreso_oc DESC";
}
//vendedor o responsable
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado=='0' && $vende!='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario str_elaboro_oc='$vende' AND b_borrado_oc='0' AND pago_pendiente='NO'  GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}

//vendedor o responsable y FECHA
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual != '0' && $mes != '0' && $pendiente == '0'  && $autorizado=='0' && $vende!='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario str_elaboro_oc='$vende' AND fecha_ingreso_oc BETWEEN DATE_FORMAT('$fecha', '%Y-%m-01') AND DATE_FORMAT('$fecha', '%Y-%m-31') AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}

//AUTORIZADO LLENO
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado!='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario b_borrado_oc='0' and autorizado = '$autorizado' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//AUTORIZADO Y VENDEDOR
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual == '0' && $mes == '0' && $pendiente == '0'  && $autorizado!='0' && $vende!='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario str_elaboro_oc='$vende' AND autorizado = '$autorizado' AND b_borrado_oc='0' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}
//FECHA Y AUTORIZADO LLENO
if($estado == '0' && $ref == '0' && $orden == '0' && $cliente == '0' && $anual != '0' && $mes != '0' && $pendiente == '0'  && $autorizado!='0' && $vende=='0')
{
  $query_ordenes_compra = "SELECT * FROM Tbl_orden_compra WHERE $soloinventario fecha_ingreso_oc BETWEEN DATE_FORMAT('$fecha', '%Y-%m-01') AND DATE_FORMAT('$fecha', '%Y-%m-31') AND b_borrado_oc='0' and autorizado = '$autorizado' AND pago_pendiente='NO' GROUP BY str_numero_oc ORDER BY id_pedido DESC";
}

$query_limit_ordenes_compra = sprintf("%s LIMIT %d, %d", $query_ordenes_compra, $startRow_ordenes_compra, $maxRows_ordenes_compra);
$ordenes_compra = mysql_query($query_limit_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);

if (isset($_GET['totalRows_ordenes_compra'])) {
  $totalRows_ordenes_compra = $_GET['totalRows_ordenes_compra'];
} else {
  $all_ordenes_compra = mysql_query($query_ordenes_compra);
  $totalRows_ordenes_compra = mysql_num_rows($all_ordenes_compra);
}
$totalPages_ordenes_compra = ceil($totalRows_ordenes_compra/$maxRows_ordenes_compra)-1;

$queryString_ordenes_compra = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_ordenes_compra") == false && 
      stristr($param, "totalRows_ordenes_compra") == false) {
      array_push($newParams, $param);
  }
}
if (count($newParams) != 0) {
  $queryString_ordenes_compra = "&" . htmlentities(implode("&", $newParams));
}
}
$queryString_ordenes_compra = sprintf("&totalRows_ordenes_compra=%d%s", $totalRows_ordenes_compra, $queryString_ordenes_compra);


$conexion = new ApptivaDB();

$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_ano = $conexion->llenaSelect('anual','','ORDER BY anual DESC'); 

$row_mensual = $conexion->llenaSelect('mensual','','ORDER BY id_mensual DESC'); 

/*$row_orden = $conexion->llenaSelect('tbl_orden_compra','','ORDER BY str_numero_oc DESC');

$row_cliente = $conexion->llenaSelect('cliente','','ORDER BY nombre_c ASC');
$row_numero = $conexion->llenaSelect('tbl_referencia','','ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC');*/

$row_vendedores = $conexion->llenaSelect('vendedor','','ORDER BY nombre_vendedor ASC');


?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <script type="text/javascript" src="js/usuario.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="AjaxControllers/updateAutorizar.js"></script>
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

    <!-- select2 -->
    <link href="select2/css/select2.min.css" rel="stylesheet"/>
    <script src="select2/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/general.css"/>

    <!-- css Bootstrap hace mas grande el formato-->
    <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

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
<body onload = "JavaScript: AutoRefresh (90000);">
    <script>
        //$(document).ready(function() { $(".busqueda").select2(); });
    </script>
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
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
                    <div class="panel-heading"><h3>INGRESAR  DESPACHOS  &nbsp;&nbsp; </h3></div>
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                 <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="despacho_listado1_oc.php">LISTADO REMISIONES</a></li>
                    <li><?php echo $_SESSION['Usuario']; ?></li>
                  </ul>
               </div> 
               <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12"> 
               </div>
             </div> 
          <!-- grid --> 
        <form action="despacho_oc2.php" method="get" name="form1" > 
          <div class="container-fluid"> 
             <h3 ><strong>FILTRAR  DESPACHOS</strong></h3>
             <div class="row" > 
             
                      <select name="pendiente" id="pendiente" style="width:100px" class="busqueda selectsMini">
                        <option value="0"<?php if (!(strcmp(0, $_GET['pendiente']))) {echo "selected=\"selected\"";} ?>>Estado General...</option>
                        <option value="="<?php if (!(strcmp('=', $_GET['pendiente']))) {echo "selected=\"selected\"";} ?>>COMPLETOS</option>
                        <option value=">"<?php if (!(strcmp('>', $_GET['pendiente']))) {echo "selected=\"selected\"";} ?>>PENDIENTES</option>
                      </select>
                      <select name="estado" id="estado" style="width:100px" class="busqueda selectsMini">
                        <option value="0"<?php if (!(strcmp(0, $_GET['estado']))) {echo "selected=\"selected\"";} ?>>Estado O.C</option>
                        <option value="1"<?php if (!(strcmp(1, $_GET['estado']))) {echo "selected=\"selected\"";} ?>>Ingresada</option>
                        <option value="2"<?php if (!(strcmp(2, $_GET['estado']))) {echo "selected=\"selected\"";} ?>>Programada</option>
                        <option value="3"<?php if (!(strcmp(3, $_GET['estado']))) {echo "selected=\"selected\"";} ?>>Remisionada</option>
                        <option value="4"<?php if (!(strcmp(4, $_GET['estado']))) {echo "selected=\"selected\"";} ?>>Fact. Parcial</option>
                        <option value="5"<?php if (!(strcmp(5, $_GET['estado']))) {echo "selected=\"selected\"";} ?>>Fact. Total</option>
                      </option>
                    </select>

                    <div class="main"> 
                       <select id='ref' name='ref' class="selectsMini">
                         <option value='0'<?php if (!(strcmp(0, $_GET['ref']))) {echo "selected=\"selected\"";} ?>>- Referencia -</option>
                       </select>
                    </div>

                    <div class="main"> 
                       <select id='orden' name='orden' class="selectsMini">
                         <option value='0'<?php if (!(strcmp(0, $_GET['orden']))) {echo "selected=\"selected\"";} ?>>- Orden de Compra -</option>
                       </select>
                    </div>

                    <div class="main"> 
                       <select id='cliente' name='cliente' class="selectsMedio">
                         <option value='0'<?php if (!(strcmp(0, $_GET['cliente']))) {echo "selected=\"selected\"";} ?>>- Cliente -</option>
                       </select>
                    </div> 
 
                    <strong >ANUAL:</strong> 
                    <select name="fecha" id="fecha" class="busqueda selectsMini" >
                    <option value="0"<?php if (!(strcmp("", $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>ANUAL</option>
                    <?php  foreach($row_ano as $row_ano ) { ?>
                    <option value="<?php echo $row_ano['anual']; ?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_ano['anual']); ?> </option>
                    <?php } ?>
                    </select>

                    <strong >MENSUAL:</strong> 
                    <select name="mensual" id="mensual" class="busqueda selectsMini" >
                    <option value="0"<?php if (!(strcmp("", $_GET['mensual']))) {echo "selected=\"selected\"";} ?>>MENSUAL</option>
                    <?php  foreach($row_mensual as $row_mensual ) { ?>
                    <option value="<?php echo $row_mensual['id_mensual']; ?>"<?php if (!(strcmp($row_mensual['id_mensual'], $_GET['mensual']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_mensual['mensual']); ?> </option>
                    <?php } ?>
                    </select> 

                     
                    <select name="autorizado" id="autorizado" style="width:100px">
                       <option value="0">Autorizadas</option>
                       <option value="SI"<?php if (!(strcmp('SI', $_GET['autorizado']))) {echo "selected=\"selected\"";} ?>>SI</option>
                       <option value="NO"<?php if (!(strcmp('NO', $_GET['autorizado']))) {echo "selected=\"selected\"";} ?>>NO</option>
                    </select>

                    <select name="vende" id="vende" class="busqueda selectsMini" >
                    <option value="0"<?php if (!(strcmp(0, $_GET['vende']))) {echo "selected=\"selected\"";} ?>>Vendedor</option>
                    <?php  foreach($row_vendedores as $row_vendedores ) { ?>
                    <option value="<?php echo $row_vendedores['nombre_vendedor']; ?>"<?php if (!(strcmp($row_vendedores['nombre_vendedor'], $_GET['vende']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_vendedores['nombre_vendedor']); ?> </option>
                    <?php } ?>
                    </select> 
           
                  </div>
                 <p></p>
                <input type="submit" class="botonGeneral" name="Submit" value="FILTRO"/>
              <hr> 

           <div class="row" >
            <div class="span6"> 
             <img src="images/falta.gif" alt="INGRESADA x O.C."title="INGRESADA O.C." border="0" style="cursor:hand;"/> ingresada
             <img src="images/f.gif" alt="FACTURADA O.C." title="FACTURADA O.C." border="0" style="cursor:hand;"/> facturado total
             <img src="images/fr.gif" alt="FACTURADA PARCIAL"title="FACTURADA PARCIAL" border="0" style="cursor:hand;"/> factura parcial
               <img src="images/r.gif" alt="REMISION O.C."title="REMISION O.C." border="0" style="cursor:hand;"/> remisionada
               <img src="images/p.gif" alt="PROGRAMADA O.C."title="PROGRAMADA O.C." border="0" style="cursor:hand;"/> programada 
            
               <img src="images/pa.gif" alt="PROGRAMADA O.C."title="PROGRAMADA O.C." border="0" style="cursor:hand;"/> En produccion
               <img src="images/salir.gif" alt="SIN AUTORIZAR" title="SIN AUTORIZAR" border="0" style="cursor:hand;" width="20" height="18" />Orden Sin autorizar
               <img src="images/accept.png" alt="AUTORIZADA" title="AUTORIZADA" border="0" style="cursor:hand;" width="20" height="18" />Orden Autorizada 
               <p></p> 
            </div>
            <div class="span3"> 
                Nota: Si la R esta en color rojo significa que tiene despachos pendientes o parciales  
          </div>
          <div class="span3"> 
                <a href="despacho_listado1_oc.php"><img src="images/r.gif" style="cursor:hand;" alt="LISTADO DE REMISIONES" title="LISTADO DE REMISIONES" border="0" /></a><a href="despacho_oc.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a>
          </div>
           </div>
      </form>
             <hr>
              <div class="row align-items-start">  
                 <div class="col" ><strong>N&deg; O.C  </strong></div>
                 <div class="col" ><strong>FECHA INGRESO </strong></div>
                 <div class="col" ><strong>CLIENTE </strong></div>
                 <div class="col" ><strong>VENDEDOR </strong></div>
                 <div class="col" ><strong>PENDIENTES </strong></div>
                 <div class="col" ><strong>ESTADO </strong></div>
                 <div class="col" ><strong>FECHA AUTORIZA </strong></div> 
                 <div class="col" ><strong>AUTORIZAR SALIDA  </strong></div>   
              </div> 
             <?php do{  ?>
               <?php 
               $id_pedido=$row_ordenes_compra['id_pedido'];
               $restante = $conexion->llenarCampos('tbl_items_ordenc'," WHERE id_pedido_io='$id_pedido' ", "", "SUM(int_cantidad_rest_io) AS restante");

                  if($row_ordenes_compra['autorizado']=='SI' &&  ($row_ordenes_compra['b_estado_oc'] > 1 || $restante['restante'] > 0.00) ){  
                    $urls = "<a href=despacho_items_oc.php?str_numero_r=".$row_ordenes_compra['str_numero_oc']." target=_top  style=text-decoration:none; color:#000000 >"; 
                  }else{
                    $urls = "<a href=despacho_items_oc.php?str_numero_r=".$row_ordenes_compra['str_numero_oc']." target=_top  style=text-decoration:none; color:#000000 >"; 
                  }
              ?>

            <div class="row celdaborde1" > 
              <div class="col" id="fondo_2"onMouseOver="uno(this,'8C8C9F');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                <p><?php echo $urls;?><strong><?php echo $row_ordenes_compra['str_numero_oc']; ?></strong></a></p>
              </div> 
              <div class="col" id="fondo_2">
                <p><?php echo $urls;?><?php echo $row_ordenes_compra['fecha_ingreso_oc']; ?></a></p>
              </div>
              <div class="col" id="fondo_2">
                <p><?php echo $urls;?>
                              <?php 
                              $nit_c=$row_ordenes_compra['str_nit_oc'];
                              $sqln="SELECT * FROM cliente WHERE nit_c='$nit_c'"; 
                              $resultn=mysql_query($sqln); 
                              $numn=mysql_num_rows($resultn); 
                              if($numn >= '1') 
                               { $nit_cliente_c=mysql_result($resultn,0,'nombre_c'); echo utf8_encode($nit_cliente_c); }
                             else { echo "";  } ?>
                           </a></p>
              </div>
              <div class="col" id="fondo_2">
                <p><?php echo $urls;?><?php echo $row_ordenes_compra['str_elaboro_oc']; ?></a></p>
              </div>
              <div class="col" id="fondo_2">
                <p><?php  
                       if( $restante['restante'] > 0.00 ) : ?>
                           <img src="images/falta3.gif" alt="CANTIDAD PENDIENTES" width="20" height="18" style="cursor:hand;" title="CANTIDAD PENDIENTES" border="0"/>  
                        <?php elseif($restante['restante'] == '') : ?>
                          <em>sin items</em>
                        <?php   else:?>
                           <img src="images/cumple.gif" alt="OK" width="20" height="18" style="cursor:hand;" title="OK" border="0"/> 
                        <?php endif; ?></p>
              </div>
              <div class="col" id="fondo_2">
                <p><?php 
                $id_pedido=$row_ordenes_compra['id_pedido'];
                $estado=$row_ordenes_compra['b_estado_oc'];
                $sqlrem="SELECT int_cantidad_rest_io FROM Tbl_items_ordenc WHERE id_pedido_io='$id_pedido' GROUP BY int_cantidad_rest_io"; 
                $resultrem=mysql_query($sqlrem); 
                $numrem=mysql_num_rows($resultrem);
                if($numrem >= '1'){
                 $cant_rest=mysql_result($resultrem,0,'int_cantidad_rest_io');
               }
               if($estado=='3' && $cant_rest != ''){?><img src="images/rr.gif" alt="REMISION CON RESTANTES"title="REMISION CON RESTANTES" border="0" style="cursor:hand;"/><?php } 
               else if($estado=='3' && ($cant_rest == '' )){?><img src="images/r.gif" alt="REMISION O.C."title="REMISION O.C." border="0" style="cursor:hand;"/><?php }   
               else if($estado=='1'){ ?><?php echo $urls;?><img src="images/falta.gif" alt="INGRESADA O.C."title="INGRESADA O.C." border="0" style="cursor:hand;"/></a><?php } 
               $id_oc=$row_ordenes_compra['str_numero_oc'];
               $sqlmp="SELECT Tbl_items_ordenc.str_numero_io,Tbl_orden_produccion.str_numero_oc_op,Tbl_items_ordenc.int_cod_ref_io,Tbl_orden_produccion.int_cod_ref_op AS existe_op, Tbl_orden_produccion.b_borrado_op 
               FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.str_numero_io=$id_oc AND Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
               AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_borrado_op='0'";
               $resultmp= mysql_query($sqlmp);
               $nump = mysql_num_rows($resultmp);
               if($nump >='1')
               { 
                 $existe_op = mysql_result($resultmp,0,'existe_op');
               }else {$existe_op="0";}    
               if($estado=='2' && $existe_op =='0'){ ?><img src="images/p.gif" alt="PROGRAMADA O.C."title="PROGRAMADA O.C." border="0" style="cursor:hand;"/><?php }else 
               if($estado=='2' && $existe_op > '0'){ ?><img src="images/pa.gif" alt="EN PRODUCCION"title="EN PRODUCCION" border="0" style="cursor:hand;"/><?php } 
               else if($estado=='4' ){ ?><img src="images/fr.gif" alt="FACTURADA PARCIAL"title="FACTURADA PARCIAL" border="0" style="cursor:hand;"/><?php }
               else if($estado=='5'){ ?><img src="images/f.gif" alt="FACTURADA O.C." title="FACTURADA O.C." border="0" style="cursor:hand;"/><?php }
               else{echo "";}  
               ?></p>
              </div>
              <div class="col" id="fondo_2">
                <p> <?php echo substr($row_ordenes_compra['fecha_autoriza'],0,10);?> </p>
              </div>
              <div class="col" id="fondo_2">
                <p><?php if($row_ordenes_compra['autorizado']=='SI'): ?>
                  <img src="images/accept.png" alt="AUTORIZADA" title="AUTORIZADA" border="0" style="cursor:hand;" width="20" height="18" /> 
                  <?php else: ?>
                    <a href="javascript:updateAutorizar('Autorizar',<?php echo $row_ordenes_compra['id_pedido']; ?>,'despacho_oc2.php','<?php echo $row_ordenes_compra['str_numero_oc']; ?>')" ><img src="images/salir.gif" alt="AUTORIZAR" title="AUTORIZAR" border="0" style="cursor:hand;" width="20" height="18" /></a>
                 <!-- <img src="images/salir.gif" alt="SIN AUTORIZAR" title="SIN AUTORIZAR" border="0" style="cursor:hand;" width="20" height="18" /> -->
                </p> 
              <?php endif; ?> 
              </div>
              <?php 
              //Alert de oc autorizadas 
                 $nuevafecha = restarMes(date('Y-m-d'), 5); 
                   $row_orden_autorizadas = $conexion->llenarCampos('tbl_orden_compra'," WHERE autorizado='SI' AND fecha_ingreso_oc > '$nuevafecha' AND str_numero_oc='".$row_ordenes_compra['str_numero_oc']."' ", "ORDER BY id_pedido DESC", "str_numero_oc");       
                         if($row_orden_autorizadas['str_numero_oc'])
                             $arrayName .=  $row_orden_autorizadas['str_numero_oc'].', ';         
               ?>    
              <?php //if($arrayName!='' || $restante['restante'] > 0.00):  ?>
                    <!-- <script type="text/javascript"> var ocsAutor = <?php echo json_encode($arrayName); ?>; swal("Alerta!", "Ordenes Autorizadas y/o con Pendientes: "+ocsAutor, "error");</script> -->
                <?php //endif; ?>
 
              
            </div>
            <?php } while ($row_ordenes_compra = mysql_fetch_assoc($ordenes_compra)); ?>
          </div> 
             <!-- tabla para paginacion opcional -->
              <table border="0" width="50%" align="center">
               <tr>
                 <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra > 0) { // Show if not first page ?>
                   <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, 0, $queryString_ordenes_compra); ?>">Primero</a>
                   <?php } // Show if not first page ?>
                 </td>
                 <td width="31%" id="dato2"><?php if ($pageNum_ordenes_compra > 0) { // Show if not first page ?>
                   <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, max(0, $pageNum_ordenes_compra - 1), $queryString_ordenes_compra); ?>">Anterior</a>
                   <?php } // Show if not first page ?>
                 </td>
                 <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra < $totalPages_ordenes_compra) { // Show if not last page ?>
                   <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, min($totalPages_ordenes_compra, $pageNum_ordenes_compra + 1), $queryString_ordenes_compra); ?>">Siguiente</a>
                   <?php } // Show if not last page ?>
                 </td>
                 <td width="23%" id="dato2"><?php if ($pageNum_ordenes_compra < $totalPages_ordenes_compra) { // Show if not last page ?>
                   <a href="<?php printf("%s?pageNum_ordenes_compra=%d%s", $currentPage, $totalPages_ordenes_compra, $queryString_ordenes_compra); ?>">&Uacute;ltimo</a>
                   <?php } // Show if not last page ?>
                 </td>
               </tr>
             </table>


   </div> <!-- contenedor -->

 </div>
</div>
</div>
</div>
</td>
</tr>
</table>
</div> 
</div>

<!-- js Bootstrap-->
<script src="bootstrap-4/js/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

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
 
</script>

<script>

 $(document).ready(function(){  

    $('#ref').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"*",
                    var2:"tbl_referencia",
                    var3:"",
                    var4:"ORDER BY CONVERT(cod_ref, SIGNED INTEGER) DESC",
                    var5:"cod_ref",
                    var6:"cod_ref"
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

    $('#orden').select2({ 
        ajax: {
            url: "select3/proceso.php",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    palabraClave: params.term, // search term
                    var1:"*",
                    var2:"tbl_orden_compra",
                    var3:"",
                    var4:"  ORDER BY str_numero_oc DESC",
                    var5:"str_numero_oc",
                    var6:"str_numero_oc"
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
                    var1:"*",
                    var2:"cliente",
                    var3:"",
                    var4:"ORDER BY nombre_c ASC",
                    var5:"id_c",
                    var6:"nombre_c"
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


</script>
<?php
mysql_free_result($usuario);

?>