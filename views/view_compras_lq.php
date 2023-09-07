<?php
   //require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   //require (ROOT_BBDD); 
?> 
<?php 
//$conexion = new ApptivaDB();

//$proveedores = $conexion->llenaSelect('proveedor','','ORDER BY proveedor_p ASC');
include('funciones/funciones_php.php'); 


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
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
  <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/funcionesmat.js"></script> 
  <!-- <script src="https://code.jquery.com/jquery-3.2.1.js"></script> -->
  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
  </script><!-- view_index.php?c=comprasLQ&a=Guardar&columna=<?php echo $_GET['columna'];?>&id=<?php echo $_GET['id'];?> -->
<form action="view_index.php?c=comprasLQ&a=Guardar&columna=<?php echo $_GET['columna'];?>&id=<?php echo $_GET['id'];?>" method="POST" enctype="multipart/form-data" name="form1" >
  <div class="spiffy_content"> <!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div align="center">
      <table style="width: 100%" id="tabla2">
        <tr>
         <td align="center">
           <div class="row-fluid">
             <div class="span8 offset2"> <!--span8 offset2   esto da el tamaño pequeño -->
               <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color gris-->
                 <div class="row" >
                   <div class="span12">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/cabecera.jpg"></div>
                   <div class="span12"><h3> PROCESO DE COMPRAS  &nbsp;&nbsp;&nbsp; </h3></div>
                 </div>
                 <div class="panel-heading" align="left" ></div><!--color gris-->
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
                         LIQUIDACUÍON DE IMPORTACIONES ALBERTO CADAVID Y COMPAÑIA
                       </td>
                     </tr> 
                 </table> 
               </div>
             </div> 
             <br>
      
         <!-- ENCABEZADO DE CONSULTAS -->
            <div style="text-align: left;" >
              <div class="row" >
                    <div class="form-group col-lg-4">
                     <?php foreach($this->general as $general) { $general; } ?> 
                     <?php foreach($this->items as $items) { $items;}?>

                     <label for="category">Proveedor</label>
                     <select name="category" id="category" class="busqueda"style="width: 300px;" ><!-- selectsMMedio -->
                       <option value="">Seleccione Proveedor</option>
                       <?php foreach($this->proveedores as $proveedores) {  ?>
                        <option value="<?php echo $proveedores['proveedor_p']; ?>"<?php if (!(strcmp($proveedores['proveedor_p'], $general['proveedor']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($proveedores['proveedor_p']); ?> </option>
                      <?php } ?>
                    </select>
                  </div>

                   <div class="form-group col-lg-4" id="Divsubcategory" > 
                       <label for="subcategory">Facturas</label>
                       <select name="subcategory" id="subcategory" class="busqueda" style="width: 300px;" ></select> 
                  </div> 

                   <div class="form-group col-lg-4">
                       <label for="fecha">Fecha</label> 
                       <input name="fecha" id="fecha" placeholder="" value="<?php echo quitarDia($general['fecha'])  =='' ? $_GET['fecha'] : quitarDia($general['fecha']) ; ?>" type="month" class="negro_inteso " >
                   </div> 
                    
                    <!-- este es por si quiero un select dependiente mas -->
                   <!-- <div class="form-group col-lg-6" id="Divsubsubcategory"> 
                       <label for="subsubcategory">Pedido / O.C</label>
                       <select name="subsubcategory" id="subsubcategory" class="form-control"></select> 
                   </div> --> 
                   <input name="factura" id="factura" value="<?php echo $_GET['columna']=='factura' ? $_GET['id'] : $items['factura']; ?>" type="hidden" placeholder="Factura" > 
                   <input name="pedido" id="pedido" value="<?php echo $_GET['columna']=='pedido' ? $_GET['id'] : $items['pedido'] ; ?>" type="hidden" placeholder="Pedido"  >&nbsp;&nbsp; <em style="color: red;" > *Busque por Proveedor</em>
                  
              </div>
            <!-- FINENCABEZADO DE CONSULTAS -->
              <hr>



             <table border="1" > <!-- class="table table-bordered" -->
                 <thead>
                     <tr>
                         <td nowrap="nowrap" colspan="12"> 
                           
                        </td>  
                     </tr>
                 </thead>

                 <tbody>
                 <tr>
                   <th nowrap="nowrap" class="grisOscuro"></th> 
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                   <th colspan="2" nowrap="nowrap" class="grisOscuro">ITEMS # <?php echo $cont;?></th> 
                  <?php  } ?> 
                 </tr>

                  <tr><td nowrap="nowrap" class="grisOscuro">OC AC&CIA SA.</td> 
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                      <td colspan="2" class="gris">
                        <input name="oc_acycia_sa<?php echo $cont;?>" id="oc_acycia_sa<?php echo $cont;?>" class="oc_acycia_sa<?php echo $cont;?> form-control negro_inteso " placeholder="OC" value="<?php echo $general['oc_acycia_sa'.$cont] =='' ? $items['pedido'] : $general['oc_acycia_sa'.$cont]; ?>" type="text" ></td> 
                    <?php  } ?>
                  </tr>


                  <tr>
                    <td nowrap="nowrap" class="grisOscuro">PROVEEDOR</td> 
                    <?php $cont=0; foreach($this->general as $items) { $cont++; ?> 
                      <td colspan="2" class="gris">
                        <input name="proveedor<?php echo $cont;?>" id="proveedor<?php echo $cont;?>" placeholder="" value="<?php echo $general['proveedor'] =='' ? $items['descripcion'] : $general['proveedor'] ; ?>" type="hidden" class="proveedor<?php echo $cont;?> form-control negro_inteso ">
                      <?php echo $general['proveedor'.$cont] =='' ? $items['proveedor'] : $general['proveedor'.$cont]; ?></td> 
                      </td>
                      <?php  } ?> 
                  </tr>
                  

                  <tr><td nowrap="nowrap" class="grisOscuro">PROFORMA NO.</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?>  
                      <td colspan="2" class="gris">
                        <input name="proforma<?php echo $cont;?>" id="proforma<?php echo $cont;?>" placeholder="*Proforma" value="<?php echo $general['proforma'.$cont]=='' ? $items['proforma'] : $general['proforma'.$cont]; ?>" type="text" class="proforma<?php echo $cont;?> form-control negro_inteso ">
                      </td> 
                    <?php  } ?> 
                  </tr>

                  
                  <tr><td nowrap="nowrap" class="grisOscuro">REFERENCIA DE MATERIAL</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td colspan="2" class="gris">
                        <input name="material<?php echo $cont;?>" id="material<?php echo $cont;?>" placeholder="" value="<?php echo $general['material'.$cont]=='' ? $items['descripcion'] : $general['material'.$cont] ; ?>" type="text" class="material<?php echo $cont;?> form-control negro_inteso "></td> 
                    <?php  } ?>
                  </tr>


                  <tr><td nowrap="nowrap" class="grisOscuro"># FACTURA</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td colspan="2" class="gris"><input name="factura<?php echo $cont;?>" id="factura<?php echo $cont;?>" placeholder="" value="<?php echo $general['f.$cont']=='' ? $items['factura'] : $general['factura'.$cont]; ?>" type="text" class="factura<?php echo $cont;?> form-control negro_inteso "></td>
                    <?php  } ?> 
                  </tr>

 
                  <tr><td nowrap="nowrap" class="grisOscuro">FECHA FACTURA</td>
                    <?php $contfac=1; foreach($this->fechafact as $fechafact) { $contfac++;}  ?>
                      <td colspan="8" class="gris"> <!-- cambias a colspan=2 si se deja para cada items -->
                        <input name="fecha_f<?php echo $contfac;?>" id="fecha_f<?php echo $contfac;?>" placeholder="" value="<?php echo $fechafact['fecha'];?>" type="hidden" class="fecha_f<?php echo $contfac;?> form-control negro_inteso "><span style="color: black;" ><?php echo $fechafact["fecha"]; ?></span> 
                      </td>
                    <?php  ?>
                  </tr>


                  <tr>
                    <td nowrap="nowrap" class="grisOscuro">FECHA PLAZO FACTURA </td>
                     <?php $contf=1; foreach($this->fechafact as $fechafact) { $contf++;}  ?>
                       <td colspan="8" class="gris"> <!-- cambias a colspan=2 si se deja para cada items -->
                         <input name="fecha_factura<?php echo $contf;?>" id="fecha_factura<?php echo $contf;?>" placeholder="" value="<?php echo $general['fecha_factura'.$cont]=='' ? $fechafact['fecha_plazo'] : $general['fecha_factura'.$cont]; ?>" type="hidden" class="fecha_factura<?php echo $contf;?> form-control negro_inteso ">
                              <?php $date = date('Y-m-d'); ?>
                               <?php if($date > $fechafact["fecha_plazo"] ): ?>
                            <span style="color: red;" ><?php echo $fechafact["fecha_plazo"]; ?></span>
                            <?php else: ?>
                         <?php echo $fechafact["fecha_plazo"]; ?> 
                        <?php endif; ?>
                       </td>
                     <?php  ?>
                  </tr>


                  <tr><td nowrap="nowrap" class="grisOscuro">CANTIDAD A IMPORTAR</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                     <?php 
                     $cantidadMillar = $general['cantidad'.$cont]=='' ? $items['cantidad'] : $general['cantidad'.$cont];
                     $medida = $general['medida'.$cont]=='' ? $items['medida'] : $general['medida'.$cont];
                     //$cantidadMillar=conversionCantidadMedida($cantidadMillar,$medida); ?> 
                      <td class="gris"><input name="cantidad<?php echo $cont;?>" id="cantidad<?php echo $cont;?>" placeholder="" value="<?php echo $cantidadMillar; ?>" type="text" class="cantidad<?php echo $cont;?> form-control negro_inteso "></td>
                      <td class="gris"><?php  echo $medida;?> </td> 
                    <?php  } ?> 
                  </tr> 
 

                  <tr><td nowrap="nowrap" class="grisOscuro">PRECIO DE COMPRA</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td class="gris"><input name="precio_compra_unidad_usd<?php echo $cont;?>" id="precio_compra_unidad_usd<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['precio_compra_unidad_usd'.$cont]=='' ? $items['precio']: $general['precio_compra_unidad_usd'.$cont]; ?>" type="text" class="precio_compra_unidad_usd<?php echo $cont;?> form-control negro_inteso "></td> 
                      <td class="gris">KG/MT</td>
                    <?php  } ?> 
                  </tr>


                  <tr><td nowrap="nowrap" class="grisOscuro">PRECIO TOTAL PROFORMA</td>
                  <?php foreach($this->items as $items) { $preciototal += $items['precio_total']; 
                         $preciototal2= ($preciototal);  
                  ?> 
                      <td class="gris">
                        <input name="precio_proforma" id="precio_proforma" placeholder="*Precio Proforma" value="<?php echo $general['precio_proforma'.$cont]=='' ? $preciototal2 : $general['precio_proforma'.$cont]; ?>" type="text" class="precio_proforma form-control negro_inteso ">
                      </td>
                      <td class="selectsMedio ">USD</td>
                      <?php  } ?>   
                  </tr>


                  <tr><td nowrap="nowrap" >TRM</td>
                    <?php foreach($this->items as $items) {$items; ?>  
                      <td><input name="trm_cl" id="trm_cl" placeholder="$ " value="<?php echo $general['trm_cl']=='' ? $general['trm'] : $general['trm_cl']; ?>" type="text" class="trm_cl form-control negro_inteso ">
                        <!-- TRM: HOY: Dolar Wilkinsonpc Ind-Eco-Basico Start -->
                        <!-- <div id="IndEcoBasico"><a href="http://dolar.wilkinsonpc.com.co/"></a></div>
                        <script type="text/javascript" src="http://dolar.wilkinsonpc.com.co/js/ind-eco-basico.js?ancho=170&alto=85&fsize=10&ffamily=sans-serif">
                        </script> --><!-- Dolar Wilkinsonpc Ind-Eco-Basico End 
                        <input name="trm" id="trm" type="text" style="width:70px" value="<?php //echo trm_dolar();?>" > -->
                      </td> 
                      <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr>
                  

                  <tr>
                      <td nowrap="nowrap">Precio unit FOB</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td class="gris"><input name="precio_unit_fob<?php echo $cont;?>" id="precio_unit_fob<?php echo $cont;?>" placeholder="$" value="<?php echo $general['precio_unit_fob'.$cont]=='' ? ($general['valoricot']) : $general['precio_unit_fob'.$cont]; ?>" type="text" class="precio_unit_fob form-control negro_inteso "></td>
                      <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr>


                  <tr>
                      <td nowrap="nowrap">Total FOB</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                     <td class="gris"><input name="total_fob<?php echo $cont;?>" id="total_fob<?php echo $cont;?>" placeholder="$" value="<?php echo $general['total_fob'.$cont]=='' ? ($items['valoricot']) : $general['total_fob'.$cont]; ?>" type="text" class="total_fob form-control negro_inteso "></td>
                      <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr>

                  
                  <tr>
                      <td nowrap="nowrap">Flete Internacional y Seguro</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td class="gris"><input name="flete_inter_seguro<?php echo $cont;?>" id="flete_inter_seguro<?php echo $cont;?>" placeholder="$" value="<?php echo $general['flete_inter_seguro'.$cont]=='' ? ($general['fleteseguro']) : $general['flete_inter_seguro'.$cont] ; ?>" type="text" class="flete_inter_seguro form-control negro_inteso "></td>
                      <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr>


                  <tr>
                      <td nowrap="nowrap" class="grisOscuro">TOTAL CIF</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td class="gris"><input name="total_cif<?php echo $cont;?>" id="total_cif<?php echo $cont;?>" placeholder="$" value="<?php echo $general['total_cif'.$cont]; ?>" type="text" class="total_cif form-control negro_inteso" ></td>
                      <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr> 


                  <tr>
                      <td nowrap="nowrap" class="grisOscuro">TOTAL COP (total cif * trm)</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td class="gris"><input name="total_cop<?php echo $cont;?>" id="total_cop<?php echo $cont;?>" placeholder="$" value="<?php echo ($general['total_cop'.$cont]); ?>" type="text" class="total_cop  form-control negro_inteso "></td>
                      <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr> 
                   
    

                  <tr>
                    <th nowrap="nowrap" class="grisOscuro">GASTOS EN ORIGEN</th> 
                    <th colspan="8" nowrap="nowrap" class="grisOscuro"> </th> 
                  </tr> 
                  <tr>
                      <td nowrap="nowrap">Recogida en el Proveedor</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td class="gris">
                        <input name="recogida_prov<?php echo $cont;?>" id="recogida_prov<?php echo $cont;?>" placeholder="$" value="<?php echo $general['recogida_prov'.$cont]; ?>" type="text" class="recogida_prov<?php echo $cont;?> gastosorigen form-control negro_inteso "> </td>
                      <td class="selectsMedio ">USD</td>
                    <?php  } ?> 
                  </tr>
                  <tr>
                      <td nowrap="nowrap">Aduana en Origen</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                      <td class="gris"><input name="aduana_origen<?php echo $cont;?>" id="aduana_origen<?php echo $cont;?>" placeholder="$" value="<?php echo $general['aduana_origen'.$cont]; ?>" type="text" class="aduana_origen<?php echo $cont;?> gastosorigen form-control negro_inteso "></td>
                      <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr>
                  <tr>
                      <td nowrap="nowrap">Otros</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td class="gris"><input name="otrosgo<?php echo $cont;?>" id="otrosgo<?php echo $cont;?>" placeholder="$" value="<?php echo $general['otrosgo'.$cont]; ?>" type="text" class="otrosgo<?php echo $cont;?> gastosorigen form-control negro_inteso "> </td>
                      <td class="form-control negro_inteso "></td>
                    <?php  } ?>
                  </tr>  



                  <tr><th nowrap="nowrap" class="grisOscuro">TRANSPORTE INTERNACIONAL</th>
                      <th colspan="8" nowrap="nowrap" class="grisOscuro"> </th> 
                  </tr> 
                  <tr>
                      <td nowrap="nowrap">Flete <?php echo $general['tipopedido']; ?> + Seguro</td>
                     <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                      <td class="gris"><input name="flete_internal_seguro<?php echo $cont;?>" id="flete_internal_seguro<?php echo $cont;?>" placeholder="" value="<?php echo $general['flete_internal_seguro'.$cont]=='' ?  ($general['fleteseguro']) : $general['flete_internal_seguro'.$cont] ; ?>" type="text" class="flete_internal_seguro<?php echo $cont;?> transinter form-control negro_inteso "></td>
                      <td class="selectsMedio ">USD</td> 
                    <?php  } ?> 
                     <tr>
                      <td nowrap="nowrap">Otros</td>
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                        <td class="gris">
                          <input name="otrosti<?php echo $cont;?>" id="otrosti<?php echo $cont;?>" placeholder="Otros <?php echo $cont;?>" value="<?php echo $general['otrosti'.$cont]; ?>" cols="30" rows="3" class="otrosti<?php echo $cont;?> transinter form-control negro_inteso "></td>
                          <td class="form-control negro_inteso "></td>
                      <?php  } ?> 
                     </tr> 
                  </tr>

                  
                  <tr><th nowrap="nowrap" class="grisOscuro">GASTOS PORTUARIOS DESTINO</th>
                      <th colspan="8" nowrap="nowrap" class="grisOscuro"> </th> 
                  </tr>
                  <tr>
                    <td nowrap="nowrap">Emision (B/L)</td> 
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td class="gris"><input name="emision_b_l<?php echo $cont;?>" id="emision_b_l<?php echo $cont;?>" placeholder="$" value="<?php echo $general['emision_b_l'.$cont]; ?>" type="text" class="emision_b_l<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr> 
 

                  <tr>
                    <td nowrap="nowrap">Liberacion de Documentos de Transporte</td> 
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td class="gris"><input name="liberacion_doc_transp<?php echo $cont;?>" id="liberacion_doc_transp<?php echo $cont;?>" placeholder="$" value="<?php echo $general['liberacion_doc_transp'.$cont]; ?>" type="text" class="liberacion_doc_transp<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr>


                  <tr><td nowrap="nowrap">Operación Portuaria</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                       <td><input name="opera_portuaria<?php echo $cont;?>" id="opera_portuaria<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['opera_portuaria'.$cont]; ?>" type="text" class="opera_portuaria<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                       <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr>


                  <tr>
                    <td nowrap="nowrap">Documentacion o Uso de Instalaciones</td> 
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td class="gris"><input name="doc_uso_instalaciones<?php echo $cont;?>" id="doc_uso_instalaciones<?php echo $cont;?>" placeholder="$" value="<?php echo $general['doc_uso_instalaciones'.$cont]; ?>" type="text" class="doc_uso_instalaciones<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr> 

                  <tr><td nowrap="nowrap">Manejo de carga</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                       <td><input name="manejo_carga<?php echo $cont;?>" id="manejo_carga<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['manejo_carga'.$cont]; ?>" type="text" class="manejo_carga<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                       <td class="selectsMedio ">USD</td>
                    <?php  } ?> 
                  </tr>


                  <tr>
                    <td nowrap="nowrap">Bodegaje o Almacenamiento</td> 
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td class="gris"><input name="bodegaje_o_almacen<?php echo $cont;?>" id="bodegaje_o_almacen<?php echo $cont;?>" placeholder="$" value="<?php echo $general['bodegaje_o_almacen'.$cont]; ?>" type="text" class="bodegaje_o_almacen<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr>


                  <tr>
                    <td nowrap="nowrap">Montacargas y/o Elevador(Bascula)</td> 
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td class="gris"><input name="montac_o_elevador<?php echo $cont;?>" id="montac_o_elevador<?php echo $cont;?>" placeholder="$" value="<?php echo $general['montac_o_elevador'.$cont]; ?>" type="text" class="montac_o_elevador<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr>


                  <tr>
                    <td nowrap="nowrap">Inspeccion - Preinspección</td> 
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td class="gris"><input name="inspeccion_preinspec<?php echo $cont;?>" id="inspeccion_preinspec<?php echo $cont;?>" placeholder="$" value="<?php echo $general['inspeccion_preinspec'.$cont]; ?>" type="text" class="inspeccion_preinspec<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr>


                  <tr>
                    <td nowrap="nowrap">Costos Financieros</td> 
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td class="gris"><input name="costos_financieros<?php echo $cont;?>" id="costos_financieros<?php echo $cont;?>" placeholder="$" value="<?php echo $general['costos_financieros'.$cont]; ?>" type="text" class="costos_financieros<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr>


                  <tr>
                    <td nowrap="nowrap">Otros</td> 
                      <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td class="gris"><input name="otrosgpd<?php echo $cont;?>" id="otrosgpd<?php echo $cont;?>" placeholder="otros <?php echo $cont;?>" value="<?php echo $general['otrosgpd'.$cont]; ?>" cols="30" rows="3" class="otrosgpd<?php echo $cont;?> gastosBL form-control negro_inteso "></td>
                        <td class="form-control negro_inteso "></td>
                      <?php  } ?> 
                  </tr>


                  <tr>
                     <th nowrap="nowrap" class="grisOscuro">GASTOS NACIONALIZACION</th> 
                     <th colspan="8" nowrap="nowrap" class="grisOscuro"> </th> 
                  </tr>
                  <tr>
                    <td nowrap="nowrap">Comision por Intermediacion Aduanera</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                         <td><input name="comision_intermedia_aduanera<?php echo $cont;?>" id="comision_intermedia_aduanera<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['comision_intermedia_aduanera'.$cont]; ?>" type="text" class="comision_intermedia_aduanera<?php echo $cont;?> GastosNal form-control negro_inteso "></td>
                         <td class="selectsMedio ">USD</td>
                      <?php  } ?>
                  </tr>

                  <tr><td nowrap="nowrap">Elaboracion Declaracion Importacion</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td><input name="elabora_declara_importa<?php echo $cont;?>" id="elabora_declara_importa<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['elabora_declara_importa'.$cont]; ?>" type="text" class="elabora_declara_importa<?php echo $cont;?> GastosNal form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?> 
                  </tr>

                  <tr><td nowrap="nowrap">Declaracion Andina de Valor</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                       <td><input name="declara_andina_valor<?php echo $cont;?>" id="declara_andina_valor<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['declara_andina_valor'.$cont]; ?>" type="text" class="declara_andina_valor<?php echo $cont;?> GastosNal form-control negro_inteso "></td>
                       <td class="selectsMedio ">USD</td>
                    <?php  } ?> 
                  </tr>

                  <tr><td nowrap="nowrap">Gastos Operativos</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td><input name="gastos_operativos<?php echo $cont;?>" id="gastos_operativos<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['gastos_operativos'.$cont]; ?>" type="text" class="gastos_operativos<?php echo $cont;?> GastosNal form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                      <?php  } ?>
                  </tr>

                  <tr><td nowrap="nowrap">Trasmision siglo XXI</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td><input name="trasmi_siglo_xxi<?php echo $cont;?>" id="trasmi_siglo_xxi<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['trasmi_siglo_xxi'.$cont]; ?>" type="text" class="trasmi_siglo_xxi<?php echo $cont;?> GastosNal form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr>

                  <tr><td nowrap="nowrap">Aranceles</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                        <td><input name="aranceles<?php echo $cont;?>" id="aranceles<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['aranceles'.$cont]; ?>" type="text" class="aranceles<?php echo $cont;?> GastosNal form-control negro_inteso "></td>
                        <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr>

                  <tr><td nowrap="nowrap">Descargue directo</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                       <td><input name="descargue_directo<?php echo $cont;?>" id="descargue_directo<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['descargue_directo'.$cont]; ?>" type="text" class="descargue_directo<?php echo $cont;?> GastosNal form-control negro_inteso "></td>
                       <td class="selectsMedio ">USD</td>
                    <?php  } ?>
                  </tr> 

                  <tr><td nowrap="nowrap">Otros</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                       <td><input name="otrosgn<?php echo $cont;?>" id="otrosgn<?php echo $cont;?>" placeholder="otros <?php echo $cont;?>" value="<?php echo $general['otrosgn'.$cont]; ?>" cols="30" rows="3" class="otrosgn<?php echo $cont;?> GastosNal form-control negro_inteso "></td>
                       <td class="form-control negro_inteso "></td>
                    <?php  } ?>
                  </tr>

                 <tr>
                        <th nowrap="nowrap" class="grisOscuro">TRANSPORTE EN DESTINO</th> 
                        <th colspan="8" nowrap="nowrap" class="grisOscuro"> </th> 
                 </tr>
                 <tr><td nowrap="nowrap">Fletes Devolucion de Contenedor</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                     <td><input name="fletes_devol_contenedor<?php echo $cont;?>" id="fletes_devol_contenedor<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['fletes_devol_contenedor'.$cont]; ?>" type="text" class="fletes_devol_contenedor<?php echo $cont;?> TransDest form-control negro_inteso "></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">Descargue Servientrega</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="descargue_servientrega<?php echo $cont;?>" id="descargue_servientrega<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['descargue_servientrega'.$cont]; ?>" type="text" class="descargue_servientrega<?php echo $cont;?> TransDest form-control negro_inteso "></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">Flete Terrestre </td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="flete_terrestre<?php echo $cont;?>" id="flete_terrestre<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['flete_terrestre'.$cont]; ?>" type="text" class="flete_terrestre<?php echo $cont;?> TransDest form-control negro_inteso "></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">ITR Puerto</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="itr_puerto<?php echo $cont;?>" id="itr_puerto<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['itr_puerto'.$cont]; ?>" type="text" class="itr_puerto<?php echo $cont;?> TransDest form-control negro_inteso "></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">Otros</td>
                    <?php $cont=0; foreach($this->items as $items) { $cont++; ?> 
                       <td><input name="otrosted<?php echo $cont;?>" id="otrosted<?php echo $cont;?>" placeholder="otros <?php echo $cont;?>" value="<?php echo $general['otrosted'.$cont]; ?>" cols="30" rows="3" class="otrosted<?php echo $cont;?> TransDest form-control negro_inteso "></td>
                       <td class="form-control negro_inteso "></td>
                    <?php  } ?>
                  </tr>
                  

                  <tr>
                      <th nowrap="nowrap" class="beige">RESUMEN DE LA OPERACIÓN</th> 
                      <th colspan="8" nowrap="nowrap" class="beige"> </th> 
                 </tr>
                 <tr><td nowrap="nowrap">VALOR FACTURA</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="valor_factura<?php echo $cont;?>" id="valor_factura<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['valor_factura'.$cont]; ?>" type="text" class="valor_factura resmOper form-control negro_inteso " ></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr> 
                 <tr><td nowrap="nowrap">Gastos En Origen</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="gastos_origen<?php echo $cont;?>" id="gastos_origen<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['gastos_origen'.$cont]; ?>" type="text" class="gastos_origen resmOper form-control negro_inteso " ></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr> 
                 <tr><td nowrap="nowrap">FLETES AEREOS / MARITIMOS</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="fletes_total<?php echo $cont;?>" id="fletes_total<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['fletes_total'.$cont]; ?>" type="text" class="fletes_total resmOper form-control negro_inteso " ></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">GASTOS PORTUARIOS</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="gastos_portuarios<?php echo $cont;?>" id="gastos_portuarios<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['gastos_portuarios'.$cont]; ?>" type="text" class="gastos_portuarios resmOper form-control negro_inteso " ></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">VALOR GASTOS NACIONALIZACION</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="valor_gastos_nacional<?php echo $cont;?>" id="valor_gastos_nacional<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['valor_gastos_nacional'.$cont]; ?>" type="text" class="valor_gastos_nacional resmOper form-control negro_inteso " ></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">VALOR TRANSPORTE INTERNO</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="valor_transp_interno<?php echo $cont;?>" id="valor_transp_interno<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['valor_transp_interno'.$cont]; ?>" type="text" class="valor_transp_interno resmOper form-control negro_inteso " ></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td class="gris" nowrap="nowrap">TOTAL</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td class="gris" ><input name="total<?php echo $cont;?>" id="total<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['total'.$cont]; ?>" type="text" class="total<?php echo $cont;?> totaliza form-control negro_inteso "  ></td>
                     <td class="selectsMedio ">USD</td>
                  <?php  } ?>
                 </tr>
                 <tr><td class="rojo_inteso" nowrap="nowrap">TOTAL PLANTA</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td class="gris" ><input name="total_planta<?php echo $cont;?>" id="total_planta<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['total_planta'.$cont]; ?>" type="text" class="total_planta<?php echo $cont;?> totalizaplanta form-control negro_inteso " ></td>
                     <td class="selectsMedio ">$</td>
                  <?php  } ?>
                 </tr>
                 

                  <tr>
                      <th nowrap="nowrap" class="grisOscuro">OTRA INFORMACIÓN</th> 
                      <th colspan="8" nowrap="nowrap" class="grisOscuro"> </th> 
                 </tr>
                 <tr><td nowrap="nowrap">Numero del BL</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="numero_bl<?php echo $cont;?>" id="numero_bl<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['numero_bl'.$cont]=='' ? $general['bl'] : $general['numero_bl'.$cont] ; ?>" type="text" class="numero_bl paratodos form-control negro_inteso " ></td>
                     <td class="form-control negro_inteso "></td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">Fecha del BL</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="fecha_bl<?php echo $cont;?>" id="fecha_bl<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['fecha_bl'.$cont]=='' ? $general['fecha_bl'] : $general['fecha_bl'.$cont] ; ?>" type="text" class="fecha_bl paratodos form-control negro_inteso " ></td>
                     <td class="form-control negro_inteso "></td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">Numero Declaracion Importacion</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="declara<?php echo $cont;?>" id="declara<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['declara'.$cont]=='' ? $general['declara'] : $general['declara'.$cont] ; ?>" type="text" class="declara paratodos form-control negro_inteso " ></td>
                     <td class="form-control negro_inteso "></td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">Fecha Declaracion Importacion</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="fecha_dec<?php echo $cont;?>" id="fecha_dec<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['fecha_dec'.$cont]=='' ? $general['fecha_dec'] : $general['fecha_dec'.$cont] ; ?>" type="text" class="fecha_dec paratodos form-control negro_inteso " ></td>
                     <td class="form-control negro_inteso "></td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">Valor Deposito</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="valor_deposito<?php echo $cont;?>" id="valor_deposito<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['valor_deposito'.$cont]=='' ? $general['valor_deposito'] : $general['valor_deposito'.$cont] ; ?>" type="text" class="valor_deposito paratodos form-control negro_inteso " ></td>
                     <td class="form-control negro_inteso "></td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap"># Contenedor</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="num_contenedor<?php echo $cont;?>" id="num_contenedor<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['num_contenedor'.$cont]=='' ? $general['num_contenedor'] : $general['num_contenedor'.$cont] ; ?>" type="text" class="num_contenedor paratodos form-control negro_inteso " ></td>
                     <td class="form-control negro_inteso "></td>
                  <?php  } ?>
                 </tr>
                 <tr><td nowrap="nowrap">Tamaño Contenedor</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td><input name="tam_contenedor<?php echo $cont;?>" id="tam_contenedor<?php echo $cont;?>" placeholder="$ " value="<?php echo $general['tam_contenedor'.$cont]=='' ? $general['tam_contenedor'] : $general['tam_contenedor'.$cont] ; ?>" type="text" class="tam_contenedor paratodos form-control negro_inteso " ></td>
                     <td class="form-control negro_inteso "></td>
                  <?php  } ?>
                 </tr> 


                 <tr><td nowrap="nowrap">NOTA</td>
                  <?php $cont=0; foreach($this->items as $items) { $cont++; ?>
                     <td colspan="2">
                        <textarea name="nota<?php echo $cont;?>" id="nota<?php echo $cont;?>" placeholder="nota <?php echo $cont;?>" cols="50" rows="3" class="form-control negro_inteso "><?php echo $general['nota'.$cont]; ?></textarea>
                   </td> 
                  <?php  } ?> 
                 </tr>

                 </tbody>
               
             </table>
            </div>  
             <br><br>


             <div class="panel-footer" > 
               <div class="row" >
                 <div class="span6" >
                  <?php foreach($this->general as $general) { $general; } ?>
                  <?php if($general):?>
                    <button id='botondeenvio' type="submit" onclick='submitform(); ' ><img type="image" style="width: 30px; height: 40px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="GUARDAR"title="GUARDAR" > </button>

                    <!-- <button id="btnGuardar" name="btnGuardar" type="button" class="botonSelladoxCaja" >GUARDAR </button><br><em style="display: none;  align-items: center; justify-content: center;color: red; " id="alertG" ></em>    -->

                  <?php endif; ?>   
                </div>
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                <div  class="span6" ><br>
                  <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $dato['id_pedido']; ?>">SALIR</a>  -->
                  <a class="botonFinalizar" style="text-decoration:none; "href="javascript:Salir('view_index.php?c=compras&a=Menu')" >SALIR</a> 
                </div>
              </div>
                  <em style="display: none;  align-items: center; justify-content: center;color: red; " id="mensaje" ></em> 
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

