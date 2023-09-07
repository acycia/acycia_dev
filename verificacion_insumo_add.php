<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?>
<?php require_once('Connections/conexion1.php'); ?>
<?php
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$conexion = new ApptivaDB();

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
//Actualiza el saldo de la oc
  $id_det=$_POST['id_det_vi'];
  $falta=$_POST['faltantes_vi'];
  $sqldetalle="UPDATE orden_compra_detalle SET verificacion_det='$falta' WHERE id_det='$id_det'";

  $insertSQL = sprintf("INSERT INTO verificacion_insumos (n_vi, id_det_vi, n_oc_vi, id_insumo_vi, id_p_vi, fecha_vi, factura_vi, remision_vi, entrega_vi, cantidad_solicitada_vi, cantidad_recibida_vi, apariencia_vi, accion_vi, observaciones_vi, recibido_vi, registro_vi, fecha_registro_vi, servicio_vi, faltantes_vi) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
   GetSQLValueString($_POST['n_vi'], "int"),
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
   GetSQLValueString($_POST['accion_vi'], "int"),
   GetSQLValueString($_POST['observaciones_vi'], "text"),
   GetSQLValueString($_POST['recibido_vi'], "text"),
   GetSQLValueString($_POST['registro_vi'], "text"),
   GetSQLValueString($_POST['fecha_registro_vi'], "date"),
   GetSQLValueString($_POST['servicio_vi'], "int"),
   GetSQLValueString($_POST['faltantes_vi'], "double"));

  mysql_select_db($database_conexion1, $conexion1);
  $resultdetalle=mysql_query($sqldetalle);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error()); 



   //Ingreso a inventario 
   $myObject = new oIngresosalida();
   $inventario =  new oIngresosalida();

   if(isset($_POST['n_oc_vi']) && (isset($_POST['clase_insumo']) && $_POST['clase_insumo']==28 ) )//28 es la clase de insumos de pagina web por combos
   { 
    $inventario=$myObject->Obtener('tbl_ingresosalida_items','oc', " '".$_POST['n_oc_vi']."'  " );
 
   
    $myObject->RegistrarVerif("tbl_ingresosalida_items", "nombre,ingresokilos,fecharecepcion,oc,fechasalida,salidakilos,inventariofinal,totalconsumo,responsable,modificado", $_POST);
    }



  /*$insertSQL2 = sprintf("INSERT INTO verificacion_noconformei (n_vi, ensayo, fecha, proveedor, loteprod, referencia, ancho, factura, ocompra, destinada, produccion, otros, colorfilm, numprueba, preg_1, preg_6, preg_11, preg_2, preg_7, preg_12, preg_3, preg_8, preg_13, preg_4, preg_9, preg_14, preg_5, preg_10, preg_15,no_conforme,preg_16,observacion ) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
   GetSQLValueString($_POST['n_vi'], "int"),
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
   GetSQLValueString($_POST['no_conforme'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($insertSQL2, $conexion1) or die(mysql_error());*/


  $insertGoTo = "verificacion_insumo_vista.php?n_vi=" . $_POST['n_vi'] . "";
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

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM verificacion_insumos ORDER BY n_vi DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_orden_compra = "-1";
if (isset($_GET['n_oc'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM orden_compra WHERE n_oc = %s", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

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
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <script type="text/javascript" src="js/consulta.js"></script>



  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" crossorigin="anonymous"></script>  

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
                  <li><a href="compras.php">GESTION COMPRAS</a></li>
                </ul>
              </td>
            </tr>  
            <tr>
              <td colspan="2" align="center">
                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_vi','','R','recibido_vi','','R','saldo_anterior_vi','','R','cantidad_solicitada_vi','','R','cantidad_recibida_vi','','R','faltantes_vi','','R','registro_vi','','R','fecha_registro_vi','','R','no_conforme','','R');return document.MM_returnValue">
                  <table id="tabla2">
                    <tr id="tr2">
                      <td colspan="4" id="titulo2">VERIFICACION DE INSUMOS ( CRITICOS ) N&deg; <?php $numero=$row_ultimo['n_vi']+1; echo $numero; ?>
                        <input name="n_vi" type="hidden" value="<?php echo $numero; ?>">
                        <input name="id_det_vi" type="hidden" id="id_det_vi" value="<?php echo $row_insumo_det['id_det']; ?>"></td>
                      </tr>
                      <tr>
                        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
                        <td colspan="2" id="numero2">&nbsp;</td>
                        <td id="fuente2"><a href="verificacion_insumo_oc.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>"><img src="images/v.gif" alt="VERIFICACIONES X REF" border="0" style="cursor:hand;"/></a><a href="verificaciones_criticos.php"><img src="images/cat.gif" style="cursor:hand;" alt="VERIFICACIONES (CRITICOS)" border="0"/></a><a href="orden_compra.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="insumos.php"><img src="images/i.gif" style="cursor:hand;" alt="INSUMOS" border="0"/></a><a href="verificacion_insumo_listado.php"><img src="images/11.png" style="cursor:hand;" alt="LISTADO VERIF" title="LISTADO VERIF" border="0"/></a></td>
                      </tr>

                      <tr>
                        <td colspan="3" id="subtitulo1"><strong>O.C. N&deg; </strong><?php echo $row_orden_compra['n_oc']; ?>
                          <input name="n_oc_vi" type="hidden" value="<?php echo $row_orden_compra['n_oc']; ?>">
                          DE <input name="id_p_vi" type="hidden" value="<?php echo $row_orden_compra['id_p_oc']; ?>">
                          <?php $id_p=$row_orden_compra['id_p_oc'];
                          $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
                          $resultp=mysql_query($sqlp);
                          $nump=mysql_num_rows($resultp);
                          if($nump >= '1') { $proveedor_p=mysql_result($resultp,0,'proveedor_p'); echo $proveedor_p; }
                          else { echo "";	} ?></td>
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente1">INSUMO RECIBIDO</td>
                          <td nowrap id="fuente1">CONFORME ?</td>
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente1">
                            <select name="id_insumo_vi" onBlur="verificacion()">
                            <option value="0" <?php if (!(strcmp(0, $row_insumo_det['id_insumo_det']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
                            <?php
                            do {  
                              ?>
                              <option value="<?php echo $row_insumos['id_insumo']?>"<?php if (!(strcmp($row_insumos['id_insumo'], $row_insumo_det['id_insumo_det']))) {echo "selected=\"selected\"";} ?>><?php echo $row_insumos['descripcion_insumo']?></option>
                              <?php
                            } while ($row_insumos = mysql_fetch_assoc($insumos));
                            $rows = mysql_num_rows($insumos);
                            if($rows > 0) {
                              mysql_data_seek($insumos, 0);
                              $row_insumos = mysql_fetch_assoc($insumos);
                            }
                            ?>
                          </select></td>
                          <td>
                            <select onChange="comprobar_conforme(this)"  id="no_conforme" name="no_conforme" required>
                              <option value="SI">SI</option>
                              <option value="NO">NO</option>
                            </select>
                          </td>
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente1">NOMBRE DE  QUIEN RECIBIO EL INSUMO </td>
                        </tr>
                        <tr>
                          <td colspan="3" id="dato1"><input type="text" name="recibido_vi" value="" size="30"></td>
                        </tr>

                        <tr>
                          <td id="fuente1">FECHA DE RECIBIDO</td>
                          <td id="fuente1">FACTURA</td>
                          <td id="fuente1">REMISION</td>
                          <td id="fuente1">ENTREGA</td>
                        </tr>
                        <tr>
                          <td id="dato1"><input type="text" name="fecha_vi" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
                          <td id="dato1"><input type="text" name="factura_vi" value="" size="20"></td>
                          <td id="dato1"><input type="text" name="remision_vi" value="" size="20"></td>
                          <td id="dato1"><select name="entrega_vi">
                            <option value="PARCIAL">PARCIAL</option>
                            <option value="TOTAL">TOTAL</option>
                          </select></td>
                        </tr>
                        <tr>
                          <td id="fuente1">CANTIDAD PEDIDA </td>
                          <td id="fuente1">SALDO ANTERIOR </td>
                          <td id="fuente1">CANT. RECIBIDA </td>
                          <td id="fuente1">FALTANTES</td>
                        </tr>
                        <tr>
                          <td id="dato1"><input type="text" name="cantidad_solicitada_vi" value="<?php echo $row_insumo_det['cantidad_det']; ?>" size="20" onBlur="faltantes()"></td>
                          <td id="dato1"><input name="saldo_anterior_vi" type="text" id="saldo_anterior_vi" onBlur="faltantes()" value="<?php echo $row_insumo_det['verificacion_det']; ?>" size="20"></td>
                          <td id="dato1"><input type="text" name="cantidad_recibida_vi" value="" size="20" onBlur="faltantes()"></td>
                          <td id="dato1"><input name="faltantes_vi" type="text" id="faltantes_vi" value="" size="20" ></td>
                        </tr>
                        <tr>
                          <td colspan="4" id="fuente1">OBSERVACIONES</td>
                        </tr>
                        <tr>
                          <td colspan="4" id="dato1"><textarea name="observaciones_vi" cols="80" rows="3"></textarea></td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">APARIENCIA DEL INSUMO RECIBIDO</td>
                          <td colspan="2" id="fuente1"><input name="accion_vi" type="checkbox" id="accion_vi" value="1">
                          REQUIERE PLAN DE ACCION</td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">
                            <select name="apariencia_vi" id="apariencia_vi">
                              <option value="1">Buena (1)</option>
                              <option value="0.5">Regular (0.5)</option>
                              <option value="0">Mala (0)</option>
                            </select></td>
                            <td colspan="2" id="fuente1">&nbsp;</td>
                          </tr>
                          <tr>
                            <td id="fuente1">CALIFICACION DEL SERVICIO</td>
                            <td colspan="2" id="fuente1">REGISTRO EN EL SISTEMA</td>
                            <td id="fuente1">FECHA REGISTRO </td>
                          </tr>
                          <tr>
                            <td id="dato1"><select name="servicio_vi" id="servicio_vi">
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                              <option value="9">9</option>
                              <option value="10">10</option>
                            </select></td>
                            <td colspan="2" id="dato1"><input name="registro_vi" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" readonly></td>
                            <td id="dato1"><input name="fecha_registro_vi" type="text" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
                          </tr><br>



                          <!-- INICIA NO CONFORME -->
                          <hr>
                          <tr>
                            <td colspan="9" >
                              <div id="noconforme" style="display: none;" >
                                <table class="table">
                                  <thead >
                                    <tr id="tr2">
                                      <th colspan="12" scope="col" style="text-align: center;" >IDENTIFICACIÓN</th> 
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <th><b> Ensayo N°: </b></th>
                                      <td><input type="text" name="ensayo" value=""></td>
                                      <td ><b>Fecha: </b> </td>
                                      <td colspan="6" ><input type="date" name="fecha" value=""></td>
                                    </tr>
                                    <tr>
                                      <th><b> Proveedor: </b></th>
                                      <td><input type="text" name="proveedor" value=""></td>
                                      <td > <b> Lote de Producción: </b></td>
                                      <td colspan="6" ><input type="text" name="loteprod" value=""></td>
                                    </tr>
                                    <tr>
                                      <th><b> Referencia: </b></th>
                                      <td><input type="text" name="referencia" value=""></td>
                                      <td><b> Ancho (mm): </b></td>
                                      <td><b><input type="text" name="ancho" value="" size="7"></b></td>
                                      <td><b>  Factura :</b></td>
                                      <td  ><input type="text" name="factura" value="" size="7"></td>
                                    </tr>
                                    <tr>
                                      <th><b> O de Compra: </b></th>
                                      <td><input type="text" name="ocompra" value=""></td>
                                      <td><b> Destinada a: </b></td>
                                      <td><input type="text" name="destinada" value="" size="5"></td>
                                      <td><b>  Producción:</b></td>
                                      <td><input type="text" name="produccion" value="" size="5"></td>
                                      <td><b>  Otros</b></td>
                                      <td><input type="text" name="otros" value="" size="5"></td>
                                    </tr>
                                  </tbody>
                                </table>

                                <table class="table">
                                  <thead >
                                    <tr id="tr2">
                                      <th colspan="12" scope="col" style="text-align: center;" >CONDICIONES DE ENSAYO</th> 
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td><b> Color del Film: </b></td> 
                                      <td ><input type="text" name="colorfilm" value=""></td> 
                                      <td><b> Número de Pruebas: </b></td> 
                                      <td ><input type="text" name="numprueba" value=""></td> 
                                    </tr>
                                  </tbody>
                                </table>


                                <table class="table">
                                  <thead >
                                    <tr id="tr2">
                                      <th nowrap colspan="4" scope="col" >Ensayo con Calor 48 ºc</th>
                                      <th nowrap colspan="4" scope="col" >Ensayo con frio -96ºc </th> 
                                      <th nowrap colspan="4" scope="col" >Ensayo temperatura ambiente 25ºc </th> 
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td colspan="3"><b> Prueba</b></td>
                                      <td><b> Cumple</b></td>
                                      <td colspan="3"><b> Prueba</b></td>
                                      <td><b> Cumple</b></td>
                                      <td colspan="3"><b> Prueba</b></td>
                                      <td><b> Cumple</b></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" >1</td>
                                      <td nowrap> <input type="checkbox" name="preg_1" value="SI" ></td>
                                      <td colspan="3" >1</td>
                                      <td nowrap> <input type="checkbox" name="preg_6" value="SI" ></td>
                                      <td colspan="3" >1</td>
                                      <td nowrap> <input type="checkbox" name="preg_11" value="SI" ></td>
                                    </tr> 
                                    <tr>
                                      <td colspan="3" >2</td>
                                      <td nowrap> <input type="checkbox" name="preg_2" value="SI" ></td>
                                      <td colspan="3" >2</td>
                                      <td nowrap> <input type="checkbox" name="preg_7" value="SI" ></td>
                                      <td colspan="3" >2</td>
                                      <td nowrap> <input type="checkbox" name="preg_12" value="SI" ></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" >3</td>
                                      <td nowrap> <input type="checkbox" name="preg_3" value="SI" ></td>
                                      <td colspan="3" >3</td>
                                      <td nowrap> <input type="checkbox" name="preg_8" value="SI" ></td>
                                      <td colspan="3" >3</td>
                                      <td nowrap> <input type="checkbox" name="preg_13" value="SI" ></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" >4</td>
                                      <td nowrap> <input type="checkbox" name="preg_4" value="SI" ></td>
                                      <td colspan="3" >4</td>
                                      <td nowrap> <input type="checkbox" name="preg_9" value="SI" ></td>
                                      <td colspan="3" >4</td>
                                      <td nowrap> <input type="checkbox" name="preg_14" value="SI" ></td>
                                    </tr>
                                    <tr>
                                      <td colspan="3" >5</td>
                                      <td nowrap> <input type="checkbox" name="preg_5" value="SI" ></td>
                                      <td colspan="3" >5</td>
                                      <td nowrap> <input type="checkbox" name="preg_10" value="SI" ></td>
                                      <td colspan="3" >5</td>
                                      <td nowrap> <input type="checkbox" name="preg_15" value="SI" ></td>
                                    </tr>
                                  </tbody>
                                </table>
                                    <div class="row" >
                                      <div class="8" >
                                       Observaciones: <br>
                                       <textarea name="observacion" cols="80" rows="3"></textarea> 
                                      </div>
                                      <div class="4" >
                                       Cumple? 
                                       <input type="checkbox" name="preg_16" value="SI" > 
                                       </div>
                                    </div>
                              </div>
                            </td>
                          </tr>


                          <tr>
                            <td colspan="4" id="dato2">
                              <input type="hidden" name="clase_insumo" value="<?php echo $row_claseinsumos['clase_insumo'];?>"> 
                              <input name="submit" type="submit" value="ADD VERIFICACION"></td>
                          </tr>
                        </table><p></p>
                        <input type="hidden" name="MM_insert" value="form1">
                      </form>
                    </td>
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

           $( document ).ready(function() {
             comprobar_conforme($('#no_conforme').val());
           }); 
            /*  $('#no_conforme').change(function(){
                          comprobar_conforme();
                        }); */

                        function comprobar_conforme(sel){


                         if(sel.value == 'NO'){
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

                     mysql_free_result($ultimo);

                     mysql_free_result($orden_compra);

                     mysql_free_result($insumos);

                     mysql_free_result($insumo_det);
                     ?>