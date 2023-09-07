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

$editFormAction = $_SERVER['PHP_SELF'];

$conexion = new ApptivaDB();

if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

  $directorio = "pdfacturasoc/";
  $tieneadjunto1 = adjuntarArchivo('', $directorio, $_FILES['str_archivo_oc']['name'],$_FILES['str_archivo_oc']['tmp_name'],'NUEVOS');
  $tieneadjunto2 = adjuntarArchivo('', $directorio, $_FILES['adjunto2']['name'],$_FILES['adjunto2']['tmp_name'],'NUEVOS');
  $tieneadjunto3 = adjuntarArchivo('', $directorio, $_FILES['adjunto3']['name'],$_FILES['adjunto3']['tmp_name'],'NUEVOS'); 
 /* $ocInterno =  $_POST['b_oc_interno']==true ? "1":"0" ;
 $insertSQL = $conexion->insertar("tbl_orden_compra","str_numero_oc, id_c_oc, str_nit_oc, fecha_ingreso_oc, str_condicion_pago_oc, str_observacion_oc, int_total_oc, b_facturas_oc, b_num_remision_oc, b_factura_cirel_oc, str_dir_entrega_oc, str_archivo_oc, adjunto2, adjunto3, str_elaboro_oc, str_aprobo_oc, b_estado_oc, str_responsable_oc, b_borrado_oc, salida_oc, b_oc_interno,vta_web_oc, expo_oc"," '".$_POST['str_numero_oc']."','".$_POST['id_c_oc']."','".$_POST['nit_c']."','".$_POST['fecha_ingreso_oc']."','".$_POST['str_condicion_pago_oc']."','".$_POST['str_observacion_oc']."','".$_POST['int_total_oc']."','".$_POST['b_facturas_oc']."','".$_POST['b_num_remision_oc']."','".$_POST['b_factura_cirel_oc']."','".$_POST['str_dir_entrega_oc']."','".$_POST['b_num_remision_oc']."','".$tieneadjunto1."','".$tieneadjunto2."','".$tieneadjunto3."','".$_POST['str_elaboro_oc']."','".$_POST['str_aprobo_oc']."','".$_POST['b_estado_oc']."','".$_POST['str_responsable_oc']."','".$_POST['b_borrado_oc']."','".$_POST['salida_oc']."','$ocInterno','".$_POST['vta_web_oc']."','".$_POST['expo_oc']."' ");*/
 
 
/* $os = array("AC-", "PW-", "PW1-", "TB-", "PP-");
 
   foreach ($value as  $value2) {
      if (in_array($value, $value2)) {
       $ocalimapiar2 =  explode($value,  $_POST['str_numero_oc'] ); 
      } 
     
    
    }*/

 /*$ocalimapiar =  explode("-",  $_POST['str_numero_oc'] );
 
 $resulrt = $conexion->insertar("tbl_orden_consecutivo","str_numero_oc"," '".$ocalimapiar[1]."' ");*/
 
 if(isset($_POST['str_numero_oc'])){ 
  $myObject = new oComercial();
   $existeorden=$myObject->Obtener('tbl_orden_compra','str_numero_oc', " '".$_POST['str_numero_oc']."'  " );
 } 
 if(isset($_POST['str_numero_oc']) && !$existeorden){

 $insertSQL = sprintf("INSERT INTO tbl_orden_compra ( str_numero_oc, id_c_oc, str_nit_oc, fecha_ingreso_oc,fecha_entrega_oc, str_condicion_pago_oc, str_observacion_oc, int_total_oc, b_facturas_oc, b_num_remision_oc, b_factura_cirel_oc, str_dir_entrega_oc, str_archivo_oc, adjunto2, adjunto3, str_elaboro_oc, str_aprobo_oc, b_estado_oc, str_responsable_oc, b_borrado_oc, salida_oc, b_oc_interno,vta_web_oc,expo_oc,autorizado, entrega_fac, fecha_cierre_fac, comprobante_ent,pago_pendiente,cobra_flete,precio_flete,tipo_despacho )VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s )",  
   GetSQLValueString($_POST['str_numero_oc'], "text"),
   GetSQLValueString($_POST['id_c_oc'], "int"), 
   GetSQLValueString($_POST['nit_c'], "text"),                     
   GetSQLValueString($_POST['fecha_ingreso_oc'], "text"),
   GetSQLValueString($_POST['fecha_entrega_oc'], "text"),
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
   GetSQLValueString(isset($_POST['b_oc_interno']) ? "true" : "", "defined","1","0"),
   GetSQLValueString($_POST['vta_web_oc'], "text"),
   GetSQLValueString($_POST['expo_oc'], "text"),
   GetSQLValueString($_POST['autorizado'], "text"),
   GetSQLValueString($_POST['entrega_fac'], "text"),
   GetSQLValueString($_POST['fecha_cierre_fac'], "text"),
   GetSQLValueString($_POST['comprobante_ent'], "text"),
   GetSQLValueString($_POST['pago_pendiente'], "text"),
   GetSQLValueString(isset($_POST['cobra_flete']) ? "true" : "", "defined","1","0"),
   GetSQLValueString($_POST['precio_flete'], "text"),
   GetSQLValueString($_POST['tipo_despacho'], "text")
   ); 
   
   mysql_select_db($database_conexion1, $conexion1);
   $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error()); 
 
}
//UPDATE A LA DIRECCION DE ENVIO DE FACTURA DEL CLIENTE
 $updateSQL = sprintf("UPDATE cliente SET direccion_c=%s, direccion_envio_factura_c=%s,plazo_aprobado_c=%s WHERE id_c=%s",
   GetSQLValueString($_POST['dir_c'], "text"),
   GetSQLValueString($_POST['str_dir_entrega_oc'], "text"),
   GetSQLValueString($_POST['str_condicion_pago_oc'], "text"),
   GetSQLValueString($_POST['id_oc'], "int"));


 mysql_select_db($database_conexion1, $conexion1); 
 $Result2 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  //GUARDADO DE HISTORICOS
 $myObject = new oComercial();
 $historico =  new oComercial();

 if(isset($_POST['str_numero_oc'])){ 
  $historico=$myObject->Obtener('tbl_orden_compra','str_numero_oc', " '".$_POST['str_numero_oc']."'  " );
} 

