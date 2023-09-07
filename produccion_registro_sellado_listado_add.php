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
$query_orden_produccion = "SELECT *, COUNT(DISTINCT rollo_rp) as rollos, SUM(`int_kilos_prod_rp`) as kilos FROM Tbl_reg_produccion WHERE id_proceso_rp >= '4' GROUP BY id_op_rp DESC";
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
//LISTADOS
mysql_select_db($database_conexion1, $conexion1);
$query_lista_op = "SELECT * FROM Tbl_orden_produccion WHERE Tbl_orden_produccion.b_borrado_op='0' AND Tbl_orden_produccion.id_op IN (SELECT TblSelladoRollo.id_op_r FROM TblSelladoRollo WHERE TblSelladoRollo.id_op_r = Tbl_orden_produccion.id_op) ORDER BY Tbl_orden_produccion.id_op DESC";
$lista_op = mysql_query($query_lista_op, $conexion1) or die(mysql_error());
$row_lista_op = mysql_fetch_assoc($lista_op);
$totalRows_lista_op = mysql_num_rows($lista_op);

mysql_select_db($database_conexion1, $conexion1);
$query_ref_op = "SELECT id_ref, cod_ref FROM Tbl_referencia order by id_ref desc";
$ref_op = mysql_query($query_ref_op, $conexion1) or die(mysql_error());
$row_ref_op = mysql_fetch_assoc($ref_op);
$totalRows_ref_op = mysql_num_rows($ref_op);

