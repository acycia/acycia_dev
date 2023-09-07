<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
  <?php
/*//initialize the session
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
  }*/
  ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_registros = 10;
$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;

$conexion = new ApptivaDB();

$row_lista = $conexion->llenaListas('proveedor','','ORDER BY proveedor_p DESC',"");//tabla, condicion, orden, distinct

$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
 
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$colname_busqueda= "-1";
$busqueda = $_GET["busqueda"];
 
$colname_busqueda= "-1";
if ( isset($_GET["valor"]) ) {

  $colname_busqueda = (get_magic_quotes_gpc()) ? $_GET["valor"] : addslashes($_GET["valor"]);
  $row_registros = $conexion->buscarListar("verificacion_insumos","*","ORDER BY n_vi DESC","",$maxRows_registros,$pageNum_registros,"where $busqueda ='$colname_busqueda'" );
}else{ 
  //Filtra todos vacios
  if($op== '0' && $id_ref=='0' && $cliente == '0' && $proceso == '0')
  {
  //$query_orden_produccion = "SELECT * FROM Tbl_orden_produccion WHERE b_borrado_op='0' ORDER BY id_op DESC";
  $row_registros = $conexion->buscarListar("Tbl_orden_produccion","*","ORDER BY id_op DESC","",$maxRows_registros,$pageNum_registros," WHERE b_borrado_op='0'" );
  }
  //FILTRA OP LLENO
  if($op != '0' && $id_ref=='0' && $cliente == '0' && $proceso == '0')
  {
  //$query_registros = "SELECT * FROM Tbl_registros WHERE b_borrado_op='0' AND id_op='$op'";
  $row_registros = $conexion->buscarListar("Tbl_registros","*","ORDER BY id_op DESC","",$maxRows_registros,$pageNum_registros," WHERE b_borrado_op='0' AND id_op='$op'" );
  }
}

//$registros=$conexion->buscarListar("verificacion_insumos","*","ORDER BY n_vi DESC","",$maxRows_registros,$pageNum_registros,"" );
 
if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $totalRows_registros = $conexion->conteo('verificacion_insumos'); 
} 
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;


