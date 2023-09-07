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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO TblIngresos (id_det_ing, oc_ing, id_insumo_ing, ingreso_ing, valor_und_ing, fecha_ing) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_det_ing'], "int"),
                       GetSQLValueString($_POST['oc_ing'], "int"),
                       GetSQLValueString($_POST['id_insumo_ing'], "int"),
                       GetSQLValueString($_POST['ingreso_ing'], "double"),
                       GetSQLValueString($_POST['valor_und_ing'], "double"),
                       GetSQLValueString($_POST['fecha_ing'], "date"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

//ACTUALIZA EL VALOR UNITARIO EN INSUMOS ESTO FUE AGREGADO RECIENTEMENTE
  $updateSQL = sprintf("UPDATE insumo SET valor_unitario_insumo=%s WHERE id_insumo=%s",
                       GetSQLValueString($_POST['valor_und_ing'], "double"),
                       GetSQLValueString($_POST['id_insumo_ing'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL, $conexion1) or die(mysql_error());
echo "<script type=\"text/javascript\">window.close();</script>";  
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

$colname_detalle = "-1";
if (isset($_GET['id_det'])) {
  $colname_detalle = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_detalle = sprintf("SELECT * FROM orden_compra_detalle WHERE id_det = %s", $colname_detalle);
$detalle = mysql_query($query_detalle, $conexion1) or die(mysql_error());
$row_detalle = mysql_fetch_assoc($detalle);
$totalRows_detalle = mysql_num_rows($detalle);

$colname_orden_compra = "-1";
if (isset($_GET['id_det'])) {
  $colname_orden_compra = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden_compra = sprintf("SELECT * FROM orden_compra_detalle, orden_compra WHERE orden_compra_detalle.id_det = '%s' AND orden_compra_detalle.n_oc_det = orden_compra.n_oc", $colname_orden_compra);
$orden_compra = mysql_query($query_orden_compra, $conexion1) or die(mysql_error());
$row_orden_compra = mysql_fetch_assoc($orden_compra);
$totalRows_orden_compra = mysql_num_rows($orden_compra);

$colname_inventario = "-1";
if (isset($_GET['id_det'])) {
  $colname_inventario = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_inventario = sprintf("SELECT SUM(TblIngresos.ingreso_ing) AS ingreso,SUM(TblIngresos.salida_ing) AS salida FROM orden_compra_detalle, TblIngresos WHERE orden_compra_detalle.id_det = %s AND orden_compra_detalle.id_insumo_det=TblIngresos.id_insumo_ing", $colname_inventario);
$inventario = mysql_query($query_inventario, $conexion1) or die(mysql_error());
$row_inventario = mysql_fetch_assoc($inventario);
$totalRows_inventario = mysql_num_rows($inventario);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
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
</ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="return detalle_ing()" >
        <table id="tabla2">
          <tr>
            <td colspan="4" id="subtitulo">ADD ENTRADA</td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">ORDEN DE COMPRA N&deg; <strong><?php echo $row_orden_compra['n_oc']; ?></strong><input name="oc_ing" type="hidden" value="<?php echo $row_orden_compra['n_oc']; ?>">
              <input type="hidden" name="fecha_ing" value="<?php echo date("Y-m-d");?>" size="5">
              <input name="id_insumo_ing" type="hidden" id="id_insumo_ing" value="<?php echo $row_detalle['id_insumo_det']; ?>" size="5"></td>
            <td colspan="2" id="fuente3"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a></td>
            </tr>
          
          <tr>
            <td colspan="4" id="fuente1"><strong>INSUMO</strong></td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><input name="id_det_ing" type="hidden" id="id_det_ing" value="<?php echo $row_detalle['id_det']; ?>">
			<?php 
			$insumo=$row_detalle['id_insumo_det'];
			$sqlins="SELECT descripcion_insumo,medida_insumo FROM insumo WHERE id_insumo ='$insumo'";
			$resultins= mysql_query($sqlins);
			$numins= mysql_num_rows($resultins);
			if($numins >='1')
			{ 
			$insumo_nombre = mysql_result($resultins,0,'descripcion_insumo');
			$insumo_medida = mysql_result($resultins,0,'medida_insumo');
			echo $insumo_nombre;
			 } ?> </td>
            </tr>

          <tr>
            <td id="dato1"><strong>INVENTARIO: </strong></td>
            <td id="dato1"><strong>CANT. SOLICITADA:</strong></td>
            <td id="dato1"><strong>INGRESO:</strong></td>
            <td id="dato1"><strong>VALOR EN 
              <?php $medida=$insumo_medida;
			if($medida!='')
			{
			$sqlmedida="SELECT nombre_medida FROM medida WHERE medida.id_medida ='$medida'";
			$resultmedida= mysql_query($sqlmedida);
			$numedida= mysql_num_rows($resultmedida);
			if($numedida >='1')
			{ 
			$nombre_medida = mysql_result($resultmedida,0,'nombre_medida');
			echo $nombre_medida;
			} } ?></strong></td>
            </tr>
          <tr>
            <td id="dato1"><?php $inventario=$row_inventario['ingreso'] - $row_inventario['salida']; echo $inventario;?>
            <input name="inventario" type="hidden" id="inventario" value="<?php echo $inventario;?>" size="5">
              </td>
            <td id="dato1"><input name="existente" type="hidden" id="existente" value="<?php echo $row_inventario['ingreso'];?>" size="5">
            <input name="cantidad" type="hidden" id="cantidad" value="<?php echo $row_detalle['cantidad_det']; ?>" size="5">
              <?php echo $row_detalle['cantidad_det']; ?></td>
            <td id="dato1"><input name="ingreso_ing" type="number" id="ingreso_ing" placeholder="0,00" style="width:100px" min="0.00" step="0.01" value="" required onBlur="detalle_ing()"></td>
            <td id="dato1"><input type="number" name="valor_und_ing" value="<?php echo $row_detalle['valor_unitario_det']; ?>"placeholder="0.0001" style="width:100px" min="0.0000" step="0.0001" required onBlur="detalle_ing()">
            <?php echo $row_detalle['moneda_det']; ?>
              </td>
          </tr>
          <tr>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">&nbsp;</td>
            <td id="dato1">TOTAL INVENTARIO:</td>
            <td id="dato1"><input type="number" name="total_det" value="" placeholder="0,00" style="width:100px" min="0.00" step="0.01" required onBlur="detalle_ing()"></td>
          </tr>

          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input type="submit" value="ADD ENTRADA"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
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

mysql_free_result($detalle);

mysql_free_result($inventario);
?>