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

$maxRows_verificaciones = 20;
$pageNum_verificaciones = 0;
if (isset($_GET['pageNum_verificaciones'])) {
  $pageNum_verificaciones = $_GET['pageNum_verificaciones'];
}
$startRow_verificaciones = $pageNum_verificaciones * $maxRows_verificaciones;

mysql_select_db($database_conexion1, $conexion1);
$n_vi = $_GET['n_vi'];
$n_oc = $_GET['n_oc'];
$id_p = $_GET['id_p'];
$id_insumo = $_GET['id_insumo'];
$fecha = $_GET['fecha'];
//Filtra todos vacios
if($n_vi == '0' && $n_oc == '0' && $id_p == '0' && $id_insumo == '0' && $fecha == '0')
{
$row_verificaciones = $conexion->buscarListar("verificacion_insumos","*","ORDER BY n_vi DESC","",$maxRows_verificaciones,$pageNum_verificaciones,"" );
 
}
//FILTRO X VERIFICACION
if($n_vi != '0' && $n_oc == '0' && $id_p == '0' && $id_insumo == '0' && $fecha == '0')
{
  $row_verificaciones = $conexion->buscarListar("verificacion_insumos","*","ORDER BY n_vi DESC","",$maxRows_verificaciones,$pageNum_verificaciones,"WHERE n_vi='$n_vi'" );
//$query_verificaciones = "SELECT * FROM verificacion_insumos WHERE n_vi='$n_vi'";
}
//FILTRO X O.C.
if($n_vi == '0' && $n_oc != '0' && $id_p == '0' && $id_insumo == '0' && $fecha == '0')
{
  $row_verificaciones = $conexion->buscarListar("verificacion_insumos","*","ORDER BY n_oc_vi DESC","",$maxRows_verificaciones,$pageNum_verificaciones,"WHERE n_oc_vi='$n_oc'" );
//$query_verificaciones = "SELECT * FROM verificacion_insumos WHERE n_oc_vi='$n_oc'";
}
//FILTRO X PROVEEDOR
if($n_vi == '0' && $n_oc == '0' && $id_p != '0' && $id_insumo == '0' && $fecha == '0')
{
  $row_verificaciones = $conexion->buscarListar("verificacion_insumos","*","ORDER BY n_vi DESC","",$maxRows_verificaciones,$pageNum_verificaciones,"WHERE id_p_vi='$id_p'" );
//$query_verificaciones = "SELECT * FROM verificacion_insumos WHERE id_p_vi='$id_p' ORDER BY n_vi DESC";
}
//FILTRO X INSUMO
if($n_vi == '0' && $n_oc == '0' && $id_p == '0' && $id_insumo != '0' && $fecha == '0')
{
  $row_verificaciones = $conexion->buscarListar("verificacion_insumos","*","ORDER BY n_vi DESC","",$maxRows_verificaciones,$pageNum_verificaciones,"WHERE id_insumo_vi='$id_insumo'" );
//$query_verificaciones = "SELECT * FROM verificacion_insumos WHERE id_insumo_vi='$id_insumo' ORDER BY n_vi DESC";
}
//FILTRO X FECHA
if($n_vi == '0' && $n_oc == '0' && $id_p == '0' && $id_insumo == '0' && $fecha != '0')
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$row_verificaciones = $conexion->buscarListar("verificacion_insumos","*","ORDER BY n_vi DESC","",$maxRows_verificaciones,$pageNum_verificaciones,"WHERE fecha_vi >= '$fecha1' and fecha_vi < '$fecha2' " );
//$query_verificaciones = "SELECT * FROM verificacion_insumos WHERE fecha_vi >= '$fecha1' and fecha_vi < '$fecha2' ORDER BY n_vi DESC";
}
 

if (isset($_GET['totalRows_verificaciones'])) {
  $totalRows_verificaciones = $_GET['totalRows_verificaciones'];
} else {
  $totalRows_verificaciones = $conexion->conteo('verificacion_insumos'); 
} 
$totalPages_verificaciones = floor($totalRows_verificaciones/$maxRows_verificaciones)-1;


$queryString_verificaciones = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_verificaciones") == false && 
        stristr($param, "totalRows_verificaciones") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_verificaciones = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_verificaciones = sprintf("&totalRows_verificaciones=%d%s", $totalRows_verificaciones, $queryString_verificaciones);


$row_lista = $conexion->llenaSelect('verificacion_insumos','','ORDER BY n_vi DESC');

$row_lista_oc = $conexion->llenaSelect('orden_compra','','ORDER BY n_oc DESC');

$row_lista_p = $conexion->llenaListas('proveedor',"",'ORDER BY proveedor_p ASC','id_p,proveedor_p'); 

