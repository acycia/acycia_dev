<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php

require_once("db/db.php");
require_once 'Models/Occomercial.php';

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
//LLAMADO A FUNCIONES
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
//FIN
 
//CONSECUTIVO
  $colname_ver_items= "-1";   
  if (isset($_GET['id_items'])){
    $colname_ver_items= (get_magic_quotes_gpc()) ? $_GET['id_items'] : addslashes($_GET['id_items']);}
    mysql_select_db($database_conexion1, $conexion1);
    $query_items =sprintf("SELECT * FROM Tbl_items_ordenc WHERE id_items=%s ORDER BY id_items ASC ",$colname_ver_items);
    $items = mysql_query($query_items, $conexion1) ;
    $row_items = mysql_fetch_assoc($items);
    $totalRows_items = mysql_num_rows($items);
//REFERENCIAS ACYCIA SE TRAE REF DEL CLIENTE
    $colname_ref_cliente= "-1";   
    if (isset($_GET['id_oc'])){
      $colname_ref_cliente= (get_magic_quotes_gpc()) ? $_GET['id_oc'] : addslashes($_GET['id_oc']);}
      mysql_select_db($database_conexion1, $conexion1);
      $query_referencias = sprintf("SELECT DISTINCT cliente.id_c,cliente.nit_c,Tbl_cliente_referencia.Str_nit,Tbl_cliente_referencia.N_referencia,Tbl_referencia.cod_ref,Tbl_referencia.estado_ref FROM cliente,Tbl_cliente_referencia,Tbl_referencia WHERE cliente.id_c =%s AND cliente.nit_c=Tbl_cliente_referencia.Str_nit AND Tbl_cliente_referencia.N_referencia = Tbl_referencia.cod_ref AND Tbl_referencia.estado_ref='1' ORDER BY Tbl_referencia.cod_ref ASC ",$colname_ref_cliente);
      $referencias = mysql_query($query_referencias, $conexion1) ;
      $row_referencias = mysql_fetch_assoc($referencias);
      $totalRows_referencias = mysql_num_rows($referencias);
//MATERIA PRIMA
      mysql_select_db($database_conexion1, $conexion1);
      $query_referencias3 ="SELECT id_mp_vta, str_nombre FROM Tbl_mp_vta  ORDER BY id_mp_vta ASC ";
      $referencias3 = mysql_query($query_referencias3, $conexion1) ;
      $row_referencias3 = mysql_fetch_assoc($referencias3);
      $totalRows_referencias3 = mysql_num_rows($referencias3);
//REFERENCIAS CLIENTES
      $colname_ref_cliente2= "-1";   
      if (isset($_GET['id_oc'])){
        $colname_ref_cliente2= (get_magic_quotes_gpc()) ? $_GET['id_oc'] : addslashes($_GET['id_oc']);}
        $colname_ref_cl= "-1";   
        if (isset($_GET['int_cod_ref_io'])){
          $colname_ref_cl= (get_magic_quotes_gpc()) ? $_GET['int_cod_ref_io'] : addslashes($_GET['int_cod_ref_io']);}
          mysql_select_db($database_conexion1, $conexion1);
          $query_referencias2 = sprintf("SELECT * FROM Tbl_refcliente WHERE id_c_rc=%s AND int_ref_ac_rc=%s AND  int_estado_ref_rc='1' ORDER BY str_ref_cl_rc ASC",$colname_ref_cliente2,$colname_ref_cl);
          $referencias2 = mysql_query($query_referencias2, $conexion1) ;
          $row_referencias2 = mysql_fetch_assoc($referencias2);
          $totalRows_referencias2 = mysql_num_rows($referencias2);
//IMRPIME EL NOMBRE DEL VENDEDOR
          mysql_select_db($database_conexion1, $conexion1);
          $query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
          $vendedores = mysql_query($query_vendedores, $conexion1) ;
          $row_vendedores = mysql_fetch_assoc($vendedores);
          $totalRows_vendedores = mysql_num_rows($vendedores);
//NOMBRE FORMULA
          mysql_select_db($database_conexion1, $conexion1);
          $query_nombre = "SELECT * FROM Tbl_formula_nombres  ORDER BY id ASC";
          $nombre = mysql_query($query_nombre, $conexion1) ;
          $row_nombre = mysql_fetch_assoc($nombre);
          $totalRows_nombre = mysql_num_rows($nombre);
