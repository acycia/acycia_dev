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
  $updateSQL = sprintf("UPDATE orden_compra_rollos SET id_p_ocr=%s, id_rollo_ocr=%s, id_ref_ocr=%s, pedido_ocr=%s WHERE n_ocr=%s",
                       GetSQLValueString($_POST['id_p_ocr'], "int"),
                       GetSQLValueString($_POST['id_rollo_ocr'], "int"),
                       GetSQLValueString($_POST['id_ref_ocr'], "int"),
                       GetSQLValueString($_POST['pedido_ocr'], "text"),
                       GetSQLValueString($_POST['n_ocr'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "rollos_oc_edit.php?n_ocr=" . $_POST['n_ocr'] . "";
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

$colname_rollo_oc = "-1";
if (isset($_GET['n_ocr'])) {
  $colname_rollo_oc = (get_magic_quotes_gpc()) ? $_GET['n_ocr'] : addslashes($_GET['n_ocr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_oc = sprintf("SELECT * FROM orden_compra_rollos WHERE n_ocr = %s", $colname_rollo_oc);
$rollo_oc = mysql_query($query_rollo_oc, $conexion1) or die(mysql_error());
$row_rollo_oc = mysql_fetch_assoc($rollo_oc);
$totalRows_rollo_oc = mysql_num_rows($rollo_oc);

mysql_select_db($database_conexion1, $conexion1);
$query_proveedores = "SELECT * FROM proveedor ORDER BY proveedor_p ASC";
$proveedores = mysql_query($query_proveedores, $conexion1) or die(mysql_error());
$row_proveedores = mysql_fetch_assoc($proveedores);
$totalRows_proveedores = mysql_num_rows($proveedores);

mysql_select_db($database_conexion1, $conexion1);
$query_rollos = "SELECT * FROM materia_prima_rollos ORDER BY nombre_rollo ASC";
$rollos = mysql_query($query_rollos, $conexion1) or die(mysql_error());
$row_rollos = mysql_fetch_assoc($rollos);
$totalRows_rollos = mysql_num_rows($rollos);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM Tbl_referencia order by id_ref desc";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);
?>
<html>
<head>
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
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form method="post" name="form1" action="<?php echo $editFormAction; ?>" onSubmit="MM_validateForm('pedido_ocr','','R','id_p_ocr','','R','id_rollo_ocr','','R','id_ref_ocr','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr id="tr1">            
            <td id="codigo" width="25%">CODIGO : A3 - F01 </td>
            <td colspan="2" id="titulo2" width="50%">ORDEN DE COMPRA </td>
            <td id="codigo" width="25%">VERSION : 1 </td>
		</tr>
          <tr>
            <td rowspan="4" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td colspan="2" id="subtitulo">MATERIA PRIMA ( ROLLOS )</td>
            <td id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="numero2"><strong>N&deg; <?php echo $row_rollo_oc['n_ocr']; ?></strong></td>
            <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="rollos_oc_edit.php?n_ocr=<?php echo $row_rollo_oc['n_ocr']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="rollos_oc_vista.php?n_ocr=<?php echo $row_rollo_oc['n_ocr']; ?>"><img src="images/hoja.gif" style="cursor:hand;" alt="VISTA IMPRESION" border="0" /></a><a href="rollos_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="O.C. ROLLOS" border="0" /></a><a href="rollos.php"><img src="images/r.gif" style="cursor:hand;" alt="ROLLOS" border="0" /></a></td>
          </tr>
          <tr>
            <td colspan="3" id="dato2">&nbsp;</td>
            </tr>
          <tr>
            <td colspan="3" id="justificacion">Antes de hacer cualquier modificacion tenga en cuenta que al realizar cualquier cambio de los datos principales de la Orden de Compra para los ROLLOS a su vez cambiaran los datos especificos relacionados a estos. Recuerde que todos los campos son obligatorios, para completar los datos especificos de la O.C. </td>
            </tr>
          
          <tr>
            <td colspan="4" id="fuente1">PROVEEDOR</td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><select name="id_p_ocr">
              <option value="" <?php if (!(strcmp("", $row_rollo_oc['id_p_ocr']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_proveedores['id_p']?>"<?php if (!(strcmp($row_proveedores['id_p'], $row_rollo_oc['id_p_ocr']))) {echo "selected=\"selected\"";} ?>><?php echo $row_proveedores['proveedor_p']?></option>
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
            <td colspan="4" id="fuente1">MATERIAL SOLICITADO </td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><select name="id_rollo_ocr">
              <option value="" <?php if (!(strcmp("", $row_rollo_oc['id_rollo_ocr']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rollos['id_rollo']?>"<?php if (!(strcmp($row_rollos['id_rollo'], $row_rollo_oc['id_rollo_ocr']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rollos['nombre_rollo']?></option>
              <?php
} while ($row_rollos = mysql_fetch_assoc($rollos));
  $rows = mysql_num_rows($rollos);
  if($rows > 0) {
      mysql_data_seek($rollos, 0);
	  $row_rollos = mysql_fetch_assoc($rollos);
  }
?>
                        </select></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">REFERENCIA DEL PRODUCTO </td>
            <td colspan="2" id="fuente1">CLASE DE PEDIDO </td>
            </tr>
          <tr>
            <td colspan="2" id="dato1"><select name="id_ref_ocr">
              <option value="" <?php if (!(strcmp("", $row_rollo_oc['id_ref_ocr']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_referencias['id_ref']?>"<?php if (!(strcmp($row_referencias['id_ref'], $row_rollo_oc['id_ref_ocr']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencias['cod_ref']?></option>
              <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
                        </select></td>
            <td colspan="2" id="dato1"><select name="pedido_ocr">
              <option value="Nuevo" <?php if (!(strcmp("Nuevo", $row_rollo_oc['pedido_ocr']))) {echo "selected=\"selected\"";} ?>>Nuevo</option>
              <option value="Repeticion" <?php if (!(strcmp("Repeticion", $row_rollo_oc['pedido_ocr']))) {echo "selected=\"selected\"";} ?>>Repeticion</option>
            </select></td>
            </tr>
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input name="submit" type="submit" value="Actualizar O.C."></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="n_ocr" value="<?php echo $row_rollo_oc['n_ocr']; ?>">
      </form></td>
  </tr>
  <tr>
    <td colspan="2" align="center" id="linea1">&nbsp;</td>
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

mysql_free_result($rollo_oc);

mysql_free_result($proveedores);

mysql_free_result($rollos);

mysql_free_result($referencias);
?>