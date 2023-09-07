<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
require(ROOT_BBDD);
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF'] . "?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")) {
  $logoutAction .= "&" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) && ($_GET['doLogout'] == "true")) {
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
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0)
    $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: " . $MM_restrictGoTo);
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
$usuario = mysql_query($query_usuario, $conexion1);
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

//PRIORIDAD
mysql_select_db($database_conexion1, $conexion1);
$query_prioridad = "SELECT * FROM Tbl_orden_produccion WHERE b_visual_op <> '0' AND b_borrado_op='0' ORDER BY b_visual_op ASC";
$prioridad = mysql_query($query_prioridad, $conexion1);
$row_prioridad = mysql_fetch_assoc($prioridad);
$totalRows_prioridad = mysql_num_rows($prioridad);

$maxRows_orden_produccion = 20;
$pageNum_orden_produccion = 0;
if (isset($_GET['pageNum_orden_produccion'])) {
  $pageNum_orden_produccion = $_GET['pageNum_orden_produccion'];
}
$startRow_orden_produccion = $pageNum_orden_produccion * $maxRows_orden_produccion;

mysql_select_db($database_conexion1, $conexion1);
$query_orden_produccion = "SELECT * FROM Tbl_orden_produccion WHERE b_borrado_op='0' ORDER BY b_visual_op,id_op DESC";
$query_limit_orden_produccion = sprintf("%s LIMIT %d, %d", $query_orden_produccion, $startRow_orden_produccion, $maxRows_orden_produccion);
$orden_produccion = mysql_query($query_limit_orden_produccion, $conexion1);
$row_orden_produccion = mysql_fetch_assoc($orden_produccion);

if (isset($_GET['totalRows_orden_produccion'])) {
  $totalRows_orden_produccion = $_GET['totalRows_orden_produccion'];
} else {
  $all_orden_produccion = mysql_query($query_orden_produccion);
  $totalRows_orden_produccion = mysql_num_rows($all_orden_produccion);
}
$totalPages_orden_produccion = ceil($totalRows_orden_produccion / $maxRows_orden_produccion) - 1;

$queryString_orden_produccion = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (
      stristr($param, "pageNum_orden_produccion") == false &&
      stristr($param, "totalRows_orden_produccion") == false
    ) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_orden_produccion = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_orden_produccion = sprintf("&totalRows_orden_produccion=%d%s", $totalRows_orden_produccion, $queryString_orden_produccion);

mysql_select_db($database_conexion1, $conexion1);
$query_lista_op = "SELECT id_op FROM Tbl_orden_produccion WHERE b_borrado_op = '0' ORDER BY id_op DESC";
$lista_op = mysql_query($query_lista_op, $conexion1);
$row_lista_op = mysql_fetch_assoc($lista_op);
$totalRows_lista_op = mysql_num_rows($lista_op);

mysql_select_db($database_conexion1, $conexion1);
$query_ref_op = "SELECT id_ref, cod_ref FROM Tbl_referencia order by n_egp_ref desc";
$ref_op = mysql_query($query_ref_op, $conexion1);
$row_ref_op = mysql_fetch_assoc($ref_op);
$totalRows_ref_op = mysql_num_rows($ref_op);

mysql_select_db($database_conexion1, $conexion1);
$query_mensual = "SELECT * FROM mensual ORDER BY id_mensual ASC";
$mensual = mysql_query($query_mensual, $conexion1);
$row_mensual = mysql_fetch_assoc($mensual);
$totalRows_mensual = mysql_num_rows($mensual);

$row_anual = $conexion->llenaSelect('anual', '', 'ORDER BY id_anual DESC');

?>
<html>

<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <link rel="stylesheet" type="text/css" href="css/formato.css" />
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css" />
  <script type="text/javascript" src="AjaxControllers/js/envioListado.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>


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

