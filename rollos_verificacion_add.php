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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
/*ACTUALIZA SALDO EN OCR*/
$n_ocr=$_POST['n_ocr_vr'];
$falta=$_POST['faltantes_vr'];
$sqlocr="UPDATE orden_compra_rollos SET saldo_verificacion_ocr='$falta' WHERE n_ocr='$n_ocr'";
/*ADD VERIFICACION*/
  $insertSQL = sprintf("INSERT INTO verificacion_rollos (n_vr, n_ocr_vr, id_p_vr, id_rollo_vr, id_ref_vr, fecha_recibo_vr, responsable_recibo_vr, entrega_vr, factura_vr, remision_vr, otro_recibo_vr, unidades_recibidas_vr, cantidad_solicitada_vr, faltantes_vr, cantidad_encontrada_vr, cantidad_muestras_vr, cantidad_no_conforme_vr, cantidad_cumple_vr, cantidad_observacion_vr, calibre_solicitado_vr, calibre_encontrado_vr, calibre_muestras_vr, calibre_no_conforme_vr, calibre_cumple_vr, calibre_observacion_vr, peso_solicitado_vr, peso_encontrado_vr, peso_muestras_vr, peso_no_conforme_vr, peso_cumple_vr, peso_observacion_vr, ancho_solicitado_vr, ancho_encontrado_vr, ancho_muestras_vr, ancho_no_conforme_vr, ancho_cumple_vr, ancho_observacion_vr, rodillo_solicitado_vr, rodillo_encontrado_vr, rodillo_muestras_vr, rodillo_no_conforme_vr, rodillo_cumple_vr, rodillo_observacion_vr, tratamiento_solicitado_vr, tratamiento_encontrado_vr, tratamiento_muestras_vr, tratamiento_no_conforme_vr, tratamiento_cumple_vr, tratamiento_observacion_vr, md_solicitado_vr, md_encontrado_vr, md_muestras_vr, md_no_conforme_vr, md_cumple_vr, md_observacion_vr, td_solicitado_vr, td_encontrado_vr, td_muestras_vr, td_no_conforme_vr, td_cumple_vr, td_observacion_vr, angulo_solicitado_vr, angulo_encontrado_vr, angulo_muestras_vr, angulo_no_conforme_vr, angulo_cumple_vr, angulo_observacion_vr, fuerzaselle_solicitado_vr, fuerzaselle_encontrado_vr, fuerzaselle_muestras_vr, fuerzaselle_no_conforme_vr, fuerzaselle_cumple_vr, fuerzaselle_observacion_vr, apariencia_cumple_vr, apariencia_muestras_vr, apariencia_no_conforme_vr, apariencia_observacion_vr, resistencia_sellos_cumple_vr, resistencia_sellos_muestras_vr, resistencia_sellos_no_conforme_vr, resistencia_sellos_observacion_vr, impresion_cumple_vr, impresion_muestras_vr, impresion_no_conforme_vr, impresion_observacion_vr, color_cumple_vr, color_muestras_vr, color_no_conforme_vr, color_observacion_vr, tinta_cumple_vr, tinta_muestras_vr, tinta_no_conforme_vr, tinta_observacion_vr, observaciones_vr, servicio_vr, calificacion_vr, fecha_registro_vr, responsable_registro_vr, fecha_modificacion_vr, responsable_modificacion_vr) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_vr'], "int"),
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
                       GetSQLValueString($_POST['responsable_modificacion_vr'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $resultocr=mysql_query($sqlocr);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "rollos_verificacion_vista.php?n_vr=" . $_POST['n_vr'] . "";
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

$colname_orden_compra_rollos = "-1";
if (isset($_GET['n_ocr'])) {
  $colname_orden_compra_rollos = (get_magic_quotes_gpc()) ? $_GET['n_ocr'] : addslashes($_GET['n_ocr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra_rollos = sprintf("SELECT * FROM orden_compra_rollos WHERE n_ocr = %s", $colname_orden_compra_rollos);
$orden_compra_rollos = mysql_query($query_orden_compra_rollos, $conexion1) or die(mysql_error());
$row_orden_compra_rollos = mysql_fetch_assoc($orden_compra_rollos);
$totalRows_orden_compra_rollos = mysql_num_rows($orden_compra_rollos);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM verificacion_rollos ORDER BY n_vr DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<table id="tabla3">
<tr><td align="center">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul></td></tr></table>  
  </td></tr>
  <tr><td id="linea1" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_recibo_vr','','R','responsable_recibo_vr','','R','n_ocr_vr','','R','unidades_recibidas_vr','','R','cantidad_solicitada_vr','','R','saldo_verificacion_ocr','','R','cantidad_encontrada_vr','','R','faltantes_vr','','R','fecha_registro_vr','','R','responsable_registro_vr','','R');return document.MM_returnValue">
        <table id="tabla3">
        <tr id="tr1">
          <td id="codigo" width="25%">CODIGO : A3 - F08 </td>
          <td colspan="2" id="titulo2" width="50%">VERIFICACION</td>
          <td width="25%" colspan="3" id="codigo">VERSION : 1</td>
        </tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td colspan="2" id="subtitulo">MATERIA PRIMA ( ROLLOS ) </td>
            <td colspan="2" id="dato2"><a href="rollos_oc_verificacion.php?n_ocr=<?php echo $row_verificacion_rollos['n_ocr_vr']; ?>" target="_top"><img src="images/v.gif" alt="VERIF X O.C." border="0" style="cursor:hand;"/></a><a href="rollos_oc.php" target="_top"><img src="images/o.gif" alt="O.C. (ROLLOS)" border="0" style="cursor:hand;"/></a><a href="rollos_verificacion.php" target="_top"><img src="images/cat.gif" alt="VERIFICACIONES (ROLLOS)" border="0" style="cursor:hand;"/></a><a href="rollos.php" target="_top"><img src="images/r.gif" alt="ROLLOS" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td colspan="2" id="numero2"><strong>N&deg; 
                <input name="n_vr" type="hidden" value="<?php $num=$row_ultimo['n_vr']+1; 
 echo $num; ?>"><?php echo $num; ?></strong></td>
            <td colspan="2" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td id="fuente2">FECHA DE RECIBO </td>
            <td id="fuente2">RECIBIDO POR </td>
            <td colspan="2" id="fuente2">ENTREGA</td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="fecha_recibo_vr" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
            <td id="dato2"><input type="text" name="responsable_recibo_vr" value="" size="20"></td>
            <td colspan="2" id="dato2"><select name="entrega_vr">
              <option value="0">PARCIAL</option>
              <option value="1">TOTAL</option>
                        </select></td>
          </tr>
          <tr>
            <td id="fuente2">FACTURA</td>
            <td id="fuente2">REMISION</td>
            <td colspan="2" id="fuente2">OTRO RECIBO</td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="factura_vr" value="" size="20"></td>
            <td id="dato2"><input type="text" name="remision_vr" value="" size="20"></td>
            <td colspan="2" id="dato2"><input type="text" name="otro_recibo_vr" value="" size="20"></td>
          </tr>
          <tr id="tr1">
            <td id="fuente2">ORDEN DE COMPRA </td>
            <td id="fuente2">PROVEEDOR</td>
            <td id="fuente2">MATERIAL SOLICITADO </td>
            <td id="fuente2">REF.</td>
            <td id="fuente2">N&deg; ROLLOS RECIBIDOS </td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="n_ocr_vr" value="<?php echo $row_orden_compra_rollos['n_ocr']; ?>" size="5"></td>
            <td id="dato2"><input name="id_p_vr" type="hidden" value="<?php echo $row_orden_compra_rollos['id_p_ocr']; ?>">
            <?php $id_p=$row_orden_compra_rollos['id_p_ocr'];			
		  if($id_p!='')
		  {
		  $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
		  $resultp= mysql_query($sqlp);
		  $nump= mysql_num_rows($resultp);
		  if($nump >='1') 
		  { 
		  $proveedor_p = mysql_result($resultp,0,'proveedor_p');
		  echo $proveedor_p; } } ?></td>
            <td id="dato2"><input name="id_rollo_vr" type="hidden" value="<?php echo $row_orden_compra_rollos['id_rollo_ocr']; ?>"><?php $id_rollo=$row_orden_compra_rollos['id_rollo_ocr'];  
		  if($id_rollo!='')
		  {
		  $sqlr="SELECT * FROM materia_prima_rollos WHERE id_rollo='$id_rollo'";
		  $resultr= mysql_query($sqlr);
		  $numr= mysql_num_rows($resultr);
		  if($numr >='1') 
		  { 
		  $nombre_rollo = mysql_result($resultr,0,'nombre_rollo');
		  echo $nombre_rollo; 
		  } } ?></td>
            <td id="dato2"><input name="id_ref_vr" type="hidden" value="<?php echo $row_orden_compra_rollos['id_ref_ocr']; ?>"><?php $id_ref=$row_orden_compra_rollos['id_ref_ocr'];
		  if($id_ref!='')
		  {
		  $sqlr="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
		  $resultr= mysql_query($sqlr);
		  $numr= mysql_num_rows($resultr);
		  if($numr >='1') 
		  { 
		  $cod_ref = mysql_result($resultr,0,'cod_ref');
		  echo $cod_ref; 
		  } } ?></td>
            <td id="dato2"><input type="text" name="unidades_recibidas_vr" value="" size="10"></td>
          </tr>
          <tr>
            <td colspan="5" align="center"><table id="tabla3">
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
              <td id="dato2"><input type="text" name="cantidad_solicitada_vr" value="<?php echo $row_orden_compra_rollos['cantidad_ocr']; ?>" size="10" onBlur="vr_cantidad()"></td>
              <td id="dato2"><input name="saldo_verificacion_ocr" type="text" id="saldo_verificacion_ocr" value="<?php echo $row_orden_compra_rollos['saldo_verificacion_ocr']; ?>" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><input type="text" name="cantidad_encontrada_vr" value="" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><input name="faltantes_vr" type="text" id="faltantes_vr" value="" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><input type="text" name="cantidad_muestras_vr" value="" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><input type="text" name="cantidad_no_conforme_vr" value="" size="10" onBlur="vr_cantidad()"></td>
              <td id="fuente2"><select name="cantidad_cumple_vr" id="cantidad_cumple_vr">
                <option value="2">N.A.</option>
                <option value="1">Cumple</option>
                <option value="0">No cumple</option>
              </select></td>
              <td id="fuente2"><input type="text" name="cantidad_observacion_vr" value="" size="30"></td>
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
              <td id="dato2"><input type="text" name="calibre_solicitado_vr" value="<?php echo $row_orden_compra_rollos['calibre_micras_ocr']; ?>" size="10" onBlur="vr_calibre()"></td>
              <td id="dato2"><input type="text" name="calibre_encontrado_vr" value="" size="10" onBlur="vr_calibre()"></td>
              <td id="dato2"><input type="text" name="calibre_muestras_vr" value="" size="10" onBlur="vr_calibre()"></td>
              <td id="dato2"><input type="text" name="calibre_no_conforme_vr" value="" size="10" onBlur="vr_calibre()"></td>
              <td id="dato2"><select name="calibre_cumple_vr">
                <option value="2">N.A.</option>
                <option value="1">Cumple</option>
                <option value="0">No cumple</option>
              </select></td>
              <td id="dato2"><input type="text" name="calibre_observacion_vr" value="" size="30"></td>
            </tr>
            <tr id="tr2">
              <td colspan="2" id="dato1">Peso (kg) </td>
              <td id="dato2"><input type="text" name="peso_solicitado_vr" value="" size="10" onBlur="vr_peso()"></td>
              <td id="dato2"><input type="text" name="peso_encontrado_vr" value="" size="10" onBlur="vr_peso()"></td>
              <td id="dato2"><input type="text" name="peso_muestras_vr" value="" size="10" onBlur="vr_peso()"></td>
              <td id="dato2"><input type="text" name="peso_no_conforme_vr" value="" size="10" onBlur="vr_peso()"></td>
              <td id="dato2"><select name="peso_cumple_vr">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0">No cumple</option>
                        </select></td>
              <td id="dato2"><input type="text" name="peso_observacion_vr" value="" size="30"></td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Ancho del Rollo (cm) </td>
              <td id="dato2"><input type="text" name="ancho_solicitado_vr" value="<?php echo $row_orden_compra_rollos['ancho_material_ocr']; ?>" size="10" onBlur="vr_ancho()"></td>
              <td id="dato2"><input type="text" name="ancho_encontrado_vr" value="" size="10" onBlur="vr_ancho()"></td>
              <td id="dato2"><input type="text" name="ancho_muestras_vr" value="" size="10" onBlur="vr_ancho()"></td>
              <td id="dato2"><input type="text" name="ancho_no_conforme_vr" value="" size="10" onBlur="vr_ancho()"></td>
              <td id="dato2"><select name="ancho_cumple_vr">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0">No cumple</option>
                        </select></td>
              <td id="dato2"><input type="text" name="ancho_observacion_vr" value="" size="30"></td>
            </tr>
            <tr id="tr2">
              <td colspan="2" id="dato1">Repeticion / Rodillo </td>
              <td id="dato2"><input type="text" name="rodillo_solicitado_vr" value="<?php echo $row_orden_compra_rollos['repeticion_rodillo_ocr']; ?>" size="10" onBlur="vr_rodillo()"></td>
              <td id="dato2"><input type="text" name="rodillo_encontrado_vr" value="" size="10" onBlur="vr_rodillo()"></td>
              <td id="dato2"><input type="text" name="rodillo_muestras_vr" value="" size="10" onBlur="vr_rodillo()"></td>
              <td id="dato2"><input type="text" name="rodillo_no_conforme_vr" value="" size="10" onBlur="vr_rodillo()"></td>
              <td id="dato2"><select name="rodillo_cumple_vr">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0">No cumple</option>
                        </select></td>
              <td id="dato2"><input type="text" name="rodillo_observacion_vr" value="" size="30"></td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Tratamiento (Tension Superficial) </td>
              <td id="dato2"><input type="text" name="tratamiento_solicitado_vr" value="<?php echo ">=38<=42 dynas/cm"; ?>" size="10"></td>
              <td id="dato2"><input type="text" name="tratamiento_encontrado_vr" value="" size="10" onBlur="vr_tratamiento()"></td>
              <td id="dato2"><input type="text" name="tratamiento_muestras_vr" value="" size="10" onBlur="vr_tratamiento()"></td>
              <td id="dato2"><input type="text" name="tratamiento_no_conforme_vr" value="" size="10" onBlur="vr_tratamiento()"></td>
              <td id="dato2"><select name="tratamiento_cumple_vr">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0">No cumple</option>
                        </select></td>
              <td id="dato2"><input type="text" name="tratamiento_observacion_vr" value="" size="30"></td>
            </tr>
            <tr id="tr2">
              <td colspan="2" id="dato1">Resistencia al Rasgado MD&gt;3g/mic * </td>
              <td id="dato2"><input type="text" name="md_solicitado_vr" value="" size="10" onBlur="vr_md()"></td>
              <td id="dato2"><input type="text" name="md_encontrado_vr" value="" size="10" onBlur="vr_md()"></td>
              <td id="dato2"><input type="text" name="md_muestras_vr" value="" size="10" onBlur="vr_md()"></td>
              <td id="dato2"><input type="text" name="md_no_conforme_vr" value="" size="10" onBlur="vr_md()"></td>
              <td id="dato2"><select name="md_cumple_vr">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0">No cumple</option>
                        </select></td>
              <td id="dato2"><input type="text" name="md_observacion_vr" value="" size="30"></td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Resistencia al Rasgado TD&gt;6g/mic * </td>
              <td id="dato2"><input type="text" name="td_solicitado_vr" value="" size="10" onBlur="vr_td()"></td>
              <td id="dato2"><input type="text" name="td_encontrado_vr" value="" size="10" onBlur="vr_td()"></td>
              <td id="dato2"><input type="text" name="td_muestras_vr" value="" size="10" onBlur="vr_td()"></td>
              <td id="dato2"><input type="text" name="td_no_conforme_vr" value="" size="10" onBlur="vr_td()"></td>
              <td id="dato2"><select name="td_cumple_vr">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0">No cumple</option>
                        </select></td>
              <td id="dato2"><input type="text" name="td_observacion_vr" value="" size="30"></td>
            </tr>
            <tr id="tr2">
              <td colspan="2" id="dato1">Angulo de Deslizamiento Min. 18&deg; * </td>
              <td id="dato2"><input type="text" name="angulo_solicitado_vr" value="" size="10" onBlur="vr_angulo()"></td>
              <td id="dato2"><input type="text" name="angulo_encontrado_vr" value="" size="10" onBlur="vr_angulo()"></td>
              <td id="dato2"><input type="text" name="angulo_muestras_vr" value="" size="10" onBlur="vr_angulo()"></td>
              <td id="dato2"><input type="text" name="angulo_no_conforme_vr" value="" size="10" onBlur="vr_angulo()"></td>
              <td id="dato2"><select name="angulo_cumple_vr">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0">No cumple</option>
                        </select></td>
              <td id="dato2"><input type="text" name="angulo_observacion_vr" value="" size="30"></td>
            </tr>
            <tr>
              <td colspan="2" id="dato1">Fuerza de Selle&gt;30g/mic * </td>
              <td id="dato2"><input type="text" name="fuerzaselle_solicitado_vr" value="" size="10" onBlur="vr_fuerzaselle()"></td>
              <td id="dato2"><input type="text" name="fuerzaselle_encontrado_vr" value="" size="10" onBlur="vr_fuerzaselle()"></td>
              <td id="dato2"><input type="text" name="fuerzaselle_muestras_vr" value="" size="10" onBlur="vr_fuerzaselle()"></td>
              <td id="dato2"><input type="text" name="fuerzaselle_no_conforme_vr" value="" size="10" onBlur="vr_fuerzaselle()"></td>
              <td id="dato2"><select name="fuerzaselle_cumple_vr">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0">No cumple</option>
                        </select></td>
              <td id="dato2"><input type="text" name="fuerzaselle_observacion_vr" value="" size="30"></td>
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
              <td id="dato2"><select name="apariencia_cumple_vr" onBlur="vr_calificacion()">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0.5">Parcial</option>
                      <option value="0">No cumple</option>
                    </select></td>
              <td id="dato2"><input type="text" name="apariencia_muestras_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="apariencia_no_conforme_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="apariencia_observacion_vr" value="" size="40"></td>
            </tr>
            <tr>
              <td id="dato1">Sellabilidad y Resistencia </td>
              <td id="dato2"><select name="resistencia_sellos_cumple_vr" onBlur="vr_calificacion()">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0.5">Parcial</option>
                      <option value="0">No cumple</option>
                    </select></td>
              <td id="dato2"><input type="text" name="resistencia_sellos_muestras_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="resistencia_sellos_no_conforme_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="resistencia_sellos_observacion_vr" value="" size="40"></td>
            </tr>
            <tr id="tr2">
              <td id="dato1">Impresion - Concordancia en el arte </td>
              <td id="dato2"><select name="impresion_cumple_vr" onBlur="vr_calificacion()">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0.5">Parcial</option>
                      <option value="0">No cumple</option>
                    </select></td>
              <td id="dato2"><input type="text" name="impresion_muestras_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="impresion_no_conforme_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="impresion_observacion_vr" value="" size="40"></td>
            </tr>
            <tr>
              <td id="dato1">Color</td>
              <td id="dato2"><select name="color_cumple_vr" onBlur="vr_calificacion()">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0.5">Parcial</option>
                      <option value="0">No cumple</option>
                    </select></td>
              <td id="dato2"><input type="text" name="color_muestras_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="color_no_conforme_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="color_observacion_vr" value="" size="40"></td>
            </tr>
            <tr id="tr2">
              <td id="dato1">Adhesion de Tinta </td>
              <td id="dato2"><select name="tinta_cumple_vr"onBlur="vr_calificacion()">
                      <option value="2">N.A.</option>
                      <option value="1">Cumple</option>
                      <option value="0.5">Parcial</option>
                      <option value="0">No cumple</option>
                    </select></td>
              <td id="dato2"><input type="text" name="tinta_muestras_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="tinta_no_conforme_vr" value="" size="10"></td>
              <td id="dato2"><input type="text" name="tinta_observacion_vr" value="" size="40"></td>
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
            <td id="dato2"><select name="servicio_vr">
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
          </tr>
          <tr>
            <td id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" rowspan="3" id="fuente1"><strong>OTRAS OBSERVACIONES</strong><br>
			  <textarea name="observaciones_vr" cols="70" rows="2"></textarea>
            <td id="fuente2">CALIFICACION % </td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="calificacion_vr" value="" size="10" onBlur="vr_calificacion()"></td>
          </tr>
          <tr>
            <td id="dato2">&nbsp;</td>
          </tr>
          <tr id="tr1">
            <td id="fuente2">FECHA DE REGISTRO </td>
            <td id="fuente2">RESPONSABLE DEL REGISTRO </td>
            <td id="fuente2">FECHA MODIFICACION </td>
            <td colspan="2" id="fuente2">RESPONSABLE MODIFICACION </td>
          </tr>
          <tr>
            <td id="dato2"><input type="text" name="fecha_registro_vr" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
            <td id="dato2"><input type="text" name="responsable_registro_vr" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30"></td>
            <td id="dato2"><input type="text" name="fecha_modificacion_vr" value="" size="10"></td>
            <td colspan="2" id="dato2"><input type="text" name="responsable_modificacion_vr" value="" size="30"></td>
          </tr>
          <tr>
            <td colspan="5" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="5" id="dato2"><input name="submit" type="submit" value="ADD VERIFICACION"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form></td></tr>
</table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($orden_compra_rollos);

mysql_free_result($ultimo);
?>