mysql_select_db($database_conexion1, $conexion1);
$query_mensual = "SELECT * FROM mensual ORDER BY id_mensual ASC";
$mensual = mysql_query($query_mensual, $conexion1) or die(mysql_error());
$row_mensual = mysql_fetch_assoc($mensual);
$totalRows_mensual = mysql_num_rows($mensual);

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

  <!-- css Bootstrap hace mas grande el formato-->
    <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
  <script>
      $(document).ready(function() { $(".busqueda").select2(); });
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
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="produccion_registro_extrusion_listado.php">EXTRUSION</a></li>
                    <li><a href="sellado_numeracion_listado.php">SELLADAS</a></li>
                  </ul>
               </div> 
               <div class="panel-body">
                 <br> 
                 <div >
                  <div class="row">
                    <div class="span12"> 
               </div>
             </div>
             <form action="produccion_registro_sellado_listado_add2.php" method="get" name="form1" id="form1">
              <table class="table table-bordered table-sm">
                <tr>
                  <td colspan="6" id="fuente2">
                    REGISTRO DE ORDENES DE PRODUCCION EN SELLADO
                  </td>
                </tr>
                <tr>
                  <td colspan="6" id="fuente2">
                    <select name="op" id="op" class="busqueda selectsMini">
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
                    </select> 
                    <select name="id_ref" id="id_ref" class="busqueda selectsMini">
                      <option value="0">REF</option>
                      <?php
                      do {  
                        ?>
                        <option value="<?php echo $row_ref_op['cod_ref']?>"> <?php  echo $row_ref_op['cod_ref']?>
                      </option>
                      <?php
                    } while ($row_ref_op = mysql_fetch_assoc($ref_op));
                    $rows = mysql_num_rows($ref_op);
                    if($rows > 0) {
                      mysql_data_seek($ref_op, 0);
                      $row_ref_op = mysql_fetch_assoc($ref_op);
                    }
                    ?>
                  </select>
                  <select id='anyo' name='anyo' class="" >
                          <option value="0">AÑOS</option>
                              <?php  foreach($row_anual as $row_anual ) { ?>
                           <option value="<?php echo $row_anual['anual']; ?>"><?php echo htmlentities($row_anual['anual']); ?> </option>
                       <?php } ?>
                     </select>

                  <select name="mes" id="mes" class="">
                    <option value="0">MES</option>
                    <?php
                    do {  
                      ?>
                      <option value="<?php echo $row_mensual['id_mensual']?>">
                        <?php  echo $row_mensual['mensual']?>
                      </option>
                      <?php
                    } while ($row_mensual = mysql_fetch_assoc($mensual));
                    $rows = mysql_num_rows($mensual);
                    if($rows > 0) {
                      mysql_data_seek($mensual, 0);
                      $row_mensual = mysql_fetch_assoc($mensual);
                    }
                    ?>
                  </select>

                  <input class="botonUpdate" type="submit" name="consultar" id="consultar" value="consultar" onClick="ListadoProduccion();">
                 </td>
                </tr>

                <tr>
                  <td colspan="3" id="dato1">Nota: si en el la columna '<strong>Proceso</strong>', aparecen las siguientes notificaciones tenga en cuenta:
                  </td>
                  <td colspan="3" id="dato1">Nota: si en el la columna '<strong>Mezcla</strong>', aparecen las siguientes notificaciones tenga en cuenta:
                  </td>
                </tr>
                <tr>
                  <td colspan="3" id="dato1">
                    <img src="images/ok.gif" width="20" height="20" alt="ROLLO LIQUIDADO"title="ROLLO LIQUIDADO" border="0" style="cursor:hand;"/>O.P Liquidada<br>
                    <img src="images/falta6.gif" width="20" height="20" alt="O.P IMPRESA"title="O.P IMPRESA" border="0" style="cursor:hand;"/>O.P tiene pendientes por liquidar
                  * Columna kilos en rojo; le falta kilos por sellar, (sellado tiene un 15% de tolerancia).<br>
                  </td>
                  <td colspan="3" id="dato1">
                  <img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" />Si tiene las mezclas de impresion <br>
                  <img src="images/e_rojo.gif" style="cursor:hand;" alt="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" title="LE FALTO AGREGAR LAS CARACTERISTICA DE ESTA REFERENCIA EN IMPRESION" border="0" />No tiene mezclas de impresion
                   </td>
                  </tr>
                </table>
              </form>  
  <form action="delete_listado.php" method="get" name="seleccion">
      <table class="table table-bordered table-sm">
          <tr>
             <td colspan="2">
                <?php 
                    $var=$row_usuario['tipo_usuario'];
                   if ($var==1) {?><!--<input name="borrado" type="hidden" id="borrado" value="39" />
                   <input name="Input" type="submit" onClick="return eliminar_sellado();" value="Eliminar"/>-->
               <?php } ?>   
     </td>
     <td colspan="7" id="dato3"><?php $id=$_GET['id']; 
     if($id == '1') { ?><div id="acceso1"> <?php echo "CAMBIO DE ESTADO A INACTIVA COMPLETA"; ?> </div> <?php }
     if($id == '2') { ?><div id="numero1"> <?php echo "SE ACTIVO CORRECTAMENTE"; ?> </div> <?php }
     if($id == '0') { ?><div id="numero1"> <?php echo "NO HA SELECCIONADO"; ?> </div> <?php }?></td>
     <td colspan="3" id="dato3"><?php  if ($row_usuario['tipo_usuario']==1) {?><a href="consumo_tiempos_sell.php"><img src="images/rt.gif" alt="LISTADO DE TIEMPOS"title="LISTADO DE TIEMPOS" border="0" style="cursor:hand;"></a><a href="consumo_materias_primas_sell.php"><img src="images/mp.gif" alt="LISTADO DE MATERIAS PRIMAS"title="LISTADO DE MATERIAS PRIMAS" border="0" style="cursor:hand;"></a><a href="despacho_direccion.php"><img src="images/d.gif" alt="DESPACHO"title="DESPACHO" border="0" style="cursor:hand;"></a>
      <?php } ?><a href="produccion_registro_sellado_listado.php"><img src="images/opciones.gif" alt="LISTADO SELLADAS"title="LISTADO SELLADAS" border="0" style="cursor:hand;"></a><a href="hoja_maestra_listado.php"><img src="images/m.gif" alt="HOJAS MAESTRAS"title="HOJAS MAESTRAS" border="0" style="cursor:hand;"></a>
      <a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="REFRESCAR"title="REFRESCAR" border="0" style="cursor:hand;"/></a>
      <input type="button" value="Exporta Excel" onClick="window.location = 'produccion_exportar_excel.php?tipoListado=3'" />
    </td>
  </tr>  
  <tr id="tr1">
    <!--    <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>-->
    <td nowrap="nowrap"id="titulo4">N&deg; O.P </td>
    <td nowrap="nowrap"id="titulo4">ROLLOS</td>
    <td nowrap="nowrap"id="titulo4">FECHA INGRESO</td>
    <td nowrap="nowrap"id="titulo4">CLIENTE</td>
    <td nowrap="nowrap"id="titulo4">REF. </td>
    <td nowrap="nowrap"id="titulo4">VER.</td>
    <td nowrap="nowrap"id="titulo4">KILOS</td>
    <td nowrap="nowrap" id="titulo4">KILOS DESP.</td>
    <td nowrap="nowrap" id="titulo4">METROXMINUTO</td>
    <td nowrap="nowrap"id="titulo4">OPERARIO</td>
    <td nowrap="nowrap"id="titulo4">REVISOR</td>
    <td nowrap="nowrap"id="titulo4">ADD ROLLO</td>
    <td nowrap="nowrap"id="titulo4">MEZCLA</td>
    <td nowrap="nowrap"id="titulo4">PROCESO</td>
  </tr>
  <?php do { ?>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
<!--      <td id="dato2"><?php  
    $id_r=$row_orden_produccion['id_op_rp'];
    $rolloI=$row_orden_produccion['rollo_r'];
    $sqlregpro="SELECT id_rp,id_ref_rp,b_borrado_rp,fecha_ini_rp FROM Tbl_reg_produccion WHERE id_op_rp='$id_r' AND rollo_rp='$rolloI' AND id_proceso_rp='2'";
    $resultregpro= mysql_query($sqlregpro);
    $numregpro= mysql_num_rows($resultregpro);
    if($numregpro >='1')
    { 
    $borrado = mysql_result($resultregpro, 0, 'b_borrado_rp');
    }?><input name="borrar[]" type="checkbox" value="<?php echo $row_orden_produccion['id_r']; ?>" />   
  </td>-->
  <td nowrap="nowrap" id="dato2"><strong><?php echo $row_orden_produccion['id_op_rp']; ?></strong></td>
  <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['rollos']; ?></td>
  <td nowrap="nowrap" id="dato2"><?php echo $row_orden_produccion['fecha_ini_rp']; ?></td>
  <td nowrap="nowrap" id="dato2">
    <?php 
    $op_c=$row_orden_produccion['id_op_rp'];
    $sqln="SELECT * FROM Tbl_orden_produccion, cliente WHERE Tbl_orden_produccion.id_op='$op_c' AND Tbl_orden_produccion.int_cliente_op=cliente.id_c"; 
    $resultn=mysql_query($sqln); 
    $numn=mysql_num_rows($resultn); 
    if($numn >= '1') 
      { $nombre_cliente_c=mysql_result($resultn,0,'nombre_c'); $ca = ($nombre_cliente_c); echo $ca; }
    else { echo ""; 
  }?>
</a></td>
<td id="dato2"><?php echo $row_orden_produccion['int_cod_ref_rp']; ?></td>
<td id="dato2"><?php 
echo $row_orden_produccion['version_ref_rp'];?></td>
<td id="dato2">
  <?php 
$op_c=$row_orden_produccion['id_op_rp'];
$sqlkiloIm="SELECT SUM(int_total_kilos_rp) as kilos FROM Tbl_reg_produccion WHERE id_proceso_rp='2' AND id_op_rp='$op_c' ORDER BY rollo_rp DESC"; 
$resultkiloIm=mysql_query($sqlkiloIm); 
$numkiloIm=mysql_num_rows($resultkiloIm); 
if($numkiloIm >= '1') 
  { $cant_kilo_imp=mysql_result($resultkiloIm,0,'kilos');} 
if($cant_kilo_imp!=''){
  $porc_tolerar = porcentaje($row_orden_produccion['kilos'],15);
  if ($row_orden_produccion['kilos'] + $porc_tolerar  <  $cant_kilo_imp)//15 es el margen de tolerancia
  { ?> <div id="numero1">
    <strong>
    <?php echo $row_orden_produccion['kilos'] ; ?>
  </strong>
  </div>
<?php }else{ echo $row_orden_produccion['kilos'];}
}else{
  echo $row_orden_produccion['kilos']; 
} 
?>
</td>
<td id="dato2"><?php echo $row_orden_produccion['int_kilos_desp_rp'];?></td>
<td id="dato2"><?php echo $row_orden_produccion['int_metroxmin_rp'];?></td>

<td nowrap="nowrap"id="dato2">
  <?php  
  $id_emp=$row_orden_produccion['int_cod_empleado_rp'];
  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
  $resultemp= mysql_query($sqlemp);
  $numemp= mysql_num_rows($resultemp);
  if($numemp >='1')
  { 
    $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
  }else{echo "N/A";}?>
</td>
<td nowrap="nowrap"id="dato2">
  <?php  
  $id_emp=$row_orden_produccion['int_cod_liquida_rp'];
  $sqlemp="SELECT * FROM empleado WHERE codigo_empleado='$id_emp' ";
  $resultemp= mysql_query($sqlemp);
  $numemp= mysql_num_rows($resultemp);
  if($numemp >='1')
  { 
    $nombre = mysql_result($resultemp, 0, 'nombre_empleado');echo $nombre; 
  }else{echo "N/A";}?>
</td>
<td id="dato2">
 <?php  
 $op_c=$row_orden_produccion['id_op_rp'];
 $sqlno="SELECT COUNT(DISTINCT rollo_r) AS rols,MAX(rolloParcial_r) AS parcial FROM TblSelladoRollo WHERE id_op_r='$op_c'"; 
 $resultno=mysql_query($sqlno); 
 $numno=mysql_num_rows($resultno);
 if($numno > '0') 
  { $rolloS=mysql_result($resultno,0,'rols');
$parcial=mysql_result($resultno,0,'parcial');}  
$sqlnE="SELECT COUNT(DISTINCT rollo_r) AS role FROM TblImpresionRollo WHERE id_op_r='$op_c'"; 
$resultnE=mysql_query($sqlnE); 
$numnE=mysql_num_rows($resultnE);
if($numnE > '0') 
  { $rolloE=mysql_result($resultnE,0,'role');}  

?>       
<?php if($rolloS < $rolloE){?>           
  <a href="javascript:verFoto('produccion_registro_sellado_add.php?id_op=<?php echo $row_orden_produccion['id_op_rp']; ?>','800','700')"><img src="images/mas.gif" alt="ADD ROLLOS"title="ADD ROLLOS" border="0" style="cursor:hand;" /></a>
  <?php 
}else { ?>      
  <a href="javascript:verFoto('produccion_sellado_listado_rollos.php?id_op_r=<?php echo $row_orden_produccion['id_op_rp']; ?>','800','700')"><img src="images/completo.gif" alt="COMPLETOS"title="COMPLETOS" border="0" style="cursor:hand;" /></a> 
<?php }?>
</td>
<td id="dato2">
  <a href="javascript:popUp('produccion_caract_sellado_vista.php?id_ref=<?php echo $row_orden_produccion['id_ref_rp'];?>','800','600')"><img src="images/e.gif" style="cursor:hand;" alt="VISUALIZAR CARACTERISTICA" title="VISUALIZAR CARACTERISTICA" border="0" /></a></td>
  <td nowrap="nowrap" id="dato2">
    <?php 
    if($rolloS > $row_orden_produccion['rollos']){?><a href="javascript:verFoto('produccion_registro_sellado_total_vista.php?id_op=<?php echo $row_orden_produccion['id_op_rp']; ?>','870','710')"><img src="images/falta6.gif" width="28" height="20" alt="ROLLOS PENDIENTES POR LIQUIDAR" title="ROLLOS PENDIENTES POR LIQUIDAR" border="0" style="cursor:hand;"/></a><?php } else  {?>
      <a href="javascript:verFoto('produccion_registro_sellado_total_vista.php?id_op=<?php echo $row_orden_produccion['id_op_rp']; ?>','870','710')"><img src="images/ok.gif" alt="LIQUIDADO" title="LIQUIDADO" border="0" style="cursor:hand;" /></a>
      <?php }?></td>      
    </tr>
  <?php } while ($row_orden_produccion = mysql_fetch_assoc($orden_produccion)); ?>
</table>
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
                    var3:" Tbl_orden_produccion.b_borrado_op='0' AND Tbl_orden_produccion.id_op IN (SELECT TblSelladoRollo.id_op_r FROM TblSelladoRollo WHERE TblSelladoRollo.id_op_r = Tbl_orden_produccion.id_op)",//where
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
                        var5:"cod_ref",
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

       var vista = 'produccion_registro_selladon_listado_add2.php';
      
          enviovarListados(form,vista);  
       
  }

</script>

<?php
mysql_free_result($usuario);

mysql_free_result($orden_produccion);
?>