<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php
 require_once("Controller/Csellado.php");
//initialize the session

session_start();

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
  <script type="text/javascript" src="AjaxControllers/js/funcionesSellado.js"></script>  

  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

   <!-- Loading -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
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
                 <div class="panel-heading"><h2>CAJAS NEW</h2></div>

                 <div class="panel-body">
                   <br> 
                   <div class="container">
                    <div class="row">
                      <div class="span12">
                        <div class="bordesolido"  >
                         <?php 
                         //$row_caja_num = $conexion->llenarCampos("tbl_tiquete_numeracion", "WHERE int_op_tn='".$_GET['id_op']."'", "ORDER BY int_caja_tn DESC,int_paquete_tn DESC LIMIT 1", "DISTINCT int_caja_tn,int_op_tn,imprime ");

                         //$row_opfaltantes = $conexion->llenarCampos("tbl_orden_produccion", "WHERE id_op='".$_GET['id_op']."'", "", "imprimiop ");

                         $select_caja_num = $conexion->llenaListas('tbl_tiquete_numeracion',"WHERE int_op_tn='".$_GET['id_op']."'",'ORDER BY int_caja_tn DESC,int_paquete_tn DESC', "DISTINCT int_caja_tn,int_op_tn,imprime");

                          
                        foreach($select_caja_num as $row_caja_num) {  ?> 
               
                          <p class="letraPaquete">


                      
                                <div id="paquetexcaja"  style="display: none;"> 
                                 <div class="bordesolido" id="ventanas">  
                                  <table id="example" class="display" style="width:100%" border="1">
                                    <thead>
                                    <tr>
                                      <td colspan="8" >&nbsp;
                                        <em style="align-items: center; justify-content: center;color: red; " id="Mostrando"></em> 
                                      </td>
                                    </tr>
                                      <tr>
                                        <th>PAQUETEF</th>
                                        <th>DESDEF</th>
                                        <th>HASTAF</th> 
                                      </tr>
                                    </thead>
                                    <tbody id="DataConsulta"> 
                                      
                                    </tbody>
                                  </table> <br> 
                                 </div> 
                                </div>

                                <div id="tiquetxCajas" style="display: none;"> 
                                  <div class="bordesolido" id="ventanas">
                                    <table id="example" class="display" style="width:100%" border="1">
                                      <thead>
                                      <tr>
                                        <td colspan="8" >&nbsp;
                                          <em style="align-items: center; justify-content: center;color: red; " id="Mostrando"></em> 
                                        </td>
                                      </tr>
                                        <tr>
                                          <th>CAJA</th>
                                          <th>DESDE</th>
                                          <th>HASTA</th> 
                                        </tr>
                                      </thead>
                                      <tbody id="DataConsultaxCaja"> 
                                        
                                      </tbody>
                                    </table> <br> 
                                  </div>
                                </div>
                       
                            <a href="#" style="color:red;" name="<?php echo $row_caja_num['imprime']; ?>" id="<?php echo $row_caja_num['int_caja_tn']; ?>" class="zonalink">VER PAQUETES DE CAJA: <?php echo $row_caja_num['int_caja_tn'];?>--------------</a> 

                            <a href="javascript:popUp('sellado_control_cajas_vista.php?id_op=<?php echo $row_caja_num['int_op_tn']; ?>&int_caja_tn=<?php echo $row_caja_num['int_caja_tn']; ?>','1200','780')" target="_top"><?php echo "IMPRIME STICKERS CAJAS: ".$row_caja_num['int_caja_tn']; ?></a>
                            -- <a href="javascript:eliminarconAlerta(<?php echo $row_caja_num['int_op_tn']; ?>,<?php echo $row_caja_num['int_caja_tn']; ?>,'')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR TODOS LOS PAQUETES" title="ELIMINAR TODOS LOS PAQUETES" border="0"></a> 
                          </p>
                        <?php  } ?>
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
        var ops = "<?php echo $row_caja_num['int_op_tn']; ?>";
        //var faltante = "<?php echo $row_opfaltantes['imprimiop']; ?>";
      $(".zonalink").on("click",function(event) {
        
        var consec = $(this).attr('id');
        var faltante = $(this).attr('name');

        if(consec && ops){
            if(faltante==0){
                 var func = "Consultar";
                consultaPaquetesHistorico(ops,consec,func);  
                }else{
                 var func = "ConsultarTiquetxOP";
                consultaPaquetesHistoricoColas(ops,consec,func);
            }
        }  
     });

   
 
 </script>


<?php
mysql_free_result($usuario);

?>