$queryString_registros = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_registros") == false && 
        stristr($param, "totalRows_registros") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_registros = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_registros = sprintf("&totalRows_registros=%d%s", $totalRows_registros, $queryString_registros);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <script type="text/javascript" src="js/usuario.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/listado.js"></script> <!-- importante dejarlo resalta las filas-->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" /> <!-- importante dejarlo imprime todo lo nuevo-->
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
                   <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/cabecera.jpg"></div>
                   <div class="span12"><h3> PROCESO DE COMPRAS  &nbsp;&nbsp;&nbsp; </h3></div>
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
                 <div >  <!--  class="container" si lo quito se amplia todo el listado-->
                  <div class="row">
                    <div class="span12">
                     <table id="tabla2">
                       <tr>
                         <td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td> 
                       </tr>
                       <tr>
                        <td id="subtitulo">
                         LISTADO
                       </td>
                     </tr>
                     <tr>
                      <td id="numero2">
                       <h5 id="numero2" > </h5>
                     </td> 
                   </tr>
                 </table> 
               </div>
             </div>
             <br> 
          <br>
          <!-- grid --> 

          <div class="container-fluid"> 
             <h3 id="dato2"><strong>VERIFICACIONES - INGRESADAS</strong></h3>

             <div class="span3"> <strong >FECHAS RECIBIDO: </strong>
              <input  type="date" id="fecha_vi" name="fecha_vi" value="<?php echo $_GET['valor']; ?>" class='campostext buscar' > 
              </div>

              <div class="span3"> <strong >OP: </strong>
                <select name="op" id="op" class="busqueda selectsMini">
                    <option value="0"<?php if (!(strcmp(0, $_GET['op']))) {echo "selected=\"selected\"";} ?>>O.P</option>
                       <?php  foreach($row_lista as $row_lista ) { ?>
                    <option value="<?php echo $row_lista['id_op']; ?>"<?php if (!(strcmp($row_lista['id_op'], $_GET['op']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_lista['id_op']); ?> 
                  </option>
                <?php } ?>
                </select>  
               </div>
             <hr>
              <div class="row align-items-start">  
                 <div class="col-lg-1" ><strong>O.C</strong></div>
                 <div class="col-lg-1" ><strong>INSUMO</strong></div>
                 <div class="col-lg-2" ><strong>RECIBIO</strong></div>
                 <div class="col-lg-2" ><strong>FECHA RECIBIDO </strong></div>
                 <div class="col-lg-1" ><strong>FACTURA</strong></div>
                 <div class="col-lg-1" ><strong>ENTREGA</strong></div>
                 <div class="col-lg-1" ><strong>CANT. PEDIDA</strong></div>
                 <div class="col-lg-1" ><strong>SALDO ANTES</strong></div>
                 <div class="col-lg-1" ><strong>CANT. RECIBIDA</strong></div>
                 <div class="col-lg-1" ><strong>FALTAN</strong></div>
                 <div class="col-lg-1" ><strong>VER</strong></div>   
              </div> 
             <?php foreach($row_registros as $row_verificacion_insumo) {  ?>
            <div class="row celdaborde1">
              <div class="col-lg-1" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['n_oc_vi']; ?></a></p>
              </div>
              <div class="col-lg-1" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000">
                  <?php   
                    $row_ver_nuevo = $conexion->buscar('insumo','id_insumo',$row_verificacion_insumo['id_insumo_vi']); 
                     echo $row_ver_nuevo['descripcion_insumo']=='' ? $row_verificacion_insumo['id_insumo_vi'] : $row_ver_nuevo['descripcion_insumo']; 
                  ?> 
                  </a>
                </p>
              </div>
              <div class="col-lg-2" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['recibido_vi']; ?></a></p>
              </div> 
              <div class="col-lg-2" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['fecha_vi']; ?></a></p>
              </div>
              <div class="col-lg-1" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['factura_vi']; ?></a></p>
              </div>
              <div class="col-lg-1" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['entrega_vi']; ?></a></p>
              </div>
              <div class="col-lg-1" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['cantidad_solicitada_vi']; ?></a></p>
              </div>
              <div class="col-lg-1" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['verificacion_det']; ?></a></p>
              </div>
              <div class="col-lg-1" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['cantidad_recibida_vi']; ?></a></p>
              </div>
              <div class="col-lg-1" id="fondo_2">
                <p><a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"> <?php echo $row_verificacion_insumo['faltantes_vi']; ?></a></p>
              </div>
              <div class="col-lg-1" id="fondo_2">
                <p>
                  <!-- <a href="javascript:eliminar_fantasma('id_fantasma',<?php echo $row_impresion_vista['id_rp']; ?>,'produccion_registro_impresion_vista.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;"/></a>  -->
                  <a href="insumos_interno_entrada_salida_editar.php?id=<?php echo $row_verificacion_insumo['n_vi']; ?>" target="_top" style="text-decoration:none; color:#000000"><img src="images/pincel.PNG" alt="EDITAR" title="EDITAR" border="0" style="cursor:hand;" width="20" height="18" /> </a>
                 <a class="botonGeneral" style="text-decoration:none; "href="?id=<?php echo $dato['id_pedido']; ?>">SALIR</a>
                 <a href="?action=editar&id=<?php echo $r->id; ?>" style="text-decoration:none;"  >Editar</a> 
               <a class="botonGMini" target="_blank"  href="view_index.php?c=ocomercial&a=Crud&id=<?php echo $row_orden_compra['id_pedido']; ?>&columna=id_pedido&tabla=tbl_orden_compra_historico">VER HISTORICO DE MODIFICACIONES</a> </p>
              </div>

              <div class="panel-footer" > 
                 <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $dato['id_pedido']; ?>">SALIR</a>  -->
                 <a class="botonFinalizar" style="text-decoration:none; "href="?id=<?php echo $dato['id_pedido']; ?>">SALIR</a> 
                
              </div>
              
            </div>
            <?php  } ?>
          </div> 
             <!-- tabla para paginacion opcional -->
             <table border="0" width="50%" align="center">
               <tr>
                 <td width="23%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
                   <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">Primero</a>
                 <?php } // Show if not first page ?>
               </td>
               <td width="31%" id="dato2"><?php if ($pageNum_registros > 0) { // Show if not first page ?>
                 <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">Anterior</a>
               <?php } // Show if not first page ?>
             </td>
             <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
               <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente</a>
             <?php } // Show if not last page ?>
           </td>
           <td width="23%" id="dato2"><?php if ($pageNum_registros < $totalPages_registros) { // Show if not last page ?>
             <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo</a>
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
  
  $(document).ready(function(){
 
   $(".buscar").change(function(){
       // form = $("#envio").serialize();
       //var name = document.getElementsByName("buscar")[0];
       var name = $(this).attr('name'); 
       var value = $(this).val();  
        url = '<?php echo BASE_URL; ?>'; 
        window.location.assign(url+'verificacion_insumo_listado.php?busqueda='+name+'&valor='+value)
    });
  });
  /*$("#ejemplo2").change(function(){   });*/
</script>


<?php
mysql_free_result($usuario);

?>
