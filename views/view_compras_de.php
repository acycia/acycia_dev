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
  <script type="text/javascript" src="AjaxControllers/js/funcionesmat.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/elimina.js"></script>
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
<form action="view_index.php?c=comprasDE&a=Guardar&columna=<?php echo $_GET['columna'];?>&id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data" name="form1" >
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
                      <?php if($_SESSION['acceso']): ?>
                      <li>
                        <!-- <a class="botonDel" href="?c=comprasDE&a=Eliminar&columna=<?php echo $_GET['columna']; ?>&master=<?php echo $_GET['id']; ?>">DELETE</a> -->
                       <a class="botonDel" id="btnDelMaster" onclick='eliminar("<?php echo $_GET['id']; ?>","<?php echo $_GET['columna']; ?>","DETALLE EMBARQUE",  "?c=comprasDE&a=Eliminar","1"  )' type="button" >DELETE</a>
                        </li>
                      <?php endif;?> 
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
                         DETALLE DE EMBARQUE
                       </td>
                     </tr> 
                 </table> 
               </div>
             </div> 
             <br>
             <!-- grids --> 
             <div class="row" >
               <div class="span12" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                <button id='botondeenvio' type="submit" onclick="submitform(); return false;"><img type="image" style="width: 30px; height: 40px;" name="botondeenvio" id="botondeenvio" src="images/save3.PNG" alt="GUARDAR"title="GUARDAR"> </button> 
               </div>
             </div><br> 
              
             <table class="table table-striped" id="items" >
               <thead>
                 <tr>
                  <tr>
                    <td>
                      <?php foreach($this->general as $general) { $general; } ?>
                      <input name="proceso" id="proceso" value="DETALLE EMBARQUE" type="hidden" > 
                      Proforma #
                      <input name="proforma" id="proforma" value="<?php echo $_GET['columna'] =='proforma' ? $_GET['id'] : $general['proforma']; ?>" type="text" placeholder="Proforma" size="20" >
                      Tipo de Embarque
                      <select name="tipopedido" id="tipopedido" class="selectsMedio busqueda" class="selectsMini" required="required" >
                          <option value="">Seleccione</option> 
                          <option value="Aereo"<?php if (!(strcmp("Aereo",$general['tipopedido']))){echo "selected=\"selected\"";} ?>>Aereo</option> 
                          <option value="Maritimo"<?php if (!(strcmp("Maritimo",$general['tipopedido']))){echo "selected=\"selected\"";} ?>>Maritimo</option>
                      </select>
                      Factura
                      <input name="factura" id="factura" value="<?php echo $_GET['columna'] =='factura' ? $_GET['id'] : $general['factura']; ?>" type="text" placeholder="Factura" size="20" > 
                      Pedido
                      <input name="pedido" id="pedido" value="<?php echo $general['pedido']; ?>" type="number" placeholder="Pedido" size="20" readonly="readonly">
                      Proveedor
                         <select name="proveedor" id="proveedor" class="selectsMMedio busqueda" required="required">
                        <option value="">Seleccione Proveedor</option>
                        <?php foreach($this->proveedores as $proveedores) {  ?>
                          <option value="<?php echo $proveedores['proveedor_p']; ?>"<?php if (!(strcmp($proveedores['proveedor_p'], $general['proveedor']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($proveedores['proveedor_p']); ?> </option>
                        <?php } ?>
                      </select> 

                   Fecha Embarque
                   <input name="fecha" id="fecha" type="date" step="1" min="2020-01-01" placeholder="Fecha" value="<?php echo $general['fecha']=='' ? date('Y-m-d') : $general['fecha']; ?>" class="campostextMedio" >
                   <!-- Bodega
                   <input name="bodega" id="bodega" class="selectsMedio" value="<?php echo htmlentities($general['bodega']);?>" type="text" class="selectsMini"> --><br>
                   
                   Tipo Insumo
                   <input name="tipoinsumo" id="tipoinsumo" class="selectsMedio" value="<?php echo htmlentities($general['tipoinsumo']); ?>" type="text" readonly="readonly" class="selectsMini">&nbsp;
                   <span id="maquinas" style="display: none;" > 
                   Cual Maquina ?
                   <input name="maquina" id="maquina" class="selectsMMedio" value="<?php echo htmlentities($general['maquina']); ?>" type="text" readonly="readonly" > 
                   </span>&nbsp; 

                   Plazo
                    <input name="plazo" id="plazo" class="selectsMini" class="selectsMini" value="<?php echo htmlentities($general['plazo']); ?>" type="text" readonly="readonly"> 
                   <span id="plazodias" style="display: none;" > 
                   Dias / Valor
                       <input name="valorplazo" id="valorplazo" placeholder="Dias O Valor" value="<?php echo $general['valorplazo']; ?>" type="text" class="campostextMini" readonly="readonly">    
                   </span>
                   Fecha Pago Estimada 
                   <input name="fecha_plazo" id="fecha_plazo" type="text" placeholder="Fecha Plazo" value="<?php echo $general['fecha_plazo']; ?>" readonly="readonly" class="campostextMini" >
                   Quien Ingresa
                   <input name="usuario" id="usuario" type="text" placeholder="Usuario" value="<?php echo $_SESSION['Usuario']; ?>" readonly="readonly" class="campostextMedio" >
                   <?php foreach($this->proformas as $proforma) { $proforma;} ?>
                    Fecha Proforma: <?php echo $proforma['fecha']; ?> 
                    </td> 
                  <tr>
                    <td>
                      Nota:<em>cada vez que quiera filtrar se debe limpiar los campos de proforma y factura</em><br>
                      <em style="display: none;  align-items: center; justify-content: center;color: red; " id="mensaje" ></em> </td>
                  </tr>
                  <tr>
                    <td>
                      # BL
                      <input name="bl" id="bl" value="<?php echo $general['bl']; ?>" type="text" placeholder="BL" size="20" >
                      Fecha BL
                     <input name="fecha_bl" id="fecha_bl" type="date" step="1" min="2020-01-01" placeholder="Fecha bl" value="<?php echo $general['fecha_bl']=='' ? date('Y-m-d') : $general['fecha_bl']; ?>" class="campostextMedio" >
                      Fecha ZARPE
                     <input name="fecha_zar" id="fecha_zar" type="date" step="1" min="2020-01-01" placeholder="Fecha zarpe" value="<?php echo $general['fecha_zar']=='' ? date('Y-m-d') : $general['fecha_zar']; ?>" class="campostextMedio" >
                      Fecha ETA
                     <input name="fecha_eta" id="fecha_eta" type="date" step="1" min="2020-01-01" placeholder="Fecha eta" value="<?php echo $general['fecha_eta']=='' ? date('Y-m-d') : $general['fecha_eta']; ?>" class="campostextMedio" >
                      <span id="maritoaeropuerto" >PUERTO LLEGADA</span>
                     <input name="puerto_lleg" id="puerto_lleg" value="<?php echo $general['puerto_lleg']; ?>" type="text" size="30" >
                      # Contenedor
                     <input name="num_contenedor" id="num_contenedor" value="<?php echo $general['num_contenedor']; ?>" type="text" size="20" >
                     Tamaño Contenedor
                     <input name="tam_contenedor" id="tam_contenedor" value="<?php echo $general['tam_contenedor']; ?>" type="text" size="20" >
                    </td>
                  </tr>
                  </tr>
                   <th scope="col">ITEMS</th>
                   <th scope="col"><!-- <input class="botonGMini" type="button" onClick="crear(this)"value="+ ADD +" > --></th>
                 </tr>
               </thead>
               <tbody >
                <tr>
                 <th><!--   &nbsp;  &nbsp;RESPONSABLE  &nbsp;  &nbsp;&nbsp;DIRECCION  &nbsp;  &nbsp;&nbsp;INDICATIVO  &nbsp;  &nbsp;&nbsp;TELEFONO  &nbsp;  &nbsp;&nbsp;EXTENSION  &nbsp;  &nbsp;&nbsp;CIUDAD -->
                 </th>
                </tr>
                 <tr>
                  <td nowrap>

                   <div class="row celdaborde1">  
                         <div style="width: 100px;" ><strong></strong></div>
                         <div style="width: 150px;" ><strong>QTY</strong></div>
                         <div style="width: 100px;" ><strong>MEDIDA</strong></div>
                         <div style="width: 300px;" ><strong>CODE</strong></div> 
                         <div style="width: 300px;" ><strong>DESCRIPCION</strong></div> 
                         <div style="width: 150px;" ><strong>MONEDA </strong></div>
                         <div style="width: 150px;" ><strong>PRECIO</strong></div> 
                         <div style="width: 150px;" ><strong>PRECIO TOTAL</strong></div>
                         <div style="width: 100px;" ><strong>ICOTERM</strong></div> 
                         <!-- <div style="width: 100px;" ><strong>VALOR</strong></div> -->
                         <div style="width: 100px;" ><strong>BODEGA</strong></div> 
                        <!--  <div class="col-lg-1" ><strong>VER</strong></div>     --> 
                      </div> 
                   <div class="row celdaborde1">
                     <div class="col-lg-12" id="fondo_2">
                    <?php $cont=0; foreach($this->proformasPrincipal as $dato) { $cont++; ?>
                       <input type="checkbox" id="items[]" name="items[]" value="<?php echo $dato['id']; ?>" disabled="disabled" ></label> 
                      <input type="hidden" name="idi[]" id="idi[]" value="<?php echo $dato['id']; ?>" > 
                      <input name="cantidad[]" id="cantidad" class="cantidad<?php echo $cont;?>" value="<?php echo $dato['cantidad']; ?>" type="text" placeholder="QTY" size="20" required="required" onchange='actualizacion(<?php echo $dato['id']; ?>,this,this,"DETALLE EMBARQUE","?c=comprasFA&a=Actualizar")'>&nbsp;&nbsp;
                      <select name="medida[]" id="medida" class="selectsMini" required="required" onchange='actualizacion(<?php echo $dato['id']; ?>,this,this,"DETALLE EMBARQUE","?c=comprasFA&a=Actualizar")'>
                        <option value="">Seleccione</option>
                        <option value="KILOS"<?php if (!(strcmp("KILOS",$dato['medida']))){echo "selected=\"selected\"";} ?>>KILOS</option>              
                        <option value="METROS"<?php if (!(strcmp("METROS",$dato['medida']))){echo "selected=\"selected\"";} ?>>METROS</option>
                        <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$dato['medida']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
                        <option value="ROLLO"<?php if (!(strcmp("ROLLO",$dato['medida']))){echo "selected=\"selected\"";} ?>>ROLLO</option>
                      </select> &nbsp;&nbsp;
                      <input name="code[]" id="code" value="<?php echo $dato['code']; ?>" type="text" placeholder="CODE" size="21" required="required" onchange='actualizacion(<?php echo $dato['id']; ?>,this,this,"DETALLE EMBARQUE","?c=comprasFA&a=Actualizar")'>&nbsp;&nbsp;
                      <select name="descripcion[]" id="descripcion" class="selectsGrande busqueda" required="required" onchange='actualizacion(<?php echo $dato['id']; ?>,this,this,"DETALLE EMBARQUE","?c=comprasFA&a=Actualizar")'>
                        <option value="">DESCRIPCION INSUMO</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"<?php if (!(strcmp($insumo['descripcion_insumo'], $dato['descripcion']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <select name="moneda[]" id="moneda" class="selectsMini" required="required" onchange='actualizacion(<?php echo $dato['id']; ?>,this,this,"DETALLE EMBARQUE","?c=comprasFA&a=Actualizar")'>
                        <option value="">Seleccione</option>
                        <option value="USD$"<?php if (!(strcmp("USD$",$dato['moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>              
                        <option value="COL$"<?php if (!(strcmp("COL$",$dato['moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€",$dato['moneda']))){echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£",$dato['moneda']))){echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select> &nbsp;&nbsp;
                      <input name="precio[]" id="precio" class="precio<?php echo $cont;?>" value="<?php echo $dato['precio']; ?>" type="text" placeholder="Price Each" size="20" required="required" onchange='actualizacion(<?php echo $dato['id']; ?>,this,this,"DETALLE EMBARQUE","?c=comprasFA&a=Actualizar")'>&nbsp;&nbsp;
                      <input name="precio_total[]" id="precio_total" class="precio_total<?php echo $cont;?>" value="<?php echo $dato['precio_total']; ?>"  type="text" placeholder="Line Total" size="20" required="required" onblur='actualizacion(<?php echo $dato['id']; ?>,this,this,"DETALLE EMBARQUE","?c=comprasFA&a=Actualizar")'>&nbsp;&nbsp; 
                      <select name="incoterm[]" id="incoterm" class="selectsMini">
                        <option value="">Seleccione</option>
                        <option value="EXW"<?php if (!(strcmp("EXW",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP",$dato['incoterm']))){echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <input name="valoricot[]" id="valoricot" value="<?php echo $dato['valoricot']; ?>" type="hidden" placeholder="VALOR" size="20"> 
                      <select name="bodega[]" id="bodega" class="selectsMini"  onchange='actualizacion(<?php echo $dato['id']; ?>,this,this,"DETALLE EMBARQUE","?c=comprasFA&a=Actualizar")'>
                        <option value="">Seleccione</option>
                         <option value="MED PRINCIPAL"<?php if (!(strcmp("MED PRINCIPAL",$dato['bodega']))){echo "selected=\"selected\"";} ?>>MED PRINCIPAL</option>
                         <option value="SERVIENTREGA"<?php if (!(strcmp("SERVIENTREGA",$dato['bodega']))){echo "selected=\"selected\"";} ?>>SERVIENTREGA</option>
                      </select><?php if($dato['id']  && $_SESSION['acceso']){  ?> 

                         <?php //echo json_encode($dato);?>
                        <!-- <a class="botonUpdate" id="btnDelItems" onclick='actualizar("<?php echo $dato['id']; ?>","<?php echo $_GET['columna']; ?>","DETALLE EMBARQUE",  "?c=comprasEM&a=Actualizar",$("#form1").serialize() )' type="button" >UPDATE</a> -->
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <a class="botonDel" id="btnDelItems" onclick='eliminar("<?php echo $dato['id']; ?>","<?php echo $_GET['columna']; ?>","DETALLE EMBARQUE",  "?c=comprasEM&a=Eliminar","0" )' type="button" >DELETE</a>
                       <?php  } ?> 
                       &nbsp;&nbsp;<br>  
                     <?php  } ?>  
                    </div>
                  </div>
                  <br> 
                    </div>
                    <input type="hidden" name="userfilegen" id="userfilegen" value="<?php echo $general['adjunto']; ?>" /> 
                    Adjuntar 1: <input class="botonGMini" type="file" name="adjunto" id="adjunto" size="100"/>
                    Adjuntar 2: <input class="botonGMini" type="file" name="adjunto2" id="adjunto2" size="100"/>
                    Adjuntar 3: <input class="botonGMini" type="file" name="adjunto3" id="adjunto3" size="100"/>
                    Adjuntar 4: <input class="botonGMini" type="file" name="adjunto4" id="adjunto4" size="100"/>
                    Adjuntar 5: <input class="botonGMini" type="file" name="adjunto5" id="adjunto5" size="100"/><br>
                    <?php 
                    $porciones = array();
                    $porciones = explode(",", $general['adjunto']);
                    $count = 0;
                    ?>
                    <?php if(isset($_GET['id']) && $_GET['id'] !='' && $general['adjunto'] != ''): ?>
                     <?php foreach ($porciones as $key => $value) { ?>
                      <?php $count++;?>
                      <?php if($value!=''):?> 
                        <input name="userfile<?php echo $count;?>" type="hidden" id="userfile<?php echo $count;?>" value="<?php echo $value; ?>"/> 
                        <a href="javascript:verFoto('pdfprocesocompras/<?php echo $value;?>','800','600')">Ver Archivo <?php echo $count;?></a>&nbsp;&nbsp;&nbsp; 
                      <?php endif; ?>
                    <?php } ?> 
                  <?php endif; ?>
                  </td>
                 </tr>   
                <tr> 
                   <td> 
                    <input name="estado" id="estado" readonly="readonly" type="hidden" value="EN TRANSITO" >
                  </td>
                </tr>
               </tbody>
             </table>
 
            <?php if($this->detalle) : ?>
             <hr><br>
               <div style="text-align: left"><strong> <em scope="col">ITEMS DETALLE DE EMBARQUE</em></strong></div><br>
                <div class="row align-items-start">  
                   <div class="col-lg-1" ><strong>PROFORMA #</strong></div> 
                   <div class="col-lg-1" ><strong>PEDIDO</strong></div>
                   <div class="col-lg-1" ><strong>FACTURA</strong></div> 
                   <div class="col-lg-1" ><strong>CANTIDAD</strong></div> 
                   <div class="col-lg-1" ><strong>CODE </strong></div>
                   <div class="col-lg-2" ><strong>DESCRIPCION</strong></div> 
                   <div class="col-lg-1" ><strong>MONEDA</strong></div>
                   <div class="col-lg-1" ><strong>PRECIO</strong></div>
                   <div class="col-lg-1" ><strong>PRECIO TOTAL</strong></div> 
                </div> 
                 <?php foreach($this->detalle as $detalle) { ?>
                <div class="row celdaborde1">
                  <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $detalle['proforma']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2"> 
                   <p><?php echo $detalle['pedido']; ?></p>
                 </div> 
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $detalle['factura']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $detalle["cantidad"]; ?></p>
                 </div> 
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $detalle["code"]; ?></p>
                 </div>
                 <div class="col-lg-2" id="fondo_2">
                   <p><?php echo $detalle["descripcion"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $detalle["moneda"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $detalle["precio"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $detalle["precio_total"]; ?></p> 
                 </div> 
               </div>
                <?php  } ?>

              <?php else: ?>

               <div style="text-align: left"><strong> <em scope="col">ITEMS DE FACTURA</em></strong></div><br>
                <div class="row align-items-start">  
                   <div class="col-lg-1" ><strong>PROFORMA #</strong></div> 
                   <div class="col-lg-1" ><strong>PEDIDO</strong></div>
                   <div class="col-lg-1" ><strong>FACTURA</strong></div> 
                   <div class="col-lg-1" ><strong>CANTIDAD</strong></div> 
                   <div class="col-lg-1" ><strong>CODE </strong></div>
                   <div class="col-lg-2" ><strong>DESCRIPCION</strong></div> 
                   <div class="col-lg-1" ><strong>MONEDA</strong></div>
                   <div class="col-lg-1" ><strong>PRECIO</strong></div>
                   <div class="col-lg-1" ><strong>PRECIO TOTAL</strong></div> 
                </div> 
                 <?php foreach($this->factura as $factura) { ?>
                <div class="row celdaborde1">
                  <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $factura['proforma']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2"> 
                   <p><?php echo $factura['pedido']; ?></p>
                 </div> 
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $factura['factura']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $factura["cantidad"]; ?></p>
                 </div> 
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $factura["code"]; ?></p>
                 </div>
                 <div class="col-lg-2" id="fondo_2">
                   <p><?php echo $factura["descripcion"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $factura["moneda"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $factura["precio"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $factura["precio_total"]; ?></p> 
                 </div> 
               </div>
                <?php  } ?>
              <hr><br>
              
              <?php endif; ?>

                <!--   <hr><br>
                  <div style="text-align: left"><strong> <em scope="col">ITEMS DE MERCANCIA</em></strong></div><br>
                   <div class="row align-items-start">  
                      <div class="col-lg-1" ><strong>PROFORMA #</strong></div> 
                      <div class="col-lg-1" ><strong>PEDIDO</strong></div>
                      <div class="col-lg-1" ><strong>FACTURA</strong></div> 
                      <div class="col-lg-1" ><strong>CANTIDAD</strong></div> 
                      <div class="col-lg-1" ><strong>CODE </strong></div>
                      <div class="col-lg-2" ><strong>DESCRIPCION</strong></div> 
                      <div class="col-lg-1" ><strong>MONEDA</strong></div>
                      <div class="col-lg-1" ><strong>PRECIO</strong></div>
                      <div class="col-lg-1" ><strong>PRECIO TOTAL</strong></div> 
                   </div> 
                    <?php foreach($this->mercancia as $mercancia) { ?>
                   <div class="row celdaborde1">
                     <div class="col-lg-1" id="fondo_2">
                      <p><?php echo $mercancia['proforma']; ?></p>
                    </div>
                    <div class="col-lg-1" id="fondo_2"> 
                      <p><?php echo $mercancia['pedido']; ?></p>
                    </div> 
                    <div class="col-lg-1" id="fondo_2">
                      <p><?php echo $mercancia['factura']; ?></p>
                    </div>
                    <div class="col-lg-1" id="fondo_2">
                      <p><?php echo $mercancia["cantidad"]; ?></p>
                    </div> 
                    <div class="col-lg-1" id="fondo_2">
                      <p><?php echo $mercancia["code"]; ?></p>
                    </div>
                    <div class="col-lg-2" id="fondo_2">
                      <p><?php echo $mercancia["descripcion"]; ?></p>
                    </div>
                    <div class="col-lg-1" id="fondo_2">
                      <p><?php echo $mercancia["moneda"]; ?></p>
                    </div>
                    <div class="col-lg-1" id="fondo_2">
                      <p><?php echo $mercancia["precio"]; ?></p>
                    </div>
                    <div class="col-lg-1" id="fondo_2">
                      <p><?php echo $mercancia["precio_total"]; ?></p> 
                    </div> 
                  </div>
                   <?php  } ?> 
                <hr><br>
               <div style="text-align: left"><strong> <em scope="col">ITEMS DE PROFORMA</em></strong></div><br>
                <div class="row align-items-start">  
                   <div class="col-lg-1" ><strong>PROFORMA #</strong></div> 
                   <div class="col-lg-1" ><strong>PEDIDO</strong></div>
                   <div class="col-lg-1" ><strong>FACTURA</strong></div> 
                   <div class="col-lg-1" ><strong>CANTIDAD</strong></div> 
                   <div class="col-lg-1" ><strong>CODE </strong></div>
                   <div class="col-lg-2" ><strong>DESCRIPCION</strong></div> 
                   <div class="col-lg-1" ><strong>MONEDA</strong></div>
                   <div class="col-lg-1" ><strong>PRECIO</strong></div>
                   <div class="col-lg-1" ><strong>PRECIO TOTAL</strong></div> 
                </div> 
                 <?php foreach($this->proformas as $dato) { ?>
                <div class="row celdaborde1">
                  <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $dato['proforma']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2"> 
                   <p><?php echo $dato['pedido']; ?></p>
                 </div> 
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $dato['factura']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $dato["cantidad"]; ?></p>
                 </div> 
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $dato["code"]; ?></p>
                 </div>
                 <div class="col-lg-2" id="fondo_2">
                   <p><?php echo $dato["descripcion"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $dato["moneda"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $dato["precio"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $dato["precio_total"]; ?></p> 
                 </div> 
               </div>
                <?php  } ?>
              
 -->
             <br><br><br>
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

   $( ".cantidad1" ).on( "change", function() {
       total1 = parseFloat($(".cantidad1").val() * $(".precio1").val());
       $(".precio_total1").val(total1.toFixed(2));
   });
   $( ".cantidad2" ).on( "change", function() {
       total2 = parseFloat($(".cantidad2").val() * $(".precio2").val());
       $(".precio_total2").val(total2.toFixed(2));
   });
   $( ".cantidad3" ).on( "change", function() {
       total3 = parseFloat($(".cantidad3").val() * $(".precio3").val());
       $(".precio_total3").val(total3.toFixed(2));
   });
   $( ".cantidad4" ).on( "change", function() {
       total4 = parseFloat($(".cantidad4").val() * $(".precio4").val());
       $(".precio_total4").val(total4.toFixed(2));
   });
   $( ".cantidad5" ).on( "change", function() {
       total5 = parseFloat($(".cantidad5").val() * $(".precio5").val());
       $(".precio_total5").val(total5.toFixed(2));
   });
   $( ".cantidad6" ).on( "change", function() {
       total6 = parseFloat($(".cantidad6").val() * $(".precio6").val());
       $(".precio_total6").val(total6.toFixed(2));
   });
   $( ".cantidad7" ).on( "change", function() {
       total7 = parseFloat($(".cantidad7").val() * $(".precio7").val());
       $(".precio_total7").val(total7.toFixed(2));
   });

   $( ".precio1" ).on( "change", function() {
       total1 = parseFloat($(".cantidad1").val() * $(".precio1").val());
       $(".precio_total1").val(total1.toFixed(2));
   });
   $( ".precio2" ).on( "change", function() {
       total2 = parseFloat($(".cantidad2").val() * $(".precio2").val());
       $(".precio_total2").val(total2.toFixed(2));
   });
   $( ".precio3" ).on( "change", function() {
       total3 = parseFloat($(".cantidad3").val() * $(".precio3").val());
       $(".precio_total3").val(total3.toFixed(2));
   });
   $( ".precio4" ).on( "change", function() {
       total4 = parseFloat($(".cantidad4").val() * $(".precio4").val());
       $(".precio_total4").val(total4.toFixed(2));
   });
   $( ".precio5" ).on( "change", function() {
       total5 = parseFloat($(".cantidad5").val() * $(".precio5").val());
       $(".precio_total5").val(total5.toFixed(2));
   });
   $( ".precio6" ).on( "change", function() {
       total6 = parseFloat($(".cantidad6").val() * $(".precio6").val());
       $(".precio_total6").val(total6.toFixed(2));
   });
   $( ".precio7" ).on( "change", function() {
       total7 = parseFloat($(".cantidad7").val() * $(".precio7").val());
       $(".precio_total7").val(total7.toFixed(2));
   });
   
 $( "#proforma" ).on( "change", function() {
       idproforma = $( "#proforma" ).val();   

       window.location="view_index.php?c=comprasDE&a=Crud&columna=proforma&id="+idproforma;
       $('#mensaje').hide(); 
       if(idproforma){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando Proforma... !');  
       }
  });

 $( "#factura" ).on( "change", function() {  
       idfactura = $( "#factura" ).val();  
       idproforma = $( "#proforma" ).val(); 
       if(idproforma==''){
           window.location="view_index.php?c=comprasDE&a=Crud&columna=factura&id="+idfactura;
           $('#mensaje').hide(); 
           if(idfactura){ 
             $('#mensaje').show(); 
             $("#mensaje").text('Buscando Factura... !');  
           }
       }/*else{
             $( "#proforma" ).attr("readonly","readonly");
           }*/
 }); 
 $(document).ready(function() {  

    $( "#tipopedido" ).on( "change", function() {
          if($( "#tipopedido" ).val()=='Aereo'){
             $("#maritoaeropuerto").text('AEREOLINEA');
          }else{
             $("#maritoaeropuerto").text('PUERTO LLEGADA'); 
          }
          
    });
  
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
        }


  });  
 

  
</script>
 