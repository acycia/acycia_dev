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

 $colname_usuario = "1";
 if (isset($_SESSION['MM_Username'])) {
   $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
 }
  
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$row_bolsas_oc = $conexion->buscarListar("orden_compra_bolsas","*","ORDER BY n_ocb DESC","",$maxRows_bolsas_oc,$pageNum_bolsas_oc,"" );

$row_ordenes_compra = $conexion->llenaSelect('orden_compra_bolsas','','ORDER BY n_ocb DESC');

$row_proveedores = $conexion->llenaSelect('proveedor','','ORDER BY proveedor_p ASC');

$row_bolsas = $conexion->llenaSelect('material_terminado_bolsas','','ORDER BY nombre_bolsa ASC');

$row_referencias = $conexion->llenaSelect('Tbl_referencia','','ORDER BY id_ref DESC');

$row_anual = $conexion->llenaSelect('anual','','ORDER BY anual DESC');


$maxRows_bolsas_oc = 20;
$pageNum_bolsas_oc = 0;
if (isset($_GET['pageNum_bolsas_oc'])) {
  $pageNum_bolsas_oc = $_GET['pageNum_bolsas_oc'];
}
$startRow_bolsas_oc = $pageNum_bolsas_oc * $maxRows_bolsas_oc;

mysql_select_db($database_conexion1, $conexion1);
$n_ocb = $_GET['n_ocb'];
$id_p = $_GET['id_p'];
$id_bolsa = $_GET['id_bolsa'];
$id_ref = $_GET['id_ref'];
$fecha = $_GET['fecha'];
/*FILTRA TODOS VACIOS*/
if($n_ocb == '0' && $id_p == '0' && $id_bolsa == '0' && $id_ref == '0' && $fecha == '0')
{
  $row_bolsas_oc = $conexion->buscarListar("orden_compra_bolsas","*","ORDER BY n_ocb DESC","",$maxRows_bolsas_oc,$pageNum_bolsas_oc,"" );
//$query_bolsas_oc = "SELECT * FROM orden_compra_bolsas ORDER BY n_ocb DESC";
}
/*FILTRA OC*/
if($n_ocb != '0' && $id_p == '0' && $id_bolsa == '0' && $id_ref == '0' && $fecha == '0')
{
  $row_bolsas_oc = $conexion->buscarListar("orden_compra_bolsas","*","ORDER BY n_ocb DESC","",$maxRows_bolsas_oc,$pageNum_bolsas_oc,"WHERE n_ocb='$n_ocb'" );
//$query_bolsas_oc = "SELECT * FROM orden_compra_bolsas WHERE n_ocb='$n_ocb' ORDER BY n_ocb DESC";
}
/*FILTRA PROVEEDOR*/
if($n_ocb == '0' && $id_p != '0' && $id_bolsa == '0' && $id_ref == '0' && $fecha == '0')
{
  $row_bolsas_oc = $conexion->buscarListar("orden_compra_bolsas","*","ORDER BY n_ocb DESC","",$maxRows_bolsas_oc,$pageNum_bolsas_oc,"WHERE id_p_ocb='$id_p'" );
$query_bolsas_oc = "SELECT * FROM orden_compra_bolsas WHERE id_p_ocb='$id_p' ORDER BY n_ocb DESC";
}
/*FILTRA ROLLO*/
if($n_ocb == '0' && $id_p == '0' && $id_bolsa != '0' && $id_ref == '0' && $fecha == '0')
{
  $row_bolsas_oc = $conexion->buscarListar("orden_compra_bolsas","*","ORDER BY n_ocb DESC","",$maxRows_bolsas_oc,$pageNum_bolsas_oc,"WHERE id_bolsa_ocb='$id_bolsa'" );
//$query_bolsas_oc = "SELECT * FROM orden_compra_bolsas WHERE id_bolsa_ocb='$id_bolsa' ORDER BY n_ocb DESC";
}
/*FILTRA REFERENCIA*/
if($n_ocb == '0' && $id_p == '0' && $id_bolsa == '0' && $id_ref != '0' && $fecha == '0')
{
  $row_bolsas_oc = $conexion->buscarListar("orden_compra_bolsas","*","ORDER BY n_ocb DESC","",$maxRows_bolsas_oc,$pageNum_bolsas_oc,"WHERE id_ref_ocb='$id_ref'" );
//$query_bolsas_oc = "SELECT * FROM orden_compra_bolsas WHERE id_ref_ocb='$id_ref' ORDER BY n_ocb DESC";
}
/*FILTRA FECHA*/
if($n_ocb == '0' && $id_p == '0' && $id_bolsa == '0' && $id_ref == '0' && $fecha != '0')
{
$fecha1 = "$fecha-01-01";
$fecha2 = $fecha + 1;
$fecha2 = "$fecha2-01-01";
$row_bolsas_oc = $conexion->buscarListar("orden_compra_bolsas","*","ORDER BY n_ocb DESC","",$maxRows_bolsas_oc,$pageNum_bolsas_oc,"WHERE fecha_pedido_ocb >= '$fecha1' AND fecha_pedido_ocb < '$fecha2'" );

//$query_bolsas_oc = "SELECT * FROM orden_compra_bolsas WHERE fecha_pedido_ocb >= '$fecha1' AND fecha_pedido_ocb < '$fecha2' ORDER BY n_ocb DESC";
}
 
