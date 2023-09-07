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

$colname_proveedor = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor = sprintf("SELECT * FROM proveedor WHERE id_p = %s", $colname_proveedor);
$proveedor = mysql_query($query_proveedor, $conexion1) or die(mysql_error());
$row_proveedor = mysql_fetch_assoc($proveedor);
$totalRows_proveedor = mysql_num_rows($proveedor);

$colname_proveedor_seleccion = "-1";
if (isset($_GET['id_p'])) {
  $colname_proveedor_seleccion = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_proveedor_seleccion = sprintf("SELECT * FROM proveedor_seleccion WHERE id_p_seleccion = %s", $colname_proveedor_seleccion);
$proveedor_seleccion = mysql_query($query_proveedor_seleccion, $conexion1) or die(mysql_error());
$row_proveedor_seleccion = mysql_fetch_assoc($proveedor_seleccion);
$totalRows_proveedor_seleccion = mysql_num_rows($proveedor_seleccion);

mysql_select_db($database_conexion1, $conexion1);
$query_insumos = "SELECT * FROM insumo WHERE id_insumo IN(SELECT id_in FROM TblProveedorInsumo WHERE id_p=$colname_proveedor_seleccion) ORDER BY descripcion_insumo ASC";
$insumos = mysql_query($query_insumos, $conexion1) or die(mysql_error());
$row_insumos = mysql_fetch_assoc($insumos);
$totalRows_insumos = mysql_num_rows($insumos);

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
<table id="tablainterna">
<tr>
  <td id="subppal">CODIGO : A3-F03</td>
  <td colspan="2" nowrap="nowrap" id="principal">SELECCION DE PROVEEDORES </td>
  <td id="subppal">VERSION : 0</td>
</tr>
<tr>
  <td rowspan="6" id="fondo"><img src="images/logoacyc.jpg"/></td>
  <td colspan="2" id="fondo"> N&deg; <?php echo $row_proveedor['id_p']; ?></td>
  <td id="noprint"><a href="proveedor_edit.php?id_p=<?php echo $row_proveedor['id_p']; ?>"><img src="images/menos.gif" alt="EDITAR PROVEEDOR" border="0" style="cursor:hand;" /></a><img src="images/impresor.gif" onclick="window.print();" style="cursor:hand;" alt="INPRIMIR" /><a href="proveedor_add.php"><img src="images/mas.gif" alt="ADD PROVEEDOR" border="0" style="cursor:hand;"></a><a href="proveedores.php"><img src="images/cat.gif" style="cursor:hand;" alt="LISTADO PROVEEDORES" border="0"/></a><a href="proveedor_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"/></a><img src="images/salir.gif" style="cursor:hand;" alt="SALIR" onclick="window.close() "/></td>
</tr>
<tr>
  <td nowrap="nowrap" id="subppal2">FECHA DE REGISTRO </td>
  <td colspan="2" nowrap="nowrap" id="subppal2">REGISTRADO POR </td>
  </tr>
<tr>
  <td id="fuente2"><?php echo $row_proveedor['fecha_registro_p']; ?></td>
  <td colspan="2" id="fuente2"><?php echo $row_proveedor['registro_p']; ?></td>
  </tr>
<tr>
  <td id="subppal2">FECHA MODIFICACION </td>
  <td colspan="2" id="subppal2"> MODIFICADO POR </td>
  </tr>
<tr>
  <td id="fuente2"><?php if($row_proveedor['fecha_modif_p'] == '') { echo "- -"; } else { echo $row_proveedor['fecha_modif_p']; } ?></td>
  <td colspan="2" id="fuente2"><?php if($row_proveedor['modificacion_p'] == '') { echo "- -"; } else { echo $row_proveedor['modificacion_p']; } ?></td>
  </tr>
<tr>
  <td colspan="3" id="fondo">Validaci&oacute;n de proveedores que afectan directamente la calidad del producto. </td>
  </tr>
<tr>
  <td colspan="4" id="subtitulo">I. INFORMACION COMERCIAL </td>
  </tr>
<tr>
  <td colspan="2" id="subppal2">RAZON SOCIAL </td>
  <td id="subppal2">NIT. - C.C. - ID </td>
  <td id="subppal2">TIPO DE PROVEEDOR </td>
</tr>
<tr>
  <td colspan="2" id="fuente3"><?php echo $row_proveedor['proveedor_p']; ?></td>
  <td id="fuente3"><?php echo $row_proveedor['nit_p']; ?></td>
  <td id="fuente3"><?php $tipo_p = $row_proveedor['tipo_p'];
  if($tipo_p != '')
  {
  $sqltipo = "SELECT * FROM tipo WHERE id_tipo ='$tipo_p'";
  $resultipo = mysql_query($sqltipo);
  $numtipo = mysql_num_rows($resultipo);
  if($numtipo >='1') 
  { 
  $nombre_tipo = mysql_result($resultipo,0,'nombre_tipo'); 
  echo $nombre_tipo;
  }
  }
  ?></td>
</tr>
<tr>
  <td colspan="4" id="subppal2">DIRECCION COMERCIAL </td>
  </tr>
<tr>
  <td colspan="4" id="fuente3"><?php echo $row_proveedor['direccion_p']; ?></td>
  </tr>
<tr>
  <td colspan="2" id="subppal2">CONTACTO COMERCIAL </td>
  <td id="subppal2">TELEFONO</td>
  <td id="subppal2">FAX</td>
</tr>
<tr>
  <td colspan="2" id="fuente3"><?php echo $row_proveedor['contacto_p']; ?></td>
  <td id="fuente3"><?php echo $row_proveedor['telefono_p']; ?></td>
  <td id="fuente3"><?php echo $row_proveedor['fax_p']; ?></td>
</tr>
<tr>
  <td id="subppal2">CELULAR</td>
  <td id="subppal2">PAIS</td>
  <td id="subppal2">PROVINCIA</td>
  <td id="subppal2">CIUDAD</td>
</tr>
<tr>
  <td id="fuente3"><?php echo $row_proveedor['celular_c_p']; ?></td>
  <td id="fuente3"><?php echo $row_proveedor['pais_p']; ?></td>
  <td id="fuente3"><?php echo $row_proveedor['dpto_p']; ?></td>
  <td id="fuente3"><?php echo $row_proveedor['ciudad_p']; ?></td>
</tr>
<tr>
  <td id="subppal2">EMAIL</td>
  <td id="subppal2">REGIMEN</td>
  <td colspan="2" id="subppal2">&nbsp;</td>
  </tr>
<tr>
  <td id="fuente3"><?php echo $row_proveedor['email_c_p']; ?></td>
  <td id="fuente3"><?php echo $row_proveedor['regimen_p']; ?></td>
  <td id="fuente3"><input <?php if (!(strcmp($row_proveedor['contribuyentes_p'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="checkbox" value="1" />
    CONTRIBUYENTES</td>
  <td id="fuente3"><input <?php if (!(strcmp($row_proveedor['autoretenedores_p'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="checkbox2" value="1" />
    AUTORETENEDORES</td>
</tr>
<tr>
  <td id="subppal2">PRODUCTO / SERVICIO</td>
  <td id="subppal2">&nbsp;</td>
  <td colspan="2" id="subppal2">&nbsp;</td>
  </tr>
<tr>
  <td id="fuente3"><?php echo $row_proveedor['tipo_servicio_p']; ?></td>
  <td id="fuente3">&nbsp;</td>
  <td id="fuente3">&nbsp;</td>
  <td id="fuente3">&nbsp;</td>
</tr>
  <td colspan="4" id="fuente1">DOCUMENTOS ADJUNTOS 
 
      <hr>
 
     </td> 
  </tr>
  <tr>
    <td colspan="2" nowrap id="detalle1">
        Camara de Comercio (Vigente)<br>  
        <a href="javascript:verFoto('archivosc/<?php echo $row_proveedor['camara_comercio_p'] ?>','610','490')"><?php if($row_proveedor['camara_comercio_p']!='') echo "Camara Comercio"; ?></a>
    </td>
      <td colspan="2" nowrap id="detalle1">RUT<br> 
      <a href="javascript:verFoto('archivosc/<?php echo $row_proveedor['rut_p'] ?>','610','490')"> 
      <?php if($row_proveedor['rut_p']!='') echo "Rut"; ?>
      </a>
    </td>
  </tr>
 <tr>
  <td colspan="2" nowrap id="detalle1">Proteccion de Datos<br> 
    <a href="javascript:verFoto('archivosc/<?php echo $row_proveedor['datos_proyeccion_p'] ?>','610','490')">
    <?php if($row_proveedor['datos_proyeccion_p']!='') echo "Proteccion de Datos"?>
    </a>
  </td>
</tr>
<tr>
  <td colspan="4" id="subtitulo">II. INFORMACION DEL PROCESO - PRODUCTO / SERVICIO </td>
  </tr>
<tr>
  <td colspan="4" id="subppal2">PRODUCTOS O SERVICIOS QUE SUMINISTRA </td>
  </tr>
<tr>
  <td colspan="4" id="fuente3"><?php if($row_proveedor['prod_serv_p']=='') { echo "- -"; } else { echo $row_proveedor['prod_serv_p']; } ?></td>
 
  </tr>
  
  <?php
 $item=0;
   do {
	?>
    <tr><td colspan="4" id="fuente3"> 
	<?php    
   $item ++;
  echo $item."-".$row_insumos['descripcion_insumo']."<BR>";
  ?>
  </td>
    </tr>
  <?php 
  } while ($row_insumos = mysql_fetch_assoc($insumos));       
    ?>
    
</table><?php $tipo_p=$row_proveedor['tipo_p'];
			if($tipo_p != '2')
			{ ?>
<table id="tablainterna">
<tr>
<td colspan="2" id="subtitulo">III. ENCUESTA PARA LA CALIFICACION DEL PROVEEDOR </td>
    </tr><?php if($row_proveedor_seleccion['id_seleccion']!='') { ?>	
      <tr>
        <td colspan="2" id="subppal2"><strong>1</strong>. Es una empresa que ofrece directamente sus productos y/o servicios,los subcontrata o tiene distribuidores ?</td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3">
		<?php switch($row_proveedor_seleccion['directo_p'])
		{ 
		case 0: echo "N.A."; break;
		case 5: echo "DIRECTO"; break;
		case 3: echo "DISTRIBUIDOR"; break;
		case 1: echo "SUBCONTRATA"; break;
		}
		?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Puntos de distribuci&oacute;n ? </td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['punto_dist_p'] == '') { echo "- -"; } else { echo $row_proveedor_seleccion['punto_dist_p']; } ?></td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="subppal2"><strong>2</strong>. Ofrece Formas de Pago ? </td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3">
		<?php $forma_pago_p=$row_proveedor_seleccion['forma_pago_p']; 
		if($forma_pago_p == '0')
		{ echo "N.A.";}
		if($forma_pago_p == '5')
		{ echo "30 a 60 dias"; }
		if($forma_pago_p == '3')
		{ echo "15 a 29 dias"; }
		if($forma_pago_p == '1')
		{ echo "Contado a 14 dias";}
		?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Otra, Cual ? </td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['otra_p'] == '') { echo "- -"; } else { echo $row_proveedor_seleccion['otra_p']; } ?></td>
      </tr>
      <tr id="tr1">
        <td colspan="2" id="subppal2"><strong>3</strong>. Tiene Sistema de Gesti&oacute;n de Calidad certificado ?</td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php switch($row_proveedor_seleccion['sist_calidad_p']){
		case 0: echo "N.A."; break;
		case 5: echo "Si"; break;
		case 3: echo "En proceso"; break;
		case 1: echo "No"; break;
		} ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Con cual Norma y que porcentaje de Avance ?</td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['norma_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['norma_p']; } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>4</strong>. Entrega certificado de calidad de sus productos con cada despacho (insumos) u ofrece garantia al servicio ?</td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php 
		switch($row_proveedor_seleccion['certificado_p']) 
		{ 
		case 0: echo "N.A."; break;
		case 5: echo "Si"; break;
		case 3: echo "Algunas veces"; break;
		case 1: echo "No"; break;
		} ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Con que frecuencia ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['frecuencia_p']=='') { echo ""; } else { echo $row_proveedor_seleccion['frecuencia_p']; } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>5</strong>. Realiza analisis de control de calidad a cada lote de material ?</td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php 
		switch($row_proveedor_seleccion['analisis_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "Si"; break;
		case 3: echo "Por muestreo"; break;
		case 1: echo "No"; break;
		 }?>		</td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Si es por muestra, cada cuanto ?</td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['muestra_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['muestra_p'];  } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>6</strong>. Requiere orden de compra con anterioridad ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php
		switch($row_proveedor_seleccion['orden_compra_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "1 a 15 dias"; break;
		case 3: echo "16 a 30 dias"; break;
		} ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Si es mayor a 30 en cuanto tiempo?</td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['mayor_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['mayor_p']; } ?></td>
    </tr>
      <tr id="tr1">
        <td colspan="2" id="subppal2"><strong>7</strong>. Tiene establecido un tiempo para la agilidad de respuesta ante un reclamo ?</td>
    </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php 
		switch($row_proveedor_seleccion['tiempo_agil_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "El mismo dia"; break;
		case 3: echo "1 semana"; break;
		case 1: echo "1 mes"; break;
		} ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Cuanto tiempo ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['tiempo_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['tiempo_p']; } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>8</strong>. Realiza entrega del producto o servicio en las instalaciones de la empresa ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php 
		switch($row_proveedor_seleccion['entrega_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "Si"; break;
		case 3: echo "Con intermediario"; break;
		case 1: echo "No"; break;
		} ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Otros metodos ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['metodos_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['metodos_p']; } ?></td>
    </tr>
      <tr id="tr1">
        <td colspan="2" id="subppal2"><strong>9</strong>. El flete correspondiente a la entrega corre por parte del proveedor ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php 
		switch($row_proveedor_seleccion['flete_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "Si"; break;
		case 1: echo "No"; break;
		} ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">&oacute; cuando se establece ese requisito ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['requisito_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['requisito_p']; } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>10</strong>. Tiene establecido un plan de mejora para el producto, servicios y/o sus procesos?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php
		switch($row_proveedor_seleccion['plan_mejora_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "Si"; break;
		case 1: echo "No"; break;
		}?></td></tr>
      <tr>
        <td colspan="2" id="subppal2">En que aspectos ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['aspecto_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['aspecto_p']; } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>11</strong>. Maneja listado de precios actualizado ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php
		switch($row_proveedor_seleccion['precios_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "Anual"; break;
		case 3: echo "Semestral"; break;
		case 1: echo "Otro (< 6 meses)"; break;
		} ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">En caso de otro, explique.</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['otro_caso_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['otro_caso_p']; } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>12</strong>. Asigna asesores comerciales a cada empresa ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php
		switch($row_proveedor_seleccion['asesor_com_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "Si"; break;
		case 3: echo "No"; break;
		 }?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Nombre ? </td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['nombre_asesor_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['nombre_asesor_p']; } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>13</strong>. Tiene limites minimos de pedido ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php
		switch($row_proveedor_seleccion['limite_min_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "No"; break;
		case 1: echo "Si"; break;
		}?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2">Cuanto ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php if($row_proveedor_seleccion['cuanto_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['cuanto_p']; } ?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal2"><strong>14</strong>. Cuentan con un proceso definido para preservar y manejar el material o equipo suministrado por el cliente ?</td>
      </tr>
      <tr>
        <td colspan="2" id="fuente3"><?php
		switch($row_proveedor_seleccion['proceso_p'])
		{
		case 0: echo "N.A."; break;
		case 5: echo "Si"; break;
		case 1: echo "No"; break;
		}?></td>
    </tr>
      <tr>
        <td colspan="2" id="subppal3"><strong>Nota</strong>: En algunos casos puede que su empresa no aplique a alguno de los items anteriores. Por ejemplo, si la pregunta hace referencia a un producto (tangible) y su empresa es de servicios, si es el caso por favor se&ntilde;ale la casilla <strong>NO</strong> de la columna <strong> No Aplica</strong></td>
      </tr>
      <tr>
        <td id="subppal2">CALIFICACION INICIAL (%) </td>
        <td id="subppal2">FECHA ENCUESTA</td>
      </tr>
      <tr>
        <td id="fuente2"><?php echo $row_proveedor_seleccion['primera_calificacion_p']; ?></td>
        <td id="fuente2"><?php echo $row_proveedor_seleccion['fecha_encuesta_p']; ?></td>
      </tr>
      <tr>
        <td id="subppal2">NOMBRE DEL ENCUESTADOR </td>
        <td id="subppal2">CARGO DEL ENCUESTADOR </td>
      </tr>
      <tr>
        <td id="fuente2"><?php echo $row_proveedor_seleccion['encuestador_p']; ?></td>
        <td id="fuente2"><?php echo $row_proveedor_seleccion['cargo_p']; ?></td>
</tr>
      <tr>
        <td id="subppal2">ULTIMA CALIFICACION (%) </td>
        <td id="subppal2">FECHA DE ULTIMA CALIFICACION</td>
      </tr>
      <tr>
        <td id="fuente2"><?php if($row_proveedor_seleccion['ultima_calificacion_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['ultima_calificacion_p']; } ?></td>
        <td id="fuente2"><?php if($row_proveedor_seleccion['fecha_ultima_calificacion_p']=='') { echo "- -"; } else { echo $row_proveedor_seleccion['fecha_ultima_calificacion_p'] ; } ?></td>
      </tr> <?php } else {?>
	  <tr>
	    <td colspan="2" id="numero1">ES UN PROVEEDOR CRITICO Y NO EXISTE ENCUESTA REGISTRADA. </td>
	  </tr><?php } ?>
</table>
<?php } ?>
</div>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($proveedor);

mysql_free_result($proveedor_seleccion);
?>
