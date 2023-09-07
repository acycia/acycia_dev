<?php require_once('Connections/conexion1.php'); ?>
<?php 
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

/*if ((isset($_POST["MM_envio"])) && ($_POST["MM_envio"] == "form1")) {
$colname_egl = "-1";
if (isset($_GET['n_egl'])) {
  $colname_egl = $_GET['n_egl'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_egl = sprintf("SELECT * FROM egl WHERE n_egl = %s", $colname_egl);
$egl = mysql_query($query_egl, $conexion1) or die(mysql_error());
$row_egl = mysql_fetch_assoc($egl);
$totalRows_egl = mysql_num_rows($egl);
  $updateGoTo = "cotizacion_general_laminas.php?n_embob=" . $_POST['embobinado_egl'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
 header(sprintf("Location: %s", $updateGoTo));
}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>

<body>
<form  name="fprefijos">
<table width="200" border="0"id="tabla_formato2">
      <tr>
        <td colspan="4" id="subtitulo2">ESPECIFICACION DEL DESPACHO</td>
      </tr>
      <tr>
        <td colspan="4" id="nivel_1">EMBOBINADO</td>
      </tr>
      <tr>
        <td id="logo_2"><img src="images/embobinado1.gif"></td>
        <td id="logo_2"><img src="images/embobinado2.gif"></td>
        <td id="logo_2"><img src="images/embobinado3.gif"></td>
        <td id="logo_2"><img src="images/embobinado4.gif"></td>
      </tr>
      <tr>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="1"onclick="agregueImagen('1')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="2"onclick="agregueImagen('2')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="3"onclick="agregueImagen('3')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="4"onclick="agregueImagen('4')"></td>
      </tr>
      <tr>
        <td id="logo_2"><img src="images/embobinado5.gif"></td>
        <td id="logo_2"><img src="images/embobinado6.gif"></td>
        <td id="logo_2"><img src="images/embobinado7.gif"></td>
        <td id="logo_2"><img src="images/embobinado8.gif"></td>
      </tr>
      <tr>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="5"onclick="agregueImagen('5')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="6"onclick="agregueImagen('6')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="7"onclick="agregueImagen('7')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="8"onclick="agregueImagen('8')"></td>
      </tr>
      <tr>
        <td id="logo_2"><img src="images/embobinado9.gif"></td>
        <td id="logo_2"><img src="images/embobinado10.gif"></td>
        <td id="logo_2"><img src="images/embobinado11.gif"></td>
        <td id="logo_2"><img src="images/embobinado12.gif"></td>
      </tr>
      <tr>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="9"onclick="agregueImagen('9')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="10"onclick="agregueImagen('10')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="11"onclick="agregueImagen('11')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="12"onclick="agregueImagen('12')"></td>
      </tr>
      <tr>
        <td id="logo_2"><img src="images/embobinado13.gif"></td>
        <td id="logo_2"><img src="images/embobinado14.gif"></td>
        <td id="logo_2"><img src="images/embobinado15.gif"></td>
        <td id="logo_2"><img src="images/embobinado16.gif"></td>
      </tr>
      <tr>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="13"onclick="agregueImagen('13')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="14"onclick="agregueImagen('14')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="15"onclick="agregueImagen('15')"></td>
        <td id="dato_2"><input name="embobinado_egl" type="radio" value="16"onclick="agregueImagen('16')"></td>
      </tr>
      <tr>
      <td colspan="4" id="nivel_2">
<!--<input name="submit" type="submit"value="AGREGAR A COTIZACION LAMINAS"onclick="window.opener.getElementById['idDeLaCajaDeTexto'].value = window.document.getElementById['nombreDelCombo'].value;"/>-->
       
<!--España: 
<input type="Button" value="2" onclick="agregueImagen('2')"> -->
        
<!--<a href="JavaScript:close();" title="pasar valor" onClick="window.opener.document.frm_ch3a.txt_ogas0 _resp_ini_medicion.value = window.document.formu.datos.value;">Pasar valor a ventana padre</a>-->

<!-- onclick="window.opener.location.reload(); window.close();"   --> <!--window.opener.document.getElementById('embobinado_egl').value = loquesea;-->
<!--<a href="javascript:window.opener.document.location.reload();self.close()"> Cerrar </a>--></td>
      </tr>
</table>
    <!--<input type="hidden" name="MM_envio" value="form1">-->
</form>
</body>
</html>