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
<body>
  <script>
      $(document).ready(function() { $(".busqueda").select2(); });
  </script>
<form action="view_index.php?c=cingresosalida&a=Guardar" method="post" enctype="multipart/form-data" name="form1" id="form1">
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
                   <div class="span12"><h3> ENTRADA SALIDA INSUMOS &nbsp;&nbsp;&nbsp; </h3></div>
                 </div>
                 <div class="panel-heading" align="left" ></div><!--color azul-->
                    <div id="cabezamenu">
                     <ul id="menuhorizontal">
                      <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                      <?php if($_SESSION['superacceso']): ?>
                      <li>
                        <!-- <a class="botonDel" href="?c=csicoqFA&a=Eliminar&columna=<?php echo $_GET['columna']; ?>&master=<?php echo $_GET['id']; ?>">DELETE</a> -->
                       <!-- <a class="botonDel" id="btnDelMaster" onclick='eliminar("<?php echo $_GET['id']; ?>","<?php echo $_GET['columna']; ?>","id_i", "?c=cingresosalida&a=Eliminar","1"  )' type="button" >DELETE</a> -->
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
                         CREAR - INVENTARIO 
                       </td>
                       <td id="subtitulo">VERSIÓN: 01 </td>
                       <td id="subtitulo">Fecha Actual: <?php echo $fechaActual = date('Y-m-d'); ?></td>
                       <td><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="INPRIMIR" title="INPRIMIR"></td>
                     </tr> 
                 </table> 
               </div>
             </div> 
             
             <!-- grids --> 
             <div class="row" >
               <div class="span12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                <?php if($_SESSION['superacceso']): ?>
                <button id='botondeenvio' type="submit" onclick="submitform(); return false;"><img type="image" style="width: 30px; height: 40px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="GUARDAR"title="GUARDAR"></button>&nbsp;&nbsp;&nbsp;&nbsp; 
                <!-- <button id='botondeeditar' type="button" onclick="editarform(); return false;"><img type="image" style="width: 30px; height: 40px;" name="botondeeditar" id="botondeeditar" src="images/editar.PNG" alt="EDITAR"title="EDITAR"></button>  -->
                <?php endif;?> 
               </div>
             </div> 
              
             <table class="table table-striped" id="items" >
         
                    <?php foreach($this->general as $general) { $general; } ?>
                     <tr>
                       <td colspan="12"> 
                           CONSECUTIVO: 
                          <input name="id_i" id="id_i" value="<?php echo $general['id']+1 ; ?>" type="text" placeholder="id" style="width: 80px;" readonly="readonly" > INSUMO: 
                           <select name="nombrebusqueda" id="nombrebusqueda" class="selectsMMedio busqueda">
                             <option value="">Nombre Insumo</option>
                             <?php foreach($this->insumo as $insumo) {  ?>
                               <option value="<?php echo $insumo['id_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                             <?php } ?>
                           </select>
                           OC BUSQUEDA:
                           <input name="ocbusqueda" id="ocbusqueda" value="" type="text" placeholder="oc busqueda" style="width: 80px;" >
                           AÑO:
                           <input name="anyobusqueda" id="anyobusqueda" value="" type="date" placeholder="año busqueda" style="width: 80px;" >
                           
                       </td> 
                   </tr>
                    <tr>
                      <td colspan="6" >
                        <div class="alert alert-danger divScroll" role="alert" style="text-align: left;">
                          <span>Insumo menos de 20 Unidades en Stock: <br>  </span> 
                          <?php foreach($this->refcero as $refcero ) {  
                             if($refcero['insumoveinte']!=''){
                               $sqlnsinstock = $conexion->llenarCampos('insumo ', " WHERE id_insumo = '".$refcero['insumoveinte']."' ", " ORDER BY descripcion_insumo ASC",' descripcion_insumo ' );

                               echo ' * ' . $sqlnsinstock['descripcion_insumo'] . ' / ID-'.$refcero['insumoveinte'] ; echo ',<br>';
                               
                             }
                                 
                                
                          } ?>
                        </div>
                        </td>
                        <td colspan="6">
                        <div id="verAlert" style="display: none;" class="alert alert-warning  divScroll" role="alert" style="text-align: left;"> </div>
                      </td>
                    </tr>
                   
                 </tr>
               
               <tbody > 
                 <tr>
                  <td nowrap='nowrap' colspan="12">

                   <div class="divScrollMedio" id="itemspedido" role="alert" style="text-align: left;"> 
                      <div class="row celdaborde1">  
                         <div style="width: 40px;" ><strong></strong></div>
                         <div style="width: 300px;" ><strong>NOMBRE INSUMO</strong></div> 
                         <!-- <div style="width: 100px;" ><strong>INGRESO </strong></div>
                         <div style="width: 120px;" ><strong>FECHA RECEP.</strong></div>   -->
                         <div style="width: 100px;" ><strong>O.C </strong></div>
                         <div style="width: 120px;" ><strong>FECHA SALIDA  </strong></div>
                         <div style="width: 180px;" ><strong>SALIDA INSUMOS V.</strong></div>
                         <div style="width: 120px;" ><strong>INVENTARIO FINAL</strong></div>
                         <div style="width: 110px;" ><strong>TOTAL CONSUMO</strong></div> 
                         <div style="width: 110px;" ><strong>RESPONSABLE </strong></div>  
                      </div> 
                   <div id="dinamicos" class="row celdaborde1">
                     <div class="col-lg-12" id="fondo_2">
                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled" ></label>  
                      <select name="nombre" id="nombre" class="selectsMMedio busqueda" onChange="stockminimo();" required="required">
                        <option value="">Nombre Insumo</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['id_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <!-- <input name="ingresokilos" id="ingresokilos" class="ingresokilos" step="1" value="0" type="number" placeholder=" INGRESO" style="width: 80px;" onChange="totalConsumo();" >&nbsp;&nbsp;
                      <input name="fecharecepcion" id="fecharecepcion" type="date" step="1" min="2020-01-01" placeholder="fecharecepcion" value=""  class="campostextMedio" >&nbsp;&nbsp;  -->
                      <input name="oc" id="oc" class="oc" value="" type="text" placeholder="o.c" style="width: 100px;" required="required">&nbsp;&nbsp;
                      <input name="fechasalida" id="fechasalida" type="date" step="1" min="2020-01-01" placeholder="fechasalida" value=""  size="10">&nbsp;&nbsp; 
                      <input name="salidakilos" id="salidakilos" class="salidakilos" step="1" value="0" type="number" placeholder="Salida" style="width: 170px;" onChange="totalConsumo();">&nbsp;&nbsp; 
                      <input name="inventariofinal" id="inventariofinal" class="inventariofinal" step="1" value="0" type="number" placeholder=" inventario final" style="width: 110px;" onChange="totalConsumo();" >&nbsp;&nbsp;
                      <input name="totalconsumo" id="totalconsumo" class="totalconsumo" value="" type="text" placeholder="total consumo" style="width: 100px;" onClick="totalConsumo();">&nbsp;&nbsp; 
                      <input name="responsable" id="responsable" type="text" placeholder="responsable" value="<?php echo $general['responsable']=='' ? $_SESSION['Usuario'] : $general['responsable']; ?>" class="campostextMedio" style="width: 80px;">
                      <input name="modificado" id="modificado" type="hidden" placeholder="modificado" value="<?php echo $_SESSION['Usuario']; ?>" class="campostextMedio" > 
                     </div> 
                      <!-- <fieldset id="field"></fieldset> --> <!-- este muestra dinamicos -->
                    </div>
                  </div>

                    </div>
                    <!-- Adjuntar pdf: 
                    <input class="botonGMini" type="file" name="adjunto" id="adjunto" />
                    <?php if( $general['userfile'] != '' ): ?>
                    <input name="userfile" type="hidden" id="userfile" value="<?php echo $general['userfile'];?>"/> 
                    <a href="javascript:verFoto('pdfsicoq/<?php echo $general['userfile'];?>','800','600')"> Ver Archivo</a>
                    <?php endif; ?> -->
                  </td>
                 </tr>
               </tbody>



             </table>
            
            <?php  if($this->items) :  ?>
             <hr> 
              <em id="AlertUpdate" ></em>&nbsp;</em>  
                <div class="row align-items-start"> 
                  <div style="width: 360px;" ><strong>NOMBRE INSUMO</strong></div> 
                  <div style="width: 90px;" ><strong>INGRESO </strong></div>
                  <div style="width: 100px;" ><strong>FECHA RECEP.</strong></div>  
                  <!-- <div style="width: 50px;" ><strong>O.C </strong></div> -->
                  <div style="width: 100px;" ><strong>FECHA SALIDA  </strong></div>
                  <div style="width: 160px;" ><strong>SALIDA INSUMOS V.</strong></div>
                  <div style="width: 120px;" ><strong>INVENTARIO FINAL</strong></div>
                  <div style="width: 110px;" ><strong>TOTAL CONSUMO</strong></div> 
                  <div style="width: 110px;" ><strong>RESPONSABLE </strong></div>  
                  <?php if($_SESSION['superacceso']): ?>
                  <div style="width: 120px;" ><strong>ELIMINAR</strong></div>
                <?php endif; ?>
                  <!--  <div class="col-lg-1" ><strong>VER</strong></div>     --> 
                </div> 
              <div class="divScrollGrande" id="itemspedido" role="alert" style="text-align: left;"> 
                     
                 <?php $ids = 0; foreach($this->items as $items) {  $ids++; ?>
                <div id="dinamicos" class="row celdaborde1">
                  <div style="width: 20px;" id="fondo_2"> </div>
                  <div style="width: 360px;" id="fondo_2">
                   <p  >
                    <?php 
                    
                    $sqlndespacho = $conexion->llenarCampos('insumo ', " WHERE id_insumo = '".$items['nombre']."' ", " ORDER BY descripcion_insumo ASC",' descripcion_insumo ' );

                   echo $cantdepacho = $sqlndespacho['descripcion_insumo']; 

                   ?></p>
                 </div>
                 <div style="width: 80px;" id="fondo_1">
                   <p>
                    <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text"  id="ingresokilos_<?php echo $ids;?>" name="ingresokilos_<?php echo $ids;?>" value="<?php echo $items['ingresokilos']; ?>" class="campostext" style="display: none;width: 80px;" ><?php $sumaingresostotal += $items['ingresokilos']; ?> 
                    <strong class="ingresokilos_<?php echo $ids;?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['ingresokilos']; ?></strong>
                 </p> 
                 </div> 
                 <div style="width: 100px;" id="fondo_2">
                   <p>
                    <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text"  id="fecharecepcion_<?php echo $ids;?>" name="fecharecepcion_<?php echo $ids;?>" value="<?php echo $items['fecharecepcion']; ?>" class="campostext" style="display: none;width: 80px;" >  
                    <strong class="fecharecepcion_<?php echo $ids;?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['fecharecepcion']; ?></strong> 
                  </p>
                 </div> 
                 <!-- <div style="width: 80px;" id="fondo_2">
                   <p><input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="oc_<?php echo $ids;?>" name="oc_<?php echo $ids;?>" value="<?php echo $items['oc']; ?>" class="campostext" style="display: none;width: 100px;" >
                    <strong class="oc_<?php echo $ids;?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['oc']; ?></strong> </p> 
                 </div> --> 
                 <div style="width: 120px;" id="fondo_2">
                   <p>
                    <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text"  id="fechasalida_<?php echo $ids;?>" name="fechasalida_<?php echo $ids;?>" value="<?php echo $items['fechasalida']; ?>" class="campostext" style="display: none;width: 80px;" > 
                    <strong class="fechasalida_<?php echo $ids;?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['fechasalida']; ?></strong>
                     </p>
                 </div>
                 <div style="width: 140px;" id="fondo_2">
                   <p>
                    <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="salidakilos_<?php echo $ids;?>" name="salidakilos_<?php echo $ids;?>" value="<?php echo $items['salidakilos']; ?>" class="campostext" style="display: none;width: 170px;" >
                    <strong style="color: green;" class="salidakilos_<?php echo $ids;?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['salidakilos']; ?></strong> 
                    <?php $salidakilostotal += $items["salidakilos"];?></p> 
                 </div>  
                 <div style="width: 120px;" id="fondo_2">
                   <p>
                    <input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="inventariofinal_<?php echo $ids;?>" name="inventariofinal_<?php echo $ids;?>" value="<?php echo $items['inventariofinal']; ?>" class="campostext" style="display: none;width: 80px;" >
                    <strong style="color: blue;" class="inventariofinal_<?php echo $ids;?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['inventariofinal']; ?></strong>
                    <?php $inventariof += $items["inventariofinal"];?></p>
                 </div> 
                 <div style="width: 110px;" id="fondo_1">
                   <p><input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="totalconsumo_<?php echo $ids;?>" name="totalconsumo_<?php echo $ids;?>" value="<?php echo $items['totalconsumo']; ?>" class="campostext" style="display: none;width: 80px;" >
                    <strong style="color: red;" class="totalconsumo_<?php echo $ids;?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"> <?php echo $items['totalconsumo']; ?></strong> </p> 
                 </div>
                 <div style="width: 120px;" id="fondo_2">
                   <p><input onChange="UpdatesItems(<?php echo $items['id_i']; ?>,this)" type="text" id="responsable_<?php echo $ids;?>" name="responsable_<?php echo $ids;?>" value="<?php echo $items['responsable']; ?>" class="campostext" style="display: none;width: 80px;" >
                    <strong class="responsable_<?php echo $ids;?>" onClick="UpdatesItems(<?php echo $items['id_i']; ?>,this)"><?php echo $items['responsable']; ?></strong> </p> 
                 </div>
 
                  <?php if($items['id_i']  && $_SESSION['acceso']){  ?> 
                    <div class="col-lg-1" id="fondo_2">
                      <a class="botonDelMini" id="btnDelItems" style="width: 20;height: 10" onclick='eliminar("<?php echo $items['id_i']; ?>","<?php echo $_GET['columna']; ?>","id_i","?c=cingresosalida&a=Eliminar","0" )' type="button" >DELETE</a>
                    </div>
                  <?php  } ?> 
               </div>
                <?php  } ?>
              
            </div>
              <div id="dinamicos" class="row celdaborde1">
                <div style="width: 200px;" id="fondo_1"><strong>TOTAL INGRESO RECIBIDA:</strong> </div>
                <div style="width: 120px;" id="fondo_1">
                   <p><?php echo $sumaingresostotal; ?></p> 
                 </div>
                 <div style="width: 200px;" id="fondo_1"><strong>SALIDA INSUMOS VENDIDOS:</strong> </div>
                <div style="width: 120px;" id="fondo_1">
                   <p><?php echo $salidakilostotal; ?></p> 
                 </div>
              </div>
                <input name="sumaingresos" id="sumaingresos" class="sumaingresos" type="hidden" value="<?php echo $sumaingresostotal; ?>" >
                <input name="sumasalidas" id="sumasalidas" class="sumasalidas" type="hidden" value="<?php echo $salidakilostotal; ?>" >    
            <?php  endif; ?>
             <br><br><br>
             <div class="panel-footer" > 
              VERSIÓN: 01
                <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $items['id_pedido']; ?>">SALIR</a>  -->
                <a class="botonFinalizar" style="text-decoration:none; "href="javascript:Salir('view_index.php?c=csicoq&a=Menu')" >SALIR</a>  
               
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
    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
        if(e.keyCode == 13) {
          e.preventDefault();
        }
      }))
    });
    

 
 

  $( "#ocbusqueda" ).on( "change", function() { 
       idocbusqueda = $( "#ocbusqueda" ).val();  
       window.location="view_index.php?c=cingresosalida&a=Crud&columna=oc&id="+idocbusqueda;
       $('#mensaje').hide(); 
       if(idocbusqueda){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando ocbusqueda... !');  
       }
  }); 
 
   $( "#nombrebusqueda" ).on( "change", function() { 
       idnombrebusqueda = $( "#nombrebusqueda" ).val();    
       window.location="view_index.php?c=cingresosalida&a=Crud&columna=nombre&id="+idnombrebusqueda;
       $('#mensaje').hide(); 
       if(idnombrebusqueda){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando nombrebusqueda... !');  
       }
  });

   $( "#anyobusqueda" ).on( "change", function() { 
       idanyobusqueda = $( "#anyobusqueda" ).val();    
       window.location="view_index.php?c=cingresosalida&a=Crud&columna=fecharecepcion&id="+idanyobusqueda;
       $('#mensaje').hide(); 
       if(idanyobusqueda){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando anyobusqueda... !');  
       }
  });

  function stockminimo(){
     
       var insumo = !$("#nombre").val() ? 0 :$("#nombre").val();
       
       var fecha = new Date();
       var anyo = fecha.getFullYear();
 
       var result = consultaGeneral('tbl_ingresosalida_items','','nombre','fecharecepcion',insumo,'',anyo,'');
  
  }
   

   function totalConsumo(){
      var ingresokilos = !$(".ingresokilos").val() ? 0 :$(".ingresokilos").val();
      var salidakilos = !$(".salidakilos").val() ? 0 :$(".salidakilos").val();
      var inventariofinal = !$(".inventariofinal").val() ? 0 :$(".inventariofinal").val();
      if(salidakilos!=0 ){
      var totalkConsumo =(parseFloat(salidakilos)+parseFloat(inventariofinal) );// parseFloat(ingresokilos) - 
      
      //totalconsumo es si ingresa salidas
        $(".totalconsumo").val(totalkConsumo.toFixed(2)); 
      }

   }
 


