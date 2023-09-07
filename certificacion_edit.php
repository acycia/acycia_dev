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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE TblCertificacion SET idc=%s, op=%s, idref=%s, codref=%s, versref=%s, refCliente=%s, oc=%s, factura=%s, anchvaloptenido=%s, largvaloptenido=%s, solvaloptenido=%s, fuellvaloptenido=%s, calvaloptenido=%s, tenstdvaloptenido=%s, tensmdvaloptenido=%s, elongtdvaloptenido=%s, elongmdvaloptenido=%s, factortdvaloptenido=%s, factormdvaloptenido=%s, coefdimccval=%s, coefdimddval=%s, coefestccval=%s, coefestddval=%s, impatdval=%s, tensionsval=%s, tempeselleval=%s, referencia=%s, lotenum=%s, ensayonum=%s, aparcump=%s, evidfriocump=%s, evidcalorcump=%s, evidsolvcumple=%s, evidtambcumple=%s, pigmcump=%s, colortoncump=%s, textcump=%s, codigbarcump=%s, observ=%s, estado=%s, fecha=%s, fechamodifico=%s, instrumentista=%s, modifico=%s, jefeplanta=%s WHERE idcc=%s",
                       GetSQLValueString($_POST['idc'], "int"),
                       GetSQLValueString($_POST['op'], "int"),
                       GetSQLValueString($_POST['idref'], "int"),
                       GetSQLValueString($_POST['codref'], "text"),
                       GetSQLValueString($_POST['versref'], "int"),
					   GetSQLValueString($_POST['refCliente'], "text"),
                       GetSQLValueString($_POST['oc'], "text"),
                       GetSQLValueString($_POST['factura'], "int"),
                       GetSQLValueString($_POST['anchvaloptenido'], "double"),
                       GetSQLValueString($_POST['largvaloptenido'], "double"),
                       GetSQLValueString($_POST['solvaloptenido'], "double"),
                       GetSQLValueString($_POST['fuellvaloptenido'], "double"),
                       GetSQLValueString($_POST['calvaloptenido'], "double"),
                       GetSQLValueString($_POST['tenstdvaloptenido'], "double"),
                       GetSQLValueString($_POST['tensmdvaloptenido'], "double"),
                       GetSQLValueString($_POST['elongtdvaloptenido'], "double"),
                       GetSQLValueString($_POST['elongmdvaloptenido'], "double"),
                       GetSQLValueString($_POST['factortdvaloptenido'], "double"),
                       GetSQLValueString($_POST['factormdvaloptenido'], "double"),
                       GetSQLValueString($_POST['coefdimccval'], "double"),
                       GetSQLValueString($_POST['coefdimddval'], "double"),
                       GetSQLValueString($_POST['coefestccval'], "double"),
                       GetSQLValueString($_POST['coefestddval'], "double"),
                       GetSQLValueString($_POST['impatdval'], "double"),
                       GetSQLValueString($_POST['tensionsval'], "double"),
                       GetSQLValueString($_POST['tempeselleval'],"double"),
                       GetSQLValueString($_POST['referencia'], "text"),
                       GetSQLValueString($_POST['lotenum'], "text"),
                       GetSQLValueString($_POST['ensayonum'], "text"),
                       GetSQLValueString($_POST['aparcump'], "int"),
                       GetSQLValueString($_POST['evidfriocump'], "int"),
                       GetSQLValueString($_POST['evidcalorcump'], "int"),
                       GetSQLValueString($_POST['evidsolvcumple'], "int"),
                       GetSQLValueString($_POST['evidtambcumple'], "int"),
                       GetSQLValueString($_POST['pigmcump'], "int"),
                       GetSQLValueString($_POST['colortoncump'], "int"),
                       GetSQLValueString($_POST['textcump'], "int"),
                       GetSQLValueString($_POST['codigbarcump'], "int"),
                       GetSQLValueString($_POST['observ'], "text"),
                       GetSQLValueString($_POST['estado'], "int"),
                       GetSQLValueString($_POST['fecha'], "date"),
                       GetSQLValueString($_POST['fechamodifico'], "date"),
                       GetSQLValueString($_POST['instrumentista'], "text"),
                       GetSQLValueString($_POST['modifico'], "text"),
                       GetSQLValueString($_POST['jefeplanta'], "text"),
                       GetSQLValueString($_POST['idcc'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "certificacion_vista.php?idcc=" . $_POST['idcc'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_op = "-1";
if (isset($_GET['op'])) {
  $colname_op = (get_magic_quotes_gpc()) ? $_GET['op'] : addslashes($_GET['op']); 
}
mysql_select_db($database_conexion1, $conexion1);
$query_ordencompra = sprintf("SELECT id_op,str_numero_oc_op FROM Tbl_orden_produccion WHERE Tbl_orden_produccion.id_op = '%s'",$colname_op);
$ordencompra = mysql_query($query_ordencompra, $conexion1) or die(mysql_error());
$row_ordencompra = mysql_fetch_assoc($ordencompra);
$totalRows_ordencompra = mysql_num_rows($ordencompra);

$colname_referencia = "-1";
if (isset($_GET['idcc'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['idcc'] : addslashes($_GET['idcc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM TblCertificacion,Tbl_referencia, Tbl_egp WHERE TblCertificacion.idcc = '%s' AND TblCertificacion.idref = Tbl_referencia.id_ref  AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_clientes = "-1";
if (isset($_GET['idcc'])) {
  $colname_clientes = (get_magic_quotes_gpc()) ? $_GET['idcc'] : addslashes($_GET['idcc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_clientes = sprintf("SELECT TblCertificacion.idcc,TblCertificacion.codref,Tbl_cliente_referencia.N_referencia,Tbl_cliente_referencia.Str_nit,cliente.id_c,cliente.nit_c,cliente.nombre_c FROM TblCertificacion, Tbl_cliente_referencia,cliente WHERE TblCertificacion.idcc = '%s' AND TblCertificacion.codref = Tbl_cliente_referencia.N_referencia and Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY cliente.nombre_c ASC", $colname_clientes);

//SELECT DISTINCT TblCertificacion.idcc,TblCertificacion.codref,Tbl_cliente_referencia.Str_nit,cliente.id_c,cliente.nit_c,cliente.nombre_c FROM TblCertificacion,Tbl_cliente_referencia,cliente WHERE TblCertificacion.idcc='%s' 
//and TblCertificacion.codref=Tbl_cliente_referencia.N_referencia and Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY cliente.nombre_c ASC
/*$query_clientes = sprintf("SELECT  TblCertificacion.codref, Tbl_referencia.cod_ref,Tbl_cliente_referencia.N_referencia,Tbl_cliente_referencia.Str_nit,cliente.id_c,cliente.nit_c,cliente.nombre_c FROM Tbl_referencia,Tbl_cliente_referencia,cliente WHERE TblCertificacion.idcc = '%s' AND TblCertificacion.codref = Tbl_referencia.cod_ref 
and Tbl_referencia.cod_ref=Tbl_cliente_referencia.N_referencia and Tbl_cliente_referencia.Str_nit=cliente.nit_c ORDER BY cliente.nombre_c ASC", $colname_clientes);
*/
$clientes = mysql_query($query_clientes, $conexion1) or die(mysql_error());
$row_clientes = mysql_fetch_assoc($clientes);
$totalRows_clientes = mysql_num_rows($clientes);

//REF CLIENTE
$colname_opref = "-1";
if (isset($_GET['idcc'])) {
  $colname_opref = (get_magic_quotes_gpc()) ? $_GET['idcc'] : addslashes($_GET['idcc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_opref = sprintf("SELECT TblCertificacion.idcc,TblCertificacion.op,Tbl_orden_produccion.id_op,Tbl_orden_produccion.id_ref_op,Tbl_orden_produccion.int_cotiz_op FROM TblCertificacion,Tbl_orden_produccion WHERE TblCertificacion.idcc = '%s' AND TblCertificacion.idref=Tbl_orden_produccion.id_ref_op  ORDER BY Tbl_orden_produccion.id_op ASC", $colname_opref);
$opref = mysql_query($query_opref, $conexion1) or die(mysql_error());
$row_opref = mysql_fetch_assoc($opref);
$totalRows_opref = mysql_num_rows($opref);

$colname_ficha_tecnica = "-1";
if (isset($_GET['idcc'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['idcc'] : addslashes($_GET['idcc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM TblCertificacion,TblFichaTecnica WHERE TblCertificacion.idcc = '%s' AND TblCertificacion.idref = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion = "-1";
if (isset($_GET['idcc'])) {
  $colname_certificacion = (get_magic_quotes_gpc()) ? $_GET['idcc'] : addslashes($_GET['idcc']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion = sprintf("SELECT * FROM TblCertificacion  WHERE TblCertificacion.idcc='%s'",$colname_certificacion);
$certificacion = mysql_query($query_certificacion, $conexion1) or die(mysql_error());
$row_certificacion = mysql_fetch_assoc($certificacion);
$totalRows_certificacion = mysql_num_rows($certificacion);
?><html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
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
<table id="tabla1">
  <tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
	<li><a href="<?php echo $logoutAction ?>
">CERRAR SESION</a>
</li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>
</ul>
</td>
</tr>
<tr>
  <td colspan="2" align="center" id="linea1"><form method="POST" name="form1" action="<?php echo $editFormAction; ?>" onSubmit="return validacion_select_oc()">
    <table id="tabla2">
      <tr id="tr1">
        <td colspan="2" id="codigo">CODIGO: R4-F15</td>
        <td colspan="3" id="titulo2">CERTIFICADO DE CALIDAD
          <input name="idcc" type="hidden" value="<?php echo $row_certificacion['idcc']; ?>"></td>
        <td colspan="2" id="codigo">VERSION: 2 </td>
      </tr>
      <tr>
        <td colspan="1" rowspan="4" id="dato2"><img src="images/logoacyc.jpg" /></td>
        <td colspan="3" id="subtitulo"><input name="idref" type="hidden" value="<?php echo $row_certificacion['idref']; ?>">
          CERT N°
          <input name="codref" type="text" value="<?php echo $row_certificacion['codref']; ?>" size="6" readonly></td>
        <td colspan="3" id="dato2"><a href="certificacion_vista.php?idcc=<?php echo $row_certificacion['idcc']; ?>"><img src="images/hoja.gif" alt="VISTA CERTIFICACION" title="VISTA CERTIFICACION" border="0" /></a><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" title="RESTAURAR" onClick="window.history.go()"><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHAS TECNICAS" title="FICHAS TECNICAS" border="0" style="cursor:hand;"/></a><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="certificacion_listado.php?id_ref=<?php echo $_GET['id_ref']; ?>"><img src="images/c.gif" style="cursor:hand;" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0"/></a></td>
      </tr>
      <tr>
        <td id="fuente2">FECHA  ELABORACION</td>
        <td colspan="5" id="fuente2">ELABORADA POR </td>
      </tr>
      <tr>
        <td id="dato1"><input type="date" name="fecha" value="<?php echo $row_certificacion['fecha']; ?>" size="10"></td>
        <td colspan="5" id="dato1"><input name="instrumentista" type="text" id="instrumentista" value="<?php echo $row_certificacion['instrumentista']; ?>" size="30" readonly></td>
      </tr>
      <tr>
        <td colspan="6" id="dato1">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">CLIENTE</td>
        <td colspan="6" id="fuente1"><select  name="idc" id="cliente" style="width:200px">
          <option value=""<?php if (!(strcmp("", $row_certificacion['idc']))) {echo "selected=\"selected\"";} ?>></option>
          <?php
do {  
?>
          <option value="<?php echo $row_clientes['id_c']?>"<?php if (!(strcmp($row_clientes['id_c'], $row_certificacion['idc']))) {echo "selected=\"selected\"";} ?>><?php echo $row_clientes['nombre_c'];?></option>
          <?php
} while ($row_clientes = mysql_fetch_assoc($clientes));
  $rows = mysql_num_rows($clientes);
  if($rows > 0) {
      mysql_data_seek($clientes, 0);
	  $row_clientes = mysql_fetch_assoc($clientes);
  }
?>
        </select></td>
        </tr>
      <tr>
        <td id="fuente1">ORDEN DE PRODUCCION</td>
        <td id="fuente1"><select name="op" id="op" onChange="if(form1.op.value) { consulta_certificacion_op_edit(); } else{ alert('Debe Seleccionar una O.P'); }">
          <option value=""<?php if (!(strcmp("", $row_ordencompra['id_op']))) {echo "selected=\"selected\"";} ?>></option>
          <?php
do {  
?>
          <option value="<?php echo $row_opref['id_op']?>"<?php if (!(strcmp($row_opref['id_op'], $row_ordencompra['id_op']))) {echo "selected=\"selected\"";} ?>><?php echo $row_opref['id_op']?></option>
          <?php
} while ($row_opref = mysql_fetch_assoc($opref));
  $rows = mysql_num_rows($opref);
  if($rows > 0) {
      mysql_data_seek($opref, 0);
	  $row_opref = mysql_fetch_assoc($opref);
  }
?>
        </select></td>
        <td colspan="2" id="fuente1">FICHA TECNICA / REFERENCIA</td>
        <td id="fuente1"><strong><?php echo $row_referencia['cod_ref'];?>-<?php echo $row_referencia['version_ref']; ?>
            <input type="hidden" name="versref" value="<?php echo $row_certificacion['versref']; ?>" size="32">
        </strong></td>
        <td id="fuente1">REF-CLIENTE</td>
        <td id="fuente1"><input name="refCliente" type="text" id="refCliente" placeholder="Ref-Cliente" style="width:60px" value="<?php echo $row_certificacion['refCliente']; ?>" onBlur="conMayusculas(this)"></td>
      </tr>
      <tr>
        <td id="fuente1">ORDEN DE COMPRA</td>
        <td id="fuente1"><input type="hidden" name="oc" size="32" value="<?php if($row_ordencompra['str_numero_oc_op']=='') {echo $row_certificacion['oc'];}else{echo $row_ordencompra['str_numero_oc_op'];} ?>">
          <?php if($row_ordencompra['str_numero_oc_op']=='') {echo $row_certificacion['oc'];}else{echo $row_ordencompra['str_numero_oc_op'];}; ?></td>
        <td colspan="2" id="fuente1">CALIBRE &mu;m</td>
        <td id="fuente1"><?php $calibrem = ($row_referencia['calibre_ref']*25.4); echo $calibrem; ?></td>
        <td id="fuente1">FACTURA N&deg;</td>
        <td id="fuente1"><input name="factura" type="number" id="factura" placeholder="0" style="width:60px" min="0" step="1" value="<?php echo $row_certificacion['factura']; ?>"></td>
      </tr>
      <tr id="tr1">
        <td colspan="7" id="titulo2">DIMENSIONES DE
          <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){echo "LAMINAS";}else if($row_referencia['tipo_bolsa_ref']=='PACKING LIST'){echo "PACKING LIST";}else{echo "BOLSAS";}?></td>
      </tr>
      <tr>
        <td rowspan="2" id="titulo3">ESPECIFICACIONES</td>
        <td rowspan="2" id="titulo3">VALOR</td>
        <td colspan="2" id="titulo3">VARIACION</td>
        <td rowspan="2" id="titulo3">VALORES OBTENIDOS</td>
        <td rowspan="2" id="titulo3">METODO DE ENSAYO</td>
        <td rowspan="2" id="titulo3">UNIDADES</td>
      </tr>
      <tr>
        <td id="titulo3">MAX</td>
        <td id="titulo3">MIN</td>
      </tr>
      <tr>
        <td id="fuente1">ANCHO</td>
        <td id="fuente1"><?php $ancho = ($row_referencia['ancho_ref']*10);echo $ancho; ?></td>
        <td id="fuente1"><?php echo $ancho+10; ?></td>
        <td id="fuente1"><?php echo $ancho-10; ?></td>
        <td id="fuente2"><input name="anchvaloptenido" type="number" id="anchvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['anchvaloptenido']; ?>"></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">mm</td>
      </tr>
      <tr> 
        <td id="fuente1"><?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){$largo = $row_referencia['N_repeticion_l'];  echo 'REPETICION';}else{$largo = ($row_referencia['largo_ref']*10);echo 'LARGO';}?></td>
        <td id="fuente1"><?php echo $largo; ?></td>
        <td id="fuente1"><?php echo $largo+10; ?></td>
        <td id="fuente1"><?php echo $largo-10; ?></td>
        <td id="fuente2"><input name="largvaloptenido" type="number" id="largvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['largvaloptenido']; ?>"></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">mm</td>
      </tr>
      <?php if($row_referencia['tipo_bolsa_ref']!='LAMINA'){ ?>
      <tr>
        <td id="fuente1">SOLAPA
          <?php if (!(strcmp($row_referencia['b_solapa_caract_ref'],2))){echo "sencilla";} if (!(strcmp($row_referencia['b_solapa_caract_ref'],1))){echo "Doble";}?></td>
        <td id="fuente1"><?php $solapa=$row_referencia['solapa_ref']*10;echo $solapa; ?></td>
        <td id="fuente1"><?php echo $solapa+10; ?></td>
        <td id="fuente1"><?php echo $solapa-10; ?></td>
        <td id="fuente2"><input name="solvaloptenido" type="number" id="solvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['solvaloptenido']; ?>"></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">mm</td>
      </tr>
      <tr>
        <td id="fuente1">FUELLE</td>
        <td id="fuente1"><?php echo $row_referencia['N_fuelle']*10; ?></td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente1">&nbsp;</td>
        <td id="fuente2"><input name="fuellvaloptenido" type="number" id="fuellvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['fuellvaloptenido']; ?>"></td>
        <td id="fuente1">Interno</td>
        <td id="fuente1">mm</td>
      </tr>
      <?php }?>
      <tr>
        <td id="fuente1">CALIBRE</td>
        <td id="fuente1"><?php echo $calibrem; ?></td>
        <td id="fuente1"><?php $calibremi = ($calibrem+($calibrem*10)/100); echo $calibremi;?></td>
        <td id="fuente1"><?php $calibrem = ($calibrem-($calibrem*10)/100); echo $calibrem;?></td>
        <td id="fuente2"><input name="calvaloptenido" type="number" id="calvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['calvaloptenido']; ?>"></td>
        <td id="fuente1">ASTM D-6988-08</td>
        <td id="fuente1">&mu;m: (micras)</td>
      </tr>
      <tr id="tr1">
        <td colspan="7" id="titulo2"><strong>PROPIEDADES MECANICAS</strong></td>
      </tr>
      <tr id="tr1">
        <td id="detalle2">ANALISIS</td>
        <td colspan="2" id="detalle2">MAXIMO / MINIMO</td>
        <td colspan="2" id="detalle2">VALORES OBTENIDOS</td>
        <td id="detalle2">NORMAL DE ENSAYO</td>
        <td id="detalle2">UNIDAD</td>
      </tr>
      <tr>
        <td id="detalle1">Tensi&oacute;n TD</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['PtensionTd_ft']; ?></td>
        <td colspan="2" id="detalle2"><input name="tenstdvaloptenido" type="number" id="tenstdvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['tenstdvaloptenido']; ?>"></td>
        <td id="detalle1">ASTM D-882-02</td>
        <td id="detalle2">Newton</td>
      </tr>
      <tr>
        <td id="detalle1">Tensi&oacute;n MD</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['PtensionMd_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="tensmdvaloptenido" type="number" id="tensmdvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['tensmdvaloptenido']; ?>"></td>
        <td id="detalle1">ASTM D-882-02</td>
        <td id="detalle2">Newton</td>
      </tr>
      <tr>
        <td id="detalle1">Elongaci&oacute;n TD </td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['elongTd_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="elongtdvaloptenido" type="number" id="elongtdvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['elongtdvaloptenido']; ?>"></td>
        <td id="detalle1">ASTM D-882-02</td>
        <td id="detalle2">%</td>
      </tr>
      <tr>
        <td id="detalle1">Elongaci&oacute;n MD </td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['elongMd_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="elongmdvaloptenido" type="number" id="elongmdvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['elongmdvaloptenido']; ?>"></td>
        <td id="detalle1">ASTM D-882-02</td>
        <td id="detalle2">%</td>
      </tr>
      <tr>
        <td id="detalle1">Factor de Rompimiento TD</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['FrompTd_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="factortdvaloptenido" type="number" id="factortdvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['factortdvaloptenido']; ?>"></td>
        <td id="detalle1">ASTM D-882-02</td>
        <td id="detalle2">Mpa</td>
      </tr>
      <tr>
        <td id="detalle1">Factor de Rompimiento MD</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['FrompMd_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="factormdvaloptenido" type="number" id="factormdvaloptenido" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['factormdvaloptenido']; ?>"></td>
        <td id="detalle1">ASTM D-882-02</td>
        <td id="detalle2">Mpa</td>
      </tr>
      <tr>
        <td id="detalle1">Coeficiente Din&aacute;mico Cara/Cara</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CDinamCaraMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CDinamCaraMin_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="coefdimccval" type="number" id="coefdimccval" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['coefdimccval']; ?>"></td>
        <td id="detalle1">ASTM 1894</td>
        <td id="detalle2">N.A.</td>
      </tr>
      <tr>
        <td id="detalle1">Coeficiente Din&aacute;mico Dorso/Dorso</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CDinamDorsoMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CDinamDorsoMin_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="coefdimddval" type="number" id="coefdimddval" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['coefdimddval']; ?>"></td>
        <td id="detalle1">ASTM 1894</td>
        <td id="detalle2">N.A.</td>
      </tr>
      <tr>
        <td id="detalle1">Coeficiente Est&aacute;tico Cara/Cara</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CEstCaraMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CEstCaraMin_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="coefestccval" type="number" id="coefestccval" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['coefestccval']; ?>"></td>
        <td id="detalle1">ASTM 1894</td>
        <td id="detalle2">N.A.</td>
      </tr>
      <tr>
        <td id="detalle1">Coeficiente Est&aacute;tico Dorso/Dorso</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CEstDorsoMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['CEstDorsoMin_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="coefestddval" type="number" id="coefestddval" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['coefestddval']; ?>"></td>
        <td id="detalle1">ASTM 1894</td>
        <td id="detalle2">N.A.</td>
      </tr>
      <tr>
        <td id="detalle1">Impacto al Dardo</td>
        <td colspan="2" id="detalle2">&ge; <?php echo $row_ficha_tecnica['ImpacDardo_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="impatdval" type="number" id="impatdval" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['impatdval']; ?>"></td>
        <td id="detalle1">ASTM D-1709</td>
        <td id="detalle2">Gramos</td>
      </tr>
      <tr>
        <td id="detalle1">Tensi&oacute;n Superficial</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['TsuperfMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['TsuperfMin_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="tensionsval" type="number" id="tensionsval" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['tensionsval']; ?>"></td>
        <td id="detalle1">ASTM D-2578-09</td>
        <td id="detalle2">Dinas</td>
      </tr>
      <?php if($row_referencia['tipo_bolsa_ref']=='LAMINA'){ ?>
      <tr>
        <td id="detalle1">Temperatura de Selle</td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['TselleMax_ft'];?></td>
        <td id="detalle2"><?php echo $row_ficha_tecnica['TselleMin_ft'];?></td>
        <td colspan="2" id="detalle2"><input name="tempeselleval" type="number" id="tempeselleval" placeholder="0,00" style="width:60px" min="0.00" step="0.01" value="<?php echo $row_certificacion['tempeselleval']; ?>"></td>
        <td id="detalle1">ASTM F 88</td>
        <td id="detalle2">&deg;C</td>
      </tr>
      <?php }?>
      <tr id="tr1">
        <td colspan="8" id="titulo2">ANALISIS DE CINTA DE SEGURIDAD</td>
      </tr>
      <!-- <tr>
        <td id="detalle1">REFERENCIA</td>
        <td id="detalle1"><input type="text" name="referencia" size="5" onBlur="conMayusculas(this)" value="<?php echo $row_certificacion['referencia']; ?>"></td>
        <td id="detalle1">LOTE N&deg;</td>
        <td colspan="2" id="detalle1"><input type="text" name="lotenum" size="5" onBlur="conMayusculas(this)" value="<?php echo $row_certificacion['lotenum']; ?>"></td>
        <td id="detalle1">ENSAYO N&deg;</td>
        <td id="detalle1"><input type="text" name="ensayonum" size="5" onBlur="conMayusculas(this)" value="<?php echo $row_certificacion['ensayonum']; ?>"></td>
      </tr> -->
      <tr id="tr1">
        <td colspan="2" id="detalle2">PARAMETROS</td>
        <td id="detalle2">CUMPLE</td>
        <td colspan="2" id="detalle2">NO CUMPLE</td>
        <td id="detalle2">METODO DE ENSAYO</td>
        <td id="detalle2">UNIDADES</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">APARIENCIA</td>
        <td id="detalle1"><input type="radio" name="aparcump" value="1" id="aparcump_1" <?php if (!(strcmp($row_certificacion['aparcump'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="aparcump" value="0" id="aparcump_0" <?php if (!(strcmp($row_certificacion['aparcump'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('aparcump',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">EVIDENCIA EN FRIO</td>
        <td id="detalle1"><input type="radio" name="evidfriocump" value="1" id="evidfriocump_1" <?php if (!(strcmp($row_certificacion['evidfriocump'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="evidfriocump" value="0" id="evidfriocump_0" <?php if (!(strcmp($row_certificacion['evidfriocump'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('evidfriocump',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">EVIDENCIA EN CALOR</td>
        <td id="detalle1"><input type="radio" name="evidcalorcump" value="1" id="evidcalorcump_1" <?php if (!(strcmp($row_certificacion['evidcalorcump'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="evidcalorcump" value="0" id="evidcalorcump_0" <?php if (!(strcmp($row_certificacion['evidcalorcump'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('evidcalorcump',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">EVIDENCIA SOLVENTES</td>
        <td id="detalle1"><input type="radio" name="evidsolvcumple" value="1" id="evidsolvcumple_1" <?php if (!(strcmp($row_certificacion['evidsolvcumple'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="evidsolvcumple" value="0" id="evidsolvcumple_0" <?php if (!(strcmp($row_certificacion['evidsolvcumple'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('evidsolvcumple',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">EVID. TEMP. AMBIENTE</td>
        <td id="detalle1"><input type="radio" name="evidtambcumple" value="1" id="evidtambcumple_1" <?php if (!(strcmp($row_certificacion['evidtambcumple'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="evidtambcumple" value="0" id="evidtambcumple_0" <?php if (!(strcmp($row_certificacion['evidtambcumple'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('evidtambcumple',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr id="tr1">
        <td colspan="8" id="titulo2">PARAMETROS DE EXTRUSION E IMPRESION</td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="detalle2">PARAMETROS</td>
        <td id="detalle2">CUMPLE</td>
        <td colspan="2" id="detalle2">NO CUMPLE</td>
        <td id="detalle2">METODO DE ENSAYO</td>
        <td id="detalle2">UNIDADES</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">PIGMENTACION</td>
        <td id="detalle1"><input type="radio" name="pigmcump" value="1" id="pigmcump_1" <?php if (!(strcmp($row_certificacion['pigmcump'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="pigmcump" value="0" id="pigmcump_0" <?php if (!(strcmp($row_certificacion['pigmcump'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('pigmcump',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">COLORES-TONALIDAD</td>
        <td id="detalle1"><input type="radio" name="colortoncump" value="1" id="colortoncump_1" <?php if (!(strcmp($row_certificacion['colortoncump'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="colortoncump" value="0" id="colortoncump_0" <?php if (!(strcmp($row_certificacion['colortoncump'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('colortoncump',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">TEXTOS</td>
        <td id="detalle1"><input type="radio" name="textcump" value="1" id="textcump_1" <?php if (!(strcmp($row_certificacion['textcump'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="textcump" value="0" id="textcump_0" <?php if (!(strcmp($row_certificacion['textcump'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('textcump',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr>
        <td colspan="2" id="detalle1">CODIGO DE BARRAS</td>
        <td id="detalle1"><input type="radio" name="codigbarcump" value="1" id="codigbarcump_1" <?php if (!(strcmp($row_certificacion['codigbarcump'],1))) {echo "checked=\"checked\"";} ?>></td>
        <td colspan="2" id="detalle1"><input type="radio" name="codigbarcump" value="0" id="codigbarcump_0" <?php if (!(strcmp($row_certificacion['codigbarcump'],0))) {echo "checked=\"checked\"";} ?>>
          <a href="javascript:limpiar('codigbarcump',0)">limpiar</a></td>
        <td id="detalle1">Interno</td>
        <td id="detalle1">N.A.</td>
      </tr>
      <tr id="tr1">
        <td colspan="7" id="titulo2">OBSERVACIONES</td>
      </tr>
      <tr>
        <td colspan="7" id="detalle1"><label for="observ"></label>
          <textarea name="observ" id="observ" cols="70" rows="2"><?php echo $row_certificacion['observ']; ?></textarea></td>
      </tr>
      <tr>
        <td colspan="7" id="detalle1"><p>Vida &Uacute;til: 12 a 18 meses m&aacute;ximo despu&eacute;s de fecha de producci&oacute;n.</p></td>
      </tr>
      <tr>
        <td colspan="7" id="dato1"><table border="0" id="tabla1" >
          <tr>
            <td id="fuente2">ESTADO CERT</td>
            <td id="fuente2">FECHA MODIF. </td>
            <td id="fuente2">MODIFICADO POR </td>
            <td id="fuente2">APROBO</td>
          </tr>
          <tr>
            <td><select name="estado">
              <option value="Activa" <?php if (!(strcmp("0", $row_certificacion['estado']))) {echo "selected=\"selected\"";} ?>>Activa</option>
              <option value="Inactiva" <?php if (!(strcmp("1", $row_certificacion['estado']))) {echo "selected=\"selected\"";} ?>>Inactiva</option>
            </select></td>
            <td><input name="fechamodifico" type="date" id="fechamodifico" value="<?php echo date("Y-m-d");?>" readonly></td>
            <td><input name="modifico" type="text" id="modifico" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" readonly></td>
            <td><input name="jefeplanta" type="text" id="jefeplanta" size="30" value="<?php echo $row_certificacion['jefeplanta'] ?>"></td>
          </tr>
        </table></td>
      </tr>
      <tr id="tr1">
        <td colspan="7" id="dato2"><input type="submit" value="GUARDAR CERTIFICACION"></td>
      </tr>
    </table>
    <input type="hidden" name="MM_update" value="form1">
  </form></td>
</tr>
</table>
</div>
<b class="spiffy"> <b class="spiffy5"></b> <b class="spiffy4"></b> <b class="spiffy3"></b> <b class="spiffy2"><b></b></b> <b class="spiffy1"><b></b></b></b>
</div>
</td>
</tr>
</table>
<p>&nbsp;</p>
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ultimo);

mysql_free_result($referencia);

mysql_free_result($clientes);

mysql_free_result($opref);

mysql_free_result($ficha_tecnica);

mysql_free_result($certificacion);

?>