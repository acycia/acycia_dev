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
  $insertSQL = sprintf("INSERT INTO lamina (id_lamina, codigo_usuario, fecha_registro, responsable, ancho, cantidad, calibre, tipo_extrusion, pigmento_exterior, pigmento_interior, peso_maximo, embobinado, tratamiento_corona, color1, pantone1, ubicacion1, color2, pantone2, ubicacion2, color3, pantone3, ubicacion3, color4, pantone4, ubicacion4, color5, pantone5, ubicacion5, color6, pantone6, ubicacion6, observaciones) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_lamina'], "int"),
                       GetSQLValueString($_POST['codigo_usuario'], "text"),
                       GetSQLValueString($_POST['fecha_registro'], "date"),
                       GetSQLValueString($_POST['responsable'], "text"),
                       GetSQLValueString($_POST['ancho'], "double"),
                       GetSQLValueString($_POST['cantidad'], "double"),
                       GetSQLValueString($_POST['calibre'], "double"),
                       GetSQLValueString($_POST['tipo_extrusion'], "text"),
                       GetSQLValueString($_POST['pigmento_exterior'], "text"),
                       GetSQLValueString($_POST['pigmento_interior'], "text"),
                       GetSQLValueString($_POST['peso_maximo'], "double"),
                       GetSQLValueString($_POST['embobinado'], "int"),
                       GetSQLValueString($_POST['tratamiento_corona'], "text"),
                       GetSQLValueString($_POST['color1'], "text"),
                       GetSQLValueString($_POST['pantone1'], "text"),
                       GetSQLValueString($_POST['ubicacion1'], "text"),
                       GetSQLValueString($_POST['color2'], "text"),
                       GetSQLValueString($_POST['pantone2'], "text"),
                       GetSQLValueString($_POST['ubicacion2'], "text"),
                       GetSQLValueString($_POST['color3'], "text"),
                       GetSQLValueString($_POST['pantone3'], "text"),
                       GetSQLValueString($_POST['ubicacion3'], "text"),
                       GetSQLValueString($_POST['color4'], "text"),
                       GetSQLValueString($_POST['pantone4'], "text"),
                       GetSQLValueString($_POST['ubicacion4'], "text"),
                       GetSQLValueString($_POST['color5'], "text"),
                       GetSQLValueString($_POST['pantone5'], "text"),
                       GetSQLValueString($_POST['ubicacion5'], "text"),
                       GetSQLValueString($_POST['color6'], "text"),
                       GetSQLValueString($_POST['pantone6'], "text"),
                       GetSQLValueString($_POST['ubicacion6'], "text"),
                       GetSQLValueString($_POST['observaciones'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "egp_lamina_vista.php";
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
$query_lamina = "SELECT * FROM lamina ORDER BY id_lamina DESC";
$lamina = mysql_query($query_lamina, $conexion1) or die(mysql_error());
$row_lamina = mysql_fetch_assoc($lamina);
$totalRows_lamina = mysql_num_rows($lamina);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tabla1" align="center">
  <tr>
    <td><div id="cabecera"></div>
        <div id="menuh">
          <ul>
            <li><a href="menu.php">MENU PRINCIPAL</a></li>
            <li><a href="comercial.php">GESTION COMERCIAL</a></li>
			<li><a href="egp_menu.php">MENU EGP</a></li>			            
            <li><a href="egp_lamina.php">LISTADO EGP - LAMINA</a></li>
			<li><a href="egp_lamina_add.php">RESTAURAR</a></li>			
			<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
          </ul>
        </div></td>
  </tr>
</table>
</div>
<div align="center">
  <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <table id="tabla2">
      <tr>
        <td id="codigo">Codigo : R1 - F08 </td>
        <td id="gestion">EGP - LAMINA </td>
        <td id="codigo">Versi&oacute;n : 2 </td>
      </tr>
      <tr>
        <td rowspan="6" id="logo"><img src="images/logoacyc.jpg" /></td>
        <td colspan="2" id="usuario2">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">EGP - LAMINA N&deg; </td>
        <td id="fuente1">Fecha Registro </td>
      </tr>
      <tr>
        <td id="dato"><input name="id_lamina" type="hidden" value="<?php $num=$row_lamina['id_lamina']+1; echo $num; ?>" />
            <?php echo $num; ?></td>
        <td id="dato"><input type="text" name="fecha_registro" value="<?php echo date("Y/m/d"); ?>" size="10" /></td>
      </tr>
      <tr>
        <td id="fuente1">Responsable del Registro</td>
        <td id="fuente1">Tipo de Usuario </td>
      </tr>
      <tr>
        <td id="dato"><input name="responsable" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" /><?php echo $row_usuario['nombre_usuario']; ?></td>
        <td id="dato"><input name="codigo_usuario" type="hidden" value="<?php echo $row_usuario['codigo_usuario']; ?>" />
        <?php $codigo=$row_usuario['codigo_usuario']; if($codigo=='ACYCIA') { echo "ACYCIA"; } else { echo "CLIENTE COMERCIAL"; } ?></td>
      </tr>
      <tr>
        <td id="dato">&nbsp;</td>
        <td id="dato">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">Ancho</td>
        <td id="fuente1">Cantidad</td>
        <td id="fuente1">Calibre</td>
      </tr>
      <tr>
        <td id="dato"><input type="text" name="ancho" value="" size="20" /></td>
        <td id="dato"><input type="text" name="cantidad" value="" size="20" /></td>
        <td id="dato"><input type="text" name="calibre" value="" size="20" /></td>
      </tr>
      <tr>
        <td colspan="3" id="subtitulo">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" id="logo">TIPO DE EXTRUSION</td>
      </tr>
      <tr>
        <td colspan="3" id="logo">
		<table id="tabla3">
          <tr>
            <td id="logo"><img src="images/extrusionA.jpg"/></td>
            <td id="logo"><img src="images/extrusionB.jpg"/></td>
            <td id="logo"><img src="images/extrusionC.jpg"/></td>
            <td id="logo"><img src="images/extrusionD.jpg"/></td>
          </tr>
          <tr>
            <td id="dato"><input name="tipo_extrusion" type="radio" value="A" checked="checked" /></td>
            <td id="dato"><input name="tipo_extrusion" type="radio" value="B" /></td>
            <td id="dato"><input name="tipo_extrusion" type="radio" value="C" /></td>
            <td id="dato"><input name="tipo_extrusion" type="radio" value="D" /></td>
          </tr>
        </table></td>
      </tr>

      <tr>
        <td colspan="3" id="subtitulo">&nbsp;</td>
      </tr>
      <tr>
        <td id="fuente1">Pigmento Exterior </td>
        <td id="fuente1">Pigmento Interior </td>
        <td id="fuente1">Peso Maximo del Rollo </td>
      </tr>
      <tr>
        <td id="dato"><input type="text" name="pigmento_exterior" value="" size="20" /></td>
        <td id="dato"><input type="text" name="pigmento_interior" value="" size="20" /></td>
        <td id="dato"><input type="text" name="peso_maximo" value="" size="20" /></td>
      </tr>
      <tr>
        <td colspan="3" id="subtitulo">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3" id="logo">TIPO DE EMBOBINADO </td>
      </tr>

      <tr>
        <td colspan="3" id="logo"><table id="tabla3">
          <tr>
            <td id="logo"><img src="images/embobinado1.jpg"/></td>
            <td id="logo"><img src="images/embobinado2.jpg"/></td>
            <td id="logo">&nbsp;</td>
            <td id="logo">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato"><input name="embobinado" type="radio" value="1" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="2" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="3" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="4" /></td>
          </tr>
          <tr>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato"><input name="embobinado" type="radio" value="5" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="6" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="7" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="8" /></td>
          </tr>
          <tr>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato"><input name="embobinado" type="radio" value="9" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="10" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="11" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="12" /></td>
          </tr>
          <tr>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
            <td id="dato">&nbsp;</td>
          </tr>
          <tr>
            <td id="dato"><input name="embobinado" type="radio" value="13" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="14" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="15" /></td>
            <td id="dato"><input name="embobinado" type="radio" value="16" /></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="3" id="subtitulo">&nbsp;</td>
      </tr>
      <tr>
        <td id="dato">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>

      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="tratamiento_corona" value="" size="20" /></td>
        <td>Tratamiento_corona:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="color1" value="" size="20" /></td>
        <td>Color1:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="pantone1" value="" size="20" /></td>
        <td>Pantone1:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="ubicacion1" value="" size="20" /></td>
        <td>Ubicacion1:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="color2" value="" size="20" /></td>
        <td>Color2:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="pantone2" value="" size="20" /></td>
        <td>Pantone2:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="ubicacion2" value="" size="20" /></td>
        <td>Ubicacion2:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="color3" value="" size="20" /></td>
        <td>Color3:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="pantone3" value="" size="20" /></td>
        <td>Pantone3:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="ubicacion3" value="" size="20" /></td>
        <td>Ubicacion3:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="color4" value="" size="20" /></td>
        <td>Color4:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="pantone4" value="" size="20" /></td>
        <td>Pantone4:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="ubicacion4" value="" size="20" /></td>
        <td>Ubicacion4:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="color5" value="" size="20" /></td>
        <td>Color5:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="pantone5" value="" size="20" /></td>
        <td>Pantone5:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="ubicacion5" value="" size="20" /></td>
        <td>Ubicacion5:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="color6" value="" size="20" /></td>
        <td>Color6:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="pantone6" value="" size="20" /></td>
        <td>Pantone6:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="ubicacion6" value="" size="20" /></td>
        <td>Ubicacion6:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="text" name="observaciones" value="" size="20" /></td>
        <td>Observaciones:</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Insertar registro" /></td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <input type="hidden" name="MM_insert" value="form1">
  </form>
  </div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($lamina);
?>
