<?php
   //require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   //require (ROOT_BBDD); 
?> 
<?php 
//$conexion = new ApptivaDB();

//$proveedores = $conexion->llenaSelect('proveedor','','ORDER BY proveedor_p ASC');

include('funciones/funciones_php.php'); 
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
<form action="#" method="post" enctype="multipart/form-data" name="form1" >
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
                         CONTROL DE OPERACIONES
                       </td>
                     </tr> 
                 </table> 
               </div>
             </div> 
             <br>
             <table class="table table-striped" id="items" >
               <thead>
                 <tr>
                  <tr>
                    <td> 
                      Proceso
                      <select name="proceso" id="proceso" class="selectsMedio busqueda" required="required" class="selectsMini"> 
                        <option value="PROFORMA"<?php if (!(strcmp("PROFORMA",$_GET['proceso']))){echo "selected=\"selected\"";} ?>>PROFORMA</option>         
                        <option value="ENTRADA FACTURA"<?php if (!(strcmp("ENTRADA FACTURA",$_GET['proceso']))){echo "selected=\"selected\"";} ?>>ENTRADA FACTURA</option>
                        <option value="DETALLE EMBARQUE"<?php if (!(strcmp("DETALLE EMBARQUE",$_GET['proceso']))){echo "selected=\"selected\"";} ?>>DETALLE EMBARQUE</option>
                        <option value="ENTRADA MERCANCIA"<?php if (!(strcmp("ENTRADA MERCANCIA",$_GET['proceso']))){echo "selected=\"selected\"";} ?>>ENTRADA MERCANCIA</option>
                        <option value="EXPO"<?php if (!(strcmp("EXPO",$_GET['proceso']))){echo "selected=\"selected\"";} ?>>EXPORTACION</option>
                      </select> 
                      Estado
                      <select name="estado" id="estado" class="selectsMedio busqueda" required="required" class="selectsMini"> 
                        <option value="">Seleccione Estado</option>
                        <option value="EN TRANSITO"<?php if (!(strcmp("EN TRANSITO",$_GET['id']))){echo "selected=\"selected\"";} ?>>EN TRANSITO</option>              
                        <option value="INVENTARIO"<?php if (!(strcmp("INVENTARIO",$_GET['id']))){echo "selected=\"selected\"";} ?>>INVENTARIO</option>
                      </select>
                       Tipo de Pedido
                       <select name="tipopedido" id="tipopedido" class="selectsMedio busqueda" required="required" class="selectsMini">
                        <option value="">Seleccione</option> 
                        <option value="Nacional"<?php if (!(strcmp("Nacional",$_GET['id']))){echo "selected=\"selected\"";} ?>>Nacional</option> 
                        <option value="Importacion"<?php if (!(strcmp("Importacion",$_GET['id']))){echo "selected=\"selected\"";} ?>>Importacion</option> 
                        <option value="Exportacion"<?php if (!(strcmp("Exportacion",$_GET['id']))){echo "selected=\"selected\"";} ?>>Exportacion</option>
                      </select>
                      Proforma #
                      <input name="proforma" id="proforma" value="<?php echo $_GET['columna']=='proforma' ? $_GET['id'] : ''; ?>" type="text" placeholder="Proforma" size="20" required="required" > 
                      Factura
                      <input name="factura" id="factura" value="<?php echo $_GET['columna']=='factura' ? $_GET['id'] : ''; ?>" type="text" placeholder="Factura" size="20" required="required" > 
                      Pedido AC
                      <input name="pedido" id="pedido" value="<?php echo $_GET['columna']=='pedido' ? $_GET['id'] : ''; ?>" type="number" placeholder="Pedido" size="20" required="required" ><br><br>
                      Proveedor
                      <select name="proveedor" id="proveedor" class="selectsMMedio busqueda" required="required">
                       <option value="">Seleccione Proveedor</option>
                       <?php foreach($this->proveedores as $proveedores) {  ?>
                         <option value="<?php echo $proveedores['proveedor_p']; ?>"<?php if (!(strcmp($proveedores['proveedor_p'], $_GET['id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($proveedores['proveedor_p']); ?> </option>
                       <?php } ?>
                     </select> 
                     Fecha Proceso
                     <input name="fecha" id="fecha" type="text" placeholder="Fecha" value="<?php echo $_GET['columna']=='fecha' ? $_GET['id'] : ''; ?>" required="required" class="campostextMini" > 
                    <br> 
                 </td>
                 <tr>
                  <td><em style="display: none;  align-items: center; justify-content: center;color: red; " id="mensaje" ></em> </td>
                </tr>
              </tr>
              <th scope="col">ITEMS</th> 
            </tr>
          </thead> 
        </table>

        <?php if($this->general): ?>
        <div style="text-align: left;">
           &nbsp;&nbsp;Descarga Excel 
        </div> 
         <div style="text-align: left;">
            <a style="text-decoration:none; "href='view_index.php?c=comprasCO&a=Excel&proceso=<?php echo $_GET["proceso"]; ?>&columna=<?php echo $_GET["columna"]; ?>&id=<?php echo $_GET["id"]; ?>  ' ><img type="image" style="width: 50px; height: 60px;" name="botondeexcel" id="botondeexcel" src="images/11.PNG" alt="DESCARGA EXCEL" title="DESCARGA EXCEL"></a> 
         </div> <br> 
        <?php endif; ?>


      <?php if( isset($_GET['id']) && $_GET['id']!='' && $_GET['id'] !='Exportacion') : ?>           
       <table border="2" style="width: 100%; height: 50px;" > <!-- class="table table-bordered" -->
           <thead>
               <tr>
                 <th class="azul" nowrap="nowrap"><strong>ORDEN DE COMPRA</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>PROVEEDOR</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>MATERIAL OC</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>KILOS / METROS / UNIDAD</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>TIPO DE EMBARQUE</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>PROFORMA No.</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>FECHA PROFORMA</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>TRM</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>FACTURA N°</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>VALOR FACTURA USD O EUR</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>FECHA FACTURA</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>VALOR FOB USD SEGÚN DIM</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>ETD</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>TIEMPO DE TRANSITO</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>ETA</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>PUERTO DE LLEGADA</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>FECHA DE LLEGADA A PLANTA</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>FECHA ESTIMADA DE  PAGO</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>FECHA DE  REAL DE PAGO</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong># BL</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>FECHA DE BL</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong># DE FORMULARIO</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong># DECLARACION IMPORT</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>FECHA DIM</strong></th>
                 <th class="azul" nowrap="nowrap"><strong>PROCESO</strong></th> 
                 <th class="azul" nowrap="nowrap"><strong>ESTADO</strong></th>
                 <th class="azul" nowrap="nowrap"><strong>PAGO?</strong></th>  
               </tr>
           </thead>
           <tbody>
           <?php foreach($this->general as $dato) { ?>
            <tr>  
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato['pedido']; ?>
               </td> 
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["proveedor"]; ?>
               </td> 
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["descripcion"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["medida"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["tipopedido"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato['proforma']; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato['fecha']; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato['trm']; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato['factura']; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["moneda"]. ' '. numeros_format($dato["precio_total"]); ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo numeros_format($dato["valoricot"]); ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo 'ETD'; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo 'TIEMPO DE TRANSITO'; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha_eta"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["puerto_lleg"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php $date = date('Y-m-d'); ?>
                    <?php if($date > $dato["fecha_plazo"]  && $dato['pago']!='SI'): ?>
                           <span style="color: red;" ><?php echo $dato["fecha_plazo"]; ?></span>
                                <script type="text/javascript">swal("Ojo", "En Importaciones Hay Facturas vencidas ", "error");</script>
                          <?php else: ?>
                    <?php echo $dato["fecha_plazo"]; ?> 
                <?php endif; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo 'FECHA DE REAL DE PAGO'; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["bl"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha_bl"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo '# DE FORMULARIO'; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["declara"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha_dec"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["proceso"]; ?> 
               </td> 
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["estado"]; ?> 
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php if($dato['id'] && $dato['pago']!='SI' && $_SESSION['acceso']): ?> 
                   <span id="#resp" style="display: none;" > Se Actualizo correctamente!</span>  
                   <a class="botonDel" id="btnDelItems" onclick='uPDATE("<?php echo $dato['id']; ?>","<?php echo $_GET['id']; ?>","<?php echo $_GET['columna']; ?>", "<?php echo $_GET['proceso']; ?>", "?c=comprasCO&a=Actualizar")' type="button" >PAGO?</a><!-- botonUpdate -->
                  <?php   else : ?>
                   <a class="botonUpdate" id="btnDelItems" type="button" >PAGADO</a>
                  <?php endif; ?>  
               </td> 
            </tr> 
            <?php } ?>
           </tbody>
       </table>
      <?php else: ?>
       <table border="2" style="width: 100%; height: 50px;" > <!-- class="table table-bordered" -->
           <thead>
               <tr>
                 <th class="verde" nowrap="nowrap"><strong>FACTURA N°</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>FECHA FACTURA</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>CLIENTE</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>VENCE</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>VALOR FACTURA USD </strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>FLETE + SEGURO </strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>VR FOB O CIF </strong></th> 
                 <th class="verde" nowrap="nowrap"><strong># BL/GUIA</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>FECHA </strong></th> 
                 <th class="verde" nowrap="nowrap"><strong># DEX</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>FECHA DEX</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>PAGO</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>FECHA DE PAGO</strong></th> 
                 <th class="verde" nowrap="nowrap"><strong>COMENTARIOS</strong></th>
                 <th class="azul" nowrap="nowrap"><strong>PAGO?</strong></th>  
               </tr>
           </thead>
           <tbody>
           <?php foreach($this->general as $dato) { ?>
            <tr>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato['factura']; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha_factura"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["proveedor"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php $date2 = date('Y-m-d'); ?>
                    <?php if($date2 > $dato["fecha_pago"]  && $dato['pago']!='SI'): ?>
                           <span style="color: red;" ><?php echo $dato["fecha_pago"]; ?></span>
                                <script type="text/javascript">swal("Ojo", "En Exportaciones Hay Facturas vencidas ", "error");</script>
                          <?php else: ?>
                    <?php echo $dato["fecha_pago"]; ?> 
                <?php endif; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["moneda"]. ' '. numeros_format($dato["precio_total"]); ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fleteseguro"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo numeros_format($dato["valoricot"]); ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["bl"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha_bl"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["dex"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha_dex"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["pago"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["fecha_pago"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php echo $dato["comentarios"]; ?>
               </td>
               <td nowrap="nowrap" class="gris">
                 <?php if($dato['id'] && $dato['pago']!='SI' && $_SESSION['acceso']): ?> 
                   <span id="#resp" style="display: none;" > Se Actualizo correctamente!</span>  
                   <a class="botonDel" id="btnDelItems" onclick='actualizacion("<?php echo $dato['id']; ?>","<?php echo $_GET['id']; ?>","<?php echo $_GET['columna']; ?>", "<?php echo $_GET['proceso']; ?>", "?c=comprasCO&a=Actualizar")' type="button" >PAGO?</a><!-- botonUpdate -->
                  <?php   else : ?>
                   <a class="botonUpdate" id="btnDelItems" type="button" >PAGADO</a>
                  <?php endif; ?>  
               </td> 
            </tr> 
            <?php } ?>
           </tbody>
       </table>

      <?php endif; ?>

 
       <br><br><br>
         <div class="panel-footer" >
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
    
    function uPDATE(id,valor,colum,proceso,url){
 
     
       swal({   
        title: "Actualizar?",   
        text: "Esta seguro que Quiere Actualizar a Pagado!",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Si, Actualizar!",   
        cancelButtonText: "No, Actualizar!",   
        closeOnConfirm: false,   
        closeOnCancel: false }, 
        function(isConfirm){   
          if (isConfirm) {  
            swal("Actualizado!", "El registro se ha Actualizado.", "success"); 
            actualizacion(id,valor,colum,proceso,url);
            location.reload(); 
          } else {     
            swal("Cancelado", "has cancelado :)", "error"); 
          } 
        });  

    } 

  $( "#proceso" ).on( "change", function() {
        idproceso = $( "#proceso" ).val();  
        idproceso = $( "#proceso" ).val();    
        window.location="view_index.php?c=comprasCO&a=Crud&proceso="+idproceso+"&columna=proceso&id="+idproceso;
        $('#mensaje').hide(); 
        if(idproceso){ 
          $('#mensaje').show(); 
          $("#mensaje").text('Buscando Proceso... !');  
        }
   });

  $( "#estado" ).on( "change", function() {
        idproceso = $( "#proceso" ).val();  
        idestado = $( "#estado" ).val();    
        window.location="view_index.php?c=comprasCO&a=Crud&proceso="+idproceso+"&columna=estado&id="+idestado;
        $('#mensaje').hide(); 
        if(idestado){ 
          $('#mensaje').show(); 
          $("#mensaje").text('Buscando Estado... !');  
        }
   });

  $( "#proforma" ).on( "change", function() {
       idproceso = $( "#proceso" ).val();   
       idproforma = $( "#proforma" ).val();    
       window.location="view_index.php?c=comprasCO&a=Crud&proceso="+idproceso+"&columna=proforma&id="+idproforma;
       $('#mensaje').hide(); 
       if(idproforma){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando Proforma... !');  
       }
  });

  $( "#factura" ).on( "change", function() {
       idproceso = $( "#proceso" ).val();  
       idfactura = $( "#factura" ).val();    
       window.location="view_index.php?c=comprasCO&a=Crud&proceso="+idproceso+"&columna=factura&id="+idfactura;
       $('#mensaje').hide(); 
       if(idfactura){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando Factura... !');  
       }
  });

  $( "#pedido" ).on( "change", function() {
       idproceso = $( "#proceso" ).val();  
       idpedido = $( "#pedido" ).val();    
       window.location="view_index.php?c=comprasCO&a=Crud&proceso="+idproceso+"&columna=pedido&id="+idpedido;
       $('#mensaje').hide(); 
       if(idpedido){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando Pedido... !');  
       }
  });

  $( "#proveedor" ).on( "change", function() {
       idproceso = $( "#proceso" ).val();  
       idproveedor = $( "#proveedor" ).val();    
       window.location="view_index.php?c=comprasCO&a=Crud&proceso="+idproceso+"&columna=proveedor&id="+idproveedor;
       $('#mensaje').hide(); 
       if(idproveedor){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando Proveedor... !');  
       }
  });

  $( "#fecha" ).on( "change", function() {
       idproceso = $( "#proceso" ).val();  
       idfecha = $( "#fecha" ).val();    
       window.location="view_index.php?c=comprasCO&a=Crud&proceso="+idproceso+"&columna=fecha&id="+idfecha;
       $('#mensaje').hide(); 
       if(idfecha){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando Fecha... !');  
       }
  });

  $( "#tipopedido" ).on( "change", function() {
       idproceso = $( "#proceso" ).val();  
       idtipopedido = $( "#tipopedido" ).val();    
       window.location="view_index.php?c=comprasCO&a=Crud&proceso="+idproceso+"&columna=tipopedido&id="+idtipopedido;
       $('#mensaje').hide(); 
       if(idtipopedido){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando Tipopedido... !');  
       }
  });    
    
  
</script>
 