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
<form action="view_index.php?c=compras&a=Guardar" method="post" enctype="multipart/form-data" name="form1" >
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
                        <!-- <a class="botonDel" href="?c=comprasFA&a=Eliminar&columna=<?php echo $_GET['columna']; ?>&master=<?php echo $_GET['id']; ?>">DELETE</a> -->
                       <a class="botonDel" id="btnDelMaster" onclick='eliminar("<?php echo $_GET['id']; ?>","<?php echo $_GET['columna']; ?>","PROFORMA",  "?c=compras&a=Eliminar","1"  )' type="button" >DELETE</a>
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
                         CREAR - PROFORMA
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
                    <input name="proceso" id="proceso" value="PROFORMA" type="hidden" > 
                      Proforma #
                      <input name="proforma" id="proforma" value="<?php echo $_GET['columna'] =='proforma' ? $_GET['id'] : $general['proforma']; ?>" type="text" placeholder="Proforma" size="20" required="required" > 
                      Pedido AC
                      <input name="pedido" id="pedido" value="<?php echo $general['pedido']; ?>" type="number" placeholder="Pedido" size="20" >
                      Proveedor
                      <select name="proveedor" id="proveedor" class="selectsMMedio busqueda" required="required">
                     <option value="">Seleccione Proveedor</option>
                     <?php foreach($this->proveedores as $proveedores) {  ?>
                       <option value="<?php echo $proveedores['proveedor_p']; ?>"<?php if (!(strcmp($proveedores['proveedor_p'], $general['proveedor']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($proveedores['proveedor_p']); ?> </option>
                     <?php } ?>
                   </select> 
                   Fecha
                   <input name="fecha" id="fecha" type="date" step="1" min="2020-01-01" placeholder="Fecha" value="<?php echo $general['fecha'] =='' ? date('Y-m-d') : $general['fecha']; ?> " required="required" class="campostextMedio" >
                   Bodega
                   <select name="bodega" id="bodega" class="selectsMedio busqueda" required="required" class="selectsMini">
                        <option value="MED PRINCIPAL"<?php if (!(strcmp("MED PRINCIPAL",$general['bodega']))){echo "selected=\"selected\"";} ?>>MED PRINCIPAL</option>              
                        <option value="SERVIENTREGA"<?php if (!(strcmp("SERVIENTREGA",$general['bodega']))){echo "selected=\"selected\"";} ?>>SERVIENTREGA</option>
                      </select> 
                    Tipo de Pedido
                    <select name="tipopedido" id="tipopedido" class="selectsMedio busqueda" required="required" class="selectsMini">
                       <option value="">Seleccione</option> 
                       <option value="Nacional"<?php if (!(strcmp("Nacional",$general['tipopedido']))){echo "selected=\"selected\"";} ?>>Nacional</option> 
                       <option value="Importacion"<?php if (!(strcmp("Importacion",$general['tipopedido']))){echo "selected=\"selected\"";} ?>>Importacion</option><!--  
                       <option value="Exportacion"<?php if (!(strcmp("Exportacion",$general['tipopedido']))){echo "selected=\"selected\"";} ?>>Exportacion</option> -->
                   </select><br>

                    Tipo Insumo
                    <select name="tipoinsumo" id="tipoinsumo" class="selectsMedio  " required="required" class="selectsMMedio">
                       <option value="">Seleccione</option> 
                       <option value="MATERIA PRIMA"<?php if (!(strcmp("MATERIA PRIMA",$general['tipoinsumo']))){echo "selected=\"selected\"";} ?>>MATERIA PRIMA</option> 
                       <option value="INSUMO"<?php if (!(strcmp("INSUMO",$general['tipoinsumo']))){echo "selected=\"selected\"";} ?>>INSUMO</option>
                       <option value="REPUESTO"<?php if (!(strcmp("REPUESTO",$general['tipoinsumo']))){echo "selected=\"selected\"";} ?>>REPUESTO</option>
                   </select>&nbsp;
                   <span id="maquinas" style="display: none;" > 
                   Cual Maquina ?
                   <select name="maquina" id="maquina" class="selectsMMedio" >
                     <option value="">Seleccione Maquina</option>
                     <?php foreach($this->maquina as $maquina) {  ?>
                       <option value="<?php echo $maquina['nombre_maquina']; ?>"<?php if (!(strcmp($maquina['nombre_maquina'], $general['maquina']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($maquina['nombre_maquina']); ?> </option>
                     <?php } ?>
                   </select> 
                   </span>&nbsp; 

                   Plazo
                    <select name="plazo" id="plazo" class="selectsMini  " required="required" class="selectsMini">
                       <option value="">Seleccione</option> 
                       <option value="CONTADO"<?php if (!(strcmp("CONTADO",$general['plazo']))){echo "selected=\"selected\"";} ?>>CONTADO</option>
                       <option value="CREDITO"<?php if (!(strcmp("CREDITO",$general['plazo']))){echo "selected=\"selected\"";} ?>>CREDITO</option>  
                   </select>
                   <span id="plazodias" style="display: none;" > 
                   Dias / Valor
                       <input name="valorplazo" id="valorplazo" placeholder="Dias O Valor" value="<?php echo $general['valorplazo']; ?>" type="number" class="campostextMini">    
                   </span>
                   <input name="usuario" id="usuario" type="hidden" placeholder="Usuario" value="<?php echo $_SESSION['Usuario']; ?>" class="campostextMedio" >
                    </td>
                  <tr>
                    <td><em style="display: none;  align-items: center; justify-content: center;color: red; " id="mensaje" ></em> </td>
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

                   <div class="divScrollGrande" id="itemspedido" role="alert" style="text-align: left;"> 
                      <div class="row celdaborde1">  
                         <div style="width: 100px;" ><strong></strong></div>
                         <div style="width: 150px;" ><strong>QTY</strong></div>
                         <div style="width: 100px;" ><strong>MEDIDA</strong></div>
                         <div style="width: 300px;" ><strong>CODE</strong></div> 
                         <div style="width: 300px;" ><strong>DESCRIPCION</strong></div> 
                         <div style="width: 150px;" ><strong>MONEDA </strong></div>
                         <div style="width: 150px;" ><strong>PRECIO</strong></div> 
                         <div style="width: 150px;" ><strong>PRECIO TOTAL</strong></div>
                        <!--  <div style="width: 150px;" ><strong>ICOTERM</strong></div> 
                         <div style="width: 150px;" ><strong>VALOR</strong></div>  -->
                        <!--  <div class="col-lg-1" ><strong>VER</strong></div>     --> 
                      </div> 
                   <div id="dinamicos" class="row celdaborde1">
                     <div class="col-lg-12" id="fondo_2">
                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled" ></label>  
                      <input name="cantidad[]" id="cantidad[]" class="cantidad1" value="" type="text" placeholder="QTY" size="20" required="required">&nbsp;&nbsp;
                      <select name="medida[]" class="selectsMini" required="required">
                        <option value="">Seleccione</option>
                        <option value="KILOS"<?php if (!(strcmp("KILOS",$general['medida']))){echo "selected=\"selected\"";} ?>>KILOS</option>              
                        <option value="METROS"<?php if (!(strcmp("METROS",$general['medida']))){echo "selected=\"selected\"";} ?>>METROS</option>
                        <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$general['medida']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
                        <option value="ROLLO"<?php if (!(strcmp("ROLLO",$general['medida']))){echo "selected=\"selected\"";} ?>>ROLLO</option>
                      </select> &nbsp;&nbsp;
                      <input name="code[]" id="code[]" value="" type="text" placeholder="CODE" size="21" required="required">&nbsp;&nbsp;
                      <select name="descripcion[]" class="selectsGrande busqueda" required="required">
                        <option value="">DESCRIPCION INSUMO</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <select name="moneda[]" class="selectsMini busqueda" required="required">
                        <option value="">Seleccione</option>
                        <option value="USD$"<?php if (!(strcmp("USD$",$general['moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>              
                        <option value="COL$"<?php if (!(strcmp("COL$",$general['moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€",$general['moneda']))){echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£",$general['moneda']))){echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select> &nbsp;&nbsp;
                      <input name="precio[]" id="precio[]" class="precio1" value="" type="text" placeholder="Price Each" size="20" required="required">&nbsp;&nbsp;
                      <input name="precio_total[]" class="precio_total1" id="precio_total[]" value=""  type="text" placeholder="Line Total" size="20" required="required">&nbsp;&nbsp; 
                      <div style="display: none;" ><select name="incoterm[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="EXW"<?php if (!(strcmp("EXW",$general['incoterm']))){echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <input name="valoricot[]" id="valoricot[]" value="" type="text" placeholder="VALOR" size="20"></div>&nbsp;&nbsp;<br> 


                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled" ></label>  
                      <input name="cantidad[]" id="cantidad[]" class="cantidad2" value="" type="text" placeholder="QTY" size="20">&nbsp;&nbsp;
                      <select name="medida[]" class="selectsMini">
                        <option value="">Seleccione</option>
                        <option value="KILOS"<?php if (!(strcmp("KILOS",$general['medida']))){echo "selected=\"selected\"";} ?>>KILOS</option>              
                        <option value="METROS"<?php if (!(strcmp("METROS",$general['medida']))){echo "selected=\"selected\"";} ?>>METROS</option>
                        <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$general['medida']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
                        <option value="ROLLO"<?php if (!(strcmp("ROLLO",$general['medida']))){echo "selected=\"selected\"";} ?>>ROLLO</option>
                      </select> &nbsp;&nbsp;
                      <input name="code[]" id="code[]" value="" type="text" placeholder="CODE" size="21">&nbsp;&nbsp;
                      <select name="descripcion[]" class="selectsGrande busqueda">
                        <option value="">DESCRIPCION INSUMO</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <select name="moneda[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="USD$"<?php if (!(strcmp("USD$",$general['moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>              
                        <option value="COL$"<?php if (!(strcmp("COL$",$general['moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€",$general['moneda']))){echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£",$general['moneda']))){echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select> &nbsp;&nbsp;
                      <input name="precio[]" id="precio[]" class="precio2" value="" type="text" placeholder="Price Each" size="20" >&nbsp;&nbsp;
                      <input name="precio_total[]" class="precio_total2" id="precio_total[]" value="" type="text" placeholder="Line Total" size="20" >&nbsp;&nbsp; 
                      <div style="display: none;" ><select name="incoterm[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="EXW"<?php if (!(strcmp("EXW",$general['incoterm']))){echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <input name="valoricot[]" id="valoricot[]" value="" type="text" placeholder="VALOR" size="20"></div> &nbsp;&nbsp;<br> 

                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled" ></label>  
                      <input name="cantidad[]" class="cantidad3" id="cantidad[]" value="" type="text" placeholder="QTY" size="20">&nbsp;&nbsp;
                      <select name="medida[]" class="selectsMini">
                        <option value="">Seleccione</option>
                        <option value="KILOS"<?php if (!(strcmp("KILOS",$general['medida']))){echo "selected=\"selected\"";} ?>>KILOS</option>              
                        <option value="METROS"<?php if (!(strcmp("METROS",$general['medida']))){echo "selected=\"selected\"";} ?>>METROS</option>
                        <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$general['medida']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
                        <option value="ROLLO"<?php if (!(strcmp("ROLLO",$general['medida']))){echo "selected=\"selected\"";} ?>>ROLLO</option>
                      </select> &nbsp;&nbsp;
                      <input name="code[]" id="code[]" value="" type="text" placeholder="CODE" size="21">&nbsp;&nbsp;
                      <select name="descripcion[]" class="selectsGrande busqueda">
                        <option value="">DESCRIPCION INSUMO</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <select name="moneda[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="USD$"<?php if (!(strcmp("USD$",$general['moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>              
                        <option value="COL$"<?php if (!(strcmp("COL$",$general['moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€",$general['moneda']))){echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£",$general['moneda']))){echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select> &nbsp;&nbsp;
                      <input name="precio[]" class="precio3" id="precio[]" value="" type="text" placeholder="Price Each" size="20" >&nbsp;&nbsp;
                      <input name="precio_total[]" class="precio_total3" id="precio_total[]" value=""  type="text" placeholder="Line Total" size="20" >&nbsp;&nbsp;
                     <div style="display: none;" > 
                      <select name="incoterm[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="EXW"<?php if (!(strcmp("EXW",$general['incoterm']))){echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <input name="valoricot[]" id="valoricot[]" value="" type="text" placeholder="VALOR" size="20"></div> &nbsp;&nbsp;<br> 
                      
                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled" ></label>  
                      <input name="cantidad[]" class="cantidad4" id="cantidad[]" value="" type="text" placeholder="QTY" size="20">&nbsp;&nbsp;
                      <select name="medida[]" class="selectsMini">
                        <option value="">Seleccione</option>
                        <option value="KILOS"<?php if (!(strcmp("KILOS",$general['medida']))){echo "selected=\"selected\"";} ?>>KILOS</option>              
                        <option value="METROS"<?php if (!(strcmp("METROS",$general['medida']))){echo "selected=\"selected\"";} ?>>METROS</option>
                        <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$general['medida']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
                        <option value="ROLLO"<?php if (!(strcmp("ROLLO",$general['medida']))){echo "selected=\"selected\"";} ?>>ROLLO</option>
                      </select> &nbsp;&nbsp;
                      <input name="code[]" id="code[]" value="" type="text" placeholder="CODE" size="21">&nbsp;&nbsp;
                      <select name="descripcion[]" class="selectsGrande busqueda">
                        <option value="">DESCRIPCION INSUMO</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <select name="moneda[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="USD$"<?php if (!(strcmp("USD$",$general['moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>              
                        <option value="COL$"<?php if (!(strcmp("COL$",$general['moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€",$general['moneda']))){echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£",$general['moneda']))){echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select> &nbsp;&nbsp;
                      <input name="precio[]" id="precio[]" class="precio4" value="" type="text" placeholder="Price Each" size="20" >&nbsp;&nbsp;
                      <input name="precio_total[]" class="precio_total4" id="precio_total[]" value=""  type="text" placeholder="Line Total" size="20" >&nbsp;&nbsp;
                    <div style="display: none;" > 
                      <select name="incoterm[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="EXW"<?php if (!(strcmp("EXW",$general['incoterm']))){echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <input name="valoricot[]" id="valoricot[]" value="" type="text" placeholder="VALOR" size="20"></div> &nbsp;&nbsp;<br> 

                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled" ></label>  
                      <input name="cantidad[]" class="cantidad5" id="cantidad[]" value="" type="text" placeholder="QTY" size="20">&nbsp;&nbsp;
                      <select name="medida[]" class="selectsMini">
                        <option value="">Seleccione</option>
                        <option value="KILOS"<?php if (!(strcmp("KILOS",$general['medida']))){echo "selected=\"selected\"";} ?>>KILOS</option>              
                        <option value="METROS"<?php if (!(strcmp("METROS",$general['medida']))){echo "selected=\"selected\"";} ?>>METROS</option>
                        <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$general['medida']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
                        <option value="ROLLO"<?php if (!(strcmp("ROLLO",$general['medida']))){echo "selected=\"selected\"";} ?>>ROLLO</option>
                      </select> &nbsp;&nbsp;
                      <input name="code[]" id="code[]" value="" type="text" placeholder="CODE" size="21">&nbsp;&nbsp;
                      <select name="descripcion[]" class="selectsGrande busqueda">
                        <option value="">DESCRIPCION INSUMO</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <select name="moneda[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="USD$"<?php if (!(strcmp("USD$",$general['moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>              
                        <option value="COL$"<?php if (!(strcmp("COL$",$general['moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€",$general['moneda']))){echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£",$general['moneda']))){echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select> &nbsp;&nbsp;
                      <input name="precio[]" class="precio5" id="precio[]" value="" type="text" placeholder="Price Each" size="20" >&nbsp;&nbsp;
                      <input name="precio_total[]" class="precio_total5" id="precio_total[]" value="" type="text" placeholder="Line Total" size="20" >&nbsp;&nbsp; 
                     <div style="display: none;" > <select name="incoterm[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="EXW"<?php if (!(strcmp("EXW",$general['incoterm']))){echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <input name="valoricot[]" id="valoricot[]" value="" type="text" placeholder="VALOR" size="20"></div> &nbsp;&nbsp;<br> 

                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled" ></label>  
                      <input name="cantidad[]" class="cantidad6" id="cantidad[]" value="" type="text" placeholder="QTY" size="20">&nbsp;&nbsp;
                      <select name="medida[]" class="selectsMini">
                        <option value="">Seleccione</option>
                        <option value="KILOS"<?php if (!(strcmp("KILOS",$general['medida']))){echo "selected=\"selected\"";} ?>>KILOS</option>              
                        <option value="METROS"<?php if (!(strcmp("METROS",$general['medida']))){echo "selected=\"selected\"";} ?>>METROS</option>
                        <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$general['medida']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
                        <option value="ROLLO"<?php if (!(strcmp("ROLLO",$general['medida']))){echo "selected=\"selected\"";} ?>>ROLLO</option>
                      </select> &nbsp;&nbsp;
                      <input name="code[]" id="code[]" value="" type="text" placeholder="CODE" size="21">&nbsp;&nbsp;
                      <select name="descripcion[]" class="selectsGrande busqueda">
                        <option value="">DESCRIPCION INSUMO</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <select name="moneda[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="USD$"<?php if (!(strcmp("USD$",$general['moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>              
                        <option value="COL$"<?php if (!(strcmp("COL$",$general['moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€",$general['moneda']))){echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£",$general['moneda']))){echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select> &nbsp;&nbsp;
                      <input name="precio[]" id="precio[]" class="precio6" value="" type="text" placeholder="Price Each" size="20" >&nbsp;&nbsp;
                      <input name="precio_total[]" class="precio_total6" id="precio_total[]" value="" type="text" placeholder="Line Total" size="20" >&nbsp;&nbsp; 
                    <div style="display: none;" >  <select name="incoterm[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="EXW"<?php if (!(strcmp("EXW",$general['incoterm']))){echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <input name="valoricot[]" id="valoricot[]" value="" type="text" placeholder="VALOR" size="20"></div> &nbsp;&nbsp;<br> 

                      <input type="checkbox" id="items" value="first_checkbox" disabled="disabled" ></label>  
                      <input name="cantidad[]" class="cantidad7" id="cantidad[]" value="" type="text" placeholder="QTY" size="20">&nbsp;&nbsp;
                      <select name="medida[]" class="selectsMini">
                        <option value="">Seleccione</option>
                        <option value="KILOS"<?php if (!(strcmp("KILOS",$general['medida']))){echo "selected=\"selected\"";} ?>>KILOS</option>              
                        <option value="METROS"<?php if (!(strcmp("METROS",$general['medida']))){echo "selected=\"selected\"";} ?>>METROS</option>
                        <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$general['medida']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
                        <option value="ROLLO"<?php if (!(strcmp("ROLLO",$general['medida']))){echo "selected=\"selected\"";} ?>>ROLLO</option>
                      </select> &nbsp;&nbsp;
                      <input name="code[]" id="code[]" value="" type="text" placeholder="CODE" size="21">&nbsp;&nbsp;
                      <select name="descripcion[]" class="selectsGrande busqueda">
                        <option value="">DESCRIPCION INSUMO</option>
                        <?php foreach($this->insumo as $insumo) {  ?>
                          <option value="<?php echo $insumo['descripcion_insumo']; ?>"><?php echo htmlentities($insumo['descripcion_insumo']); ?> </option>
                        <?php } ?>
                      </select>&nbsp;&nbsp;
                      <select name="moneda[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="USD$"<?php if (!(strcmp("USD$",$general['moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>              
                        <option value="COL$"<?php if (!(strcmp("COL$",$general['moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
                        <option value="EUR€"<?php if (!(strcmp("EUR€",$general['moneda']))){echo "selected=\"selected\"";} ?>>EUR€</option>
                        <option value="GBP£"<?php if (!(strcmp("GBP£",$general['moneda']))){echo "selected=\"selected\"";} ?>>GBP£</option>
                      </select> &nbsp;&nbsp;
                      <input name="precio[]" id="precio[]" class="precio7" value="" type="text" placeholder="Price Each" size="20" >&nbsp;&nbsp;
                      <input name="precio_total[]" class="precio_total7" id="precio_total[]" value="" type="text" placeholder="Line Total" size="20" >&nbsp;&nbsp; 
                    <div style="display: none;" >  <select name="incoterm[]" class="selectsMini busqueda">
                        <option value="">Seleccione</option>
                        <option value="EXW"<?php if (!(strcmp("EXW",$general['incoterm']))){echo "selected=\"selected\"";} ?>>EXW</option>
                        <option value="FCA"<?php if (!(strcmp("FCA",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FCA</option>
                        <option value="FAS"<?php if (!(strcmp("FAS",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FAS</option>
                        <option value="FOB"<?php if (!(strcmp("FOB",$general['incoterm']))){echo "selected=\"selected\"";} ?>>FOB</option>
                        <option value="CFR"<?php if (!(strcmp("CFR",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CFR</option>
                        <option value="CIF"<?php if (!(strcmp("CIF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIF</option>
                        <option value="CPT"<?php if (!(strcmp("CPT",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CPT</option>
                        <option value="CIP"<?php if (!(strcmp("CIP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>CIP</option>
                        <option value="DAF"<?php if (!(strcmp("DAF",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DAF</option>
                        <option value="DES"<?php if (!(strcmp("DES",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DES</option>
                        <option value="DEQ"<?php if (!(strcmp("DEQ",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DEQ</option>
                        <option value="DDU"<?php if (!(strcmp("DDU",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDU</option>
                        <option value="DDP"<?php if (!(strcmp("DDP",$general['incoterm']))){echo "selected=\"selected\"";} ?>>DDP</option>
                      </select>
                      <input name="valoricot[]" id="valoricot[]" value="" type="text" placeholder="VALOR" size="20">
                     </div> 
                      <!-- <fieldset id="field"></fieldset> --> <!-- este muestra dinamicos -->
                    </div>
                  </div>

                    </div>
                    Adjuntar pdf: <input class="botonGMini" type="file" name="adjunto" id="adjunto" size="100"/>
                    <?php if( $general['adjunto'] != ''): ?>
                    <input name="userfile" type="hidden" id="userfile" value="<?php echo $general['adjunto'];?>"/> 
                    <a href="javascript:verFoto('pdfprocesocompras/<?php echo $general['adjunto'];?>','800','600')"> Ver Archivo</a>
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

             <hr>
             <div style="text-align: left"><strong> <em scope="col">ITEMS DE PROFORMA</em></strong></div><br>
                <div class="row align-items-start">  
                   <div class="col-lg-1" ><strong>PROFORMA #</strong></div> 
                   <div class="col-lg-1" ><strong>PROCESO</strong></div>
                   <div class="col-lg-1" ><strong>PEDIDO</strong></div>
                   <div class="col-lg-1" ><strong>FACTURA</strong></div> 
                   <div class="col-lg-1" ><strong>CANTIDAD</strong></div>
                   <div class="col-lg-1" ><strong>MEDIDA</strong></div> 
                   <div class="col-lg-2" ><strong>DESCRIPCION</strong></div> 
                   <div class="col-lg-1" ><strong>MONEDA</strong></div>
                   <div class="col-lg-1" ><strong>PRECIO</strong></div>
                   <div class="col-lg-1" ><strong>PRECIO TOTAL</strong></div>
                   <div class="col-lg-1" ><strong>DELETE</strong></div>
                  <!--  <div class="col-lg-1" ><strong>VER</strong></div>     --> 
                </div> 
                 <?php foreach($this->proformas as $proforma) { ?>
                <div class="row celdaborde1">
                  <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $proforma['proforma']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $proforma['proceso']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2"><!-- <a target="_blank" style="text-decoration:none; color:#000000" href="view_orden_compra_cl_edit_hist.php?id=<?php echo $proforma['id']; ?>&str_numero_oc=<?php echo $proforma['str_numero_oc']; ?>&id_oc=<?php echo $proforma['id_c_oc'];?>"> </a> -->
                   <p><?php echo $proforma['pedido']; ?></p>
                 </div> 
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $proforma['factura']; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $proforma["cantidad"]; ?></p>
                 </div> 
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $proforma["medida"]; ?></p>
                 </div> 
                 <div class="col-lg-2" id="fondo_2">
                   <p><?php echo $proforma["descripcion"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $proforma["moneda"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $proforma["precio"]; ?></p>
                 </div>
                 <div class="col-lg-1" id="fondo_2">
                   <p><?php echo $proforma["precio_total"]; ?></p> 
                 </div>
                  <?php if($proforma['proforma']  && $_SESSION['acceso']){  ?> 
                    <div class="col-lg-1" id="fondo_2">
                      <a class="botonDel" id="btnDelItems" onclick='eliminar("<?php echo $proforma['id']; ?>","<?php echo $_GET['columna']; ?>","PROFORMA",  "?c=compras&a=Eliminar","0" )' type="button" >DELETE</a>
                    </div>
                  <?php  } ?>

                  
               </div>
                <?php  } ?>
 
             <br><br><br>
             <div class="panel-footer" > 
                <!-- <a class="botonGeneral" href="?c=ocomercial&id=<?php echo $proforma['id_pedido']; ?>">SALIR</a>  -->
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
       window.location="view_index.php?c=compras&a=Crud&columna=proforma&id="+idproforma;
       $('#mensaje').hide(); 
       if(idproforma){ 
         $('#mensaje').show(); 
         $("#mensaje").text('Buscando Proforma... !');  
       }
  });

  /*$( "#pedido" ).on( "change", function() {  
        idpedido = $( "#pedido" ).val();    
        window.location="view_index.php?c=compras&a=Crud&columna=pedido&id="+idpedido;
        $('#mensaje').hide(); 
        if(idpedido){ 
          $('#mensaje').show(); 
          $("#mensaje").text('Buscando Pedido... !');  
        }
   }); */
 

$(document).ready(function() {  

 
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
 