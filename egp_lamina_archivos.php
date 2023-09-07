<?php require_once('Connections/conexion1.php'); ?>
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

/*------------------------------------------------------------------------*/
/*INSERTAR egl_archivos*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) 
{
	/*PRIMERO: ADJUNTAR ARCHIVO*/
	if (isset($_FILES['archivo']) && $_FILES['archivo']['name'] != "") 
	{
		$directorio = "egplamina/";
		$nombre = $_FILES['archivo']['name'];
		$archivo_temporal = $_FILES['archivo']['tmp_name'];
		if (!copy($archivo_temporal,$directorio.$nombre)) 
		 { $error = "Error al enviar el archivo"; } else { $imagen = "egplamina/".$nombre; }
	}	
	/*INSERTAR DATOS*/
  $insertSQL = sprintf("INSERT INTO egl_archivos (id_archivo, n_egl, archivo) VALUES (%s, %s, '$nombre')",
                       GetSQLValueString($_POST['id_archivo'], "int"),
                       GetSQLValueString($_POST['n_egl'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());
  $insertGoTo = "egp_lamina_archivos.php?n_egl=" . $_POST['n_egl'] . "";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));
}
/*--------------------------------------------------------*/
/*UPDATE  egl_archivos */
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
	/*ARCHIVO ADJUNTO*/	
	if (isset($_FILES['archivo']) && $_FILES['archivo']['name'] != "") {
		$nombre1=$_POST['arte1'];
		if($nombre1 != ''){ if(file_exists("egplamina/".$nombre1)) { unlink("egplamina/".$nombre1);	} }
		$directorio = "egplamina/";
		$nombre = $_FILES['archivo']['name'];
		$archivo_temporal = $_FILES['archivo']['tmp_name'];
		copy($archivo_temporal,$directorio.$nombre);
	}	
	/*UPDATE DE TABLA egl_archivos*/
	$updateSQL = sprintf("UPDATE egl_archivos SET n_egl=%s, archivo='$nombre' WHERE id_archivo=%s",
                       GetSQLValueString($_POST['n_egl'], "int"),					   
                       GetSQLValueString($_POST['id_archivo'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "egp_lamina_archivos.php?n_egl=" . $_GET['n_egl'] . "";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
}
/*--------------------------------------------------------*/
$colname_archivos = "-1";
if (isset($_GET['n_egl'])) {
  $colname_archivos = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_archivos = sprintf("SELECT * FROM egl_archivos WHERE n_egl = %s", GetSQLValueString($colname_archivos, "int"));
$archivos = mysql_query($query_archivos, $conexion1) or die(mysql_error());
$row_archivos = mysql_fetch_assoc($archivos);
$totalRows_archivos = mysql_num_rows($archivos);

$colname_edit_archivo = "-1";
if (isset($_GET['id_archivo'])) {
  $colname_edit_archivo = $_GET['id_archivo'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_edit_archivo = sprintf("SELECT * FROM egl_archivos WHERE id_archivo = %s", GetSQLValueString($colname_edit_archivo, "int"));
$edit_archivo = mysql_query($query_edit_archivo, $conexion1) or die(mysql_error());
$row_edit_archivo = mysql_fetch_assoc($edit_archivo);
$totalRows_edit_archivo = mysql_num_rows($edit_archivo);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM egl_archivos ORDER BY id_archivo DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);
 
/*FECHA ACTUAL DEL PAIS*/
date_default_timezone_set("America/Bogota"); 
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body oncontextmenu="return false">
<table id="tabla_formato">    
    <tr>
      <td colspan="2" id="subtitulo_2">ARCHIVOS ADJUNTOS ( EGL # <?php echo $_GET['n_egl']; ?> ) </td>
    </tr>
  <tr>
      <td colspan="2" id="subtitulo_2"></td>
    </tr>
    <?php if($row_archivos['id_archivo']!='') { ?>    
    <?php do { ?>
      <tr>
        <td id="detalle_1"><a href="javascript:verFoto('egplamina/<?php echo $row_archivos['archivo']; ?>','610','490')"><?php echo $row_archivos['archivo']; ?></a></td>
        <td id="detalle_2"><a href="egp_lamina_archivos.php?id_archivo=<?php echo $row_archivos['id_archivo']; ?>&n_egl=<?php echo $row_archivos['n_egl']; ?>"><img src="images/menos.gif" alt="CAMBIAR ARCHIVO" border="0" style="cursor:hand;"></a><a href="javascript:eliminar2('id_color',<?php echo $row_colores['id_color']; ?>,'egp_lamina_colores.php','n_egl',<?php echo $row_colores['n_egl']; ?>)"></a><a href="javascript:eliminar2('id_archivo',<?php echo $row_archivos['id_archivo']; ?>,'egp_lamina_archivos.php','n_egl',<?php echo $row_archivos['n_egl']; ?>)"><img src="images/por.gif" border="0" style="cursor:hand;" alt="ELIMINAR" /></a></td>
      </tr>
      <?php } while ($row_archivos = mysql_fetch_assoc($archivos)); ?>
<?php }?>    
    <tr>
      <td colspan="2">&nbsp;</td>
  </tr>
    <?php if($_GET['add']=='1' && $_GET['n_egl'] != '') { ?>
    <form method="post" name="form1" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data"onsubmit="MM_validateForm('archivo','','R');return document.MM_returnValue">
    <tr>
      <td id="dato_2"><input name="id_archivo" type="hidden" value="<?php $num=$row_ultimo['id_archivo']; $ultimo_num=$num+1; echo $ultimo_num;?>">
      <input name="n_egl" type="hidden" value="<?php echo $_GET['n_egl']; ?>">
      <input name="archivo" type="file"  value="" size="20"></td>
      <td id="dato_2"><input type="submit" value="ADD"></td>
    </tr>
    <input type="hidden" name="MM_insert" value="form1">
    </form>
    <?php } ?>
    <?php if($_GET['id_archivo'] != '' && $_GET['n_egl'] != '') { ?>
    <form method="post" name="form2" action="<?php echo $editFormAction; ?>" enctype="multipart/form-data"onsubmit="MM_validateForm('archivo','','R');return document.MM_returnValue">
    <tr>
      <td id="dato_2">
      <input name="arte1" type="hidden" id="arte1" value="<?php echo $row_edit_archivo['archivo']; ?>" />
      <?php echo $row_edit_archivo['archivo']; ?></td>
      <td id="dato_2">&nbsp;</td>
    </tr>
    <tr>
      <td id="dato_2">
      <input name="id_archivo" type="hidden" value="<?php echo $row_edit_archivo['id_archivo']; ?>">
      <input name="n_egl" type="hidden" value="<?php echo $row_edit_archivo['n_egl']; ?>">
        <input name="archivo" type="file"  value="" size="20">
      </td>
      <td id="dato_2"><input type="submit" value="EDIT"></td>
    </tr>
    <input type="hidden" name="MM_update" value="form2">    
    </form>
    <?php } ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" id="dato_2"><a href="egp_lamina_archivos.php?add=1&n_egl=<?php echo $_GET['n_egl']; ?>"><img src="images/mas.gif" alt="ADD ARCHIVO" border="0" style="cursor:hand;"></a><a href="egp_lamina_archivos.php?n_egl=<?php echo $_GET['n_egl']; ?>"><img src="images/ciclo1.gif"  alt="RESTAURAR" border="0" style="cursor:hand;"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
    </tr>
</table>
 
</body>
</html>
<?php
mysql_free_result($archivos);

mysql_free_result($edit_archivo);

mysql_free_result($ultimo);
?>
