<?php
   require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
   require (ROOT_BBDD); 
?> 
<?php require_once('Connections/conexion1.php'); ?>
<?php
//LLAMADO A FUNCIONES
include('funciones/adjuntar.php'); 
include('funciones/funciones_php.php');//SISTEMA RUW PARA LA BASE DE DATOS 
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
 

  if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {

    $directorio = ROOT."ArchivosOcProv/";

      
      $archivofinal = $_POST['userfilegen']; 


      $adjunto1 = ($_FILES['userfilenuevo']['name']); 
      if($_FILES['userfilenuevo']['name'] != "") { 
        $porciones1 = explode(".", $_FILES['userfilenuevo']['name']);
        $adjunto1 = "ADJ1". $_POST['n_oc'] . "." . $porciones1[1];
        $tieneadjunto1 = adjuntarArchivoOK($_POST['userfile1'], $directorio, $adjunto1,$_FILES['userfilenuevo'],'UPDATES');  
        $archivofinal .= $tieneadjunto1.',';
      } 
 

      $adjunto2 = ($_FILES['userfilenuevo2']['name']); 
      if($_FILES['userfilenuevo2']['name'] != '') {
        $porciones2 = explode(".", $_FILES['userfilenuevo2']['name']);
        $adjunto2 = "ADJ2". $_POST['n_oc'] . "." . $porciones2[1];
        $tieneadjunto2 = adjuntarArchivoOK($_POST['userfile2'], $directorio, $adjunto2,$_FILES['userfilenuevo2'],'UPDATES');  
        $archivofinal .= $tieneadjunto2.',';
      } 

      
      $adjunto3 = ($_FILES['userfilenuevo3']['name']);
      if($_FILES['userfilenuevo3']['name'] != '') {
        $porciones3 = explode(".", $_FILES['userfilenuevo3']['name']);
        $adjunto3 = "ADJ3". $_POST['n_oc'] . "." . $porciones3[1];
        $tieneadjunto3 = adjuntarArchivoOK($_POST['userfile3'], $directorio, $adjunto3,$_FILES['userfilenuevo3'],'UPDATES');  
        $archivofinal .= $tieneadjunto3;
       } 
    

 

$updateSQL = sprintf("UPDATE orden_compra SET id_p_oc=%s, fecha_pedido_oc=%s, fecha_entrega_oc=%s, cond_pago_oc=%s, observacion_oc=%s, valor_bruto_oc=%s, valor_iva_oc=%s, fte_oc=%s,fte_iva_oc=%s,fte_ica_oc=%s, total_oc=%s, lugar_entrega_oc=%s, aprobo_oc=%s, responsable_oc=%s, userfilenuevo=%s, adelanto_oc=%s, descuento_oc=%s, factura=%s,correo1=%s,correo2=%s,constante_iva=%s,constante_fte=%s,constante_fte_iva=%s,constante_fte_ica=%s,fecha_factura=%s,fecha_vence_factura=%s, tipo_pedido=%s,contacto=%s,horario=%s,telefono=%s WHERE n_oc=%s",
 GetSQLValueString($_POST['id_p_oc'], "int"),
 GetSQLValueString($_POST['fecha_pedido_oc'], "date"),
 GetSQLValueString($_POST['fecha_entrega_oc'], "date"),
 GetSQLValueString($_POST['cond_pago_oc'], "text"),
 GetSQLValueString($_POST['observacion_oc'], "text"),
 GetSQLValueString($_POST['valor_bruto_oc'], "text"),
 GetSQLValueString($_POST['valor_iva_oc'], "text"),
 GetSQLValueString($_POST['fte_oc'], "text"),
 GetSQLValueString($_POST['fte_iva_oc'], "text"),
 GetSQLValueString($_POST['fte_ica_oc'], "text"),
 GetSQLValueString($_POST['total_oc'], "text"),
 GetSQLValueString($_POST['lugar_entrega_oc'], "text"),
 GetSQLValueString($_POST['aprobo_oc'], "text"),
 GetSQLValueString($_POST['responsable_oc'], "text"),
 GetSQLValueString($archivofinal, "text"),	
 GetSQLValueString($_POST['adelanto_oc'], "text"),
 GetSQLValueString($_POST['descuento_oc'], "text"),
 GetSQLValueString($_POST['factura'], "text"),
 GetSQLValueString($_POST['correo1'], "text"),
 GetSQLValueString($_POST['correo2'], "text"), 
 GetSQLValueString($_POST['constante_iva'], "text"),
 GetSQLValueString($_POST['constante_fte'], "text"),
 GetSQLValueString($_POST['constante_fte_iva'], "text"),
 GetSQLValueString($_POST['constante_fte_ica'], "text"),
 GetSQLValueString($_POST['fecha_factura'], "date"),
 GetSQLValueString($_POST['fecha_vence_factura'], "date"),
 GetSQLValueString($_POST['tipo_pedido'], "text"),
 GetSQLValueString($_POST['contacto'], "text"),
 GetSQLValueString($_POST['horario'], "text"),
 GetSQLValueString($_POST['telefono'], "text"), 
 GetSQLValueString($_POST['n_oc'], "int"));

mysql_select_db($database_conexion1, $conexion1);
$Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

$updateGoTo = "orden_compra_vista.php?n_oc=" . $_POST['n_oc'] . "";
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

$colname_orden_compra = "-1";
if (isset($_GET['n_oc'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM orden_compra WHERE n_oc = '%s'", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

$colname_proveedor = "-1";
if (isset($_GET['id_p_oc'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p_oc'] : addslashes($_GET['id_p_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor WHERE id_p = '%s'", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

$colname_detalle = "-1";
if (isset($_GET['n_oc'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['n_oc'] : addslashes($_GET['n_oc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_detalle = sprintf("SELECT * FROM orden_compra_detalle, insumo WHERE orden_compra_detalle.n_oc_det = '%s' AND orden_compra_detalle.id_insumo_det=insumo.id_insumo", $colname_detalle);
$detalle = mysql_query($query_detalle, $conexion1) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);


?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>SISADGE AC &amp; CIA</title> 
 
  <link href="css/formato.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="js/formato.js"></script>
  <link rel="stylesheet" type="text/css" href="css/general.css"/>
  <script type="text/javascript" src="js/ordenCompraInsumos.js"></script> 
  <script type="text/javascript" src="js/listado.js"></script>
  <script type="text/javascript" src="js/validacion_numerico.js"></script>

  <!-- sweetalert -->
  <script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
  <link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

  <!-- select2 -->
<!--   <link href="select2/css/select2.min.css" rel="stylesheet"/>
  <script src="select2/js/select2.min.js"></script>  -->

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

  <!-- css Bootstrap hace mas grande el formato-->
  <link rel="stylesheet" href="bootstrap-4/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


</head>
<body   > 
<?php echo $conexion->header('vistas'); ?>

      <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" onSubmit="MM_validateForm('fecha_pedido_oc','','R','fecha_entrega_oc','','R','cond_pago_oc','','R','valor_bruto_oc','','R','valor_iva_oc','','R','total_oc','','R','lugar_entrega_oc','','R','aprobo_oc','','R','responsable_oc','','R');return document.MM_returnValue">
      <table class="table table-bordered table-sm">
        <tr id="tr1">
          <td nowrap id="codigo">CODIGO : A3 - F02</td>
          <td nowrap id="titulo2">ORDEN DE COMPRA </td>
          <td nowrap id="codigo">VERSION : 1 </td>
        </tr>
        <tr>
          <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
          <td id="subtitulo">INSUMOS</td>
          <td id="dato2"><a href="orden_compra_edit.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>&id_p_oc=<?php echo $row_orden_compra['id_p_oc']; ?>"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" /></a><a href="javascript:eliminar1('n_oc',<?php echo $row_orden_compra['n_oc']; ?>,'orden_compra_edit.php')"><img src="images/por.gif" alt="ELIMINAR O.C." border="0" style="cursor:hand;"/></a><a href="orden_compra_vista.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="orden_compra.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="compras.php"><img src="images/opciones.gif" alt="GESTION DE COMPRAS" border="0" style="cursor:hand;"/></a></td>
        </tr>
        <tr>
          <td id="numero2">N&deg; <strong><?php echo $row_orden_compra['n_oc']; ?></strong></td>
          <td id="dato2">&nbsp;</td>
        </tr>
        <tr>
          <td id="fuente1">FECHA DE PEDIDO </td>
          <td id="fuente1">FECHA RECIBIDO</td>
        </tr>
        <tr>
          <td id="dato1"><input type="date" name="fecha_pedido_oc" value="<?php echo $row_orden_compra['fecha_pedido_oc']; ?>" size="10"></td>
          <td id="dato1"><input type="date" class="rojo_normal_n" name="fecha_entrega_oc" value="<?php echo $row_orden_compra['fecha_entrega_oc']; ?>" size="10"></td>
        </tr>
        <tr>
          <td colspan="2" id="fuente1">PROVEEDOR</td>
        </tr>
        <tr>
          <td colspan="2" id="dato1">
            <select name="id_p_oc" id="id_p_oc" onBlur="consultaproveedor()">
              <option value="0" <?php if (!(strcmp(0, $_GET['id_p_oc']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
              <?php
              do {  
                ?>
                <option value="<?php echo $row_proveedores['id_p']?>"<?php if (!(strcmp($row_proveedores['id_p'], $_GET['id_p_oc']))) {echo "selected=\"selected\"";} ?>><?php echo $row_proveedores['proveedor_p']?></option>
                <?php
              } while ($row_proveedores = mysql_fetch_assoc($proveedores));
              $rows = mysql_num_rows($proveedores);
              if($rows > 0) {
                mysql_data_seek($proveedores, 0);
                $row_proveedores = mysql_fetch_assoc($proveedores);
              }
              ?>
            </select></td>
          </tr>
          <tr>
            <td colspan="3" id="dato1">
              <table class="table">
                <tr>
                  <td id="dato1" width="50%"><strong>NIT: </strong><?php echo $row_proveedor['nit_p']; ?></td>
                  <td id="dato1" width="50%"><strong>PAIS / CIUDAD: </strong><?php echo $row_proveedor['pais_p']; ?> / <?php echo $row_proveedor['ciudad_p']; ?></td>
                </tr>
                <tr>
                  <td colspan="2" id="dato1"><strong>CONTACTO COMERCIAL: </strong><?php echo $row_proveedor['contacto_p']; ?></td>
                </tr>
                <tr>
                  <td id="dato1"><strong>TELEFONO: </strong><?php echo $row_proveedor['telefono_p']; ?></td>
                  <td id="dato1"><strong>CELULAR: </strong><?php echo $row_proveedor['celular_c_p']; ?></td>
                </tr>
                <tr>
                  <td colspan="4" id="dato1"><strong>CORREO ELECTRONICO: </strong><?php echo $row_proveedor['email_c_p']; ?></td> 
                </tr>
              </table>
            </td>
          </tr> 
          <tr>
          <tr>
            <td colspan="4" id="dato1"><strong>DIRECCION DE ENTREGA</strong> </td> 
           </tr>  
           <tr>
             <td colspan="4"><textarea name="lugar_entrega_oc" class="rojo_normal_n" required="required" style="margin: 0px; width:100%; height: 50px;" rows="2" placeholder="DIRECCION DE ENTREGA" onBlur="conMayusculas(this)"><?php echo $row_orden_compra['lugar_entrega_oc']; ?></textarea>
             </td>
           </tr>
           <tr> 
            <td id="dato1"><strong>NOMBRE CONTACTO: </strong> </td>
            <td id="dato1"><strong>HORARIO DE RECEPCIÓN </strong> </td>
            <td id="dato1"><strong>TELÉFONO </strong> </td> 
           </tr>
           <tr>
             <td id="dato1"><input type="text" placeholder="Contacto" id="contacto" name="contacto" value="<?php echo $row_orden_compra['contacto']; ?>" style="width: 200px;" >
             </td>
             <td id="dato1"><input type="text" placeholder="Horario" id="horario" name="horario" value="<?php echo $row_orden_compra['horario']; ?>" style="width: 200px;" >
             </td>
             <td id="dato1"><input type="text" placeholder="Telefono" id="telefono" name="telefono" value="<?php echo $row_orden_compra['telefono']; ?>" style="width: 200px;" >
             </td> 
           </tr>  
           <tr> 
            <td id="dato1"><strong>FACTURAS </strong> </td>
            <td id="dato1"><strong>FECHA FACTURA </strong> </td>
            <td id="dato1"><strong>FECHA VENCE FACTURAS </strong> </td> 
           </tr> 
           <tr>
             <td id="dato1"><input type="text" placeholder="Factura Numero" name="factura" value="<?php echo $row_orden_compra['factura']; ?>" style="width: 200px;" >
             </td>
             <td id="dato1"><input type="date" placeholder="Fecha Factura" name="fecha_factura" value="<?php echo $row_orden_compra['fecha_factura']; ?>" style="width: 200px;" >
             </td>
             <td id="dato1"><input type="date" placeholder="Fecha Vence Facturas" name="fecha_vence_factura" value="<?php echo $row_orden_compra['fecha_vence_factura']; ?>" style="width: 200px;" >
                <select name="tipo_pedido" required="required" >
                      <option value=""<?php if (!(strcmp("",$row_orden_compra['tipo_pedido']))){echo "selected=\"selected\"";} ?>>Tipo Pedido</option>
                        <option value="Nacional"<?php if (!(strcmp("Nacional",$row_orden_compra['tipo_pedido']))){echo "selected=\"selected\"";} ?>>Nacional</option>
                        <option value="Importacion"<?php if (!(strcmp("Importacion",$row_orden_compra['tipo_pedido']))){echo "selected=\"selected\"";} ?>>Importacion</option>
                        <option value="Exportacion"<?php if (!(strcmp("Exportacion",$row_orden_compra['tipo_pedido']))){echo "selected=\"selected\"";} ?>>Exportacion</option> 
                      </select>
             </td>
           </tr>
           <tr>
            <td colspan="2" id="dato1"><strong>CORREO 1</strong> </td>
            <td colspan="2" id="dato1"><strong>CORREO 2</strong> </td>
           </tr> 
           <tr> 
             <td colspan="2" id="dato1"><input type="text" placeholder="Correo 1" name="correo1" value="<?php echo $row_orden_compra['correo1']; ?>" style="width: 250px;" onBlur="conMayusculas(this)"></td>
             <td colspan="2" id="dato1"><input type="text" placeholder="Correo 2" name="correo2" value="<?php echo $row_orden_compra['correo2']; ?>" style="width: 280px;" onBlur="conMayusculas(this)"></td>
           </tr>
           <tr>
            <td colspan="4" id="dato2"><strong><a href="javascript:verFoto('orden_compra_add_detalle.php?n_oc=<?php echo $row_orden_compra['n_oc']; ?>&id_p=<?php echo $row_proveedor['id_p']; ?>','1200','380')">* ADD ITEM *</a></strong></td>
            </tr><?php if($row_detalle['id_det']!='') { ?>
              <tr id="tr2">
                <td colspan="4" id="dato1">
                  <table class="table">
                  <tr>
                    <td id="nivel2">&nbsp;</td>
                    <td id="nivel2">CODIGO</td>
                    <td id="nivel2">CANT.</td>
                    <td id="nivel2">RESTANTE</td>
                    <td id="nivel2">DESCRIPCION</td>
                    <td id="nivel2">MEDIDA</td>
                    <td id="nivel2">MONEDA</td>
                    <td id="nivel2">CONCEPTO1</td>
                    <td id="nivel2">CONCEPTO2</td>
                    <td id="nivel2">VALOR</td>
                    <td id="nivel2">DESC.</td>
                    <td id="nivel2">IVA.</td>
                    <td id="nivel2">SUBTOTAL</td>
                    <td id="nivel2">TOTAL</td>
                    <td id="nivel2">INGRESO REAL</td>
                    <td id="nivel2">ESTADO</td>
                  </tr>
                  <?php do { ?>
                    <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF">
                      <td id="talla2"><a href="javascript:eliminar1('id_det',<?php echo $row_detalle['id_det']; ?>,'orden_compra_edit.php')"><img src="images/por.gif" alt="ELIMINAR O.C." border="0" style="cursor:hand;"/></a></td>
                      <td id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['codigo_insumo']; ?></a></td>
                      <td id="talla3"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000"><?php echo $cantidad=$row_detalle['cantidad_det']; ?></a></td>
                      <td id="talla2"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000">
                        <?php 
                         $row_restante = $conexion->llenarCampos("orden_compra_detalle ocd, TblIngresos ti", " WHERE ocd.id_det = ".$row_detalle['id_det']." AND ocd.id_det=ti.id_det_ing  ", " ", " ROUND(SUM(ti.ingreso_ing), 2) ingreso,ROUND(SUM(ti.salida_ing), 2) salida"); echo ($cantidad - $row_restante['ingreso']);    
                        ?></a>
                      </td>
                      <td id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['descripcion_insumo']; ?></a></td>
                      <td id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000">
                        <?php $medida=$row_detalle['medida_insumo'];
                        if($medida!='')
                        {
                          $sqlmedida="SELECT * FROM medida WHERE id_medida ='$medida'";
                          $resultmedida= mysql_query($sqlmedida);
                          $numedida= mysql_num_rows($resultmedida);
                          if($numedida >='1')
                          { 
                            $nombre_medida = mysql_result($resultmedida,0,'nombre_medida');
                            echo $nombre_medida;
                          } } ?></a>
                        </td>
                        <td id="talla2"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_detalle['moneda_det']; ?></a></td>
                        <td id="talla1"><?php echo $row_detalle['concepto1']; ?></td>
                        <td id="talla1"><?php echo $row_detalle['concepto2']; ?></td>
                        <td nowrap="nowrap" id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000">$ <?php echo $valor_unitario=$row_detalle['valor_unitario_det']; ?></a></td>
                        <td nowrap="nowrap"id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000">$ <?php echo $row_detalle['descuento_det']; $totalDescuento+=$row_detalle['descuento_det']; ?></a></td>
                        <td nowrap="nowrap" id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000">$ <?php echo $row_detalle['valor_iva']; $valor_iva += $row_detalle['valor_iva'];  ?></a></td>
                        <td nowrap="nowrap" id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000">$ <?php echo $row_detalle['subtotal_det']; $subtotal += $row_detalle['subtotal_det']; ?></a></td>
                        <td nowrap="nowrap" id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000">$ <?php echo $row_detalle['total_det']; $total += $row_detalle['total_det']; ?></a></td>
                          <?php 
                          $ingresos=$row_detalle['id_det']; 
                          $sqlingresos="SELECT ROUND(SUM(ingreso_ing), 2) sumaingre FROM TblIngresos WHERE id_det_ing ='$ingresos'";
                          $resultingresos= mysql_query($sqlingresos);
                          $nuingresos= mysql_num_rows($resultingresos);
                          if($nuingresos !='')
                          { 
                            $sumaingre = mysql_result($resultingresos,0,'sumaingre');
                          }
                          ?>
                          <td nowrap="nowrap" id="talla1"><a href="javascript:verFoto('orden_compra_edit_detalle.php?id_det=<?php echo $row_detalle['id_det']; ?>&id_insumo=<?php echo $row_detalle['id_insumo_det']; ?>','1200','380')" target="_top" style="text-decoration:none; color:#000000">$ <?php $totalIngresado +=($sumaingre*$valor_unitario);?> <b id="adecimal"><?php echo ($sumaingre*$valor_unitario);?></b> </a></td>
                        <td nowrap="nowrap" id="talla2">
                          <?php if( $row_detalle['cantidad_det'] > $sumaingre ) {?>
                            <a href="javascript:verFoto('orden_compra_add_ingreso.php?id_det=<?php echo $row_detalle['id_det']; ?>','1000','530')" target="_top" style="text-decoration:none; color:#000000"><img src="images/ingreso4.gif" alt="INGRESO" title="INGRESO" border="0" style="cursor:hand;"></a>
                          <?php }else{?>
                            <a href="javascript:verFoto('orden_compra_add_ingreso.php?id_det=<?php echo $row_detalle['id_det']; ?>','1000','530')" target="_top" style="text-decoration:none; color:#000000"><img src="images/ok.gif" alt="INGRESOS OK" width="46" height="42" style="cursor:hand;" title="INGRESOS OK" border="0"></a>
                          </td>
                        <?php }?>
                      </tr>
                    <?php } while ($row_detalle = mysql_fetch_assoc($detalle)); ?>
                  </table></td>           
                  </tr><?php } ?>
                  <tr>
                    <td id="fuente1">CONDICIONES DE PAGO </td>
                    <td id="fuente1">&nbsp;</td>
                    <td id="fuente1">ADJUNTAR ARCHIVO</td>
                  </tr>
                  <tr>
                  <td id="dato1">
                    <input type="text" name="cond_pago_oc" value="<?php echo $row_orden_compra['cond_pago_oc']; ?>" size="30" onBlur="conMayusculas(this)">&nbsp;&nbsp;&nbsp;
                  </td>

                    <td colspan="2" id="dato1">
                      <input type="hidden" name="userfilegen" id="userfilegen" value="<?php echo $row_orden_compra['userfilenuevo']; ?>" /> 
                         Adjuntar1:<input type="file" name="userfilenuevo" id="userfilenuevo"/> <br>
                         Adjuntar2:<input type="file" name="userfilenuevo2" id="userfilenuevo2"/><br>
                         Adjuntar3:<input type="file" name="userfilenuevo3" id="userfilenuevo3"/> 
                     </td>    
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
                          <td id="dato1" >
                            <a href="javascript:verFoto('ArchivosOcProv/<?php echo $value;?>','610','490')">Archivo<?php echo $count;?></a> 
                            <input name="userfile<?php echo $count;?>" type="hidden" id="userfile<?php echo $count;?>" value="<?php echo $value; ?>"/> 
                           </td>
                          <?php endif; ?>
                        <?php } ?> 
                      <?php endif; ?>
                  </tr>
                      
                    <tr>
                         
                    </tr>       
                    <tr> 
                      <td colspan="2" id="fuente1">OBSERVACIONES</td> 
                      <td colspan="2" id="fuente3"><strong>Adelanto:</strong>
                        <input type="number" name="adelanto_oc" id="adelanto_oc" value="<?php echo $row_orden_compra['adelanto_oc']=="" ? 0 : $row_orden_compra['adelanto_oc']; ?>" style="width:120px" min="0" step="0.01" onChange="neto_totalizar();"> 
                      </td> 

                      </tr>
                      <tr>
                        <td colspan="2" rowspan="8" id="fuente1"><textarea name="observacion_oc" class="rojo_normal_n" style="width:100%" rows="8" placeholder="OBSERVACIONES" ><?php echo $row_orden_compra['observacion_oc']; ?></textarea>
                        </td>
                         <td id="fuente3"><strong>Descuento:</strong>
                           <input type="number" name="descuento_oc" id="descuento_oc" value="<?php echo $row_orden_compra['descuento_oc']=="" ? $totalDescuento : $row_orden_compra['descuento_oc']; ?>" style="width:120px" min="0" step="0.01" onChange="neto_totalizar();" readonly > 
                         </td> 
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente3"><strong>Subtotal $</strong>
                            <input type="text" name="valor_bruto_oc" id="valor_bruto_oc"  value="<?php echo $totalIngresado-$totalDescuento; ?>" style="width:120px" min="0" step="0.0001" onChange="neto_totalizar();">
                          </td>
                        </tr>
                        <tr>
                        <td id="fuente3"><strong>I.V.A %
                          <input name="constante_iva" id="constante_iva" type="number" style="width:60px" min="0" step="0.01" value="<?php echo $row_orden_compra['constante_iva']=='' ? '19' :$row_orden_compra['constante_iva'] ; ?>" onChange="neto_totalizar();">
                        </strong>
                        <input type="text" name="valor_iva_oc" id="valor_iva_oc" value="<?php echo $valor_iva; ?>" style="width:120px" min="0"  step="0.0001"></td>
                      </tr>
                      <tr>
                        <td id="fuente3" nowrap>Rte. Fte 4.0%<strong>
                          <input name="constante_fte" id="constante_fte" type="number"  style="width:60px" min="0"  step="0.01" value="<?php echo $row_orden_compra['constante_fte']!='' ? $row_orden_compra['constante_fte']: '0'; ?>" onChange="neto_totalizar();" >
                        </strong>
                        <input name="fte_oc" id="fte_oc" type="text" id="fte_oc" value="<?php echo $rte_fte = $row_orden_compra['fte_oc']; ?>" style="width:120px" min="0" step="0.0001"></td>
                      </tr>
                      <tr>
                        <td id="fuente3">Rte. IVA 0%<strong>
                          <input name="constante_fte_iva" type="number" id="constante_fte_iva" style="width:60px" min="0" step="0.01" value="<?php echo $row_orden_compra['constante_fte_iva']!='' ? $row_orden_compra['constante_fte_iva']: '0'; ?>" onChange="neto_totalizar()">
                        </strong>
                        <input name="fte_iva_oc" type="text" id="fte_iva_oc" value="<?php echo $rte_iva = $row_orden_compra['fte_iva_oc']; ?>" style="width:120px" min="0" step="0.0001"></td>
                      </tr>
                      <tr>
                        <td id="fuente3">Rte. ICA 0%<strong>
                          <input name="constante_fte_ica" type="number" id="constante_fte_ica" style="width:60px" min="0" step="0.01" value="<?php echo $row_orden_compra['constante_fte_ica']!='' ? $row_orden_compra['constante_fte_ica']: '0'; ?>" onChange="neto_totalizar()">
                        </strong>
                        <input name="fte_ica_oc" type="text" id="fte_ica_oc" value="<?php echo $rte_ica = $row_orden_compra['fte_ica_oc']; ?>" style="width:120px" min="0" step="0.0001"></td>
                      </tr>
                      <tr>            
                        <td id="fuente3" ><strong>TOTAL: </strong>  
                            <?php echo $total ;?></td>
                       </tr>
                        <tr>
                          <td id="fuente3"><strong>Neto a pagar: </strong> 
                            <input type="text" name="total_oc" id="total_oc" value="<?php echo $total = $row_orden_compra['total_oc']!="" ? $row_orden_compra['total_oc'] : $total; ?>" style="width:120px" min="0" step="0.0001" >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2" id="fuente1">ELABORADO POR</td> 
                          <td colspan="2" id="fuente1">APROBADO POR</td> 
                        </tr>
                        <tr>
                          <td colspan="2" id="dato1"><input name="responsable_oc" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="20" readonly></td>
                          <td colspan="2" id="dato1"><input type="text" name="aprobo_oc" value="<?php echo $row_orden_compra['aprobo_oc']; ?>" size="20" onBlur="conMayusculas(this)"></td> 
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="3" id="fuente2"><input class="botonGeneral" type="submit" value="FINALIZAR O.C."></td>
                        </tr>
                      </table>
                      <input type="hidden" name="MM_update" value="form1">
                      <input type="hidden" name="n_oc" value="<?php echo $row_orden_compra['n_oc']; ?>">
                    </form>
                    <?php echo $conexion->header('footer'); ?>
          </body>
          </html>
 <script type="text/javascript">
  $(document).ready(function() { 
      /*adelanto(); 
      rte_fte();
      fte_ica();*/
      neto_totalizar()

  });
 

 </script> 

 <script>
   
  $('#id_p_oc').select2({ 
         ajax: {
             url: "select3/proceso.php",
             type: "post",
             dataType: 'json',
             delay: 250,
             data: function (params) {
                 return {
                     palabraClave: params.term, // search term
                     var1:"id_p",//campo normal para usar
                     var2:"proveedor",//tabla
                     var3:"",//where
                     var4:"ORDER BY proveedor_p ASC",
                     var5:"id_p",//clave
                     var6:"proveedor_p"//columna a buscar
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

  
 </script>
    <?php
    mysql_free_result($usuario); 

    mysql_free_result($orden_compra);

    mysql_free_result($proveedores);

    mysql_free_result($proveedor);

    mysql_free_result($detalle);
    ?>