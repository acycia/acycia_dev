<?php require_once('Connections/conexion1.php'); ?>
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
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = (get_magic_quotes_gpc()) ? $_SESSION['MM_Username'] : addslashes($_SESSION['MM_Username']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = '%s'", $colname_usuario);
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);

$colname_bolsa_verificacion = "-1";
if (isset($_GET['n_vb'])) {
  $colname_bolsa_verificacion = (get_magic_quotes_gpc()) ? $_GET['n_vb'] : addslashes($_GET['n_vb']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_bolsa_verificacion = sprintf("SELECT * FROM verificacion_bolsas WHERE n_vb = %s", $colname_bolsa_verificacion);
$bolsa_verificacion = mysql_query($query_bolsa_verificacion, $conexion1) or die(mysql_error());
$row_bolsa_verificacion = mysql_fetch_assoc($bolsa_verificacion);
$totalRows_bolsa_verificacion = mysql_num_rows($bolsa_verificacion);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/vista.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/vista.js"></script>
</head>
<body>
<div align="center">
<table id="tabla2">
  <tr>
    <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="bolsas_verificacion_edit.php?n_vb=<?php echo $row_bolsa_verificacion['n_vb']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="bolsas_oc_verificacion.php?n_ocb=<?php echo $row_bolsa_verificacion['n_ocb_vb']; ?>"><img src="images/v.gif" style="cursor:hand;" alt="VERIF. X OC" border="0" /></a><a href="bolsas_verificacion.php"><img src="images/cat.gif" style="cursor:hand;" alt="VERIFICACIONES (BOLSAS)" border="0" /></a><a href="bolsas_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="O.C. (BOLSAS)" border="0" /></a><a href="bolsas.php"><img src="images/b.gif" style="cursor:hand;" alt="BOLSAS" border="0" /></a><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
  </tr>
</table>
<table id="tabla1"><tr><td align="center">
<table id="tabla2">
<tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
<td id="titulo">VERIFICACION</td>
</tr>
<tr>
  <td id="titular2">PRODUCTO TERMINADO (BOLSAS)  </td>
</tr>
<tr>
  <td id="numero2">N&deg; <strong><?php echo $row_bolsa_verificacion['n_vb']; ?></strong></td>
</tr>
<tr>
  <td id="fondo2">ALBERTO CADAVID R &amp; CIA S.A.  Nit: 890915756-6<br />
    Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
</tr>
</table>
<table id="tabla2">
  <tr>
    <td id="dato1">FECHA DE RECIBO : <?php echo $row_bolsa_verificacion['fecha_recibido_vb']; ?></td>
    <td id="dato1">RECIBIDO POR : <?php echo $row_bolsa_verificacion['responsable_recibido_vb']; ?></td>
  </tr>
  <tr>
    <td id="dato1">FACTURA / REMISION : <?php echo $row_bolsa_verificacion['factura_vb']; ?> /  <?php echo $row_bolsa_verificacion['remision_vb']; ?></td>
    <td id="dato1">OTRO RECIBO : <?php echo $row_bolsa_verificacion['otro_recibo_vb']; ?></td>
  </tr>  
  <tr>
    <td id="dato1">O.C. N&deg; <?php echo $row_bolsa_verificacion['n_ocb_vb']; ?></td>
    <td id="dato1">ENTREGA : <?php $entrega=$row_bolsa_verificacion['entrega_vb']; if($entrega == '0') { echo "Parcial"; } if($entrega == '1') { echo "Total"; } ?></td>
  </tr>
  <tr>
    <td colspan="2" id="dato1">PROVEEDOR : <?php $id_p=$row_bolsa_verificacion['id_p_vb'];		  
		  if($id_p != '')
		  {
		  $sqlp="SELECT * FROM proveedor WHERE id_p='$id_p'";
		  $resultp= mysql_query($sqlp);
		  $nump= mysql_num_rows($resultp);
		  if($nump >='1') 
		  { 
		  $proveedor_p = mysql_result($resultp,0,'proveedor_p');
		  echo $proveedor_p; } } ?></td>
    </tr>
  <tr>
    <td colspan="2" id="dato1">MATERIAL SOLICITADO : <?php $id_bolsa=$row_bolsa_verificacion['id_bolsa_vb']; 
		  if($id_bolsa != '')
		  {
		  $sqlb="SELECT * FROM material_terminado_bolsas WHERE id_bolsa='$id_bolsa'";
		  $resultb= mysql_query($sqlb);
		  $numb= mysql_num_rows($resultb);
		  if($numb >='1') 
		  { 
		  $nombre_bolsa = mysql_result($resultb,0,'nombre_bolsa');
		  echo $nombre_bolsa; } } ?></td>
    </tr>
  <tr>
    <td id="dato1">REFERENCIA DEL PRODUCTO : <?php $id_ref=$row_bolsa_verificacion['id_ref_vb']; 
		  if($id_ref != '')
		  {
		  $sqlr="SELECT * FROM Tbl_referencia WHERE id_ref='$id_ref'";
		  $resultr= mysql_query($sqlr);
		  $numr= mysql_num_rows($resultr);
		  if($numr >='1') 
		  { 
		  $cod_ref = mysql_result($resultr,0,'cod_ref');
		  echo $cod_ref; 
		  } } ?></td>
    <td id="dato1">PAQUETES RECIBIDOS : <?php echo $row_bolsa_verificacion['paquetes_recibidos_vb']; ?></td>
  </tr>
</table><table id="tabla2">
  <tr>
    <td colspan="7" id="subtitulo1">I. PARAMETROS CUANTITATIVOS</td>
  </tr>
  <tr>
    <td id="nivel2" width="20%">VARIABLE</td>
    <td id="nivel2" width="10%">SOLICITADO</td>
    <td id="nivel2" width="10%">ENCONTRADO</td>
    <td id="nivel2" width="10%">MUESTRAS</td>
    <td id="nivel2" width="10%">NO CONFORME </td>
    <td id="nivel2" width="10%">CUMPLE</td>
    <td id="nivel2" width="30%">OBSERVACION</td>
  </tr>
  <tr>
    <td id="detalle1">Cantidad</td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['cantidad_solicitada_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['cantidad_encontrada_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['cantidad_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['cantidad_no_conforme_vb']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['cantidad_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle2">&nbsp;<?php echo $row_bolsa_verificacion['cantidad_observacion_vb']; ?></td>
  </tr>  
  <tr>
    <td id="detalle1">Calibre</td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['calibre_solicitado_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['calibre_encontrado_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['calibre_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['calibre_no_conforme_vb']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['calibre_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['calibre_observacion_vb']; ?></td>
  </tr>  
  <tr>
    <td id="detalle1">Ancho</td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['ancho_solicitado_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['ancho_encontrado_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['ancho_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['ancho_no_conforme_vb']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['ancho_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['ancho_observacion_vb']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">largo</td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['largo_solicitado_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['largo_encontrado_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['largo_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['largo_no_conforme_vb']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['largo_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['largo_observacion_vb']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Solapa</td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['solapa_solicitada_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['solapa_encontrada_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['solapa_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['solapa_no_conforme_vb']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['solapa_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['solapa_observacion_vb']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Fuelle / Fondo</td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['fuelle_solicitado_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['fuelle_encontrado_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['fuelle_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['fuelle_no_conforme_vb']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['fuelle_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['fuelle_observacion_vb']; ?></td>
  </tr>
  <tr>
    <td colspan="7" id="subtitulo1">II. PARAMETROS CUALITATIVOS </td>
    </tr>
  <tr>
    <td colspan="2" id="nivel2">VARIABLE</td>
    <td id="nivel2">CUMPLE</td>
    <td id="nivel2">MUESTRAS</td>
    <td id="nivel2">NO CONFORME</td>
    <td colspan="2" id="nivel2">OBSERVACION</td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">Unidad de Empaque</td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['empaque_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['empaque_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['empaque_no_conforme_vb']; ?></td>
    <td colspan="2" id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['empaque_observacion_vb']; ?></td>
    </tr>
  <tr>
    <td colspan="2" id="detalle1">Apariencia</td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['apariencia_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['apariencia_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['apariencia_no_conforme_vb']; ?></td>
    <td colspan="2" id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['apariencia_observacion_vb']; ?></td>
    </tr>
  <tr>
    <td colspan="2" id="detalle1">Resistencia de los sellos</td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['resistencia_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['resistencia_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['resistencia_no_conforme_vb']; ?></td>
	<td colspan="2" id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['resistencia_observacion_vb']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">Tratamiento</td>
    <td id="detalle2">&nbsp;<?php switch ($row_bolsa_verificacion['tratamiento_cumple_vb']) {
    case 0:
        echo "NO";
        break;
    case 0.5:
        echo "PARCIAL";
        break;
    case 1:
        echo "SI";
        break;
	case 2:
        echo "N.A.";
        break;
} ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['tratamiento_muestras_vb']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_bolsa_verificacion['tratamiento_no_conforme_vb']; ?></td>
	<td colspan="2" id="detalle1">&nbsp;<?php echo $row_bolsa_verificacion['tratamiento_observacion_vb']; ?></td>
  </tr>
  <tr>
    <td colspan="7" id="subtitulo1">OBSERVACIONES </td>
  </tr>
  <tr>
    <td colspan="7" id="dato1"><strong>Nota :</strong> Cada muestreo se realiza con base a lo establecido en la guía A3-G02, llamado "Plan de Inspección de Materia Prima".</td>
  </tr>
  <tr>
    <td colspan="7" id="dato1">OTRAS OBSERVACIONES : <?php echo $row_bolsa_verificacion['observaciones_vb']; ?></td>
  </tr>
  </table>
  <table id="tabla2">
    <tr>
      <td id="subtitulo2">SERVICIO ( de 1 a 10 ) </td>
      <td id="subtitulo2">CALIFICACION TOTAL</td>
      </tr>
    <tr>
      <td id="dato2">&nbsp;<?php echo $row_bolsa_verificacion['servicio_vb']; ?></td>
      <td id="dato2">&nbsp;<?php echo $row_bolsa_verificacion['calificacion_vb']; ?> % </td>
      </tr>
    <tr>
    <td id="subtitulo2">REGISTRO</td>
    <td id="subtitulo2">MODIFICACION</td>    
    </tr>
  <tr>
    <td id="dato2">&nbsp;<?php echo $row_bolsa_verificacion['responsable_registro_vb']; ?> - <?php echo $row_bolsa_verificacion['fecha_registro_vb']; ?></td>
	<td id="dato2">&nbsp;<?php echo $row_bolsa_verificacion['responsable_modificacion_vb']; ?> - <?php echo $row_bolsa_verificacion['fecha_modificacion_vb']; ?></td>
	</tr>
  <tr>
    <td id="fondo2"><strong>CODIGO : A3 - F08</strong></td>
    <td id="fondo2"><strong>VERSION : 1</strong></td>
    </tr>
	</table>
</td></tr></table>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($bolsa_verificacion);
?>
