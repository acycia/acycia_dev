<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?>
<?php
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
?>
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


$conexion = new ApptivaDB();
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
  <script type="text/javascript" src="js/addCamposCompras.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/elimina.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/actualiza.js"></script>
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
<body  >
  <script>
      $(document).ready(function() { $(".busqueda").select2(); });
  </script>
<form action="view_index.php?c=cinventario&a=Guardar" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table style="width: 100%">
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                 <div class="row" >
                   <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/cabecera.jpg"></div>
                   <div class="span12"><h3> INVENTARIO DISPONIBLE&nbsp;&nbsp; </h3></div>
                 </div>
                 <div class="panel-heading" align="left" ></div><!--color azul-->
                    <div id="cabezamenu">
                     <ul id="menuhorizontal">
                      <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                      <?php if($_SESSION['superacceso']): ?>
                      <li> 
                       <!-- <a class="botonDel" id="btnDelMaster" onclick='eliminar("<?php echo $_GET['id']; ?>","<?php echo $_GET['columna']; ?>","referencia", "?c=cinventario&a=Eliminar","1"  )' type="button" >DELETE</a> -->
                        </li>
                      <?php endif;?> 
                    </ul>
                </div> 
               <div class="panel-body">
                 <br> 
                 <div ><!--  SI QUITO  class="container" SE ALINEA A LA IZQUIERDA TODO EL CONTENIDO DE ESTE -->
                  <div class="row">
                    <div class="span12">
                     <table id="tabla2">
                       <tr>
                        <td id="subtitulo">
                         INVENTARIO 
                       </td>
                       <td id="subtitulo">VERSIÓN: 01 </td>
                       <td id="subtitulo">Fecha Actual: <?php echo $fechaActual = date('Y-m-d'); ?></td>
                       <td><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="INPRIMIR" title="INPRIMIR">&nbsp;&nbsp;
                       <a href="view_index.php?c=cinventario&a=Inicio"><img src="images/ciclo1.gif" title="Volver" alt="Volver" border="0" style="cursor:hand;"/></a></td>
                     </tr> 
                 </table> 
               </div>
             </div> 
             <br>
             <!-- grids --> 
             <div class="row" >
               <div class="span12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                <?php if($_SESSION['superacceso']): ?>
                <!-- <button id='botondeenvio' type="submit" onclick="submitform(); return false;"><img type="image" style="width: 30px; height: 40px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="GUARDAR"title="GUARDAR"></button>&nbsp;&nbsp;&nbsp;&nbsp; 
                <button id='botondeeditar' type="button" onclick="editarform(); return false;"><img type="image" style="width: 30px; height: 40px;" name="botondeeditar" id="botondeeditar" src="images/editar.PNG" alt="EDITAR"title="EDITAR"></button>  -->
                <?php endif;?> 
               </div>
             </div> 
              
             <table class="table table-striped" id="items" >
         
                    <?php foreach($this->general as $general) { $general; } ?>
                
                    <tr>
                      <td   >
                        <div class="alert alert-danger divScrollAlerta" role="alert" style="text-align: left;">
                          <span>Referencias del Dia sin Stock: </span><br>
                          <?php foreach($this->refcero as $refcero ) { ?>
                            <?php echo $refcero['refcero']; ?>
                          <?php } ?>
                        </div>
                      </td>
                    </tr>
                  <tr>
                    <td colspan="3"> 
                      REFERENCIA #
                       <input name="referencia" id="referencia" value="" type="text" placeholder="Referencia" size="20" required="required" ><?php //echo $_GET['columna'] =='referencia' ? $_GET['id'] : $general['referencia']; ?>
                       FECHA
                       <input name="fecha" id="fecha" value="" type="text" placeholder="Fecha" size="20" required="required" > 
                       <a href="view_index.php?c=cinventario&a=Inicio"><img src="images/ciclo1.gif" title="Volver" alt="Volver" border="0" style="cursor:hand;"/></a>
                    </td> 
                </tr>
                <tr> 
                </tr> 
                <tr>
                    <td><em style="display: none;  align-items: center; justify-content: center;color: red; " id="mensaje" ></em> </td>
                </tr>
                <tr> 
                   <th scope="col" >DISPONIBLE INVETARIO</th>
                   <!-- <th scope="col" >DISPONIBLE DESPACHO</th> -->
                 </tr>
               
               <tbody > 
                 <tr>
                  <td nowrap='nowrap' >

                  <div class="row align-items-start" >
                  <div style="width: 20px;" ><strong></strong></div> 
                    <div style="width: 180px;" ><strong>REFERENCIA</strong></div> 
                    <div style="width: 200px;" ><strong>FECHA</strong></div>
                    <div style="width: 200px;" ><strong>INVENTARIO</strong></div> 
                    <div style="width: 200px;" ><strong>DESPACHO</strong></div>
                    <div style="width: 200px;" ><strong>DISPONIBLE</strong></div>
                    <div style="width: 200px;" ><strong>DESPACHO HOY</strong></div>
                    <div style="width: 200px;" ><strong>NUEVO STOCK</strong></div>
                  </div> 
                   <div class="divScrollGigante" id="itemspedido" role="alert" style="text-align: left;"> 
                      <div class="row celdaborde1">  
                         
                         <?php foreach($this->general as $general) { ?>
                   
                          <div id="dinamicos" class="row celdaborde1">
                            <!-- <select name="nombre" id="nombre" class="selectsMMedio busqueda" required="required">
                              <option value="">Ref</option>
                              <?php //foreach($this->insumo as $insumo) {  ?>
                                <option value="<?php //echo $insumo['descripcion_insumo']; ?>"><?php //echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                              <?php //} ?>
                            </select>  -->
                           <div style="width: 30px;" id="fondo_2">
                           </div>
                           <div style="width: 180px;" id="fondo_2">
                             <p>&nbsp;&nbsp; <?php echo $general['referencia']; ?></p>
                           </div>
                           <div style="width: 200px;" id="fondo_2">
                             <p><?php echo $general['fecha']; ?></p>
                           </div>
                            <div style="width: 200px;" id="fondo_2">
                             <p><?php echo $general['inventario']; ?></p>
                           </div>
                           <div style="width: 200px;" id="fondo_2">
                             <p><?php echo $general['despacho']; ?></p>
                           </div>
                           <div style="width: 200px;" id="fondo_2">
                             <p><?php echo $general['disponible']; ?></p>
                           </div>
                           <div style="width: 200px;" id="fondo_2">
                             <p>
                              <?php 
                              
                               $fechaActual = $_GET['id']==''? date('Y-m-d') : $_GET['id'];

                               $sqlndespacho = $conexion->llenarCampos('tbl_remision_detalle tabla1 ', " WHERE tabla1.int_ref_io_rd = '".$general['referencia']."' AND tabla1.fecha_rd ='$fechaActual' ", "GROUP BY tabla1.int_ref_io_rd ORDER BY tabla1.int_ref_io_rd ASC",'SUM(tabla1.int_cant_rd) AS despachadohoy ' );

                               $cantdepacho = $sqlndespacho['despachadohoy']; 

                                echo $cantdepacho =='' ? 0 : $cantdepacho; 
                                $stock = $general['disponible']-$cantdepacho; 
                                ?>
                            </p>
                           </div>
                           <?php if($stock<=0):?>
                           <div style="width: 200px;color: red;" id="fondo_2">
                             <p><?php echo $stock; ?></p>
                           </div>
                           <?php else: ?>
                             <div style="width: 200px;" id="fondo_2">
                               <p><?php echo $stock; ?></p>
                             </div>
                           <?php endif;?>
                         </div>
                          
                          <?php } ?>
                      </div> 
                     
                    </div>
                  </div>

                    </div>
                    
                  </td>
                 </tr>
               </tbody> 
             </table>

             <hr> 
         <br>
                
              
                <input name="sumaingresos" id="sumaingresos" class="sumaingresos" type="hidden" value="<?php echo $sumaingresostotal; ?>" >
                <input name="sumasalidas" id="sumasalidas" class="sumasalidas" type="hidden" value="<?php echo $salidakilostotal; ?>" > 
                <input name="controladas" id="controladas" class="controladas" type="hidden" value="<?php echo $_GET['controladas']; ?>" >   
  
             <div class="panel-footer" > 
              VERSIÓN: 01
                <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $general['id_pedido']; ?>">SALIR</a>  -->
                <a class="botonFinalizar" style="text-decoration:none; "href="javascript:Salir('view_index.php?c=cinventario&a=Menu')" >SALIR</a>  
               
             </div>
          </div> 
         

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
</form>
</html>

<script type="text/javascript">
  //bloquea envio del formulario con enter 
   /* document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
        if(e.keyCode == 13) {
          e.preventDefault();
        }
      }))
    });*/
    

 
 

  $( "#referencia" ).on( "change", function() { 
       idreferencia = $( "#referencia" ).val();    
       window.location="view_index.php?c=cinventario&a=Crud&columna=referencia&id="+idreferencia;
       $('#mensaje').hide(); 
       if(idreferencia){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando referencia... !');  
       }
  });
 

  $( "#fecha" ).on( "change", function() { 
       idfecha = $( "#fecha" ).val();    

       window.location="view_index.php?c=cinventario&a=Crud&columna=fecha&id="+idfecha;
       $('#mensaje').hide(); 
       if(idfecha){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando fecha... !');  
       }
  }); 

   function autoRefresh() {
        window.location = window.location.href;
    }
    setInterval('autoRefresh()', 30000);
</script>
 