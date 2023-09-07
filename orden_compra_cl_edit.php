<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php
include('funciones/adjuntar.php'); 

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
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
    break;    
    case "long":
    case "int":
    $theValue = ($theValue != "") ? intval($theValue) : "NULL";
    break;
    case "double":
    $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
    break;
    case "date":
    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
    break;
    case "defined":
    $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
    break;
  }
  return $theValue;
}

$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
 $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$tieneadjunto1=$_POST['adjunto1'];
$tieneadjunto2=$_POST['adjunto22'];
$tieneadjunto3=$_POST['adjunto33'];
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")&&($_POST['items']=='')) {
 echo "<script type=\"text/javascript\"> alert(\"NO SE PUEDE GUARDAR LA O.C SIN ITEMS INGRESADOS\");history.go(-1)</script>"; 
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")&&($_POST['items']!='')) {
  //UPDATE DEL ARCHIVO ELN EL SERVIDOR 

  $directorio = "pdfacturasoc/";
  if (isset($_FILES['str_archivo_oc']) && $_FILES['str_archivo_oc']['name'] != "") 
    $tieneadjunto1 = adjuntarArchivo($tieneadjunto1, $directorio, $_FILES['str_archivo_oc']['name'],$_FILES['str_archivo_oc']['tmp_name'],'UPDATES');

  if (isset($_FILES['str_archivo_oc']) && $_FILES['adjunto2']['name'] != "") 
    $tieneadjunto2 = adjuntarArchivo($tieneadjunto2, $directorio, $_FILES['adjunto2']['name'],$_FILES['adjunto2']['tmp_name'],'UPDATES');

  if (isset($_FILES['str_archivo_oc']) && $_FILES['adjunto3']['name'] != "") 
    $tieneadjunto3 = adjuntarArchivo($tieneadjunto3, $directorio, $_FILES['adjunto3']['name'],$_FILES['adjunto3']['tmp_name'],'UPDATES'); 