if(isset($_POST['str_numero_oc']) && $historico){
  $myObject->Registrar("tbl_orden_compra_historico", "id_pedido,str_numero_oc,id_c_oc,str_nit_oc,fecha_ingreso_oc,fecha_entrega_oc,str_condicion_pago_oc,str_observacion_oc,int_total_oc,b_facturas_oc,b_num_remision_oc,b_factura_cirel_oc,str_dir_entrega_oc,str_archivo_oc,adjunto2,adjunto3,str_elaboro_oc,str_aprobo_oc,b_estado_oc,str_responsable_oc,b_borrado_oc,salida_oc,b_oc_interno,vta_web_oc,expo_oc,autorizado,tb_pago,factura_oc,entrega_fac,fecha_cierre_fac,comprobante_ent,estado_cartera,tipo_pago_cartera,valor_cartera,modifico", $historico);
  }//FIN HISTORICO

//ENVIO CORREO JOSE ADMINISTRADOR
  $oc=$_POST['str_numero_oc_r'];
  $fec=$_POST['fecha_ingreso_oc'];
  $cant=$_POST['int_total_oc'];

/*           $headers = "MIME-Version: 1.0\r\n"; 
           $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
           //dirección del remitente 
           $headers .= "From: ACYCIA\r\n"; 
           //dirección de respuesta, si queremos que sea distinta que la del remitente 
           $headers .= "ACYCIA\r\n";         
           $to = 'jcarvajal@acycia.com';  //enviar al correo su carnet
           $mensaje = "<p>Orden de Compra Ingresada: $oc, Fecha Ingreso: $fec,  Cantidad: $cant,</p></b>";           
                   $mensaje .= "<p>Revisa en el siguiente Link: <a href='http://intranet.acycia.com/orden_compra1_cl.php?str_numero_oc=$oc&id_c=0&nit_c=0&estado_oc=0' target='_blank'>Ver Orden de Compra</a></p></b>";
           $mensaje .= "<p><span style=\"color: #FF0000\"><strong>Nota Importante: </strong></span>Recuerde que es necesario haber iniciado sesion en la pagina de SISAGE, para poder abrir este link, Gracias. </p></b>";
           (mail("$to","Orden de Compra Ingresada: $oc, Fecha Ingreso: $fec, Cantidad: $cant, ",$mensaje,$headers));*/

           $insertGoTo = "orden_compra_cl_edit.php?str_numero_oc=" . $_POST['str_numero_oc'] . "&id_oc=" . $_POST['id_c_oc'] . "";
           /*$insertGoTo = "orden_compra_cl_add_detalle.php?str_numero_oc=" . $_POST['str_numero_oc'] . "";*/
           if (isset($_SERVER['QUERY_STRING'])) {
            $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
            $insertGoTo .= $_SERVER['QUERY_STRING'];
          }
          header(sprintf("Location: %s", $insertGoTo));
        } 
        

        $colname_cliente = "-1";
       /* if (isset($_GET['id_oc'])) {
          $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['id_oc'] : addslashes($_GET['id_oc']);
        } 
        $row_cliente = $conexion->llenarCampos('cliente',"WHERE id_c=".$colname_cliente,'ORDER BY nombre_c ASC',"*"); */

 
         //$row_nit = $conexion->llenaSelect('cliente',"",'ORDER BY nit_c DESC',"id_c,nombre_c,nit_c"); //demora la carga de la pagina y combos
         //$row_nombres = $conexion->llenaSelect('cliente',"",'ORDER BY nombre_c ASC',"id_c,nombre_c,nit_c"); //demora la carga de la pagina y combos

        $colname_detalle = "-1";
        if (isset($_GET['str_numero_oc'])) {
          $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['str_numero_oc'] : addslashes($_GET['str_numero_oc']);
        }

        $select_tiquete_num = $conexion->llenaSelect('tbl_items_ordenc',"WHERE tbl_items_ordenc.str_numero_io ='".$colname_detalle."' ",'ORDER BY id_items ASC');

        $row_detalle = $conexion->llenarCampos("tbl_items_ordenc","WHERE tbl_items_ordenc.str_numero_io =".$colname_detalle, "ORDER BY id_items DESC","id_items"); 
 
        //antiguoo filtros nit yy cliente