<body onload="JavaScript: AutoRefresh (100000);">
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
                  <div class="panel-heading" align="left"></div><!--color azul-->
                  <div class="row">
                    <div class="span12">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg">

                    </div>
                  </div>
                  <div class="panel-heading" align="left"></div><!--color azul-->
                  <div id="cabezamenu">
                    <ul id="menuhorizontal">
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li>
                      <li><a href="produccion_registro_extrusion_listado.php">EXTRUSION</a></li>
                    </ul>
                  </div>
                  <div class="panel-body">
                    <br>
                    <div><!-- contenedor -->
                      <div class="row">
                        <div class="span12">
                        </div>
                      </div>
                      <form action="produccion_registro_extrusion_listado2.php" method="get" name="form1" id="form1">

                        <table class="table table-bordered table-sm">
                          <tr>
                            <td id="fuente2" colspan="9">ORDENES DE PRODUCCION PARA EXTRUIR</td>
                          </tr>
                          <tr>
                            <!-- <td id="fuente3" colspan="2">
                      <input type="text" name="id_op" required  onBlur="if (form1.id_op.value) { DatosGestiones('10','id_op',form1.id_op.value); } else { alert('Debe digitar el O.P para validar su existencia en la BD'); };" placeholder="O.P" > 

                       </div>
                      </td>-->
                            <td id="fuente2" colspan="9">
                              <div id="resultado"><input name="retorno_mensaje" type="hidden">
                                <select class='busqueda selectsMini' name="op" id="op">
                                  <option value="0">O.P.</option>
                                  <?php
                                  do {
                                  ?>
                                    <option value="<?php echo $row_lista_op['id_op'] ?>"><?php echo $row_lista_op['id_op'] ?></option>
                                  <?php
                                  } while ($row_lista_op = mysql_fetch_assoc($lista_op));
                                  $rows = mysql_num_rows($lista_op);
                                  if ($rows > 0) {
                                    mysql_data_seek($lista_op, 0);
                                    $row_lista_op = mysql_fetch_assoc($lista_op);
                                  }
                                  ?>
                                </select>
                                <!--&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&-->
                                <select class='busqueda selectsMini' name="id_ref" id="id_ref">
                                  <option value="0">REF</option>
                                  <?php
                                  do {
                                  ?>
                                    <option value="<?php echo $row_ref_op['cod_ref'] ?>"><?php echo $row_ref_op['cod_ref'] ?>
                                    </option>
                                  <?php
                                  } while ($row_ref_op = mysql_fetch_assoc($ref_op));
                                  $rows = mysql_num_rows($ref_op);
                                  if ($rows > 0) {
                                    mysql_data_seek($ref_op, 0);
                                    $row_ref_op = mysql_fetch_assoc($ref_op);
                                  }
                                  ?>
                                </select>
                                <!--&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&-->

                                <?php $Year = date("Y"); ?>
                                <select id='anyo' name='anyo' class="">
                                  <option value="0">AÑOS</option>
                                  <?php foreach ($row_anual as $row_anual) { ?>
                                    <option value="<?php echo $row_anual['anual']; ?>"><?php echo htmlentities($row_anual['anual']); ?> </option>
                                  <?php } ?>
                                </select>
                                <select class='busqueda selectsMini' name="mes" id="mes">
                                  <option value="0">MES</option>
                                  <?php
                                  do {
                                  ?>
                                    <option value="<?php echo $row_mensual['id_mensual'] ?>">
                                      <?php echo $row_mensual['mensual'] ?>
                                    </option>
                                  <?php
                                  } while ($row_mensual = mysql_fetch_assoc($mensual));
                                  $rows = mysql_num_rows($mensual);
                                  if ($rows > 0) {
                                    mysql_data_seek($mensual, 0);
                                    $row_mensual = mysql_fetch_assoc($mensual);
                                  }
                                  ?>
                                </select>
                                <select class='busqueda selectsMini' name="estado" id="estado">
                                  <option value="">ESTADO</option>
                                  <option value="0">INGRESADA</option>
                                  <option value="1">LIQUIDADO</option>
                                  <option value="2">SIN LIQUIDAR</option>
                                </select>

                                <input class="botonUpdate" type="submit" name="consultar" id="consultar" value="consultar" onClick="ListadoProduccion();">

                                <?php if ($row_usuario['tipo_usuario'] == 1) { ?><a href="produccion_registro_extrusion_listado_xkilos.php"><img src="images/r.gif" alt="LISTADO REF KILOS X PROCESO" title="LISTADO REF KILOS X PROCESO" border="0" style="cursor:hand;"></a>
                                  <a href="despacho_direccion.php"><img src="images/c.gif" alt="VERIFICAR PAQUETES X CAJA" title="VERIFICAR PAQUETES X CAJA" border="0" style="cursor:hand;" /></a><?php } ?>
                                <a href="produccion_registro_extrusion_listado_add.php"><img src="images/opciones.gif" alt="LISTADO EXTRUIDAS" title="LISTADO EXTRUIDAS" border="0" style="cursor:hand;"></a>
                                <a href="hoja_maestra_listado.php"><img src="images/m.gif" alt="HOJAS MAESTRAS" title="HOJAS MAESTRAS" border="0" style="cursor:hand;"></a>
                                <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="REFRESCAR" title="REFRESCAR" border="0" style="cursor:hand;" /></a>
                            </td>
                          </tr>

                          <tr>
                            <td colspan="9" nowrap="nowrap" id="talla1">EXT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- IMP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELL&nbsp;&nbsp;  -->DESPERDICIOS</td>
                          </tr>
                          <tr>
                            <td colspan="9">
                              <input id="extruder" name="extruder" type="text" value="0" readonly="readonly" style="width:40px" />
                              <!-- <input id="impre" name="impre" type="text" value="0" readonly="readonly" style="width:40px"/> 
                         <input id="sellado" name="sellado" type="text" value="0" readonly="readonly" style="width:40px"/> --> =
                              <input id="desperdiciototal" name="desperdiciototal" type="text" value="0" min="0" max="50" style="width:40px" readonly="readonly" onchange="consultaPorcentajesOpList();" /> %
                            </td>
                          </tr>

                          <tr>
                            <td colspan="3" id="dato1">Nota: si en el la columna '<strong>Proceso</strong>', aparecen las siguientes notificaciones tenga en cuenta:
                            </td>
                            <td colspan="4" id="dato1">Nota: si en el la columna '<strong>Mezcla</strong>', aparecen las siguientes notificaciones tenga en cuenta:
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3" id="dato1">
                              <img src="images/falta.gif" width="20" height="17" alt="O.P INGRESADA" title="O.P INGRESADA" border="0" style="cursor:hand;" /> O.P Ingresada <br>
                              <img src="images/falta7.gif" width="20" height="17" alt="O.P INGRESADA" title="O.P INGRESADA" border="0" style="cursor:hand;" /> O.P Liquidada
                            </td>
                            <td colspan="3" id="dato1">
                              <img src="images/falta6.gif" width="20" height="17" alt="O.P INGRESADA" title="O.P INGRESADA" border="0" style="cursor:hand;" /> O.P Falta por liquidar <br>
                              <img src="images/falta5.gif" width="16" height="16" alt="KILOS DISTINTOS" title="KILOS DISTINTOS" border="0" style="cursor:hand;" />Kilos de consumo son distintos
                            <td colspan="3" id="dato1">
                              <img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" />Tiene las mezclas de extrusion <br>
                              <img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" border="0" />No tiene las mezclas de extrusion<br>
                              R. pend: Hay mas rollos liquidados que registrados en la tabla de rollos <br>
                            </td>
                          </tr>
                        </table>
                        <?php if ($row_prioridad['id_op'] != '') { ?>
                          <fieldset>
                            <legend id="dato1">ORDENES DE PRODUCCION CON PRIORIDAD</legend>
                            <table class="table table-bordered table-sm">
                              <tr>
                                <td colspan="2" id="dato1">&nbsp;
                                </td>
                                <td colspan="3">&nbsp;</td>
                                <td colspan="5" id="dato3"><?php if ($row_usuario['tipo_usuario'] == 1) { ?><a href="consumo_tiempos_ext.php"><img src="images/rt.gif" alt="LISTADO DE TIEMPOS" title="LISTADO DE TIEMPOS" border="0" style="cursor:hand;"></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS" title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;"></a><?php } ?><a href="produccion_registro_extrusion_listado_add.php"><img src="images/opciones.gif" alt="LISTADO EXTRUIDAS" title="LISTADO EXTRUIDAS" border="0" style="cursor:hand;"></a><a href="hoja_maestra_listado.php"><img src="images/m.gif" alt="HOJAS MAESTRAS" title="HOJAS MAESTRAS" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="REFRESCAR" title="REFRESCAR" border="0" style="cursor:hand;" /></a></td>
                              </tr>
                              <tr id="tr1">
                                <td nowrap="nowrap" id="titulo4">N&deg; O.P </td>
                                <td nowrap="nowrap" id="titulo4">CLIENTE</td>
                                <td nowrap="nowrap" id="titulo4">REF. </td>
                                <td nowrap="nowrap" id="titulo4">VER.</td>
                                <td nowrap="nowrap" id="titulo4">KILOS</td>
                                <td nowrap="nowrap" id="titulo4">FECHA REGISRO O.P</td>
                                <td nowrap="nowrap" id="titulo4">ESTADO O.P</td>
                                <td nowrap="nowrap" id="titulo4">ROLLOS</td>
                                <td nowrap="nowrap" id="titulo4">R. PARCIAL</td>
                                <td nowrap="nowrap" id="titulo4">MEZCLA</td>
                                <td nowrap="nowrap" id="titulo4">PROCESO</td>
                              </tr>
                              <?php do { ?>
                                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                                  <td nowrap="nowrap" id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_prioridad['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><strong><?php echo $row_prioridad['id_op']; ?></strong></a></td>
                                  <td nowrap="nowrap" id="dato1"><a href="produccion_op_vista.php?id_op=<?php echo $row_prioridad['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
                                      <?php
                                      $id_cp = $row_prioridad['int_cliente_op'];
                                      $sqlnp = "SELECT * FROM  cliente WHERE cliente.id_c=$id_cp";
                                      $resultnp = mysql_query($sqlnp);
                                      $numnp = mysql_num_rows($resultnp);
                                      if ($numnp >= '1') {
                                        $nombre_cliente_cp = mysql_result($resultnp, 0, 'nombre_c');
                                        echo $nombre_cliente_cp;
                                      } else {
                                        echo "";
                                      } ?></a>
                                  </td>
                                  <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_prioridad['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_prioridad['int_cod_ref_op']; ?></a></td>
                                  <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_prioridad['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_prioridad['version_ref_op']; ?></a></td>
                                  <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_prioridad['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_prioridad['int_kilos_op']; ?></a></td>
                                  <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_prioridad['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_prioridad['fecha_registro_op']; ?></a></td>
                                  <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_prioridad['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php if ($row_prioridad['b_borrado_op'] == '0') {
                                                                                                                                                                                          echo "ACTIVA";
                                                                                                                                                                                        } else {
                                                                                                                                                                                          echo "INACTIVA";
                                                                                                                                                                                        } ?></a></td>
                                  <td id="dato2">
                                    <?php
                                    $op_c = $row_orden_produccion['id_op'];
                                    $sqlno = "SELECT COUNT(rollo_r) as Rollos, SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$op_c'";
                                    $resultno = mysql_query($sqlno);
                                    $numno = mysql_num_rows($resultno);
                                    if ($numno > '0') {
                                      $kilosE = mysql_result($resultno, 0, 'kilos');
                                      $RollosE = mysql_result($resultno, 0, 'Rollos');
                                    }
                                    if ($kilosE == '' /*<$row_orden_produccion['int_kilos_op']*/) {
                                    ?>
                                      <a href="javascript:verFoto('produccion_extrusion_stiker_rollo_add.php?id_op_r=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/mas.gif" alt="ADD ROLLOS" title="ADD ROLLOS" border="0" style="cursor:hand;" /></a>
                                    <?php
                                      $tienerollos = 0;
                                    } else {
                                      $tienerollos = 1; ?>
                                      <a href="javascript:verFoto('produccion_extrusion_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/completo.gif" alt="COMPLETO" title="COMPLETO" border="0" style="cursor:hand;" /></a>
                                    <?php } ?>
                                  </td>
                                  <td id="dato2">
                                    <?php

                                    $op_c = $row_orden_produccion['id_op'];
                                    $sqlparcial = "SELECT parcial FROM Tbl_reg_produccion WHERE id_op_rp = '$op_c' AND `id_proceso_rp` ='1' ORDER BY parcial DESC";
                                    $resultparcial = mysql_query($sqlparcial);
                                    $numparcial = mysql_num_rows($resultparcial);

                                    $parcial = mysql_result($resultparcial, 0, 'parcial');

                                    if ($kilosE != '' && ($parcial > '1')) {   ?>
                                      <a href="javascript:verFoto('produccion_extrusion_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/parcial.gif" alt="PARCIAL" title="PARCIAL" border="0" style="cursor:hand;" /></a>
                                    <?php } ?>
                                  </td>
                                  <td id="dato2">
                                    <?php
                                    $id_ref_pr = $row_prioridad['int_cod_ref_op'];
                                    $sqlop = "SELECT * FROM tbl_caracteristicas_prod WHERE cod_ref='$id_ref_pr' AND proceso = '1' ORDER BY cod_ref DESC LIMIT 1";
                                    $resultca = mysql_query($sqloca);
                                    $numca = mysql_num_rows($resultca);
                                    if ($numca >= '1') {
                                      $id_codp = mysql_result($resultca, 0, 'int_cod_ref_cp');
                                    ?>
                                      <a href="javascript:popUp('view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $id_codp; ?>','1300','700')"><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /></a><?php
                                                                                                                                                                                                                                                                                  } else { ?><a href="javascript:popUp('produccion_mezclas_add.php?id_ref=<?php echo $row_prioridad['id_ref_op']; ?>&cod_ref=<?php echo $row_prioridad['int_cod_ref_op']; ?>','1300','700')"><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" border="0" /></a>
                                    <?php } ?>
                                  </td>
                                  <td nowrap="nowrap" id="dato2">
                                    <?php
                                    $estado_op = $row_orden_produccion['b_estado_op'];

                                    $op_c = $row_orden_produccion['id_op'];
                                    $sqlsell = "SELECT SUM(int_kilos_prod_rp) AS int_kilos_prod_rp, id_rp,id_ref_rp,id_op_rp,MAX(rollo_rp) as rollo_rp,fecha_ini_rp,int_kilos_prod_rp FROM Tbl_reg_produccion WHERE id_op_rp = '$op_c' AND `id_proceso_rp` ='1' ORDER BY rollo_rp DESC";
                                    $resultsell = mysql_query($sqlsell);
                                    $numsell = mysql_num_rows($resultsell);

                                    $id_rp = mysql_result($resultsell, 0, 'id_rp');
                                    $id_op_rp = mysql_result($resultsell, 0, 'id_op_rp');
                                    $id_ref_rp = mysql_result($resultsell, 0, 'id_ref_rp');
                                    $rollos = mysql_result($resultsell, 0, 'rollo_rp');
                                    $totalKilosliq = mysql_result($resultsell, 0, 'int_kilos_prod_rp');


                                    $sqlre = "SELECT SUM(valor_prod_rp) AS totalkilos FROM Tbl_reg_kilo_producido WHERE op_rp='$op_c' AND id_proceso_rkp='1'";
                                    $resultre = mysql_query($sqlre);
                                    $numere = mysql_num_rows($resultre);
                                    if ($numere >= '1') {
                                      $cantidad = mysql_result($resultre, 0, 'totalkilos');
                                    }
                                    ?>
                                    <?php if ($numsell > '0'  && $tienerollos == 1 && $rollos == $RollosE && $cantidad == $totalKilosliq) : ?>
                                      <a href="javascript:verFoto('produccion_registro_extrusion_vista.php?id_op_rp=<?php echo $id_op_rp; ?>&id_rp=<?php echo $id_rp; ?>','870','710')"><img src="images/falta7.gif" width="16" height="16" alt="LIQUIDADO" title="LIQUIDADO" border="0" style="cursor:hand;" /></a>
                                    <?php elseif ($numsell > '0'  && $tienerollos == 1 && $rollos == $RollosE && $cantidad != $totalKilosliq) : ?>
                                      <a href="javascript:verFoto('produccion_registro_extrusion_edit.php?id_op=<?php echo $id_op_rp; ?>&id_rp=<?php echo $id_rp; ?>,&tipo=1','870','710')"><img src="images/falta5.gif" width="16" height="16" alt="KILOS DISTINTOS" title="KILOS DISTINTOS" border="0" style="cursor:hand;" /></a>
                                    <?php elseif ($numsell > '0' && $tienerollos == 1 && $rollos < $RollosE) : ?>
                                      <a href="javascript:popUp('produccion_registro_extrusion_add.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/falta6.gif" alt="LIQUIDAR" title="LIQUIDAR" width="16" height="16" border="0" style="cursor:hand;" /></a>
                                    <?php elseif ($numsell > '0' && $tienerollos == 1 && $rollos > $RollosE) :
                                      echo "R. pend";
                                    elseif ($numsell == '' && $tienerollos == 0 && $rollos < $RollosE) : ?>
                                      <a href="javascript:popUp('produccion_registro_extrusion_add.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/falta.gif" alt="INGRESE LOS ROLLOS" title="INGRESE LOS ROLLOS" width="16" height="16" border="0" style="cursor:hand;" /> </a>
                                    <?php elseif ($numca < '1') : ?>
                                      <a href="javascript:popUp('view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $id_ref_pr; ?>','1300','700')"><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" border="0" /></a>
                                    <?php else :  ?>
                                      <a href="javascript:popUp('produccion_registro_extrusion_add.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/falta.gif" alt="INGRESE LOS ROLLOS" title="INGRESE LOS ROLLOS" width="16" height="16" border="0" style="cursor:hand;" /> </a>
                                    <?php endif; ?>
                                  </td>
                                </tr>
                              <?php } while ($row_prioridad = mysql_fetch_assoc($prioridad)); ?>
                            </table>
                          </fieldset>
                        <?php } ?>




                        <fieldset>
                          <legend id="dato1">LISTADO ORDENES DE PRODUCCION</legend>
                          <table class="table table-bordered table-sm">
                            <tr>
                              <td colspan="2" id="dato1">&nbsp;
                              </td>
                              <td colspan="3">&nbsp;</td>
                              <td colspan="5" id="dato3"><?php if ($row_usuario['tipo_usuario'] == 1) { ?><a href="extruder_tiempos_y_preparacion.php"><img src="images/rt.gif" alt="LISTADO DE TIEMPOS Y PREPARACION" title="LISTADO DE TIEMPOS Y PREPARACION" border="0" style="cursor:hand;"></a><a href="consumo_tiempos_ext.php"><img src="images/rt.gif" alt="LISTADO DE TIEMPOS" title="LISTADO DE TIEMPOS" border="0" style="cursor:hand;"></a><a href="consumo_materias_primas.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS" title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;"></a><?php } ?><a href="produccion_registro_extrusion_listado_add.php"><img src="images/opciones.gif" alt="LISTADO EXTRUIDAS" title="LISTADO EXTRUIDAS" border="0" style="cursor:hand;"></a><a href="hoja_maestra_listado.php"><img src="images/m.gif" alt="HOJAS MAESTRAS" title="HOJAS MAESTRAS" border="0" style="cursor:hand;"></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="REFRESCAR" title="REFRESCAR" border="0" style="cursor:hand;" /></a></td>
                            </tr>
                            <tr id="tr1">
                              <td nowrap="nowrap" id="titulo4">N&deg; O.P </td>
                              <td nowrap="nowrap" id="titulo4">CLIENTE</td>
                              <td nowrap="nowrap" id="titulo4">REF. </td>
                              <td nowrap="nowrap" id="titulo4">VER.</td>
                              <td nowrap="nowrap" id="titulo4">KILOS</td>
                              <td nowrap="nowrap" id="titulo4">FECHA REGISRO O.P</td>
                              <td nowrap="nowrap" id="titulo4">ESTADO O.P</td>
                              <td nowrap="nowrap" id="titulo4">ROLLOS</td>
                              <td nowrap="nowrap" id="titulo4">R. PARCIAL</td>
                              <td nowrap="nowrap" id="titulo4">MEZCLA</td>
                              <td nowrap="nowrap" id="titulo4">PROCESO</td>
                            </tr>
                            <?php do { ?>
                              <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                                <td nowrap="nowrap" id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><strong><?php echo $row_orden_produccion['id_op']; ?></strong></a></td>
                                <td id="dato1"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>" target="new" style="text-decoration:none; color:#000000">
                                    <?php
                                    $id_c = $row_orden_produccion['int_cliente_op'];
                                    $sqln = "SELECT * FROM  cliente WHERE cliente.id_c=$id_c";
                                    $resultn = mysql_query($sqln);
                                    $numn = mysql_num_rows($resultn);
                                    if ($numn >= '1') {
                                      $nombre_cliente_c = mysql_result($resultn, 0, 'nombre_c');
                                      $ca = $nombre_cliente_c;
                                      echo $ca;
                                    } else {
                                      echo "";
                                    } ?></a>
                                </td>
                                <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['int_cod_ref_op']; ?></a></td>
                                <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['version_ref_op']; ?></a></td>
                                <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['int_kilos_op']; ?></a></td>
                                <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php echo $row_orden_produccion['fecha_registro_op']; ?></a></td>
                                <td id="dato2"><a href="produccion_op_vista.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>" target="new" style="text-decoration:none; color:#000000"><?php if ($row_orden_produccion['b_borrado_op'] == '0') {
                                                                                                                                                                                                echo "ACTIVA";
                                                                                                                                                                                              } else {
                                                                                                                                                                                                echo "INACTIVA";
                                                                                                                                                                                              } ?></a></td>
                                <td id="dato2">
                                  <?php
                                  $op_c = $row_orden_produccion['id_op'];
                                  $sqlno = "SELECT COUNT(rollo_r) as Rollos, SUM(kilos_r) AS kilos FROM TblExtruderRollo WHERE id_op_r='$op_c'";
                                  $resultno = mysql_query($sqlno);
                                  $numno = mysql_num_rows($resultno);
                                  if ($numno > '0') {
                                    $kilosE = mysql_result($resultno, 0, 'kilos');
                                    $RollosE = mysql_result($resultno, 0, 'Rollos');
                                  }
                                  if ($kilosE == ''/*< $row_orden_produccion['int_kilos_op']*/) {
                                  ?>
                                    <a href="javascript:verFoto('produccion_extrusion_stiker_rollo_add.php?id_op_r=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/mas.gif" alt="ADD ROLLOS" title="ADD ROLLOS" border="0" style="cursor:hand;" /></a>
                                  <?php
                                    $tienerollos = 0;
                                  } else {
                                    $tienerollos = 1; ?>
                                    <a href="javascript:verFoto('produccion_extrusion_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/completo.gif" alt="COMPLETO" title="COMPLETO" border="0" style="cursor:hand;" /></a>

                                  <?php } ?>
                                </td>
                                <td id="dato2">
                                  <?php

                                  $op_c = $row_orden_produccion['id_op'];
                                  $sqlparcial = "SELECT parcial FROM Tbl_reg_produccion WHERE id_op_rp = '$op_c' AND `id_proceso_rp` ='1' ORDER BY parcial DESC";
                                  $resultparcial = mysql_query($sqlparcial);
                                  $numparcial = mysql_num_rows($resultparcial);

                                  $parcial = mysql_result($resultparcial, 0, 'parcial');

                                  if ($kilosE != '' && ($parcial > '1')) {   ?>
                                    <a href="javascript:verFoto('produccion_extrusion_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/parcial.gif" alt="PARCIAL" title="PARCIAL" border="0" style="cursor:hand;" /></a>
                                  <?php } ?>
                                </td>
                                <td id="dato2">
                                  <?php
                                  $id_ref_pr = $row_orden_produccion['int_cod_ref_op'];
                                  $sqloca = "SELECT * FROM tbl_caracteristicas_prod WHERE cod_ref='$id_ref_pr' AND proceso = '1' ORDER BY cod_ref DESC LIMIT 1";
                                  $resultca = mysql_query($sqloca);
                                  $numca = mysql_num_rows($resultca);
                                  $id_codp = mysql_result($resultca, 0, 'cod_ref');
                                  if ($numca >= '1') {
                                  ?>

                                    <a href="javascript:popUp('view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $id_codp; ?>','1300','700')"><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /></a><?php
                                                                                                                                                                                                                                                                                } else { ?><a href="javascript:popUp('view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $id_ref_pr; ?>','1300','700')"><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" border="0" /></a>
                                  <?php } ?>
                                </td>
                                <td nowrap="nowrap" id="dato2">
                                  <?php
                                  $estado_op = $row_orden_produccion['b_estado_op'];
                                  //if ($estado_op > '0'){
                                  $op_c = $row_orden_produccion['id_op'];
                                  $sqlsell = "SELECT SUM(int_kilos_prod_rp) AS int_kilos_prod_rp, id_rp,id_ref_rp,id_op_rp,MAX(rollo_rp) as rollo_rp,fecha_ini_rp,int_kilos_prod_rp FROM Tbl_reg_produccion WHERE id_op_rp = '$op_c' AND `id_proceso_rp` ='1' ORDER BY rollo_rp DESC";
                                  $resultsell = mysql_query($sqlsell);
                                  $numsell = mysql_num_rows($resultsell);

                                  $id_rp = mysql_result($resultsell, 0, 'id_rp');
                                  $id_op_rp = mysql_result($resultsell, 0, 'id_op_rp');
                                  $id_ref_rp = mysql_result($resultsell, 0, 'id_ref_rp');
                                  $rollos = mysql_result($resultsell, 0, 'rollo_rp');
                                  $totalKilosliq = mysql_result($resultsell, 0, 'int_kilos_prod_rp');


                                  $sqlre = "SELECT SUM(valor_prod_rp) AS totalkilos FROM  Tbl_reg_kilo_producido WHERE op_rp='$op_c' AND id_proceso_rkp='1' ";
                                  $resultre = mysql_query($sqlre);
                                  $numere = mysql_num_rows($resultre);
                                  if ($numere >= '1') {
                                    $cantidad = mysql_result($resultre, 0, 'totalkilos');
                                  }
                                  ?>
                                  <?php if ($numsell > '0'  && $tienerollos == 1 && $rollos == $RollosE && $cantidad == $totalKilosliq && $numca >= '1') : ?>
                                    <a href="javascript:verFoto('produccion_registro_extrusion_vista.php?id_op_rp=<?php echo $id_op_rp; ?>&id_rp=<?php echo $id_rp; ?>','870','710')"><img src="images/falta7.gif" width="16" height="16" alt="LIQUIDADO" title="LIQUIDADO" border="0" style="cursor:hand;" /></a>
                                  <?php elseif ($numsell > '0'  && $tienerollos == 1 && $rollos == $RollosE && $cantidad != $totalKilosliq && $numca >= '1') : ?>
                                    <a href="javascript:verFoto('produccion_registro_extrusion_edit.php?id_op=<?php echo $id_op_rp; ?>&id_rp=<?php echo $id_rp; ?>,&tipo=1','870','710')"><img src="images/falta5.gif" width="16" height="16" alt="KILOS DISTINTOS" title="KILOS DISTINTOS" border="0" style="cursor:hand;" /></a>
                                  <?php elseif ($numsell > '0' && $tienerollos == 1 && $rollos < $RollosE && $numca >= '1') : ?>
                                    <a href="javascript:popUp('produccion_registro_extrusion_add.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/falta6.gif" alt="LIQUIDAR" title="LIQUIDAR" width="16" height="16" border="0" style="cursor:hand;" /></a>
                                  <?php elseif ($numsell > '0' && $tienerollos == 1 && $rollos > $RollosE && $numca >= '1') :
                                    echo "R. pend";
                                  elseif ($numsell == '' && $tienerollos == 0 && $rollos < $RollosE && $numca >= '1') : ?>
                                    <a href="javascript:popUp('produccion_registro_extrusion_add.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/falta.gif" alt="INGRESE LOS ROLLOS" title="INGRESE LOS ROLLOS" width="16" height="16" border="0" style="cursor:hand;" /> </a>
                                  <?php elseif ($numca < '1') : ?>
                                    <a href="javascript:popUp('view_index.php?c=cmezclas&a=Mezcla&cod_ref=<?php echo $id_ref_pr; ?>','1300','700')"><img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN EXTRUDER" border="0" /></a>
                                  <?php else :  ?>
                                    <a href="javascript:popUp('produccion_registro_extrusion_add.php?id_op=<?php echo $row_orden_produccion['id_op']; ?>','870','710')"><img src="images/falta.gif" alt="INGRESE LOS ROLLOS" title="INGRESE LOS ROLLOS" width="16" height="16" border="0" style="cursor:hand;" /> </a>
                                  <?php endif; ?>

                                </td>
                              </tr>
                            <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?>
                          </table>
                        </fieldset>
                      </form>
                      <table border="0" width="50%" align="center">
                        <tr>
                          <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion > 0) { // Show if not first page 
                                                      ?>
                              <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, 0, $queryString_orden_produccion); ?>">Primero</a>
                            <?php } // Show if not first page 
                            ?>
                          </td>
                          <td width="31%" id="dato2"><?php if ($pageNum_orden_produccion > 0) { // Show if not first page 
                                                      ?>
                              <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, max(0, $pageNum_orden_produccion - 1), $queryString_orden_produccion); ?>">Anterior</a>
                            <?php } // Show if not first page 
                            ?>
                          </td>
                          <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion < $totalPages_orden_produccion) { // Show if not last page 
                                                      ?>
                              <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, min($totalPages_orden_produccion, $pageNum_orden_produccion + 1), $queryString_orden_produccion); ?>">Siguiente</a>
                            <?php } // Show if not last page 
                            ?>
                          </td>
                          <td width="23%" id="dato2"><?php if ($pageNum_orden_produccion < $totalPages_orden_produccion) { // Show if not last page 
                                                      ?>
                              <a href="<?php printf("%s?pageNum_orden_produccion=%d%s", $currentPage, $totalPages_orden_produccion, $queryString_orden_produccion); ?>">&Uacute;ltimo</a>
                            <?php } // Show if not last page 
                            ?>
                          </td>
                        </tr>
                      </table>
                    </div>
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
      data: function(params) {
        return {
          palabraClave: params.term, // search term
          var1: "id_op", //campo normal para usar
          var2: "tbl_orden_produccion", //tabla
          var3: "b_estado_op >= 0", //where
          var4: "ORDER BY Tbl_orden_produccion.id_op DESC",
          var5: "id_op", //clave
          var6: "id_op" //columna a buscar
        };
      },
      processResults: function(response) {
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
      data: function(params) {
        return {
          palabraClave: params.term, // search term
          var1: "cod_ref",
          var2: "tbl_referencia",
          var3: "", //where
          var4: "GROUP BY cod_ref ORDER BY CAST(cod_ref AS int) DESC",
          var5: "cod_ref",
          var6: "cod_ref" //columna a buscar
        };
      },
      processResults: function(response) {
        return {
          results: response
        };
      },
      cache: true
    }
  });


  function ListadoProduccion() {
    var form = $("#form1").serialize();

    var vista = 'produccion_registro_extrusion_listado2.php';

    enviovarListados(form, vista);

  }

  $(document).ready(function() {
    var fecha = new Date();
    var anyolis = fecha.getFullYear();
    var mes = $("#mes").val()
    var ref = $("#id_ref").val();
    var ops = $("#op").val();
    var estado = $("#estado").val();

    /*if( meslis!='0' || anyolis!='0' || ref !=0 || ops !=0 || estado!=''){*/

    consultaPorcentajesProduccion(mes, anyolis, ref, ops, estado);

    /* } */
  });
</script>

<?php

mysql_free_result($usuario);
mysql_free_result($prioridad);
mysql_free_result($orden_produccion);
mysql_free_result($all_orden_produccion);
mysql_free_result($lista_op);
mysql_free_result($ref_op);
mysql_free_result($mensual);
mysql_free_result($resultnp);
mysql_free_result($resultno);
mysql_free_result($resultparcial);
mysql_free_result($resultca);
mysql_free_result($resultsell);
mysql_free_result($resultn);
mysql_free_result($resultno);
mysql_free_result($resultparcial);
mysql_free_result($resultca);
mysql_free_result($resultsell);

?>