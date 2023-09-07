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
<form action="view_index.php?c=comprasLQU&a=Guardar" method="post" enctype="multipart/form-data" name="form1" >
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
                         CARGA - DE LA LIQUIDACION
                       </td>
                     </tr> 
                 </table> 
               </div>
             </div> 
             <br> 
             <table class="table table-striped" id="items" >
               <thead>

               </thead>
               <tbody > 
                 <tr>
                  <td nowrap>  
                    <div class="row" >
                      <div class="span4" >
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Adjuntar Excel de <b>Liquidacion </b> : <input class="botonGMini" type="file" name="adjunto" id="adjunto" size="100" /> &nbsp;&nbsp;Adjuntar Excel de <b>Control</b> : <input class="botonGMini" type="file" name="adjunto1" id="adjunto1" size="100" />
                      </div>
                      <div class="span4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                       <button id='botondeenvio' type="submit" ><img type="image" style="width: 30px; height: 40px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="CARGAR" title="CARGAR" > </button> 
                       </div>
                        <div class="span4" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                          <?php if(isset($_GET['id']) && $_GET['id'] !=''): ?>
                             <em style="color: red;font-style: oblique; " id="mensaje" ><?php echo $_GET['id'];?></em> 
                          <?php endif; ?>
                       </div>
                    </div>
                   </td> 
                 </tr>
                 <tr>
                  <th scope="col"> 
                        AQUÍ PUEDE DESCARGAR EL ARCHIVO DE LIQUIDACION
                  </th>
                 </tr> 
                 <tr>
                   <td>
                    <?php //if(isset($_GET['nombre']) && $_GET['nombre'] !='' && isset($_GET['mostrar']) && $_GET['mostrar'] == 1): ?>
                         <!-- <a href="pdfprocesocompras/<?php echo  $_GET['nombre'];?> "> Ver Archivo de Liquidación </a> -->
                       <a href="javascript:verFoto('pdfprocesocompras/<?php echo $_GET['nombre'];?>','700','400')"> Ver Archivo de Liquidación </a> 
                    <?php //endif; ?>
                   </td>
                 </tr>  
                <tr> 
                   <td> 
                    <input name="estado" id="estado" readonly="readonly" type="hidden" value="LIQUIDACION" >
                  </td>
                </tr>
               </tbody>
             </table> 
             <div class="panel-footer" > 
                <a class="botonFinalizar" style="text-decoration:none; " href="javascript:Salir('view_index.php?c=compras&a=Menu')" >SALIR</a>  
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
  $( "#botondeenvio" ).on( "click", function() {
      if($("#adjunto").val() =='' && $("#adjunto1").val() =='') {
          swal("Vacios!", "Alguno de los registros debe llenarse", "success"); 
          return false;
      }
      
  });

 $(document).ready(function() { 

  setTimeout(function() { $("#mensaje").fadeOut(); },7000);
 

  });  
 

  
</script>
 