$row_lista_ano = $conexion->llenaSelect('anual','','ORDER BY anual DESC');

$row_lista_insumo = $conexion->llenaListas('insumo',"",'ORDER BY descripcion_insumo ASC','id_insumo,descripcion_insumo');  

 $queryString_verificaciones = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_verificaciones") == false && 
        stristr($param, "totalRows_verificaciones") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_verificaciones = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_verificaciones = sprintf("&totalRows_verificaciones=%d%s", $totalRows_verificaciones, $queryString_verificaciones);


?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<!-- sweetalert -->
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
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
                </ul>
               </div> 
               <div class="panel-body">
                 <br> 
                 <div ><!-- contenedor -->
                  <div class="row">
                    <div class="span12"> 
               </div>
             </div>  
             <div align="center">
              <form action="verificaciones_criticos1.php" method="get" name="consulta">
                <table class="table table-bordered table-sm" >
                  <tr>
                    <td id="titulo2">VERIFICACIONES ( INSUMOS CRITICOS ) </td>
                  </tr>
                  <tr>
                    <td id="fuente2">
                      <select class="selectsMedio busqueda" name="n_vi" id="n_vi">
                       <option value="0"<?php if (!(strcmp(0, $_GET['n_vi']))) {echo "selected=\"selected\"";} ?>>VERIF</option>
                             <?php  foreach($row_lista as $row_lista ) { ?>
                          <option value="<?php echo $row_lista['n_vi']?>"<?php if (!(strcmp($row_lista['n_vi'], $_GET['n_vi']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista['n_vi']?></option>
                       
                      <?php } ?>
                      </select>

                      <select class="selectsMedio busqueda" name="n_oc" id="n_oc">
                       <option value="0"<?php if (!(strcmp(0, $_GET['n_oc']))) {echo "selected=\"selected\"";} ?>>O.C.</option>
                             <?php  foreach($row_lista_oc as $row_lista_oc ) { ?>
                          <option value="<?php echo $row_lista_oc['n_oc']?>"<?php if (!(strcmp($row_lista_oc['n_oc'], $_GET['n_oc']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_oc['n_oc']?></option>
                         
                      <?php } ?>
                      </select>

                      <select class="selectsGrande busqueda" name="id_p" id="id_p">
                       <option value="0"<?php if (!(strcmp(0, $_GET['id_p']))) {echo "selected=\"selected\"";} ?>>PROVEEDOR</option>
                             <?php  foreach($row_lista_p as $row_lista_p ) { ?>
                          <option value="<?php echo $row_lista_p['id_p']?>"<?php if (!(strcmp($row_lista_p['id_p'], $_GET['id_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_p['proveedor_p']?></option>
                    
                      <?php } ?>
                      </select>
                      
                   </td>
                   </tr>
                   <tr>
                     <td id="fuente2">
                      <select class="selectsGrande busqueda" name="id_insumo" id="id_insumo">
                       <option value="0"<?php if (!(strcmp(0, $_GET['id_insumo']))) {echo "selected=\"selected\"";} ?>>INSUMO</option>
                             <?php  foreach($row_lista_insumo as $row_lista_insumo ) { ?>
                          <option value="<?php echo $row_lista_insumo['id_insumo']?>"<?php if (!(strcmp($row_lista_insumo['id_insumo'], $_GET['id_insumo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_insumo['descripcion_insumo']?></option>
                  
                      <?php } ?>
                      </select>
 
                    <select class="selectsMedio busqueda" name="fecha" id="fecha">
                       <option value="0"<?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>AÑO</option>
                           <?php  foreach($row_lista_ano as $row_lista_ano ) { ?>
                        <option value="<?php echo $row_lista_ano['anual']?>"<?php if (!(strcmp($row_lista_ano['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_lista_ano['anual']?></option>
                     
                    <?php } ?>
                    </select>

                    </td>
                  </tr>
                  <tr>
                    <td id="fuente2"><input type="submit" class="botonGeneral" name="Submit" value="FILTRO" onClick="if(consulta.n_vi.value=='0' && consulta.n_oc.value=='0' && consulta.id_p.value=='0' && consulta.id_insumo.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
                  </tr>
                </table>
              </form>
            </div>

            <div align="center">
              <form action="delete_listado.php" method="get" name="seleccion">
                <table class="table table-bordered table-sm">
                  <tr>
                    <td colspan="3" id="dato1"><input name="borrado" type="hidden" id="borrado" value="12" />
                      <input class="botonDel" name="Input" type="submit" value="Delete"/>  </td>
                      <td colspan="3"><?php $id=$_GET['id']; 
                      if($id >= '1') { ?>
                        <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
                      <?php }
                      if($id == '0') { ?>
                        <div id="numero1"> <?php echo "SELECCIONE PARA ELIMINAR"; ?> </div>
                        <?php }?></td>
                        <td id="dato2"><a href="verificaciones_criticos.php"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="orden_compra.php"><img src="images/o.gif" style="cursor:hand;" alt="ORDENES DE COMPRA" border="0"/></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/></a></td>
                      </tr>  
                      <tr id="tr1">
                        <td id="fuente2" width="3%"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                        <td id="titulo4" width="5%">N°</td>
                        <td id="titulo4" width="5%">O.C.</td>
                        <td id="titulo4" width="30%">PROVEEDOR</td>
                        <td id="titulo4" width="30%">INSUMO</td>
                        <td id="titulo4" width="10%">FECHA</td>
                        <td id="titulo4" width="10%">ENTREGA</td>
                      </tr>
                      <?php foreach($row_verificaciones as $row_verificaciones) {  ?>
                        <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                          <td id="dato2"><input name="borrar[]" type="checkbox" id="borrar[]" value="<?php echo $row_verificaciones['n_vi']; ?>" /></td>
                          <td id="dato2"><a href="verificacion_insumo_vista.php?n_vi= <?php echo $row_verificaciones['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"><strong><?php echo $row_verificaciones['n_vi']; ?></strong></a></td>
                          <td id="dato2"><a href="orden_compra_vista.php?n_oc= <?php echo $row_verificaciones['n_oc_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones['n_oc_vi']; ?></a></td>
                          <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_verificaciones['id_p_vi']; ?>" target="_top" style="text-decoration:none; color:#000000">
                             <?php 
                             $id_p = $row_verificaciones['id_p_vi']; 
                             $nump = $conexion->llenarCampos("proveedor", "WHERE id_p=$id_p ", " ", "proveedor_p"); 

                             if($nump >= '1') 
                             { 
                              echo $proveedor_p= $nump['proveedor_p'];
                            }
                            ?>
                         </a>
                       </td>
                      
                      <td id="dato1"><a href="insumo_edit.php?id_insumo= <?php echo $row_verificaciones['id_insumo_vi']; ?>" target="_top" style="text-decoration:none; color:#000000">
                         <?php 
                         $id_insumo=$row_verificaciones['id_insumo_vi']==''? '0' : $row_verificaciones['id_insumo_vi'];
                         $numi = $conexion->llenarCampos("insumo", "WHERE id_insumo=$id_insumo ", " ", "descripcion_insumo"); 

                         if($numi >= '1') 
                         { 
                           echo $descripcion_insumo=$numi['descripcion_insumo']; 
                         } 
                         ?> </a>
                       </td>
                         <td nowrap id="dato2"><a href="verificacion_insumo_vista.php?n_vi= <?php echo $row_verificaciones['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificaciones['fecha_vi']; ?> </a></td>
                         <td id="dato2"><a href="verificacion_insumo_vista.php?n_vi= <?php echo $row_verificaciones['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_verificaciones['entrega_vi']; ?></a></td>
                       </tr>
                     <?php } ?>
                   </table>
                 </form> 
               </div>   
               <table border="0" width="50%" align="center">
                <tr>
                  <td width="23%" id="dato2"><?php if ($pageNum_verificaciones > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_verificaciones=%d%s", $currentPage, 0, $queryString_verificaciones); ?>">Primero</a>
                  <?php } // Show if not first page ?>
                </td>
                <td width="31%" id="dato2"><?php if ($pageNum_verificaciones > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_verificaciones=%d%s", $currentPage, max(0, $pageNum_verificaciones - 1), $queryString_verificaciones); ?>">Anterior</a>
                <?php } // Show if not first page ?>
              </td>
              <td width="23%" id="dato2"><?php if ($pageNum_verificaciones < $totalPages_verificaciones) { // Show if not last page ?>
                <a href="<?php printf("%s?pageNum_verificaciones=%d%s", $currentPage, min($totalPages_verificaciones, $pageNum_verificaciones + 1), $queryString_verificaciones); ?>">Siguiente</a>
              <?php } // Show if not last page ?>
            </td>
            <td width="23%" id="dato2"><?php if ($pageNum_verificaciones < $totalPages_verificaciones) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_verificaciones=%d%s", $currentPage, $totalPages_verificaciones, $queryString_verificaciones); ?>">&Uacute;ltimo</a>
            <?php } // Show if not last page ?>
          </td>
        </tr>
      </table>
</td>
</div>
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
<?php
mysql_free_result($usuario);

mysql_free_result($verificaciones);

mysql_free_result($lista);

mysql_free_result($lista_oc);

mysql_free_result($lista_p);

mysql_free_result($lista_ano);

mysql_free_result($lista_insumo);
?>