//LISTADO COTIZACIONES
/*(SELECT Str_nit,N_referencia_c,N_cotizacion FROM Tbl_cotiza_bolsa WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY N_cotizacion DESC LIMIT 0,1)
   UNION (SELECT Str_nit,N_referencia_c,N_cotizacion FROM Tbl_cotiza_laminas WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY N_cotizacion DESC LIMIT 0,1) 
   UNION (SELECT Str_nit,N_referencia_c,N_cotizacion FROM Tbl_cotiza_packing WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY N_cotizacion DESC LIMIT 0,1)
   UNION (SELECT Str_nit,N_referencia_c,N_cotizacion FROM Tbl_cotiza_materia_p WHERE Str_nit='$nit_c' and Str_referencia='$codref' ORDER BY N_cotizacion DESC LIMIT 0,1)*/
   if (isset($_GET['nit_c'])||(isset($_GET['int_cod_ref_io']))){
    $nit_c=$_GET['nit_c'];
    $codref=$_GET['int_cod_ref_io'];
    mysql_select_db($database_conexion1, $conexion1);
    $query_listado = ("SELECT * FROM Tbl_cliente_referencia WHERE N_referencia='$codref' AND Str_nit='$nit_c' ORDER BY N_cotizacion DESC LIMIT 0,1");
    $listado = mysql_query($query_listado, $conexion1) ;
    $row_listado = mysql_fetch_assoc($listado);
    $totalRows_listado = mysql_num_rows($listado);}
