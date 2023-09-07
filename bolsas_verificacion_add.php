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
/*ACTUALIZA SALDO EN OCB*/
$n_ocb=$_POST['n_ocb_vb'];
$falta=$_POST['faltantes_vb'];
$sqlocb="UPDATE orden_compra_bolsas SET saldo_verificacion_ocb='$falta' WHERE n_ocb='$n_ocb'";
/*ADD VERIFICACION*/
  $insertSQL = sprintf("INSERT INTO verificacion_bolsas (n_vb, n_ocb_vb, id_p_vb, id_bolsa_vb, id_ref_vb, fecha_recibido_vb, responsable_recibido_vb, entrega_vb, factura_vb, remision_vb, otro_recibo_vb, paquetes_recibidos_vb, cantidad_solicitada_vb, faltantes_vb, cantidad_encontrada_vb, cantidad_muestras_vb, cantidad_no_conforme_vb, cantidad_cumple_vb, cantidad_observacion_vb, calibre_solicitado_vb, calibre_encontrado_vb, calibre_muestras_vb, calibre_no_conforme_vb, calibre_cumple_vb, calibre_observacion_vb, ancho_solicitado_vb, ancho_encontrado_vb, ancho_muestras_vb, ancho_no_conforme_vb, ancho_cumple_vb, ancho_observacion_vb, largo_solicitado_vb, largo_encontrado_vb, largo_muestras_vb, largo_no_conforme_vb, largo_cumple_vb, largo_observacion_vb, solapa_solicitada_vb, solapa_encontrada_vb, solapa_muestras_vb, solapa_no_conforme_vb, solapa_cumple_vb, solapa_observacion_vb, fuelle_solicitado_vb, fuelle_encontrado_vb, fuelle_muestras_vb, fuelle_no_conforme_vb, fuelle_cumple_vb, fuelle_observacion_vb, empaque_cumple_vb, empaque_muestras_vb, empaque_no_conforme_vb, empaque_observacion_vb, apariencia_cumple_vb, apariencia_muestras_vb, apariencia_no_conforme_vb, apariencia_observacion_vb, resistencia_cumple_vb, resistencia_muestras_vb, resistencia_no_conforme_vb, resistencia_observacion_vb, tratamiento_cumple_vb, tratamiento_muestras_vb, tratamiento_no_conforme_vb, tratamiento_observacion_vb, servicio_vb, calificacion_vb, observaciones_vb, fecha_registro_vb, responsable_registro_vb, fecha_modificacion_vb, responsable_modificacion_vb) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['n_vb'], "int"),
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
                       GetSQLValueString($_POST['responsable_modificacion_vb'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $resultocb=mysql_query($sqlocb);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "bolsas_verificacion_vista.php?n_vb=" . $_POST['n_vb'] . "";
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
$query_ultimo = "SELECT * FROM verificacion_bolsas ORDER BY n_vb DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_bolsa_oc = "-1";
if (isset($_GET['n_ocb'])) {
  $colname_bolsa_oc = (get_magic_quotes_gpc()) ? $_GET['n_ocb'] : addslashes($_GET['n_ocb']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa_oc = sprintf("SELECT * FROM orden_compra_bolsas WHERE n_ocb = %s", $colname_bolsa_oc);
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
	 <td id="cabezamenu">
	 <ul id="menuhorizontal">
	 <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
	 <li><a href="menu.php">MENU PRINCIPAL</a></li>
	 <li><a href="compras.php">GESTION COMPRAS</a></li>
	 </ul>
	 </td>
  </tr>
 </table></td></tr>
 <tr><td id="linea1" align="center">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_recibido_vb','','R','responsable_recibido_vb','','R','factura_vb','','R','n_ocb_vb','','R','paquetes_recibidos_vb','','R','cantidad_solicitada_vb','','R','saldo_verificacion_ocb','','R','cantidad_encontrada_vb','','R','faltantes_vb','','R','calificacion_vb','','R','fecha_registro_vb','','R','responsable_registro_vb','','R');return document.MM_returnValue">
        <table id="tabla3">
      <tr id="tr1">
          <td id="codigo" width="25%">CODIGO : A3 - F08 </td>
          <td colspan="2" id="titulo2" width="50%">VERIFICACION</td>
          <td width="25%" colspan="3" id="codigo">VERSION : 1 </td>
        </tr>
      <tr>
        <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
        <td colspan="2" id="subtitulo">PRODUCTO TERMINADO (BOLSAS)</td>
        <td colspan="3" id="dato2"><a href="bolsas_oc_verificacion.php?n_ocb=<?php echo $row_bolsa_oc['n_ocb']; ?>" target="_top"><img src="images/v.gif" alt="VERIF X O.C." border="0" style="cursor:hand;"/></a><a href="bolsas_oc.php" target="_top"><img src="images/o.gif" alt="O.C. (BOLSAS)" border="0" style="cursor:hand;"/></a><a href="bolsas_verificacion.php" target="_top"><img src="images/cat.gif" alt="VERIFICACIONES" border="0" style="cursor:hand;"/></a><a href="bolsas.php" target="_top"><img src="images/b.gif" alt="BOLSAS" border="0" style="cursor:hand;"/></a></td>
      </tr>
      <tr>
        <td colspan="2" id="numero2"><strong>N&deg;
            <input name="n_vb" type="hidden" id="n_vb" value="<?php $num=$row_ultimo['n_vb']+1; echo $num; ?>">
            <?php echo $num; ?></strong></td>
        <td colspan="2" id="dato2">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente2">FECHA DE RECIBO</td>
        <td id="fuente2">RECIBIDO POR</td>
        <td colspan="2" id="fuente2">ENTREGA</td>
      </tr>
      <tr>
        <td id="dato2"><input type="text" name="fecha_recibido_vb" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
        <td id="dato2"><input type="text" name="responsable_recibido_vb" value="" size="20"></td>
        <td colspan="2" id="dato2"><select name="entrega_vb" id="entrega_vb">
          <option value="0">PARCIAL</option>
          <option value="1">TOTAL</option>
        </select></td>
      </tr>
      <tr>
        <td id="fuente2">FACTURA</td>
        <td id="fuente2">REMISION</td>
        <td colspan="2" id="fuente2">OTRO RECIBO </td>
        </tr>
      <tr>
        <td id="dato2"><input type="text" name="factura_vb" value="" size="20"></td>
        <td id="dato2"><input type="text" name="remision_vb" value="" size="20"></td>
        <td colspan="2" id="dato2"><input type="text" name="otro_recibo_vb" value="" size="20"></td>
      </tr>
      <tr id="tr1">
        <td id="fuente2">ORDEN DE COMPRA</td>
        <td id="fuente2">PROVEEDOR</td>
        <td id="fuente2">MATERIAL SOLICITADO</td>
        <td id="fuente2">REF.</td>
        <td id="fuente2">PAQUETES RECIBIDOS</td>
        </tr>
      <tr>
        <td id="dato2"><input type="text" name="n_ocb_vb" value="<?php echo $row_bolsa_oc['n_ocb']; ?>" size="5"></td>
        <td id="dato2"><?php $id_p=$row_bolsa_verificacion['id_p_vb'];
		  if($id_p == '') {  $id_p=$row_bolsa_oc['id_p_ocb']; }
		  if($id_p != '') {  $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
		  $resultp= mysql_query($sqlp);
		  $nump= mysql_num_rows($resultp);
		  if($nump >='1') { 
		  $proveedor_p = mysql_result($resultp,0,'proveedor_p');
		  echo $proveedor_p; } } ?>
          <input name="id_p_vb" type="hidden" value="<?php echo $id_p; ?>"></td>
        <td id="dato2"><?php $id_bolsa=$row_bolsa_oc['id_bolsa_ocb'];
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
        <td id="dato2"><?php $id_ref=$row_bolsa_oc['id_ref_ocb'];		  
		  if($id_ref!='') { $sqlr="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
		  $resultr= mysql_query($sqlr);
		  $numr= mysql_num_rows($resultr);
		  if($numr >='1') 
		  { $cod_ref = mysql_result($resultr,0,'cod_ref');
		  echo $cod_ref; } } ?><input name="id_ref_vb" type="hidden" value="<?php echo $id_ref; ?>"></td>
        <td id="dato2"><input type="text" name="paquetes_recibidos_vb" value="" size="10"></td>
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
        <td id="dato2"><input type="text" name="cantidad_solicitada_vb" value="<?php echo $row_bolsa_oc['cantidad_ocb']; ?>" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input name="saldo_verificacion_ocb" type="text" id="saldo_verificacion_ocb" value="<?php echo $row_bolsa_oc['saldo_verificacion_ocb']; ?>" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input type="text" name="cantidad_encontrada_vb" value="" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input type="text" name="faltantes_vb" value="" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input type="text" name="cantidad_muestras_vb" value="" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><input type="text" name="cantidad_no_conforme_vb" value="" size="10" onBlur="vb_cantidad()"></td>
        <td id="dato2"><select name="cantidad_cumple_vb" id="cantidad_cumple_vb" onBlur="vb_calificacion()">
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
          <option value="0">No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="cantidad_observacion_vb" value="" size="30"></td>
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
	     <td id="dato2"><input type="text" name="calibre_solicitado_vb" value="<?php echo $row_bolsa_oc['calibre_micras_ocb']; ?>" size="10" onBlur="vb_calibre()"></td>
	     <td id="dato2"><input type="text" name="calibre_encontrado_vb" value="" size="10" onBlur="vb_calibre()"></td>
	     <td id="dato2"><input type="text" name="calibre_muestras_vb" value="" size="10" onBlur="vb_calibre()"></td>
	     <td id="dato2"><input type="text" name="calibre_no_conforme_vb" value="" size="10" onBlur="vb_calibre()"></td>
	     <td id="dato2"><select name="calibre_cumple_vb" id="calibre_cumple_vb" onBlur="vb_calificacion()">
	       <option value="2">N.A.</option>
	       <option value="1">Cumple</option>
		   <option value="0">No cumple</option>
         </select></td>
		 <td id="dato2"><input type="text" name="calibre_observacion_vb" value="" size="30"></td>
      </tr>
      <tr id="tr2">
        <td id="dato1">Ancho (cm) </td>
        <td id="dato2"><input type="text" name="ancho_solicitado_vb" value="<?php echo $row_bolsa_oc['ancho_ocb']; ?>" size="10" onBlur="vb_ancho()"></td>
        <td id="dato2"><input type="text" name="ancho_encontrado_vb" value="" size="10" onBlur="vb_ancho()"></td>
        <td id="dato2"><input type="text" name="ancho_muestras_vb" value="" size="10" onBlur="vb_ancho()"></td>
        <td id="dato2"><input type="text" name="ancho_no_conforme_vb" value="" size="10" onBlur="vb_ancho()"></td>
        <td id="dato2"><select name="ancho_cumple_vb" id="ancho_cumple_vb" onBlur="vb_calificacion()">
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
          <option value="0">No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="ancho_observacion_vb" value="" size="30"></td>
      </tr>
      <tr>
        <td id="dato1">Largo (cm) </td>
        <td id="dato2"><input type="text" name="largo_solicitado_vb" value="<?php echo $row_bolsa_oc['largo_ocb']; ?>" size="10" onBlur="vb_largo()"></td>
        <td id="dato2"><input type="text" name="largo_encontrado_vb" value="" size="10" onBlur="vb_largo()"></td>
        <td id="dato2"><input type="text" name="largo_muestras_vb" value="" size="10" onBlur="vb_largo()"></td>
        <td id="dato2"><input type="text" name="largo_no_conforme_vb" value="" size="10" onBlur="vb_largo()"></td>
        <td id="dato2"><select name="largo_cumple_vb" id="largo_cumple_vb" onBlur="vb_calificacion()">
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
		  <option value="0">No cumple</option>
		  </select></td>
        <td id="dato2"><input type="text" name="largo_observacion_vb" value="" size="30"></td>
      </tr>
      <tr id="tr2">
        <td id="dato1">Solapa (cm)</td>
        <td id="dato2"><input type="text" name="solapa_solicitada_vb" value="<?php echo $row_bolsa_oc['solapa_ocb']; ?>" size="10"  onBlur="vb_solapa()"></td>
        <td id="dato2"><input type="text" name="solapa_encontrada_vb" value="" size="10" onBlur="vb_solapa()"></td>
        <td id="dato2"><input type="text" name="solapa_muestras_vb" value="" size="10" onBlur="vb_solapa()"></td>
        <td id="dato2"><input type="text" name="solapa_no_conforme_vb" value="" size="10" onBlur="vb_solapa()"></td>
        <td id="dato2"><select name="solapa_cumple_vb" id="solapa_cumple_vb" onBlur="vb_calificacion()">
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
		  <option value="0">No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="solapa_observacion_vb" value="" size="30"></td>
      </tr>
      <tr>
        <td id="dato1">Fuelle / Fondo</td>
        <td id="dato2"><input type="text" name="fuelle_solicitado_vb" value="<?php echo $row_bolsa_oc['fuelle_ocb']; ?>" size="10" onBlur="vb_fuelle()"></td>
        <td id="dato2"><input type="text" name="fuelle_encontrado_vb" value="" size="10" onBlur="vb_fuelle()"></td>
        <td id="dato2"><input type="text" name="fuelle_muestras_vb" value="" size="10" onBlur="vb_fuelle()"></td>
        <td id="dato2"><input type="text" name="fuelle_no_conforme_vb" value="" size="10" onBlur="vb_fuelle()"></td>
        <td id="dato2"><select name="fuelle_cumple_vb" id="fuelle_cumple_vb" onBlur="vb_calificacion()">
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
		  <option value="0">No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="fuelle_observacion_vb" value="" size="30"></td>
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
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
          <option value="0.5">Parcial</option>
		  <option value="0">No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="empaque_muestras_vb" value="" size="10"></td>
        <td id="dato2"><input type="text" name="empaque_no_conforme_vb" value="" size="10"></td>
        <td id="dato2"><input type="text" name="empaque_observacion_vb" value="" size="40"></td>
      </tr>
      <tr>
        <td id="dato1">Apariencia</td>
        <td id="dato2"><select name="apariencia_cumple_vb" id="apariencia_cumple_vb" onBlur="vb_calificacion()">
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
          <option value="0.5">Parcial</option>
		  <option value="0">No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="apariencia_muestras_vb" value="" size="10"></td>
        <td id="dato2"><input type="text" name="apariencia_no_conforme_vb" value="" size="10"></td>
        <td id="dato2"><input type="text" name="apariencia_observacion_vb" value="" size="40"></td>
      </tr>
      <tr id="tr2">
        <td id="dato1">Resistencia de los Sellos </td>
        <td id="dato2"><select name="resistencia_cumple_vb" id="resistencia_cumple_vb" onBlur="vb_calificacion()">
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
          <option value="0.5">Parcial</option>
		  <option value="0">No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="resistencia_muestras_vb" value="" size="10"></td>
        <td id="dato2"><input type="text" name="resistencia_no_conforme_vb" value="" size="10"></td>
        <td id="dato2"><input type="text" name="resistencia_observacion_vb" value="" size="40"></td>
      </tr>
      <tr>
        <td id="dato1">Tratamiento</td>
        <td id="dato2"><select name="tratamiento_cumple_vb" id="tratamiento_cumple_vb" onBlur="vb_calificacion()">
          <option value="2">N.A.</option>
          <option value="1">Cumple</option>
          <option value="0.5">Parcial</option>
          <option value="0">No cumple</option>
        </select></td>
        <td id="dato2"><input type="text" name="tratamiento_muestras_vb" value="" size="10"></td>
        <td id="dato2"><input type="text" name="tratamiento_no_conforme_vb" value="" size="10"></td>
        <td id="dato2"><input type="text" name="tratamiento_observacion_vb" value="" size="40"></td>
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
		  <textarea name="observaciones_vb" cols="70" rows="2"></textarea></td>
        <td id="fuente2">CALIFICACION %</td>
      </tr>
      <tr>
        <td id="dato2"><input type="text" name="calificacion_vb" value="" size="10" onBlur="vb_calificacion()"></td>
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
        <td id="dato2"><input type="text" name="fecha_registro_vb" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
        <td id="dato2"><input type="text" name="responsable_registro_vb" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30"></td>
        <td id="dato2"><input type="text" name="fecha_modificacion_vb" value="" size="10"></td>
        <td colspan="2" id="dato2"><input type="text" name="responsable_modificacion_vb" value="" size="30"></td>
        </tr>
      <tr>
        <td colspan="5" id="dato2">&nbsp;</td>
      </tr>
	  <tr>
	  <td colspan="5" id="dato2"><input type="submit" value="ADD VERIFICACION"></td>
	  </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form>
</td></tr></table>      
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ultimo);

mysql_free_result($bolsa_oc);
?>