/*        mysql_select_db($database_conexion1, $conexion1);
        $query_clientes = ("SELECT * FROM cliente ORDER BY nombre_c ASC");
        $clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
        $row_nombres = mysql_fetch_assoc($clientes);
        $totalRows_clientes = mysql_num_rows($clientes);

        $colname_cliente = "-1";
        if (isset($_GET['id_oc'])) {
          $colname_cliente = (get_magic_quotes_gpc()) ? $_GET['id_oc'] : addslashes($_GET['id_oc']);
        }
        mysql_select_db($database_conexion1, $conexion1);
        $query_cliente = sprintf("SELECT * FROM cliente WHERE id_c = %s", $colname_cliente);
        $cliente = mysql_query($query_cliente, $conexion1) or die(mysql_error());
        $row_cliente = mysql_fetch_assoc($cliente);
        $totalRows_cliente = mysql_num_rows($cliente);*/

        ?>
        <html>
        <head>
          <title>SISADGE AC &amp; CIA</title>
          <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
 
          <script type="text/javascript" src="js/ordenCompra.js"></script>
 
          <link href="css/formato.css" rel="stylesheet" type="text/css" />
          <script type="text/javascript" src="js/formato.js"></script>
          <script type="text/javascript" src="js/consulta.js"></script>
          <script type="text/javascript" src="js/listado.js"></script>
          <script type="text/javascript" src="js/validacion_numerico.js"></script>
          <script type="text/javascript" src="AjaxControllers/js/consultas.js"></script>
          <script type="text/javascript" src="AjaxControllers/js/ordenCompraDet.js"></script>
          <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
          
          <link rel="stylesheet" type="text/css" href="css/general.css"/>
          <link rel="stylesheet" type="text/css" href="css/desplegable.css" />

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
          <!-- css Bootstrap-->
          <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

          <!-- Select3 Nuevo -->
          <meta charset="UTF-8">
          <!-- jQuery -->
          <script src='select3/assets/js/jquery-3.4.1.min.js' type='text/javascript'></script>

          <!-- select2 css -->
          <link href='select3/assets/plugin/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

          <!-- select2 script -->
          <script src='select3/assets/plugin/select2/dist/js/select2.min.js'></script>
          <!-- Styles -->
          <link rel="stylesheet" href="select3/assets/css/style.css">
          <!-- Fin Select3 Nuevo -->

            <style type="text/css">
          .loader {
              position: fixed;
              left: 0px;
              top: 0px;
              width: 100%;
              height: 100%;
              z-index: 3200;
              background: url('images/loadingcircle4.gif') 50% 50% no-repeat rgb(250,250,250);
              background-size: 5% 10%;/*tamaño del gif*/
              -moz-opacity:65;
              opacity:0.65;

          }
          </style>
        </head>

        <body> 
          <script>
            $(document).ready(function() { $(".combos").select2(); });
          </script>
          <?php echo $conexion->header('vistas'); ?>
           <table align="center" id="tabla"><tr align="center"><td>  
                <form action="view_index.php?c=ocomercial&a=Guardaroc" method="post"  enctype="multipart/form-data" name="form1" id="form1" ><!--  && validacion_select_oc() -->
                            <table id="tabla">
                              <tr id="tr1">
                                <td nowrap id="codigo">CODIGO : A3 - F02</td>
                                <td nowrap id="titulo2">ORDEN DE COMPRA </td>
                                <td nowrap id="codigo">VERSION : 0</td>
                              </tr>
                              <tr>
                                <td rowspan="10" id="dato2" ><img src="images/logoacyc.jpg"></td>
                                <td id="subtitulo">&nbsp;</td>
                                <td id="dato2"> <img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="orden_compra_cl2.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" title="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
                              </tr>
                              <tr>
                                <td id="numero1">PREFIJO
                                <select   name="prefijo" id="prefijo"  style="width:82px">
                                 <option value="">PREFIJO</option>
                                 <option value="AC">AC</option>
                                 <option value="PW">PW</option> 
                                 <option value="PW1">PW-1</option> 
                                 <option value="TB">TB</option> 
                                 <option value="PP">PP</option>
                               </select>
                              
                           </td> 
                             </tr>
                             <tr>
                              <td id="numero1" nowrap>N&deg; 
                                <input name="str_numero_oc" type="text" class="rojo_inteso" id="str_numero_oc" size="10" onChange="return limpiaEspacios(this);" required  ><!--  onBlur="if (form1.str_numero_oc.value) { DatosGestiones('5','str_numero_oc',form1.str_numero_oc.value);}" -->              
                                <!-- onClick="addoc();"  --> <div style="display: none;" > <input  name="b_oc_interno" type="checkbox"  value="" id="b_oc_interno" ></div>
                                <span style="display: none;" id="oc_internas"  class="centrado1">O.C INTERNA</span> <br>
                                <span id="mensaje" ></span>
                                

                                <input name="sioc" id="sioc" type="hidden" value="<?php $pieza = explode("AC-", $row_orden_compra['str_numero_oc']);echo 'AC-';echo $pieza[1]+1;?>">
                              </td>
                              <td id="dato_1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="2" id="numero1"><div id="resultado"></div>&nbsp;</td>
                            </tr>
                            <tr>
                              <td id="fuente1">FECHA INGRESO</td>
                              <td id="dato_1">&nbsp;</td>
                            </tr>
                            <tr>
                              <td id="dato1">
                                <input name="fecha_ingreso_oc" id="fecha_ingreso_oc" required type="date" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" autofocus />
                                <?php $fechas = date('Y-m-d'); ?>
                                 <input name="fecha_entrega_oc" id="fecha_entrega_oc" type="text" min="2000-01-02" value="<?php echo sumarMesyDias($fechas,3);?>" size="10" />        
                              </td>
                              <td rowspan="5" id="dato1"> <!-- TRM: <div id="IndEcoBasico"><a href="http://dolar.wilkinsonpc.com.co/" target="_blank" ></a></div><script type="text/javascript" src="http://dolar.wilkinsonpc.com.co/js/ind-eco-basico.js?ancho=170&alto=85&fsize=10&ffamily=sans-serif"></script>  --></td>
                            </tr>
                            <tr>
                              <td id="fuente1">CLIENTE</td> 
                            </tr>
                            <tr>
                              <td colspan="2" id="dato1"> 
                                <select class="elcliente"  name="id_oc" id="cliente" onChange="if (form1.id_oc.value) {consultaclienteadd_oc(form1.id_oc.value), verConsultaAlertGenerico('idcliente',form1.id_oc.value,'El Cliente tiene Cartera pendiente ') } else { swal('DEBE SELECCIONAR CLIENTE '); }" style="width:250px">
                                     <option value='0'>SELECCIONE CLIENTE</option>
                                   </select>
                                 </select>

                                <!--  <select class="elcliente" name="id_oc" id="cliente" onChange="if (form1.id_oc.value) {consultaclienteadd_oc(form1.id_oc.value), verConsultaAlertGenerico('idcliente',form1.id_oc.value,'El Cliente tiene Cartera pendiente ') } else { swal('DEBE SELECCIONAR CLIENTE '); }" style="width:250px">
                                   <option value="0">SELECCIONE CLIENTE</option>
                                   <?php  foreach($row_nombres as $row_nombres ) { ?>
                                     <option value="<?php echo $row_nombres['id_c']?>"<?php if (!(strcmp($row_nombres['id_c'],$row_nombres['id_oc']))){echo "selected=\"selected\"";} ?>><?php $cadd=($row_nombres['nombre_c']); echo $cadd;?></option>
                                   <?php } ?>
                                 </select> -->

                                 

                                <!-- <select class="combos" name="id_oc" id="cliente" onChange="if (form1.id_oc.value) { consultaclienteadd_oc(form1.id_oc.value); } else { alert('DEBE SELECCIONAR CLIENTE '); }" style="width:250px">
                                  <option value="0" <?php if (!(strcmp(0, $row_nombres['id_oc']))) {echo "selected=\"selected\"";} ?>>SELECCIONE CLIENTE</option>
                                  <?php
                                  do {  
                                    ?>
                                    <option value="<?php echo $row_nombres['id_c']?>"<?php if (!(strcmp($row_nombres['id_c'], $row_nombres['id_oc']))) {echo "selected=\"selected\"";} ?>><?php $cadd=($row_nombres['nombre_c']); echo $cadd;?></option>
                                    <?php
                                  } while ($row_nombres = mysql_fetch_assoc($clientes));
                                  $rows = mysql_num_rows($clientes);
                                  if($rows > 0) {
                                    mysql_data_seek($clientes, 0);
                                    $row_nombres = mysql_fetch_assoc($clientes);
                                  }
                                  ?>
                                </select> -->
                              </td>
                            </tr>
                            <tr>
                              <td colspan="2" id="dato1">NIT</td>
                            </tr>
                            <tr>
                              <td colspan="2" id="dato4">
                                 

                                 <select class="elnit" name="id_nit_oc" id="nit" onChange="if (form1.id_nit_oc.value) { consultaclienteadd_oc(form1.id_nit_oc.value); } else { alert('DEBE SELECCIONAR NIT '); }" style="width:250px">
                                   <option value="0">SELECCIONE NIT</option> 
                                 </select>

                                 <!-- <select class="elcliente" name="id_nit_oc" id="nit" onChange="if (form1.id_nit_oc.value) { consultaclienteadd_oc(form1.id_nit_oc.value); } else { swal('DEBE SELECCIONAR NIT '); }" style="width:250px">
                                   <option value="0">SELECCIONE NIT</option>
                                   <?php  foreach($row_nit as $row_nit ) { ?>
                                     <option value="<?php echo $row_nit['id_c']?>"<?php if (!(strcmp($row_nit['id_c'], $_GET['id_oc']))) {echo "selected=\"selected\"";} ?>><?php echo $row_nit['nit_c']?></option>
                                   <?php } ?>
                                 </select>  -->

                                  

                                <!-- <select class="combos" name="id_nit_oc" id="nit" onChange="if (form1.id_nit_oc.value) { consultaclienteadd_oc(form1.id_nit_oc.value); } else { alert('DEBE SELECCIONAR NIT '); }" style="width:250px">
                                  <option value="0" <?php if (!(strcmp(0, $row_nombres['id_oc']))) {echo "selected=\"selected\"";} ?>>SELECCIONE NIT</option>
                                  <?php 
                                  do {  
                                    ?>
                                    <option value="<?php echo $row_nombres['id_c']?>"<?php if (!(strcmp($row_nombres['id_c'], $_GET['id_oc']))) {echo "selected=\"selected\"";} ?>><?php echo $row_nombres['nit_c']?></option>
                                    <?php
                                  } while ($row_nombres = mysql_fetch_assoc($clientes));
                                  $rows = mysql_num_rows($clientes);
                                  if($rows > 0) {
                                    mysql_data_seek($clientes, 0);
                                    $row_nombres = mysql_fetch_assoc($clientes);
                                  }
                                  ?>
                                </select> -->
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3" id="detalle2">
                                <div id="definicion_oc">
                                  <table id="tabla"><!--se cambio tabla2 x tabla1-->
                                    <tr>
                                      <td id="dato1" width="50%"><strong>NIT : </strong><?php echo $row_cliente['nit_c']; ?></td>
                                      <td id="dato1" width="50%"><strong>PAIS / CIUDAD : </strong><?php  $cad=htmlentities ($row_cliente['pais_c']);echo $cad; ?> / <?php $cad2=htmlentities ($row_cliente['ciudad_c']); echo $cad2;?></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" id="dato1"><strong>NOMBRE DE LA EMPRESA: </strong><?php $cad4=htmlentities ($row_cliente['nombre_c']);echo $cad4; ?></td>
                                    </tr>
                                    <tr>
                                      <td id="dato1"><strong>DIRECCCION C:</strong><?php $dir=$row_cliente['direccion_c'] ?> <?php echo $dir; ?>
                                      <input name="dir_c" type="hidden" value="<?php echo limpiarCaracteresEspeciales($dir); ?>"></td>
                                      <td id="dato1"><strong>TELEFONO:</strong><?php echo $row_cliente['telefono_c']; ?></td>
                                    </tr>
                                    <tr>
                                      <td id="dato1"><strong>CONTACTO COMERCIAL: </strong> <?php echo $row_cliente['contacto_c']; ?></td>
                                      <td id="dato1"><strong>TEL COMERCIAL:</strong><?php echo $row_cliente['telefono_contacto_c']; ?></td>
                                    </tr>
                                    <tr>
                                      <td id="dato1"><strong>EMAIL COMERCIAL: </strong><?php echo $row_cliente['email_comercial_c']; ?></td>
                                      <td id="dato1"><strong>CONDICIONES DE PAGO:</strong>
                                        <select name="str_condicion_pago_oc" id="str_condicion_pago_oc">
                                          <option value="ANTICIPADO"<?php if (!(strcmp("ANTICIPADO", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Anticipado</option>
                                          <option value="PAGO DE CONTADO"<?php if (!(strcmp("PAGO DE CONTADO", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago de Contado</option>
                                          <option value="PAGO A 15 DIAS"<?php if (!(strcmp("PAGO A 15 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 15 Dias </option>
                                          <option value="PAGO A 30 DIAS"<?php if (!(strcmp("PAGO A 30 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 30 Dias </option>
                                          <option value="PAGO A 45 DIAS"<?php if (!(strcmp("PAGO A 45 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 45 Dias </option>
                                          <option value="PAGO A 60 DIAS"<?php if (!(strcmp("PAGO A 60 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 60 Dias </option>
                                          <option value="PAGO A 90 DIAS"<?php if (!(strcmp("PAGO A 90 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 90 Dias </option>
                                          <option value="PAGO A 120 DIAS"<?php if (!(strcmp("PAGO A 120 DIAS", $row_cliente['plazo_aprobado_c']))) {echo "selected=\"selected\"";} ?>>Pago a 120 Dias </option>
                                        </select>
                                        <input name="id_c_oc" id="id_c_oc" type="hidden" value="<?php echo $row_cliente['id_c']; ?>">
                                        <input name="nit_c" type="hidden" id="nit_c" value="<?php echo $row_cliente['nit_c'] ?>"></td>
                                      </tr>
                                      <tr>
                                        <td id="dato1"><strong>DIRECCION ENVIO DE FACTURA:</strong>
                                          <?php $dir_limpia = $row_cliente['direccion_envio_factura_c'];?>
                                          <textarea cols="40" name="str_dir_entrega_oc" id="str_dir_entrega_oc" onKeyUp="conMayusculas(this)" rows="2"><?php echo ($dir_limpia); ?></textarea>
                                        </td>
                                        <td id="dato1"><?php if ($row_cliente['id_c']!='') { ?>
                                          <a href="perfil_cliente_edit.php?id_c=<?php echo $row_cliente['id_c'] ?>" target="_blank">ACTUALIZAR PERFIL CLIENTE</a>
                                          <?php }?></td>
                                        </tr>
                                        <tr>
                                          <td id="dato1"><strong>Se entrega Factura? </strong>
                                            <select name="entrega_fac" id="entrega_fac" required="required" > 
                                              <option value="SI">SI</option>
                                              <option value="NO">NO</option>
                                              <option value="">Seleccione...</option>
                                            </select>
                                          </td>
                                          <td id="dato1"><strong>Fecha Cierre Facturacion:</strong>
                                            <input type="date" name="fecha_cierre_fac" id="fecha_cierre_fac" value="" size="10">
                                          </td> 
                                        </tr>
                                        <tr>
                                         <td id="dato1"><strong>Adjuntar Comprobante? </strong>
                                          <select name="comprobante_ent" id="comprobante_ent" > 
                                            <option value="NO">NO</option>
                                            <option value="SI">SI</option>
                                            <option value="">Seleccione...</option>
                                          </select>

                                        </td>
                                        <td id="dato1"> 
                                          <strong>Tipo de O.C </strong>
                                           <select name="tipo_despacho" id="tipo_despacho" required="required" >
                                            <option value="">Seleccione...</option> 
                                            <option value="despacho">Para despacho</option> 
                                            <option value="inventario">Para inventario</option>
                                          </select>
                                        </td>

                                      </tr>
                                      <tr>
                                       <td colspan="2" id="dato2">
                                        <span id="alertG" style="color: red;" > </span>
                                        <strong><?php  if ($row_cliente['id_c']!=''){ ?>
                                        <input id="additem" class="botonGeneral" onclick="guardaRegistro()" value="ADD ITEM"><!-- type="submit"  -->
                                        <!-- <a href="javascript:envio_form()"><img src="images/mas.gif" alt="<?php echo $muestra;?>"title="ADD ITEM" border="0" style="cursor:hand;"  /> AGREGAR ITEM</a>--><?php }?>
                                      </strong></td>
                                    </tr>
                                 </table>
                               </div>
                             </td>
                           </tr>         
                            <tr>
                             <td id="dato1"> <br>
                               <strong>Tipo de O.C </strong>
                                <select name="tipo_despacho" id="tipo_despacho" required="required" >
                                 <option value="">Seleccione...</option> 
                                 <option value="despacho">Para despacho</option> 
                                 <option value="inventario">Para inventario</option>
                               </select>
                             </td>
                           </tr>
                           <tr>
                            <td  id="detalle1">
                              Cobra Flete: 
                              <input type="checkbox" name="cobra_flete" id="cobra_flete" value="1" onClick="flete(); " > <span style="display: none;" id="recuadro" class="recuadro"><input name="precio_flete" type="text" id="precio_flete"  min="0"  style="width:100px" value=""  /></span>
                            </td>
                            <td  id="dato1"><strong>Proforma Pendiente de Pago? </strong>
                             <select name="pago_pendiente" id="pago_pendiente" required="required" >
                              <option value="">Seleccione...</option> 
                              <option value="NO">NO</option> 
                              <option value="SI">SI</option>
                            </select>
                          </td>
                          <td colspan="2" id="dato1"> <strong>Autoriza Despacho? </strong>
                           <select name="autorizado" id="autorizado"> 
                             <option value="NO">NO</option> 
                             <option value="SI">SI</option>
                           </select> 
                         </td>
                         <td><p> <br> &nbsp;&nbsp;&nbsp;&nbsp;</p></td>
                       </tr>  
                       <tr id="tr2">
                        <td colspan="4" id="dato2">
                        </td>
                      </tr> 

                      <tr>
                        <td  colspan="4"><div class="alert alert-danger verAlert" style="display: none;" ></div></td>
                      </tr> 
                      <tr>
                        <td colspan="2" id="fuente1">OBSERVACIONES:</td>            
                        <td id="fuente3">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" rowspan="2" id="fuente1"><textarea name="str_observacion_oc" cols="50" onKeyUp="conMayusculas(this)" rows="3" id="str_observacion_oc"></textarea></td>
                        <td id="fuente3"></td>
                      </tr>
                      <tr>            
                        <td id="fuente3">
                          <strong>TOTAL $</strong>:
                          <input name="int_total_oc" type="text" id="int_total_oc" value="<?php echo $subtotal; ?>" size="10" onBlur="puntos(this,this.value.charAt(this.value.length-1))" readonly></td>
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente8">&nbsp;</td>
                        </tr>
                        <tr>
                          <td id="detalle3"><strong>ENVIAR FACTURAS TRAS:</strong>
                            <select name="b_facturas_oc" id="b_facturas_oc">
                              <option value="0">NO</option>
                              <option value="1">SI</option>
                            </select></td>
                            <td id="detalle3"><strong>CONTROL DE NUM. EN REMISION:</strong>
                              <select name="b_num_remision_oc" id="b_num_remision_oc">
                                <option value="0">NO</option>
                                <option value="1">SI</option>

                              </select></td>
                              <td id="detalle3"><strong>VNTA POR WEB</strong>
                                <select name="vta_web_oc" id="vta_web_oc">
                                  <option value="0">NO</option>
                                  <option value="1">SI</option>
                                </select></td>
                              </tr>
                              <tr>
                                <td id="detalle3"><strong>FACTURAR CIRELES:</strong>
                                  <select name="b_factura_cirel_oc" id="b_factura_cirel_oc">
                                    <option value="0">NO</option>
                                    <option value="1">SI</option>
                                  </select></td>
                                  <td id="detalle3"><strong>EXPORTACION
                                    <select name="expo_co" id="expo_oc">
                                      <option value="0">NO</option>
                                      <option value="1">SI</option>
                                    </select>
                                  </strong></td>
                                  <td id="detalle3"><strong>SALIDAS TIPO:</strong>
                                    <select name="salida_oc" id="salida_oc" style="width:80px">
                                      <option value="0">Normal</option>
                                      <option value="1">Reposiciones</option>
                                      <option value="2">Muestras</option>
                                      <option value="3">Salidas especiales</option>
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
                                    <td colspan="3" id="detalle2"><strong>ADJUNTAR ARCHIVO 1</strong>
                                      <input type="file" name="str_archivo_oc" id="str_archivo_oc"size="100"/></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" id="detalle2"><strong>ADJUNTAR ARCHIVO 2</strong>
                                        <input type="file" name="adjunto2" id="adjunto2"size="100"/></td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" id="detalle2"><strong>ADJUNTAR ARCHIVO 3</strong>
                                          <input type="file" name="adjunto3" id="adjunto3"size="100"/></td>
                                        </tr>
                                        <tr>
                                          <td colspan="3" id="detalle">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td id="dato1"><strong>ELABORA</strong></td>
                                          <td id="dato1"><strong>APROBADO POR</strong></td>
                                          <td id="dato1"><strong>ESTADO DE LA ORDEN DE COMPRA</strong></td>
                                        </tr>
                                        <tr>
                                          <td id="dato1"><strong>
                                            <input name="str_elaboro_oc" type="text" required id="str_elaboro_oc" value="<?php $nom= $_SESSION['Usuario'];echo $nom; ?>" size="30" readonly>
                                          </strong></td>
                                          <td id="dato1"><input onBlur='copiar_oc();' name="str_aprobo_oc" type="text" id="str_aprobo_oc" value="<?php echo $nom; ?>" onKeyUp="conMayusculas(this)" size="30"></td>
                                          <td id="dato1"><select name="b_estado_oc" id="b_estado_oc">
                                            <option value="1">INGRESADA</option>
                                                      <!-- <option value="2">PROGRAMADA O INVENTARIO</option>
                                                      <option value="3">REMISION</option>
                                                      <option value="4">FACTURADA PARCIAL</option>
                                                      <option value="5">FACTURADA TOTAL</option> -->
                                                    </select></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" id="fuente5">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" id="fuente2"><input type="hidden" name="MM_insert" value="form1">
                                                      <input type="hidden" name="b_borrado_oc" id="b_borrado_oc" value="0">
                                                      <input type="hidden" name="ordencompra" id="ordencompra" value="ordencompra">
                                                      <input name="str_responsable_oc" type="hidden" value="<?php echo $_SESSION['Usuario']; ?>"><input name='validar_oc' id='validar_oc' type='hidden' value='0'> 
                                                      <!-- <img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR" onClick="salir();" /> -->
                                                    </td>
                                                    </tr>
                                                  </table>
                                                </form>
                                                </td> </tr> </table>
                            <?php echo $conexion->header('footer'); ?>

                            <div id="contentbloq"></div> <!-- este bloquea pantalla evitando duplicidad -->
                      </body>
                      </html> 
                      <script type="text/javascript">
                        //bloquea el enter
                        document.addEventListener('DOMContentLoaded', () => {
                          document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
                            if(e.keyCode == 13) {
                              e.preventDefault();
                            }
                          }))
                        });

                      $("#prefijo").on( "change", function() {
                        cambioPrefijo(); 
                      });

                       function cambioPrefijo(){ 
                         verConsultaSencillo("prefijo",$("#prefijo").val(),"orden_compra_cl_add.php");  
                       }  

                       $("#str_numero_oc").on( "blur", function() {
                         cambiostr_numero_oc(); 
                       });

                        function cambiostr_numero_oc(){ 
                          consultaNumeroOrden("num_orden_compra",$("#str_numero_oc").val(),"orden_compra_cl_add.php");  
                        }   


                       

                      function guardaRegistro(){ 
                         
                          
                           var vista = 'view_index.php?c=ocomercial&a=Guardaroc';//'AjaxControllers/Actions/guardar.php'
                           var resul = validaTodo();

                            if(resul){
                               
                              $('#contentbloq').html('<div class="loader"></div>');
                                 setTimeout(function() { $(".loader").fadeOut("slow");},2000); 
                              //$('#additem').attr('disabled', true);
                                 //guardarGeneral(vista); 
                                  document.form1.submit(); 
                                //window.location = "orden_compra_cl_edit.php?str_numero_oc="+$('#str_numero_oc').val()+"&id_oc="+$('#id_c_oc').val();
                             
                            } 

                       }
                         function validaTodo(){
                           if (document.form1.validar_oc.value > "0"){ 
                             swal("EXISTE!", "EL NUMERO DE ORDEN YA EXISTE O EXISTEN CARACTERES EXTRAÑOS, FAVOR HACER REVISION ", "warning", {
                               button: " OK!",
                             });
                             document.form1.str_numero_oc.focus();
                              return false;
                           }
                           if( $("#tipo_despacho").val()==''){
                            swal("Seleccione Tipo de O.C")
                              return false;  
                           }
                           if($("#pago_pendiente").val()==''){
                            swal("Seleccione Proforma Pendiente")
                              return false;   
                           }
                           if($("#autorizado").val()==''){
                            swal("Seleccione Autoriza despacho")
                              return false;   
                           }
                           if($("#cliente").val()==''){
                            swal("Seleccione Nit")
                              return false;   
                           } 
                          
                          return true;
                        

                       } 
                         
    //FILTROS
   $(document).ready(function(){  
         $('.elcliente').select2({ 
             ajax: {
                 url: "select3/proceso.php",
                 type: "post",
                 dataType: 'json',
                 delay: 250,
                 data: function (params) {
                     return {
                         palabraClave: params.term, // search term
                         var1:"id_c,nombre_c",
                         var2:"cliente",
                         var3:"",
                         var4:"ORDER BY nombre_c ASC",
                         var5:"id_c",
                         var6:"nombre_c"
                     };
                 },
                 processResults: function (response) {
                     return {
                         results: response
                     };
                 },
                 cache: true
             }
         });
    
    });
  
  $(document).ready(function(){  
        $('.elnit').select2({ 
            ajax: {
                url: "select3/proceso.php",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        palabraClave: params.term, // search term
                        var1:"id_c,nit_c",
                        var2:"cliente",
                        var3:"",
                        var4:"ORDER BY CONVERT(nit_c, SIGNED INTEGER) ASC",
                        var5:"id_c",
                        var6:"nit_c"
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
   
   });               

  </script>
