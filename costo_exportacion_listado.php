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
$conexion = new ApptivaDB();

$currentPage = $_SERVER["PHP_SELF"];

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_lista = $conexion->llenaSelect('TblCostoExportacion',"","ORDER BY n_ce DESC");  

$row_clientes = $conexion->llenaSelect('cliente',"","ORDER BY nombre_c ASC");  
 

$maxRows_exportaciones = 20;
$pageNum_exportaciones = 0;
if (isset($_GET['pageNum_exportaciones'])) {
  $pageNum_exportaciones = $_GET['pageNum_exportaciones'];
}
$startRow_exportaciones = $pageNum_exportaciones * $maxRows_exportaciones;

mysql_select_db($database_conexion1, $conexion1);
$query_exportaciones = "SELECT * FROM TblCostoExportacion ORDER BY n_ce DESC";
$query_limit_exportaciones = sprintf("%s LIMIT %d, %d", $query_exportaciones, $startRow_exportaciones, $maxRows_exportaciones);
$exportaciones = mysql_query($query_limit_exportaciones, $conexion1) or die(mysql_error());
$row_exportaciones = mysql_fetch_assoc($exportaciones);


mysql_select_db($database_conexion1, $conexion1);
$query_ano = "SELECT * FROM anual ORDER BY anual DESC";
$ano = mysql_query($query_ano, $conexion1) or die(mysql_error());
$row_ano = mysql_fetch_assoc($ano);
$totalRows_ano = mysql_num_rows($ano);

if (isset($_GET['totalRows_exportaciones'])) {
  $totalRows_exportaciones = $_GET['totalRows_exportaciones'];
} else {
  $all_exportaciones = mysql_query($query_exportaciones);
  $totalRows_exportaciones = mysql_num_rows($all_exportaciones);
}
$totalPages_exportaciones = ceil($totalRows_exportaciones/$maxRows_exportaciones)-1;

 
$queryString_exportaciones = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_exportaciones") == false && 
        stristr($param, "totalRows_exportaciones") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_exportaciones = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_exportaciones = sprintf("&totalRows_exportaciones=%d%s", $totalRows_exportaciones, $queryString_exportaciones);

/*session_start();*/
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/listado.js"></script>

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
  
 <!-- css Bootstrap-->
 <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
