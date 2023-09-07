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
  $updateSQL = sprintf("UPDATE proveedor_seleccion SET id_p_seleccion=%s, directo_p=%s, punto_dist_p=%s, forma_pago_p=%s, otra_p=%s, sist_calidad_p=%s, norma_p=%s, certificado_p=%s, frecuencia_p=%s, analisis_p=%s, muestra_p=%s, orden_compra_p=%s, mayor_p=%s, tiempo_agil_p=%s, tiempo_p=%s, entrega_p=%s, metodos_p=%s, flete_p=%s, requisito_p=%s, plan_mejora_p=%s, aspecto_p=%s, precios_p=%s, otro_caso_p=%s, asesor_com_p=%s, nombre_asesor_p=%s, limite_min_p=%s, cuanto_p=%s, proceso_p=%s, primera_calificacion_p=%s, encuestador_p=%s, cargo_p=%s, fecha_encuesta_p=%s, ultima_calificacion_p=%s, registro_ultima_calificacion=%s, fecha_ultima_calificacion_p=%s WHERE id_seleccion=%s",
                       GetSQLValueString($_POST['id_p_seleccion'], "int"),
                       GetSQLValueString($_POST['directo_p'], "int"),
                       GetSQLValueString($_POST['punto_dist_p'], "text"),
                       GetSQLValueString($_POST['forma_pago_p'], "int"),
                       GetSQLValueString($_POST['otra_p'], "text"),
                       GetSQLValueString($_POST['sist_calidad_p'], "int"),
                       GetSQLValueString($_POST['norma_p'], "text"),
                       GetSQLValueString($_POST['certificado_p'], "int"),
                       GetSQLValueString($_POST['frecuencia_p'], "text"),
                       GetSQLValueString($_POST['analisis_p'], "int"),
                       GetSQLValueString($_POST['muestra_p'], "text"),
                       GetSQLValueString($_POST['orden_compra_p'], "int"),
                       GetSQLValueString($_POST['mayor_p'], "text"),
                       GetSQLValueString($_POST['tiempo_agil_p'], "int"),
                       GetSQLValueString($_POST['tiempo_p'], "text"),
                       GetSQLValueString($_POST['entrega_p'], "int"),
                       GetSQLValueString($_POST['metodos_p'], "text"),
                       GetSQLValueString($_POST['flete_p'], "int"),
                       GetSQLValueString($_POST['requisito_p'], "text"),
                       GetSQLValueString($_POST['plan_mejora_p'], "int"),
                       GetSQLValueString($_POST['aspecto_p'], "text"),
                       GetSQLValueString($_POST['precios_p'], "int"),
                       GetSQLValueString($_POST['otro_caso_p'], "text"),
                       GetSQLValueString($_POST['asesor_com_p'], "int"),
                       GetSQLValueString($_POST['nombre_asesor_p'], "text"),
                       GetSQLValueString($_POST['limite_min_p'], "int"),
                       GetSQLValueString($_POST['cuanto_p'], "text"),
                       GetSQLValueString($_POST['proceso_p'], "int"),
                       GetSQLValueString($_POST['primera_calificacion_p'], "double"),
                       GetSQLValueString($_POST['encuestador_p'], "text"),
                       GetSQLValueString($_POST['cargo_p'], "text"),
                       GetSQLValueString($_POST['fecha_encuesta_p'], "date"),
                       GetSQLValueString($_POST['ultima_calificacion_p'], "double"),
                       GetSQLValueString($_POST['registro_ultima_calificacion'], "text"),
                       GetSQLValueString($_POST['fecha_ultima_calificacion_p'], "date"),
                       GetSQLValueString($_POST['id_seleccion'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateGoTo = "proveedor_edit.php?id_p=" . $_POST['id_p_seleccion'] . "";
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

$colname_seleccion_edit = "-1";
if (isset($_GET['id_p'])) {
  $colname_seleccion_edit = (get_magic_quotes_gpc()) ? $_GET['id_p'] : addslashes($_GET['id_p']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_seleccion_edit = sprintf("SELECT * FROM proveedor_seleccion WHERE id_p_seleccion = %s", $colname_seleccion_edit);
$seleccion_edit = mysql_query($query_seleccion_edit, $conexion1) or die(mysql_error());
$row_seleccion_edit = mysql_fetch_assoc($seleccion_edit);
$totalRows_seleccion_edit = mysql_num_rows($seleccion_edit);
?><html>
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
	</ul>
</td>
</tr>  
  <tr>
    <td colspan="2" align="center">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" onSubmit="MM_validateForm('primera_calificacion_p','','R','fecha_encuesta_p','','R','encuestador_p','','R','cargo_p','','R');return document.MM_returnValue">
        <table id="tabla2">
          <tr id="tr2">
            <td colspan="2" id="titulo1"><input name="id_p_seleccion" type="hidden" value="<?php echo $row_seleccion_edit['id_p_seleccion']; ?>">
              III. ENCUESTA PARA LA CALIFICACION DEL PROVEEDOR ( EDITAR ) </td>
          </tr>
          <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>1</strong>. Es una empresa que ofrece directamente sus productos y/o servicios,los subcontrata o tiene distribuidores ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="directo_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['directo_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['directo_p']))) {echo "selected=\"selected\"";} ?>>Directo</option>
            <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['directo_p']))) {echo "selected=\"selected\"";} ?>>Distribuidor</option>
            <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['directo_p']))) {echo "selected=\"selected\"";} ?>>Subcontrata</option>
        </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Puntos de distribuci&oacute;n ? </td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="punto_dist_p" cols="70" rows="2"><?php echo $row_seleccion_edit['punto_dist_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>2</strong>. Ofrece Formas de Pago ? </td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="forma_pago_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['forma_pago_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['forma_pago_p']))) {echo "selected=\"selected\"";} ?>>30 a 60 dias</option>
            <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['forma_pago_p']))) {echo "selected=\"selected\"";} ?>>15 a 29 dias</option>
            <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['forma_pago_p']))) {echo "selected=\"selected\"";} ?>>Contado a 14 dias</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Otra, Cual ? </td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="otra_p" cols="70" rows="2"><?php echo $row_seleccion_edit['otra_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>3</strong>. Tiene Sistema de Gesti&oacute;n de Calidad certificado ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="sist_calidad_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['sist_calidad_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['sist_calidad_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
          <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['sist_calidad_p']))) {echo "selected=\"selected\"";} ?>>En proceso</option>
            <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['sist_calidad_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Con cual Norma y que porcentaje de Avance ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="norma_p" cols="70" rows="2"><?php echo $row_seleccion_edit['norma_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>4</strong>. Entrega certificado de calidad de sus productos con cada despacho (insumos) u ofrece garantia al servicio ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="certificado_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['certificado_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['certificado_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
          <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['certificado_p']))) {echo "selected=\"selected\"";} ?>>Algunas veces</option>
          <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['certificado_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Con que frecuencia ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="frecuencia_p" cols="70" rows="2"><?php echo $row_seleccion_edit['frecuencia_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>5</strong>. Realiza analisis de control de calidad a cada lote de material ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="analisis_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['analisis_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['analisis_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
          <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['analisis_p']))) {echo "selected=\"selected\"";} ?>>Por muestreo</option>
            <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['analisis_p']))) {echo "selected=\"selected\"";} ?>>No</option>
        </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Si es por muestra, cada cuanto ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="muestra_p" cols="70" rows="2"><?php echo $row_seleccion_edit['muestra_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>6</strong>. Requiere orden de compra con anterioridad ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="orden_compra_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['orden_compra_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['orden_compra_p']))) {echo "selected=\"selected\"";} ?>>1 a 15 d&iacute;as</option>
            <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['orden_compra_p']))) {echo "selected=\"selected\"";} ?>>16 a 30 d&iacute;as</option>
            <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['orden_compra_p']))) {echo "selected=\"selected\"";} ?>>Mayor a 30 d&iacute;as</option>
        </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Si es mayor a 30 en cuanto tiempo?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="mayor_p" cols="70" rows="2"><?php echo $row_seleccion_edit['mayor_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>7</strong>. Tiene establecido un tiempo para la agilidad de respuesta ante un reclamo ?</td>
        </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="tiempo_agil_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['tiempo_agil_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['tiempo_agil_p']))) {echo "selected=\"selected\"";} ?>>El mismo dia</option>
            <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['tiempo_agil_p']))) {echo "selected=\"selected\"";} ?>>1 semana</option>
            <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['tiempo_agil_p']))) {echo "selected=\"selected\"";} ?>>1 mes</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Cuanto tiempo ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="tiempo_p" cols="70" rows="2"><?php echo $row_seleccion_edit['tiempo_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>8</strong>. Realiza entrega del producto o servicio en las instalaciones de la empresa ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="entrega_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['entrega_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['entrega_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
          <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['entrega_p']))) {echo "selected=\"selected\"";} ?>>Con intermediario</option>
            <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['entrega_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Otros metodos ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="metodos_p" cols="70" rows="2"><?php echo $row_seleccion_edit['metodos_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>9</strong>. El flete correspondiente a la entrega corre por parte del proveedor ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="flete_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['flete_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['flete_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
          <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['flete_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">&oacute; cuando se establece ese requisito ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="requisito_p" cols="70" rows="2"><?php echo $row_seleccion_edit['requisito_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>10</strong>. Tiene establecido un plan de mejora para el producto, servicios y/o sus procesos?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="plan_mejora_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['plan_mejora_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['plan_mejora_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
          <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['plan_mejora_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">En que aspectos ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="aspecto_p" cols="70" rows="2"><?php echo $row_seleccion_edit['aspecto_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>11</strong>. Maneja listado de precios actualizado ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="precios_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['precios_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['precios_p']))) {echo "selected=\"selected\"";} ?>>Anual</option>
            <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['precios_p']))) {echo "selected=\"selected\"";} ?>>Semestral</option>
            <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['precios_p']))) {echo "selected=\"selected\"";} ?>>Otro (&lt; 6 meses)</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">En caso de otro, explique.</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="otro_caso_p" cols="70" rows="2"><?php echo $row_seleccion_edit['otro_caso_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>12</strong>. Asigna asesores comerciales a cada empresa ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="asesor_com_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['asesor_com_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['asesor_com_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
          <option value="3" <?php if (!(strcmp(3, $row_seleccion_edit['asesor_com_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Nombre ? </td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="nombre_asesor_p" cols="70" rows="2"><?php echo $row_seleccion_edit['nombre_asesor_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>13</strong>. Tiene limites minimos de pedido ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="limite_min_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['limite_min_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['limite_min_p']))) {echo "selected=\"selected\"";} ?>>No</option>
          <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['limite_min_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
                                        </select></td>
        </tr>
      <tr>
        <td colspan="2" id="fuente1">Cuanto ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><textarea name="cuanto_p" cols="70" rows="2"><?php echo $row_seleccion_edit['cuanto_p']; ?></textarea></td>
        </tr>
      <tr id="tr1">
        <td colspan="2" id="fuente1"><strong>14</strong>. Cuentan con un proceso definido para preservar y manejar el material o equipo suministrado por el cliente ?</td>
      </tr>
      <tr>
        <td colspan="2" id="dato1"><select name="proceso_p" onBlur="calificacion()">
          <option value="0" <?php if (!(strcmp(0, $row_seleccion_edit['proceso_p']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="5" <?php if (!(strcmp(5, $row_seleccion_edit['proceso_p']))) {echo "selected=\"selected\"";} ?>>Si</option>
          <option value="1" <?php if (!(strcmp(1, $row_seleccion_edit['proceso_p']))) {echo "selected=\"selected\"";} ?>>No</option>
                </select></td>
        </tr>
          <tr id="tr1">
            <td colspan="2" id="fuente1"><strong>Nota</strong>: En algunos casos puede que su empresa no aplique a alguno de los items anteriores. Por ejemplo, si la pregunta hace referencia a un producto (tangible) y su empresa es de servicios, si es el caso por favor se&ntilde;ale la casilla <strong>NO</strong> de la columna <strong> No Aplica</strong></td>
          </tr>
          <tr id="tr2">
            <td id="fuente1">CALIFICACION INICIAL (%) </td>
            <td id="fuente1">FECHA DE ENCUESTA </td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="primera_calificacion_p" value="<?php echo $row_seleccion_edit['primera_calificacion_p']; ?>" size="10"></td>
            <td id="dato1"><input type="text" name="fecha_encuesta_p" value="<?php echo $row_seleccion_edit['fecha_encuesta_p']; ?>" size="10"></td>
          </tr>
          <tr id="tr2">
            <td id="fuente1">NOMBRE DEL ENCUESTADOR </td>
            <td id="fuente1">CARGO DEL ENCUESTADOR </td>
          </tr>
          <tr>
            <td id="dato1"><input type="text" name="encuestador_p" value="<?php echo $row_seleccion_edit['encuestador_p']; ?>" size="30"></td>
            <td id="dato1"><input type="text" name="cargo_p" value="<?php echo $row_seleccion_edit['cargo_p']; ?>" size="30"></td>
          </tr>
          <tr id="tr2">
            <td id="fuente1">ULTIMA CALIFICACION (%)              </td>
            <td id="fuente1">FECHA ULTIMA CALIFICACION </td>
          </tr>
          <tr>
            <td id="fuente1"><input type="text" name="ultima_calificacion_p" value="<?php echo $row_seleccion_edit['ultima_calificacion_p']; ?>" size="10"></td>
            <td id="dato1"><input type="text" name="fecha_ultima_calificacion_p" value="<?php echo $row_seleccion_edit['fecha_ultima_calificacion_p']; ?>" size="10"></td>
          </tr>
          <tr id="tr2">
            <td colspan="2" id="fuente1">EVALUADO POR 
              <input type="text" name="registro_ultima_calificacion" value="<?php echo $row_seleccion_edit['registro_ultima_calificacion']; ?>" size="30"></td>
          </tr>
          <tr>
            <td colspan="2" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" id="dato2"><input type="submit" value="Actualizar ENCUESTA"></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_seleccion" value="<?php echo $row_seleccion_edit['id_seleccion']; ?>">
      </form></td></tr>
	  <tr>
      <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center"><a href="javascript:eliminar1('id_seleccion',<?php echo $row_seleccion_edit['id_seleccion']; ?>,'proveedor_seleccion_edit.php')"><img src="images/por.gif" alt="ELIMINAR" border="0" style="cursor:hand;"></a><a href="proveedor_edit.php?id_p=<?php echo $row_seleccion_edit['id_p_seleccion']; ?>"><img src="images/menos.gif" alt="EDITAR PROVEEDOR" border="0" style="cursor:hand;" /></a><a href="proveedor_vista.php?id_p=<?php echo $row_seleccion_edit['id_p_seleccion']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" border="0" style="cursor:hand;"></a><a href="proveedores.php"><img src="images/cat.gif" border="0" style="cursor:hand;" alt="LISTADO PROVEEDORES" /></a><a href="proveedor_busqueda.php"><img src="images/embudo.gif" alt="FILTRO" border="0" style="cursor:hand;"/></a></td>
    </tr>
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

mysql_free_result($seleccion_edit);
?>
