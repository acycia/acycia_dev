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

$colname_verificacion = "-1";
if (isset($_GET['n_vr'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['n_vr'] : addslashes($_GET['n_vr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM verificacion_rollos WHERE n_vr = %s", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);
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
    <td id="noprint" align="right"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><img src="images/impresor.gif" onClick="window.print();" style="cursor:hand;" alt="IMPRIMIR" border="0" /><a href="rollos_verificacion_edit.php?n_vr=<?php echo $row_verificacion['n_vr']; ?>"><img src="images/menos.gif" alt="EDITAR" border="0" /></a><a href="rollos_oc_verificacion.php?n_ocr=<?php echo $row_verificacion['n_ocr_vr']; ?>"><img src="images/v.gif" style="cursor:hand;" alt="VERIF. X OC" border="0" /></a><a href="rollos_oc.php"><img src="images/o.gif" style="cursor:hand;" alt="O.C. (ROLLOS)" border="0" /></a><a href="rollos_verificacion.php"><img src="images/cat.gif" style="cursor:hand;" alt="VERIFICACIONES (ROLLOS)" border="0" /></a><a href="rollos.php"><img src="images/r.gif" style="cursor:hand;" alt="ROLLOS" border="0" /></a><a href="compras.php"><img src="images/opciones.gif" style="cursor:hand;" alt="GESTION COMPRAS" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" border="0"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onClick="window.close() "/></td>
  </tr>
</table>
<table id="tabla1"><tr><td align="center">
<table id="tabla2">
<tr><td rowspan="4" id="fondo2"><img src="images/logoacyc.jpg"></td>
<td id="titulo">VERIFICACION</td>
</tr>
<tr>
  <td id="titular2">MATERIA PRIMA ( ROLLOS ) </td>
</tr>
<tr>
  <td id="numero2">N&deg; <strong><?php echo $row_verificacion['n_vr']; ?></strong></td>
</tr>
<tr>
  <td id="fondo2">ALBERTO CADAVID R &amp; CIA S.A.  Nit: 890915756-6<br />
    Carrera 45 No. 14 - 15  Tel: 311-21-44  Fax: 266-41-23  Medellin-Colombia</td>
</tr>
</table>
<table id="tabla2">
  <tr>
    <td id="dato1">FECHA DE RECIBO : <?php echo $row_verificacion['fecha_recibo_vr']; ?></td>
    <td id="dato1">RECIBIDO POR : <?php echo $row_verificacion['responsable_recibo_vr']; ?></td>
  </tr>
  <tr>
    <td id="dato1">FACTURA / REMISION : <?php echo $row_verificacion['factura_vr']; ?> /  <?php echo $row_verificacion['remision_vr']; ?></td>
    <td id="dato1">OTRO RECIBO : <?php echo $row_verificacion['otro_recibo_vr']; ?></td>
  </tr>
  
  <tr>
    <td id="dato1">O.C. N&deg; <?php echo $row_verificacion['n_ocr_vr']; ?></td>
    <td id="dato1">ENTREGA : <?php $entrega=$row_verificacion['entrega_vr']; if($entrega == '0') { echo "PARCIAL"; } if($entrega == '1') { echo "TOTAL"; } ?></td>
  </tr>
  <tr>
    <td colspan="2" id="dato1">PROVEEDOR : <?php $id_p=$row_verificacion['id_p_vr'];		  
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
    <td colspan="2" id="dato1">MATERIAL SOLICITADO : <?php $id_rollo=$row_verificacion['id_rollo_vr']; 
		  if($id_rollo != '')
		  {
		  $sqlr="SELECT * FROM materia_prima_rollos WHERE id_rollo='$id_rollo'";
		  $resultr= mysql_query($sqlr);
		  $numr= mysql_num_rows($resultr);
		  if($numr >='1') 
		  { 
		  $nombre_rollo = mysql_result($resultr,0,'nombre_rollo');
		  echo $nombre_rollo; } } ?></td>
    </tr>
  <tr>
    <td id="dato1">REFERENCIA DEL PRODUCTO : <?php $ref=$row_verificacion['id_ref_vr']; 
		  if($ref != '')
		  {
		  $sqlref="SELECT * FROM Tbl_referencia WHERE id_ref='$ref'";
		  $resultref= mysql_query($sqlref);
		  $numref= mysql_num_rows($resultref);
		  if($numref >='1') 
		  { 
		  $cod_ref = mysql_result($resultref,0,'cod_ref');
		  echo $cod_ref; 
		  } } ?></td>
    <td id="dato1">N&deg; ROLLOS RECIBIDOS : <?php echo $row_verificacion['unidades_recibidas_vr']; ?></td>
  </tr>
</table>
<table id="tabla2">
  <tr>
    <td colspan="7" id="subtitulo1">I. PARAMETROS CUANTITATIVOS </td>
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
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['cantidad_solicitada_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['cantidad_encontrada_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['cantidad_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['cantidad_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['cantidad_cumple_vr']) {
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
    <td id="detalle2">&nbsp;<?php echo $row_verificacion['cantidad_observacion_vr']; ?></td>
  </tr>  
  <tr>
    <td id="detalle1">Calibre</td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['calibre_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['calibre_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['calibre_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['calibre_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['calibre_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['calibre_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Peso</td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['peso_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['peso_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['peso_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['peso_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['peso_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['peso_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Ancho del Rollo </td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['ancho_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['ancho_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['ancho_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['ancho_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['ancho_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['ancho_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Repeticion / Rodillo </td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['rodillo_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['rodillo_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['rodillo_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['rodillo_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['rodillo_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['rodillo_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Tratamiento (Tension Superficial) </td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['tratamiento_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['tratamiento_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['tratamiento_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['tratamiento_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['tratamiento_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['tratamiento_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Resistencia al Rasgado MD&gt;3g/mic * </td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['md_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['md_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['md_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['md_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['md_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['md_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Resistencia al Rasgado TD&gt;6g/mic * </td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['td_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['td_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['td_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['td_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['td_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['td_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Angulo de Deslizamiento Min. 18&deg; * </td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['angulo_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['angulo_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['angulo_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['angulo_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['angulo_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['angulo_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td id="detalle1">Fuerza de Selle&gt;30g/mic * </td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['fuerzaselle_solicitado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['fuerzaselle_encontrado_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['fuerzaselle_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['fuerzaselle_no_conforme_vr']; ?></td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['fuerzaselle_cumple_vr']) {
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
    <td id="detalle1">&nbsp;<?php echo $row_verificacion['fuerzaselle_observacion_vr']; ?></td>
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
    <td colspan="2" id="detalle1">Apariencia</td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['apariencia_cumple_vr']) {
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
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['apariencia_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['apariencia_no_conforme_vr']; ?></td>
    <td colspan="2" id="detalle1">&nbsp;<?php echo $row_verificacion['apariencia_observacion_vr']; ?></td>
    </tr>
  <tr>
    <td colspan="2" id="detalle1">Sellabilidad y Resistencia</td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['resistencia_sellos_cumple_vr']) {
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
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['resistencia_sellos_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['resistencia_sellos_no_conforme_vr']; ?></td>
	<td colspan="2" id="detalle1">&nbsp;<?php echo $row_verificacion['resistencia_sellos__observacion_vr']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">Impresion - Concordancia en el arte</td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['impresion_cumple_vr']) {
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
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['impresion_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['impresion_no_conforme_vr']; ?></td>
    <td colspan="2" id="detalle1">&nbsp;<?php echo $row_verificacion['impresion_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">Color</td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['color_cumple_vr']) {
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
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['color_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['color_no_conforme_vr']; ?></td>
    <td colspan="2" id="detalle1">&nbsp;<?php echo $row_verificacion['color_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td colspan="2" id="detalle1">Adhesion de Tinta</td>
    <td id="detalle2">&nbsp;<?php switch ($row_verificacion['tinta_cumple_vr']) {
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
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['tinta_muestras_vr']; ?></td>
    <td id="detalle3">&nbsp;<?php echo $row_verificacion['tinta_no_conforme_vr']; ?></td>
	<td colspan="2" id="detalle1">&nbsp;<?php echo $row_verificacion['tinta_observacion_vr']; ?></td>
  </tr>
  <tr>
    <td colspan="7" id="subtitulo1">OBSERVACIONES </td>
  </tr>
  <tr>
    <td colspan="7" id="dato1"><strong>*</strong> De acuerdo a la ficha tecnica del Material. <br>
                  <strong>**</strong> De acuerdo a lo comparado con el certificado de calidad del lote recibido.<br>
                  <strong>Nota :</strong> Cada muestreo se realiza con base a lo establecido en la guía A3-G02, llamado "Plan de Inspección de Materia Prima".</td>
  </tr>
  <tr>
    <td colspan="7" id="dato1">OTRAS OBSERVACIONES : <?php echo $row_verificacion['observaciones_vr']; ?></td>
  </tr>
  </table>
  <table id="tabla2">
    <tr>
      <td id="subtitulo2">SERVICIO ( de 1 a 10 ) </td>
      <td id="subtitulo2">CALIFICACION TOTAL</td>
      </tr>
    <tr>
      <td id="dato2">&nbsp;<?php echo $row_verificacion['servicio_vr']; ?></td>
      <td id="dato2">&nbsp;<?php echo $row_verificacion['calificacion_vr']; ?> % </td>
      </tr>
    <tr>
    <td id="subtitulo2">REGISTRO</td>
    <td id="subtitulo2">MODIFICACION</td>    
    </tr>
  <tr>
    <td id="dato2">&nbsp;<?php echo $row_verificacion['responsable_registro_vr']; ?> - <?php echo $row_verificacion['fecha_registro_vr']; ?></td>
	<td id="dato2">&nbsp;<?php echo $row_verificacion['responsable_modificacion_vr']; ?> - <?php echo $row_verificacion['fecha_modificacion_vr']; ?></td>
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

mysql_free_result($verificacion);
?>
