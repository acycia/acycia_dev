 
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
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>
  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">  
</head>
<body>
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
                   <div class="span12"><h3> PROCESO DE COMPRAS  &nbsp;&nbsp;&nbsp; </h3></div>
                 </div>
                 <div class="panel-heading" align="left" ></div><!--color azul-->
                    <div id="cabezamenu">
                     <ul id="menuhorizontal">
                      <li id="nombreusuario" ><?php echo $_SESSION['Usuario']; ?></li>
                      <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                      <li><a href="menu.php">MENU PRINCIPAL</a></li> 
                      <li><a href="insumos.php">VER INSUMOS</a></li>
                      <li><a href="orden_compra.php">ORDENE COMPRA</a></li> 
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
                         MENU - PROCESO DE COMPRA EXPORTACIONES
                       </td>
                     </tr> 
                 </table> 
               </div>
             </div>
             <br> 
          <br>
          <!-- grid --> 

          <div class="container-fluid">  
          
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">PROCESO DE COMPRA EXPORTACIONES</th>
                  <!-- <th scope="col">FACTURA</th>  -->
                </tr>
              </thead>
              <tbody ><!-- 
                <tr>
                  <td><em> CREAR PROFORMA</em>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank"  href="view_index.php?c=compras&a=Proforma">CREAR PROFORMA >>></a> </td>
                  <td> </td> 
                </tr>
                <tr>
                  <td><em> ENTRADA FACTURA COMERCIAL</em>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank"  href="view_index.php?c=comprasFA&a=Factura">CREAR FACTURA COMERCIAL >>></a></td>
                  <td> </td> 
                </tr>
                <tr>
                  <td><em> DETALLE DE EMBARQUE</em>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank"  href="view_index.php?c=comprasDE&a=Detalle">DETALLE DE EMBARQUE >>></a> </td>
                  <td> </td>
                </tr>
                <tr>
                  <td><em> ENTRADA DE MERCANCIA</em>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank"  href="view_index.php?c=comprasEM&a=Mercancia">ENTRADA DE MERCANCIA >>></a></td>
                  <td> </td>
                </tr>
                <tr>
                  <td><em> LIQUIDACIÓN</em>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank"  href="view_index.php?c=comprasLQ&a=Liquidacion">LIQUIDACIÓN >>></a></td>
                  <td> </td>
                </tr> -->
                <tr>
                  <td>
                   <?php if($_SESSION['acceso']): ?> <em> EXPORTACIONES</em>&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank"  href="view_index.php?c=comprasEX&a=Exterior">EXPORTACIONES >>></a> <?php endif;?>
                   </td>
                  <td> </td>
                </tr>
                <tr>
                  <td> </td>
                  <td> </td> 
                </tr> 
              </tbody>
            </table>
            <div class="panel-footer" > 
              <!-- <button id="btnEnviarG" name="btnEnviarG" type="button" class="botonFinalizar" autofocus="" >FINALIZAR</button>  <button id="btnEnviarG" name="btnEnviarG" type="button" class="botonGeneral" autofocus="" >GUARDAR Y CONTINUAR</button>--> 
              <!-- <a class="botonGeneral" href="?c=ocomercial&a=Crud&id=<?php echo $dato['id_pedido']; ?>">Editar</a> --> 
              <!-- <a class="botonFinalizar" href="?c=ocomercial&a=Eliminar&id=<?php echo $dato['id_pedido']; ?>">Eliminar</a> -->
                   <a class="botonFinalizar" style="text-decoration:none; "href="?id=<?php echo $dato['id_pedido']; ?>">SALIR</a>
            </div> 
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
</html>
<script type="text/javascript">
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

