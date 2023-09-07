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
$colname_usuario_comercial = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario_comercial = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario_comercial = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario_comercial);
$usuario_comercial = mysql_query($query_usuario_comercial, $conexion1) or die(mysql_error());
$row_usuario_comercial = mysql_fetch_assoc($usuario_comercial);
$totalRows_usuario_comercial = mysql_num_rows($usuario_comercial);

session_start(); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table id="tabla">
  <tr align="center">
    <td><div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla"><tr><td align="center">
<div id="cabecera"><img src="images/cabecera.jpg"></div>
<div id="cabezamenu">
<ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="referencias.php">REFERENCIAS</a></li>
</ul>
</div>
<div id="nombreusuario"><?php echo $row_usuario_comercial['nombre_usuario']; ?></div>
</td>
</tr>
  <tr>
    <td id="subtitulo">&nbsp;</td>
  </tr>
  <tr>
    <td id="subtitulo">RESULTADOS DE ARCHIVOS ADJUNTOS </td>
  </tr>
  <tr>
    <td id="subtitulo"></td>
  </tr>
  <tr>
    <td>
	<?php $id_verif_l = $_POST["id_verif_l"];
	$id_ref = $_POST["id_ref"];
	$fecha_aprob_arte_verif_l = $_POST["fecha_aprob_arte_verif_l"];
	$estado_arte_verif_l = $_POST["estado_arte_verif_l"]; 
	$nombre_archivo_l = $_FILES['userfile_l']['name'];
	$tipo_archivo_l = $_FILES['userfile_l']['type'];
	$tamano_archivo_l = $_FILES['userfile_l']['size'];
	$arte= $_POST["arte"]; 	
	if (!((strpos($tipo_archivo_l, "pdf")) && ($tamano_archivo_l < 30485770))) 
	{ ?>
	<div id="numero2"> <img src="images/por.gif" /> <?php echo "La extensión o el tamaño de los archivos no es correcta. <br><br>Se permiten archivos .pdf y se permiten archivos de 10 MB máximo o 10000 KB."; ?> </div> 
	<?php  } else{
	if($arte != '')
	{
	   if (file_exists("archivo/".$arte))
	   { 
	   unlink("archivo/".$arte);
	   }	   
	} 
	if (move_uploaded_file($_FILES['userfile_l']['tmp_name'], "archivo/".$nombre_archivo_l))  
	{  ?> <div id="acceso2"> <img src="images/cliente.gif" /> <?php
	echo "El archivo ha sido cargado correctamente."; ?> </div>	</td>
  </tr>
  <tr>
    <td id="fuente2"><?php
	   mysql_select_db($database_conexion1, $conexion1);
	   $sql1="UPDATE Tbl_verificacion_lamina SET userfile_l= '$nombre_archivo_l', fecha_aprob_arte_verif_l= '$fecha_aprob_arte_verif_l', estado_arte_verif_l='$estado_arte_verif_l' WHERE id_verif_l='$id_verif_l'";
	   $result1=mysql_query($sql1);	   
	   $sw=1;
	   echo "Se adjunto el arte   ";
    }
	else
	{  echo "Ocurrió algún error al subir el fichero. <br><br> No pudo guardarse."; 
	   $sw=0;
    } 
} 
if($sw=='1')
	  {
	  echo $nombre_archivo_l;
	  }
	  if($sw=='0')
	  {
	  echo $arte;
	  } ?></td>
  </tr>
  <tr>
    <td id="subtitulo">&nbsp;</td>
  </tr>
  <tr>
    <td id="subtitulo"><a href="adjuntar_lamina1.php?id_verif_l=<?php echo $_POST['id_verif_l']; ?>&amp;id_ref=<?php echo $_POST['id_ref']; ?>"><img src="images/clip.gif" alt="ADJUNTAR OTRO" title="ADJUNTAR OTRO" border="0" style="cursor:hand;" /></a><a href="verificacion_lamina_vista.php?id_verif_l=<?php echo $_POST['id_verif_l']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" style="cursor:hand;" /></a><a href="verificacion_lamina_edit.php?id_verif_l=<?php echo $_POST['id_verif_l']; ?>"><img src="images/menos.gif" alt="EDITAR VERIFICACION" title="EDITAR VERIFICACION" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia_lamina.php?id_ref=<?php echo $_POST['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion_l.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a></td>
  </tr>
  <tr>
    <td id="subtitulo">&nbsp;</td>
  </tr>
	<tr>
	<td>&nbsp;</td>
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
?>
