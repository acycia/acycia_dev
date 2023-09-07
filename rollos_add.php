<?php require_once('Connections/conexion1.php'); ?>
<?php require_once('Connections/conexion1.php'); ?>
<?php require_once('Connections/conexion1.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO materia_prima_rollos (id_rollo, cod_rollo, nombre_rollo, ref_prod_rollo, presentacion_rollo, tipo_rollo, medida_rollo, ancho_rollo, calibre_rollo, tratamiento_rollo, observacion_rollo) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_rollo'], "int"),
                       GetSQLValueString($_POST['cod_rollo'], "text"),
                       GetSQLValueString($_POST['nombre_rollo'], "text"),
                       GetSQLValueString($_POST['ref_prod_rollo'], "int"),
                       GetSQLValueString($_POST['presentacion_rollo'], "text"),
                       GetSQLValueString($_POST['tipo_rollo'], "int"),
                       GetSQLValueString($_POST['medida_rollo'], "int"),
                       GetSQLValueString($_POST['ancho_rollo'], "double"),
                       GetSQLValueString($_POST['calibre_rollo'], "double"),
                       GetSQLValueString($_POST['tratamiento_rollo'], "text"),
                       GetSQLValueString($_POST['observacion_rollo'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "rollos_vista.php?id_rollo=" . $_POST['id_rollo'] . "";
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
$query_ultimo = "SELECT * FROM materia_prima_rollos ORDER BY id_rollo DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

mysql_select_db($database_conexion1, $conexion1);
$query_referencias = "SELECT * FROM referencia ORDER BY cod_ref ASC";
$referencias = mysql_query($query_referencias, $conexion1) or die(mysql_error());
$row_referencias = mysql_fetch_assoc($referencias);
$totalRows_referencias = mysql_num_rows($referencias);

mysql_select_db($database_conexion1, $conexion1);
$query_tipo = "SELECT * FROM tipo ORDER BY nombre_tipo ASC";
$tipo = mysql_query($query_tipo, $conexion1) or die(mysql_error());
$row_tipo = mysql_fetch_assoc($tipo);
$totalRows_tipo = mysql_num_rows($tipo);

mysql_select_db($database_conexion1, $conexion1);
$query_medida = "SELECT * FROM medida ORDER BY nombre_medida ASC";
$medida = mysql_query($query_medida, $conexion1) or die(mysql_error());
$row_medida = mysql_fetch_assoc($medida);
$totalRows_medida = mysql_num_rows($medida);
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
  </ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('cod_rollo','','R','nombre_rollo','','R','ref_prod_rollo','','R','tipo_rollo','','R','medida_rollo','','R','ancho_rollo','','R','calibre_rollo','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr>
            <td colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg" /></td>
            <td colspan="2" id="titulo2">ADD MATERIA PRIMA ( ROLLO ) </td>
          </tr>
          <tr>
            <td id="fuente2">Consecutivo N&deg; <?php $numero=$row_ultimo['id_rollo']+1; echo $numero; ?> 
              <input name="id_rollo" type="hidden" value="<?php echo $numero; ?>"></td>
            <td id="fuente2"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="rollos.php"><img src="images/r.gif" alt="ROLLOS" border="0" /></a><a href="rollos_busqueda.php"><img src="images/embudo.gif" style="cursor:hand;" alt="FILTRO" border="0" /></a><a href="rollos_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="O.C. (ROLLOS)" border="0" /></a></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">CODIGO DEL ROLLO </td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><input type="text" name="cod_rollo" value="" size="50"></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">NOMBRE DEL ROLLO </td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><input type="text" name="nombre_rollo" value="" size="50"></td>
          </tr>
          <tr>
            <td colspan="2" id="fuente1">REF. DEL PRODUCTO </td>
            <td id="fuente1">PRESENTACION DEL ROLLO </td>
            <td id="fuente1">TIPO</td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><select name="ref_prod_rollo">
              <option value="">SELECCIONE</option>
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
            <td id="dato1"><select name="presentacion_rollo" id="presentacion_rollo">
              <option value="N.A.">N.A.</option>
              <option value="Lamina">Lamina</option>
              <option value="Semitubular">Semitubular</option>
              <option value="Tubular">Tubular</option>
            </select></td>
            <td id="dato1"><select name="tipo_rollo">
              <option value="value">SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_tipo['id_tipo']?>"><?php echo $row_tipo['nombre_tipo']?></option>
              <?php
} while ($row_tipo = mysql_fetch_assoc($tipo));
  $rows = mysql_num_rows($tipo);
  if($rows > 0) {
      mysql_data_seek($tipo, 0);
	  $row_tipo = mysql_fetch_assoc($tipo);
  }
?>
                        </select></td>
          </tr>
          <tr>
            <td id="fuente1">ANCHO</td>
            <td id="fuente1">CALIBRE</td>
            <td id="fuente1">UNIDAD DE MEDIDA </td>
            <td id="fuente1">TRATAMIENTO</td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="ancho_rollo" value="" size="10"></td>
            <td id="dato1"><input type="text" name="calibre_rollo" value="" size="10"></td>
            <td id="dato1"><select name="medida_rollo">
              <option value="" >SELECCIONE</option>
              <?php
do {  
?>
              <option value="<?php echo $row_medida['id_medida']?>"><?php echo $row_medida['nombre_medida']?></option>
              <?php
} while ($row_medida = mysql_fetch_assoc($medida));
  $rows = mysql_num_rows($medida);
  if($rows > 0) {
      mysql_data_seek($medida, 0);
	  $row_medida = mysql_fetch_assoc($medida);
  }
?>
                        </select></td>
            <td id="dato1"><select name="tratamiento_rollo" id="tratamiento_rollo">
                  <option value="N.A.">N.A.</option>
                  <option value="1 cara">1 cara</option>
                  <option value="2 caras">2 caras</option>
                </select></td>
          </tr>
          <tr>
            <td colspan="4" id="fuente1">OBSERVACIONES</td>
            </tr>
          <tr>
            <td colspan="4" id="dato1"><textarea name="observacion_rollo" cols="80" rows="3"></textarea></td>
            </tr>
          <tr>
            <td colspan="4" id="dato2"><input type="submit" value="ADD ROLLO"></td>
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

mysql_free_result($referencias);

mysql_free_result($tipo);

mysql_free_result($medida);
?>