/*  $( "#btnGuardar" ).on( "click", function() { 
   
     if($("#pedido").val()=='' )
     {
       swal("Error", "Hay campos pedido vacios! :)", "error"); 
       return false;
     }else{  
       
        var pagina ="view_index.php?c=comprasLQ&a=Guardar&columna=<?php echo $_GET['columna'];?>&id=<?php echo $_GET['id'];?>"; 
    
        guardarGeneral(pagina);   
     } 
    
     });*/
 
 /* $( "#factura" ).on( "change", function() {
  
       idfactura = $( "#factura" ).val();   
       idpedido = $( "#pedido" ).val(); 
       if(idpedido==''&& idfactura!='') {
         window.location="view_index.php?c=comprasLQ&a=Crud&columna=factura&id="+idfactura;
         $('#mensaje').hide(); 
         if(idfactura){ 
           $('#mensaje').show(); 
           $("#mensaje").text('Buscando Factura... !');  
         }
       }
       if(idpedido==''&& idfactura=='') {
          window.location="view_index.php?c=comprasLQ&a=Liquidacion";    
       }
  });

  $( "#pedido" ).on( "change", function() {
       proveedor = $( "#proveedor" ).val(); 
       idpedido = $( "#pedido" ).val();
       idfactura = $( "#factura" ).val(); 
       if(idfactura==''&& idpedido!='') {
         window.location="view_index.php?c=comprasLQ&a=Crud&columna=pedido&id="+idpedido;
         $('#mensaje').hide(); 
         if(idpedido){ 
           $('#mensaje').show(); 
           $("#mensaje").text('Buscando Pedido... !');  
         }

       }
       if(idpedido==''&& idfactura=='') {
          window.location="view_index.php?c=comprasLQ&a=Liquidacion";    
       }
  });
*/
   


 
 $(document).ready(function() {  


  sumaGastosorigen();
  sumaTransporteIn();
  sumaPortuarios();
  sumaNacionalizacion();
  sumaTransDestino();
  //sumaTotalFob();
  sumaTotalCif();
  sumaTotalCop(); 
  //precioFob();
  PrecioUnitFOB();
  sumaTotales();
  totalPlanta();
  

/////MULTIPLICA VALOR TOTAL DE FOB


function PrecioUnitFOB(){
  for (var i=1; i<8; i++) {
     var cantidad = $("#cantidad"+i).val();
     var totalfob = $("#total_fob"+i).val(); 
     valor = (parseFloat(totalfob) / cantidad);
     if (!(isNaN(valor))){
        $( "#precio_unit_fob"+i).val(valor.toFixed(6)); 
     } 
  }
 
 
}
 

//TOTAL CIF 
function sumaTotalCif(){
  for (var i=1; i<6; i++) {
    valor = [ $( "#total_fob"+i).val(), $( "#flete_inter_seguro"+i).val() ] ;
    Gastosnal = sumar_valores(valor);
     $( "#total_cif"+i ).val(Gastosnal);
  }
}
$(".total_cif").on('keydown', function(){
   $(".total_cif").each(function(index){ 
     sumaTotalCif()
   });
 });
$(".trm_cl").on('keydown', function(){
   $(".trm_cl").each(function(index){ 
     sumaTotalCif();
     sumaTotalCop();
   });
 });
 
$(".flete_inter_seguro").on('keydown', function(){
   $(".flete_inter_seguro").each(function(index){ 
     sumaTotalCif();
     sumaTotalCop();
   });
 });
$(".precio_unit_fob").on('keydown', function(){
   $(".precio_unit_fob").each(function(index){ 
     sumaTotalCif();
     sumaTotalCop();
   });
 });


//TOTAL COP
function sumaTotalCop(){
  for (var i=1; i<6; i++) { 
    var trm = $("#trm_cl").val();
    var totalcif = $("#total_cif"+i).val(); 
    var totalCop = multiplica_valores(trm,totalcif); 
     total_cop=humanizeNumber(totalCop);
     $( "#total_cop"+i ).val(total_cop);
     $( "#valor_factura"+i ).val(totalCop);
     sumaTotales()

  }
}
/*$(".total_cop").on("blur", function(){
   $(".total_cop").each(function(index){
   sumaTotalCop(); 
   });
 });*/


/////SUMA VALOR FACTURA Y GASTOS EN ORIGEN
function sumaGastosorigen(){
    for (var i=1; i<6; i++) {
      valor = [ $( "#recogida_prov"+i).val(), $( "#aduana_origen"+i).val(), $( "#otrosgo"+i).val() ] ;
      Gastosorigen = sumar_valores(valor);
       $( "#gastos_origen"+i ).val(Gastosorigen);
    }
  }
   $(".gastosorigen").on('change',function(){
    $(".gastosorigen").each(function(index){ 
      sumaGastosorigen();
      sumaTotales();
    });
  });
    $(".gastosorigen").on('blur',function(){
     $(".gastosorigen").each(function(index){ 
       sumaGastosorigen();
       sumaTotales();
     });
   });
    $(".gastosorigen").on('keydown',function(){
     $(".gastosorigen").each(function(index){ 
       sumaGastosorigen();
       sumaTotales();
     });
   });


/////SUMA FLETES AEREOS / MARITIMOS
function sumaTransporteIn(){
  for (var i=1; i<6; i++) {
    valor = [ 0, $( "#otrosti"+i).val() ] ;//$( "#flete_internal_seguro"+i).val()
    Gastosnal = sumar_valores(valor);
     $( "#fletes_total"+i ).val(Gastosnal);
  }
}
  $(".transinter").on('change',function(){
   $(".transinter").each(function(index){ 
     sumaTransporteIn();
     sumaTotales();
   });
 });
  $(".transinter").on('blur',function(){
   $(".transinter").each(function(index){ 
     sumaTransporteIn();
     sumaTotales();
   });
 });
  $(".transinter").on('keydown',function(){
   $(".transinter").each(function(index){ 
     sumaTransporteIn();
     sumaTotales();
   });
 });


/////SUMA GASTOS PORTUARIOS
function sumaPortuarios(){
  for (var i=1; i<6; i++) {
    valor = [ $( "#emision_b_l"+i).val(), $( "#liberacion_doc_transp"+i).val(), $( "#opera_portuaria"+i).val(), $( "#doc_uso_instalaciones"+i).val(), $( "#manejo_carga"+i).val(), $( "#bodegaje_o_almacen"+i).val(), $( "#montac_o_elevador"+i).val(), $( "#inspeccion_preinspec"+i).val(), $( "#costos_financieros"+i).val(), $( "#otrosgpd"+i).val() ] ;
    Gastosnal = sumar_valores(valor);
     $( "#gastos_portuarios"+i ).val(Gastosnal);
  }
}
 $(".gastosBL").on("change",function(){
   $(".gastosBL").each(function(index){ 
     sumaPortuarios();
     sumaTotales();
   });
 });
 $(".gastosBL").on("blur",function(){
   $(".gastosBL").each(function(index){ 
     sumaPortuarios();
     sumaTotales();
   });
 });
  $(".gastosBL").on('keydown',function(){
   $(".gastosBL").each(function(index){ 
     sumaPortuarios();
     sumaTotales();
   });
 });
/////SUMA VALOR GASTOS NACIONALIZACION
function sumaNacionalizacion(){
  for (var i=1; i<6; i++) {
    valor = [ $( "#comision_intermedia_aduanera"+i).val(), $( "#elabora_declara_importa"+i).val(), $( "#declara_andina_valor"+i).val(), $( "#gastos_operativos"+i).val(), $( "#trasmi_siglo_xxi"+i).val(), $( "#aranceles"+i).val(), $( "#descargue_directo"+i).val(), $( "#otrosgn"+i).val() ] ;
    Gastosnal = sumar_valores(valor);
     $( "#valor_gastos_nacional"+i ).val(Gastosnal);
  }
}
 $(".GastosNal").on("change", function(){
   $(".GastosNal").each(function(index){ 
     sumaNacionalizacion();
     sumaTotales();
   });
 });
 $(".GastosNal").on("blur", function(){
   $(".GastosNal").each(function(index){ 
     sumaNacionalizacion();
     sumaTotales();
   });
 });
 $(".GastosNal").on("keydown", function(){
   $(".GastosNal").each(function(index){ 
     sumaNacionalizacion();
     sumaTotales();
   });
 });

/////SUMA VALOR TRANSPORTE INTERNO
function sumaTransDestino(){
  for (var i=1; i<6; i++) {
    valor = [ $( "#fletes_devol_contenedor"+i).val(), $( "#descargue_servientrega"+i).val(), $( "#flete_terrestre"+i).val(), $( "#itr_puerto"+i).val(), $( "#otrosted"+i).val() ] ;
    Gastosnal = sumar_valores(valor);
     $( "#valor_transp_interno"+i ).val(Gastosnal);
  }
}
 $(".TransDest").on("change",function(){
   $(".TransDest").each(function(index){ 
     sumaTransDestino();
     sumaTotales();
   });
 });
 $(".TransDest").on("blur",function(){
   $(".TransDest").each(function(index){ 
     sumaTransDestino();
     sumaTotales();
   });
 });
 $(".TransDest").on("keydown",function(){
   $(".TransDest").each(function(index){ 
     sumaTransDestino();
     sumaTotales();
   });
 });
/////SUMA TOTALES
 
function sumaTotales(){
     for (var i=1; i<6; i++) {
       valor = [ $( "#valor_factura"+i).val(), $( "#gastos_origen"+i).val(), $( "#fletes_total"+i).val(), $( "#gastos_portuarios"+i).val(), $( "#valor_gastos_nacional"+i).val(), $( "#valor_transp_interno"+i).val() ] ;
           Gastosnal = sumar_valores(valor);
        $( "#total"+i ).val(Gastosnal); 
      }
      totalPlanta();
}


$(".resmOper").on("change",function(){
  $(".resmOper").each(function(index){ 
    sumaTotales(); 
  });
});
$(".resmOper").on("blur",function(){
  $(".resmOper").each(function(index){ 
    sumaTotales(); 
  });
});
$(".resmOper").on("keydown",function(){
  $(".resmOper").each(function(index){ 
    sumaTotales(); 
  });
});


$(".totaliza").on("change",function(){
  $(".totaliza").each(function(index){ 
    sumaTotales(); 
  });
});
$(".totaliza").on("blur",function(){
  $(".totaliza").each(function(index){ 
    sumaTotales(); 
  });
});
$(".totaliza").on("keydown",function(){
  $(".totaliza").each(function(index){ 
    sumaTotales(); 
  });
});
 

 
 
}); 