$fechas_entrega = sumarMesyDias($_POST['fecha_ingreso_oc'],3); 

  $updateSQL = sprintf("UPDATE Tbl_orden_compra SET id_pedido=%s, str_numero_oc=%s, id_c_oc=%s, str_nit_oc=%s, fecha_ingreso_oc=%s,fecha_entrega_oc=%s, str_condicion_pago_oc=%s, str_observacion_oc=%s, int_total_oc=%s, b_facturas_oc=%s, b_num_remision_oc=%s, b_factura_cirel_oc=%s, str_dir_entrega_oc=%s, str_archivo_oc=%s, adjunto2=%s, adjunto3=%s, str_elaboro_oc=%s, str_aprobo_oc=%s, b_estado_oc=%s, str_responsable_oc=%s, b_borrado_oc=%s, salida_oc=%s,vta_web_oc=%s,expo_oc=%s, autorizado=%s, entrega_fac=%s, fecha_cierre_fac=%s, comprobante_ent=%s, pago_pendiente=%s, cobra_flete=%s, precio_flete=%s, tipo_despacho=%s,fecha_autoriza=%s WHERE id_pedido=%s",

   GetSQLValueString($_POST['id_pedido'], "text"),
   GetSQLValueString($_POST['str_numero_oc'], "text"),
   GetSQLValueString($_POST['id_c_oc'], "int"),
   GetSQLValueString($_POST['nit_c'], "text"),
   GetSQLValueString($_POST['fecha_ingreso_oc'], "text"),
   GetSQLValueString($fechas_entrega, "text"),
   GetSQLValueString($_POST['str_condicion_pago_oc'], "text"),
   GetSQLValueString($_POST['str_observacion_oc'], "text"),
   GetSQLValueString($_POST['int_total_oc'], "double"),
   GetSQLValueString($_POST['b_facturas_oc'], "text"),
   GetSQLValueString($_POST['b_num_remision_oc'], "text"),             
   GetSQLValueString($_POST['b_factura_cirel_oc'], "text"),
   GetSQLValueString($_POST['str_dir_entrega_oc'], "text"),
   GetSQLValueString($tieneadjunto1, "text"),
   GetSQLValueString($tieneadjunto2, "text"),
   GetSQLValueString($tieneadjunto3, "text"),
   GetSQLValueString($_POST['str_elaboro_oc'], "text"),
   GetSQLValueString($_POST['str_aprobo_oc'], "text"),
   GetSQLValueString($_POST['b_estado_oc'], "text"),
   GetSQLValueString($_POST['str_responsable_oc'], "text"),               
   GetSQLValueString($_POST['b_borrado_oc'], "text"),
   GetSQLValueString($_POST['salida_oc'], "text"),
   GetSQLValueString($_POST['vta_web_oc'], "text"),
   GetSQLValueString($_POST['expo_oc'], "text"),
   GetSQLValueString($_POST['autorizado'], "text"),
   GetSQLValueString($_POST['entrega_fac'], "text"),
   GetSQLValueString($_POST['fecha_cierre_fac'], "text"),
   GetSQLValueString($_POST['comprobante_ent'], "text"),
   GetSQLValueString($_POST['pago_pendiente'], "text"),
   GetSQLValueString(isset($_POST['cobra_flete']) ? "true" : "", "defined","1","0"),
   GetSQLValueString($_POST['precio_flete'], "text"),
   GetSQLValueString($_POST['tipo_despacho'], "text"),
   GetSQLValueString($_POST['fecha_ingreso_oc'], "text"),
   GetSQLValueString($_POST['id_pedido'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) ;

//CAMBIAR A FACTURADO TOTAL EN LOS ITEMS DE LA O.C SI SE CAMBIA EL ESTADO A FACTURADO TOTAL DESDE ESTA PANTALLA  
  if ($_POST['b_estado_oc']=='5'){
    $fecha_modif_io = date("Y-m-d H:i:s"); 
    $updateSQL3 = sprintf("UPDATE Tbl_items_ordenc SET b_estado_io=%s, fecha_modif_io=%s, responsable_modif_io=%s WHERE id_pedido_io=%s",
     GetSQLValueString($_POST['b_estado_oc'], "text"),
     GetSQLValueString($fecha_modif_io, "text"),
     GetSQLValueString($_POST['responsable_modif_io'], "text"),
     GetSQLValueString($_POST['id_pedido'], "int"));
    mysql_select_db($database_conexion1, $conexion1); 
    $Result4 = mysql_query($updateSQL3, $conexion1) ;
  }

//GUARDADO DE HISTORICOS
  $myObject = new oComercial();
  $historico =  new oComercial();

  if(isset($_POST['id_pedido'])){ 
    $historico=$myObject->Obtener('tbl_orden_compra','id_pedido',$_POST['id_pedido']);
  } 

  if(isset($_POST['id_pedido']) && $historico){
    $myObject->Registrar("tbl_orden_compra_historico", "id_pedido,str_numero_oc,id_c_oc,str_nit_oc,fecha_ingreso_oc,fecha_entrega_oc,str_condicion_pago_oc,str_observacion_oc,int_total_oc,b_facturas_oc,b_num_remision_oc,b_factura_cirel_oc,str_dir_entrega_oc,str_archivo_oc,adjunto2,adjunto3,str_elaboro_oc,str_aprobo_oc,b_estado_oc,str_responsable_oc,b_borrado_oc,salida_oc,b_oc_interno,vta_web_oc,expo_oc,autorizado,tb_pago,factura_oc,entrega_fac,fecha_cierre_fac,comprobante_ent,estado_cartera,tipo_pago_cartera,valor_cartera,modifico", $historico);
}//FIN HISTORICO

//CAMBIA EL ESTADO EN LAS O LA REMISIONES Q TENGA ESA O.C
$remiupdate=$_POST['str_numero_oc'];             
$sqlupdate="UPDATE Tbl_remisiones SET b_borrado_r='0' WHERE str_numero_oc_r='$remiupdate' AND b_borrado_r='1'";
mysql_select_db($database_conexion1, $conexion1);
$Result2 =mysql_query($sqlupdate, $conexion1) ;
//UPDATE A LA DIRECCION DE ENVIO DE FACTURA DEL CLIENTE
$updateSQL2 = sprintf("UPDATE cliente SET direccion_c=%s, direccion_envio_factura_c=%s,plazo_aprobado_c=%s  WHERE id_c=%s",
 GetSQLValueString($_POST['dir_c'], "text"),
 GetSQLValueString($_POST['str_dir_entrega_oc'], "text"),
 GetSQLValueString($_POST['str_condicion_pago_oc'], "text"),
 GetSQLValueString($_POST['id_oc'], "int"));
mysql_select_db($database_conexion1, $conexion1);
$Result3 = mysql_query($updateSQL2, $conexion1) ;

//FIN ENVIO DE E-MAIL A FACTURACION
$updatetGoTo = "orden_compra_cl_vista.php?str_numero_oc=" . $_POST['str_numero_oc'] . "";
if (isset($_SERVER['QUERY_STRING'])) {
  $updatetGoTo .= (strpos($updatetGoTo, '?')) ? "&" : "?";
  $updatetGoTo .= $_SERVER['QUERY_STRING'];
}
header(sprintf("Location: %s", $updatetGoTo));
}
//ACTIVAR NUEVAMENTE
if($_POST['borrada']=='0'){
  $updateSQL5 = sprintf("UPDATE Tbl_orden_compra  SET str_responsable_oc=%s, b_borrado_oc=%s WHERE id_pedido=%s",
    GetSQLValueString($_POST['str_responsable_oc'], "text"),  
    GetSQLValueString($_POST['borrada'], "text"),           
    GetSQLValueString($_POST['id_pedido'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result5 = mysql_query($updateSQL5, $conexion1) ;  
  $updatetGoTo = "orden_compra_cl_vista.php?str_numero_oc=" . $_POST['str_numero_oc'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updatetGoTo .= (strpos($updatetGoTo, '?')) ? "&" : "?";
    $updatetGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updatetGoTo));           
}
//FIN ACTIVAR
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) ;
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_orden_compra = "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM Tbl_orden_compra WHERE str_numero_oc ='%s'", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) ;
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

$colname_cliente = "-1";
if (isset($_GET['id_oc'])) {
  $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['id_oc'] : addslashes($_GET['id_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_cliente = sprintf("SELECT * FROM cliente WHERE id_c = %s", $colname_cliente);
$cliente = mysql_query($query_cliente, $conexion1) ;
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$colname_detalle = "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);

$query_detalle = "SELECT * FROM tbl_items_ordenc WHERE str_numero_io =  '".$_GET['str_numero_oc']."' ORDER BY id_items ASC" ; 
$detalle = mysql_query($query_detalle, $conexion1) ;
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);



 //IMRPIME EL NOMBRE DEL VENDEDOR
mysql_select_db($database_conexion1, $conexion1);
$query_vendedores = "SELECT * FROM vendedor ORDER BY nombre_vendedor ASC";
$vendedores = mysql_query($query_vendedores, $conexion1) ;
$row_vendedores = mysql_fetch_assoc($vendedores);
$totalRows_vendedores = mysql_num_rows($vendedores);


//ASIGNA NUMERO CONSECUTIVO DE REMISION
$colname_remision= "-1";
if (isset($_GET['str_numero_oc'])) {
  $colname_remision = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_remision = sprintf("SELECT * FROM tbl_remisiones WHERE str_numero_oc_r='%s'", $colname_remision);
$remision = mysql_query($query_remision, $conexion1) or die(mysql_error());
$row_remision = mysql_fetch_assoc($remision);
$totalRows_remision = mysql_num_rows($remision);

 
//redirecciona si es inventario o programada
/* echo  $_SESSION['id_usuario']=37; echo ' ';
 echo  $_SESSION['superacceso']=0; echo ' ';
 echo  $row_orden_compra['b_estado_oc']=1; echo ' ';*/

if( $_SESSION['superacceso']==1 )  //$row_orden_compra['b_estado_oc'] != 1
{
 echo 'Test-1'; 

 if( $_SESSION['id_usuario']==64  && $row_orden_compra['b_estado_oc'] < 1){
         echo 'Test-2'; //aqui puede editar si esta recien ingresada
       } else if( ($_SESSION['id_usuario']==26 || $_SESSION['id_usuario']==23) && $row_orden_compra['b_estado_oc'] >= 0){
           echo 'Test-3'; //aqui puede editar independientemente del estado
         }else{
          echo 'Test-4';  //aqui no puede editar ya esta en proceso y que sea otro usuario no permitido
          header("Location: orden_compra_cl_vista.php?str_numero_oc=".$row_orden_compra['str_numero_oc']."&id_oc=".$_GET['id_oc']);
        }


      }else{
       echo 'Test2-1';
       if( $row_orden_compra['b_estado_oc'] > 1){
         echo 'Test2-2'; //aqui no puede editar ya esta en proceso

         header("Location: orden_compra_cl_vista.php?str_numero_oc=".$row_orden_compra['str_numero_oc']."&id_oc=".$_GET['id_oc']);
       }else{
         echo 'Test2-3'; //aqui puede editar si esta recien ingresada

       }

     } 


     ?>
     <html><head>
      <title>SISADGE AC &amp; CIA</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

      <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
      <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
      <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
      <script type="text/javascript" src="js/ordenCompra.js"></script>
      <script type="text/javascript" src="AjaxControllers/js/ordenCompraDet.js"></script>

      <link href="css/formato.css" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="js/formato.js"></script>
      <script type="text/javascript" src="js/consulta.js"></script>
      <script type="text/javascript" src="js/listado.js"></script>
      <script type="text/javascript" src="js/validacion_numerico.js"></script>
      <link rel="stylesheet" type="text/css" href="css/general.css"/>

      <link rel="stylesheet" type="text/css" href="css/desplegable.css" />

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

      <script type="text/javascript">
        function confirActivo() {

          swal({
            title: "Cliente Inactivo",
            text: "Quiere activar el cliente",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
          },
          function(){
            setTimeout(function(){
              var url="cambio_estado_cliente.php?"; 
              var campo1=<?php echo $_GET['str_numero_oc'];?>;
              var campo2=<?php echo $_GET['id_oc']; ?>; 
              var campo3="<?php echo $row_cliente['nit_c']; ?>"; 
              var campo4="1"; 
              var dato1="str_numero_oc";
              var dato2="id_oc";
              var dato3="Str_nit";
              var dato4='id';

              window.location.href=url+dato1+"="+campo1+"&"+dato2+"="+campo2+"&"+dato3+"="+campo3+"&"+dato4+"="+campo4;

              swal("Proceso finalizado!");
            }, 2000);

          });
        }
//desactivar todo
//$('#form1').find('input, textarea, button, select').attr('disabled','disabled');


</script>
</head>
<body>
<!--<body onload="verFoto('orden_compra_cl_add_detalle.php?str_numero_oc=<?php echo $row_orden_compra['str_numero_oc'] ?>&id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')">
-->

<?php echo $conexion->header('vistas'); ?>
           <table align="center" id="tabla"><tr align="center"><td>   
                <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onSubmit="return ActualizarEstadosOc()">
                  <table id="tabla">
                    <tr id="tr1">
                      <td nowrap id="codigo">CODIGO : A3 - F02</td>
                      <td nowrap id="titulo2">ORDEN DE COMPRA </td>
                      <td nowrap id="codigo">VERSION : 0</td>
                    </tr>
                    <tr>
                      <td rowspan="8" id="dato2" ><img src="images/logoacyc.jpg"></td>
                      <td id="subtitulo">&nbsp;</td>
                      <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/>
                        <?php 
                      //SI EL ITEM TIENE PRODUCCION
                        $id_items = $row_detalle['id_items'];         
                        $sqlmp="SELECT Tbl_orden_produccion.int_cod_ref_op AS existe_op  
                        FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.id_items='$id_items' AND   Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
                        AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_borrado_op='0'";
                        $resultmp= mysql_query($sqlmp);
                        $nump = mysql_num_rows($resultmp);
                        $str_numero_oc=$_GET['str_numero_oc'];


                      //SI LA O.C TIENE REMISIONES          
                        $sql2="SELECT * FROM Tbl_remisiones,tbl_remision_detalle WHERE Tbl_remisiones.str_numero_oc_r='$str_numero_oc' AND Tbl_remisiones.str_numero_oc_r=tbl_remision_detalle.str_numero_oc_rd";
                        $result2= mysql_query($sql2);
                        $numRem = mysql_num_rows($result2);

                        if( ($nump >='1' || $numRem >='1') && ($nump !='' || $numRem !=''))
                        { 
                          $existe_op ="1";
                        }else {
                          $existe_op="0";
                        }
                        ?>
                        <?php  
                      //CONTROL DE MENU ELIMINACION  
                         if($existe_op=='0'  && ($_SESSION['no_edita'])) : //$_SESSION['tipo_usuario']=='1' || $_SESSION['tipo_usuario']=='11'        
                          ?> 
                                  
                          <a href="javascript:eliminar1('id_pedido',<?php echo $row_orden_compra['id_pedido']; ?>,'orden_compra_cl_edit.php')"><img src="images/por.gif" alt="ELIMINAR O.C." title="ELIMINAR O.C." border="0" style="cursor:hand;"/></a>
                          <?php else:?>
                            <img src="images/por.gif" alt="ELIMINAR O.C." title="ELIMINAR O.C." border="0" style="cursor:hand;" onClick="enProduccion();" /> 
                          <?php endif; ?>
                            <a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_orden_compra['str_numero_oc']; ?>&id_oc=<?php echo $_GET['id_oc']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="orden_compra_cl2.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" title="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
                        </tr>
                        <tr>
                          <td id="numero1">N&deg;
                            <input type="text" name="str_numero_oc" class="rojo_inteso" id="str_numero_oc" value="<?php echo $row_orden_compra['str_numero_oc']=='' ? $_GET['str_numero_oc'] : $row_orden_compra['str_numero_oc']; ?>" readonly ="readonly" size="20">
                            <?php if (!(strcmp($row_orden_compra['b_oc_interno'],1))) { ?>
                              <span class="centrado1">O.C INTERNA</span><?php }?>
                              <input type="hidden" name="id_pedido" id="id_pedido" value="<?php echo $row_orden_compra['id_pedido'] ?>">
                              <input name="id_c_oc" type="hidden" value="<?php echo $row_cliente['id_c']; ?>"></td>
                              <td id="dato_1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td id="fuente1">FECHA INGRESO</td>
                              <td id="numero1"><?php if($row_orden_compra['b_borrado_oc']=='1'){?><strong>OC. BORRADA, Quiere Activarla de nuevo ?:
                                <select name="borrada" id="borrada">
                                  <option value="0">SI</option>
                                  </select><?php }else ?>
                                </strong><?php if($row_orden_compra['b_borrado_oc']=='0'){?><input type="hidden" name="b_borrado_oc" id="b_borrado_oc" value="<?php echo $row_orden_compra['b_borrado_oc']?>"><?php } ?></td>
                              </tr>
                              <tr>
                                <td id="dato1">
                                  <input name="fecha_ingreso_oc" type="date" min="2000-01-02" value="<?php echo $row_orden_compra["fecha_ingreso_oc"]; ?>" size="10" autofocus />

                                </td>
                                <td id="dato1">&nbsp;</td>
                              </tr>
                              <tr>
                                <td id="fuente1">CLIENTE</td>
                                <td id="fuente1">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2" id="dato1"><strong><?php echo utf8_encode($row_cliente['nombre_c']); ?></strong>              <input type="hidden" name="id_oc" id="id_oc" value="<?php echo $row_orden_compra['id_c_oc']; ?>"></td>
                              </tr>
                              <tr>
                                <td colspan="2" id="dato1">NIT              </td>
                              </tr>
                              <tr>
                                <td colspan="2" id="dato1"><strong><?php echo $row_orden_compra['str_nit_oc']; ?></strong><input type="hidden" name="nit_c" id="nit_c" value="<?php echo $row_cliente['nit_c'] ?>"></td>
                              </tr>
                              <tr>
                                <td colspan="3" id="detalle2"><table id="tabla"><!--se cambio tabla2 x tabla1-->
                                  <tr>
                                    <td id="dato1" width="50%"><strong>NIT : </strong><?php echo $row_cliente['nit_c']; ?></td>
                                    <td id="dato1" width="50%"><strong>PAIS / CIUDAD : </strong><?php  $cad=utf8_encode($row_cliente['pais_c']);echo $cad; ?> / <?php $cad2=utf8_encode($row_cliente['ciudad_c']); echo $cad2;?></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2" id="dato1"><strong>NOMBRE DE LA EMPRESA: </strong><?php $cad4=utf8_encode($row_cliente['nombre_c']);echo $cad4; ?></td>
                                  </tr>
                                  <tr>
                                    <td id="dato1"><strong>DIRECCCION C:</strong><?php $dir= ($row_cliente['direccion_c']); ?> <?php echo $dir; ?>
                                    <input name="dir_c" type="hidden" value="<?php echo  ($dir); ?>"></td>
                                    <td id="dato1"><strong>TELEFONO:</strong><?php echo $row_cliente['telefono_c']; ?></td>
                                  </tr>
                                  <tr>
                                    <td id="dato1"><strong>CONTACTO COMERCIAL:</strong><?php echo  ($row_cliente['contacto_c']); ?></td>
                                    <td id="dato1"><strong>TEL COMERCIAL:</strong><?php echo $row_cliente['telefono_contacto_c']; ?></td>
                                  </tr>
                                  <tr>
                                    <td id="dato1"><strong>EMAIL COMERCIAL: </strong><?php echo $row_cliente['email_comercial_c']; ?></td>
                                    <td id="dato1"><strong>CONDICIONES DE PAGO:</strong>
                                      <select name="str_condicion_pago_oc" id="str_condicion_pago_oc">
                                        <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_orden_compra['str_condicion_pago_oc']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
                                        <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_orden_compra['str_condicion_pago_oc']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
                                        <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_orden_compra['str_condicion_pago_oc']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias </option>
                                        <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_orden_compra['str_condicion_pago_oc']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias </option>
                                        <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_orden_compra['str_condicion_pago_oc']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias </option>
                                        <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_orden_compra['str_condicion_pago_oc']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias </option>
                                        <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_orden_compra['str_condicion_pago_oc']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias </option>
                                        <option value="PAGO A 120 DIAS"<?php if (!(strcmp("PAGO A 120 DIAS", $row_orden_compra['str_condicion_pago_oc']))) {echo "selected=\"selected\"";} ?>>Pago a 120 Dias </option>
                                      </select></td>
                                    </tr>
                                    <tr>
                                      <td id="dato1"><strong>DIRECCION ENVIO DE FACTURA:</strong>
                                        <?php //$dir_limpia = $row_cliente['direccion_envio_factura_c'];?>
                                        <input name="fecha_modif_io" type="hidden" value="<?php echo date("Y-m-d H:i:s");?>">
                                        <input name="responsable_modif_io" type="hidden" value="<?php echo $_SESSION['Usuario'];?>">
                                        <textarea cols="40" name="str_dir_entrega_oc" id="str_dir_entrega_oc" onKeyUp="conMayusculas(this)" rows="2"><?php echo ($row_orden_compra["str_dir_entrega_oc"]); ?></textarea>
                                      </td>
                                      <td id="dato1"><a href="perfil_cliente_vista.php?id_c=<?php echo $row_cliente['id_c'] ?>" target="_blank">ACTUALIZAR PERFIL CLIENTE</a></td>
                                    </tr>
                                    <tr>
                                     <td id="dato1"><strong>Se entrega Factura? </strong>
                                       <select name="entrega_fac" id="entrega_fac" >
                                         <option value=""<?php if (!(strcmp("",$row_orden_compra['entrega_fac']))){echo "selected=\"selected\"";} ?>>Seleccione...</option>
                                         <option value="NO"<?php if (!(strcmp("NO",$row_orden_compra['entrega_fac']))){echo "selected=\"selected\"";} ?>>NO</option>
                                         <option value="SI"<?php if (!(strcmp("SI",$row_orden_compra['entrega_fac']))){echo "selected=\"selected\"";} ?>>SI</option>
                                       </select>
                                     </td>
                                     <td id="dato1"><strong>Fecha Cierre Facturacion:</strong><input type="date" name="fecha_cierre_fac" id="fecha_cierre_fac" value="<?php echo $row_orden_compra['fecha_cierre_fac'] ?>" size="10"></td>
                                   </tr>
                                   <tr>
                                     <td id="dato1"><strong>Adjuntar Comprobante? </strong>
                                      <select name="comprobante_ent" id="comprobante_ent" > 
                                        <option value="NO"<?php if (!(strcmp("NO",$row_orden_compra['comprobante_ent']))){echo "selected=\"selected\"";} ?>>NO</option>
                                        <option value="SI"<?php if (!(strcmp("SI",$row_orden_compra['comprobante_ent']))){echo "selected=\"selected\"";} ?>>SI</option>
                                      </select>
                                      <a href="javascript:verFoto('Archivosdesp/<?php echo $row_remision['comprobante_file'];?>','610','490')"> 
                                        <?php if($row_remision['comprobante_file']!=''){echo "VER COMPROBANTE";}else{'Sin Adjunto';} ?>
                                      </a>
                                    </td>

                                    

                                  </tr>
                                  <tr>
                                    <td id="dato1">FACTURRA # <strong><?php echo  $row_orden_compra['factura_oc']; ?></strong>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="4" id="dato2">
                                      <br>
                                      <?php  if($_GET['str_numero_oc']!='' && $_GET['id_oc']!=''&& $_GET['id_oc']!='0'&& $row_cliente['estado_c']!="INACTIVO"){ ?><strong>
                                        <a href="javascript:verFoto('orden_compra_cl_add_detalle.php?str_numero_oc=<?php echo $row_orden_compra['str_numero_oc'] ?>&id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')"><img src="images/mas.gif" alt="<?php echo $muestra;?>" title="ADD ITEM" border="0" style="cursor:hand;"/> AGREGAR ITEM</a></strong><?php }else { ?><a href="#" onClick="confirActivo()"><img src="images/por.gif" alt="ELIMINAR O.C." title="ELIMINAR O.C." border="0" style="cursor:hand;"/><strong>ACTIVAR CLIENTE</strong></a>
                                      <?php }?>
                                      <br><br>
                                      <!--<a href="orden_compra_add_detalle.php?n_oc=<?php echo $row_orden_compra['str_numero_oc']; ?>&id_p=<?php echo $row_cliente['id_p']; ?>">* ADD POS *</a></strong>--></td>
                                    
                                     <td  id="dato1"> TRM: <div id="IndEcoBasico"><a href="http://dolar.wilkinsonpc.com.co/" target="_blank" ></a></div><script type="text/javascript" src="http://dolar.wilkinsonpc.com.co/js/ind-eco-basico.js?ancho=170&alto=85&fsize=10&ffamily=sans-serif"></script> </td>
                                   </tr>
                                   <tr>
                                     <td id="dato1"> 
                                       <strong>Tipo de O.C </strong>
                                        <select name="tipo_despacho" id="tipo_despacho" required="required" > 
                                         <option value="despacho"<?php if (!(strcmp("despacho",$row_orden_compra['tipo_despacho']))){echo "selected=\"selected\"";} ?>>Para despacho</option> 
                                         <option value="inventario"<?php if (!(strcmp("inventario",$row_orden_compra['tipo_despacho']))){echo "selected=\"selected\"";} ?>>Para inventario</option>
                                       </select>
                                     </td>
                                   </tr>
                                   <tr>
                                    <td  id="detalle1">
                                     <label for="cobra_flete"> Cobra Flete:</label> 
                                     <input <?php if (!(strcmp($row_orden_compra['cobra_flete'],1))) {echo "checked=\"checked\"";} ?> name="cobra_flete" type="checkbox" id="cobra_flete" value="1"  onClick="flete();"  /> <span style="display: none;" id="recuadro" class="recuadro"><input name="precio_flete" type="text" id="precio_flete"  min="0"  style="width:100px" value="<?php echo $row_orden_compra['precio_flete']?>"  /></span><br><br>
                                   </td>
                                   <td colspan="2" id="dato1"><strong>Proforma Pendiente de Pago? </strong>
                                     <select name="pago_pendiente" id="pago_pendiente" required="required">
                                      <option value=""<?php if (!(strcmp("",$row_orden_compra['pago_pendiente']))){echo "selected=\"selected\"";} ?>>Seleccione...</option> 
                                      <option value="NO"<?php if (!(strcmp("NO",$row_orden_compra['pago_pendiente']))){echo "selected=\"selected\"";} ?>>NO</option> 
                                      <option value="SI"<?php if (!(strcmp("SI",$row_orden_compra['pago_pendiente']))){echo "selected=\"selected\"";} ?>>SI</option>
                                    </select>
                                  </td>
                                  <td colspan="2" id="dato1"> <strong>Autoriza Despacho? </strong>
                                    <select name="autorizado" id="autorizado">  
                                      <option value="NO"<?php if (!(strcmp("NO",$row_orden_compra['autorizado']))){echo "selected=\"selected\"";} ?>>NO</option> 
                                      <option value="SI"<?php if (!(strcmp("SI",$row_orden_compra['autorizado']))){echo "selected=\"selected\"";} ?>>SI</option>
                                    </select> 
                                  </td>
                                  <td><p> <br> &nbsp;&nbsp;&nbsp;&nbsp;</p></td>
                                </tr> 

                              </table></td>
                            </tr>         
                            <tr>
                              <td colspan="4" id="dato3"><?php $id=$_GET['id']; 
                              if($id == '1') { ?> 
                                <div id="acceso1"> <?php echo "ELIMINACION DEL ITEMS"; ?> </div>
                              <?php }
                              if($id == '0') { ?>
                                <div id="numero1"> <?php echo "NO SE PUEDE ELIMINAR PORQUE YA ESTA EN PRODUCCION"; ?> </div>
                              <?php }
                              if($id == '2') { ?>
                                <div id="numero1"> <?php echo "NO SE PUEDE ELIMINAR PORQUE TIENE ITEMS"; ?> </div>
                                <?php } ?></td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato2"></td>
                              </tr> 
                              <?php  if($row_detalle['str_numero_io']!='' && $row_detalle['id_mp_vta_io']=='') : ?>
                                <tr id="tr2">
                                  <td colspan="4" id="dato2"><table id="tabla">
                                    <tr>
                                      <td id="nivel2">&nbsp;</td>
                                      <td id="nivel2">ITEM</td>
                                      <td id="nivel2">REF. AC</td>
                                      <td id="nivel2">REF. MP</td>
                                      <td id="nivel2">REF. CLIENTE</td>
                                      <td id="nivel2">CANT.</td>
                                      <td id="nivel2">CANT. RESTANTE&nbsp;</td>
                                      <td id="nivel2">UNIDADES&nbsp;</td>
                                      <td id="nivel2">MONEDA&nbsp;</td>
                                      <td id="nivel2">TRM&nbsp; </td>
                                      <td nowrap="nowrap" id="nivel2">PRECIO / TRM&nbsp;</td>
                                      <td nowrap="nowrap" id="nivel2">PRECIO / PESOS&nbsp;</td>
                                      <td id="nivel2">IPUU&nbsp;</td>
                                      <td nowrap="nowrap" id="nivel2">FECHA ENTREGA&nbsp;</td>
                                      <td nowrap="nowrap" id="nivel2">FECHA MODIF&nbsp;</td>
                                      <td id="nivel2">RESP MODIF</td>
                                      <td id="nivel2">TOTAL ITEM</td>
                                      <td id="nivel2">DIRECCION ENTREGA</td>
                                      <td id="nivel2">COBRA CYREL</td>
                                      
                                   <!--<td id="nivel2">VENDEDOR</td>
                                     <td id="nivel2">COMI. %</td>-->
                                     <td nowrap="nowrap"id="nivel2">FACTURADO</td>
                                   </tr>
                                   <?php do { ?>
                                     <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                                      <td id="talla2"><?php 
                                      $id_items = $row_detalle['id_items'];         
                                      $sqlmp="SELECT Tbl_orden_produccion.int_cod_ref_op AS existe_op  
                                      FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.id_items='$id_items' AND   Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
                                      AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_borrado_op='0'";
                                      $resultmp= mysql_query($sqlmp);
                                      $nump = mysql_num_rows($resultmp);
                                      $str_numero_oc=$_GET['str_numero_oc']; 
//SI EL ITEM TIENE REMISIONES 

                                      $sql2="SELECT * FROM Tbl_items_ordenc,tbl_remision_detalle WHERE Tbl_items_ordenc.id_items='$id_items' AND tbl_remision_detalle.str_numero_oc_rd = Tbl_items_ordenc.str_numero_io AND Tbl_items_ordenc.int_cod_ref_io = tbl_remision_detalle.int_ref_io_rd";
                                      $result2= mysql_query($sql2);
                                      $numRem = mysql_num_rows($result2);

                                      if($nump >='1' || $numRem >='1' || $nump !='' || $numRem !='')
                                      { 
                                        $existe_op_det ="1";
                                      }else {
                                        $existe_op_det ="0";
                                      }         


                                      if ($existe_op_det=='0') {?>
                                       <a href="javascript:eliminar1('id_items',<?php echo $row_detalle['id_items']; ?>,'orden_compra_edit.php')"><img src="images/por.gif" alt="ELIMINAR O.C." title="ELIMINAR O.C." border="0" style="cursor:hand;"/></a><?php }else{?><img src="images/pa.gif" alt="EN PRODUCCION" title="EN PRODUCCION" border="0" onClick="enProduccion();" style="cursor:hand;"/>
                                     <?php } ?>
                                     <?php if ($_SESSION['superacceso']) { ?>
                                       <a href="javascript:eliminar1('id_items',<?php echo $row_detalle['id_items']; ?>,'orden_compra_edit.php')"><img src="images/por.gif" alt="PUEDE ELIMINAR POR SER SUPERACCESO" title="PUEDE ELIMINAR POR SER SUPERACCESO" border="0" style="cursor:hand;"/></a>
                                     <?php  } ?> 
                                   </td>
                                   <td id="talla2">
                                    <input type="hidden" name="items" id="items" value="<?php echo $row_detalle['id_items']; ?>"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000">

                                      <?php echo $row_detalle['int_consecutivo_io']; ?></a>
                                    </td>
                                    <td id="talla1">
                                      <a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_cod_ref_io']; ?></a>
                                    </td>
                                    <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php $mp=$row_detalle['id_mp_vta_io'];

                                    $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
                                    $resultmp= mysql_query($sqlmp);
                                    $nump= mysql_num_rows($resultmp);
                                    if($nump >='1')
                                    { 
                                     $nombre_mp = mysql_result($resultmp,0,'str_nombre');
                                     echo $nombre_mp;
                                   }else {echo "N.A";}?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_cod_cliente_io']; ?></a></td>
                                   <td id="talla2"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_cantidad_io']; ?></a></td>
                                   <td id="talla2"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php if($row_detalle['int_cantidad_rest_io']==''){echo '0.00';}else{echo $row_detalle['int_cantidad_rest_io'];} ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['str_unidad_io']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['str_moneda_io']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['trm']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_precio_trm']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['N_precio_old'];//$row_detalle['int_precio_io']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['impuesto']==1 ? 'SI': 'NO'; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['fecha_entrega_io']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['fecha_modif_io']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['responsable_modif_io']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php $subtotal=$subtotal+ ($row_detalle['int_cantidad_io']*$row_detalle['N_precio_old']); echo ($row_detalle['int_cantidad_io']*$row_detalle['N_precio_old']);//$row_detalle['int_total_item_io']; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php $cad5 = htmlentities($row_detalle['str_direccion_desp_io']);echo $cad5; ?></a></td>
                                   <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['cobra_cyrel']==1 ? 'SI':'NO' ; ?></a></td>

                  <!--<td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php $vendedor=$row_detalle['int_vendedor_io'];
  if($vendedor!='')
  {
  $sqlvendedor="SELECT * FROM vendedor WHERE id_vendedor ='$vendedor'";
  $resultvendedor= mysql_query($sqlvendedor);
  $nuvendedor= mysql_num_rows($resultvendedor);
  if($nuvendedor >='1')
  { 
  $nombre_vendedor = mysql_result($resultvendedor,0,'nombre_vendedor');
  echo $nombre_vendedor;
  } } ?></a></td>
  <td id="talla2"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_comision_io'];  ?></a></td>-->

  <td nowrap="nowrap"id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php if($row_detalle['b_estado_io']=='1'){echo "Ingresado";}else if($row_detalle['b_estado_io']=='5'){echo "Facturado Total";}else if($row_detalle['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_detalle['b_estado_io']=='2'){echo "Programado";}else if($row_detalle['b_estado_io']=='3'){echo "Remisionado";} ?></a></td>
</tr>
<?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>

</table>
</td>
</tr>



<?php elseif($row_detalle['id_mp_vta_io']!='' && $row_detalle['str_numero_io']!='') : ?>



  <tr id="tr2">
    <td colspan="4" id="dato2"><table id="tabla">
      <tr>
        <td id="nivel2">&nbsp;</td>
        <td id="nivel2">ITEM</td>
        <td id="nivel2">REF. AC</td>
        <td id="nivel2">REF. MP</td>
        <td id="nivel2">REF. CLIENTE</td>
        <td id="nivel2">CANT.</td>
        <td id="nivel2">CANT. RESTANTE</td>
        <td id="nivel2">UNIDADES</td>
        <td id="nivel2">MONEDA</td>
        <td id="nivel2">TRM</td>
        <td id="nivel2">PRECIO / <a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000">TRM</a></td>
        <td id="nivel2">PRECIO / PESOS</td>
        <td id="nivel2">FECHA ENTREGA</td>
        <td id="nivel2">FECHA MODIF</td>
        <td id="nivel2">RESP MODIF</td>
        <td id="nivel2">TOTAL ITEM</td>

        <td id="nivel2">DIRECCION ENTREGA</td>
        <td id="nivel2">COBRA CYREL</td>

                <!--<td id="nivel2">VENDEDOR</td>
                  <td id="nivel2">COMI. %</td>-->
                  <td nowrap="nowrap"id="nivel2">FACTURADO</td>
                </tr>
                <?php do { ?>
                  <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                    <td id="talla2"><?php if ($existe_op_det=='0') {?>
                      <a href="javascript:eliminar1('id_items',<?php echo $row_detalle['id_items']; ?>,'orden_compra_edit.php')"><img src="images/por.gif" alt="ELIMINAR O.C." title="ELIMINAR O.C." border="0" style="cursor:hand;"/></a><?php }else{?><img src="images/pa.gif" alt="EN PRODUCCION"title="EN PRODUCCION" border="0" style="cursor:hand;" onClick="enProduccion();" /><?php } ?></td>
                      <td id="talla2"><input type="hidden" name="items" id="items" value="<?php echo $row_detalle['id_items']; ?>"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000">

                        <?php echo $row_detalle['int_consecutivo_io']; ?></a></td>
                        <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_cod_ref_io']; ?></a></td>
                        <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php $mp=$row_detalle['id_mp_vta_io'];
                        $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
                        $resultmp= mysql_query($sqlmp);
                        $nump= mysql_num_rows($resultmp);
                        if($nump >='1')
                        { 
                         $nombre_mp = mysql_result($resultmp,0,'str_nombre');
                         echo $nombre_mp;
                       }else {echo "N.A";}?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_cod_cliente_io']; ?></a></td>
                       <td id="talla2"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_cantidad_io']; ?></a></td>
                       <td id="talla2"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php if($row_detalle['int_cantidad_rest_io']==''){echo '0.00';}else{echo $row_detalle['int_cantidad_rest_io'];} ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['str_unidad_io']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['str_moneda_io']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['trm']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_precio_trm']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['N_precio_old']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['fecha_entrega_io']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['fecha_modif_io']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['responsable_modif_io']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php $subtotal=$subtotal+ ($row_detalle['int_cantidad_io']*$row_detalle['N_precio_old']); echo ($row_detalle['int_cantidad_io']*$row_detalle['N_precio_old']);//$row_detalle['int_total_item_io']; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php $cad5 = htmlentities($row_detalle['str_direccion_desp_io']);echo $cad5; ?></a></td>
                       <td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['cobra_cyrel']==1 ? 'SI':'NO' ; ?></a></td>

                  <!--<td id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&fecha=<?php echo $row_orden_compra['fecha_ingreso_oc']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php $vendedor=$row_detalle['int_vendedor_io'];
  if($vendedor!='')
  {
  $sqlvendedor="SELECT * FROM vendedor WHERE id_vendedor ='$vendedor'";
  $resultvendedor= mysql_query($sqlvendedor);
  $nuvendedor= mysql_num_rows($resultvendedor);
  if($nuvendedor >='1')
  { 
  $nombre_vendedor = mysql_result($resultvendedor,0,'nombre_vendedor');
  echo $nombre_vendedor;
  } } ?></a></td>
  <td id="talla2"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['int_comision_io'];  ?></a></td>-->
  <td nowrap="nowrap"id="talla1"><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>&int_cod_ref_io=<?php echo $row_detalle['int_cod_ref_io']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000">
    <?php if($row_detalle['b_estado_io']=='1'){echo "Ingresado";}else if($row_detalle['b_estado_io']=='5'){echo "Facturado Total";}else if($row_detalle['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_detalle['b_estado_io']=='2'){echo "Programado";}else if($row_detalle['b_estado_io']=='3'){echo "Remisionado";} ?>
  </a><a href="javascript:verFoto('orden_compra_cl_edit_detalle.php?id_oc=<?php echo $row_orden_compra['id_c_oc']; ?>&id_items=<?php echo $row_detalle['id_items']; ?>&nit_c=<?php echo $row_cliente['nit_c']; ?>','1300','550')" target="_top" style="text-decoration:none; color:#000000"></a></td>
</tr>
<?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>

</table>
</td>
</tr><?php endif; ?>            
<tr>
  <td colspan="4" id="fuente1">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" id="fuente1">OBSERVACIONES:</td>            
  <td id="fuente3">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" rowspan="2" id="fuente1"><textarea name="str_observacion_oc" cols="50" onKeyUp="conMayusculas(this)" rows="3" id="str_observacion_oc"><?php echo ($row_orden_compra['str_observacion_oc']); ?></textarea></td>
            <td id="fuente3"><!--<strong>TOLERANCIAS -
            </strong>              <input type="text" name="valor_iva_oc2" value="<?php echo $row_orden_compra['valor_iva_oc']; ?>" size="10" onBlur="totaloc()">--></td>
          </tr>
          <tr>            
            <td id="fuente3"><!--<strong>TOLERANCIA +</strong>
              <input type="text" name="valor_iva_oc" value="<?php echo $row_orden_compra['valor_iva_oc']; ?>" size="10" onBlur="totaloc()">-->
              <strong>TOTAL $</strong>
              <input name="int_total_oc" type="text" id="int_total_oc" value="<?php echo numeros_format($subtotal); ?>" size="10" readonly></td>
            </tr>
            <tr>
              <td id="fuente6">&nbsp;</td>
              <td id="fuente6">&nbsp;</td>
              <td id="fuente6">&nbsp;</td>
            </tr>
            <tr>
              <td id="detalle3"><strong>ENVIAR FACTURAS TRAS:</strong>
                <select name="b_facturas_oc" id="b_facturas_oc">
                  <option value="0"<?php if (!(strcmp("0",$row_orden_compra['b_facturas_oc']))){echo "selected=\"selected\"";} ?>>NO</option>
                  <option value="1"<?php if (!(strcmp("1",$row_orden_compra['b_facturas_oc']))){echo "selected=\"selected\"";} ?>>SI</option>
                </select></td>
                <td id="detalle3"><strong>CONTROL DE NUM. EN REMISION:</strong>
                  <select name="b_num_remision_oc" id="b_num_remision_oc">
                    <option value="0"<?php if (!(strcmp("0",$row_orden_compra['b_num_remision_oc']))){echo "selected=\"selected\"";} ?>>NO</option>
                    <option value="1"<?php if (!(strcmp("1",$row_orden_compra['b_num_remision_oc']))){echo "selected=\"selected\"";} ?>>SI</option>

                  </select></td>
                  <td id="detalle3"><strong>VNTA POR WEB</strong>
                    <select name="vta_web_oc" id="vta_web_oc">
                      <option value="0"<?php if (!(strcmp("0",$row_orden_compra['vta_web_oc']))){echo "selected=\"selected\"";} ?>>NO</option>
                      <option value="1"<?php if (!(strcmp("1",$row_orden_compra['vta_web_oc']))){echo "selected=\"selected\"";} ?>>SI</option>
                    </select></td>
                  </tr>
                  <tr>
                    <td id="detalle3"><strong>FACTURAR CIRELES</strong>
                      <select name="b_factura_cirel_oc" id="b_factura_cirel_oc">
                        <option value="0"<?php if (!(strcmp("0",$row_orden_compra['b_factura_cirel_oc']))){echo "selected=\"selected\"";} ?>>NO</option>
                        <option value="1"<?php if (!(strcmp("1",$row_orden_compra['b_factura_cirel_oc']))){echo "selected=\"selected\"";} ?>>SI</option>
                      </select></td>
                      <td id="detalle3"><strong>EXPORTACION
                        <select name="expo_co" id="expo_oc">
                          <option value="0"<?php if (!(strcmp("0",$row_orden_compra['expo_oc']))){echo "selected=\"selected\"";} ?>>NO</option>
                          <option value="1"<?php if (!(strcmp("1",$row_orden_compra['expo_oc']))){echo "selected=\"selected\"";} ?>>SI</option>
                        </select>
                      </strong></td>
                      <td id="detalle3"><strong>SALIDAS TIPO:</strong>
                        <select name="salida_oc" id="salida_oc" style="width:80px">
                          <option value="0"<?php if (!(strcmp("0",$row_orden_compra['salida_oc']))){echo "selected=\"selected\"";} ?>>Normal</option>
                          <option value="1"<?php if (!(strcmp("1",$row_orden_compra['salida_oc']))){echo "selected=\"selected\"";} ?>>Reposiciones</option>
                          <option value="2"<?php if (!(strcmp("2",$row_orden_compra['salida_oc']))){echo "selected=\"selected\"";} ?>>Muestras</option>
                          <option value="3"<?php if (!(strcmp("3",$row_orden_compra['salida_oc']))){echo "selected=\"selected\"";} ?>>Salidas especiales</option>
                        </select></td>
                      </tr>
                      <tr>
                        <td id="fuente4">&nbsp;</td>
                        <td id="fuente4">&nbsp;</td>
                        <td id="fuente4">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="fuente1">&nbsp;</td>
                        <td id="fuente1">&nbsp;</td>
                        <td id="fuente1">&nbsp;</td>
                      </tr>
                      
                      <tr>
                        <td colspan="2" id="detalle2"><strong>ADJUNTAR ARCHIVO 1</strong>
                          <input name="str_archivo_oc" type="file" size="100" maxlength="100" class="botones_file">
                          <input type="hidden" name="adjunto1" value="<?php echo $row_orden_compra['str_archivo_oc'];?>"/>
                        </td>
                        <td id="detalle2">
                          <a href="javascript:verFoto('pdfacturasoc/<?php echo $row_orden_compra['str_archivo_oc'];?>','610','490')"> 
                            <?php if($row_orden_compra['str_archivo_oc']!='') echo "ADJUNTAR ARCHIVO 1"; ?>
                          </a> 
                        </tr>
                        <tr>
                          <td colspan="2" id="detalle2"><strong>ADJUNTAR ARCHIVO 2</strong>
                            <input name="adjunto2" type="file" size="100" maxlength="100" class="botones_file">
                            <input type="hidden" name="adjunto22" value="<?php echo $row_orden_compra['adjunto2'];?>"/>
                          </td>
                          <td id="detalle2">
                            <a href="javascript:verFoto('pdfacturasoc/<?php echo $row_orden_compra['adjunto2'];?>','610','490')"> 
                              <?php if($row_orden_compra['adjunto2']!='') echo "ADJUNTAR ARCHIVO 2"; ?>
                            </a> 
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2" id="detalle2"><strong>ADJUNTAR ARCHIVO 3</strong>
                            <input name="adjunto3" type="file" size="100" maxlength="100" class="botones_file">
                            <input type="hidden" name="adjunto33" value="<?php echo $row_orden_compra['adjunto3'];?>"/>
                          </td>
                          <td id="detalle2">
                            <a href="javascript:verFoto('pdfacturasoc/<?php echo $row_orden_compra['adjunto3'];?>','610','490')"> 
                              <?php if($row_orden_compra['adjunto3']!='') echo "ADJUNTAR ARCHIVO 3"; ?>
                            </a> 
                          </td>
                        </tr>
                        <tr>
                          <td colspan="3" id="detalle">&nbsp;</td>
                        </tr>
                        <tr>
                          <td id="dato1"><strong>VENDEDOR </strong></td>
                          <td id="dato1"><strong>ELABORÓ </strong></td>
                          <!-- <td id="dato1"><strong>APROBADO POR</strong></td> -->
                          <td id="dato1"><strong>ESTADO DE LA ORDEN DE COMPRA</strong></td>
                        </tr>
                        <tr>
                          <td id="dato1"><strong>
                            <?php   
                            $idoc = $row_orden_compra['id_pedido']; 
                            $row_vendio = $conexion->llenarCampos("vendedor ver", "LEFT JOIN tbl_items_ordenc itm on  ver.id_vendedor=itm.int_vendedor_io WHERE itm.id_pedido_io='$idoc'","","distinct ver.nombre_vendedor");  
                            ?> 
                            <input name="vendedor" type="text" id="vendedor" value="<?php echo $row_vendio['nombre_vendedor'];?>" size="30"required="required" readonly>
                          </strong></td>
                          <td id="dato1"><strong>
                            <input name="str_elaboro_oc" type="text" id="str_elaboro_oc" value="<?php echo $row_orden_compra['str_elaboro_oc'];?>" size="30"required="required" readonly>
                          </strong></td>
                          <!-- <td id="dato1"><input name="str_aprobo_oc" type="text" id="str_aprobo_oc" onKeyUp="conMayusculas(this)" value="<?php echo $row_orden_compra['str_aprobo_oc'];?>" size="30" required></td> -->
                          <td id="dato1"><select name="b_estado_oc" id="b_estado_oc" onChange="ActualizarEstadosOc(this)">
                            <option value="1"<?php if(!(strcmp("1",$row_orden_compra['b_estado_oc']))){echo "selected=\"selected\"";} ?> >INGRESADA</option>
                            <option value="2"<?php if(!(strcmp("2",$row_orden_compra['b_estado_oc']))){echo "selected=\"selected\"";} ?> >PROGRAMADA O INVENTARIO</option>
                            <option value="3"<?php if(!(strcmp("3",$row_orden_compra['b_estado_oc']))){echo "selected=\"selected\"";} ?> >REMISIONADA</option>
                            <option value="4"<?php if(!(strcmp("4",$row_orden_compra['b_estado_oc']))){echo "selected=\"selected\"";} ?> >FACTURADA PARCIAL</option>
                            <option value="5"<?php if(!(strcmp("5",$row_orden_compra['b_estado_oc']))){echo "selected=\"selected\"";} ?> >FACTURADA TOTAL</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente5">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" id="dato1">Responsable Modifica:<input name="str_responsable_oc" readonly="readonly"  type="text" value="<?php echo $_SESSION['Usuario']; ?>"></td>
                        </tr>
                        <tr>
                         <td colspan="3" id="dato3"><p><br><br><br>
                          <a class="botonGMini" target="_blank"  href="view_index.php?c=ocomercial&a=Crud&id=<?php echo $row_orden_compra['id_pedido']; ?>&columna=id_pedido&id_oc=<?php echo $_GET['id_oc']; ?>&tabla=tbl_orden_compra_historico">VER HISTORICO GENERAL DE LA O.C</a> 

                          <a class="botonGMini" target="_blank"  href="view_index.php?c=ocomercial&a=Crud&id=<?php echo $row_orden_compra['id_pedido']; ?>&columna=id_pedido_io&id_oc=<?php echo $_GET['id_oc']; ?>&tabla=tbl_items_ordenc_historico">VER HISTORICO ITEMS DE LA O.C</a>

                        </p>
                        <P><br><br><br> </P>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" id="fuente2"><input class="botonGeneral" type="submit" value="EDITAR"  ></td>
                    </tr>
                  </table>
                  <input type="hidden" name="MM_update" value="form1"> 
                  <!--<input type="hidden" name="str_numero_oc" value="<?php echo $row_orden_compra['str_numero_oc']; ?>">-->
                </form>
              </td> </tr> </table>
            <?php echo $conexion->header('footer'); ?>
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
mysql_free_result($Result1);
mysql_free_result($Result4);
mysql_free_result($Result3);
mysql_free_result($Result5);
mysql_free_result($usuario);
mysql_free_result($orden_compra);
mysql_free_result($cliente);
mysql_free_result($detalle);
mysql_free_result($vendedores);

/*mysql_close($conexion1); */
?>