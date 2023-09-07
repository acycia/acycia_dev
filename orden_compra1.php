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
$currentPage = $_SERVER["PHP_SELF"];

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 


$maxRows_ordenes_compra = 20;
$pageNum_ordenes_compra = 0;
if (isset($_GET['pageNum_ordenes_compra'])) {
  $pageNum_ordenes_compra = $_GET['pageNum_ordenes_compra'];
}
$startRow_ordenes_compra = $pageNum_ordenes_compra * $maxRows_ordenes_compra;

mysql_select_db($database_conexion1, $conexion1);
$n_oc = $_GET['n_oc'];
$id_p = $_GET['id_p'];
$fecha = $_GET['fecha'];
$tipo_pedido = $_GET['tipo_pedido'];
//Filtra todos vacios
if($n_oc == '0' && $id_p == '0' && $fecha == '0' && $tipo_pedido=='0')
{
  $row_ordenes_compra = $conexion->buscarListar("orden_compra","*","ORDER BY n_oc DESC","",$maxRows_ordenes_compra,$pageNum_ordenes_compra," " );
  //$query_ordenes_compra = "SELECT * FROM orden_compra ORDER BY n_oc DESC";
}
//Filtra oc lleno
if($n_oc != '0' && $id_p == '0' && $fecha == '0' && $tipo_pedido=='0')
{
  $row_ordenes_compra = $conexion->buscarListar("orden_compra","*","ORDER BY n_oc DESC","",$maxRows_ordenes_compra,$pageNum_ordenes_compra,"WHERE n_oc='$n_oc'" );
  //$query_ordenes_compra = "SELECT * FROM orden_compra WHERE n_oc='$n_oc'";
}
//Filtra proveedor lleno
if($n_oc == '0' && $id_p != '0' && $fecha == '0' && $tipo_pedido=='0')
{
  $row_ordenes_compra = $conexion->buscarListar("orden_compra","*","ORDER BY n_oc DESC","",$maxRows_ordenes_compra,$pageNum_ordenes_compra,"WHERE id_p_oc='$id_p'" );
 // $query_ordenes_compra = "SELECT * FROM orden_compra WHERE id_p_oc='$id_p' ORDER BY n_oc DESC";
}
//Filtra fecha lleno
if($n_oc == '0' && $id_p == '0' && $fecha != '0' && $tipo_pedido=='0')
{
  $fecha1 = "$fecha-01-01";
  $fecha2 = "$fecha-12-01";
  $row_ordenes_compra = $conexion->buscarListar("orden_compra","*","ORDER BY n_oc DESC","",$maxRows_ordenes_compra,$pageNum_ordenes_compra,"WHERE fecha_pedido_oc BETWEEN '$fecha1' and '$fecha2'" );
  //$query_ordenes_compra = "SELECT * FROM orden_compra WHERE fecha_pedido_oc BETWEEN '$fecha1' and '$fecha2' ORDER BY n_oc DESC";
}
//Filtra proveedor y fecha lleno
if($n_oc == '0' && $id_p != '0' && $fecha != '0' && $tipo_pedido=='0')
{
  $fecha1 = "$fecha-01-01";
  $fecha2 = "$fecha-12-01";
  $row_ordenes_compra = $conexion->buscarListar("orden_compra","*","ORDER BY n_oc DESC","",$maxRows_ordenes_compra,$pageNum_ordenes_compra,"WHERE id_p_oc='$id_p' AND fecha_pedido_oc BETWEEN '$fecha1' and '$fecha2'" );
  //$query_ordenes_compra = "SELECT * FROM orden_compra WHERE id_p_oc='$id_p' AND fecha_pedido_oc BETWEEN '$fecha1' and '$fecha2' ORDER BY n_oc DESC";
}
//Filtra pedido
if($n_oc == '0' && $id_p == '0' && $fecha == '0' && $tipo_pedido!='0')
{
  $row_ordenes_compra = $conexion->buscarListar("orden_compra","*","ORDER BY n_oc DESC","",$maxRows_ordenes_compra,$pageNum_ordenes_compra,"WHERE tipo_pedido='$tipo_pedido'" );
  //$query_ordenes_compra = "SELECT * FROM orden_compra WHERE tipo_pedido='$tipo_pedido'";
}
//Filtra proveedor y pedido
if($n_oc == '0' && $id_p != '0' && $fecha == '0' && $tipo_pedido!='0')
{
  $row_ordenes_compra = $conexion->buscarListar("orden_compra","*","ORDER BY n_oc DESC","",$maxRows_ordenes_compra,$pageNum_ordenes_compra,"WHERE id_p_oc='$id_p' and tipo_pedido='$tipo_pedido'" );
  //$query_ordenes_compra = "SELECT * FROM orden_compra WHERE id_p_oc='$id_p' and tipo_pedido='$tipo_pedido'";
}
/*$query_limit_ordenes_compra = sprintf("%s LIMIT %d, %d", $query_ordenes_compra, $startRow_ordenes_compra, $maxRows_ordenes_compra);
$ordenes_compra = mysql_query($query_limit_ordenes_compra, $conexion1) or die(mysql_error());
$row_ordenes_compra = mysql_fetch_assoc($ordenes_compra);*/

