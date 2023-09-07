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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE orden_compra_rollos SET id_p_ocr=%s, id_rollo_ocr=%s, id_ref_ocr=%s, fecha_pedido_ocr=%s, fecha_entrega_ocr=%s, condiciones_pago_ocr=%s, cantidad_ocr=%s, valor_unitario_ocr=%s, valor_neto_ocr=%s, iva_ocr=%s, valor_total_ocr=%s, pedido_ocr=%s, calibre_micras_ocr=%s, calibre_millas_ocr=%s, ancho_material_ocr=%s, tratamiento_corona_ocr=%s, pigmento_exterior_ocr=%s, ref_pigmento_exterior_ocr=%s, pigmento_interior_ocr=%s, ref_pigmento_interior_ocr=%s, presentacion_material_ocr=%s, ancho_bolsa_ocr=%s, largo_bolsa_ocr=%s, solapa_bolsa_ocr=%s, anexa_arte_ocr=%s, anexa_arte_impreso_ocr=%s, repeticion_rodillo_ocr=%s, negativo_ocr=%s, cyrell_ocr=%s, observacion_ocr=%s, elaboro_ocr=%s, aprobo_ocr=%s, saldo_verificacion_ocr=%s WHERE n_ocr=%s",
                       GetSQLValueString($_POST['id_p_ocr'], "int"),
                       GetSQLValueString($_POST['id_rollo_ocr'], "int"),
                       GetSQLValueString($_POST['id_ref_ocr'], "int"),
                       GetSQLValueString($_POST['fecha_pedido_ocr'], "date"),
                       GetSQLValueString($_POST['fecha_entrega_ocr'], "date"),
                       GetSQLValueString($_POST['condiciones_pago_ocr'], "text"),
                       GetSQLValueString($_POST['cantidad_ocr'], "double"),
                       GetSQLValueString($_POST['valor_unitario_ocr'], "double"),
                       GetSQLValueString($_POST['valor_neto_ocr'], "double"),
                       GetSQLValueString($_POST['iva_ocr'], "double"),
                       GetSQLValueString($_POST['valor_total_ocr'], "double"),
                       GetSQLValueString($_POST['pedido_ocr'], "text"),
                       GetSQLValueString($_POST['calibre_micras_ocr'], "double"),
                       GetSQLValueString($_POST['calibre_millas_ocr'], "double"),
                       GetSQLValueString($_POST['ancho_material_ocr'], "double"),
                       GetSQLValueString($_POST['tratamiento_corona_ocr'], "text"),
                       GetSQLValueString($_POST['pigmento_exterior_ocr'], "text"),
                       GetSQLValueString($_POST['ref_pigmento_exterior_ocr'], "text"),
                       GetSQLValueString($_POST['pigmento_interior_ocr'], "text"),
                       GetSQLValueString($_POST['ref_pigmento_interior_ocr'], "text"),
                       GetSQLValueString($_POST['presentacion_material_ocr'], "text"),
                       GetSQLValueString($_POST['ancho_bolsa_ocr'], "double"),
                       GetSQLValueString($_POST['largo_bolsa_ocr'], "double"),
                       GetSQLValueString($_POST['solapa_bolsa_ocr'], "double"),
                       GetSQLValueString(isset($_POST['anexa_arte_ocr']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['anexa_arte_impreso_ocr']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['repeticion_rodillo_ocr'], "double"),
                       GetSQLValueString(isset($_POST['negativo_ocr']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['cyrell_ocr']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observacion_ocr'], "text"),
                       GetSQLValueString($_POST['elaboro_ocr'], "text"),
                       GetSQLValueString($_POST['aprobo_ocr'], "text"),
                       GetSQLValueString($_POST['cantidad_ocr'], "double"),
                       GetSQLValueString($_POST['n_ocr'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "rollos_oc_vista.php?n_ocr=" . $_POST['n_ocr'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_rollo_oc = "-1";
if (isset($_GET['n_ocr'])) {
  $colname_rollo_oc = (get_magic_quotes_gpc()) ? $_GET['n_ocr'] : addslashes($_GET['n_ocr']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_rollo_oc = sprintf("SELECT * FROM orden_compra_rollos WHERE n_ocr = %s", $colname_rollo_oc);
$rollo_oc = mysql_query($query_rollo_oc, $conexion1) or die(mysql_error());
$row_rollo_oc = mysql_fetch_assoc($rollo_oc);
$totalRows_rollo_oc = mysql_num_rows($rollo_oc);
?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr align="center"><td>
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr><td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
  <td id="cabezamenu"><ul id="menuhorizontal">
  <li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
  <li><a href="menu.php">MENU PRINCIPAL</a></li>
  <li><a href="compras.php">GESTION COMPRAS</a></li>
  </ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('fecha_pedido_ocr','','R','fecha_entrega_ocr','','R','condiciones_pago_ocr','','R','cantidad_ocr','','R','valor_unitario_ocr','','R','valor_neto_ocr','','R','iva_ocr','','R','valor_total_ocr','','R','calibre_micras_ocr','','R','calibre_millas_ocr','','R','ancho_material_ocr','','R','pigmento_exterior_ocr','','R','ref_pigmento_exterior_ocr','','R','pigmento_interior_ocr','','R','ref_pigmento_interior_ocr','','R','elaboro_ocr','','R','aprobo_ocr','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr id="tr1">            
            <td id="codigo" width="25%">CODIGO : A3 - F01 </td>
            <td colspan="2" id="titulo2" width="50%">ORDEN DE COMPRA </td>
            <td id="codigo" width="25%">VERSION : 1 </td>
		</tr>
          <tr>
            <td rowspan="7" id="dato2"><img src="images/logoacyc.jpg"></td>
            <td colspan="2" id="subtitulo">MATERIA PRIMA ( ROLLOS )</td>
            <td id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="numero2"><strong>N &deg; <?php echo $row_rollo_oc['n_ocr']; ?></strong></td>
            <td id="dato2"><img src="images/ciclo1.gif" alt="RESTAURAR" border="0" style="cursor:hand;" onClick="window.history.go()"/><a href="rollos_oc_vista.php?n_ocr=<?php echo $row_rollo_oc['n_ocr']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"/></a><a href="javascript:eliminar1('n_ocr',<?php echo $row_rollo_oc['n_ocr']; ?>,'rollos_oc_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"/></a><a href="rollos_oc.php"><img src="images/o.gif" alt="O.C. ROLLOS" border="0" style="cursor:hand;"/></a><a href="rollos.php"><img src="images/r.gif" alt="ROLLOS" border="0" style="cursor:hand;"/></a><a href="proveedores.php"><img src="images/p.gif" alt="PROVEEDORES" border="0" style="cursor:hand;"/></a></td>
          </tr>
          <tr>
            <td colspan="2" id="dato2"><a href="rollos_oc_edit1.php?n_ocr=<?php echo $row_rollo_oc['n_ocr']; ?>">Cambiar datos principales</a></td>
            <td id="dato3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" id="fuente2">&nbsp;</td>
            </tr>
          <tr>
            <td id="fuente2">FECHA DE PEDIDO </td>
            <td id="fuente2">FECHA DE ENTREGA </td>
            <td id="fuente2">CONDICIONES DE PAGO </td>
            </tr>
          <tr>
            <td id="dato2"><input type="text" name="fecha_pedido_ocr" value="<?php echo $row_rollo_oc['fecha_pedido_ocr']; ?>" size="10"></td>
            <td id="dato2"><input type="text" name="fecha_entrega_ocr" value="<?php echo $row_rollo_oc['fecha_entrega_ocr']; ?>" size="10"></td>
            <td id="dato2"><input type="text" name="condiciones_pago_ocr" value="<?php echo $row_rollo_oc['condiciones_pago_ocr']; ?>" size="20"></td>
            </tr>
          <tr>
            <td colspan="2" id="dato2">&nbsp;</td>
            <td id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="subtitulo2">DATOS DEL PROVEEDOR </td>
          </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1">PROVEEDOR</td>
            <td id="fuente1">NIT</td>
            <td id="fuente1">TIPO DE PROVEEDOR </td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><input name="id_p_ocr" type="hidden" value="<?php echo $row_rollo_oc['id_p_ocr']; ?>"><?php $proveedor=$row_rollo_oc['id_p_ocr'];
	if($proveedor!='')
	{
	$sqlp="SELECT * FROM proveedor WHERE id_p ='$proveedor'";
	$resultp= mysql_query($sqlp);
	$nump= mysql_num_rows($resultp);
	if($nump >='1')
	{ 
	$nombre = mysql_result($resultp,0,'proveedor_p');
	$nit_p = mysql_result($resultp,0,'nit_p');
	$tipo_p = mysql_result($resultp,0,'tipo_p');
    $direccion_p = mysql_result($resultp,0,'direccion_p');
	$pais_p = mysql_result($resultp,0,'pais_p');
	$ciudad_p = mysql_result($resultp,0,'ciudad_p');
    $telefono_p = mysql_result($resultp,0,'telefono_p');
    $fax_p = mysql_result($resultp,0,'fax_p');
	$contacto_p = mysql_result($resultp,0,'contacto_p');
	echo $nombre;
	} } ?></td>
            <td id="dato1"><?php echo $nit_p; ?></td>
            <td id="dato1"><?php if($tipo_p != '') {
			$sqltipo="SELECT * FROM tipo WHERE id_tipo ='$tipo_p'";
			$resultipo= mysql_query($sqltipo);
			$numtipo= mysql_num_rows($resultipo);
			if($numtipo >='1') { $nombre = mysql_result($resultipo,0,'nombre_tipo');	
			echo $nombre; } } ?></td>
          </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1">DIRECCION COMERCIAL </td>
            <td id="fuente1">PAIS</td>
            <td id="fuente1">CIUDAD</td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><?php echo $direccion_p; ?></td>
            <td id="dato1"><?php echo $ciudad_p; ?></td>
            <td id="dato1"><?php echo $pais_p; ?></td>
          </tr>          
          <tr id="tr1">
            <td colspan="2" id="fuente1">CONTACTO COMERCIAL </td>
            <td id="fuente1">TELEFONO</td>
            <td id="fuente1">FAX</td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><?php echo $contacto_p; ?></td>
            <td id="dato1"><?php echo $telefono_p; ?></td>
            <td id="dato1"><?php echo $fax_p; ?></td>
          </tr>
          <tr>
            <td colspan="4" id="subtitulo2">MATERIAL SOLICITADO </td>
            </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1">NOMBRE DEL ROLLO </td>
            <td id="fuente1">CODIGO DEL ROLLO </td>
            <td id="fuente1">UNIDAD DE MEDIDA </td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><?php $id_rollo=$row_rollo_oc['id_rollo_ocr'];
			if($id_rollo!='') {
			    $sqlrollo="SELECT * FROM materia_prima_rollos WHERE id_rollo ='$id_rollo'";
				$resultrollo= mysql_query($sqlrollo);
				$numrollo= mysql_num_rows($resultrollo);
				if($numrollo >='1')	{ 
				    $rollo = mysql_result($resultrollo,0,'nombre_rollo');
					$codigo = mysql_result($resultrollo,0,'cod_rollo');
					$medida = mysql_result($resultrollo,0,'medida_rollo');
					$presentacion = mysql_result($resultrollo,0,'presentacion_rollo');
					$tratamiento = mysql_result($resultrollo,0,'tratamiento_rollo');
					$calibre_rollo = mysql_result($resultrollo,0,'calibre_rollo');
					$ancho_rollo = mysql_result($resultrollo,0,'ancho_rollo');
					$ref_rollo = mysql_result($resultrollo,0,'ref_prod_rollo');
					} } echo $rollo; ?><input name="id_rollo_ocr" type="hidden" value="<?php echo $id_rollo; ?>"></td>
            <td id="dato1"><?php echo $codigo; ?></td>
            <td id="dato1"><?php if($medida!='') { 
    $sqlm="SELECT * FROM medida WHERE id_medida ='$medida'";
	$resultm= mysql_query($sqlm);
	$numedida= mysql_num_rows($resultm);
	if($numedida >='1') { $nombre_medida = mysql_result($resultm,0,'nombre_medida');
	echo $nombre_medida; } } ?></td>
          </tr>
          <tr id="tr1">
            <td id="fuente1">CANTIDAD</td>
            <td id="fuente1">VALOR UNITARIO </td>
            <td id="fuente1">VALOR NETO </td>
            <td id="fuente1">VALOR IVA </td>
          </tr>
          <tr>
            <td><input type="text" name="cantidad_ocr" value="<?php echo $row_rollo_oc['cantidad_ocr']; ?>" size="20" onBlur="ocr_total()"></td>
            <td><input type="text" name="valor_unitario_ocr" value="<?php echo $row_rollo_oc['valor_unitario_ocr']; ?>" size="20" onBlur="ocr_total()"></td>
            <td><input type="text" name="valor_neto_ocr" value="<?php echo $row_rollo_oc['valor_neto_ocr']; ?>" size="20" onBlur="ocr_total()"></td>
            <td><input type="text" name="iva_ocr" value="<?php echo $row_rollo_oc['iva_ocr']; ?>" size="20" onBlur="ocr_total()"></td>
          </tr>
          <tr>
            <td colspan="3" rowspan="2" id="dato1">Seg&uacute;n el numeral 3.1 de Gestion de Compras. La cantidad de polietileno impreso a solicitar sea mayor o igual a una tonelada (1 Ton), el material se debe de pedir al proveedor cuya clasificacion sea alta. </td>
            <td id="fuente1"><strong>VALOR TOTAL</strong></td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="valor_total_ocr" value="<?php echo $row_rollo_oc['valor_total_ocr']; ?>" size="20" onBlur="ocr_total()"></td>
          </tr>
          <tr>
            <td colspan="4" id="subtitulo2">ESPECIFICACIONES TECNICAS DE LAS BOBINAS </td>
            </tr>
          <tr id="tr1">
            <td id="fuente1">PEDIDO</td>
            <td id="fuente1">REF. DEL PRODUCTO </td>
            <td id="fuente1">CALIBRE REF.(micras) </td>
            <td id="fuente1">CALIBRE ROLLO (micras)</td>
          </tr>
          <tr>
            <td id="dato1"><input name="pedido_ocr" type="hidden" id="pedido_ocr" value="<?php echo $row_rollo_oc['pedido_ocr']; ?>"><?php echo $row_rollo_oc['pedido_ocr']; ?></td>
            <td id="dato1"><?php if($row_rollo_oc['id_ref_ocr']!='') { $id_ref=$row_rollo_oc['id_ref_ocr']; } else { $id_ref=$ref_rollo; }		
			if($id_ref!='') { 
			$sqlref="SELECT * FROM Tbl_referencia WHERE id_ref ='$id_ref'";
			$resultref= mysql_query($sqlref);
			$numref= mysql_num_rows($resultref);
			if($numref >='1') { 
					$cod_ref = mysql_result($resultref,0,'cod_ref');
					$version_ref = mysql_result($resultref,0,'version_ref');
					$material_ref = mysql_result($resultref,0,'material_ref');
					$calibre_ref = mysql_result($resultref,0,'calibre_ref');
					$n_egp_ref = mysql_result($resultref,0,'n_egp_ref');
					$ancho_ref = mysql_result($resultref,0,'ancho_ref');
					$largo_ref = mysql_result($resultref,0,'largo_ref');
					$solapa_ref = mysql_result($resultref,0,'solapa_ref'); } } ?><input name="id_ref_ocr" type="hidden" value="<?php echo $id_ref; ?>"><?php echo $cod_ref; ?> - <?php echo $version_ref; ?></td>
            <td id="dato1"><?php echo $calibre_ref; ?></td>
            <td id="dato1"><input type="text" name="calibre_micras_ocr" value="<?php echo $calibre_rollo; ?>" size="10" onBlur="ocr_calibremillas()"></td>
          </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1">PRESENTACION DEL MATERIAL </td>
            <td id="fuente1">CALIBRE ROLLO (millas)</td>
            <td id="fuente1">ANCHO MATERIAL (cm/pulg) </td>
          </tr>
          <tr>
            <td colspan="2" id="dato1"><select name="presentacion_material_ocr" id="presentacion_material_ocr">
                <option value="N.A" <?php if (!(strcmp("N.A", $presentacion))) {echo "selected=\"selected\"";} ?>>N.A</option>
                <option value="Lamina" <?php if (!(strcmp("Lamina", $presentacion))) {echo "selected=\"selected\"";} ?>>Lamina</option>
                <option value="Semitubular" <?php if (!(strcmp("Semitubular", $presentacion))) {echo "selected=\"selected\"";} ?>>Semitubular</option>
                <option value="Tubular" <?php if (!(strcmp("Tubular", $presentacion))) {echo "selected=\"selected\"";} ?>>Tubular</option>
            </select></td>
            <td id="dato1"><input type="text" name="calibre_millas_ocr" value="<?php echo $row_rollo_oc['calibre_millas_ocr']; ?>" size="10" onBlur="ocr_calibremillas()"></td>
            <td id="dato1"><input type="text" name="ancho_material_ocr" value="<?php echo $ancho_rollo; ?>" size="10"></td>
          </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1">TRATAMIENTO CORONA </td>
            <td colspan="2" id="fuente1">TIPO DE EXTRUSION </td>
            </tr>
          <tr>
            <td colspan="2" id="dato1"><select name="tratamiento_corona_ocr" id="tratamiento_corona_ocr">
                <option value="N.A." <?php if (!(strcmp("N.A.", $tratamiento))) {echo "selected=\"selected\"";} ?>>N.A.</option>
                <option value="1 cara" <?php if (!(strcmp("1 cara", $tratamiento))) {echo "selected=\"selected\"";} ?>>1 cara</option>
                <option value="2 caras" <?php if (!(strcmp("2 caras", $tratamiento))) {echo "selected=\"selected\"";} ?>>2 caras</option>
            </select></td>
            <td colspan="2" id="dato1"><?php echo $material_ref; ?></td>
            </tr>
          <tr id="tr1">
            <td id="fuente1">PIGMENTO EXTERIOR </td>
            <td id="fuente1">REFERENCIA</td>
            <td id="fuente1">PIGMENTO INTERIOR </td>
            <td id="fuente1">REFERENCIA</td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="pigmento_exterior_ocr" value="<?php echo $row_rollo_oc['pigmento_exterior_ocr']; ?>" size="20"></td>
            <td id="dato1"><input type="text" name="ref_pigmento_exterior_ocr" value="<?php echo $row_rollo_oc['ref_pigmento_exterior_ocr']; ?>" size="20"></td>
            <td id="dato1"><input type="text" name="pigmento_interior_ocr" value="<?php echo $row_rollo_oc['pigmento_interior_ocr']; ?>" size="20"></td>
            <td id="dato1"><input type="text" name="ref_pigmento_interior_ocr" value="<?php echo $row_rollo_oc['ref_pigmento_interior_ocr']; ?>" size="20"></td>
          </tr><?php $pedido= $row_rollo_oc['pedido_ocr']; if($pedido=='Nuevo') { ?>
          <tr>
            <td colspan="4" id="subtitulo2">ESPECIFICACIONES TECNICAS DE LA IMPRESION </td>
            </tr><?php if($n_egp_ref != '') {
  $sqlegp="SELECT * FROM Tbl_egp WHERE n_egp ='$n_egp_ref'";
  $resultegp= mysql_query($sqlegp);
  $numegp= mysql_num_rows($resultegp);
  if($numegp >='1') { 
	$color1_egp = mysql_result($resultegp,0,'color1_egp');
	$pantone1_egp = mysql_result($resultegp,0,'pantone1_egp');
	$color2_egp = mysql_result($resultegp,0,'color2_egp');
	$pantone2_egp = mysql_result($resultegp,0,'pantone2_egp');
	$color3_egp = mysql_result($resultegp,0,'color3_egp');
	$pantone3_egp = mysql_result($resultegp,0,'pantone3_egp');
	$color4_egp = mysql_result($resultegp,0,'color4_egp');
	$pantone4_egp = mysql_result($resultegp,0,'pantone4_egp');
	$color5_egp = mysql_result($resultegp,0,'color5_egp');
	$pantone5_egp = mysql_result($resultegp,0,'pantone5_egp');
	$color6_egp = mysql_result($resultegp,0,'color6_egp');
	$pantone6_egp = mysql_result($resultegp,0,'pantone6_egp');
	} } ?>
          <tr>
            <td colspan="4" id="fuente1">COLORES DE IMPRESION </td>
            </tr>
          <tr>
            <td id="detalle1">Color 1 : <?php echo $color1_egp; ?></td>
            <td id="detalle1">Pantone 1 : <?php echo $pantone1_egp; ?></td>
            <td id="detalle1">Color 4 : <?php echo $color4_egp; ?></td>
            <td id="detalle1">Pantone 4 : <?php echo $pantone4_egp; ?></td>
          </tr>
          <tr>
            <td id="detalle1">Color 2 : <?php echo $color2_egp; ?></td>
            <td id="detalle1">Pantone 2 : <?php echo $pantone2_egp; ?></td>
            <td id="detalle1">Color 5 : <?php echo $color5_egp; ?></td>
            <td id="detalle1">Pantone 5 : <?php echo $pantone5_egp; ?></td>
          </tr>
          <tr>
            <td id="detalle1">Color 3 : <?php echo $color3_egp; ?></td>
            <td id="detalle1">Pantone 3 : <?php echo $pantone3_egp; ?></td>
            <td id="detalle1">Color 6 : <?php echo $color6_egp; ?></td>
            <td id="detalle1">Pantone 6 : <?php echo $pantone6_egp; ?></td>
          </tr>   
          <tr>
            <td id="detalle1">Color 7 : <?php echo $color7_egp; ?></td>
            <td id="detalle1">Pantone 7 : <?php echo $pantone7_egp; ?></td>
            <td id="detalle1">Color 8 : <?php echo $color8_egp; ?></td>
            <td id="detalle1">Pantone 8 : <?php echo $pantone8_egp; ?></td>
          </tr>
          <tr>
            <td colspan="4" id="fuente1">DIMENSIONES DE LA BOLSA FORMADA </td>
            </tr>
          <tr id="tr1">
            <td id="fuente1">REPETICION RODILLO </td>
            <td id="fuente1">ANCHO</td>
            <td id="fuente1">LARGO</td>
            <td id="fuente1">SOLAPA</td>
          </tr>
          <tr>
            <td id="dato1"><?php $id_ref_rev=$row_rollo_oc['id_ref_ocr']; 
			if($id_ref_rev != '') { 
			$sqlrev="SELECT * FROM revision WHERE id_ref_rev ='$id_ref_rev'";
			$resultrev= mysql_query($sqlrev);
			$numrev= mysql_num_rows($resultrev);
			if($numrev >='1') { $repeticion_rev = mysql_result($resultrev,0,'repeticion_rev'); } } ?><input type="text" name="repeticion_rodillo_ocr" value="<?php echo $repeticion_rev; ?>" size="10"></td>
            <td id="dato1"><input type="text" name="ancho_bolsa_ocr" value="<?php echo $ancho_ref; ?>" size="10"></td>
            <td id="dato1"><input type="text" name="largo_bolsa_ocr" value="<?php echo $largo_ref; ?>" size="10"></td>
            <td id="dato1"><input type="text" name="solapa_bolsa_ocr" value="<?php echo $solapa_ref; ?>" size="10"></td>
          </tr>
          <tr>
            <td colspan="4" id="fuente1">ARTE</td>
            </tr>
          <tr>
            <td colspan="2" id="detalle1">              <input type="checkbox" name="anexa_arte_ocr" value="1" <?php if (!(strcmp($row_rollo_oc['anexa_arte_ocr'],1))) {echo "checked=\"checked\"";} ?>>
              Se anexa arte </td>
            <td colspan="2" id="detalle1">              <input type="checkbox" name="negativo_ocr" value="1" <?php if (!(strcmp($row_rollo_oc['negativo_ocr'],1))) {echo "checked=\"checked\"";} ?>>
              Se entrega negativo </td>
          </tr>
          <tr>
            <td colspan="2" id="detalle1">              <input type="checkbox" name="anexa_arte_impreso_ocr" value="1" <?php if (!(strcmp($row_rollo_oc['anexa_arte_impreso_ocr'],1))) {echo "checked=\"checked\"";} ?>>
              Se anexa arte impreso </td>
            <td colspan="2" id="detalle1">              <input type="checkbox" name="cyrell_ocr" value="1" <?php if (!(strcmp($row_rollo_oc['cyrell_ocr'],1))) {echo "checked=\"checked\"";} ?>>
              Se entrega cirel </td>
          </tr><?php } ?>          
          <tr>
            <td colspan="4" id="subtitulo2">OBSERVACIONES GENERALES </td>
            </tr>
          <tr>
            <td colspan="4" id="detalle1">1. Favor remitirse a las especificaciones t&eacute;cnicas del material y de impresi&oacute;n durante la producci&oacute;n. <br>
2. Es muy importante que las caracter&iacute;sticas t&eacute;cnicas de la bolsa se respeten. En caso de alguna duda comuniquela inmediatamente.<br>
3. NO DEBE DE APARECER  EL LOGO DEL IMPRESOR POR NING&Uacute;N MOTIVO. <br>
4. Debe de revisar bien los sellos y la resistencia de estos.</td>
            </tr>
          <tr id="tr1">
            <td colspan="4" id="fuente1">OTRAS OBSERVACIONES</td>
          </tr>
          <tr>
            <td colspan="4" id="dato1"><textarea name="observacion_ocr" cols="80" rows="3"><?php echo $row_rollo_oc['observacion_ocr']; ?></textarea></td>
            </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente2">ELABORADO</td>
            <td colspan="2" id="fuente2">APROBADO</td>
          </tr>
          <tr>
            <td colspan="2" id="dato2"><input type="text" name="elaboro_ocr" value="<?php echo $row_rollo_oc['elaboro_ocr']; ?>" size="30"></td>
            <td colspan="2" id="dato2"><input type="text" name="aprobo_ocr" value="<?php echo $row_rollo_oc['aprobo_ocr']; ?>" size="30"></td>
          </tr>
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input type="submit" value="FINALIZAR O.C. ROLLOS"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="n_ocr" value="<?php echo $row_rollo_oc['n_ocr']; ?>">
      </form></td>
  </tr>
  <tr>
    <td colspan="2" align="center">&nbsp;</td>
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
mysql_free_result($usuario);

mysql_free_result($rollo_oc);
?>