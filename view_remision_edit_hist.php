<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php
include('funciones/adjuntar.php');
require_once('envio_correo/envio_correos.php'); 

require_once("db/db.php");
require_once 'Models/Mremision.php';
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);

  $logoutGoTo = "usuario.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
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
 
//ENVIO DE E-MAIL A FACTURACION
  $oc=$_POST['str_numero_oc_r'];
  $idc_oc=$_POST['id_c_oc'];
 

      $colname_usuario = "-1";
      if (isset($_SESSION['MM_Username'])) {
        $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
      }
      mysql_select_db($database_conexion1, $conexion1);
      $query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
      $usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
      $row_usuario = mysql_fetch_assoc($usuario);
      $totalRows_usuario = mysql_num_rows($usuario);
//ASIGNA NUMERO CONSECUTIVO DE REMISION
      $colname_remision= "-1";
      if (isset($_GET['int_remision'])) {
        $colname_remision = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
      }
      mysql_select_db($database_conexion1, $conexion1);
      $query_remision = sprintf("SELECT * FROM Tbl_remisiones WHERE int_remision=%s", $colname_remision);
      $remision = mysql_query($query_remision, $conexion1) or die(mysql_error());
      $row_remision = mysql_fetch_assoc($remision);
      $totalRows_remision = mysql_num_rows($remision);
//REMISIONES
      $colname_remision_detalle = "-1";
      if (isset($_GET['int_remision'])) {
        $colname_remision_detalle = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
      }
      mysql_select_db($database_conexion1, $conexion1);
      $query_remision_detalle = sprintf("SELECT * FROM Tbl_remision_detalle,Tbl_items_ordenc WHERE Tbl_remision_detalle.int_remision_r_rd = %s AND Tbl_remision_detalle.int_item_io_rd=Tbl_items_ordenc.id_items ORDER BY Tbl_remision_detalle.id_rd, Tbl_remision_detalle.int_total_cajas_rd DESC ", $colname_remision_detalle);
      $remision_detalle = mysql_query($query_remision_detalle, $conexion1) or die(mysql_error());
      $row_remision_detalle = mysql_fetch_assoc($remision_detalle);
      $totalRows_remision_detalle = mysql_num_rows($remision_detalle);
//imprime total cajas
      if($totalRows_remision_detalle >='1'){
        $total_c = mysql_result($remision_detalle, 0, 'int_total_cajas_rd');
        $total_cajas=$total_cajas+$total_c;
      }
//REMISIONES NOMBRE REF CLIENTE
      $colname_rc = "-1";
      if (isset($_GET['int_remision'])) {
        $colname_rc = (get_magic_quotes_gpc()) ? $_GET['int_remision'] : addslashes($_GET['int_remision']);
      }
      mysql_select_db($database_conexion1, $conexion1);
      $query_rc = sprintf("SELECT * FROM Tbl_remision_detalle,Tbl_refcliente WHERE Tbl_remision_detalle.int_remision_r_rd = %s AND Tbl_remision_detalle.int_ref_io_rd=Tbl_refcliente.int_ref_ac_rc", $colname_rc);
      $remision_rc = mysql_query($query_rc, $conexion1) or die(mysql_error());
      $row_remision_rc = mysql_fetch_assoc($remision_rc);
      $totalRows_remision_rc = mysql_num_rows($remision_rc);
//TODA LA INFO DE ORDEN CON ITEMS
      $colname_orden_r = "-1";
      if (isset($_GET['str_numero_r'])) {
        $colname_orden_r = (get_magic_quotes_gpc()) ? $_GET['str_numero_r'] : addslashes($_GET['str_numero_r']);
      }
      mysql_select_db($database_conexion1, $conexion1);
      $query_orden_compra =sprintf("SELECT * FROM Tbl_orden_compra,cliente WHERE Tbl_orden_compra.str_numero_oc='%s' AND Tbl_orden_compra.id_c_oc=cliente.id_c ", $colname_orden_r);
      $orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
      $row_orden_compra = mysql_fetch_assoc($orden_compra);
      $totalRows_orden_compra = mysql_num_rows($orden_compra);
