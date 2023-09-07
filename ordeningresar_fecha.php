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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
	
	if($_GET['tipo']==1){
  $updateSQL = sprintf("UPDATE Tbl_orden_produccion SET f_coextruccion=%s WHERE id_op=%s",
                       GetSQLValueString($_POST['f_coextruccion'], "date"),
                       GetSQLValueString($_POST['id'], "int"));
	}
	if($_GET['tipo']==2){
  $updateSQL = sprintf("UPDATE Tbl_orden_produccion SET f_impresion=%s WHERE id_op=%s",
                       GetSQLValueString($_POST['f_coextruccion'], "date"),
                       GetSQLValueString($_POST['id'], "int"));
	}
	if($_GET['tipo']==3){
  $updateSQL = sprintf("UPDATE Tbl_orden_produccion SET f_sellada=%s WHERE id_op=%s",
                       GetSQLValueString($_POST['f_coextruccion'], "date"),
                       GetSQLValueString($_POST['id'], "int"));
	}
	if($_GET['tipo']==4){
  $updateSQL = sprintf("UPDATE Tbl_orden_produccion SET f_despacho=%s WHERE id_op=%s",
                       GetSQLValueString($_POST['f_coextruccion'], "date"),
                       GetSQLValueString($_POST['id'], "int"));
	}
  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "?listo=1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_orden = "-1";
if (isset($_GET['id'])) {
  $colname_orden = $_GET['id'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_orden = sprintf("SELECT id_op, f_coextruccion FROM Tbl_orden_produccion WHERE id_op = %s", GetSQLValueString($colname_orden, "int"));
$orden = mysql_query($query_orden, $conexion1) or die(mysql_error());
$row_orden = mysql_fetch_assoc($orden);
$totalRows_orden = mysql_num_rows($orden);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Fecha</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php if ($_GET['listo']) {?>
registro realizado
<?php }else  {?>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td><span id="sprytextfield1">
      <?php if($_GET['tipo']==1){ ?> 
      <input type="date" name="f_coextruccion" value="<?php echo htmlentities($row_orden['f_coextruccion'], ENT_COMPAT, 'utf-8'); ?>" size="10" />
      <?php } ?>
        <?php if($_GET['tipo']==2){ ?> 
      <input type="date" name="f_coextruccion" value="<?php echo htmlentities($row_orden['f_impresion'], ENT_COMPAT, 'utf-8'); ?>" size="10" />
      <?php } ?>
        <?php if($_GET['tipo']==3){ ?> 
      <input type="date" name="f_coextruccion" value="<?php echo htmlentities($row_orden['f_sellada'], ENT_COMPAT, 'utf-8'); ?>" size="10" />
      <?php } ?>
        <?php if($_GET['tipo']==4){ ?> 
      <input type="date" name="f_coextruccion" value="<?php echo htmlentities($row_orden['f_despacho'], ENT_COMPAT, 'utf-8'); ?>" size="10" />
      <?php } ?>
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
      <td nowrap="nowrap">dd-mm-aaaa</td>
    </tr>
    <tr valign="baseline">
      <td><input type="submit" value="Registrar Fecha" onClick="opener.location.reload()"/></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_orden['id_op']; ?>" />
</form>
<p>&nbsp;</p>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "date", {format:"yyyy-mm-dd", useCharacterMasking:true, validateOn:["blur"]});
</script><?php } ?>
</body>
</html>
<?php
mysql_free_result($orden);
?>
