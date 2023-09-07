<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?><?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "usuario.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
    $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php

$conexion = new ApptivaDB();

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_orden_compra = "-1";
if (isset($_GET['n_oc'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM orden_compra WHERE n_oc = %s", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

$colname_proveedor_oc = "-1";
if (isset($_GET['n_oc'])) {
  $colname_proveedor_oc = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor_oc = sprintf("SELECT * FROM orden_compra, proveedor WHERE orden_compra.n_oc = '%s' AND orden_compra.id_p_oc = proveedor.id_p", $colname_proveedor_oc);
$proveedor_oc = mysql_query($query_proveedor_oc, $conexion1) or die(mysql_error());
$row_proveedor_oc = mysql_fetch_assoc($proveedor_oc);
$totalRows_proveedor_oc = mysql_num_rows($proveedor_oc);

$colname_detalle = "-1";
if (isset($_GET['n_oc'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_detalle = sprintf("SELECT * FROM orden_compra_detalle, insumo WHERE orden_compra_detalle.n_oc_det = '%s' AND orden_compra_detalle.id_insumo_det = insumo.id_insumo", $colname_detalle);
$detalle = mysql_query($query_detalle, $conexion1) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);
?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
<!--   <link href="css/vista.css" rel="stylesheet" type="text/css" /> -->
  <script type="text/javascript" src="js/formato.js"></script> 

 <!-- desde aqui para listados nuevos -->
   <link href="css/formato.css" rel="stylesheet" type="text/css" />
   <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
   <link rel="stylesheet" type="text/css" href="css/general.css"/>

 <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>
<body>
  <div class="table-responsive"><!-- este define el fondo gris de lado a lado si se coloca dentro de tabla inicial solamente coloca borde gris -->
    <div class="table-responsive" align="center"> <!-- <div align="center"> -->
      <table > 
        <tr>
         <td> 
           <div class="panel panel-primary">
                <div class="panel-heading" align="left" ></div><!--color azul-->
                <div class="row" >
                  <div class="span3">&nbsp;&nbsp;&nbsp; <img src="images/cabecera.jpg"></div> 
                  <div class="span3">
                      <img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><?php if($row_proveedor_oc['id_p_oc']=='') { ?><a href="orden_compra_edit.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><?php } else { ?><a href="orden_compra_edit.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>&id_p_oc=<?php echo $row_proveedor_oc['id_p_oc']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="orden_compra.php"><img src="images/o.gif" style="cursor:hand;" alt="ORDENES DE COMPRA" border="0" /></a><?php } ?><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/>
                    </div>
                </div>
               
                <div class="panel-body">

                 <div align="center"> 
                  <table > 
                    <tr>
                      <td ><h2 style="text-align: right;" > ORDEN DE COMPRA</h2></td>
                    </tr>
                    <tr>
                      <td style="text-align: center;" ><strong><?php echo $row_proveedor_oc['tipo_servicio_p']; ?></strong></td>
                    </tr>
                  </table>
                </div> 

                <table class="table table-bordered table-sm">
                  <tr>
                    <td rowspan="4" align="center" ><img src="images/logoacyc.jpg"></td> 
                  </tr>  
                  <tr>
                    <td style="color: red;" align="center" ><h3> N&deg; <?php echo $row_orden_compra['n_oc']; ?> </h3></td>
                  </tr>
                  <tr>
                    <td align="center" >ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6<br>Carrera 45 No. 14 - 15  Tel: 604 311-21-44 Medellin-Colombia</td>
                  </tr>
                </table>
                <table class="table table-bordered table-sm">
                  <tr>
                    <td colspan="5" ><strong>FECHA DE PEDIDO : </strong><?php echo $row_proveedor_oc['fecha_pedido_oc']; ?></td>
                    <td colspan="5" ><strong>FECHA DE ENTREGA : </strong><span class="rojo_azul_n" > <?php echo $row_proveedor_oc['fecha_entrega_oc']; ?></span></td>
                  </tr>
                  <tr>
                    <td colspan="10" ><strong>PROVEEDOR : </strong><?php echo $row_proveedor_oc['proveedor_p']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="5" ><strong>NIT : </strong><?php echo $row_proveedor_oc['nit_p']; ?></td>
                    <td colspan="5" ><strong>PAIS / CIUDAD : </strong><?php echo $row_proveedor_oc['pais_p']; ?> / <?php echo $row_proveedor_oc['ciudad_p']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="10" ><strong>CONTACTO COMERCIAL : </strong><?php echo $row_proveedor_oc['contacto_p']; ?></td>
                  </tr>
                  <hr>
                  <tr>
                    <td colspan="5" ><strong>TELEFONO : </strong><?php echo $row_proveedor_oc['telefono_p']; ?></td>
                    <td colspan="5" ><strong>N° CELULAR: </strong><?php echo $row_proveedor_oc['fax_p']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="10" ><strong>CONDICIONES DE PAGO : </strong><?php echo $row_proveedor_oc['cond_pago_oc']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="10" ><strong>CORREO ELECTRÓNICO CLIENTE : </strong><?php echo $row_proveedor_oc['email_c_p']; ?></td>
                  </tr>
                 
                  <tr>
                    <td colspan="10" ><strong>DIRECCION DE ENTREGA: </strong><?php echo $row_orden_compra['lugar_entrega_oc']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" ><strong>NOMBRE CONTACTO: </strong><?php echo $row_orden_compra['contacto']; ?></td>
                    <td colspan="4" ><strong>TELEFONO: </strong><?php echo $row_orden_compra['telefono']; ?></td>
                    <td colspan="3" ><strong>HORARIO DE RECEPCIÓN: </strong><?php echo $row_orden_compra['horario']; ?></td> 
                  </tr>
                  <tr>
                    <td colspan="3" ><strong>FACTURAS: </strong><?php echo $row_orden_compra['factura']; ?></td>
                    <td colspan="4" ><strong>FECHA FACTURA:</strong><?php echo $row_orden_compra['fecha_factura']; ?> </td>
                    <td colspan="3" ><strong>FECHA VENCE FACTURAS:</strong><?php echo $row_orden_compra['fecha_vence_factura']; ?>  </td> 
                  </tr>
                  <tr>
                    <td colspan="10" >
                     <strong> TIPO DE PEDIDO:</strong>  <?php echo $row_orden_compra['tipo_pedido']; ?>
                   </td>
                 </tr>
                 <tr>
                  <td colspan="5" ><strong>CORREO FACTURACIÓN: </strong><?php echo $row_orden_compra['correo1']; ?></td>
                  <td colspan="5" ><strong>CORREO COMPRAS: </strong><?php echo $row_orden_compra['correo2']; ?></td>
                </tr>
                <tr>

                  <?php  
                  $porciones = array();
                  $porciones = explode(",", $row_orden_compra['userfilenuevo']);
                  $count = 0;
                  ?>
                  <?php if( $row_orden_compra['userfilenuevo'] != ''): ?>
                   <?php foreach ($porciones as $key => $value) { ?>
                    <?php $count++;?>
                    <?php if($value!=''):?> 
                      <td  >
                        <a href="javascript:verFoto('ArchivosOcProv/<?php echo $value;?>','610','490')">Archivo<?php echo $count;?></a> 
                        <input name="userfile<?php echo $count;?>" type="hidden" id="userfile<?php echo $count;?>" value="<?php echo $value; ?>"/> 
                      </td>
                    <?php endif; ?>
                  <?php } ?> 
                <?php endif; ?>

              </tr>
            </table>


            <table class="table table-bordered table-sm" >
              <tr> 
                <td nowrap="nowrap" ><strong>CANTIDAD</strong></td>
                <td colspan="3" nowrap="nowrap" ><strong>DESCRIPCION</strong></td>
                <td nowrap="nowrap" ><strong>MEDIDA</strong></td> 
                <td nowrap="nowrap" ><strong>ÁREA</strong></td>
                <td nowrap="nowrap" ><strong>MONEDA</strong></td>
                <td nowrap="nowrap" ><strong>V. UNIT.</strong></td>
                <td nowrap="nowrap" ><strong>DESC.</strong></td>
                <td nowrap="nowrap" ><strong>IVA</strong></td>
                <td nowrap="nowrap" ><strong>SUBTOTAL</strong></td>
              </tr>
              <?php do { ?>
                <tr> 
                  <td id="detalle3"><?php echo $row_detalle['cantidad_det']; ?></td>
                  <td colspan="3" id="detalle1"><?php echo $row_detalle['descripcion_insumo']; ?></td>
                  <td id="detalle1">
                    <?php 
                    $medida=$row_detalle['medida_insumo'];
                    if($medida!='')
                    {
                     $sqlmedida="SELECT * FROM medida WHERE id_medida ='$medida'";
                     $resultmedida= mysql_query($sqlmedida);
                     $numedida= mysql_num_rows($resultmedida);
                     if($numedida >='1')
                     { 
                       $nombre_medida = mysql_result($resultmedida,0,'nombre_medida');
                       echo $nombre_medida;
                     } } ?></td> 
                     <td nowrap="nowrap" id="detalle2"><?php echo $row_detalle['concepto1']; ?></td>
                     <td nowrap="nowrap" id="detalle2"><?php echo $row_detalle['moneda_det']; ?></td>
                     <td nowrap="nowrap" id="detalle3"><?php echo $row_detalle['valor_unitario_det']; ?></td> 
                     <td nowrap="nowrap" id="detalle3"><?php echo $row_detalle['descuento_det']; ?> % </td>
                     <td nowrap="nowrap" id="detalle3"><?php echo $row_detalle['valor_iva']; ?></td>
                     <td nowrap="nowrap" id="detalle3"><strong><?php echo $row_detalle['total_det']; ?></strong></td>
                   </tr>
                 <?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>
                 <tr>
                   <td><br></td>
                 </tr>
                 <tr>
                   <td colspan="3" id="nivel1">OBSERVACIONES</td>
                   <td colspan="3" > <?php $muestra=$row_orden_compra['userfilenuevo']; ?>
           <!-- <a href="javascript:verFoto('ArchivosOcProv/<?php echo $muestra;?>','610','490')">
            <?php if ($muestra!="")echo "Archivo";?>
          </a> --></td>
          <td colspan="4"  ><strong> ADELANTO</strong></td>
          <td id="detalle3"><?php echo $row_orden_compra['adelanto_oc']; ?></td>
        </tr>
         <tr>
          <td colspan="6" rowspan="8" class="rojo_azul_n">- <?php echo $row_orden_compra['observacion_oc']; ?> - </td>
        <tr> 
          <td colspan="4"  ><strong> DESCUENTO</strong></td>
          <td id="detalle3"><?php echo $row_orden_compra['descuento_oc']; ?></td>
        </tr>
          
          <td colspan="4"  ><strong> SUBTOTAL$</strong></td>
          <td id="detalle3"><?php echo $row_orden_compra['valor_bruto_oc']; ?></td>
        </tr>
        <tr>
          <td colspan="4"  ><strong> I.V.A $ <?php echo $row_orden_compra['constante_iva']; ?></strong></td>
          <td id="detalle3"><?php echo $row_orden_compra['valor_iva_oc'] == '' ? '0': $row_orden_compra['valor_iva_oc']; ?><?php /*if($valor_iva != '0') { echo $row_orden_compra['valor_iva_oc']; }else{echo "Excento";}*/  ?></td>
        </tr>
       
         <tr>
           <td colspan="4"  ><strong> Rte. Fte  $ <?php echo $row_orden_compra['constante_fte']; ?></strong></td>
           <td id="detalle3"><?php echo $row_orden_compra['fte_oc']; ?></td>
         </tr>
         <tr>
           <td colspan="4"  ><strong> Rte. IVA $ <?php echo $row_orden_compra['constante_fte_iva']; ?></strong></td>
           <td id="detalle3"><?php echo $row_orden_compra['fte_iva_oc']; ?></td>
         </tr>
         <tr>
           <td colspan="4"  ><strong> Rte. ICA $ <?php echo $row_orden_compra['constante_fte_ica']; ?></strong></td>
           <td id="detalle3"><?php echo $row_orden_compra['fte_ica_oc']; ?></td>
         </tr>
         <tr>
           <td colspan="4"  ><strong> TOTAL</strong></td>
           <td id="detalle3"><strong> <?php echo $row_orden_compra['total_oc']; ?></strong></td>
         </tr>
       </table>
       <table class="table table-bordered table-sm">
        <tr>
           <td colspan="10"><p><h5 style="color: red;" >Para darle cumplimiento a la resolución 0085 de DIAN solo se recibirány aceptaran para pago, las facturas<br>  que sean enviadas al correo  <strong><a href="mailto:factura-electronica@acycia.com">factura-electronica@acycia.com</a> </strong> adjuntas en PDF y XML.</h5> </p> 
           </td> 
         </tr>
       
        <tr>
          <td colspan="4"><strong> ELABORADO POR </strong></td>
          <td colspan="3"><strong> APROBADO POR </strong></td>
          <td colspan="4"> <p><strong>FIRMA &amp; SELLO ACYCIA </strong></p> 
          </td>
        </tr>
        <tr>
          <td colspan="4"><strong><?php echo $row_orden_compra['responsable_oc']; ?></strong></td>
          <td colspan="3"><strong><?php echo $row_orden_compra['aprobo_oc']; ?></strong></td>
          <td colspan="4">&nbsp;</td>
        </tr>
      </table>
      <table class="table table-bordered table-sm">
        <tr>
          <td id="fondo1">CODIGO : A3 - F02</td>
          <td id="fondo2"><b style="font-size:15px;" > Favor citar este numero de Orden de Compra en la Factura.</b></td>
          <td id="fondo3">VERSION : 2</td>
        </tr>
      </table>
 
  
          </div>
        </div> 
      </td>
    </tr>
  </table>
</div>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($orden_compra);

mysql_free_result($proveedor_oc);

mysql_free_result($detalle);
?>