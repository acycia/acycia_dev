<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config.php');
require (ROOT_BBDD); 
?> 
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

$conexion = new ApptivaDB();

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE  Tbl_egp SET ancho_egp=%s, largo_egp=%s, solapa_egp=%s, largo_cang_egp=%s, adhesivo_egp=%s, tipo_bolsa_egp=%s, tipo_sello_egp=%s, tipo_solapatr_egp=%s, tipo_cinta_egp=%s, tipo_superior_egp=%s, tipo_principal_egp=%s, tipo_inferior_egp=%s, cb_solapatr_egp=%s, cb_cinta_egp=%s, cb_superior_egp=%s, cb_principal_egp=%s, cb_inferior_egp=%s, unids_paq_egp=%s, unids_caja_egp=%s, observacion5_egp=%s, responsable_modificacion=%s, fecha_modificacion=%s, hora_modificacion=%s WHERE n_egp=%s",
                       GetSQLValueString($_POST['ancho_ref'], "double"),
                       GetSQLValueString($_POST['largo_ref'], "double"),
					   GetSQLValueString($_POST['solapa_ref'], "double"),
					   GetSQLValueString($_POST['bolsillo_guia_ref'], "double"),
					   GetSQLValueString($_POST['adhesivo_ref'], "text"),				   					   					   
                       GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),
                       GetSQLValueString($_POST['tipo_sello_egp'], "text"),				   					   
                       GetSQLValueString($_POST['tipo_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['tipo_cinta_egp'], "text"),
					   GetSQLValueString($_POST['tipo_superior_egp'], "text"),
                       GetSQLValueString($_POST['tipo_principal_egp'], "text"),
                       GetSQLValueString($_POST['tipo_inferior_egp'], "text"),
                       GetSQLValueString($_POST['cb_solapatr_egp'], "text"),
                       GetSQLValueString($_POST['cb_cinta_egp'], "text"),
					   GetSQLValueString($_POST['cb_superior_egp'], "text"),
                       GetSQLValueString($_POST['cb_principal_egp'], "text"),
                       GetSQLValueString($_POST['cb_inferior_egp'], "text"),
                       GetSQLValueString($_POST['unids_paq_egp'], "int"),
                       GetSQLValueString($_POST['unids_caja_egp'], "int"),
                       GetSQLValueString($_POST['observacion5_egp'], "text"),
                       GetSQLValueString($_POST['registro2_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro2_ref'], "date"),
                       GetSQLValueString($_POST['hora_modificacion'], "text"),
					   GetSQLValueString($_POST['cod_ref'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

  $updateSQL2 = sprintf("UPDATE Tbl_referencia SET tipo_bolsa_ref=%s, ancho_ref=%s, largo_ref=%s, solapa_ref=%s, bolsillo_guia_ref=%s, str_bols_ub_ref=%s, str_bols_fo_ref=%s, bol_lamina_1_ref=%s, bol_lamina_2_ref=%s, B_troquel=%s, N_fuelle=%s, adhesivo_ref=%s, registro1_ref=%s, fecha_registro1_ref=%s, registro2_ref=%s, fecha_registro2_ref=%s WHERE  id_ref=%s",
                       GetSQLValueString($_POST['tipo_bolsa_ref'], "text"),                       
                       GetSQLValueString($_POST['ancho_ref'], "double"),
                       GetSQLValueString($_POST['largo_ref'], "double"),
					   GetSQLValueString($_POST['solapa_ref'], "double"),					   
                       GetSQLValueString($_POST['bolsillo_guia_ref'], "double"),
					   GetSQLValueString($_POST['str_bols_ub_ref'], "text"),
					   GetSQLValueString($_POST['str_bols_fo_ref'], "text"),
					   GetSQLValueString($_POST['bol_lamina_1_ref'], "double"),
					   GetSQLValueString($_POST['bol_lamina_2_ref'], "double"),		  
					   GetSQLValueString($_POST['B_troquel'], "double"),
                       GetSQLValueString($_POST['N_fuelle'], "double"),
                       GetSQLValueString($_POST['adhesivo_ref'], "text"),
                       GetSQLValueString($_POST['registro1_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro1_ref'], "date"),
                       GetSQLValueString($_POST['registro2_ref'], "text"),
                       GetSQLValueString($_POST['fecha_registro2_ref'], "date"),
					   GetSQLValueString($_POST['id_ref'], "int"));
  mysql_select_db($database_conexion1, $conexion1);
  $Result2 = mysql_query($updateSQL2, $conexion1) or die(mysql_error());  	  

  $updateGoTo = "produccion_caract_sellado_vista.php?id_ref=" . $_POST['id_ref'] . "&cod_ref=" . $_POST['cod_ref'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
$colname_usuario = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_usuario = $_SESSION['MM_Username'];
}
mysql_select_db($database_conexion1, $conexion1);
$query_usuario = sprintf("SELECT * FROM usuario WHERE usuario = %s", GetSQLValueString($colname_usuario, "text"));
$usuario = mysql_query($query_usuario, $conexion1) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);
$totalRows_usuario = mysql_num_rows($usuario);
//CONSULTA COLOR EGP
$colname_ref = "-1";
if (isset($_GET['id_ref'])) 
{
  $colname_ref= (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref = sprintf("SELECT * FROM Tbl_referencia,Tbl_egp WHERE Tbl_referencia.id_ref=%s AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp ",$colname_ref);
$ref = mysql_query($query_ref, $conexion1) or die(mysql_error());
$row_ref = mysql_fetch_assoc($ref);
$totalRows_ref = mysql_num_rows($ref);
//LLENA COMBOS DE MATERIAS PRIMAS
mysql_select_db($database_conexion1, $conexion1);
$query_materia_prima = "SELECT * FROM insumo WHERE clase_insumo='5' ORDER BY descripcion_insumo ASC";
$materia_prima = mysql_query($query_materia_prima, $conexion1) or die(mysql_error());
$row_materia_prima = mysql_fetch_assoc($materia_prima);
$totalRows_materia_prima = mysql_num_rows($materia_prima);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/validacion_numerico.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

<!-- desde aqui para listados nuevos -->
  <link rel="stylesheet" type="text/css" href="css/desplegable.css" />
  <link rel="stylesheet" type="text/css" href="css/general.css"/>

<!-- sweetalert -->
<script src="librerias/sweetalert/dist/sweetalert.min.js"></script> 
<link rel="stylesheet" type="text/css" href="librerias/sweetalert/dist/sweetalert.css">
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.2.min.js"></script> 
  <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

</head>
<body>
<?php echo $conexion->header('vistas'); ?>
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1">
    <table class="table table-bordered table-sm">
    <tr>
      <td id="fuente1"><table id="tabla3">
        <tr id="tr1">
          <td colspan="10" id="titulo2">CARACTERISTICAS DE SELLADO </td>
        </tr>
        <tr>
          <td width="137" colspan="2" rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
          <td colspan="8" id="dato3"><a href="produccion_mezclas.php"><img src="images/m.gif" style="cursor:hand;" alt="LISTADO MEZCLAS" title="LISTADO MEZCLAS" border="0" /></a><a href="produccion_caracteristicas.php"><img src="images/c.gif" style="cursor:hand;" alt="LISTADO CARACTERISTICAS" title="LISTADO CARACTERISTICAS" border="0" /></a><a href="produccion_referencias.php"><img src="images/rp.gif" style="cursor:hand;" alt="LISTADO REF. POR PROCESO" title="LISTADO REF. POR PROCESO" border="0" /></a><a href="menu.php"><img src="images/identico.gif" style="cursor:hand;" alt="MENU PRINCIPAL" title="MENU PRINCIPAL" border="0"/></a></td>
        </tr>
        <tr id="tr1">
          <td width="182" colspan="2" nowrap="nowrap" id="fuente1">Fecha Ingreso
            <input name="fecha_registro2_ref" type="date" id="fecha_registro2_ref" min="2000-01-02" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
          <td colspan="6" id="fuente1"> Ingresado por
            <input name="registro2_ref" type="text" id="registro2_ref" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="27" readonly="readonly"/></td>
        </tr>
        <tr id="tr3">
          <td colspan="2" nowrap="nowrap" id="fuente3">&nbsp;</td>
          <td width="126" colspan="2" nowrap="nowrap" id="fuente3">&nbsp;</td>
          <td width="235" colspan="4" id="fuente3">&nbsp;</td>
        </tr>
        <tr id="tr3">
          <td colspan="2" nowrap="nowrap" id="fuente2">Referencia</td>
          <td colspan="2" id="fuente2">Version</td>
          <td colspan="4" id="dato1">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_ref['cod_ref']; ?></td>
          <td colspan="2" nowrap="nowrap" id="numero2"><?php echo $row_ref['version_ref']; ?></td>
          <td colspan="4" id="fuente3">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" id="dato2">&nbsp;</td>
          <td colspan="2" id="dato2">&nbsp;</td>
          <td colspan="4" id="dato2"><datalist id="dias"></datalist></td>
        </tr>
        <!--<tr id="tr1">
          <td colspan="13" id="titulo4">SELLADO            </td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap"id="fuente1">TIPO DE CINTA DE CIERRE</td>
          <td nowrap="nowrap"id="fuente1">&nbsp;</td>
          <td nowrap="nowrap"id="fuente1">REFERENCIA</td>
          <td nowrap="nowrap"id="fuente1">&nbsp;</td>
          <td nowrap="nowrap"id="fuente1">&nbsp;</td>
          <td colspan="2" nowrap="nowrap"id="fuente1">CANTIDAD</td>
        </tr>
        <tr>
          <td colspan="2" nowrap="nowrap"><input name="color"  type="text"  id="color" placeholder="Color"value="<?php echo $row_ref['adhesivo_ref'] ?>" size="20" readonly="readonly"/></td>
          <td>&nbsp;</td>
          <td colspan="4"><select name="id[]" id="id[]" style="width:150px">
            <option value="">Ref</option>
            <?php
do {  
?>
            <option value="<?php echo $row_materia_prima['id_insumo']?>"<?php if (!(strcmp($row_materia_prima['id_insumo'], $var))) {echo "selected=\"selected\"";} ?>><?php echo $row_materia_prima['descripcion_insumo']?></option>
            <?php
} while ($row_materia_prima = mysql_fetch_assoc($materia_prima));
  $rows = mysql_num_rows($materia_prima);
  if($rows > 0) {
      mysql_data_seek($materia_prima, 0);
	  $row_materia_prima = mysql_fetch_assoc($materia_prima);
  }
?>
          </select></td>
          <td><input name="valor[]"  style="width:100px" min="0"step="1" type="number"  id="valor[]"  size="5" value=""/></td>
        </tr>-->
        <tr id="tr1">
          <td colspan="8" id="fuente3">&nbsp;</td>
        </tr>
        <tr id="tr1">
          <td colspan="8" id="titulo4">CARACTERISTICAS DE SELLADO</td>
        </tr>
        <tr>
          <td id="fuente1">Ancho            </td>
          <td id="fuente1"><input name="ancho_ref" id="ancho_ref" style="width:65px" min="0"step="0.01" type="number" readonly="readonly" size="5" value="<?php echo $row_ref['ancho_ref'] ?>"/></td>
          <td id="fuente1">Largo</td>
          <td id="fuente1"><input name="largo_ref" id="largo_ref" style="width:65px" min="0"step="0.01" type="number" readonly="readonly" size="5" value="<?php echo $row_ref['largo_ref'] ?>"/></td>
          <td colspan="3" id="fuente1">Solapa</td>
          <td id="fuente1"><input name="solapa_ref" id="solapa_ref" style="width:65px" min="0"step="0.01" type="number" size="5" value="<?php echo $row_ref['solapa_ref'] ?>"/></td>
        </tr>
        <tr>
          <td id="fuente1">Fuelle</td>
          <td id="fuente1"><input name="N_fuelle" id="N_fuelle" style="width:65px" min="0"step="0.01" type="number" size="5" value="<?php echo $row_ref['N_fuelle'] ?>"/></td>
          <td id="fuente1">Tipo de Cinta/Adhesivo/Liner</td>
          <td id="fuente1"><select name="adhesivo_ref" id="adhesivo_ref" style="width:70px">
            <option value="N.A." <?php if (!(strcmp("0", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="HOT MELT" <?php if (!(strcmp("HOT MELT", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>HOT MELT</option>
            <option value="CINTA PERMANENTE" <?php if (!(strcmp("CINTA PERMANENTE", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA PERMANENTE</option>
            <option value="CINTA RESELLABLE" <?php if (!(strcmp("CINTA RESELLABLE", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA RESELLABLE</option>
            <option value="CINTA DE SEGURIDAD" <?php if (!(strcmp("CINTA DE SEGURIDAD", $row_ref['adhesivo_ref']))) {echo "selected=\"selected\"";} ?>>CINTA DE SEGURIDAD</option>
          </select>        
        </td>
          <td colspan="3" id="fuente1">Tipo de Bolsa</td>
          <td id="fuente1"><select name="tipo_bolsa_ref" id="tipo_bolsa_ref" style="width:70px">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="SEGURIDAD" <?php if (!(strcmp("SEGURIDAD", $row_ref['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>SEGURIDAD</option>
            <option value="CURRIER" <?php if (!(strcmp("CURRIER", $row_ref['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>CURRIER</option>
            <option value="BOLSA PLASTICA" <?php if (!(strcmp("BOLSA PLASTICA", $row_ref['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA PLASTICA</option>
            <option value="BOLSA MONEDA" <?php if (!(strcmp("BOLSA MONEDA", $row_ref['tipo_bolsa_ref']))) {echo "selected=\"selected\"";} ?>>BOLSA MONEDA</option>
          </select></td>
        </tr>
        <tr>
          <td id="fuente1">Tipo de Sello</td>
          <td id="fuente1"><select name="tipo_sello_egp" id="tipo_sello_egp" style="width:70px">
            <option></option>
          <option value="N/A"<?php if (!(strcmp("N/A", $row_ref['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>N/A</option>
          <option value="HILO"<?php if (!(strcmp("HILO", $row_ref['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO</option>
          <option value="PLANO"<?php if (!(strcmp("PLANO", $row_ref['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>PLANO</option>
          <option value="HILO/PLANO"<?php if (!(strcmp("HILO/PLANO", $row_ref['tipo_sello_egp']))) {echo "selected=\"selected\"";} ?>>HILO/PLANO</option>
      </select>
        </td>
          <td id="fuente1">Und. X Caja</td>
          <td id="fuente1"><input name="unids_caja_egp" id="unids_caja_egp" style="width:65px" min="0" type="number" size="5" value="<?php echo $row_ref['unids_caja_egp'] ?>" required="required"/></td>
          <td colspan="3" id="fuente1">Und. X Paquete</td>
          <td id="fuente1"><input name="unids_paq_egp" id="unids_paq_egp" style="width:65px" min="0"  type="number" size="5" required="required" value="<?php echo $row_ref['unids_paq_egp'] ?>"/></td>
        </tr>
        <tr>
          <td id="fuente1">Tama&ntilde;o mm(Forma -  Bolsillo)</td>
          <td id="fuente1"><input name="bolsillo_guia_ref" id="bolsillo_guia_ref" style="width:65px" min="0"step="0.01" type="number" size="5" value="<?php echo $row_ref['bolsillo_guia_ref'] ?>"/></td>
          <td id="fuente1">Bolsillo Portaguia (Anverso/Reverso) </td>
          <td id="fuente1"><select name="str_bols_ub_ref" id="str_bols_ub_ref" style="width:70px">
            <option value="">N.A.</option>
            <option value="ANVERSO"<?php if (!(strcmp('ANVERSO', $row_ref['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Anverso</option>
            <option value="REVERSO"<?php if (!(strcmp('REVERSO', $row_ref['str_bols_ub_ref']))) {echo "selected=\"selected\"";} ?>>Reverso</option>
          </select></td>
          <td colspan="3" id="fuente1">Bolsillo Portaguia   (Traslape/Resellable )</td>
          <td id="fuente1"><select name="str_bols_fo_ref" id="str_bols_fo_ref" style="width:70px">
            <option value="">N.A.</option>
              <option value="TRANSLAPE"<?php if (!(strcmp('TRANSLAPE', $row_ref['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Translape</option>
              <option value="RESELLABLE"<?php if (!(strcmp('RESELLABLE', $row_ref['str_bols_fo_ref']))) {echo "selected=\"selected\"";} ?>>Resellable</option>
          </select></td>
        </tr>
        <tr>
          <td id="fuente1">Lamina 1  (Forma -  Bolsillo)</td>
          <td id="fuente1"><input name="bol_lamina_1_ref" id="bol_lamina_1_ref" style="width:65px" min="0" type="number" size="5" value="<?php echo $row_ref['bol_lamina_1_ref'] ?>"/></td>
          <td id="fuente1">Lamina 2  (Forma -  Bolsillo)</td>
          <td id="fuente1"><input name="bol_lamina_2_ref" id="bol_lamina_2_ref" style="width:65px" min="0" type="number" size="5" value="<?php echo $row_ref['bol_lamina_2_ref'] ?>"/></td>
          <td colspan="3" id="fuente1">Precortes  (Forma -  Bolsillo)</td>
          <td id="fuente1"><select name="B_troquel" id="B_troquel" style="width:70px">
            <option value=""<?php if (!(strcmp("", $row_ref['B_troquel']))) {echo "selected=\"selected\"";} ?>>*</option>
            <option value="1"<?php if (!(strcmp("1", $row_ref['B_troquel']))) {echo "selected=\"selected\"";} ?>>SI</option>
            <option value="0"<?php if (!(strcmp("0",$row_ref['B_troquel']))) {echo "selected=\"selected\"";} ?>>NO</option>
          </select></td>
        </tr>
        <tr>
          <td id="detalle1">POSICION</td>
          <td id="detalle1">TIPO DE NUMERACION</td>
          <td id="detalle1">FORMATO &amp; CODIGO DE BARAS </td>
          <td id="fuente11">&nbsp;</td>
          <td colspan="3" id="fuente11">&nbsp;</td>
          <td id="fuente11">&nbsp;</td>
        </tr>
        <tr>
          <td id="detalle1">Solapa TR </td>
          <td id="detalle1"><select name="tipo_solapatr_egp" id="tipo_solapatr_egp">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Normal" <?php if (!(strcmp("Normal", $row_ref['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
            <option value="CCTV" <?php if (!(strcmp("CCTV", $row_ref['tipo_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
          </select></td>
          <td id="detalle1"><select name="cb_solapatr_egp" id="cb_solapatr_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ref['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_ref['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_ref['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_ref['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_ref['cb_solapatr_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>            
          </select></td>
          <td id="fuente10">&nbsp;</td>
          <td colspan="3" id="fuente10">&nbsp;</td>
          <td id="fuente10">&nbsp;</td>
        </tr>
        <tr>
          <td id="detalle1">Cinta</td>
          <td id="detalle1"><select name="tipo_cinta_egp" id="tipo_cinta_egp">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Normal" <?php if (!(strcmp("Normal", $row_ref['tipo_cinta_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          </select></td>
          <td id="detalle1"><select name="cb_cinta_egp" id="cb_cinta_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ref['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_ref['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_ref['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_ref['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_ref['cb_cinta_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option> 
          </select></td>
          <td id="fuente9">&nbsp;</td>
          <td colspan="3" id="fuente9">&nbsp;</td>
          <td id="fuente9">&nbsp;</td>
        </tr>
        <tr>
          <td id="detalle1">Superior</td>
          <td id="detalle1"><select name="tipo_superior_egp" id="tipo_superior_egp">
            <option value="N.A."<?php if (!(strcmp("N.A.", $row_ref['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Normal"<?php if (!(strcmp("Normal", $row_ref['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
            <option value="CCTV"<?php if (!(strcmp("CCTV", $row_ref['tipo_superior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
          </select></td>
          <td id="detalle1"><select name="cb_superior_egp" id="cb_superior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_ref['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ref['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_ref['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_ref['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_ref['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_ref['cb_superior_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
          </select></td>
          <td id="fuente8">&nbsp;</td>
          <td colspan="3" id="fuente8">&nbsp;</td>
          <td id="fuente8">&nbsp;</td>
        </tr>
        <tr>
          <td id="detalle1">Principal</td>
          <td id="detalle1"><select name="tipo_principal_egp" id="tipo_principal_egp">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Normal" <?php if (!(strcmp("Normal", $row_ref['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
            <option value="CCTV" <?php if (!(strcmp("CCTV", $row_ref['tipo_principal_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
          </select></td>
          <td id="detalle1"><select name="cb_principal_egp" id="cb_principal_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ref['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_ref['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_ref['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_ref['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_ref['cb_principal_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>             
          </select></td>
          <td id="fuente7">&nbsp;</td>
          <td colspan="3" id="fuente7">&nbsp;</td>
          <td id="fuente7">&nbsp;</td>
        </tr>
        <tr>
          <td id="detalle1">Inferior</td>
          <td id="detalle1"><select name="tipo_inferior_egp" id="tipo_inferior_egp">
            <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
            <option value="Normal" <?php if (!(strcmp("Normal", $row_ref['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
            <option value="CCTV" <?php if (!(strcmp("CCTV", $row_ref['tipo_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
          </select></td>
          <td id="detalle1"><select name="cb_inferior_egp" id="cb_inferior_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_ref['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_ref['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_ref['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_ref['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_ref['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_ref['cb_inferior_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>          
          </select></td>
          <td id="fuente5">&nbsp;</td>
          <td colspan="3" id="fuente5">&nbsp;</td>
          <td id="fuente5">&nbsp;</td>
        </tr>
<tr>
        <td id="detalle1">Liner</td>
        <td id="detalle1"><select name="tipo_liner_egp" id="tipo_liner_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_liner_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle1"><select name="cb_liner_egp" id="cb_liner_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_liner_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>         
          
        </select></td>
          <td id="fuente5">&nbsp;</td>
          <td colspan="3" id="fuente5">&nbsp;</td>
          <td id="fuente5">&nbsp;</td>        
      </tr>
      <tr>
        <td id="detalle1">Bolsillo</td>
        <td id="detalle1"><select name="tipo_bols_egp" id="tipo_bols_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_bols_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle1"><select name="cb_bols_egp" id="cb_bols_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_bols_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select></td>
          <td id="fuente5">&nbsp;</td>
          <td colspan="3" id="fuente5">&nbsp;</td>
          <td id="fuente5">&nbsp;</td>        
      </tr>
      <tr>
        <td id="detalle1">        
        <input type="text" list="misdatos" name="tipo_nom_egp" id="tipo_nom_egp" value="<?php echo $row_referencia_editar['tipo_nom_egp']; ?>" onBlur="primeraletra(this)">
        <datalist id="misdatos">
         <option  label="Solapa TR" value="Solapa TR">
         <option  label="Cinta" value="Cinta">
         <option  label="Superior" value="Superior">
         <option  label="Principal" value="Principal">
         <option  label="Inferior" value="Inferior">
         <option  label="Liner" value="Liner">
         <option  label="Bolsillo" value="Bolsillo">
        </datalist></td>
        <td id="detalle1"><select name="tipo_otro_egp" id="tipo_otro_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="Normal" <?php if (!(strcmp("Normal", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>Normal</option>
          <option value="CCTV" <?php if (!(strcmp("CCTV", $row_referencia_editar['tipo_otro_egp']))) {echo "selected=\"selected\"";} ?>>CCTV</option>
        </select></td>
        <td id="detalle1"><select name="cb_otro_egp" id="cb_otro_egp">
          <option value="N.A." <?php if (!(strcmp("N.A.", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>N.A.</option>
          <option value="EAN128" <?php if (!(strcmp("EAN128", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>EAN128</option>
          <option value="EAN13" <?php if (!(strcmp("EAN13", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>EAN13</option>
          <option value="CODE128" <?php if (!(strcmp("CODE128", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE128</option>
          <option value="CODE93" <?php if (!(strcmp("CODE93", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE93</option>
          <option value="CODE39" <?php if (!(strcmp("CODE39", $row_referencia_editar['cb_otro_egp']))) {echo "selected=\"selected\"";} ?>>CODE39</option>
        </select></td>
          <td id="fuente5">&nbsp;</td>
          <td colspan="3" id="fuente5">&nbsp;</td>
          <td id="fuente5">&nbsp;</td>        
      </tr>        
        <tr>
          <td colspan="8" id="fuente1">&nbsp;</td>
          </tr>
        <tr>
          <td colspan="8" id="fuente1"><strong>NOTA: </strong> La informacion que se modifique, tambien modificara la referencia</td>
          </tr>        
        <tr id="tr1">
        <td colspan="8" id="titulo4">OBSERVACION</td>
        </tr>
        <tr>
          <td colspan="8" id="fuente6"><textarea name="observacion5_egp" id="observacion5_egp" cols="80" rows="2"placeholder="OBSERVACIONES"onblur="conMayusculas(this)"><?php echo $row_ref['observacion5_egp']; ?></textarea></td>
        </tr>
        <tr>
          <td colspan="8" id="fuente2"><input type="submit" class="botonGeneral" name="Guardar" id="Guardar" value="Actualizar" /></td>
        </tr>
      </table></td>    
    </table>
    <input name="registro1_ref" type="hidden" value="<?php echo $row_ref['registro1_ref']; ?>" size="27" />
    <input name="fecha_registro1_ref" type="hidden" value="<?php echo $row_ref['fecha_registro1_ref']; ?>" size="10" />
    <input name="hora_modificacion" type="hidden" value="<?php echo date("g:i a"); ?>" />
    <input type="hidden" name="id_ref" id="id_ref" value="<?php echo $row_ref['id_ref']; ?>"/>
    <input type="hidden" name="cod_ref" id="cod_ref" value="<?php echo $row_ref['cod_ref']; ?>"/>
    <input type="hidden" name="MM_update" value="form1">
  </form>  
  <?php echo $conexion->header('footer'); ?>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($ref);

mysql_free_result($materia_prima);

?>
