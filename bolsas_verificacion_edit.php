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
/*ACTUALIZA SALDO EN OCB*/
$n_ocb=$_POST['n_ocb_vb'];
$falta=$_POST['faltantes_vb'];
$sqlocb="UPDATE orden_compra_bolsas SET saldo_verificacion_ocb='$falta' WHERE n_ocb='$n_ocb'";
/*ACTUALIZA LA VERIFICACION*/
  $updateSQL = sprintf("UPDATE verificacion_bolsas SET n_ocb_vb=%s, id_p_vb=%s, id_bolsa_vb=%s, id_ref_vb=%s, fecha_recibido_vb=%s, responsable_recibido_vb=%s, entrega_vb=%s, factura_vb=%s, remision_vb=%s, otro_recibo_vb=%s, paquetes_recibidos_vb=%s, cantidad_solicitada_vb=%s, faltantes_vb=%s, cantidad_encontrada_vb=%s, cantidad_muestras_vb=%s, cantidad_no_conforme_vb=%s, cantidad_cumple_vb=%s, cantidad_observacion_vb=%s, calibre_solicitado_vb=%s, calibre_encontrado_vb=%s, calibre_muestras_vb=%s, calibre_no_conforme_vb=%s, calibre_cumple_vb=%s, calibre_observacion_vb=%s, ancho_solicitado_vb=%s, ancho_encontrado_vb=%s, ancho_muestras_vb=%s, ancho_no_conforme_vb=%s, ancho_cumple_vb=%s, ancho_observacion_vb=%s, largo_solicitado_vb=%s, largo_encontrado_vb=%s, largo_muestras_vb=%s, largo_no_conforme_vb=%s, largo_cumple_vb=%s, largo_observacion_vb=%s, solapa_solicitada_vb=%s, solapa_encontrada_vb=%s, solapa_muestras_vb=%s, solapa_no_conforme_vb=%s, solapa_cumple_vb=%s, solapa_observacion_vb=%s, fuelle_solicitado_vb=%s, fuelle_encontrado_vb=%s, fuelle_muestras_vb=%s, fuelle_no_conforme_vb=%s, fuelle_cumple_vb=%s, fuelle_observacion_vb=%s, empaque_cumple_vb=%s, empaque_muestras_vb=%s, empaque_no_conforme_vb=%s, empaque_observacion_vb=%s, apariencia_cumple_vb=%s, apariencia_muestras_vb=%s, apariencia_no_conforme_vb=%s, apariencia_observacion_vb=%s, resistencia_cumple_vb=%s, resistencia_muestras_vb=%s, resistencia_no_conforme_vb=%s, resistencia_observacion_vb=%s, tratamiento_cumple_vb=%s, tratamiento_muestras_vb=%s, tratamiento_no_conforme_vb=%s, tratamiento_observacion_vb=%s, servicio_vb=%s, calificacion_vb=%s, observaciones_vb=%s, fecha_registro_vb=%s, responsable_registro_vb=%s, fecha_modificacion_vb=%s, responsable_modificacion_vb=%s WHERE n_vb=%s",
                       GetSQLValueString($_POST['n_ocb_vb'], "int"),
                       GetSQLValueString($_POST['id_p_vb'], "int"),
                       GetSQLValueString($_POST['id_bolsa_vb'], "int"),
                       GetSQLValueString($_POST['id_ref_vb'], "int"),
                       GetSQLValueString($_POST['fecha_recibido_vb'], "date"),
                       GetSQLValueString($_POST['responsable_recibido_vb'], "text"),
                       GetSQLValueString($_POST['entrega_vb'], "int"),
                       GetSQLValueString($_POST['factura_vb'], "text"),
                       GetSQLValueString($_POST['remision_vb'], "text"),
                       GetSQLValueString($_POST['otro_recibo_vb'], "text"),
                       GetSQLValueString($_POST['paquetes_recibidos_vb'], "int"),
                       GetSQLValueString($_POST['cantidad_solicitada_vb'], "int"),
                       GetSQLValueString($_POST['faltantes_vb'], "int"),
                       GetSQLValueString($_POST['cantidad_encontrada_vb'], "int"),
                       GetSQLValueString($_POST['cantidad_muestras_vb'], "int"),
                       GetSQLValueString($_POST['cantidad_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['cantidad_cumple_vb'], "text"),
                       GetSQLValueString($_POST['cantidad_observacion_vb'], "text"),
                       GetSQLValueString($_POST['calibre_solicitado_vb'], "double"),
                       GetSQLValueString($_POST['calibre_encontrado_vb'], "double"),
                       GetSQLValueString($_POST['calibre_muestras_vb'], "int"),
                       GetSQLValueString($_POST['calibre_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['calibre_cumple_vb'], "text"),
                       GetSQLValueString($_POST['calibre_observacion_vb'], "text"),
                       GetSQLValueString($_POST['ancho_solicitado_vb'], "double"),
                       GetSQLValueString($_POST['ancho_encontrado_vb'], "double"),
                       GetSQLValueString($_POST['ancho_muestras_vb'], "int"),
                       GetSQLValueString($_POST['ancho_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['ancho_cumple_vb'], "text"),
                       GetSQLValueString($_POST['ancho_observacion_vb'], "text"),
                       GetSQLValueString($_POST['largo_solicitado_vb'], "double"),
                       GetSQLValueString($_POST['largo_encontrado_vb'], "double"),
                       GetSQLValueString($_POST['largo_muestras_vb'], "int"),
                       GetSQLValueString($_POST['largo_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['largo_cumple_vb'], "text"),
                       GetSQLValueString($_POST['largo_observacion_vb'], "text"),
                       GetSQLValueString($_POST['solapa_solicitada_vb'], "double"),
                       GetSQLValueString($_POST['solapa_encontrada_vb'], "double"),
                       GetSQLValueString($_POST['solapa_muestras_vb'], "int"),
                       GetSQLValueString($_POST['solapa_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['solapa_cumple_vb'], "text"),
                       GetSQLValueString($_POST['solapa_observacion_vb'], "text"),
                       GetSQLValueString($_POST['fuelle_solicitado_vb'], "double"),
                       GetSQLValueString($_POST['fuelle_encontrado_vb'], "double"),
                       GetSQLValueString($_POST['fuelle_muestras_vb'], "int"),
                       GetSQLValueString($_POST['fuelle_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['fuelle_cumple_vb'], "text"),
                       GetSQLValueString($_POST['fuelle_observacion_vb'], "text"),
                       GetSQLValueString($_POST['empaque_cumple_vb'], "text"),
                       GetSQLValueString($_POST['empaque_muestras_vb'], "int"),
                       GetSQLValueString($_POST['empaque_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['empaque_observacion_vb'], "text"),
                       GetSQLValueString($_POST['apariencia_cumple_vb'], "text"),
                       GetSQLValueString($_POST['apariencia_muestras_vb'], "int"),
                       GetSQLValueString($_POST['apariencia_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['apariencia_observacion_vb'], "text"),
                       GetSQLValueString($_POST['resistencia_cumple_vb'], "text"),
                       GetSQLValueString($_POST['resistencia_muestras_vb'], "int"),
                       GetSQLValueString($_POST['resistencia_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['resistencia_observacion_vb'], "text"),
                       GetSQLValueString($_POST['tratamiento_cumple_vb'], "text"),
                       GetSQLValueString($_POST['tratamiento_muestras_vb'], "int"),
                       GetSQLValueString($_POST['tratamiento_no_conforme_vb'], "int"),
                       GetSQLValueString($_POST['tratamiento_observacion_vb'], "text"),
                       GetSQLValueString($_POST['servicio_vb'], "int"),
                       GetSQLValueString($_POST['calificacion_vb'], "double"),
                       GetSQLValueString($_POST['observaciones_vb'], "text"),
                       GetSQLValueString($_POST['fecha_registro_vb'], "date"),
                       GetSQLValueString($_POST['responsable_registro_vb'], "text"),
                       GetSQLValueString($_POST['fecha_modificacion_vb'], "date"),
                       GetSQLValueString($_POST['responsable_modificacion_vb'], "text"),
                       GetSQLValueString($_POST['n_vb'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $resultocb=mysql_query($sqlocb);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "bolsas_verificacion_vista.php?n_vb=" . $_POST['n_vb'] . "";
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

$colname_bolsa_verificacion = "-1";
if (isset($_GET['n_vb'])) {
  $colname_bolsa_verificacion = (get_magic_quotes_gpc()) ? $_GET['n_vb'] : addslashes($_GET['n_vb']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa_verificacion = sprintf("SELECT * FROM verificacion_bolsas WHERE n_vb = %s", $colname_bolsa_verificacion);
$bolsa_verificacion = mysql_query($query_bolsa_verificacion, $conexion1) or die(mysql_error());
$row_bolsa_verificacion = mysql_fetch_assoc($bolsa_verificacion);
$totalRows_bolsa_verificacion = mysql_num_rows($bolsa_verificacion);

$colname_bolsa_oc = "-1";
if (isset($_GET['n_vb'])) {
  $colname_bolsa_oc = (get_magic_quotes_gpc()) ? $_GET['n_vb'] : addslashes($_GET['n_vb']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa_oc = sprintf("SELECT * FROM verificacion_bolsas, orden_compra_bolsas WHERE verificacion_bolsas.n_vb = '%s'  AND  verificacion_bolsas.n_ocb_vb = orden_compra_bolsas.n_ocb", $colname_bolsa_oc);
$bolsa_oc = mysql_query($query_bolsa_oc, $conexion1) or die(mysql_error());
$row_bolsa_oc = mysql_fetch_assoc($bolsa_oc);
$totalRows_bolsa_oc = mysql_num_rows($bolsa_oc);
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
  </ul></td>
  </tr>
  </table></td></tr>
  <tr><td id="linea1" align="center">
  <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_recibido_vb','','R','responsable_recibido_vb','','R','n_ocb_vb','','R','paquetes_recibidos_vb','','R','cantidad_solicitada_vb','','R','saldo_verificacion_ocb','','R','cantidad_encontrada_vb','','R','faltantes_vb','','R','calificacion_vb','','R','fecha_registro_vb','','R','responsable_registro_vb','','R','fecha_modificacion_vb','','R','responsable_modificacion_vb','','R');return document.MM_returnValue">
    <table id="tabla3">
      <tr id="tr1">
          <td id="codigo" width="25%">CODIGO : A3 - F08 </td>
          <td colspan="2" id="titulo2" width="50%">VERIFICACION</td>
          <td width="25%" colspan="3" id="codigo">VERSION : 1 </td>
        </tr>
      <tr>
        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
        <td colspan="2" id="subtitulo">PRODUCTO TERMINADO (BOLSAS)</td>
        <td colspan="3" id="dato2"><a href="bolsas_verificacion_vista.php?n_vb=<?php echo $row_bolsa_verificacion['n_vb']; ?>" target="_top"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('n_vb',<?php echo $row_bolsa_verificacion['n_vb']; ?>,'bolsas_verificacion_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a><a href="bolsas_oc_verificacion.php?n_ocb=<?php echo $row_bolsa_verificacion['n_ocb_vb']; ?>" target="_top"><img src="images/v.gif" alt="VERIF. X O.C." border="0" style="cursor:hand;"/></a><a href="bolsas_oc.php" target="_top"><img src="images/o.gif" alt="O.C. (BOLSAS)" border="0" style="cursor:hand;"/></a><a href="bolsas_verificacion.php" target="_top"><img src="images/cat.gif" alt="VERIFICACIONES (BOLSAS)" border="0" style="cursor:hand;"/></a><a href="bolsas.php" target="_top"><img src="images/b.gif" alt="BOLSAS" border="0" style="cursor:hand;"/></a></td>
      </tr>
      <tr>
        <td colspan="2" id="numero2"><strong>N° <?php echo $row_bolsa_verificacion['n_vb']; ?></strong></td>
        <td colspan="2" id="dato2">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente2">FECHA DE RECIBO</td>
        <td id="fuente2">RECIBIDO POR</td>
        <td colspan="2" id="fuente2">ENTREGA</td>
      </tr>
      <tr>
        <td id="dato2"><input type="text" name="fecha_recibido_vb" value="<?php echo $row_bolsa_verificacion['fecha_recibido_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="responsable_recibido_vb" value="<?php echo $row_bolsa_verificacion['responsable_recibido_vb']; ?>" size="20"></td>
        <td colspan="2" id="dato2"><select name="entrega_vb" id="entrega_vb" onBlur="vb_calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['entrega_vb']))) {echo "selected=\"selected\"";} ?>>PARCIAL</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['entrega_vb']))) {echo "selected=\"selected\"";} ?>>TOTAL</option>
        </select></td>
      </tr>
      <tr>
        <td id="fuente2">FACTURA</td>
        <td id="fuente2">REMISION</td>
        <td colspan="2" id="fuente2">OTRO RECIBO </td>
        </tr>
      <tr>
        <td id="dato2"><input type="text" name="factura_vb" value="<?php echo $row_bolsa_verificacion['factura_vb']; ?>" size="20"></td>
        <td id="dato2"><input type="text" name="remision_vb" value="<?php echo $row_bolsa_verificacion['remision_vb']; ?>" size="20"></td>
        <td colspan="2" id="dato2"><input type="text" name="otro_recibo_vb" value="<?php echo $row_bolsa_verificacion['otro_recibo_vb']; ?>" size="20"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente2">ORDEN DE COMPRA</td>
        <td id="fuente2">PROVEEDOR</td>
        <td id="fuente2">MATERIAL SOLICITADO</td>
        <td id="fuente2">REF.</td>
        <td id="fuente2">PAQUETES RECIBIDOS</td>
        </tr>
      <tr>
        <td id="dato2"><input type="text" name="n_ocb_vb" value="<?php echo $row_bolsa_verificacion['n_ocb_vb']; ?>" size="5"></td>
        <td id="dato2"><?php $id_p=$row_bolsa_verificacion['id_p_vb'];
		  if($id_p == '') {  $id_p=$row_bolsa_oc['id_p_ocb']; }
		  if($id_p != '') {  $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
		  $resultp= mysql_query($sqlp);
		  $nump= mysql_num_rows($resultp);
		  if($nump >='1') { 
		  $proveedor_p = mysql_result($resultp,0,'proveedor_p');
		  echo $proveedor_p; } } ?>
          <input name="id_p_vb" type="hidden" value="<?php echo $id_p; ?>"></td>
        <td id="dato2"><?php $id_bolsa=$row_bolsa_verificacion['id_bolsa_vb'];
		  if($id_bolsa=='') 
		  {  
		  $id_bolsa=$row_bolsa_oc['id_bolsa_ocb']; 
		  }
		  if($id_bolsa!='')
		  {
		  $sqlb="SELECT * FROM material_terminado_bolsas WHERE id_bolsa='$id_bolsa'";
		  $resultb= mysql_query($sqlb);
		  $numb= mysql_num_rows($resultb);
		  if($numb >='1') { $nombre_bolsa = mysql_result($resultb,0,'nombre_bolsa');
		   echo $nombre_bolsa; } } ?><input name="id_bolsa_vb" type="hidden" value="<?php echo $id_bolsa; ?>"></td>
        <td id="dato2"><?php $id_ref=$row_bolsa_verificacion['id_ref_vb'];
		  if($id_ref=='') { $id_ref=$row_bolsa_oc['id_ref_ocb']; }
		  if($id_ref!='') { $sqlr="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
		  $resultr= mysql_query($sqlr);
		  $numr= mysql_num_rows($resultr);
		  if($numr >='1') 
		  { $cod_ref = mysql_result($resultr,0,'cod_ref');
		  echo $cod_ref; } } ?><input name="id_ref_vb" type="hidden" value="<?php echo $id_ref; ?>"></td>
        <td id="dato2"><input type="text" name="paquetes_recibidos_vb" value="<?php echo $row_bolsa_verificacion['paquetes_recibidos_vb']; ?>" size="10"></td>
      </tr></table>
	  <table id="tabla3">
	  <tr id="tr1">
	  <td colspan="8" id="subtitulo1">I. PARAMETROS CUANTITATIVOS </td>
	  </tr>
	  <tr><td colspan="8" id="dato1">Variable de <strong>CANTIDAD</strong> Kg (Si lo recibido es menor a lo pedido, habran faltantes para una futura entrega con su respectiva verificacion) </td>
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
        <td id="dato2"><input type="text" name="cantidad_solicitada_vb" value="<?php if($row_bolsa_verificacion['cantidad_solicitada_vb']=='') { echo $row_bolsa_oc['cantidad_ocb']; }
			  else { echo $row_bolsa_verificacion['cantidad_solicitada_vb']; } ?>" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input name="saldo_verificacion_ocb" type="text" id="saldo_verificacion_ocb" value="<?php echo $row_bolsa_oc['saldo_verificacion_ocb']; ?>" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input type="text" name="cantidad_encontrada_vb" value="<?php echo $row_bolsa_verificacion['cantidad_encontrada_vb']; ?>" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input type="text" name="faltantes_vb" value="<?php echo $row_bolsa_verificacion['faltantes_vb']; ?>" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input type="text" name="cantidad_muestras_vb" value="<?php echo $row_bolsa_verificacion['cantidad_muestras_vb']; ?>" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input type="text" name="cantidad_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['cantidad_no_conforme_vb']; ?>" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><select name="cantidad_cumple_vb" id="cantidad_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['cantidad_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['cantidad_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
          <option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['cantidad_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="cantidad_observacion_vb" value="<?php echo $row_bolsa_verificacion['cantidad_observacion_vb']; ?>" size="30"></td>
      </tr>
	  </table>
	  <table id="tabla3">
	   <tr id="tr1">
	     <td id="subtitulo2">OTRAS VARIABLES </td>
	     <td id="subtitulo2">SOLICITADO</td>
	     <td id="subtitulo2">ENCONTRADO</td>
	     <td id="subtitulo2">MUESTRAS</td>
	     <td id="subtitulo2">NO CONFORME </td>
	  	<td id="subtitulo2">CUMPLE </td>
		<td id="subtitulo2">OBSERVACION</td>
	   </tr>
	   <tr>
	     <td id="dato1">Calibre (micras) </td>
	     <td id="dato2"><input type="text" name="calibre_solicitado_vb" value="<?php if($row_bolsa_verificacion['calibre_solicitado_vb']=='') { echo $row_bolsa_oc['calibre_micras_ocb']; } else { echo $row_bolsa_verificacion['calibre_solicitado_vb']; } ?>" size="10" onBlur="vb_calibre()"></td>
	     <td id="dato2"><input type="text" name="calibre_encontrado_vb" value="<?php echo $row_bolsa_verificacion['calibre_encontrado_vb']; ?>" size="10" onBlur="vb_calibre()"></td>
	     <td id="dato2"><input type="text" name="calibre_muestras_vb" value="<?php echo $row_bolsa_verificacion['calibre_muestras_vb']; ?>" size="10" onBlur="vb_calibre()"></td>
	     <td id="dato2"><input type="text" name="calibre_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['calibre_no_conforme_vb']; ?>" size="10" onBlur="vb_calibre()"></td>
	     <td id="dato2"><select name="calibre_cumple_vb" id="calibre_cumple_vb" onBlur="vb_calificacion()">
	       <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['calibre_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
	       <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['calibre_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['calibre_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
         </select></td>
		 <td id="dato2"><input type="text" name="calibre_observacion_vb" value="<?php echo $row_bolsa_verificacion['calibre_observacion_vb']; ?>" size="30"></td>
      </tr>
      <tr id="tr2">
        <td id="dato1">Ancho (cm) </td>
        <td id="dato2"><input type="text" name="ancho_solicitado_vb" value="<?php if($row_bolsa_verificacion['ancho_solicitado_vb']=='') { echo $row_bolsa_oc['ancho_ocb']; } else { echo $row_bolsa_verificacion['ancho_solicitado_vb']; } ?>" size="10" onBlur="vb_ancho()"></td>
        <td id="dato2"><input type="text" name="ancho_encontrado_vb" value="<?php echo $row_bolsa_verificacion['ancho_encontrado_vb']; ?>" size="10" onBlur="vb_ancho()"></td>
        <td id="dato2"><input type="text" name="ancho_muestras_vb" value="<?php echo $row_bolsa_verificacion['ancho_muestras_vb']; ?>" size="10" onBlur="vb_ancho()"></td>
        <td id="dato2"><input type="text" name="ancho_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['ancho_no_conforme_vb']; ?>" size="10" onBlur="vb_ancho()"></td>
        <td id="dato2"><select name="ancho_cumple_vb" id="ancho_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['ancho_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['ancho_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['ancho_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="ancho_observacion_vb" value="<?php echo $row_bolsa_verificacion['ancho_observacion_vb']; ?>" size="30"></td>
      </tr>
      <tr>
        <td id="dato1">Largo (cm) </td>
        <td id="dato2"><input type="text" name="largo_solicitado_vb" value="<?php if($row_bolsa_verificacion['largo_solicitado_vb']=='') { echo $row_bolsa_oc['largo_ocb']; } else { echo $row_bolsa_verificacion['largo_solicitado_vb']; } ?>" size="10" onBlur="vb_largo()"></td>
        <td id="dato2"><input type="text" name="largo_encontrado_vb" value="<?php echo $row_bolsa_verificacion['largo_encontrado_vb']; ?>" size="10" onBlur="vb_largo()"></td>
        <td id="dato2"><input type="text" name="largo_muestras_vb" value="<?php echo $row_bolsa_verificacion['largo_muestras_vb']; ?>" size="10" onBlur="vb_largo()"></td>
        <td id="dato2"><input type="text" name="largo_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['largo_no_conforme_vb']; ?>" size="10" onBlur="vb_largo()"></td>
        <td id="dato2"><select name="largo_cumple_vb" id="largo_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['largo_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['largo_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['largo_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="largo_observacion_vb" value="<?php echo $row_bolsa_verificacion['largo_observacion_vb']; ?>" size="30"></td>
      </tr>
      <tr id="tr2">
        <td id="dato1">Solapa (cm)</td>
        <td id="dato2"><input type="text" name="solapa_solicitada_vb" value="<?php if($row_bolsa_verificacion['solapa_solicitada_vb']=='') { echo $row_bolsa_oc['solapa_ocb']; } else {  echo $row_bolsa_verificacion['solapa_solicitada_vb']; } ?>" size="10"  onBlur="vb_solapa()"></td>
        <td id="dato2"><input type="text" name="solapa_encontrada_vb" value="<?php echo $row_bolsa_verificacion['solapa_encontrada_vb']; ?>" size="10" onBlur="vb_solapa()"></td>
        <td id="dato2"><input type="text" name="solapa_muestras_vb" value="<?php echo $row_bolsa_verificacion['solapa_muestras_vb']; ?>" size="10" onBlur="vb_solapa()"></td>
        <td id="dato2"><input type="text" name="solapa_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['solapa_no_conforme_vb']; ?>" size="10" onBlur="vb_solapa()"></td>
        <td id="dato2"><select name="solapa_cumple_vb" id="solapa_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['solapa_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['solapa_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['solapa_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="solapa_observacion_vb" value="<?php echo $row_bolsa_verificacion['solapa_observacion_vb']; ?>" size="30"></td>
      </tr>
      <tr>
        <td id="dato1">Fuelle / Fondo</td>
        <td id="dato2"><input type="text" name="fuelle_solicitado_vb" value="<?php if($row_bolsa_verificacion['fuelle_solicitado_vb']=='') { echo $row_bolsa_oc['fuelle_ocb']; } else {  echo $row_bolsa_verificacion['fuelle_solicitado_vb']; } ?>" size="10" onBlur="vb_fuelle()"></td>
        <td id="dato2"><input type="text" name="fuelle_encontrado_vb" value="<?php echo $row_bolsa_verificacion['fuelle_encontrado_vb']; ?>" size="10" onBlur="vb_fuelle()"></td>
        <td id="dato2"><input type="text" name="fuelle_muestras_vb" value="<?php echo $row_bolsa_verificacion['fuelle_muestras_vb']; ?>" size="10" onBlur="vb_fuelle()"></td>
        <td id="dato2"><input type="text" name="fuelle_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['fuelle_no_conforme_vb']; ?>" size="10" onBlur="vb_fuelle()"></td>
        <td id="dato2"><select name="fuelle_cumple_vb" id="fuelle_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['fuelle_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['fuelle_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
<option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['fuelle_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="fuelle_observacion_vb" value="<?php echo $row_bolsa_verificacion['fuelle_observacion_vb']; ?>" size="30"></td>
      </tr>
	  </table>
	  <table id="tabla3">
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
        <td id="dato1">Unidad de Empaque</td>
        <td id="dato2"><select name="empaque_cumple_vb" id="empaque_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['empaque_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['empaque_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
          <option value="0.5" <?php if (!(strcmp(0.5, $row_bolsa_verificacion['empaque_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
<option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['empaque_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="empaque_muestras_vb" value="<?php echo $row_bolsa_verificacion['empaque_muestras_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="empaque_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['empaque_no_conforme_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="empaque_observacion_vb" value="<?php echo $row_bolsa_verificacion['empaque_observacion_vb']; ?>" size="40"></td>
      </tr>
      <tr>
        <td id="dato1">Apariencia</td>
        <td id="dato2"><select name="apariencia_cumple_vb" id="apariencia_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['apariencia_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['apariencia_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
          <option value="0.5" <?php if (!(strcmp(0.5, $row_bolsa_verificacion['apariencia_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
<option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['apariencia_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="apariencia_muestras_vb" value="<?php echo $row_bolsa_verificacion['apariencia_muestras_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="apariencia_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['apariencia_no_conforme_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="apariencia_observacion_vb" value="<?php echo $row_bolsa_verificacion['apariencia_observacion_vb']; ?>" size="40"></td>
      </tr>
      <tr id="tr2">
        <td id="dato1">Resistencia de los Sellos </td>
        <td id="dato2"><select name="resistencia_cumple_vb" id="resistencia_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['resistencia_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['resistencia_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
          <option value="0.5" <?php if (!(strcmp(0.5, $row_bolsa_verificacion['resistencia_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
<option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['resistencia_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="resistencia_muestras_vb" value="<?php echo $row_bolsa_verificacion['resistencia_muestras_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="resistencia_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['resistencia_no_conforme_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="resistencia_observacion_vb" value="<?php echo $row_bolsa_verificacion['resistencia_observacion_vb']; ?>" size="40"></td>
      </tr>
      <tr>
        <td id="dato1">Tratamiento</td>
        <td id="dato2"><select name="tratamiento_cumple_vb" id="tratamiento_cumple_vb" onBlur="vb_calificacion()">
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['tratamiento_cumple_vb']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['tratamiento_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Cumple</option>
          <option value="0.5" <?php if (!(strcmp(0.5, $row_bolsa_verificacion['tratamiento_cumple_vb']))) {echo "selected=\"selected\"";} ?>>Parcial</option>
          <option value="0" <?php if (!(strcmp(0, $row_bolsa_verificacion['tratamiento_cumple_vb']))) {echo "selected=\"selected\"";} ?>>No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="tratamiento_muestras_vb" value="<?php echo $row_bolsa_verificacion['tratamiento_muestras_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="tratamiento_no_conforme_vb" value="<?php echo $row_bolsa_verificacion['tratamiento_no_conforme_vb']; ?>" size="10"></td>
        <td id="dato2"><input type="text" name="tratamiento_observacion_vb" value="<?php echo $row_bolsa_verificacion['tratamiento_observacion_vb']; ?>" size="40"></td>
      </tr>
	  </table>
	  <table id="tabla3">
      <tr id="tr1">
        <td colspan="5" id="subtitulo1">OBSERVACIONES</td>
        </tr>
      <tr>
        <td colspan="4" rowspan="3" id="justificacion"><strong>( * ) </strong> De acuerdo a la Ficha Tecnica del Material. <br>
                  <strong>( ** ) </strong>De acuerdo a lo comparado con el certificado de calidad del lote recibido.<br>
                  <strong>Nota: </strong>Cada muestreo se realiza con base a lo establecido en la gu&iacute;a A3-G02. Plan de Inspecci&oacute;n de Materia Prima.</td>
        <td id="fuente2" width="20%">SERVICIO</td>
      </tr>
      <tr>
        <td id="dato2"><select name="servicio_vb" id="servicio_vb">
          <option value="1" <?php if (!(strcmp(1, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>1</option>
          <option value="2" <?php if (!(strcmp(2, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>2</option>
          <option value="3" <?php if (!(strcmp(3, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>3</option>
          <option value="4" <?php if (!(strcmp(4, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>4</option>
          <option value="5" <?php if (!(strcmp(5, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>5</option>
          <option value="6" <?php if (!(strcmp(6, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>6</option>
          <option value="7" <?php if (!(strcmp(7, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>7</option>
          <option value="8" <?php if (!(strcmp(8, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>8</option>
          <option value="9" <?php if (!(strcmp(9, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>9</option>
          <option value="10" <?php if (!(strcmp(10, $row_bolsa_verificacion['servicio_vb']))) {echo "selected=\"selected\"";} ?>>10</option>
        </select></td>
      </tr>
      <tr>
        <td id="dato2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" rowspan="3" id="fuente1"><strong>OTRAS OBSERVACIONES</strong><br>
		  <textarea name="observaciones_vb" cols="70" rows="2"><?php echo $row_bolsa_verificacion['observaciones_vb']; ?></textarea></td>
        <td id="fuente2">CALIFICACION %</td>
      </tr>
      <tr>
        <td id="dato2"><input type="text" name="calificacion_vb" value="<?php echo $row_bolsa_verificacion['calificacion_vb']; ?>" size="10" onBlur="vb_calificacion()"></td>
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
        <td id="dato2"><input type="text" name="fecha_registro_vb" value="<?php if($row_bolsa_verificacion['fecha_registro_vb']=='') { echo date("Y-m-d"); } else { echo $row_bolsa_verificacion['fecha_registro_vb']; } ?>" size="10"></td>
        <td id="dato2"><input type="text" name="responsable_registro_vb" value="<?php if($row_bolsa_verificacion['responsable_registro_vb']=='') { echo $row_usuario['nombre_usuario']; } else { echo $row_bolsa_verificacion['responsable_registro_vb']; } ?>" size="30"></td>
        <td id="dato2"><input type="text" name="fecha_modificacion_vb" value="<?php if($row_bolsa_verificacion['fecha_modificacion_vb']=='') { echo date("Y-m-d"); } else { echo $row_bolsa_verificacion['fecha_modificacion_vb']; } ?>" size="10"></td>
        <td colspan="2" id="dato2"><input type="text" name="responsable_modificacion_vb" value="<?php if($row_bolsa_verificacion['responsable_modificacion_vb']=='') { echo $row_usuario['nombre_usuario']; } else { echo $row_bolsa_verificacion['responsable_modificacion_vb']; } ?>" size="30"></td>
        </tr>
      <tr>
        <td colspan="5" id="dato2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="5" id="dato2"><input type="submit" value="Actualizar VERIFICACION"></td>
        </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
    <input type="hidden" name="n_vb" value="<?php echo $row_bolsa_verificacion['n_vb']; ?>">
  </form></td></tr></table>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($bolsa_verificacion);

mysql_free_result($bolsa_oc);
?>