<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
 
//initialize the session

session_start();

$conexion = new ApptivaDB();


  //TIQUETES X CAJAS
   //$registros = $conexion->llenaListas('tbl_tiquete_numeracion', "WHERE int_op_tn='".$_GET['id_op']."' ",'ORDER BY int_caja_tn DESC','DISTINCT int_caja_tn,int_op_tn' ); 
   //$registros = $conexion->llenaListas('tbl_tiquete_numeracion',"WHERE int_op_tn='".$_GET['id_op']."' and imprime='1' ",'ORDER BY int_caja_tn DESC', "DISTINCT id_tn, int_caja_tn,int_op_tn"); //modifico fecha 05/10/2021
   $registros = $conexion->llenaListas('tbl_tiquete_numeracion',"WHERE int_op_tn='".$_GET['id_op']."'  and imprime='1' ",'GROUP BY int_caja_tn ORDER BY int_caja_tn DESC', " id_tn,int_op_tn,int_caja_tn "); 

   //$numeracion = $conexion->llenaListas('tbl_numeracion',"WHERE int_op_n='".$_GET['id_op']."' ","", " int_desde_n,int_hasta_n ");
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
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
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
                 <div class="panel-heading"><h2>CAJAS</h2></div>

                 <div class="panel-body">
                   <br> 
                   <div class="container">
                    <div class="row">
                      <div class="span12">
                        <div class="bordesolido"  >
                         <?php 
                        //foreach($numeracion as $numeracion) { $numeracion; }

                         //Navegamos cada fila que devuelve la consulta mysql y la imprimimos en pantalla
                         foreach($registros as $fila) { 
                              //$op_tipxcaj=$fila['int_op_tn']; $caja_tipxcaj=$fila['int_caja_tn']; 
                               $id_tn = $fila['id_tn'];
                          ?>          
                          <p class="letraPaquete"><a href="javascript:popUp('sellado_totaltiqxcaja_colas.php?id_op=<?php echo $fila['int_op_tn']; ?>&int_caja_tn=<?php echo $fila['int_caja_tn']; ?>','1200','780')" target="_top"><?php echo " TIQUETES X CAJAS #: ".$fila['int_caja_tn'];?></a><!-- -------<a href="javascript:eliminar_cajas('id_tncaja',<?php echo $fila['id_tn'];?>,'sellado_control_numeracion_edit.php')"><img src="images/por.gif" style="cursor:hand;" alt="ELIMINAR TIQUETE" title="ELIMINAR TIQUETE" border="0"></a> --></p>

                        <?php }?>
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
  
          function eliminar_cajas(campo1,id_tn)
          {
            swal({
              title: "Estas seguro?",
              text: "Quiere Eliminar el Paquete y sus faltantes?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: '#DD6B55',
              confirmButtonText: 'Si, Eliminarlos!',
              cancelButtonText: "No, cancelarlo!",
              closeOnConfirm: false,
                 // closeOnCancel: false
             },
             function(isConfirm) {
              if (isConfirm) { 
                window.location ="delete2.php?"+campo1+"="+id_tn;  
              } else {
                swal("Cancelado " , " Su archivo no se Eliminaros :)", "error");
              }
             });
  
  }

</script>

<?php
mysql_free_result($usuario);

?>
