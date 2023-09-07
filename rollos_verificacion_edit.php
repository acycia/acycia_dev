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
/*ACTUALIZA SALDO EN OCR*/
$n_ocr=$_POST['n_ocr_vr'];
$falta=$_POST['faltantes_vr'];
$sqlocr="UPDATE orden_compra_rollos SET saldo_verificacion_ocr='$falta' WHERE n_ocr='$n_ocr'";
/*ACTUALIZA LA VERIFICACION*/
  $updateSQL = sprintf("UPDATE verificacion_rollos SET n_ocr_vr=%s, id_p_vr=%s, id_rollo_vr=%s, id_ref_vr=%s, fecha_recibo_vr=%s, responsable_recibo_vr=%s, entrega_vr=%s, factura_vr=%s, remision_vr=%s, otro_recibo_vr=%s, unidades_recibidas_vr=%s, cantidad_solicitada_vr=%s, faltantes_vr=%s, cantidad_encontrada_vr=%s, cantidad_muestras_vr=%s, cantidad_no_conforme_vr=%s, cantidad_cumple_vr=%s, cantidad_observacion_vr=%s, calibre_solicitado_vr=%s, calibre_encontrado_vr=%s, calibre_muestras_vr=%s, calibre_no_conforme_vr=%s, calibre_cumple_vr=%s, calibre_observacion_vr=%s, peso_solicitado_vr=%s, peso_encontrado_vr=%s, peso_muestras_vr=%s, peso_no_conforme_vr=%s, peso_cumple_vr=%s, peso_observacion_vr=%s, ancho_solicitado_vr=%s, ancho_encontrado_vr=%s, ancho_muestras_vr=%s, ancho_no_conforme_vr=%s, ancho_cumple_vr=%s, ancho_observacion_vr=%s, rodillo_solicitado_vr=%s, rodillo_encontrado_vr=%s, rodillo_muestras_vr=%s, rodillo_no_conforme_vr=%s, rodillo_cumple_vr=%s, rodillo_observacion_vr=%s, tratamiento_solicitado_vr=%s, tratamiento_encontrado_vr=%s, tratamiento_muestras_vr=%s, tratamiento_no_conforme_vr=%s, tratamiento_cumple_vr=%s, tratamiento_observacion_vr=%s, md_solicitado_vr=%s, md_encontrado_vr=%s, md_muestras_vr=%s, md_no_conforme_vr=%s, md_cumple_vr=%s, md_observacion_vr=%s, td_solicitado_vr=%s, td_encontrado_vr=%s, td_muestras_vr=%s, td_no_conforme_vr=%s, td_cumple_vr=%s, td_observacion_vr=%s, angulo_solicitado_vr=%s, angulo_encontrado_vr=%s, angulo_muestras_vr=%s, angulo_no_conforme_vr=%s, angulo_cumple_vr=%s, angulo_observacion_vr=%s, fuerzaselle_solicitado_vr=%s, fuerzaselle_encontrado_vr=%s, fuerzaselle_muestras_vr=%s, fuerzaselle_no_conforme_vr=%s, fuerzaselle_cumple_vr=%s, fuerzaselle_observacion_vr=%s, apariencia_cumple_vr=%s, apariencia_muestras_vr=%s, apariencia_no_conforme_vr=%s, apariencia_observacion_vr=%s, resistencia_sellos_cumple_vr=%s, resistencia_sellos_muestras_vr=%s, resistencia_sellos_no_conforme_vr=%s, resistencia_sellos_observacion_vr=%s, impresion_cumple_vr=%s, impresion_muestras_vr=%s, impresion_no_conforme_vr=%s, impresion_observacion_vr=%s, color_cumple_vr=%s, color_muestras_vr=%s, color_no_conforme_vr=%s, color_observacion_vr=%s, tinta_cumple_vr=%s, tinta_muestras_vr=%s, tinta_no_conforme_vr=%s, tinta_observacion_vr=%s, observaciones_vr=%s, servicio_vr=%s, calificacion_vr=%s, fecha_registro_vr=%s, responsable_registro_vr=%s, fecha_modificacion_vr=%s, responsable_modificacion_vr=%s WHERE n_vr=%s",
                       GetSQLValueString($_POST['n_ocr_vr'], "int"),
                       GetSQLValueString($_POST['id_p_vr'], "int"),
                       GetSQLValueString($_POST['id_rollo_vr'], "int"),
                       GetSQLValueString($_POST['id_ref_vr'], "int"),
                       GetSQLValueString($_POST['fecha_recibo_vr'], "date"),
                       GetSQLValueString($_POST['responsable_recibo_vr'], "text"),
                       GetSQLValueString($_POST['entrega_vr'], "int"),
                       GetSQLValueString($_POST['factura_vr'], "text"),
                       GetSQLValueString($_POST['remision_vr'], "text"),
                       GetSQLValueString($_POST['otro_recibo_vr'], "text"),
                       GetSQLValueString($_POST['unidades_recibidas_vr'], "double"),
                       GetSQLValueString($_POST['cantidad_solicitada_vr'], "double"),
                       GetSQLValueString($_POST['faltantes_vr'], "double"),
                       GetSQLValueString($_POST['cantidad_encontrada_vr'], "double"),
                       GetSQLValueString($_POST['cantidad_muestras_vr'], "double"),
                       GetSQLValueString($_POST['cantidad_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['cantidad_cumple_vr'], "text"),
                       GetSQLValueString($_POST['cantidad_observacion_vr'], "text"),
                       GetSQLValueString($_POST['calibre_solicitado_vr'], "double"),
                       GetSQLValueString($_POST['calibre_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['calibre_muestras_vr'], "double"),
                       GetSQLValueString($_POST['calibre_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['calibre_cumple_vr'], "text"),
                       GetSQLValueString($_POST['calibre_observacion_vr'], "text"),
                       GetSQLValueString($_POST['peso_solicitado_vr'], "double"),
                       GetSQLValueString($_POST['peso_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['peso_muestras_vr'], "double"),
                       GetSQLValueString($_POST['peso_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['peso_cumple_vr'], "text"),
                       GetSQLValueString($_POST['peso_observacion_vr'], "text"),
                       GetSQLValueString($_POST['ancho_solicitado_vr'], "double"),
                       GetSQLValueString($_POST['ancho_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['ancho_muestras_vr'], "double"),
                       GetSQLValueString($_POST['ancho_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['ancho_cumple_vr'], "text"),
                       GetSQLValueString($_POST['ancho_observacion_vr'], "text"),
                       GetSQLValueString($_POST['rodillo_solicitado_vr'], "double"),
                       GetSQLValueString($_POST['rodillo_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['rodillo_muestras_vr'], "double"),
                       GetSQLValueString($_POST['rodillo_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['rodillo_cumple_vr'], "text"),
                       GetSQLValueString($_POST['rodillo_observacion_vr'], "text"),
                       GetSQLValueString($_POST['tratamiento_solicitado_vr'], "text"),
                       GetSQLValueString($_POST['tratamiento_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['tratamiento_muestras_vr'], "double"),
                       GetSQLValueString($_POST['tratamiento_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['tratamiento_cumple_vr'], "text"),
                       GetSQLValueString($_POST['tratamiento_observacion_vr'], "text"),
                       GetSQLValueString($_POST['md_solicitado_vr'], "double"),
                       GetSQLValueString($_POST['md_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['md_muestras_vr'], "double"),
                       GetSQLValueString($_POST['md_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['md_cumple_vr'], "text"),
                       GetSQLValueString($_POST['md_observacion_vr'], "text"),
                       GetSQLValueString($_POST['td_solicitado_vr'], "double"),
                       GetSQLValueString($_POST['td_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['td_muestras_vr'], "double"),
                       GetSQLValueString($_POST['td_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['td_cumple_vr'], "text"),
                       GetSQLValueString($_POST['td_observacion_vr'], "text"),
                       GetSQLValueString($_POST['angulo_solicitado_vr'], "double"),
                       GetSQLValueString($_POST['angulo_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['angulo_muestras_vr'], "double"),
                       GetSQLValueString($_POST['angulo_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['angulo_cumple_vr'], "text"),
                       GetSQLValueString($_POST['angulo_observacion_vr'], "text"),
                       GetSQLValueString($_POST['fuerzaselle_solicitado_vr'], "double"),
                       GetSQLValueString($_POST['fuerzaselle_encontrado_vr'], "double"),
                       GetSQLValueString($_POST['fuerzaselle_muestras_vr'], "double"),
                       GetSQLValueString($_POST['fuerzaselle_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['fuerzaselle_cumple_vr'], "text"),
                       GetSQLValueString($_POST['fuerzaselle_observacion_vr'], "text"),
                       GetSQLValueString($_POST['apariencia_cumple_vr'], "text"),
                       GetSQLValueString($_POST['apariencia_muestras_vr'], "double"),
                       GetSQLValueString($_POST['apariencia_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['apariencia_observacion_vr'], "text"),
                       GetSQLValueString($_POST['resistencia_sellos_cumple_vr'], "text"),
                       GetSQLValueString($_POST['resistencia_sellos_muestras_vr'], "double"),
                       GetSQLValueString($_POST['resistencia_sellos_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['resistencia_sellos_observacion_vr'], "text"),
                       GetSQLValueString($_POST['impresion_cumple_vr'], "text"),
                       GetSQLValueString($_POST['impresion_muestras_vr'], "double"),
                       GetSQLValueString($_POST['impresion_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['impresion_observacion_vr'], "text"),
                       GetSQLValueString($_POST['color_cumple_vr'], "text"),
                       GetSQLValueString($_POST['color_muestras_vr'], "double"),
                       GetSQLValueString($_POST['color_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['color_observacion_vr'], "text"),
                       GetSQLValueString($_POST['tinta_cumple_vr'], "text"),
                       GetSQLValueString($_POST['tinta_muestras_vr'], "double"),
                       GetSQLValueString($_POST['tinta_no_conforme_vr'], "double"),
                       GetSQLValueString($_POST['tinta_observacion_vr'], "text"),
                       GetSQLValueString($_POST['observaciones_vr'], "text"),
                       GetSQLValueString($_POST['servicio_vr'], "int"),
                       GetSQLValueString($_POST['calificacion_vr'], "double"),
                       GetSQLValueString($_POST['fecha_registro_vr'], "date"),
                       GetSQLValueString($_POST['responsable_registro_vr'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion_vr'], "date"),
                       GetSQLValueString($_POST['responsable_modificacion_vr'], "text"),
                       GetSQLValueString($_POST['n_vr'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $resultocr=mysql_query($sqlocr);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "rollos_verificacion_vista.php?n_vr=" . $_POST['n_vr'] . "";
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

$colname_verificacion_rollos = "-1";
if (isset($_GET['n_vr'])) {
  $colname_verificacion_rollos = (get_magic_quotes_gpc()) ? $_GET['n_vr'] : addslashes($_GET['n_vr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion_rollos = sprintf("SELECT * FROM verificacion_rollos WHERE n_vr = %s", $colname_verificacion_rollos);
$verificacion_rollos = mysql_query($query_verificacion_rollos, $conexion1) or die(mysql_error());
$row_verificacion_rollos = mysql_fetch_assoc($verificacion_rollos);
$totalRows_verificacion_rollos = mysql_num_rows($verificacion_rollos);

$colname_orden_compra_rollos = "-1";
if (isset($_GET['n_vr'])) {
  $colname_orden_compra_rollos = (get_magic_quotes_gpc()) ? $_GET['n_vr'] : addslashes($_GET['n_vr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra_rollos = sprintf("SELECT * FROM verificacion_rollos, orden_compra_rollos WHERE verificacion_rollos.n_vr = '%s' AND verificacion_rollos.n_ocr_vr = orden_compra_rollos.n_ocr", $colname_orden_compra_rollos);
$orden_compra_rollos = mysql_query($query_orden_compra_rollos, $conexion1) or die(mysql_error());
$row_orden_compra_rollos = mysql_fetch_assoc($orden_compra_rollos);
$totalRows_orden_compra_rollos = mysql_num_rows($orden_compra_rollos);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<table id="tabla3">
<tr><td align="center">
<table id="tabla1">
  <tr>
    <td colspan="2" id="fuente2"><img src="images/cabecera.jpg"></td>
    </tr>
  <tr>
    <td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul></td></tr></table></td></tr>
<tr><td id="linea1" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_recibo_vr','','R','responsable_recibo_vr','','R','n_ocr_vr','','R','unidades_recibidas_vr','','R','cantidad_solicitada_vr','','R','saldo_verificacion_ocr','','R','cantidad_encontrada_vr','','R','faltantes_vr','','R','calificacion_vr','','R','fecha_registro_vr','','R','responsable_registro_vr','','R');return document.MM_returnValue">
    <table id="tabla3">
        <tr id="tr1">
          <td id="codigo" width="25%">CODIGO : A3 - F08 </td>
          <td colspan="2" id="titulo2" width="50%">VERIFICACION</td>
          <td width="25%" colspan="2" id="codigo">VERSION : 1 </td>
        </tr>
        <tr>
          <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
          <td colspan="2" id="subtitulo">MATERIA PRIMA ( ROLLOS ) </td>
          <td colspan="2" id="dato2"><a href="rollos_verificacion_vista.php?n_vr=<?php echo $row_verificacion_rollos['n_vr']; ?>" target="_top"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('n_vr',<?php echo $row_verificacion_rollos['n_vr']; ?>,'rollos_verificacion_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a><a href="rollos_oc_verificacion.php?n_ocr=<?php echo $row_verificacion_rollos['n_ocr_vr']; ?>" target="_top"><img src="images/v.gif" alt="VERIFICACIONES (ROLLOS)" border="0" style="cursor:hand;"/></a><a href="rollos_oc.php" target="_top"><img src="images/o.gif" alt="O.C. (ROLLOS)" border="0" style="cursor:hand;"/></a><a href="rollos_verificacion.php" target="_top"><img src="images/cat.gif" alt="VERIFICACIONES (ROLLOS)" border="0" style="cursor:hand;"/></a><a href="rollos.php" target="_top"><img src="images/r.gif" alt="ROLLOS" border="0" style="cursor:hand;"/></a></td>
        </tr>
        <tr>
          <td colspan="2" id="numero2"><strong>N&deg; <?php echo $row_verificacion_rollos['n_vr']; ?></strong></td>
          <td colspan="2" id="dato2">&nbsp;</td>
        </tr>
        <tr>
          <td id="fuente2">FECHA DE RECIBO </td>
          <td id="fuente2">RECIBIDO POR </td>
          <td colspan="2" id="fuente2">ENTREGA</td>
          </tr>
        <tr>
          <td id="dato2"><input type="text" name="fecha_recibo_vr" value="<?php echo $row_verificacion_rollos['fecha_recibo_vr']; ?>" size="10"></td>
          <td id="dato2"><input type="text" name="responsable_recibo_vr" value="<?php echo $row_verificacion_rollos['responsable_recibo_vr']; ?>" size="20"></td>
          <td colspan="2" id="dato2"><select name="entrega_vr">
              <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['entrega_vr']))) {echo "selected=\"selected\"";} ?>>PARCIAL</option>
              <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['entrega_vr']))) {echo "selected=\"selected\"";} ?>>TOTAL</option>
          </select></td>
          </tr>
        <tr>
          <td id="fuente2">FACTURA</td>
          <td id="fuente2">REMISION</td>
          <td colspan="2" id="fuente2">OTRO RECIBO </td>
        </tr>
        <tr>
          <td id="dato2"><input type="text" name="factura_vr" value="<?php echo $row_verificacion_rollos['factura_vr']; ?>" size="20"></td>
          <td id="dato2"><input type="text" name="remision_vr" value="<?php echo $row_verificacion_rollos['remision_vr']; ?>" size="20"></td>
          <td colspan="2" id="dato2"><input type="text" name="otro_recibo_vr" value="<?php echo $row_verificacion_rollos['otro_recibo_vr']; ?>" size="20"></td>
        </tr>
        <tr id="tr1">
          <td id="fuente2">ORDEN DE COMPRA </td>
          <td id="fuente2">PROVEEDOR</td>
          <td id="fuente2">MATERIAL SOLICITADO </td>
          <td id="fuente2">REF.</td>
          <td id="fuente2">N&deg; ROLLOS RECIBIDOS </td>
        </tr>
        <tr>
          <td id="dato2"><input type="text" name="n_ocr_vr" value="<?php echo $row_verificacion_rollos['n_ocr_vr']; ?>" size="5"></td>
          <td id="dato2"><?php $id_p=$row_verificacion_rollos['id_p_vr'];
		  if($id_p == '') 
		  {  
		  $id_p=$row_orden_compra_rollos['id_p_ocr']; 
		  }
		  if($id_p != '')
		  {
		  $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
		  $resultp= mysql_query($sqlp);
		  $nump= mysql_num_rows($resultp);
		  if($nump >='1') 
		  { 
		  $proveedor_p = mysql_result($resultp,0,'proveedor_p');
		  echo $proveedor_p; 
		  }
		  } ?><input name="id_p_vr" type="hidden" value="<?php echo $id_p; ?>"></td>
          <td id="dato2"><?php $id_rollo=$row_verificacion_rollos['id_rollo_vr'];
		  if($id_rollo == '') 
		  {  
		  $id_rollo=$row_orden_compra_rollos['id_rollo_ocr']; 
		  }
		  if($id_rollo != '')
		  {
		  $sqlr="SELECT * FROM materia_prima_rollos WHERE id_rollo='$id_rollo'";
		  $resultr= mysql_query($sqlr);
		  $numr= mysql_num_rows($resultr);
		  if($numr >='1') 
		  { 
		  $nombre_rollo = mysql_result($resultr,0,'nombre_rollo');
		  echo $nombre_rollo; 
		  }
		  } ?><input name="id_rollo_vr" type="hidden" value="<?php echo $id_rollo; ?>"></td>
          <td id="dato2"><?php $ref=$row_verificacion_rollos['id_ref_vr'];
		  if($ref=='') { $ref=$row_orden_compra_rollos['id_ref_ocr']; }
		  if($ref!='') {  
		  $sqlref="SELECT * FROM Tbl_referencia WHERE id_ref='$ref'";
		  $resultref= mysql_query($sqlref);
		  $numref= mysql_num_rows($resultref);
		  if($numref >='1') { 
		  $cod_ref = mysql_result($resultref,0,'cod_ref');
		  echo $cod_ref; } } ?><input name="id_ref_vr" type="hidden" value="<?php echo $ref; ?>"></td>
          <td id="dato2"><input type="text" name="unidades_recibidas_vr" value="<?php echo $row_verificacion_rollos['unidades_recibidas_vr']; ?>" size="5"></td>
        </tr>
        <tr>
          <td colspan="5" align="center">		  
		  <table id="tabla3">
            <tr id="tr1">
              <td colspan="8" id="subtitulo1">I. PARAMETROS CUANTITATIVOS </td>
              </tr>
            <tr>
              <td colspan="8" id="dato1">Variable de <strong>CANTIDAD</strong> Kg (Si lo recibido es menor a lo pedido, habran faltantes para una futura entrega con su respectiva verificacion) </td>
              </tr>
            <tr>
              <td id="fuente2">SOLICITADA</td>
              <td id="fuente2">SALDO</td>
              <td id="fuente2">ENCONTRADA</td>
              <td id="fuente2">FALTANTES</td>
              <td id="fuente2">MUESTRAS</td>
              <td id="fuente2">NO CONFORME</td>
              <td id="fuente2">CUMPLE</td>
              <td id="fuente2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="dato2"><input type="text" name="cantidad_solicitada_vr" value="<?php if($row_verificacion_rollos['cantidad_solicitada_vr']=='')
			  { echo $row_orden_compra_rollos['cantidad_ocr']; }
			  else { echo $row_verificacion_rollos['cantidad_solicitada_vr']; } ?>" size="10" onBlur="vr_cantidad()"></td>
              <td id="dato2"><input name="saldo_verificacion_ocr" type="text" id="saldo_verificacion_ocr" value="<?php echo $row_orden_compra_rollos['saldo_verificacion_ocr']; ?>" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><input type="text" name="cantidad_encontrada_vr" value="<?php echo $row_verificacion_rollos['cantidad_encontrada_vr']; ?>" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><input name="faltantes_vr" type="text" id="faltantes_vr" value="<?php echo $row_verificacion_rollos['faltantes_vr']; ?>" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><input type="text" name="cantidad_muestras_vr" value="<?php echo $row_verificacion_rollos['cantidad_muestras_vr']; ?>" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><input type="text" name="cantidad_no_conforme_vr" value="<?php echo $row_verificacion_rollos['cantidad_no_conforme_vr']; ?>" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><select name="cantidad_cumple_vr" id="cantidad_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['cantidad_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['cantidad_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['cantidad_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="fuente2"><input type="text" name="cantidad_observacion_vr" value="<?php echo $row_verificacion_rollos['cantidad_observacion_vr']; ?>" size="30"></td>
            </tr>
			</table>
		<table id="tabla3">
            <tr id="tr1">
              <td colspan="2" id="subtitulo2">OTRAS VARIABLES</td>
              <td id="subtitulo2">SOLICITADO</td>
              <td id="subtitulo2">ENCONTRADO</td>
              <td id="subtitulo2">MUESTRAS</td>
              <td nowrap id="subtitulo2">NO CONFORME </td>
              <td id="subtitulo2">CUMPLE</td>
              <td id="subtitulo2">OBSERVACION</td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Calibre (micras) </td>
              <td id="dato2"><input type="text" name="calibre_solicitado_vr" value="<?php if($row_verificacion_rollos['calibre_solicitado_vr']=='')
			  { echo $row_orden_compra_rollos['calibre_micras_ocr']; }
			  else { echo $row_verificacion_rollos['calibre_solicitado_vr']; }
			  ?>" size="10" onBlur="vr_calibre()"></td>
              <td id="dato2"><input type="text" name="calibre_encontrado_vr" value="<?php echo $row_verificacion_rollos['calibre_encontrado_vr']; ?>" size="10" onBlur="vr_calibre()"></td>
              <td id="dato2"><input type="text" name="calibre_muestras_vr" value="<?php echo $row_verificacion_rollos['calibre_muestras_vr']; ?>" size="10" onBlur="vr_calibre()"></td>
              <td id="dato2"><input type="text" name="calibre_no_conforme_vr" value="<?php echo $row_verificacion_rollos['calibre_no_conforme_vr']; ?>" size="10" onBlur="vr_calibre()"></td>
              <td id="dato2"><select name="calibre_cumple_vr" id="calibre_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['calibre_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['calibre_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['calibre_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="calibre_observacion_vr" value="<?php echo $row_verificacion_rollos['calibre_observacion_vr']; ?>" size="30"></td>
            </tr>
            <tr id="tr2">
              <td colspan="2" id="dato1">Peso (kg) </td>
              <td id="dato2"><input type="text" name="peso_solicitado_vr" value="<?php
			   echo $row_verificacion_rollos['peso_solicitado_vr']; ?>" size="10" onBlur="vr_peso()"></td>
              <td id="dato2"><input type="text" name="peso_encontrado_vr" value="<?php echo $row_verificacion_rollos['peso_encontrado_vr']; ?>" size="10" onBlur="vr_peso()"></td>
              <td id="dato2"><input type="text" name="peso_muestras_vr" value="<?php echo $row_verificacion_rollos['peso_muestras_vr']; ?>" size="10" onBlur="vr_peso()"></td>
              <td id="dato2"><input type="text" name="peso_no_conforme_vr" value="<?php echo $row_verificacion_rollos['peso_no_conforme_vr']; ?>" size="10" onBlur="vr_peso()"></td>
              <td id="dato2"><select name="peso_cumple_vr" id="peso_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['peso_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['peso_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['peso_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="peso_observacion_vr" value="<?php echo $row_verificacion_rollos['peso_observacion_vr']; ?>" size="30"></td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Ancho del Rollo (cm) </td>
              <td id="dato2"><input type="text" name="ancho_solicitado_vr" value="<?php
			  if($row_verificacion_rollos['ancho_solicitado_vr']=='')
			  { echo $row_orden_compra_rollos['ancho_material_ocr']; } else { echo $row_verificacion_rollos['ancho_solicitado_vr']; } ?>" size="10" onBlur="vr_ancho()"></td>
              <td id="dato2"><input type="text" name="ancho_encontrado_vr" value="<?php echo $row_verificacion_rollos['ancho_encontrado_vr']; ?>" size="10" onBlur="vr_ancho()"></td>
              <td id="dato2"><input type="text" name="ancho_muestras_vr" value="<?php echo $row_verificacion_rollos['ancho_muestras_vr']; ?>" size="10" onBlur="vr_ancho()"></td>
              <td id="dato2"><input type="text" name="ancho_no_conforme_vr" value="<?php echo $row_verificacion_rollos['ancho_no_conforme_vr']; ?>" size="10" onBlur="vr_ancho()"></td>
              <td id="dato2"><select name="ancho_cumple_vr" id="ancho_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['ancho_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['ancho_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['ancho_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="ancho_observacion_vr" value="<?php echo $row_verificacion_rollos['ancho_observacion_vr']; ?>" size="30"></td>
            </tr>
            <tr id="tr2">
              <td colspan="2" id="dato1">Repeticion / Rodillo </td>
              <td id="dato2"><input type="text" name="rodillo_solicitado_vr" value="<?php
			  if($row_verificacion_rollos['rodillo_solicitado_vr']=='')
			  { echo $row_orden_compra_rollos['repeticion_rodillo_ocr']; } else { echo $row_verificacion_rollos['rodillo_solicitado_vr']; } ?>" size="10" onBlur="vr_rodillo()"></td>
              <td id="dato2"><input type="text" name="rodillo_encontrado_vr" value="<?php echo $row_verificacion_rollos['rodillo_encontrado_vr']; ?>" size="10" onBlur="vr_rodillo()"></td>
              <td id="dato2"><input type="text" name="rodillo_muestras_vr" value="<?php echo $row_verificacion_rollos['rodillo_muestras_vr']; ?>" size="10" onBlur="vr_rodillo()"></td>
              <td id="dato2"><input type="text" name="rodillo_no_conforme_vr" value="<?php echo $row_verificacion_rollos['rodillo_no_conforme_vr']; ?>" size="10" onBlur="vr_rodillo()"></td>
              <td id="dato2"><select name="rodillo_cumple_vr" id="rodillo_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['rodillo_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['rodillo_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['rodillo_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="rodillo_observacion_vr" value="<?php echo $row_verificacion_rollos['rodillo_observacion_vr']; ?>" size="30"></td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Tratamiento (Tension Superficial) </td>
              <td id="dato2"><input type="text" name="tratamiento_solicitado_vr" value="<?php
			  if($row_verificacion_rollos['tratamiento_solicitado_vr']=='')
			  { echo ">=38<=42 dynas/cm"; } else { echo $row_verificacion_rollos['tratamiento_solicitado_vr']; } ?>" size="10"></td>
              <td id="dato2"><input type="text" name="tratamiento_encontrado_vr" value="<?php echo $row_verificacion_rollos['tratamiento_encontrado_vr']; ?>" size="10" onBlur="vr_tratamiento()"></td>
              <td id="dato2"><input type="text" name="tratamiento_muestras_vr" value="<?php echo $row_verificacion_rollos['tratamiento_muestras_vr']; ?>" size="10" onBlur="vr_tratamiento()"></td>
              <td id="dato2"><input type="text" name="tratamiento_no_conforme_vr" value="<?php echo $row_verificacion_rollos['tratamiento_no_conforme_vr']; ?>" size="10" onBlur="vr_tratamiento()"></td>
              <td id="dato2"><select name="tratamiento_cumple_vr" id="tratamiento_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['tratamiento_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['tratamiento_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['tratamiento_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="tratamiento_observacion_vr" value="<?php echo $row_verificacion_rollos['tratamiento_observacion_vr']; ?>" size="30"></td>
            </tr>
            <tr id="tr2">
              <td colspan="2" id="dato1">Resistencia al Rasgado MD&gt;3g/mic * </td>
              <td id="dato2"><input type="text" name="md_solicitado_vr" value="<?php echo $row_verificacion_rollos['md_solicitado_vr']; ?>" size="10" onBlur="vr_md()"></td>
              <td id="dato2"><input type="text" name="md_encontrado_vr" value="<?php echo $row_verificacion_rollos['md_encontrado_vr']; ?>" size="10" onBlur="vr_md()"></td>
              <td id="dato2"><input type="text" name="md_muestras_vr" value="<?php echo $row_verificacion_rollos['md_muestras_vr']; ?>" size="10" onBlur="vr_md()"></td>
              <td id="dato2"><input type="text" name="md_no_conforme_vr" value="<?php echo $row_verificacion_rollos['md_no_conforme_vr']; ?>" size="10" onBlur="vr_md()"></td>
              <td id="dato2"><select name="md_cumple_vr" id="md_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['md_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['md_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['md_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="md_observacion_vr" value="<?php echo $row_verificacion_rollos['md_observacion_vr']; ?>" size="30"></td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Resistencia al Rasgado TD&gt;6g/mic * </td>
              <td id="dato2"><input type="text" name="td_solicitado_vr" value="<?php echo $row_verificacion_rollos['td_solicitado_vr']; ?>" size="10" onBlur="vr_td()"></td>
              <td id="dato2"><input type="text" name="td_encontrado_vr" value="<?php echo $row_verificacion_rollos['td_encontrado_vr']; ?>" size="10" onBlur="vr_td()"></td>
              <td id="dato2"><input type="text" name="td_muestras_vr" value="<?php echo $row_verificacion_rollos['td_muestras_vr']; ?>" size="10" onBlur="vr_td()"></td>
              <td id="dato2"><input type="text" name="td_no_conforme_vr" value="<?php echo $row_verificacion_rollos['td_no_conforme_vr']; ?>" size="10" onBlur="vr_td()"></td>
              <td id="dato2"><select name="td_cumple_vr" id="td_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['td_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['td_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['td_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="td_observacion_vr" value="<?php echo $row_verificacion_rollos['td_observacion_vr']; ?>" size="30"></td>
            </tr>
            <tr id="tr2">
              <td colspan="2" id="dato1">Angulo de Deslizamiento Min. 18&deg; * </td>
              <td id="dato2"><input type="text" name="angulo_solicitado_vr" value="<?php echo $row_verificacion_rollos['angulo_solicitado_vr']; ?>" size="10" onBlur="vr_angulo()"></td>
              <td id="dato2"><input type="text" name="angulo_encontrado_vr" value="<?php echo $row_verificacion_rollos['angulo_encontrado_vr']; ?>" size="10" onBlur="vr_angulo()"></td>
              <td id="dato2"><input type="text" name="angulo_muestras_vr" value="<?php echo $row_verificacion_rollos['angulo_muestras_vr']; ?>" size="10" onBlur="vr_angulo()"></td>
              <td id="dato2"><input type="text" name="angulo_no_conforme_vr" value="<?php echo $row_verificacion_rollos['angulo_no_conforme_vr']; ?>" size="10" onBlur="vr_angulo()"></td>
              <td id="dato2"><select name="angulo_cumple_vr" id="angulo_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['angulo_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['angulo_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['angulo_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="angulo_observacion_vr" value="<?php echo $row_verificacion_rollos['angulo_observacion_vr']; ?>" size="30"></td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Fuerza de Selle&gt;30g/mic * </td>
              <td id="dato2"><input type="text" name="fuerzaselle_solicitado_vr" value="<?php echo $row_verificacion_rollos['fuerzaselle_solicitado_vr']; ?>" size="10" onBlur="vr_fuerzaselle()"></td>
              <td id="dato2"><input type="text" name="fuerzaselle_encontrado_vr" value="<?php echo $row_verificacion_rollos['fuerzaselle_encontrado_vr']; ?>" size="10" onBlur="vr_fuerzaselle()"></td>
              <td id="dato2"><input type="text" name="fuerzaselle_muestras_vr" value="<?php echo $row_verificacion_rollos['fuerzaselle_muestras_vr']; ?>" size="10" onBlur="vr_fuerzaselle()"></td>
              <td id="dato2"><input type="text" name="fuerzaselle_no_conforme_vr" value="<?php echo $row_verificacion_rollos['fuerzaselle_no_conforme_vr']; ?>" size="10" onBlur="vr_fuerzaselle()"></td>
              <td id="dato2"><select name="fuerzaselle_cumple_vr" id="fuerzaselle_cumple_vr">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['fuerzaselle_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['fuerzaselle_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['fuerzaselle_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="fuerzaselle_observacion_vr" value="<?php echo $row_verificacion_rollos['fuerzaselle_observacion_vr']; ?>" size="30"></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td colspan="5" align="center"><table id="tabla3">
            <tr id="tr1">
              <td colspan="5" id="subtitulo1">II. PARAMETROS CUALITATIVOS</td>
              </tr>
            <tr>
              <td id="fuente2">VARIABLE</td>
              <td id="fuente2">CUMPLE</td>
              <td id="fuente2">MUESTRAS</td>
              <td id="fuente2">NO CONFORME </td>
              <td id="fuente2">OBSERVACION</td>
            </tr>
            <tr id="tr2">
              <td id="dato1">Apariencia</td>
              <td id="dato2"><select name="apariencia_cumple_vr" id="apariencia_cumple_vr" onBlur="vr_calificacion()">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['apariencia_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['apariencia_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0.5" <?php if (!(strcmp(0.5, $row_verificacion_rollos['apariencia_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['apariencia_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="apariencia_muestras_vr" value="<?php echo $row_verificacion_rollos['apariencia_muestras_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="apariencia_no_conforme_vr" value="<?php echo $row_verificacion_rollos['apariencia_no_conforme_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="apariencia_observacion_vr" value="<?php echo $row_verificacion_rollos['apariencia_observacion_vr']; ?>" size="40"></td>
            </tr>
            <tr>
              <td id="dato1">Sellabilidad y Resistencia </td>
              <td id="dato2"><select name="resistencia_sellos_cumple_vr" id="resistencia_sellos_cumple_vr" onBlur="vr_calificacion()">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['resistencia_sellos_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['resistencia_sellos_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0.5" <?php if (!(strcmp(0.5, $row_verificacion_rollos['resistencia_sellos_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['resistencia_sellos_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="resistencia_sellos_muestras_vr" value="<?php echo $row_verificacion_rollos['resistencia_sellos_muestras_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="resistencia_sellos_no_conforme_vr" value="<?php echo $row_verificacion_rollos['resistencia_sellos_no_conforme_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="resistencia_sellos_observacion_vr" value="<?php echo $row_verificacion_rollos['resistencia_sellos_observacion_vr']; ?>" size="40"></td>
            </tr>
            <tr id="tr2">
              <td id="dato1">Impresion - Concordancia en el arte </td>
              <td id="dato2"><select name="impresion_cumple_vr" id="impresion_cumple_vr" onBlur="vr_calificacion()">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['impresion_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['impresion_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0.5" <?php if (!(strcmp(0.5, $row_verificacion_rollos['impresion_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['impresion_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="impresion_muestras_vr" value="<?php echo $row_verificacion_rollos['impresion_muestras_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="impresion_no_conforme_vr" value="<?php echo $row_verificacion_rollos['impresion_no_conforme_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="impresion_observacion_vr" value="<?php echo $row_verificacion_rollos['impresion_observacion_vr']; ?>" size="40"></td>
            </tr>
            <tr>
              <td id="dato1">Color</td>
              <td id="dato2"><select name="color_cumple_vr" id="color_cumple_vr" onBlur="vr_calificacion()">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['color_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['color_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0.5" <?php if (!(strcmp(0.5, $row_verificacion_rollos['color_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['color_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="color_muestras_vr" value="<?php echo $row_verificacion_rollos['color_muestras_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="color_no_conforme_vr" value="<?php echo $row_verificacion_rollos['color_no_conforme_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="color_observacion_vr" value="<?php echo $row_verificacion_rollos['color_observacion_vr']; ?>" size="40"></td>
            </tr>
            <tr id="tr2">
              <td id="dato1">Adhesion de Tinta </td>
              <td id="dato2"><select name="tinta_cumple_vr" id="tinta_cumple_vr" onBlur="vr_calificacion()">
                <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['tinta_cumple_vr']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['tinta_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
                <option value="0.5" <?php if (!(strcmp(0.5, $row_verificacion_rollos['tinta_cumple_vr']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
                <option value="0" <?php if (!(strcmp(0, $row_verificacion_rollos['tinta_cumple_vr']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="tinta_muestras_vr" value="<?php echo $row_verificacion_rollos['tinta_muestras_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="tinta_no_conforme_vr" value="<?php echo $row_verificacion_rollos['tinta_no_conforme_vr']; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="tinta_observacion_vr" value="<?php echo $row_verificacion_rollos['tinta_observacion_vr']; ?>" size="40"></td>
            </tr>
          </table></td>
          </tr>
        <tr id="tr1">
          <td colspan="5" id="subtitulo1">OBSERVACIONES</td>
          </tr>
        <tr>
          <td colspan="4" rowspan="3" id="justificacion"><strong>( * ) </strong> De acuerdo a la Ficha Tecnica del Material. <br>
                  <strong>( ** ) </strong>De acuerdo a lo comparado con el certificado de calidad del lote recibido.<br>
                  <strong>Nota: </strong>Cada muestreo se realiza con base a lo establecido en la gu&iacute;a A3-G02. Plan de Inspecci&oacute;n de Materia Prima.</td>
          <td id="fuente2">SERVICIO</td>
        </tr>
        <tr>
          <td id="dato2"><select name="servicio_vr" id="servicio_vr">
              <option value="1" <?php if (!(strcmp(1, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>1</option>
              <option value="2" <?php if (!(strcmp(2, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>2</option>
              <option value="3" <?php if (!(strcmp(3, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>3</option>
              <option value="4" <?php if (!(strcmp(4, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>4</option>
              <option value="5" <?php if (!(strcmp(5, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>5</option>
              <option value="6" <?php if (!(strcmp(6, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>6</option>
              <option value="7" <?php if (!(strcmp(7, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>7</option>
              <option value="8" <?php if (!(strcmp(8, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>8</option>
              <option value="9" <?php if (!(strcmp(9, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>9</option>
              <option value="10" <?php if (!(strcmp(10, $row_verificacion_rollos['servicio_vr']))) {echo "selected=\"selected\"";} ?>>10</option>
          </select></td>
          </tr>
        <tr>
          <td id="justificacion">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="4" rowspan="3" id="fuente1"><strong>OTRAS OBSERVACIONES</strong><br>
            <textarea name="observaciones_vr" cols="70" rows="2"><?php echo $row_verificacion_rollos['observaciones_vr']; ?></textarea></td>
          <td id="fuente2">CALIFICACION %</td>
        </tr>
        <tr>
          <td id="dato2"><input type="text" name="calificacion_vr" value="<?php echo $row_verificacion_rollos['calificacion_vr']; ?>" size="10" onBlur="vr_calificacion()"></td>
          </tr>
        <tr>
          <td id="fuente1">&nbsp;</td>
        </tr>
        <tr id="tr1">
          <td id="fuente2">FECHA DE REGISTRO </td>
          <td id="fuente2">RESPONSABLE DEL REGISTRO </td>
          <td id="fuente2">FECHA MODIFICACION </td>
          <td colspan="2" id="fuente2">RESPONSABLE MODIFICACION </td>
        </tr>
        <tr>
          <td id="dato2"><input type="text" name="fecha_registro_vr" value="<?php echo $row_verificacion_rollos['fecha_registro_vr']; ?>" size="10"></td>
          <td id="dato2"><input type="text" name="responsable_registro_vr" value="<?php echo $row_verificacion_rollos['responsable_registro_vr']; ?>" size="30"></td>
          <td id="dato2">- <input type="hidden" name="fecha_modificacion_vr" value="<?php echo date("Y-m-d"); ?>" size="10"> <?php echo $row_verificacion_rollos['fecha_modificacion_vr']; ?> -</td>
          <td colspan="2" id="dato2">- <input name="responsable_modificacion_vr" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>"> 
          <?php echo $row_verificacion_rollos['responsable_modificacion_vr']; ?> -</td>
        </tr>
        <tr>
          <td colspan="5" id="dato2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="5" id="dato2"><input type="submit" value="Actualizar VERIFICACION"></td>
          </tr>
      </table>
      <input type="hidden" name="MM_update" value="form1">
      <input type="hidden" name="n_vr" value="<?php echo $row_verificacion_rollos['n_vr']; ?>">
    </form></td>
</tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($verificacion_rollos);

mysql_free_result($orden_compra_rollos);
?>
