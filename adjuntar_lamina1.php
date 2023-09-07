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
?><?php
$colname_usuario_comercial = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

$colname_ver_nuevo = "-1";
if (isset($_GET['id_verif_l'])) {
  $colname_ver_nuevo = (get_magic_quotes_gpc()) ? $_GET['id_verif_l'] : addslashes($_GET['id_verif_l']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ver_nuevo = sprintf("SELECT * FROM Tbl_verificacion_lamina WHERE id_verif_l = %s", $colname_ver_nuevo);
$ver_nuevo = mysql_query($query_ver_nuevo, $conexion1) or die(mysql_error());
$row_ver_nuevo = mysql_fetch_assoc($ver_nuevo);
$totalRows_ver_nuevo = mysql_num_rows($ver_nuevo);

$colname_referencia = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref = %s", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);
session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tabla"><tr align="center"><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr><td align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="referencias_l.php">REFERENCIAS</a></li>
</ul>
<div id="nombreusuario"><?php echo $row_usuario_comercial['nombre_usuario']; ?></div>
</td>
</tr>
  <tr>
    <td id="subtitulo">&nbsp;</td>
  </tr>
  <tr>
    <td id="subtitulo">ADJUNTAR ARTE</td>
  </tr>
  <tr>
    <td id="numero2">Recuerde que el archivo debe ser de extensi&oacute;n .pdf</td>
  </tr>
  <tr>
    <td id="fuente2">&nbsp;</td>
  </tr>  
  <tr>
    <td align="center">
	<form action="adjuntar_lamina2.php" method="post" enctype="multipart/form-data">
	<div align="center">
	<table width="50%">
      <tr id="tr1">
        <td width="25%" id="titulo4">REFERENCIA </td>
        <td width="25%" id="titulo4"> VERIFICACION N&ordm;</td>
      </tr>
      <tr id="tr3">
        <td id="dato2"><?php echo $row_referencia['cod_ref']; ?>
          <input name="id_ref" type="hidden" id="id_ref" value="<?php echo $row_referencia['id_ref']; ?>" />
- <?php echo $row_referencia['version_ref']; ?></td>
        <td id="dato2"><?php echo $row_ver_nuevo['id_verif_l']; ?>
          <input name="id_verif_l" type="hidden" id="id_verif_l" value="<?php echo $_GET['id_verif_l']; ?>" /></td>
      </tr>
      <tr id="tr1">
        <td id="titulo4">ARTE</td>
        <td id="titulo4">VERSION M. </td>
      </tr>
      <tr id="tr3">
        <td id="dato2"><input name="arte" type="hidden" id="arte" value="<?php echo $row_ver_nuevo['userfile_l']; ?>" />
          <?php echo $row_ver_nuevo['userfile_l']; ?></td>
        <td id="dato2"><?php echo $row_ver_nuevo['version_ref_verif_l']; ?></td>
      </tr>
      <tr id="tr1">
        <td id="titulo4">Estado del Arte          </td>
        <td id="titulo4">Fecha Aprobacion</td>
      </tr>
      <tr id="tr3">
        <td id="dato2"><select name="estado_arte_verif_l">
          <option value="0" <?php if (!(strcmp(0, $row_ver_nuevo['estado_arte_verif_l']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
          <option value="1" <?php if (!(strcmp(1, $row_ver_nuevo['estado_arte_verif_l']))) {echo "selected=\"selected\"";} ?>>Rechazado</option>
          <option value="2" <?php if (!(strcmp(2, $row_ver_nuevo['estado_arte_verif_l']))) {echo "selected=\"selected\"";} ?>>Aceptado</option>
          <option value="3" <?php if (!(strcmp(3, $row_ver_nuevo['estado_arte_verif_l']))) {echo "selected=\"selected\"";} ?>>Anulado</option>
        </select></td>
        <td id="dato2">DD/MM/AA          
          <input name="fecha_aprob_arte_verif_l" type="date" value="<?php echo $row_ver_nuevo['fecha_aprob_arte_verif_l']; ?>" size="10" /></td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="titulo4">ADJUNTAR ARTE </td>
        </tr>
      <tr id="tr3">
        <td colspan="2" id="dato2"><input type="hidden" name="MAX_FILE_SIZE" value="10485770" />
          <input name="userfile_l" type="file" size="40" /></td>
        </tr>
      <tr id="tr3">
        <td colspan="2" id="dato2"><input name="submit" type="submit" value="Adjuntar" /></td>
        </tr>
    </table>
	</div>
	</form></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td id="dato2"><a href="verificacion_lamina_vista.php?id_verif_l=<?php echo $row_ver_nuevo['id_verif_l']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="IMPRESION" border="0" style="cursor:hand;" /></a><a href="verificacion_lamina_edit.php?id_verif_l=<?php echo $row_ver_nuevo['id_verif_l']; ?>"><img src="images/menos.gif" alt="EDITAR VERIFICACION" title="EDITAR VERIFICACION" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_lamina.php?id_ref=<?php echo $row_ver_nuevo['id_ref_verif_l']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VIRIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion_l.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
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
mysql_free_result($usuario_comercial);

mysql_free_result($ver_nuevo);

mysql_free_result($referencia);
?>
