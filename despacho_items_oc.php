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
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
      case "long":
      case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
      case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
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
}

$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  
    //ENVIO EMAIL CUANDO SE ADJUNTA COMPROBANTE
    $tieneadjunto1=$_POST['adjunto1'];
    
    $directorio = "Archivosdesp/";

    if (isset($_FILES['comprobante_file']) && $_FILES['comprobante_file']['name'] != ""){

     $name =  ($_FILES['comprobante_file']['name']);
     $name = str_replace(" ", "", $name);

     $tieneadjunto1 = adjuntarArchivo($tieneadjunto1, $directorio, $name,$_FILES['comprobante_file']['tmp_name'],'NUEVOS');
      
     $enviar = new EnvioEmails();    
     $to = 'lidersistemas@acycia.com';
     $to2 = 'andres85684@outlook.com';
     /* $to = 'sistemas@acycia.com';
     $to2 = 'robinrt144@gmail.com';  */
     $file = $name!='' ? $name : $tieneadjunto1; 
     $from = 'Remision Despachada';
     $asunto = "Envio de remision Numero:". $_POST['int_remision'] ." ";
     $body='Saludos, Adjunto encontrara el numero de remision al cual se le adjunto el comprobante de Entrega.';   
      
     $envioCorreo = $enviar->enviar($to,$to2,$file,$from,$asunto,$body,$directorio);

    } 
    //FIN ADJUNTO COMPROBANTE
   
   if(isset($_POST['int_remision'])){ 
    $myObject = new oRemision();
     $existeremision=$myObject->Obtener('tbl_remisiones','int_remision', " '".$_POST['int_remision']."'  " );
   } 
   if(isset($_POST['int_remision']) && !$existeremision){
  $insertSQL = sprintf("INSERT INTO Tbl_remisiones(int_remision,str_numero_oc_r,fecha_r,str_encargado_r,str_transportador_r,str_guia_r,str_elaboro_r,str_aprobo_r,str_observacion_r,factura_r,b_borrado_r,ciudad_pais, comprobante_file) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
   GetSQLValueString($_POST['int_remision'], "int"),
   GetSQLValueString($_POST['str_numero_oc_r'], "text"),
   GetSQLValueString($_POST['fecha_r'], "date"),
   GetSQLValueString($_POST['str_encargado_r'], "text"),
   GetSQLValueString($_POST['str_transportador_r'], "text"),
   GetSQLValueString($_POST['str_guia_r'], "text"),
   GetSQLValueString($_POST['str_elaboro_r'], "text"),
   GetSQLValueString($_POST['str_aprobo_r'], "text"),
   GetSQLValueString($_POST['str_observacion_r'], "text"), 
   GetSQLValueString($_POST['factura_r'], "text"),                     
   GetSQLValueString($_POST['b_borrado_r'], "int"),
   GetSQLValueString($_POST['ciudad_pais'], "text"),
   GetSQLValueString($tieneadjunto1, "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
   }

  //GUARDADO DE HISTORICOS
  $myObject = new oRemision();
  $historico =  new oRemision();

  if(isset($_POST['int_remision'])){ 
    $historico=$myObject->Obtener('tbl_remisiones','int_remision', " '".$_POST['int_remision']."'  " );
  }  

  if(isset($_POST['int_remision']) && $historico){
    $myObject->Registrar("tbl_remisiones_historico", "int_remision,str_numero_oc_r,fecha_r,str_encargado_r,str_transportador_r,str_guia_r,str_elaboro_r,str_aprobo_r,str_observacion_r,factura_r,b_borrado_r,ciudad_pais,modifico", $historico);
  }//FIN HISTORICO
 

  $insertGoTo = "despacho_items_oc_edit.php?int_remision=" . $_POST['int_remision']  . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
} 

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
mysql_select_db($database_conexion1, $conexion1);
$query_remision = "SELECT * FROM Tbl_remisiones ORDER BY id_r DESC";
$remision = mysql_query($query_remision, $conexion1) or die(mysql_error());
$row_remision = mysql_fetch_assoc($remision);
$totalRows_remision = mysql_num_rows($remision);
//TODA LA INFO DE ORDEN CON ITEMS


//codigo para controlar si ya tiene remision 2023-05-03
if (isset($_GET['str_numero_r'])) {
 
    $myObject = new oRemision();
     $existeremision=$myObject->Obtener('tbl_remisiones','str_numero_oc_r', " '".$_GET['str_numero_r']."'  " ); 
     
     $numeroRemision=$existeremision[0]['int_remision'];
     $yaexisteremi = $existeremision[0]['str_numero_oc_r'];//para que imprima los items y info de O.C
   
   
}
 
 $_GET['str_numero_r'] = $_GET['str_numero_r'] == '' ? $yaexisteremi : $_GET['str_numero_r'];//si no existe remision asociada toma el $_GET['str_numero_r']
 
 
$colname_orden_r = "-1";
if (isset($_GET['str_numero_r'])) {
  $colname_orden_r = (get_magic_quotes_gpc()) ? $_GET['str_numero_r'] : addslashes($_GET['str_numero_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra =sprintf("SELECT * FROM Tbl_orden_compra,cliente WHERE Tbl_orden_compra.str_numero_oc='%s' AND Tbl_orden_compra.id_c_oc=cliente.id_c", $colname_orden_r);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

$colname_items = "-1";
if (isset($_GET['str_numero_r'])) {
  $colname_items = (get_magic_quotes_gpc()) ? $_GET['str_numero_r'] : addslashes($_GET['str_numero_r']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_items = sprintf("SELECT * FROM Tbl_items_ordenc WHERE str_numero_io = '%s' ORDER BY id_items ASC", $colname_items);
$items = mysql_query($query_items, $conexion1) or die(mysql_error());
$row_items = mysql_fetch_assoc($items);
$totalRows_items = mysql_num_rows($items);

?>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <title>SISADGE AC &amp; CIA</title>
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>
  <script type="text/javascript" src="AjaxControllers/js/insert.js"></script>
  

  <!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

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

  

  <script>
    function validar(){ 
      if (document.form1.oc.value=="1"){
       alert("NO SE GUARDARON LOS DATOS PORQUE EL NUMERO DE ORDEN YA EXISTE FAVOR HACER REVISION"); 
       return false;} 
       return true; 
     }
   </script>
   <!--CODIGO DE CONFIRMACION CIERRE DE PAGINA WEB-->
   <script type="text/javascript">
    window.onbeforeunload = confirmaSalida;  

/*function confirmaSalida()   {    
       if (str_encargado_r.value==""||str_transportador_r.value==""||str_guia_r.value==""||str_aprobo_r.value=="") {

          return "¿Esta Seguro de Cancelar el Despacho. Si has hecho algun cambio sin grabar vas a perder todos los datos?";  
       }
     }*/
   </script>
   <!--CONFIRMACION AL DARLE CLICK EN SALIR BOTON-->
   <script type="text/javascript">
    function salir()
    {
      var statusConfirm = confirm("Esta seguro de finalizar el proceso?");
      if (statusConfirm == true)
      {
       window.location ='despacho_oc.php'
     }else if (statusConfirm == false)
     {
       window.close();
     }
   }
   <!--FIN-->
 </script>
</head>
<body>
  <?php echo $conexion->header('vistas'); ?>
           <table align="center" id="tabla"><tr align="center"><td>    
                  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onSubmit="MM_validateForm('fecha_r','','R','str_encargado_r','','R','str_guia_r','','R','str_transportador_r','','R','str_elaboro_r','','R','str_aprobo_r','','R');return document.MM_returnValue">
                  <table class="table">
                    <tr id="tr1">
                      <td nowrap id="codigo">CODIGO : A3 - F02</td>
                      <td nowrap id="titulo2">REMISION</td>
                      <td nowrap id="codigo">VERSION : 0</td>
                    </tr>
                    <tr>
                      <td rowspan="8" id="dato2" ><img src="images/logoacyc.jpg"></td>
                      <td id="subtitulo">&nbsp;</td>
                      <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="despacho_items_oc_vista.php?int_remision=<?php echo $row_remision['int_remision']; ?>&str_numero_r=<?php echo $row_remision['str_numero_oc_r']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="despacho_oc.php"><img src="images/r.gif" style="cursor:hand;" alt="ORDENES DE COMPRA" title="LISTADO DE ORDENES DE COMPRA" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
                    </tr>
                    <tr>
                      <td style="display: none;" class="iDremision" id="numero2">N&deg;
                       
                        <input name="int_remision" type="text"size="15"class="rojo_inteso" value="<?php echo $row_remision['int_remision']+1 ; ?>" readonly/> &nbsp;
                         </td>
                        <td id="dato_1">
                          <div id="resultado"></div><?php $oc=$_GET['oc'];?><input name='oc' type='hidden' value='<?php echo $oc; ?>'> 
                        </td>
                      </tr>
                      <tr>
                        <td id="fuente1">FECHA INGRESO</td>
                        <td id="fuente1">&nbsp;</td>
                      </tr>
                      <tr>
                        <td id="dato1"><input name="fecha_r" type="text" id="fecha_r" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
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
                        <td colspan="2" id="dato1">ORDEN DE COMPRA N&deg : <b> <?php echo $row_orden_compra['str_numero_oc']; ?></b>
                         <br><?php if($numeroRemision): ?> <em>Ya Tiene la remision: <?php echo $numeroRemision; ?> creada</em><?php endif; ?> </td>
                      </tr>
                      <tr>
                        <td colspan="2" id="dato4">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="3" id="detalle2">
                          <table class="table"><!--se cambio tabla2 x tabla1-->
                          <tr>
                            <td colspan="2" id="dato1"><strong>CLIENTE: </strong>
                              <?php $cad4= utf8_encode($row_orden_compra['nombre_c']);echo $cad4; ?></td>
                              <td width="50%" colspan="2" id="dato1"><strong>PAIS / CIUDAD : </strong>
                                <?php  $cad= utf8_encode($row_orden_compra['pais_c']);  $cad2= utf8_encode($row_orden_compra['ciudad_c']); ?>
                                <input type="text" name="ciudad_pais" id="ciudad_pais" size="40" maxlength="200"  value="<?php echo $cad.' / '.$cad2;?>" onKeyUp="conMayusculas(this)"></td>
                              </tr>
                              <tr>
                                <td colspan="2" id="dato1"><strong>NIT : </strong><?php echo $row_orden_compra['nit_c']; ?></td>
                                <td colspan="2" id="dato1"><strong>TELEFONO:</strong><?php echo $row_orden_compra['telefono_c']; ?></td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato1"><strong>DIRECCCION COMERCIAL :</strong>
                                  <?php  $cade =  utf8_encode($row_orden_compra['str_dir_entrega_oc']); echo $cade; ?>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato1"><strong>DIRECCCION ENVIO FACTURA :</strong>
                                  <?php  $cade2 =  utf8_encode($row_orden_compra['direccion_envio_factura_c']); echo $cade2; ?>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato1"><strong>SALIDAS TIPO:</strong> 
                                  <?php if (!(strcmp("0",$row_orden_compra['salida_oc']))){echo "Normal";} ?>
                                  <?php if (!(strcmp("1",$row_orden_compra['salida_oc']))){echo "Reposiciones";} ?>
                                  <?php if (!(strcmp("2",$row_orden_compra['salida_oc']))){echo "Muestras";} ?>
                                  <?php if (!(strcmp("3",$row_orden_compra['salida_oc']))){echo "Salidas especiales";} ?>
                                </td>
                              </tr>
                              <tr>
                                <td width="14%" id="dato1"><strong>ENCARGADO:</strong> </td>
                                <td width="36%" id="dato1"><input name="str_encargado_r" type="text" id="str_encargado_r"onKeyUp="conMayusculas(this)" value="<?php $nom=strtoupper($row_usuario['nombre_usuario']);echo $nom; ?>"></td>
                                <td id="dato1"><strong>GUIA : </strong>              </td>
                                <td id="dato1"><input type="text" name="str_guia_r" id="str_guia_r" size="15" onKeyUp="conMayusculas(this)" onBlur="MM_validateForm('str_guia_r','','R');return document.MM_returnValue"></td>
                              </tr>
                              <tr>
                                <td id="dato1"><strong>TRANSPORTADOR:</strong></td>
                                <td id="dato1"><strong>
                                  <input type="text" name="str_transportador_r" id="str_transportador_r"onKeyUp="conMayusculas(this)"onBlur="MM_validateForm('str_transportador_r','','R');return document.MM_returnValue">
                                </strong></td>
                                <td id="dato1"><strong>DESPACHADO POR :
                                  
                                </strong></td>
                                <td id="dato1"><strong>
                                  <input name="str_elaboro_r" type="text" onKeyUp="conMayusculas(this)"id="str_elaboro_r" value="<?php $nom=strtoupper($row_usuario['nombre_usuario']);echo $nom; ?>" size="15">
                                </strong></td>
                              </tr>
                              <tr>
                                <td id="dato1"><strong>APROBADO POR</strong></td>
                                <td id="dato1"><input name="str_aprobo_r" type="text" id="str_aprobo_r" value="JEFE PRODUCCION" onKeyUp="conMayusculas(this)" ></td>
                                <td id="dato1"><strong>FACTURA:</strong></td>
                                <td id="dato1"><input style="width: 140px" type="text" name="factura_r" id="factura_r" min="0" size="15" required value="<?php echo $row_orden_compra['factura_oc']; ?>" >
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato3">&nbsp;</td>
                              </tr>
                              <tr> 
                                <td colspan="4" id="dato1"><strong> Adjuntar Comprobante de Entrega:</strong> <input type="file" name="comprobante_file" id="comprobante_file"size="100"/></td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato1">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="2" id="dato1"><strong>Se entrega Factura? : </strong> <?php echo $row_orden_compra['entrega_fac']; ?></td>
                                <td colspan="2" id="dato1"><strong>Adjuntar Comprobante? : </strong> <?php echo $row_orden_compra['comprobante_ent']; ?></td>
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
                                <td colspan="4" id="dato1"><strong>OBSERVACIONES:</strong> <span id="alertG" style="color: red;" > </span> </td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato2">
                                  <textarea name="str_observacion_r" cols="70" onKeyUp="conMayusculas(this)" rows="8" id="str_observacion_oc"><?php echo $row_orden_compra['str_observacion_oc']; ?></textarea>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="4" id="dato1"></td>
                              </tr>
                            </table></td>
                          </tr>         
                          <tr>
                            <td colspan="4" id="dato3">&nbsp;</td>
                          </tr>

                          <?php if(!$yaexisteremi): ?>

                          <tr id="imprime" >
                            <td colspan="4" id="dato2">
                            <input type="button" class="botonGigante" onclick="imprimeMuestra()" name="idGuardar" id="idGuardar" value="REMISIONAR ITEMS "/> 
                            </td> 
                          </tr>

                           <?php endif; ?>
                         
                         

                          <?php if($row_items['id_items']!='') { ?>
                          <tr id="tr2" >
                            <td colspan="4" id="dato2">


                              <table class="table">
                              <tr>
                                <td id="nivel2">ITEM</td>
                                <td id="nivel2">REF. AC</td>
                                <td id="nivel2">REF. PM</td>
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
                                <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_consecutivo_io']; ?></a></td>
                                <td id="talla1"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_cod_ref_io']; ?></a></td>
                                
                                <td id="talla1"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000">
                                  <?php $mp=$row_items['id_mp_vta_io'];
                                  if($mp!='')
                                  {
                                    $sqlmp="SELECT * FROM Tbl_mp_vta WHERE id_mp_vta='$mp'";
                                    $resultmp= mysql_query($sqlmp);
                                    $nump= mysql_num_rows($resultmp);
                                    if($nump >='1')
                                    { 
                                      $nombre_mp = mysql_result($resultmp,0,'str_nombre');
                                      ;
                                    } } ?><?php echo $nombre_mp ?></a></td> 
                                    
                                    <td id="talla3"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_cod_cliente_io']; ?></a></td>
                                    <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_cantidad_io']; ?></a></td>
                                    <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php if($row_items['int_cantidad_rest_io']==''){echo '0';}else{echo $row_items['int_cantidad_rest_io'];} ?></a></td>
                                    <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['str_unidad_io']; ?></a></td>
                                    <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['fecha_entrega_io']; ?></a></td>
                                    <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_precio_io']; ?></a></td>
                                    <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_total_item_io'];$total=$subtotal+$row_items['int_total_item_io'];?></a></td>
                                    <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['str_moneda_io']; ?></a></td>
                                    <td id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php $dd = htmlentities($row_items['str_direccion_desp_io']);echo $dd; ?></a></td>
                                    <td nowrap="nowrap" id="talla2"><a href="javascript:verPopUp('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']+1; ?>','900','400')" onclick="mostrarId();" target="_top" style="text-decoration:none; color:#000000"><?php if($row_items['b_estado_io']=='5'){echo "Facturado Total";}else if($row_items['b_estado_io']=='4'){echo "Facturado Parcial";}else if($row_items['b_estado_io']=='1'){echo "Ingresado";}else if($row_items['b_estado_io']=='2'){echo "Programado";}else if($row_items['b_estado_io']=='3'){echo "Remisionado";}else if($row_items['b_estado_io']=='6'){echo "Muestras reposicion";}  ?></a>
                                    </td>
                                    <td id="talla3">
                                      <?php 
                                      $id_items = $row_items['id_items'];         
                                      $sqlmp="SELECT Tbl_orden_produccion.int_cod_ref_op AS existe_op  
                                      FROM Tbl_items_ordenc,Tbl_orden_produccion WHERE Tbl_items_ordenc.id_items='$id_items' AND   Tbl_items_ordenc.str_numero_io=Tbl_orden_produccion.str_numero_oc_op 
                                      AND Tbl_items_ordenc.int_cod_ref_io=Tbl_orden_produccion.int_cod_ref_op AND Tbl_orden_produccion.b_borrado_op='0'";
                                      $resultmp= mysql_query($sqlmp);
                                      $nump = mysql_num_rows($resultmp);
                                      $str_numero_oc=$_GET['str_numero_r']; 
//SI EL ITEM TIENE REMISIONES 

                                      $sql2="SELECT * FROM Tbl_items_ordenc,tbl_remision_detalle WHERE Tbl_items_ordenc.id_items='$id_items' AND tbl_remision_detalle.str_numero_oc_rd = Tbl_items_ordenc.str_numero_io AND Tbl_items_ordenc.int_cod_ref_io = tbl_remision_detalle.int_ref_io_rd";
                                      $result2= mysql_query($sql2);
                                      $numRem = mysql_num_rows($result2);

                                      if($nump >='1' )
                                      { 
                                        $existe_op_det ="1";
                                      }else if($numRem >='1') {
                                        $existe_op_det ="1";
                                      }else{
                                        $existe_op_det ="0";

                                      }         


                                      if ($existe_op_det=='0') { ?>
                                       <a href="javascript:eliminar1('id_items_rem',<?php echo $row_items['id_items']; ?>,'despacho_items_oc.php')"><img src="images/por.gif" alt="ELIMINAR O.C." title="ELIMINAR O.C." border="0" style="cursor:hand;"/></a>
                                       <?php }else{?>
                                        <img src="images/pa.gif" alt="EN PRODUCCION" title="EN PRODUCCION" border="0" onClick="enProduccion();" style="cursor:hand;"/>
                                     <?php } ?>
                                     <?php if ($_SESSION['superacceso']) { ?>
                                       <a href="javascript:eliminar1('id_items_rem',<?php echo $row_items['id_items']; ?>,'despacho_items_oc.php')"><img src="images/por.gif" alt="PUEDE ELIMINAR POR SER SUPERACCESO" title="PUEDE ELIMINAR POR SER SUPERACCESO" border="0" style="cursor:hand;"/></a>
                                     <?php  } ?> 
                                    </td>
                                  </tr>
                                  <?php } while ($row_items = mysql_fetch_assoc($items)); ?>

                                </table>
                              </td>
                              </tr>
                              <?php } ?>

                            
     

                              <tr>
                                <td colspan="4" id="fuente1">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="13" id="fuente2"><?php 
                                 $muestra=$row_orden_compra['str_archivo_oc'];
                                  if($row_orden_compra['str_archivo_oc']==''){echo "Sin Archivo";}else{?><a href="javascript:verFoto('pdfacturasoc/<?php  echo $muestra;?>','610','490')"> <?php echo "ARCHIVO";?></a><?php } ?>
                                </td>
                              </tr>
                              <tr>            
                                <td id="fuente3">&nbsp;</td>
                              </tr>
                              <tr>
                                <td colspan="3" id="fuente2"><input type="hidden" name="MM_insert" value="form1">
                                  <input type="hidden" name="b_borrado_r" id="b_borrado_r" value="0">
                                  <input type="hidden" name="remisionar" id="remisionar" value="remisionar">
                                  <input type="hidden" name="str_numero_oc_r" id="str_numero_oc_r" value="<?php echo $row_orden_compra['str_numero_oc'] ?>">
                                  <img src="images/salir.gif" style="cursor:hand;" alt="SALIR" title="SALIR" onClick="salir()"/></td>
                                </tr>
                              </table>
                            </form>
                            </td> </tr> </table>
                            <?php echo $conexion->header('footer'); ?>
                  </body>
                  </html>
                  <script>
                     $(document).ready(function(){

                                        var existe = '<?php echo $yaexisteremi;?>';
                                         if(existe){
                                            mostrarId(); 
                                         }
                       }); 

                    function mostrarId(){
                          $('.iDremision').show(); 
                    }  

                    function imprimeMuestra(){ 
                       
                         mostrarId()
                         var vista = 'AjaxControllers/Actions/guardar.php';
                        var resul = validaTodo();

                          if(resul){
                             
                              $('#tr2').show();
                              $('#idGuardar').hide(); 
                            guardarGeneral(vista);  
                            window.print();
                          } 

                     }

                     function validaTodo(){

                        return true;

                     } 
      
                  </script>

                  <?php
                  mysql_free_result($usuario);mysql_close($conexion1);

                  mysql_free_result($orden_compra);

                  mysql_free_result($remision);

                  mysql_free_result($items);

                  ?>