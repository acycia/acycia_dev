<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 

  <?php

   session_start();

$conexion = new ApptivaDB();

if ((isset($_POST['MM_update'])) && !(empty($_POST['MM_update']) )) {
   //obtenemos el archivo .csv
 
   $tipo = $_FILES['archivo']['type'];
   $tamanio = $_FILES['archivo']['size'];
   $archivotmp = $_FILES['archivo']['tmp_name'];
    
   //cargamos el archivo
   $lineas = file($archivotmp);
   //inicializamos variable a 0, esto nos ayudará a indicarle que no lea la primera línea
   //Recorremos el bucle para leer línea por línea

   
   foreach ($lineas as $linea_num => $linea)
   { 
      //abrimos bucle
      /*si es diferente a 0 significa que no se encuentra en la primera línea 
      (con los títulos de las columnas) y por lo tanto puede leerla*/
      for($i=0;$i <=$linea;$i++) 
      { 
     

          //abrimos condición, solo entrará en la condición a partir de la segunda pasada del bucle.
          /* La funcion explode nos ayuda a delimitar los campos, por lo tanto irá 
          leyendo hasta que encuentre un ; O , */
          $datos = explode(",",$linea);//;
          //CONSULTO SI EXISTE
          $str_numero_oc =str_replace(' ', '', $datos[0]);
          //$str_numero_oc = strtoupper($str_numero_oc);
          $resultvi = $conexion->llenarCampos("tbl_orden_compra", "WHERE str_numero_oc='$str_numero_oc' ", " ", "str_numero_oc");
          if($resultvi['str_numero_oc'] !='')
           { 
          //UPDATE en base de datos la línea que existe
           $factura_oc =str_replace(' ', '', $datos[1]); 
           $actualizofac = $conexion->actualizar("tbl_orden_compra", "factura_oc='$factura_oc', b_estado_oc='5'", " str_numero_oc='$str_numero_oc' " ); 

           //UPDATE remision
            $actualizoremfac = $conexion->actualizar("tbl_remisiones", "factura_r='$factura_oc' ", " str_numero_oc_r='$str_numero_oc' " ); 

            } 
        
      }//for cerramos bucle
   } 

}

  ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

 
 
 
//$row_facturas = $conexion->llenarCampos("tbl_orden_compra", "WHERE int_op_tn='".$_GET['id_op']."' AND int_caja_tn='".$cajamenos1."'", "ORDER BY int_caja_tn,int_paquete_tn DESC LIMIT 1", "*");

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
  <script type="text/javascript" src="AjaxControllers/js/updates.js"></script>
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 

  <!-- css Bootstrap-->
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
                 <div class="panel-heading"><h2>ACTUALIZAR FACTURAS</h2></div>
                  
               <div class="panel-body">
                 <br> 
                 <div class="container">
                  <div class="row">
                    <div class="span12">
                     <table id="tabla2"> 
                       <tr>
                        <td id="subtitulo">
                         RELACION FACTURAS - ORDEN DE COMPRA
                       </td>
                     </tr> 
                 </table> 
               </div>
             </div>
             <br> 
          <br>
       
          <div class="container-fluid">  
           <form action="facturas_update.php"  name="form1" enctype="multipart/form-data" method="post"> 
            <table class="table table-striped">
              <thead>
                <tr>
                  <th scope="col">CARGUE LOS ID'S DE ORDEN DE COMPRA - FACTURA </th> 
                </tr>
              </thead>
              <tbody>
                <td id="detalle2"><input class="botonMini" id="archivo" accept=".csv" name="archivo" type="file" />
                  <input name="MAX_FILE_SIZE" type="hidden" value="90000" /> 
                </td> 
                <tr>
                  <td><button id="btnEnviarG" name="btnEnviarG" type="button" class="botonGeneral" autofocus="" >GUARDAR Y CONTINUAR</button> </td>
                </tr>
              </tbody>
            </table> 
            <input type="hidden" id="MM_update" name="MM_update" value="form1">
            </form>
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

 <script type="text/javascript">
   $( "#btnEnviarG" ).on( "click", function() {

     if($("#archivo").val()==''){ 
       swal("Error", "Debe cargar un archivo! :)", "error"); 
       return false; 
     }else{
        swal({   
         title: "ACTUALIZAR?",   
         text: "Esta seguro que Quiere Actualizar " ,   
         type: "warning",   
         showCancelButton: true,   
         confirmButtonColor: "#DD6B55",   
         confirmButtonText: "Si, Actualizar!",   
         cancelButtonText: "No, Actualizar!",   
         closeOnConfirm: false,   
         closeOnCancel: false }, 
         function(isConfirm){   
           if (isConfirm) {  
             document.form1.submit(); 
             swal("Actualizado!", "Los registros se han Actualizado.", "success"); 
              window.opener.location.reload();
             setTimeout(function() 
                 { 
                   window.close();
                 },2000); 
             //updatenumFactura(id,campo,pagina);//este actualiza en base
             return true;
              
           } else {     
             swal("Cancelado", "has cancelado :)", "error"); 
              return false;
           } 
         }); 
        
        //updateGeneral(archivo,'facturas','updatenumFactura',"facturas_update.php");
         // document.form1.submit();
         //form1.submit();
      //return true;
     } 
    
     });

 </script>
</body>
</html>



<?php
mysql_free_result($usuario);

?>
