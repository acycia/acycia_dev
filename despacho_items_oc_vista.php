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
 
//CLIENTE


$colname_cliente_r = "-1";
if (isset($_GET['int_remision'])) {
  $colname_cliente_r = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
}

mysql_select_db($database_conexion1, $conexion1);
$query_cliente_r = sprintf("SELECT * FROM Tbl_remisiones,Tbl_orden_compra, cliente WHERE Tbl_remisiones.int_remision = %s AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.str_numero_oc_r=Tbl_orden_compra.str_numero_oc AND Tbl_orden_compra.str_nit_oc = cliente.nit_c AND Tbl_orden_compra.b_borrado_oc='0'", $colname_cliente_r);
$cliente_r = mysql_query($query_cliente_r, $conexion1) or die(mysql_error());
$row_cliente_r = mysql_fetch_assoc($cliente_r);
 $totalRows_cliente_r = mysql_num_rows($cliente_r);
//imprime total detalle remision y detalle oc

//SELECT * FROM Tbl_remisiones,Tbl_remision_detalle WHERE Tbl_remisiones.int_remision = %s AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd  ORDER BY Tbl_remision_detalle.id_rd ASC
$colname_remision = "-1";
if (isset($_GET['int_remision'])) {
  $colname_remision = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision = sprintf("SELECT * FROM Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_remisiones.int_remision = %s AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_remision_detalle.id_rd ASC", $colname_remision);
$remision = mysql_query($query_remision, $conexion1) or die(mysql_error());
$row_remision = mysql_fetch_assoc($remision);
$totalRows_remision = mysql_num_rows($remision);
//imprime total cajas
if($totalRows_remision >='1'){
  $total_cajas = mysql_result($remision, 0, 'int_total_cajas_rd');
}
//imprime total  detalle oc y detalle remision
$colname_remision2 = "-1";
if (isset($_GET['int_remision'])) {
  $colname_remision2 = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision2 = sprintf("SELECT * FROM Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_remisiones.int_remision = %s AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_mp_io_rd=Tbl_items_ordenc.id_mp_vta_io and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_remision_detalle.id_rd ASC", $colname_remision2);
$remision2 = mysql_query($query_remision2, $conexion1) or die(mysql_error());
$row_remision2 = mysql_fetch_assoc($remision2);
$totalRows_remision2 = mysql_num_rows($remision2);


$colname_remision5 = "-1";
if (isset($_GET['int_remision'])) {
  $colname_remision5 = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision5 = sprintf("SELECT * FROM Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_remisiones.int_remision = %s AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_mp_io_rd=Tbl_items_ordenc.id_mp_vta_io and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_remision_detalle.id_rd ASC", $colname_remision5);
$remision5 = mysql_query($query_remision5, $conexion1) or die(mysql_error());
$row_remision5 = mysql_fetch_assoc($remision5);
$totalRows_remision5 = mysql_num_rows($remision5);

$colname_remision6 = "-1";
if (isset($_GET['int_remision'])) {
  $colname_remision6 = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision6 = sprintf("SELECT * FROM Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_remisiones.int_remision = %s AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_mp_io_rd=Tbl_items_ordenc.id_mp_vta_io and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_remision_detalle.id_rd ASC", $colname_remision6);
$remision6 = mysql_query($query_remision6, $conexion1) or die(mysql_error());
$row_remision6 = mysql_fetch_assoc($remision6);
$totalRows_remision6 = mysql_num_rows($remision6);


//oc
$colname_remision = "-1";
if (isset($_GET['int_remision'])) {
  $colname_remision = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision3 = sprintf("SELECT * FROM Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_remisiones.int_remision = %s AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_remision_detalle.id_rd ASC", $colname_remision);
$remision3 = mysql_query($query_remision3, $conexion1) or die(mysql_error());
$row_remision3 = mysql_fetch_assoc($remision3);
$totalRows_remision3 = mysql_num_rows($remision3);

$colname_remision = "-1";
if (isset($_GET['int_remision'])) {
  $colname_remision = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision4 = sprintf("SELECT * FROM Tbl_remisiones,Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_remisiones.int_remision = %s AND Tbl_remisiones.b_borrado_r='0' AND Tbl_remisiones.int_remision=Tbl_remision_detalle.int_remision_r_rd AND  Tbl_remision_detalle.str_numero_oc_rd=Tbl_items_ordenc.str_numero_io AND  Tbl_remision_detalle.int_ref_io_rd=Tbl_items_ordenc.int_cod_ref_io  and Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_remision_detalle.id_rd ASC", $colname_remision);
$remision4 = mysql_query($query_remision4, $conexion1) or die(mysql_error());
$row_remision4 = mysql_fetch_assoc($remision4);
$totalRows_remision4 = mysql_num_rows($remision4);

//IMRPIME EL NOMBRE DEL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) or die(mysql_error());
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <!-- <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/> -->
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/vista.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/vista.js"></script>
  <script type="text/javascript" src="js/formato.js"></script>
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
  <div align="center">
    <table id="tabla3">
      <tr>
        <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" title="IMPRIMIR" border="0" /><?php if($row_cliente_r['str_nit_oc']=='') { ?><a href="despacho_items_oc_edit.php?int_remision=<?php echo $row_cliente_r['int_remision']; ?>&str_numero_r<?php echo $row_cliente_r['str_numero_oc_r']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><?php } else { ?><a href="despacho_items_oc_edit.php?int_remision=<?php echo $row_cliente_r['int_remision']; ?>&str_numero_r=<?php echo $row_cliente_r['str_numero_oc_r']; ?>"><img src="images/menos.gif" alt="EDITAR" title="EDITAR" border="0" /></a><a href="despacho_oc.php"><img src="images/r.gif" style="cursor:hand;" alt="LISTADO DE REMISIONES" title="LISTADO DE REMISIONES" border="0" /></a><?php } ?> <a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><a href="menu.php"><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR" onClick="window.close() "/></a></td>
      </tr>
    </table>
    <table id="tabla1"><tr><td align="center">
      <table id="tabla3">
        <tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
          <td id="titulo">REMISION</td>
        </tr>


        <tr>
          <td id="numero2">N&deg; <strong><?php echo $row_cliente_r['int_remision']; ?></strong></td>
          <td id="fondo2"><strong><?php if($row_remision['b_estado_io']=='6'){echo "Muestras reposicion";}  ?> </strong></td>
        </tr>
        <tr>
          <td id="fondo2">ALBERTO CADAVID R & CIA S.A.  Nit: 890915756-6<br>Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
        </tr>
      </table>
      <table id="tabla3">
        <tr>
          <td id="dato1"><strong>NIT : </strong><?php echo $row_cliente_r['nit_c']; ?></td>
          <td  colspan="2"  id="dato1"><strong>PAIS / CIUDAD : </strong><?php echo $row_cliente_r['ciudad_pais']; ?></td>
        </tr>
        <tr>
          <td colspan="3" id="dato1"><strong>CLIENTE : </strong><?php echo $row_cliente_r['nombre_c']; ?></td>
        </tr>
        <tr>
          <td id="dato1"><strong>FECHA DE PEDIDO : </strong><?php echo $row_cliente_r['fecha_r']; ?></td>
          <td  colspan="2" id="dato1"><strong>TELEFONO : </strong><?php echo $row_cliente_r['telefono_c']; ?></td>
        </tr>
        <tr>
          <td colspan="3" id="dato1"><strong>DIRECCION COMERCIAL:</strong><?php $dir =htmlentities($row_cliente_r['direccion_c']);echo $dir;  ?></td>
        </tr>
        <tr>
          <td colspan="3" id="dato1"><strong>DIRECCION ENVIO FACTURA:</strong><?php $dir2 =htmlentities($row_cliente_r['direccion_envio_factura_c']);echo $dir2;  ?></td>
        </tr>
        <tr>
          <td id="dato1"><strong>CONTACTO COMERCIAL : </strong><?php echo $row_cliente_r['contacto_c']; ?></td>
          <td   id="dato1"><strong>FAX : </strong><?php echo $row_cliente_r['fax_c']; ?></td>
          <td id="dato1"><strong>ORDEN.C:</strong> <a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_cliente_r['str_numero_oc'] ?>&id_oc=<?php echo $row_cliente_r['id_c_oc'] ?>" target="_blank" ><?php echo $row_remision3['str_numero_io']; ?></a></td>
        </tr>
        <tr>
          <td id="dato1"><strong>EMAIL COMERCIAL: </strong><?php echo $row_cliente_r['email_comercial_c']; ?></td>
          <td  colspan="2" id="dato1"><strong>CONDICIONES DE PAGO : </strong><?php echo $row_cliente_r['str_condicion_pago_oc']; ?></td>
        </tr>

      </table>
      <?php if($row_remision['id_rd']==''&&$row_remision2['id_rd']==''){
        echo"<div id='numero2'>LA REMISION NO CONTIENE REFERENCIAS REMISIONADAS O ESTA EN ESTADO 0</div>";
      } //TERMINA TO EL CICLE DE IMPRESION Y EL ALERTA) //PARA CONTROLAR LA ALERTA DE TODO SI ESTAN VACIAS?>
      <?php if($row_remision['id_rd']!=''){ ?>
      <table id="tabla3">
        <tr>
          <td colspan="13" id="nivel2">RELACION REMISION - O.C.</td>
        </tr>
        <tr>
          <td nowrap="nowrap" id="nivel2">REF. AC </td>
          <td nowrap="nowrap" id="nivel2">REF. CLIENTE</td>
          <td colspan="2" id="nivel2">N. CAJAS</td>
          <td colspan="3" id="nivel2">DESDE</td>
          <td colspan="2" id="nivel2">HASTA</td>
          <td id="nivel2">UNIDADES</td>
          <td id="nivel2">PESO</td> 
          <td id="nivel2">IPUU</td>
          <td nowrap="nowrap" id="nivel2"><strong>PRECIO / PESOS</strong></td>
        </tr>   
        <?php do { ?>
        <tr>
          <!--VARIABLES DE REMISION X ITEMS-->   
          <td id="fondo2"><?php echo $row_remision['int_ref_io_rd']; ?></td>
          <td nowrap="nowrap" id="fondo2"><?php echo $row_remision['int_cod_cliente_io']; ?></td>
          <td colspan="2" id="fondo2"><?php echo $row_remision['int_total_cajas_rd']; ?></td>
          <td colspan="3" id="fondo2"><?php echo $row_remision['int_numd_rd']; ?></td>
          <td colspan="2" id="fondo2"><?php echo $row_remision['int_numh_rd']; ?></td>
          <td id="fondo2"><?php $cant=$row_remision['int_cant_rd'];echo number_format($cant, 2, ",", "."); ?></td>
          <td id="fondo2"><?php echo number_format($row_remision['int_peso_rd'], 2, ",", "."); ?></td> 
          <td id="fondo2"><?php echo $row_remision['impuesto']==1 ? 'SI': 'NO'; ?></td>
          <td nowrap="nowrap" id="fondo2"><strong><?php echo number_format($row_remision['N_precio_old'], 2, ",", "."); $total_pre=$row_remision['N_precio_old']; ?></strong></td>
        </tr>
        <tr>
      <!-- <td colspan="2" style="text-align: left;" nowrap="nowrap" id="fondo2">N. CAJAS</td> 
        <td colspan="2" id="fondo2"></td>-->
      <!--<td colspan="3" id="detalle4">&nbsp;</td>      
      <td colspan="2" nowrap="nowrap" id="fondo2">Sub Totales:</td>
      <td id="fondo2"></td>
      <td id="fondo2"></td> -->
      <?php  $row_remision['int_total_cajas_rd'];$total_c=$total_c+$row_remision['int_total_cajas_rd']; ?>
      <?php number_format($row_remision['int_cant_rd'], 2, ",", "."); $totalrm=$totalrm+$row_remision['int_cant_rd']; ?>
      <?php number_format($row_remision['int_peso_rd'], 2, ",", "."); $peso=$peso+$row_remision['int_peso_rd']; ?>
      <td id="fondo2">&nbsp; </td> 
    </tr>    
    <?php } while ($row_remision = mysql_fetch_assoc($remision)); ?> 

    <tr> 
      <td colspan="2" nowrap="nowrap" id="nivel2">TOTAL CAJAS</td>
      <td colspan="2" id="detalle2"><strong>
        <?php  echo $total_c; ?>
      </strong></td>
      <td colspan="3" id="detalle4">&nbsp;</td>
      <td colspan="2" id="nivel2">TOTALES</td>
      <td id="detalle2"><strong><?php echo number_format($totalrm, 2, ",", "."); ?></strong></td>
      <td id="detalle2"><strong><?php echo number_format($peso, 2, ",", "."); ?></strong></td> 
    </tr>
    <tr>
      <!-- <td nowrap="nowrap" id="nivel2">ORDEN.C</td> -->
      <td id="nivel2">ITEM N&deg;</td>
      <td colspan="2" nowrap="nowrap" id="nivel2">REF. AC</td> 
      <td colspan="2" nowrap="nowrap" id="nivel2">FECHA DE ENTREGA</td>
      <td colspan="6" id="nivel2">CANTIDAD</td>
      <!-- <td colspan="2" nowrap="nowrap" id="nivel2">CANTIDAD RESTANTE</td>
      <td nowrap="nowrap" id="nivel2">MEDIDA</td>
      <td nowrap="nowrap" id="nivel2">MONEDA</td> -->
    </tr> 
    <?php do { ?>
    <tr>
     <!--FIN VARIABLES DE REMISION X ITEMS-->   
     <!-- <td nowrap="nowrap" id="fondo2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_cliente_r['str_numero_oc'] ?>&id_oc=<?php echo $row_cliente_r['id_c_oc'] ?>" target="_blank" ><?php echo $row_remision3['str_numero_io']; ?></a></td> -->
     <td id="fondo2"><?php echo $row_remision3['int_consecutivo_io']; ?></td>
     <td colspan="2" nowrap="nowrap" id="fondo2"><?php echo $row_remision3['int_cod_ref_io']; ?></td>
     <td colspan="2" nowrap="nowrap" id="fondo2"><?php echo $row_remision3['fecha_entrega_io']; ?></td>
     <td colspan="6" id="fondo2"><?php echo $row_remision3['int_cantidad_io']; ?></td>
     <!-- <td colspan="2" nowrap="nowrap" id="fondo2"><?php if($row_remision3['int_cantidad_rest_io']==''){ '0';}else{ $row_remision3['int_cantidad_rest_io'];} ?></td>
     <td id="fondo2"><?php  $row_remision3['str_unidad_io']; ?></td>
     <td id="fondo2"><?php  $row_remision3['str_moneda_io']; ?></td> -->
   </tr>    
   <?php } while ($row_remision3 = mysql_fetch_assoc($remision3)); ?> 
   
   <tr> 
    <td nowrap="nowrap" id="nivel2"><strong>TOTAL ITEM $</strong></td>
    <td nowrap="nowrap" colspan="10" id="nivel2">DIRECCION ENTREGA</td> 
    <td nowrap="nowrap" id="nivel2">FACTURADO</td> 
  </tr>  
  <?php do { ?>
  <tr>  
    <td nowrap="nowrap" id="fondo2"><strong><?php  $tota=$row_remision4['int_total_item_io'];echo number_format($tota, 2, ",", ".");  ?></strong></td>
    <td colspan="9" style="text-align: left;" id="fondo2"><?php echo  ($row_remision4['str_direccion_desp_rd']); ?></td>
    <td style="text-align: center;" nowrap="nowrap" id="fondo3"><?php if($row_remision4['b_estado_io']=='1'){echo "Ingresado";} else if($row_remision4['b_estado_io']=='5'){echo "Facturado Total";}else if($row_remision4['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_remision4['b_estado_io']=='2'){echo "Programado";}else if($row_remision4['b_estado_io']=='3'){echo "Remisionado";}else if($row_remision4['b_estado_io']=='6'){echo "Muestras reposicion";}  ?></td>        
  </tr>

  <tr>
    <td id="detalle4"><?php $total_c=$total_c+$row_remision4['int_total_cajas_rd']; ?></td> 
    <td id="detalle4"><?php $totalrm=$totalrm+$row_remision4['int_cant_rd']; ?></td>
    <td colspan="7" id="detalle4"><?php $peso=$peso+$row_remision4['int_peso_rd']; ?></td>
    <td id="detalle4"><?php $peson=$peson+$row_remision4['int_pesoneto_rd']; ?></td> 
    <td nowrap="nowrap" id="fondo2"><strong>sub total $</strong></td>
    <td id="fondo2"><strong><?php 
    $subtotal=$row_remision4['int_cant_rd']*$row_remision4['N_precio_old'];
    echo number_format($subtotal, 2, ",", "."); 
    ?>        <?php $acumula+=$subtotal; ?></td> 
  </tr>    
  <?php } while ($row_remision4 = mysql_fetch_assoc($remision4)); ?> 
  <?php //do { ?></strong>
  <tr> 
   <td colspan="5" id="detalle4"><strong>
     <?php  $total_c; ?>
   </strong></td>  
   <td colspan="5" id="detalle4">&nbsp;</td>
   <td id="nivel2"><strong>TOTAL $</strong></td>
   <td id="detalle2"><strong><?php echo number_format($acumula, 2, ",", ".");?></strong>
    </td>
 </tr>
 <tr>
  <td colspan="12" >&nbsp;</td>
  </tr> 
 <tr>
  <td colspan="6"id="dato1"><strong>FECHA CIERRE FACTURACION:</strong> <?php echo $row_cliente_r['fecha_cierre_fac'] ?></td>
  <td colspan="6"id="dato1"><label for="cobra_flete">COBRA FLETE:</label> 
   <b> <?php echo $row_cliente_r['cobra_flete'] == 1 ?  "SI" : 'NO'; ?>   <?php echo 'valor: ' .$row_cliente_r['precio_flete']?> </b></td>
</tr>

<tr>
  <td colspan="6" id="dato1"><strong>SE ENTREGA FACTURA : </strong> <?php echo $row_cliente_r['entrega_fac']; ?></td>
  <td colspan="6" id="dato1"><strong>ADJUNTAR COMPROBANTE : </strong> <?php echo $row_cliente_r['comprobante_ent']; ?></td>
</tr>
 <tr>
   <td colspan="12" id="dato1">Comprobante de Entrega: 
    <a href="javascript:verFoto('Archivosdesp/<?php echo $row_cliente_r['comprobante_file'];?>','610','490')"> 
     <?php if($row_cliente_r['comprobante_file']!='') {echo "VER COMPROBANTE";} ?>
    </a>
   </td>
</tr>

<tr>
  <td colspan="9" id="fondo1"><br><br><br><strong>OBSERVACIONES </strong></td>
  <td id="fondo1"><?php if($row_cliente_r['str_archivo_oc']==''){echo "Sin archivo";}else{echo "Archivo:";}?></td>
  <td nowrap id="fondo1">Enviar facturas:</td>
  <td nowrap id="fondo1">Control de numeracion:</td>
</tr>
<tr>
  <td colspan="9" id="nivel1" rowspan="3"><?php /*if($_GET['mostrar']=='1'){*/ echo   $row_cliente_r['str_observacion_r']; /*}*/ ?></td>
  <td id="fondo1"><?php if($row_cliente_r['str_archivo_oc']!=''){?><a href="javascript:verFoto('pdfacturasoc/<?php  $muestra=$row_cliente_r['str_archivo_oc'];echo $muestra;?>','610','490')"> <?php echo "ARC.";?></a><?php }?></td>      
  <td nowrap id="fondo2"><strong>
    <?php if ($row_cliente_r['b_facturas_oc']=='0'){echo "NO";}else {echo "SI";}?>
  </strong></td>
  <td nowrap id="fondo2"><strong>
    <?php if ($row_cliente_r['b_num_remision_oc']=='0'){echo "NO";}else {echo "SI";}?>
  </strong></td>
</tr>
</table>
<table id="tabla3">
  <tr>
    <td id="nivel2">VENDEDOR O.C</td>
    <td id="nivel2">ELABORADO POR </td>
    <td id="nivel2">APROBADO POR </td>
    <td colspan="2" id="nivel2"><p>GUIA N&deg;</p></td>
    <!-- <td id="nivel2">FIRMA &amp; SELLO ACYCIA </td> -->
  </tr>
  <tr>
    <td id="detalle2">
     <?php 
     $idoc = $row_cliente_r['str_numero_oc'];
       $row_vendio = $conexion->llenarCampos("vendedor ver", "LEFT JOIN tbl_items_ordenc itm on  ver.id_vendedor=itm.int_vendedor_io WHERE itm.str_numero_io='$idoc'","","distinct ver.nombre_vendedor");   
        echo $row_vendio['nombre_vendedor'];
    ?>
    </td>
    <td id="detalle2"><?php echo $row_cliente_r['str_elaboro_r']; ?></td>
    <td id="detalle2"><?php echo $row_cliente_r['str_aprobo_r']; ?></td>
    <td colspan="2"  id="detalle2"><strong><?php echo $row_cliente_r['str_guia_r']; ?></strong></td>
    <!-- <td id="detalle2">&nbsp;</td> -->
  </tr>
</table>
<table id="tabla3">
  <tr>
    <td id="fondo1">CODIGO : A3 - F02</td>
    <td id="fondo2">Favor citar este numero de Orden de Compra en la Factura.</td>
    <td id="fondo3">VERSION : 0</td>
  </tr>
</table>
<?php }?>


 

<!-- AQUI EMPIEZA SI LA REF ES DE MATERIA PRIMA-->
<?php if($row_remision2['id_rd']!=''){ ?>
<table id="tabla3">
  <tr>
    <td colspan="12" id="nivel2">RELACION REMISION - O.C.</td>
  </tr>
  <tr> 
    <td nowrap="nowrap"id="nivel2">REF. MP </td>
    <td nowrap="nowrap"id="nivel2">REF. AC </td>
    <td nowrap="nowrap"id="nivel2">REF. CLIENTE</td>
    <td id="nivel2">RANGOS</td>
    <td id="nivel2">DESDE</td>
    <td id="nivel2">HASTA</td>
    <td id="nivel2">UNIDADES</td>
    <td id="nivel2">IPUU</td>
    <td id="nivel2">PESO</td> 
  </tr>   
  <?php do { ?>
  <tr>
    <!--VARIABLES DE REMISION X ITEMS--> 
    <td id="fondo2"><?php $mp=$row_remision2['id_mp_vta_io'];
    if($mp!='')
    {
      $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
      $resultmp= mysql_query($sqlmp);
      $nump= mysql_num_rows($resultmp);
      if($nump >='1')
      { 
        $nombre_mp = mysql_result($resultmp,0,'str_nombre');
        echo $nombre_mp;
      } }else {echo "N.A";} ?></td>     
      <td id="fondo2"><?php echo $row_remision2['int_ref_io_rd']; ?></td>
      <td nowrap="nowrap"id="fondo2"><?php echo $row_remision2['int_cod_cliente_io']; ?></td>
      <td id="fondo2"><?php echo $row_remision2['int_caja_rd']; ?></td>
      <td id="fondo2"><?php echo $row_remision2['int_numd_rd']; ?></td>
      <td id="fondo2"><?php echo $row_remision2['int_numh_rd']; ?></td>
      <td id="fondo2"><?php $cant=$row_remision2['int_cant_rd'];echo number_format($cant, 2, ",", "."); ?></td>
      <td id="fondo2"><?php echo $row_remision2['impuesto']==1 ? 'SI': 'NO'; ?></td>
      <td id="fondo2"><?php echo number_format($row_remision2['int_peso_rd'], 2, ",", "."); ?></td> 

    </tr>
    <tr>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="fondo2">RANGOS</td>
      <td id="fondo2"><?php echo $row_remision2['int_caja_rd'];$total_c=$total_c+$row_remision2['int_total_cajas_rd']; ?></td>
      <td id="detalle4">&nbsp;</td>      
      <td nowrap="nowrap" id="fondo2">Sub Totales:</td>
      <td id="fondo2"><?php echo number_format($row_remision2['int_cant_rd'], 2, ",", "."); $totalrm=$totalrm+$row_remision2['int_cant_rd']; ?></td>
      <td id="fondo2"><?php echo number_format($row_remision2['int_peso_rd'], 2, ",", "."); $peso=$peso+$row_remision2['int_peso_rd']; ?></td>
      <td id="fondo2"><?php echo number_format($row_remision2['int_pesoneto_rd'], 2, ",", ".");$peson=$peson+$row_remision2['int_pesoneto_rd']; ?></td>
    </tr>    
    <?php } while ($row_remision2 = mysql_fetch_assoc($remision2)); ?> 

    <tr>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      <td id="nivel2">TOTAL CAJAS</td>
      <td id="detalle2"><strong>
        <?php  echo $total_c; ?>
      </strong></td>
      <td id="detalle4">&nbsp;</td>
      <td id="nivel2">TOTALES</td>
      <td id="detalle2"><strong><?php echo number_format($totalrm, 2, ",", "."); ?></strong></td>
      <td id="detalle2"><strong><?php echo number_format($peso, 2, ",", "."); ?></strong></td>
      <td colspan="3" id="detalle2"><strong><?php echo number_format($peson, 2, ",", "."); ?></strong></td>
    </tr>


    <tr>
      <td nowrap="nowrap" id="nivel2">ORDEN.C</td>
      <td id="nivel2">ITEM N&deg;</td>
      <td id="nivel2">REF. AC</td>
      <td id="nivel2">REF. MP</td> 
      <td  colspan="2" id="nivel2">FECHA DE ENTREGA</td>
      <td id="nivel2">CANTIDAD</td>
      <td id="nivel2">CANTIDAD RESTANTE</td>
      <td nowrap="nowrap" id="nivel2">MEDIDA</td>
      <td nowrap="nowrap" id="nivel2">MONEDA</td>

    </tr> 
    <?php do { ?>
    <tr>
     <!--FIN VARIABLES DE REMISION X ITEMS-->   
     <td nowrap="nowrap"id="fondo2"><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_cliente_r['str_numero_oc'] ?>&id_oc=<?php echo $row_cliente_r['id_c_oc'] ?>" target="_blank" ><?php echo $row_remision5['str_numero_io']; ?></a></td>
     <td id="fondo2"><?php echo $row_remision5['int_consecutivo_io']; ?></td>
     <td id="fondo2"><?php echo $row_remision5['int_cod_ref_io']; ?></td>
     <td id="fondo2"><?php $mp=$row_remision5['id_mp_vta_io'];
     if($mp!='')
     {
       $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
       $resultmp= mysql_query($sqlmp);
       $nump= mysql_num_rows($resultmp);
       if($nump >='1')
       { 
         $nombre_mp = mysql_result($resultmp,0,'str_nombre');
         echo $nombre_mp;
       } }else {echo "N.A";} ?></td> 
       <td colspan="2" id="fondo2"><?php echo $row_remision5['fecha_entrega_io']; ?></td>
       <td id="fondo2"><?php echo $row_remision5['int_cantidad_io']; ?></td>
       <td id="fondo2"><?php if($row_remision5['int_cantidad_rest_io']==''){echo '0';}else{echo $row_remision5['int_cantidad_rest_io'];} ?></td>
       <td id="fondo2"><?php echo $row_remision5['str_unidad_io']; ?></td>
       <td  id="fondo2"><?php echo $row_remision5['str_moneda_io']; ?></td>


     </tr>    
     <?php } while ($row_remision5 = mysql_fetch_assoc($remision5)); ?> 


     <tr> 
      <td nowrap="nowrap" id="nivel2">PRECIO / TRM</td>
      <td nowrap="nowrap" id="nivel2">PRECIO / PESOS</td>
      <td nowrap="nowrap" id="nivel2">TOTAL ITEM $</td>
      <td colspan="5" id="nivel2">DIRECCION ENTREGA</td> 
      <td colspan="3" nowrap="nowrap"id="nivel2">FACTURADO</td> 
    </tr>  
    <?php do { ?>
    <tr> 
      <td id="fondo2"><?php echo $row_remision6['int_precio_trm']; ?></td>
      <td nowrap="nowrap"id="fondo2"><?php echo number_format($row_remision6['N_precio_old'], 2, ",", "."); ?></td>
      <td id="fondo2"><?php  $tota=$row_remision6['int_total_item_io'];echo number_format($tota, 2, ",", ".");  ?></td>
      <td colspan="5" id="fondo2"><?php echo $row_remision6['str_direccion_desp_rd']; ?></td>
      <td nowrap="nowrap"id="fondo3"><?php if($row_remision6['b_estado_io']=='1'){echo "Ingresado";} else if($row_remision6['b_estado_io']=='5'){echo "Facturado Total";}else if($row_remision6['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_remision6['b_estado_io']=='2'){echo "Programado";}else if($row_remision6['b_estado_io']=='3'){echo "Remisionado";}  ?></td> 
      <td id="fondo2"><?php echo $row_remision3['trm']; ?></td>       
    </tr>
    <tr>
      <td id="detalle4"><?php $total_c=$total_c+$row_remision6['int_total_cajas_rd']; ?></td>
      <td id="detalle4">&nbsp;</td>      
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4"><?php $totalrm=$totalrm+$row_remision6['int_cant_rd']; ?></td>
      <td id="detalle4"><?php $peso=$peso+$row_remision6['int_peso_rd']; ?></td>
      <td id="detalle4"><?php $peson=$peson+$row_remision6['int_pesoneto_rd']; ?></td>
      <td id="detalle4">&nbsp;</td>
      <td id="detalle4">&nbsp;</td>
      
      <td nowrap="nowrap" id="fondo2">sub total $</td>
      <td id="fondo2"><?php 
      $subtotal=$row_remision6['int_cant_rd']*$row_remision6['N_precio_old'];
      echo number_format($subtotal, 2, ",", "."); 
      ?>        <?php $acumula+=$subtotal; ?></td>
      <td id="detalle4">&nbsp;</td>
    </tr>    
    <?php } while ($row_remision6 = mysql_fetch_assoc($remision6)); ?> 

    <?php //do { ?>
    <tr> 
     <td colspan="5" id="detalle4">&nbsp;</td> 
     <td colspan="5" id="detalle4"><strong>
       <?php  $total_c; ?>
     </strong></td>  
     <td id="nivel2">TOTAL $</td>
     <td id="detalle2"><strong><?php echo number_format($acumula, 2, ",", ".");?></strong></td>
   </tr>
   <tr>
    <td colspan="7">&nbsp;</td>
  </tr>


  <tr>
    <td colspan="9" id="fondo1"><strong>OBSERVACIONES </strong></td>
    <td id="fondo1"><?php if($row_cliente_r['str_archivo_oc']==''){echo "Sin archivo";}else{echo "Archivo:";}?></td>
    <td nowrap id="fondo1">Enviar facturas:</td>
    <td nowrap id="fondo1">Control de numeracion:</td>
  </tr>
  <tr>
    <td colspan="9" id="fondo1"rowspan="3">- <?php if($_GET['mostrar']=='1'){ echo utf8_decode($row_cliente_r['str_observacion_r']);
     }?> - </td>
    <td id="fondo1"><?php if($row_cliente_r['str_archivo_oc']!=''){?><a href="javascript:verFoto('pdfacturasoc/<?php  $muestra=$row_cliente_r['str_archivo_oc'];echo $muestra;?>','610','490')"> <?php echo "ARC.";?></a><?php }?></td>      
    <td nowrap id="fondo2"><strong>
      <?php if ($row_cliente_r['b_facturas_oc']=='0'){echo "NO";}else {echo "SI";}?>
    </strong></td>
    <td nowrap id="fondo2"><strong>
      <?php if ($row_cliente_r['b_num_remision_oc']=='0'){echo "NO";}else {echo "SI";}?>
    </strong></td>
  </tr>
</table>
<table id="tabla3">
  <tr>
    <td id="nivel2">VENDEDOR O.C</td>
    <td id="nivel2">ELABORADO POR </td>
    <td id="nivel2">APROBADO POR </td>
    <td colspan="2" id="nivel2"><p>GUIA N&deg;</p></td>
    <!-- <td id="nivel2">FIRMA &amp; SELLO ACYCIA </td> -->
  </tr>
  <tr>
    <td id="detalle2">
      <?php 
      $idoc = $row_cliente_r['str_numero_oc'];
        $row_vendio = $conexion->llenarCampos("vendedor ver", "LEFT JOIN tbl_items_ordenc itm on  ver.id_vendedor=itm.int_vendedor_io WHERE itm.str_numero_io='$idoc'","","distinct ver.nombre_vendedor");   
         echo $row_vendio['nombre_vendedor'];
     ?></td>
    <td id="detalle2"><?php echo $row_cliente_r['str_elaboro_r']; ?></td>
    <td id="detalle2"><?php echo $row_cliente_r['str_aprobo_r']; ?></td>
    <td colspan="2" id="detalle2"><strong><?php echo $row_cliente_r['str_guia_r']; ?></strong>
    </td>
    <!-- <td id="detalle2">&nbsp;</td> -->
  </tr>
</table>
<table id="tabla3">
  <tr>
    <td id="fondo1">CODIGO : A3 - F02</td>
    <td id="fondo2">Favor citar este numero de Orden de Compra en la Factura.</td>
    <td id="fondo3">VERSION : 0</td>
  </tr>
</table>
<?php }?>
</td>
</tr>
</table>
</div>
</body>
</html>

<script>
 $(document).ready(function(){

   var editar =  "<?php echo $_SESSION['no_edita'];?>";
   if(editar==0){

     $("input").attr('disabled','disabled');
     $("textarea").attr('disabled','disabled');
     $("select").attr('disabled','disabled'); 

     $('a').each(function() { 
       $(this).attr('href', '#');
     });
              //swal("No Autorizado", "Sin permisos para editar :)", "error"); 
   }
 });
</script> 

<?php
mysql_free_result($usuario);

mysql_free_result($cliente_r);

mysql_free_result($remision);

mysql_free_result($remision2);

mysql_free_result($vendedores);
?>