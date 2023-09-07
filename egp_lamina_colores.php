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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO egl_colores (id_color, n_egl, color, pantone, ubicacion) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_color'], "int"),
                       GetSQLValueString($_POST['n_egl'], "int"),
                       GetSQLValueString($_POST['color'], "text"),
                       GetSQLValueString($_POST['pantone'], "text"),
                       GetSQLValueString($_POST['ubicacion'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "egp_lamina_colores.php?n_egl=" . $_GET['n_egl'] . "";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE egl_colores SET n_egl=%s, color=%s, pantone=%s, ubicacion=%s WHERE id_color=%s",
                       GetSQLValueString($_POST['n_egl'], "int"),
                       GetSQLValueString($_POST['color'], "text"),
                       GetSQLValueString($_POST['pantone'], "text"),
                       GetSQLValueString($_POST['ubicacion'], "text"),
                       GetSQLValueString($_POST['id_color'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "egp_lamina_colores.php?n_egl=" . $_POST['n_egl'] . "";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_colores = "-1";
if (isset($_GET['n_egl'])) {
  $colname_colores = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_colores = sprintf("SELECT * FROM egl_colores WHERE n_egl = %s ORDER BY id_color ASC", GetSQLValueString($colname_colores, "int"));
$colores = mysql_query($query_colores, $conexion1) or die(mysql_error());
$row_colores = mysql_fetch_assoc($colores);
$totalRows_colores = mysql_num_rows($colores);

$colname_egl_colores = "-1";
if (isset($_GET['id_color'])) {
  $colname_egl_colores = $_GET['id_color'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_egl_colores = sprintf("SELECT * FROM egl_colores WHERE id_color = %s", GetSQLValueString($colname_egl_colores, "int"));
$egl_colores = mysql_query($query_egl_colores, $conexion1) or die(mysql_error());
$row_egl_colores = mysql_fetch_assoc($egl_colores);
$totalRows_egl_colores = mysql_num_rows($egl_colores);

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM egl_colores ORDER BY id_color DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);
/*FECHA ACTUAL DEL PAIS*/
date_default_timezone_set("America/Bogota"); ?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body oncontextmenu="return false" bgcolor="#ACCFE8">
  <table id="tabla_formato">    
    <tr>
      <td colspan="4" id="subtitulo_2">COLORES ( EGL # <?php echo $_GET['n_egl']; ?> )</td>
    </tr>
    <tr>
      <td id="nivel_2">COLOR</td>
      <td id="nivel_2">PANTONE</td>
      <td id="nivel_2">UBICACION</td>
      <td id="nivel_2"><a href="egp_lamina_colores.php?add=1&n_egl=<?php echo $_GET['n_egl']; ?>"><img src="images/mas.gif" alt="ADD COLOR" border="0" style="cursor:hand;"></a></td>
    </tr>
    <?php if($row_colores['id_color']!='') { ?>    
    <?php do { ?>
    <tr>
      <td id="talla_1"><?php echo $row_colores['color']; ?></td>
      <td id="talla_1"><?php echo $row_colores['pantone']; ?></td>
      <td id="talla_1"><?php echo $row_colores['ubicacion']; ?></td>
      <td id="talla_2"><a href="egp_lamina_colores.php?id_color=<?php echo $row_colores['id_color']; ?>&n_egl=<?php echo $row_colores['n_egl']; ?>"><img src="images/menos.gif" alt="EDIT COLOR" border="0" style="cursor:hand;"></a><a href="javascript:eliminar2('id_color',<?php echo $row_colores['id_color']; ?>,'egp_lamina_colores.php','n_egl',<?php echo $row_colores['n_egl']; ?>)"><img src="images/por.gif" border="0" style="cursor:hand;" alt="ELIMINAR" /></a></td>
    </tr>
      <?php } while ($row_colores = mysql_fetch_assoc($colores)); ?>
<?php } ?>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <?php if($_GET['add']=='1' && $_GET['n_egl'] != '') { ?>
    <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
    <tr>
      <td id="dato_1"><input name="id_color" type="hidden" value="<?php $num=$row_ultimo['id_color']; $ultimo_num=$num+1; echo $ultimo_num; ?>">
        <input name="n_egl" type="hidden" value="<?php echo $_GET['n_egl']; ?>">
      <input name="color" type="text" value="" size="20" maxlength="20"></td>
      <td id="dato_1"><input name="pantone" type="text" value="" size="20" maxlength="20"></td>
      <td id="dato_1"><input name="ubicacion" type="text" value="" size="20" maxlength="20"></td>
      <td id="dato_2"><input type="submit" value="ADD"></td>
    </tr>    
    <input type="hidden" name="MM_insert" value="form1">
  </form>
  <?php } ?>
  <?php if($_GET['id_color'] != '' && $_GET['n_egl'] != '') { ?>
  <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
    <tr>
      <td id="dato_1"><input name="n_egl" type="hidden" id="n_egl" value="<?php echo $row_egl_colores['n_egl']; ?>">
      <input name="color" type="text" id="color" value="<?php echo $row_egl_colores['color']; ?>" size="20"></td>
      <td id="dato_1"><input name="pantone" type="text" id="pantone" value="<?php echo $row_egl_colores['pantone']; ?>" size="20" maxlength="20"></td>
      <td id="dato_1"><input name="ubicacion" type="text" id="ubicacion" value="<?php echo $row_egl_colores['ubicacion']; ?>" size="20" maxlength="20"></td>
      <td id="dato_2"><input type="submit" value="EDIT"></td>
    </tr>
    <input type="hidden" name="MM_update" value="form2">
    <input type="hidden" name="id_color" value="<?php echo $row_egl_colores['id_color']; ?>">
  </form>
  <?php } ?>
    <tr>
      <td colspan="4" id="dato_2">&nbsp;</td>
    </tr>    
    <tr>
      <td colspan="4" id="dato_2"><img src="images/ciclo1.gif" style="cursor:hand;" alt="RESTAURAR" onClick="window.history.go()" /><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
    </tr>
  </table>  
</body>
</html>
<?php
mysql_free_result($colores);

mysql_free_result($egl_colores);

mysql_free_result($ultimo);
?>
