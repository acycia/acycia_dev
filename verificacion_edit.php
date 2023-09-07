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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE verificacion SET id_ref_verif=%s, version_ref_verif=%s, fecha_verif=%s, responsable_verif=%s, ancho_verif=%s, observ_ancho_verif=%s, largo_verif=%s, observ_largo_verif=%s, solapa_verif=%s, observ_solapa_verif=%s, dist_logo_borde_verif=%s, observ_logo_borde_verif=%s, rev_textos_verif=%s, observ_rev_textos_verif=%s, rev_ortog_verif=%s, observ_rev_ortog_verif=%s, rev_portag_verif=%s, observ_portag_verif=%s, rev_extru_verif=%s, observ_extru_verif=%s, color_ext_verif=%s, observ_color_ext_verif=%s, color_int_verif=%s, observ_color_int_verif=%s, `1color_verif`=%s, observ_1color_verif=%s, `2color_verif`=%s, observ_2color_verif=%s, `3color_verif`=%s, observ_3color_verif=%s, `4color_verif`=%s, observ_4color_verif=%s, `5color_verif`=%s, observ_5color_verif=%s, `6color_verif`=%s, observ_6color_verif=%s, `7color_verif`=%s, observ_7color_verif=%s, `8color_verif`=%s, observ_8color_verif=%s, marca_foto_verif=%s, observ_marca_foto_verif=%s, alt_tal_rec_verif=%s, observ_alt_tal_rec_verif=%s, alt_cinta_seg_verif=%s, observ_alt_cinta_seg_verif=%s, alt_ppal_verif=%s, observ_alt_ppal_verif=%s, alt_inf_verif=%s, observ_alt_inf_verif=%s, otro_nom_verif=%s, alt_liner_verif=%s, observ_alt_liner_verif=%s, alt_bols_verif=%s, observ_alt_bols_verif=%s, alt_otro_verif=%s, observ_alt_otro_verif=%s, form_tal_rec_verif=%s, observ_form_tal_rec_verif=%s, form_cinta_seg_verif=%s, observ_form_cinta_seg_verif=%s, form_ppal_verif=%s, observ_form_ppal_verif=%s, form_inf_verif=%s, observ_form_inf_verif=%s, form_liner_verif=%s, observ_form_liner_verif=%s, form_bols_verif=%s, observ_form_bols_verif=%s, form_otro_verif=%s, observ_form_otro_verif=%s, observacion_verif=%s, userfile=%s, estado_arte_verif=%s, fecha_aprob_arte_verif=%s, fecha_edit_verif=%s, responsable_edit_verif=%s WHERE id_verif=%s",
                       GetSQLValueString($_POST['id_ref_verif'], "int"),
                       GetSQLValueString($_POST['version_ref_verif'], "text"),
                       GetSQLValueString($_POST['fecha_verif'], "date"),
                       GetSQLValueString($_POST['responsable_verif'], "text"),
                       GetSQLValueString(isset($_POST['ancho_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_ancho_verif'], "text"),
                       GetSQLValueString(isset($_POST['largo_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_largo_verif'], "text"),
                       GetSQLValueString(isset($_POST['solapa_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_solapa_verif'], "text"),
                       GetSQLValueString(isset($_POST['dist_logo_borde_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_logo_borde_verif'], "text"),
                       GetSQLValueString(isset($_POST['rev_textos_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rev_textos_verif'], "text"),
                       GetSQLValueString(isset($_POST['rev_ortog_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rev_ortog_verif'], "text"),
                       GetSQLValueString(isset($_POST['rev_portag_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_portag_verif'], "text"),
                       GetSQLValueString(isset($_POST['rev_extru_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_extru_verif'], "text"),
                       GetSQLValueString(isset($_POST['color_ext_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color_ext_verif'], "text"),
                       GetSQLValueString(isset($_POST['color_int_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color_int_verif'], "text"),
                       GetSQLValueString(isset($_POST['1color_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_1color_verif'], "text"),
                       GetSQLValueString(isset($_POST['2color_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_2color_verif'], "text"),
                       GetSQLValueString(isset($_POST['3color_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_3color_verif'], "text"),
                       GetSQLValueString(isset($_POST['4color_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_4color_verif'], "text"),
                       GetSQLValueString(isset($_POST['5color_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_5color_verif'], "text"),
                       GetSQLValueString(isset($_POST['6color_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_6color_verif'], "text"),
                       GetSQLValueString(isset($_POST['7color_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_7color_verif'], "text"),
                       GetSQLValueString(isset($_POST['8color_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_8color_verif'], "text"),
                       GetSQLValueString(isset($_POST['marca_foto_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_marca_foto_verif'], "text"),
                       GetSQLValueString(isset($_POST['alt_tal_rec_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_alt_tal_rec_verif'], "text"),
                       GetSQLValueString(isset($_POST['alt_cinta_seg_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_alt_cinta_seg_verif'], "text"),
                       GetSQLValueString(isset($_POST['alt_ppal_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_alt_ppal_verif'], "text"),
                       GetSQLValueString(isset($_POST['alt_inf_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_alt_inf_verif'], "text"),
                       GetSQLValueString($_POST['otro_nom_verif'], "text"),
                       GetSQLValueString(isset($_POST['alt_liner_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_alt_liner_verif'], "text"),
                       GetSQLValueString(isset($_POST['alt_bols_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_alt_bols_verif'], "text"),
                       GetSQLValueString(isset($_POST['alt_otro_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_alt_otro_verif'], "text"),
                       GetSQLValueString(isset($_POST['form_tal_rec_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_form_tal_rec_verif'], "text"),
                       GetSQLValueString(isset($_POST['form_cinta_seg_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_form_cinta_seg_verif'], "text"),
                       GetSQLValueString(isset($_POST['form_ppal_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_form_ppal_verif'], "text"),
                       GetSQLValueString(isset($_POST['form_inf_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_form_inf_verif'], "text"),
                       GetSQLValueString(isset($_POST['form_liner_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_form_liner_verif'], "text"),
                       GetSQLValueString(isset($_POST['form_bols_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_form_bols_verif'], "text"),
                       GetSQLValueString(isset($_POST['form_otro_verif']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_form_otro_verif'], "text"),
                       GetSQLValueString($_POST['observacion_verif'], "text"),
                       GetSQLValueString($_POST['userfile'], "text"),
                       GetSQLValueString($_POST['estado_arte_verif'], "int"),
                       GetSQLValueString($_POST['fecha_aprob_arte_verif'], "date"),
                       GetSQLValueString($_POST['fecha_edit_verif'], "date"),
                       GetSQLValueString($_POST['responsable_edit_verif'], "text"),
                       GetSQLValueString($_POST['id_verif'], "int"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($updateSQL, $conexion1) or die(mysql_error());

//UPDATE  POR SI SE LES OLVIDA ACTUALIZAR LA VERSION MANUALMENTE
   $version_ref_verif=$_POST['version_ref_verif'];
   $id_ref=$_POST['id_ref_verif'];
   $sql1="UPDATE Tbl_referencia SET version_ref='$version_ref_verif' WHERE id_ref='$id_ref'";
   $Result2 = mysql_query($sql1, $conexion1) or die(mysql_error());
	 
  $updateGoTo = "verificacion_vista.php?id_verif=" . $_POST['id_verif'] . "";
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

$colname_verificacion_edit = "-1";
if (isset($_GET['id_verif'])) {
  $colname_verificacion_edit = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion_edit = sprintf("SELECT * FROM verificacion WHERE id_verif = %s", $colname_verificacion_edit);
$verificacion_edit = mysql_query($query_verificacion_edit, $conexion1) or die(mysql_error());
$row_verificacion_edit = mysql_fetch_assoc($verificacion_edit);
$totalRows_verificacion_edit = mysql_num_rows($verificacion_edit);

$colname_referencia_revision = "-1";
if (isset($_GET['id_verif'])) {
  $colname_referencia_revision = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia_revision = sprintf("SELECT * FROM verificacion, revision, Tbl_referencia WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif = revision.id_ref_rev AND revision.id_ref_rev=Tbl_referencia.id_ref", $colname_referencia_revision);
$referencia_revision = mysql_query($query_referencia_revision, $conexion1) or die(mysql_error());
$row_referencia_revision = mysql_fetch_assoc($referencia_revision);
$totalRows_referencia_revision = mysql_num_rows($referencia_revision);

$colname_ref_egp = "-1";
if (isset($_GET['id_verif'])) {
  $colname_ref_egp = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ref_egp = sprintf("SELECT * FROM verificacion, Tbl_referencia, Tbl_egp WHERE verificacion.id_verif = %s AND  verificacion.id_ref_verif = Tbl_referencia.id_ref AND Tbl_referencia.n_egp_ref=Tbl_egp.n_egp", $colname_ref_egp);
$ref_egp = mysql_query($query_ref_egp, $conexion1) or die(mysql_error());
$row_ref_egp = mysql_fetch_assoc($ref_egp);
$totalRows_ref_egp = mysql_num_rows($ref_egp);

$colname_modificacion = "-1";
if (isset($_GET['id_verif'])) {
  $colname_modificacion = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_modificacion = sprintf("SELECT * FROM control_modificaciones WHERE id_verif_cm = %s", $colname_modificacion);
$modificacion = mysql_query($query_modificacion, $conexion1) or die(mysql_error());
$row_modificacion = mysql_fetch_assoc($modificacion);
$totalRows_modificacion = mysql_num_rows($modificacion);

$colname_validacion = "-1";
if (isset($_GET['id_verif'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM verificacion, validacion WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif = validacion.id_ref_val", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_verif'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM verificacion, TblFichaTecnica WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif = TblFichaTecnica.id_ref_ft", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_verif'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_verif'] : addslashes($_GET['id_verif']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM verificacion,TblCertificacion WHERE verificacion.id_verif = %s AND verificacion.id_ref_verif = TblCertificacion.idref",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref);    

?>
<html>
<head>
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
<script type="text/javascript" src="js/consulta.js"></script>

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
<div align="center">
<table id="tabla"><tr align="center"><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1">
<tr>
<td colspan="2" align="center"><img src="images/cabecera.jpg"></td></tr>
<tr>
<td id="nombreusuario"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
<li><a href="<?php echo $logoutAction ?>">CERRAR SESION</a></li>
<li><a href="menu.php">MENU PRINCIPAL</a></li>
<li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>
</ul>
</td></tr>
<tr><td colspan="2" align="center">
      <form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
        <table id="tabla2">
        <tr id="tr1">
          <td id="codigo" width="25%">CODIGO: R2-F01</td>
          <td colspan="2" nowrap="nowrap" id="titulo2">PLAN DE DISE&Ntilde;O &amp; DESARROLLO</td>
          <td id="codigo" width="25%">VERSION: 4</td>
        </tr>
          <tr>
            <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
            <td colspan="2" id="subtitulo">EDITAR - II. VERIFICACION 
              
              <?php echo $row_verificacion_edit['id_verif']; ?></td>
            <td id="dato2"><a href="verificacion_vista.php?id_verif=<?php echo $row_verificacion_edit['id_verif']; ?>"><img src="images/hoja.gif" alt="VISTA IMPRESION" title="VISTA IMPRESION" border="0" style="cursor:hand;" /></a>
              <a href="javascript:eliminar1('id_verif',<?php echo $row_verificacion_edit['id_verif']; ?>,'verificacion_edit.php')"><img src="images/por.gif" alt="ELIMINAR" title="ELIMINAR" border="0" style="cursor:hand;" ></a>
              <a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a>
              <a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision_vista.php?id_rev=<?php echo $row_referencia_revision['id_rev']; ?>"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion_referencia.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACION X REF" title="VERIFICACION X REF" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/identico.gif" alt="LISTADO DE VERIFICACIONES" title="LISTADO DE VERIFICACIONES" border="0" style="cursor:hand;" /></a><?php if($row_validacion['id_val'] == '') { ?> <a href="validacion_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/v.gif" alt="ADD VALIDACION" title="ADD VALIDACION" border="0" style="cursor:hand;" /></a><?php } else{ ?> <a href="validacion_vista.php?id_val=<?php echo $row_validacion['id_val']; ?>"><img src="images/v.gif" alt="VALIDACION" title="VALIDACION" border="0" style="cursor:hand;" /></a><?php } ?><?php if($row_ficha_tecnica['n_ft'] == '') { ?>
      <a href="ficha_tecnica_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>"><img src="images/f.gif" alt="ADD FICHA TECNICA" title="ADD FICHA TECNICA" border="0" style="cursor:hand;" /></a><?php } else{ ?>
      <a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>      <?php } ?>
              <?php if($row_certificacion['idcc']=='') { ?>
        <a href="certificacion_add.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $row_referencia_revision['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
        </a><?php } ?></td>
          </tr>
          <tr id="tr1">
            <td id="fuente2">FECHA DE REGISTRO </td>
            <td colspan="2" id="fuente2">RESPONSABLE</td>
            </tr>
          <tr>
            <td id="dato2"><input type="text" name="fecha_verif" value="<?php echo $row_verificacion_edit['fecha_verif']; ?>" size="10" /></td>
            <td colspan="2" id="dato2"><input type="text" name="responsable_verif" value="<?php echo $row_verificacion_edit['responsable_verif']; ?>" size="30" /></td>
            </tr>
          <tr id="tr1">
            <td id="fuente2">REFERENCIA</td>
            <td id="fuente2">MODIFICACION</td>
            <td id="fuente2">VERSION MODIF.</td>
          </tr>
          <tr>
            <td id="dato2"><input name="id_ref_verif" type="hidden" value="<?php echo $row_verificacion_edit['id_ref_verif']; ?>" /><strong><?php echo $row_referencia_revision['cod_ref']; ?> - <?php echo $row_referencia_revision['version_ref']; ?></strong></td>
            <td id="dato2"><?php if($row_modificacion['id_cm'] != '') { ?> <a href="control_modif_edit.php?id_cm=<?php echo $row_modificacion['id_cm']; ?>"><img src="images/m.gif" alt="EDIT MODIFICACION" border="0" style="cursor:hand;" /></a> <?php } else { echo "- -"; } ?></td>
            <td id="dato2"><input name="version_ref_verif" type="text" value="<?php echo $row_verificacion_edit['version_ref_verif']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td><?php if($row_referencia_revision['estado_ref'] == '1') { ?> <div id="acceso2"><?php echo "Activa"; ?></div> <?php } else { ?> <div id="numero2"> <?php echo "Inactiva"; ?> </div> <?php } ?></td>
            <td id="dato2"><!--EGP N&ordm; <?php //echo $row_referencia_revision['n_egp_ref']; ?>--></td>
            <td id="dato2">COTIZACION N&ordm; <?php echo $row_referencia_revision['n_cotiz_ref']; ?></td>
          </tr>
          <tr id="tr1">
          <td colspan="4" id="titulo4">LISTADO DE VERIFICACION DE PARAMETROS GENERALES (Cumple Si / No)</td></tr>
          <tr>
            <td colspan="4" align="center"><table id="tabla1">
              <tr id="tr1">
                <td id="fuente2">DATO</td>
                <td id="fuente2">CUMPLE</td>
                <td id="fuente2">OBSERVACION</td>
              </tr>
              <tr>
                <td id="detalle1">Ancho: <?php echo $row_referencia_revision['ancho_ref']; ?></td>
                <td id="detalle1"><input name="ancho_verif" type="checkbox" id="ancho_verif" value="1" <?php if (!(strcmp($row_verificacion_edit['ancho_verif'],1))) {echo "checked=\"checked\"";} ?> />
                  Ancho</td>
                <td id="detalle2"><input name="observ_ancho_verif" type="text" id="observ_ancho_verif" value="<?php echo $row_verificacion_edit['observ_ancho_verif'];?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Largo: <?php echo $row_referencia_revision['largo_ref']; ?></td>
                <td id="detalle1"><input name="largo_verif" type="checkbox" id="largo_verif" value="1" <?php if (!(strcmp($row_verificacion_edit['largo_verif'],1))) {echo "checked=\"checked\"";} ?> />
                  Largo</td>
                <td id="detalle2"><input name="observ_largo_verif" type="text" id="observ_largo_verif" value="<?php echo $row_verificacion_edit['observ_largo_verif'];?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Solapa: <?php echo $row_referencia_revision['solapa_ref']; ?></td>
                <td id="detalle1"><input name="solapa_verif" type="checkbox" id="solapa_verif" value="1" <?php if (!(strcmp($row_verificacion_edit['solapa_verif'],1))) {echo "checked=\"checked\"";} ?> />
                  Solapa</td>
                <td id="detalle2"><input name="observ_solapa_verif" type="text" id="observ_solapa_verif" value="<?php echo $row_verificacion_edit['observ_solapa_verif'];?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Distribuci&oacute;n entre Logos y Bordes </td>
                <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_verificacion_edit['dist_logo_borde_verif'],1))) {echo "checked=\"checked\"";} ?> name="dist_logo_borde_verif" type="checkbox" id="dist_logo_borde_verif" value="1" />
Distribuci&oacute;n entre Logos y Bordes</td>
                <td id="detalle2"><input name="observ_logo_borde_verif" type="text" id="observ_logo_borde_verif" value="<?php echo $row_verificacion_edit['observ_logo_borde_verif'];?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Revisi&oacute;n Textos</td>
                <td nowrap="nowrap" id="detalle1"><input <?php if (!(strcmp($row_verificacion_edit['rev_textos_verif'],1))) {echo "checked=\"checked\"";} ?> name="rev_textos_verif" type="checkbox" id="rev_textos_verif" value="1" />
Revisi&oacute;n Textos </td>
                <td id="detalle1"><input name="observ_rev_textos_verif" type="text" id="observ_rev_textos_verif" value="<?php echo $row_verificacion_edit['observ_rev_textos_verif'];?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Revisi&oacute;n Ortografica</td>
                <td id="detalle1"><input <?php if (!(strcmp($row_verificacion_edit['rev_ortog_verif'],1))) {echo "checked=\"checked\"";} ?> name="rev_ortog_verif" type="checkbox" id="rev_ortog_verif" value="1" />
                  Revisi&oacute;n Ortografica</td>
                <td id="detalle1"><input name="observ_rev_ortog_verif" type="text" id="observ_rev_ortog_verif" value="<?php echo $row_verificacion_edit['observ_rev_ortog_verif']; ;?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Bolsillo Portagu&iacute;a: <?php echo $row_referencia_revision['bolsillo_guia_ref']; ?></td>
                <td id="detalle1"><input <?php if (!(strcmp($row_verificacion_edit['rev_portag_verif'],1))) {echo "checked=\"checked\"";} ?> name="rev_portag_verif" type="checkbox" id="rev_portag_verif" value="1" />Bolsillo Portaguia</td>
                <td id="detalle1"><input type="text" name="observ_portag_verif" value="<?php echo $row_verificacion_edit['observ_portag_verif']; ;?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Tipo Extrusi&oacute;n: <?php echo $row_ref_egp['tipo_ext_egp']; ?></td>
                <td id="detalle1"><input <?php if (!(strcmp($row_verificacion_edit['rev_extru_verif'],1))) {echo "checked=\"checked\"";} ?> name="rev_extru_verif" type="checkbox" id="rev_extru_verif" value="1" />
Tipo Extrusi&oacute;n</td>
                <td id="detalle1"><input type="text" name="observ_extru_verif" value="<?php echo $row_verificacion_edit['observ_extru_verif']; ;?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Pigmento Exterior: <?php echo $row_ref_egp['pigm_ext_egp']; ?></td>
                <td id="detalle1"><input <?php if (!(strcmp($row_verificacion_edit['color_ext_verif'],1))) {echo "checked=\"checked\"";} ?> name="color_ext_verif" type="checkbox" id="color_ext_verif" value="1" />
                  Color Extrusi&oacute;n Exterior </td>
                <td id="detalle1"><input name="observ_color_ext_verif" type="text" id="observ_color_ext_verif" value="<?php echo $row_verificacion_edit['observ_color_ext_verif'];?>" size="50" /></td>
              </tr>
              <tr>
                <td id="detalle1">Pigmento Interior: <?php echo $row_ref_egp['pigm_int_epg']; ?></td>
                <td id="detalle1"><input <?php if (!(strcmp($row_verificacion_edit['color_int_verif'],1))) {echo "checked=\"checked\"";} ?> name="color_int_verif" type="checkbox" id="color_int_verif" value="1" />
                  Color Extrusi&oacute;n Interior </td>
                <td id="detalle2"><input name="observ_color_int_verif" type="text" id="observ_color_int_verif" value="<?php echo $row_verificacion_edit['observ_color_int_verif']; ;?>" size="50" /></td>
              </tr>
            </table></td>
            </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo4">VERIFICACION DE COLORES DE IMPRESION (Cumple Si / No)</td>
          </tr>
          <tr>
            <td colspan="4" align="center"><table id="tabla1">
            <tr id="tr1">
              <td id="fuente2">COLOR</td>
              <td id="fuente2">PANTONE</td>
              <td id="fuente2">CUMPLE</td>
              <td id="fuente2">OBSERVACION</td>
            </tr>
            <tr>
              <td nowrap="nowrap" id="detalle1"><strong>1</strong> : <?php echo $row_ref_egp['color1_egp']; ?></td>
              <td nowrap="nowrap" id="detalle1">- <?php echo $row_ref_egp['pantone1_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['1color_verif'],1))) {echo "checked=\"checked\"";} ?> name="1color_verif" type="checkbox" id="1color_verif" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_1color_verif" value="<?php echo $row_verificacion_edit['observ_1color_verif'];; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>2</strong> : <?php echo $row_ref_egp['color2_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone2_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['2color_verif'],1))) {echo "checked=\"checked\"";} ?> name="2color_verif" type="checkbox" id="2color_verif" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_2color_verif" value="<?php echo $row_verificacion_edit['observ_2color_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>3</strong> : <?php echo $row_ref_egp['color3_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone3_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['3color_verif'],1))) {echo "checked=\"checked\"";} ?> name="3color_verif" type="checkbox" id="3color_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_3color_verif" value="<?php echo $row_verificacion_edit['observ_3color_verif']; ;?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>4</strong> : <?php echo $row_ref_egp['color4_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone4_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['4color_verif'],1))) {echo "checked=\"checked\"";} ?> name="4color_verif" type="checkbox" id="4color_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_4color_verif" value="<?php echo $row_verificacion_edit['observ_4color_verif'];;  ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>5</strong> : <?php echo $row_ref_egp['color5_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone5_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['5color_verif'],1))) {echo "checked=\"checked\"";} ?> name="5color_verif" type="checkbox" id="5color_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_5color_verif" value="<?php echo $row_verificacion_edit['observ_5color_verif'];; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>6</strong> : <?php echo $row_ref_egp['color6_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone6_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['6color_verif'],1))) {echo "checked=\"checked\"";} ?> name="6color_verif" type="checkbox" id="6color_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_6color_verif" value="<?php echo $row_verificacion_edit['observ_6color_verif']; ; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>7</strong> : <?php echo $row_ref_egp['color7_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone7_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['7color_verif'],1))) {echo "checked=\"checked\"";} ?> name="7color_verif" type="checkbox" id="7color_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_7color_verif" value="<?php echo $row_verificacion_edit['observ_7color_verif']; ; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><strong>8</strong> : <?php echo $row_ref_egp['color8_egp']; ?></td>
              <td id="detalle1">- <?php echo $row_ref_egp['pantone8_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['8color_verif'],1))) {echo "checked=\"checked\"";} ?> name="8color_verif" type="checkbox" id="8color_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_8color_verif" value="<?php echo $row_verificacion_edit['observ_8color_verif']; ; ?>" size="60" /></td>
            </tr>                        
            <tr>
              <td colspan="2" id="detalle1">MARCA DE FOTOCELDA </td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['marca_foto_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="marca_foto_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_marca_foto_verif" value="<?php echo $row_verificacion_edit['observ_marca_foto_verif'];?>" size="60" /></td>
            </tr>
          </table></td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo4">VERIFICACION DE NUMERACION (Cumple Si / No)</td>
          </tr>
          <tr>
            <td colspan="4" align="center"><table id="tabla1">
            <tr id="tr1">
              <td id="fuente2">POSICIONES</td>
              <td id="fuente2">CUMPLE</td>
              <td id="fuente2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="detalle1">Talonario Recibo: <?php echo $row_ref_egp['tipo_solapatr_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['alt_tal_rec_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="alt_tal_rec_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_alt_tal_rec_verif" value="<?php echo $row_verificacion_edit['observ_alt_tal_rec_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Cinta de Seguridad: <?php echo $row_ref_egp['tipo_cinta_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['alt_cinta_seg_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="alt_cinta_seg_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_alt_cinta_seg_verif" value="<?php echo $row_verificacion_edit['observ_alt_cinta_seg_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Principal: <?php echo $row_ref_egp['tipo_principal_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['alt_ppal_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="alt_ppal_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_alt_ppal_verif" value="<?php echo $row_verificacion_edit['observ_alt_ppal_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Inferior: <?php echo $row_ref_egp['tipo_inferior_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['alt_inf_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="alt_inf_verif" value="1"></td>
              <td id="detalle2"><input type="text" name="observ_alt_inf_verif" value="<?php echo $row_verificacion_edit['observ_alt_inf_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Liner:<?php echo $row_ref_egp['tipo_liner_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['alt_liner_verif'],1))) {echo "checked=\"checked\"";} ?> name="alt_liner_verif" type="checkbox" id="alt_liner_verif" value="1"></td>
              <td id="detalle2"><input name="observ_alt_liner_verif" type="text" id="observ_alt_liner_verif" value="<?php echo $row_verificacion_edit['observ_alt_liner_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Bolsillo:<?php echo $row_ref_egp['tipo_bols_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['alt_bols_verif'],1))) {echo "checked=\"checked\"";} ?> name="alt_bols_verif" type="checkbox" id="alt_bols_verif" value="1"></td>
              <td id="detalle2"><input name="observ_alt_bols_verif" type="text" id="observ_alt_bols_verif" value="<?php echo $row_verificacion_edit['observ_alt_inf_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><?php echo $row_ref_egp['tipo_nom_egp']; ?>
                <label for="alt_nom_verif"></label>
                <input type="hidden" name="otro_nom_verif" id="otro_nom_verif" value="<?php echo $row_ref_egp['tipo_nom_egp']; ?>">
                :<?php echo $row_ref_egp['tipo_otro_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['alt_otro_verif'],1))) {echo "checked=\"checked\"";} ?> name="alt_otro_verif" type="checkbox" id="alt_otro_verif" value="1"></td>
              <td id="detalle2"><input name="observ_alt_otro_verif" type="text" id="observ_alt_otro_verif" value="<?php echo $row_verificacion_edit['observ_alt_inf_verif']; ?>" size="60" /></td>
            </tr>          
          </table></td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo4">VERIFICACION DE CODIGO DE BARRAS (Cumple Si / No)</td>
            </tr>
          <tr>
            <td colspan="4" align="center"><table id="tabla1">
            <tr id="tr1">
              <td id="fuente2">POSICIONES</td>
              <td id="fuente2">CUMPLE</td>
              <td id="fuente2">OBSERVACION</td>
            </tr>
            <tr>
              <td id="detalle1">Talonario Recibo:
                <?php echo $row_ref_egp['cb_solapatr_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['form_tal_rec_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_tal_rec_verif" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_form_tal_rec_verif" value="<?php echo $row_verificacion_edit['observ_form_tal_rec_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Cinta de Seguridad:
                <?php echo $row_ref_egp['cb_cinta_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['form_cinta_seg_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_cinta_seg_verif" value="1" /></td>
              <td id="detalle2"><input  type="text" name="observ_form_cinta_seg_verif" value="<?php echo $row_verificacion_edit['observ_form_cinta_seg_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Principal:
                <?php echo $row_ref_egp['cb_principal_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['form_ppal_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_ppal_verif" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_form_ppal_verif" value="<?php echo $row_verificacion_edit['observ_form_ppal_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Inferior:
                <?php echo $row_ref_egp['cb_inferior_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['form_inf_verif'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="form_inf_verif" value="1" /></td>
              <td id="detalle2"><input type="text" name="observ_form_inf_verif" value="<?php echo $row_verificacion_edit['observ_form_inf_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Liner:<?php echo $row_ref_egp['cb_liner_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['form_liner_verif'],1))) {echo "checked=\"checked\"";} ?> name="form_liner_verif" type="checkbox" id="form_liner_verif" value="1"></td>
              <td id="detalle2"><input name="observ_form_liner_verif" type="text" id="observ_form_liner_verif" value="<?php echo $row_verificacion_edit['observ_form_liner_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1">Bolsillo:<?php echo $row_ref_egp['cb_bols_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['form_bols_verif'],1))) {echo "checked=\"checked\"";} ?> name="form_bols_verif" type="checkbox" id="form_bols_verif" value="1"></td>
              <td id="detalle2"><input name="observ_form_bols_verif" type="text" id="observ_form_bols_verif" value="<?php echo $row_verificacion_edit['observ_form_bols_verif']; ?>" size="60" /></td>
            </tr>
            <tr>
              <td id="detalle1"><?php echo $row_ref_egp['tipo_nom_egp']; ?>:<?php echo $row_ref_egp['cb_otro_egp']; ?></td>
              <td id="detalle2"><input <?php if (!(strcmp($row_verificacion_edit['form_otro_verif'],1))) {echo "checked=\"checked\"";} ?> name="form_otro_verif" type="checkbox" id="form_otro_verif" value="1"></td>
              <td id="detalle2"><input name="observ_form_otro_verif" type="text" id="observ_form_otro_verif" value="<?php echo $row_verificacion_edit['observ_form_otro_verif']; ?>" size="60" /></td>
            </tr>            
          </table></td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr id="tr2">
            <td colspan="4" id="titulo3"><a href="javascript:" onClick="window.open('verificacion_cireles.php?id_verif=<?php echo $row_verificacion_edit['id_verif']; ?>','','height=500,width=600,scrollbars=1')" target="_top" style="text-decoration: inherit; color:#F00">VERIFICACION DE CIRELES</a></td>
          </tr>
          <tr>
            <td colspan="4" align="center">&nbsp;</td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo4">OBSERVACIONES GENERALES</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><textarea name="observacion_verif" cols="80" rows="2"><?php echo $row_verificacion_edit['observacion_verif']; ?></textarea></td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo4">ARTE</td>
          </tr>
          <tr id="tr1">
            <td id="fuente2">ADD o Cambiar Arte </td>
            <td id="fuente2">Nombre del Arte </td>
            <td id="fuente2">Estado del Arte </td>
            <td id="fuente2"><p>Fecha Aprobaci&oacute;n</p>              </td>
          </tr>
          <tr>
            <td id="dato2"><a href="adjuntar1.php?id_verif=<?php echo $row_verificacion_edit['id_verif']; ?>&amp;id_ref=<?php echo $row_verificacion_edit['id_ref_verif']; ?>"><img src="images/clip.gif" alt="ADJUNTAR" border="0" style="cursor:hand;" /></a></td>
            <td id="dato2"><?php $muestra=$row_verificacion_edit['userfile']; ?>
              <a href="javascript:verFoto('archivo/<?php echo $muestra;?>','610','490')"> <?php echo $muestra;?>
              <input name="userfile" type="hidden" id="userfile" value="<?php echo $row_referencia_revision['userfile']; ?>" />
              </a></td>
            <td id="dato2"><?php $ref=$row_verificacion_edit['id_ref_verif'];
			$sql5="SELECT * FROM verificacion WHERE id_ref_verif='$ref' ";
			$result5= mysql_query($sql5);
			$row_ref = mysql_fetch_assoc($result5);
			$num5= mysql_num_rows($result5);
			if($num5 >='1')
			{ 
			$estado_arte = mysql_result($result5, 0, 'estado_arte_verif'); 
			
			?><select name="estado_arte_verif" id="estado_arte_verif">
                <option value="0"<?php if (!(strcmp(0, $row_verificacion_edit['estado_arte_verif']))) {echo "selected=\"selected\"";} ?>>Pendiente</option>
                <option value="1"<?php if (!(strcmp(1, $row_verificacion_edit['estado_arte_verif']))) {echo "selected=\"selected\"";} ?>>Rechazado</option>
                <option value="2"<?php if (!(strcmp(2, $row_verificacion_edit['estado_arte_verif']))) {echo "selected=\"selected\"";} ?>>Aceptado</option>
                <option value="3"<?php if (!(strcmp(3, $row_verificacion_edit['estado_arte_verif']))) {echo "selected=\"selected\"";} ?>>Anulado</option>
              </select><?php } else { echo $estado_arte;}?></td>
            <td id="dato2"><input name="fecha_aprob_arte_verif" type="date" value="<?php echo $row_verificacion_edit['fecha_aprob_arte_verif']; ?>" size="10" /></td>
          </tr>
          <tr id="tr1">
            <td colspan="4" id="titulo4">ULTIMA ACTUALIZACION</td>
            </tr>
          <tr>
            <td colspan="2" id="dato2">- 
              <input name="fecha_edit_verif" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
              <?php echo $row_verificacion_edit['fecha_edit_verif']; ?> - </td>
            <td colspan="2" id="dato2">-  
              <input name="responsable_edit_verif" type="hidden" value="<?php echo $row_usuario['nombre_usuario']; ?>" />
              <?php echo $row_verificacion_edit['responsable_edit_verif']; ?> - </td>
            </tr>
          <tr>
            <td colspan="4" id="dato2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4" id="dato2"><input type="submit" value="Actualizar Verificacion"></td>
            </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1">
        <input type="hidden" name="id_verif" value="<?php echo $row_verificacion_edit['id_verif']; ?>">
      </form></td></tr>
</table>
</div>
<b class="spiffy"> 
<b class="spiffy5"></b>
<b class="spiffy4"></b>
<b class="spiffy3"></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy1"><b></b></b></b></div> 
 </td>
  </tr>  
</table>
</div>
</body>
</body>
</html>
<?php
mysql_free_result($usuario);

mysql_free_result($verificacion_edit);

mysql_free_result($referencia_revision);

mysql_free_result($ref_egp);

mysql_free_result($modificacion);

mysql_free_result($validacion);

mysql_free_result($ficha_tecnica);
?>