//IMPRIME LA CANTIDAD DE LA ULTIMA COTIZACION
    if (isset($_GET['nit_c'])||(isset($_GET['int_cod_ref_io']))){
      $nit_c=$_GET['nit_c'];
      $codref=$_GET['int_cod_ref_io'];
      mysql_select_db($database_conexion1, $conexion1);
      $query_cotiz = ("(SELECT N_referencia_c,Str_nit,N_cant_impresion AS cantidad,N_precio AS N_precio, Str_unidad_vta, Str_moneda, fecha_creacion,Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_bolsa WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY fecha_creacion DESC LIMIT 0,1) 
               UNION (SELECT N_referencia_c,Str_nit,N_cantidad AS cantidad,N_precio_k AS N_precio,Str_unidad_vta, Str_moneda, fecha_creacion, Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_laminas WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY fecha_creacion DESC LIMIT 0,1) 
               UNION (SELECT N_referencia_c,Str_nit,N_cantidad AS cantidad, N_precio_vnta AS N_precio, Str_unidad_vta, Str_moneda, fecha_creacion,Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_packing WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY fecha_creacion DESC LIMIT 0,1) 
               UNION (SELECT N_referencia_c,Str_nit,N_cantidad AS cantidad,N_precio_vnta AS N_precio, Str_unidad_vta, Str_moneda, fecha_creacion,Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_materia_p WHERE Str_nit='$nit_c' and Str_referencia='$codref' ORDER BY fecha_creacion DESC LIMIT 0,1)");

               /*("(SELECT N_referencia_c,Str_nit,N_cant_impresion AS cantidad,N_precio, Str_unidad_vta, Str_moneda, fecha_creacion,Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_bolsa WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY fecha_creacion DESC LIMIT 0,1)
             UNION (SELECT N_referencia_c,Str_nit,N_cantidad AS cantidad,N_precio_k AS N_precio,Str_unidad_vta,Str_moneda,N_ancho,fecha_creacion, Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_laminas WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY fecha_creacion DESC LIMIT 0,1)
             UNION (SELECT N_referencia_c,Str_nit,N_cantidad AS cantidad,N_precio_vnta AS N_precio,Str_unidad_vta, N_ancho, Str_moneda, fecha_creacion,Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_packing WHERE Str_nit='$nit_c' and N_referencia_c='$codref' ORDER BY fecha_creacion DESC LIMIT 0,1) 
             UNION (SELECT N_referencia_c,Str_nit,N_cantidad AS cantidad,N_precio_vnta AS N_precio, Str_unidad_vta, Str_moneda, fecha_creacion,Str_usuario AS usuario, N_comision AS comision FROM Tbl_cotiza_materia_p WHERE Str_nit='$nit_c' and Str_referencia='$codref' ORDER BY N_cotizacion DESC LIMIT 0,1)");
*/
      $cotiz = mysql_query($query_cotiz, $conexion1) ;
      $row_cotiz = mysql_fetch_assoc($cotiz);
      $totalRows_cotiz = mysql_num_rows($cotiz);}
//REF CLIENTE
      $colname_refcliente = "-1";
      if (isset($_GET['int_cod_ref_io'])) {
        $colname_refcliente = (get_magic_quotes_gpc()) ? $_GET['int_cod_ref_io'] : addslashes($_GET['int_cod_ref_io']);
      }
      mysql_select_db($database_conexion1, $conexion1);
      $query_refcliente = sprintf("SELECT Tbl_refcliente.id_refcliente,Tbl_refcliente.int_ref_ac_rc,Tbl_refcliente.str_ref_cl_rc,Tbl_refcliente.str_descripcion_rc FROM Tbl_referencia,Tbl_refcliente WHERE Tbl_refcliente.int_ref_ac_rc='%s'", $colname_refcliente);
      $refcliente = mysql_query($query_refcliente, $conexion1) ;
      $row_refcliente = mysql_fetch_assoc($refcliente);
      $totalRows_refcliente = mysql_num_rows($refcliente);
      
//CONSULTA ORDEN COMPRA
      $colname_orden_compra = "-1";   
      if (isset($_GET['id_items'])){
        $colname_orden_compra= (get_magic_quotes_gpc()) ? $_GET['id_items'] : addslashes($_GET['id_items']);}
        mysql_select_db($database_conexion1, $conexion1);
        $query_orden_compra =sprintf("SELECT Tbl_orden_compra.id_c_oc FROM Tbl_items_ordenc,Tbl_orden_compra WHERE Tbl_items_ordenc.id_items='%s' AND Tbl_items_ordenc.str_numero_io=Tbl_orden_compra.str_numero_oc",$colname_orden_compra);
        $orden_compra = mysql_query($query_orden_compra, $conexion1) ;
        $row_orden_compra = mysql_fetch_assoc($orden_compra);
        $totalRows_orden_compra = mysql_num_rows($orden_compra);
        ?>
        <html>
        <head>
          <title>SISADGE AC &amp; CIA</title>
          <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
          <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">

          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
          <link href="css/formato.css" rel="stylesheet" type="text/css" />
          <script type="text/javascript" src="js/formato.js"></script>
          <script type="text/javascript" src="js/consulta.js"></script>
          <script type="text/javascript" src="js/validacion_numerico.js"></script>
          <script type="text/javascript" src="AjaxControllers/js/ordenCompraDet.js"></script>
 
          <!-- jquery -->
          <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
          <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
          <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
          <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
          <script src="https://code.jquery.com/jquery-3.5.0.js"></script>

          <script> 
            function cambio_restante(){
             var canti=document.form1.int_cantidad_io.value
             var cant_res=document.form1.int_cantidad_rest_io.value
             var usuario = "<?php echo $row_usuario['id_usuario'];?>";
             if(canti!=cant_res && (usuario!='26' && usuario!='23')){
               alert('Si quiere cambiar la cantidad restante, asegurese que no halla despachos de este item !') 
             }
           }
         </script>
         <script>
          function valida_envia(){ 
    //valido el nombre 
    if (document.form1.int_cod_ref_io.value.length==0){ 
      alert("Tiene que seleccionar una referencia") 
      document.form1.int_cod_ref_io.focus() 
      return 0; 
    } 


    //el formulario se envia 
    alert("Muchas gracias por enviar el formulario"); 
    document.form1.submit(); 
   }
 </script>

</head>
<body onLoad="itemsoc();">
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
              <tr><td id="nombreusuario"><?php echo $_SESSION['Usuario']; ?></td>
  <td id="cabezamenu"><!--<ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="compras.php">GESTION COMPRAS</a></li>
</ul>--></td>
</tr>  
<tr>
  <td colspan="2" align="center" id="linea1"><form action="<?php echo $editFormAction; ?>" method="post" name="form1" onsubmit="return submitform(); return itemsoc();"><!-- onSubmit="return existeop()"-->
    <table id="tabla2">
      <tr>
        <td colspan="3" id="subtitulo">AGREGAR ITEM </td>
      </tr>
      <tr>
        <td colspan="2" id="fuente1">ORDEN DE COMPRA N&deg; <strong><?php echo $row_items['str_numero_io']; ?>
          <input name="id_pedido_io" type="hidden" value="<?php echo $row_items['id_pedido_io']; ?>">
          <input name="str_numero_io" type="hidden" value="<?php echo $row_items['str_numero_io']; ?>">
          <input name="fecha_ingreso_oc" type="hidden" value="<?php echo $_GET['fecha']; ?>">
          <input name="fecha_modif_io" type="hidden" value="<?php echo fechaHoraDatelocal();?>">
          <input name="responsable_modif_io" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>">
          
          <input name="id_oc" type="hidden" value="<?php echo $_GET['id_oc']; ?>">
        </strong></td>
        <td id="fuente2"><!--<a href="orden_compra_add_detalle.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/></a><a href="orden_compra_edit.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>&id_p_oc=<?php echo $row_orden_compra['id_p_oc']; ?>"><img src="images/menos.gif" alt="ORDEN DE COMPRA" border="0" /></a><a href="orden_compra_vista.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>"><img src="images/hoja.gif" alt="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="orden_compra.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="compras.php"><img src="images/opciones.gif" alt="GESTION DE COMPRAS" border="0" style="cursor:hand;"/></a>--></td>
      </tr>
      
      <tr>
        <td colspan="3" id="fuente3"><?php  if ($row_refcliente['id_refcliente']!="") {?>
          <a href="javascript:verFoto('ref_ac_ref_cliente_edit.php?id_refcliente=<?php echo $row_refcliente['id_refcliente'];?>','840','370')"><?php echo "Ver nombre de la Ref Aquí"; ?></a><?php }else{?>
          <a href="javascript:verFoto('ref_ac_ref_cliente_add.php','840','390')"><?php echo "Agregue Nombre a la Ref Aquí"; ?></a><?php }?></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1">TRM: <!-- Dolar Wilkinsonpc Ind-Eco-Basico Start --><div id="IndEcoBasico"><a href="http://dolar.wilkinsonpc.com.co/"></a></div><script type="text/javascript" src="http://dolar.wilkinsonpc.com.co/js/ind-eco-basico.js?ancho=170&alto=85&fsize=10&ffamily=sans-serif"></script><!-- Dolar Wilkinsonpc Ind-Eco-Basico End -->
            <input name="trm" id="trm"  type="text" style="width:70px" required  value="<?php  if ($row_items['trm'] > '0') {echo $row_items['trm'];}else{echo trm_dolar();}?>" onChange="itemsoc()"></td>
            <td id="fuente3"><?php if ($cliente['id_d']=='') { ?>
              <a href="perfil_cliente_edit.php?id_c=<?php echo $_GET['id_oc'] ?>" target="_blank" onClick="self.close();">DEBE ACTUALIZAR BODEGAS DE PERFIL CLIENTE</a>
              <?php }?></td>
            </tr>
            
            <tr id="tr2">
              <td colspan="3" id="dato2"><table id="tabla3">
                <tr>
                  <td id="nivel2">ITEM </td>
                  <td id="nivel2">REF. AC</td>
                  <td id="nivel2">REF. MP</td>
                  <td id="nivel2">REF. CLIENTE</td>
                  <td id="nivel2">CANTIIDAD</td>
                  <td id="nivel2">RESTANTE</td>
                  <td id="nivel2">UNIDADES</td>
                  <td id="nivel2">MONEDA</td>
                  <td id="nivel2">PRECIO/VENTA</td>
                  <td colspan="2"  nowrap id="nivel2"><strong>COTIZACIONES</strong></td>
                </tr>
                
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                  <td id="talla2"><input name="int_consecutivo_io" type="text" id="int_consecutivo_io" value="<?php echo $row_items['int_consecutivo_io']; ?>" size="1" readonly /></td>
                  <td id="talla1"><?php $id_items = $row_items['id_items'];         
                  $sqlmp="SELECT Tbl_orden_produccion.int_cod_ref_op AS existe_op  
                  FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.id_items=$id_items AND   Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
                  AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_borrado_op='0'";
                  $resultmp= mysql_query($sqlmp);
                  $nump = mysql_num_rows($resultmp);
//SI EL ITEM TIENE REMISIONES         
                  $sql2="SELECT * FROM Tbl_remisiones WHERE str_numero_oc_r='$str_numero_oc'";
                  $result2= mysql_query($sql2);
                  $numRem = mysql_num_rows($result2);

                  if($nump >='1' || $numRem >='1')
                  { 
                   $existe_op ="1";
                 }else {
                   $existe_op="0";
                 }
          //CONTROL DE MENU ELIMINACION
                 
                 
                 
                 
          /*if($nump >='1')
          { 
          $existe_op = mysql_result($resultmp,0,'existe_op');
        }else {$existe_op="0";} */
        ?> 
        <select name="int_cod_ref_io" id="ref_cl" <?php if($_SESSION['superacceso']){?> onChange="javascript:refacvsrefcl_edit();" <?php }?> autofocus onChange="if(form1.int_cod_ref_io.value!=''){document.getElementById('ref_mp').disabled = true;sinPermiso();  }" <?php if ($existe_op > '0' && ($_SESSION['superacceso'])){ ?> disabled onClick="existeop();"<?php }?> >
          <option value="" <?php if (!(strcmp(0, $_GET['int_cod_ref_io']))) {echo "selected=\"selected\"";} ?>>Select</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_referencias['cod_ref']?>"<?php if (!(strcmp($row_referencias['cod_ref'], $_GET['int_cod_ref_io']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
            <?php
          } while ($row_referencias = mysql_fetch_assoc($referencias));
          $rows = mysql_num_rows($referencias);
          if($rows > 0) {
            mysql_data_seek($referencias, 0);
            $row_referencias = mysql_fetch_assoc($referencias);
          }
          ?>
        </select></td>
        <td id="talla1"><select name="id_mp_vta_io" id="ref_mp"<?php if($_SESSION['superacceso']){?> onChange="javascript:refmpvsrefac_edit()" onBlur="if(form1.id_mp_vta_io.value!=''){document.getElementById('ref_cl').disabled = true;}" <?php }?> style="width:100px" >
          <option value=""<?php if (!(strcmp(0, $row_items['id_mp_vta_io']))) {echo "selected=\"selected\"";} ?>>Select</option>
          <?php
          do {  
            ?>
            <option value="<?php echo $row_referencias3['id_mp_vta']?>"<?php if (!(strcmp($row_referencias3['id_mp_vta'], $row_items['id_mp_vta_io']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias3['str_nombre']?></option>
            <?php
          } while ($row_referencias3 = mysql_fetch_assoc($referencias3));
          $rows = mysql_num_rows($referencias3);
          if($rows > 0) {
            mysql_data_seek($referencias3, 0);
            $row_referencias3 = mysql_fetch_assoc($referencias3);
          }
          ?>
        </select>                  
        
        
      </td>                    
      <td id="talla3"><select name="int_cod_cliente_io" id="int_cod_cliente_io" style="width:100px">
        <option value="" <?php if (!(strcmp(0, $row_referencias2['str_ref_cl_rc']))) {echo "selected=\"selected\"";} ?>>Select</option>
        <?php
        do {  
          ?>
          <option value="<?php echo $row_referencias2['str_ref_cl_rc']?>"<?php if (!(strcmp($row_referencias2['str_ref_cl_rc'], $row_referencias2['str_ref_cl_rc']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias2['str_ref_cl_rc']?></option>
          <?php
        } while ($row_referencias2 = mysql_fetch_assoc($referencias2));
        $rows2 = mysql_num_rows($referencias2);
        if($rows2 > 0) {
          mysql_data_seek($referencias2, 0);
          $row_referencias2 = mysql_fetch_assoc($referencias2);
        }
        ?>
      </select></td>
      <td id="talla2">  
        <input name="int_cantidad_io" type="number" step="0.01" <?php if ($existe_op > '0' && ($_SESSION['superacceso'])){ ?> readonly="readonly" onClick="existeop(), igualRestante()"<?php }?> id="int_cantidad_io" onChange="itemsoc()" style="width:70px" value="<?php echo $row_items['int_cantidad_io']=='' ?  $row_cotiz['cantidad']:$row_items['int_cantidad_io']; ?>" required></td>
        <td id="talla2"><input name="int_cantidad_rest_io" type="number" step="0.01" style="width:70px" <?php if ($existe_op > '0' && ($_SESSION['superacceso'])){ ?> readonly onBlur="existeop();"<?php }?>  value="<?php echo $row_items['int_cantidad_rest_io']; ?>" onBlur="cambio_restante()" required>
        </td>
        <td id="talla1"><select name="str_unidad_io" id="str_unidad_io" onChange="itemsoc()">
          <option value="UNIDAD"<?php if (!(strcmp("UNIDAD",$row_cotiz['Str_unidad_vta']))){echo "selected=\"selected\"";} ?>>UNIDAD</option>
          <option value="MILLAR"<?php if (!(strcmp("MILLAR",$row_cotiz['Str_unidad_vta']))){echo "selected=\"selected\"";} ?>>MILLAR</option>
          <option value="PAQUETE"<?php if (!(strcmp("PAQUETE",$row_cotiz['Str_unidad_vta']))){echo "selected=\"selected\"";} ?>>PAQUETE</option>
          <option value="KILOS"<?php if (!(strcmp("KILOS",$row_cotiz['Str_unidad_vta']))){echo "selected=\"selected\"";} ?>>KILOS</option>
        </select></td>
        <td id="talla1"><select name="str_moneda_io" id="str_moneda_io" onChange="itemsoc()">
          <option value="COL$"<?php if (!(strcmp("COL$",$row_cotiz['Str_moneda']))){echo "selected=\"selected\"";} ?>>COL$</option>
          <option value="USD$"<?php if (!(strcmp("USD$",$row_cotiz['Str_moneda']))){echo "selected=\"selected\"";} ?>>USD$</option>
          <option value="EUR&euro;"<?php if (!(strcmp("EUR&euro;",$row_cotiz['Str_moneda']))){echo "selected=\"selected\"";} ?>>EUR&euro;</option>
        </select></td>                       
        
        <td id="talla1"><input name="valor" style="width:70px" type="number" step="0.01" id="valor" onBlur="itemsoc()" value="<?php 
        if($row_cotiz['N_precio']!=''){echo $row_cotiz['N_precio'];}else
        if($row_items['int_precio_trm']=='0'){echo $row_items['int_precio_io'];}else{echo $row_items['int_precio_trm'];} ?>" <?php if($_SESSION['superacceso']){?> required onChange="return itemsoc(),valores();" <?php } ?> >
        <input name="id_c_oc" id="id_c_oc" type="hidden" style="width:70px" value="<?php echo $row_orden_compra['id_c_oc']?>">
        <input name="precioreal" id="precioreal" type="hidden" style="width:70px" value="<?php  if($row_cotiz['N_precio']!=''){echo $row_cotiz['N_precio'];}else
        if($row_items['int_precio_trm']=='0'){echo $row_items['int_precio_io'];}else{echo $row_items['int_precio_trm'];}?>">
        <input type="hidden" style="width:70px" name="int_precio_trm" value="<?php echo $row_items['int_precio_trm']?>">
        <input type="hidden" style="width:70px" name="int_precio_io" value="<?php echo $row_items['int_precio_io']?>">
      </td>
      <?php do { ?>
      <td rowspan="3" valign="top" id="talla2">
        
       <a href="javascript:verFoto('control_tablas.php?n_cotiz=<?php echo $row_listado['N_cotizacion']; ?>&cod_ref=<?php echo $row_listado['N_referencia']; ?>&Str_nit=<?php echo $row_listado['Str_nit']; ?>&case=<?php echo "6"; ?>','850','270')"><em>P.actual <?php echo $row_listado['N_cotizacion']; ?></em></a>
       
     </td>
     <td valign="top" rowspan="3" id="talla2">
      <?php if($row_listado['N_referencia']!=''){?><a href="javascript:verFoto('control_tablas.php?cod_ref=<?php echo $row_listado['N_referencia']; ?>&Str_nit=<?php echo $row_listado['Str_nit']; ?>&case=<?php echo "9"; ?>','850','700')"><em>Recotizar</em></a>
        <?php }?>
      </td> 
      <?php } while ($row_listado = mysql_fetch_assoc($listado)); ?>
    </tr>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="nivel2">&nbsp;</td>
      <td colspan="2" id="nivel2">FECHA ENTREGA</td> 
      <td id="nivel2">TOTAL ITEM</td>
      <td id="nivel2">DIRECCION DESPACHO</td>
      <td id="nivel2">VENDEDOR</td>
      <td colspan="2" id="nivel2">COMI. %</td>
      <td id="nivel2">&nbsp;</td>
    </tr>
    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
      <td id="talla4">&nbsp;</td>
      <td colspan="2" id="talla5"><input name="fecha_entrega_io" type="date" min="2000-01-02" value="<?php echo $row_items['fecha_entrega_io']?>" required /></td>
      
      <td id="talla6"><input name="int_total_item_io" required="required" type="text" id="int_total_item_io" style="width:90px" readonly  onBlur="itemsoc()" value="<?php echo $row_items['int_total_item_io']?>" ></td>
    <td id="talla6"> 

     <select name="str_direccion_desp_io" id="str_direccion_desp_io"  style="width:100px"> 
      <?php if($cliente['direccion_c']!=''){?>
     <option value="<?php echo $cliente['direccion_c']."-".$cliente['ciudad_c']?>"<?php if (!(strcmp($cliente['direccion_c']."-".$cliente['ciudad_c'], $row_items['str_direccion_desp_io']))) {echo "selected=\"selected\"";} ?>>
       <?php  $mayu=strtoupper($cliente['direccion_c']);$cad1 = ($mayu); echo $cad1."-".($cliente['ciudad_c'])." Dir. compañia";
      }?>
     </option>
     <?php if($cliente['direccion_envio_factura_c']!=''){?>
     <option value="<?php echo $cliente['direccion_envio_factura_c']."-".$cliente['ciudad_c']?>"<?php if (!(strcmp($cliente['direccion_envio_factura_c']."-".$cliente['ciudad_c'], $row_items['str_direccion_desp_io']))) {echo "selected=\"selected\"";} ?>>
       <?php  $mayu=strtoupper($cliente['direccion_envio_factura_c']);$cad1 = ($mayu); echo $cad1."-".($cliente['ciudad_c'])." Dir. envio factura";
       }?>
     </option>
        <?php  foreach($row_cliente as $row_cliente ) { ?>
      <option value="<?php echo $row_cliente['direccion']."-".$row_cliente['ciudad']?>"<?php if (!(strcmp($row_cliente['direccion']."-".$row_cliente['ciudad'], $row_items['str_direccion_desp_io']))) {echo "selected=\"selected\"";} ?>>
       <?php  $mayu=strtoupper($row_cliente['direccion']);$cad1 = ($mayu); echo $cad1."-".($row_cliente ['ciudad'])." Dir. bodega";?>
     </option>
     <?php } ?>

     </select> 
 </td>
 <td id="talla2"><select name="int_vendedor_io" id="int_vendedor_io" style="width:100px">
  <option value="" <?php if (!(strcmp(0, $row_items['int_vendedor_io']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
  <?php
  do {  
    ?>
    <option value="<?php echo $row_vendedores['id_vendedor']?>"<?php if (!(strcmp($row_vendedores['id_vendedor'], $row_items['int_vendedor_io']))) {echo "selected=\"selected\"";} ?>><?php echo $row_vendedores['nombre_vendedor']?></option>
    <?php
  } while ($row_vendedores = mysql_fetch_assoc($vendedores));
  $rows = mysql_num_rows($vendedores);
  if($rows > 0) {
    mysql_data_seek($vendedores, 0);
    $row_vendedores = mysql_fetch_assoc($vendedores);
  }
  ?>
</select></td>
<td colspan="2" id="talla2"><input name="int_comision_io" type="number" id="int_comision_io"  step="0.1" min="1" max="10" style="width:50px" value="<?php echo $row_items['int_comision_io']?>" required/>
                    <!--<select name="nombre" id="nombre" style="width:130px">
                    <option value="" <?php if (!(strcmp("", $row_items['int_nombre_io']))) {echo "selected=\"selected\"";} ?>>Seleccione</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_nombre['id']?>"<?php if (!(strcmp($row_nombre['id'], $row_items['int_nombre_io']))) {echo "selected=\"selected\"";} ?>><?php echo $row_nombre['nombre_fn']?></option>
                    <?php
} while ($row_nombre = mysql_fetch_assoc($nombre));
  $rows = mysql_num_rows($nombre);
  if($rows > 0) {
      mysql_data_seek($nombre, 0);
    $row_nombre = mysql_fetch_assoc($nombre);
  }
?>
</select>--></td>
<td id="nivel2">&nbsp;</td>
</tr>


</table></td>
</tr>
<tr>
  <td id="dato2">&nbsp;</td>
  <td colspan="2" id="dato1">PROGRAMAR: 
    <select name="b_estado_io" id="b_estado_io" style="width:100px" <?php if ($existe_op > '0' && ($_SESSION['superacceso'])){ ?> disabled  onClick="existeop();"<?php }?>>
      <option value="1"<?php if(!(strcmp("1", $row_items['b_estado_io']))) {echo "selected=\"selected\"";} ?>>Ingresado</option>
      <option value="2"<?php if(!(strcmp("2", $row_items['b_estado_io']))) {echo "selected=\"selected\"";} ?>>Programar</option>              
    </select></td>
  </tr>
  <tr>
    <td colspan="3" id="dato1"><strong>Nota:</strong> Si no aparece el precio, entonces debe crear un cotizacion</td>
  </tr>
  <tr>
    <td  id="detalle1">
      <label for="cobra_cyrel">Cobra Cyrel: </label>
      <input <?php //if (!(strcmp($row_items['cobra_cyrel'],1))) {echo "checked=\"checked\"";} ?> name="cobra_cyrel" type="checkbox" id="cobra_cyrel" value="1"/><br><br>
    </td>
    <td  id="detalle1">
      <label for="cobra_flete"> Cobra Flete:</label> 
      <input <?php if (!(strcmp($row_items['cobra_flete'],1))) {echo "checked=\"checked\"";} ?> name="cobra_flete" type="checkbox" id="cobra_flete" value="1"  onClick="flete();"  /> <span style="display: none;" id="recuadro" class="recuadro"><input name="precio_flete" type="text" id="precio_flete"  min="0"  style="width:100px" value="<?php echo $row_items['precio_flete']?>"  /></span><br><br>
    </td>
  </tr> 
 
  <tr>
    <td colspan="3" id="dato2"> </td>
    </tr>
  </table>
  <input name="nit_c" type="hidden" value="<?php echo $_GET['nit_c']; ?>">
  <input type="hidden" name="MM_update" value="form1">
  <input name="id_items" type="hidden" id="id_items" value="<?php echo $row_items['id_items']; ?>">
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
<script type="text/javascript">
  if (document.getElementById('cobra_flete').checked);
      flete();

   function sinPermiso(){
       var ref = "<?php echo $row_items['int_cod_ref_io'] ?>";
       document.form1.int_cod_ref_io.value = ref;
   }
 
$( "#int_cantidad_io" ).change(function() {
  igualRestante()
});
 
   //$("#int_cantidad_io").onChange(function() {function igualRestante() }

      function igualRestante(){
           
           $("#int_cantidad_rest_io").val($("#int_cantidad_io").val());

      }


</script>
<?php
mysql_free_result($usuario); 
mysql_free_result($Result1);
mysql_free_result($Result2);
mysql_free_result($usuario);
mysql_free_result($cliente);
mysql_free_result($items);
mysql_free_result($referencias);
mysql_free_result($referencias3);
mysql_free_result($referencias2);
mysql_free_result($vendedores);
mysql_free_result($nombre);
mysql_free_result($listado);
mysql_free_result($cotiz);
mysql_free_result($refcliente);
mysql_free_result($orden_compra);
mysql_free_result($resultm);
mysql_free_result($result);

/*mysql_close($conexion1);

unset($usuario,$conexion1);
unset($cliente,$conexion1);
unset($orden_compra,$conexion1);
unset($consecutivo,$conexion1);
unset($referencias,$conexion1);
unset($referencias3,$conexion1);
unset($referencias2,$conexion1);
unset($cotiz,$conexion1);
unset($vendedores,$conexion1);
unset($nombre,$conexion1);
unset($listado,$conexion1);
unset($refcliente,$conexion1);*/


?>