if (isset($_GET['totalRows_ordenes_compra'])) {
  $totalRows_ordenes_compra = $_GET['totalRows_ordenes_compra'];
} else {
  $totalRows_ordenes_compra = $conexion->conteo('orden_compra'); 
} 
$totalPages_ordenes_compra = ceil($totalRows_ordenes_compra/$maxRows_ordenes_compra)-1;



$row_lista = $conexion->llenaSelect('orden_compra','','ORDER BY n_oc DESC');
 

$row_proveedores = $conexion->llenaSelect('proveedor','','ORDER BY proveedor_p DESC');

 
$row_ano = $conexion->llenaSelect('anual','','ORDER BY anual DESC');

 


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

session_start();
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <script type="text/javascript" src="js/usuario.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="AjaxControllers/updateAutorizar.js"></script>
  
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
    <link href="select2/css/select2.min.css" rel="stylesheet"/>
    <script src="select2/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/general.css"/>

    <!-- css Bootstrap hace mas grande el formato-->
    <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <script>
      $(document).ready(function() { $(".busqueda").select2(); });
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
                    <div class="panel-heading"><h3>ORDENES DE COMPRA  &nbsp;&nbsp; </h3></div>
                </div>
                <div class="panel-heading" align="left" ></div><!--color azul-->
                 <div id="cabezamenu">
                  <ul id="menuhorizontal">
                    <li><?php echo $row_usuario['nombre_usuario']; ?></li>
                    <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                    <li><a href="menu.php">MENU PRINCIPAL</a></li>
                    <li><a href="compras.php">GESTION COMPRAS</a></li>
                    <li><a href="insumos_interno_listado.php">SALIDA INGRESOS INTERNO</a></li>
                  </ul>
               </div> 
               <div class="panel-body">
                 <br> 
                 <div ><!-- contenedor -->
                  <div class="row">
                    <div class="span12"> 
               </div>
             </div>
           
           <form action="orden_compra1.php" method="get" name="consulta">
  
                <table class="table table-bordered table-sm">
                  <tr>
                    <td id="fuente2">

                      <select name="n_oc" id="n_oc"  class="selectsMedio busqueda">
                          <option value="0"<?php if (!(strcmp(0, $_GET['n_oc']))) {echo "selected=\"selected\"";} ?>>O.C.</option>
                             <?php  foreach($row_lista as $row_lista ) { ?>
                          <option value="<?php echo $row_lista['n_oc']; ?>"<?php if (!(strcmp($row_lista['n_oc'], $_GET['n_oc']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_lista['n_oc']); ?> 
                        </option>
                      <?php } ?>
                      </select>

                      <select name="id_p" id="id_p"  class="selectsMedio busqueda">
                          <option value="0"<?php if (!(strcmp(0, $_GET['id_p']))) {echo "selected=\"selected\"";} ?>>PROVEEDOR</option>
                             <?php  foreach($row_proveedores as $row_proveedores ) { ?>
                          <option value="<?php echo $row_proveedores['id_p']; ?>"<?php if (!(strcmp($row_proveedores['id_p'], $_GET['id_p']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_proveedores['proveedor_p']); ?> 
                        </option>
                      <?php } ?>
                      </select>

                      <select name="fecha" id="fecha"  class="selectsMedio busqueda">
                          <option value="0"<?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>A&Ntilde;O</option>
                             <?php  foreach($row_ano as $row_ano ) { ?>
                          <option value="<?php echo $row_ano['anual']; ?>"<?php if (!(strcmp($row_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_ano['anual']); ?> 
                        </option>
                      <?php } ?>
                      </select> &nbsp;&nbsp;&nbsp;&nbsp;
                    <select name="tipo_pedido" class="selectsMedio busqueda">
                        <option value=""<?php if (!(strcmp("", $_GET['tipo_pedido']))) {echo "selected=\"selected\"";} ?>>Tipo Pedido</option>
                        <option value="Nacional"<?php if (!(strcmp("Nacional", $_GET['tipo_pedido']))) {echo "selected=\"selected\"";} ?> >Nacional</option>
                        <option value="Importacion"<?php if (!(strcmp("Importacion", $_GET['tipo_pedido']))) {echo "selected=\"selected\"";} ?>>Importacion</option>
                        <option value="Exportacion"<?php if (!(strcmp("Exportacion", $_GET['tipo_pedido']))) {echo "selected=\"selected\"";} ?>>Exportacion</option> 
                    </select>
                    <input type="submit" name="Submit" value="FILTRO" class="botonGeneral" onClick="if(consulta.n_oc.value=='0' && consulta.id_p.value=='0' && consulta.fecha.value=='0' && consulta.tipo_pedido.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
                  </tr>
                  <tr>
                    <td id="fuente1"><img src="images/ingreso4.gif" alt="FALTAN INGRESOS (COMPRAS)" width="24" height="26" style="cursor:hand;" title="FALTAN INGRESOS (COMPRAS)" border="0"> Tiene ingresos pendientes a la bodega
                      <img src="images/ok.gif" alt="INGRESOS OK" width="26" height="26" style="cursor:hand;" title="INGRESOS OK" border="0">Tiene todos los ingresos al dia
                      <img src="images/opcion3.gif" alt="CERO INGRESOS" width="22" height="23" style="cursor:hand;" title="CERO INGRESOS" border="0"> La O.C no tiene items 'o' no tiene ingresos.</td>
                    </tr> 
                      <tr>
                     <td id="fuente1">&nbsp;</td>
                    </tr>
                  </table> 
                </form>

                <form action="delete_listado.php" method="get" name="seleccion">
                  <table class="table table-bordered table-sm">
                    <tr>
                      <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="11" />
                        <input name="Input" type="submit" value="Delete" onClick="return eliminar_listados()"/>  </td>
                        <td colspan="3"><?php $id=$_GET['id']; 
                        if($id >= '1') { ?> 
                          <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
                        <?php }
                        if($id == '0') { ?>
                          <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>      <?php }?></td>
                          <td colspan="3" id="dato3"><a href="orden_compra_add.php"><img src="images/mas.gif" alt="ADD ORDEN DE COMPRA" border="0" style="cursor:hand;"/></a><a href="orden_compra_ingresos.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/></a><a href="verificacion_insumo_listado.php"><img src="images/11.png" style="cursor:hand;" alt="LISTADO VERIF" title="LISTADO VERIF" border="0"/></a></td>
                        </tr>  
                        <tr id="tr1">
                          <td id="fuente2"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                          <td id="titulo4">N° O.C</td>
                          <td id="titulo4">PEDIDO</td>
                          <td id="titulo4">FECH. RECIBIDO</td>
                          <td id="titulo4">PROVEEDOR</td>
                          <td id="titulo4">TIPO PED.</td>
                          <td id="titulo4">RESPONSABLE</td>
                          <td id="titulo4"><a href="verificaciones_criticos.php"><img src="images/v.gif" alt="VERIFICACIONES (CRITICOS)" title="VERIFICACIONES (CRITICOS)" border="0" style="cursor:hand;"/></a></td>
                          <td id="titulo4">ENTRADAS</td>
                          <td id="titulo4">CALIDAD</td>
                        </tr>
                        <?php foreach($row_ordenes_compra as $row_ordenes_compra) {  ?>
                          <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                            <td id="dato2"><input name="borrar[]" type="checkbox" id="borrar[]" value="<?php echo $row_ordenes_compra['n_oc']; ?>" /></td>
                            <td id="dato3"><a href="orden_compra_vista.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_ordenes_compra['n_oc']; ?></strong></a></td>
                            <td id="dato3"><a href="orden_compra_vista.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['fecha_pedido_oc']; ?></a></td>
                            <td id="dato2"><a href="orden_compra_vista.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['fecha_entrega_oc']; ?></a></td>
                            <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_ordenes_compra['id_p_oc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php 
                            $id_p=$row_ordenes_compra['id_p_oc'];
                            $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'"; 
                            $resultp=mysql_query($sqlp); 
                            $nump=mysql_num_rows($resultp); 
                            if($nump >= '1') 
                             { $proveedor_p=mysql_result($resultp,0,'proveedor_p'); echo $proveedor_p; }
                           else { echo "";	} ?>
                         </a></td>
                         <td id="dato1"><a href="orden_compra_vista.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['tipo_pedido']; ?></a></td>
                         <td id="dato1"><a href="orden_compra_vista.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_ordenes_compra['responsable_oc']; ?></a></td>
                         <td id="dato1"><a href="verificacion_insumo_oc.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>">
                          <?php 
                          $n_oc=$row_ordenes_compra['n_oc'];
                          $sqldet="SELECT id_insumo FROM orden_compra_detalle,insumo WHERE orden_compra_detalle.n_oc_det='$n_oc' AND orden_compra_detalle.id_insumo_det=insumo.id_insumo AND insumo.tipo_insumo in ('1','2')"; 
                          $resultdet=mysql_query($sqldet); 
                          $numdet=mysql_num_rows($resultdet); 
                          if($numdet >= '1') 
                          { 
                           $sqlv="SELECT id_det_vi,n_oc_vi FROM verificacion_insumos WHERE n_oc_vi='$n_oc' AND id_det_vi!=''"; 
                           $resultv=mysql_query($sqlv); 
                           $numv=mysql_num_rows($resultv); 
                           if($numv >= '1') 
                             { ?>
                              <img src="images/v.gif" alt="VERIF x O.C." border="0" style="cursor:hand;" title="VERIFICADO"/>
                            <?php }
                            else { ?>
                              <img src="images/falta.gif" alt="VERIF x O.C." border="0" style="cursor:hand;" title="VERIF x O.C."/>
                            <?php }
                          } else { echo ""; }
                          ?>
                        </a></td>
                        <td id="dato2"><?php 
                        $ingresos=$row_ordenes_compra['n_oc'];
                        if($ingresos!=''){
                          $sqldetalle="SELECT orden_compra_detalle.id_det,orden_compra_detalle.n_oc_det, SUM(orden_compra_detalle.cantidad_det) AS cantidadet FROM orden_compra_detalle WHERE orden_compra_detalle.n_oc_det='$ingresos'";
                          $resultdetalle = mysql_query($sqldetalle);
                          $numdetalle = mysql_num_rows($resultdetalle);
                          
                          $id_det = mysql_result($resultdetalle,0,'id_det');
                          $cantidadet = mysql_result($resultdetalle,0,'cantidadet');
                          
                          $sqlentrada="SELECT TblIngresos.id_det_ing, SUM(TblIngresos.ingreso_ing) AS sumaingre FROM TblIngresos WHERE TblIngresos.oc_ing='$ingresos'";
                          $resultentrada = mysql_query($sqlentrada);
                          $numentrada = mysql_num_rows($resultentrada);
                          
                          $sumaingre = mysql_result($resultentrada,0,'sumaingre');
                          
                          if($sumaingre!='' || $cantidadet !='')
                          {
                            ?>
                            <?php if( $cantidadet > $sumaingre ) {?>
                              <a href="orden_compra_edit.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>&id_p_oc=<?php echo $row_ordenes_compra['id_p_oc'] ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/ingreso4.gif" alt="FALTAN INGRESOS (COMPRAS)" width="31" height="26" style="cursor:hand;" title="FALTAN INGRESOS (COMPRAS)" border="0"></a>
                            <?php }else{?>
                              <a href="orden_compra_edit.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>&id_p_oc=<?php echo $row_ordenes_compra['id_p_oc'] ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/ok.gif" alt="INGRESOS OK" width="26" height="26" style="cursor:hand;" title="INGRESOS OK" border="0"></a>
                            <?php } 
                          }else{?>
                            <a href="orden_compra_edit.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>&id_p_oc=<?php echo $row_ordenes_compra['id_p_oc'] ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/opcion3.gif" alt="CERO INGRESOS" width="22" height="23" style="cursor:hand;" title="CERO INGRESOS" border="0"></a>
                          <?php }?>
                        <?php }?>
                      </td>
                      <td>
                          <p><?php 
                               $n_oc = $row_ordenes_compra['n_oc'];
                               $row_fact = $conexion->llenarCampos("verificacion_insumos", "WHERE n_oc_vi='".$n_oc."' ", " ", "autorizado ");  
                              ?> 
                            <?php if($row_fact['autorizado']=='SI'): ?>
                            <a href="verificacion_insumo_oc.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>"><img src="images/accept.png" alt="PASO CALIDAD" title="PASO CALIDAD" border="0" style="cursor:hand;" width="20" height="18" /></a>
                            <?php else: ?>
                              <a href="verificacion_insumo_oc.php?n_oc=<?php echo $row_ordenes_compra['n_oc']; ?>"><img src="images/salir.gif" alt="SIN CALIDAD" title="SIN CALIDAD" border="0" style="cursor:hand;" width="20" height="18" /></a> 
                           <?php endif; ?>
                          </p>   
                      </td>
                    </tr>
                  <?php } ?>
                </table>
                </form>
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
</div><!-- contenedor -->
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
<!-- js Bootstrap-->
<script src="bootstrap-4/js/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<?php
mysql_free_result($usuario);
mysql_free_result($ordenes_compra);

mysql_free_result($lista);

mysql_free_result($proveedores);

mysql_free_result($ano);

mysql_close($conexion1);

?>