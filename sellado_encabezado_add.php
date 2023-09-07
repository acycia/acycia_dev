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
  $insertSQL = sprintf("INSERT INTO sellado_encabezado (id_encabezado, responsable_registro, fecha_registro, hora_registro, n_op, fecha_op, id_referencia, version_ref, id_maquina, unids_paquete, otro_unids_paquete, control_numeracion) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_encabezado'], "int"),
                       GetSQLValueString($_POST['responsable_registro'], "text"),
                       GetSQLValueString($_POST['fecha_registro'], "date"),
					   GetSQLValueString($_POST['hora_registro'], "text"),
                       GetSQLValueString($_POST['n_op'], "int"),
                       GetSQLValueString($_POST['fecha_op'], "date"),
                       GetSQLValueString($_POST['id_referencia'], "int"),
					   GetSQLValueString($_POST['version_ref'], "int"),
                       GetSQLValueString($_POST['id_maquina'], "int"),
                       GetSQLValueString($_POST['unids_paquete'], "int"),
					   GetSQLValueString($_POST['otro_unids_paquete'], "int"),
                       GetSQLValueString(isset($_POST['control_numeracion']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "sellado_encabezado_edit.php?id_encabezado=" . $_POST['id_encabezado'] . "";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING']; }*/
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
$query_ultimo = "SELECT * FROM sellado_encabezado ORDER BY id_encabezado DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM referencia order by id_ref desc";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

mysql_select_db($database_conexion1, $conexion1);
$query_maquinas = "SELECT * FROM maquina ORDER BY codigo_maquina ASC";
$maquinas = mysql_query($query_maquinas, $conexion1) or die(mysql_error());
$row_maquinas = mysql_fetch_assoc($maquinas);
$totalRows_maquinas = mysql_num_rows($maquinas);
?>
<?php date_default_timezone_set("America/Bogota"); ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body oncontextmenu="return false">
  <table id="tabla_formato"><tr><td>
    <div id="cabecera_formato"><div class="menu_formato"><ul>  
      <li><?php echo $row_usuario['nombre_usuario']; ?></li>
      <li><a href="sellado.php">LISTADO</a></li>
      <li><a href="produccion.php">PRODUCCION</a></li>  
      <li><a href="<?php echo $logoutAction ?>">SALIR</a></li>  
      </ul></div></div>
     </td></tr>
     <tr><td>
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('id_encabezado','','RisNum','responsable_registro','','R','fecha_registro','','R','n_op','','RisNum','fecha_op','','R','id_referencia','','R','version_ref','','RisNum','id_maquina','','R','unids_paquete','','R','otro_unids_paquete','','RisNum');return document.MM_returnValue">
        <table id="tabla_formato">
          <tr>
            <td id="codigo_formato">CODIGO : R4-F02</td>
            <td id="titulo_formato">REPORTE DE SELLADO N&ordm; 
              <input name="id_encabezado" type="text" id="id_encabezado" value="<?php $num=$row_ultimo['id_encabezado']+1; echo $num; ?>" size="8" maxlength="8"></td>
            <td id="codigo_formato">VERSION: 1</td>
          </tr>
          <tr>
            <td rowspan="2" id="logo_2"><img src="images/logoacyc.jpg" /></td>            
          </tr>
          <tr id="fondo_2">
            <td colspan="2" id="detalle">
               <table id="tabla_formato">
                 <tr><td colspan="6" id="subtitulo_1">ENCABEZADO</td></tr>
                 <tr>
                    <td colspan="2" id="fuente_2">N&ordm; O. P. </td>
                    <td id="fuente_2">FECHA O.P.</td>
                    <td id="fuente_2">REFERENCIA</td>
                    <td id="fuente_2">VERSION</td>
                    <td id="fuente_2">MAQUINA</td>
                  </tr>
                  <tr>
                    <td colspan="2" id="dato_2"><input name="n_op" type="text" size="10" maxlength="11" value=""/></td>
                    <td id="dato_2"><input name="fecha_op" type="text" value="<?php echo date("Y-m-d"); ?>" size="10" maxlength="10" /></td>
                    <td id="dato_2"><select name="id_referencia" onBlur="DatosGestiones('4','id_referencia',form1.id_referencia.value);">
        <option value="">*</option>
        <?php
do {  
?>
        <option value="<?php echo $row_referencias['id_ref']?>"><?php echo $row_referencias['cod_ref']?></option>
        <?php
} while ($row_referencias = mysql_fetch_assoc($referencias));
  $rows = mysql_num_rows($referencias);
  if($rows > 0) {
      mysql_data_seek($referencias, 0);
	  $row_referencias = mysql_fetch_assoc($referencias);
  }
?>
      </select></td>
    <td id="dato_2"><div id="resultado"></div></td>
    <td id="dato_2"><select name="id_maquina">
        <option value="">*</option>
        <?php
do {  
?>
        <option value="<?php echo $row_maquinas['id_maquina']?>"><?php echo $row_maquinas['codigo_maquina']?></option>
        <?php
} while ($row_maquinas = mysql_fetch_assoc($maquinas));
  $rows = mysql_num_rows($maquinas);
  if($rows > 0) {
      mysql_data_seek($maquinas, 0);
	  $row_maquinas = mysql_fetch_assoc($maquinas);
  }
?>
    </select></td>
    </tr>
  <tr>
    <td colspan="2" id="fuente_2">UNIDS x PAQ.</td>
    <td id="fuente_2">NUMERACION</td>
    <td colspan="2" id="fuente_2">REGISTRO</td>
    <td id="fuente_2">&nbsp;</td>
    </tr>
  <tr>
    <td nowrap id="dato_2"><select name="unids_paquete" id="unids_paquete" onBlur=" DatosGestiones('41','unids_paquete',form1.unids_paquete.value); ">
        <option>*</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="0">otro</option>
    </select></td>
    <td nowrap id="dato_2"><div id="resultado1"></div></td>
    <td id="dato_2"><input type="checkbox" name="control_numeracion" value="" /></td>
    <td colspan="2" id="detalle_2"><?php echo $row_usuario['nombre_usuario']; ?><input name="responsable_registro" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
      <?php echo date("Y-m-d"); ?><input name="fecha_registro" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
      <input name="hora_registro" type="hidden" id="hora_registro" value="<?php echo date("h:i a"); ?>" /></td>
    <td id="dato_2"><input name="submit" type="submit" value="ADICIONAR" /></td>
    </tr></table>    
    </td></tr>
</table>
<input type="hidden" name="MM_insert" value="form1"></form></td></tr></table>     
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ultimo);

mysql_free_result($referencias);

mysql_free_result($maquinas);
?>
