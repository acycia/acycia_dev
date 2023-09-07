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
  $updateSQL = sprintf("UPDATE TblCostoExportacionDetalle SET id_ref_det=%s, descripcion_det=%s, medida_det=%s, cantidad_det=%s, precio_unid_det=%s, valor_total_det=%s WHERE id_det=%s",
                       GetSQLValueString($_POST['id_ref_det_edit'], "int"),
                       GetSQLValueString($_POST['descripcion_det'], "text"),
                       GetSQLValueString($_POST['medida_det'], "text"),
                       GetSQLValueString($_POST['cantidad_det'], "double"),
                       GetSQLValueString($_POST['precio_unid_det'], "double"),
                       GetSQLValueString($_POST['valor_total_det'], "double"),
                       GetSQLValueString($_POST['id_det'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

echo "<script type=\"text/javascript\">window.opener.location.reload();</script>"; 
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

$colname_costoExp = "-1";
if (isset($_GET['id_det'])) {
  $colname_costoExp = (get_magic_quotes_gpc()) ? $_GET['id_det'] : addslashes($_GET['id_det']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_costoExp = sprintf("SELECT * FROM TblCostoExportacionDetalle WHERE id_det = '%s'", $colname_costoExp);
$costoExp = mysql_query($query_costoExp, $conexion1) or die(mysql_error());
$row_costoExp = mysql_fetch_assoc($costoExp);
$totalRows_costoExp = mysql_num_rows($costoExp);

$colname_referenciacliente = "-1";
if (isset($_GET['id_c_ce'])) {
  $colname_referenciacliente = (get_magic_quotes_gpc()) ? $_GET['id_c_ce'] : addslashes($_GET['id_c_ce']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT DISTINCT id_ref,cod_ref FROM cliente, Tbl_referencia, Tbl_cliente_referencia WHERE cliente.id_c=%s AND cliente.nit_c = Tbl_cliente_referencia.Str_nit AND Tbl_cliente_referencia.N_referencia=Tbl_referencia.cod_ref  ORDER BY Tbl_referencia.cod_ref DESC", $colname_referenciacliente);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

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
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="costo_exportacion_listado.php">FACTURAS</a></li>
</ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="POST" name="form1" onSubmit="MM_validateForm('id_ref_det_edit','','R','precio_unid_det','','R','valor_total_det','','R');return document.MM_returnValue" >
        <table id="tabla2">
          <tr>
            <td colspan="4" id="subtitulo">ADD ITEM </td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">FACTURA N&deg; <strong><?php echo $row_costoExp['n_ce_det']; ?></strong><input name="n_ce_det" type="hidden" value="<?php echo $row_costoExp['n_ce_det']; ?>">
              </td>
            <td colspan="2" id="fuente2"><a href="javascript:location.reload()"><img src="images/ciclo1.gif" alt="RESTAURAR"title="RESTAURAR" border="0" style="cursor:hand;"/></a><a href="costo_exportacion_listado.php"><img src="images/e.gif" alt="LISTADO FACTURAS" title="LISTADO FACTURAS" border="0" style="cursor:hand;"/></a></td>
            </tr>
          
          <tr>
            <td colspan="4" id="fuente1">REFERENCIA</td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><select name="id_ref_det_edit" onChange="DatosGestiones('18','id_ref_det_edit',form1.id_ref_det_edit.value);">
              <option value=""<?php if (!(strcmp("", $row_costoExp['id_ref_det']))) {echo "selected=\"selected\"";} ?>>SELECCIONE</option>
              <?php
				do {  
				?>
              <option value="<?php echo $row_referencia['id_ref']?>"<?php if (!(strcmp($row_referencia['id_ref'], $row_costoExp['id_ref_det']))) {echo "selected=\"selected\"";} ?>><?php echo $row_referencia['cod_ref']?></option>
              <?php
				} while ($row_referencia = mysql_fetch_assoc($referencia));
				  $rows = mysql_num_rows($referencia);
				  if($rows > 0) {
					  mysql_data_seek($referencia, 0);
					  $row_referencia = mysql_fetch_assoc($referencia);
				  }
				?>
                        </select></td>
            </tr>
          <tr>
            <td colspan="5" id="dato1"><div id="resultado"></div></td>
          </tr>
  
    <tr>
      <td id="fuente1">CANTIDAD</td>
      <td id="fuente1">TOTAL</td>
      <td id="fuente1">&nbsp;</td>
      <td id="fuente1">&nbsp;</td>
      </tr>
    <tr>
      <td id="dato1">
        <input type="number" name="cantidad_det" style="width:80px" min="0" step="0.0001" value="<?php echo $row_costoExp['cantidad_det'];?>" onChange="detalle_ce()">
     </td>
      <td id="dato1"><input type="number" name="valor_total_det" style="width:80px" min="0.00" step="0.01" value="<?php echo $row_costoExp['valor_total_det'];?>" onBlur="detalle_ce()"></td>
      <td id="dato1">&nbsp;</td>
      <td id="dato1">&nbsp;</td>       
    </tr>         
  <tr>
            <td colspan="4" id="dato1">DESCRIPCION</td>
            </tr>
            <tr>
            <td colspan="4" id="dato1"><textarea type="text" name="descripcion_det" cols="50" rows="2"><?php echo $row_costoExp['descripcion_det'];?></textarea> </td>
            </tr>       

          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input type="submit" value="ADD A LA EXPORTACION"></td>
            </tr>
        </table>
        <input name="id_det" type="hidden" value="<?php echo $row_costoExp['id_det']; ?>">
        <input type="hidden" name="MM_update" value="form1">
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

mysql_free_result($costoExp);

mysql_free_result($referencia);
?>