//ITEMS O.C
      $colname_items = "-1";
      if (isset($_GET['str_numero_r'])) {
        $colname_items = (get_magic_quotes_gpc()) ? $_GET['str_numero_r'] : addslashes($_GET['str_numero_r']);
      }
      mysql_select_db($database_conexion1, $conexion1);
      $query_items = sprintf("SELECT * FROM Tbl_items_ordenc WHERE str_numero_io = '%s' ORDER BY id_items ASC ", $colname_items);
      $items = mysql_query($query_items, $conexion1) or die(mysql_error());
      $row_items = mysql_fetch_assoc($items);
      $totalRows_items = mysql_num_rows($items);

      ?>
      <html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; harset=utf-8">
        <title>SISADGE AC &amp; CIA</title>
        <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
        <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

        <link href="css/formato.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/formato.js"></script>
        <script type="text/javascript" src="js/consulta.js"></script>
        <script type="text/javascript" src="js/listado.js"></script>
        <script type="text/javascript" src="js/validacion_numerico.js"></script> 
        <link rel="stylesheet" type="text/css" href="css/general.css"/>
        <script>
          function validar(){ 
            if (document.form1.oc.value=="1"){
             alert("NO SE GUARDARON LOS DATOS PORQUE EL NUMERO DE ORDEN YA EXISTE FAVOR HACER REVISION"); 
             return false;} 
             return true; 
           }
         </script>
 