/*      function editarform(){
           ids=''+"<?php echo $_GET['id']; ?>"; //coloque la columna del id a actualizar
           colum = "id";
           tabla='tbl_sicoq';  
           url='view_index.php?c=csicoq&a=Guardar&alert=1&'; 
           //nuevoArchivo=document.getElementById("adjunto");
           
           updates(ids,colum,tabla,url); 
      
      } */

      function UpdatesItems(vid,valores){
        separador = "_";
        var ingreso = $(valores).attr("class");
        ingreso2 = ingreso.split(separador); 
    
         
        if(ingreso2[1]!=''){
           $("#"+ingreso2[0]+"_"+ingreso2[1]).show();
           $("."+ingreso2[0]+"_"+ingreso2[1]).hide();
        }

        
        var name = $(valores).attr("name"); 
        var textoseparado = name.split(separador);
        var valores = $(valores).attr("name", textoseparado[0]);

        
        ids='id_i';//coloque la columna del id a actualizar
        valorid = ''+vid; 
        tabla='tbl_ingresosalida_items';
        url='view_index.php?c=cgeneral&a=Actualizar'; //la envio en campo proceso
        
         //resta a totalInsumo 
        totalConsumoItems('id_i',$("#ingresokilos_"+textoseparado[1]).val(),$("#salidakilos_"+textoseparado[1]).val(),$("#inventariofinal_"+textoseparado[1]).val(),valorid,$("#totalconsumo").attr("name", "totalconsumo"))
      
        actualizapaso(ids,valorid,valores,tabla,url);
         location.reload(); 


      }
 


    function totalConsumoItems(id,ingreso,salida,inventario,valorid,consumototal){
      var ingresokilos = !ingreso? 0 :ingreso;
      var salidakilos = !salida ? 0 :salida;
      var inventariofinal = !inventario ? 0 :inventario;
      
      if(salidakilos!=0 ){
         var totalkConsumo = (parseFloat(salidakilos)+parseFloat(inventariofinal) );//parseFloat(ingresokilos) - 
       }
       $(consumototal).val(totalkConsumo)
       var valores = consumototal;
        

      ids=id;//coloque la columna del id a actualizar
      valorid = ''+valorid; 
      tabla='tbl_ingresosalida_items';
      url='view_index.php?c=cgeneral&a=Actualizar'; //la envio en campo proceso
      
      actualizapaso(ids,valorid,valores,tabla,url);
     
   }



   function autoRefresh() {

        window.location = window.location.href;
    }
    setInterval('autoRefresh()', 90000);
</script>
 