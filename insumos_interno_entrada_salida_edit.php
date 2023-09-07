<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
 
  <?php
  if (!isset($_SESSION)) {
  session_start();
}

$currentPage = $_SERVER["PHP_SELF"];
 
$conexion = new ApptivaDB();

$colname_usuario = "1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
 
$row_usuario = $conexion->buscar('usuario','usuario',$colname_usuario); 

$colname_remision_id= "-1";
if (isset($_GET["id_remision"])) {
  $colname_remision_id = (get_magic_quotes_gpc()) ? $_GET["id_remision"] : addslashes($_GET["id_remision"]);
}
 
$row_existe = $conexion->buscar('tbl_remision_interna','id_remision',$colname_remision_id);

$row_ver_nuevo = $conexion->buscarId('tbl_remision_interna','id_remision');

$proveedores = $conexion->llenaSelect('proveedor','','ORDER BY proveedor_p DESC');

$insumos = $conexion->llenaSelect('insumo','','ORDER BY descripcion_insumo DESC');
 
 
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
   <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
   <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
   <script type="text/javascript" src="AjaxControllers/js/delete.js"></script> 
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body onKeyDown="javascript:Verificar()">
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table id="tabla1">


        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                 <div class="panel-heading"><h2>INSUMOS INTERNO ENTRADA-SALIDA</h2></div>
                 <div id="cabezamenu">
                  <ul id="menuhorizontal">
                   <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                   <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                   <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                   <li><a href="insumos.php/">VER INSUMOS</a></li>
                   <li><a href="insumos_interno_listado.php">LISTADO ENTRADAS</a></li>
                 </ul>
               </div> 
               <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12">
                     <table id="tabla2">
                       <tr>
                         <td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td> 
                       </tr>
                       <tr>
                        <td id="subtitulo">
                         REMISION <?php echo strtoupper($row_existe['entrada']); ?>
                       </td>
                     </tr>
                     <tr>
                      <td align="center" >
                       <h5 id="numero2" class="id_remision" ><h2>N° <?php $num = $_GET["id_remision"]=='' ? $row_ver_nuevo['id']+1 : $_GET["id_remision"]; echo $num; ?></h2> </h5>
                    <hr>
                       ALBERTO CADAVID R & CIA S.A. -  Nit: 890915756-6 <br>
                       Carrera 45 N°. 14 - 15 Tel: 604 311-21-44 -  Medellin-Colombia
                       <p></p>
                     </td>
                   </tr>
                 </table> 
               </div>
             </div>
           
 
             <form action="guardar.php" method="post" id="form1" name="form1" >
               <table id="tabla1"> 
                  <tr>
                   <td ><input id="id_remision" name="id_remision" type="hidden" value="<?php echo $num; ?>" >
                        <strong >CLIENTE:</strong>
                        <input type="text" required="required" id="cliente" name="cliente" value="<?php echo $row_existe['cliente']; ?>" class="form-control negro_inteso">
                            <!-- <select name="cliente" id="cliente" class="selectsMedio" >
                            <option value=""<?php if (!(strcmp("", $row_existe['cliente']))) {echo "selected=\"selected\"";} ?>>Seleccione Cliente</option>
                            <?php  foreach($proveedores as $row_proveedores ) { ?>
                            <option value="<?php echo $row_proveedores['id_p']; ?>"<?php if (!(strcmp($row_proveedores['id_p'], $row_existe['cliente']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_proveedores['proveedor_p']); ?> </option>
                            <?php } ?>
                            </select>  --> 
                      </td >
                      <td>  
                        <strong >ENTRADA - SALIDA&nbsp;&nbsp;</strong>
                         <select id="entrada" name="entrada" required="required" class="form-control" >
                             <option value=""<?php if (!(strcmp("", $row_existe['entrada']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                             <option value="Entrada"<?php if (!(strcmp("Entrada", $row_existe['entrada']))) {echo "selected=\"selected\"";} ?>>Entrada</option>
                             <option value="Salida"<?php if (!(strcmp("Salida", $row_existe['entrada']))) {echo "selected=\"selected\"";} ?>>Salida</option>
                        </select>
                   </td>
                </tr> 
                 <tr>
                   <td >
                      <strong >NIT / C.C:</strong >&nbsp;&nbsp;<input type="text" required="required" id="documento" name="documento" value="<?php echo $row_existe['documento']; ?>" class="form-control negro_inteso" > 
                   </td >
                   <td >
                      <strong >PAIS/CIUDAD:</strong >&nbsp;&nbsp;<input type="text" id="pais" name="pais" value="<?php echo $row_existe['pais']; ?>" class="form-control negro_inteso" >
                    </td >
                  </tr>
                  
                  <tr>
                    <td colspan="2" >
                      <strong >CONTACTO: </strong>
                      <input type="text" required="required" placeholder="Contacto" id="contacto" name="contacto" value="<?php echo $row_existe['contacto']; ?>" class='form-control negro_inteso'   >
                    </td>
                  </tr> 

                  <tr>
                     <td> 
                      <strong >TELEFONO: </strong>
                      <input type="text" placeholder="Telefono" id="telefono" name=" telefono" value="<?php echo $row_existe['telefono']; ?>"  class='form-control negro_inteso'  >
                     </td>
                     <td> 
                      <strong >N°CELULAR: </strong>
                      <input type="text" required="required" placeholder="Celular" id="celular" name="celular" value="<?php echo $row_existe['celular']; ?>"  class='form-control negro_inteso'  >
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                   <tr>
                    <td> 
                      <strong >FECHAS ENTRADA: </strong>
                      <input type="date" required="required"  id="fecha" name="fecha" value="<?php echo $row_existe['fecha']; ?>" class='form-control' style="width:200">
                     </td>
                     <td> 
                       <strong >FECHAS SALIDA: </strong>
                       <input type="date" required="required"  id="fecha_salida" name="fecha_salida" value="<?php echo $row_existe['fecha_salida']; ?>" class='form-control' style="width:200">
                      </td>
                    </tr>
 

               <tr>
                  <td colspan="2">
                     <!-- grid --> 
                     <hr>
                     <table id="example" class="display" style="width:100%" border="1">
                       <thead>
                         <tr> 
                           <th style="text-align: center;" >DESCRIPCION</th>
                           <th>MEDIDA/AC</th>
                           <th>CANTIDAD</th>
                           <th>PESO</th>
                           <th>SUBTOTAL</th>
                           <th>DELETE</th>  
                         </tr>
                       </thead>
                       <tbody id="DataResult"> 
                         
                       </tbody> 
                     </table> 
                   

                </td>
              </tr>
              

              <tr>
                                <td colspan="2">
                                <hr>
                             <table border="1" style="width: 100%;" >
                                <tr align="center" >
                                  <td style="width:310px"><strong>DESCRIPCION</strong></td>
                                  <td style="width:90px"><strong>MEDIDA</strong></td>
                                  <td style="width:60px"><strong>CANTIDAD</strong></td>
                                  <td style="width:90px"><strong>PESO</strong></td>
                                  <td style="width:85px"><strong>SUBTOTAL</strong></td>

                                </tr>
                                <?php for ($i=1; $i <= 3; $i++) { ?>
                                
                                <tr>
                                    <td colspan="12" id="dato1">
                                      <input type="hidden" name="remision_id" id="remision_id" value="<?php echo $num; ?>" style="width:70px">&nbsp;
                                       <input type="text" required="required" placeholder="Descripcion" id="insumo[]" name="insumo[]" value="<?php echo $row_existe['insumo']; ?>"  style="width:300px"> &nbsp;
                                      <select id="medida[]" name="medida[]" required="required" style="width:70px">
                                           <option value="">Seleccione</option>
                                           <option value="unidad">Unidad</option>
                                           <option value="kilo">Kilo</option>
                                           <option value="rollo">Rollo</option>
                                      </select> &nbsp;
                                      <input onChange="multiplicaTotal(<?php echo $i;?>,this)" type="text" required="required" placeholder="Cantidad" id="cantidad<?php echo $i;?>" name="cantidad[]" value="" style="width:70px" >&nbsp;
                                      <input onChange="multiplicaTotal(<?php echo $i;?>,this)" type="text" required="required" placeholder="Peso" id="peso<?php echo $i;?>" name="peso[]" value="" style="width:80px">&nbsp;
                                      <input type="text" required="required" placeholder="Subtotal" id="precio<?php echo $i;?>" name="precio[]" value="" style="width:80px"  > 
                                    </td>     
                                </tr>
                              <?php } ?>
                              <tr>
                                <td colspan="4">
                                  <em style="display: none;  align-items: center; justify-content: center;color: red; " id="busqueda" ></em>  
                                  <em style="display: none;  align-items: center; justify-content: center;color: red; " id="AlertItem" ></em> 
                                  
                                </td>
                                <td colspan="4">Total: <strong id="totales" ></strong> </td>
                                <td id="dato3"> 
                                   <input type="hidden" name="formItems" value="formItems"> 
                                </td> 
                              </tr> 
                                
                              </table>

                              </td>
                            </tr>


                 <tr>
                    <td colspan="2"> 
                     <strong >OBSERVACIONES:</strong>
                     <textarea class="form-control" id="observacion" name="observacion" cols="50" rows="3"><?php echo $row_existe['observacion']; ?></textarea>
                    </td>
                  </tr>

                  <tr>
                    <td> 
                      <strong >ELABORADO POR: </strong>
                      <input type="text" placeholder="Elabora" id="elabora" name="elabora" value="<?php echo $row_existe['elabora']; ?>" class='form-control'   >
                    </td>
                     <td> 
                      <strong >RECIBIDO POR: </strong>
                      <input type="text"  placeholder="Recibe" id="recibe" name="recibe" value="<?php echo $row_existe['recibe']; ?>" class='form-control'   >
                    </td>
                  </tr>

                 <tr>
                   <td colspan="2">
                  <div class="row">
                        <div class="span12" style="align-items: center;">
                         <em style="display: none;  align-items: center; justify-content: center;color: red; " id="alertG" ></em> 
                       </div>  
                     </div>
                       <div class="panel-footer" id="continuar" align="center">
                          
                         <button id="btnEnviarG" name="btnEnviarG" type="button" class="botonGeneral" autofocus="" >GUARDAR Y CONTINUAR</button>
                       </div>
                         </td>
                      </tr> 
                     </td>
                   </tr>
                   <input type="hidden" name="MM_insert" value="form1"> 
                   </form> 
   

        </table> 

         

          <?php //endif; ?>
 
          
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
<!-- <script src="bootstrap-4/js/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->


</body>
</html>
 
<script type="text/javascript">
 $(document).ready(function(){
  consultasItems($("#id_remision").val());//despliega los items
}); 

 $( "#btnEnviarG" ).on( "click", function() {

   if($("#cliente").val()==''){
     swal("Error", "Debe agregar un valor al campo cliente! :)", "error"); 
     return false;
   }else 
   if($("#entrada").val()==''){
     swal("Error", "Debe agregar un valor al campo entrada! :)", "error"); 
     return false;
   }else 
   if($("#documento").val()==''){
     swal("Error", "Debe agregar un valor al campo documento! :)", "error"); 
     return false;
   }else 
   if($("#contacto").val()==''){
     swal("Error", "Debe agregar un valor al campo contacto! :)", "error"); 
     return false;
   }else{
      guardarConAlert($("#id_remision").val()); 
   } 
  
   });

  
   $( "#btnEnviarItems" ).on( "click", function() {

   if($("#insumo").val()==''){
     swal("Error", "Debe agregar un valor al campo insumo! :)", "error"); 
     return false;
   } 
   else if($("#peso").val()=='' && $("#precio").val()==''){
     swal("Error", "Debe agregar un valor al campo peso o precio! :)", "error"); 
     return false;
   } 
   else if($("#cantidad").val()==''){
     swal("Error", "Debe agregar un valor al campo cantidad! :)", "error"); 
     return false;
   }else{ 
     guardarConAlertItems();
   }

 });

   $( "#insumo" ).on( "change", function() {
       consultaInsumos();
  });
 

  var suma = 0;
  function multiplicaTotal(vid,valores){
  
       cant =  $("#cantidad"+vid).val()
       peso =  $("#peso"+vid).val()
 
       if(cant!='' ){
        
         $("#precio"+vid).val(cant)
       }
       if( peso!=''){
           
         $("#precio"+vid).val(peso)
       } 
       
       

           $("#precio"+vid).each(function() {
               suma +=  parseFloat($("#precio"+vid).val());
            });
 
       $("#totales").text(suma)

 }

</script>
<?php
mysql_free_result($usuario);
mysql_free_result($ver_nuevo);
mysql_free_result($proveedores);
mysql_free_result($insumos);


?>