function totalPlanta(){
  for (var i=1; i<6; i++) {
     total = $( "#total"+i).val();
     cantidad = $( "#cantidad"+i).val();
        totalplantas = dividir_valores(total,cantidad);
        totalplantas = humanizeNumber(totalplantas);
     $( "#total_planta"+i ).val(totalplantas); 
   }
  
}


$(".totalizaplanta").on('click',function(){
  $(".totalizaplanta").each(function(index){  
    totalPlanta();
  });
});
 $(".totalizaplanta").on('keydown',function(){
  $(".totalizaplanta").each(function(index){ 
    totalPlanta();
  });
});

//finaliza aqui el on carga de pagina
 

//para consultar y filtrar 
$(document).ready(function(){
    
    if( $("#subcategory").val()!=''){
       proveedor()
    }

    $("#category").on('change', function () {
         proveedor()    
      });

    $("#fecha").on('change', function () {
         proveedor();
      });

    $("#subcategory").on('change', function () {
          facturas();
      });

    //AL SELECCIONAR PARA CARGAR FACTURAS
    function proveedor(){
        $("#category option:selected").each(function () {
            var id_category = $("#category").val();
            var fechas = $("#fecha").val();  
            $.post("view_index.php?c=comprasLQ&a=Selectes&", { id_category: id_category,columna:'factura',nombrecolum:'proveedor',fecha:fechas }, function(data) { 
                $("#subcategory").html(data);
            });
            $("#fecha").val('');       
        });
    }
   
    //RECARGA PAGINA CON INFORMACION
     function facturas(){
         $("#subcategory option:selected").each(function () {
             var id_category = $("#subcategory").val();
             var subcategory = $( "#subcategory" ).val();  
             $.post("view_index.php?c=comprasLQ&a=Selectes&", { id_category: id_category,columna:'pedido',nombrecolum:'factura' }, function(data) {
               var fechas = $("#fecha").val(); 
                 window.location="view_index.php?c=comprasLQ&a=Crud&columna=factura&id="+subcategory+'&fecha='+fechas;
                 $("#subsubcategory").html(data); 
             });      
         });

     }

     
    
});
//fin para consultar y filtrar 



</script>
 