</head>
<body>
  <div align="center">
    <table align="center" id="tabla"><tr align="center"><td>
      <div> 
        <b class="spiffy"> 
          <b class="spiffy1"><b></b></b>
          <b class="spiffy2"><b></b></b>
          <b class="spiffy3"></b>
          <b class="spiffy4"></b>
          <b class="spiffy5"></b></b>
          <div class="spiffy_content">
            <table id="tabla1"><tr>
              <td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
              <tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
                <td id="cabezamenu"><ul id="menuhorizontal">
                  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                  <li><a href="menu.php">MENU PRINCIPAL</a></li>
                </ul></td>
              </tr>  
              <tr>
                <td colspan="2" align="center" id="linea1"><form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="MM_validateForm('fecha_r','','R','str_encargado_r','','R','str_guia_r','','R','str_transportador_r','','R','str_elaboro_r','','R','str_aprobo_r','','R');return document.MM_returnValue">
                  <table id="tabla2">
                    <tr id="tr1">
                      <td nowrap id="codigo">CODIGO : A3 - F02</td>
                      <td nowrap id="titulo2">REMISION</td>
                      <td nowrap id="codigo">VERSION : 0</td>
                    </tr>
                    <tr>
                      <td rowspan="8" id="dato2" ><img src="images/logoacyc.jpg"></td>
                      <td id="subtitulo">&nbsp;</td>
                      <td id="dato2">
                       <?php
                       $str_numero_oc=$_GET['str_numero_r'];
                       $sql2="SELECT * FROM Tbl_remisiones,tbl_remision_detalle WHERE Tbl_remisiones.str_numero_oc_r='$str_numero_oc' AND Tbl_remisiones.str_numero_oc_r=tbl_remision_detalle.str_numero_oc_rd";
                       $result2= mysql_query($sql2);
                       $numRem = mysql_num_rows($result2);

                       if($numRem >='1')
                       { 
                        $existe_op ="1";
                      }else {
                        $existe_op="0";
                      }
          //CONTROL DE MENU ELIMINACION
                      
                        ?>  
                         <a href="despacho_oc.php"><img src="images/r.gif" style="cursor:hand;" alt="LISTADO_DESPACHOS" title="LISTADO DESPACHOS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/></td>
                      </tr>
                      <tr>
                        <td id="numero2">N&deg;
                          <input name="int_remision" type="text" readonly="readonly"size="15"class="rojo_inteso" value="<?php echo $row_remision['int_remision']; ?>" readonly/></td>
                          <td id="dato_1"><div id="resultado"></div><?php $oc=$_GET['oc'];?><input name='oc' type='hidden' value='<?php echo $oc; ?>'> </td>
                        </tr>
                        <tr>
                          <td id="fuente1">FECHA INGRESO</td>
                          <td id="fuente1">&nbsp; </td>
                        </tr>
                        <tr>
                          <td id="dato1"><input name="fecha_r" type="date" min="2000-01-02" value="<?php echo $row_remision['fecha_r']; ?>" size="10" autofocus /></td>
                          <td id="dato1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td id="fuente1">&nbsp;</td>
                          <td id="fuente1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="dato1">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="dato1">ORDEN DE COMPRA N&deg : <?php echo $row_remision['str_numero_oc_r']; ?></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="dato4">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" id="detalle2"><table id="tabla1"><!--se cambio tabla2 x tabla1-->
                            <tr>
                              <td colspan="2" id="dato1"><strong>CLIENTE: </strong>
                                <?php $cad4=htmlentities ($row_orden_compra['nombre_c']);echo $cad4; ?></td>
                                <td width="50%" colspan="2" id="dato1"><strong>PAIS / CIUDAD : </strong><?php $cad2=htmlentities ($row_remision['ciudad_pais']); ?>
                                  <input type="text" readonly="readonly" name="ciudad_pais" id="ciudad_pais" size="40" maxlength="200"  value="<?php echo $cad.' / '.$cad2;?>" onKeyUp="conMayusculas(this)">
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2" id="dato1"><strong>NIT : </strong><?php echo $row_orden_compra['nit_c']; ?></td>
                                <td colspan="2" id="dato1"><strong>TELEFONO:</strong><?php echo $row_orden_compra['telefono_c']; ?></td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato1"><strong>DIRECCCION COMERCIAL:</strong>
                                  <?php  $cade = htmlentities($row_orden_compra['str_dir_entrega_oc']); echo $cade; ?>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato1"><strong>DIRECCCION ENVIO FACTURA:</strong>
                                  <?php  $cade2 = htmlentities($row_orden_compra['direccion_envio_factura_c']); echo $cade2; ?>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato1"><strong>SALIDAS TIPO: </strong>
                                  <?php if (!(strcmp("0",$row_orden_compra['salida_oc']))){echo "Normal";} ?>
                                  <?php if (!(strcmp("1",$row_orden_compra['salida_oc']))){echo "Reposiciones";} ?>
                                  <?php if (!(strcmp("2",$row_orden_compra['salida_oc']))){echo "Muestras";} ?>
                                  <?php if (!(strcmp("3",$row_orden_compra['salida_oc']))){echo "Salidas especiales";} ?></td>
                                </tr>
                                <tr>
                                  <td width="14%" id="dato1"><strong>ENCARGADO:</strong> </td>
                                  <td width="36%" id="dato1"><input name="str_encargado_r" type="text" readonly="readonly" id="str_encargado_r" value="<?php echo $row_remision['str_encargado_r']; ?>" onKeyUp="conMayusculas(this)" required="required"></td>
                                  <td id="dato1"><strong>GUIA : </strong>              </td>
                                  <td id="dato1"><input type="text" readonly="readonly" name="str_guia_r" id="str_guia_r" size="15"value="<?php echo $row_remision['str_guia_r']; ?>" onKeyUp="conMayusculas(this)"  required="required"></td>
                                </tr>
                                <tr>
                                  <td id="dato1"><strong>TRANSPORTADOR:</strong></td>
                                  <td id="dato1"><strong>
                                    <input type="text" readonly="readonly" name="str_transportador_r" id="str_transportador_r"value="<?php echo $row_remision['str_transportador_r']; ?>"onKeyUp="conMayusculas(this)" required="required">
                                  </strong></td>
                                  <td id="dato1"><strong>DESPACHADO POR :

                                  </strong></td>
                                  <td id="dato1"><strong>
                                    <input name="str_elaboro_r" type="text" readonly="readonly" onKeyUp="conMayusculas(this)"id="str_elaboro_r" value="<?php echo $row_remision['str_elaboro_r']; ?>" size="15"required="required">
                                  </strong></td>
                                </tr>
                                <tr>
                                  <td id="dato1"><strong>APROBADO POR</strong></td>
                                  <td id="dato1"><input name="str_aprobo_r" type="text" readonly="readonly" id="str_aprobo_r" value="<?php echo $row_remision['str_aprobo_r']; ?>" onKeyUp="conMayusculas(this)" required></td>
                                  <td id="dato1"><strong>FACTURA:</strong></td>
                                  <td id="dato1"><input style="width: 140px" type="text" readonly="readonly" name="factura_r" id="factura_r" min="0" size="15" required value="<?php echo $row_remision['factura_r']; ?>"></td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato3">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato1"><strong>FECHA CIERRE FACTURACION:</strong> <?php echo $row_orden_compra['fecha_cierre_fac'] ?></td>
                                </tr>
                                <tr>
                                  <td colspan="2" id="dato1"><strong>SE ENTREGA FACTURA : </strong> <?php echo $row_orden_compra['entrega_fac']; ?></td>
                                  <td colspan="2" id="dato1"><strong>ADJUNTAR COMPROBANTE : </strong> <?php echo $row_orden_compra['comprobante_ent']; ?></td>
                                </tr>
                                <tr> 
                                  <td colspan="4" id="dato1">Adjuntar Comprobante de Entrega:  
                                    <input type="hidden" name="adjunto1" value="<?php echo $row_remision['comprobante_file'];?>"/>
                                    <a href="javascript:verFoto('Archivosdesp/<?php echo $row_remision['comprobante_file'];?>','610','490')"> 
                                      <?php if($row_remision['comprobante_file']!='') {echo "VER COMPROBANTE";}else{'Sin Adjunto';} ?>
                                      </a>
                                  </td>
                                </tr> 
                                <tr>
                                  <td colspan="4" id="dato1">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato1">
                                  <label for="cobra_flete"> COBRA FLETE:</label> 
                                  <b> <?php echo $row_orden_compra['cobra_flete'] == 1 ?  "SI" : 'NO'; ?>   <?php echo 'valor: ' .$row_orden_compra['precio_flete']?> </b>
                                </td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato1">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato1"><strong>OBSERVACIONES:</strong></td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato2"><textarea name="str_observacion_r" cols="70" onKeyUp="conMayusculas(this)" rows="2" id="str_observacion_oc"><?php echo $row_remision['str_observacion_r']; ?></textarea></td>
                                </tr>
                                <tr>
                                  <td colspan="4" id="dato1"></td>
                                </tr>
                              </table></td>
                            </tr>         
                            <tr>
                              <td colspan="4" id="dato2">REFERENCIAS DE O.C.</td>
                            </tr>
                            <tr>
                              <td colspan="4" id="dato2"> </td>

                            </tr><?php if (($row_items['id_items']!='')) { ?>
                            <tr id="tr2">
                              <td colspan="4" id="dato2"><table id="tabla1">
                                <tr>
                                  <td id="nivel2">ITEM</td>
                                  <td id="nivel2">REF. AC</td>
                                  <td id="nivel2">REF. MP</td>
                                  <td id="nivel2">REF. CLIENTE</td>
                                  <td id="nivel2">CANT.</td>
                                  <td id="nivel2">CANT. RESTANTE</td>
                                  <td id="nivel2">UNIDADES</td>
                                  <td id="nivel2">FECHA ENTREGA</td>
                                  <td id="nivel2">PRECIO / VENTA</td>
                                  <td id="nivel2">TOTAL ITEM</td>
                                  <td id="nivel2">MONEDA</td>
                                  <td id="nivel2">DIRECCION ENTREGA</td>
                                  <td nowrap="nowrap" id="nivel2">FACTURADO</td>
                                </tr>
                                <?php do { ?>
                                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF"> 
                                  <td id="talla2"><?php echo $row_items['int_consecutivo_io']; ?></a></td>
                                  <td id="talla2"><?php echo $row_items['int_cod_ref_io']; ?></a></td>
                                  <td id="talla2"><?php $mp=$row_items['id_mp_vta_io'];
                                  if($mp!='')
                                  {
                                    $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
                                    $resultmp= mysql_query($sqlmp);
                                    $nump= mysql_num_rows($resultmp);
                                    if($nump >='1')
                                    { 
                                      $nombre_mp = mysql_result($resultmp,0,'str_nombre');

                                    }  ?><?php echo $nombre_mp; }?></td>
                                    <td id="talla2"><?php echo $row_items['int_cod_cliente_io']; ?></td>
                                    <td id="talla2"><?php echo $row_items['int_cantidad_io']; ?></td>
                                    <td id="talla2"><?php if($row_items['int_cantidad_rest_io']==''){echo '0.00';}else{echo $row_items['int_cantidad_rest_io'];} ?></td>
                                    <td id="talla2"><?php echo $row_items['str_unidad_io']; ?></td>
                                    <td id="talla2"><?php echo $row_items['fecha_entrega_io']; ?></td>
                                    <td id="talla2"><?php echo $row_items['int_precio_io']; ?></td>
                                    <td id="talla2"><?php echo $row_items['int_total_item_io'];$total=$subtotal+$row_items['int_total_item_io'];?></td>
                                    <td id="talla2"><?php echo $row_items['str_moneda_io']; ?></td>
                                    <td id="talla2"><?php $ca = htmlentities($row_items['str_direccion_desp_io']); echo $ca; ?></td>
                                    <td nowrap="nowrap"id="talla2">
                                      <?php if($row_items['b_estado_io']=='5'){echo "Facturado Total";}else if($row_items['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_items['b_estado_io']=='1'){echo "Ingresado";}else if($row_items['b_estado_io']=='2'){echo "Programado";}else if($row_items['b_estado_io']=='3'){echo "Remisionado";}else if($row_items['b_estado_io']=='6'){echo "Muestras reposicion";}  ?>
                                    </td>
                                  </tr>
                                  <?php } while ($row_items = mysql_fetch_assoc($items)); ?>
                                  
                                  <tr>
                                    <td colspan="4" id="talla2">
                                    </td>
                                  </tr>

                                </table></td>
                              </tr><?php } ?>
                              <tr>
                                <td colspan="4" id="dato2">&nbsp;</td>
                              </tr>
                              <tr>
                               <td colspan="4" id="fuente2">REMISIONES</td>
                             </tr>
                             <?php if (($row_remision_detalle['id_rd']!='')) { ?>
                             <tr id="tr2">
                              <td colspan="4" id="dato2"><table id="tabla1">
                                <tr>
                                  <td id="nivel2"></td>
                                  <td id="nivel2">REF. AC</td>
                                  <td id="nivel2">REF. MP</td>
                                  <td id="nivel2">REF. CLIENTE</td>
                                  <td id="nivel2">DESCRIPCION</td>
                                  <td id="nivel2">CANTIDADES</td>
                                  <td id="nivel2">RANGOS</td>
                                  <td id="nivel2">NUM. DESDE</td>
                                  <td id="nivel2">NUM. HASTA</td>
                                  <td id="nivel2">PESO</td>  
                                  <td id="nivel2">PESO/NETO</td>
                                  <td nowrap="nowrap"id="nivel2">FACTURADO</td>                
                                </tr>
                                <?php do { ?>
                                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF"> 
                                  <td id="talla1"> </td>
                                  <td id="talla2"><input type="hidden" name="remision" id="remision" value="<?php echo $row_remision_detalle['int_remision_r_rd']; ?>">
                                    <input type="hidden" name="refac" id="refac" value="<?php  $r=$row_remision_detalle['int_ref_io_rd'];echo $r; ?>">
                                    <a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_ref_io_rd']; ?></a></td>

                                    <td id="talla2">                 
                                      <?php $mp=$row_items['id_mp_vta_io'];
                                      if($mp!='')
                                      {
                                        $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
                                        $resultmp= mysql_query($sqlmp);
                                        $nump= mysql_num_rows($resultmp);
                                        if($nump >='1')
                                        { 
                                          $nombre_mp = mysql_result($resultmp,0,'str_nombre');
                                        } } ?><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $nombre_mp;?></a><input type="hidden" name="rcl" id="rcl" value="<?php  $rc=$nombre_mp;echo $rc; ?>"></td>
                                        <td id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_cod_cliente_io']; ?></a></td>                  
                                        <td id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_rc['str_descripcion_rc']; ?></a></td>
                                        <td id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_cant_rd']; ?></a>
                                          <input type="hidden" name="cant" id="cant" value="<?php  $can=$row_remision_detalle['int_cant_rd'];echo $can; ?>"></td>
                                          <td id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_caja_rd'];$cajas=$cajas+$row_remision_detalle['int_total_cajas_rd']; ?></a></td>
                                          <td id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_numd_rd']; ?></a></td>
                                          <td id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_numh_rd'];$total=$subtotal+$row_remision_detalle['int_total_item_io'];?></a></td>
                                          <td id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_peso_rd']; $peso=$peso+$row_remision_detalle['int_peso_rd']; ?></a></td>
                                          <td id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_pesoneto_rd'];$peson= $peson+$row_remision_detalle['int_pesoneto_rd']; ?></a></td>
                                          <td nowrap="nowrap" id="talla2"><a href="javascript:verFoto('view_remision_detalle_hist.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000">
                                            <?php if( $row_remision_detalle['b_estado_io']=='5'){echo "Facturado Total";}else if( $row_remision_detalle['b_estado_io']=='4'){echo "Facturado Parcial";}else if( $row_remision_detalle['b_estado_io']=='1'){echo "Ingresado";}else if($row_remision_detalle['b_estado_io']=='2'){echo "Programado";}else if($row_remision_detalle['b_estado_io']=='3'){echo "Remisionado";}else if($row_items['b_estado_io']=='6'){echo "Muestras reposicion";}  ?>
                                          </a></td>
                                        </tr>
                                        <?php } while ($row_remision_detalle = mysql_fetch_assoc($remision_detalle)); ?>

                                      </table></td>
                                    </tr><?php } ?>
                                    <tr>
                                      <td colspan="13" id="fuente2"><?php 
                                       $muestra=$row_orden_compra['str_archivo_oc'];
                                        if($row_orden_compra['str_archivo_oc']==''){echo "Sin Archivo";}else{?><a href="javascript:verFoto('pdfacturasoc/<?php  echo $muestra;?>','610','490')"> <?php echo "ARCHIVO";?></a><?php } ?>
                                      </td>
                                    </tr>
                                    <tr>            
                                      <td colspan="4"id="fuente2"><strong>ESTADO DE LA ORDEN DE COMPRA</strong>
                                        <select name="b_estado_oc" id="b_estado_oc" onChange="ActualizarEstadosRemision(this)">
                                          <option value="3"<?php if (!(strcmp("3",$row_orden_compra['b_estado_oc']))){echo "selected=\"selected\"";} ?>>REMISIONADA</option>
                                          <option value="4"<?php if (!(strcmp("4",$row_orden_compra['b_estado_oc']))){echo "selected=\"selected\"";} ?>>FACTURADA PARCIAL</option>
                                          <option value="5"<?php if (!(strcmp("5",$row_orden_compra['b_estado_oc']))){echo "selected=\"selected\"";} ?>>FACTURADA TOTAL</option>
                                        </select>
                                      </td>
                                      </tr>
                                      <tr>
                                        <td colspan="4"id="nivel3"><strong>TOTAL CAJAS:</strong>
                                          <input type="text" readonly="readonly" name="FG" id="FG" size="5" value="<?php echo $cajas; ?>"></td>
                                        </tr>
                                        <tr>
                                          <td colspan="4"id="nivel3"><strong>TOTAL PESO:</strong>
                                            <input type="text" readonly="readonly" name="FG2" id="FG2" size="5" value="<?php echo $peso ?>"readonly></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"id="nivel3"><strong>TOTAL P/NETO:</strong>
                                              <input type="text" readonly="readonly" name="FG3" id="FG3" size="5"value="<?php echo $peson ?>"readonly></td>
                                            </tr>
                                            <tr>
                                              <td colspan="3" id="fuente2"><input type="checkbox" <?php echo "checked=\"checked\"";?> name="mostrar_ob" id="mostrar_ob" value="1">
                                                <label for="mostrar_ob">Mostrar observaciones</label></td>
                                              </tr>
                                              <tr>
                                                <td colspan="3" id="fuente1"></td>
                                              </tr> 
                                              <tr>
                                                <td colspan="3" id="fuente2"><input type="hidden" name="MM_update" value="form1">
                                                  <input type="hidden" name="b_borrado_r" id="b_borrado_r" value="0">
                                                  <input type="hidden" name="id_c_oc" id="id_c_oc" value="<?php echo $row_orden_compra['id_c_oc']; ?>">

                                                  <input type="hidden" name="str_numero_oc_r" id="str_numero_oc_r" value="<?php echo $row_remision['str_numero_oc_r']; ?>"> </td>
                                                </tr>
                                              </table>
                                            </form></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" align="center">&nbsp;</td>
                                          </tr>
                                        </table>
                                      </div>
                                      <b class="spiffy"> 
                                        <b class="spiffy5"></b>
                                        <b class="spiffy4"></b>
                                        <b class="spiffy3"></b>
                                        <b class="spiffy2"><b></b></b>
                                        <b class="spiffy1"><b></b></b></b></div> 
                                      </td></tr></table>
                                    </div>
                                  </body>
                                  </html>
                                  <?php
                                  mysql_free_result($usuario);mysql_close($conexion1);

                                  mysql_free_result($orden_compra);

                                  mysql_free_result($remision);

                                  mysql_free_result($remision_rc);

                                  mysql_free_result($remision_detalle);

                                  mysql_free_result($items);

                                  ?>