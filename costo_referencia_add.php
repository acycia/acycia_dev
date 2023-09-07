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
	$codyvers=$_POST['codigo_cref']."-".$_POST['version_cref'];
  $insertSQL = sprintf("INSERT INTO TblCostoRef (id_cref, id_ref_cref, cod_cref ,codigo_cref, descripcion_cref, unidad_cref, cliente_cref, costo_und_cref,  responsable_cref, fecha_cref) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_cref'], "int"),
                       GetSQLValueString($_POST['id_ref_cref'], "int"),
					   GetSQLValueString($_POST['cod_cref'], "text"),
					   GetSQLValueString($codyvers, "text"),
					   GetSQLValueString($_POST['descripcion_cref'], "text"),
					   GetSQLValueString($_POST['unidad_cref'], "text"),
					   GetSQLValueString($_POST['cliente_cref'], "text"),
					   GetSQLValueString($_POST['costo_und_cref'], "double"),
                       GetSQLValueString($_POST['responsable_cref'], "text"),
                       GetSQLValueString($_POST['fecha_cref'], "date"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "costo_referencia_listado.php";
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
$query_ultimo = "SELECT * FROM TblCostoRef ORDER BY id_cref DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_ref = "SELECT * FROM Tbl_referencia ORDER BY cod_ref ASC";
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
?>
<html>
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
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="costos_generales.php">COSTOS GENERALES</a></li>
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1"><form action="<?php echo $editFormAction; ?>" method="POST" name="form1"  onSubmit="return validacion_todos_select()">
        <table id="tabla2">
          <tr id="tr1">
      <td nowrap id="codigo" width="25%">CODIGO : A3 - F02 </td>
      <td nowrap id="titulo2" width="50%">COSTO POR REFERENCIA</td>
      <td nowrap id="codigo" width="25%">VERSION : 1 </td>
    </tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td id="subtitulo">&nbsp;</td>			
            <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="costo_referencia_listado.php"><img src="images/opciones.gif" alt="LISTADO COSTO REF" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td id="numero2"> <?php $numero=$row_ultimo['id_cref']+1; ?>
              <input name="id_cref" type="hidden" value="<?php echo $numero; ?>">
            </strong></td>
            <td id="fuente1">&nbsp;</td>
          </tr>
          <tr>
            <td id="fuente1">FECHA </td>
            <td id="fuente1">RESPONSABLE</td>
            </tr>
          <tr>
            <td id="dato1"><input type="date" name="fecha_cref" value="<?php echo date("Y-m-d"); ?>" size="10"></td>
            <td id="dato1"><input name="responsable_cref" type="text" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="10" readonly></td>
            </tr>
          <tr>
            <td colspan="2" id="fuente1">REFERENCIA-VERSION</td>
            </tr>
          <tr>
            <td colspan="2" id="dato1"><select name="id_ref_cref" id="nombre" onChange="DatosGestiones('20','id_ref',form1.id_ref_cref.value);">
              <option value="0">SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_ref['id_ref']?>"><?php echo $row_ref['cod_ref']?></option>
              <?php
} while ($row_ref = mysql_fetch_assoc($ref));
  $rows = mysql_num_rows($ref);
  if($rows > 0) {
      mysql_data_seek($ref, 0);
	  $row_ref = mysql_fetch_assoc($ref);
  }
?>
            </select></td>
            </tr>
          <tr>
            <td colspan="3" id="fuente2"><div id="resultado"></div></td>
            </tr>
          <tr>
            <td colspan="3" id="dato2"><input type="submit" value="GUARDAR"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_insert" value="form1">
      </form></td>
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

mysql_free_result($ultimo);

mysql_free_result($ref);
?>