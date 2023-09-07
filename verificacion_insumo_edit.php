<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
include('funciones/adjuntar.php'); 
require_once("db/db.php");
require_once 'Models/Mingresosalida.php';
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



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {


    $directorio = ROOT."ArchivosVerifInsumo/";



    $archivofinal = $_POST['userfilegen']; 


    $adjunto1 = ($_FILES['userfilenuevo']['name']); 
    if($_FILES['userfilenuevo']['name'] != "") { 
      $porciones1 = explode(".", $_FILES['userfilenuevo']['name']);
      $adjunto1 = "ADJ1". $_POST['n_oc_vi'] . "." . $porciones1[1];
      $tieneadjunto1 = adjuntarArchivoOK($_POST['userfile1'], $directorio, $adjunto1,$_FILES['userfilenuevo'],'UPDATES');  
      $archivofinal .= $tieneadjunto1.',';
    } 
    

    $adjunto2 = ($_FILES['userfilenuevo2']['name']); 
    if($_FILES['userfilenuevo2']['name'] != '') {
      $porciones2 = explode(".", $_FILES['userfilenuevo2']['name']);
      $adjunto2 = "ADJ2". $_POST['n_oc_vi'] . "." . $porciones2[1];
      $tieneadjunto2 = adjuntarArchivoOK($_POST['userfile2'], $directorio, $adjunto2,$_FILES['userfilenuevo2'],'UPDATES');  
      $archivofinal .= $tieneadjunto2.',';
    } 

    
    $adjunto3 = ($_FILES['userfilenuevo3']['name']);
    if($_FILES['userfilenuevo3']['name'] != '') {
      $porciones3 = explode(".", $_FILES['userfilenuevo3']['name']);
      $adjunto3 = "ADJ3". $_POST['n_oc_vi'] . "." . $porciones3[1];
      $tieneadjunto3 = adjuntarArchivoOK($_POST['userfile3'], $directorio, $adjunto3,$_FILES['userfilenuevo3'],'UPDATES');  
      $archivofinal .= $tieneadjunto3.',';
     } 


    $adjunto4 = ($_FILES['userfilenuevo4']['name']);
    if($_FILES['userfilenuevo4']['name'] != '') {
      $porciones4 = explode(".", $_FILES['userfilenuevo4']['name']);
      $adjunto4 = "ADJ4". $_POST['n_oc_vi'] . "." . $porciones4[1];
      $tieneadjunto4 = adjuntarArchivoOK($_POST['userfile4'], $directorio, $adjunto4,$_FILES['userfilenuevo4'],'UPDATES');  
      $archivofinal .= $tieneadjunto4.',';
     }  


    $adjunto5 = ($_FILES['userfilenuevo5']['name']);
    if($_FILES['userfilenuevo5']['name'] != '') {
      $porciones5 = explode(".", $_FILES['userfilenuevo5']['name']);
      $adjunto5 = "ADJ5". $_POST['n_oc_vi'] . "." . $porciones5[1];
      $tieneadjunto5 = adjuntarArchivoOK($_POST['userfile5'], $directorio, $adjunto5,$_FILES['userfilenuevo5'],'UPDATES');  
      $archivofinal .= $tieneadjunto5;
     } 




 //echo $archivofinal;die;
   
   
//Actualiza el saldo de la oc
  $id_det=$_POST['id_det_vi'];
  $falta=$_POST['faltantes_vi'];
  $sqldetalle="UPDATE orden_compra_detalle SET verificacion_det='$falta' WHERE id_det='$id_det'";

  $updateSQL = sprintf("UPDATE verificacion_insumos SET id_det_vi=%s, n_oc_vi=%s, id_insumo_vi=%s, id_p_vi=%s, fecha_vi=%s, factura_vi=%s, remision_vi=%s, entrega_vi=%s, cantidad_solicitada_vi=%s, cantidad_recibida_vi=%s, apariencia_vi=%s, accion_vi=%s, observaciones_vi=%s, recibido_vi=%s, registro_vi=%s, fecha_registro_vi=%s, servicio_vi=%s, faltantes_vi=%s, userfilenuevo=%s, usuario=%s, autorizado=%s, fecha_autorizo=%s WHERE n_vi=%s",
   GetSQLValueString($_POST['id_det_vi'], "int"),
   GetSQLValueString($_POST['n_oc_vi'], "int"),
   GetSQLValueString($_POST['id_insumo_vi'], "int"),
   GetSQLValueString($_POST['id_p_vi'], "int"),
   GetSQLValueString($_POST['fecha_vi'], "date"),
   GetSQLValueString($_POST['factura_vi'], "text"),
   GetSQLValueString($_POST['remision_vi'], "text"),
   GetSQLValueString($_POST['entrega_vi'], "text"),
   GetSQLValueString($_POST['cantidad_solicitada_vi'], "double"),
   GetSQLValueString($_POST['cantidad_recibida_vi'], "double"),
   GetSQLValueString($_POST['apariencia_vi'], "text"),
   GetSQLValueString(isset($_POST['accion_vi']) ? "true" : "", "defined","1","0"),
   GetSQLValueString($_POST['observaciones_vi'], "text"),
   GetSQLValueString($_POST['recibido_vi'], "text"),
   GetSQLValueString($_POST['registro_vi'], "text"),
   GetSQLValueString($_POST['fecha_registro_vi'], "date"),
   GetSQLValueString($_POST['servicio_vi'], "int"),
   GetSQLValueString($_POST['faltantes_vi'], "double"),
   GetSQLValueString($archivofinal, "text"),
   GetSQLValueString($_POST['usuario'], "text"),
   GetSQLValueString($_POST['autorizado'], "text"),
   GetSQLValueString($_POST['fecha_autorizo'], "text"),
   GetSQLValueString($_POST['n_vi'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $resultdetalle=mysql_query($sqldetalle);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());


  //Ingreso a inventario 
  $myObject = new oIngresosalida();
  $inventario =  new oIngresosalida();

  if(isset($_POST['n_oc_vi']) && (isset($_POST['clase_insumo']) && $_POST['clase_insumo']==28 ) )//28 es la clase de insumos de pagina web por combos
  { 
   $inventario=$myObject->Obtener('tbl_ingresosalida_items','oc', " '".$_POST['n_oc_vi']."'  " );
  
   
   $myObject->RegistrarVerif("tbl_ingresosalida_items", "nombre,ingresokilos,fecharecepcion,oc,fechasalida,salidakilos,inventariofinal,totalconsumo,responsable,modificado", $_POST);
   }

 /* $updateSQL2 = sprintf("UPDATE  verificacion_noconformei SET ensayo=%s, fecha=%s, proveedor=%s, loteprod=%s, referencia=%s, ancho=%s, factura=%s, ocompra=%s, destinada=%s, produccion=%s, otros=%s, colorfilm=%s, numprueba=%s, preg_1=%s, preg_6=%s, preg_11=%s, preg_2=%s, preg_7=%s, preg_12=%s, preg_3=%s, preg_8=%s, preg_13=%s, preg_4=%s, preg_9=%s, preg_14=%s, preg_5=%s, preg_10=%s, preg_15=%s, preg_16=%s, observacion=%s  WHERE  n_vi=%s ", 
   GetSQLValueString($_POST['ensayo'], "text"),
   GetSQLValueString($_POST['fecha'], "text"),
   GetSQLValueString($_POST['proveedor'], "text"),
   GetSQLValueString($_POST['loteprod'], "text"),
   GetSQLValueString($_POST['referencia'], "text"),
   GetSQLValueString($_POST['ancho'], "text"),
   GetSQLValueString($_POST['factura'], "text"),
   GetSQLValueString($_POST['ocompra'], "text"),
   GetSQLValueString($_POST['destinada'], "text"),
   GetSQLValueString($_POST['produccion'], "text"),
   GetSQLValueString($_POST['otros'], "text"),
   GetSQLValueString($_POST['colorfilm'], "text"),
   GetSQLValueString($_POST['numprueba'], "text"),
   GetSQLValueString($_POST['preg_1'], "text"),
   GetSQLValueString($_POST['preg_6'], "text"),
   GetSQLValueString($_POST['preg_11'], "text"),
   GetSQLValueString($_POST['preg_2'], "text"),
   GetSQLValueString($_POST['preg_7'], "text"),
   GetSQLValueString($_POST['preg_12'], "text"),
   GetSQLValueString($_POST['preg_3'], "text"),
   GetSQLValueString($_POST['preg_8'], "text"),
   GetSQLValueString($_POST['preg_13'], "text"),
   GetSQLValueString($_POST['preg_4'], "text"),
   GetSQLValueString($_POST['preg_9'], "text"),
   GetSQLValueString($_POST['preg_14'], "text"),
   GetSQLValueString($_POST['preg_5'], "text"),
   GetSQLValueString($_POST['preg_10'], "text"),
   GetSQLValueString($_POST['preg_15'], "text"),
   GetSQLValueString($_POST['preg_16'], "text"),
   GetSQLValueString($_POST['observacion'], "text"),
   GetSQLValueString($_POST['n_vi'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());*/


  $updateGoTo = "verificacion_insumo_vista.php?n_vi=" . $_POST['n_vi'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_verificacion_insumo = "-1";
if (isset($_GET['n_vi'])) {
  $colname_verificacion_insumo = (get_magic_quotes_gpc()) ? $_GET['n_vi'] : addslashes($_GET['n_vi']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion_insumo = sprintf("SELECT * FROM verificacion_insumos WHERE n_vi = %s", $colname_verificacion_insumo);
$verificacion_insumo = mysql_query($query_verificacion_insumo, $conexion1) or die(mysql_error());
$row_verificacion_insumo = mysql_fetch_assoc($verificacion_insumo);
$totalRows_verificacion_insumo = mysql_num_rows($verificacion_insumo);

$colname_insumos = "-1";
if (isset($_GET['n_oc'])) {
  $colname_insumos = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumos = sprintf("SELECT * FROM insumo, orden_compra_detalle WHERE insumo.tipo_insumo = '1' AND insumo.id_insumo = orden_compra_detalle.id_insumo_det AND orden_compra_detalle.n_oc_det = '%s' AND orden_compra_detalle.verificacion_det > '0'", $colname_insumos);
$insumos = mysql_query($query_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);
$totalRows_insumos = mysql_num_rows($insumos);

$colname2_insumo_det = "-1";
if (isset($_GET['id_insumo'])) {
  $colname2_insumo_det = (get_magic_quotes_gpc()) ? $_GET['id_insumo'] : addslashes($_GET['id_insumo']);
}
$colname_insumo_det = "-1";
if (isset($_GET['n_oc'])) {
  $colname_insumo_det = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_insumo_det = sprintf("SELECT * FROM orden_compra_detalle WHERE n_oc_det = '%s' AND id_insumo_det = '%s'", $colname_insumo_det,$colname2_insumo_det);
$insumo_det = mysql_query($query_insumo_det, $conexion1) or die(mysql_error());
$row_insumo_det = mysql_fetch_assoc($insumo_det);
$totalRows_insumo_det = mysql_num_rows($insumo_det);


$colname_verificacion_no = "-1";
if (isset($_GET['n_vi'])) {
  $colname_verificacion_no = (get_magic_quotes_gpc()) ? $_GET['n_vi'] : addslashes($_GET['n_vi']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion_no = sprintf("SELECT * FROM verificacion_noconformei WHERE n_vi = %s", $colname_verificacion_no);
$verificacion_no = mysql_query($query_verificacion_no, $conexion1) or die(mysql_error());
$row_verificacion_no = mysql_fetch_assoc($verificacion_no);
$totalRows_verificacion_no = mysql_num_rows($verificacion_no);

$colname_insumos = "-1";
if (isset($_GET['id_insumo'])) {
  $colname_insumos = (get_magic_quotes_gpc()) ? $_GET['id_insumo'] : addslashes($_GET['id_insumo']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_claseinsumos = sprintf("SELECT clase_insumo FROM insumo WHERE id_insumo = '%s' ", $colname_insumos);
$claseinsumos = mysql_query($query_claseinsumos, $conexion1) or die(mysql_error());
$row_claseinsumos = mysql_fetch_assoc($claseinsumos);
$totalRows_claseinsumos = mysql_num_rows($claseinsumos);

?>
<html>
<head>
  <title>SISADGE AC &amp; CIA</title>
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>

  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
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
              <tr><td id="nombreusuario"><?php echo $_SESSION['Usuario']; ?></td>
                <td id="cabezamenu"><ul id="menuhorizontal">
                  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
                  <li><a href="menu.php">MENU PRINCIPAL</a></li>
                  <li><a href="compras.php">GESTION COMPRAS</a></li>
                </ul>
              </td>
            </tr>  
            <tr>
              <td colspan="2" align="center">
                <form method="post" name="form1" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data">
                  <table id="tabla2">
                    <tr id="tr2">
                      <td colspan="4" id="titulo2">VERIFICACION DE INSUMOS ( CRITICOS ) N. <?php echo $row_verificacion_insumo['n_vi']; ?></td>
                    </tr>
                    <tr>
                      <td rowspan="6" id="fuente2"><img src="images/logoacyc.jpg"></td>
                      <td colspan="2" id="fuente1">&nbsp;</td>
                      <td id="fuente2"><a href="verificacion_insumo_edit.php?n_vi=<?php echo $row_verificacion_insumo['n_vi']; ?>&n_oc=<?php echo $row_verificacion_insumo['n_oc_vi']; ?>&id_insumo=<?php echo $row_verificacion_insumo['id_insumo_vi']; ?>"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" /></a><a href="javascript:eliminar1('n_vi',<?php echo $row_verificacion_insumo['n_vi']; ?>,'verificacion_insumo_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a><a href="verificacion_insumo_vista.php?n_vi=<?php echo $row_verificacion_insumo['n_vi']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="verificacion_insumo_oc.php?n_oc=<?php echo $row_verificacion_insumo['n_oc_vi']; ?>"><img src="images/v.gif" alt="VERIFICACIONES X REF" border="0" style="cursor:hand;"/></a><a href="verificaciones_criticos.php"><img src="images/cat.gif" style="cursor:hand;" alt="VERIFICACIONES (CRITICOS)" border="0"/></a><a href="orden_compra.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/></a><a href="verificacion_insumo_listado.php"><img src="images/11.png" style="cursor:hand;" alt="LISTADO VERIF" title="LISTADO VERIF" border="0"/></a></td>
                    </tr>
                    <tr>
                      <td colspan="3" id="subtitulo1">O.C. N&deg; <input name="n_oc_vi" type="hidden" value="<?php echo $row_verificacion_insumo['n_oc_vi']; ?>">
                        <?php echo $row_verificacion_insumo['n_oc_vi']; ?>

                        DE 
                        <input name="id_p_vi" type="hidden" value="<?php echo $row_verificacion_insumo['id_p_vi']; ?>">
                        <?php $id_p=$row_verificacion_insumo['id_p_vi'];
                        $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
                        $resultp=mysql_query($sqlp);
                        $nump=mysql_num_rows($resultp);
                        if($nump >= '1') { $proveedor_p=mysql_result($resultp,0,'proveedor_p'); echo $proveedor_p; }
                        else { echo "";	} ?></td>
                      </tr>
                      <tr>
                        <td colspan="3" id="fuente1">INSUMO RECIBIDO 
                          <input name="id_det_vi" type="hidden" value="<?php if($row_verificacion_insumo['id_det_vi']=='') { echo $row_insumo_det['id_det']; } else { echo $row_verificacion_insumo['id_det_vi']; } ?>"></td>
                          <!-- <td nowrap id="fuente1">CONFORME ?</td> -->
                        </tr>
                        <tr>
                          <td colspan="3" id="dato1"><strong>
                           <?php $id_insumo=$row_verificacion_insumo['id_insumo_vi']; 
                           $sqlinsumo="SELECT * FROM insumo WHERE id_insumo='$id_insumo'";
                           $resultinsumo=mysql_query($sqlinsumo);
                           $numinsumo=mysql_num_rows($resultinsumo);
                           if($numinsumo >= '1') { $descripcion_insumo=mysql_result($resultinsumo,0,'descripcion_insumo'); echo $descripcion_insumo; }
                           else { echo "";	} ?>
                           <input name="id_insumo_vi" type="hidden" id="id_insumo_vi" value="<?php echo $row_verificacion_insumo['id_insumo_vi']; ?>">
                         </strong></td>

                         <td style="display: none;" >
                          <select name="no_conforme" id="no_conforme" onChange="comprobar_conforme(this)"  >
                            <option value="SI" <?php if (!(strcmp('SI', $row_verificacion_no['no_conforme']))) {echo "selected=\"selected\"";} ?>>SI</option>
                            <option value="NO" <?php if (!(strcmp('NO', $row_verificacion_no['no_conforme']))) {echo "selected=\"selected\"";} ?>>NO</option>
                        </select>
                      </td>  

                    </tr>
                    <tr>
                      <td colspan="3" id="fuente1">NOMBRE DE QUIEN RECIBIO EL INSUMO </td>
                    </tr>
                    <tr>
                      <td colspan="3" id="dato1"><input type="text" name="recibido_vi" value="<?php echo $row_verificacion_insumo['recibido_vi']; ?>" size="30"></td>
                    </tr>
                    <tr>
                      <td id="fuente1">FECHA DE RECIBIDO </td>
                      <td id="fuente1">FACTURA</td>
                      <td id="fuente1">REMISION</td>
                      <td id="fuente1">ENTREGA</td>
                    </tr>
                    <tr>
                      <td id="dato1"><input type="text" name="fecha_vi" value="<?php echo $row_verificacion_insumo['fecha_vi']; ?>" size="10"></td>
                      <td id="dato1"><input type="text" name="factura_vi" value="<?php echo $row_verificacion_insumo['factura_vi']; ?>" size="20"></td>
                      <td id="dato1"><input type="text" name="remision_vi" value="<?php echo $row_verificacion_insumo['remision_vi']; ?>" size="20"></td>
                      <td id="dato1"><select name="entrega_vi">
                        <option value="PARCIAL" <?php if (!(strcmp("PARCIAL", $row_verificacion_insumo['entrega_vi']))) {echo "selected=\"selected\"";} ?>>PARCIAL</option>
                        <option value="TOTAL" <?php if (!(strcmp("TOTAL", $row_verificacion_insumo['entrega_vi']))) {echo "selected=\"selected\"";} ?>>TOTAL</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td id="fuente1">CANTIDAD PEDIDA </td>
                      <td id="fuente1">SALDO ANTERIOR </td>
                      <td id="fuente1">CANT. RECIBIDA </td>
                      <td id="fuente1">FALTANTES</td>
                    </tr>
                    <tr>
                      <td id="dato1"><input type="text" name="cantidad_solicitada_vi" value="<?php echo $row_verificacion_insumo['cantidad_solicitada_vi']; ?>" size="20" onBlur="faltantes()"></td>
                      <td id="dato1"><input name="saldo_anterior_vi" type="text" id="saldo_anterior_vi" onBlur="faltantes()" value="<?php echo $row_insumo_det['verificacion_det']; ?>" size="20"></td>
                      <td id="dato1"><input type="text" name="cantidad_recibida_vi" value="<?php echo $row_verificacion_insumo['cantidad_recibida_vi']; ?>" size="20" onBlur="faltantes()">
                      <input type="hidden" name="cantidad_recibida_bk" value="<?php echo $row_verificacion_insumo['cantidad_recibida_vi']; ?>" size="20" onBlur="faltantes()">
                    </td>
                      <td id="dato1"><input type="text" name="faltantes_vi" value="<?php echo $row_verificacion_insumo['faltantes_vi']; ?>" size="20" onBlur="faltantes()"></td>
                    </tr>
                    <tr>
                      <td colspan="4" id="fuente1">OBSERVACIONES</td>
                    </tr>
                    <tr>
                      <td colspan="4" id="dato1"><textarea name="observaciones_vi" cols="80" rows="3"><?php echo $row_verificacion_insumo['observaciones_vi']; ?></textarea></td>
                    </tr>
                    <tr>
                      <td colspan="2" id="fuente1">APARIENCIA DEL INSUMO RECIBIDO </td>
                      <td colspan="2" id="fuente1"><input type="checkbox" name="accion_vi" value="1" <?php if (!(strcmp($row_verificacion_insumo['accion_vi'],1))) {echo "checked=\"checked\"";} ?>>              
                      REQUIERE PLAN DE ACCION </td>
                    </tr>
                    <tr>
                      <td colspan="2" id="dato1"><select name="apariencia_vi" id="apariencia_vi">
                        <option value="1" <?php if (!(strcmp(1, $row_verificacion_insumo['apariencia_vi']))) {echo "selected=\"selected\"";} ?>>Buena (1)</option>
                        <option value="0.5" <?php if (!(strcmp(0.5, $row_verificacion_insumo['apariencia_vi']))) {echo "selected=\"selected\"";} ?>>Regular (0.5)</option>
                        <option value="0" <?php if (!(strcmp(0, $row_verificacion_insumo['apariencia_vi']))) {echo "selected=\"selected\"";} ?>>Mala (0)</option>
                      </select></td>
                      <td colspan="2" id="dato1">&nbsp;</td>
                    </tr>
                    <tr>
                      <td id="fuente1">CALIFICACION DEL SERVICIO</td>
                      <td id="fuente1">RANGO CALIFICACION</td>
                      <td id="fuente1">REGISTRO EN EL SISTEMA</td>
                      <td id="fuente1">FECHA REGISTRO</td>
                    </tr>
                    <tr>
                      <td id="dato1"><select name="servicio_vi" id="servicio_vi">
                        <option value="1" <?php if (!(strcmp(1, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>1</option>
                        <option value="2" <?php if (!(strcmp(2, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>2</option>
                        <option value="3" <?php if (!(strcmp(3, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>3</option>
                        <option value="4" <?php if (!(strcmp(4, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>4</option>
                        <option value="5" <?php if (!(strcmp(5, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>5</option>
                        <option value="6" <?php if (!(strcmp(6, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>6</option>
                        <option value="7" <?php if (!(strcmp(7, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>7</option>
                        <option value="8" <?php if (!(strcmp(8, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>8</option>
                        <option value="9" <?php if (!(strcmp(9, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>9</option>
                        <option value="10" <?php if (!(strcmp(10, $row_verificacion_insumo['servicio_vi']))) {echo "selected=\"selected\"";} ?>>10</option>
                      </select>
                    </td>
                    <td id="dato1">
                      Malo 1 - 3<br>
                      Regular 4 - 6<br>
                      Bueno 7 - 8<br>
                      Excelente 9 - 10
                    </td>
                      <td  id="dato1">
                        <input name="registro_vi" type="text" value="<?php echo $row_verificacion_insumo['registro_vi']; ?>" size="30" readonly></td>
                      <td id="dato1">
                        <input type="text" name="fecha_registro_vi" value="<?php echo $row_verificacion_insumo['fecha_registro_vi']; ?>" size="10">
                      </td> 
                    </tr>
                    <tr><td colspan="2" id="dato1">&nbsp;</td></tr>
                     <?php if( $_SESSION['id_usuario'] == '25' || $_SESSION['id_usuario'] == '26' ): ?>
                    <tr> 
                      <td id="fuente1">USUARIO</td>
                      <td id="fuente1">CALIDAD CUMPLE?</td>  
                    </tr>
                    <tr>
                      <td id="dato1"><input name="usuario" type="text" value="<?php echo $_SESSION['Usuario']; ?>" size="10" readonly></td>
                      <td id="dato1">
                        <select name="autorizado" id="autorizado">
                        <option value="SI" <?php if (!(strcmp("SI", $row_verificacion_insumo['autorizado']))) {echo "selected=\"selected\"";} ?>>SI</option>
                        <option value="NO" <?php if (!(strcmp("NO", $row_verificacion_insumo['autorizado']))) {echo "selected=\"selected\"";} ?>>NO</option> 
                      </select>
                      </td>
                      <td id="dato1">
                        <input name="fecha_autorizo" readonly="readonly" type="hidden" value="<?php echo date("Y-m-d"); ?>" size="10" readonly>
                      </td> 
                    </tr>
                      <?php endif; ?>
                    <tr><td colspan="2" id="dato1">&nbsp; </td></tr>
                    <tr>
                      <td id="dato1">
                        <input type="hidden" name="userfilegen" id="userfilegen" value="<?php echo $row_verificacion_insumo['userfilenuevo']; ?>" /> 
                           Adjuntar1:<input type="file" name="userfilenuevo" id="userfilenuevo"/> </td> 
                      <td id="dato1">
                           Adjuntar2:<input type="file" name="userfilenuevo2" id="userfilenuevo2"/> </td> 
                      <td id="dato1">
                           Adjuntar3:<input type="file" name="userfilenuevo3" id="userfilenuevo3"/> </td> 
                      </tr>
                      <tr>
                        <td id="dato1">
                             Adjuntar4:<input type="file" name="userfilenuevo4" id="userfilenuevo4"/> </td> 
                        <td id="dato1">
                             Adjuntar5:<input type="file" name="userfilenuevo5" id="userfilenuevo5"/> 
                         </td> 
                      </tr>
                      <tr> 
                            <?php 
                            $porciones = array();
                            $porciones = explode(",", $row_verificacion_insumo['userfilenuevo']);
                            $count = 0;
                            ?>
                            <?php if( $row_verificacion_insumo['userfilenuevo'] != ''): ?>
                             <?php foreach ($porciones as $key => $value) { ?>
                              <?php $count++;?>
                              <?php if($value!=''):?> 
                              <td id="dato1" >
                                <a href="javascript:verFoto('ArchivosVerifInsumo/<?php echo $value;?>','610','490')">Archivo<?php echo $count;?></a> 
                                <input name="userfile<?php echo $count;?>" type="hidden" id="userfile<?php echo $count;?>" value="<?php echo $value; ?>"/> 
                               </td>
                              <?php endif; ?>
                            <?php } ?> 
                          <?php endif; ?>
                    </tr>


                     
                    <?php //endif;?> 
                  </table><p></p>

                </td>
              </tr>


              <tr>
                <td colspan="4" id="dato2">
                  <input type="hidden" name="clase_insumo" value="<?php echo $row_claseinsumos['clase_insumo'];?>"> 
                  <input  class="botonGeneral" type="submit" value="ACTUALIZAR VERIFICACION"></td>
              </tr>
            </table>
            <input type="hidden" name="MM_update" value="form1">
            <input type="hidden" name="n_vi" value="<?php echo $row_verificacion_insumo['n_vi']; ?>">
          </form></td>
        </tr>
      </table>
    </div> </table>
  </div>
</body>
</html>
<script type="text/javascript">

  window.onload = function() {


    comprobar_conforme(document.getElementById("no_conforme").value); 
  };




  function comprobar_conforme(sel){

   if(sel.value == 'NO' || sel == 'NO' ){
     document.getElementById('noconforme').style.display = "block";

                         /*if(preg_1 =='NO' && preg_6 =='NO' && preg_11 =='NO' && preg_2 =='NO' && preg_7 =='NO' && preg_12 =='NO' && preg_3 =='NO' && preg_8 =='NO' && preg_13 =='NO' && preg_4 =='NO' && preg_9 =='NO' && preg_14 =='NO' && preg_5 =='NO' && preg_10 =='NO' && preg_15 =='NO'){
                           alert('complete las preguntas de si cumple o no!');
                          return fales;
                        }*/

                      }else{
                       noconforme.style.display = "none";
                     }
                   } 

                 </script>

                 <?php
                 mysql_free_result($usuario);

                 mysql_free_result($verificacion_insumo);

                 mysql_free_result($insumos);

                 mysql_free_result($insumo_det);
                 ?>