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
$conexion = new ApptivaDB();

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

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$maxRows_orden_produccion = 20;
$pageNum_orden_produccion = 0;
if (isset($_GET['pageNum_orden_produccion'])) {
  $pageNum_orden_produccion = $_GET['pageNum_orden_produccion'];
}
$startRow_orden_produccion = $pageNum_orden_produccion * $maxRows_orden_produccion;

mysql_select_db($database_conexion1, $conexion1);
$var1 = $_GET['op'];
$var2 = $_GET['id_ref'];
$anyo = $_GET['anyo'];
$var3 = $_GET['mes'];
$maquina = $_GET['maquina'];
//FILTRA VACIOS
if($var1 == '0' && $var2 == '0' && $var3 == '0' && $anyo == '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA OP LLENO
if($var1 != '0' && $var2 == '0' && $var3 == '0' && $anyo == '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_op_rp='$var1' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA OP, AÑO LLENO
if($var1 != '0' && $var2 == '0' && $var3 == '0' && $anyo != '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_op_rp='$var1' AND YEAR(fecha_ini_rp)='$anyo' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA OP, MES LLENO
if($var1 != '0' && $var2 == '0' && $var3 != '0' && $anyo == '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_op_rp='$var1' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA OP, AÑO, MES LLENO
if($var1 != '0' && $var2 == '0' && $var3 != '0' && $anyo != '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_op_rp='$var1' AND YEAR(fecha_ini_rp)='$anyo' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA REF LLENO
if($var1 == '0' && $var2 != '0' && $var3 == '0' && $anyo == '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_ref_rp='$var2' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA REF, AÑO LLENO
if($var1 == '0' && $var2 != '0' && $var3 == '0' && $anyo != '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_ref_rp='$var2' AND YEAR(fecha_ini_rp)='$anyo' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA REF, MES LLENO
if($var1 == '0' && $var2 != '0' && $var3 != '0' && $anyo == '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_ref_rp='$var2' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA REF, AÑO, MES LLENO
if($var1 == '0' && $var2 != '0' && $var3 != '0' && $anyo != '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE id_ref_rp='$var2' AND YEAR(fecha_ini_rp)='$anyo' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA AÑO
if($var1 == '0' && $var2 == '0' && $var3 == '0' && $anyo != '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE YEAR(fecha_ini_rp)='$anyo' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA MES AÑO
if($var1 == '0' && $var2 == '0' && $var3 != '0' && $anyo != '0' && $maquina =='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE YEAR(fecha_ini_rp)='$anyo' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}

//FILTRA MAQUINA LLENO
if($var1 == '0' && $var2 == '0' && $var3 == '0' && $anyo == '0' && $maquina !='0')
{
$query_orden_produccion="SELECT * FROM tbl_reg_produccion WHERE str_maquina_rp='$maquina' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA MAQUINA, AÑO LLENO
if($var1 == '0' && $var2 == '0' && $var3 == '0' && $anyo != '0' && $maquina !='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE str_maquina_rp='$maquina' AND YEAR(fecha_ini_rp)='$anyo' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA MAQUINA, MES LLENO
if($var1 == '0' && $var2 == '0' && $var3 != '0' && $anyo == '0' && $maquina !='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE str_maquina_rp='$maquina' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}
//FILTRA MAQUINA, AÑO, MES LLENO
if($var1 == '0' && $var2 == '0' && $var3 != '0' && $anyo != '0' && $maquina !='0')
{
$query_orden_produccion = "SELECT * FROM tbl_reg_produccion WHERE str_maquina_rp='$maquina' AND YEAR(fecha_ini_rp)='$anyo' AND MONTH(fecha_ini_rp)='$var3' AND id_proceso_rp='1' ORDER BY id_op_rp DESC";
}

$query_limit_orden_produccion = sprintf("%s LIMIT %d, %d", $query_orden_produccion, $startRow_orden_produccion, $maxRows_orden_produccion);
$orden_produccion = mysql_query($query_limit_orden_produccion, $conexion1) or die(mysql_error());
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);

if (isset($_GET['totalRows_orden_produccion'])) {
  $totalRows_orden_produccion = $_GET['totalRows_orden_produccion'];
} else {
  $all_orden_produccion = mysql_query($query_orden_produccion);
  $totalRows_orden_produccion = mysql_num_rows($all_orden_produccion);
}
$totalPages_orden_produccion = ceil($totalRows_orden_produccion/$maxRows_orden_produccion)-1;

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

mysql_select_db($database_conexion1, $conexion1);
$query_lista_op = "SELECT * FROM Tbl_orden_produccion WHERE Tbl_orden_produccion.b_borrado_op='0' AND Tbl_orden_produccion.id_op IN (SELECT TblExtruderRollo.id_op_r FROM TblExtruderRollo WHERE TblExtruderRollo.id_op_r = Tbl_orden_produccion.id_op) ORDER BY Tbl_orden_produccion.id_op DESC";
$lista_op = mysql_query($query_lista_op, $conexion1) or die(mysql_error());
$row_lista_op = mysql_fetch_assoc($lista_op);
$totalRows_lista_op = mysql_num_rows($lista_op);

mysql_select_db($database_conexion1, $conexion1);
$query_ref_op = "SELECT id_ref, cod_ref FROM Tbl_referencia order by n_egp_ref desc";
$ref_op = mysql_query($query_ref_op, $conexion1) or die(mysql_error());
$row_ref_op = mysql_fetch_assoc($ref_op);
$totalRows_ref_op = mysql_num_rows($ref_op);

/*mysql_select_db($database_conexion1, $conexion1);
$query_mensual = "SELECT * FROM mensual ORDER BY id_mensual ASC";
$mensual = mysql_query($query_mensual, $conexion1) or die(mysql_error());
$row_mensual = mysql_fetch_assoc($mensual);
$totalRows_mensual = mysql_num_rows($mensual);*/

$row_maquina = $conexion->llenaSelect('maquina','WHERE proceso_maquina=1','ORDER BY codigo_maquina ASC');

$row_mensual = $conexion->llenaSelect('mensual','','ORDER BY id_mensual ASC');

$row_anual = $conexion->llenaSelect('anual','','ORDER BY id_anual DESC');

 ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

<link rel="stylesheet" type="text/css" href="css/desplegable.css" />
<link rel="stylesheet" type="text/css" href="css/general.css"/>
<script type="text/javascript" src="AjaxControllers/js/envioListado.js"></script>

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

  <!-- css Bootstrap hace mas grande el formato-->
    <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
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
                     
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                 <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="produccion_registro_extrusion_listado.php">EXTRUSION</a></li>
                  </ul>
               </div> 
               <div class="panel-body">
                 <br> 
                 <div >
                  <div class="row">
                    <div class="span12"> 
               </div>
             </div>
             <form action="produccion_registro_extrusion_listado_add2.php" method="get" name="form1" id="form1">
              <table class="table table-bordered table-sm">
                <tr>
                  <td colspan="4" id="fuente2">
                    REGISTRO DE ORDENES DE PRODUCCION EXTRUIDAS
                  </td>
                </tr>
                <tr>
                  <td colspan="2" id="fuente3"><select class='busqueda selectsMini' name="op" id="op" >
                    <option value="0">O.P.</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_lista_op['id_op']?>"><?php echo $row_lista_op['id_op']?></option>
                      <?php
                    } while ($row_lista_op = mysql_fetch_assoc($lista_op));
                    $rows = mysql_num_rows($lista_op);
                    if($rows > 0) {
                      mysql_data_seek($lista_op, 0);
                      $row_lista_op = mysql_fetch_assoc($lista_op);
                    }
                    ?>
                  </select></td>
                  <td colspan="2" id="fuente1"><select class='busqueda selectsMini' name="id_ref" id="id_ref" >
                    <option value="0">REF</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_ref_op['id_ref']?>"><?php  echo $row_ref_op['cod_ref']?></option>
                      <?php
                    } while ($row_ref_op = mysql_fetch_assoc($ref_op));
                    $rows = mysql_num_rows($ref_op);
                    if($rows > 0) {
                      mysql_data_seek($ref_op, 0);
                      $row_ref_op = mysql_fetch_assoc($ref_op);
                    }
                    ?>
                  </select>
                  <!--&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&--> 
                   
                    <select id='maquina' name='maquina' class="" >
                         <option value="0"<?php if (!(strcmp("0", $_GET['maquina']))) {echo "selected=\"selected\"";} ?>>MAQUINA</option>
                             <?php  foreach($row_maquina as $row_maquina ) { ?>
                          <option value="<?php echo $row_maquina['id_maquina']; ?>"<?php if (!(strcmp($row_maquina['id_maquina'], $_GET['maquina']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_maquina['nombre_maquina']); ?> </option>
                      <?php } ?>
                    </select>



                       <select id='anyo' name='anyo' class="" onchange="consultaPorcentajesOpList();">
                            <option value="0"<?php if (!(strcmp("0", $_GET['anyo']))) {echo "selected=\"selected\"";} ?>>AÑOS</option>
                                <?php  foreach($row_anual as $row_anual ) { ?>
                             <option value="<?php echo $row_anual['anual']; ?>"<?php if (!(strcmp($row_anual['anual'], $_GET['anyo']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_anual['anual']); ?> </option>
                         <?php } ?>
                       </select>


                    <select class=' selectsMini' name="mes" id="mes" >
                         <option value="0"<?php if (!(strcmp("0", $_GET['mes']))) {echo "selected=\"selected\"";} ?>>MES</option>
                             <?php  foreach($row_mensual as $row_mensual ) { ?>
                          <option value="<?php echo $row_mensual['id_mensual']; ?>"<?php if (!(strcmp($row_mensual['id_mensual'], $_GET['mes']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_mensual['mensual']); ?> </option>
                      <?php } ?>
                    </select>


                <input class="botonUpdate" type="submit" name="consultar" id="consultar" value="consultar" onClick="ListadoProduccion();">
              </td>
                </tr>
                <tr>
                  <td colspan="2" id="dato1"><p>Nota: si en el la columna '<strong>Proceso</strong>', aparecen las siguientes notificaciones tenga en cuenta:</p>
                    <p> </p></td>
                    <td id="dato1">Nota: si en el la columna '<strong>Mezcla</strong>', aparecen las siguientes notificaciones tenga en cuenta:</td>
                  </tr>
                  <tr>
                    <td colspan="2" id="dato1"><img src="images/extruir.gif" width="20" height="17" alt="O.P EXTRUIDA"title="O.P EXTRUIDA" border="0" style="cursor:hand;"/> significa que la o.p esta en proceso de extrusion</td>
                    <td id="dato1"><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /> significa que la ref. si tiene las mezclas de extrusion
                      <img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /> significa que la ref. no tiene las mezclas de extrusion</td>
                    </tr>
                  </table>
                </form>  
                <form action="delete_listado.php" method="get" name="seleccion">
                  <fieldset> <legend id="dato1">ORDENES DE PRODUCCION CON PRIORIDAD</legend>
                    <table class="table table-bordered table-sm">
                      <tr>
                        <td colspan="2" id="dato1">
                          <?php 
                          $var=$row_usuario['tipo_usuario'];
                          if ($var==1) {?>
                            <input name="borrado" type="hidden" id="borrado" value="eliminar_ext" /> 
                            <input name="Input" type="submit" onClick="return eliminar_extrusion();" value="Eliminar"/> <?php
                          } if ($var!=1) {?>
                            <input name="borrado" type="hidden" id="borrado" value="36" />
                            <input name="Input" type="submit" value="Cambio Estado"/> <?php
                          } ?>
                        </td>
                        <td colspan="7"><?php $id=$_GET['id']; 
                        if($id == '1') { ?> <div id="numero1"> <?php echo "CAMBIO DE ESTADO A INACTIVA COMPLETA"; ?> </div> <?php }
                        if($id == '2') { ?><div id="acceso1"> <?php echo "SE ACTIVO CORRECTAMENTE"; ?> </div> <?php }
                        if($id == '3') { ?> <div id="numero1"> <?php echo "ELIMINACION COMPLETA"; ?> </div> <?php }
                        if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
                        <td colspan="6" id="dato3"><a href="consumo_tiempos_ext.php"><img src="images/rt.gif" alt="LISTADO DE TIEMPOS"title="LISTADO DE TIEMPOS" border="0" style="cursor:hand;"></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;"></a><a href="produccion_registro_extrusion_listado.php"><img src="images/opciones.gif" alt="EXTRUIR"title="EXTRUIR" border="0" style="cursor:hand;"></a><a href="hoja_maestra_listado.php"><img src="images/m.gif" alt="HOJAS MAESTRAS"title="HOJAS MAESTRAS" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="REFRESCAR"title="REFRESCAR" border="0" style="cursor:hand;"/></a>
                          <input type="button" class="botonGeneral" value="Exporta Excel" onClick="window.location = 'produccion_exportar_excel.php?tipoListado=1&op=<?php echo $_GET['op'];?>&id_ref=<?php echo $_GET['id_ref'];?>&maquina=<?php echo $_GET['maquina'];?>&anyo=<?php echo $_GET['anyo'];?>&mes=<?php echo $_GET['mes'];?>'" />
                        </td>
                      </tr>          
                      <tr id="tr1">
                        <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                        <td nowrap="nowrap" id="titulo4">N&deg; O.P </td>
                        <td nowrap="nowrap" id="titulo4">REF. </td>
                        <td nowrap="nowrap" id="titulo4">VER.</td>
                        <td nowrap="nowrap" id="titulo4">KILOS</td>
                        <td nowrap="nowrap" id="titulo4">KILOS/HORA</td>
                        <td nowrap="nowrap" id="titulo4">KILOS DESP.</td>
                        <td nowrap="nowrap" id="titulo4">FECHA INICIAL</td>
                        <td nowrap="nowrap" id="titulo4">FECHA FINAL</td> 
                        <td nowrap="nowrap" id="titulo4">MONTAJE</td>
                        <td nowrap="nowrap" id="titulo4">LIQUIDA</td>
                        <td nowrap="nowrap" id="titulo4">MAQUINA</td>
                        <td nowrap="nowrap" id="titulo4">ESTADO</td>
                        <td nowrap="nowrap" id="titulo4">ROLLOS</td>
                        <td nowrap="nowrap" id="titulo4">R. PARCIAL</td>
                        <td nowrap="nowrap" id="titulo4">MEZCLA</td>
                        <td nowrap="nowrap" id="titulo4">PROCESO</td>
                      </tr>
                      <?php do { ?>
                        <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                          <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_orden_produccion['id_rp']; ?>" />     
                          </td>
                          <td nowrap="nowrap" id="dato2"><strong><?php echo $row_orden_produccion['id_op_rp']; ?></strong></td>
                          <td id="dato2"><?php echo $row_orden_produccion['int_cod_ref_rp']; ?></td>
                          <td id="dato2"><?php echo $row_orden_produccion['version_ref_rp']; ?></td>
                          <td id="dato2"><?php echo $row_orden_produccion['int_kilos_prod_rp']; ?></td>
                          <td id="dato2"><?php echo $row_orden_produccion['int_kilosxhora_rp']; if($row_orden_produccion['int_kilosxhora_rp']==''){echo "0";}?></td>
                          <td id="dato2"><?php echo $row_orden_produccion['int_kilos_desp_rp']; if($row_orden_produccion['int_kilos_desp_rp']==''){echo "0.00";}?></td>       
                          <td nowrap="nowrap"id="dato2"><?php echo $row_orden_produccion['fecha_ini_rp']; ?></td>
                          <td nowrap="nowrap"id="dato2"><?php echo $row_orden_produccion['fecha_fin_rp']; ?></td>
                          <td nowrap="nowrap" id="dato2">
                            <?php  
                            $id_emp=$row_orden_produccion['int_cod_empleado_rp'];
                            $sqlemp="SELECT nombre_empleado FROM empleado WHERE empleado.codigo_empleado='$id_emp'";
                            $resultemp= mysql_query($sqlemp);
                            $numemp= mysql_num_rows($resultemp);
                            if($numemp >='1')
                            { 
                             $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; }else{echo "N/A";
                           }?></td>
                           <td id="dato2">
                            <?php 
                              $maquinaid = $row_orden_produccion['str_maquina_rp'];
                              $nombre_maquinas = $conexion->llenarCampos("maquina", "WHERE id_maquina='".$maquinaid."' ", " ", "nombre_maquina "); 
                              echo $nombre_maquinas['nombre_maquina']; 
                              ?> 
                           </td>
                           <td nowrap="nowrap"id="dato2">
                            <?php  
                            $id_emp=$row_orden_produccion['int_cod_liquida_rp'];
                            $sqlemp="SELECT nombre_empleado FROM empleado WHERE empleado.codigo_empleado='$id_emp'";
                            $resultemp= mysql_query($sqlemp);
                            $numemp= mysql_num_rows($resultemp);
                            if($numemp >='1')
                            { 
                             $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; }else{echo "N/A";
                           }?></td> 
                           <td id="dato2">
                            <?php if($row_orden_produccion['b_borrado_rp']=='1'){?><input name="activar[]" type="checkbox" value="<?php echo $row_orden_produccion['id_rp']; ?>" /><a href="javascript:document.seleccion.submit()">Activar</a><?php }else{echo "Activa";}?>
                          </td> 
                          <td id="dato2">
                          <?php 
                          $op_c=$row_orden_produccion['id_op_rp'];
                          $sqlno="SELECT SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$op_c'"; 
                          $resultno=mysql_query($sqlno); 
                          $numno=mysql_num_rows($resultno);
                          if($numno > '0') 
                            { $kilosE=mysql_result($resultno,0,'kilos');} 
                          $sqlnI="SELECT SUM(int_kilos_op) AS kilosop FROM Tbl_orden_produccion WHERE id_op='$op_c'"; 
                          $resultnI=mysql_query($sqlnI); 
                          $numnI=mysql_num_rows($resultnI);
                          if($numnI > '0') 
                            {$kilosOP=mysql_result($resultnI,0,'kilosop');} 
                          if($kilosE < $kilosOP)
                          {		
                           ?>
                           <a href="javascript:verFoto('produccion_extrusion_stiker_rollo_add.php?id_op_r=<?php echo $row_orden_produccion['id_op_rp']; ?>','870','710')"><img src="images/mas.gif" alt="salieron menos kilos que en la o.p"title="salieron menos kilos que en la o.p" border="0" style="cursor:hand;" /></a> 
                         <?php }else{ ?>
                          <a href="javascript:verFoto('produccion_extrusion_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op_rp']; ?>','870','710')"><img src="images/completo.gif" alt="COMPLETO"title="COMPLETO" border="0" style="cursor:hand;" /></a>
                          <?php } ?></td>
                          <td id="dato2"> 
                           <?php 
                           $op_c=$row_orden_produccion['id_op'];
                           $sqlparcial="SELECT parcial FROM Tbl_reg_produccion WHERE id_op_rp = '$op_c' AND `id_proceso_rp` ='1' ORDER BY parcial DESC"; 
                           $resultparcial=mysql_query($sqlparcial); 
                           $numparcial=mysql_num_rows($resultparcial);         

                           $parcial = mysql_result($resultparcial, 0, 'parcial');
                           if($kilosE > $kilosOP && (  $parcial >'1')) {   ?> 
                            <a href="javascript:verFoto('produccion_extrusion_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op_rp']; ?>','870','710')"><img src="images/parcial.gif" alt="PARCIAL" title="PARCIAL" border="0" style="cursor:hand;" /></a>
                            <?php } ?> </td>
                            <td id="dato2">
                              <?php 
                              $id_ref_op=$row_orden_produccion['id_ref_rp'];
                              $sqlop="SELECT * FROM Tbl_caract_proceso WHERE id_ref_cp='$id_ref_op' AND id_proceso='1' ORDER BY id_ref_cp DESC LIMIT 1"; 
                              $resultop=mysql_query($sqlop); 
                              $numop=mysql_num_rows($resultop);
                              $id_pm = mysql_result($resultop, 0, 'id_pm_cp');
                              $id_ref = mysql_result($resultop, 0, 'id_ref_cp');
                              $id_cod = mysql_result($resultop, 0, 'int_cod_ref_cp');	  			
                              if($numop >= '1'){		 
                                ?><a href="javascript:verFoto('produccion_caract_extrusion_mezcla_vista.php?id_ref=<?php echo $id_ref;?>&id_pm=<?php echo $id_pm;?>','870','710')"><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /></a><?php 
                              }else { ?><a href="javascript:verFoto('produccion_mezclas_add.php?id_ref=<?php echo $row_orden_produccion['id_ref_rp'];?>&cod_ref=<?php echo $row_orden_produccion['int_cod_ref_rp'];?>','870','710')"><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" /></a>
                            <?php }?>     
                          </td> 
                          <td nowrap="nowrap" id="dato2">	  
                           <?php if($row_orden_produccion['id_proceso_rp']=='1'){?>
                             <a href="javascript:verFoto('produccion_registro_extrusion_vista.php?id_op_rp=<?php echo $row_orden_produccion['id_op_rp'];?>&amp;id_rp=<?php echo $row_orden_produccion['id_rp'];?>','870','710')"><img src="images/extruir.gif" width="28" height="20" alt="O.P EXTRUIDA"title="O.P EXTRUIDA" border="0" style="cursor:hand;"/></a>
                           <?php }?>
                         </td>   
                       </tr>
                     <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?>
                   </table>
                 </fieldset>
               </form>
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
</body>
</html>
<script>
 
 
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
                    var3:"b_estado_op >= 0",//where
                    var4:"ORDER BY Tbl_orden_produccion.id_op DESC",
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
                        var1:"id_ref, cod_ref",
                        var2:"tbl_referencia",
                        var3:"",//where
                        var4:"GROUP BY cod_ref ORDER BY CAST(cod_ref AS int) DESC",
                        var5:"id_ref",
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

 function ListadoProduccion(){ 
       var form = $("#form1").serialize();

       var vista = 'produccion_registro_extrusion_listado_add2.php';
      
          enviovarListados(form,vista);  
       
  }

</script>
<?php
mysql_free_result($usuario);

mysql_free_result($orden_produccion);
?>