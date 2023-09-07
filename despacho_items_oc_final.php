<?php require_once('Connections/conexion1.php'); ?>
<?php
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
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
			
$updateSQL = sprintf("UPDATE Tbl_remisiones SET str_numero_oc_r=%s,fecha_r=%s,str_encargado_r=%s,str_transportador_r=%s,str_guia_r=%s,
str_elaboro_r=%s,str_aprobo_r=%s,str_observacion_r=%s,b_borrado_r=%s  WHERE int_remision=%s",
                       
                       GetSQLValueString($_POST['str_numero_oc_r'], "text"),
                       GetSQLValueString($_POST['fecha_r'], "date"),
					   GetSQLValueString($_POST['str_encargado_r'], "text"),
					   GetSQLValueString($_POST['str_transportador_r'], "text"),
					   GetSQLValueString($_POST['str_guia_r'], "text"),
					   GetSQLValueString($_POST['str_elaboro_r'], "text"),
                       GetSQLValueString($_POST['str_aprobo_r'], "text"),
					   GetSQLValueString($_POST['str_observacion_r'], "text"),                      
                       GetSQLValueString($_POST['b_borrado_r'], "int"),
					   GetSQLValueString($_POST['int_remision'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "despacho_items_oc_vista.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
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
$query_remision_detalle = sprintf("SELECT * FROM Tbl_remision_detalle WHERE int_remision_r_rd = %s ORDER BY id_rd ASC ", $colname_remision_detalle);
$remision_detalle = mysql_query($query_remision_detalle, $conexion1) or die(mysql_error());
$row_remision_detalle = mysql_fetch_assoc($remision_detalle);
$totalRows_remision_detalle = mysql_num_rows($remision_detalle);
//REMISIONES
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
<title>SISADGE AC &amp; CIA</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/listado.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
/*window.onbeforeunload = confirmaSalida;  
function confirmaSalida()   {    
       if (str_encargado_r.value!="") {
              return "¿Vas a abandonar esta pagina. Si has hecho algun cambio sin grabar, vas a perder todos los datos.?";  
       }
}*/
</script>
<!--CONFIRMACION AL DARLE CLICK EN SALIR BOTON-->
<script type="text/javascript">
/*function salir()
{
	
	sal=confirm("¿Desea cerrar el despacho, se guardara todos los cambios realizados?");
	if(sal) {
	//document.form1.submit();
	window.form1.submit()
	window.close()  
	//window.location ="despacho_items_oc_vista.php?"; 	
	}	
	else { window.history.go(); }         	   
}*/
function salir()
{
var statusConfirm = confirm("Esta seguro de finalizar el proceso?");
if (statusConfirm == true)
{
	window.form1.submit()
	window.location ='despacho_items_oc_edit.php'
}else if (statusConfirm == false)
{
   
   window.close();
}
}
</script>
<!--FIN-->
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
  <li><a href="menu.php">GESTION COMPRAS</a></li>
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
            <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" title="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="orden_compra_cl_vista.php?str_numero_oc=<?php echo $row_orden_compra['str_numero_oc']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="orden_compra_cl.php"><img src="images/o.gif" alt="ORDENES DE COMPRA" title="ORDENES DE COMPRA" border="0" style="cursor:hand;"/></a><a href="menu.php"><img src="images/opciones.gif" alt="GESTION DE COMPRAS" title="GESTION DE COMPRAS" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td id="numero2">N&deg;
              <input name="int_remision" type="text"size="15"class="rojo_inteso" value="<?php echo $row_remision['int_remision']; ?>" readonly/></td>
            <td id="dato_1"><div id="resultado"></div><?php $oc=$_GET['oc'];?><input name='oc' type='hidden' value='<?php echo $oc; ?>'> </td>
          </tr>
          <tr>
            <td id="fuente1">FECHA INGRESO</td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato1"><input name="fecha_r" type="text" id="fecha_r" value="<?php echo $row_remision['fecha_r']; ?>" size="10"></td>
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
            <td width="50%" colspan="2" id="dato1"><strong>PAIS / CIUDAD : </strong><?php  $cad=htmlentities ($row_orden_compra['pais_c']);echo $cad; ?> / <?php $cad2=htmlentities ($row_orden_compra['ciudad_c']); echo $cad2;?></td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><strong>NIT : </strong><?php echo $row_orden_compra['nit_c']; ?></td>
            <td colspan="2" id="dato1"><strong>TELEFONO:</strong><?php echo $row_orden_compra['telefono_c']; ?></td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><strong>DIRECCCION ENTREGA FACTURA:</strong>
              <?php  $cade = htmlentities($row_orden_compra['str_dir_entrega_oc']); echo $cade; ?>
            </td>
            </tr>
          <tr>
            <td width="14%" id="dato1"><strong>ENCARGADO:</strong> </td>
            <td width="36%" id="dato1"><input name="str_encargado_r" type="text" id="str_encargado_r"value="<?php echo $row_remision['str_encargado_r']; ?>"></td>
            <td id="dato1"><strong>GUIA : </strong>              </td>
            <td id="dato1"><input type="text" name="str_guia_r" id="str_guia_r" size="15"value="<?php echo $row_remision['str_guia_r']; ?>"></td>
          </tr>
          <tr>
            <td id="dato1"><strong>TRANSPORTADOR:</strong></td>
            <td id="dato1"><strong>
              <input type="text" name="str_transportador_r" id="str_transportador_r"value="<?php echo $row_remision['str_transportador_r']; ?>">
              </strong></td>
            <td id="dato1"><strong>DESPACHADO POR :
                
            </strong></td>
            <td id="dato1"><strong>
              <input name="str_elaboro_r" type="text" onKeyUp="conMayusculas(this)"id="str_elaboro_r" value="<?php echo $row_remision['str_elaboro_r']; ?>" size="15">
            </strong></td>
          </tr>
          <tr>
            <td id="dato1"><strong>APROBADO POR</strong></td>
            <td id="dato1"><input name="str_aprobo_r" type="text" id="str_aprobo_r" value="<?php echo $row_remision['str_aprobo_r']; ?>"onKeyUp="conMayusculas(this)" ></td>
            <td colspan="2" id="dato1">&nbsp;</td>
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
                <td id="nivel2">ITEM N°</td>
                <td id="nivel2">REF. AC</td>
                <td id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">CANTIDAD</td>
                <td id="nivel2">UNIDADES</td>
                <td id="nivel2">FECHA ENTREGA</td>
                <td id="nivel2">PRECIO / VENTA</td>
                <td id="nivel2">TOTAL ITEM</td>
                <td id="nivel2">MONEDA</td>
                <td id="nivel2">DIRECCION ENTREGA</td>
                </tr>
              <?php do { ?>
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF"> 
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_consecutivo_io']; ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_cod_ref_io']; ?></a></td>
                  <td id="talla3"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_cod_cliente_io']; ?></a></td>
                  <td id="talla3"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_cantidad_io']; ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['str_unidad_io']; ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['fecha_entrega_io']; ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_precio_io']; ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['int_total_item_io'];$total=$subtotal+$row_items['int_total_item_io'];?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['str_moneda_io']; ?></a></td>
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_items['id_items']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_items['str_direccion_desp_io']; ?></a></td>
                </tr>
                <?php } while ($row_items = mysql_fetch_assoc($items)); ?>

            </table></td>
            </tr><?php } ?>
			<tr>
            <td colspan="4" id="fuente2">&nbsp;</td>
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
                <td id="nivel2">REF. CLIENTE</td>
                <td id="nivel2">DESCRIPCION</td>
                <td id="nivel2">UNIDADES</td>
                <td id="nivel2">CAJA</td>
                <td id="nivel2">NUM. DESDE</td>
                <td id="nivel2">NUM. HASTA</td>
                <td id="nivel2">PESO</td>  
                <td id="nivel2">PESO/NETO</td>                
                </tr>
              <?php do { ?>
                <tr onMouseOver="uno(this,'CBCBE4');" onMouseOut="dos(this,'#FFFFFF');" bgcolor="#FFFFFF"> 
                  <td id="talla1"><a href="javascript:eliminar1('id_rd',<?php echo $row_remision_detalle['id_rd']; ?>,'despacho_oc_add_detalle.php')"><img src="images/por.gif" alt="ELIMINAR REMISION." title="ELIMINAR REMISION" border="0" style="cursor:hand;"/></a></td>
                  <td id="talla1"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_items=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_ref_io_rd']; ?></a></td>
                  <td id="talla2"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_rc['str_ref_cl_rc']; ?></a></td>                  
                  <td id="talla2"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_rc['str_descripcion_rc']; ?></a></td>
                  <td id="talla2"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_cant_rd']; ?></a></td>
                  <td id="talla2"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_caja_rd']; ?></a></td>
                  <td id="talla2"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_numd_rd']; ?></a></td>
                  <td id="talla2"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_numh_rd'];$total=$subtotal+$row_remision_detalle['int_total_item_io'];?></a></td>
                  <td id="talla2"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_peso_rd']; ?></a></td>
                  <td id="talla2"><a href="javascript:verFoto('despacho_oc_add_detalle.php?id_rd=<?php echo $row_remision_detalle['id_rd']; ?>&int_remision=<?php echo $row_remision['int_remision']; ?>','900','400')" target="_top" style="text-decoration:none; color:#000000"><?php echo $row_remision_detalle['int_pesoneto_rd']; ?></a></td>
                </tr>
                <?php } while ($row_remision_detalle = mysql_fetch_assoc($remision_detalle)); ?>

            </table></td>
            </tr><?php } ?>
          <tr>            
            <td colspan="4"id="fuente2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4"id="nivel3"><strong>TOTAL CAJAS:</strong>
              <input type="text" name="FG" id="FG" size="5">
            </td>
          </tr>
          <tr>
            <td colspan="4"id="nivel3"><strong>TOTAL PESO:</strong><input type="text" name="FG" id="FG" size="5"></td>
          </tr>
          <tr>
            <td colspan="4"id="nivel3"><strong>TOTAL P/NETO:</strong><input type="text" name="FG" id="FG" size="5"></td>
          </tr>
          <tr>
            <td colspan="3" id="fuente2"><input type="hidden" name="MM_update" value="form1">
              <input type="hidden" name="b_borrado_r" id="b_borrado_r" value="0">
              <input type="hidden" name="str_numero_oc_r" id="str_numero_oc_r" value="<?php echo $row_remision['str_numero_oc_r']; ?>">
              <img src="images/salir.gif" style="cursor:hand;" alt="GUARDAR Y SALIR" title="GUARDAR Y SALIR" onClick="salir()"/>              <input type="submit" value="FINALIZAR REMISION"></td>
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
mysql_free_result($usuario);

mysql_free_result($orden_compra);

mysql_free_result($remision);

mysql_free_result($items);

?>