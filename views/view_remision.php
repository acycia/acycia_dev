 
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

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table id="tabla1">
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                 <div class="panel-heading"><h2>HISTORICO</h2></div>
                  
                    <div class="panel-body">
                      <br> 
                      <div class="container">
                       <div class="row">
                         <div class="span12">
                          <table id="tabla2"> 
                            <tr>
                             <td id="subtitulo">
                              HISTORICO DE REMISIONES
                            </td>
                          </tr> 
                      </table> 
                    </div>
                  </div>
                  <br> 
               <br>
               <!-- grid --> 

               <hr>
                <div class="row align-items-start">  
                   <div class="col-lg-1" ><strong>IDREMISION</strong></div> 
                   <div class="col-lg-2" ><strong>NUMERO   OC</strong></div>
                   <div class="col-lg-2" ><strong>FECHA </strong></div>
                   <div class="col-lg-2" ><strong>GUIA</strong></div> 
                   <div class="col-lg-4" ><strong>MODIFICO Y FECHA</strong></div> 
                   <div class="col-lg-1" ><strong>VER</strong></div>     
                </div> 
                <?php foreach($this->ordenc as $dato) {  ?>
               <div class="row celdaborde1">
                 <div class="col-lg-1" id="fondo_2"><a target="_blank" style="text-decoration:none; color:#000000" href="view_remision_edit_hist.php?int_remision=<?php echo $dato['int_remision']; ?>&str_numero_r=<?php echo $dato['str_numero_oc_r']; ?>&id_oc=<?php echo $dato['id_c_oc'];?>">
                   <p><?php echo $dato['int_remision']; ?></a></p>
                 </div> 
                 <div class="col-lg-2" id="fondo_2"><a target="_blank" style="text-decoration:none; color:#000000" href="view_remision_edit_hist.php?int_remision=<?php echo $dato['int_remision']; ?>&str_numero_r=<?php echo $dato['str_numero_oc_r']; ?>&id_oc=<?php echo $dato['id_c_oc'];?>">
                   <p><?php echo $dato["str_numero_oc_r"]; ?></a></p>
                 </div> 
                 <div class="col-lg-2" id="fondo_2"><a target="_blank" style="text-decoration:none; color:#000000" href="view_remision_edit_hist.php?int_remision=<?php echo $dato['int_remision']; ?>&str_numero_r=<?php echo $dato['str_numero_oc_r']; ?>&id_oc=<?php echo $dato['id_c_oc'];?>">
                   <p><?php echo $dato["fecha_r"]; ?></a></p>
                 </div>
                 <div class="col-lg-2" id="fondo_2"><a target="_blank" style="text-decoration:none; color:#000000" href="view_remision_edit_hist.php?int_remision=<?php echo $dato['int_remision']; ?>&str_numero_r=<?php echo $dato['str_numero_oc_r']; ?>&id_oc=<?php echo $dato['id_c_oc'];?>">
                   <p><?php echo $dato["str_guia_r"]; ?></a></p>
                 </div>
                 <div class="col-lg-4" id="fondo_2"><a target="_blank" style="text-decoration:none; color:#000000" href="view_remision_edit_hist.php?int_remision=<?php echo $dato['int_remision']; ?>&str_numero_r=<?php echo $dato['str_numero_oc_r']; ?>&id_oc=<?php echo $dato['id_c_oc'];?>">
                   <p><?php echo $dato["modifico"]; ?></a></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p> 
                     <a target="_blank" style="text-decoration:none; color:#000000" href="view_remision_edit_hist.php?int_remision=<?php echo $dato['int_remision']; ?>&str_numero_r=<?php echo $dato['str_numero_oc_r']; ?>&id_oc=<?php echo $dato['id_c_oc'];?>"><img src="images/pincel.PNG" alt="VER" title="VER"  border="0" style="cursor:hand;" width="20" height="18" /></a>
                   </p>
                 </div> 
               </div>
               <?php  } ?>

             <br><br><br>
                <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $dato['id_pedido']; ?>">SALIR</a>  -->
                <a class="botonGeneral" style="text-decoration:none; "href="?int_remision=<?php echo $dato['id_pedido']; ?>">SALIR</a> 
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

<!-- js Bootstrap-->
<script src="bootstrap-4/js/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="bootstrap-4/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>



<?php
 

?>