<?php echo $conexion->header('listas'); ?>


                  <form action="costo_exportacion_listado2.php" method="get" name="consulta">
                    <table class="table table-bordered table-sm">
                      <tr>
                        <td id="titulo2">FACTURAS EXPORTACION</td>
                      </tr>
                      <tr>
                        <td id="fuente2">
                          <select class="busqueda selectsMedio" name="n_ce" id="n_ce">
                             <option value="0">FACTURA</option>
                             <?php  foreach($row_lista as $row_lista ) { ?>
                              <option value="<?php echo $row_lista['n_ce']?>"><?php echo $row_lista['n_ce']?></option>
                            <?php } ?>
                          </select>


                        <select class="busqueda selectsGrande"  name="id_c" id="id_c">
                           <option value="0">CLIENTE</option>
                           <?php  foreach($row_clientes as $row_clientes ) { ?>
                            <option value="<?php echo $row_clientes['id_c']?>"><?php echo $row_clientes['nombre_c']?></option>
                          <?php } ?>
                        </select>

                        <select name="fecha" id="fecha">
                          <option value="0">A&Ntilde;O</option>
                          <?php
                          do {  
                            ?><option value="<?php echo $row_ano['anual']?>"><?php echo $row_ano['anual']?></option>
                            <?php
                          } while ($row_ano = mysql_fetch_assoc($ano));
                          $rows = mysql_num_rows($ano);
                          if($rows > 0) {
                            mysql_data_seek($ano, 0);
                            $row_ano = mysql_fetch_assoc($ano);
                          }
                          ?>
                        </select><input type="submit" class="botonGMini" name="Submit" value="FILTRO" onClick="if(consulta.n_ce.value=='0' && consulta.id_c.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
                      </tr>
                    </table>
                  </form>
                  <form action="delete_listado.php" method="get" name="seleccion">
                    <table class="table table-bordered table-sm">
                      <tr>
                        <td colspan="2" id="dato1"><input name="borrado" type="hidden" id="borrado" value="44" />
                          <input name="Input" class="botonDel" type="submit" value="Delete"/>  </td>
                          <td colspan="3"><?php $id=$_GET['id']; 
                          if($id >= '1') { ?> 
                            <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
                          <?php }
                          if($id == '0') { ?>
                            <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>      <?php }?></td>
                            <td colspan="2" id="dato2"><a href="costo_exportacion_add.php"><img src="images/mas.gif" alt="ADD COSTO EXPORTACION" title="ADD COSTO EXPORTACION" border="0" style="cursor:hand;"/></a><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
                          </tr>  
                          <tr id="tr1">
                            <td id="fuente2"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                            <td id="titulo4">FACTURA N&deg;</td>
                            <td id="titulo4">PEDIDO</td>
                            <td id="titulo4">ENTREGA </td>
                            <td id="titulo4">CLIENTE</td>
                            <td id="titulo4">RESPONSABLE</td>
                          </tr>
                          <?php do { ?>
                            <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                              <td id="dato2"><input name="borrar[]" type="checkbox" id="borrar[]" value="<?php echo $row_exportaciones['n_ce']; ?>" /></td>
                              <td id="dato2"><a href="costo_exportacion_vista.php?n_ce=<?php echo $row_exportaciones['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_exportaciones['n_ce']; ?></strong></a></td>
                              <td id="dato2"><a href="costo_exportacion_vista.php?n_ce=<?php echo $row_exportaciones['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_exportaciones['fecha_pre_ce']; ?></a></td>
                              <td id="dato2"><a href="costo_exportacion_vista.php?n_ce=<?php echo $row_exportaciones['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_exportaciones['fecha_ven_ce']; ?></a></td>
                              <td id="dato1"><a href="costo_exportacion_vista.php?n_ce=<?php echo $row_exportaciones['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php 
                              $id_c=$row_exportaciones['id_c_ce'];
                              $sqlc="SELECT nombre_c FROM cliente WHERE id_c='$id_c'"; 
                              $resultc=mysql_query($sqlc); 
                              $numc=mysql_num_rows($resultc); 
                              if($numc >= '1') 
                               { $nombre_c=mysql_result($resultc,0,'nombre_c'); echo $nombre_c; }
                             else { echo "";	} ?>
                           </a></td>
                           <td id="dato1"><a href="costo_exportacion_vista.php?n_ce=<?php echo $row_exportaciones['n_ce']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_exportaciones['responsable_ce']; ?></a></td>
                         </tr>
                       <?php } while ($row_exportaciones = mysql_fetch_assoc($exportaciones)); ?>
                     </table>
                   </form>
                           <table id="tabla1">
                             <tr>
                               <td id="dato1" width="25%"><?php if ($pageNum_exportaciones > 0) { // Show if not first page ?>
                                 <a href="<?php printf("%s?pageNum_exportaciones=%d%s", $currentPage, 0, $queryString_exportaciones); ?>">Primero</a>
                               <?php } // Show if not first page ?>
                             </td>
                             <td id="dato1" width="25%"><?php if ($pageNum_exportaciones > 0) { // Show if not first page ?>
                               <a href="<?php printf("%s?pageNum_exportaciones=%d%s", $currentPage, max(0, $pageNum_exportaciones - 1), $queryString_exportaciones); ?>">Anterior</a>
                             <?php } // Show if not first page ?>
                           </td>
                           <td id="dato1" width="25%"><?php if ($pageNum_exportaciones < $totalPages_exportaciones) { // Show if not last page ?>
                             <a href="<?php printf("%s?pageNum_exportaciones=%d%s", $currentPage, min($totalPages_exportaciones, $pageNum_exportaciones + 1), $queryString_exportaciones); ?>">Siguiente</a>
                           <?php } // Show if not last page ?>
                         </td>
                         <td id="dato1" width="25%"><?php if ($pageNum_exportaciones < $totalPages_exportaciones) { // Show if not last page ?>
                           <a href="<?php printf("%s?pageNum_exportaciones=%d%s", $currentPage, $totalPages_exportaciones, $queryString_exportaciones); ?>">&Uacute;ltimo</a>
                         <?php } // Show if not last page ?>
                       </td>
                     </tr>
                   </table>
<?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($exportaciones);

mysql_free_result($lista);

mysql_free_result($clientes);

mysql_free_result($ano);
?>