if (isset($_GET['totalRows_bolsas_oc'])) {
  $totalRows_bolsas_oc = $_GET['totalRows_bolsas_oc'];
} else {
  $totalRows_bolsas_oc = $conexion->conteo('orden_compra_bolsas'); 
} 
$totalPages_bolsas_oc = floor($totalRows_bolsas_oc/$maxRows_bolsas_oc)-1;

$queryString_bolsas_oc = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_bolsas_oc") == false && 
        stristr($param, "totalRows_bolsas_oc") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_bolsas_oc = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_bolsas_oc = sprintf("&totalRows_bolsas_oc=%d%s", $totalRows_bolsas_oc, $queryString_bolsas_oc);

?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
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
                    <div class="panel-heading"><h3>ORDENES DE COMPRA ( BOLSAS )</h3></div>
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
             <form action="bolsas_oc1.php" method="get" name="consulta">

              <table class="table table-bordered table-sm">

                <tr>
                  <td id="fuente2">
                    <select class="selectsMedio busqueda" name="n_ocb" id="n_ocb">
                           <option value="0"<?php if (!(strcmp(0, $_GET['n_ocb']))) {echo "selected=\"selected\"";} ?>>O.C.</option>
                                 <?php  foreach($row_ordenes_compra as $row_ordenes_compra ) { ?>
                              <option value="<?php echo $row_ordenes_compra['n_ocb']?>"<?php if (!(strcmp($row_ordenes_compra['n_ocb'], $_GET['n_ocb']))) {echo "selected=\"selected\"";} ?>><?php echo $row_ordenes_compra['n_ocb']?></option>
                          
                          <?php } ?>
                          </select> 
                          
                        <select class="selectsGrande busqueda" name="id_p" id="id_p">
                         <option value="0"<?php if (!(strcmp(0, $_GET['id_p']))) {echo "selected=\"selected\"";} ?>>PROVEEDOR</option>
                               <?php  foreach($row_proveedores as $row_proveedores ) { ?>
                            <option value="<?php echo $row_proveedores['id_p']?>"<?php if (!(strcmp($row_proveedores['id_p'], $_GET['id_p']))) {echo "selected=\"selected\"";} ?>><?php echo $row_proveedores['proveedor_p']?></option>
                        
                        <?php } ?>
                        </select>

                         <br>
                         <select class="selectsMedio busqueda" name="id_bolsa" id="id_bolsa">
                          <option value="0"<?php if (!(strcmp(0, $_GET['id_bolsa']))) {echo "selected=\"selected\"";} ?>>BOLSA</option>
                                <?php  foreach($row_bolsas as $row_bolsas ) { ?>
                             <option value="<?php echo $row_bolsas['id_bolsa']?>"<?php if (!(strcmp($row_bolsas['id_bolsa'], $_GET['id_bolsa']))) {echo "selected=\"selected\"";} ?>><?php echo $row_bolsas['nombre_bolsa']?></option>
                         
                         <?php } ?>
                         </select> 

                         <select class="selectsMedio busqueda" name="id_ref" id="id_ref">
                         <option value="0"<?php if (!(strcmp(0, $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>>REF</option>
                                <?php  foreach($row_referencias as $row_referencias ) { ?>
                             <option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $_GET['id_ref']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
                         
                         <?php } ?>
                         </select>

                         
                         <select class="selectsMedio busqueda" name="fecha" id="fecha">
                          <option value="0"<?php if (!(strcmp(0, $_GET['fecha']))) {echo "selected=\"selected\"";} ?>>A&Ntilde;O</option>
                                <?php  foreach($row_anual as $row_anual ) { ?>
                            <option value="<?php echo $row_anual['anual']?>"<?php if (!(strcmp($row_anual['anual'], $_GET['fecha']))) {echo "selected=\"selected\"";} ?>><?php echo $row_anual['anual']?></option>
                         
                         <?php } ?>
                         </select>
                <input type="submit" name="Submit" value="FILTRO" class="botonGeneral" onClick="if(consulta.n_ocb.value=='0' && consulta.id_p.value=='0' && consulta.id_bolsa.value=='0' && consulta.id_ref.value=='0' && consulta.fecha.value=='0') { alert('DEBE SELECCIONAR UNA OPCION'); }"/></td>
              </tr>
            </table>
          </div>
        </form>

        <form action="delete_listado.php" method="get" name="seleccion">
          <table class="table table-bordered table-sm">
            <tr>
              <td id="dato2"><input name="borrado" type="hidden" id="borrado" value="17" />
                <input name="Input" type="submit" value="X"/></td>
                <td colspan="6" id="dato1"><?php $id=$_GET['id']; 
                if($id >= '1') { ?> 
                  <div id="acceso1"> <?php echo "ELIMINACION COMPLETA"; ?> </div>
                <?php }
                if($id == '0') { ?>
                  <div id="numero1"> <?php echo "No se ha seleccionado ningún registro para eliminar"; ?> </div>
                <?php }
                if($id == '') { ?>
                  <div id="dato1"> <?php echo "Si elimina una O.C., sera definitivamente."; ?> </div>      <?php }
                  ?></td>
                  <td colspan="2" id="dato2"><a href="bolsas_oc.php" target="_top"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="bolsas_oc_add.php" target="_top"><img src="images/mas.gif" alt="ADD O.C. BOLSA" border="0" style="cursor:hand;"/></a><a href="bolsas.php" target="_top"><img src="images/b.gif" alt="BOLSAS" border="0" style="cursor:hand;"/></a></td>
                </tr>  
                <tr id="tr1">
                  <td id="titulo4"><input name="chulo1" type="checkbox" onClick="if(seleccion.chulo1.checked) { seleccionar_todo() } else{ deseleccionar_todo() } "/></td>
                  <td id="titulo4">N&deg;O.C.</td>
                  <td id="titulo4">PROVEEDOR</td>
                  <td id="titulo4">NOMBRE DE LA BOLSA </td>
                  <td id="titulo4">REF. </td>
                  <td id="titulo4">FECHA. PED.</td>
                  <td id="titulo4">FECHA ENT. </td>
                  <td id="titulo4">PEDIDO</td>
                  <td id="titulo4"><a href="bolsas_verificacion.php"><img src="images/v.gif" border="0"></a></td>
                </tr>
                <?php foreach($row_bolsas_oc as $row_bolsas_oc) {  ?>
                  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                    <td id="dato2"><input name="borrar[]" type="checkbox" value="<?php echo $row_bolsas_oc['n_ocb']; ?>" /></td>
                    <td id="dato2"><strong><a href="bolsas_oc_vista.php?n_ocb=<?php echo $row_bolsas_oc['n_ocb']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsas_oc['n_ocb']; ?></a></strong></td>
                    <td id="dato1"><a href="proveedor_vista.php?id_p=<?php echo $row_bolsas_oc['id_p_ocb']; ?>" target="_top" style="text-decoration:none; color:#000000">
                     <?php 
                      $id_p=$row_bolsas_oc['id_p_ocb'];

                     if($id_p!=''){

                       $nump = $conexion->llenarCampos("proveedor", "WHERE id_p=$id_p ", " ", "proveedor_p"); 

                       if($nump >= '1') 
                        { 
                          echo $proveedor_p= $nump['proveedor_p'];
                         }else{

                       } 
                     } 

                      ?>
                     </a></td>
                     <td id="dato1"><a href="bolsas_vista.php?id_bolsa=<?php echo $row_bolsas_oc['id_bolsa_ocb']; ?>" target="_top" style="text-decoration:none; color:#000000">
                      <?php 
                      $id_bolsa=$row_bolsas_oc['id_bolsa_ocb'];
                      
                      if($id_bolsa!=''){

                        $numbolsa = $conexion->llenarCampos("material_terminado_bolsas", "WHERE id_bolsa = $id_bolsa ", " ", "nombre_bolsa"); 
                      
                          if($numbolsa >='1') { 
                            echo $nombre_bolsa = $numbolsa['nombre_bolsa']; 
                           }
                         
                        } 
                       ?>
                     </a></td>
                     <td id="dato2"><a href="referencia_vista.php?id_ref=<?php echo $row_bolsas_oc['id_ref_bolsa']; ?>" target="_top" style="text-decoration:none; color:#000000">
                        <?php 
                        $ref=$row_bolsas_oc['id_ref_ocb'];

                       if($ref!=''){

                             $numref = $conexion->llenarCampos("Tbl_referencia", "WHERE id_ref = $ref", " ", "cod_ref"); 
                      
                           if($numref >='1') { 
                             $cod_ref= $numref['cod_ref']; 
                           }
                            echo $cod_ref; 
                       } 
                      ?>
                     </a></td>
                     <td id="dato2"><a href="bolsas_oc_vista.php?n_ocb=<?php echo $row_bolsas_oc['n_ocb']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsas_oc['fecha_pedido_ocb']; ?></a></td>
                     <td id="dato2"><a href="bolsas_oc_vista.php?n_ocb=<?php echo $row_bolsas_oc['n_ocb']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_bolsas_oc['fecha_entrega_ocb']; ?></a></td>
                     <td id="dato2"><a href="bolsas_oc_vista.php?n_ocb=<?php echo $row_bolsas_oc['n_ocb']; ?>" target="_top" style="text-decoration:none; color:#000000"><?php if($row_bolsas_oc['pedido_ocb']=='0') { echo "Nuevo"; } if($row_bolsas_oc['pedido_ocb']=='1') { echo "Reimpresion"; } ?></a></td>
                     <td id="dato2"><a href="bolsas_oc_verificacion.php?n_ocb=<?php echo $row_bolsas_oc['n_ocb']; ?>">
                      <?php 
                      $n_ocb=$row_bolsas_oc['n_ocb']; if($n_ocb != '') { 

                        $numv = $conexion->llenarCampos("verificacion_bolsas", "WHERE n_ocb_vb = '$n_ocb' ", "ORDER BY n_vb DESC", "n_ocb_vb"); 
                      
                       if($numv >='1') { ?>
                        <img src="images/v.gif" border="0">
                      <?php } else { ?>
                        <img src="images/falta.gif" border="0">
                      <?php } } ?>
                    </a></td>
                  </tr>
                <?php }  ?>
              </table>
            </form>	
            <table border="0" width="50%" align="center">
              <tr>
                <td width="23%" id="dato2"><?php if ($pageNum_bolsas_oc > 0) { // Show if not first page ?>
                  <a href="<?php printf("%s?pageNum_bolsas_oc=%d%s", $currentPage, 0, $queryString_bolsas_oc); ?>">Primero</a>
                <?php } // Show if not first page ?>
              </td>
              <td width="31%" id="dato2"><?php if ($pageNum_bolsas_oc > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_bolsas_oc=%d%s", $currentPage, max(0, $pageNum_bolsas_oc - 1), $queryString_bolsas_oc); ?>">Anterior</a>
              <?php } // Show if not first page ?>
            </td>
            <td width="23%" id="dato2"><?php if ($pageNum_bolsas_oc < $totalPages_bolsas_oc) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_bolsas_oc=%d%s", $currentPage, min($totalPages_bolsas_oc, $pageNum_bolsas_oc + 1), $queryString_bolsas_oc); ?>">Siguiente</a>
            <?php } // Show if not last page ?>
          </td>
          <td width="23%" id="dato2"><?php if ($pageNum_bolsas_oc < $totalPages_bolsas_oc) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_bolsas_oc=%d%s", $currentPage, $totalPages_bolsas_oc, $queryString_bolsas_oc); ?>">&Uacute;ltimo</a>
          <?php } // Show if not last page ?>
        </td>
      </tr>
    </table> 
</div>
</div>
</td>
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

mysql_free_result($ordenes_compra);

mysql_free_result($proveedores);

mysql_free_result($bolsas);

mysql_free_result($referencias);

mysql_free_result($anual);

mysql_free_result($bolsas_oc);
?>