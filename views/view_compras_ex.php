<?php
   //require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   //require (ROOT_BBDD); 
?> 
<?php 
//$conexion = new ApptivaDB();

//$proveedores = $conexion->llenaSelect('proveedor','','ORDER BY proveedor_p ASC');


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
<form action="view_index.php?c=comprasEX&a=Guardar" method="post" enctype="multipart/form-data" name="form1" >
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
                         ENTRADA - EXPORTACIONES
                       </td>
                     </tr> 
                 </table> 
               </div>
             </div> 
             <br>
             <!-- grids --> 
             <div class="row" >
               <div class="span12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                <button id='botondeenvio' type="submit" ><img type="image" style="width: 30px; height: 40px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="GUARDAR"title="GUARDAR"> </button> 
               </div>
             </div><br> 
              
             <table class="table table-striped" id="items" >
               <thead>
                 <tr>
                  <tr>
                    <td>
                      <?php foreach($this->general as $general) { $general; } ?>
                      <input name="proceso" id="proceso" value="EXPO" type="hidden" > 
                      Factura
                      <input name="factura" id="factura" value="<?php echo $_GET['columna'] =='factura' ? $_GET['id'] : $general['factura']; ?>" type="text" placeholder="Factura" size="30" required="required" > 
                      Fecha Factura
                      <input name="fecha_factura" id="fecha_factura" type="date" step="1" min="2020-01-01" placeholder="Fecha factura" value="<?php echo $general['fecha_factura']=='' ? date('Y-m-d') : $general['fecha']; ?>" required="required" class="campostextMedio" >
                      Valor Factura
                      <input name="valor_fact" id="valor_fact" value="<?php echo $general['valor_fact']; ?>" type="text" placeholder="Valor factura" size="30" >
                      Cliente
                      <input name="proveedor" id="proveedor" value="<?php echo $general['proveedor']; ?>" type="text" placeholder="Cliente" size="60" required="required" >
                      BL/GUIA
                      <input name="bl" id="bl" value="<?php echo $general['bl']; ?>" type="text" placeholder="BL/Guia" size="30" >
                      Fecha
                      <input name="fecha" id="fecha" type="date" step="1" min="2020-01-01" placeholder="Fecha" value="<?php echo $general['fecha']=='' ? date('Y-m-d') : $general['fecha']; ?>" class="campostextMedio" ><br><br>
                      <input name="tipopedido" id="tipopedido" value="Exportacion" type="hidden" > 
                      # DEX
                      <input name="dex" id="dex" value="<?php echo $general['dex']; ?>" type="text" placeholder="# DEX" size="30" >
                      Fecha Dex
                      <input name="fecha_dex" id="fecha_dex" type="date" step="1" min="2020-01-01" placeholder="Fecha_dex" value="<?php echo $general['fecha_dex']=='' ? date('Y-m-d') : $general['fecha_dex']; ?>" class="campostextMedio" >
                      Pago
                      <input name="pago" id="pago" value="<?php echo $general['pago']; ?>" type="text" placeholder="Pago" size="30" >
                      Fecha De Pago
                      <input name="fecha_pago" id="fecha_pago" type="date" step="1" min="2020-01-01" placeholder="Fecha pago" value="<?php echo $general['fecha_pago']=='' ? date('Y-m-d') : $general['fecha_pago']; ?>" class="campostextMedio" >
                      Flete + Seguro
                      <input name="fleteseguro" id="fleteseguro" value="<?php echo $general['fleteseguro']; ?>" type="text" placeholder="Flete + Seguro" size="30" >
                      <br><br>
                      <textarea class="observaciones" name="comentarios" cols="50" placeholder="Comentarios" rows="3"><?php echo $general['comentarios']; ?></textarea>
                      <input name="usuario" id="usuario" type="hidden" placeholder="Usuario" value="<?php echo $_SESSION['Usuario']; ?>" class="campostextMedio" >
                    </td>
                  <tr>
                    <td><?php if(isset($_GET['id']) && $_GET['id'] !='' && isset($_GET['mostrar']) && $_GET['mostrar'] == 1): ?>
                         <em style="align-items: center; justify-content: center;color: red; " id="mensaje" >Guardado con Exito!</em> 
                     <?php endif; ?>
                   </td>
                  </tr> 
               </thead>
               <tbody >
                <tr>
                 <th><!--   &nbsp;  &nbsp;RESPONSABLE  &nbsp;  &nbsp;&nbsp;DIRECCION  &nbsp;  &nbsp;&nbsp;INDICATIVO  &nbsp;  &nbsp;&nbsp;TELEFONO  &nbsp;  &nbsp;&nbsp;EXTENSION  &nbsp;  &nbsp;&nbsp;CIUDAD -->
                 </th>
                </tr>
                 <tr>
                  <td nowrap>
                    Adjuntar pdf: <input class="botonGMini" type="file" name="adjunto" id="adjunto" size="100"/>
                    <?php if( $general['adjunto'] != ''): ?>
                    <a href="javascript:verFoto('pdfprocesocompras/<?php echo $general['adjunto'];?>','800','600')"> Ver Archivo</a>
                    <?php endif; ?>
                  </td>
                 </tr>   
                <tr> 
                   <td> 
                    <input name="estado" id="estado" readonly="readonly" type="hidden" value="INVENTARIO" >
                  </td>
                </tr>
               </tbody>
             </table>
  
             <div class="panel-footer" > 
                <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $dato['id_pedido']; ?>">SALIR</a>  -->
                <a class="botonFinalizar" style="text-decoration:none; "href="javascript:Salir('view_index.php?c=compras&a=Menu')" >SALIR</a>  
               
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

   
  $( "#factura" ).on( "change", function() {  
        idfactura = $( "#factura" ).val();    
        window.location="view_index.php?c=comprasEX&a=Crud&columna=factura&id="+idfactura;
        $('#mensaje').hide(); 
        if(idfactura){ 
          $('#mensaje').show(); 
          $("#mensaje").text('Buscando Factura... !');  
        }
  }); 

 $(document).ready(function() { 
 /*
    $( "#tipoinsumo" ).on( "change", function() {
          if($( "#tipoinsumo" ).val()=='REPUESTO'){
             $("#maquinas").show();
          }else{
           $("#maquinas").hide();
          }
          
    });

     $( "#plazo" ).on( "change", function() {
          if($( "#plazo" ).val()!=''){
             $("#plazodias").show();
          }else{
           $("#plazodias").hide();
          }
          
    });

  if ($( "#tipoinsumo" ).val()=='REPUESTO'){
       $("#maquinas").show();
           }else{
            $("#maquinas").hide();
        }
  
   if($( "#plazo" ).val()!=''){
           $("#plazodias").show();
        }else{
         $("#plazodias").hide();
        }*/


  });  
 

  
</script>
 