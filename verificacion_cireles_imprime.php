<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
<?php 
 require_once('Connections/conexion1.php');
 require_once("db/db.php");
 require_once("Controller/Cgeneral.php");
 require_once 'Models/Mgeneral.php'; 
 
 //CUANDO NO TIENE UNA VERIFICACION SE INSERTA LA VERIFICACION AUTOMATICA
  $conexion = new CgeneralController();
 
  $row_plancha=$conexion->llenarCampos("tblreporteplanchas","WHERE id =".$_GET['id_imprime'],"","*");

  ?>
<html>
<head>
  <title>VERIFICAR CIRELES</title>
  <link href="css/general.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <link rel="stylesheet" type="text/css" href="css/formato.css"/>
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" /> 
 
</head>
<body oncontextmenu="return false">
 
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table>
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                 <div class="row">
                 <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/cabecera.jpg"></div>
                 <div class="span12"><h3>&nbsp;&nbsp;&nbsp;&nbsp;REPORTE DE PLANCHAS MALAS &nbsp;&nbsp;&nbsp; </h3></div>
               </div>
               <div class="panel-heading" align="left" ></div><!--color azul-->

               <div class="panel-body">
                 <br> 
                 <div ><!--  SI QUITO  class="container" SE ALINEA A LA IZQUIERDA TODO EL CONTENIDO DE ESTE Y SE REDUCE -->
                  <div class="row">
                    <div class="span12">
                     <table id="tabla2"> 
                       <tr>
                        <td id="subtitulo">
                         CIRELES <img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0">
                       </td>
                     </tr> 
                   </table> 
                 </div>
               </div>
               <br> 
               <br>
               <!-- grid --> 
 
                <table id="tabla1">
                  <tr id="fondo_3">
                    <td colspan="2" id="subtitulo_1">CLIENTE</td>
                    <td colspan="2" id="subtitulo_2"><?php echo $row_plancha['cliente']; ?></td>
                  </tr>
                  <tr id="fondo_3">
                    <td colspan="2" id="subtitulo_1">REFERENCIA</td> 
                    <td colspan="2" id="subtitulo2"><?php echo $row_plancha['ref']; ?></td> 
                  </tr>
                  <tr >
                    <td colspan="2" id="subtitulo_2">&nbsp;</td> 
                    <td colspan="2" id="subtitulo_2">&nbsp;</td> 
                  </tr>
                  <tr >
                    <td colspan="2" id="subtitulo_1"> </td> 
                    <td colspan="2" id="fondo_3">MOTIVO DE DAÑO</td> 
                  </tr>
                  <tr id="fondo_3">
                    <td colspan="2" id="subtitulo2"> </td> 
                    <td colspan="2" id="subtitulo2">USO / DAÑO</td> 
                  </tr>
                   
                  <tr id="fondo_3">
                    <td id="subtitulo_1">COLOR 1</td> 
                    <td id="subtitulo2"><?php echo $row_plancha['color1']; ?></td>
                    <td colspan="2" id="subtitulo2"><?php echo $row_plancha['motivo']; ?></td>  
                  </tr>
                  <tr id="fondo_3">
                    <td id="subtitulo_1">COLOR 2</td> 
                    <td id="subtitulo2"><?php echo $row_plancha['color2']; ?></td>
                    <td colspan="2" id="subtitulo2"><?php echo $row_plancha['motivo2']; ?></td>  
                  </tr>
                  <tr id="fondo_3">
                    <td id="subtitulo_1">COLOR 3</td> 
                    <td id="subtitulo2"><?php echo $row_plancha['color3']; ?></td>
                    <td colspan="2" id="subtitulo2"><?php echo $row_plancha['motivo3']; ?></td>  
                  </tr>
                  <tr id="fondo_3">
                    <td id="subtitulo_1">COLOR 4</td> 
                    <td id="subtitulo2"><?php echo $row_plancha['color4']; ?></td>
                    <td colspan="2" id="subtitulo2"><?php echo $row_plancha['motivo4']; ?></td>  
                  </tr>
                  <tr > 
                    <td colspan="4" id="subtitulo2">&nbsp;</td>  
                  </tr>
                  <tr id="fondo_3">
                    <td id="subtitulo2">FECHA DE REPORTE</td> 
                    <td id="subtitulo2"><?php echo $row_plancha['fecha_reporte']; ?></td>
                    <td id="subtitulo2">SE HIZO REPOSICION</td> 
                    <td id="subtitulo2"><?php echo $row_plancha['se_hizo_repo']; ?></td>  
                  </tr>
                  <tr id="fondo_3">
                    <td id="subtitulo2">FECHA REPOSICION</td> 
                    <td id="subtitulo2"><?php echo $row_plancha['fecha_repo']; ?></td>    
                    <td id="subtitulo2">RESPONSABLE</td> 
                    <td id="subtitulo2"><?php echo $row_plancha['responsable']; ?></td>
                  </tr> 
    
    <tr>
      <td colspan="3" id="dato_2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" id="dato_1"> </td>
    </tr> 
  </table>
  

 
</div>
</div>
</div>
</div>
</div>
</td>

</body>
</html>
<script>
     $(document).ready(function(){
      var cod_ref = "<?php echo $row_verif_ref_egp['cod_ref']==''? $row_verificar_cirel['id_ref_verif']:$row_verif_ref_egp['cod_ref']; ?>"
      consultasPlanchas(cod_ref);//despliega los items
    }); 
    $( "#btnEnviarItems" ).on( "click", function() {
     if($("#color1").val()=='' ){
       swal("Alerta"," Ingrese Color 1","warning")
          return false;
     }
     if($("#color1").val()!='' && $("#motivo").val()==''){
       swal("Alerta"," Ingrese Motivo 1","warning")
          return false;
     }
     if($("#color2").val()!='' && $("#motivo2").val()==''){
       swal("Alerta"," Ingrese Motivo 2","warning")
          return false;
     }
     if($("#color3").val()!='' && $("#motivo3").val()==''){
       swal("Alerta"," Ingrese Motivo 3","warning")
          return false;
     }
     if($("#color4").val()!='' && $("#motivo4").val()==''){
       swal("Alerta"," Ingrese Motivo 4","warning")
          return false;
     }else{ 
      guardarItemsCireles();
    }

   

  });


    $(document).ready(function(){
      var editar =  "<?php echo $_SESSION['no_edita'];?>";//es una excepcion
 
      //excepcion para el de planchas
      if(editar==0 )//es una excepcion
      {
         
        $("input").attr('disabled','disabled');
        $("textarea").attr('disabled','disabled');
        $("select").attr('disabled','disabled');
        $("button").attr('disabled','disabled');

        $('a').each(function() { 
          $(this).attr('href', '#');
        }); 
                 swal("No Autorizado", "Sin permisos para editar :)", "error"); 
      }
    });
</script> 
<?php
mysql_free_result($verificar_cirel);

mysql_free_result($verif_ref_egp);

mysql_free_result($usuario);
?>
