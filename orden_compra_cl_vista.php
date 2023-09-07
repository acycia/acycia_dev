<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?><?php


require_once("db/db.php"); 
require_once("Controller/Cgeneral.php");


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
if (isset($_GET['str_numero_oc'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM Tbl_orden_compra WHERE str_numero_oc = '%s' AND b_borrado_oc='0'", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

$colname_cliente_oc = "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_cliente_oc = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente_oc = sprintf("SELECT * FROM Tbl_orden_compra, cliente WHERE Tbl_orden_compra.str_numero_oc = '%s' AND Tbl_orden_compra.str_nit_oc = cliente.nit_c AND Tbl_orden_compra.b_borrado_oc='0'", $colname_cliente_oc);
$cliente_oc = mysql_query($query_cliente_oc, $conexion1) or die(mysql_error());
$row_cliente_oc = mysql_fetch_assoc($cliente_oc);
$totalRows_cliente_oc = mysql_num_rows($cliente_oc);

$colname_detalle = "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_detalle = sprintf("SELECT * FROM Tbl_items_ordenc WHERE str_numero_io = '%s' ORDER BY id_items ASC", $colname_detalle);
$detalle = mysql_query($query_detalle, $conexion1) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);
//REMISIONES X ITEMS
$colname_remision= "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_remision = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision = sprintf("SELECT * FROM Tbl_orden_compra,Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_orden_compra.str_numero_oc = '%s' AND Tbl_orden_compra.b_borrado_oc='0' AND Tbl_orden_compra.str_numero_oc=Tbl_remision_detalle.str_numero_oc_rd AND  Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY  Tbl_items_ordenc.id_items ASC", $colname_remision);
$remision = mysql_query($query_remision, $conexion1) or die(mysql_error());
$row_remision = mysql_fetch_assoc($remision);
$totalRows_remision = mysql_num_rows($remision);
//IMRPIME EL NOMBRE DEL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);


//ASIGNA NUMERO CONSECUTIVO DE REMISION
      $colname_remision= "-1";
      if (isset($_GET['str_numero_oc'])) {
        $colname_remision = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
      }
      mysql_select_db($database_conexion1, $conexion1);
      $query_remadjunto = sprintf("SELECT * FROM tbl_remisiones WHERE str_numero_oc_r='%s'", $colname_remision);
      $remadjunto = mysql_query($query_remadjunto, $conexion1) or die(mysql_error());
      $row_remadjunto = mysql_fetch_assoc($remadjunto);
      $totalRows_remadjunto = mysql_num_rows($remadjunto);
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/vista.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/vista.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
  <!-- select2 -->
  <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>


  <!-- css Bootstrap-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
  <div align="center">
    <table id="tabla3">
      <tr>
        <td id="noprint" align="right"><a href="orden_compra_cl_add.php"><img src="images/mas.gif" alt="ADD ORDEN DE COMPRA" title="ADD ORDEN DE COMPRA" border="0" style="cursor:hand;"/></a><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" /><?php if($row_cliente_oc['str_nit_oc']=='') { ?><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_orden_compra['str_numero_oc']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><?php } else { ?><a href="orden_compra_cl_edit.php?str_numero_oc=<?php echo $row_orden_compra['str_numero_oc']; ?>&id_oc=<?php echo $_GET['id_oc']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><a href="orden_compra_cl2.php"><img src="images/o.gif" style="cursor:hand;" alt="ORDENES DE COMPRA" title="LISTADO DE ORDENES DE COMPRA" border="0" /></a><?php } ?><a href="orden_compra_cl.php"><img src="images/i.gif" style="cursor:hand;" alt="ORDENES DE COMPRA INACTIVAS" title="LISTADO DE ORDENES DE COMPRA INACTIVAS" border="0" /></a> <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR" onClick="window.close() "/></a></td>
      </tr>
    </table>
    <table id="tabla1">
     <tr>
       <td align="center">
        <table id="tabla3">
          <tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
            <td id="titulo">ORDEN DE COMPRA</td>
          </tr>

          <tr>
            <td id="titular2">CLIENTES</td>
          </tr>
          <tr>
            <td id="numero2">N&deg; <strong><?php echo $row_orden_compra['str_numero_oc']; ?></strong></td>
          </tr>
          <tr>
            <td id="fondo2">ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6<br>Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
          </tr>
        </table>
        <table id="tabla3" >
          <tr>
            <td id="dato1"><strong>NIT : </strong><?php echo $row_cliente_oc['nit_c']; ?></td>
            <td id="dato1"><strong>PAIS / CIUDAD : </strong><?php echo $row_cliente_oc['pais_c']; ?> / <?php echo $row_cliente_oc['ciudad_c']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><strong>CLIENTE : </strong><?php echo $row_cliente_oc['nombre_c']; ?></td>
          </tr>
          <tr>
            <td id="dato1"><strong>FECHA DE PEDIDO : </strong><?php echo $row_cliente_oc['fecha_ingreso_oc']; ?></td>
            <td id="dato1"><strong>TELEFONO : </strong><?php echo $row_cliente_oc['telefono_c']; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><strong>DIRECCION C: </strong><?php $dir = ($row_orden_compra['str_dir_entrega_oc']);echo $dir; ?></td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><strong>DIRECCION ENVIO FACTURA: </strong><?php $dir = ($row_cliente_oc['direccion_envio_factura_c']);echo $dir; ?></td>
          </tr>
          <tr>
            <td id="dato1"><strong>CONTACTO COMERCIAL : </strong><?php echo $row_cliente_oc['contacto_c']; ?></td>
            <td id="dato1"><strong>FAX : </strong><?php echo $row_cliente_oc['fax_c']; ?></td>
          </tr>
          <tr>
            <td id="dato1"><strong>EMAIL COMERCIAL : </strong><?php echo $row_cliente_oc['email_comercial_c']; ?></td>
            <td id="dato1"><strong>CONDICIONES DE PAGO : </strong><?php echo $row_cliente_oc['str_condicion_pago_oc']; ?></td>
          </tr>
          <tr>
               <td id="dato1"><strong>Se entrega Factura: <?php echo $row_orden_compra['entrega_fac']; ?> </strong> 
             </td>
               <td id="dato1"><strong>Fecha Cierre Facturacion: <?php echo $row_orden_compra['fecha_cierre_fac']; ?></strong> </td>
          </tr>
          <tr>
             <td id="dato1"><strong>Adjuntar Comprobante: <?php echo $row_orden_compra['comprobante_ent']; ?></strong>
             <a class="editar" href="javascript:verFoto('Archivosdesp/<?php echo $row_remadjunto['comprobante_file'];?>','610','490')"> 
              <?php if($row_remadjunto['comprobante_file']!='') {echo "VER COMPROBANTE";}else{'Sin Adjunto';} ?>
               </a> 
              <td colspan="2" id="dato1">
              <label for="cobra_flete"><strong> Cobra Flete:</strong></label> 
               <?php echo $row_orden_compra['cobra_flete'] == 1 ?  "SI" : 'NO'; ?>   <?php if($row_orden_compra['cobra_flete']== 1 ){ echo 'valor: '  .$row_orden_compra['precio_flete']; } ?> 

              <strong>Tipo de O.C: </strong><?php echo $row_orden_compra['tipo_despacho']; ?> 
              </td> 
           </tr> 
           <tr>
              <td colspan="20" ><div class="alert alert-danger" id="verAlert" style="display: none;" ></div></td> 
           </tr>
        </table>
        <?php if(($row_remision['id_rd']!='')){ ?>
          <table id="tabla3">
            <tr>
              <td colspan="25" id="nivel2">RELACION O.C. - REMISION</td>
            </tr>
            <tr>
              <td nowrap="nowrap" id="nivel2">ITEM N&deg;</td>
              <td id="nivel2">REF. AC</td>
              <td nowrap="nowrap" id="nivel2">REF. MP</td>
              <td nowrap="nowrap" id="nivel2">REF. CLIENTE</td>
              <td id="nivel2">FECHA DE ENTREGA</td>
              <td id="nivel2">CANTIDAD</td>
              <td id="nivel2">CANTIDAD RESTANTE</td>
              <td nowrap="nowrap" id="nivel2">MEDIDA</td>
              <td nowrap="nowrap" id="nivel2">MONEDA</td>
              <td nowrap="nowrap" id="nivel2">TRM</td>
              <td nowrap="nowrap" id="nivel2">PRECIO / TRM</td>
              <td nowrap="nowrap" id="nivel2">PRECIO / PESOS&nbsp;</td>
              <td id="nivel2">IPUU&nbsp;</td>
              <td id="nivel2">TOTAL ITEM $ </td>
              <td nowrap="nowrap" id="nivel2">DIRECCION ENTREGA DEL PRODUCTO</td>
              <td nowrap="nowrap" id="nivel2">FACTURADO</td>
              <td nowrap="nowrap" id="nivel2">REMISION N&deg;</td>
              <td id="nivel2">REF. AC </td>
              <td id="nivel2">REF. CLIENTE</td>
              <td id="nivel2">RANGOS</td>
              <td id="nivel2">DESDE</td>
              <td nowrap="nowrap" id="nivel2">HASTA</td>
              <td id="nivel2">UNIDADES</td>
              <td id="nivel2">PESO</td>
              <td id="nivel2">PESO/N</td>
            </tr>
            <?php do { ?>
              <tr>
                <td id="fondo2"><?php echo $row_remision['int_consecutivo_io']; ?></td>
                <td id="fondo2">
                <?php 
                      $ref=$row_remision['int_cod_ref_io'];
                      $resultverif = $conexion->llenarCampos('tbl_referencia ref', "left join verificacion ver on ref.id_ref=ver.id_ref_verif WHERE ref.cod_ref='$ref' AND ver.estado_arte_verif='2'", "ORDER BY ver.id_verif DESC","ver.userfile " ); 
                       $muestra = $resultverif['userfile'] =='' ? "sin_arte.jpg" : $resultverif['userfile'] ; ?>

                        <a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')" > <?php echo $ref; ?></a>
                </td>
                <td nowrap="nowrap" id="fondo2"><?php $mp=$row_remision['id_mp_vta_io'];
                if($mp!='')
                {
                  $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
                  $resultmp= mysql_query($sqlmp);
                  $nump= mysql_num_rows($resultmp);
                  if($nump >='1')
                  { 
                    $nombre_mp = mysql_result($resultmp,0,'str_nombre');
                    echo $nombre_mp;
                  } } ?></td>
                  <td id="fondo2"><?php echo $row_remision['int_cod_cliente_io']; ?></td>
                  <td id="fondo2"><?php echo $row_remision['fecha_entrega_io']; ?></td>
                  <td id="fondo2"><?php echo ($row_remision['int_cantidad_io']); ?></td>
                  <td id="fondo2"><?php if($row_remision['int_cantidad_rest_io']==''){echo '0';}else{echo $row_remision['int_cantidad_rest_io'];} ?></td>
                  <td id="fondo2"><?php echo $row_remision['str_unidad_io']; ?></td>
                  <td id="fondo2"><?php echo $row_remision['str_moneda_io']; ?></td>
                  <td id="fondo2"><?php echo $row_remision['trm']; ?></td>
                  <td id="fondo2"><?php echo $row_remision['int_precio_trm']; ?></td>
                  <td id="fondo2"><?php echo $row_remision['N_precio_old']//$row_remision['int_precio_io']; ?></td>
                  <td id="fondo2"><?php echo $row_remision['impuesto']==1 ? 'SI': 'NO'; ?></td>
                  <td id="fondo2"><?php echo $tota=($row_remision['int_cantidad_io']*$row_remision['N_precio_old'])//$row_detalle['int_total_item_io'];?><?php  //$tota=$row_remision['int_total_item_io']; echo number_format($tota, 2, ",", ".");  ?></td>
                  <td id="fondo1"><?php echo $row_remision['str_direccion_desp_io']; ?></td>
                  <td nowrap="nowrap" id="fondo3">
                   <?php if($row_remision['b_estado_io']=='1'){echo "Ingresado";}else if($row_remision['b_estado_io']=='5'){echo "Facturado Total";}else if($row_remision['b_estado_io']=='4'){echo "Facturado parcial";}else if($row_remision['b_estado_io']=='2'){echo "Programado";}else if($row_remision['b_estado_io']=='3'){echo "Remisionado";} ?>
                 </td>
                 <!--VARIABLES DE REMISION X ITEMS-->
                 <td id="fondo2">
                  <a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision_r_rd'] ?>" target="_blank"><?php echo $row_remision['int_remision_r_rd']; ?></a>
                </td>      
                 <td id="fondo2"><?php echo $row_remision['int_ref_io_rd']; ?></td>
                 <td id="fondo2"><?php echo $row_remision['str_ref_cl_io_rd']; ?></td>
                 <td id="fondo2"><?php echo $row_remision['int_caja_rd']; ?></td>
                 <td id="fondo2"><?php echo $row_remision['int_numd_rd']; ?></td>
                 <td id="fondo2"><?php echo $row_remision['int_numh_rd']; ?></td>
                 <td id="fondo2"><?php $cant=$row_remision['int_cant_rd'];echo number_format($cant, 0, ",", "."); ?></td>
                 <td id="fondo2"><?php echo $row_remision['int_peso_rd']; $peso=(double)$peso+$row_remision['int_peso_rd']; ?></td>
                 <td id="fondo2"><?php echo $row_remision['int_pesoneto_rd'];$peson=(double)$peson+$row_remision['int_pesoneto_rd']; ?></td>      
               </tr>
               <tr>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td nowrap="nowrap"id="detalle4">&nbsp;</td>
                <td nowrap="nowrap"id="detalle4">&nbsp;</td>
                <td nowrap="nowrap"id="detalle4">&nbsp;</td>
                <td nowrap="nowrap"id="detalle4">&nbsp;</td>     
                <td nowrap="nowrap"id="fondo2">Sub Total $</td>
                <td id="fondo2"><?php 
                $subtotal=$row_remision['int_cant_rd']*$row_remision['N_precio_old'];
                echo number_format($subtotal, 2, ",", "."); 
                ?>        <?php $acumula+=$subtotal; ?></td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td nowrap="nowrap"id="fondo2">Sub Total $</td>
                <td id="fondo2"><?php echo number_format($row_remision['int_cant_rd'], 0, ",", "."); $totalrm=$totalrm+$row_remision['int_cant_rd']; ?></td>      
                <td id="fondo2"><?php echo $peso; ?></td>
                <td id="fondo2"><?php echo $peson; ?></td>
                <td id="detalle4">&nbsp;</td>
              </tr>
            <?php } while ($row_remision = mysql_fetch_assoc($remision)); ?> 

            <tr>
              <td id="detalle4">&nbsp;</td>
              <td id="detalle4">&nbsp;</td>
              <td id="detalle4">&nbsp;</td>
              <td id="detalle4">&nbsp;</td>
              <td id="detalle4">&nbsp;</td>
              <td id="detalle4">&nbsp;</td>
              <td id="detalle4">&nbsp;</td>
              <td nowrap="nowrap"id="detalle4">&nbsp;</td>
              <td nowrap="nowrap"id="detalle4">&nbsp;</td>
              <td nowrap="nowrap"id="detalle4">&nbsp;</td>
              <td nowrap="nowrap"id="detalle4">&nbsp;</td>
              <td id="nivel2">TOTALES </td>
              <td id="detalle2"><strong><?php echo number_format($acumula, 2, ",", ".");?></strong></td>
              <td id="detalle4">&nbsp;</td>
              <td id="detalle4">&nbsp;</td>
              <td colspan="4" id="fondo2">    
                <?php if ($row_orden_compra['b_estado_oc']=='4'&&$row_cliente_oc['str_condicion_pago_oc']!="ANTICIPADO"){echo "O.C Facturada Parcial";}else if($row_orden_compra['b_estado_oc']=='5'&&$row_cliente_oc['str_condicion_pago_oc']!="ANTICIPADO"){echo "O.C Facturada Total";}else if($row_orden_compra['b_estado_oc']=='4'&&$row_cliente_oc['str_condicion_pago_oc']=="ANTICIPADO"){echo "O.C Facturada Parcial Anticipado";}else if($row_orden_compra['b_estado_oc']=='5'&&$row_cliente_oc['str_condicion_pago_oc']=="ANTICIPADO"){echo "O.C Facturada Total Anticipado";};?></td>
                <td id="nivel2">TOTALES</td>
                <td id="detalle2"><strong><?php echo number_format($totalrm, 0, ",", "."); ?></strong></td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
                <td id="detalle4">&nbsp;</td>
              </tr>
            </table>    
          <?php }?>
             

          <!-- //INICIA E IMPRIME SI NO TIENE REMISIONES -->  
          <?php if(($row_detalle['id_items']!='')){ ?>
            <table width="123%" id="tabla3">
              <tr>
                <td colspan="20" id="nivel2">DETALLE O.C. POR REFERENCIA</td>
              </tr>
              <tr>
                <td nowrap="nowrap" id="nivel2">ITEM N&deg;</td>
                <td id="nivel2">REF. AC</td>
                <td id="nivel2">REF. MP</td>
                <td nowrap="nowrap" id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">FECHA DE ENTREGA</td>
                <td id="nivel2">CANTIDAD</td>
                <td id="nivel2">CANTIDAD RESTANTE</td>
                <td id="nivel2">MEDIDA</td>
                <td nowrap="nowrap" id="nivel2">MONEDA</td>
                <td nowrap="nowrap" id="nivel2">TRM</td>
                <td nowrap="nowrap" id="nivel2">PRECIO / TRM</td>
                <td id="nivel2">PRECIO / PESOS</td>
                <td id="nivel2">IPUU&nbsp;</td>
                <td id="nivel2">TOTAL REMISIONADO $</td>
                <!--<td id="nivel2">VENDEDOR</td>
                  <td id="nivel2">COMI. %</td>-->
                  <td id="nivel2">COBRA CYREL</td>
                  <!-- <td id="nivel2">COBRA FLETE</td> -->
                  <td nowrap="nowrap" id="nivel2">FACTURADO</td>  
                   <td id="nivel2">VENDEDOR</td> 
                  <td nowrap="nowrap" id="nivel2">DIR. BODEGA ENTREGA</td>               
                </tr>
                <?php do { ?>
                  <tr>
                    <td id="fondo2"><?php echo $row_detalle['int_consecutivo_io']; ?></td>
                    <td id="fondo2"> 
                      <?php 
                      $ref=$row_detalle['int_cod_ref_io'];
                      $resultverif = $conexion->llenarCampos('tbl_referencia ref', "left join verificacion ver on ref.id_ref=ver.id_ref_verif WHERE ref.cod_ref='$ref' AND ver.estado_arte_verif='2'", "ORDER BY ver.id_verif DESC","ver.userfile " ); 
                       $muestra = $resultverif['userfile'] =='' ? "sin_arte.jpg" : $resultverif['userfile'] ; ?>

                        <a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')" > <?php echo $ref; ?></a>

                    </td>
                    <td nowrap="nowrap" id="fondo2"><?php $mp=$row_detalle['id_mp_vta_io'];
                    if($mp!='')
                    {
                      $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
                      $resultmp= mysql_query($sqlmp);
                      $nump= mysql_num_rows($resultmp);
                      if($nump >='1')
                      { 
                        $nombre_mp = mysql_result($resultmp,0,'str_nombre');
                        echo $nombre_mp;
                      } } ?></td>
                      <td id="fondo2"><?php echo $row_detalle['int_cod_cliente_io']; ?></td>
                      <td id="fondo2"><?php echo $row_detalle['fecha_entrega_io']; ?></td>
                      <td id="fondo2"><?php echo ($row_detalle['int_cantidad_io']);?></td>
                      <td id="fondo2"><?php if($row_detalle['int_cantidad_rest_io']==''){echo '0';}else{echo ($row_detalle['int_cantidad_rest_io']);} ?></td>
                      <td id="fondo2"><?php echo $row_detalle['str_unidad_io']; ?></td>
                      <td id="fondo2"><?php echo $row_detalle['str_moneda_io']; ?></td>
                      <td id="fondo2"><?php echo $row_detalle['trm']; ?></td>
                      <td id="fondo2"><?php echo $row_detalle['int_precio_trm']; ?></td>
                      <td id="fondo2"><?php echo $row_detalle['N_precio_old']; ?></td>
                      <td id="fondo2"><?php echo $row_detalle['impuesto']==1 ? 'SI': 'NO'; ?></td>
                      <td id="fondo2"><?php  $rem=($row_detalle['int_cantidad_io']-$row_detalle['int_cantidad_rest_io']);$remisionado=$rem*$row_detalle['N_precio_old'];echo number_format($remisionado, 2, ",", "."); ?></td>
                      <td id="fondo2"><?php echo $row_detalle['cobra_cyrel']==1 ? 'SI':'NO' ; ?></td>
                      <!-- <td id="fondo2"><?php echo $row_detalle['cobra_flete']==1 ? $row_detalle['precio_flete']:'NO' ;   ?></td> -->
      <!--    
  <td id="fondo2"><?php echo $row_detalle['int_comision_io']; ?></td>  -->    
  <td nowrap="nowrap"id="fondo2"><?php if($row_detalle['b_estado_io']=='1'){echo "Ingresado";}else if($row_detalle['b_estado_io']=='5'){echo "Facturado Total";}else if($row_detalle['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_detalle['b_estado_io']=='2'){echo "Programado";}else if($row_detalle['b_estado_io']=='3'){echo "Remisionado";} ?>
  </td>
  <td id="fondo2"><?php $vendedor=$row_detalle['int_vendedor_io'];
    if($vendedor!='')
    {
    $sqlvendedor="SELECT * FROM vendedor WHERE id_vendedor ='$vendedor'";
    $resultvendedor= mysql_query($sqlvendedor);
    $nuvendedor= mysql_num_rows($resultvendedor);
    if($nuvendedor >='1')
    { 
    $nombre_vendedor = mysql_result($resultvendedor,0,'nombre_vendedor');
    echo $nombre_vendedor;
    } } ?></td>
    <td id="fondo1"><?php echo $row_detalle['str_direccion_desp_io']; ?></td>
</tr>         
<?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>       
<?php }?>
<tr>
 <td id="nivel1">FACTURA CIRELES</td>
 <td id="fondo2"><strong>
  <?php if ($row_orden_compra['b_factura_cirel_oc']=='0'){echo "NO";}else {echo "SI";}?>
</strong></td>
<td >&nbsp;</td>
<td id="nivel1"><strong>VNTA. WEB</strong></td>
<td id="fondo2"><strong>
 <?php if ($row_orden_compra['vta_web_oc']=='0'){echo "NO";}else {echo "SI";}?>
</strong></td>
<td id="nivel1"><strong>EXPORTACION</strong></td>
<td id="fondo2"><strong>
 <?php if ($row_orden_compra['expo_co']=='0'){echo "NO";}else {echo "SI";}?>
</strong></td>

<td >&nbsp;</td>
<td colspan="9">&nbsp;</td>
</tr>

<td nowrap="nowrap"></td>
<td nowrap="nowrap"></td>
</tr> 

<tr>
  <td colspan="9" id="nivel1">OBSERVACIONES</td>
  <td nowrap="nowrap" id="nivel1"> ARCHIVO 1</td>
  <td nowrap="nowrap" id="nivel1"> ARCHIVO 2</td>  
  <td nowrap="nowrap" id="nivel1"> ARCHIVO 3</td>      
</tr>
<tr>
 <td colspan="9" rowspan="3" id="detalle1">- <?php $obs=  ($row_orden_compra['str_observacion_oc']);echo $obs; ?> - </td>      
<td id="detalle1">
 <?php if($row_orden_compra['str_archivo_oc']!=''){ ?><a class="editar" href="javascript:verFoto('pdfacturasoc/<?php  echo $row_orden_compra['str_archivo_oc'];?>','610','490')"><?php echo "ARC 1";?></a><?php }else{echo 'Sin Archivo';} ?> 
 </td>
<td id="detalle1">
<?php if($row_orden_compra['adjunto2']!=''){ ?><a class="editar" href="javascript:verFoto('pdfacturasoc/<?php  echo $row_orden_compra['adjunto2'];?>','610','490')"><?php echo "ARC 2";?></a><?php }else{echo 'Sin Archivo';} ?> 
 </td>  
<td id="detalle1">
<?php if($row_orden_compra['adjunto3']!=''){ ?><a class="editar" href="javascript:verFoto('pdfacturasoc/<?php  echo $row_orden_compra['adjunto3'];?>','610','490')"><?php echo "ARC 3";?></a><?php }else{echo 'Sin Archivo';} ?> 
</td>  

</tr>
<table id="tabla3">
  <tr>
    <td id="nivel2">LUGAR DE ENTREGA </td>
    <td id="nivel2">ELABORADO POR </td>
    <td id="nivel2">APROBADO POR </td>
    <td id="nivel2"><p>FIRMA &amp; SELLO ACYCIA </p>
    </td>
  </tr>
  <tr>
    <td id="detalle2"><?php echo $row_orden_compra['str_dir_entrega_oc']; ?></td>
    <td id="detalle2"><?php echo $row_orden_compra['str_elaboro_oc']; ?></td>
    <td id="detalle2"><?php echo $row_orden_compra['str_aprobo_oc']; ?></td>
    <td id="detalle2">&nbsp;</td>
  </tr>
</table>
<table id="tabla3">
  <tr>
    <td id="fondo2">CODIGO : A3 - F02</td>
    <td id="fondo2">Favor citar este numero de Orden de Compra en la Factura.</td>
    <td id="fondo3">VERSION : 0</td>
  </tr>
</table>
</table>
</div>
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){ 
    var id_oc = "<?php echo $_GET['id_oc'] ?>"; 
    var cliente = "<?php echo $row_cliente_oc['nombre_c'] ?>"; 
    consultaGenerico("Tbl_logs","codigo_id",id_oc,cliente,'El Cliente se Modifico Recientemente: '); 
 }); 
   
</script>
<script>
 $(document).ready(function(){

   var editar = "<?php echo $_SESSION['acceso'];?>";
   if(editar==0){

     $("input").attr('disabled','disabled');
     $("textarea").attr('disabled','disabled');
     $("select").attr('disabled','disabled'); 

     $('a').each(function() { 
       $(".editar").attr('href', '#');
     });
              //swal("No Autorizado", "Sin permisos para editar :)", "error"); 
   }
 });
</script> 
<?php
mysql_free_result($usuario);mysql_close($conexion1);

mysql_free_result($orden_compra);

mysql_free_result($cliente_oc);

mysql_free_result($detalle);

mysql_free_result($vendedores);
mysql_close($conexion1);
?>