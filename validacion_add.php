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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO validacion (id_val, id_rev_val, id_ref_val, version_val, n_op_val, fecha_val, responsable_val, ancho_val, observ_ancho_val, altura_val, observ_altura_val, solapa_val, observ_solapa_val, calibre_val, observ_calibre_val, dist_logo_borde_val, observ_dist_logo_borde_val, rev_texto_val, observ_rev_texto_val, rev_ortog_val, observ_rev_ortog_val, rev_portag_val, observ_portag_val, rev_extru_val, observ_extru_val, color_ext_val, observ_color_ext_val, color_int_val, observ_color_int_val, color1_val, observ_color1_val, color2_val, observ_color2_val, color3_val, observ_color3_val, color4_val, observ_color4_val, color5_val, observacion_color5_val, color6_val, observacion_color6_val, color7_val, observacion_color7_val, color8_val, observacion_color8_val, marca_foto_val, observ_marca_foto_val, num_tal_rec_val, observ_num_tal_rec_val, num_cinta_seg_val, observ_num_cinta_seg_val, num_ppal_val, observ_num_ppal_val, num_inf_val, observ_num_inf_val, otro_nom_val, num_liner_val, observ_num_liner_val, num_bols_val, observ_num_bols_val, num_otro_val, observ_num_otro_val, num_fecha_cad_val, observ_num_fecha_val, cod_tal_rec_val, observ_cod_tal_rec_val, cod_cinta_seg_val, observ_cod_cinta_seg_val, cod_ppal_val, observ_cod_ppal_val, cod_inf_val, observ_cod_inf_val, cod_liner_val, observ_cod_liner_val, cod_bols_val, observ_cod_bols_val, cod_otro_val, observ_cod_otro_val, otras_observ_val) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['id_val'], "int"),
                       GetSQLValueString($_POST['id_rev_val'], "int"),
                       GetSQLValueString($_POST['id_ref_val'], "int"),
                       GetSQLValueString($_POST['version_val'], "text"),
                       GetSQLValueString($_POST['n_op_val'], "int"),
                       GetSQLValueString($_POST['fecha_val'], "date"),
                       GetSQLValueString($_POST['responsable_val'], "text"),
                       GetSQLValueString(isset($_POST['ancho_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_ancho_val'], "text"),
                       GetSQLValueString(isset($_POST['altura_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_altura_val'], "text"),
                       GetSQLValueString(isset($_POST['solapa_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_solapa_val'], "text"),
                       GetSQLValueString(isset($_POST['calibre_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_calibre_val'], "text"),
                       GetSQLValueString(isset($_POST['dist_logo_borde_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_dist_logo_borde_val'], "text"),
                       GetSQLValueString(isset($_POST['rev_texto_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rev_texto_val'], "text"),
                       GetSQLValueString(isset($_POST['rev_ortog_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_rev_ortog_val'], "text"),
                       GetSQLValueString(isset($_POST['rev_portag_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_portag_val'], "text"),
                       GetSQLValueString(isset($_POST['rev_extru_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_extru_val'], "text"),
                       GetSQLValueString(isset($_POST['color_ext_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color_ext_val'], "text"),
                       GetSQLValueString(isset($_POST['color_int_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color_int_val'], "text"),
                       GetSQLValueString(isset($_POST['color1_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color1_val'], "text"),
                       GetSQLValueString(isset($_POST['color2_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color2_val'], "text"),
                       GetSQLValueString(isset($_POST['color3_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color3_val'], "text"),
                       GetSQLValueString(isset($_POST['color4_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_color4_val'], "text"),
                       GetSQLValueString(isset($_POST['color5_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observacion_color5_val'], "text"),
                       GetSQLValueString(isset($_POST['color6_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observacion_color6_val'], "text"),
                       GetSQLValueString(isset($_POST['color7_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observacion_color7_val'], "text"),
                       GetSQLValueString(isset($_POST['color8_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observacion_color8_val'], "text"),
                       GetSQLValueString(isset($_POST['marca_foto_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_marca_foto_val'], "text"),
                       GetSQLValueString(isset($_POST['num_tal_rec_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_num_tal_rec_val'], "text"),
                       GetSQLValueString(isset($_POST['num_cinta_seg_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_num_cinta_seg_val'], "text"),
                       GetSQLValueString(isset($_POST['num_ppal_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_num_ppal_val'], "text"),
                       GetSQLValueString(isset($_POST['num_inf_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_num_inf_val'], "text"),
                       GetSQLValueString($_POST['otro_nom_val'], "text"),
                       GetSQLValueString(isset($_POST['num_liner_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_num_liner_val'], "text"),
                       GetSQLValueString(isset($_POST['num_bols_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_num_bols_val'], "text"),
                       GetSQLValueString(isset($_POST['num_otro_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_num_otro_val'], "text"),
                       GetSQLValueString(isset($_POST['num_fecha_cad_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_num_fecha_val'], "text"),
                       GetSQLValueString(isset($_POST['cod_tal_rec_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_cod_tal_rec_val'], "text"),
                       GetSQLValueString(isset($_POST['cod_cinta_seg_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_cod_cinta_seg_val'], "text"),
                       GetSQLValueString(isset($_POST['cod_ppal_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_cod_ppal_val'], "text"),
                       GetSQLValueString(isset($_POST['cod_inf_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_cod_inf_val'], "text"),
                       GetSQLValueString(isset($_POST['cod_liner_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_cod_liner_val'], "text"),
                       GetSQLValueString(isset($_POST['cod_bols_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_cod_bols_val'], "text"),
                       GetSQLValueString(isset($_POST['cod_otro_val']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['observ_cod_otro_val'], "text"),
                       GetSQLValueString($_POST['otras_observ_val'], "text"));

  mysql_select_db($database_conexion1, $conexion1);
  $Result1 = mysql_query($insertSQL, $conexion1) or die(mysql_error());

  $insertGoTo = "validacion_vista.php?id_val=" . $_POST['id_val'] . "";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_conexion1, $conexion1);
$query_ultimo = "SELECT * FROM validacion ORDER BY id_val DESC";
$ultimo = mysql_query($query_ultimo, $conexion1) or die(mysql_error());
$row_ultimo = mysql_fetch_assoc($ultimo);
$totalRows_ultimo = mysql_num_rows($ultimo);

$colname_referencia = "-1";
if (isset($_GET['id_ref'])) {
  $colname_referencia = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_referencia = sprintf("SELECT * FROM Tbl_referencia WHERE id_ref = %s", $colname_referencia);
$referencia = mysql_query($query_referencia, $conexion1) or die(mysql_error());
$row_referencia = mysql_fetch_assoc($referencia);
$totalRows_referencia = mysql_num_rows($referencia);

$colname_revision = "-1";
if (isset($_GET['id_ref'])) {
  $colname_revision = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_revision = sprintf("SELECT * FROM revision WHERE id_ref_rev = %s", $colname_revision);
$revision = mysql_query($query_revision, $conexion1) or die(mysql_error());
$row_revision = mysql_fetch_assoc($revision);
$totalRows_revision = mysql_num_rows($revision);

$colname_validacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_validacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_validacion = sprintf("SELECT * FROM validacion WHERE id_ref_val = %s", $colname_validacion);
$validacion = mysql_query($query_validacion, $conexion1) or die(mysql_error());
$row_validacion = mysql_fetch_assoc($validacion);
$totalRows_validacion = mysql_num_rows($validacion);

/*$colname_verificacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM validacion, verificacion WHERE validacion.id_ref_val = '%s' AND validacion.id_ref_val = verificacion.id_ref_verif AND verificacion.estado_arte_verif = '2' ", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);*/

$colname_verificacion = "-1";
if (isset($_GET['id_ref'])) {
  $colname_verificacion = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_verificacion = sprintf("SELECT * FROM verificacion WHERE id_ref_verif = %s AND estado_arte_verif = '2'", $colname_verificacion);
$verificacion = mysql_query($query_verificacion, $conexion1) or die(mysql_error());
$row_verificacion = mysql_fetch_assoc($verificacion);
$totalRows_verificacion = mysql_num_rows($verificacion);

$colname_egp = "-1";
if (isset($_GET['id_ref'])) {
  $colname_egp = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_egp = sprintf("SELECT * FROM Tbl_referencia, Tbl_egp WHERE Tbl_referencia.id_ref = %s AND Tbl_referencia.n_egp_ref = Tbl_egp.n_egp", $colname_egp);
$egp = mysql_query($query_egp, $conexion1) or die(mysql_error());
$row_egp = mysql_fetch_assoc($egp);
$totalRows_egp = mysql_num_rows($egp);

$colname_ficha_tecnica = "-1";
if (isset($_GET['id_ref'])) {
  $colname_ficha_tecnica = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_ficha_tecnica = sprintf("SELECT * FROM TblFichaTecnica WHERE id_ref_ft = %s", $colname_ficha_tecnica);
$ficha_tecnica = mysql_query($query_ficha_tecnica, $conexion1) or die(mysql_error());
$row_ficha_tecnica = mysql_fetch_assoc($ficha_tecnica);
$totalRows_ficha_tecnica = mysql_num_rows($ficha_tecnica);

$colname_certificacion_ref = "-1";
if (isset($_GET['id_ref'])) {
  $colname_certificacion_ref = (get_magic_quotes_gpc()) ? $_GET['id_ref'] : addslashes($_GET['id_ref']);
}
mysql_select_db($database_conexion1, $conexion1);
$query_certificacion_ref = sprintf("SELECT * FROM TblCertificacion WHERE TblCertificacion.idref='%s'",$colname_certificacion_ref);
$certificacion_ref = mysql_query($query_certificacion_ref, $conexion1) or die(mysql_error());
$row_certificacion_ref = mysql_fetch_assoc($certificacion_ref);
$totalRows_certificacion_ref = mysql_num_rows($certificacion_ref);



?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISADGE AC &amp; CIA</title>
<link href="css/formato.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/formato.js"></script>
</head>
<body>
<div align="center">
<table align="center" id="tabla"><tr><td align="center">
<div> 
<b class="spiffy"> 
<b class="spiffy1"><b></b></b>
<b class="spiffy2"><b></b></b>
<b class="spiffy3"></b>
<b class="spiffy4"></b>
<b class="spiffy5"></b></b>
<div class="spiffy_content">
<table id="tabla1"><tr>
<td colspan="2" align="center">
<img src="images/cabecera.jpg">
</td>
</tr>
<tr>
<td id="nombreusuario" width="20%"><?php echo $row_usuario['nombre_usuario']; ?></td>
<td id="cabezamenu"><ul id="menuhorizontal">
           <li><a href="<?php echo $logoutAction ?>" target="_top">CERRAR SESION</a></li>
           <li><a href="menu.php">MENU PRINCIPAL</a></li>
           <li><a href="referencia_copia.php">LISTADO REFERENCIA</a></li>
           </ul></td>
</tr>  
  <tr>
    <td colspan="2" align="center" id="linea1"><form method="POST" name="form1" action="<?php echo $editFormAction; ?>">
      <table id="tabla2">
        <tr id="tr1">
          <td id="codigo" width="25%">CODIGO: R2-F01</td>
          <td colspan="2" nowrap="nowrap" id="titulo2">PLAN DE DISE&Ntilde;O &amp; DESARROLLO</td>
          <td id="codigo" width="28%">VERSION: 4</td>
        </tr>
        
        <tr>
          <td rowspan="6" id="dato2"><img src="images/logoacyc.jpg"/></td>
          <td colspan="2" id="subtitulo">ADD - III. VALIDACION
            # 
            <input name="id_val" type="hidden" value="<?php
$num=$row_ultimo['id_val']+1; echo $num; ?>" />
            <?php echo $num; ?></td>
          <td id="dato2"><a href="referencias.php"><img src="images/a.gif" alt="REF'S ACTIVAS" title="REF'S ACTIVAS" border="0" style="cursor:hand;"/></a><a href="referencias_inactivas.php"><img src="images/i.gif" alt="REF'S INACTIVAS" title="REF'S INACTIVAS" border="0" style="cursor:hand;"/></a><a href="revision.php"><img src="images/r.gif" alt="REVISIONES" title="REVISIONES" border="0" style="cursor:hand;" /></a><a href="verificacion.php"><img src="images/v.gif" alt="VERIFICACIONES" title="VERIFICACIONES" border="0" style="cursor:hand;" /></a><a href="validacion.php"><img src="images/v.gif" alt="VALIDACIONES" title="VALIDACIONES" border="0" style="cursor:hand;" /></a><a href="ficha_tecnica.php"><img src="images/f.gif" alt="FICHA TECNICA" title="FICHA TECNICA" border="0" style="cursor:hand;" /></a>
          <?php if($row_certificacion['idcc']=='') { ?>
          <a href="certificacion_add.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="ADD CERTIFICACION" title="ADD CERTIFICACION" border="0" style="cursor:hand;"></a><?php } else { ?><a href="certificacion_listado.php?id_ref=<?php echo $_GET['id_ref']; ?>" target="new"><img src="images/c.gif" alt="CERTIFICACIONES" title="CERTIFICACIONES" border="0" style="cursor:hand;">
          </a><?php } ?></td>
        </tr>
        <tr id="linea1">
          <td id="fuente1">FECHA</td>
          <td colspan="2" id="fuente1">RESPONSABLE</td>
          </tr>
        <tr>
          <td id="dato1"><input type="text" name="fecha_val" value="<?php echo date("Y-m-d"); ?>" size="10" /></td>
          <td colspan="2" id="dato1"><input type="text" name="responsable_val" value="<?php echo $row_usuario['nombre_usuario']; ?>" size="30" /></td>
          </tr>
        <tr>
          <td nowrap id="dato1">REF : <input name="id_ref_val" type="hidden" value="<?php echo $row_referencia['id_ref']; ?>" /><a href="referencia_bolsa_vista.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&cod_ref= <?php echo $row_referencia['cod_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none;" ><strong><?php echo $row_referencia['cod_ref']; ?> - <?php echo $row_validacion['version_val']; ?></strong></a><?php if ($row_referencia['version_ref'] != $row_validacion['version_val']) echo "<span class='rojo_normal'>"." Cambiar a vers: ".$row_referencia['version_ref']."</spam>"?></td>
          <td id="dato1"><!--EGP : <a href="egp_bolsa_vista.php?n_egp=<?php// echo $row_referencia['n_egp_ref']; ?>" target="_top" style="text-decoration:none;" ><?php //echo $row_referencia['n_egp_ref']; ?></a>--></td>
          <td id="dato1">COTIZACION : <a href="cotizacion_g_bolsa_vista_ref_activas.php?N_cotizacion=<?php echo $row_referencia['n_cotiz_ref']; ?>&tipo=<?php echo $row_usuario['tipo_usuario']; ?>" target="_top" style="text-decoration:none;" ><?php echo $row_referencia['n_cotiz_ref']; ?></a></td>
        </tr>
        <tr>
          <td id="dato1">REVISION <input name="id_rev_val" type="hidden" value="<?php echo $row_revision['id_rev']; ?>" />
:            <a href="revision_vista.php?id_rev=<?php echo $row_revision['id_rev']; ?>" target="_top" style="text-decoration:none;" ><?php echo $row_revision['id_rev']; ?></a></td>
          <td id="dato1">VERIFICACION : <a href="verificacion_vista.php?id_verif=<?php echo $row_verificacion['id_verif']; ?>" target="_top" style="text-decoration:none;" ><?php echo $row_verificacion['id_verif']; ?></a>  <a href="verificacion_referencia.php?id_ref=<?php echo $row_referencia['id_ref']; ?>"><img src="images/v.gif" alt="VERIFICACIONES X REF" border="0" style="cursor:hand;" ></a></td>
          <td id="dato1">CLIENTES : <a href="referencia_cliente.php?id_ref=<?php echo $row_referencia['id_ref']; ?>&cod_ref=<?php echo $row_referencia['cod_ref']; ?>"><img src="images/cliente.gif" alt="CLIENTES" border="0"></a></td>
        </tr>
        <tr>
          <td id="dato1"><strong><a href="ficha_tecnica_vista.php?n_ft=<?php echo $row_ficha_tecnica['n_ft']; ?>" style="text-decoration:none;"><?php echo $row_ficha_tecnica['cod_ft']; ?></a></strong></td>
          <td id="dato1">ARTE : <a href="javascript:verFoto('archivo/<?php echo $row_verificacion['userfile'];?>','610','490')" ><?php echo $row_verificacion['userfile']; ?></a></td>
          <td id="dato1">ORDEN PROD.  
            <input type="text" name="n_op_val" value="" size="5" /></td>
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
                        <td id="detalle1"><input name="ancho_val" type="checkbox" id="ancho_val"<?php if ($row_validacion['ancho_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['ancho_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> /> 
                          Ancho</td>
                        <td id="detalle2"><input name="observ_ancho_val" type="text" id="observ_ancho_val" value="<?php echo $row_validacion['observ_ancho_val'] !='' ? $row_validacion['observ_ancho_val'] : $row_verificacion['observ_ancho_verif'];?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Largo: <?php echo $row_referencia_revision['largo_ref']; ?></td>
                        <td id="detalle1"><input name="altura_val" type="checkbox" id="altura_val"<?php if ($row_validacion['altura_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['largo_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> />
                          Largo</td>
                        <td id="detalle2"><input name="observ_altura_val" type="text" id="observ_altura_val" value="<?php echo $row_validacion['observ_altura_val'] !='' ? $row_validacion['observ_altura_val'] : $row_verificacion['observ_largo_verif'];?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Solapa: <?php echo $row_referencia_revision['solapa_ref']; ?></td>
                        <td id="detalle1"><input name="solapa_val" type="checkbox" id="solapa_val" <?php if ($row_validacion['solapa_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['solapa_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> />
                          Solapa</td>
                        <td id="detalle2"><input name="observ_solapa_val" type="text" id="observ_solapa_val" value="<?php echo $row_validacion['observ_solapa_val'] !='' ? $row_validacion['observ_solapa_val'] : $row_verificacion['observ_solapa_verif'];?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Calibre : <?php echo $row_referencia_revision['calibre_ref']; ?></td> 
                        <td id="detalle1"><input <?php if ($row_validacion['calibre_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; } ?> type="checkbox" name="calibre_val" value="1" >
                          Calibre(10%)</td>
                        <td colspan="2" id="detalle"><input type="text" name="observ_calibre_val" value="<?php echo $row_validacion['observ_calibre_val'];  ?>" size="50"></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Distribuci&oacute;n entre Logos y Bordes </td> 
                        <td nowrap="nowrap" id="detalle1"><input <?php if ($row_validacion['dist_logo_borde_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['dist_logo_borde_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="dist_logo_borde_val" type="checkbox" id="dist_logo_borde_val" />
        Distribuci&oacute;n entre Logos y Bordes</td>
                        <td id="detalle2"><input name="observ_dist_logo_borde_val" type="text" id="observ_dist_logo_borde_val" value="<?php echo $row_validacion['observ_dist_logo_borde_val'] !='' ? $row_validacion['observ_dist_logo_borde_val'] : $row_verificacion['observ_logo_borde_verif'];?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Revisi&oacute;n Textos</td> 
                        <td nowrap="nowrap" id="detalle1"><input <?php if ($row_validacion['rev_textos_verif'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['rev_textos_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="rev_texto_val" type="checkbox" id="rev_texto_val" />
        Revisi&oacute;n Textos </td>
                        <td id="detalle1"><input name="observ_rev_texto_val" type="text" id="observ_rev_texto_val" value="<?php echo $row_validacion['observ_rev_texto_val'] !='' ? $row_validacion['observ_rev_texto_val'] : $row_verificacion['observ_rev_texto_val'];?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Revisi&oacute;n Ortografica</td> 
                        <td id="detalle1"><input <?php if ($row_validacion['rev_ortog_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['rev_ortog_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="rev_ortog_val" type="checkbox" id="rev_ortog_val" />
                          Revisi&oacute;n Ortografica</td>
                        <td id="detalle1"><input name="observ_rev_ortog_val" type="text" id="observ_rev_ortog_val" value="<?php echo $row_validacion['observ_rev_ortog_val'] !='' ? $row_validacion['observ_rev_ortog_val'] : $row_verificacion['observ_rev_ortog_val']; ?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Bolsillo Portagu&iacute;a: <?php echo $row_referencia_revision['bolsillo_guia_ref']; ?></td> 
                        <td id="detalle1"><input <?php if ($row_validacion['rev_portag_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['rev_portag_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="rev_portag_val" type="checkbox" id="rev_portag_val" />Bolsillo Portaguia</td>
                        <td id="detalle1"><input type="text" name="observ_portag_val" value="<?php echo $row_validacion['observ_portag_val'] !='' ? $row_validacion['observ_portag_val'] : $row_verificacion['observ_portag_verif']; ;?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Tipo Extrusi&oacute;n: <?php echo $row_ref_egp['tipo_ext_egp']; ?></td> 
                        <td id="detalle1"><input <?php if ($row_validacion['rev_extru_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['rev_extru_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="rev_extru_val" type="checkbox" id="rev_extru_val" />
        Tipo Extrusi&oacute;n</td>
                        <td id="detalle1"><input type="text" name="observ_extru_val" value="<?php echo $row_validacion['observ_extru_val'] !='' ? $row_validacion['observ_extru_val'] : $row_verificacion['observ_extru_val']; ;?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Pigmento Exterior: <?php echo $row_ref_egp['pigm_ext_egp']; ?></td> 
                        <td id="detalle1"><input <?php if ($row_validacion['color_ext_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['color_ext_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="color_ext_val" type="checkbox" id="color_ext_val" />
                          Color Extrusi&oacute;n Exterior </td>
                        <td id="detalle1"><input name="observ_color_ext_val" type="text" id="observ_color_ext_val" value="<?php echo $row_validacion['observ_color_ext_val'] !='' ? $row_validacion['observ_color_ext_val'] : $row_verificacion['observ_color_ext_verif'];?>" size="50" /></td>
                      </tr>
                      <tr>
                        <td id="detalle1">Pigmento Interior: <?php echo $row_ref_egp['pigm_int_epg']; ?></td> 
                        <td id="detalle1"><input <?php if ($row_validacion['color_int_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['color_int_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="color_int_val" type="checkbox" id="color_int_val" />
                          Color Extrusi&oacute;n Interior </td>
                        <td id="detalle2"><input name="observ_color_int_val" type="text" id="observ_color_int_val" value="<?php echo $row_validacion['observ_color_int_val'] !='' ? $row_validacion['observ_color_int_val'] : $row_verificacion['observ_color_int_verif'];?>" size="50" /></td>
                      </tr>
                    </table></td>
                    </tr>
            <tr id="tr1">
              <td colspan="4" id="titulo1">VALIDACION DE COLORES DE IMPRESION (Cumple Si / No) </td>
            </tr>
            <tr id="tr1">
              <td id="detalle">VARIABLE</td>
              <td id="detalle">DATO EGP</td>
              <td id="detalle">PANTONE</td>
              <td colspan="2" id="detalle">OBSERVACIONES</td>
            </tr>
            <tr>
              <td id="detalle1">COLOR 1</td>
              <td id="detalle1"><input <?php if ($row_validacion['color1_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['1color_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="color1_val" >
                <?php echo $row_egp['color1_egp']; ?></td>
               <td id="detalle1"> <?php echo $row_egp['pantone1_egp']; ?></td>
              <td colspan="2" id="detalle">
                <input type="text" name="observ_color1_val" value="<?php echo $row_validacion['observ_color1_val'] !='' ? $row_validacion['observ_color1_val'] : $row_verificacion['observ_1color_verif']; ?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">COLOR 2</td>
              <td id="detalle1"><input <?php if ($row_validacion['color2_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['2color_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="color2_val"  >
                <?php echo $row_egp['color2_egp']; ?></td>
                <td id="detalle1"> <?php echo $row_egp['pantone2_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_color2_val" value="<?php echo $row_validacion['observ_color2_val'] !='' ? $row_validacion['observ_color2_val'] :  $row_verificacion['observ_2color_verif'];  ?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">COLOR 3</td>
              <td id="detalle1"><input <?php if ($row_validacion['color3_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['3color_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="color3_val"  >
                <?php echo $row_egp['color3_egp']; ?></td>
                <td id="detalle1"> <?php echo $row_egp['pantone3_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_color3_val" value="<?php echo $row_validacion['observ_color3_val'] !='' ? $row_validacion['observ_color3_val'] :  $row_verificacion['observ_3color_verif']; ?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">COLOR 4</td>
              <td id="detalle1"><input <?php if ($row_validacion['color4_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['4color_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="color4_val"  >
                <?php echo $row_egp['color4_egp']; ?></td>
                <td id="detalle1"> <?php echo $row_egp['pantone4_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_color4_val" value="<?php echo $row_validacion['observ_color4_val'] !='' ? $row_validacion['observ_color4_val'] : $row_verificacion['observ_4color_verif']; ?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">COLOR 5</td>
              <td id="detalle1"><input <?php if ($row_validacion['color5_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['5color_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="color5_val"  >
                <?php echo $row_egp['color5_egp']; ?></td>
                <td id="detalle1"> <?php echo $row_egp['pantone5_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observacion_color5_val" value="<?php echo $row_validacion['observacion_color5_val'] !='' ? $row_validacion['observacion_color5_val'] : $row_verificacion['observ_5color_verif'];?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">COLOR 6</td>
              <td id="detalle1"><input <?php if ($row_validacion['color6_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['6color_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="color6_val"  >
                <?php echo $row_egp['color6_egp']; ?></td>
                <td id="detalle1"> <?php echo $row_egp['pantone6_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observacion_color6_val" value="<?php echo $row_validacion['observacion_color6_val'] !='' ? $row_validacion['observacion_color6_val'] : $row_verificacion['observ_6color_verif'];?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">COLOR 7</td>
              <td id="detalle1"><input <?php if ($row_validacion['color7_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['7color_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="color7_val"  >
                <?php echo $row_egp['color7_egp']; ?></td>
                <td id="detalle1"> <?php echo $row_egp['pantone7_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observacion_color7_val" value="<?php echo $row_validacion['observacion_color7_val'] !='' ? $row_validacion['observacion_color7_val'] : $row_verificacion['observ_7color_verif'];?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">COLOR 8</td>
              <td id="detalle1"><input <?php if ($row_validacion['color8_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['8color_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="color8_val"  >
                <?php echo $row_egp['color8_egp']; ?></td>
                <td id="detalle1"> <?php echo $row_egp['pantone8_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observacion_color8_val" value="<?php echo $row_validacion['observacion_color8_val'] !='' ? $row_validacion['observacion_color8_val'] : $row_verificacion['observ_8color_verif'];?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">Marca de Fotocelda </td>
              <td id="detalle1"><input <?php if ($row_validacion['marca_foto_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['marca_foto_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="marca_foto_val"  ></td>
              <td id="detalle1"> </td>
              <td colspan="2" id="detalle">
                <input type="text" name="observ_marca_foto_val" value="<?php echo $row_validacion['observ_marca_foto_val'] !='' ? $row_validacion['observ_marca_foto_val'] :  $row_verificacion['observ_marca_foto_verif'];  ?>" size="40"></td>
            </tr>
            <tr id="tr1">
              <td colspan="4" id="titulo1">VALIDACION DE NUMERACION (Cumple Si / No) </td>
            </tr>
            <tr id="tr1">
              <td id="detalle">POSICIONES</td>
              <td id="detalle">DATO EGP</td>
              <td colspan="2" id="detalle">OBSERVACIONES</td>
            </tr>
            <tr>
              <td id="detalle1">Solapa TR </td>
              <td id="detalle1"><input <?php if ($row_validacion['num_tal_rec_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['alt_tal_rec_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="num_tal_rec_val"  >
                <?php echo $row_egp['tipo_solapatr_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_num_tal_rec_val" value="<?php echo $row_validacion['observ_num_tal_rec_val'] !='' ? $row_validacion['observ_num_tal_rec_val'] : $row_verificacion['observ_alt_tal_rec_verif'];?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">Cinta de Seguridad</td>
              <td id="detalle1"><input <?php if ($row_validacion['num_cinta_seg_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['alt_cinta_seg_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?>type="checkbox" name="num_cinta_seg_val"  >
                <?php echo $row_egp['tipo_cinta_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_num_cinta_seg_val" value="<?php echo $row_validacion['observ_num_cinta_seg_val'] !='' ? $row_validacion['observ_num_cinta_seg_val'] : $row_verificacion['observ_alt_cinta_seg_verif'];?>" size="40"></td>
            </tr> 
            <tr>
              <td id="detalle1">Principal</td>
              <td id="detalle1"><input <?php if ($row_validacion['num_ppal_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['alt_ppal_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?>type="checkbox" name="num_ppal_val"  >
                <?php echo $row_egp['tipo_principal_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_num_ppal_val" value="<?php echo $row_validacion['observ_num_ppal_val'] !='' ? $row_validacion['observ_num_ppal_val'] : $row_verificacion['observ_alt_ppal_verif']; ?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">Inferior</td>
              <td id="detalle1"><input <?php if ($row_validacion['num_inf_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['alt_inf_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="num_inf_val"  >
                <?php echo $row_egp['tipo_inferior_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_num_inf_val" value="<?php echo $row_validacion['observ_num_inf_val'] !='' ? $row_validacion['observ_num_inf_val'] : $row_verificacion['observ_alt_inf_verif'];  ?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">Liner</td>
              <td id="detalle1"><input <?php if ($row_validacion['num_liner_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['alt_liner_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="num_liner_val" type="checkbox" id="num_liner_val" >
                <?php echo $row_egp['tipo_liner_egp']; ?></td>
              <td colspan="2" id="detalle"><input name="observ_num_liner_val" type="text" id="observ_num_liner_val" value="<?php  echo $row_validacion['observ_num_liner_val'] !='' ? $row_validacion['observ_num_bols_val'] : $row_verificacion['observ_alt_liner_verif'];?>" size="40" /></td>
            </tr>
            <tr>
              <td id="detalle1">Bolsillo</td>
              <td id="detalle1"><input <?php if ($row_validacion['num_bols_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['alt_bols_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> name="num_bols_val" type="checkbox" id="num_bols_val" >
                <?php echo $row_egp['tipo_bols_egp']; ?></td>
              <td colspan="2" id="detalle"><input name="observ_num_bols_val" type="text" id="observ_num_bols_val" value="<?php echo $row_validacion['observ_num_bols_val'] !='' ? $row_validacion['observ_num_otro_val'] : $row_verificacion['observ_alt_bols_verif']; ?>" size="40" /></td>
            </tr>
            <tr>
              <td id="detalle1"><?php echo $row_egp['tipo_nom_egp']; ?>
                <label for="alt_nom_verif"></label>
                <input type="hidden" name="num_otro_val" id="num_otro_val" value="<?php echo $row_validacion['num_otro_val']; ?>"></td>
              <td id="detalle1"><input name="num_otro_val" <?php if ($row_validacion['num_otro_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['alt_otro_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" id="num_otro_val" >
                <?php echo $row_egp['tipo_otro_egp']; ?></td>
              <td colspan="2" id="detalle"><input name="observ_num_otro_val" type="text" value="<?php echo $row_validacion['observ_num_otro_val'] !='' ? $row_validacion['observ_num_fecha_val'] : $row_verificacion['observ_alt_otro_verif']; ?>" size="40" /></td>
            </tr>
            <tr>
              <td id="detalle1">Fecha de Caducidad</td>
              <td id="detalle1"><input <?php if (!(strcmp($row_validacion['num_fecha_cad_val'],1))) {echo "checked=\"checked\"";} ?> type="checkbox" name="num_fecha_cad_val" value="1"  ></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_num_fecha_val" value="<?php echo $row_validacion['observ_num_fecha_val']; ?>" size="40"></td>
            </tr>
            <tr id="tr1">
              <td colspan="4" id="titulo1">VALIDACION DE CODIGO DE BARRAS (Si / No) </td>
            </tr>
            <tr id="tr1">
              <td id="detalle">POSICIONES</td>
              <td id="detalle">DATO EGP</td>
              <td colspan="2" id="detalle">OBSERVACIONES</td>
            </tr>
            <tr>
              <td id="detalle1">Solapa TR </td>
              <td id="detalle1"><input <?php if ($row_validacion['cod_tal_rec_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['form_tal_rec_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="cod_tal_rec_val"  >
                <?php echo $row_egp['cb_solapatr_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_cod_tal_rec_val" value="<?php echo $row_validacion['observ_cod_tal_rec_val'] !='' ? $row_validacion['observ_cod_tal_rec_val'] : $row_verificacion['observ_form_tal_rec_verif'];?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">Cinta de Seguridad</td>
              <td id="detalle1"><input <?php if ($row_validacion['cod_cinta_seg_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['form_cinta_seg_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="cod_cinta_seg_val"  >
                <?php echo $row_egp['cb_cinta_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_cod_cinta_seg_val" value="<?php echo $row_validacion['observ_cod_cinta_seg_val'] !='' ? $row_validacion['observ_cod_cinta_seg_val'] : $row_verificacion['observ_form_cinta_seg_verif']; ?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">Principal</td>
              <td id="detalle1"><input <?php if ($row_validacion['cod_ppal_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['form_ppal_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="cod_ppal_val"  >
                <?php echo $row_egp['cb_principal_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_cod_ppal_val" value="<?php echo $row_validacion['observ_cod_ppal_val'] !='' ? $row_validacion['observ_cod_ppal_val'] : $row_verificacion['observ_form_ppal_verif']; ?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">Inferior</td>
              <td id="detalle1"><input <?php if ($row_validacion['cod_inf_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['form_inf_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" name="cod_inf_val"  >
                <?php echo $row_egp['cb_inferior_egp']; ?></td>
              <td colspan="2" id="detalle"><input type="text" name="observ_cod_inf_val" value="<?php echo $row_validacion['observ_cod_inf_val'] !='' ? $row_validacion['observ_cod_inf_val'] : $row_verificacion['observ_form_inf_verif'];?>" size="40"></td>
            </tr>
            <tr>
              <td id="detalle1">Liner</td>
              <td id="detalle1"><input <?php if ($row_validacion['cod_liner_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['form_liner_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?>  name="cod_liner_val" type="checkbox" >
                <?php echo $row_egp['cb_liner_egp']; ?></td>
              <td colspan="2" id="detalle"><input name="observ_cod_liner_val" type="text" value="<?php echo $row_validacion['observ_cod_liner_val'] !='' ? $row_validacion['observ_cod_liner_val'] : $row_verificacion['observ_form_liner_verif']; ?>" size="40" /></td>
            </tr>
            <tr>
              <td id="detalle1">Bolsillo</td>
              <td id="detalle1"><input <?php if ($row_validacion['cod_bols_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['form_bols_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?>  name="cod_bols_val" type="checkbox" >
                <?php echo $row_egp['cb_bols_egp']; ?></td>
              <td colspan="2" id="detalle"><input name="observ_cod_bols_val" type="text" value="<?php echo $row_validacion['observ_cod_bols_val'] !='' ? $row_validacion['observ_cod_bols_val'] : $row_verificacion['observ_form_bols_verif']; ?>" size="40" /></td>
            </tr>
            <tr>
              <td id="detalle1"><?php echo $row_egp['tipo_nom_egp']; ?>
                </td>
              <td id="detalle1"><input name="cod_otro_val" <?php if ($row_validacion['cod_otro_val'] =='1') {echo "checked=\"checked\"" . "value=\"1\""; }else if ($row_verificacion['form_otro_verif']=='1') {echo "checked=\"checked\"" . "value=\"1\"" ; } ?> type="checkbox" id="cod_otro_val" ><?php echo $row_egp['cb_otro_egp']; ?></td>
              <td colspan="2" id="detalle"><input name="observ_cod_otro_val" type="text" value="<?php echo $row_validacion['observ_cod_otro_val'] !='' ? $row_validacion['observ_cod_otro_val'] : $row_verificacion['observ_form_otro_verif'];?>" size="40" /></td>
              </tr>          
        <tr id="tr1">
          <td colspan="4" id="titulo1">OBSERVACIONES GENERALES </td>
          </tr>
        <tr>
          <td colspan="4" id="detalle1">1. Se debe de dejar una muestra de la bolsa terminada para archivo de producci&oacute;n y calidad. </td>
          </tr>
        <tr id="tr1">
          <td colspan="4" id="titulo1">Otras Observaciones</td>
          </tr>
        <tr>
          <td colspan="4" id="dato1"><textarea name="otras_observ_val" cols="80" rows="2"><?php echo $row_validacion['otras_observ_val'] != '' ? $row_verificacion['observacion_verif']: $row_verificacion['observacion_verif'];?> </textarea></td>
          </tr>
        <tr>
          <td colspan="4" id="dato2"><input type="hidden" name="version_val" id="version_val" value="<?php echo $row_referencia['version_ref']; ?>"><input type="submit" value="ADD VALIDACION"></td>
          </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1">
    </form>
	</td>
  </tr></table>
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

mysql_free_result($ultimo);

mysql_free_result($referencia);

mysql_free_result($revision);

mysql_free_result($verificacion);

mysql_free_result($egp);

mysql_free_result($ficha_tecnica);
?>
